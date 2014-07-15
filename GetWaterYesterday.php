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
        $_GET['percent']= $argv[2];
        }



// Get Input Param GET method (Debug & Zone Number & daynumber)
if (isset($_GET['debug']) && !empty($_GET['debug'])){
                        $debug=1;
                        }

if($debug == 1)
        {
        echo "[Debug ON : Get Water Counter Value for 1 Day]\n";
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


$month=exec("date +%m -d yesterday");
$day=exec("date +%d -d yesterday");
$year=exec("date +%Y -d yesterday");
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
