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
        <div :class="{'bg-blue-100': message.sender_type === 'user', 'bg-gray-100': message.sender_type === 'guest'}" class="p-2 rounded-lg max-w-xs">
          <p>{{ message.message_text }}</p>
        </div>
      </div>
    </div>
    <div class="bg-gray-100 p-4 flex">
      <input v-model="messageText" placeholder="Напишіть повідомлення..." class="flex-grow p-2 border border-gray-300 rounded-lg mr-2" />
      <button @click="sendMessage" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Відправити
      </button>
    </div>
  </div>

  <button v-else @click="startSession" class="fixed bottom-4 right-4 p-4 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700">
    Чат з консультантом
  </button>
</template>

<script>
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
  data() {
    return {
      showChat: false,
      chatSessionId: null,
      messageText: '',
      messages: [],
    };
  },
  mounted() {
    // Налаштування для підключення до Pusher через Laravel Echo
    window.Pusher = Pusher;

    this.echo = new Echo({
      broadcaster: 'pusher',
      key: import.meta.env.VITE_PUSHER_APP_KEY,
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
      encrypted: true,
    });

    // Підписка на канал чату
    if (this.chatSessionId) {
      this.echo.channel('chat.' + this.chatSessionId)
        .listen('MessageSent', (event) => {
          this.messages.push(event.message);
        });
    }
  },
  methods: {
    async startSession() {
      try {
        // Запит на сервер для початку сесії чату
        const response = await axios.post('/chat/start');
        this.chatSessionId = response.data.id;
        this.showChat = true;
        this.getMessages();
      } catch (error) {
        console.error('Error starting chat session:', error);
      }
    },

    async getMessages() {
      try {
        // Отримати повідомлення чату
        const response = await axios.get(`/chat/messages/${this.chatSessionId}`);
        this.messages = response.data;
      } catch (error) {
        console.error('Error getting messages:', error);
      }
    },

    async sendMessage() {
      try {
        if (this.messageText.trim() === '') return;

        // Надіслати повідомлення
        const response = await axios.post('/chat/send', {
          chat_session_id: this.chatSessionId,
          message_text: this.messageText,
        });

        this.messages.push(response.data); // Додати нове повідомлення
        this.messageText = ''; // Очистити поле введення
      } catch (error) {
        console.error('Error sending message:', error);
      }
    },

    closeChat() {
      this.showChat = false;
    },
  },
};
</script>

<style scoped>
/* Можна додавати власні стилі, якщо потрібно */
</style>
