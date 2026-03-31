
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
            form: new Form('company'),
            bulk_action: new BulkAction('companies')
        }
    },
    methods: {
        onSubmit() {
            this.form.loading = true;
            if (this.form.country === "") {
                this.form.errors.set('country', [country_validation_required_message]);
                this.form.loading = false;
                return;
            }
            this.form.submit();
        },
        onChangeCurrency(currency_code) {
            if (! currency_code) {
                return;
            }
            if (! this.all_currencies.length) {
                let currency_promise = Promise.resolve(window.axios.get((url + '/settings/currencies')));
                currency_promise.then(response => {
                    if (response.data.success) {
                        this.all_currencies = response.data.data;
                    }
                    this.all_currencies.forEach(function (currency, index) {
                        if (currency_code == currency.code) {
                            this.currency = currency;
                            this.form.currency = currency.code;
                        }
                    }, this);
                })
                .catch(error => {
                    this.onChangeCurrency(currency_code);
                });
            } else {
                this.all_currencies.forEach(function (currency, index) {
                    if (currency_code == currency.code) {
                        this.currency = currency;
                        this.form.currency = currency.code;
                    }
                }, this);
            }
        },
    }
});
