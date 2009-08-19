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
        // to use this with age groups, use report tables with age groups,
        // e.g., m_consult_report and use mysql_fetch_array to
        // get $key=>$value pairs:
        // 'M_LESS_1' => x
        // 'M_1to4' => y
        //    note: you can change the $key text by adding aliases
        //    to SQL field selections
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

$datay=array(12,8,19,3,10,5);
$datax = array("Jan", "Feb", "Mar", "Apr", "May", "Jun");

// Create the graph. These two calls are always required
$graph = new Graph(310,200,"auto");    
$graph->img->SetMargin(40,30,20,40);
$graph->SetScale("textlin");
$graph->SetShadow();

// Create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillColor("orange");
$bplot->SetWidth(1);
$graph->Add($bplot);

$graph->title->Set("Example 20");
$graph->xaxis->title->Set("X-title");
$graph->yaxis->title->Set("Y-title");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_FONT2);

// Display the graph
$graph->Stroke();
?>
