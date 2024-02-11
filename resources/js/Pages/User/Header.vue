<script setup>
import {usePage,Link} from '@inertiajs/vue3'
import 'sweetalert2/dist/sweetalert2.min.css';
import {computed} from 'vue'
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

onMounted(() => {
        initFlowbite();
    })
const canLogin =  usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const auth = usePage().props.auth;
const cart = computed(()=>usePage().props.cart);

</script>

<template>
<nav class="bg-white border-gray-200 dark:bg-gray-900">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <Link href="/" class="flex items-center space-x-2 rtl:space-x-reverse">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-primary-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
</svg>
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Nshop</span>
</Link>
  <div v-if="canLogin || auth" class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
      
    <div class="flex items-center justify-center text-sm py-1 px-2 rounded-full md:me-0">
      
      <span v-if="auth?.user" class="mr-3">Welcome <span class="text-primary-600 font-bold">{{auth?.user?.name }}!</span></span>
      
      <Link :href="route('cart.view')"
       as="button"
        class="relative inline-flex items-center p-2 text-sm font-medium text-center text-primary-500 bg-white rounded-lg hover:bg-gray-100  focus:outline-none  dark:primary-600 dark:hover:bg-primary-600 dark:focus:ring-primary-400">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
      </svg>
      <span class="sr-only">Cart</span>
        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-[11px] font-bold text-white bg-primary-500 border-2 border-white rounded-full -top-1 -end-1 dark:border-gray-900">  {{ cart.data.count }}</div>
    </Link>

      
      

      </div>
    <button v-if="auth?.user" type="button" class="flex text-sm py-1 px-1 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 " id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
        
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
      </svg>
      </button>

      <div v-else>
        <Link :href="route('login')" type="button" class="focus:outline-none text-white bg-primary-600 hover:bg-primary-300   font-medium rounded-lg text-sm px-6 py-2 me-2 focus:transform focus:scale-110 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Login</Link>
        <Link v-if="canRegister" :href="route('register')" type="button" class="text-primary-600 bg-white border-2 border-green-300 focus:outline-none hover:bg-gray-100 focus:transform focus:scale-110 font-medium rounded-lg text-sm px-6 py-2 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700
         dark:hover:border-gray-600 dark:focus:ring-gray-700">Register</Link>

      </div>



      <!-- Dropdown menu -->
      <div v-if="auth.user" class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
        <div class="px-4 py-3">
          <span class="block text-sm text-gray-900 dark:text-white">{{ auth?.user.name }}</span>
          <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ auth?.user.email }}</span>
        </div>
        <ul class="py-2" aria-labelledby="user-menu-button">
          <li>
            <Link method="get" as="button" :href="route('dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white w-full text-left">My Orders</Link>
            
          </li>
          <li v-if="auth?.user?.isAdmin == 1">

            <Link method="get" as="button" :href="route('admin.products.index')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white w-full text-left">Admin Dashboard</Link>
          </li>
    
    
          <li>
            <Link :href="route('logout')"
            method="post"
            as="button"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100
             dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white w-full text-left">Sign out</Link>
          </li>
        </ul>
      </div>
      <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
      <li>
        <Link :href="route('home')" method="get"  class="block py-2 px-3 text-white bg-primary-400 rounded md:bg-transparent md:text-primary-400 md:p-0 md:dark:text-primary-500" aria-current="page">Home</Link>
      </li>
      <li v-if="auth?.user">
        <Link :href="route('dashboard')" method="get" as="button" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-400 md:p-0
         dark:text-white md:dark:hover:text-primary-500
         dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">My Orders</Link>
      </li>
      <li>
        <Link :href="route('cart.view')" method="get" as="button" class="block py-2 px-3 text-gray-900 rounded
         hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-400 md:p-0 dark:text-white
          md:dark:hover:text-primary-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Cart</Link>
      </li>
      <li>
        <Link :href="route('products.index')" method="get" as="button" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-400 md:p-0 dark:text-white md:dark:hover:text-primary-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">All Products</Link>
      </li>
      <li v-if="auth?.user?.isAdmin == 1">
        <Link :href="route('admin.products.index')" method="get" as="button" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-400 md:p-0 dark:text-white md:dark:hover:text-primary-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Dashboard</Link>
      </li>
    </ul>
  </div>
  </div>
</nav>
</template>