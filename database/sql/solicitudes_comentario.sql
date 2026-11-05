-- =====================================================================
-- Solicitudes de crédito (nuevas y renovaciones): comentario para quien
-- aprueba. Se escribe en la sección "Comentarios" del formulario y se
-- muestra al abrir la solicitud para aprobación (Formulario/{id}).
-- Ejecutar manualmente en MySQL, una sola vez por base.
-- =====================================================================
ALTER TABLE tbl_requests_credit
    ADD COLUMN comentario VARCHAR(500) NULL DEFAULT NULL;
