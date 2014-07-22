
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `MonChauffeEau`
--

DROP TABLE IF EXISTS `MonChauffeEau`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MonChauffeEau` (
  `idMonChauffeEau` int(11) NOT NULL AUTO_INCREMENT,
  `Mode` tinyint(4) NOT NULL DEFAULT '1',
  `CmdRelayOn` varchar(200) NOT NULL,
  `CmdRelayOff` varchar(200) NOT NULL,
  `CmdRelayStatus` varchar(200) DEFAULT NULL,
  `CmdGetHotWater` varchar(200) NOT NULL,
  `WaterHeaterPower` int(11) NOT NULL DEFAULT '3000',
  `Mode0ModeReStartDate` datetime NOT NULL,
  `Mode0ModeTransition` int(11) NOT NULL DEFAULT '2',
  `Mode1HeatingTime` int(11) NOT NULL DEFAULT '40',
  `Mode1SummerEnable` tinyint(1) NOT NULL DEFAULT '0',
  `SummerModeStartDate` datetime NOT NULL,
  `SummerModeEndDate` datetime NOT NULL,
  `SummerModeHeatingTime` int(11) DEFAULT '30',
  `Mode2HeatingTime` int(11) NOT NULL DEFAULT '240',
  `Mode2ModeTransition` int(11) NOT NULL DEFAULT '3',
  `Mode3MaxDayWithoutHeating` int(11) NOT NULL DEFAULT '3',
  `Mode3WaterHeaterCapacity` int(11) NOT NULL DEFAULT '300',
  `Mode3WaterUsagePerCentWithoutHeating` int(11) NOT NULL DEFAULT '40',
  `LastDayConso` float NOT NULL DEFAULT '0',
  `LastDaysConsoWithoutHeating` float NOT NULL DEFAULT '0',
  `NumberDaysWithoutHeating` int(11) NOT NULL DEFAULT '0',
  `HCEndTime` int(11) NOT NULL DEFAULT '6',
  `MCEStartScriptTime` int(11) NOT NULL DEFAULT '0',
  `LastPowerUsage` int(11) NOT NULL DEFAULT '0',
  `Language` int(11) NOT NULL DEFAULT '0',
  `ConsoDay1CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay2CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay3CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay4CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay5CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay6CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay7CW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay1HW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay2HW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay3HW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay4HW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay5HW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay6HW` int(11) NOT NULL DEFAULT '0',
  `ConsoDay7HW` int(11) NOT NULL DEFAULT '0',
  `MinHeating` int(11) NOT NULL DEFAULT '0',
  `CmdGetColdWater` varchar(200) NOT NULL DEFAULT 'cmd',
  PRIMARY KEY (`idMonChauffeEau`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MonChauffeEau`
--

LOCK TABLES `MonChauffeEau` WRITE;
/*!40000 ALTER TABLE `MonChauffeEau` DISABLE KEYS */;
INSERT INTO `MonChauffeEau` VALUES (1,1,'/home/user/gcerbuffer/gcerbset.sh led7_1','/home/user/gcerbuffer/gcerbset.sh led7_0','http://192.168.1.202:40405/stats/41/output/latest','GetWaterYesterday.php 0 40',2800,'2013-10-29 00:00:00',1,240,0,'2013-06-21 00:00:00','2013-09-21 00:00:00',180,400,1,1,300,90,31,0,0,7,0,11200,2,46,0,0,21,40,49,55,31,0,0,14,26,32,37,5,'GetWaterYesterday.php 0 60');
/*!40000 ALTER TABLE `MonChauffeEau` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
