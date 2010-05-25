<?php
  
	require_once("../jpgraph/current/src/jpgraph.php");
  	require_once("../jpgraph/current/src/jpgraph_pie.php");
  	require_once("../jpgraph/current/src/jpgraph_pie3d.php");
  	require_once("../jpgraph/current/src/jpgraph_bar.php");
  	require_once("../jpgraph/current/src/jpgraph_line.php");

	if($_SESSION[userid]):
		
		$graph_details = $_SESSION["graph_details"];
		$ydata = $_SESSION["ydata"];	

		$w = 400;
		$h = 200;

		$graph = new Graph($w,$h);
		$graph->SetScale('intlin');
		
		$graph->SetMargin(40,20,20,40);
		$graph->title->Set($graph_details[0]);

		$graph->xaxis->title->Set($graph_details[1]);
		$graph->yaxis->title->Set($graph_details[2]);

		$lineplot=new LinePlot($ydata);
		$lineplot->SetColor( 'blue' );
		$lineplot->SetWeight( 2 );

		$lineplot->value->Show();

		$graph->Add($lineplot);
		
		$graph->Stroke();

	endif;

?>