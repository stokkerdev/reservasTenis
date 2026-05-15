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
*   **Páginas Informativas:** Secciones de Términos de Servicio, Política de Privacidad y Contacto.
*   **Almacenamiento en la Nube:** Soporte para almacenamiento de imágenes de canchas en buckets compatibles con S3 (como Cloudflare R2).

## Requisitos del Sistema

Asegúrate de tener instalados los siguientes componentes en tu entorno de desarrollo:

*   **PHP:** `^8.3`
*   **Composer:** Herramienta de gestión de dependencias para PHP.
*   **Node.js:** Versión LTS recomendada.
*   **npm** o **Yarn:** Gestor de paquetes para Node.js.
*   **Base de Datos:** SQLite (por defecto para desarrollo) o MySQL/PostgreSQL.

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
    # o si usas Yarn
    # yarn install
    ```

4.  **Configurar el Archivo `.env`:**

    Copia el archivo de ejemplo `.env.example` y renómbralo a `.env`.

    ```bash
    cp .env.example .env
    ```

    Abre el archivo `.env` y configura las variables de entorno según tu necesidad. Por defecto, el proyecto usa SQLite para la base de datos, pero puedes cambiarlo a MySQL o PostgreSQL.

    **Configuración de Base de Datos (SQLite por defecto):**

    ```env
    DB_CONNECTION=sqlite
    # DB_DATABASE=/ruta/a/tu/base_de_datos.sqlite (opcional, si no, se creará en database/database.sqlite)
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

    **Configuración de Almacenamiento en la Nube (para despliegue):**

    Si vas a desplegar en un entorno como Laravel Cloud que usa un bucket compatible con S3 (ej. Cloudflare R2), configura estas variables. De lo contrario, déjalas vacías o con los valores por defecto.

    ```env
    FILESYSTEM_DISK=s3
    AWS_ACCESS_KEY_ID=tu_access_key_id
    AWS_SECRET_ACCESS_KEY=tu_secret_access_key
    AWS_DEFAULT_REGION=auto # o la región de tu bucket (ej. us-east-1)
    AWS_BUCKET=tu_nombre_de_bucket
    AWS_ENDPOINT=tu_endpoint_de_bucket # ej. https://xxxxxxxx.r2.cloudflarestorage.com
    AWS_USE_PATH_STYLE_ENDPOINT=false
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

7.  **Generar Enlace Simbólico de Almacenamiento (Solo para desarrollo local):**

    Si estás usando el disco `local` para `public` y quieres que las imágenes subidas sean accesibles vía URL, necesitas crear un enlace simbólico.

    ```bash
    php artisan storage:link
    ```

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
*   `league/flysystem-aws-s3-v3`: `^3.0` (para soporte de S3/R2)
*   `railsware/mailtrap-php`: `^3.11` (para desarrollo de correos)

### Frontend (npm)

*   `vue`: `^3.3.13`
*   `@inertiajs/vue3`: `^2.0`
*   `tailwindcss`: `^3.4.0`
*   `vite`: `^8.0.0`
*   `concurrently`: `^9.0.1` (para `npm run dev:all`)
*   `chart.js`: `^4.5.1`
*   `vue-chartjs`: `^5.3.3`

## Despliegue en Laravel Cloud

Para desplegar en Laravel Cloud, asegúrate de que las variables de entorno para el bucket S3/R2 estén configuradas en tu panel de control, tal como se describe en la sección de configuración del archivo `.env`.

El proyecto está configurado para usar el disco `s3` cuando `FILESYSTEM_DISK=s3` en tu `.env` de producción, lo que dirigirá las subidas de archivos directamente a tu bucket de Laravel Cloud.

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---

**Autor:** Manus AI (en representación del desarrollador)
**Fecha:** 15 de Mayo de 2026
