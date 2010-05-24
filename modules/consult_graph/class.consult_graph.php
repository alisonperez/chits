<?php

  include("../jpgraph/current/src/jpgraph.php");
  include("../jpgraph/current/src/jpgraph_pie.php");
  include("../jpgraph/current/src/jpgraph_pie3d.php");
  include("../jpgraph/current/src/jpgraph_bar.php");
  include("../jpgraph/current/src/jpgraph_line.php"); 
  
  class consult_graph extends module{
    
    function consult_graph(){
      $this->author = "darth_ali";
      $this->version = "0.1-".date("Y-m-d");
      $this->module = "consult_graph";
      $this->description = "CHITS - Patient-Consult Level Graphs";
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
      $g = new graph;
      echo 'ali';          
    }

  }

?>