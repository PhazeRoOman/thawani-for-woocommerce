<template>
  <div
    class="fixed right-0 top-0 w-1/3 bottom-0 h-screen bg-gray-100 text-thawani shadow-lg overscroll-auto overflow-auto"
    v-if="sessionIndex > -1"
  >
    <div class="text-white mt-14 p-4">
      <h1 class="text-2xl text-thawani uppercase tracking-wider relative mb-4">
        Session infromation

        <div class="absolute top-0 -mt-8">
          <div
            @click="hide()"
            class="flex space-x-2 cursor-pointer bg-green-600 text-white transition-colors duration-300 hover:bg-green-600 p-1 rounded-lg"
          >
            <div class="flex">
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
              <span class="text-sm inline-block">Close</span>
            </div>
          </div>
        </div>
      </h1>
      <!-- display  the selected session information -->
      <div class="flex-1 space-y-2 my-1">
        <div
          class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg text-base"
        >
          <h2 class="text-xl">created at</h2>
          {{ format_date(sessionList[sessionIndex].created_at) }}
        </div>
        <div
          class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg flex-1 text-base"
        >
          <h2 class="text-xl">expire at</h2>
          {{ format_date(sessionList[sessionIndex].expire_at) }}
        </div>
      </div>

      <div class="flex-1 space-y-2 justify-items-auto">
        <div class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg flex-1">
          <h2 class="text-xl">Payment Status  <span class="text-base mx-4">
            {{ sessionList[sessionIndex].payment_status }}</span
          ></h2>
        </div>
        
      </div>

      <div class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg">
        <h2 class="text-xl">session id</h2>
        <span class="break-words inline-block box-border container text-xl">
          {{ sessionList[sessionIndex].session_id }}
        </span>
      </div>
      <div class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg">
        <h2 class="text-xl">customer id</h2>
        <span class="text-base">
          {{ sessionList[sessionIndex].customer_id || "guest" }}</span
        >
      </div>

      <div class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg">
        <h2 class="text-xl">Products</h2>
        <div
          class="grid grid-cols-2 gap-2"
          v-for="product in sessionList[sessionIndex].products"
          :key="product.name"
        >
          <div>
            <span class="text-base">{{ product.name }}</span> x
            <span class="text-sm"> {{ product.quantity }} </span>
          </div>
          <div class="place-self-center">
            {{ price_format(product.unit_amount) }}
          </div>
        </div>
      </div>
      <div class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg">
          <h2 class="text-xl">Total</h2>
          <span class="text-base font-semibold">
            {{ price_format(sessionList[sessionIndex].total_amount) }}</span
          >
        </div>
       <div class="bg-gray-100 text-gray-600 shadow my-2 p-2 rounded-lg">
          <h2 class="text-xl">Order id</h2>
          <span v-if="sessionList[sessionIndex].metadata" class="text-base">
            {{ sessionList[sessionIndex].metadata.order_id }}
          </span>
          <h2 class="text-xl">Customer name</h2>
          <span v-if="sessionList[sessionIndex].metadata" class="text-base">
            {{ sessionList[sessionIndex].metadata.customer_name }}
          </span>
           <h2 class="text-xl">Customer phone number</h2>
          <span v-if="sessionList[sessionIndex].metadata" class="text-base">
            {{ sessionList[sessionIndex].metadata.phone }}
          </span>
        </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
export default {
  name: "SessionSidebar",
  props: ["session-list", "session-index"],
  data() {
    return {};
  },
  methods: {
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
    format_date(date) {
      return moment(date).format("dddd, MMMM Do YYYY, h:mm:ss a");
    },
    hide() {
      this.$emit("hide-sidebar", -1);
    },
  },
};
</script>