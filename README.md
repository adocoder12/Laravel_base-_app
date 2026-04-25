# Laravel Base API & Admin Panel

A robust, production-ready boilerplate built on **Laravel 11+** (PHP 8.2+) designed to accelerate the development of secure REST APIs and administrative backends. This setup comes pre-configured with advanced authentication, access control, and monitoring tools.

## 🚀 Core Features

### 🔐 Security & Auth
* **JWT Authentication:** Full API authentication support via `tymon/jwt-auth`.
* **Two-Factor Authentication (2FA):** Integrated Google Authenticator for secure user logins.
* **Spatie Roles & Permissions:** Pre-configured Access Control List (ACL) for managing user levels.
* **Sanctum Support:** Token-based authentication ready for SPA or mobile integration.

### 🖥️ Admin Interface
* **AdminLTE 3:** Responsive and feature-rich dashboard for management.
* **Visitor Tracking:** Integrated logic to monitor traffic and user activity.
* **Changelog System:** Built-in module to track application version history.
* **PWA Support:** Configuration ready to turn your app into a Progressive Web App.

### 🛠️ Backend Architecture
* **Soft Deletes:** Configured on models to ensure data can be recovered.
* **Vite Integration:** Modern asset bundling for high-speed frontend performance.
* **Pre-configured Middleware:** Optimized for API versioning, CORS, and rate limiting.

---

## 🛠️ Requirements
* **PHP:** 8.2 or higher
* **Composer:** 2.x
* **Node.js & NPM:** Latest LTS
* **Database:** MySQL 8.0+, PostgreSQL, or SQLite

---

## ⚙️ Local Installation

1.  **Clone the Repository**
    ```bash
    git clone [https://github.com/adocoder12/Laravel_base-_app.git](https://github.com/adocoder12/Laravel_base-_app.git)
    cd Laravel_base-_app
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend Assets**
    ```bash
    npm install
    npm run build
    ```

4.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Open your `.env` file and update your database credentials.*

5.  **Security Keys**
    ```bash
    php artisan jwt:secret
    ```

6.  **Database Setup & Seeding**
    ```bash
    php artisan migrate --seed
    ```

---

## 🔑 Access Credentials

After seeding, you can access the administration panel using:

* **Login URL:** `your-domain.test/login`
* **User:** `programador@base.com`
* **Password:** `12345678`

---

## 📡 API Integration

The API is structured under `v1` versioning. Routes are managed in `routes/api.php`.

### Example: Login (JWT)
**Endpoint:** `POST /api/v1/login`

**Payload:**
```json
{
    "email": "programador@base.com",
    "password": "12345678"
}





<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


