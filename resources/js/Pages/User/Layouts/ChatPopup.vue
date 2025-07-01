<script setup>
import { ref } from 'vue'

const isOpen = ref(false)
const message = ref('')

function toggleChat() {
    isOpen.value = !isOpen.value
}

function sendMessage() {
    if (message.value.trim() !== '') {
        // You can emit, store, or send the message
        console.log('User message:', message.value)
        message.value = ''
    }
}
</script>

<template>
    <button @click="toggleChat"
        class="fixed bottom-10 right-10 z-50 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition">
        💬
    </button>
    <!-- Chat Box -->
    <div v-if="isOpen"
        class="fixed bottom-20 right-4 w-80 bg-white border border-gray-300 rounded-xl shadow-xl flex flex-col">
        <!-- Header -->
        <div class="bg-blue-600 text-white px-4 py-2 rounded-t-xl flex justify-between items-center">
            <h3 class="font-semibold">Chat</h3>
            <button @click="toggleChat" class="text-white hover:text-gray-200">✖</button>
        </div>

        <!-- Messages Area -->
        <div class="p-4 flex-1 overflow-y-auto max-h-60 text-sm text-gray-700">
            <p class="mb-2">👋 Hi! How can I help you?</p>
            <!-- Add dynamic messages here -->
        </div>

        <!-- Input Area -->
        <div class="p-2 border-t border-gray-200 flex">
            <input v-model="message" @keyup.enter="sendMessage" type="text" placeholder="Type a message..."
                class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none text-sm" />
            <button @click="sendMessage"
                class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 text-sm">
                Send
            </button>
        </div>
    </div>

</template>
