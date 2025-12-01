-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 01-12-2025 a las 11:49:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `worldbuildingapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulosgenericos`
--

CREATE TABLE `articulosgenericos` (
  `id_articulo` int(11) NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `contenido` text DEFAULT NULL,
  `tipo` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asentamientos`
--

CREATE TABLE `asentamientos` (
  `id` int(16) NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `id_tipo_asentamiento` int(16) DEFAULT NULL,
  `gentilicio` varchar(256) DEFAULT NULL,
  `fundacion` int(16) DEFAULT 0,
  `disolucion` int(16) DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `poblacion` int(11) DEFAULT NULL,
  `demografia` text DEFAULT NULL,
  `gobierno` text DEFAULT NULL,
  `infraestructura` text DEFAULT NULL,
  `historia` text DEFAULT NULL,
  `defensas` text DEFAULT NULL,
  `economia` text DEFAULT NULL,
  `cultura` text DEFAULT NULL,
  `geografia` text DEFAULT NULL,
  `clima` text DEFAULT NULL,
  `recursos` text DEFAULT NULL,
  `otros` text DEFAULT NULL,
  `id_owner` int(16) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conflicto`
--

CREATE TABLE `conflicto` (
  `id` int(16) NOT NULL,
  `nombre` varchar(256) DEFAULT NULL,
  `id_tipo_conflicto` int(16) DEFAULT NULL,
  `tipo_localizacion` varchar(64) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `preludio` text DEFAULT NULL,
  `desarrollo` text DEFAULT NULL,
  `resultado` text DEFAULT NULL,
  `consecuencias` text DEFAULT NULL,
  `otros` text DEFAULT NULL,
  `id_conflicto_padre` int(16) DEFAULT NULL,
  `fecha_inicio` int(16) DEFAULT 0,
  `fecha_fin` int(16) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conflicto_beligerantes`
--

CREATE TABLE `conflicto_beligerantes` (
  `id` int(11) NOT NULL,
  `id_conflicto` int(11) DEFAULT NULL,
  `id_organizacion` int(11) DEFAULT NULL,
  `lado` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conflicto_personajes`
--

CREATE TABLE `conflicto_personajes` (
  `id` int(16) NOT NULL,
  `id_conflicto` int(16) NOT NULL,
  `id_personaje` int(16) NOT NULL,
  `rol` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `construccions`
--

CREATE TABLE `construccions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(256) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `historia` text DEFAULT NULL,
  `proposito` text DEFAULT NULL,
  `aspecto` text DEFAULT NULL,
  `otros` text DEFAULT NULL,
  `tipo` bigint(20) UNSIGNED DEFAULT NULL,
  `ubicacion` int(11) DEFAULT NULL,
  `construccion` int(11) NOT NULL DEFAULT 0,
  `destruccion` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlaces`
--

CREATE TABLE `enlaces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies`
--

CREATE TABLE `especies` (
  `id` int(16) NOT NULL,
  `nombre` varchar(256) DEFAULT NULL,
  `edad` varchar(64) DEFAULT NULL,
  `peso` varchar(64) DEFAULT NULL,
  `altura` varchar(64) DEFAULT NULL,
  `longitud` varchar(64) DEFAULT NULL,
  `estatus` varchar(64) DEFAULT NULL,
  `anatomia` text DEFAULT NULL,
  `alimentacion` text DEFAULT NULL,
  `reproduccion` text DEFAULT NULL,
  `distribucion` text DEFAULT NULL,
  `habilidades` text DEFAULT NULL,
  `domesticacion` text DEFAULT NULL,
  `explotacion` text DEFAULT NULL,
  `otros` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fechas`
--

CREATE TABLE `fechas` (
  `id` int(16) NOT NULL,
  `dia` int(11) DEFAULT 0,
  `mes` int(11) DEFAULT 0,
  `anno` int(11) DEFAULT NULL,
  `tabla` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `table_owner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_temporales`
--

CREATE TABLE `lineas_temporales` (
  `id` int(16) NOT NULL,
  `nombre` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares`
--

CREATE TABLE `lugares` (
  `id` int(32) NOT NULL,
  `nombre` varchar(256) DEFAULT NULL,
  `descripcion_breve` text DEFAULT NULL,
  `id_tipo_lugar` int(16) DEFAULT NULL,
  `otros_nombres` text DEFAULT NULL,
  `geografia` text DEFAULT NULL,
  `ecosistema` text DEFAULT NULL,
  `clima` text DEFAULT NULL,
  `flora_fauna` text DEFAULT NULL,
  `recursos` text DEFAULT NULL,
  `historia` text DEFAULT NULL,
  `otros` text DEFAULT NULL,
  `id_owner` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nombres`
--

CREATE TABLE `nombres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lista` text DEFAULT NULL,
  `tipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizaciones`
--

CREATE TABLE `organizaciones` (
  `id_organizacion` int(16) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `disolucion` int(16) DEFAULT 0,
  `fundacion` int(16) DEFAULT 0,
  `id_tipo_organizacion` int(16) DEFAULT NULL,
  `id_owner` int(16) DEFAULT 0,
  `id_ruler` int(16) DEFAULT 0,
  `gentilicio` varchar(128) DEFAULT NULL,
  `capital` varchar(128) DEFAULT NULL,
  `id_capital` int(16) DEFAULT 0,
  `descripcionBreve` text DEFAULT NULL,
  `lema` varchar(512) DEFAULT NULL,
  `demografia` text DEFAULT NULL,
  `historia` text DEFAULT NULL,
  `estructura` text DEFAULT NULL,
  `politicaExteriorInterior` text DEFAULT NULL,
  `militar` text DEFAULT NULL,
  `religion` text DEFAULT NULL,
  `cultura` text DEFAULT NULL,
  `educacion` text DEFAULT NULL,
  `tecnologia` text DEFAULT NULL,
  `territorio` text DEFAULT NULL,
  `economia` text DEFAULT NULL,
  `recursosNaturales` text DEFAULT NULL,
  `otros` text DEFAULT NULL,
  `escudo` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaje`
--

CREATE TABLE `personaje` (
  `id` int(16) NOT NULL,
  `Nombre` varchar(256) NOT NULL,
  `id_foranea_especie` int(16) DEFAULT NULL,
  `nacimiento` int(16) DEFAULT NULL,
  `fallecimiento` int(16) DEFAULT NULL,
  `lugar_nacimiento` int(11) DEFAULT NULL,
  `nombreFamilia` varchar(256) DEFAULT NULL,
  `Apellidos` text DEFAULT NULL,
  `causa_fallecimiento` varchar(256) DEFAULT NULL,
  `DescripcionShort` text DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `salud` text DEFAULT NULL,
  `Personalidad` text DEFAULT NULL,
  `Deseos` text DEFAULT NULL,
  `Miedos` text DEFAULT NULL,
  `Magia` text DEFAULT NULL,
  `educacion` text DEFAULT NULL,
  `Historia` text DEFAULT NULL,
  `Religion` text DEFAULT NULL,
  `Familia` text DEFAULT NULL,
  `Politica` text DEFAULT NULL,
  `Retrato` varchar(128) DEFAULT NULL,
  `Sexo` varchar(16) DEFAULT NULL,
  `otros` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personajes_relevantes`
--

CREATE TABLE `personajes_relevantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `relato` int(11) DEFAULT NULL,
  `personaje` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `religiones`
--

CREATE TABLE `religiones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `lema` varchar(256) DEFAULT NULL,
  `escudo` varchar(256) NOT NULL DEFAULT 'default.png',
  `descripcion` text DEFAULT NULL,
  `historia` text DEFAULT NULL,
  `cosmologia` text DEFAULT NULL,
  `doctrina` text DEFAULT NULL,
  `sagrado` text DEFAULT NULL,
  `fiestas` text DEFAULT NULL,
  `politica` text DEFAULT NULL,
  `estructura` text DEFAULT NULL,
  `sectas` text DEFAULT NULL,
  `otros` text DEFAULT NULL,
  `fundacion` int(11) NOT NULL DEFAULT 0,
  `disolucion` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `religion_presence`
--

CREATE TABLE `religion_presence` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organizacion` int(11) NOT NULL,
  `religion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `timelines`
--

CREATE TABLE `timelines` (
  `dia` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `anno` int(11) DEFAULT NULL,
  `id` int(16) NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_linea_temporal` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_asentamiento`
--

CREATE TABLE `tipo_asentamiento` (
  `id` int(16) NOT NULL,
  `nombre` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_conflicto`
--

CREATE TABLE `tipo_conflicto` (
  `id` int(16) NOT NULL,
  `nombre` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_construccion`
--

CREATE TABLE `tipo_construccion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_lugar`
--

CREATE TABLE `tipo_lugar` (
  `id` int(16) NOT NULL,
  `nombre` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_organizacion`
--

CREATE TABLE `tipo_organizacion` (
  `id` int(16) NOT NULL,
  `nombre` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulosgenericos`
--
ALTER TABLE `articulosgenericos`
  ADD PRIMARY KEY (`id_articulo`);

--
-- Indices de la tabla `asentamientos`
--
ALTER TABLE `asentamientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_owner` (`id_owner`),
  ADD KEY `id_tipo_asentamiento` (`id_tipo_asentamiento`),
  ADD KEY `asentamiento_disolucion` (`disolucion`),
  ADD KEY `fundacion` (`fundacion`) USING BTREE;

--
-- Indices de la tabla `conflicto`
--
ALTER TABLE `conflicto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo_conflicto` (`id_tipo_conflicto`),
  ADD KEY `id_conflicto_padre` (`id_conflicto_padre`),
  ADD KEY `fecha_inicio` (`fecha_inicio`,`fecha_fin`),
  ADD KEY `fk_fecha_fin` (`fecha_fin`);

--
-- Indices de la tabla `conflicto_beligerantes`
--
ALTER TABLE `conflicto_beligerantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_organizacion` (`id_organizacion`),
  ADD KEY `id_conflicto` (`id_conflicto`);

--
-- Indices de la tabla `conflicto_personajes`
--
ALTER TABLE `conflicto_personajes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `construccions`
--
ALTER TABLE `construccions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_id` (`tipo`),
  ADD KEY `construccions_ubicacion_foreign` (`ubicacion`),
  ADD KEY `construccions_construccion_foreign` (`construccion`),
  ADD KEY `construccions_destruccion_foreign` (`destruccion`);

--
-- Indices de la tabla `enlaces`
--
ALTER TABLE `enlaces`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `especies`
--
ALTER TABLE `especies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fechas`
--
ALTER TABLE `fechas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `imagenes_nombre_unique` (`nombre`);

--
-- Indices de la tabla `lineas_temporales`
--
ALTER TABLE `lineas_temporales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lugares`
--
ALTER TABLE `lugares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo_lugar` (`id_tipo_lugar`),
  ADD KEY `id_owner` (`id_owner`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nombres`
--
ALTER TABLE `nombres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `organizaciones`
--
ALTER TABLE `organizaciones`
  ADD PRIMARY KEY (`id_organizacion`),
  ADD KEY `id_tipo_organizacion` (`id_tipo_organizacion`),
  ADD KEY `id_ruler` (`id_ruler`),
  ADD KEY `id_owner` (`id_owner`),
  ADD KEY `fundacion` (`fundacion`),
  ADD KEY `disolucion` (`disolucion`),
  ADD KEY `id_capital` (`id_capital`);

--
-- Indices de la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_foranea_especie` (`id_foranea_especie`),
  ADD KEY `lugar_nacimiento` (`lugar_nacimiento`),
  ADD KEY `nacimiento` (`nacimiento`) USING BTREE,
  ADD KEY `personaje_fallecimiento` (`fallecimiento`);

--
-- Indices de la tabla `personajes_relevantes`
--
ALTER TABLE `personajes_relevantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personajes_relevantes_relato_foreign` (`relato`),
  ADD KEY `personajes_relevantes_personaje_foreign` (`personaje`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `religiones`
--
ALTER TABLE `religiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `religiones_fundacion_foreign` (`fundacion`),
  ADD KEY `religiones_disolucion_foreign` (`disolucion`);

--
-- Indices de la tabla `religion_presence`
--
ALTER TABLE `religion_presence`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `timelines`
--
ALTER TABLE `timelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `linea_temporal` (`id_linea_temporal`),
  ADD KEY `id_linea_temporal` (`id_linea_temporal`);

--
-- Indices de la tabla `tipo_asentamiento`
--
ALTER TABLE `tipo_asentamiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_conflicto`
--
ALTER TABLE `tipo_conflicto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_construccion`
--
ALTER TABLE `tipo_construccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_lugar`
--
ALTER TABLE `tipo_lugar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_organizacion`
--
ALTER TABLE `tipo_organizacion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulosgenericos`
--
ALTER TABLE `articulosgenericos`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asentamientos`
--
ALTER TABLE `asentamientos`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conflicto`
--
ALTER TABLE `conflicto`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conflicto_beligerantes`
--
ALTER TABLE `conflicto_beligerantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conflicto_personajes`
--
ALTER TABLE `conflicto_personajes`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `construccions`
--
ALTER TABLE `construccions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enlaces`
--
ALTER TABLE `enlaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fechas`
--
ALTER TABLE `fechas`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lineas_temporales`
--
ALTER TABLE `lineas_temporales`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lugares`
--
ALTER TABLE `lugares`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nombres`
--
ALTER TABLE `nombres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `organizaciones`
--
ALTER TABLE `organizaciones`
  MODIFY `id_organizacion` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personaje`
--
ALTER TABLE `personaje`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personajes_relevantes`
--
ALTER TABLE `personajes_relevantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `religiones`
--
ALTER TABLE `religiones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `religion_presence`
--
ALTER TABLE `religion_presence`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `timelines`
--
ALTER TABLE `timelines`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_asentamiento`
--
ALTER TABLE `tipo_asentamiento`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_conflicto`
--
ALTER TABLE `tipo_conflicto`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_construccion`
--
ALTER TABLE `tipo_construccion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_lugar`
--
ALTER TABLE `tipo_lugar`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_organizacion`
--
ALTER TABLE `tipo_organizacion`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asentamientos`
--
ALTER TABLE `asentamientos`
  ADD CONSTRAINT `asentamiento_disolucion` FOREIGN KEY (`disolucion`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asentamiento_fundacion` FOREIGN KEY (`fundacion`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asentamientos_ibfk_1` FOREIGN KEY (`id_owner`) REFERENCES `organizaciones` (`id_organizacion`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `asentamientos_ibfk_2` FOREIGN KEY (`id_tipo_asentamiento`) REFERENCES `tipo_asentamiento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `conflicto`
--
ALTER TABLE `conflicto`
  ADD CONSTRAINT `conflicto_ibfk_1` FOREIGN KEY (`id_tipo_conflicto`) REFERENCES `tipo_conflicto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `conflicto_ibfk_2` FOREIGN KEY (`id_conflicto_padre`) REFERENCES `conflicto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fecha_comienzo` FOREIGN KEY (`fecha_inicio`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fecha_fin` FOREIGN KEY (`fecha_fin`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `conflicto_beligerantes`
--
ALTER TABLE `conflicto_beligerantes`
  ADD CONSTRAINT `conflicto_beligerantes_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `organizaciones` (`id_organizacion`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `conflicto_beligerantes_ibfk_2` FOREIGN KEY (`id_conflicto`) REFERENCES `conflicto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `construccions`
--
ALTER TABLE `construccions`
  ADD CONSTRAINT `construccions_construccion_foreign` FOREIGN KEY (`construccion`) REFERENCES `fechas` (`id`),
  ADD CONSTRAINT `construccions_destruccion_foreign` FOREIGN KEY (`destruccion`) REFERENCES `fechas` (`id`),
  ADD CONSTRAINT `construccions_ubicacion_foreign` FOREIGN KEY (`ubicacion`) REFERENCES `asentamientos` (`id`),
  ADD CONSTRAINT `tipo_id` FOREIGN KEY (`tipo`) REFERENCES `tipo_construccion` (`id`);

--
-- Filtros para la tabla `lugares`
--
ALTER TABLE `lugares`
  ADD CONSTRAINT `lugares_ibfk_1` FOREIGN KEY (`id_tipo_lugar`) REFERENCES `tipo_lugar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `lugares_ibfk_2` FOREIGN KEY (`id_owner`) REFERENCES `organizaciones` (`id_organizacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `organizaciones`
--
ALTER TABLE `organizaciones`
  ADD CONSTRAINT `organizaciones_capital` FOREIGN KEY (`id_capital`) REFERENCES `asentamientos` (`id`),
  ADD CONSTRAINT `organizaciones_disolucion` FOREIGN KEY (`disolucion`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organizaciones_fundacion` FOREIGN KEY (`fundacion`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organizaciones_ibfk_1` FOREIGN KEY (`id_tipo_organizacion`) REFERENCES `tipo_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `organizaciones_ibfk_2` FOREIGN KEY (`id_ruler`) REFERENCES `personaje` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organizaciones_ibfk_3` FOREIGN KEY (`id_owner`) REFERENCES `organizaciones` (`id_organizacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD CONSTRAINT `personaje_fallecimiento` FOREIGN KEY (`fallecimiento`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personaje_ibfk_1` FOREIGN KEY (`id_foranea_especie`) REFERENCES `especies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `personaje_nacimiento` FOREIGN KEY (`nacimiento`) REFERENCES `fechas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personajes_relevantes`
--
ALTER TABLE `personajes_relevantes`
  ADD CONSTRAINT `personajes_relevantes_personaje_foreign` FOREIGN KEY (`personaje`) REFERENCES `personaje` (`id`),
  ADD CONSTRAINT `personajes_relevantes_relato_foreign` FOREIGN KEY (`relato`) REFERENCES `articulosgenericos` (`id_articulo`);

--
-- Filtros para la tabla `religiones`
--
ALTER TABLE `religiones`
  ADD CONSTRAINT `religiones_disolucion_foreign` FOREIGN KEY (`disolucion`) REFERENCES `fechas` (`id`),
  ADD CONSTRAINT `religiones_fundacion_foreign` FOREIGN KEY (`fundacion`) REFERENCES `fechas` (`id`);

--
-- Filtros para la tabla `timelines`
--
ALTER TABLE `timelines`
  ADD CONSTRAINT `timelines_ibfk_1` FOREIGN KEY (`id_linea_temporal`) REFERENCES `lineas_temporales` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
