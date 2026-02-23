// Платформа для проведения и оценивания конкурсов

// Описание
Веб-приложение для проведения конкурсов
Администратор создает конкурсы и редактирует их
Проверяющий проверяет загруженные работы 
Участник участвует в конкурсах

// Требования
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js & NPM

// Установка


1. Установить зависимости
composer install
npm install
npm run build

2. Настройка окружения
bash
cp .env.example .env
php artisan key:generate

3. Настройка базы данных (в файле .env)
text
B_CONNECTION=mysql
DB_HOST=MySql-8.0
DB_PORT=3306
DB_DATABASE=platform
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file

4. S3 хранилище
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID= _____
AWS_SECRET_ACCESS_KEY= ____
AWS_DEFAULT_REGION=ru-1
AWS_BUCKET=108441407d0b-college
AWS_ENDPOINT=https://s3.ru1.storage.beget.cloud
AWS_URL=https://108441407d0b-college.s3.ru1.storage.beget.cloud
AWS_USE_PATH_STYLE_ENDPOINT=true

5. Запустить миграции и сиды
bash
php artisan migrate
php artisan db:seed

6. Запустить очередь (в отдельном окне)
bash
php artisan queue:work

7. Запустить сервер
bash
php artisan serve

