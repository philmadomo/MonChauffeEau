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

// Get Input Param GET method (For DISABLED Mode (0) : WaterHeaterMode, Mode0ModeReStartDate, Mode0ModeTransition
#if (isset($_GET['WaterHeaterMode']) && !empty($_GET['WaterHeaterMode'])){
if (isset($_GET['WaterHeaterMode']) && !empty($_GET['WaterHeaterMode'])){
			$WaterHeaterMode=$_GET['WaterHeaterMode'];
			if (str_replace(' ','',$WaterHeaterMode)==''){
				$err="2";
			}
			if (isset($_GET['Mode2HeatingTime']) && !empty($_GET['Mode2HeatingTime'])){
                        	$Mode2HeatingTime=$_GET['Mode2HeatingTime'];
                        	if (str_replace(' ','',$Mode2HeatingTime)==''){
                        	        $err="3";
                        	}
			}
			else
			{
				// IF NOT SET OR =0 ERROR !
				$err="4";		
			}
			if (isset($_GET['Mode2ModeTransition']) && !empty($_GET['Mode2ModeTransition'])){
                                $Mode2ModeTransition=$_GET['Mode2ModeTransition'];
                                if (str_replace(' ','',$Mode2ModeTransition)==''){
                                        $err="3";
                                }
			}
			else
			{ // IF NOT SET OR =0 ERROR !
				//$err="4";
				$Mode2ModeTransition=0;
			}

if($debug==1)
{
	echo "WHMode=2 [$Mode2HeatingTime,$Mode2ModeTransition,err=$err]";
}
// Set WaterHeaterMode, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate,SummerModeHeatingTime in DB
//
if ($err==0)
{
	$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `Mode` = '$WaterHeaterMode', `Mode2HeatingTime` = '$Mode2HeatingTime', `Mode2ModeTransition` = '$Mode2ModeTransition' WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;",$connection)      or exit("Sql Error".mysql_error());

}
}

// Get SELECT Water Heater Info
$sql_result=mysql_query("SELECT Mode, Mode0ModeReStartDate, Mode0ModeTransition, Mode2HeatingTime, Mode2ModeTransition, LastDayConso, LastDaysConsoWithoutHeating, NumberDaysWithoutHeating, HCEndTime, MCEStartScriptTime, Language, LastPowerUsage, ConsoDay1CW, ConsoDay1HW
FROM `MonChauffeEau` AS che
WHERE che.`idMonChauffeEau` = 1
LIMIT 1 ",$connection)	or exit("Sql Error".mysql_error());
$sql_num=mysql_num_rows($sql_result);

while($sql_row=mysql_fetch_array($sql_result))
{
	$WaterHeaterMode=$sql_row["Mode"];
	$Mode0ModeReStartDate=$sql_row["Mode0ModeReStartDate"];
  $Mode0ModeTransition=$sql_row["Mode0ModeTransition"];
  $Mode2HeatingTime=$sql_row["Mode2HeatingTime"];
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
	<title>MonChauffeEau - Mode Forced</title>
	<link href="design.css" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="datepicker.css" /> 
	<script type="text/javascript" src="datepicker.js"></script>
</head>


<body>
<form method="get" action="#" name="ModeChange">
<table style="text-align: left;" border="0"
 cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>
      <?php if ($err==0){ include("header.php");}else{ echo "DB Connection ERROR OR PARAM ERROR [ERR=$err]";} ?>
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
<input name="WaterHeaterMode" value="2" type="hidden">
<?php echo $configval['Mode2HeatingTime'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"Mode2HeatingTime\" title=\"".$labelval['Mode2HeatingTime']."\">";
?>
<input maxlength="3" size="4" name="Mode2HeatingTime" value=<?php echo $Mode2HeatingTime; ?>>
</td> </tr>
</tbody>
<tbody>
<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">
<?php echo $configval['Mode2ModeTransition'];?>
</td><td></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"Mode2ModeTransition\" title=\"".$labelval['Mode2ModeTransition']."\">";
?>

<select size="1" name="Mode2ModeTransition">
	<option value="0" <?php if ($Mode2ModeTransition==0){ echo "selected=\"\"";} ?>>Disabled</option>
	<option value="1" <?php if ($Mode2ModeTransition==1){ echo "selected=\"\"";} ?>>Normal</option>
	<option value="3" <?php if ($Mode2ModeTransition==3){ echo "selected=\"\"";} ?>>ECO</option></select>
</label>

</td>
</tr>
<tr>
<td>
<BR>
</td>
</tr>
<tr><td></td><td></td>
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
