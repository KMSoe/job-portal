php artisan migrate:fresh --seed
php artisan world:install
php artisan module:migrate --seed Organization
php artisan module:migrate --seed Recruitment

# mysql -h [hostname] -u [username] -p [database_name] < world.sql