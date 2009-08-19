<?
class occupation extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function occupation() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.6-".date("Y-m-d");
        $this->module = "occupation";
        $this->description = "CHITS Module - Occupation";
        // 0.3 added error management for foreign key constraint
        // 0.4 added automated index generation for new occupation
        //     see get_cat_index()
        // 0.5 changed order of form and display, also of Occupation ID
        //     and Occupation Category
        // 0.6 debugged: ucwords save of occupation name
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
        module::set_lang("FTITLE_OCCUPATION_FORM", "english", "OCCUPATION FORM", "Y");
        module::set_lang("LBL_OCCUPATION_ID", "english", "OCCUPATION ID", "Y");
        module::set_lang("LBL_OCCUPATION_NAME", "english", "OCCUPATION NAME", "Y");
        module::set_lang("FTITLE_OCCUPATION_LIST", "english", "OCCUPATION LIST", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("FTITLE_OCCUPATION_CATEGORY_FORM", "english", "OCCUPATION CATEGORY FORM", "Y");
        module::set_lang("LBL_CAT_ID", "english", "CATEGORY _ID", "Y");
        module::set_lang("LBL_CAT_NAME", "english", "CATEGORY NAME", "Y");
        module::set_lang("FTITLE_OCCUPATION_CATEGORY_LIST", "english", "OCCUPATION CATEGORY LIST", "Y");
        module::set_lang("LBL_OCCUPATION_CAT", "english", "OCCUPATION CATEGORY", "Y");

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
        module::set_menu($this->module, "Occupation", "LIBRARIES", "_occupation");
        module::set_menu($this->module, "Occupation Category", "LIBRARIES", "_occupation_cat");
        module::set_menu($this->module, "Occupation", "STATS", "_occupation_stats");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_occupation` (".
            "`occup_id` varchar(10) NOT NULL default '',".
            "`occup_cat` varchar(15) NOT NULL default '',".
            "`occup_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`occup_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS001', 'TRANS', 'Jeepney Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS002', 'TRANS', 'Taxi Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS003', 'TRANS', 'Bus Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS004', 'TRANS', 'Truck Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS005', 'TRANS', 'Bus Conductor');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS006', 'TRANS', 'Tricycle Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS007', 'TRANS', 'Pedicab Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('TRANS008', 'TRANS', 'Calesa Driver');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('SVC001', 'SVC', 'Private Employee');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('SVC002', 'SVC', 'Private School Teacher');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH001', 'HEALTH', 'Physician');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH002', 'HEALTH', 'Nurse');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH003', 'HEALTH', 'Midwife');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH004', 'HEALTH', 'Physical Therapist');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH005', 'HEALTH', 'Respiratory Therapist');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH006', 'HEALTH', 'X-Ray Technician');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH007', 'HEALTH', 'Nurse Aide');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH008', 'HEALTH', 'Dentist');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('HEALTH009', 'HEALTH', 'Medical Technologist');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('AGRI001', 'AGRI', 'Farmer');");
        module::execsql("INSERT INTO `m_lib_occupation` VALUES('GOVT001', 'GOVT', 'Govt Employee');");

        module::execsql("CREATE TABLE `m_lib_occupation_cat` (".
            "`cat_id` varchar(10) NOT NULL default '',".
            "`cat_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`cat_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('TRANS', 'Transport');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('SVC', 'Service');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('FOOD', 'Food Industry');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('EDUC', 'Education');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('TOUR', 'Tourism');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('HEALTH', 'Health');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('AGRI', 'Agriculture');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('GOVT', 'Government');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('MFG', 'Manufacturing');");
        module::execsql("INSERT INTO `m_lib_occupation_cat` VALUES('RETL', 'Retail');");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_occupation`;");
        module::execsql("DROP TABLE `m_lib_occupation_cat`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _occupation() {
    //
    // main submodule for occupation
    // calls form_occupation()
    //       display_occupation()
    //       process_occupation()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('occupation')) {
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
        $o = new occupation;
        if ($post_vars["submitoccupation"]) {
            $o->process_occupation($menu_id, $post_vars, $get_vars);
        }
        $o->form_occupation($menu_id, $post_vars, $get_vars);
        $o->display_occupation($menu_id, $post_vars, $get_vars);
    }

    function show_occupation() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $occup_id = $arg_list[0];
            $cat_id = $arg_list[1];
        }
        if ($cat_id) {
            $sql = "select occup_id, occup_name from m_lib_occupation where occup_cat = '$cat_id' order by occup_name";
        } else {
            $sql = "select occup_id, occup_name from m_lib_occupation order by occup_name";
        }
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select size='5' name='occupation' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($occup_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_occupation_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $occup_id = $arg_list[0];
        }
        $sql = "select occup_name from m_lib_occupation where occup_id = '$occup_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_occupation() {
    //
    // called by _occupation()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["occup_id"]) {
                $sql = "select occup_id, occup_cat, occup_name ".
                       "from m_lib_occupation where occup_id = '".$get_vars["occup_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $occupation = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<a name='form_occupation'>";
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id#form_occupation' name='form_occupation' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_OCCUPATION_FORM."</span><br><br>";
        print "<b>NOTE: WHEN YOU SELECT AN OCCUPATION CATEGORY, A NEW OCCUPATION ID WILL BE AUTOMATICALLY GENERATED.</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OCCUPATION_CAT."</span><br> ";
        print occupation::show_occup_cat(($occupation["occup_cat"]?$occupation["occup_cat"]:$post_vars["cat_id"]), "refresh");
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OCCUPATION_ID."</span><br> ";
        if ($get_vars["occup_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='occup_id' value='".$occupation["occup_id"]."'>";
        } else {
            $disable = "";
        }
        if ($post_vars["cat_id"]) {
            $index = occupation::get_cat_index($post_vars["cat_id"]);
            $post_vars["occup_id"] = $post_vars["cat_id"].$index;
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='occup_id' value='".($occupation["occup_id"]?$occupation["occup_id"]:$post_vars["occup_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OCCUPATION_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='occup_name' value='".($occupation["occup_name"]?$occupation["occup_name"]:$post_vars["occup_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["occup_id"]) {
            print "<input type='hidden' name='occup_id' value='".$get_vars["occup_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Occupation' class='textbox' name='submitoccupation' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Occupation' class='textbox' name='submitoccupation' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Occupation' class='textbox' name='submitoccupation' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function get_cat_index() {
    //
    // generates new index for new occupation entry
    // from occupation category and last database category entry
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
        }
        $sql = "SELECT lpad(replace(occup_id, '$cat_id','')+1, 3, '0') new_index ".
               "FROM `m_lib_occupation` ".
               "where occup_cat = '$cat_id' ".
               "order by occup_id desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($new_index) = mysql_fetch_array($result);
                return $new_index;
            } else {
                return "001";
            }
        }
    }

    function process_occupation() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitoccupation"]) {
            if ($post_vars["occup_id"] && $post_vars["cat_id"] && $post_vars["occup_name"]) {
                switch($post_vars["submitoccupation"]) {
                case "Add Occupation":
                    $sql = "insert into m_lib_occupation (occup_id, occup_cat, occup_name) ".
                           "values ('".strtoupper($post_vars["occup_id"])."', '".$post_vars["cat_id"]."', '".ucwords($post_vars["occup_name"])."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Occupation":
                    $sql = "update m_lib_occupation set ".
                           "occup_cat = '".$post_vars["cat_id"]."', ".
                           "occup_name = '".ucwords($post_vars["occup_name"])."' ".
                           "where occup_id = '".$post_vars["occup_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    } else {
                        print "<font color='red'>".mysql_errno().": ".mysql_error()."</font><br/>";
                    }
                    break;
                case "Delete Occupation":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_occupation where occup_id = '".$post_vars["occup_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                        } else {
                            print "<font color='red'>".mysql_errno().": ".mysql_error()."</font><br/>";
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

    function display_occupation() {
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
        print "<span class='library'>".FTITLE_OCCUPATION_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select o.occup_cat, o.occup_id, o.occup_name ".
               "from m_lib_occupation o, m_lib_occupation_cat c ".
               "where o.occup_cat = c.cat_id order by c.cat_name, o.occup_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($cat, $id, $name) = mysql_fetch_array($result)) {
                    if ($prev_cat<>$cat) {
                    print "<tr valign='top' bgcolor='#FFCC00'><td colspan='2'><b>".strtoupper(occupation::get_occupcat_name($cat))."</b></td></tr>";
                    }
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&occup_id=$id#form_occupation'>$name</a></td></tr>";
                    $prev_cat = $cat;
                }
            }
        }
        print "</table><br>";
    }

    function _occupation_cat() {
    //
    // main submodule for patient outcomes
    // calls form_outcome()
    //       display_outcome()
    //       process_outcome()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('ntp')) {
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
        $o = new occupation;
        if ($post_vars["submitcat"]) {
            $o->process_occupcat($menu_id, $post_vars, $get_vars);
        }
        $o->display_occupcat($menu_id, $post_vars, $get_vars);
        $o->form_occupcat($menu_id, $post_vars, $get_vars);
    }

    function show_occup_cat() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
            $refresh = $arg_list[1];
        }
        $sql = "select cat_id, cat_name from m_lib_occupation_cat order by cat_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if ($refresh) {
                    $ret_val .= "<select name='cat_id' onchange='this.form.submit();' class='textbox'>";
                } else {
                    $ret_val .= "<select name='cat_id' class='textbox'>";
                }
                $ret_val .= "<option value=''>Select Category</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($cat_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            } else {
                $ret_val .= "<font color='red'>None.</font>";
            }
        }
    }

    function get_occupcat_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
        }
        $sql = "select cat_name from m_lib_occupation_cat where cat_id = '$cat_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_occupcat() {
    //
    // called by _ntp_outcomes()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["cat_id"]) {
                $sql = "select cat_id, cat_name ".
                       "from m_lib_occupation_cat where cat_id = '".$get_vars["cat_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $cat = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_category' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_OCCUPATION_CATEGORY_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CAT_ID."</span><br> ";
        if ($get_vars["cat_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='cat_id' value='".$get_vars["cat_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='cat_id' value='".($cat["cat_id"]?$cat["cat_id"]:$post_vars["cat_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CAT_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='cat_name' value='".($cat["cat_name"]?$cat["cat_name"]:$post_vars["cat_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["cat_id"]) {
            print "<input type='hidden' name='cat_id' value='".$get_vars["cat_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_occupcat() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitcat"]) {
            if ($post_vars["cat_id"] && $post_vars["cat_name"]) {
                switch($post_vars["submitcat"]) {
                case "Add Category":
                    $sql = "insert into m_lib_occupation_cat (cat_id, cat_name) ".
                           "values ('".strtoupper($post_vars["cat_id"])."', '".$post_vars["cat_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Category":
                    $sql = "update m_lib_occupation_cat set ".
                           "cat_name = '".$post_vars["cat_name"]."' ".
                           "where cat_id = '".$post_vars["cat_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Category":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_occupation_cat where cat_id = '".$post_vars["cat_id"]."'";
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

    function display_occupcat() {
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
        print "<span class='library'>".FTITLE_OCCUPATION_CATEGORY_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select cat_id, cat_name from m_lib_occupation_cat order by cat_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&cat_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }


// end of class
}
?>
