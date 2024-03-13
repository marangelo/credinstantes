-- view_status_cliente
SELECT
    c.id_clientes as ID,
         ac.id_creditos AS ID_CREDITO,
    c.nombre as NOMBRE,
    c.apellidos as APELLIDOS,
    c.direccion_domicilio as DIRECCIN,
    c.cedula as CEDULA,
    c.telefono as TELEFONO,
    MAX(COALESCE(ac.fecha_cuota, cr.fecha_apertura)) AS ULTIMA_FECHA_ABONO,
        cr.saldo  AS SALDO_CREDITO,
        cr.id_diassemana as DIA_VISITA,
    DATEDIFF(NOW(), MAX(COALESCE(ac.fecha_cuota, cr.fecha_apertura))) AS DIAS_EN_MORA,
        DATEDIFF(cr.fecha_ultimo_abono, NOW()) AS DIAS_PARA_VENCER,
        CASE WHEN DATEDIFF(cr.fecha_ultimo_abono,NOW()) > 0 THEN 'N' ELSE 'S' END AS VENCIDO,
     CASE WHEN DATEDIFF(NOW(), MAX(COALESCE(ac.fecha_cuota, cr.fecha_apertura))) > 7 THEN 'S' ELSE 'N' END AS MORA,
       -- (SELECT CASE WHEN COUNT(FECHA_ABONO) = 1 THEN 'N' WHEN COUNT(FECHA_ABONO) = 0 THEN 'S' END  FROM view_fecha_pagos t0  WHERE t0.ID_cliente = c.id_clientes and t0.ID_CREDITO = cr.id_creditos  AND t0.FECHA_PAGO = CURDATE()  ORDER BY t0.NUM_PAGO) AS MORA,
        c.activo AS CLIENTE_ACTIVO,
         cr.activo as CREDITO_ACTIVO        
FROM
    Tbl_Clientes c
    LEFT JOIN Tbl_Creditos cr ON c.id_clientes = cr.id_clientes
    LEFT JOIN Tbl_AbonosCreditos ac ON cr.id_creditos = ac.id_creditos
    LEFT JOIN Tbl_PagosAbonos pa ON cr.id_creditos = pa.id_creditos 
    AND ac.id_abonoscreditos = pa.numero_pago 
WHERE
    ((COALESCE(ac.fecha_cuota, cr.fecha_apertura) < NOW()) AND ac.saldo_actual > 0 
    AND (pa.id_pagoabono IS NULL OR pa.FechaPago < COALESCE(ac.fecha_cuota, cr.fecha_apertura))) 
    OR ac.id_abonoscreditos IS NULL
    AND c.activo = 1 AND cr.activo = 1 
GROUP BY c.id_clientes,cr.fecha_ultimo_abono,cr.saldo,cr.id_diassemana,cr.activo,cr.id_creditos
ORDER BY c.id_clientes;


--view_fecha_pagos
SELECT
    t0.id_creditos AS ID_CREDITO,
    t1.id_clientes AS ID_CLIENTE,
	t2.id_zona as ID_ZONA,
    t0.numero_pago AS NUM_PAGO,
    t0.FechaPago AS FECHA_PAGO,   
        ObtenerValorDinamico(t0.id_creditos, t0.FechaPago, 'FechaPago') AS FECHA_ABONO,
        COALESCE(ObtenerValorDinamico(t0.id_creditos, t0.FechaPago, 'SaldoCuota'), t1.cuota) AS SALDO_PENDIENTE
FROM
    Tbl_PagosAbonos t0
    INNER JOIN Tbl_Creditos t1 ON t0.id_creditos = t1.id_creditos 
		INNER JOIN tbl_clientes T2 ON t2.id_clientes = t1.id_clientes
WHERE
    t1.activo = 1;


    SELECT
    t0.id_creditos AS ID_CREDITO,
    t1.id_clientes AS ID_CLIENTE,
		t2.id_zona as ID_ZONA,
    t0.numero_pago AS NUM_PAGO,
    t0.FechaPago AS FECHA_PAGO,   
    ObtenerValorDinamico(t0.id_creditos, t0.FechaPago, 'FechaPago') AS FECHA_ABONO,
    CASE
    WHEN t1.saldo > 0 THEN ObtenerValorDinamico(t0.id_creditos, t0.FechaPago, 'SaldoCuota') 
    ELSE t1.saldo
    END AS SALDO_PENDIENTE
FROM
    Tbl_PagosAbonos t0
    INNER JOIN Tbl_Creditos t1 ON t0.id_creditos = t1.id_creditos 
		INNER JOIN tbl_clientes T2 ON t2.id_clientes = t1.id_clientes
WHERE
    t1.activo = 1;


CREATE DEFINER=`root`@`localhost` FUNCTION `ObtenerValorDinamico`(idCredito INT, fechaPago DATE, campo VARCHAR(255)) RETURNS varchar(255) CHARSET latin1
BEGIN
    DECLARE valor VARCHAR(255);
    
    IF campo = 'FechaPago' THEN
        SELECT T2.fecha_cuota 
        INTO valor
        FROM Tbl_AbonosCreditos T2 
        WHERE T2.id_creditos = idCredito 
        AND T2.fecha_cuota BETWEEN fechaPago AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
        AND T2.activo = 1 
        ORDER BY T2.fecha_cuota DESC 
        LIMIT 1;
    ELSEIF campo = 'SaldoCuota' THEN
        
        SELECT T2.saldo_cuota
        INTO valor
        FROM Tbl_AbonosCreditos T2 
        WHERE T2.id_creditos = idCredito 
        AND T2.fecha_cuota BETWEEN fechaPago AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
        AND T2.activo = 1 
        ORDER BY T2.fecha_cuota DESC 
        LIMIT 1;
                
    ELSE
        SET valor = NULL;
    END IF;
    
    RETURN valor;
END

--view_logs_pagos
SELECT
    T0.id_abonoscreditos,
    T0.id_creditos,
    t1.id_clientes,
		t2.id_zona,
    (T0.abono_dia1 - T0.intereses_por_cuota) CAPITAL,
    T0.pago_intereses INTERES,
    T0.fecha_cuota_secc1 FECHA_ABONO,
    T0.activo 
FROM
    Tbl_AbonosCreditos T0
    INNER JOIN Tbl_Creditos t1 ON T0.id_creditos = t1.id_creditos 
		INNER JOIN tbl_clientes T2 ON t2.id_clientes = t1.id_clientes WHERE T0.activo = 1
UNION
SELECT
    t0.id_abonoscreditos,
    t0.id_creditos,
        t1.id_clientes,
				t2.id_zona,
    t0.abono_dia2 AS CAPITAL,
    0 AS INTERES,
    t0.fecha_cuota_secc2 AS FECHA_ABONO,
    t0.activo
FROM
    Tbl_AbonosCreditos t0
    INNER JOIN Tbl_Creditos t1 ON t0.id_creditos = t1.id_creditos
		INNER JOIN tbl_clientes T2 ON t2.id_clientes = t1.id_clientes
WHERE
    t0.abono_dia2 IS NOT NULL AND t0.activo = 1;


--  reporte de visitas

SELECT
	t0.id_clientes as Nu,
	t0.nombre AS NOMBRE,
	t0.apellidos as APELLIDOS,
	CASE
		WHEN DAYNAME(t1.fecha_apertura) = 'Monday' THEN 'LUNES'
		WHEN DAYNAME(t1.fecha_apertura) = 'Tuesday' THEN 'MARTES'
		WHEN DAYNAME(t1.fecha_apertura) = 'Wednesday' THEN 'MIERCOLES'
		WHEN DAYNAME(t1.fecha_apertura) = 'Thursday' THEN 'JUEVES'
		WHEN DAYNAME(t1.fecha_apertura) = 'Friday' THEN 'VIERNES'
		ELSE DAYNAME(t1.fecha_apertura)
	END as DIA_APERTURA,
	t2.dia_semana as DIA_VISITA
FROM
	tbl_clientes t0
	INNER JOIN tbl_creditos t1 ON t0.id_clientes = t1.id_clientes
	INNER JOIN cat_diassemana t2 on t2.id_diassemana = t1.id_diassemana
WHERE t0.id_clientes in (39, 54, 55, 64);


--CREDITOS_INACTIVOS
SELECT
    t0.id_clientes,
    GROUP_CONCAT(t1.estado_credito) AS GROUP_CONCAT_CREDITOS,
		GROUP_CONCAT(t1.id_creditos) AS GROUP_CONCAT_IDS
FROM
    tbl_clientes t0
INNER JOIN tbl_creditos t1 ON t0.id_clientes = t1.id_clientes
WHERE
    t0.activo = 1 AND t1.activo = 1
GROUP BY
    t0.id_clientes,
    t0.nombre,
    t0.apellidos,
    t0.activo
HAVING
    GROUP_CONCAT(t1.estado_credito) NOT LIKE '%1%'
    AND GROUP_CONCAT(t1.estado_credito) NOT LIKE '%2%'
    AND GROUP_CONCAT(t1.estado_credito) NOT LIKE '%3%';



ALTER TABLE tbl_pagosabonos ADD COLUMN Pagado INT NOT NULL DEFAULT 0;

ALTER TABLE tbl_abonoscreditos
ADD COLUMN NumPago INT(11) NOT NULL DEFAULT 0 AFTER fecha_cuota,
ADD COLUMN Descuento DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER NumPago;


