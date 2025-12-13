# Zay Shop API

## 🚀 Project Overview
**Zay Shop API** is a RESTful backend application built with **Laravel** for an e-commerce clothing store.  
The API provides secure authentication, product and category management, cart functionality, and order processing. It is designed to be consumed by web or mobile frontend applications.

---

## ✨ Features
- User authentication (Register, Login, Logout) using **Laravel Sanctum**
- Admin authentication system
- Google OAuth login
- Email verification
- Product & Category management (CRUD)
- Shopping cart system
- Order & checkout system
- Secure and scalable RESTful API design

---

## 🛠 Technologies Used
- PHP (Laravel)
- Laravel Sanctum
- MySQL
- RESTful API
- OAuth (Google Sign-In)
- JSON

---

## 🔐 Authentication
This API uses **Bearer Token Authentication** with Laravel Sanctum.

```http
Authorization: Bearer {access_token}
```

Protected routes require a valid access token.

---

## 📡 API Endpoints Overview

### 🔑 User Authentication
| Method | Endpoint | Description |
|------|---------|------------|
| POST | `/register` | Register a new user |
| POST | `/login` | User login |
| POST | `/logout` | Logout authenticated user |
| POST | `/auth/google` | Login with Google |

---

### 🛒 Cart (Authenticated)
| Method | Endpoint | Description |
|------|---------|------------|
| GET | `/cart` | Get cart items |
| GET | `/cart/{cartItem}` | Get single cart item |
| POST | `/cart/{productId}` | Add product to cart |
| PUT | `/cart/update/{product}` | Update cart item |
| DELETE | `/cart/remove/{item}` | Remove item from cart |
| DELETE | `/cart/clear` | Clear cart |

---

### 📦 Orders (Authenticated)
| Method | Endpoint | Description |
|------|---------|------------|
| POST | `/checkout` | Process checkout |
| GET | `/orders` | Get user orders |
| GET | `/orders/{order}` | Get order details |

---

### 🛍 Products
| Method | Endpoint | Description |
|------|---------|------------|
| GET | `/product` | Get all products |
| POST | `/product` | Create a product |
| GET | `/product/{id}` | Get product by ID |
| PUT | `/product/{id}` | Update product |
| DELETE | `/product/{id}` | Delete product |

---

### 📂 Categories
| Method | Endpoint | Description |
|------|---------|------------|
| GET | `/category` | Get all categories |
| POST | `/category` | Create category |
| GET | `/category/{id}` | Get category by ID |
| PUT | `/category/{id}` | Update category |
| DELETE | `/category/{id}` | Delete category |

---

## ⚙️ Installation & Setup

Clone the repository:
```bash
git clone https://github.com/kaisshaheen/zay-shop-api.git
cd zay-shop-api
```

Install dependencies:
```bash
composer install
```

Environment setup:
```bash
cp .env.example .env
php artisan key:generate
```

Run migrations:
```bash
php artisan migrate
```

Start the development server:
```bash
php artisan serve
```

---

## 📌 Notes
- All protected endpoints require authentication.
- API follows RESTful best practices.
- Suitable for frontend frameworks and mobile applications.

---

## 👨‍💻 Author
**Kais Shaheen**  
Backend Developer – Laravel REST APIs

GitHub: https://github.com/kaisshaheen

