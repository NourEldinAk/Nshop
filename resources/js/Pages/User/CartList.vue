<script setup>
import UserLayout from './UserLayout.vue';
import {computed} from 'vue'
import {usePage,router, Link} from '@inertiajs/vue3'
import { reactive } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';



defineProps({
    userAddress: Array,
    items: Array
})


const isDisabled = true;
const products = computed(()=>usePage().props.cart.data.products)
const items = computed(()=>usePage().props.cart.data.items)
const total = computed(()=>usePage().props.cart.data.total)
const itemId = (id)=> items.value.findIndex((item)=> item.product_id === id)
const auth = usePage().props.auth

const errors =  usePage().props.errors

const updateQuantity = (product,quantity)=>
        router.patch(route('cart.update', product),{
            quantity,
        })

const removeItem = (product)=>router.delete(route('cart.delete',product));

const form = reactive({
    address:null,
    state:null,
    city:null,
    country_code:null,
    address_type:null,
    zipcode:null
})
const formFilled = computed(()=>{
   return (form.address !== null &&
    form.state !== null &&
    form.city !== null &&
    form.zipcode !== null &&
    form.country_code !== null &&
    form.address_type !== null )
})

const checkout= ()=>{
    console.log(errors)
    router.visit(route('checkout.store'),{
        method:'post',
        data:{
            cartItems : usePage().props.cart.data.items,
            products : usePage().props.cart.data.products,
            total:usePage().props.cart.data.total,
            state: form.state,
            city: form.city,
            country_code: form.country_code,
            address_type:form.address_type,
            zipcode:form.zipcode,
            address: form.address

        }
    })
}
    

</script>


    <template>
<UserLayout>

    
    <div class=" md:w-[85%] mx-auto">
 
        <section class="text-gray-600 body-font relative">
        <div class="container px-5 py-24 mx-auto flex md:flex-nowrap flex-wrap">
            <div class="lg:w-2/3 md:w-1/2 w-full mb-auto  rounded-lg overflow-hidden sm:mr-10 p-10 ">
                <!-- Start Cart Items  -->


                <div v-if="products.length > 0" class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-16 py-3">
                    <span class="sr-only">Image</span>
                </th>
                <th scope="col" class="px-6 py-3">
                    Product
                </th>
                <th scope="col" class="px-6 py-3">
                    Qty
                </th>
                <th scope="col" class="px-6 py-3">
                    Price
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
            v-for="product in products"
            :key="product.id"
             class="bg-white border-b dark:bg-gray-800
              dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="p-4">
                    <img v-if="product.product_images.length>0 " :src="`/${product.product_images[0].image}`"
                :alt="product.imageAlt"
                class="w-16 md:w-32 max-w-full max-h-full">
                <img v-else src="https://st4.depositphotos.com/14953852/24787/v/450/depositphotos_247872612-stock-illustration-no-image-available-icon-vector.jpg"
                :alt="product.imageAlt"
                class="w-16 md:w-32 max-w-full max-h-full">
                </td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                    {{ product.title }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <button
                        type="button"
                        :disabled="items[itemId(product.id)].quantity <= 1"
                        @click.prevent="updateQuantity(product, items[itemId(product.id)].quantity - 1)"
                        :class="[items[itemId(product.id)].quantity > 1 ? 'text-primary-500 cursor-pointer' : 'text-gray-300 cursor-not-allowed ', 'inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6  bg-white border border-primary-500 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700']" >
                            <span class="sr-only">Quantity button</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                            </svg>
                        </button>
                        <div>
                            <input type="number" id="first_product" 
                            v-model="items[itemId(product.id)].quantity"
                            class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"  required>
                        </div>
                        <button 
                        type="button"
                        @click.prevent="updateQuantity(product,items[itemId(product.id)].quantity + 1 )"
                        class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-primary-500 bg-white border border-primary-500 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" >
                            <span class="sr-only">Quantity button</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                    ${{ product.price }}
                </td>
                <td class="px-6 py-4">
                    <button @click.prevent="removeItem(product)" 
                    type="button"
                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
                </td>
            </tr>

        </tbody>
    </table>
</div>

<div v-else class=" overflow-x-auto border-2 border-gray-200 p-10 rounded-lg text-center w-full md:w-2/3 mx-auto md:py-24">
<p class="text-lg font-extrabold text-gray-400">Your Cart is Empty!</p> 
<Link
:href="route('products.index')"
method="get"
as="button"
class="text-primary-400 font-extrabold hover:text-gray-700 transition ease-in-out duration-200 text-md"
>
    Start Shopping... </Link>
</div>

</div>
                <!-- Send Cart Items -->
            <div class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
            <form @submit.prevent="checkout">
            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Summary</h2>
            <p class="leading-relaxed mb-5 text-gray-600">Total: ${{total > 0 ?  total?.toFixed(2) : 0 }}</p>
            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Shipping Address</h2>
                <div v-if="userAddress">
                <p  class="leading-relaxed mb-5 text-gray-600">{{userAddress.address1}},
                {{userAddress.country_code}}, {{userAddress.state}}</p>
                <p>You Can Add New Address Below</p>
                </div>
                <div v-else>
                    <p>Add Your Address To Continue</p>

                </div>
               
            <div class="relative mb-4">
                <label  for="address" class="leading-7 text-sm text-gray-600">Address</label>
                <input v-model="form.address" type="text" id="address" name="address" class="w-full bg-white rounded border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <p v-if="errors.address">{{ errors.address }}</p>
            </div>
                <div class="relative mb-4">
                <label  for="state" class="leading-7 text-sm text-gray-600">State</label>
                <input v-model="form.state" type="text" id="state" name="state" class="w-full bg-white rounded border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <p class="text-red-500" v-if="errors.state">{{ errors.state }}</p>

            </div>
            <div class="relative mb-4">
                <label  for="city" class="leading-7 text-sm text-gray-600">City</label>
                <input v-model="form.city" type="text" id="city" name="city" class="w-full bg-white rounded border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <p class="text-red-500" v-if="errors.city">{{ errors.city }}</p>

            </div>
            <div class="relative mb-4">
                <label  for="zipcode" class="leading-7 text-sm text-gray-600">Zipcode</label>
                <input v-model="form.zipcode" type="text" id="zipcode" name="zipcode" class="w-full bg-white rounded border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <p class="text-red-500" v-if="errors.zipcode">{{ errors.zipcode }}</p>

            </div>
            <div class="relative mb-4">
                <label  for="country_code" class="leading-7 text-sm text-gray-600">Country Code</label>
                <input v-model="form.country_code" type="text" id="country_code" name="country_code" class="w-full bg-white rounded border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <p class="text-red-500" v-if="errors.country_code">{{ errors.country_code }}</p>

            </div>
            <div class="relative mb-4">
                <label for="address_type" class="leading-7 text-sm text-gray-600">Address Type</label>
                <input v-model="form.address_type" type="text" id="address_type" name="address_type" class="w-full bg-white rounded border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <p class="text-red-500" v-if="errors.addree_type">{{ errors.address_type }}</p>

            </div>
            <div v-if="auth?.user">
                <div v-if="items.length > 0">
                    <button v-if="formFilled || userAddress" type="submit" class="text-white bg-primary-500 border-0 py-2 px-6 focus:outline-none hover:bg-primary-600 rounded text-lg">Checkout</button>
                    <button v-else type="submit" :disabled="!formFilled" class="text-white cursor-not-allowed bg-gray-400 border-0 py-2 px-6 focus:outline-none hover:bg-primary-600 rounded text-lg">Add Address</button>    

                </div>
                <div v-else >
                <button  type="submit"  class="text-white cursor-not-allowed bg-red-400 border-0 py-2 px-6 focus:outline-none  rounded text-lg transition duration-150 ease-in-out" :disabled='isDisabled'>Empty Cart</button>    

                </div>
                <p class="text-xs text-gray-500 mt-3">Continue Shopping...</p>

            </div>
            <div v-else class="">
                <Link :href="route('login')" as="button"  type="button" >
                <PrimaryButton class="py-3 px-6 ">
                    Login
                </PrimaryButton>
            </Link>
            <p class="text-xs text-gray-500 mt-3">You need to Login to continue</p>
            </div>
        </form>
        </div>
        </div>
    </section>
    
</div>

</UserLayout>

</template>