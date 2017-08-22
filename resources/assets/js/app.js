require('./bootstrap');
require('./ui');

var VueResource = require('vue-resource');
require('ammap3');

Vue.component('modify-users', require('./components/AdminModifyUsersComponent.vue'));
Vue.component('subscribe-button', require('./components/SubscribeButtonComponent.vue'));
Vue.component('report-project-button', require('./components/ReportProjectComponent.vue'));
Vue.component('report-fragment-button', require('./components/ReportFragmentComponent.vue'));
Vue.component('delete-report', require('./components/ModeratorDeleteReportButtonComponent.vue'));
Vue.component('messaging', require('./components/UserMessagingComponent.vue'));
Vue.component('set-volume-fragment', require('./components/SetVolumeFragmentComponent.vue'));
Vue.component('vue-slider', require('vue-slider-component'));

Vue.use(VueResource);

const app = new Vue({
    el: '#app'
});

