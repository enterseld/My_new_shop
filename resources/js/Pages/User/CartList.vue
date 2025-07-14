<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import axios from 'axios';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue'
import {
    Combobox,
    ComboboxInput,
    ComboboxOptions,
    ComboboxOption,
    ComboboxButton
} from '@headlessui/vue'
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';

const auth = usePage().props.auth;
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

const remove = (product) => router.delete(route('cart.delete', product), { preserveState: true, replace: true });


const filteredCities = ref([]);
let selected = ref("0");
let query = ref("");

let filteredWarehouses = ref([]);
let selectedWarehouse = ref("");
let queryWarehouse = ref("0");

let cities = ref([]);
let warehouses = ref([]);

watch(query, (newQuery) => {
    // Reset selected warehouse when query changes
    selectedWarehouse.value = "";

    // Filter cities based on query
    if (newQuery === '') {
        filteredCities.value = cities.value.slice(); // Make a copy of the original array
    } else {
        filteredCities.value = cities.value.filter((product) => {
            const searchQuery = newQuery.toLowerCase().replace(/\s+/g, '');

            const titleWithoutBrackets = product.title
                .toLowerCase()
                .replace(/\s+/g, '')
                .replace(/\(.*?\)/g, '');

            return titleWithoutBrackets.includes(searchQuery);
        });
    }
    // Load warehouses after filtering cities
    loadAllWarehouses();
});

watch(selected, () => {
    loadAllWarehouses();
});

const loadAllWarehouses = () => {

    axios
        .get(`/getWarehouses/${selected.value.title}/0`)
        .then((response) => {
            // Map the response data to match the Combobox items
            warehouses.value = response.data.warehouses;

        })
        .catch((error) => {
            console.error('Error loading cities:', error);
        });
};

watch(queryWarehouse, (newQueryWarehouse) => {

    // Filter cities based on query
    if (newQueryWarehouse === '') {
        filteredWarehouses.value = warehouses.value.slice(); // Make a copy of the original array
    } else {
        filteredWarehouses.value = warehouses.value.filter((product) => {
            const searchQuery = newQueryWarehouse.toLowerCase().replace(/\s+/g, '');

            const titleWithoutBrackets = product.title
                .toLowerCase()
                .replace(/\s+/g, '')
                .replace(/\(.*?\)/g, '');

            return titleWithoutBrackets.includes(searchQuery);
        });
    }


});

let handleChange = () => {
    if (selectedWarehouse.value.title) {
        selectedWarehouse.value.title = "";
    }
    else {
        selectedWarehouse = ref("");
    }
};

let phone_number = ref("");
let first_name = ref("");
let last_name = ref("");
let middle_name = ref("");
let email = ref("");
if (auth.user) {
    first_name.value = auth.user.first_name;
    last_name.value = auth.user.last_name;
    middle_name.value = auth.user.middle_name;
}



let sendOrder = async () => {
    if (!validate()) {
        if (auth.user)
            Swal.fire({
                text: "Будь ласка, введіть правильний номер телефону та ПІБ",
                icon: "warning"
            })
        else Swal.fire({
            text: "Будь ласка, введіть правильний номер телефону, email та ПІБ",
            icon: "warning"
        })
        return; // Stop execution of outerFunction if validation fails
    }
    if (auth.user) {
        const formOrder = new FormData();
        formOrder.append('first_name', first_name.value);
        formOrder.append('last_name', last_name.value);
        formOrder.append('middle_name', middle_name.value);
        formOrder.append('total_price', total.value);
        formOrder.append('email', usePage().props.auth.user.email);
        formOrder.append('mobile_phone', phone_number.value);
        formOrder.append('shipping_city', selected.value.title);
        formOrder.append('shipping_warehouse', selectedWarehouse.value.title);
        formOrder.append('user_id', usePage().props.auth.user.id);

        formOrder.append('products', JSON.stringify(usePage().props.cart.data.items));

        const formAdress = new FormData();
        formAdress.append('shipping_city', selected.value.title);
        formAdress.append('shipping_warehouse', selectedWarehouse.value.title);
        formAdress.append('user_id', usePage().props.auth.user.id);
        try {
            router.post('/orders/store', formOrder, {
                onSuccess: page => {
                    router.post('/adresses/store', formAdress, {
                        onSuccess: page => {
                            console.log('Address stored successfully');

                        },
                    })
                },
            })
        }
        catch (err) {
            console.log('An error occurred:', err);
        }
    }
    else {
        const formOrder = new FormData();
        formOrder.append('first_name', first_name.value);
        formOrder.append('last_name', last_name.value);
        formOrder.append('middle_name', middle_name.value);
        formOrder.append('email', email.value);
        formOrder.append('total_price', total.value);
        formOrder.append('mobile_phone', phone_number.value);
        formOrder.append('shipping_city', selected.value.title);
        formOrder.append('shipping_warehouse', selectedWarehouse.value.title);
        formOrder.append('user_id', 0);

        formOrder.append('products', JSON.stringify(usePage().props.cart.data.items));
        try {
            router.post('/orders/store', formOrder, {
                onSuccess: page => {
                },
            })
        }
        catch (err) {
            console.log('An error occurred:', err);
        }
    }


};

let validate = () => {
    let patternPhone1 = /^\+?\d{1,3}\d{9}$/;
    let patternPhone2 = /^\d{10}$/;
    let patternEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if ((patternPhone1.test(phone_number.value) || patternPhone2.test(phone_number.value)) && (patternEmail.test(email.value) || auth.user) && first_name.value != "" && last_name.value != "" && middle_name.value != "") {

        return true;
    }
    else {
        console.log("not passed")
        return false;
    }
}

onMounted(() => {
    axios
        .get(`/getCities/0`)
        .then((response) => {
            // Map the response data to match the Combobox items
            cities.value = response.data.cities;

        })
        .catch((error) => {
            console.error('Error loading cities:', error);
        });


});


</script>
<template>

    <UserLayout>
        <section
            class="bg-white text-gray-600 body-font relative mx-auto max-w-1xl px-4 py-6 sm:px-6 sm:py-24 lg:max-w-screen-1xl lg:px-8">
            <div
                class="container px-5 py-3 mx-auto flex flex-wrap lg:flex-col lg:space-y-4 xl:flex-row xl:space-y-0 xl:space-x-4 justify-center ">

                <!--List of cart items-->
                <div class="overflow-x-auto">
                    <table
                        class="w-full text-xs md:text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 justify-center">
                        <thead
                            class="text-[10px] md:text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                            <tr>
                                <th scope="col" class="px-16 py-3 text-center w-2/11 ">
                                    <span class="sr-only">Image</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11 font-medium">
                                    Продукт
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11 font-medium">
                                    Кількість
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11 font-medium">
                                    Ціна
                                </th>
                                <th scope="col" class="px-6 py-3 text-center w-2/11 font-medium">
                                    Дії
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
                                                v-model="carts[itemId(product.id)].quantity" min="1"
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
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline">Прибрати</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

            <div
                class="container px-5  mx-auto flex flex-wrap justify-center lg:flex-col lg:space-y-4 xl:flex-row xl:space-y-0 xl:space-x-4">
                <div
                    class="lg:w-2/3 md:w-4/4 bg-white flex-col ml-50 md:py-8 mt-8 ml-5 md:mt-0 w-full sm:mt-4 lg:inline-block xl:block ">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font mx-auto">Підсумок</h2>
                    <p class="leading-relaxed text-lg mb-5 text-gray-600">Загалом: {{ total }} грн</p>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full md:w-full px-3 mb-6 md:mb-0 flex">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-city">
                            </label>
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="name" name="floating_name" id="floating_name" v-model="first_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="floating_name"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Ім'я</label>
                            </div>
                            <div class="relative z-0 w-full mb-5 ml-5 group">
                                <input type="last_name" name="floating_last_name" id="floating_last_name"
                                    v-model="last_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="floating_last_name"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Прізвище</label>
                            </div>
                            <div class="relative z-0 w-full mb-5 ml-5 group">
                                <input type="middle_name" name="floating_middle_name" id="floating_middle_name"
                                    v-model="middle_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="floating_middle_name"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">По-батькові</label>
                            </div>
                        </div>

                    </div>
                    <div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                <Combobox v-model="selected">
                                    <div class="relative z-30 w-full mb-5 group">
                                        <div class="relative">
                                            <ComboboxInput id="floating_city" name="floating_city"
                                                class="peer block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600"
                                                :displayValue="(city) => city?.title"
                                                @input="query = $event.target.value" placeholder=" " required />

                                            <label for="floating_city"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Місто
                                            </label>

                                            <ComboboxButton
                                                class="absolute inset-y-0 right-0 flex items-center pr-2 z-20">
                                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                            </ComboboxButton>
                                        </div>

                                        <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100"
                                            leaveTo="opacity-0" @after-leave="query = ''">
                                            <ComboboxOptions
                                                class="absolute mt-1 z-50 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                <div v-if="filteredCities.length === 0 && query !== ''"
                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700">
                                                    Nothing found.
                                                </div>
                                                <ComboboxOption v-for="city in (filteredCities || []).slice(0, 20)"
                                                    as="template" :key="city.id" :value="city"
                                                    v-slot="{ selected, active }">
                                                    <li class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                        :class="{
                                                            'bg-teal-600 text-white': active,
                                                            'text-gray-900': !active,
                                                        }">
                                                        <span class="block"
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
                            <div class="w-full md:w-2/3 px-3">
                                <Combobox v-model="selectedWarehouse" @focus="loadWarehouses">
                                    <div class="relative z-30 w-full mb-5 group">
                                        <div class="relative">
                                            <ComboboxInput id="floating_warehouse" name="floating_warehouse"
                                                class="peer block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600"
                                                :displayValue="(warehouse) => warehouse?.title"
                                                @change="queryWarehouse = $event.target.value" placeholder=" "
                                                required />
                                            <label for="floating_warehouse"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Назва або номер відділення
                                            </label>
                                        </div>

                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2 z-20">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>

                                        <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100"
                                            leaveTo="opacity-0" @after-leave="queryWarehouse = ''">
                                            <ComboboxOptions
                                                class="absolute mt-1 z-40 max-h-60 w-full overflow-x-auto whitespace-nowrap rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                <div v-if="filteredWarehouses.length === 0 && queryWarehouse !== ''"
                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700">
                                                    Нічого не знайдено.
                                                </div>
                                                <ComboboxOption
                                                    v-for="warehouse in (filteredWarehouses || []).slice(0, 20)"
                                                    as="template" :key="warehouse.id" :value="warehouse"
                                                    v-slot="{ selectedWarehouse, active }">
                                                    <li class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                        :class="{
                                                            'bg-teal-600 text-white': active,
                                                            'text-gray-900': !active,
                                                        }">
                                                        <span
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

                    </div>


                    <div class="relative z-30 w-full mb-5 group" v-if="!auth.user">
                        <input type="email" name="floating_email" id="floating_phone_number" v-model="email"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="email"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Електронна
                            пошта</label>
                    </div>

                    <div class="relative z-0 w-full mb-5 group">
                        <input type="phone_number" name="floating_phone_number" id="floating_phone_number"
                            v-model="phone_number"
                            class="block py-2.5  px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="phone_number"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Номер
                            телефону (+380123456789)</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input id="checkbox-1" type="checkbox" value="0"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Я
                            погоджуюся із <a href="#" class="text-blue-600 hover:underline dark:text-blue-500">умовами
                                обслуговування</a>.</label>
                    </div>
                    <button type="submit"
                        class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                        @click="sendOrder()">
                        Checkout
                    </button>
                    <p class="text-xs text-gray-500 mt-3">Continue shopping</p>
                </div>

            </div>

        </section>

    </UserLayout>

</template>
