/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue';
// Import Vuex
import Vuex from 'Vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        model: {},
    }
}) 

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('login-component', require('./components/Login.vue').default);
Vue.component('home-component', require('./components/Home.vue').default);
Vue.component('card-component', require('./components/Card.vue').default);
Vue.component('table-component', require('./components/Table.vue').default);
Vue.component('modal-component', require('./components/Modal.vue').default);
Vue.component('brand-component', require('./components/Brand.vue').default);
Vue.component('alert-component', require('./components/Alert.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter('formatDate', function(date) {
    if(!date) return ''
    
    date = date.split('T')[0]
    date = date.split('-')
    date = date[2] + '/' + date[1] + '/' + date[0]

    return date
})

const app = new Vue({
    el: '#app',
    store,
});
