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
    $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("Cannot query: 123". mysql_error());
    list($population)= mysql_fetch_array($q_pop);
    
    $this->q_report_header($population);
    $this->Ln(10);
    
    $this->SetFont('Arial','BI','20');
    $this->Cell(340,10,'D E M O G R A P H I C    P R O F I L E (A1 RHU)',1,1,C);
    
    $this->SetFont('Arial','B','12');
    $w = array(100,30,30,30,30,60,60);
    $this->SetWidths($w);
    $label = array('Indicators','Male','Female','Total','Ratio to Population','Interpretation','Recommendation/Action Taken');
    $this->Row($label);
}

function q_report_header($population){
    $this->SetFont('Arial','B','12');
    $this->Cell(0,5,'FHSIS REPORT FOR THE YEAR: '.$_SESSION[year],0,1,L);
    $this->Cell(0,5, 'MUNICAPLITY/CITY NAME: '.$_SESSION[datanode][name],0,1,L);
    $this->Cell(0,5,'PROVINCE: '.$_SESSION[province]."          PROJECTED POPULATION OF THE YEAR: ".$population,0,1,L);
}


function show_demographic_profile(){
    
    $q_demographic = mysql_query("SELECT barangay,bhs,doctors_male,doctors_female,dentist_male,dentist_female,nurse_male,nurse_female,midwife_male,midwife_female,nutritionist_male,nutritionist_female,medtech_male,medtech_female,se_male,se_female,si_male,si_female,bhw_male,bhw_female FROM m_lib_demographic_profile WHERE year='$_SESSION[year]'") or die("Cannot query 149 ".mysql_error());
    list($brgy,$bhs,$md_m,$md_f,$dentist_m,$dentist_f,$nurse_m,$nurse_f,$midwife_m,$midwife_f,$nutritionist_m,$nutritionist_f,$medtech_m,$medtech_f,$se_m,$se_f,$si_m,$si_f,$bhw_m,$bhw_f) = mysql_fetch_array($q_demographic);

    $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("Cannot query: 123". mysql_error());
    list($population)= mysql_fetch_array($q_pop);    
        
    $arr_indicators = array(0=>array('Barangays','','',$brgy,'','',''),1=>array('Barangay Health Station','','',$bhs,'','',''),2=>array('Doctors',$md_m,$md_f,($md_m + $md_f),'1:'.round($population/($md_m + $md_f)),'',''),3=>array('Dentist',$dentist_m,$dentist_f,($dentist_m+$dentist_f),'1:'.round($population/($dentist_m+$dentist_f)),'',''),4=>array('Nurses',$nurse_m,$nurse_f,($nurse_m+$nurse_f),'1:'.round($population/($nurse_m+$nurse_f)),'',''),5=>array('Midwives',$midwife_m,$midwife_f,($midwife_m+$midwife_f),'1:'.round($population/($midwife_m+$midwife_f)),'',''),6=>array('Nutritionist',$nutritionist_m,$nutritionist_f,($nutritionist_m + $nutritionist_f),'1:'.round($population/($nutritionist_m+$nutritionist_f)),'',''),7=>array('Medical Technologist',$medtech_m,$medtech_f,($medtech_m+$medtech_f),'1:'.round($population/($medtech_m+$medtech_f)),'',''),8=>array('Sanitary Engineers',$se_m,$se_f,($se_m+$se_f),'1:'.round($population/($se_m+$se_f)),'',''),9=>array('Sanitary Inspectors',$si_m,$si_f,($si_m+$si_f),'1:'.round($population/($si_m+$si_f)),'',''),10=>array('Active BHW',$bhw_m,$bhw_f,($bhw_m+$bhw_f),'1:'.round($population/($bhw_m+$bhw_f)),'',''));
    
    $w = array(100,30,30,30,30,60,60);
    
    for($i=0;$i<count($arr_indicators);$i++){
        for($x=0;$x<count($arr_indicators[$i]);$x++){
            //echo $arr_indicators[$i][$x].'<br>';
            $this->Cell($w[$x],6,$arr_indicators[$i][$x],'1',0,'L');            
        }
        $this->Ln();
    }
    
    //print_r($arr_indicators);
    
    
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

$pdf->show_demographic_profile();

//$pdf->AddPage();
//$pdf->show_fp_summary();
$pdf->Output();

?>
