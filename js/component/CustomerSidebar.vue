<template>
  <div
    class="fixed right-0 top-0 w-1/3 bottom-0 h-screen bg-gray-100 text-thawani shadow-lg"
    v-if="customerIndex > -1"
  >
    <div class="text-white mt-14 p-4">
      <h1 class="text-2xl text-thawani uppercase tracking-wider relative mb-4">
        Customer infromation

        <div class="absolute top-0 -mt-8">
          <div
            @click="hide()"
            class="flex space-x-2 cursor-pointer bg-green-600 transition-colors duration-300 hover:bg-green-600 p-1 rounded-lg"
          >
            <div class="flex text-white">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="stroke-current w-5 h-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div class="flex">
              <span class="text-sm inline-block text-white">Close</span>
            </div>
          </div>
        </div>
      </h1>
      <!-- display  the selected session information -->
      <div class="bg-gray-100 text-thawani shadow-lg p-2 rounded-lg my-4">
        <h2 class="text-xl">thawani customer id</h2>
        <span class="break-words inline-block box-border container text-xl">
          {{ customerList[customerIndex].id }}
        </span>
      </div>
      <div class="bg-gray-100 text-thawani shadow-lg p-2 rounded-lg my-4">
        <h2 class="text-xl">application Customer id</h2>
        <span class="break-words inline-block box-border container text-xl">
          {{ customerList[customerIndex].customer_client_id }}
        </span>
      </div>
      <!-- user actions  -->
      <div class="flex-1 space-y-2 text-base">
        <div
          @click="get_payments"
          class="bg-gray-100 text-thawani shadow-lg p-2 rounded-lg my-4 hover:bg-green-600 cursor-pointer hover:text-white flex space-x-4"
        >
          <div>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="stroke-current w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
              />
            </svg>
          </div>
          <div>get cusotmer payment methods</div>
        </div>
        <div
          @click="remove_pyaments"
          :class="{
            'cursor-pointer': paymentList != null,
            'cursor-not-allowed': paymentList == null,
          }"
          class="bg-gray-100 text-red-400 shadow-lg p-2 rounded-lg my-4 flex space-x-4 hover:bg-red-600 hover:text-white"
        >
          <div>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="stroke-current w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"
              />
            </svg>
          </div>
          <div>Remove cusotmer payment methods</div>
        </div>
        <div
          class="bg-gray-100 text-red-400 shadow-lg p-2 rounded-lg my-4 flex space-x-4 hover:bg-red-600 hover:text-white cursor-pointer"
        >
          <div>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="stroke-current w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"
              />
            </svg>
          </div>
          <div>Remove customer</div>
        </div>
      </div>
      <!-- /end wrapper -->
    </div>
  </div>
</template>

<script>
import axios from "axios";
import qs from "querystring";
let site_url = null;
const prefix = "thawani_gw";
export default {
  name: "CustomerSidebar",
  props: ["customer-list", "customer-index"],
  data() {
    return {
      paymentList: null,
    };
  },
  mounted() {
    site_url = document.querySelector("#thawani_url_admin").dataset.url;
  },
  methods: {
    hide() {
      this.$emit("hide-sidebar", -1);
    },
    get_payments() {
      const customer_token = this.customerList[this.customerIndex].id;
      axios
        .post(
          `${site_url}/wp-admin/admin-ajax.php`,
          qs.stringify({
            customer_token: customer_token,
            action: `${prefix}_get_customer_payment`,
          })
        )
        .then((response) => {
          console.log(JSON.parse(response.data));
        })
        .catch((err) => console.log(err.response));
    },
    remove_pyaments() {
      if (this.paymentList == null) {
        window.alert("first get the payment ");
        return;
      }
    },
  },
};
</script>