
require('./../../bootstrap');
import Vue from 'vue';
import DashboardPlugin from './../../plugins/dashboard-plugin';
import Global from './../../mixins/global';
import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';
Vue.use(DashboardPlugin);
const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('tax'),
            bulk_action: new BulkAction('taxes')
        }
    },
    methods: {
        onChangeTaxRate() {
            this.form.rate = this.form.rate.replace(',', '.');
        },
    }
});
