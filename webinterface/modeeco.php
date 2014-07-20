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

			if (isset($_GET['Mode3MaxDayWithoutHeating']) && !empty($_GET['Mode3MaxDayWithoutHeating'])){
                                $Mode3MaxDayWithoutHeating=$_GET['Mode3MaxDayWithoutHeating'];
                                if (str_replace(' ','',$Mode3MaxDayWithoutHeating)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
				$err="4";
                        }

			if (isset($_GET['Mode3WaterHeaterCapacity']) && !empty($_GET['Mode3WaterHeaterCapacity'])){
                                $Mode3WaterHeaterCapacity=$_GET['Mode3WaterHeaterCapacity'];
                                if (str_replace(' ','',$Mode3WaterHeaterCapacity)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
				$err="4";
                        }

			if (isset($_GET['Mode3WaterUsagePerCentWithoutHeating']) && !empty($_GET['Mode3WaterUsagePerCentWithoutHeating'])){
                                $Mode3WaterUsagePerCentWithoutHeating=$_GET['Mode3WaterUsagePerCentWithoutHeating'];
                                if (str_replace(' ','',$Mode3WaterUsagePerCentWithoutHeating)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                $err="4";
                        }

			if (isset($_GET['MinHeating']) && !empty($_GET['MinHeating'])){
                                $MinHeating=$_GET['MinHeating'];
                                if (str_replace(' ','',$MinHeating)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                //$err="4";
				$MinHeating="0";
                        }



if($debug==1)
{
	echo "WHMode=3 [$Mode1HeatingTime,$Mode3MaxDayWithoutHeating,$Mode3WaterHeaterCapacity,$Mode3WaterUsagePerCentWithoutHeating,$MinHeating,err=$err]";
}
// Set WaterHeaterMode, Mode1HeatingTime, Mode1SummerEnable, SummerModeStartDate, SummerModeEndDate,SummerModeHeatingTime, MinHeating in DB
//
if ($err==0)
{
	$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `Mode` = '$WaterHeaterMode', `Mode1HeatingTime` = '$Mode1HeatingTime', `Mode3MaxDayWithoutHeating` = '$Mode3MaxDayWithoutHeating', `Mode3WaterHeaterCapacity` = '$Mode3WaterHeaterCapacity', `Mode3WaterUsagePerCentWithoutHeating` = '$Mode3WaterUsagePerCentWithoutHeating', `MinHeating` = '$MinHeating' WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;",$connection)	or exit("Sql Error".mysql_error());
}

}


// Get SELECT Water Heater Info
$sql_result=mysql_query("SELECT Mode, Mode0ModeReStartDate, 
Mode0ModeTransition, Mode1HeatingTime, Mode2ModeTransition, Mode3MaxDayWithoutHeating, Mode3WaterHeaterCapacity, Mode3WaterUsagePerCentWithoutHeating, LastDayConso, LastDaysConsoWithoutHeating, NumberDaysWithoutHeating, Language, MinHeating, ConsoDay1CW, ConsoDay1HW
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
	$Mode2ModeTransition=$sql_row["Mode2ModeTransition"];
  $Mode3MaxDayWithoutHeating=$sql_row["Mode3MaxDayWithoutHeating"];
	$Mode3WaterHeaterCapacity=$sql_row["Mode3WaterHeaterCapacity"];
  $Mode3WaterUsagePerCentWithoutHeating=$sql_row["Mode3WaterUsagePerCentWithoutHeating"];
	$LastDayConso=$sql_row["LastDayConso"];
	$LastDaysConsoWithoutHeating=$sql_row["LastDaysConsoWithoutHeating"];
	$NumberDaysWithoutHeating=$sql_row["NumberDaysWithoutHeating"];
	$Language=$sql_row["Language"];
	$MinHeating=$sql_row["MinHeating"];
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
	<title>MonChauffeEau - Mode ECO</title>
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
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<input name="WaterHeaterMode" value="3" type="hidden">
<?php echo $configval['Mode1HeatingTime'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"Mode1HeatingTime\" title=\"".$labelval['Mode1HeatingTime']."\">";
?>
<input maxlength="3" size="3" name="Mode1HeatingTime" value=<?php echo $Mode1HeatingTime; ?>>
</td> </tr>
<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">

<?php echo $configval['Mode3MaxDayWithoutHeating'];?>
</td><td></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"Mode3MaxDayWithoutHeating\" title=\"".$labelval['Mode3MaxDayWithoutHeating']."\">";
?>
<select size="1" name="Mode3MaxDayWithoutHeating">
        <option value="1" <?php if ($Mode3MaxDayWithoutHeating==1){ echo "selected=\"\"";} ?>>1</option>
        <option value="2" <?php if ($Mode3MaxDayWithoutHeating==2){ echo "selected=\"\"";} ?>>2</option>
        <option value="3" <?php if ($Mode3MaxDayWithoutHeating==3){ echo "selected=\"\"";} ?>>3</option>
	<option value="4" <?php if ($Mode3MaxDayWithoutHeating==4){ echo "selected=\"\"";} ?>>4</option>
        <option value="5" <?php if ($Mode3MaxDayWithoutHeating==5){ echo "selected=\"\"";} ?>>5</option>
        <option value="6" <?php if ($Mode3MaxDayWithoutHeating==6){ echo "selected=\"\"";} ?>>6</option>
	<option value="7" <?php if ($Mode3MaxDayWithoutHeating==7){ echo "selected=\"\"";} ?>>7</option>
        <option value="8" <?php if ($Mode3MaxDayWithoutHeating==8){ echo "selected=\"\"";} ?>>8</option>
        <option value="9" <?php if ($Mode3MaxDayWithoutHeating==9){ echo "selected=\"\"";} ?>>9</option>
</select>
</label>
</td>
</tr>

<tr>
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<?php echo $configval['Mode3WaterHeaterCapacity'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"Mode3WaterHeaterCapacity\" title=\"".$labelval['Mode3WaterHeaterCapacity']."\">";
?>
<input maxlength="3" size="3" name="Mode3WaterHeaterCapacity" value=<?php echo $Mode3WaterHeaterCapacity; ?>>
</td> </tr>



<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">

<?php echo $configval['Mode3WaterUsagePerCentWithoutHeating'];?>
</td><td></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"Mode3WaterUsagePerCentWithoutHeating\" title=\"".$labelval['Mode3WaterUsagePerCentWithoutHeating']."\">";
?>
<select size="1" name="Mode3WaterUsagePerCentWithoutHeating">
        <option value="10" <?php if ($Mode3WaterUsagePerCentWithoutHeating==10){ echo "selected=\"\"";} ?>>10%</option>
        <option value="20" <?php if ($Mode3WaterUsagePerCentWithoutHeating==20){ echo "selected=\"\"";} ?>>20%</option>
        <option value="30" <?php if ($Mode3WaterUsagePerCentWithoutHeating==30){ echo "selected=\"\"";} ?>>30%</option>
        <option value="40" <?php if ($Mode3WaterUsagePerCentWithoutHeating==40){ echo "selected=\"\"";} ?>>40%</option>
        <option value="50" <?php if ($Mode3WaterUsagePerCentWithoutHeating==50){ echo "selected=\"\"";} ?>>50%</option>
        <option value="60" <?php if ($Mode3WaterUsagePerCentWithoutHeating==60){ echo "selected=\"\"";} ?>>60%</option>
        <option value="70" <?php if ($Mode3WaterUsagePerCentWithoutHeating==70){ echo "selected=\"\"";} ?>>70%</option>
        <option value="80" <?php if ($Mode3WaterUsagePerCentWithoutHeating==80){ echo "selected=\"\"";} ?>>80%</option>
        <option value="90" <?php if ($Mode3WaterUsagePerCentWithoutHeating==90){ echo "selected=\"\"";} ?>>90%</option>
</select>
</label>
</td>
</tr>


<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">

<?php echo $configval['MinHeating'];?>
</td><td></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"MinHeating\" title=\"".$labelval['MinHeating']."\">";
?>
<select size="1" name="MinHeating">
        <option value="0" <?php if ($MinHeating==0){ echo "selected=\"\"";} ?>>0</option>
        <option value="5" <?php if ($MinHeating==5){ echo "selected=\"\"";} ?>>5</option>
        <option value="10" <?php if ($MinHeating==10){ echo "selected=\"\"";} ?>>10</option>
        <option value="15" <?php if ($MinHeating==15){ echo "selected=\"\"";} ?>>15</option>
        <option value="20" <?php if ($MinHeating==20){ echo "selected=\"\"";} ?>>20</option>
</select>
</label>
</td>
</tr>


<tr>
<td>
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
