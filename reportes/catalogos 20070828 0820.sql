-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.45-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;




--
-- Definition of table `cfg_catalogos22`
--

DROP TABLE IF EXISTS `cfg_catalogos2`;
CREATE TABLE `cfg_catalogos2` (
  `catalogo` varchar(30) NOT NULL,
  `titulo` varchar(255) default NULL,
  `tipo` int(1) default NULL,
  `clase` varchar(30) default NULL,
  `query` text,
  `parametros` text,
  `expresiones` text,
  `expresiones_det` text,
  `plantilla` varchar(30) default NULL,
  `navegador` varchar(30) default NULL,
  `busqueda` int(1) default '1',
  `campos_busquedas` varchar(255) default NULL,
  `reg_pag` int(4) default '-1',
  `pag_bloque` int(4) default '-1',
  `reg_grupo` int(4) default '-1',
  PRIMARY KEY  (`catalogo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cfg_catalogos2`
--

/*!40000 ALTER TABLE `cfg_catalogos2` DISABLE KEYS */;
INSERT INTO `cfg_catalogos2` (`catalogo`,`titulo`,`tipo`,`clase`,`query`,`parametros`,`expresiones`,`expresiones_det`,`plantilla`,`navegador`,`busqueda`,`campos_busquedas`,`reg_pag`,`pag_bloque`,`reg_grupo`) VALUES 
 ('catalogo_1','catalogo uno',NULL,NULL,'select * from cfg_campos','q_detalle:select id,nombre from cfg_usuarios union select sum(id) as id,\'&nbsp\\;\' from cfg_usuarios ','x:1000;','x:{exp=&EX_x+10};','catalogo',NULL,1,NULL,0,0,-1);
/*!40000 ALTER TABLE `cfg_catalogos2` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
