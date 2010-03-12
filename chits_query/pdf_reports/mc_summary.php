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
//	print_r($_SESSION[months][1]);
	


	$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];
	$municipality_label = $_SESSION[datanode][name];
        
	$this->SetFont('Arial','B',12);        

        if($_SESSION[ques]==36): //maternal care summary table                
	$this->Cell(0,5,'Maternal Care Summary Table ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
	
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
	
	elseif($_SESSION[ques]==80 || $_SESSION[ques]==81): //maternal care monthly and quarterly report respectively
	    $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("CAnnot query: 164");
	    
	    if(mysql_num_rows($q_pop)!=0):
                list($population) = mysql_fetch_row($q_pop);
            else:
	        $population = 0;
	    endif;
	    
            if($_SESSION[ques]==80):
                $this->Cell(0,5,'FHSIS REPORT FOR THE MONTH: '.date('F',mktime(0,0,0,$_SESSION[smonth],1,0)).'          YEAR: '.$_SESSION[year],0,1,L);
                $this->Cell(0,5,'NAME OF BHS: '.$this->get_brgy(),0,1,L); 
                $w = array(200,40);                
                $header = array('MATERNAL CARE', 'No.');
                
            elseif($_SESSION[ques]==81):
                $w = array(161,30,25,25,50,45);
                $header = array('Indicators', 'Eligible Population','No.','% / Rate','Interpretation','Recommendation/Action Taken');            
                $this->Cell(0,5,'FHSIS REPORT FOR THE QUARTER: '.$_SESSION[quarter].'          YEAR: '.$_SESSION[year],0,1,L);            
            else:
            
            endif;
            
            $this->Cell(0,5,'MUNICIPALITY/CITY OF: '.$_SESSION[lgu],0,1,L);
            $this->Cell(0,5,'PROVINCE: '.$_SESSION[province].'          PROJECTED POPULATION OF THE YEAR: '.$population,0,1,L);
            
            $this->SetFont('Arial','B','13');
        
        else:
             
	endif;
	
	$this->Ln();
	$this->SetWidths($w);
	$this->Row($header);	
}

function show_mc_summary(){
	


	$criteria = array('Pregnant Women with 4 or more prenatal visits','Pregnant Women given 2 doses of TT','Pregnant Women given TT2 plus','Pregnant given complete iron with folic acid','Postpartum women with at least 2 PPV','Postpartum women given complete iron','Postpartum women given Vit. A','Postpartum women initiated breastfeeding');			
    
	for($i=0;$i<count($criteria);$i++){
	
		$array_target = array();
		$q_array = array();
		$gt = 0;

		$mstat = $this->compute_indicator($i+1);
		$brgy_pop = $this->get_brgy_pop();
		

		$target_perc = $this->get_target($i+1);

		$target = round(($brgy_pop * $target_perc));

		for($j=1;$j<=count($mstat);$j++){
			if($target==0):
				$array_target[$j] = 0;
			else:
				$array_target[$j] = round($mstat[$j]/$target,3)*100;
			endif;
		}

		$q_array = $this->get_quarterly_total($mstat,$target);
		$gt = array_sum($mstat);
                
                if($_SESSION[ques]==36):
                    $w = array(30,18,18,18,18,15,18,18,18,15,18,18,18,15,18,18,18,15,18); //340
                    $this->SetWidths($w);                		
                    $this->Row(array($criteria[$i],$target,$mstat[1],$mstat[2],$mstat[3],$q_array[1],$mstat[4],$mstat[5],$mstat[6],$q_array[2],$mstat[7],$mstat[8],$mstat[9],$q_array[3],$mstat[10],$mstat[11],$mstat[12],$q_array[4],$gt));
                elseif($_SESSION[ques]==80):                    
                    $w = array(200,40); //340 //monthly report                   
                    $this->SetWidths($w);
                    $arr_disp = array();
                    $this->SetFont('Arial','',13);
                    array_push($arr_disp,$criteria[$i],$mstat[$_SESSION[smonth]]);                    
                    
                    for($x=0;$x<count($arr_disp);$x++){
                        if($x==0):
                            $this->Cell($w[$x],6,($i+1).'. '.$arr_disp[$x],'1',0,'1');
                        else:
                            $this->Cell($w[$x],6,$arr_disp[$x],'1',0,'1');
                        endif;
                    }
                    
                    $this->Ln();
                                        
                elseif($_SESSION[ques]==81): //quarterly report
                    $w = array(161,30,25,25,50,45);
                    $this->SetWidths($w);
                    $arr_disp = array();
                    $this->SetFont('Arial','',13);                                                            
                    
                    array_push($arr_disp,$criteria[$i],$target,$q_array[$_SESSION[quarter]],$this->compute_mc_rate($target,$q_array[$_SESSION[quarter]]).'%',' ',' ');
                    
                    for($x=0;$x<count($arr_disp);$x++){
                        if($x==0):
                            $this->Cell($w[$x],6,($i+1).'. '.$arr_disp[$x],'1',0,'1');
                        else:
                            $this->Cell($w[$x],6,$arr_disp[$x],'1',0,'1');
                        endif;
                    }
                    
                    $this->Ln();
                else:
                
                endif;
                		
		//$this->Row(array($criteria[$i],$target,$array_target[1],$array_target[2],$array_target[3],$q_array[1],$array_target[4],$array_target[5],$array_target[6],$q_array[2],$array_target[7],$array_target[8],$array_target[9],$q_array[3],$array_target[10],$array_target[11],$array_target[12],$q_array[4],$gt));

	}
 }

function compute_indicator($crit){
	$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
	list($syr,$smonth,$sdate) = explode('-',$_SESSION[sdate2]);
	list($eyr,$emonth,$edate) = explode('-',$_SESSION[edate2]);
	$brgy_array = $this->get_brgy_array();
	$brgy_array = implode(',',$brgy_array);

	//print_r($brgy_array);

		switch($crit){

		case 1: //pregnant with 4 or more prenatal visits
				
//				if(in_array('all',$_SESSION[brgy])):
					$get_visits = mysql_query("SELECT distinct mc_id,patient_id,MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE visit_sequence >=  4 AND trimester=3 AND prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' GROUP by mc_id") or die("Cannot query: 186");								
//				else:
//					$get_visits = mysql_query("SELECT distinct a.mc_id,a.patient_id,MIN(a.prenatal_date) FROM m_consult_mc_prenatal a ,m_family_members b, m_family_address c WHERE a.visit_sequence >=  4 AND a.trimester=3 AND a.prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) GROUP by a.mc_id") or die(mysql_error());				
//				endif;
			
			if(mysql_num_rows($get_visits)!=0):
		
			while(list($mcid,$pxid,$predate)=mysql_fetch_array($get_visits)){
				$banat = 0;
				if(in_array('all',$_SESSION[brgy])):
					$banat = 1;
				else:
				
				$str = implode(',',$_SESSION[brgy]);
				$get_brgy = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id IN ($str)") or die(mysql_error());
															
					if(mysql_num_rows($get_brgy)!=0):
						$banat = 1;
					else:
						$banat = 0;
					endif;
				endif;

				if($banat==1): //$banat variable, if set to 1 mean the patient is in the barangay

				for($j=1;$j<=3;$j++){   //traverse for checking the trimester format 1-1-2
					$get_tri = mysql_query("SELECT consult_id, prenatal_date FROM m_consult_mc_prenatal WHERE trimester='$j' AND mc_id='$mcid' ORDER by prenatal_date DESC") or die("Cannot query: 186");
										
					$num = mysql_num_rows($get_tri);

					if($num!=0):
					   if($j==3):

						$q_min_date = mysql_query("SELECT MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE mc_id='$mcid' AND trimester='$j' AND prenatal_date!=(SELECT MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE mc_id='$mcid' AND trimester='$j')") or die("cannot query: 204");
						
						  if(mysql_num_rows($q_min_date)!=0):

							list($sec_date) = mysql_fetch_array($q_min_date);						
							list($latestdate) = explode(' ',$sec_date);
							list($latesty,$latestm,$latestd) = explode('-',$latestdate);
							$yr = date('Y');
							$max_date = date("n",mktime(0,0,0,$latestm,$latestd,$yr)); //get the unix timestamp then return month without trailing 0
							$arr[$j] = ($num>=2)?1:0; //check if the third trimester has at least 2 visits

						  endif;						
					   else:
						  $arr[$j] = 1; //marked trimester 1 and 2 with 1's if $num!=0
					   endif;
					endif;
					
				} //exit 1-1-4 format checking
								
				
				if($arr[1]==1 && $arr[2]==1 && $arr[3]==1):	
					$month_stat[$max_date]+=1;
				endif;				

				endif;
			} //end while
			
			
			endif; //end 

			break;
			
		case 2: //pregnant women given 2 doses of TT or TT2 plus protected women
			if(in_array('all',$_SESSION[brgy])):
				$q_px_tt = mysql_query("SELECT patient_id,actual_vaccine_date FROM m_consult_mc_vaccine WHERE vaccine_id='TT1'") or die(mysql_error());
			else:
				$q_px_tt = mysql_query("SELECT a.patient_id,a.actual_vaccine_date FROM m_consult_mc_vaccine a,m_family_members b,m_family_address c WHERE a.vaccine_id='TT1' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die(mysql_error());
			endif;
			

			while(list($pxid,$vacc_date)=mysql_fetch_array($q_px_tt)){			
				$q_t2 = mysql_query("SELECT a.patient_id,a.actual_vaccine_date FROM m_consult_mc_vaccine a,m_consult_mc_prenatal b WHERE a.vaccine_id='TT2' AND a.patient_id='$pxid' AND a.patient_id=b.patient_id AND b.prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.visit_sequence='1'") or die(mysql_error());

				if(mysql_num_rows($q_t2)!=0):
					list($pxid,$vacc_date)=mysql_fetch_array($q_t2);
					$month_stat[$this->get_max_month($vacc_date)]+=1;
				endif;
			}

			
			break;
		
		case 3: //pregnant women who are protected with TT2 plus protection
			$arr_tt = array(1=>0,2=>0,3=>0,4=>0,5=>0);
			
			$vacc = array('TT1','TT2','TT3','TT4','TT5');

			$tt_duration = array(1=>0,2=>1095,3=>1825,4=>3650,5=>10000); //number of days of effectiveness
			$highest_tt = 0;
			$protected = 0;
			
			if(in_array('all',$_SESSION[brgy])):
				$get_px_tt = mysql_query("SELECT distinct patient_id, max(vaccine_id), actual_vaccine_date FROM m_consult_mc_vaccine WHERE vaccine_id IN ('TT1','TT2','TT3','TT4','TT5') GROUP by patient_id") or die(mysql_error());

			else:
				$get_px_tt = mysql_query("SELECT distinct a.patient_id, max(a.vaccine_id), a.actual_vaccine_date FROM m_consult_mc_vaccine a, m_family_members b, m_family_address c WHERE a.vaccine_id IN ('TT1','TT2','TT3','TT4','TT5') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) GROUP by a.patient_id") or die(mysql_error());
			endif;
			
			while(list($pxid,$vacc_id,$vacc_date)=mysql_fetch_array($get_px_tt)){
				//check if the patient is in the active maternal cases for the time span
				//echo $pxid.'/'.$vacc_id.'/'.$vacc_date.'<br>';
				
				if($vacc_id!='TT1'):
				
				list($ttbuffer,$tt_num) = explode('TT',$vacc_id);
			
				$q_check_mc = mysql_query("SELECT a.mc_id,b.prenatal_date FROM m_patient_mc a,m_consult_mc_prenatal b WHERE a.patient_id='$pxid' AND a.mc_id=b.mc_id AND b.visit_sequence=1 AND b.prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND (TO_DAYS(a.patient_edc)-TO_DAYS('$vacc_date'))<='$tt_duration[$tt_num]'") or die("Cannot query : 297"); //killer SQL code 

				if(mysql_num_rows($q_check_mc)!=0):
					list($mcid,$vdate) = mysql_fetch_array($q_check_mc);
					$month_stat[$this->get_max_month($vdate)]+=1;
				endif;

				endif;				
			}



			break;

		case 4:	//pregnant women who have taken 180 tablets of iron with folic acid throughout the prenancy duration
			
			if(in_array('all',$_SESSION[brgy])):
				$get_iron_mc = mysql_query("SELECT distinct mc_id,patient_id FROM m_consult_mc_services WHERE service_id='IRON' ORDER by mc_id ASC, actual_service_date ASC") or die("cannot query: 346");
			else:
				$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b, m_family_address c WHERE a.service_id='IRON' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.mc_id ASC, a.actual_service_date ASC") or die(mysql_error());				
			endif;

			if(mysql_num_rows($get_iron_mc)!=0):				
				while(list($mcid,$pxid)=mysql_fetch_array($get_iron_mc)){
					$iron_total = 0;
					$target_reach = 0; //reset the flag target reach for every mc_id

					$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='IRON' AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.actual_service_date<=b.patient_edc ORDER by a.actual_service_date ASC") or die("Cannot query; 277");


					while(list($qty,$serv_date)=mysql_fetch_array($q_mc)){
						//echo $mcid.'/'.$qty.'/'.$serv_date.'<br>';
						$iron_total+=$qty;
						if($iron_total >= 180 && $target_reach==0):							
							$target_reach = 1;
							list($taon,$buwan,$araw) = explode('-',$serv_date);
							$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0
							$month_stat[$max_date]+=1;
							//echo $max_date.'<br>'.$mcid;
						endif;
					}
				}
			endif;

			break;
		
		case 5:
			
			if(in_array('all',$_SESSION[brgy])):
				$q_post = mysql_query("SELECT a.mc_id,a.postpartum_date,b.delivery_date,a.patient_id FROM m_consult_mc_postpartum a, m_patient_mc b WHERE a.mc_id=b.mc_id AND (TO_DAYS(a.postpartum_date)-TO_DAYS(b.delivery_date))<=1") or die("Cannot query: 297"); // get mc_id of patients who visited 24 hours after giving birth			
			else:
				$q_post = mysql_query("SELECT a.mc_id,a.postpartum_date,b.delivery_date,a.patient_id FROM m_consult_mc_postpartum a, m_patient_mc b,m_family_members c, m_family_address d WHERE a.mc_id=b.mc_id AND a.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id IN ($brgy_array) AND (TO_DAYS(a.postpartum_date)-TO_DAYS(b.delivery_date))<=1") or die("Cannot query: 380"); 
			endif;



			
			while(list($mcid,$post_date,$del_date,$pxid)=mysql_fetch_array($q_post)){ //check if the mcid(24-hrs) has 1-week (+3/-3) visit
			   $q_wk = mysql_query("SELECT a.postpartum_date FROM m_consult_mc_postpartum a, m_patient_mc b WHERE a.mc_id='$mcid' AND (TO_DAYS(a.postpartum_date)-TO_DAYS(b.delivery_date)) BETWEEN 4 AND 10 AND a.postpartum_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.postpartum_date ASC") or die(mysql_error());
			   
				if(mysql_num_rows($q_wk)!=0):					
					list($postdate) = mysql_fetch_array($q_wk);
					$month_stat[$this->get_max_month($postdate)]+=1;
				else:

				endif;
			
			}

			break; 

		case 6: //postpartum mothers wih complete iron w/ folic acid intake
			if(in_array('all',$_SESSION[brgy])):
				$get_iron_mc = mysql_query("SELECT distinct mc_id,patient_id FROM m_consult_mc_services WHERE service_id='IRON' ORDER by mc_id ASC, actual_service_date ASC") or die("Cannot query: 316");
			else:
				$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c WHERE a.service_id='IRON' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 316");
			endif;


			if(mysql_num_rows($get_iron_mc)):
				while(list($mcid,$pxid)=mysql_fetch_array($get_iron_mc)){

					$iron_total = 0;
					$target_reach = 0;

					$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='IRON' AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.actual_service_date BETWEEN b.delivery_date AND b.postpartum_date ORDER by a.actual_service_date ASC") or die("Cannot query; 277");
					
					
					while(list($qty,$serv_date)=mysql_fetch_array($q_mc)){
						//echo $mcid.'/'.$qty.'/'.$serv_date.'<br>';
						$iron_total+=$qty;
						if($iron_total >= 90 && $target_reach==0):							
							$target_reach = 1;
							$month_stat[$this->get_max_month($serv_date)]+=1;
							//echo $max_date.'<br>'.$mcid;
						endif;
					}				
				}
			endif;

			break;

		case 7: // postpartum women given vitamin A supplementation
			if(in_array('all',$_SESSION[brgy])):
				$get_vita = mysql_query("SELECT distinct mc_id,patient_id FROM m_consult_mc_services WHERE service_id='VITA' ORDER by mc_id ASC, actual_service_date ASC") or die("Cannot query: 358");
			else:
				$get_vita = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c WHERE a.service_id='VITA' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 358");	
			endif;

			if(mysql_num_rows($get_vita)!=0):
				while(list($mcid,$pxid)=mysql_fetch_array($get_vita)){
					$vit_total = 0;
					$target_reach = 0;
						$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='VITA' AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.actual_service_date BETWEEN b.delivery_date AND b.postpartum_date ORDER by a.actual_service_date ASC") or die("Cannot query; 277");

					while(list($qty,$serv_date)=mysql_fetch_array($q_mc)){						
						$vita_total+=$qty;

						if($vita_total >= 200000 && $target_reach==0):							
							$target_reach = 1;
							$month_stat[$this->get_max_month($serv_date)]+=1;
							//echo $max_date.'<br>'.$mcid;
						endif;
					}
				}
			endif;

			break;

		case 8: //postpartum women initiated breadstfeeding after giving birth
			if(in_array('all',$_SESSION[brgy])):
				$get_post_bfeed = mysql_query("SELECT mc_id, delivery_date, patient_id FROM m_patient_mc WHERE breastfeeding_asap='Y' AND delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by delivery_date") or die("cannot query: 350");
			else:
				$get_post_bfeed = mysql_query("SELECT a.mc_id, a.delivery_date, a.patient_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.breastfeeding_asap='Y' AND  a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.delivery_date") or die(mysql_error());			
			endif;

			if(mysql_num_rows($get_post_bfeed)!=0):
				while(list($mcid,$deldate)=mysql_fetch_array($get_post_bfeed)){
					$month_stat[$this->get_max_month($deldate)]+=1;
				}

			endif;

			break;
		default:
			//echo 'hohohoh';		
			break;

		} // end <switch>


//	} //end <for> months

	return $month_stat; //throw this consolidated array of months
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

function get_max_month($date){
	list($taon,$buwan,$araw) = explode('-',$date);
	$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

	return $max_date;
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

function get_target($criteria){
	if($criteria>=1 && $criteria<=4):
		$perc = '.035';
	else:
		$perc = '.03';
	endif;
	return $perc;
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


function get_brgy(){
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
                                                                                                                                                                                                        
                                                                                                                                                                                                    

function compute_mc_rate($target,$actual){
        if($target==0):
            return 0;
        else:
            return round((($actual/$target)*100),0);
        endif;
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

$pdf->show_mc_summary();

$pdf->Output();

?>
