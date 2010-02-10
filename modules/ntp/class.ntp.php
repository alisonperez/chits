<?
class ntp extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function ntp() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD / darth_ali';
        $this->version = "0.81-".date("Y-m-d");
        $this->module = "ntp";
        $this->description = "CHITS Module - Natl TB Program";
        // 0.3 installed foreign key constraints
        // 0.4 revised intensive display
        // 0.5 extensive revisions on foreign key constraints
        // 0.6 added milestone dates in m_patient_ntp
        //     intensive_start_date, maintenance_start_date, treatment_end_date,
        //     intensive_projected_end_date, maintenance_projected_end_date
        // 0.7 debugged and optimized refresh and display of ntp data
        // 0.8 added projected sputum exam dates
            // 0.81. added a SYMPTOMATIC submenu

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
        module::set_dep($this->module, "calendar");
        module::set_dep($this->module, "appointment");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_PATIENT_TYPE_FORM", "english", "PATIENT TYPE FORM", "Y");
        module::set_lang("LBL_TYPE_ID", "english", "TYPE ID", "Y");
        module::set_lang("LBL_TYPE_NAME", "english", "TYPE NAME", "Y");
        module::set_lang("FTITLE_NTP_PATIENT_TYPE", "english", "NTP PATIENT TYPES", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("FTITLE_TREATMENT_OUTCOME_FORM", "english", "TREATMENT OUTCOME FORM", "Y");
        module::set_lang("FTITLE_NTP_TREATMENT_OUTCOME", "english", "TREATMENT OUTCOMES", "Y");
        module::set_lang("LBL_OUTCOME_ID", "english", "OUTCOME ID", "Y");
        module::set_lang("LBL_OUTCOME_NAME", "english", "OUTCOME NAME", "Y");
        module::set_lang("FTITLE_NTP_TREATMENT_PARTNER", "english", "TREATMENT PARTNERS", "Y");
        module::set_lang("FTITLE_TREATMENT_PARTNER_FORM", "english", "TREATMENT PARTNER FORM", "Y");
        module::set_lang("LBL_PARTNER_ID", "english", "PARTNER ID", "Y");
        module::set_lang("LBL_PARTNER_NAME", "english", "PARTNER NAME", "Y");
        module::set_lang("LBL_SPUTUM_EXAM", "english", "SPUTUM EXAM DONE", "Y");
        module::set_lang("LBL_PATIENT_TYPE", "english", "PATIENT TYPE", "Y");
        module::set_lang("LBL_TREATMENT_CAT", "english", "TREATMENT CATEGORY", "Y");
        module::set_lang("LBL_TREATMENT_PARTNER", "english", "TREATMENT PARTNER", "Y");
        module::set_lang("FTITLE_NTP_DATA_FORM", "english", "NTP CONSULT DATA", "Y");
        module::set_lang("FTITLE_NTP_LAB_FORM", "english", "NTP LAB REQUEST", "Y");
        module::set_lang("LBL_LAB_EXAM", "english", "LAB EXAM", "Y");
        module::set_lang("FTITLE_NTP_LABS", "english", "NTP LAB EXAMS", "Y");
        module::set_lang("LBL_LABS", "english", "LAB EXAMS", "Y");
        module::set_lang("LBL_TREATMENT_OUTCOME", "english", "TREATMENT OUTCOME", "Y");
        module::set_lang("FTITLE_NTP_VISIT1_DATA", "english", "NTP FIRST VISIT DATA", "Y");
        module::set_lang("LBL_OCCUPATION", "english", "OCCUPATION", "Y");
        module::set_lang("LBL_HOUSEHOLD_CONTACTS", "english", "NUMBER OF HOUSEHOLD CONTACTS", "Y");
        module::set_lang("LBL_BCG_SCAR", "english", "BCG SCAR PRESENT?", "Y");
        module::set_lang("LBL_PREVIOUS_TREATMENT", "english", "PREVIOUS TREATMENT?", "Y");
        module::set_lang("LBL_PREVIOUS_TREATMENT_DURATION", "english", "DURATION OF PREVIOUS TREATMENT", "Y");
        module::set_lang("LBL_PREVIOUS_TREATMENT_DRUGS", "english", "DRUGS IN PREVIOUS TREATMENT", "Y");
        module::set_lang("LBL_PREVIOUS_TREATMENT_DRUGS", "english", "DRUGS IN PREVIOUS TREATMENT", "Y");
        module::set_lang("LBL_CONTACT_PERSON", "english", "CONTACT PERSON", "Y");
        module::set_lang("INSTR_NTP_VISIT1_DATA", "english", "NOTE: The form below is for first visit confirmed TB patients only. Saving data creates a new registry entry for this patient.", "Y");
        module::set_lang("FTITLE_NTP_INTAKE_DATA_FORM", "english", "NTP INTENSIVE PHASE DATA", "Y");
        module::set_lang("FTITLE_NTP_COLLECTION_DATA_FORM", "english", "NTP MAINTENANCE PHASE DATA", "Y");
        module::set_lang("LBL_INTENSIVE_PHASE", "english", "INTENSIVE PHASE ONLY", "Y");
        module::set_lang("LBL_MAINTENANCE_PHASE", "english", "MAINTENANCE PHASE ONLY", "Y");
        module::set_lang("LBL_REGISTRY_ID", "english", "SELECT REGISTRY ID", "Y");
        module::set_lang("LBL_NTP_REGISTRY_ID", "english", "REGISTRY ID", "Y");
        module::set_lang("LBL_TREATMENT_DATE", "english", "TREATMENT DATE", "Y");
        module::set_lang("INSTR_TREATMENT_DATE", "english", "Select date from calendar above.", "Y");
        module::set_lang("LBL_INTAKE_FLAG", "english", "INTAKE COMPLETED?", "Y");
        module::set_lang("LBL_INTAKE_REMARKS", "english", "INTAKE REMARKS", "Y");
        module::set_lang("LBL_REGION", "english", "REGION", "Y");
        module::set_lang("FTITLE_NTP_TREATMENT_CATEGORY", "english", "NTP TREATMENT CATEGORIES", "Y");
        module::set_lang("FTITLE_TREATMENT_CATEGORY_FORM", "english", "NTP TREATMENT CATEGORY FORM", "Y");
        module::set_lang("LBL_CATEGORY_ID", "english", "CATEGORY ID", "Y");
        module::set_lang("LBL_CATEGORY_NAME", "english", "CATEGORY NAME", "Y");
        module::set_lang("LBL_CATEGORY_DETAILS", "english", "CATEGORY DETAILS", "Y");
        module::set_lang("THEAD_DETAILS", "english", "DETAILS", "Y");
        module::set_lang("LBL_FOLLOWUP_DATE", "english", "FOLLOW UP DATE", "Y");
        module::set_lang("LBL_TB_CLASS", "english", "TB CLASS", "Y");
        module::set_lang("LBL_ACTUAL_DATE", "english", "ACTUAL FOLLOWUP DATE", "Y");
        module::set_lang("LBL_REMARKS", "english", "REMARKS", "Y");
        module::set_lang("INSTR_TREATMENT_OUTCOME", "english", "Selecting any other choice aside from <b>Under Treatment</b> ends treatment course.", "Y");
        module::set_lang("FTITLE_ASSIGN_NON_NTP_LABS", "english", "ASSIGN NON-NTP LABS", "Y");
        module::set_lang("INSTR_ASSIGN_NON_NTP_LABS", "english", "SELECT LAB EXAM TO ASSIGN NTP COVERAGE", "Y");
        module::set_lang("INSTR_VIEW_REGISTRY_DATA", "english", "CLICK ON DATE TO VIEW REGISTRY DATA", "Y");
        module::set_lang("FTITLE_NTP_FOLLOWUP_RECORD", "english", "NTP FOLLOWUP RECORDS", "Y");
        module::set_lang("FTITLE_NTP_APPOINTMENTS", "english", "NTP APPOINTMENTS", "Y");
        module::set_lang("FTITLE_NTP_APPOINTMENT_FORM", "english", "NTP APPOINTMENT FORM", "Y");
        module::set_lang("LBL_APPOINTMENT_SELECTION", "english", "SELECT APPOINTMENT", "Y");

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
        module::set_menu($this->module, "NTP Followup", "CONSULTS", "_consult_ntp_followup");
        module::set_menu($this->module, "NTP Patient Type", "LIBRARIES", "_ntp_patient_type");
        module::set_menu($this->module, "NTP Outcomes", "LIBRARIES", "_ntp_outcomes");
        module::set_menu($this->module, "NTP Partners", "LIBRARIES", "_ntp_partners");
        module::set_menu($this->module, "NTP Lab Exams", "LIBRARIES", "_ntp_labs");
        module::set_menu($this->module, "NTP Tx Regimens", "LIBRARIES", "_ntp_treatment_category");
        module::set_menu($this->module, "NTP Appointments", "LIBRARIES", "_ntp_appointments");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_ntp_appointment` (".
            "`appointment_id` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`appointment_id`),".
            "CONSTRAINT `m_lib_ntp_appointment_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `m_lib_appointment` (`appointment_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("INSERT INTO `m_lib_ntp_appointment` VALUES ('SPT');");
        module::execsql("INSERT INTO `m_lib_ntp_appointment` VALUES ('NTPI');");
        module::execsql("INSERT INTO `m_lib_ntp_appointment` VALUES ('NTPM');");

        module::execsql("CREATE TABLE `m_lib_ntp_patient_type` (".
            "`type_id` varchar(10) NOT NULL default '',".
            "`type_name` varchar(40) NOT NULL default '',".
            "PRIMARY KEY  (`type_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("insert into m_lib_ntp_patient_type (type_id, type_name) values ('NEW', 'New Patient')");
        module::execsql("insert into m_lib_ntp_patient_type (type_id, type_name) values ('REL', 'Relapse')");
        module::execsql("insert into m_lib_ntp_patient_type (type_id, type_name) values ('TIN', 'Transfer In')");
        module::execsql("insert into m_lib_ntp_patient_type (type_id, type_name) values ('RAD', 'Return After Deferral')");
        module::execsql("insert into m_lib_ntp_patient_type (type_id, type_name) values ('FAIL', 'Treatment Failure')");
        module::execsql("insert into m_lib_ntp_patient_type (type_id, type_name) values ('OTH', 'Others')");

        module::execsql("CREATE TABLE `m_lib_ntp_treatment_outcome` (".
            "`outcome_id` varchar(10) NOT NULL default '',".
            "`outcome_name` varchar(40) NOT NULL default '',".
            "PRIMARY KEY  (`outcome_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('CURE', 'Cured')");
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('COMP', 'Treatment Completed')");
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('DIED', 'Patient Died')");
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('FAIL', 'Treatment Failure')");
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('LOST', 'Lost to Follow-up')");
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('TOUT', 'Transfer Out')");
        module::execsql("insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) values ('TX', 'Under Treatment')");

        module::execsql("CREATE TABLE `m_lib_ntp_treatment_category` (".
            "`cat_id` char(3) NOT NULL default '',".
            "`cat_name` varchar(50) NOT NULL default '',".
            "`cat_details` tinytext NOT NULL,".
            "PRIMARY KEY  (`cat_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("INSERT INTO `m_lib_ntp_treatment_category` VALUES ('1','Regimen 1','6SCC (2HRZE/4HR)\nNew case\n1. Smear (+)\n2. Seriously ill:\n2.1 Smear (-); extensive lung lesion; moderately or far advanced radiolographic lesion\n2.2 Extra-pulmonary cases\n');");
        module::execsql("INSERT INTO `m_lib_ntp_treatment_category` VALUES ('2','Regimen 2','8SCC (2HRZES/1HRZE/5HRE)\n1. Relapse\n2. Faliures\n3. Others');");
        module::execsql("INSERT INTO `m_lib_ntp_treatment_category` VALUES ('3','Regimen 3','4SCC (2HRZ/2HR)\n1. New case: smear(-) PTB Minimal');");

        // tb_class: P=pulmonary E=extrapulmonary
        module::execsql("CREATE TABLE `m_patient_ntp` (".
            "`patient_id` float NOT NULL default '0',".
            "`ntp_id` float NOT NULL auto_increment,".
            "`user_id` float NOT NULL default '0',".
            "`occupation_id` varchar(10) NOT NULL default '',".
            "`ntp_consult_date` datetime NOT NULL default '0000-00-00 00:00:00',".
            "`intensive_start_date` date NOT NULL default '0000-00-00',".
            "`maintenance_start_date` date NOT NULL default '0000-00-00',".
            "`treatment_end_date` date NOT NULL default '0000-00-00',".
            "`intensive_projected_end_date` date NOT NULL default '0000-00-00',".
            "`maintenance_projected_end_date` date NOT NULL default '0000-00-00',".
            "`household_contacts` int(11) NOT NULL default '0',".
            "`region_id` varchar(10) NOT NULL default '',".
            "`body_weight` float NOT NULL default '0',".
            "`bcg_scar` char(1) NOT NULL default '',".
            "`tb_class` char(1) NOT NULL default 'P',".
            "`previous_treatment_flag` char(1) NOT NULL default '',".
            "`previous_treatment_duration` varchar(10) NOT NULL default '',".
            "`previous_treatment_drugs` varchar(10) NOT NULL default '',".
            "`treatment_category_id` varchar(10) NOT NULL default '',".
            "`contact_person` varchar(50) NOT NULL default '',".
            "`outcome_id` varchar(10) NOT NULL default '',".
            "`patient_type_id` varchar(10) NOT NULL default '',".
            "`treatment_partner_id` varchar(10) NOT NULL default '',".
            "`healthcenter_id` varchar(10) NOT NULL default '',".
            "`ntp_timestamp` timestamp(14) NOT NULL,".
            "`course_end_flag` char(1) NOT NULL default 'N',".
            "PRIMARY KEY  (`ntp_id`),".
            "KEY `key_region` (`region_id`),".
            "KEY `key_treatment_category` (`treatment_category_id`),".
            "KEY `key_outcome` (`outcome_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_partner` (`treatment_partner_id`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_patient_type` (`patient_type_id`),".
            "KEY `key_occupation` (`occupation_id`),".
            "CONSTRAINT `m_patient_ntp_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        // version 0.8 for sputum exam projections
        module::execsql("set foreign_key_checks=0;");
        module::execsql("ALTER TABLE `m_patient_ntp`".
            "ADD COLUMN `sputum1_date` date NOT NULL,".
            "ADD COLUMN `sputum2_date` date NOT NULL,".
            "ADD COLUMN `sputum3_date` date NOT NULL;");
        module::execsql("set foreign_key_checks=1;");

        module::execsql("CREATE TABLE `m_consult_ntp_sputum` (".
            "`consult_id` float NOT NULL default '0',".
            "`ntp_id` float NOT NULL default '0',".
            "`request_id` int(11) NOT NULL default '0',".
            "`sputum_exam_date` date NOT NULL default '0000-00-00',".
            "`sputum_exam_result` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`consult_id`),".
            "INDEX (ntp_id), ".
            "CONSTRAINT `m_consult_ntp_sputum_ibfk_2` FOREIGN KEY (`ntp_id`) REFERENCES `m_patient_ntp` (`ntp_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ntp_sputum_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_lib_ntp_treatment_partner` (".
            "`partner_id` varchar(10) NOT NULL default '',".
            "`partner_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`partner_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('PRIVMD', 'Private Physician')");
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('HCMD', 'Health Center Physician')");
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('BHW', 'Barangay Health Worker')");
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('BN', 'Barangay Nurse')");
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('BMW', 'Barangay Midwife')");
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('FAM', 'Family Member')");
        module::execsql("insert into m_lib_ntp_treatment_partner (partner_id, partner_name) values ('LABAIDE', 'Lab Aide')");

        // NTP Labs
        module::execsql("CREATE TABLE `m_consult_ntp_labs` (".
            "`lab_id` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`lab_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("insert into m_consult_ntp_labs (lab_id) values ('CXR')");
        module::execsql("insert into m_consult_ntp_labs (lab_id) values ('SPT')");

        module::execsql("CREATE TABLE `m_consult_ntp_collection_maintenance` (".
            "`ntp_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`ntp_timestamp` timestamp(14) NOT NULL,".
            "`treatment_month` int(11) NOT NULL default '0',".
            "`treatment_week` int(11) NOT NULL default '0',".
            "`treatment_date` date NOT NULL default '0000-00-00',".
            "`intake_flag` char(1) NOT NULL default 'N', ".
            "`remarks` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`consult_id`,`ntp_id`, `treatment_date`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_patient` (`patient_id`),".
            "INDEX (ntp_id), ".
            "CONSTRAINT `m_consult_ntp_collection_maintenance_ibfk_2` FOREIGN KEY (`ntp_id`) REFERENCES `m_patient_ntp` (`ntp_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ntp_collection_maintenance_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_consult_ntp_due_dates` (".
            "`ntp_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`ntp_timestamp` timestamp(14) NOT NULL,".
            "`treatment_month` int(11) NOT NULL default '0',".
            "`treatment_week` int(11) NOT NULL default '0',".
            "`treatment_due_date` date NOT NULL default '0000-00-00',".
            "`treatment_actual_date` date NOT NULL default '0000-00-00',".
            "`remarks` varchar(100) NOT NULL default '',".
            " PRIMARY KEY  (`consult_id`,`ntp_id`),".
            " KEY `key_patient` (`patient_id`),".
            "INDEX (ntp_id), ".
            " CONSTRAINT `m_consult_ntp_due_dates_ibfk_2` FOREIGN KEY (`ntp_id`) REFERENCES `m_patient_ntp` (`ntp_id`) ON DELETE CASCADE,".
            " CONSTRAINT `m_consult_ntp_due_dates_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_consult_ntp_intake_intensive` (".
            "`ntp_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`ntp_timestamp` timestamp(14) NOT NULL,".
            "`treatment_month` int(11) NOT NULL default '0',".
            "`treatment_week` int(11) NOT NULL default '0',".
            "`treatment_date` date NOT NULL default '0000-00-00',".
            "`intake_flag` char(1) NOT NULL default 'N', ".
            "`remarks` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`consult_id`,`ntp_id`,`treatment_date`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_user` (`user_id`),".
            "INDEX (ntp_id), ".
            "CONSTRAINT `m_consult_ntp_intake_intensive_ibfk_2` FOREIGN KEY (`ntp_id`) REFERENCES `m_patient_ntp` (`ntp_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ntp_intake_intensive_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_consult_ntp_labs_request` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`ntp_id` float NOT NULL default '0',".
            "`request_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`request_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`request_id`,`ntp_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `ntp_id` (`ntp_id`),".
            "KEY `request_id` (`request_id`),".
            "CONSTRAINT `m_consult_ntp_labs_request_ibfk_3` FOREIGN KEY (`ntp_id`) REFERENCES `m_patient_ntp` (`ntp_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ntp_labs_request_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_ntp_labs_request_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `m_consult_lab` (`request_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");
            


        module::execsql("CREATE TABLE `m_consult_ntp_symptomatics` (
            `symptomatic_id` FLOAT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `ntp_id` float NOT NULL,
            `consult_id` FLOAT NOT NULL ,
            `patient_id` INT NOT NULL ,
            `sputum_diag1` FLOAT NOT NULL ,
            `sputum_diag2` FLOAT NOT NULL ,
            `xray_date_referred` DATE NOT NULL ,
            `xray_date_received` INT NOT NULL ,
            `xray_result` CHAR( 1 ) NOT NULL ,
            `remarks` TEXT NOT NULL ,
            `user_id` FLOAT NOT NULL ,
            `date_updated` DATETIME NOT NULL
        ) ENGINE = MYISAM ;");

    }

    function drop_tables() {

        module::execsql("set foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_lib_ntp_appointment`;");
        module::execsql("DROP TABLE `m_consult_ntp_labs_request`;");
        module::execsql("DROP TABLE `m_lib_ntp_patient_type`;");
        module::execsql("DROP TABLE `m_lib_ntp_treatment_outcome`;");
        module::execsql("DROP TABLE `m_lib_ntp_treatment_partner`;");
        module::execsql("DROP TABLE `m_lib_ntp_treatment_category`");
        module::execsql("DROP TABLE `m_consult_ntp_sputum`;");
        module::execsql("DROP TABLE `m_consult_ntp_labs`;");
        module::execsql("DROP TABLE `m_consult_ntp_collection_maintenance`;");
        module::execsql("DROP TABLE `m_consult_ntp_due_dates`;");
        module::execsql("DROP TABLE `m_consult_ntp_intake_intensive`;");
        module::execsql("DROP TABLE `m_patient_ntp`;");
        module::execsql("set foreign_key_checks=1;");

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------


    function _consult_ntp_followup() {
    //
    // main submodule for listing patients who
    // failed to followup by the day
    //
       // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        print "<span class='patient'>".FTITLE_NTP_FOLLOWUP_RECORD."</span><br/><br/>";
        $base_url = $_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"];
        if ($get_vars["year"] && $get_vars["month"] && $get_vars["day"]) {
            $date = $get_vars["year"]."-".$get_vars["month"]."-".$get_vars["day"];
        } else {
            $date = date("Y-m-d");
        }
        print "<table><tr valign='top'><td>";
        calendar::display_calendar($menu_id, $post_vars, $get_vars, $validuser, $isadmin, $base_url);
        print "</td><td>";
        print "<span class='tinylight'>";
        print "<ol><b>NTP FOLLOWUP HOWTO:</b></ol>";
        print "<ol>";
        print "<li>THIS PAGE SHOWS PATIENTS ENROLLED IN THE NTP PROGRAM WHO FAILED TO APPEAR ON THE SELECTED DATE.</li>";
        print "<li>TO SEE FAILED APPOINTMENTS OTHER THAN TODAY CLICK ON DESIRED CALENDAR DATE.</li>";
        print "<li>YOU CAN ALSO USE THE CALENDAR NAVIGATION BUTTONS TO GO TO A DIFFERENT MONTH OR YEAR.</li>";
        print "</ol>";
        print "</span>";
        print "</td></tr></table>";
        $sql = "select a.schedule_id, a.patient_id, p.patient_lastname, p.patient_firstname, ".
               "p.patient_dob, p.patient_gender, l.appointment_name, ".
               "round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age ".
               "from m_patient p, m_consult_appointments a, m_lib_appointment l ".
               "where p.patient_id = a.patient_id and ".
               "a.appointment_id = l.appointment_id and ".
               "to_days(a.visit_date) = to_days('$date') and ".
               "actual_date = '0000-00-00' ".
               "order by p.patient_lastname, p.patient_firstname";
        if ($result = mysql_query($sql)) {
            print "<br/><table width=600 bgcolor='#FFFFFF' cellpadding='3' cellspacing='0' style='border: 2px solid black'>";
            print "<tr><td>";
            print "<span class='tinylight'><b>".LBL_EXPECTED_TO_ARRIVE_TODAY." ".$date.":</b></span><br/><br/>";
            if (mysql_num_rows($result)) {
                $i=0;
                while (list($sid, $pid, $plast, $pfirst, $pdob, $pgender, $appname, $p_age) = mysql_fetch_array($result)) {
                    if ($prev_app<>$appname) {
                        $patient_array[$i] .= "<span class='boxtitle'><font color='red'>".strtoupper($appname)."</font></span><br/>";
                    }
                    $patient_array[$i] .= "<a href='".$_SERVER["PHP_SELF"]."?page=PATIENTS&menu_id=$menu_id&patient_id=$pid'><b>$plast, $pfirst</b></a> [$p_age/$pgender] $pdob";
                    if (class_exists("family")) {
                        // show family icon if patient has a family
                        $family_id = family::get_family_id($pid);
                        if ($family_id<>0) {
                            $family_menu_id = module::get_menu_id("_family");
                            $patient_array[$i] .= " <a href='".$_SERVER["PHP_SELF"]."?page=PATIENTS&menu_id=$family_menu_id&family_id=$family_id' title='GO TO FAMILY'><img src='../images/family.gif' border='0'/></a>";
                        }
                    }
                    if (class_exists("healthcenter")) {
                        $consult_menu_id = module::get_menu_id("_consult");
                        $patient_array[$i] .= " <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$consult_menu_id&enter_consult=$pid&appt_date=$date&sked_id=$sid' title='LOAD PATIENT RECORD'><img src='../images/records.gif' border='0'/></a>";
                    }
                    $i++;
                    $prev_app = $appname;
                }
                print $this->columnize_list($patient_array);
            } else {
                print "<font color='red'>No patients scheduled today.</font><br/>";
            }
            print "</td></tr>";
            print "</table>";
        }
    }

    function _ntp_labs() {
    //
    // main submodule for segregating NTP Labs
    // calls form_ntp_lab()
    //       display_ntp_lab()
    //       process_ntp_lab()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $n = new ntp;
        if ($post_vars["submitntplab"]) {
            $n->process_ntp_lab($menu_id, $post_vars, $get_vars);
        }
        $n->display_ntp_labs($menu_id, $post_vars, $get_vars);
        $n->form_ntp_lab($menu_id, $post_vars, $get_vars);
    }

    function show_ntp_labs() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $type_id = $arg_list[0];
        }
        $sql = "select n.lab_id, l.lab_name ".
               "from m_lib_laboratory l, m_consult_ntp_labs n ".
               "where l.lab_id = n.lab_id order by l.lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<input type='checkbox' name='ntp_lab[]' value='$id' class='textbox' /> $name<br />";
                }
                return $ret_val;
            }
        }
    }

    function form_ntp_lab() {
    //
    // called by _ntp_labs()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_patient_type' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_NTP_LAB_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LABS."</span><br> ";
        print lab::show_lab_exams();
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["lab_id"]) {
            print "<input type='hidden' name='type_id' value='".$get_vars["type_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Type' class='textbox' name='submittype' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Type' class='textbox' name='submittype' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add NTP Exam' class='textbox' name='submitntplab' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function display_ntp_labs() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_ntp_lab' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_NTP_LABS."</span><br>";
        print "</td></tr>";
        $sql = "select n.lab_id, l.lab_name ".
               "from m_lib_laboratory l, m_consult_ntp_labs n ".
               "where l.lab_id = n.lab_id ".
               "order by l.lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td><input type='checkbox' name='ntp_labs[]' value='$id'/> $name</td></tr>";
                }
                print "<tr valign='top'><td>";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' value = 'Delete NTP Exam' class='textbox' name='submitntplab' style='border: 1px solid #000000'> ";
                }
                print "</td></tr>";
            }
        }
        print "</form>";
        print "</table><br>";
    }

    function process_ntp_lab() {
    //
    // tags lab exams as NTP exams
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
        if ($post_vars["submitntplab"]) {
            switch($post_vars["submitntplab"]) {
            case "Add NTP Exam":
                if ($post_vars["lab_exam"]) {
                    $sql = "insert into m_consult_ntp_labs (lab_id) ".
                           "values ('".$post_vars["lab_exam"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                }
                break;
            case "Delete NTP Exam":
                    foreach($post_vars["ntp_labs"] as $key=>$value) {
                        $sql = "delete from m_consult_ntp_labs where lab_id = '$value'";
                        $result = mysql_query($sql);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                break;
            }
        }
    }

    function _consult_ntp() {
    //
    // main submodule for consult
    // called from healthcenter _consult()
    // calls form_patient_type()
    //       display_patient_type()
    //       process_patient_type()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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

        $n = new ntp;

        $n->ntp_menu($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        if ($post_vars["submitntp"]) {
            $n->process_ntp($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        }
        switch($get_vars["ntp"]) {

	case "SYMP":
	    $n->form_ntp_symptomatic($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
	    break;

        case "VISIT1":
            $n->form_patient_ntp($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            break;
        case "INTAKE":
            //$base_url = $_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"];
            $n->form_intensive_ntp($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            break;
        case "COLL":
            //$base_url = $_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"];
            $n->form_maintenance_ntp($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            break;
        case "LABS":
            // lab requests: either request or generate referral
            $n->form_consult_ntp_lab($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            // lab requests done outside of ntp but can be
            // assigned to ntp, e.g., first sputum exam
            $n->form_consult_assign_lab($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            break;
        }
    }

    function ntp_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["ntp"])) {

            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=VISIT1".($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:""));
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";

	print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=SYMP".($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["ntp"]=="SYMP"?"<b>TB SYMPTOMATIC</b>":"TB SYMPTOMATIC"))."</a>";

        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=VISIT1".($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["ntp"]=="VISIT1"?"<b>VISIT1</b>":"VISIT1"))."</a>";

	


        if ($get_vars["ntp_id"]) {
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=INTAKE".($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["ntp"]=="INTAKE"?"<b>INTENSIVE</b>":"INTENSIVE"))."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=COLL".($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["ntp"]=="COLL"?"<b>MAINT</b>":"MAINT"))."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=LABS".($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["ntp"]=="LABS"?"<b>LABS</b>":"LABS"))."</a>";
        }
        print "</td></tr></table><br/>";
    }

    function process_ntp() {
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
        switch($post_vars["submitntp"]) {
        case "Assign Lab Exam":
            if ($post_vars["labs"] && $post_vars["registry_id"]) {
                foreach($post_vars["labs"] as $key=>$value) {
                    print $sql = "insert into m_consult_ntp_labs_request (consult_id, patient_id, ntp_id, request_id, user_id, request_timestamp) ".
                           "values ('".$get_vars["consult_id"]."', '$patient_id', '".$post_vars["registry_id"]."', '$value', '".$_SESSION["userid"]."', sysdate())";
                    $result = mysql_query($sql);
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
            }
            break;
        case "Add Follow-up Date":
            if ($post_vars["treatment_actual_date"]) {
                list($month,$day,$year) = explode("/", $post_vars["treatment_actual_date"]);
                $actual_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                print $sql = "update m_consult_ntp_due_dates set ".
                       "treatment_actual_date = '$actual_date', ".
                       "remarks = '".addslashes($post_vars["remarks"])."' ".
                       "where ntp_id = '".$post_vars["ntp_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                }
            }
            break;
        case "Save NTP Data":
            if ($post_vars["occupation"] && $post_vars["region"] && $post_vars["bcg_scar"] && $post_vars["contact_person"] &&
                $post_vars["treatment_category"] && $post_vars["treatment_outcome"] && $post_vars["treatment_partner"]) {

                // always use what's existing
                $body_weight = healthcenter::get_body_weight($get_vars["consult_id"]);
                $bcg_scar = ($post_vars["bcg_scar"]?"Y":"N");

                $previous_tx = ($post_vars["previous_treatment_flag"]?"Y":"N");
                // save previous treatment drugs in one field
                if ($post_vars["previous_treatment_drugs"]) {
                    foreach($post_vars["previous_treatment_drugs"] as $key=>$drug) {
                        $tx_drugs .= $drug;
                    }
                }

                $sql_first = "insert into m_patient_ntp (patient_id, occupation_id, ".
                             "household_contacts, region_id, body_weight, bcg_scar, ".
                             "previous_treatment_flag, previous_treatment_duration, ".
                             "previous_treatment_drugs, treatment_category_id, ".
                             "contact_person, outcome_id, patient_type_id, ".
                             "treatment_partner_id, healthcenter_id, ntp_timestamp, ".
                             "ntp_consult_date, user_id, tb_class) ".
                             "values ('$patient_id', '".$post_vars["occupation"]."', ".
                             "'".$post_vars["hh_contacts"]."', '".$post_vars["region"]."', ".
                             "'$body_weight', '$bcg_scar', '$previous_tx', ".
                             "'".$post_vars["previous_treatment_duration"]."', '$tx_drugs', ".
                             "'".$post_vars["treatment_category"]."', ".
                             "'".$post_vars["contact_person"]."', ".
                             "'".$post_vars["treatment_outcome"]."', ".
                             "'".$post_vars["patient_type"]."', ".
                             "'".$post_vars["treatment_partner"]."', ".
                             "'".$_SESSION["datanode"]["code"]."', sysdate(), ".
                             "sysdate(), '".$_SESSION["userid"]."', ".
                             "'".$post_vars["tb_class"]."')";
                if ($result_first = mysql_query($sql_first)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
            break;
        case "Update NTP Data":
            if ($post_vars["occupation"] && $post_vars["region"] && $post_vars["bcg_scar"] && $post_vars["contact_person"] &&
                $post_vars["treatment_category"] && $post_vars["treatment_outcome"] && $post_vars["treatment_partner"]) {

                // always use what's existing
                $body_weight = healthcenter::get_body_weight($get_vars["consult_id"]);
                $bcg_scar = ($post_vars["bcg_scar"]?"Y":"N");

                // if treatment outcome is any other than TX end this course
                $course_end_flag = ($post_vars["treatment_outcome"]<>"TX"?"Y":"N");
                if ($course_end_flag=="Y") {
                    $treatment_end_date = date("Y-m-d");
                } else {
                    $treatment_end_date = '0000-00-00';
                }
                $previous_tx = ($post_vars["previous_treatment_flag"]?"Y":"N");
                if ($post_vars["previous_treatment_drugs"]) {
                    foreach($post_vars["previous_treatment_drugs"] as $key=>$drug) {
                        $tx_drugs .= $drug;
                    }
                }
                $sql_first = "update m_patient_ntp set ".
                             "occupation_id = '".$post_vars["occupation"]."', ".
                             "household_contacts = '".$post_vars["hh_contacts"]."', ".
                             "region_id = '".$post_vars["region"]."', ".
                             "bcg_scar = '$bcg_scar', ".
                             "body_weight = '$body_weight', ".
                             "previous_treatment_flag = '$previous_tx', ".
                             "previous_treatment_duration = '".$post_vars["previous_treatment_duration"]."', ".
                             "previous_treatment_drugs = '$tx_drugs', ".
                             "treatment_category_id = '".$post_vars["treatment_category"]."', ".
                             "contact_person = '".$post_vars["contact_person"]."', ".
                             "outcome_id = '".$post_vars["treatment_outcome"]."', ".
                             "course_end_flag = '$course_end_flag', ".
                             "treatment_end_date = '$treatment_end_date', ".
                             "patient_type_id = '".$post_vars["patient_type"]."', ".
                             "treatment_partner_id = '".$post_vars["treatment_partner"]."', ".
                             "ntp_timestamp = sysdate(), ".
                             "user_id = '".$_SESSION["userid"]."', ".
                             "tb_class = '".$post_vars["tb_class"]."' ".
                             "where ntp_id = '".$post_vars["ntp_id"]."' ";
                if ($result_first = mysql_query($sql_first)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=VISIT1&ntp_id=".$get_vars["ntp_id"]);
                }
            }
            break;
        case "Delete NTP Data":
            // this is a dummy entry
            // real delete code is in ntp::display_course();
            break;
        case "Save Intensive Data":
            if ($post_vars["treatment_date"]) {

                $start_date = ntp::get_start_date($post_vars["registry_id"]);
                $intake = ($post_vars["intake_flag"]?"Y":"N");
                list($month,$day,$year) = explode("/", $post_vars["treatment_date"]);
                $intake_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);

                $sql = "insert into m_consult_ntp_intake_intensive (ntp_id, consult_id, patient_id, user_id, treatment_month, treatment_week, treatment_date, intake_flag, remarks) ".
                       "values ('".$post_vars["registry_id"]."', '".$get_vars["consult_id"]."', '$patient_id', '".$_SESSION["userid"]."', (month(sysdate())+1)-month('$start_date'), (week(sysdate())+1)-week('$start_date'), '$intake_date', '$intake', '".addslashes($post_vars["remarks"])."')";
                if ($result = mysql_query($sql)) {
                    // if first treatment day,
                    //  put in projected dates
                    if (ntp::get_intensive_date($post_vars["registry_id"])=="0000-00-00") {
                        // get regimen and determine projected dates
                        // sputum exam intervals are given 1 week (7 days) lead time
                        $regimen = ntp::get_treatment_regimen($post_vars["registry_id"]);
                        switch ($regimen) {
                        case 1:
                            $interval_sputum1 = "49 day"; // towards end of 2nd month
                            $interval_sputum2 = "105 day"; // towards end of 4th month
                            //$interval_sputum3 = "161 day"; // towards end of 6th month
                            $interval_sputum3 = "140 day"; // in the beginning of 6th month
                            //$interval_intensive = "42 day";
                            $interval_intensive = "56 day";
                            //$interval_maintenance = "154 day";
                            $interval_maintenance = "168 day";
                            $sql_ntp = "update m_patient_ntp set ".
                                       "intensive_start_date = '$intake_date', ".
                                       "intensive_projected_end_date = '$intake_date' + interval $interval_intensive, ".
                                       "maintenance_projected_end_date = '$intake_date' + interval $interval_maintenance, ".
                                       "sputum1_date = '$intake_date' + interval $interval_sputum1, ".
                                       "sputum2_date = '$intake_date' + interval $interval_sputum2, ".
                                       "sputum3_date = '$intake_date' + interval $interval_sputum3 ".
                                       "where ntp_id = '".$post_vars["registry_id"]."'";
                            break;
                        case 3:
                            $interval_sputum1 = "49 day"; // towards end of 2nd month
                            //$interval_intensive = "42 day";
                            //$interval_maintenance = "154 day";
                            $interval_intensive = "56 day";
                            $interval_maintenance = "168 day";
                            $sql_ntp = "update m_patient_ntp set ".
                                       "intensive_start_date = '$intake_date', ".
                                       "intensive_projected_end_date = '$intake_date' + interval $interval_intensive, ".
                                       "maintenance_projected_end_date = '$intake_date' + interval $interval_maintenance, ".
                                       "sputum1_date = '$intake_date' + interval $interval_sputum1 ".
                                       "where ntp_id = '".$post_vars["registry_id"]."'";
                            break;
                        case 2:
                            $interval_sputum1 = "77 day"; // towards end of 3rd month
                            $interval_sputum2 = "133 day"; // towards end of 5th month
                            //$interval_sputum3 = "217 day"; // towards end of 8th month
                            $interval_sputum3 = "196 day"; // beginning of 8th month
                            $interval_intensive = "84 day";
                            $interval_maintenance = "224 day";
                            $sql_ntp = "update m_patient_ntp set ".
                                       "intensive_start_date = '$intake_date', ".
                                       "intensive_projected_end_date = '$intake_date' + interval $interval_intensive, ".
                                       "maintenance_projected_end_date = '$intake_date' + interval $interval_maintenance, ".
                                       "sputum1_date = '$intake_date' + interval $interval_sputum1, ".
                                       "sputum2_date = '$intake_date' + interval $interval_sputum2, ".
                                       "sputum3_date = '$intake_date' + interval $interval_sputum3 ".
                                       "where ntp_id = '".$post_vars["registry_id"]."'";
                            break;
                        }
                        $result_ntp = mysql_query($sql_ntp) or die(mysql_error());
                    }

                    // set appointment only if treatment date is today
                    // useless if treatment date is the past
                    // caution: do not enter first treatment date on a weekend
                    if ($intake_date == date("Y-m-d")) {
                        // if day today is a friday, add 3 days
                        // if saturday add 2 days
                        if (date("w")==5) {
                            $interval = 3; // monday
                        } elseif(date("w")==6) {
                            $interval = 2; // 1 day
                        } else {
                            $interval = 1; // 1 day
                        }
                        $sql_appt = "insert into m_consult_appointments (visit_date, ".
                                    "consult_id, schedule_timestamp, user_id, patient_id, ".
                                    "appointment_id) values (sysdate() + interval $interval day, ".
                                    "'".$get_vars["consult_id"]."', sysdate(), '".$_SESSION["userid"]."', ".
                                    "'$patient_id', 'NTPI')";
                        $result_app = mysql_query($sql_appt);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."#intensive_form");
                }
            } else {
                print "<font color='red'>No treatment date</font><br/>";
            }
            break;
        case "Save Maintenance Data":
            $start_date = ntp::get_start_date($post_vars["registry_id"]);
            $intake = ($post_vars["intake_flag"]?"Y":"N");

            // treatment date
            if ($post_vars["treatment_date"] && $post_vars["followup_date"]) {

                // treatment date
                list($txmonth,$txday,$txyear) = explode("/", $post_vars["treatment_date"]);
                $intake_date = $txyear."-".str_pad($txmonth, 2, "0", STR_PAD_LEFT)."-".str_pad($txday, 2, "0", STR_PAD_LEFT);
                // followup date
                list($ffmonth,$ffday,$ffyear) = explode("/", $post_vars["followup_date"]);
                $followup_date = $ffyear."-".str_pad($ffmonth, 2, "0", STR_PAD_LEFT)."-".str_pad($ffday, 2, "0", STR_PAD_LEFT);

                $sql = "insert into m_consult_ntp_collection_maintenance (ntp_id, consult_id, patient_id, user_id, treatment_month, treatment_week, treatment_date, intake_flag, remarks) ".
                       "values ('".$post_vars["registry_id"]."', '".$get_vars["consult_id"]."', '$patient_id', '".$_SESSION["userid"]."', (month(sysdate())+1)-month('$start_date'), (week(sysdate())+1)-week('$start_date'), '$intake_date', '$intake', '".addslashes($post_vars["remarks"])."')";
                if ($result = mysql_query($sql)) {
                    if (ntp::get_maintenance_date($post_vars["registry_id"])=="0000-00-00") {
                        $sql_ntp = "update m_patient_ntp set ".
                                   "maintenance_start_date = '$intake_date' ".
                                   "where ntp_id = '".$post_vars["registry_id"]."'";
                        $result_ntp = mysql_query($sql_ntp);
                    }
                    if ($followup_date) {
                        // set appointment based on followup date
                        // maintenance phase appointments are more flexible
                        $sql_appt = "insert into m_consult_appointments (visit_date, ".
                                    "consult_id, schedule_timestamp, user_id, patient_id, ".
                                    "appointment_id) values ('$followup_date', ".
                                    "'".$get_vars["consult_id"]."', sysdate(), '".$_SESSION["userid"]."', ".
                                    "'$patient_id', 'NTPM')";
                        $result_app = mysql_query($sql_appt);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."#maintenance_form");
                }
            } else {
                print "<font color='red'>Incomplete date(s)</font><br/>";
            }
            break;
        case "Send Request":
            if ($post_vars["registry_id"]) {
                foreach($post_vars["ntp_lab"] as $key=>$value) {
                    $sql_lab = "insert into m_consult_lab (consult_id, patient_id, lab_id, request_timestamp, request_user_id) ".
                               "values ('".$get_vars["consult_id"]."', '$patient_id', '$value', sysdate(), '".$_SESSION["userid"]."')";
                    if ($result_lab = mysql_query($sql_lab)) {
                        // try to insert in ntp lab registry
                        $insert_id = mysql_insert_id();
                        $sql_ntp = "insert into m_consult_ntp_labs_request (consult_id, patient_id, ntp_id, request_id, user_id, request_timestamp) ".
                                   "values ('".$get_vars["consult_id"]."', '$patient_id', '".$post_vars["registry_id"]."', '$insert_id', '".$_SESSION["userid"]."', sysdate())";
                        $result_ntp = mysql_query($sql_ntp);
                    }
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"].($get_vars["ntp_id"]?"&ntp_id=".$get_vars["ntp_id"]:""));
            }
            break;
            
        case "Save TB Symptomatic":
            print_r($_POST);
            break;
            
        case "Print Referral":
            break;
        }
    }

    function form_consult_assign_lab() {
    //
    // use this to import data from lab
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
        print "<b>".FTITLE_ASSIGN_NON_NTP_LABS."</b><br/><br/>";
        // query gets similar lab_id records from m_consult_lab
        // that are not in m_consult_ntp_labs_request
        print "<a name='assignlab_form'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."' name='form_assign_ntp_labs' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_NTP_REGISTRY_ID."</span><br> ";
        print "REGISTRY ID: <font color='red'>".module::pad_zero($get_vars["ntp_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".INSTR_ASSIGN_NON_NTP_LABS."</span><br/>";
        $sql = "SELECT s.request_id, l.lab_id, date_format(s.lab_timestamp, '%a %d %b %Y, %h:%i%p') lab_timestamp ".
               "FROM m_consult_lab l, m_consult_lab_sputum s  ".
               "left join m_consult_ntp_labs_request n ".
               "on n.request_id = l.request_id ".
               "where s.request_id = l.request_id and isnull(n.request_id) ";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($ntp_lab = mysql_fetch_array($result)) {
                    print "<input type='checkbox' name='labs[]' value='".$ntp_lab["request_id"]."' /><font color='red'>".module::pad_zero($ntp_lab["request_id"],7)."</font>: ".lab::get_lab_name($ntp_lab["lab_id"]);
                    print "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ntp_lab["lab_timestamp"]."<br/>";
                }
            } else {
                print "<font color='red'>No lab records for this category</font><br/>";
            }
        }
        print "</td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<input type='hidden' name='registry_id' value='".$get_vars["ntp_id"]."'/>";
            print "<br><input type='submit' value = 'Assign Lab Exam' class='textbox' name='submitntp' style='border: 1px solid #000000'><br>";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function form_ntp_symptomatic(){
      if(func_num_args()>0):
	$arg_list = func_get_args();
        $menu_id = $arg_list[0];
	$post_vars = $arg_list[1];
	$get_vars = $arg_list[2];
	$validuser = $arg_list[3];
	$isadmin = $arg_list[4];
      endif;
      
      $pxid = healthcenter::get_patient_id($_GET["consult_id"]);      
      
      echo "<a name='tb_symptomatic'>";

      echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ntp=$_GET[ntp]' name='form_symptomatic' method='POST'>";

      echo "<input type='hidden' value='$pxid' name='pxid'></input>";
      echo "<table>";
      echo "<tr><td>PATIENT IS TB SYMPTOMATIC?</td>";
      echo "<td><select name='sel_symp' size='1'>";
      echo "<option value=''>Please Specify</option>";
      echo "<option value='Y'>Yes</option>";
      echo "<option value='N'>No</option>";
      echo "</select></td><tr>";


      echo "<tr>";
      echo "<td colspan='2'>SPUTUM EXAMINATION (before treatment)</td>";            
      echo "</tr>";
      echo "<tr>";
      
      echo "<tr><td>1st</td><td>";      
      $this->show_sputum_test('sputum_diag1',$pxid);      
      echo "</td></tr>";
      
      echo "<tr><td>2nd</td><td>";      
      $this->show_sputum_test('sputum_diag2',$pxid);      
      echo "</td></tr>";                            

      echo "<tr><td>Date Referred for X-Ray</td>";
      echo "<td><input type='text' name='date_referred_xray' size='8'></input>&nbsp;";
      echo "<a href=\"javascript:show_calendar4('document.form_symptomatic.date_referred_xray', document.form_symptomatic.date_referred_xray.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a></input>";
      echo "</td>";
      echo "</tr>";

      echo "<tr><td>Date X-Ray Received</td>";
      echo "<td><input type='text' name='date_received_xray' size='8'></input>&nbsp;";
      echo "<a href=\"javascript:show_calendar4('document.form_symptomatic.date_received_xray', document.form_symptomatic.date_received_xray.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a></input>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr><td>X-ray Results</td>";
      echo "<td><select name='xray_result' size='1'>";
      echo "<option value=''>Select Result</option>";
      echo "<option value='P'>Positive</option>";
      echo "<option value='N'>Negative</option>";
      echo "</select></td>";
      echo "</tr>";
      
      echo "<tr><td>Additional Remarks</td>";
      echo "<td>";
      echo "<textarea cols='20' rows='5' name='symptomatic_remarks'></textarea>";
      echo "</td>";
      echo "</tr>";      
      
      echo "<tr><td>Link to NTP Treatment</td>";
      echo "<td>";
      
      
      $q_ntp = mysql_query("SELECT ntp_id,date_format(ntp_consult_date,'%m/%d/%Y') as consult_date,intensive_start_date,maintenance_start_date,course_end_flag FROM m_patient_ntp WHERE patient_id='$pxid' ORDER by consult_date DESC") or die("Cannot query: 1132".mysql_error());
      
      if(mysql_num_rows($q_ntp)==0):
          echo "<font color='red' size='2'>Patient has never underwent any NTP treatment.</font>";
      else:
          echo "<select name='select_ntp' size='1'>";
          echo "<option value=''>Select NTP Treatment</option>";
          
          while($r_ntp = mysql_fetch_array($q_ntp)){
              echo "<option value='$r_ntp[ntp_id]'>(#$r_ntp[ntp_id])$r_ntp[consult_date] (I: $r_ntp[intensive_start_date], M: $r_ntp[maintenance_start_date])</option>";
          }
          
          echo "</select>";
      endif;            
      echo "</td>";
      echo "</tr>";      
      
      
      echo "<tr align='center'>";
      echo "<td colspan='2'><input type='submit' name='submitntp' value='Save TB Symptomatic'></input>&nbsp;&nbsp;";
      echo "<input type='reset' value='Clear' name='btn_submit'></input>";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      
      echo "</form>";
    }

    function form_patient_ntp() {
    //
    // get ntp data for this visit1
    // of patient
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
        if ($get_vars["ntp_id"]) {
            $sql = "select patient_id, ntp_id, user_id, occupation_id, household_contacts, ".
                   "region_id, body_weight, bcg_scar, tb_class, ".
                   "previous_treatment_flag, previous_treatment_duration, previous_treatment_drugs, ".
                   "treatment_category_id, contact_person, outcome_id, patient_type_id, ".
                   "treatment_partner_id, course_end_flag ".
                   "from m_patient_ntp where ntp_id = '".$get_vars["ntp_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    $ntp = mysql_fetch_array($result);
                }
            }
        }
        print "<a name='visit1_form'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=VISIT1&ntp_id=".$get_vars["ntp_id"]."#prevtx' name='form_ntp_visit1' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_NTP_VISIT1_DATA."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='tinylight'>".INSTR_NTP_VISIT1_DATA."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<a name='prevtx'>";
        print "<span class='boxtitle'>".LBL_PREVIOUS_TREATMENT."</span><br> ";
        print "<input type='checkbox' name='previous_treatment_flag' onchange='this.form.submit();' ".(($ntp["previous_treatment_flag"]=="Y"||$post_vars["previous_treatment_flag"])?"checked":"")." value='1'/> Check previous treatment<br />";
        print "</td></tr>";
        if ($ntp["previous_treatment_flag"]=="Y" || $post_vars["previous_treatment_flag"]) {
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_PREVIOUS_TREATMENT_DURATION."</span><br>";
            print "<select name='previous_treatment_duration'>";
            print "<option value='M1' ".($ntp["previous_treatment_duration"]=="M1"?"selected":"").">More than 1 month</option>";
            print "<option value='L1' ".($ntp["previous_treatment_duration"]=="L1"?"selected":"").">Less than 1 month</option>";
            print "</select>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_PREVIOUS_TREATMENT_DRUGS."</span><br> ";
            print "<input type='checkbox' name='previous_treatment_drugs[]' value='E' ".(ereg("E", $ntp["previous_treatment_drugs"])?"checked":"")."> Ethambutol<br>";
            print "<input type='checkbox' name='previous_treatment_drugs[]' value='R' ".(ereg("R", $ntp["previous_treatment_drugs"])?"checked":"")."> Rifampicin<br>";
            print "<input type='checkbox' name='previous_treatment_drugs[]' value='H' ".(ereg("H", $ntp["previous_treatment_drugs"])?"checked":"")."> Isoniazid<br>";
            print "<input type='checkbox' name='previous_treatment_drugs[]' value='S' ".(ereg("S", $ntp["previous_treatment_drugs"])?"checked":"")."> Streptomycin<br>";
            print "<input type='checkbox' name='previous_treatment_drugs[]' value='Z' ".(ereg("Z", $ntp["previous_treatment_drugs"])?"checked":"")."> Pyrazinamide<br>";
            print "</td></tr>";
        }
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OCCUPATION."</span><br> ";
        print occupation::show_occupation($ntp["occupation_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_HOUSEHOLD_CONTACTS."</span><br> ";
        print "<select name='hh_contacts' class='textbox'>";
        print "<option value='0'>None</option>";
        for ($i=1; $i<20; $i++) {
            print "<option value='$i' ".($ntp["household_contacts"]==$i?"selected":"").">$i</option>";
        }
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BCG_SCAR."</span><br> ";
        print "<select name='bcg_scar'>";
        print "<option 'Y' ".($ntp["bcg_scar"]=="Y"?"selected":"").">Yes</option>";
        print "<option 'N' ".($ntp["bcg_scar"]=="N"?"selected":"").">No</option>";
        print "<option 'D' ".($ntp["bcg_scar"]=="D"?"selected":"").">Doubtful</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TB_CLASS."</span><br> ";
        print "<select name='tb_class'>";
        print "<option 'P' ".($ntp["tb_class"]=="P"?"selected":"").">Pulmonary</option>";
        print "<option 'E' ".($ntp["tb_class"]=="E"?"selected":"").">Extrapulmonary</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_REGION."</span><br> ";
        if (!$ntp["region_id"]) {
            $region = "NCR";
        } else {
            $region = $ntp["region_id"];
        }
        print region::show_region($region);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CONTACT_PERSON."</span><br> ";
        print "<input type='text' class='textbox' name='contact_person' value='".$ntp["contact_person"]."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PATIENT_TYPE."</span><br> ";
        if (!$ntp["patient_type_id"]) {
            $patient_type = "NEW";
        } else {
            $patient_type = $ntp["patient_type_id"];
        }
        print ntp::show_patient_type($patient_type);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TREATMENT_CAT."</span><br> ";
        print ntp::show_treatment_cat($ntp["treatment_category_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TREATMENT_PARTNER."</span><br> ";
        print ntp::show_treatment_partners($ntp["treatment_partner_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        if (!$ntp["outcome_id"]) {
            $outcome = "TX";
        } else {
            $outcome = $ntp["outcome_id"];
        }
        print "<span class='boxtitle'>".LBL_TREATMENT_OUTCOME."</span><br> ";
        print ntp::show_treatment_outcomes($outcome);
        print "<br/><small>".INSTR_TREATMENT_OUTCOME."</small><br/>";
        print "</td></tr>";
        print "<tr><td>";
        if ($get_vars["ntp_id"]) {
            print "<input type='hidden' name='ntp_id' value='".$get_vars["ntp_id"]."'/>";
            if ($_SESSION["priv_update"] || $_SESSION["isadmin"]) {
                if ($ntp["course_end_flag"]=="N" || $_SESSION["isadmin"]) {
                    print "<br><input type='submit' value = 'Update NTP Data' class='textbox' name='submitntp' style='border: 1px solid #000000'><br>";
                }
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Save NTP Data' class='textbox' name='submitntp' style='border: 1px solid #000000'><br>";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function form_intensive_ntp() {
    //
    // get ntp data for this consult
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
        print "<a name='intensive_form'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."' name='form_intensive' method='post'>";
        print "<tr valign='top'><td>";
        print "<input type='hidden' name='patient_id' value='$patient_id'/>";
        print "<b>".FTITLE_NTP_INTAKE_DATA_FORM."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<font color='red'><b>".LBL_INTENSIVE_PHASE."</b></font><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_NTP_REGISTRY_ID."</span><br> ";
        print "REGISTRY ID: <font color='red'>".module::pad_zero($get_vars["ntp_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TREATMENT_DATE."</span><br> ";
        print "<input type='text' size='10' class='textbox' name='treatment_date' value='$thisday' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_intensive.treatment_date', document.form_intensive.treatment_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INTAKE_FLAG."</span><br> ";
        print "<input type='checkbox' name='intake_flag' value='1'/> Check if medication taken on date above<br />";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INTAKE_REMARKS."</span><br> ";
        print "<textarea rows='3' cols='30' name='remarks' class='textbox'></textarea><br/>";
        print "</td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<input type='hidden' name='registry_id' value='".$get_vars["ntp_id"]."'/>";
            print "<br><input type='submit' value = 'Save Intensive Data' class='textbox' name='submitntp' style='border: 1px solid #000000'><br>";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function form_maintenance_ntp() {
    //
    // get ntp data for this consult
    // maintenance
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
        print "<a name='maintenance_form'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."' name='form_maintenance' method='post'>";
        print "<tr valign='top'><td>";
        print "<input type='hidden' name='patient_id' value='$patient_id'/>";
        print "<b>".FTITLE_NTP_COLLECTION_DATA_FORM."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<font color='red'><b>".LBL_MAINTENANCE_PHASE."</b></font><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_NTP_REGISTRY_ID."</span><br> ";
        print "REGISTRY ID: <font color='red'>".module::pad_zero($get_vars["ntp_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TREATMENT_DATE."</span><br> ";
        print "<input type='text' size='10' class='textbox' name='treatment_date' value='$thisday' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_maintenance.treatment_date', document.form_maintenance.treatment_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FOLLOWUP_DATE."</span><br> ";
        print "<input type='text' size='10' class='textbox' name='followup_date' value='$thisday' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_maintenance.followup_date', document.form_maintenance.followup_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>When should the patient come back? Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INTAKE_FLAG."</span><br> ";
        print "<input type='checkbox' name='intake_flag' value='1'/> Check if medication taken on the treatment date above<br />";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INTAKE_REMARKS."</span><br> ";
        print "<textarea rows='3' cols='30' name='remarks' class='textbox'></textarea><br/>";
        print "</td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<input type='hidden' name='registry_id' value='".$get_vars["ntp_id"]."'/>";
            print "<br><input type='submit' value = 'Save Maintenance Data' class='textbox' name='submitntp' style='border: 1px solid #000000'><br>";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function form_consult_ntp_lab() {
    //
    // get ntp data for this consult
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
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=LABS&ntp_id=".$get_vars["ntp_id"]."' name='form_vitalsigns' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_NTP_LAB_FORM."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_NTP_REGISTRY_ID."</span><br> ";
        print "REGISTRY ID: <font color='red'>".module::pad_zero($get_vars["ntp_id"],7)."</font><br/>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAB_EXAM."</span><br> ";
        print ntp::show_ntp_labs();
        print "</td></tr>";
        print "<tr><td><br />";
        if ($_SESSION["priv_add"]) {
            print "<input type='hidden' name='registry_id' value='".$get_vars["ntp_id"]."'/>";
            print "<input type='submit' value = 'Send Request' class='textbox' name='submitntp' style='border: 1px solid #000000'> ";
        }
        print "<br /></td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function _details_ntp() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<table width='300' border=0><tr><td>";
        print "<b>NTP RECORD</b><br/>";
        print "<span class='tinylight'>".INSTR_VIEW_REGISTRY_DATA.":</span><br/>";
        ntp::display_ntp_record($menu_id, $post_vars, $get_vars);
        print "<br/>";
        if ($get_vars["ntp_id"]) {
            print "<b>NTP FOLLOW-UPS - INTENSIVE</b><br/>";
            ntp::display_month_intensive($menu_id, $post_vars, $get_vars);
            if ($get_vars["ntp_id"] && $get_vars["txi_date"]) {
                //ntp::display_ntp_followups($menu_id, $post_vars, $get_vars);
                ntp::display_ntp_intensive_txdate($menu_id, $post_vars, $get_vars);
            }
            print "<br/>";
            print "<b>NTP FOLLOW-UPS - MAINTENANCE</b><br/>";
            ntp::display_month_maintenance($menu_id, $post_vars, $get_vars);
            if ($get_vars["ntp_id"] && $get_vars["txm_date"]) {
                //ntp::display_ntp_followups($menu_id, $post_vars, $get_vars);
                ntp::display_ntp_maintenance_txdate($menu_id, $post_vars, $get_vars);
            }
            print "<br/>";
            print "<b>NTP LABS</b><br/>";
            ntp::display_ntp_lab_requests($menu_id, $post_vars, $get_vars);
            print "<br/>";
            /*
            print "<b>IMPORTANT DATES</b><br/>";
            ntp::display_due_dates($menu_id, $post_vars, $get_vars);
            if ($get_vars["ntp_id"] && $get_vars["duedate"]) {
                //ntp::display_ntp_followups($menu_id, $post_vars, $get_vars);
                ntp::display_ntp_duedate_detail($menu_id, $post_vars, $get_vars);
            }
            print "<br/>";
            */
        }
        print "</td></tr></table>";
    }

    function display_ntp_record() {
    //
    // contents of table m_patient_ntp
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
        $sql_ntp = "select ntp_id, patient_id, date_format(ntp_consult_date, '%a %d %b %Y'), outcome_id ".
                   "from m_patient_ntp where patient_id = '$patient_id' order by ntp_consult_date desc";
        if ($result_ntp = mysql_query($sql_ntp)) {
            if (mysql_num_rows($result_ntp)) {
                while (list($nid, $pid, $regdate, $outcome) = mysql_fetch_array($result_ntp)) {
                    print "<img src='../images/arrow_redwhite' border='0'/> REGISTRY NO: <font color='red'>".module::pad_zero($nid,7)."</font> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=VISIT1&ntp_id=$nid'>$regdate</a> ";
                    print ($outcome=="TX"?"<font color='green'>OPEN</font>":"<font color='red'>CLOSED</font>");
                    print "<br/>";
                    if ($get_vars["ntp_id"] && $get_vars["ntp_id"]==$nid) {
                        ntp::display_ntp_record_details($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
    }

    function display_ntp_record_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // manage Delete here
        if ($post_vars["submitntp"] && $get_vars["ntp_id"]) {
            if ($post_vars["submitntp"]=="Delete") {
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    print $sql = "delete from m_patient_ntp where ntp_id = '".$get_vars["ntp_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=VISIT1");
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=VISIT1");
                    }
                }
            }
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $consult_date = healthcenter::get_consult_date($get_vars["consult_id"]);
        $sql = "select patient_id, date_format(ntp_consult_date, '%a %d %b %Y, %h:%i%p') ntp_consult_date, date_format(ntp_timestamp, '%a %d %b %Y, %h:%i%p') ts, ntp_id, user_id, ".
               "occupation_id, household_contacts, region_id, body_weight, bcg_scar, ".
               "previous_treatment_flag, previous_treatment_duration, previous_treatment_drugs, ".
               "patient_type_id, outcome_id, treatment_partner_id, treatment_category_id, contact_person, course_end_flag, ".
               "intensive_start_date, maintenance_start_date, treatment_end_date, ".
               "sputum1_date, sputum2_date, sputum3_date, ".
               "intensive_projected_end_date, maintenance_projected_end_date, ".
               "to_days('$consult_date') days_consult_date, ".
               "to_days(intensive_projected_end_date) days_proj_int_end, ".
               "to_days(maintenance_projected_end_date) days_proj_maint_end, ".
               "to_days(sputum1_date) days_sputum1_date, ".
               "to_days(sputum2_date) days_sputum2_date, ".
               "to_days(sputum3_date) days_sputum3_date ".
               "from m_patient_ntp ".
               "where ntp_id = '".$get_vars["ntp_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($ntpdata = mysql_fetch_array($result)) {
                    print "<table width='250' style='border: 1px dotted black'>";
                    print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=ntp&ntp=INTAKE&ntp_id=".$get_vars["ntp_id"]."'>";
                    print "<tr><td>";
                    print "<span class='tinylight'>";
                    print "Patient Name: ".strtoupper(patient::get_name($ntpdata["patient_id"]))."<br/>";
                    print "Registration Date: ".$ntpdata["ntp_consult_date"]."<br/>";
                    print "Last Update: ".$ntpdata["ts"]."<br/>";
                    print "Updated By: ".user::get_username($ntpdata["user_id"])."<br/>";
                    print "<hr size='1'/>";
                    print "IMPORTANT DATES:<br/>";
                    print "Start Intensive Phase: ".($ntpdata["intensive_start_date"]<>"0000-00-00"?$ntpdata["intensive_start_date"]:"NA")."<br/>";
                    print "Start Maintenance Phase: ".($ntpdata["maintenance_start_date"]<>"0000-00-00"?$ntpdata["maintenance_start_date"]:"NA")."<br/>";
                    print "End of Treatment: ".($ntpdata["maintenance_start_date"]<>"0000-00-00"?$ntpdata["maintenance_start_date"]:"NA")."<br/><br/>";
                    print "PROJECTED DATES:<br/>";
                    print "Proj End Intensive Phase: ".($ntpdata["intensive_projected_end_date"]=="0000-00-00"?"NA":($ntpdata["days_proj_int_end"]<=$ntpdata["days_consult_date"]?"<font color='red'>".$ntpdata["intensive_projected_end_date"]."</font>":$ntpdata["intensive_projected_end_date"]))."<br/>";
                    print "Proj End Maint Phase: ".($ntpdata["maintenance_projected_end_date"]=="0000-00-00"?"NA":($ntpdata["days_proj_maint_end"]<=$ntpdata["days_consult_date"]?"<font color='red'>".$ntpdata["maintenance_projected_end_date"]."</font>":$ntpdata["maintenance_projected_end_date"]))."<br/>";
                    if ($ntpdata["treatment_category"]==3) {
                        print "Sputum Exam #1 Date: ".($ntpdate["sputum1_date"]=="0000-00-00"?"NA":($ntpdata["days_sputum1_date"]<=$ntpdata["days_consult_date"]?"<font color='red'>".$ntpdata["sputum1_date"]."</font>":$ntpdata["sputum1_date"]))."<br/>";
                    } else {
                        print "Sputum Exam #1 Date: ".($ntpdate["sputum1_date"]=="0000-00-00"?"NA":($ntpdata["days_sputum1_date"]<=$ntpdata["days_consult_date"]?"<font color='red'>".$ntpdata["sputum1_date"]."</font>":$ntpdata["sputum1_date"]))."<br/>";
                        print "Sputum Exam #2 Date: ".($ntpdate["sputum2_date"]=="0000-00-00"?"NA":($ntpdata["days_sputum2_date"]<=$ntpdata["days_consult_date"]?"<font color='red'>".$ntpdata["sputum2_date"]."</font>":$ntpdata["sputum2_date"]))."<br/>";
                        print "Sputum Exam #3 Date: ".($ntpdate["sputum3_date"]=="0000-00-00"?"NA":($ntpdata["days_sputum3_date"]<=$ntpdata["days_consult_date"]?"<font color='red'>".$ntpdata["sputum3_date"]."</font>":$ntpdata["sputum3_date"]))."<br/>";
                    }
                    print "<hr size='1'/>";
                    print "Occupation: ".occupation::get_occupation_name($ntpdata["occupation_id"])."<br/>";
                    print "Contact Person: ".$ntpdata["contact_person"]."<br/>";
                    print "Region: ".region::get_region_name($ntpdata["region_id"])."<br/>";
                    print "<hr size='1'/>";
                    print "BCG Scar: ".($ntpdata["bcg_scar"]=="D"?"Doubtful":$ntpdata["bcg_scar"])."<br/>";
                    print "Household Contacts: ".$ntpdata["household_contacts"]." ".($ntpdata["household_contacts"]==1?"person":"persons")."<br/>";
                    print "Previous Treatment? ".$ntpdata["previous_treatment_flag"]."<br/>";
                    if ($ntpdata["previous_treatment_flag"]=="Y") {
                        print "<span class='tinylight'>";
                        print "&nbsp;&nbsp;Drugs: ".$ntpdata["previous_treatment_drugs"]."<br/>";
                        print "&nbsp;&nbsp;Duration: ".($ntpdata["previous_treatment_duration"]=="M1"?">1 month":"<1 month")."<br/>";
                        print "</span>";
                    }
                    print "Patient Type: ".ntp::get_patient_type($ntpdata["patient_type_id"])."<br/>";
                    print "Tx Category: ".ntp::get_treatment_cat($ntpdata["treatment_category_id"])."<br/>";
                    print "Tx Outcome: ".ntp::get_treatment_outcome($ntpdata["outcome_id"])."<br/>";
                    print "Tx Partner: ".ntp::get_partner_name($ntpdata["treatment_partner_id"])."<br/>";
                    if ($_SESSION["priv_delete"]) {
                        if ($ntpdata["course_end_flag"]<>"Y") {
                            print "<input type='submit' class='tinylight' name='submitntp' value='Delete' style='border: 1px solid black'/>";
                        }
                    }
                    print "</span>";
                    print "</td></tr>";
                    print "</form>";
                    print "</table>";
                }
            }
        }
    }

    function display_ntp_lab_requests() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // manage delete here
        print "<a name='lab_requests'>";
        if ($get_vars["delete_request_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_ntp_labs_request where request_id = '".$get_vars["delete_request_id"]."'";
                if ($result = mysql_query($sql)) {
                    $sql_lab = "delete from m_consult_lab where request_id = '".$get_vars["delete_request_id"]."'";
                    $result_lab = mysql_query($sql_lab);
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                }
            }
        }
        $sql = "select n.ntp_id, n.request_id, n.request_timestamp, l.lab_name, l.lab_module  ".
               "from m_consult_ntp_labs_request n, m_consult_lab c, m_lib_laboratory l ".
               "where n.request_id = c.request_id and c.lab_id = l.lab_id ".
               "and n.ntp_id = '".$get_vars["ntp_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($nid, $rid, $nts, $name, $mod) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif'/> ";
                    print "<font color='red'>".module::pad_zero($rid,7)."</font>: <a href='".$_SERVER["PHPS-SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=LABS&module=$mod&request_id=$rid#$mod'>$name</a> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&delete_request_id=$rid#lab_requests'><img src='../images/delete.png' border='0' /></a>";
                    print "<br/>";
                }
            } else {
                print "<font color='red'>No recorded requests.</font><br/>";
            }
        }
    }

    function display_due_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<span class='tinylight'><img src='../images/star.gif'/> means no follow up.</span><br/>";
        $sql = "select ntp_id, treatment_due_date, if(treatment_actual_date='0000-00-00','Y','N') followup_flag, date_format(treatment_due_date, '%a %d %b %Y') nicedate ".
               "from m_consult_ntp_due_dates where ntp_id = '".$get_vars["ntp_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $duedate, $noffup_flag, $nicedate) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif'/> ";
                    print "<a href='".$_SERVER["PHPS-SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&duedate=$duedate#d$duedate'>$nicedate</a> ".($noffup_flag=="Y"?"<img src='../images/star.gif' border='0'/>":"")."<br/>";
                }
            } else {
                print "<font color='red'>No recorded due dates.</font><br/>";
            }
        }
    }

    function display_ntp_duedate_detail() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select patient_id, user_id, treatment_due_date, treatment_actual_date, remarks ".
               "from m_consult_ntp_due_dates ".
               "where ntp_id = '".$get_vars["ntp_id"]."' and treatment_due_date = '".$get_vars["duedate"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $duedate = mysql_fetch_array($result);
                print "<a name='d".$get_vars["duedate"]."'>";
                print "<table width='250' style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "Due Date: ".$duedate["treatment_due_date"]."<br/>";
                if ($duedate["treatment_actual_date"]<>"0000-00-00") {
                    print "Actual Date: ".$duedate["treatment_actual_date"]."<br/>";
                    print "Remarks:<br/>".nl2br(stripslashes($duedate["remarks"]))."<br/>";
                } else {
                    print "<form method='post' action='".$_SERVER["PHPS-SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&duedate=".$get_vars["duedate"]."#d".$get_vars["duedate"]."' name='form_due_date'>";
                    print "<input type='hidden' name='ntp_id' value='".$get_vars["ntp_id"]."'/>";
                    print "<span class='boxtitle'>".LBL_ACTUAL_DATE."</span><br/>";
                    print "<input type='text' size='10' class='textbox' name='treatment_actual_date' value='' style='border: 1px solid #000000'> ";
                    print "<a href=\"javascript:show_calendar4('document.form_due_date.treatment_actual_date', document.form_due_date.treatment_actual_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
                    print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br><br/>";
                    print "<span class='boxtitle'>".LBL_REMARKS."</span><br/>";
                    print "<textarea rows='3' cols='30' name='remarks' class='tinylight' style='border: 1px solid black'></textarea><br/><br/>";
                    print "<input type='submit' name='submitntp' value='Add Follow-up Date' class='tinylight' style='border: 1px solid black'/>";
                    print "</form>";
                }
                print "</span>";
                print "</td></tr></table>";
            }
        }
    }

    function display_ntp_intensive_txdate() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<a name='i_detail'>";
        // manage Delete here
        if ($post_vars["submitntp"] && $get_vars["ntp_id"]) {
            if ($post_vars["submitntp"]=="Delete Intensive Entry") {
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_consult_ntp_intake_intensive ".
                           "where ntp_id = '".$get_vars["ntp_id"]."' and ".
                           "treatment_date = '".$get_vars["txi_date"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                    }
                }
            }
        }
        $sql = "select patient_id, user_id, treatment_date, intake_flag, remarks, ".
               "treatment_week, treatment_month ".
               "from m_consult_ntp_intake_intensive ".
               "where ntp_id = '".$get_vars["ntp_id"]."' and treatment_date = '".$get_vars["txi_date"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $txdate = mysql_fetch_array($result);
                print "<form method='post' action='".$_SERVER["PHPS-SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&txi_date=".$txdate["treatment_date"]."#i_detail' name='form_intensive_txdate'>";
                print "<table width='200' style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "<b>TREATMENT DATE ".$txdate["treatment_date"]."</b><br/>";
                print "Entered by: ".user::get_username($txdate["user_id"])."<br/>";
                print "Treatment Week: ".$txdate["treatment_week"]."<br/>";
                print "Treatment Month: ".$txdate["treatment_month"]."<br/>";
                print "Intake: ".($txdate["intake_flag"]=="Y"?"Yes":"No")."<br/>";
                print "Remarks:<br/>".($txdate["remarks"]?nl2br(stripslashes($txdate["remarks"])):"None")."<br/>";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitntp' value='Delete Intensive Entry' class='tinylight' style='border:1px solid black' />";
                }
                print "</span>";
                print "</td></tr></table>";
                print "</form>";
            }
        }
    }

    function display_ntp_maintenance_txdate() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<a name='m_detail'>";
        // manage Delete here
        if ($post_vars["submitntp"] && $get_vars["ntp_id"]) {
            if ($post_vars["submitntp"]=="Delete Maintenance Entry") {
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_consult_ntp_collection_maintenance ".
                           "where ntp_id = '".$get_vars["ntp_id"]."' and ".
                           "treatment_date = '".$get_vars["txm_date"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]);
                    }
                }
            }
        }
        $sql = "select patient_id, user_id, treatment_date, intake_flag, remarks, ".
               "treatment_week, treatment_month ".
               "from m_consult_ntp_collection_maintenance ".
               "where ntp_id = '".$get_vars["ntp_id"]."' and treatment_date = '".$get_vars["txm_date"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $txdate = mysql_fetch_array($result);
                print "<form method='post' action='".$_SERVER["PHPS-SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&txm_date=".$txdate["treatment_date"]."#m_detail' name='form_maintenance_txdate'>";
                print "<table width='200' style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "<b>TREATMENT DATE ".$txdate["treatment_date"]."</b><br/>";
                print "Entered by: ".user::get_username($txdate["user_id"])."<br/>";
                print "Treatment Week: ".$txdate["treatment_week"]."<br/>";
                print "Treatment Month: ".$txdate["treatment_month"]."<br/>";
                print "Intake: ".($txdate["intake_flag"]=="Y"?"Yes":"No")."<br/>";
                print "Remarks:<br/>".nl2br(stripslashes($txdate["remarks"]))."<br/>";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitntp' value='Delete Maintenance Entry' class='tinylight' style='border:1px solid black' />";
                }
                print "</span>";
                print "</td></tr></table>";
                print "</form>";
            }
        }
    }

    function display_ntp_followups() {
    //
    // contents of table m_consult_ntp_intake_intensive
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
        $sql_ntp = "select ntp_id, patient_id, date_format(treatment_date, '%a %d %b %Y'), intake_flag ".
                   "from m_consult_ntp_intake_intensive ".
                   "where ntp_id = '".$get_vars["ntp_id"]."' ".
                   "order by treatment_date desc";
        if ($result_ntp = mysql_query($sql_ntp)) {
            if (mysql_num_rows($result_ntp)) {
                print "<table width='300' bgcolor='#FFFFCC' style='border: 1px dotted black'><tr><td>";
                while (list($nid, $pid, $regdate, $intake) = mysql_fetch_array($result_ntp)) {
                    if ($prev_nid<>$nid) {
                        print "REGISTRY NO: $nid INTENSIVE<br/>";
                    }
                    print "<img src='../images/arrow_redwhite' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=".$get_vars["ntp"]."&ffup_ntp_id=$nid'>$regdate</a> ".($intake=="Y"?"<font color='blue'>success</font>":"<font color='red'>fail</font>")."<br/>";
                    if ($get_vars["ffup_ntp_id"] && $get_vars["ffup_ntp_id"]==$nid) {
                        ntp::display_course($menu_id, $post_vars, $get_vars);
                    }
                    $prev_nid = $nid;
                }
                print "</td></tr></table>";
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
    }

    function display_month_intensive() {
    //
    // contents of table m_consult_ntp_intake_intensive
    // displayed as success and fail days
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
        $sql_ntp = "select ntp_id, patient_id, intake_flag, treatment_date, ".
                   "date_format(treatment_date, '%d') treatment_day, ".
                   "date_format(treatment_date, '%b') treatment_month, ".
                   "date_format(treatment_date, '%Y') treatment_year ".
                   "from m_consult_ntp_intake_intensive ".
                   "where ntp_id = '".$get_vars["ntp_id"]."' ".
                   "order by treatment_date";
        if ($result = mysql_query($sql_ntp)) 
          {
          if (mysql_num_rows($result)) 
            {
            $week=1;
            $d=0;
            while (list($nid, $pid, $flag, $txdate, $day, $month, $year) = mysql_fetch_array($result)) 
              {
              $d++;
              if ($d==8) 
                {
                $d=1;
                $week++;
                }
              $day = "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&txi_date=$txdate#i_detail'>$day</a>";
              if ($prev_yr_month<>$year.$month) 
                {
                $col = 1;
                if (isset($cal_string)) 
                  {
                  $cal_string .= "<br/>";
                  }
                $cal_string .= "<b><font color='#669900'>$month $year</font></b><br/>";
                $cal_string .= " <font color='red'><b>WK$week</b></font> ";
                $cal_string .= ($flag=="Y"?"$day":"<font color=blue><b>$day</b></font>")."|";
                } 
              else 
                {
                if ($prev_week <> $week) 
                  {
                  $col=1;
                  $cal_string .= " <br><font color='red'><b>WK$week</b></font> ";
                  }
                $cal_string .= ($flag=="Y"?"$day":"<font color=blue><b>$day</b></font>")."|";
                }
              $col++;
              if ($col==8) 
                {
                $col = 1;
                }
              $prev_yr_month = $year.$month;
              $prev_week = $week;
              }
              print $cal_string."<br/><br/>";
            } 
          else 
            {
            print "<font color='red'>No records</font><br/>";
            }
          }
      }

    function display_month_maintenance() {
    //
    // contents of table m_consult_ntp_intake_intensive
    // displayed as success and fail days
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
        $sql_ntp = "select ntp_id, patient_id, intake_flag, treatment_date, ".
                   "date_format(treatment_date, '%d') treatment_day, ".
                   "date_format(treatment_date, '%b') treatment_month, ".
                   "date_format(treatment_date, '%Y') treatment_year ".
                   "from m_consult_ntp_collection_maintenance ".
                   "where ntp_id = '".$get_vars["ntp_id"]."' ".
                   "order by treatment_date";
        if ($result = mysql_query($sql_ntp)) {
            if (mysql_num_rows($result)) {
                $week=1;
                $d=0;
                while (list($nid, $pid, $flag, $txdate, $day, $month, $year) = mysql_fetch_array($result)) {
                    $d++;
                    if ($d==8) {
                        $d=0;
                        $week++;
                    }
                    $day = "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=ntp&ntp=".$get_vars["ntp"]."&ntp_id=".$get_vars["ntp_id"]."&txm_date=$txdate#m_detail'>$day</a>";
                    if ($prev_yr_month<>$year.$month) {
                        $col = 1;
                        if (isset($cal_string)) {
                            $cal_string .= "<br/>";
                        }
                        $cal_string .= "<b><font color='#669900'>$month $year</font></b><br/>";
                        $cal_string .= " <font color='red'><b>WK$week</b></font> ";
                        $cal_string .= ($flag=="Y"?"$day":"<font color=blue><b>$day</b></font>")."|";
                    } else {
                        if ($prev_week <> $week) {
                            $col=1;
                            $cal_string .= " <br><font color='red'><b>WK$week</b></font> ";
                        }
                        $cal_string .= ($flag=="Y"?"$day":"<font color=blue><b>$day</b></font>")."|";
                    }
                    $col++;
                    if ($col==8) {
                        $col = 1;
                    }
                    $prev_yr_month = $year.$month;
                    $prev_week = $week;
                }
                print $cal_string."<br/><br/>";
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
    }

    function show_registry_id() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        //
        // list down registry numbers that have not been officially ended
        // course_end_flag = 'N'
        //
        $sql = "select ntp_id, date_format(ntp_timestamp, '%a %d %b %Y') ts from m_patient_ntp ".
               "where course_end_flag = 'N' and patient_id = '$patient_id' order by ntp_timestamp desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='registry_id' class='textbox'>";
                while (list($registry_id, $ts) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$registry_id'>$registry_id - $ts</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            } else {
                print "<font color='red'>No registry records</font><br/>";
            }
        }
    }

    function get_month_start_ntp() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ntp_id = $arg_list[0];
        }
        $sql = "select month(registration_date) from m_patient_ntp where ntp_id = '$ntp_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($month) = mysql_fetch_array($result);
                return $month;
            }
        }
    }

    function get_treatment_month() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $startmonth = $arg_list[0];
            $date_today= $arg_list[1];
        }
        $sql = "select month('".$date_today."') - $startmonth";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($txmonth) = mysql_fetch_array($result);
                return $txmonth;
            }
        }
    }

    function get_start_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ntp_id = $arg_list[0];
        }
        $sql = "select date_format(ntp_timestamp, '%Y-%m-%d') ts from m_patient_ntp where ntp_id = '$ntp_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                return $date;
            }
        }
    }

    function get_intensive_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ntp_id = $arg_list[0];
        }
        $sql = "select intensive_start_date from m_patient_ntp where ntp_id = '$ntp_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                return $date;
            }
        }
    }

    function get_maintenance_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ntp_id = $arg_list[0];
        }
        $sql = "select maintenance_start_date from m_patient_ntp where ntp_id = '$ntp_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                return $date;
            }
        }
    }

    function get_treatment_regimen() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $ntp_id = $arg_list[0];
        }
        $sql = "select treatment_category_id from m_patient_ntp where ntp_id = '$ntp_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($cat_id) = mysql_fetch_array($result);
                return $cat_id;
            }
        }
    }

    // ---------------------- LAB METHODS ---------------------------

    function show_treatment_cat() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
        }
        $sql = "select cat_id, cat_name from m_lib_ntp_treatment_category order by cat_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='treatment_category' class='textbox'>";
                $ret_val .= "<option value=''>Select Type</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($cat_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_treatment_cat() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
        }
        $sql = "select cat_name from m_lib_ntp_treatment_category ".
               "where cat_id = '$cat_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function _ntp_appointments() {
    //
    // main submodule for appointment
    // calls form_ntp_appointment()
    //       display_ntp_appointment()
    //       process_ntp_appointment()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $n = new ntp;
        if ($post_vars["submitappt"]) {
            $n->process_ntp_appointment($menu_id, $post_vars, $get_vars);
        }
        $n->display_ntp_appointment($menu_id, $post_vars, $get_vars);
        $n->form_ntp_appointment($menu_id, $post_vars, $get_vars);
    }

    function form_ntp_appointment() {
    //
    // called by _ntp_appointment()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_ntp_appointment' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_NTP_APPOINTMENT_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPOINTMENT_SELECTION."</span><br> ";
        print appointment::show_appointment();
        print "</td></tr>";
        print "<tr><td><br>";
        if ($_SESSION["priv_delete"]) {
            print "<input type='submit' value = 'Delete Appointment' class='textbox' name='submitappt' style='border: 1px solid #000000'> ";
        }
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Add Appointment' class='textbox' name='submitappt' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function display_ntp_appointment() {
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
        print "<span class='library'>".FTITLE_NTP_APPOINTMENTS."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select n.appointment_id, l.appointment_name ".
               "from m_lib_appointment l, m_lib_ntp_appointment n ".
               "where l.appointment_id = n.appointment_id ".
               "order by l.appointment_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td>$name</td></tr>";
                }
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
        print "</table><br>";
    }

    function process_ntp_appointment() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitappt"]) {
                switch($post_vars["submitappt"]) {
                case "Add Appointment":
                    print $sql = "insert into m_lib_ntp_appointment (appointment_id) ".
                           "values ('".strtoupper($post_vars["appointment"])."')";
                    if ($result = mysql_query($sql)) {
                        //header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Appointment":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ntp_appointment where appointment_id = '".$post_vars["appointment"]."'";
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

    function _ntp_treatment_category() {
    //
    // main submodule for patient type
    // calls form_patient_type()
    //       display_patient_type()
    //       process_patient_type()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $n = new ntp;
        if ($post_vars["submitcat"]) {
            $n->process_treatment_category($menu_id, $post_vars, $get_vars);
        }
        $n->display_treatment_category($menu_id, $post_vars, $get_vars);
        $n->form_treatment_category($menu_id, $post_vars, $get_vars);
    }

    function form_treatment_category() {
    //
    // called by _ntp_patient_type()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["cat_id"]) {
                $sql = "select cat_id, cat_name, cat_details ".
                       "from m_lib_ntp_treatment_category where cat_id = '".$get_vars["cat_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $cat = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_treatment_category' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_TREATMENT_CATEGORY_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CATEGORY_ID."</span><br> ";
        if ($get_vars["cat_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='cat_id' value='".$type["cat_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='cat_id' value='".($cat["cat_id"]?$cat["cat_id"]:$post_vars["cat_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CATEGORY_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='cat_name' value='".($cat["cat_name"]?$cat["cat_name"]:$post_vars["cat_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CATEGORY_DETAILS."</span><br> ";
        print "<textarea class='textbox' rows='4' cols='35' name='cat_details' style='border: 1px solid #000000'>".stripslashes(($cat["cat_details"]?$cat["cat_details"]:$post_vars["cat_details"]))."</textarea><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["cat_id"]) {
            print "<input type='hidden' name='cat_id' value='".$get_vars["cat_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_treatment_category() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitcat"]) {
            if ($post_vars["cat_id"] && $post_vars["cat_name"]) {
                switch($post_vars["submitcat"]) {
                case "Add Category":
                    $sql = "insert into m_lib_ntp_treatment_category (cat_id, cat_name, cat_details) ".
                           "values ('".strtoupper($post_vars["cat_id"])."', '".$post_vars["cat_name"]."', '".addslashes($post_vars["cat_details"])."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Category":
                    $sql = "update m_lib_ntp_treatment_category set ".
                           "cat_name = '".$post_vars["cat_name"]."', ".
                           "cat_details = '".addslashes($post_vars["cat_details"])."' ".
                           "where cat_id = '".$post_vars["cat_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Category":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ntp_treatment_category where cat_id = '".$post_vars["cat_id"]."'";
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

    function display_treatment_category() {
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
        print "<span class='library'>".FTITLE_NTP_TREATMENT_CATEGORY."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_DETAILS."</b></td></tr>";
        $sql = "select cat_id, cat_name, cat_details from m_lib_ntp_treatment_category order by cat_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $details) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&cat_id=$id'>$name</a></td><td>".nl2br($details)."</td></tr>";
                }
            }
        }
        print "</table><br>";
    }


    function _ntp_patient_type() {
    //
    // main submodule for patient type
    // calls form_patient_type()
    //       display_patient_type()
    //       process_patient_type()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $n = new ntp;
        if ($post_vars["submittype"]) {
            $n->process_patient_type($menu_id, $post_vars, $get_vars);
        }
        $n->display_patient_type($menu_id, $post_vars, $get_vars);
        $n->form_patient_type($menu_id, $post_vars, $get_vars);
    }

    function show_patient_type() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $type_id = $arg_list[0];
        }
        $sql = "select type_id, type_name from m_lib_ntp_patient_type order by type_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='patient_type' class='textbox'>";
                $ret_val .= "<option value=''>Select Type</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($type_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_patient_type() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $type_id = $arg_list[0];
        }
        $sql = "select type_name from m_lib_ntp_patient_type where type_id = '$type_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_patient_type() {
    //
    // called by _ntp_patient_type()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["type_id"]) {
                $sql = "select type_id, type_name ".
                       "from m_lib_ntp_patient_type where type_id = '".$get_vars["type_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $type = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_patient_type' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_PATIENT_TYPE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TYPE_ID."</span><br> ";
        if ($get_vars["type_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='type_id' value='".$type["type_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='type_id' value='".($type["type_id"]?$type["type_id"]:$post_vars["type_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TYPE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='type_name' value='".($type["type_name"]?$type["type_name"]:$post_vars["type_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["type_id"]) {
            print "<input type='hidden' name='type_id' value='".$get_vars["type_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Type' class='textbox' name='submittype' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Type' class='textbox' name='submittype' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Type' class='textbox' name='submittype' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_patient_type() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submittype"]) {
            if ($post_vars["type_id"] && $post_vars["type_name"]) {
                switch($post_vars["submittype"]) {
                case "Add Type":
                    $sql = "insert into m_lib_ntp_patient_type (type_id, type_name) ".
                           "values ('".strtoupper($post_vars["type_id"])."', '".$post_vars["type_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Type":
                    $sql = "update m_lib_ntp_patient_type set ".
                           "type_name = '".$post_vars["type_name"]."' ".
                           "where type_id = '".$post_vars["type_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Type":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ntp_patient_type where type_id = '".$post_vars["type_id"]."'";
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

    function display_patient_type() {
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
        print "<span class='library'>".FTITLE_NTP_PATIENT_TYPE."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select type_id, type_name from m_lib_ntp_patient_type order by type_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&type_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _ntp_outcomes() {
    //
    // main submodule for patient outcomes
    // calls form_outcome()
    //       display_outcome()
    //       process_outcome()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $n = new ntp;
        if ($post_vars["submitoutcome"]) {
            $n->process_outcome($menu_id, $post_vars, $get_vars);
        }
        $n->display_outcome($menu_id, $post_vars, $get_vars);
        $n->form_outcome($menu_id, $post_vars, $get_vars);
    }

    function show_treatment_outcomes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $outcome_id = $arg_list[0];
        }
        $sql = "select outcome_id, outcome_name from m_lib_ntp_treatment_outcome order by outcome_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='treatment_outcome' class='textbox'>";
                $ret_val .= "<option value=''>Select Outcome</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($outcome_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_treatment_outcome() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $outcome_id = $arg_list[0];
        }
        $sql = "select outcome_name from m_lib_ntp_treatment_outcome where outcome_id = '$outcome_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_outcome() {
    //
    // called by _ntp_outcomes()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["outcome_id"]) {
                $sql = "select outcome_id, outcome_name ".
                       "from m_lib_ntp_treatment_outcome where outcome_id = '".$get_vars["outcome_id"]."'";
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
        print "<span class='library'>".FTITLE_TREATMENT_OUTCOME_FORM."</span><br><br>";
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
                    $sql = "insert into m_lib_ntp_treatment_outcome (outcome_id, outcome_name) ".
                           "values ('".strtoupper($post_vars["outcome_id"])."', '".$post_vars["outcome_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Outcome":
                    $sql = "update m_lib_ntp_treatment_outcome set ".
                           "outcome_name = '".$post_vars["outcome_name"]."' ".
                           "where outcome_id = '".$post_vars["outcome_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Outcome":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ntp_treatment_outcome where outcome_id = '".$post_vars["outcome_id"]."'";
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
        print "<span class='library'>".FTITLE_NTP_TREATMENT_OUTCOME."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select outcome_id, outcome_name from m_lib_ntp_treatment_outcome order by outcome_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&outcome_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _ntp_partners() {
    //
    // main submodule for treatment partners
    // calls form_partner()
    //       display_partner()
    //       process_partner()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $n = new ntp;
        if ($post_vars["submitpartner"]) {
            $n->process_partner($menu_id, $post_vars, $get_vars);
        }
        $n->display_partner($menu_id, $post_vars, $get_vars);
        $n->form_partner($menu_id, $post_vars, $get_vars);
    }

    function show_treatment_partners() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $partner_id = $arg_list[0];
        }
        $sql = "select partner_id, partner_name from m_lib_ntp_treatment_partner order by partner_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='treatment_partner' class='textbox'>";
                $ret_val .= "<option value=''>Select Partner</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($partner_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_partner_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $partner_id = $arg_list[0];
        }
        $sql = "select partner_name from m_lib_ntp_treatment_partner where partner_id = '$partner_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_partner() {
    //
    // called by _ntp_partners()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["partner_id"]) {
                $sql = "select partner_id, partner_name ".
                       "from m_lib_ntp_treatment_partner where partner_id = '".$get_vars["partner_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $partner = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_partner' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_TREATMENT_PARTNER_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PARTNER_ID."</span><br> ";
        if ($get_vars["partner_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='partner_id' value='".$get_vars["partner_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='partner_id' value='".($partner["partner_id"]?$partner["partner_id"]:$post_vars["partner_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PARTNER_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='partner_name' value='".($partner["partner_name"]?$partner["partner_name"]:$post_vars["partner_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["partner_id"]) {
            print "<input type='hidden' name='partner_id' value='".$get_vars["partner_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Partner' class='textbox' name='submitpartner' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Partner' class='textbox' name='submitpartner' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Partner' class='textbox' name='submitpartner' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_partner() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitpartner"]) {
            if ($post_vars["partner_id"] && $post_vars["partner_name"]) {
                switch($post_vars["submitpartner"]) {
                case "Add Partner":
                    $sql = "insert into m_lib_ntp_treatment_partner (partner_id, partner_name) ".
                           "values ('".strtoupper($post_vars["partner_id"])."', '".$post_vars["partner_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Partner":
                    $sql = "update m_lib_ntp_treatment_partner set ".
                           "partner_name = '".$post_vars["partner_name"]."' ".
                           "where partner_id = '".$post_vars["partner_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Partner":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ntp_treatment_partner where partner_id = '".$post_vars["partner_id"]."'";
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

    function display_partner() {
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
        print "<span class='library'>".FTITLE_NTP_TREATMENT_PARTNER."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select partner_id, partner_name from m_lib_ntp_treatment_partner order by partner_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&partner_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }
    
    function show_sputum_test($form_name,$pxid){
        $q_sputum = mysql_query("SELECT request_id,sp1_collection_date,sp2_collection_date,sp3_collection_date,sp1_reading,sp2_reading,sp3_reading from m_consult_lab_sputum WHERE patient_id='$pxid' ORDER BY sp1_collection_date DESC,sp2_collection_date DESC, sp3_collection_date DESC") or die("Cannot query 3052:".mysql_error());
        
        if(mysql_num_rows($q_sputum)!=0):
            echo "<select name='$form_name' size='1'>";
            echo "<option value=''>Select Sputum Exam</option>";
            
            while($r_sputum=mysql_fetch_array($q_sputum)){
                echo "<option value='$r_sputum[request_id]'>(1) $r_sputum[sp1_collection_date]($r_sputum[sp1_reading]), (2) $r_sputum[sp2_collection_date]($r_sputum[sp2_reading]), (3) $r_sputum[sp3_collection_date]($r_sputum[sp3_reading])</option>";
            }            
            
            echo "</select>";
        else:
            echo"<font color='red' size='2'><b>No record for sputum exam. Please record SPUTUM RESULTS <a href='$_SERVER[SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=LABS'>here</a></font>";        
        endif;

        
    }

// end of class
}
?>
