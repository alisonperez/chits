<?
class mclib extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function mclib() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "mclib";
        $this->description = "CHITS Library - MC Library";
        // 0.4: debugged

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "vaccine");
        module::set_dep($this->module, "lab");
        module::set_dep($this->module, "mc");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_MC_DATA_FORM", "english", "MATERNAL CARE DATA", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_HOSPITAL_FLAG", "english", "HOSPITAL FLAG", "Y");
        module::set_lang("THEAD_MONITOR_FLAG", "english", "MONITOR FLAG", "Y");
        module::set_lang("LBL_LMP_DATE", "english", "LAST MENSTRUAL PERIOD", "Y");
        module::set_lang("LBL_OBSTETRIC_SCORE", "english", "OBSTETRIC SCORE", "Y");
        module::set_lang("LBL_RISK_FACTORS", "english", "RISK FACTORS", "Y");
        module::set_lang("LBL_PATIENT_HEIGHT", "english", "PATIENT HEIGHT (CM)", "Y");
        module::set_lang("LBL_PATIENT_BLOODTYPE", "english", "BLOOD TYPE", "Y");
        module::set_lang("FTITLE_MC_SERVICES", "english", "MATERNAL CARE SERVICES", "Y");
        module::set_lang("FTITLE_MC_SERVICES_LIST", "english", "MATERNAL CARE SERVICES LIST", "Y");
        module::set_lang("FTITLE_MC_SERVICE_FORM", "english", "MATERNAL CARE SERVICES FORM", "Y");
        module::set_lang("FTITLE_MATERNAL_RISKFACTORS", "english", "MATERNAL RISK FACTORS", "Y");
        module::set_lang("THEAD_HOSPITAL_FLAG", "english", "HOSPITAL FLAG", "Y");
        module::set_lang("THEAD_MONITOR_FLAG", "english", "MONITOR FLAG", "Y");
        module::set_lang("FTITLE_MATERNAL_RISK_FACTORS_FORM", "english", "MATERNAL RISK FACTORS FORM", "Y");
        module::set_lang("LBL_HOSPITAL_FLAG", "english", "REFER TO HOSPITAL", "Y");
        module::set_lang("INSTR_HOSPITAL_FLAG", "english", "Check if for hospital referral when risk factor positive", "Y");
        module::set_lang("LBL_MONITOR_FLAG", "english", "MONITOR DURING PREGNANCY", "Y");
        module::set_lang("INSTR_MONITOR_FLAG", "english", "Check if for monitoring during pregnancy", "Y");
        module::set_lang("FTITLE_PREGNANCY_OUTCOMES", "english", "PREGNANCY OUTCOMES", "Y");
        module::set_lang("FTITLE_PREGNANCY_OUTCOME_FORM", "english", "PREGNANCY OUTCOME FORM", "Y");
        module::set_lang("LBL_OUTCOME_ID", "english", "OUTCOME ID", "Y");
        module::set_lang("LBL_OUTCOME_NAME", "english", "OUTCOME NAME", "Y");
        module::set_lang("LBL_RISK_NAME", "english", "RISK FACTOR NAME", "Y");
        module::set_lang("FTITLE_REGISTRY_RECORDS", "english", "MATERNAL REGISTRY RECORDS", "Y");
        module::set_lang("FTITLE_MC_PRENATAL_FORM", "english", "PRENATAL VISIT FORM", "Y");
        module::set_lang("LBL_BLOOD_PRESSURE", "english", "BLOOD PRESSURE", "Y");
        module::set_lang("LBL_PATIENT_WEIGHT", "english", "WEIGHT", "Y");
        module::set_lang("LBL_OBSTETRIC_EXAM", "english", "OBSTETRIC EXAM", "Y");
        module::set_lang("LBL_PRESENTATION", "english", "FETAL PRESENTATION", "Y");
        module::set_lang("FTITLE_MC_POSTPARTUM_FORM", "english", "POSTPARTUM VISIT FORM", "Y");
        module::set_lang("LBL_DELIVERY_DATE", "english", "DELIVERY DATE", "Y");
        module::set_lang("LBL_CHILD_PATIENT_ID", "english", "PATIENT ID OF CHILD", "Y");
        module::set_lang("INSTR_CHILD_PATIENT_ID", "english", "If patient\'s child has been registered, type in the patient ID here.", "Y");
        module::set_lang("LBL_PREGNANCY_OUTCOME", "english", "PREGNANCY OUTCOME", "Y");
        module::set_lang("LBL_BABY_WEIGHT", "english", "BABY\'S WEIGHT", "Y");
        module::set_lang("LBL_BREASTFEEDING_FLAG", "english", "WAS BABY BREASTFED ASAP", "Y");
        module::set_lang("INSTR_BREASTFEEDING_ASAP_FLAG", "english", "Check if the mother initiated breastfeeding ASAP", "Y");
        module::set_lang("LBL_HEALTHY_BABY_FLAG", "english", "HEALTHY BABY", "Y");
        module::set_lang("INSTR_HEALTHY_BABY_FLAG", "english", "Check if the baby is healthy (no anomalies, congenital defects, etc.)", "Y");
        module::set_lang("FTITLE_MC_LAB_FORM", "english", "LAB REQUEST FORM", "Y");
        module::set_lang("FTITLE_MC_VACCINE_LIST", "english", "MATERNAL VACCINES LIST", "Y");
        module::set_lang("FTITLE_MC_VACCINE_FORM", "english", "MATERNAL VACCINE FORM", "Y");
        module::set_lang("LBL_SAME_RISK_FACTORS_VISIT1", "english", "Same as risk factors in VISIT1", "Y");
        module::set_lang("FTITLE_PRENATAL_RECORDS", "english", "PRENATAL RECORDS", "Y");
        module::set_lang("FTITLE_MC_ATTENDANT_LIST", "english", "BIRTH ATTENDANT LIST", "Y");
        module::set_lang("LBL_ATTENDANT_NAME", "english", "ATTENDANT NAME", "Y");
        module::set_lang("LBL_ATTENDANT_ID", "english", "ATTENDANT ID", "Y");
        module::set_lang("FTITLE_MC_ATTENDANT_FORM", "english", "BIRTH ATTENDANT FORM", "Y");
        module::set_lang("LBL_BIRTH_ATTENDANT", "english", "BIRTH ATTENDANT", "Y");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries
        module::set_menu($this->module, "MC Risk Factors", "LIBRARIES", "_mc_risk_factors");
        module::set_menu($this->module, "MC Services", "LIBRARIES", "_mc_services");
        module::set_menu($this->module, "MC Vaccines", "LIBRARIES", "_mc_vaccines");
        module::set_menu($this->module, "MC Preg Outcomes", "LIBRARIES", "_mc_outcomes");
        module::set_menu($this->module, "MC Birth Attend", "LIBRARIES", "_mc_attendant");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // library table for MC attendant
        module::execsql("CREATE TABLE `m_lib_mc_birth_attendant` (".
            "`attendant_id` varchar(10) NOT NULL default '',".
            "`attendant_name` varchar(40) NOT NULL default '',".
            "PRIMARY KEY  (`attendant_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("INSERT INTO `m_lib_mc_birth_attendant` (`attendant_id`, `attendant_name`) VALUES ('MD', 'Physician')");
        module::execsql("INSERT INTO `m_lib_mc_birth_attendant` (`attendant_id`, `attendant_name`) VALUES ('RN', 'Nurse')");
        module::execsql("INSERT INTO `m_lib_mc_birth_attendant` (`attendant_id`, `attendant_name`) VALUES ('MW', 'Midwife')");
        module::execsql("INSERT INTO `m_lib_mc_birth_attendant` (`attendant_id`, `attendant_name`) VALUES ('UTH', 'Untrained Hilot')");
        module::execsql("INSERT INTO `m_lib_mc_birth_attendant` (`attendant_id`, `attendant_name`) VALUES ('TRH', 'Trained Hilot')");
        module::execsql("INSERT INTO `m_lib_mc_birth_attendant` (`attendant_id`, `attendant_name`) VALUES ('OTH', 'Other')");

        // library table for MC vaccines
        module::execsql("CREATE TABLE `m_lib_mc_vaccines` (".
            "`vaccine_id` varchar(25) NOT NULL default '',".
            "`vaccine_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`vaccine_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("INSERT INTO `m_lib_mc_vaccines` (`vaccine_id`, `vaccine_name`) VALUES ('TT1', 'Tetanus Toxoid 1')");
        module::execsql("INSERT INTO `m_lib_mc_vaccines` (`vaccine_id`, `vaccine_name`) VALUES ('TT2', 'Tetanus Toxoid 2')");
        module::execsql("INSERT INTO `m_lib_mc_vaccines` (`vaccine_id`, `vaccine_name`) VALUES ('TT3', 'Tetanus Toxoid 3')");
        module::execsql("INSERT INTO `m_lib_mc_vaccines` (`vaccine_id`, `vaccine_name`) VALUES ('TT4', 'Tetanus Toxoid 4')");
        module::execsql("INSERT INTO `m_lib_mc_vaccines` (`vaccine_id`, `vaccine_name`) VALUES ('TT5', 'Tetanus Toxoid 5')");

        // library table for MC services
        module::execsql("CREATE TABLE `m_lib_mc_services` (".
            "`service_id` varchar(10) NOT NULL default '',".
            "`service_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`service_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("insert into m_lib_mc_services (service_id, service_name) values ('DENT', 'Dental Checkup')");
        module::execsql("insert into m_lib_mc_services (service_id, service_name) values ('IRON', 'Ferrous Sulfate')");

        module::execsql("CREATE TABLE `m_lib_mc_risk_factors` (".
            "`risk_id` int(11) NOT NULL auto_increment,".
            "`risk_name` varchar(100) NOT NULL default '',".
            "`hospital_flag` char(1) NOT NULL default 'N',".
            "`monitor_flag` char(1) NOT NULL default 'N',".
            "PRIMARY KEY  (`risk_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (1,'N','','Age younger than 15 years old or older than 35 years old.');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (2,'N','','Height lower than 145cm.');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (3,'Y','','Poor OB history (3 consecutive miscarriages, stillbirth, postpartum hemorrhage)');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (4,'N','','Leg and pelvic deformities (polio paralysis)');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (5,'N','','No prenatal or irregular prenatal in previous pregnancy');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (6,'N','','First pregnancy');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (7,'N','','Pregnancy more than 5');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (8,'N','','Pregnancy interval less than 24 months from the last delivery');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (9,'N','','Pregnancy longer than 294 days or 42 weeks');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (10,'N','','Pre-pregnancy weight less than 80% of standard weight');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (11,'N','Y','Anemia less than 8gm Hb');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (12,'N','Y','Weight less than 4% of pre-pregnancy weight per trimester');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (13,'N','Y','Weight gain more than 6% of pre-pregnancy weight per trimester');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (14,'N','','Abnormal presentation');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (15,'N','','Multiple fetus');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (16,'N','Y','Blood pressure greater than 140/90');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (17,'N','Y','Dizziness or blurring of vision');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (18,'N','Y','Convulsions');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (19,'N','','Positive urine albumin');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (20,'N','Y','Vaginal infections');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (21,'N','Y','Vaginal bleeding');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (22,'N','Y','Pitting edema');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (23,'Y','','Heart or kidney disease');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (24,'N','','Tuberculosis');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (25,'N','','Malaria');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (26,'Y','','Diabetes');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (27,'N','','Rubella');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (28,'Y','','Thyroid problems');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (30,'N','','Mental disorder');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (31,'N','','Unwed mother');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (32,'N','','Illiterate mother');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (33,'N','','Perform heavy manual labor');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (34,'N','','Unwanted or unplanned pregnancy');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (35,'N','','Other socio-economic factors');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (36,'Y','','Previous cesarean section');");
        module::execsql("INSERT INTO m_lib_mc_risk_factors (risk_id, hospital_flag, monitor_flag, risk_name) VALUES (37,'Y','','Bronchial asthma');");

        module::execsql("CREATE TABLE `m_lib_mc_outcome` (".
            "`outcome_id` varchar(10) NOT NULL default '',".
            "`outcome_name` varchar(40) NOT NULL default '',".
            "PRIMARY KEY  (`outcome_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("insert into m_lib_mc_outcome (outcome_id, outcome_name) values ('NSDF', 'Live baby girl NSD')");
        module::execsql("insert into m_lib_mc_outcome (outcome_id, outcome_name) values ('NSDM', 'Live baby boy NSD')");
        module::execsql("insert into m_lib_mc_outcome (outcome_id, outcome_name) values ('LSCSF', 'Live baby girl LSCS')");
        module::execsql("insert into m_lib_mc_outcome (outcome_id, outcome_name) values ('LSCSM', 'Live baby boy LSCS')");

    }

    function drop_tables() {

        module::execsql("SET foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_lib_mc_risk_factors`;");
        module::execsql("DROP TABLE `m_lib_mc_outcome`;");
        module::execsql("DROP TABLE `m_lib_mc_vaccines`;");
        module::execsql("DROP TABLE `m_lib_mc_services`;");
        module::execsql("DROP TABLE `m_lib_mc_birth_attendant`;");
        module::execsql("SET foreign_key_checks=1;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _mc_attendant() {
    //
    // library submodule for mc attendant
    // calls form_attendant()
    //       display_attendant()
    //       process_attendant()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('mc')) {
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
        $m = new mc;
        if ($post_vars["submitattendant"]) {
            $m->process_attendant($menu_id, $post_vars, $get_vars);
        }
        $m->display_attendant($menu_id, $post_vars, $get_vars);
        $m->form_attendant($menu_id, $post_vars, $get_vars);
    }

    function show_attendant() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $attendant_id = $arg_list[0];
        }
        $sql = "select attendant_id, attendant_name from m_lib_mc_birth_attendant order by attendant_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='attendant' class='textbox'>";
                $ret_val .= "<option value=''>Select Attendant</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($attendant_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function checkbox_attendant() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $attendant_id = $arg_list[0];
        }
        $sql = "select attendant_id, attendant_name from m_lib_mc_birth_attendant order by attendant_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='attendant[]' value='$id'> $name<br>";
                }
                return $ret_val;
            }
        }
    }

    function get_attendant_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $attendant_id = $arg_list[0];
        }
        $sql = "select attendant_name from m_lib_mc_birth_attendant where attendant_id = '$attendant_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_attendant() {
    //
    // called by _mc_attendant()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["attendant_id"]) {
                $sql = "select attendant_id, attendant_name ".
                       "from m_lib_mc_birth_attendant where attendant_id = '".$get_vars["attendant_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $attendant = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_attendant' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_MC_ATTENDANT_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ATTENDANT_ID."</span><br> ";
        if ($get_vars["attendant_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='attendant_id' value='".$attendant["attendant_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='attendant_id' value='".($attendant["attendant_id"]?$attendant["attendant_id"]:$post_vars["attendant_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ATTENDANT_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='attendant_name' value='".($attendant["attendant_name"]?$attendant["attendant_name"]:$post_vars["attendant_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["attendant_id"]) {
            print "<input type='hidden' name='attendant_id' value='".$get_vars["attendant_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Attendant' class='textbox' name='submitattendant' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Attendant' class='textbox' name='submitattendant' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Attendant' class='textbox' name='submitattendant' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_attendant() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitattendant"]) {
            if ($post_vars["attendant_id"] && $post_vars["attendant_name"]) {
                switch($post_vars["submitattendant"]) {
                case "Add Attendant":
                    $sql = "insert into m_lib_mc_birth_attendant (attendant_id, attendant_name) ".
                           "values ('".strtoupper($post_vars["attendant_id"])."', '".$post_vars["attendant_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Attendant":
                    $sql = "update m_lib_mc_birth_attendant set ".
                           "attendant_name = '".$post_vars["attendant_name"]."' ".
                           "where attendant_id = '".$post_vars["attendant_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Attendant":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_mc_birth_attendant where attendant_id = '".$post_vars["attendant_id"]."'";
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

    function display_attendant() {
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
        print "<span class='library'>".FTITLE_MC_ATTENDANT_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select attendant_id, attendant_name from m_lib_mc_birth_attendant order by attendant_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&attendant_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _mc_vaccines() {
    //
    // library submodule for mc vaccines
    // calls form_vaccine()
    //       display_vaccine()
    //       process_vaccine()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('mc')) {
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
        $m = new mc;
        if ($post_vars["submitvaccine"]) {
            $m->process_vaccine($menu_id, $post_vars, $get_vars);
        }
        $m->display_vaccine($menu_id, $post_vars, $get_vars);
        $m->form_vaccine($menu_id, $post_vars, $get_vars);
    }

    function show_vaccines() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
        }
        $sql = "select vaccine_id, vaccine_name from m_lib_mc_vaccines order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='vaccine' class='textbox'>";
                $ret_val .= "<option value=''>Select Vaccine</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($vaccine_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function checkbox_vaccines() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
        }
        $sql = "select vaccine_id, vaccine_name from m_lib_mc_vaccines order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='vaccines[]' value='$id'> $name<br>";
                }
                return $ret_val;
            }
        }
    }

    function get_vaccine_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
        }
        $sql = "select vaccine_name from m_lib_mc_vaccines where vaccine_id = '$vaccine_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_vaccine() {
    //
    // called by _mc_vaccines()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["vaccine_id"]) {
                $sql = "select vaccine_id, vaccine_name ".
                       "from m_lib_mc_vaccines where vaccine_id = '".$get_vars["vaccine_id"]."'";
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
        print "<span class='library'>".FTITLE_MC_VACCINE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_ID."</span><br> ";
        if ($get_vars["vaccine_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='vaccine_id' value='".$type["vaccine_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='vaccine_id' value='".($vaccine["vaccine_id"]?$vaccine["vaccine_id"]:$post_vars["vaccine_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='vaccine_name' value='".($vaccine["vaccine_name"]?$vaccine["vaccine_name"]:$post_vars["vaccine_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
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
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitvaccine"]) {
            if ($post_vars["vaccine_id"] && $post_vars["vaccine_name"]) {
                switch($post_vars["submitvaccine"]) {
                case "Add Vaccine":
                    $sql = "insert into m_lib_mc_vaccines (vaccine_id, vaccine_name) ".
                           "values ('".strtoupper($post_vars["vaccine_id"])."', '".$post_vars["vaccine_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Vaccine":
                    $sql = "update m_lib_mc_vaccines set ".
                           "vaccine_name = '".$post_vars["vaccine_name"]."' ".
                           "where vaccine_id = '".$post_vars["vaccine_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Vaccine":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_mc_vaccines where vaccine_id = '".$post_vars["vaccine_id"]."'";
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

    function display_vaccine() {
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
        print "<span class='library'>".FTITLE_MC_VACCINE_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select vaccine_id, vaccine_name from m_lib_mc_vaccines order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&vaccine_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _mc_services() {
    //
    // library submodule for mc services
    // calls form_service()
    //       display_service()
    //       process_service()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('mc')) {
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
        $m = new mc;
        if ($post_vars["submitservice"]) {
            $m->process_service($menu_id, $post_vars, $get_vars);
        }
        $m->display_service($menu_id, $post_vars, $get_vars);
        $m->form_service($menu_id, $post_vars, $get_vars);
    }

    function show_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_id, service_name from m_lib_mc_services order by service_name";
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
        $sql = "select service_id, service_name from m_lib_mc_services order by service_name";
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
        $sql = "select service_name from m_lib_mc_services where service_id = '$service_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_service() {
    //
    // called by _mc_services()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["service_id"]) {
                $sql = "select service_id, service_name ".
                       "from m_lib_mc_services where service_id = '".$get_vars["service_id"]."'";
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
        print "<span class='library'>".FTITLE_MC_SERVICE_FORM."</span><br><br>";
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
                    $sql = "insert into m_lib_mc_services (service_id, service_name) ".
                           "values ('".strtoupper($post_vars["service_id"])."', '".$post_vars["service_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Service":
                    $sql = "update m_lib_mc_services set ".
                           "service_name = '".$post_vars["service_name"]."' ".
                           "where service_id = '".$post_vars["service_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Service":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_mc_services where service_id = '".$post_vars["service_id"]."'";
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
        print "<span class='library'>".FTITLE_MC_SERVICES_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select service_id, service_name from m_lib_mc_services order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&service_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _mc_outcomes() {
    //
    // main submodule for pregnancy outcomes
    // calls form_outcome()
    //       display_outcome()
    //       process_outcome()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('mc')) {
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
        $m = new mc;
        if ($post_vars["submitoutcome"]) {
            $m->process_outcome($menu_id, $post_vars, $get_vars);
        }
        $m->display_outcome($menu_id, $post_vars, $get_vars);
        $m->form_outcome($menu_id, $post_vars, $get_vars);
    }

    function show_pregnancy_outcomes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $outcome_id = $arg_list[0];
        }
        $sql = "select outcome_id, outcome_name from m_lib_mc_outcome order by outcome_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='outcome' class='textbox'>";
                $ret_val .= "<option value=''>Select Outcome</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($outcome_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_pregnancy_outcome() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $outcome_id = $arg_list[0];
        }
        $sql = "select outcome_name from m_lib_mc_outcome where outcome_id = '$outcome_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_outcome() {
    //
    // called by _mc_outcomes()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["outcome_id"]) {
                $sql = "select outcome_id, outcome_name ".
                       "from m_lib_mc_outcome where outcome_id = '".$get_vars["outcome_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $outcome = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_outcome' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_PREGNANCY_OUTCOME_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OUTCOME_ID."</span><br> ";
        if ($get_vars["outcome_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='outcome_id' value='".$get_vars["outcome_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='outcome_id' value='".($outcome["outcome_id"]?$outcome["outcome_id"]:$post_vars["outcome_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OUTCOME_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='outcome_name' value='".($outcome["outcome_name"]?$outcome["outcome_name"]:$post_vars["outcome_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["outcome_id"]) {
            print "<input type='hidden' name='outcome_id' value='".$get_vars["outcome_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Outcome' class='textbox' name='submitoutcome' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Outcome' class='textbox' name='submitoutcome' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Outcome' class='textbox' name='submitoutcome' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_outcome() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitoutcome"]) {
            if ($post_vars["outcome_id"] && $post_vars["outcome_name"]) {
                switch($post_vars["submitoutcome"]) {
                case "Add Outcome":
                    $sql = "insert into m_lib_mc_outcome (outcome_id, outcome_name) ".
                           "values ('".strtoupper($post_vars["outcome_id"])."', '".$post_vars["outcome_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Outcome":
                    $sql = "update m_lib_mc_outcome set ".
                           "outcome_name = '".$post_vars["outcome_name"]."' ".
                           "where outcome_id = '".$post_vars["outcome_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Outcome":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_mc_outcome where outcome_id = '".$post_vars["outcome_id"]."'";
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

    function display_outcome() {
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
        print "<span class='library'>".FTITLE_PREGNANCY_OUTCOMES."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select outcome_id, outcome_name from m_lib_mc_outcome order by outcome_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&outcome_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _mc_risk_factors() {
    //
    // main submodule for maternal risk factors
    // calls form_riskfactor()
    //       display_riskfactor()
    //       process_riskfactor()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('mc')) {
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
        $m = new mc;
        if ($post_vars["submitriskfactor"]) {
            $m->process_riskfactor($menu_id, $post_vars, $get_vars);
        }
        $m->display_riskfactor($menu_id, $post_vars, $get_vars);
        $m->form_riskfactor($menu_id, $post_vars, $get_vars);
    }

    function show_riskfactor() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $risk_id = $arg_list[0];
        }
        $sql = "select risk_id, risk_name from m_lib_mc_risk_factors order by risk_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='riskfactor' class='textbox'>";
                $ret_val .= "<option value=''>Select Risk Factor</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($risk_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_riskfactor_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $risk_id = $arg_list[0];
        }
        $sql = "select risk_name from m_lib_mc_risk_factors where risk_id = '$risk_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_riskfactor() {
    //
    // called by _mc_risk_factors()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["risk_id"]) {
                $sql = "select risk_id, risk_name, hospital_flag, monitor_flag ".
                       "from m_lib_mc_risk_factors where risk_id = '".$get_vars["risk_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $risk = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<a name='riskform'>";
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"]."' name='form_riskfactor' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_MATERNAL_RISK_FACTORS_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_HOSPITAL_FLAG."?</span><br> ";
        print "<input type='checkbox' name='hospital_flag' value='1' ".($risk["hospital_flag"]=="Y"?"checked":"")."' > ".INSTR_HOSPITAL_FLAG."<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_MONITOR_FLAG."?</span><br> ";
        print "<input type='checkbox' name='monitor_flag' value='1' ".($risk["monitor_flag"]=="Y"?"checked":"")."' > ".INSTR_MONITOR_FLAG."<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_RISK_NAME."</span><br> ";
        print "<textarea name='risk_name' cols='40' rows='3' class='textbox' style='border: 1px solid black'>".stripslashes($risk["risk_name"]?$risk["risk_name"]:$post_vars["risk_name"])."</textarea><br/>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["risk_id"]) {
            print "<input type='hidden' name='risk_id' value='".$get_vars["risk_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Risk Factor' class='textbox' name='submitriskfactor' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Risk Factor' class='textbox' name='submitriskfactor' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Risk Factor' class='textbox' name='submitriskfactor' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_riskfactor() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($post_vars);
        }
        if ($post_vars["submitriskfactor"]) {
            if ($post_vars["risk_name"]) {
                $hospital_flag = ($post_vars["hospital_flag"]?"Y":"N");
                $monitor_flag = ($post_vars["monitor_flag"]?"Y":"N");
                switch($post_vars["submitriskfactor"]) {
                case "Add Risk Factor":
                    $sql = "insert into m_lib_mc_risk_factors (risk_name, hospital_flag, monitor_flag) ".
                           "values ('".$post_vars["risk_name"]."', '$hospital_flag', '$monitor_flag')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Risk Factor":
                    $sql = "update m_lib_mc_risk_factors set ".
                           "hospital_flag = '$hospital_flag', ".
                           "monitor_flag = '$monitor_flag', ".
                           "risk_name = '".$post_vars["risk_name"]."' ".
                           "where risk_id = '".$post_vars["risk_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Risk Factor":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_mc_risk_factors where risk_id = '".$post_vars["risk_id"]."'";
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

    function display_riskfactor() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='500' cellspacing='0' cellpadding='2'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_MATERNAL_RISKFACTORS."</span><br>";
        print "</td></tr>";
        print "<tr valign='top' bgcolor='#FFCC33'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_MONITOR_FLAG."</b></td><td><b>".THEAD_HOSPITAL_FLAG."</b></td></tr>";
        $sql = "select risk_id, risk_name, hospital_flag, monitor_flag from m_lib_mc_risk_factors order by risk_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $hospital, $monitor) = mysql_fetch_array($result)) {
                    $bgcolor = ($bgcolor=="#CCCCFF"?"#FFFFFF":"#CCCCFF");
                    print "<tr valign='top' bgcolor='$bgcolor'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&risk_id=$id#riskform'>$name</a></td><td>$monitor</td><td>$hospital</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

// end of class
}
?>
