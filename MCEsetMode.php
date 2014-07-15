<?php
// Set of the New Mode Value in Database
// and Output OK or NOK: 
//    0 // OK
//		or
//		1 // NOK
//
// For Debug Purpose: Set debug parameter to 1
//
//
require_once("inc/inc_bddcx.php");
$err=0;
$debug=0;

// Get Input Argv method
if (isset($argv[1])){
	$_GET['debug']= $argv[1];
	}

if (isset($argv[2])){
	$_GET['NewMode']= $argv[2];
	}

// Get Input Param GET method (Debug & exttemp & timestamp)
if (isset($_GET['debug']) && !empty($_GET['debug'])){
			$debug=1;
			}

if (isset($_GET['NewMode']) && !empty($_GET['NewMode'])){
			$NewMode=$_GET['NewMode'];
			if (str_replace(' ','',$NewMode)==''){
				if($debug == 1)
				{
					echo "NewMode is not set: Default Value > 0\n";
				}
				$NewMode="0";
				$err=1;
			}
		}else{
			if($debug == 1)
				{
				echo "NewMode to default > 0\n";//debug
				}
			$NewMode="0";
		}

//test NewMode and ONLY a Number
if (!is_numeric($NewMode))
{
	$err=1;
	$NewMode="0";
}else{// 0 =< NewMode <= 3
	if ( 3 < intval($NewMode) || 0 > intval($NewMode))
	{
		if($debug == 1)
		{
			echo "NewMode NOK";
		}
		$err=1;
	}
}


if($debug == 1)
	{
	echo "Params: NewMode=$NewMode err=$err";
	}

//UPDATE `MonChauffeEau`.`MonChauffeEau` SET `Mode` = '1' WHERE `MonChauffeEau`.`idMonChauffeEau` =1;
if ($err==0)
{
	$sql_result=mysql_query("UPDATE `MonChauffeEau`.`MonChauffeEau` SET `Mode` = $NewMode WHERE `MonChauffeEau`.`idMonChauffeEau` =1;",$connection)	or exit("Sql Error".mysql_error());
	mysql_close();
	echo "0"; // OK
}
else
{
	echo "1"; // NOK
}

?>
