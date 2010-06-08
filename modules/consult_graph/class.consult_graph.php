<?php
   session_start();
/*  require_once("../jpgraph/current/src/jpgraph.php");
  require_once("../jpgraph/current/src/jpgraph_pie.php");
  require_once("../jpgraph/current/src/jpgraph_pie3.php");
  require_once("../jpgraph/current/src/jpgraph_bar.php");
  require_once("../jpgraph/current/src/jpgraph_line.php"); */
  
  class consult_graph extends module{
    
    function consult_graph(){
      $this->author = "darth_ali";
      $this->version = "0.1-".date("Y-m-d");
      $this->module = "consult_graph";
      $this->description = "CHITS - Patient-Consult Level Graphs";
      $this->arr_graph = array('BMI'=>array("Body Mass Index","Consults","BMI","line"),'BP'=>array("Blood Pressure","Consults","Blood Pressure","line"),'WT'=>array("Weight","Consults","Weight (kg)","line","Weight (lb)"));
    } 
    
    function init_deps(){
      module::set_dep($this->module,"module");
      module::set_dep($this->module,"patient");
      module::set_dep($this->module,"healthcenter");
    }
    
    function init_lang(){
    
    
    }
    
    function init_stats(){
    
    }
    
    function init_help(){
    
    }
    
    function init_menu(){      
      module::set_detail($this->description,$this->version,$this->author,$this->module);
    }
    
    function init_sql(){
    
    }
    
    function drop_tables(){
    
    
    }
    
    // ---- CUSTOM-BUILT FUNCTIONS --- //
    
    function _graph_form(){
	$pxid = healthcenter::get_patient_id($_GET["consult_id"]);
    	$this->form_graph();

	if($_POST["submit_graph"]):
		$_SESSION["graph_details"] = $this->arr_graph[$_POST["sel_graph"]];
		$_SESSION["indicator"] = $_POST["sel_graph"];
		unset($_SESSION["ydata"]);
		$this->process_graph($_POST["sel_graph"],$pxid);


		echo "<img src='../site/draw_graph.php?consult_id=$_GET[consult_id]' alt=''></img><br>";
	endif;
	
    }

    function form_graph(){
	echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]#graph' method='POST' name='form_graph'>";
	echo "<a name='graph'></a>";
	echo "<table>";
	echo "<tr><td>SELECT TYPE OF GRAPH AND PRESS VIEW TO DISPLAY</td></tr>";
	echo "<tr><td>";
	echo "<select name='sel_graph' value='1'>";
	echo "<option value=''>Select graph to view</option>";
	foreach($this->arr_graph as $key=>$value){
		echo "<option value='$key'>$value[0]</option>";
	}
	echo "</select>&nbsp";
	echo "<input type='submit' value='View Graph' name='submit_graph'></input>";
	echo "</td></tr>";
	echo "</table>";
	echo "</form>";
    }

    function process_graph($graph_type,$pxid){
	switch($graph_type){
		
		case 'BMI':			
			$_SESSION["ydata"] = $this->get_bmi($pxid);
			break;
		
		case 'BP':
			$_SESSION["ydata"] = $this->get_bp($pxid);
			break;
		case 'WT':
			$_SESSION["ydata"] = $this->get_weight($pxid);
			break;

		default:
			break;
	}
    }


    function get_bmi($pxid){

	$arr_bmi = array();
	$arr_date = array();
	$q_bmi = mysql_query("SELECT a.vitals_height,a.vitals_weight,date_format(b.consult_date,'%m/%d/%Y') as 'consult_date' FROM m_consult_vitals a, m_consult b WHERE vitals_height!=0 AND vitals_weight!=0 AND a.patient_id='$pxid' AND a.consult_id=b.consult_id ORDER by b.consult_date ASC") or die("Cannot query 106".mysql_error());

	array_push($arr_bmi,0);
	array_push($arr_date,0);
	
	if(mysql_num_rows($q_bmi)!=0):
			
		while(list($ht,$wt,$consult_date)=mysql_fetch_array($q_bmi)){
			$bmi = round($wt / pow(($ht/100),2),2);
			array_push($arr_date,$consult_date);
			array_push($arr_bmi,$bmi);
		}
	
	endif;
	
	$_SESSION["consult_date"] = $arr_date;
	return $arr_bmi;
    }

    function get_weight($pxid){
	$arr_date = array();
	$arr_wt = array();

	$q_wt = mysql_query("SELECT a.vitals_weight, date_format(b.consult_date,'%m/%d/%Y') as 'consult_date' FROM m_consult_vitals a, m_consult b WHERE vitals_height!=0 AND vitals_weight!=0 AND a.patient_id='$pxid' AND a.consult_id=b.consult_id ORDER by b.consult_date ASC") or die("Cannot query 136 ".mysql_error());

	
            array_push($arr_date,0);
            array_push($arr_wt,0);

	while(list($wt,$consult_date)=mysql_fetch_array($q_wt)){	
		array_push($arr_date,$consult_date);
		array_push($arr_wt,$wt);
	}
	
	$_SESSION["consult_date"] = $arr_date;

	return $arr_wt;


    }

    function get_bp($pxid){
	$arr_bp = array();

	$q_bp = mysql_query("SELECT a.vitals_systolic, a.vitals_diastolic,date_format(b.consult_date,'%m/%d/%Y') as 'consult_date',a.consult_id FROM m_consult_vitals a, m_consult b WHERE a.vitals_systolic!=0 AND a.vitals_diastolic!=0 AND a.consult_id = b.consult_id AND a.patient_id='$pxid' ORDER by b.consult_date ASC") or die("Cannot query 154 ".mysql_error());

			array_push($arr_bp,array(0,0,0,0));
	if(mysql_num_rows($q_bp)!=0):
		while(list($systolic,$diastolic,$consult_date,$consult_id)=mysql_fetch_array($q_bp)){
			$arr_bp_details = array();
			$edad = healthcenter::get_patient_age($consult_id);
			$bp_stage = healthcenter::hypertension_code($systolic,$diastolic,$edad);
			array_push($arr_bp_details,$consult_date,$systolic,$diastolic,$bp_stage);
			array_push($arr_bp,$arr_bp_details);
		}
		
	endif;
	return $arr_bp;
    }
  }

?>