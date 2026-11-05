-- =====================================================================
-- Créditos: comentario heredado de la solicitud aprobada (nueva o
-- renovación). Se imprime en el PDF "Solicitud de crédito".
-- Ejecutar manualmente en MySQL, una sola vez por base, DESPUÉS de
-- solicitudes_comentario.sql.
-- =====================================================================
ALTER TABLE tbl_creditos
    ADD COLUMN comentario VARCHAR(500) NULL DEFAULT NULL;

-- Créditos ya aprobados cuya solicitud tenía comentario: se copia.
UPDATE tbl_creditos c
    INNER JOIN tbl_ref_request_credit r ON r.id_credit = c.id_creditos
    INNER JOIN tbl_requests_credit    q ON q.id_req    = r.id_resquest
SET c.comentario = q.comentario
WHERE c.comentario IS NULL
  AND q.comentario IS NOT NULL;
