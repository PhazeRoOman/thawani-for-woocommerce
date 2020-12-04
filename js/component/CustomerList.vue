<template>
  <div class="my-2 p-2">
    <div class="shadow-md">
      <div
        class="grid grid-cols-2 gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md"
      >
        <div>thawani customer id</div>
        <div>application Customer id</div>
      </div>

      <div v-if="customers">
        <div
          class="grid grid-cols-2 gap-2 font-semibold bg-gray-100 text-gray-500 text-base p-2 rounded-md"
          v-for="(customer, index) in customers"
          :key="index"
          :class="{ 'bg-gray-200': index % 2 == 0 }"
        >
          <div class="flex space-x-8">
            <div class="cursor-pointer" @click="show(index)">
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
            <div>{{ customer.id }}</div>
          </div>
          <div>{{ customer.customer_client_id }}</div>
        </div>
      </div>
      <div v-else>
        <div
          class="grid grid-cols-2 gap-2 font-semibold bg-gray-100 text-thawani text-base p-2 rounded-md animate-pulse"
        >
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
          <div class="bg-gray-200 rounded-lg h-3 w-20"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CustomerList",
  props: ["customers"],
  data() {
    return {};
  },
  methods: {
    show(data) {
      this.$emit("show-sidebar", data);
    },
  },
};
</script>