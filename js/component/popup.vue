<template>
  <div class="cursor-pointer underline relative">
    <div class="flex space-x-3">
      <div class="cursor-pointer" @click="show">
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
    </div>

    <div
      class="flex space-x-4 absolute top-0 bg-gray-700 text-white text-gray font-semibold p-3 rounded-lg"
      :class="{ hidden: !popup }"
    >
      <!-- <div>
        {{ text }}
      </div> -->
      <div class="cursor-pointer" @click="toggle()">
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
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "popup",
  props: ["text", "index"],
  data() {
    return {
      popup: false,
    };
  },
  methods: {
    toggle() {
      this.popup = !this.popup;
    },
    show() {
      this.$emit("show-sidebar", this.index);
    },
  },
};
</script>
