<template>

  <div class="feedback-details-container">
    <h2>Feedback Details</h2>

  <router-link to="/feedback" class="btn btn-primary mt-2">Back FeedBack List</router-link>
   
<!-- eslint-disable-next-line vue/no-unused-vars -->
    <div v-for="(feedback, _) in feedbackdetails" :key="feedback.id">
      <div>
        <strong>User Submited:</strong> {{ feedback.user.name }}
      </div>
      <div>
        <strong>Title:</strong> {{ feedback.title }}
      </div>
      <div>
        <strong>Category:</strong> {{ feedback.category }}
      </div>
      <div>
        <strong>Description:</strong> {{ feedback.description }}
      </div>
      <div>
        <h4>Comments</h4>
        <ul>
          <li v-for="(comment, index) in feedback.comments" :key="index">
            <div>
              <strong>User:</strong> {{ comment.user.name }}
            </div>
            <div>
              <strong>Date:</strong> {{ formatDate(comment.created_at) }}
            </div>
            <div>
              <strong>Content:</strong> {{ comment.content }}
            </div>
          </li>
        </ul>
      </div>
    </div>

  
  </div>
</template>

<script>
import axios from 'axios';
export default {

  data() {
    return {
      feedbackdetails: [],
      feedbackId:null,
    };
  },
  computed: {
   
  },
 
 mounted() {
    const app = this;
    let id = app.$route.params.id;
    axios.get(process.env.VUE_APP_API_URL + 'feedbacks/' + id)
      .then(function (resp) {
        app.feedbackdetails = resp.data;
        console.log(app.feedbackdetails);
      })
      .catch(function () {
        alert("Could not load your feedback");
      });


      
  },
  methods: {
  
 formatDate(dateString) {
    const date = new Date(dateString);

    return date.toLocaleDateString(); 
  }


  },
};
</script>
<style>
.feedback-details-container {
  max-width: 800px;
 
  padding: 20px;
}

.feedback-details-container ul {
  padding-left: 20px;
}

.btn {
  display: inline-block;
  margin-bottom: 10px;
}
</style>



