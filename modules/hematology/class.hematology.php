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
    
    //print_r($);
      
    $h = new hematology;
    
    if($_POST["submitlab"]):                
      $q_request = mysql_query("SELECT request_id FROM m_consult_lab_hematology WHERE request_id='$_POST[request_id]'") or die("Cannot query 94:".mysql_error());
              
      if($_POST["release_flag"]==1):
        $release = 'Y';
        $release_date = date('Y-m-d H:i:s');
        $q_update_lab = mysql_query("UPDATE m_consult_lab SET request_done='$release',done_timestamp='$release_date',done_user_id='$_SESSION[userid]' WHERE request_id='$_POST[request_id]'") or die("Cannot query 99".mysql_error());
                                                                                                        
      else:
        $release = 'N';
        $release_date = '';                        
      endif;
                                                                                                                                          
      $pxid = healthcenter::get_patient_id($_GET[consult_id]);
      list($m,$d,$y) = explode('/',$_POST[hematology_date]);
      
      
      $date_lab_exam = $y.'-'.$m.'-'.$d;
      
      
            
      if(mysql_num_rows($q_request)!=0):
          $update_hematology = mysql_query("UPDATE m_consult_lab_hematology SET consult_id='$_GET[consult_id]',request_id='$_POST[request_id]',patient_id='$pxid',date_lab_exam='$date_lab_exam',hemoglobin='$_POST[txt_hemoglobin]',hematocrit='$_POST[txt_hemocrit]',rbc='$_POST[txt_rbc]',rbc_mcv='$_POST[txt_mcv]',rbc_mchc='$_POST[txt_mchc]',rbc_mch='$_POST[txt_mch]',wbc='$_POST[txt_wbc]',wbc_polys='$_POST[txt_polys]',wbc_lympho='$_POST[txt_lympho]',wbc_mxd='$_POST[txt_mxd]',wbc_mono='$_POST[txt_mono]',wbc_eosin='$_POST[txt_eosin]',wbc_baso='$_POST[txt_baso]',platelet='$_POST[txt_platelet]',reticulocytes='$_POST[txt_reticulocytes]',esr='$_POST[txt_esr]',clotting_time='$_POST[txt_clot]',bleeding_time='$_POST[txt_bleeding]',malaria='$_POST[txt_malaria]',slit_smear='$_POST[txt_slit_smear]',fbs='$_POST[txt_fbs]',blood_type='$_POST[sel_bloodtype]',release_flag='$release',release_date='$release_date',user_id='$_SESSION[userid]'") or die("Cannot query 99: ".mysql_error());
      else:      
          $update_hematology = mysql_query("INSERT INTO m_consult_lab_hematology SET consult_id='$_GET[consult_id]',request_id='$_POST[request_id]',patient_id='$pxid',date_lab_exam='$date_lab_exam',hemoglobin='$_POST[txt_hemoglobin]',hematocrit='$_POST[txt_hemocrit]',rbc='$_POST[txt_rbc]',rbc_mcv='$_POST[txt_mcv]',rbc_mchc='$_POST[txt_mchc]',rbc_mch='$_POST[txt_mch]',wbc='$_POST[txt_wbc]',wbc_polys='$_POST[txt_polys]',wbc_lympho='$_POST[txt_lympho]',wbc_mxd='$_POST[txt_mxd]',wbc_mono='$_POST[txt_mono]',wbc_eosin='$_POST[txt_eosin]',wbc_baso='$_POST[txt_baso]',platelet='$_POST[txt_platelet]',reticulocytes='$_POST[txt_reticulocytes]',esr='$_POST[txt_esr]',clotting_time='$_POST[txt_clot]',bleeding_time='$_POST[txt_bleeding]',malaria='$_POST[txt_malaria]',slit_smear='$_POST[txt_slit_smear]',fbs='$_POST[txt_fbs]',blood_type='$_POST[sel_bloodtype]',release_flag='$release',release_date='$release_date',user_id='$_SESSION[userid]'") or die("Cannot query 102: ".mysql_error());
      endif;
                                                                                                                                                                                              
      //print_r($_POST);
      
      if($update_hematology):
        echo "<script language='Javascript'>";
        echo "window.alert('Hematology data was successfully been saved.')";
        echo "</script>";
      endif;
      
        
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

    $arr_blood_type = array("0"=>"---","A"=>"A","B"=>"B","O"=>"O","AB"=>"AB");
    
    
    $q_hema = mysql_query("SELECT date_format(date_lab_exam,'%m/%d/%Y') as 'date_lab_exam',hemoglobin,hematocrit,rbc,rbc_mcv,rbc_mchc,rbc_mch,wbc,wbc_polys,wbc_lympho,wbc_mxd,wbc_mono,wbc_eosin,wbc_baso,platelet,reticulocytes,esr,clotting_time,bleeding_time,malaria,slit_smear,fbs,blood_type,user_id,patient_id FROM m_consult_lab_hematology WHERE request_id='$_GET[request_id]'") or die("Cannot query: 131".mysql_error());
    $q_lab = mysql_query("SELECT patient_id,date_format(request_timestamp,'%a %d %Y,%h %i %p') as 'date_requested',request_user_id,date_format(done_timestamp,'%a %d %b %Y, %h %i %p') as 'date_done',request_done,done_user_id FROM m_consult_lab WHERE request_id='$_GET[request_id]'") or die("Cannot query 132".mysql_error());
    
    if(mysql_num_rows($q_hema)!=0):
      list($date_lab_exam,$hemo,$hema,$rbc,$mcv,$mchc,$mch,$wbc,$polys,$lympho,$mxd,$mono,$eosin,$baso,$platelet,$reticulocytes,$esr,$clotting_time,$bleeding_time,$malaria,$slit_smear,$fbs,$blood_type,$user_id,$patient_id) = mysql_fetch_array($q_hema);
    else:
      $date_lab_exam = date('m/d/Y');
    endif;    
    
    list($pxid,$date_request,$request_user_id,$date_done,$request_done,$done_user_id) = mysql_fetch_array($q_lab);        
    
    echo "<a name='hematology'>";
    echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=LABS&module=hematology&request_id=$_GET[request_id]&lab_id=HEM#hematology' method='POST' name='form_lab'>";

     echo "<span class='tinylight'>";
     echo "<b>LAB REQUEST DETAILS</b><br/>";
     echo "<table width='470' bgcolor='#ffff99' style='border: 1px solid black'>";
     echo "<tr><td class='tinylight'><b>LAB EXAM: </b>HEMATOLOGY<br/>";
     echo "<b>DATE REQUESTED: </b>".$date_request."<br/>";
     echo "<b>REQUESTED BY: </b>".user::get_username($request_user_id)."<br/>";
     echo "<b>DATE COMPLETED: </b>".$date_done."<br/>";
     echo "<b>PROCESSED BY: </b>".($done_user_id?user::get_username($done_user_id):"NA")."<br/>";
     echo "<b>RELEASED: </b>".$request_done."<br/></td></tr>";
     echo "</table>";
                                                       
    echo "<hr size='1'/>";  
    
    echo "<table width='470' style='border: 1px solid black'>";
    echo "<tr align='center' class='boxtitle'><td colspan='4'>HEMATOLOGY</td></tr>";
    echo "<tr><td colspan='4' class='boxtitle' class='boxtitle'>DATE EXAMINED &nbsp; <input type='text' name='hematology_date' size='8' maxlength='10' value='$date_lab_exam' class='tinylight'></input>&nbsp;";
    echo "<a href=\"javascript:show_calendar4('document.form_lab.hematology_date', document.form_lab.hematology_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a> ";
    echo "</td></tr>";
                      
    echo "<tr align='center' class='boxtitle'><td>TEST</td><td>RESULT</td><td>TEST</td><td>RESULT</td></tr>";
    echo "<tr><td class='boxtitle'>HEMOGLOBIN</td><td><input type='text' name='txt_hemoglobin' size='9' value='$hemo' class='tinylight'></input></td><td class='boxtitle'>PLATELET</td><td><input type='text' name='txt_platelet' size='9' value='$platelet' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>HEMATOCRIT</td><td><input type='text' name='txt_hemocrit' size='9' value='$hema' class='tinylight'></input></td><td class='boxtitle'>RETICULOCYTES</td><td><input type='text' name='txt_reticulocytes' size='9' value='$reticulocytes' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>RBC</td><td><input type='text' name='txt_rbc' size='9' value='$rbc' class='tinylight'></input></td><td class='boxtitle'>ESR</td><td><input type='text' name='txt_esr' size='9' value='$esr' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>MCV</td><td><input type='text' name='txt_mcv' size='9' value='$mcv' class='tinylight'></input></td><td class='boxtitle'>CLOTING TIME</td><td><input type='text' name='txt_clot' size='9' value='$clotting_time' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>MCHC</td><td><input type='text' name='txt_mchc' size='9' value='$mchc' class='tinylight'></input></td><td class='boxtitle'>BLEEDING TIME</td><td><input type='text' name='txt_bleeding' size='9' value='$bleeding_time' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>MCH</td><td><input type='text' name='txt_mch' size='9' value='$mch' class='tinylight'></input></td><td class='boxtitle'>MALARIA</td><td><input type='text' name='txt_malaria' size='9' value='$malaria' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>WBC</td><td><input type='text' name='txt_wbc' size='9' value='$wbc' class='tinylight'></input></td><td class='boxtitle'>SLIT SMEAR</td><td class='boxtitle'><input type='text' name='txt_slit_smear' size='9' value='$slit_smear' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>POLYS</td><td><input type='text' name='txt_polys' size='9' value='$polys' class='tinylight'></input></td><td class='boxtitle'>FBS</td><td class='boxtitle'><input type='text' name='txt_fbs' size='9' value='$fbs' class='tinylight'></input></td></tr>";
    echo "<tr><td class='boxtitle'>LYMPHO</td><td><input type='text' name='txt_lympho' size='9' value='$lympho' class='tinylight'></input></td><td class='boxtitle'>";
    
    echo "BLOOD TYPE";    
    echo "</td><td>";
    echo "<select name='sel_bloodtype' size='1'>";
    
      foreach($arr_blood_type as $key_type=>$label_type){
      if($blood_type==$key_type):
        echo "<option value='$key_type' SELECTED>$label_type</option>";
      else:
        echo "<option value='$key_type'>$label_type</option>";
      endif;
    }
    
    echo "</select></td></tr>";
    echo "<tr><td class='boxtitle'>MXD</td><td><input type='text' name='txt_mxd' size='9' value='$mxd' class='tinylight'></input></td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td class='boxtitle'>MONO</td><td><input type='text' name='txt_mono' size='9' value='$mono' class='tinylight'></input></td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td class='boxtitle'>EOSIN</td><td><input type='text' name='txt_eosin' size='9' value='$eosin' class='tinylight'></input></td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td class='boxtitle'>BASO</td><td><input type='text' name='txt_baso' size='9' value='$baso' class='tinylight'></input></td><td colspan='2'>&nbsp;</td></tr>";
    
    echo "<tr valign='top'><td colspan='4'><hr size='1'/>";
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

  
  function _consult_lab_hematology_results(){
    if(func_num_args()>0):
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
    endif;
    
    $q_hema = mysql_query("SELECT date_format(date_lab_exam,'%m/%d/%Y') as 'date_lab_exam',hemoglobin,hematocrit,rbc,rbc_mcv,rbc_mchc,rbc_mch,wbc,wbc_polys,wbc_lympho,wbc_mxd,wbc_mono,wbc_eosin,wbc_baso,platelet,reticulocytes,esr,clotting_time,bleeding_time,malaria,slit_smear,fbs,blood_type,user_id,patient_id FROM m_consult_lab_hematology WHERE request_id='$_GET[request_id]'") or die("Cannot query: 131".mysql_error());
    $q_lab = mysql_query("SELECT patient_id,date_format(request_timestamp,'%a %d %Y,%h %i %p') as 'date_requested',request_user_id,date_format(done_timestamp,'%a %d %b %Y, %h %i %p') as 'date_done',request_done,done_user_id FROM m_consult_lab WHERE request_id='$_GET[request_id]'") or die("Cannot query 132".mysql_error());    

    if(mysql_num_rows($q_hema)!=0):
      list($date_lab_exam,$hemo,$hema,$rbc,$mcv,$mchc,$mch,$wbc,$polys,$lympho,$mxd,$mono,$eosin,$baso,$platelet,$reticulocytes,$esr,$clotting_time,$bleeding_time,$malaria,$slit_smear,$fbs,$blood_type,$user_id,$patient_id) = mysql_fetch_array($q_hema);
    else:
      $date_lab_exam = date('m/d/Y');
    endif;    
    
    list($pxid,$date_request,$request_user_id,$date_done,$request_done,$done_user_id) = mysql_fetch_array($q_lab);            
    
    echo "<a name='hematology_result'></a>";
    echo "<table style='border: 1px dotted black' width='400'><tr><td colspan='4'>";
    echo "<span class='tinylight'>";
    echo "<b>HEMATOLOGY RESULTS FOR ".strtoupper(patient::get_name($pxid))."</b><br/>";
    echo "REQUEST ID: <font color='red'>".module::pad_zero($_GET["request_id"],7)."</font><br/>";
    echo "DATE REQUESTED: ".$date_request."<br/>";
    echo "REQUESTED BY: ".user::get_username($request_user_id)."<br/>";
    echo "DATE COMPLETED: ".$date_done."<br/>";
    echo "PROCESSED BY: ".($done_user_id?user::get_username($done_user_id):"NA")."<br/>";      
    echo "RELEASED: ".$request_done."<br/>";    

    echo "<hr size='1'></td></tr>";

    echo "<tr><td colspan='4' class='boxtitle'>DATE EXAMINED &nbsp; $date_lab_exam";
    
    echo "</td></tr>";
                      
    echo "<tr><td>TEST</td><td>RESULT</td><td>TEST</td><td>RESULT</td></tr>";
    echo "<tr><td class='boxtitle'>HEMOGLOBIN</td><td class='tinylight'>$hemo</td><td class='boxtitle'>PLATELET</td><td class='tinylight'>$platelet</td></tr>";
    echo "<tr><td class='boxtitle'>HEMATOCRIT</td><td class='tinylight'>$hema</td><td class='boxtitle'>RETICULOCYTES</td><td class='tinylight'>$reticulocytes</td></tr>";
    echo "<tr><td class='boxtitle'>RBC</td><td class='tinylight'>$rbc</td><td class='boxtitle'>ESR</td><td class='tinylight'>$esr</td></tr>";
    echo "<tr><td class='boxtitle'>MCV</td><td class='tinylight'>$mcv</td><td class='boxtitle'>CLOTING TIME</td><td class='tinylight'>$clotting_time</td></tr>";
    echo "<tr><td class='boxtitle'>MCHC</td><td class='tinylight'>$mchc</td><td class='boxtitle'>BLEEDING TIME</td><td class='tinylight'>$bleeding_time</td></tr>";
    echo "<tr><td class='boxtitle'>MCH</td><td class='tinylight'>$mch</td><td class='boxtitle'>MALARIA</td><td class='tinylight'>$malaria</td></tr>";
    echo "<tr><td class='boxtitle'>WBC</td><td class='tinylight'>$wbc</td><td class='boxtitle'>SLIT SMEAR</td><td class='tinylight'>$slit_smear</td></tr>";
    echo "<tr><td class='boxtitle'>POLYS</td><td class='tinylight'>$polys</td><td class='boxtitle'>FBS</td><td class='tinylight'>$fbs</td></tr>";
    echo "<tr><td class='boxtitle'>LYMPHO</td><td class='tinylight'>$lympho</td><td class='boxtitle'>";
    
    echo "BLOOD TYPE";    
    echo "</td><td class='tinylight'>$blood_type";
    echo "</td></tr>";
    echo "<tr><td class='boxtitle'>MXD</td><td class='tinylight'>$mxd</td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td class='boxtitle'>MONO</td><td class='tinylight'>$mono</td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td class='boxtitle'>EOSIN</td><td class='tinylight'>$eosin</td><td colspan='2'>&nbsp;</td></tr>";
    echo "<tr><td class='boxtitle'>BASO</td><td class='tinylight'>$baso</td><td colspan='2'>&nbsp;</td></tr>";        
    
    echo "</table>";    
      
  }


}

?>