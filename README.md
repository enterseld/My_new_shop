# 🛒 Laravel + Vue E-Commerce Platform with AI Consultant Chat 🤖

A modern, full-stack e-commerce web application built with **Laravel** (back-end) and **Vue 3** (front-end), featuring a powerful **AI Consultant Chatbot** to assist users with intelligent product recommendations and customer support.

---

## 🚀 Features

### 🛍️ E-Commerce Functionality
- User registration & authentication
- Product listing, search, filtering
- Shopping cart and checkout
- Order management
- Admin dashboard for managing products, categories, and orders

### 💬 AI-Powered Consultant Chat
- Semantic product search & recommendation
- GPT-based conversational interface
- Context-aware assistance
- Integration with product metadata

### ⚙️ Technologies Used
| Stack        | Tools / Frameworks                            |
|--------------|-----------------------------------------------|
| **Frontend** | Vue 3, Vite, Tailwind CSS, Axios              |
| **Backend**  | Laravel 10, Laravel Sanctum, Eloquent ORM     |
| **AI**       | OpenAI / Transformers.js, Pinecone (optional) |
| **Database** | MySQL / MariaDB                               |
| **DevOps**   | Docker, Laravel Octane, Nginx, Composer       |

---

## 📸 Screenshots

<!-- Add screenshots or gifs here -->
![Home Page](screenshots/home.png)
![AI Chatbot](screenshots/chat.png)
![Admin Dashboard](screenshots/admin.png)

---

## 🔧 Installation

### Prerequisites
- PHP >= 8.2
- Node.js >= 18
- Composer
- MySQL or MariaDB
- Docker (optional)

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/laravel-vue-ecommerce-ai.git
cd laravel-vue-ecommerce-ai
2. Set Up Backend (Laravel)
bash
Копіювати
Редагувати
composer install
cp .env.example .env
php artisan key:generate

# Configure your .env database settings
php artisan migrate --seed
php artisan storage:link
3. Set Up Frontend (Vue)
bash
Копіювати
Редагувати
cd resources/js
npm install
npm run dev
4. Run the Project
bash
Копіювати
Редагувати
php artisan serve
Or use Octane for better performance:

bash
Копіювати
Редагувати
php artisan octane:start
