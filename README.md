# Sample Store

A PHP e-commerce demo showcasing a customer-facing storefront and an admin panel with eSewa payment gateway integration.

## Requirements

- PHP 8.0+
- MySQL 5.7+/MariaDB 10+
- Web server (Apache/Nginx) or PHP built-in server for local testing

## Setup

1. Create a MySQL database and user, then import the schema:

   ```sql
   CREATE DATABASE sample_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE sample_store;
   SOURCE database/schema.sql;
   ```

   The schema seeds an admin account with email `admin@example.com` and password `password123`.

2. Update `includes/config.php` with your database credentials, `BASE_URL`, and `SITE_URL` (must be the publicly reachable URL when using eSewa callbacks).

3. Configure your web server to point at the project root (so both `public/` and `admin/` are reachable) or run PHP's built-in server from the root:

   ```bash
   php -S localhost:8000 -t .
   ```

4. Visit `http://localhost:8000/public/index.php` for the storefront. Log in using the seeded admin account and open `http://localhost:8000/admin/dashboard.php` to manage the catalogue.

5. For eSewa integration:
   - Keep `ESEWA_ENV` as `test` while using UAT credentials (`EPAYTEST`).
   - Set `SITE_URL` to the URL reachable by eSewa (e.g., `https://your-domain.com/`).
   - When going live, switch `ESEWA_ENV` to `live`, update `ESEWA_MERCHANT_CODE`, and whitelist success/failure URLs in your eSewa merchant portal.

## Features

### Customer side

- Homepage with featured products and categories
- Product catalogue with search and price filters
- Product detail pages with related items
- Cart management stored in session
- Checkout with cash-on-delivery and eSewa payment options
- eSewa payment verification via transaction record API
- Order history, tracking by code, wishlist, user profile updates
- Newsletter signup captured in database

### Admin side

- Dashboard showing key metrics
- Manage products, categories, orders, and customers
- Update order statuses and tracking codes automatically generated per order

## Notes

- Assets are intentionally lightweight (no frameworks) and use a shared stylesheet.
- Replace placeholder thumbnail URLs with your own images when populating products.
- Always enforce HTTPS in production, especially for payment callbacks.
