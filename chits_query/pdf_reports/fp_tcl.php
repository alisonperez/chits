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
	$main_width = array(252,41,47);
	$main_content = array('FOLLOW UP VISITS (Upper Space: Next Service Date / Lower Space: Date Accomplished)','DROP-OUTS','REMARKS / ACTION TAKEN');
	
	$this->SetWidths($main_width);
	$this->Row($main_content);

	$w = array(21,21,21,21,21,21,21,21,21,21,21,21,20,21,47);
	$header = array('1ST','2ND','3RD','4TH','5TH','6TH','7TH','8TH','9TH','10TH','11TH','12TH','REASON','DATE','');

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
    
        $q_prev_method = mysql_query("SELECT a.method_id,b.method_name FROM m_patient_fp_method a, m_lib_fp_methods b WHERE a.patient_id='$r_px_id[$i]' AND a.method_id=b.method_id AND '$_SESSION[sdate2]' > a.date_registered ORDER by a.date_registered DESC LIMIT 1") or die("Cannot query(174): ".mysql_error());
                                
        $arr_prev = array();
        
        while(list($method_id,$method_name)=mysql_fetch_array($q_prev_method)){
            array_push($arr_prev,$method_id);
        }
        
        $this->Row(array($dreg."\n".' ',$family_id,$lname.', '.$fname,$address.', '.$brgy,$edad,$client_code,$arr_prev[0]));
    }

}

function show_fp2(){
    $r_px_id = $_SESSION[fp_px];
    $r_fp_id = $_SESSION[fp_method_id];
    
    //print_r($r_fp_id);
    $w = array(252,50,47);
    
    for($i=0;$i<count($r_fp_id);$i++){                
        $arr_disp = array();        
        $arr_service = $this->followup_visit($r_fp_id[$i],$r_px_id[$i]);
       
        $q_drop = mysql_query("SELECT a.date_dropout,b.fhsis_code,a.dropout_remarks FROM m_patient_fp_method a,m_lib_fp_dropoutreason b WHERE a.fp_px_id='$r_fp_id[$i]' AND a.patient_id='$r_px_id[$i]' AND a.dropout_reason=b.reason_id") or die("Cannot query (198): ".mysql_error());
        
        list($date_dropout,$fhsis_code,$remarks) = mysql_fetch_array($q_drop);
        
        for($j=0;$j<12;$j++){
            if(!empty($arr_service)):
                array_push($arr_disp,$arr_service[$j][next]."\n".$arr_service[$j][serv]);
            else:
                array_push($arr_disp,' '."\n".' ');
            endif;
        }
        
        array_push($arr_disp,$fhsis_code);
        array_push($arr_disp,$date_dropout);
        array_push($arr_disp,$remarks);
                    
    
        $this->Row($arr_disp);    
    }
    
}

function followup_visit($fp_id,$px_id){ //returns an associative array with 12 elements representing month 1 to 12
    
    $arr_followup = array();

    //$q_service = mysql_query("SELECT a.date_service,a.next_service_date,b.date_registered,TO_DAYS(date_service) as days_service,TO_DAYS(next_service_date) as days_next,TO_DAYS(date_registered) as days_reg FROM m_patient_fp_method_service a,m_patient_fp_method b WHERE a.fp_px_id='$fp_id' AND a.fp_px_id=b.fp_px_id AND a.patient_id='$px_id' ORDER by next_service_date DESC") or die("Cannot query(202): ".mysql_error());
    
    $q_service = mysql_query("SELECT a.fp_service_id,a.date_service,a.next_service_date,b.date_registered FROM m_patient_fp_method_service a,m_patient_fp_method b WHERE a.fp_px_id='$fp_id' AND a.fp_px_id=b.fp_px_id AND a.patient_id='$px_id' ORDER by date_service ASC") or die("Cannot query(202): ".mysql_error()); 
    
    while(list($service_id,$date_service,$next_service_date,$reg_date) = mysql_fetch_array($q_service)){
                    
        list($serv_yr,$serv_month,$serv_date) = explode('-',$date_service);
        list($next_yr,$next_month,$next_date) = explode('-',$next_service_date);
        list($reg_yr,$reg_month,$reg_date) = explode('-',$reg_date);
        
        $arr_service = array('year'=>$serv_yr,'month'=>$serv_month,'day'=>$serv_date);
        $arr_next = array('year'=>$next_yr,'month'=>$next_month,'day'=>$next_date);
        $arr_reg = array('year'=>$reg_yr,'month'=>$reg_month,'day'=>$reg_date);        

        $arr_diff_service = $this->date_difference($arr_reg,$arr_service);        
        $arr_diff_next = $this->date_difference($arr_reg,$arr_next);
        
        $arr_followup[$arr_diff_service[months]][serv] = $date_service;
        $arr_followup[$arr_diff_next[months]][next] = $next_service_date;        
    }
    
    return $arr_followup;
}




function smoothdate ($year, $month, $day){
    return sprintf ('%04d', $year) . sprintf ('%02d', $month) . sprintf ('%02d', $day);    
}

function date_difference ($first, $second)
{

    $month_lengths = array (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $retval = FALSE;

    if (    checkdate($first['month'], $first['day'], $first['year']) &&
            checkdate($second['month'], $second['day'], $second['year'])
        )
    {
        $start = $this->smoothdate ($first['year'], $first['month'], $first['day']);
        $target = $this->smoothdate ($second['year'], $second['month'], $second['day']);
                            
        if ($start <= $target)
        {
            $add_year = 0;
            while ($this->smoothdate ($first['year']+ 1, $first['month'], $first['day']) <= $target)
            {
                $add_year++;
                $first['year']++;
            }
                                                                                                            
            $add_month = 0;
            while ($this->smoothdate ($first['year'], $first['month'] + 1, $first['day']) <= $target)
            {
                $add_month++;
                $first['month']++;
                
                if ($first['month'] > 12)
                {
                    $first['year']++;
                    $first['month'] = 1;
                }
            }
                                                                                                                                                                            
            $add_day = 0;
            while ($this->smoothdate ($first['year'], $first['month'], $first['day'] + 1) <= $target)
            {
                if (($first['year'] % 100 == 0) && ($first['year'] % 400 == 0))
                {
                    $month_lengths[1] = 29;
                }
                else
                {
                    if ($first['year'] % 4 == 0)
                    {
                        $month_lengths[1] = 29;
                    }
                }
                
                $add_day++;
                $first['day']++;
                if ($first['day'] > $month_lengths[$first['month'] - 1])
                {
                    $first['month']++;
                    $first['day'] = 1;
                    
                    if ($first['month'] > 12)
                    {
                        $first['month'] = 1;
                    }
                }
                
            }
                                                                                                                                                                                                                                                        
            $retval = array ('years' => $add_year, 'months' => $add_month, 'days' => $add_day);
        }
    }
                                                                                                                                                          
    return $retval;
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
