<?
class mc extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function mc() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD / darth_ali';
        $this->version = "0.96-".date("Y-m-d");
        $this->module = "mc";
        $this->description = "CHITS Library - Maternal Care";
        // 0.4: debugged
        // 0.5 added prenatal import
        // 0.6 added end_pregnancy_flag to m_patient_mc
        //     added threshold detection for BP, important dates, age
        // 0.7 added mc followups
        // 0.8 added JNC7 hypertension staging
        // 0.9 added postpartum visit table, m_consult_mc_postpartum
        //     added home visit to services
        // 0.91 fixed interface issues, postpartum week problem

		// 0.93-0.95 (08/31/2008) allowed dynamic entry of data. removed HBMR checkbox. auto-ordering of visit details
	 	// 0.96       removed visit_sequence as primary key in m_consult_mc_prenatal. added prenatal_id and assigned it as a primary				  key
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
        module::set_lang("LBL_FINAL_OBSTETRIC_SCORE", "english", "FINAL OB SCORE", "Y");
        module::set_lang("INSTR_POSTPARTUM_RECORD", "english", "FILLING UP DELIVERY DATE CREATES FAMILY PLANNING REGISTRY RECORD. CHECKING END PREGNANCY FLAG CLOSES THIS RECORD.", "Y");
        module::set_lang("LBL_BIRTH_ATTENDANT", "english", "BIRTH ATTENDANT", "Y");
        module::set_lang("LBL_PRENATAL_VISIT_DATE", "english", "PRENATAL VISIT DATE", "Y");
        module::set_lang("LBL_PRENATAL_VISIT_SEQUENCE", "english", "PRENATAL VISIT SEQUENCE", "Y");
        module::set_lang("FTITLE_VACCINE_DATA_IMPORT", "english", "IMPORT VACCINE DATA", "Y");
        module::set_lang("LBL_VACCINATIONS_RECEIVED", "english", "VACCINATIONS RECEIVED", "Y");
        module::set_lang("LBL_END_PREGNANCY_FLAG", "english", "END THIS PREGNANCY RECORD", "Y");
        module::set_lang("INSTR_END_PREGNANCY_FLAG", "english", "Check this to close this record", "Y");
        module::set_lang("FTITLE_MC_POSTPARTUM_DATA_FORM", "english", "POSTPARTUM DATA FORM", "Y");
        module::set_lang("FTITLE_MC_POSTPARTUM_VISIT_FORM", "english", "POSTPARTUM VISIT FORM", "Y");
        module::set_lang("LBL_POSTPARTUM_VISIT_DATE", "english", "POSTPARTUM VISIT DATE", "Y");
        module::set_lang("LBL_VISIT_TYPE", "english", "TYPE OF VISIT", "Y");
        module::set_lang("LBL_POSTPARTUM_EVENTS", "english", "POSTPARTUM EVENTS", "Y");
        module::set_lang("LBL_BREASTFEEDING", "english", "IS THIS PATIENT BREASTFEEDING", "Y");
        module::set_lang("LBL_FAMILY_PLANNING", "english", "HAS THIS PATIENT SELECTED FP METHOD", "Y");
        module::set_lang("FTITLE_POSTPARTUM_RECORDS", "english", "POSTPARTUM RECORDS", "Y");

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
        // menu entries
        module::set_menu($this->module, "MC Follow-ups", "CONSULTS", "_mc_followup");
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

        // track labs requests
        module::execsql("CREATE TABLE `m_consult_mc_labs_request` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`mc_id` float NOT NULL default '0',".
            "`request_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`request_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`request_id`,`mc_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `mc_id` (`mc_id`),".
            "KEY `request_id` (`request_id`),".
            "CONSTRAINT `m_consult_mc_labs_request_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_labs_request_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `m_consult_lab` (`request_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_labs_request_ibfk_3` FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc` (`mc_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        // main registry table
        module::execsql("CREATE TABLE `m_patient_mc` (".
            "`mc_id` float NOT NULL auto_increment,".
            "`patient_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`mc_timestamp` timestamp(14) NOT NULL,".
            "`child_patient_id` float NOT NULL default '0',".
            "`mc_consult_date` datetime NOT NULL default '0000-00-00 00:00:00',".
            "`patient_lmp` date NOT NULL default '0000-00-00',".
            "`patient_edc` date NOT NULL default '0000-00-00',".
            "`trimester1_date` date NOT NULL default '0000-00-00',".
            "`trimester2_date` date NOT NULL default '0000-00-00',".
            "`trimester3_date` date NOT NULL default '0000-00-00',".
            "`postpartum_date` date NOT NULL default '0000-00-00',".
            "`delivery_date` date default '0000-00-00',".
            "`delivery_type` char(1) NOT NULL default 'N',".
            "`delivery_location` varchar(10) NOT NULL default '',".
            "`obscore_gp` varchar(10) NOT NULL default '',".
            "`obscore_fpal` varchar(10) NOT NULL default '',".
            "`outcome_id` varchar(10) NOT NULL default '',".
            "`healthy_baby` char(1) default 'Y',".
            "`birthweight` float NOT NULL default '0',".
            "`birthmode` varchar(5) NOT NULL default '',".
            "`breastfeeding_asap` char(1) NOT NULL default 'Y',".
            "`user_id` int(11) NOT NULL default '0',".
            "`blood_type` char(3) NOT NULL default '',".
            "`patient_age` float NOT NULL default '0',".
            "`patient_height` float NOT NULL default '0',".
            "`end_pregnancy_flag` char(1) NOT NULL default 'N',".
            "PRIMARY KEY  (`mc_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "CONSTRAINT `m_patient_mc_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_patient_mc_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        // tracks prenatal data
        // data_type identifies whether record data comes from
        // another facility HBMR: INT=internal, EXT=external
        module::execsql("CREATE TABLE `m_consult_mc_prenatal` (".
            "`mc_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`prenatal_timestamp` timestamp(14) NOT NULL,".
            "`prenatal_date` datetime NOT NULL default '0000-00-00 00:00:00',".
            "`user_id` float NOT NULL default '0',".
            "`aog_weeks` float NOT NULL default '0',".
            "`trimester` smallint(6) NOT NULL default '0',".
            "`visit_sequence` smallint(6) NOT NULL default '0',".
            "`patient_weight` float NOT NULL default '0',".
            "`blood_pressure_systolic` int(11) NOT NULL default '0',".
            "`blood_pressure_diastolic` int(11) NOT NULL default '0',".
            "`fundic_height` int(11) NOT NULL default '0',".
            "`presentation` varchar(10) NOT NULL default '',".
            "`fhr` int(11) NOT NULL default '0',".
            "`fhr_location` char(3) NOT NULL default '',".
            "`data_type` char(3) NOT NULL default 'INT',".
            "PRIMARY KEY  (`mc_id`,`consult_id`, `visit_sequence`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_mc` (`mc_id`),".
            "CONSTRAINT `m_consult_mc_prenatal_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_prenatal_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_prenatal_ibfk_3` FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc` (`mc_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        // tracks postpartum visit
        // visit type can be HOME or CLINIC
        module::execsql("CREATE TABLE `m_consult_mc_postpartum` (".
            "`mc_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`postpartum_timestamp` timestamp(14) NOT NULL,".
            "`postpartum_date` datetime NOT NULL default '0000-00-00 00:00:00',".
            "`user_id` float NOT NULL default '0',".
            "`postpartum_week` smallint(6) NOT NULL default '0',".
            "`visit_sequence` smallint(6) NOT NULL default '0',".
            "`visit_type` varchar(10) NOT NULL default 'CLINIC',".
            "`breastfeeding_flag` char(1) NOT NULL default 'Y',".
            "`family_planning_flag` char(1) NOT NULL default 'Y',".
            "`fever_flag` char(1) NOT NULL default 'N',".
            "`vaginal_infection_flag` char(1) NOT NULL default 'N',".
            "`vaginal_bleeding_flag` char(1) NOT NULL default 'N',".
            "`pallor_flag` char(1) NOT NULL default 'N',".
            "`cord_ok_flag` char(1) NOT NULL default 'Y',".
            "`blood_pressure_systolic` int(11) NOT NULL default '0',".
            "`blood_pressure_diastolic` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`mc_id`,`consult_id`,`visit_sequence`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_mc` (`mc_id`),".
            "CONSTRAINT `m_consult_mc_postpartum_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_postpartum_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_postpartum_ibfk_3` FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc` (`mc_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        // tracks risk factors for visits
        module::execsql("CREATE TABLE `m_consult_mc_visit_risk` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`mc_id` float NOT NULL default '0',".
            "`visit_risk_id` int(11) NOT NULL default '0',".
            "`risk_timestamp` timestamp(14) NOT NULL,".
            "`user_id` float NOT NULL default '0',".
            "PRIMARY KEY  (`consult_id`,`visit_risk_id`,`mc_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_pregnancy` (`mc_id`),".
            "CONSTRAINT `m_consult_mc_visit_risk_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_visit_risk_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_mc_visit_risk_ibfk_3` FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc` (`mc_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        // what services given each consult
        module::execsql("CREATE TABLE `m_consult_mc_services` (".
            "`mc_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`mc_timestamp` timestamp(14) NOT NULL,".
            "`visit_type` varchar(10) NOT NULL default 'CLINIC',".
            "`service_id` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`mc_id`,`consult_id`,`service_id`,`mc_timestamp`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_user` (`user_id`), ".
            "KEY `key_consult` (`consult_id`), ".
            "KEY `key_mc` (`mc_id`), ".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ON DELETE CASCADE, ".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient`(`patient_id`) ON DELETE CASCADE, ".
            "FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc`(`mc_id`) ON DELETE CASCADE ".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_consult_mc_vaccine` (".
            "`mc_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`user_id` float NOT NULL default '0',".
            "`vaccine_timestamp` timestamp(14) NOT NULL,".
            "`actual_vaccine_date` date NOT NULL default '0000-00-00',".
            "`adr_flag` char(1) NOT NULL default 'N',".
            "`vaccine_id` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`mc_id`,`consult_id`,`vaccine_id`, `vaccine_timestamp`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_vaccine` (`vaccine_id`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_mc` (`mc_id`), ".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ON DELETE CASCADE, ".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient`(`patient_id`) ON DELETE CASCADE, ".
            "FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc`(`mc_id`) ON DELETE CASCADE ".
            ") TYPE=InnoDB; ");

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

	//create m_lib_mc_delivery_location
	module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_mc_delivery_location` (
		  `delivery_id` varchar(10) NOT NULL,`delivery_name` text NOT NULL,  PRIMARY KEY  (`delivery_id`)
		   ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

	module::execsql("INSERT INTO `m_lib_mc_delivery_location` (`delivery_id`, `delivery_name`) VALUES
			('HOME', 'Home'),('HOSP', 'Hospital'),('LYIN', 'Private Lying-In Clinic'),
			('HC', 'Health Center'),('BHS', 'Barangay Health Station'),('OTHERS', 'Others');");

    }

    function drop_tables() {

        module::execsql("SET foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_consult_mc_labs_request`;");
        module::execsql("DROP TABLE `m_consult_mc_prenatal`;");
        module::execsql("DROP TABLE `m_consult_mc_postpartum`;");
        module::execsql("DROP TABLE `m_consult_mc_visit_risk`;");
        module::execsql("DROP TABLE `m_consult_mc_services`;");
        module::execsql("DROP TABLE `m_consult_mc_vaccine`;");
        module::execsql("DROP TABLE `m_patient_mc`;");
        module::execsql("DROP TABLE `m_lib_mc_risk_factors`;");
        module::execsql("DROP TABLE `m_lib_mc_outcome`;");
        module::execsql("DROP TABLE `m_lib_mc_vaccines`;");
        module::execsql("DROP TABLE `m_lib_mc_services`;");
        module::execsql("DROP TABLE `m_lib_mc_birth_attendant`;");
        module::execsql("SET foreign_key_checks=1;");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _mc_followup() {
    //
    // main submodule for mc followups
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
    }

    function _consult_mc() {
    //
    // main submodule for mc consults
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

        $m->mc_menu($menu_id, $post_vars, $get_vars);
	     
	if ($post_vars["submitmc"]) {
            $m->process_mc($menu_id, $post_vars, $get_vars);
        }

        switch ($get_vars["mc"]) {
        case "VISIT1":
            if ($post_vars["submitmc"]=="Update Postpartum") {
                $m->form_mc_postpartum($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            } else {
                $m->form_mc_firstvisit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            }
            break;
        case "PREN":
            $m->form_mc_prenatal($menu_id, $post_vars, $get_vars, $validuser, $isadmin);

            break;
        case "POSTP":
            if ($post_vars["submitmc"]=="Update Visit 1") {
                $m->form_mc_firstvisit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            } else {
                $m->form_mc_postpartum($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
                // make sure there is a delivery date because date calculations
                //  are done based on it
                if ($m->get_delivery_date($get_vars["mc_id"])<>"0000-00-00") {
                    $m->form_mc_postpartum_visit($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
                }
            }
            break;
        case "SVC":
            $m->form_mc_services($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            $m->form_vaccine_data_import($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
            break;
		
		case "RF":
			$m->form_risk_factors();
			$m->display_risk_factors($get_vars);
			break;
        default:
			break;
        }
    }

    function mc_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["mc"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=VISIT1");
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=VISIT1".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["mc"]=="VISIT1"?"<b>VISIT1</b>":"VISIT1"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=PREN".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["mc"]=="PREN"?"<b>PRENATAL</b>":"PRENATAL"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=POSTP".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["mc"]=="POSTP"?"<b>POSTPARTUM</b>":"POSTPARTUM"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=SVC".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["mc"]=="SVC"?"<b>SERVICES</b>":"SERVICES"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=RF".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["mc"]=="RF"?"<b>RISK FACTORS</b>":"RISK FACTORS"))."</a>";

        print "</td></tr></table><br/>";
    }

    function registry_record_exists() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        // there may be several pregnancies
        // and we only need to get the latest
        // that has no delivery date on it!
        $sql = "select mc_id ".
               "from m_patient_mc ".
               "where patient_id = '$patient_id' and ".
               "end_pregnancy_flag = 'N' ".
               "order by mc_timestamp desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($id) = mysql_fetch_array($result);
                return $id;
            }
        }
    }

    function process_mc() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitmc"]) {
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $patient_age = patient::get_age($patient_id);
            if ($post_vars["lmp_date"]) {
                list($month, $day, $year) = explode("/", $post_vars["lmp_date"]);
                $lmp_date = "$year-".str_pad($month,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);
            }
            switch($post_vars["submitmc"]) {

			case "Save Risk Factor":

				if(empty($_POST[risk_date])):
					echo "<script language='Javascript'>";
					echo "window.alert('Please supply date of when the risk factor was detected.')";
					echo "</script>";
				
				elseif(empty($get_vars[mc_id])):
					echo "<script language='Javascript'>";
					echo "window.alert('Open a maternal record first!')";
					echo "</script>";

				else:					
					list($month,$date,$year) = explode('/',$_POST[risk_date]);
					$risk_date = $year.'-'.$month.'-'.$date;

					$q_risk = mysql_query("SELECT visit_risk_id FROM m_consult_mc_visit_risk WHERE mc_id='$get_vars[mc_id]' AND visit_risk_id='$_POST[risk_code]' AND date_detected='$risk_date'") or die("Cannot query: 582");					

					if(mysql_num_rows($q_risk)==0):
												
						$insert_risk = mysql_query("INSERT INTO m_consult_mc_visit_risk SET consult_id='$get_vars[consult_id]',patient_id='$patient_id',mc_id='$get_vars[mc_id]',visit_risk_id='$_POST[risk_code]',date_detected='$risk_date',risk_timestamp=sysdate(),user_id='$_SESSION[userid]'") or die(mysql_error());

						if($insert_risk):
							echo "<font color='red'>Record successfully added.</font>";
						endif;

					else:
						echo "<font color='red'>Risk code and date already exists.</font>";
					endif;
				endif;


				break;

			case "Delete Risk Factor";				

				if(isset($_POST[riskcode]) && $_POST[confirm_del]==1):
						
					while(list($risk_key,$risk_value)=each($_POST[riskcode])){
						list($riskid,$mcid,$datedetected) = explode('*',$risk_value);
							
						$del_risk = mysql_query("DELETE FROM m_consult_mc_visit_risk WHERE mc_id='$mcid' AND visit_risk_id='$riskid' AND date_detected='$datedetected'") or die("Cannot query: 615");
					}

					if($del_risk):
						echo "<font color='red'>Risk factor was successfully deleted.</font>";
						header("Location: $_SERVER[PHP_SELF]?page=CONSULTS&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=DETAILS&module=mc&mc=RF&mc_id=$get_vars[mc_id]");						
					endif;	
				endif;
				
				break;
				
			case "Save Prenatal Remarks":
				$update_prenatal = mysql_query("UPDATE m_patient_mc SET prenatal_remarks='$_POST[prenatal_remarks]' WHERE mc_id='$get_vars[mc_id]'") or die("Cannot query: 634"); 

				if($update_prenatal):
					header("Location: $_SERVER[PHP_SELF]?page=CONSULTS&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=DETAILS&module=mc&mc=PREN&mc_id=$get_vars[mc_id]#prerem");
				endif;

				break;

			case "Save Postpartum Remarks":
				$update_post = mysql_query("UPDATE m_patient_mc SET postpartum_remarks='$_POST[postpartum_remarks]' WHERE mc_id='$get_vars[mc_id]'") or die("Cannot query: 645");


				if($update_post):
					header("Location: $_SERVER[PHP_SELF]?page=CONSULTS&menu_id=$get_vars[menu_id]&consult_id=$get_vars[consult_id]&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=$get_vars[mc_id]#postrem");					
				endif;
				
				break;


            case "Save Postpartum Visit":
                if ($post_vars["visit_date"] && $post_vars["visit_type"] &&
                    $post_vars["patient_systolic"] && $post_vars["patient_diastolic"]) {
		
		    print_r($post_vars);
		    $wk_count = mc::get_pp_week($post_vars["mc_id"],$post_vars["visit_date"]);		    

		    if($wk_count>0):
	                    list($month, $day, $year) = explode("/", $post_vars["visit_date"]);
        	            $visit_date = "$year-".str_pad($month,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);
        	            $breastfeeding_flag = ($post_vars["breastfeeding_flag"]?"Y":"N");
        	            $family_planning_flag = ($post_vars["family_planning_flag"]?"Y":"N");
        	            $fever_flag = ($post_vars["fever_flag"]?"Y":"N");
        	            $vaginal_infection_flag = ($post_vars["vaginal_infection_flag"]?"Y":"N");
        	            $vaginal_bleeding_flag = ($post_vars["vaginal_bleeding_flag"]?"Y":"N");
        	            $pallor_flag = ($post_vars["pallor_flag"]?"Y":"N");
        	            $cord_ok_flag = ($post_vars["cord_ok_flag"]?"Y":"N");
	
        	            $sql = "insert into m_consult_mc_postpartum (mc_id, consult_id, ".
        	                   "patient_id, postpartum_timestamp, postpartum_date, user_id, ".
        	                   "postpartum_week, visit_sequence, visit_type, ".
        	                   "breastfeeding_flag, family_planning_flag, fever_flag, ".
        	                   "vaginal_infection_flag, vaginal_bleeding_flag, ".
        	                   "pallor_flag, cord_ok_flag, blood_pressure_systolic, ".
        	                   "blood_pressure_diastolic) ".
        	                   "values ('".$post_vars["mc_id"]."', '".$get_vars["consult_id"]."', ".
        	                   "'$patient_id', sysdate(), '$visit_date', '".$_SESSION["userid"]."', ".
        	                   "'".$wk_count."', '".$post_vars["visit_sequence"]."', ".
        	                   "'".$post_vars["visit_type"]."', '$breastfeeding_flag', ".
        	                   "'$family_planning_flag', '$fever_flag', '$vaginal_infection_flag', ".
        	                   "'$vaginal_bleeding_flag', '$pallor_flag', '$cord_ok_flag', ".
        	                   "'".$post_vars["patient_systolic"]."', '".$post_vars["patient_diastolic"]."')"; 
                    if ($result = mysql_query($sql)):
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=POSTP&mc_id=".$get_vars["mc_id"]);
                    endif;

		    else:
			echo "<font color='red'>Postpartum visit date should be after date of delivery.</font>";
		    endif;
                }
		else{
			echo "<font color='red'>Postpartum visit not saved. Please check visit date, type of visit and systolic/diastolic.</font>";
		}
                break;


		case "Update Postpartum Data Form":
			break;

		case "Update Postpartum":
				
                if ($post_vars["visit_date"] && $post_vars["visit_type"] &&
                    $post_vars["patient_systolic"] && $post_vars["patient_diastolic"]) {
		    
		    $wk_count = mc::get_pp_week($get_vars["mc_id"],$post_vars["visit_date"]);

                    list($month, $day, $year) = explode("/", $post_vars["visit_date"]);
                    $visit_date = "$year-".str_pad($month,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);
                    $breastfeeding_flag = ($post_vars["breastfeeding_flag"]?"Y":"N");
                    $family_planning_flag = ($post_vars["family_planning_flag"]?"Y":"N");
                    $fever_flag = ($post_vars["fever_flag"]?"Y":"N");
                    $vaginal_infection_flag = ($post_vars["vaginal_infection_flag"]?"Y":"N");
                    $vaginal_bleeding_flag = ($post_vars["vaginal_bleeding_flag"]?"Y":"N");
                    $pallor_flag = ($post_vars["pallor_flag"]?"Y":"N");
                    $cord_ok_flag = ($post_vars["cord_ok_flag"]?"Y":"N");

		    if($wk_count>0):

                    $sql = "update m_consult_mc_postpartum set ".
                           "postpartum_date = '$visit_date', ".
			   "postpartum_week = '$wk_count', ".
                           "visit_type = '".$post_vars["visit_type"]."', ".
                           "blood_pressure_systolic = '".$post_vars["patient_systolic"]."', ".
                           "blood_pressure_diastolic = '".$post_vars["patient_diastolic"]."', ".
                           "breastfeeding_flag = '$breastfeeding_flag', ".
                           "family_planning_flag = '$family_planning_flag', ".
                           "fever_flag = '$fever_flag', ".
                           "vaginal_infection_flag = '$vaginal_infection_flag', ".
                           "vaginal_bleeding_flag = '$vaginal_bleeding_flag', ".
                           "pallor_flag = '$pallor_flag', ".
                           "cord_ok_flag = '$cord_ok_flag' ".
                           "where mc_id = '".$get_vars["mc_id"]."' and ".
                           "visit_sequence = '".$post_vars["visit_sequence"]."' and ".
                           "consult_id = '".$get_vars["consult_id"]."'";

    		     $result = mysql_query($sql) or die("Cannot query: 721");
                    
		     if ($result):
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=".$get_vars["mc_id"]."&ppvisitseq=".$get_vars["ppvisitseq"]);				
                     endif;
		    else:
			echo "<font color='red'>Postpartum visit date should be after date of delivery.</font>";			
		    endif;
                }
		else{
			echo "<font color='red'>Postpartum visit not updated. Please check visit date, type of visit and systolic/diastolic.</font>";
		}
                break;
            case "Delete Postpartum":
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_consult_mc_postpartum ".
                           "where mc_id = '".$get_vars["mc_id"]."' and ".
                           "consult_id = '".$get_vars["consult_id"]."' and ".
                           "visit_sequence = '".$post_vars["visit_sequence"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=".$get_vars["mc_id"]);
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=".$get_vars["mc_id"]);
                    }
                }
                break;
            case "Import Data":
                if ($post_vars["vaccines"]) {
                    // remember, no need to update m_consult_vaccine
                    // since these data are coming from that source!
                    foreach($post_vars["vaccines"] as $key=>$value) {
                        // get complete data from m_consult_vaccine
                        $sql_vaccine = "select patient_id, consult_id, vaccine_id, actual_vaccine_date, adr_flag, vaccine_timestamp ".
                                       "from m_consult_vaccine ".
                                       "where patient_id = '".$post_vars["patient_id"]."' and ".
                                       "vaccine_id = '$value'";
                        if ($result_vaccine = mysql_query($sql_vaccine)) {
                            if (mysql_num_rows($result_vaccine)) {
                                while ($vaccine = mysql_fetch_array($result_vaccine)) {
                                    //$sql_import = "insert into m_consult_mc_vaccine (mc_id, consult_id, user_id, patient_id, vaccine_timestamp, actual_vaccine_date, vaccine_id) "."values ('".$post_vars["mc_id"]."', '".$vaccine["consult_id"]."', '".$_SESSION["userid"]."', '".$vaccine["patient_id"]."', sysdate(), '".$vaccine["actual_vaccine_date"]."', '".$vaccine["vaccine_id"]."')";
									
									$sql_import = "INSERT INTO m_consult_mc_vaccine SET mc_id='$post_vars[mc_id]',consult_id='$vaccine[consult_id]',user_id='$_SESSION[userid]',patient_id='$vaccine[patient_id]',vaccine_timestamp='$vaccine[vaccine_timestamp]',actual_vaccine_date='$vaccine[actual_vaccine_date]',vaccine_id='$vaccine[vaccine_id]'";
// updating of imported vaccine!
                                    $result_import = mysql_query($sql_import) or die("Cannot query: 669");
                                }
                            }
                        }
                    }
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=SVC&mc_id=".$get_vars["mc_id"]);
                break;
            case "Update Services":
                if ($post_vars["services"]) {
                    foreach($post_vars["services"] as $key=>$value) {
												
                        $sql = "insert into m_consult_mc_services (mc_id, consult_id, user_id, patient_id, mc_timestamp, service_id, visit_type) ".
                               "values ('".$post_vars["mc_id"]."', '".$get_vars["consult_id"]."', '".$_SESSION["userid"]."', '$patient_id', sysdate(), '$value', '".$post_vars["visit_type"]."')";
                        $result = mysql_query($sql);
                    }
                }
                if ($post_vars["vaccines"]) {
                    foreach($post_vars["vaccines"] as $key=>$value) {
                        $sql = "insert into m_consult_mc_vaccine (mc_id, consult_id, user_id, patient_id, vaccine_timestamp, actual_vaccine_date, vaccine_id) ".
                               "values ('".$post_vars["mc_id"]."', '".$get_vars["consult_id"]."', '".$_SESSION["userid"]."', '$patient_id', sysdate(), sysdate(), '$value')";
                        $result = mysql_query($sql);

                        // update patient vaccine record also
                        $sql_vaccine = "insert into m_consult_vaccine (consult_id, patient_id, vaccine_id, user_id, vaccine_timestamp, actual_vaccine_date, source_module) ".
                                       "values ('".$get_vars["consult_id"]."', '$patient_id', '$value', '".$_SESSION["userid"]."', sysdate(), sysdate(), 'mc')";
                        $result_vaccine = mysql_query($sql_vaccine);
                    }
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=SVC&mc_id=".$post_vars["mc_id"]);
                break;
            case "Save Data":
                if ($post_vars["lmp_date"] && $post_vars["obscore_gp"] && $post_vars["obscore_fpal"] && $post_vars["patient_height"]) {
                    $sql = "insert into m_patient_mc (patient_id, consult_id, ".
                           "mc_timestamp, mc_consult_date, patient_lmp, patient_edc, ".
                           "trimester1_date, trimester2_date, trimester3_date, postpartum_date, ".
                           "obscore_gp, obscore_fpal, ".
                           "user_id, blood_type, patient_age, patient_height) ".
                           "values ('$patient_id', '".$get_vars["consult_id"]."', ".
                           "sysdate(), sysdate(), '$lmp_date', from_days(to_days('$lmp_date')+280), ".
                           "from_days(to_days('$lmp_date')+93.3), from_days(to_days('$lmp_date')+186.67), from_days(to_days('$lmp_date')+280), from_days(to_days('$lmp_date')+322), ".
                           "'".$post_vars["obscore_gp"]."', '".$post_vars["obscore_fpal"]."', '".$_SESSION["userid"]."', ".
                           "'".$post_vars["bloodtype"]."', '$patient_age', ".
                           "'".$post_vars["patient_height"]."')";

                    if ($result = mysql_query($sql)) {
                        $insert_id = mysql_insert_id();
						/*$tt_vacc = array('TT1','TT2','TT3','TT4','TT5');
						
						for($j=0;$j<count($tt_vacc);$j++){
						
						$check_TT = mysql_query("SELECT actual_vaccine_date, vaccine_id, consult_id,vaccine_timestamp,adr_flag FROM m_consult_vaccine WHERE patient_id='$patient_id' AND vaccine_id='$tt_vacc[$j]'") or die("Cannot query: 713");
						
						if(mysql_num_rows($check_TT)>0):
							while($r_check_TT=mysql_fetch_array($check_TT))
							{
								$q_mc_vacc = mysql_query("SELECT consult_id,patient_id,mc_id FROM m_consult_mc_vaccine WHERE consult_id='$r_check_TT[consult_id]' AND vaccine_timestamp='$r_check_TT[vaccine_timestamp]' AND vaccine_id='$r_check_TT[vaccine_id]'") or die(mysql_error());

								if(mysql_num_rows($q_mc_vacc)==0):
									$insert_vacc = mysql_query("INSERT INTO m_consult_mc_vaccine SET mc_id='$insert_id',consult_id='$r_check_TT[consult_id]',patient_id='$patient_id',user_id='$_SESSION[userid]',vaccine_timestamp='$r_check_TT[vaccine_timestamp]',actual_vaccine_date='$r_check_TT[actual_vaccine_date]',adr_flag='$r_check_TT[adr_flag]',vaccine_id='$r_check_TT[vaccine_id]'") or die("Cannot query: 725");

									$update_to_mc = mysql_query("UPDATE SET m_consult_vaccine SET source_module='mc' WHERE patient_id='$patient_id' AND consult_id='$r_check_TT[consult_id]' AND vaccine_id='$r_check_TT[vaccine_id]' AND vaccine_timestamp='$r_check_TT[vaccine_timestamp]'") or die(mysql_error());

								else:
									echo 'test1';
								endif;
							}

						else:
							echo 'test2';

						endif;

						}*/

						$date_ngayon = date('Y-m-d');
                        foreach($post_vars["risk"] as $key=>$value) {

                            $sql_risk = "insert into m_consult_mc_visit_risk (consult_id , ".
                                        "patient_id, mc_id, visit_risk_id, risk_timestamp,date_detected ".
                                        "user_id) values ('".$get_vars["consult_id"]."', ".
                                        "'$patient_id', '$insert_id', '$value', ".
                                        "sysdate(),$date_ngayon '".$_SESSION["userid"]."')";
                            $result_risk = mysql_query($sql_risk);
                        }
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=VISIT1&mc_id=$insert_id");
                    }
                } else {
                    print "<font color='red'>Incomplete entries.</font><br/>";
                }
                break;
            case "Update Data":
                if ($post_vars["lmp_date"] && $post_vars["obscore_gp"] && $post_vars["obscore_fpal"] && $post_vars["patient_height"]) {
                    $sql = "update m_patient_mc set ".
                           "patient_lmp = '$lmp_date', ".
                           "patient_edc = from_days(to_days('$lmp_date')+280), ".
                           "trimester1_date = from_days(to_days('$lmp_date')+93.3), ".
                           "trimester2_date = from_days(to_days('$lmp_date')+186.67), ".
                           "trimester3_date = from_days(to_days('$lmp_date')+280), ".
                           "postpartum_date = from_days(to_days('$lmp_date')+322), ".
                           "mc_timestamp = sysdate(), ".
                           "obscore_gp = '".$post_vars["obscore_gp"]."', ".
                           "obscore_fpal = '".$post_vars["obscore_fpal"]."', ".
                           "patient_height = '".$post_vars["patient_height"]."', ".
                           "blood_type = '".$post_vars["bloodtype"]."' ".
                           "where mc_id = '".$post_vars["mc_id"]."'";
                    if ($result = mysql_query($sql)) {
                        // delete risk factors first
                        $sql_delete = "delete from m_consult_mc_visit_risk ".
                                      "where consult_id = '".$get_vars["consult_id"]."' and mc_id = '".$post_vars["mc_id"]."'";
                        $result_delete = mysql_query($sql_delete);
                        // ...then update from form
						$date_ngayon = date('Y-m-d');
                        foreach($post_vars["risk"] as $key=>$value) {
                            $sql_risk = "insert into m_consult_mc_visit_risk (consult_id , ".
                                        "patient_id, mc_id, visit_risk_id, risk_timestamp,date_detected,".
                                        "user_id) values ('".$get_vars["consult_id"]."', ".
                                        "'$patient_id', '".$post_vars["mc_id"]."', '$value', ".
                                        "sysdate(),$date_ngayon, '".$_SESSION["userid"]."')";
                            $result_risk = mysql_query($sql_risk);
                        }
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=VISIT1".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));
                    }
                } else {
                    print "<font color='red'>Incomplete entries.</font><br/>";
                }
                break;
            case "Delete Data":
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_patient_mc where mc_id = '".$post_vars["mc_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=VISIT1".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=VISIT1".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));
                    }
                }
                break;
            case "Save Prenatal Data":
				$exists = mc::check_visit_date($post_vars["visit_date"],$post_vars[mc_id]);

                if ($post_vars["patient_systolic"] && $post_vars["patient_diastolic"] &&
                    $post_vars["patient_weight"] && $post_vars["visit_date"] && $exists!=1) {
								
                    //if ($post_vars["data_import_flag"]) {
                        list($month, $day, $year) = explode("/", $post_vars["visit_date"]);
                        $visit_date = "$year-".str_pad($month,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);
                        $trimester = mc::get_trimester($post_vars["mc_id"], $visit_date);
                        list($aog_weeks, $days) = mc::get_aog($post_vars["mc_id"], $visit_date);
                        $aog_total = $aog_weeks + ($days/7);
                        $data_type = "EXT";
                    //} else {
                    /*    $visit_date = healthcenter::get_consult_date($get_vars["consult_id"]);
                        $trimester = mc::get_trimester($post_vars["mc_id"], $visit_date);
                        $trimester = $post_vars["trimester"];
                        $aog_total = $post_vars["aog_weeks"];
                        $data_type = "INT";
				     */
                    //}
                    $sql = "insert into m_consult_mc_prenatal (mc_id, consult_id, patient_id, prenatal_timestamp, ".
                           "prenatal_date, user_id, aog_weeks, trimester, visit_sequence, patient_weight, ".
                           "blood_pressure_systolic, blood_pressure_diastolic, fhr, fhr_location, ".
                           "fundic_height, presentation, data_type) values ('".$post_vars["mc_id"]."', ".
                           "'".$get_vars["consult_id"]."', '$patient_id', '$visit_date', ".
                           "'$visit_date', '".$_SESSION["userid"]."', '$aog_total', ".
                           "'$trimester', '".$post_vars["visit_sequence"]."', ".
                           "'".$post_vars["patient_weight"]."', '".$post_vars["patient_systolic"]."', ".
                           "'".$post_vars["patient_diastolic"]."', '".$post_vars["fhr"]."', ".
                           "'".$post_vars["fhr_location"]."', '".$post_vars["fundic_height"]."', ".
                           "'".$post_vars["presentation"]."', '$data_type')";					
		
		    $result = mysql_query($sql) or die("Cannot query: 840");
		    mc::reorder_prenatal_visits($post_vars["mc_id"]);
                    if ($result) {		

                        // do not update for visit 1
                        if ($post_vars["visit_sequence"]>1) {
                            // delete risk factors first but only the M flagged
                            // caution use this only with MySQL version 4.+
                            $sql_delete = "delete from m_consult_mc_visit_risk ".
                                          "using m_consult_mc_visit_risk, m_lib_mc_risk_factors ".
                                          "where m_consult_mc_visit_risk.consult_id = '".$get_vars["consult_id"]."' ".
                                          "and m_consult_mc_visit_risk.mc_id = '".$post_vars["mc_id"]."' and ".
                                          "m_consult_mc_visit_risk.visit_risk_id = m_lib_mc_risk_factors.risk_id and ".
                                          "m_lib_mc_risk_factors.monitor_flag = 'Y'";
                            $result_delete = mysql_query($sql_delete);
                            // ...then update from form
                            foreach($post_vars["risk"] as $key=>$value) {

                                /*$sql_risk = "insert into m_consult_mc_visit_risk (consult_id , ".
                                            "patient_id, mc_id, visit_risk_id, risk_timestamp, ".
                                            "user_id) values ('".$get_vars["consult_id"]."', ".
                                            "'$patient_id', '".$post_vars["mc_id"]."', '$value', ".
                                            "sysdate(), '".$_SESSION["userid"]."')";

								 */
								$date_ngayon = date('Y-m-d');
								$sql_risk = "insert into m_consult_mc_visit_risk (consult_id , ".
                                            "patient_id, mc_id, visit_risk_id, risk_timestamp,date_detected,".
                                            "user_id) values ('".$get_vars["consult_id"]."', ".
                                            "'$patient_id', '".$post_vars["mc_id"]."', '$value', ".
                                            "sysdate(),$date_ngayon,'".$_SESSION["userid"]."')";		
                            }
                        }

                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=PREN".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));
                    }
                }
                break;
            case "Update Prenatal":
				//print_r($post_vars);
				$exists = mc::check_visit_date($post_vars["visit_date"],$post_vars[mc_id]);
				
                if ($post_vars["patient_systolic"] && $post_vars["patient_diastolic"] &&
                    $post_vars["patient_weight"] && $post_vars["fundic_height"] && $post_vars["fhr"] &&
                    $post_vars["fhr_location"] && $post_vars["presentation"] && $post_vars["visit_date"]) {
					
					list($month, $day, $year) = explode("/", $post_vars["visit_date"]);									
					$visit_date = "$year-".str_pad($month,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);					
					$trimester = mc::get_trimester($post_vars["mc_id"], $visit_date);
										

                    $sql = "update m_consult_mc_prenatal set ".
                           "fundic_height = '".$post_vars["fundic_height"]."', ".
                           "fhr = '".$post_vars["fhr"]."', ".
						   "prenatal_date = '".$visit_date."', ".
                           "fhr_location = '".$post_vars["fhr_location"]."', ".
						   "trimester = '".$trimester."', ".
                           "presentation = '".$post_vars["presentation"]."', ".
						   "patient_weight = '".$post_vars["patient_weight"]."', ".
						   "blood_pressure_systolic = '".$post_vars["patient_systolic"]."', ".
						   "blood_pressure_diastolic = '".$post_vars["patient_diastolic"]."' ".
                           "where mc_id = '".$post_vars["mc_id"]."' and visit_sequence = '".$post_vars["visit_sequence"]."'";

					$result = mysql_query($sql) or die("Cannot update: 855");
                    
					if ($result) {

                        // do not update for visit 1
                        if ($post_vars["visit_sequence"]>1) {
                            // delete risk factors first but only the M flagged
                            // caution use this only with MySQL version 4.+
                            /*$sql_delete = "delete from m_consult_mc_visit_risk ".
                                          "using m_consult_mc_visit_risk, m_lib_mc_risk_factors ".
                                          "where m_consult_mc_visit_risk.consult_id = '".$post_vars["consult_id"]."' ".
                                          "and m_consult_mc_visit_risk.mc_id = '".$post_vars["mc_id"]."' and ".
                                          "m_consult_mc_visit_risk.visit_risk_id = m_lib_mc_risk_factors.risk_id and ".
                                          "m_lib_mc_risk_factors.monitor_flag = 'Y'";
                            $result_delete = mysql_query($sql_delete);
							
                            // ...then update from form							

							foreach($post_vars["risk"] as $key=>$value) {								
                                $sql_risk = "insert into m_consult_mc_visit_risk (consult_id , ".
                                            "patient_id, mc_id, visit_risk_id, risk_timestamp, ".
                                            "user_id) values ('".$get_vars["consult_id"]."', ".
                                            "'$patient_id', '".$post_vars["mc_id"]."', '$value', ".
                                            "sysdate(), '".$_SESSION["userid"]."')";
                                $result_risk = mysql_query($sql_risk);
                            }*/
                        }
                    header("location:  ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=PREN".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));	
                    }
					else{
						echo 'Record was not updated';
					}
                }
				else{
					echo "Cannot update. Please complete the form.";
				}
                break;
            case "Delete Prenatal":
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_consult_mc_prenatal where mc_id = '".$post_vars["mc_id"]."' and visit_sequence = '".$post_vars["visit_sequence"]."'";
                    if ($result = mysql_query($sql)) {
                        // delete associated risk data for this prenatal visit
                        // there is no referential integrity definition here...
                        $sql_risk = "delete from m_consult_mc_visit_risk where mc_id = '".$post_vars["mc_id"]."' and consult_id = '".$post_vars["consult_id"]."'";
                        $result_risk = mysql_query($sql_risk);
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=PREN".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=PREN".($post_vars["mc_id"]?"&mc_id=".$post_vars["mc_id"]:""));
                    }
                }
                break;
            case "Save Postpartum Data":
                if ($post_vars["delivery_date"] && $post_vars["delivery_location"] &&
                    $post_vars["outcome"] && $post_vars["birth_weight"] && $post_vars["attendant"]) {
					

					if(isset($_POST[breastfeeding_flag])): //naka-check
							
						if(empty($_POST[date_breastfed])): //no date but checked
							$datebreastfeed = 'n';
						elseif($this->get_day_diff($_POST[date_breastfed],$post_vars["delivery_date"]) < 0): //check if date of breastfeeding occurs after the delivery date
							$datebreastfeed = 'w';
						else:
							$datebreastfeed = 'y'; //set to yes is date_breastfed is not empty and not exceeding delivery date
						endif;

					else:
						$datebreastfeed = 'y'; //not checked, no need to check for date
					endif;



					if($datebreastfeed=='y'){
						list($month, $day, $year) = explode("/", $post_vars["delivery_date"]);
						list($bmonth,$bday,$byr) = explode("/",$_POST["date_breastfed"]);

						$delivery_date = "$year-".str_pad($month,2,"0",STR_PAD_LEFT)."-".str_pad($day,2,"0",STR_PAD_LEFT);
						
						echo $delivery_date;

						if(isset($_POST[breastfeeding_flag])):
							$bfeed_date = "$byr-".str_pad($bmonth,2,"0",STR_PAD_LEFT)."-".str_pad($bday,2,"0",STR_PAD_LEFT);
						else:
							$bfeed_date = '';
						endif;

						
						$healthy_baby = ($post_vars["healthy_baby_flag"]?"Y":"N");
						$breastfeeding = ($post_vars["breastfeeding_flag"]?"Y":"N");
						$end_pregnancy = ($post_vars["end_pregnancy_flag"]?"Y":"N");
						

						// NOTE: update postpartum date from delivery date
						$sql = "update m_patient_mc set ".
							   "delivery_date = '$delivery_date', ".
							   "postpartum_date = from_days(to_days('$delivery_date')+42), ".
							   "obscore_gp = '".$post_vars["obscore_gp"]."', ".
							   "obscore_fpal = '".$post_vars["obscore_fpal"]."', ".
							   "birthweight = '".$post_vars["birth_weight"]."', ".
							   "child_patient_id = '".$post_vars["child_patient_id"]."', ".
							   "delivery_location = '".$post_vars["delivery_location"]."', ".
							   "outcome_id = '".$post_vars["outcome"]."', ".
							   "healthy_baby = '$healthy_baby', ".
							   "end_pregnancy_flag = '$end_pregnancy', ".
							   "breastfeeding_asap = '$breastfeeding', ".
							   "date_breastfed = '$bfeed_date', ".
							   "birthmode = '".$post_vars["attendant"]."' ".
							   "where mc_id = '".$get_vars["mc_id"]."'"; 

						$result = mysql_query($sql) or die("Cannot query: 1068");

						if ($result) {
							header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=".$get_vars["mc_id"]);
						}
					}

					else{
						echo "<script language='Javascript'>";
						if($datebreastfeed=='n'):
							echo "window.alert('Record not saved. Please indicate the date of breastfeeding if the baby was breastfed ASAP.')";
						else: //w ito
						echo "window.alert('Record not saved. Date of breastfeeding should be on or after date of delivery.')";							
						endif;
						echo "</script>";
					}
                }else{
						echo "<script language='Javascript'>";
						echo "window.alert('Record not saved. Please complete REQUIRED fields.')";
						echo "</script>";
				
				
				}
                break;
            }
        }
    }
	
	function get_day_diff($edate,$sdate){
	   $d1=explode("/", $sdate);
	   $d2=explode("/", $edate);
		

   //gregoriantojd() Converts a Gregorian date to Julian Day Count (400BC to 9999AD kaya convert)

	   $start_date=gregoriantojd($d1[0], $d1[1], $d1[2]);   
	   $end_date=gregoriantojd($d2[0], $d2[1], $d2[2]);
	   $diff = $end_date - $start_date;
		return $diff;
	}

    function reorder_prenatal_visits(){
		
	//reorders the visit sequence in the prenatal table
	if(func_num_args()>0):
	   $arg_list = func_get_args();
	   //print_r($arg_list);
	endif;
	$i=1;
	$sql = mysql_query("SELECT * FROM m_consult_mc_prenatal WHERE mc_id='$arg_list[0]' ORDER by prenatal_date ASC") or die("Cannot query: 928");
	
	while($result = mysql_fetch_array($sql)){
	print_r($result).'<br>';
	echo $result["prenatal_date"].'<br>';
	$update_visit = mysql_query("UPDATE m_consult_mc_prenatal SET visit_sequence='$i' WHERE prenatal_date='$result[prenatal_date]'") or die(mysql_error());
	echo $i;
	$i++;		
	}
	$total = mysql_num_rows($sql)+1;
    }

    function check_visit_date(){
	if(func_num_args()>0):
	   $arg_list = func_get_args();	   
	endif;    
	
	$arg_list[0] = trim($arg_list[0]);


	if(empty($arg_list[0])):
		return 1;
	else:

		list($month,$date,$year) = explode("/",$arg_list[0]);
		$prenataldate =  $year.'-'.$month.'-'.$date.' '.'00:00:00';

		$sql = mysql_query("SELECT mc_id FROM m_consult_mc_prenatal WHERE prenatal_date = '$prenataldate' AND mc_id='$arg_list[1]'") or die("Cannot query: 944");

		if(mysql_num_rows($sql)>0):
			return 1;
		else:		
			return 0;
		endif;

	endif;
    }

    function form_mc_labs() {
    //
    // process la$prenataldatebs through registry
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
        // get most recent pregnancy id
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $mc_id = mc::registry_record_exists($patient_id);
        if ($mc_id) {
            print "<table width='300'>";
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&ntp=LABS&ntp_id=".$get_vars["ntp_id"]."' name='form_vitalsigns' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_MC_LAB_FORM."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_LAB_EXAM."</span><br> ";
            print lab::checkbox_lab_exams();
            print "</td></tr>";
            print "<tr><td><br />";
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Send Request' class='textbox' name='submitntp' style='border: 1px solid #000000'> ";
            }
            print "<br /></td></tr>";
            print "</form>";
            print "</table><br>";
        } else {
            print "<font color='red'>No valid Registry ID. Please create a new one.</font>";
        }

    }

	function display_risk_factors($getvars){
		
		$q_risk = mysql_query("SELECT a.risk_id,a.risk_name,b.date_detected FROM m_lib_mc_risk_factors a, m_consult_mc_visit_risk b WHERE b.mc_id='$getvars[mc_id]' AND b.visit_risk_id=a.risk_id ORDER by date_detected DESC") or die("Cannot query: 1103");

		if(mysql_num_rows($q_risk)!=0):
			echo "<form method='POST' name='form_risk'>";
			echo "<input type='hidden' name='confirm_del' value='0'></input>";
			echo "<table bgcolor='#FFCCFF'>";
			echo "<tr align='center'><td style='font-size: 13px; font-weight: bold;'>Risk Factor</td><td style='font-size: 13px; font-weight: bold;'><b>Date Detected</b></td></tr>";
			
			while(list($riskid,$riskname,$datedetected)=mysql_fetch_array($q_risk)){
				$val = $riskid.'*'.$getvars[mc_id].'*'.$datedetected;
				echo "<tr>";
				echo "<td><input type='checkbox' name='riskcode[]' value='$val'>&nbsp;$riskname</td>";
				echo "<td align='center'>$datedetected</td>";
				echo "</tr>";
			}
			
			echo "<tr><td align='center' colspan='2'><input name='submitmc' value='Delete Risk Factor' type='submit' onclick='javascript: check_delete();' style='border: 1px solid #000000'></td></tr>";
			echo "</table>";
			echo "</form>";
		endif;
		
	}

	function form_risk_factors(){

		$query_risk_code = mysql_query("SELECT risk_id,risk_name,monitor_flag FROM m_lib_mc_risk_factors ORDER by risk_name ASC") or die("Cannot query: 1073");
		
		if(mysql_num_rows($query_risk_code)!=0):
			echo "<form method='POST' name='form_riskcode'>";

			echo "<table width='300' bgcolor>";
			echo "<tr><td style='font-size: 13px; font-weight: bold;'>List of Risk Factors&nbsp;&nbsp;(* - for monitoring)";	
			echo "<select name=\"risk_code\" size='1' style='font-size:10;'>";

			while($res_risk = mysql_fetch_array($query_risk_code)){
				if($res_risk[monitor_flag]=='Y'):
					echo "<option value='$res_risk[risk_id]'>$res_risk[risk_name] *</option>";
				else:
					echo "<option value='$res_risk[risk_id]'>$res_risk[risk_name]</option>";				
				endif;
			}		
			echo "</select></td></tr>";

			echo "<tr><td style='font-size: 13px; font-weight: bold;'><b>Date Risk Factor was Detected</b> &nbsp;<input name='risk_date' type='text' size='11' maxlength='10' readonly></input>&nbsp;";
			print "<a href=\"javascript:show_calendar4('document.form_riskcode.risk_date', document.form_riskcode.risk_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";			
			echo "</td></tr>";

			echo "<tr><td align='center'>";
			echo "<input type='submit' value='Save Risk Factor' class='textbox' name='submitmc' style='border: 1px solid #000000'></input>";			
			echo "</td></tr>";

			echo "</table>";
			
			
			echo "</form>";
		
		endif;
	}
    


	function form_vaccine_data_import() {
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
        $mc_id = mc::registry_record_exists($patient_id);
        if ($mc_id) {
            print "<table width='300'>";
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&mc_id=".$get_vars["mc_id"]."' name='form_mc_services' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_VACCINE_DATA_IMPORT."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>REGISTRY INFORMATION</span><br/>";
            print "<span class='tinylight'>";
            print "REGISTRY ID: <font color='red'>".module::pad_zero($mc_id, 7)."</font><br/>";
            print "</span>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            // VACCINE DATA
            print "<table bgcolor='#CCCCFF' width='300' cellpadding='3'><tr><td>";
            print "<span class='boxtitle'>".LBL_VACCINATIONS_RECEIVED."</span><br> ";
            $sql = "select vaccine_id, actual_vaccine_date, consult_id,actual_vaccine_date from m_consult_vaccine ".
                   "where patient_id = '$patient_id'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
					$disp = 0;
                    while (list($id, $vdate,$consultid,$actual_vdate) = mysql_fetch_array($result)) {
						$check_mc_vacc = mysql_query("SELECT patient_id FROM m_consult_mc_vaccine WHERE consult_id='$consultid' AND vaccine_id='$id' AND patient_id='$patient_id' AND actual_vaccine_date='$actual_vdate'") or die(mysql_error());
						
						if(mysql_num_rows($check_mc_vacc)==0):
							print "<input type='checkbox' name='vaccines[]' value='$id'/> ";
							print vaccine::get_vaccine_name($id);
							print " $vdate <br/>";
							$disp = 1;
						endif;
                    }
                } else {
                    print "<font color='red'>No vaccine data available.</font><br/>";
                }
            }
            print "</td></tr>";
            print "</table>";

            print "</td></tr>";
            print "<tr><td><br/>";			
			if($disp!=0):
				if ($_SESSION["priv_add"]) {
					print "<input type='hidden' name='mc_id' value='$mc_id'>";
					print "<input type='hidden' name='patient_id' value='$patient_id'>";
					print "<input type='hidden' name='consult_id' value='".$get_vars["consult_id"]."'>";
					print "<input type='submit' value = 'Import Data' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
				}
			endif;

			print "</td></tr>";
            print "</form>";
            print "</table><br>";
        } else {
            print "<font color='red'>No valid Registry ID. Please create a new one.</font>";
        }

    }

    function form_mc_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // get most recent pregnancy id
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $mc_id = mc::registry_record_exists($patient_id);
        if ($mc_id) {
            print "<table width='300'>";
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=SVC' name='form_mc_services' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_MC_SERVICES."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>REGISTRY INFORMATION</span><br/>";
            print "<span class='tinylight'>";
            print "REGISTRY ID: <font color='red'>".module::pad_zero($mc_id, 7)."</font><br/>";
            print "</span>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            // SERVICE
            print "<table bgcolor='#FFFF66' width='300' cellpadding='3'>";
            print "<tr><td>";
            print "<span class='boxtitle'>".LBL_VISIT_TYPE."</span><br> ";
            print "<input type='radio' name='visit_type' value='HOME'/> Home ";
            print "<input type='radio' name='visit_type' value='CLINIC'/> Clinic ";
            print "</td></tr>";
            print "<tr><td>";
            print "<span class='boxtitle'>".LBL_SERVICES."</span><br> ";
            print mc::checkbox_services();
            print "</td></tr>";
            print "</table>";

            print "</td></tr>";
            print "<tr valign='top'><td>";
            // VACCINE DATA
            print "<table bgcolor='#CCCCFF' width='300' cellpadding='3'><tr><td>";
            print "<span class='boxtitle'>".LBL_VACCINATIONS."</span><br> ";
            print mc::checkbox_vaccines($patient_id);
            print "</td></tr>";
            print "</table>";

            print "</td></tr>";
            print "<tr><td><br/>";
            if ($_SESSION["priv_add"]) {
                print "<input type='hidden' name='mc_id' value='$mc_id'>";
                print "<input type='submit' value = 'Update Services' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
            }
            print "</td></tr>";
            print "</form>";
            print "</table><br>";
        } else {
            print "<font color='red'>No valid Registry ID. Please create a new one.</font>";
        }

    }

    function form_mc_firstvisit() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["mc_id"] && $post_vars["submitmc"]) {
            $sql = "select mc_id, patient_id, consult_id, mc_consult_date, ".
                   "patient_lmp, obscore_gp, obscore_fpal, blood_type, ".
                   "patient_height ".
                   "from m_patient_mc ".
                   "where mc_id = '".$post_vars["mc_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    $mc = mysql_fetch_array($result);
                }
            }
        }
        print "<a name='visit1form'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=VISIT1' name='form_mc_visit1' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_MC_DATA_FORM."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        // FIRST VISIT INFO
        // get most recent pregnancy id
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $mc_id = mc::registry_record_exists($patient_id);
        print "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
        print "<tr valign='top'><td>";
        if ($mc["patient_lmp"]) {
            list($year, $month, $day) = explode("-", $mc["patient_lmp"]);
            $lmp_date = "$month/$day/$year";
        }
        print "<span class='boxtitle'>".LBL_LMP_DATE."</span><br> ";
        print "<input type='text' size='10' maxlength='10' class='textbox' name='lmp_date' value='".($lmp_date?$lmp_date:$post_vars["lmp_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_mc_visit1.lmp_date', document.form_mc_visit1.lmp_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OBSTETRIC_SCORE."</span><br> ";
        print "G/P <input type='text' class='tinylight' size='7' maxlength='7' name='obscore_gp' value='".($mc["obscore_gp"]?$mc["obscore_gp"]:$post_vars["obscore_gp"])."' style='border: 1px solid #000000'> ";
        print "FPAL <input type='text' class='tinylight' size='7' maxlength='7' name='obscore_fpal' value='".($mc["obscore_fpal"]?$mc["obscore_fpal"]:$post_vars["obscore_fpal"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PATIENT_HEIGHT."</span><br> ";
        print "<input type='text' class='tinylight' size='10' maxlength='10' name='patient_height' value='".($mc["patient_height"]?$mc["patient_height"]:$post_vars["patient_height"])."' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PATIENT_BLOODTYPE."</span><br> ";
        print mc::show_bloodtype($mc["blood_type"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_RISK_FACTORS."</span><br> ";
        print mc::checkbox_risk_factors($mc["mc_id"], $mc["consult_id"]);
        print "</td></tr>";
        print "</table>";
        print "</td></tr>";
        print "<tr><td><br/>";
        if ($post_vars["mc_id"]) {
            if ($_SESSION["priv_update"]) {
                print "<input type='hidden' name='mc_id' value='".$post_vars["mc_id"]."' />";
                print "<input type='submit' value = 'Update Data' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
                print "<input type='submit' value = 'Delete Data' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Save Data' class='textbox' name='submitmc' style='border: 1px solid #000000'><br>";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function form_mc_prenatal() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);

        }
        if ($post_vars["prenatal_id"] && $post_vars["submitmc"] && $post_vars["visit_sequence"]) {
            $sql = "select mc_id, patient_id, consult_id, patient_weight, prenatal_date, ".
                   "blood_pressure_systolic, blood_pressure_diastolic, fundic_height, ".
                   "presentation, fhr, fhr_location, trimester, visit_sequence, data_type ".
                   "from m_consult_mc_prenatal ".
                   "where mc_id = '".$post_vars["prenatal_id"]."' and visit_sequence = '".$post_vars["visit_sequence"]."'";

            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    $mc = mysql_fetch_array($result);
                    //print_r($mc);
                }
            }
        }
        // PRENATAL INFO
        // get most recent pregnancy id
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $mc_id = mc::registry_record_exists($patient_id);
        if ($mc_id) {
            // edit prenatal data
            if ($post_vars["prenatal_id"]) {
                list($aog_weeks,$aog_days) = mc::get_aog($mc_id, healthcenter::get_consult_date($mc["consult_id"]));
                $aog = ($aog_weeks + ($aog_days/7));
                $trimester = $mc["trimester"];
                //$visit_sequence = $mc["visit_sequence"];
				$visit_sequence = $post_vars["visit_sequence"];
            } else {
                // new prenatal data headers
				$sql_count = mysql_query("SELECT count(mc_id) FROM m_consult_mc_prenatal WHERE mc_id='$mc_id'") or die("cannot query: 1236");
				$prenatal_seq = mysql_fetch_array($sql_count);		
				$seq = $prenatal_seq[0] + 1;

                list($aog_weeks,$aog_days) = mc::get_aog($mc_id, $get_vars["consult_id"]);
                $aog = ($aog_weeks + ($aog_days/7));
                $trimester = mc::get_trimester($mc_id, healthcenter::get_consult_date($get_vars["consult_id"]));
                //$visit_sequence = mc::get_visit_sequence($mc_id, $get_vars["consult_id"]);
				$visit_sequence = $seq;
            }
            print "<a name='prenatal'>";
            print "<table width='300'>";
            if ($post_vars["Delete Prenatal"]) {
                $tail = "prenatal";
            } else {
                $tail = "";
            }
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=PREN&mc_id=$mc_id#$tail' name='form_mc_prenatal' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_MC_PRENATAL_FORM."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<a name='prevtx'>";
            //print "<span class='boxtitle'>".LBL_IMPORT_EXTERNAL_DATA."?</span><br> ";
            //print "<input type='checkbox' name='data_import_flag' onchange='this.form.submit();' ".(($mc["data_type"]?$mc["data_type"]=="EXT":$post_vars["data_import_flag"])?"checked":"")." value='1'/> Check if external HBMR<br />";
            print "</td></tr>";
            //if ($mc["data_type"]=="EXT" || $post_vars["data_import_flag"]) {
                if ($mc["prenatal_date"]) {
                    list($date, $time) = explode(" ", $mc["prenatal_date"]);
                    list($year, $month, $day) = explode("-", $date);
                    $visit_date = "$month/$day/$year";
                }
                print "<tr valign='top'><td>";
                print "<span class='boxtitle'>".LBL_PRENATAL_VISIT_DATE."</span><br>";
                print "<input type='text' size='10' maxlength='10' class='textbox' name='visit_date' value='".($visit_date?$visit_date:$post_vars["visit_date"])."' style='border: 1px solid #000000'> ";
                print "<a href=\"javascript:show_calendar4('document.form_mc_prenatal.visit_date', document.form_mc_prenatal.visit_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
                print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
                print "</td></tr>";
            //}
            print "<tr valign='top'><td>";
            print "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
            //if ($post_vars["data_import_flag"]) {
                print "<tr valign='top'><td>";
                print "<span class='boxtitle'>".LBL_PRENATAL_VISIT_SEQUENCE."</span><br>";
            //  print "<input type='text' size='5' maxlength='5' class='textbox' name='visit_sequence' value='".($mc["visit_sequence"]?$mc["visit_sequence"]:$post_vars["visit_sequence"])."' style='border: 1px solid #000000'> ";
		
		print "<input type='text' size='5' maxlength='5' class='textbox' name='visit_sequence' value='$visit_sequence' style='border: 1px solid #000000' readonly> ";
                print "</td></tr>";
            //} else {
            /*    print "<tr valign='top'><td>";
                print "<span class='boxtitle'>REGISTRY INFORMATION</span><br/>";
                print "<span class='tinylight'>";
                print "REGISTRY ID: <font color='red'>".module::pad_zero($mc_id, 7)."</font><br/>";
                print "AOG THIS VISIT: $aog_weeks WKS $aog_days DAYS<br/>";
                print "PATIENT TRIMESTER: $trimester<br/>";
                print "VISIT SEQUENCE: $visit_sequence<br/>";
                print "</span>";
                print "</td></tr>";
   	    */
            //}
            print "<tr valign='top'><td>";
            if ($post_vars["prenatal_id"]) {
                $systolic = $mc["blood_pressure_systolic"];
                $diastolic = $mc["blood_pressure_diastolic"];
            } else {
                list($systolic, $diastolic) = healthcenter::get_blood_pressure($get_vars["consult_id"]);
            }
            print "<span class='boxtitle'>".LBL_BLOOD_PRESSURE."</span><br> ";
            print "<span class='tinylight'>SYSTOLIC</span> <input type='text' class='tinylight' size='5' maxlength='3' name='patient_systolic' value='$systolic' style='border: 1px solid #000000'> ";
            print "<span class='tinylight'>DIASTOLIC</span> <input type='text' class='tinylight' size='5' maxlength='3' name='patient_diastolic' value='$diastolic' style='border: 1px solid #000000'> ";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            if ($post_vars["prenatal_id"]) {
                $weight = $mc["patient_weight"];
            } else {
                $weight = healthcenter::get_body_weight($get_vars["consult_id"]);
            }
            print "<span class='boxtitle'>".LBL_PATIENT_WEIGHT." (KG)</span><br> ";
            print "<input type='text' class='tinylight' size='5' maxlength='5' name='patient_weight' value='$weight' style='border: 1px solid #000000'> ";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_OBSTETRIC_EXAM."</span><br> ";
            print "<span class='tinylight'>FUNDIC HEIGHT (CM)</span> <input type='text' class='tinylight' size='5' maxlength='5' name='fundic_height' value='".($mc["fundic_height"]?$mc["fundic_height"]:$post_vars["fundic_height"])."' style='border: 1px solid #000000'><br/> ";
            print "<span class='tinylight'>FHR (beats/min)</span> <input type='text' class='tinylight' size='5' maxlength='5' name='fhr' value='".($mc["fhr"]?$mc["fhr"]:$post_vars["fhr"])."' style='border: 1px solid #000000'><br/> ";
            print "<span class='tinylight'>FHR LOCATION</span> ";
            print mc::show_fhr_location($mc["fhr_location"]);
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_PRESENTATION."</span><br> ";
            print mc::show_presentation($mc["presentation"]);
            print "</td></tr>";
            
			/*
			print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_RISK_FACTORS." (risk factors shown are those that are marked for monitoring)</span> <br> ";
            if ($visit_sequence>1) {
                if ($post_vars["prenatal_id"]) {
                    print mc::checkbox_risk_factors($post_vars["prenatal_id"], $mc["consult_id"], "M");
                } else {
                    print mc::checkbox_risk_factors($mc_id, $get_vars["consult_id"], "M");
                }
            } else {
                print "<font color='red'>".LBL_SAME_RISK_FACTORS_VISIT1."</font><br/>";
            }
            print "</td></tr>";
            */
			print "</table>";
            print "</td></tr>";
            print "<tr><td><br/>";
            if ($post_vars["prenatal_id"]) {
                if ($_SESSION["priv_update"]) {
                    print "<input type='hidden' name='mc_id' value='".$get_vars["mc_id"]."' />";
                    //print "<input type='hidden' name='consult_id' value='".$mc["mc_id"]."' />";
                    if (!$post_vars["data_import_flag"]) {
                        print "<input type='hidden' name='visit_sequence' value='$visit_sequence' />";
                    }
                    print "<input type='submit' value = 'Update Prenatal' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
                    print "<input type='submit' value = 'Delete Prenatal' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
                }
            } else {
                if ($_SESSION["priv_add"]) {
                    print "<input type='hidden' name='mc_id' value='".$get_vars["mc_id"]."' />";
                    if (!$post_vars["data_import_flag"]) {
                        print "<input type='hidden' name='aog_weeks' value='$aog' />";
                        print "<input type='hidden' name='trimester' value='$trimester' />";
                        print "<input type='hidden' name='visit_sequence' value='$visit_sequence' />";
                    }
                    print "<br><input type='submit' value = 'Save Prenatal Data' class='textbox' name='submitmc' style='border: 1px solid #000000'><br>";
                }
            }
            print "</td></tr>";
            print "</form>";
            print "</table><br>";
			
			$q_remarks = mysql_query("SELECT prenatal_remarks FROM m_patient_mc WHERE mc_id='$get_vars[mc_id]'") or die("Cannot query: 1574");
			
			list($remarks) = mysql_fetch_array($q_remarks);
			echo "<form method='POST' name='form_prenatal_remarks'>";
			echo "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
			echo "<a name='prerem'></a>";
			echo "<span class='boxtitle'>PRENATAL REMARKS</span>";
			
			if($_POST["update_prenatal"]):
				echo "<tr><td><textarea name='prenatal_remarks' cols='35' rows='4'>";				
			else:
				echo "<tr><td><textarea name='prenatal_remarks' cols='35' rows='4' readonly>";
			endif;

			echo $remarks;
			echo "</textarea></td></tr>";

			if($_POST["update_prenatal"]):
				echo "<tr><td><input type='submit' name='submitmc' value='Save Prenatal Remarks' style='border: 1px solid #000000'></input>&nbsp;&nbsp;<input type='button' name='cancel' value='Cancel' onclick='history.go(-1)' style='border: 1px solid #000000'></input></td></tr>";				
			else:			
				echo "<tr><td><input type='submit' name='update_prenatal' value='Update Prenatal Remarks' style='border: 1px solid #000000'></input></td></tr>";
			endif;
			
			
			echo "</table>";
				
			echo "</form>";


        } else {
            print "<font color='red'>No valid Registry ID. Please create a new one.</font>";
        }
    }

    function form_mc_postpartum_visit() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["postpartum_id"] && $post_vars["submitmc"]=="Update Visit" && $post_vars["visit_sequence"]) {
            $sql = "select mc_id, patient_id, consult_id, postpartum_week, ".
                   "blood_pressure_systolic, blood_pressure_diastolic, visit_sequence, ".
                   "postpartum_date, visit_type, breastfeeding_flag, family_planning_flag, ".
                   "vaginal_infection_flag, vaginal_bleeding_flag, fever_flag, ".
                   "pallor_flag, cord_ok_flag ".
                   "from m_consult_mc_postpartum ".
                   "where mc_id = '".$post_vars["postpartum_id"]."' and visit_sequence = '".$post_vars["visit_sequence"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    $mc = mysql_fetch_array($result);
                    //print_r($mc);
                }
            }
        }
        // POSTPARTUM INFO
        // get most recent pregnancy id
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        //$mc_id = mc::registry_record_exists($patient_id);

	$mc_id = $get_vars["mc_id"];  //this will enable editing postpartum visits even the mc instance is closed

        if ($mc_id) {
            // edit prenatal data
            if ($post_vars["postpartum_id"]) {
                $postpartum_week = $mc["postpartum_week"];
                $visit_sequence = $mc["visit_sequence"];
            } else {
                // postpartum data headers
                $postpartum_week = mc::get_pp_weeks($mc_id, $get_vars["consult_id"]);
                $visit_sequence = mc::get_ppvisit_sequence($mc_id, $get_vars["consult_id"]);
            }
            print "<a name='postpartum_visit'>";
            print "<table width='300'>";
            if ($post_vars["Delete Postpartum"]) {
                $tail = "postpartum_visit";
            } else {
                $tail = "";
            }
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=POSTP&mc_id=$mc_id#$tail' name='form_mc_ppvisit' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_MC_POSTPARTUM_VISIT_FORM."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            if ($mc["postpartum_date"]) {
                list($date, $time) = explode(" ", $mc["postpartum_date"]);
                list($year, $month, $day) = explode("-", $date);
                $visit_date = "$month/$day/$year";
            }
            print "<span class='boxtitle'>".LBL_POSTPARTUM_VISIT_DATE."</span><br>";
            print "<input type='text' size='10' maxlength='10' class='textbox' name='visit_date' value='".($visit_date?$visit_date:$post_vars["visit_date"])."' style='border: 1px solid #000000'> ";
            print "<a href=\"javascript:show_calendar4('document.form_mc_ppvisit.visit_date', document.form_mc_ppvisit.visit_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
            print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_VISIT_TYPE."</span><br> ";
            print "<select name='visit_type' class='textbox'>";
            print "<option value=''>Select Type of Visit</option>";
            print "<option value='CLINIC' ".($mc["visit_type"]=="CLINIC"?"selected":"").">Clinic Visit</option>";
            print "<option value='HOME' ".($mc["visit_type"]=="HOME"?"selected":"").">Home Visit</option>";
            print "</select>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>REGISTRY INFORMATION</span><br/>";
            print "<span class='tinylight'>";
            print "REGISTRY ID: <font color='red'>".module::pad_zero($mc_id, 7)."</font><br/>";
            print "POSTPARTUM WEEK: $postpartum_week<br/>";
            print "VISIT SEQUENCE: $visit_sequence<br/>";
            print "</span>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            if ($post_vars["postpartum_id"]) {
                $systolic = $mc["blood_pressure_systolic"];
                $diastolic = $mc["blood_pressure_diastolic"];
            } else {
                list($systolic, $diastolic) = healthcenter::get_blood_pressure($get_vars["consult_id"]);
            }
            print "<span class='boxtitle'>".LBL_BLOOD_PRESSURE."</span><br> ";
            print "<span class='tinylight'>SYSTOLIC</span> <input type='text' class='tinylight' size='5' maxlength='3' name='patient_systolic' value='$systolic' style='border: 1px solid #000000'> ";
            print "<span class='tinylight'>DIASTOLIC</span> <input type='text' class='tinylight' size='5' maxlength='3' name='patient_diastolic' value='$diastolic' style='border: 1px solid #000000'> ";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_POSTPARTUM_EVENTS."</span><br> ";
            print "<input type='checkbox' name='vaginal_infection_flag' ".(($mc["vaginal_infection_flag"]?$mc["vaginal_infection_flag"]=="Y":$post_vars["vaginal_infection_flag"])?"checked":"")." value='1'/> Check if with vaginal infection<br />";
            print "<input type='checkbox' name='vaginal_bleeding_flag' ".(($mc["vaginal_bleeding_flag"]?$mc["vaginal_bleeding_flag"]=="Y":$post_vars["vaginal_bleeding_flag"])?"checked":"")." value='1'/> Check if with vaginal bleeding<br />";
            print "<input type='checkbox' name='fever_flag' ".(($mc["fever_flag"]?$mc["fever_flag"]=="Y":$post_vars["fever_flag"])?"checked":"")." value='1'/> Check if with fever>38&deg;C<br />";
            print "<input type='checkbox' name='pallor_flag' ".(($mc["pallor_flag"]?$mc["pallor_flag"]=="Y":$post_vars["pallor_flag"])?"checked":"")." value='1'/> Check if with pallor<br />";
            print "<input type='checkbox' name='cord_ok_flag' ".(($mc["cord_ok_flag"]?$mc["cord_ok_flag"]=="Y":$post_vars["cord_ok_flag"])?"checked":"")." value='1'/> Check if baby's cord OK<br />";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_BREASTFEEDING."?</span><br> ";
            print "<input type='checkbox' name='breastfeeding_flag' ".(($mc["breastfeeding_flag"]?$mc["breastfeeding_flag"]=="Y":$post_vars["breastfeeding_flag"])?"checked":"")." value='1'/> Check if patient breastfeeds baby<br />";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_FAMILY_PLANNING."?</span><br> ";
            print "<input type='checkbox' name='family_planning_flag' ".(($mc["family_planning_flag"]?$mc["family_planning_flag"]=="Y":$post_vars["family_planning_flag"])?"checked":"")." value='1'/> Check if with family planning method<br />";
            print "</td></tr>";
            print "</table>";
            print "</td></tr>";
            print "<tr><td><br/>";
            if ($post_vars["postpartum_id"]) {
                if ($_SESSION["priv_update"]) {
                    print "<input type='hidden' name='mc_id' value='".$get_vars["mc_id"]."' />";
                    print "<input type='hidden' name='visit_sequence' value='$visit_sequence' />";
                    print "<input type='submit' value = 'Update Postpartum' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
                    print "<input type='submit' value = 'Delete Postpartum' class='textbox' name='submitmc' style='border: 1px solid #000000'> ";
                }
            } else {
                if ($_SESSION["priv_add"]) {
                    print "<input type='hidden' name='mc_id' value='".$get_vars["mc_id"]."' />";
                    print "<input type='hidden' name='postpartum_week' value='$postpartum_week' />";
                    print "<input type='hidden' name='visit_sequence' value='$visit_sequence' />";
                    print "<br><input type='submit' value = 'Save Postpartum Visit' class='textbox' name='submitmc' style='border: 1px solid #000000'><br>";
                }
            }
            print "</td></tr>";
            print "</form>";
            print "</table><br>";



        } else {
            print "<font color='red'>No valid Registry ID. Please create a new one.</font>";
        }
    }

    function form_mc_postpartum() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["mc_id"] && $post_vars["submitmc"]=="Update Postpartum Data Form") {
            $sql = "select mc_id, patient_id, consult_id, mc_consult_date, ".
                   "delivery_date, obscore_gp, obscore_fpal, child_patient_id, ".
                   "delivery_location, outcome_id, birthmode, birthweight, ".
                   "breastfeeding_asap,date_breastfed,healthy_baby ".
                   "from m_patient_mc ".
                   "where mc_id = '".$post_vars["mc_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    $mc = mysql_fetch_array($result);
					
					if($mc["date_breastfed"]!='0000-00-00'):
						list($byr,$bmonth,$bdate) = explode('-',$mc["date_breastfed"]);
						$bfeed_date = $bmonth.'/'.$bdate.'/'.$byr;
					endif;
                }
            }
        }
        // get most recent pregnancy id
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $mc_id = mc::registry_record_exists($patient_id);
        if ($mc_id) {
            // edit prenatal data
            print "<a name='visit1form'>";
            print "<table width='300'>";
            print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=POSTP&mc_id=".$get_vars["mc_id"]."' name='form_mc_postpartum' method='post'>";
            print "<tr valign='top'><td>";
            print "<b>".FTITLE_MC_POSTPARTUM_DATA_FORM."</b><br/><br/>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='tinylight'><b>IMPORTANT:</b> ".INSTR_POSTPARTUM_RECORD.". DATA FIELDS WITH <font color='red'><b>*</b></font> ARE REQUIRED.</span><br/>";
            // THIS COMPLETES THE REST OF ENTRIES IN m_patient_mc TABLE
            print "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>REGISTRY INFORMATION</span><br/>";
            print "<span class='tinylight'>";
            print "REGISTRY ID: <font color='red'>".module::pad_zero($mc_id, 7)."</font><br/>";
            print "</span>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            if ($mc["delivery_date"]) {
                list($year, $month, $day) = explode("-", $mc["delivery_date"]);
                $delivery_date = "$month/$day/$year";
            }
            print "<span class='boxtitle'>".LBL_DELIVERY_DATE."<font color='red'> *</font></span><br> ";
            print "<input type='text' size='10' maxlength='10' class='textbox' name='delivery_date' value='".($delivery_date?$delivery_date:$post_vars["delivery_date"])."' style='border: 1px solid #000000'> ";
            print "<a href=\"javascript:show_calendar4('document.form_mc_postpartum.delivery_date', document.form_mc_postpartum.delivery_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
            print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_FINAL_OBSTETRIC_SCORE."</span><br> ";
            print "G/P <input type='text' class='tinylight' size='7' maxlength='7' name='obscore_gp' value='".($mc["obscore_gp"]?$mc["obscore_gp"]:$post_vars["obscore_gp"])."' style='border: 1px solid #000000'> ";
            print "FPAL <input type='text' class='tinylight' size='7' maxlength='7' name='obscore_fpal' value='".($mc["obscore_fpal"]?$mc["obscore_fpal"]:$post_vars["obscore_fpal"])."' style='border: 1px solid #000000'><br>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_CHILD_PATIENT_ID."</span><br> ";
            print "<input type='text' class='tinylight' size='5' maxlength='5' name='child_patient_id' value='".($mc["child_patient_id"]?$mc["child_patient_id"]:$post_vars["child_patient_id"])."' style='border: 1px solid #000000'/><br/> ";
            print "<small>".INSTR_CHILD_PATIENT_ID."</small><br>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_DELIVERY_LOCATION."</span><font color='red'> *</font><br> ";
            print mc::show_delivery_location($mc["delivery_location"]);
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_BIRTH_ATTENDANT."</span><font color='red'> *</font><br> ";
            print mc::show_attendant($mc["birthmode"]);

            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_PREGNANCY_OUTCOME."</span><font color='red'> *</font><br> ";
            print mc::show_pregnancy_outcomes($mc["outcome_id"]);
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_BABY_WEIGHT." (KG)</span><font color='red'> *</font><br> ";
            print "<input type='text' class='tinylight' size='5' maxlength='5' name='birth_weight' value='".($mc["birthweight"]?$mc["birthweight"]:$post_vars["birth_weight"])."' style='border: 1px solid #000000'> ";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_BREASTFEEDING_FLAG."?</span><br> ";
            print "<input type='checkbox' name='breastfeeding_flag' value='1' ".($mc["breastfeeding_asap"]=="Y"?"checked":"")."'> ".INSTR_BREASTFEEDING_ASAP_FLAG."<br>";

            print "</td></tr>";
			
			echo "<tr><td>";
			print "<span class='boxtitle'>DATE OF BREASTFEEDING</span>&nbsp;";
			echo "<input type='text' name='date_breastfed' id='bfeed' size='10'  value='$bfeed_date' maxlength='10' value='$mc[date_breastfed]' style='border: 1px solid #000000' readonly></input>&nbsp;";
			print "<a href=\"javascript:show_calendar4('document.form_mc_postpartum.date_breastfed', document.form_mc_postpartum.date_breastfed.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
			echo "</td></tr>";

            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>".LBL_HEALTHY_BABY_FLAG."?</span><br>";
            print "<input type='checkbox' name='healthy_baby_flag' value='1' ".($mc["healthy_baby"]=="Y"?"checked":"")."'> ".INSTR_HEALTHY_BABY_FLAG."<br>";
            print "</td></tr>";
            print "<tr bgcolor='#FF66FF' valign='top'><td>";
            print "<span class='boxtitle'>".LBL_END_PREGNANCY_FLAG."?</span><br> ";
            print "<input type='checkbox' name='end_pregnancy_flag' value='1' ".($mc["end_pregnancy_flag"]=="Y"?"checked":"")."'> ".INSTR_END_PREGNANCY_FLAG."<br>";
            print "</td></tr>";
            print "</table>";
            print "</td></tr>";
            print "<tr><td>";
            if ($mc_id || $post_vars["mc_id"]) {
                if ($_SESSION["priv_add"] || $_SESSION["isadmin"]) {
                    print "<br><input type='submit' value = 'Save Postpartum Data' class='textbox' name='submitmc' style='border: 1px solid #000000'><br>";
                }
            }
            print "</td></tr>";

            print "</form>";
            print "</table><br>";

			$q_post_remarks = mysql_query("SELECT postpartum_remarks FROM m_patient_mc WHERE mc_id='$get_vars[mc_id]'") or die("Cannot query; 1856");

			list($remarks) = mysql_fetch_array($q_post_remarks);

			echo "<form method='POST' name='form_postpartum_remarks'>";
			echo "<a name='postrem'></a>";
			echo "<table bgcolor='#FFCCFF' width='300' cellpadding='3'>";
			echo "<span class='boxtitle'>POSTPARTUM REMARKS</span>";

			echo "<tr><td>";
			
			if($_POST["update_postpartum"]):
				echo "<textarea name='postpartum_remarks' cols='35' width='4'>";
			else:
				echo "<textarea name='postpartum_remarks' cols='35' width='4' readonly>";
			endif;
			

			echo $remarks."</textarea></td></tr>";
			
			if($_POST["update_postpartum"]):
				echo "<tr><td><input type='submit' name='submitmc' value='Save Postpartum Remarks' style='border: 1px solid #000000'></input>&nbsp;&nbsp;<input type='button' name='cancel' value='Cancel' onclick='history.go(-1)' style='border: 1px solid #000000'></input></td></tr>";				
			else:			
				echo "<tr><td><input type='submit' name='update_postpartum' value='Update Postpartum Remarks' style='border: 1px solid #000000'></input></td></tr>";
			endif;


			echo "</table>";
			echo "</form>";

        } else {
            print "<font color='red'>No valid Registry ID. Please create a new one.</font>";
        }
    }

    function show_bloodtype() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $type_id = $arg_list[0];
        }
        print "<select name='bloodtype' class='tinylight'>";
        print "<option value=''>Select Type</option>";
        print "<option value='O' ".($type_id=="O"?"selected":"").">Type O</option>";
        print "<option value='A' ".($type_id=="A"?"selected":"").">Type A</option>";
        print "<option value='B' ".($type_id=="B"?"selected":"").">Type B</option>";
        print "<option value='AB' ".($type_id=="AB"?"selected":"").">Type AB</option>";
        print "</select>";
    }

    function show_fhr_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }
        print "<select name='fhr_location' class='tinylight'>";
        print "<option value=''>Select Location</option>";
        print "<option value='LLQ' ".($location_id=="LLQ"?"selected":"").">LLQ</option>";
        print "<option value='RLQ' ".($location_id=="RLQ"?"selected":"").">RLQ</option>";
        print "<option value='LUQ' ".($location_id=="LUQ"?"selected":"").">LUQ</option>";
        print "<option value='RUQ' ".($location_id=="RUQ"?"selected":"").">RUQ</option>";
        print "<option value='NA' ".($location_id=="NA"?"selected":"").">N/A</option>";
        print "</select>";
    }

    function show_presentation() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $pres_id = $arg_list[0];
        }
        print "<select name='presentation' class='tinylight'>";
        print "<option value=''>Select Presentation</option>";
        print "<option value='CEPH' ".($pres_id=="CEPH"?"selected":"").">Cephalic</option>";
        print "<option value='BREECH' ".($pres_id=="BREECH"?"selected":"").">Breech</option>";
        print "<option value='TRANS' ".($pres_id=="TRANS"?"selected":"").">Transverse</option>";
        print "<option value='MASS' ".($pres_id=="MASS"?"selected":"").">Mass Palpable - NA</option>";
        print "</select>";
    }

    function checkbox_risk_factors() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
            $consult_id = $arg_list[1];
            $flag = $arg_list[2];
        }
        switch($flag) {
        case "M":  //monitor risk factor during pregnancy
            $sql = "select risk_id, hospital_flag, risk_name from m_lib_mc_risk_factors where monitor_flag = 'Y' order by risk_name";
            break;
        case "H": //hospital 
            $sql = "select risk_id, hospital_flag, risk_name from m_lib_mc_risk_factors where hospital_flag = 'Y' order by risk_name";
            break;
        default:
            $sql = "select risk_id, hospital_flag, risk_name from m_lib_mc_risk_factors order by risk_name";
        }
        if ($result = mysql_query($sql)) {
            print "<table cellspacing='0' cellpadding='0'>";
            while(list($id, $hflag, $name) = mysql_fetch_array($result)) {
                print "<tr valign='top'><td>$status<input type='checkbox' name='risk[]' value='$id' ".(mc::get_risk_status($id, $mc_id, $consult_id, $flag)?"checked":"")."/> </td>";
                print "<td class='tinylight'>".($hflag=="Y"?"<font color='red'><b>$name</b></font>":"$name")."</td></tr>";
            }
            print "</table>";
        }
    }

    function get_risk_status() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $risk_id = $arg_list[0];
            $mc_id = $arg_list[1];
            $consult_id = $arg_list[2];
            $flag = $arg_list[3];
        }
        if ($flag=="M") {
            $sql = "select c.visit_risk_id ".
                   "from m_consult_mc_visit_risk c, m_lib_mc_risk_factors r ".
                   "where c.visit_risk_id = r.risk_id and ".
                   "c.mc_id = '$mc_id' and c.visit_risk_id = '$risk_id' ".
                   "and c.consult_id = '$consult_id' and r.monitor_flag = 'Y'";
        } else {
			$sql = "select visit_risk_id FROM m_consult_mc_visit_risk WHERE mc_id='$mc_id' AND visit_risk_id='$risk_id'";
			//$sql = "select visit_risk_id from m_consult_mc_visit_risk ".
                   "where mc_id = '$mc_id' and visit_risk_id = '$risk_id' and consult_id = '$consult_id'";
        }
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($id) = mysql_fetch_array($result);
                return $id;
            }
            return 0;
        }
    }

    function _details_mc() {
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
            mc::process_detail($menu_id, $post_vars, $get_vars);
        }
        print "<b>".FTITLE_REGISTRY_RECORDS."</b><br/>";
        mc::display_registry_records($menu_id, $post_vars, $get_vars);
        if ($get_vars["mc_id"]) {
            mc::display_registry_record_detail($menu_id, $post_vars, $get_vars);
            mc::display_prenatal_records($menu_id, $post_vars, $get_vars);
            mc::display_postpartum_records($menu_id, $post_vars, $get_vars);
            mc::display_vaccine_record($menu_id, $post_vars, $get_vars);
            mc::display_service_record($menu_id, $post_vars, $get_vars);
        }
        print "<br/>";
        print "";
    }

    function display_registry_records() {
    //
    // called from _details_mc()
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
        $sql = "select mc_id, patient_id, consult_id, delivery_date, end_pregnancy_flag, date_format(mc_timestamp, '%a %d %b %Y, %h:%i%p') mc_consult_date, obscore_gp ".
               "from m_patient_mc where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($mc = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "REGISTRY ID: <font color='red'>".module::pad_zero($mc["mc_id"],7)."</font> <font color='#669900'>".$mc["obscore_gp"]."</font> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=".$get_vars["mc"]."&mc_id=".$mc["mc_id"]."#visit1'>".$mc["mc_consult_date"]."</a> ";
                    print ($mc["end_pregnancy_flag"]=='N'?"OPEN":"CLOSED")."<br/>";
                }
            } else {
                print "<font color='red'>No registry records.</font><br/>";
            }
        }
    }

    function display_prenatal_records() {
    //
    // called from _details_mc()
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
        print "<b>".FTITLE_PRENATAL_RECORDS."</b><br/>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select mc_id, patient_id, consult_id, date_format(prenatal_date, '%a %d %b %Y, %h:%i%p') prenatal_date, ".
               "round(aog_weeks,2), trimester, visit_sequence ".
               "from m_consult_mc_prenatal where patient_id = '$patient_id' and mc_id = '".$get_vars["mc_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
		$visit_counter = 1;
                while ($mc = mysql_fetch_array($result)) {
                    print "<span class='tinylight'>";
                    if ($prev_trim<>$mc["trimester"]) {
                        print "<img src='../images/arrow_redwhite.gif' border='0'/> <b>TRIMESTER ".$mc["trimester"]."</b><br/>";
                    }
                    //print "VISIT ".$mc["visit_sequence"]." ";
		    print "VISIT ".$visit_counter." ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=PREN&mc_id=".$mc["mc_id"]."&visitseq=".$mc["visit_sequence"]."#prenatal_detail'>".$mc["prenatal_date"]."</a><br/>";
                    print "</span>";
                    $prev_trim = $mc["trimester"];
                    if ($get_vars["visitseq"]==$mc["visit_sequence"]) {
                        mc::display_prenatal_record_detail($menu_id, $post_vars, $get_vars);
                    }
			$visit_counter++;
                }
            } else {
                print "<font color='red'>No prenatal records.</font><br/>";
            }
        }
        print "<br/>";
    }

    function display_postpartum_records() {
    //
    // called from _details_mc()
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
        print "<b>".FTITLE_POSTPARTUM_RECORDS."</b><br/>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select mc_id, patient_id, consult_id, date_format(postpartum_date, '%a %d %b %Y, %h:%i%p') postpartum_date, "."postpartum_week, visit_sequence "."from m_consult_mc_postpartum where patient_id = '$patient_id' and mc_id = '".$get_vars["mc_id"]."' ORDER BY visit_sequence ASC";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($mc = mysql_fetch_array($result)) {
                    print "<span class='tinylight'>";
                    if ($prev_week<>$mc["postpartum_week"]) {
                        print "<img src='../images/arrow_redwhite.gif' border='0'/> <b>WEEK ".$mc["postpartum_week"]."</b><br/>";
                    }		    
                    print "VISIT ".$mc["visit_sequence"]." ";
                    //print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=".$mc["mc_id"]."&ppvisitseq=".$mc["visit_sequence"]."#postpartum_detail'>".$mc["postpartum_date"]."</a><br/>";
					
		print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$mc["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP&mc_id=".$mc["mc_id"]."&ppvisitseq=".$mc["visit_sequence"]."#postpartum_detail'>".$mc["postpartum_date"]."</a><br/>";
                    
		print "</span>";
                    $prev_week = $mc["postpartum_week"];
                    if ($get_vars["ppvisitseq"]==$mc["visit_sequence"]) {
                        mc::display_postpartum_record_detail($menu_id, $post_vars, $get_vars);
                    }

                }
            } else {
                print "<font color='red'>No postpartum records.</font><br/>";
            }
        }
        print "<br/>";
    }

    function display_registry_record_detail() {
    //
    // called from _details_mc()
    // contains most alerts for this pregnancy
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
        $sql = "select mc_id, patient_id, consult_id, date_format(mc_timestamp, '%a %d %b %Y, %h:%i%p') mc_timestamp, ".
               "patient_lmp, patient_edc, trimester1_date, trimester2_date, round((to_days(mc_consult_date)-to_days(patient_lmp))/7,0) patient_aog, ".
               "MOD((to_days(mc_consult_date)-to_days(patient_lmp)),7) remainder, ".
               "trimester3_date, postpartum_date, to_days(trimester1_date) days_trim1, ".
               "to_days(trimester2_date) days_trim2, to_days(trimester3_date) days_trim3, ".
               "to_days(postpartum_date) days_pp, to_days(mc_consult_date) days_today, obscore_gp, obscore_fpal, user_id, ".
               "blood_type, patient_age, patient_height, delivery_date, ".
               "outcome_id, birthweight, end_pregnancy_flag ".
               "from m_patient_mc where patient_id = '$patient_id' and mc_id = '".$get_vars["mc_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $mc = mysql_fetch_array($result);
                if ($get_vars["mc_id"]==$mc["mc_id"]) {
                    print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=".$get_vars["mc"]."&mc_id=".$get_vars["mc_id"]."#visit1form'>";
                    print "<table width='280' style='border: 1px dotted black'><tr><td>";
                    print "<span class='tinylight'>";
                    print "REGISTRY ID: <font color='red'>".module::pad_zero($mc["mc_id"], 7)."</font><br/>";
                    print "PATIENT NAME: ".patient::get_name($patient_id)."<br/>";
                    $patient_age = patient::get_age($patient_id);
                    print "AGE: ".(!mc::is_normal_age($patient_age)?"<font color='red'><b>$patient_age</b></font>":$patient_age)."<br/>";
                    print "SCORE: ".mc::check_gp_score($mc["obscore_gp"])." ".mc::check_fpal_score($mc["obscore_fpal"])."<br/>";
                    print "<hr size='1'/>";
                    print "REG DATE: ".$mc["mc_timestamp"]."<br/>";
                    print "REG BY: ".user::get_username($mc["user_id"])."<br/>";
                    print "<hr size='1'/>";
                    print "<table cellpadding='0' cellspacing='0'><tr valign='top'><td>";
                    // column 1
                    print "<span class='tinylight'>";
                    print "LMP: ".$mc["patient_lmp"]."<br/>";
                    print "EDC: ".$mc["patient_edc"]."<br/>";
                    // get AOG according to whether patient has delivered or not
                    // if has delivered, compute AOG according to that
                    // if not compute by consult_date
                    list($aog_wks, $aog_days) = mc::get_aog($mc["mc_id"], (mc::get_delivery_date($mc["mc_id"])=="0000-00-00"?healthcenter::get_consult_date($get_vars["consult_id"]):mc::get_delivery_date($mc["mc_id"])));
                    print "AOG: ".$aog_wks." WKS ".$aog_days." DAYS<br/>";
                    print "</span>";
                    print "</td><td>";
                    // column 2
                    print "<span class='tinylight'>";
                    print "&nbsp;BLOOD TYPE: ".($mc["blood_type"]=="AB"?"<font color='red'><b>".$mc["blood_type"]."</b></font>":$mc["blood_type"])."<br/>";
                    print "&nbsp;HEIGHT (cm): ".(!mc::is_normal_height($mc["patient_height"])?"<font color='red'><b>".$mc["patient_height"]."</b></font>":$mc["patient_height"])."<br/>";
                    list($systolic, $diastolic) = healthcenter::get_blood_pressure($get_vars["consult_id"]);
                    print "&nbsp;SYS BP: ".($systolic?$systolic:"NA")."<br/>";
                    print "&nbsp;DIAS BP: ".($diastolic?$diastolic:"NA")."<br/>";
                    if ($systolic && $diastolic) {
                        print "&nbsp;STAGE: ".healthcenter::hypertension_stage($systolic, $diastolic)."<br/>";
                    }
                    print "</span>";
                    print "</td></tr></table>";
                    print "<hr size='1'/>";
                    print "IMPORTANT DATES:<br/>";
                    print "End of 1st trimester: ".($mc["days_today"]>=$mc["days_trim1"]?"<font color='red'>".$mc["trimester1_date"]."</font>":$mc["trimester1_date"])."<br/>";
                    print "End of 2nd trimester: ".($mc["days_today"]>=$mc["days_trim2"]?"<font color='red'>".$mc["trimester2_date"]."</font>":$mc["trimester2_date"])."<br/>";
                    print "End of 3rd trimester: ".($mc["days_today"]>=$mc["days_trim3"]?"<font color='red'>".$mc["trimester3_date"]."</font>":$mc["trimester3_date"])."<br/>";
                    print "End of postpartum period: ".($mc["days_today"]>=$mc["days_pp"]?"<font color='red'>".$mc["postpartum_date"]."</font>":$mc["postpartum_date"])."<br/>";

					echo "<hr size='1'>";
					echo "Tetanus Toxoid Status: ";
					echo "<font color='red'><b>".mc::get_tt_status($mc["mc_id"],$patient_id,$mc["patient_edc"])."</b></font>";
					echo "</hr>";

                    print "<hr size='1'/>";

                    print "RISK FACTORS:<br/>";
                    $sql_risk = "select c.visit_risk_id, r.hospital_flag, r.monitor_flag ".
                                "from m_consult_mc_visit_risk c, m_lib_mc_risk_factors r ".
                                "where c.visit_risk_id = r.risk_id and ".
                                "c.consult_id = '".$mc["consult_id"]."'";
                    if ($result_risk = mysql_query($sql_risk)) {
                        if (mysql_num_rows($result_risk)) {
                            while ($risk = mysql_fetch_array($result_risk)) {
                                print "<img src='../images/arrow_redwhite.gif' border='0' /> ";
                                print ($risk["hospital_flag"]=="Y"?"<font color='red'>".mc::get_riskfactor_name($risk["visit_risk_id"])."</font>":mc::get_riskfactor_name($risk["visit_risk_id"]))."<br/>";
                            }
                        }
                    }
                    if ($mc["delivery_date"]<>"0000-00-00") {
                        // if registry record closed display the rest
                        print "<hr size='1'/>";
                        print "<table cellspacing='0' cellpadding='0' width='100%' bgcolor='#CCFFCC'><tr><td class='tinylight'>";
                        print "DELIVERY DATE: ".$mc["delivery_date"]."<br/>";
                        print "OUTCOME: ".mc::get_pregnancy_outcome($mc["outcome_id"])."<br/>";
                        print "BIRTH WT (KG): ".$mc["birthweight"]."<br/>";
                        print "</td></tr></table>";
                    }
                    print "<br/>";
                    if ($_SESSION["priv_add"]) {
                        if ($mc["end_pregnancy_flag"]=="N") {
                            // prevent editing of closed registry record
                            print "<input type='submit' name='submitmc' value='Update Visit 1' class='tinylight' style='border: 1px solid black'/> ";
                            print "<input type='submit' name='submitmc' value='Update Postpartum Data Form' class='tinylight' style='border: 1px solid black'/> ";
                        }
                        print "<input type='hidden' name='mc_id' value='".$mc["mc_id"]."'/>";
                    }
                    print "</span>";
                    print "</td></tr></table>";
                    print "</form>";
                }
            }
        }
    }


	function get_tt_status(){
		$arr_tt = array(1=>0,2=>0,3=>0,4=>0,5=>0);
		$tt_duration = array(1=>0,2=>1095,3=>1825,4=>3650,5=>10000); //number of days of effectiveness
		$highest_tt = 0;
		$protected = 0;

		if(func_num_args()>0){
			$arg_list = func_get_args();
			$mc_id = $arg_list[0];
			$pxid = $arg_list[1];
			$pxedc = $arg_list[2];
		}

		for($i=1;$i<=5;$i++){
			$antigen = 'TT'.$i;
			$q_vacc = mysql_query("SELECT MAX(actual_vaccine_date),vaccine_id,mc_id FROM m_consult_mc_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigen' AND actual_vaccine_date<='$pxedc' GROUP by patient_id") or die("Cannot query: 2368");
						
			if(mysql_num_rows($q_vacc)!=0):
				list($vacc_date,$vacc_id,$mcid) = mysql_fetch_array($q_vacc);			
				$arr_tt[$i] = $vacc_date;
			endif;

		}
		
//		print_r($arr_tt);
		
		for($j<=1;$j<=5;$j++){
			
//			echo $arr_tt[$j].'/'.$j.'<br>';
			
			//case 1: use this test case to refer to the highest TT. once a blank TT is considered, the last highest vaccine is considered. this will likely miss higher vaccinations after the blank
			/*if($arr_tt[$j]=='0' && $highest_tt==0):
				$highest_tt = $j-1; //get the previous TT antigen
			endif; */
			
			//case 2: use this scenario to get the highest possible 
			if($arr_tt[$j]!='0'):  
				$highest_tt = $j; //get the previous TT antigen
				$date_tt = $arr_tt[$j];
			endif;
		}

		$highest_tt = ($heighest_tt<5)?$highest_tt:5;				

		if($highest_tt==1 || $highest_tt==0):
			$protected = 0;
		elseif($highest_tt==5):
			$protected = 1;
		else:
			$antigen = 'TT'.$highest_tt;

			$q_diff = mysql_query("SELECT consult_id FROM m_consult_mc_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigen' AND (TO_DAYS('$pxedc')-TO_DAYS(actual_vaccine_date)) <= '$tt_duration[$highest_tt]'") or die("Cannot query: 2399");
			
			if(mysql_num_rows($q_diff)!=0):
					$protected = 1;
			endif;
		endif;

		$tt_stat = 'TT'.$highest_tt;
		$tt_stat.=($protected==1)?' Active':' Not Active';
		$tt_stat.="&nbsp;(".$date_tt.")";
		return $tt_stat;

		
	}


    function display_prenatal_record_detail() {
    //
    // called from _details_mc()
    // lowest level detail about prenatal visit
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
        $sql = "select mc_id, patient_id, consult_id, date_format(prenatal_date, '%a %d %b %Y, %h:%i%p') visit_date, prenatal_date, ".
               "user_id, aog_weeks, trimester, visit_sequence, patient_weight, date_format(prenatal_timestamp, '%a %d %b %Y, %h:%i%p') prenatal_timestamp, ".
               "blood_pressure_systolic, blood_pressure_diastolic, fundic_height, ".
               "presentation, fhr, fhr_location, data_type ".
               "from m_consult_mc_prenatal where patient_id = '$patient_id' and ".
               "mc_id = '".$get_vars["mc_id"]."' and visit_sequence = '".$get_vars["visitseq"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $mc = mysql_fetch_array($result);
                if ($get_vars["mc_id"]==$mc["mc_id"]) {
                    print "<a name='prenatal_detail'>";
                    $bgcolor = ($get_vars["consult_id"]==$mc["consult_id"]?"#FFFFCC":"#FFFFFF");
                    print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=PREN".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]."&visitseq=".$get_vars["visitseq"]."#visit1form":"")."'>";
                    print "<table bgcolor='$bgcolor' width='280' style='border: 1px dotted black'><tr><td>";
                    print "<span class='tinylight'>";
                    //print ($mc["data_type"]=="EXT"?"<font color='red'><b>DATA FROM EXTERNAL RECORDS</b></font><br/>":"")."";
                    print "REGISTRY ID: <font color='red'>".module::pad_zero($mc["mc_id"], 7)."</font><br/>";
                    print "PATIENT NAME: ".patient::get_name($patient_id)."<br/>";
                    print "VISIT DATE: ".$mc["visit_date"]."<br/>";
                    print "VISIT SEQUENCE ".$mc["visit_sequence"]."<br/>";
                    print "TRIMESTER: ".$mc["trimester"]."<br/>";
                    print "<hr size='1'/>";
                    print "LAST UPDATE: ".$mc["prenatal_timestamp"]."<br/>";
                    print "UPDATE BY: ".user::get_username($mc["user_id"])."<br/>";
                    print "<hr size='1'/>";
                    print "<table cellpadding='0' cellspacing='0'><tr valign='top'><td>";
                    // column 1
                    print "<span class='tinylight'>";
                    print "FUNDIC HT (CM): ".$mc["fundic_height"]."<br/>";
                    print "FETAL PRES: ".$mc["presentation"]."<br/>";
                    list($aog_wks, $aog_days) = mc::get_aog($mc["mc_id"], $mc["prenatal_date"]);
                    print "AOG: ".$aog_wks." WKS ".$aog_days." DAYS<br/>";
                    print "FHR (BPM): ".$mc["fhr"]."<br/>";
                    print "LOCATION: ".$mc["fhr_location"]."<br/>";
                    print "</span>";
                    print "</td><td>";
                    // column 2
                    print "<span class='tinylight'>";
                    print "&nbsp;WEIGHT (KG): ".$mc["patient_weight"]."<br/>";
                    print "&nbsp;SYS BP: ".$mc["blood_pressure_systolic"]."<br/>";
                    print "&nbsp;DIAS BP: ".$mc["blood_pressure_diastolic"]."<br/>";
                    print "</span>";
                    print "</td></tr></table>";
                    print "<hr size='1'/>";
                    print "RISK FACTORS:<br/>";
                    $sql_risk = "select c.visit_risk_id, r.hospital_flag, r.monitor_flag ".
                                "from m_consult_mc_visit_risk c, m_lib_mc_risk_factors r ".
                                "where c.visit_risk_id = r.risk_id and ".
                                "c.consult_id = '".$mc["consult_id"]."'";
                    if ($result_risk = mysql_query($sql_risk)) {
                        if (mysql_num_rows($result_risk)) {
                            while ($risk = mysql_fetch_array($result_risk)) {
                                print "<img src='../images/arrow_redwhite.gif' border='0' /> ";
                                print ($risk["hospital_flag"]=="Y"?"<font color='red'>".mc::get_riskfactor_name($risk["visit_risk_id"])."</font>":mc::get_riskfactor_name($risk["visit_risk_id"]))."<br/>";
                            }
                        }
                    }
                    print "<br/>";
                    if ($_SESSION["priv_update"]) {
                        print "<input type='submit' name='submitmc' value='Update Record' class='tinylight' style='border: 1px solid black'/>";
                        print "<input type='hidden' name='prenatal_id' value='".$mc["mc_id"]."'/>";
                        print "<input type='hidden' name='visit_sequence' value='".$mc["visit_sequence"]."'/>";
                    }
                    print "</span>";
                    print "</td></tr></table>";
                    print "</form>";
                }
            }
        }
    }

    function display_postpartum_record_detail() {
    //
    // called from _details_mc()
    // lowest level detail about postpartum visit
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
        $sql = "select mc_id, patient_id, consult_id, date_format(postpartum_date, '%a %d %b %Y, %h:%i%p') postpartum_date, ".
               "user_id, postpartum_week, visit_sequence, date_format(postpartum_timestamp, '%a %d %b %Y, %h:%i%p') postpartum_timestamp, ".
               "blood_pressure_systolic, blood_pressure_diastolic, visit_type, ".
               "breastfeeding_flag, family_planning_flag, fever_flag, ".
               "vaginal_infection_flag, vaginal_bleeding_flag, pallor_flag, cord_ok_flag ".
               "from m_consult_mc_postpartum where patient_id = '$patient_id' and ".
               "mc_id = '".$get_vars["mc_id"]."' and visit_sequence = '".$get_vars["ppvisitseq"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $mc = mysql_fetch_array($result);
                if ($get_vars["mc_id"]==$mc["mc_id"]) {
                    print "<a name='postpartum_detail'>";
                    $bgcolor = ($get_vars["consult_id"]==$mc["consult_id"]?"#FFFFCC":"#FFFFFF");
                    print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=mc&mc=POSTP".($get_vars["mc_id"]?"&mc_id=".$get_vars["mc_id"]."&visitseq=".$get_vars["visitseq"]."#postpartum_visit":"")."'>";
                    print "<table bgcolor='$bgcolor' width='280' style='border: 1px dotted black'><tr><td>";
                    print "<span class='tinylight'>";
                    print "REGISTRY ID: <font color='red'>".module::pad_zero($mc["mc_id"], 7)."</font><br/>";
                    print "PATIENT NAME: ".patient::get_name($patient_id)."<br/>";
                    print "VISIT DATE: ".$mc["postpartum_date"]."<br/>";
                    print "VISIT SEQUENCE: ".$mc["visit_sequence"]."<br/>";
                    print "VISIT TYPE: ".$mc["visit_type"]."<br/>";
                    print "POSTPARTUM WEEK: ".$mc["postpartum_week"]."<br/>";
                    print "<hr size='1'/>";
                    print "LAST UPDATE: ".$mc["postpartum_timestamp"]."<br/>";
                    print "UPDATE BY: ".user::get_username($mc["user_id"])."<br/>";
                    print "<hr size='1'/>";
                    print "VITAL SIGNS:<br/>";
                    print "&nbsp;SYS BP: ".$mc["blood_pressure_systolic"]."<br/>";
                    print "&nbsp;DIAS BP: ".$mc["blood_pressure_diastolic"]."<br/>";
                    print "<br/>";
                    print "POSTPARTUM EVENTS:<br/>";
                    print "&nbsp;Fever >38&deg;C: ".($mc["fever_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "&nbsp;Vaginal Infection: ".($mc["vaginal_infection_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "&nbsp;Vaginal Bleeding: ".($mc["vaginal_bleeding_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "&nbsp;Pallor: ".($mc["pallor_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "&nbsp;Baby's Cord OK: ".($mc["cord_ok_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "<br/>";
                    print "OTHER INFO:<br/>";
                    print "&nbsp;Breastfeeding: ".($mc["breastfeeding_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "&nbsp;Family Planning Method: ".($mc["family_planning_flag"]=="Y"?"Yes":"No")."<br/>";
                    print "<br/>";
                    if ($_SESSION["priv_update"]) {
                        print "<input type='submit' name='submitmc' value='Update Visit' class='tinylight' style='border: 1px solid black'/>";
                        print "<input type='hidden' name='postpartum_id' value='".$mc["mc_id"]."'/>";
                        print "<input type='hidden' name='visit_sequence' value='".$mc["visit_sequence"]."'/>";
                    }
                    print "</span>";
                    print "</td></tr></table>";
                    print "</form>";
                }
            }
        }
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
			if(isset($_POST[actual_service_date]) && isset($_POST[service_qty])):
				list($month,$date,$year) = explode('/',$_POST[actual_service_date]);
				$serv_date = $year.'-'.$month.'-'.$date;
				/*
				$sel_px = mysql_query("SELECT patient_id FROM m_patient_mc WHERE mc_id='$get_vars[mc_id]'") or die("Cannot query: 2197");
				list($px_id) = mysql_fetch_array($sel_px);

				$q_service = mysql_query("SELECT actual_service_date,service_id,mc_timestamp FROM m_consult_mc_services WHERE mc_id='$get_vars[mc_id]' AND patient_id='$px_id' AND actual_service_date='$_POST[actual_service_date]' AND service_id='$get_vars[service_id]' AND mc_timestamp!='$get_vars[sts]'") or die("Cannot query: 2200");
			
				list($date,$sid,$ts) = mysql_fetch_array($q_service);
				echo $_POST[actual_service_date];
				
				if(mysql_num_rows($q_service)==0):
				*/
						$update_service = mysql_query("UPDATE m_consult_mc_services SET actual_service_date='$serv_date',service_qty='$_POST[service_qty]' WHERE service_id='$_POST[service]' AND mc_id='$_POST[mc_id]' AND mc_timestamp='$_POST[sts]'") or die("Cannot query: 2193");
					
						echo "<script language='Javascript'>";
						
						if($update_service):
							echo "window.alert('Service was successfully been updated!')";
						else:
							echo "window.alert('Service was not update.\n Maaring kulang ang date o quantity.')";			
						endif;
						echo "</script>";

/*				else:
					echo 'b';
						echo "<script language='Javascript'>";
						echo "window.alert('Service was not updated. Actual date of service already exists.')";
						echo "</script>";									
				endif; */
				
			else:
				echo "<script language='Javascript'>";
				echo "window.alert('Service was not update.\n Maaring kulang ang date o quantity.')";
				echo "</script>";
			endif;

			break;
			
        case "Delete Service":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_mc_services ".
                       "where service_id = '".$post_vars["service"]."' and ".
                       "mc_id = '".$post_vars["mc_id"]."' and ".
                       "mc_timestamp = '".$post_vars["sts"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&mc_id=".$post_vars["mc_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&mc_id=".$post_vars["mc_id"]);
                }
            }
            break;
        case "Delete Record";
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_mc_vaccine ".
                       "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                       "mc_id = '".$post_vars["mc_id"]."' and ".
                       "vaccine_timestamp = '".$post_vars["ts"]."'";
                if ($result = mysql_query($sql)) {
                    /*$sql_vaccine = "delete from m_consult_vaccine ".
                                   "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                                   "consult_id = '".$post_vars["vaccine_consult_id"]."' and ".
                                   "source_module = 'mc'"; */

					$sql_vaccine = "DELETE FROM m_consult_vaccine WHERE consult_id='$post_vars[vaccine_consult_id]' AND vaccine_id='$post_vars[vaccine]'";


                    $result_vaccine = mysql_query($sql_vaccine) or die(mysql_error());
					
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&mc_id=".$post_vars["mc_id"]);
                }

            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&mc_id=".$post_vars["mc_id"]);
                }
            }
            break;
        case "Update Record";

            $adr = ($post_vars["adr_flag"]?"Y":"N");
            list($month,$day,$year) = explode("/", $post_vars["actual_vaccine_date"]);

            $actual_vaccine_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $patient_dob = patient::get_dob($patient_id);
            $sql = "update m_consult_mc_vaccine set ".
                   "actual_vaccine_date = '$actual_vaccine_date' ".
                   "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                   "mc_id = '".$post_vars["mc_id"]."' and ".
                   "vaccine_timestamp = '".$post_vars["ts"]."'";
            if ($result = mysql_query($sql)) {
                /*$sql_vaccine = "update m_consult_vaccine set ".
                               "actual_vaccine_date = '$actual_vaccine_date' ".
                               "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                               "consult_id = '".$post_vars["vaccine_consult_id"]."' and ".
                               "source_module = 'mc'";
				*/

				$sql_vaccine = "update m_consult_vaccine set ".
                               "actual_vaccine_date = '$actual_vaccine_date' ".
                               "where vaccine_id = '".$post_vars["vaccine"]."' and ".
                               "consult_id = '".$post_vars["vaccine_consult_id"];		
				
                $result_vaccine = mysql_query("UPDATE m_consult_vaccine SET actual_vaccine_date='$actual_vaccine_date' WHERE consult_id='$post_vars[vaccine_consult_id]' AND vaccine_id='$post_vars[vaccine]'") or die("Cannot update: 2247");

                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=SVC&vaccine=".$post_vars["vaccine"]."&ts=".$post_vars["ts"]."&mc_id=".$post_vars["mc_id"]."#vaccine_detail");
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
        print "<a name='vaccine_detail'>";
        print "<b>".FTITLE_VACCINE_RECORD."</b><br/>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select mc_id, vaccine_id, vaccine_timestamp, date_format(actual_vaccine_date,'%a %d %b %Y') ".
               "from m_consult_mc_vaccine ".
               "where patient_id = '$patient_id' order by vaccine_id, vaccine_timestamp desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($cid, $vaccine, $vstamp, $vdate) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=".$get_vars["mc"]."&vaccine=$vaccine&ts=$vstamp&mc_id=$cid#detail'>$vaccine</a> $vdate<br/>";
                    if ($get_vars["vaccine"]==$vaccine && $get_vars["ts"]==$vstamp && $get_vars["mc_id"]==$cid) {
                        mc::display_vaccine_record_details($menu_id, $post_vars, $get_vars);
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
        $sql = "select mc_id, consult_id, user_id, patient_id, vaccine_timestamp, date_format(actual_vaccine_date, '%a %d %b %Y, %h:%i%p'), ".
               "vaccine_id, actual_vaccine_date, date_format(vaccine_timestamp, '%a %d %b %Y, %h:%i%p') ".
               "from m_consult_mc_vaccine where ".
               "mc_id = '".$get_vars["mc_id"]."' and vaccine_id = '".$get_vars["vaccine"]."' and ".
               "vaccine_timestamp = '".$get_vars["ts"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($cdid, $cid, $uid, $pid, $vstamp, $vdate, $vid, $actual_date, $report_date) = mysql_fetch_array($result);
                print "<a name='detail'>";
                print "<table width='250' cellpadding='3' style='border:1px dashed black'><tr><td>";
                print "<form name='form_vaccine_detail' method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&vaccine=$vid&ts=$vstamp'>";
                print "<span class='tinylight'>";
                print "REGISTRY ID: <font color='red'>".module::pad_zero($cdid,7)."</font><br/>";
                print "VACCINE: ".mc::get_vaccine_name($vid)."<br/>";
                print "REPORT DATE: $report_date<br/>";
                print "RECORDED BY: ".user::get_username($uid)."<br/>";
                print "ACTUAL VACCINE DATE:<br/>";
                if ($actual_date<>"0000-00-00") {
                    list($year, $month, $day) = explode("-", $actual_date);
                    $conv_date = "$month/$day/$year";
                }
                print "<input type='text' size='10' maxlength='10' class='tinylight' name='actual_vaccine_date' value='".($conv_date?$conv_date:$post_vars["actual_vaccine_date"])."' style='border: 1px solid #000000'> ";
                print "<a href=\"javascript:show_calendar4('document.form_vaccine_detail.actual_vaccine_date', document.form_vaccine_detail.actual_vaccine_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
                print "<input type='hidden' name='vaccine' value='".$get_vars["vaccine"]."'/>";
                print "<input type='hidden' name='vaccine_consult_id' value='$cid'/>";
                print "<input type='hidden' name='ts' value='".$get_vars["ts"]."'/>";
                print "<input type='hidden' name='mc_id' value='".$get_vars["mc_id"]."'/>";
                print "<br/>";
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

    function display_service_record() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<br/>";
        print "<b>".FTITLE_SERVICE_RECORD."</b><br/>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select mc_id, service_id, date_format(mc_timestamp,'%a %d %b %Y') service_date, mc_timestamp,actual_service_date, date_format(actual_service_date,'%a %d %b %Y') actual_sdate, service_qty ".
               "from m_consult_mc_services ".
               "where patient_id = '$patient_id' order by service_id, actual_service_date desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($cid, $service, $sdate, $ts, $actual_service_date, $actual_sdate, $qty) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
					
					$disp_date = ($actual_service_date=='0000-00-00')?$sdate:$actual_sdate;

                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=mc&mc=SVC&service_id=$service&sts=$ts&mc_id=$cid&actual_vdate=$actual_service_date#service' name='service'>".mc::get_service_name($service)." ($qty)</a> $disp_date<br/>";
					
					if ($get_vars["service_id"]==$service && $get_vars["actual_vdate"]==$actual_service_date && $get_vars["sts"]==$ts) {
                        mc::display_service_record_details($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
    }

    function display_service_record_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select mc_id, consult_id, user_id, patient_id, mc_timestamp, date_format(mc_timestamp, '%a %d %b %Y, %h:%i%p'), ".
               "service_id, visit_type, actual_service_date, service_qty ".
               "from m_consult_mc_services where ".
               "mc_id = '".$get_vars["mc_id"]."' and service_id = '".$get_vars["service_id"]."' and ".
               "mc_timestamp = '".$get_vars["sts"]."'";
				
		$result = mysql_query($sql) or die(mysql_error());

        if ($result) {

            if (mysql_num_rows($result)) {							
				

                while(list($cdid, $cid, $uid, $pid, $cstamp, $sdate, $sid, $vtype, $actual_date, $qty) = mysql_fetch_array($result))
				{
				
                print "<a name='detail'>";
                print "<table width='250' cellpadding='3' style='border:1px dashed black'><tr><td>";
                print "<form name='form_service_detail' method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&mc=".$get_vars["mc"]."&mc_id=".$get_vars["mc_id"]."&sts=".$get_vars["sts"]."&service_id=".$get_vars["service_id"].'#detail'."'>";
                print "<span class='tinylight'>";
				
				print "REGISTRY ID: <font color='red'>".module::pad_zero($cdid,7)."</font><br/>";
                print "SERVICE: ".mc::get_service_name($sid)."<br/>";
                print "VISIT TYPE: $vtype<br/>";
                print "REPORT DATE: $sdate<br/>";
                print "RECORDED BY: ".user::get_username($uid)."<br/>";
				print "DATE SERVICE WAS GIVEN: &nbsp;";

				list($reported_date) = explode(' ',$cstamp);
				$actual = ($actual_date=='0000-00-00')?$reported_date.' <font color=\'red\'>(no date given, report date instead)</font>':$actual_date;
				$disp_date = ($actual_date=='0000-00-00')?$reported_date:$actual_date;
				list($yr,$month,$date) = explode('-',$disp_date);

				$disp =	$month.'/'.$date.'/'.$yr;

				echo $actual.'<br>';
				
				print "SET DATE&nbsp;<input type='text' size='10' class='tinylight' name='actual_service_date' value='$disp' style='border: 1px solid #000000' readonly></input>";
				
				print "<a href=\"javascript:show_calendar4('document.form_service_detail.actual_service_date', document.form_service_detail.actual_service_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br><br>";
				
				print "QUANTITY&nbsp;<input type='text' size='10' class='tinylight' name='service_qty' value='$qty' style='border: 1px solid #000000'></input><br><br>";

                print "<input type='hidden' name='service' value='$sid'/>";
                print "<input type='hidden' name='sts' value='$cstamp'/>";
                print "<input type='hidden' name='mc_id' value='$cdid'/>";
		

				if($_SESSION["priv_update"]):
					print "<input type='submit' name='submitdetail' value='Update Service' class='tinylight' style='border: 1px solid black'/> ";
				endif;

                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdetail' value='Delete Service' class='tinylight' style='border: 1px solid black'/> ";
                }
                print "</span>";
                print "</form>";
                print "</td></tr></table>";
				}
            }
        }
    }

    function get_delivery_date() {
    //
    // gets delivery date from
    // m_patient_mc!
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
        }
        $sql = "select delivery_date from m_patient_mc where mc_id = '$mc_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($delivery_date) = mysql_fetch_array($result);
                return $delivery_date;
            }
        }
    }

    function get_pp_weeks() {
    //
    // calculates postpartum weeks from
    // m_patient_mc (delivery_date)!
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
            $consult_id = $arg_list[1];
        }

        //$consult_date = healthcenter::get_consult_date($consult_id);
        $sql = "select round((to_days(c.postpartum_date) - to_days(m.delivery_date))/7,0) postpartum_weeks ".
               "from m_patient_mc m, m_consult_mc_postpartum c ".
               "where m.consult_id = c.consult_id and m.mc_id = '$mc_id'";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($weeks) = mysql_fetch_array($result);
                return $weeks;
            }
        }
    }

    function get_pp_week(){
	//revised version of the get_pp_weeks function but accepts postpartum visit date as an argument (2009-06-05)
	if(func_num_args()>0){		
		$arg_list = func_get_args();
		$mc_id = $arg_list[0];
		$postpartum_date = $arg_list[1];
	}

	$date_array = explode('/',$postpartum_date);
	$postpartum_date = $date_array[2].'-'.$date_array[0].'-'.$date_array[1];
	
	$get_week = mysql_query("SELECT round((to_days('$postpartum_date') - to_days(delivery_date))/7,0) postpartum_week from m_patient_mc where mc_id='$mc_id'") or die(mysql_error());
	
	if(mysql_num_rows($get_week)>0):
		list($wk) = mysql_fetch_array($get_week);
	        $wk = ($wk==0?1:$wk); //for postpartum visits after 0-6 days of birth, wk count=1
		return $wk;
	endif;
	
    }

    function is_normal_height() {
    //
    // returns true if height > 145cm
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $height = $arg_list[0];
        }
        if ($height>145) {
            return true;
        } else {
            return false;
        }
    }

    function is_normal_age() {
    //
    // returns true if age within 18-35
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $age = $arg_list[0];
        }
        if ($age>=18 && $age<=35) {
            return true;
        } else {
            return false;
        }
    }

    function check_gp_score() {
    //
    // checks GP score for pregnancies
    // of 4 or more.
    // returns formatted string
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $gp_score = $arg_list[0];
        }
        $gravida = substr($gp_score, 1,1);
        $gravida_string = substr($gp_score, 0,2);
        $para_string = substr($gp_score, 2,2);
        if ($gravida>=4) {
            return "<font color='red'><b>$gravida_string</b></font>".$para_string;
        } else {
            return $gp_score;
        }
    }

    function check_fpal_score() {
    //
    // checks FPAL score for abortions
    // of 3 or more.
    // returns formatted string
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $fpal_score = $arg_list[0];
        }
        $F_string = substr($fpal_score, 0,1);
        $P_string = substr($fpal_score, 1,1);
        $A_string = substr($fpal_score, 2,1);
        $L_string = substr($fpal_score, 3,1);
        if ($A_string>=3) {
            return $F_string.$P_string."<font color='red'><b>$A_string</b></font>".$L_string;
        } else {
            return $fpal_score;
        }
    }

    function get_aog() {
    //
    // get_aog()
    // age of gestation
    // returns array (wks, days)
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
            $consult_date = $arg_list[1];
        }
        $sql = "select round((to_days('$consult_date')-to_days(patient_lmp))/7,0) patient_aog, ".
               "MOD((to_days('$consult_date')-to_days(patient_lmp)),7) remainder ".
               "from m_patient_mc ".
               "where mc_id = '$mc_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($wks, $days) = mysql_fetch_array($result);
                return array($wks, $days);
            }
        }
    }

    function get_trimester() {
    //
    // get_trimester()
    // what trimester is patient in?
    // returns trimester integer
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
            $consult_date = $arg_list[1];
        }
        $sql = "select case when (to_days('$consult_date')<=to_days(trimester1_date)) then 1 ".
               "when (to_days('$consult_date')<=to_days(trimester2_date) and to_days('$consult_date')>to_days(trimester1_date)) then 2 ".
               "when to_days('$consult_date')>to_days(trimester2_date) then 3 end ".
               "from m_patient_mc ".
               "where mc_id = '$mc_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($trimester) = mysql_fetch_array($result);
                return $trimester;
            }
        }
    }

    function get_visit_sequence() {
    //
    // get_visit_sequence()
    // what visit sequence is patient in?
    // returns sequence integer
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
            $consult_id = $arg_list[1];
        }
        $sql = "select m.visit_sequence ".
               "from m_consult_mc_prenatal m, m_consult c ".
               "where m.patient_id = c.patient_id and ".
               "m.mc_id = '$mc_id' and c.consult_id = '$consult_id' ".
               "order by m.visit_sequence desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($sequence) = mysql_fetch_array($result);
                return $sequence+1;
            } else {
                return 1;
            }
        }
    }

    function get_ppvisit_sequence() {
    //
    // get_visit_sequence()
    // what visit sequence is patient in for postpartum period?
    // returns sequence integer
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
            $consult_id = $arg_list[1];
        }
        $sql = "select m.visit_sequence ".
               "from m_consult_mc_postpartum m, m_consult c ".
               "where m.patient_id = c.patient_id and ".
               "m.mc_id = '$mc_id' and c.consult_id = '$consult_id' ".
               "order by m.visit_sequence desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($sequence) = mysql_fetch_array($result);
                return $sequence+1;
            } else {
                return 1;
            }
        }
    }

    function check_vaccine_status() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $vaccine_id = $arg_list[0];
            $patient_id = $arg_list[1];
        }
        $sql = "select count(vaccine_id) from m_consult_mc_vaccine ".
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

    function is_pregnancy_terminated() {
    //
    // returns end_pregnancy_flag field
    // true if Y false if N
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $mc_id = $arg_list[0];
        }
        $sql = "select end_pregnancy_flag from m_patient_mc ".
               "where mc_id = '$mc_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($flag) = mysql_fetch_array($result);
                return $flag;
            }
        }
    }

    // ----------------------- LIBRARY METHODS -------------------------------

    function show_delivery_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }

	$q_delivery = mysql_query("SELECT * FROM m_lib_mc_delivery_location") or die(mysql_error());
	$ret_val = "<select name='delivery_location' class='textbox'>";
        $ret_val .= "<option value=''>Select Location</option>";
	while($r_delivery = mysql_fetch_array($q_delivery)){
		$ret_val .= "<option value='$r_delivery[delivery_id]' ".($location_id=="$r_delivery[delivery_id]"?"selected":"").">$r_delivery[delivery_name]</option>";
	}
	$ret_val .= "</select>";

        return $ret_val;
    }

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
            $patient_id = $arg_list[0];
        }
        $sql = "select vaccine_id, vaccine_name from m_lib_mc_vaccines order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $vaccine_status = mc::check_vaccine_status($id, $patient_id);
                    $ret_val .= "<input type='checkbox' name='vaccines[]' value='$id'> $name $vaccine_status<br>";
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
