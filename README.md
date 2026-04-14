# TechGear — Full-Stack PC Webshop

A full-stack e-commerce application built as a portfolio project. Customers can browse products, manage a cart, and place orders. Admins get a full management panel for products, categories, and users.

**Live demo →** [techgear-fullstack-webshop.vercel.app](https://techgear-fullstack-webshop.vercel.app)

---

## Tech stack

| Layer | Technology |
|---|---|
| Frontend | Vue 3, Pinia, Vue Router, Axios |
| Backend | PHP 8.2, Bramus Router |
| Auth | JWT (access + refresh tokens) |
| Database | MariaDB |
| Local dev | Docker Compose, Nginx, phpMyAdmin |
| Deployment | Vercel (frontend) · Railway (backend) |

---

## Demo accounts

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `Admin123` |
| User | `username` | `Password123` |

The admin account unlocks the product/category/user management panel. The user account can browse, add to cart, and check out.

---

## Features

**Storefront**
- Product listing with search and category filtering
- Paginated product grid with stock availability
- Product detail page

**Cart & checkout**
- Add, remove, and update item quantities
- Stock-aware: quantities are capped to available stock
- Checkout converts the cart into an order
- Order history page per user

**Authentication**
- JWT access tokens with automatic refresh via Axios interceptors
- Login, signup, and token-based session persistence

**Admin panel** *(admin role required)*
- Create, edit, and delete products
- Manage categories
- View and update user roles

---

## Project structure

```
techgear-fullstack-webshop/
├── Frontend/          # Vue 3 SPA
│   └── src/
│       ├── components/    # Page components and UI
│       ├── stores/        # Pinia stores (auth, cart)
│       ├── router/        # Vue Router with route guards
│       └── axios-auth.js  # Axios instance with JWT interceptor
└── Backend/           # PHP REST API
    ├── app/
    │   ├── public/        # Entry point + route definitions
    │   ├── controllers/   # Request handling
    │   ├── services/      # Business logic
    │   ├── repositories/  # Data access layer
    │   └── models/        # Data models
    ├── sql/               # Database seed/init scripts
    ├── docker-compose.yml
    └── Dockerfile         # Production image (Railway)
```

---

## API reference

### Products
| Method | Endpoint | Auth |
|---|---|---|
| `GET` | `/products` | — |
| `GET` | `/products/:id` | — |
| `POST` | `/products` | Admin |
| `PUT` | `/products/:id` | Admin |
| `DELETE` | `/products/:id` | Admin |

### Categories
| Method | Endpoint | Auth |
|---|---|---|
| `GET` | `/categories` | — |
| `POST` | `/categories` | Admin |
| `PUT` | `/categories/:id` | Admin |
| `DELETE` | `/categories/:id` | Admin |

### Users
| Method | Endpoint | Auth |
|---|---|---|
| `POST` | `/users/signup` | — |
| `POST` | `/users/login` | — |
| `POST` | `/users/refresh` | — |

### Cart
| Method | Endpoint | Auth |
|---|---|---|
| `GET` | `/cart` | User |
| `POST` | `/cart` | User |
| `PUT` | `/cart` | User |
| `DELETE` | `/cart/:id` | User |
| `DELETE` | `/cart` | User |
| `POST` | `/cart/checkout` | User |

### Orders
| Method | Endpoint | Auth |
|---|---|---|
| `GET` | `/orders` | User |

### Admin
| Method | Endpoint | Auth |
|---|---|---|
| `GET` | `/admin/users` | Admin |
| `PUT` | `/admin/users/:id` | Admin |

---

## Local development

### Backend

**Requirements:** Docker and Docker Compose

```bash
cd Backend
cp .env.example .env
docker-compose up --build
```

Services after startup:
- API: `http://localhost`
- phpMyAdmin: `http://localhost:8080`

The `sql/` directory is mounted as the MariaDB init directory, so the schema and seed data are applied automatically on first run.

### Frontend

**Requirements:** Node.js 18+

```bash
cd Frontend
cp .env.example .env        # sets VITE_API_BASE_URL=http://localhost
npm install
npm run dev                 # http://localhost:5173
```

---

## Deployment

**Backend (Railway)**
- Built from `Backend/Dockerfile`
- Configured via `railway.toml`
- Environment variables required: `APP_ENV`, `APP_CORS_ORIGIN`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `JWT_SECRET`
- Railway's MySQL plugin injects `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE` automatically

**Frontend (Vercel)**
- Standard Vite build (`npm run build`, output `dist/`)
- Environment variable required: `VITE_API_BASE_URL` set to the Railway backend URL
