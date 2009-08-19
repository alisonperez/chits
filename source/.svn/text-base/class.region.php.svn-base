<?
class region extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function region() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "region";
        $this->description = "CHITS Library - PH Regions";
        // 0.3 debugged and fixed library

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
        module::set_lang("FTITLE_REGION_FORM", "english", "REGION FORM", "Y");
        module::set_lang("LBL_REGION_ID", "english", "REGION ID", "Y");
        module::set_lang("LBL_REGION_NAME", "english", "REGION NAME", "Y");
        module::set_lang("LBL_REGION_PROVINCES", "english", "REGION PROVINCES", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_PROVINCES", "english", "NAME", "Y");
        module::set_lang("FTITLE_REGION_LIST", "english", "REGION LIST", "Y");

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
        module::set_menu($this->module, "Region", "LIBRARIES", "_region");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_region` (".
            "`region_id` varchar(10) NOT NULL default '',".
            "`region_provinces` varchar(255) NOT NULL default '',".
            "`region_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`region_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO m_lib_region VALUES ('1','Ilocos Norte, Ilocos Sur, La Union, Pangasinan','Ilocos Region');");
        module::execsql("INSERT INTO m_lib_region VALUES ('10','Bukidnon, Camiguin, Misamis Occidental, Misamis Oriental, Lanao del Norte','Northern Mindanao');");
        module::execsql("INSERT INTO m_lib_region VALUES ('11','Davao City, Davao del Norte, Davao del Sur, Davao Oriental, Compostela Valley','Davao');");
        module::execsql("INSERT INTO m_lib_region VALUES ('12','North Cotabato, Sultan Kudarat, South Cotabato, Saranggani','SOCCSKSARGEN');");
        module::execsql("INSERT INTO m_lib_region VALUES ('13','Agusan del Norte, Agusan del Sur, Surigao del Norte, Surigao del Sur','CARAGA');");
        module::execsql("INSERT INTO m_lib_region VALUES ('2','Batanes, Cagayan, Isabela, Nueva Vizcaya, Quirino','Cagayan Valley');");
        module::execsql("INSERT INTO m_lib_region VALUES ('3','Aurora, Bataan, Bulacan, Pampanga, Nueva Ecija, Tarlac, Zambales','Central Luzon');");
        module::execsql("INSERT INTO m_lib_region VALUES ('4A','Batangas, Cavite, Laguna, Quezon, Rizal','CALABARZON');");
        module::execsql("INSERT INTO m_lib_region VALUES ('4B','Marinduque, Occidental Mindoro, Oriental Mindoro, Palawan, Romblon','MIMAROPA');");
        module::execsql("INSERT INTO m_lib_region VALUES ('5','Albay, Camarines Sur, Camarines Norte, Catanduanes, Sorsogon, Masbate','Bicol');");
        module::execsql("INSERT INTO m_lib_region VALUES ('6','Aklan, Antique, Capiz, Iloilo, Guimaras, Negros Occidental','Western Visayas');");
        module::execsql("INSERT INTO m_lib_region VALUES ('7','Bohol, Cebu, Negros Oriental, Siquijor','Central Visayas');");
        module::execsql("INSERT INTO m_lib_region VALUES ('8','Biliran, Eastern Samar, Leyte, Northern Samar, Southerm Leyte','Eastern Visayas');");
        module::execsql("INSERT INTO m_lib_region VALUES ('9','Zamboanga Sibugay, Zamboanga del Sur, Zamboanga del Norte, Zamboanga City, Isabela City','Zamboanga Peninsula');");
        module::execsql("INSERT INTO m_lib_region VALUES ('ARMM','Basilan, Sulu, Tawi-tawi, Lanao del Sur, Maguindanao','Autonomous Region of Muslim Mindanao');");
        module::execsql("INSERT INTO m_lib_region VALUES ('CAR','Abra, Benguet, Ifugao, Kalinga, Apayao, Mountain Province','Cordillera Administrative Region');");
        module::execsql("INSERT INTO m_lib_region VALUES ('NCR','Caloocan, Las Piñas, Quezon City, Makati, Manila, Muntinlupa,    Parañaque, Pasig, Pasay, Malabon, Mandaluyong, Marikina and Valenzuela and the municipalities of Navotas, Pateros, San Juan and Taguig','National Capital Region');");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_region`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _region() {
    //
    // calls form_region()
    //       display_region()
    //       process_region()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('region')) {
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
        $r = new region;
        if ($post_vars["submitregion"]) {
            $r->process_region($menu_id, $post_vars, $get_vars);
        }
        $r->display_region($menu_id, $post_vars, $get_vars);
        $r->form_region($menu_id, $post_vars, $get_vars);
    }

    function show_region() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $region_id = $arg_list[0];
        }
        $sql = "select region_id, region_name from m_lib_region order by region_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select size='5' name='region' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($region_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_region_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $region_id = $arg_list[0];
        }
        $sql = "select region_name from m_lib_region where region_id = '$region_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function form_region() {
    //
    // called by _region()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["region_id"]) {
                $sql = "select region_id, region_name, region_provinces ".
                       "from m_lib_region where region_id = '".$get_vars["region_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $region = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"]."' name='form_region' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_REGION_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_REGION_ID."</span><br> ";
        if ($get_vars["region_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='region_id' value='".$region["region_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='region_id' value='".($region["region_id"]?$region["region_id"]:$post_vars["region_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_REGION_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='region_name' value='".($region["region_name"]?$region["region_name"]:$post_vars["region_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_REGION_PROVINCES."</span><br> ";
        print "<textarea class='textbox' rows='3' cols='40' name='region_provinces' style='border: 1px solid black'>".stripslashes(($region["region_provinces"]?$region["region_provinces"]:$post_vars["region_provinces"]))."</textarea><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["region_id"]) {
            print "<input type='hidden' name='region_id' value='".$get_vars["region_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Region' class='textbox' name='submitregion' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Region' class='textbox' name='submitregion' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Region' class='textbox' name='submitregion' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_region() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitregion"]) {
            if ($post_vars["region_id"] && $post_vars["region_name"]) {
                switch($post_vars["submitregion"]) {
                case "Add Region":
                    $sql = "insert into m_lib_region (region_id, region_name, region_provinces) ".
                           "values ('".strtoupper($post_vars["region_id"])."', '".$post_vars["region_name"]."', '".addslashes($post_vars["region_provinces"])."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Region":
                    $sql = "update m_lib_region set ".
                           "region_name = '".$post_vars["region_name"]."', ".
                           "region_provinces = '".addslashes($post_vars["region_provinces"])."' ".
                           "where region_id = '".$post_vars["region_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Region":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_region where region_id = '".$post_vars["region_id"]."'";
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

    function display_region() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='500'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_REGION_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_PROVINCES."</b></td></tr>";
        $sql = "select region_id, region_name, region_provinces ".
               "from m_lib_region ".
               "order by region_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $prov) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&region_id=$id'>$name</a></td><td>$prov</td></tr>";
                }
            }
        }
        print "</table><br>";
    }



// end of class
}
?>
