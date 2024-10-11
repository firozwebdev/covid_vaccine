<template>
    <div class="search-status-container">
        <h1>Vaccination Status</h1>
        <div v-if="status">
            <p>Status: {{ status }}</p>
        </div>
        <div v-else>
            <p>Loading...</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['nid'], // Receive nid from the route
    data() {
        return {
            status: '',
        };
    },
    created() {
        this.fetchStatus();
    },
    methods: {
        fetchStatus() {
            axios.get(`/api/status/${this.nid}`)
                .then(response => {
                    this.status = response.data.status; // Adjust according to your API response
                })
                .catch(error => {
                    console.error("There was an error fetching the status:", error);
                });
        },
    },
};
</script>

<style scoped>
.search-status-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}
</style>
