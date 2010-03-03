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
	
	if($_SESSION[brgy]=='all'):
		$brgy_label = 'All Barangays';

	else:
		$brgy_name = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$_SESSION[brgy]'") or die("Cannot query: 124");
		list($brgy_label) = mysql_fetch_array($brgy_name);
	endif;
	
	$this->SetFont('Arial','B',12);
	$this->Cell(0,5,'Target Client List for Prenatal Care ( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$brgy_label,0,1,'C');


	$this->SetFont('Arial','',10);
	if($_SESSION[pahina]==1):
	$w = array(30,30,55,85,10,30,25,25,25,25); //340
	$header = array('Registration Date','Family Serial No.','Name','Address','Age','LMP / GP','EDC','1st Trimester','2nd Trimester','3rd Trimester');
	
	elseif($_SESSION[pahina]==2):
	$this->SetFont('Arial','',8);
	$w = array(20,22,22,22,22,22,35,25,25,20,20,30,20,30);
	$header = array('Tetanus Status','TT1','TT2','TT3','TT4','TT5','Date and No. Iron With Folic Acid Was Given','Risk Code/Date Detected','Pregnancy Date Teminated','Outcome','Birth Weight (grams)','Place of Delivery','Attended By','Remarks');

	else:
	endif;

		$this->SetWidths($w);
		$this->Row($header);	
}

function show_records()
{	

	$r_prenatal = array_values(array_unique($_SESSION[prenatal_mc_px]));		
	
	$w = array(30,30,55,85,10,30,25,25,25,25);
	$r_rec = array();
	
	for($i=0;$i<count($r_prenatal);$i++){
	        
	        //echo $r_prenatal[$i].'<br>';
	        
		$q_px = mysql_query("SELECT patient_lastname,patient_firstname,TO_DAYS(patient_dob) as kaarawan FROM m_patient WHERE patient_id='$r_prenatal[$i]'") or die(mysql_error());

		$q_name = mysql_query("SELECT a.patient_id,a.patient_lastname,a.patient_firstname,TO_DAYS(a.patient_dob) as day_bday,b.family_id,c.address,d.barangay_name FROM m_patient a,m_family_members b,m_family_address c,m_lib_barangay d WHERE a.patient_id='$r_prenatal[$i]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id=d.barangay_id") or die("Cannot query: 137");
		$r_name = mysql_fetch_array($q_name);

		//$q_prenatal = mysql_query("SELECT a.patient_lmp,a.patient_edc,a.obscore_gp,b.prenatal_date,TO_DAYS(b.prenatal_date) as day_prenatal FROM m_patient_mc a, m_consult_mc_prenatal b WHERE a.patient_id='$r_prenatal[$i]' AND a.patient_id=b.patient_id AND b.visit_sequence='1' AND end_pregnancy_flag='N'") or die(mysql_error());
		$q_prenatal = mysql_query("SELECT a.patient_lmp,a.patient_edc,a.obscore_gp,b.prenatal_date,TO_DAYS(b.prenatal_date) as day_prenatal FROM m_patient_mc a, m_consult_mc_prenatal b WHERE a.patient_id='$r_prenatal[$i]' AND a.patient_id=b.patient_id AND end_pregnancy_flag='N' ORDER by b.prenatal_date ASC") or die(mysql_error());

		$result_prenatal = mysql_fetch_array($q_prenatal);				
	        
		if(empty($r_name[patient_lastname]) && empty($r_name[patient_firstname])):
			$r_px = mysql_fetch_array($q_px);
			$pangalan = $r_px[patient_lastname].', '.$r_px[patient_firstname];
			$edad_sa_prenatal = floor(($result_prenatal[day_prenatal] - $r_px[kaarawan])/365);		
			$tirahan = '';
		else:
			$pangalan = $r_name[patient_lastname].', '.$r_name[patient_firstname];
			$tirahan = $r_name[address]. ', '.$r_name[barangay_name];
			$edad_sa_prenatal = floor(($result_prenatal[day_prenatal] - $r_name[day_bday])/365);		

		endif;

		list($predate,$pretime) = explode(' ',$result_prenatal[prenatal_date]);
		list($prenataldate,$prenataltime) = explode(' ',$result_prenatal[prenatal_date]);
		
		
		
		$q_prenatal_visits = mysql_query("SELECT a.patient_id,a.prenatal_date,a.trimester FROM m_consult_mc_prenatal a,m_patient_mc b WHERE a.patient_id='$r_prenatal[$i]' AND a.patient_id=b.patient_id AND b.end_pregnancy_flag='N' ORDER by a.prenatal_date DESC") or die(mysql_error());
		
		$first='';
		$second='';
		$third='';

		while($r_prenatal_visits=mysql_fetch_array($q_prenatal_visits)){
			list($d,$t) = explode(' ',$r_prenatal_visits[prenatal_date]);			

			switch($r_prenatal_visits[trimester]){
				case 1:
					$first.= $d."\n";
					break;
				case 2:
					$second = $d."\n";
					break;
				case 3:
					$third = $d."\n";
					break;
				default:			
			}
		}

		array_push($r_rec,array("datereg"=>$prenataldate,"familyserial"=>$r_name[family_id],"ngalan"=>$pangalan,"bahay"=>$tirahan,"edad"=>$edad_sa_prenatal,"lmp_gp"=>$result_prenatal[patient_lmp].', '.$result_prenatal[obscore_gp],"edc"=>$result_prenatal[patient_edc],"first_tri"=>$first,"second_tri"=>$second,"third_tri"=>$third));			
	}
			
		$this->SetWidths($w);
		for($j=0;$j<count($r_rec);$j++){  //rows
			$this->Row(array($r_rec[$j]['datereg'],$r_rec[$j]['familyserial'],$r_rec[$j]['ngalan'],$r_rec[$j]['bahay'],$r_rec[$j]['edad'],$r_rec[$j]['lmp_gp'],$r_rec[$j]['edc'],$r_rec[$j]['first_tri'],$r_rec[$j]['second_tri'],$r_rec[$j]['third_tri']));
		}	
}


function show_second(){
	$r_prenatal = $_SESSION[prenatal_mc_px];
	$r_mc_id = $_SESSION[mc_id];

	$w = array(20,22,22,22,22,22,35,25,25,20,20,30,20,30);
	$r_rec = array();

	for($i=0;$i<count($r_prenatal);$i++)
	{
		$r_dates = array();
		$outcome_code = '';

		$q_vaccine = mysql_query("SELECT a.actual_vaccine_date, a.vaccine_id FROM m_consult_mc_vaccine a, m_lib_vaccine b WHERE a.patient_id='$r_prenatal[$i]' AND a.vaccine_id=b.vaccine_id ORDER by a.actual_vaccine_date DESC") or die("Cannot query: 210");
		
		if(mysql_num_rows($q_vaccine)!=0):
			while($r_vaccine=mysql_fetch_array($q_vaccine))
			{
				
				switch($r_vaccine[vaccine_id])
				{
					case 'TT1':
						$r_dates[0].=$r_vaccine[actual_vaccine_date]."\n";
						break;
					case 'TT2':
						$r_dates[1].=$r_vaccine[actual_vaccine_date]."\n";
						break;
					case 'TT3':
						$r_dates[2].=$r_vaccine[actual_vaccine_date]."\n";
						break;
					case 'TT4':
						$r_dates[3].=$r_vaccine[actual_vaccine_date]."\n";
						break;
					case 'TT5':
						$r_dates[4].= $r_vaccine[actual_vaccine_date]."\n";
						break;
					default:
						break;
				}
			}
			//print_r($r_dates);						
			$tt_stat = $this->get_tt_status($r_dates);
		else:
			$tt_stat = 'Unknown';

		endif;
		
		$q_postpartum = mysql_query("SELECT delivery_date,delivery_location,birthweight,outcome_id,birthmode,trimester3_date, TO_DAYS(delivery_date) as del_days FROM m_patient_mc WHERE mc_id='$r_mc_id[$i]' AND patient_id='$r_prenatal[$i]'") or die("cannot query: 245");
		
		list($del_date,$del_location,$bwt,$outcome,$bmode,$del_days,$tri3) = mysql_fetch_array($q_postpartum);
		
		$bwt_gm = empty($bwt)?'':($bwt * 1000);
		

		if($del_date=='0000-00-00'):
			$del_date = '';
		endif;

		$outcome_code = empty($outcome)?'':$this->get_outcome_code($outcome,$r_mc_id[$i]);
		$del_location = $this->get_delivery_location($del_location);
		$bmode = empty($bmode)?'':$this->get_attended($bmode);
		$ironman = $this->get_iron_intake($r_prenatal[$i],$r_mc_id[$i],$tri3);
		$riskcode = $this->get_risk_code($r_prenatal[$i],$r_mc_id[$i]);
		$remark = $this->get_prenatal_remarks($r_prenatal[$i],$r_mc_id[$i]);

	$this->SetWidths($w);
	$this->Row(array($tt_stat,$r_dates[0],$r_dates[1],$r_dates[2],$r_dates[3],$r_dates[4],$ironman,$riskcode,$del_date,$outcome_code,$bwt_gm,$del_location,$bmode,$remark));
	}
}


function get_tt_status($tt_dates){
	ksort($tt_dates);
	arsort($tt_dates);
	list($max,$value) = each($tt_dates);
	$max+=1;
	return 'TT'.$max;
}



function get_outcome_code($outcome,$mc_id){
	$get_lmp = mysql_query("SELECT TO_DAYS(patient_lmp) as pxlmp, TO_DAYS(delivery_date) as pxdel FROM m_patient_mc WHERE mc_id='$mc_id'") or die("cannot query: 270");
	
	list($lmp_days,$del_days) = mysql_fetch_array($get_lmp);
	$wks = floor(($del_days-$lmp_days)/7);

	if($outcome=='SB'):
		if($wks >= 20):
			$outcome_code = 'SB';
		else:
			$outcome_code = 'AB';
		endif;

	elseif($outcome=='FDU'):
		$outcode_code = 'Fetal Death';

	else:  
		$outcome_code = 'LB';
	endif;

	return $outcome_code;
}


function get_delivery_location($del_loc){
	if($del_loc=='HOME'):
		$lokasyon = 'Home';
	elseif($del_loc=='HOSP'):
		$lokasyon = 'Hospital';
		
	elseif($del_loc=='LYIN'):
		$lokasyon = 'Lying-in Clinic';
	else:
		$lokasyon = '';
	endif;
	return $lokasyon;
}


function get_attended($bmode){
	$q_bmode = mysql_query("SELECT attendant_name FROM m_lib_mc_birth_attendant WHERE attendant_id='$bmode'") or die(mysql_error());

	list($attendant) = mysql_fetch_array($q_bmode);
	switch($bmode){
		
		case 'MD':
			$code_bmode = 'A';
			break;
		case 'RN':
			$code_bmode = 'B';
			break;
		case 'MW':
			$code_bmode = 'C';
		case 'TRH':
			$code_bmode = 'D';
			break;
		case 'UTH':
			$code_bmode = 'D';
		default:
			$code_bmode = 'E';
			break;
	}
	return $code_bmode;
}	

function get_iron_intake($pxid,$mcid,$tri3){
	$q_iron = mysql_query("SELECT actual_service_date, service_qty FROM m_consult_mc_services WHERE patient_id='$pxid' AND mc_id='$mcid' AND service_id='IRON' AND actual_service_date <= '$tri3'  ORDER by actual_service_date DESC") or die(mysql_error());

	if(mysql_num_rows($q_iron)>0):
		while($r_iron = mysql_fetch_array($q_iron)){
			$iron.= $r_iron[actual_service_date].'('.$r_iron[service_qty].')'."\n";
		}
	else:
		$iron = '';
	endif;

	return $iron;

}

function get_risk_code($pxid,$mcid){

	$q_riskcode = mysql_query("SELECT visit_risk_id,date_detected FROM m_consult_mc_visit_risk WHERE patient_id='$pxid' AND mc_id='$mcid' ORDER by date_detected DESC") or die(mysql_error());

	if(mysql_num_rows($q_riskcode)>0):

		$q_px_bday = mysql_query("SELECT floor((TO_DAYS(a.patient_lmp) - TO_DAYS(b.patient_dob))/365) as px_bday FROM m_patient_mc a,m_patient b WHERE a.mc_id='$mcid' AND a.patient_id='$pxid' AND a.patient_id=b.patient_id") or die(mysql_error());
		
		list($px_age) = mysql_fetch_array($q_px_bday);
		
		if($px_age<18 || $px_age>35):
			$code.=$edad_issue = 'A'."\n";
		endif;
			

		while($r_riskcode = mysql_fetch_array($q_riskcode)){
;			
			list($r_date) = explode(' ',$r_riskcode[date_detected]);

			if($r_riskcode[visit_risk_id]=='2'):
				$itsrisky = 'B';

			elseif($r_riskcode[visit_risk_id]=='38'):
				$itsrisky = 'C';

			elseif($r_riskcode[visit_risk_id]=='3' || $r_riskcode[visit_risk_id]=='36'):

				$itsrisky = 'D';

			elseif($r_riskcode[visit_risk_id]=='24' || $r_riskcode[visit_risk_id]=='23' || $r_riskcode[visit_risk_id]=='26' || $r_riskcode[visit_risk_id]=='37' || $r_riskcode[visit_risk_id]=='28'):
				
				$itsrisky = 'E';

			else:

				$itsrisky = 'Others';
			endif;
			
			$code.= $itsrisky.'('.$r_date.')'."\n";
		}

	else:
		$code = '';
	endif;
	
	return $code;
}


function get_prenatal_remarks($pxid,$mcid){
	$get_remark = mysql_query("SELECT prenatal_remarks FROM m_patient_mc WHERE patient_id='$pxid' AND mc_id='$mcid'") or die("Cannot query: 412");
	
	list($remarks) = mysql_fetch_array($get_remark);
	return $remarks;
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



//print_r($_SESSION[prenatal_mc_px]);

$_SESSION[pahina] = $page==1?1:2;

$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();


$_SESSION[pahina]==1?$pdf->show_records():$pdf->show_second();

$pdf->Output();
//header("Location: ../index.php");
?>
