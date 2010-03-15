<?php

	session_start();

	ob_start();

	require('./fpdf/fpdf.php');

	$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
	mysql_select_db($_SESSION[dbname]);
	
	// Author: Jeffrey V. Tolentino
        // Report Version: 1.0
        // Description: Leprosy - Quarter Report (FHSIS)
        // Version Released: Feb 2010
	
	
	// Comment date: Jan 05, 2010, JVTolentino
   	// The following are member functions of the PDF class. These functions are needed
	// 	for the basic operations of the class. DO NOT DELETE OR MODIFY THESE FUNCTIONS
	//		UNLESS, OF COURSE, YOU KNOW WHAT YOU'RE DOING ^^.
   	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		// 1. function SetWidths($w)
		// 2. function SetAligns($a)
		// 3. function Row($data)
		// 4. function CheckPageBreak($h)
		// 5. function NbLines($w,$txt)
		// 6. function Header()
		// 7. function Footer()
		// 8. function show_leprosy_summary()  	>> the main function used in showing the report
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	
	
	
	class PDF extends FPDF {
		var $widths;
		var $aligns;
		var $page;	
		
		
		
		function SetWidths($w) {
			//Set the array of column widths
			$this->widths=$w;
		}
		
		
		
		function SetAligns($a) {
			//Set the array of column alignments
			$this->aligns=$a;
		}
		
		
		
		function Row($data) {
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++) {
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C'; //sets the alignment of text inside the cell
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h);
				//Print the text
				$this->MultiCell($w,5,$data[$i],0,$a);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		
		
	
		function CheckPageBreak($h) {
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}
		
		
		
		function NbLines($w,$txt) {
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb) {
				$c=$s[$i];
				if($c=="\n") {
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' ')
					$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax) {
					if($sep==-1) {
						if($i==$j)
							$i++;
					}
					else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}
				else
					$i++;
			}
			return $nl;
		}
		
		
		
		function Header() {
			$population = $this->get_brgy_pop();
			
			$this->q_report_header($population);
			$this->Ln(10);
			
			$this->SetFont('Arial','BI','20');
			$this->Cell(340,10,'National Leprosy Control Program',1,1,C);
			
			$this->SetFont('Arial','B','12');
			$w = array(80,30,30,30,30,70,70); //340
			$this->SetWidths($w);

			$label = array('Indicators', 
				'Number (Male)', 
				'Number (Female)', 
				'Number (Total)',
				'Rate', 
				'Interpretation',
				'Recommendation/Actions Taken');
			$this->Row($label);
		}
		
		
	
		function q_report_header($population) {
			$this->SetFont('Arial','B','12');
			$this->Cell(0,5,'FHSIS REPORT FOR THE QUARTER: '.$_SESSION[quarter]."          YEAR: ".$_SESSION[year],0,1,L);
			$this->Cell(0,5, 'MUNICIPALITY/CITY NAME: '.$_SESSION[datanode][name],0,1,L);
			$this->Cell(0,5,'PROVINCE: '.$_SESSION[province]."          PROJECTED POPULATION OF THE YEAR: ".$population,0,1,L);
		}
		
		
		
		function show_leprosy_quarterly(){
			$w = array(80,30,30,30,30,70,70);
			$str_brgy = $this->get_brgy();    
			$population = $this->get_brgy_pop();

			for($indicator_ctr = 1; $indicator_ctr <= 5; $indicator_ctr++) {
				$col2 = $this->get_data($_SESSION[sdate2], $_SESSION[edate2],
					$indicator_ctr, $str_brgy, 2);
				$col3 = $this->get_data($_SESSION[sdate2], $_SESSION[edate2], 
					$indicator_ctr, $str_brgy, 3);
				$col4 = $col2 + $col3;

				switch($indicator_ctr) {
					// Leprosy cases. 
					// Formula is: total cases / total population * 10,000
					case 1: 
						if(($col4 == 0) || ($population == 0)) {
							$col5 = 0;
						}
						else {
							$col5 = number_format((($col4 / $population) * 10000),2,'.','');
						}
						break;
					// Leprosy cases below 15 yrs old. 
					// Formula is: leprosy cases below 15 yrs old / total cases * 100
					case 2:
						$start = $_SESSION[sdate2];
						$end = $_SESSION[edate2];
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. How many 
							leprosy cases do i have? What is the cure 
							rate?

						solution: if the leprosy cases is 3 and the cure rate 
							is 1/3, use the following query.

						$query = "SELECT a.patient_id FROM m_leprosy_post_treatment a ".
							"INNER JOIN m_family_members b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_address c ON b.family_id = c.family_id ".
							"WHERE ((a.patient_cured = 'Undergoing Treatment') OR ".
							"((a.patient_cured = 'Completed') AND ".
							"(a.upon_tc_date >= '$start' AND a.upon_tc_date <= '$end'))) AND ".
							"c.barangay_id IN ($str_brgy) ";
						*/
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_family_members b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_address c ON b.family_id = c.family_id ".
							"WHERE (a.date_of_diagnosis >= '$start' AND ".
							"a.date_of_diagnosis <= '$end') AND ".
							"c.barangay_id IN ($str_brgy) ";
						$result = mysql_query($query) or die("Couldn't execute query.".mysql_error());
						$leprosy_cases = mysql_num_rows($result);

						if(($col4 == 0) || ($leprosy_cases == 0)) {
							$col5 = 0;
						}
						else {
							$col5 = number_format((($col4 / $leprosy_cases) * 100),2,'.','')."%";
						}
						break;
					// Newly detected leprosy cases. 
					// Formula is: newly detected leprosy cases / total population * 100,000
					case 3:
						if(($col4 == 0) || ($population == 0)) {
							$col5 = 0;
						}
						else {
							$col5 = number_format((($col4 / $population) * 100000),2,'.','');
						}
						break;
					// Newly detected case with Grade 2 disability. 
					// Formula is: newly detected leprosy case with grade 2 disability / total population * 100,000
					case 4:
						if(($col4 == 0) || ($population == 0)) {
							$col5 = 0;
						}
						else {
							$col5 = number_format((($col4 / $population) * 100000),2,'.','');
						}
						break;
					// Case cured. 
					// Formula is: case cured / leprosy cases * 100
					case 5:
						$start = $_SESSION[sdate2];
						$end = $_SESSION[edate2];
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. How many 
							leprosy cases do i have? What is the cure 
							rate?

						solution: if the leprosy cases is 4 and the cure rate 
							is 1/3, use the following query.
						$query = "SELECT a.patient_id FROM m_leprosy_post_treatment a ".
							"INNER JOIN m_family_members b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_address c ON b.family_id = c.family_id ".
							"WHERE ((a.patient_cured = 'Undergoing Treatment') OR ".
							"((a.patient_cured = 'Completed') AND ".
							"(a.upon_tc_date >= '$start' AND a.upon_tc_date <= '$end'))) AND ".
							"c.barangay_id IN ($str_brgy) ";
						*/
						$query = "SELECT a.patient_id FROM ".
							"m_leprosy_diagnosis a INNER JOIN m_family_members b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE (a.date_of_diagnosis >= '$start' AND ".
							"a.date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($str_brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query.");
						$leprosy_cases = mysql_num_rows($result);

						$col5 = $col4." / ".$leprosy_cases;
						
						if(($col4 == 0) || ($leprosy_cases == 0)) {
							$col5 = 0;
						}
						else {
							$col5 = number_format((($col4 / $leprosy_cases) * 100),2,'.','')."%";
						}
						break;
					default:
						break;
				}
				
				switch($indicator_ctr) {
					case 1:
						$indicator = "Leprosy cases?";
						break;
					case 2:
						$indicator = "Leprosy cases below 15 yrs old?";
						break;
					case 3:
						$indicator = "Newly detected Leprosy cases?";
						break;
					case 4:
						$indicator = "Newly detected cases with Grade 2 disability?";
						break;
					case 5:
						$indicator = "Case cured?";
						break;
					default:
						break;
				}

				$leprosy_contents = array("\n\n".$indicator."\n\n\n", "\n\n".$col2."\n\n\n", "\n\n".$col3."\n\n\n", "\n\n".$col4."\n\n\n", "\n\n".$col5."\n\n\n", '', '');
				$this->Row($leprosy_contents);
			}
		}



		function get_brgy_pop() {
                        list($taon,$buwan,$araw) = explode('-',$_SESSION[edate2]);
                        if(in_array('all',$_SESSION[brgy])):
                                $q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon'") or die("Cannot query: 286");
                        else:
                                $str = implode(',',$_SESSION[brgy]);
                                $q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon' AND barangay_id IN ($str)") or die("Cannot query: 372");
                        endif;

                        if(mysql_num_rows($q_brgy_pop)!=0):
                                list($populasyon) = mysql_fetch_array($q_brgy_pop);
                        endif;

                        return $populasyon;
                }
		
		
		
		function get_brgy(){  //returns the barangay is CSV format. to be used in WHERE clause for determining barangay residence of patient
			$arr_brgy = array();
			
			if(in_array('all',$_SESSION[brgy])):
				$q_brgy = mysql_query("SELECT barangay_id FROM m_lib_barangay ORDER by barangay_id ASC") 
					or die("Cannot query 252". mysql_error());
				while(list($brgy_id) = mysql_fetch_array($q_brgy)) {            
					array_push($arr_brgy,$brgy_id);
				}
			else:
				$arr_brgy = $_SESSION[brgy];
			endif;
			
			$str_brgy = implode(',',$arr_brgy);
			
			return $str_brgy;
        
		}       
		
		
		
		function get_data() {
			if(func_num_args() > 0) {
				$args = func_get_args();
				$start = $args[0];
				$end = $args[1];
				$indicator = $args[2];
				$brgy = $args[3];
				$col_code = $args[4];
			}
			
			
			// $col_code represents the column number on the report
			// Column 1 = Indicators
			// Column 2 = Number (Male)
			// Column 3 = Number (Female)
			// Column 4 = Number (Total)
			// Column 5 = Rate
			// Column 6 = Interpreation
			// Column 7 = Recommendation/Action Taken
			switch($col_code) {
				case '2':
					// Indicator #1: Leprosy Cases. 
					// Note: code finalized
					if($indicator == '1') {
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. How many 
							leprosy cases do i have?  

						solution: if the leprosy cases are 3 use the following query.

						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_leprosy_post_treatment b ON a.consult_id = b.consult_id ".
							"INNER JOIN m_patient c on b.patient_id = c.patient_id ".
							"INNER JOIN m_family_members d ON c.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
							"WHERE c.patient_gender = 'M' AND ".
							"((b.patient_cured = 'Undergoing Treatment') OR ".
							"(b.patient_cured = 'Completed' AND ".
							"(b.upon_tc_date >= '$start' AND b.upon_tc_date <= '$end'))) AND ".
							"e.barangay_id IN ($brgy) ";
						*/
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_patient b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'M' AND ".
							"(date_of_diagnosis >= '$start' AND ".
							"date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						$result = mysql_query($query) or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}
					
					// Indicator #2: Leprosy cases below 15 yrs old?
					// Note: code finalized
					if($indicator == '2') {
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
							x, y, and z are all less than 15 years old.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. How many 
							leprosy cases do i have?  

						solution: if the leprosy cases are 3 use the following query.

						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_leprosy_post_treatment b ON a.consult_id = b.consult_id ".
							"INNER JOIN m_patient c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_members d ON c.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
							"WHERE c.patient_gender = 'M' AND ".
							"a.patient_age < 15 AND ".
							"((b.patient_cured = 'Undergoing Treatment') OR ".
							"(b.patient_cured = 'Completed' AND ".
							"(b.upon_tc_date >= '$start' AND b.upon_tc_date <= '$end'))) AND ".
							"e.barangay_id IN ($brgy) ";
						*/
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_patient b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'M' AND ".
							"a.patient_age < 15 AND ".
							"(a.date_of_diagnosis >= '$start' AND ".
							"a.date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}

					// Indicator #3: Newly detected Leprosy cases?
					// Note: code finalized
					if($indicator == '3') {
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_patient b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'M' AND ".
							"a.patient_case = 'New Case' AND ".
							"(a.date_of_diagnosis >= '$start' AND ".
							"a.date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query. ");
						return mysql_num_rows($result);
						break;
					}

					// Indicator #4: Newly detected cases with Grade 2 disability?
					// Note: code finalized
					if($indicator == '4') {
						$query = "SELECT b.patient_id FROM m_patient a ".
							"INNER JOIN m_leprosy_diagnosis b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_leprosy_who_disability_grade c ON b.consult_id = c.consult_id ".
							"INNER JOIN m_family_members d ON b.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
							"WHERE b.patient_case = 'New Case' AND ".
							"a.patient_gender = 'M' AND ".
							"((c.who_disability = 'Maximum Grade' AND ".
                                                        "c.upon_dx_right = '2') OR ".
                                                        "(c.who_disability = 'Maximum Grade' AND ".
                                                        "c.upon_dx_left = '2')) AND ".
							"(b.date_of_diagnosis >= '$start' AND ".
							"b.date_of_diagnosis <= '$end') AND ".
							"e.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}
					// Indicator #5: Case cured?
					// Note: code finalized
					if($indicator == '5') {
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. What is the
							cure rate? 

						solution: if the cure rate is 1/3, use the following codes:
						$query = "SELECT a.patient_id FROM m_leprosy_post_treatment a ".
							"INNER JOIN m_patient b on a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'M' AND ".
							"a.patient_cured = 'Completed' AND ".
							"(a.upon_tc_date >= '$start' AND ".
							"a.upon_tc_date <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						*/
						$query = "SELECT b.patient_id FROM m_patient a ".
                                                        "INNER JOIN m_leprosy_diagnosis b ON a.patient_id = b.patient_id ".
                                                        "INNER JOIN m_leprosy_post_treatment c ON b.consult_id = c.consult_id ".
							"INNER JOIN m_family_members d ON b.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
                                                        "WHERE c.patient_cured = 'Completed' AND ".
                                                        "a.patient_gender = 'M' AND ".
							"(c.upon_tc_date >= '$start' AND ".
							"c.upon_tc_date <= '$end') AND ".
							"e.barangay_id IN ($brgy) ";
						$result = mysql_query($query) or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}
				case '3':
					// Indicator #1: Leprosy Cases. 
					// Note: code finalized
					if($indicator == '1') {
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. How many 
							leprosy cases do i have? What is the cure 
							rate?

						solution: if the leprosy cases is 4 and the cure rate 
							is 1/3, use the following query.
						
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_leprosy_post_treatment b ON a.consult_id = b.consult_id ".
							"INNER JOIN m_patient c on b.patient_id = c.patient_id ".
							"INNER JOIN m_family_members d ON c.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
							"WHERE c.patient_gender = 'F' AND ".
							"((b.patient_cured = 'Undergoing Treatment') OR ".
							"(b.patient_cured = 'Completed' AND ".
							"(b.upon_tc_date >= '$start' AND b.upon_tc_date <= '$end'))) AND ".
							"e.barangay_id IN ($brgy) ";
						*/
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_patient b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'F' AND ".
							"(date_of_diagnosis >= '$start' AND ".
							"date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}
					
					// Indicator #2: Leprosy cases below 15 yrs old?
					// Note: code finalized
					if($indicator == '2') {
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
							x, y, and z are all less than 15 years old.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. How many 
							leprosy cases do i have?  

						solution: if the leprosy cases are 3 use the following query.
						
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_leprosy_post_treatment b ON a.consult_id = b.consult_id ".
							"INNER JOIN m_patient c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_members d ON c.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
							"WHERE c.patient_gender = 'F' AND ".
							"a.patient_age < 15 AND ".
							"((b.patient_cured = 'Undergoing Treatment') OR ".
							"(b.patient_cured = 'Completed' AND ".
							"(b.upon_tc_date >= '$start' AND b.upon_tc_date <= '$end'))) AND ".
							"e.barangay_id IN ($brgy) ";
						*/
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_patient b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'F' AND ".
							"a.patient_age < 15 AND ".
							"(a.date_of_diagnosis >= '$start' AND ".
							"a.date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}

					// Indicator #3: Newly detected Leprosy cases?
					// Note: code finalized
					if($indicator == '3') {
						$query = "SELECT a.patient_id FROM m_leprosy_diagnosis a ".
							"INNER JOIN m_patient b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'F' AND ".
							"a.patient_case = 'New Case' AND ".
							"(a.date_of_diagnosis >= '$start' AND ".
							"a.date_of_diagnosis <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query. ");
						return mysql_num_rows($result);
						break;
					}

					// Indicator #4: Newly detected cases with Grade 2 disability?
					// Note: code finalized
					if($indicator == '4') {
						$query = "SELECT b.patient_id FROM m_patient a ".
							"INNER JOIN m_leprosy_diagnosis b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_leprosy_who_disability_grade c ON b.consult_id = c.consult_id ".
							"INNER JOIN m_family_members d ON b.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
							"WHERE b.patient_case = 'New Case' AND ".
							"a.patient_gender = 'F' AND ".
							"((c.who_disability = 'Maximum Grade' AND ".
                                                        "c.upon_dx_right = '2') OR ".
                                                        "(c.who_disability = 'Maximum Grade' AND ".
                                                        "c.upon_dx_left = '2')) AND ".
							"(b.date_of_diagnosis >= '$start' AND ".
							"b.date_of_diagnosis <= '$end') AND ".
							"e.barangay_id IN ($brgy) ";
						$result = mysql_query($query)
							or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}
					// Indicator #5: Case cured?
					// Note: code finalized
					if($indicator == '5') {
						/*
						scenario:
							May 2009, patient 'x' was diagnosed.
							Jan 2010, patient 'x' was cured.
							Feb 2010, two new patients: 'y' and 'z'.
						questions:
							I want to print out all leprosy cases
							during the period Jan - Apr 2010. What is the
							cure rate? 

						solution: if the cure rate is 1/3, use the following codes:
						
						$query = "SELECT a.patient_id FROM m_leprosy_post_treatment a ".
							"INNER JOIN m_patient b on a.patient_id = b.patient_id ".
							"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".
							"INNER JOIN m_family_address d ON c.family_id = d.family_id ".
							"WHERE b.patient_gender = 'F' AND ".
							"a.patient_cured = 'Completed' AND ".
							"(a.upon_tc_date >= '$start' AND ".
							"a.upon_tc_date <= '$end') AND ".
							"d.barangay_id IN ($brgy) ";
						*/
						$query = "SELECT b.patient_id FROM m_patient a ".
                                                        "INNER JOIN m_leprosy_diagnosis b ON a.patient_id = b.patient_id ".
                                                        "INNER JOIN m_leprosy_post_treatment c ON b.consult_id = c.consult_id ".
							"INNER JOIN m_family_members d ON b.patient_id = d.patient_id ".
							"INNER JOIN m_family_address e ON d.family_id = e.family_id ".
                                                        "WHERE c.patient_cured = 'Completed' AND ".
                                                        "a.patient_gender = 'F' AND ".
							"(c.upon_tc_date >= '$start' AND ".
							"c.upon_tc_date <= '$end') AND ".
							"e.barangay_id IN ($brgy) ";
						$result = mysql_query($query) or die("Couldn't execute query.");
						return mysql_num_rows($result);
						break;
					}
				default:
					break;
					
				}	
		}
		
		
		
		function get_px_brgy(){
			if(func_num_args()>0):
				$arg_list = func_get_args();
				$pxid = $arg_list[0];
				$str = $arg_list[1];
			endif;
        
			$q_px = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id IN ($str)") or die("cannot query 389: ".mysql_error());
			
			if(mysql_num_rows($q_px)!=0):
				return 1;
			else:   
				return ;
			endif; 
		}
		
		
		
		function Footer(){
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
		}
		
		
		
	} // end of class

	$pdf = new PDF('L','mm','Legal');

	$pdf->AliasNbPages();
	$pdf->SetFont('Arial','',10);

	$pdf->AddPage();

	$pdf->show_leprosy_quarterly();

	$pdf->Output();
?>
