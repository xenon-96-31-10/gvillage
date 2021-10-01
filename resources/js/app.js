/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vuelidate from 'vuelidate';
import VueTheMask from 'vue-the-mask';
import VueDatePicker from '@mathieustan/vue-datepicker';
import VueCompositionAPI from '@vue/composition-api'
import Multiselect from '@vueform/multiselect/dist/multiselect.vue2.js'
import VueDadata from 'vue-dadata'
import DadataSuggestions from 'vue-dadata-suggestions'


Vue.use(Vuelidate);
Vue.use(VueTheMask)
Vue.use(VueDatePicker, {
  lang: 'ru'
});
Vue.use(VueCompositionAPI)
Vue.component('Multiselect', Multiselect)
Vue.use(VueDadata)
Vue.use(DadataSuggestions,{
  token: '67fd1083a94980b339851baf73f373e31510c813'
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('login-component', require('./components/auth/LoginComponent.vue').default);
Vue.component('reset-link-component', require('./components/auth/ResetLinkComponent.vue').default);
Vue.component('reset-component', require('./components/auth/ResetComponent.vue').default);
Vue.component('register-user-component', require('./components/users/RegisterComponent.vue').default);
Vue.component('register-owner-component', require('./components/auth/RegisterOwnerComponent.vue').default);

Vue.component('create-pass-component', require('./components/passes/CreatePassComponent.vue').default);

Vue.component('profile-cars-component', require('./components/cars/ProfileCarsComponent.vue').default);
Vue.component('create-car-component', require('./components/cars/CreateCarComponent.vue').default);
Vue.component('edit-car-component', require('./components/cars/EditCarComponent.vue').default);

Vue.component('fast-create-profile-component', require('./components/profiles/FastCreateProfileComponent.vue').default);

Vue.component('profile-documents-component', require('./components/documents/ProfileDocumentsComponent.vue').default);

Vue.component('profile-mechanizms-component', require('./components/mechanizms/ProfileMechanizmsComponent.vue').default);
Vue.component('create-mechanizm-component', require('./components/mechanizms/CreateMechanizmComponent.vue').default);
Vue.component('edit-mechanizm-component', require('./components/mechanizms/EditMechanizmComponent.vue').default);
Vue.component('passes-component', require('./components/passes/PassesComponent.vue').default);
Vue.component('pass-report-component', require('./components/passes/PassReportComponent.vue').default);
Vue.component('pass-component', require('./components/passes/PassComponent.vue').default);
Vue.component('search-item-component', require('./components/SearchItemComponent.vue').default);
Vue.component('multisearch-item-component', require('./components/SearchMultiSelectComponent.vue').default);


Vue.component('personal-account-component', require('./components/accounts/PersonalAccountComponent.vue').default);
Vue.component('create-account-component', require('./components/accounts/CreateAccountComponent.vue').default);
Vue.component('refill-account-component', require('./components/accounts/RefillAccountComponent.vue').default);
Vue.component('report-account-component', require('./components/accounts/ReportAccountComponent.vue').default);
Vue.component('notifications-component', require('./components/notifications/NotificationsComponent.vue').default);


Vue.component('users-component', require('./components/users/UsersComponent.vue').default);
Vue.component('update-user-component', require('./components/users/UpdateComponent.vue').default);

Vue.component('control-profiles-component', require('./components/profiles/ControlProfilesComponent.vue').default);
Vue.component('profile-component', require('./components/profiles/ProfileComponent.vue').default);
Vue.component('profiles-component', require('./components/profiles/ProfilesComponent.vue').default);
Vue.component('view-profiles-component', require('./components/profiles/ViewProfilesComponent.vue').default);
Vue.component('profile-edit-component', require('./components/profiles/EditComponent.vue').default);
Vue.component('profile-create-component', require('./components/profiles/CreateProfileComponent.vue').default);
Vue.component('profile-key-component', require('./components/profiles/KeyComponent.vue').default);
Vue.component('profile-show-component', require('./components/profiles/ShowComponent.vue').default);

Vue.component('pass-rate-plans-component', require('./components/passRates/PlansComponent.vue').default);
Vue.component('create-rate-plan-component', require('./components/passRates/CreateRatePlanComponent.vue').default);
Vue.component('show-rate-plan-component', require('./components/passRates/ShowRatePlanComponent.vue').default);
Vue.component('edit-rate-plan-component', require('./components/passRates/EditRatePlanComponent.vue').default);


Vue.component('control-projects-component', require('./components/projects/ControlProjectsComponent.vue').default);
Vue.component('project-show-component', require('./components/projects/ShowComponent.vue').default);
Vue.component('project-edit-component', require('./components/projects/EditComponent.vue').default);
Vue.component('project-create-component', require('./components/projects/CreateComponent.vue').default);

Vue.component('control-guardposts-component', require('./components/guardPosts/ControlGuardPostsComponent.vue').default);
Vue.component('view-guardposts-component', require('./components/guardPosts/ViewComponent.vue').default);
Vue.component('guardpost-show-component', require('./components/guardPosts/ShowComponent.vue').default);
Vue.component('guardpost-edit-component', require('./components/guardPosts/EditComponent.vue').default);
Vue.component('guardpost-create-component', require('./components/guardPosts/CreateComponent.vue').default);

Vue.component('control-projectgroups-component', require('./components/projectGroups/ControlProjectGroupsComponent.vue').default);
Vue.component('projectgroup-show-component', require('./components/projectGroups/ShowComponent.vue').default);
Vue.component('projectgroup-edit-component', require('./components/projectGroups/EditComponent.vue').default);
Vue.component('projectgroup-create-component', require('./components/projectGroups/CreateComponent.vue').default);

Vue.component('roles-component', require('./components/roles/RolesComponent.vue').default);

Vue.component('control-items-component', require('./components/ControlItemsComponent.vue').default);
Vue.component('Loader', require('./components/LoaderComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
$(function () {
  $('[data-bs-toggle="popover"]').popover()
});
$(document).ready(function(){

});
