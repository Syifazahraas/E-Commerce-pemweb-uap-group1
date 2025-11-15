<p align="center">
  <a href="https://github.com/WisnuIbnu/E-Commerce-pemweb-uap">
    <h1 align="center" style="color: #4B47FF">E-Commerce UAP</h1>
  </a>
</p>

This repository is Laravel 12 with the auth starter kit Laravel Breeze and a provided Database Structure. Your task is to submit a Pull Request with your team's version of implementing the task, and your PR will be reviewed by the practicum assistant.

# Getting Started

## Task Explanation

You need to create a simple CRUD E-Commerce interface with several pages:

User Pages (Customer Side):
1. **Homepage:** List of products, including:
    - List of all products
    - List of products based on product category
2. **Product Page:** Display a single product with detail of product, images, category, and reviews
3. **Checkout Page:** Customer fills address, shipping type, and completes purchase
4. **Transaction History Page (Opsional) :** Display past purchases and transaction details

Store Pages (Seller Dashboard):
1. **Store Registration Page:** Seller creates a store profile
2. **Order Management Page:** View and update incoming orders, shipping info, and tracking number
3. **Store Balance Page:** View balance and balance history
4. **Withdrawal Page:** Request withdrawal and view withdrawal history, including:
    - Manage (i.e., update) bank name, bank account name, bank account number
5. **Seller Store Page:** For the author to manage store, including:
    - Manage (i.e., update/delete) store profile  
    - Manage (i.e., create/update/delete) products
    - Manage (i.e., create/update/delete) product categories
    - Manage (i.e., create/update/delete) Product Images

Admin Pages (Owner of e-commerce):
1. **Store Verification Page:** Verify or reject store applications
2. **User & Store Management Page:** View and manage registered all of users and stores

## DB Structure
![db structure](https://github.com/WisnuIbnu/E-Commerce-pemweb-uap/blob/main/public/db_structure.png?raw=true)

## Prerequisites

You will need the following to run project:

-   PHP >= 8.3
-   Composer
-   NPM
-   Database server (MySQL, MariaDB, PostgreSQL, or SQLite)

## Installation

The following steps will guide you through the installation process for running in a development environment locally on your machine:

1. Clone the latest version from the repository
2. Run `composer install` to install the required PHP dependencies
3. Copy the .env.example file to .env and edit the database credentials according to your database server
4. Run `php artisan key:generate` to generate a new application key
5. Run `php artisan migrate` to create the database tables. You can also add the `--seed` flag to seed the database with some dummy data
6. Run `php artisan serve` to start the development server
7. Open another terminal and run `npm install && npm run build` to install the required node modules
8. Run `npm run dev` to compile the assets for development
9. Open your browser and go to `http://localhost:8000` to view the application

## Submitting Assignment:

1. Fork the repository with the name "e-commerce-group-x"
2. Complete the assignment tasks as specified.
3. Create a pull request to our repository's main branch with your changes.