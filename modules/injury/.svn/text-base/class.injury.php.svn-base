<?
class injury extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function injury() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "injury";
        $this->description = "CHITS Module - Injury";
        // 0.2 installed foreign key constraint
        // 0.3 revamped injury module and changed mechanism to text
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "complaint");
        module::set_dep($this->module, "icd10");
        module::set_dep($this->module, "barangay");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_INJURY_CODE", "english", "INJURY CODE FORM", "Y");
        module::set_lang("FTITLE_INJURY_CODE_LIST", "english", "INJURY CODES", "Y");
        module::set_lang("LBL_INJURY_ID", "english", "INJURY ID", "Y");
        module::set_lang("LBL_INJURY_NAME", "english", "INJURY NAME", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_MAPPED_ICD10", "english", "ICD10 MAPPING", "Y");
        module::set_lang("FTITLE_INJURY_LOCATION_FORM", "english", "INJURY LOCATION FORM", "Y");
        module::set_lang("LBL_LOCATION_ID", "english", "LOCATION ID", "Y");
        module::set_lang("LBL_LOCATION_NAME", "english", "LOCATION NAME", "Y");
        module::set_lang("FTITLE_INJURY_LOCATION_LIST", "english", "INJURY LOCATIONS", "Y");
        module::set_lang("FTITLE_INJURY_MECHANISM_FORM", "english", "INJURY MECHANISM FORM", "Y");
        module::set_lang("LBL_MECHANISM_ID", "english", "MECHANISM ID", "Y");
        module::set_lang("LBL_MECHANISM_NAME", "english", "MECHANISM NAME", "Y");
        module::set_lang("FTITLE_INJURY_MECHANISM_LIST", "english", "INJURY MECHANISM", "Y");
        module::set_lang("FTITLE_INJURY_DATA", "english", "INJURY DATA", "Y");
        module::set_lang("LBL_INJURY_CODE", "english", "INJURY CODE", "Y");
        module::set_lang("LBL_INJURY_MECHANISM", "english", "INJURY MECHANISM", "Y");
        module::set_lang("LBL_INJURY_LOCATION", "english", "INJURY LOCATION", "Y");
        module::set_lang("LBL_INJURY_DATE", "english", "INJURY DATE", "Y");
        module::set_lang("LBL_INJURY_TIME", "english", "INJURY TIME", "Y");
        module::set_lang("LBL_INJURY_LOCATION_DETAILS", "english", "LOCATION DETAILS", "Y");
        module::set_lang("LBL_ICDCODES_INJURY", "english", "ICD MAPPING FOR THIS INJURY CODE", "Y");
        module::set_lang("INSTR_SEARCH_TERM_INJURY", "english", "TYPE SEARCH TERM FOR ICD CODE FOR INJURY", "Y");
        module::set_lang("INSTR_INJURY_MECHANISM", "english", "Describe how injury is caused", "Y");
        module::set_lang("INSTR_INJURY_LOCATION_DETAILS", "english", "Describe details about location of injury", "Y");

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
        module::set_menu($this->module, "Injury Codes", "LIBRARIES", "_injury_codes");
        module::set_menu($this->module, "Injury Locations", "LIBRARIES", "_injury_locations");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // this table relates complaint to loadable modules:
        // imci_cough, imci_diarrhea, imci_fever
        module::execsql("CREATE TABLE `m_consult_injury` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`injury_timestamp` timestamp(14) NOT NULL,".
            "`injury_id` varchar(10) NOT NULL default '',".
            "`user_id` int(11) NOT NULL default '0',".
            "`location_code` varchar(10) NOT NULL default '',".
            "`location_detail` varchar(255) default '',".
            "`barangay_id` int(11) NOT NULL default '0',".
            "`injury_time` time NOT NULL default '00:00:00',".
            "`injury_date` date NOT NULL default '0000-00-00',".
            "`injury_mechanism` text, ".
            "INDEX (patient_id), ".
            "PRIMARY KEY  (`consult_id`,`injury_timestamp`), ".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ON DELETE CASCADE,".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient`(`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_injury` (".
            "`injury_id` varchar(10) NOT NULL default '',".
            "`injury_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`injury_id`)".
            ") TYPE=InnoDB; ");

        // maps injury code to icd10
        module::execsql("CREATE TABLE `m_lib_injury_icd10` (".
            "`injury_id` varchar(10) NOT NULL default '',".
            "`diagnosis_code` varchar(12) NOT NULL default '',".
            "PRIMARY KEY  (`injury_id`,`diagnosis_code`),".
            "KEY `key_injury` (`injury_id`),".
            "CONSTRAINT `m_lib_injury_icd10_ibfk_1` FOREIGN KEY (`injury_id`) REFERENCES `m_lib_injury` (`injury_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_injury_location` (".
            "`location_id` varchar(10) NOT NULL default '',".
            "`location_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`location_id`)".
            ") TYPE=InnoDB; ");

        // load initial data

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_consult_injury`;");
        module::execsql("DROP TABLE `m_lib_injury_location`;");
        module::execsql("DROP TABLE `m_lib_injury`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_injury() {
    //
    // consult injury form
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $i = new injury;
        if ($post_vars["submitinjury"]) {
            $i->process_consult_injury($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
        }
        $i->form_consult_injury($menu_id, $post_vars, $get_vars);
    }

    function process_consult_injury() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        switch($post_vars["submitinjury"]) {
        case "Add Injury Data":
            if ($post_vars["injury_id"] && $post_vars["injurym_echanism"]
                && $post_vars["location_code"] && $post_vars["injury_date"]
                && $post_vars["military_time"]) {
                list($month,$day,$year) = explode("/", $post_vars["injury_date"]);
                $injury_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                $injury_time = substr($post_vars["military_time"],0,2).":".substr($post_vars["military_time"],2,2).":00";
                $sql = "insert into m_consult_injury (consult_id, injury_timestamp, ".
                       "injury_id, user_id, location_code, location_detail, ".
                       "injury_time, injury_date, injury_mechanism, patient_id) ".
                       "values ('".$get_vars["consult_id"]."', sysdate(), ".
                       "'".$post_vars["injury_id"]."', '".$_SESSION["userid"]."', ".
                       "'".$post_vars["location_code"]."', '".$post_vars["location_detail"]."', ".
                       "'$injury_time', '$injury_date', ".
                       "'".addslashes($post_vars["injury_mechanism"])."','$patient_id')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
            break;
        case "Update Injury Data":
            if ($post_vars["injury_id"] && $post_vars["injury_mechanism"]
                && $post_vars["location_code"] && $post_vars["injury_date"]
                && $post_vars["military_time"]) {
                list($month,$day,$year) = explode("/", $post_vars["injury_date"]);
                $injury_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                $injury_time = substr($post_vars["military_time"],0,2).":".substr($post_vars["military_time"],2,2).":00";
                $sql = "update m_consult_injury set ".
                       "injury_id = '".$post_vars["injury_id"]."', ".
                       "location_code = '".$post_vars["location_code"]."', ".
                       "location_detail = '".$post_vars["location_detail"]."', ".
                       "injury_mechanism = '".$post_vars["injury_mechanism"]."', ".
                       "injury_time = '$injury_time', ".
                       "injury_date = '$injury_date' ".
                       "where consult_id = '".$post_vars["cid"]."' and ".
                       "injury_timestamp = '".$post_vars["injury_ts"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
            break;
        case "Delete Injury Data":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_injury ".
                       "where consult_id = '".$post_vars["cid"]."' and ".
                       "injury_timestamp = '".$post_vars["injury_ts"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
            break;
        }
    }

    function form_consult_injury() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["cid"] && $get_vars["injury_ts"]) {
                $sql = "select injury_id, injury_mechanism, location_code, injury_date, injury_time, location_detail ".
                       "from m_consult_injury ".
                       "where consult_id = '".$get_vars["cid"]."' and injury_timestamp = '".$get_vars["injury_ts"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $injury = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."' name='form_consult_injury' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_INJURY_DATA."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_CODE."</span><br> ";
        print injury::show_injury_code($injury["injury_id"]?$injury["injury_id"]:$post_vars["injury_id"]);
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_MECHANISM."</span><br> ";
        print "<textarea name='injury_mechanism' cols='36' rows='3' style='border: 1px solid black' class='tinylight'>".stripslashes(nl2br($injury["injury_mechanism"]?$injury["injury_mechanism"]:$post_vars["injury_mechanism"]))."</textarea><br/>";
        print "<small>".INSTR_INJURY_MECHANISM.".</small><br/>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_LOCATION."</span><br> ";
        print injury::show_injury_location($injury["location_code"]?$injury["location_code"]:$post_vars["location_code"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_LOCATION_DETAILS."</span><br> ";
        print "<textarea name='location_detail' cols='36' rows='3' style='border: 1px solid black' class='tinylight'>".stripslashes(nl2br($injury["location_detail"]?$injury["location_detail"]:$post_vars["location_detail"]))."</textarea><br/>";
        print "<small>".INSTR_INJURY_LOCATION_DETAILS."</small><br/>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_DATE."</span><br> ";
        if ($injury["injury_date"]) {
            list($year, $month, $day) = explode("-", $injury["injury_date"]);
            $injury_date = "$month/$day/$year";
        }
        print "<input type='text' size='15' maxlength='10' class='textbox' name='injury_date' value='".($injury_date?$injury_date:$post_vars["injury_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_consult_injury.injury_date', document.form_consult_injury.injury_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_TIME."</span><br> ";
        list($hours, $minutes, $seconds) = explode(":", $injury["injury_time"]);
        $injury_time = $hours.$minutes;
        print module::show_military_time($injury_time?$injury_time:$post_vars["injury_time"]);
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($get_vars["cid"] && $get_vars["injury_ts"]) {
            print "<input type='hidden' name='cid' value='".$get_vars["cid"]."'>";
            print "<input type='hidden' name='injury_ts' value='".$get_vars["injury_ts"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Injury Data' class='textbox' name='submitinjury' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Injury Data' class='textbox' name='submitinjury' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Add Injury Data' class='textbox' name='submitinjury' style='border: 1px solid #000000'><br> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function _details_injury() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        print "<b>INJURY RECORDS</b><br>";
        $sql = "select patient_id, consult_id, injury_timestamp, date_format(injury_timestamp, '%a %d %b %Y, %h:%i%p') ts ".
               "from m_consult_injury where patient_id = '$patient_id' order by injury_timestamp desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($injury = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&cid=".$injury["consult_id"]."&injury_ts=".$injury["injury_timestamp"]."'>".$injury["ts"]."</a><br/>";
                    if ($get_vars["cid"]==$injury["consult_id"]) {
                        injury::show_details($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
    }

    function show_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select patient_id, consult_id, date_format(injury_timestamp, '%a %d %b %Y, %h:%i%p') ts, ".
               "user_id, injury_id, location_code, location_detail, injury_date, injury_time, mechanism_code ".
               "from m_consult_injury where patient_id = '$patient_id' and consult_id = '".$get_vars["cid"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $injury = mysql_fetch_array($result);
                print "<table cellpadding='1' width='200' style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "CONSULT DATE: ".$injury["ts"]."<br/>";
                print "SEEN BY: ".user::get_username($injury["user_id"])."<br/><br/>";
                print "INJURY DATE: ".$injury["injury_date"]."<br/>";
                print "INJURY TIME: ".$injury["injury_time"]."<br/>";
                print "INJURY CODE: ".injury::get_injury_name($injury["injury_id"])."<br/>";
                print "INJURY LOCATION: ".injury::get_location_name($injury["location_code"])."<br/>";
                print "INJURY MECHANISM:<br/>".stripslashes(nl2br($injury["injury_mechanism"]))."<br/>";
                print "</span>";
                print "</td></tr></table>";
            }
        }
    }


    // ------------------------- LIBRARY METHODS ------------------------------

    function _injury_locations() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitlocation"]) {
            $this->process_injury_location($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        }
        $this->display_injury_location($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        $this->form_injury_location($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
    }

    function form_injury_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["injury_id"]) {
                $sql = "select location_id, location_name from m_lib_injury_location where location_id = '".$get_vars["location_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $location = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_location' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_INJURY_LOCATION_FORM."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LOCATION_ID."</span><br> ";
        print "<input type='text' size='15' maxlength='10' ".($get_vars["location_id"]?"disabled":"")." class='textbox' name='location_id' value='".($location["location_id"]?$location["location_id"]:$post_vars["location_id"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LOCATION_NAME."</span><br> ";
        print "<input type='text' size='15' maxlength='50' class='textbox' name='location_name' value='".($location["location_name"]?$location["location_name"]:$post_vars["location_name"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($get_vars["location_id"]) {
            print "<input type='hidden' name='location_id' value='".$get_vars["location_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Injury Location' class='textbox' name='submitlocation' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Injury Location' class='textbox' name='submitlocation' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Add Injury Location' class='textbox' name='submitlocation' style='border: 1px solid #000000'><br> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_injury_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch ($post_vars["submitlocation"]) {
        case "Add Injury Location":
            if ($post_vars["location_id"] && $post_vars["location_name"]) {
                $sql = "insert into m_lib_injury_location (location_id, location_name) ".
                       "values ('".$post_vars["location_id"]."', '".$post_vars["location_name"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Update Injury Location":
            if ($post_vars["location_id"] && $post_vars["location_name"]) {
                $sql = "update m_lib_injury_location set ".
                       "location_name = '".$post_vars["location_name"]."' ".
                       "where location_id = '".$post_vars["location_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Injury Location":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_lib_injury_location where location_id = '".$post_vars["location_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        }
    }

    function display_injury_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_INJURY_LOCATION_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select location_id, location_name ".
               "from m_lib_injury_location ".
               "order by location_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&location_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function show_injury_location() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }
        $sql = "select location_id, location_name ".
               "from m_lib_injury_location ".
               "order by location_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='location_code' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($location_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
            } else {
                $ret_val .= "<font color='red'>No location codes in library.</font><br/>";
            }
            return $ret_val;
        }
    }

    function get_location_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }
        $sql = "select location_name from m_lib_injury_location where location_id = '$location_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function _injury_codes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitcode"]) {
            $this->process_injurycode($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        }
        $this->display_injurycodes($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        $this->form_injurycode($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
    }

    function form_injurycode() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["injury_id"]) {
                $sql = "select injury_id, injury_name from m_lib_injury where injury_id = '".$get_vars["injury_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $injury = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='450'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"].($get_vars["injury_id"]?"&injury_id=".$get_vars["injury_id"]:"")."' name='form_injury' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_INJURY_CODE."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_ID."</span><br> ";
        print "<input type='text' size='15' maxlength='10' ".($get_vars["injury_id"]?"disabled":"")." class='textbox' name='injury_id' value='".($injury["injury_id"]?$injury["injury_id"]:$post_vars["injury_id"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_INJURY_NAME."</span><br> ";
        print "<input type='text' size='15' maxlength='50' class='textbox' name='injury_name' value='".($injury["injury_name"]?$injury["injury_name"]:$post_vars["injury_name"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        if ($get_vars["injury_id"]) {
            // map to ICD10
            print "<tr valign='top'><td>";
            if ($post_vars["search_term"]) {
                injury::process_search($menu_id, $post_vars, $get_vars);
            }
            injury::form_search($menu_id, $post_vars, $get_vars);
            injury::display_injury_icdcode($menu_id, $post_vars, $get_vars);
            print "<br/></td></tr>";
        }
        print "<tr><td>";
        if ($get_vars["injury_id"]) {
            print "<input type='hidden' name='injury_id' value='".$get_vars["injury_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Injury Code' class='textbox' name='submitcode' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Injury Code' class='textbox' name='submitcode' style='border: 1px solid #000000'> ";
                print "<input type='submit' value = 'Delete ICD Codes' class='textbox' name='submitdisease' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Add Injury Code' class='textbox' name='submitcode' style='border: 1px solid #000000'><br> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_injurycode() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch ($post_vars["submitcode"]) {
        case "Delete ICD Codes":
            if ($post_vars["deletecode"]) {
                foreach($post_vars["deletecode"] as $key=>$value) {
                    $sql = "delete from m_lib_disease_icdcode ".
                           "where disease_id = '".$post_vars["disease_id"]."' and ".
                           "icd_code = '$value'";
                    $result = mysql_query($sql);
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
            }
            break;
        case "Search":
            // dummy entry
            // processing code is process_search()
            break;
        case "Add Injury Code":
            if ($post_vars["injury_id"] && $post_vars["injury_name"]) {
                $sql = "insert into m_lib_injury (injury_id, injury_name) ".
                       "values ('".$post_vars["injury_id"]."', '".$post_vars["injury_name"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Update Injury Code":
            if ($post_vars["injury_id"] && $post_vars["injury_name"]) {
                $sql = "update m_lib_injury set ".
                       "injury_name = '".$post_vars["injury_name"]."' ".
                       "where injury_id = '".$post_vars["injury_id"]."'";
                if ($result = mysql_query($sql)) {
                    foreach($post_vars["code"] as $key=>$value) {
                        $sql_icd = "insert into m_lib_injury_icd10 (injury_id, diagnosis_code) ".
                                   "values ('".$post_vars["injury_id"]."', '$value')";
                        $result_icd = mysql_query($sql_icd);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Injury Code":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_lib_injury where injury_id = '".$post_vars["injury_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        }
    }

    function display_injurycodes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_INJURY_CODE_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_MAPPED_ICD10."</b></td></tr>";
        $sql = "select injury_id, injury_name ".
               "from m_lib_injury ".
               "order by injury_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&injury_id=$id'>$name</a></td>";
                    print "<td>".injury::get_icd10_mapping($id)."</td>";
                    print "</tr>";
                }
            }
        }
        print "</table><br>";
    }

    function show_injury_code() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $injury_id = $arg_list[0];
        }
        $sql = "select injury_id, injury_name ".
               "from m_lib_injury ".
               "order by injury_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='injury_id' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($injury_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
            } else {
                $ret_val .= "<font color='red'>No injury codes in library.</font><br/>";
            }
            return $ret_val;
        }
    }

    function get_injury_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $injury_id = $arg_list[0];
        }
        $sql = "select injury_name from m_lib_injury where injury_id = '$injury_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function process_search() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $sql = "select diagnosis_code, description, ".
               "round(match(description) against ('".$post_vars["search_term"]."' in boolean mode),2) as relevance_score ".
               "from m_lib_icd10_en where match(description) against ('".$post_vars["search_term"]."' in boolean mode) >0.5 ".
               "order by relevance_score desc limit 100;";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<br/><span class='boxtitle'>".INSTR_ICD10_INJURY.":</span><br/><br/>";
                print "<table width='300' cellspacing='0'>";
                print "<tr bgcolor='#CCCCCC'><td>&nbsp;</td><td><b>ICD</b></td><td><b>".THEAD_RELEVANCE."</b></td><td><b>".THEAD_DESCRIPTION."</b></td></tr>";
                while(list($code, $desc, $score) = mysql_fetch_array($result)) {
                    $bgcolor = ($bgcolor=="#CCFFFF"?"#CCFF66":"#CCFFFF");
                    print "<tr bgcolor='$bgcolor' valign='top'><td><input type='checkbox' name='code[]' value='$code'/> </td>";
                    print "<td class='tinylight'><b>$code</b></td><td class='tinylight' align='right'><font color='red'><b>$score</b></font></td>";
                    print "<td class='tinylight'>$desc</td></tr>";
                }
                print "<tr><td colspan='4'><br/>";
                print "</td></tr>";
                print "</table>";
            } else {
                print "<font color='red'>No search results</font><br/>";
            }
        }
    }

    function get_icd10_mapping() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $injury_id = $arg_list[0];
        }
        $sql = "select diagnosis_code from m_lib_injury_icd10 where injury_id = '$injury_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($code) = mysql_fetch_array($result)) {
                    $ret_val .= "$code, ";
                }
                return substr($ret_val,0,strlen($ret_val)-2);
            } else {
                return "none";
            }
        }
    }

    function form_search() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='300' bgcolor='#FFFFCC'>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".INSTR_SEARCH_TERM_INJURY."</span><br> ";
        print "<input type='text' class='tinylight' name='search_term' style='border: 1px solid #000000'><br>";
        print "<input type='hidden' name='' value=''/>";
        print "</td></tr>";
        print "<tr><td>";
        print "<input type='submit' value = 'Search' class='tinylight' name='submitcode' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</table><br>";
    }

    function display_injury_icdcode() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $sql = "select c.injury_id, c.diagnosis_code, i.description ".
               "from m_lib_injury_icd10 c, m_lib_icd10_en i ".
               "where c.diagnosis_code = i.diagnosis_code and ".
               "c.injury_id = '".$get_vars["injury_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<span class='boxtitle'>".LBL_ICDCODES_INJURY."</span><br/>";
                while (list($did, $icd, $name) = mysql_fetch_array($result)) {
                    print "<input type='checkbox' name='deletecode[]' value='$icd'/> ";
                    print "<b>$icd</b> $name<br/>";
                }
                $button_icd = "<input type='submit' name='submitinjury' value='Delete ICD Code' class='textbox' style='border: 1px solid black'/>";
            }
        }
    }

// end of class
}
?>
