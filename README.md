# Sistema de Fichaje de Empleados

Este proyecto es un sistema de fichaje de empleados desarrollado en Laravel, con MySQL y un frontend moderno y responsive. Permite gestionar empleados, registrar entradas y salidas, y consultar historial y resumen de horas trabajadas.

## Requisitos
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js >= 16.x y npm (para compilar assets)
- Laravel 10+

## Instalación de MySQL

1. Descarga e instala MySQL desde [mysql.com](https://dev.mysql.com/downloads/) o usa XAMPP/Laragon que incluye MySQL
2. Inicia el servicio MySQL
3. Crea la base de datos:

   ```bash
   mysql -u root -p
   CREATE DATABASE sistema_fichaje;
   exit;
   ```

   O si prefieres usar phpMyAdmin (incluido en XAMPP/Laragon):
   - Accede a http://localhost/phpmyadmin
   - Crea una nueva base de datos llamada `sistema_fichaje`

## Instalación del Proyecto

1. Clona el repositorio:
   ```bash
   git clone <REPO_URL>
   cd sistema-fichaje-nuevo
   ```

2. Instala dependencias PHP:
   ```bash
   composer install
   ```

3. Instala dependencias de frontend:
   ```bash
   npm install
   ```

4. Copia el archivo de entorno y configura la base de datos:
   ```bash
   cp .env.example .env
   ```
   Edita el archivo `.env` y configura:
   ```
   DB_DATABASE=sistema_fichaje
   DB_USERNAME=root
   DB_PASSWORD=tu_contraseña
   ```

5. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

## Migraciones y datos de prueba

1. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

2. (Opcional) Rellena la base de datos con empleados y registros de ejemplo:
   ```bash
   php artisan db:seed
   ```
3. (Opcional) Para regenerar toda la base de datos y usar los seeders.:
``` php artisan migrate:refresh --seed
```
## Compilar assets (CSS/JS)

Para producción:
```bash
npm run build
```

Para desarrollo:
```bash
npm run dev
```

## Ejecución

Inicia el servidor de desarrollo de Laravel:
```bash
php artisan serve
```

Accede a [http://localhost:8000]

## Script SQL independiente

Si prefieres importar la base de datos manualmente, usa el archivo `script_fichaje.sql` incluido en la raíz del proyecto.

## Notas adicionales

- El sistema es responsive y accesible
- Incluye feedback visual con SweetAlert y badges dinámicos
- Código organizado y fácil de mantener
- Soporte para múltiples navegadores modernos

---

### Enrique Cuevas Garcia
Desarrollado para entrevista técnica.
