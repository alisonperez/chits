<?php
	session_start();

	ob_start();

	require('./fpdf/fpdf.php');

	$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
	mysql_select_db($_SESSION[dbname]);
	
	// Author: Jeffrey V. Tolentino
        // Report Version: 1.0
        // Description: Leprosy - Summary Table (FHSIS)
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
			$m1 = explode('/',$_SESSION[sdate_orig]);
			$m2 = explode('/',$_SESSION[edate_orig]);
			
			$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];
			
			$municipality_label = $_SESSION[datanode][name];
			
			$this->SetFont('Arial','B',12);
			
			
			$this->Cell(0,5,'National Leprosy Control Program Summary Table ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
			
			if(in_array('all',$_SESSION[brgy])):
				$brgy_label = '(All Barangays)';
			else:
				$brgy_label = '(';
				for($i=0;$i<count($_SESSION[brgy]);$i++) {
					$brgy = $_SESSION[brgy][$i];
					$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$brgy'") or die("Cannot query: 139");
					
					list($brgyname) = mysql_fetch_array($q_brgy);
					
					if($i!=(count($_SESSION[brgy])-1)):
						$brgy_label.= $brgyname.', ';
					else:
						$brgy_label.= $brgyname.')';
					endif;
				}
			endif;
			
			$this->SetFont('Arial','',10);
			
			$this->Cell(0,5,$brgy_label,0,1,'C');		
			$w = array(30,18,18,18,18,15,18,18,18,15,18,18,18,15,18,18,18,15,18); //340
			$header = array('INDICATORS','Target','JAN','FEB','MAR','1st Q','APR','MAY','JUNE','2nd Q','JULY','AUG','SEPT','3rd Q','OCT','NOV','DEC','4th Q','TOTAL');
			
			$w2 = array(30,18,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9);
			$subheader = array('','');
			
			for($i=0;$i<17;$i++) {
				array_push($subheader,'M','F');
			}
			
			$this->SetWidths($w);
			$this->Row($header);	
			$this->SetWidths($w2);
			$this->Row($subheader);
		}
		
		
		
		function Footer() {
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
		}
		
		
		
		function show_leprosy_summary() {
			$arr_indicators = array('Leprosy cases?', 
				'Leprosy cases below 15 yrs old?', 
				'Newly detected Leprosy cases?',
				'Newly detected cases with Grade 2 disability?',
				'Case cured?');
				
			for($i=0;$i<count($arr_indicators);$i++) {
				$brgy_pop = $this->get_brgy_pop(); //get population of brgy/s
				$target_perc = $this->get_target($i); //get the percentage of targets
				if($target_perc == 0) {
					$target = '';
				}
				else {
					$target = round(($brgy_pop * $target_perc)); //get the population target
				}
				$header = array(30,18,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9);
				$disp_arr = array();
				
				$load = 0;
				$arr_gender_stat = $this->compute_indicators($i+1); //return an array with index 0= array of M count. 1=array of F count
				$male_monthly = $arr_gender_stat[0];
				$female_monthly = $arr_gender_stat[1];
				
				//print_r($arr_gender_stat);
				$male_quarterly = $this->get_quarterly_total($arr_gender_stat[0],$target);
				$female_quarterly = $this->get_quarterly_total($arr_gender_stat[1],$target);
				array_push($disp_arr,$arr_indicators[$i],$target);				
				
				for($k=1;$k<((count($male_monthly)+count($female_monthly)+count($male_quarterly)+count($female_quarterly))/7);$k++) { 	
					for($l=0;$l<3;$l++) {
						array_push($disp_arr,$male_monthly[$k+$load+$l],$female_monthly[$k+$load+$l]);
					}
					array_push($disp_arr,$male_quarterly[$k],$female_quarterly[$k]);
					$load+=2;
				}
				
				array_push($disp_arr,array_sum($male_quarterly),array_sum($female_quarterly));
				
				$this->SetWidths($header);
				$this->Row($disp_arr);
				
			}
		}
		
		
		
		function compute_indicators() {
			if(func_num_args() > 0) {
				$arg_list = func_get_args();
				$crit = $arg_list[0];
				$header = $arg_list[1];
			}
			
			$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
			$arr_gender = array('M','F');
			$brgy_array = $this->get_brgy_array();
			$brgy_array = implode(',',$brgy_array);
			
			$arr_gender_stat = array();
			
			switch($crit) {
				case 1: // Indicator #1: Leprosy case?
					// Note: code finalized
					for($sex = 0; $sex < count($arr_gender); $sex++) {
						$month_stat = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);

						$query = "SELECT m_leprosy_diagnosis.patient_id, m_leprosy_diagnosis.date_of_diagnosis ".
							"FROM m_leprosy_diagnosis INNER JOIN m_patient ON ".
							"m_leprosy_diagnosis.patient_id = m_patient.patient_id WHERE ".
							"m_patient.patient_gender = '$arr_gender[$sex]' ";
						$result = mysql_query($query) or die("Couldn't execute query.");
						
						while(list($patient_id, $date_of_diagnosis) = mysql_fetch_array($result)) {
								list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
								list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
								list($ctaon,$cbuwan,$cdate) = explode('-',$date_of_diagnosis); 
						
							$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
							$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
							$doc = mktime(0,0,0,$cbuwan,$cdate,$ctaon);
						
							if($doc >= $start && $doc <= $end) {
								if($this->get_px_brgy($patient_id, $brgy_array)) {
									$month_stat[$this->get_max_month($date_of_diagnosis)] += 1;
								}
							}
						}
						array_push($arr_gender_stat,$month_stat);
					}
					break;
					
				case 2: // Leprosy cases below 15 yrs old?
					// Note: code finalized
					for($sex = 0; $sex < count($arr_gender); $sex++) {
						$month_stat = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);

						// same scenario as case 1	
						$query = "SELECT a.patient_id, a.date_of_diagnosis ".
							"FROM m_leprosy_diagnosis a INNER JOIN m_patient b ON ".
							"a.patient_id = b.patient_id WHERE ".
							"b.patient_gender = '$arr_gender[$sex]' AND ".
							"a.patient_age < 15 ";
						$result = mysql_query($query)
							or die("Couldn't execute query");
						
						while(list($patient_id, $date_of_consultation) = mysql_fetch_array($result)) {
							list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
							list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
							list($ctaon,$cbuwan,$cdate) = explode('-',$date_of_consultation);
						
							$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
							$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
							$doc = mktime(0,0,0,$cbuwan,$cdate,$ctaon);
						
							if($doc >= $start && $doc <= $end) {
								if($this->get_px_brgy($patient_id, $brgy_array)) {
									$month_stat[$this->get_max_month($date_of_consultation)] += 1;
								}
							}
						}
						array_push($arr_gender_stat,$month_stat);
					}
					break;
				
				case 3: // Newly detected Leprosy cases?
					// Note: code finalized
					for($sex = 0; $sex < count($arr_gender); $sex++) {
						$month_stat = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
					
						$query = "SELECT a.patient_id, a.date_of_diagnosis ".
							"FROM m_leprosy_diagnosis a INNER JOIN m_patient b ON ".
							"a.patient_id = b.patient_id WHERE ".
							"b.patient_gender = '$arr_gender[$sex]' AND ".
							"a.patient_case = 'New Case' ";
						$result = mysql_query($query)
							or die("Couldn't execute query");
						
						while(list($patient_id, $date_of_consultation) = mysql_fetch_array($result)) {
							list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
							list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
							list($ctaon,$cbuwan,$cdate) = explode('-',$date_of_consultation);
						
							$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
							$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
							$doc = mktime(0,0,0,$cbuwan,$cdate,$ctaon);
						
							if($doc >= $start && $doc <= $end) {
								if($this->get_px_brgy($patient_id, $brgy_array)) {
									$month_stat[$this->get_max_month($date_of_consultation)] += 1;
								}
							}
						}
						
						array_push($arr_gender_stat,$month_stat);
					}
					break;
					
				case 4: // Newly detected cases with Grade 2 disability?
					// Note: code finalized
					for($sex = 0; $sex < count($arr_gender); $sex++) {
						$month_stat = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);
					
						$query = "SELECT b.patient_id, b.date_of_diagnosis FROM m_patient a ".
							"INNER JOIN m_leprosy_diagnosis b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_leprosy_who_disability_grade c ON b.consult_id = c.consult_id ".
							"WHERE b.patient_case = 'New Case' AND ".
							"a.patient_gender = '$arr_gender[$sex]' AND ".
							"((c.who_disability = 'Maximum Grade' AND ".
							"c.upon_dx_right = '2') OR ".
							"(c.who_disability = 'Maximum Grade' AND ".
							"c.upon_dx_left = '2'))";
						$result = mysql_query($query)
							or die("Couldn't execute query".mysql_error());
						
						while(list($patient_id, $date_of_consultation) = mysql_fetch_array($result)) {
							list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
							list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
							list($ctaon,$cbuwan,$cdate) = explode('-',$date_of_consultation);
						
							$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
							$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
							$doc = mktime(0,0,0,$cbuwan,$cdate,$ctaon);
						
							if($doc >= $start && $doc <= $end) {
								if($this->get_px_brgy($patient_id, $brgy_array)) {
									$month_stat[$this->get_max_month($date_of_consultation)] += 1;
								}
							}
						}
						
						array_push($arr_gender_stat,$month_stat);
					}
					break;
					
				case 5: // Case cured?
					// Note: code finalized
					for($sex = 0; $sex < count($arr_gender); $sex++) {
						$month_stat = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0);

						$query = "SELECT b.patient_id, b.date_of_diagnosis ".
							"FROM m_patient a ".
							"INNER JOIN m_leprosy_diagnosis b ON a.patient_id = b.patient_id ".
							"INNER JOIN m_leprosy_post_treatment c ON b.consult_id = c.consult_id ".
							"WHERE c.patient_cured = 'Completed' AND ".
							"a.patient_gender = '$arr_gender[$sex]' ";
						$result = mysql_query($query)
							or die("Couldn't execute query". mysql_error());
						
						while(list($patient_id, $date_of_consultation) = mysql_fetch_array($result)) {
							list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
							list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
							list($ctaon,$cbuwan,$cdate) = explode('-',$date_of_consultation);
						
							$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
							$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
							$doc = mktime(0,0,0,$cbuwan,$cdate,$ctaon);
						
							if($doc >= $start && $doc <= $end) {
								if($this->get_px_brgy($patient_id, $brgy_array)) {
									$month_stat[$this->get_max_month($date_of_consultation)] += 1;
								}
							}
						}
						
						array_push($arr_gender_stat,$month_stat);
					}
					break;
				
				default:	
					break;
			}	
			
			return $arr_gender_stat;
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
			if($criteria == 0):
				$perc = '0.0001';
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
		
		
		
	} // end of class

	$pdf = new PDF('L','mm','Legal');
	$pdf->AliasNbPages();
	$pdf->SetFont('Arial','',10);
	$pdf->AddPage();

	$pdf->show_leprosy_summary();

	$pdf->Output();
?>
