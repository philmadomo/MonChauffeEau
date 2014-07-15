<?php
// Set the Cold & Hot Water Usage : 
// in WaterHeater Databasa
// and Output OK or NOK:
//    1 // OK
//		or
//		0 // NOK
//
// For Debug Purpose: Set debug parameter to 1
// 
// 
// 1) Get ConsoDay1CW -> ConsoDay6CW and ConsoDay1HW -> ConsoDay6HW
// 2) Set LastColdWater on ConsoDay1CW 
// 3) Set LastHotWater on ConsoDay1HW
// 4) Then Set ConsoDay1CW to ConsoDay2CW.... and ConsoDay1HW to ConsoDay2HW...
require_once("inc/inc_bddcx.php");
$err=0;
$debug=0;

// Get Param argv method
if (isset($argv[1])){
	$_GET['debug']= $argv[1];
	}

if (isset($argv[2])){
	$_GET['lastcoldwater']= $argv[2];
	}

if (isset($argv[3])){
	$_GET['lasthotwater']= $argv[3];
	}

// Get Input Param GET method (debug , ldc, ldcwh, ndwh)

if (isset($_GET['debug']) && !empty($_GET['debug'])){
			$debug=1;
			}

///////////// LastColdWaterUsage

if (isset($_GET['lastcoldwater']) && !empty($_GET['lastcoldwater'])){
                        $lastcoldwater=$_GET['lastcoldwater'];
                        if (str_replace(' ','',$lastcoldwater)==''){
                                if($debug == 1){
                                        echo "lastcoldwater is not set: Default Value >>> 0\n";
                                }
                                $lastcoldwater="0";
                        }
                }else{
                        if($debug == 1){
                                echo "lastcoldwater to default >>> 0\n";//debug
                                }
                        $lastcoldwater="0";
                }

//test lastcoldwaterusage  (String to Int function) and ONLY a Number
if (is_numeric($lastcoldwater))
{
        if($debug == 1){
                echo "lastcoldwater Is Numeric \n";//debug
        }
}
else
{
        if($debug == 1){
                echo "lastcoldwater Is NOT Numeric \n";//debug
        }
        $err=1;
        $lastcoldwater="0";
}

/////////////// Lasthotwaterusage
if (isset($_GET['lasthotwater']) && !empty($_GET['lasthotwater'])){
                        $lasthotwater=$_GET['lasthotwater'];
                        if (str_replace(' ','',$lasthotwater)==''){
                                if($debug == 1){
                                        echo "lasthotwater is not set: Default Value >>> 0\n";
                                }
                                $lasthotwater="0";
                        }
                }else{
                        if($debug == 1){
                                echo "lasthotwater to default >>> 0\n";//debug
                                }
                        $lasthotwater="0";
                }

//test lasthotwaterusage  (String to Int function) and ONLY a Number
if (is_numeric($lasthotwater))
{
        if($debug == 1){
                echo "lasthotwater Is Numeric \n";//debug
        }
}
else
{
        if($debug == 1){
                echo "lasthotwater Is NOT Numeric \n";//debug
        }
        $err=1;
        $lasthotwater="0";
}


if($debug == 1){
	echo "Params: lastColdWater=$lastcoldwater lastHotWater=$lasthotwater\n";
	}
	
// Set LastDayConso, LastDayConsoWithoutHeating, NumberDayWithoutHeating, LastPowerUsage
//
//
//
if ($err==0)
{
	// Get ConsoDay1CW, ConsoDay2CW... and ConsoDay1HW, ConsoDay2HW,...
	$sql_result=mysql_query("SELECT ConsoDay1CW, ConsoDay2CW, ConsoDay3CW, ConsoDay4CW, ConsoDay5CW, ConsoDay6CW, ConsoDay1HW, ConsoDay2HW, ConsoDay3HW, ConsoDay4HW, ConsoDay5HW, ConsoDay6HW
	FROM `MonChauffeEau` AS che
	WHERE che.`idMonChauffeEau` = 1
	LIMIT 1 ",$connection)	or exit("Sql Error".mysql_error());
	$sql_num=mysql_num_rows($sql_result);

	while($sql_row=mysql_fetch_array($sql_result))
	{
		$HWvalue[0]=$sql_row["ConsoDay1HW"];
		$HWvalue[1]=$sql_row["ConsoDay2HW"];
		$HWvalue[2]=$sql_row["ConsoDay3HW"];
		$HWvalue[3]=$sql_row["ConsoDay4HW"];
		$HWvalue[4]=$sql_row["ConsoDay5HW"];
		$HWvalue[5]=$sql_row["ConsoDay6HW"];
		$CWvalue[0]=$sql_row["ConsoDay1CW"];
		$CWvalue[1]=$sql_row["ConsoDay2CW"];
		$CWvalue[2]=$sql_row["ConsoDay3CW"];
		$CWvalue[3]=$sql_row["ConsoDay4CW"];
		$CWvalue[4]=$sql_row["ConsoDay5CW"];
		$CWvalue[5]=$sql_row["ConsoDay6CW"];
	}
	 mysql_free_result($sql_result);

	$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `ConsoDay1CW` = $lastcoldwater, `ConsoDay1HW` = $lasthotwater, `ConsoDay2CW` = $CWvalue[0], `ConsoDay3CW` = $CWvalue[1], `ConsoDay4CW` = $CWvalue[2], `ConsoDay5CW` = $CWvalue[3], `ConsoDay6CW` = $CWvalue[4], `ConsoDay7CW` = $CWvalue[5], `ConsoDay2HW` = $HWvalue[0], `ConsoDay3HW` = $HWvalue[1], `ConsoDay4HW` = $HWvalue[2], `ConsoDay5HW` = $HWvalue[3], `ConsoDay6HW` = $HWvalue[4], `ConsoDay7HW` = $HWvalue[5]  WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;
	",$connection)	or exit("Sql Error".mysql_error());
	echo "0"; // OK

	mysql_close();
}
else
{
	echo "1"; // NOK
}						
?>
