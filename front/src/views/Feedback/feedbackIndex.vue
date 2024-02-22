<template>
  <div>
    <h2>Feedback List</h2>

    <router-link :to="{ name: 'submitfeedback' }" class="btn btn-primary">Submit Feedback</router-link>

    <table class="table mt-4">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Description</th>
          <th>User</th>
          <th>Actions</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(feedback, index) in feedbackList" :key="feedback.id">
          <td>{{ feedback.title }}</td>
          <td>{{ feedback.category }}</td>
          <td>{{ feedback.description }}</td>
          <td>{{ feedback.user.name }}</td>
          <td>
            <button class="btn btn-primary" @click="openCommentModal(index, feedback)">Add Comment</button>
          </td>
          <td>
            <router-link :to="{ name: 'feedbackdetails', params: { id: feedback.id } }" class="btn btn-primary">
              View Details
            </router-link>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal for adding comments -->
    <div class="modal" v-if="showCommentModal">
      <!-- Modal content -->
      <div class="modal-content">
        <span class="close" @click="closeCommentModal">&times;</span>
        <h3>Add Comment</h3>
       
        
       <form @submit.prevent="submitComment">
       <div class="form-group position-relative">
    
    <textarea class="form-control" id="comment" v-model="newComment" rows="3" @input="handleCommentInput"></textarea>
    
    <ul v-if="showSuggestions" class="suggestions-dropdown" :style="{ top: dropdownTop + 'px', left: dropdownLeft + 'px' }">
      <li v-for="user in suggestedUsers" :key="user.id" @click="mentionUser(user)">
        <span>@{{ '@' + user.name }}</span>
      </li>
    </ul>
  </div>
  <button type="submit" class="btn btn-primary mt-2">Submit</button>
</form>


      </div>
    </div>

   <nav aria-label="Page navigation" class="pagination-nav">
  <ul class="pagination">
    <paginate
      :page-count="pageCount"
      :prev-text="'Previous'"
      :next-text="'Next'"
      container-class="pagination"
      :click-handler="handlePageClick"
    ></paginate>
  </ul>
</nav>

  </div>
</template>

<script>
import axios from 'axios';
import Paginate from 'vuejs-paginate';

export default {
  components: {
    Paginate,
  },
data() {
  return {
    feedbackList: [],
    perPage: 10,
    currentPage: 1,
    showCommentModal: false,
    selectedFeedbackIndex: null,
    newComment: '',
    feedbackId: null,
    suggestedUsers: [], 
    showSuggestions: false, 
  };
},
  computed: {
    pageCount() {
      return Math.ceil(this.totalItems / this.perPage);
    },
  
   dropdownTop() {
    if (this.$refs.commentTextarea && this.$refs.commentGroup) {
      return this.$refs.commentTextarea.offsetTop + this.$refs.commentTextarea.offsetHeight;
    }
    return 0;
  },
  
  dropdownLeft() {
    if (this.$refs.commentTextarea && this.$refs.commentGroup) {
      return this.$refs.commentTextarea.offsetLeft;
    }
    return 0;
  },
  },
  mounted() {
    this.fetchFeedbackList();
    
  },
  methods: {
    fetchFeedbackList() {
      axios.get(process.env.VUE_APP_API_URL + 'feedbacks', {
        params: {
          page: this.currentPage,
        },
      })
      .then(response => {
        this.feedbackList = response.data.data;
        this.totalItems = response.data.meta.total; 
       
      })
      .catch(error => {
        console.error('Error fetching feedback list:', error);
        alert('Could not load feedback list');
      });
    },


    handlePageClick(page) {
      this.currentPage = page;
      this.fetchFeedbackList();
    },
    openCommentModal(index, feedback) {
      this.selectedFeedbackIndex = index;
      this.feedbackId = feedback.id;
      this.showCommentModal = true;
    },
    closeCommentModal() {
      this.showCommentModal = false;
      this.selectedFeedbackIndex = null;
      this.feedbackId = null;
      this.newComment = '';
    },
handleCommentInput() {
  console.log('Input detected');
  const mentionedUsernames = this.newComment.match(/@(\w+)/g);
  if (mentionedUsernames && mentionedUsernames.length > 0) {
    this.showSuggestions = true;
    axios.get(process.env.VUE_APP_API_URL + 'users')
      .then(resp => {
        if (Array.isArray(resp.data.users)) {
          this.suggestedUsers = resp.data.users.filter(user => mentionedUsernames.includes(`@${user.name}`));
        } else {
          console.error('Invalid response data format:', resp.data);
        }
      })
      .catch(error => {
        console.error('Error fetching suggested users:', error);
      });
  } else {
    this.showSuggestions = false;
    this.suggestedUsers = [];
  }
},





mentionUser(user) {
 
  const mentionedUsername = `@${user.name}`;
  this.newComment = this.newComment.replace(mentionedUsername, '');
  
  this.newComment += ` ${mentionedUsername} `;
  this.suggestedUsers = [];
 },

    submitComment() {
      axios.post(process.env.VUE_APP_API_URL + 'comment', { 
        content: this.newComment,
        feedback_id: this.feedbackId,
      })
      .then(() => {
        console.log('Comment added successfully');
        this.closeCommentModal();
        this.$router.push({ path: '/feedback' });
      })
      .catch(error => {
        console.error('Error adding comment:', error);
        alert('Could not add new comment!');
      });
    },
  },
};
</script>

<style>
/* Modal styles */
.modal {
  display: block !important; 
  position: fixed; 
  z-index: 1; 
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.4); 
}

/* Modal content styles */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; 
  padding: 20px;
  border: 1px solid #888;
  width: 80%; 
  max-width: 600px; 
}

/* Close button styles */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* Suggestions dropdown styles */
.suggestions-dropdown {

  max-width: 100px; 
  margin-top: -30px; 
  padding: 5px 10px;
  margin-left: 5px;
  list-style-type: none;
  z-index: 999; 

}

.suggestions-dropdown li {
  cursor: pointer;
}

.suggestions-dropdown li:hover {
  background-color: #f0f0f0;
}

.pagination-nav {
  display: flex;
  justify-content: center;
}


</style>
