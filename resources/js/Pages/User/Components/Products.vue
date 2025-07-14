<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
defineProps({
    products: Array,
    viewMode: String
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


</script>

<template>
    <div :class="[
        viewMode === 'grid'
            ? 'mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8'
            : 'mt-6 flex flex-col gap-y-6'
    ]">
        <div v-for="product in products" :key="product.id" :class="[
            'relative overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md transition-all duration-300',
            viewMode === 'grid'
                ? 'flex w-full max-w-xs flex-col m-2'
                : 'flex w-full flex-row items-center p-4'
        ]">
            <Link :href="route('product.show', product.id)" target="_blank" rel="noopener noreferrer" :class="[
                viewMode === 'grid'
                    ? 'relative mx-3 mt-3 flex overflow-hidden rounded-xl justify-center'
                    : 'flex-shrink-0 w-32 h-32 overflow-hidden rounded-xl'
            ]">
            <img v-if="product.product_images.length > 0" :src="product.product_images[0].image"
                class="object-cover w-full h-full" :alt="product.imageAlt" />
            <img v-else
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                class="object-cover w-full h-full" :alt="product.imageAlt" />
            </Link>

            <div :class="[viewMode === 'grid' ? 'mt-4 px-5 pb-5' : 'flex-1 ml-6']">
                <Link :href="route('product.show', product.id)" rel="noopener noreferrer">
                <h5 class="text-base font-semibold tracking-tight text-slate-900">{{ product.title }}</h5>
                </Link>

                <p class="text-sm text-gray-500 dark:text-white underline mt-1">
                    Код товару: {{ product.vendor_code }}
                </p>

                <div class="mt-2 flex items-center justify-between">
                    <p>
                        <span class="text-xl font-bold text-slate-900">{{ product.price }} грн</span>
                    </p>
                </div>

                <div class="flex items-center mb-4 mt-2">
                    <p class="text-sm font-medium ml-2 text-gray-900 flex flex-wrap">
                        <svg v-for="stars in Math.ceil(product.rating)" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-400">
                            <path fill-rule="evenodd"
                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg v-for="stars in 5 - Math.ceil(product.rating)" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-gray-400">
                            <path fill-rule="evenodd"
                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </p>
                    <span v-if="product.rating != '0'"
                        class="mr-2 ml-3 rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold">
                        {{ Number.parseFloat(product.rating).toFixed(1) }}
                    </span>
                </div>

                <div v-if="viewMode === 'grid'" class="mt-2">
                    <a v-if="product.published != 0" @click="addToCart(product)"
                        class="flex items-center justify-center rounded-md bg-slate-900 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <button class="flex w-full h-full px-5 py-2.5 justify-center">
                            Додати до корзини
                        </button>
                    </a>
                    <a v-else
                        class="flex items-center justify-center rounded-md border border-gray-200 bg-white-900 text-sm font-medium text-gray hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <button class="flex w-full h-full px-5 py-2.5 justify-center">
                            Немає в наявності
                        </button>
                    </a>
                </div>

                <div v-if="viewMode === 'list'" class="flex mt-4 space-x-4">
                    <a v-if="product.published != 0" @click="addToCart(product)"
                        class="flex items-center rounded-md bg-slate-900 text-sm font-medium text-white hover:bg-gray-700 px-4 py-2">
                        Додати
                    </a>
                    <span v-else class="text-sm text-gray-400">Немає в наявності</span>
                </div>

            </div>
        </div>
    </div>

</template>