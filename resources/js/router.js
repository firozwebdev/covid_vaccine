import { createRouter, createWebHistory } from 'vue-router';
import Register from './components/Register.vue';
import Search from './components/Search.vue';
import SearchStatus from './components/SearchStatus.vue';
import Schedule from './components/Schedule.vue';


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
    component: SearchStatus, 
    props: true, 
  },
  {
    path: '/schedule',
    name: 'schedule',
    component: Schedule,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
