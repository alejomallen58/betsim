-- =============================================================================
--  BetSim – Aplicación Web de Simulación de Apuestas Deportivas
--  SCHEMA: Creación completa de la base de datos
--
--  Proyecto TFG · DAW · Alejo Segura Cerdan · 2025-2026
--  Compatible con: MySQL 8.0 / MariaDB 10.6+
--
--  Uso:
--    mysql -u root -p < database/sql/schema.sql
--    mysql -u root -p apuestas_deportivas < database/sql/seed_data.sql
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Crear base de datos
-- -----------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `apuestas_deportivas`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `apuestas_deportivas`;

-- -----------------------------------------------------------------------------
-- Eliminar tablas existentes (orden inverso a las FK)
-- -----------------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `apuestas`;
DROP TABLE IF EXISTS `cuotas`;
DROP TABLE IF EXISTS `partidos`;
DROP TABLE IF EXISTS `equipos`;
DROP TABLE IF EXISTS `ligas`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- TABLAS DE FRAMEWORK (Laravel)
-- =============================================================================

-- -----------------------------------------------------------------------------
-- users
-- -----------------------------------------------------------------------------
CREATE TABLE `users` (
    `id`                BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(255)     NOT NULL,
    `email`             VARCHAR(255)     NOT NULL,
    `email_verified_at` TIMESTAMP        NULL DEFAULT NULL,
    `password`          VARCHAR(255)     NOT NULL,
    `saldo`             DECIMAL(10, 2)   NOT NULL DEFAULT 1000.00
                            COMMENT 'Saldo virtual del usuario en euros simulados',
    `is_admin`          TINYINT(1)       NOT NULL DEFAULT 0
                            COMMENT '1 = administrador, 0 = usuario estándar',
    `remember_token`    VARCHAR(100)     NULL DEFAULT NULL,
    `created_at`        TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuarios registrados en la plataforma';

-- -----------------------------------------------------------------------------
-- password_reset_tokens
-- -----------------------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- sessions
-- -----------------------------------------------------------------------------
CREATE TABLE `sessions` (
    `id`            VARCHAR(255)     NOT NULL,
    `user_id`       BIGINT UNSIGNED  NULL DEFAULT NULL,
    `ip_address`    VARCHAR(45)      NULL DEFAULT NULL,
    `user_agent`    TEXT             NULL DEFAULT NULL,
    `payload`       LONGTEXT         NOT NULL,
    `last_activity` INT              NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- cache / cache_locks
-- -----------------------------------------------------------------------------
CREATE TABLE `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT   NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- jobs / job_batches / failed_jobs
-- -----------------------------------------------------------------------------
CREATE TABLE `jobs` (
    `id`           BIGINT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255)        NOT NULL,
    `payload`      LONGTEXT            NOT NULL,
    `attempts`     TINYINT UNSIGNED    NOT NULL,
    `reserved_at`  INT UNSIGNED        NULL DEFAULT NULL,
    `available_at` INT UNSIGNED        NOT NULL,
    `created_at`   INT UNSIGNED        NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
    `id`             VARCHAR(255) NOT NULL,
    `name`           VARCHAR(255) NOT NULL,
    `total_jobs`     INT          NOT NULL,
    `pending_jobs`   INT          NOT NULL,
    `failed_jobs`    INT          NOT NULL,
    `failed_job_ids` LONGTEXT     NOT NULL,
    `options`        MEDIUMTEXT   NULL DEFAULT NULL,
    `cancelled_at`   INT          NULL DEFAULT NULL,
    `created_at`     INT          NOT NULL,
    `finished_at`    INT          NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid`       VARCHAR(255)    NOT NULL,
    `connection` TEXT            NOT NULL,
    `queue`      TEXT            NOT NULL,
    `payload`    LONGTEXT        NOT NULL,
    `exception`  LONGTEXT        NOT NULL,
    `failed_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- TABLAS DE NEGOCIO
-- =============================================================================

-- -----------------------------------------------------------------------------
-- ligas
-- -----------------------------------------------------------------------------
CREATE TABLE `ligas` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre`     VARCHAR(255)    NOT NULL COMMENT 'Nombre de la competición',
    `pais`       VARCHAR(255)    NOT NULL COMMENT 'País o región de la competición',
    `logo`       VARCHAR(255)    NULL DEFAULT NULL COMMENT 'URL o ruta del logo (opcional)',
    `activa`     TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = liga activa en la plataforma',
    `created_at` TIMESTAMP       NULL DEFAULT NULL,
    `updated_at` TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Ligas y competiciones deportivas disponibles';

-- -----------------------------------------------------------------------------
-- equipos
-- -----------------------------------------------------------------------------
CREATE TABLE `equipos` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `liga_id`    BIGINT UNSIGNED NOT NULL COMMENT 'Liga a la que pertenece el equipo',
    `nombre`     VARCHAR(255)    NOT NULL COMMENT 'Nombre oficial del equipo',
    `escudo`     VARCHAR(255)    NULL DEFAULT NULL COMMENT 'URL o ruta del escudo (opcional)',
    `ciudad`     VARCHAR(255)    NULL DEFAULT NULL COMMENT 'Ciudad sede del equipo',
    `created_at` TIMESTAMP       NULL DEFAULT NULL,
    `updated_at` TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `equipos_liga_id_foreign` (`liga_id`),
    CONSTRAINT `equipos_liga_id_foreign`
        FOREIGN KEY (`liga_id`) REFERENCES `ligas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Equipos deportivos clasificados por liga';

-- -----------------------------------------------------------------------------
-- partidos
-- -----------------------------------------------------------------------------
CREATE TABLE `partidos` (
    `id`                  BIGINT UNSIGNED                                          NOT NULL AUTO_INCREMENT,
    `liga_id`             BIGINT UNSIGNED                                          NOT NULL,
    `equipo_local_id`     BIGINT UNSIGNED                                          NOT NULL,
    `equipo_visitante_id` BIGINT UNSIGNED                                          NOT NULL,
    `fecha`               DATETIME                                                 NOT NULL COMMENT 'Fecha y hora de inicio del partido',
    `estado`              ENUM('pendiente','en_juego','finalizado','cancelado')     NOT NULL DEFAULT 'pendiente',
    `goles_local`         TINYINT UNSIGNED                                         NULL DEFAULT NULL,
    `goles_visitante`     TINYINT UNSIGNED                                         NULL DEFAULT NULL,
    `jornada`             VARCHAR(50)                                              NULL DEFAULT NULL COMMENT 'Etiqueta de jornada/matchday',
    `created_at`          TIMESTAMP                                                NULL DEFAULT NULL,
    `updated_at`          TIMESTAMP                                                NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `partidos_liga_id_foreign`             (`liga_id`),
    KEY `partidos_equipo_local_id_foreign`     (`equipo_local_id`),
    KEY `partidos_equipo_visitante_id_foreign` (`equipo_visitante_id`),
    KEY `partidos_estado_fecha_index`          (`estado`, `fecha`),
    CONSTRAINT `partidos_liga_id_foreign`
        FOREIGN KEY (`liga_id`)             REFERENCES `ligas`   (`id`) ON DELETE CASCADE,
    CONSTRAINT `partidos_equipo_local_id_foreign`
        FOREIGN KEY (`equipo_local_id`)     REFERENCES `equipos` (`id`) ON DELETE CASCADE,
    CONSTRAINT `partidos_equipo_visitante_id_foreign`
        FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Eventos deportivos: partidos de fútbol y baloncesto';

-- -----------------------------------------------------------------------------
-- cuotas
-- -----------------------------------------------------------------------------
CREATE TABLE `cuotas` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `partido_id`      BIGINT UNSIGNED NOT NULL,
    `cuota_local`     DECIMAL(5, 2)   NOT NULL COMMENT 'Cuota para victoria del equipo local (1)',
    `cuota_empate`    DECIMAL(5, 2)   NOT NULL COMMENT 'Cuota para empate (X)',
    `cuota_visitante` DECIMAL(5, 2)   NOT NULL COMMENT 'Cuota para victoria del equipo visitante (2)',
    `created_at`      TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `cuotas_partido_id_unique` (`partido_id`),
    CONSTRAINT `cuotas_partido_id_foreign`
        FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Cuotas 1/X/2 asociadas a cada partido';

-- -----------------------------------------------------------------------------
-- apuestas
-- -----------------------------------------------------------------------------
CREATE TABLE `apuestas` (
    `id`                 BIGINT UNSIGNED                              NOT NULL AUTO_INCREMENT,
    `user_id`            BIGINT UNSIGNED                              NOT NULL,
    `partido_id`         BIGINT UNSIGNED                              NOT NULL,
    `tipo`               ENUM('local','empate','visitante')           NOT NULL COMMENT 'Tipo de apuesta seleccionado por el usuario',
    `cantidad`           DECIMAL(10, 2)                               NOT NULL COMMENT 'Euros virtuales apostados',
    `cuota`              DECIMAL(5, 2)                                NOT NULL COMMENT 'Cuota vigente en el momento de la apuesta',
    `ganancia_potencial` DECIMAL(10, 2)                               NOT NULL COMMENT 'Ganancia potencial = cantidad × cuota',
    `estado`             ENUM('pendiente','ganada','perdida','cancelada') NOT NULL DEFAULT 'pendiente',
    `created_at`         TIMESTAMP                                    NULL DEFAULT NULL,
    `updated_at`         TIMESTAMP                                    NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `apuestas_user_partido_unique` (`user_id`, `partido_id`)
        COMMENT 'Un usuario solo puede apostar una vez por partido',
    KEY `apuestas_user_id_foreign`    (`user_id`),
    KEY `apuestas_partido_id_foreign` (`partido_id`),
    CONSTRAINT `apuestas_user_id_foreign`
        FOREIGN KEY (`user_id`)    REFERENCES `users`    (`id`) ON DELETE CASCADE,
    CONSTRAINT `apuestas_partido_id_foreign`
        FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Apuestas realizadas por los usuarios sobre partidos';

-- =============================================================================
-- FIN DEL SCHEMA
-- =============================================================================
