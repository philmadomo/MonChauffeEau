<?php
// Get the WaterHeater info in the database: Mode, CmdRelayON...
// and display it in bash style with ";" between value: 
//    Mode=1;WaterHeaterPower=3000;...
//
// Database ID: idMonChauffeEau
// Mode, CmdRelayOn, CmdRelayOff, CmdRelayStatus, CmdGetHotWater, WaterHeaterPower, Mode0ModeReStartDate, Mode0ModeTransition, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate, SummerModeHeatingTime, Mode2HeatingTime, Mode2ModeTransition, Mode3MaxDayWithoutHeating, Mode3WaterHeaterCapacity, Mode3WaterUsagePerCentWithoutHeating, HCEndTime, MCEStartScriptTime
// LastDayConso
// LastDaysConsoWithoutHeating
// NumberDaysWithoutHeating
// MinHeating
		
// 
// For Debug Purpose: Set debug parameter to 1
//
//
require_once("inc/inc_bddcx.php");
$err=0;
$debug=0;

// Get Input Param argv method (Debug & Zone Number & daynumber)
if (isset($argv[1])){
	$_GET['debug']= $argv[1];
	}

// Get Input Param GET method (Debug & Zone Number & daynumber)
if (isset($_GET['debug']) && !empty($_GET['debug'])){
			$debug=1;
			}

if($debug == 1)
	{
	echo "[Get Water Heater Info from DB]\n";
	}


// Get SELECT Water Heater Info
$sql_result=mysql_query("SELECT Mode, CmdRelayOn, CmdRelayOff, CmdRelayStatus, CmdGetHotWater, CmdGetColdWater, WaterHeaterPower, Mode0ModeReStartDate, Mode0ModeTransition, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate, SummerModeHeatingTime, Mode2HeatingTime, Mode2ModeTransition, Mode3MaxDayWithoutHeating, Mode3WaterHeaterCapacity, Mode3WaterUsagePerCentWithoutHeating, LastDayConso, LastDaysConsoWithoutHeating, NumberDaysWithoutHeating, HCEndTime, MCEStartScriptTime,MinHeating
FROM `MonChauffeEau` AS che
WHERE che.`idMonChauffeEau` = 1
LIMIT 1 ",$connection)	or exit("Sql Error".mysql_error());
$sql_num=mysql_num_rows($sql_result);

while($sql_row=mysql_fetch_array($sql_result))
{
	$WaterHeaterMode=$sql_row["Mode"];
	$CmdRelayOn=$sql_row["CmdRelayOn"];
	$CmdRelayOff=$sql_row["CmdRelayOff"];
	$CmdRelayStatus=$sql_row["CmdRelayStatus"];
	$CmdGetHotWater=$sql_row["CmdGetHotWater"];
	$CmdGetColdWater=$sql_row["CmdGetColdWater"];
	$WaterHeaterPower=$sql_row["WaterHeaterPower"];
	$Mode0ModeReStartDate=$sql_row["Mode0ModeReStartDate"];
        $Mode0ModeTransition=$sql_row["Mode0ModeTransition"];
	$Mode1HeatingTime=$sql_row["Mode1HeatingTime"];
	$Mode1SummerEnable=$sql_row["Mode1SummerEnable"];
        $SummerModeStartDate=$sql_row["SummerModeStartDate"];
	$SummerModeEndDate=$sql_row["SummerModeEndDate"];
	$SummerModeHeatingTime=$sql_row["SummerModeHeatingTime"];
        $Mode2HeatingTime=$sql_row["Mode2HeatingTime"];
	$Mode2ModeTransition=$sql_row["Mode2ModeTransition"];
        $Mode3MaxDayWithoutHeating=$sql_row["Mode3MaxDayWithoutHeating"];
	$Mode3WaterHeaterCapacity=$sql_row["Mode3WaterHeaterCapacity"];
        $Mode3WaterUsagePerCentWithoutHeating=$sql_row["Mode3WaterUsagePerCentWithoutHeating"];
	$LastDayConso=$sql_row["LastDayConso"];
	$LastDaysConsoWithoutHeating=$sql_row["LastDaysConsoWithoutHeating"];
	$NumberDaysWithoutHeating=$sql_row["NumberDaysWithoutHeating"];
	$HCEndTime=$sql_row["HCEndTime"];
	$MCEStartScriptTime=$sql_row["MCEStartScriptTime"];
	$MinHeating=$sql_row["MinHeating"];
}

mysql_close();

// Test if the first output is ok
if (isset($WaterHeaterMode)){
			echo "WaterHeaterMode=$WaterHeaterMode;";
			echo "CmdRelayOn='$CmdRelayOn';";
			echo "CmdRelayOff='$CmdRelayOff';";
                        echo "CmdRelayStatus='$CmdRelayStatus';";
                        echo "CmdGetHotWater='$CmdGetHotWater';";
			echo "CmdGetColdWater='$CmdGetColdWater';";
                        echo "WaterHeaterPower=$WaterHeaterPower;";
                        echo "Mode0ModeReStartDate='$Mode0ModeReStartDate';";
                        echo "Mode0ModeTransition=$Mode0ModeTransition;";
                        echo "Mode1HeatingTime=$Mode1HeatingTime;";
                        echo "Mode1SummerEnable=$Mode1SummerEnable;";
                        echo "SummerModeStartDate='$SummerModeStartDate';";
                        echo "SummerModeEndDate='$SummerModeEndDate';";
                        echo "SummerModeHeatingTime=$SummerModeHeatingTime;";
                        echo "Mode2HeatingTime=$Mode2HeatingTime;";
                        echo "Mode2ModeTransition=$Mode2ModeTransition;";
			echo "Mode3MaxDayWithoutHeating=$Mode3MaxDayWithoutHeating;";
                        echo "Mode3WaterHeaterCapacity=$Mode3WaterHeaterCapacity;";
			echo "Mode3WaterUsagePerCentWithoutHeating=$Mode3WaterUsagePerCentWithoutHeating;";
			echo "HCEndTime=$HCEndTime;";
			echo "MCEStartScriptTime=$MCEStartScriptTime;";
			echo "LastDayConso=$LastDayConso;";
			echo "LastDaysConsoWithoutHeating=$LastDaysConsoWithoutHeating;";
			echo "NumberDaysWithoutHeating=$NumberDaysWithoutHeating;";
			echo "MinHeating=$MinHeating";
			}
			else
			{ // Error: var $HeaterCmdCheck NOT Set
			echo "WaterHeaterMode=E";
			}			
?>
