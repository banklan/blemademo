/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VeeValidate from 'vee-validate';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import vuetify from './plugins/vuetify';
import VueModal from 'vue-js-modal';
import Routes from './routes';
import { store } from './store';
import './filters';
Vue.use(require('vue-moment'));
import VueChatScroll from 'vue-chat-scroll';

Vue.use(VueRouter);
Vue.use(VeeValidate);
Vue.use(VueModal);
Vue.use(VueChatScroll);

const router = new VueRouter({
    routes: Routes,
    mode: 'history'
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('product-organic', require('./components/OrganicProduct.vue').default);
Vue.component('mini-cart', require('./components/CartMini.vue').default);
Vue.component('my-cart', require('./components/MyCart.vue').default);
Vue.component('product-card', require('./components/ProductCard.vue').default);
Vue.component('product-search', require('./components/ProductSearch.vue').default);
Vue.component('soup-card', require('./components/SoupCard.vue').default);
Vue.component('service-card', require('./components/ServiceCard.vue').default);
Vue.component('nav-drawer-user', require('./components/User/NavDrawer.vue').default);
Vue.component('nav-drawer-admin', require('./components/Admin/NavDrawer.vue').default);
Vue.component('contact-us', require('./components/ContactUs.vue').default);
Vue.component('about-us', require('./components/AboutUs.vue').default);
Vue.component('how-it-works', require('./components/HowItWorks.vue').default);
Vue.component('confirm-checkout', require('./components/CheckoutConfirm.vue').default);
Vue.component('terms-conditions', require('./components/TermsAndCondtions.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify,
});
