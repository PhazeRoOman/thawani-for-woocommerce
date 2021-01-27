<template>
  <div class="my-2 p-2">
    <div class="shadow-md">
      <div
        class="grid grid-cols-5 gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md border-b-2"
      >
        <div>created at</div>
        <div>client reference / order id</div>
        <div>Customer infromation</div>
        <div>payment_status</div>
        <div>total_amount</div>
      </div>

      <div v-if="sessions">
        <div
          class="grid grid-cols-5 gap-2 items-center font-semibold bg-gray-50 text-gray-500 text-base p-2 rounded-md hover:bg-blue-100"
          v-for="(session, index) in sessions"
          :key="index"
          :class="{ 'bg-gray-100': index % 2 == 0 }"
        >
          <div>
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
            {{ session.client_reference_id.trim() }}
          </div>
          <div>
            <span class="font-semibold block">{{
              session.metadata.customer_name || "guest"
            }}</span>
            <span class="text-sm text-gray-400">{{
              session.metadata.phone
            }}</span>
          </div>
          <div><payment-status :state="session.payment_status" /></div>
          <div>
            {{ price_format(session.total_amount) }}
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
      return moment(date).format("LLL");
    },
    from_now(date) {
      return moment(date).fromNow();
    },
  },
};
</script>