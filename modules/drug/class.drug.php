<?
session_start();

class drug extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function drug() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "drug";
        $this->description = "CHITS Module - Drug Inventory";

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");

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
        module::set_menu($this->module, "Drug Formulation", "LIBRARIES", "_drug_formulation");
        module::set_menu($this->module, "Drug Preparation", "LIBRARIES", "_drug_preparation");
        module::set_menu($this->module, "Drug Manufacturer", "LIBRARIES", "_drug_manufacturer");
        module::set_menu($this->module, "Drug Source", "LIBRARIES", "_drug_source");
        module::set_menu($this->module, "Drugs", "LIBRARIES", "_drugs");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_drug_category` (".
            "`cat_id` varchar(10) NOT NULL default '',".
            "`cat_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`cat_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ABIO','Antibiotics');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('AHELM','Anti-helminthic');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('AHIST','Antihistamines');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('AHPN','Anti-hypertensives');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ANALG','Analgesic/Anti-inflammatory');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ANTITB','Antituberculous Agents');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('APYR','Antipyretic');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ASPASM','Antispasmodic Agents');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ASTHMA','Anti-asthmatics');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('CONTR','Contraceptive Agents');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('EYE','Opthalmic Solutions/Drops');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('HYDR','Rehydration Solutions');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('VIT','Vitamins');");

        module::execsql("CREATE TABLE `m_lib_drug_formulation` (".
            "`form_id` int(11) NOT NULL auto_increment,".
            "`form_name` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`form_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (1,'125mg/5ml x 100ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (2,'250mg/5ml x 100ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (3,'200mg + 40mg / 5ml x 100ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (4,'100mg/ml x 30ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (5,'100mg/ml x 60ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (6,'100mg/ml x 10ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (7,'500mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (8,'500mg/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (9,'100,000IU/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (10,'200,000IU/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (11,'800mg + 160mg / tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (12,'400mg + 80mg / tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (13,'2mg/5ml x 60ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (14,'400mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (15,'60mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (16,'2mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (17,'27.9g/sachet (see sachet for details)');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (18,'10,000IU/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (19,'150mg/ml x 1 vial');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (20,'0.3mg norgestrel + 0.03mg ethinyl estradiol/tab x 21 tabs');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (21,'Type 1, R450mg x 7 caps + I300mg x 7 tabs + P500mg x 14 tabs / blister pack');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (22,'Type 2, R450mg x 7 caps + I300mg x 7 tabs / blister pack');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (23,'1gm/vial');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (24,'200mg/5ml x 60ml');");

        module::execsql("CREATE TABLE `m_lib_drug_manufacturer` (".
            "`manufacturer_id` varchar(10) NOT NULL default '',".
            "`manufacturer_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`manufacturer_id`)".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_lib_drug_preparation` (".
            "`prep_id` varchar(10) NOT NULL default '',".
            "`prep_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`prep_id`)".
            ") TYPE=MyISAM;");

        // load initial data
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('SUSP','Suspension');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('TAB','Tablet');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('CAP','Capsule');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('BPACK','Blister pack');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('SACH','Sachet');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('VIAL','Vial');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('NEB','Nebule/Respule');");

        module::execsql("CREATE TABLE `m_lib_drug_source` (".
            "`source_id` varchar(10) NOT NULL default '',".
            "`source_name` varchar(40) NOT NULL default '',".
            "PRIMARY KEY  (`source_id`)".
            ") TYPE=InnoDB; ");

        // load initial data

        module::execsql("INSERT INTO m_lib_drug_source VALUES ('CDS','DOH');");
        module::execsql("INSERT INTO m_lib_drug_source VALUES ('LGU','LGU');");

        module::execsql("CREATE TABLE `m_lib_drugs` (".
            "`drug_id` varchar(10) NOT NULL default '',".
            "`drug_cat` varchar(10) NOT NULL default '',".
            "`drug_name` varchar(50) NOT NULL default '',".
            "`drug_preparation` varchar(10) NOT NULL default '',".
            "`drug_formulation` varchar(10) NOT NULL default '',".
            "`manufacturer_id` varchar(10) NOT NULL default '',".
            "`drug_source` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`drug_id`)".
            ") TYPE=InnoDB;");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_drug_category`;");
        module::execsql("DROP TABLE `m_lib_drug_formulation`;");
        module::execsql("DROP TABLE `m_lib_drug_manufacturer`;");
        module::execsql("DROP TABLE `m_lib_drug_preparation`;");
        module::execsql("DROP TABLE `m_lib_drug_source`;");
        module::execsql("DROP TABLE `m_lib_drugs`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_drug() {
    //
    // main submodule for consult drug
    // left panel
    //
        // always check dependencies

        if ($exitinfo = $this->missing_dependencies('drug')) {
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
        $d = new drug;
        $d->drug_menu($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        if ($post_vars["submitdrug"]) {
            $d->process_drug($menu_id, $post_vars, $get_vars);
        }

    }

    function drug_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["drug"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&drug=DISP".($get_vars["drug_id"]?"&drug_id=".$get_vars["drug_id"]:""));
        }

        
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        /*print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=drug&drug=DISP".($get_vars["drug_id"]?"&drug_id=".$get_vars["drug_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["drug"]=="DISP"?"<b>DISPENSING</b>":"DISPENSING"))."</a>"; */

		print "<a href=\"$PHP_SELF\">".strtoupper(($get_vars["drug"]=="DISP"?"<b>DISPENSING</b>":"DISPENSING"))."</a>";

        if ($get_vars["drug_id"]) {
        }
        print "</td></tr></table><br/>";
    }

    function _details_drug() {
    //
    // main submodule for consult drug
    // right panel
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('drug')) {
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

// end of class
}
?>
