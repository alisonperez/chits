<?
class ptgroup extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function ptgroup() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "ptgroup";
        $this->description = "CHITS Library - Patient Groups";
        // 0.4: debugged

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
        module::set_lang("FTITLE_PATIENT_GROUPS", "english", "PATIENT GROUP LIST", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_GROUP_MODULE", "english", "GROUP MODULE", "Y");
        module::set_lang("FTITLE_PATIENT_GROUPS_FORM", "english", "PATIENT GROUP FORM", "Y");
        module::set_lang("LBL_GROUP_ID", "english", "GROUP ID", "Y");
        module::set_lang("LBL_GROUP_NAME", "english", "GROUP NAME", "Y");
        module::set_lang("LBL_GROUP_MODULE", "english", "GROUP MODULE", "Y");
        module::set_lang("LBL_DESCRIPTION", "english", "GROUP DESCRIPTION", "Y");
        module::set_lang("LBL_CONDITION", "english", "MODULE CONDITION", "Y");
        module::set_lang("INSTR_CONDITION", "english", "Type in conditional processing method or function.", "Y");

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
        module::set_menu($this->module, "Patient Groups", "LIBRARIES", "_ptgroup");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_ptgroup` (".
               "`ptgroup_id` varchar(10) NOT NULL default '',".
               "`ptgroup_name` varchar(25) NOT NULL default '',".
               "`ptgroup_module` varchar(25) NOT NULL default '',".
               "`ptgroup_condition` varchar(255) NOT NULL default '',".
               "PRIMARY KEY  (`ptgroup_id`),".
               "UNIQUE KEY `ukey_ptgroupname` (`ptgroup_name`)".
               ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_ptgroup_patient` (".
            "`ptgroup_id` varchar(10) NOT NULL default '',".
            "`patient_id` float NOT NULL default '0',".
            "`healthcenter_id` varchar(32) NOT NULL default '',".
            "PRIMARY KEY  (`ptgroup_id`,`patient_id`,`healthcenter_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_ptgroup` (`ptgroup_id`),".
            "CONSTRAINT `m_ptgroup_patient_ibfk_1` FOREIGN KEY (`ptgroup_id`) REFERENCES `m_lib_ptgroup` (`ptgroup_id`) ON DELETE CASCADE, ".
            "CONSTRAINT `m_ptgroup_patient_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO `m_lib_ptgroup` (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) VALUES ('CHILD', 'Child Care', 'ccdev','is_child')");
        module::execsql("INSERT INTO `m_lib_ptgroup` (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) VALUES ('MATERNAL', 'Maternal Care', 'maternalcare','is_female, is_childbearing_age')");
        module::execsql("INSERT INTO `m_lib_ptgroup` (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) VALUES ('OTHER', 'Other', '')");
        module::execsql("INSERT INTO `m_lib_ptgroup` (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) VALUES ('HPN', 'Hypertensive', '')");
        module::execsql("INSERT INTO `m_lib_ptgroup` (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) VALUES ('DIAB', 'Diabetic', '')");
        module::execsql("INSERT INTO `m_lib_ptgroup` (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) VALUES ('NTP', 'TB Therapy', 'ntp')");

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_lib_ptgroup`;");
        module::execsql("DROP TABLE `m_ptgroup_patient`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function module_name() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $id = $arg_list[0];
            $sql = "select ptgroup_module from m_lib_ptgroup where ptgroup_id = '$id'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($name) = mysql_fetch_array($result);
                    return $name;
                }
            }
        }
    }

    function menu_modules() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select l.ptgroup_id, l.ptgroup_module, l.ptgroup_name from m_lib_ptgroup l, m_consult_ptgroup c ".
               "where l.ptgroup_id = c.ptgroup_id and l.ptgroup_module <> '' ".
               "and c.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table cellpadding='0' cellspacing='1' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td>";
                print "<span class='groupmenu'><font color='#666699'><b>GROUP</b></font></span> ";
                while (list($gid, $module, $name) = mysql_fetch_array($result)) {
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&group=$gid' class='groupmenu'>".strtoupper(($get_vars["group"]==$gid?"<b>$name</b>":$name))."</a>";
                }
                print "</td></tr></table>";
            }
        }
    }

    function is_childbearing_age() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $age = $arg_list[0];
            $gender = $arg_list[1];
        }
        if ($age>=9 && $age<=50) {
            if ($gender=="F") {
                return true;
            }
        }
        return false;
    }

    function is_child() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $age = $arg_list[0];
        }
        if ($age<=6) {
            return true;
        } else {
            return false;
        }
    }

    function is_female() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $gender = $arg_list[0];
        }
        if ($gender=="F") {
            return true;
        } else {
            return false;
        }
    }

    function checkbox_ptgroup() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $age = $arg_list[0];
            $gender = $arg_list[1];
        }
        $sql = "select ptgroup_id, ptgroup_name, ptgroup_condition ".
               "from m_lib_ptgroup order by ptgroup_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($id, $name, $cond) = mysql_fetch_array($result)) {
                    if (strlen($cond)>0) {
                        if (eregi('is_childbearing_age',$cond)) {
                            if (ptgroup::is_childbearing_age($age, $gender)) {
                                print "<input type='checkbox' name='ptgroup[]' value='$id'> $name<br/>";
                            }
                        } elseif (eregi('is_child', $cond)) {
                            if (ptgroup::is_child($age)) {
                                print "<input type='checkbox' name='ptgroup[]' value='$id'> $name<br/>";
                            }
                        }
                    } else {
                        print "<input type='checkbox' name='ptgroup[]' value='$id'> $name<br/>";
                    }
                }
            }
        }
    }

    function show_ptgroup() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select ptgroup_id, ptgroup_name from m_lib_ptgroup order by ptgroup_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<select name='ptgroup' class='textbox'>";
                while(list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id'>$name</option>";
                }
                print "</select>";
            }
        }
    }

    function display_ptgroup() {
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
        print "<span class='library'>".FTITLE_PATIENT_GROUPS."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_GROUP_MODULE."</b></td></tr>";
        $sql = "select ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition from m_lib_ptgroup order by ptgroup_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $mod, $cond) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&group_id=$id'>$name</a>".(strlen($cond)>0?" <img src='../images/star.gif' border='0'/>":"")."</td><td>$mod</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function get_ageyears() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $dob = $arg_list[0];
        }
        $sql = "select round((to_days(now())-to_days($dob))/365 , 1) computed_age";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($age) = mysql_fetch_array($result);
                return $age;
            }
        }
    }

    function process_ptgroup() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitptgroup"]) {
            if ($post_vars["group_id"] && $post_vars["group_name"]) {
                switch($post_vars["submitptgroup"]) {
                case "Add Group":
                    $sql = "insert into m_lib_ptgroup (ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition) ".
                           "values ('".$post_vars["group_id"]."', '".$post_vars["group_name"]."', '".$post_vars["module"]."', '".$post_vars["group_condition"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Group":
                    $sql = "update m_lib_ptgroup set ".
                           "ptgroup_name = '".$post_vars["group_name"]."', ".
                           "ptgroup_module = '".$post_vars["module"]."', ".
                           "ptgroup_condition = '".$post_vars["group_condition"]."' ".
                           "where ptgroup_id = '".$post_vars["group_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Group":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_ptgroup where ptgroup_id = '".$post_vars["group_id"]."'";
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

    function form_ptgroup() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["group_id"]) {
                $sql = "select ptgroup_id, ptgroup_name, ptgroup_module, ptgroup_condition ".
                       "from m_lib_ptgroup where ptgroup_id = '".$get_vars["group_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $ptgroup = mysql_fetch_array($result);
                        //print_r($ptgroup);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_PATIENT_GROUPS_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GROUP_ID."</span><br> ";
        if ($ptgroup["ptgroup_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='group_id' value='".$ptgroup["ptgroup_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='group_id' value='".($ptgroup["ptgroup_id"]?$ptgroup["ptgroup_id"]:$post_vars["group_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GROUP_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='group_name' value='".($ptgroup["ptgroup_name"]?$ptgroup["ptgroup_name"]:$post_vars["group_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<br/>";
        print "<span class='boxtitle'>".LBL_GROUP_MODULE."</span><br> ";
        print module::show_modules(($ptgroup["ptgroup_module"]?$ptgroup["ptgroup_module"]:$post_vars["module"]));
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<br/>";
        print "<span class='boxtitle'>".LBL_CONDITION."</span><br> ";
        print "<input type='text' class='textbox' size='30' maxlength='250' name='group_condition' value='".($ptgroup["ptgroup_condition"]?$ptgroup["ptgroup_condition"]:$post_vars["group_condition"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["group_id"]) {
            print "<input type='hidden' name='group_id' value='".$get_vars["group_id"]."'>";
            print "<input type='submit' value = 'Update Group' class='textbox' name='submitptgroup' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Group' class='textbox' name='submitptgroup' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Group' class='textbox' name='submitptgroup' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function _ptgroup() {
        //
        // main method for ptgroup
        // calls form_ptgroup, process_ptgroup, display_ptgroup
        //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ptgroup')) {
            return print($exitinfo);
        }

        if ($post_vars["submitptgroup"]) {
            $this->process_ptgroup($menu_id, $post_vars, $get_vars);
        }
        ptgroup::display_ptgroup($menu_id, $post_vars, $get_vars);
        ptgroup::form_ptgroup($menu_id, $post_vars, $get_vars);
    }

}
?>
