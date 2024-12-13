<script setup>
import Comments from './Components/Comments.vue';
import Products from './Components/Products.vue';
import UserLayout from './Layouts/UserLayout.vue';
import { router, } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
defineProps({
    product: Object,
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

const handleScroll = () => {
    const gallery = document.getElementById("gallery");
    const section = document.getElementById("endScroll");
    const scrollY = window.scrollY;
    const sectionTop = section.offsetTop;
    const maxOffset = section.offsetHeight - 460;

    if (scrollY >= sectionTop) {
        const scrollOffset = scrollY - sectionTop;
        const offset = Math.min(scrollOffset, maxOffset);
        gallery.style.position = "relative";
        gallery.style.top = `${Math.min(offset, maxOffset)}px`;
    } else {
        gallery.style.position = "relative";
        gallery.style.top = "0px";
    }
};

onMounted(() => {
    document.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    document.removeEventListener("scroll", handleScroll);
});

</script>

<template>
    <UserLayout>

        <section id="endScroll" class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased overflow-hidden">
            <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                    <div id="gallery"
                        class="relative w-full transition-transform duration-100 ease-out will-change-transform"
                        data-carousel="slide">
                        <!-- Carousel wrapper -->
                        <div class="relative h-56 overflow-hidden rounded-lg md:h-96 border-2">
                            <!-- Item 1 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item
                                v-for="image in product.product_images">
                                <img v-if="product.product_images.length > 0" :src="image.image" :alt="product.imageAlt"
                                    class="h-full w-full object-cover object-center lg:h-full lg:w-full " />
                                <img v-else
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                    :alt="product.imageAlt"
                                    class="absolute h-full w-full object-cover object-center lg:h-full lg:w-full" />
                            </div>
                            <!-- Slider controls -->
                            <div v-if="product.product_images.length > 1">
                                <button type="button"
                                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                    data-carousel-prev>
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-gray/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                        <svg class="w-4 h-4 text-black dark:text-gray-800 rtl:rotate-180"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M5 1 1 5l4 4" />
                                        </svg>
                                        <span class="sr-only">Previous</span>
                                    </span>
                                </button>
                                <button type="button"
                                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                    data-carousel-next>
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                        <svg class="w-4 h-4 text-black dark:text-gray-800 rtl:rotate-180"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                        </svg>
                                        <span class="sr-only">Next</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="mt-6 sm:mt-8 lg:mt-0">
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                            {{ product.title }}
                        </h1>
                        <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                            <p class="text-2xl  text-gray-900 sm:text-3xl dark:text-white">
                                {{ product.price }} грн.
                            </p>

                            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                            </div>
                        </div>

                        <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                            <a href="#" title=""
                                class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                role="button">
                                <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
                                </svg>
                                Add to favorites
                            </a>

                            <a @click="addToCart(product)"
                                class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                role="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                Add to cart
                            </a>
                        </div>

                        <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ product.description }}
                        </p>
                        <div>
                            <div class="px-4 sm:px-0">
                                <h3 class="text-base/7 font-semibold text-gray-900">Характеристики</h3>
                                <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Personal details and application.</p>
                            </div>
                            <div class="mt-6 border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm/6 font-medium text-gray-900">Бренд</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ product.brand.name }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm/6 font-medium text-gray-900">Категорія</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            {{ product.category.name }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm/6 font-medium text-gray-900">Email address</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                            margotfoster@example.com</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm/6 font-medium text-gray-900">Salary expectation</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">$120,000</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm/6 font-medium text-gray-900">About</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">Fugiat ipsum
                                            ipsum
                                            deserunt culpa aute sint do nostrud anim incididunt cillum culpa consequat.
                                            Excepteur qui ipsum aliquip consequat sint. Sit id mollit nulla mollit
                                            nostrud
                                            in ea officia proident. Irure nostrud pariatur mollit ad adipisicing
                                            reprehenderit deserunt qui eu.</dd>
                                    </div>
                                    <div class="px-4 py-6">
                                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">
                                            Discussion</h2>
                                    </div>
                                    
                                    <form class="mb-6">
                                        <div
                                            class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                            <label for="comment" class="sr-only">Your comment</label>
                                            <textarea id="comment" rows="6"
                                                class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                                                placeholder="Write a comment..." required></textarea>
                                        </div>
                                        <button type="submit"
                                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                            Post comment
                                        </button>
                                    </form>

                                    <Comments :products="products"></Comments>
                                    
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Latest products</h2>

            <Products :products="products"></Products>
            <div class="flex justify-center mt-5">
                <Link :href="route('products.index')" type="button"
                    class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                All Products</Link>
            </div>


        </div>
    </UserLayout>
</template>