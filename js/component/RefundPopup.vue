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
      <small class="text-sm"> {{$t('order')}} <a 
      :href="`./post.php?post=${session.metadata.order_id}&action=edit`"
      target="_blank"
      class="text-blue-600 hover:underline hover:text-blue-700"
      >
      #{{ session.metadata.order_id }}</a> </small>
    </h1>
    <p class="text-gray-500 text-base">
      {{ $t('refund_option_description')}}
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
            :value="$t('refund_option.wrong_product')"
          />
          {{$t('refund_option.wrong_product')}}</label
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
            :value="$t('refund_option.cancel_order')"
          />
          {{$t('refund_option.wrong_product')}}</label
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
            :value="$t('refund_option.repeated_order')"
          />
          {{$t('refund_option.repeated_order')}}</label
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
          {{$t('refund_option.other')}}
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
        v-model="message"
      ></textarea>
      <button class="bg-blue-800 text-white rounded block w-full uppercase p-2">
        {{$t('send')}}
      </button>
      <button @click.prevent="closePopup" class="mt-1 text-gray-500 rounded block w-full uppercase p-2 hover:underline">
        {{$t('close')}}
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: "RefundPopup",
  props:['session'],
  data() {
    return {
      select: '',
      isOtherSelected: false,
      message: ''
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
    closePopup(){
      this.$emit('close-popup', true);
    }
  },
};
</script>
