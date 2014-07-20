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

// Get Input Param GET method (For Config (1) :  CmdRelayOn, CmdRelayOff, CmdGetHotWater, WaterHeaterPower, HCEndTime, MCEStartScriptTime, Mode3WaterHeaterCapacity, Language
if (isset($_GET['Language']) && !empty($_GET['Language'])){
			$Language=$_GET['Language'];
			if (str_replace(' ','',$Language)==''){
				$err="2";
			}
			if (isset($_GET['CmdRelayOn']) && !empty($_GET['CmdRelayOn'])){
                        	$CmdRelayOn=$_GET['CmdRelayOn'];
                        	if (str_replace(' ','',$CmdRelayOn)==''){
                        	        $err="3";
                        	}
			}
			else
			{ // IF NOT SET OR =0 ERROR !
				$err="1";
			}
                        if (isset($_GET['CmdRelayOff']) && !empty($_GET['CmdRelayOff'])){
                                $CmdRelayOff=$_GET['CmdRelayOff'];
                                if (str_replace(' ','',$CmdRelayOff)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                $err="1";
                        }
                        if (isset($_GET['CmdGetHotWater']) && !empty($_GET['CmdGetHotWater'])){
                                $CmdGetHotWater=$_GET['CmdGetHotWater'];
                                if (str_replace(' ','',$CmdGetHotWater)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                $err="1";
                        }

			if (isset($_GET['CmdGetColdWater']) && !empty($_GET['CmdGetColdWater'])){
                                $CmdGetColdWater=$_GET['CmdGetColdWater'];
                                if (str_replace(' ','',$CmdGetColdWater)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                $err="1";
                        }


			if (isset($_GET['WaterHeaterPower']) && !empty($_GET['WaterHeaterPower'])){
                                $WaterHeaterPower=$_GET['WaterHeaterPower'];
                                if (str_replace(' ','',$WaterHeaterPower)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
																$WaterHeaterPower="0";
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

                        if (isset($_GET['HCEndTime']) && !empty($_GET['HCEndTime'])){
                                $HCEndTime=$_GET['HCEndTime'];
                                if (str_replace(' ','',$HCEndTime)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                $HCEndTime="0";
                        }


			if (isset($_GET['MCEStartScriptTime']) && !empty($_GET['MCEStartScriptTime'])){
                                $MCEStartScriptTime=$_GET['MCEStartScriptTime'];
                                if (str_replace(' ','',$MCEStartScriptTime)==''){
                                        $err="3";
                                }
                        }
                        else
                        { // IF NOT SET OR =0 ERROR !
                                $MCEStartScriptTime="0";
                        }


if($debug==1)
{
	echo "WHMode=3 [$CmdRelayOn,$CmdRelayOff,$CmdGetHotWater,$CmdGetColdWater,$WaterHeaterPower,$HCEndTime,$MCEStartScriptTime,$Mode3WaterHeaterCapacity,$Language, err=$err]";
}
// Set CmdRelayOn, CmdRelayOff, CmdRelayStatus, CmdGetHotWater, CmdGetHotWater, WaterHeaterPower,
//    HCEndTime, MCEStartScriptTime, Mode3WaterHeaterCapacity, Language
//
if ($err==0)
{
	$sql_result=mysql_query("UPDATE `MonChauffeEau` SET `CmdRelayOn` = '$CmdRelayOn', `CmdRelayOff` = '$CmdRelayOff', `CmdGetHotWater` = '$CmdGetHotWater', `CmdGetColdWater` = '$CmdGetColdWater', `Mode3WaterHeaterCapacity` = '$Mode3WaterHeaterCapacity', `WaterHeaterPower` = '$WaterHeaterPower', `HCEndTime` = '$HCEndTime', `MCEStartScriptTime` = '$MCEStartScriptTime', `Language` = '$Language' WHERE `MonChauffeEau`.`idMonChauffeEau` = 1;",$connection)	or exit("Sql Error".mysql_error());
}

}


// Get SELECT Water Heater Info
$sql_result=mysql_query("SELECT Mode, CmdRelayOn, CmdRelayOff, CmdGetHotWater, CmdGetColdWater, WaterHeaterPower, Mode0ModeReStartDate, Mode0ModeTransition, Mode2ModeTransition, Mode3WaterHeaterCapacity, LastDayConso, LastDaysConsoWithoutHeating, NumberDaysWithoutHeating, HCEndTime, MCEStartScriptTime, Language, ConsoDay1CW, ConsoDay1HW
FROM `MonChauffeEau` AS che
WHERE che.`idMonChauffeEau` = 1
LIMIT 1 ",$connection)	or exit("Sql Error".mysql_error());
$sql_num=mysql_num_rows($sql_result);

while($sql_row=mysql_fetch_array($sql_result))
{
	$WaterHeaterMode=$sql_row["Mode"];
	$CmdRelayOn=$sql_row["CmdRelayOn"];
	$CmdRelayOff=$sql_row["CmdRelayOff"];
	$CmdGetHotWater=$sql_row["CmdGetHotWater"];
	$CmdGetColdWater=$sql_row["CmdGetColdWater"];
	$WaterHeaterPower=$sql_row["WaterHeaterPower"];
	$Mode0ModeReStartDate=$sql_row["Mode0ModeReStartDate"];
  $Mode0ModeTransition=$sql_row["Mode0ModeTransition"];
  $Mode2ModeTransition=$sql_row["Mode2ModeTransition"];
	$Mode3WaterHeaterCapacity=$sql_row["Mode3WaterHeaterCapacity"];
	$LastDayConso=$sql_row["LastDayConso"];
	$LastDaysConsoWithoutHeating=$sql_row["LastDaysConsoWithoutHeating"];
	$NumberDaysWithoutHeating=$sql_row["NumberDaysWithoutHeating"];
	$HCEndTime=$sql_row["HCEndTime"];
	$MCEStartScriptTime=$sql_row["MCEStartScriptTime"];
	$Language=$sql_row["Language"];
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
	<title>MonChauffeEau - Config</title>
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

<?php echo $configval['CmdRelayOn'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"CmdRelayOn\" title=\"".$labelval['CmdRelayOn']."\">";
?>
<input maxlength="150" size="45" name="CmdRelayOn" value="<?php echo $CmdRelayOn; ?>">
</td></tr>

<tr>
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<?php echo $configval['CmdRelayOff'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"CmdRelayOff\" title=\"".$labelval['CmdRelayOff']."\">";
?>
<input maxlength="150" size="45" name="CmdRelayOff" value="<?php echo $CmdRelayOff; ?>">
</td></tr>

<tr>
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<?php echo $configval['CmdGetColdWater'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"CmdGetColdWater\" title=\"".$labelval['CmdGetColdWater']."\">";
?>
<input maxlength="150" size="45" name="CmdGetColdWater" value="<?php echo $CmdGetColdWater; ?>">
</td></tr>

<tr>
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<?php echo $configval['CmdGetHotWater'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"CmdGetHotWater\" title=\"".$labelval['CmdGetHotWater']."\">";
?>
<input maxlength="150" size="45" name="CmdGetHotWater" value="<?php echo $CmdGetHotWater; ?>">
</td></tr>

<tr>
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<?php echo $configval['WaterHeaterPower'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"WaterHeaterPower\" title=\"".$labelval['WaterHeaterPower']."\">";
?>
<input maxlength="4" size="3" name="WaterHeaterPower" value="<?php echo $WaterHeaterPower; ?>">
</td> </tr>

<tr>
<td style="text-align: right; width: 200px; font : 15px Batang, arial, serif;">
<?php echo $configval['Mode3WaterHeaterCapacity'];?>
</td><td style="width: 10px;"></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"Mode3WaterHeaterCapacity\" title=\"".$labelval['Mode3WaterHeaterCapacity']."\">";
?>
<input maxlength="3" size="3" name="Mode3WaterHeaterCapacity" value="<?php echo $Mode3WaterHeaterCapacity; ?>">
</td> </tr>

<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">
<?php echo $configval['HCEndTime'];?>
</td><td></td>
<td style="width: 309px;">
<?php
	echo "<label for=\"HCEndTime\" title=\"".$labelval['HCEndTime']."\">";
?>
<select size="1" name="HCEndTime">
	<option value="0" <?php if ($HCEndTime==0){ echo "selected=\"\"";} ?>>00h</option>
        <option value="1" <?php if ($HCEndTime==1){ echo "selected=\"\"";} ?>>01h</option>
	<option value="2" <?php if ($HCEndTime==2){ echo "selected=\"\"";} ?>>02h</option>
        <option value="3" <?php if ($HCEndTime==3){ echo "selected=\"\"";} ?>>03h</option>
        <option value="4" <?php if ($HCEndTime==4){ echo "selected=\"\"";} ?>>04h</option>
        <option value="5" <?php if ($HCEndTime==5){ echo "selected=\"\"";} ?>>05h</option>
        <option value="6" <?php if ($HCEndTime==6){ echo "selected=\"\"";} ?>>06h</option>
        <option value="7" <?php if ($HCEndTime==7){ echo "selected=\"\"";} ?>>07h</option>
        <option value="8" <?php if ($HCEndTime==8){ echo "selected=\"\"";} ?>>08h</option>
        <option value="9" <?php if ($HCEndTime==9){ echo "selected=\"\"";} ?>>09h</option>
        <option value="10" <?php if ($HCEndTime==10){ echo "selected=\"\"";} ?>>10h</option>
        <option value="11" <?php if ($HCEndTime==11){ echo "selected=\"\"";} ?>>11h</option>
        <option value="12" <?php if ($HCEndTime==12){ echo "selected=\"\"";} ?>>12h</option>
        <option value="13" <?php if ($HCEndTime==13){ echo "selected=\"\"";} ?>>13h</option>
        <option value="14" <?php if ($HCEndTime==14){ echo "selected=\"\"";} ?>>14h</option>
        <option value="15" <?php if ($HCEndTime==15){ echo "selected=\"\"";} ?>>15h</option>
        <option value="16" <?php if ($HCEndTime==16){ echo "selected=\"\"";} ?>>16h</option>
        <option value="17" <?php if ($HCEndTime==17){ echo "selected=\"\"";} ?>>17h</option>
        <option value="18" <?php if ($HCEndTime==18){ echo "selected=\"\"";} ?>>18h</option>
        <option value="19" <?php if ($HCEndTime==19){ echo "selected=\"\"";} ?>>19h</option>
        <option value="20" <?php if ($HCEndTime==20){ echo "selected=\"\"";} ?>>20h</option>
        <option value="21" <?php if ($HCEndTime==21){ echo "selected=\"\"";} ?>>21h</option>
        <option value="22" <?php if ($HCEndTime==22){ echo "selected=\"\"";} ?>>22h</option>
        <option value="23" <?php if ($HCEndTime==23){ echo "selected=\"\"";} ?>>23h</option>
</select>
</label>
</td>
</tr>

<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">
<?php echo $configval['MCEStartScriptTime'];?>
</td><td></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"MCEStartScriptTime\" title=\"".$labelval['MCEStartScriptTime']."\">";
?>
<select size="1" name="MCEStartScriptTime">
	<option value="0" <?php if ($MCEStartScriptTime==0){ echo "selected=\"\"";} ?>>00h</option>
        <option value="1" <?php if ($MCEStartScriptTime==1){ echo "selected=\"\"";} ?>>01h</option>
        <option value="2" <?php if ($MCEStartScriptTime==2){ echo "selected=\"\"";} ?>>02h</option>
        <option value="3" <?php if ($MCEStartScriptTime==3){ echo "selected=\"\"";} ?>>03h</option>
        <option value="4" <?php if ($MCEStartScriptTime==4){ echo "selected=\"\"";} ?>>04h</option>
        <option value="5" <?php if ($MCEStartScriptTime==5){ echo "selected=\"\"";} ?>>05h</option>
        <option value="6" <?php if ($MCEStartScriptTime==6){ echo "selected=\"\"";} ?>>06h</option>
        <option value="7" <?php if ($MCEStartScriptTime==7){ echo "selected=\"\"";} ?>>07h</option>
        <option value="8" <?php if ($MCEStartScriptTime==8){ echo "selected=\"\"";} ?>>08h</option>
        <option value="9" <?php if ($MCEStartScriptTime==9){ echo "selected=\"\"";} ?>>09h</option>
        <option value="10" <?php if ($MCEStartScriptTime==10){ echo "selected=\"\"";} ?>>10h</option>
        <option value="11" <?php if ($MCEStartScriptTime==11){ echo "selected=\"\"";} ?>>11h</option>
        <option value="12" <?php if ($MCEStartScriptTime==12){ echo "selected=\"\"";} ?>>12h</option>
        <option value="13" <?php if ($MCEStartScriptTime==13){ echo "selected=\"\"";} ?>>13h</option>
        <option value="14" <?php if ($MCEStartScriptTime==14){ echo "selected=\"\"";} ?>>14h</option>
        <option value="15" <?php if ($MCEStartScriptTime==15){ echo "selected=\"\"";} ?>>15h</option>
        <option value="16" <?php if ($MCEStartScriptTime==16){ echo "selected=\"\"";} ?>>16h</option>
        <option value="17" <?php if ($MCEStartScriptTime==17){ echo "selected=\"\"";} ?>>17h</option>
        <option value="18" <?php if ($MCEStartScriptTime==18){ echo "selected=\"\"";} ?>>18h</option>
        <option value="19" <?php if ($MCEStartScriptTime==19){ echo "selected=\"\"";} ?>>19h</option>
        <option value="20" <?php if ($MCEStartScriptTime==20){ echo "selected=\"\"";} ?>>20h</option>
        <option value="21" <?php if ($MCEStartScriptTime==21){ echo "selected=\"\"";} ?>>21h</option>
        <option value="22" <?php if ($MCEStartScriptTime==22){ echo "selected=\"\"";} ?>>22h</option>
        <option value="23" <?php if ($MCEStartScriptTime==23){ echo "selected=\"\"";} ?>>23h</option>
</select>
</label>
</td>
</tr>

<tr>
<td style="text-align: right; width: 140px; font : 15px Batang, arial, serif;">

<?php echo $configval['Language'];?>
</td><td></td>
<td style="width: 309px;">
<?php
        echo "<label for=\"Language\" title=\"".$labelval['Language']."\">";
?>
<select size="1" name="Language">
        <option value="1" <?php if ($Language==1){ echo "selected=\"\"";} ?>>English</option>
        <option value="2" <?php if ($Language!=1){ echo "selected=\"\"";} ?>>Fran&ccedil;ais</option>
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
