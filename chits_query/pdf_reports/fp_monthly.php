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
    $this->Cell(340,10,'F A M I L Y   P L A N N I N G',1,1,C);
    
    $this->SetFont('Arial','B','12');
    $w = array(90,50,50,50,50,50);
    $this->SetWidths($w);
    $label = array('Indicators','Current User '."\n".'(Begin Mo)','New Acceptors','Others','Dropout','Current User'."\n".'(End Mo)');
    $this->Row($label);
}

function q_report_header($population){
    $this->SetFont('Arial','B','12');
    $this->Cell(0,5,'FHSIS REPORT FOR THE MONTH: '.date('F',mktime(0,0,0,$_SESSION[smonth],1,0))."          YEAR: ".$_SESSION[year],0,1,L);
    $this->Cell(0,5, 'NAME OF BHS: Brgy - '.$this->get_brgy(),0,1,L);    
    $this->Cell(0,5, 'MUNICAPLITY/CITY NAME: '.$_SESSION[datanode][name],0,1,L);
    $this->Cell(0,5,'PROVINCE: '.$_SESSION[province],0,1,L);
    $this->Cell(0,5, 'PROJECTED POPULATION OF THE YEAR: '.$population,0,1,L);
}


function show_fp_quarterly(){
    $arr_method = array('a'=>'FSTRBTL','b'=>'MSV','c'=>'PILLS','d'=>'IUD','e'=>'DMPA','f'=>'NFPCM','g'=>'NFPBBT','h'=>'NFPLAM','i'=>'NFPSDM','j'=>'NFPSTM','k'=>'CONDOM');
    $w = array(90,50,50,50,50,50);
    $str_brgy = $this->get_brgy();    
    
    //echo $_SESSION[sdate2].'/'.$_SESSION[edate2];
    
    foreach($arr_method as $col_code=>$method_code){
        $q_fp = mysql_query("SELECT method_name FROM m_lib_fp_methods WHERE method_id='$method_code'") or die("Cannot query: 151".mysql_error());    
        list($method_name) = mysql_fetch_array($q_fp);
        
        $cu_prev = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,2);
        $na_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,3);
        $other_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,4);
        $dropout_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,5 );
        $cu_pres = ($cu_prev + $na_pres + $other_pres) - $dropout_pres;
        
                
        $fp_contents = array($col_code.'. '.$method_name,$cu_prev,$na_pres,$other_pres,$dropout_pres,$cu_pres);
        
        
        for($x=0;$x<count($fp_contents);$x++){
            $this->Cell($w[$x],6,$fp_contents[$x],'1',0,'L');
        }
        $this->Ln();                        
    }
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

function get_current_users(){
    if(func_num_args()>0):
        $args = func_get_args();
        $start = $args[0];
        $end = $args[1];
        $method = $args[2];
        $brgy = $args[3];
        $col_code = $args[4];
    endif;
    
    switch($col_code){
        
        
        case '2': //this will compute the Current User beginning the Quarter ((NA+Others)-Dropout)prev
            $q_active_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered < '$start' AND method_id='$method'") or die("Cannot query 198: ".mysql_error());
            $q_dropout_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_dropout < '$start' AND drop_out='Y' AND method_id='$method'") or die("Cannot query: 199". mysql_error());
            
            //echo mysql_num_rows($q_active_prev);
            
            $arr_active_prev = $this->sanitize_brgy($q_active_prev,$brgy);
            $arr_dropout_prev = $this->sanitize_brgy($q_dropout_prev,$brgy);
                              
            $cu_prev = count($arr_active_prev)-count($arr_dropout_prev);
            
            //echo $method.'/'.count($arr_active_prev).' less '.count($arr_dropout_prev).'='.$diff."<br>";
            return $cu_prev;            
            break;

    
        case '3':        
            $q_na = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$start' AND '$end' AND client_code='NA' AND method_id='$method'") or die("Cannot query 215 ".mysql_error());
            
            $arr_na_pres = $this->sanitize_brgy($q_na,$brgy);
            
            $cu_na = count($arr_na_pres);
            
            return $cu_na;
            
            break;
        
        case '4': //cu for others
            $q_others = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$start' AND '$end' AND client_code!='NA' AND method_id='$method'") or die("Cannot query 235 ".mysql_error());
            $arr_others = $this->sanitize_brgy($q_others,$brgy);
            $cu_others = count($arr_others);
            return $cu_others;
            
            break;
            
        case '5': //dropouts for a given quarter
        
            $q_dropout = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_dropout BETWEEN '$start' AND '$end' AND drop_out='Y' AND method_id='$method'") or die("Cannot query 240 ".mysql_error());
            $arr_dropout_pres = $this->sanitize_brgy($q_dropout,$brgy);            
            $dropout_count = count($arr_dropout_pres);
            
            return $dropout_count;
            
            break;
        
        
            
        default:
        break;
    
    }
    
        
    
}

function get_cpr(){
    if(func_num_args()>0){
        $args = func_get_args();
        $cu = $args[0];
    }
    $target_pop = 0.85;
    $elig_pop = 0.145; 
 
    if(in_array('all',$_SESSION[brgy])):
        $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("Cannot query 272 ".mysql_error());
    else:
       $str_brgy = implode(',',$_SESSION[brgy]);
       $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]' AND barangay_id IN ($str_brgy)") or die("Cannot query 275 ".mysql_error());
    endif;
    
    
    
        list($tp) = mysql_fetch_array($q_pop);
        $cpr = ($tp!=0)?(($cu/$tp) * $target_pop * $elig_pop * 100):0;
        
                
    return round($cpr,3);
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

$pdf->show_fp_quarterly();

//$pdf->AddPage();
//$pdf->show_fp_summary();
$pdf->Output();

?>
