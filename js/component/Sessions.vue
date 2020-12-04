<template>
  <div class="my-2 p-2">
    <div class="shadow-md">
      <div
        class="grid grid-cols-7 gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md"
      >
        <div>created at</div>
        <div>Session id</div>
        <div>client_reference_id</div>
        <div>customer_id</div>
        <div>payment_status</div>
        <div>total_amount</div>
        <div>Order id</div>
      </div>

      <div v-if="sessions">
        <div
          class="grid grid-cols-7 gap-2 font-semibold bg-gray-100 text-gray-500 text-base p-2 rounded-md"
          v-for="(session, index) in sessions"
          :key="index"
          :class="{ 'bg-gray-200': index % 2 == 0 }"
        >
          <div>
            {{ format_date(session.created_at) }}
          </div>
          <div>
            <Popup
              :text="session.session_id"
              :index="index"
              @show-sidebar="show"
            />
          </div>
          <div>
            {{ session.client_reference_id.trim() }}
          </div>
          <div>{{ session.customer_id || "guest" }}</div>
          <div>{{ session.payment_status }}</div>
          <div>
            {{ price_format(session.total_amount) }}
          </div>
          <div>{{ order_id(session) }}</div>
        </div>
      </div>
      <div v-else>
        <div
          class="grid grid-cols-7 gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md animate-pulse"
        >
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
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
export default {
  name: "Sessions",
  props: ["sessions"],
  components: {
    Popup,
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
      return moment(date).format("dddd, MMMM Do YYYY, h:mm:ss a");
    },
  },
};
</script>