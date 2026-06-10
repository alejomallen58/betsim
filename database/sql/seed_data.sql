-- =============================================================================
--  BetSim – Aplicación Web de Simulación de Apuestas Deportivas
--  SEED DATA: Datos de prueba para demostración y desarrollo
--
--  Proyecto TFG · DAW · Alejo Segura Cerdan · 2025-2026
--
--  IMPORTANTE: Ejecutar DESPUÉS de schema.sql
--    mysql -u root -p apuestas_deportivas < database/sql/seed_data.sql
--
--  Credenciales de los usuarios de prueba:
--    Admin       → admin@apuestas.com     / password
--    Demo        → demo@apuestas.com      / password
--    Carlos      → carlos@apuestas.com    / password
--    Laura       → laura@apuestas.com     / password
--    Miguel      → miguel@apuestas.com    / password
-- =============================================================================

USE `apuestas_deportivas`;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `apuestas`;
TRUNCATE TABLE `cuotas`;
TRUNCATE TABLE `partidos`;
TRUNCATE TABLE `equipos`;
TRUNCATE TABLE `ligas`;
TRUNCATE TABLE `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- USUARIOS
-- Contraseña 'password' hasheada con bcrypt (cost 10), compatible con Laravel.
-- =============================================================================
INSERT INTO `users`
    (`id`, `name`, `email`, `email_verified_at`, `password`, `saldo`, `is_admin`, `created_at`, `updated_at`)
VALUES
-- Administrador (saldo 0, no apuesta)
(1, 'Administrador',
 'admin@apuestas.com',
 '2026-01-01 00:00:00',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 0.00, 1, NOW(), NOW()),

-- Usuario demo con saldo alto (para probar apuestas)
(2, 'Usuario Demo',
 'demo@apuestas.com',
 '2026-01-01 00:00:00',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 1000.00, 0, NOW(), NOW()),

-- Usuarios adicionales con distintos saldos para mostrar variedad
(3, 'Carlos Rodríguez',
 'carlos@apuestas.com',
 '2026-02-15 09:30:00',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 1340.50, 0, '2026-02-15 09:30:00', NOW()),

(4, 'Laura García',
 'laura@apuestas.com',
 '2026-03-10 14:20:00',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 420.00, 0, '2026-03-10 14:20:00', NOW()),

(5, 'Miguel Torres',
 'miguel@apuestas.com',
 '2026-04-05 11:00:00',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 750.25, 0, '2026-04-05 11:00:00', NOW());

-- =============================================================================
-- LIGAS
-- =============================================================================
INSERT INTO `ligas`
    (`id`, `nombre`, `pais`, `logo`, `activa`, `created_at`, `updated_at`)
VALUES
(1, 'La Liga',         'España',      NULL, 1, NOW(), NOW()),
(2, 'Premier League',  'Inglaterra',  NULL, 1, NOW(), NOW()),
(3, 'Serie A',         'Italia',      NULL, 1, NOW(), NOW()),
(4, 'Bundesliga',      'Alemania',    NULL, 1, NOW(), NOW()),
(5, 'Champions League','Europa',      NULL, 1, NOW(), NOW()),
(6, 'NBA Playoffs',    'Estados Unidos', NULL, 1, NOW(), NOW()),
(7, 'Mundial 2026',    'Internacional', NULL, 1, NOW(), NOW());

-- =============================================================================
-- EQUIPOS
-- =============================================================================

-- La Liga (liga_id = 1)
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
( 1, 1, 'Real Madrid',        'Madrid',        NOW(), NOW()),
( 2, 1, 'FC Barcelona',       'Barcelona',     NOW(), NOW()),
( 3, 1, 'Atlético de Madrid', 'Madrid',        NOW(), NOW()),
( 4, 1, 'Sevilla FC',         'Sevilla',       NOW(), NOW()),
( 5, 1, 'Real Betis',         'Sevilla',       NOW(), NOW()),
( 6, 1, 'Valencia CF',        'Valencia',      NOW(), NOW()),
( 7, 1, 'Athletic Club',      'Bilbao',        NOW(), NOW()),
( 8, 1, 'Real Sociedad',      'San Sebastián', NOW(), NOW());

-- Premier League (liga_id = 2)
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
( 9, 2, 'Manchester City',   'Manchester', NOW(), NOW()),
(10, 2, 'Arsenal',           'Londres',    NOW(), NOW()),
(11, 2, 'Liverpool',         'Liverpool',  NOW(), NOW()),
(12, 2, 'Chelsea',           'Londres',    NOW(), NOW()),
(13, 2, 'Manchester United', 'Manchester', NOW(), NOW()),
(14, 2, 'Tottenham',         'Londres',    NOW(), NOW());

-- Serie A (liga_id = 3)
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
(15, 3, 'Juventus',    'Turín',   NOW(), NOW()),
(16, 3, 'Inter Milan', 'Milán',   NOW(), NOW()),
(17, 3, 'AC Milan',    'Milán',   NOW(), NOW()),
(18, 3, 'AS Roma',     'Roma',    NOW(), NOW()),
(19, 3, 'Napoli',      'Nápoles', NOW(), NOW()),
(20, 3, 'Lazio',       'Roma',    NOW(), NOW());

-- Bundesliga (liga_id = 4)
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
(21, 4, 'Bayern München',  'Múnich',     NOW(), NOW()),
(22, 4, 'Borussia Dortmund','Dortmund',  NOW(), NOW()),
(23, 4, 'Bayer Leverkusen','Leverkusen', NOW(), NOW()),
(24, 4, 'RB Leipzig',      'Leipzig',    NOW(), NOW());

-- Champions League (liga_id = 5) – equipos multinacionales
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
(25, 5, 'Real Madrid CF',    'Madrid',    NOW(), NOW()),
(26, 5, 'Manchester City FC','Manchester',NOW(), NOW()),
(27, 5, 'Bayern München',    'Múnich',    NOW(), NOW()),
(28, 5, 'Inter de Milán',    'Milán',     NOW(), NOW());

-- NBA Playoffs (liga_id = 6)
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
(29, 6, 'Boston Celtics',       'Boston',      NOW(), NOW()),
(30, 6, 'Oklahoma City Thunder','Oklahoma City',NOW(), NOW()),
(31, 6, 'Cleveland Cavaliers',  'Cleveland',   NOW(), NOW()),
(32, 6, 'Indiana Pacers',       'Indianapolis',NOW(), NOW());

-- Mundial 2026 (liga_id = 7)
INSERT INTO `equipos` (`id`, `liga_id`, `nombre`, `ciudad`, `created_at`, `updated_at`) VALUES
(33, 7, 'España',    'Madrid',   NOW(), NOW()),
(34, 7, 'Brasil',    'Brasília', NOW(), NOW()),
(35, 7, 'Francia',   'París',    NOW(), NOW()),
(36, 7, 'Argentina', 'Buenos Aires', NOW(), NOW()),
(37, 7, 'Alemania',  'Berlín',   NOW(), NOW()),
(38, 7, 'Portugal',  'Lisboa',   NOW(), NOW());

-- =============================================================================
-- PARTIDOS
-- Mezcla de: pendientes (próximos), finalizados (con resultado) y cancelados
-- Las fechas son relativas a junio de 2026
-- =============================================================================

-- ─── PARTIDOS FINALIZADOS (con resultado) ────────────────────────────────────
INSERT INTO `partidos`
    (`id`, `liga_id`, `equipo_local_id`, `equipo_visitante_id`,
     `fecha`, `estado`, `goles_local`, `goles_visitante`, `jornada`,
     `created_at`, `updated_at`)
VALUES
-- La Liga – Jornada 34 (finalizada)
(1,  1,  1,  2,  '2026-05-25 21:00:00', 'finalizado', 2, 1, 'Jornada 34', NOW(), NOW()),  -- Real Madrid 2-1 Barcelona
(2,  1,  3,  4,  '2026-05-26 19:00:00', 'finalizado', 1, 1, 'Jornada 34', NOW(), NOW()),  -- Atlético 1-1 Sevilla
(3,  1,  5,  6,  '2026-05-26 21:00:00', 'finalizado', 3, 0, 'Jornada 34', NOW(), NOW()),  -- Betis 3-0 Valencia
(4,  1,  7,  8,  '2026-05-27 18:00:00', 'finalizado', 0, 2, 'Jornada 34', NOW(), NOW()),  -- Athletic 0-2 Real Sociedad

-- Premier League – Matchday 35 (finalizada)
(5,  2,  9, 10,  '2026-05-24 17:00:00', 'finalizado', 3, 1, 'Matchday 35', NOW(), NOW()), -- Man City 3-1 Arsenal
(6,  2, 11, 12,  '2026-05-24 19:30:00', 'finalizado', 2, 2, 'Matchday 35', NOW(), NOW()), -- Liverpool 2-2 Chelsea

-- Serie A – Giornata 34 (finalizada)
(7,  3, 15, 16,  '2026-05-23 20:45:00', 'finalizado', 1, 0, 'Giornata 34', NOW(), NOW()), -- Juventus 1-0 Inter
(8,  3, 17, 19,  '2026-05-23 18:00:00', 'finalizado', 0, 1, 'Giornata 34', NOW(), NOW()), -- AC Milan 0-1 Napoli

-- Champions League – Semifinal (finalizada)
(9,  5, 25, 26,  '2026-05-06 21:00:00', 'finalizado', 1, 0, 'Semifinal ida', NOW(), NOW()),   -- Real Madrid 1-0 Man City
(10, 5, 27, 28,  '2026-05-07 21:00:00', 'finalizado', 2, 2, 'Semifinal ida', NOW(), NOW()),   -- Bayern 2-2 Inter

-- NBA Playoffs – Conference Final (finalizado)
(11, 6, 29, 30,  '2026-05-28 02:30:00', 'finalizado', 108, 99, 'Conf. Final G5', NOW(), NOW()), -- Celtics 108-99 Thunder

-- ─── PARTIDOS PENDIENTES (próximos) ──────────────────────────────────────────
-- La Liga – Jornada 35 (próximos)
(12, 1,  1,  3,  '2026-06-14 21:00:00', 'pendiente', NULL, NULL, 'Jornada 35', NOW(), NOW()),  -- Real Madrid vs Atlético
(13, 1,  2,  4,  '2026-06-15 19:00:00', 'pendiente', NULL, NULL, 'Jornada 35', NOW(), NOW()),  -- Barcelona vs Sevilla
(14, 1,  5,  7,  '2026-06-15 18:00:00', 'pendiente', NULL, NULL, 'Jornada 35', NOW(), NOW()),  -- Betis vs Athletic
(15, 1,  6,  8,  '2026-06-15 18:00:00', 'pendiente', NULL, NULL, 'Jornada 35', NOW(), NOW()),  -- Valencia vs Real Sociedad

-- Premier League – Matchday 36
(16, 2,  9, 11,  '2026-06-13 17:30:00', 'pendiente', NULL, NULL, 'Matchday 36', NOW(), NOW()), -- Man City vs Liverpool
(17, 2, 10, 12,  '2026-06-13 20:00:00', 'pendiente', NULL, NULL, 'Matchday 36', NOW(), NOW()), -- Arsenal vs Chelsea
(18, 2, 13, 14,  '2026-06-14 15:00:00', 'pendiente', NULL, NULL, 'Matchday 36', NOW(), NOW()), -- Man United vs Tottenham

-- Serie A – Giornata 35
(19, 3, 16, 17,  '2026-06-16 20:45:00', 'pendiente', NULL, NULL, 'Giornata 35', NOW(), NOW()), -- Inter vs AC Milan
(20, 3, 15, 19,  '2026-06-16 18:00:00', 'pendiente', NULL, NULL, 'Giornata 35', NOW(), NOW()), -- Juventus vs Napoli

-- Bundesliga
(21, 4, 21, 22,  '2026-06-13 18:30:00', 'pendiente', NULL, NULL, 'Spieltag 33', NOW(), NOW()), -- Bayern vs Dortmund
(22, 4, 23, 24,  '2026-06-14 15:30:00', 'pendiente', NULL, NULL, 'Spieltag 33', NOW(), NOW()), -- Leverkusen vs Leipzig

-- Champions League – Final
(23, 5, 25, 28,  '2026-05-31 21:00:00', 'pendiente', NULL, NULL, 'Final',       NOW(), NOW()), -- Real Madrid vs Inter

-- NBA Playoffs – NBA Finals
(24, 6, 29, 31,  '2026-06-12 02:00:00', 'pendiente', NULL, NULL, 'Finals G1',   NOW(), NOW()), -- Celtics vs Cavaliers
(25, 6, 29, 31,  '2026-06-15 02:00:00', 'pendiente', NULL, NULL, 'Finals G2',   NOW(), NOW()), -- Celtics vs Cavaliers

-- Mundial 2026 – Fase de grupos
(26, 7, 33, 34,  '2026-06-17 21:00:00', 'pendiente', NULL, NULL, 'Grupo A – J1', NOW(), NOW()), -- España vs Brasil
(27, 7, 35, 36,  '2026-06-17 18:00:00', 'pendiente', NULL, NULL, 'Grupo B – J1', NOW(), NOW()), -- Francia vs Argentina
(28, 7, 37, 38,  '2026-06-18 15:00:00', 'pendiente', NULL, NULL, 'Grupo C – J1', NOW(), NOW()); -- Alemania vs Portugal

-- =============================================================================
-- CUOTAS (una por partido)
-- =============================================================================
INSERT INTO `cuotas`
    (`id`, `partido_id`, `cuota_local`, `cuota_empate`, `cuota_visitante`,
     `created_at`, `updated_at`)
VALUES
-- Partidos finalizados
( 1,  1, 1.80, 3.60, 4.50, NOW(), NOW()),  -- Real Madrid vs Barcelona
( 2,  2, 2.10, 3.20, 3.50, NOW(), NOW()),  -- Atlético vs Sevilla
( 3,  3, 2.40, 3.10, 3.00, NOW(), NOW()),  -- Betis vs Valencia
( 4,  4, 2.80, 3.20, 2.60, NOW(), NOW()),  -- Athletic vs Real Sociedad
( 5,  5, 1.70, 3.80, 5.00, NOW(), NOW()),  -- Man City vs Arsenal
( 6,  6, 2.00, 3.30, 3.80, NOW(), NOW()),  -- Liverpool vs Chelsea
( 7,  7, 2.20, 3.30, 3.20, NOW(), NOW()),  -- Juventus vs Inter
( 8,  8, 2.00, 3.40, 3.70, NOW(), NOW()),  -- AC Milan vs Napoli
( 9,  9, 2.10, 3.50, 3.40, NOW(), NOW()),  -- Real Madrid vs Man City (UCL)
(10, 10, 2.40, 3.20, 2.90, NOW(), NOW()),  -- Bayern vs Inter (UCL)
(11, 11, 1.65, 99.00, 2.20, NOW(), NOW()), -- Celtics vs Thunder (NBA, sin empate)

-- Partidos pendientes
(12, 12, 1.90, 3.50, 4.00, NOW(), NOW()),  -- Real Madrid vs Atlético
(13, 13, 1.75, 3.80, 4.80, NOW(), NOW()),  -- Barcelona vs Sevilla
(14, 14, 2.50, 3.10, 2.80, NOW(), NOW()),  -- Betis vs Athletic
(15, 15, 2.30, 3.20, 3.10, NOW(), NOW()),  -- Valencia vs Real Sociedad
(16, 16, 1.85, 3.60, 4.20, NOW(), NOW()),  -- Man City vs Liverpool
(17, 17, 1.95, 3.40, 4.00, NOW(), NOW()),  -- Arsenal vs Chelsea
(18, 18, 2.60, 3.20, 2.70, NOW(), NOW()),  -- Man United vs Tottenham
(19, 19, 1.90, 3.50, 3.90, NOW(), NOW()),  -- Inter vs AC Milan
(20, 20, 2.10, 3.30, 3.50, NOW(), NOW()),  -- Juventus vs Napoli
(21, 21, 1.60, 4.00, 5.50, NOW(), NOW()),  -- Bayern vs Dortmund
(22, 22, 1.80, 3.60, 4.20, NOW(), NOW()),  -- Leverkusen vs Leipzig
(23, 23, 2.00, 3.40, 3.60, NOW(), NOW()),  -- Real Madrid vs Inter (UCL Final)
(24, 24, 1.80, 99.00, 2.00, NOW(), NOW()), -- Celtics vs Cavaliers G1
(25, 25, 1.75, 99.00, 2.10, NOW(), NOW()), -- Celtics vs Cavaliers G2
(26, 26, 2.20, 3.20, 3.30, NOW(), NOW()),  -- España vs Brasil
(27, 27, 1.90, 3.50, 4.10, NOW(), NOW()),  -- Francia vs Argentina
(28, 28, 2.10, 3.30, 3.40, NOW(), NOW());  -- Alemania vs Portugal

-- =============================================================================
-- APUESTAS DE PRUEBA
-- Cubren los tres estados: pendiente, ganada, perdida
-- =============================================================================
INSERT INTO `apuestas`
    (`id`, `user_id`, `partido_id`, `tipo`, `cantidad`, `cuota`,
     `ganancia_potencial`, `estado`, `created_at`, `updated_at`)
VALUES
-- ─── Usuario Demo (id=2) ────────────────────────────────────────────────────
-- Apuesta ganada: apostó local en Real Madrid 2-1 Barcelona → GANADA
(1, 2,  1, 'local',     50.00, 1.80,  90.00, 'ganada',   '2026-05-20 10:00:00', NOW()),
-- Apuesta perdida: apostó empate en Atlético 1-1 Sevilla (era empate → debería ganar)
-- Nota: corrijo, apostó visitante (Sevilla) pero fue empate → PERDIDA
(2, 2,  2, 'visitante', 30.00, 3.50, 105.00, 'perdida',  '2026-05-21 09:00:00', NOW()),
-- Apuesta ganada: apostó local en Man City 3-1 Arsenal → GANADA
(3, 2,  5, 'local',     80.00, 1.70, 136.00, 'ganada',   '2026-05-22 11:00:00', NOW()),
-- Apuesta pendiente en partido próximo
(4, 2, 12, 'local',    100.00, 1.90, 190.00, 'pendiente','2026-06-09 20:00:00', NOW()),
-- Apuesta pendiente en Mundial
(5, 2, 26, 'local',     50.00, 2.20, 110.00, 'pendiente','2026-06-10 08:00:00', NOW()),

-- ─── Carlos Rodríguez (id=3) ────────────────────────────────────────────────
-- Varias apuestas para mostrar historial rico
(6,  3,  1, 'local',      100.00, 1.80, 180.00, 'ganada',  '2026-05-20 09:30:00', NOW()),
(7,  3,  3, 'local',      150.00, 2.40, 360.00, 'ganada',  '2026-05-21 10:00:00', NOW()),
(8,  3,  6, 'empate',      80.00, 3.30, 264.00, 'ganada',  '2026-05-22 10:00:00', NOW()),
(9,  3,  7, 'local',      200.00, 2.20, 440.00, 'ganada',  '2026-05-23 10:00:00', NOW()),
(10, 3,  8, 'visitante',   50.00, 3.70, 185.00, 'ganada',  '2026-05-23 10:00:00', NOW()),
(11, 3,  2, 'local',      100.00, 2.10, 210.00, 'perdida', '2026-05-21 09:00:00', NOW()),
-- Pendientes
(12, 3, 16, 'local',      200.00, 1.85, 370.00, 'pendiente','2026-06-09 20:00:00', NOW()),
(13, 3, 21, 'local',      100.00, 1.60, 160.00, 'pendiente','2026-06-09 21:00:00', NOW()),

-- ─── Laura García (id=4) ────────────────────────────────────────────────────
(14, 4,  5, 'local',      200.00, 1.70, 340.00, 'ganada',  '2026-05-22 12:00:00', NOW()),
(15, 4,  9, 'local',      300.00, 2.10, 630.00, 'ganada',  '2026-05-02 10:00:00', NOW()),
(16, 4,  1, 'visitante',  200.00, 4.50, 900.00, 'perdida', '2026-05-20 10:00:00', NOW()),
(17, 4,  4, 'local',      150.00, 2.80, 420.00, 'perdida', '2026-05-22 09:00:00', NOW()),
(18, 4, 11, 'local',      100.00, 1.65, 165.00, 'ganada',  '2026-05-27 10:00:00', NOW()),
-- Pendiente
(19, 4, 27, 'local',      100.00, 1.90, 190.00, 'pendiente','2026-06-10 07:00:00', NOW()),

-- ─── Miguel Torres (id=5) ───────────────────────────────────────────────────
(20, 5,  1, 'empate',      50.00, 3.60, 180.00, 'perdida', '2026-05-20 10:00:00', NOW()),
(21, 5,  7, 'visitante',   75.00, 3.20, 240.00, 'perdida', '2026-05-23 10:00:00', NOW()),
(22, 5, 10, 'empate',     100.00, 3.20, 320.00, 'ganada',  '2026-05-03 10:00:00', NOW()),
(23, 5, 11, 'local',      200.00, 1.65, 330.00, 'ganada',  '2026-05-27 10:00:00', NOW()),
-- Pendientes
(24, 5, 23, 'local',      200.00, 2.00, 400.00, 'pendiente','2026-06-08 10:00:00', NOW()),
(25, 5, 24, 'local',      100.00, 1.80, 180.00, 'pendiente','2026-06-10 09:00:00', NOW()),
(26, 5, 28, 'visitante',   75.00, 3.40, 255.00, 'pendiente','2026-06-10 10:00:00', NOW());

-- =============================================================================
-- VERIFICACIÓN RÁPIDA
-- =============================================================================
SELECT 'users'    AS tabla, COUNT(*) AS total FROM users
UNION ALL
SELECT 'ligas',    COUNT(*) FROM ligas
UNION ALL
SELECT 'equipos',  COUNT(*) FROM equipos
UNION ALL
SELECT 'partidos', COUNT(*) FROM partidos
UNION ALL
SELECT 'cuotas',   COUNT(*) FROM cuotas
UNION ALL
SELECT 'apuestas', COUNT(*) FROM apuestas;

-- =============================================================================
-- FIN DEL SEED DATA
-- =============================================================================
