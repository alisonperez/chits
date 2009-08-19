<?
class mc_report extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function mc_report() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "mc_report";
        $this->description = "CHITS Module - MC Reports";

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
        module::set_dep($this->module, "mc");
		
    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_MC_REPORTS", "english", "MATERNAL CARE REPORTS", "Y");
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
        module::set_menu($this->module, "Maternal Care Reports", "REPORTS", "_mc_report");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE m_patient_mc_prenatal_tcl (".
            "`mc_id` float NOT NULL default '0',". 
            "`patient_id` float NOT NULL default '0',". 
            "`registration_date` date NOT NULL default '0000-00-00',".
            "`family_id` varchar(10) NOT NULL default '',".
            "`patient_name` varchar(50) NOT NULL default '',".
            "`patient_age` float NOT NULL default '0',".
            "`patient_address` varchar(100) NOT NULL default '',".
            "`barangay_name` varchar(10) NOT NULL default '',".
            "`patient_lmp` date NOT NULL default '0000-00-00',".
            "`obscore_gp` varchar(10) NOT NULL default '',".
            "`patient_edc` date NOT NULL default '0000-00-00',".
            "`trimester1_visit_dates` varchar(50) NOT NULL default '',".
            "`trimester2_visit_dates` varchar(50) NOT NULL default '',".
            "`trimester3_visit_dates` varchar(50) NOT NULL default '',".
            "`risk_id_date` varchar(100) NOT NULL default '',".
            "`fully_immunized_date` varchar(15) default '',".
	    "`TT_vaccine_dates` varchar(100) default '',". 
            "`IRON` varchar(50) default '',".
            "`delivery_date` varchar(15) default '',".
            "`outcome_id` varchar(10) NOT NULL default '',".
            "`birthweight` varchar(5) NOT NULL default '',".
            "`delivery_location` varchar(10) NOT NULL default '',".
            "`attendant_name` varchar(20) NOT NULL default '',".   
            "PRIMARY KEY  (`mc_id`), ".
            "KEY `key_mc` (`mc_id`),".
            "CONSTRAINT `m_patient_mc_prenatal_tcl_ibfk_1` FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc` (`mc_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE m_patient_mc_postpartum_tcl (".
            "`mc_id` float NOT NULL default '0',".   
            "`patient_id` float NOT NULL default '0',".   
            "`family_id` varchar(10) NOT NULL default '',".    
            "`patient_name` varchar(50) NOT NULL default '',".
            "`patient_age` float NOT NULL default '0',".
            "`patient_address` varchar(100) NOT NULL default '',".
            "`barangay_name` float NOT NULL default '0',".
            "`postpartum_wk4` varchar(10) NOT NULL default '',".
            "`postpartum_wk6` varchar(10) NOT NULL default '',". 
            "`postpartum_home_visit` varchar(100) default '',".
            "`postpartum_clinic_visit` varchar(100) default '',".
            "`date_started_breastfeeding` varchar(15) default '',".
            "`IRON` varchar(50) default '',".  
            "`VITA` varchar(50) default '',".  
            "PRIMARY KEY  (`mc_id`),".
            "KEY `key_mc` (`mc_id`),".
            "CONSTRAINT `m_patient_mc_postpartum_tcl_ibfk_1` FOREIGN KEY (`mc_id`) REFERENCES `m_patient_mc` (`mc_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");
            
        module::execsql("CREATE TABLE m_consult_mc_consolidation_report (".
            "`barangay_name` varchar(50) NOT NULL default '',".
            "`barangay_population` int(11) NOT NULL default '0',".
            "`pn_visits3plus` int(11) NOT NULL default '0',".
            "`pn_visits3plus_percent` float NOT NULL default '0',".
            "`pn_tt2plus` int(11) NOT NULL default '0',".
            "`pn_tt2plus_percent` float NOT NULL default '0',".
            "`pn_iron` int(11) NOT NULL default '0',".
            "`pn_iron_percent` float NOT NULL default '0',".
            "`pp_visit1plus` int(11) NOT NULL default '0',".
            "`pp_visit1plus_percent` float NOT NULL default '0',".
            "`pp_iron` int(11) NOT NULL default '0',".
            "`pp_iron_percent` float NOT NULL default '0',".
            "`pp_breastfeeding` int(11) NOT NULL default '0',".
            "`pp_breastfeeding_percent` float NOT NULL default '0',".
            "`lactating_vitA` int(11) NOT NULL default '0',".
            "`lactating_vitA_percent` float NOT NULL default '0',".
            "`F_15to49_iodized_oil` int(11) NOT NULL default '0',".
            "`F_15to49_iodized_oil_percent` float NOT NULL default '0',".
            "PRIMARY KEY (`barangay_name`)".
            ") TYPE=MyISAM;");
    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_patient_mc_prenatal_tcl`;");
        module::execsql("DROP TABLE `m_patient_mc_postpartum_tcl`;");

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _mc_report() {
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
        if ($exitinfo = $this->missing_dependencies('mc_report')) {
            return print($exitinfo);
        }
        print "<span class='patient'>".FTITLE_MC_REPORTS."</span><br/><br/>";
        $m = new mc_report;
        $g = new graph;
        $m->report_menu($menu_id, $post_vars, $get_vars);
        print "<table><tr><td>";
        // column 1
        switch($get_vars["report_menu"]) {
        case "TCL":
		    if ($post_vars["submitreport"]) {
                $m->process_inclusive_dates($menu_id, $post_vars, $get_vars);
            }
            $m->form_inclusive_dates($menu_id, $post_vars, $get_vars);
            $m->display_tcl_inclusive_dates($menu_id, $post_vars, $get_vars);
            break;
        case "SUMMARY":
            $m->summary_menu($menu_id, $post_vars, $get_vars);
            switch($get_vars[report])
              {
              case "CONSOLIDATION":
                $get_vars[module] = $this->module;
                $m->summary_inclusive_dates($menu_id, $post_vars, $get_vars);
                if ($post_vars[submitreport])
                  {
                  $m->consolidation_report($menu_id, $post_vars, $get_vars);
                  $m->display_mc_consolidation_report($menu_id, $post_vars, $get_vars);
                  }
                break;
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

    function summary_menu()
      {
      if (func_num_args()>0) 
        {
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];
        }
        
      echo("
      <table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'>
      <tr valign='top'>
        <td nowrap><span class='groupmenu'><font color='#666699'><b>SUMMARY REPORT TYPE</b></font></span>
          <a href='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&report_menu=$get_vars[report_menu]&report=CONSOLIDATION&module=$get_vars[module]' class='groupmenu'>".strtoupper(($get_vars[report]=='CONSOLIDATION'?'<b>CONSOLIDATION</b>':'CONSOLIDATION'))."</a>
        </td>
      </tr>
      </table>
      ");
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
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=SUMMARY&module=mc_report' class='ptmenu'>".($get_vars["report_menu"]=="SUMMARY"?"<b>".MENU_SUMMARY."</b>":MENU_SUMMARY)."</a></td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&module=mc_report' class='ptmenu'>".($get_vars["report_menu"]=="GRAPHS"?"<b>".MENU_GRAPHS."</b>":MENU_GRAPHS)."</a></td>";
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

        // STEP 2. insert vaccines from lib in report table
        // records are unique for vaccine_id so you can enter only one set of
        // vaccines for a given start consult date

        // STEP 3. loop through vaccine consult records and
        // update report table by vaccine id 
        
	// start date
        list($month,$day,$year) = explode("/", $post_vars["start_date"]);
        $start_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
        
	// end date
        list($month,$day,$year) = explode("/", $post_vars["end_date"]);
        $end_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);

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
        print "<span class='boxtitle'>TYPE OF REPORT</span><br> ";
		print "<select name='mc_type'>";
		print "<option value='pre'>Prenatal</option>";
		print "<option value='post'>Postpartum</option>";
		echo "</select>";

		/*
        print "<span class='boxtitle'>".LBL_REPORT_WEEK."</span><br> ";
        print "<select name='report_week' class='textbox'>";
        print "<option value='0'>Select Week</option>";
        for ($i=1; $i<=52; $i++) {
            print "<option value='$i'>Week $i</option>";
        }
        print "</select>";
        print "<br/></td></tr>";
		*/
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<br><input type='submit' value = 'Generate Report' class='textbox' name='submitreport' style='border: 1px solid #000000'><br>";

        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    /////
    ///// MATERNAL TCL's
    /////
    
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
//        print "<a href='../modules/_uploads/mc_prenatal_tcl.pdf' target='_blank'>MC PRENATAL TCL</a><br/>";
//        print "<a href='../modules/_uploads/mc_postpartum_tcl.pdf' target='_blank'>MC POSTPARTUM TCL</a><br/>";
    }
    
    function process_inclusive_dates() 
      {
      if (func_num_args()>0) 
        {
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];
        //print_r($arg_list);
        }

      $sql_delete = "delete from m_patient_mc_prenatal_tcl";
      $result_delete = mysql_query($sql_delete) or die(mysql_error());

      $sql_delete = "delete from m_patient_mc_postpartum_tcl";
      $result_delete = mysql_query($sql_delete) or die(mysql_error());
	
      list($month,$day,$year) = explode("/", $post_vars["start_date"]);
      $start_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
        
      list($month,$day,$year) = explode("/", $post_vars["end_date"]);
      $end_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
		
      // prenatal
      $get_prenatal = mysql_query("SELECT
              m.mc_id, 
              p.registration_date,
              p.patient_id,
              concat(p.patient_lastname,', ',p.patient_firstname) patient_name,
              m.patient_lmp,
              m.obscore_gp,
              m.patient_edc,
              m.trimester1_date,
              m.trimester2_date,
              m.trimester3_date,
              date_format(m.delivery_date, '%c/%e/%y') delivery_date,
              m.outcome_id, 
              m.birthweight,
              m.delivery_location,
              round((to_days(now())-to_days(p.patient_dob))/365,2) patient_age,
              m.birthmode
              FROM m_patient p, m_patient_mc m
              WHERE p.patient_id = m.patient_id AND
              to_days(p.registration_date) >= to_days('$start_date') AND
              to_days(p.registration_date) <= to_days('$end_date') AND
              m.delivery_date = '0000-00-00'")
              or die(mysql_error());
              
      /*$sql = "select m.mc_id, p.registration_date, p.patient_id, concat(p.patient_lastname, ', ', p.patient_firstname) ".
	       "patient_name, m.patient_lmp, m.obscore_gp, m.patient_edc, m.trimester1_date, m.trimester2_date, ".
	       "m.trimester3_date, date_format(m.delivery_date, '%c/%e/%y') delivery_date, m.outcome_id, m.birthweight, ".
	       "m.delivery_location, ".
	       "m.breastfeeding_asap, round((to_days(now())-to_days(p.patient_dob))/365,2) patient_age, ".
	       "adddate(m.delivery_date, interval 28 day) postpartum_wk4, ".
	       "adddate(m.delivery_date, interval 42 day) postpartum_wk6, m.birthmode ".
	       "from m_patient p, m_patient_mc m ".
               "where p.patient_id = m.patient_id and ".
               "to_days(p.registration_date) >= to_days('$start_date') and ".
	       "to_days(p.registration_date) <= to_days('$end_date') "; */

      if ($get_prenatal) 
        {
        if (mysql_num_rows($get_prenatal)) 
          {
          while ($prenatal = mysql_fetch_array($get_prenatal)) 
            {
            // blank variables
            $family_id = '';
            $patient_address = '';
            $barangay_id = '';

            // retrieve other data
            $family_id = family::get_family_id($prenatal["patient_id"]);
            if ($family_id != '0') 
              {
              $patient_address = family::show_address($family_id);
              $barangay_id = family::barangay_id($family_id);
              }

            if ($prenatal[birthweight] == '0')
              {
              $birthweight = '';
              }
            else
              {
              $birthweight = $prenatal[birthweight];
              }
                      
            if ($prenatal[delivery_date] == '0/0/00')
              {
              $delivery_date = '';
              }
            else 
              {
              $delivery_date = $prenatal[delivery_date];
              }
		      
            $fully_immunized_date = $this->get_fully_immunized_tt_date($prenatal["patient_id"]);
            $tt_vaccines = $this->get_tt_vaccines($prenatal["patient_id"]);
	    $risk_id_date = $this->get_risk_id_date($prenatal["patient_id"]);
	    $iron_date = $this->get_iron_date($prenatal["patient_id"]);
	    /*$vita_date = $this->get_vita_date($report["patient_id"]);
	    $home_visit_dates = $this->get_home_visit_dates($report["patient_id"]);
	    $clinic_visit_dates = $this->get_clinic_visit_dates($report["patient_id"],$report[postpartum_wk4],$report[postpartum_wk6]);*/
	    $trimester1_visit_dates = $this->get_trimester1_visit_dates($prenatal["patient_id"],$prenatal["trimester1_date"],$prenatal["patient_lmp"]);
	    $trimester2_visit_dates = $this->get_trimester2_visit_dates($prenatal["patient_id"],$prenatal["trimester2_date"],$prenatal["trimester1_date"]);
	    $trimester3_visit_dates = $this->get_trimester3_visit_dates($prenatal["patient_id"],$prenatal["trimester3_date"],$prenatal["trimester2_date"]);
	    //$breastfeeding_date = $this->get_breastfeeding_date($report["patient_id"]);
	    $attendant = $this->get_attendant($prenatal[birthmode]); 
	      
	    //insert data into prenatal tcl
	    $sql_insert = "insert into m_patient_mc_prenatal_tcl (mc_id, patient_id, registration_date, family_id, patient_name, ".
                "patient_age, patient_address, barangay_name, patient_lmp, obscore_gp, patient_edc, ".
                "trimester1_visit_dates, trimester2_visit_dates, trimester3_visit_dates, risk_id_date, ".
                "fully_immunized_date, TT_vaccine_dates, IRON, delivery_date, outcome_id, birthweight, ".
		"delivery_location, attendant_name) values ('".$prenatal["mc_id"]."', ".
		"'".$prenatal["patient_id"]."', '".$prenatal["registration_date"]."', ".
		"'$family_id', '".$prenatal["patient_name"]."', '".$prenatal["patient_age"]."', ".
		"'$patient_address', '$barangay_id', '".$prenatal["patient_lmp"]."', ".
		"'".$prenatal["obscore_gp"]."', '".$prenatal["patient_edc"]."', ".
		"'$trimester1_visit_dates', '$trimester2_visit_dates', ".
                "'$trimester3_visit_dates', '$risk_id_date', '$fully_immunized_date', ".
		"'$tt_vaccines', '$iron_date', '$delivery_date', ".
 		"'".$prenatal["outcome_id"]."', '$birthweight', ".
		"'".$prenatal["delivery_location"]."', '$attendant')";
            $result_insert = mysql_query($sql_insert) or die(mysql_error());
	      
	    /*$sql_insert = "insert into m_patient_mc_postpartum_tcl (mc_id, patient_id, family_id, patient_name, ".
                                  "patient_age, patient_address, barangay_name, postpartum_wk4, postpartum_wk6, ".
				  "date_started_breastfeeding, postpartum_home_visit, postpartum_clinic_visit, ".
				  "IRON, VITA) values ('".$report["mc_id"]."', '".$report["patient_id"]."', ".
				  "'$family_id', '".$report["patient_name"]."', '".$report["patient_age"]."', ".
				  "'$patient_address', '$barangay_id', '".$report["postpartum_wk4"]."', ".
				  "'".$report["postpartum_wk6"]."', '$breastfeeding_date','$home_visit_dates', ".
				  "'$clinic_visit_dates', '$iron_date', '$vita_date')";
            $result_insert = mysql_query($sql_insert) or die(mysql_error());*/

            } // while
          }
        }

      // get postpartum
      /*$get_pp = mysql_query("SELECT
              m.mc_id,
              p.registration_date,
              p.patient_id,
              concat(p.patient_lastname,', ',p.patient_firstname) patient_name,
              m.breastfeeding_asap,
              round((to_days(now())-to_days(p.patient_dob))/365,2) patient_age,
              adddate(m.delivery_date, interval 28 day) postpartum_wk4,
              adddate(m.delivery_date, interval 42 day) postpartum_wk6
              FROM m_patient p, m_patient_mc m
              WHERE p.patient_id = m.patient_id AND
              to_days(p.registration_date) >= to_days('$start_date') AND
              to_days(p.registration_date) <= to_days('$end_date') AND
              m.delivery_date <> '0000-00-00' ")
              or die(mysql_error());*/
              
      /*$get_pp = mysql_query("SELECT
              m.mc_id,
              p.registration_date,
              p.patient_id,
              concat(p.patient_lastname,', ',p.patient_firstname) patient_name,
              m.breastfeeding_asap,
              round((to_days(now())-to_days(p.patient_dob))/365,2) patient_age,
              adddate(m.delivery_date, interval 28 day) postpartum_wk4,
              adddate(m.delivery_date, interval 42 day) postpartum_wk6
              FROM m_patient p, m_patient_mc m
              WHERE p.patient_id = m.patient_id AND
              to_days(m.postpartum_date) >= to_days('$start_date') AND
              to_days(m.postpartum_date) <= to_days('$end_date') ")
              or die(mysql_error());*/
              
      $get_pp = mysql_query("SELECT
              m.mc_id,
              p.registration_date, pp.postpartum_date, pp.visit_sequence,
              p.patient_id,
              concat(p.patient_lastname,', ',p.patient_firstname) patient_name,
              m.breastfeeding_asap,
              round((to_days(now())-to_days(p.patient_dob))/365,2) patient_age,
              adddate(m.delivery_date, interval 28 day) postpartum_wk4,
              adddate(m.delivery_date, interval 42 day) postpartum_wk6
              FROM m_patient p, m_patient_mc m, m_consult_mc_postpartum pp
              WHERE p.patient_id = m.patient_id AND
              to_days(pp.postpartum_date) >= to_days('$start_date') AND
              to_days(pp.postpartum_date) <= to_days('$end_date') AND 
              m.patient_id=pp.patient_id AND 
              pp.visit_sequence='1'") or die(mysql_error());
              
      if ($get_pp) 
        {
        if (mysql_num_rows($get_pp)) 
          {
          while ($pp = mysql_fetch_array($get_pp)) 
            {
            // blank variables
            $family_id = '';
            $patient_address = '';
            $barangay_id = '';

            // retrieve other data
            $family_id = family::get_family_id($pp["patient_id"]);
            if ($family_id != '0') 
              {
              $patient_address = family::show_address($family_id);
              $barangay_id = family::barangay_id($family_id);
              }
                
            $vita_date = $this->get_vita_date($pp["patient_id"]);
            $iron_date = $this->get_iron_date($pp["patient_id"]);
	    $home_visit_dates = $this->get_home_visit_dates($pp["patient_id"]);
	    $clinic_visit_dates = $this->get_clinic_visit_dates($pp["patient_id"],$pp[postpartum_wk4],$pp[postpartum_wk6]);
	    $breastfeeding_date = $this->get_breastfeeding_date($pp["patient_id"]);
	      
	    // insert into postpartum tcl
	    $sql_insert = "insert into m_patient_mc_postpartum_tcl (mc_id, patient_id, family_id, patient_name, ".
	        "patient_age, patient_address, barangay_name, postpartum_wk4, postpartum_wk6, ".
		"date_started_breastfeeding, postpartum_home_visit, postpartum_clinic_visit, ".
		"IRON, VITA) values ('".$pp["mc_id"]."', '".$pp["patient_id"]."', ".
		"'$family_id', '".$pp["patient_name"]."', '".$pp["patient_age"]."', ".
		"'$patient_address', '$barangay_id', '".$pp["postpartum_wk4"]."', ".
		"'".$pp["postpartum_wk6"]."', '$breastfeeding_date','$home_visit_dates', ".
		"'$clinic_visit_dates', '$iron_date', '$vita_date')";
              
            $result_insert = mysql_query($sql_insert) or die(mysql_error());
            }
          }
        }
                
      $sql = "select date_format(registration_date, '%c/%e/%y') 'REGISTRATION DATE', ".
            "family_id 'FAMILY ID', concat(patient_name, ' / ', patient_age) 'NAME / AGE', ".
            "patient_address 'ADDRESS', barangay_name 'BRGY', date_format(patient_lmp, '%c/%e/%y') 'LMP', ".
            "obscore_gp 'G-P', date_format(patient_edc, '%c/%e/%y') 'EDC', ".
            "trimester1_visit_dates '1st TRIMESTER VISITS', ".
            "trimester2_visit_dates '2nd TRIMESTER VISITS', ".
            "trimester3_visit_dates '3rd TRIMESTER VISITS', ".
            "risk_id_date 'RISK CODE / DATE DETECTED', fully_immunized_date ".
            "'FULLY IMMUNIZED DATE', TT_vaccine_dates 'TT IMMUNIZATION GIVEN', ".
            "IRON 'IRON', delivery_date 'DATE TERMINATED', ".
            "outcome_id 'OUTCOME', birthweight 'BIRTH WEIGHT', delivery_location 'PLACE OF DELIVERY', ".
            "attendant_name 'ATTENDED BY' ". 
            "from m_patient_mc_prenatal_tcl order by barangay_name, registration_date "; 
            
      $pdf = new PDF('L','pt','Legal');
      $pdf->SetFont('Arial','',10);
      $pdf->AliasNbPages();
      $pdf->connect('localhost','root','root','cuartero');
      $attr=array('titleFontSize'=>14,'titleText'=>'TARGET CLIENT LIST FOR PRENATAL CARE ('.$post_vars["start_date"].' - '.$post_vars["end_date"].')');
      $pdf->mysql_report($sql,false,$attr,"../modules/_uploads/mc_prenatal_tcl.pdf");
      header("location:".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=TCL");

      $sql = "select family_id 'FAMILY ID', concat(patient_name, ' / ', patient_age) 'NAME / AGE', ".
	    "patient_address 'ADDRESS', barangay_name 'BRGY', date_format(postpartum_wk4, '%c/%e/%y') '4TH PP WK', ".
	    "date_format(postpartum_wk6, '%c/%e/%y') '6TH PP WK', ".
	    "postpartum_home_visit 'DATES OF POSTPARTUM CARE HOME VISITS', ".
	    "postpartum_clinic_visit 'DATES OF POSTPARTUM CARE CLINIC CHECK-UP BTW 4-6 WKS PP', ".
	    "date_started_breastfeeding 'DATE STARTED BREASTFEEDING', ".
	    "IRON 'IRON', VITA 'VITAMIN A' ".
	    "from m_patient_mc_postpartum_tcl order by barangay_name, patient_name "; 
        
      $pdf = new PDF('L','pt','Legal');
      $pdf->SetFont('Arial','',12);
      $pdf->AliasNbPages();
      $pdf->connect('localhost','root','root','chits');
      $attr=array('titleFontSize'=>14,'titleText'=>'TARGET CLIENT LIST FOR POSTPARTUM CARE ('.$post_vars["start_date"].' - '.$post_vars["end_date"].')');
      $pdf->mysql_report($sql,false,$attr,"../modules/_uploads/mc_postpartum_tcl.pdf");
      header("location:".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=TCL");

      }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////// SUMMARY REPORTS ////////////////////////////////////////////////////////////////////
    
    // QUARTERLY CONSOLIDATION TABLE
    
    function consolidation_report()
      {
      if (func_num_args()>0) 
        {
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];
        //print_r($post_vars);
        }
        
      // STEP 1. empty report table
      $delete_table = mysql_query("DELETE FROM m_consult_mc_consolidation_report") or die(mysql_error());
      
      if ($post_vars[quarter] && $post_vars[year])
        {
        switch($post_vars[quarter])
          {
          case "first":
            $start_date = $post_vars[year]."-".'01-01';
            $end_date = $post_vars[year]."-".'03-31';
            break;
          case "second":
            $start_date = $post_vars[year]."-".'04-01';
            $end_date = $post_vars[year]."-".'06-30';
            break;
          case "third":
            $start_date = $post_vars[year]."-".'07-01';
            $end_date = $post_vars[year]."-".'09-30';
            break;
          case "fourth":
            $start_date = $post_vars[year]."-".'10-01';
            $end_date = $post_vars[year]."-".'12-31';
            break;
          }
        }
      
      $query_barangay = mysql_query("SELECT barangay_name, barangay_population FROM m_lib_barangay");
      
      if ($query_barangay)
        {
        while(list($brgy_name, $brgy_popn) = mysql_fetch_array($query_barangay))
          {
          $insert_barangay = mysql_query("INSERT INTO m_consult_mc_consolidation_report (barangay_name, barangay_population)
              VALUES ('$brgy_name', '$brgy_popn')");
          }
        }
        
      // No. of pregnant women w/ at least 3 prenatal visits
      $query_prenatal = mysql_query("SELECT b.barangay_name, 
        count(distinct(m.mc_id)), (count(distinct(m.mc_id)) / b.barangay_population) * 3.5
        FROM m_consult_mc_prenatal m, m_family_members f, m_family_address a, m_lib_barangay b
        WHERE f.patient_id = m.patient_id
        AND a.barangay_id = b.barangay_id
        AND a.family_id = f.family_id
        AND to_days(m.prenatal_date) >= to_days('$start_date')
        AND to_days(m.prenatal_date) <= to_days('$end_date')
        AND m.visit_sequence >= 3
        GROUP BY barangay_name
        ORDER BY barangay_name") or die(mysql_error());
      
      if ($query_prenatal)
        {
        while(list($brgy, $count, $percent) = mysql_fetch_array($query_prenatal))
          {
          $update_report = mysql_query("UPDATE m_consult_mc_consolidation_report 
            SET pn_visits3plus = '$count', pn_visits3plus_percent = '$percent'
            WHERE barangay_name = '$brgy'") or die(mysql_error());
          }
        }
      
      // No. of pregnant women who received at least TT1 vaccine
      $query_prenatal = mysql_query("SELECT b.barangay_name, 
        count(distinct(v.mc_id)), (count(distinct(v.mc_id)) / b.barangay_population) * 3.5
        FROM m_consult_mc_vaccine v, m_family_members f, m_family_address a, m_lib_barangay b 
        WHERE f.patient_id = v.patient_id
        AND a.barangay_id = b.barangay_id
        AND a.family_id = f.family_id
        AND to_days(v.actual_vaccine_date) >= to_days('$start_date') 
        AND to_days(v.actual_vaccine_date) <= to_days('$end_date')  
        AND v.vaccine_id != 'TT1' 
        GROUP BY barangay_name 
        ORDER by barangay_name") or die(mysql_error());
        
      if ($query_prenatal)
        {
        while(list($brgy, $count, $percent) = mysql_fetch_array($query_prenatal))
          {
          $update_report = mysql_query("UPDATE m_consult_mc_consolidation_report 
            SET pn_tt2plus = '$count', pn_tt2plus_percent = '$percent' 
            WHERE barangay_name = '$brgy'") or die(mysql_error());
          }
        }  
        
      // No. of pregnant women who received IRON dosage
      $query_prenatal = mysql_query("SELECT b.barangay_name, 
        count(distinct(s.mc_id)), (count(distinct(s.mc_id)) / b.barangay_population) * 3.5
        FROM m_consult_mc_services s, m_family_members f, m_family_address a, m_lib_barangay b 
        WHERE f.patient_id = s.patient_id
        AND a.barangay_id = b.barangay_id
        AND a.family_id = f.family_id
        AND to_days(s.mc_timestamp) >= to_days('$start_date') 
        AND to_days(s.mc_timestamp) <= to_days('$end_date')  
        AND s.service_id = 'IRON' 
        GROUP BY barangay_name 
        ORDER By barangay_name") or die(mysql_error());
        
      if ($query_prenatal)
        {
        while(list($brgy, $count, $percent) = mysql_fetch_array($query_prenatal))
          {
          $update_report = mysql_query("UPDATE m_consult_mc_consolidation_report 
            SET pn_iron = '$count', pn_iron_percent = '$percent' 
            WHERE barangay_name = '$brgy'") or die(mysql_error());
          }
        }
        
      // No. of women w/ at least one postpartum visit
      $query_postpartum = mysql_query("SELECT b.barangay_name, 
        count(distinct(m.mc_id)), (count(distinct(m.mc_id)) / b.barangay_population) * 3
        FROM m_consult_mc_postpartum m, m_family_members f, m_family_address a, m_lib_barangay b 
        WHERE f.patient_id=m.patient_id 
        AND a.barangay_id=b.barangay_id 
        AND a.family_id=f.family_id 
        AND to_days(m.postpartum_date) >= to_days('$start_date') 
        AND to_days(m.postpartum_date) <= to_days('$end_date')  
        AND m.visit_sequence  >= 1 
        GROUP BY barangay_name
        ORDER BY barangay_name") or die(mysql_error());
        
      if ($query_postpartum)
        {
        while(list($brgy, $count, $percent) = mysql_fetch_array($query_postpartum))
          {
          $update_report = mysql_query("UPDATE m_consult_mc_consolidation_report 
            SET pp_visit1plus = '$count', pp_visit1plus_percent = '$percent' 
            WHERE barangay_name = '$brgy'") or die(mysql_error());
          }
        }
      
      // No. of mothers who were given complete IRON dosage

  
      // No. of mothers who initiated brestfeeding asap
      $query_pp = mysql_query("SELECT b.barangay_name, 
        count(DISTINCT(p.mc_id)), (count(distinct(p.mc_id)) / b.barangay_population) * 3
        FROM m_patient_mc p, m_family_members f, m_family_address a, m_lib_barangay b
        WHERE f.patient_id = p.patient_id
        AND a.barangay_id = b.barangay_id
        AND a.family_id = f.family_id
        AND to_days(p.postpartum_date) >= to_days('$start_date')
        AND to_days(p.postpartum_date) <= to_days('$end_date')
        AND p.breastfeeding_asap = 'Y'
        GROUP BY barangay_name
        ORDER BY barangay_name") or die(mysql_error());
        
      if ($query_pp)
        {
        while(list($brgy, $count, $percent) = mysql_fetch_array($query_pp))
          {
          $update_report = mysql_query("UPDATE m_consult_mc_consolidation_report 
            SET pp_breastfeeding = '$count', pp_breastfeeding_percent = '$percent' 
            WHERE barangay_name = '$brgy'") or die(mysql_error());
          }
        }
        
      // No. of lactating mothers who have been given Vit. A
      $query_pp = mysql_query("SELECT b.barangay_name, 
        count(DISTINCT(s.mc_id)), (count(distinct(s.mc_id)) / b.barangay_population) * 3
        FROM m_consult_mc_services s, m_family_members f, m_family_address a, m_lib_barangay b
        WHERE f.patient_id = s.patient_id
        AND a.barangay_id = b.barangay_id
        AND a.family_id = f.family_id
        AND to_days(s.mc_timestamp) >= to_days('$start_date')
        AND to_days(s.mc_timestamp) <= to_days('$end_date')
        AND s.service_id = 'VITA'
        GROUP BY barangay_name
        ORDER BY barangay_name") or die(mysql_error());
        
      if ($query_pp)
        {
        while(list($brgy, $count, $percent) = mysql_fetch_array($query_pp))
          {
          $update_report = mysql_query("UPDATE m_consult_mc_consolidation_report 
            SET lactating_vitA = '$count', lactating_vitA_percent = '$percent' 
            WHERE barangay_name = '$brgy'") or die(mysql_error());
          }
        }
        
      // No. of women aged 15-49 who have been given iodized oil capsule
      
      }
      
    function display_mc_consolidation_report()
      {
      if (func_num_args()>0) 
        {
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];
        //print_r($arg_list);
        }
      
      switch($post_vars[quarter])
        {
        case "first":
          $post_vars[quarter] = '1st';
          break;
        case "second":
          $post_vars[quarter] = '2nd';
          break;        
        case "third":
          $post_vars[quarter] = '3rd';
          break;
        case "fourth":
          $post_vars[quarter] = '4th';
          break;  
        }  
      $query_consolidation_report = mysql_query("SELECT * FROM m_consult_mc_consolidation_report") 
        or die(mysql_error());

      if ($query_consolidation_report)
        {
        echo ("
        <br><b>QUARTERLY CONSOLIDATION TABLE</b>
        <br><b>Maternal Care</b><br>
        <br>QUARTER: <b>".$post_vars[quarter]."</b>
        <br>YEAR: <b>".$post_vars[year]."</b><br><br>
        
        <table width='600' cellspacing='0' cellpadding='2' style='border: 1px solid black' border='1'>
        <tr bgcolor='#FFCC33'>
          <td class='tinylight' rowspan='3' align='center' width='60'><b>NAME OF BHS's</b></td>
          <td class='tinylight' rowspan='3' align=center width='60'><b>TOTAL POP.</b></td>
          <td class='tinylight' colspan='6' align=center><b><i>PREGNANT WOMEN</i></b></td>
          <td class='tinylight' colspan='6' align=center><b><i>POSTPARTUM MOTHERS</i></b></td>
          <td class='tinylight' colspan='2' align=center><b><i>LACTATING</i></b></td>
          <td class='tinylight' colspan='2' align=center><b><i>WOMEN 15-49</i></b></td>
        </tr>
        <tr bgcolor='#FFCC33'>
          <td class='tinylight' colspan='2' align='center' width='60'>W/3 OR MORE PN VISIT</td>
          <td class='tinylight' colspan='2' align='center' width='60'>GIVEN TT2 PLUS</td>
          <td class='tinylight' colspan='2' align='center' width='60'>GIVEN COMPLETE IRON DOS.</td>
          <td class='tinylight' colspan='2' align='center' width='60'>W/ AT LEAST 1 PP VISIT</td>
          <td class='tinylight' colspan='2' align='center' width='60'>GIVEN COMPLETE IRON DOS.</td>
          <td class='tinylight' colspan='2' align='center' width='60'>INITIATED BREASTFEEDING</td>
          <td class='tinylight' colspan='2' align='center' width='60'>GIVEN VIT. A</td>
          <td class='tinylight' colspan='2' align='center' width='60'>GIVEN IODIZED OIL CAPSULE</td>
        </tr>
        <tr bgcolor='#FFCC33'>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
          <td class='tinylight' align='center'>NO.</td>
          <td class='tinylight' align='center'>%</td>
        </tr>
        ");
        
        while ($report = mysql_fetch_array($query_consolidation_report))
          {
          $bgcolor=($bgcolor=="#FFFF99"?"white":"#FFFF99");
          echo ("
          <tr bgcolor='$bgcolor'>
            <td class='tinylight' align='center'>".$report[barangay_name]."</td>
            <td class='tinylight' align='center'>".$report[barangay_population]."</td>
            <td class='tinylight' align='center'>".$report[pn_visits3plus]."</td>
            <td class='tinylight' align='center'>".$report[pn_visits3plus_percent]."</td>
            <td class='tinylight' align='center'>".$report[pn_tt2plus]."</td>
            <td class='tinylight' align='center'>".$report[pn_tt2plus_percent]."</td>
            <td class='tinylight' align='center'>".$report[pn_iron]."</td>
            <td class='tinylight' align='center'>".$report[pn_iron_percent]."</td>
            <td class='tinylight' align='center'>".$report[pp_visit1plus]."</td>
            <td class='tinylight' align='center'>".$report[pp_visit1plus_percent]."</td>
            <td class='tinylight' align='center'>".$report[pp_iron]."</td>
            <td class='tinylight' align='center'>".$report[pp_iron_percent]."</td>
            <td class='tinylight' align='center'>".$report[pp_breastfeeding]."</td>
            <td class='tinylight' align='center'>".$report[pp_breastfeeding_percent]."</td>
            <td class='tinylight' align='center'>".$report[lactating_vitA]."</td>
            <td class='tinylight' align='center'>".$report[lactating_vitA_percent]."</td>
            <td class='tinylight' align='center'>".$report[F_15to49_iodized_oil]."</td>
            <td class='tinylight' align='center'>".$report[F_15to49_iodized_oil_percent]."</td>
          </tr>
          ");
          }
        } 
      }
    
    function summary_inclusive_dates() 
      {
      if (func_num_args()>0) 
        {
        $arg_list = func_get_args();
        $menu_id = $arg_list[0];
        $post_vars = $arg_list[1];
        $get_vars = $arg_list[2];
        $validuser = $arg_list[3];
        $isadmin = $arg_list[4];
        //print_r($arg_list);
        }
      
      echo ("
      <table width='400'>
      <form action = '$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]&report_menu=$get_vars[report_menu]&report=CONSOLIDATION&module=$get_vars[module]' name='summary_inclusive_dates' method='post'>
      <tr valign='top'>
        <td><!--<input type='hidden' name='patient_id' value='$patient_id'/> -->
          <br><b>INCLUSIVE DATES FOR THIS CONSOLIDATION REPORT</b><br/><br/>
        </td>
      </tr>
      <tr valign='top'>
        <td><span class='boxtitle'>QUARTER</span><br>
          <select class='textbox' name='quarter'>
            <option value=''>Select Quarter</option>
            <option value='first'>First</option>
            <option value='second'>Second</option>
            <option value='third'>Third</option>
            <option value='fourth'>Fourth</option>
          </select><br/>
        </td>
      </tr>
      <tr valign='top'>
        <td><br><span class='boxtitle'>YEAR</span><br>
          <select name='year' class='textbox'>
            <option value=''>Select Year</option>
      ");
      for ($i=2004; $i<=2020; $i++) 
        {
        echo("<option value='$i'>$i</option>");
        }
      
      echo ("
          </select><br/>
        </td>
      </tr>
      <tr>
        <td>
      ");
        
      if ($_SESSION["priv_add"]) 
        {
        echo("<br><input type='submit' value = 'Generate Report' class='textbox' name='submitreport' style='border: 1px solid #000000'><br>");
        }
      echo("
        </td>
      </tr>
      </form>
      </table><br>
      ");
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    function get_attendant ($birthmode)
       {
       $sql = mysql_query("SELECT attendant_name FROM m_lib_mc_birth_attendant WHERE attendant_id = '$birthmode'");	
       list($attendant_name) = mysql_fetch_array($sql);
       return $attendant_name;
       }

    function get_fully_immunized_tt_date () {
        if (func_num_args() > 0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

        $sql = "select date_format(actual_vaccine_date, '%c/%e/%y')  from m_consult_mc_vaccine ".
               "where patient_id = '$patient_id' and vaccine_id='TT5'";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($fully_immunized_date) = mysql_fetch_array($result);
            	return $fully_immunized_date;
	    }
        }
    }        
	

    function get_tt_vaccines() {
    //
    // get vaccine list for given date and patient
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
            
        $sql = "select vaccine_id, date_format(actual_vaccine_date, '%c/%e/%y') vaccine_date ".
               "from m_consult_mc_vaccine ".
               "where patient_id = '$patient_id'";
        
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($vaccine_name, $vaccine_date) = mysql_fetch_array($result)) {
                    $vaccines .= $vaccine_name." - ".$vaccine_date.", ";
                }
                $vaccines = substr($vaccines, 0, strlen($vaccines)-2);
                return $vaccines;
            }
        }
    }    

    function get_risk_id_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

	$sql = "select visit_risk_id, date_format(risk_timestamp, '%c/%e/%y') risk_date from m_consult_mc_visit_risk ".
	       "where patient_id = '$patient_id' ";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($id, $date) = mysql_fetch_array($result)) {
                    $risk_id_date .= $id." - ".$date.", ";
		} 	      
                $risk_id_date = substr($risk_id_date, 0, strlen($risk_id_date)-2);
            	return $risk_id_date;
	    }
        }
    }

    function get_iron_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

	$sql = "select date_format(mc_timestamp, '%c/%e/%y')  from m_consult_mc_services ".
	       "where patient_id = '$patient_id' and service_id = 'IRON' ";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($date) = mysql_fetch_array($result))
                  {
                  $iron_date .= $date.", ";
                  }
            	$iron_date = substr($iron_date, 0, strlen($iron_date)-2);
            	return $iron_date;
	    }
        }
    }

    function get_vita_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

	$sql = "select date_format(mc_timestamp, '%c/%e/%y') from m_consult_mc_services ".
	       "where patient_id = '$patient_id' and service_id = 'VITA' ";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($date) = mysql_fetch_array($result))
                  {
                  $vita_date .= $date.", ";
                  }
                $vita_date = substr($vita_date, 0, strlen($vita_date)-2);
            	return $vita_date;
	    }
        }
    }

    function get_home_visit_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

	$sql = "select date_format(postpartum_date, '%c/%e/%y') from m_consult_mc_postpartum ".
	       "where patient_id = '$patient_id' and visit_type = 'HOME' ";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($visit_date) = mysql_fetch_array($result)) {
		    $home_visit_date .= $visit_date.", ";
		}
                $home_visit_date = substr($home_visit_date, 0, strlen($home_visit_date)-2);
            	return $home_visit_date;
	    }
        }
    }

    function get_clinic_visit_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $pp_wk4 = $arg_list[1];
            $pp_wk6 = $arg_list[2];
        }
        

	$sql = "select date_format(postpartum_date, '%c/%e/%y') from m_consult_mc_postpartum ".
	       "where patient_id = '$patient_id' and visit_type = 'CLINIC' and ".
	       "to_days(postpartum_date) >= to_days('$pp_wk4') and to_days(postpartum_date) <= to_days('$pp_wk6')";

        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while(list($visit_date) = mysql_fetch_array($result)) {
            	    $clinic_visit_date .= $visit_date.", ";
		}
                $clinic_visit_date = substr($clinic_visit_date, 0, strlen($clinic_visit_date)-2);
		return $clinic_visit_date;
	    }
        }
    }

    function get_breastfeeding_date() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }

	$sql = "select date_format(delivery_date, '%c/%e/%y'), breastfeeding_asap from m_patient_mc where patient_id = '$patient_id'";
	$result = mysql_query($sql) or die(mysql_error());

	if ($result = mysql_query($sql)) {
	    if (mysql_num_rows($result)) {
		while (list($delivery, $breastfeed) = mysql_fetch_array($result)) {
		    if ($delivery == '0/0/00')
		      {
		      $date = '';
		      }
		    elseif ($breastfeed == 'Y') 
		      {
		      $date = $delivery;
		      }
		}
		return $date;
	    }
	}
    }

    function get_trimester1_visit_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
	    $trimester1_date = $arg_list[1];
	    $patient_lmp = $arg_list[2];
        }

	$sql = "select date_format(prenatal_date, '%c/%e/%y') from m_consult_mc_prenatal ".
	       "where patient_id = '$patient_id' and ".
	       "prenatal_date >= '$patient_lmp' and prenatal_date <= '$trimester1_date' ";

	if ($result = mysql_query($sql)) {
	    if (mysql_num_rows($result)) {
		while(list($date) = mysql_fetch_array($result)) {
		    $trimester1_visit_dates .= $date.", "; 
		}
		$trimester1_visit_dates = substr($trimester1_visit_dates, 0, strlen($trimester1_visit_dates)-2);
		return $trimester1_visit_dates;
	    }
	}
    }

    function get_trimester2_visit_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
	    $trimester2_date = $arg_list[1];
	    $trimester1_date = $arg_list[2];
        }

	$sql = "select date_format(prenatal_date, '%c/%e/%y') from m_consult_mc_prenatal where patient_id = '$patient_id' and ".
	       "prenatal_date >= '$trimester1_date' and prenatal_date <= '$trimester2_date' ";

	if ($result = mysql_query($sql)) {
	    if (mysql_num_rows($result)) {
		while(list($date) = mysql_fetch_array($result)) {
		    $trimester2_visit_dates .= $date.", "; 
		}
		$trimester2_visit_dates = substr($trimester2_visit_dates, 0, strlen($trimester2_visit_dates)-2);
		return $trimester2_visit_dates;
	    }
	}
    }

    function get_trimester3_visit_dates() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
	    $trimester3_date = $arg_list[1];
	    $trimester2_date = $arg_list[2];
        }

	$sql = "select date_format(prenatal_date, '%c/%e/%y') from m_consult_mc_prenatal where patient_id = '$patient_id' and ".
	       "prenatal_date > '$trimester2_date' and prenatal_date < '$trimester3_date' ";

	if ($result = mysql_query($sql)) {
	    if (mysql_num_rows($result)) {
		while(list($date) = mysql_fetch_array($result)) {
		    $trimester3_visit_dates .= $date.", "; 
		}
		$trimester3_visit_dates = substr($trimester3_visit_dates, 0, strlen($trimester3_visit_dates)-2);
		return $trimester3_visit_dates;
	    }
	}
    }
// end of class
}
?>
