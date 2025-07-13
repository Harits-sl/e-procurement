# 🛒 E-Procurement API

This is a **basic E-Procurement API** built with **Laravel 12**, featuring:

- User authentication (register & login)
- Vendor registration
- Product catalog CRUD by vendor
- Image upload for product

---

## 🚀 **Tech Stack**

- **PHP** 8.3+
- **Laravel** 12.x
- **Laravel Sanctum** for API authentication
- **MySQL** (can switch to PostgreSQL)

---

## 📁 **Installation**

1. **Clone Repository**

```bash
git clone https://github.com/yourusername/e-procurement-api.git
cd e-procurement-api
```

2 **Install Dependencies**
```bash
composer install
```

3 **Copy .env**
```bash
cp .env.example .env
```

4 **Generate Key**
```bash
php artisan key:generate
```

5 **Run Migrationy**
```bash
php artisan migrate
```

6 **Storage Link (For Image Upload)**
```bash
php artisan storage:link
```

7 **Run Server**
```bash
php artisan serve
```

---

## 📁 **Authentication**
**🔐 Authentication**
Uses Laravel Sanctum for token-based API authentication.

***User*** <br/>
POST /api/register – User registration <br/>
POST /api/login – User login  <br/>
Field name, email, password <br/>

***🏢 Vendor Registration*** <br/>
POST /api/vendors – Register as vendor <br/>
Fields: vendor_name, address, contact <br/>
<br/>
***📦 Product Catalog (CRUD)***
| Method | Endpoint           | Description                        |
| ------ | ------------------ | ---------------------------------- |
| GET    | /api/products      | List all products (current vendor) |
| POST   | /api/products      | Create product                     |
| GET    | /api/products/{id} | Show product detail                |
| PUT    | /api/products/{id} | Update product                     |
| DELETE | /api/products/{id} | Delete product                     |

Fields: vendor_id, product_name, description, price, stock, image
