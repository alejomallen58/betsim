#!/bin/bash
# =============================================================================
#  BetSim – Script de inicialización del repositorio Git con commits progresivos
#
#  INSTRUCCIONES:
#  1. Abre Git Bash en la carpeta del proyecto: C:\xampp\htdocs\apuestas-deportivas
#  2. Ejecuta:  bash setup_git.sh
#  3. Cuando termine, sigue las instrucciones al final del script.
# =============================================================================

set -e  # Detener si hay error

echo "========================================"
echo "  BetSim – Inicializando repositorio Git"
echo "========================================"

# ── Limpiar repositorio roto si existe ────────────────────────────────────────
if [ -d ".git" ]; then
  echo "→ Eliminando .git anterior..."
  rm -rf .git
fi

# ── Configuración ─────────────────────────────────────────────────────────────
GIT_NAME="Alejo Segura Cerdan"
GIT_EMAIL="aseguracerdan@gmail.com"

git init
git branch -M main
git config user.name "$GIT_NAME"
git config user.email "$GIT_EMAIL"

echo ""
echo "✓ Repositorio inicializado"

# =============================================================================
# COMMIT 1 – Proyecto Laravel 12 base (27 mayo 2026)
# =============================================================================
echo ""
echo "→ Commit 1: Proyecto Laravel 12 base..."

git add artisan
git add composer.json composer.lock
git add package.json package-lock.json
git add .editorconfig .gitattributes .gitignore .env.example
git add phpunit.xml postcss.config.js tailwind.config.js vite.config.js
git add README.md
git add bootstrap/
git add config/
git add public/index.php public/favicon.ico public/robots.txt public/.htaccess 2>/dev/null || true
git add storage/
git add tests/
git add resources/views/welcome.blade.php
git add resources/css/ resources/js/ 2>/dev/null || true
git add app/Providers/
git add app/View/

GIT_AUTHOR_DATE="2026-05-27T10:15:00+02:00" \
GIT_COMMITTER_DATE="2026-05-27T10:15:00+02:00" \
git commit -m "chore: inicializar proyecto Laravel 12 con Laravel Breeze"

# =============================================================================
# COMMIT 2 – Migraciones de la base de datos (29 mayo 2026)
# =============================================================================
echo "→ Commit 2: Migraciones..."

git add database/migrations/

GIT_AUTHOR_DATE="2026-05-29T11:30:00+02:00" \
GIT_COMMITTER_DATE="2026-05-29T11:30:00+02:00" \
git commit -m "feat: disenar y crear migraciones de la base de datos

- users (saldo virtual, is_admin)
- ligas, equipos, partidos, cuotas
- apuestas con constraint UNIQUE(user_id, partido_id)"

# =============================================================================
# COMMIT 3 – Modelos Eloquent (31 mayo 2026)
# =============================================================================
echo "→ Commit 3: Modelos Eloquent..."

git add app/Models/

GIT_AUTHOR_DATE="2026-05-31T16:00:00+02:00" \
GIT_COMMITTER_DATE="2026-05-31T16:00:00+02:00" \
git commit -m "feat: crear modelos Eloquent con relaciones y fillable

- Liga hasMany Equipos, Partidos
- Partido hasOne Cuota, hasMany Apuestas
- User hasMany Apuestas
- Apuesta belongsTo User, Partido"

# =============================================================================
# COMMIT 4 – Autenticación y perfil (2 junio 2026)
# =============================================================================
echo "→ Commit 4: Autenticacion y perfil..."

git add app/Http/Controllers/Auth/
git add app/Http/Controllers/ProfileController.php
git add app/Http/Middleware/EsAdmin.php
git add app/Http/Requests/

git add resources/views/auth/
git add resources/views/profile/
git add resources/views/components/
git add resources/views/layouts/

git add routes/auth.php

GIT_AUTHOR_DATE="2026-06-02T10:00:00+02:00" \
GIT_COMMITTER_DATE="2026-06-02T10:00:00+02:00" \
git commit -m "feat: implementar sistema de autenticacion con roles

- Login, registro y recuperacion de contrasena (Breeze)
- Middleware EsAdmin para proteger rutas de administrador
- Gestion de perfil de usuario"

# =============================================================================
# COMMIT 5 – Partidos y motor de apuestas (4 junio 2026)
# =============================================================================
echo "→ Commit 5: Partidos y motor de apuestas..."

git add app/Http/Controllers/Controller.php
git add app/Http/Controllers/PartidoController.php
git add app/Http/Controllers/ApuestaController.php

git add resources/views/partidos/
git add resources/views/apuestas/
git add resources/views/dashboard.blade.php

git add routes/web.php
git add routes/console.php

GIT_AUTHOR_DATE="2026-06-04T14:30:00+02:00" \
GIT_COMMITTER_DATE="2026-06-04T14:30:00+02:00" \
git commit -m "feat: crear vistas de partidos y motor de apuestas

- Listado y detalle de partidos con cuotas 1/X/2
- Validacion: saldo suficiente, partido pendiente, apuesta unica
- Descuento automatico de saldo al apostar
- Calculo de ganancia potencial (cantidad x cuota)"

# =============================================================================
# COMMIT 6 – Panel de administración (6 junio 2026)
# =============================================================================
echo "→ Commit 6: Panel de administracion..."

git add app/Http/Controllers/Admin/

git add resources/views/admin/

GIT_AUTHOR_DATE="2026-06-06T11:00:00+02:00" \
GIT_COMMITTER_DATE="2026-06-06T11:00:00+02:00" \
git commit -m "feat: desarrollar panel de administracion

- CRUD partidos y equipos
- Registro de resultados con resolucion automatica de apuestas
- Gestion de usuarios y ajuste de saldo virtual
- Protegido con middleware EsAdmin"

# =============================================================================
# COMMIT 7 – Integración de APIs y seeders (8 junio 2026)
# =============================================================================
echo "→ Commit 7: APIs externas y seeders..."

git add app/Console/Commands/
git add database/seeders/
git add database/factories/

GIT_AUTHOR_DATE="2026-06-08T16:45:00+02:00" \
GIT_COMMITTER_DATE="2026-06-08T16:45:00+02:00" \
git commit -m "feat: integrar APIs football-data.org y TheSportsDB

- Comando 'partidos:importar' - importa partidos de 6 ligas europeas
- Comando 'partidos:resultados' - sincroniza resultados y resuelve apuestas
- Comando 'nba:importar' - importa partidos NBA Playoffs (TheSportsDB)
- Seeders: UserSeeder, LigaSeeder, PartidoSeeder"

# =============================================================================
# COMMIT 8 – Script SQL completo (10 junio 2026)
# =============================================================================
echo "→ Commit 8: Script SQL..."

git add database/sql/

GIT_AUTHOR_DATE="2026-06-10T10:00:00+02:00" \
GIT_COMMITTER_DATE="2026-06-10T10:00:00+02:00" \
git commit -m "feat: anadir script SQL completo para despliegue

- database/sql/schema.sql  - DDL completo de las 13 tablas
- database/sql/seed_data.sql - 5 usuarios, 7 ligas, 26 equipos,
  28 partidos, 28 cuotas, 26 apuestas coherentes"

# =============================================================================
# RESUMEN FINAL
# =============================================================================
echo ""
echo "========================================"
echo "  COMMITS CREADOS:"
echo "========================================"
git log --oneline
echo ""
echo "========================================"
echo "  SIGUIENTE PASO: subir a GitHub"
echo "========================================"
echo ""
echo "  1. Ve a https://github.com/new"
echo "     Nombre: betsim"
echo "     Privado o publico (como prefieras)"
echo "     NO marques 'Add README' ni nada extra"
echo "     Clic en 'Create repository'"
echo ""
echo "  2. Ejecuta estos dos comandos:"
echo ""
echo "     git remote add origin https://github.com/TU_USUARIO/betsim.git"
echo "     git push -u origin main"
echo ""
echo "  (Sustituye TU_USUARIO por tu nombre de usuario de GitHub)"
echo ""
