<?php

$debug=0;
function get_microtime(){   
list($tps_usec, $tps_sec) = explode(" ",microtime());   
return ((float)$tps_usec + (float)$tps_sec);   
}   

$tps_start = get_microtime();

	$err=0;
	require_once("inc/inc_bddcx.php");
	require_once("./menu.php");
	$menu = affiche_menu();

// Get Input Param GET method (For Normal Mode (1) : WaterHeaterMode, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate,SummerModeHeatingTime
if (isset($_GET['WaterHeaterMode']) && !empty($_GET['WaterHeaterMode'])){
			$WaterHeaterMode=$_GET['WaterHeaterMode'];
			if (str_replace(' ','',$WaterHeaterMode)==''){
				$err="2";
			}
			if (isset($_GET['Mode1HeatingTime']) && !empty($_GET['Mode1HeatingTime'])){
                        	$Mode1HeatingTime=$_GET['Mode1HeatingTime'];
                        	if (str_replace(' ','',$Mode1HeatingTime)==''){
                        	        $err="3";
                        	}
		}
		else
		{ // IF NOT SET OR =0 ERROR !
				//$err="4";
				$Mode1HeatingTime=0;
		}

			if (isset($_GET['Mode1SummerEnable']) && !empty($_GET['Mode1SummerEnable'])){
                        	$Mode1SummerEnable=$_GET['Mode1SummerEnable'];
                        	if (str_replace(' ','',$Mode1SummerEnable)==''){
                        	        $err="2";
                        	}
				else
				{
					if (isset($_GET['SummerModeStartDate']) && !empty($_GET['SummerModeStartDate'])){
						$SummerModeStartDate=$_GET['SummerModeStartDate'];
                        			if (str_replace(' ','',$SummerModeStartDate)==''){
                                			$err="2";
                        			}
					}
					else
					{
						$err="2";
					}
					if (isset($_GET['SummerModeEndDate']) && !empty($_GET['SummerModeEndDate'])){
                                                $SummerModeEndDate=$_GET['SummerModeEndDate'];
                                                if (str_replace(' ','',$SummerModeEndDate)==''){
                                                        $err="2";
                                                }
                                        }
                                        else
                                        {
                                                $err="2";
                                        }
					if (isset($_GET['SummerModeHeatingTime']) && !empty($_GET['SummerModeHeatingTime'])){
                                                $SummerModeHeatingTime=$_GET['SummerModeHeatingTime'];
                                                if (str_replace(' ','',$SummerModeHeatingTime)==''){
                                                        $err="2";
                                                }
                                        }
                                        else
                                        {
                                                $err="2";
                                        }
				}
			}
			else
			{ // Param Mode1SummerEnable NOT SET SO ->>> 0
				$Mode1SummerEnable=0;
			}
if($debug==1)
{
	echo "WHMode=1 [$Mode1HeatingTime,$Mode1SummerEnable,err=$err]";
	if ($Mode1SummerEnable == 1) { echo "[$SummerModeStartDate,$SummerModeEndDate,$SummerModeHeatingTime]"; }	
}
// Set WaterHeaterMode, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate,SummerModeHeatingTime in DB
//
if ($err==0)
{
	if ($Mode1SummerEnable == 1)
	{
		$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `Mode` = $WaterHeaterMode, `Mode1HeatingTime` = '$Mode1HeatingTime', `Mode1SummerEnable` = '$Mode1SummerEnable', `SummerModeStartDate` = '$SummerModeStartDate 00:00:00', `SummerModeEndDate` = '$SummerModeEndDate 00:00:00', `SummerModeHeatingTime` = '$SummerModeHeatingTime' WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;",$connection)	or exit("Sql Error".mysql_error());
}
	else
	{
	$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `Mode` = $WaterHeaterMode, `Mode1HeatingTime` = $Mode1HeatingTime, `Mode1SummerEnable` = $Mode1SummerEnable WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;",$connection)      or exit("Sql Error".mysql_error());
	}
}

}


// Get SELECT Water Heater Info
$sql_result=mysql_query("SELECT Mode, Mode0ModeReStartDate, Mode0ModeTransition, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate, SummerModeHeatingTime, Mode2ModeTransition, LastDayConso, LastDaysConsoWithoutHeating, NumberDaysWithoutHeating, HCEndTime, MCEStartScriptTime, Language, LastPowerUsage, ConsoDay1CW, ConsoDay1HW
FROM `MonChauffeEau` AS che
WHERE che.`idMonChauffeEau` = 1
LIMIT 1 ",$connection)	or exit("Sql Error".mysql_error());
$sql_num=mysql_num_rows($sql_result);

while($sql_row=mysql_fetch_array($sql_result))
{
	$WaterHeaterMode=$sql_row["Mode"];
	$Mode0ModeReStartDate=$sql_row["Mode0ModeReStartDate"];
  $Mode0ModeTransition=$sql_row["Mode0ModeTransition"];
	$Mode1HeatingTime=$sql_row["Mode1HeatingTime"];
	$Mode1SummerEnable=$sql_row["Mode1SummerEnable"];
  $SummerModeStartDate=$sql_row["SummerModeStartDate"];
	$SummerModeEndDate=$sql_row["SummerModeEndDate"];
	$SummerModeHeatingTime=$sql_row["SummerModeHeatingTime"];
	$Mode2ModeTransition=$sql_row["Mode2ModeTransition"];
	$LastDayConso=$sql_row["LastDayConso"];
	$LastDaysConsoWithoutHeating=$sql_row["LastDaysConsoWithoutHeating"];
	$NumberDaysWithoutHeating=$sql_row["NumberDaysWithoutHeating"];
	$HCEndTime=$sql_row["HCEndTime"];
	$MCEStartScriptTime=$sql_row["MCEStartScriptTime"];
	$Language=$sql_row["Language"];
	$LastPowerUsage=$sql_row["LastPowerUsage"];
	$ConsoDay1CW=$sql_row["ConsoDay1CW"];
	$ConsoDay1HW=$sql_row["ConsoDay1HW"];
}

// Test if the first output is ok
if (isset($WaterHeaterMode)){
        // OK
}
else
{ // Error: DB Connection
        $err=1;
}

if($Language == '1') {
                include 'lang_en.php';
        }
        else
        {
                include 'lang_fr.php';
        }

?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>MonChauffeEau - Mode Normal</title>
	<link href="design.css" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="datepicker.css" /> 
	<script type="text/javascript" src="datepicker.js"></script>
</head>

<SCRIPT LANGUAGE="JavaScript"><!--
function codename() {
if(document.ModeChange.Mode1SummerEnable.checked)
	{
	document.ModeChange.SummerModeStartDate.disabled=false;
	document.ModeChange.SummerModeEndDate.disabled=false;
	document.ModeChange.SummerModeHeatingTime.disabled=false;
	}
	else
	{
	document.ModeChange.SummerModeStartDate.disabled=true;
	document.ModeChange.SummerModeEndDate.disabled=true;
	document.ModeChange.SummerModeHeatingTime.disabled=true;
	}
}

function disablesummerdate() {
	document.ModeChange.SummerModeStartDate.disabled=true;
	document.ModeChange.SummerModeEndDate.disabled=true;
	document.ModeChange.SummerModeHeatingTime.disabled=true;
}
//-->
</SCRIPT>

<body <?php if ($Mode1SummerEnable==0){ echo "onload=\"disablesummerdate()\"";} ?>>
<form method="get" action="#" name="ModeChange">
<table style="text-align: left;" border="0"
 cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
      <?php if ($err==0){ include("header.php");}else{ echo "DB Connection ERROR";} ?>
      </td>
    </tr>
    <tr>
      <td>

<?php
    echo $menu;
?>

<table style="text-align: left; width: 600px;" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<th style="width: 160px; font : bold 21px Batang, arial, serif;"><br></th>
<td></td>
</tr>
<tr>
<td style="text-align: right; width: 160px; font : 15px Batang, arial, serif;">
<input name="WaterHeaterMode" value="1" type="hidden">
<?php echo $configval['Mode1HeatingTime'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"Mode1HeatingTime\" title=\"".$labelval['Mode1HeatingTime']."\">";
?>
<input maxlength="3" size="4" name="Mode1HeatingTime" value=<?php echo $Mode1HeatingTime; ?>>
</td> </tr>
</tbody>
<tbody>
<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">
<?php echo $configval['Mode1SummerEnable'];?>
</td><td></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"Mode1SummerEnable\" title=\"".$labelval['Mode1SummerEnable']."\">";
?>
<input name="Mode1SummerEnable" value="1" type="checkbox" onclick="codename()" <?php if ($Mode1SummerEnable==1){ echo "checked";} ?>></label></td>
</tr>
<tr>
<td>
</td><td></td>
<td>
<table border="0" cellpadding="0" cellspacing="0">
</tbody>
<tr>
<td style="text-align: right; width: 180px; font : 15px Batang, arial, serif;">
<?php echo $configval['SummerModeStartDate'];?>
</td><td style="width: 10px;"></td>
<td style="width: 140px;">
<?php
	echo "<label for=\"SummerModeStartDate\" title=\"".$labelval['SummerModeStartDate']."\">";
?>
<input maxlength="20" size="10" name="SummerModeStartDate" id="sumstart_dt" value=<?php $dateonly = explode(" ",$SummerModeStartDate); echo $dateonly[0]; ?> class='datepicker'></td>
</tr>
<tr>
<td style="text-align: right; width: 180px; font : 15px Batang, arial, serif;">
<?php echo $configval['SummerModeEndDate'];?>
</td><td style="width: 10px;"></td>
<td style="width: 140px;">
<?php
	echo "<label for=\"SummerModeEndDate\" title=\"".$labelval['SummerModeEndDate']."\">";
?>
<input maxlength="20" size="10" name="SummerModeEndDate" id="sumend_dt"  value=<?php $dateonly = explode(" ",$SummerModeEndDate); echo $dateonly[0]; ?> class='datepicker'></td>
</tr>
<tr>
<td style="text-align: right; width: 180px; font : 15px Batang, arial, serif;">
<?php echo $configval['SummerModeHeatingTime'];?>
</td><td style="width: 10px;"></td>
<td style="width: 140px;">
<?php
	echo "<label for=\"SummerModeHeatingTime\" title=\"".$labelval['SummerModeHeatingTime']."\">";
?>
<input maxlength="3" size="4" name="SummerModeHeatingTime" value=<?php echo $SummerModeHeatingTime; ?>></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td>
<BR>
</td>
</tr>
<tr>
<td>
</td><td></td>
<td style="width: 194px;">
<input name="save" value=<?php echo $configval['save']; ?> type="submit">
<input name="cancel" value=<?php echo $configval['cancel']; ?> type="reset"></td>
</tr>
</tbody>
</table>
<tr><td><BR></td></tr>

<?php
include 'mcefooter.php';
?>

</td>
    </tr>
  </tbody>
</table>
<br>

</body>
</html>
