<?
class reminder extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function reminder() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "reminder";
        $this->description = "CHITS Module - Clinical Reminders";
        // 0.3 added appointment integration
        // 0.4 addedd reminder::get_home_address() and reminder::get_barangay()
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "template");
        module::set_dep($this->module, "barangay");

    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    // 1. refer to table term
    // 2. skip remarks and translationof since this term is manually entered
    //
        module::set_lang("THEAD_SMS_TEXT", "english", "SMS TEXT", "Y");
        module::set_lang("FTITLE_REMINDER_TEMPLATE", "english", "REMINDER TEMPLATES", "Y");
        module::set_lang("FTITLE_SMS_TEMPLATE_FORM", "english", "REMINDER SMS TEMPLATE FORM", "Y");
        module::set_lang("LBL_TEMPLATE_ID", "english", "TEMPLATE ID", "Y");
        module::set_lang("LBL_SMS_TEMPLATE_TEXT", "english", "SMS TEMPLATE TEXT", "Y");
        module::set_lang("FTITLE_REMINDER_DATA_FORM", "english", "REMINDER DATA FORM", "Y");
        module::set_lang("LBL_CELLULAR_PHONE", "english", "CELLULAR PHONE", "Y");
        module::set_lang("LBL_HOME_PHONE", "english", "HOME PHONE", "Y");
        module::set_lang("LBL_OFFICE_PHONE", "english", "OFFICE PHONE", "Y");
        module::set_lang("LBL_EMAIL_ADDRESS", "english", "EMAIL ADDRESS", "Y");
        module::set_lang("LBL_HOME_ADDRESS", "english", "HOME ADDRESS", "Y");
        module::set_lang("LBL_BARANGAY", "english", "BARANGAY", "Y");
        module::set_lang("FTITLE_REMINDERS_FOR_PATIENT", "english", "REMINDERS FOR THIS PATIENT", "Y");
        module::set_lang("LBL_APPT_TYPE", "english", "APPOINTMENT TYPE", "Y");
        module::set_lang("THEAD_APPT", "english", "APPT TYPE", "Y");
        module::set_lang("LBL_APPT_DATE", "english", "APPT DATE", "Y");
        module::set_lang("LBL_TEMPLATE", "english", "SMS TEMPLATE", "Y");

    }

    function init_help() {
    }

    function init_menu() {
    //
    // menu entries
    // use multiple inserts (watch out for ;)
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::set_menu($this->module, "Reminder Templates", "LIBRARIES", "_sms_template");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
    //
    // create module tables
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }

        module::execsql("CREATE TABLE `m_patient_reminder_data` (".
            "`patient_id` float NOT NULL default '0',".
            "`cellular_phone` varchar(50) NOT NULL default '',".
            "`home_phone` varchar(50) NOT NULL default '',".
            "`office_phone` varchar(50) NOT NULL default '',".
            "`email_address` varchar(50) NOT NULL default '',".
            "`home_address` tinytext NOT NULL,".
            "`barangay` int(11) NOT NULL default '0',".
            "`reg_timestamp` timestamp(14) NOT NULL,".
            "`user_id` float NOT NULL default '0',".
            "PRIMARY KEY  (`patient_id`),".
            "CONSTRAINT `m_patient_reminder_data_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_reminder` (".
            "`reminder_id` float NOT NULL auto_increment,".
            "`patient_id` float NOT NULL default '0',".
            "`consult_id` float NOT NULL default '0',".
            "`reminder_text` varchar(160) NOT NULL default '',".
            "`sent_flag` char(1) NOT NULL default 'N',".
            "`user_id` float NOT NULL default '0',".
            "`reminder_timestamp` timestamp(14) NOT NULL,".
            "`reminder_date` date NOT NULL default '0000-00-00',".
            "`reminder_time` time NOT NULL default '00:00:00',".
            "`reminder_freq` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`reminder_id`),".
            "KEY `key_patient` (`patient_id`),".
            "KEY `key_consult` (`consult_id`),".
            "CONSTRAINT `m_consult_reminder_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_reminder_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_reminder_sms_template` (".
            "`appointment_id` varchar(10) NOT NULL default '',".
            "`template_text` varchar(120) NOT NULL default '',".
            "PRIMARY KEY  (`appointment_id`),".
            "CONSTRAINT `m_lib_reminder_sms_template_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `m_lib_appointment` (`appointment_id`) ON DELETE CASCADE ON UPDATE CASCADE".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("INSERT INTO m_lib_reminder_sms_template ".
            "VALUES ('NTPM','_SENDER_: Para kay _RECEIVER_ ".
            "huwag kalimutang bumalik sa _APPT_DATE_ para sa gamutan natin. Salamat.');");
        module::execsql("INSERT INTO m_lib_reminder_sms_template VALUES ".
            "('PRENATAL','_SENDER_: Dear _RECEIVER_, huwag ".
            "kalimutang bumalik sa _APPT_DATE_ para sa iyong susunod na ".
            "prenatal checkup. Salamat.');");
        module::execsql("INSERT INTO m_lib_reminder_sms_template VALUES ".
            "('VACC','_SENDER_: Para kay _RECEIVER_ huwag ".
            "kalimutang bumalik sa _APPT_DATE_ para sa bakuna ng inyong anak.');");

    }

    function drop_tables() {
    //
    // called from delete_module()
    //
        module::execsql("DROP TABLE `m_patient_reminder_data`;");
        module::execsql("DROP TABLE `m_consult_reminder`;");
        module::execsql("DROP TABLE `m_lib_reminder_sms_template`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_reminder() {
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('reminder')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $r = new reminder;
        $r->reminder_menu($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        if ($post_vars["submitreminder"]) {
            $r->process_reminder($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        switch ($get_vars["reminder"]) {
        case "SEND":
            if ($r->get_reminder_id($patient_id)) {
                $r->form_send_reminder($menu_id, $post_vars, $get_vars);
            } else {
                print "<font color='red'>No reminder data</font><br/>";
            }
            break;
        case "DATA":
        default:
            $r->form_reminder_data($menu_id, $post_vars, $get_vars);
            break;
        }
    }

    function reminder_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&reminder=DATA".($get_vars["rem_id"]?"&rem_id=".$get_vars["rem_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["reminder"]=="DATA"?"<b>REMINDER DATA</b>":"REMINDER DATA"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&reminder=SEND".($get_vars["rem_id"]?"&rem_id=".$get_vars["rem_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["reminder"]=="SEND"?"<b>SEND REMINDER</b>":"SEND REMINDER"))."</a>";
        print "</td></tr></table><br/>";
    }

    function form_reminder_data() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
            if ($post_vars["patient_id"]) {
                $sql = "select patient_id, cellular_phone, home_phone, office_phone, ".
                       "email_address, home_address, barangay ".
                       "from m_patient_reminder_data ".
                       "where patient_id = '".$post_vars["patient_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $reminder = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<a name='reminderdata'>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=reminder&reminder=DATA' name='form_reminder_data' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_REMINDER_DATA_FORM."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        // FIRST VISIT INFO
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        print "<table bgcolor='#CCFFCC' width='300' cellpadding='3'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CELLULAR_PHONE."</span><br> ";
        print "<input type='text' class='tinylight' size='15' maxlength='50' name='cellular_phone' value='".($reminder["cellular_phone"]?$reminder["cellular_phone"]:$post_vars["cellular_phone"])."' style='border: 1px solid #000000'><br/> ";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_HOME_PHONE."</span><br> ";
        print "<input type='text' class='tinylight' size='15' maxlength='50' name='home_phone' value='".($reminder["home_phone"]?$reminder["home_phone"]:$post_vars["home_phone"])."' style='border: 1px solid #000000'><br/> ";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_OFFICE_PHONE."</span><br> ";
        print "<input type='text' class='tinylight' size='15' maxlength='50' name='office_phone' value='".($reminder["office_phone"]?$reminder["office_phone"]:$post_vars["office_phone"])."' style='border: 1px solid #000000'><br/> ";
        print "</td></tr>";
        print "</table>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        // email, address, barangay
        print "<table bgcolor='#CCFF99' width='300' cellpadding='3'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_EMAIL_ADDRESS."</span><br> ";
        print "<input type='text' class='tinylight' size='35' maxlength='50' name='email_address' value='".($reminder["email_address"]?$reminder["email_address"]:$post_vars["email_address"])."' style='border: 1px solid #000000'><br/> ";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_HOME_ADDRESS."</span><br> ";
        print "<textarea class='tinylight' name='home_address' cols='35' rows='5' style='border: 1px solid #000000'>".($reminder["home_address"]?$reminder["home_address"]:$post_vars["home_address"])."</textarea><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_BARANGAY."</span><br> ";
        print barangay::show_barangays($reminder["barangay"]);
        print "</td></tr>";
        print "</table>";
        print "</td></tr>";
        print "<tr><td><br/>";
        print "<input type='hidden' name='patient_id' value='$patient_id' />";
        if ($post_vars["patient_id"]) {
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Data' class='textbox' name='submitreminder' style='border: 1px solid #000000'> ";
                print "<input type='submit' value = 'Delete Data' class='textbox' name='submitreminder' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Save Reminder Data' class='textbox' name='submitreminder' style='border: 1px solid #000000'><br>";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function form_send_reminder() {
    //
    // called from _sms()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder&reminder=SEND' name='form_sms' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>TO SEND OUT REMINDER:";
        print "<ol><li>Select appointment date. Reminder will be sent 1 day before appointment date.</li>";
        print "<li>Select reminder template.</li>";
        print "<li>Modify SMS message if you need to add anything.</li>";
        print "<li>Click on <u>Send SMS</u>. Appointment register is automatically updated.</li>";
        print "</ol>";
        print "</b>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPT_DATE."</span><br> ";
        print "<input type='text' size='15' maxlength='10' class='textbox' name='appt_date' value='".($post_vars["appt_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_sms.appt_date', document.form_sms.appt_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TEMPLATE."</span><br> ";
        print reminder::show_templates($post_vars["template_id"]);
        if ($post_vars["template_id"] && $post_vars["appt_date"]) {
            $msg_body = reminder::get_template_text($post_vars["template_id"]);
            $msg_body = ereg_replace("_SENDER_", "HEALTH CTR", $msg_body);
            $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
            $name = patient::get_name($patient_id);
            $msg_body = ereg_replace("_RECEIVER_", "$name", $msg_body);
            $msg_body = ereg_replace("_APPT_DATE_", $post_vars["appt_date"], $msg_body);
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SMS_MESSAGE."</span><br> ";
        print "<textarea name='msg_body' onChange='document.form_sms.count_display.value=document.form_sms.msg_body.value.length;' ".
              "onkeypress='document.form_sms.count_display.value=document.form_sms.msg_body.value.length+1;' ".
              "onBlur='document.form_sms.count_display.value=document.form_sms.msg_body.value.length;' rows='5' cols='30' class='textbox'>".
              (isset($msg_body)?stripslashes($msg_body):'')."</textarea>".
              "<br><span class='textbox'>Character count</span> ".
              "<input type='text' name='count_display' class='textbox' size='4' style='background: #ffff00;' readonly><br/>".
              "<span class='small'>Max of 160 characters</span>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Send SMS' class='textbox' name='submitreminder' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_reminder() {
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
        if ($post_vars["submitreminder"]) {
            switch($post_vars["submitreminder"]) {
            case "Send SMS":
                if ($post_vars["appt_date"]) {
                    list($month, $day, $year) = explode("/", $post_vars["appt_date"]);
                    $reminder_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                    $sql = "insert into m_consult_reminder (patient_id, consult_id, reminder_text, sent_flag, user_id, reminder_timestamp, reminder_date, reminder_time, reminder_freq) ".
                           "values ('$patient_id', '".$get_vars["consult_id"]."', '".$post_vars["msg_body"]."', 'N', '".$_SESSION["userid"]."', sysdate(), from_days(to_days('$reminder_date')-1), '08:00:00', '1')";
                    if ($result = mysql_query($sql)) {
                        // update appointment schedule automatically too
                        $sql_appt = "insert into m_consult_appointments (visit_date, consult_id, ".
                                    "schedule_timestamp, user_id, patient_id, appointment_id, reminder_flag) ".
                                    "values ('$reminder_date', '".$get_vars["consult_id"]."', sysdate(), '".$_SESSION["userid"]."', ".
                                    "'$patient_id', '".$post_vars["template_id"]."', 'Y')";
                        if ($result_appt = mysql_query($sql_appt)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder&reminder=SEND");
                        }
                    }
                }
                break;
            case "Save Reminder Data":
                $sql = "insert into m_patient_reminder_data (patient_id, cellular_phone, home_phone, office_phone, email_address, home_address, barangay, reg_timestamp, user_id) ".
                       "values ('".$post_vars["patient_id"]."', '".$post_vars["cellular_phone"]."', '".$post_vars["home_phone"]."', '".$post_vars["office_phone"]."', '".$post_vars["email_address"]."', '".$post_vars["home_address"]."', '".$post_vars["barangay"]."', sysdate(), '".$_SESSION["userid"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder");
                }
                break;
            case "Update Data":
                $sql = "update m_patient_reminder_data set ".
                       "cellular_phone = '".$post_vars["cellular_phone"]."', ".
                       "home_phone = '".$post_vars["home_phone"]."', ".
                       "office_phone = '".$post_vars["office_phone"]."', ".
                       "email_address = '".$post_vars["email_address"]."', ".
                       "home_address = '".$post_vars["home_address"]."', ".
                       "barangay = '".$post_vars["barangay"]."' ".
                       "where patient_id = '".$post_vars["patient_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder");
                }
                break;
            case "Delete Data":
                if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                    $sql = "delete from m_patient_reminder_data where patient_id = '".$post_vars["patient_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder");
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder");
                    }
                }
                break;
            }
        }
    }

    function _details_reminder() {
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
        reminder::display_reminder_data($menu_id, $post_vars, $get_vars);
        if (reminder::get_reminder_id($patient_id)) {
            reminder::display_consult_reminders($menu_id, $post_vars, $get_vars);
        }

    }

    function get_reminder_id() {
    //
    // answers the question: does this patient have contact info?
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select patient_id from m_patient_reminder_data where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($id) = mysql_fetch_array($result);
                return $id;
            }
            return 0;
        }
    }

    function display_reminder_data() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder&reminder=DATA'>";
        print "<table width='300' cellpadding='2' style='border: 1px dotted black'><tr><td>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        print "<b>NAME: ".strtoupper(patient::get_name($patient_id))."</b><br/>";

        $sql = "select patient_id, cellular_phone, home_phone, ".
               "office_phone, email_address, home_address, ".
               "barangay, reg_timestamp, user_id ".
               "from m_patient_reminder_data ".
               "where patient_id = $patient_id";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $reminder = mysql_fetch_array($result);
                print "<span class='tinylight'>";
                if (strlen($reminder["cellular_phone"])>0) {
                    print "CELLULAR PHONE: ".$reminder["cellular_phone"]."<br/>";
                }
                if (strlen($reminder["home_phone"])>0) {
                    print "HOME PHONE: ".$reminder["home_phone"]."<br/>";
                }
                if (strlen($reminder["office_phone"])>0) {
                    print "OFFICE PHONE: ".$reminder["office_phone"]."<br/>";
                }
                if (strlen($reminder["email_address"])>0) {
                    print "EMAIL ADDRESS: ".$reminder["email_address"]."<br/>";
                }
                if (strlen($reminder["home_address"])>0) {
                    print "HOME ADDRESS: ".$reminder["home_address"]."<br/>";
                }
                if ($reminder["barangay"]) {
                    print "BARANGAY: ".barangay::barangay_name($reminder["barangay"])."<br/>";
                }
                print "<br/></span>";
                print "<input type='hidden' name='patient_id' value='".$reminder["patient_id"]."' />";
                if ($_SESSION["priv_update"] || $_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdetail' value='Edit Reminder Data' class='tinylight' style='border: 1px solid black' />";
                }
            } else {
                print "<font color='red'>No reminder data</font><br/>";
            }
        }
        print "</td></tr></table>";
        print "</form>";
    }

    function display_consult_reminders() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<b>".FTITLE_REMINDERS_FOR_PATIENT."</b><br/><br/>";
        $sql = "select reminder_id, reminder_date ".
               "from m_consult_reminder where consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $date) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<font color='red'>".module::pad_zero($id,7)."</font> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder&reminder=DATA&reminder_id=$id'>$date</a><br/>";
                    if ($get_vars["reminder_id"]==$id) {
                        reminder::display_consult_reminder_detail($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "<font color='red'>No reminders set for this consult.</font><br/>";
            }
        }
    }


    function display_consult_reminder_detail() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // do processing here
        if ($post_vars["submitsms"]=="Delete SMS") {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_reminder where reminder_id = '".$post_vars["reminder_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder&reminder=".$get_vars["reminder"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=reminder&reminder=".$get_vars["reminder"]);
                }
            }
        }
        $sql = "select reminder_id, reminder_text, patient_id, consult_id, sent_flag, user_id, reminder_date ".
               "from m_consult_reminder where reminder_id = '".$get_vars["reminder_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $sms = mysql_fetch_array($result);
                print "<form method='post' action=''>";
                print "<table style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "REMINDER NO: <font color='red'>".module::pad_zero($sms["reminder_id"],7)."</font><br/>";
                print "SENT: ".$sms["sent_flag"]."<br/>";
                print "REMINDER DATE: ".$sms["reminder_date"]."<br/>";
                print "CREATED BY: ".user::get_username($sms["user_id"])."<br/>";
                print "<hr size='1'/>";
                print "REMINDER TEXT:<br/>";
                print $sms["reminder_text"]."<br/>";
                print "<br/>";
                print "<input type='hidden' name='reminder_id' value='".$sms["reminder_id"]."' />";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitsms' value='Delete SMS' class='tinylight' style='border: 1px solid black'/>";
                }
                print "</span>";
                print "</td></tr></table>";
                print "</form>";
            }
        }
    }

    function get_home_address() {
    //
    // use this as an alternative for patient address
    // if patient has no family address
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select home_address from m_patient_reminder_data where patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($address) = mysql_fetch_array($result);
                return $address;
            }
        }
    }

    function get_barangay() {
    //
    // use this as an alternative for barangay
    // if patient has no family id
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select l.barangay_name ".
               "from m_patient_reminder_data r, m_lib_barangay l ".
               "where r.barangay = l.barangay_id and r.patient_id = '$patient_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($barangay) = mysql_fetch_array($result);
                return $barangay;
            }
        }
    }

    // ---------------- LIBRARY METHODS ------------------------
    function _sms_template() {
    //
    // main method for sms template
    // calls form_template(), process_template(), display_template()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('reminder')) {
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
        if ($post_vars["submittemplate"]) {
            $this->process_template($menu_id, $post_vars, $get_vars);
        }
        $this->display_template($menu_id, $post_vars, $get_vars);
        $this->form_template($menu_id, $post_vars, $get_vars, $isadmin);
    }

    function get_template_text() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $appointment_id = $arg_list[0];
        }
        $sql = "select template_text from m_lib_reminder_sms_template where appointment_id = '$appointment_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($text) = mysql_fetch_array($result);
                return $text;
            }
        }
    }

    function display_template() {
    //
    // called from _sms_template()
    //
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
        print "<span class='library'>".FTITLE_REMINDER_TEMPLATE."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_APPT."</b></td><td><b>".THEAD_SMS_TEXT."</b></td></tr>";
        $sql = "select s.appointment_id, appointment_name, s.template_text ".
               "from m_lib_reminder_sms_template s, m_lib_appointment l ".
               "where s.appointment_id = l.appointment_id ".
               "order by l.appointment_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $appt, $text) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&template_id=$id#form_template'>$id</a></td><td>$appt</td><td>$text</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function show_templates() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $template_id = $arg_list[0];
        }
        $sql = "select s.appointment_id, l.appointment_name ".
               "from m_lib_reminder_sms_template s, m_lib_appointment l ".
               "where s.appointment_id = l.appointment_id ".
               "order by l.appointment_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='template_id' class='textbox' onchange='this.form.submit();'>";
                $ret_val .= "<option value=''>Select Template</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($template_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select><br/>";
                return $ret_val;
            } else {
                return "<font color='red'>No templates</font><br/>";
            }
        }
    }

    function form_template() {
    //
    // called from _sms_template()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $isadmin = $arg_list[3];
            if ($get_vars["template_id"]) {
                $sql = "select appointment_id, template_text ".
                       "from m_lib_reminder_sms_template where appointment_id = '".$get_vars["template_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $template = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<a name='form_template'>";
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_sms_template' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_SMS_TEMPLATE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>NOTE: This is a template for sending SMS reminders. ".
              "You can use the following variables: _RECEIVER_, _SENDER_, ".
              "_APPT_DATE_, _APPT_LOCATION_. These will be substituted with ".
              "actual values.</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPT_TYPE."</span><br> ";
        print appointment::show_appointment($template["appointment_id"]);
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SMS_TEMPLATE_TEXT."</span><br> ";
        print "<input type='text' class='textbox' size='40' maxlength='120' name='template_text' value='".stripslashes(($template["template_text"]?$template["template_text"]:$post_vars["template_text"]))."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["template_id"]) {
            print "<input type='hidden' name='appointment_id' value='".$get_vars["appointment_id"]."'>";
            print "<input type='submit' value = 'Update Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_template() {
    //
    // called from _sms_template()
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        if ($post_vars["submittemplate"]) {
            if ($post_vars["appointment"] && $post_vars["template_text"]) {
                switch($post_vars["submittemplate"]) {
                case "Add Template":
                    $sql = "insert into m_lib_reminder_sms_template (appointment_id, template_text) ".
                           "values ('".$post_vars["appointment"]."', '".addslashes($post_vars["template_text"])."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                    break;
                case "Update Template":
                    $sql = "update m_lib_reminder_sms_template set ".
                           "template_text = '".addslashes($post_vars["template_text"])."' ".
                           "where appointment_id = '".$post_vars["appointment"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                    }
                    break;
                case "Delete Template":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_reminder_sms_template ".
                               "where appointment_id = '".$post_vars["appointment"]."'";
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
        }
    }

// end of class
}
?>
