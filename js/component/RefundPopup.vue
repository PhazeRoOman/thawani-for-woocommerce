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
  <!-- confirm --> 
  <div v-if="isConfirm" class="p-8 bg-white absolute top-32 w-3/6 left-0 right-0 mx-auto shadow-md rounded z-10">
    <h3 class="font-bold text-center">{{ $t('confirm_refund') }}</h3>
    <div v-if="isLoading">
      <!-- sending a request --> 
     <div class="flex justify-center mt-3 mb-1">
       <div v-if="!isDone">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
         </svg>
       </div>
       <div v-if="isSuccess != -1" class="text-center">
         <div v-if="isSuccess  === 1">
           <span class="text-thawani">{{requestMessage.success}}</span>
         </div>
         <div v-else>
           <span class="text-red-400">{{requestMessage.error}}</span>
         </div>

         <button @click.prevent="closePopup" class="bg-blue-800 hover:bg-blue-500 text-white rounded block w-full uppercase p-2">
          {{$t('close')}}
        </button>
       </div>
     </div>
      <!-- /sending request --> 
    </div>
    <div v-else>
      <div class="mt-4 flex">
      <button @click.prevent="toggleConfirm" class="text-gray-500 rounded block w-full uppercase p-2 hover:underline">
        {{$t('no')}}
      </button>
      <button @click.prevent="sendRefund" class="bg-blue-800 hover:bg-blue-500 text-white rounded block w-full uppercase p-2">
        {{$t('yes')}}
      </button>
    </div>
    </div>
  </div>
  <div v-if="isConfirm" class="bg-gray-800 opacity-30 absolute inset-0"></div>
    <h1 class="text-2xl font-bold my-2">
      <span class="uppercase">{{ $t("refund") }}</span>
      <small class="text-sm"> {{$t('order')}} <a 
      :href="`./post.php?post=${session.metadata.order_id}&action=edit`"
      target="_blank"
      class="text-blue-600 hover:underline hover:text-blue-700"
      >
      #{{ session.metadata.order_id }}</a> </small>
      <span class="inline-block mx-2 text-xs p-1 rounded-md bg-gray-100 text-gray-600">
        {{orderStatus}}
      </span>
    </h1>
    <div v-if="errorMessage" class="text-red-400">
      {{this.errorMessage}}
    </div>
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
      <button @click.prevent="validate()" class="bg-blue-800 text-white rounded block w-full uppercase p-2">
        {{$t('send')}}
      </button>
      <button @click.prevent="closePopup" class="mt-1 text-gray-500 rounded block w-full uppercase p-2 hover:underline">
        {{$t('close')}}
      </button>
    </div>
  </div>
</template>
<script>
let site_url = null;
import axios  from 'axios';
import qs  from 'querystring'; 
export default {
  name: "RefundPopup",
  props:['session'],
  data() {
    return {
      select: '',
      isOtherSelected: false,
      message: '',
      isConfirm: false,
      orderStatus: '',
      errorMessage:'',
      isLoading: false,
      isDone: false,
      requestMessage: { 
        success :'The payment has refunded.',
        error: 'Cannot refund. Time passed to make the refund workable.'
      },
      isSuccess: -1
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
  async mounted(){
    site_url = document.querySelector("#thawani_url_admin").dataset.url;
    try{ 
      const {data}  = await axios.post(
    site_url + '/wp-admin/admin-ajax.php',
    qs.stringify({ 
        order_id: this.session.metadata.order_id,
        action: 'thawani_gw_get_order_status'
      })
    );
    this.orderStatus = data.status
    }catch(error) {
      console.error(error)
    }
    
  },
  methods: {
    focusOnTextarea() {
      this.$refs.message.focus();
    },
    closePopup(){
      this.$emit('close-popup', true);
    },
    toggleConfirm(){
      this.isConfirm = !this.isConfirm
    },
    validate(){
      if(this.select === '') {
        this.errorMessage = 'please select refund reason';
        return false
      }
      if(this.orderStatus === 'refunded') {  
        this.errorMessage = 'Can not refund. it is refunded';
        return false
      }
      this.toggleConfirm()
    },
    async sendRefund(){

        let message = this.select; 
        if( this.select.toLowerCase() === 'other')
          message = this.message;

        this.isLoading = true
        const response  = axios.post(
          site_url + '/wp-admin/admin-ajax.php',
          qs.stringify(
            {
              action: 'thawani_gw_send_refund',
              order_id: this.session.metadata.order_id,
              invoice: this.session.invoice,
              message
            }
          )
        ).then( response  => { 
          this.isDone = true
          this.isSuccess = 1 
        }).catch(error => { 
            console.error(error)
            this.isDone = true
            this.isSuccess = 0
        });
        
      
    }
  },
};
</script>
