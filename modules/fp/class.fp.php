<?
class fp extends module 
  {

  // Author: Herman Tolentino MD
  // CHITS Project 2004
        
  function fp() 
    {
    //
    // do not forget to update version
    //
    $this->author = 'darth_ali';
    $this->version = "0.1-".date("Y-m-d");
    $this->module = "fp";
    $this->description = "CHITS Module - Family Planning";
    }
                                                                                                                                                
  // --------------- STANDARD MODULE FUNCTIONS ------------------
  function init_deps() 
    {
    //
    // insert dependencies in module_dependencies
    //
    module::set_dep($this->module, "module");
    module::set_dep($this->module, "healthcenter");
    module::set_dep($this->module, "patient");
    }
                                                                                                                                                                                                                
  function init_lang() 
    {
    //
    // insert necessary language directives
    //
    }
    
  function init_stats() 
    {
    }
        
  function init_help() 
    {
    }
                
  function init_menu() 
    {
    // use this for updating menu system
    // under LIBRARIES
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      }
    
    // menu entries
    module::set_menu($this->module, "FP Follow-ups", "CONSULTS", "_mc_followup");
    module::set_menu($this->module, "FP History", "LIBRARIES", "_fp_history");
    module::set_menu($this->module, "FP Methods", "LIBRARIES", "_fp_methods");
                                                                                                                                
    // add more detail
    module::set_detail($this->description, $this->version, $this->author, $this->module);
    
    }

  function init_sql() 
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      }
    
    // main registry table
    module::execsql("CREATE TABLE `m_patient_fp` (".
      "`fp_id` float NOT NULL auto_increment,".
      "`patient_id` float NOT NULL default '0',".
      "`plan_more_children` char(1) NOT NULL default '',".
      "`no_of_living_children` tinyint(2) NOT NULL default '0',".
      "`acceptor_type` char(1) NOT NULL default '',".
      "`previous_method` varchar(10) NOT NULL default '',".
      "`educ_id` varchar(10) NOT NULL default '',".
      "`occup_id` varchar(10) NOT NULL default '',".
      "`spouse_name` varchar(100) NOT NULL default '',".
      "`spouse_educ_id` varchar(10) NOT NULL default '',".
      "`spouse_occup_id` varchar(10) NOT NULL default '',".
      "`ave_monthly_income` float NOT NULL default '0',".
      "`drop_out_flag` char(1) NOT NULL default '',".
      "`user_id` int(11) NOT NULL default '0',".
      "PRIMARY KEY  (`fp_id`),".
      "INDEX `key_patient` (`patient_id`),".
      "INDEX `key_educ` (`educ_id`),".
      "INDEX `key_occup` (`occup_id`),".
      "INDEX `key_spouse_educ` (`spouse_educ_id`),".
      "INDEX `key_spouse_occup` (`spouse_occup_id`),".
      "FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
      "CONSTRAINT `m_patient_fp_educ` ".
      "FOREIGN KEY (`educ_id`) REFERENCES `m_lib_education`(`educ_id`) ".
      "ON DELETE RESTRICT, ".
      "CONSTRAINT `m_patient_fp_occup` ".
      "FOREIGN KEY (`occup_id`) REFERENCES `m_lib_occupation`(`occup_id`) ".
      "ON DELETE RESTRICT, ".
      "CONSTRAINT `m_patient_fp_spouse_educ` ".
      "FOREIGN KEY (`spouse_educ_id`) REFERENCES `m_lib_education`(`educ_id`) ".
      "ON DELETE RESTRICT, ".
      "CONSTRAINT `m_patient_fp_spouse_occup` ".
      "FOREIGN KEY (`spouse_occup_id`) REFERENCES `m_lib_occupation`(`occup_id`) ".
      "ON DELETE RESTRICT".
      ") TYPE=InnoDB; ");
      
    // fp medical history
    module::execsql("CREATE TABLE `m_consult_fp_hx` (".
      "`fp_id` float NOT NULL default '0',".
      "`consult_id` float NOT NULL default '0',".
      "`patient_id` float NOT NULL default '0',".
      "`fp_timestamp` timestamp(14) NOT NULL,".
      "`heent_hx` varchar(25) NOT NULL default '',".
      "`cxhrt_hx` varchar(25) NOT NULL default '',".
      "`abdomen_hx` varchar(15) NOT NULL default '',".
      "`genital_hx` varchar(15) NOT NULL default '',".
      "`extremities_hx` varchar(10) NOT NULL default '',".
      "`skin_hx` varchar(10) NOT NULL default '',".
      "`any_hx` varchar(25) NOT NULL default '',".
      "`ob_fpal` varchar(15) NOT NULL default '',".
      "`last_delivery_date` date NOT NULL default '0000-00-00',".
      "`last_delivery_type` varchar(10) NOT NULL default '',".
      "`past_menstrual_date` date NOT NULL default '0000-00-00',".
      "`last_menstrual_date` date NOT NULL default '0000-00-00',".
      "`bleeding_duration` tinyint(2) NOT NULL default '0',".
      "`bleeding_character` char(2) NOT NULL default '',".
      "`user_id` int(11) NOT NULL default '0',".
      "PRIMARY KEY (`fp_id`, `consult_id`, `fp_timestamp`),".
      "KEY `key_patient` (`patient_id`),".
      "KEY `key_consult` (`consult_id`),".
      "KEY `key_fp` (`fp_id`),".
      "KEY `key_user` (`user_id`),".
      "CONSTRAINT `m_consult_fp_hx_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
      "CONSTRAINT `m_consult_fp_hx_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".      
      "CONSTRAINT `m_consult_fp_hx_ibfk_3` FOREIGN KEY (`fp_id`) REFERENCES `m_patient_fp` (`fp_id`) ON DELETE CASCADE".      
      ") TYPE=InnoDB; ");      
      
    module::execsql("CREATE TABLE `m_consult_fp_pe` (".
      "`fp_id` float NOT NULL default '0',".
      "`consult_id` float NOT NULL default '0',".
      "`patient_id` float NOT NULL default '0',".
      "`fp_timestamp` timestamp(14) NOT NULL,".
      "`systolic_pe` tinyint(3) NOT NULL default '0',".
      "`diastolic_pe` tinyint(3) NOT NULL default '0',".
      "`pluserate_pe` tinyint(3) NOT NULL default '0',".
      "`weight_pe` float NOT NULL default '0',".
      "`conjuctiva_pe` varchar(10) NOT NULL default '',".
      "`neck_pe` varchar(10) NOT NULL default '',".
      "`breast_pe` varchar(10) NOT NULL default '',".
      "`thorax_pe` varchar(10) NOT NULL default '',".
      "`abdomen_pe` varchar(10) NOT NULL default '',".
      "`extremities_pe` varchar(10) NOT NULL default '',".      
      "`user_id` int(11) NOT NULL default '0',".
      "PRIMARY KEY (`fp_id`, `consult_id`, `fp_timestamp`),".
      "KEY `key_patient` (`patient_id`),".
      "KEY `key_consult` (`consult_id`),".
      "KEY `key_fp` (`fp_id`),".
      "KEY `key_user` (`user_id`),".
      "CONSTRAINT `m_consult_fp_pe_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
      "CONSTRAINT `m_consult_fp_pe_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".      
      "CONSTRAINT `m_consult_fp_pe_ibfk_3` FOREIGN KEY (`fp_id`) REFERENCES `m_patient_fp` (`fp_id`) ON DELETE CASCADE".      
      ") TYPE=InnoDB; ");            
      
    module::execsql("CREATE TABLE `m_consult_fp_pelvic_exam` (".
      "`fp_id` float NOT NULL default '0',".
      "`consult_id` float NOT NULL default '0',".
      "`patient_id` float NOT NULL default '0',".
      "`fp_timestamp` timestamp(14) NOT NULL,".
      "`perineum_pelvic` varchar(15) NOT NULL default '',".
      "`vagina_pelvic` varchar(15) NOT NULL default '',".
      "`cervix_pelvic` varchar(15) NOT NULL default '',".
      "`uteruspos_pelvic` varchar(10) NOT NULL default '',".
      "`uterussize_pelvic` varchar(10) NOT NULL default '',".
      "`uterusmass_pelvic` varchar(10) NOT NULL default '',".
      "`uterine_depth` float NOT NULL default '',".
      "`adnexa_pelvic` varchar(10) NOT NULL default '',".
      "`user_id` int(11) NOT NULL default '0',".
      "PRIMARY KEY (`fp_id`, `consult_id`, `fp_timestamp`),".
      "KEY `key_patient` (`patient_id`),".
      "KEY `key_consult` (`consult_id`),".
      "KEY `key_fp` (`fp_id`),".
      "KEY `key_user` (`user_id`),".
      "CONSTRAINT `m_consult_fp_pelvic_exam_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
      "CONSTRAINT `m_consult_fp_pelvic_exam_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".      
      "CONSTRAINT `m_consult_fp_pelvic_exam_ibfk_3` FOREIGN KEY (`fp_id`) REFERENCES `m_patient_fp` (`fp_id`) ON DELETE CASCADE".      
      ") TYPE=InnoDB; ");            

    module::execsql("CREATE TABLE `m_consult_fp_services` (".
      "`fp_id` float NOT NULL default '0',".
      "`consult_id` float NOT NULL default '0',".
      "`patient_id` float NOT NULL default '0',".
      "`fp_timestamp` timestamp(14) NOT NULL,".
      "`service_date` date NOT NULL default '0000-00-00',".  
      "`method_id` varchar(10) NOT NULL default '',".
      "`remarks` text NOT NULL default '',".
      "`user_id` int(11) NOT NULL default '0',".
      "PRIMARY KEY (`fp_id`, `consult_id`, `fp_timestamp`, `method_id`),".
      "KEY `key_patient` (`patient_id`),".
      "KEY `key_method` (`method_id`),".
      "KEY `key_user` (`user_id`),".
      "KEY `key_consult` (`consult_id`),".
      "KEY `key_fp` (`fp_id`),".
      "CONSTRAINT `m_consult_fp_services_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
      "CONSTRAINT `m_consult_fp_services_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
      "CONSTRAINT `m_consult_fp_services_ibfk_3` FOREIGN KEY (`fp_id`) REFERENCES `m_patient_fp` (`fp_id`) ON DELETE CASCADE".
      ") TYPE=InnoDB; ");
      
    // medical history library
    module::execsql("CREATE TABLE `m_lib_fp_history` (".
      "`history_id` varchar(10) NOT NULL default '',".
      "`history_text` varchar(100) NOT NULL default '',".
      "`history_cat` varchar(15) NOT NULL default '',".
      "PRIMARY KEY (`history_id`)".
      ") TYPE=MyISAM; ");

    // medical history category table
    module::execsql("CREATE TABLE `m_lib_fp_history_cat` (".
      "`cat_id` varchar(10) NOT NULL default '',".
      "`cat_name` varchar(50) NOT NULL default '',".
      "PRIMARY KEY (`cat_id`)".
      ") TYPE=MyISAM; ");
    
    // family planning methods table  
    module::execsql("CREATE TABLE `m_lib_fp_methods` (".
      "`method_id` varchar(10) NOT NULL default '',".
      "`method_name` varchar(25) NOT NULL default '',".
      "PRIMARY KEY (`method_id`)".
      ") TYPE=MyISAM; ");
   
    //family planning client table
    module::execsql(" CREATE TABLE `m_lib_fp_client` (
    `client_id` INT( 7 ) NOT NULL AUTO_INCREMENT ,
    `client_code` VARCHAR( 2 ) NOT NULL ,
    `client_text` TEXT NOT NULL ,
    PRIMARY KEY ( `client_id` )
    ) ENGINE = MYISAM; ");
     
    // initial data for m_lib_fp_history
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('EPILEPSY', 'Epilepsy/Convulsion/Seizure', 'HEENT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('HEADACHE', 'Severe headache/dizziness', 'HEENT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('VISION', 'Visual disturbance/blurring of vision', 'HEENT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('YCONJ', 'Yellowish conjunctive', 'HEENT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ETHY', 'Enlarged thyroid', 'HEENT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('CXPAIN', 'Severe chest pain', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('FATIGUE', 'Shortness of breath and easy fatiguability', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('BRSTMASS', 'Breast/axillary masses', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('NIPBLOOD', 'Nipple discharges (blood)', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('NIPPUS', 'Nipple discharges (pus)', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('SYS140', 'Systolic of 140 & above', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('DIAS90', 'Diastolic of 90 & above', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('CVAHARHD', 'Family history of CVA (strokes), hypertension, asthma, rheumatic heart disease', 'CXHRT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('MASSABD', 'Mass in the abdomen', 'ABD')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('GALL', 'History of gallbladder disease', 'ABD')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('LIVER', 'History of liver disease', 'ABD')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('UTERUS', 'Mass in the uterus', 'GEN')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('VAGDISCH', 'Vaginal discharge', 'GEN')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('INTERBLEED', 'Intermenstrual bleeding', 'GEN')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('POSTBLEED', 'Postcoital bleeding', 'GEN')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('VARICOSE', 'Severe varicosities', 'EXT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('LEGPAIN', 'Swelling or severe pain in the legs not related to injuries', 'EXT')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('YELLOWSKIN', 'Yellowish skin', 'SKIN')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('SMOKING', 'Smoking', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ALLERGY', 'Allergies', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('DRUGINTAKE', 'Drug intake (anti-TB, anti-diabetic, anticonvulsant)', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('STD', 'STD', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('MPARTNERS', 'Multiple partners', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('BLEEDING', 'Bleeding tendencies (nose, gums, etc.)', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ANEMIA', 'Anemia', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('DIABETES', 'Diabetes', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('HMOLE', 'Hydatidiform mole (w/in the last 12 mos.)', 'ANY')");
    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ECTPREG', 'Ectopic pregnancy', 'ANY')");
    
    // initial data for m_lib_fp_history_cat
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('HEENT', 'HEENT')");
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('CXHRT', 'CHEST/HEART')");
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('ABD', 'ABDOMEN')");
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('GEN', 'GENITAL')");
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('EXT', 'EXTREMITIES')");
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('SKIN', 'SKIN')");
    module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('ANY', 'HISTORY OF ANY OF THE FOLLOWING')");
    
    // initial data for m_lib_fp_methods    
    module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`) VALUES ('PILLS', 'Pills')");
    module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`) VALUES ('INJECT', 'Injection')");
    module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`) VALUES ('CONDOM', 'Condom')");
    module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`) VALUES ('IUD', 'IUD')");
    module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`) VALUES ('LAM', 'Lactational amenorrhea')");
    module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`) VALUES ('OMBBTSTM', 'Ovulation/Basal Body Temp./Sympto-thermal')");
    
    }
    
//    module::execsql(" INSERT INTO `m_lib_fp_client` (`client_id`,`client_code`,`client_text`) VALUES (NULL , 'CU', 'Current Users'
//    ), (NULL , 'NA', 'New Acceptors'), (NULL , 'CM', 'Changing Method'), (NULL , 'CC', 'Changing Clinic'), (NULL , 'RS', 'Restart');");
    
  function drop_tables() 
    {
    module::execsql("set foreign_key_checks=0;");
    module::execsql("DROP TABLE `m_consult_fp_services`;");
    module::execsql("DROP TABLE `m_consult_fp_hx`;");
    module::execsql("DROP TABLE `m_consult_fp_pe`;");
    module::execsql("DROP TABLE `m_consult_fp_pelvic_exam`;");
    module::execsql("DROP TABLE `m_lib_fp_history_cat`;");
    module::execsql("DROP TABLE `m_lib_fp_history`;");
    module::execsql("DROP TABLE `m_lib_fp_methods`;");
    module::execsql("DROP TABLE `m_patient_fp`;");
    module::execsql("set foreign_key_checks=1;");

    }
    
  // --------------- CUSTOM MODULE FUNCTIONS ------------------
  
  function _consult_fp() 
    {
    //
    // main submodule for family planning consults
    //
    // always check dependencies
    if ($exitinfo = $this->missing_dependencies('fp')) 
      {
      return print($exitinfo);
      }
    
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
    
    $fp = new fp;

    $fp->fp_menu($menu_id, $post_vars, $get_vars);
    
    if ($post_vars[submitfp]) 
      {
      $fp->process_fp($menu_id, $post_vars, $get_vars);
      }
    
    $patient_id = healthcenter::get_patient_id($get_vars[consult_id]);
    
    switch ($get_vars[fp]) 
      {
      case "VISIT1":
        if (!($fp->registry_record_exists($patient_id)) || $post_vars[submitfp] == 'Update Visit 1') 
          {
          $fp->form_fp_firstvisit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
          } 
        break;
      
      case "HX":
        $fp->form_fp_medhx($menu_id, $post_vars, $get_vars);
        break;
      
      case "PE":
        $fp->form_fp_pe($menu_id, $post_vars, $get_vars);
        break;
        
      case "PELVIC":
        $fp->form_fp_pe($menu_id, $post_vars, $get_vars);
        break;
        
      case "SVC":
      default:
        $fp->form_fp_services($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        break;
      }
    }
    
  function fp_menu()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
      
    if (!isset($get_vars[fp])) 
      {
      header("location: $_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&fp=VISIT1");
      }
      
    echo ("
    <table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'>
    <tr valign='top'>
      <td nowrap>
        <a href='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&fp=VISIT1' class='groupmenu'>".strtoupper(($get_vars[fp]=='VISIT1'?"<b>FIRST VISIT</b>":"FIRST VISIT"))."</a>
        <a href='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&fp=HX' class='groupmenu'>".strtoupper(($get_vars[fp]=="HX"?"<b>MED HX</b>":"MED HX"))."</a>
        <a href='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&fp=PE' class='groupmenu'>".strtoupper(($get_vars[fp]=="PE"?"<b>PE</b>":"PE"))."</a>
        <a href='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&fp=PELVIC' class='groupmenu'>".strtoupper(($get_vars[fp]=="PELVIC"?"<b>PELVIC EXAM</b>":"PELVIC EXAM"))."</a>
        <a href='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&fp=SVC' class='groupmenu'>".strtoupper(($get_vars[fp]=="SVC"?"<b>SERVICES</b>":"SERVICES"))."</a>
      </td>
    </tr>
    </table>
    <br/>
    ");
    }
  
  function registry_record_exists() 
    {
    //
    // this makes sure that all entries for registry tables
    // have the correct registry id embedded
    // assumption: only one registry id for each patient
    //
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $patient_id = $arg_list[0];
      }
    
    $query_fp = mysql_query("SELECT fp_id FROM m_patient_fp WHERE patient_id = '$patient_id'")
        or die(mysql_error());
        
    if ($query_fp) 
      {
      if (mysql_num_rows($query_fp)) 
        {
        list($id) = mysql_fetch_array($result);
        return $id;
        }
      }
    }
    
  function form_fp_firstvisit() 
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
    
    if ($post_vars[fp_id] && $post_vars[submitfp]) 
      {
      $query_fp = mysql_query("SELECT * FROM m_patient_fp WHERE fp_id = '$post_vars[fp_id]'")
        or die(mysql_error());
    
      if (mysql_num_rows($query_fp)) 
        {
        $fp = mysql_fetch_array($query_fp);
        }
      }
      
    echo ("
    <a name='visit1form'>
    <table width='300' border='0'>
    <form action = '$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=fp&fp=VISIT1' name='form_fp_visit1' method='post'>
    <tr valign='top'>
      <td><b>FAMILY PLANNING DATA</b><br/><br/></td>
    </tr>
    <tr valign='top'>
      <td>
        <table bgcolor='#FFCCFF' width='300' cellpadding='3' border='0'>
        <tr valign='top'>
          <td><span class='boxtitle'>PLAN MORE CHILDREN?</span</br>
            <select name='plan_more_children'>
              <option value='' ".(($fp[plan_more_children] || $post_vars[plan_more_children]) == ''?'selected':'')."> ---- </option>
              <option value='Y' ".(($fp[plan_more_children] || $post_vars[plan_more_children]) == 'Y'?'selected':'').">Yes</option>
              <option value='N' ".(($fp[plan_more_children] || $post_vars[plan_more_children]) == 'N'?'selected':'').">No</option>
            </select>
          </td>
        </tr>
        <tr>
          <td><span class='boxtitle'>NO. OF LIVING CHILDREN :</span></br>
            <input type='text' class='textbox' size='3' maxlength='3' name='no_of_living_children' value='".($fp[no_of_living_children]?$fp[no_of_living_children]:$post_vars[no_of_living_children])."' style='border: 1px solid black'>
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>TYPE OF ACCEPTOR :</span</br>
            <select name='acceptor_type'>
              <option value='' ".(($fp[acceptor_type] || $post_vars[acceptor_type]) == ''?'selected':'')."> --- Select type ---</option>
              <option value='N' ".(($fp[acceptor_type] || $post_vars[acceptor_type]) == 'N'?'selected':'').">New to the program</option>
              <option value='C' ".(($fp[acceptor_type] || $post_vars[acceptor_type]) == 'C'?'selected':'').">Continuing user</option>
            </select>
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>PREVIOUS METHOD USED :</span</br>
    ");
    
    $this->show_fp_methods($fp[previous_method]?$fp[previous_method]:$post_vars[previous_method]);
    
    echo ("
          <br></td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>HIGHEST EDUC. ATTAINMENT :</span</br>
            <select name='educ_id' size='5'>
    ");
    
    $this->show_education($fp[educ_id]?$fp[educ_id]:$post_vars[educ_id]);
    
    echo ("
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>OCCUPATION :</span</br>
            <select name='occup_id' size='5'>
    ");
    
    $this->show_occupation($fp[occup_id]?$fp[occup_id]:$post_vars[occup_id]);
    
    echo ("
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>NAME OF SPOUSE :</span</br>
            <input type='text' name='spouse_name' value='".($fp[spouse_name]?$fp[spouse_name]:$post_vars[spouse_name])."' style='border: 1px solid black'>
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>SPOUSE'S HIGHEST EDUC. ATTAINMENT :</span</br>
            <select name='spouse_educ_id' size='5'>
    ");
    
    $this->show_education($fp[spouse_educ_id]?$fp[spouse_educ_id]:$post_vars[spouse_educ_id]);
    
    echo ("
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>SPOUSE'S OCCUPATION :</span</br>
            <select name='spouse_occup_id' size='5'>
    ");
    
    $this->show_occupation($fp[spouse_occup_id]?$fp[spouse_occup_id]:$post_vars[spouse_occup_id]);
    
    echo ("
          </td>
        </tr>
        <tr valign='top'>
          <td><span class='boxtitle'>AVE. MONTHLY FAMILY INCOME :</span</br>
            <input type='text' name='income' value='".($fp[ave_monthly_income]?$fp[ave_monthly_income]:$post_vars[income])."' style='border: 1px solid black' size='10'>
          </td>
        </tr>
        <tr>
          <td><br>
    ");
    
    if ($get_vars[fp_id])
      {
      if ($_SESSION[priv_update])
        {
        echo ("
        <input type='hidden' name='fp_id' value='$get_vars[fp_id]' />
        <input type='submit' value = 'Update Visit 1' class='textbox' name='submitfp' style='border: 1px solid black'>
        <input type='submit' value = 'Delete Visit 1' class='textbox' name='submitfp' style='border: 1px solid black'>
        ");
        }
      }
    else
      {
      if ($_SESSION[priv_add])
        {
        echo ("
        <input type='submit' value = 'Save Visit 1' class='textbox' name='submitfp' style='border: 1px solid #000000'>
        ");
        }
      }
    
    echo ("
        </table>
      </td>
    </tr>
    </form>
    </table>
    ");	
    
    }
    
  function show_fp_methods()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $method_id = $arg_list[0];
      }
      
    $query_fp_method = mysql_query("SELECT method_id, method_name FROM m_lib_fp_methods
      ORDER BY method_name") or die(mysql_error());
    
    if (mysql_num_rows($query_fp_method))
      {
      echo ("
      <select name='previous_method'>
        <option value=''>-- Select method --</option>
      ");
      
      while (list($id, $name) = mysql_fetch_array($query_fp_method))
        {
        echo ("
        <option value='$id' ".($method_id == '$id'?'selected':'').">$name</option>
        ");
        }
      
      echo ("
      </select>
      ");
      }
      
    else
      {
      echo ("
      <font color='red'>No library available.</font>
      ");
      }
    }
    
  function show_education()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $educ_id = $arg_list[0];
      }
      
    $query_educ = mysql_query("SELECT * from m_lib_education") or die(mysql_error());
    
    if (mysql_num_rows($query_educ))
      {
      while (list($id, $name) = mysql_fetch_array($query_educ))
        {
        echo ("
        <option value='$id' ".($educ_id == '$id'?'selected':'').">$name</option>
        ");
        }
      echo ("
      </select>
      ");
      }
      
    else
      {
      echo ("
      <font color='red'>No library available.</font>
      ");
      }
    }
    
  function show_occupation()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $occup_id = $arg_list[0];
      }
      
    $query_occup = mysql_query("SELECT occup_id, occup_name from m_lib_occupation ORDER BY occup_name") or die(mysql_error());
    
    if (mysql_num_rows($query_occup))
      {
      while (list($id, $name) = mysql_fetch_array($query_occup))
        {
        echo ("
        <option value='$id' ".($occup_id == '$id'?'selected':'').">$name</option>
        ");
        }
      echo ("
      </select>
      ");
      }
      
    else
      {
      echo ("
      <font color='red'>No library available.</font>
      ");
      }
    }
    
  function form_fp_medhx()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
      
    if ($post_vars[fp_id] && $post_vars[submitfp]) 
      {
      $query_fp = mysql_query("SELECT * FROM m_consult_fp_hx WHERE fp_id = '$post_vars[fp_id]'")
        or die(mysql_error());
    
      if (mysql_num_rows($query_fp)) 
        {
        $fp = mysql_fetch_array($query_fp);
        }
      }
      
    echo ("
    <a name='hxform'>
    <table width='300' border='0'>
    <form action = '$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=fp&fp=HX' name='form_fp_hx' method='post'>
    <tr valign='top'>
      <td><b>MEDICAL HISTORY</b><br/><br/></td>
    </tr>
    <tr valign='top'>
      <td>
        <table bgcolor='#FFCCFF' width='300' cellpadding='3' border='0'>
        <tr valign='top'>
          <td><span class='boxtitle'>HEENT</span</br>
            <input type='checkbox' name='heent' value='1'> 
    ");
    }
    
  function form_fp_pe()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
    }
    
  function form_fp_pelvic_exam()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
    }
    
  function form_fp_services()
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
    }

  function _details_fp() 
    {
    if (func_num_args()>0) 
      {
      $arg_list = func_get_args();
      $menu_id = $arg_list[0];
      $post_vars = $arg_list[1];
      $get_vars = $arg_list[2];
      $validuser = $arg_list[3];
      $isadmin = $arg_list[4];
      //print_r($arg_list);
      }
      
    if ($post_vars["submitdetail"]) 
      {
      //fp::process_detail($menu_id, $post_vars, $get_vars);
      }
    
    print "<b>FAMILY PLANNING RECORDS</b><br/>";
    //fp::display_registry_records($menu_id, $post_vars, $get_vars);
    if ($get_vars[fp_id]) 
      {
      //fp::display_registry_record_detail($menu_id, $post_vars, $get_vars);
      //fp::display_prenatal_records($menu_id, $post_vars, $get_vars);
      //mc::display_postpartum_records($menu_id, $post_vars, $get_vars);
      //mc::display_vaccine_record($menu_id, $post_vars, $get_vars);
      //mc::display_service_record($menu_id, $post_vars, $get_vars);
      }
    print "<br/>";
    print "";
    }
                                                                                                                                                                                                                                                                                            
  } // end of class  
