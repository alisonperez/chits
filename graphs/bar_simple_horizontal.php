<?php
//
// MODULE: bar_simple_horizontal.php
// AUTHOR: Herman Tolentino MD
//
// DESCRIPTION:
// an abstracted bar graph drawing utility
// using JPGRAPH
// uses database-derived parameters
// distributed under GPL
//
// NOTE: Please be aware of JPGRAPH licensing model
// before distributing it with derived application
//
include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_bar.php");

// get database connection
// this module is called externally from CHITS
include "../class.mysqldb.php";
$db = new MySQLDB;
$conn = $db->connid();
include "../modules/_dbselect.php";
// GET SQL STATEMENT
// this internal module depends on class.graph.php
// and the presence of table m_lib_graph_piegraph
//
$sql_sql = "select graph_title, graph_width, graph_height, graph_xlabel, ".
           "graph_ylabel, graph_barcolor, graph_sql ".
           "from m_lib_graph_bargraph ".
           "where graph_id = '".$HTTP_GET_VARS["graph_id"]."'";
if ($result_sql = mysql_query($sql_sql)) {
    if (mysql_num_rows($result_sql)) {
        $graph_data = mysql_fetch_array($result_sql);
        //
        // RUN GRAPH SPECIFIC SQL STATEMENT
        // SQL statement must provide records with two fields:
        // $_1 = text based x axis
        // $_2 = numeric counts y axis
        //
        if ($result = mysql_query($graph_data["graph_sql"])) {
            if (mysql_num_rows($result)) {
                while (list($_1, $_2) = mysql_fetch_array($result)) {
                    $datax[] .= $_1;
                    $datay[] .= $_2;
                }
            }
        }
    }
}

//$datay=array(2,3,5,8,12,6,3);
//$datax=array("Jan","Feb","Mar","Apr","May","Jun","Jul");

// Set the basic parameters of the graph
$graph = new Graph($graph_data["graph_width"],$graph_data["graph_height"],'auto');
$graph->SetAngle(90);
$graph->SetScale("textlin");
$graph->img->SetMargin(140,10,80,100);
//$graph->SetShadow();

// Setup title
$graph->title->Set($graph_data["graph_title"]);
$graph->title->SetFont(FF_FONT2,FS_BOLD);
$year = date("Y");

// Setup X-axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL, 10);

// Some extra margin looks nicer
$graph->xaxis->SetLabelMargin(5);

// Label align for X-axis
$graph->xaxis->SetLabelAlign('right','center');

// Add some grace to y-axis so the bars doesn't go
// all the way to the end of the plot area
$graph->yaxis->scale->SetGrace(80);

// Setup the Y-axis to be displayed in the bottom of the
// graph. We also finetune the exact layout of the title,
// ticks and labels to make them look nice.
$graph->yaxis->SetPos('max');

// First make the labels look right
$graph->yaxis->SetLabelAlign('center','top');
$graph->yaxis->SetLabelFormat('%d');
$graph->yaxis->SetLabelSide(SIDE_RIGHT);

// The fix the tick marks
$graph->yaxis->SetTickSide(SIDE_LEFT);

// Finally setup the title
$graph->yaxis->SetTitleSide(SIDE_RIGHT);
$graph->yaxis->SetTitleMargin(35);

// To align the title to the right use :
$graph->yaxis->SetTitle($graph_data["graph_xlabel"],'high');
$graph->yaxis->title->Align('right');

// To center the title use :
//$graph->yaxis->SetTitle('Turnaround 2002','center');
//$graph->yaxis->title->Align('center');

$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetAngle(0);

$graph->yaxis->SetFont(FF_FONT2,FS_NORMAL, 10);
// If you want the labels at an angle other than 0 or 90
// you need to use TTF fonts
//$graph->yaxis->SetLabelAngle(0);

// Now create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillColor($graph_data["graph_barcolor"]);
//$bplot->SetShadow();

//You can change the width of the bars if you like
$bplot->SetWidth(0.7);

// We want to display the value of each bar at the top
$bplot->value->Show();
$bplot->value->SetFont(FF_FONT1,FS_NORMAL,20);
$bplot->value->SetAlign('left','center');
$bplot->value->SetColor("black","darkred");
//$bplot->value->SetFormat('%.1f');

// Add the bar to the graph
$graph->Add($bplot);
$graph->Stroke();

?>
