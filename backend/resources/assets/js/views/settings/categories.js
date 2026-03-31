
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
        Global,
    ],
    data: function () {
        return {
            form: new Form('category'),
            bulk_action: new BulkAction('categories'),
            categoriesBasedTypes: null,
            selected_type: true
        }
    },
    methods: {
        updateParentCategories(event) {
            if (event === '') {
                return;
            }
            if (typeof JSON.parse(this.form.categories)[event] === 'undefined') {
                this.categoriesBasedTypes = [];
                return;
            }
            if (this.form.parent_category_id) {
                this.form.parent_category_id = null;
                return;
            }
            this.selected_type = false;
            this.categoriesBasedTypes = JSON.parse(this.form.categories)[event];
        }
    }
});
