# Edificios API

Backend de un sistema de administración de edificios/condominios (estilo ERP), construido como API REST con Laravel. Diseñado desde el inicio con arquitectura **multi-tenant**, pensado para escalar a múltiples administradoras, condominios y eventualmente otros tipos de cliente (empresas de seguridad, conserjería externa).

## Estado del proyecto

🚧 En desarrollo activo — MVP en construcción. Módulo de **reservas de espacios comunes** funcional de punta a punta (autenticación, validación de reglas de negocio, solapamiento de horarios).

## Stack

- **Backend:** Laravel 12, PHP 8.4
- **Base de datos:** MySQL
- **Autenticación:** Laravel Sanctum (tokens API)
- **Entorno local:** Laragon

## Arquitectura de datos

Modelo multi-tenant jerárquico:

```
Administradora
  └── Condominio
        ├── Unidad
        │     ├── Residentes (N:N vía unidad_user)
        │     ├── Visitas
        │     ├── Reservas
        │     └── Estacionamiento fijo
        ├── Espacio común
        │     └── Reservas
        ├── Estacionamiento (fijo o de visita)
        │     └── Ocupaciones
        └── Staff / Conserjería (N:N vía condominio_user)
```

Roles de usuario: `super_admin`, `admin_administradora`, `admin_condominio`, `conserje`, `residente`.

## Módulos

| Módulo | Estado |
|---|---|
| Autenticación (Sanctum) | ✅ Funcional |
| Administradoras / Condominios / Unidades | ✅ Funcional |
| Espacios comunes y reservas (con validación de solapamiento) | ✅ Funcional |
| Visitas (con QR de autorización) | 🚧 Modelo de datos listo, endpoints pendientes |
| Estacionamientos | 🚧 Modelo de datos listo, endpoints pendientes |
| Gastos comunes / pagos | 📋 Planeado |
| Control de asistencia (conserjes/guardias) | 📋 Planeado |
| Panel de analytics | 📋 Planeado |

## Instalación local (Laragon)

### Requisitos
- PHP 8.4+
- Composer
- MySQL (incluido en Laragon)

### Pasos

```bash
git clone https://github.com/melek-eyzaguirre-dev/edificios-api.git
cd edificios-api
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu `.env` con los datos de tu base de datos local:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edificios_db
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de datos y corre las migraciones:

```bash
php artisan migrate
```

Crea un usuario administrador de prueba:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Esto crea `admin@edificios.test` / `admin1234` (cámbialo en `database/seeders/AdminUserSeeder.php` antes de correrlo si quieres otras credenciales).

## Uso de la API

Todas las rutas (excepto `/api/login`) requieren un token Bearer obtenido en el login.

### Autenticación

```
POST /api/login
{ "email": "admin@edificios.test", "password": "admin1234" }
```

### Flujo típico de datos

```
POST /api/administradoras   { "nombre": "..." }
POST /api/condominios       { "administradora_id": 1, "nombre": "...", "direccion": "..." }
POST /api/unidades          { "condominio_id": 1, "numero": "101" }
POST /api/espacios-comunes  { "condominio_id": 1, "nombre": "Quincho", "duracion_maxima_horas": 4 }
POST /api/reservas          { "espacio_comun_id": 1, "unidad_id": 1, "inicio": "...", "fin": "..." }
```

El endpoint de reservas valida automáticamente: espacio activo, solapamiento de horario, duración máxima y anticipación mínima.

## Roadmap

- [ ] Endpoints de visitas (registro + validación de QR)
- [ ] Endpoints de estacionamientos
- [ ] Módulo de gastos comunes
- [ ] Frontend web (repo separado: `edificios-web`)
- [ ] App móvil para conserjería
- [ ] Panel de analytics para administradores

## Licencia

Privado — todos los derechos reservados.
