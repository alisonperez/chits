<?
class lab extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function lab() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD, darth_ali';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "lab";
        $this->description = "CHITS Module - Laboratory";
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
        module::set_lang("FTITLE_LAB_EXAMS", "english", "LAB EXAMS", "Y");
        module::set_lang("FTITLE_LAB_EXAM_FORM", "english", "LAB EXAM FORM", "Y");
        module::set_lang("LBL_LAB_ID", "english", "LAB ID", "Y");
        module::set_lang("LBL_LAB_NAME", "english", "LAB NAME", "Y");
        module::set_lang("LBL_LAB_MODULES", "english", "LAB MODULES", "Y");
        module::set_lang("FTITLE_PENDING_LAB_REQUESTS", "english", "PENDING LAB REQUESTS", "Y");
        module::set_lang("FTITLE_COMPLETED_LAB_REQUESTS", "english", "COMPLETED LAB REQUESTS", "Y");
        module::set_lang("FTITLE_LAB_REQUEST", "english", "MAKE LAB REQUESTS", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("THEAD_LAB_MODULE", "english", "LAB MODULE", "Y");
        module::set_lang("LBL_COLLECTION_DATE", "english", "COLLECTION DATE", "Y");
        module::set_lang("INSTR_CLICK_VIEW_RESULTS", "english", "CLICK THIS TO VIEW RESULTS", "Y");

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
        module::set_menu($this->module, "Lab Exams", "LIBRARIES", "_lab_exams");
        module::set_menu($this->module, "Laboratory", "CONSULTS", "_laboratory");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // lab exams
        module::execsql("CREATE TABLE `m_lib_laboratory` (".
            "`lab_id` varchar(10) NOT NULL default '',".
            "`lab_module` varchar(25) NOT NULL default '',".
            "`lab_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`lab_id`),".
            "KEY `key_module` (`lab_module`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('CBC', 'Complete Blood Count', 'hematology')");
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('HGB', 'Hgb/Hct', 'hematology')");
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('SPT', 'NTP Sputum Exam', 'sputum')");
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('CXR', 'Chest X-Ray', 'radiology')");
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('URN', 'Urinalysis', 'urinalysis')");
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('FEC', 'Fecalysis', 'fecalysis')");
        module::execsql("insert into m_lib_laboratory (lab_id, lab_name, lab_module) values ('CCS', 'Cervical Cancer Screening', 'ccs')");

        // lab requests
        module::execsql("CREATE TABLE IF NOT EXISTS `m_consult_lab` (
	   `request_id` float NOT NULL AUTO_INCREMENT,
	   `patient_id` float NOT NULL DEFAULT '0',
	   `lab_id` varchar(10) NOT NULL DEFAULT '',
	   `request_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	   `request_user_id` float NOT NULL DEFAULT '0',
	   `consult_id` float NOT NULL DEFAULT '0',
	   `request_done` char(1) NOT NULL DEFAULT 'N',
	   `done_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	   `done_user_id` float NOT NULL DEFAULT '0',
	    PRIMARY KEY (`request_id`),
	    KEY `key_user1` (`request_user_id`),
	    KEY `key_patient` (`patient_id`),
	    KEY `key_user2` (`done_user_id`),
	    KEY `key_consult` (`consult_id`),".
            "FOREIGN KEY (`consult_id`) REFERENCES `m_consult`(`consult_id`) ON DELETE CASCADE, ".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient`(`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");


    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_laboratory`;");
        module::execsql("SET foreign_key_checks=0; DROP TABLE `m_consult_lab`; SET foreign_key_checks=1; ");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _laboratory () {
    //
    // main module for labs
    // assumption is each lab exam is a type in a class of exams
    // examples:
    // 1. chest x-ray belongs to lab class radiology
    // 2. cbc belongs to lab class hematology
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('lab')) {
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
        $l = new lab;
        
        
        
        print "<table width='600'>";
        print "<tr><td>";
        $l->request_info($menu_id, $post_vars, $get_vars);
        print "</td></tr>";
        print "<tr><td>";
        if ($get_vars["consult_id"]) {
            $l->patient_lab_menu($menu_id, $post_vars, $get_vars);
        }
        print "</td></tr>";
        print "<tr><td>";
        if ($get_vars["module"]) {
            // this establishes access to API of lab modules
            $eval_string = $get_vars["module"]."::_consult_lab_".$get_vars["module"]."(\$menu_id, \$post_vars, \$get_vars, \$validuser, \$isadmin);";
            // this executes lab module
            if (class_exists($get_vars["module"])) {                                
                eval('$eval_string');
            } else {
                print "<b><font color='red'>WARNING:</font> module ".$get_vars["module"]." not loaded.</b><br/>";
            }
        }
        print "</td></tr>";
        print "</table>";        
    }

    function request_info() {
    //
    //  shows patients with lab requests
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
        $sql = "select count(l.request_id), l.consult_id, p.patient_id, p.patient_lastname, p.patient_firstname ".
               "from m_consult_lab l, m_patient p where l.patient_id = p.patient_id ".
               "and l.request_done = 'N' group by l.patient_id";
        if ($result = mysql_query($sql)) {
            print "<table width=600 bgcolor='#FFFFFF' cellpadding='3' cellspacing='0' style='border: 2px solid black'>";
            print "<tr><td>";
            print "<span class='patient'>LAB REQUESTS</span><br>";
            if (mysql_num_rows($result)) {
                $i=0;
                while (list($count, $cid, $pid, $plast, $pfirst) = mysql_fetch_array($result)) {
                    $consult_array[$i] = "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=$cid'><b>$plast, $pfirst</b></a> [$count]";
                    $i++;
                }
                print $this->columnize_list($consult_array);
            } else {
                print "<font color='red'>No requests available.</font>";
            }
            print "</td></tr>";
            print "</table>";
        }
    }

    function patient_lab_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // show pending lab requests as a menu
        $sql = "select c.request_id, l.lab_id, l.lab_name, l.lab_module ".
               "from m_lib_laboratory l, m_consult_lab c ".
               "where l.lab_id = c.lab_id and ".
               "c.request_done = 'N' and ".
               "c.consult_id = '".$get_vars["consult_id"]."' order by lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table cellpadding='1' cellspacing='1' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td>";
                while (list($rid, $lid, $name, $mod) = mysql_fetch_array($result)) {
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&module=$mod&request_id=$rid&lab_id=$lid' class='groupmenu'>".strtoupper(($get_vars["module"]==$mod?"<b>$name</b>":$name))."</a>";
                }
                print "</td></tr></table>";
            }
        }
    }

    function display_requests() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // delete routine
        if ($get_vars["delete_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_lab where request_id = '".$get_vars["delete_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]);
                }
            }
        }
        print "<b>".FTITLE_PENDING_LAB_REQUESTS."</b><br/><br/>";
        $sql = "select c.request_id, l.lab_name, l.lab_module, date_format(c.request_timestamp, '%a %d %b %Y, %h:%i%p') from m_lib_laboratory l, m_consult_lab c where l.lab_id = c.lab_id and c.consult_id ='$get_vars[consult_id]' and c.done_timestamp = '0000-00-00' AND request_done='N'";
               
        
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table width='300'><tr><td>";
                while (list($id, $name, $mod, $ts1, $ts2) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' align='bottom' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=$mod&request_id=$id#sputum_form' title='".INSTR_CLICK_VIEW_RESULTS."'>$name</a> ";
                    if ($_SESSION["priv_delete"]) {
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&delete_id=$id'><img src='../images/delete.png' border='0'/></a>";
                    }
                    print "<br/>";
                    print "&nbsp;&nbsp;&nbsp;$ts1<br/>";
                    if ($get_vars["request_id"]==$id && $get_vars["module"]==$mod) {
                        // access result API for lab exam
                        // <module_name>::_consult_lab_<module_name>_results()
                        $eval_string = "$mod::_consult_lab_".$get_vars["module"]."(\$menu_id, \$post_vars, \$get_vars);";
                        if (class_exists($mod)) {                        
                            //echo $eval_string;    
                            eval("$eval_string");
                        } else {
                            print "<b><font color='red'>WARNING:</font> $mod missing.</b><br/>";
                        }
                    }
                }
                print "</td></tr></table>";
            } else {
                print "<font color='red'>No requests.</font><br/>";
            }
        }
        print "<br/>";
        print "<b>".FTITLE_COMPLETED_LAB_REQUESTS."</b><br/><br/>";
        $sql = "select c.request_id, l.lab_name, l.lab_module, date_format(c.request_timestamp, '%a %d %b %Y, %h:%i%p') from m_lib_laboratory l, m_consult_lab c where l.lab_id = c.lab_id and c.consult_id = '$get_vars[consult_id]' and c.done_timestamp <> '0000-00-00' and c.request_done='Y'";
        
        if ($result = mysql_query($sql)) {                
            if (mysql_num_rows($result)) {
                print "<table><tr><td>";
                while (list($id, $name, $mod, $ts1, $ts2) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' align='left' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=$mod&request_id=$id#sputum_result' title='".INSTR_CLICK_VIEW_RESULTS."'>$name</a> ";
                    print "<br/>&nbsp;&nbsp;&nbsp;$ts1<br/>";
                    if ($get_vars["request_id"]==$id && $get_vars["module"]==$mod) {
                        // access result API for lab exam
                        // <module_name>::_consult_lab_<module_name>_results()
                        $eval_string = "$mod::_consult_lab_".$get_vars["module"]."_results(\$menu_id, \$post_vars, \$get_vars);";
                        if (class_exists($mod)) {
                            //echo $eval_string;                            
                            //sputum::_consult_lab_sputum($_GET["menu_id"],$_POST,$_GET);
                            eval("$eval_string");
                        } else {
                            print "<b><font color='red'>WARNING:</font> $mod missing.</b><br/>";
                        }
                    }
                }
                print "</td></tr></table>";
            } else {
                print "<font color='red'>No requests.</font><br/>";
            }
        }
    }

    function process_send_request() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        switch($post_vars["submitlab"]) {
        case "Send Request":
            mysql_query("ALTER TABLE `m_consult_lab` DROP PRIMARY KEY, ADD PRIMARY KEY(`request_id`)");
            if ($post_vars["lab_exam"]) {
                foreach($post_vars["lab_exam"] as $key=>$value) {
                    $sql = "insert into m_consult_lab (consult_id, patient_id, lab_id, request_timestamp, request_user_id) ".
                           "values ('".$get_vars["consult_id"]."', '$patient_id', '$value', sysdate(), '".$_SESSION["userid"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]);
                    }
                }
            }
            break;
        case "Print Referral":
            break;
        }
    }

    function form_send_request() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=LABS' name='form_send_request' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_LAB_REQUEST."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LABS."</span><br> ";
        print lab::checkbox_lab_exams();
        print "</td></tr>";
        print "<tr><td><br>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Send Request' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Print Referral' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    // --------------------- LAB LIBRARY METHODS ------------------------

    function _lab_exams() {
    //
    // library submodule for labs
    // calls form_lab()
    //       display_lab()
    //       process_lab()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('lab')) {
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
        $l = new lab;
        if ($post_vars["submitlab"]) {
            $l->process_lab($menu_id, $post_vars, $get_vars);
        }
        $l->display_lab($menu_id, $post_vars, $get_vars);
        $l->form_lab($menu_id, $post_vars, $get_vars);
    }

    function checkbox_lab_exams() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $lab_id = $arg_list[0];
        }
        $sql = "select lab_id, lab_name from m_lib_laboratory order by lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='lab_exam[]' value='$id'> $name<br>";
                }
                return $ret_val;
            }
        }
    }

    function get_lab_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $lab_id = $arg_list[0];
        }
        $sql = "select lab_name from m_lib_laboratory where lab_id = '$lab_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function show_lab_exams() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $lab_id = $arg_list[0];
        }
        $sql = "select lab_id, lab_name from m_lib_laboratory order by lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='lab_exam' class='textbox'>";
                $ret_val .= "<option value=''>Select Exam</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id'>$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function form_lab() {
    //
    // called by _lab_exams()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["lab_id"]) {
                $sql = "select lab_id, lab_name ".
                       "from m_lib_laboratory where lab_id = '".$get_vars["lab_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $lab = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_lab' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_LAB_EXAM_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAB_ID."</span><br> ";
        if ($get_vars["lab_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='lab_id' value='".$get_vars["lab_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='lab_id' value='".($lab["lab_id"]?$lab["lab_id"]:$post_vars["lab_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAB_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='lab_name' value='".($lab["lab_name"]?$lab["lab_name"]:$post_vars["lab_name"])."' style='border: 1px solid #000000'><br><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAB_MODULES."</span><br> ";
        print module::show_modules();
        print "<br/><small>WARNING: Not all modules shown are lab modules. Please ask administrator if the appropriate lab module has been uploaded.</small><br/>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["lab_id"]) {
            print "<input type='hidden' name='lab_id' value='".$get_vars["lab_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_lab() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitlab"]) {
            if ($post_vars["lab_id"] && $post_vars["lab_name"]) {
                switch($post_vars["submitlab"]) {
                case "Add Lab Exam":
                    $sql = "insert into m_lib_laboratory (lab_id, lab_name, lab_module) ".
                           "values ('".strtoupper($post_vars["lab_id"])."', '".$post_vars["lab_name"]."', '".$post_vars["module"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Lab Exam":
                    $sql = "update m_lib_laboratory set ".
                           "lab_name = '".$post_vars["lab_name"]."', ".
                           "lab_module = '".$post_vars["module"]."' ".
                           "where lab_id = '".$post_vars["lab_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Lab Exam":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_laboratory where lab_id = '".$post_vars["lab_id"]."'";
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

    function display_lab() {
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
        print "<span class='library'>".FTITLE_LAB_EXAMS."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_LAB_MODULE."</b></td></tr>";
        $sql = "select lab_id, lab_name, lab_module from m_lib_laboratory order by lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $module) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&lab_id=$id'>$name</a></td><td>$module</td></tr>";
                }
            }
        }
        print "</table><br>";
    }


// end of class
}
?>
