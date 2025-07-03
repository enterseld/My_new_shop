<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Hero from './Layouts/Hero.vue';
import Favorites from './Components/Favorites.vue';
import ProductsScroll from './Components/ProductsScroll.vue';
import { ref } from 'vue'
import { pipeline, env } from '@xenova/transformers'


defineProps({
    products: Array,
    favorites: Array
})

const auth = usePage().props.auth;
const productsByCategory = usePage().props.productsByCategory;

env.allowRemoteModels = true
env.allowLocalModels = false

const inputText = ref('')
const embedding = ref([])
const loading = ref(false)
const response = ref('')
const reply = ref('')

const messages = ref([
    {
        role: 'system',
        content: `Ти — україномовний консультант магазину алмазного інструменту. Відповідай чітко, ввічливо та лише українською. Якщо користувач запитує про товар — допоможи обрати найкращий варіант з наданих, але обирай тільки товари, які надані, не придумуй інші. Якщо запит про доставку, оплату, повернення — відповідай відповідно до політики магазину.`,
    }
])

const embedText = async () => {
    loading.value = true
    try {
        const extractor = await pipeline('feature-extraction', 'Xenova/all-MiniLM-L6-v2')
        const result = await extractor(inputText.value, { pooling: 'mean', normalize: true })
        const vector = result.data
        console.log(vector)
        response.value = await axios.post('/search', { vector })
        const matches = response.value.data.matches || []
        const productList = matches.map((match, i) =>
            `${i + 1}. ${match.metadata.text}`
        ).join('\n')

        messages.value.push({
            role: 'user',
            content: `Мені потрібно обрати ${inputText.value}. Ось список релевантних товарів:\n${productList} Який краще обрати(обери тільки один)? Поясни чому.`,
        })

        console.log(messages.value)

        const res = await axios.post('/ask', {
            messages: messages.value,
            session_id: '1',
            last_message: inputText.value
        })
        console.log(res)
        const assistantReply = res.data.reply
        console.log('assistantReply:', assistantReply)
        messages.value.push({ role: 'assistant', content: assistantReply })
        reply.value = assistantReply

    } catch (error) {
        console.error('Помилка embedding, пошуку або відповіді:', error)
        reply.value = 'Вибач, щось пішло не так. Спробуй ще раз.'
    } finally {
        loading.value = false
        console.log(response.value.data.matches)
    }
}




</script>

<template>
    <UserLayout>



        <!--Hero section-->
        <Hero :productsByCategory="productsByCategory"></Hero>

        <!--End-->
        <div class="bg-white mx-auto max-w-fit py-16 sm:py-24 lg:max-w-screen-2xl lg:px-8">
            <div class="mx-auto max-w-screen-lg px-2 py-16 sm:px-1 sm:py-24 lg:max-w-screen-2xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Новинки</h2>

                <ProductsScroll :products="products"></ProductsScroll>
                <h2 v-if="favorites" class="text-2xl font-bold tracking-tight text-gray-900 mt-10">Вподобані товари</h2>
                <Favorites v-if="auth.user" :favorites="favorites"></Favorites>
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.index')" type="button"
                        class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    All Products</Link>
                </div>
            </div>
        </div>


        <div class="p-4 max-w-md mx-auto">
            <input v-model="inputText" placeholder="Enter text" class="w-full p-2 border rounded" />
            <button @click="embedText" :disabled="loading || !inputText"
                class="mt-2 w-full bg-blue-600 text-white py-2 rounded">
                {{ loading ? 'Embedding...' : 'Get Embedding' }}
            </button>

            <pre v-if="embedding.length" class="mt-4 text-xs whitespace-pre-wrap">
      {{ embedding }}
    </pre>
        </div>

    </UserLayout>

</template>