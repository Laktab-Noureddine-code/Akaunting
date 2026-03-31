
require('../../bootstrap');
import Vue from 'vue';
import DashboardPlugin from '../../plugins/dashboard-plugin';
import Global from '../../mixins/global';
import Form from './../../plugins/form';
Vue.use(DashboardPlugin);
const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('import'),
        }
    }
});
