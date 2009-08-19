<?
class icd10 extends module{

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // ICD10 table structures derived from care2x
    //

    function icd10() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.5-".date("Y-m-d");
        $this->module = "icd10";
        $this->description = "CHITS Module - ICD10 Codes";
        // 0.3 converted to latest module conventions
        // 0.4 added consult integration
        // 0.5 added icd10::get_icd10_list()
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries
        module::set_menu($this->module, "ICD10 Codes", "LIBRARIES", "_icd10");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("LBL_ICD_CODE", "english", "ICD CODE", "Y");
        module::set_lang("LBL_ICD_DESCRIPTION", "english", "DESCRIPTION", "Y");
        module::set_lang("LBL_ICD_CLASS_SUB", "english", "SUB CLASS", "Y");
        module::set_lang("LBL_ICD_INCLUSIVE", "english", "INCLUSIVE", "Y");
        module::set_lang("LBL_ICD_EXCLUSIVE", "english", "EXCLUSIVE", "Y");
        module::set_lang("LBL_ICD_NOTES", "english", "NOTES", "Y");
        module::set_lang("LBL_ICD_REMARKS", "english", "REMARKS", "Y");
        module::set_lang("LBL_ICD_EXTRACODES", "english", "EXTRA CODES", "Y");
        module::set_lang("LBL_ICD_EXTRACLASS", "english", "EXTRA CLASS", "Y");
        module::set_lang("LBL_ICD_STANDARD_CODE", "english", "STANDARD CODE?", "Y");
        module::set_lang("LBL_ICD_SUBLEVEL", "english", "ICD SUBLEVEL", "Y");
        module::set_lang("FTITLE_ICD_CODE", "english", "ICD CODE FORM", "Y");
        module::set_lang("FTITLE_ICD10_SEARCH_FORM", "english", "ICD10 SEARCH FORM", "Y");
        module::set_lang("INSTR_SEARCH_TERM", "english", "ENTER SEARCH TERM", "Y");
        module::set_lang("THEAD_RELEVANCE", "english", "RELEVANCE", "Y");
        module::set_lang("THEAD_ICD", "english", "ICD", "Y");
        module::set_lang("THEAD_DESCRIPTION", "english", "DESCRIPTION", "Y");
        module::set_lang("LBL_SEARCH_EXAMPLES", "english", "SEARCH EXAMPLES", "Y");
        module::set_lang("FTITLE_CONSULT_ICD10", "english", "ICD10 CODES THIS CONSULT", "Y");
        module::set_lang("FTITLE_CONSULT_ICD10_HX", "english", "ICD10 CODES THIS PATIENT", "Y");
        module::set_lang("LBL_ONSET_DATE", "english", "ONSET DATE", "Y");

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // this is not auto_increment
        module::execsql("CREATE TABLE `m_lib_icd10_en` (".
            "`diagnosis_code` varchar(12) NOT NULL default '',".
            "`description` text NOT NULL,".
            "`class_sub` varchar(5) NOT NULL default '',".
            "`type` varchar(10) NOT NULL default '',".
            "`inclusive` text NOT NULL,".
            "`exclusive` text NOT NULL,".
            "`notes` text NOT NULL,".
            "`std_code` char(1) NOT NULL default '',".
            "`sub_level` tinyint(4) NOT NULL default '0',".
            "`remarks` text NOT NULL,".
            "`extra_codes` text NOT NULL,".
            "`extra_subclass` text NOT NULL,".
            "PRIMARY KEY  (`diagnosis_code`),".
            "FULLTEXT KEY `key_description` (`description`)".
            ") TYPE=MyISAM; ");

        module::execsql("CREATE TABLE `m_consult_icd10` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`diagnosis_code` varchar(12) NOT NULL default '',".
            "`icd10_timestamp` timestamp(14) NOT NULL,".
            "`user_id` float NOT NULL default '0',".
            "PRIMARY KEY  (`consult_id`,`diagnosis_code`),".
            "KEY `key_patient` (`patient_id`),".
            "CONSTRAINT `m_consult_icd10_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_icd10_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        // load ICD data from sql text file
        // using MODULE->Load Module SQL
    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_consult_icd10`;");
        module::execsql("DROP TABLE `m_lib_icd10_en`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_icd10() {
    //
    // main interface for consult related ICD10 coding
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
        if ($exitinfo = $this->missing_dependencies('icd10')) {
            return print($exitinfo);
        }
        $icd = new icd10;
        if ($post_vars["submitsearch"]) {
            $icd->process_search($menu_id, $post_vars, $get_vars);
            if ($post_vars["code"]) {
                $icd->process_consult_icd10($menu_id, $post_vars, $get_vars);
            }
        }
        $icd->form_search($menu_id, $post_vars, $get_vars);
    }

    function process_consult_icd10() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        if ($post_vars["submitcode"]) {
            switch($post_vars["submitcode"]) {
            case "Save ICD Code":
                if ($post_vars["code"]) {
                    foreach ($post_vars["code"] as $key=>$value) {
                        $sql = "insert into m_consult_icd10 (consult_id, patient_id, diagnosis_code, icd10_timestamp, user_id) ".
                               "values ('".$get_vars["consult_id"]."', '$patient_id', '$value', sysdate(), '".$_SESSION["userid"]."')";
                        $result = mysql_query($sql);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=icd10");
                }
                break;
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
                print "<b>".FTITLE_SEARCH_RESULTS.":</b><br/><br/>";
                print "<table width='300' cellspacing='0'>";
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=icd10'>";
                print "<tr bgcolor='#CCCCCC'><td>&nbsp;</td><td><b>ICD</b></td><td><b>".THEAD_RELEVANCE."</b></td><td><b>".THEAD_DESCRIPTION."</b></td></tr>";
                while(list($code, $desc, $score) = mysql_fetch_array($result)) {
                    $bgcolor = ($bgcolor=="#CCFFFF"?"#CCFF66":"#CCFFFF");
                    print "<tr bgcolor='$bgcolor' valign='top'><td><input type='checkbox' name='code[]' value='$code'/> </td>";
                    print "<td class='tinylight'><b>$code</b></td><td class='tinylight' align='right'><font color='red'><b>$score</b></font></td>";
                    print "<td class='tinylight'>$desc</td></tr>";
                }
                print "<tr><td colspan='4'><br/>";
                if ($_SESSION["priv_add"]) {
                    print "<input type='hidden' name='submitsearch' value='".$post_vars["submitsearch"]."' />";
                    print "<input type='submit' name='submitcode' value='Save ICD Code' class='textbox' style='border: 1px solid black'/>";
                }
                print "</td></tr>";
                print "</form>";
                print "</table><br/>";
            }
        }
    }

    function form_search () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=icd10' name='form_search' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_ICD10_SEARCH_FORM."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SEARCH_EXAMPLES."</span><br>";
        print "<span class='tinylight'>";
        print "<b><font color='blue'>apple banana</font></b> - find rows that contain <u>at least one</u> of these words<br/>";
        print "<b><font color='blue'>+apple +banana</font></b> - find rows that contain <u>both</u> of these words<br/>";
        print "<b><font color='blue'>+apple banana</font></b> - find rows that contain <u>apple</u> but rank it higher if it contains <u>banana</u><br/>";
        print "<b><font color='blue'>+apple -banana</font></b> - find rows that contain <u>apple</u> but not <u>banana</u><br/>";
        print "<b><font color='blue'>+apple +(&gt;banana &lt;mango)</font></b> - find rows that contain <u>apple</u> and <u>banana</u> or <u>apple</u> and <u>mango</u> (in any order) but rank <u>apple pie</u> higher than <u>apple mango</u><br/>";
        print "<b><font color='blue'>apple* </font></b> - find rows that contain <u>apple pie</u>, <u>apple peach</u>, etc.<br/>";
        print "<b><font color='blue'>\"apple pie\" </font></b> - find rows that contain <u>apple pie</u> but not <u>apple peach pie</u><br/>";
        print "</span>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".INSTR_SEARCH_TERM."</span><br> ";
        print "<input type='text' class='textbox' name='search_term' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td>";
        print "<br><input type='submit' value = 'Search' class='textbox' name='submitsearch' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function _details_icd10() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        icd10::display_consult_icd10($menu_id, $post_vars, $get_vars);
        icd10::display_consult_icd10_hx($menu_id, $post_vars, $get_vars);
    }

    function display_consult_icd10() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // process delete here
        if ($get_vars["delete_icd10_code"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_icd10 ".
                       "where consult_id = '".$get_vars["consult_id"]."' and ".
                       "diagnosis_code = '".$get_vars["delete_icd10_code"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=icd10");
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=icd10");
                }
            }
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_CONSULT_ICD10."</b><br>";
        print "</td></tr>";
        $sql = "select h.consult_id, h.diagnosis_code, l.description ".
               "from m_lib_icd10_en l, m_consult_icd10 h ".
               "where l.diagnosis_code = h.diagnosis_code and h.consult_id = '".$get_vars["consult_id"]."' ".
               "order by h.diagnosis_code";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($cid, $icd, $name) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<b>$icd</b> $name ";
                    if ($_SESSION["priv_delete"]) {
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=icd10&&delete_icd10_code=$icd'><img src='../images/delete.png' border='0' /></a> ";
                    }
                    print "<br/>";
                }
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td><font color='red'>No records.</font></td></tr>";
            }
        }
        print "</table><br>";

    }

    function display_consult_icd10_hx() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // process delete here
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_CONSULT_ICD10_HX."</b><br>";
        print "</td></tr>";
        $sql = "select h.consult_id, h.diagnosis_code, l.description ".
               "from m_lib_icd10_en l, m_consult_icd10 h ".
               "where l.diagnosis_code = h.diagnosis_code and h.patient_id = '$patient_id' ".
               "order by h.diagnosis_code";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($cid, $icd, $name) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<b>$icd</b> $name ";
                    print "<br/>";
                }
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td><font color='red'>No records.</font></td></tr>";
            }
        }
        print "</table><br>";

    }

    function get_icd10_list() {
    //
    // get complaint list for given date and patient
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $consult_date = $arg_list[1];
        }
        $sql = "select i.diagnosis_code, l.description ".
               "from m_lib_icd10_en l, m_consult_icd10 i, m_consult c ".
               "where l.diagnosis_code = i.diagnosis_code and ".
               "c.consult_id = i.consult_id and ".
               "i.patient_id = '$patient_id' and to_days(c.consult_date) = to_days('$consult_date')";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($dx_code, $dx_name) = mysql_fetch_array($result)) {
                    $dx_list .= "[$dx_code] ".$dx_name.", ";
                }
                $dx_list = substr($dx_list, 0, strlen($dx_list)-2);
                return $dx_list;
            }
        }

    }

    // ------------------ ICD10 LIBRARY METHODS -----------------

    function _icd10() {
    //
    // main ICD10
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
        if ($exitinfo = $this->missing_dependencies('icd10')) {
            return print($exitinfo);
        }

        if ($post_vars["submiticd10"]) {
            //$this->process_icd10($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        //$this->display_icd10($menu_id);
        $this->form_icd10($menu_id, $post_vars, $get_vars);
    }

    function form_icd10 () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
            if ($get_vars["diagnosis_code"]) {
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_patient' method='post'>";
        print "<tr valign='top'><td>";
        print "<font color='#99CC00' size='5'><b>".FTITLE_ICD_CODE."</b></font>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>NOTE:</b> This form is for old patients.<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_CODE."</span><br> ";
        print "<input type='text' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_firstname' value='".($patient["patient_firstname"]?$patient["patient_firstname"]:$post_vars["patient_firstname"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_DESCRIPTION."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_description' style='border: 1px solid #000000'>".($icd10["inclusive"]?$icd10["inclusive"]:$post_vars["icd10_inclusive"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_CLASS_SUB."</span><br> ";
        print "<input type='text' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_lastname' value='".($patient["patient_lastname"]?$patient["patient_lastname"]:$post_vars["patient_lastname"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_INCLUSIVE."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_inclusive' style='border: 1px solid #000000'>".($icd10["inclusive"]?$icd10["inclusive"]:$post_vars["icd10_inclusive"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_EXCLUSIVE."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_exclusive' style='border: 1px solid #000000'>".($icd10["exclusive"]?$icd10["exclusive"]:$post_vars["icd10_exclusive"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_NOTES."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_notes' style='border: 1px solid #000000'>".($icd10["notes"]?$icd10["notes"]:$post_vars["icd10_notes"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_REMARKS."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_notes' style='border: 1px solid #000000'>".($icd10["notes"]?$icd10["notes"]:$post_vars["icd10_notes"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_EXTRACODES."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_notes' style='border: 1px solid #000000'>".($icd10["notes"]?$icd10["notes"]:$post_vars["icd10_notes"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_EXTRACLASS."</span><br> ";
        print "<textarea rows='5' cols='40' class='textbox' name='icd_notes' style='border: 1px solid #000000'>".($icd10["notes"]?$icd10["notes"]:$post_vars["icd10_notes"])."</textarea><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_STANDARD_CODE."</span><br> ";
        print "<input type='checkbox' name='std_code' ".(($icd10["std_code"]?$icd10["std_code"]:$post_vars["std_code"])=="Y"?"checked":"")."> Check if standard code<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ICD_SUBLEVEL."</span><br> ";
        print "<select name='patient_gender' ".($get_vars["patient_id"]?'disabled':'')." class='textbox'>";
        print "<option ".($icd10["sub_level"]=='1'?'selected':'')." value='1'>Level 1</option>";
        print "<option ".($icd10["sub_level"]=='2'?'selected':'')." value='2'>Level 2</option>";
        print "<option ".($icd10["sub_level"]=='3'?'selected':'')." value='3'>Level 3</option>";
        print "<option ".($icd10["sub_level"]=='4'?'selected':'')." value='4'>Level 4</option>";
        print "<option ".($icd10["sub_level"]=='5'?'selected':'')." value='5'>Level 5</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<br><input type='submit' value = 'Add Code' class='textbox' name='submitcode' style='border: 1px solid #000000'><br>";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

}
?>
