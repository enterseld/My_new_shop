<script setup>
import { router } from '@inertiajs/vue3';
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

const showProduct = (id) => {
    router.get(route('product.show', id))
}

</script>

<template>

    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8 ">
        <div v-for="product in products" :key="product.id"
            class="h-auto group relative justify-between border-bottom-2 border-2  rounded-lg">


            <div @click="showProduct(product.id)" class="w-full overflow-hidden bg-gray-200 border-2 ">
                <img v-if="product.product_images.length > 0" :src="product.product_images[0].image"
                    :alt="product.imageAlt" class="h-full w-full object-contain object-center lg:h-full lg:w-full" />
                <img v-else
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                    :alt="product.imageAlt" class="h-full w-full object-contain object-center lg:h-full lg:w-full" />
            </div>

            <div class="flex justify-center border-1 m-2  ">

                <div class="m-auto relative justify-center">
                    <h3 @click="showProduct(product.id)" class="text-sm text-gray-700 flex text-center">
                        <span aria-hidden="true" class="" />
                        {{ product.title }}
                    </h3>


                    <p @click="showProduct(product.id)" class="text-sm font-medium text-gray-900 flex justify-center">{{
                        product.price }}грн</p>

                    <div v-if ="product.published != 0" class="mt-2 flex justify-center">
                        <button type="button" @click="addToCart(product)"
                            class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Купити
                        </button>
                    </div>
                    <div v-if = "product.published == 0" class="mt-2 flex justify-center">
                        <button type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Немає в наявності</button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>