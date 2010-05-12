<?php

  class fecalysis extends module{
  
    function fecalysis(){
      $this->author = 'darth_ali';
      $this->version = "0.1".date("Y-m-d");
      $this->module = "fecalysis";
      $this->description = "CHITS Module - Fecalysis Examination";    
    }
    
    
    // ----- STANDARD MODULE FUNCTIONS ------ 
    
    function init_deps(){
      module::set_dep($this->module,"module");
      module::set_dep($this->module,"lab");      
    }
    
    function init_lang(){
      module::set_lang("FTITLE_FECALYSIS","english","FECALYSIS LAB EXAM","Y");
      module::set_lang("FTITLE_FECALYSIS_MACRO","english","Macroscopic","Y");      
      module::set_lang("FTITLE_FECALYSIS_MICRO","english","Microscopic","Y");       
      module::set_lang("THEAD_MACRO_PHYSICAL","english","PHYSICAL","Y");     
      module::set_lang("THEAD_MACRO_CHEMICAL","english","CHEMICAL","Y");     
      module::set_lang("LBL_MACRO_COLOR","english","Color","Y");
      module::set_lang("LBL_MACRO_CONSISTENCY","english","Consistency","Y");
      module::set_lang("LBL_MACRO_OCCULTBLOOD","english","Occult Blood","Y");
      module::set_lang("LBL_MACRO_OCCULTBLOOD","english","Occult Blood","Y");
      module::set_lang("LBL_MICRO_OVA","english","Ova or Parasite","Y");
      module::set_lang("LBL_MICRO_WBC","english","WBC","Y");
      module::set_lang("LBL_MICRO_WBC","english","WBC","Y");
      module::set_lang("LBL_MICRO_RBC","english","RBC","Y");
      module::set_lang("LBL_MICRO_BACTERIA","english","Bacteria","Y");
      module::set_lang("LBL_MICRO_FAT","english","Fat Globules","Y");
      module::set_lang("LBL_MICRO_STARCH","english","Starch Granules","Y");
      module::set_lang("LBL_MICRO_OTHERS","english","OTHERS","Y");
      module::set_lang("LBL_MICRO_OTHERS","english","OTHERS","Y");      
      module::set_lang("LBL_MICRO_REMARKS","english","REMARKS","Y");                                          
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
      if(func_num_args()>0):
        $arg_list = func_get_args();
      endif;
      
      module::execsql("CREATE TABLE IF NOT EXISTS `m_consult_lab_fecalysis` (
        `consult_id` float NOT NULL,`request_id` float NOT NULL,`patient_id` float NOT NULL,
        `date_lab_exam` date NOT NULL,`fecal_color` text NOT NULL,`fecal_consistency` text NOT NULL,
        `fecal_occultblood` text NOT NULL,`fecal_ova` text NOT NULL,`fecal_wbc` text NOT NULL,
        `fecal_rbc` text NOT NULL,`fecal_bacteria` text NOT NULL,`fecal_fat` text NOT NULL,
        `fecal_starch` text NOT NULL,`fecal_others` text NOT NULL,`release_flag` text NOT NULL,`release_date` datetime NOT NULL,
        `user_id` float NOT NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");                  
    }
    
    function drop_tables(){
      module::execsql("DROP TABLE `m_consult_lab_fecalysis`;");
    }
    
    //----- CUSTOM MODULE FUNCTIONS -----
    
    
    function _consult_lab_fecalysis(){
      if(func_num_args()>0):
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];      
      endif;
      
      if($exitinfo=$this->missing_dependencies('fecalysis')):
        return print($exitinfo);      
      endif;
      
      $f = new fecalysis;
      
      if($_POST["submitlab"]):
        //print_r($_POST);
        
        $q_request = mysql_query("SELECT request_id FROM m_consult_lab_fecalysis WHERE request_id='$_POST[request_id]'") or die("Cannot query 101 ".mysql_error());
        
       if($_POST[release_flag]==1):
          $release = 'Y';
          $release_date = date('Y-m-d H:i:s');
          
          $q_update_lab = mysql_query("UPDATE m_consult_lab SET request_done='$release',done_timestamp='$release_date',done_user_id='$_SESSION[userid]' WHERE request_id='$_POST[request_id]' AND lab_id='$_GET[lab_id]'") or die("Cannot query 107 ".mysql_error());
          
        else:
          $release = 'N';
          $release_date = '';
        endif;

          $pxid = healthcenter::get_patient_id($_GET[consult_id]);
          list($m,$d,$y) = explode('/',$_POST[fecal_date]);
          
          $date_lab_exam = $y.'-'.$m.'-'.$d;        
                
        if(mysql_num_rows($q_request)!=0):
          $update_fecal = mysql_query("UPDATE m_consult_lab_fecalysis SET date_lab_exam='$date_lab_exam',fecal_color='$_POST[fecal_color]',fecal_consistency='$_POST[fecal_consistency]',fecal_occultblood='$_POST[fecal_blood]',fecal_ova='$_POST[fecal_ova]',fecal_wbc='$_POST[fecal_wbc]',fecal_rbc='$_POST[fecal_rbc]',fecal_bacteria='$_POST[fecal_bacteria]',fecal_fat='$_POST[fecal_fat]',fecal_starch='$_POST[fecal_starch]',fecal_others='$_POST[fecal_others]',release_flag='$release',release_date='$release_date'") or die("Cannot query 120 ".mysql_error());
        else:                    
          $insert_fecal = mysql_query("INSERT INTO m_consult_lab_fecalysis SET consult_id='$_GET[consult_id]', request_id='$_POST[request_id]', patient_id='$pxid', date_lab_exam='$date_lab_exam', fecal_color='$_POST[fecal_color]', fecal_consistency='$_POST[fecal_consistency]', fecal_occultblood='$_POST[fecal_blood]',fecal_ova='$_POST[fecal_ova]',fecal_wbc='$_POST[fecal_wbc]',fecal_rbc='$_POST[fecal_rbc]',fecal_bacteria='$_POST[fecal_bacteria]', fecal_fat='$_POST[fecal_fat]',fecal_starch='$_POST[fecal_starch]',fecal_others='$_POST[fecal_others]',user_id='$_SESSION[userid]',release_flag='$release',release_date='$release_date'") or die("Cannot query 106".mysql_error());                                      
        endif;
        
        if($update_fecal || $insert_fecal):
          echo "<script language='Javascript'>";          
          echo "window.alert('Fecalysis data was successfully been saved.')";          
          echo "</script>";
        endif;
        
        
      endif;
      
      
            
      $f->form_consult_lab_fecalysis($menu_id,$post_vars,$get_vars);
    }
    
    function _consult_lab_fecalysis_results(){
      $q_fecalysis = mysql_query("SELECT date_format(date_lab_exam,'%m/%d/%Y') as 'date_lab_exam',fecal_color,fecal_consistency,fecal_occultblood,fecal_ova,fecal_wbc,fecal_rbc,fecal_bacteria,fecal_fat,fecal_starch,fecal_others,user_id,patient_id FROM m_consult_lab_fecalysis WHERE request_id='$_GET[request_id]' AND release_flag='Y'") or die("Cannot query 150".mysql_error());
      $q_lab_details = mysql_query("SELECT patient_id,date_format(request_timestamp,'%a %d %b %Y, %h %i %p') as 'date_requested', request_user_id, date_format(done_timestamp,'%a %d %b %Y, %h %i %p') as 'date_done', request_done, done_user_id FROM m_consult_lab WHERE request_id='$_GET[request_id]' AND request_done='Y'") or die("Cannot query 151:".mysql_error());
      
      list($pxid,$date_request,$request_user_id,$date_done,$request_done,$done_user_id) = mysql_fetch_array($q_lab_details);    
      list($date_lab_exam,$color,$consistency,$blood,$ova,$wbc,$rbc,$bacteria,$fat,$starch,$others,$userid,$pxid)  = mysql_fetch_row($q_fecalysis);      
      
      echo "<a name='fecalysis_result'></a>";
      
      
      echo "<table style='border: 1px dotted black'><tr><td>";
      print "<span class='tinylight'>";
      print "<b>FECALYSIS RESULTS FOR ".strtoupper(patient::get_name($pxid))."</b><br/>";
      print "REQUEST ID: <font color='red'>".module::pad_zero($_GET["request_id"],7)."</font><br/>";
      print "DATE REQUESTED: ".$date_request."<br/>";
      print "REQUESTED BY: ".user::get_username($request_user_id)."<br/>";
      print "DATE COMPLETED: ".$date_done."<br/>";
      print "PROCESSED BY: ".($done_user_id?user::get_username($done_user_id):"NA")."<br/>";      
      print "RELEASED: ".$request_done."<br/>";
      
      print "<hr size='1'/>";
      print "<b>FECALYSIS EXAM DATE: </b>".$date_lab_exam."<br/> ";      
      print "<b>MACROSCOPIC</b><br/><b>PHYSICAL</b><br/>";
      print "<b>COLOR: </b>".$color."<br/>";
      print "<b>CONSISTENCY: </b>".$consistency."<br/>";
      print "<b>CHEMICAL</b><br/>";
      print "<b>OCCULT BLOOD: </b>".$blood."<br/>";
      
      print "<hr size='1'/>";
      print "<b>MICROSCOPIC </b><br/>";
      print "<b>OVA OR PARASITE: </b>".$ova."<br/>";
      print "<b>WBC: </b>".$wbc."<br/>";
      print "<b>RBC: </b>".$rbc."<br/>";      
      print "<b>BACTERIA: </b>".$bacteria."<br/>";
      print "<b>FAT GLOBULES: </b>".$fat."<br/>";
      print "<b>STARCH GRANULES: </b>".$starch."<br/>";
      print "<b>OTHERS: </b>".$others."<br/>";      
      print "</span>";      
                
      echo "</td></tr></table>";
    }    
    
    
    
    function form_consult_lab_fecalysis(){
      if(func_num_args()>0):
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];      
      endif;      
        
      $q_fecalysis = mysql_query("SELECT date_format(date_lab_exam,'%m/%d/%Y') as 'date_lab_exam',fecal_color,fecal_consistency,fecal_occultblood,fecal_ova,fecal_wbc,fecal_rbc,fecal_bacteria,fecal_fat,fecal_starch,fecal_others,user_id,patient_id FROM m_consult_lab_fecalysis WHERE request_id='$_GET[request_id]'") or die("Cannot query 150".mysql_error());
      $q_lab_details = mysql_query("SELECT patient_id,date_format(request_timestamp,'%a %d %b %Y, %h %i %p') as 'date_requested', request_user_id, date_format(done_timestamp,'%a %d %b %Y, %h %i %p') as 'date_done', request_done, done_user_id FROM m_consult_lab WHERE request_id='$_GET[request_id]'") or die("Cannot query 151:".mysql_error());
      
      list($pxid,$date_request,$request_user_id,$date_done,$request_done,$done_user_id) = mysql_fetch_array($q_lab_details);
      
      if(mysql_num_rows($q_fecalysis)!=0):
        list($date_lab_exam,$color,$consistency,$blood,$ova,$wbc,$rbc,$bacteria,$fat,$starch,$others,$userid,$pxid)  = mysql_fetch_row($q_fecalysis);
      else:
        $date_lab_exam = date('m/d/Y');      
      endif;
      
      echo "<a name='fecalysis'></a>";
      echo "<form action='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&module=fecalysis&request_id=$get_vars[request_id]&lab_id=FEC&ptmenu=LABS#fecalysis' name='form_lab' method='post'>";
      
      echo "<span class='tinylight'>";      
      echo "<b>LAB REQUEST DETAILS</b><br/>";
      echo "<table width='250' bgcolor='#ffff99' style='border: 1px solid black'>";      
      //echo "<tr><td class='tinylight'><b>REQUEST ID: </b><font color='red'>".module::pad_zero($_GET["request_id"],7)."</font><br/>";
      echo "<tr><td class='tinylight'><b>LAB EXAM: </b>FECALYSIS<br/>";
      echo "<b>DATE REQUESTED: </b>".$date_request."<br/>";
      echo "<b>REQUESTED BY: </b>".user::get_username($request_user_id)."<br/>";
      echo "<b>DATE COMPLETED: </b>".$date_done."<br/>";
      echo "<b>PROCESSED BY: </b>".($done_user_id?user::get_username($done_user_id):"NA")."<br/>";      
      echo "<b>RELEASED: </b>".$request_done."<br/></td></tr>";
      echo "</table>";
      
      echo "<hr size='1'/>";
      
      echo "<table style='border: 1px dotted black'>";                                                                                                                                                            
                                    
      echo "<tr><td colspan='2' class='boxtitle'>DATE EXAMINED &nbsp; <input type='text' name='fecal_date' size='8' maxlength='10' value='$date_lab_exam' class='tinylight'></input>&nbsp;";
      
      echo "<a href=\"javascript:show_calendar4('document.form_lab.fecal_date', document.form_lab.fecal_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a> ";
      echo "</td></tr>";
      echo "<tr><td colspan='2' align='center' class='boxtitle'>MACROSCOPIC</td></tr>";            
      echo "<tr><td colspan='2' class='boxtitle' align='center'>PHYSICAL</td></tr>";
      echo "<tr valign='top' class='tinylight'><td class='boxtitle'>COLOR</td><td><input name='fecal_color' type='text' size='9' value='$color' class='tinylight'></input></td></tr>";
      echo "<tr><td class='boxtitle'>CONSISTENCY</td><td><input name='fecal_consistency' type='text' size='9' value='$consistency' class='tinylight'></input></td></tr>";
      
      echo "<tr><td colspan='2' class='boxtitle' align='center'>CHEMICAL</td></tr>";
      echo "<tr><td class='boxtitle'>OCCULT BLOOD</td><td><textarea name='fecal_blood' rows='2' cols='20' class='tinylight'>$blood</textarea></td></tr>";
      
      echo "<tr><td colspan='2'>&nbsp;</td></tr>";
      
      echo "<tr><td colspan='2' class='boxtitle' align='center'>MICROSCOPIC</td></tr>";
      echo "<tr valign='top'><td class='boxtitle'>OVA OR PARASITE</td>";      
      echo "<td><textarea name='fecal_ova' rows='2' cols='20' class='tinylight'>$ova</textarea></td></tr>";      
      
      echo "<tr valign='top'><td class='boxtitle'>WBC</td>";
      echo "<td><input name='fecal_wbc' type='text' size='9' value='$wbc' class='tinylight'></input> /hpf</td></tr>";
      
      echo "<tr valign='top'><td class='boxtitle'>RBC</td>";
      echo "<td><input name='fecal_rbc' type='text' size='9' value='$rbc' class='tinylight'></input> /hpf</td></tr>";
      
      echo "<tr valign='top'><td class='boxtitle'>BACTERIA</td>";
      echo "<td><input name='fecal_bacteria' type='text' size='9' value='$bacteria' class='tinylight'></input></td></tr>";
      
      echo "<tr valign='top'><td class='boxtitle'>FAT GLOBULES</td>";
      echo "<td><input name='fecal_fat' type='text' size='9' value='$fat' class='tinylight'></input></td></tr>";
      
      echo "<tr valign='top'><td class='boxtitle'>STARCH GLOBULES</td>";
      echo "<td><input name='fecal_starch' type='text' size='9' value='$starch' class='tinylight'></input></td></tr>";
      
      echo "<tr valign='top'><td class='boxtitle'>OTHERS</td>";
      echo "<td><textarea name='fecal_others' rows='2' cols='20' class='tinylight'>$others</textarea></td></tr>";
      
      echo "<tr valign='top'><td colspan='2'>";
      echo "<span class='boxtitle'>".LBL_RELEASE_FLAG."</span><br>";
      echo "<input type='checkbox' name='release_flag' ".(($fecalysis["release_flag"]?$fecalysis["release_flag"]:$post_vars["release_flag"])=="Y"?"checked":"")." value='1'/> ".INSTR_RELEASE_FLAG."<br />";
      echo "</td></tr>";      
      
      
      
      echo "<tr><td colspan='2' align='center'>";
      if ($get_vars["request_id"]) {
      
        print "<input type='hidden' name='request_id' value='".$get_vars["request_id"]."'>";
        
        if ($_SESSION["priv_update"]) {
          print "<input type='submit' value = 'Update Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
        }              
      }      
      echo "</td></tr>";
                
      echo "</table>";
      
      echo "</form>";
    }
    
    
    
  
  
  
  
  
  }
?>
