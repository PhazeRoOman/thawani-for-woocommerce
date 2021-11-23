<template>
  <div
    class="
      bg-white
      text-base
      px-20
      py-8
      fixed
      left-0
      right-0
      w-full
      lg:w-3/6
      top-12
      lg:top-32
      lg:p-8
      mx-auto
      rounded
      z-20
    "
  >
    <h1 class="text-2xl font-bold my-2">
      <span class="uppercase">{{ $t("refund") }}</span>
      <small class="text-sm">Order #283493</small>
    </h1>
    <p class="text-gray-500 text-base">
      I want to refund the customer because of
    </p>
    <ul class="my-2">
      <li
        class="
          border border-blue-500
          p-2
          rounded
          hover:bg-blue-500 hover:text-white
          cursor-pointer
        "
      >
        <label for="option1" class="block"
          ><input
            v-model="select"
            type="radio"
            id="option1"
            value="option1 "
          />
          Wrong product on order</label
        >
      </li>
      <li
        class="
          border border-blue-500
          p-2
          rounded
          hover:bg-blue-500 hover:text-white
          cursor-pointer
        "
      >
        <label for="option2" class="block"
          ><input
            v-model="select"
            type="radio"
            id="option2"
            value="yopn 2"
          />
          Canceled order</label
        >
      </li>
      <li
        class="
          border border-blue-500
          p-2
          rounded
          hover:bg-blue-500 hover:text-white
          cursor-pointer
        "
      >
        <label for="option3" class="block">
          <input
            v-model="select"
            type="radio"
            id="option3"
            value="option 3"
          />
          Repeated order</label
        >
      </li>
      <li
        class="
          border border-blue-500
          p-2
          rounded
          hover:bg-blue-500 hover:text-white
          cursor-pointer
        "
      >
        <label for="option4" class="block" @click="focusOnTextarea"
          ><input
            v-model="select"
            type="radio"
            id="option4"
            value="other"
          />
          Other
        </label>
      </li>
    </ul>
    <div>
      <textarea
        :disabled="!isOtherSelected"
        name="message"
        id="message"
        ref="message"
        class="h-32 w-full bg-gray-50 border rounded p-1"
      ></textarea>
      <button class="bg-blue-800 text-white rounded block w-full uppercase p-2">
        Send
      </button>
      <button class="mt-1 text-gray-500 rounded block w-full uppercase p-2 hover:underline">
        Close
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: "RefundPopup",
  data() {
    return {
      select: "",
      isOtherSelected: false,
    };
  },
  watch: {
    select: function (val) {
      if (val.toLowerCase() == "other") {
        this.isOtherSelected = true;
        this.$nextTick(()=> { 
          this.focusOnTextarea()
        })
      }else {
        this.isOtherSelected = false;
      }
    },
  },
  methods: {
    focusOnTextarea() {
      this.$refs.message.focus();
    },
  },
};
</script>
