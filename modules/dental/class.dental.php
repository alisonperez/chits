<?
  class dental extends module {
    
   // Author: Herman Tolentino MD
   // CHITS Project 2004
	
   // DENTAL HEALTH CARE PROGRAM MODULE
   
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
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   function dental() {
		$this->author = "Jeffrey V. Tolentino";
		$this->version = "0.1-".date("Y-m-d");
		$this->module = "dental";
		$this->description = "CHITS Module - Dental Health Care Program";  
   }
   // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
   // Comment date: Oct 21, '09, JVTolentino
   // This function is somehow needed by the healthcenter class, the reason 
   //    is unknown.
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   function _details_dental() {
   }
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
 
 
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
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function init_menu() {
		if (func_num_args()>0) {
			$arg_list = func_get_args();
		}
      
      //print_r($arg_list);
		
      // set_menu parameters
      // set_menu([module name], [menu title - what is displayed], menu categories (top menu)], [script executed in class])
      //module::set_menu($this->module, "Dental Records", "PATIENTS", "_consult_dental");
      // set_detail parameters
      // set_detail([module description], [module version], [module author], [module name/id]
      module::set_detail($this->description, $this->version, $this->author, $this->module);
   }
   // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
		
	// Comment date: Oct 7, 2009, JVTolentino
	// The init_sql() function starts here.
	// This function will initialize the tables for the Dental Module in CHITS DB.
	//
	// Comment date: Dec 01, 09, JVTolentino
	// According to the Data Dictionary in FHSIS orally fit children (12-71 months old) refers to 
	// 	children who meet ALL of the following upon oral examination and/or completion of
	// 	treatment:
	// 	1. carries-free or decayed teeth filled
	// 	2. has healthy gums
	// 	3. no oral debris, and
	// 	4. no dento-facial anomaly that limits normal function.
	// Thus, an additional field was added in m_dental_patient_ohc_table_a: healthy_gums.
	//
	// Comment date: Dec 03, 2009, JVTolentino
	// To satisfy Indicator #2 in the Dental Program on FHSIS another table was added to the db:
	// 	m_dental_other_services. Please refer to FHSIS manual.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function init_sql() {
      if (func_num_args()>0) {
        $arg_list = func_get_args();
      }
      
      // The following codes will be used to create [m_dental_patient_ohc].
      // If needed, change the table name to follow proper naming conventions in CHITS.
      // The table reflects the standard format on how to collect data regarding 
      //    the patient's oral health condition. The entries on the fields [tooth_number]
      //    and [tooth_condition] reflects what the dentists actually used in their 
      //    patient/treatment record. See the IPTR given by Dr. Domingo for further reference.
      module::execsql("CREATE TABLE IF NOT EXISTS `m_dental_patient_ohc` (".
        "`ohc_id` float NOT NULL auto_increment COMMENT 'Patient Oral Health Condition',".
        "`patient_id` int(11) NOT NULL,".
        "`consult_id` float NOT NULL,".
        "`is_patient_pregnant` char(1) COLLATE swe7_bin NOT NULL,".
        "`tooth_number` int(11) NOT NULL,".
        "`tooth_condition` varchar(5) collate swe7_bin NOT NULL,".
        "`date_of_oral` date NOT NULL,".
        "`dentist` float NOT NULL,".
        "PRIMARY KEY  (`ohc_id`)".
        ") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin COMMENT='Patient Oral Health Condition' AUTO_INCREMENT=1 ;");
		
		// The following codes will be used to create [m_dental_services].
		// If needed, change the table name to follow proper naming conventions in CHITS.
		// The table reflects the standard format on how to collect data regarding 
		//    the dentist's services to patients. The entries on the fields [tooth_number]
		//    and [service_provided] reflects what the dentists actually used in their 
		//    patient/treatment record. See the IPTR given by Dr. Domingo for further reference.
		module::execsql("CREATE TABLE IF NOT EXISTS `m_dental_services` (".
			"`service_id` float NOT NULL auto_increment,".
			"`patient_id` float NOT NULL,".
			"`consult_id` float NOT NULL,".
			"`tooth_number` int(11) NOT NULL,".
			"`service_provided` varchar(5) collate swe7_bin NOT NULL,".
			"`date_of_service` date NOT NULL,".
			"`dentist` float NOT NULL,".
			"PRIMARY KEY  (`service_id`)".
			") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
        
        
      // The following codes will be used to create [m_lib_dental_tooth_condition]
      // If needed, change the table name to follow proper naming conventions in CHITS.
      // The table reflects the standard tooth conditions and their corresponding legends.
      // See the IPTR given by Dr. domingo for further reference.
      module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_dental_tooth_condition` (".
        "`legend` varchar(5) collate swe7_bin NOT NULL COMMENT 'condition legend',".
        "`status` varchar(50) collate swe7_bin NOT NULL COMMENT 'tooth status Perma/Tempo',".
        "`condition` varchar(50) NOT NULL COMMENT 'condition description',".
        "PRIMARY KEY  (`legend`)".
        ") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin;");
		
		
		// The following codes will be used to create [m_lib_dental_services]
		// If needed, change the table name to follow proper naming conventions in CHITS.
		// The table reflects the standard tooth services (including their legends) that can be provided.
		// See the IPTR given by Dr. domingo for further reference.
		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_dental_services` (".
			"`legend` varchar(5) collate swe7_bin NOT NULL COMMENT 'service legend',".
			"`service` varchar(50) NOT NULL COMMENT 'service description',".
			"PRIMARY KEY  (`legend`)".
			") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin;");
        
        
      // The following codes will be used to insert the initial records for
      //    m_lib_dental_tooth_condition.
      module::execsql("INSERT INTO `m_lib_dental_tooth_condition` (`legend`, `status`, `condition`) VALUES".
        "('D', 'Permanent', 'Decayed'),".
        "('F', 'Permanent', 'Filled'),".
        "('JC', 'Permanent', 'Jacket Crown'),".
        "('M', 'Permanent', 'Missing'),".
        "('P', 'Permanent', 'Pontic'),".
        "('S', 'Permanent', 'Supernumerary Tooth'),".
        "('Un', 'Permanent', 'Unerupted'),".
        "('X', 'Permanent', 'Indicated for Extraction'),".
        "('Y', 'Permanent', 'Sound/Sealed'),".
        "('d', 'Temporary', 'Decayed'),".
        "('e', 'Temporary', 'Missing'),".
        "('f', 'Temporary', 'Filled'),".
        "('jc', 'Temporary', 'Jacket Crown'),".
        "('p', 'Temporary', 'Pontic'),".
        "('s', 'Temporary', 'Supernumerary Tooth'),".
        "('un', 'Temporary', 'Unerupted'),".
        "('x', 'Temporary', 'Indicated for Extraction'),".
        "('y', 'Temporary', 'Sound/Sealed');");
		
		// The following codes will be used to insert the initial records for
		//    m_lib_dental_services.
		module::execsql("INSERT INTO `m_lib_dental_services` (`legend`, `service`) VALUES".
			"('O', 'Others'),".
			"('PF', 'Permanent Filling'),".
			"('S', 'Sealant'),".
			"('TF', 'Temporary Filling'),".
			"('X', 'Extraction');");
        
      
      // The following codes will be used to create m_dental_patient_ohc_table_a.
		// See the IPTR given by Dr. Domingo for further reference.
		module::execsql("CREATE TABLE IF NOT EXISTS `m_dental_patient_ohc_table_a` (".
			"`ohc_table_id` float NOT NULL auto_increment,".
			"`patient_id` float NOT NULL,".
			"`consult_id` float NOT NULL,".
			"`date_of_oral` date NOT NULL,".
			"`dental_caries` char(3) collate swe7_bin NOT NULL,".
			"`gingivitis_periodontal_disease` char(3) collate swe7_bin NOT NULL,".
			"`debris` char(3) collate swe7_bin NOT NULL,".
			"`calculus` char(3) collate swe7_bin NOT NULL,".
			"`abnormal_growth` char(3) collate swe7_bin NOT NULL,".
			"`cleft_lip_palate` char(3) collate swe7_bin NOT NULL,".
			"`others` char(3) collate swe7_bin NOT NULL,".
			"`healthy_gums` char(3) COLLATE swe7_bin NOT NULL,".
			"`dento_facial_anomaly` char(3) COLLATE swe7_bin NOT NULL,".
			"PRIMARY KEY  (`ohc_table_id`)".
			") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
		// Comment date: Dec 01, 2009, JVTolentino
		// The following codes will be used to create m_dental_fhsis.
		// This table is experimental at this moment, I will continue to ponder about this one.
		module::execsql("CREATE TABLE IF NOT EXISTS `m_dental_fhsis` (".
			"`record_number` float NOT NULL AUTO_INCREMENT,".
			"`consult_id` float NOT NULL,".
			"`patient_id` float NOT NULL,".
			"`indicator` int(11) NOT NULL,".
			"`indicator_qualified` char(3) COLLATE swe7_bin NOT NULL,".
			"`date_of_consultation` date NOT NULL,".
			"`age` float NOT NULL,".
			"`gender` char(1) COLLATE swe7_bin NOT NULL,".
			"PRIMARY KEY (`record_number`)".
			") ENGINE=InnoDB DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
			
			
		// Comment date: Dec 03, 2009, JVTolentino
		// The following codes will be used to create m_dental_other_services.
		module::execsql("CREATE TABLE IF NOT EXISTS `m_dental_other_services` (".
			"`record_number` float NOT NULL AUTO_INCREMENT,".
			"`consult_id` float NOT NULL,".
			"`patient_id` float NOT NULL,".
			"`date_of_service` date NOT NULL,".
			"`dentist` float NOT NULL,".
			"`supervised_tooth_brushing` char(3) COLLATE swe7_bin NOT NULL,".
			"`altraumatic_restorative_treatment` char(3) COLLATE swe7_bin NOT NULL,".
			"`out_removal_of_unsavable_teeth` char(3) COLLATE swe7_bin ".
			"NOT NULL COMMENT 'Oral Urgent Treatment (OUT)',".
			"`out_referral_of_complicates_cases` char(3) COLLATE swe7_bin ".
			"NOT NULL COMMENT 'Oral Urgent Treatment (OUT)',".
			"`out_treatment_of_post_extraction_complications` char(3) COLLATE swe7_bin ".
			"NOT NULL COMMENT 'Oral Urgent Treatment (OUT)',".
			"`out_drainage_of_localized_oral_abscess` char(3) COLLATE swe7_bin ".
			"NOT NULL COMMENT 'Oral Urgent Treatment (OUT)',".
			"`education_and_counselling` char(3) COLLATE swe7_bin NOT NULL,".
			"`scaling` char(3) COLLATE swe7_bin NOT NULL,".
			"`gum_treatment` char(3) COLLATE swe7_bin NOT NULL,".
			"PRIMARY KEY (`record_number`)".
			") ENGINE=InnoDB  DEFAULT CHARSET=swe7 COLLATE=swe7_bin AUTO_INCREMENT=1 ;");
     
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
	// Comment date: Oct 13, 2009, JVTolentino
	// The drop_tables() function starts here.
	// This function will be used to drop tables from CHITS DB.
	// This function will be executed if the user opts to delete
	//    the tables associated with this module.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function drop_tables() {
		module::execsql("DROP TABLE `m_dental_patient_ohc`");
		module::execsql("DROP TABLE `m_lib_dental_tooth_condition`");
		module::execsql("DROP TABLE `m_dental_patient_ohc_table_a`");
		module::execsql("DROP TABLE `m_lib_dental_services`");
		module::execsql("DROP TABLE `m_dental_services`");
		module::execsql("DROP TABLE `m_dental_fhsis`"); //experiment...delete this if needed.
		module::execsql("DROP TABLE `m_dental_other_services`");
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    // Comment date: Oct. 13, 09, JVTolentino
    // The succeeding codes and functions will be used exclusively(??) for
    //    the 'CHITS - DENTAL HEALTH CARE PROGRAM MODULE'. These codes
    //    are open-source, so feel free to modify, enhance, and distribute
    //    as you wish.
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
    // Comment date: Oct 16, '09, JVTolentino
    // The following function will be used for acquiring tooth condition
    //    from m_dental_patient_ohc.
    // This function accepts two arguments:
    //    1. $tn -> corresponds to the field tooth_number
    //    2. $cid -> corresponds to the field consult_id
    // Initial assessment is that the function will not need the patient's
    //    id to get a unique record, likewise, the date of oral examination
    //    is also not required. The field consult_id will most probably be
    //    enough to get a unique tooth_condition for every oral examination.
    //    If these arguments prove flawed, change the arguments so that it 
    //    references the fields patient_id and date_of_oral.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function tooth_condition($tn, $cid) {
      $query = "SELECT `tooth_condition` FROM `m_dental_patient_ohc` WHERE `tooth_number` = ".$tn." AND `consult_id` = ".$cid."";
      $result = mysql_query($query)
        or die ("Couldn't execute query.");
      
      if($row = mysql_fetch_assoc($result)) {
        return $row['tooth_condition'];
      }
      else {
        return "&nbsp;";
      }
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
   // Comment date: Oct 14, '09, JVTolentino
   // The following function is used to display the current date,
   //    and if needed, the ability to change the consult date of the
   //    patient. This is usually done when the patient's record 
   //    are being recorded days later after his original consulatation
   //    date.
	//
	// Comment date: Nov 13, '09, JVTolentino
	// Added additional condition on this function. If from a previous post the
	//    user already set the date, it will carry over to the next instance of the page.
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   function show_date_of_oral() {
		echo "<table border=3 bordercolor='red' cellspacing=1 align='center' width=600>";
			echo "<tr>";
				echo "<th align='left' colspan=2 bgcolor=#CC9900#>Date of Oral Examination</th>";          
			echo "</tr>";
       
		echo "<tr>";
			echo "<td align='center' width=200>";
			if($_POST['date_of_oral'] == '') {
				echo "<input type='text' name='date_of_oral' ".
					"readonly='true' size=10 value='".date("m/d/Y").
					"'> <a href=\"javascript:show_calendar4('document.form_dental.date_of_oral', ".
					"document.form_dental.date_of_oral.value);\">".
					"<img src='../images/cal.gif' width='16' height='16' border='0' ".
					"alt='Click Here to Pick up the Date'></a></input>";
			} else {
				echo "<input type='text' name='date_of_oral' ".
					"readonly='true' size=10 value='".$_POST['date_of_oral'].
					"'> <a href=\"javascript:show_calendar4('document.form_dental.date_of_oral', ".
					"document.form_dental.date_of_oral.value);\">".
					"<img src='../images/cal.gif' width='16' height='16' border='0' ".
					"alt='Click Here to Pick up the Date'></a></input>";
			}
          echo "</td>";
          echo "<td> Click the image on the left to change the date of oral examination.</td>";
        echo "</tr>";
      echo "</table>";
	}
   // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
    // Comment date: Oct. 21, '09, JVTolentino
    // The following function is used to get the patient's teeth
    //    conditions.
    //
    // Comment date: Oct 21 (4:12PM), '09, JVTolentino
    // I will implement another feature in this function. The function will now
    //    accept one argument, the age of the patient, and use it to dynamically
    //    get only the relevant teeth numbers and conditions.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function get_teeth_conditions() {
      // upper-teeth (temporary)
      for($this->toothnumber=51; $this->toothnumber<=55; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
      for($this->toothnumber=61; $this->toothnumber<=65; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
        
      // lower-teeth (temporary)
      for($this->toothnumber=81; $this->toothnumber<=85; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
      for($this->toothnumber=71; $this->toothnumber<=75; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
    
      // upper-teeth (permanent)
      for($this->toothnumber=11; $this->toothnumber<=18; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
      for($this->toothnumber=21; $this->toothnumber<=28; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
  
      // lower-teeth (permanent)
      for($this->toothnumber=41; $this->toothnumber<=48; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
      for($this->toothnumber=31; $this->toothnumber<=38; $this->toothnumber++) {
        $this->condition[$this->toothnumber] = $this->tooth_condition($this->toothnumber, $this->consult_id);
      }
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
	// Comment date: Oct 8, '09, JVTolentino
	// The following codes are used to select a tooth_number
	//    and the condition for that particular tooth.
   // The selection will come entirely from the user.
   //
   // Comment date: Oct 21 (4:21PM), '09, JVTolentino
   // I will implement another feature in this function. The function will now
   //    accept one argument, the age of the patient, and use it to dynamically
   //    show in the listbox the relevant teeth and conditions.
	//
	// Comment date: Nov 12, '09, JVTolentino
	// Due to the request and advise of Dr. Domingo (during our meeting last Nov. 09, 
	//    I deleted the feature which was added on Oct 21, '09. 
	//    It seems even older patients (above 4 y.o.) can still have temporary teeth. Therefore,
	//    this function no longer accepts an argument.
	//
	// Comment date: Nov 12, '09, JVTolentino
	// Added one item to element select_condition. The item will have a value '0'.
	// If the user selects this option, the condition with reference to its respective tooth number
	//    will be deleted in the database. The deletion will be handled by the function
	//    new_dental_record().
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function select_tooth_and_condition() {
		echo "<table border=3 bordercolor='red' align='center' width=500>";
			echo "<tr>";
			echo "<th align='left' bgcolor='CC9900'>Set Patient's Tooth Condition</th>";
			echo "</tr>";
  
			echo "<tr>";
				echo "<td>";
					echo "<table align='center' border=0 cellspacing=0>";
						echo "<tr>";
							echo "<td width=200 align='left'>Select tooth number:</td>";
							echo "<td>";
								echo "<select name='select_tooth'>";
									for($i=11; $i<=18; $i++) {
										echo "<option value=$i>$i</option>";
									}
                
									for($i=21; $i<=28; $i++) {
										echo "<option value=$i>$i</option>";
									}
                
									for($i=31; $i<=38; $i++) {
										echo "<option value=$i>$i</option>";
									}
                
									for($i=41; $i<=48; $i++) {
										echo "<option value=$i>$i</option>";
									}

									for($i=51; $i<=55; $i++) {
										echo "<option value=$i>$i</option>";
									}
                
									for($i=61; $i<=65; $i++) {
										echo "<option value=$i>$i</option>";
									}
                
									for($i=71; $i<=75; $i++) {
										echo "<option value=$i>$i</option>";
									}
                
									for($i=81; $i<=85; $i++) {
										echo "<option value=$i>$i</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
    
						echo "<tr>";
							echo "<td width=200 align='left'>Select tooth condition:</td>";
                
							// Comment date: Oct 14, '09, JVTolentino
							// The following codes will be used to propagate a list box which will show
							//    all the possible tooth condition that a patient may have.
							// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
							echo "<td>";
								$query = "SELECT DISTINCT legend FROM m_lib_dental_tooth_condition ORDER BY legend";
								$result = mysql_query($query)
									or die ("Couldn't execute query.");
                  
								echo "<select name='select_condition'>"; 
									echo "<option value='0'></option>";
									while ($row = mysql_fetch_array($result)) {
										extract($row);
										echo "<option value='$legend'>$legend</option>";
									}
								echo "</select>";
							echo "</td>";     
							// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                
							echo "<td width=100 align='center'><input type='submit' name='submit_button'".
								"value='Save Tooth Condition'></input></td>";
						echo "</tr>";
    
					echo "</table>";
				echo "</td>";
			echo "</tr>";
		
			echo "<tr>";
				echo "<td>&nbsp;&nbsp;&nbsp;Use capital letters to record the condition of permanent ".
					"dentition and small letters for the status of temporary dentition.".
					"<br>&nbsp;&nbsp;&nbsp;To delete a tooth condition, just select a tooth number and then ".
					"leave the tooth condition blank.</td>";
			echo "</tr>";
		echo "</table>";
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
    // Comment date: Oct 6, '09
    // The following codes are used to populate a table with
    //   teeth symbols and conditions.
    // My initial plan is to refresh the page every
    //   tooth condition updates.
    // Will see if this is acceptable, especially
    //   when querying the db.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function show_teeth_conditions() {
      echo "<table border=3 bordercolor=#009900# align='center'>";
        // upper-teeth-temporary symbols and conditions
        echo "<tr>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          for($this->toothnumber=55; $this->toothnumber>=51; $this->toothnumber--) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
          for($this->toothnumber=61; $this->toothnumber<=65; $this->toothnumber++) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
        echo "</tr>";
  
        echo "<tr>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          for($this->toothnumber=55; $this->toothnumber>=51; $this->toothnumber--) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
          for($this->toothnumber=61; $this->toothnumber<=65; $this->toothnumber++) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
        echo "</tr>";
          
        // upper-teeth-permanent symbols and conditions
        echo "<tr>";
          for($this->toothnumber=18; $this->toothnumber>=11; $this->toothnumber--) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
          for($this->toothnumber=21; $this->toothnumber<=28; $this->toothnumber++) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
        echo "</tr>";
  
        echo "<tr>";
          for($this->toothnumber=18; $this->toothnumber>=11; $this->toothnumber--) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
          for($this->toothnumber=21; $this->toothnumber<=28; $this->toothnumber++) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
        echo "</tr>";
      echo "</table>";
  
    
      echo "&nbsp;";
      echo "<table border=3 bordercolor=#009900# align='center'>";
        // lower-teeth-permanent symbols and conditions
        echo "<tr>";
          for($this->toothnumber=48; $this->toothnumber>=41; $this->toothnumber--) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
          for($this->toothnumber=31; $this->toothnumber<=38; $this->toothnumber++) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
        echo "</tr>";
  
        echo "<tr>";
          for($this->toothnumber=48; $this->toothnumber>=41; $this->toothnumber--) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
          for($this->toothnumber=31; $this->toothnumber<=38; $this->toothnumber++) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
        echo "</tr>";

        // lower-teeth-temporary symbols and conditions
        echo "<tr>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          for($this->toothnumber=85; $this->toothnumber>=81; $this->toothnumber--) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
          for($this->toothnumber=71; $this->toothnumber<=75; $this->toothnumber++) {
            echo "<td align='center'>{$this->condition[$this->toothnumber]}</td>";
          }
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
        echo "</tr>";
        
        echo "<tr>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          for($this->toothnumber=85; $this->toothnumber>=81; $this->toothnumber--) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
          for($this->toothnumber=71; $this->toothnumber<=75; $this->toothnumber++) {
            echo "<td align='center'><b>$this->toothnumber</b></td>";
          }
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
          echo "<td align='center'></td>";
        echo "</tr>";
      echo "</table>"; 
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
	// Comment date: Oct 22, '09, JVTolentino
	// Initial codes for inserting records in m_dental_patient_ohc_table_a
	//
	// Comment date: Dec 2, 2009, JVTolentino
	// Added 'Dento-Facial Anomaly that Limits Normal Function' to the table to satisfy
	// 	the third criteria in FHSIS indicator 1. Please consult FHSIS Manual (Data Dictionary).
	// TODO: Modularize this function.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function new_ohc_table_a_record() {
		$loc_patient_id = $_POST['h_patient_id'];
		$loc_consult_id = $_POST['h_consult_id'];
      
		list($month, $day, $year) = explode("/", $_POST['date_of_oral']);
		$loc_date_of_oral = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
		
		if($_POST['cb_healthy_gums'] != "YES") {
			$loc_healthy_gums = "NO";
		} else {
			$loc_healthy_gums = $_POST['cb_healthy_gums'];
		}

      if($_POST['cb_dental_caries'] != "YES") {
        $loc_dental_caries = "NO";
      } else {
        $loc_dental_caries = $_POST['cb_dental_caries'];
      }
      
      if($_POST['cb_gingivitis_periodontal_disease'] != "YES") {
        $loc_gingivitis_periodontal_disease = "NO";
      } else {
        $loc_gingivitis_periodontal_disease = $_POST['cb_gingivitis_periodontal_disease'];
      }
      
      if($_POST['cb_debris'] != "YES") {
        $loc_debris = "NO";
      } else {
        $loc_debris = $_POST['cb_debris'];
      }
      
      if($_POST['cb_calculus'] != "YES") {
        $loc_calculus = "NO";
      } else {
        $loc_calculus = $_POST['cb_calculus'];
      }
      
      if($_POST['cb_abnormal_growth'] != "YES") {
        $loc_abnormal_growth = "NO";
      } else {
        $loc_abnormal_growth = $_POST['cb_abnormal_growth'];
      }
      
      if($_POST['cb_cleft_lip_palate'] != "YES") {
        $loc_cleft_lip_palate = "NO";
      } else {
        $loc_cleft_lip_palate = $_POST['cb_cleft_lip_palate'];
      }
      
      if($_POST['cb_others'] != "YES") {
        $loc_others = "NO";
      } else {
        $loc_others = $_POST['cb_others'];
      }
		
		if($_POST['cb_dento_facial_anomaly'] != "YES") {
			$loc_dento_facial_anomaly = "NO";
		} else {
			$loc_dento_facial_anomaly = $_POST['cb_dento_facial_anomaly'];
		}
      
      
		$query = "SELECT * FROM m_dental_patient_ohc_table_a ".
			"WHERE patient_id = $loc_patient_id AND consult_id = $loc_consult_id ";
      $result = mysql_query($query)
        or die("Couldn't execute query.");
		
		if(mysql_num_rows($result)) {
			$query = "UPDATE m_dental_patient_ohc_table_a SET ".
				"dental_caries = '$loc_dental_caries', ".
				"gingivitis_periodontal_disease = '$loc_gingivitis_periodontal_disease', ".
				"debris = '$loc_debris', ".
				"calculus = '$loc_calculus', ".
				"abnormal_growth = '$loc_abnormal_growth', ".
				"cleft_lip_palate = '$loc_cleft_lip_palate', ".
				"others = '$loc_others', ".
				"healthy_gums = '$loc_healthy_gums', ".
				"dento_facial_anomaly = '$loc_dento_facial_anomaly' ".
				"WHERE consult_id = $loc_consult_id AND patient_id = $loc_patient_id ";
		} else {
			$query = "INSERT INTO m_dental_patient_ohc_table_a ".
				"(patient_id, consult_id, date_of_oral, dental_caries, gingivitis_periodontal_disease, ".
				"debris, calculus, abnormal_growth, cleft_lip_palate, others, healthy_gums, ".
				"dento_facial_anomaly) VALUES($loc_patient_id, $loc_consult_id, '$loc_date_of_oral', ".
				"'$loc_dental_caries', '$loc_gingivitis_periodontal_disease', '$loc_debris', ".
				"'$loc_calculus', '$loc_abnormal_growth', '$loc_cleft_lip_palate', '$loc_others', ".
				"'$loc_healthy_gums', '$loc_dento_facial_anomaly')";
      }
		
      $result = mysql_query($query)
        or die("Couldn't execute query. ".mysql_error());
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 12, '09, JVTolentino
	// This function will add a new record to [m_dental_patient_ohc].
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function new_dental_condition($patient_id, $consult_id, $is_patient_pregnant, 
		$tooth_number, $tooth_condition, $date_of_oral, $dentist) {
		$query = "INSERT INTO `m_dental_patient_ohc` ".
			"(`patient_id`, `consult_id`, `is_patient_pregnant`, ".
			"`tooth_number`, `tooth_condition`, `date_of_oral`, `dentist`) ".
			"VALUES($patient_id, $consult_id, '$is_patient_pregnant', ".
			"$tooth_number, '$tooth_condition', '$date_of_oral', $dentist)"; 
		$result = mysql_query($query)
			or die("Couldn't add new dental condition to the database.".mysql_error());
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 12, '09, JVTolentino
	// This function will update a record in [m_dental_patient_ohc].
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function update_dental_condition($consult_id, $tooth_number, $tooth_condition, 
		$is_patient_pregnant, $dentist) {
		$query = "UPDATE m_dental_patient_ohc ".
			"SET tooth_number = $tooth_number, ".
			"tooth_condition = '$tooth_condition', ".
			"is_patient_pregnant = '$is_patient_pregnant', ".
			"dentist = $dentist ".
			"WHERE consult_id = $consult_id AND ".
			"tooth_number = $tooth_number ";
		$result = mysql_query($query)
			or die("Couldn't update the record on the database.");
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 12, '09, JVTolentino
	// This function will delete a record in [m_dental_patient_ohc].
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function delete_dental_condition($consult_id, $tooth_number) {
		$query = "DELETE FROM m_dental_patient_ohc ".
			"WHERE tooth_number = $tooth_number AND ".
			"consult_id = $consult_id ";
		$result = mysql_query($query)
			or die("Couldnt' delete record.");
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
   // Comment date: Oct 21, '09, JVTolentino
	// Initial codes for inserting records.
	//
	// Comment date: Nov 12, '09, JVTolentino
	// Added new feature to this function. If the user left the 'tooth condition' blank, the record 
	//    pointed by tooth_number and consult_id will be deleted. This feature was added
	//    to give the user the ability to recover from mistakes when inputting a new record.
	//
	// Comment date: Nov 12, '09 (04:50PM), JVTolentino
	// Modularized this function a little bit. Created three new functions, i.e.
	//    new_dental_condition(), update_dental_condition(), delete_dental_condition(), and
	//    moved some of the codes from here to those three functions accordingly.
	//
	// Comment date: Nov 13, '09, JVTolentino
	// Added new feature to this function. If the user left the 'select service' blank, the record 
	//    pointed by tooth_number_for_service and consult_id will be deleted. This feature was 
	//    added to give the user the ability to recover from mistakes when inputting a new record.
	// Furthermore, the function new_dental_service_record() was also deleted and was
	//    replaced by three new functions new_dental_service(), update_dental_service(),
	//    and delete_dental_service().
	//
	// Comment date: Dec 02, 2009, JVTolentino
	// Added additional codes for populating the table [m_dental_fhsis]. This table is used
	// 	for creating the dental report.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function new_dental_record() {
		// The following variables are used for inserting a new record in
		//    m_dental_patient_ohc.
		$loc_patient_id = $_POST['h_patient_id'];
		$loc_consult_id = $_POST['h_consult_id'];
		$loc_tooth_number = $_POST['select_tooth'];
		$loc_tooth_condition = $_POST['select_condition'];
		$loc_date_of_oral = $_POST['date_of_oral'];
		list($month, $day, $year) = explode("/", $_POST['date_of_oral']);
		$loc_date_of_oral = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
		$loc_patient_pregnant = mc::check_if_pregnant($loc_patient_id, $loc_date_of_oral);
		$loc_dentist = $_POST['h_dentist'];
		
		
		// The following variables are used for inserting a new record in [m_dental_services].
		$loc_tooth_for_service = $_POST['select_tooth_for_service'];
		$loc_service = $_POST['select_tooth_service'];
		
		
		if(@$_POST['submit_button'] == "Save OHC(A)") {
			$this->new_ohc_table_a_record();
		} 
		
		// The following codes will be used to add or modify a record in
		//    m_dental_services.
		// 
		// Comment date: Dec 01, '09, JVTolentino
		// Added additional codes in here. It came to light that if the dentist provided
		// 	a service to a patient, the service should also reflect to the patient's
		// 	dental condition([m_dental_ohc]). I need to check this notion out with 
		// 	Dr. Domingo. I'll comment further if this condition is needed, otherwise
		// 	I will delete this comment later (if this comment is not deleted, it may just mean
		// 	that I have spoken with Dr. Domingo and I've forgotten to delete this
		// 	comment ^^).
		elseif (@$_POST['submit_button'] == "Save Service Provided")  {
			if($loc_service == '0') {
				$this->delete_dental_service($loc_consult_id, $loc_tooth_for_service);
			} else {
				$query = "SELECT * FROM m_dental_services WHERE ".
					"tooth_number = $loc_tooth_for_service AND ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query)
					or die("Couldn't ascertain if the record already exists in the database.");
			
				if(mysql_num_rows($result)) {
					$this->update_dental_service($loc_patient_id, $loc_consult_id, 
						$loc_tooth_for_service, $loc_service, $loc_date_of_oral, $loc_dentist);
				} else {
					$this->new_dental_service($loc_patient_id, $loc_consult_id, 
						$loc_tooth_for_service, $loc_service, $loc_date_of_oral, $loc_dentist);
				}
			}
		} 
		
		// The following codes will be used to add, modify, or delete a record in 
		//    m_dental_patient_ohc.
		elseif (@$_POST['submit_button'] == "Save Tooth Condition") {
			if($loc_tooth_condition == '0') {
				$this->delete_dental_condition($loc_consult_id, $loc_tooth_number);
			} else {
				$query = "SELECT * FROM m_dental_patient_ohc ".
					"WHERE tooth_number = $loc_tooth_number AND ".
					"consult_id = $loc_consult_id ";
				$result = mysql_query($query)
					or die ("Couldn't recognize if the record exists or not in the database. ".mysql_error());
					
				if(mysql_num_rows($result)) {
					$this->update_dental_condition($loc_consult_id, $loc_tooth_number, 
						$loc_tooth_condition, $loc_patient_pregnant, $loc_dentist);
				} else {
					$this->new_dental_condition($loc_patient_id, $loc_consult_id, $loc_patient_pregnant, 
						$loc_tooth_number, $loc_tooth_condition, $loc_date_of_oral, $loc_dentist);
				}
					
				$result = mysql_query($query)
					or die ("Couldn't execute query. ".mysql_error());
			}
		}
		
		// The following codes will be used to add or modify a record in
		//    m_dental_other_services.
		elseif (@$_POST['submit_button'] == "Save Other Dental Services")  {
			$query = "SELECT * FROM m_dental_other_services WHERE ".
				"patient_id = $loc_patient_id AND consult_id = $loc_consult_id ";
			$result = mysql_query($query);
			
			if($_POST['cb_supervised_tooth_brushing'] == "YES") {
				$loc_supervised_tooth_brushing = $_POST['cb_supervised_tooth_brushing'];
			} else {
				$loc_supervised_tooth_brushing = "NO";
			}
			
			if($_POST['cb_altraumatic_restorative_treatment'] == "YES") {
				$loc_altraumatic_restorative_treatment = 
					$_POST['cb_altraumatic_restorative_treatment'];
			} else {
				$loc_altraumatic_restorative_treatment = "NO";
			}
			
			if($_POST['cb_out_removal_of_unsavable_teeth'] == "YES") {
				$loc_out_removal_of_unsavable_teeth = $_POST['cb_out_removal_of_unsavable_teeth'];
			} else {
				$loc_out_removal_of_unsavable_teeth = "NO";
			}
			
			if($_POST['cb_out_referral_of_complicates_cases'] == "YES") {
				$loc_out_referral_of_complicates_cases = 
					$_POST['cb_out_referral_of_complicates_cases'];
			} else {
				$loc_out_referral_of_complicates_cases = "NO";
			}
			
			if($_POST['cb_out_treatment_of_post_extraction_complications'] == "YES") {
				$loc_out_treatment_of_post_extraction_complications =
					$_POST['cb_out_treatment_of_post_extraction_complications'];
			} else {
				$loc_out_treatment_of_post_extraction_complications = "NO";
			}
			
			if($_POST['cb_out_drainage_of_localized_oral_abscess'] == "YES") {
				$loc_out_drainage_of_localized_oral_abscess = 
					$_POST['cb_out_drainage_of_localized_oral_abscess'];
			} else {
				$loc_out_drainage_of_localized_oral_abscess = "NO";
			}
			
			if($_POST['cb_education_and_counselling'] == "YES") {
				$loc_education_and_counselling = $_POST['cb_education_and_counselling'];
			} else {
				$loc_education_and_counselling = "NO";
			}
			
			if($_POST['cb_scaling'] == "YES") {
				$loc_scaling = $_POST['cb_scaling'];
			} else {
				$loc_scaling = "NO";
			}
			
			if($_POST['cb_gum_treatment'] == "YES") {
				$loc_gum_treatment = $_POST['cb_gum_treatment'];
			} else {
				$loc_gum_treatment = "NO";
			}
			
			
			if(mysql_num_rows($result)) {
				$this->update_dental_other_service($loc_patient_id, $loc_consult_id, $loc_date_of_oral, 
					$loc_dentist, $loc_supervised_tooth_brushing, $loc_altraumatic_restorative_treatment, 
					$loc_out_removal_of_unsavable_teeth, $loc_out_referral_of_complicates_cases, 
					$loc_out_treatment_of_post_extraction_complications, 
					$loc_out_drainage_of_localized_oral_abscess, $loc_education_and_counselling, 
					$loc_scaling, $loc_gum_treatment);
			}
			else {
				$this->new_dental_other_service($loc_patient_id, $loc_consult_id, $loc_date_of_oral, 
					$loc_dentist, $loc_supervised_tooth_brushing, $loc_altraumatic_restorative_treatment, 
					$loc_out_removal_of_unsavable_teeth, $loc_out_referral_of_complicates_cases, 
					$loc_out_treatment_of_post_extraction_complications, 
					$loc_out_drainage_of_localized_oral_abscess, $loc_education_and_counselling, 
					$loc_scaling, $loc_gum_treatment);
			}
		}
		
		$loc_patient_age = healthcenter::get_patient_age($_GET['consult_id']);
		$loc_patient_gender = $this->get_patient_gender($loc_patient_id);
		
		// Refer to FHSIS Manual for Dental Indicators
		if ((($loc_patient_age * 12) >= 12.00) &&  (($loc_patient_age * 12) <=71.99)) {
			$this->fhsis_indicator_1($loc_consult_id, $loc_patient_id, $loc_date_of_oral, 
				$loc_patient_age, $loc_patient_gender);
			$this->fhsis_indicator_2($loc_consult_id, $loc_patient_id, $loc_date_of_oral, 
				$loc_patient_age, $loc_patient_gender);
		}
		
		if(($loc_patient_age >= 10.00) && ($loc_patient_age <= 24.99)) {
			$this->fhsis_indicator_3($loc_consult_id, $loc_patient_id, $loc_date_of_oral, 
				$loc_patient_age, $loc_patient_gender);
		}
		
		if($loc_patient_pregnant == 'Y') {
			$this->fhsis_indicator_4($loc_consult_id, $loc_patient_id, $loc_date_of_oral, 
				$loc_patient_age, $loc_patient_gender);
		}
		
		if (($loc_patient_age) >= 60.00) {
			$this->fhsis_indicator_5($loc_consult_id, $loc_patient_id, $loc_date_of_oral, 
				$loc_patient_age, $loc_patient_gender);
		}
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
    // Comment date: Nov 04, '09, JVTolentino
    // This function will query m_lib_dental_tooth_condition and show tooth condition
    //    legends.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function show_tooth_legends() {
      echo "<table border=3 bordercolor=#009900# align='center' width=500>";
        echo "<tr>";
          echo "<th align='left' bgcolor='CC9900' colspan=3>Tooth Condition Legends</th>";
        echo "</tr>";
        
        echo "<tr>";
          echo "<td colspan=2>Capital letters shall be used for recording the condition of permanent".
            " dentition and small letters for the status of temporary dentition.</td>";
        echo "</tr>";
        
        echo "<tr>";
          echo "<td align='center'><i>Legend</i></td>";
          echo "<td align='center'><i>Tooth Condition</i></td>";
        echo "</tr>";
        

        $query = "SELECT * FROM m_lib_dental_tooth_condition ORDER BY legend"; 
        $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
          extract($row);
            echo "<tr>";
              echo "<td align='center'>$condition</td>";
              echo "<td align='center'>$legend</td>";
            echo "</tr>";
          }
		
		echo "<tr>";
          echo "<th align='left' bgcolor='CC9900' colspan=3>Services Monitoring Legends</th>";
        echo "</tr>";
		
		echo "<tr>";
          echo "<td align='center'><i>Service</i></td>";
          echo "<td align='center'><i>Legend</i></td>";
        echo "</tr>";
        

        $query = "SELECT * FROM m_lib_dental_services ORDER BY legend"; 
        $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
          extract($row);
            echo "<tr>";
              echo "<td align='center'>$service</td>";
              echo "<td align='center'>$legend</td>";
            echo "</tr>";
          }
		
		
      echo "</table>";
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
	// Comment date: Nov 04, '09, JVTolentino
	// This function is used to create a table for the patient's
	// Oral Health Condition (A).
	//
	// Comment date: Dec 01, '09, JVTolentino
	// This came to my attention after reviewing the data dictionary for dental health care:
	// Indicator: Oraly Fit Children (12-71 months old) (disaggregated by sex)
	// Definition: Proportion of children 12 to 71 months old and are orally fit during a given point
	//    in time.
	// Definition of Terms: Orally Fit Children - refers to children who meet ALL of the following
	//    upon oral examination and/or completion of treatment.
	//    1. Carries-free or decayed teeth filled
	// 	2. has healthy gums
	// 	3. no oral debris, and 
	// 	4. no dento-facial anomaly that limits normal function
	// To meet this indicator an additional field was added to m_dental_patient_ohc_table_a:
	// 	healthy_gums. Accordingly, codes were added to this function to meet this requirement.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function show_ohc_table_a($p_id) {
      echo "<table border=3 bordercolor='red' align='center'>";
        echo "<tr>";
          echo "<th align='left' bgcolor='CC9900' colspan=7>Oral Health Condition (A)</th>";
        echo "</tr>";
      
      
        echo "<tr>";
          echo "<td colspan=7>Check the box if the condition is present on patient.</td>";
        echo "</tr>";
        
        
			echo "<tr>";
				echo "<td>Date of Oral Examination</td>";
          
				$query = "SELECT date_of_oral FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query.");
					
				echo "<td>&nbsp;</td>";
          
				$ctr = 1;  
				while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
					extract($row);
					echo"<td align='center'>$date_of_oral</td>";
					$ctr++;
				}
			echo "</tr>";
			
			
			echo "<tr>";
				echo "<td>Healthy Gums</td>";
				echo "<td align='center'><input type='checkbox' name='cb_healthy_gums' ".
					"value='YES'></input></td>";
					
				$query = "SELECT healthy_gums FROM m_dental_patient_ohc_table_a ".
					"WHERE patient_id = $p_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldnt' execute query. ".mysql_error());
					
				$ctr = 1;
				while(($row = mysql_fetch_array($result)) && ($ctr <= 5)) {
					extract($row);
					echo "<td align='center'>$healthy_gums</td>";
					$ctr++;
				}
			echo "</tr>";
			
       
        echo "<tr>";
          echo "<td>Dental Caries</td>";
          echo "<td align='center'><input type='checkbox' name='cb_dental_caries' value='YES'></input></td>";
          
          $query = "SELECT dental_caries FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$dental_caries</td>";
            $ctr++;
          }
        echo "</tr>";
       
        
        echo "<tr>";
          echo "<td>Gingivitis/Periodontal Disease</td>";
          echo "<td align='center'><input type='checkbox' name='cb_gingivitis_periodontal_disease' value='YES'></input></td>";
          
          $query = "SELECT gingivitis_periodontal_disease FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$gingivitis_periodontal_disease</td>";
            $ctr++;
          }
        echo "</tr>";
        
        
        echo "<tr>";
          echo "<td>Debris</td>";
          echo "<td align='center'><input type='checkbox' name='cb_debris' value='YES'></input></td>";
          
          $query = "SELECT debris FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$debris</td>";
            $ctr++;
          }
        echo "</tr>";
        
        
        echo "<tr>";
          echo "<td>Calculus</td>";
          echo "<td align='center'><input type='checkbox' name='cb_calculus' value='YES'></input></td>";
          
          $query = "SELECT calculus FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$calculus</td>";
            $ctr++;
          }
        echo "</tr>";
        
        
        echo "<tr>";
          echo "<td>abnormal_growth</td>";
          echo "<td align='center'><input type='checkbox' name='cb_abnormal_growth' value='YES'></input></td>";
          
          $query = "SELECT abnormal_growth FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$abnormal_growth</td>";
            $ctr++;
          }
        echo "</tr>";
        
        
        echo "<tr>";
          echo "<td>Cleft Lip/Palate</td>";
          echo "<td align='center'><input type='checkbox' name='cb_cleft_lip_palate' value='YES'></input></td>";
          
          $query = "SELECT cleft_lip_palate FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$cleft_lip_palate</td>";
            $ctr++;
          }
        echo "</tr>";
        
        
        echo "<tr>";
          echo "<td>Others (supernumerary / mesiodens, etc.)</td>";
          echo "<td align='center'><input type='checkbox' name='cb_others' value='YES'></input></td>";
          
          $query = "SELECT others FROM m_dental_patient_ohc_table_a WHERE patient_id = $p_id ORDER BY consult_id DESC";
          $result = mysql_query($query)
            or die("Couldn't execute query.");
            
          $ctr = 1;
          while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
            extract($row);
            echo"<td align='center'>$others</td>";
            $ctr++;
          }
        echo "</tr>";
			
		 
			echo "<tr>";
				echo "<td>Dento-Facial Anomaly that Limits Normal Function</td>";
				echo "<td align='center'><input type='checkbox' name='cb_dento_facial_anomaly' ".
					"value='YES'></input></td>";
          
				$query = "SELECT dento_facial_anomaly FROM m_dental_patient_ohc_table_a ".
					"WHERE patient_id = $p_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
            
				$ctr = 1;
				while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
					extract($row);
					echo"<td align='center'>$dento_facial_anomaly</td>";
					$ctr++;
				}
			echo "</tr>";
        
        
        echo "<tr>";
          echo "<td align='center' colspan=7><input type='submit' name='submit_button' ".
			"value='Save OHC(A)'></input></td>";
        echo "</tr>";
      
      
      echo "</table>";
    
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 09, '09, JVTolentino
    // This function is used to get the patient's number of teeth present during a consultation.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function get_no_of_teeth_present($c_id, $dentition_status) {
		if($dentition_status == "PERMANENT") {
			$query = "SELECT COUNT(*) AS no_of_teeth_present FROM m_dental_patient_ohc ".
				"WHERE consult_id = $c_id AND tooth_condition <> 'M' AND ".
				"((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) ";
		} elseif ($dentition_status == "TEMPORARY") {
			$query = "SELECT COUNT(*) AS no_of_teeth_present FROM m_dental_patient_ohc ".
				"WHERE consult_id = $c_id AND tooth_condition <> 'e' AND ".
				"((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) ";
		}
		$result = mysql_query($query)
			or die("Couldn't execute query.");
		if($row = mysql_fetch_assoc($result)) {
			return $row['no_of_teeth_present'];
		}
	}
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 09, '09, JVTolentino
    // This function is used to get the patient's number of sound teeth during a consultation.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function get_no_of_sound_teeth($c_id, $dentition_status) {
		if($dentition_status == "PERMANENT") {
			$query = "SELECT COUNT(*) AS no_of_sound_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'Y' AND ".
				"((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) ";
		} elseif($dentition_status == "TEMPORARY") {
			$query = "SELECT COUNT(*) AS no_of_sound_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'y' AND ".
				"((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) ";
		}
		$result = mysql_query($query)
			or die("Couldn't execute query.");
		if($row = mysql_fetch_assoc($result)) {
			return $row['no_of_sound_teeth'];
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 09, '09, JVTolentino
    // This function is used to get the patient's number of decayed teeth during a consultation.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function get_no_of_decayed_teeth($c_id, $dentition_status) {
		if ($dentition_status == "PERMANENT") {
			$query = "SELECT COUNT(*) AS no_of_decayed_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'D' AND ".
				"((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) ";
		} elseif ($dentition_status == "TEMPORARY") {
			$query = "SELECT COUNT(*) AS no_of_decayed_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'd' AND ".
				"((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) ";
		}
		$result = mysql_query($query)
			or die("Couldn't execute query.");
		if($row = mysql_fetch_assoc($result)) {
			return $row['no_of_decayed_teeth'];
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 09, '09, JVTolentino
    // This function is used to get the patient's number of missing teeth during a consultation.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function get_no_of_missing_teeth($c_id, $dentition_status) {
		if ($dentition_status == "PERMANENT") {
			$query = "SELECT COUNT(*) AS no_of_missing_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'M' AND ".
				"((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) ";
		} elseif ($dentition_status == "TEMPORARY") {
			$query = "SELECT COUNT(*) AS no_of_missing_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'e' AND ".
				"((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) ";
		}
		$result = mysql_query($query)
			or die("Couldn't execute query.");
		if($row = mysql_fetch_assoc($result)) {
			return $row['no_of_missing_teeth'];
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 09, '09, JVTolentino
    // This function is used to get the patient's number of filled teeth during a consultation.
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function get_no_of_filled_teeth($c_id, $dentition_status) {
		if ($dentition_status == "PERMANENT") {
			$query = "SELECT COUNT(*) AS no_of_filled_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'F' AND ".
				"((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) ";
		} elseif ($dentition_status == "TEMPORARY") {
			$query = "SELECT COUNT(*) AS no_of_filled_teeth ".
				"FROM m_dental_patient_ohc WHERE consult_id = $c_id ".
				"AND tooth_condition = 'f' AND ".
				"((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) ";
		}
		$result = mysql_query($query)
			or die("Couldn't execute query.");
		if($row = mysql_fetch_assoc($result)) {
			return $row['no_of_filled_teeth'];
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    
    
    
    
    
    // Comment date: Nov 05, '09, JVTolentino
    // This function is used to create a table for the patient's
    // Oral Health Condition (B).
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function show_ohc_table_b($p_id) {
		$query = "SELECT DISTINCT consult_id FROM m_dental_patient_ohc WHERE ". 
			"patient_id = $p_id ".
			"ORDER BY consult_id DESC";
		$result = mysql_query($query)
			or die("Couldnt' execute query.");
		
		$total_consults = 0;
		while($row = mysql_fetch_array($result)) {
			extract($row);
			$patient_consults[$total_consults] = $consult_id;
			$total_consults++;
		}
		$total_consults--;
		
		echo "<table border=3 bordercolor='red' align='center'>";
			echo "<tr>";
				echo "<th align='left' bgcolor='CC9900' colspan=7>Oral Health Condition (B)</th>";
			echo "</tr>";
			
			// The following codes are used to show the last five consultation dates of the patient.
			echo "<tr>";
				echo "<td>Date of Oral Examination</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					$query = "SELECT DISTINCT date_of_oral FROM m_dental_patient_ohc ".
						"WHERE consult_id = ".$patient_consults[$i]." ";
					$result = mysql_query($query)
						or die("Couldn't query patient's dates of oral.");
					if($row = mysql_fetch_assoc($result)) {
						$date_of_oral[$i] = $row['date_of_oral'];
					}
					echo "<td align='center'>{$date_of_oral[$i]}</td>";
					
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of permanent teeth present.
			echo "<tr>";
				echo "<td>Number of Permanent Teeth Present</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_teeth_present($patient_consults[$i], 'PERMANENT')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of permanent sound teeth.
			echo "<tr>";
				echo "<td>Number of Permanent Sound Teeth</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_sound_teeth($patient_consults[$i], 'PERMANENT')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of permanent decayed teeth.
			echo "<tr>";
				echo "<td>Number of Decayed Teeth (D)</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_decayed_teeth($patient_consults[$i], 'PERMANENT')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of missing teeth.
			echo "<tr>";
				echo "<td>Number of Missing Teeth (M)</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_missing_teeth($patient_consults[$i], 'PERMANENT')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of permanent filled teeth.
			echo "<tr>";
				echo "<td>Number of Filled Teeth (F)</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_filled_teeth($patient_consults[$i], 'PERMANENT')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of DMF teeth.
			echo "<tr>";
				echo "<td>Total Number of DMF Teeth</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					$total_DMF_teeth = 0; 	//resets total DMF to 0
					$total_DMF_teeth = 
						$this->get_no_of_decayed_teeth($patient_consults[$i], 'PERMANENT') +
						$this->get_no_of_missing_teeth($patient_consults[$i], 'PERMANENT') +
						$this->get_no_of_filled_teeth($patient_consults[$i], 'PERMANENT');
					
					echo "<td align='center'>$total_DMF_teeth</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of temporary teeth present.
			echo "<tr>";
				echo "<td>Number of Temporary Teeth Present</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_teeth_present($patient_consults[$i], 'TEMPORARY')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of temporary sound teeth.
			echo "<tr>";
				echo "<td>Number of Temporary Sound Teeth</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_sound_teeth($patient_consults[$i], 'TEMPORARY')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of temporary decayed teeth.
			echo "<tr>";
				echo "<td>Number of Decayed Teeth (d)</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_decayed_teeth($patient_consults[$i], 'TEMPORARY')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of temporary filled teeth.
			echo "<tr>";
				echo "<td>Number of Filled Teeth (f)</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					echo "<td align='center'>".
						"{$this->get_no_of_filled_teeth($patient_consults[$i], 'TEMPORARY')}".
						"</td>";
				}
			echo "</tr>";
			
			// The following codes are used in obtatining the no. of df teeth.
			echo "<tr>";
				echo "<td>Total Number of df Teeth</td>";
				for($i=0; ($i<=$total_consults) && ($i<=5); $i++) {
					$total_df_teeth = 0; 	//resets total df to 0
					$total_df_teeth = 
						$this->get_no_of_decayed_teeth($patient_consults[$i], 'TEMPORARY') +
						$this->get_no_of_filled_teeth($patient_consults[$i], 'TEMPORARY');
					
					echo "<td align='center'>$total_df_teeth</td>";
				}
			echo "</tr>";
			
		echo "</table>";
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
    // The following function will be used for acquiring tooth condition
    //    [from m_dental_services].
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function tooth_service_acquired($tn, $c_id) {
		$query = "SELECT service_provided FROM m_dental_services ".
			"WHERE tooth_number = $tn AND consult_id = $c_id ";
		$result = mysql_query($query)
			or die ("Couldn't execute query.");
      
		if($row = mysql_fetch_assoc($result)) {
			return $row['service_provided'];
		} else {
			return "&nbsp;";
		}
    }
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
   // This function is used for the Services Monitoring Chart
	// Further comments will be added soon.
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function show_services_monitoring_chart($p_id) {
		print "<table border=3 bordercolor='red' align='center'>";
			print "<tr>";
				print "<th align='left' bgcolor='CC9900'>Set Dental Service Provided to Patient</th>";
			print "</tr>";
			
			echo "<tr>";
          echo "<td>";
            echo "<table align='center' border=0 cellspacing=0>";
              echo "<tr>";
                echo "<td width=200 align='left'>Select tooth number:</td>";
                echo "<td>";
                  echo "<select name='select_tooth_for_service'>";
                    for($i=11; $i<=18; $i++) {
                      echo "<option value=$i>$i</option>";
                    }
                
                    for($i=21; $i<=28; $i++) {
                      echo "<option value=$i>$i</option>";
                    }
                
                    for($i=31; $i<=38; $i++) {
                      echo "<option value=$i>$i</option>";
                    }
                
                    for($i=41; $i<=48; $i++) {
                      echo "<option value=$i>$i</option>";
                    }

                    for($i=51; $i<=55; $i++) {
                      echo "<option value=$i>$i</option>";
                    }
                
                    for($i=61; $i<=65; $i++) {
                      echo "<option value=$i>$i</option>";
                    }
                
                    for($i=71; $i<=75; $i++) {
                      echo "<option value=$i>$i</option>";
                    }
                
                    for($i=81; $i<=85; $i++) {
                      echo "<option value=$i>$i</option>";
                    }

                  echo "</select>";
                echo "</td>";
              echo "</tr>";
    
              echo "<tr>";
                echo "<td width=200 align='left'>Select service:</td>";
                
                // Comment date: Nov 10, '09, JVTolentino
                // The following codes will be used to propagate a list box which will show
                //    all the possible tooth services that a dentist can provide to a patient.
                // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                echo "<td>";
                  $query = "SELECT DISTINCT legend FROM m_lib_dental_services ORDER BY legend";
                  $result = mysql_query($query)
                    or die ("Couldn't execute query.");
                  
                  echo "<select name='select_tooth_service'>"; 
							echo "<option value='0'></option>";
							while ($row = mysql_fetch_array($result)) {
								extract($row);
								echo "<option value='$legend'>$legend</option>";
							}
                  echo "</select>";
                echo "</td>";     
                // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                
                echo "<td width=100 align='center'><input type='submit' name='submit_button'".
					"value='Save Service Provided'></input></td>";
              echo "</tr>";
    
            echo "</table>";
          echo "</td>";
        echo "</tr>";
		
		print "</table>";
		
		print "&nbsp;";
		
		print "<table border=3 bordercolor='009900' align='center'>";
			print "<tr>";
				print "<th align='left' bgcolor='CC9900'>Services Monitoring Chart</th>";
			print "</tr>";
			
			print "<tr>";
			print "<td>";
				$this->temp_upper_teeth_service_monitoring_chart($p_id);
			print "</td>";
		
			print "<tr>";
			print "<td>";
				$this->temp_lower_teeth_service_monitoring_chart($p_id);
			print "</td>";
		
			print "<tr>";
			print "<td>";
				$this->perm_upper_teeth_service_monitoring_chart($p_id);
			print "</td>";
		
			print "<tr>";
			print "<td>";
				$this->perm_lower_teeth_service_monitoring_chart($p_id);
			print "</td>";
		print "</table>";
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 03, 2009, JVTolentino
	// This function is used for showing the table 'Other Services' in the dental page.
	// This function was added after reviewing the FHSIS Manual (Data Dictionary, Indicator 2),
	// 	and decided to add another table on chits database (m_dental_other_services)
	// 	to satisfy Indicator #2.
	// I need to set up a meeting with Dr. Domingo later to get his suggestions. I will add 
	// 	additional comments after I talked with him (if no comments were added, again,
	// 	I may have forgotten ^^)
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function show_other_dental_services($patient_id) {
		print "<table border=3 bordercolor='red' align='center'>";
			print "<tr>";
				print "<th align='left' bgcolor='CC9900' colspan=7>Other Dental Services</th>";
			print "</tr>";
			
			print "<tr>";
				print "<td colspan=7>Check the box if service was provided to the patient.</td>";
			print "</tr>";
			
			
			print "<tr>";
				print "<td>Date of Oral Examination</td>";
				$query = "SELECT date_of_service FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC ";
				$result = mysql_query($query)
					or die("Couldn't execute qeury. ".mysql_error());
					
				print "<td>&nbsp;</td>";
				
				$ctr = 1;
				while(($row = mysql_fetch_array($result)) && ($ctr <=5)) {
					extract($row);
					print "<td align='center'>$date_of_service</td>";
					$ctr++;
				}
			print "</tr>";
			
			
			print "<tr>";
				print "<td>80% Attendance to Supervised Tooth Brushing</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_supervised_tooth_brushing' value='YES'></input></td>";
					
				$query = "SELECT supervised_tooth_brushing FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
					
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$supervised_tooth_brushing</td>";
					$ctr++;
				}
			print "</tr>";
			
			
			print "<tr>";
				print "<td>Altraumatic Restorative Treatment</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_altraumatic_restorative_treatment' value='YES'></input></td>";
					
				$query = "SELECT altraumatic_restorative_treatment FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
					
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$altraumatic_restorative_treatment</td>";
					$ctr++;
				}
			print "</tr>";
			
			
			print "<tr>";
				print "<td colspan=7>Oral Urgent Treatment</td>";
			print "</tr>";
			
			
			print "<tr>";
				print "<td>&nbsp;&nbsp;&nbsp;- Removal of Unsavable Teeth</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_out_removal_of_unsavable_teeth' value='YES'></input></td>";
					
				$query = "SELECT out_removal_of_unsavable_teeth FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
					
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$out_removal_of_unsavable_teeth</td>";
					$ctr++;
				}
			print "</tr>";
				
				
			print "<tr>";
				print "<td>&nbsp;&nbsp;&nbsp;- Referral of Complicates Cases</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_out_referral_of_complicates_cases' value='YES'></input></td>";
					
				$query = "SELECT out_referral_of_complicates_cases FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
					
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$out_referral_of_complicates_cases</td>";
					$ctr++;
				}
			print "</tr>"; 
			
			
			print "<tr>";
				print "<td>&nbsp;&nbsp;&nbsp;- Treatment of Post-Extraction Complications</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_out_treatment_of_post_extraction_complications' value='YES'></input></td>";
					
				$query = "SELECT out_treatment_of_post_extraction_complications ".
					"FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
				
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$out_treatment_of_post_extraction_complications</td>";
					$ctr++;
				}
			print "</tr>"; 
			
			
			print "<tr>";
				print "<td>&nbsp;&nbsp;&nbsp;- Drainage of Localized Oral Abscess</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_out_drainage_of_localized_oral_abscess' value='YES'></input></td>";
				
				$query = "SELECT out_drainage_of_localized_oral_abscess ".
					"FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
				
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$out_drainage_of_localized_oral_abscess</td>";
					$ctr++;
				}
			print "</tr>"; 
			
			
			print "<tr>";
				print "<td>Education and Counselling on health effects<br>".
					"&nbsp;&nbsp;&nbsp;of tobacco/smoking, diet, and oral hygiene</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_education_and_counselling' value='YES'></input></td>";
				
				$query = "SELECT education_and_counselling ".
					"FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
				
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$education_and_counselling</td>";
					$ctr++;
				}
			print "</tr>"; 
			
			
			print "<tr>";
				print "<td>Scaling</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_scaling' value='YES'></input></td>";
				
				$query = "SELECT scaling FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
				
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$scaling</td>";
					$ctr++;
				}
			print "</tr>"; 
			
			
			print "<tr>";
				print "<td>Gum Treatment</td>";
				print "<td align='center'><input type='checkbox' ".
					"name='cb_gum_treatment' value='YES'></input></td>";
				
				$query = "SELECT gum_treatment FROM m_dental_other_services ".
					"WHERE patient_id = $patient_id ORDER BY consult_id DESC";
				$result = mysql_query($query)
					or die("Couldn't execute query. ".mysql_error());
				
				$ctr = 1;
				while(($row=mysql_fetch_array($result)) && ($ctr <= 5)){
					extract($row);
					print "<td align='center'>$gum_treatment</td>";
					$ctr++;
				}
			print "</tr>"; 
			
			
			print "<tr>";
				print "<td align='center' colspan=7><input type='submit' name='submit_button' ".
					"value='Save Other Dental Services'></input></td>";
			print "</tr>";
		print "</table>";
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
	// This function is used to add a record to [m_dental_services].
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function new_dental_service($patient_id, $consult_id, $tooth_number, 
		$service_provided, $date_of_service, $dentist) {
			
		$query = "INSERT INTO m_dental_services (patient_id, consult_id, tooth_number, ".
			"service_provided, date_of_service, dentist) VALUES".
			"($patient_id, $consult_id, $tooth_number, '$service_provided', ".
			"'$date_of_service', $dentist)";
		
		$result = mysql_query($query)
			or die("Couldn't add new dental service to the database.".mysql_error());
	}
   // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 13, '09, JVTolentino
	// This function is used to modify a record in [m_dental_services].
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function update_dental_service($patient_id, $consult_id, $tooth_number, 
		$service_provided, $date_of_service, $dentist) {
		$query = "UPDATE m_dental_services SET ".
			"tooth_number = $tooth_number, ".
			"service_provided = '$service_provided', ".
			"date_of_service = '$date_of_service', ".
			"dentist = $dentist ".
			"WHERE patient_id = $patient_id AND ".
			"consult_id = $consult_id AND ".
			"tooth_number = $tooth_number ";
		
		$result = mysql_query($query)
			or die("Couldn't add new dental service to the database.");
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 13, '09, JVTolentino
	// This function is used to delete a record in [m_dental_services].
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function delete_dental_service($consult_id, $tooth_number) {
		$query = "DELETE FROM m_dental_services WHERE ".
			"consult_id = $consult_id AND ".
			"tooth_number = $tooth_number ";
		$result = mysql_query($query)
			or die("Couldnt' delete record.");
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 03, 2009, JVTolentino
	// This function is used to insert a record in [m_dental_other_services].
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function new_dental_other_service($patient_id, $consult_id, $date_of_service,
		$dentist, $supervised_tooth_brushing, $altraumatic_restorative_treatment,
		$out_removal_of_unsavable_teeth, $out_referral_of_complicates_cases,
		$out_treatment_of_post_extraction_complications, 
		$out_drainage_of_localized_oral_abscess, $education_and_counselling, 
		$scaling, $gum_treatment) {
			
		$query = "INSERT INTO m_dental_other_services (patient_id, consult_id, ".
			"date_of_service, dentist, supervised_tooth_brushing, ".
			"altraumatic_restorative_treatment, out_removal_of_unsavable_teeth, ".
			"out_referral_of_complicates_cases, out_treatment_of_post_extraction_complications, ".
			"out_drainage_of_localized_oral_abscess, education_and_counselling, scaling, ".
			"gum_treatment) VALUES".
			"($patient_id, $consult_id, '$date_of_service', $dentist, '$supervised_tooth_brushing', ".
			"'$altraumatic_restorative_treatment', '$out_removal_of_unsavable_teeth', ".
			"'$out_referral_of_complicates_cases', '$out_treatment_of_post_extraction_complications', ".
			"'$out_drainage_of_localized_oral_abscess', '$education_and_counselling', ".
			"'$scaling', '$gum_treatment')";
			
		$result = mysql_query($query)
			or die("Couldn't add new dental service to the database.".mysql_error());
	}
   // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 07, 2009, JVTolentino
	// This function is used to modify a record in [m_dental_other_services].
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function update_dental_other_service($patient_id, $consult_id, $date_of_service,
		$dentist, $supervised_tooth_brushing, $altraumatic_restorative_treatment,
		$out_removal_of_unsavable_teeth, $out_referral_of_complicates_cases,
		$out_treatment_of_post_extraction_complications, 
		$out_drainage_of_localized_oral_abscess, $education_and_counselling,
		$scaling, $gum_treatment) {
		$query = "UPDATE m_dental_other_services SET ".
			"date_of_service = '$date_of_service', ".
			"dentist = $dentist, ".
			"supervised_tooth_brushing = '$supervised_tooth_brushing', ".
			"altraumatic_restorative_treatment = '$altraumatic_restorative_treatment', ".
			"out_removal_of_unsavable_teeth = '$out_removal_of_unsavable_teeth', ".
			"out_referral_of_complicates_cases = '$out_referral_of_complicates_cases', ".
			"out_treatment_of_post_extraction_complications = ".
			"'$out_treatment_of_post_extraction_complications', ".
			"out_drainage_of_localized_oral_abscess = '$out_drainage_of_localized_oral_abscess', ".
			"education_and_counselling = '$education_and_counselling', ".
			"scaling = '$scaling', ".
			"gum_treatment = '$gum_treatment' ".
			"WHERE patient_id = $patient_id AND ".
			"consult_id = $consult_id ";
			
		$result = mysql_query($query)
			or die("Couldn't add new record to the database. ".mysql_error());
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
    // This function is used to display the services provided by the dentist to a patient.
	// Upper teeth (temporary).
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function temp_upper_teeth_service_monitoring_chart($p_id) {
		$query = "SELECT DISTINCT consult_id FROM m_dental_services WHERE patient_id = $p_id ".
			"AND tooth_number BETWEEN 50 AND 66 ORDER BY consult_id DESC";
		$result = mysql_query($query)
			or die("Couldnt' execute query.");
		
		$total_consults = 0;
		while($row = mysql_fetch_array($result)) {
			extract($row);
			$patient_consults[$total_consults] = $consult_id;
			$total_consults++;
		}
		$total_consults--;
		
		echo "<table border=3 bordercolor=#009900# align='center'>";
			echo "<tr>";
				echo "<td align='center'>Date</td>";
				for($loc_tooth_number=55; $loc_tooth_number>=51; $loc_tooth_number--) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
				for($loc_tooth_number=61; $loc_tooth_number<=65; $loc_tooth_number++) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
			echo "</tr>";
			
			for($i=0; $i<=$total_consults; $i++) {
				$c_id = $patient_consults[$i];
				$query = "SELECT * FROM `m_dental_services` ".
					"WHERE `consult_id` = $c_id ";
				$result = mysql_query($query)
					or die ("Couldn't execute query.");
      
				if($row = mysql_fetch_assoc($result)) {
					echo "<tr>";
						echo "<td align='center'>{$row['date_of_service']}</td>";
						for($loc_tooth_number=55; $loc_tooth_number>=51; $loc_tooth_number--) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
						for($loc_tooth_number=61; $loc_tooth_number<=65; $loc_tooth_number++) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
					echo "</tr>";
				}
			}
		echo "</table>";
	}
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
    // This function is used to display the services provided by the dentist to a patient.
	// Lower teeth (temporary).
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function temp_lower_teeth_service_monitoring_chart($p_id) {
		$query = "SELECT DISTINCT consult_id FROM m_dental_services WHERE patient_id = $p_id ".
			"AND tooth_number BETWEEN 70 AND 86 ORDER BY consult_id DESC";
		$result = mysql_query($query)
			or die("Couldnt' execute query.");
		
		$total_consults = 0;
		while($row = mysql_fetch_array($result)) {
			extract($row);
			$patient_consults[$total_consults] = $consult_id;
			$total_consults++;
		}
		$total_consults--;
		
		echo "<table border=3 bordercolor=#009900# align='center'>";
			echo "<tr>";
				echo "<td align='center'>Date</td>";
				for($loc_tooth_number=85; $loc_tooth_number>=81; $loc_tooth_number--) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
				for($loc_tooth_number=71; $loc_tooth_number<=75; $loc_tooth_number++) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
			echo "</tr>";
			
			for($i=0; $i<=$total_consults; $i++) {
				$c_id = $patient_consults[$i];
				$query = "SELECT * FROM `m_dental_services` ".
					"WHERE `consult_id` = $c_id ";
				$result = mysql_query($query)
					or die ("Couldn't execute query.");
      
				if($row = mysql_fetch_assoc($result)) {
					echo "<tr>";
						echo "<td align='center'>{$row['date_of_service']}</td>";
						for($loc_tooth_number=85; $loc_tooth_number>=81; $loc_tooth_number--) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
						for($loc_tooth_number=71; $loc_tooth_number<=75; $loc_tooth_number++) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
					echo "</tr>";
				}
			}
		echo "</table>";
	}
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
    // This function is used to display the services provided by the dentist to a patient.
	// Upper teeth (permanent).
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function perm_upper_teeth_service_monitoring_chart($p_id) {
		$query = "SELECT DISTINCT consult_id FROM m_dental_services WHERE patient_id = $p_id ".
			"AND tooth_number BETWEEN 10 AND 29 ORDER BY consult_id DESC";
		$result = mysql_query($query)
			or die("Couldnt' execute query.");
		
		$total_consults = 0;
		while($row = mysql_fetch_array($result)) {
			extract($row);
			$patient_consults[$total_consults] = $consult_id;
			$total_consults++;
		}
		$total_consults--;
		
		echo "<table border=3 bordercolor=#009900# align='center'>";
			echo "<tr>";
				echo "<td align='center'>Date</td>";
				for($loc_tooth_number=18; $loc_tooth_number>=11; $loc_tooth_number--) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
				for($loc_tooth_number=21; $loc_tooth_number<=28; $loc_tooth_number++) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
			echo "</tr>";
			
			for($i=0; $i<=$total_consults; $i++) {
				$c_id = $patient_consults[$i];
				$query = "SELECT * FROM `m_dental_services` ".
					"WHERE `consult_id` = $c_id ";
				$result = mysql_query($query)
					or die ("Couldn't execute query.");
      
				if($row = mysql_fetch_assoc($result)) {
					echo "<tr>";
						echo "<td align='center'>{$row['date_of_service']}</td>";
						for($loc_tooth_number=18; $loc_tooth_number>=11; $loc_tooth_number--) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
						for($loc_tooth_number=21; $loc_tooth_number<=28; $loc_tooth_number++) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
					echo "</tr>";
				}
			}
		echo "</table>";
	}
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 10, '09, JVTolentino
    // This function is used to display the services provided by the dentist to a patient.
	// Lower teeth (permanent).
    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function perm_lower_teeth_service_monitoring_chart($p_id) {
		$query = "SELECT DISTINCT consult_id FROM m_dental_services WHERE patient_id = $p_id ".
			"AND tooth_number BETWEEN 30 AND 49 ORDER BY consult_id DESC";
		$result = mysql_query($query)
			or die("Couldnt' execute query.");
		
		$total_consults = 0;
		while($row = mysql_fetch_array($result)) {
			extract($row);
			$patient_consults[$total_consults] = $consult_id;
			$total_consults++;
		}
		$total_consults--;
		
		echo "<table border=3 bordercolor=#009900# align='center'>";
			echo "<tr>";
				echo "<td align='center'>Date</td>";
				for($loc_tooth_number=48; $loc_tooth_number>=41; $loc_tooth_number--) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
				for($loc_tooth_number=31; $loc_tooth_number<=38; $loc_tooth_number++) {
					echo "<td align='center'><b>$loc_tooth_number</b></td>";
				}
			echo "</tr>";
			
			for($i=0; $i<=$total_consults; $i++) {
				$c_id = $patient_consults[$i];
				$query = "SELECT * FROM `m_dental_services` ".
					"WHERE `consult_id` = $c_id ";
				$result = mysql_query($query)
					or die ("Couldn't execute query.");
      
				if($row = mysql_fetch_assoc($result)) {
					echo "<tr>";
						echo "<td align='center'>{$row['date_of_service']}</td>";
						for($loc_tooth_number=48; $loc_tooth_number>=41; $loc_tooth_number--) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
						for($loc_tooth_number=31; $loc_tooth_number<=38; $loc_tooth_number++) {
							echo "<td align='center'>".
								"{$this->tooth_service_acquired($loc_tooth_number, $c_id)}".
								"</td>";
						}
					echo "</tr>";
				}
			}
		echo "</table>";
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Nov 12, '09, JVTolentino
	// This function will check if the patient is pregnant and show a message if the patient is.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function show_message_if_patient_is_pregnant($p_id, $consultation_date) {
		if(mc::check_if_pregnant($p_id, $consultation_date) == 'Y') {
			print "<table border=3 bordercolor='red' align='center'>";
				print "<tr>";
					print "<th align='left' bgcolor='yellow'> ".
						"Advisory: According to our records, as of {$consultation_date}, ".
						"your patient is PREGNANT.</th>";
				print "</tr>";
			print "</table>";
		} 
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 07, 2009, JVTolentino
	// This function will add a new record to [m_dental_fhsis].
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function new_fhsis_record($consult_id, $patient_id, $indicator, $indicator_qualified, 
		$date_of_consultation, $age, $gender) {
		$query = "INSERT INTO m_dental_fhsis (consult_id, patient_id, indicator, ".
			"indicator_qualified, date_of_consultation, age, gender) VALUES ".
			"($consult_id, $patient_id, $indicator, '$indicator_qualified', ".
			"'$date_of_consultation', $age, '$gender')";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 07, 2009, JVTolentino
	// This function will modify a record to [m_dental_fhsis] based on the fields 
	// consult_id and indicator.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function update_fhsis_record($consult_id, $indicator, $indicator_qualified, 
		$date_of_consultation, $age) {
		$query = "UPDATE m_dental_fhsis SET ".
			"indicator_qualified = '$indicator_qualified', ".
			"date_of_consultation = '$date_of_consultation', ".
			"age = $age ".
			"WHERE consult_id = $consult_id AND ".
			"indicator = $indicator ";
		$result = mysql_query($query) 
			or die("Couldn't update record. ".mysql_error());
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 01, 2009, JVTolentino 
	// Prototype code for FHSIS Indicator #1. See FHSIS Manual Data Dictionary.
	// Indicator:
	//		Orally Fit Children (12-71 months old) (disaggregated by sex).
	//	Definition:
	//		Proportion of children 12 to 71 months old and are orally fit during a given point in time.
	// Definition of Terms:
	//		Orally Fit Children - refers to children who meet ALL of the following upon oral examination
	//			and/or completion of treatment:
	//				1.a caries-free or 
	//				1.b decayed teeth filled
	//				2. has healthy gums
	//				3. no oral debris, and 
	//				4. no dento-facial anomaly that limits normal functions.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function fhsis_indicator_1($consult_id, $patient_id, $date_of_consultation, $age, 
		$gender) {
		// CRITERIA:
		//		orally fit = 1, not orally fit = 0.
		$query = "SELECT * FROM m_dental_patient_ohc_table_a ".
			"WHERE consult_id = $consult_id AND (dental_caries = 'YES' OR ".
			"debris = 'YES' OR healthy_gums = 'NO' OR dento_facial_anomaly = 'YES')";
			
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria1a234 = 0;
		} else {
			$criteria1a234 = 1;
		}
		
		$query = "SELECT * FROM m_dental_patient_ohc ".
			"WHERE consult_id = $consult_id AND (tooth_condition = 'D' OR ".
			"tooth_condition = 'd') ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria1b = 0;
		} else {
			$criteria1b = 1;
		}
		
		$indicator = 1;
		if (($criteria1a234 == 1) && ($criteria1b == 1)) {
			$indicator_qualified = "YES";
		} else {
			$indicator_qualified = "NO";
		}
		
		$query = "SELECT * FROM m_dental_fhsis WHERE ".
			"consult_id = $consult_id AND indicator = $indicator";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
		
		if(mysql_num_rows($result)) {
			//update based on criteria
			$this->update_fhsis_record($consult_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age);
		} else {
			// insert record
			$this->new_fhsis_record($consult_id, $patient_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age, $gender);
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 03, 2009, JVTolentino
	// Indicator:
	// 	Children 12-71 months old provided with Basic Oral Health Care (BOHC)
	// 	(disaggregated by sex)
	// Definition:
	// 	Proportion of children whose ages ranges from 12 to 71 months old and 
	// 	were provided with Basic Oral Health Care (BOHC).
	// Definition of Terms:
	// Basic Oral Health Care provided to children 12-71 months old - refers to one or
	// 	or more of the following services:
	// 	1. Oral Examination
	//		2. 80% Attendance to Supervised Tooth Brushing
	// 	3. Altraumatic Restorative Treatment (ART)
	// 	4. Oral Urgent Treatment (OUT)
	// 		4a. removal of unsavable teeth, or
	//			4b. referral of complicates cases, or 
	//			4c. treatment of post-extraction complications,
	// 		4d. drainage of localized oral abscess.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function fhsis_indicator_2($consult_id, $patient_id, $date_of_consultation, $age, 
		$gender) {
		// CRITERIA:
		//		provided BOHC = 1, not provided BOHC = 0
		
		//criteria 2,3, and 4.
		$query = "SELECT * FROM m_dental_other_services ".
			"WHERE consult_id = $consult_id AND ".
			"(supervised_tooth_brushing = 'YES' OR ".
			"altraumatic_restorative_treatment = 'YES' OR ".
			"out_removal_of_unsavable_teeth = 'YES' OR ".
			"out_referral_of_complicates_cases = 'YES' OR ".
			"out_treatment_of_post_extraction_complications = 'YES' OR ".
			"out_drainage_of_localized_oral_abscess = 'YES')";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria234 = 1;
		} else {
			$criteria234 = 0;
		}
		
		$indicator = 2;
		if($criteria234 == 1) {
			$indicator_qualified = "YES";
		} else {
			$indicator_qualified = "NO";
		}
		
		$query = "SELECT * FROM m_dental_fhsis WHERE ".
			"consult_id = $consult_id AND indicator = $indicator ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
		
		if(mysql_num_rows($result)) {
			//update based on criteria
			$this->update_fhsis_record($consult_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age);
		} else {
			// insert record
			$this->new_fhsis_record($consult_id, $patient_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age, $gender);
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 08, 2009, JVTolentino
	// Indicator:
	// 	Adolescent and Youth (10-24 years old) provided with Basic Oral Health Care (BOHC)
	//		(disaggregated by sex).
	// Definition:
	// 	Proportion of adolescents and youth whose ages ranges from 10 to 24 years old
	// 	and were provided with Basic Oral Health Care (BOHC).
	// Definition of Terms:
	// 	Basic Oral Health Care (BOHC) provided to Adolescents and Youth (10-24 years old) -
	//		refers to one or more of the following services:
	// 	1. Oral Examination
	//		2. Education and counselling on health effects of tobacco/smoking, diet, and
	//			oral hygiene.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function fhsis_indicator_3($consult_id, $patient_id, $date_of_consultation, $age, $gender) {
		// CRITERIA:
		//		provided BOHC = 1, not provided BOHC = 0
		//criteria 2
		$query = "SELECT * FROM m_dental_other_services ".
			"WHERE consult_id = $consult_id AND ".
			"education_and_counselling = 'YES' ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria2 = 1;
		} else {
			$criteria2 = 0;
		}
		
		$indicator = 3;
		if($criteria2 == 1) {
			$indicator_qualified = "YES";
		} else {
			$indicator_qualified = "NO";
		}
		
		$query = "SELECT * FROM m_dental_fhsis WHERE ".
			"consult_id = $consult_id AND indicator = $indicator ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
		
		if(mysql_num_rows($result)) {
			//update based on criteria
			$this->update_fhsis_record($consult_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age);
		} else {
			// insert record
			$this->new_fhsis_record($consult_id, $patient_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age, $gender);
		}
		
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 09, 2009, JVTolentino
	// Indicator:
	// 	Pregnant Women provided with Basic Oral Health Care (BOHC)
	// Definition:
	// 	Proportion of pregnant women who were provided with Basic Oral Health Care (BOHC)
	// Definition of Terms:
	// 	Basic Oral Health Care (BOHC) provided to Pregnant Women - refers to one or more
	// 		of the following services:
	// 	1. Oral Examination
	//		2. Scaling
	// 	3. Permanent Filling
	// 	4. Gum Treatment
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function fhsis_indicator_4($consult_id, $patient_id, $date_of_consultation, $age, $gender) {
		// CRITERIA:
		//		provided BOHC = 1, not provided BOHC = 0
		
		// criteria 2 and 4
		$query = "SELECT * FROM m_dental_other_services ".
			"WHERE consult_id = $consult_id AND ".
			"(scaling = 'YES' OR gum_treatment = 'YES')";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria24 = 1;
		} else {
			$criteria24 = 0;
		}
		
		// criteria 3
		$query = "SELECT * FROM m_dental_services ".
			"WHERE consult_id = $consult_id AND service_provided = 'PF'";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria3 = 1;
		} else {
			$criteria3 = 0;
		}
		
		$indicator = 4;
		if(($criteria24 == 1) OR ($criteria3 == 1)) {
			$indicator_qualified = "YES";
		} else {
			$indicator_qualified = "NO";
		}
		
		$query = "SELECT * FROM m_dental_fhsis WHERE ".
			"consult_id = $consult_id AND indicator = $indicator ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
		
		if(mysql_num_rows($result)) {
			//update based on criteria
			$this->update_fhsis_record($consult_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age);
		} else {
			// insert record
			$this->new_fhsis_record($consult_id, $patient_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age, $gender);
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 10, 2009, JVTolentino
	// Indicator:
	// 	Older Persons 60 years old and above provided with Basic Oral Health Care (BOHC)
	//		(disaggregated by sex)
	// Definition:
	// 	Proportion of Older Persons 60 years old and above who were provided with 
	//		Basic Oral Health Care (BOHC)
	// Definition of Terms:
	// 	Basic Oral Health Care (BOHC) provided to Older Persons - refers to one or more
	// 		of the following services:
	// 	1. Oral Examination
	//		2. Extraction
	//		3. Gum Treatment
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function fhsis_indicator_5($consult_id, $patient_id, $date_of_consultation, $age, $gender) {
		// CRITERIA:
		//		provided BOHC = 1, not provided BOHC = 0
		
		// criteria 2
		$query = "SELECT * FROM m_dental_services ".
			"WHERE consult_id = $consult_id AND service_provided = 'X'";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria2 = 1;
		} else {
			$criteria2 = 0;
		}
		
		// criteria 3
		$query = "SELECT * FROM m_dental_other_services ".
			"WHERE consult_id = $consult_id AND gum_treatment = 'YES'";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if(mysql_num_rows($result)) {
			$criteria3 = 1;
		} else {
			$criteria3 = 0;
		}
		
		$indicator = 5;
		if(($criteria2 == 1) OR ($criteria3 == 1)) {
			$indicator_qualified = "YES";
		} else {
			$indicator_qualified = "NO";
		}
		
		$query = "SELECT * FROM m_dental_fhsis WHERE ".
			"consult_id = $consult_id AND indicator = $indicator ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
		
		if(mysql_num_rows($result)) {
			//update based on criteria
			$this->update_fhsis_record($consult_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age);
		} else {
			// insert record
			$this->new_fhsis_record($consult_id, $patient_id, $indicator, $indicator_qualified, 
				$date_of_consultation, $age, $gender);
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
	// Comment date: Dec 02, 2009, JVTolentino
	// This function will query m_patient and get the patient's gender based on the patient's
	// 	id.
	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function get_patient_gender($patient_id) {
		$query = "SELECT patient_gender FROM m_patient WHERE patient_id = $patient_id ";
		$result = mysql_query($query)
			or die("Couldn't execute query. ".mysql_error());
			
		if($row = mysql_fetch_assoc($result)) {
			return $row['patient_gender'];
		}
	}
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	
	
   // Comment date: Nov 04, '09, JVTolentino
   // This is the main function for the dental module.
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   function _consult_dental() {
      echo "<form name='form_dental' action='$_POST[PHP_SELF]' method='POST'>";
      
      $dental = new dental;
      
      $dental->toothnumber = 0;
      $dental->condition[$dental->toothnumber] = 'Y';
      $dental->consult_id = $_GET['consult_id'];
      $dental->patient_id = healthcenter::get_patient_id($_GET['consult_id']);
      $dental->patient_age = healthcenter::get_patient_age($_GET['consult_id']);
      $dental->dentist = $_SESSION['userid'];
      
      // The following codes will initialize hidden textboxes and their values
      echo "<input type='hidden' name='h_patient_id' value='{$dental->patient_id}'></input>";
      echo "<input type='hidden' name='h_consult_id' value='{$dental->consult_id}'></input>";
      echo "<input type='hidden' name='h_dentist' value='{$dental->dentist}'></input>";
      
      if (@$_POST['h_save_flag'] == 'GO') {
        $dental->new_dental_record();
		
			print "&nbsp;";
			$dental->show_message_if_patient_is_pregnant($dental->patient_id, date("Y-m-d"));
        
        echo "&nbsp;";
        $dental->show_date_of_oral();
			
        $dental->get_teeth_conditions($dental->patient_age);
			
        echo "&nbsp;";
        $dental->select_tooth_and_condition($dental->patient_age);
			
        echo "&nbsp;";
        $dental->show_teeth_conditions($dental->patient_age);
        
        echo "&nbsp;";
        $dental->show_ohc_table_a($dental->patient_id);
        
        echo "&nbsp;";
        $dental->show_ohc_table_b($dental->patient_id);
			
			echo "&nbsp;";
			$dental->show_services_monitoring_chart($dental->patient_id);
			
			print "&nbsp;";
			$dental->show_other_dental_services($dental->patient_id);
        
			echo "&nbsp;";
			$dental->show_tooth_legends($dental->patient_age);
      } 
		else {
			print "&nbsp;";  
			$dental->show_message_if_patient_is_pregnant($dental->patient_id, date("Y-m-d"));
			
			echo "&nbsp;";
			$dental->show_date_of_oral();
			
			$dental->get_teeth_conditions($dental->patient_age);
			
			echo "&nbsp;";
			$dental->select_tooth_and_condition($dental->patient_age);
			
			echo "&nbsp;";
			$dental->show_teeth_conditions($dental->patient_age);
        
			echo "&nbsp;";
			$dental->show_ohc_table_a($dental->patient_id);
        
			echo "&nbsp;";
			$dental->show_ohc_table_b($dental->patient_id);
			
			echo "&nbsp;";
			$dental->show_services_monitoring_chart($dental->patient_id);
			
			print "&nbsp;";
			$dental->show_other_dental_services($dental->patient_id);
        
			echo "&nbsp;";
			$dental->show_tooth_legends($dental->patient_age);
      }
      
		echo "<input type='hidden' name='h_save_flag' value='GO'></input>";
		echo "</form>";
    
   }
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  } // class ends here
  
?>
