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
    
    <!-- Status Display -->
    <div v-if="status">
      <p>Status: {{ status }}</p>

      <!-- Show scheduled date if available -->
      <p v-if="scheduledDate">Scheduled Date: {{ formattedScheduledDate  }}</p>

      <!-- Show registration link if not registered -->
      <p v-if="status === 'Not registered'">
        <router-link :to="{ name: 'registration' }">Register Here</router-link>
      </p>
    </div>

    <!-- Error message display -->
    <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      nid: '',           // National ID entered by user
      status: '',        // Vaccination status from API
      scheduledDate: '', // Scheduled date from API (if available)
      errorMessage: '',  // Error message to display
    };
  },
  computed: {
    formattedScheduledDate() {
      if (!this.scheduledDate.scheduled_date) return ''; // Return empty if no date

      const date = new Date(this.scheduledDate.scheduled_date);
      const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: 'numeric', 
        minute: 'numeric', 
        hour12: true // 12-hour format with AM/PM
      };
      return date.toLocaleString('en-US', options);
    },
  },
  methods: {
    checkStatus() {
      // Reset error and status before making a request
      this.errorMessage = '';
      this.status = '';
      this.scheduledDate = '';

      // Validate NID (must be exactly 10 digits)
      if (!this.validateNID(this.nid)) {
        this.errorMessage = 'NID must be exactly 10 digits.';
        return;
      }

      // Make an API call to check the vaccination status
      axios.get(`/api/status/${this.nid}`)
        .then(response => {
          this.status = response.data.status;       // Update status from API
          this.scheduledDate = response.data.scheduled_date || ''; // Set the scheduled date if it exists
          this.errorMessage = '';  // Clear error message
        })
        .catch(error => {
          // Handle specific cases for errors
          if (error.response && error.response.status === 404) {
            this.status = 'Not registered'; // Set status to not registered
            this.errorMessage = ''; // Clear any previous error message
          } else {
            console.error("There was an error checking the status:", error);
            this.errorMessage = 'An unexpected error occurred. Please try again.';
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
