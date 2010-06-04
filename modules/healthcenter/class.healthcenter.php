<?
class healthcenter extends module{

    // Author: Herman Tolentino MD
    // CHITS Project 2004 //

    function healthcenter() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.96-".date("Y-m-d");
        $this->module = "healthcenter";
        $this->description = "CHITS Module - Health Center";
        // 0.9 debugged and edited consult menu to accommodate modules
        // 0.91 trimmed down and removed dead code
        // 0.92 fixed automatic patient consult registration from
        //      Patient Records
        // 0.93 added consult confirmation
        //      appointment integration
        //      added see_doctor_flag column in m_consult
        //      added alert.gif in images
        //      changed consult_date from date to datetime
        // 0.94 removed complaint category and moved it to notes module
        // 0.95 added drug module
	// 0.96 added BMI and vitals_height in m_consult_vitals table
	// 0.97 (2009-06-16) re-ordered the search results alphabetically based on last name
        // 0.98 (2009-09-11) added m_lib_supply_source, contains library of sources of supplies and drugs
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "ptgroup");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "barangay");
        module::set_dep($this->module, "family");
        module::set_dep($this->module, "complaint");
        module::set_dep($this->module, "lab");
        module::set_dep($this->module, "notes");
        module::set_dep($this->module, "appointment");
        module::set_dep($this->module, "drug");
        module::set_dep($this->module, "consult_graph");
    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    //

        module::set_lang("LBL_PTGROUP", "english", "PATIENT GROUP", "Y");
        module::set_lang("FTITLE_CONSULT_MODULE", "english", "CONSULT MODULE FORM", "Y");
        module::set_lang("LBL_CONSULT_MODULE", "english", "CONSULT MODULE", "Y");
        module::set_lang("FTITLE_LOADED_CONSULT_MODULES", "english", "LOADED CONSULT MODULE", "Y");
        module::set_lang("LBL_BLOODPRESSURE", "english", "BLOOD PRESSURE", "Y");
        module::set_lang("LBL_HEARTRATE", "english", "HEART RATE", "Y");
        module::set_lang("LBL_RESPRATE", "english", "RESPIRATORY RATE", "Y");
        module::set_lang("LBL_WEIGHT", "english", "BODY WEIGHT (KG)", "Y");
        module::set_lang("HEIGHT (CM)", "english", "BODY HEIGHT (CM)", "Y");
        module::set_lang("LBL_BODYTEMP", "english", "BODY TEMP (C)", "Y");
        module::set_lang("LBL_VISIT_TYPE", "english", "VISIT TYPE", "Y");
        module::set_lang("INSTR_CORRECT_VALUES", "english", "INSTRUCTIONS: Fill in the form below with correct values:", "Y");
        module::set_lang("FTITLE_VITAL_SIGNS_RECORD", "english", "VITAL SIGNS RECORD", "Y");
        module::set_lang("FTITLE_PREVIOUS_VISITS", "english", "PREVIOUS VISITS:", "Y");
        module::set_lang("LBL_FAMILY_NUMBER", "english", "FAMILY NUMBER:", "Y");
        module::set_lang("LBL_TOTAL_VISITS", "english", "TOTAL VISITS:", "Y");
        module::set_lang("LBL_LAST_VISIT", "english", "LAST VISIT:", "Y");
        module::set_lang("LBL_ELAPSED_TIME", "english", "ELAPSED TIME:", "Y");
        module::set_lang("BTN_END_CONSULT", "english", "END CONSULT", "Y");
        module::set_lang("FTITLE_PATIENT_GROUP", "english", "PATIENT GROUP", "Y");
        module::set_lang("FTITLE_CONSULT_COMPLAINT", "english", "CONSULT COMPLAINT:", "Y");
        module::set_lang("BTN_SAVE_DETAILS", "english", "Save Details", "Y");
        module::set_lang("MENU_VISIT_DETAILS", "english", "VISIT DETAILS", "Y");
        module::set_lang("MENU_APPTS", "english", "APPTS", "Y");
        module::set_lang("MENU_VITAL_SIGNS", "english", "VITAL SIGNS", "Y");
        module::set_lang("MENU_LABS", "english", "LABS", "Y");
        module::set_lang("MENU_NOTES", "english", "NOTES", "Y");
        module::set_lang("MENU_DRUGS", "english", "DRUGS", "Y");
        module::set_lang("MENU_CONSULT", "english", "CONSULT", "Y");
        module::set_lang("FTITLE_CONSULT_COMPLAINTS", "english", "CONSULT COMPLAINTS", "Y");
        module::set_lang("FTITLE_LAB_REQUEST", "english", "LAB REQUEST FORM", "Y");
        module::set_lang("LBL_LABS", "english", "LAB EXAMS", "Y");
        module::set_lang("FTITLE_CONSULTS_TODAY", "english", "CONSULTS TODAY", "Y");
        module::set_lang("FTITLE_VITAL_SIGNS", "english", "VITAL SIGNS", "Y");
        module::set_lang("FTITLE_CONSULT_MODULES", "english", "CONSULT MODULES", "Y");
        module::set_lang("INSTR_CONSULT_MANAGEMENT", "english", "THIS PAGE ENABLES YOU TO DO SOMETHING POTENTIALLY CATASTROPHIC TO PATIENT DATA. TO END THIS CONSULT, IF PATIENT IS ABOUT TO LEAVE THE HEALTH CENTER PRESS THE <b>END CONSULT</b> BUTTON. TO DELETE THIS CONSULT, IF CREATED BY MISTAKE, PRESS THE <b>DELETE CONSULT</b> BUTTON.", "Y");
        module::set_lang("LBL_END_CONSULT", "english", "DO YOU WANT TO <u>END</u> THIS CONSULT", "Y");
        module::set_lang("LBL_DELETE_CONSULT", "english", "DO YOU WANT TO <u>DELETE</u> THIS CONSULT", "Y");
        module::set_lang("INSTR_CLICK_TO_VIEW_RECORD", "english", "CLICK TO VIEW RECORD", "Y");
        module::set_lang("FTITLE_PATIENT_GROUP_HX", "english", "PATIENT GROUP HISTORY", "Y");
        module::set_lang("LBL_SEE_DOCTOR", "english", "SEE PHYSICIAN", "Y");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries
        // parameters:
        // module::set_menu(MODULE_NAME, MENU_CAPTION, MENU_CAT, MODULE_METHOD)
        //
        module::set_menu($this->module, "Today\'s Patients", "CONSULTS", "_consult");
        module::set_menu($this->module, "Consult Modules", "CONSULTS", "_modules");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_consult` (".
            "`consult_id` float NOT NULL auto_increment, ".
            "`patient_id` float NOT NULL default '0', ".
            "`user_id` float NOT NULL default '1', ".
            "`healthcenter_id` varchar(25) NOT NULL default '0', ".
            "`consult_timestamp` timestamp(14) NOT NULL, ".
            "`consult_end` datetime NOT NULL default '0000-00-00 00:00:00', ".
            "`consult_visit_type` char(1) NOT NULL default '', ".
            "`elapsed_time` float NOT NULL default '0', ".
            "`see_doctor_flag` char(1) NOT NULL default 'N', ".
            "`consult_date` datetime NOT NULL default '0000-00-00 00:00:00', ".
            "PRIMARY KEY  (`consult_id`), ".
            "KEY `key_patient` (`patient_id`), ".
            "KEY `key_user` (`user_id`), ".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient`(`patient_id`) ".
            "ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_vitals` (".
            "`consult_id` float NOT NULL default '0',".
            "`vitals_timestamp` timestamp(14) NOT NULL,".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` int(11) NOT NULL default '0',".
            "`vitals_weight` float NOT NULL default '0',".
            "`vitals_temp` float NOT NULL default '0',".
            "`vitals_systolic` int(11) NOT NULL default '0',".
            "`vitals_diastolic` int(11) NOT NULL default '0',".
            "`vitals_heartrate` int(11) NOT NULL default '0',".
			"`vitals_height` int(11) NOT NULL default '0',".
            "`vitals_resprate` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`consult_id`,`vitals_timestamp`),".
            "KEY `key_patient` (`patient_id`),".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient`(`patient_id`) ".
            "ON DELETE CASCADE, ".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ".
            "ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_diagnosis` (".
            "`consult_id` float NOT NULL default '0',".
            "`diagnosis_code` varchar(12) NOT NULL default '',".
            "`diagnosis_text` text, ".
            "PRIMARY KEY  (`consult_id`, `diagnosis_code`),".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ".
            "ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_ptgroup` (".
            "`ptgroup_id` varchar(10) NOT NULL default '',".
            "`consult_id` float NOT NULL default '0',".
            "`ptgroup_timestamp` timestamp(14) NOT NULL,".
            "`user_id` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`consult_id`,`ptgroup_id`),".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ".
            "ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_complaint` (".
            "`consult_id` float NOT NULL default '0',".
            "`complaint_id` varchar(10) NOT NULL default '',".
            "`user_id` int(11) NOT NULL default '0',".
            "`complaint_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`consult_id`,`complaint_id`),".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ".
            "ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_appointments` (".
            "`visit_date` date NOT NULL default '0000-00-00',".
            "`consult_id` float NOT NULL default '0',".
            "`schedule_timestamp` timestamp(14) NOT NULL,".
            "`user_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`patient_group` varchar(10) NOT NULL default '',".
            "`visit_done` char(1) NOT NULL default '',".
            " PRIMARY KEY  (`visit_date`,`patient_id`,`patient_group`)".
            "INDEX (`consult_id`), ".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ".
            "ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_healthcenter_modules` (".
               "`module_id` varchar(25) NOT NULL default '',".
               "PRIMARY KEY  (`module_id`)".
               ") TYPE=InnoDB;");
	
	//m_lib_supply_source

	module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_supply_source` (
		  `source_id` int(5) NOT NULL auto_increment,
		  `source_name` varchar(200) NOT NULL,
		  `source_cat` set('F','B','NA') NOT NULL,
		  PRIMARY KEY  (`source_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

	module::execsql("INSERT INTO `m_lib_supply_source` (`source_id`, `source_name`, `source_cat`) VALUES
			(1, 'MLGU', 'F'),(2, 'PLGU', 'F'),(3, 'DOH', 'F'),(4, 'NGO', 'F'),(5, 'N/A', 'NA'),(6, 'Bought at own expense', 'B');");

	
    }

    function drop_tables() {
        module::execsql("SET foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_consult_diagnosis`;");
        module::execsql("DROP TABLE `m_consult_complaint`;");
        module::execsql("DROP TABLE `m_healthcenter_modules`;");
        module::execsql("DROP TABLE `m_consult_vitals`;");
        module::execsql("DROP TABLE `m_consult_ptgroup`;");
        module::execsql("DROP TABLE `m_consult`;");
	module::execsql("DROP TABLE `m_lib_supply_source`");
        module::execsql("SET foreign_key_checks=1;");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _healthcenter() {

    }


    function form_selectmodule() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_module' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='module'>".FTITLE_CONSULT_MODULE."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>NOTE: You are adding a sub-module to the health center module. Please read instructions for module integration.</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>".LBL_CONSULT_MODULE."</b><br> ";
        print module::show_modules($module["module_id"]?$module["module_id"]:$post_vars["module"]);
        print "<br/><br/></td></tr>";
        print "<tr><td><br>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Add Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
        }
        if ($_SESSION["priv_delete"]) {
            print "<input type='submit' value = 'Delete Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }
	
    function process_module() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["delete_id"]) {
            $sql = "delete from m_healthcenter_modules where module_id = '".$post_vars["module_id"]."'";
            $result = mysql_query($sql);
        }
        switch ($post_vars["submitmodule"]) {
        case "Add Module":
            if ($post_vars["module"]) {
                $sql = "insert into m_healthcenter_modules (module_id) ".
                       "values ('".$post_vars["module"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Module":
            if ($post_vars["module"]) {
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_healthcenter_modules ".
                           "where module_id = '".$post_vars["module"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                }
            }
            break;
        }
    }

    function display_modules() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='620'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='module'>".FTITLE_LOADED_CONSULT_MODULES."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td colspan='4'>";
        print "<b>Click on module name to see details (and source code).</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td><td><b>INIT STATUS</b></td><td><b>VERSION</b></td><td><b>DESCRIPTION</b></td></tr>";
        $sql = "select c.module_id, m.module_name, m.module_init, m.module_version, m.module_desc ".
               "from modules m, m_healthcenter_modules c ".
               "where m.module_id = c.module_id order by m.module_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $init, $version, $desc) = mysql_fetch_array($result)) {
                    print "<tr class='tinylight' valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=MODULES&module_id=$id'>$name</a></td><td>".($init=="N"?"No <a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=INIT'>[<b>activate</b>]</a>":"Yes")."</td><td>$version</td><td>".($desc?"$desc":"<font color='red'>empty</font>")."</td></tr>";
                }
            } else {
                print "<tr valign='top'><td colspan='4'>";
                print "<font color='red'>No modules loaded.</font><br>";
                print "</td></tr>";
            }
        }
        print "</table><br>";
    }

    function _modules() {
    //
    // main API for loading health center modules
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitmodule"]) {
            $this->process_module($menu_id, $post_vars, $get_vars);
        }
        $this->display_modules($menu_id, $post_vars, $get_vars);
        $this->form_selectmodule($menu_id, $post_vars, $get_vars);
    }

    function _consult_housekeeping() {
    //
    // executes with ptmenu CONSULTS
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
        // manage everything here
        if ($post_vars["endconsult"]) {
            switch($post_vars["endconsult"]) {
            case "End Consult":
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=CONSULT'>";
                print "<font color='red' size='5'><b>Are you sure you want to end this consult?</b></font><br />";
                print "<input type='hidden' name='endconsult' value='End Consult'/>";
                print "<input type='hidden' name='consult_id' value='".$get_vars["consult_id"]."'/>";
                print "<input type='submit' name='confirm_end' value='Yes' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFFF33; border: 2px solid black' /> ";
                print "<input type='submit' name='confirm_end' value='No' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFCC33; border: 2px solid black' /> ";
                print "</form>";
                if ($post_vars["confirm_end"]=="Yes") {
                    $sql = "update m_consult set ".
                           "elapsed_time = (unix_timestamp()-unix_timestamp(consult_timestamp))/3600, ".
                           "consult_end = now() ".
                           "where consult_id = '".$post_vars["consult_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                } elseif($post_vars["confirm_end"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
                break;
            case "Delete Consult":
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=CONSULT'>";
                print "<font color='red' size='5'><b>Are you sure you want to delete this consult?</b></font><br />";
                print "<input type='hidden' name='endconsult' value='Delete Consult'/>";
                print "<input type='hidden' name='consult_id' value='".$get_vars["consult_id"]."'/>";
                print "<input type='submit' name='confirm_delete' value='Yes' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFFF33; border: 2px solid black' /> ";
                print "<input type='submit' name='confirm_delete' value='No' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFCC33; border: 2px solid black' /> ";
                print "</form>";
                if ($post_vars["confirm_delete"]=="Yes") {
                    $sql = "delete from m_consult where consult_id = '".$post_vars["consult_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                } elseif($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
                break;
            }
        }
        print "<table width='300'>";
        print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=CONSULT' name='form_consult'>";
        print "<tr><td><br/>";
        print "<span class='tinylight'>".INSTR_CONSULT_MANAGEMENT."</span><br/>";
        print "</td></tr>";
        print "<tr><td><br/>";
        // check if completed consult before showing
        // END CONSULT button
        if (!healthcenter::completed_consult($get_vars["consult_id"])) {
            print "<span class='boxtitle'>".LBL_END_CONSULT."?</span><br/>";
            print "<input type='submit' name='endconsult' value='End Consult' class='textbox' style='border: 1px solid black' />";
            print "</td></tr>";
        }
        if ($_SESSION["priv_delete"]) {
            print "<tr><td><br/>";
            print "<span class='boxtitle'>".LBL_DELETE_CONSULT."?</span><br/>";
            print "<input type='submit' name='endconsult' value='Delete Consult' class='textbox' style='border: 1px solid black; background-color: #FF6600' />";
            print "</td></tr>";
        }
        print "</form>";
        print "</table>";

    }

    function _consult() {
    //
    // main consult API
    // executes with menu choice "Today's Patients"
    //

        static $patient;
        static $notes;
        static $lab;

        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('healthcenter')) {
            return print($exitinfo);
        }
        
        mysql_query("ALTER TABLE `m_consult` DROP PRIMARY KEY , ADD PRIMARY KEY (`consult_id`)");
        mysql_query("ALTER TABLE `m_consult_notes` DROP PRIMARY KEY , ADD PRIMARY KEY (`notes_id`)");
                
        
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($patient)) {
            $patient = new patient;
            $notes = new notes;
            $lab = new lab;
            $drug = new drug;
            $graph = new consult_graph;
        }
        if ($get_vars["patient_id"] && $get_vars["consult_id"]) {
            print "<table>";
            print "<tr valign='top'><td>";
            $this->patient_info($menu_id, $post_vars, $get_vars);
            print "</td></tr>";
            print "</table>";
        } else {
            if ($post_vars["submitpatient"]) {
                // processes form_patient and immediately
                // starts consult
                $patient->process_patient($menu_id, $post_vars, $get_vars);
                $this->process_consult($menu_id, $post_vars, $get_vars);
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
            }
            // check if we are loading patient records or validating entry for
            //   an existing patient in today's consult list
            if ($post_vars["submitconsult"] || $get_vars["enter_consult"] || $post_vars["confirm_add_consult"]) {
                //$post_vars["consult_id"] = $get_vars["enter_consult"];
                // confirms consult for found patients		                
		$this->process_consult($menu_id, $post_vars, $get_vars);
            }
            if ($post_vars["submitsearch"]) {
                // lists down search results for patient
                $this->process_search($menu_id, $post_vars, $get_vars);
            }
            print "<table width='600'>";
            if ($get_vars["consult_id"]) {
                print "<tr valign='top'><td colspan='2'>";
                $this->patient_info($menu_id, $post_vars, $get_vars);
                print "</td></tr>";
                print "<tr valign='top'><td colspan='2'>";
                $this->patient_menu($menu_id, $post_vars, $get_vars);
                print "</td></tr>";
                print "<tr valign='top'><td>";
                // column 1
                switch ($get_vars["ptmenu"]) {
                case "APPTS":
                    appointment::_consult_appointment($menu_id, $post_vars, $get_vars);
                    break;
                case "LABS":
                    if ($post_vars["submitlab"] || $get_vars["delete_id"]) {
                        $lab->process_send_request($menu_id, $post_vars, $get_vars);
                    }
                    $lab->form_send_request($menu_id, $post_vars, $get_vars);
                    break;
                case "DETAILS":
                    if ($get_vars["module"]) {
                        $module_method = $get_vars["module"]."::_consult_".$get_vars["module"]."(\$menu_id, \$post_vars, \$get_vars);";
                        if (class_exists($get_vars["module"])) {
                            eval("$module_method");
                        }
                    } else {
                        if ($post_vars["submitdetails"]) {
                            $this->process_details($menu_id, $post_vars, $get_vars);
                        }
                        $this->form_visitdetails($menu_id, $post_vars, $get_vars);
                    }
                    break;
                case "VITALS":
                    //$this->show_vitalsigns($menu_id, $post_vars, $get_vars);
                    if ($post_vars["submitvitals"]) {
                        $this->process_vitalsigns($menu_id, $post_vars, $get_vars, $_SESSION["userid"]);
                    }
                    $this->form_vitalsigns($menu_id, $post_vars, $get_vars);
                    break;
                case "NOTES":
                    $notes->_consult_notes($menu_id, $post_vars, $get_vars);
                    break;
                case GRAPH:
                    $graph->_graph_form($menu_id,$post_vars,$get_vars);
                    break;
                case "DRUGS":
                    $drug->_consult_drug($menu_id, $post_vars, $get_vars);
                    break;
                case "CONSULT":
                    $this->_consult_housekeeping($menu_id, $post_vars, $get_vars);
                    break;
                }
                print "</td><td>";
                // column 2
                switch ($get_vars["ptmenu"]) {
                case "APPTS":
                    appointment::display_consult_appointments($menu_id, $post_vars, $get_vars);
                    break;
                case "LABS":
                    // lab requests for this consult
                    // flag if done
                    $lab->display_requests($menu_id, $post_vars, $get_vars);
                    break;
                case "VITALS":
                    $this->display_vitals($menu_id, $post_vars, $get_vars);
                    break;
                case "DETAILS":
                    if ($get_vars["module"]) {
                        // construct eval string
                        $module_method = $get_vars["module"]."::_details_".$get_vars["module"]."(\$menu_id, \$post_vars, \$get_vars);";
                        if (class_exists($get_vars["module"])) {
                            eval("$module_method");
                        }
                    } else {
                        $this->show_visitdetails($menu_id, $post_vars, $get_vars);
                        $this->display_consults($menu_id, $post_vars, $get_vars);
                    }
                    break;
                case "NOTES":
                    $notes->_details_notes($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
                    break;
                case "DRUGS":
                    $drug->_details_drug($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
                    break;
                }
                print "</td></tr>";
                print "<tr valign='top'><td colspan='2'>";
                // display all patients confirmed with consults
                // CONSULTS TODAY DISPLAYED AT THE BOTTOM
                $this->consult_info($menu_id, $post_vars, $get_vars);
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td colspan='2'>";
                // display all patients confirmed with consults
                print "<table>";
                print "<tr><td>";
                // CONSULTS TODAY
                $this->consult_info($menu_id, $post_vars, $get_vars);
                print "</td></tr>";
                /*
                print "<tr><td>";
                // REGISTERED PATIENTS TODAY
                $patient->patient_info($menu_id, $post_vars, $get_vars);
                print "</td></tr>";
                */
                print "</table>";
                print "</td></tr>";
                print "<tr valign='top'><td>";
                $patient->newsearch($menu_id, $post_vars, $get_vars);
                print "</td><td>";
                $patient->form_patient($menu_id, $post_vars, $get_vars);
                print "</td></tr>";
            }
            print "</table>";
        }
    }

    function process_search() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        // cascade through selection
        if ($post_vars["last"]) {
            $index .= "lower(patient_lastname) like '%".strtolower($post_vars["last"])."%'";
        }
        // check with ternary operator and isset whether $index has been initialized
        if ($post_vars["first"]) {
            $index .= (isset($index)?" AND ":" ") ." lower(patient_firstname) like '%".strtolower($post_vars["first"])."%' ";
        }
        // query the database and append $index to WHERE
        if (isset($index)) {
            $sql = "SELECT patient_id, patient_firstname, patient_lastname, patient_gender, patient_dob FROM m_patient WHERE $index ORDER BY patient_lastname ASC, patient_firstname ASC";
            if ($result=mysql_query($sql)) {
                print "<span class='module'>".FTITLE_SEARCH_RESULTS."</span><br><br>";
                if ($rows = mysql_num_rows($result)) {
                    print "<b>Found <font color='red'>$rows</font> record".($rows>1?"s":"").". Please select patient to load...</b><br><br>";
                    print "<table bgcolor='#FFFF99' width='300' cellpadding='3' cellspacing='0' style='border: 2px solid black'>";
                    print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id'>";
                    while(list($id,$first,$last,$gender, $dob)=mysql_fetch_array($result)) {
                        print "<tr><td>";
                        print "<input type='radio' name='consult_patient_id' value='$id'> ";
                        $pt_menu_id = module::get_menu_id("_patient");
                        $patient_age = patient::get_age($id);
                        //print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&patient_id=$id'><b>$first $last</b> ($patient_age/$gender) $dob</a>";
			print "<a href='".$_SERVER["PHP_SELF"]."?page=PATIENTS&menu_id=657&patient_id=$id'><b>#$id -  $last, $first</b> ($patient_age/$gender/$dob)</a>";
                        print "</td></tr>" ;
                    }
                    print "<tr><td>";
                    print "<input type='submit' name='submitconsult' value='Select Patient' class='textbox' style='border: 1px solid black; background-color: #FFCC33'><br>";
                    print "</td></tr>";
                    print "</form>";
                    print "</table><br/>";
                } else {
                    print "<b><font color='red'>NO RECORDS FOUND.</a></font></b><br><br>";
                }
            }
        }
    }

    function display_vitals() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<b>".FTITLE_VITAL_SIGNS_RECORD."</b><br/>";
        $sql = "select consult_id, vitals_timestamp, date_format(vitals_timestamp, '%a %d %b %Y, %h:%i%p') ".
               "from m_consult_vitals where consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($cid, $ts, $date) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> $date <a href='".$_SESSION["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS&timestamp=$ts#detail'><img src='../images/view.png' border='0'/></a><br/>";
                    if ($get_vars["timestamp"]==$ts) {
                        $this->vitals_detail($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "<font color='red'>none</font><br/>";
            }
        }
    }

    function vitals_detail() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // process delete here
        if ($post_vars["submitvitals"]) {
            if ($post_vars["submitvitals"]=="Delete") {
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql_delete = "delete from m_consult_vitals ".
                                  "where consult_id = '".$get_vars["consult_id"]."' and ".
                                  "vitals_timestamp = '".$get_vars["timestamp"]."'";
                    if ($result_delete = mysql_query($sql_delete)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS");
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS");
                    }
                }
            }
            
            
        }
        $sql = "select user_id, vitals_weight, vitals_temp, vitals_systolic, vitals_diastolic, vitals_heartrate, vitals_resprate,vitals_height,vitals_pulse ".
               "from m_consult_vitals where consult_id = '".$get_vars["consult_id"]."' and vitals_timestamp = '".$get_vars["timestamp"]."'";

		$edad  = healthcenter::get_patient_age($get_vars["consult_id"]);				

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($uid, $wt, $temp, $syst, $diast, $hrate, $rrate,$ht,$pulse) = mysql_fetch_array($result);
                print "<a name='detail'>";
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS&timestamp=".$get_vars["timestamp"]."' name='form_vitals_detail'>";
                print "<table width='250' style='border: 1px dotted black'><tr><td colspan='2'>";
                print "Taken by: ".user::get_username($uid)."<br/>";
                print "<td></tr>";
                print "<tr valign='top'><td>";
                print "BP: $syst/$diast<br/>";
                print "HR: $hrate<br/>";
                print "RR: $rrate<br/>";
                print "Pulse Rate: $pulse<br/>";
                print "</td><td>";
                print "Weight (kg): $wt<br/>";
                print "Temp deg C: $temp<br/>";
                print "Height: $ht cms<br/>";
                print "</td></tr>";
                print "<tr><td colspan='2'>";
                //print "HPN STAGE: ".healthcenter::hypertension_stage($syst, $diast, $edad)."<br/>";
		print "HPN STAGE: ";
		healthcenter::hypertension_stage($syst, $diast, $edad);
		print "<br/>";
		print healthcenter::compute_bmi($ht,$wt);
                print "</td></tr>";
                print "<tr><td colspan='2'>";
                //if ($_SESSION["priv_delete"]) {
                print "<input type='submit' name='submitvitals' value='Update' class='tinylight' style='border: 1px solid black'/>&nbsp;&nbsp;";
                print "<input type='submit' name='submitvitals' value='Delete' class='tinylight' style='border: 1px solid black'/>";
                //}
                print "</td></tr>";
                print "</table>";
                print "</form>";
            }
        }
    }

    function process_vitalsigns() {

        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        
        
            
        switch($post_vars["submitvitals"]) {
        case "Save Vital Signs":			
			
			if ($post_vars["bloodpressure"]) {
				list($systolic, $diastolic) = explode("/", $post_vars["bloodpressure"]);
			}
			
			$pxid = healthcenter::get_patient_id($_GET[consult_id]);

			$sql = "insert into m_consult_vitals set consult_id='$get_vars[consult_id]',patient_id='$pxid',user_id='$_SESSION[userid]',vitals_weight='$post_vars[bodyweight]',vitals_temp='$post_vars[bodytemp]',vitals_systolic='$systolic',vitals_diastolic='$diastolic',vitals_heartrate='$post_vars[heartrate]',vitals_resprate='$post_vars[resprate]',vitals_height='$post_vars[pxheight]',vitals_pulse='$post_vars[pxpulse]'";
			//$sql = "insert into m_consult_vitals (consult_id, user_id, vitals_weight, vitals_temp, vitals_systolic, vitals_diastolic, vitals_heartrate, vitals_resprate,) "."values ('".$get_vars["consult_id"]."', '".$_SESSION["userid"]."', '".$post_vars["bodyweight"]."', '".$post_vars["bodytemp"]."', '$systolic', '$diastolic', '".$post_vars["heartrate"]."', '".$post_vars["resprate"]."')";

			if ($result = mysql_query($sql) or die(mysql_error())) {
				header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS");
			}
			break;
			
        case "Update Vital Signs":
            if($post_vars["bloodpressure"]):
                list($systolic,$diastolic) = explode("/",$post_vars["bloodpressure"]);
            endif;    
            
            $pxid = healthcenter::get_patient_id($_GET[consult_id]);
            
            $update_vitals = mysql_query("UPDATE m_consult_vitals SET vitals_weight='$post_vars[bodyweight]',vitals_temp='$post_vars[bodytemp]',vitals_systolic='$systolic',vitals_diastolic='$diastolic',vitals_heartrate='$post_vars[heartrate]',vitals_resprate='$post_vars[resprate]',vitals_height='$post_vars[pxheight]',vitals_pulse='$post_vars[pxpulse]' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 802 ".mysql_error());
            
            if($update_vitals):
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS");                            
            endif;
        
	}
		
    }


    function form_vitalsigns() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        
        if($post_vars["submitvitals"]=="Update"):
                $q_vitals = mysql_query("SELECT vitals_weight,vitals_temp,vitals_systolic,vitals_diastolic,vitals_heartrate,vitals_resprate,vitals_height,vitals_pulse FROM m_consult_vitals WHERE consult_id='$get_vars[consult_id]' AND vitals_timestamp='$get_vars[timestamp]'") or die("Cannot query 724 ".mysql_error());                
                list($wt,$temp,$sys,$dias,$heart,$resp,$ht,$pulse) = mysql_fetch_array($q_vitals);            
                $bp = $sys.'/'.$dias;
        endif;                
        
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS' name='form_vitalsigns' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_VITAL_SIGNS."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BLOODPRESSURE."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='bloodpressure' value='$bp' style='border: 1px solid #000000'><br>";
        print "<small>Example: 100/90, 160/100</small><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_HEARTRATE."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='heartrate' value='$heart' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_RESPRATE."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='resprate' value='$resp' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_WEIGHT."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='bodyweight' value='$wt' style='border: 1px solid #000000'><br>";
        print "</td></tr>";

        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>PULSE RATE (beats per minute)</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='pxpulse' value='$pulse' style='border: 1px solid #000000'><br>";
        print "</td></tr>";

        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>HEIGHT (CM)</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='pxheight' value='$ht' style='border: 1px solid #000000'><br>";
        print "</td></tr>";

        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BODYTEMP."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='7' name='bodytemp' value='$temp' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td>";
        //if ($_SESSION["priv_add"]) {
        
        if($post_vars["submitvitals"]=="Update"):
            print "<br><input type='submit' value = 'Update Vital Signs' class='textbox' name='submitvitals' style='border: 1px solid #000000'><br>";                    
        else:
            print "<br><input type='submit' value = 'Save Vital Signs' class='textbox' name='submitvitals' style='border: 1px solid #000000'><br>";        
        endif;
        
        //}
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }
	
	function compute_bmi($height,$weight){

		echo "Body Mass Index: ";
		$ht_cm = $height / 100;			
		
		if($ht_cm!=0):		
			$bmi = round($weight / pow($ht_cm,2),2);
		if($bmi < 18.5):
			$status = "Underweight";
		elseif($bmi >= 18.5 && $bmi < 25):
			$status = "Normal";
		elseif($bmi >= 25 && $bmi < 30):
			$status = "Overweight";
		else: //obese
			$status = "Obese";
		endif;

		echo $bmi.'('.$status.')'.'<br>';		
		
		else:
			print "<font color='red' size='2'>No BMI. Height is 0.</font>";
		endif;



	}


    function display_consults() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<b>".FTITLE_PREVIOUS_VISITS."</b><br/>";
        $patient_id = $this->get_patient_id($get_vars["consult_id"]);
        //$sql = "select consult_id, date_format(consult_timestamp, '%a %d %b %Y, %h:%i%p') ".
        //       "from m_consult where patient_id = $patient_id order by consult_timestamp desc";
		$sql = "select consult_id, date_format(consult_date, '%a %d %b %Y') ".
               "from m_consult where patient_id = '$patient_id'  order by consult_date desc";
		
		$result = mysql_query($sql) or die(mysql_error());
        
		if ($result) {
            if (mysql_num_rows($result)) {
                print "<span class='tinylight'>";
                while (list($cid, $date) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "$date <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=$cid&ptmenu=DETAILS'>".($get_vars["consult_id"]<>$cid?"<b>VIEW</b>":"")."</a><br/>";
                }
                print "</span>";
            }
        }
    }

    function patient_menu() {
    //
    // controls ptmenu $get_vars
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
        print "<table width='650' cellpadding='2' bgcolor='#CCCC99' cellspacing='1' style='border: 2px solid black'><tr>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS' class='ptmenu'>".($get_vars["ptmenu"]=="DETAILS"?"<b>".MENU_VISIT_DETAILS."</b>":MENU_VISIT_DETAILS)."</a></td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=APPTS' class='ptmenu'>".($get_vars["ptmenu"]=="APPTS"?"<b>".MENU_APPTS."</b>":MENU_APPTS)."</a></td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=VITALS' class='ptmenu'>".($get_vars["ptmenu"]=="VITALS"?"<b>".MENU_VITAL_SIGNS."</b>":MENU_VITAL_SIGNS)."</td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=LABS' class='ptmenu'>".($get_vars["ptmenu"]=="LABS"?"<b>".MENU_LABS."</b>":MENU_LABS)."</td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES' class='ptmenu'>".($get_vars["ptmenu"]=="NOTES"?"<b>".MENU_NOTES."</b>":MENU_NOTES)."</td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS' class='ptmenu'>".($get_vars["ptmenu"]=="DRUGS"?"<b>".MENU_DRUGS."</b>":MENU_DRUGS)."</td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=GRAPH#graph' class='ptmenu'>".($get_vars["ptmenu"]=="GRAPHS"?"<b>GRAPHS</b>":"GRAPHS")."</td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=CONSULT' class='ptmenu'>".($get_vars["ptmenu"]=="CONSULT"?"<b>".MENU_CONSULT."</b>":MENU_CONSULT)."</td>";
        print "</tr></table>";
    }

    function patient_info() {
		
		//print_r($_SESSION);

        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["consult_id"]) {
            $sql = "select p.patient_id, p.patient_lastname, p.patient_firstname, round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age, p.patient_gender, p.patient_dob, c.see_doctor_flag ".
                   "from m_patient p, m_consult c ".
                   "where c.patient_id = p.patient_id and c.consult_id = '".$get_vars["consult_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    $ptinfo = mysql_fetch_array($result);
                }
            }
        }
        print "<table width='650' cellpadding='2' cellspacing='0' style='border: 2px solid black'>";
        print "<tr><td colspan='2' bgcolor='#FFFFCC'>";
        print "<span class='library'>".strtoupper($ptinfo["patient_lastname"].", ".$ptinfo["patient_firstname"])."</span> <br/>";
        print LBL_FAMILY_NUMBER." <b>".family::search_family($ptinfo["patient_id"])."</b>&nbsp;&nbsp;&nbsp;"."AGE: <b>".($ptinfo["computed_age"]<1?($ptinfo["computed_age"]*12)."M":$ptinfo["computed_age"]."Y")."/".$ptinfo["patient_gender"]."</b>&nbsp;&nbsp;&nbsp; BIRTHDATE: <b>".$ptinfo["patient_dob"]."</b><br/>";
        print "</td></tr>";
        print "<tr valign='top' bgcolor='#FFFF99'><td>";
        print LBL_PATIENT_ID.": <b>".module::pad_zero($ptinfo["patient_id"],7)."</b><br/>";
        print LBL_TOTAL_VISITS.": <b>".$this->get_totalvisits($ptinfo["patient_id"])."</b>&nbsp;&nbsp;&nbsp;".LBL_LAST_VISIT." <b>".$this->get_lastvisit($ptinfo["patient_id"])."</b><br/>";
        print "</td><td>&nbsp;";
        print "</td></tr>";
        print "<tr><td colspan='2' bgcolor='#FFFF66'>";
        print LBL_ELAPSED_TIME." <b>".$this->get_elapsedtime($get_vars["consult_id"])."</b> &nbsp;&nbsp;";
        print "</td></tr>";
        print "</table>";
    }

    function show_visitdetails() {
    //
    // data shown on the right side when DETAILS  is clicked
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
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        print "<b>".FTITLE_PATIENT_GROUP."</b><br/>";

        if ($get_vars["deletets"] && $get_vars["deletegroup"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql_delete = "delete from m_consult_ptgroup where consult_id = '".$get_vars["consult_id"]."' and ptgroup_timestamp = '".$get_vars["deletets"]."' and ptgroup_id = '".$get_vars["deletegroup"]."'";
                if ($result_delete = mysql_query($sql_delete)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS");
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS");
                }
            }
        }
        $sql_ptgroup = "select g.ptgroup_name, g.ptgroup_module, c.ptgroup_timestamp, c.ptgroup_id from m_consult_ptgroup c, m_lib_ptgroup g ".
                       "where g.ptgroup_id = c.ptgroup_id and c.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql_ptgroup)) {
            if (mysql_num_rows($result)) {
                while (list($name, $mod, $ts, $grp) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=$mod'>$name</a> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&deletets=$ts&deletegroup=$grp'><img src='../images/delete.png' border='0'/></a><br/>";
                }
            } else {
                print "<font color='red'>none</font><br/>";
            }
        }
        print "<br/>";
        print "<b>".FTITLE_PATIENT_GROUP_HX."</b><br/>";

        $sql_ptgroup = "select count(c.ptgroup_id), g.ptgroup_name, g.ptgroup_module, c.ptgroup_id, c.consult_id ".
                       "from m_consult_ptgroup c, m_lib_ptgroup g, m_consult h ".
                       "where g.ptgroup_id = c.ptgroup_id and ".
                       "h.consult_id = c.consult_id and ".
                       "h.patient_id = '$patient_id' ".
                       "group by c.ptgroup_id";
        if ($result = mysql_query($sql_ptgroup)) {
            if (mysql_num_rows($result)) {
                print "<span class='tinylight'>";
                while (list($count, $name, $mod, $grp, $cid) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "$name: $count ".($count>1?" visits":"visit")."<br/> ";
                }
                print "</span>";
            } else {
                print "<font color='red'>No records.</font><br/>";
            }
        }
        /*
        print "<br/>";
        print "<b>".FTITLE_CONSULT_COMPLAINTS."</b><br/>";
        // process delete here
        if ($get_vars["deletets"] && $get_vars["deletecomplaint"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql_delete = "delete from m_consult_complaint where consult_id = '".$get_vars["consult_id"]."' and complaint_timestamp = '".$get_vars["deletets"]."' and complaint_id = '".$get_vars["deletecomplaint"]."'";
                if ($result_delete = mysql_query($sql_delete)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS");
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS");
                }
            }
        }
        $sql_complaint = "select l.complaint_name, l.complaint_module, c.complaint_timestamp, c.complaint_id from m_consult_complaint c, m_lib_complaint l ".
                         "where l.complaint_id = c.complaint_id and c.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql_complaint)) {
            if (mysql_num_rows($result)) {
                while (list($name, $mod, $ts, $comp) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=$mod'>$name</a> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&deletets=$ts&deletecomplaint=$comp'><img src='../images/delete.png' border='0'/></a><br/>";
                }
            } else {
                print "<font color='red'>none</font><br/>";
            }
        }
        */
        print "<br/>";
        print "<b>".FTITLE_CONSULT_MODULES."</b><br/>";
        $sql_modules = "select m.module_desc, h.module_id ".
                       "from m_healthcenter_modules h, modules m ".
                       "where h.module_id = m.module_id";
        if ($result = mysql_query($sql_modules)) {
            if (mysql_num_rows($result)) {
                while (list($desc, $mod) = mysql_fetch_array($result)) {
                    // remove the CHITS prefix
                    $desc = ereg_replace("CHITS ","", $desc);
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=$mod'>$desc</a><br/> ";
                }
            } else {
                print "<font color='red'>none</font><br/>";
            }
        }
        print "<br/>";
    }

    function process_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitdetails"]) {
            if (count($post_vars["ptgroup"])>0) {
                // any ptgroup checked
                foreach($post_vars["ptgroup"] as $key=>$value) {
                    $sql = "insert into m_consult_ptgroup (ptgroup_id, consult_id, ptgroup_timestamp, user_id) ".
                           "values ('$value', '".$get_vars["consult_id"]."', sysdate(), '".$_SESSION["userid"]."')";
                    $result = mysql_query($sql);
                }
            }
            /*
            if (count($post_vars["complaintcat"])>0) {
                foreach($post_vars["complaintcat"] as $key=>$value) {
                    $sql = "insert into m_consult_complaint (complaint_id, consult_id, complaint_timestamp, user_id) ".
                           "values ('$value', '".$get_vars["consult_id"]."', sysdate(), '".$_SESSION["userid"]."')";
                    $result = mysql_query($sql);
                }
            }
            */
            if ($post_vars["see_doctor_flag"]=="Y") {
                $sql = "update m_consult set see_doctor_flag = 'Y' where consult_id = '".$get_vars["consult_id"]."'";
                $result = mysql_query($sql);
            } elseif($post_vars["see_doctor_flag"]=="N") {
                $sql = "update m_consult set see_doctor_flag = 'N' where consult_id = '".$get_vars["consult_id"]."'";
                $result = mysql_query($sql);
            }
        }
    }

    function form_visitdetails() {
    //
    // shows list of patient groups and complaints
    // on the left side under VISIT DETAILS
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
        $patient_id = $this->get_patient_id($get_vars["consult_id"]);
        $age = patient::get_age($patient_id);
        $gender = patient::get_gender($patient_id);
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=CONSULTS&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS' name='form_patient' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PTGROUP."</span><br> ";
        print ptgroup::checkbox_ptgroup($age, $gender);
        print "<br>";
        print "</td></tr>";
        /*
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_COMPLAINTCAT."</span><br> ";
        print complaint::checkbox_complaintcat($age, $gender);
        print "<br>";
        print "</td></tr>";
        */
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SEE_DOCTOR."?</span><br> ";
        print "<input type='radio' name='see_doctor_flag' value='Y'/> Will see physician<br/>";
        print "<input type='radio' name='see_doctor_flag' value='N'/> Will not see physician<br/>";
        print "</td></tr>";
        print "<tr><td>";
        print "<br><input type='submit' value = 'Save Details' class='textbox' name='submitdetails' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function consult_info() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select c.consult_id, p.patient_id, p.patient_lastname, p.patient_firstname, see_doctor_flag ".
               "from m_consult c, m_patient p where c.patient_id = p.patient_id ".
               "and consult_end = '0000-00-00 00:00:00' order by c.consult_date asc";
        if ($result = mysql_query($sql)) {
            print "<span class='patient'>".FTITLE_CONSULTS_TODAY."</span><br>";
            print "<table width=600 bgcolor='#FFFFFF' cellpadding='3' cellspacing='0' style='border: 2px solid black'>";
            print "<tr><td>";
            print "<span class='tinylight'>HIGHLIGHTED NAMES OR THOSE MARKED WITH <img src='../images/star.gif' border='0'/> WILL SEE PHYSICIAN.</span><br/>";
            if (mysql_num_rows($result)) {
                // initialize array index
                $i=0;
                // have to return always to consult page
                // wherever this is shown
                $consult_menu_id = module::get_menu_id("_consult");
                while (list($cid, $pid, $plast, $pfirst, $see_doctor) = mysql_fetch_array($result)) {

                    $q_lab = mysql_query("SELECT request_id,request_done FROM m_consult_lab WHERE patient_id='$pid' AND consult_id='$cid'") or die("Cannot query 1224".mysql_error());
                                        
                    
                    if(mysql_num_rows($q_lab)!=0):
                        $arr_done = array();
                        $arr_id = array();
                        while(list($req_id,$done_status) = mysql_fetch_array($q_lab)){
                            array_push($arr_id,$req_id);
                            array_push($arr_done,$done_status);                                                    
                        }
                        
                        $done = (in_array("N",$arr_done)?"N":"Y");
                        $request_id = $arr_id[0];                        
                        $url = "page=CONSULTS&menu_id=1327&consult_id=$cid&ptmenu=LABS";
                    else:
                        $request_id = $done = "";
                    endif;
                                                            
                    
                    $visits = healthcenter::get_total_visits($pid);
                    $consult_array[$i] = "<a href='".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=$consult_menu_id&consult_id=$cid&ptmenu=DETAILS' title='".INSTR_CLICK_TO_VIEW_RECORD."' ".($see_doctor=="Y"?"style='background-color: #FFFF33'":"").">".
                                         "<b>$plast, $pfirst</b></a> [$visits] ".($see_doctor=="Y"?"<img src='../images/star.gif' border='0'/>":"").(($request_id!="")?(($done=="Y")?"<a href='$_SERVER[PHP_SELF]?$url' title='lab completed'><img src='../images/lab.png' width='15px' height='15px' border='0' alt='lab completed' /></a>":"<a href='$_SERVER[PHP_SELF]?$url' title='lab pending'><img src='../images/lab_untested.png' width='15px' height='15px' border='0' alt='lab pending' /></a>"):"");
                                         
                    $i++;
                }
                // pass on patient list to be columnized
                print $this->columnize_list($consult_array);
            } else {
                print "<font color='red'>No consults available.</font>";
            }
            $sql_time = "select round(avg(unix_timestamp(consult_end)-unix_timestamp(consult_date))/60,2) consult_minutes from m_consult where consult_end>0 and to_days(consult_date) = to_days(sysdate());";
            if ($result_time = mysql_query($sql_time)) {
                if (mysql_num_rows($result_time)) {
                    list($mean_consult_time) = mysql_fetch_array($result_time);
                }
                if ($mean_consult_time) {
                    print "<tr><td class='tinylight'>";
                    if ($mean_consult_time>60) {
                        $unit_time = "hour(s)";
                    } elseif ($meantime>1440) {
                        $unit_time = "day(s)";
                    } else {
                        $unit_time = "minute(s)";
                    }
                    print "AVERAGE CONSULT TIME: <font color='red'>$mean_consult_time $unit_time</font><br/>";
                    print "</td></tr>";
                }
            }
            print "</td></tr>";
            print "</table>";
        }
    }

    function process_consult() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
	

        // make sure you catch where patient_id is coming from
        if ($post_vars["consult_patient_id"]) {
            $patient_id = $post_vars["consult_patient_id"];
        } elseif ($get_vars["enter_consult"]) {
            $patient_id = $get_vars["enter_consult"];
        } elseif ($post_vars["patient_id"]) {
            $patient_id = $post_vars["patient_id"];
        }
        // check if consult records for today contain
        //   patient id to avoid possible duplicate consults
        // limitation of this software enforced constraint
        //   is if consult exceeds one day (not possible in health centers)
        if (healthcenter::is_patient_in_consult($patient_id)) {
            $post_vars["patient_id"] = $patient_id;
            if (healthcenter::confirm_add_consult($menu_id, $post_vars, $get_vars)) {
                $sql = "insert into m_consult (patient_id, user_id, healthcenter_id, consult_date) ".
                       "values ('$patient_id', '".$_SESSION["userid"]."', '".$_SESSION["datanode"]["code"]."', sysdate())";
                if ($result = mysql_query($sql)) {
                    // get insert_id into header
                    $insert_id = mysql_insert_id();
                    // if patient comes from appointment page
                    // update appointment table
                    if ($post_vars["schedule_id"]) {
                        $sql_appt = "update m_consult_appointments set ".
                                    "actual_date = sysdate(), ".
                                    "visit_done = 'Y' ".
                                    "where schedule_id = '".$post_vars["schedule_id"]."'";
                        $result_appt = mysql_query($sql_appt);
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&schedule_id=".$get_vars["schedule_id"]."&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0#detail");
                    } else {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=".$get_vars["menu_id"]."&consult_id=$insert_id&ptmenu=DETAILS");
                    }
                }
            } else {
                if ($post_vars["confirm_add_consult"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=".$get_vars["menu_id"]."&consult_id=$insert_id&ptmenu=DETAILS");
                }
            }
        } else {

            // insert into consult table if there are no
            //   possible duplicate entries

            $sql = "insert into m_consult (patient_id, user_id, healthcenter_id, consult_date) ".
                   "values ('$patient_id', '".$_SESSION["userid"]."', '".$_SESSION["datanode"]["code"]."', sysdate())";
            if ($result = mysql_query($sql)) {
                // get insert_id into header
                $insert_id = mysql_insert_id();
                // if patient comes from appointment page
                // update appointment table
                if ($post_vars["schedule_id"]) {
                    $sql_appt = "update m_consult_appointments set ".
                                "actual_date = sysdate(), ".
                                "visit_done = 'Y' ".
                                "where schedule_id = '".$post_vars["schedule_id"]."'";
                    $result_appt = mysql_query($sql_appt);
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&schedule_id=".$get_vars["schedule_id"]."&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0#detail");
                } else {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=".$get_vars["menu_id"]."&consult_id=$insert_id&ptmenu=DETAILS");
                }
            }
        }
    }

    // -------------------------- MISCELLANEOUS --------------------------

    function hypertension_stage() {
    //
    // returns systolic pressure with stage
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $systolic = $arg_list[0];
            $diastolic = $arg_list[1];
            $edad = $arg_list[2];
        }
	
		if($edad >= 18):
        
			if ($systolic >=160 || $diastolic >=100) {
				echo "<font color='red'>$systolic/$diastolic <b>HPN STAGE 2</b></font>";
				return 'HPN2';
			} elseif (($systolic >=140 && $systolic <=159) || ($diastolic>=90 && $diastolic<=99)) {
				echo "<font color='red'>$systolic/$diastolic <b>HPN STAGE 1</b></font>";
				return 'HPN1';
			} elseif (($systolic >=120 && $systolic <=139) || ($diastolic>=80 && $diastolic<=89)) {
				echo "<font color='red'>$systolic/$diastolic PRE-HPN</font>";
				return 'PREHPN';
			} elseif ($systolic<120 && $diastolic<80) {
				echo "<font color='blue'>$systolic/$diastolic NORMAL</font>";
				return 'NORMAL';
			} elseif(!$systolic && !$diastolic) {
				echo "NA";
				return "NA";
			}
			else{}

		else:
			return "<font color='red' size='2'>BP not applicable for <18YO.</font>";
		endif;
  }

function hypertension_code() {
    //
    // returns systolic pressure with stage
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $systolic = $arg_list[0];
            $diastolic = $arg_list[1];
            $edad = $arg_list[2];
        }
	
		if($edad >= 18):
        
			if ($systolic >=160 || $diastolic >=100) {
				return 'HPN2';
			} elseif (($systolic >=140 && $systolic <=159) || ($diastolic>=90 && $diastolic<=99)) {
				return 'HPN1';
			} elseif (($systolic >=120 && $systolic <=139) || ($diastolic>=80 && $diastolic<=89)) {
				return 'PREHPN';
			} elseif ($systolic<120 && $diastolic<80) {
				return 'NORMAL';
			} elseif(!$systolic && !$diastolic) {
				return "NA";
			}
			else{}

		else:
			return "NA";
		endif;
  }

    function confirm_add_consult() {
    //
    //  if (healthcenter::confirm_add_consult($menu_id, $post_vars, $get_vars)) {
    //      ..insert statement
    //  } else {
    //      if ($post_vars["confirm_add_consult"]=="No") {
    //          header("location: "<previous page URL>);
    //      }
    //  }
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($post_vars);
        }
        if ($post_vars["confirm_add_consult"]) {
            switch ($post_vars["confirm_add_consult"]) {
            case "Yes":
                return true;
                break;
            case "No":
                //header("location: ".$_SERVER["HTTP_REFERRER"]);
                return false;
                break;
            }
        } else {
            print "<table>";
            print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."'>";
            print "<tr><td>";
            // make sure you catch the source of patient_id!
            // ... from PATIENT RECORDS as image link
            if ($get_vars["enter_consult"]) {
                $patient_id = $get_vars["enter_consult"];
            // ... or from SEARCH for OLD PATIENT
            } elseif ($post_vars["consult_patient_id"]) {
                $patient_id = $post_vars["consult_patient_id"];
            }
            print "<font color='red' size='5'><b>You are attempting to load a patient that is already in today's consult. Are you sure you want to load this patient's records again?</b></font><br />";
            print "<input type='hidden' name='patient_id' value='$patient_id'/> ";
            print "<input type='hidden' name='sked_id' value='".$get_vars["sked_id"]."'/> ";
            print "<input type='submit' name='confirm_add_consult' value='Yes' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFFF33; border: 2px solid black' /> ";
            print "<input type='submit' name='confirm_add_consult' value='No' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFCC33; border: 2px solid black' /> ";
            print "</td></tr>";
            print "</form>";
            print "</table>";
            return false;
        }
    }

    function is_patient_in_consult() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

        $date = date("Y-m-d");
        $sql = "select patient_id from m_consult ".
             "where consult_end = '0000-00-00 00:00:00' and ".
             "patient_id = '$patient_id' and to_days(consult_date) = to_days('$date')";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($pt_in_consult) = mysql_fetch_array($result);
                return $pt_in_consult;
            }
        }
        return 0;
    }

    function get_total_visits() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select count(consult_id) from m_consult where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($count) = mysql_fetch_array($result);
                return $count;
            }
        }
    }
    function get_mothers_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        $sql = "select p.patient_mother from m_patient p, m_consult c ".
               "where c.patient_id = p.patient_id and c.consult_id = '$consult_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($mother) = mysql_fetch_array($result);
                return $mother;
            }
        }
    }

    function get_body_weight() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        $sql = "select vitals_weight from m_consult_vitals ".
               "where consult_id = '$consult_id' order by vitals_timestamp desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($weight) = mysql_fetch_array($result);
                return $weight;
            }
        }
    }

    function get_blood_pressure() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        $sql = "select vitals_systolic, vitals_diastolic from m_consult_vitals ".
               "where consult_id = '$consult_id' order by vitals_timestamp desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($systolic, $diastolic) = mysql_fetch_array($result);
                return array("$systolic", "$diastolic");
            }
        }
    }
	
    function completed_consult() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        $sql = "select consult_id from m_consult where consult_end<>'0000-00-00 00:00:00' and consult_id = '$consult_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function get_elapsedtime() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        /*$sql = "select date_format(c.consult_date, '%b %d %Y, %h:%i %p'), ".
               "round(if(elapsed_time=0,(unix_timestamp()-unix_timestamp(c.consult_date))/3600, elapsed_time),2) elapsed from m_consult c ".
               "where c.consult_id = $consult_id"; */

		$sql = "select date_format(c.consult_timestamp, '%b %d %Y, %h:%i %p'), ".
               "round(if(elapsed_time=0,(unix_timestamp()-unix_timestamp(c.consult_timestamp))/3600, elapsed_time),2) elapsed from m_consult c ".
               "where c.consult_id = $consult_id"; 
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date, $elapsed) = mysql_fetch_array($result);
                return "$date $elapsed"."H elapsed";
            }
        }
    }

    function get_patient_id() {
    //
    // frequently called function
    // in other modules
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        $sql = "select patient_id from m_consult where consult_id = '$consult_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($patient_id) = mysql_fetch_array($result);
                return $patient_id;
            }
        }
    }

    function get_consult_date() {
    //
    // returns consult date
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }
        $sql = "select consult_date from m_consult where consult_id = '$consult_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                return $date;
            }
        }
    }

    function get_totalvisits() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select count(consult_id) from m_consult where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($count) = mysql_fetch_array($result);
                return $count;
            }
        }
    }

    function get_lastvisit() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select date_format(MAX(consult_date), '%a %d %b %Y, %h:%i%p') from m_consult where patient_id = '$patient_id' AND consult_id!=(SELECT MAX(consult_id) FROM m_consult WHERE patient_id='$patient_id') GROUP BY patient_id";
        if ($result = mysql_query($sql)) {	
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                return $date;
            }
        }        
    }

	function get_patient_age(){
		if(func_num_args() > 0):
			$arg_list = func_get_args();
			$consult_id = $arg_list[0];
		endif;

		$q_age = mysql_query("SELECT round((TO_DAYS(b.consult_date) - TO_DAYS(a.patient_dob))/365 ,2),a.patient_id FROM m_patient a, m_consult b WHERE b.consult_id='$consult_id' AND a.patient_id=b.patient_id") or die("Cannot query: 1517");
		
		list($edad,$patient_id) = mysql_fetch_array($q_age);

		return $edad;		
	}

// end of class
}
?>
