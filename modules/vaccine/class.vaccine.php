<?
class vaccine extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function vaccine() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "vaccine";
        $this->description = "CHITS Module - Vaccine";
        // 0.3 revamped m_lib_vaccine table
        // 0.4 added _consult_vaccine
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "healthcenter");

    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    // 1. refer to table term
    // 2. skip remarks and translationof since this term is manually entered
    //
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_INTERVAL", "english", "INTERVAL", "Y");
        module::set_lang("THEAD_DESCRIPTION", "english", "DESCRIPTION", "Y");
        module::set_lang("FTITLE_VACCINES", "english", "VACCINES", "Y");
        module::set_lang("FTITLE_VACCINE_FORM", "english", "VACCINE FORM", "Y");
        module::set_lang("LBL_VACCINE_ID", "english", "VACCINE ID", "Y");
        module::set_lang("LBL_VACCINE_NAME", "english", "VACCINE NAME", "Y");
        module::set_lang("LBL_VACCINE_AGE", "english", "VACCINE AT AGE IN WEEKS", "Y");
        module::set_lang("LBL_VACCINE_DESCRIPTION", "english", "VACCINE DESCRIPTION", "Y");
        module::set_lang("INSTR_VACCINE_AGE", "english", "Enter age in weeks patient is supposed to receive this vaccines.", "Y");
        module::set_lang("INSTR_VACCINE_REQUIRED_FLAG", "english", "Check if required in immunization program.", "Y");
        module::set_lang("LBL_VACCINE_REQUIRED", "english", "VACCINE REQUIRED BY PROGRAM", "Y");
        module::set_lang("LBL_HIGHLIGHTED_VACCINES", "english", "HIGHLIGHTED VACCINES ARE REQUIRED BY IMMUNIZATION PROGRAM", "Y");
        module::set_lang("THEAD_VACCINE_AGE", "english", "AGE IN WEEKS", "Y");
        module::set_lang("FTITLE_VACCINE_RECORD", "english", "GENERAL VACCINE RECORD", "Y");
        module::set_lang("FTITLE_GENERAL_VACCINES", "english", "GENERAL VACCINE RECORD FORM", "Y");
        module::set_lang("INSTR_VACCINE_RECORD", "english", "IMPORTANT: YOU CAN ONLY UPDATE RECORDS CREATED THROUGH THIS MODULE.", "Y");

    }

    function init_help() {
    }

    function init_menu() {
    //
    // menu entries
    // use multiple inserts (watch out for ;)
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::set_menu($this->module, "Vaccines", "LIBRARIES", "_vaccine");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
    //
    // create module tables
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }

        module::execsql("CREATE TABLE `m_vaccine_record` (".
               "`patient_id` int(11) NOT NULL default '0',".
               "`healthcenter_id` int(11) NOT NULL default '0',".
               "`vaccine_id` varchar(5) NOT NULL default '',".
               "`vaccine_date` date NOT NULL default '0000-00-00',".
               "`user_id` int(11) NOT NULL default '0',".
               "`vaccine_adverse` char(1) NOT NULL default '',".
               "`remarks` text NOT NULL,".
               "PRIMARY KEY  (`patient_id`,`healthcenter_id`,`vaccine_id`)".
               ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_lib_vaccine` (".
            "`vaccine_id` varchar(25) NOT NULL default '',".
            "`vaccine_name` varchar(50) NOT NULL default '',".
            "`vaccine_interval` int(11) NOT NULL default '',".
            "`vaccine_required` char(1) NOT NULL default 'N',".
            "`vaccine_desc` text NOT NULL,".
            "PRIMARY KEY  (`vaccine_id`),".
            "UNIQUE KEY `ukey_vaccinename` (`vaccine_name`)".
            ") TYPE=InnoDB;");

        module::execsql("INSERT INTO m_lib_vaccine VALUES ('HEPB1','Hepatitis B1 Vaccine','6','N','Hepatitis B Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('HEPB2','Hepatitis B2 Vaccine','10','N','Hepatitis B Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('HEPB3','Hepatitis B3 Vaccine','14','N','Hepatitis B Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('BCG','BCG Vaccine','1','Y','BCG Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('DPT1','DPT1 Vaccine','6','Y','DPT Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('DPT2','DPT2 Vaccine','10','Y','DPT Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('DPT3','DPT3 Vaccine','14','Y','DPT Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('OPV1','OPV1 Vaccine','6','Y','Oral Polio Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('OPV2','OPV2 Vaccine','10','Y','Oral Polio Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('OPV3','OPV3 Vaccine','14','Y','Oral Polio Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('MSL','Measles Vaccine','36','Y','Measles Vaccine');");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('TT1', 'TT1 Vaccine', '0', 'Y', 'Tetanus Toxoid 1')");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('TT2', 'TT2 Vaccine', '0', 'Y', 'Tetanus Toxoid 2')");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('TT3', 'TT3 Vaccine', '0', 'Y', 'Tetanus Toxoid 3')");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('TT4', 'TT4 Vaccine', '0', 'Y', 'Tetanus Toxoid 4')");
        module::execsql("INSERT INTO m_lib_vaccine VALUES ('TT5', 'TT5 Vaccine', '0', 'Y', 'Tetanus Toxoid 5')");

        // this table records all vaccinations whether
        // pediatric or maternal
        module::execsql("CREATE TABLE `m_consult_vaccine` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`vaccine_timestamp` timestamp(14) NOT NULL,".
            "`actual_vaccine_date` date NOT NULL default '0000-00-00',".
            "`source_module` varchar(25) NOT NULL default 'vaccine',".
            "`adr_flag` char(1) NOT NULL default 'N',".
            "`vaccine_id` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`patient_id`,`vaccine_id`, `consult_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_vaccine` (`vaccine_id`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_consult` (`consult_id`),".
            "CONSTRAINT `m_consult_vaccine_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_vaccine_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

    }

    function drop_tables() {
    //
    // called from delete_module()
    //
        module::execsql("SET foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_consult_vaccine`;");
        module::execsql("DROP TABLE m_vaccine_record;");
        module::execsql("DROP TABLE m_lib_vaccine;");
        module::execsql("SET foreign_key_checks=1;");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_vaccine() {
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('vaccine')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $v = new vaccine;
        if ($post_vars["submitvaccine"]) {
            $v->process_consult_vaccine($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        }
        $v->form_consult_vaccine($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
    }

    function process_consult_vaccine() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch($post_vars["submitvaccine"]) {
        case "Update Vaccination":
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            if ($post_vars["vaccine"]) {
                foreach($post_vars["vaccine"] as $key=>$value) {
                    $sql_vaccine = "insert into m_consult_vaccine (consult_id, patient_id, vaccine_id, user_id, vaccine_timestamp, actual_vaccine_date, source_module) ".
                                   "values ('".$get_vars["consult_id"]."', '$patient_id', '$value', '".$_SESSION["userid"]."', sysdate(), sysdate(), 'vaccine')";
                    $result_vaccine = mysql_query($sql_vaccine);
					
					$vacc_id = mysql_insert_id();

					$check_gender = mysql_query("SELECT patient_gender FROM m_patient WHERE patient_id='$patient_id'") or die("Cannot query: 200");
					list($px_gender) = mysql_fetch_array($check_gender);
					
					$get_vacc_details = mysql_query("SELECT vaccine_timestamp,actual_vaccine_date,adr_flag FROM m_consult_vaccine WHERE consult_id='$get_vars[consult_id]' AND patient_id='$patient_id' AND vaccine_id='$value'") or die("Cannot query: 206");
						
					list($timestamp,$actual_vdate,$adr) = mysql_fetch_array($get_vacc_details);

					//insert into mc_table
					if($px_gender=='F'):						
						$check_vacc = mysql_query("SELECT patient_id FROM m_consult_mc_vaccine WHERE consult_id='$get_vars[consult_id]' AND patient_id='$patient_id' AND vaccine_timestamp='$timestamp' AND vaccine_id='$value'") or die("Cannot query: 204");

						if(mysql_num_rows($check_vacc)==0):
							$insert_vacc = mysql_query("INSERT INTO m_consult_mc_vaccine SET mc_id='0',consult_id='$get_vars[consult_id]',patient_id='$patient_id',user_id='$_SESSION[userid]',vaccine_timestamp='$timestamp',actual_vaccine_date='$actual_vdate',adr_flag='$adr',vaccine_id='$value'") or die("Cannot query: 213");							
						endif;

					endif;

					//insert into ccdev_table
					/*$arr_vacc = array('BCG','DPT1','DPT2','DPT3','HEPB1','HEPB2','HEPB3','MSL','OPV1','OPV2','OPV3');

					if(in_array($value,$arr_vacc)):
						$q_vacc = mysql_query("SELECT patient_id FROM m_consult_ccdev_vaccine WHERE consult_id='$get_vars[consult_id]' AND patient_id='$patient_id' AND vaccine_timestamp='$timestamp' AND vaccine_id='$value'") or die("Cannot query: 223");

						if(mysql_num_rows($q_vacc)==0):
							$q_age = "select round((to_days('$actual_vdate')-to_days(patient_dob))/7,1) age from m_patient where patient_id = '$patient_id'";
							list($age_weeks) = mysql_fetch_array($q_age);

							$insert_ccdev_vacc = mysql_query("INSERT INTO m_consult_ccdev_vaccine SET ccdev_id='0',consult_id='$get_vars[consult_id]',patient_id='$patient_id',user_id='$_SESSION[userid]',vaccine_timestamp='$timestamp',actual_vaccine_date='$actual_vdate',adr_flag='$adr',vaccine_id='$value',age_on_vaccine='$age_weeks'") or die("Cannot query: 226");
						endif;
					else:
											
					endif;*/


                }
            }
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=vaccine");
            break;
        }
    }

    function form_consult_vaccine() {
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
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=vaccine' name='form_consult_vaccine' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_GENERAL_VACCINES."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        // VACCINE DATA
        print "<table bgcolor='#CCCCFF' width='300' cellpadding='3'><tr><td>";
        print "<span class='boxtitle'>".LBL_VACCINATIONS."</span><br> ";
        print vaccine::checkbox_vaccines($patient_id);
        print "</td></tr>";
        print "</table>";

        print "</td></tr>";
        print "<tr><td><br/>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Update Vaccination' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function _details_vaccine() {
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('vaccine')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitdetail"]) {
            vaccine::process_detail($menu_id, $post_vars, $get_vars);
        }
        print "<b>".FTITLE_VACCINE_RECORD."</b><br/><br/>";
        print INSTR_VACCINE_RECORD."<br/>";
        vaccine::display_vaccine_record($menu_id, $post_vars, $get_vars);
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
        case "Delete Record";
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_vaccine ".
                       "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                       "consult_id = '".$post_vars["vaccine_consult_id"]."' and ".
                       "source_module = 'vaccine'";
                if ($result = mysql_query($sql)) {
					$delete_mc_vacc = mysql_query("DELETE FROM m_consult_mc_vaccine WHERE vaccine_id='$post_vars[vaccine]' AND consult_id='$post_vars[vaccine_consult_id]'") or die("cannot query: 298");

                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=vaccine");
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=vaccine");
                }
            }
            break;
        case "Update Record";
            $adr = ($post_vars["adr_flag"]?"Y":"N");
            list($month,$day,$year) = explode("/", $post_vars["actual_vaccine_date"]);
            $actual_vaccine_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $patient_dob = patient::get_dob($patient_id);
            $sql = "update m_consult_vaccine set ".
                   "adr_flag = '$adr', ".
                   "actual_vaccine_date = '$actual_vaccine_date' ".
                   "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                   "consult_id = '".$post_vars["vaccine_consult_id"]."' and ".
                   "source_module = 'vaccine'";
            if ($result = mysql_query($sql)) {
					$update_mc = mysql_query("UPDATE m_consult_mc_vaccine SET actual_vaccine_date='$actual_vaccine_date',adr_flag='$adr' WHERE consult_id='$post_vars[vaccine_consult_id]' AND patient_id='$patient_id' AND vaccine_id='$post_vars[vaccine]'") or die("Cannot query: 319");

                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=vaccine");
            }
        }
    }

    function display_vaccine_record() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select vaccine_id, vaccine_timestamp, date_format(vaccine_timestamp,'%a %d %b %Y'),date_format(actual_vaccine_date,'%a %d %b %Y') ".
               "from m_consult_vaccine ".
               "where patient_id = '$patient_id' order by vaccine_id, vaccine_timestamp desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($vaccine, $vstamp, $vdate, $actual_vdate) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=vaccine&vaccine=$vaccine&ts=$vstamp&actual=$actual_vdate#detail'>$vaccine</a> $actual_vdate<br/>";
                    if ($get_vars["vaccine"]==$vaccine && $get_vars["ts"]==$vstamp && $get_vars["actual"]==$actual_vdate) {
                        vaccine::display_vaccine_record_details($menu_id, $post_vars, $get_vars);
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
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select consult_id, user_id, patient_id, vaccine_timestamp, date_format(vaccine_timestamp, '%a %d %b %Y, %h:%i%p'), ".
               "vaccine_id, adr_flag, actual_vaccine_date, source_module ".
               "from m_consult_vaccine where ".
               "vaccine_id = '".$get_vars["vaccine"]."' and patient_id = '$patient_id' and ".
               "vaccine_timestamp = '".$get_vars["ts"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($cid, $uid, $pid, $vstamp, $vdate, $vid, $adr, $actual_date, $source) = mysql_fetch_array($result);
                print "<a name='detail'>";
                print "<table width='250' cellpadding='3' style='border:1px dashed black'><tr><td>";
                print "<form name='form_vaccine_detail' method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=vaccine&vaccine=$vid&ts=$vstamp&actual=$actual_vdate'>";
                print "<span class='tinylight'>";
                print "PATIENT: ".patient::get_name($pid)."<br/>";
                print "DATA SOURCE: ".strtoupper($source)."<br/>";
                print "VACCINE: ".vaccine::get_vaccine_name($vid)."<br/>";
                print "REPORT DATE: $vdate<br/>";
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
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdetail' value='Delete Record' class='tinylight' style='border: 1px solid black'/> ";
                }
                if ($_SESSION["priv_update"]) {
                    print "<input type='submit' name='submitdetail' value='Update Record' class='tinylight' style='border: 1px solid black'/> ";
                }
                print "</span>";
                print "</form>";
                print "</td></tr></table>";
            }
        }
    }

    // -----------------------VACCINE LIBRARY METHODS ----------------------

    function _vaccine() {
    //
    // main method for vaccine
    // called from database menu entry
    // calls form_vaccine(), process_vaccine(), display_vaccine()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('vaccine')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitvaccine"]) {
            $this->process_vaccine($menu_id, $post_vars, $get_vars);
        }
        $this->display_vaccine($menu_id);
        $this->form_vaccine($menu_id, $post_vars, $get_vars, $isadmin);
    }

    function checkbox_vaccines() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

        $sql = "select vaccine_id, vaccine_interval, vaccine_name ".
               "from m_lib_vaccine order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $interval, $name) = mysql_fetch_array($result)) {
                    $vaccine_status = vaccine::check_vaccine_status($id, $patient_id);
                    $ret_val .= "<input type='checkbox' name='vaccine[]' value='$id'> $name $vaccine_status<br>";
                }
                return $ret_val;
            }
        }
    }

    function check_vaccine_status() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
            $patient_id = $arg_list[1];
        }
        $sql = "select count(vaccine_id) from m_consult_vaccine ".
               "where patient_id = '$patient_id' and vaccine_id = '$vaccine_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($shots) = mysql_fetch_array($result)) {
                    if ($shots>0) {
                        return "<font color='blue'>GIVEN ($shots)</font>";
                    } else {
                        return "<font color='red'><b>NOT GIVEN</b></font>";
                    }
                }
            }
        }
    }

    function display_vaccine() {
    //
    // called from _vaccine()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='500' cellspacing='0'>";
        print "<tr valign='top'><td colspan='4'>";
        print "<span class='library'>".FTITLE_VACCINES."</span><br><br>";
        print "<span class='tinylight'>".LBL_HIGHLIGHTED_VACCINES.".</span><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_VACCINE_AGE."</b></td><td><b>".THEAD_DESCRIPTION."</b></td></tr>";
        $sql = "select vaccine_id, vaccine_name, vaccine_interval, vaccine_required, vaccine_desc from m_lib_vaccine order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $interval, $reqd, $desc) = mysql_fetch_array($result)) {
                    $bgcolor = ($reqd=="Y"?"#FFFF33":"");
                    print "<tr bgcolor='$bgcolor' valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&vaccine_id=$id'>$name</a></td><td>$interval</td><td>$desc</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function form_vaccine() {
    //
    // called from _vaccine()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $isadmin = $arg_list[3];
            if ($get_vars["vaccine_id"]) {
                $sql = "select vaccine_id, vaccine_name, vaccine_interval, vaccine_required, vaccine_desc ".
                       "from m_lib_vaccine where vaccine_id = '".$get_vars["vaccine_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $vaccine = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_vaccine' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_VACCINE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>NOTE: Fill up the following form with the correct values.</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_ID."</span><br> ";
        print "<input type='text' class='textbox' size='5' maxlength='10' name='vaccine_id' value='".($vaccine["vaccine_id"]?$vaccine["vaccine_id"]:$post_vars["vaccine_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='vaccine_name' value='".($vaccine["vaccine_name"]?$vaccine["vaccine_name"]:$post_vars["vaccine_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_AGE."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='10' name='vaccine_interval' value='".($vaccine["vaccine_interval"]?$vaccine["vaccine_interval"]:$post_vars["vaccine_interval"])."' style='border: 1px solid #000000'><br>";
        print "<small>".INSTR_VACCINE_AGE."</small><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_REQUIRED."?</span><br> ";
        print "<input type='checkbox' name='vaccine_required_flag' value='1' ".($vaccine["vaccine_required"]=="Y"?"checked":"")."'> ".INSTR_VACCINE_REQUIRED_FLAG."<br>";
        print "</td></tr>";
        print "<tr><td><br>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_DESCRIPTION."</span><br> ";
        print "<textarea rows='5' cols='35' name='vaccine_desc' style='border: 1px solid black'>".stripslashes($vaccine["vaccine_desc"]?$vaccine["vaccine_desc"]:$post_vars["vaccine_desc"])."</textarea>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        if ($get_vars["vaccine_id"]) {
            print "<input type='hidden' name='vaccine_id' value='".$get_vars["vaccine_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Vaccine' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Vaccine' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Vaccine' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_vaccine() {
    //
    // called from _vaccine()
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        if ($post_vars["submitvaccine"]) {
            if ($post_vars["vaccine_id"] && $post_vars["vaccine_name"]) {
                $vaccine_required = ($post_vars["vaccine_required_flag"]?"Y":"N");
                switch($post_vars["submitvaccine"]) {
                case "Add Vaccine":
                    $sql = "insert into m_lib_vaccine (vaccine_id, vaccine_name, vaccine_interval, vaccine_required, vaccine_desc) ".
                           "values ('".strtoupper($post_vars["vaccine_id"])."', '".ucfirst($post_vars["vaccine_name"])."', '".$post_vars["vaccine_interval"]."', '$vaccine_required', '".$post_vars["vaccine_desc"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
                    }
                    break;
                case "Update Vaccine":
                    $sql = "update m_lib_vaccine set ".
                           "vaccine_name = '".ucfirst($post_vars["vaccine_name"])."', ".
                           "vaccine_interval = '".$post_vars["vaccine_interval"]."', ".
                           "vaccine_required = '$vaccine_required', ".
                           "vaccine_desc = '".$post_vars["vaccine_desc"]."' ".
                           "where vaccine_id = '".$post_vars["vaccine_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
                    }
                    break;
                case "Delete Vaccine":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_vaccine ".
                               "where vaccine_id = '".$post_vars["vaccine_id"]."'";
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

    function get_vaccine_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
        }
        $sql = "select vaccine_name from m_lib_vaccine where vaccine_id = '$vaccine_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

}
?>
