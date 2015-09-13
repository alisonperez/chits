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
        `demographic_id` int(3) NOT NULL AUTO_INCREMENT,`year` year(4) NOT NULL,`barangay` int(10) NOT NULL,`bhs` int(11) NOT NULL,`household` int(10) NOT NULL,`doctors_male` int(3) NOT NULL,
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
      
      if($_POST["submitdemo"]=='Update Demographic Profile'):
        //print_r($_POST);
        
        $q_demog = mysql_query("SELECT demographic_id FROM m_lib_demographic_profile WHERE year='$_POST[sel_year]'") or die("Cannot query 84 ".mysql_error());
        
        if(mysql_num_rows($q_demog)==0): //insert demographic record if year does not exist yet
          $insert_demog = mysql_query("INSERT INTO m_lib_demographic_profile SET year='$_POST[sel_year]',barangay='$_POST[txt_brgy_total]',bhs='$_POST[txt_bhs_total]',doctors_male='$_POST[txt_doctor_m]',doctors_female='$_POST[txt_doctor_f]',dentist_male='$_POST[txt_dentist_m]',dentist_female='$_POST[txt_dentist_f]',nurse_male='$_POST[txt_nurse_m]',nurse_female='$_POST[txt_nurse_f]',midwife_male='$_POST[txt_midwife_m]',midwife_female='$_POST[txt_midwife_f]',nutritionist_male='$_POST[txt_nutritionist_m]',nutritionist_female='$_POST[txt_nutritionist_f]',medtech_male='$_POST[txt_medtech_m]',medtech_female='$_POST[txt_medtech_f]',se_male='$_POST[txt_se_m]',se_female='$_POST[txt_se_f]',si_male='$_POST[txt_si_m]',si_female='$_POST[txt_si_f]',bhw_male='$_POST[txt_bhw_m]',bhw_female='$_POST[txt_bhw_f]'") or die("Cannot query 87".mysql_error());        
        else:  //otherwise, update the existing demographic record for the year
          $insert_demog = mysql_query("UPDATE m_lib_demographic_profile SET barangay='$_POST[txt_brgy_total]',bhs='$_POST[txt_bhs_total]',doctors_male='$_POST[txt_doctor_m]',doctors_female='$_POST[txt_doctor_f]',dentist_male='$_POST[txt_dentist_m]',dentist_female='$_POST[txt_dentist_f]',nurse_male='$_POST[txt_nurse_m]',nurse_female='$_POST[txt_nurse_f]',midwife_male='$_POST[txt_midwife_m]',midwife_female='$_POST[txt_midwife_f]',nutritionist_male='$_POST[txt_nutritionist_m]',nutritionist_female='$_POST[txt_nutritionist_f]',medtech_male='$_POST[txt_medtech_m]',medtech_female='$_POST[txt_medtech_f]',se_male='$_POST[txt_se_m]',se_female='$_POST[txt_se_f]',si_male='$_POST[txt_si_m]',si_female='$_POST[txt_si_f]',bhw_male='$_POST[txt_bhw_m]',bhw_female='$_POST[txt_bhw_f]' WHERE year='$_POST[sel_year]'") or die("Cannot query 89".mysql_error());                  
        endif;
        
        echo "<script language='Javascript'>";
        
        if($insert_demog):
          echo "window.alert('Demographic profile for year $_POST[sel_year] was successfully been updated!')";
        else:
          echo "window.alert('Demographic profile for year $_POST[sel_year] was not updated!')";
        endif;
        
        echo "</script>";
        
      
      elseif($_POST["submitdemo"]=='View Year'):
        $_SESSION[sel_year] = $_POST[sel_year];
      
      else:        
        
        
      endif;
      
      $this->form_demographic_profile();                                                                                                                                                                                                      
    }
    
    
    function form_demographic_profile(){
    
      $year = (empty($_SESSION[sel_year])?date('Y'):$_SESSION[sel_year]);
      
      $q_demog = mysql_query("SELECT demographic_id,year,barangay,bhs,doctors_male,doctors_female,dentist_male,dentist_female,nurse_male,nurse_female,midwife_male,midwife_female,nutritionist_male,nutritionist_female,medtech_male,medtech_female,se_male,se_female,si_male,si_female,bhw_male,bhw_female FROM m_lib_demographic_profile WHERE year='$year'") or die("Cannot query 113".mysql_error());
      
      if(mysql_num_rows($q_demog)!=0):
        list($demog_id,$year,$brgy,$bhs,$md_m,$md_f,$dentist_m,$dentist_f,$nurse_m,$nurse_f,$mw_m,$mw_f,$nutri_m,$nutri_f,$medtech_m,$medtech_f,$se_m,$se_f,$si_m,$si_f,$bhw_m,$bhw_f) = mysql_fetch_array($q_demog);
      else:
        echo "<script language='Javascript'>";
        echo "window.alert('No demographic profile data for $year')";
        echo "</script>";
      endif;
      
      echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]#tbl_demog' method='POST' name='form_demo'>";
      echo "<table style='border: 1px solid #000000'>";
      echo "<a name='tbl_demog'></a>";
      echo "<tr align='center'><td colspan='4'><span class='library'>DEMOGRAPHIC PROFILE</span></td></tr>";      
      echo "<tr><td colspan='4' class='boxtitle'>YEAR&nbsp;";
      echo "<select name='sel_year' size='1'>";
      
      for($i=(date('Y')-10);$i<(date('Y')+10);$i++){
        if($i==$year):
          echo "<option value='$i' SELECTED>$i</option>";
        else:
         echo "<option value='$i'>$i</option>";
        endif;
      }
      
      echo "</select>&nbsp;<input type='submit' value='View Year' name='submitdemo' style='border: 1px solid #000000' /></td></tr>";
      echo "<tr align='center'><td><b>INDICATORS</b></td><td><b>MALE</b></td><td><b>FEMALE</b></td><td><b>TOTAL</b></td></tr>";
      echo "<tr><td class='boxtitle'>BARANGAY</td><td></td><td></td><td><input type='text' name='txt_brgy_total' size='2' maxlength='3' value='$brgy' /></td></tr>";
      echo "<tr><td class='boxtitle'>BARANGAY HEALTH STATIONS</td><td></td><td></td><td><input type='text' name='txt_bhs_total' size='2' maxlength='3' value='$bhs' /></td></tr>";
      //echo "<tr><td class='boxtitle'>HOUSEHOLDS</td><td></td><td></td><td><input type='text' name='txt_bhs_total' size='2' maxlength='3' value='$bhs' /></td></tr>";
      echo "<tr><td class='boxtitle'>DOCTORS</td><td><input type='text' name='txt_doctor_m' size='2' maxlength='3' value='$md_m' /></td><td><input type='text' name='txt_doctor_f' size='2' maxlength='3' value='$md_f' /></td><td>";
      echo $md_m + $md_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>DENTIST</td><td><input type='text' name='txt_dentist_m' size='2' maxlength='3' value='$dentist_m' /></td><td><input type='text' name='txt_dentist_f' size='2' maxlength='3' value='$dentist_f' /></td><td>";
      echo $dentist_m + $dentist_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>NURSES</td><td><input type='text' name='txt_nurse_m' size='2' maxlength='3' value='$nurse_m' /></td><td><input type='text' name='txt_nurse_f' size='2' maxlength='3' value='$nurse_f' /></td><td>";
      echo $nurse_m + $nurse_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>MIDWIVES</td><td><input type='text' name='txt_midwife_m' size='2' maxlength='3' value='$mw_m' /></td><td><input type='text' name='txt_midwife_f' size='2' maxlength='3' value='$mw_f' /></td><td>";
      echo $mw_m + $mw_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>NUTRITIONIST</td><td><input type='text' name='txt_nutritionist_m' size='2' maxlength='3' value='$nutri_m' /></td><td><input type='text' name='txt_nutritionist_f' size='2' maxlength='3' value='$nutri_f' /></td><td>";
      echo $nutri_m + $nutri_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>MEDICAL TECHNOLOGISTS</td><td><input type='text' name='txt_medtech_m' size='2' maxlength='3' value='$medtech_m' /></td><td><input type='text' name='txt_medtech_f' size='2' maxlength='3' value='$medtech_f' /></td><td>";
      echo $medtech_m + $medtech_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>SANITARY ENGINEERS</td><td><input type='text' name='txt_se_m' size='2' maxlength='3' value='$se_m' /></td><td><input type='text' name='txt_se_f' size='2' maxlength='3' value='$se_f' /></td><td>";
      echo $se_m + $se_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>SANITARY INSPECTORS</td><td><input type='text' name='txt_si_m' size='2' maxlength='3' value='$si_m' /></td><td><input type='text' name='txt_si_f' size='2' maxlength='3' value='$si_f' /></td><td>";
      echo $si_m + $si_f;
      echo "</td></tr>";
      echo "<tr><td class='boxtitle'>ACTIVE BHW's</td><td><input type='text' name='txt_bhw_m' size='2' maxlength='3' value='$bhw_m' /></td><td><input type='text' name='txt_bhw_f' size='2' maxlength='3' value='$bhw_f' /></td><td>";
      echo $bhw_m + $bhw_f;
      echo "</td></tr>";      
      
      echo "<tr align='center'><td colspan='4'><input type='submit' name='submitdemo' value='Update Demographic Profile' style='border: 1px solid #000000' /></td></tr>";
      echo "</table>";
      echo "</form>";
    }
  }

?>