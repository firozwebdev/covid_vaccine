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
        <a href="/registration">Register Here</a>
      </p>
    </div>
    <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div> <!-- Display error message -->
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      nid: '',
      status: '',
      errorMessage: '', // New variable for error message
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
          this.status = response.data.status;
          this.errorMessage = ''; // Reset error message if successful
        })
        .catch(error => {
          // Handle 404 error specifically
          if (error.response && error.response.status === 404) {
            this.errorMessage = 'Not registered'; // Display message if not registered
            this.status = ''; // Reset status
          } else {
            console.error("There was an error checking the status:", error);
          }
        });
    },
    validateNID(nid) {
      // Check if the NID is exactly 10 digits
      return /^\d{10}$/.test(nid);
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
