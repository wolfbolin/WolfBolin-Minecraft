// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import App from './App';
import axios from 'axios';
import router from './router';
import VueI18n from 'vue-i18n';
import 'echarts/lib/chart/bar';
import 'echarts/lib/chart/line';
import ECharts from 'vue-echarts';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import 'element-ui/lib/theme-chalk/display.css';

Vue.use(ElementUI);
Vue.component('v-chart', ECharts);

Vue.config.productionTip = false;
Vue.prototype.$http = axios;
Vue.prototype.$wb_host = "http://localhost:25600";

// 引入多语言支持
Vue.use(VueI18n);
const i18n = new VueI18n({
  locale: 'zh-CN',    // 语言标识, 通过切换locale的值来实现语言切换,this.$i18n.locale
  messages: {
    'zh-CN': require('./assets/lang/zh-cn'),
    'en-US': require('./assets/lang/en-us'),
  }
});

// 生成页面实例
new Vue({
  el: '#app',
  i18n,
  router,
  components: { App },
  template: '<App/>'
});
