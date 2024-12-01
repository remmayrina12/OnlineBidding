@echo off
cd /d "C:\path\to\your\laravel\project"
php artisan queue:work --sleep=3 --tries=3
