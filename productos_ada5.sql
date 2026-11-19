-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2026 a las 01:35:44
-- Versión del servidor: 10.11.16-MariaDB
-- Versión de PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `explorandoweb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_ada5`
--

CREATE TABLE `productos_ada5` (
  `id` int(11) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_ada5`
--

INSERT INTO `productos_ada5` (`id`, `codigo`, `nombre`, `descripcion`, `precio`, `activo`) VALUES
(1, 'HTML-GUIA', 'Guía de HTML', 'Recurso introductorio de HTML.', 99.00, 1),
(2, 'CSS-GUIA', 'Guía de CSS', 'Recurso para aprender diseño web.', 99.00, 1),
(3, 'JS-GUIA', 'Guía de JavaScript', 'Recurso de programación web.', 149.00, 1),
(4, 'PHP-GUIA', 'Guía de PHP', 'Recurso de programación del lado del servidor.', 179.00, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos_ada5`
--
ALTER TABLE `productos_ada5`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos_ada5`
--
ALTER TABLE `productos_ada5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
