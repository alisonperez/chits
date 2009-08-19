<?php
//
// MODULE: pie_simple.php
// AUTHOR: Herman Tolentino MD
//
// DESCRIPTION:
// an abstracted pie graph drawing utility
// using JPGRAPH
// uses database-derived parameters
// distributed under GPL
//
// NOTE: Please be aware of JPGRAPH licensing model
// before distributing it with derived application
//
include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_pie.php");

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
$sql_sql = "select graph_title, graph_width, graph_height, graph_sql ".
           "from m_lib_graph_piegraph ".
           "where graph_id = '".$HTTP_GET_VARS["graph_id"]."'";
if ($result_sql = mysql_query($sql_sql)) {
    if (mysql_num_rows($result_sql)) {
        list($title, $width, $height, $graph_sql) = mysql_fetch_array($result_sql);
        //
        // RUN GRAPH SPECIFIC SQL STATEMENT
        // SQL statement must provide records with two fields:
        // $_1 = text based legend
        // $_2 = numeric counts
        //
        if ($result = mysql_query($graph_sql)) {
            if (mysql_num_rows($result)) {
                while (list($_1, $_2) = mysql_fetch_array($result)) {
                    $legend[] .= $_1;
                    $data[] .= $_2;
                }
            }
        }
    }
}

// THIS IS STANDARD JPGRAPH ROUTINE
// OBTAINED FROM EXAMPLES
// Create the Pie Graph.

$graph = new PieGraph($width,$height,"auto");
//$graph->SetShadow();

// Set A title for the plot
$graph->title->Set($title);
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Create
$p1 = new PiePlot($data);
$p1->SetLegends($legend);
$graph->Add($p1);
$graph->Stroke();

?>
