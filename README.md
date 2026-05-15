# ReservasTenis

## Descripción del Proyecto

**ReservasTenis** es una aplicación web desarrollada con Laravel, Vue.js e Inertia.js, diseñada para la gestión de reservas de canchas de tenis. Permite a los usuarios registrarse, ver la disponibilidad de las canchas, realizar reservas y consultar sus estadísticas de juego. Los administradores pueden gestionar canchas, horarios, reservas y usuarios.

## Características Principales

*   **Gestión de Usuarios:** Registro, inicio de sesión y roles (administrador/usuario).
*   **Gestión de Canchas:** Creación, edición y eliminación de canchas de tenis con detalles como tipo, capacidad y precio.
*   **Sistema de Reservas:** Los usuarios pueden reservar canchas según la disponibilidad horaria.
*   **Panel de Administración:** Interfaz para que los administradores gestionen todos los aspectos de la aplicación.
*   **Estadísticas de Usuario:** Los usuarios pueden ver un resumen de sus reservas, canchas favoritas y horas de juego.
*   **Notificaciones por Correo:** Envío de correos automáticos para confirmación, cancelación y cambios de estado de reservas.

## Requisitos del Sistema

Asegúrate de tener instalados los siguientes componentes en tu entorno de desarrollo:

*   **PHP:** `^8.3`
*   **Composer:** Herramienta de gestión de dependencias para PHP.
*   **Node.js:** Versión LTS recomendada.
*   **npm** o **Yarn:** Gestor de paquetes para Node.js.
*   **Base de Datos:** pgsql 

## Instalación

Sigue estos pasos para configurar el proyecto en tu máquina local:

1.  **Clonar el Repositorio:**

    ```bash
    git clone https://github.com/stokkerdev/reservasTenis.git
    cd reservasTenis
    ```

2.  **Instalar Dependencias de Composer:**

    ```bash
    composer install
    ```

3.  **Instalar Dependencias de Node.js:**

    ```bash
    npm install

    ```

4.  **Configurar el Archivo `.env`:**

    Copia el archivo de ejemplo `.env.example` y renómbralo a `.env`.

    ```bash
    cp .env.example .env
    ```

    Abre el archivo `.env` y configura las variables de entorno según tu necesidad. Por defecto, el proyecto usa pgsql

    **Configuración de Base de Datos (pgsql por defecto):**

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=reservas-tenis
    DB_USERNAME=postgres
    DB_PASSWORD=password

    ```

    **Configuración de Correo (Mailtrap para desarrollo):**

    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=tu_usuario_de_mailtrap
    MAIL_PASSWORD=tu_password_de_mailtrap
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="club@reservastenis.com"
    MAIL_FROM_NAME="Club de Tenis"
    ```


5.  **Generar la Clave de Aplicación:**

    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar Migraciones y Seeders:**

    Esto creará las tablas de la base de datos y poblará con datos de prueba, incluyendo un usuario administrador y un usuario normal.

    ```bash
    php artisan migrate:fresh --seed
    ```

    **Credenciales de Usuarios por Defecto:**

    | Rol         | Correo Electrónico    | Contraseña   |
    | :---------- | :-------------------- | :----------- |
    | Administrador | `admin@tenis.com`     | `admin123`   |
    | Usuario     | `jugador@tenis.com`   | `jugador123` |

## Uso

Para iniciar el servidor de desarrollo de Laravel y el servidor de Vite (para los assets de Vue.js) simultáneamente, usa el siguiente comando:

```bash
npm run dev:all
```

Esto iniciará la aplicación en `http://127.0.0.1:8000` (o el puerto configurado en tu `.env`).

## Dependencias Clave

### Backend (Composer)

*   `php`: `^8.3`
*   `laravel/framework`: `^13.0`
*   `inertiajs/inertia-laravel`: `^2.0`
*   `laravel/jetstream`: `^5.5`
*   `laravel/sanctum`: `^4.0`
*   `railsware/mailtrap-php`: `^3.11` (para desarrollo de correos)

### Frontend (npm)

*   `vue`: `^3.3.13`
*   `@inertiajs/vue3`: `^2.0`
*   `tailwindcss`: `^3.4.0`
*   `vite`: `^8.0.0`
*   `concurrently`: `^9.0.1` (para `npm run dev:all`)
*   `chart.js`: `^4.5.1`
*   `vue-chartjs`: `^5.3.3`

