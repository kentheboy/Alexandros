php artisan down
echo "Deploy inprogress..."

echo "git checkout ."
git checkout .

echo "git pull"
git pull

# echo "php artisan l5-swagger:generate"
# php artisan l5-swagger:generate
echo "sudo chown -R $USER:www-data storage/api-docs"
sudo chown -R $USER:www-data storage/api-docs

echo "composer install --no-dev --optimize-autoloader"
composer install --no-dev --optimize-autoloader

echo "php artisan migrate --force"
php artisan migrate --force

echo "php artisan optimize"
php artisan optimize

echo "php artisan view:clear"
php artisan view:clear

echo "php artisan cache:clear"
php artisan cache:clear

php artisan up
echo "Deploy work completed!\n"