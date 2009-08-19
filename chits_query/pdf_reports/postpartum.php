<?php

	/*	$this->app = 'CHITS Report Query Tool';
	$this->app_module = 'Postpartum TCL';
	$this->version = '1.0';
	$this->app_date = '2008-09-18';
	$this->author = 'alison perez <perez.alison@gmail.com>';
	*/

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
	$this->Cell(0,5,'Target Client List for Postpartum Care ( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$brgy_label,0,1,'C');


	$this->SetFont('Arial','',10);
//	$w = array(30,25,17,30,35,65,25,25,27,25,38); //340
	$w = array(25,25,17,15,35,55,25,25,25,27,25,38); //340
	$header = array('Registration Date','Delivery Date','Outcome','Family Serial No.','Name','Address','Date of Visit Within 24 hours','Date of Visit'. "\n". 'One Week After','Date Initiated Breastfedding','Iron (Quantity)','Vitamin A','Remarks');
	

	$this->SetWidths($w);
	$this->Row($header);	
}

function show_postpartum(){

	$r_px = $_SESSION[r_post_px];
	$r_mc = $_SESSION[r_post_mc];
	
	$w = array(25,25,17,15,35,55,25,25,25,27,25,38);

	for($i=0;$i<count($r_px);$i++){
		$q_px = mysql_query("SELECT a.patient_lastname,a.patient_firstname,b.delivery_date,b.outcome_id,b.postpartum_remarks FROM m_patient a, m_patient_mc b WHERE a.patient_id='$r_px[$i]' AND a.patient_id=b.patient_id AND b.mc_id='$r_mc[$i]'") or die(mysql_error());
		
		list($lname,$fname,$deldate,$outcome,$post_remark) = mysql_fetch_array($q_px);
			

		$q_fam = mysql_query("SELECT a.family_id, b.address, c.barangay_name FROM m_family_members a,m_family_address b, m_lib_barangay c WHERE a.patient_id='$r_px[$i]' AND a.family_id=b.family_id AND b.barangay_id=c.barangay_id") or die("Cannot query: 153");
		list($fam_id,$address,$brgy) = mysql_fetch_array($q_fam);
				

		$q_post = mysql_query("SELECT postpartum_date,visit_sequence FROM m_consult_mc_postpartum WHERE mc_id='$r_mc[$i]' AND patient_id='$r_px[$i]' ORDER by postpartum_date ASC") or die("Cannot query: 149");
		


		while(list($postpartum_date,$visit_seq)=mysql_fetch_array($q_post)){
			list($d,$t) = explode(' ',$postpartum_date);

			if($visit_seq==1): 
				list($reg_date,$reg_time) = explode(' ',$postpartum_date); 
			endif;

			$visit_wk = $this->get_days($d,$deldate,'w');
			
		}
		
		$visit_24 = $this->get_days($reg_date,$deldate,'d');	

		$outcome = empty($outcome)?'':$this->get_outcome_code($outcome,$r_mc[$i]);
		$iron = $this->get_iron_intake($r_px[$i],$r_mc[$i],$deldate);
		$vit = $this->get_vitamin($r_px[$i],$r_mc[$i],$deldate);
		$date_init_breastfeed = $this->get_breastfeed($r_px[$i],$r_mc[$i]);

		$this->SetWidths($w);
		$this->Row(array($reg_date,$deldate,$outcome,$fam_id,$lname.', '.$fname,$address.', '.$brgy,$visit_24,$visit_wk,$date_init_breastfeed,$iron,$vit,$post_remark));

	}
}

function get_breastfeed($pxid,$mcid){
	$q_bfeed = mysql_query("SELECT breastfeeding_asap,date_breastfed FROM m_patient_mc WHERE mc_id='$mcid' AND patient_id='$pxid'") or die("Cannot query: 189");
	list($bfeed,$date_bfeed) = mysql_fetch_array($q_bfeed);

	$bfeed = ($bfeed=='N')?'':$date_bfeed;
	return $bfeed;

}

function get_days($regdate,$deldate,$code){
   //explode the date by "-" and storing to array
   $d1=explode("-", $deldate);
   $d2=explode("-", $regdate);

   //gregoriantojd() Converts a Gregorian date to Julian Day Count (400BC to 9999AD kaya convert)

   $start_date=gregoriantojd($d1[1], $d1[2], $d1[0]);   
   $end_date=gregoriantojd($d2[1], $d2[2], $d2[0]);
   $diff = $end_date - $start_date;

   if($diff <= 1 && $code=='d'):
		return $regdate;
   elseif($diff>=4 && $diff<=10 && $code=='w'):
		return $regdate;
   else:	
	   return;
   endif;
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


function get_iron_intake($pxid,$mcid,$deldate){
	$q_iron = mysql_query("SELECT actual_service_date, service_qty FROM m_consult_mc_services WHERE patient_id='$pxid' AND mc_id='$mcid' AND actual_service_date >= '$deldate' AND service_id='IRON' ORDER by actual_service_date DESC") or die(mysql_error());

	if(mysql_num_rows($q_iron)>0):
		while($r_iron = mysql_fetch_array($q_iron)){
			$iron.= $r_iron[actual_service_date].'('.$r_iron[service_qty].')'."\n";
		}
	else:
		$iron = '';
	endif;

	return $iron;

}

function get_vitamin($pxid,$mcid,$deldate){
	$q_vit = mysql_query("SELECT actual_service_date, service_qty FROM m_consult_mc_services WHERE patient_id='$pxid' AND mc_id='$mcid' AND actual_service_date >= '$deldate' AND service_id='VITA' ORDER by actual_service_date DESC") or die("Cannot query: 221");
	if(mysql_num_rows($q_vit)>0):
		while($r_vita = mysql_fetch_array($q_vit)){
			$vita .= $r_vita[actual_service_date]."\n";
		}
	endif;

	return $vita;

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

$pdf->show_postpartum();

$pdf->Output();

?>