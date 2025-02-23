# Laravel API Skeleton Setup

This guide provides instructions for setting up the Laravel back-end environment using Docker Compose.

## Prerequisites

- Docker and Docker Compose installed on your machine.
- Clone the back-end repository from [agonibrahimi/laravel-api-skeleton](https://github.com/agonibrahimi/laravel-api-skeleton).

## 1. Clone the Repository

Clone the Laravel back-end repository to your local machine:

```bash
git clone https://github.com/agonibrahimi/laravel-api-skeleton laravel-api-skeleton
cd laravel-api-skeleton
```

## 2. Create the `.env` File

Create a `.env` file in the Laravel project root directory by copying the example file:

```bash
cp .env.example .env
```

You don't need to manually set the `APP_KEY` because it will be generated automatically during container startup if not already set.

## 3. Start the Laravel Environment

Run the following command to start the Docker containers:

```bash
docker compose up --build
```

The entrypoint script performs the following steps:
1. **Installs Composer Dependencies**: If the `vendor` directory is missing, it runs `composer install` with optimized autoloading and excludes development dependencies.
2. **Generates the Application Key**: If the `APP_KEY` is not set, it runs `php artisan key:generate`.
3. **Runs Database Migrations**: Automatically runs `php artisan migrate` to apply any pending migrations.
4. **Starts the Services**: Launches PHP-FPM and Nginx.

The Laravel app should be accessible at `http://localhost`.

## 4. Set Up Laravel Passport

If your application uses Laravel Passport for authentication, follow these steps to configure it properly:

1. **Create a Personal Access Client**

Run the following command to create a personal access client for Passport:

```bash
php artisan passport:client --personal
```

Note down the generated `Client ID` and `Client Secret`.

---

2. **Update the `.env` File**

Add the following environment variables to your `.env` file, replacing `your-client-id` and `your-client-secret` with the actual values from the previous step:

```plaintext
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=your-client-id
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=your-client-secret
```

---

3. **Restart the Laravel Container**

To apply the changes made to the `.env` file, restart the Laravel container:

```bash
docker compose restart
```

Your Laravel application is now configured to use Passport with personal access tokens.


## Common Commands

- **Rebuild the Dockerphp Images:** To rebuild the images after making changes to the `Dockerfile`, run:

  ```bash
  docker compose up --build -d
  ```

- **Stop the Containers:** To stop the containers, run:

  ```bash
  docker compose down
  ```

- **Access a Running Container:** To access a running container, such as the Laravel container, run:

  ```bash
  docker compose exec laravel /bin/bash
  ```

## Troubleshooting

- **Permissions Issues:** If you encounter file permission issues, try adjusting the ownership or permissions within the container.
- **Port Conflicts:** Ensure the ports (3306, 80) are not being used by other services on your machine.

## Notes

- The `.env` file should be added to `.gitignore` to avoid committing sensitive information to version control.
- For production, consider using Docker secrets to handle sensitive data securely.

## Useful Links

- [agonibrahimi/laravel-api-skeleton](https://github.com/agonibrahimi/laravel-api-skeleton)
- [Docker Documentation](https://docs.docker.com/)
- [Laravel Documentation](https://laravel.com/docs)

Now, your Laravel back-end environment should be set up and ready for development, with automatic handling of dependencies, key generation, and migrations.
