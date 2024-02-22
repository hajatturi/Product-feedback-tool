<template>
  <div>
    <h2>Submit Feedback</h2>
    <form @submit.prevent="submitFeedback">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" v-model="feedback.title" required>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <select class="form-control" id="category" v-model="feedback.category" required>
          <option value="">Select Category</option>
          <option value="bug">Bug Report</option>
          <option value="feature">Feature Request</option>
          <option value="improvement">Improvement</option>
        </select>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" rows="3" v-model="feedback.description" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
import axios from 'axios';
export default {
  data() {
    return {
      feedback: {
        title: '',
        category: '',
        description: ''
      }
    };
  },
  methods: {
    submitFeedback() {
          event.preventDefault();
                var app = this;
                var newFeedback = app.feedback;
                axios.post(process.env.VUE_APP_API_URL +'feedbacks', newFeedback)
                    .then(function (resp) {
                        app.$router.push({path: '/feedback'});
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create feedback");
                    });
    }
  }
};
</script>
