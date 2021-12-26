<template>
  <div class="my-2 p-2">
    <div class="shadow-md">
      <div
        class="hidden md:grid md:grid-cols-6 md:gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md border-b-2"
      >
        <div>{{ $t("created_at") }}</div>
        <div>{{ $t("order_id") }}</div>
        <div>{{ $t("customer_info") }}</div>
        <div>{{ $t("payment_status.title") }}</div>
        <div>{{ $t("total_amount") }}</div>
        <div>{{ $t("refund") }}</div>
      </div>

      <div v-if="sessions">
        <div
          class="grid grid-row-6 md:grid-cols-6 md:gap-2 items-center font-semibold bg-gray-50 text-gray-500 text-base p-2 rounded-md hover:bg-blue-100"
          v-for="(session, index) in sessions"
          :key="index"
          :class="{ 'bg-gray-100': index % 2 == 0 }"
        >
          <div>
            <div class="text-thawani md:hidden">{{ $t("created_at") }}</div>
            <div class="flex">
              <div>
                <Popup
                  :text="session.session_id"
                  :index="index"
                  @show-sidebar="show"
                />
              </div>
              <div>
                <span class="block font-semibold">
                  {{ from_now(session.created_at) }}</span
                >
                <span class="text-xs">
                  {{ format_date(session.created_at) }}</span
                >
              </div>
            </div>
          </div>
          <div>
            <a
              :href="`./post.php?post=${session.metadata.order_id}&action=edit`"
              target="_blank"
              class="text-blue-500 hover:text-blue-800"
            >
              {{ session.metadata.order_id }}
            </a>
          </div>
          <div>
            <div class="text-thawani md:hidden">{{ $t("customer_info") }}</div>
            <span class="font-semibold block">{{
              session.metadata.customer_name || $t("guest")
            }}</span>
            <span class="text-sm text-gray-400">{{
              session.metadata.phone
            }}</span>
          </div>
          <div>
            <div class="text-thawani md:hidden">
              {{ $t("payment_status.title") }}
            </div>
            <payment-status :state="session.payment_status" />
          </div>
          <div>
            <div class="text-thawani md:hidden">{{ $t("total_amount") }}</div>
            {{ price_format(session.total_amount) }}
          </div>
          <div>
            <button
              class="bg-blue-100 text-blue-500 hover:bg-blue-900 hover:text-white rounded inline-block p-1 w-full lg:w-4/5 shadow text-center uppercase"
              @click="showRefund(session)"
            >
              {{ $t("refund") }}
            </button>
          </div>
        </div>
      </div>
      <div v-else>
        <div
          class="grid grid-cols-5 gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md animate-pulse"
        >
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import Popup from "./popup";
import PaymentStatus from "./PaymentStatus";
// import PaymentStatus from './PaymentStatus.vue';
export default {
  name: "Sessions",
  props: ["sessions"],
  components: {
    Popup,
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
    show(data) {
      this.$emit("show-sidebar", data);
    },
    format_date(date) {
      return moment(date)
        .add(4, "h")
        .format("LLL");
    },
    from_now(date) {
      return moment(date)
        .add(4, "h")
        .fromNow();
    },
    showRefund(data) {
      this.$emit("show-refund", data);
    },
    showRefund(data) {
      this.$emit("show-refund", data);
    },
  },
};
</script>
