<?php
	session_start();

	ob_start();

	require('./fpdf/fpdf.php');

	$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
	mysql_select_db($_SESSION[dbname]);

	// Author: Jeffrey V. Tolentino
	// Report Version: 1.0
	// Description: Leprosy - Target Client List (NLCP - Manual of Procedures)
	// Version Released: Feb 2010
	
	
	// Comment date: Jan 05, 2010, JVTolentino
   	// The following are member functions of the PDF class. These functions are needed
	// 	for the basic operations of the class. DO NOT DELETE OR MODIFY THESE FUNCTIONS
	//	UNLESS, OF COURSE, YOU KNOW WHAT YOU'RE DOING ^^.
   	// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		// 1. function SetWidths($w)
		// 2. function SetAligns($a)
		// 3. function Row($data)
		// 4. function CheckPageBreak($h)
		// 5. function NbLines($w,$txt)
		// 6. function Header()
		// 7. function Footer()
		// 8. function show_leprosy_tcl()  	>> the main function used in showing the report
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
			$m1 = explode('/',$_SESSION[sdate_orig]);
			$m2 = explode('/',$_SESSION[edate_orig]);
			
			$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];
			
			$municipality_label = $_SESSION[datanode][name];
			
			$this->SetFont('Arial','B',12);
			
			$this->Cell(0,5, 'Target Client List for National Leprosy Control Program ('.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].')',0,1,'C');
			
			if($_SESSION[brgy] == 'all' || $_SESSION[brgy] == 'All') {
				$brgy_label = 'All Barangays';
			}
			else {
				$brgy_name = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$_SESSION[brgy]'")
					or die("Couldn't execute query.");
				list($brgy_label) = mysql_fetch_array($brgy_name);
			}
			
			$this->SetFont('Arial','',10);
 
			$this->Cell(0,5, $brgy_label, 0, 1, 'C');
			$this->Cell(0,5,$_SESSION[datanode][name],0,1,'C');
			$this->Cell(0,5,'Province of '.$_SESSION[province],0,1,'C');

			$w = array(8,69,15,15,69,26,24,26,24,40,24); //340
			$header = array('#', 'Name', 'Age', 'Sex', 'Address', 'Patient Status', 'WHO Disability Grading', 'MDT Classification', 'Date MDT Started', 'Movement Of Patient', 'Date MDT Completed');
			$w2 = array(8,69,15,15,69,13,13,24,13,13,24,10,10,10,10,24);
			$sub_header = array('','','','','','Old','New','','PB','MB','','T/O','T/I','Died','Lost','');
			
			$this->SetWidths($w);
			$this->Row($header);
			$this->SetWidths($w2);
			$this->Row($sub_header);
		}
		
		
		
		function Footer() {
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
		}
		
		
		
		function show_leprosy_tcl() {
			list($month, $day, $year) = explode("/", $_SESSION[sdate_orig]);
			$start = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
			list($month, $day, $year) = explode("/", $_SESSION[edate_orig]);
			$end = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);

			$ctr = 1;
			$w = array(8,69,15,15,69,26,24,26,24,40,24); //340
			$this->SetWidths($w);

			if($_SESSION[brgy] == 'All' || $_SESSION[brgy] == 'all') {
				$patients = "SELECT b.patient_lastname, b.patient_firstname, b.patient_middle, a.patient_age, b.patient_gender, ".
					"d.address, e.barangay_name, a.patient_case, a.consult_id, a.classification ".
					"FROM m_leprosy_diagnosis a INNER JOIN m_patient b ON a.patient_id = b.patient_id ".	// lastname, firstname, middle
					"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".			// to get the family id in order
					"INNER JOIN m_family_address d ON c.family_id = d.family_id ".				// to get the family address
					"INNER JOIN m_lib_barangay e ON d.barangay_id = e.barangay_id ".			// and then family barangay
					"WHERE (a.date_of_diagnosis >= '$start' AND a.date_of_diagnosis <= '$end') ORDER BY a.date_of_diagnosis";
			}
			else {
				$patients = "SELECT b.patient_lastname, b.patient_firstname, b.patient_middle, a.patient_age, b.patient_gender, ".
					"d.address, e.barangay_name, a.patient_case, a.consult_id, a.classification ".
					"FROM m_leprosy_diagnosis a INNER JOIN m_patient b ON a.patient_id = b.patient_id ".	// lastname, firstname, middle
					"INNER JOIN m_family_members c ON b.patient_id = c.patient_id ".			// to get the family id in order
					"INNER JOIN m_family_address d ON c.family_id = d.family_id ".				// to get the family address
					"INNER JOIN m_lib_barangay e ON d.barangay_id = e.barangay_id ".			// and then family barangay
					"WHERE (a.date_of_diagnosis >= '$start' AND a.date_of_diagnosis <= '$end') AND ".
					"e.barangay_id = '$_SESSION[brgy]' ORDER BY a.date_of_diagnosis";
			}

			$result = mysql_query($patients)
				or die("Couldn't execute query.");
			$ctr = 1;
	
			while(list($ln, $fn, $mn, $age, $patient_gender, $address, $barangay, $patient_case, $consult_id, $classification ) = mysql_fetch_array($result)) {
				$patient_name = $ln.", ".$fn." ".$mn;
				list($age_in_year,$month) = explode(".", $age);
				$patient_address = $address.", ".$barangay;

				// The following query will be used to get the patient's maximum who disability grade.
				$who_query = "SELECT b.upon_dx_right, b.upon_dx_left, b.upon_tc_right, b.upon_tc_left ".
					"FROM m_leprosy_diagnosis a INNER JOIN m_leprosy_who_disability_grade b ON a.consult_id = b.consult_id ".
					"WHERE b.who_disability = 'Maximum Grade' AND b.consult_id = $consult_id ";
				$who_result = mysql_query($who_query) or die("Couldn't execute query");
				list($dx_right, $dx_left, $tc_right, $tc_left) = mysql_fetch_array($who_result); 
				$who_dg = 0;
				if($dx_right > $who_dg)
					$who_dg = $dx_right;
				if($dx_left > $who_dg)
					$who_dg = $dx_left;
				if($tc_right > $who_dg)
					$who_dg = $tc_right;
				if($tc_left > $who_dg)
					$who_dg = $tc_left;

				// The following query will be used to get the date when MDT was started.
				$mdt_started_query = "SELECT b.date_for_the_supervised_dose FROM m_leprosy_diagnosis a ".
					"INNER JOIN m_leprosy_drug_collection_chart b ON a.consult_id = b.consult_id ".
					"WHERE b.consult_id = $consult_id ORDER BY b.date_for_the_supervised_dose";
				$mdt_started_result = mysql_query($mdt_started_query) or die("Couldn't execute query");
				list($mdt_started) = mysql_fetch_array($mdt_started_result);
				if($mdt_started != '') {
					list($year, $month, $day) = explode("-", $mdt_started);
                        		$mdt_started = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				}


				// The following query will be used to get the movement of patient and when MDT was completed.
				$post_treatment_query = "SELECT b.upon_tc_date, b.movement_of_patient, b.patient_cured FROM m_leprosy_diagnosis a ".
					"INNER JOIN m_leprosy_post_treatment b ON a.consult_id = b.consult_id ".
					"WHERE b.consult_id = $consult_id ";
				$post_treatment_result = mysql_query($post_treatment_query) or die("Couldn't execute query.".mysql_error());
				list($tc_date, $movement, $patient_cured) = mysql_fetch_array($post_treatment_result);
				if($patient_cured != 'Completed') {
					$tc_date = '';
				}
				else {
					list($year, $month, $day) = explode("-", $tc_date);
                        		$tc_date = str_pad($month, 2, "0", STR_PAD_LEFT)."/".str_pad($day, 2, "0", STR_PAD_LEFT)."/".$year;
				}

				$row_contents = array($ctr, $patient_name, $age_in_year, $patient_gender, $patient_address, $patient_case, $who_dg, $classification, $mdt_started, $movement, $tc_date);
				//$row_contents = array($ctr, $ln.", ".$fn." ".$mn, $address.", ".$barangay_name, $patient_case);
				$this->Row($row_contents);
				$ctr++;
			}
			
		}
		
		
		function compute_indicators() {
			if(func_num_args() > 0) {
				$arg_list = func_get_args();
				$crit = $arg_list[0];
				$header = $arg_list[1];
			}
			
			
			
		}
		
		
		
		function get_max_month($date) {
			list($taon,$buwan,$araw) = explode('-',$date);
			$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0
			return $max_date;
		}
		
		
		
		function disp_blank_header($header_title,$target){
			$header = array(30,18,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9);
			$this->SetWidths($header);
			$disp_arr = array($header_title,$target);
			for($x=0;$x<35;$x++) {
				array_push($disp_arr,'');
			}				
			$this->Row($disp_arr);
		}
		
		
		/*	
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
		
		
		
		function get_quarterly_total($r_month,$target) {
			$q_total = array();
			$counter = 0;
			
			for($i=1;$i<=4;$i++) {
				//$sum = $r_month[$i+$counter] + $r_month[$i+$counter+1] + $r_month[$i+$counter+2];
				$q_total[$i] = $r_month[$i+$counter] + $r_month[$i+$counter+1] + $r_month[$i+$counter+2];
				//$q_total[$i] = round(($sum/$target),3)*100;
				$counter+=2;
			}
			return $q_total;
		}
		
		
		
		function get_target($criteria){
			if($criteria>=0 && $criteria<=2):
				$perc = '0.027';
			else:
				
			endif;
			return $perc;
		}
		
		
		
		function get_brgy_array(){
			$mga_brgy = array();
			if(in_array('all',$_SESSION[brgy])):
				$q_brgy = mysql_query("SELECT barangay_id FROM m_lib_barangay ORDER by barangay_id ASC") or die("Cannot query: 448");
				while(list($b_id)=mysql_fetch_array($q_brgy)){
					array_push($mga_brgy,$b_id);
				}
				return $mga_brgy;
			else:
				return $_SESSION[brgy];
			endif;	
		}
		
		
		
		function get_px_brgy() {
			if(func_num_args()>0):
				$arg_list = func_get_args();
				$pxid = $arg_list[0];
				$str = $arg_list[1];
			endif;
			
			$q_px = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id IN ($str)") or die("cannot query :1061");
		
			if(mysql_num_rows($q_px)!=0):
				return 1;
			else:
				return ;
			endif;
		}
		*/
		
		
	} // end of class

	$pdf = new PDF('L','mm','Legal');
	$pdf->AliasNbPages();
	$pdf->SetFont('Arial','',10);
	$pdf->AddPage();

	$pdf->show_leprosy_tcl();

	$pdf->Output();
?>
