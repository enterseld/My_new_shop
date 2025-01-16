<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import axios from 'axios';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue'
import {
    Combobox,
    ComboboxInput,
    ComboboxOptions,
    ComboboxOption,
    ComboboxButton
} from '@headlessui/vue'
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';

const carts = computed(() => usePage().props.cart.data.items);
const products = computed(() => usePage().props.cart.data.products);
const total = computed(() => usePage().props.cart.data.total);
const itemId = (id) => carts.value.findIndex((item) => item.product_id == id);

const update = (product, quantity) =>
    router.patch(route('cart.update', product), {
        quantity,
        preserveState: true,
        replace: true
    })

const remove = (product) =>
    router.delete(route('cart.delete', product), { preserveState: true, replace: true });

let filteredCities = ref([]);
let selected = ref("0");
let query = ref("А");

let filteredWarehouses = ref([]);
let selectedWarehouse = ref("");
let queryWarehouse = ref("0");


let loadCities = computed(() => {
    selectedWarehouse = ref("");
    axios
        .get(`/getCities/${query.value}`)
        .then((response) => {
            // Map the response data to match the Combobox items
            filteredCities.value = response.data.cities;

        })
        .catch((error) => {
            console.error('Error loading cities:', error);
        });

});

let loadWarehouses = computed(() => {
    if (selected.value.title) {
        if (!queryWarehouse.value) {
            queryWarehouse.value = "0";
        }
        axios
            .get(`/getWarehouses/${selected.value.title}/${queryWarehouse.value}`)
            .then((response) => {
                // Map the response data to match the Combobox items
                filteredWarehouses.value = response.data.warehouses;

            })
            .catch((error) => {
                console.error('Error loading cities:', error);
            });
    }
    else {
        axios
            .get(`/getWarehouses/${selected.value}/${queryWarehouse.value}`)
            .then((response) => {
                // Map the response data to match the Combobox items
                filteredWarehouses.value = response.data.warehouses;

            })
            .catch((error) => {
                console.error('Error loading cities:', error);
            });
    }
});

let handleChange = () => {
    if(selectedWarehouse.value.title){
        selectedWarehouse.value.title = "";
    }
    else{
        selectedWarehouse = ref("");
    }
};

</script>
<template>

    <UserLayout>
        <section
            class="bg-white text-gray-600 body-font relative mx-auto max-w-1xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-screen-1xl lg:px-8">
            <div
                class="container px-5 py-3 mx-auto flex flex-wrap lg:flex-col lg:space-y-4 xl:flex-row xl:space-y-0 xl:space-x-4 justify-center ">

                <!--List of cart items-->
                <div class="overflow-x-auto">
                    <table
                        class="w-full text-xs md:text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 justify-center">
                        <thead
                            class="text-[10px] md:text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                            <tr>
                                <th scope="col" class="px-16 py-3 text-center w-2/11">
                                    <span class="sr-only">Image</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11">
                                    Product
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11">
                                    Qty
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="p-4">
                                    <img v-if="product.product_images.length > 0" :src="product.product_images[0].image"
                                        class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                                    <img v-else
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                        class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ product.title }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)"
                                            :disabled="carts[itemId(product.id)].quantity <= 1"
                                            :class="[carts[itemId(product.id)].quantity > 1 ? 'cursor-pointer text-purple-600' : 'cursor-not-allowed text-gray-300 dark:text-gray-500', 'inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700']"
                                            class="" type="button">

                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                            </svg>
                                        </button>
                                        <div>
                                            <input type="number" id="first_product"
                                                v-model="carts[itemId(product.id)].quantity"
                                                class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="1" required />
                                        </div>
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                                            class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                            type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ product.price }} грн.
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#" @click="remove(product)"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

            <div
                class="container px-5  mx-auto flex flex-wrap justify-center lg:flex-col lg:space-y-4 xl:flex-row xl:space-y-0 xl:space-x-4">
                <div
                    class="lg:w-2/3 md:w-2/3 bg-white flex-col ml-50 md:py-8 mt-8 ml-20 md:mt-0 w-full sm:mt-4 lg:inline-block xl:block ">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font mx-auto">Summary</h2>
                    <p class="leading-relaxed mb-5 text-gray-600">Total: {{ total }} грн</p>
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Shipping Address</h2>
                    <p class="leading-relaxed mb-5 text-gray-600">1234</p>
                    <p class="leading-relaxed mb-5 text-gray-600">Or you can add one below</p>
                    <div class="relative mb-4">
                        <label for="name" class="leading-7 text-sm text-gray-600">Address</label>
                        <input type="text" id="name" name="address1"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-600">City</label>
                        <input type="text" id="email" name="city"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-600">State</label>
                        <input type="text" id="email" name="state"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-600">Zipcode</label>
                        <input type="text" id="email" name="zipcode"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-600">Country Code</label>
                        <input type="text" id="email" name="countrycode"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div>
                        <div>
                            <Combobox v-model="selected" @focus="loadCities">
                                <div class="relative mt-1">
                                    <div
                                        class="relative pe-20 flex items-center w-full cursor-default overflow-hidden rounded-lg text-left border-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">

                                        <ComboboxInput
                                            class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                                            :displayValue="(city) => city.title" @change="query = $event.target.value;"
                                            placeholder="Почніть вводити місто..." />

                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 z-20"
                                            @click="handleChange">
                                            <ComboboxButton>
                                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                            </ComboboxButton>
                                        </div>
                                        
                                    </div>
                                    <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100"
                                        leaveTo="opacity-0" @after-leave="query = ''">
                                        <ComboboxOptions
                                            class="absolute mt-1 z-20 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                            <div v-if="filteredCities.length === 0 && query !== ''"
                                                class="relative cursor-default select-none px-4 py-2 text-gray-700">
                                                Nothing found.
                                            </div>
                                            <ComboboxOption v-for="city in filteredCities" as="template" :key="city.id"
                                                :value="city" v-slot="{ selected, active }">
                                                <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{
                                                    'bg-teal-600 text-white': active,
                                                    'text-gray-900': !active,
                                                }">
                                                    <span class="block truncate"
                                                        :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                                        {{ city.title }}
                                                    </span>
                                                    <span v-if="selected"
                                                        class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                        :class="{ 'text-white': active, 'text-teal-600': !active }">
                                                        <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                    </span>
                                                </li>
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </TransitionRoot>
                                </div>
                            </Combobox>
                        </div>
                        <div>
                            <Combobox v-model="selectedWarehouse" @focus="loadWarehouses">
                                <div class="relative mt-1">
                                    <div
                                        class="relative pe-20 flex items-center w-full cursor-default overflow-hidden rounded-lg text-left border-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">

                                        <ComboboxInput
                                            class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                                            :displayValue="(warehouse) => warehouse.title"
                                            @change="queryWarehouse = $event.target.value"
                                            placeholder="Почніть вводити назву чи номер відділення..." />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2 z-20">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>
                                    </div>
                                    <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100"
                                        leaveTo="opacity-0" @after-leave="queryWarehouse = ''">
                                        <ComboboxOptions
                                            class="absolute mt-1 z-20 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                            <div v-if="filteredWarehouses.length === 0 && queryWarehouse !== ''"
                                                class="relative cursor-default select-none px-4 py-2 text-gray-700">
                                                Nothing found.
                                            </div>
                                            <ComboboxOption v-for="warehouse in filteredWarehouses" as="template"
                                                :key="warehouse.id" :value="warehouse"
                                                v-slot="{ selectedWarehouse, active }">
                                                <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{
                                                    'bg-teal-600 text-white': active,
                                                    'text-gray-900': !active,
                                                }">
                                                    <span class="block truncate"
                                                        :class="{ 'font-medium': selectedWarehouse, 'font-normal': !selectedWarehouse }">
                                                        {{ warehouse.title }}
                                                    </span>
                                                    <span v-if="selectedWarehouse"
                                                        class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                        :class="{ 'text-white': active, 'text-teal-600': !active }">
                                                        <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                    </span>
                                                </li>
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </TransitionRoot>
                                </div>
                            </Combobox>
                        </div>
                    </div>
                    <div class="relative mb-4">
                        <label for="email" class="leading-7 text-sm text-gray-600">Address type</label>
                        <input type="text" id="email" name="type"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <button
                        class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Checkout</button>
                    <p class="text-xs text-gray-500 mt-3">Continue shopping</p>
                </div>

            </div>

        </section>

    </UserLayout>

</template>
