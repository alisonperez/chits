<?
class education extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function education() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "education";
        $this->description = "CHITS Module - Education";

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
        module::set_menu($this->module, "Education", "LIBRARIES", "_education");
        module::set_menu($this->module, "Education", "STATS", "_education_stats");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_education` (".
            "`educ_id` varchar(10) NOT NULL default '',".
            "`educ_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`educ_id`)".
            ") TYPE=InnoDB;  ");

        // load initial data
        module::execsql("INSERT INTO m_lib_education (educ_id, educ_name) VALUES ('PRIM', 'Elementary')");
        module::execsql("INSERT INTO m_lib_education (educ_id, educ_name) VALUES ('SEC', 'High School')");
        module::execsql("INSERT INTO m_lib_education (educ_id, educ_name) VALUES ('COLL', 'College')");
        module::execsql("INSERT INTO m_lib_education (educ_id, educ_name) VALUES ('GRAD', 'Graduate Studies')");
        module::execsql("INSERT INTO m_lib_education (educ_id, educ_name) VALUES ('VOC', 'Vocational')");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_education`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _education() {
    //
    // main submodule for education
    // calls form_education()
    //       display_education()
    //       process_education()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('education')) {
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
        $e = new education;
        if ($post_vars["submiteducation"]) {
            $e->process_education($menu_id, $post_vars, $get_vars);
        }
        $e->display_education($menu_id, $post_vars, $get_vars);
        $e->form_education($menu_id, $post_vars, $get_vars);
    }

    function show_education() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $educ_id = $arg_list[0];
        }
        $sql = "select educ_id, educ_name from m_lib_education order by educ_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select size='10' name='education' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($educ_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_education_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $educ_id = $arg_list[0];
        }
        $sql = "select educ_name from m_lib_education where educ_id = '$educ_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_education() {
    //
    // called by _education()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["educ_id"]) {
                $sql = "select educ_id, educ_name ".
                       "from m_lib_education where educ_id = '".$get_vars["educ_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $education = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_education' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_EDUCATION_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_EDUC_ID."</span><br> ";
        if ($get_vars["educ_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='educ_id' value='".$education["educ_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='educ_id' value='".($education["educ_id"]?$education["educ_id"]:$post_vars["educ_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_EDUC_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='occup_name' value='".($education["educ_name"]?$education["educ_name"]:$post_vars["educ_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["educ_id"]) {
            print "<input type='hidden' name='educ_id' value='".$get_vars["educ_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Education' class='textbox' name='submiteducation' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Education' class='textbox' name='submiteducation' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Education' class='textbox' name='submiteducation' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_education() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submiteducation"]) {
            if ($post_vars["educ_id"] && $post_vars["educ_name"]) {
                switch($post_vars["submiteducation"]) {
                case "Add Education":
                    $sql = "insert into m_lib_education (educ_id, educ_name) ".
                           "values ('".strtoupper($post_vars["educ_id"])."', '".$post_vars["educ_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Education":
                    $sql = "update m_lib_education set ".
                           "educ_name = '".$post_vars["educ_name"]."' ".
                           "where educ_id = '".$post_vars["educ_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Education":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_education where educ_id = '".$post_vars["educ_id"]."'";
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

    function display_education() {
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
        print "<span class='library'>".FTITLE_EDUCATION_LEVEL__LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select educ_id, educ_name ".
               "from m_lib_education ".
               "order by educ_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&educ_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }



// end of class
}
?>
