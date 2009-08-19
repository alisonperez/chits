<?php
session_start();
ob_start();
require('./fpdf/fpdf.php');


$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
mysql_select_db($_SESSION[dbname]);


class PDF extends FPDF{

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
	
	if($_SESSION[brgy]=='all'):
		$brgy_label = 'All Barangays';

	else:
		$brgy_name = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$_SESSION[brgy]'") or die("Cannot query: 124");
		list($brgy_label) = mysql_fetch_array($brgy_name);
	endif;
	
	$this->SetFont('Arial','B',12);
	$this->Cell(0,5,'Target Client List for Children Under One ( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$_SESSION[datanode][name],0,1,'C');


	$this->SetFont('Arial','',10);

	$this->Cell(0,5,$brgy_label,0,1,'C');	

	if($_SESSION[pahina]==1):
	$w = array(25,25,40,15,40,60,25,50,60); //340
	$header = array('Registration Date','Date of Birth','Name of Child','Sex','Complete Name of Mother','Complete Address','Date Referred for Newborn Screening','Child Protected At Birth (CPAB)'. "\n".'Date Assess / CPAB / TT Status / Date TT Given','Low Birth Weight Infant Given Iron Supplementation'."\n". 'Birth Weight / Date Started / Date Completed');

	
	elseif($_SESSION[pahina]==2):
	$this->SetFont('Arial','',10);
	$main_width = array(240,20,60,20);
	$main_content = array('Date Immunization Received','Date Fully Immunized','Child Was Exclusively Breastfed','Remarks');
	
	$this->SetWidths($main_width);
	$this->Row($main_content);

	$w = array(20,20,20,20,20,20,20,20,20,20,20,20,20,8,8,8,8,8,20,20);
	$header = array('BCG','DPT1','DPT2','DPT3','POLIO1','POLIO2','POLIO3','HEPA B1(<24hrs)','HEPA B1(>24hrs)','HEPA B2','HEPA B3','ANTI MEASLES','','1st MO','2nd MO','3rd MO','4th MO','5th MO','Date 6th MO','');

	else:
		
	endif;

		$this->SetWidths($w);
		$this->Row($header);
}


function show_ccdev1(){
	$r_ccdev = $_SESSION[ccdev_id];	
	$r_pxid = $_SESSION[ccdev_pxid];
	$r_ccdev_record = array();
	$w = array(25,25,40,15,40,60,25,50,60); //340

	for($i=0;$i<count($r_ccdev);$i++){
		$reg_date = $this->get_reg_date($r_ccdev[$i]);
		$px_info = $this->get_px_info($r_pxid[$i]);
		$date_nbs = $this->get_nbs_date($r_ccdev[$i]);
		$cpab_info = $this->get_cpab($r_pxid[$i]);
		$lbw_info = $this->get_lbw($r_ccdev[$i]);

		list($vacc_id,$active_status,$date_vacc) = explode('*',$cpab_info);
		
		$cpab = date('Y-m-d').' / '.(($active_status=='Active')?'Y':'N').' / '.$vacc_id.' / '.$date_vacc;
		
		array_push($r_ccdev_record,array("ccdev_reg"=>$reg_date,"ccdev_dob"=>$px_info[0],"ccdev_name"=>$px_info[1],"ccdev_sex"=>$px_info[2],"ccdev_address"=>$px_info[3],"ccdev_mother"=>$px_info[4],"nbs"=>$date_nbs,"cpab"=>$cpab,"lbw"=>$lbw_info));
	}
		
	for($j=0;$j<count($r_ccdev_record);$j++){		
		$this->Row(array($r_ccdev_record[$j]["ccdev_reg"],$r_ccdev_record[$j]["ccdev_dob"],$r_ccdev_record[$j]["ccdev_name"],$r_ccdev_record[$j]["ccdev_sex"],$r_ccdev_record[$j]["ccdev_mother"],$r_ccdev_record[$j]["ccdev_address"],$r_ccdev_record[$j]["nbs"],$r_ccdev_record[$j]["cpab"],$r_ccdev_record[$j]["lbw"]));
	}
}


function show_ccdev2(){
	$r_ccdev = $_SESSION[ccdev_id];
	$r_pxid = $_SESSION[ccdev_pxid];
	$w = array(20,20,20,20,20,20,20,20,20,20,20,20,20,8,8,8,8,8,20,20);	
	
	$antigens = array('BCG','DPT1','DPT2','DPT3','HEPB1-<','HEPB1->','HEPB2','HEPB3','MSL','OPV1','OPV2','OPV3');
	$antigen_date = array();
	$bfed_status = array();

	for($i=0;$i<count($r_pxid);$i++){
		
		for($j=0;$j<count($antigens);$j++){
			$antigen_date[$antigens[$j]] = $this->get_antigen_date($r_pxid[$i],$antigens[$j]);		
		}	
	
		$fic_date = $this->determine_vacc_status($r_pxid[$i]);
		$bfed_status = $this->get_bfeed_status($r_pxid[$i]);
		
		$bfed_sixth = ($bfed_status[6]=='0000-00-00')?'':$bfed_status[6];
		$ccdev_remark = $this->get_ccdev_remarks($r_pxid[$i]);
		

		$this->SetFont('Arial','',9);
		
		$this->Row(array($antigen_date["BCG"],$antigen_date["DPT1"],$antigen_date["DPT2"],$antigen_date["DPT3"],$antigen_date["OPV1"],$antigen_date["OPV2"],$antigen_date["OPV3"],$antigen_date["HEPB1-<"],$antigen_date["HEPB1->"],$antigen_date["HEPB2"],$antigen_date["HEPB3"],$antigen_date["MSL"],$fic_date,$bfed_status[0],$bfed_status[1],$bfed_status[2],$bfed_status[3],$bfed_status[4],$bfed_sixth,$ccdev_remark));		
	}	
}



function get_antigen_date(){
	if(func_num_args()>0):
		$arg_list = func_get_args();
		$pxid = $arg_list[0];
		$antigen = $arg_list[1];
	endif;

	if($antigen=='HEPB1-<' || $antigen=='HEPB1->' || $antigen=='HEPB1'):
		
		list($hep,$indicator) = explode('-',$antigen);
	
		if($indicator=='<'):
			$q_antigen = mysql_query("SELECT a.actual_vaccine_date FROM m_consult_vaccine a,m_patient b WHERE a.patient_id='$pxid' AND a.patient_id=b.patient_id AND (TO_DAYS(a.actual_vaccine_date) - TO_DAYS(b.patient_dob)) BETWEEN 0 AND 1 AND a.vaccine_id='HEPB1' ORDER by a.actual_vaccine_date ASC") or die("Cannot query: 211");
		else:
			$q_antigen = mysql_query("SELECT a.actual_vaccine_date FROM m_consult_vaccine a,m_patient b WHERE a.patient_id='$pxid' AND a.patient_id=b.patient_id AND (TO_DAYS(a.actual_vaccine_date) - TO_DAYS(b.patient_dob)) > 1 AND a.vaccine_id='HEPB1' ORDER by a.actual_vaccine_date ASC") or die("Cannot query: 211");
		endif;

	else:
		$q_antigen = mysql_query("SELECT actual_vaccine_date FROM m_consult_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigen' ORDER by actual_vaccine_date ASC") or die("Cannot query: 211");
	endif;

	if(mysql_num_rows($q_antigen)!=0):
		while(list($actual_vdate)=mysql_fetch_array($q_antigen)){
			$antigen_date.= $actual_vdate."\n";	
		}
	else:
		$antigen_date = '';
	endif;

	return $antigen_date;
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
								
			$q_antigen = mysql_query("SELECT round((TO_DAYS('$ant_date') - TO_DAYS(a.patient_dob))/7,2) week_span FROM m_patient a WHERE a.patient_id='$pxid'") or die("Cannot query: 269");
			list($wk_age) = mysql_fetch_array($q_antigen);

			if($wk_age>52):				
				$cic=1;
			endif;
		}
	endif;
	
	arsort($antigen_date);
//	print_r($antigen_date).'<br>';
	
	if($cic==1):
		return current($antigen_date)."\n".'(CIC)';
	else:
		return current($antigen_date)."\n".'(FIC)';
	endif;
}

function get_bfeed_status(){

	if(func_num_args()>0):
		$arg_list = func_get_args();
		$pxid = $arg_list[0];
	endif;
	$arr_bfed_status = array();

	$q_bfeed = mysql_query("SELECT bfed_month1,bfed_month2,bfed_month3,bfed_month4,bfed_month5,bfed_month6,bfed_month6_date FROM m_patient_ccdev WHERE patient_id='$pxid'") or die(mysql_error());
	
	$arr_bfed = mysql_fetch_row($q_bfeed);

	for($i=0;$i<(count($arr_bfed)-1);$i++){
		if($arr_bfed[$i]=='Y'):
			$arr_bfed_status[$i] = chr(47); //'&#10003';  //check mark
		else:
			$arr_bfed_status[$i] = '';
		endif;	
	}


	$arr_bfed_status[count($arr_bfed)-1] = $arr_bfed[count($arr_bfed)-1];
	//print_r($arr_bfed_status);
	return $arr_bfed_status;
}

function get_ccdev_remarks(){
	if(func_num_args()>0):
		$arg_list = func_get_args();
		$pxid = $arg_list[0];		
	endif;	

	$q_remark = mysql_query("SELECT ccdev_remarks FROM m_patient_ccdev WHERE patient_id='$pxid'") or die(mysql_error());
	list($remarks) = mysql_fetch_array($q_remark);
	return $remarks;
}

function get_reg_date($ccdev_id){
	$q_reg_date = mysql_query("SELECT date_registered FROM m_patient_ccdev WHERE ccdev_id='$ccdev_id'") or die("cannot query: 167");
	list($date_reg) = mysql_fetch_array($q_reg_date);
	return $date_reg;
}

function get_lbw($ccdev_id){
	$q_lbw = mysql_query("SELECT birth_weight,lbw_date_started,lbw_date_completed FROM m_patient_ccdev WHERE ccdev_id='$ccdev_id'") or die("cannot query: 189");

	if(mysql_num_rows($q_lbw)!=0):
		list($bwt,$lbw_sdate,$lbw_edate) = mysql_fetch_array($q_lbw);
		$sdate = ($lbw_sdate=='0000-00-00')?' - ':$lbw_sdate;
		$edate = ($lbw_edate=='0000-00-00')?' - ':$lbw_edate;

		if($bwt<0.25):
			$lbw_status = ($bwt*1000).'gms / '.$sdate.' / '.$edate;
		else:
			$lbw_status = ($bwt*1000).'gms (Normal birth weight)';
		endif;
	endif;
	
	return $lbw_status;
}


function get_nbs_date($ccdev_id){
	//select the first date wherein NBS was conducted. it is possible that there are many dates

	$q_nbs = mysql_query("SELECT MIN(ccdev_service_date) FROM m_consult_ccdev_services WHERE ccdev_id='$ccdev_id' AND service_id='NBS'") or die("cannot query: 183");
	
	list($nbs_date) = mysql_fetch_array($q_nbs);
	
	return $nbs_date;
}

function get_px_info($pxid){
	$r_px_info = array();

	$q_px = mysql_query("SELECT patient_lastname,patient_firstname,patient_dob,patient_gender,patient_mother FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query: 182");

	list($pxlname,$pxfname,$pxdob,$pxgender,$pxmother) = mysql_fetch_array($q_px);

	$q_brgy = mysql_query("SELECT a.address,c.barangay_name FROM m_family_address a, m_family_members b,m_lib_barangay c WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id=c.barangay_id") or die("cannot query: 186");

	if(mysql_num_rows($q_brgy)!=0):
		list($address,$brgy_name) = mysql_fetch_array($q_brgy);
	else:
		$brgy_name = "";
	endif;

	array_push($r_px_info,$pxdob,$pxlname.', '.$pxfname,$pxgender,$address.', '.$brgy_name,$pxmother);
		
	return $r_px_info;
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

			$q_diff = mysql_query("SELECT consult_id FROM m_consult_mc_vaccine WHERE patient_id='$pxid' AND vaccine_id='$antigen' AND (TO_DAYS('$pxedc')-TO_DAYS(actual_vaccine_date)) <= '$tt_duration[$highest_tt]'") or die("Cannot query: 2399");
			
			if(mysql_num_rows($q_diff)!=0):
					$protected = 1;
			endif;
		endif;
		
		$tt_stat = 'TT'.$highest_tt.'*';
		$tt_stat.=($protected==1)?'Active':'Not Active';
		$tt_stat.= '*'.$date_tt;

		return $tt_stat;		
}


function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}

}


$_SESSION[pahina] = $page==1?1:2;

$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$_SESSION[pahina]==1?$pdf->show_ccdev1():$pdf->show_ccdev2();

$pdf->Output();

?>