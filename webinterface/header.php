<?php
$WhMode['0']='Disabled';
$WhMode['1']='Normal';
$WhMode['2']='Forced';
$WhMode['3']='ECO';

echo "
<table style=\"text-align: left; width: 719px;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody>
          <tr>
            <td style=\"width: 230px;\"><img
 style=\"width: 205px; height: 295px;\"
 src=\"images/logoMCE.jpg\"></td>
            <td style=\"width: 441px;\">
            <table style=\"text-align: left; width: 100%;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tbody>
                <tr style=\"font : bold 31px Batang, arial, serif;\">
                  <td>Stats</td>
                </tr>
                <tr>
                  <td></td>
                  </tr>
                <tr style=\"font : 15px Batang, arial, serif;\">
                  <td>".$configval['WaterHeaterMode']."<B>".$WhMode[$WaterHeaterMode]."</B><br>".
$configval['ConsoDay1CW']."<B>".$ConsoDay1CW."</B> ".$configval['liter']."<br>".
$configval['ConsoDay1HW']."<B>".$ConsoDay1HW."</B> ".$configval['liter']."<br>";
if ($WaterHeaterMode == "3")
{
	echo 
	$configval['LastDaysConsoWithoutHeating']."<B>".$LastDaysConsoWithoutHeating."</B> ".$configval['liter']." / <B>".$NumberDaysWithoutHeating."</B> ".$configval['day']."<br><br>
";
}
else
{
	echo "<BR>";
}


if ($WaterHeaterMode == "1" || $WaterHeaterMode == "3")
{
	echo "<div style=\"text-align: left;\">&nbsp;<img style=\"width: 400px; height: 200px;\" src=\"images/dailygraph.jpg\"></div>";
}
else
{
	if ($WaterHeaterMode == "0")
	{
// WaterHeaterMode = Disabled
		if ($Mode0ModeTransition == "2")
        	{
			//echo "[0][2][$Mode2ModeTransition]";
			$firstbox=$WhMode[$WaterHeaterMode];
			$secbox=$WhMode[$Mode0ModeTransition];
			$thirdbox=$WhMode[$Mode2ModeTransition];
			$datewttime=explode(" ",$Mode0ModeReStartDate);
			$dateddmmyy=explode("-",$datewttime[0]);
			$datefirst=intval(mktime(0,0,0,$dateddmmyy[1],$dateddmmyy[2],$dateddmmyy[0]));
			$datesec=($datefirst+86400);
                        $datethird=($datesec+86400);
		}
		else
		{// Mode0Transition ECO or Normal
			//echo "[0][$Mode0ModeTransition]";
			$firstbox=$WhMode[$WaterHeaterMode];
                        $secbox=$WhMode[$Mode0ModeTransition];
                        $thirdbox="0";
			$datewttime=explode(" ",$Mode0ModeReStartDate);
                        $dateddmmyy=explode("-",$datewttime[0]);
                        
			$datefirst=mktime(0,0,0,$dateddmmyy[1],$dateddmmyy[2],$dateddmmyy[0]);
			$datesec=($datefirst+86400);
			//$datefirst=$dateddmmyy[0]."/".$dateddmmyy[1]."/".$dateddmmyy[2];
		}
	
	}else{
// WaterHeaterMode = Forced
		if ($Mode2ModeTransition == "0")
                {
                        //echo "[2][0][$Mode0ModeTransition]";
			$firstbox=$WhMode[$WaterHeaterMode];
                        $secbox=$WhMode[$Mode2ModeTransition];
                        $thirdbox=$WhMode[$Mode0ModeTransition];
			$datefirst=intval(mktime(0,0,0,date("m"),date("d"),date("Y")));
			$datesec=($datefirst+86400);
			$datethird=($datesec+86400);
		}
                else
                {// Mode2ModeTransition ECO or Normal
                        //echo "[2][$Mode2ModeTransition]";
			$firstbox=$WhMode[$WaterHeaterMode];
                        $secbox=$WhMode[$Mode2ModeTransition];
                        $thirdbox="0";
			$datefirst=intval(mktime(0,0,0,date("m"),date("d"),date("Y")));
                        $datesec=($datefirst+86400);
                }
	}	 
	echo "
	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody>
<tr><td style=\"width: 96px;\">
      <table style=\"text-align: left; background-color: black; width: 108px; height: 75px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody><tr style=\"color: white; font-weight: bold;\" align=\"center\">
            <td style=\"height: 30px; background-color: black;\">
";
	echo $firstbox;
	echo "
            </td></tr><tr align=\"center\"><td style=\"height: 37px; background-color: rgb(224, 224, 224);\">
          <small>(";

	//echo $datefirst;
	echo $configval['end']." : ".date("d/m",$datefirst);
	echo")</small>
          </td></tr></tbody></table>
      </td>
      <td style=\"width: 20px; text-align: center;\"><img style=\"width: 35px; height: 70px;\" alt=\"\" src=\"images/modetomode.jpg\"><br>
      </td>
      <td style=\"width: 96px;\"><table style=\"text-align: left; background-color: black; width: 108px; height: 75px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody><tr style=\"color: white; font-weight: bold;\" align=\"center\">
            <td style=\"height: 30px; background-color: black;\">
	";
	echo $secbox;
	echo "
            </td></tr><tr align=\"center\"><td style=\"height: 37px; background-color: rgb(224, 224, 224);\">
          <small>(";
	if ($thirdbox == "0"){echo $configval['start']." : ";}
	echo date("d/m",$datesec);
	echo ")</small></td></tr></tbody></table>
      </td>";
	if ($thirdbox == "0")
	{
		echo "
        	</tr></tbody></table>
        	";
	}
	else
	{
	echo "
      <td style=\"width: 20px;\"><img style=\"width: 35px; height: 70px;\" alt=\"\" src=\"images/modetomode.jpg\"></td>
      <td style=\"width: 96px;\"><table style=\"text-align: left; background-color: black; width: 108px; height: 75px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tbody><tr style=\"color: white; font-weight: bold;\" align=\"center\">
            <td style=\"height: 30px; background-color: black;\">
	";
	echo $thirdbox;
        echo "    
	</td></tr><tr align=\"center\"><td style=\"height: 37px; background-color: rgb(224, 224, 224);\">
            <small>(";
	echo $configval['start']." : ";
	echo date("d/m",$datethird);
	echo ")</small>
            </td>
	";
	echo "
	</tr></tbody></table></td></tr></tbody></table>
	";
	}	
}
echo "
                  </td>
                </tr>
              </tbody>
            </table>
            </td>
          </tr>
        </tbody>
      </table>
";

?>
