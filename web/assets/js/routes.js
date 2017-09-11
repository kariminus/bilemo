import App from './App.vue';

const Phone = resolve => {
    require.ensure(['./components/phone/Phone.vue'], () => {
        resolve(require('./components/phone/Phone.vue'));
    }, 'phone');
};
const PhoneDetail = resolve => {
    require.ensure(['./components/phone/PhoneDetail.vue'], () => {
        resolve(require('./components/phone/PhoneDetail.vue'));
    }, 'phone');
};

const PhoneStart = resolve => {
    require.ensure(['./components/phone/PhoneStart.vue'], () => {
        resolve(require('./components/phone/PhoneStart.vue'));
    }, 'phone');
};

export const routes = [
    { path: '', name: 'home', components: {
        default: App
    }, children: [
        { path: '', component: Phone },
        { path: '/phone/:slug', component: PhoneDetail, props: true }
        ]},
    { path: '*', redirect: '/' }
];