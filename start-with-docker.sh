docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan module:migrate --seed Organization
docker compose exec app php artisan module:migrate --seed Recruitment

# mysql -h [hostname] -u [username] -p [database_name] < world.sql