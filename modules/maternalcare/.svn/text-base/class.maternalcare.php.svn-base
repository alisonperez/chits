<?
class maternalcare extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function maternalcare() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.1-".date("Y-m-d");
        $this->module = "maternalcare";
        $this->description = "CHITS Module - Maternal Care";
        // 0.1: BEGIN
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "vaccine");
        module::set_dep($this->module, "alert");

    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    // 1. refer to table term
    // 2. skip remarks and translationof since this term is manually entered
    //
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_DESCRIPTION", "english", "DESCRIPTION", "Y");
        module::set_lang("FTITLE_VACCINES", "english", "VACCINES", "Y");
        module::set_lang("FTITLE_VACCINE_FORM", "english", "VACCINE FORM", "Y");
        module::set_lang("LBL_VACCINE_ID", "english", "VACCINE ID", "Y");
        module::set_lang("LBL_VACCINE_NAME", "english", "VACCINE NAME", "Y");

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

        module::set_menu($this->module, "Maternal Care", "STATS", "_maternalcare_stats");

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

        module::execsql("CREATE TABLE `m_patient_pregnancy` (".
            "`pregnancy_id` float NOT NULL auto_increment,".
            "`patient_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`pregnancy_timestamp` timestamp(14) NOT NULL,".
            "`patient_lmp` date NOT NULL default '0000-00-00',".
            "`patient_edc` date NOT NULL default '0000-00-00',".
            "`delivery_date` date default '0000-00-00',".
            "`delivery_type` char(1) NOT NULL default 'N',".
            "`delivery_location` varchar(10) NOT NULL default '',".
            "`healthy_baby` char(1) default 'Y',".
            "`birthweight` int(11) NOT NULL default '0',".
            "`birthmode` varchar(5) NOT NULL default '',".
            "`breastfeeding_asap` char(1) NOT NULL default 'Y',".
            "`user_id` int(11) NOT NULL default '0',".
            "`blood_type` char(3) NOT NULL default '',".
            "`patient_age` int(11) NOT NULL default '0',".
            "`patient_height` float NOT NULL default '0',".
            "`previous_pregnancies` int(11) NOT NULL default '0',".
            "`previous_cesarean` char(1) NOT NULL default '',".
            "`three_miscarriages` char(1) NOT NULL default '',".
            "`stillbirth` char(1) NOT NULL default '',".
            "`postpartum_hge` char(1) NOT NULL default '',".
            " PRIMARY KEY  (`pregnancy_id`)".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_pregnancy` (".
            "`consult_id` float NOT NULL default '0',".
            "`pregnancy_id` float NOT NULL default '0',".
            "`next_visit` date NOT NULL default '0000-00-00',".
            "`trimester` smallint(6) NOT NULL default '0',".
            "`aog` float NOT NULL default '0',".
            "`toxoid_given` int(11) NOT NULL default '0',".
            "`vaginal_bleeding` char(1) NOT NULL default 'N',".
            "`patient_weight` float NOT NULL default '0',".
            "`hypertension` char(1) NOT NULL default 'N',".
            "`fever` char(1) NOT NULL default 'N',".
            "`pallor` char(1) NOT NULL default 'N',".
            "`abn_fundic_ht` char(1) NOT NULL default 'N',".
            "`abn_presentation` char(1) NOT NULL default '',".
            "`no_fetal_heartbeat` char(1) NOT NULL default 'N',".
            "`edema` char(1) NOT NULL default '',".
            "`vaginal_infection` char(1) NOT NULL default 'N',".
            "PRIMARY KEY  (`consult_id`,`pregnancy_id`)".
            ") TYPE=InnoDB; ");

    }

    function drop_tables() {
    //
    // called from delete_module()
    //
        module::execsql("DROP TABLE `m_patient_pregnancy`;");
        module::execsql("DROP TABLE `m_consult_pregnancy`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _maternalcare_stats() {
    }

    function _consult_maternalcare() {
    //
    // main submodule for maternal care consult
    // 1. first visit
    // 2. follow up
    // 3. post-partum
    // 4. home visit by BHW
    //

    static $visit_type;     // type of visit
    static $mc;             // maternalcare class

    $mc = new maternalcare;

        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('maternalcare')) {
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
        // check type of visit
        // then field to appropriate page
        $visit_type = $mc->_visit_type($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        switch($visit_type) {
        case "first":
            $mc->_visit_first();
            break;
        case "followup":
            $mc->_visit_prenatal();
            break;
        case "postpartum":
            $mc->_visit_postpartum();
            break;
        }
    }

    function _visit_type() {
    //
    // visit type
    // 1. first
    // 2. followup prenatals
    // 3. postpartum
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
        print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&group=".$get_vars["group"]."'>";
        print "<select name='visit_type' onchange='this.form.submit();' class='textbox'>";
        print "<option value=''>Select Type of Visit</option>";
        print "<option value='first' ".($post_vars["visit_type"]=="first"?"selected":"").">First Visit</option>";
        print "<option value='ffup' ".($post_vars["visit_type"]=="ffup"?"selected":"").">Followup Prenatal</option>";
        print "<option value='ppart' ".($post_vars["visit_type"]=="ppart"?"selected":"").">Followup PostPartum</option>";
        print "</select>";
        print "</form>";
        return $post_vars["visit_type"];
    }

    function _visit_first() {
    //
    // get first visit data
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
    }

    function _visit_prenatal() {
    //
    // get prenatal visit data
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
    }

    function _visit_postpartum() {
    //
    // get postpartum data
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
    }
// end of class
}
?>
