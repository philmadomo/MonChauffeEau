<?php
// Set the info : LastDayConso, LastDaysConsoWithoutHeating, NumberDaysWithoutHeating, LastPowerUsage
// in WaterHeater Database
// and Output OK or NOK: 
//    1 // OK
//		or
//		0 // NOK
//
// For Debug Purpose: Set debug parameter to 1
// 
//
require_once("inc/inc_bddcx.php");
$err=0;
$debug=0;

// Get Param argv method
if (isset($argv[1])){
	$_GET['debug']= $argv[1];
	}

if (isset($argv[2])){
	$_GET['ldc']= $argv[2];
	}

if (isset($argv[3])){
	$_GET['ldcwh']= $argv[3];
	}

if (isset($argv[4])){
        $_GET['ndwh']= $argv[4];
        }

if (isset($argv[5])){
        $_GET['lpus']= $argv[5];
        }

// Get Input Param GET method (debug , ldc, ldcwh, ndwh)

if (isset($_GET['debug']) && !empty($_GET['debug'])){
			$debug=1;
			}

///////////////// LDC

if (isset($_GET['ldc']) && !empty($_GET['ldc'])){
			$ldc=$_GET['ldc'];
			if (str_replace(' ','',$ldc)==''){
				if($debug == 1){
					echo "ldc is not set: Default Value >>> 0\n";
				}
				$ldc="0";
			}
		}else{
			if($debug == 1){
				echo "ldc to default >>> 0\n";//debug
				}
			$ldc="0";
		}

//test lastdayconso  (String to Int function) and ONLY a Number
if (is_numeric($ldc))
{
	if($debug == 1){
		echo "ldc Is Numeric \n";//debug
	}
}
else
{
	if($debug == 1){
                echo "ldc Is NOT Numeric \n";//debug
        } 
	$err=1;
	$ldc="0";
}

///////////// LDC Without Heating

if (isset($_GET['ldcwh']) && !empty($_GET['ldcwh'])){
                        $ldcwh=$_GET['ldcwh'];
                        if (str_replace(' ','',$ldcwh)==''){
                                if($debug == 1){
                                        echo "ldcwh is not set: Default Value >>> 0\n";
                                }
                                $ldcwh="0";
                        }
                }else{
                        if($debug == 1){
                                echo "ldcwh to default >>> 0\n";//debug
                                }
                        $ldcwh="0";
                }

//test lastdayconsoWithoutHeating  (String to Int function) and ONLY a Number
if (is_numeric($ldcwh))
{
        if($debug == 1){
                echo "ldcwh Is Numeric \n";//debug
        }
}
else
{
        if($debug == 1){
                echo "ldcwh Is NOT Numeric \n";//debug
        }
        $err=1;
        $ldcwh="0";
}

/////////////// NumberDaysWithoutHeating
if (isset($_GET['ndwh']) && !empty($_GET['ndwh'])){
                        $ndwh=$_GET['ndwh'];
                        if (str_replace(' ','',$ndwh)==''){
                                if($debug == 1){
                                        echo "ndwh is not set: Default Value >>> 0\n";
                                }
                                $ndwh="0";
                        }
                }else{
                        if($debug == 1){
                                echo "ndwh to default >>> 0\n";//debug
                                }
                        $ndwh="0";
                }

//test NumberDaysWithoutHeating  (String to Int function) and ONLY a Number
if (is_numeric($ndwh))
{
        if($debug == 1){
                echo "ndwh Is Numeric \n";//debug
        }
}
else
{
        if($debug == 1){
                echo "ndwh Is NOT Numeric \n";//debug
        }
        $err=1;
        $ndwh="0";
}

/////////////// LastPowerUsage
if (isset($_GET['lpus']) && !empty($_GET['lpus'])){
                        $lpus=$_GET['lpus'];
                        if (str_replace(' ','',$lpus)==''){
                                if($debug == 1){
                                        echo "lpus is not set: Default Value >>> 0\n";
                                }
                                $lpus="0";
                        }
                }else{
                        if($debug == 1){
                                echo "lpus to default >>> 0\n";//debug
                                }
                        $lpus="0";
                }

//test LastPowerUsage
if (is_numeric($lpus))
{
        if($debug == 1){
                echo "lpus Is Numeric \n";//debug
        }
}
else
{
        if($debug == 1){
                echo "lpus Is NOT Numeric \n";//debug
        }
        $err=1;
        $lpus="0";
}



if($debug == 1){
	echo "Params: LastDayConso=$ldc LastDayConsoWithoutHeating=$ldcwh NumberDayWithoutHeating=$ndwh LastPowerUsage=$lpus\n";
	}
	
// Set LastDayConso, LastDayConsoWithoutHeating, NumberDayWithoutHeating, LastPowerUsage
//
//
//
if ($err==0)
{
	$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `LastDayConso` = $ldc, `LastDaysConsoWithoutHeating` = $ldcwh, `NumberDaysWithoutHeating` = $ndwh, `LastPowerUsage` = $lpus WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;
	",$connection)	or exit("Sql Error".mysql_error());
	mysql_close();
	echo "0"; // OK
}
else
{
	echo "1"; // NOK
}						
?>
