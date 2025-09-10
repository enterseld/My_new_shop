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
- Conversational interface
- Context-aware assistance
- Integration with product metadata

### ⚙️ Technologies Used
| Stack        | Tools / Frameworks                            |
|--------------|-----------------------------------------------|
| **Frontend** | Vue 3, Vite, Tailwind CSS, Axios              |
| **Backend**  | Laravel 11, Laravel Sanctum, Eloquent ORM     |
| **AI**       | OpenRouter, Transformers.js, Pinecone         |
| **Database** | MySQL                                         |

---

## 🔧 Installation

### Prerequisites
- PHP >= 8.2
- Node.js >= 18
- Composer
- MySQL
- Docker (optional)

### 1. Clone the Repository

```bash
git clone https://github.com/enterseld/My_new_shop.git
cd My_new_shop
```
2. Set Up Backend (Laravel)
```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure your .env database settings
php artisan migrate --seed
php artisan storage:link
```
3. Set Up Frontend (Vue)
```bash
cd resources/js
npm install
npm run dev
```
4. Run the Project
```bash
php artisan serve
```
Or use Octane for better performance(Not for Windows):
```bash
php artisan octane:start
```
