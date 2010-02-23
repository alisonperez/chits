<?
class sputum extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004-2010

    function sputum() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD / darth_ali';
        $this->version = "0.5-".date("Y-m-d");
        $this->module = "sputum";
        $this->description = "CHITS Module - Sputum Microscopy";

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "lab");
        module::set_dep($this->module, "ntp");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_SPUTUM_APPEARANCE_LIST", "english", "SPUTUM VISUAL APPEARANCE", "Y");
        module::set_lang("LBL_APPEARANCE_ID", "english", "APPEARANCE ID", "Y");
        module::set_lang("LBL_APPEARANCE_NAME", "english", "APPEARANCE NAME", "Y");
        module::set_lang("FTITLE_SPUTUM_APPEARANCE_FORM", "english", "SPUTUM VISUAL APPEARANCE FORM", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("LBL_LAB_REQUEST_DETAILS", "english", "LAB REQUEST DETAILS", "Y");
        module::set_lang("LBL_LAB_EXAM", "english", "LAB EXAM", "Y");
        module::set_lang("LBL_DATE_REQUESTED", "english", "DATE REQUESTED", "Y");
        module::set_lang("LBL_REQUESTED_BY", "english", "REQUESTED BY", "Y");
        module::set_lang("LBL_SPECIMEN", "english", "SPECIMEN", "Y");
        module::set_lang("LBL_VISUAL_APPEARANCE", "english", "VISUAL APPEARANCE", "Y");
        module::set_lang("LBL_READING", "english", "READING", "Y");
        module::set_lang("LBL_LAB_DIAGNOSIS", "english", "LAB DIAGNOSIS", "Y");
        module::set_lang("LBL_RELEASE_FLAG", "english", "RELEASE FLAG", "Y");
        module::set_lang("INSTR_RELEASE_FLAG", "english", "Check to release lab exam if complete", "Y");
        module::set_lang("LBL_COLLECTION_DATE", "english", "COLLECTION DATE", "Y");
        module::set_lang("LBL_DATE_PROCESSED", "english", "DATE PROCESSED", "Y");
        module::set_lang("LBL_PROCESSED_BY", "english", "PROCESSED BY", "Y");
        module::set_lang("LBL_SPUTUM_PERIOD", "english", "PERIOD OF SPUTUM EXAM", "Y");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        // use this for updating menu system
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_consult_lab_sputum` (".
            "`consult_id` float NOT NULL default '0',".
            "`request_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`sputum_period` char(5) NOT NULL default 'DX', ".
            "`lab_timestamp` timestamp(14) NOT NULL,".
            "`sp1_collection_date` date NOT NULL default '0000-00-00',".
            "`sp2_collection_date` date NOT NULL default '0000-00-00',".
            "`sp3_collection_date` date NOT NULL default '0000-00-00',".
            "`sp1_appearance` varchar(10) NOT NULL default '',".
            "`sp2_appearance` varchar(10) NOT NULL default '',".
            "`sp3_appearance` varchar(10) NOT NULL default '',".
            "`sp1_reading` varchar(10) NOT NULL default '',".
            "`sp2_reading` varchar(10) NOT NULL default '',".
            "`sp3_reading` varchar(10) NOT NULL default '',".
	    "`sp3_reading` varchar(10) NOT NULL default '',".
            "`lab_diagnosis` varchar(10) NOT NULL default '',".
            "`user_id` float NOT NULL default '0',".
            "`release_flag` char(1) NOT NULL default '',".
            "PRIMARY KEY  (`request_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_user` (`user_id`),".
            "CONSTRAINT `m_consult_lab_sputum_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_lab_sputum_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_lab_sputum_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `m_consult_lab` (`request_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");
            
        
        module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_sputum_appearance` (
          `sputum_appearance_code` varchar(4) NOT NULL,
            `sputum_appearance_name` text NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");                        
            
        module::execsql("INSERT INTO `m_lib_sputum_appearance` (`sputum_appearance_code`, `sputum_appearance_name`) VALUES
            ('BS', 'Blood-Stained'),('MP', 'Mucopurulent'),('MC', 'Mucoid'),('SA', 'Salivary'),('QNS', 'Inadequate Specimen');");
            
        module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_sputum_reading` (
          `sputum_reading_code` varchar(5) NOT NULL,
            `sputum_reading_label` text NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
                        
        module::execsql("INSERT INTO `m_lib_sputum_reading` (`sputum_reading_code`, `sputum_reading_label`) VALUES
('Z', 'Zero'),('PN', '+N'),('1P', '1+'),('2P', '2+'),('3P', '3+'),('+1', '+1'),('+2', '+2'),
('+3', '+3'),('+4', '+4'),('+5', '+5'),('+6', '+6'),('+7', '+7'),('+8', '+8');");
            
            
        module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_sputum_period` (
          `period_code` varchar(5) NOT NULL,
            `period_label` text NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
            
            
        module::execsql("INSERT INTO `m_lib_sputum_period` (`period_code`, `period_label`) VALUES
        ('DX', 'Before Treatment'),('E02', 'End of 2nd Month'),('E03', 'End of 3rd Month'),('E04', 'End of 4th Month'),
        ('E05', 'End of 5th Month'),('7M', 'After 7th Month');");

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_consult_lab_sputum`;");
        module::execsql("DROP TABLE `m_lib_sputum_appearance`;");
        module::execsql("DROP TABLE `m_lib_sputum_reading`;");        
        module::execsql("DROP TABLE `m_lib_sputum_period`;");        
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_lab_sputum_results() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
                
        $sql = mysql_query("select l.request_id, l.request_user_id, l.request_done, ".
               "date_format(l.request_timestamp, '%a %d %b %Y, %h:%i%p') request_timestamp, ".
               "s.consult_id, s.patient_id, done_user_id, ".
               "if(l.done_timestamp<>'00000000000000', date_format(l.done_timestamp, '%a %d %b %Y, %h:%i%p'), 'NA') done_timestamp, ".
               "if(l.request_done='Y', (unix_timestamp(l.done_timestamp)-unix_timestamp(l.request_timestamp))/3600,(unix_timestamp(sysdate())-unix_timestamp(l.request_timestamp))/3600) elapsed, ".
               "s.sp1_collection_date, s.sp2_collection_date, s.sp3_collection_date, ".
               "s.sp1_appearance, s.sp2_appearance, s.sp3_appearance, ".
               "s.sp1_reading, s.sp2_reading, s.sp3_reading, s.lab_diagnosis, ".
               "s.user_id, s.request_id, s.release_flag, s.sputum_period,s.lab_diag1,s.lab_diag2,s.lab_diag3 ".
               "from m_consult_lab_sputum s, m_consult_lab l ".
               "where l.request_id = s.request_id and ".
               "s.request_id = '".$get_vars["request_id"]."'") or die("Cannot query ".mysql_error());
               
              
        if ($sql) {
            if (mysql_num_rows($sql)) {
                $sputum = mysql_fetch_array($sql);
                
                $res1 = ((!empty($sputum["lab_diag1"])?(($sputum["lab_diag1"]=="P")?"Positive":(($sputum["lab_diag1"]=="N")?"Negative":"Doubtful")):"No diagnosis yet"));
                $res2 = ((!empty($sputum["lab_diag2"])?(($sputum["lab_diag2"]=="P")?"Positive":(($sputum["lab_diag2"]=="N")?"Negative":"Doubtful")):"No diagnosis yet"));          
                $res3 = ((!empty($sputum["lab_diag3"])?(($sputum["lab_diag3"]=="P")?"Positive":(($sputum["lab_diag3"]=="N")?"Negative":"Doubtful")):"No diagnosis yet"));
                
                
                print "<a name='sputum_result'>";
                print "<table style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "<b>SPUTUM RESULTS FOR ".strtoupper(patient::get_name($sputum["patient_id"]))."</b><br/>";
                print "REQUEST ID: <font color='red'>".module::pad_zero($sputum["request_id"],7)."</font><br/>";
                print "DATE REQUESTED: ".$sputum["request_timestamp"]."<br/>";
                print "REQUESTED BY: ".user::get_username($sputum["request_user_id"])."<br/>";
                print "DATE COMPLETED: ".$sputum["done_timestamp"]."<br/>";
                print "PROCESSED BY: ".($sputum["done_user_id"]?user::get_username($sputum["done_user_id"]):"NA")."<br/>";
                print "HOURS ELAPSED: ".$sputum["elapsed"]."<br/>";
                print "RELEASED: ".$sputum["release_flag"]."<br/>";
                print "<hr size='1'/>";
                print "SPUTUM EXAM PERIOD:<br/> ";
                print "&nbsp;&nbsp;".sputum::get_sputum_period_name($sputum["sputum_period"])."<br/>";
                print "<hr size='1'/>";
                print "SPECIMEN COLLECTION DATES - DIAGNOSIS<br/>";
                print "sp #1: ".$sputum["sp1_collection_date"]." - ".$res1."<br/>";
                print "sp #2: ".$sputum["sp2_collection_date"]." - ".$res2."<br/>";
                print "sp #3: ".$sputum["sp3_collection_date"]." - ".$res3."<br/>";
                print "<hr size='1'/>";
                print "SPECIMEN VISUAL APPEARANCE:<br/>";
                print "sp #1: ".sputum::get_sputum_appearance_name($sputum["sp1_appearance"])."<br/>";
                print "sp #2: ".sputum::get_sputum_appearance_name($sputum["sp2_appearance"])."<br/>";
                print "sp #3: ".sputum::get_sputum_appearance_name($sputum["sp3_appearance"])."<br/>";
                print "<hr size='1'/>";
                print "SPECIMEN READING:<br/>";
                print "sp #1: ".sputum::get_sputum_reading_name($sputum["sp1_reading"])."<br/>";
                print "sp #2: ".sputum::get_sputum_reading_name($sputum["sp2_reading"])."<br/>";
                print "sp #3: ".sputum::get_sputum_reading_name($sputum["sp3_reading"])."<br/>";
                print "<hr size='1'/>";
                print "FINAL LAB DIAGNOSIS: ".sputum::get_diagnosis_name($sputum["lab_diagnosis"])."<br/>";
                print "</span>";
                print "</td></tr></table>";
            }
            
        }
        
        
    }

    function _consult_lab_sputum() {
    //
    // main submodule for sputum
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('sputum')) {
            return print($exitinfo);
        }
        $s = new sputum;
        if ($post_vars["submitlab"]) {
            $s->process_consult_lab_sputum($menu_id, $post_vars, $get_vars);
        }
        $s->form_consult_lab_sputum($menu_id, $post_vars, $get_vars);
    }

    function form_consult_lab_sputum() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        echo "<a name='sputum_form'></a>";
        
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=sputum&request_id=".$get_vars["request_id"]."&lab_id=SPT". "&ptmenu=LABS' name='form_lab' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_LAB_EXAM_FORM."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAB_REQUEST_DETAILS."</span><br> ";
        $sql = "select lab_id, consult_id, date_format(request_timestamp, '%a %d %b %Y, %h:%i%p') request_timestamp, request_user_id, request_done, ".
               "date_format(done_timestamp, '%a %d %b %Y, %h:%i%p') done_timestamp, done_user_id ".
               "from m_consult_lab ".
               "where request_id = '".$get_vars["request_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $lab = mysql_fetch_array($result);
                print "<table width='250' bgcolor='#FFFF99' style='border: 1px solid black'><tr><td class='tinylight'>";
                print "<b>".LBL_LAB_EXAM.":</b> ".lab::get_lab_name($lab["lab_id"])."<br/>";
                print "<b>".LBL_DATE_REQUESTED.":</b> ".$lab["request_timestamp"]."<br/>";
                print "<b>".LBL_REQUESTED_BY.":</b> ".user::get_username($lab["request_user_id"])."<br/>";
                print "<b>".LBL_DATE_PROCESSED.":</b> ".($lab["done_timestamp"]?$lab["done_timestamp"]:"NA")."<br/>";
                print "<b>".LBL_PROCESSED_BY.":</b> ".($lab["done_user_id"]?user::get_username($lab["done_user_id"]):"NA")."<br/>";
                print "</td></tr></table>";
            }
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        if ($get_vars["request_id"]) {
            $sql_sputum = "select sp1_collection_date, sp2_collection_date, sp3_collection_date, ".
                          "sp1_appearance, sp2_appearance, sp3_appearance, ".
                          "sp1_reading, sp2_reading, sp3_reading, lab_diag1, lab_diag2, lab_diag3, lab_diagnosis, release_flag, sputum_period ".
                          "from m_consult_lab_sputum ".
                          "where request_id = '".$get_vars["request_id"]."'";
            if ($result_sputum = mysql_query($sql_sputum)) {
                if (mysql_num_rows($result_sputum)) {
                    $sputum = mysql_fetch_array($result_sputum);
                    //print_r($sputum);
                    // set up collection dates
                    if ($sputum["sp1_collection_date"]<>"0000-00-00") {
                        list($year,$month,$day) = explode("-",$sputum["sp1_collection_date"]);
                        $sp1_collection_date = "$month/$day/$year";
                    }
                    if ($sputum["sp2_collection_date"]<>"0000-00-00") {
                        list($year,$month,$day) = explode("-",$sputum["sp2_collection_date"]);
                        $sp2_collection_date = "$month/$day/$year";
                    }
                    if ($sputum["sp3_collection_date"]<>"0000-00-00") {
                        list($year,$month,$day) = explode("-",$sputum["sp3_collection_date"]);
                        $sp3_collection_date = "$month/$day/$year";
                    }
                }
            }
        }
        print "<table width='250' style='border: 1px dotted black'>";
        print "<tr><td class='boxtitle'>".LBL_SPECIMEN."</td><td class='boxtitle'>#1</td><td class='boxtitle'>#2</td><td class='boxtitle'>#3</td></tr>";
        print "<tr><td class='boxtitle'nowrap>".LBL_COLLECTION_DATE."</td>";
        print "<td>";
        print "<input type='text' size='10' class='tinylight' name='sp1_collection_date' value='".($sp1_collection_date?$sp1_collection_date:$post_vars["sp1_collection_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_lab.sp1_collection_date', document.form_lab.sp1_collection_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a> ";
        print "</td><td>";
        print "<input type='text' size='10' class='tinylight' name='sp2_collection_date' value='".($sp2_collection_date?$sp2_collection_date:$post_vars["sp2_collection_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_lab.sp2_collection_date', document.form_lab.sp2_collection_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a> ";
        print "</td><td>";
        print "<input type='text' size='10' class='tinylight' name='sp3_collection_date' value='".($sp3_collection_date?$sp3_collection_date:$post_vars["sp3_collection_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_lab.sp3_collection_date', document.form_lab.sp3_collection_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a> ";
        print "</td>";
        print "</tr>";
        print "<tr><td class='boxtitle'nowrap>".LBL_VISUAL_APPEARANCE."</td>";
        print "<td>".sputum::show_sputum_appearance(($sputum["sp1_appearance"]?$sputum["sp1_appearance"]:$post_vars["sp1_appearance"]),'sp1_appearance')."</td>";
        print "<td>".sputum::show_sputum_appearance(($sputum["sp2_appearance"]?$sputum["sp2_appearance"]:$post_vars["sp2_appearance"]),'sp2_appearance')."</td>";
        print "<td>".sputum::show_sputum_appearance(($sputum["sp3_appearance"]?$sputum["sp3_appearance"]:$post_vars["sp3_appearance"]),'sp3_appearance')."</td>";
        	        
        print "</tr>";

	print "<tr><td class='boxtitle'>SPUTUM EXAM RESULT</td>";
	print sputum::show_sputum_dropdown('lab_diag1',$sputum["lab_diag1"]);
        print sputum::show_sputum_dropdown('lab_diag2',$sputum["lab_diag2"]);
        print sputum::show_sputum_dropdown('lab_diag3',$sputum["lab_diag3"]);
	print "</tr>";

        print "<tr><td class='boxtitle'>".LBL_READING."</td>";
        print "<td>".sputum::show_sputum_reading(($sputum["sp1_reading"]?$sputum["sp1_reading"]:$post_vars["sp1_reading"]),'sp1_reading')."</td>";
        print "<td>".sputum::show_sputum_reading(($sputum["sp2_reading"]?$sputum["sp2_reading"]:$post_vars["sp2_reading"]),'sp2_reading')."</td>";
        print "<td>".sputum::show_sputum_reading(($sputum["sp3_reading"]?$sputum["sp3_reading"]:$post_vars["sp3_reading"]),'sp3_reading')."</td>";
        print "</tr>";
        print "<tr><td class='boxtitle'>".LBL_SPUTUM_PERIOD."</td>";
        print "<td colspan='3'>";
        print sputum::show_sputum_period(($sputum["sputum_period"]?$sputum["sputum_period"]:$post_vars["sputum_period"]));
        print "</td>";
        print "</tr>";
        print "<tr><td class='boxtitle'>FINAL ".LBL_LAB_DIAGNOSIS."</td>";
        print "<td colspan='3'>";
        print "<select name='lab_diagnosis' class='tinylight'>";
        print "<option value=''>Select Diagnosis</option>";
        print "<option value='P' ".(($sputum["lab_diagnosis"]?$sputum["lab_diagnosis"]:$post_vars["lab_diagnosis"])=="P"?"selected":"").">Positive</option>";
        print "<option value='N' ".(($sputum["lab_diagnosis"]?$sputum["lab_diagnosis"]:$post_vars["lab_diagnosis"])=="N"?"selected":"").">Negative</option>";
        print "<option value='D' ".(($sputum["lab_diagnosis"]?$sputum["lab_diagnosis"]:$post_vars["lab_diagnosis"])=="D"?"selected":"").">Doubtful</option>";
        print "</select>";
        print "</td>";
        print "</tr>";
        print "</table>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_RELEASE_FLAG."</span><br> ";
        print "<input type='checkbox' name='release_flag' ".(($sputum["release_flag"]?$sputum["release_flag"]:$post_vars["release_flag"])=="Y"?"checked":"")." value='1'/> ".INSTR_RELEASE_FLAG."<br />";
        print "</td></tr>";
        print "<tr><td align='center'>";
        if ($get_vars["request_id"]) {
            print "<input type='hidden' name='request_id' value='".$get_vars["request_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_consult_lab_sputum() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        
        //print_r($arg_list);
        
        if ($post_vars["submitlab"]) {
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            switch($post_vars["submitlab"]) {
            case "Update Lab Exam":
                // enforce transaction
                // specimen 1
                if ($post_vars["sp1_collection_date"]) {
                    list($month,$day,$year) = explode("/", $post_vars["sp1_collection_date"]);
                    $sp1_collection_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                }
                // specimen 2
                if ($post_vars["sp2_collection_date"]) {
                    list($month,$day,$year) = explode("/", $post_vars["sp2_collection_date"]);
                    $sp2_collection_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                }
                // specimen 3
                if ($post_vars["sp3_collection_date"]) {
                    list($month,$day,$year) = explode("/", $post_vars["sp3_collection_date"]);
                    $sp3_collection_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                }

                $release_flag = ($post_vars["release_flag"]?"Y":"N");
                mysql_query("SET autocommit=0;") or die(mysql_error());
                mysql_query("START TRANSACTION;") or die(mysql_error());


                if ($release_flag=="Y") {
                    if(empty($_POST["lab_diagnosis"])):
                        echo "<script language='Javascript'>";
                        echo "window.alert('Cannot close / release sputum exam yet. Please indicate LAB DIAGNOSIS!')";                        
                        echo "</script>";
                        
                    elseif(empty($_POST["sputum_period"])):
                        echo "<script language='Javascript'>"; 
                        echo "window.alert('Cannot close / release sputum exam yet.  Please indicate PERIOD OF SPUTUM EXAMS!')"; 
                        echo "</script>";
                                                                
                    else:
                    
                    $sql = "update m_consult_lab set ".
                           "done_timestamp = sysdate(), ".
                           "request_done = 'Y', ".
                           "done_user_id = '".$_SESSION["userid"]."' ".
                           "where request_id = '".$post_vars["request_id"]."'";
                    if ($result = mysql_query($sql)) {
                        // successful.. so just go to next SQL statement in
                        // transaction set
                    } else {
                        mysql_query("ROLLBACK;") or die(mysql_error());
                        mysql_query("SET autocommit=1;") or die(mysql_error());
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=".$get_vars["module"]."&request_id=".$post_vars["request_id"]."&lab_id=".$get_vars["lab_id"]."&ptmenu=LABS");
                    }
                                                
                    endif;
                }
                              
                // try insert first, will fail if previous request has been inserted
                // because of primary key constraint - then it will cascade to update below...
                
                $sql_sputum = "insert into m_consult_lab_sputum (consult_id, request_id, patient_id, ".
                              "lab_timestamp, sp1_collection_date, sp2_collection_date, sp3_collection_date, ".
                              "sp1_appearance, sp2_appearance, sp3_appearance, ".
                              "sp1_reading, sp2_reading, sp3_reading, lab_diag1, lab_diag2, lab_diag3, lab_diagnosis, sputum_period, ".
                              "user_id, release_flag) values ('".$get_vars["consult_id"]."', '".$post_vars["request_id"]."', ".
                              "'$patient_id', sysdate(), '$sp1_collection_date', '$sp2_collection_date', '$sp3_collection_date', ".
                              "'".$post_vars["sp1_appearance"]."', '".$post_vars["sp2_appearance"]."', '".$post_vars["sp3_appearance"]."', ".
                              "'".$post_vars["sp1_reading"]."', '".$post_vars["sp2_reading"]."', '".$post_vars["sp3_reading"]."', ".
                              "'".$post_vars["lab_diag1"]."', '".$post_vars["lab_diag2"]."', '".$post_vars["lab_diag3"]."', ".                              
                              "'".$post_vars["lab_diagnosis"]."', '".$post_vars["sputum_period"]."', '".$_SESSION["userid"]."', '$release_flag')";
                  
                  
                              
                if ($result_sputum = mysql_query($sql_sputum)) {
                    mysql_query("COMMIT;") or die(mysql_error());
                    mysql_query("SET autocommit=1;") or die(mysql_error());
                    //header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=".$get_vars["module"]."&request_id=".$get_vars["request_id"]."&lab_id=".$get_vars["lab_id"]."&ptmenu=LABS");
                } else {
                    $sql_update = "update m_consult_lab_sputum set ".
                                  "lab_timestamp = sysdate(), ".
                                  "sp1_collection_date = '$sp1_collection_date', ".
                                  "sp2_collection_date = '$sp2_collection_date', ".
                                  "sp3_collection_date = '$sp3_collection_date', ".
                                  "sp1_appearance = '".$post_vars["sp1_appearance"]."', ".
                                  "sp2_appearance = '".$post_vars["sp2_appearance"]."', ".
                                  "sp3_appearance = '".$post_vars["sp3_appearance"]."', ".
                                  "sp1_reading = '".$post_vars["sp1_reading"]."', ".
                                  "sp2_reading = '".$post_vars["sp2_reading"]."', ".
                                  "sp3_reading = '".$post_vars["sp3_reading"]."', ".
                                  "lab_diag1 = '".$post_vars["lab_diag1"]."', ".
                                  "lab_diag2 = '".$post_vars["lab_diag2"]."', ".                                  
                                  "lab_diag3 = '".$post_vars["lab_diag3"]."', ".                                                                    
                                  "lab_diagnosis = '".$post_vars["lab_diagnosis"]."', ".
                                  "sputum_period = '".$post_vars["sputum_period"]."', ".
                                  "user_id = '".$_SESSION["userid"]."', ".
                                  "release_flag = '$release_flag' ".
                                  "where request_id = '".$post_vars["request_id"]."'";
                    if ($result_update = mysql_query($sql_update)) {                        
                        mysql_query("COMMIT;") or die(mysql_error());
                        mysql_query("SET autocommit=1;") or die(mysql_error());
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=".$get_vars["module"]."&ptmenu=LABS"."&module=sputum"."&request_id=".$get_vars["request_id"]."#sputum_form");
                    } else {
                        mysql_query("ROLLBACK;") or die(mysql_error());
                        mysql_query("SET autocommit=1;") or die(mysql_error());
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=".$get_vars["module"]."&ptmenu=LABS"."&module=sputum"."&request_id=".$get_vars["request_id"]."#sputum_form");
                    }
                }                
                
                break;
            case "Delete Lab Exam":
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_consult_lab where request_id = '".$post_vars["request_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=".$get_vars["module"]."&ptmenu=LABS");
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=".$get_vars["module"]."&ptmenu=LABS");
                    }
                }
                break;
            }
        }
    }

    function get_sputum_results() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ntp_id = $arg_list[0];
            $ntp_period = $arg_list[1];
        }
        $sql = "SELECT l.lab_diagnosis, l.lab_timestamp ".
               "FROM m_consult_lab_sputum l, m_consult_ntp_labs_request n ".
               "where l.request_id = l.request_id and ".
               "n.ntp_id = '$ntp_id' and l.sputum_period = '$ntp_period' and l.release_flag = 'Y'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($labdx, $labts) = mysql_fetch_array($result);
                $ret_val["lab_diagnosis"] = $labdx;
                $ret_val["lab_timestamp"] = $labts;
                return $ret_val;
            }
        }
    }

    function show_sputum_period() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $period_id = $arg_list[0];
        }
        
        $ret_val = '';
        
        $q_period = mysql_query("SELECT period_code,period_label FROM m_lib_sputum_period") or die("Cannot query 494: ".mysql_query());
        
        if(mysql_num_rows($q_period)!=0):
            $ret_val .= "<select name='sputum_period' class='tinylight'>";
            $ret_val .= "<option value=''>Select Period</option>";
            
            while(list($period_code,$period_label)=mysql_fetch_array($q_period)){
                if($period_code==$period_id):
                    $ret_val .= "<option value='$period_code' selected>$period_label</option>";
                else:
                    $ret_val .= "<option value='$period_code'>$period_label</option>";                
                endif;
            }

            echo "</select>";            
        else:
            echo "<font color='red'>WARNING: library for the period is not present.</font>";
        endif;
                                
        return $ret_val;
    }

    function get_sputum_period_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $period_id = $arg_list[0];
        }
    
        switch($period_id) {
        case "DX":
            return "Before Treatment";
            break;
        case "E02":            
            return "End of 2nd Month";
            break;
        case "E03":
            return "End of 3rd Month";
            break;
        case "E04":
            return "End of 4th Month";
            break;
        case "E05":
            return "End of 5th Month";
            break;
        case "7M":
            return "After 7th Month";
            break;
        default:
            return;
            break;
        }
    }

    function show_sputum_appearance() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $appearance_id = $arg_list[0];
            $control_name = $arg_list[1];
        }
        $ret_val = '';        
        $q_sputum_appearance = mysql_query("SELECT sputum_appearance_code,sputum_appearance_name FROM m_lib_sputum_appearance") or die("Cannot query: 513".mysql_error());
        
        if(mysql_num_rows($q_sputum_appearance)!=0):
        
        $ret_val .= "<select name='$control_name' class='tinylight'>";        
        $ret_val .= "<option value=''>Select Appearance</option>";        
        
        while(list($appear_code,$appear_label) = mysql_fetch_array($q_sputum_appearance)){
            if($appear_code==$appearance_id):
                $ret_val .= "<option value='$appear_code' selected>$appear_label</option>";
            else:
                $ret_val .= "<option value='$appear_code'>$appear_label</option>";            
            endif;
            
        }

        $ret_val .= "</select>";        
                                
        else:
            echo "<font class='red'>WARNING: Library for appearance does not exists.</font>";
        endif;
        
        return $ret_val;
    }

    function show_sputum_reading() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $reading_id = $arg_list[0];
            $control_name = $arg_list[1];
        }
        
        $ret_val = '';
        
        $q_reading = mysql_query("SELECT sputum_reading_code, sputum_reading_label FROM m_lib_sputum_reading") or die("Cannot query 556".mysql_error());
        
        if(mysql_num_rows($q_reading)!=0):
                
        $ret_val .= "<select name='$control_name' class='tinylight'>";
        $ret_val .= "<option value=''>Select Reading</option>";
        
        while(list($reading_code,$reading_label) = mysql_fetch_array($q_reading)){
            if($reading_code == $reading_id):
                $ret_val .= "<option value='$reading_code' selected>$reading_label</option>";
            else:
                $ret_val .= "<option value='$reading_code'>$reading_label</option>";
            endif;
        }
                
        
        else:
            echo "<font color='red'>WARNING: library for reading not present.</font>";
        endif;
        
        return $ret_val;
    }

    function get_sputum_appearance_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $appearance_id = $arg_list[0];
        }
        switch($appearance_id) {
        case "BS":
            return "Blood-stained";
            break;
        case "MP":
            return "Mucopurulent";
            break;
        case "MC":
            return "Mucoid";
            break;
        case "SL":
            return "Saliva";
            break;
        }
    }

    function get_sputum_reading_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $appearance_id = $arg_list[0];
        }
        switch($appearance_id) {
        case "Z":
            return "Zero";
            break;
        case "PN":
            return "+N";
            break;
        case "+1":
            return "+1";
            break;
        case "+2":
            return "+2";
            break;
        case "+3":
            return "+3";
            break;
        case "+4":
            return "+4";
            break;
        case "+5":
            return "+5";
            break;
        case "+6":
            return "+6";
            break;
        case "+7":
            return "+7";
            break;
        case "+8":
            return "+8";
            break;
        case "+9":
            return "+9";
            break;        
        case "1P":
            return "1+";
            break;
        case "2P":
            return "2+";
            break;
        case "3P":
            return "3+";
            break;
        }
    }

    function get_diagnosis_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $dx_id = $arg_list[0];
        }
        switch($dx_id) {
        case "P":
            return "Positive";
            break;
        case "N":
            return "Negative";
            break;
        case "D":
            return "Doubtful";
            break;
        }
    }

    function show_sputum_dropdown(){
      if(func_num_args()>0):
	$arg_list = func_get_args();      
        $dropdown_name = $arg_list[0];
	$sputum_result_value = $arg_list[1];	
      endif;
              
        print "<td><select name='$dropdown_name' size='1' class='tinylight'>" ;
        print "<option value=''>Select Result</option>";
        print "<option value='P' ".(($sputum_result_value?$sputum_result_value:$_POST["$dropdown_name"])=="P"?"selected":"").">Positive</option>";
        print "<option value='N' ".(($sputum_result_value?$sputum_result_value:$_POST["$dropdown_name"])=="N"?"selected":"").">Negative</option>";
        //print "<option value='D' ".(($sputum_result_value?$sputum_result_value:$_POST["$dropdown_name"])=="D"?"selected":"").">Doubtful</option>";
        print "</select></td>";


    }
    
// end of class
}
?>
