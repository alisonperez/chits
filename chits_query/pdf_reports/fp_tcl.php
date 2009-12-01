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
	$this->Cell(0,5,'Target Client List for Family Planning - '.$_SESSION[fp_method] .' ( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$_SESSION[datanode][name],0,1,'C');


	$this->SetFont('Arial','',10);

	$this->Cell(0,5,$brgy_label,0,1,'C');	

	if($_SESSION[pahina]==1):
	$w = array(35,35,65,130,25,25,25); //340
	$header = array('DATE OF REGISTRATION','FAMILY SERIAL NO.','NAME','ADDRESS','AGE','TYPE OF CLIENT','PREVIOUS METHOD');

	
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


function show_fp1(){ //this method shall extract the FP records on page 1 of the TCL. source data will be session of FP patients and method id's
    $r_px_id = $_SESSION[fp_px];
    $r_fp_id = $_SESSION[fp_method_id];
    
    for($i=0;$i<count($r_fp_id);$i++){
        $q_date_reg = mysql_query("SELECT date_format(date_registered,'%m/%d/%Y') as date_reg, TO_DAYS(date_registered) as reg_days,client_code FROM m_patient_fp_method WHERE fp_px_id='$r_fp_id[$i]'") or die("Cannot query (162): mysql_error()");
        list($dreg,$reg_days,$client_code) = mysql_fetch_array($q_date_reg);        
                
        if($_SESSION[brgy]=='all'):
            $q_px_info = mysql_query("SELECT a.patient_lastname,a.patient_firstname,b.address,c.barangay_name,TO_DAYS(patient_dob) as dob_days,b.family_id FROM m_patient a,m_family_address b,m_lib_barangay c,m_family_members d WHERE a.patient_id='$r_px_id[$i]' AND a.patient_id=d.patient_id AND d.family_id=b.family_id AND b.barangay_id=c.barangay_id") or die("Cannot query(166): mysql_error()");        
        else:
            $q_px_info = mysql_query("SELECT a.patient_lastname,a.patient_firstname,b.address,c.barangay_name,TO_DAYS(patient_dob),b.family_id as dob_days FROM m_patient a,m_family_address b,m_lib_barangay c,m_family_members d WHERE a.patient_id='$r_px_id[$i]' AND a.patient_id=d.patient_id AND d.family_id=b.family_id AND b.barangay_id='$_SESSION[brgy]' AND b.barangay_id=c.barangay_id") or die("Cannot query(168): mysql_error()");
        endif;
        
        list($lname,$fname,$address,$brgy,$dob_days,$family_id) = mysql_fetch_array($q_px_info);                
        $edad = floor(($reg_days - $dob_days)/365);
    
        $q_prev_method = mysql_query("SELECT a.method_id,b.method_name FROM m_patient_fp_method a, m_lib_fp_methods b WHERE a.patient_id='$r_px_id[$i]' AND a.method_id=b.method_id ORDER by a.date_registered DESC") or die("Cannot query(174): ".mysql_error());
        $arr_prev = array();
        while(list($method_id,$method_name)=mysql_fetch_array($q_prev_method)){
            array_push($arr_prev,$method_id);
        }
        
        $this->Row(array($dreg,$family_id,$lname.', '.$fname,$address.', '.$brgy,$edad,$client_code,$arr_prev[1]));  //TODO
    }

}

function show_fp2(){

}


function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}

}



$_SESSION[pahina] = ($page==1)?1:2;

$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$_SESSION[pahina]==1?$pdf->show_fp1():$pdf->show_fp2();

$pdf->Output();

?>