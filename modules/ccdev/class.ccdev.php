<?
class ccdev extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function ccdev() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.7-".date("Y-m-d");
        $this->module = "ccdev";
        $this->description = "CHITS Module - Child Care Dev";
        // 0.3 foreign key constraints installed
        // 0.4 integrated wt for age module
        // 0.5 revised patient data, added actual vaccine date
        // 0.6 added age_on_vaccine (weeks) to m_consult_ccdev_vaccine
        //     added age_on_service (weeks) to m_consult_ccdev_services
        // 0.7 added checking for full immunization
        //     added foreign key for ccdev - absent before!

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "complaint");
        module::set_dep($this->module, "lab");
        module::set_dep($this->module, "vaccine");
        module::set_dep($this->module, "occupation");
        module::set_dep($this->module, "education");
        module::set_dep($this->module, "barangay");
        module::set_dep($this->module, "family");
        module::set_dep($this->module, "imci");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_CCDEV_SERVICE_FORM", "english", "CHILD CARE SERVICE FORM", "Y");
        module::set_lang("LBL_SERVICE_ID", "english", "SERVICE ID", "Y");
        module::set_lang("LBL_SERVICE_NAME", "english", "SERVICE NAME", "Y");
        module::set_lang("FTITLE_CCDEV_SERVICES_LIST", "english", "CHILD CARE SERVICES", "Y");
        module::set_lang("FTITLE_CCDEV_BREASTFEED", "english", "BREASTFEED STATUS", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("FTITLE_CCDEV_DATA_FORM", "english", "CHILD CARE DATA FORM", "Y");
        module::set_lang("LBL_MOTHERS_OCCUPATION", "english", "MOTHER\'S OCCUPATION", "Y");
        module::set_lang("LBL_MOTHERS_EDUCATION", "english", "MOTHER\'S EDUCATION", "Y");
        module::set_lang("LBL_FATHERS_NAME", "english", "FATHER\'S NAME", "Y");
        module::set_lang("LBL_FATHERS_OCCUPATION", "english", "FATHER\'S OCCUPATION", "Y");
        module::set_lang("LBL_FATHERS_EDUCATION", "english", "FATHER\'S EDUCATION", "Y");
        module::set_lang("LBL_BIRTH_WEIGHT", "english", "BIRTH WEIGHT (kg)", "Y");
        module::set_lang("LBL_CIVIL_REGISTRATION_DATE", "english", "DATE OF REGISTRATION CIVIL REGISTRY", "Y");
        module::set_lang("LBL_DELIVERY_LOCATION", "english", "LOCATION OF DELIVERY", "Y");
        module::set_lang("FTITLE_CCDEV_SERVICES", "english", "CHILD CARE SERVICES", "Y");
        module::set_lang("LBL_SERVICES", "english", "SERVICES", "Y");
        module::set_lang("LBL_VACCINATIONS", "english", "VACCINATIONS", "Y");
        module::set_lang("INSTR_ADVERSE_VACCINE_REACTION", "english", "Check for adverse vaccine reaction", "Y");
        module::set_lang("FTITLE_OTHER_FAMILY_MEMBERS", "english", "OTHER FAMILY MEMBERS", "Y");
        module::set_lang("LBL_SELECT_SIBLING", "english", "SELECT SIBLING", "Y");
        module::set_lang("FTITLE_FIRST_VISIT_DATA", "english", "FIRST VISIT DATA`", "Y");
        module::set_lang("FTITLE_PATIENT_DATA", "english", "PATIENT DATA", "Y");
        module::set_lang("FTITLE_VACCINE_RECORD", "english", "VACCINE RECORD", "Y");
        module::set_lang("FTITLE_SERVICE_RECORD", "english", "SERVICE RECORD", "Y");
        module::set_lang("FTITLE_PATIENT_SIBLINGS", "english", "PATIENT SIBLINGS", "Y");
        module::set_lang("LBL_AGE_IN_WEEKS", "english", "AGE IN WEEKS", "Y");
        module::set_lang("LBL_WT_FOR_AGE", "english", "WEIGHT FOR AGE", "Y");
        module::set_lang("LBL_NO_WEIGHT_AVAILABLE", "english", "NO WEIGHT AVAILABLE", "Y");
        module::set_lang("LBL_IMMUNIZATION_STATUS", "english", "IMMUNIZATION STATUS", "Y");
        module::set_lang("LBL_CCDEV_ID", "english", "REGISTRY ID", "Y");
        module::set_lang("INSTR_FIRST_VISIT", "english", "YOU NEED TO FILL THIS UP TO INCLUDE THIS PATIENT IN THE CHILD CARE REGISTRY.", "Y");
        module::set_lang("LBL_NO_REGISTRY_ID", "english", "NO REGISTRY ID FOR THIS PATIENT", "Y");
        module::set_lang("LBL_NO_FAMILY_ID", "english", "NO FAMILY ID FOR THIS PATIENT", "Y");
		module::set_lang("LBL_CPAB", "english", "CHILD PROTECTED AT BIRTH (CPAB)", "Y");
		module::set_lang("LBL_LOW_BIRTH_WEIGHT", "english", "LOW BIRTH WEIGHT", "Y");
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
        module::set_menu($this->module, "Child Care", "STATS", "_ccdev_stats");
        module::set_menu($this->module, "CCDEV Services", "LIBRARIES", "_ccdev_services");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_consult_ccdev_services` (".
            "`ccdev_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`ccdev_timestamp` timestamp(14) NOT NULL,".
            "`service_id` varchar(10) NOT NULL default '',".
            "`age_on_service` float NOT NULL default '0',".
            "PRIMARY KEY  (`ccdev_id`,`consult_id`,`service_id`,`ccdev_timestamp`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `ccdev_id` (`ccdev_id`),".
            "CONSTRAINT `m_consult_ccdev_services_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ccdev_services_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ccdev_services_ibfk_2` FOREIGN KEY (`ccdev_id`) REFERENCES `m_patient_ccdev` (`ccdev_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;  ");

        // version 0.8
        module::execsql("set foreign_key_checks=0;");
        module::execsql("alter table `m_patient_ccdev` drop foreign key `m_patient_ccdev_father_educ`;");
        module::execsql("alter table `m_patient_ccdev` drop foreign key `m_patient_ccdev_father_occup`;");
        module::execsql("set foreign_key_checks=1;");

        module::execsql("CREATE TABLE `m_consult_ccdev_vaccine` (".
            "`ccdev_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`vaccine_timestamp` timestamp(14) NOT NULL,".
            "`actual_vaccine_date` date NOT NULL default '0000-00-00',".
            "`adr_flag` char(1) NOT NULL default 'N',".
            "`vaccine_id` varchar(10) NOT NULL default '',".
            "`age_on_vaccine` float NOT NULL default '0',".
            "PRIMARY KEY  (`ccdev_id`,`consult_id`,`vaccine_id`,`vaccine_timestamp`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_vaccine` (`vaccine_id`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `ccdev_id` (`ccdev_id`),".
            "CONSTRAINT `m_consult_ccdev_vaccine_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ccdev_vaccine_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ccdev_vaccine_ibfk_2` FOREIGN KEY (`ccdev_id`) REFERENCES `m_patient_ccdev` (`ccdev_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");
		
        module::execsql("CREATE TABLE `m_lib_ccdev_services` (".
            "`service_id` varchar(10) NOT NULL default '',".
            "`service_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`service_id`)".
            ") TYPE=InnoDB; ");
		
        // load initial data
        module::execsql("insert into m_lib_ccdev_services (service_id, service_name) values ('DENT', 'Dental Checkup')");
        module::execsql("insert into m_lib_ccdev_services (service_id, service_name) values ('WORM', 'Deworming')");
        module::execsql("insert into m_lib_ccdev_services (service_id, service_name) values ('VITA', 'Vitamin A')");
        module::execsql("insert into m_lib_ccdev_services (service_id, service_name) values ('NBS', 'Newborn Screening')");

        module::execsql("CREATE TABLE `m_patient_ccdev` (".
            "`ccdev_id` float NOT NULL auto_increment,".
            "`patient_id` float NOT NULL default '0',".
            "`mother_name` varchar(100) NOT NULL default '',".
            "`mother_educ_id` varchar(10) NOT NULL default '',".
            "`mother_occup_id` varchar(10) NOT NULL default '',".
            "`father_name` varchar(100) NOT NULL default '',".
            "`father_educ_id` varchar(10) NOT NULL default '',".
            "`father_occup_id` varchar(10) NOT NULL default '',".
            "`ccdev_timestamp` timestamp(14) NOT NULL,".
            "`ccdev_dob` date NOT NULL default '0000-00-00',".
            "`birth_weight` float NOT NULL default '0',".
            "`delivery_location` varchar(100) NOT NULL default '',".
            "`fully_immunized_date` date NOT NULL default '0000-00-00',".
            "`civil_registry_date` date NOT NULL default '0000-00-00',".
            "PRIMARY KEY  (`ccdev_id`), ".
            "INDEX `key_patient` (`patient_id`), ".
            "INDEX `key_mother_educ` (`mother_educ_id`), ".
            "INDEX `key_mother_occup` (`mother_occup_id`), ".
            "INDEX `key_father_educ` (`father_educ_id`), ".
            "INDEX `key_father_occup` (`father_occup_id`), ".
            "CONSTRAINT `m_patient_ccdev_patient` ".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ".
            "ON DELETE CASCADE, ".
            "CONSTRAINT `m_patient_ccdev_mother_educ` ".
            "FOREIGN KEY (`mother_educ_id`) REFERENCES `m_lib_education`(`educ_id`) ".
            "ON DELETE RESTRICT, ".
            "CONSTRAINT `m_patient_ccdev_mother_occup` ".
            "FOREIGN KEY (`mother_occup_id`) REFERENCES `m_lib_occupation`(`occup_id`) ".
            "ON DELETE RESTRICT, ".
            "CONSTRAINT `m_patient_ccdev_father_educ` ".
            "FOREIGN KEY (`father_educ_id`) REFERENCES `m_lib_education`(`educ_id`) ".
            "ON DELETE RESTRICT, ".
            "CONSTRAINT `m_patient_ccdev_father_occup` ".
            "FOREIGN KEY (`father_occup_id`) REFERENCES `m_lib_occupation`(`occup_id`) ".
            "ON DELETE RESTRICT".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_patient_ccdev_sibling` (".
            "`ccdev_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`sibling_patient_id` float NOT NULL default '0',".
            "PRIMARY KEY  (`ccdev_id`,`sibling_patient_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_patient_sibling` (`sibling_patient_id`),".
            "KEY `ccdev_id` (`ccdev_id`),".
            "CONSTRAINT `m_patient_ccdev_sibling_ibfk_3` FOREIGN KEY (`ccdev_id`) REFERENCES `m_patient_ccdev` (`ccdev_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_patient_ccdev_sibling_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_patient_ccdev_sibling_ibfk_2` FOREIGN KEY (`sibling_patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;  ");

    }

    function drop_tables() {

        module::execsql("set foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_consult_ccdev_services`;");
        module::execsql("DROP TABLE `m_consult_ccdev_vaccine`;");
        module::execsql("DROP TABLE `m_patient_ccdev_sibling`;");
        module::execsql("DROP TABLE `m_lib_ccdev_services`;");
        module::execsql("DROP TABLE `m_patient_ccdev`;");
        module::execsql("set foreign_key_checks=1;");

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_ccdev() {
    //
    // main submodule for ccdev consults
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ccdev')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $c = new ccdev;
        //$imci = new imci;

        mysql_query("ALTER TABLE `m_patient_ccdev` DROP PRIMARY KEY, ADD PRIMARY KEY(`ccdev_id`)");

        $c->ccdev_menu($menu_id, $post_vars, $get_vars);
        if ($post_vars["submitccdev"]) {
            $c->process_ccdev($menu_id, $post_vars, $get_vars);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        switch ($get_vars["ccdev"]) {
        case "VISIT1":

            if (!($c->registry_record_exists($patient_id))) {
                $c->form_ccdev_firstvisit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            } else {
                $c->display_ccdev_firstvisit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
                if ($post_vars["ccdev_id"]) {
                    $c->form_ccdev_firstvisit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
                }
				
				$c->display_form_low_birth_wt($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            }
            break;
        case "SIBLINGS":
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $get_vars["family_id"] = family::search_family($patient_id);
            ccdev::form_family_members($menu_id, $post_vars, $get_vars);
            break;
        case "IMCI":
            break;

		case "BFED":
			$c->form_ccdev_breastfeed($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
			break;

        case "SVC":

        default:
            $c->form_ccdev_services($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
			$c->form_ccdev_remarks($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            break;
        }

    }

    function form_family_members() {
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
        $ccdev_id = ccdev::registry_record_exists($patient_id);
        $family_id = family::get_family_id($patient_id);
        print "<b>".FTITLE_OTHER_FAMILY_MEMBERS."</b><br/><br/>";
        if ($family_id==0) {
            print "<font color='red'>".LBL_NO_FAMILY_ID."</font><br/>";
        } else {
            if ($ccdev_id==0) {
                print "<font color='red'>".LBL_NO_REGISTRY_ID."</font><br/>";
            } else {
                print "<span class='tiny'>".LBL_SELECT_SIBLING."</span><br/>";
                $sql = "select p.patient_id, p.patient_lastname, p.patient_firstname, p.patient_dob, p.patient_gender, round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age, f.family_role ".
                       "from m_family_members f, m_patient p where p.patient_id = f.patient_id and f.family_id = '".$get_vars["family_id"]."' ".
                       "and p.patient_id <> '$patient_id' ".
                       "order by p.patient_lastname, p.patient_firstname";
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SIBLINGS'>";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        while (list($pid, $plast, $pfirst, $pdob, $pgender, $p_age, $prole) = mysql_fetch_array($result)) {
                            print "<input type='checkbox' name='patients[]' value='$pid'/> $pfirst $plast ($p_age/$pgender)<br/>";
                        }
                    }
                }
                if ($_SESSION["priv_add"]) {
                    print "<input type='hidden' name='patient_id' value='$patient_id' />";
                    print "<input type='hidden' name='ccdev_id' value='$ccdev_id' />";
                    print "<br/><input type='submit' name='submitccdev' value='Add Sibling' class='tinylight' style='border: 1px solid black'/>";
                }
                print "</form>";
            }
        }
    }

    function form_ccdev_services() {
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
        $ccdev_id = ccdev::registry_record_exists($patient_id);
        if ($ccdev_id==0) {
            print "<font color='red'>".LBL_NO_REGISTRY_ID."</font><br/>";
        } else {
            print "<table width='300'>";
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SVC' name='form_ccdev_services' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_CCDEV_SERVICES."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            // SERVICE
            print "<table bgcolor='#FFFF66' width='300' cellpadding='3'><tr><td>";
            print "<span class='boxtitle'>".LBL_SERVICES."</span><br> ";
            print ccdev::checkbox_services();
            print "</td></tr>";
            print "</table>";

            print "</td></tr>";
            print "<tr valign='top'><td>";
            // VACCINE DATA
            print "<table bgcolor='#CCCCFF' width='300' cellpadding='3'><tr><td>";
            print "<span class='boxtitle'>".LBL_VACCINATIONS."</span><br> ";
            print ccdev::checkbox_vaccines($get_vars["consult_id"]);
            if ($_SESSION["priv_add"]) {				
				// complete or incomplete
                //$status = ccdev::get_immunization_status($patient_id);
                // check if all required vaccines have been given
                //$fully_immunized = ccdev::fully_immunized($ccdev_id);
            	/*if ($fully_immunized=="Y") {
                    if (eregi("Incomplete", $status)) {
                        print "<br/><input type='submit' name='submitccdev' value='Fully Immunized' class='tinylight' style='border: 1px solid black; background-color: #99FF00;' /><br/>";
                    }				
                }*/

            }
            print "</td></tr>";
            print "</table>";

            print "</td></tr>";
            print "<tr><td><br/>";
            if ($_SESSION["priv_add"]) {
                print "<input type='hidden' name='ccdev_id' value='$ccdev_id'>";
                print "<input type='submit' value = 'Update Record' class='textbox' name='submitccdev' style='border: 1px solid #000000'> ";
                //print "<input type='submit' value = 'For Vaccination' class='textbox' name='submitccdev' style='border: 1px solid #000000'> ";
            }
            print "</td></tr>";
            print "</form>";
            print "</table><br>";
        }
    }

	function form_ccdev_breastfeed(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];			
		endif;

		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $ccdev_id = ccdev::registry_record_exists($patient_id);

		if($ccdev_id=='0'):
			print "<font color='red'>".LBL_NO_REGISTRY_ID."</font><br/>";			
		else:
			echo "<form name='form_ccdev_bfed' action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=BFED' method='post'>";
			echo "<table>";
			echo "<tr><td><b>".FTITLE_CCDEV_BREASTFEED."</b></td></tr>";
			echo "<tr><td><span class='tinylight'>Please tick the month if mother exclusively breastfed this	child. To set month 6 date to blank, just delete the contents of the date box.</spans></td></tr>";			
			for($i=1;$i<7;$i++){
				
				$field = 'bfed_month'.$i;

				$q_field = mysql_query("SELECT $field, bfed_month6_date FROM m_patient_ccdev WHERE ccdev_id='$ccdev_id'") or die("Cannot query: 427");
				list($bfed_status,$bfed_date) = mysql_fetch_array($q_field);

				echo "<tr><td>";
				if($bfed_status=='Y'):
					echo "<input type='checkbox' name='bfed_month[]' value='$i' checked>Month $i</input>";
				else:
					echo "<input type='checkbox' name='bfed_month[]' value='$i'><font color='red'>Month $i</font></font></input>";
				endif;
				
				if($i==6):
					if($bfed_date=='0000-00-00'):
						$bfed_six_value = '';
					else:
						list($y,$m,$d) = explode('-',$bfed_date);
						$bfed_six_value = $m.'/'.$d.'/'.$y;
					endif;

					echo "&nbsp;&nbsp;&nbsp;<input type='text' name='date_bfed_six' size='7' value='$bfed_six_value'>&nbsp;";
					echo "<a href=\"javascript:show_calendar4('document.form_ccdev_bfed.date_bfed_six', document.form_ccdev_bfed.date_bfed_six.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
					echo "</input>";
				endif;
				echo "</td></tr>";
			}
		
			echo "<tr><td><input type='submit' name='submitccdev' value='Save Breastfeeding Status' style='border: 1px solid #000000'>&nbsp;<input type='reset' name='reset' value='Reset' style='border: 1px solid #000000'></td></tr>";
			echo "</table>";
			echo "</form>";

		endif;
	}

	
	function form_ccdev_remarks(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];			
		endif;
		
		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);			
		
		echo "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SVC' name='form_ccdev_remarks' method='POST'>";
		
		$q_remarks = mysql_query("SELECT ccdev_remarks FROM m_patient_ccdev WHERE patient_id='$patient_id'") or die("Cannot query: 464");
		
		list($remarks) = mysql_fetch_array($q_remarks);
				
		echo "<table>";
		echo "<span class='boxtitle'>CHILD CARE REMARKS</span>";		
		
		if($_POST["update_ccdev_remark"]):
			echo "<tr><td><textarea name='ccdev_remarks' cols='35' rows='4'>";		
		else:
			echo "<tr><td><textarea name='ccdev_remarks' cols='35' rows='4' readonly>";
		endif;
		
		
		echo $remarks;
		echo "</textarea></td></tr>";

		if($_POST["update_ccdev_remark"]):
			echo "<tr><td><input type='submit' name='submitccdev' value='Save Child Care Remarks' style='border: 1px solid #000000'></input>&nbsp;&nbsp;<input type='button' name='cancel' value='Cancel' onclick='history.go(-1)' style='border: 1px solid #000000'></input></td></tr>";				
		else:			
			echo "<tr><td><input type='submit' name='update_ccdev_remark' value='Update Child Care Remarks' style='border: 1px solid #000000'></input></td></tr>";				
		endif;

		echo "</table>";
				
		echo "</form>";
	}

    function fully_immunized() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ccdev_id = $arg_list[0];
        }
        // catch false then return
        $sql_lib = "select vaccine_id from m_lib_vaccine where vaccine_required = 'Y'";
        if ($result_lib = mysql_query($sql_lib)) {
            if (mysql_num_rows($result_lib)) {
                $ret_val = "Y";
                while (list($vaccine)=mysql_fetch_array($result_lib)) {
                    // do not include TT since this is pediatric
                    if (!ereg("TT", $vaccine)) {
                        $sql = "select vaccine_id from m_consult_ccdev_vaccine ".
                               "where vaccine_id = '$vaccine' and ccdev_id = '$ccdev_id'";
                        if ($result = mysql_query($sql)) {
                            if (mysql_num_rows($result)==0) {
                                $ret_val = "N";
                                break;
                            }
                        }
                    }
                }
                return $ret_val;
            }
        }
    }

    function get_age_months() {
    //
    // (days/365)*12
    // may be in error for 1 day every 4 years
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select round(((to_days(sysdate())-to_days(patient_dob))/365)*12,1) age from m_patient where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($age) = mysql_fetch_array($result);
                return $age;
            }
        }
    }

    function get_age_weeks() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        
		$sql = "select round((to_days(sysdate())-to_days(patient_dob))/7,1) age from m_patient where patient_id = '$patient_id'";
				
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($age) = mysql_fetch_array($result);
                return $age;
            }
        }
    }

    function check_vaccine_status() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
            $patient_id = $arg_list[1];
        }
        $sql = "select count(vaccine_id) from m_consult_ccdev_vaccine ".
               "where patient_id = '$patient_id' and vaccine_id = '$vaccine_id'";
		$result = mysql_query($sql) or die("Cannot query: 466");

		$sql_vacc = mysql_query("select count(vaccine_id) from m_consult_vaccine where patient_id='$patient_id' and vaccine_id='$vaccine_id' AND source_module!='ccdev'") or die("Cannot query: 467");
		
		if($result && $sql_vacc):
			list($ccdev_vac) = mysql_fetch_array($result);
			list($vacc)  = mysql_fetch_array($sql_vacc);

			$shots = $ccdev_vac + $vacc;

			if($shots>0):
			   return "<font color='blue'>GIVEN ($shots)</font>";
			else:
		        return "<font color='red'><b>NOT GIVEN</b></font>";
			endif;

		endif;		
    }



    function check_vaccine_timeliness() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
            $patient_id = $arg_list[1];
            $age_weeks = $arg_list[2];
            $interval = $arg_list[3];
        }
        $period = explode(",", $interval);
        $sql = "select vaccine_id from m_consult_ccdev_vaccine ".
               "where patient_id = '$patient_id' and vaccine_id = '$vaccine_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($shots) = mysql_fetch_array($result)) {
                    foreach($period as $key=>$value) {
                        print $period[$key]."/".$age_weeks."<br/>";
                    }
                    return "<font color='blue'>GIVEN ($shots)</font>";
                }
            } else {
            }
        }
    }

    function checkbox_vaccines() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
        }

        $patient_id = healthcenter::get_patient_id($consult_id);
        $age_weeks = ccdev::get_age_weeks($patient_id);

        $sql = "select vaccine_id, vaccine_interval, vaccine_name, vaccine_required ".
               "from m_lib_vaccine order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $interval, $name, $vaccine_required) = mysql_fetch_array($result)) {
                    $vaccine_status = ccdev::check_vaccine_status($id, $patient_id);
                    //$timely_status = ccdev::check_vaccine_timeliness($id, $patient_id, $age_weeks, $interval);
                    // do not display TT if pediatric
                    if (!ereg("TT", $id)) {
                        $required = ($vaccine_required=="Y"?" [EPI] ":"");
                        $ret_val .= "<input type='checkbox' name='vaccine[]' value='$id'> ";
                        $ret_val .= $name.$required." $vaccine_status<br>";
                    }
                }
                return $ret_val;
            }
        }
    }

    function get_immunization_status() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select fully_immunized_date from m_patient_ccdev where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                if ($date<>"0000-00-00") {
                    return "Completed <font color='red'><b>$date</b></font>";
                } else {
                    return "<font color='red'>Incomplete</font>";
                }
            } else {
                return "<font color='red'>No registry record</font>";
            }
        }
    }
	
	function display_form_low_birth_wt(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arglist[4];
		endif;

		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
		$q_ccdev = mysql_query("SELECT ccdev_id,birth_weight,lbw_date_started,lbw_date_completed,date_format(lbw_date_started,'%m/%d/%Y') lbw_sdate,date_format(lbw_date_completed,'%m/%d/%Y') lbw_edate FROM m_patient_ccdev WHERE patient_id='$patient_id'") or die("cannot query; 562");
		
		if(mysql_num_rows($q_ccdev)!=0):
			list($ccdev_id,$bwt,$lbw_start,$lbw_end,$lbw_sdate,$lbw_edate) = mysql_fetch_array($q_ccdev);
			
			$disp_sdate = ($lbw_start=='0000-00-00')?'':$lbw_sdate;
			$disp_edate = ($lbw_end=='0000-00-00')?'':$lbw_edate;

			if($this->check_low_birth_wt($ccdev_id,$pxid)=='Yes'):
				echo "<form method='post' name='form_lbw'>";

				echo "<span class='tinylight'><font color='red'>NOTE: This child has low birth weight. Indicate the dates <br>when IRON SUPPLEMENTATION started and completed.</font></span><br><br>";
				
				echo "<span class='tinylight'><font color='red'>To delete dates,just clear the textboxes and click 'Save Date'</font></span><br>";
				
				echo "<span class='boxtitle'>DATE STARTED &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
				echo "<input name='lbw_start_date' type='text' size='7' class='textbox' value='$disp_sdate'></input>";

				echo "<a href=\"javascript:show_calendar4('document.form_lbw.lbw_start_date', document.form_lbw.lbw_start_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
				
				echo "<span class='boxtitle'>DATE COMPLETED </span>";
				echo "<input name='lbw_completed_date' type='text' size='7' class='textbox' value='$disp_edate'></input>";
				echo "<a href=\"javascript:show_calendar4('document.form_lbw.lbw_completed_date', document.form_lbw.lbw_completed_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
				
				echo "<input type='submit' name='submitccdev' value='Save Date' class='tinylight' style='border: 1px solid #000000'></input>&nbsp;<input type='reset' value='Reset' class='tinylight' style='border: 1px solid #000000'></input>";
				echo "</form>";
			endif;
		endif;

	}
	
	function check_low_birth_wt(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$ccdev_id = $arg_list[0];
			$pxid = $arg_list[1];
		endif;

		$q_wt = mysql_query("SELECT round(birth_weight*1000,0) bwt_wks FROM m_patient_ccdev WHERE ccdev_id='$ccdev_id'") or die("Cannot query; 549");

		if(mysql_num_rows($q_wt)!=0):
			list($bwt_wks) = mysql_fetch_array($q_wt);
			if($bwt_wks<250):
				$bwt_status = 'Yes'; //mark as low birth weight
			else:
				$bwt_status = 'No';
			endif;

		else:

		endif;

		return $bwt_status;
	}

	
	function get_cpab_status($ccdev_id,$pxid){
		$q_mother = mysql_query("SELECT a.date_registered,date_format(a.ccdev_timestamp,'%Y-%m-%d') date_stamp,a.mother_px_id,b.patient_lastname,b.patient_firstname FROM m_patient_ccdev a, m_patient b WHERE a.patient_id='$pxid' AND b.patient_id=a.mother_px_id AND b.patient_gender='F'") or die(mysql_error());

		$get_bday = mysql_query("SELECT patient_dob from m_patient where patient_id='$pxid'") or die("cannot query: 581");
		list($px_dob) = mysql_fetch_array($get_bday);

		if(mysql_num_rows($q_mother)!=0):
			list($actual_date,$datestamp,$mother_id,$lname,$fname) = mysql_fetch_array($q_mother);							
			
			$status = mc::get_tt_status(0,$mother_id,$px_dob);
			echo "<font color='red'>".$status."</font>";
		else:
			echo "<font color='red'>Unknown (mother's ID does not exists)</font>";
		endif;
		
	}


    function registry_record_exists() {
    //
    // this makes sure that all entries for registry tables
    // have the correct registry id embedded
    // assumption: only one registry id for each patient
    //      they go through childhood only once
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select ccdev_id from m_patient_ccdev ".
               "where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($id) = mysql_fetch_array($result);
                return $id;
            }
        }
    }

    function ccdev_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["ccdev"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SVC");
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1' class='groupmenu'>".strtoupper(($get_vars["ccdev"]=="VISIT1"?"<b>FIRST VISIT</b>":"FIRST VISIT"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SIBLINGS' class='groupmenu'>".strtoupper(($get_vars["ccdev"]=="SIBLINGS"?"<b>SIBLINGS</b>":"SIBLINGS"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SVC' class='groupmenu'>".strtoupper(($get_vars["ccdev"]=="SVC"?"<b>SERVICES</b>":"SERVICES"))."</a>";
		print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=BFED' class='groupmenu'>".strtoupper(($get_vars["ccdev"]=="BFED"?"<b>BREASTFEEDING</b>":"BREASTFEEDING"))."</a>";
        //print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=IMCI' class='groupmenu'>".strtoupper(($get_vars["ccdev"]=="IMCI"?"<b>IMCI</b>":"IMCI"))."</a>";
        print "</td></tr></table><br/>";
    }

    function process_ccdev() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch($post_vars["submitccdev"]) {
        case "Fully Immunized":
            $sql = "update m_patient_ccdev set fully_immunized_date = sysdate() ".
                   "where ccdev_id = '".$post_vars["ccdev_id"]."'";
            if ($result = mysql_query($sql)) {
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SVC");
            }
            break;
        case "Add Sibling":
            if ($post_vars["patient_id"]) {
                foreach($post_vars["patients"] as $key=>$value) {
                    $sql = "insert into m_patient_ccdev_sibling (ccdev_id, patient_id, sibling_patient_id ) ".
                           "values ('".$post_vars["ccdev_id"]."', '".$post_vars["patient_id"]."', '$value')";
                    $result = mysql_query($sql);
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1");
            }
            break;
        case "Delete Data":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_patient_ccdev ".
                        "where ccdev_id = '".$post_vars["ccdev_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1");
                }
            } else {
                if ($post_vars["confirm_delete"]=="Y") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1");
                }
            }
            break;

		case "Save Child Care Remarks":
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);

			$update_remark = mysql_query("UPDATE m_patient_ccdev SET ccdev_remarks='$_POST[ccdev_remarks]' WHERE patient_id='$patient_id'") or die("Cannot query: 834");		

			if($update_remark):
				header("$_SERVER[SELF]?page=$get_vars[page]&menu_id=$menu_id&consult_id=$get_vars[consult_id]&ptmenu=$get_vars[ptmenu]&module=$get_vars[module]&ccdev=SVC");
			endif;

			break;

		case "Save Breastfeeding Status":
			$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
			$q_ccdev = mysql_query("SELECT ccdev_id FROM m_patient_ccdev WHERE patient_id='$patient_id'") or die("Cannot query: 784");
		
		list($ccdevid) = mysql_fetch_array($q_ccdev);
		
		if(isset($_POST[bfed_month])):
			$arr_bfed_month = $_POST[bfed_month];
		else:
			$arr_bfed_month = array();
		endif;

			for($i=1;$i<7;$i++){
				$field = 'bfed_month'.$i;
				
				if(in_array($i,$arr_bfed_month)):
					if($i==6):
						if(!empty($_POST[date_bfed_six])):
							list($m,$d,$y) = explode('/',$_POST[date_bfed_six]);
							$bfed_six = $y.'-'.$m.'-'.$d;
							
							$check_age = mysql_query("SELECT (TO_DAYS('$bfed_six')-TO_DAYS(patient_dob)) days_age FROM m_patient where patient_id='$patient_id'") or die("Cannot query: 795");
							list($araw)	= mysql_fetch_array($check_age);
							
							if($araw<180):
								echo "<font color='red'>Cannot add. Date for the sixth month should be at least six months after patient's birth</font>";
							else:
	
								$update_ccdev = mysql_query("UPDATE m_patient_ccdev SET $field='Y',bfed_month6_date='$bfed_six' WHERE ccdev_id='$ccdevid'") or die(mysql_error());	

							endif;
						else:
							$bfed_six = '0000-00-00';
							$update_ccdev = mysql_query("UPDATE m_patient_ccdev SET $field='Y',bfed_month6_date='0000-00-00' WHERE ccdev_id='$ccdevid'") or die(mysql_error());													
						endif;						

					else:
						$update_ccdev = mysql_query("UPDATE m_patient_ccdev SET $field='Y' WHERE ccdev_id='$ccdevid'") or die(mysql_error());					
					endif;
				else:
					$update_ccdev = mysql_query("UPDATE m_patient_ccdev SET $field='N' WHERE ccdev_id='$ccdevid'") or die(mysql_error());
				endif;
			}
			break;

        case "Update Data":

			list($reg_month,$reg_date,$reg_year) = explode('/',$_POST[ccdev_date_reg]);
			$ccdev_reg_date = $reg_year.'-'.$reg_month.'-'.$reg_date;			


            $sql = "update m_patient_ccdev set ".
                   "mother_educ_id = '".$post_vars["mother_educ"]."', ".
                   "mother_occup_id = '".$post_vars["mother_occup"]."', ".
                   "father_name = '".ucwords($post_vars["father_name"])."', ".
                   "father_educ_id = '".$post_vars["father_educ"]."', ".
                   "father_occup_id = '".$post_vars["father_occup"]."', ".
                   "delivery_location = '".$post_vars["delivery_location"]."', ".
                   "birth_weight = '".$post_vars["birth_weight"]."', ".
                   "date_registered = '".$ccdev_reg_date."', ".
                   "mother_px_id = '".$_POST[mother_px_id]."' ".
                   "where ccdev_id = '".$post_vars["ccdev_id"]."'";
			
			$result = mysql_query($sql) or die(mysql_error());
            
			if ($result) {
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1");
            }
            break;
        case "Save Data":
            if ($post_vars["mother_name"] && $post_vars["mother_occup"] &&
                $post_vars["mother_educ"] && $post_vars["delivery_location"] && isset($_POST["ccdev_date_reg"])) {
                $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
                $patient_dob = patient::get_dob($patient_id);
                
				$sql = "insert into m_patient_ccdev set patient_id='$patient_id',mother_name='$post_vars[mother_name]',mother_educ_id='$post_vars[mother_educ]',mother_occup_id='$post_vars[mother_occup]',father_name='$post_vars[father_name]',father_educ_id='$post_vars[father_educ]',father_occup_id='$post_vars[father_occup]',ccdev_timestamp='sysdate()',ccdev_dob='$patient_dob',birth_weight='$post_vars[birth_weight]',delivery_location='$post_vars[delivery_location]',date_registered='$_POST[ccdev_date_range]',mother_px_id='$_POST[mother_px_id]'";

				/*print $sql = "insert into m_patient_ccdev (patient_id, mother_name, mother_educ_id, mother_occup_id, father_name, father_educ_id, father_occup_id, ccdev_timestamp, ccdev_dob, birth_weight, delivery_location, date_registered,mother_px_id) ".
                       "values ('$patient_id', '".$post_vars["mother_name"]."', '".$post_vars["mother_educ"]."', '".$post_vars["mother_occup"]."', ".
                       "'".$post_vars["father_name"]."', '".$post_vars["father_educ"]."', '".$post_vars["father_occup"]."', ".
                       "sysdate(), '$patient_dob', '".$post_vars["birth_weight"]."', '".$post_vars["delivery_location"]."')";
                */
				
				if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1");
                }

            }
            break;
        case "Update Record":
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $age_weeks = ccdev::get_age_weeks($patient_id);
            if ($post_vars["services"]) {
                foreach($post_vars["services"] as $key=>$value) {
                    $sql = "insert into m_consult_ccdev_services (ccdev_id, consult_id, user_id, patient_id, ccdev_timestamp, service_id, age_on_service) ".
                           "values ('".$post_vars["ccdev_id"]."', '".$get_vars["consult_id"]."', '".$_SESSION["userid"]."', '$patient_id', sysdate(), '$value', '$age_weeks')";
                    $result = mysql_query($sql);
                }
            }
            if ($post_vars["vaccine"]) {
                foreach($post_vars["vaccine"] as $key=>$value) {
                    $sql = "insert into m_consult_ccdev_vaccine (ccdev_id, consult_id, user_id, patient_id, vaccine_timestamp, actual_vaccine_date, vaccine_id, age_on_vaccine) ".
                           "values ('".$post_vars["ccdev_id"]."', '".$get_vars["consult_id"]."', '".$_SESSION["userid"]."', '$patient_id', sysdate(), sysdate(), '$value', '$age_weeks')";
                    $result = mysql_query($sql);

                    // update patient vaccine record also
                    $sql_vaccine = "insert into m_consult_vaccine (consult_id, patient_id, vaccine_id, user_id, vaccine_timestamp, actual_vaccine_date, source_module) ".
                                   "values ('".$get_vars["consult_id"]."', '$patient_id', '$value', '".$_SESSION["userid"]."', sysdate(), sysdate(), 'ccdev')";
                    $result_vaccine = mysql_query($sql_vaccine);

                }
            }
            break;
        case "For Vaccination":
            break;

		case "Save Date":
			$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);

			if(!empty($_POST[lbw_start_date])):
				
				list($smonth,$sdate,$syear) = explode('/',$_POST[lbw_start_date]);
				
				$lbw_sdate = $syear.'-'.$smonth.'-'.$sdate;

				$ccdev_info = mysql_query("SELECT ccdev_id,date_registered ,(TO_DAYS('$lbw_sdate')-TO_DAYS(date_registered)) days_diff FROM m_patient_ccdev WHERE patient_id='$patient_id'") or die(mysql_error());

				list($ccdev_id,$date_reg,$days_diff) = mysql_fetch_array($ccdev_info);

				if($days_diff<0):
					echo "<font color='red'>Date not saved. The date started occurs before date of registration.</font><br><br>";
				else:
					$update_ccdev = mysql_query("UPDATE m_patient_ccdev SET lbw_date_started='$lbw_sdate' WHERE ccdev_id='$ccdev_id'") or die("CAnnot query: 800");
				endif;

			else:
				$update_ccdev = mysql_query("UPDATE m_patient_ccdev SET lbw_date_started='0000-00-00' WHERE ccdev_id='$ccdev_id'") or die("CAnnot query: 808");
			endif;


			if(!empty($_POST[lbw_completed_date])):
				list($emonth,$edate,$eyear) = explode('/',$_POST[lbw_completed_date]);
				$lbw_edate = $eyear.'-'.$emonth.'-'.$edate;

				$ccdev_info = mysql_query("SELECT ccdev_id,date_registered,lbw_date_started,(TO_DAYS('$lbw_edate')-TO_DAYS(lbw_date_started)) days_diff FROM m_patient_ccdev WHERE patient_id='$patient_id' AND lbw_date_started!='0000-00-00'") or die(mysql_error());
				
				if(mysql_num_rows($ccdev_info)!=0):
					list($ccdev_id,$date_reg,$lbw_sdate,$day_diff) = mysql_fetch_array($ccdev_info);
					
					if($day_diff<0):
						echo "<font color='red'>Date not saved. Date of completion of iron supplementation intake occurs before the start date.</font><br><br>";
					else:
						$update_ccdev2 = mysql_query("UPDATE m_patient_ccdev SET lbw_date_completed='$lbw_edate' WHERE ccdev_id='$ccdev_id'") or die("Cannot query: 820");

					endif;
				else:
					echo "<font color='red'>Date not saved. Start date of iron supplementation is not yet supplied.</font><br><br>";
				endif;
			else:
				$update_ccdev2 = mysql_query("UPDATE m_patient_ccdev SET lbw_date_completed='0000-00-00' WHERE ccdev_id='$ccdev_id'") or die("Cannot query: 820");
			endif;
				
				if($update_ccdev): echo "<font color='red'>Date started was successfully been updated.</font><br><br>"; endif;
				if($update_ccdev2): echo "<font color='red'>Date completed was successfully been updated.</font><br><br>"; endif;				
			break;
        }
    }

    function display_ccdev_firstvisit() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<b>".FTITLE_FIRST_VISIT_DATA."</b><br/><br/>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select ccdev_id, patient_id, ".
               "mother_name, mother_educ_id, mother_occup_id, ".
               "father_name, father_educ_id, father_occup_id, ".
               "date_format(ccdev_timestamp, '%a %d %b %Y, %h:%i%p'), ccdev_dob, ".
               "birth_weight, delivery_location,date_format(date_registered,'%a %d %b %Y') actual_serv_date ".
               "from m_patient_ccdev where patient_id = '$patient_id'";

		$result = mysql_query($sql) or die(mysql_error());

        if ($result) {
            if (mysql_num_rows($result)) {
                list($cid, $pid, $mname, $meduc, $moccup, $fname, $feduc, $foccup, $ts, $dob, $bw, $loc, $actual_serv_date) = mysql_fetch_array($result);
                print "<table cellpadding='3' style='border: 1px dashed black'><tr><td>";
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=".$get_vars["ccdev"]."#visit'>";
                print "REGISTRY ID: <b>".module::pad_zero($cid,7)."</b><br/>";
                print "DATE: <b>$actual_serv_date</b><br/><br/>";
                print "MOTHER: $mname<br/>";
                print "Education: ".education::get_education_name($meduc)."<br/>";
                print "Occupation: ".occupation::get_occupation_name($moccup)."<br/><br/>";
                print "FATHER: $fname<br/>";
                print "Education: ".education::get_education_name($feduc)."<br/>";
                print "Occupation: ".occupation::get_occupation_name($foccup)."<br/><br/>";
                print "DELIVERY LOCATION: $loc<br/>";
                print "BIRTH WEIGHT: $bw KG<br/><br/>";
                if ($_SESSION["priv_update"]) {
                    print "<input type='hidden' name='ccdev_id' value='$cid'/>";
                    print "<input type='submit' name='submitccdev' value='Update Visit Data' class='tinylight' style='border: 1px solid black'/>";
                }
                print "</form>";
                print "</td></tr></table><br/>";
            }
        }
    }

    function form_ccdev_firstvisit() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["ccdev_id"] && $post_vars["submitccdev"]) {
            $sql = "select mother_name, mother_educ_id, mother_occup_id, ".
                   "father_name, father_educ_id, father_occup_id, ".
                   "birth_weight, delivery_location,date_registered,mother_px_id ".
                   "from m_patient_ccdev ".
                   "where ccdev_id = '".$post_vars["ccdev_id"]."'";
            if ($result = mysql_query($sql)) {

                if (mysql_num_rows($result)) {

                    $ccdev = mysql_fetch_array($result);
                }
            }
        }

		
		if(empty($ccdev["date_registered"])):
			$ccdev_reg_date = '';
		else:
			if($ccdev["date_registered"]!='0000-00-00'):		
				list($reg_yr,$reg_month,$reg_date) = explode('-',$ccdev["date_registered"]);
				$ccdev_reg_date = $reg_month.'/'.$reg_date.'/'.$reg_yr;	
			else:
				$ccdev_reg_date = '';
			endif;
		endif;
		
        
		
		print "<a name='visit'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=VISIT1' name='form_consult_ccdev' method='post'>";
		
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_CCDEV_DATA_FORM."</b><br/><br/>";
        print "</td></tr>";	
        print "<tr valign='top'><td>";
        print "<span class='tinylight'><b>IMPORTANT:</b> ".INSTR_FIRST_VISIT."</span><br/><br/>";
        // MATERNAL DATA
        print "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
		

		

		print "<tr><td>";
		print "<span class='boxtitle'>DATE REGISTERED</span><br>";
		print "<input type='text' size='11' class='textbox' name='ccdev_date_reg' value='$ccdev_reg_date' readonly></input>&nbsp;";
		echo "<a href=\"javascript:show_calendar4('document.form_consult_ccdev.ccdev_date_reg', document.form_consult_ccdev.ccdev_date_reg.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		print "</td></tr>";
		
		echo "<tr><td>";
        if (!isset($post_vars["mother_name"])) {
            $post_vars["mother_name"] = healthcenter::get_mothers_name($get_vars["consult_id"]);
        }

        print "<span class='boxtitle'>".LBL_MOTHERS_NAME."</span><br> ";
        print "<input type='text' size='30' class='textbox' ".($_SESSION["isadmin"]||!$get_vars["patient_id"]?"":"disabled")." name='mother_name' value='".($ccdev["mother_name"]?$ccdev["mother_name"]:$post_vars["mother_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
		
		echo "<tr><td>";
		print "<span class='boxtitle'>PATIENT NUMBER OF MOTHERS IN CHITS</span>";
		echo "<input type='text' name='mother_px_id' id='mothers' size='4' value='$ccdev[mother_px_id]'></input>&nbsp;";
		echo "<input type='button' value='Verify' onclick='verify_mother_id();'></input>.";
		echo "</td></tr>";

        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_MOTHERS_OCCUPATION."</span><br> ";
        $sql_moccup = "select occup_id, occup_name from m_lib_occupation order by occup_name";
        if ($result = mysql_query($sql_moccup)) {
            if (mysql_num_rows($result)) {
                print  "<select size='5' name='mother_occup' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($ccdev["mother_occup_id"]==$id?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_MOTHERS_EDUCATION."</span><br> ";
        $sql_meduc = "select educ_id, educ_name from m_lib_education order by educ_name";
        if ($result = mysql_query($sql_meduc)) {
            if (mysql_num_rows($result)) {
                print "<select size='5' name='mother_educ' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($ccdev["mother_educ_id"]==$id?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
        print "</td></tr>";
        print "</table>";

        print "</td></tr>";
        print "<tr valign='top'><td>";
        // PATERNAL DATA
        print "<table bgcolor='#99CCFF' width='300' cellpadding='3'><tr><td>";
        print "<span class='boxtitle'>".LBL_FATHERS_NAME."</span><br> ";
        print "<input type='text' size='30' class='textbox' ".($_SESSION["isadmin"]||!$get_vars["patient_id"]?"":"disabled")." name='father_name' value='".($ccdev["father_name"]?$ccdev["father_name"]:$post_vars["father_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FATHERS_OCCUPATION."</span><br> ";
        $sql_moccup = "select occup_id, occup_name from m_lib_occupation order by occup_name";
        if ($result = mysql_query($sql_moccup)) {
            if (mysql_num_rows($result)) {
                print  "<select size='5' name='father_occup' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($ccdev["father_occup_id"]==$id?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FATHERS_EDUCATION."</span><br> ";
        $sql_meduc = "select educ_id, educ_name from m_lib_education order by educ_name";
        if ($result = mysql_query($sql_meduc)) {
            if (mysql_num_rows($result)) {
                print "<select size='5' name='father_educ' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($ccdev["father_educ_id"]==$id?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
        print "</td></tr>";
        print "</table>";

        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BIRTH_WEIGHT."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='10' name='birth_weight' value='".($ccdev["birth_weight"]?$ccdev["birth_weight"]:$post_vars["birth_weight"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_DELIVERY_LOCATION."</span><br> ";
        print ccdev::show_delivery_location($ccdev["delivery_location"]?$ccdev["delivery_location"]:$post_vars["delivery_location"]);
        print "</td></tr>";
        print "<tr><td><br/>";
        if ($post_vars["ccdev_id"]) {
            if ($_SESSION["priv_update"]) {
                print "<input type='hidden' name='ccdev_id' value='".$post_vars["ccdev_id"]."' />";
                print "<input type='submit' value = 'Update Data' class='textbox' name='submitccdev' style='border: 1px solid #000000'> ";
                print "<input type='submit' value = 'Delete Data' class='textbox' name='submitccdev' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Save Data' class='textbox' name='submitccdev' style='border: 1px solid #000000'><br>";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function show_delivery_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }
        $ret_val .= "<select name='delivery_location' class='textbox'>";
        $ret_val .= "<option value=''>Select Location</option>";
        $ret_val .= "<option value='HOME' ".($location_id=="HOME"?"selected":"").">Home</option>";
        $ret_val .= "<option value='HOSP' ".($location_id=="HOSP"?"selected":"").">Hospital</option>";
        $ret_val .= "<option value='LYIN' ".($location_id=="LYIN"?"selected":"").">Lying-In Clinic</option>";
        $ret_val .= "</select>";
        return $ret_val;
    }

    function _details_ccdev() {
    //
    // main submodule for ccdev consults
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ccdev')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitdetail"]) {
            ccdev::process_detail($menu_id, $post_vars, $get_vars);
        }
        print "<b>".FTITLE_PATIENT_DATA."</b><br/><br/>";
        ccdev::display_patient_data($menu_id, $post_vars, $get_vars);
        print "<br/>";
        ccdev::display_siblings($menu_id, $post_vars, $get_vars);
        print "<br/>";
        print "<b>".FTITLE_VACCINE_RECORD."</b><br/><br/>";
        ccdev::display_vaccine_record($menu_id, $post_vars, $get_vars);
        print "<br/>";
        print "<b>".FTITLE_SERVICE_RECORD."</b><br/><br/>";
        ccdev::display_service_record($menu_id, $post_vars, $get_vars);
    }

    function process_detail() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        switch($post_vars["submitdetail"]) {
		case "Update Service":			

			list($serv_mo,$serv_date,$serv_year) = explode('/',$_POST[ccdev_serv_date]);
			$actual_serv_date = $serv_year.'-'.$serv_mo.'-'.$serv_date;
			
			$get_px_id = mysql_query("SELECT b.patient_lastname,b.patient_firstname,b.patient_dob,(TO_DAYS('$actual_serv_date')-TO_DAYS(b.patient_dob)) age_days FROM m_consult_ccdev_services a,m_patient b WHERE a.ccdev_id='$post_vars[ccdev_id]' AND b.patient_id=a.patient_id") or die(mysql_error());
			
			if(mysql_num_rows($get_px_id)!=0):
				list($lname,$fname,$dob,$age_days) = mysql_fetch_array($get_px_id);				
				
				$age_weeks = round($age_days/7,1);

				if($age_days<0):
					echo "<font color='red' size='3'>Update unsuccessful.Newborn screening should occur on or after this patient's date of birth. ($dob)</font><br>";
				else:
					$update_ccdev_service = mysql_query("UPDATE m_consult_ccdev_services SET ccdev_service_date='$actual_serv_date' ,age_on_service='$age_weeks' WHERE service_id='$post_vars[service]' AND ccdev_id='$post_vars[ccdev_id]' AND ccdev_timestamp='$post_vars[sts]'") or die("Cannot query: 960");
					
					if($update_ccdev_service):
						echo "<font color='red' size='3'>Update was successfully done!</font><br>";
					endif;
				endif;


			
			else:
				
			endif;

			
			break;

        case "Delete Service":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_ccdev_services ".
                       "where service_id = '".$post_vars["service"]."' and ".
                       "ccdev_id = '".$post_vars["ccdev_id"]."' and ".
                       "ccdev_timestamp = '".$post_vars["sts"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
            break;
        case "Delete Record";
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_ccdev_vaccine ".
                       "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                       "ccdev_id = '".$post_vars["ccdev_id"]."' and ".
                       "vaccine_timestamp = '".$post_vars["ts"]."'";
                if ($result = mysql_query($sql)) {
                    $sql_vaccine = "delete from m_consult_vaccine ".
                                   "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                                   "consult_id = '".$post_vars["vaccine_consult_id"]."' and ".
                                   "source_module = 'ccdev'";
                    $result_vaccine = mysql_query($sql_vaccine);
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
            break;
        case "Update Record";
            $adr = ($post_vars["adr_flag"]?"Y":"N");
            list($month,$day,$year) = explode("/", $post_vars["actual_vaccine_date"]);
            $actual_vaccine_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $patient_dob = patient::get_dob($patient_id);
            $sql = "update m_consult_ccdev_vaccine set ".
                   "adr_flag = '$adr', ".
                   "actual_vaccine_date = '$actual_vaccine_date', ".
                   "age_on_vaccine = (to_days('$actual_vaccine_date')-to_days('$patient_dob'))/7 ".
                   "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                   "ccdev_id = '".$post_vars["ccdev_id"]."' and ".
                   "vaccine_timestamp = '".$post_vars["ts"]."'";
            if ($result = mysql_query($sql)) {
                $sql_vaccine = "update m_consult_vaccine set ".
                               "actual_vaccine_date = '$actual_vaccine_date' ".
                               "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                               "consult_id = '".$post_vars["vaccine_consult_id"]."' and ".
                               "source_module = 'ccdev'";
                $result_vaccine = mysql_query($sql_vaccine);
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=SVC&vaccine=".$post_vars["vaccine"]."&ts=".$post_vars["ts"]."&ccdev_id=".$post_vars["ccdev_id"]."#detail");
            }
        }
    }

    function display_siblings() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $ccdev_id = ccdev::registry_record_exists($get_vars["consult_id"]);
        print "<b>".FTITLE_PATIENT_SIBLINGS."</b><br/>";
        $sql = "select s.sibling_patient_id, p.patient_lastname, p.patient_firstname, p.patient_dob, p.patient_gender, round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age ".
               "from m_patient p, m_patient_ccdev_sibling s ".
               "where s.sibling_patient_id = p.patient_id and s.ccdev_id = '$ccdev_id' ".
               "order by p.patient_lastname, p.patient_firstname";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($pid, $plast, $pfirst, $pdob, $pgender, $p_age, $prole) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> $pfirst $plast ($p_age/$pgender)<br/>";
                }
            } else {
                print "<font color='red'>No siblings.</font><br/>";
            }
        }
    }

    function display_patient_data() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
		$actual_weight = wtforage::get_body_weight($get_vars["consult_id"]);
        $ccdev_id = ccdev::registry_record_exists($patient_id);
        print "<span class='tinylight'>";
        print LBL_CCDEV_ID.": <font color='red'>".($ccdev_id?module::pad_zero($ccdev_id,7):"none")."</font><br/>";
        print LBL_AGE_IN_WEEKS.": ".ccdev::get_age_weeks($patient_id)."<br/>";
        print LBL_WEIGHT.": $actual_weight<br/>";
        list($min, $max, $class) = wtforage::_wtforage($get_vars["consult_id"]);
        if ($class) {
            print LBL_WT_FOR_AGE.": <font color='red'>".strtoupper($class)."</font> (min: $min, max: $max)<br/>";
        } else {
            print LBL_WT_FOR_AGE.": <font color='red'>".LBL_NO_WEIGHT_AVAILABLE."</font><br/>";
        }
        
		print LBL_IMMUNIZATION_STATUS.": ";
		
		$vacc_status = ccdev::determine_vacc_status($patient_id);

		if($vacc_status=='Incomplete'):
			echo "<font color='red'><b>$vacc_status</b></font><br>";
		else:
			echo "<b>$vacc_status</b><br>";
		endif;
		
		print "CHILD PROTECTECTED AT BIRTH".": ";
		echo ccdev::get_cpab_status($ccdev_id,$patient_id)."<br>";				

		print "LOW BIRTH WEIGHT".": ";		
		echo ccdev::check_low_birth_wt($ccdev_id,$patient_id).'<br>';
        print "</span>";
    }

    function display_vaccine_record() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
		$found = 0;
        $sql = "select ccdev_id, vaccine_id, vaccine_timestamp, date_format(actual_vaccine_date,'%a %d %b %Y') ".
               "from m_consult_ccdev_vaccine ".
               "where patient_id = '$patient_id' order by vaccine_id, vaccine_timestamp desc";
		
		$result = mysql_query($sql) or die("Cannot query: 1253");

        if ($result) {
            if (mysql_num_rows($result)) {
				$found = 1;
                while (list($cid, $vaccine, $vstamp, $vdate) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=".$get_vars["ccdev"]."&vaccine=$vaccine&ts=$vstamp&ccdev_id=$cid#detail'>$vaccine</a> $vdate<br/>";
                    if ($get_vars["vaccine"]==$vaccine && $get_vars["ts"]==$vstamp && $get_vars["ccdev_id"]==$cid) {
                        ccdev::display_vaccine_record_details($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                //print "<font color='red'>No records</font><br/>";
            }
        }
		

		$sql_outside_antigens = mysql_query("SELECT consult_id,date_format(actual_vaccine_date,'%a %d %b %Y'),vaccine_id FROM m_consult_vaccine WHERE patient_id='$patient_id' AND vaccine_id IN ('BCG','DPT1','DPT2','DPT3','HEPB1','HEPB2','HEPB3','MSL','OPV1','OPV2','OPV3') AND source_module!='ccdev' ORDER by vaccine_id ASC, actual_vaccine_date ASC") or die("Cannot query: 1272");

		if(mysql_num_rows($sql_outside_antigens)!=0):

			$found = 1;			
			print "<br>Recorded in MODULE::VACCINES: <br>";

			while(list($consultid,$actual_vdate,$vacc_id)=mysql_fetch_array($sql_outside_antigens)){

				print "<img src='../images/arrow_redwhite.gif' border='0'></img>&nbsp;";
				print "$vacc_id  (".$actual_vdate.")<br />";				
			}
		endif;

		if($found==0):
			print "<font color='red'>No records</font><br/>";
		endif;
    }

    
	function display_service_record() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
		
		/*print_r($get_vars);
		echo "<br>";*/

        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select ccdev_id, service_id, date_format(ccdev_timestamp,'%a %d %b %Y') service_date, ccdev_timestamp,date_format(ccdev_service_date,'%a %d %b %Y') actual_service_date,ccdev_service_date ".
               "from m_consult_ccdev_services ".
               "where patient_id = '$patient_id' order by service_id, ccdev_timestamp desc";
		
		$result = mysql_query($sql) or die(mysql_error());
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($cid, $service, $sdate, $ts,$actual_service_date,$serv_date) = mysql_fetch_array($result)) {


                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ccdev&ccdev=SVC&service_id=$service&sts=$ts&ccdev_id=$cid#detail'>".ccdev::get_service_name($service)."</a>&nbsp;";
					echo ($serv_date=='0000-00-00')?$sdate." <font color='red' size='2'><b>(pls update actual service date)</b></font>":$actual_service_date;
					echo "<br/>";					

                    if ($get_vars["service_id"]==$service && $get_vars["sts"]==$ts && $get_vars["ccdev_id"]==$cid) { //this was '=' instead of '==' before
                        ccdev::show_service_details($menu_id, $post_vars, $get_vars);
                    }
					
			
                }
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
    }

    function display_vaccine_record_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select ccdev_id, consult_id, user_id, patient_id, vaccine_timestamp, date_format(vaccine_timestamp, '%a %d %b %Y, %h:%i%p'), ".
               "vaccine_id, adr_flag, actual_vaccine_date, round(age_on_vaccine,2) ".
               "from m_consult_ccdev_vaccine where ".
               "ccdev_id = '".$get_vars["ccdev_id"]."' and vaccine_id = '".$get_vars["vaccine"]."' and ".
               "vaccine_timestamp = '".$get_vars["ts"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($cdid, $cid, $uid, $pid, $vstamp, $vdate, $vid, $adr, $actual_date, $age_vaccine) = mysql_fetch_array($result);
                print "<a name='detail'>";
                print "<table width='250' cellpadding='3' style='border:1px dashed black'><tr><td>";
                print "<form name='form_vaccine_detail' method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=".$get_vars["ccdev"]."&vaccine=$vid&ts=$vstamp'>";
                print "<span class='tinylight'>";
                print "REGISTRY ID: $cdid<br/>";
                print "VACCINE: ".vaccine::get_vaccine_name($vid)."<br/>";
                print "REPORT DATE: $vdate<br/>";
                //print "AGE ON VACCINATION: ".ccdev::age_on_vaccination($pid, $actual_date)." weeks<br/>";
                print "AGE ON VACCINATION: $age_vaccine weeks<br/>";
                print "RECORDED BY: ".user::get_username($uid)."<br/>";
                print "ACTUAL VACCINE DATE:<br/>";
                if ($actual_date<>"0000-00-00") {
                    list($year, $month, $day) = explode("-", $actual_date);
                    $conv_date = "$month/$day/$year";
                }
                print "<input type='text' size='10' maxlength='10' class='tinylight' name='actual_vaccine_date' value='".($conv_date?$conv_date:$post_vars["actual_vaccine_date"])."' style='border: 1px solid #000000'> ";
                print "<a href=\"javascript:show_calendar4('document.form_vaccine_detail.actual_vaccine_date', document.form_vaccine_detail.actual_vaccine_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
                print "<input type='checkbox' name='adr_flag' ".($adr=="Y"?"checked":"")." value='1'/> ".INSTR_ADVERSE_VACCINE_REACTION."<br/>";
                print "<input type='hidden' name='vaccine' value='".$get_vars["vaccine"]."'/>";
                print "<input type='hidden' name='vaccine_consult_id' value='$cid'/>";
                print "<input type='hidden' name='ts' value='".$get_vars["ts"]."'/>";
                print "<input type='hidden' name='ccdev_id' value='".$get_vars["ccdev_id"]."'/>";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdetail' value='Delete Record' class='tinylight' style='border: 1px solid black'/> ";
                }
                if ($_SESSION["priv_add"] || $_SESSION["priv_update"]) {
                    print "<input type='submit' name='submitdetail' value='Update Record' class='tinylight' style='border: 1px solid black'/> ";
                }
                print "</span>";
                print "</form>";
                print "</td></tr></table>";
            }
        }
    }

    function show_service_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select ccdev_id, consult_id, user_id, patient_id, ccdev_timestamp, date_format(ccdev_timestamp, '%a %d %b %Y, %h:%i%p'), ".
               "service_id, round(age_on_service,2),date_format(ccdev_service_date, '%m/%d/%Y'),ccdev_service_date ".
               "from m_consult_ccdev_services where ".
               "ccdev_id = '".$get_vars["ccdev_id"]."' and service_id = '".$get_vars["service_id"]."' and ".
               "ccdev_timestamp = '".$get_vars["sts"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($cdid, $cid, $uid, $pid, $cstamp, $sdate, $sid, $age_service, $service_date, $ccdev_sdate) = mysql_fetch_array($result);
                print "<a name='detail'>";
                print "<table width='250' cellpadding='3' style='border:1px dashed black'><tr><td>";
                print "<form name='form_service_detail' method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ccdev=".$get_vars["ccdev"]."'>";
                print "<span class='tinylight'>";
                print "REGISTRY ID: $cdid<br/>";
                print "SERVICE: ".ccdev::get_service_name($sid)."<br/>";
                print "REPORT DATE: $sdate<br/>";
                print "AGE ON SERVICE: $age_service weeks<br/>";
                print "RECORDED BY: ".user::get_username($uid)."<br/>";
                
				print "<input type='hidden' name='service' value='$sid'/>";
                print "<input type='hidden' name='sts' value='$cstamp'/>";
                print "<input type='hidden' name='ccdev_id' value='$cdid'/>";
				print "<input type='hidden' name='ccdev_sdate' value='$ccdev_sdate'/>";

				echo "ACTUAL DATE OF SERVICE<br>";
				if($service_date=='00/00/0000'):
					list($tstamp) = explode(' ',$cstamp);
					list($tstamp_yr,$tstamp_mo,$tstamp_date) = explode('-',$tstamp);
					$tstamp_serv_date = $tstamp_mo.'/'.$tstamp_date.'/'.$tstamp_yr;
					echo "<font color='red' size='1'><b>(report timestamp is used. please record actual service date)</b></font><br>";
					
					echo "<input type='text' size='7' maxlength='10' value='$tstamp_serv_date' name='ccdev_serv_date'></input>";
				else:
					echo "<input type='text' size='7' maxlength='10' value='$service_date' name='ccdev_serv_date'></input>";					
				endif;

				echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_service_detail.ccdev_serv_date',document.form_service_detail.ccdev_serv_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";

				if ($_SESSION["priv_update"]) {
                    print "<br><br><input type='submit' name='submitdetail' value='Update Service' class='tinylight' style='border: 1px solid black'/> ";
                }
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdetail' value='Delete Service' class='tinylight' style='border: 1px solid black'/> ";
                }

                print "</span>";
                print "</form>";
                print "</td></tr></table>";
            }
        }
    }

    function age_on_vaccination() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $vaccine_timestamp = $arg_list[1];
        }
        $sql = "select (to_days('$vaccine_timestamp') - to_days(patient_dob))/7 from m_patient where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($age) = mysql_fetch_array($result);
                return $age;
            }
        }

    }

    // ------------------------- CCDEV LIBRARY METHODS -------------------------

    function _ccdev_services() {
    //
    // library submodule for ccdev services
    // calls form_service()
    //       display_service()
    //       process_service()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ccdev')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $c = new ccdev;
        if ($post_vars["submitservice"]) {
            $c->process_service($menu_id, $post_vars, $get_vars);
        }
        $c->display_service($menu_id, $post_vars, $get_vars);
        $c->form_service($menu_id, $post_vars, $get_vars);
    }

    function show_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_id, service_name from m_lib_ccdev_services order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='service' class='textbox'>";
                $ret_val .= "<option value=''>Select Service</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id'>$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function checkbox_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_id, service_name from m_lib_ccdev_services order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='services[]' value='$id'> $name<br>";
                }
                return $ret_val;
            }
        }
    }

    function get_service_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_name from m_lib_ccdev_services where service_id = '$service_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_service() {
    //
    // called by _ccdev_services()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["service_id"]) {
                $sql = "select service_id, service_name ".
                       "from m_lib_ccdev_services where service_id = '".$get_vars["service_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $service = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_service' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_CCDEV_SERVICE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SERVICE_ID."</span><br> ";
        if ($get_vars["service_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='service_id' value='".$type["service_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='service_id' value='".($service["service_id"]?$service["service_id"]:$post_vars["service_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SERVICE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='service_name' value='".($service["service_name"]?$service["service_name"]:$post_vars["service_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["service_id"]) {
            print "<input type='hidden' name='service_id' value='".$get_vars["service_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Service' class='textbox' name='submitservice' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Service' class='textbox' name='submitservice' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Service' class='textbox' name='submitservice' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_service() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitservice"]) {
            if ($post_vars["service_id"] && $post_vars["service_name"]) {
                switch($post_vars["submitservice"]) {
                case "Add Service":
                    $sql = "insert into m_lib_ccdev_services (service_id, service_name) ".
                           "values ('".strtoupper($post_vars["service_id"])."', '".$post_vars["service_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Service":
                    $sql = "update m_lib_ccdev_services set ".
                           "service_name = '".$post_vars["service_name"]."' ".
                           "where service_id = '".$post_vars["service_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Service":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ccdev_services where service_id = '".$post_vars["service_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
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

    function display_service() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_CCDEV_SERVICES_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select service_id, service_name from m_lib_ccdev_services order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&service_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

function determine_vacc_status(){
	if(func_num_args()>0):		
		$arg_list = func_get_args();
		$pxid = $arg_list[0];
	endif;
	
	$antigens = array('BCG','DPT1','DPT2','DPT3','HEPB1','HEPB2','HEPB3','MSL','OPV1','OPV2','OPV3');
	$antigen_stat = array('BCG'=>0,'DPT1'=>0,'DPT2'=>0,'DPT3'=>0,'HEPB1'=>0,'HEPB2'=>0,'HEPB3'=>0,'MSL'=>0,'OPV1'=>0,'OPV2'=>0,'OPV3'=>0);
	$cic = 0;
	
	$antigen_date = array();
	
	for($i=0;$i<count($antigens);$i++){
		$q_vacc = mysql_query("SELECT MIN(actual_vaccine_date) FROM m_consult_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigens[$i]' GROUP by patient_id") or die(mysql_error());

		if(mysql_num_rows($q_vacc)!=0):
			list($actual_vdate) = mysql_fetch_array($q_vacc);
			$antigen_stat[$antigens[$i]] = 1;
			$antigen_date[$antigens[$i]] = $actual_vdate;
		else:
			$antigen_date[$antigens[$i]] = 0;
		endif;
	}

	if(in_array('0',$antigen_stat)): //incomplete vaccination
		return 'Incomplete';
		//print_r($antigen_stat);
	else:
		for($j=0;$j<count($antigens);$j++){
			$ant_date = $antigen_date[$antigens[$j]];
								
			$q_antigen = mysql_query("SELECT round((TO_DAYS('$ant_date') - TO_DAYS(a.patient_dob))/7,2) week_span FROM m_patient a WHERE a.patient_id='$pxid'") or die("Cannot query: 269");
			list($wk_age) = mysql_fetch_array($q_antigen);

			if($wk_age>52):				
				$cic=1;
			endif;
		}
	endif;
	
	arsort($antigen_date);
//	print_r($antigen_date).'<br>';
	
	if($cic==1):
		return current($antigen_date)."\n".'(CIC)';
	else:
		return current($antigen_date)."\n".'(FIC)';
	endif;
}
// end of class
}
?>
