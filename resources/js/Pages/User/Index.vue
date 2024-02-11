<script setup>

import { onMounted } from 'vue'
import UserLayout from './UserLayout.vue';
import {router, Link} from '@inertiajs/vue3'
import Hero from './Hero.vue';
import Products from '../User/Components/Products.vue'
    defineProps({
        products:Array
    })
    


    
</script>

<template>
    
    <UserLayout>
        
        <Hero/>
        <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Featured Products</h2>

            <!-- start products  -->
            <Products :products = "products"></Products>

            <!-- end products -->
            
        <!-- More products... -->
        <div class="flex items-center justify-center mt-10 w-full">

            <Link :href="route('products.index')" as="button"  type="button" class="focus:outline-none text-white bg-primary-600 hover:bg-primary-300  
             rounded-lg text-sm px-6 py-3 me-2 focus:transform w-1/4 font-bold
             focus:scale-110 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">View All Products</Link>
        </div>
        </div>
        </div>

    </UserLayout>


</template>