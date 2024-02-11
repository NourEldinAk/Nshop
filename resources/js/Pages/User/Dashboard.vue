<script setup>
    import UserLayout from '../User/UserLayout.vue'

    defineProps({
        orders:Array,
    })

</script>

<template>
    <UserLayout>

        <div class="relative max-w-screen-xl py-24  mx-auto my-10 overflow-x-auto">
    <table v-show="order.order_items.length>0" v-for="order in orders" class="w-full text-sm text-left rtl:text-right mt-8 text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Order_ID#
                </th>
                <th scope="col" class="px-6 py-3">
                    Product Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Category
                </th>
                <th scope="col" class="px-6 py-3">
                    Brand
                </th>
                <th scope="col" class="px-6 py-3">
                    Price
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in order.order_items" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium  uppercase text-gray-900 whitespace-nowrap dark:text-white">
                {{ item.id }}
                </th>
                <td class="px-6 py-4 uppercase">
                    {{ item.product.title }}
                </td>
                <td class="px-6 py-4 uppercase ">
                    {{ item.product.category.name }}
                </td>
                <td class="px-6 py-4 uppercase ">
                    {{ item.product.brand.name }}
                </td>
                <td class="px-6 py-4 uppercase">
                    {{ item.product.price }}
                </td>
            </tr>

        </tbody>
    </table>
</div>
    </UserLayout>

</template>