<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { pipeline, env } from '@xenova/transformers'
const isOpen = ref(false)
const session = ref('')
const sessionType = ref('normal')
const inputText = ref('')
const embedding = ref([])
const loading = ref(false)
const response = ref(null)
const reply = ref('')
const chatMessages = ref([])
const messagesConsult = ref([])


env.allowRemoteModels = true
env.allowLocalModels = false

const initSession = async (openToggle = true) => {
    try {
        const response = await axios.post('/initSession', {
            session_type: sessionType.value
        })
        console.log('Session started:', response.data)
        session.value = response.data.session

        chatMessages.value = response.data.messages.map((msg, index) => ({
            content: msg.message,
            role: index % 2 === 0 ? 'user' : 'assistant'
        }));

        console.log("messages array:", chatMessages.value)
        if (!response.data.messages || response.data.messages.length === 0 && sessionType === 'product') {
            chatMessages.value.push({
                role: 'system',
                content: `Ти — україномовний консультант магазину алмазного інструменту. Відповідай чітко, ввічливо та лише українською. Відповідай тільки текстом, без додаткових неграматичних знаків. Якщо користувач запитує про товар — допоможи обрати найкращий варіант з наданих, але обирай тільки товари, які надані, не придумуй інші. Якщо запит про доставку, оплату, повернення — відповідай відповідно до політики магазину.`,
            })
        }
        else if (!response.data.messages || response.data.messages.length === 0 && sessionType === 'product') {
            chatMessages.value.push({
                role: 'system',
                content: `Ти — україномовний консультант магазину алмазного інструменту. Відповідай чітко, ввічливо та лише українською. Якщо запит про доставку, оплату, повернення — відповідай відповідно до політики магазину.`,
            })
        }
    } catch (error) {
        console.error('Failed to start session:', error)
    }

    if (openToggle) {
        toggleChat()
    }
}
const toggleSessionType = async () => {
    sessionType.value = sessionType.value === 'normal' ? 'product' : 'normal';
    initSession(false);
}

const productQuestion = async () => {
    loading.value = true
    try {
        if (!response.value) {
            const extractor = await pipeline('feature-extraction', 'Xenova/all-MiniLM-L6-v2')
            const result = await extractor(inputText.value, { pooling: 'mean', normalize: true })
            const vector = result.data
            console.log(vector)

            response.value = await axios.post('/search', { vector })

            const matches = response.value.data.matches || []

            const productList = matches.map((match, i) =>
                `${i + 1}. ${match.metadata.text}`
            ).join('\n')

            messagesConsult.value.push({
                role: 'user',
                content: `Ось мій запит:${inputText.value}. Ось список релевантних товарів:\n${productList} Який краще обрати(обери тільки один)? Поясни чому.`,
            })
        } else {
            const extractor = await pipeline('feature-extraction', 'Xenova/all-MiniLM-L6-v2')
            const result = await extractor(inputText.value, { pooling: 'mean', normalize: true })
            const vector = result.data
            console.log(vector)

            response.value = await axios.post('/search', { vector })

            const matches = response.value.data.matches || []

            const productList = matches.map((match, i) =>
                `${i + 1}. ${match.metadata.text}`
            ).join('\n')

            messagesConsult.value.push({
                role: 'user',
                content: `Ось наступний запит:${inputText.value}. Ось список релевантних товарів:\n${productList}, якщо потрібно, якщо ні - прост дай відповідь відповідно до попередніх запитів.`,
            })
        }
        chatMessages.value.push({ role: 'user', content: inputText.value })
        console.log(messagesConsult.value)

        const res = await axios.post('/ask', {
            messages: messagesConsult.value,
            session_id: session.value.id,
            last_message: inputText.value
        })
        console.log(res)
        const assistantReply = res.data.reply
        console.log('assistantReply:', assistantReply)
        reply.value = assistantReply
        chatMessages.value.push({ role: 'assistant', content: assistantReply })



    } catch (error) {
        console.error('Помилка embedding, пошуку або відповіді:', error)
        reply.value = 'Вибач, щось пішло не так. Спробуй ще раз.'
    } finally {
        loading.value = false
        inputText.value = '';
    }
}

const normalQuestion = async () => {
    loading.value = true
    try {
        if (!response.value) {
            const extractor = await pipeline('feature-extraction', 'Xenova/all-MiniLM-L6-v2')
            const result = await extractor(inputText.value, { pooling: 'mean', normalize: true })
            const vector = result.data
            console.log(vector)

            response.value = await axios.post('/search', { vector })

            const matches = response.value.data.matches || []

            const productList = matches.map((match, i) =>
                `${i + 1}. ${match.metadata.text}`
            ).join('\n')

            messagesConsult.value.push({
                role: 'user',
                content: `Ось мій запит:${inputText.value}. Ось дані із Terms of Service:\n${productList} Дай відповідь на запит? Поясни, чому так.`,
            })
        } else {
            messagesConsult.value.push({
                role: 'user',
                content: `Ось наступний запит:${inputText.value}.`,
            })
        }
        chatMessages.value.push({ role: 'user', content: inputText.value })
        console.log(messagesConsult.value)

        const res = await axios.post('/ask', {
            messages: messagesConsult.value,
            session_id: session.value.id,
            last_message: inputText.value
        })
        console.log(res)
        const assistantReply = res.data.reply
        console.log('assistantReply:', assistantReply)
        reply.value = assistantReply
        chatMessages.value.push({ role: 'assistant', content: assistantReply })



    } catch (error) {
        console.error('Помилка embedding, пошуку або відповіді:', error)
        reply.value = 'Вибач, щось пішло не так. Спробуй ще раз.'
    } finally {
        loading.value = false
        inputText.value = '';
    }
}

function toggleChat() {
    isOpen.value = !isOpen.value
}

</script>

<template>
    <!-- Chat Toggle Button -->
    <button @click="initSession"
        class="fixed bottom-10 right-10 z-50 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition">
        💬
    </button>

    <!-- Chat Box -->
    <div v-if="isOpen"
        class="fixed bottom-20 right-4 w-80 bg-white border border-gray-300 rounded-xl shadow-xl flex flex-col">

        <!-- Header -->
        <div class="bg-blue-600 text-white px-4 py-2 rounded-t-xl flex justify-between items-center">
            <h3 class="font-semibold">Chat</h3>
            <div class="flex items-center space-x-2">
                <button @click="toggleSessionType"
                    class="bg-white text-blue-600 px-2 py-1 rounded text-xs hover:bg-gray-100">
                    {{ sessionType }}
                </button>
                <button @click="toggleChat" class="text-white hover:text-gray-200">✖</button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="p-4 flex-1 overflow-y-auto max-h-60 text-sm text-gray-700">
            <p class="mb-2">👋 Вітаю! Чим можу допомогти?</p>
            <div v-for="(msg, index) in chatMessages" :key="index" class="mb-2">
                <p v-if="msg.role === 'user'"
                    class="text-right bg-gray-200 rounded-lg p-2 inline-block ml-auto max-w-[90%]">
                    {{ msg.content }}
                </p>
                <p v-else-if="msg.role === 'assistant'"
                    class="text-left bg-blue-100 rounded-lg p-2 inline-block mr-auto max-w-[90%]">
                    {{ msg.content }}
                </p>
            </div>
        </div>

        <!-- Input Area -->
        <div v-if="sessionType === 'product'" class="p-2 border-t border-gray-200 flex">
            <input v-model="inputText" @keyup.enter="sendMessage" type="text" placeholder="Type a message..."
                class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none text-sm" />
            <button @click="productQuestion" :disabled="loading || !inputText"
                class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 text-sm">
                Send normal
            </button>
        </div>

        <div v-if="sessionType === 'normal'" class="p-2 border-t border-gray-200 flex">
            <input v-model="inputText" @keyup.enter="sendMessage" type="text" placeholder="Type a message..."
                class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none text-sm" />
            <button @click="normalQuestion" :disabled="loading || !inputText"
                class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 text-sm">
                Send prod
            </button>
        </div>
    </div>
</template>
