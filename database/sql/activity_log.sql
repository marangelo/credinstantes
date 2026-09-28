-- =====================================================================
-- Tabla de auditoria de actividad: activity_log
-- Registra los movimientos de los usuarios con rol de Operaciones (id_rol = 3).
-- Ejecutar manualmente en MySQL (el proyecto no usa migraciones para esto).
-- =====================================================================

CREATE TABLE IF NOT EXISTS `activity_log` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        INT UNSIGNED     NULL,
  `id_rol`         INT              NULL,
  `nombre_usuario` VARCHAR(150)     NULL,
  `metodo`         VARCHAR(10)      NULL,          -- GET, POST, PUT, DELETE
  `ruta`           VARCHAR(255)     NULL,          -- path de la peticion
  `route_name`     VARCHAR(150)     NULL,          -- nombre de la ruta laravel
  `seccion`        VARCHAR(100)     NULL,          -- seccion legible (ej: Clientes)
  `tipo`           VARCHAR(20)      NULL,          -- vista | impresion | exportacion | ajax
  `registro_id`    VARCHAR(100)     NULL,          -- id del registro afectado (si aplica)
  `ip`             VARCHAR(45)      NULL,
  `user_agent`     VARCHAR(255)     NULL,
  `created_at`     TIMESTAMP        NULL,          -- hora de entrada a la seccion
  PRIMARY KEY (`id`),
  KEY `idx_activity_user`    (`user_id`),
  KEY `idx_activity_created` (`created_at`),
  KEY `idx_activity_tipo`    (`tipo`),
  KEY `idx_activity_user_created` (`user_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
