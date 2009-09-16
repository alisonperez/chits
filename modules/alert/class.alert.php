<?
class alert extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function alert() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.1-".date("Y-m-d");
        $this->module = "alert";
        $this->description = "CHITS Module - Vaccine";
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

        module::set_menu($this->module, "Define Alerts", "LIBRARIES", "_alerts");

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

        module::execsql("CREATE TABLE `m_patient_alert` (".
            "`alert_id` bigint(20) NOT NULL auto_increment,".
            "`alert_name` varchar(100) NOT NULL default '',".
            "`alert_table` varchar(50) NOT NULL default '',".
            "`alert_field` varchar(50) NOT NULL default '',".
            "`alert_condition` varchar(100) NOT NULL default '',".
            "`alert_message` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`alert_id`)".
            ") TYPE=InnoDB; ");

        module::load_sql("alerts.sql");
    }

    function drop_tables() {
    //
    // called from delete_module()
    //
        module::execsql("DROP TABLE `m_patient_alert`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _alerts() {
    //
    // main method for patient alerts
    // called from database menu entry
    // calls form_alert(), process_alert(), display_alerts()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('alert')) {
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
            $this->process_alert($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        $this->display_alerts($menu_id);
        $this->form_alert($menu_id, $post_vars, $get_vars, $isadmin);
    }

    function display_alerts() {
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
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_VACCINES."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_DESCRIPTION."</b></td></tr>";
        $sql = "select vaccine_id, vaccine_name, vaccine_desc from m_lib_vaccine order by vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $desc) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&vaccine_id=$id'>$name</a></td><td>$desc</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function form_alert() {
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
                $sql = "select vaccine_id, vaccine_name, vaccine_desc ".
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
        print "<b>NOTE: Fill up the following form with the correct values.</b>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_ID."</span><br> ";
        print "<input type='text' class='textbox' size='5' maxlength='10' name='vaccine_id' value='".($vaccine["vaccine_id"]?$vaccine["vaccine_id"]:$post_vars["vaccine_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_VACCINE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='vaccine_name' value='".($vaccine["vaccine_name"]?$vaccine["vaccine_name"]:$post_vars["pain_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["vaccine_id"]) {
            print "<input type='hidden' name='vaccine_id' value='".$get_vars["vaccine_id"]."'>";
            print "<input type='submit' value = 'Update Vaccine' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Vaccine' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Vaccine' class='textbox' name='submitvaccine' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_alert() {
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
                switch($post_vars["submitvaccine"]) {
                case "Add Vaccine":
                    $sql .= "insert into m_lib_vaccine (vaccine_id, vaccine_name, vaccine_desc) ";
                    $sql .= "values ('".$post_vars["vaccine_id"]."', '".$post_vars["vaccine_name"]."', '".$post_vars["vaccine_desc"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Vaccine":
                    $sql = "update m_lib_vaccine set ".
                           "vaccine_name = '".$post_vars["vaccine_name"]."', ".
                           "vaccine_desc = '".$post_vars["vaccine_desc"]."' ".
                           "where vaccine_id = '".$post_vars["vaccine_id"]."'";
                    $result = mysql_query($sql);
                    break;
                case "Delete Vaccine":
                    $sql = "delete from m_lib_vaccine ".
                           "where vaccine_id = '".$post_vars["vaccine_id"]."'";
                    $result = mysql_query($sql);
                    break;
                }
            }
        }
    }

}
?>
