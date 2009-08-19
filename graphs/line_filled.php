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
include ("./jpgraph/jpgraph.php");
include ("./jpgraph/jpgraph_line.php");
include ("./jpgraph/jpgraph_error.php");
include ("./jpgraph/jpgraph_bar.php");

// get database connection
// this module is called "externally" from CHITS
include "../class.mysqldb.php";
$db = new MySQLDB;
$conn = $db->connid();
include "../modules/_dbselect.php";

// scroll through months of the year
for ($i = 1; $i <= 12; $i++) {
    if ($usercell) {
        $sql_consults = "select month(d.reportdate), count(d.events) from rawdata d where (d.events <> 'z1' and d.events <> 'Z1') and month(d.reportdate) = $i and year(d.reportdate) = year(sysdate()) and d.cellnumber = '$usercell' group by month(d.reportdate)";
    } else {
        $sql_events = "select month(d.reportdate), count(d.events) from rawdata d where (d.events <> 'z1' and d.events <> 'Z1') and month(d.reportdate) = $i and year(d.reportdate) = year(sysdate()) group by month(d.reportdate)";
    }
    if ($result_events = mysql_query($sql_events, $mysql_conn)) {
        if (mysql_num_rows($result_events)) {
            list($month, $events) = mysql_fetch_array($result_events);
            $l1datay[] = $events;
        } else {
            $l1datay[] = 0;
        }
    }

    if ($usercell) {
        $sql_total = "select month(d.reportdate), count(d.events) from rawdata d where month(d.reportdate) = $i and year(d.reportdate) = year(sysdate()) and d.cellnumber = '$usercell' group by month(d.reportdate)";
    } else {
        $sql_total = "select month(d.reportdate), count(d.events) from rawdata d where month(d.reportdate) = $i and year(d.reportdate) = year(sysdate()) group by month(d.reportdate)";
    }
    if ($result_total = mysql_query($sql_total, $mysql_conn)) {
        if (mysql_num_rows($result_total)) {
            list($month, $total) = mysql_fetch_array($result_total);
            $l2datay[] = $total;
            $datax[] = "$i";
        } else {
            $l2datay[] = 0;
            $datax[] = "$i";
        }
    }

}

// $l1datay = array(11,9,2,4,3,13,17);
// $l2datay = array(23,12,5,19,17,10,15);
// $datax=array("Jan","Feb","Mar","Apr","May");

// Create the graph.
$graph = new Graph(370,170,"auto");
$graph->img->SetMargin(40,130,20,40);
$graph->SetScale("textlin");
//$graph->SetShadow();

// Create the linear error plot
$l1plot=new LinePlot($l1datay);
$l1plot->SetColor("red");
$l1plot->SetWeight(3);
$l1plot->SetLegend("Critical Events");

// Create the bar plot
$l2plot = new LinePlot($l2datay);
$l2plot->SetFillColor("orange");
$l2plot->SetLegend("Reported Cases");

// Add the plots to the graph
$graph->Add($l2plot);
$graph->Add($l1plot);

$graph->title->Set("Event Profile Per Month, ".date("Y"));
$graph->xaxis->title->Set("Month");
$graph->yaxis->title->Set("Adverse Events");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->xaxis->SetTickLabels($datax);
//$graph->xaxis->SetTextTickInterval(2);

// Display the graph
$graph->Stroke();
?>
