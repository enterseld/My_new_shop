<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import { onMounted, ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3';
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
import Products from '../User/Components/Products.vue';
import SecondaryButtonVue from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { BAR_MAP } from 'element-plus';


const sortOptions = [
    { name: 'Most Popular', href: '#', current: true },
    { name: 'Best Rating', href: '#', current: false },
    { name: 'Newest', href: '#', current: false },
    { name: 'Price: Low to High', href: '#', current: false },
    { name: 'Price: High to Low', href: '#', current: false },
]


const filterPrices = useForm({
    prices: [0, 100000]
})

const priceFilter = () => {
    router.get('products', {
        brands: selectedBrands.value,

        prices: {
            from: filterPrices.prices[0],
            to: filterPrices.prices[1]
        }
    },
        {
            preserveState: true,
            replace: true
        });
}
const mobileFiltersOpen = ref(false)

const props = defineProps({
    categoryForList: Number,
    products: Array,
    brands: Array,
    categories: Array,
    productDiameters: Array,
    productFitDiameters: Array,
    pagination: Object
})
//filter brands and categories
const selectedBrands = ref([])
const selectedCategories = ref([])

// Add the category from page props if it exists
if (usePage().props.categoryForList) {
  selectedCategories.value.push(usePage().props.categoryForList)
}
const selectedDiameters = ref([])
const selectedFitDiameters = ref([])

watch(selectedBrands, () => {
    updateFilteredProducts()
})


watch(selectedDiameters, () => {
    updateFilteredProducts()
})
watch(selectedFitDiameters, () => {
    updateFilteredProducts()

})

function updateFilteredProducts(page = 1) {
    console.log(selectedCategories.value)
    router.get(route('productByCategory.index', { category: props.categoryForList }), {
        brands: selectedBrands.value,
        categories: selectedCategories.value,
        product_diameters: selectedDiameters.value,
        product_fit_diameters: selectedFitDiameters.value,
        prices: {
            from: filterPrices.prices[0],
            to: filterPrices.prices[1]
        },
        page
    },

        {
            preserveState: true,
            replace: true
        });
}


</script>


<template>
    <UserLayout>

        <div class="bg-white mx-auto max-w-fit px-4 py-16 sm:px-6 sm:py-24 lg:max-w-screen-2xl lg:px-8">

            <!-- Mobile filter dialog -->
            <TransitionRoot as="template" :show="mobileFiltersOpen">
                <Dialog class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                        enter-from="opacity-0" enter-to="opacity-100"
                        leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black/25" />
                    </TransitionChild>

                    <div class="fixed inset-0 z-40 flex">
                        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                            leave-to="translate-x-full">
                            <DialogPanel
                                class="relative ml-auto flex size-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                                <div class="flex items-center justify-between px-4">
                                    <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                    <button type="button"
                                        class="-mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                        @click="mobileFiltersOpen = false">
                                        <span class="sr-only">Close menu</span>
                                        <XMarkIcon class="size-6" aria-hidden="true" />
                                    </button>
                                </div>

                                <!-- Filters -->
                                <form class="mt-4 border-t border-gray-200">

                                    <Disclosure as="div" v-for="section in filters" :key="section.id"
                                        class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                        <h3 class="-mx-2 -my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                                <span class="font-medium text-gray-900">Brand</span>
                                                <span class="ml-6 flex items-center">
                                                    <PlusIcon v-if="!open" class="size-5" aria-hidden="true" />
                                                    <MinusIcon v-else class="size-5" aria-hidden="true" />
                                                </span>
                                            </DisclosureButton>
                                        </h3>
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-6">
                                                <div v-for="(option, optionIdx) in section.options" :key="option.value"
                                                    class="flex items-center">
                                                    <input :id="`filter-mobile-${section.id}-${optionIdx}`"
                                                        :name="`${section.id}[]`" :value="option.value" type="checkbox"
                                                        :checked="option.checked"
                                                        class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                    <label :for="`filter-mobile-${section.id}-${optionIdx}`"
                                                        class="ml-3 min-w-0 flex-1 text-gray-500">{{ option.label
                                                        }}</label>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>




                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>

            <main class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Каталог</h1>

                    <div class="flex items-center">
                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton
                                    class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                    Sort
                                    <ChevronDownIcon
                                        class="-mr-1 ml-1 size-5 shrink-0 text-gray-400 group-hover:text-gray-500"
                                        aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                                        <a :href="option.href"
                                            :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100 outline-none' : '', 'block px-4 py-2 text-sm']">{{
                                                option.name }}</a>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>

                        <button type="button" class="-m-2 ml-5 p-2 text-gray-400 hover:text-gray-500 sm:ml-7">
                            <span class="sr-only">View grid</span>
                            <Squares2X2Icon class="size-5" aria-hidden="true" />
                        </button>
                        <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                            @click="mobileFiltersOpen = true">
                            <span class="sr-only">Filters</span>
                            <FunnelIcon class="size-5" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <section aria-labelledby="products-heading" class="pb-24 pt-6">


                    <h2 id="products-heading" class="sr-only">Products</h2>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <!-- Filters -->
                        <form class="hidden lg:block">
                            <h3 class="sr-only">Price</h3>

                            <!--price filter-->
                            <div class="flex items-center justify-between space-x-3">
                                <div class="basis-1/3">
                                    <label for="filters-price-from"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Від
                                    </label>

                                    <input type="number" id="filters-price-from" placeholder="Min price"
                                        v-model="filterPrices.prices[0]"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                </div>
                                <div class="basis-1/3">
                                    <label for="filters-price-to"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        До
                                    </label>

                                    <input type="number" id="filters-price-to" placeholder="Max price"
                                        v-model="filterPrices.prices[1]"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                </div>
                                <SecondaryButtonVue class="self-end" @click="priceFilter()">
                                    Ok
                                </SecondaryButtonVue>


                            </div>
                            <!--end-->

                            <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                <h3 class="-my-3 flow-root">
                                    <DisclosureButton
                                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                        <span class="font-medium text-gray-900">Бренди</span>
                                        <span class="ml-6 flex items-center">
                                            <PlusIcon v-if="!open" class="size-5" aria-hidden="true" />
                                            <MinusIcon v-else class="size-5" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <div class="space-y-4">
                                        <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                            <input :id="`filter-${brand.id}`" :value="brand.id" type="checkbox"
                                                v-model="selectedBrands"
                                                class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                            <label :for="`filter-${brand.id}`" class="ml-3 text-sm text-gray-600">{{
                                                brand.name }}</label>
                                        </div>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>


                            <!--Diameters-->
                            <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                <h3 class="-my-3 flow-root">
                                    <DisclosureButton
                                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                        <span class="font-medium text-gray-900">Діаметр диска чи свердла</span>
                                        <span class="ml-6 flex items-center">
                                            <PlusIcon v-if="!open" class="size-5" aria-hidden="true" />
                                            <MinusIcon v-else class="size-5" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div v-for="diameter in productDiameters" :key="diameter.id"
                                                class="flex items-center">
                                                <input :id="`filter-${diameter.id+1000}`" :value="diameter.number"
                                                    type="checkbox" v-model="selectedDiameters"
                                                    class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label :for="`filter-${diameter.id}`"
                                                    class="ml-3 text-sm text-gray-600">{{ diameter.number }}</label>
                                            </div>
                                        </div>

                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                            <!--End-->
                            <!--Fit diameters-->
                            <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                <h3 class="-my-3 flow-root">
                                    <DisclosureButton
                                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                        <span class="font-medium text-gray-900">Посадочний отвір</span>
                                        <span class="ml-6 flex items-center">
                                            <PlusIcon v-if="!open" class="size-5" aria-hidden="true" />
                                            <MinusIcon v-else class="size-5" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div v-for="fitDiameter in productFitDiameters" :key="fitDiameter.id"
                                                class="flex items-center">
                                                <input :id="`filter-${fitDiameter.id+2000}`" :value="fitDiameter.number"
                                                    type="checkbox" v-model="selectedFitDiameters"
                                                    class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label :for="`filter-${fitDiameter.id}`"
                                                    class="ml-3 text-sm text-gray-600">{{ fitDiameter.number }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                            <!--End-->
                        </form>

                        <!-- Product grid -->
                        <div class="lg:col-span-3">
                            <Products :products="products.data"></Products>
                        </div>
                    </div>

                    <div class="flex justify-center items-center mt-10">
                        <div></div> <!-- Empty div for alignment placeholder -->

                        <div class="flex pl-2 py-1 border-2 rounded-lg">
                            <button type="button" v-if="pagination.current_page > 1"
                                @click="updateFilteredProducts(pagination.current_page - 1)"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 8 14">
                                    <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 1 1.3 6.326a.91.91 0 0 0 0 1.348L7 13" />
                                </svg>
                                <span class="sr-only">Icon description</span>
                            </button>
                            <div class="pagination justify-center flex-wrap mx-auto mr-2 mt-1.5">
                                <span class="text-center">Сторінка {{ pagination.current_page }} із {{
                                    pagination.last_page }}</span>
                            </div>
                            <button type="button" v-if="pagination.current_page < pagination.last_page"
                                @click="updateFilteredProducts(pagination.current_page + 1)"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 8 14">
                                    <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m1 13 5.7-5.326a.909.909 0 0 0 0-1.348L1 1" />
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    </div>

                </section>
            </main>
        </div>



    </UserLayout>
</template>