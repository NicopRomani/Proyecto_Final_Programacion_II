SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `vehiculos`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `usuarios`;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(50) DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `rol` TINYINT(1) NOT NULL DEFAULT 2,    /* 1 admin, 2 empleado*/
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `clientes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `dni` VARCHAR(20) UNIQUE NOT NULL,      
    `nombre` VARCHAR(100) NOT NULL,
    `apellido` VARCHAR(100) NOT NULL,
    `tel` VARCHAR(30) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `vehiculos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `marca` VARCHAR(20) NOT NULL,
    `modelo` VARCHAR(100) NOT NULL,
    `dominio` VARCHAR(20) DEFAULT NULL,       
    `anio` YEAR NOT NULL,
    `chasis` VARCHAR(50) NOT NULL,           
    `descripcion` TEXT,
    `vendedor_id` INT NOT NULL,              
    `cliente_id` INT NOT NULL,               
    
    PRIMARY KEY (`id`),
    
    KEY `vendedor_id_idx` (`vendedor_id`),
    KEY `cliente_id_idx` (`cliente_id`),
    
    CONSTRAINT `fk_vehiculos_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`),
    CONSTRAINT `fk_vehiculos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



