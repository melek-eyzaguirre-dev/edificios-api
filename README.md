# Edificios ERP | Backend API

API REST para la administración de edificios y condominios. Este proyecto provee autenticación, autorización por roles y endpoints para la operación diaria de un edificio.

> Proyecto personal con propósito educativo y de portafolio. El diseño busca servir como base para una solución comercial, con énfasis en seguridad, trazabilidad y crecimiento modular.

**Backend:** Laravel 13 + PHP 8.3  
**Frontend:** [edificios-erp](https://github.com/melek-eyzaguirre-dev/edificios-erp)

## Capacidades de la API

- Autenticación con Laravel Sanctum.
- Autorización por roles mediante `spatie/laravel-permission`.
- Administradoras, condominios y unidades.
- Residentes, personal y turnos.
- Visitas y reporte de visitas no autorizadas.
- Reservas y espacios comunes.
- Estacionamientos y ocupaciones.
- Novedades, proveedores e inventario.
- Asistencias, entradas, salidas y solicitudes de ausencia.
- Gastos comunes, cargos, distribución y pagos.
- Resúmenes para dashboard administrativo y residente.
- Feedback de residentes.

Las rutas protegidas requieren autenticación mediante Sanctum. La definición completa de endpoints está en `routes/api.php`.

## Tecnologías

- PHP 8.3+
- Laravel 13
- Laravel Sanctum
- Spatie Laravel Permission
- PHPUnit
- SQLite para desarrollo inicial, con posibilidad de usar MySQL

## Instalación local

Requisitos: PHP 8.3 o superior, Composer, Node.js y una base de datos.

```bash
git clone https://github.com/melek-eyzaguirre-dev/edificios-api.git
cd edificios-api
composer install
```

En Windows PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Configura la conexión de base de datos y el origen permitido para el frontend:

```env
APP_URL=http://edificios-api.test
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

Ejecuta las migraciones:

```bash
php artisan migrate
```

Inicia la API:

```bash
php artisan serve
```

Por defecto quedará disponible en `http://127.0.0.1:8000`. Si utilizas Laragon, puedes usar un dominio local como `http://edificios-api.test`.

## Conectar el frontend

En el frontend, crea `.env` a partir de `.env.example` y configura:

```env
VITE_API_URL=http://edificios-api.test/api
```

El frontend está en el repositorio [edificios-erp](https://github.com/melek-eyzaguirre-dev/edificios-erp).

## Comandos útiles

```bash
php artisan migrate               # ejecuta migraciones
php artisan migrate:fresh         # reinicia la base de datos local
php artisan route:list             # muestra las rutas disponibles
php artisan test                   # ejecuta pruebas
vendor/bin/pint                   # revisa formato PHP
```

No ejecutes `migrate:fresh` en una base de datos con información importante: el comando elimina y recrea las tablas.

## Organización del proyecto

```text
app/
  Http/Controllers/  # endpoints y reglas de entrada
  Models/            # entidades de dominio
database/
  migrations/        # estructura de la base de datos
  seeders/           # datos iniciales
routes/api.php       # contrato principal de la API
tests/               # pruebas automatizadas
```

## Diseño del proyecto

El sistema se está construyendo por módulos de negocio. La API mantiene separados los recursos de operación, seguridad, residentes y finanzas para facilitar el mantenimiento y la incorporación de nuevas capacidades.

## Roadmap

- Agregar documentación OpenAPI/Swagger.
- Completar pruebas de autorización por rol.
- Incorporar auditoría de cambios y bitácora de eventos.
- Añadir notificaciones y trabajos en segundo plano.
- Integrar pagos, reportes exportables y almacenamiento de documentos.
- Preparar despliegue con configuración segura para producción.

## Estado

Proyecto en desarrollo. El contrato de la API puede cambiar mientras se completan los módulos y sus pruebas.

## Licencia

Este proyecto se publica como material de aprendizaje y portafolio. La licencia comercial y las condiciones de uso se definirán antes de ofrecerlo a terceros.
