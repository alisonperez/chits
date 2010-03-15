<?
	class leprosy extends module {
		// Author: Herman Tolentino MD
		// CHITS Project 2004
		
		// LEPROSY CONTROL PROGRAM MODULE
		
		// Feel free to add additional comments anywhere.
		// Just add comment dates before the actual comment.
		
		// COMMENT DATE: Sep 25, '09
		// THESE ARE THE REQUIRED APIs/FUNCTIONS FOR EVERY MODULE
		// 1. init_deps()
		// 2. init_lang()
		// 3. init_stats()
		// 4. init_help()
		// 5. init_menu()
		// 6. init_sql()
		// 7. CONSTRUCTOR FUNCTION
		// 8. drop_tables()
		
		
		
		// Comment date: Sep 25, '09
		// The constructor function starts here
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function leprosy() {
			$this->author = "Jeffrey V. Tolentino";
			$this->version = "0.1-".date("Y-m-d");
			$this->module = "leprosy";
			$this->description = "CHITS Module - Leprosy Control Program";  
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Oct 21, '09, JVTolentino
		// This function is somehow needed by the healthcenter class, the reason 
		//    is unknown.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function _details_leprosy() {
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		function init_deps() {
			module::set_dep($this->module, "module");
			module::set_dep($this->module, "healthcenter");
			module::set_dep($this->module, "patient");                               
		}
		
		
		
		function init_lang() {
		}	
		
		
		
		function init_stats() {
		}
		
		
		
		function init_help() {
		}
		
		
		
		// Comment date: Sep 25, '09
		// The init_menu() function starts here
		// This function is used to include a link to the menu pane,
		// to the pane at the bottom of the menu pane, and so on...
		// (The menu pane is located at the left side of the site)
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function init_menu() {
			if (func_num_args()>0) {
				$arg_list = func_get_args();
			}
			
			print_r($arg_list);
			
			// set_menu parameters
			// set_menu([module name], [menu title - what is displayed], menu categories (top menu)], [script executed in class])
			//module::set_menu($this->module, "Dental Records", "PATIENTS", "_consult_dental");
			// set_detail parameters
			// set_detail([module description], [module version], [module author], [module name/id]
			module::set_detail($this->description, $this->version, $this->author, $this->module);
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
		// Comment date: Oct 7, 2009, JVTolentino
		// The init_sql() function starts here.
		// This function will initialize the tables for the Leprosy Module in Chits DB.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function init_sql() {
			if (func_num_args()>0) {
			$arg_list = func_get_args();
			}
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_diagnosis` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`patient_age` float NOT NULL,".
				"`date_of_diagnosis` date NOT NULL,".
				"`patient_case` char(8) COLLATE swe7_bin NOT NULL,".
				"`classification` char(4) COLLATE swe7_bin NOT NULL,".
				"`mode_of_detection` char(25) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_past_treatment` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`patient_id` float NOT NULL,".
				"`treatment_received` char(50) COLLATE swe7_bin NOT NULL,".
				"`duration_of_treatment` char(50) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_other_illness` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`patient_id` float NOT NULL,".
				"`tb` char(1) COLLATE swe7_bin NOT NULL,".
				"`severe_jaundice` char(1) COLLATE swe7_bin NOT NULL,".
				"`peptic_ulcer` char(1) COLLATE swe7_bin NOT NULL,".
				"`kidney_disease` char(1) COLLATE swe7_bin NOT NULL,".
				"`other_illness` char(50) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_contact_examination` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`contact_patient_id` float NOT NULL,".
				"`relationship` char(50) COLLATE swe7_bin NOT NULL,".
				"`year_of_birth` char(4) COLLATE swe7_bin NOT NULL,".
				"`sex` char(1) COLLATE swe7_bin NOT NULL,".
				"`examination_done` char(1) COLLATE swe7_bin NOT NULL,".
				"`results` char(1) COLLATE swe7_bin NOT NULL,".
				"`date_examined` date NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_skin_smear` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`skin_smear_done` char(1) COLLATE swe7_bin NOT NULL,".
				"`bacillary_index_reading` char(25) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".	
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_drug_collection_chart` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`date_for_the_supervised_dose` date NOT NULL,".
				"`treatment` char(3) COLLATE swe7_bin NOT NULL,".
				"`given_by` char(5) COLLATE swe7_bin NOT NULL,".
				"`remarks` char(25) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_voluntary_muscle_testing` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`key_movement` char(25) COLLATE swe7_bin NOT NULL,".
				"`upon_dx_left` char(1) COLLATE swe7_bin NOT NULL,".
				"`upon_dx_right` char(1) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_left` char(1) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_right` char(1) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_eye_evaluation` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`indicator` char(30) COLLATE swe7_bin NOT NULL,".
				"`upon_dx_right` char(10) COLLATE swe7_bin NOT NULL,".
				"`upon_dx_left` char(10) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_right` char(10) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_left` char(10) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_who_disability_grade` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
				"`consult_id` float NOT NULL,".
				"`patient_id` float NOT NULL,".
				"`who_disability` char(25) COLLATE swe7_bin NOT NULL,".
				"`upon_dx_right` char(1) COLLATE swe7_bin NOT NULL,".
				"`upon_dx_left` char(1) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_right` char(1) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_left` char(1) COLLATE swe7_bin NOT NULL,".
				"`date_last_updated` date NOT NULL,".
				"`user_id` float NOT NULL,".
				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");


			module::execsql("CREATE TABLE IF NOT EXISTS `m_leprosy_post_treatment` (".
				"`record_number` float NOT NULL AUTO_INCREMENT,".
  				"`consult_id` float NOT NULL,".
  				"`patient_id` float NOT NULL,".
				"`upon_dx_physician` char(30) COLLATE swe7_bin NOT NULL,".
  				"`upon_tc_physician` char(30) COLLATE swe7_bin NOT NULL,".
				"`upon_tc_date` date NOT NULL,".
  				"`patient_cured` char(20) COLLATE swe7_bin NOT NULL,".
				"`movement_of_patient` char(10) COLLATE swe7_bin NOT NULL, ".
  				"`date_last_updated` date NOT NULL,".
  				"`user_id` float NOT NULL,".
  				"PRIMARY KEY (`record_number`)".
				") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
		// Comment date: Oct 13, 2009, JVTolentino
		// The drop_tables() function starts here.
		// This function will be used to drop tables from CHITS DB.
		// This function will be executed if the user opts to delete
		//    the tables associated with this module.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function drop_tables() {
			module::execsql("DROP TABLE `m_leprosy_diagnosis`");
			module::execsql("DROP TABLE `m_leprosy_past_treatment`");
			module::execsql("DROP TABLE `m_leprosy_other_illness`");
			module::execsql("DROP TABLE `m_leprosy_contact_examination`");
			module::execsql("DROP TABLE `m_leprosy_skin_smear`");
			module::execsql("DROP TABLE `m_leprosy_drug_collection_chart`");
			module::execsql("DROP TABLE `m_leprosy_voluntary_muscle_testing`");
			module::execsql("DROP TABLE `m_leprosy_eye_evaluation`");
			module::execsql("DROP TABLE `m_leprosy_who_disability_grade`");
			module::execsql("DROP TABLE `m_leprosy_post_treatment`");
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		// Comment date: Jan 19, 2010, JVTolentino
		// The succeeding codes and functions will be used exclusively(??) for
		//    the 'CHITS - LEPROSY CONTROL PROGRAM MODULE'. These codes
		//    are open-source, so feel free to modify, enhance, and distribute
		//    as you wish.
		// Some codes, especially thosed used for the required functions were copied
		//    from the dental module and pasted here, thus, the comment dates on those
		// 	functions.
		// Start date: Jan 19, 2010, JVTolentino
		// End date: under construction
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 26, 2010, JVTolentino
		// This function will outline the NLCP Form 1 located at pp. 68-69 of the Manual of Procedures -
		// 	National Leprosy Control Program of the Department of Health.
		// The MoP was given by Dra. Lazatin of the Provincial Health Office after an interview
		// 	regarding the Leprosy Program of DOH.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function show_NLCPForm1() {
			$this->create_diagnosis_table();
			print "<br>";
			
			$this->create_history_table();
			print "<br>";
			
			$this->create_contact_examination_table();
			print "<br>";
			
			$this->create_skin_smear_table();
			print "<br>";
			
			$this->create_drug_collection_chart_table();
			print "<br>";
			
			$this->create_voluntary_muscle_testing_table();
			print "<br>";
			
			$this->create_eye_evaluation_table();
			print "<br>";
			
			$this->create_who_disability_grade_table();
			print "<br>";

			$this->create_post_treatment_table();
			print "<br>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 27, 2010, JVTolentino
		// This function will printout on the page a table of the patient's diagnosis.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function create_diagnosis_table() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
				
			$query = "SELECT * FROM m_leprosy_diagnosis WHERE ".
				"consult_id = $loc_consult_id AND ".
				"patient_id = $loc_patient_id ";
			$result = mysql_query($query)
				or die ("Couldn't execute query.");
				
			if($row = mysql_fetch_assoc($result)) {
				list($year, $month, $day) = explode("-", $row['date_of_diagnosis']);
				$loc_date_of_diagnosis = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				
				$loc_patient_case = $row['patient_case'];
				$loc_classification = $row['classification'];
				$loc_mode_of_detection = $row['mode_of_detection'];
				
				list($year, $month, $day) = explode("-", $row['date_last_updated']);
				$loc_date_last_updated = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				$loc_userid = $row['user_id'];
				$loc_updated = 'Y';
			}
			else {
				$loc_date_of_diagnosis = date("m/d/Y");
				$loc_patient_case = "";
				$loc_classification = "";
				$loc_mode_of_detection = "";
				$loc_date_last_updated = date("m/d/Y");
				$loc_userid = "";
				$loc_updated = 'N';
			}
				
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					if($loc_updated == 'Y') {
						print "<th align='left' bgcolor=#CC9900#>Diagnosis</th>";
						print "<th align='left' bgcolor=#CC9900#>".
							"<font color='red'>Patient was diagnosed on $loc_date_of_diagnosis.".
							"<br>This section has been updated last $loc_date_last_updated.</font></th>";
					}
					else {
						print "<th align='left' bgcolor=#CC9900#>Diagnosis</th>";
						print "<th align='left' bgcolor=#CC9900#>".
							"<font color='red'>This section has never been updated before.".
							"<br>To update, click the 'Save Diagnosis' button.</font></th>";
					}
				print "</tr>";
					
				print "<tr>";
					print "<td align='center' width='150'>";
						if($_POST['date_of_diagnosis'] == '') {
							print "<input type='text' name='date_of_diagnosis' ".
								"readonly='true' size=10 value='$loc_date_of_diagnosis'>".
								"<a href=\"javascript:show_calendar4('document.form_leprosy.date_of_diagnosis', ".
								"document.form_leprosy.date_of_diagnosis.value);\">".
								"<img src='../images/cal.gif' width='16' height='16' border='0' ".
								"alt='Click Here to Pick up the Date'></a></input>";
						} 
						else {
							print "<input type='text' name='date_of_diagnosis' ".
								"readonly='true' size=10 value='".$_POST['date_of_diagnosis'].
								"'> <a href=\"javascript:show_calendar4('document.form_leprosy.date_of_diagnosis', ".
								"document.form_leprosy.date_of_diagnosis.value);\">".
								"<img src='../images/cal.gif' width='16' height='16' border='0' ".
								"alt='Click Here to Pick up the Date'></a></input>";
						}
					print "</td>";
					print "<td> Click the image on the left to change the date of diagnosis.</td>";
				print "</tr>";
				
				print "<tr>";
				print "<td width='150'></td>";
				switch($loc_patient_case) {
					case 'New Case':
						print "<td>".
							"<input type='radio' name='patient_case' value='New Case' checked>".
							"<font color='red'>New Case <br></font>".
							"<input type='radio' name='patient_case' value='Old Case'>".
							"Old Case</td>";
						break;
					case 'Old Case':
						print "<td>".
							"<input type='radio' name='patient_case' value='New Case'>".
							"New Case <br>".
							"<input type='radio' name='patient_case' value='Old Case' checked>".
							"<font color='red'>Old Case</font></td>";	
						break;
					default:
						print "<td>".
							"<input type='radio' name='patient_case' value='New Case'>New Case <br>".
							"<input type='radio' name='patient_case' value='Old Case'>Old Case</td>";	
						break;
				}
				print "</tr>";
				
				print "<tr>";
				print "<td width='150' align='center'><i><b>CLASSIFICATION</i></b></td>";
				switch($loc_classification) {
					case 'SLPB':
						print "<td>".
							"<input type='radio' name='classification' value='SLPB' checked>".
							"<font color='red'>SLPB</font><br>".
							"<input type='radio' name='classification' value='PB'>".
							"PB<br>".
							"<input type='radio' name='classification' value='MB'>".
							"MB</td>";
							break;
					case 'PB':
						print "<td>".
							"<input type='radio' name='classification' value='SLPB'>".
							"SLPB <br>".
							"<input type='radio' name='classification' value='PB' checked>".
							"<font color='red'>PB</font><br>".
							"<input type='radio' name='classification' value='MB'>".
							"MB</td>";
							break;
					case 'MB':
						print "<td>".
							"<input type='radio' name='classification' value='SLPB'>".
							"SLPB<br>".
							"<input type='radio' name='classification' value='PB'>".
							"PB<br>".
							"<input type='radio' name='classification' value='MB' checked>".
							"<font color='red'>MB</font></td>";
							break;
					default:
						print "<td>".
							"<input type='radio' name='classification' value='SLPB'>SLPB<br>".
							"<input type='radio' name='classification' value='PB'>PB<br>".
							"<input type='radio' name='classification' value='MB'>MB</td>";
						break;
				}
				print "</tr>";

				print "<tr>";
				print "<td width='150' align='center'><i><b>MODE<br>OF<br>DETECTION</b></i></td>";
				switch($loc_mode_of_detection) {
					case 'Voluntary':
						print "<td>".
							"<input type='radio' name='mode_of_detection' value='Voluntary' checked>".
							"<font color='red'>Voluntary</font><br>".
							"<input type='radio' name='mode_of_detection' value='Special Projects'>".
							"Special Projects<br>".
							"<input type='radio' name='mode_of_detection' value='Contact Examination'>".
							"Contact Examination</td>";
						break;
					case 'Special Projects':
						print "<td>".
						 	"<input type='radio' name='mode_of_detection' value='Voluntary'>Voluntary<br>".
							"<input type='radio' name='mode_of_detection' value='Special Projects' checked>".
							"<font color='red'>Special Projects</font><br>".
							"<input type='radio' name='mode_of_detection' value='Contact Examination'>".
							"Contact Examination</td>";
						break;
					case 'Contact Examination':
						print "<td>".
							"<input type='radio' name='mode_of_detection' value='Voluntary'>Voluntary<br>".
							"<input type='radio' name='mode_of_detection' value='Special Projects'>".
							"Special Projects<br>".
							"<input type='radio' name='mode_of_detection' value='Contact Examination' checked>".
							"<font color='red'>Contact Examination</font></td>";
						break;
					default:
						print "<td>".
							"<input type='radio' name='mode_of_detection' value='Voluntary'>Voluntary<br>".
							"<input type='radio' name='mode_of_detection' value='Special Projects'>".
							"Special Projects<br>".
							"<input type='radio' name='mode_of_detection' value='Contact Examination'>".
							"Contact Examination</td>";
						break;
				}
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=2 align='center'><input type='submit' name='submit_button' ".
						"value='Save Diagnosis'></input></td>";
				print "</tr>";
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 27, 2010, JVTolentino
		// This function will be used to add or update a record in [m_leprosy_diagnosis].
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function diagnosis_record() {
			$loc_patient_id = $_POST['h_patient_id'];
			$loc_consult_id = $_POST['h_consult_id'];
			$loc_patient_age = healthcenter::get_patient_age($loc_consult_id);
			$loc_userid = $_POST['h_userid'];
			list($month, $day, $year) = explode("/", $_POST['date_of_diagnosis']);
			$loc_date_of_diagnosis = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_patient_case = $_POST['patient_case'];
			$loc_classification = $_POST['classification'];
			$loc_mode_of_detection = $_POST['mode_of_detection'];
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			
			$query = "SELECT consult_id, patient_id FROM m_leprosy_diagnosis WHERE ".
				"consult_id = $loc_consult_id AND ".
				"patient_id = $loc_patient_id ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(mysql_num_rows($result)) {
				$query = "UPDATE m_leprosy_diagnosis SET ".
					"date_of_diagnosis = '$loc_date_of_diagnosis', ".
					"patient_case = '$loc_patient_case', ".
					"classification = '$loc_classification', ".
					"mode_of_detection = '$loc_mode_of_detection', ".
					"date_last_updated = '$loc_current_date', ".
					"user_id = $loc_userid ".
					"WHERE consult_id = $loc_consult_id AND ".
					"patient_id = $loc_patient_id ";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			}
			else {
				$query = "INSERT INTO m_leprosy_diagnosis ".
					"(consult_id, patient_id, patient_age, date_of_diagnosis, patient_case, classification, ".
					"mode_of_detection, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, $loc_patient_age, '$loc_date_of_diagnosis', '$loc_patient_case', ".
					"'$loc_classification', '$loc_mode_of_detection', '$loc_current_date', $loc_userid) ";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			}
			
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 27, 2010, JVTolentino
		// This function will printout on the page a table of the patient's history.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function create_history_table() {
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT * FROM m_leprosy_past_treatment WHERE ".
				"patient_id = $loc_patient_id ORDER BY ".
				"date_last_updated DESC ";
			$result = mysql_query($query)
				or die ("Couldn't execute query. ".mysql_error());
			
			if($row = mysql_fetch_assoc($result)) {
				$loc_updated = 'Y';
				list($year, $month, $day) = explode("-", $row['date_last_updated']);
				$loc_date_last_updated = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
			}
			else {
				$loc_updated = 'N';
			}
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<th align='left' colspan=2 bgcolor=#CC9900#>History</th>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='left'><b>Leprosy:</b></td>";
					if($loc_updated == 'Y') {
						print "<td><font color='red'>This section has been updated last ".
							"$loc_date_last_updated.</font></td>";
					}
					else {
						print "<td><font color='red'>This section has never been updated.</font></td>";
					}
				print "</tr>";
				
				print "<tr>";
					print "<td align='center'><i>Treatment Received</i></td>";
					print "<td align='center'><i>Duration of Treatment</i></td>";
				print "</tr>";
				
				$query = "SELECT * FROM m_leprosy_past_treatment WHERE ".
					"patient_id = $loc_patient_id ORDER BY ".
					"date_last_updated DESC ";
				$result = mysql_query($query)
					or die ("Couldn't execute query. ".mysql_error());
					
				while($row = mysql_fetch_array($result)) {
					print "<tr>";
						extract($row);
						print "<td align='center'><font color='red'>$treatment_received</font></td>";
						print "<td align='center'><font color='red'>$duration_of_treatment</font></td>";
					print "</tr>";
				}
				
				print "<tr>";
					print "<td align='center'>";
						print "<select name='treatment'>";
							print "<option value='Dapsone Monotherapy'>Dapsone Monotherapy</option>";
							print "<option value='WHO - Multi Drug Therapy'>WHO - Multi Drug Therapy</option>";
							print "<option value='ROM Therapy'>ROM Therapy</option>";
							print "<option value='Other MDT Regimen'>Other MDT Regimen</option>";
						print "</select>";
					print "</td>";
					print "<td align='center'><input type='text' name='duration_of_treatment' size=40>".
						"</input></td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=2 align='center'><input type='submit' name='submit_button' ".
						"value='Add Treatment Record'></input></td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=2 align='center'>".
						"This button will DELETE from the records the last added data, and ".
						"NOT the last treatment record of the patient.<br>".
						"<input type='submit' name='submit_button' ".
						"value='Delete Last Added Treatment Record'></input></td>";
				print "</tr>";
				
				
				$query = "SELECT * FROM m_leprosy_other_illness WHERE ".
					"patient_id = $loc_patient_id ORDER BY ".
					"date_last_updated DESC ";
				$result = mysql_query($query)
					or die ("Couldn't execute query. ".mysql_error());
				
				if($row = mysql_fetch_assoc($result)) {
					$loc_updated = 'Y';
					list($year, $month, $day) = explode("-", $row['date_last_updated']);
					$loc_date_last_updated = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
					
					$loc_tb = $row['tb'];
					$loc_severe_jaundice = $row['severe_jaundice'];
					$loc_peptic_ulcer = $row['peptic_ulcer'];
					$loc_kidney_disease = $row['kidney_disease'];
					$loc_other_illness = $row['other_illness'];
				}
				else {
					$loc_updated = 'N';
				}
				
				print "<tr>";
					print "<td align='left'><b>Other Illnesses:</b></td>";
					if($loc_updated == 'Y') {
						print "<td><font color='red'>This section has been updated last ".
							"$loc_date_last_updated.</font></td>";
					}
					else {
						print "<td><font color='red'>This section has never been updated.</font></td>";
					}
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=2>";
						if($loc_tb == 'Y') {
							print "<input type='checkbox' name='tb' ".
								"value='Y' checked><font color='red'>TB</font></input>&nbsp;";
						}
						else {
							print "<input type='checkbox' name='tb' value='Y'>TB</input>&nbsp;";
						}
						
						if($loc_severe_jaundice == 'Y') {
							print "<input type='checkbox' name='severe_jaundice' ".
								"value='Y' checked><font color='red'>Severe Jaundice</font></input>&nbsp;";
						}
						else {
							print "<input type='checkbox' name='severe_jaundice' ".
								"value='Y'>Severe Jaundice</input>&nbsp;";
						}
						
						if($loc_peptic_ulcer == 'Y') {
							print "<input type='checkbox' name='peptic_ulcer' ".
								"value='Y' checked><font color='red'>Peptic Ulcer</font></input>&nbsp;";
						}
						else {
							print "<input type='checkbox' name='peptic_ulcer' ".
								"value='Y'>Peptic Ulcer</input>&nbsp;";
						}
						
						if($loc_kidney_disease == 'Y') {
							print "<input type='checkbox' name='kidney_disease' ".
								"value='Y' checked><font color='red'>Kidney Disease</font></input>&nbsp;";
						}
						else {
							print "<input type='checkbox' name='kidney_disease' ".
								"value='Y'>Kidney Disease</input>&nbsp;";
						}
						
						print "<br>Input other illnesses on the box provided below:<br>".
							"<input type='text' name='other_illness' value='$loc_other_illness' size=50>".
							"</input>";
					print "</td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=2 align='center'><input type='submit' name='submit_button' ".
						"value='Save Other Illnesses'></input></td>";
				print "</tr>";
			print "</table>";
			
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 28, 2010, JVTolentino
		// This function will be used to add a record in [m_leprosy_past_treatment]
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function past_treatment_record() {
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			$loc_treatment = $_POST['treatment'];
			$loc_duration_of_treatment = $_POST['duration_of_treatment'];
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_POST['h_userid'];
			
			if($loc_duration_of_treatment != '') {
				$query = "INSERT INTO m_leprosy_past_treatment ".
					"(patient_id, treatment_received, duration_of_treatment, date_last_updated, user_id) ".
					"VALUES($loc_patient_id, '$loc_treatment', '$loc_duration_of_treatment', ".
					"'$loc_current_date', $loc_userid) ";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 28, 2010, JVTolentino
		// This function will be used to delete a record in [m_leprosy_past_treatment]
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function delete_treatment_record() {
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT record_number FROM m_leprosy_past_treatment WHERE ".
				"patient_id = $loc_patient_id ORDER BY record_number DESC ";
			$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			
			if($row = mysql_fetch_assoc($result)) {
				$loc_record_number = $row['record_number'];
				
				$query = "DELETE FROM m_leprosy_past_treatment WHERE ".
					"record_number = $loc_record_number ";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 29, 2010, JVTolentino
		// This function will add/modify a record in [m_leprosy_other_illness].
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function other_illness_record() {
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			if($_POST['tb'] == 'Y') {
				$loc_tb = 'Y';
			} else {
				$loc_tb = 'N';
			}
			if($_POST['severe_jaundice'] == 'Y') {
				$loc_severe_jaundice = 'Y';
			} else {
				$loc_severe_jaundice = 'N';
			}
			if($_POST['peptic_ulcer'] == 'Y') {
				$loc_peptic_ulcer = 'Y';
			} else {
				$loc_peptic_ulcer = 'N';
			}
			if($_POST['kidney_disease'] == 'Y') {
				$loc_kidney_disease = 'Y';
			} else {
				$loc_kidney_disease = 'N';
			}
			if($_POST['other_illness'] == '') {
				$loc_other_illness = ' ';
			} else {
				$loc_other_illness = $_POST['other_illness'];
			}
			
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_POST['h_userid'];
			
			$query = "SELECT * FROM m_leprosy_other_illness WHERE ".
				"patient_id = $loc_patient_id ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
					
			if(mysql_num_rows($result)) {
				$query = "UPDATE m_leprosy_other_illness SET ".
					"tb = '$loc_tb', ".
					"severe_jaundice = '$loc_severe_jaundice', ".
					"peptic_ulcer = '$loc_peptic_ulcer', ".
					"kidney_disease = '$loc_kidney_disease', ".
					"other_illness = '$loc_other_illness', ".
					"date_last_updated = '$loc_current_date', ".
					"user_id = $loc_userid ".
					"WHERE patient_id = $loc_patient_id ";
			} else {
				$query = "INSERT INTO m_leprosy_other_illness ".
					"(patient_id, tb, severe_jaundice, peptic_ulcer, kidney_disease, ".
					"other_illness, date_last_updated, user_id) ".
					"VALUES($loc_patient_id, '$loc_tb', '$loc_severe_jaundice', ".
					"'$loc_peptic_ulcer', '$loc_kidney_disease', '$loc_other_illness', ".
					"'$loc_current_date', $loc_userid)";
			}
			
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 29, 2010, JVTolentino
		// This function will printout on the page a table of the patient's contact examination.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function create_contact_examination_table() {
			$this->populate_contact_examination();
			
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			$loc_family_id = $this->get_family_id();
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<th colspan = 8 align='left' bgcolor=#CC9900#>Contact Examination</th>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='center'><b>Patient<br>ID</b></td>";
					print "<td align='center'><b>Name of Household</b></td>";
					print "<td align='center'><b>Relationship</b></td>";
					print "<td align='center'><b>Year<br>of<br>Birth</b></td>";
					print "<td align='center'><b>Sex</b></td>";
					print "<td align='center'><b>Examination<br>Done<br>(Y / N)</b></td>";
					print "<td align='center'><b>Results<br>(+ / -)</b></td>";
					print "<td align='center'><b>Date<br>Examined</b></td>";
				print "</tr>";
				
				$query = "SELECT m_leprosy_contact_examination.*, ".
					"m_patient.patient_lastname, m_patient.patient_firstname, m_patient.patient_middle ".
					"FROM m_leprosy_contact_examination INNER JOIN ".
					"m_patient ON m_leprosy_contact_examination.contact_patient_id = ".
					"m_patient.patient_id WHERE ".
					"m_leprosy_contact_examination.consult_id = $loc_consult_id ";
				$result = mysql_query($query) 
					or die("Couldn't execute query. ".mysql_error());
				
				while($row = mysql_fetch_array($result)) {
					extract($row);
					print "<tr>";
						print "<td align='center'>$contact_patient_id</td>";
						print "<td align='center'>$patient_lastname, $patient_firstname $patient_middle</td>";
						print "<td align='center'>$relationship</td>";
						print "<td align='center'>$year_of_birth</td>";
						print "<td align='center'>$sex</td>";
						print "<td align='center'>$examination_done</td>";
						print "<td align='center'>$results</td>";
						list($year, $month, $day) = explode("-", $row['date_examined']);
						$loc_date_examined = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
						print "<td align='center'>$loc_date_examined</td>";
					print "</tr>";
				}
				
				$query = "SELECT * FROM m_leprosy_contact_examination WHERE ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query) 
					or die("Couldn't execute query. ".mysql_error());
				
				print "<tr>";
					print "<td colspan=8><b>Update Contact Examination</b></td>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='center' colspan=2>Patient ID<br>";
						print "<select name='contact_patient_id'>";
							while($row = mysql_fetch_array($result)) {
								extract($row);
								print "<option value='$contact_patient_id'>$contact_patient_id</option>";
							}
						print "</select>";
					print "</td>";
					
					print "<td align='center' colspan=4>Relationship<br>".
						"<input type='text' name='relationship'></input></td>";
					
					print "<td align='center' colspan=2>Examination Done?<br>";
						print "<select name='examination_done'>";
							print "<option value='N'>N</option>";
							print "<option value='Y'>Y</option>";
						print "</select>";
					print "</td>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='center' colspan=2>Results<br>";
						print "<select name='results'>";
							print "<option value='-'>-</option>";
							print "<option value='+'>+</option>";
						print "</select>";
					print "</td>";
					
					list($month, $day, $year) = explode("/", date("m/d/Y"));
					$loc_current_date = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
					
					print "<td align='center' colspan=2>Date Examined<br>";
						if($_POST['date_examined'] == '') {
							print "<input type='text' name='date_examined' ".
								"readonly='true' size=10 value='$loc_current_date'".
								"<a href=\"javascript:show_calendar4('document.form_leprosy.date_examined', ".
								"document.form_leprosy.date_examined.value);\">".
								"<img src='../images/cal.gif' width='16' height='16' border='0' ".
								"alt='Click Here to Pick up the Date'></a></input>";
						} 
						else {
							print "<input type='text' name='date_examined' ".
								"readonly='true' size=10 value='".$_POST['date_examined'].
								"'> <a href=\"javascript:show_calendar4('document.form_leprosy.date_examined', ".
								"document.form_leprosy.date_examined.value);\">".
								"<img src='../images/cal.gif' width='16' height='16' border='0' ".
								"alt='Click Here to Pick up the Date'></a></input>";
						}
					print "</td>";
					
					print "<td colspan=4 align='center'><input type='submit' name='submit_button' ".
						"value='Save Contact Examination'></input></td>";
				print "</tr>";
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 02, 2010, JVTolentino
		// This function will be used to update a record in m_leprosy_contact_examination.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function contact_examination_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_contact_patient_id = $_POST['contact_patient_id'];
			
			$loc_relationship = $_POST['relationship'];
			$loc_examination_done = $_POST['examination_done'];
			$loc_results = $_POST['results'];
			
			list($month, $day, $year) = explode("/", $_POST['date_examined']);
			$loc_date_examined = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			
			$loc_userid = $_SESSION['userid'];
			
			$query = "UPDATE m_leprosy_contact_examination SET ".
				"relationship = '$loc_relationship', ".
				"examination_done = '$loc_examination_done', ".
				"results = '$loc_results', ".
				"date_examined = '$loc_date_examined', ".
				"date_last_updated = '$loc_current_date', ".
				"user_id = $loc_userid ".
				"WHERE consult_id = $loc_consult_id AND ".
				"contact_patient_id = $loc_contact_patient_id ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 29, 2010, JVTolentino
		// This function will be used to retrieve the family_id of a patient using his/her patient_id.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function get_family_id() {
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT family_id FROM m_family_members WHERE ".
				"patient_id = $loc_patient_id ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if($row = mysql_fetch_assoc($result)) {
				return $row['family_id'];
			} else {
				return '';
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 2, 2010, JVTolentino
		// This function will be used to populate m_leprosy_contact_examination with the patient's
		//		household members, based on his/her family folder.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function populate_contact_examination() {
			$loc_family_id = $this->get_family_id();
			
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			$query_family = "SELECT m_family_members.patient_id, m_patient.patient_dob, ".
				"m_patient.patient_gender FROM m_family_members INNER JOIN m_patient ".
				"ON m_family_members.patient_id = m_patient.patient_id WHERE ".
				"m_family_members.family_id = $loc_family_id AND ".
				"m_family_members.patient_id <> $loc_patient_id ";
			$result_family = mysql_query($query_family)
				or die("Couldn't query family execute query. ".mysql_error());
				
			while($row = mysql_fetch_array($result_family)) {
				extract($row);
				$loc_contact_patient_id = $row['patient_id'];
				list($year, $month, $day) = explode("-", $row['patient_dob']);
				$loc_sex = $row['patient_gender'];
				
				$query = "SELECT * FROM m_leprosy_contact_examination WHERE ".
					"patient_id = $loc_patient_id AND ".
					"consult_id = $loc_consult_id AND ".
					"contact_patient_id = $loc_contact_patient_id ";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
				
				if(mysql_num_rows($result)) {
					// no need to update
				} else {
					$query = "INSERT INTO m_leprosy_contact_examination ".
						"(consult_id, patient_id, contact_patient_id, relationship, year_of_birth, sex, ".
						"examination_done, results, date_examined, date_last_updated, user_id) ".
						"VALUES($loc_consult_id, $loc_patient_id, $loc_contact_patient_id, 'n/a', '$year', ".
						"'$loc_sex', 'N', '-', '0000-00-00', '$loc_current_date', $loc_userid)";
					$result = mysql_query($query) 
						or die("Couldn't execute query. ".mysql_error());
				}
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 03, 2010, JVTolentino
		// This function will be used to create the 'Skin Smear' table.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function create_skin_smear_table() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT * FROM m_leprosy_skin_smear WHERE ".
				"consult_id = $loc_consult_id ";
			$result = mysql_query($query) 
				or die("Couldn't execute query. ".mysql_error());
			
			if($row = mysql_fetch_assoc($result)) {
				$loc_skin_smear_done = $row['skin_smear_done'];
				$loc_bacillary_index_reading = $row['bacillary_index_reading'];
				list($year, $month, $day) = explode("-", $row['date_last_updated']);
				$loc_date_last_updated = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				$loc_updated = 'Y';
			}
			else {
				$loc_skin_smear_done = '';
				$loc_bacillary_index_reading = '';
				$loc_updated = 'N';
			}
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					if($loc_updated == 'Y') {
						print "<th align='left' bgcolor=#CC9900#>Skin Smear</th>";
						print "<th align='left' bgcolor=#CC9900#><font color='red'>".
							"This section has been updated last $loc_date_last_updated.</font></th>";
					}
					else {
						print "<th align='left' bgcolor=#CC9900#>Skin Smear</th>";
						print "<th align='left' bgcolor=#CC9900#>".
							"<font color='red'>This section has never been updated before.</font></th>";
					}
				print "</tr>";
				
				print "<tr>";
					switch($loc_skin_smear_done) {
						case 'Y':
							print "<td>".
								"<font color='red'>".
								"<input type='radio' name='skin_smear_done' value='Y' checked>Done</input>".
								"</font>".
								"<input type='radio' name='skin_smear_done' value='N'>Not Done</input>".
								"</td>";
							break;
						case 'N':
							print "<td>".
								"<input type='radio' name='skin_smear_done' value='Y'>Done</input>".
								"<font color='red'>".
								"<input type='radio' name='skin_smear_done' value='N' checked>Not Done</input>".
								"</font></td>";
							break;
						default:
							print "<td>".
								"<input type='radio' name='skin_smear_done' value='Y'>Done</input>".
								"<input type='radio' name='skin_smear_done' value='N'>Not Done</input>".
								"</td>";
					}
					
					if($loc_updated == 'Y') {
						print "<td>Bacillary Index (BI) Reading: ";
						print "<select name='bacillary_index_reading'>";
						switch($loc_bacillary_index_reading) {
							case '0':
								print "<option value='0' selected>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";
								break;
							case '1':
								print "<option value='0'>0</option>";
								print "<option value='1' selected>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";

								break;
							case '2':
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2' selected>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";
								break;
							case '3':
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3' selected>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";
								break;
							case '4':
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4' selected>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";
								break;
							case '5':
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5' selected>5</option>";
								print "<option value='6'>6</option>";
								break;
							case '6':
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6' selected>6</option>";
								break;
							default:
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";
								break;
						}
						print "</select>";
					}
					else {
						print "<td>Bacilliary Index (BI) Reading: ";
							print "<select name='bacillary_index_reading'>";
								print "<option value='0'>0</option>";
								print "<option value='1'>1</option";
								print "<option value='2'>2</option>";
								print "<option value='3'>3</option>";
								print "<option value='4'>4</option";
								print "<option value='5'>5</option>";
								print "<option value='6'>6</option>";
							print "</select></td>";
					}
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=2 align='center'><input type='submit' name='submit_button' ".
						"value='Save Skin Smear'></input></td>";
				print "</tr>";
				
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, 2010, JVTolentino
		// This function will add/update a record in [m_leprosy_skin_smear].
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function skin_smear_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$loc_skin_smear_done = $_POST['skin_smear_done'];
			$loc_bacillary_index_reading = $_POST['bacillary_index_reading'];
			
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			
			$loc_userid =  $_SESSION['userid'];
			
			$query = "SELECT consult_id FROM m_leprosy_skin_smear WHERE ".
				"consult_id = $loc_consult_id ";
			$result = mysql_query($query) 
				or die("Couldn't execute query. ".mysql_error());
			
			if(mysql_num_rows($result)) {
				$query = "UPDATE m_leprosy_skin_smear SET ".
					"skin_smear_done = '$loc_skin_smear_done', ".
					"bacillary_index_reading = '$loc_bacillary_index_reading', ".
					"date_last_updated = '$loc_current_date', ".
					"user_id = $loc_userid ".
					"WHERE consult_id = $loc_consult_id ";
			}
			else {
				$query = "INSERT INTO m_leprosy_skin_smear ".
					"(consult_id, patient_id, skin_smear_done, bacillary_index_reading, ".
					"date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, '$loc_skin_smear_done', ".
					"'$loc_bacillary_index_reading', '$loc_date_last_updated', $loc_userid)";
			}
			$result = mysql_query($query) 
				or die("Couldn't execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, 2010, JVTolentino
		// This function will create the drug collection chart on the page.
		function create_drug_collection_chart_table() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT * FROM m_leprosy_drug_collection_chart WHERE ".
				"consult_id = $loc_consult_id ";
			$result = mysql_query($query)
				or die ("Couldn't execute query. ".mysql_error());
			
			if($row = mysql_fetch_assoc($result)) {
				list($year, $month, $day) = explode("-", $row['date_last_updated']);
				$loc_date_last_updated = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				
				$loc_date_for_the_supervised_dose = $row['date_for_the_supervised_dose'];
				$loc_treatment = $row['treatment'];
				$loc_given_by = $row['given_by'];
				$loc_remarks = $row['remarks'];
				
				$loc_updated = 'Y';
			}
			else {
				$loc_date_for_the_supervised_dose = '';
				$loc_treatment = '';
				$loc_given_by = '';
				$loc_remarks = '';
				$loc_updated = 'N';
			}
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					if($loc_updated == 'Y') {
						print "<th align='left' bgcolor=#CC9900# colspan=2>Drug Collection Chart</th>";
						print "<th align='left' bgcolor=#CC9900# colspan=3>".
							"<font color='red'>This section has been updated last $loc_date_last_updated. ".
							"</font></th>";
					}
					else {
						print "<th align='left' bgcolor=#CC9900# colspan=2>Drug Collection Chart</th>";
						print "<th align='left' bgcolor=#CC9900# colspan=3>".
							"<font color='red'>This section has never been updated before.</font></th>";
					}
				print "</tr>";
				
				print "<tr>";
					print "<td align='center'><b>#</b></td>";
					print "<td align='center'><b>Date for the Supervised Dose<b></td>";
					print "<td align='center'><b>Treatment<b></td>";
					print "<td align='center'><b>Given By<b></td>";
					print "<td align='center'><b>Remarks<b></td>";
				print "</tr>";
				
				$query = "SELECT * FROM m_leprosy_drug_collection_chart WHERE ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query)
					or die ("Couldn't execute query. ".mysql_error());
				
				$ctr = 1;
				while($row = mysql_fetch_array($result)) {
					extract($row);
					print "<tr>";
						print "<td align='center'>$ctr</td>";
						list($year, $month, $day) = explode("-", $date_for_the_supervised_dose);
						$date_for_the_supervised_dose = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
						print "<td align='center'>$date_for_the_supervised_dose</td>";
						print "<td align='center'>$treatment</td>";
						print "<td align='center'>$given_by</td>";
						print "<td align='center'>$remarks</td>";
						$ctr += 1;
					print "</tr>";
				}
				
				print "<tr>";
					print "<td colspan=5><b>Update Drug Collection Chart:</b></td>";
				print "</tr>";
				
				print "</tr>";
					print "<td align='center'>$ctr</td>";
					
					list($month, $day, $year) = explode("/", date("m/d/Y"));
					$loc_current_date = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
					
					print "<td align='center'>";
						if($_POST['date_for_the_supervised_dose'] == '') {
							print "<input type='text' name='date_for_the_supervised_dose' ".
								"readonly='true' size=10 value='$loc_current_date'".
								"<a href=\"javascript:show_calendar4('document.form_leprosy.date_for_the_supervised_dose', ".
								"document.form_leprosy.date_for_the_supervised_dose.value);\">".
								"<img src='../images/cal.gif' width='16' height='16' border='0' ".
								"alt='Click Here to Pick up the Date'></a></input>";
						} 
						else {
							print "<input type='text' name='date_for_the_supervised_dose' ".
								"readonly='true' size=10 value='".$_POST['date_for_the_supervised_dose'].
								"'> <a href=\"javascript:show_calendar4('document.form_leprosy.date_for_the_supervised_dose', ".
								"document.form_leprosy.date_for_the_supervised_dose.value);\">".
								"<img src='../images/cal.gif' width='16' height='16' border='0' ".
								"alt='Click Here to Pick up the Date'></a></input>";
						}
					print "</td>";
					
					print "<td align='center'>".
						"<select name='treatment'>".
							"<option value='ROM'>ROM</option>".
							"<option value='PB'>PB</option>".
							"<option value='MB'>MB</option>".
						"</select></td>";
					
					print "<td align='center'>".
						"<input type='text' name='given_by' size=5></input>".
						"</td>";
					
					print "<td align='center'>".
						"<input type='text' name='remarks' size=25></input>".
						"</td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=5 align='center'><input type='submit' name='submit_button' ".
						"value='Add To Drug Collection Chart'></input></td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=5 align='center'>".
						"This button will DELETE from the records the last added data.<br>".
						"<input type='submit' name='submit_button' ".
						"value='Delete Last Record From Drug Collection Chart'></input></td>";
				print "</tr>";
			
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, JVTolentino
		// This function will add a record in [m_leprosy_drug_collection-chart]
		function drug_collection_chart_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", $_POST['date_for_the_supervised_dose']);
			$loc_date_for_the_supervised_dose = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_treatment = $_POST['treatment'];
			$loc_given_by = $_POST['given_by'];
			$loc_remarks = $_POST['remarks'];
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_POST['h_userid'];
			
			$query = "INSERT INTO m_leprosy_drug_collection_chart ".
				"(consult_id, patient_id, date_for_the_supervised_dose, treatment, given_by, remarks, ".
				"date_last_updated, user_id) ".
				"VALUES($loc_consult_id, $loc_patient_id, '$loc_date_for_the_supervised_dose', ".
				"'$loc_treatment', '$loc_given_by', '$loc_remarks', '$loc_current_date', $loc_userid)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, JVTolentino
		// This function will delete the patient's last record in [m_leprosy_drug_collection-chart]
		function delete_record_from_drug_collection_chart() {
			$loc_consult_id = $_GET['consult_id'];
			//uncomment the following line of code if needed.
			//$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT record_number FROM m_leprosy_drug_collection_chart WHERE ".
				"consult_id = $loc_consult_id ORDER BY record_number DESC ";
			$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			
			if($row = mysql_fetch_assoc($result)) {
				$loc_record_number = $row['record_number'];
				
				$query = "DELETE FROM m_leprosy_drug_collection_chart WHERE ".
					"record_number = $loc_record_number ";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, 2010, JVTolentino
		// This function will be used to create the 'Voluntary Muscle Testing' table.
		function create_voluntary_muscle_testing_table() {
			$this->populate_voluntary_muscle_testing();
			
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<th align='left' bgcolor=#CC9900# colspan=5>Voluntary Muscle Testing</th>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='center' colspan=2><b>Upon Dx</b></td>";
					print "<td align='center'></td>";
					print "<td align='center' colspan=2><b>Upon TC</b></td>";
				print "<tr>";
				
				print "<tr>";
					print "<td align='center'><b>Right</b></td>";
					print "<td align='center'><b>Left</b></td>";
					print "<td align='center' rowspan=2><b><i>Key Movements</i></b></td>";
					print "<td align='center'><b>Right</b></td>";
					print "<td align='center'><b>Left</b></td>";
				print "<tr>";
				
				$query = "SELECT * FROM m_leprosy_voluntary_muscle_testing WHERE ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query)	
					or die("Couldn't execute query. ".mysql_error());
				
				while($row = mysql_fetch_array($result)) {
					extract($row);
					print "<tr>";
						print "<td align='center'>$upon_dx_right</td>";
						print "<td align='center'>$upon_dx_left</td>";
						print "<td align='center'>$key_movement</td>";
						print "<td align='center'>$upon_tc_right</td>";
						print "<td align='center'>$upon_tc_left</td>";
					print "<tr>";
				}
			//print "</table>";
			
			
			//print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<td colspan=5><b>Update Voluntary Muscle Testing:</b></td>";
				print "</tr>";
				
				print "<tr>";				
					print "<td align='center' colspan=5>";
						print "<select name='voluntary_muscle_testing_dx_tc'>";
							print "<option value='dx_right'>Upon Dx - Right</option>";
							print "<option value='dx_left'>Upon Dx - Left</option>";
							print "<option value='tc_right'>Upon TC - Right</option>";
							print "<option value='tc_left'>Upon TC - Left</option>";
						print "</select>";
						
						print "&nbsp;";
						
						print "<select name='voluntary_muscle_testing_key_movement'>";
							print "<option value='Tight Eye Closure'>Tight Eye Closure</option>";
							print "<option value='Little Finger Out'>Little Finger Out</option>";
							print "<option value='Thumb Up'>Thumb Up</option>";
							print "<option value='Wrist Up'>Wrist Up</option>";
							print "<option value='Foot Up'>Foot Up</option>";
						print "</select>";
						
						print "&nbsp;";
						
						print "<select name='voluntary_muscle_testing_muscle_strength'>";
							print "<option value='5'>5 - Full range of motion; full resistance</option>";
							print "<option value='4'>4 - Full range of motion; some resistance</option>";
							print "<option value='3'>3 - Full range of motion; no resistance</option>";
							print "<option value='2'>2 - Decreased range of motion</option>";
							print "<option value='1'>1 - Muscle flicker/palpable muscle contraction</option>";
							print "<option value='0'>0 - Complete Paralysis</option>";
						print "</select>";
					print "</td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=5 align='center'><input type='submit' name='submit_button' ".
						"value='Update Voluntary Muscle Testing'></input></td>";
				print "</tr>";
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, 2010, JVTolentino
		// This function will populate [m_leprosy_voluntary_muscle_testing] with the patient's record.
		function populate_voluntary_muscle_testing() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			// IF THERE IS A RECORD, NO NEED TO UPDATE
			
			$query = "SELECT * FROM m_leprosy_voluntary_muscle_testing ".
				"WHERE consult_id = $loc_consult_id AND ".
				"key_movement = 'Tight Eye Closure' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_voluntary_muscle_testing ".
					"(consult_id, patient_id, key_movement, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Tight Eye Closure', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_voluntary_muscle_testing ".
				"WHERE consult_id = $loc_consult_id AND ".
				"key_movement = 'Little Finger Out' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_voluntary_muscle_testing ".
					"(consult_id, patient_id, key_movement, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Little Finger Out', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_voluntary_muscle_testing ".
				"WHERE consult_id = $loc_consult_id AND ".
				"key_movement = 'Thumb Up' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_voluntary_muscle_testing ".
					"(consult_id, patient_id, key_movement, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Thumb Up', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_voluntary_muscle_testing ".
				"WHERE consult_id = $loc_consult_id AND ".
				"key_movement = 'Wrist Up' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_voluntary_muscle_testing ".
					"(consult_id, patient_id, key_movement, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Wrist Up', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_voluntary_muscle_testing ".
				"WHERE consult_id = $loc_consult_id AND ".
				"key_movement = 'Foot Up' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_voluntary_muscle_testing ".
					"(consult_id, patient_id, key_movement, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Foot Up', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 04, 2010, JVTolentino
		// This function will modify a record in [m_leprosy_voluntary_muscle_testing].
		function voluntary_muscle_testing_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			$loc_key_movement = $_POST['voluntary_muscle_testing_key_movement'];
			$loc_dx_or_tc = $_POST['voluntary_muscle_testing_dx_tc'];
			$loc_muscle_strength = $_POST['voluntary_muscle_testing_muscle_strength'];
			
			switch($loc_dx_or_tc) {
				case 'dx_right':
					$query = "UPDATE m_leprosy_voluntary_muscle_testing SET ".
						"upon_dx_right = '$loc_muscle_strength', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"key_movement = '$loc_key_movement' ";
					break;
				case 'dx_left':
					$query = "UPDATE m_leprosy_voluntary_muscle_testing SET ".
						"upon_dx_left = '$loc_muscle_strength', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"key_movement = '$loc_key_movement' ";
					break;
				case 'tc_right':
					$query = "UPDATE m_leprosy_voluntary_muscle_testing SET ".
						"upon_tc_right = '$loc_muscle_strength', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"key_movement = '$loc_key_movement' ";
					break;
				case 'tc_left':
					$query = "UPDATE m_leprosy_voluntary_muscle_testing SET ".
						"upon_tc_left = '$loc_muscle_strength', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"key_movement = '$loc_key_movement' ";
					break;
				default:
			}
			$result = mysql_query($query)
				or die("Couldnt execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 05, 2010, JVTolentino
		// This function will be used to create the 'Eye Evaluation' table.
		function create_eye_evaluation_table() {
			$this->populate_eye_evaluation();
			
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<th align='left' bgcolor=#CC9900# colspan=5>Eye Evaluation</th>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='center' colspan=2><b>Upon Dx</b></td>";
					print "<td align='center'></td>";
					print "<td align='center' colspan=2><b>Upon TC</b></td>";
				print "<tr>";
				
				print "<tr>";
					print "<td align='center'><b>Right</b></td>";
					print "<td align='center'><b>Left</b></td>";
					print "<td align='center' rowspan=2><b><i>Indicators</i></b></td>";
					print "<td align='center'><b>Right</b></td>";
					print "<td align='center'><b>Left</b></td>";
				print "<tr>";
				
				$query = "SELECT * FROM m_leprosy_eye_evaluation WHERE ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query)	
					or die("Couldn't execute query. ".mysql_error());
				
				while($row = mysql_fetch_array($result)) {
					extract($row);
					print "<tr>";
						print "<td align='center'>$upon_dx_right</td>";
						print "<td align='center'>$upon_dx_left</td>";
						print "<td align='center'>$indicator</td>";
						print "<td align='center'>$upon_tc_right</td>";
						print "<td align='center'>$upon_tc_left</td>";
					print "<tr>";
				}
				
				print "<tr>";
					print "<td colspan=5><b>Update Eye Evaluation:</b></td>";
				print "</tr>";
				
				print "<tr>";				
					print "<td align='center' colspan=5>";
						print "<select name='eye_evaluation_dx_tc'>";
							print "<option value='dx_right'>Upon Dx - Right</option>";
							print "<option value='dx_left'>Upon Dx - Left</option>";
							print "<option value='tc_right'>Upon TC - Right</option>";
							print "<option value='tc_left'>Upon TC - Left</option>";
						print "</select>";
						
						print "&nbsp;";
						
						print "<select name='eye_evaluation_indicator'>";
							print "<option value='Redness (Y/N)'>Redness (Y/N)</option>";
							print "<option value='Decreased of No Blink (Y/N)'>Decreased of No Blink (Y/N)</option>";
							print "<option value='Lid Gap w/ Light Closure (mm)'>Lid Gap w/ Light Closure (mm)</option>";
							print "<option value='Visual Acuity (meters)'>Visual Acuity (meters)</option>";
							print "<option value='Corneal Opacity (Y/N)'>Corneal Opacity (Y/N)</option>";
						print "</select>";
						
						print "&nbsp;";
						
						print "<input type='text' name='eye_evaluation_indicator_value' size=10>".
							"</input>";
					print "</td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=5 align='center'><input type='submit' name='submit_button' ".
						"value='Update Eye Evaluation'></input></td>";
				print "</tr>";
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 05, 2010, JVTolentino
		// This function will populate [m_leprosy_eye_evaluation] with the patient's record.
		function populate_eye_evaluation() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			// IF THERE IS A RECORD, NO NEED TO UPDATE
			
			$query = "SELECT * FROM m_leprosy_eye_evaluation ".
				"WHERE consult_id = $loc_consult_id AND ".
				"indicator = 'Redness (Y/N)' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_eye_evaluation ".
					"(consult_id, patient_id, indicator, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Redness (Y/N)', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_eye_evaluation ".
				"WHERE consult_id = $loc_consult_id AND ".
				"indicator = 'Decreased of No Blink (Y/N)' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_eye_evaluation ".
					"(consult_id, patient_id, indicator, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Decreased of No Blink (Y/N)', ".
					"'$loc_current_date', $loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_eye_evaluation ".
				"WHERE consult_id = $loc_consult_id AND ".
				"indicator = 'Lid Gap w/ Light Closure (mm)' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_eye_evaluation ".
					"(consult_id, patient_id, indicator, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Lid Gap w/ Light Closure (mm)', ".
					"'$loc_current_date', $loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_eye_evaluation ".
				"WHERE consult_id = $loc_consult_id AND ".
				"indicator = 'Visual Acuity (meters)' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_eye_evaluation ".
					"(consult_id, patient_id, indicator, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Visual Acuity (meters)', ".
					"'$loc_current_date', $loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_eye_evaluation ".
				"WHERE consult_id = $loc_consult_id AND ".
				"indicator = 'Corneal Opacity (Y/N)' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_eye_evaluation ".
					"(consult_id, patient_id, indicator, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Corneal Opacity (Y/N)', ".
					"'$loc_current_date', $loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 05, 2010, JVTolentino
		// This function will modify a record in [m_leprosy_eye_evaluation].
		function eye_evaluation_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			$loc_indicator = $_POST['eye_evaluation_indicator'];
			$loc_dx_or_tc = $_POST['eye_evaluation_dx_tc'];
			$loc_eye_evaluation_indicator_value = $_POST['eye_evaluation_indicator_value'];
			
			switch($loc_dx_or_tc) {
				case 'dx_right':
					$query = "UPDATE m_leprosy_eye_evaluation SET ".
						"upon_dx_right = '$loc_eye_evaluation_indicator_value', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"indicator = '$loc_indicator' ";
					break;
				case 'dx_left':
					$query = "UPDATE m_leprosy_eye_evaluation SET ".
						"upon_dx_left = '$loc_eye_evaluation_indicator_value', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"indicator = '$loc_indicator' ";
					break;
				case 'tc_right':
					$query = "UPDATE m_leprosy_eye_evaluation SET ".
						"upon_tc_right = '$loc_eye_evaluation_indicator_value', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"indicator = '$loc_indicator' ";
					break;
				case 'tc_left':
					$query = "UPDATE m_leprosy_eye_evaluation SET ".
						"upon_tc_left = '$loc_eye_evaluation_indicator_value', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"indicator = '$loc_indicator' ";
					break;
				default:
			}
			$result = mysql_query($query)
				or die("Couldnt execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 05, 2010, JVTolentino
		// This function will be used to create the 'WHO Disability Grade' table.
		function create_who_disability_grade_table() {
			$this->populate_who_disability_grade();
			
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<th align='left' bgcolor=#CC9900# colspan=5>WHO Disability Grade</th>";
				print "</tr>";
				
				print "<tr>";
					print "<td align='center' colspan=2><b>Upon Dx</b></td>";
					print "<td align='center'></td>";
					print "<td align='center' colspan=2><b>Upon TC</b></td>";
				print "<tr>";
				
				print "<tr>";
					print "<td align='center'><b>Right</b></td>";
					print "<td align='center'><b>Left</b></td>";
					print "<td align='center' rowspan=2><b><i>WHO Disability</i></b></td>";
					print "<td align='center'><b>Right</b></td>";
					print "<td align='center'><b>Left</b></td>";
				print "<tr>";
				
				$query = "SELECT * FROM m_leprosy_who_disability_grade WHERE ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query)	
					or die("Couldn't execute query. ".mysql_error());
				
				while($row = mysql_fetch_array($result)) {
					extract($row);
					print "<tr>";
						print "<td align='center'>$upon_dx_right</td>";
						print "<td align='center'>$upon_dx_left</td>";
						print "<td align='center'>$who_disability</td>";
						print "<td align='center'>$upon_tc_right</td>";
						print "<td align='center'>$upon_tc_left</td>";
					print "<tr>";
				}
				
				print "<tr>";
					print "<td colspan=5><b>Update WHO Disability Grade:</b></td>";
				print "</tr>";
				
				print "<tr>";				
					print "<td align='center' colspan=5>";
						print "<select name='who_disability_grade_dx_tc'>";
							print "<option value='dx_right'>Upon Dx - Right</option>";
							print "<option value='dx_left'>Upon Dx - Left</option>";
							print "<option value='tc_right'>Upon TC - Right</option>";
							print "<option value='tc_left'>Upon TC - Left</option>";
						print "</select>";
						
						print "&nbsp;";
						
						print "<select name='who_disability'>";
							print "<option value='Eyes'>Eyes</option>";
							print "<option value='Hands'>Hands</option>";
							print "<option value='Feet'>Feet</option>";
							print "<option value='Maximum Grade'><i>Maximum Grade</i></option>";
						print "</select>";
						
						print "&nbsp;";
						
						print "<select name='who_disability_grade'>";
							print "<option value='2'>Grade 2: Visible Deformity</option>";
							print "<option value='1'>Grade 1: Anesthesia</option>";
							print "<option value='0'>Grade 0: Normal</option>";
						print "</select>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=5 align='center'><input type='submit' name='submit_button' ".
						"value='Update WHO Disability Grade'></input></td>";
				print "</tr>";
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 05, 2010, JVTolentino
		// This function will populate [m_leprosy_who_disability_grade] with the patient's record.
		function populate_who_disability_grade() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			// IF THERE IS A RECORD, NO NEED TO UPDATE
			
			$query = "SELECT * FROM m_leprosy_who_disability_grade ".
				"WHERE consult_id = $loc_consult_id AND ".
				"who_disability = 'Eyes' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_who_disability_grade ".
					"(consult_id, patient_id, who_disability, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Eyes', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_who_disability_grade ".
				"WHERE consult_id = $loc_consult_id AND ".
				"who_disability = 'Hands' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_who_disability_grade ".
					"(consult_id, patient_id, who_disability, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Hands', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_who_disability_grade ".
				"WHERE consult_id = $loc_consult_id AND ".
				"who_disability = 'Feet' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_who_disability_grade ".
					"(consult_id, patient_id, who_disability, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Feet', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
			
			$query = "SELECT * FROM m_leprosy_who_disability_grade ".
				"WHERE consult_id = $loc_consult_id AND ".
				"who_disability = 'Maximum Grade' ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			if(!(mysql_num_rows($result))) {
				$query = "INSERT INTO m_leprosy_who_disability_grade ".
					"(consult_id, patient_id, who_disability, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, 'Maximum Grade', '$loc_current_date', ".
					"$loc_userid) ";
				$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Feb 05, 2010, JVTolentino
		// This function will modify a record in [m_leprosy_who_disability_grade].
		function who_disability_grade_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];
			
			$loc_who_disability = $_POST['who_disability'];
			$loc_dx_or_tc = $_POST['who_disability_grade_dx_tc'];
			$loc_who_disability_grade = $_POST['who_disability_grade'];
			
			switch($loc_dx_or_tc) {
				case 'dx_right':
					$query = "UPDATE m_leprosy_who_disability_grade SET ".
						"upon_dx_right = '$loc_who_disability_grade', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"who_disability = '$loc_who_disability' ";
					break;
				case 'dx_left':
					$query = "UPDATE m_leprosy_who_disability_grade SET ".
						"upon_dx_left = '$loc_who_disability_grade', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"who_disability = '$loc_who_disability' ";
					break;
				case 'tc_right':
					$query = "UPDATE m_leprosy_who_disability_grade SET ".
						"upon_tc_right = '$loc_who_disability_grade', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"who_disability = '$loc_who_disability' ";
					break;
				case 'tc_left':
					$query = "UPDATE m_leprosy_who_disability_grade SET ".
						"upon_tc_left = '$loc_who_disability_grade', ".
						"date_last_updated = '$loc_current_date', ".
						"user_id = $loc_userid ".
						"WHERE consult_id = $loc_consult_id AND ".
						"who_disability = '$loc_who_disability' ";
					break;
				default:
			}
			$result = mysql_query($query)
				or die("Couldnt execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<



		// Comment date: Feb 10, 2010, JVTolentino
		// This function will be used to create the 'Post-Treatment Table'
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function create_post_treatment_table() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			
			$query = "SELECT date_of_diagnosis FROM m_leprosy_diagnosis WHERE ".
				"consult_id = $loc_consult_id ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			if($row = mysql_fetch_array($result)) {
				list($year, $month, $day) = explode("-", $row['date_of_diagnosis']);
				$loc_date_of_diagnosis = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
			} 

			$query = "SELECT * FROM m_leprosy_post_treatment WHERE ".
				"consult_id = $loc_consult_id ";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			if($row = mysql_fetch_array($result)) {
				$loc_upon_dx_physician = $row['upon_dx_physician'];
				$loc_upon_tc_physician = $row['upon_tc_physician'];
				list($year, $month, $day) = explode("-", $row['upon_tc_date']);
				$loc_upon_tc_date = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				$loc_patient_cured = $row['patient_cured'];
				$loc_movement_of_patient = $row['movement_of_patient'];
				$loc_updated = 'Y';
			}
			else {
				$loc_updated = 'N';
			}

			print "<table border=3 bordercolor='black' align='center' width=600>";
				print "<tr>";
					print "<th align='left' colspan=3 bgcolor=#CC9900#>Post Treatment</th>";
				print "</tr>";

				print "<tr>";
					print "<td>Upon Diagnosis</td>";
					if($loc_updated == 'Y') {
						print "<td>Examining Physician: <input type='text' name='upon_dx_physician' ".
							"value='$loc_upon_dx_physician' size=30></input></td>";
					}
					else {
						print "<td>Examining Physician: <input type='text' name='upon_dx_physician' size=30></input></td>";
					}

					print "<td>Date: <input type='text' name='upon_dx_date' value='$loc_date_of_diagnosis' size=10 readonly></input></td>";
				print "</tr>";
				
				print "<tr>";	
					print "<td>Upon Completion of Treatment</td>";
					if($loc_updated == 'Y') {
						print "<td>Examining Physician: <input type='text' name='upon_tc_physician' ".
							"value='$loc_upon_tc_physician' size=30></input></td>";	
					}
					else {
						print "<td>Examining Physician: <input type='text' name='upon_tc_physician' size=30></input></td>";	
					}
					if($loc_updated == 'Y') {
						print "<td>Date: ".
							"<input type='text' name='upon_tc_date' ".
							"readonly='true' size=10 value='$loc_upon_tc_date'>".
							"<a href=\"javascript:show_calendar4('document.form_leprosy.upon_tc_date', ".
							"document.form_leprosy.upon_tc_date.value);\">".
							"<img src='../images/cal.gif' width='16' height='16' border='0' ".
							"alt='Click Here to Pick up the Date'></a></input>";
							"</td>";
					}
					else {
						print "<td>Date: ".
							"<input type='text' name='upon_tc_date' ".
							"readonly='true' size=10 value='".date("m/d/Y").
							"'> <a href=\"javascript:show_calendar4('document.form_leprosy.upon_tc_date', ".
							"document.form_leprosy.upon_tc_date.value);\">".
							"<img src='../images/cal.gif' width='16' height='16' border='0' ".
							"alt='Click Here to Pick up the Date'></a></input>";
							"</td>";
					}
				print "</tr>";

				print "<tr>";
					print "<td>Patient Status: </td>";
					print "<td colspan=2><select name='patient_cured'>";
						switch($loc_patient_cured) {
							case 'Completed':
								print "<option value='Defaulted'>Defaulted</option>";
								print "<option value='Completed' selected>Completed</option>";
								print "<option value='Undergoing Treatment'>Undergoing Treatment</option>";
								break;
							case 'Defaulted':
								print "<option value='Defaulted' selected>Defaulted</option>";
								print "<option value='Completed'>Completed</option>";
								print "<option value='Undergoing Treatment'>Undergoing Treatment</option>";
								break;
							case 'Undergoing Treatment':
								print "<option value='Defaulted'>Defaulted</option>";
								print "<option value='Completed'>Completed</option>";
								print "<option value='Undergoing Treatment' selected>Undergoing Treatment</option>";
								break;
							default:
								print "<option value='Defaulted'>Defaulted</option>";
								print "<option value='Completed'>Completed</option>";
								print "<option value='Undergoing Treatment' selected>Undergoing Treatment</option>";
								break;
						}
					print "</select></td>";
				print "</tr>";

				print "<tr>";
					print "<td>Movement of Patient: </td>";
					print "<td colspan=2><select name='movement_of_patient'>";
						switch($loc_movement_of_patient) {
							case 'T/I':
								print "<option value='T/I' selected>T/I</option>";
								print "<option value='T/O'>T/O</option>";
								print "<option value='Died'>Died</option>";
								print "<option value='Lost'>Lost</option>";
								print "<option value='n/a'>N/A</option>";
								break;
							case 'T/O':
								print "<option value='T/I'>T/I</option>";
								print "<option value='T/O' selected>T/O</option>";
								print "<option value='Died'>Died</option>";
								print "<option value='Lost'>Lost</option>";
								print "<option value='n/a'>N/A</option>";
								break;
							case 'Died':
								print "<option value='T/I'>T/I</option>";
								print "<option value='T/O'>T/O</option>";
								print "<option value='Died' selected>Died</option>";
								print "<option value='Lost'>Lost</option>";
								print "<option value='n/a'>N/A</option>";
								break;
							case 'Lost':
								print "<option value='T/I'>T/I</option>";
								print "<option value='T/O'>T/O</option>";
								print "<option value='Died'>Died</option>";
								print "<option value='Lost' selected>Lost</option>";
								print "<option value='n/a'>N/A</option>";
								break;
							default:
								print "<option value='T/I'>T/I</option>";
								print "<option value='T/O'>T/O</option>";
								print "<option value='Died'>Died</option>";
								print "<option value='Lost'>Lost</option>";
								print "<option value='n/a' selected>N/A</option>";
								break;
						}
					print "</selecte></td>";
				print "</tr>";
				
				print "<tr>";
					print "<td colspan=3 align='center'><input type='submit' name='submit_button' ".
						"value='Save Post Treatment Record'></input></td>";
				print "</tr>";
			print "</table>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

		
		
		// Comment date: FEb 10, 2010, JVTolentino
		// This function will add/update a record in [m_leprosy_post_treatment].
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function post_treatment_record() {
			$loc_consult_id = $_GET['consult_id'];
			$loc_patient_id = healthcenter::get_patient_id($_GET['consult_id']);
			$loc_upon_dx_physician = $_POST['upon_dx_physician'];
			$loc_upon_tc_physician = $_POST['upon_tc_physician'];
			list($month, $day, $year) = explode("/", $_POST['upon_tc_date']);
			$loc_upon_tc_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_patient_cured = $_POST['patient_cured'];
			$loc_movement_of_patient = $_POST['movement_of_patient'];
			list($month, $day, $year) = explode("/", date("m/d/Y"));
			$loc_current_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			$loc_userid = $_SESSION['userid'];

			$query = "SELECT * FROM m_leprosy_post_treatment WHERE consult_id = $loc_consult_id ";
			$result = mysql_query($query) 
				or die("Couldn't execute query. ".mysql_error());

			if(mysql_num_rows($result)) {
				$query = "UPDATE m_leprosy_post_treatment SET ".
					"upon_dx_physician = '$loc_upon_dx_physician', ".
					"upon_tc_physician = '$loc_upon_tc_physician', ".
					"upon_tc_date = '$loc_upon_tc_date', ".
					"patient_cured = '$loc_patient_cured', ".
					"movement_of_patient = '$loc_movement_of_patient', ".
					"date_last_updated = '$loc_current_date', ".
					"user_id = $loc_userid ".
					"WHERE consult_id = $loc_consult_id ";
			}
			else {
				$query = "INSERT INTO m_leprosy_post_treatment ".
					"(consult_id, patient_id, upon_dx_physician, upon_tc_physician, upon_tc_date, ".
					"patient_cured, movement_of_patient, date_last_updated, user_id) ".
					"VALUES($loc_consult_id, $loc_patient_id, '$loc_upon_dx_physician', ".
					"'$loc_upon_tc_physician', '$loc_upon_tc_date', '$loc_patient_cured', ".
					"'$loc_movement_of_patient', '$loc_current_date', $loc_userid)";
			}
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<



		// Comment date: Jan 27, 2010, JVTolentino
		// This function will be used to add records to 'Leprosy Module Tables' in CHITS DB.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function new_leprosy_record() {
			
			switch($_POST['submit_button']) {
				case 'Save Diagnosis':
					$this->diagnosis_record();
					break;
				case 'Add Treatment Record':
					$this->past_treatment_record();
					break;
				case 'Delete Last Added Treatment Record':
					$this->delete_treatment_record();
					break;
				case 'Save Other Illnesses':
					$this->other_illness_record();
					break;
				case 'Save Contact Examination':
					$this->contact_examination_record();
					break;
				case 'Save Skin Smear':
					$this->skin_smear_record();
					break;
				case 'Add To Drug Collection Chart':
					$this->drug_collection_chart_record();
					break;
				case 'Delete Last Record From Drug Collection Chart':
					$this->delete_record_from_drug_collection_chart();
					break;
				case 'Update Voluntary Muscle Testing':
					$this->voluntary_muscle_testing_record();
					break;
				case 'Update Eye Evaluation':
					$this->eye_evaluation_record();
					break;
				case 'Update WHO Disability Grade':
					$this->who_disability_grade_record();
					break;
				case 'Save Post Treatment Record':
					$this->post_treatment_record();
					break;
			}
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
		
		// Comment date: Jan 29, 2010, JVTolentino
		// There is an issue regarding the database, for some reason the primary keys (ai)
		// 	for every table will initialize to 1 everytime each module starts, instead of 
		//		getting the last value of the last record.
		//	This is a server-side problem. The solution is to drop each primary each and then 
		//		re-assigns it. The following codes can be inserted into index.php, under the info
		//		directory. I'm experimenting if it is possible to not modify index.php everytime
		//		a module is being introduced to EMR, rather insert the codes here and execute
		//		this everytime this module starts.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function init_primary_keys() {
			$query = "ALTER TABLE m_leprosy_contact_examination DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());			

			$query = "ALTER TABLE m_leprosy_diagnosis DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
			
			$query = "ALTER TABLE m_leprosy_drug_collection_chart DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_eye_evaluation DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_other_illness DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_past_treatment DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_post_treatment DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_skin_smear DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_voluntary_muscle_testing DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());

			$query = "ALTER TABLE m_leprosy_who_disability_grade DROP PRIMARY KEY, ".
				"ADD PRIMARY KEY(record_number)";
			$result = mysql_query($query)
				or die("Couldn't execute query. ".mysql_error());
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
		// Comment date: Nov 04, '09, JVTolentino
		// This is the main function for the leprosy module.
		// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		function _consult_leprosy() {
			echo "<form name='form_leprosy' action='$_POST[PHP_SELF]' method='POST'>";
				
				$leprosy = new leprosy;
				
				$leprosy->consult_id = $_GET['consult_id'];
				$leprosy->patient_id = healthcenter::get_patient_id($_GET['consult_id']);
				//$leprosy->patient_age = healthcenter::get_patient_age($_GET['consult_id']);
				$leprosy->userid = $_SESSION['userid'];
				
				//The following codes will initialize hidden textboxes and their values
				echo "<input type='hidden' name='h_consult_id' value='{$leprosy->consult_id}'></input>";
				echo "<input type='hidden' name='h_patient_id' value='{$leprosy->patient_id}'></input>";
				echo "<input type='hidden' name='h_userid' value='{$leprosy->userid}'></input>";
				
				if (@$_POST['h_save_flag'] == 'GO') {
					print "&nbsp;";
					$leprosy->new_leprosy_record();
					// test wether it still needed to initialize primary keys after a POST.
					//$leprosy->init_primary_keys();
					
					print "&nbsp;";
					$leprosy->show_NLCPForm1();
				
				} 
				else {
					print "&nbsp;";
					$leprosy->init_primary_keys();
					
					print "&nbsp;";
					$leprosy->show_NLCPForm1();
					
					
				}
				
				echo "<input type='hidden' name='h_save_flag' value='GO'></input>";
			echo "</form>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
	} // class ends here
?>
