-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-03-2024 a las 02:55:40
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_habitacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id`, `cantidad`, `comentario`, `fecha`, `id_habitacion`, `id_usuario`) VALUES
(1, 4, 'Hola que tal', '2024-03-21 16:21:02', 5, 5),
(2, 4, 'Excelente servicio, se los recomiendo', '2024-03-22 00:50:33', 4, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `estado`) VALUES
(1, 'Habitación Estándar', 1),
(2, 'Habitación Familiar', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `num_identidad` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `mensaje` text NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `num_identidad`, `nombre`, `telefono`, `direccion`, `correo`, `mensaje`, `facebook`, `twitter`, `instagram`, `whatsapp`) VALUES
(1, '123456789', 'VIDA INFORMATICO', '900897537', 'HUARI -ANCASH', 'info@angelsifuentes.net', 'Donde cada momento se transforma en una experiencia memorable. Disfruta de nuestra hospitalidad excepcional, comodidades de primer nivel y servicio impecable. ¡Tu escapada perfecta comienza aquí!', 'https://es-la.facebook.com/', 'https://twitter.com/?lang=es', 'https://www.instagram.com/', 'https://twitter.com/?lang=es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `foto` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `categorias` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL,
  `estilo` varchar(200) NOT NULL,
  `numero` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `estilo`, `numero`, `capacidad`, `slug`, `foto`, `video`, `descripcion`, `precio`, `estado`, `fecha`) VALUES
(1, 'Habitación Serenidad', 10, 3, 'habitacion-serenidad', '1.jpg', NULL, 'Un refugio tranquilo y relajante, perfecto para aquellos que buscan escapar del ajetreo y el bullicio de la vida cotidiana.', 50.00, 1, '2024-03-16 15:18:09'),
(2, 'Suite Ejecutiva', 150, 3, 'suite-ejecutiva', '2.jpg', NULL, 'Un espacio elegante y funcional diseñado para viajeros de negocios que valoran la comodidad y la eficiencia durante su estancia.', 50.00, 1, '2024-03-16 15:19:33'),
(3, 'Habitación Familiar', 50, 6, 'habitacion-familiar', '3.jpg', NULL, 'Amplia y acogedora, esta habitación es ideal para familias que desean compartir momentos especiales juntos mientras disfrutan de todas las comodidades del hogar.', 100.00, 1, '2024-03-16 15:19:33'),
(4, 'Suite Romántica', 150, 3, 'suite-romantica', '4.jpg', NULL, 'Una escapada íntima y lujosa diseñada para parejas que buscan reavivar la chispa del romance en un entorno elegante y privado.', 50.00, 1, '2024-03-16 15:22:56'),
(5, 'Habitación con Vistas al Mar', 50, 6, 'habitacion-vistas-mar', '5.jpg', NULL, 'Disfruta de impresionantes vistas al océano desde esta habitación, donde la brisa marina y el sonido de las olas crean un ambiente de tranquilidad y serenidad.', 100.00, 1, '2024-03-16 15:23:00'),
(6, 'Suite Deluxe', 150, 3, 'suite-deluxe', '6.jpg', NULL, 'Sumérgete en el lujo y la elegancia de esta suite excepcionalmente espaciosa, donde cada detalle está cuidadosamente diseñado para brindarte una experiencia inolvidable.', 50.00, 1, '2024-03-16 15:23:04'),
(7, 'Habitación Zen Garden', 50, 6, 'habitacion-zen-garden', '7.jpg', NULL, 'Encuentra paz y armonía en esta habitación inspirada en un jardín zen, donde la serenidad del entorno te invita a relajarte y renovar tus sentidos.', 100.00, 1, '2024-03-16 15:23:08'),
(8, 'Suite Presidencial', 150, 3, 'suite-presidencial', '8.jpg', NULL, 'Experimenta el máximo nivel de lujo y privacidad en esta suite exclusiva, diseñada para satisfacer incluso los gustos más exigentes de nuestros huéspedes VIP.', 50.00, 1, '2024-03-16 15:23:12'),
(9, 'Habitación Loft Urbano', 50, 6, 'habitacion-loft-urbano', '9.jpg', NULL, ' Disfruta de un estilo moderno y sofisticado en este loft urbano, donde el diseño vanguardista se combina con comodidades de primera clase para una estancia inigualable.', 100.00, 1, '2024-03-16 15:23:15'),
(10, 'Suite Skyline', 50, 6, 'suite-skyline', '10.jpg', NULL, 'Contempla las impresionantes vistas de la ciudad desde esta suite de altura, donde la elegancia se combina con panorámicas inigualables para una experiencia verdaderamente espectacular.', 100.00, 1, '2024-03-16 15:23:15'),
(11, 'habitacion eliminar', 30, 20, 'habitacion-eliminar', '20240319155234.jpg', NULL, '<p>esta se eliminara</p>', 40.00, 0, '2024-03-19 14:52:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `num_transaccion` varchar(50) NOT NULL,
  `cod_reserva` varchar(50) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `metodo` varchar(50) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `monto`, `num_transaccion`, `cod_reserva`, `fecha_ingreso`, `fecha_salida`, `fecha_reserva`, `descripcion`, `estado`, `metodo`, `id_habitacion`, `id_usuario`) VALUES
(1, 100.00, '5W778472PD6438208', '65bbcd2f6c31f', '2024-02-01', '2024-02-03', '2024-02-01 16:56:15', '<ol><li>Primer info.</li><li>Segundo info</li></ol>', 1, 'Paypal', 1, 1),
(2, 100.00, '1316848668', '65bbcd9980413', '2024-02-01', '2024-02-03', '2024-02-01 16:58:01', '<p>segundo</p>', 1, 'Mercado Pago', 1, 1),
(3, 100.00, '6TA1245808384350G', '65fb2c7a717f4', '2024-03-20', '2024-03-21', '2024-03-20 18:35:38', '<p><strong>Aviso!</strong> Tienes una reserva pendiente</p>', 1, 'Paypal', 5, 5),
(7, 400.00, '1321965051', '65fcd557eea59', '2024-03-22', '2024-03-30', '2024-03-22 00:48:23', '<p>VAMOS HACER UNA PRUEBA DE PAGO</p>', 1, 'Mercado Pago', 4, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `subtitulo` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sliders`
--

INSERT INTO `sliders` (`id`, `titulo`, `subtitulo`, `url`, `foto`, `estado`) VALUES
(1, 'Reserva Hoy', 'Vive Experiencias Inolvidables', 'http://localhost/reservas/', 'slider1.jpg', 1),
(2, 'Planifica Ahora', 'Comienza la Magia', 'http://localhost/reservas/', 'slider2.jpg', 1),
(3, 'Tu Hogar te Espera', 'Calidez y Comodidad', 'http://localhost/reservas/', 'slider3.jpg', 1),
(5, 'CUARTA', 'DESCRIPCION', NULL, '20240319152603.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `identidad` varchar(100) DEFAULT NULL,
  `num_identidad` varchar(20) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `apellido` varchar(150) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `clave` varchar(150) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `verify` int(11) NOT NULL DEFAULT 0,
  `rol` int(11) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `identidad`, `num_identidad`, `nombre`, `apellido`, `usuario`, `correo`, `telefono`, `direccion`, `clave`, `token`, `verify`, `rol`, `foto`, `estado`, `fecha`) VALUES
(1, NULL, NULL, 'NOMBRE', 'APELLIDO', 'USUARIO', 'info@angelsifuentes.net', NULL, NULL, '$2y$10$tlr673uHS3cIkQIPH2N3T.pd4UAveHGLBtpjwRGSZor0H2Li6ZEoO', NULL, 0, 1, NULL, 1, '2024-03-16 15:54:46'),
(2, NULL, NULL, 'NOMBRE', 'APELLIDO', 'USUARIO1', 'CORREO1@GMAIL.COM', NULL, NULL, '$2y$10$ks2E5DumEWR2A54Jz.2/y.BvmDAqMa9IRRAeH0961hyS4wAIpch..', NULL, 0, 2, NULL, 1, '2023-12-15 15:44:41'),
(3, NULL, NULL, 'VIDA', 'INFO', 'VIDAINFO', 'VIDAINFO@GMAIL.COM', NULL, '', '$2y$10$zJe2Ytx0J83YphA4kYWZUeLLp75UvmAyeM4Y1anvh/tMmvKTCJYF6', NULL, 0, 1, NULL, 1, '2024-03-18 16:12:29'),
(4, NULL, NULL, 'hola', 'hola edit', 'hola', 'hola@gmail.com', NULL, 'hola', '$2y$10$0pTbMKFLXKUDjUJL7X4DROrjfNo63gV8M1Ldalo3P4G9Z86pE0Pom', NULL, 0, 1, NULL, 0, '2024-03-18 16:17:10'),
(5, 'DNI', '3423243243', 'CLIENTE', 'ADMIN', '', 'ADMINCLIENTE@GMAIL.COM', '8978798798', 'PERU edit', '$2y$10$tlr673uHS3cIkQIPH2N3T.pd4UAveHGLBtpjwRGSZor0H2Li6ZEoO', NULL, 0, 3, NULL, 1, '2024-03-19 16:57:31'),
(6, NULL, NULL, 'Cliente', 'Para reserva', 'cliente', 'cliente@gmail.com', NULL, NULL, '$2y$10$ZIAkUSNxd8bM.DNng141NucQbCBQv/Wk.XnmUDV.UJTvoT9oIPEna', NULL, 0, 3, NULL, 1, '2024-03-22 00:41:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_habitacion` (`id_habitacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_habitacion` (`id_habitacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
