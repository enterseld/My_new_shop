<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Hero from './Layouts/Hero.vue';
import Products from './Components/Products.vue';
import { onMounted, ref } from 'vue';
import Favorites from './Components/Favorites.vue';
import ProductsScroll from './Components/ProductsScroll.vue';
import ChatWidget from './Components/ChatWidget.vue';

defineProps({
    products: Array,
    favorites: Array
})

const auth = usePage().props.auth;
const productsByCategory = usePage().props.productsByCategory;
</script>

<template>
    <UserLayout>
        
    
        <!--Hero section-->
        <Hero :productsByCategory = "productsByCategory"></Hero>

        <!--End-->
        <div class="bg-white mx-auto max-w-fit py-16 sm:py-24 lg:max-w-screen-2xl lg:px-8">
            <div class="mx-auto max-w-screen-lg px-2 py-16 sm:px-1 sm:py-24 lg:max-w-screen-2xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Новинки</h2>

                <ProductsScroll :products="products"></ProductsScroll>
                <h2 v-if = "favorites" class="text-2xl font-bold tracking-tight text-gray-900 mt-10">Вподобані товари</h2>
                <Favorites v-if="auth.user" :favorites="favorites"></Favorites>
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.index')" type="button"
                        class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    All Products</Link>
                </div>


            </div>
        </div>
        <ChatWidget>
            
        </ChatWidget>
    </UserLayout>

</template>