<?php

session_start();

ob_start();

require('./fpdf/fpdf.php');

$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
mysql_select_db($_SESSION[dbname]);

class PDF extends FPDF
{
	var $widths;
	var $aligns;
	var $page;	

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
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


function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


function NbLines($w,$txt)
{
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
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
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
        if($l>$wmax)
        {
            if($sep==-1)
            {
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


function Header()
{
	$m1 = explode('/',$_SESSION[sdate_orig]);
	$m2 = explode('/',$_SESSION[edate_orig]);
	
	$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];

	$municipality_label = $_SESSION[datanode][name];
	
	$this->SetFont('Arial','B',12);		
	
	if($_SESSION[ques]==39):
	
	$this->Cell(0,5,'Child Care Summary Table ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
	
	if(in_array('all',$_SESSION[brgy])):
		$brgy_label = '(All Barangays)';
	else:
		$brgy_label = '(';
		for($i=0;$i<count($_SESSION[brgy]);$i++){
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

	for($i=0;$i<17;$i++){
		array_push($subheader,'M','F');
	}


        elseif($_SESSION[ques]==50 || $_SESSION[ques]==51):

            $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("CAnnot query: 164");
                                                    
            if(mysql_num_rows($q_pop)!=0):
                list($population) = mysql_fetch_row($q_pop);
                                            
            else:
                $population = 0;
            endif;
                                                                            
                                                                            
        
	if($_SESSION[ques]==50):  //monthly report
		$this->Cell(0,5,'FHSIS REPORT FOR THE MONTH: '.date('F',mktime(0,0,0,$_SESSION[smonth],1,0)).'          YEAR: '.$_SESSION[year],0,1,L);
                
		$this->Cell(0,5,'NAME OF BHS: Brgy '.$this->get_brgy(),0,1,L);                                 
		$w = array(200,40,40);                
		
		$header = array('CHILD CARE', 'Male', 'Female');
		
		
	
	elseif($_SESSION[ques]==51):  //quarterly report
                $w = array(120,30,60,20,50,55);
                $w2 = array(120,30,20,20,20,20,50,55);
		$header = array('Indicators', 'Eligible Population','Number','%','Interpretation','Recommendation/Action Taken');
                $subheader = array('','','Male','Female','Total','','','');
		$this->Cell(0,5,'FHSIS REPORT FOR THE QUARTER: '.$_SESSION[quarter].'          YEAR: '.$_SESSION[year],0,1,L);
		
	else:

	endif;	
	    $this->Cell(0,5,'MUNICIPALITY/CITY OF: '.$_SESSION[lgu],0,1,L);
            $this->Cell(0,5,'PROVINCE: '.$_SESSION[province].'          PROJECTED POPULATION OF THE YEAR: '.$population,0,1,L);
            $this->Ln(15);    
            
	else:
	
	endif;
		
		
	$this->SetWidths($w);
	$this->Row($header);	
	$this->SetWidths($w2);
	$this->Row($subheader);

}

function Footer(){
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}

function show_ccdev_summary(){
	$arr_indicators = array(array('Immunization Given < 1 yr'=>array('BCG'=>'BCG','DPT1'=>'DPT1','DPT2'=>'DPT2','DPT3'=>'DPT3','OPV1'=>'OPV1','OPV2'=>'OPV2','OPV3'=>'OPV3','HEPB1<24'=>'Hepa B1 w/ in 24 hrs','HEPB1>24'=>'Hepa B1 > 24 hours','HEPB2'=>'Hepatitis B2','HEPB3'=>'Hepatitis B3','MSL'=>'Measles')),'Fully Immunized Child','Completely Immunized Child (12-23 mos)','Child Protected at Birth','Infant age 6 mo seen','Infant exclusively breastfed until 6 mo','Infant 0-11 mos referred for NBS',array('Diarrhea (0-59 mos)'=>array('num_case'=>'No. of Cases','ort'=>'Given ORT','ors'=>'Given ORS','orswz'=>'Given ORS w/ Zinc')),array('Pneumonia (0-59 mos)'=>array('num_cases'=>'No. of cases','pneumonia_tx'=>'Given Treatment')),array('Sick Children Seen'=>array('6*11'=>'6-11 mos','12*59'=>'12-59 mos','60*71'=>'60-71 mos')),array('Sick Children Given Vit A'=>array('6*11'=>'6-11 mos','12*59'=>'12-59 mos','60*71'=>'60-71 mos')),'Infant 2-6 mos w/ LBW seen','Infant 2-6 mos w/ LBW given iron','Anemic Children 2-59 mos seen','Anemic Children 2-59 mos given iron');
	$m_index = array('1'=>array('2','3'),'2'=>array('4','5'),'3'=>array('6','7'),'4'=>array('10','11'),'5'=>array('12','13'),'6'=>array('14','15'),'7'=>array('18','19'),'8'=>array('20','21'),'9'=>array('22','23'),'10'=>array('26','27'),'11'=>array('28','29'),'12'=>array('30','31'));
	$q_index = array('1'=>array('8','9'),'2'=>array('16','17'),'3'=>array('24','25'),'4'=>array('32','33'));
	
	if($_SESSION[ques]==39):
	    $header = array(30,18,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9);	
        elseif($_SESSION[ques]==50):
            $header = array(200,40,40);
        elseif($_SESSION[ques]==51):
            $header = array(120,30,20,20,20,20,50,55);
            //$header = array(120,30,60,20,50,55);        
        else:        
        endif;
	
	
	for($i=0;$i<count($arr_indicators);$i++){
		$sub_arr = array();
		$brgy_pop = $this->get_brgy_pop(); //get population of brgy/s
		$target_perc = $this->get_target($i); //get the percentage of targets
		$target = round(($brgy_pop * $target_perc)); //get the population target
		
		$disp_arr = array();

		if(is_array($arr_indicators[$i])):

			$sub_arr = array_keys($arr_indicators[$i]); //this will return the header title if the content is an array			
			$counter = 0;
			$arr_sub_indicators = $this->compute_indicators($i+1,$sub_arr,$arr_indicators[$i]);			
			
			if($_SESSION[ques]==39):
			    $this->disp_blank_header($sub_arr[0],$target);								
			endif;
			
			//print_r($arr_sub_indicators);
			
			for($j=0;$j<(count($arr_sub_indicators)/2);$j++){
				$disp_arr = array();				
				$load = 0;
				
				
				$lbl_indicator = $this->disp_arr_indicator($i,$j);

				$counter = $j*2;

				$male_monthly = $arr_sub_indicators[$counter];
				$female_monthly = $arr_sub_indicators[$counter+1];

				$male_quarterly = $this->get_quarterly_total($arr_sub_indicators[$counter],$target);
				$female_quarterly = $this->get_quarterly_total($arr_sub_indicators[$counter+1],$target);
				
				array_push($disp_arr,$lbl_indicator,$target);

				for($k=1;$k<((count($male_monthly)+count($female_monthly)+count($male_quarterly)+count($female_quarterly))/7);$k++){	
					for($l=0;$l<3;$l++){
						array_push($disp_arr,$male_monthly[$k+$load+$l],$female_monthly[$k+$load+$l]);
					}
					array_push($disp_arr,$male_quarterly[$k],$female_quarterly[$k]);
					$load+=2;
				}
				
				array_push($disp_arr,array_sum($male_quarterly),array_sum($female_quarterly));
                                
                                                   
                                //print_r($disp_arr);
                                
				$this->SetWidths($header);			        
				
				//$this->Row($disp_arr);
			        
				if($_SESSION[ques]==39):				    
				    $this->Row($disp_arr);				    
                                elseif($_SESSION[ques]==50):
                                    
                                    $m_arr = array('     '.$disp_arr[0],$disp_arr[$m_index[$_SESSION[smonth]][0]],$disp_arr[$m_index[$_SESSION[smonth]][1]]);
                                    
                                    for($x=0;$x<count($m_arr);$x++){
                                        if($counter==0):
                                            $this->Cell($header[0],6,$sub_arr[0],'1',0,'L');
                                            $this->Cell($header[1],6,' ','1',0,'L');
                                            $this->Cell($header[2],6,' ','1',0,'L');
                                            $this->Ln();
                                            $counter = 1;                                            
                                        endif;
                                        $this->Cell($header[$x],6,$m_arr[$x],'1',0,'L');
                                    }
                                    
                                    $this->Ln();                                    
                                    //$this->Row(array($disp_arr[0],$disp_arr[$m_index[$_SESSION[smonth]][0]],$disp_arr[$m_index[$_SESSION[smonth]][1]]));                                
                                elseif($_SESSION[ques]==51):
                                    $total_q = $disp_arr[$q_index[$_SESSION[quarter]][0]] + $disp_arr[$q_index[$_SESSION[quarter]][1]];
                                                
                                    $q_arr = array('     '.$disp_arr[0],$target,$disp_arr[$q_index[$_SESSION[quarter]][0]],$disp_arr[$q_index[$_SESSION[quarter]][1]],$total_q,$this->compute_ccdev_rate($target,$total_q),' ',' ');
                                    
                                    
                                    for($x=0;$x<count($q_arr);$x++){
                                        if($counter==0):
                                            $this->Cell($header[0],6,$sub_arr[0],'1',0,'L');
                                            $this->Cell($header[1],6,' ','1',0,'L');
                                            $this->Cell($header[2],6,' ','1',0,'L');
                                            $this->Cell($header[3],6,' ','1',0,'L');
                                            $this->Cell($header[4],6,' ','1',0,'L');
                                            $this->Cell($header[5],6,' ','1',0,'L');
                                            $this->Cell($header[6],6,' ','1',0,'L');                                            
                                            $this->Cell($header[7],6,' ','1',0,'L');                                            
                                            $this->Ln();
                                            $counter = 1;                                            
                                        endif;
                                        
                                        $this->Cell($header[$x],6,$q_arr[$x],'1',0,'L');                                        
                                    }
                                 
                                    $this->Ln();
                                                                                                       
                                else:
                                
                                endif;
			}

		else:
			$load = 0;
			$arr_gender_stat = $this->compute_indicators($i+1); //return an array with index 0= array of M count. 1=array of F count
			$male_monthly = $arr_gender_stat[0];
			$female_monthly = $arr_gender_stat[1];

			//print_r($arr_gender_stat);
			$male_quarterly = $this->get_quarterly_total($arr_gender_stat[0],$target);
			$female_quarterly = $this->get_quarterly_total($arr_gender_stat[1],$target);
			array_push($disp_arr,$arr_indicators[$i],$target);				
			
			for($k=1;$k<((count($male_monthly)+count($female_monthly)+count($male_quarterly)+count($female_quarterly))/7);$k++){	
				for($l=0;$l<3;$l++){
					array_push($disp_arr,$male_monthly[$k+$load+$l],$female_monthly[$k+$load+$l]);
				}
				array_push($disp_arr,$male_quarterly[$k],$female_quarterly[$k]);
				$load+=2;
			}
	
			array_push($disp_arr,array_sum($male_quarterly),array_sum($female_quarterly));

			$this->SetWidths($header);
			
			//$this->Row($disp_arr);
                        
                        if($_SESSION[ques]==39):
                            $this->Row($disp_arr);
                        elseif($_SESSION[ques]==50):
                            $m_arr = array($disp_arr[0],$disp_arr[$m_index[$_SESSION[smonth]][0]],$disp_arr[$m_index[$_SESSION[smonth]][1]]);
                                    
                            for($x=0;$x<count($m_arr);$x++){                                                                
                                $this->Cell($header[$x],6,$m_arr[$x],'1',0,'L');
                            }
                            
                            $this->Ln();                        
                            //$this->Row(array($disp_arr[0],$disp_arr[$m_index[$_SESSION[smonth]][0]],$disp_arr[$m_index[$_SESSION[smonth]][1]]));
                        elseif($_SESSION[ques]==51):
                            $total_q = $disp_arr[$q_index[$_SESSION[quarter]][0]] + $disp_arr[$q_index[$_SESSION[quarter]][1]];
                            
                            $q_arr = array($disp_arr[0],$target,$disp_arr[$q_index[$_SESSION[quarter]][0]],$disp_arr[$q_index[$_SESSION[quarter]][1]],$total_q,$this->compute_ccdev_rate($target,$total_q),'','');

                            for($x=0;$x<count($q_arr);$x++){
                                $this->Cell($header[$x],6,$q_arr[$x],'1',0,'L');
                            }
                            $this->Ln();
                        else:
                            
                        endif;
                        
                        //print_r($disp_arr);

		endif;
		
		if(!empty($sub_arr)):

		else:

		endif;
	}
}

function compute_indicators(){
	if(func_num_args()>0):
		$arg_list = func_get_args();
		$crit = $arg_list[0];
		$header = $arg_list[1];
		$sub_arr_crit = $arg_list[2];
	endif;
	
	$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
	$arr_gender = array('M','F');
	$brgy_array = $this->get_brgy_array();
	$brgy_array = implode(',',$brgy_array);

	if(!empty($sub_arr_crit)):  //indicators with sub arrays 0,7,8,9,10

		switch($crit){
			case 1:
				$arr_antigen = array();

				foreach($sub_arr_crit as $antigen_label=>$antigen_array){
					
					foreach($antigen_array as $key=>$value){
					
					foreach($arr_gender as $key2=>$value2){

					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);					
					if($key=='HEPB1<24'):
						$q_antigen = mysql_query("SELECT a.actual_vaccine_date,a.vaccine_id,b.patient_id FROM m_consult_vaccine a,m_patient b WHERE a.patient_id=b.patient_id AND (TO_DAYS(a.actual_vaccine_date)-TO_DAYS(b.patient_dob)) <= 1 AND a.vaccine_id='HEPB1' AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$value2'") or die(mysql_error());
					elseif($key=='HEPB1>24'):
						//$q_antigen = mysql_query("SELECT (TO_DAYS(a.actual_vaccine_date)-TO_DAYS(b.patient_dob)) days,a.actual_vaccine_date,a.vaccine_id,b.patient_id FROM m_consult_vaccine a,m_patient b WHERE a.patient_id=b.patient_id AND (TO_DAYS(a.actual_vaccine_date)-TO_DAYS(b.patient_dob)) > 1 AND a.vaccine_id='HEPB1' AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$value2'") or die(mysql_error());						
						$q_antigen = mysql_query("SELECT a.actual_vaccine_date,a.vaccine_id,b.patient_id FROM m_consult_vaccine a,m_patient b WHERE a.patient_id=b.patient_id AND (TO_DAYS(a.actual_vaccine_date)-TO_DAYS(b.patient_dob)) > 1 AND a.vaccine_id='HEPB1' AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$value2'") or die(mysql_error());						
					else:
						$q_antigen = mysql_query("SELECT a.actual_vaccine_date,a.vaccine_id,b.patient_id FROM m_consult_vaccine a,m_patient b WHERE a.patient_id=b.patient_id AND floor((TO_DAYS(a.actual_vaccine_date)-TO_DAYS(b.patient_dob))/7) < 260 AND a.vaccine_id='$key' AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$value2'") or die(mysql_error());
					endif;

					if(mysql_num_rows($q_antigen)!=0):
						while(list($actual_vaccine_date,$vaccine_id,$patient_id)=mysql_fetch_array($q_antigen)){
							if($this->get_px_brgy($patient_id,$brgy_array)):								
								$month_stat[$this->get_max_month($actual_vaccine_date)] += 1;
							endif;
						}
					endif;
							
					array_push($arr_antigen,$month_stat);
					//echo '/'.$key.'/'.array_sum($month_stat).'<br>';
					}
					
					}
				}
				return $arr_antigen;
				break;
			
			case 8: //diarrhea (0-59 mos)
				
				$arr_diarrhea_seen = array();
				foreach($sub_arr_crit as $arr_diarrhea_label=>$diarrhea_array){
					foreach($diarrhea_array as $diarrhea_key=>$diarrhea_value){
						foreach($arr_gender as $sex_key=>$sex_label){
						$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);

							if($diarrhea_key=='num_case'):					

								$q_diarrhea = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,a.patient_dob,c.notes_id FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%diarrhea%' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die(mysql_error());
								
								if(mysql_num_rows($q_diarrhea)!=0):
									//echo mysql_num_rows($q_diarrhea).'<br>';
									while(list($consult_id,$consult_date,$pxid,$pxdob,$notes_id)=mysql_fetch_array($q_diarrhea)){
										if($this->get_px_brgy($pxid,$brgy_array)):
											$month_stat[$this->get_max_month($consult_date)] += 1;
										endif;
									}
								endif;
								

							elseif($diarrhea_key=='ort'):
								
								$q_ort = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,c.diarrhea_ort FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%diarrhea%' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND c.diarrhea_ort BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 339");

								if(mysql_num_rows($q_ort)!=0):
									while(list($consult_id,$consult_date,$patient_id,$diarrhea_ort)=mysql_fetch_array($q_ort)){
										if($this->get_px_brgy($patient_id,$brgy_array)):
											$month_stat[$this->get_max_month($diarrhea_ort)] += 1;
										endif;
									}
								endif;
							
							elseif($diarrhea_key=='ors'):
								$q_ors = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,c.diarrhea_ors FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%diarrhea%' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND c.diarrhea_ors BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 347");

								if(mysql_num_rows($q_ors)!=0):
									while(list($consult_id,$consult_date,$patient_id,$diarrhea_ors)=mysql_fetch_array($q_ors)){
										if($this->get_px_brgy($patient_id,$brgy_array)):
											$month_stat[$this->get_max_month($diarrhea_ors)] += 1;
										endif;
									}
								endif;
							
							elseif($diarrhea_key=='orswz'):
								$q_orswz = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,c.diarrhea_orswz FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%diarrhea%' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND c.diarrhea_orswz BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 356");

								if(mysql_num_rows($q_orswz)!=0):
									while(list($consult_id,$consult_date,$patient_id,$diarrhea_orswz)=mysql_fetch_array($q_orswz)){
										if($this->get_px_brgy($patient_id,$brgy_array)):
											$month_stat[$this->get_max_month($diarrhea_orswz)] += 1;
										endif;
									}
								endif;

							else:
							endif;

							array_push($arr_diarrhea_seen,$month_stat);
						}
					}
				}
				return $arr_diarrhea_seen;
				break;

			case 9: //pneumonia (0-59 mos)
				$arr_pneu_seen = array();

				foreach($sub_arr_crit as $arr_pneu_label=>$pneu_arr){
					foreach($pneu_arr as $pneu_key=>$pneu_label ){ 
						foreach($arr_gender as $sex_key=>$sex_label){
						
						$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
						
						if($pneu_key=='num_cases'):

						$q_pneu = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,a.patient_dob,c.notes_id FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%pneumonia%' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 382");

						if(mysql_num_rows($q_pneu)!=0):						
							while(list($consult_id,$consult_date,$pxid,$pxdob,$notes_id)=mysql_fetch_array($q_pneu)){
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($consult_date)] += 1;
								endif;
							}
						endif;

						elseif($pneu_key=='pneumonia_tx'):
						
						$q_pneu = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,a.patient_dob,c.notes_id,c.pneumonia_date_given FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%pneumonia%' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND c.pneumonia_date_given BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 390");

						if(mysql_num_rows($q_pneu)!=0):							
							while(list($consult_id,$consult_date,$pxid,$pxdob,$notes_id,$pneu_date)=mysql_fetch_array($q_pneu)){
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($pneu_date)] += 1;
								endif;
							}
						endif;
						
						else:
						endif;

						array_push($arr_pneu_seen,$month_stat);

						}					
					}				
				}
				return $arr_pneu_seen;
				break;
			
			case 10: // sick children seen
				$arr_sick = array();
				$r_consult = array();
				$r_sick1 = array();
				$r_sick2 = array();
				$r_sick3 = array();
				$arr_sakit = array('measles','severe pneumonia','persistent diarrhea','malnutrition','xerophthalmia','night		blindness','bitot','corneal xerosis','corneal ulcerations','keratomalacia');

				for($x=0;$x<count($arr_sakit);$x++){
					$str_sakit = "SELECT a.consult_id FROM m_consult a, m_consult_notes_dxclass b,m_lib_notes_dxclass c WHERE a.consult_id=b.consult_id AND b.class_id=c.class_id";

					$r_sakit = explode(" ",$arr_sakit[$x]);

					for($y=0;$y<count($r_sakit);$y++){
						$str_sakit.=" AND c.class_name LIKE'%$r_sakit[$y]%'";					
					}

					$q_sakit = mysql_query($str_sakit) or die(mysql_error());
					
					if(mysql_num_rows($q_sakit)!=0):
						while(list($consult_id,$pxid)=mysql_fetch_array($q_sakit)){
								array_push($r_consult,$consult_id);
						}
					endif;
	
				}
				$r_consult = array_unique($r_consult);
				$str_consult_id = implode(',',$r_consult);
				
				//print_r($str_consult_id);
				
				foreach($sub_arr_crit as $arr_key=>$arr_label){

				foreach($arr_label as $sick_key=>$sick_label){
				
				foreach($arr_gender as $sex_key=>$sex_label){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
					
					for($m=0;$m<=count($r_consult);$m++){

					if($sick_key=='6*11'):
						$q_sick = mysql_query("SELECT a.patient_id, date_format(b.consult_date,'%Y-%m-%d'),b.consult_id,round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) FROM m_patient a,m_consult b WHERE a.patient_id=b.patient_id AND b.consult_id='$r_consult[$m]' AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_gender='$sex_label' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 6 AND 11") or die("Cannot query: 440");

					if(mysql_num_rows($q_sick)!=0):
						array_push($r_sick1,$r_consult[$m]);
						while(list($pxid,$consult_date,$consult_id,$range)=mysql_fetch_array($q_sick)){							
							if($this->get_px_brgy($pxid,$brgy_array)):
								$month_stat[$this->get_max_month($consult_date)] += 1;
							endif;
						}
					endif;

					elseif($sick_key=='12*59'):

						$q_sick = mysql_query("SELECT a.patient_id, date_format(b.consult_date,'%Y-%m-%d'),b.consult_id,round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) FROM m_patient a,m_consult b WHERE a.patient_id=b.patient_id AND b.consult_id='$r_consult[$m]' AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_gender='$sex_label' AND round(((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30),2) BETWEEN 12 AND 59") or die("Cannot query: 440");
						


						if(mysql_num_rows($q_sick)!=0):
							while(list($pxid,$consult_date,$consult_id,$range)=mysql_fetch_array($q_sick)){
								//echo $pxid.'/'.$consult_date.'/'.$consult_id.'/'.$range.'<br>';

								if($this->get_px_brgy($pxid,$brgy_array)):
									array_push($r_sick2,$r_consult[$m]);
									$month_stat[$this->get_max_month($consult_date)] += 1;
								endif;
							}
						endif;

					elseif($sick_key=='60*71'):
						$q_sick = mysql_query("SELECT a.patient_id, date_format(b.consult_date,'%Y-%m-%d'),b.consult_id FROM m_patient a,m_consult b WHERE a.patient_id=b.patient_id AND b.consult_id='$r_consult[$m]' AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_gender='$sex_label' AND round(((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30),2) BETWEEN 50 AND 71") or die("Cannot query: 440");
						
						if(mysql_num_rows($q_sick)!=0):
							while(list($pxid,$consult_date,$consult_id,$range)=mysql_fetch_array($q_sick)){
								if($this->get_px_brgy($pxid,$brgy_array)):
									array_push($r_sick3,$r_consult[$m]);
									$month_stat[$this->get_max_month($consult_date)] += 1;
								endif;
							}
						endif;
					
					else:
					endif;

					}					
					array_push($arr_sick,$month_stat);
				}

				}
				}
				$r_sicko = array();
				
				array_push($r_sicko,$r_sick1,$r_sick2,$r_sick3);
				
				//print_r($r_sicko);
				$_SESSION[sick_consult_id] = $r_sicko;

				return $arr_sick;
				break;
			
			case 11: //sick children given vitamin A
				$arr_sick_vita = array();

				$r_consult = $_SESSION[sick_consult_id];
				//print_r($r_consult);

				foreach($sub_arr_crit as $arr_key=>$arr_label){
					foreach($arr_label as $vita_key=>$vita_label){
						foreach($arr_gender as $sex_key=>$sex_label){
							$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);					
							
							if($vita_key=='6*11'):
								foreach($r_consult[0] as $consult_key=>$consult_id){
									$q_vita = mysql_query("SELECT a.vita_date,b.patient_id FROM m_consult_notes a,m_patient b WHERE a.patient_id=b.patient_id AND a.consult_id='$consult_id' AND b.patient_gender='$sex_label'") or die("Cannot query 520");

									if(mysql_num_rows($q_vita)!=0):
										list($vita_date,$pxid) = mysql_fetch_array($q_vita);
										
										if($this->get_px_brgy($pxid,$brgy_array)):
											$month_stat[$this->get_max_month($vita_date)] += 1;
										endif;
									endif;
								}

							elseif($vita_key=='12*59'):
								
								
								foreach($r_consult[1] as $consult_key=>$consult_id){																
									$q_vita = mysql_query("SELECT a.vita_date,b.patient_id FROM m_consult_notes a,m_patient b WHERE a.patient_id=b.patient_id AND a.consult_id='$consult_id' AND b.patient_gender='$sex_label'") or die("Cannot query: 530");
								}
								
								if($q_vita):		
								if(mysql_num_rows($q_vita)!=0):
									list($vita_date,$pxid) = mysql_fetch_array($q_vita);
									
									if($this->get_px_brgy($pxid,$brgy_array)):
										$month_stat[$this->get_max_month($vita_date)] += 1;
									endif;
								endif;
								
								endif;
								
						

							elseif($vita_key=='60*71'):
								foreach($r_consult[2] as $consult_key=>$consult_id){
									$q_vita = mysql_query("SELECT a.vita_date,b.patient_id FROM m_consult_notes a,m_patient b WHERE a.patient_id=b.patient_id AND a.consult_id='$consult_id' AND b.patient_gender='$sex_label'") or die("Cannot query: 530");								
								}

								if($q_vita):
								
								if(mysql_num_rows($q_vita)!=0):
									list($vita_date,$pxid) = mysql_fetch_array($q_vita);

										if($this->get_px_brgy($pxid,$brgy_array)):
											$month_stat[$this->get_max_month($vita_date)] += 1;
										endif;
								endif;

								endif;
							else:
							endif;
						array_push($arr_sick_vita,$month_stat);
						}


					}				
				}
				return $arr_sick_vita;

				break;
					


			default:

			break;
			
		
		}	
	else: // single string indicator
		$arr_gender_stat = array();

		switch($crit){
			case 2: // fully immunized child
				
				for($sex=0;$sex<count($arr_gender);$sex++){
				$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
				$q_cic = mysql_query("SELECT DISTINCT a.patient_id, MAX(b.actual_vaccine_date),floor((TO_DAYS(MAX(b.actual_vaccine_date)) - TO_DAYS(a.patient_dob))/30) days_vacc,a.patient_dob FROM m_patient a,m_consult_vaccine b WHERE a.patient_id=b.patient_id AND a.patient_gender='$arr_gender[$sex]' GROUP by a.patient_id") or die(mysql_query());
				
					while(list($pxid,$actual_vaccine_date,$day_vacc,$patient_dob)=mysql_fetch_array($q_cic)){
						list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
						list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
						list($vtaon,$vbuwan,$vdate) = explode('-',$actual_vaccine_date);

						$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
						$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
						$vacc = mktime(0,0,0,$vbuwan,$vdate,$vtaon);

						if($vacc>=$start && $vacc<=$end):
							if($this->determine_vacc_status($pxid)=='FIC'):
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($actual_vaccine_date)] += 1;
								endif;
							endif;
						endif;

					}
				array_push($arr_gender_stat,$month_stat);
				}
				break;
			
			case 3: //completely immunized child (12-23 mos)
				for($sex=0;$sex<count($arr_gender);$sex++){
				
				$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
				
				$q_cic = mysql_query("SELECT DISTINCT a.patient_id, MAX(b.actual_vaccine_date),floor((TO_DAYS(MAX(b.actual_vaccine_date)) - TO_DAYS(a.patient_dob))/30) days_vacc,a.patient_dob FROM m_patient a,m_consult_vaccine b WHERE a.patient_id=b.patient_id AND a.patient_gender='$arr_gender[$sex]' GROUP by a.patient_id") or die(mysql_query());
				
					while(list($pxid,$actual_vaccine_date,$day_vacc,$patient_dob)=mysql_fetch_array($q_cic)){
						list($staon,$sbuwan,$sdate) = explode('-',$_SESSION[sdate2]);
						list($etaon,$ebuwan,$edate) = explode('-',$_SESSION[edate2]);
						list($vtaon,$vbuwan,$vdate) = explode('-',$actual_vaccine_date);

						$start = mktime(0,0,0,$sbuwan,$sdate,$staon);
						$end = mktime(0,0,0,$ebuwan,$edate,$etaon);
						$vacc = mktime(0,0,0,$vbuwan,$vdate,$vtaon);
						
						if($vacc>=$start && $vacc<=$end):
							if($this->determine_vacc_status($pxid)=='CIC'):
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($actual_vaccine_date)] += 1;
								endif;
							endif;
						endif;

					}
				array_push($arr_gender_stat,$month_stat);
				}				
				break;

			case 4:  //cpab
				
				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
					
					$q_cpab = mysql_query("SELECT DISTINCT a.patient_id,a.date_registered FROM m_patient_ccdev a, m_patient b WHERE a.patient_id=b.patient_id AND a.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$arr_gender[$sex]'") or die("Cannot query:385");

					while(list($pxid,$date_reg)=mysql_fetch_array($q_cpab)){
						// kelan matatara ang CPAB? date of ccdev reg, date of birth, date of TT intake of mom
						
						list($vacc_id,$status,$vacc_date) = explode('*',$this->get_cpab($pxid));

						if($status=='Active'):
							if($this->get_px_brgy($pxid,$brgy_array)):
								$month_stat[$this->get_max_month($date_reg)] += 1;
							endif;
						endif;
					}
					array_push($arr_gender_stat,$month_stat);
				}
				
				break;

			case 5: //infants seen at 6 mos
				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
					
					$q_sixth = mysql_query("SELECT DISTINCT a.patient_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_dob  FROM m_patient a, m_consult b,m_patient_ccdev c WHERE a.patient_id=b.patient_id AND b.patient_id=c.patient_id AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d')) - TO_DAYS(a.patient_dob))/30,2) BETWEEN 6 AND 6.999 AND a.patient_gender='$arr_gender[$sex]'") or die("Cannot query:396");

					if(mysql_num_rows($q_sixth)!=0):
						while(list($pxid,$consult_date,$px_dob)=mysql_fetch_array($q_sixth)){
							//echo $pxid.'/'.$consult_date.'/'.$px_dob.'<br>';
							if($this->get_px_brgy($pxid,$brgy_array)):
								$month_stat[$this->get_max_month($consult_date)] += 1;
							endif;
						}
					endif;
					array_push($arr_gender_stat,$month_stat);
				}

				break;
			
			case 6: //infant exclusively bfeed until 6 month
				for($sex=0;$sex<count($arr_gender);$sex++){
					
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
					
					$q_bfeed = mysql_query("SELECT a.patient_id, b.bfed_month1,b.bfed_month2,b.bfed_month3,b.bfed_month4,b.bfed_month5,b.bfed_month6,b.bfed_month6_date FROM m_patient a, m_patient_ccdev b WHERE a.patient_id=b.patient_id AND round((TO_DAYS(b.bfed_month6_date) - TO_DAYS(a.patient_dob))/30,2) BETWEEN 6 AND 7 AND a.patient_gender='$arr_gender[$sex]' AND b.bfed_month6_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die(mysql_error());

					if(mysql_num_rows($q_bfeed)!=0):
						while($r_bfeed = mysql_fetch_array($q_bfeed)){
							if(!in_array('N',$r_bfeed)):
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($r_bfeed[bfed_month6_date])] += 1;
								endif;
							endif;
						}
					endif;
					array_push($arr_gender_stat,$month_stat);				
				}
				
				
				break;
			
			case 7: //referred to the NBS
				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);

					$q_nbs = mysql_query("SELECT DISTINCT a.patient_id, MIN(b.ccdev_service_date),b.service_id FROM m_patient a, m_consult_ccdev_services b WHERE a.patient_id=b.patient_id AND b.service_id='NBS' AND a.patient_gender='$arr_gender[$sex]' AND round((TO_DAYS(b.ccdev_service_date)-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 11 GROUP by a.patient_id") or die(mysql_error());

					if(mysql_num_rows($q_nbs)!=0):

						while(list($pxid,$ccdev_service_date,$service_id)=mysql_fetch_array($q_nbs)){
							list($staon,$smonth,$sdate) = explode('-',$_SESSION[sdate2]);
							list($etaon,$emonth,$edate) = explode('-',$_SESSION[edate2]);
							list($vtaon,$vmonth,$vdate) = explode('-',$ccdev_service_date);

							$start = mktime(0,0,0,$smonth,$sdate,$staon);
							$end = mktime(0,0,0,$emonth,$edate,$etaon);
							$serv_date = mktime(0,0,0,$vmonth,$vdate,$vtaon);

							if($serv_date>=$start && $serv_date<=$end):
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($ccdev_service_date)] += 1;
								endif;
							endif;
						}
					endif;

					array_push($arr_gender_stat,$month_stat);
				}
				
				break;
			
			case 12: //infants 2-6 mos with LBW

				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);

					$q_lbw = mysql_query("SELECT a.patient_id,b.ccdev_id,b.date_registered FROM m_patient a,m_patient_ccdev b WHERE a.patient_id=b.patient_id AND round((TO_DAYS(b.date_registered) - TO_DAYS(a.patient_dob))/30,2) BETWEEN 2 AND 6 AND b.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_gender='$arr_gender[$sex]'") or die("Cannot query: 715");

					if(mysql_num_rows($q_lbw)!=0):
						while(list($pxid,$ccdevid,$ccdev_date)=mysql_fetch_array($q_lbw)){
							if($this->get_lbw($ccdevid)=='lbw'):
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($ccdev_date)] += 1;
								endif;
							endif;
						}
					endif;


				array_push($arr_gender_stat,$month_stat);
				}
				break;
			
			case 13: //infants 2-6 mos with LBW given iron
				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
					
					$q_lbw = mysql_query("SELECT a.patient_id,b.ccdev_id,b.date_registered,b.lbw_date_started,b.lbw_date_completed FROM m_patient a,m_patient_ccdev b WHERE a.patient_id=b.patient_id AND round((TO_DAYS(b.date_registered) - TO_DAYS(a.patient_dob))/30,2) BETWEEN 2 AND 6 AND b.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND round((TO_DAYS(b.lbw_date_completed) - TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 6.999 AND a.patient_gender='$arr_gender[$sex]'") or die("Cannot query: 715");

					if(mysql_num_rows($q_lbw)!=0):

						while(list($pxid,$ccdevid,$ccdev_date,$start_date,$completed_date)=mysql_fetch_array($q_lbw)){
							if($this->get_lbw($ccdevid)=='lbw'):
								if($this->get_px_brgy($pxid,$brgy_array)):
									$month_stat[$this->get_max_month($completed_date)] += 1;
								endif;
							endif;
						}
					endif;					
				array_push($arr_gender_stat,$month_stat);
				}
				
				break;
			case 14:
				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);			
					
					$q_anemia = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,a.patient_dob,c.notes_id FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%anemia%' AND a.patient_gender='$arr_gender[$sex]' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 753");
					
					if(mysql_num_rows($q_anemia)!=0):
						while(list($consult_id,$consult_date,$pxid,$pxdob,$notes_id) = mysql_fetch_array($q_anemia)){
							if($this->get_px_brgy($pxid,$brgy_array)):
								$month_stat[$this->get_max_month($consult_date)] += 1;
							endif;
						}
					endif;
				
				array_push($arr_gender_stat,$month_stat);
				
				}
				break;
			
			case 15:
				for($sex=0;$sex<count($arr_gender);$sex++){
					$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);

					$q_anemia = mysql_query("SELECT DISTINCT b.consult_id,date_format(b.consult_date,'%Y-%m-%d'),a.patient_id,a.patient_dob,c.notes_id,f.anemia_completed_date FROM m_patient a, m_consult b,m_consult_notes c,m_consult_notes_dxclass d,m_lib_notes_dxclass e,m_consult_notes f WHERE a.patient_id=b.patient_id AND b.consult_id=c.consult_id AND c.notes_id=d.notes_id AND d.class_id=e.class_id AND e.class_name LIKE '%anemia%' AND a.patient_gender='$arr_gender[$sex]' AND round((TO_DAYS(date_format(b.consult_date,'%Y-%m-%d'))-TO_DAYS(a.patient_dob))/30,2) BETWEEN 0 AND 59 AND b.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.consult_id=f.consult_id AND f.anemia_completed_date!='0000-00-00'") or die("Cannot queru: 770");

					if(mysql_num_rows($q_anemia)!=0):
						while(list($consult_id,$consult_date,$pxid,$pxdob,$notes_id,$anemia_completed)=mysql_fetch_array($q_anemia)){	
							if($this->get_px_brgy($pxid,$brgy_array)):
								$month_stat[$this->get_max_month($consult_date)] += 1;
							endif;
						}
					endif;
				array_push($arr_gender_stat,$month_stat);
				}

				break;			

			default:	
			
				break;
		}	

		return $arr_gender_stat;

	endif;
}

function determine_vacc_status(){
	if(func_num_args()>0):		
		$arg_list = func_get_args();
		$pxid = $arg_list[0];
	endif;

	$antigens = array('BCG','DPT1','DPT2','DPT3','HEPB1','HEPB2','HEPB3','MSL','OPV1','OPV2','OPV3');
	$antigen_stat = array('BCG'=>0,'DPT1'=>0,'DPT2'=>0,'DPT3'=>0,'HEPB1'=>0,'HEPB2'=>0,'HEPB3'=>0,'MSL'=>0,'OPV1'=>0,'OPV2'=>0,'OPV3'=>0);
	$cic = 0;
	
	$antigen_date = array();
	
	for($i=0;$i<count($antigens);$i++){
		$q_vacc = mysql_query("SELECT MIN(actual_vaccine_date) FROM m_consult_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigens[$i]' GROUP by patient_id") or die(mysql_error());
	
		if(mysql_num_rows($q_vacc)!=0):
			list($actual_vdate) = mysql_fetch_array($q_vacc);
			$antigen_stat[$antigens[$i]] = 1;
			$antigen_date[$antigens[$i]] = $actual_vdate;
		else:
			$antigen_date[$antigens[$i]] = 0;
		endif;
	}


	if(in_array('0',$antigen_stat)): //incomplete vaccination
		return 'Incomplete';
		//print_r($antigen_stat);
	else:
		for($j=0;$j<count($antigens);$j++){
			$ant_date = $antigen_date[$antigens[$j]];

			$q_antigen = mysql_query("SELECT round((TO_DAYS('$ant_date') - TO_DAYS(a.patient_dob))/30,2) month_span FROM m_patient a WHERE a.patient_id='$pxid'") or die("Cannot query: 269");
			list($month_span) = mysql_fetch_array($q_antigen);

			if($month_span>12 && $month_span<=23):
				$cic=1;
			endif;
		}
	endif;
	
	arsort($antigen_date);
//	print_r($antigen_date).'<br>';
	
	if($cic==1):
		return 'CIC';
	else:
		return 'FIC';
	endif;
}

function get_lbw($ccdev_id){
	$q_lbw = mysql_query("SELECT birth_weight,lbw_date_started,lbw_date_completed FROM m_patient_ccdev WHERE ccdev_id='$ccdev_id'") or die("cannot query: 189");

	if(mysql_num_rows($q_lbw)!=0):
		list($bwt,$lbw_sdate,$lbw_edate) = mysql_fetch_array($q_lbw);
		$sdate = ($lbw_sdate=='0000-00-00')?' - ':$lbw_sdate;
		$edate = ($lbw_edate=='0000-00-00')?' - ':$lbw_edate;

		if($bwt<0.25):
			$lbw_status = 'lbw';
		else:
			$lbw_status = 'normal';
		endif;
	endif;
	
	return $lbw_status;
}

function get_max_month($date){
	list($taon,$buwan,$araw) = explode('-',$date);
	$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

	return $max_date;
}

function disp_blank_header($header_title,$target){
	$header = array(30,18,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9,9,9,9,9,8,7,9,9);
	$this->SetWidths($header);
	$disp_arr = array($header_title,$target);
	for($x=0;$x<35;$x++){
		array_push($disp_arr,'');
	}				
	$this->Row($disp_arr);
}

function disp_arr_indicator(){
	if(func_num_args()>0):
		$arg_list = func_get_args();
		$crit = $arg_list[0];
		$sub_crit = $arg_list[1];
	endif;

	switch($crit){
		case 0:
			$r_label = array('BCG','DPT1','DPT2','DPT3','OPV1','OPV2','OPV3','Hepa B1 w/in 24 hrs','Hepa B1 > 24 hrs','Hepatitis B2','Hepatitis B3','Measles'); 
			return $r_label[$sub_crit];
			break;

		case 7:
			$r_label = array('No. of Diarreha Cases','No. of Diarrhea Cases Given ORT','No. of Diarrhea Cases Given ORS','No. of Diarrhea Cases Given ORS w/ zinc');
			
			return $r_label[$sub_crit];
			break;
		case 8:
			$r_label = array('No. of Pneumonia Cases','Given Treatment');
			return $r_label[$sub_crit];

			break;
		case 9:
			$r_label = array('6-11 mos','12-59 mos','60-71 mos');
			return $r_label[$sub_crit];
			break;

		case 10:
			$r_label = array('6-11 mos','12-59 mos','60-71 mos');	
			return $r_label[$sub_crit];
			break;
		default:

			break;
	}

}

function get_brgy_pop(){
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

function get_quarterly_total($r_month,$target){
	$q_total = array();
	$counter = 0;
	
	for($i=1;$i<=4;$i++){
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
function get_cpab($pxid){
		$q_mother = mysql_query("SELECT a.date_registered,date_format(a.ccdev_timestamp,'%Y-%m-%d') date_stamp,a.mother_px_id,b.patient_lastname,b.patient_firstname FROM m_patient_ccdev a, m_patient b WHERE a.patient_id='$pxid' AND b.patient_id=a.mother_px_id AND b.patient_gender='F'") or die(mysql_error());	

		$get_bday = mysql_query("SELECT patient_dob from m_patient where patient_id='$pxid'") or die("cannot query: 581");
		list($px_dob) = mysql_fetch_array($get_bday);


		if(mysql_num_rows($q_mother)!=0):
			list($reg_date,$date_stamp,$mother_id,$lname,$fname) = mysql_fetch_array($q_mother);
			$status = $this->get_tt_status(0,$mother_id,$px_dob);

		else:
			$status = "";
		endif;		

		return $status;
}

function get_tt_status(){
		$arr_tt = array(1=>0,2=>0,3=>0,4=>0,5=>0);
		$tt_duration = array(1=>0,2=>1095,3=>1825,4=>3650,5=>10000); //number of days of effectiveness
		$highest_tt = 0;
		$protected = 0;

		if(func_num_args()>0){
			$arg_list = func_get_args();
			$mc_id = $arg_list[0];
			$pxid = $arg_list[1];
			$pxedc = $arg_list[2];
		}
		
		for($i=1;$i<=5;$i++){
			$antigen = 'TT'.$i;
			$q_vacc = mysql_query("SELECT MAX(actual_vaccine_date),vaccine_id,mc_id FROM m_consult_mc_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigen' AND actual_vaccine_date<='$pxedc' GROUP by patient_id") or die("Cannot query: 2368");
						
			if(mysql_num_rows($q_vacc)!=0):
				list($vacc_date,$vacc_id,$mcid) = mysql_fetch_array($q_vacc);			
				$arr_tt[$i] = $vacc_date;
			endif;

		}
				
		for($j<=1;$j<=5;$j++){
			
//			echo $arr_tt[$j].'/'.$j.'<br>';
			
			//case 1: use this test case to refer to the highest TT. once a blank TT is considered, the last highest vaccine is considered. this will likely miss higher vaccinations after the blank
			/*if($arr_tt[$j]=='0' && $highest_tt==0):
				$highest_tt = $j-1; //get the previous TT antigen
			endif; */
			
			//case 2: use this scenario to get the highest possible 
			if($arr_tt[$j]!='0'):  
				$highest_tt = $j; //get the previous TT antigen
				$date_tt = $arr_tt[$j];
			endif;
		}

		$highest_tt = ($heighest_tt<5)?$highest_tt:5;				

		if($highest_tt==1 || $highest_tt==0):
			$protected = 0;
		elseif($highest_tt==5):
			$protected = 1;
		else:
			$antigen = 'TT'.$highest_tt;

			$q_diff = mysql_query("SELECT consult_id,(TO_DAYS('$pxedc')-TO_DAYS(actual_vaccine_date)) vacc_diff FROM m_consult_mc_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigen' AND (TO_DAYS('$pxedc')-TO_DAYS(actual_vaccine_date)) <= '$tt_duration[$highest_tt]'") or die("Cannot query: 2399");
			
			if(mysql_num_rows($q_diff)!=0):
				//list($consult_id,$vacc_diff) = mysql_fetch_row($q_diff);
				$protected = 1;
			endif;
		endif;
		
		$tt_stat = 'TT'.$highest_tt.'*';
		$tt_stat.=($protected==1)?'Active':'Not Active';
		$tt_stat.= '*'.$date_tt;

		return $tt_stat;		
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

function get_px_brgy(){

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
    

function get_brgy(){
    $arr_brgy = array();
      
    if(in_array('all',$_SESSION[brgy])):
        $q_brgy = mysql_query("SELECT barangay_id FROM m_lib_barangay ORDER by barangay_id ASC") or die("Cannot query 252". mysql_error());
        
        while(list($brgy_id) = mysql_fetch_array($q_brgy)){            
            array_push($arr_brgy,$brgy_id);
        }
    else:
        $arr_brgy = $_SESSION[brgy];
    endif;
                                                                  
    
    $str_brgy = implode(',',$arr_brgy);

                                                                          
    return $str_brgy;
}


function compute_ccdev_rate($target,$actual){
    if($target==0):
        return 0;
    else:
        return round((($actual/$target)*100),0);
    endif;
}


}

$pdf = new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$pdf->show_ccdev_summary();

$pdf->Output();

?>
