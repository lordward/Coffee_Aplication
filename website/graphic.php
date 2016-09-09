<?php 

/*Control de seguridad*/
session_start();
if ($_SESSION["autenticado"]==false){echo "<script>window.location='/';</script>";}

require_once ('src/jpgraph.php');
require_once ('src/jpgraph_line.php');

$graph = new Graph(800,200,'auto');
$graph->SetScale("textint");
$graph->title->Set("Evolucion de consumo de cafe");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->Set("");
$graph->xaxis->SetTickLabels($_SESSION["labels"]);
$graph->yaxis->title->Set("Numero de cafes (".$_SESSION["num_cafes"].')');

$p1 = new LinePlot($_SESSION["datay"]);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->value->Show();
$p1->mark->SetFillColor("red");
$p1->mark->SetWidth(2);
$p1->SetColor("black");
$p1->SetCenter();

$p2 = new LinePlot($_SESSION["euros"]);
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->value->Show();
$p2->mark->SetFillColor("red");
$p2->mark->SetWidth(2);
$p2->SetColor("red");
$p2->SetCenter();

$graph->Add($p1);
$graph->Add($p2);
$graph->Stroke();

?>  
