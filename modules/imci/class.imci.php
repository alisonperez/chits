<?
class imci extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // Integrated Management of Childhood Illness

    function imci() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "imci";
        $this->description = "CHITS Module - IMCI";

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "ccdev");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_EDUCATION_FORM", "english", "EDUCATION LEVEL FORM", "Y");
        module::set_lang("LBL_EDUC_ID", "english", "EDUCATION LEVEL ID", "Y");
        module::set_lang("LBL_EDUC_NAME", "english", "EDUCATION LEVEL NAME", "Y");
        module::set_lang("FTITLE_EDUCATION_LEVEL__LIST", "english", "EDUCATION LEVEL LIST", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");

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
        module::set_menu($this->module, "IMCI Dx Class", "LIBRARIES", "_imci_dxclass");
        module::set_menu($this->module, "IMCI Drugs", "LIBRARIES", "_imci_drugs");
        module::set_menu($this->module, "IMCI Drug Class", "LIBRARIES", "_imci_drug_class");
        module::set_menu($this->module, "IMCI Signs", "LIBRARIES", "_imci_signs");
        module::set_menu($this->module, "IMCI Treatment", "LIBRARIES", "_imci_treatment");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // consult instance of IMCI dxclass
        // answers question: what is the classification of this patient in this consult?
        module::execsql("CREATE TABLE `m_consult_imci_dxclass` (".
            "`imci_id` float NOT NULL auto_increment,".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`ccdev_id` float NOT NULL default '0',".
            "`imci_timestamp` timestamp(14) NOT NULL,".
            "`user_id` float NOT NULL default '0',".
            "`imci_class_id` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`imci_id`),".
            "KEY `key_ccdev` (`ccdev_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_imci_class` (`imci_class_id`),".
            "KEY `key_consult` (`consult_id`),".
            "CONSTRAINT `m_consult_imci_class_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_imci_class_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_imci_class_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        // consult instance of drug
        // answers question: what drugs were given for this patient in this consult?
        module::execsql("CREATE TABLE `m_consult_imci_drug` (".
            "`imci_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`ccdev_id` float NOT NULL default '0',".
            "`imci_drug_id` varchar(25) NOT NULL default '',".
            "`user_id` float NOT NULL default '0',".
            "`drug_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`imci_id`,`imci_drug_id`),".
            "KEY `key_ccdev` (`ccdev_id`),".
            "KEY `key_user` (`user_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_drug` (`imci_drug_id`),".
            "CONSTRAINT `m_consult_imci_drug_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_imci_drug_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        // diagnosis class, answers CLASSIFY column of IMCI manual
        module::execsql("CREATE TABLE `m_lib_imci_dxclass` (".
            "`class_id` int(11) NOT NULL default '0',".
            "`class_name` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`class_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_imci_drug` (".
            "`drug_id` varchar(25) NOT NULL default '',".
            "`drug_class` varchar(15) NOT NULL default '',".
            "`drug_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`drug_id`)".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_lib_imci_drugclass` (".
            "`class_id` varchar(15) NOT NULL default '',".
            "`class_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`class_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_imci_sign` (".
            "`sign_id` int(11) NOT NULL default '0',".
            "`sign_name` varchar(10) NOT NULL default '',".
            "`sign_desc` text NOT NULL,".
            "PRIMARY KEY  (`sign_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_imci_treatment` (".
            "`treatment_id` int(11) NOT NULL default '0',".
            "`treatment_name` varchar(10) NOT NULL default '',".
            "`treatment_desc` text NOT NULL,".
            "PRIMARY KEY  (`treatment_id`)".
            ") TYPE=InnoDB;");

        // bridge entity for dxclass and treatment
        module::execsql("CREATE TABLE `m_lib_imci_dxclass_treatment` (".
            "`class_id` int(11) NOT NULL default '0',".
            "`treatment_id` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`class_id`,`treatment_id`),".
            "KEY `key_treatment` (`treatment_id`),".
            "KEY `key_dxclass` (`class_id`),".
            "CONSTRAINT `m_lib_dxclass_treatment_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `m_lib_imci_dxclass` (`class_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_lib_dxclass_treatment_ibfk_2` FOREIGN KEY (`treatment_id`) REFERENCES `m_lib_imci_treatment` (`treatment_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        // bridge entity for sign and dxclass
        module::execsql("CREATE TABLE `m_lib_imci_sign_dxclass` (".
            "`sign_id` int(11) NOT NULL default '0',".
            "`class_id` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`sign_id`,`class_id`),".
            "KEY `key_sign` (`sign_id`),".
            "KEY `key_dxclass` (`class_id`),".
            "CONSTRAINT `m_lib_imci_sign_dxclass_ibfk_1` FOREIGN KEY (`sign_id`) REFERENCES `m_lib_imci_sign` (`sign_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_lib_imci_sign_dxclass_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `m_lib_imci_dxclass` (`class_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_imci_dxclass_treatment`;");
        module::execsql("DROP TABLE `m_lib_imci_sign_dxclass`;");
        module::execsql("DROP TABLE `m_consult_imci_dxclass`;");
        module::execsql("DROP TABLE `m_consult_imci_drug`;");
        module::execsql("DROP TABLE `m_lib_imci_drug`;");
        module::execsql("DROP TABLE `m_lib_imci_drugclass`;");
        module::execsql("DROP TABLE `m_lib_imci_dxclass`;");
        module::execsql("DROP TABLE `m_lib_imci_sign`;");
        module::execsql("DROP TABLE `m_lib_imci_treatment`;");

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_imci() {
    //
    // main submodule for education
    // calls form_education()
    //       display_education()
    //       process_education()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('imci')) {
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
        // check if age correct
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        if (!ptgroup::is_child(patient::get_age($patient_id))) {
            return print("<font color='red'>Module inappropriate for age.</font><br/>");
        }
    }

    function _details_imci() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // check if age correct
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        if (!ptgroup::is_child(patient::get_age($patient_id))) {
            return print("<font color='red'>Module inappropriate for age.</font><br/>");
        }
    }

// ------------------ IMCI LIBRARY METHODS ------------------------

    function _imci_dxclass() {
    //
    // IMCI diagnosis class
    // the CLASSIFY column in IMCI manual
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
    }

    function _imci_sign() {
    //
    // IMCI sign
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
    }

    function _imci_treatment() {
    //
    // IMCI treatment
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
    }

    function _imci_drug() {
    //
    // IMCI drug
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
    }

// end of class
}
?>
