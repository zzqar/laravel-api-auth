# Простое API на Laravel с JWT и Swagger

## Описание

Этот проект представляет собой простое API на Laravel, реализующее следующие функциональные возможности:

1. **Авторизация с использованием JWT**
2. **Получение данных об авторизованном пользователе по токену**
3. **Выход из системы**

API документировано с использованием Swagger для упрощения тестирования и использования.

## Установка

1. Клонируйте репозиторий:

    ```bash
    git clone https://github.com/zzqar/laravel-api-auth.git
    ```

2. Перейдите в директорию проекта:

    ```bash
    cd laravel-api-auth
    ```

3. Установите зависимости Composer:

    ```bash
    composer install
    ```

4. Настройте файл `.env`. Скопируйте файл `.env.example` и настройте параметры в соответствии с вашей средой:

    ```bash
    cp .env.example .env
    ```

5. Сгенерируйте ключ приложения:

    ```bash
    php artisan key:generate
    ```

6. Выполните миграции базы данных:

    ```bash
    php artisan migrate
    ```

7. Установите Swagger пакеты для документации:

    ```bash
    composer require darkaonline/l5-swagger
    ```

8. Сгенерируйте документацию Swagger:

    ```bash
    php artisan l5-swagger:generate
    ```

9. Запустите сервер разработки:

    ```bash
    php artisan serve
    ```
## Документация Swagger

Документация API доступна по адресу: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)


## Использование

### 1. Регистрация нового пользователя

- **Метод**: POST
- **URL**: `/api/register`
- **Параметры запроса**:
    ```json
    {
        "name": "John",
        "email": "john@gmail.com",
        "password": "123456",
        "password_confirmation": "123456"
    }
    ```
- **Ответ**:
    - **200 OK**:
        ```json
        {
            "status": 200,
            "message": "успешно"
        }
        ```
    - **400 Bad Request**:
        ```json
        {
            "status": 400,
            "message": "Ошибка валидации"
        }
        ```

### 2. Аутентификация пользователя и получение JWT токена

- **Метод**: POST
- **URL**: `/api/login`
- **Параметры запроса**:
    ```json
    {
        "email": "john@gmail.com",
        "password": "123456"
    }
    ```
- **Ответ**:
    - **200 OK**:
        ```json
        {
            "status": true,
            "message": "Пользователь успешно вошел в систему",
            "token": "eyJhbGciOiJIUzI1NiICI6IkpXVCJ9..."
        }
        ```
    - **422 Unprocessable Entity**:
        ```json
        {
            "status": false,
            "message": "Неверные данные для входа"
        }
        ```

### 3. Получение данных профиля аутентифицированного пользователя

- **Метод**: GET
- **URL**: `/api/show`
- **Заголовок**: `Authorization: Bearer {token}`
- **Ответ**:
    - **200 OK**:
        ```json
        {
            "status": true,
            "message": "Пользователь получен",
            "data": {
                "id": 1,
                "name": "John",
                "email": "john.doe@example.com"
            }
        }
        ```
    - **401 Unauthorized**:
        ```json
        {
            "status": false,
            "message": "Неавторизован"
        }
        ```

### 4. Выход из системы

- **Метод**: GET
- **URL**: `/api/logout`
- **Заголовок**: `Authorization: Bearer {token}`
- **Ответ**:
    - **200 OK**:
        ```json
        {
            "status": true,
            "message": "Успешный выход из системы"
        }
        ```
    - **401 Unauthorized**:
        ```json
        {
            "status": false,
            "message": "Неавторизован"
        }
        ```

