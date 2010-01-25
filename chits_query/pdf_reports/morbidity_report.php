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

    $this->q_report_header();
    
    $arr_gender = array();
    $this->SetFont('Arial','B','15');
    $this->Cell(340,8,'M O R B I D I T Y   D I S E A S E   R E P O R T',1,1,C);
    
    $this->SetFont('Arial','','8');
    $w = array(60,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,24);    
    $this->SetWidths($w);
    $label = array('DISEASE','ICD CODE','Under 1','1-4','5-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50-54','55-59','60-64','65&above','TOTAL');
    $this->Row($label);
    
    $w = array(60,16,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,12,12);        
    array_push($arr_gender,' ',' ');
    
    for($i=0;$i<16;$i++){
        array_push($arr_gender,'M','F');
    }
    $this->SetWidths($w);
    $this->Row($arr_gender);
    
}

function q_report_header(){
    
    $this->SetFont('Arial','B','12');
    $taon = $_SESSION[year];
    if($_SESSION[ques]==70):   //morbidity weekly report
        $freq = 'FHSIS REPORT FOR THE WEEK: ';
        $freq_val = $_SESSION[week];        
    elseif($_SESSION[ques]==71)://morbidity monthly report
        $freq = 'FHSIS REPORT FOR THE MONTH: ';
        $freq_val = date('F',mktime(0,0,0,$_SESSION[smonth],1,0));
    elseif($_SESSION[ques]==72):
        $freq = 'FHSIS REPORT FOR THE QUARTER: ';
        $freq_val = $_SESSION[quarter];
    elseif($_SESSION[ques]==73):
        $freq = 'FHSIS ANNUAL REPORT FOR THE YEAR: ';
        $freq_val = $_SESSION[year];
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
    if($_SESSION[ques]==73):
        $this->Cell(0,5,$freq.$freq_val."          YEAR: ".$_SESSION[year],0,1,L);            
    else:
        $this->Cell(0,5,$freq.$freq_val."          YEAR: ".$_SESSION[year],0,1,L);    
    endif;
}

function show_header_bhs(){    
    if($_SESSION[ques]==70 || $_SESSION[ques]==71):  //applies only to W-BHS and M2 reports
        $this->Cell(0,5,'NAME OF BHS/BHC - Brgy '.$this->get_brgy(),0,1,L);    
    endif;        
}

function show_header_rhu(){
    if($_SESSION[ques]==70 || $_SESSION[ques]==71):   //applies only to W-BHS and M2 reports
        $this->Cell(0,5,'CATCHMENT RHU/BHS: '.$_SESSION[datanode][name],0,1,L);        
    endif;
}

function show_header_lgu(){
    if($_SESSION[ques]==72 || $_SESSION[ques]==73): //applies only to Q2 and A2 reports
        $this->Cell(0,5,'MUNICIPALITY/CITY OF: '.$_SESSION[lgu],0,1,L);
    endif;
}

function show_header_province(){
    if($_SESSION[ques]==72 || $_SESSION[ques]==73): //applies only to Q2 and A2 reports
        $this->Cell(0,5,'PROVINCE: '.$_SESSION[province],0,1,L);
    endif;
}




function show_morbidity(){
    
    $w = array(60,16,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,12,12);
    
    $arr_gender = array('M','F');
    
            
    //print_r($arr_age_group);
    
    $arr_diag = array();
    
    $str_brgy = $this->get_brgy();    
    
    $q_diagnosis = mysql_query("SELECT a.class_id,COUNT(a.class_id) as 'bilang',e.class_name FROM m_consult_notes_dxclass a, m_patient b, m_family_members c, m_family_address d,m_lib_notes_dxclass e WHERE a.diagnosis_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id IN ($str_brgy) AND a.class_id=e.class_id GROUP by class_id ORDER by bilang DESC,a.class_id ASC") or die("Cannot query 158: ".mysql_error());
            
    //echo mysql_num_rows($q_diagnosis);
    if(mysql_num_rows($q_diagnosis)!=0):
    $bilang = 0;              
    while(list($diag_id, $count, $diag_name, $pxid) = mysql_fetch_array($q_diagnosis)){
        //echo $diag_id.'/'.$count.'/'.$diag_name.'<br>';
        
        //initialize arr_age_group and arr_age for every diagnosis iteration
        $arr_age_group = array('<0'=>array('M'=>0,'F'=>0),'1-4'=>array('M'=>0,'F'=>0),'5-9'=>array('M'=>0,'F'=>0),'10-14'=>array('M'=>0,'F'=>0),'15-19'=>array('M'=>0,'F'=>0),'20-24'=>array('M'=>0,'F'=>0),'25-29'=>array('M'=>0,'F'=>0),'30-34'=>array('M'=>0,'F'=>0),'35-39'=>array('M'=>0,'F'=>0),
                    '40-44'=>array('M'=>0,'F'=>0),'45-49'=>array('M'=>0,'F'=>0),'50-54'=>array('M'=>0,'F'=>0),'55-59'=>array('M'=>0,'F'=>0),'60-64'=>array('M'=>0,'F'=>0),'>65'=>array('M'=>0,'F'=>0));
        $arr_age = array();
        $arr_row = array();
        $total_male = $total_female = 0;  
        $bilang += 1;    

        foreach($arr_gender as $gender_key=>$gender){
            //echo $gender;
            $q_px_id = mysql_query("SELECT a.patient_id,round((to_days(a.diagnosis_date)-to_days(b.patient_dob))/365,0) as computed_age FROM m_consult_notes_dxclass a, m_patient b WHERE a.diagnosis_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' AND a.class_id='$diag_id' AND a.patient_id=b.patient_id AND b.patient_gender='$gender'") or die("Cannot query 164 ".mysql_error());
        
            while(list($pxid,$computed_age) = mysql_fetch_array($q_px_id)){
                if($this->get_px_brgy($pxid,$str_brgy)):
                    if($computed_age >= 1):
                        $arr_age[$computed_age][$gender] += 1;
                    else:
                        $arr_age['<0'][$gender] += 1;
                    endif;
                endif;
                //echo $pxid.'/'.$computed_age.'<br>';
            }
            //after the execution of this while loop, arr_age will contain count per age per gender (i.e. arr_age[20][M],arr_age[20][F])
            
            
            foreach($arr_age as $edad=>$arr_kasarian){            
                if($edad=='<0'):                                                
                    $arr_age_group['<0'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=1 && $edad<=4):
                    $arr_age_group['1-4'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=5 && $edad<=9):
                    $arr_age_group['5-9'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=10 && $edad<=14):
                    $arr_age_group['10-14'][$gender] += $arr_age[$edad][$gender];                    
                elseif($edad>=15 && $edad<=19):
                    $arr_age_group['15-19'][$gender] += $arr_age[$edad][$gender];                    
                elseif($edad>=20 && $edad<=24):
                    $arr_age_group['20-24'][$gender] += $arr_age[$edad][$gender];        
                elseif($edad>=25 && $edad<=29):
                    $arr_age_group['25-29'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=30 && $edad<=34):
                    $arr_age_group['30-34'][$gender] += $arr_age[$edad][$gender];                    
                elseif($edad>=35 && $edad<=39):
                    $arr_age_group['35-39'][$gender] += $arr_age[$edad][$gender];                                        
                elseif($edad>=40 && $edad<=44):
                    $arr_age_group['40-44'][$gender] += $arr_age[$edad][$gender];                    
                elseif($edad>=45 && $edad<=49):
                    $arr_age_group['45-49'][$gender] += $arr_age[$edad][$gender];                                                            
                elseif($edad>=50 && $edad<=54):
                    $arr_age_group['50-54'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=55 && $edad<=59):
                    $arr_age_group['55-59'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=60 && $edad<=64):
                    $arr_age_group['60-64'][$gender] += $arr_age[$edad][$gender];
                elseif($edad>=65):
                    $arr_age_group['>65'][$gender] += $arr_age[$edad][$gender];                    
                else:
                
                endif;     
            }            
            
        } 
        //after this foreach loop, arr_age_group will contain count per age group, per gender. array size is 32 (0-31)
      
      array_push($arr_row,$diag_name,' ');
        
      //print_r($arr_age_group);
      
      foreach($arr_age_group as $age_group=>$arr_sex){
          foreach($arr_sex as $kasarian=>$kasarian_count){
              array_push($arr_row,$kasarian_count);
              if($kasarian=='M'):
                  $total_male += $kasarian_count;
              else:
                  $total_female += $kasarian_count;
              endif;
          }      
      }

          array_push($arr_row,$total_male,$total_female);                    
          $this->SetFont('Arial','','7');          
          
          for($x=0;$x<count($arr_row);$x++){
              
              if($x==0):
                  $this->Cell($w[$x],6,$bilang.'. '.$arr_row[$x],'1',0,'L');
              else:
                  $this->Cell($w[$x],6,$arr_row[$x],'1',0,'L');              
              endif;              
          }
          
          $this->Ln();
          
          //$this->Row($arr_row);
     }
    
    else:
          $this->SetWidths(array('340'));
          $this->SetFont('Arial','','10');          
          $this->Row(array('No recorded morbidity and notifiable disease for this period'));
    endif;                        
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

function sanitize_brgy(){
    if(func_num_args()>0):
        $args = func_get_args();
        $query = $args[0];
        $brgy = $args[1];        
        
    endif;
        
    $arr_count = array();
        
    
    if(mysql_num_rows($query)!=0): 
        while($r_query = mysql_fetch_array($query)){
            if($this->get_px_brgy($r_query[patient_id],$brgy)):
                array_push($arr_count,$r_query);
            endif;
        }
    endif;
    
    return $arr_count;
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

$pdf->show_morbidity();

//$pdf->AddPage();
//$pdf->show_fp_summary();
$pdf->Output();

?>
