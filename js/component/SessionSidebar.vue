<template>
  <div
    class="fixed right-0 left-0 top-12 mx-auto h-4/5 w-full md:w-1/2 bg-gray-100 text-thawani shadow-xl rounded-md overscroll-auto overflow-auto"
    v-if="sessionIndex > -1"
  >
    <div class="text-white mt-14 p-4">
      <h1 class="text-2xl text-thawani uppercase tracking-wider relative mb-4">
        {{ $t("session_info") }}

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
              <span class="text-sm inline-block"> {{ $t("close") }} </span>
            </div>
          </div>
        </div>
      </h1>
      <!-- display  the selected session information -->
      <div class="flex-1 space-y-2 my-1">
        <div class="text-gray-600 my-2 p-2 text-base">
          <h2 class="text-xl">{{ $t("created_at") }}</h2>
          <span class="text-sm">{{
            format_date(sessionList[sessionIndex].created_at)
          }}</span>
        </div>
        <div class="text-gray-600 my-2 p-2 text-base">
          <h2 class="text-xl">{{ $t("expire_at") }}</h2>
          <span class="text-sm">{{
            format_date(sessionList[sessionIndex].expire_at)
          }}</span>
        </div>
      </div>

      <div class="my2">
        <PaymentStatus :state="sessionList[sessionIndex].payment_status" />
      </div>

      <div class="text-gray-600 my-2 p-2 rounded-lg">
        <h2 class="text-xl">{{ $t("session_id") }}</h2>
        <span class="break-words block box-border container text-sm">
          {{ sessionList[sessionIndex].session_id }}
        </span>
      </div>

      <div class="text-gray-600 my-2 p-2">
        <h2 class="text-xl">{{ $t("products") }}</h2>
        <div class="grid grid-cols-3 gap-2 bg-gray-200 border-b-2">
          <div>{{ $t("product.title") }}</div>
          <div>{{ $t("product.qty") }}</div>
          <div>{{ $t("product.price") }}</div>
        </div>
        <div
          class="grid grid-cols-3 gap-2"
          v-for="product in sessionList[sessionIndex].products"
          :key="product.name"
        >
          <div>
            <span class="text-base">{{ product.name }}</span>
          </div>
          <div>
            <span class="text-base"> {{ product.quantity }} </span>
          </div>
          <div class="place-self-center">
            {{ price_format(product.unit_amount) }}
          </div>
        </div>
      </div>
      <div class="flex justify-end space-x-8 text-gray-600 bg-gray-200">
        <div>
          <h2 class="text-base">{{ $t("total_amount") }}</h2>
        </div>
        <div>
          <span class="text-base">
            {{ price_format(sessionList[sessionIndex].total_amount) }}</span
          >
        </div>
      </div>
      <div class="text-gray-600 my-2 p-2">
        <h2 class="text-xl">{{ $t("order_id") }}</h2>
        <span v-if="sessionList[sessionIndex].metadata" class="text-base">
          {{ sessionList[sessionIndex].metadata.order_id }}
        </span>
        <h2 class="text-xl">{{ $t("customer_info") }}</h2>
        <span v-if="sessionList[sessionIndex].metadata" class="text-base">
          {{ sessionList[sessionIndex].metadata.customer_name }}
        </span>
        <span v-if="sessionList[sessionIndex].metadata" class="text-base block">
          {{ sessionList[sessionIndex].metadata.phone }}
        </span>
        <span
          v-if="sessionList[sessionIndex].metadata.email"
          class="text-base block"
        >
          {{ sessionList[sessionIndex].metadata.email }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import PaymentStatus from "./PaymentStatus.vue";

export default {
  name: "SessionSidebar",
  props: ["session-list", "session-index"],
  components: {
    PaymentStatus,
  },
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
      return moment(date).add(4,'h').format("dddd, MMMM Do YYYY, h:mm:ss a");
    },
    hide() {
      this.$emit("hide-sidebar", -1);
    },
  },
};
</script>