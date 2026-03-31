
window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Content-Type'] = 'multipart/form-data';
import NProgress from "nprogress";
import 'nprogress/nprogress.css';
window.axios.interceptors.request.use(function (config) {
    NProgress.start();
    return config;
}, function (error) {
    console.log(error);
    return Promise.reject(error);
});
window.axios.interceptors.response.use(function (response) {
    NProgress.done();
    return response;
}, function (error) {
    NProgress.done();
    console.log(error);
    return Promise.reject(error);
});
