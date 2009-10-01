<?
class family extends module{

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function family() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.8-".date("Y-m-d");
        $this->module = "family";
        $this->description = "CHITS Module - Family";
        // 0.4 extremely edited and debugged version
        // 0.5 multiple member insertion
        //     improved search, role modification
        // 0.6 added family address editing
        // 0.7 added head of family functionality
        // 0.8 debugged barangay_name()
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "barangay");
    }

    function init_lang() {
    //
    // insert necessary language directives
    //

        module::set_lang("FTITLE_FAMILY_RECORDS", "english", "FAMILY FOLDERS", "Y");
        module::set_lang("LBL_REMOVE_PATIENT", "english", "REMOVE PATIENT FROM FAMILY?", "Y");
        module::set_lang("LBL_FAMILY_ADDRESS", "english", "FAMILY ADDRESS", "Y");
        module::set_lang("LBL_FOLDER_HOWTO", "english", "FAMILY FOLDER HOWTO", "Y");
        module::set_lang("LBL_FAMILY_BARANGAY", "english", "FAMILY BARANGAY", "Y");
        module::set_lang("THEAD_FAMILIES", "english", "FAMILIES", "Y");
        module::set_lang("THEAD_COUNT", "english", "COUNT", "Y");
        module::set_lang("INSTR_FAMILY_ROLE", "english", "SELECT A ROLE FOR THIS FAMILY MEMBER", "Y");
        module::set_lang("INSTR_FAMILY_FOLDER", "english", "FILL UP THE FOLLOWING FORM TO CREATE A NEW FAMILY", "Y");
        module::set_lang("INSTR_FAMILY_INFO", "english", "CLICK ON FAMILY ID TO EDIT INFO", "Y");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::set_menu($this->module, "Family Folders", "PATIENTS", "_family");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
    //
    // pay attention to order of table creation
    // to prevent foerign key constraint errors
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // this is not auto_increment
        module::execsql("CREATE TABLE `m_family` (".
            "`family_id` int(11) NOT NULL auto_increment,".
            "`head_patient_id` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`family_id`)".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_family_address` (".
            "`family_id` int(11) NOT NULL default '0',".
            "`address_year` year(4) NOT NULL default '0000',".
            "`address` text NOT NULL,".
            "`barangay_id` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`family_id`,`address_year`),".
            "KEY `key_barangay` (`barangay_id`),".
            "CONSTRAINT `m_family_address_ibfk_2` FOREIGN KEY (`barangay_id`) REFERENCES `m_lib_barangay` (`barangay_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_family_address_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `m_family` (`family_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_family_members` (".
            "`family_id` int(11) NOT NULL default '0',".
            "`family_role` varchar(10) NOT NULL default '',".
            "`patient_id` float NOT NULL default '0',".
            "PRIMARY KEY  (`family_id`,`patient_id`,`family_role`),".
            "KEY `key_family` (`family_id`),".
            "KEY `key_patient` (`patient_id`),".
            "CONSTRAINT `m_family_members_ibfk_2` FOREIGN KEY (`family_id`) REFERENCES `m_family` (`family_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_family_members_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

    }

    function drop_tables() {
    //
    // pay attention to order of deletion
    // to prevent foerign key constraint errors
    //
        module::execsql("DROP TABLE `m_family_address`;");
        module::execsql("DROP TABLE `m_family_members`;");
        module::execsql("DROP TABLE `m_family`;");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _family() {
    //
    // main program for family module
    //
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
        if ($exitinfo = $this->missing_dependencies('family')) {
            return print($exitinfo);
        }
        print "<span class='patient'>".FTITLE_FAMILY_RECORDS."</span><br/>";
        print "<table width='600' cellpadding='2'>";
        print "<tr valign='top'><td width='50%'>";
        // column 1
        print "<b>".LBL_FOLDER_HOWTO."</b><br/>";
        print "<ol>";
        print "<li>If you are creating a new family folder, type in address and ".
              "barangay then click on the <b>Create Folder</b> button. Famiy numbers ".
              "are created sequentially and automatically.</li>";
        print "<li>If you are looking for an existing family folder, ".
              "type in the name of a family member. Important - The patient ".
              "account to be included in ".
              "a family must be created first.</li>";
        print "</ol>";
        if ($post_vars["submitfolder"]) {
            $this->process_folder($menu_id, $post_vars, $get_vars);
        }
        if ($_SESSION["priv_add"]) {
            $this->form_folder($menu_id, $post_vars, $get_vars);
        }
        // end of column 1
        print "</td><td>";
        // column 2
        if ($get_vars["delete_family_id"]) {
            $this->process_stats($menu_id, $post_vars, $get_vars);
        }
        $this->family_stats($menu_id, $post_vars, $get_vars);
        if ($get_vars["family_id"]) {
            $this->process_member($menu_id, $post_vars, $get_vars);
            $this->family_info($menu_id, $post_vars, $get_vars);
        }
        if ($post_vars["submitsearch"]) {
            $this->process_search($menu_id, $post_vars, $get_vars);
        } else {
            $this->familysearch($menu_id, $post_vars, $get_vars);
        }
        // end of column 2
        print "</td></tr>";
        print "<tr><td colspan='2'>";
        healthcenter::consult_info($menu_id, $post_vars, $get_vars);
        print "</td></tr>";
        print "</table>";
    }

    function process_stats() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
            $sql = "delete from m_family where family_id = '".$get_vars["delete_family_id"]."'";
            if ($result = mysql_query($sql)) {
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&members=".$get_vars["members"]);
            }
        } else {
            if ($post_vars["confirm_delete"]=="No") {
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&members=".$get_vars["members"]);
            }
        }
    }

    function family_stats() {
    //
    // which families are empty?
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "SELECT if(m.patient_id,'Y', 'N') membership, ".
               "count(isnull(m.patient_id)) membercount ".
               "FROM m_family f left join m_family_members m ".
               "on f.family_id = m.family_id group by isnull(m.patient_id)";
        if ($result = mysql_query($sql)) {
            $ret_val .= "<table width='250' cellpadding='2' cellspacing='0' style='border: 1px solid black'>";
            if (mysql_num_rows($result)) {
                $ret_val .= "<tr bgcolor='#FFCC66'><td><b>".THEAD_FAMILIES."</b></td><td><b>".THEAD_COUNT."</b></td></tr>";
                while (list($membership, $count) = mysql_fetch_array($result)) {
                    $ret_val .= "<tr><td>";
                    if ($membership=="Y") {
                        $ret_val .= "<b>".($membership=="Y"?"With members":"No members")."</b></td>";
                    } else {
                        $ret_val .= "<b><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&members=$membership'>".($membership=="Y"?"With members":"No members")."</a></b></td>";
                    }
                    $ret_val .= "<td><font color='red'>$count</font></td></tr>";
                    if ($get_vars["members"]==$membership) {
                        switch ($get_vars["members"]) {
                        case "Y":
                            break;
                        case "N":
                            $sql_mem = "SELECT f.family_id ".
                                       "FROM m_family f left join m_family_members m ".
                                       "on f.family_id = m.family_id ".
                                       "where ISNULL(m.patient_id)";
                            if ($result_mem = mysql_query($sql_mem)) {
                                if (mysql_num_rows($result_mem)) {
                                    $ret_val .= "<tr><td colspan='2'>";
                                    $ret_val .= "<table width='200' class='tinylight'><tr><td>";
                                    while (list($fid) = mysql_fetch_array($result_mem)) {
                                        $ret_val .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=$fid'>$fid</a> ";
                                        if ($_SESSION["priv_delete"]) {
                                            $ret_val .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&members=".$get_vars["members"]."&delete_family_id=$fid'><img src='../images/delete.png' border='0'/></a><br/>";
                                        }
                                    }
                                    $ret_val .= "</td></tr></table>";
                                    $ret_val .= "</td></tr>";
                                }
                            }
                            break;
                        }
                    }
                    $ret_val .= "</td></tr>";
                }
            } else {
                $ret_val .= "<tr><td><font color='red'>&nbsp;&nbsp;<b>No family records.</b></font></td></tr>";
            }
            $ret_val .= "</table><br/>";
            print $ret_val;
        }
    }

    function process_member() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["delete_id"]) {
            if (module::confirm_delete($menu_id,$post_vars,$get_vars)) {
                $sql = "delete from m_family_members where family_id = '".$get_vars["family_id"]."' and patient_id = '".$get_vars["delete_id"]."'";
                if ($result = mysql_query($sql)) {
                    $sql_check = "select family_id from m_family_members where family_id = '".$get_vars["family_id"]."'";
                    if ($result_check = mysql_query($sql)) {
                        if (mysql_num_rows($result_check)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]);
                        } else {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                        }
                    }
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]);
                }
            }
        }
        // method 1
        // addition to family one at a time
        if ($get_vars["add_id"]) {
            $sql = "insert into m_family_members (family_id, patient_id, family_role) ".
                   "values ('".$get_vars["family_id"]."', '".$get_vars["add_id"]."', 'member')";
            if ($result = mysql_query($sql)) {
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]);
            }
        }
        // method 2
        // multiple addition to family
        if ($post_vars["include_patient"]) {
            foreach ($post_vars["include_patient"] as $key=>$value) {
                $sql = "insert into m_family_members (family_id, patient_id, family_role) ".
                       "values ('".$get_vars["family_id"]."', '$value', 'member')";
                $result = mysql_query($sql);
            }
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]);
        }
    }

    function process_folder() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        list($month,$day,$year) = explode("/", $post_vars["patient_dob"]);
        $dob = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
        switch ($post_vars["submitfolder"]) {
        case "Create Folder":
            if ($post_vars["family_address"] && $post_vars["barangay"]) {
                $sql = "insert into m_family (head_patient_id) values ('0')";
                if ($result = mysql_query($sql)) {
                    $insert_id = mysql_insert_id();
                    $sql_address = "insert into m_family_address (family_id, address_year, address, barangay_id) ".
                                   "values ($insert_id, '".date("Y")."', '".$post_vars["family_address"]."', '".$post_vars["barangay"]."')";
                    if ($result_address = mysql_query($sql_address)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=$insert_id");
                    }
                }
            }
            break;
        case "Update Folder":
            if ($post_vars["family_address"] && $post_vars["barangay"]) {
                $sql = "update m_family_address set ".
                       "address = '".$post_vars["family_address"]."', ".
                       "barangay_id = '".$post_vars["barangay"]."' ".
                       "where family_id = '".$post_vars["family_id"]."' and ".
                       "address_year = '".$post_vars["address_year"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]);
                }
            }
            break;
        case "Delete Folder":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_family_address ".
                       "where family_id = '".$post_vars["family_id"]."' and ".
                       "address_year = '".$post_vars["address_year"]."'";
                if ($result = mysql_query($sql)) {
                    $sql_delete = "delete from m_family where family_id = '".$post_vars["family_id"]."'";
                    if ($result_delete = mysql_query($sql_delete)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]);
                }
            }
            break;
        }
    }

    function form_folder() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<a name='family_form'>";
        print "<table width='300'>";
        if ($get_vars["family_id"]) {
            print "<form action = '".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&family_id=".$get_vars["family_id"].($get_vars["edit_family_id"]?"&edit_family_id".$get_vars["edit_family_id"]:"")."' name='form_search' method='post'>";
        } else {
            print "<form action = '".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_search' method='post'>";
        }
        if ($get_vars["edit_family_id"]) {
            // get latest address
            $sql = "select family_id, address_year, address, barangay_id ".
                   "from m_family_address ".
                   "where family_id = '".$get_vars["edit_family_id"]."' ".
                   "order by address_year desc limit 1";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($fid, $year, $address, $barangay_id) = mysql_fetch_array($result);
                }
            }
        }
        print "<tr valign='top'><td>";
        if (!$fid && !$get_vars["edit_family_id"]) {
            print "<b>".INSTR_FAMILY_FOLDER."</b><br/>";
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FAMILY_ADDRESS."</span><br> ";
        print "<textarea class='textbox' name='family_address' cols='35' rows='5' style='border: 1px solid #000000'>".$address."</textarea>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FAMILY_BARANGAY."</span><br> ";
        print barangay::show_barangays($barangay_id);
        print "</td></tr>";
        print "<tr><td><br/>";
        print "<input type='hidden' name='family_id' value='".$get_vars["family_id"]."'/>";
        print "<input type='hidden' name='address_year' value='$year'/>";
        if ($fid) {
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Folder' class='textbox' name='submitfolder' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Folder' class='textbox' name='submitfolder' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Create Folder' class='textbox' name='submitfolder' style='border: 1px solid #000000'><br>";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }


    function get_family_id() {
    //
    // same as search_family
    // better use of functionality
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        return family::search_family($patient_id);
    }

    function search_family() {
    //
    // looks for family based on patient id
    // returns family id
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select family_id from m_family_members where patient_id = $patient_id";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($family_id) = mysql_fetch_array($result);
                return $family_id;
            }
        }
        return 0;
    }

    function show_address() {
    //
    // deprecated
    // use get_family_address() instead
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $family_id = $arg_list[0];
        }
        $sql = "select address from m_family_address where family_id = $family_id order by address_year desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($address) = mysql_fetch_array($result);
                return $address;
            }
        }
    }

    function get_family_address() {
    //
    // use this to get address
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $family_id = $arg_list[0];
        }
        $sql = "select address from m_family_address where family_id = $family_id order by address_year desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($address) = mysql_fetch_array($result);
                return $address;
            }
        }
    }

    function barangay_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $family_id = $arg_list[0];
        }
        $sql = "select b.barangay_name from m_family_address a, m_lib_barangay b ".
               "where a.barangay_id = b.barangay_id and ".
               "a.family_id = '$family_id' ".
               "order by a.address_year desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function barangay_id() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $family_id = $arg_list[0];
        }
        $sql = "select b.barangay_id from m_family_address a, m_lib_barangay b ".
               "where a.barangay_id = b.barangay_id and ".
               "a.family_id = '$family_id' ".
               "order by a.address_year desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($id) = mysql_fetch_array($result);
                return $id;
            }
        }
    }

    function get_family_members() {
    //
    // get family members
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $family_id = $arg_list[0];
        }
        $sql = "select p.patient_id, p.patient_lastname, p.patient_firstname, p.patient_dob, p.patient_gender, round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age, f.family_role ".
               "from m_family_members f, m_patient p where p.patient_id = f.patient_id and f.family_id = '$family_id'".
               "order by p.patient_lastname, p.patient_firstname";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($pid, $plast, $pfirst, $pdob, $pgender, $p_age, $role) = mysql_fetch_array($result)) {
                    $ret_val .= "$plast, $pfirst [$p_age/$pgender] ".module::pad_zero($pid,7)."<br/>";
                }
                return $ret_val;
            } else {
                return "No members";
            }
        }
    }

    function family_info() {
    //
    // family details
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select p.patient_id, p.patient_lastname, p.patient_firstname, p.patient_dob, p.patient_gender, round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age, f.family_role ".
               "from m_family_members f, m_patient p where p.patient_id = f.patient_id and f.family_id = '".$get_vars["family_id"]."'".
               "order by p.patient_lastname, p.patient_firstname";
        if ($result = mysql_query($sql)) {
            print "<table width=270 bgcolor='#FFFFFF' cellpadding='4' cellspacing='0' style='border: 2px solid black'>";
            print "<tr><td>";
            print "<span class='tinylight'>".INSTR_FAMILY_INFO."</span><br/>";
            print "<b><font color='red'>SELECTED FAMILY</font> ";
            //print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]."&edit_family_id=".$get_vars["family_id"]."#family_form'>".module::pad_zero($get_vars["family_id"],5)."</a></b> ";
            print module::pad_zero($get_vars["family_id"],5)."&nbsp;<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]."&edit_family_id=".$get_vars["family_id"]."#family_form'>".edit."</a></b> ";
            print "<br>";
            print "<font color='#999999'>";
            print "<b>ADDRESS:</b> ".$this->show_address($get_vars["family_id"])."<br/>";
            print "<b>BARANGAY:</b> ".$this->barangay_name($get_vars["family_id"])."<br/>";
            print "</font>";
            print "<br/>";
            if (mysql_num_rows($result)) {
                $i=0;
                while (list($pid, $plast, $pfirst, $pdob, $pgender, $p_age, $role) = mysql_fetch_array($result)) {
                    //$patient_menu_id = module::get_menu_id("_patient");
                    //$consult_menu_id = module::get_menu_id("_consult");
                    print  "<a href='".$_SERVER["PHP_SELF"]."?page=PATIENTS&menu_id=".$get_vars["menu_id"]."&patient_id=$pid&family_id=".$get_vars["family_id"]."'><b>$plast, $pfirst</b></a> [$p_age/$pgender] ";
                    if ($role=="head") {
                        print "<img src='../images/star.gif' border='0'/> ";
                    }
                    if ($_SESSION["priv_delete"]) {
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$get_vars["family_id"]."&delete_id=$pid'><img src='../images/delete.png' border='0'/></a>";
                    }
                    print "<br/>";
                    $i++;
                    if ($get_vars["patient_id"]==$pid) {
                        family::form_assign_role($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "No members for this family.";
            }
            print "</td></tr>";
            print "</table><br/>";
        }
    }

    function form_assign_role() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        // process submission here
        if ($post_vars["submitrole"]) {
            $sql = "update m_family_members set ".
                   "family_role = '".$post_vars["family_role"]."' ".
                   "where family_id = '".$get_vars["family_id"]."' and ".
                   "patient_id = '".$get_vars["patient_id"]."'";
            if ($result = mysql_query($sql)) {
                if ($post_vars["family_role"]=="HEAD") {
                    $sql_head = "update m_family set head_patient_id = '".$get_vars["patient_id"]."'";

                    $result_head = mysql_query($sql_head);
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&family_id=".$get_vars["family_id"]);
            }
        }
        $sql = "select family_role from m_family_members ".
               "where family_id = '".$get_vars["family_id"]."' and ".
               "patient_id = '".$get_vars["patient_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($role) = mysql_fetch_array($result);
            }
        }
        print "<table width='200' style='border: 1px dotted black'>";
        print "<form method='post' action=''>";
        print "<tr><td class='tinylight'>";
        print "<b>".INSTR_FAMILY_ROLE.":</b><br/>";
        print "<input type='radio' name='family_role' ".($role=="head"?"checked":"")." value='head'/> Head of family<br/>";
        print "<input type='radio' name='family_role' ".($role=="member"?"checked":"")." value='member'/> Family member<br/>";
        print "<input type='submit' name='submitrole' value='Assign Role' class='tinylight' style='border: 1px solid black'/>";
        print "</td></tr>";
        print "</form>";
        print "</table>";
    }

    function process_search() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        if ($post_vars["family_number"]) {
            $sql = "select family_id from m_family where family_id = '".$post_vars["family_number"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=".$post_vars["family_number"]);
                } else {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
        } else {
            // cascade through selection
            if ($post_vars["last"]) {
                $index .= "lower(patient_lastname) like '%".strtolower($post_vars["last"])."%'";
            }
            // check with ternary operator and isset whether $index has been initialized
            if ($post_vars["first"]) {
                $index .= (isset($index)?" AND ":" ") ." lower(patient_firstname) like '%".strtolower($post_vars["first"])."%' ";
            }
            // query the database and append $index to WHERE
            if (isset($index)) {
                $sql = "SELECT patient_id, patient_firstname,patient_lastname, patient_gender, patient_dob, round((to_days(now())-to_days(patient_dob))/365 , 1) computed_age ".
                       "FROM m_patient WHERE $index ";
                if ($result=mysql_query($sql)) {
                    if ($rows = mysql_num_rows($result)) {
                        print "<span class='module'>SEARCH RESULTS</span><br><br>";
                        print "<b>Found <font color='red'>$rows</font> record".($rows>1?"s":"").". ";
                        if ($get_vars["family_id"]) {
                            print "Please select a patient to add to the family above by clicking on the selected patient name. ";
                        } else {
                            print "You have not selected nor created a family to add patients to. ";
                        }
                        print "If you see the ".
                              "<img src='../images/family.gif' border='0'/> icon, click on it to view family members.</b><br><br>";
                        print "<table width='250' cellspacing='0' cellpadding='3'>";
                        print "<form method='post' action=''>";
                        while(list($id,$first,$last,$gender, $dob, $age)=mysql_fetch_array($result)) {
                            print "<tr bgcolor='#FFFF99'><td>";
                            $fid = $this->search_family($id);
                            if ($fid) {
                                print "<font color='red'>".module::pad_zero($id,7)."</font> $first $last ($age/$gender) ";
                                print ($fid?"<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&family_id=$fid'><img src='../images/family.gif' border='0'/></a>":"");
                            } else {
                                if ($get_vars["family_id"]) {
                                    print "<input type='checkbox' name='include_patient[]' value='$id'/> ";
                                    //print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                                    print "<font color='red'>".module::pad_zero($id,7)."</font> <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&add_id=$id&family_id=".$get_vars["family_id"]."'>$first $last ($age/$gender) </a> ";
                                } else {
                                    print "<font color='red'>".module::pad_zero($id,7)."</font> $first $last ($age/$gender) ";
                                }
                            }
                            print "<input type='hidden' name='family_id' value='$family_id'/>";
                            print "</td></tr>" ;
                        }
                        print "<tr><td>";
                        print "<input type='submit' name='submitpatient' value='Add to Family' class='tinylight' style='border: 1px solid black' />";
                        print "</td></tr>";
                        print "</form>";
                        print "<tr><td><br>";
                        print "</td></tr>";
                        print "</table>";
                    } else {
                        print "<font color='red'>No records found.</font><br/>";
                    }
                }
            }
        }
    }

    function process_family() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            print_r($arg_list);
        }
        if ($post_vars["patient_id"]) {
            switch ($post_vars["submitfamily"]) {
            case "Assign Family Member":
                $role = "member";
                break;
            case "Assign Family Head":
                $role = "head";
                break;
            }
            if ($post_vars["submitfamily"]) {
                if ($post_vars["family_id"]==0) {
                    // this table generates family ids
                    $sql_family = "insert into m_family (head_patient_id) ".
                                  "values ('".$post_vars["patient_id"]."')";
                    if ($result_family = mysql_query($sql_family)) {
                        $insert_id = mysql_insert_id();
                        $sql_member = "insert into m_family_members (family_id, family_role, patient_id) ".
                                      "values ('$insert_id', '$role', '".$post_vars["patient_id"]."')";
                        $result_member = mysql_query($sql_member);
                        $ret_val = $insert_id;
                    }
                }
                if ($get_vars["family_id"]) {
                    $sql_member = "insert into m_family_member (family_id, family_role, patient_id) ".
                                  "values ('".$post_vars["family_id"]."', '$role', '".$post_vars["patient_id"]."')";
                    $result_member = mysql_query($sql_member);
                    $ret_val = $post_vars["family_id"];
                }
            }
            return $ret_val;
        }
    }

    function process_patient() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        list($month,$day,$year) = explode("/", $post_vars["patient_dob"]);
        $dob = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
        switch ($post_vars["submitpatient"]) {
        case "Remove Patient":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_family_members where patient_id = '".$get_vars["patient_id"]."' and family_id='".$get_vars["family_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Add Patient":
            if ($post_vars["patient_lastname"] && $post_vars["patient_firstname"] && $post_vars["patient_gender"]) {
                $sql = "insert into m_patient (patient_lastname, patient_firstname, patient_middle, patient_dob, patient_age, patient_gender) ".
                       "values ('".ucwords($post_vars["patient_lastname"])."', '".ucwords($post_vars["patient_firstname"])."', '".ucwords($post_vars["patient_middle"])."', ".
                       "'$dob', '".$post_vars["patient_age"]."', '".$post_vars["patient_gender"]."')";
                $result = mysql_query($sql);
            } else {
                return;
            }
            break;
        }
    }

    function familysearch () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='300'>";
        if ($get_vars["family_id"]) {
            print "<form action = '".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&family_id=".$get_vars["family_id"]."' name='form_search' method='post'>";
        } else {
            print "<form action = '".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_search' method='post'>";
        }
        print "<tr valign='top'><td>";
        print "<b>INSTRUCTIONS: To build a family, look for the name of household members and add them one by one. <br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FIRST_NAME."</span><br> ";
        print "<input type='text' class='textbox' name='first' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAST_NAME."</span><br> ";
        print "<input type='text' class='textbox' name='last' style='border: 1px solid #000000'><br><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>...or type family number below.</b><br/><br/>";
        print "<span class='boxtitle'>".LBL_FAMILY_NUMBER."</span><br> ";
        print "<input type='text' class='textbox' name='family_number' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td>";
        print "<input type='hidden' name='family_id' value='".$get_vars["family_id"]."'/>";
        print "<br><input type='submit' value = 'Search' class='textbox' name='submitsearch' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function form_family () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
            if ($get_vars["patient_id"]) {
                $sql = "select patient_id, healthcenter_id, patient_lastname, ".
                       "patient_firstname, patient_middle, patient_dob, patient_gender ".
                       "from m_patient where patient_id = '".$get_vars["patient_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $patient = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&patient_id=".$get_vars["patient_id"]."&family_id=".$get_vars["family_id"]."' name='form_patient' method='post'>";
        print "<tr valign='top'><td>";
        if ($get_vars["patient_id"]) {
            print "<font color='red' size='5'><b>".LBL_REMOVE_PATIENT."</b></font>";
            print "</td></tr>";
            print "<tr valign='top'><td>";
            print "<b>NOTE:</b> You are removing this patient from the selected family.<br><br>";
            print "</td></tr>";
        }
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FIRST_NAME."</span><br> ";
        print "<input type='text' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_firstname' value='".($patient["patient_firstname"]?$patient["patient_firstname"]:$post_vars["patient_firstname"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_MIDDLE_NAME."</span><br> ";
        print "<input type='text' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_middle' value='".($patient["patient_middle"]?$patient["patient_middle"]:$post_vars["patient_middle"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAST_NAME."</span><br> ";
        print "<input type='text' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_lastname' value='".($patient["patient_lastname"]?$patient["patient_lastname"]:$post_vars["patient_lastname"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PATIENT_DOB."</span><br> ";
        print "<input type='text' size='10' maxlength='10' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_dob' value='".($patient["patient_dob"]?$patient["patient_dob"]:$post_vars["patient_dob"])."' style='border: 1px solid #000000'><br>";
        print "<small>Use format MM/DD/YYYY.</small>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GENDER."</span><br> ";
        print "<select name='patient_gender' ".($get_vars["patient_id"]?'disabled':'')." class='textbox'>";
        print "<option ".($patient["patient_gender"]=='M'?'selected':'')." value='M'>Male</option>";
        print "<option ".($patient["patient_gender"]=='F'?'selected':'')." value='F'>Female</option>";
        print "<option ".($patient["patient_gender"]=='I'?'selected':'')." value='I'>Indeterminate</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr><td>";
        if ($get_vars["patient_id"]) {
            if ($_SESSION["priv_delete"]) {
                print "<br><input type='submit' value = 'Remove Patient' class='textbox' name='submitpatient' style='border: 1px solid #000000'><br>";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Add Patient' class='textbox' name='submitpatient' style='border: 1px solid #000000'><br>";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

// end of class
}
?>
