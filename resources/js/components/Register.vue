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
                <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div> <!-- Display error message -->
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

        <!-- Success Message -->
        <div v-if="message" :class="status === 'registered' ? 'success-message' : 'warning-message'">
           {{ message }}
        </div>

        <!-- Link to Check Status if registration is successful -->
        <div v-if="status === 'registered'">
            <router-link :to="{ name: 'status', params: { nid: form.nid } }">
                Check your vaccination status here
            </router-link>
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
            isCenterFull: false,
            selectedCenter: null,
            errorMessage: '',
        };
    },
    created() {
        this.fetchVaccineCenters();
    },
    methods: {
        async fetchVaccineCenters() {
            try {
                const response = await axios.get('/api/vaccine-centers');
                this.vaccineCenters = response.data.data.vaccineCenters;
            } catch (error) {
                console.error("There was an error fetching vaccine centers:", error);
            }
        },
        checkCenterCapacity() {
            this.selectedCenter = this.vaccineCenters.find(center => center.id === this.form.vaccine_center_id);
            this.isCenterFull = this.selectedCenter && this.selectedCenter.current_registrations >= this.selectedCenter.daily_limit;
        },
        async registerUser() {
            if (this.isCenterFull) {
                alert('Please choose another vaccine center, as the selected one is full.');
                return;
            }

            this.errorMessage = ''; // Clear previous error messages

            // Validate NID
            if (!/^\d{10}$/.test(this.form.nid)) { // Check if NID is exactly 10 digits
                this.errorMessage = 'NID must be exactly 10 digits.';
                return; // Stop execution if validation fails
            }

            try {
                const response = await axios.post('/api/register', this.form);
                this.message = response.data.message;
                this.status = response.data.status;

                // Set the status URL to navigate to after registration
                if (response.data.status === 'registered') {
                    this.statusUrl = `/status/${this.form.nid}`; // Adjust according to your routes
                }
            } catch (error) {
                console.error("There was an error registering:", error);

                // Check for validation errors from the server
                if (error.response && error.response.data) {
                    if (error.response.data.nid && error.response.data.nid.length > 0) {
                        this.errorMessage = error.response.data.nid[0]; // Display the first error message for NID
                    } else {
                        this.errorMessage = error.response.data.message || "An unexpected error occurred. Please try again.";
                    }
                } else {
                    this.errorMessage = "An unexpected error occurred. Please try again."; // Fallback message
                }
            }
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

.error-message {
    color: red; /* Styling for the error message */
    margin-top: 5px;
}

a {
    color: blue;
    text-decoration: underline;
    margin-top: 10px;
}
</style>
