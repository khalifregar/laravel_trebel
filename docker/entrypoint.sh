#!/bin/bash

# Tunggu database MySQL ready
until mysql -h db -u root -e "SELECT 1" > /dev/null 2>&1
do
  echo "Menunggu MySQL siap..."
  sleep 2
done

# Jalankan migrate
php artisan migrate --force

# Jalankan Laravel server
php artisan serve --host=0.0.0.0 --port=8000
