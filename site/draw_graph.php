<?php
  
	require_once("../jpgraph/current/src/jpgraph.php");
  	require_once("../jpgraph/current/src/jpgraph_pie.php");
  	require_once("../jpgraph/current/src/jpgraph_pie3d.php");
  	require_once("../jpgraph/current/src/jpgraph_bar.php");
  	require_once("../jpgraph/current/src/jpgraph_line.php");
	

	if($_SESSION[userid]):

		$dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
    		
		mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");		
		
		$graph_details = $_SESSION["graph_details"];
		$ydata = $_SESSION["ydata"];	
		
		if($_SESSION["indicator"]=='BMI'):			
			draw_bmi($ydata,$graph_details);
		elseif($_SESSION["indicator"]=='WT'):		
			draw_weight($ydata,$graph_details);

		elseif($_SESSION["indicator"]=='BP'):
			draw_bp($ydata,$graph_details);
		else:

		endif;

		
	

	endif;

	function draw_bmi($actual,$graph_details){
		$arr_bmi_bound = get_bmi_bound();

		$arr_xlabel = $_SESSION["consult_date"];

		$arr_under = $arr_bmi_bound["Underweight"];
		$arr_normal = $arr_bmi_bound["Normal"];
		$arr_over = $arr_bmi_bound["Overweight"];
		$arr_obese = $arr_bmi_bound["Obese"];
		
		
		$w = 400;
		$h = 300;

		$graph = new Graph($w,$h);
		$graph->SetScale('intlin',0,50);
		
		$graph->SetMargin(40,40,40,60);
		$graph->title->Set($graph_details[0].' of '.get_px_name());

		$graph->xaxis->title->Set($graph_details[1]);
		$graph->yaxis->title->Set($graph_details[2]);

		$graph->xaxis->SetTickLabels($arr_xlabel);
		 
		

		$lineplot=new LinePlot($actual);
		$lineplot->SetColor( 'blue' );
		$lineplot->SetWeight( 2 );

		$lineplot->value->Show();

		$bmi_under = new LinePlot($arr_under);
		$bmi_under->SetColor('yellow');
		$bmi_under->SetWeight( 3 );
				

		$bmi_normal = new LinePlot($arr_normal);
		$bmi_normal->SetColor('green');
		$bmi_normal->SetWeight( 3 );

		$bmi_over = new LinePlot($arr_over);
		$bmi_over->SetColor('red');
		$bmi_over->SetWeight( 3 );				
		
		$lineplot->SetLegend('Actual BMI');
		$bmi_under->SetLegend('Normal');
		$bmi_normal->SetLegend('Overweight');
		$bmi_over->SetLegend('Obese');

		$graph->legend->SetLayout(LEGEND_HOR);
		$graph->legend->Pos(0.5,.99,'center','bottom');	

		$graph->Add($bmi_under);
		$graph->Add($bmi_normal);
		$graph->Add($bmi_over);
		$graph->Add($lineplot);
		$graph->Stroke();

	}
	
	function draw_weight($actual,$graph_details){

		$arr_pound = array();
		$arr_xlabel = $_SESSION["consult_date"];

		for($i=0;$i<count($actual);$i++){
			$pound = $actual[$i] * 2.2;
			array_push($arr_pound,$pound);
		}
	
		$w = 500;
		$h = 400;
//		print_r($arr_pound);
		$graph = new Graph($w,$h);
		
		$graph->SetScale('intlin',0,100);
		$graph->SetY2Scale('lin');

		$graph->SetMargin(40,70,40,60);

		$graph->title->Set($graph_details[0].' of '.get_px_name());

		$graph->xaxis->title->Set($graph_details[1]);
		$graph->yaxis->title->Set($graph_details[2]);
		$graph->y2axis->title->Set($graph_details[4]);

		$graph->xaxis->SetTickLabels($arr_xlabel);

		$lineplot=new LinePlot($actual);
		$lineplot2=new LinePlot($arr_pound);

		$lineplot->SetColor( 'blue' );
		$lineplot->SetWeight( 2 );

		$lineplot2->SetColor( 'red' );
		$lineplot2->SetWeight( 2 );

		$lineplot->value->Show();
		$lineplot2->value->Show();

		$graph->Add($lineplot);
		$graph->AddY2($lineplot2);		

		$graph->Stroke();

	}

	function draw_bp($actual,$graph_details){
		
		$w = 700;
		$h = 450;
		$arr_xlabel = array();
		$arr_diastolic = array();
		$arr_systolic = array();
		$arr_status = array();
		
		//print_r($actual);

		foreach($actual as $key=>$value){
			array_push($arr_xlabel,$value[0]);
			array_push($arr_diastolic,$value[1]);
			array_push($arr_systolic,$value[2]);
			array_push($arr_status,$value[3]);
		}

		$graph = new Graph($w,$h);
		$graph->SetScale('intlin');
		$graph->SetY2Scale('int',0,200);

		$graph->SetMargin(40,70,40,60);
		
		$graph->title->Set($graph_details[0].' of '.get_px_name());

		$graph->xaxis->title->Set($graph_details[1]);
		$graph->yaxis->title->Set($graph_details[2]);

		$graph->xaxis->SetTickLabels($arr_xlabel);

		$lineplot=new LinePlot($arr_diastolic);
		$lineplot2=new LinePlot($arr_systolic);

		$lineplot->SetColor( 'blue' );
		$lineplot->SetWeight( 2 );

		$lineplot2->SetColor( 'red' );
		$lineplot2->SetWeight( 2 );

		$lineplot->value->Show();
		$lineplot2->value->Show();

		$graph->Add($lineplot);
		$graph->AddY2($lineplot2);

		$lineplot->SetLegend('Diastolic');
		$lineplot2->SetLegend('Systolic');

		$graph->legend->SetLayout(LEGEND_HOR);
		$graph->legend->Pos(0.5,.99,'center','bottom');	

		$graph->Stroke();
	}

	
	function get_bmi_bound(){
		
		$arr_bound = array('Underweight'=>array(),'Normal'=>array(),'Overweight'=>array(),'Obese'=>array()); 		

		foreach($arr_bound as $key=>$value){

			$arr_content = array();

			for($i=0;$i<count($_SESSION["ydata"]);$i++){

			switch($key){				
				case 'Underweight':
					$index = 'Underweight';
					$bmi_value = '18.5';
					break;

				case 'Normal':
					$index = 'Normal';
					$bmi_value = '25';
					break;

				case 'Overweight':
					$index = 'Overweight';
					$bmi_value = '30';
					break;

				case 'Obese':
					$index = 'Obese';	
					$bmi_value = '';
					break;

				default:
				
					break;				
			}
			
				array_push($arr_content,$bmi_value);
			}

			$arr_bound[$index] = $arr_content;
		}

		return $arr_bound;
	}


	function get_px_name(){
		$q_patient = mysql_query("SELECT a.patient_lastname, a.patient_firstname FROM m_patient a, m_consult b WHERE b.consult_id='$_GET[consult_id]' AND a.patient_id=b.patient_id") or die("Cannot query 126".mysql_error());

		list($lname,$fname) = mysql_fetch_array($q_patient);

		return $fname.' '.$lname;
	}
?>