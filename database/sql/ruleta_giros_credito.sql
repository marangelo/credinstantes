-- Ruleta de Premios: el giro guarda el crédito activo con el que se identificó al cliente
-- (búsqueda por cédula + número de crédito) y el código del voucher pasa a ser numérico.
-- Correr una sola vez en cada base (local y producción) antes de publicar el cambio.

ALTER TABLE tbl_ruleta_giros
  ADD COLUMN id_creditos INT NULL COMMENT 'tbl_creditos.id_creditos con el que se giró' AFTER id_clientes,
  MODIFY codigo VARCHAR(20) NOT NULL COMMENT 'Código del voucher: número de 8 dígitos (los primeros giros usaban RP-XXXXXX)';
