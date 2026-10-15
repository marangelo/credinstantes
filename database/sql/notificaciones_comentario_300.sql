-- =====================================================================
-- Notificaciones de visita: el comentario pasa de 100 a 300 caracteres.
-- Ejecutar manualmente en MySQL, una sola vez por base.
-- =====================================================================
ALTER TABLE tbl_notificaciones_visita
    MODIFY COLUMN comentario VARCHAR(300) NULL DEFAULT NULL;
