import axios from "axios";
import NProgress from "nprogress";
axios.interceptors.request.use(function (config) {
    NProgress.start();
    return config;
}, function (error) {
    console.log(error);
    return Promise.reject(error);
});
axios.interceptors.response.use(function (response) {
    NProgress.done();
    return response;
}, function (error) {
    NProgress.done();
    console.log(error);
    return Promise.reject(error);
});
