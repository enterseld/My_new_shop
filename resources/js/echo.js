import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,  // замінити на правильний ключ
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,  // замінити на правильний кластер
    encrypted: true,
    wsHost: 'ws.pusherapp.com',  // для Pusher, зазвичай це ws.pusherapp.com
    wsPort: 80,
    wssPort: 443,
    forceTLS: true,  // Pusher вимагає використання HTTPS для безпечних з'єднань
    enabledTransports: ['ws', 'wss'],
});