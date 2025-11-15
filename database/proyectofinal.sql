-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2025 a las 02:58:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectofinal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tel` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `dni`, `nombre`, `apellido`, `tel`) VALUES
(3, '12366778', 'MARCOS', 'PEREZ', ''),
(7, '33445566', 'Miguel', 'Ramirez', '02299886783'),
(8, '12366788', 'Jose', 'Gonzalez', '01134569900'),
(9, '30712567', 'Miguel', 'Ramirez', '01134569900'),
(10, '12223445', 'PEDRO', 'LOPEZ', '12345676543'),
(12, '12334455', 'MARCOS', 'PEREZ', '01134569900'),
(13, '1234567654', 'juan', 'pe', '12345654321|');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` tinyint(1) NOT NULL DEFAULT 2,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`, `estado`) VALUES
(3, 'nico', '$2y$10$82t/g4wyv7w3N9efnUEe9uGOW4xknsssruzKHTrlwquXhzDcTbEey', 1, 1),
(22, 'admin@email.com', '$2y$10$Clo1NC8yuFGu8scaPoxGQ.9l8pKRIGe7kKwBfnImM2YraWCrvNRXu', 1, 1),
(23, 'usuario@email.com', '$2y$10$G4XYNidgIaB/griuaoIQqe96zc2FRi9V68oowxO/zvioz2oEsbfbO', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `marca` varchar(20) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `dominio` varchar(20) DEFAULT NULL,
  `anio` year(4) NOT NULL,
  `chasis` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vendedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `marca`, `modelo`, `dominio`, `anio`, `chasis`, `descripcion`, `cliente_id`, `vendedor_id`) VALUES
(5, 'Ford', 'Focus Titanium', 'XYZ987', '2016', 'CHS002', 'Color blanco, unico dueño.\r\n', NULL, 3),
(14, 'toyota', 'Hilux', 'AD248FH', '2020', 'l629264', 'unidad en buen estado, proximo services a los 80.000 Km.', 7, 23),
(15, 'FORD', 'ECOSPORT', 'AD828QE', '2020', '123ddf2', 'HOLA', 3, 23),
(16, 'Toyota', 'Hilux', 'AF955DI', '2020', 'a000758', '', 8, 23),
(17, 'Audi', 'A3', 'MKD968', '2012', '5024118', '', 9, 23),
(18, 'Volkswagen', 'AMAROK', 'PQT790', '2015', 'WAUZ4A3', '', 10, 22),
(21, 'FORD', 'RANGER', 'AG025MN', '2025', 'PFB9995', '', 3, 22),
(22, 'FORD', 'RANGER', 'AH222LK', '2025', 'PLM9876', '', 3, 22),
(24, 'FORD', 'RANGER', 'AH222LK', '2025', '1234iop', '', 8, 22),
(31, 'Volkswagen', 'AMAROK', 'QQ999OO', '2020', 'MMNNBB5', '', 12, 22),
(32, 'FORD', 'RANGER', 'AG025MN', '2025', 'PFB9995', 'esta señada', 12, 22);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
