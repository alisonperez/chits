<?php

class hematology extends module{


  function hematology(){
    $this->author = "darth_ali";
    $this->version = "0.1-".date("Y-m-d");
    $this->module = "hematology";
    $this->description = "CHITS Module - Hematology Lab Examination";      
  }
  
  // ---- STANDARD CHITS FUNCTIONS ---
  
  function init_deps(){
    module::set_dep($this->module,"module");
    module::set_dep($this->module,"lab");
  }
  
  function init_lang(){
  
  }
  
  function init_stats(){
  
  }
  
  function init_help(){
  
  }
  
  function init_menu(){
    if(func_num_args()>0):    
      $arg_list = func_get_args();
    endif;
    
    module::set_detail($this->description,$this->version,$this->author,$this->module);
  
  }
  
  
  function init_sql(){
    module::execsql("CREATE TABLE IF NOT EXISTS `m_consult_lab_hematology` (
      `consult_id` bigint(20) NOT NULL,`request_id` float NOT NULL,`patient_id` float NOT NULL,`date_lab_exam` date NOT NULL,
      `hemoglobin` text NOT NULL,`hematocrit` text NOT NULL,`rbc` text NOT NULL,`rbc_mcv` text NOT NULL,`rbc_mchc` text NOT NULL,
      `rbc_mch` text NOT NULL,`wbc` text NOT NULL,`wbc_polys` text NOT NULL,`wbc_lympho` text NOT NULL,`wbc_mxd` text NOT NULL,
      `wbc_mono` text NOT NULL,`wbc_eosin` text NOT NULL,`wbc_baso` text NOT NULL,`platelet` text NOT NULL,
      `reticulocytes` text NOT NULL,`esr` text NOT NULL,`clotting_time` text NOT NULL,`bleeding_time` text NOT NULL,
      `malaria` text NOT NULL,`slit_smear` text NOT NULL,`fbs` text NOT NULL,`blood_type` char(2) NOT NULL,
      `release_flag` text NOT NULL,`release_date` date NOT NULL,`user_id` float NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
  }
  
  
  function drop_sql(){
    module::execsql("DROP TABLE m_consult_lab_hematology");
  }
  
  
  //----- CUSTOM-BUILT FUNCTIONS -----
  
  function _consult_lab_hematology(){
    if(func_num_args()>0):
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
    endif;


    echo "<a name='hematology'>";
    echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=LABS&module=hematology&request_id=$_GET[request_id]&lab_id=HEM#hematology' method='POST' name='form_lab'>";
    
    echo "<table border='1'>";
    echo "<tr><td colspan='4'>HEMATOLOGY</td></tr>";
    echo "<tr><td>TEST</td><td>RESULT</td><td>TEST</td><td>RESULT</td></tr>";
    echo "</table>";
    echo "</form>";
  }




}


?>