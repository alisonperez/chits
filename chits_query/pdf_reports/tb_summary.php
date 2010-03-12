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
        
	$gender = array();
	
	$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];
        
	$municipality_label = $_SESSION[datanode][name];
	
	$this->SetFont('Arial','B',12);

	
	if($_SESSION[ques]==94):
	
	$this->Cell(0,5,'Tuberculosis Summary Table ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
	
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
	$this->Ln(10);
	//$w = array(30,18,18,18,18,15,18,18,18,15,18,18,18,15,18,18,18,15,18); //340
	$w = array(54,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16); //340
    	$header = array('INDICATORS','Target','JAN','FEB','MAR','1st Q','APR','MAY','JUNE','2nd Q','JULY','AUG','SEPT','3rd Q','OCT','NOV','DEC','4th Q','TOTAL');	
      		
	
	
	$w2 = array(54,16,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8);
	array_push($gender,' ',' ');
	for($i=0;$i<17;$i++){
	  array_push($gender,'M','F');	  
	}
	
	
	
	elseif($_SESSION[ques]==92 || $_SESSION[ques]==93):
	    $this->q_report_header();		   
                                        	    
    	    if($_SESSION[ques]==92): //TB Q report table header
    	        $this->SetFont('Arial','B',15);	    	        	    
    	        $this->Cell(310,8,'D I S E A S E   C O N T R O L',1,1,C);    	    
    	        $this->SetFont('Arial','B',12);
    	        
                
                $w = array('100','90','60','60');
                $header = array('TUBERCULOSIS','Number','Interpretation','Recommendation / Actions Taken');
	    
                $w2 = array('100','30','30','30','60','60');
                $header2 = array(' ','Male','Female','Total',' ',' ');
                
	    elseif($_SESSION[ques]==93): //TB M report table header
	        $this->SetFont('Arial','B',15);	    	        	    
    	        $this->Cell(190,8,'D I S E A S E   C O N T R O L',1,1,C);
    	        
	        $this->SetFont('Arial','B',12);

	        $w = array('100','45','45');
                $header = array('TUBERCULOSIS','MALE','FEMALE');
	    
	    else:
	    
	    endif;
	else:
	
	endif;			
	
	$this->SetWidths($w);
	$this->Row($header);
	
	$this->SetWidths($w2);
	$this->Row($gender);		
	
	$this->Row($header2);
}

function q_report_header(){
    $this->SetFont('Arial','B','12');
    
    $taon = $_SESSION[year];
    
    if($_SESSION[ques]==92): //TB Quarterly Report
        $freq = 'FHSIS REPORT FOR THE QUARTER: ';
        $freq_val = $_SESSION[quarter];
    elseif($_SESSION[ques]==93): //TB Monthly Report
        $freq = 'FHSIS REPORT FOR THE MONTH: ';
        $freq_val = date('F',mktime(0,0,0,$_SESSION[smonth],1,0));
    else:
        
    endif;
    
    $this->show_header_freq($freq,$freq_val);
    $this->show_header_bhs();
    $this->show_header_rhu();
    $this->show_header_lgu();
    $this->show_header_province();
    $this->Ln();                    
}

function show_header_freq($freq,$freq_val){    
    $this->Cell(0,5,$freq.$freq_val."          YEAR: ".$_SESSION[year],0,1,L);    
}

function show_header_bhs(){    
    if($_SESSION[ques]==93):  //applies only to W-BHS and M2 reports
        $this->Cell(0,5,'NAME OF BHS/BHC- '.$this->get_brgy_header(),0,1,L);
    endif;
}

function show_header_rhu(){
    if($_SESSION[ques]==93):   //applies only to W-BHS and M2 reports
        $this->Cell(0,5,'CATCHMENT RHU/BHS: '.$_SESSION[datanode][name],0,1,L);        
    endif;
}

function show_header_lgu(){
    if($_SESSION[ques]==92): //applies only to Q2 and A2 reports
        $this->Cell(0,5,'MUNICIPALITY/CITY OF: '.$_SESSION[lgu],0,1,L);
    endif;
}
                
function show_header_province(){
    if($_SESSION[ques]==92): //applies only to Q2 and A2 reports
        $this->Cell(0,5,'PROVINCE: '.$_SESSION[province],0,1,L);
    endif;
}
                                
                            

function show_tb_summary(){
	
	
	$brgy_pop = $this->get_brgy_pop();    //compute for the brgy population: ALL or specific brgys only
        $target_pop = $this->get_target($brgy_pop); //compute for the target of FP        
        $str_brgy = $this->get_brgy(); //return list of barangays in CSV format
        $m_index = array('1'=>array('2','3'),'2'=>array('4','5'),'3'=>array('6','7'),'4'=>array('10','11'),'5'=>array('12','13'),'6'=>array('14','15'),'7'=>array('18','19'),'8'=>array('20','21'),'9'=>array('22','23'),'10'=>array('26','27'),'11'=>array('28','29'),'12'=>array('30','31'));
        $q_index = array('1'=>array('8','9'),'2'=>array('16','17'),'3'=>array('24','25'),'4'=>array('32','33'));
                
        $header = array(54,16,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8);              
        $arr_indicators = array('TB Symptomatics who underwent DSSM','Smear Positive Discovered','New Smear (+) cases initiated treatment','New Smear (+) cured','Smear (+) retreatment case initiated Tx','Smear (+) retreatment case for cured');
        
        foreach($arr_indicators as $key=>$value){
          $arr_total = array();
          $arr_gender_stat = array();
          $disp_arr = array($value,'');        
          $load = 0;  
                    
          $brgy_pop = $this->get_brgy_pop();
          $target_perc = $this->get_target($brgy_pop);
          
          $arr_gender_stat = $this->compute_indicator($key);
          
          $male_monthly = $arr_gender_stat[0];
          $female_monthly = $arr_gender_stat[1];
          
          $male_quarterly = $this->get_quarterly_total($male_monthly);
          $female_quarterly = $this->get_quarterly_total($female_monthly);
          
          //print_r($female_monthly);                                        
          
          for($k=1;$k<((count($male_monthly)+count($female_monthly)+count($male_quarterly)+count($female_quarterly))/7);$k++){
            for($l=0;$l<3;$l++){
              array_push($disp_arr,$male_monthly[$k+$load+$l],$female_monthly[$k+$load+$l]);
            }

            array_push($disp_arr,$male_quarterly[$k],$female_quarterly[$k]);
            $load+=2;
          }
          
          array_push($disp_arr,array_sum($male_quarterly),array_sum($female_quarterly)); //grand total
          
          if($_SESSION[ques]==94):    
              $header = array(54,16,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8);                                  
              $this->SetWidths($header);
              $this->Row($disp_arr);  
          
          elseif($_SESSION[ques]==93): //TB M report
              $header = array('100','45','45');          
              
              $m_arr = array('     '.$disp_arr[0],$disp_arr[$m_index[$_SESSION[smonth]][0]],$disp_arr[$m_index[$_SESSION[smonth]][1]]);
              
              $this->Cell($header[0],6,$m_arr[0],'1',0,'L');   
              $this->Cell($header[1],6,$m_arr[1],'1',0,'L');   
              $this->Cell($header[2],6,$m_arr[2],'1',0,'L');   
              $this->Ln();                               
          
          elseif($_SESSION[ques]==92):
              $header = array('100','30','30','30','60','60');
              
              $total_q =  $disp_arr[$q_index[$_SESSION[quarter]][0]] + $disp_arr[$q_index[$_SESSION[quarter]][1]];
              $q_arr = array($disp_arr[0],$disp_arr[$q_index[$_SESSION[quarter]][0]],$disp_arr[$q_index[$_SESSION[quarter]][1]],$total_q,'','');
              
              $this->Cell($header[0],6,$q_arr[0],'1',0,'L');
              $this->Cell($header[1],6,$q_arr[1],'1',0,'L');
              $this->Cell($header[2],6,$q_arr[2],'1',0,'L');
              $this->Cell($header[3],6,$q_arr[3],'1',0,'L');
              $this->Cell($header[4],6,$q_arr[4],'1',0,'L');
              $this->Cell($header[5],6,$q_arr[5],'1',0,'L');
              
              $this->Ln();
          else:
          
          endif;                    
          
        }			
}


function compute_indicator($indicator){
  if(func_num_args()>0):
                    
  endif;
  
  $month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
  $arr_gender = array('M','F');
  $brgy = $this->get_brgy();
  $arr_gender_stat = array();  
  
    
    switch($indicator){
      
      case 0:   //tb symptomatic who underwent DSSM. check if: 1. px is symptomatic (m_consult_ntp_symptomatics) and 2). at least 1 DSSM was made (m_consult_lab_sputum).
        for($x=0;$x<count($arr_gender);$x++){        
        $month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
          
        $q_symp = mysql_query("SELECT a.patient_id, a.date_seen, b.sp3_collection_date FROM m_consult_ntp_symptomatics a, m_consult_lab_sputum b,m_patient c WHERE a.patient_id=c.patient_id AND a.sputum_diag1=b.request_id AND b.release_flag='Y' AND b.sp3_collection_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND c.patient_gender='$arr_gender[$x]'") or die("Cannot query 216".mysql_error());
        
        if(mysql_num_rows($q_symp)!=0):
          
          while(list($pxid,$date_seen,$sp3_date)=mysql_fetch_array($q_symp)){            
            if($this->get_px_brgy($pxid,$brgy)){
              $month_stat[$this->get_max_month($sp3_date)] += 1;
            }          
          }
        endif;          
          array_push($arr_gender_stat,$month_stat);
  
        }
                
        break;
      
      case 1: //Smear (+) discovered. 1. px is found positive (+) for the sputum exam (both symptomatic and ongoing cases)
        
        for($x=0;$x<count($arr_gender);$x++){
          $month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
                    
          $q_smear = mysql_query("SELECT a.patient_id,a.sp3_collection_date,a.lab_diagnosis FROM m_consult_lab_sputum a,m_patient b WHERE a.lab_diagnosis='P' AND a.release_flag='Y' AND a.sp3_collection_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.patient_gender='$arr_gender[$x]'") or die("Cannot query 260 ".mysql_error());
                    
          if(mysql_num_rows($q_smear)!=0):
            while(list($pxid,$sp3_date,$lab_diag)=mysql_fetch_array($q_smear)){
              if($this->get_px_brgy($pxid,$brgy)):
                $month_stat[$this->get_max_month($sp3_date)] += 1;
              endif;
            }            
          endif;
          
          array_push($arr_gender_stat,$month_stat);
        
        }
        
        break;
        
      case 2: // new TB patients initiated tretment, 1. new case, 2. have not taken TB drugs in less than a month
        
        for($x=0;$x<count($arr_gender);$x++){
        
        $month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);        
        
        $q_ntp = mysql_query("SELECT a.patient_id,a.ntp_id,a.patient_type_id,a.intensive_start_date FROM m_patient_ntp a, m_patient b WHERE a.intensive_start_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_type_id='NEW' AND a.patient_id=b.patient_id AND b.patient_gender='$arr_gender[$x]'") or die("Cannot query 279 ".mysql_error());
        
        if(mysql_num_rows($q_ntp)!=0):
          
          while(list($pxid,$ntp_id,$pxtype,$intensive_date)=mysql_fetch_array($q_ntp)){
            if($this->get_px_brgy($pxid,$brgy)):
              $month_stat[$this->get_max_month($intensive_date)] += 1;
            endif;
          }                                  
        endif;
        
          array_push($arr_gender_stat,$month_stat);
        
        }
        
        break;
      
      case 3: // number of NEW smear (+) cases cured: 1. tx completed, 2. sputum negative in the duration of tx and continuation phase. CURED status in db
          for($x=0;$x<count($arr_gender);$x++){
              $month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
              
              $q_smear_cured = mysql_query("SELECT a.patient_id,a.ntp_id,a.treatment_end_date,a.outcome_id FROM m_patient_ntp a,m_patient b WHERE a.patient_id=b.patient_id AND a.outcome_id='CURE' AND a.patient_type_id='NEW' AND a.treatment_end_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$arr_gender[$x]'") or die("Cannot query 305 ".mysql_error());

              if(mysql_num_rows($q_smear_cured)!=0):
                  while(list($pxid,$ntp_id,$tx_end,$outcome)=mysql_fetch_array($q_smear_cured)){
                      if($this->get_px_brgy($pxid,$brgy)):
                          $month_stat[$this->get_max_month($tx_end)] += 1;
                      endif;
                  
                  }              
              endif;
               
               array_push($arr_gender_stat,$month_stat);
          }
      
    
      case 4: //smear (+) re-treatment - relapsed, return after default, treatment failure. CAT 2 TB patients
          $arr_retreat = array('FAIL','OTH','RAD','REL');
          
          for($x=0;$x<count($arr_gender);$x++){
              $q_retreatment = mysql_query("SELECT a.patient_id,a.ntp_id,a.intensive_start_date,a.patient_type_id FROM m_patient_ntp a, m_patient b WHERE a.patient_id=b.patient_id AND a.patient_type_id IN ('FAIL','OTH','RAD','REL') AND a.intensive_start_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$arr_gender[$x]'") or die("Cannot query: 322 ".mysql_error());
          
              if(mysql_num_rows($q_retreatment)!=0):
                  while(list($pxid,$ntp_id,$intensive_start,$pxtype)=mysql_fetch_array($q_retreatment)){
                      if($this->get_px_brgy($pxid,$brgy)):
                          $month_stat[$this->get_max_month($intensive_start)] += 1;                      
                      endif;                  
                  }              
              endif;
              
              array_push($arr_gender_stat,$month_stat);
          }
          break;
      
      case 5:
      
          $arr_retreat = array('FAIL','OTH','RAD','REL');
          
          for($x<0;$x<count($arr_gender);$x++){
              $q_retreatment_cured = mysql_query("SELECT a.patient_id,a.ntp_id,a.intensive_start_date,a.treatment_end_date,a.patient_type_id,a.outcome_id FROM m_patient_ntp a, m_patient b WHERE a.patient_id=b.patient_id AND a.patient_type_id IN ('FAIL','OTH','RAD','REL') AND a.outcome_id='CURE' AND a.treatment_end_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.patient_gender='$arr_gender[$x]'") or die("Cannot query 342 ".mysql_error());
              
              if(mysql_num_rows($q_retreatment_cured)!=0):
                  while(list($pxid,$ntp_id,$intensive_start,$tx_end,$px_type,$outcome)=mysql_fetch_array($q_retreatment_cured)){
                      if($this->get_px_brgy($pxid,$brgy)):
                          $month_stat[$this->get_max_month($tx_end)] += 1;           
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
  
  
}


function get_quarterly_total($r_month){
        $q_total = array();
        $counter = 0;
                        
        for($i=1;$i<=4;$i++){                                                              
          $q_total[$i] = $r_month[$i+$counter] + $r_month[$i+$counter+1] + $r_month[$i+$counter+2];                                                                                                
          $counter+=2;
        }
       return $q_total;
}
                                                                                                                                                

function get_target($brgy_pop){
	return round($brgy_pop * 0.85 * 0.145); //FP Target = total population X 14.5 X 85%
}

function get_brgy_pop(){
        list($taon,$buwan,$araw) = explode('-',$_SESSION[edate2]);
        if(in_array('all',$_SESSION[brgy])):
                $q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon'") or die("Cannot query: 206");
        else:
                $str = implode(',',$_SESSION[brgy]);
                $q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon' AND barangay_id IN ($str)") or die("Cannot query: 209");        
        endif;  

        if(mysql_num_rows($q_brgy_pop)!=0):
                list($populasyon) = mysql_fetch_array($q_brgy_pop);
        endif;          
        
        return $populasyon;
}

function get_brgy(){  //returns the barangay is CSV format. to be used in WHERE clause for determining barangay residence of patient
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

function get_brgy_header(){
    $arr_brgy = array();
    $str_brgy = '';    

    if(in_array('all',$_SESSION[brgy])):
        /*$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay ORDER by barangay_id ASC") or die("Cannot query 252". mysql_error());        
        while(list($brgy_name) = mysql_fetch_array($q_brgy)){            
            array_push($arr_brgy,$brgy_id);
        }*/
        $str_brgy = 'All Barangay';
    else:
        $arr_brgy = $_SESSION[brgy];
		
	for($x=0;$x<count($arr_brgy);$x++){
	
        $q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id = '$arr_brgy[$x]' ORDER by barangay_id ASC") or die("Cannot query 252". mysql_error());        
        
	while(list($brgy) = mysql_fetch_array($q_brgy)){
		$str_brgy = $str_brgy.'  '.$brgy;
	}	        

	}                
    endif;                                                                         
                                                                          
    return $str_brgy;
}


function create_qt_gt($arr_in_months){  //this function receives an array representing totals in 12 months. transform the array by inserting
    				     // quarterly and grand totals    
    $arr_qt_gt = array();
    $gt = 0;
    for($i=1;$i<=count($arr_in_months);$i++){
        if(($i % 3)==0):
            array_push($arr_qt_gt,$arr_in_months[$i]);
            $qtrly_total = $arr_in_months[$i] + $arr_in_months[$i-1] + $arr_in_months[$i-2];
            array_push($arr_qt_gt,$qtrly_total);
        else:
            array_push($arr_qt_gt,$arr_in_months[$i]);
        endif;
        
        $gt = $gt + $arr_in_months[$i];
    }
    
    array_push($arr_qt_gt,$gt);
    
    return $arr_qt_gt;   
}

function return_blank($cells){
	$arr_blank = array();
	for($i=0;$i<$cells;$i++){
		array_push($arr_blank,'');
	}
	
	return $arr_blank;
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



function get_max_month($date){
        list($taon,$buwan,$araw) = explode('-',$date);
        $max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

        return $max_date;
}    



function Footer(){
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}



}

$pdf = new PDF('L','mm','Legal');


$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);

$pdf->AddPage();


//$pdf->AddPage();
$pdf->show_tb_summary();
$pdf->Output();

?>
