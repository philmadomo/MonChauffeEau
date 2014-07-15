<?php
// GetWaterCount.php <debug> <date> <PerCentHotwater>
//
// Return INT or EX (Error)
//
// Warning this piece of PHP Script Only Work with Domogik 0.1
// and my 1wire plugin mod (DS2423 compatibility)
//
// Get the Water usage of 1 day (date in input) in the Domogik Mysql DB
// and return the HotWater usage
//
// On my install, I have only one Water Counter (for cold and hot water) , so to get the correct HotWater usage I have to calculate It:
// Like WaterUsage * PerCentHotwater / 100 -> HotWaterUsage
// For Example @Home 36% of the water usage is HotWater !
//
//
$serveur="localhost" ;
$login="root" ;
$base="domogik" ;
$pass = "philippe" ;    // $pass contient password compte root MySql.
$watercounterid="24";
$debug=0;
$err=0;

// Get Input Param argv method (Debug & Zone Number & daynumber)
if (isset($argv[1])){
        $_GET['debug']= $argv[1];
        }

if (isset($argv[2])){
        $_GET['day']= $argv[2];
        }

if (isset($argv[3])){
        $_GET['month']= $argv[3];
        }

if (isset($argv[4])){
        $_GET['year']= $argv[4];
        }

if (isset($argv[5])){
        $_GET['percent']= $argv[5];
        }



// Get Input Param GET method (Debug & Zone Number & daynumber)
if (isset($_GET['debug']) && !empty($_GET['debug'])){
                        $debug=1;
                        }

if($debug == 1)
        {
        echo "[Debug ON : Get Water Counter Value for 1 Day]\n";
        }



if (isset($_GET['day']) && !empty($_GET['day'])){
                        $day=$_GET['day'];
                        if (str_replace(' ','',$day)==''){
                                $err=1;
                                if($debug == 1)
                                {
                                        echo "No day\n";//debug
				}
				$err=1;
                                $day="0";
                        }
                }else{
                        $err=1;
                        if($debug == 1)
                                {
                                echo "NO DAY\n";//debug
                                }
                        $day="0";
                }

if (isset($_GET['month']) && !empty($_GET['month'])){
                        $month=$_GET['month'];
                        if (str_replace(' ','',$month)==''){
                                $err=1;
                                if($debug == 1)
                                {
                                        echo "No month\n";//debug
                                }
                                $err=1;
                                $month="0";
                        }
                }else{
                        $err=1;
                        if($debug == 1)
                                {
                                echo "NO MONTH\n";//debug
                                }
                        $month="0";
                }

if (isset($_GET['year']) && !empty($_GET['year'])){
                        $year=$_GET['year'];
                        if (str_replace(' ','',$year)==''){
                                $err=1;
                                if($debug == 1)
                                {
                                        echo "No year value\n";//debug
                                }
                                $err=1;
                                $year="2012";
                        }
                }else{
                        $err=1;
                        if($debug == 1)
                                {
                                echo "NO YEAR Value\n";//debug
                                }
                        $year="2012";
                }

if (isset($_GET['percent']) && !empty($_GET['percent'])){
                        $percent=$_GET['percent'];
                        if (str_replace(' ','',$percent)==''){
                                $err=1;
                                if($debug == 1)
                                {
                                        echo "No Percent Value\n";//debug
                                }
                                $err=1;
                                $percent="100";
                        }
                }else{
                        $err=1;
                        if($debug == 1)
                                {
                                echo "NO Percent Value\n";//debug
                                }
                        $percent="100";
                }

//test Day Number
if (is_numeric($day))
{
        if (31 < intval($day) || 0 > intval($day))
                {
                        $day="0";
			$err=2;
                }
}
else
{
        $day="0";
	$err=1;
}
#test Month Number
if (is_numeric($month))
{
        if (12 < intval($month) || 0 > intval($month))
                {
                        $month="0";
                        $err=2;
                }
}
else
{
        $month="0";
        $err=1;
}
#test Year Number
if (is_numeric($year))
{
        if (2012 < intval($year))
                {
                        $year="2012";
                        $err=2;
                }
}
else
{
        $year="2012";
        $err=1;
}

#test Percent Number
if (is_numeric($percent))
{
        if (100 < intval($percent) || 0 > intval($percent) )
                {
                        $percent="100";
                        $err=2;
                }
}
else
{
        $year="100";
        $err=1;
}


//$month="08";
//$day="15";
//$year="2012";
//$percent="100";

$timestampday = mktime(0,0,0,$month,($day+1),$year);
$timestampdaybefore = mktime(0,0,0,$month,$day,$year);


if($debug == 1)
        {
	echo "Error: $err ";
	echo "Day:$day ";
	echo "TS:$timestampday -> $timestampdaybefore\n";
	}

if($err == 0)
        {
	$connection = mysql_connect($serveur, $login, $pass) or die("Erreur de connexion au serveur MySql");
	mysql_select_db($base,$connection) or die("Erreur de connexion a la base de donnees $base");
	$table="core_device_stats";
		
	$sql_result=mysql_query("select SUM(value_num) from core_device_stats WHERE device_id = $watercounterid AND timestamp<$timestampday and timestamp>$timestampdaybefore",$connection) or exit("Sql Error".mysql_error());
	$sql_num=mysql_num_rows($sql_result);

	$row = mysql_fetch_row($sql_result);
	mysql_close();	
	if($debug == 1)
		{
		echo "HotWater: $row[0] * $percent/100\n";	
		//echo floatval($row[0])*intval($percent)/100;
		}
	//intval($percent)
	//echo "$row[0]";
	echo floatval($row[0])*intval($percent)/100;
	}
	else
	{
	echo "EE";
	}
?>
