<script setup>

import { onMounted } from 'vue'
import UserLayout from './UserLayout.vue';
import {router, Link} from '@inertiajs/vue3'
import Hero from './Hero.vue';
import Products from '../User/Components/Products.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
    defineProps({
        products:Array
    })
    


    
</script>

<template>
    
    <UserLayout>
        
        <Hero/>
        <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-24 ">Featured Products</h2>

            <!-- start products  -->
            <Products :products = "products"></Products>

            <!-- end products -->
            
        <!-- More products... -->
        <div class="flex items-center justify-center mt-10 w-full">

            <Link :href="route('products.index')" as="button"  type="button" >
                <PrimaryButton class="py-4 px-4 mb-8">
                    View All Products

                </PrimaryButton>
            </Link>
        </div>
        </div>
        </div>

    </UserLayout>


</template>