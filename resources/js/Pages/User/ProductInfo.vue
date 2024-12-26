<script setup>


import Products from './Components/Products.vue';
import UserLayout from './Layouts/UserLayout.vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref, computed } from 'vue';

defineProps({
    product: Object,
    products: Object,
})

const auth = usePage().props.auth;
const inputText = ref(""); // Using ref for reactive data
const isButtonDisabled = computed(() => !inputText.value.trim());


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
let selectedRating = ref(1);
let commentText = ref(0);
const starsContainer = ref(null);

const sendComment = async (commentText, rating, product) => {
    const formData = new FormData();
    formData.append('comment', commentText);
    formData.append('rating', rating);
    formData.append('user_name', auth.user.name);
    formData.append('product_id', product.id);
    try {
        await router.post('/comment/store', formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
            },
        })
    } catch (err) {
        console.log(err)
    }
};

const sendReply = async (comment_id, index) => {
    const formData = new FormData();
    const textarea = document.getElementById("reply-" + index); 
    const reply = textarea.value.trim();
    
    formData.append('reply', reply);
    formData.append('comment_id', comment_id);
    formData.append('user_name', auth.user.name);
    
    try {
        await router.post('/reply/store', formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
            },
        })
    } catch (err) {
        console.log(err)
    }
};

const selectRating = (rating) => {
    const textArea = document.getElementById('comment');
    selectedRating.value = rating;

    commentText.value = textArea.value.trim();

    // Directly manipulating the DOM
    const stars = starsContainer.value.querySelectorAll('svg');
    stars.forEach((star, i) => {
        if (i < rating) {
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
        }
    });
};

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

const toggleVisibility = (event) => {
    const divId = event.target.id;
    const lastSymbol = divId.slice(-1);
    const div = "div-" + lastSymbol;
    const textarea = document.getElementById(div);

    if (textarea.classList.contains('hidden')) {
        textarea.classList.remove('hidden');
        textarea.classList.add('visible');
    } else if (textarea.classList.contains('visible')) {
        textarea.classList.remove('visible');
        textarea.classList.add('hidden');
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

        <section id="endScroll" class="bg-white mx-auto max-w-1xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-screen-2xl lg:px-8">
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
                                    class="absolute z-30  top-0 start-0 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
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
                                    class="absolute z-30 top-0 end-0 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
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
                                            Коментарі</h2>
                                    </div>
                                    <div class="max-w-2xl mx-auto px-4 py-4">
                                        <form v-if="auth.user" class="mb-6">

                                            <div
                                                class="py-2 px-4 mb-4 mt-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                                <label for="comment" class="sr-only">Your comment</label>
                                                <textarea id="comment" rows="6" v-model="inputText"
                                                    class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                                                    placeholder="Напишіть ваш коментар..." required></textarea>
                                            </div>


                                            <div class="flex flex-col">
                                                <div id="rating" ref="starsContainer"
                                                    class="inline-flex items-center py-2 px-4 mb-4 bg-white">
                                                    <svg v-for="index in 5" :key="index"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        class="w-8 h-8 text-gray-400 hover:text-yellow-400 cursor-pointer"
                                                        @click="selectRating(index)">
                                                        <path fill-rule="evenodd"
                                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <div class="inline-flex ml-2 mt-1 bg-white">{{ selectedRating }} з 5
                                                    </div>

                                                </div>

                                                <button type="submit" :disabled="isButtonDisabled"
                                                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                                    @click="sendComment(commentText, selectedRating, product)">

                                                    Опублікувати
                                                </button>
                                            </div>
                                        </form>
                                        <form v-else class="mb-6">

                                            <div
                                                class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                                <label for="notcomment" class="sr-only">Your comment</label>
                                                <textarea id="notcomment" rows="6"
                                                    class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-black dark:placeholder-gray-400 dark:bg-gray-800"
                                                    placeholder="Write a comment..." required></textarea>
                                            </div>
                                            <div class="flex flex-col">
                                                <button type="submit" data-popover-target="popover-default"
                                                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                                    disabled>
                                                    Опублікувати
                                                </button>
                                            </div>
                                            <div data-popover id="popover-default" role="tooltip"
                                                class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                <div
                                                    class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                                        Повідомлення!</h3>
                                                </div>
                                                <div class="px-3 py-2">
                                                    <p>Залишати коментарі та оцінки можуть тільки зареєстровані
                                                        користувачі.</p>
                                                </div>
                                                <div data-popper-arrow></div>
                                            </div>
                                        </form>
                                        <div v-for="(comment, index) in product.product_comments" :key="index"
                                            class=" text-base bg-white rounded-lg dark:bg-gray-900">
                                            <div v-if="comment.published == 1">
                                                <footer class="flex justify-between items-center mb-2">
                                                    <div class="flex items-center">
                                                        <p
                                                            class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                class="w-7 h-7 rounded-full bg-white"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                            </svg>{{ comment.user_name }}

                                                        </p>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                                            <time pubdate datetime="2022-02-08"
                                                                title="February 8th, 2022">{{
                                                                    comment.created_at.split('T')[0] }}</time>

                                                        </p>

                                                    </div>

                                                </footer>
                                                <p
                                                    class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                                                    <svg v-for="stars in comment.rating"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        class="w-4 h-4 text-gray-400 text-yellow-400">
                                                        <path fill-rule="evenodd"
                                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>

                                                    <svg v-for="stars in 5 - comment.rating"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-4 h-4 text-gray-400 ">
                                                        <path fill-rule="evenodd"
                                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>

                                                </p>

                                                <p class="text-gray-500 dark:text-gray-400">{{ comment.comment }}</p>
                                                <div class="flex display-flex mt-2 mb-4 space-x-4">
                                                    <button :id="'button-' + index" type="button" v-if="auth.user"
                                                        @click="toggleVisibility($event)"
                                                        class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium">
                                                        <svg class="mr-1.5 w-3.5 h-3.5" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 20 18">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z" />
                                                        </svg>
                                                        Reply
                                                    </button>

                                                </div>

                                                <div v-for="reply in comment.comments_replies"
                                                    class="p-1 mb-3 ml-6 lg:ml-12 text-base bg-white rounded-lg dark:bg-gray-900">
                                                    <div v-if="reply.published == 1">
                                                    <footer class="flex justify-between items-center mb-2">
                                                        <div class="flex items-center">
                                                            <p
                                                                class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    class="w-7 h-7 rounded-full bg-white"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                                </svg>{{ comment.user_name }}
                                                            </p>
                                                            <p class="text-sm text-gray-600 dark:text-gray-400"><time
                                                                    pubdate datetime="2022-02-12"
                                                                    title="February 12th, 2022">{{
                                                                        reply.created_at.split('T')[0] }}</time>
                                                            </p>
                                                        </div>
                                                    </footer>
                                                    <p class="text-gray-500 dark:text-gray-400">{{ reply.reply }}</p>

                                                </div>
                                            </div>
                                                <div :id="'div-' + index"
                                                    class="hidden py-2 px-4 mb-4 mt-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">

                                                    <textarea rows="6" :id="'reply-' + index"
                                                        class=" px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-black dark:placeholder-gray-400 dark:bg-gray-800"
                                                        placeholder="Write a reply..." required></textarea>
                                                    <button type="submit" @click="sendReply(comment.id, index)"
                                                        class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                                        >
                                                        Опублікувати
                                                    </button>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <div class="bg-white mx-auto max-w-1xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-screen-2xl lg:px-8">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Latest products</h2>

                <Products :products="products"></Products>
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.index')" type="button"
                        class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    All Products</Link>
                </div>


            </div>
        </div>
    </UserLayout>
</template>