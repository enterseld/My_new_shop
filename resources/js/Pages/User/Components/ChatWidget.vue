<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const showChat = ref(false);
const chatSessionId = ref(null);
const messageText = ref('');
const messages = ref([]);
let echo = null;

// Initialize Pusher/Echo
const initializeEcho = () => {
  window.Pusher = Pusher;
  echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
    forceTLS: true,
    authEndpoint: '/broadcasting/auth',
    // Cookies will be automatically included
  });
};

const startSession = async () => {
  try {
    const response = await axios.post('/chat/start');
    chatSessionId.value = response.data.id;
    console.log(response)
    showChat.value = true;
    await getMessages();
    
    // Subscribe to channel after session starts
    if (echo) {
      echo.private(`chat.${chatSessionId.value}`)
        .listen('MessageSent', (event) => {
          messages.value.push(event.message);
          scrollToBottom();
        });
    }
  } catch (error) {
    console.error('Error starting chat session:', error);
  }
};

const getMessages = async () => {
  try {
    const response = await axios.get(`/chat/messages/${chatSessionId.value}`);
    messages.value = Array.isArray(response.data) ? response.data : [];
    scrollToBottom();
  } catch (error) {
    console.error('Error getting messages:', error);
  }
};

const sendMessage = async () => {
  try {
    if (!messageText.value.trim()) return;
    const response = await axios.post('/chat/send', {
      chat_session_id: chatSessionId.value,
      message_text: messageText.value,
    });
    messages.value.push(response.data);
    console.log(response);
    messageText.value = '';
    scrollToBottom();
  } catch (error) {
    console.error('Error sending message:', error);
  }
};

const scrollToBottom = () => {
  setTimeout(() => {
    const chatContainer = document.querySelector('.overflow-y-auto');
    if (chatContainer) {
      chatContainer.scrollTop = chatContainer.scrollHeight;
    }
  }, 50);
};

const closeChat = () => {
  showChat.value = false;
  if (echo && chatSessionId.value) {
    echo.leave(`chat.${chatSessionId.value}`);
  }
};

onMounted(() => {
  initializeEcho();
});
</script>

<template>
  <div v-if="showChat" class="fixed bottom-4 right-4 w-80 h-96 bg-white border border-gray-300 rounded-lg shadow-lg flex flex-col">
    <div class="bg-blue-600 text-white p-4 text-center text-lg font-semibold">
      Консультант
      <button @click="closeChat" class="absolute top-2 right-2 text-white text-xl">
        &times;
      </button>
    </div>
    <div class="flex-grow p-4 overflow-y-auto">
      <div v-for="message in messages" :key="message.id" class="mb-4">
        <div :class="{
          'bg-blue-100 ml-auto': message.sender_type === 'user', 
          'bg-gray-100 mr-auto': message.sender_type === 'guest'
        }" class="p-2 rounded-lg max-w-xs">
          <p>{{ message.message_text }}</p>
        </div>
      </div>
    </div>
    <div class="bg-gray-100 p-4 flex">
      <input 
        v-model="messageText" 
        @keyup.enter="sendMessage"
        placeholder="Напишіть повідомлення..." 
        class="flex-grow p-2 border border-gray-300 rounded-lg mr-2" 
      />
      <button 
        @click="sendMessage" 
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        :disabled="!messageText.trim()"
      >
        Відправити
      </button>
    </div>
  </div>

  <button 
    v-else 
    @click="startSession" 
    class="fixed bottom-4 right-4 p-4 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700"
  >
    Чат з консультантом
  </button>
</template>

