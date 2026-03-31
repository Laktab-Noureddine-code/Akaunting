
require('./../../bootstrap');
import Vue from 'vue';
import DashboardPlugin from './../../plugins/dashboard-plugin';
import Global from './../../mixins/global';
import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';
Vue.use(DashboardPlugin);
const app = new Vue({
    el: '#main-body',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('account'),
            bulk_action: new BulkAction('accounts'),
        }
    },
});
