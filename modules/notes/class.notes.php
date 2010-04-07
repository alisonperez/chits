<?
class notes extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // configurable notes system
    //

    function notes() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.5-".date("Y-m-d");
        $this->module = "notes";
        $this->description = "CHITS Module - Consult Notes";
        // 0.2 Fixed template system
        // 0.3 debugged system
        // 0.4 overhaul interface and templates
        // 0.5 added get_complaint_list, get_plan, get_diagnosis_list
        //      for consult_report
        // 0.6 added ICD codes to the m_lib_dxclass table
        //     displayed ICD 10 codes table after the diagnosis name
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    // use multiple inserts
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "complaint");
        module::set_dep($this->module, "template");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("LBL_NOTES_HISTORY", "english", "HISTORY", "Y");
        module::set_lang("LBL_NOTES_PHYSICALEXAM", "english", "PHYSICAL EXAM", "Y");
        module::set_lang("LBL_NOTES_PLAN", "english", "PLAN", "Y");
        module::set_lang("FTITLE_CONSULT_NOTES", "english", "CONSULT NOTES", "Y");
        module::set_lang("FTITLE_CONSULT_NOTES_ARCHIVE", "english", "CONSULT NOTES ARCHIVE", "Y");
        module::set_lang("FTITLE_NOTES_TEMPLATE_FORM", "english", "NOTES TEMPLATE FORM", "Y");
        module::set_lang("LBL_TEMPLATE_CAT", "english", "TEMPLATE CATEGORY", "Y");
        module::set_lang("LBL_TEMPLATE_NAME", "english", "TEMPLATE NAME", "Y");
        module::set_lang("LBL_TEMPLATE_TEXT", "english", "TEMPLATE TEXT", "Y");
        module::set_lang("FTITLE_NOTES_TEMPLATE_LIST", "english", "NOTES TEMPLATE LIST", "Y");
        module::set_lang("THEAD_TEMPLATE_NAME", "english", "TEMPLATE NAME", "Y");
        module::set_lang("THEAD_TEMPLATE_TEXT", "english", "TEMPLATE TEXT", "Y");
        module::set_lang("LBL_TEMPLATE_CONTENT", "english", "TEMPLATE CONTENT", "Y");
        module::set_lang("FTITLE_CONSULT_HISTORY_FORM", "english", "CONSULT HISTORY FORM", "Y");
        module::set_lang("FTITLE_CONSULT_COMPLAINT_FORM", "english", "CONSULT COMPLAINT FORM", "Y");
        module::set_lang("LBL_HISTORY_TEMPLATE", "english", "HISTORY TEMPLATE", "Y");
        module::set_lang("LBL_PE_TEMPLATE", "english", "PE TEMPLATE", "Y");
        module::set_lang("FTITLE_CONSULT_NOTES_FORM", "english", "CONSULT NOTES", "Y");
        module::set_lang("INSTR_NOTES_CREATION", "english", "Use this form to create new notes for this consult.", "Y");
        module::set_lang("LBL_NOTES_ID", "english", "NOTES ID", "Y");
        module::set_lang("LBL_NOTES_ID", "english", "NOTES ID", "Y");
        module::set_lang("LBL_NOTES_PE", "english", "PHYSICAL EXAM NOTES", "Y");
        module::set_lang("FTITLE_DXCLASS_LIST", "english", "DIAGNOSIS CLASS LIST", "Y");
        module::set_lang("THEAD_DXID", "english", "DIAGNOSIS ID", "Y");
        module::set_lang("THEAD_DXNAME", "english", "DIAGNOSIS NAME", "Y");
        module::set_lang("FTITLE_NOTES_DXCLASS_FORM", "english", "DIAGNOSIS CLASS FORM", "Y");
        module::set_lang("LBL_DXNAME", "english", "DIAGNOSIS CLASS NAME", "Y");
        module::set_lang("FTITLE_CONSULT_PE_FORM", "english", "CONSULT PE FORM", "Y");
        module::set_lang("FTITLE_CONSULT_DIAGNOSIS_FORM", "english", "CONSULT DIAGNOSIS FORM", "Y");
        module::set_lang("LBL_DIAGNOSIS", "english", "DIAGNOSIS CLASS", "Y");
        module::set_lang("FTITLE_CONSULT_PLAN_FORM", "english", "CONSULT PLAN FORM", "Y");
        module::set_lang("LBL_PLAN_TEMPLATE", "english", "PLAN TEMPLATE", "Y");

    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        // load menu
        module::set_menu($this->module, "Notes Templates", "LIBRARIES", "_notes_templates");
        module::set_menu($this->module, "Notes DX Classes", "LIBRARIES", "_notes_dxclass");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // template library for notes free text portions
        module::execsql("CREATE TABLE `m_lib_notes_template` (".
            "`template_id` float NOT NULL auto_increment,".
            "`template_cat` varchar(10) NOT NULL default '',".
            "`template_name` varchar(40) NOT NULL default '',".
            "`template_text` text NOT NULL,".
            "PRIMARY KEY  (`template_id`)".
            ") TYPE=InnoDB;");

        // diagnosis class
        // possible integration with IMCI
        module::execsql("CREATE TABLE `m_lib_notes_dxclass` (".
            "`class_id` float NOT NULL auto_increment,".
            "`class_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`class_id`)".
            ") TYPE=InnoDB; ");

        // stores free text entries for notes
        module::execsql("CREATE TABLE IF NOT EXISTS `m_consult_notes` (
	  `notes_id` float NOT NULL AUTO_INCREMENT,
	  `consult_id` float NOT NULL DEFAULT '0',
	  `patient_id` float NOT NULL DEFAULT '0',
	  `notes_complaint` text NOT NULL,
	  `notes_history` text NOT NULL,
	  `notes_physicalexam` text NOT NULL,
	  `notes_plan` text NOT NULL,
	  `user_id` float NOT NULL DEFAULT '0',
	  `notes_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `vita_date` date NOT NULL DEFAULT '0000-00-00',
	  `anemia_start_date` date NOT NULL DEFAULT '0000-00-00',
	  `anemia_completed_date` date NOT NULL DEFAULT '0000-00-00',
	  `diarrhea_ort` date NOT NULL DEFAULT '0000-00-00',
	  `diarrhea_ors` date NOT NULL DEFAULT '0000-00-00',
	  `diarrhea_orswz` date NOT NULL DEFAULT '0000-00-00',
	  `pneumonia_date_given` date NOT NULL DEFAULT '0000-00-00',
	  PRIMARY KEY (`notes_id`),
	  KEY `key_consult` (`consult_id`),
	  KEY `key_patient` (`patient_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");

        // stores diagnosis class in notes
        module::execsql("CREATE TABLE `m_consult_notes_dxclass` (".
            "`notes_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`class_id` float NOT NULL default '0',".
            "`diagnosis_date` datetime NOT NULL default '0000-00-00 00:00:00',".
            "`user_id` float NOT NULL default '0',".
            "`diagnosis_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`notes_id`,`consult_id`,`class_id`),".
            "KEY `key_notes` (`notes_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_dxclass` (`class_id`),".
            "KEY `key_consult` (`consult_id`),".
            "CONSTRAINT `m_consult_notes_dxclass_ibfk_1` FOREIGN KEY (`notes_id`) REFERENCES `m_consult_notes` (`notes_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_notes_dxclass_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_notes_dxclass_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_notes_dxclass_ibfk_4` FOREIGN KEY (`class_id`) REFERENCES `m_lib_notes_dxclass` (`class_id`)".
            ") TYPE=InnoDB; ");

        // stores complaint in notes
        module::execsql("CREATE TABLE `m_consult_notes_complaint` (".
            "`notes_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`complaint_id` varchar(10) NOT NULL default '',".
            "`complaint_date` datetime NOT NULL default '0000-00-00 00:00:00',".
            "`user_id` float NOT NULL default '0',".
            "`complaint_timestamp` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`notes_id`,`consult_id`,`complaint_id`),".
            "KEY `key_complaint` (`complaint_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_patient` (`patient_id`),".
            "CONSTRAINT `m_consult_notes_complaint_ibfk_4` FOREIGN KEY (`notes_id`) REFERENCES `m_consult_notes` (`notes_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_notes_complaint_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_notes_complaint_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_notes_complaint_ibfk_3` FOREIGN KEY (`complaint_id`) REFERENCES `m_lib_complaint` (`complaint_id`) ON UPDATE CASCADE".
            ") TYPE=InnoDB;");

    }

    function drop_tables() {

        module::execsql("set foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_consult_notes_complaint`;");
        module::execsql("DROP TABLE `m_consult_notes_dxclass`;");
        module::execsql("DROP TABLE `m_lib_notes_dxclass`;");
        module::execsql("DROP TABLE `m_lib_notes_template`;");
        module::execsql("DROP TABLE `m_consult_notes`;");
        module::execsql("set foreign_key_checks=1;");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_notes() {
    //
    // main module for notes
    // left panel
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('notes')) {
            return print($exitinfo);
        }
        $n = new notes;
        $n->notes_menu($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        
        mysql_query("ALTER TABLE `m_consult_notes` DROP PRIMARY KEY , ADD PRIMARY KEY (`notes_id`)");
        
        if ($post_vars["submitnotes"]) {
            $n->process_consult_notes($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        }
        switch($get_vars["notes"]) {
        case "NOTES":  // Create notes entry for this consult
            $n->form_consult_notes($menu_id, $post_vars, $get_vars);
            break;
        case "CC":  // Chief Complaint
            $n->form_consult_complaint($menu_id, $post_vars, $get_vars);
            break;
        case "HX":  // History
            $n->form_consult_history($menu_id, $post_vars, $get_vars);
            break;
        case "PE":  // Physical Exam
            $n->form_consult_pe($menu_id, $post_vars, $get_vars);
            break;
        case "DX":  // Diagnosis/Impression
            $n->form_consult_diagnosis($menu_id, $post_vars, $get_vars);
            break;
        case "TX":  // Plan/Intervention
            $n->form_consult_plan($menu_id, $post_vars, $get_vars);
            break;
        case "ARCH": // Archive of notes
            $n->display_notes_detail($menu_id, $post_vars, $get_vars);
            break;
        }
    }

    function notes_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["notes"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&notes=NOTES".($get_vars["notes_id"]?"&notes_id=".$get_vars["notes_id"]:"")."#menu");
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<a name='menu'></a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=NOTES".($get_vars["notes_id"]?"&notes_id=".$get_vars["notes_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="NOTES"?"<b>NOTES</b>":"NOTES"))."</a>";
        if ($get_vars["notes_id"]) {
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=CC&notes_id=".$get_vars["notes_id"]."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="CC"?"<b>CC</b>":"CC"))."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=HX&notes_id=".$get_vars["notes_id"]."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="HX"?"<b>HX</b>":"HX"))."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=PE&notes_id=".$get_vars["notes_id"]."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="PE"?"<b>PE</b>":"PE"))."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=DX&notes_id=".$get_vars["notes_id"]."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="DX"?"<b>DX</b>":"DX"))."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=TX&notes_id=".$get_vars["notes_id"]."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="TX"?"<b>TX</b>":"TX"))."</a>";
        }
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=ARCH".($get_vars["notes_id"]?"&notes_id=".$get_vars["notes_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["notes"]=="ARCH"?"<b>ARCHIVE</b>":"ARCHIVE"))."</a>";
        print "</td></tr></table><br/>";
    }

    function display_notes_detail() {
    }
    
    function process_consult_notes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $consult_date = healthcenter::get_consult_date($get_vars["consult_id"]);
        switch($post_vars["submitnotes"]) {
        case "Create Notes":
            $sql = "insert into m_consult_notes (consult_id, patient_id, user_id, notes_timestamp) ".
                   "values ('".$get_vars["consult_id"]."', '$patient_id', '".$_SESSION["userid"]."', sysdate())";
            if ($result = mysql_query($sql)) {
                $insert_id = mysql_insert_id();
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=NOTES&notes_id=$insert_id#menu");
            }
            break;
        case "Save Complaint":
            if ($post_vars["complaintcat"]) {
                foreach ($post_vars["complaintcat"] as $key=>$value) {
                    $sql = "insert into m_consult_notes_complaint (notes_id, consult_id, patient_id, complaint_id, complaint_date, user_id, complaint_timestamp) ".
                           "values ('".$get_vars["notes_id"]."', '".$get_vars["consult_id"]."', '$patient_id', '$value', '$consult_date', '".$_SESSION["userid"]."', sysdate())";
                    $result = mysql_query($sql);
                }                                                
            }
                $update_complaint_note = mysql_query("UPDATE m_consult_notes SET notes_complaint='$post_vars[complaint_notes]' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 302 ".mysql_error());                                        
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=CC&notes_id=".$get_vars["notes_id"]."#menu");                
            break;
            
        case "Save History":
            if ($post_vars["history_text"]) {
                $sql = "update m_consult_notes set ".
                       "notes_history = '".addslashes($post_vars["history_text"])."' ".
                       "where notes_id = '".$get_vars["notes_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=HX&notes_id=".$get_vars["notes_id"]."#menu");
                }
            }
            break;
        case "Save PE":
            if ($post_vars["pe_text"]) {
                $sql = "update m_consult_notes set ".
                       "notes_physicalexam = '".addslashes($post_vars["pe_text"])."' ".
                       "where notes_id = '".$get_vars["notes_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=PE&notes_id=".$get_vars["notes_id"]."#menu");
                }
            }
            break;
        case "Save Diagnosis Class":
            if ($post_vars["dxclass"]) {
                foreach($post_vars["dxclass"] as $key=>$value) {

                    $sql = "insert into m_consult_notes_dxclass (notes_id, consult_id, patient_id, class_id, diagnosis_date, user_id, diagnosis_timestamp) ".
                           "values ('".$get_vars["notes_id"]."', '".$get_vars["consult_id"]."', '$patient_id', '$value', '$consult_date', '".$_SESSION["userid"]."', sysdate())";
                    $result = mysql_query($sql) or die(mysql_error());
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=DX&notes_id=".$get_vars["notes_id"]."#menu");
            }

            break;
        case "Save Plan":
            if ($post_vars["plan_text"]) {
                $sql = "update m_consult_notes set ".
                       "notes_plan = '".addslashes($post_vars["plan_text"])."' ".
                       "where notes_id = '".$get_vars["notes_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=TX&notes_id=".$get_vars["notes_id"]."#menu");
                }
            }
            break;
        }
    }

    function form_consult_notes() {
    //
    // create new notes entry for this consult
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
		
		$sql_notes = mysql_query("SELECT notes_id FROM m_consult_notes WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 357");
		
		if(mysql_num_rows($sql_notes)==0):

        print "<a name='notes_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#notes_form' name='form_notes'>";

	    print "<tr><td>";
		print "<b>".FTITLE_CONSULT_NOTES_FORM."</b><br/>";
		print "<br/></td></tr>";
		print "<tr><td>";
		print "<b>".INSTR_NOTES_CREATION."</b><br/>";
		print "<br/></td></tr>";
		print "<tr><td>";
		print "<input type='submit' name='submitnotes' value='Create Notes' class='textbox' style='border: 1px solid black'/> ";
		print "<br/></td></tr>";
		
        print "</form>";
        print "</table><br/>";
		
		else:
			echo "<font color='red' size='2'>Please click the consult date on the right to <br> view details of notes.</font>";
		endif;


    }

    function form_consult_complaint() {
    //
    // create complaint section of notes
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
        print "<a name='notes_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#notes_form' name='form_notes'>";
        print "<tr><td>";
        print "<b>".FTITLE_CONSULT_COMPLAINT_FORM."</b><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_ID."</span><br/>";
        print "<font color='red'>".module::pad_zero($get_vars["notes_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        
        print "<tr><td class='boxtitle'>COMPLAINT NOTES<br>";
        
        echo "<textarea name='complaint_notes' rows='4' cols='32'>";
        echo $this->get_consult_note($get_vars["consult_id"]);           
        echo "</textarea></td></tr>";    
                        
        
        print "<tr><td>";        
        print "<span class='boxtitle'>".LBL_COMPLAINT."</span><br/>";
        print complaint::checkbox_complaintcat();
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<input type='submit' name='submitnotes' value='Save Complaint' class='textbox' style='border: 1px solid black'/> ";
        print "<br/></td></tr>";
        print "</form>";
        print "</table><br/>";

    }

    function form_consult_history() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select notes_history from m_consult_notes where notes_id = '".$get_vars["notes_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($history) = mysql_fetch_array($result);
            }
        }
        print "<a name='history_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#history_form' name='form_history'>";
        print "<tr><td>";
        print "<b>".FTITLE_CONSULT_HISTORY_FORM."</b><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_ID."</span><br/>";
        print "<font color='red'>".module::pad_zero($get_vars["notes_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_HISTORY_TEMPLATE."</span><br/>";
        notes::show_templates("HX", $post_vars["template"]);
        if ($post_vars["template"]) {
            print "<br/><br/>";
            print "<span class='boxtitle'>".LBL_TEMPLATE_CONTENT."</span><br> ";
            print "<table width='200' style='border: 1px dotted black'><tr><td class='tinylight'>";
            $history_text = notes::get_template_text($post_vars["template"]);
            print stripslashes(nl2br($history_text));
            print "<br/>";
            print "<input type='hidden' name='template_text' value='$history_text' />";
            print "<input type='hidden' name='prev_template' value='".$post_vars["template"]."' class='tinylight' style='border: 1px solid black; background-color: #CCFF33'/>";
            print "<input type='submit' name='submitnotes' value='Add Template' class='tinylight' style='border: 1px solid black; background-color: #CCFF33'/>";
            print "</td></tr></table>";
        }
        if ($post_vars["submitnotes"]=="Add Template") {
            $history = $post_vars["history_text"].$post_vars["template_text"];
        }
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_HISTORY."</span><br> ";
        print "<textarea rows='15' cols='40' class='tinylight' name='history_text' style='border: 1px solid black'>".($history?$history:$post_vars["history_text"])."</textarea>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<input type='submit' name='submitnotes' value='Save History' class='textbox' style='border: 1px solid black'/> ";
        print "<br/></td></tr>";
        print "</form>";
        print "</table><br/>";
    }

    function form_consult_pe() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select notes_physicalexam from m_consult_notes where notes_id = '".$get_vars["notes_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($pe) = mysql_fetch_array($result);
            }
        }
        print "<a name='pe_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#pe_form' name='form_pe'>";
        print "<tr><td>";
        print "<b>".FTITLE_CONSULT_PE_FORM."</b><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_ID."</span><br/>";
        print "<font color='red'>".module::pad_zero($get_vars["notes_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_PE_TEMPLATE."</span><br/>";
        notes::show_templates("PE", $post_vars["template"]);
        if ($post_vars["template"]) {
            print "<br/><br/>";
            print "<span class='boxtitle'>".LBL_TEMPLATE_CONTENT."</span><br> ";
            print "<table width='200' style='border: 1px dotted black'><tr><td class='tinylight'>";
            $pe_text = notes::get_template_text($post_vars["template"]);
            print stripslashes(nl2br($pe_text));
            print "<br/>";
            print "<input type='hidden' name='template_text' value='$pe_text' />";
            print "<input type='hidden' name='prev_template' value='".$post_vars["template"]."' class='tinylight' style='border: 1px solid black; background-color: #CCFF33'/>";
            print "<input type='submit' name='submitnotes' value='Add Template' class='tinylight' style='border: 1px solid black; background-color: #CCFF33'/>";
            print "</td></tr></table>";
        }
        if ($post_vars["submitnotes"]=="Add Template") {
            $pe = $post_vars["pe_text"].$post_vars["template_text"];
        }
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_PE."</span><br> ";
        print "<textarea rows='15' cols='40' class='tinylight' name='pe_text' style='border: 1px solid black'>".($pe?$pe:$post_vars["pe_text"])."</textarea>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<input type='submit' name='submitnotes' value='Save PE' class='textbox' style='border: 1px solid black'/> ";
        print "<br/></td></tr>";
        print "</form>";
        print "</table><br/>";
    }

    function form_consult_diagnosis() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<a name='dx_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#dx_form' name='form_diagnosis'>";
        print "<tr><td>";
        print "<b>".FTITLE_CONSULT_DIAGNOSIS_FORM."</b><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_ID."</span><br/>";
        print "<font color='red'>".module::pad_zero($get_vars["notes_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        
		//print "<span class='boxtitle'>".LBL_DIAGNOSIS."</span><br/>";        
		//print notes::show_dxclass_selection();
		
		$this->show_dxsearch_box();
        
		print "<br/></td></tr>";
        print "<tr><td>";
        

		$this->search_diag_term($menu_id,$post_vars,$get_vars,$validuser,$isadmin);

		//print "<input type='submit' name='submitnotes' value='Save Diagnosis Class' class='textbox' style='border: 1px solid black'/> ";


        print "<br/></td></tr>";
        print "</form>";
        print "</table><br/>";
    }

    function form_consult_plan() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        
		$get_diag = mysql_query("SELECT a.class_id FROM m_consult_notes_dxclass a, m_consult_notes b WHERE a.notes_id='$get_vars[notes_id]' AND a.notes_id=b.notes_id") or die("Cannot query: 572");
		
		
		if(mysql_num_rows($get_diag)==0):
			echo "<font color='red'>Please record the diagnosis first before doing treament plan.</font>";
		
		else:

		$sql = "select notes_plan from m_consult_notes where notes_id = '".$get_vars["notes_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($plan) = mysql_fetch_array($result);
            }
        }		
        print "<a name='plan_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#plan_form' name='form_plan'>";
        print "<tr><td>";
        print "<b>".FTITLE_CONSULT_PLAN_FORM."</b><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_ID."</span><br/>";
        print "<font color='red'>".module::pad_zero($get_vars["notes_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_PLAN_TEMPLATE."</span><br/>";
        notes::show_templates("TX", $post_vars["template"]);
        if ($post_vars["template"]) {
            print "<br/><br/>";
            print "<span class='boxtitle'>".LBL_TEMPLATE_CONTENT."</span><br> ";
            print "<table width='200' style='border: 1px dotted black'><tr><td class='tinylight'>";
            $plan_text = notes::get_template_text($post_vars["template"]);
            print stripslashes(nl2br($plan_text));
            print "<br/>";
            print "<input type='hidden' name='template_text' value='$plan_text' />";
            print "<input type='hidden' name='prev_template' value='".$post_vars["template"]."' class='tinylight' style='border: 1px solid black; background-color: #CCFF33'/>";
            print "<input type='submit' name='submitnotes' value='Add Template' class='tinylight' style='border: 1px solid black; background-color: #CCFF33'/>";
            print "</td></tr></table>";
        }
        if ($post_vars["submitnotes"]=="Add Template") {
            $plan = $post_vars["plan_text"].$post_vars["template_text"];
        }
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<span class='boxtitle'>".LBL_NOTES_PLAN."</span><br> ";
        print "<textarea rows='10' cols='40' class='tinylight' name='plan_text' style='border: 1px solid black'>".($plan?$plan:$post_vars["plan_text"])."</textarea>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<input type='submit' name='submitnotes' value='Save Plan' class='textbox' style='border: 1px solid black'/> ";
        print "<br/></td></tr>";
        print "</form>";
        print "</table><br/>";

		$this->form_tx_sickchild($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
		
		endif;
    }

    function _details_notes() {
    //
    // main module for notes
    // right panel
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('notes')) {
            return print($exitinfo);
        }
        $n = new notes;
        if ($post_vars["submitdetail"]) {
            $n->process_detail($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        }
        switch($get_vars["notes"]) {
        case "NOTES":  // Notes details
        case "HX":  // History
        case "PE":  // Physical Exam
        case "DX":  // Diagnosis/Impression
        case "TX":  // Plan/Intervention
        case "CC":  // Chief Complaint
            $n->display_consult_notes($menu_id, $post_vars, $get_vars);
        case "ARCH": // Archive of notes
            //$n->display_consult_notes($menu_id, $post_vars, $get_vars);
            break;
        default:
            //$n->display_complaint($menu_id, $post_vars, $get_vars);
            break;
        }
    }

    function display_consult_notes() {
    //
    // lists notes generated for this
    //  consult alone
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<b>".FTITLE_CONSULT_NOTES_ARCHIVE."</b><br>";
        /*$sql_list = "select notes_id, date_format(notes_timestamp, '%a %d %b %Y, %h:%i%p') ts ".
                    "from m_consult_notes where consult_id = '".$get_vars["consult_id"]."'";
		*/
		$sql_list = "select a.notes_id, date_format(b.consult_date, '%a %d %b %Y') ts ".
                    "from m_consult_notes a, m_consult b where a.consult_id=b.consult_id AND a.consult_id = '".$get_vars["consult_id"]."'";
        if ($result_list = mysql_query($sql_list)) {
            if (mysql_num_rows($result_list)) {
                while (list($id, $ts) = mysql_fetch_array($result_list)) {
                    echo "<form method='post' name='form_consult_date'>";
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&notes_id=$id'>$ts</a>";

                    if($_SESSION["priv_update"]):
                        echo "&nbsp;&nbsp;<input type='submit' name='submitdetail' value='Edit Date' class='tinylight' style='border: 1px solid black'></input><br>";
                        $this->process_consultation_dates($menu_id, $post_vars, $get_vars);
                    endif;
                    
                    echo "</form>";

                    if ($get_vars["notes_id"]==$id) {
                        notes::display_consult_notes_detail($menu_id, $post_vars, $get_vars);
                    }					
                }
            } else {
                print "<font color='red'>No recorded notes for this consult.</font><br/>";
            }
        }
    }

    function display_consult_notes_detail() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        // do some processing here
        if ($get_vars["delete_complaint_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_notes_complaint ".
                       "where notes_id = '".$get_vars["notes_id"]."' and ".
                       "consult_id = '".$get_vars["consult_id"]."' and ".
                       "complaint_id = '".$get_vars["delete_complaint_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            }
        }
        if ($get_vars["delete_class_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_notes_dxclass ".
                       "where notes_id = '".$get_vars["notes_id"]."' and ".
                       "consult_id = '".$get_vars["consult_id"]."' and ".
                       "class_id = '".$get_vars["delete_class_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            }
        }
        // continue with real task
        $sql = "select notes_id, consult_id, notes_history, ".
               "notes_physicalexam, notes_plan, user_id, date_format(notes_timestamp, '%a %d %b %Y, %h:%i%p') ts ".
               "from m_consult_notes where consult_id = '".$get_vars["consult_id"]."' and ".
               "notes_id = '".$get_vars["notes_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $notes = mysql_fetch_array($result);
                print "<form method='post' action=''>";
                print "<table width='300' cellpadding='2' style='border: 1px dashed black'><tr><td>";
                print "<span class='tinylight'>";
                print "<b>NOTES ID:</b> <font color='red'>".module::pad_zero($notes["notes_id"],7)."</font><br/>";
                print "<b>DATE/TIME:</b> ".$notes["ts"]."<br/>";					
                print "<b>TAKEN BY:</b> ".user::get_username($notes["user_id"])."<br/>";
                print "<b>VITAL SIGNS:</b>".$this->get_vitals($_GET["consult_id"])."<br/>";
                print "<hr size='1'/>";
                print "<b>COMPLAINTS:</b><br/>";
                notes::show_complaints($menu_id, $post_vars, $get_vars);
                print "<hr size='1'/>";
                print "<b>COMPLAINT NOTES:</b><br/>";
                print $this->get_consult_note($get_vars["consult_id"]);
                print "<hr size='1'/>";
                print "<b>HISTORY:</b><br/>";
                if (strlen($notes["notes_history"])>0) {
                    print stripslashes(nl2br($notes["notes_history"]))."<br/>";
                    if ($_SESSION["priv_update"]) {
                        print "<br/>";
                        print "<input type='submit' name='submitdetail' value='Update History' class='tinylight' style='border: 1px solid black'";
                    }
                } else {
                    print "<font color='red'>No recorded history.</font><br/>";
                }
                print "<hr size='1'/>";
                print "<b>PHYSICAL EXAM:</b><br/>";
                if (strlen($notes["notes_physicalexam"])>0) {
                    print stripslashes(nl2br($notes["notes_physicalexam"]))."<br/>";
                    if ($_SESSION["priv_update"]) {
                        print "<br/>";
                        print "<input type='submit' name='submitdetail' value='Update PE' class='tinylight' style='border: 1px solid black'";
                    }
                } else {
                    print "<font color='red'>No recorded PE.</font><br/>";
                }
                print "<hr size='1'/>";
                print "<b>DIAGNOSIS:</b><br/>";
                notes::show_diagnosis($menu_id, $post_vars, $get_vars);
                print "<hr size='1'/>";
                print "<b>PLAN:</b><br/>";
                if (strlen($notes["notes_plan"])>0) {
                    print stripslashes(nl2br($notes["notes_plan"]))."<br/>";
                    if ($_SESSION["priv_update"]) {
                        print "<br/>";
                        print "<input type='submit' name='submitdetail' value='Update Plan' class='tinylight' style='border: 1px solid black'";
                    }
                } else {
                    print "<font color='red'>No recorded plan.</font><br/>";
                }
                print "<hr size='1'/>";
				

                print "<input type='hidden' name='notes_id' value='".$get_vars["notes_id"]."' />";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdetail' value='Delete Notes' class='tinylight' style='border: 1px solid black; background-color: #FF6633;'/> ";
                }
                print "</span>";
                
                print "</td></tr></table><br>";
                print "</form>";
            }
        }
    }

    function process_detail() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch ($post_vars["submitdetail"]) {
        case "Update History":
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=HX&notes_id=".$get_vars["notes_id"]);
            break;
        case "Update PE":
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=PE&notes_id=".$get_vars["notes_id"]);
            break;
        case "Update Plan":
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=TX&notes_id=".$get_vars["notes_id"]);
            break;
        case "Delete Notes":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_notes where notes_id = '".$post_vars["notes_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=".$get_vars["notes"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=".$get_vars["notes"]);
                }
            }
            break;
		

		default:
			break;
        }
    }
	
	function process_consultation_dates(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
		endif;
		
		switch($post_vars["submitdetail"]){
		
		case "Edit Date":
			$q_date = mysql_query("SELECT date_format(consult_date,'%m/%d/%Y') cons_date FROM m_consult WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 849");
			
			list($r_date) = mysql_fetch_array($q_date);
			
			echo "<span class='tinylight'>ACTUAL CONSULT DATE&nbsp;<input type='text' size='7' value='$r_date' name='txt_consult_date' readonly></input></span>";
			
			echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_consult_date.txt_consult_date', document.form_consult_date.txt_consult_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";

			echo "<br><input type='submit' name='submitdetail' value='Save Date' class='tinylight' style='border: 1px solid black'></input>&nbsp;<input type='button' name='consult_date_cancel' value='Cancel' class='tinylight' style='border: 1px solid black' onclick='history.go(-1)'></input>";
			break;
		
		case "Save Date":
			
			$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
			list($m,$d,$y) = explode('/',$post_vars["txt_consult_date"]);
			$date_consult = $y.'-'.$m.'-'.$d.' 00:00:00';
			$date_con = $y.'-'.$m.'-'.$d;

			$q_px = mysql_query("SELECT patient_id FROM m_patient WHERE patient_id='$patient_id' AND (TO_DAYS('$date_con')-TO_DAYS(patient_dob)) >= 0") or die("Cannot query: 865"); 

			if(mysql_num_rows($q_px)==0):
				echo "<font color='red'>Consultation date should be after patient's birthday.</font>";
			else:
				$update_consult_date = mysql_query("UPDATE m_consult SET consult_date='$date_consult' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 864");
	            
				header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);

			endif;
			break;

		default:
			break;
		}
	}
    
	
	function show_complaints() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $sql = "select c.complaint_id, l.complaint_name ".
               "from m_consult_notes_complaint c, m_lib_complaint l ".
               "where c.complaint_id = l.complaint_id and ".
               "consult_id = '".$get_vars["consult_id"]."' and notes_id = '".$get_vars["notes_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<span class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> $name ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]."&delete_complaint_id=$id'><img src='../images/delete.png' border='0'/></a><br/>";
                }
                print "</span>";
            } else {
                print "<font color='red'>No complaints recorded</font><br/>";
            }
        }
    }

    function show_diagnosis() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $sql = "select c.class_id, l.class_name ".
               "from m_consult_notes_dxclass c, m_lib_notes_dxclass l ".
               "where c.class_id = l.class_id and ".
               "consult_id = '".$get_vars["consult_id"]."' and notes_id = '".$get_vars["notes_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<span class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> $name ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]."&delete_class_id=$id'><img src='../images/delete.png' border='0'/></a><br/>";
                }
                print "</span>";
            } else {
                print "<font color='red'>No diagnosis class recorded</font><br/>";
            }
        }
    }

    function get_plan() {
    //
    // get diagnosis list for given date and patient
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $consult_date = $arg_list[1];
        }
        $sql = "select n.notes_plan ".
               "from m_consult_notes n, m_consult c ".
               "where n.consult_id = c.consult_id and ".
               "n.patient_id = '$patient_id' and to_days(c.consult_date) = to_days('$consult_date')";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($plan) = mysql_fetch_array($result);
                return $plan;
            }
        }

    }

    function get_diagnosis_list() {
    //
    // get diagnosis list for given date and patient
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $consult_date = $arg_list[1];
        }
        $sql = "select l.class_name ".
               "from m_lib_notes_dxclass l, m_consult_notes_dxclass n ".
               "where l.class_id = n.class_id and ".
               "n.patient_id = '$patient_id' and to_days(n.diagnosis_date) = to_days('$consult_date')";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($dx_name) = mysql_fetch_array($result)) {
                    $dx_list .= $dx_name.", ";
                }
                $dx_list = substr($dx_list, 0, strlen($dx_list)-2);
                return $dx_list;
            }
        }

    }

    function get_complaints() {
    //
    // get complaint list for given date and patient
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $consult_date = $arg_list[1];
        }
        $sql = "select l.complaint_name ".
               "from m_lib_complaint l, m_consult_notes_complaint n ".
               "where l.complaint_id = n.complaint_id and ".
               "n.patient_id = '$patient_id' and to_days(n.complaint_date) = to_days('$consult_date')";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($complaint_name) = mysql_fetch_array($result)) {
                    $complaint_list .= $complaint_name.", ";
                }
                $complaint_list = substr($complaint_list, 0, strlen($complaint_list)-2);
                return $complaint_list;
            }
        }

    }

    // ---------------------- LIBRARY METHODS ------------------------

    function _notes_templates() {
    //
    // main method for notes template library
    // calls form_template, process_template, display_templates
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
        if ($exitinfo = $this->missing_dependencies('notes')) {
            return print($exitinfo);
        }
        $n = new notes;
        if ($post_vars["submittemplate"]) {
            $n->process_template($menu_id, $post_vars, $get_vars);
        }
        $n->display_templates($menu_id);
        $n->form_template($menu_id, $post_vars, $get_vars);
    }

    function show_templates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $template_cat = $arg_list[0];
            $template_id = $arg_list[1];
        }
        $sql = "select template_id, template_name from m_lib_notes_template ".
               "where template_cat = '$template_cat'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<select name='template' class='textbox' onchange='this.form.submit();'>";
                print "<option value=''>Select Template</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($template_id==$id?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
    }

    function get_template_text() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $template_id = $arg_list[0];
        }
        $sql = "select template_text from m_lib_notes_template ".
               "where template_id = '$template_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($text) = mysql_fetch_array($result);
                return $text;
            }
        }
    }

    function form_template() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["template_id"]) {
                $sql = "select template_id, template_cat, template_name, template_text ".
                       "from m_lib_notes_template where template_id = '".$get_vars["template_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $template = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_template' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_NOTES_TEMPLATE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TEMPLATE_CAT."</span><br> ";
        print "<select name='template_cat' class='textbox'>";
        print "<option value=''>Select Template Category</option>";
        print "<option value='HX' ".($template["template_cat"]=="HX"?"selected":"").">History</option>";
        print "<option value='PE' ".($template["template_cat"]=="PE"?"selected":"").">Physical Exam</option>";
        print "<option value='TX' ".($template["template_cat"]=="TX"?"selected":"").">Plan</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TEMPLATE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='40' name='template_name' value='".($template["template_name"]?$template["template_name"]:$post_vars["template_name"])."' style='border: 1px solid #000000'><br>";
        print "<br></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TEMPLATE_TEXT."</span><br> ";
        print "<textarea class='tinylight' rows='15' cols='50' name='template_text'style='border: 1px solid #000000'>".stripslashes($template["template_text"]?$template["template_text"]:$post_vars["template_text"])."</textarea><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["template_id"]) {
            print "<input type='hidden' name='template_id' value='".$get_vars["template_id"]."'>";
            print "<input type='submit' value = 'Update Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_template() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submittemplate"]) {
            if ($post_vars["template_cat"] && $post_vars["template_name"] && $post_vars["template_text"]) {
                switch($post_vars["submittemplate"]) {
                case "Add Template":
                    $sql = "insert into m_lib_notes_template (template_cat, template_name, template_text) ".
                           "values ('".$post_vars["template_cat"]."', '".$post_vars["template_name"]."', '".$post_vars["template_text"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                    break;
                case "Update Template":
                    $sql = "update m_lib_notes_template set ".
                           "template_cat = '".$post_vars["template_cat"]."', ".
                           "template_name = '".$post_vars["template_name"]."', ".
                           "template_text = '".addslashes($post_vars["template_text"])."' ".
                           "where template_id = '".$post_vars["template_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                    break;
                case "Delete Template":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_notes_template where template_id = '".$post_vars["template_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                        }
                    } else {
                        if ($post_vars["confirm_delete"]=="No") {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                        }
                    }
                    break;
                }
            }
        }
    }

    function display_templates() {
    //
    // calls module::strfraction to
    //  reduce text display to five words
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='500'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_NOTES_TEMPLATE_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td nowrap><b>".THEAD_TEMPLATE_NAME."</b></td><td><b>".THEAD_TEMPLATE_TEXT."</b></td></tr>";
        $sql = "select template_id, template_cat, template_name, template_text from m_lib_notes_template order by template_cat, template_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $cat, $name, $text) = mysql_fetch_array($result)) {
                    if ($prev_cat<>$cat) {
                        print "<tr valign='top' bgcolor='#FFCC33'><td colspan='2'>";
                        if ($cat=="HX") {
                            $catname = "HISTORY";
                        } elseif ($cat=="PE") {
                            $catname = "PHYSICAL EXAM";
                        } elseif ($cat=="TX") {
                            $catname = "PLAN/INTERVENTION";
                        }
                        print "<b>$catname</b>";
                        print "</td></tr>";
                    }
                    print "<tr valign='top'><td class='tinylight'><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&template_id=$id'>$name</a></td><td class='tinylight'>".module::strfraction($text, 5)."</td></tr>";
                    $prev_cat = $cat;
                }
            }
        }
        print "</table><br>";
    }

    function _notes_dxclass() {
    //
    // main method for notes diagnosis class library
    // calls form_dxclass, process_dxclass, display_dxclass
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
        if ($exitinfo = $this->missing_dependencies('notes')) {
            return print($exitinfo);
        }
        $n = new notes;
        if ($post_vars["submitclass"]) {
            $n->process_dxclass($menu_id, $post_vars, $get_vars);
        }
        $n->form_dxclass($menu_id, $post_vars, $get_vars);
        $n->display_dxclass($menu_id);
    }

    function show_dxclass_selection() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        $sql = "select class_id, class_name from m_lib_notes_dxclass ".
               "order by class_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='dxclass[]' class='tinylight' multiple size='10'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($template_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
            } else {
                $ret_val = "<font color='red'>No diagnosis class records.</font><br/>";
            }
            return $ret_val;
        } 
    }

	function show_dxsearch_box(){
		echo "<form method='POST' name='form_search_dx'>";
		echo "<span class='boxtitle'>SEARCH KEYWORD FOR DIAGNOSIS <input type='text' size='15' name='txt_diagnosis'></input>&nbsp;<input type='submit' name='searchdiag' value='Search' class='tinylight' style='border: 1px solid black'></span>";
		echo "</form>";
	}

	function search_diag_term(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];
		endif;
		
		if($post_vars["searchdiag"]=="Search"):
			if(strlen($post_vars["txt_diagnosis"])<0):
				echo "<font color='red'>Keyword should be at least 1 character/s</font>";
			else:
				//$q_diagnosis = mysql_query("SELECT class_id,class_name FROM m_lib_notes_dxclass WHERE class_name LIKE '%$post_vars[txt_diagnosis]%' ORDER by class_name ASC") or die("Cannot query: 1286");
				
				$q_diagnosis = mysql_query("SELECT class_id,class_name,icd10,notifiable FROM m_lib_notes_dxclass WHERE class_name LIKE '%$post_vars[txt_diagnosis]%' ORDER by class_name ASC, icd10 ASC") or die("Cannot query: 1338".mysql_error());
				
				if(mysql_num_rows($q_diagnosis)>0):
					echo "<form method='post' name='form_select_diagnosis'>";
				        echo "<table>";
				        echo "<tr align='center'><td class='boxtitle'>DIAGNOSIS</td><td class='boxtitle'>ICD 10</td></tr>";
				        
					while(list($classid,$classname,$icd,$notifiable)=mysql_fetch_array($q_diagnosis)){
					        echo "<tr><td valign='top'>";
						echo "<span class='tinylight'><input type='checkbox' name='dxclass[]' value='$classid'>$classname</input></span>";
						echo "</td>";
						echo "<td class='tinylight'>$icd</td>";						
						echo "</tr>";
						
					}
					echo "<tr><td align='center' colspan='2'><input type='submit' name='submitnotes' value='Save Diagnosis Class' class='textbox' style='border: 1px solid black'/></td></tr>";
					
					echo "</table>";
					
					echo "</form>";
				else:
					echo "<font color='red'>No result/s found</font>";
				endif;

			endif;
		endif;
	
	}

	
	function form_tx_sickchild(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];
			
		endif;

		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
		$q_px = mysql_query("SELECT a.patient_dob,b.consult_date FROM m_patient a, m_consult b WHERE a.patient_id='$patient_id' AND round((TO_DAYS(date_format(consult_date,'%Y-%m-%d')) - TO_DAYS(a.patient_dob))/7,2) > 260") or die("Cannot query: 1334");
		

		if(mysql_num_rows($q_px)==0):

			$arr_diag_vit = array('measles','severe pneumonia','persistent diarrhea','malnutrition','xerophthalmia','night blindness','bitot','corneal xerosis','corneal ulcerations','keratomalacia');
			$arr_diag_marker = array(0,0,0,0,0,0,0,0,0,0);

			for($a=0;$a<count($arr_diag_vit);$a++){
				$q_sakit = "SELECT a.notes_id FROm m_consult_notes_dxclass a,m_lib_notes_dxclass b WHERE a.notes_id='$get_vars[notes_id]' AND a.class_id=b.class_id";

				$arr_sakit = explode(" ",$arr_diag_vit[$a]);
				
				for($b=0;$b<count($arr_sakit);$b++){
					$q_sakit .= " AND b.class_name LIKE '%$arr_sakit[$b]%'";
				}
				

				$r_sakit = mysql_query($q_sakit) or die("Cannot query: 1350");
				
				if(mysql_num_rows($r_sakit)!=0):
					$arr_diag_marker[$a] = 1;
				endif;
			}

			$this->disp_vita_supplementation($menu_id,$post_vars,$get_vars,$validuser,$isadmin,$arr_diag_marker);

			$arr_diag = array('anemia','diarrhea','pneumonia');

			for($i=0;$i<count($arr_diag);$i++){
				$q_px = mysql_query("SELECT a.notes_id FROM m_consult_notes_dxclass a,m_lib_notes_dxclass b WHERE a.notes_id='$get_vars[notes_id]' AND a.class_id=b.class_id AND b.class_name LIKE '%$arr_diag[$i]%'") or die("cannot query: 1340");
				
				if(mysql_num_rows($q_px)!=0):
					$this->disp_form_diag($menu_id,$post_vars,$get_vars,$validuser,$isadmin,$arr_diag[$i]);
				endif;
			}
		endif;
						
	}

	
	function disp_vita_supplementation(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];			
			$disease_marker = $arg_list[5];
		endif;
		
		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
		
		//ALTER TABLE `m_consult_notes` ADD `vita_date` DATE NOT NULL AFTER `notes_timestamp` ;

		if(in_array('1',$disease_marker)):
			if($post_vars["submit_diagdetails"]=='Save Vit A Date'):
				$vita_date = (!empty($post_vars["txt_vita"]))?$this->convert_date($post_vars["txt_vita"]):'0000-00-00';
				$vita_date_ok = $this->get_age_diff($vita_date,$get_vars,$patient_id);
				
				if($vita_date_ok=='1'):
					$update_vita = mysql_query("UPDATE m_consult_notes SET vita_date='$vita_date' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1394");
				else:
					echo "<font color='red' size='2'>Cannot update. Vitamins A supplementation should be given on or after this consultation.</font>";
				endif;
			endif;
			
			$q_vita = mysql_query("SELECT vita_date FROM m_consult_notes WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1400");
			list($r_vita_date) = mysql_fetch_array($q_vita);

			$vitadate = ($r_vita_date!='0000-00-00')?$this->convert_date_cal($r_vita_date):'';

			echo "<form method='post' name='form_vita'>";
			echo "<span class='boxtitle'>VITAMIN A SUPPLEMENTATION</span>";
			echo "<table>";
			
			echo "<tr><td class='tinylight'><b>DATE VIT A WAS GIVEN</b></td><td><input type='text' size='7' name='txt_vita' value='$vitadate'></input>";
			echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_vita.txt_vita', document.form_vita.txt_vita.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
			echo "<td></tr>";
				
			echo "<tr align='center'><td colspan='2'>";
			echo "<input type='submit' name='submit_diagdetails' value='Save Vit A Date' class='tinylight' style='border: 1px solid black'  onclick=\"window.alert('Vitamin A supplementation is necessary for children (0-5 yo) diagnosed with any of the following: Measles, Severe Pneumonia, Persistent Diarrhea, Malnutrition, Xerophthalmia, Night Blindness, Bitot Spot, Corneal Xerosis, Corneal Ulcerations and Keratomalacia');\"></input>";
			echo "</td></tr>";
			
			echo "</table>";
			echo "</form>";
		endif;
	}


	function disp_form_diag(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];			
			$disease = $arg_list[5];
		endif;			
		
		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
		
		switch($disease){
		
			case "anemia":

				if($post_vars["submit_diagdetails"]=="Save Anemia Tx Info"):
					$anemia_tx_sdate = (!empty($post_vars["txt_iron_started"]))?$this->convert_date($post_vars["txt_iron_started"]):'0000-00-00';
					
					$anemia_tx_cdate = (!empty($post_vars["txt_iron_completed"]))?$this->convert_date($post_vars["txt_iron_completed"]):'0000-00-00';
				
					$anemia_sdate_ok = $this->get_age_diff($anemia_tx_sdate,$get_vars,$patient_id);
					$anemia_edate_ok = $this->get_age_diff($anemia_tx_cdate,$get_vars,$patient_id);

					if($anemia_sdate_ok==1 && $anemia_edate_ok==1):
						$update_anemia = mysql_query("UPDATE m_consult_notes SET anemia_start_date='$anemia_tx_sdate',anemia_completed_date='$anemia_tx_cdate' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1378");
					else:
						echo "<font color='red' size='2'>Anemia Tx Info not saved.One of the date of intakes should occur on or after this consultation.</font>";
					
					endif;

				endif;

				$q_anemia = mysql_query("SELECT anemia_start_date,anemia_completed_date FROM m_consult_notes WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1386");

				list($anemia_sdate,$anemia_cdate) = mysql_fetch_array($q_anemia);
				$anemia_sdate = ($anemia_sdate!='0000-00-00')?$this->convert_date_cal($anemia_sdate):'';
				$anemia_cdate = ($anemia_cdate!='0000-00-00')?$this->convert_date_cal($anemia_cdate):'';
				
				echo "<form method='post' name='form_anemia'>";
				echo "<span class='boxtitle'>ANEMIC CHILDREN GIVEN IRON SUPPLEMENTATION</span>";
				echo "<table>";
				
				echo "<tr><td class='tinylight'><b>DATE STARTED</b></td><td><input type='text' size='7' name='txt_iron_started' value='$anemia_sdate'></input>";
				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_anemia.txt_iron_started', document.form_anemia.txt_iron_started.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
				echo "<td></tr>";

				
				echo "<tr><td class='tinylight'><b>DATE COMPLETED</b></td><td><input type='text' size='7' name='txt_iron_completed' value='$anemia_cdate'></input>";
				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_anemia.txt_iron_completed', document.form_anemia.txt_iron_completed.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
				echo "<td></tr>";


				echo "<tr align='center'><td colspan='2'>";
				echo "<input type='submit' name='submit_diagdetails' value='Save Anemia Tx Info' class='tinylight' style='border: 1px solid black'></input>";
				echo "</td></tr>";


				echo "</table>";
				echo "</form>";
				break;

			case "diarrhea":
				if($post_vars["submit_diagdetails"]=="Save Diarrhea Tx Info"):		
					$ort = (!empty($post_vars["date_ort_given"]))?$this->convert_date($post_vars["date_ort_given"]):'0000-00-00';
					$ors = (!empty($post_vars["date_ors_given"]))?$this->convert_date($post_vars["date_ors_given"]):'0000-00-00';
					$orswz = (!empty($post_vars["date_orswz_given"]))?$this->convert_date($post_vars["date_orswz_given"]):'0000-00-00';

					$ort_ok = $this->get_age_diff($ort,$get_vars,$patient_id);
					$ors_ok = $this->get_age_diff($ors,$get_vars,$patient_id);
					$orswz_ok = $this->get_age_diff($orswz,$get_vars,$patient_id);
					
					if($ort_ok==1 && $ors_ok==1 && $orswz_ok==1):
						$update_diarrhea_tx = mysql_query("UPDATE m_consult_notes SET diarrhea_ort='$ort',diarrhea_ors='$ors',diarrhea_orswz='$orswz' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1445");
					else:
						echo "<font color='red' size='2'>Diarrhea Tx Info cannot be saved. One of the dates occured before this consultation.</font>";
					endif;
				endif;

				$q_diarrhea = mysql_query("SELECT diarrhea_ort,diarrhea_ors,diarrhea_orswz FROM m_consult_notes WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1406");
				
				list($date_ort,$date_ors,$date_orswz) = mysql_fetch_array($q_diarrhea);

				$date_ort = ($date_ort!='0000-00-00')?$this->convert_date_cal($date_ort):'';
				$date_ors = ($date_ors!='0000-00-00')?$this->convert_date_cal($date_ors):'';
				$date_orswz = ($date_orswz!='0000-00-00')?$this->convert_date_cal($date_orswz):'';							

				echo "<form method='post' name='form_diarrhea'>";
				echo "<span class='boxtitle'>DIARRHEA TREAMENT INFORMATION</span>";
				echo "<table>";

				echo "<tr><td class='tinylight'><b>DATE ORT WAS GIVEN</b></td><td><input type='text' size='7' name='date_ort_given' value='$date_ort'></input>";				
				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_diarrhea.date_ort_given', document.form_diarrhea.date_ort_given.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";								
				echo "</td></tr>";

				echo "<tr><td class='tinylight'><b>DATE ORS WAS GIVEN</b></td><td><input type='text' size='7' name='date_ors_given' value='$date_ors'></input>";
				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_diarrhea.date_ors_given', document.form_diarrhea.date_ors_given.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";								
				echo "</td></tr>";

				echo "<tr><td class='tinylight'><b>DATE ORS WITH ZINC WAS GIVEN</b></td><td><input type='text' size='7' name='date_orswz_given' value='$date_orswz'></input>";
				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_diarrhea.date_orswz_given', document.form_diarrhea.date_orswz_given.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
				echo "</td></tr>";
				
				echo "<tr align='center'><td colspan='2'><input type='submit' name='submit_diagdetails' value='Save Diarrhea Tx Info' class='tinylight' style='border: 1px solid black'></input></td></tr>";

				echo "</table>";
				echo "</form>";

				break;

			case "pneumonia":

				if($post_vars["submit_diagdetails"]=="Save Pneumonia Tx Info"):
					$pneumonia_tx = (!empty($post_vars["date_pneu_given"]))?$this->convert_date($post_vars["date_pneu_given"]):'0000-00-00';
					
					$pneumonia_ok = $this->get_age_diff($pneumonia_tx,$get_vars,$patient_id);
					
					if($pneumonia_ok==1):
						$update_pneumonia = mysql_query("UPDATE m_consult_notes SET pneumonia_date_given='$pneumonia_tx' WHERE consult_id='$get_vars[consult_id]'") or die(mysql_error());
					else:
						echo "<font color='red'>Pneumonia Tx Info cannot be saved. Date of treatment should occur on or after this consultation.</font>";
					endif;
				endif;
				
				$q_pneumonia = mysql_query("SELECT pneumonia_date_given FROM m_consult_notes WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 1477");
				list($pneu_date) = mysql_fetch_array($q_pneumonia);
				
				$pneu_date = ($pneu_date!='0000-00-00')?$this->convert_date_cal($pneu_date):'';

				
				echo "<form method='post' name='form_pneumonia'>";
				
				echo "<span class='boxtitle'>PNEUMONIA TREATMENT INFORMATION</span>";
				echo "<table>";
				echo "<tr><td class='tinylight'><b>DATE TREATMENT GIVEN</b></td><td><input type='text' size='7' name='date_pneu_given' value='$pneu_date'></input>";
				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_pneumonia.date_pneu_given', document.form_pneumonia.date_pneu_given.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";								
				echo "</td></tr>";
				
				echo "<tr align='center'><td colspan='2'><input type='submit' name='submit_diagdetails' value='Save Pneumonia Tx Info' class='tinylight' style='border: 1px solid black'></input></td></tr>";				
				echo "</table>";

				echo "</form>";
				break;

			default:
				break;		
		}



	
	}

	function get_age_diff(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$date1 = $arg_list[0];
			$get_vars = $arg_list[1];
			$pxid = $arg_list[2];
		endif;

		$q_px = mysql_query("SELECT (TO_DAYS('$date1')-TO_DAYS(date_format(consult_date,'%Y-%m-%d'))) day_span FROM m_consult WHERE consult_id='$get_vars[consult_id]'") or die(mysql_error());

		list($day_span) = mysql_fetch_array($q_px);
		
		if($day_span<0):
			return 0;
		else:
			return 1;
		endif;
	}

	function convert_date($date_to_convert){
		list($m,$d,$y) = explode('/',$date_to_convert);
		$date_converted = $y.'-'.$m.'-'.$d;
		
		return $date_converted;	
	}

	function convert_date_cal($date_to_convert){
		list($y,$m,$d) = explode('-',$date_to_convert);
		$date_converted = $m.'/'.$d.'/'.$y;

		return $date_converted;
	}


    function form_dxclass() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["class_id"]) {
                $sql = "select class_id, class_name ".
                       "from m_lib_notes_dxclass where class_id = '".$get_vars["class_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $class = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_template' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_NOTES_DXCLASS_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_DXNAME."</span><br> ";
        print "<input type='text' class='textbox' size='35' maxlength='50' name='class_name' value='".($class["class_name"]?$class["class_name"]:$post_vars["class_name"])."' style='border: 1px solid #000000'><br>";
        print "<br></td></tr>";
        print "<tr><td><br>";
        if ($get_vars["class_id"]) {
            print "<input type='hidden' name='class_id' value='".$get_vars["class_id"]."'>";
            print "<input type='submit' value = 'Update Diagnosis' class='textbox' name='submitclass' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Diagnosis' class='textbox' name='submitclass' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Diagnosis' class='textbox' name='submitclass' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function display_dxclass() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='500'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_DXCLASS_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_DXID."</b></td><td><b>".THEAD_DXNAME."</b></td></tr>";
        $sql = "select class_id, class_name from m_lib_notes_dxclass order by class_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&class_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }
    
    
    function get_vitals(){
        if(func_num_args()>0):
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        endif;
        
        $q_vitals = mysql_query("SELECT vitals_weight,vitals_temp,vitals_systolic,vitals_diastolic,vitals_heartrate,vitals_resprate,vitals_height,vitals_pulse FROM m_consult_vitals WHERE consult_id='$consult_id'") or die("Cannot query: 1696 ".mysql_error());
        
        if(mysql_num_rows($q_vitals)!=0):
            list($wt,$temp,$systolic,$diastolic,$heart,$resprate,$ht,$pulse) = mysql_fetch_array($q_vitals);
            $str_vitals = " <b>WT:</b> ".$wt." kg, <b>TEMP:</b> ".$temp.", <b>BP:</b> ".$systolic."/".$diastolic.", <b>HR:</b> ".$heart.", <b>RR:</b> ".$resprate.", <b>PR:</b> ".$pulse.", <b>HT:</b> ".$ht." cm";
            
        else:
            $str_vitals = "<font color='red'>No vitals signs recorded.</font>";
        endif;
        
        return $str_vitals;
    }
    
    function get_consult_note(){
        if(func_num_args()>0):
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        endif;
        
        $q_consult_notes = mysql_query("SELECT notes_complaint FROM m_consult_notes WHERE consult_id='$consult_id'") or die("Cannot query 1732 ".mysql_error());
        list($notes_complaint) = mysql_fetch_array($q_consult_notes);
        return $notes_complaint;        
    }
    

    function process_dxclass() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        if ($post_vars["submitclass"]) {
            if ($post_vars["class_name"]) {
                switch($post_vars["submitclass"]) {
                case "Add Diagnosis":
                    $sql = "insert into m_lib_notes_dxclass (class_name) ".
                           "values ('".$post_vars["class_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                    break;
                case "Update Diagnosis":
                    $sql = "update m_lib_notes_dxclass set ".
                           "class_name = '".$post_vars["class_name"]."' ".
                           "where class_id = '".$post_vars["class_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                    break;
                case "Delete Diagnosis":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_notes_dxclass where class_id = '".$post_vars["class_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                        }
                    } else {
                        if ($post_vars["confirm_delete"]=="No") {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                        }
                    }
                    break;
                }
            }
        }
    }

}
// end of class
?>
