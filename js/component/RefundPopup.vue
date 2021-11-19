<template>
    <div class="bg-white text-base p-4 fixed left-0 right-0 w-full lg:w-2/6 top-12 lg:top-32 mx-auto rounded">
      <h1 class="uppercase text-2xl font-bold my-2">{{$t('refund')}}</h1>
      <p class="text-gray-500 text-base">
        I want to refund the customer because of
      </p>
      <ul class="my-2">
        <li
          class="border border-blue-500 p-2 rounded hover:bg-blue-500 hover:text-white cursor-pointer"
        >Wrong product on order </li>
        <li
          class="border border-blue-500 p-2 rounded hover:bg-blue-500 hover:text-white cursor-pointer"
        >Canceled order</li>
        <li
          class="border border-blue-500 p-2 rounded hover:bg-blue-500 hover:text-white cursor-pointer"
        >Repeated order</li>
        <li
          class="border border-blue-500 p-2 rounded hover:bg-blue-500 hover:text-white cursor-pointer"
        >Other</li>
      </ul>
      <div>
        <textarea name="message" id="message" class="h-32 w-full bg-gray-50 border rounded p-1">I'm typing here
        </textarea>
        <button class="bg-blue-800 text-white rounded block w-full uppercase p-2">
          Send
        </button>
      </div>
    </div>
</template>
<script>
export default {
    name: "RefundPopup"
}
</script>
