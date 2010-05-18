<?php


  class demographic_profile extends module{
    
    function demographic_profile(){
      $this->author = 'darth_ali';
      $this->module = "demographic_profile";
      $this->version = "0.1-".date("Y-m-d");
      $this->description = "CHITS Module - Demographics profile";    
    }
    
    
    function init_deps(){
      module::set_dep($this->module,"module");      
    }
    
    function init_lang(){
    
    }
    
    function init_stats(){
    
    }
  
  
  function init_menu() {
          // use this for updating menu system
          // under LIBRARIES
      if (func_num_args()>0) {
        $arg_list = func_get_args();
      }
          
          // _<modulename> in SQl refers to function _<modulename>() below
          // _demographic_profile in SQL refers to function _demographic_profile() below;
            
      module::set_menu($this->module, "Demographic Profile", "LIBRARIES", "_demographic_profile");
                                                                              
          // put in more details      
      module::set_detail($this->description, $this->version, $this->author, $this->module);
  }
                                                                                                              
        
    function init_help(){
    
    }
    
    function init_sql(){
      module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_demographic_profile` (
        `demographic_id` int(3) NOT NULL AUTO_INCREMENT,`year` year(4) NOT NULL,`barangay` int(10) NOT NULL,`bhs` int(11) NOT NULL,`doctors_male` int(3) NOT NULL,
        `doctors_female` int(3) NOT NULL,`dentist_male` int(3) NOT NULL,`dentist_female` int(3) NOT NULL,`nurse_male` int(3) NOT NULL,
        `nurse_female` int(3) NOT NULL,`midwife_male` int(3) NOT NULL,`midwife_female` int(3) NOT NULL,`nutritionist_male` int(3) NOT NULL,
        `nutritionist_female` int(3) NOT NULL,`medtech_male` int(3) NOT NULL,`medtech_female` int(3) NOT NULL,`se_male` int(3) NOT NULL,
        `se_female` int(3) NOT NULL,`si_male` int(3) NOT NULL,`si_female` int(3) NOT NULL,`bhw_male` int(3) NOT NULL,`bhw_female` int(3) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");                
    }
    
    function drop_tables(){
      module::execsql("DROP TABLE `m_lib_demographic_profile`");
    }
    
    // ----- CUSTOM MODULE FUNCTIONS -----
  
  
    function _demographic_profile(){
      if (func_num_args()>0) {
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];
      }

      //display dependency error
      
      if($exitinfo = $this->missing_dependencies('demographic_profile')):
        return print($exitinfo);
      endif;
      
      if($_POST["submitdemo"]):
        print_r($_POST);
        
        $q_demog = mysql_query("SELECT demographic_id FROM m_lib_demographic_profile WHERE year='$_POST[sel_year]'") or die("Cannot query 84 ".mysql_error());
        
        if(mysql_num_rows($q_demog)==0): //insert demographic record if year does not exist yet
          $insert_demog = mysql_query("INSERT INTO m_lib_demographic_profile SET ") or die("Cannot query 87".mysql_error());
        
        else:  //otherwise, update the existing demographic record for the year
        
        endif;
        
      endif;
      
      $this->form_demographic_profile();                                                                                                                                                                                                      
    }
    
    
    function form_demographic_profile(){
      echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]' method='POST' name='form_demo'>";
      echo "<table border='1'>";
      echo "<tr><td colspan='4'>DEMOGRAPHIC PROFILE</td></tr>";      
      echo "<tr><td colspan='4'>YEAR&nbsp;";
      echo "<select name='sel_year' size='1'>";
      
      for($i=(date('Y')-10);$i<(date('Y')+10);$i++){
        if($i==date('Y')):
          echo "<option value='$i' SELECTED>$i</option>";
        else:
         echo "<option value='$i'>$i</option>";
        endif;
      }
      
      echo "</select></td></tr>";
      echo "<tr><td>Indicators</td><td>Male</td><td>Female</td><td>Total</td></tr>";
      echo "<tr><td>Barangay</td><td></td><td></td><td><input type='text' name='txt_brgy_total' size='2' maxlength='3' /></td></tr>";
      echo "<tr><td>Barangay Health Stations</td><td></td><td></td><td><input type='text' name='txt_bhs_total' size='2' maxlength='3' /></td></tr>";
      echo "<tr><td>Doctors</td><td><input type='text' name='txt_doctor_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_doctor_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Dentist</td><td><input type='text' name='txt_dentist_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_dentist_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Nurses</td><td><input type='text' name='txt_nurse_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_nurse_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Midwives</td><td><input type='text' name='txt_midwife_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_midwife_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Nutritionist</td><td><input type='text' name='txt_nutritionist_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_nutritionist_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Medical Technologists</td><td><input type='text' name='txt_medtech_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_medtech_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Sanitary Engineers</td><td><input type='text' name='txt_se_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_se_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Sanitary Inspectors</td><td><input type='text' name='txt_si_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_si_f' size='2' maxlength='3' /></td><td></td></tr>";
      echo "<tr><td>Active BHW's</td><td><input type='text' name='txt_bhw_m' size='2' maxlength='3' /></td><td><input type='text' name='txt_bhw_f' size='2' maxlength='3' /></td><td></td></tr>";      
      
      echo "<tr><td colspan='4'><input type='submit' name='submitdemo' value='Update Demographic Profile' /></td></tr>";
      echo "</table>";
      echo "</form>";
    }
  }

?>