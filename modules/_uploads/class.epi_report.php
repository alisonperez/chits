<?
class epi_report extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function epi_report() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "epi_report";
        $this->description = "CHITS Module - EPI Weekly Report";

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "fpdf");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "vaccine");
        module::set_dep($this->module, "family");
        module::set_dep($this->module, "graph");
        //module::set_dep($this->module, "ccdev");
		
    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_EPI_REPORTS", "english", "EPI REPORTS", "Y");
        module::set_lang("MENU_TCL", "english", "TARGET CLIENT LIST", "Y");
        module::set_lang("MENU_SUMMARY", "english", "SUMMARY REPORTS", "Y");
        module::set_lang("MENU_GRAPHS", "english", "GRAPHS", "Y");
        module::set_lang("FTITLE_INCLUSIVE_DATES_FORM", "english", "INCLUSIVE DATES FOR THIS REPORT", "Y");
        module::set_lang("LBL_START_DATE", "english", "START DATE", "Y");
        module::set_lang("LBL_END_DATE", "english", "END DATE", "Y");
        module::set_lang("LBL_REPORT_WEEK", "english", "REPORT WEEK", "Y");

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
        module::set_menu($this->module, "EPI Reports", "REPORTS", "_epi_report");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_consult_epi_report` (".
            "`vaccine_id` varchar(10) NOT NULL default '',".
            //"`actual_vaccine_date` date NOT NULL default '0000-00-00',".
            "`vaccine_total_child` int(11) NOT NULL default '0',".
	    "`vaccine_total_pregnant` int(11) NOT NULL default '0',".
	    "`vaccine_total_non_pregnant` int(11) NOT NULL default '0',".
	    "`start_date` date NOT NULL default '0000-00-00',".
	    "`end_date` date NOT NULL default '0000-00-00',".
	    //"`source_module` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`vaccine_id`)".
            ") TYPE=MyISAM; ");

        module::execsql("CREATE TABLE `m_patient_epi_tcl` (".
            //"`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`patient_dob` date NOT NULL default '0000-00-00',". 
	    "`family_id` float NOT NULL default '0',".
            "`patient_name` varchar(50) NOT NULL default '',".
            "`patient_mother` varchar(50) NOT NULL default '',".
            "`patient_address` varchar(100) NOT NULL default '',".
            "`barangay_name` varchar(100) NOT NULL default '',".
            "`month_reaches_age1` varchar(15) NOT NULL default '',".
	    "`fully_immunized_date` date NOT NULL default '0000-00-00',".
	    "`BCG` date NOT NULL default '0000-00-00',".
            "`DPT1` date NOT NULL default '0000-00-00',".
            "`DPT2` date NOT NULL default '0000-00-00',".
            "`DPT3` date NOT NULL default '0000-00-00',".
            "`OPV1` date NOT NULL default '0000-00-00',".
            "`OPV2` date NOT NULL default '0000-00-00',".
            "`OPV3` date NOT NULL default '0000-00-00',".
            "`MSL` date NOT NULL default '0000-00-00',".
            "`HEPB1` date NOT NULL default '0000-00-00',".
            "`HEPB2` date NOT NULL default '0000-00-00',".
            "`HEPB3` date NOT NULL default '0000-00-00',".
            //"`icd10` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`patient_id`),".
            //"KEY `key_consult` (`consult_id`),".
            "KEY `key_patient` (`patient_id`),".
            "CONSTRAINT `m_patient_epi_tcl_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE,".
            //"CONSTRAINT `m_patient_epi_tcl_ibfk_2` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_consult_epi_report`;");
        module::execsql("DROP TABLE `m_patient_epi_tcl`;");

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _epi_report() {
    //
    // main API to reports
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
        if ($exitinfo = $this->missing_dependencies('vaccine')) {
            return print($exitinfo);
        }
        print "<span class='patient'>".FTITLE_EPI_REPORTS."</span><br/><br/>";
        $n = new epi_report;
        $g = new graph;
        $n->report_menu($menu_id, $post_vars, $get_vars);
        print "<table><tr><td>";
        // column 1
        switch($get_vars["report_menu"]) {
        case "TCL":
            $n->form_inclusive_dates($menu_id, $post_vars, $get_vars);
	    if ($post_vars["submitreport"]) {
                $n->process_inclusive_dates($menu_id, $post_vars, $get_vars);
            }
            $n->display_tcl_inclusive_dates($menu_id, $post_vars, $get_vars);
            break;
        case "SUMMARY":
            $n->form_inclusive_dates($menu_id, $post_vars, $get_vars);
            if ($post_vars["submitreport"]) {
                $n->generate_summary($menu_id, $post_vars, $get_vars);
            }
            break;
        case "GRAPHS":
            $g->graph_menu($menu_id, $post_vars, $get_vars);
            switch($get_vars["graph"]) {
            case "LINE":
                $get_vars["module"] = $this->module;
                $g->graph_line($menu_id, $post_vars, $get_vars);
                break;
            case "BAR":
                $get_vars["module"] = $this->module;
                $g->graph_bar($menu_id, $post_vars, $get_vars);
                break;
            case "PIE":
                $get_vars["module"] = $this->module;
                $g->graph_pie($menu_id, $post_vars, $get_vars);
                break;
            }
            break;
        }
        print "</td></tr></table>";
    }

    function report_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!$get_vars["report_menu"]) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=TCL");
        }
        print "<table width='600' cellpadding='2' bgcolor='#CCCC99' cellspacing='1' style='border: 2px solid black'><tr align='left'>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=TCL' class='ptmenu'>".($get_vars["report_menu"]=="TCL"?"<b>".MENU_TCL."</b>":MENU_TCL)."</a></td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=SUMMARY' class='ptmenu'>".($get_vars["report_menu"]=="SUMMARY"?"<b>".MENU_SUMMARY."</b>":MENU_SUMMARY)."</a></td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&module=epi_report' class='ptmenu'>".($get_vars["report_menu"]=="GRAPHS"?"<b>".MENU_GRAPHS."</b>":MENU_GRAPHS)."</a></td>";
        print "</tr></table>";
    }

    function generate_summary() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // STEP 1. empty report table
        $sql_delete = "delete from m_consult_epi_report";
        $result_delete = mysql_query($sql_delete);

        // STEP 2. insert vaccines from lib in report table
        // records are unique for vaccine_id so you can enter only one set of
        // vaccines for a given start consult date
        $sql_lib = "select vaccine_id from m_lib_vaccine order by vaccine_name";
        if ($result_lib = mysql_query($sql_lib)) {
            if (mysql_num_rows($result_lib)) {
                while (list($vaccine_id) = mysql_fetch_array($result_lib)) {
                    $sql_insert = "insert into m_consult_epi_report (vaccine_id) ".
                                  "values ('$vaccine_id')";
                    $result_insert = mysql_query($sql_insert);
                }
            }
        }
        // STEP 3. loop through vaccine consult records and
        // update report table by vaccine id 
        
	// start date
        list($month,$day,$year) = explode("/", $post_vars["start_date"]);
        $start_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
        
	// end date
        list($month,$day,$year) = explode("/", $post_vars["end_date"]);
        $end_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);

        $sql_consult = "select vaccine_id, source_module, count(vaccine_id) ".
                       "from m_consult_vaccine ".
                       "where to_days(actual_vaccine_date) >= to_days('$start_date') and ".
                       "to_days(actual_vaccine_date) <= to_days('$end_date') group by vaccine_id";
		       
        if ($result_consult = mysql_query($sql_consult)) {
            if (mysql_num_rows($result_consult)) {
                while (list($vaccine_id, $source_module, $vaccine_count) = mysql_fetch_array($result_consult)) {
		    //$patient_age = patient::get_age($patient_id);
                    //$gender = patient::get_gender($patient_id);

		    if ($source_module == 'ccdev') {
		       $sql_update = "update m_consult_epi_report set ".
		                     "vaccine_total_child = '$vaccine_count', ".
				     "start_date = '$start_date', end_date = '$end_date' ".
				     "where vaccine_id = '$vaccine_id'";
		       $result_update = mysql_query($sql_update);
		    }

		    if ($source_module == 'mc') {
		       $sql_update = "update m_consult_epi_report set ".
                                     "vaccine_total_pregnant = '$vaccine_count', ".
				     //"vaccine_total = vaccine_total + 1, source_module = '$source_module' ".
                                     "start_date = '$start_date', end_date = '$end_date' ".
				     "where vaccine_id = '$vaccine_id'";
		       $result_update = mysql_query($sql_update);
		    }

		    if ($source_module == 'vaccine') {
		       $sql_update = "update m_consult_epi_report set ".
		                     "vaccine_total_non_pregnant = '$vaccine_count', ".
				     //"vaccine_total = vaccine_total + 1, source_module = '$source_module' ".
                                     "start_date = '$start_date', end_date = '$end_date' ".
				     "where vaccine_id = '$vaccine_id'";
		       $result_update = mysql_query($sql_update);
		    }
                }
            }
        }
	
        // STEP 4. display
        $sql = "select l.vaccine_name, c.* ".
               "from m_consult_epi_report c, m_lib_vaccine l ".
               "where l.vaccine_id = c.vaccine_id order by l.vaccine_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<br/>";
                print "<b>EPI WEEKLY MONITORING REPORT</b><br/><br/>";
                print "START: ".$post_vars["start_date"]."<br/>";
                print "END: ".$post_vars["end_date"]."<br/><br/>";
                print "<table width='500' cellspacing='0' cellpadding='2' style='border: 1px solid black'>";
                print "<tr bgcolor='#FFCC33'>";
                print "<td class='tinylight' rowspan='2' valign='middle'><b>VACCINE</b></td>";
		print "<td class='tinylight' colspan='3' width='60%' align=center><b>Total No. of Shots Given</b></td>";
		print "</tr>";
                print "<tr bgcolor='#FFCC33'>";
                print "<td class='tinylight' align='center'><b>CHILD</b></td>";
                print "<td class='tinylight' align='center'><b>PREGNANT</b></td>";
		print "<td class='tinylight' align='center'><b>NON-PREGNANT</b></td>";
		print "</tr>";
		
                while ($report = mysql_fetch_array($result)) {
                    $bgcolor=($bgcolor=="#FFFF99"?"white":"#FFFF99");
                    print "<tr bgcolor='$bgcolor'>";
                    print "<td class='tinylight'>".$report["vaccine_name"]."</td>";
                    print "<td class='tinylight' align='center'>".($report["vaccine_total_child"]==0?"-":$report["vaccine_total_child"])."</td>";
                    print "<td class='tinylight' align='center'>".($report["vaccine_total_pregnant"]==0?"-":$report["vaccine_total_pregnant"])."</td>";
		    print "<td class='tinylight' align='center'>".($report["vaccine_total_non_pregnant"]==0?"-":$report["vaccine_total_non_pregnant"])."</td>";
		    print "</tr>";
                }
                print "</table>";
            }
        }
	
	/*$sql = "select count(distinct(patient_id)) from m_patient_ccdev ".
	       "where round((to_days(fully_immunized_date) - to_days(ccdev_dob))/365,2) >= 0.75 and ".
	       "round((to_days(fully_immunized_date) - to_days(ccdev_dob))/365,2) < 1.00 and ".
	       "to_days(fully_immunized_date) >= to_days('$start_date') and ".
	       "to_days(fully_immunized_date) <= to_days('$end_date')"; */
	$sql = "select count(distinct(patient_id)) from m_consult_ccdev_vaccine ".
               "where vaccine_id='MSL' and to_days(actual_vaccine_date) >= to_days('$start_date') and ".
               "to_days(actual_vaccine_date) <= to_days('$end_date') and ".
	       //"round((to_days(c.actual_vaccine_date) - to_days(p.ccdev_dob))/365, 2) >= 0.75 and ".
	       "age_on_vaccine >= 36 and age_on_vaccine < 48 ";
	       //"round((to_days(c.actual_vaccine_date) - to_days(p.ccdev_dob))/365, 2) < 1.00 ";     
        $actual_fic = mysql_result(mysql_query($sql),0);
        
	//$sql = "select count(distinct(patient_id)) from m_patient_ccdev ".
        //       "where round((to_days(fully_immunized_date) - to_days(ccdev_dob))/365,2) > 1.00 and ".
        //       "to_days(fully_immunized_date) >= to_days('$start_date') and ".
        //       "to_days(fully_immunized_date) <= to_days('$end_date')"; */
	$sql = "select count(distinct(c.patient_id)) from m_consult_ccdev_vaccine c, m_patient_ccdev p ".
               "where c.vaccine_id='MSL' and to_days(c.actual_vaccine_date) >= to_days('$start_date') and ".
               "to_days(c.actual_vaccine_date) <= to_days('$end_date') and ".
	       //"round((to_days(c.actual_vaccine_date) - to_days(p.ccdev_dob))/365, 2) >= 1.00 ";
	       "age_on_vaccine >= 48 ";
	       //"c.patient_id = p.patient_id";
	$bdate_fic = mysql_result(mysql_query($sql),0);

	$sql = "select count(distinct(patient_id)) from m_consult_vaccine ".
	       "where vaccine_id = 'TT5' and source_module = 'mc' and ".
               "to_days(actual_vaccine_date) >= to_days('$start_date') and ".
	       "to_days(actual_vaccine_date) <= to_days('$end_date')";
	$fully_immunized_mother = mysql_result(mysql_query($sql),0);
        
	print "<br/>";
	print "Actual FIC (9 mos.) : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $actual_fic <br/>";
	print "B-Date FIC (1 yr. old) : &nbsp;&nbsp;&nbsp; $bdate_fic <br/>";
	print "Fully Immunized Mother : &nbsp; $fully_immunized_mother";
	
    }

    function display_tcl_inclusive_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<a href='../modules/_uploads/epi_tcl.pdf' target='_blank'>EPI TCL</a><br/>";
    }

    function form_inclusive_dates() {
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
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."' name='form_inclusive_dates' method='post'>";
        print "<tr valign='top'><td>";
        //print "<input type='hidden' name='patient_id' value='$patient_id'/>";
        print "<b>".FTITLE_INCLUSIVE_DATES_FORM."</b><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_START_DATE."</span><br> ";
        print "<input type='text' size='10' class='textbox' name='start_date' value='' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_inclusive_dates.start_date', document.form_inclusive_dates.start_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select start date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_END_DATE."</span><br> ";
        print "<input type='text' size='10' class='textbox' name='end_date' value='$thisday' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_inclusive_dates.end_date', document.form_inclusive_dates.end_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select end date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_REPORT_WEEK."</span><br> ";
        print "<select name='report_week' class='textbox'>";
        print "<option value='0'>Select Week</option>";
        for ($i=1; $i<=52; $i++) {
            print "<option value='$i'>Week $i</option>";
        }
        print "</select>";
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<br><input type='submit' value = 'Generate Report' class='textbox' name='submitreport' style='border: 1px solid #000000'><br>";

        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_inclusive_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
	    //$start_date = $arg_list[1];
	    //$end_date = $arg_list[2];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }

	$sql_delete = "delete from m_patient_epi_tcl";
	$result_delete = mysql_query($sql_delete);

        list($month,$day,$year) = explode("/", $post_vars["start_date"]);
	$start_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
        
	list($month,$day,$year) = explode("/", $post_vars["end_date"]);
	$end_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
		
        // loop through m_consult_vaccine
        $sql = "select p.patient_id, p.patient_dob, concat(p.patient_lastname, ', ', p.patient_firstname) ".
	       "patient_name, p.patient_mother, ".
	       "date_format(adddate(p.patient_dob, interval 1 year), '%m-%Y') month_reaches_age1, m.fully_immunized_date ".
               "from m_patient p, m_patient_ccdev m ".
               "where p.patient_id = m.patient_id and ".
               "to_days(p.patient_dob) >= to_days('$start_date') and ".
	       "to_days(p.patient_dob) <= to_days('$end_date') order by p.patient_dob ";
					      
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($report = mysql_fetch_array($result)) {
                    // blank variables
                    $family_id = 0;
                    $patient_address = '';
                    $barangay_name = '';
                    //$vaccines = '';
                    //$icd_code = '';
                    // retrieve other data
                    $family_id = family::get_family_id($report["patient_id"]);
                    if ($family_id) {
                        $patient_address = family::show_address($family_id);
                        $barangay_name = family::barangay_name($family_id);
                    }

                    // insert data into tcl
                    $sql_insert = "insert into m_patient_epi_tcl (patient_id, patient_dob, family_id, patient_name, ".
                                  "patient_mother, patient_address, barangay_name, month_reaches_age1, ".
                                  "fully_immunized_date) values ('".$report["patient_id"]."', ".
				  "'".$report["patient_dob"]."', '$family_id', '".$report["patient_name"]."', ".
				  "'".$report["patient_mother"]."', '$patient_address', '$barangay_name', ".
                                  "'".$report["month_reaches_age1"]."', '".$report["fully_immunized_date"]."')";
                    $result_insert = mysql_query($sql_insert) or
die(mysql_error());

		    $this->get_vaccine_date($report["patient_id"], $start_date, $end_date);
                
		} // while
                
		$sql = "select * from m_patient_epi_tcl ". 
                       //"where to_days(actual_vaccine_date) >= to_days('$start_date') and ".
	               //"to_days(actual_vaccine_date) <= to_days('$end_date') ".
		       "order by barangay_name, patient_dob ";
		       
		$pdf = new PDF('L','pt','Legal');
                $pdf->SetFont('Arial','',10);
                $pdf->AliasNbPages();
                $pdf->connect('localhost','root','root','lagrosa');
                $attr=array('titleFontSize'=>14,'titleText'=>'EPI REGISTER');
                $pdf->mysql_report($sql,false,$attr, "../modules/_uploads/epi_tcl.pdf");
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=TCL");
            }
        } 
    }

    function get_vaccine_date () {
        if (func_num_args() > 0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $start_date = $arg_list[1];
	    $end_date = $arg_list[2]; 
        }

        $sql = "select actual_vaccine_date, vaccine_id from m_consult_ccdev_vaccine ".
               "where patient_id = '$patient_id' ";
	       //"to_days(c.consult_timestamp) >= to_days('$start_date') and ".
	       //"to_days(c.consult_timestamp) <= to_days('$end_date') ";
        
	if ($result_sql = mysql_query($sql)) {
            if (mysql_num_rows($result_sql)) {
                while (list($actual_vaccine_date, $vaccine_id) = mysql_fetch_array($result_sql)) {
		    if ($vaccine_id == 'BCG') {
                        $sql_update = "update m_patient_epi_tcl set BCG = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }
			
		    if ($vaccine_id == 'DPT1') {
                        $sql_update = "update m_patient_epi_tcl set DPT1 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }
                    
		    if ($vaccine_id == 'DPT2') {
                        $sql_update = "update m_patient_epi_tcl set DPT2 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }   
	
		    if ($vaccine_id == 'DPT3') {
                        $sql_update = "update m_patient_epi_tcl set DPT3 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }
	
		    if ($vaccine_id == 'OPV1') {
                        $sql_update = "update m_patient_epi_tcl set OPV1 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }

		    if ($vaccine_id == 'OPV2') {
                        $sql_update = "update m_patient_epi_tcl set OPV2 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }

		    if ($vaccine_id == 'OPV3') {
                        $sql_update = "update m_patient_epi_tcl set OPV3 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    } 

		    if ($vaccine_id == 'MSL') {
                        $sql_update = "update m_patient_epi_tcl set MSL = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }

		    if ($vaccine_id == 'HEPB1') {
                        $sql_update = "update m_patient_epi_tcl set HEPB1 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }

		    if ($vaccine_id == 'HEPB2') {
                        $sql_update = "update m_patient_epi_tcl set HEPB2 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }

		    if ($vaccine_id == 'HEPB3') {
                        $sql_update = "update m_patient_epi_tcl set HEPB3 = '$actual_vaccine_date' ".
                                      "where patient_id = '$patient_id' ";
			$result = mysql_query($sql_update);
		    }
		}
	    }
	}
    }


// end of class
}
?>
