# BetSim – Aplicación Web de Simulación de Apuestas Deportivas

**Trabajo de Fin de Grado · Ciclo Formativo DAW · Alejo Segura Cerdan · 2025-2026**

Aplicación web que simula un sistema de apuestas deportivas sin dinero real.  
Los usuarios gestionan un saldo virtual y apuestan sobre partidos de fútbol y baloncesto con datos en tiempo real procedentes de APIs externas.

## Stack tecnológico

- **Backend**: PHP 8.3 · Laravel 12 · Eloquent ORM
- **Frontend**: Blade Templates · CSS inline
- **Base de datos**: MySQL 8.0
- **Autenticación**: Laravel Breeze (login / registro / recuperar contraseña)
- **APIs externas**: football-data.org (La Liga, Premier League, Serie A, Bundesliga, Champions League, Mundial 2026) · TheSportsDB (NBA Playoffs)

## Funcionalidades principales

- Registro e inicio de sesión con roles (usuario / administrador)
- Listado de partidos con cuotas 1/X/2 en tiempo real
- Sistema de apuestas con validación de saldo y unicidad por partido
- Resolución automática de apuestas al registrar resultados
- Panel de administración: CRUD partidos, equipos, usuarios y saldos
- Comandos Artisan para importar datos desde APIs

## Instalación

```bash
cp .env.example .env
# Editar .env con credenciales de BD y API key de football-data.org
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

O importar directamente los scripts SQL:

```bash
mysql -u root -p < database/sql/schema.sql
mysql -u root -p apuestas_deportivas < database/sql/seed_data.sql
```

## Credenciales de prueba (seed)

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@apuestas.com | password | Administrador |
| demo@apuestas.com | password | Usuario |

## Estructura del repositorio

```
database/sql/        ← Scripts SQL (schema + datos de prueba)
app/Models/          ← Modelos Eloquent
app/Http/Controllers/← Controladores MVC
app/Console/Commands/← Comandos Artisan (importar APIs)
resources/views/     ← Plantillas Blade
```
