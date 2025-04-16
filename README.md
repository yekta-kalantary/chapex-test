Got it! Here's the **final production-ready `README.md`** content, clean and ready to copy:

# Chapex Test – Laravel Passport API

This is a Laravel-based API project using [Laravel Passport](https://laravel.com/docs/passport) for authentication with personal access tokens.

## Getting Started

Follow the steps below to set up the project locally:

### 1. Clone the Repository

```bash
git clone https://github.com/yekta-kalantary/chapex-test.git
cd chapex-test
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set Up Passport

```bash
php artisan passport:keys
php artisan passport:client --personal
```

### 5. (Optional) Run Migrations

```bash
php artisan migrate
```

## Notes

- Ensure your `.env` file is correctly configured (database, mail, etc.).
- For more information on Passport, refer to the [official Laravel Passport documentation](https://laravel.com/docs/passport).

## License

This project is open-source and available under the [MIT license](LICENSE).
```

Let me know if you want to add usage examples, API docs, or anything else!
