
/*   nombre de la base de datos: bd_tutorados                      /*

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `matriculados_2022` (
  `cod_estudiante` int(11) NOT NULL,
  `nombres_apellidos` varchar(20) NOT NULL
);


CREATE TABLE `distribucion_tutoria` (
  `cod_estudiante` int(11) NOT NULL,
  `nombres_apellidos` varchar(20) NOT NULL
);

--
--
