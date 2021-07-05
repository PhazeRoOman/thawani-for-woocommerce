import Vue from "vue";
import App from "./component/App";
import VueI18n from 'vue-i18n';
import './css/style.css';
const messages = {
  en: {
    plugin: 'Thawani Gateway',
    page_title: 'Session',
    page_title_sufix: 'History',
    tab_title: 'Session History',
    result_per: 'Results per page',
    page: 'Page',
    view: 'View',
    created_at: 'Created At',
    expire_at: 'Expire At ',
    order_id: 'Client reference / Order ID',
    customer_info: 'Customer Infromation',
    payment_status: {
      title: 'Payment Status',
      paid: 'Paid',
      cancelled: 'Cancelled',
      unpaid: 'Unpaid'
    },
    guest: 'Guest',
    total_amount: 'Total Amount',
    next: 'Next',
    prev: 'Prev',
    close: 'close',
    session_info: ' Session infromation',
    session_id: 'Session ID',
    products: 'Products',
    product: {
      title: 'Product',
      price: 'Price',
      qty: 'Quantity'
    },
    developed: 'This plugin developed by Muhannad Al-Risi',
  },
  ar: {
    plugin: 'ثواني بوابة الدفع',
    page_title: 'Session',
    page_title_sufix: 'سجل',
    tab_title: 'Session سجل',
    result_per: 'النتائج كل صفحة',
    page: 'صفحة',
    view: 'عرض',
    created_at: 'تاريخ الانشاء',
    expire_at: 'تاريخ الانتهاء ',
    order_id: 'رقم الطلب',
    customer_info: 'بيانات الزبون',
    payment_status: {
      title: 'حالة الدفع',
      paid: 'تم الدفع',
      cancelled: 'ملغي',
      unpaid: 'قيد الدفع'
    },
    guest: 'زائر',
    total_amount: 'الإجمالي',
    next: 'التالي',
    prev: 'السابق',
    close: 'إغلق',
    session_info: ' Session بيانات',
    session_id: 'Session ID',
    products: 'المنتجات',
    product: {
      title: 'المنتج',
      price: 'السعر',
      qty: 'الكمية'
    },
    developed: 'تطوير مهند الريسي',
  }
}

Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: 'en',
  messages
});

new Vue({
  i18n,
  render: (h) => h(App),
}).$mount("#app");

// check if the language is arabic  
if (document.querySelector('html').lang === 'ar')
  i18n.locale = 'ar'
// console.log(typeof document.querySelector("html").lang)