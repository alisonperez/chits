<?php

	session_start();

	ob_start();

	require('./fpdf/fpdf.php');

	$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
	mysql_select_db($_SESSION[dbname]);
	
	
	
	// Comment date: Jan 05, 2010, JVTolentino
   // The following are member functions of the PDF class. These functions are needed
	// 	for the basic operations of the class. DO NOT DELETE OR MODIFY THESE FUNCTIONS
	//		UNLESS, OF COURSE, YOU KNOW WHAT YOU'RE DOING ^^.
   // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		// 1. function SetWidths($w)
		//	2. function SetAligns($a)
		//	3. function Row($data)
		// 4. function CheckPageBreak($h)
		// 5. function NbLines($w,$txt)
		// 6. function Header()
		// 7. function Footer()
		// 8. function show_dental_summary()  	>> the main function used in showing the report
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
			$q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") 
				or die("Cannot query: 123". mysql_error());
			list($population)= mysql_fetch_array($q_pop);
			
			$this->q_report_header($population);
			$this->Ln(10);
			
			$this->SetFont('Arial','BI','20');
			$this->Cell(340,10,'Dental Health Care',1,1,C);
			
			$this->SetFont('Arial','B','12');
			//original value of $w from fp_quarterly
			//$w = array(75,28,28,26,28,28,28,47,52);
			//modified $w, to fit only 8 cols
			$w = array(76,28,28,26,28,28,63,63);
			$this->SetWidths($w);
			//original value of $label from fp_quarterly
			//$label = array('Indicators','Current User (Begin Qtr)','New Acceptors','Others','Dropout','Current User (End Qtr)','CPR'."\n".'(CU/TP) x 14.5% x 85%','Interpretation','Recommendation/Action Taken');
			//modified value of $label
			$label = array('Indicators', 
				'Elig. Pop.', 
				'Number (Male)', 
				'Number (Female)', 
				'Number (Total)', 
				'%', 
				'Interpretation', 
				'Recommendation/Action Taken');
			$this->Row($label);
		}
		
		
		
		function q_report_header($population) {
			$this->SetFont('Arial','B','12');
			$this->Cell(0,5,'FHSIS REPORT FOR THE QUARTER: '.$_SESSION[quarter]."          YEAR: ".$_SESSION[year],0,1,L);
			$this->Cell(0,5, 'MUNICAPLITY/CITY NAME: '.$_SESSION[datanode][name],0,1,L);
			$this->Cell(0,5,'PROVINCE: '.$_SESSION[province]."          PROJECTED POPULATION OF THE YEAR: ".$population,0,1,L);
		}
		
		
		
		function show_dental_quarterly(){
			// original value of $arr_method
			//$arr_method = array('a'=>'FSTRBTL','b'=>'MSV','c'=>'PILLS','d'=>'IUD','e'=>'DMPA','f'=>'NFPCM','g'=>'NFPBBT','h'=>'NFPLAM','i'=>'NFPSDM','j'=>'NFPSTM','k'=>'CONDOM');
			//new var $arr_indicator
			$arr_indicator = array('a' => 'Orally Fit Children 12-71 monthds old',
				'b' => 'Children 12-71 months old provided with BOHC?', 
				'c' => 'Adolescent & Youth (10-24 years) given BOHC?',
				'd' => 'Pregnant women provided with BOHC?',
				'e' => 'Older Person 60 years old & above provided with BOHC?');
			//$w = array(75,28,28,28,26,28,28,47,52);
			$w = array(76,28,28,26,28,28,63,63);
			$str_brgy = $this->get_brgy();    
			
			//echo $_SESSION[sdate2].'/'.$_SESSION[edate2];
			
			/*
			foreach($arr_method as $col_code=>$method_code) {
				$q_fp = mysql_query("SELECT method_name FROM m_lib_fp_methods WHERE method_id='$method_code'") 
					or die("Cannot query: 151".mysql_error());    
				list($method_name) = mysql_fetch_array($q_fp);
				
				$cu_prev = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,2);
				$na_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,3);
				$other_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,4);
				$dropout_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,5 );
				$cu_pres = ($cu_prev + $na_pres + $other_pres) - $dropout_pres;
				$cpr = $this->get_cpr($cu_pres);
				
				$fp_contents = array($col_code.'. '.$method_name,$cu_prev,$na_pres,$other_pres,$dropout_pres,$cu_pres,$cpr,'','');
				
				
				for($x=0;$x<count($fp_contents);$x++){
					$this->Cell($w[$x],6,$fp_contents[$x],'1',0,'L');
				}
				
				$this->Ln();                
				//        $this->Row($fp_contents);
			}
			*/
			for($indicator_ctr = 1; $indicator_ctr <= 5; $indicator_ctr++) {
				//$col2;
				$col3 = $this->get_data($_SESSION[sdate2], $_SESSION[edate2], 
					$indicator_ctr, $str_brgy, 3);
				$col4 = $this->get_data($_SESSION[sdate2], $_SESSION[edate2], 
					$indicator_ctr, $str_brgy, 4);
				$col5 = $col3 + $col4;
				//$col6;
				//$col7;
				//$col8;
				
				switch($indicator_ctr) {
					case 1:
						$indicator = "Orally Fit Children 12-71 months old";
						break;
					case 2:
						$indicator = "Children 12-71 months old Provided with Basic Oral Health Care (BOHC)";
						break;
					case 3:
						$indicator = "Adolescent and Youth (10-24 years old)".
							"provided with Basic Oral Health Care (BOHC)";
						break;
					case 4:
						$indicator = "Pregnant women provided with Basic Oral Health Care (BOHC)";
						break;
					case 5:
						$indicator = "Older Persons (60 years old and above) ".
							"provided with Basic Oral Health Care (BOHC)";
						break;
					default:
						break;
				}
				$dental_contents = array($indicator,
					'n/a', $col3, $col4, $col5, 'n/a', '', '');
				
				/*
				//ORIGINAL CODE FOR OUTPUTTING THE CONTENTS OF $dental_contents
				for($x = 0; $x < count($dental_contents); $x++){
					$this->Cell($w[$x], 6, $dental_contents[$x], '1', 0, 'L');
				}
				$this->Ln();
				*/
				
				// REVISED CODE FOR OUTPUTTING THE CONTENTS OF $dental_contents
				$this->Row($dental_contents);
			}
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
			// Column 2 = Elig. Pop.
			// Column 3 = Number (Male)
			// Column 4 = Number (Female)
			// Column 5 = Number (Total)
			// Column 6 = %
			// Column 7 = Interpretation
			// Column 8 = Recommendation/Action Taken
			switch($col_code) {
				case '2':
					break;
				case '3':
					$query = "SELECT patient_id, date_of_consultation FROM m_dental_fhsis ".
							"WHERE indicator = $indicator AND ".
							"indicator_qualified = 'YES' AND ".
							"gender = 'M' AND ".
							"(date_of_consultation >= '$start' AND ".
							"date_of_consultation <= '$end') ";
						$result = mysql_query($query)
							or die("Couldn't execute query on case 3. ".mysql_error());
						
					return mysql_num_rows($result);
					break;
				case '4':
					$query = "SELECT patient_id, date_of_consultation FROM m_dental_fhsis ".
						"WHERE indicator = $indicator AND ".
						"indicator_qualified = 'YES' AND ".
						"gender = 'F' AND ".
						"(date_of_consultation >= '$start' AND ".
						"date_of_consultation <= '$end') ";
					$result = mysql_query($query)
						or die("Couldn't execute query on case 4. ".mysql_error());
						
					return mysql_num_rows($result);
					break;
					
					case '6':
						return;
						break;
					case '7':
						return;
						break;
					case '8':
						return;
						break;
					
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

	$pdf->show_dental_quarterly();

	//$pdf->AddPage();
	//$pdf->show_fp_summary();
	$pdf->Output();
?>
