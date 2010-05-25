<?php

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
      $this->arr_graph = array('BMI'=>array("Body Mass Index","Consults","BMI","line"),'BP'=>array("Blood Pressure","Consults","BP","line"),'WT'=>array("Weight","Consults","Weight","line"));
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
	
    }

    function form_graph(){
	echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]#graph' method='POST' name='form_graph'>";
	echo "<a name='graph'></a>";
	echo "<table>";
	echo "<tr><td>SELECT TYPE OF GRAPH AND PRESS VIEW TO DISPLAY</td></tr>";
	echo "<tr><td>";
	echo "<select name='sel_graph' value='1'>";
	echo "<option value=''>------</option>";
	foreach($this->arr_graph as $key=>$value){
		echo "<option value='$key'>$value[0]</option>";
	}
	echo "</select>&nbsp";
	echo "<input type='submit' value='View Graph' name='submit_graph'></input>";
	echo "</td></tr>";
	echo "</table>";
	echo "</form>";
    }

  }

?>