-- =====================================================================
-- Ruleta de Premios: el peso de cada premio acepta decimales (hasta 4),
-- para dar chances chicas a premios difíciles (ej. 0.025).
-- Ejecutar manualmente en MySQL, una sola vez por base (local y producción).
-- =====================================================================
ALTER TABLE tbl_ruleta_premios
    MODIFY COLUMN peso DECIMAL(10,4) NOT NULL DEFAULT 1.0000
    COMMENT 'Probabilidad relativa; 1 = igual que los demás. Hasta 4 decimales';
