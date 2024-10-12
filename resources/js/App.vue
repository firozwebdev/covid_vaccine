<!-- src/App.vue -->
<template>
    <v-app>
      <v-container>
        <router-link to="/">Register</router-link> |
        <router-link to="/search">Search</router-link>|
        <router-link to="/schedule">Schedule</router-link>
        <router-view /> <!-- Where components will be rendered based on route -->
      </v-container>
    </v-app>
  </template>
  
  <script>
  export default {
  };
  </script>
  
  <style scoped>
  /* Optional: Add some styling */
  </style>
  