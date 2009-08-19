<?
class complaint extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function complaint() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "complaint";
        $this->description = "CHITS Library - Chief Complaint";
        // 0.2 installed foreign key constraints

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("LBL_COMPLAINTCAT", "english", "COMPLAINT CATEGORY", "Y");
        module::set_lang("FTITLE_CONSULT_COMPLAINT", "english", "CONSULT COMPLAINT", "Y");
        module::set_lang("LBL_COMPLAINT", "english", "COMPLAINT", "Y");
        module::set_lang("LBL_COMPLAINT_MODULE", "english", "COMPLAINT MODULE", "Y");
        module::set_lang("THEAD_MODULE", "english", "COMPLAINT MODULE", "Y");
        module::set_lang("LBL_COMPLAINT_ID", "english", "COMPLAINT ID", "Y");
        module::set_lang("LBL_COMPLAINT_NAME", "english", "COMPLAINT NAME", "Y");
        module::set_lang("FTITLE_COMPLAINT_LIST", "english", "COMPLAINT LIST", "Y");

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
        module::set_menu($this->module, "Complaint", "LIBRARIES", "_complaint");
        
        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // this table relates complaint to loadable modules:
        // imci_cough, imci_diarrhea, imci_fever
        module::execsql("CREATE TABLE `m_lib_complaint` (".
               "`complaint_id` varchar(10) NOT NULL default '',".
               "`complaint_module` varchar(25) NOT NULL default '',".
               "`complaint_name` varchar(100) NOT NULL default '',".
               "PRIMARY KEY  (`complaint_id`), ".
               ") TYPE=InnoDB;");

        // load initial data
        module::execsql("insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('COUGH', 'Cough', 'imci')");
        module::execsql("insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('DIARHEA', 'Diarrhea', 'imci')");
        module::execsql("insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('FEVER', 'Fever', 'imci')");
        module::execsql("insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('INJURY', 'Injury', 'injury')");

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_lib_complaint`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function module_name() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $id = $arg_list[0];
            $sql = "select complaint_module from m_lib_complaint where complaint_id = '$id'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($name) = mysql_fetch_array($result);
                    return $name;
                }
            }
        }
    }

    function menu_modules() {
    //
    // sets up complaint modules as menu
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select l.complaint_id, l.complaint_module, l.complaint_name from m_lib_complaint l, m_consult_complaint c ".
               "where l.complaint_id = c.complaint_id and l.complaint_module <> '' ".
               "and c.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table cellpadding='0' cellspacing='1' bgcolor='#99CC99' style='border: 1px solid black'><tr><td>";
                print "<span class='complaintmenu'><font color='#006666'><b>COMPLAINT</b></font></span> ";
                while (list($cid, $module, $name) = mysql_fetch_array($result)) {
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&complaint=$cid' class='complaintmenu'>".strtoupper(($get_vars["complaint"]==$cid?"<b>$name</b>":$name))."</a>";
                }
                print "</td></tr></table>";
            }
        }
    }

    function _complaint() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('healthcenter')) {
            return print($exitinfo);
        }
        if ($post_vars["submitcomplaint"]) {
            $this->process_complaint($menu_id, $post_vars, $get_vars);
        }
        $this->display_complaint($menu_id, $post_vars, $get_vars);
        $this->form_complaint($menu_id, $post_vars, $get_vars);
    }

    function display_complaint() {
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
        print "<span class='library'>".FTITLE_COMPLAINT_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_MODULE."</b></td></tr>";
        $sql = "select complaint_id, complaint_name, complaint_module from m_lib_complaint order by complaint_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $module) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&complaint_id=$id'>$name</a></td><td>$module</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function process_complaint() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        switch ($post_vars["submitcomplaint"]) {
        case "Add Complaint":
            if ($post_vars["complaint_id"] && $post_vars["complaint_name"]) {
                $sql = "insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) ".
                       "values ('".$post_vars["complaint_id"]."', '".$post_vars["complaint_name"]."', '".$post_vars["module"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Update Complaint":
            if ($post_vars["complaint_id"] && $post_vars["complaint_name"]) {
                $sql = "update m_lib_complaint set ".
                       "complaint_name = '".$post_vars["complaint_name"]."', ".
                       "complaint_module = '".$post_vars["module"]."' ".
                       "where complaint_id = '".$post_vars["complaint_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Complaint":
            if ($post_vars["complaint_id"]) {
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_lib_complaint where complaint_id = '".$post_vars["complaint_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"]);
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"]);
                    }
                }
            }
            break;
        }
    }

    function form_complaint() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
            if ($get_vars["complaint_id"]) {
                $sql = "select complaint_id, complaint_name, complaint_module ".
                       "from m_lib_complaint where complaint_id = '".$get_vars["complaint_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $complaint = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_injurycodes' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_CONSULT_COMPLAINT."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_COMPLAINT_ID."</span><br> ";
        print "<input type='text' size='15' maxlength='20' class='textbox' name='complaint_id' value='".($complaint["complaint_id"]?$complaint["complaint_id"]:$post_vars["complaint_id"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_COMPLAINT_NAME."</span><br> ";
        print "<input type='text' size='15' maxlength='20' class='textbox' name='complaint_name' value='".($complaint["complaint_name"]?$complaint["complaint_name"]:$post_vars["complaint_name"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_COMPLAINT_MODULE."</span><br> ";
        print module::show_modules($complaint["complaint_module"]?$complaint["complaint_module"]:$post_vars["module"]);
        print "<br/><br/></td></tr>";
        print "<tr><td>";
        if ($get_vars["complaint_id"]) {
            print "<input type='hidden' name='complaint_id' value='".$get_vars["complaint_id"]."'>";
            print "<input type='submit' value = 'Update Complaint' class='textbox' name='submitcomplaint' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Complaint' class='textbox' name='submitcomplaint' style='border: 1px solid #000000'> ";
        } else {
            print "<br><input type='submit' value = 'Add Complaint' class='textbox' name='submitcomplaint' style='border: 1px solid #000000'><br> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }
    function show_complaintcat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select complaint_id, complaint_name from m_lib_complaint order by complaint_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<select name='complaint' class='textbox'>";
                while(list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id'>$name</option>";
                }
                print "</select>";
            }
        }
    }

    function checkbox_complaintcat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $complaint_id = $arg_list[0];
        }
        $sql = "select complaint_id, complaint_name from m_lib_complaint order by complaint_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($id, $name) = mysql_fetch_array($result)) {
                    print "<input type='checkbox' name='complaintcat[]' value='$id'> $name<br/>";
                }
            }
        }
    }

// end of class
}
?>
