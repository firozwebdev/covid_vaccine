<template>
  <div class="search-container">
    <h1>Check Vaccination Status</h1>
    <input
      type="text"
      v-model="nid"
      placeholder="Enter NID"
      maxlength="10"
    />
    <button @click="checkStatus">Check Status</button>
    
    <div v-if="status">
      <p>Status: {{ status }}</p>
      <p v-if="status === 'Not registered'">
        <router-link :to="{ name: 'registration' }">Register Here</router-link>
      </p>
    </div>
    
    <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      nid: '',
      status: '',
      errorMessage: '',
    };
  },
  methods: {
    checkStatus() {
      // Reset error messages
      this.errorMessage = '';
      this.status = '';

      // Validate NID
      if (!this.validateNID(this.nid)) {
        this.errorMessage = 'NID must be exactly 10 digits.';
        return;
      }

      axios.get(`/api/status/${this.nid}`)
        .then(response => {
          this.status = response.data.status; // Ensure this is correctly set
          this.errorMessage = ''; // Reset error message if successful
        })
        .catch(error => {
          // Handle specific cases for errors
          if (error.response && error.response.status === 404) {
            this.status = 'Not registered'; // Set status to not registered
            this.errorMessage = ''; // Clear any previous error message
          } else {
            console.error("There was an error checking the status:", error);
            this.errorMessage = 'An unexpected error occurred. Please try again.'; // General error message
          }
        });
    },
    validateNID(nid) {
      return /^\d{10}$/.test(nid); // Ensure NID is exactly 10 digits
    },
  },
};
</script>

<style scoped>
.search-container {
  /* Add your styles here */
}
.error-message {
  color: red; /* Style for the error message */
}
</style>
