<?
class chits_library {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function chits_library() {
        //
        // do not forget to update version
        //
        $this->version = "0.2-".date("Y-m-d");
    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        print "creating library menus...<br>";

        // barangay below refers to function barangay() below;
        $sql = "insert into chits.module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ($module_id,'Barangay', 'LIBRARIES', 'barangay')";
        $result = mysql_query($sql) or die(mysql_errno().": ".mysql_error());

        // barangay below refers to function barangay() below;
        $sql = "insert into chits.module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ($module_id,'Patient Groups', 'LIBRARIES', 'ptgroup')";
        $result = mysql_query($sql) or die(mysql_errno().": ".mysql_error());

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        print "creating library tables...<br>";
        $sql = "update chits.modules set module_desc = 'CHITS Library Module', ".
               "module_version = '".$this->version."' ".
               "where module_id = $module_id;";
        $result = mysql_query($sql);

        // this is not auto_increment
        $sql = "CREATE TABLE `m_lib_barangay` (".
               "`barangay_id` int(11) NOT NULL,".
               "`barangay_name` varchar(50) NOT NULL,".
               "`barangay_population` int(11) NOT NULL default 0,".
               "`area_code` int(11) NOT NULL default '0',".
               "PRIMARY KEY  (`barangay_id`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_lib_ptgroup` (".
               "`group_id` varchar(10) NOT NULL,".
               "`group_name` varchar(50) NOT NULL default '',".
               "`group_desc` tinytext NOT NULL,".
               "PRIMARY KEY  (`group_id`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);

        // insert defaults
        $sql = "insert into chits.m_lib_ptgroup ('WELLBB', 'Well Baby', '')";
        $result = mysql_query($sql);
        $sql = "insert into chits.m_lib_ptgroup ('SICKBB', 'Sick Baby', '')";
        $result = mysql_query($sql);
        $sql = "insert into chits.m_lib_ptgroup ('POSTPART', 'Post-partum', '')";
        $result = mysql_query($sql);
        $sql = "insert into chits.m_lib_ptgroup ('PRENATAL', 'Prenatal', '')";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_lib_patient_groups` (".
               "`patient_id` int(11) NOT NULL default '0',".
               "`group_id` int(11) NOT NULL default '0',".
               "`assign_date` date NOT NULL default '0000-00-00',".
               "PRIMARY KEY  (`patient_id`,`group_id`,`assign_date`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);
    }

    function drop_tables() {
        $sql = "DROP TABLE m_lib_barangay;";
        return $result = mysql_query($sql) or die(mysql_error());
    }

// BARANGAY

    function display_barangay() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<b>BARANGAY LIST</b><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td><td><b>POPULATION</b></td></tr>";
        $sql = "select barangay_id, barangay_name, barangay_population from chits.m_lib_barangay order by barangay_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $popn) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&barangay_id=$id'>$name</a></td><td>$popn</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function process_barangay() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        if ($post_vars["submitbarangay"]) {
            if ($post_vars["barangay_id"] && $post_vars["barangay_name"]) {
                switch($post_vars["submitbarangay"]) {
                case "Add Barangay":
                    print $sql = "insert into chits.m_lib_barangay (barangay_id, area_code, barangay_population, barangay_name) ".
                           "values ('".$post_vars["barangay_id"]."', '".$post_vars["barangay_areacode"]."', '".$post_vars["barangay_population"]."', '".$post_vars["barangay_name"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Barangay":
                    print $sql = "update chits.m_lib_barangay set ".
                           "area_code = '".$post_vars["barangay_areacode"]."', ".
                           "barangay_population = '".$post_vars["barangay_population"]."', ".
                           "barangay_name = '".$post_vars["barangay_name"]."' ".
                           "where barangay_id = '".$post_vars["barangay_id"]."'";
                    $result = mysql-query($sql);
                    break;
                case "Delete Barangay":
                    $sql = "delete from chits.m_lib_barangay where barangay_id = '".$post_vars["barangay_id"]."'";
                    $result = mysql_query($sql);
                    break;
                }
            }
        }
    }

    function form_barangay() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            if ($term_id) {
                $sql = "select term_id, concept_id, term_name from openaims.anes_terms where term_id = '$term_id'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $term = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>BARANGAY FORM</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>BARANGAY NUMBER</span><br> ";
        print "<input type='text' class='textbox' size='5' maxlength='5' name='barangay_id' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>AREA CODE</span><br> ";
        print "<input type='text' class='textbox' size='5' maxlength='5' name='barangay_areacode' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>POPULATION</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='10' name='barangay_population' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>BARANGAY NAME</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='barangay_name' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($getvars["barangay_id"]) {
            print "<input type='hidden' name='term_id' value='$term_id'>";
            print "<input type='submit' value = 'Update Term' class='textbox' name='submitbarangay' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Term' class='textbox' name='submitbarangay' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Barangay' class='textbox' name='submitbarangay' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function barangay() {
        //
        // main method for barangay
        // calls form_barangay, process_barangay, display_barangay
        //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitbarangay"]) {
            chits_library::process_barangay($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        chits_library::display_barangay($menu_id);
        chits_library::form_barangay($menu_id, $post_vars, $get_vars);
    }

// PTGROUP
/*
    function display_ptgroup() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<b>PATIENT GROUPS</b><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td><td><b>DESCRIPTION</b></td></tr>";
        $sql = "select group_id, group_name, group_desc from chits.m_lib_ptgroup order by group_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $desc) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&barangay_id=$id'>$name</a></td><td>$desc</td></tr>";
                }
            }
        }
        print "</table><br>";
    }
*/
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
                    $sql = "insert into chits.m_lib_ptgroup (group_id, group_name, group_desc) ".
                           "values ('".$post_vars["barangay_id"]."', '".$post_vars["group_name"]."', '".$post_vars["group_desc"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Group":
                    print $sql = "update chits.m_lib_ptgroup set ".
                           "group_name = '".$post_vars["group_name"]."', ".
                           "group_name = '".$post_vars["group_desc"]."' ".
                           "where group_id = '".$post_vars["group_id"]."'";
                    $result = mysql-query($sql);
                    break;
                case "Delete Group":
                    $sql = "delete from chits.m_lib_ptgroup where group_id = '".$post_vars["group_id"]."'";
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
            if ($term_id) {
                $sql = "select term_id, concept_id, term_name from openaims.anes_terms where term_id = '$term_id'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $term = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>PATIENT GROUP FORM</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>GROUP ID</span><br> ";
        print "<input type='text' class='textbox' size='5' maxlength='5' name='barangay_id' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>GROUP NAME</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='barangay_name' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>GROUP DESCRIPTION</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='barangay_name' value='' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($getvars["group_id"]) {
            print "<input type='hidden' name='term_id' value='$term_id'>";
            print "<input type='submit' value = 'Update Group' class='textbox' name='submitptgroup' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Group' class='textbox' name='submitptgroup' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Group' class='textbox' name='submitptgroup' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function ptgroup() {
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
        if ($post_vars["submitptgroup"]) {
            chits_library::process_ptgroup($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        chits_library::display_ptgroup($menu_id);
        chits_library::form_ptgroup($menu_id, $post_vars, $get_vars);
    }
    }
}
?>
