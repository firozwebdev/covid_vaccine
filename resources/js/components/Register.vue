<template>
    <div class="registration-container">
      <h1>Vaccination Registration</h1>
      <form @submit.prevent="registerUser">
        <div>
          <label for="name">Full Name:</label>
          <input type="text" v-model="form.name" required />
        </div>
        <div>
          <label for="nid">NID:</label>
          <input type="text" v-model="form.nid" required />
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="email" v-model="form.email" required />
        </div>
        <div>
          <label for="mobile">Mobile:</label> 
          <input type="text" v-model="form.mobile" required />
        </div>
        <div>
          <label for="vaccine_center">Vaccine Center:</label>
          <select v-model="form.vaccine_center_id" @change="checkCenterCapacity" required>
            <option v-for="center in vaccineCenters" :key="center.id" :value="center.id">
              {{ center.name }}
            </option>
          </select>
          <div v-if="isCenterFull" class="warning-message">
            This vaccine center is full ({{ selectedCenter.daily_limit }} registrations limit).
            Please choose another center.
          </div>
        </div>
        <button type="submit" :disabled="isCenterFull">Register</button>
      </form>
  
      <!-- Success or Already Registered Message -->
      <div v-if="message" :class="status === 'registered' ? 'success-message' : 'warning-message'">
        {{ message }}
      </div>
  
      <!-- Link to Status if user is already registered -->
      <div v-if="status === 'already_registered'">
        <a :href="statusUrl">Check your vaccination status here</a>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          name: '',
          nid: '',
          email: '',
          mobile: '', 
          vaccine_center_id: null,
        },
        vaccineCenters: [],
        message: '',
        status: '',
        statusUrl: '',
        isCenterFull: false,
        selectedCenter: null,
      };
    },
    created() {
      this.fetchVaccineCenters();
    },
    methods: {
      fetchVaccineCenters() {
        axios.get('/api/vaccine-centers')
          .then(response => {
            this.vaccineCenters = response.data.data.vaccineCenters;
          })
          .catch(error => {
            console.error("There was an error fetching vaccine centers:", error);
          });
      },
      checkCenterCapacity() {
        this.selectedCenter = this.vaccineCenters.find(center => center.id === this.form.vaccine_center_id);
        this.isCenterFull = this.selectedCenter && this.selectedCenter.current_registrations >= this.selectedCenter.daily_limit;
      },
      registerUser() {
        if (this.isCenterFull) {
          alert('Please choose another vaccine center, as the selected one is full.');
          return;
        }
  
        axios.post('/api/register', this.form)
          .then(response => {
            this.message = response.data.message;
            this.status = response.data.status;
          })
          .catch(error => {
            console.error("There was an error registering:", error);
          });
      },
    },
  };
  </script>
  
  <style scoped>
  .registration-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .success-message {
    color: green;
    margin-top: 10px;
  }
  
  .warning-message {
    color: orange;
    margin-top: 10px;
  }
  
  a {
    color: blue;
    text-decoration: underline;
    margin-top: 10px;
  }
  </style>
  