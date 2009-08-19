<?
class notifiable extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function notifiable() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "notifiable";
        $this->description = "CHITS Module - Notifiable Diseases";
        // 0.3 consult and icd10 integration
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "icd10");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_NOTIFIABLE_DISEASE_LIST", "english", "NOTIFIABLE DISEASE LIST", "Y");
        module::set_lang("FTITLE_NOTIFIABLE_DISEASE_FORM", "english", "NOTIFIABLE DISEASE FORM", "Y");
        module::set_lang("LBL_DISEASE_ID", "english", "DISEASE ID", "Y");
        module::set_lang("LBL_DISEASE_NAME", "english", "DISEASE NAME", "Y");
        module::set_lang("FTITLE_NOTIFIABLE_REGISTRATION", "english", "NOTIFIABLE DISEASE REGISTRATION", "Y");
        module::set_lang("INSTR_NOTIFIABLE_DISEASE_SELECTION", "english", "SELECT NOTIFIABLE DISEASES FOR THIS PATIENT", "Y");
        module::set_lang("FTITLE_NOTIFIABLE_DISEASES_CONSULT", "english", "NOTIFIABLE DISEASE THIS CONSULT", "Y");
        module::set_lang("FTITLE_NOTIFIABLE_DISEASES_CONSULT_HX", "english", "NOTIFIABLE DISEASE HISTORY", "Y");
        module::set_lang("INSTR_ICD10_NOTIFIABLE", "english", "MAP DISEASE TO ICD10 CODE BELOW", "Y");
        module::set_lang("INSTR_SEARCH_TERM_NOTIFIABLE", "english", "TYPE SEARCH TERM BELOW", "Y");
        module::set_lang("THEAD_MAPPED_ICD10", "english", "ICD10 MAPPING", "Y");
        module::set_lang("FTITLE_NOTIFIABLE_REPORTS", "english", "NOTIFIABLE DISEASE REPORTS", "Y");
        module::set_lang("LBL_ONSET_DATE", "english", "ONSET DATE", "Y");

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
        module::set_menu($this->module, "Notifiable Diseases", "LIBRARIES", "_notifiable");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_disease_notifiable` (".
            "`disease_id` varchar(10) NOT NULL default '',".
            "`disease_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`disease_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('DIARR', 'Diarrheas');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('PNEUM', 'Pneumonias');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('BRONCH', 'Bronchitis/Bronchiolitis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('INFLU', 'Influenza');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('MEASL', 'Measles');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('TBRESP', 'TB Respiratory');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('TBMEN', 'TB Meningitis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('TBOTHER', 'TB Other Forms');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('CARDIAC', 'Diseases of the Heart');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('HPN', 'Hypertension');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('MNEOPL', 'Malignant Neoplasm');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('CPOX', 'Chicken Pox');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('DENGUE', 'Dengue Fever');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('MALARIA', 'Malaria');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('CHOLERA', 'Cholera');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('TYPH', 'Typhoid and Paratyphoid');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('HEPA', 'Viral Hepatitis A');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('HEPB', 'Viral Hepatitis B');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('RABIES', 'Human Rabies');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('DIPH', 'Diphtheria');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('NTET', 'Neonatal Tetanus');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('TET', 'Tetanus');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('POLIO', 'Poliomyelitis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('PERT', 'Whooping Cough');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('GONO', 'Gonorrhea');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('SYPH', 'Syphilis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('AIDS', 'AIDS/HIV');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('LEPR', 'Leprosy');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('FILA', 'Filariasis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('MENENC', 'Meningitis/Encephalitis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('LEPTO', 'Leptospirosis');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('POISON', 'Poisoning (Food/Chemical)');");
        module::execsql("INSERT INTO `m_lib_disease_notifiable` (`disease_id`, `disease_name`) VALUES ('MCOCC', 'Meningococcemia');");

        module::execsql("CREATE TABLE `m_lib_disease_icdcode` (".
            "`disease_id` varchar(10) NOT NULL default '',".
            "`icd_code` varchar(12) NOT NULL default '',".
            "PRIMARY KEY  (`disease_id`,`icd_code`),".
            "CONSTRAINT `m_lib_disease_icdcode_ibfk_1` FOREIGN KEY (`disease_id`) REFERENCES `m_lib_disease_notifiable` (`disease_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_consult_disease_notifiable` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`disease_id` varchar(10) NOT NULL default '',".
            "`disease_timestamp` timestamp(14) NOT NULL,".
            "`onset_date` date NOT NULL default '0000-00-00',".
            "`user_id` float NOT NULL default '0',".
            "PRIMARY KEY  (`consult_id`,`disease_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "KEY `key_disease` (`disease_id`),".
            "CONSTRAINT `m_consult_disease_notifiable_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_disease_notifiable_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_disease_notifiable_ibfk_3` FOREIGN KEY (`disease_id`) REFERENCES `m_lib_disease_notifiable` (`disease_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

    }

    function drop_tables() {

        module::execsql("set foreign_key_checks=0;");
        module::execsql("DROP TABLE `m_consult_disease_notifiable`;");
        module::execsql("DROP TABLE `m_lib_disease_icdcode`;");
        module::execsql("DROP TABLE `m_lib_disease_notifiable`;");
        module::execsql("set foreign_key_checks=1;");

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_notifiable() {
    //
    // main API to consult
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
        if ($exitinfo = $this->missing_dependencies('notifiable')) {
            return print($exitinfo);
        }
        $d = new notifiable;
        $d->notifiable_menu($menu_id, $post_vars, $get_vars);
        switch($get_vars["notifiable"]) {
        case "DISEASE":
            if ($post_vars["submitdisease"]) {
                $d->process_disease($menu_id, $post_vars, $get_vars);
            }
            $d->form_disease($menu_id, $post_vars, $get_vars);
            break;
        case "LABS":
            if ($post_vars["submitlab"]) {
                $d->process_consult_lab($menu_id, $post_vars, $get_vars);
            }
            lab::form_send_request($menu_id, $post_vars, $get_vars);
            break;
        }
    }

    function notifiable_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["notifiable"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&notifiable=DISEASE");
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&notifiable=DISEASE' class='groupmenu'>".strtoupper(($get_vars["notifiable"]=="DISEASE"?"<b>DISEASE</b>":"DISEASE"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&notifiable=LABS' class='groupmenu'>".strtoupper(($get_vars["notifiable"]=="LABS"?"<b>LABS</b>":"LABS"))."</a>";
        print "</td></tr></table><br/>";
    }

    function form_disease() {
    //
    // form for registering patients in notifiable registry
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
            if ($get_vars["pid"]) {
                $sql = "select philhealth_id, expiry_date from m_patient_philhealth ".
                       "where philhealth_id = '".$get_vars["pid"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $card = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notifiable&notifiable=DISEASE' name='form_disease' method='post'>";
        print "<tr valign='top'><td>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        print "<b>".FTITLE_NOTIFIABLE_REGISTRATION."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ONSET_DATE."</span><br> ";
        print "<input type='text' size='15' maxlength='10' class='textbox' name='onset_date' value='".$post_vars["onset_date"]."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_disease.onset_date', document.form_disease.onset_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".INSTR_NOTIFIABLE_DISEASE_SELECTION."</span><br/>";
        print notifiable::checkbox_notifiable_diseases($menu_id, $post_vars, $get_vars);
        print "</td></tr>";
        print "<tr><td><br/>";
        if ($_SESSION["priv_add"]) {
            print "<input type='hidden' name='patient_id' value='$patient_id' />";
            print "<input type='submit' value = 'Save Disease Info' class='textbox' name='submitdisease' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_disease() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch($post_vars["submitdisease"]) {
        case "Save Disease Info":
            if ($post_vars["disease"]) {
                list($month,$day,$year) = explode("/", $post_vars["onset_date"]);
                $onset_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                foreach ($post_vars["disease"] as $key=>$value) {
                    $sql = "insert into m_consult_disease_notifiable (consult_id, patient_id, disease_id, onset_date, disease_timestamp, user_id) ".
                           "values ('".$get_vars["consult_id"]."', '".$post_vars["patient_id"]."', '$value', '$onset_date', sysdate(), '".$_SESSION["userid"]."')";
                    $result = mysql_query($sql);
                }
                header("location:".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=".$get_vars["module"]."&notifiable=".$get_vars["notifiable"]);
            }
            break;
        }
    }

    function _details_notifiable() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        notifiable::display_notifiable_diseases($menu_id, $post_vars, $get_vars);
        notifiable::display_notifiable_diseases_hx($menu_id, $post_vars, $get_vars);
    }

    function display_notifiable_diseases() {
    //
    // displays notifiable diseases
    // entered for current consult
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
        // process delete here
        if ($get_vars["delete_disease_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_disease_notifiable ".
                       "where consult_id = '".$get_vars["consult_id"]."' and ".
                       "disease_id = '".$get_vars["delete_disease_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notifiable&notifiable=DISEASE");
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notifiable&notifiable=DISEASE");
                }
            }
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_NOTIFIABLE_DISEASES_CONSULT."</b><br>";
        print "</td></tr>";
        $sql = "select h.consult_id, h.disease_id, l.disease_name, h.onset_date ".
               "from m_lib_disease_notifiable l, m_consult_disease_notifiable h ".
               "where l.disease_id = h.disease_id and h.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($cid, $did, $name, $onset) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=LABS&pid=$hid'>$hid</a> $name ($onset) ";
                    if ($_SESSION["priv_delete"]) {
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notifiable&notifiable=DISEASE&delete_disease_id=$did'><img src='../images/delete.png' border='0' /></a> ";
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

    function display_notifiable_diseases_hx() {
    //
    // displays notifiable diseases
    // entered for all consults
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
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_NOTIFIABLE_DISEASES_CONSULT_HX."</b><br>";
        print "</td></tr>";
        $sql = "select distinct h.consult_id, h.disease_id, l.disease_name, h.onset_date ".
               "from m_lib_disease_notifiable l, m_consult_disease_notifiable h ".
               "where l.disease_id = h.disease_id and h.patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($cid, $did, $name, $onset) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=LABS&pid=$hid'>$hid</a> $name ($onset) <br/>";
                }
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td><font color='red'>No records.</font></td></tr>";
            }
        }
        print "</table><br>";
    }

    // --------------- NOTIFIABLE DISEASE LIBRARY METHODS ---------------

    function _notifiable() {
    //
    // main submodule for notifiable disease library
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
        if ($exitinfo = $this->missing_dependencies('notifiable')) {
            return print($exitinfo);
        }
        $n = new notifiable;
        if ($post_vars["submitdisease"]) {
            $n->process_notifiable_disease($menu_id, $post_vars, $get_vars);
        }
        $n->display_notifiable_disease($menu_id, $post_vars, $get_vars);
        $n->form_notifiable_disease($menu_id, $post_vars, $get_vars);
    }

    function form_notifiable_disease() {
    //
    // called by _notifiable()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["disease_id"]) {
                $sql = "select disease_id, disease_name ".
                       "from m_lib_disease_notifiable where disease_id = '".$get_vars["disease_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $disease = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<a name='notifiable'>";
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=".$get_vars["menu_id"].($get_vars["disease_id"]?"&disease_id=".$get_vars["disease_id"]."#notifiable":"")."' name='form_disease' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_NOTIFIABLE_DISEASE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_DISEASE_ID."</span><br> ";
        if ($get_vars["disease_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='disease_id' value='".$disease["disease_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='10' $disable maxlength='10' name='disease_id' value='".($disease["disease_id"]?$disease["disease_id"]:$post_vars["disease_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_DISEASE_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='25' name='disease_name' value='".($disease["disease_name"]?$disease["disease_name"]:$post_vars["disease_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        if ($get_vars["disease_id"]) {
            // map to ICD10
            print "<tr valign='top'><td>";
            if ($post_vars["search_term"]) {
                notifiable::process_search($menu_id, $post_vars, $get_vars);
            }
            notifiable::form_search($menu_id, $post_vars, $get_vars);
            notifiable::display_disease_icdcode($menu_id, $post_vars, $get_vars);
            print "</td></tr>";
        }
        print "<tr><td><br>";
        if ($get_vars["disease_id"]) {
            print "<input type='hidden' name='disease_id' value='".$get_vars["disease_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Disease' class='textbox' name='submitdisease' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Disease' class='textbox' name='submitdisease' style='border: 1px solid #000000'> ";
                print "<input type='submit' value = 'Delete ICD Codes' class='textbox' name='submitdisease' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Disease' class='textbox' name='submitdisease' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function display_disease_icdcode() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $sql = "select c.disease_id, c.icd_code, i.description ".
               "from m_lib_disease_icdcode c, m_lib_icd10_en i ".
               "where c.icd_code = i.diagnosis_code and ".
               "c.disease_id = '".$get_vars["disease_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<span class='boxtitle'>".LBL_ICDCODES_DISEASE."</span><br/>";
                while (list($did, $icd, $name) = mysql_fetch_array($result)) {
                    print "<input type='checkbox' name='deletecode[]' value='$icd'/> ";
                    print "<b>$icd</b> $name<br/>";
                }
                $button_icd = "<input type='submit' name='submitdisease' value='Delete ICD Code' class='textbox' style='border: 1px solid black'/>";
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
        print "<span class='boxtitle'>".INSTR_SEARCH_TERM_NOTIFIABLE."</span><br> ";
        print "<input type='text' class='tinylight' name='search_term' style='border: 1px solid #000000'><br>";
        print "<input type='hidden' name='' value=''/>";
        print "</td></tr>";
        print "<tr><td>";
        print "<input type='submit' value = 'Search' class='tinylight' name='submitdisease' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</table><br>";
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
                print "<br/><span class='boxtitle'>".INSTR_ICD10_NOTIFIABLE.":</span><br/><br/>";
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

    function process_notifiable_disease() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        if ($post_vars["submitdisease"]) {
            if ($post_vars["disease_id"] && $post_vars["disease_name"]) {
                switch($post_vars["submitdisease"]) {
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
                case "Add Disease":
                    $sql = "insert into m_lib_disease_notifiable (disease_id, disease_name) ".
                           "values ('".strtoupper($post_vars["disease_id"])."', '".$post_vars["disease_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Update Disease":
                    $sql = "update m_lib_disease_notifiable set ".
                           "disease_name = '".$post_vars["disease_name"]."' ".
                           "where disease_id = '".$post_vars["disease_id"]."'";
                    if ($result = mysql_query($sql)) {
                        foreach($post_vars["code"] as $key=>$value) {
                            $sql_icd = "insert into m_lib_disease_icdcode (disease_id, icd_code) ".
                                       "values ('".$post_vars["disease_id"]."', '$value')";
                            $result_icd = mysql_query($sql_icd);
                        }
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
                    }
                    break;
                case "Delete Disease":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_disease_notifiable where disease_id = '".$post_vars["disease_id"]."'";
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

    function display_notifiable_disease() {
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
        print "<span class='library'>".FTITLE_NOTIFIABLE_DISEASE_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_MAPPED_ICD10."</b></td></tr>";
        $sql = "select disease_id, disease_name ".
               "from m_lib_disease_notifiable ".
               "order by disease_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&disease_id=$id#notifiable'>$name</a></td>";
                    print "<td>".notifiable::get_icd10_mapping($id)."</td>";
                    print "</tr>";
                }
            }
        }
        print "</table><br>";
    }

    function get_icd10_mapping() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $disease_id = $arg_list[0];
        }
        $sql = "select icd_code from m_lib_disease_icdcode where disease_id = '$disease_id'";
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

    function checkbox_notifiable_diseases() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $disease_id = $arg_list[0];
        }
        $sql = "select disease_id, disease_name from m_lib_disease_notifiable order by disease_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='disease[]' value='$id' ".($disease_id==$id?"checked":"")."> $name<br/>";
                }
                return $ret_val;
            }
        }
    }

    function show_notifiable_disease() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $disease_id = $arg_list[0];
        }
        $sql = "select disease_id, disease_name from m_lib_disease_notifiable order by disease_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select size='10' name='disease' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($disease_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function get_notifiable_disease_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $consult_id = $arg_list[1];
        }
        $sql = "select l.disease_name from m_lib_disease_notifiable l, m_consult_disease_notifiable c ".
               "where l.disease_id = c.disease_id and c.patient_id = '$patient_id' and ".
               "c.consult_id = '$consult_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($name) = mysql_fetch_array($result))
                  {
                  $d_name .= $name.", ";
                  }
                $d_name = substr($d_name, 0, strlen($d_name)-2);
                return $d_name;
            }
        }
    }

// end of class
}
?>
