import { createRouter, createWebHistory } from 'vue-router';
import ExampleComponent from './components/ExampleComponent.vue';
import AnotherComponent from './components/AnotherComponent.vue'; // Add any additional components

const routes = [
    { path: '/', component: ExampleComponent },
    { path: '/another', component: AnotherComponent }  // Define more routes as needed
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
