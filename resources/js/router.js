import { createRouter, createWebHistory } from 'vue-router';
import Register from './components/Register.vue';
import Search from './components/Search.vue';


const routes = [
  {
    path: '/',
    name: 'Register',
    component: Register,
  },
  {
    path: '/search',
    name: 'Search',
    component: Search,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
