/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');
require ('../assets/js/datepicker.min.js');
require ('../assets/js/dropify.min.js');
require ('../assets/js/select2.min.js');
require ('../assets/js/printThis.js');
window.Vue = require('vue');

// Gates

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);




// Moment 

import moment from 'moment';



// Sweet alert

import Swal from 'sweetalert2';

window.Swal = Swal;


const toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

window.toast = toast;




// Language Change


import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueInternationalization);

// or however you determine your current app locale

window.i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});



//  Changing Date to Text

Vue.filter('upText',function(text) {
    return text.charAt(0).toUpperCase()+text.slice(1);
});
Vue.filter('myDate', function (date) {
  return moment(date).format('MMMM Do YYYY')
})


window.Fire = new Vue();




// alert

import { Form, HasError, AlertError } from 'vform'

window.Form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)





//  Progress Bar


import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})




//  Routes


import VueRouter from 'vue-router'
Vue.use(VueRouter)

import routes from './routes';

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })






// Not Found Page

  Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
  );


  // pagination
  Vue.component('pagination', require('laravel-vue-pagination'));
  


  // Select2
  import vSelect from 'vue-select';
  Vue.component('v-select', vSelect);

  import Multiselect from 'vue-multiselect'

  // register globally
  Vue.component('multiselect', Multiselect)
  


// Barcode 

  import VueBarcode from 'vue-barcode';
  Vue.component('barcode', VueBarcode);


  //  Main layout
 import App from './App.vue';



// Qr code

import Vue from 'vue';
import VueQrcode from '@chenfengyuan/vue-qrcode';

Vue.component(VueQrcode.name, VueQrcode);




import Datepicker from 'vuejs-datepicker';

Vue.component('date-picker',Datepicker);

window.DateFormat = function formatDate() {
  var d = new Date(),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2) 
      month = '0' + month;
  if (day.length < 2) 
      day = '0' + day;

  return [year, month, day].join('-');
};



Vue.use(require('vue-shortkey'));


// Chart

import Chartkick from 'vue-chartkick'
import Chart from 'chart.js'

Vue.use(Chartkick.use(Chart))



new Vue({
  router,
  i18n,
  render: h => h(App),
}).$mount('#app');
