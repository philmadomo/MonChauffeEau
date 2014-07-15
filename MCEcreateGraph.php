<?php
//Librairies JpGraph
require_once ("/usr/share/jpgraph/jpgraph.php");
require_once ("/usr/share/jpgraph/jpgraph_bar.php");
require_once ("/usr/share/jpgraph/jpgraph_line.php");

require_once("inc/inc_bddcx.php");

setlocale (LC_ALL, "fr_FR") ;

$nbjours = 7 ;// nb jours.
$xlabel = "jours" ;
$periodesecondes = 24*3600 ;	// Periode en secondes.
$timestampheure = mktime(0,0,0,date("m"),date("d"),date("Y"));	// Timestamp courant.
$timestampdebut = $timestampheure - $periodesecondes ;// Recule de $periodesecondes.
$dateformatsql = "%a %e" ;
//printf("datedebut : %s to %s", date("YmdHis", $timestampheure), date("YmdHis", $timestampdebut)) ;

// Get SELECT Water Heater Info
$sql_result=mysql_query("SELECT Language, ConsoDay1CW, ConsoDay2CW, ConsoDay3CW, ConsoDay4CW, ConsoDay5CW, ConsoDay6CW, ConsoDay7CW, ConsoDay1HW, ConsoDay2HW, ConsoDay3HW, ConsoDay4HW, ConsoDay5HW, ConsoDay6HW, ConsoDay7HW
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
	$HWvalue[6]=$sql_row["ConsoDay7HW"];
	$CWvalue[0]=$sql_row["ConsoDay1CW"];
	$CWvalue[1]=$sql_row["ConsoDay2CW"];
	$CWvalue[2]=$sql_row["ConsoDay3CW"];
	$CWvalue[3]=$sql_row["ConsoDay4CW"];
	$CWvalue[4]=$sql_row["ConsoDay5CW"];
	$CWvalue[5]=$sql_row["ConsoDay6CW"];
	$CWvalue[6]=$sql_row["ConsoDay7CW"];
	$Language=$sql_row["Language"];
}
mysql_free_result($sql_result) ;
mysql_close() ;

for ($i = ($nbjours-1); $i >= 0; $i--) {
	$datemj[$i]=date("d/m",$timestampdebut-($periodesecondes*$i));
	//printf("Date: %s\n",$datemj[$i]);
}



$graph = new Graph(400,200);
$graph->SetMargin(40,20,20,50);
$graph->SetMarginColor('black');
$graph->SetColor('gray1');
$graph->SetScale("textlin");


// Specify X-labels
$graph->xaxis->SetTickLabels($datemj);
$graph->xaxis->title->SetColor("black");
$graph->xaxis->SetColor("black","black");
$graph->yaxis->title->SetColor("black");
$graph->yaxis->SetColor("black","black");
 
$graph->ygrid->SetColor("gray5");
$graph->ygrid->Show(true, true) ;
$graph->xgrid->SetColor("gray5");
$graph->xgrid->Show(true);
 
// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.05,0.97,"left","bottom");
 
// Create the bar plots
$b1plot = new BarPlot($HWvalue);
$b1plot->SetFillColor("red");
$b1plot->SetShadow();
$b1plot->SetColor("black");
//$b1plot->SetValueMargin(25);
//$b1plot->ShowValue();
 
$b2plot = new BarPlot($CWvalue);
$b2plot->SetFillColor("green");
$b2plot->SetShadow();
$b2plot->SetColor("black");


if ($Language == 1)
{
	$b2plot->SetLegend("Cold Water");
	$b1plot->SetLegend("Hot Water");
}
else
{
	$b2plot->SetLegend("Eau Froide");
        $b1plot->SetLegend("Eau Chaude");
}
//$b2plot->ShowValue();

 
// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot)) ;
$gbplot = new AccBarPlot(array($b2plot,$b1plot)) ;
$gbplot->SetWidth(0.85);
$gbplot->SetColor("black");
//$gbplot->ShowValue();
 
// ...and add it to the graPH
$graph->Add($gbplot);
if ($Language == 1)
{
	$graph->title->Set("Water Usage History");
}
else
{
	$graph->title->Set("Historique de la consommation");
}
$graph->title->SetColor("black");
$graph->xaxis->title->Set("Date");
if ($Language == 1)
{
	$graph->yaxis->title->Set("Liter");
}
else
{
	$graph->yaxis->title->Set("Litre");
}

 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
//$graph->xaxis->title->SetMargin(25); 

// Display the graph
//$graph->Stroke("/var/www/MonChauffeEau/images/dailygraph.jpg");
$graph->Stroke("/tmp/dailygraph.jpg");
echo "0"; //OK
?>
