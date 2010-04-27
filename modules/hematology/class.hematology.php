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
    
    if($exitinfo=$this->missing_dependencies('hematology')):
      return print($exitinfo);
    endif;
    
    $h = new hematology;
    
    if($_POST["submitlab"]):                
      $q_request = mysql_query("SELECT request_id FROM m_consult_lab_urinalysis WHERE request_id='$_POST[request_id]'") or die("Cannot query 94:".mysql_error());
              
      if($_POST["release_flag"]==1):
        $release = 'Y';
        $release_date = date('Y-m-d H:i:s');
        $q_update_lab = mysql_query("UPDATE m_consult_lab SET request_done='$release',done_timestamp='$release_date',done_user_id='$_SESSION[userid]' WHERE request_id='$_POST[request_id]' AND lab_id='$_GET[lab_id]'") or die("Cannot query 99".mysql_error());
                                                                                                        
      else:
        $release = 'N';
        $release_date = '';                        
      endif;
                                                                                                                                          
      $pxid = healthcenter::get_patient_id($_GET[consult_id]);
      list($m,$d,$y) = explode('/',$_POST[hematology_date]);
      
      
      $date_lab_exam = $y.'-'.$m.'-'.$d;
      
      if(mysql_num_rows($q_request)!=0):
        
      else:
      
        $update_urinalysis = mysql_query("INSERT INTO m_consult_lab_hematology SET consult_id='$_GET[consult_id]',request_id='$_POST[request_id]',patient_id='$pxid',date_lab_exam='$date_lab_exam',hemoglobin='$_POST[txt_hemoglobin]',hematocrit='$_POST[txt_hemocrit]',rbc='$_POST[txt_rbc]',rbc_mcv='$_POST[txt_rbc]',rbc_mchc='$_POST[mchc]',wbc='$_POST[wbc]',wbc_polys='$_POST[txt_polys]',wbc_lympho='$_POST[txt_lympho]',wbc_mxd='$_POST[txt_mxd]',wbc_mono='$_POST[txt_mono]'") or die("Cannot query 102: ".mysql_error());
      
      endif;
                                                                                                                                                                                              
      print_r($_POST);
    endif;
    
    $h->form_consult_lab_hematology($menu_id,$post_vars,$get_vars);
  }
  
  function form_consult_lab_hematology(){
    if(func_num_args()>0):
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
    endif;

    $arr_blood_type = array("---","A","B","O","AB+");
    
    echo "<a name='hematology'>";
    echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=LABS&module=hematology&request_id=$_GET[request_id]&lab_id=HEM#hematology' method='POST' name='form_lab'>";
    
    echo "<table border='1'>";
    echo "<tr><td colspan='4'>HEMATOLOGY</td></tr>";
    echo "<tr><td colspan='4' class='boxtitle'>DATE EXAMINED &nbsp; <input type='text' name='hematology_date' size='8' maxlength='10' value='$date_lab_exam' class='tinylight'></input>&nbsp;";
    echo "<a href=\"javascript:show_calendar4('document.form_lab.hematology_date', document.form_lab.hematology_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a> ";
    echo "</td></tr>";
                      
    echo "<tr><td>TEST</td><td>RESULT</td><td>TEST</td><td>RESULT</td></tr>";
    echo "<tr><td>HEMOGLOBIN</td><td><input type='text' name='txt_hemoglobin' size='9'></input></td><td>PLATELET</td><td><input type='text' name='txt_platelet' size='9'></input></td></tr>";
    echo "<tr><td>HEMOCRIT</td><td><input type='text' name='txt_hemocrit' size='9'></input></td><td>RETICULOCYTES</td><td><input type='text' name='txt_reticulocytes' size='9'></input></td></tr>";
    echo "<tr><td>RBC</td><td><input type='text' name='txt_rbc' size='9'></input></td><td>ESR</td><td><input type='text' name='txt_esr' size='9'></input></td></tr>";
    echo "<tr><td>MCV</td><td><input type='text' name='txt_mcv' size='9'></input></td><td>CLOTING TIME</td><td><input type='text' name='txt_clot' size='9'></input></td></tr>";
    echo "<tr><td>MCHC</td><td><input type='text' name='txt_mchc' size='9'></input></td><td>BLEEDING TIME</td><td><input type='text' name='txt_bleeding' size='9'></input></td></tr>";
    echo "<tr><td>MCH</td><td><input type='text' name='txt_mch' size='9'></input></td><td>MALARIA</td><td><input type='text' name='txt_malaria' size='9'></input></td></tr>";
    echo "<tr><td>WBC</td><td><input type='text' name='txt_wbc' size='9'></input></td><td>SLIT SMEAR</td><td><input type='text' name='txt_slitsmear' size='9'></input></td></tr>";
    echo "<tr><td>POLYS</td><td><input type='text' name='txt_polys' size='9'></input></td><td>FBS</td><td><input type='text' name='txt_fbs' size='9'></input></td></tr>";
    echo "<tr><td>LYMPHO</td><td><input type='text' name='txt_lympho' size='9'></input></td><td>";
    
    echo "BLOOD TYPE";    
    echo "</td><td>";
    echo "<select name='sel_bloodtype' size='1'>";
    
    foreach($arr_blood_type as $key_type=>$label_type){
      echo "<option value='$key_type'>$label_type</option>";
    }
    
    echo "</select></td></tr>";
    echo "<tr><td>MXD</td><td><input type='text' name='txt_mxd' size='9'></input></td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td>MONO</td><td><input type='text' name='txt_mono' size='9'></input></td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td>EOSIN</td><td><input type='text' name='txt_eosin' size='9'></input></td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td>BASO</td><td><input type='text' name='txt_baso' size='9'></input></td><td colspan='2'>&nbsp;</td></tr>";
    
    echo "<tr valign='top'><td colspan='4'>";
    echo "<span class='boxtitle'>".LBL_RELEASE_FLAG."</span><br>";
            
    echo "<input type='checkbox' name='release_flag' value='1'/> ".INSTR_RELEASE_FLAG."<br />";
    echo "</td></tr>";      
    echo "<tr><td colspan='4' align='center'>";
    
    if ($get_vars["request_id"]) {                                                      
      print "<input type='hidden' name='request_id' value='".$get_vars["request_id"]."'>";
      
      if ($_SESSION["priv_update"]) {
        print "<input type='submit' value = 'Update Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'>&nbsp; ";
      }           

      print "<input type='reset' value = 'Clear Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";   
    }      

    echo "</td></tr>";            


    echo "</table>";
    echo "</form>";  
  }




}


?>