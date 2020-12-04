<template>
  <div>
    <!-- header --->
    <section id="header" class="p-2 my-2">
      <div class="flex space-x-4">
        <div>
          <svg
            id="Layer_1"
            data-name="Layer 1"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="h-12 w-12"
            style="fill: #6fbf49"
          >
            <path
              class="cls-1"
              d="M11.52,9.93A1.71,1.71,0,1,1,9.8,8.23h0A1.72,1.72,0,0,1,11.52,9.93Z"
            />
            <path
              class="cls-1"
              d="M16,9.93a1.71,1.71,0,1,1-1.71-1.71h0A1.71,1.71,0,0,1,16,9.92Z"
            />
            <path
              class="cls-1"
              d="M13.59,5.89a1.71,1.71,0,1,1-1.71-1.71h0A1.72,1.72,0,0,1,13.59,5.89Z"
            />
            <path
              class="cls-1"
              d="M12,16.76A5.75,5.75,0,0,1,6.24,11H3.29a8.71,8.71,0,1,0,17.42.18V11H17.76A5.75,5.75,0,0,1,12,16.76Z"
            />
          </svg>
        </div>
        <div class="place-self-center">
          <h1 class="text-3xl text-thawani">Thawani GateWay Dashboard</h1>
        </div>
      </div>
    </section>

    <!-- navigation -->
    <div class="flex justify-between space-x-8 p-4">
      <div class="flex space-x-8">
        <div>
          <a
            href="#"
            @click="tabsToggle(1)"
            class="p-2 rounded-lg inline-block text-xl hover:underline hover:text-white"
            :class="{
              'bg-thawani text-white': tabs.session,
              ' border border-thawani text-thawani hover:text-thawani': !tabs.session,
            }"
          >
            Sessions
          </a>
        </div>
        <div>
          <a
            href="#"
            @click="tabsToggle(2)"
            class="p-2 rounded-lg inline-block text-xl hover:underline hover:text-white"
            :class="{
              'bg-thawani text-white': tabs.customer,
              ' border border-thawani text-thawani hover:text-thawani': !tabs.customer,
            }"
          >
            Customers
          </a>
        </div>
      </div>
      <div>
        <form action="#" method="post" @submit.prevent="get_sessions()">
          <div class="flex-1">
            <label for="limit" class="block text-gray-500 mb-1">page</label>
            <select
              name="limit"
              class="p-2 w-12 bg-transparent"
              v-model="page"
              @change="pageChange()"
            >
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <!-- content  -->
    <div v-if="tabs.session">
      <h1 class="my-1 font-bold text-2xl p-4 py-0">
        <span class="border-b-2 border-thawani pb-2">Session</span> List
      </h1>
    </div>
    <div v-if="tabs.customer">
      <h1 class="my-1 font-bold text-2xl p-4 py-0">
        <span class="border-b-2 border-thawani pb-2">Customers</span> List
      </h1>
    </div>
    <Sessions
      v-if="tabs.session"
      :sessions="sessionList"
      @show-sidebar="show"
    />
    <CustomerList
      v-if="tabs.customer"
      :customers="customerList"
      @show-sidebar="show"
    />
    <!-- side bar -->
    <SessionSidebar
      v-if="tabs.session"
      :session-list="sessionList"
      :session-index="selectedIndex"
      @hide-sidebar="hide"
    />
    <CustomerSidebar
      v-if="tabs.customer"
      :customer-list="customerList"
      :customer-index="selectedIndex"
      @hide-sidebar="hide"
    />
    <!-- footer  -->
    <div
      class="text-gray-500 bg-gray-100 shadow-sm p-3 rounded-mf flex space-x-4 container my-4 mx-auto"
    >
      <div>This plugin developed by Muhannad Al-Risi</div>
      <div>
        Found an issue please send it to email muhannad.alrisi@gmail.com
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import qs from "querystring";
// import popup from "./popup";
// import Popup from "./popup.vue";
import Sessions from "./Sessions";
import SessionSidebar from "./SessionSidebar";
import CustomerList from "./CustomerList";
import CustomerSidebar from "./CustomerSidebar";
// import Sessions from "./Sessions.vue";
let site_url = null;
const action_prefix = "thawani_gw";
export default {
  name: "App",
  components: {
    Sessions,
    SessionSidebar,
    CustomerList,
    CustomerSidebar,
  },
  data() {
    return {
      sessionList: null,
      customerList: null,
      page: 1,
      selectedIndex: -1,
      tabs: {
        session: true,
        customer: false,
      },
    };
  },
  mounted() {
    // console.log(document.querySelector("#thawani_url_admin"));
    site_url = document.querySelector("#thawani_url_admin").dataset.url;
    console.log(site_url);

    axios
      .post(
        site_url + "/wp-admin/admin-ajax.php",
        qs.stringify({
          action: `${action_prefix}_get_all_sessions`,
        })
      )
      .then((response) => {
        const { data } = JSON.parse(response.data);
        this.sessionList = data;
        console.log(this.sessionList);
        // console.log("sessiondata", this.sessionList.data);
      })
      .catch((err) => {
        console.log(err.response);
      });
  },
  methods: {
    get_sessions() {
      this.sessionList = null;
      axios
        .post(
          site_url + "/wp-admin/admin-ajax.php",
          qs.stringify({
            action: `${action_prefix}_get_all_sessions`,
            skip: this.page,
            limit: 10,
          })
        )
        .then((response) => {
          console.log(response);
          const { data } = JSON.parse(response.data);
          this.sessionList = data;
          console.log(this.sessionList);
          // console.log("sessiondata", this.sessionList.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_customers() {
      this.customerList = null;
      axios
        .post(
          site_url + "/wp-admin/admin-ajax.php",
          qs.stringify({
            action: `${action_prefix}_get_all_customers`,
            skip: this.page,
            limit: 10,
          })
        )
        .then((response) => {
          console.log(response);
          const { data } = JSON.parse(response.data);
          this.customerList = data;
          console.log(this.customerList);
          // console.log("sessiondata", this.sessionList.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    pageChange() {
      if (this.tabs.session) this.get_sessions();
      else this.get_customers();
    },
    tabsToggle(selected) {
      this.page = 1;
      if (selected == 1) {
        if (this.sessionList == null) {
          this.get_sessions();
        }
        this.tabs.session = true;
        this.tabs.customer = false;
      } else {
        if (this.customerList == null) {
          this.get_customers();
        }
        this.tabs.session = false;
        this.tabs.customer = true;
      }
    },
    order_id(session) {
      if (session.metadata == null) return "not found";
      if (session.metadata.order_id != undefined)
        return session.metadata.order_id;
      else if (session.metadata.order_id == null) return "not found";
      else return "not found";
    },
    price_format(price) {
      return (price / 1000).toLocaleString("en-IN", {
        minimumFractionDigits: 3,
        maximumFractionDigits: 3,
        style: "currency",
        currency: "OMR",
      });
    },
    show(data) {
      console.log("hello clicked", data);
      this.selectedIndex = data;
    },
    hide(data) {
      this.selectedIndex = data;
    },
  },
};
</script>
<style src='../css/style.css'></style>
