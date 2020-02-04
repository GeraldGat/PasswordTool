1. Clone the repository using `git clone https://github.com/GeraldGat/PasswordTool`
2. Install the dependencies using `composer install` and `npm install`
3. Create a .env file based on the .env.example
4. Generate the key using `php artisan key:generate`
5. Configure the `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` and the `MAIL_XXX` in the .env
6. Generate the database with `php artisan migrate --seed`
7. Create an admin user using `php artisan user:admin:create {email} {password} {confirmPassword}`
