<?php
//
// MODULE: pie_simple.php
// AUTHOR: Herman Tolentino MD
//
// DESCRIPTION:
// an abstracted line graph drawing utility
// using JPGRAPH
// uses database-derived parameters
// distributed under GPL
//
// NOTE: Please be aware of JPGRAPH licensing model
// before distributing it with derived application
//
include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_line.php");
include ("../jpgraph/jpgraph_error.php");
include ("../jpgraph/jpgraph_bar.php");

// get database connection
// this module is called "externally" from CHITS
include "../class.mysqldb.php";
$db = new MySQLDB;
$conn = $db->connid();
include "../modules/_dbselect.php";
// GET SQL STATEMENT
// this internal module depends on class.graph.php
// and the presence of table m_lib_graph_piegraph
//
$sql_sql = "select graph_title, graph_width, graph_height, graph_xlabel, ".
           "graph_y1label, graph_y2label, graph_barcolor, graph_sql, graph_type ".
           "from m_lib_graph_linegraph ".
           "where graph_id = '".$HTTP_GET_VARS["view_id"]."'";
if ($result_sql = mysql_query($sql_sql)) {
    if (mysql_num_rows($result_sql)) {
        $graph_data = mysql_fetch_array($result_sql);
        //print_r($graph_data);
        // RUN GRAPH SPECIFIC SQL STATEMENT
        // SQL statement must provide records with two fields:
        // $_1 = text based x axis
        // $_2 = numeric counts y axis
        //
        if ($result = mysql_query($graph_data["graph_sql"])) {
            if (mysql_num_rows($result)) {
                while (list($_x, $_y1, $_y2) = mysql_fetch_array($result)) {
                    $datax[] .= $_x;
                    $data1y[] .= $_y1;
                    $data2y[] .= $_y2;
                }
            }
        }
    }
}

//$data1y = array(11,9,2,4,3,13,17);
//$data2y = array(23,12,5,19,17,10,15);
//$datax=array("Jan","Feb","Mar","Apr","May");

// Create the graph.
$graph = new Graph($graph_data["graph_width"],$graph_data["graph_height"],"auto");
$graph->img->SetMargin(40,130,20,40);
$graph->SetScale("textlin");
//$graph->SetShadow();

// Create the linear error plot
$l1plot=new LinePlot($data1y);
$l1plot->SetColor("red");
$l1plot->SetWeight(3);
$l1plot->SetLegend($graph_data["graph_y1label"]);

// Create the bar plot
$l2plot = new LinePlot($data2y);
$l2plot->SetFillColor($graph_data["graph_barcolor"]);
$l2plot->SetLegend($graph_data["graph_y2label"]);

// Add the plots to the graph
$graph->Add($l2plot);
$graph->Add($l1plot);

$graph->title->Set($graph_data["graph_title"]);
$graph->xaxis->title->Set($graph_data["graph_xlabel"]);
$graph->yaxis->title->Set($graph_data["graph_y2label"]);

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->xaxis->SetTickLabels($datax);
//$graph->xaxis->SetTextTickInterval(2);

// Display the graph
$graph->Stroke();

?>
