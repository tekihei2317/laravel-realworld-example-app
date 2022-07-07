setup:
	cd backend && composer install
	cd backend && cp .env.example .env && php artisan key:generate && php artisan jwt:secret
	cd backend && touch database/database.sqlite && php artisan migrate
