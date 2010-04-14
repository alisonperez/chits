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
	
	
	
	if($_SESSION[ques]==90):
	    $this->SetFont('Arial','B',12);	
	    
	    $this->Cell(0,5,'TB SYMPTOMATIC MASTERLIST'.'( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$_SESSION[datanode][name],0,1,'C');
	    $this->SetFont('Arial','',10);
	    $this->Cell(0,5,$brgy_label,0,1,'C');	
	
	    $w = array(20,25,50,40,10,10,84,44,17,40); //340
	    $header = array('Family Serial No.','Date of Registration','Name','Address','Age','Sex','Date Sputum Collected / Examination Result','X-ray Examination','TB Case Number','Remarks');

	    $this->SetFont('Arial','',8);	
	    $w2 = array(20,25,50,40,10,10,42,42,22,22,17,40);
	    $header2 = array('','','','','','','1st','2nd','Date Referred','Date & result','','');		    
	    
        elseif($_SESSION[ques]==91):
            $this->SetFont('Arial','B',12);	
            $this->Cell(0,5,'NTP LABORATORY REGISTER'.'( '.$_SESSION[sdate_orig].' to '.$_SESSION[edate_orig].' )'.' - '.$_SESSION[datanode][name],0,1,'C');        
            
            $this->SetFont('Arial','',10);
	    $this->Cell(0,5,$brgy_label,0,1,'C');	
	    
	    $w = array(15,23,35,10,7,35,40,25,84,35,30); //340
	    $header = array('Family Serial No.','Date of Registration','Name','Age','Sex','Name of Collection/Treatment Unit','Address','Reason for Examination','Date of Examination / Result','Remarks','Signature of MT/Microscopist');
	        	        	        
            $this->SetFont('Arial','',8);	
	                
            $w2 = array(15,23,35,10,7,35,40,10,15,42,42,35,30);
            $header2 = array('','','','','','','','DX','F-up (TB Case no)','1st','2nd','','');
            	                        	                        	    
        else:
        
        endif;


	
        
        $this->SetWidths($w);
        $this->Row($header);
        
        $this->SetWidths($w2);
        $this->Row($header2);
}


function show_symptomatic(){ 
    
    
    if($_SESSION[brgy]=='all'):
        $q_symptomatic = mysql_query("SELECT patient_id,date_seen,TO_DAYS(date_seen) as day_seen,ntp_id,sputum_diag1,sputum_diag2,xray_date_referred, xray_date_received,xray_result,remarks FROM m_consult_ntp_symptomatics WHERE date_seen BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' ORDER by date_seen ASC") or die("Cannot query: 150: ".mysql_error()); 
    else:
        $q_symptomatic = mysql_query("SELECT a.patient_id,a.date_seen,TO_DAYS(a.date_seen),a.ntp_id,a.sputum_diag1,a.sputum_diag2,a.xray_date_referred,a.xray_date_received,a.xray_result,a.remarks FROM m_consult_ntp_symptomatics a,m_family_members b,m_family_address c WHERE a.date_seen BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id='$_SESSION[brgy]' ORDER by date_seen ASC") or die("Cannot query: 152: ".mysql_error());     
    endif;                
    
    if(mysql_num_rows($q_symptomatic)!=0):

        while(list($pxid,$date_seen,$day_seen,$ntp_id,$sputum_diag1,$sputum_diag2,$xray_refer,$xray_receive,$xray_result,$remarks)=mysql_fetch_array($q_symptomatic)){
                        
            
            $q_px = mysql_query("SELECT a.patient_lastname,a.patient_firstname,c.address,d.barangay_name,TO_DAYS(a.patient_dob) as kaarawan, b.family_id,a.patient_gender FROM m_patient a,m_family_members b,m_family_address c,m_lib_barangay d WHERE a.patient_id='$pxid' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id=d.barangay_id") or die("Cannot query 157 ".mysql_error());
            
            if(mysql_num_rows($q_px)!=0):                            
                while(list($lname,$fname,$address,$brgy,$birthday,$family_id,$gender)=mysql_fetch_array($q_px)){
                
                    $sputum1 = $sputum2 = "";
                    $q_sputum1 = mysql_query("SELECT sp1_collection_date,sp2_collection_date,sp3_collection_date,lab_diagnosis FROM m_consult_lab_sputum WHERE request_id='$sputum_diag1'") or die("Cannot query 161 ".mysql_error());
                    list($a_sp1,$a_sp2,$a_sp3,$a_diag) = mysql_fetch_array($q_sputum1);
                    
                    $q_sputum2 = mysql_query("SELECT sp1_collection_date,sp2_collection_date,sp3_collection_date,lab_diagnosis FROM m_consult_lab_sputum WHERE request_id='$sputum_diag2'") or die("Cannot query 165 ".mysql_error());
                    list($b_sp1,$b_sp2,$b_sp3,$b_diag) = mysql_fetch_array($q_sputum2);
                                        
                    $edad = ($day_seen-$birthday)/365;                    
                    $edad = ($edad<1)?round($edad,1):floor($edad);
                    
                    if(mysql_num_rows($q_sputum1)!=0):
                        $result1 = $this->get_result($a_diag);
                        $sputum1 = $a_sp1.'/'.$a_sp2.'/'.$a_sp3.'('.$result1.')';
                    endif;
                    
                    if(mysql_num_rows($q_sputum2)!=0):
                        $result2 = $this->get_result($b_diag);
                        $sputum2 = $b_sp1.'/'.$b_sp2.'/'.$b_sp3.'('.$result2.')';
                    endif;
                    
                    $xray_res = $this->get_result($xray_result);
                    $xray_receive = ($xray_receive=='0000-00-00')?'':$xray_receive;
                    $xray_refer = ($xray_refer=='0000-00-00')?'':$xray_refer;                    
                    
                    $this->SetFont('Arial','',10);                    
                    
                    if($_SESSION[ques]==90):
                        $w = array(20,25,50,40,10,10,42,42,22,22,17,40);                    
                        $this->SetWidths($w);                                        
                        $this->Row(array($family_id,$date_seen,$lname.', '.$fname,$address.', '.$brgy,$edad,$gender,$sputum1,$sputum2,$xray_refer,$xray_receive.''.$xray_res,$ntp_id,$remarks));
                    elseif($_SESSION[ques]==91):
                        $w = array(15,23,35,10,7,35,40,10,15,42,42,35,30);                    
                        $this->SetWidths($w);                                        
                        $this->Row(array($family_id,$a_sp1,$lname.', '.$fname,$edad,$gender,$_SESSION[datanode][name].' '.'RHU',$address.', '.$brgy,'','',$sputum1,$sputum2,$remarks,''));                    
                    else:
                    
                    endif;
                }                            
            endif;
        }                        
    endif;
}


function get_result($diag){
    switch($diag){    
        case 'P':
            return 'Positive';
            break;
            
        case 'N':
            return 'Negative';
            break;        
        case 'D':
            return 'Doubtful';
            break;
        default:
            return '';
            break;
    
    }
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

$pdf->show_symptomatic();

$pdf->Output();

?>
