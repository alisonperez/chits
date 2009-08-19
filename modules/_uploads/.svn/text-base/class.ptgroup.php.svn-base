<?
class ptgroup extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function ptgroup() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.1-".date("Y-m-d");
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        print $sql = "insert into chits.module_dependencies (module_id, req_module, module_author) ".
               "values ('ptgroup','module', '".$this->author."')";
        $result = mysql_query($sql);

    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    // 1. refer to table chits.term
    // 2. skip remarks and translationof since this term is manually entered
    //

    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries
        $sql = "insert into chits.module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('ptgroup', 'Patient Groups', 'LIBRARIES', '_ptgroup')";
        $result = mysql_query($sql);

        $sql = "insert into chits.module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('ptgroup', 'Groups', 'PATIENTS', '_patient_ptgroups')";
        $result = mysql_query($sql);

        // add more detail
        $sql = "update chits.modules set module_desc = 'CHITS Library - Patient Groups', ".
               "module_version = '".$this->version."', module_author = '".$this->author."' ".
               "where module_id = 'ptgroup';";
        $result = mysql_query($sql);
    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        $sql = "CREATE TABLE `m_lib_ptgroup` (".
               "`ptgroup_id` varchar(10) NOT NULL default '',".
               "`ptgroup_name` varchar(25) NOT NULL default '',".
               "`ptgroup_desc` varchar(255) NOT NULL default '',".
               "PRIMARY KEY  (`ptgroup_id`),".
               "UNIQUE KEY `ukey_ptgroupname` (`ptgroup_name`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_ptgroup_patient` (".
               "`ptgroup_id` varchar(10) NOT NULL default '',".
               "`patient_id` int(11) NOT NULL default '0',".
               "`healthcenter_id` varchar(32) NOT NULL default '',".
               "PRIMARY KEY  (`ptgroup_id`,`patient_id`,`healthcenter_id`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);

        // load initial data
        $sql = "insert into chits.m_lib_ptgroup (ptgroup_id, ptgroup_name) values ('WELLBB', 'Well Baby')";
        $result = mysql_query($sql);
        $sql = "insert into chits.m_lib_ptgroup (ptgroup_id, ptgroup_name) values ('SICKBB', 'Sick Baby')";
        $result = mysql_query($sql);
        $sql = "insert into chits.m_lib_ptgroup (ptgroup_id, ptgroup_name) values ('POSTPART', 'Post-partum')";
        $result = mysql_query($sql);
        $sql = "insert into chits.m_lib_ptgroup (ptgroup_id, ptgroup_name) values ('PRENATAL', 'Prenatal')";
        $result = mysql_query($sql);

    }

    function drop_tables() {
        $sql = "DROP TABLE m_lib_ptgroup;";
        $result = mysql_query($sql);

        $sql = "DROP TABLE m_ptgroup_patient;";
        $result = mysql_query($sql);
    }

    function check_deps() {

        $sql = "select req_module from chits.module_dependencies where module_id = 'ptgroup'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($module_name) = mysql_fetch_array($result)) {
                    if (!class_exists("$module_name")) {
                        return false;
                    }
                }
                return true;
            }
        }
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function show_ptgroup() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select ptgroup_id, ptgroup_name from chits.m_lib_ptgroup order by ptgroup_name";
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
        print "<span class='library'>PATIENT GROUPS</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td><td><b>DESCRIPTION</b></td></tr>";
        $sql = "select ptgroup_id, ptgroup_name, ptgroup_desc from chits.m_lib_ptgroup order by ptgroup_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $desc) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&ptgroup_id=$id'>$name</a></td><td>$desc</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function process_ptgroup() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        if ($post_vars["submitptgroup"]) {
            if ($post_vars["group_id"] && $post_vars["group_name"]) {
                switch($post_vars["submitptgroup"]) {
                case "Add Group":
                    $sql = "insert into chits.m_lib_ptgroup (ptgroup_id, ptgroup_name, ptgroup_desc) ".
                           "values ('".$post_vars["group_id"]."', '".$post_vars["group_name"]."', '".$post_vars["group_desc"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Group":
                    $sql = "update chits.m_lib_ptgroup set ".
                           "ptgroup_name = '".$post_vars["group_name"]."', ".
                           "ptgroup_desc = '".$post_vars["group_desc"]."', ".
                           "where ptgroup_id = '".$post_vars["group_id"]."'";
                    $result = mysql-query($sql);
                    break;
                case "Delete Group":
                    $sql = "delete from chits.m_lib_ptgroup where ptgroup_id = '".$post_vars["group_id"]."'";
                    $result = mysql_query($sql);
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
                print $sql = "select ptgroup_id, ptgroup_name, ptgroup_desc ".
                       "from chits.m_lib_ptgroup where ptgroup_id = '".$get_vars["group_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $ptgroup = mysql_fetch_array($result);
                        print_r($ptgroup);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>PATIENT GROUPS FORM</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>GROUP ID</span><br> ";
        if ($ptgroup["ptgroup_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='group_id' value='".$ptgroup["ptgroup_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='5' $disable maxlength='5' name='group_id' value='".($ptgroup["group_id"]?$ptgroup["group_id"]:$post_vars["group_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>GROUP NAME</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='group_name' value='".($ptgroup["ptgroup_name"]?$ptgroup["ptgroup_name"]:$post_vars["group_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>DESCRIPTION</span><br> ";
        print "<input type='text' class='textbox' size='30' maxlength='250' name='group_desc' value='".($ptgroup["ptgroup_desc"]?$ptgroup["ptgroup_desc"]:$post_vars["group_desc"])."' style='border: 1px solid #000000'><br>";
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
            //header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        ptgroup::display_ptgroup($menu_id);
        ptgroup::form_ptgroup($menu_id, $post_vars, $get_vars);
    }

}
?>
