<?
class healthcenter extends module{

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function healthcenter() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','module')";
        $result = mysql_query($sql);

        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','ptgroup')";
        $result = mysql_query($sql);

        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','patient')";
        $result = mysql_query($sql);

        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','barangay')";
        $result = mysql_query($sql);

        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','family')";
        $result = mysql_query($sql);

        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','diagnosis')";
        $result = mysql_query($sql);

        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('healthcenter','vaccine')";
        $result = mysql_query($sql);

    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    // 1. refer to table term
    // 2. skip remarks and translationof since this term is manually entered
    //

        $sql = "insert into terms (termid, languageid, langtext, isenglish) ".
               "values ('LBL_PTGROUP','english', 'PATIENT GROUP', 'Y');";
        $result = mysql_query($sql);

        $sql = "insert into terms (termid, languageid, langtext, isenglish) ".
               "values ('LBL_COMPLAINTCAT','english', 'COMPLAINT CATEGORY', 'Y');";
        $result = mysql_query($sql);
    }

    function init_help() {
    }

    function init_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Today\'s Patients', 'CONSULTS', '_consult')";
        $result = mysql_query($sql);

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Vital Signs', 'CONSULTS', '_vitals_signs')";
        $result = mysql_query($sql);

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Diagnosis', 'CONSULTS', '_diagnosis')";
        $result = mysql_query($sql);

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Treatment', 'CONSULTS', '_treatment')";
        $result = mysql_query($sql);

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Health Centers', 'LIBRARIES', '_healthcenter')";
        $result = mysql_query($sql);

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Complaint', 'LIBRARIES', '_complaint')";
        $result = mysql_query($sql);

        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('healthcenter', 'Modules', 'CONSULTS', '_modules')";
        $result = mysql_query($sql);

        // put in more details
        $sql = "update modules set module_desc = 'CHITS Library - Health Center', ".
               "module_version = '".$this->version."', module_author = '".$this->author."' ".
               "where module_id = 'healthcenter';";
        $result = mysql_query($sql);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        $sql = "CREATE TABLE `m_lib_healthcenter` (".
               "`healthcenter_id` varchar(25) NOT NULL default '',".
               "`healthcenter_name` varchar(100) NOT NULL default '',".
               "`area_code` varchar(10) NOT NULL default '',".
               "PRIMARY KEY  (`healthcenter_id`)".
               ") TYPE=InnoDB; ";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_consult` (".
               "`consult_id` float NOT NULL auto_increment,".
               "`ptgroup_id` varchar(10) NOT NULL default '0',".
               "`patient_id` int(11) NOT NULL default '0',".
               "`healthcenter_id` varchar(25) NOT NULL default '0',".
               "`consult_timestamp` timestamp(14) NOT NULL,".
               "`consult_date` date NOT NULL default '0000-00-00',".
               "`consult_time` char(1) NOT NULL default '',".
               "`consult_complaint` varchar(255) NOT NULL default '',".
               "`consult_complaint_cat` varchar(10) NOT NULL default '',".
               "`consult_first_visit` char(1) NOT NULL default '',".
               "`consult_followup_visit` char(1) NOT NULL default '',".
               "`diagnosis_code` int(11) NOT NULL default '0',".
               "`consult_treatment` text NOT NULL,".
               "PRIMARY KEY  (`consult_id`)".
               ") TYPE=InnoDB; ";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_consult_vitals` (".
               "`consult_id` float NOT NULL default '0',".
               "`vitals_timestamp` timestamp(14) NOT NULL,".
               "`patient_id` float NOT NULL default '0',".
               "`vitals_weight` float NOT NULL default '0',".
               "`vitals_temp` float NOT NULL default '0',".
               "`vitals_systolic` int(11) NOT NULL default '0',".
               "`vitals_diastolic` int(11) NOT NULL default '0',".
               "`vitals_heartrate` int(11) NOT NULL default '0',".
               "`vitals_resprate` int(11) NOT NULL default '0',".
               "PRIMARY KEY  (`consult_id`,`vitals_timestamp`),".
               "KEY `fk_patient_id` (`patient_id`)".
               ") TYPE=InnoDB; ";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_consult_diagnosis` (".
               "`consult_id` float NOT NULL default '0',".
               "`diagnosis_code` varchar(10) NOT NULL default '',".
               "PRIMARY KEY  (`diagnosis_code`,`consult_id`)".
               ") TYPE=InnoDB; ";
        $result = mysql_query($sql);

        // this table relates complaint to loadable modules:
        // imci_cough, imci_diarrhea, imci_fever
        $sql = "CREATE TABLE `m_lib_complaint` (".
               "`complaint_id` varchar(10) NOT NULL default '',".
               "`complaint_module` varchar(25) NOT NULL default '',".
               "`complaint_name` varchar(100) NOT NULL default '',".
               "PRIMARY KEY  (`complaint_id`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);

        $sql = "CREATE TABLE `m_healthcenter_modules` (".
               "`module_id` varchar(25) NOT NULL default '',".
               "PRIMARY KEY  (`module_id`)".
               ") TYPE=InnoDB;";
        $result = mysql_query($sql);

        // load initial data
        $sql = "insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('COUGH', 'Cough', 'imci_cough')";
        $result = mysql_query($sql);

        $sql = "insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('DIARHEA', 'Diarrhea', 'imci_diarrhea')";
        $result = mysql_query($sql);

        $sql = "insert into m_lib_complaint (complaint_id, complaint_name, complaint_module) values ('FEVER', 'Fever', 'imci_fever')";
        $result = mysql_query($sql);

    }

    function drop_tables() {
        $sql = "DROP TABLE `m_lib_healthcenter`;";
        $result = mysql_query($sql);

        $sql = "DROP TABLE `m_consult`;";
        $result = mysql_query($sql);

        $sql = "DROP TABLE `m_consult_vitals`;";
        $result = mysql_query($sql);

        $sql = "DROP TABLE `m_consult_diagnosis`;";
        $result = mysql_query($sql);

        $sql = "DROP TABLE `m_lib_complaint`;";
        $result = mysql_query($sql);

        $sql = "DROP TABLE `m_healthcenter_modules`;";
        $result = mysql_query($sql);
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _healthcenter() {
    }

    function _consult() {
        static $patient;
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($patient)) {
            $patient = new patient;
        }
        // always check dependencies
        //if ($exitinfo = $this->missing_dependencies('healthcenter')) {
        //    return print($exitinfo);
        //}
        if ($get_vars["patient_id"] && $get_vars["consult_id"]) {
            print "<table>";
            print "<tr valign='top'><td>";
            $this->patient_info($menu_id, $post_vars, $get_vars);
            print "</td></tr>";
            print "</table>";
        } else {
            if ($post_vars["submitpatient"]) {
                // processes form_patient
                $patient->process_patient($menu_id, $post_vars, $get_vars);
                $this->process_consult($menu_id, $post_vars, $get_vars);
                //header("location: ".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=$menu_id");
            }
            if ($post_vars["submitconsult"]) {
                // confirms consult for found patients
                $this->process_consult($menu_id, $post_vars, $get_vars);
            }
            if ($post_vars["submitsearch"]) {
                // lists down search results for patient
                $patient->process_search($menu_id, $post_vars, $get_vars);
            }
            print "<table><tr valign='top'><td colspan='2'>";
            // display all patients confirmed with consults
            $this->consult_info($menu_id, $post_vars, $get_vars);
            print "</td></tr>";
            print "<tr valign='top'><td>";
            $patient->newsearch($menu_id, $post_vars, $get_vars);
            print "</td><td>";
            $patient->form_patient($menu_id, $post_vars, $get_vars);
            print "</td></tr>";
            print "</table>";
        }
    }

    function patient_info() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }

        $sql = "select patient_lastname, patient_firstname from m_patient where patient_id = '".$get_vars["patient_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ptinfo = mysql_fetch_array($result);
            }
        }
        print "<span class='patient'>CONSULT NOTES</span><br>";
        print "<table width='600' cellpadding='2' style='border: 2px solid black'>";
        print "<form method='post' action='".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=$menu_id'>";
        print "<tr><td colspan='2'>";
        print "<input type='submit' class='tiny' name='exitpatient' value='EXIT' style='border: 1px solid black; background-color: #FFCC00; padding: 0.5px;'>";
        print "</td></tr>";
        print "<tr><td colspan='2'>";
        print "<span class='library'>".strtoupper($ptinfo["patient_lastname"].", ".$ptinfo["patient_firstname"])."</span>";
        print "</td></tr>";
        print "</form>";
        print "<tr><td>";
        $this->form_visitdetails($menu_id, $post_vars, $get_vars);
        print "</td><td>";
        print "others";
        print "</td></tr>";
        print "</table>";
    }

    function form_visitdetails() {
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
        print "<form action = '".$_SERVER["SELF"]."?page=CONSULTS&menu_id=$menu_id' name='form_patient' method='post'>";
        print "<tr valign='top'><td>";
        print $this->consult_time($get_vars);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PTGROUP."</span><br> ";
        print ptgroup::show_ptgroup();
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_COMPLAINTCAT."</span><br> ";
        print $this->show_complaintcat();
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAST_NAME."</span><br> ";
        print "<input type='text' class='textbox' ".($get_vars["patient_id"]?'disabled':'')." name='patient_lastname' value='".($patient["patient_lastname"]?$patient["patient_lastname"]:$post_vars["patient_lastname"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PATIENT_AGE."</span><br> ";
        print "<input type='text' size='5' maxlength='5' class='textbox' ".($get_vars["patient_age"]?'disabled':'')." name='patient_age' value='".($patient["patient_age"]?$patient["patient_age"]:$post_vars["patient_age"])."' style='border: 1px solid #000000'><br>";
        print "<small>Type age using the following examples 12 hours=12H, 12 days=12D, 12 months=12M, 12 years=12Y.</small>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PATIENT_DOB."</span><br> ";
        print "<input type='text' size='10' maxlength='10' class='textbox' ".($get_vars["patient_dob"]?'disabled':'')." name='patient_dob' value='".($patient["patient_dob"]?$patient["patient_dob"]:$post_vars["patient_dob"])."' style='border: 1px solid #000000'><br>";
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
        print "<br><input type='submit' value = 'Add Patient' class='textbox' name='submitpatient' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function consult_time() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $get_vars = $arg_list[0];
        }
        $sql = "select date_format(c.consult_timestamp, '%b %d %Y, %h:%i %p'), ".
               "(unix_timestamp()-unix_timestamp(c.consult_timestamp))/3600 elapsed from m_consult c where c.patient_id = '".$get_vars["patient_id"]."' ".
               "and c.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date, $elapsed) = mysql_fetch_array($result);
                return "$date $elapsed"."H elapsed<br>";
            }
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
        $sql = "select c.consult_id, p.patient_id, p.patient_lastname, p.patient_firstname ".
               "from m_consult c, m_patient p where c.patient_id = p.patient_id order by c.consult_timestamp desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table width=500 bgcolor='#FFFFFF' cellspacing='0' style='border: 2px solid black'>";
                print "<tr><td>";
                print "<span class='patient'>CONSULTS TODAY</span><br>";
                $i=0;
                while (list($cid, $pid, $plast, $pfirst) = mysql_fetch_array($result)) {
                    $consult_array[$i] = "<a href='".$_SERVER["PHP_SELF"]."?page=CONSULTS&menu_id=$menu_id&patient_id=$pid&consult_id=$cid'><b>$plast, $pfirst</b></a> [$cid]";
                    $i++;
                }
                print $this->columnize_list($consult_array);
                print "</td></tr>";
                print "</table>";
            }
        }
    }

    function columnize_list() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $list = $arg_list[0];
        }
        //print_r($list);
        $items_per_column = (count($list)/2);
        $retval .= "<table width='100%' cellpadding='2' cellspacing='1'>";
        $retval .= "<tr bgcolor='white' valign='top'>";
        for ($i=0; $i<count($list);$i++) {
            if ($i<($items_per_column)) {
                $col1 .= $list[$i]."<br>";
            } else {
                $col2 .= $list[$i]."<br>";
            }
        }
        $retval .= "<td>$col1</td><td>$col2</td>";
        $retval .= "</tr></table>";
        return $retval;
    }

    function process_consult() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "insert into m_consult (ptgroup_id, patient_id, healthcenter_id, consult_date, consult_time) ".
               "values (0, '".$post_vars["consult_id"]."', '', '".date("Y-m-d")."', '')";
        $result = mysql_query($sql);
    }

    function show_complaintcat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select complaint_id, complaint_name from m_lib_complaint order by complaint_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<select name='complaint' class='textbox'>";
                while(list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id'>$name</option>";
                }
                print "</select>";
            }
        }
    }

}
?>
