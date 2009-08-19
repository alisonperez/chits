<?php
//
// MODULE: bar_simple_vertical.php
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
// this module is called "externally" from CHITS
include "../class.mysqldb.php";
$db = new MySQLDB;
$conn = $db->connid();
include "../modules/_dbselect.php";

// Callback function for Y-scale
function yScaleCallback($aVal) {
    return number_format($aVal);
}

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
// Some data
//$datay=array(120567,134013,192000,87000);
//$datax = array("Jan", "Feb", "Mar", "Apr");

// Create the graph and setup the basic parameters
$graph = new Graph($graph_data["graph_width"],$graph_data["graph_height"]);
$graph->img->SetMargin(80,30,30,40);
$graph->SetScale("textint");
$graph->SetShadow();
$graph->SetFrame(false); // No border around the graph

// Add some grace to the top so that the scale doesn't
// end exactly at the max value.
// Since we are using integer scale the gace gets intervalled
// to adding integer values.
// For example grace 10 to 100 will add 1 to max, 101-200 adds 2
// and so on...
$graph->yaxis->scale->SetGrace(30);
$graph->yaxis->SetLabelFormatCallback('yScaleCallback');

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_FONT2);

// Setup graph title ands fonts
$graph->title->Set($graph_data["graph_title"]);
$graph->title->SetFont(FF_FONT2,FS_BOLD);
$graph->xaxis->title->Set($graph_data["graph_xlabel"]);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->Set($graph_data["graph_ylabel"]);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillColor($graph_data["graph_barcolor"]);
if ($graph_data["graph_orientation"]=="G") {
    $bplot->SetWidth(1);
} else {
    $bplot->SetWidth(0.7);
}
//$bplot->SetShadow();

// Setup the values that are displayed on top of each bar
$bplot->value->Show();
// Must use TTF fonts if we want text at an arbitrary angle
$bplot->value->SetFont(FF_FONT1,FS_BOLD);
//$bplot->value->SetAngle(45);
$bplot->value->SetFormat(' %0.0f');
// Black color for positive values and darkred for negative values
$bplot->value->SetColor("black","darkred");
$graph->Add($bplot);

// Finally stroke the graph
$graph->Stroke();

?>
