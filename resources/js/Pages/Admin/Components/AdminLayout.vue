<script setup>
import { onMounted } from 'vue';
import {initFlowbite} from 'flowbite'
import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';
    onMounted(()=>{
        initFlowbite();
    })

</script>

<template>
<div class="antialiased bg-gray-50 dark:bg-gray-900">
    <!-- navbar start -->
        <Navbar/>
    <!-- navbar end -->
    <!-- Sidebar start  -->
        <Sidebar/>
    <!-- Sidebar end -->
    <main class="p-4 md:ml-64 h-auto pt-20">
        <slot></slot>
    </main>
  </div>
</template>