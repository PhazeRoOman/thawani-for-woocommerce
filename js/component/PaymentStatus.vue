<template>
  <div>
    <span
      class="bg-green-300 text-green-700 rounded inline-block p-1 w-4/5 text-center"
      v-if="state == 'paid'"
      ><svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        class="w-6 h-6 inline-block"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M5 13l4 4L19 7"
        /></svg
      >{{ $t("payment_status.paid") }}</span
    >
    <span
      class="bg-gray-300 text-gray-600 rounded inline-block p-1 w-4/5 text-center"
      v-else-if="state == 'unpaid'"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        class="w-6 h-6 inline-block"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      {{ $t("payment_status.unpaid") }}</span
    >
    <span
      class="bg-red-200 text-red-500 rounded inline-block p-1 w-4/5 text-center"
      v-else
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        class="w-6 h-6 inline-block"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      {{ $t("payment_status.cancelled") }}</span
    >
  </div>
</template>
<script>
export default {
  name: "PaymentStatus",
  props: ["state"],
};
</script>