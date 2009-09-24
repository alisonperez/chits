<?
class barangay extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function barangay() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "barangay";
        $this->description = "CHITS Library - Barangay";
        // 0.3 installed new activation script
        // 0.4 modified barangay selection
        
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    // use multiple inserts
    //
        module::set_dep($this->module, "module");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_POPULATION", "english", "POPULATION", "Y");
        module::set_lang("FTITLE_BARANGAY_LIST", "english", "BARANGAY LIST", "Y");
        module::set_lang("FTITLE_BARANGAY_FORM", "english", "BARANGAY FORM", "Y");
        module::set_lang("LBL_BARANGAY_NUMBER", "english", "BARANGAY NUMBER", "Y");
        module::set_lang("LBL_AREA_CODE", "english", "AREA CODE", "Y");
        module::set_lang("LBL_POPULATION", "english", "POPULATION", "Y");
        module::set_lang("LBL_BARANGAY_NAME", "english", "BARANGAY NAME", "Y");

    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        // _<modulename> in SQl refers to function _<modulename>() below
        // _barangay in SQL refers to function _barangay() below;
        module::set_menu($this->module, "Barangay", "LIBRARIES", "_barangay");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_barangay` (".
               "`barangay_id` int(11) NOT NULL,".
               "`barangay_name` varchar(50) NOT NULL,".
               "`barangay_population` int(11) NOT NULL default 0,".
               "`area_code` int(11) NOT NULL default '0',".
               "PRIMARY KEY  (`barangay_id`)".
               ") TYPE=InnoDB;");

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_lib_barangay`;");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function show_barangays() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $barangay_id = $arg_list[0];
        }
        $sql = "select barangay_id, barangay_name from m_lib_barangay order by barangay_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<select name='barangay' class='boxtext'>";
                print "<option value=''>Select Barangay</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($barangay_id==$id?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
    }

    function barangay_name() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $barangay_id = $arg_list[0];
        }
        $sql = "select barangay_name from m_lib_barangay where barangay_id = $barangay_id";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function display_barangay() {
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
        print "<span class='library'>".FTITLE_BARANGAY_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_POPULATION."</b></td></tr>";
        $sql = "select barangay_id, barangay_name, barangay_population from m_lib_barangay order by barangay_name";
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
            //print_r($arg_list);
        }
        if ($post_vars["submitbarangay"]) {
            if ($post_vars["barangay_id"] && $post_vars["barangay_name"]) {
                switch($post_vars["submitbarangay"]) {
                case "Add Barangay":
                    $sql = "insert into m_lib_barangay (barangay_id, area_code, barangay_population, barangay_name) ".
                           "values ('".$post_vars["barangay_id"]."', '".$post_vars["barangay_areacode"]."', '".$post_vars["barangay_population"]."', '".$post_vars["barangay_name"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Barangay":
                    $sql = "update m_lib_barangay set ".
                           "area_code = '".$post_vars["barangay_areacode"]."', ".
                           "barangay_population = '".$post_vars["barangay_population"]."', ".
                           "barangay_name = '".$post_vars["barangay_name"]."' ".
                           "where barangay_id = '".$post_vars["barangay_id"]."'";
                    $result = mysql_query($sql);
                    break;
                case "Delete Barangay":
                    $sql = "delete from m_lib_barangay where barangay_id = '".$post_vars["barangay_id"]."'";
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
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["barangay_id"]) {
                $sql = "select barangay_id, barangay_name, barangay_population, area_code ".
                       "from m_lib_barangay where barangay_id = '".$get_vars["barangay_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $barangay = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_BARANGAY_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BARANGAY_NUMBER."</span><br> ";
        if ($barangay["barangay_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='barangay_id' value='".$barangay["barangay_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='barangay_id' value='".($barangay["barangay_id"]?$barangay["barangay_id"]:$post_vars["barangay_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_AREA_CODE."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='10' name='barangay_areacode' value='".($barangay["area_code"]?$barangay["area_code"]:$post_vars["barangay_areacode"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_POPULATION."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='10' name='barangay_population' value='".($barangay["barangay_population"]?$barangay["barangay_population"]:$post_vars["barangay_population"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BARANGAY_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='barangay_name' value='".($barangay["barangay_name"]?$barangay["barangay_name"]:$post_vars["barangay_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";

/*		print "<tr valign='top'><td>";
        print "<span class='boxtitle'>YEAR</span><br> ";
		echo "<select name='taon' size='1'>";
		for($i=(date('Y')-5);$i<(date('Y')+5);$i++){
			if($i==date('Y')):
				echo "<option value='$i' selected>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}

*/ 
		echo "</select>";
        print "</td></tr>";

        print "<tr><td><br>";
        if ($get_vars["barangay_id"]) {
            print "<input type='hidden' name='barangay_id' value='".$get_vars["barangay_id"]."'>";
            print "<input type='submit' value = 'Update Barangay' class='textbox' name='submitbarangay' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Barangay' class='textbox' name='submitbarangay' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Barangay' class='textbox' name='submitbarangay' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function _barangay() {
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
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('barangay')) {
            return print($exitinfo);
        }

        if ($post_vars["submitbarangay"]) {
            $this->process_barangay($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        $this->display_barangay($menu_id);
        $this->form_barangay($menu_id, $post_vars, $get_vars);
    }

}
?>
