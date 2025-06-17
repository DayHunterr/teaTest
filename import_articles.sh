#!/bin/bash

# Путь к вашему Symfony проекту (укажи свой путь!)
PROJECT_DIR="/var/www/html/teaTest/"

# Переходим в директорию проекта
# shellcheck disable=SC2164
cd "$PROJECT_DIR"

# Загружаем переменные окружения
source .env

# Выполняем команды импорта
php bin/console app:import-articles            # MediaStack
php bin/console app:import-gnews-articles      # GNews
php bin/console app:import-newsapi-articles    # NewsAPI
