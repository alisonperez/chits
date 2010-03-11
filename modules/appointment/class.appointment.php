<?
class appointment extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function appointment() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "appointment";
        $this->description = "CHITS Module - Appointments";
        // 0.3 added foreign key for appointment_id to m_consult_appointments
        // 0.4 added unique key constraint (consult_id, appointment_id) to m_consult_appointment
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    // use multiple inserts
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "calendar");
        module::set_dep($this->module, "ptgroup");
        module::set_dep($this->module, "family");
        module::set_dep($this->module, "barangay");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
        module::set_lang("FTITLE_APPOINTMENT_LIST", "english", "APPOINTMENT TYPE LIST", "Y");
        module::set_lang("FTITLE_APPOINTMENT_FORM", "english", "APPOINTMENT FORM", "Y");
        module::set_lang("LBL_APPOINTMENT_ID", "english", "APPOINTMENT ID", "Y");
        module::set_lang("LBL_APPOINTMENT_NAME", "english", "APPOINTMENT NAME", "Y");
        module::set_lang("FTITLE_APPOINTMENT_SCHEDULER", "english", "APPOINTMENT SCHEDULER", "Y");
        module::set_lang("LBL_APPOINTMENT_DATE", "english", "APPOINTMENT DATE", "Y");
        module::set_lang("LBL_APPOINTMENT_CODE", "english", "APPOINTMENT CODE", "Y");
        module::set_lang("LBL_REMINDER_FLAG", "english", "SEND REMINDER?", "Y");
        module::set_lang("FTITLE_APPOINTMENTS_MADE_TODAY", "english", "APPOINTMENTS MADE TODAY", "Y");
        module::set_lang("FTITLE_PREVIOUS_APPOINTMENTS", "english", "PREVIOUS APPOINTMENTS", "Y");
        module::set_lang("LBL_EXPECTED_TO_ARRIVE_TODAY", "english", "THE FOLLOWING PATIENTS ARE EXPECTED TO ARRIVE TODAY", "Y");
        module::set_lang("FTITLE_APPOINTMENTS_TODAY", "english", "APPOINTMENTS TODAY", "Y");
        module::set_lang("LBL_FOLLOW_UP_BEHAVIOR", "english", "FOLLOW UP BEHAVIOR", "Y");
        module::set_lang("LBL_DEFER_CONSULT", "english", "DEFER CONSULT", "Y");
        module::set_lang("INSTR_DEFER_CONSULT", "english", "Check to defer consult", "Y");
        module::set_lang("LBL_PATIENT_DETAILS", "english", "PATIENT DETAILS", "Y");
        module::set_lang("FTITLE_FAMILY_INFO", "english", "FAMILY INFO", "Y");
        module::set_lang("FTITLE_CONSULTS_APPT", "english", "CONSULTS FOR", "Y");

    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::set_menu($this->module, "Appointments", "LIBRARIES", "_appointments");
        module::set_menu($this->module, "Appointments", "CONSULTS", "_consult_schedule");

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

        module::execsql("CREATE TABLE `m_lib_appointment` (".
            "`appointment_id` varchar(10) NOT NULL default '',".
            "`appointment_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`appointment_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("INSERT INTO `m_lib_appointment` VALUES ('VACC', 'Vaccination')");
        module::execsql("INSERT INTO `m_lib_appointment` VALUES ('PRENATAL', 'Prenatal')");
        module::execsql("INSERT INTO `m_lib_appointment` VALUES ('POSTP', 'Postpartum')");
        module::execsql("INSERT INTO `m_lib_appointment` VALUES ('SPT', 'Sputum Exam')");
        module::execsql("INSERT INTO `m_lib_appointment` VALUES ('NTPI', 'NTP Intensive Follow-up')");
        module::execsql("INSERT INTO `m_lib_appointment` VALUES ('NTPM', 'NTP Maintenance Follow-up')");

        module::execsql("CREATE TABLE `m_consult_appointments` (".
            "`schedule_id` float NOT NULL auto_increment,".
            "`visit_date` date NOT NULL default '0000-00-00',".
            "`consult_id` float NOT NULL default '0',".
            "`schedule_timestamp` timestamp(14) NOT NULL,".
            "`user_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`appointment_id` varchar(10) NOT NULL default '',".
            "`visit_done` char(1) NOT NULL default '',".
            "`actual_date` date NOT NULL default '0000-00-00',".
            "`reminder_flag` char(1) NOT NULL default 'Y',".
            "PRIMARY KEY  (`schedule_id`),".
            "UNIQUE KEY `consult_id_2` (`consult_id`,`appointment_id`),".
            "KEY `consult_id` (`consult_id`),".
            "KEY `patient_id` (`patient_id`),".
            "KEY `appointment_id` (`appointment_id`),".
            "CONSTRAINT `m_consult_appointments_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            "CONSTRAINT `m_consult_appointments_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `m_lib_appointment` (`appointment_id`) ON UPDATE CASCADE".
            ") TYPE=InnoDB; ");

        // add foreign key to m_consult_appointments
        module::execsql("set foreign_key_checks=0;");
        module::execsql("alter table `m_consult_appointments` ".
            "add foreign key (`appointment_id`) references `m_lib_appointment` ".
            "(`appointment_id`) on delete restrict on update cascade");
        module::execsql("set foreign_key_checks=1;");

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_consult_appointments`;");
        module::execsql("DROP TABLE `m_lib_appointment`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_schedule() {
    //
    // appointments for today
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        
        mysql_query("ALTER TABLE `m_consult_appointments` DROP PRIMARY KEY, ADD PRIMARY KEY(`schedule_id`)");
        
        if ($post_vars["submitsked"]) {
            appointment::process_appointment_record($menu_id, $post_vars, $get_vars);
        }
        print "<span class='patient'>".FTITLE_APPOINTMENTS_TODAY."</span><br/><br/>";
        $base_url = $_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"];
        if ($get_vars["year"] && $get_vars["month"] && $get_vars["day"]) {
            $date = $get_vars["year"]."-".$get_vars["month"]."-".$get_vars["day"];
        } else {
            $date = date("Y-m-d");
        }
        print "<table><tr valign='top'><td>";
        calendar::display_calendar($menu_id, $post_vars, $get_vars, $validuser, $isadmin, $base_url);
        print "</td><td>";
        print "<span class='tinylight'>";
        print "<ol><b>APPOINTMENT HOWTO:</b></ol>";
        print "<ol>";
        print "<li>THIS PAGE SHOWS THE APPOINTMENTS FOR TODAY.</li>";
        print "<li>TO SEE APPOINTMENTS OTHER THAN TODAY CLICK ON DESIRED CALENDAR DATE.</li>";
        print "<li>YOU CAN ALSO USE THE CALENDAR NAVIGATION BUTTONS TO GO TO A DIFFERENT MONTH OR YEAR.</li>";
        print "<li>CLICK ON PATIENT NAME TO SEE APPOINTMENT RECORD. HIGHLIGHTED NAME MEANS THE PATIENT FOLLOWED UP ALREADY.</li>";
        print "</ol>";
        print "</span>";
        print "</td></tr></table>";
        $sql = "select a.schedule_id, a.patient_id, p.patient_lastname, p.patient_firstname, ".
               "p.patient_dob, p.patient_gender, l.appointment_name, ".
               "round((to_days(now())-to_days(p.patient_dob))/365 , 1) computed_age, actual_date ".
               "from m_patient p, m_consult_appointments a, m_lib_appointment l ".
               "where p.patient_id = a.patient_id and ".
               "a.appointment_id = l.appointment_id and ".
               "to_days(a.visit_date) = to_days('$date') ".
               "order by p.patient_lastname, p.patient_firstname";
        if ($result = mysql_query($sql)) {
            print "<br/><table width=600 bgcolor='#FFFFFF' cellpadding='3' cellspacing='0' style='border: 2px solid black'>";
            print "<tr><td>";
            print "<span class='tinylight'><b>".LBL_EXPECTED_TO_ARRIVE_TODAY." ".$date.":</b></span><br/><br/>";
            if (mysql_num_rows($result)) {
                $i=0;
                while (list($sid, $pid, $plast, $pfirst, $pdob, $pgender, $appname, $p_age, $actual_date) = mysql_fetch_array($result)) {
                    if ($prev_app<>$appname) {
                        $patient_array[$i] .= "<span class='boxtitle'><font color='red'>".strtoupper($appname)."</font></span><br/>";
                    }
                    $patient_array[$i] .= "<a href='".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=$menu_id&patient_id=$pid&schedule_id=$sid&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0#detail' style='".($actual_date<>"0000-00-00"?"background-color: #FFFF00":"")."'><b>$plast, $pfirst</b></a> [$p_age/$pgender] $pdob";
                    if (class_exists("family")) {
                        // show family icon if patient has a family
                        $family_id = family::get_family_id($pid);
                        if ($family_id<>0) {
                            $family_menu_id = module::get_menu_id("_family");
                            $patient_array[$i] .= " <a href='".$_SERVER["PHP_SELF"]."?page=PATIENTS&menu_id=$family_menu_id&family_id=$family_id' title='GO TO FAMILY'><img src='../images/family.gif' border='0'/></a>";
                        }
                    }
                    if (class_exists("healthcenter")) {
                        $consult_menu_id = module::get_menu_id("_consult");
                        // show link only if today
                        if ($date == date("Y-m-d")) {
                            $patient_array[$i] .= " <a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$consult_menu_id&enter_consult=$pid&appt_date=$date&sked_id=$sid' title='LOAD PATIENT RECORD'><img src='../images/records.gif' border='0'/></a>";
                        }
                    }
                    $i++;
                    $prev_app = $appname;
                }
                print $this->columnize_list($patient_array);
            } else {
                print "<font color='red'>No patients scheduled today.</font><br/>";
            }
            print "</td></tr>";
            print "</table><br/>";
            if ($get_vars["schedule_id"]) {
                appointment::display_appointment_details($menu_id, $post_vars, $get_vars);
            }
            // consult patient list based on appointment date
            appointment::consult_info($menu_id, $post_vars, $get_vars);
            // consult patient list today
            healthcenter::consult_info($menu_id, $post_vars, $get_vars);
        }
    }

    function consult_info() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["month"] && $get_vars["day"] && $get_vars["year"]) {
            $date = $get_vars["year"]."-".$get_vars["month"]."-".$get_vars["day"];
            $sql = "select c.consult_id, p.patient_id, p.patient_lastname, p.patient_firstname, see_doctor_flag ".
                   "from m_consult c, m_patient p where c.patient_id = p.patient_id ".
                   "and to_days(consult_date) = to_days('$date') order by c.consult_date asc";
            if ($result = mysql_query($sql)) {
                print "<span class='patient'>".FTITLE_CONSULTS_APPT." $date</span><br>";
                print "<table width=600 bgcolor='#FFFFFF' cellpadding='3' cellspacing='0' style='border: 2px solid black'>";
                print "<tr><td>";
                print "<span class='tinylight'>HIGHLIGHTED NAMES OR THOSE MARKED WITH <img src='../images/star.gif' border='0'/> WILL SEE PHYSICIAN.</span><br/>";
                if (mysql_num_rows($result)) {
                    // initialize array index
                    $i=0;
                    // have to return always to consult page
                    // wherever this is shown
                    $consult_menu_id = module::get_menu_id("_consult");
                    while (list($cid, $pid, $plast, $pfirst, $see_doctor) = mysql_fetch_array($result)) {
                        $visits = healthcenter::get_total_visits($pid);
                        $consult_array[$i] = "<a href='".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=$consult_menu_id&consult_id=$cid&ptmenu=DETAILS' title='".INSTR_CLICK_TO_VIEW_RECORD."' ".($see_doctor=="Y"?"style='background-color: #FFFF33'":"").">".
                                             "<b>$plast, $pfirst</b></a> [$visits] ".($see_doctor=="Y"?"<img src='../images/star.gif' border='0'/>":"");
                        $i++;
                    }
                    // pass on patient list to be columnized
                    print $this->columnize_list($consult_array);
                } else {
                    print "<font color='red'>No consults available.</font>";
                }
                $sql_time = "select round(avg(unix_timestamp(consult_end)-unix_timestamp(consult_date))/60,2) consult_minutes from m_consult where consult_end>0 and to_days(consult_date) = to_days('$date');";
                if ($result_time = mysql_query($sql_time)) {
                    if (mysql_num_rows($result_time)) {
                        list($mean_consult_time) = mysql_fetch_array($result_time);
                    }
                    if ($mean_consult_time) {
                        print "<tr><td class='tinylight'>";
                        if ($mean_consult_time>60) {
                            $unit_time = "hour(s)";
                        } elseif ($meantime>1440) {
                            $unit_time = "day(s)";
                        } else {
                            $unit_time = "minute(s)";
                        }
                        print "AVERAGE CONSULT TIME: <font color='red'>$mean_consult_time $unit_time</font><br/>";
                        print "</td></tr>";
                    }
                }
                print "</td></tr>";
                print "</table>";
            }
        }
    }

    function process_appointment_record() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }		

        if ($post_vars["submitsked"]) {
            switch($post_vars["submitsked"]) {
            case "Arrived":
                // process arrival date and enter patient in consult;
                // always set to current date
                if ($post_vars["defer_consult"]) {
                    if ($post_vars["schedule_id"]) {
                        $sql_appt = "update m_consult_appointments set ".
                                    "actual_date = sysdate(), ".
                                    "visit_done = 'Y' ".
                                    "where schedule_id = '".$post_vars["schedule_id"]."'";
                        $result_appt = mysql_query($sql_appt);
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&schedule_id=".$get_vars["schedule_id"]."&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0#detail");
                    }
                } else {
                    healthcenter::process_consult($menu_id, $post_vars, $get_vars);
                }
                break;
            case "Delete":
                if (module::confirm_delete($menu_id, $post_vars,$get_vars)) {
                    $sql = "delete from m_consult_appointments where schedule_id = '".$post_vars["schedule_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0");
                    }
                } else {
                    if ($post_vars["confirm_delete"]=="No") {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0");
                    }
                }
                // delete appointment
                break;
            }
        }
    }

    function display_appointment_details() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $sql = "select schedule_id, visit_date, consult_id, date_format(schedule_timestamp, '%a %d %b %Y, %h:%i%p') schedule_timestamp, user_id, ".
               "patient_id, appointment_id, reminder_flag, actual_date, to_days(visit_date)- to_days(actual_date) variance ".
               "from m_consult_appointments where schedule_id = '".$get_vars["schedule_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $appt = mysql_fetch_array($result);
                print "<a name='detail'>";
                print "<table width='600' cellpadding='1'><tr valign='top'><td>";
                // column 1
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&patient_id=".$get_vars["patient_id"]."&schedule_id=".$appt["schedule_id"]."&year=".$get_vars["year"]."&month=".$get_vars["month"]."&day=".$get_vars["day"]."&s=0'>";
                print "<table width='300' bgcolor='#FFFFCC' cellpadding='3'><tr><td>";
                print "<span class='PATIENT'>".strtoupper(patient::get_name($appt["patient_id"]))."</span><br/><br/>";
                print "VISIT DATE: <font color='red'><b>".$appt["visit_date"]."</b></font><br/>";
                print "ACTUAL DATE: <font color='red'><b>".($appt["actual_date"]=="0000-00-00"?"No visit yet":$appt["actual_date"])."</b></font><br/>";
                if ($appt["actual_date"]<>"0000-00-00") {
                    print "VARIANCE, DAYS: ".($appt["variance"]<0?"<font color='red'><b>".$appt["variance"]."</b></font>":$appt["variance"])."<br/>";
                }
                print "REMINDER: ".$appt["reminder_flag"]."<br/>";
                print "APPT SET BY: ".user::get_username($appt["user_id"])."<br/><br/>";
                print "<span class='boxtitle'>APPT CODE</span><br/> ".appointment::get_appointment_name($appt["appointment_id"])."<br/><br/>";
                /*
                print "<span class='boxtitle'>ACTUAL FOLLOWUP DATE</span><br/>";
                if ($injury["injury_date"]) {
                    list($year, $month, $day) = explode("-", $injury["injury_date"]);
                    $injury_date = "$month/$day/$year";
                }
                print "<input type='text' size='15' maxlength='10' class='textbox' name='injury_date' value='".($injury_date?$injury_date:$post_vars["injury_date"])."' style='border: 1px solid #000000'> ";
                print "<a href=\"javascript:show_calendar4('document.form_consult_injury.injury_date', document.form_consult_injury.injury_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
                print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
                print "<br/>";
                */
                if ($appt["actual_date"]=="0000-00-00") {
                    print "<span class='boxtitle'>".LBL_DEFER_CONSULT."?</span><br/>";
                    print "<input type='checkbox' name='defer_consult' checked value='1'/> ".INSTR_DEFER_CONSULT."<br/><br/>";
                }
                if ($_SESSION["priv_add"]) {
                    if ($appt["actual_date"]=="0000-00-00") {
                        print "<input type='submit' class='textbox' name='submitsked' value='Arrived' style='border: 1px solid black'/> ";
                    }
                }
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' class='textbox' name='submitsked' value='Delete' style='border: 1px solid black'/> ";
                }
                print "<input type='hidden' name='schedule_id' value='".$get_vars["schedule_id"]."'/>";
                print "<input type='hidden' name='consult_patient_id' value='".$appt["patient_id"]."'/>";
                print "</td></tr></table></form>";

                print "</td><td width='50%'>";
                // column 2
                // followup history
                // compute only for those who have followed up
                print "<b>".LBL_FOLLOW_UP_BEHAVIOR."</b><br/>";
                $sql_hx = "select case when (to_days(visit_date)-to_days(actual_date)) >= 0 and actual_date <> '0000-00-00' then 'on time' ".
                          "when (to_days(visit_date)-to_days(actual_date)) < 0 and actual_date <> '0000-00-00' then 'not on time' ".
                          "when actual_date = '0000-00-00' then 'no follow up' ".
                          "end status, count(schedule_id) times, round(avg(to_days(visit_date)-to_days(actual_date)),2) variance ".
                          "from m_consult_appointments ".
                          "where patient_id = ".$appt["patient_id"]." and ".
                          "actual_date <> '0000-00-00' group by status";
                if ($result_hx = mysql_query($sql_hx)) {
                    if (mysql_num_rows($result_hx)) {
                        print "<table width='300'>";
                        print "<tr valign='top'><td class='tinylight'><b>STATUS</b></td><td class='tinylight'><b>FREQUENCY</b></td><td class='tinylight'><b>AVE VARIANCE</b></td></tr>";
                        while (list($status, $times, $variance) = mysql_fetch_array($result_hx)) {
                            print "<tr valign='top'><td class='tinylight'>$status</td><td class='tinylight'>$times</td><td class='tinylight'>$variance</td></tr>";
                        }
                        print "</table><br/>";
                    } else {
                        print "<font color='red'>No records to process</font><br/><br/>";
                    }
                }
                print "<b>".LBL_PATIENT_DETAILS."</b><br/><br/>";
                print "<span class='tinylight'>";
                print "<b>".FTITLE_FAMILY_INFO."</b><br/>";
                $family_id = family::get_family_id($appt["patient_id"]);
                if ($family_id) {
                print "FAMILY ID: $family_id<br/>";
                print "ADDRESS: ".family::get_family_address($family_id)."<br/>";
                print "BARANGAY: ".family::barangay_name($family_id)."<br/>";
                print "MEMBERS:<br/>".family::get_family_members($family_id)."<br/>";
                } else {
                    print "<font color='red'>No family record.</font><br/><br/>";
                }
                print "<b>".FTITLE_PATIENT_GROUP_HX."</b><br/>";

                $sql_ptgroup = "select count(c.ptgroup_id), g.ptgroup_name, g.ptgroup_module, c.ptgroup_id, c.consult_id ".
                               "from m_consult_ptgroup c, m_lib_ptgroup g, m_consult h ".
                               "where g.ptgroup_id = c.ptgroup_id and ".
                               "h.consult_id = c.consult_id and ".
                               "h.patient_id = '".$appt["patient_id"]."' ".
                               "group by c.ptgroup_id";
                if ($result = mysql_query($sql_ptgroup)) {
                    if (mysql_num_rows($result)) {
                        while (list($count, $name, $mod, $grp, $cid) = mysql_fetch_array($result)) {
                            print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                            print "$name: $count ".($count>1?" visits":"visit")."<br/> ";
                        }
                    } else {
                        print "<font color='red'>No records.</font><br/>";
                    }
                }
                print "</span>";
                print "</td></tr></table><br/>";
            }
        }
    }

    function _consult_appointment() {
    //
    // consult appointment form
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $a = new appointment;
        if ($post_vars["submitsked"]) {
            $a->process_consult_appointment($menu_id, $post_vars, $get_vars);
            //header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
        }
        $a->form_consult_appointment($menu_id, $post_vars, $get_vars);
    }

    function display_consult_appointments() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitsked"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_appointments where schedule_id = '".$get_vars["schedule_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]);
                }
            }
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        print "<b>".FTITLE_APPOINTMENTS_MADE_TODAY."</b><br/>";
        $sql = "select schedule_id, date_format(visit_date, '%a %d %b %Y') visit_date, appointment_id ".
               "from m_consult_appointments where consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($appt = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=APPTS&schedule_id=".$appt["schedule_id"]."'>".$appt["visit_date"]."</a> ";
                    print appointment::get_appointment_name($appt["appointment_id"])."<br/>";
                    if ($get_vars["schedule_id"]==$appt["schedule_id"]) {
                        appointment::show_details($menu_id, $post_vars, $get_vars);
                    }
                }
            } else {
                print "<font color='red'>No records</font><br/>";
            }
        }
        print "<br/>";
        print "<b>".FTITLE_PREVIOUS_APPOINTMENTS."</b><br/>";
        $sql = "select schedule_id, date_format(visit_date, '%a %d %b %Y') visit_date, appointment_id ".
               "from m_consult_appointments where patient_id = '$patient_id' and ".
               "to_days(visit_date) < to_days(sysdate()) order by visit_date desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($appt = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=APPTS&schedule_id=".$appt["schedule_id"]."'>".$appt["visit_date"]."</a> ";
                    print appointment::get_appointment_name($appt["appointment_id"])."<br/>";
                    if ($get_vars["schedule_id"]==$appt["schedule_id"]) {
                        appointment::show_details($menu_id, $post_vars, $get_vars);
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
            //print_r($arg_list);
        }
        $sql = "select schedule_id, visit_date, consult_id, date_format(schedule_timestamp, '%a %d %b %Y, %h:%i%p') schedule_timestamp, user_id, ".
               "patient_id, appointment_id, reminder_flag ".
               "from m_consult_appointments where schedule_id = '".$get_vars["schedule_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $appt = mysql_fetch_array($result);
                print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=APPTS&schedule_id=".$appt["schedule_id"]."'>";
                print "<table width='200' cellpadding='1' style='border: 1px dotted black'><tr><td>";
                print "<span class='tinylight'>";
                print "VISIT DATE: ".$appt["visit_date"]."<br/>";
                print "APPT SET ON: ".$appt["visit_date"]."<br/>";
                print "APPT SET BY: ".user::get_username($appt["user_id"])."<br/>";
                print "APPT CODE: ".appointment::get_appointment_name($appt["appointment_id"])."<br/>";
                print "REMINDER: ".$appt["reminder_flag"]."<br/>";
                print "<br/><input type='submit' class='tinylight' name='submitsked' value='Delete' style='border: 1px solid black'/>";
                print "</span>";
                print "</td></tr></table></form>";
            }
        }
    }

    function process_consult_appointment() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }			

        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $reminder_flag = ($post_vars["reminder_flag"]?"Y":"N");
        
		//print_r($post_vars);
		
		if($post_vars["valid"]==1):


			switch($post_vars["action_button"]){

				case "Save Schedule":

					if ($post_vars["appointment_date"] && $post_vars["appointment"]):

						list($month,$day,$year) = explode("/", $post_vars["appointment_date"]);
		                $appt_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
				        
						foreach($post_vars["appointment"] as $key=>$value) {
						    $sql = "insert into m_consult_appointments (visit_date, consult_id, ".
                           "schedule_timestamp, user_id, patient_id, appointment_id, reminder_flag) ".
                           "values ('$appt_date', '".$get_vars["consult_id"]."', sysdate(), '".$_SESSION["userid"]."', ".
                           "'$patient_id', '$value', '$reminder_flag')";
		                    $result = mysql_query($sql);
				        }
						
						$this->save_cellphone_number($patient_id,$post_vars["patient_cp"]);
						
						header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=APPTS");

					else:
						print "<font class='error'>Cannnot add schedule. Either date or appointment code is missing.</font>";
					endif;

				case "Update Schedule":

				case "Delete Schedule":

				default:

				break;
			}



		endif;
		
    }

    function form_consult_appointment() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["cid"] && $get_vars["injury_ts"]) {
                $sql = "select injury_id, mechanism_code, location_code, injury_date, injury_time, location_detail ".
                       "from m_consult_injury ".
                       "where consult_id = '".$get_vars["cid"]."' and injury_timestamp = '".$get_vars["injury_ts"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $injury = mysql_fetch_array($result);
                    }
                }
            }
        }

		
		$pxid = healthcenter::get_patient_id($get_vars[consult_id]);
		
		$cp = $this->get_patient_cellphone($pxid);
		
		$this->process_consult_appointment($menu_id,$post_vars,$get_vars,$validuser,$isadmin);

		$consult_date = healthcenter::get_consult_date($get_vars[consult_id]);
		$cons_date = $this->parse_date($consult_date);			
		
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."' name='form_consult_appointment' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_APPOINTMENT_SCHEDULER."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPOINTMENT_DATE."</span><br> ";
        if ($appointment["visit_date"]) {
            list($year, $month, $day) = explode("-", $appointment["visit_date"]);
            $appointment_date = "$month/$day/$year";
        }
        print "<input type='text' size='15' maxlength='10' class='textbox' name='appointment_date' value='".($appointment_date?$appointment_date:$post_vars["appointment_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_consult_appointment.appointment_date', document.form_consult_appointment.appointment_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPOINTMENT_CODE."</span><br> ";
        print appointment::checkbox_appointment($injury["injury_id"]?$injury["injury_id"]:$post_vars["injury_id"]);
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_REMINDER_FLAG."</span><br> ";
        print "<input type='checkbox' name='reminder_flag' value='1'/> Check to send reminder<br/>";
        print "<br/></td></tr>";
		
		print "<tr>";
		print "<td><span class='boxtitle'>PATIENT CELLPHONE NUMBER</span>";
	
		print "<input type='textbox' name='patient_cp' size='10' maxlength='11' value='$cp'></input>";

		print "<input type='hidden' name='valid' value='0'></input>";
		print "<input type='hidden' name='action_button' value=''>";
		print "<input type='hidden' name='hidden_cp' value='$cp'>";
		
		if(empty($cp)):
			print "<br><b><font color='red' size='2'>(patient no cellphone, please indicate)</font></b>";
		endif;
		
		print "</td>";
		print "</tr>";		
		
        print "<tr valign='top'><td>";
        print "<tr><td>";

        if ($get_vars["cid"] && $get_vars["injury_ts"]) {
            print "<input type='hidden' name='cid' value='".$get_vars["cid"]."'>";
            print "<input type='hidden' name='injury_ts' value='".$get_vars["injury_ts"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='button' value = 'Update Schedule' class='textbox' name='submitsked' style='border: 1px solid #000000' onclick='check_appt_info()'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Schedule' class='textbox' name='submitsked' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='button' value = 'Save Schedule' class='textbox' name='submitsked' style='border: 1px solid #000000' onclick='check_appt_info()'><br> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    // ----------------- LIBRARY METHODS --------------------

    function _appointments() {
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
        if ($exitinfo = $this->missing_dependencies('appointment')) {
            return print($exitinfo);
        }

        if ($post_vars["submitappt"]) {
            $this->process_appointment($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        $this->display_appointment($menu_id);
        $this->form_appointment($menu_id, $post_vars, $get_vars);
    }

    function checkbox_appointment() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $appointment_id = $arg_list[0];
        }
        $sql = "select appointment_id, appointment_name from m_lib_appointment order by appointment_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='appointment[]' value='$id'> $name<br>";
                }
            } else {
                print "<font color='red'>No appointment in library</font><br/>";
            }
            return $ret_val;
        }
    }

    function show_appointment() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $appointment_id = $arg_list[0];
        }
        $sql = "select appointment_id, appointment_name from m_lib_appointment order by appointment_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='appointment' class='textbox'>";
                $ret_val .= "<option value=''>Select Appt Code</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($appointment_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
            } else {
                print "<font color='red'>No appointment in library</font><br/>";
            }
            return $ret_val;
        }
    }

    function get_appointment_name() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $appointment_id = $arg_list[0];
        }
        $sql = "select appointment_name from m_lib_appointment where appointment_id = '$appointment_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function display_appointment() {
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
        print "<span class='library'>".FTITLE_APPOINTMENT_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select appointment_id, appointment_name from m_lib_appointment order by appointment_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $popn) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&appointment_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function process_appointment() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitappt"]) {
            if ($post_vars["appointment_id"] && $post_vars["appointment_name"]) {
                switch($post_vars["submitappt"]) {
                case "Add Appointment":
                    $sql = "insert into m_lib_appointment (appointment_id, appointment_name) ".
                           "values ('".$post_vars["appointment_id"]."', '".$post_vars["appointment_name"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id".$get_vars["menu_id"]);
                    }
                    break;
                case "Update Appointment":
                    $sql = "update m_lib_appointment set ".
                           "appointment_name = '".$post_vars["appointment_name"]."' ".
                           "where appointment_id = '".$post_vars["appointment_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id".$get_vars["menu_id"]);
                    }
                    break;
                case "Delete Appointment":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_appointment where appointment_id = '".$post_vars["appointment_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id".$get_vars["menu_id"]);
                        }
                    } else {
                        if ($post_vars["confirm_delete"]=="No") {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id".$get_vars["menu_id"]);
                        }
                    }
                    break;
                }
            }
        }
    }

    function form_appointment() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["appointment_id"]) {
                $sql = "select appointment_id, appointment_name ".
                       "from m_lib_appointment where appointment_id = '".$get_vars["appointment_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $appointment = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_appointment' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_APPOINTMENT_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPOINTMENT_ID."</span><br> ";
        if ($appointment["appointment_id"]) {
            $disable = "disabled";
            print "<input type='hidden' name='appointment_id' value='".$appointment["appointment_id"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='5' $disable maxlength='5' name='appointment_id' value='".($appointment["appointment_id"]?$appointment["appointment_id"]:$post_vars["appointment_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_APPOINTMENT_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='appointment_name' value='".($appointment["appointment_name"]?$appointment["appointment_name"]:$post_vars["appointment_name"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["appointment_id"]) {
            print "<input type='hidden' name='appointment_id' value='".$get_vars["appointment_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Appointment' class='textbox' name='submitappt' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Appointment' class='textbox' name='submitappt' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Appointment' class='textbox' name='submitappt' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

	function get_patient_cellphone(){
	
		if(func_num_args()):
			$arg_list = func_get_args();
			$pxid = $arg_list[0];
		endif;
		
		$q_cp = mysql_query("SELECT patient_cellphone FROM m_patient WHERE patient_id='$pxid'") or die(mysql_error());
		$r_cp = mysql_fetch_array($q_cp);
		
		return $r_cp[patient_cellphone];
	}

	function parse_date(){
		if(func_num_args()>0){
			$arg_list = func_get_args();
			$date_consult = $arg_list[0];
		}
		
		list($cons_date,$cons_time) = explode(' ',$date_consult);
		list($yr,$month,$date) = explode('-',$cons_date);

		return $month.'/'.$date.'/'.$yr;

	
	}

	function save_cellphone_number(){
		if(func_num_args() > 0):
			$arg_list = func_get_args();
			$pxid = $arg_list[0];
			$pxcp = $arg_list[1];
		endif;		
		
		$q_patient = mysql_query("UPDATE m_patient SET patient_cellphone='$pxcp' WHERE patient_id='$pxid'") or die("Cannot query: 954");
	}

}
?>
