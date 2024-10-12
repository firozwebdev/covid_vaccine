<template>
  <div class="user-list-container">
    <h1>Vaccination Users</h1>
    <table class="min-w-full border-collapse border border-gray-300">
      <thead>
        <tr>
          <th class="border border-gray-300 px-4 py-2">ID</th>
          <th class="border border-gray-300 px-4 py-2">Name</th>
          <th class="border border-gray-300 px-4 py-2">NID</th>
          <th class="border border-gray-300 px-4 py-2">Email</th>
          <th class="border border-gray-300 px-4 py-2">Status</th>
          <th class="border border-gray-300 px-4 py-2">Scheduled Date</th>
          <th class="border border-gray-300 px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users.data" :key="user.id">
          <td class="border border-gray-300 px-4 py-2">{{ user.id }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ user.name }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ user.nid }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ user.status }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ user.scheduled_date || 'Not Scheduled' }}</td>
          <td class="border border-gray-300 px-4 py-2">
            <button 
              @click="openScheduleModal(user)" 
              :disabled="user.status === 'Vaccinated'" 
              class="bg-blue-500 text-white px-4 py-2 rounded"
            >
              Schedule
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination-controls">
      <button @click="fetchUsers(users.prev_page_url)" :disabled="!users.prev_page_url" class="pagination-button">Previous</button>

      <span>Page {{ users.current_page }} of {{ users.last_page }}</span>

      <button @click="fetchUsers(users.next_page_url)" :disabled="!users.next_page_url" class="pagination-button">Next</button>
    </div>

    <!-- Schedule Modal -->
    <div v-if="selectedUser" class="modal-overlay">
      <div class="modal-content">
        <h2>Schedule Vaccination for {{ selectedUser.name }}</h2>
        <label for="schedule-date">Select Date</label>
        <input type="date" v-model="scheduleDate" id="schedule-date" class="input-field" />

        <label for="schedule-time">Select Time</label>
        <input type="time" v-model="scheduleTime" id="schedule-time" class="input-field" />

        <button @click="confirmSchedule" class="bg-green-500 text-white px-4 py-2 rounded">Confirm</button>
        <button @click="closeModal" class="bg-red-500 text-white px-4 py-2 rounded">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: {
        data: [], // User data with pagination information
      },
      selectedUser: null, // The user selected for scheduling
      scheduleDate: null, // Date selected for scheduling
      scheduleTime: null, // Time selected for scheduling
    };
  },
  created() {
    this.fetchUsers(); // Fetch users when the component is created
  },
  methods: {
    fetchUsers(pageUrl = '/api/users') {
      axios.get(pageUrl)
        .then(response => {
          this.users = response.data.users; // Assign the fetched users and pagination info
        })
        .catch(error => {
          console.error('There was an error fetching users:', error);
        });
    },
    openScheduleModal(user) {
      this.selectedUser = user; // Store the user to schedule
      this.scheduleDate = null;  // Reset date picker
      this.scheduleTime = null;  // Reset time picker
    },
    closeModal() {
      this.selectedUser = null; // Close the modal
    },
    confirmSchedule() {
      if (!this.scheduleDate || !this.scheduleTime) {
        alert('Please select both date and time.');
        return;
      }

      const scheduleDateTime = `${this.scheduleDate} ${this.scheduleTime}`;

      axios.post('/api/schedule', {
        user_id: this.selectedUser.id,  // Send user ID instead of the whole user object
        scheduled_date: scheduleDateTime
      })
      .then(response => {
        this.closeModal(); // Close the modal
        alert('Vaccination scheduled successfully!');
        this.fetchUsers(); // Refresh the user list after scheduling
        
      })
      .catch(error => {
        console.error('There was an error scheduling vaccination:', error);
      });
    }
  }
};
</script>

<style scoped>
.user-list-container {
  padding: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  background-color: #f3f4f6;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.input-field {
  margin: 10px 0;
  padding: 8px;
  width: 100%;
}

.pagination-controls {
  display: flex;
  justify-content: space-between;
  margin: 20px 0;
}

.pagination-button {
  background-color: #3b82f6;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.pagination-button:disabled {
  background-color: #e5e7eb;
  cursor: not-allowed;
}
</style>
