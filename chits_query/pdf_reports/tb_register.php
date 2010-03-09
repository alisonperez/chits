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
    //$this->q_report_header();
    //print_r($_SESSION);
    $date_label = $_SESSION[sdate_orig].' to '.$_SESSION[edate_orig];    
    $municipality_label = $_SESSION[datanode][name];    
    
    $this->SetFont('Arial','B','15');    
    $this->Cell(0,5,'T B    R E G I S T E R ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
    
    
    $arr_brgy = array($_SESSION[brgy]);
    
    if(in_array('all',$arr_brgy)):        
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
        
    $this->SetFont('Arial','','10');
    $this->Cell(0,5,$brgy_label,0,1,'C');		    
    $this->Ln(10);
    
    if($_SESSION[pahina]==1):
        $w = array(21,20,40,9,9,50,40,20,40,18,58,17);        
        $header = array('Date of Registration','TB Case No.','Name','Age','Sex','Address','Health Facility','Source of Patient','Name of Referring Physician','Class of TB Diag (P/EP)','Type of Patient','Category');
        
        $w2 = array(21,20,40,9,9,50,40,10,10,40,18,8,13,13,8,8,8,17);
        $header2 = array('','','','','','','','Pub','Pri','','','New','Relapse','Trans In','RAD','Fail','Oth','');
    
    elseif($_SESSION[pahina]==2):
        $w = array(25,161,90,20,20,20);
        $header = array('Date Treatment Started','Sputum Examination Results / Weight Record','Treatment Outcome','Type of TX Partner','TBDC Review (Y/N)','Remarks');
    
        $w2 = array(25,23,23,23,23,23,23,23,15,15,15,15,15,15,20,20,20);
        $header2 = array('','Before TX','2nd mo','3rd mo','4th mo','5th mo','6th mo','>7 mo','Cured','Tx Comp','Died','Failed','Defaulted','Time Out','','','');
        
    else:
    
    endif;
    
    $this->SetFont('Arial','','8');    
    $this->SetWidths($w);
    $this->Row($header);
    $this->SetWidths($w2);
    $this->Row($header2);
    
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

function show_first(){
    $r_patient = $_SESSION[ntp_px];
    $r_ntp = $_SESSION[ntp_id];
    
    
    for($x=0;$x<count($r_ntp);$x++){
        $w = array(21,20,40,9,9,50,40,10,10,40,18,8,13,13,8,8,8,17);
        $pub = $pri = $new = $relapse = $trans = $rad = $fail = $oth = "";
        $cured = $tx_comp = $died = $failed = $defaulted = $timeout = "";
        
        $q_mysql = mysql_query("SELECT date_format(ntp_consult_date,'%Y-%m-%d') as 'date_reg',ntp_id,intensive_start_date,TO_DAYS(intensive_start_date) as 'intensive_days',tb_class,patient_type_id,treatment_category_id,outcome_id,source_patient,refer_physician,tbdc_review,treatment_partner_id FROM m_patient_ntp WHERE ntp_id='$r_ntp[$x]'") or die("Cannot query 212 ".mysql_error());
        
        if(mysql_num_rows($q_mysql)!=0):            
            list($date_reg,$ntp_id,$intensive_date,$intensive_start_day,$tb_class,$pxtype,$tx_cat,$outcome,$source,$refer_md,$tbdc,$tx_partner) = mysql_fetch_array($q_mysql);
            $q_px = mysql_query("SELECT patient_lastname, patient_firstname, TO_DAYS(patient_dob) as 'bday',patient_gender FROM m_patient WHERE patient_id='$r_patient[$x]'") or die("Cannot query 221 ".mysql_error());
            list($lname,$fname,$bday_days,$gender) = mysql_fetch_array($q_px);
            
            $q_brgy = mysql_query("SELECT b.address,c.barangay_name FROM m_family_members a, m_family_address b, m_lib_barangay c WHERE a.patient_id='$r_patient[$x]' AND a.family_id=b.family_id AND b.barangay_id=c.barangay_id") or die("Cannot query 224 ".mysql_error());
            list($address,$brgy) = mysql_fetch_array($q_brgy);
            
            $edad = ($intensive_start_day-$bday_days)/365;
            $edad = ($edad < 1)?round($edad,1):floor($edad);
            
            switch($source){            
                case 'Public':
                    $pub = '/';
                    break;
                case 'Private':
                    $pri = '/';
                    break;
                default:
                    $pub = $pri = '';
                    break;  
            }
            
                    
            switch($pxtype){
                case 'FAIL':
                    $fail = '/';
                    break;
                case 'NEW':
                    $new = '/';
                    break;
                case 'OTH':
                    $oth = '/';
                    break;
                case 'RAD':
                    $rad = '/';
                    break;
                case 'REL':
                    $rel = '/';
                    break;
                case 'TIN':
                    $trans = '/';
                    break;
                default:                
                    break;                
            }
            
            
            switch($outcome){                    
                    case 'COMP':
                        $tx_comp = '/';
                        break;
                        
                    case 'CURE':
                        $cured = '/';
                        break;
                        
                    case 'DIED':
                        $died = '/';
                        break;
                        
                    case 'FAIL':
                        $fail = '/';
                        break;
                    
                    case 'LOST':
                        $defaulted = '/';
                        break;
                        
                    case 'TOUT':                    
                        $timeout = '/';
                        break;
                    
                    case 'TX':
                        $under_tx = '/';
                        break;
                                        
                    default:                        
                        break;                                        
                }            
            
            
            if($_SESSION[pahina]==1):
            
                $w = array(21,20,40,9,9,50,40,10,10,40,18,8,13,13,8,8,8,17);    
                
                $this->SetFont('Arial','','8');                
                $this->SetWidths($w);
                $this->Row(array($date_reg,$ntp_id,$lname.', '.$fname,$edad,$gender,$address.', '.$brgy,$_SESSION[datanode][name],$pub,$pri,$refer_md,$tb_class,$new,$rel,$trans,$rad,$fail,$oth,$tx_cat."\n".' '));

            elseif($_SESSION[pahina]==2):
                $w = array(25,23,23,23,23,23,23,23,15,15,15,15,15,15,20,20,20);
                //$cured = $tx_comp = $died = $failed = $defaulted = $timeout = $under_tx = "";
                $q_txpartner = mysql_query("SELECT partner_name FROM m_lib_ntp_treatment_partner WHERE partner_id='$tx_partner'") or die("Cannot query 314".mysql_error());
                list($partner_name) = mysql_fetch_array($q_txpartner);
                
                $q_before_tx = mysql_query("SELECT a.sputum_diag1,b.sp3_collection_date,b.consult_id,b.lab_diagnosis FROM m_consult_ntp_symptomatics a, m_consult_lab_sputum b WHERE a.ntp_id='$r_ntp[$x]' AND a.sputum_diag1=b.request_id") or die("Cannot query 317: ".mysql_error());
                list($sputum_diag,$before_sp3_date,$consult_id,$before_result) = mysql_fetch_array($q_before_tx);
                    
                $wt = $this->get_weight($consult_id);                                            
            
                $before_diag = $this->get_sputum_result($before_result);
            
                $before = ($before_sp3_date!='0000-00-00')?$before_sp3_date.'/'."\n".$before_diag.'/'.$wt:'';
                
                $q_sputum = mysql_query("SELECT a.consult_id,b.request_id,b.sp3_collection_date,b.sputum_period,b.lab_diagnosis FROM m_consult_ntp_labs_request a, m_consult_lab_sputum b WHERE a.ntp_id='$r_ntp[$x]' AND a.request_id=b.request_id") or die("Cannot query 340".mysql_error());
                
                if(mysql_num_rows($q_sputum)!=0):                                        
                    
                    while(list($consult_id,$request_id,$sp3,$sputum_period,$result)=mysql_fetch_array($q_sputum)){                        
                       $wt2 = $this->get_weight($consult_id);
                       $result = $this->get_sputum_result($result);
                       switch($sputum_period){
                           case 'E02':                               
                               $sputum_2nd = $sp3.'/'.$result.'/'.$wt2;
                               break;
                               
                           case 'E03':
                               $sputum_3rd = $sp3.'/'.$result.'/'.$wt2;
                               break;
                               
                           case 'E04':
                               $sputum_4th = $sp3.'/'.$result.'/'.$wt2;                               
                               break;
                               
                           case 'E05':
                               $sputum_5th = $sp3.'/'.$result.'/'.$wt2;                                                          
                               break;
                               
                           case 'E06':
                               $sputum_6th = $sp3.'/'.$result.'/'.$wt2;                                                                                      
                                break;
                                
                           case '7M':
                               $sputum_7th = $sp3.'/'.$result.'/'.$wt2;                                                                                                                 
                               break;
                           
                           default:
                               $sputum_2nd = $sputum_3rd = $sputum_4th = $sputum_5th = $sputum_6th = $sputum_7th = '';
                               break;
                       
                       }
                    }
                    
                endif;
                                
                
                $this->SetFont('Arial','','7');
                $this->SetWidths($w);
                $this->Row(array($intensive_date,$before,$sputum_2nd,$sputum_3rd,$sputum_4th,$sputum_5th,$sputum_6th,$sputum_7th,$cured,$tx_comp,$died,$failed,$defaulted,$timeout,$partner_name,$tbdc,''));
                    
            else:
                
            endif;
            
        endif;
    }
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

function get_sputum_result($diag){

    switch($diag){
        case 'P':
            $diag = 'Positive';
            break;
                        
        case 'N':
            $diag = 'Negative';
            break;
                        
        case 'D':
            $diag = 'Doubtful';
            break;
                        
        default:                    
            $diag = '';
            break;                
    }
    
    return $diag;
}

function get_weight($consult_id){
    $q_weight = mysql_query("SELECT vitals_weight FROM m_consult_vitals WHERE consult_id='$consult_id'") or die("Cannot query 320".mysql_error());
    list($wt) = mysql_fetch_array($q_weight);
    return $wt;
}

function Footer(){
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
} 



}

$_SESSION[pahina] = ($_GET[page]==1)?1:2;
$pdf = new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$pdf->show_first();

//$_SESSION[pahina]==1?$pdf->show_first():$pdf->show_first();
$pdf->Output();

?>
