-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2023 a las 20:41:46
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `consultorio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `medico_id` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `consulta_tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `paciente_id`, `medico_id`, `fecha_hora`, `consulta_tipo`) VALUES
(5, 5, 3, '2023-12-28 15:29:00', 1),
(7, 5, 5, '2024-05-29 23:35:00', 1),
(9, 5, 3, '2023-12-21 23:37:00', 1),
(14, 5, 3, '2023-12-21 16:09:00', 1),
(18, 23, 3, '2023-12-21 11:05:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id_medico` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id_medico`, `nombre`, `apellido`, `especialidad`, `telefono`, `email`) VALUES
(3, 'Jose ramon', 'Romo Rodríguez', 'Médico pediatra', '4494023959', 'drjose@ejemplo.com'),
(5, 'Maria', 'Juarez Martínez', 'Dermatóloga', '44978565635', 'dramaria@ejemplo.com'),
(7, 'Martin', 'Chairez Zapata', 'Odontologo', '4497858585', 'drmartin@martin.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `telefono`, `email`) VALUES
(5, 'Karen', 'Hernandez Hermosillo', '2001-06-26', 'femenino', '4498563252', 'karen@karen.com'),
(9, 'Josue Ramon', 'Medina Montoya', '2002-01-01', 'masculino', '4149565656', 'josue@josue.com'),
(15, 'Ruben', 'Torres Martínez', '2003-06-23', 'masculino', '4498568585', 'torres@torres.com'),
(16, 'Cuitlahuac', 'Torres Martínez', '1988-03-20', 'masculino', '4498565656', 'cui@cui.com'),
(17, 'Esperanza', 'Ramirez', '1970-05-20', 'femenino', '4497856555', 'esperanzaR@correo.com'),
(18, 'skdjhfksjd', 'sdfsd', '1997-01-28', 'femenino', '1654546546', 'torres@torres.com'),
(19, 'asdfadf', 'adfad', '1980-05-04', 'masculino', '54658464654', 'siu@siu.com'),
(22, 'Hazael el chamuco', 'Vázquez González', '1980-12-08', 'Masculino', '4493256598', 'hazael@gmail.com'),
(23, 'Laura', 'Martínez Rodríguez', '1970-06-29', 'femenino', '4498528578', 'laura@laura.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_consulta`
--

CREATE TABLE `tipo_consulta` (
  `id_consulta` int(11) NOT NULL,
  `consulta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_consulta`
--

INSERT INTO `tipo_consulta` (`id_consulta`, `consulta`) VALUES
(1, 'Chequeo Gneral'),
(2, 'Análisis Clínicos'),
(3, 'Radiografía');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(355) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('usuario','administrador') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `role`) VALUES
(1, 'rebeca@rebeca.com', '81dc9bdb52d04dc20036dbd8313ed055', 'administrador'),
(5, 'karen@karen.com', '81dc9bdb52d04dc20036dbd8313ed055', 'usuario'),
(22, 'hazael@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'usuario'),
(23, 'laura@laura.com', '81dc9bdb52d04dc20036dbd8313ed055', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`),
  ADD KEY `fk_consulta_tipo` (`consulta_tipo`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id_medico`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `tipo_consulta`
--
ALTER TABLE `tipo_consulta`
  ADD PRIMARY KEY (`id_consulta`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id_medico`),
  ADD CONSTRAINT `fk_consulta_tipo` FOREIGN KEY (`consulta_tipo`) REFERENCES `tipo_consulta` (`id_consulta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
