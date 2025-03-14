
<p align="center">
  <img src="./assets/logo.svg" alt="Logo">
</p>


# Mesa de Ayuda

MESA DE AYUDA es un sistema de gestión de tickets de soporte que permite a los usuarios crear, asignar, y gestionar tickets de manera eficiente. La plataforma incluye autenticación de usuarios y la gestión de roles y permisos utilizando MoonShine y Spatie Laravel Permissions.


## Tecnologías Utilizadas

- **Laravel**: Framework PHP para el desarrollo de aplicaciones web.
- **MoonShine**: Herramienta de administración para Laravel que facilita la creación de paneles de control.
- **MoonShine Roles-Permissions**: Extensión de MoonShine para gestionar roles y permisos de usuario.
- **Spatie Laravel Permissions**: Paquete de Laravel para la gestión avanzada de roles y permisos.
- **MySQL**: Sistema de gestión de bases de datos relacional.

## Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1. Clona el repositorio:
   ```bash
   git clone https://github.com/vramirezreina/MESA_AYUDA.git
   cd MESA_AYUDA
   ```

2. Instala las dependencias de PHP y JavaScript:
   ```bash
   composer install
   npm install
   npm run dev
   ```

3. Instalar moonshine
    ```bash
   composer require moonshine/moonshine
   php artisan moonshine:install
   ```
4. Instalar Spatie Laravel Permissions
    ```bash
    composer require spatie/laravel-permission
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   ```   
5. Instalar el sistema de roles y permisos en MoonShine usando el paquete MoonShine Roles-Permissions y creación de rol SuperAdministrador

    ```bash
   php artisan moonshine-rbac:install
   php artisan moonshine-rbac:user
   ```

6. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   ```

7. Configura la base de datos en el archivo `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistemas
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```



8. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```



9. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```