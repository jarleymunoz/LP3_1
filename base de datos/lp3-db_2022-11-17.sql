-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 17-11-2022 a las 14:13:50
-- Versión del servidor: 8.0.18
-- Versión de PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lp3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(5) NOT NULL,
  `codigo` int(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `creditos` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id_curso`, `codigo`, `nombre`, `creditos`) VALUES
(1, 123, 'LP3', 3),
(2, 321, 'Gerencia informatica', 2),
(3, 789, 'Ingles', 2),
(4, 987, 'Seguridad informatica', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula`
--

CREATE TABLE `matricula` (
  `id_matricula` int(5) NOT NULL,
  `id_usuario` int(5) NOT NULL,
  `id_curso` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `matricula`
--

INSERT INTO `matricula` (`id_matricula`, `id_usuario`, `id_curso`) VALUES
(1, 16, 1),
(7, 16, 2),
(8, 16, 3),
(9, 16, 4),
(10, 3, 3),
(11, 17, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(1) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Docente'),
(3, 'Alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(8) NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `rol` int(1) NOT NULL,
  `token` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `identificacion`, `primer_apellido`, `segundo_apellido`, `nombre`, `email`, `usuario`, `clave`, `rol`, `token`) VALUES
(1, '123456789', 'Munoz', 'Lara', 'Jose', 'testestek@gmail.com', 'joarl940', '$2y$10$MAUx.T0Dwvynow4QwbJyNe7r2nonb1UjTkj2GJSe/Hh8eUXSVWDwS', 1, '6372fda690cf0'),
(2, '987654321', 'Melo', 'Camacho', 'Adriana', 'amelo@gmail.com', 'Amelo', '$2y$10$ZySqBAnQd8IYDbEK73qvkuQSBndE/ZxAelX3pwbwd7aNLSIjjCvD.', 2, '6372fdc63b3a9'),
(3, '1122334455', 'Melo', 'Melo', 'Alexandra', 'alexmelo@gmail.com', 'AlexMelo', '$2y$10$2GHBJuivvWGYwOCZK08SIO07SrUl1W0FOG1KjdurbNA8qSmcKrF6i', 3, NULL),
(16, '7686578', 'Perez', 'Gomez', 'Daniel', 'dapepe@gmail.com', 'Dapepe', '$2y$10$Z.TfZe/hSsFBG6oN6HWfFuOPF3CGnnW9MoFgv75CXfWWXV1sxBpum', 3, NULL),
(17, '777777', 'caceres', 'forero', 'felipe', 'fecafo@gmail.com', 'fecafo', '$2y$10$rHyDwq1/GT0JsN8nfVDmA.SvObnuuIRARXs1bxEppLypRewlpqR/C', 3, NULL),
(18, '51651615', 'Trivino', 'Barrera', 'Ricardo', 'ricardo.trivinob@gmail.com', 'rtrivino', '$2y$10$F8Go6Jg2J4s3fAkpid/x.edRi3O/DWQ933kA6CHyi5KDEbmAghp1u', 3, NULL),
(19, '1651651', 'Trivino', 'Barrera', 'Andrey', 'andrey@gmail.com', 'andrey', '$2y$10$AYVV1AQZbuMx5yMhgvwZ7ergVNoROpeA9FRbu8ofrTkysfKmU.Ap6', 3, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD PRIMARY KEY (`id_matricula`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `matricula`
--
ALTER TABLE `matricula`
  MODIFY `id_matricula` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
