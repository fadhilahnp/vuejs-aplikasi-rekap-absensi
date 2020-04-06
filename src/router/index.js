import Vue from 'vue'
import VueRouter from 'vue-router'
import PersonalAttendance from '../views/PersonalAttendance.vue'
import AllAttendance from '../views/AllAttendance.vue'
import Help from '../views/Help.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: PersonalAttendance
  },
  {
    path: '/personal-attendance',
    name: 'personal-attendance',
    component: PersonalAttendance
  },
  {
    path: '/all-attendance',
    name: 'all-attendance',
    component: AllAttendance
  },
  {
    path: '/help',
    name: 'help',
    component: Help
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
