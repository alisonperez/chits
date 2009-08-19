<?
class tcl extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function tcl() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "tcl";
        $this->description = "CHITS Module - Target Client List";

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

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_tcl` (".
            "`tcl_id` int(11) NOT NULL auto_increment,".
            "`tcl_name` varchar(100) NOT NULL default '',".
            "`tcl_module` varchar(25) NOT NULL default '',".
            "`tcl_directory` varchar(50) NOT NULL default '',".
            "`tcl_filename` varchar(50) NOT NULL default '',".
            "`user_id` float NOT NULL default '0',".
            "`tcl_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`tcl_id`),".
            "KEY `key_module` (`tcl_module`),".
            "CONSTRAINT `m_lib_tcl_ibfk_1` FOREIGN KEY (`tcl_module`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        // load initial data
        // none to load

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_tcl`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function tcl_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<span class='groupmenu'><font color='#666699'><b>TCL TYPE</b></font></span> ";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&tcl=REG&module=".$get_vars["module"]."' class='groupmenu'>".strtoupper(($get_vars["graph"]=="REG"?"<b>REGISTRY</b>":"REGISTRY"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&tcl=STATS&module=".$get_vars["module"]."' class='groupmenu'>".strtoupper(($get_vars["graph"]=="STATS"?"<b>STATS</b>":"STATS"))."</a>";
        print "</td></tr></table>";
    }



// end of class
}
?>
