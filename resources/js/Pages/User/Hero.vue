<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';


</script>


<template>
	<section class=" bg-gray-800  text-gray-100">
		<div class="container mx-auto flex flex-col items-center px-4 py-16 text-center md:py-32 md:px-10 lg:px-32 xl:max-w-3xl">
			<h1 class="text-4xl font-bold leadi sm:text-5xl"><span class="text-primary-400">Nshop:</span> Your Ultimate Destination for Chic,
				<span class=" text-primary-400">Trendy Finds</span>
			</h1>
			<p class="px-8 mt-8 mb-12 text-lg">Discover the joy of effortless shopping. Find curated treasures that inspire, delight, and elevate your everyday moments</p>
			<div class="flex flex-wrap justify-center">
				<Link :href="route('products.index')" as="button"  type="button" >
					<PrimaryButton class="ms-4 bg-primary-500 py-4 px-5 font-bold ">
                    Get Started
                </PrimaryButton>
				</Link>
			<!-- <button class="px-8 py-3 m-2 text-lg border rounded  text-gray-50  border-gray-700">Learn more</button> -->
			</div>
		</div>
	</section>
</template>