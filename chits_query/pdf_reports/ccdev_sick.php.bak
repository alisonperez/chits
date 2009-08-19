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
	$this->Cell(0,5,'Target Client List for Sick Children ( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$_SESSION[datanode][name],0,1,'C');


	$this->SetFont('Arial','',10);

	$this->Cell(0,5,$brgy_label,0,1,'C');	

	if($_SESSION[pahina]==1):
	$main_width = array(35,40,50,25,15,65,110); //340
	$main_content = array('Registration Date','Family Serial Number','Name of Child','Date of Birth','Sex','Complete Address','Vitamin A Supplementation');
	
	$header_size = array(35,40,50,25,15,65,15,15,15,40,25);
	$header = array("","","","","","",'6-11 MOS','12-59 MOS','60-71 MOS','Diagnosis/Findings','Date Given');
	

	elseif($_SESSION[pahina]==2):
		$this->SetFont('Arial','',10);
		$main_width = array(100,100,70,70);
		$main_content = array('Anemic Children Given Iron Supplementation','Diarrhea Cases','Pneumonia Cases Seen','Remarks');
		
	$header_size= array(30,35,35,25,25,25,25,30,40,70);
	$header = array('Age in Months','Date Started','Date Completed','Age in Months','ORT','ORS','ORS W/ ZINC','Age in Months','Date Given Treatment','');

	else:
		
	endif;

	$this->SetWidths($main_width);
	$this->Row($main_content);

	$this->SetWidths($header_size);
	$this->Row($header);
}


function show_ccdev_sick1(){
	$w = array(35,40,50,25,15,65,15,15,15,40,25); //340
	$r_consultid = $_SESSION["ccdev_consultid"];

	for($i=0;$i<count($r_consultid);$i++){
		$px_info = $this->get_px_info($r_consultid[$i]);
		$vita = $this->get_vita_disease($r_consultid[$i]);		
		
		$content = array($px_info["date_reg"],$px_info["family_id"],$px_info["pxname"],$px_info["dob"],$px_info["gender"],$px_info["address"],$vita[0],$vita[1],$vita[2],$vita[3],$vita[4]);

		$this->SetWidths($w);
		$this->Row($content);
	}
}


function show_ccdev_sick2(){
	$r_consultid = $_SESSION["ccdev_consultid"];	
	
	$w = array(30,35,35,25,25,25,25,30,40,70);

	for($i=0;$i<count($r_consultid);$i++){
		$r_anemia = $this->get_anemia_tx($r_consultid[$i]);
		$r_diarrhea = $this->get_diarrhea_tx($r_consultid[$i]);
		$r_pneumonia = $this->get_pneumonia_tx($r_consultid[$i]);
		$remark = $this->get_tx_remark($r_consultid[$i]);

		$content = array($r_anemia["age_months"],$r_anemia["anemia_sdate"],$r_anemia["anemia_edate"],$r_diarrhea["age_month"],$r_diarrhea["ort"],$r_diarrhea["ors"],$r_diarrhea["orswz"],$r_pneumonia["age_month"],$r_pneumonia["pneu_date"],$remark);	
		$this->SetWidths($w);
		$this->Row($content);
	}

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


function get_px_info($consult_id){
	$r_pxinfo = array();
	$q_pxinfo = mysql_query("SELECT date_format(a.consult_date,'%Y-%m-%d') cons_date,b.patient_lastname,b.patient_firstname,b.patient_dob,b.patient_gender,c.family_id,d.address,e.barangay_name FROM m_consult a,m_patient b,m_family_members c,m_family_address d,m_lib_barangay e WHERE a.consult_id='$consult_id' AND a.patient_id=b.patient_id AND b.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id=e.barangay_id") or die("Cannot query: 188");

	if(mysql_num_rows($q_pxinfo)>0):
		list($consult_date,$px_lname,$px_fname,$px_dob,$px_gender,$family_id,$address,$brgy) = mysql_fetch_array($q_pxinfo);
		$r_pxinfo = array("date_reg"=>$consult_date,"family_id"=>$family_id,"pxname"=>$px_lname.', '.$px_fname,"dob"=>$px_dob,"gender"=>$px_gender,"address"=>$address.', '.$brgy);
	endif;

	return $r_pxinfo;
}


function get_vita_disease($consult_id){

	$array_sakit = array('measles','severe pneumonia','persistent diarrhea','malnutrition','xerophthalmia','night blindness','bitot','corneal xerosis','corneal ulcerations','keratomalacia');
	$sakit_count = array();

	for($i=0;$i<count($array_sakit);$i++){
		$str_sakit = "SELECT a.notes_id,c.vita_date,round((TO_DAYS(c.vita_date) - TO_DAYS(d.patient_dob))/30,2) age_months,d.patient_dob FROM m_consult_notes_dxclass a,m_lib_notes_dxclass b,m_consult_notes c,m_patient d WHERE a.consult_id='$consult_id' AND a.consult_id=c.consult_id AND c.patient_id=d.patient_id AND a.class_id=b.class_id";


		$arr_sakit = explode(" ",$array_sakit[$i]);
		
		for($j=0;$j<count($arr_sakit);$j++){
			$str_sakit.=" AND b.class_name LIKE '%$arr_sakit[$j]%'";
		}
		
		$q_sakit = mysql_query($str_sakit) or die(mysql_error());

		if(mysql_num_rows($q_sakit)>0):
			list($notesid,$vita_date,$age_months,$pxdob) = mysql_fetch_array($q_sakit);			
			$sakit_count[strtoupper(chr($i+97))] = 1;
		endif;
	}
	
	if(!empty($sakit_count)):
		//echo $vita_date.'/'.$pxdob.'/'.$age_months.'<br>';
		
		if($age_months>=6 && $age_months<=11):
			$arr_vita[0] = '/'; //$vita_date;
		elseif($age_months>=12 && $age_months<=59):
			$arr_vita[1] = '/';
		elseif($age_months>=60 && $age_months<=71):
			$arr_vita[2] = '/';
		else: // < 6 months and > 72 months			
		endif;
		
		$sakit_code = implode(',',array_keys($sakit_count));
		
		$arr_vita[3] = $sakit_code;
		$arr_vita[4] = $vita_date;		

		return $arr_vita;
	else:
		return;
	endif;
}	

function get_anemia_tx($consult_id){
	$r_anemia = array();

	$q_anemia = mysql_query("SELECT floor((TO_DAYS(date_format(a.consult_date,'%Y-%m-%d'))-TO_DAYS(e.patient_dob))/30) age_months, b.anemia_start_date,b.anemia_completed_date FROM m_consult a, m_consult_notes b, m_consult_notes_dxclass c, m_lib_notes_dxclass d, m_patient e WHERE a.consult_id='$consult_id' AND a.consult_id=b.consult_id AND b.notes_id=c.notes_id AND c.class_id=d.class_id AND d.class_name LIKE '%anemia%' AND a.patient_id=e.patient_id") or die("Cannot query: 253");

	if(mysql_num_rows($q_anemia)!=0):
		list($age_months,$anemia_sdate,$anemia_edate) = mysql_fetch_array($q_anemia);
		$r_anemia["age_months"] = $age_months;
		$r_anemia["anemia_sdate"] = ($anemia_sdate=='0000-00-00')?'-':$anemia_sdate;
		$r_anemia["anemia_edate"] = ($anemia_edate=='0000-00-00')?'-':$anemia_edate;
	endif;

	return $r_anemia;
}

function get_diarrhea_tx($consult_id){
	$r_diarrhea = array();

	$q_diarrhea = mysql_query("SELECT floor((TO_DAYS(date_format(a.consult_date,'%Y-%m-%d'))-TO_DAYS(e.patient_dob))/30) age_months, b.diarrhea_ort,b.diarrhea_ors,b.diarrhea_orswz FROM m_consult a, m_consult_notes b, m_consult_notes_dxclass c, m_lib_notes_dxclass d, m_patient e WHERE a.consult_id='$consult_id' AND a.consult_id=b.consult_id AND b.notes_id=c.notes_id AND c.class_id=d.class_id AND d.class_name LIKE '%pneumonia%' AND a.patient_id=e.patient_id") or die("Cannot query: 277");

	if(mysql_num_rows($q_diarrhea)!=0):
		list($age_month,$ort,$ors,$orswz) = mysql_fetch_array($q_diarrhea);

		$r_diarrhea["age_month"] = $age_month;
		$r_diarrhea["ort"] = ($ort=='0000-00-00')?'-':$ort;
		$r_diarrhea["ors"] = ($ors=='0000-00-00')?'-':$ors;
		$r_diarrhea["orswz"] = ($orswz=='0000-00-00')?'-':$orswz;
	endif;
	return $r_diarrhea;
}

function get_pneumonia_tx($consult_id){
	$r_pneumonia = array();

	$q_pneumonia = mysql_query("SELECT floor((TO_DAYS(date_format(a.consult_date,'%Y-%m-%d'))-TO_DAYS(e.patient_dob))/30) age_months, b.pneumonia_date_given FROM m_consult a, m_consult_notes b, m_consult_notes_dxclass c, m_lib_notes_dxclass d, m_patient e WHERE a.consult_id='$consult_id' AND a.consult_id=b.consult_id AND b.notes_id=c.notes_id AND c.class_id=d.class_id AND d.class_name LIKE '%pneumonia%' AND a.patient_id=e.patient_id") or die("CAnnot query: 277");

	if(mysql_num_rows($q_pneumonia)!=0):
		list($age_month,$pneu_date) = mysql_fetch_array($q_pneumonia);
		$r_pneumonia["age_month"] = $age_month;
		$r_pneumonia["pneu_date"] = ($pneu_date=='0000-00-00')?'-':$pneu_date;
	endif;

	return $r_pneumonia;
}

function get_tx_remark($consult_id){
	$q_remark = mysql_query("SELECT notes_plan FROM m_consult_notes WHERE consult_id='$consult_id'") or die("Cannot query: 307");
	
	list($remark) = mysql_fetch_array($q_remark);	
	return $remark;
}


}


$_SESSION[pahina] = $page==1?1:2;

$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$_SESSION[pahina]==1?$pdf->show_ccdev_sick1():$pdf->show_ccdev_sick2();

$pdf->Output();

?>