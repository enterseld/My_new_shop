<script setup>
import { router, usePage } from '@inertiajs/vue3';
defineProps({
    products: Array
})



const addToCart = (product) => {
    router.post(route('cart.store', product), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        }
    })
}
console.log(usePage().props.favorites)

const showProduct = (id) => {
    router.get(route('product.show', id))
}

</script>

<template>
    <div class="relative w-full max-w-screen">
        <!-- Scrollable container -->
        <div class="relative rounded-lg">
            <!-- Images row with natural scrolling -->
            <div class="flex overflow-x-auto gap-4 pb-2 [&::-webkit-scrollbar]:w-2
                [&::-webkit-scrollbar-track]:rounded-full
                [&::-webkit-scrollbar-track]:bg-gray-100
                [&::-webkit-scrollbar-thumb]:rounded-full
                [&::-webkit-scrollbar-thumb]:bg-gray-300
                dark:[&::-webkit-scrollbar-track]:bg-neutral-700
                dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <div class="mt-6 flex ">

                    
                    <div v-for="product in products" :key="product.id"
                        class="relative m-1 lg:m-2 lg:p-2 p-2 flex sm:w-auto lg:w-[300px] flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md">

                        <a class="relative flex overflow-hidden rounded-xl justify-center" @click="showProduct(product.id)">
                            <img v-if="product.product_images.length > 0" :src="product.product_images[0].image"
                                class="object-cover" :alt="product.imageAlt" />
                            <img v-else :alt="product.imageAlt" class="object-cover" />
                            <span
                                class="absolute top-0 left-0 m-2 rounded-full bg-black px-2 text-center text-sm font-medium text-white"></span>
                        </a>
                        <div class="mt-4 lg:px-5" @click="showProduct(product.id)">
                            <a>
                                <h5 class="lg:text-base text-xs tracking-tight text-slate-900">{{ product.title }}</h5>
                            </a>
                            
                            <div class="mt-1 flex items-center justify-between">
                                <p>
                                    <span class="lg:text-xl text-xs font-bold text-slate-900">{{ product.price }}грн</span>
                                    <span class="text-sm text-slate-900 line-through"></span>
                                </p>

                            </div>
                            <div class="flex items-center mb-4 mt-2">
                                <p class="text-sm font-medium ml-2 text-gray-900 grid grid-cols-5 gap-x-1 xl:gap-x-1">
                                    <svg v-for="stars in Math.ceil(product.rating)" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-gray-400 text-yellow-400">
                                        <path fill-rule="evenodd"
                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                            clip-rule="evenodd"></path>
                                    </svg>

                                    <svg v-for="stars in 5 - Math.ceil(product.rating)"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-gray-400 ">
                                        <path fill-rule="evenodd"
                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </p>
                                <span v-if="product.rating != '0'"
                                    class="mr-2 ml-3 rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold">{{
                                        product.rating }}</span>
                            </div>
                            

                        </div>
                        <div class = "flex mt-auto justify-center w-auto">
                        <a v-if="product.published != 0" @click="addToCart(product)"
                                class=" w-full items-center justify-center rounded-md bg-slate-900  text-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                                <button class="flex items-center w-full h-full px-5 py-2.5 justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Купити
                                </button>
                            </a>
                            <a v-if="product.published == 0"
                                class="flex w-full items-center justify-center rounded-md border border-gray-200 bg-white-900  text-center text-sm font-medium text-gray hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-blue-300">
                                <button class="flex w-full h-full px-5 py-2.5 justify-center">

                                    Замовити
                                </button>
                            </a>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
    </div>
    

</template>

<style scoped>
/* Minimal custom CSS for scrollbar - only what can't be done with Tailwind */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>