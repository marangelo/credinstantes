-- =====================================================================
-- CREDINSTANTES - MEJORAS DE RENDIMIENTO Y CORRECCION DE MORA
-- MySQL 5.7  |  Base: db_credinstantes
--
-- Contenido:
--   1) Indices (no modifica columnas ni datos)
--   2) Correccion de ObtenerValorDinamico (desempate por ultimo abono)
--   3) Regenerar tmp_fecha_pagos
--   4) Verificacion
--   5) Auditoria de datos (solo lectura)
--   6) Rollback (comentado)
--
-- Se puede ejecutar completo las veces que sea necesario: los indices
-- que ya existen se omiten.
-- Recomendado: respaldo previo y ejecutar fuera de horario.
--
-- Resultados medidos (02/11/2026):
--   Mora por zona ................ 7 min 18 s  ->  1.3 s
--   view_status_cliente .......... 28 s        ->  0.26 s
--   view_cliente_promotores ...... 5.2 s       ->  0.19 s
--   actualizar_tabla_abonos ...... ~40 min     ->  10 s
-- =====================================================================

USE `db_credinstantes`;

-- =====================================================================
-- 1) INDICES
-- =====================================================================
-- Procedimiento auxiliar: crea el indice solo si no existe.
DROP PROCEDURE IF EXISTS `tmp_add_index`;
DELIMITER $$
CREATE PROCEDURE `tmp_add_index` (IN p_tabla VARCHAR(64), IN p_indice VARCHAR(64), IN p_columnas VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = p_tabla AND INDEX_NAME = p_indice
    ) THEN
        SET @sql_idx = CONCAT('ALTER TABLE `', p_tabla, '` ADD INDEX `', p_indice, '` (', p_columnas, ')');
        PREPARE stmt FROM @sql_idx;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

-- tbl_pagosabonos: plan de pagos (view_fecha_pagos, ObtenerValorDinamico, RefAbonos)
CALL tmp_add_index('tbl_pagosabonos', 'idx_pa_credito_pago',   '`id_creditos`, `numero_pago`');
CALL tmp_add_index('tbl_pagosabonos', 'idx_pa_credito_pagado', '`id_creditos`, `Pagado`');

-- tbl_abonoscreditos: abonos (ObtenerValorDinamico, view_logs_pagos, reportes)
CALL tmp_add_index('tbl_abonoscreditos', 'idx_ab_cred_num_act_fecha', '`id_creditos`, `NumPago`, `activo`, `fecha_cuota`');
CALL tmp_add_index('tbl_abonoscreditos', 'idx_ab_fecha_secc1',        '`fecha_cuota_secc1`');
CALL tmp_add_index('tbl_abonoscreditos', 'idx_ab_registrado_fecha',   '`registrado_por`, `fecha_cuota_secc1`');

-- tbl_creditos: relacion cliente -> creditos y filtros de estado
CALL tmp_add_index('tbl_creditos', 'idx_cr_cliente_activo_estado', '`id_clientes`, `activo`, `estado_credito`');
CALL tmp_add_index('tbl_creditos', 'idx_cr_cliente_apertura',      '`id_clientes`, `fecha_apertura`');
CALL tmp_add_index('tbl_creditos', 'idx_cr_activo_estado',         '`activo`, `estado_credito`');

-- tbl_clientes: busqueda por zona, cedula y municipio
CALL tmp_add_index('tbl_clientes', 'idx_cl_zona_activo', '`id_zona`, `activo`');
CALL tmp_add_index('tbl_clientes', 'idx_cl_cedula',      '`cedula`');
CALL tmp_add_index('tbl_clientes', 'idx_cl_municipio',   '`id_municipio`');

-- tmp_fecha_pagos: snapshot que lee view_status_cliente (calculo de mora)
CALL tmp_add_index('tmp_fecha_pagos', 'idx_tfp_credito_fecha', '`ID_CREDITO`, `FECHA_PAGO`');
CALL tmp_add_index('tmp_fecha_pagos', 'idx_tfp_zona_fecha',    '`ID_ZONA`, `FECHA_PAGO`');

DROP PROCEDURE IF EXISTS `tmp_add_index`;

ANALYZE TABLE `tbl_pagosabonos`, `tbl_abonoscreditos`, `tbl_creditos`,
              `tbl_clientes`, `tmp_fecha_pagos`;


-- =====================================================================
-- 2) CORRECCION ObtenerValorDinamico
-- ---------------------------------------------------------------------
-- Si una cuota tiene 2+ abonos el mismo dia (parcial + completa), el
-- "ORDER BY fecha_cuota DESC LIMIT 1" original elegia cualquiera y podia
-- devolver el saldo viejo -> cuota pagada vista como pendiente (mora falsa).
-- Ahora desempata por id_abonoscreditos DESC (ultimo abono registrado).
-- Unico cambio: ", T2.id_abonoscreditos DESC" en los ORDER BY y
-- "LIMIT 1" en la lectura de tbl_pagosabonos.
-- =====================================================================
DELIMITER $$

DROP FUNCTION IF EXISTS `ObtenerValorDinamico`$$
CREATE DEFINER=`root`@`%` FUNCTION `ObtenerValorDinamico` (`idCredito` INT, `NumPago` INT, `campo` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET latin1
    READS SQL DATA
BEGIN
    DECLARE Valor_Return VARCHAR(255) DEFAULT 'N/D';

    IF campo = 'FechaPago' THEN

        SELECT T2.fecha_cuota INTO Valor_Return
        FROM tbl_abonoscreditos T2
        WHERE T2.id_creditos = idCredito
          AND T2.NumPago = NumPago AND T2.activo = 1
        ORDER BY T2.fecha_cuota DESC, T2.id_abonoscreditos DESC
        LIMIT 1;

        IF Valor_Return = 'N/D' THEN
            SELECT Pagado INTO Valor_Return FROM tbl_pagosabonos WHERE id_creditos = idCredito AND numero_pago = NumPago LIMIT 1;
            IF Valor_Return = 0 THEN
                SET Valor_Return = NULL;
            ELSE
                SELECT T2.fecha_cuota INTO Valor_Return FROM tbl_abonoscreditos T2 WHERE T2.id_creditos = idCredito AND T2.activo = 1 ORDER BY T2.id_abonoscreditos DESC LIMIT 1;
            END IF;
        END IF;

    ELSEIF campo = 'SaldoCuota' THEN

        SELECT T2.saldo_cuota INTO Valor_Return
        FROM tbl_abonoscreditos T2
        WHERE T2.id_creditos = idCredito
          AND T2.NumPago = NumPago
          AND T2.activo = 1
        ORDER BY T2.fecha_cuota DESC, T2.id_abonoscreditos DESC
        LIMIT 1;

        IF Valor_Return = 'N/D' THEN
            SELECT Pagado INTO Valor_Return FROM tbl_pagosabonos WHERE id_creditos = idCredito AND numero_pago = NumPago LIMIT 1;
            IF Valor_Return = 0 THEN
                SET Valor_Return = NULL;
            ELSE
                SET Valor_Return = 0.0000;
            END IF;
        END IF;

    END IF;

    RETURN Valor_Return;
END$$

DELIMITER ;


-- =====================================================================
-- 3) REGENERAR tmp_fecha_pagos (lo mismo que corre a las 22:00)
-- =====================================================================
CALL actualizar_tabla_abonos();


-- =====================================================================
-- 4) VERIFICACION
-- =====================================================================
-- 4.1 Deben aparecer 13 indices idx_*
SELECT TABLE_NAME, INDEX_NAME, GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS columnas
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME LIKE 'idx\_%'
  AND TABLE_NAME IN ('tbl_pagosabonos','tbl_abonoscreditos','tbl_creditos','tbl_clientes','tmp_fecha_pagos')
GROUP BY TABLE_NAME, INDEX_NAME
ORDER BY TABLE_NAME, INDEX_NAME;

-- 4.2 La funcion debe tener el desempate (corregida = 1)
SELECT ROUTINE_NAME, LAST_ALTERED,
       ROUTINE_DEFINITION LIKE '%fecha_cuota DESC, T2.id_abonoscreditos DESC%' AS corregida
FROM information_schema.ROUTINES
WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_NAME = 'ObtenerValorDinamico';

-- 4.3 tmp_fecha_pagos debe coincidir con la vista (diferencias = 0)
SELECT COUNT(*) AS filas, SUM(SALDO_PENDIENTE) AS saldo_pendiente FROM tmp_fecha_pagos;

SELECT COUNT(*) AS diferencias
FROM tmp_fecha_pagos t
JOIN view_fecha_pagos v ON v.ID_CREDITO = t.ID_CREDITO AND v.NUM_PAGO = t.NUM_PAGO
WHERE ROUND(t.SALDO_PENDIENTE, 2) <> ROUND(v.SALDO_PENDIENTE, 2);

-- 4.4 Resumen de cartera activa
SELECT MORA, VENCIDO, COUNT(*) AS creditos
FROM view_status_cliente
WHERE SALDO_CREDITO > 0 AND CLIENTE_ACTIVO = 1
GROUP BY MORA, VENCIDO;


-- =====================================================================
-- 5) AUDITORIA DE DATOS (solo lectura)
-- =====================================================================
-- 5.1 Clientes con municipio inexistente
--     (rompe Clientes::Clientes_promotor -> getMunicipio->getDepartamentos)
SELECT cl.id_clientes, cl.nombre, cl.apellidos, cl.id_municipio, cl.activo
FROM tbl_clientes cl
LEFT JOIN cat_municipio m ON m.id_municipio = cl.id_municipio
WHERE m.id_municipio IS NULL;

-- 5.2 Creditos vigentes sin ningun abono
--     (rompe Abono::getSaldoAbono opcion 1 -> $LstAbono->NumPago)
SELECT c.id_creditos, c.id_clientes, c.estado_credito, c.fecha_apertura
FROM tbl_creditos c
WHERE c.activo = 1 AND c.estado_credito IN (1,2,3)
  AND NOT EXISTS (SELECT 1 FROM tbl_abonoscreditos a WHERE a.id_creditos = c.id_creditos)
ORDER BY c.fecha_apertura;

-- 5.3 Abonos empatados (misma cuota y fecha con saldo distinto)
SELECT id_creditos, NumPago, fecha_cuota, COUNT(*) AS abonos,
       GROUP_CONCAT(id_abonoscreditos ORDER BY id_abonoscreditos) AS ids,
       GROUP_CONCAT(saldo_cuota ORDER BY id_abonoscreditos) AS saldos
FROM tbl_abonoscreditos
WHERE activo = 1
GROUP BY id_creditos, NumPago, fecha_cuota
HAVING COUNT(*) > 1 AND COUNT(DISTINCT saldo_cuota) > 1;

-- 5.4 Cedulas duplicadas entre clientes activos
SELECT cedula, COUNT(*) AS total, GROUP_CONCAT(id_clientes) AS ids
FROM tbl_clientes
WHERE activo = 1
GROUP BY cedula
HAVING COUNT(*) > 1;

-- 5.5 Pagos duplicados en el plan (debe dar 0 filas; si no, la funcion
--     falla con "Result consisted of more than one row")
SELECT id_creditos, numero_pago, COUNT(*) AS total
FROM tbl_pagosabonos
GROUP BY id_creditos, numero_pago
HAVING COUNT(*) > 1;


-- =====================================================================
-- 6) ROLLBACK (solo si se necesita revertir; descomentar)
-- =====================================================================
-- ALTER TABLE `tbl_pagosabonos`    DROP INDEX `idx_pa_credito_pago`, DROP INDEX `idx_pa_credito_pagado`;
-- ALTER TABLE `tbl_abonoscreditos` DROP INDEX `idx_ab_cred_num_act_fecha`, DROP INDEX `idx_ab_fecha_secc1`, DROP INDEX `idx_ab_registrado_fecha`;
-- ALTER TABLE `tbl_creditos`       DROP INDEX `idx_cr_cliente_activo_estado`, DROP INDEX `idx_cr_cliente_apertura`, DROP INDEX `idx_cr_activo_estado`;
-- ALTER TABLE `tbl_clientes`       DROP INDEX `idx_cl_zona_activo`, DROP INDEX `idx_cl_cedula`, DROP INDEX `idx_cl_municipio`;
-- ALTER TABLE `tmp_fecha_pagos`    DROP INDEX `idx_tfp_credito_fecha`, DROP INDEX `idx_tfp_zona_fecha`;
--
-- Para volver a la funcion original, quitar ", T2.id_abonoscreditos DESC"
-- de los dos ORDER BY del paso 2 y ejecutarlo de nuevo.


USE `db_credinstantes`;

-- =========================================================
-- 1) INDICES (no modifican columnas ni datos)
-- =========================================================
ALTER TABLE `tbl_pagosabonos`
  ADD INDEX `idx_pa_credito_pago`   (`id_creditos`, `numero_pago`),
  ADD INDEX `idx_pa_credito_pagado` (`id_creditos`, `Pagado`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `tbl_abonoscreditos`
  ADD INDEX `idx_ab_cred_num_act_fecha` (`id_creditos`, `NumPago`, `activo`, `fecha_cuota`),
  ADD INDEX `idx_ab_fecha_secc1`        (`fecha_cuota_secc1`),
  ADD INDEX `idx_ab_registrado_fecha`   (`registrado_por`, `fecha_cuota_secc1`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `tbl_creditos`
  ADD INDEX `idx_cr_cliente_activo_estado` (`id_clientes`, `activo`, `estado_credito`),
  ADD INDEX `idx_cr_cliente_apertura`      (`id_clientes`, `fecha_apertura`),
  ADD INDEX `idx_cr_activo_estado`         (`activo`, `estado_credito`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `tbl_clientes`
  ADD INDEX `idx_cl_zona_activo` (`id_zona`, `activo`),
  ADD INDEX `idx_cl_cedula`      (`cedula`),
  ADD INDEX `idx_cl_municipio`   (`id_municipio`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `tmp_fecha_pagos`
  ADD INDEX `idx_tfp_credito_fecha` (`ID_CREDITO`, `FECHA_PAGO`),
  ADD INDEX `idx_tfp_zona_fecha`    (`ID_ZONA`, `FECHA_PAGO`);

ANALYZE TABLE `tbl_pagosabonos`, `tbl_abonoscreditos`, `tbl_creditos`,
              `tbl_clientes`, `tmp_fecha_pagos`;
