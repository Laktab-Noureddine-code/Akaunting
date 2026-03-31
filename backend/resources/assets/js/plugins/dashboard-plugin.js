
import './../polyfills';
import Notifications from './../components/NotificationPlugin';
import VeeValidate from 'vee-validate';
import GlobalComponents from './globalComponents';
import GlobalDirectives from './globalDirectives';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';
locale.use(lang);
export default {
    install(Vue) {
        Vue.use(GlobalComponents);
        Vue.use(GlobalDirectives);
        Vue.use(Notifications);
        Vue.use(VeeValidate, {
            fieldsBagName: 'veeFields',
            classes      : true,
            validity     : true,
            classNames   : {
                valid  : 'is-valid',
                invalid: 'is-invalid'
            }
        });
    }
};
