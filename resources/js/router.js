import { createRouter, createWebHistory } from 'vue-router';
import Register from './components/Register.vue';
import Search from './components/Search.vue';
import SearchStatus from './components/SearchStatus.vue';


const routes = [
  {
    path: '/',
    name: 'registration',
    component: Register,
  },
  {
    path: '/search',
    name: 'search',
    component: Search,
  },
  {
    path: '/status/:nid',
    name: 'status',
    component: SearchStatus, // Your component for checking status
    props: true, // This allows you to pass the route params as props
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
