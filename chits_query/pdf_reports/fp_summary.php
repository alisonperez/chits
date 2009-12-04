<?php

session_start();

ob_start();

require('./fpdf/fpdf.php');
include('./util.php');

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

	$m1 = explode('/',$_SESSION[sdate_orig]);
	$m2 = explode('/',$_SESSION[edate_orig]);
	
	$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];

	$municipality_label = $_SESSION[datanode][name];
	
	$this->SetFont('Arial','B',12);


	$this->Cell(0,5,'Family Planning Summary Table ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
	
	if(in_array('all',$_SESSION[brgy])):
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

	$this->SetFont('Arial','',10);
	
	$this->Cell(0,5,$brgy_label,0,1,'C');		
	$this->Ln(10);
	//$w = array(30,18,18,18,18,15,18,18,18,15,18,18,18,15,18,18,18,15,18); //340
	$w = array(66,18,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15); //340
	$header = array('INDICATORS','Target','JAN','FEB','MAR','1st Q','APR','MAY','JUNE','2nd Q','JULY','AUG','SEPT','3rd Q','OCT','NOV','DEC','4th Q','TOTAL');	
		
	$this->SetWidths($w);
	$this->Row($header);		
}


function show_fp_summary(){
	$arr_methods = array();

	
	$brgy_pop = $this->get_brgy_pop();    //compute for the brgy population: ALL or specific brgys only
        $target_pop = $this->get_target($brgy_pop); //compute for the target of FP        
        $str_brgy = $this->get_brgy(); //return list of barangays in CSV format
        $i = 0;
        
	
	$q_methods = mysql_query("SELECT method_id,method_name FROM m_lib_fp_methods ORDER by report_order ASC") or die("Cannot query: 174".mysql_error());        
        
	while(list($method_id,$method_name)=mysql_fetch_array($q_methods)){  //push the method_id as index and method_name as the array contents
		$arr_methods[$method_id] = $method_name;		
		//$arr_methods[$method_id] = $method_id;		
	}	
	
	$arr_indicators = array('NA'=>array('Total New Acceptors',$arr_methods),'OTHER'=>array('Other Acceptors',$arr_methods),'DROPOUT'=>array('Total Drop Out',$arr_methods),'CU'=>array('Total Current Users',$arr_methods));
	
	
	
	foreach($arr_indicators as $client_type=>$methods){				
	        
    	        foreach($methods as $key=>$value){
                
                $arr_row = array();
                $arr_label = array();
                
                                
	        if(is_array($value)):  //this is the second index of the main array. this is the array of methods
	            foreach($value as $method_key=>$method_value){
	                //echo $methods.'/'.$value.'/'.$method_key.'/'.$method_value.'<br>';
	                $arr_method_label = array(); //clean up the label array for each iteration in the method
	                array_push($arr_method_label,$method_value,''); //push the label for method and a '' for blank target	                
                        $arr_row = $this->compute_indicator($client_type,$method_key);
                        $arr_total_quarter = $this->create_qt_gt($arr_row);
                        
                        //print_r($arr_method_label);
                        $this->Row(array_merge($arr_method_label,$arr_total_quarter));
                    }
                    $this->Row($this->return_blank(19));   //this will print a row with 19 blank cells
                    
	        else: //this is the first index, just a text/header but will contain the total of all methods
	            $this->Row($this->return_blank(19));
	            $i += 1;
	            array_push($arr_label,$i.'. '.$value);
                    $arr_row = $this->compute_indicator($client_type,'all');
                    array_push($arr_label,$target_pop);
                    $arr_total_quarter = $this->create_qt_gt($arr_row);
                    $this->Row(array_merge($arr_label,$arr_total_quarter));
                    $this->Row($this->return_blank(19));
	        endif;	        
	        
		//array_push($arr_rows,$arr_indicators[$client_type][0]);
					
		}
			
						
	}	
}


function compute_indicator(){   //accepts two parameters. 1st is (NA, OTHERS, DROPOUT, CU). 2nd is (method type including all). returns an array
    if(func_num_args()>0):      // of size 16 (4 QTRS + 12 MOS)
        $arg_list = func_get_args();
        $cat = $arg_list[0];
        $method = $arg_list[1];                
    endif;
 
    $str_brgy = $this->get_brgy();
        
    //initialize the counter for months    
    for($i=1;$i<13;$i++){
        $month_stat[$i] = 0;
    }

    
    switch($cat){
        
        case 'NA':
        
            if($method=='all'):        
                $q_methods = mysql_query("SELECT a.fp_px_id, a.date_registered,a.patient_id FROM m_patient_fp_method a WHERE a.client_code='NA' AND a.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 226: ".mysql_error());                
            else:
                $q_methods = mysql_query("SELECT a.fp_px_id, a.date_registered,a.patient_id FROM m_patient_fp_method a WHERE a.client_code='NA' AND a.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.method_id='$method'") or die("Cannot query 233: ".mysql_error());
            endif;
            break;
        
        case 'OTHER':
               
            if($method=='all'):
                $q_methods = mysql_query("SELECT a.fp_px_id, a.date_registered,a.patient_id FROM m_patient_fp_method a WHERE a.client_code!='NA' AND a.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 242: ".mysql_error());
            else:
                $q_methods = mysql_query("SELECT a.fp_px_id, a.date_registered,a.patient_id FROM m_patient_fp_method a WHERE a.client_code!='NA' AND a.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.method_id='$method'") or die("Cannot query 244: ".mysql_error());
            endif;        
            break;
            
        case 'DROPOUT':
            if($method=='all'):
                $q_methods = mysql_query("SELECT a.fp_px_id,a.date_dropout, a.patient_id FROM m_patient_fp_method a WHERE a.drop_out='Y' AND a.date_dropout BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.date_dropout ASC") or die("Cannot query 250: ". mysql_error());
            else:
                $q_methods = mysql_query("SELECT a.fp_px_id,a.date_dropout, a.patient_id FROM m_patient_fp_method a WHERE a.drop_out='Y' AND a.date_dropout BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.method_id='$method_id' ORDER by a.date_dropout ASC") or die("Cannot query 252: ". mysql_error());
            endif;
            
            break;
            
        case 'CU': //use formula (CU (prev period) + (NA + CS + CM + RS)) - DROP OUT = CU (current period)
                
            break;
            
        default:
        
            break;
    
    }
    
    if(isset($q_methods)):
        //echo $cat.'/'.$method.'/'.mysql_num_rows($q_methods).'<br>';
        
        while(list($fp_px_id,$date,$px_id)=mysql_fetch_array($q_methods)){
            //echo $cat.'/ '.$date.'<br>';
            if($this->get_px_brgy($px_id,$str_brgy)){
                $month_stat[$this->get_max_month($date)] += 1;
            }        
        }
        
    endif;
     
     //print_r($month_stat);
     
     return $month_stat;
}




function get_target($brgy_pop){
	return round($brgy_pop * 0.85 * 0.145); //FP Target = total population X 14.5 X 85%
}

function get_brgy_pop(){
        list($taon,$buwan,$araw) = explode('-',$_SESSION[edate2]);
        if(in_array('all',$_SESSION[brgy])):
                $q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon'") or die("Cannot query: 206");
        else:
                $str = implode(',',$_SESSION[brgy]);
                $q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon' AND barangay_id IN ($str)") or die("Cannot query: 209");
        
        endif;  

        if(mysql_num_rows($q_brgy_pop)!=0):
                list($populasyon) = mysql_fetch_array($q_brgy_pop);
        endif;          
        
        return $populasyon;
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

function create_qt_gt($arr_in_months){  //this function receives an array representing totals in 12 months. transform the array by inserting
    				     // quarterly and grand totals
    $arr_qt_gt = array();
    $gt = 0;
    for($i=1;$i<=count($arr_in_months);$i++){
        if(($i % 3)==0):
            array_push($arr_qt_gt,$arr_in_months[$i]);
            $qtrly_total = $arr_in_months[$i] + $arr_in_months[$i-1] + $arr_in_months[$i-2];
            array_push($arr_qt_gt,$qtrly_total);
        else:
            array_push($arr_qt_gt,$arr_in_months[$i]);
        endif;
        
        $gt = $gt + $arr_in_months[$i];
    }
    
    array_push($arr_qt_gt,$gt);
    
    return $arr_qt_gt;   
}

function return_blank($cells){
	$arr_blank = array();
	for($i=0;$i<$cells;$i++){
		array_push($arr_blank,'');
	}
	
	return $arr_blank;
}

function get_px_brgy(){

	if(func_num_args()>0):
		$arg_list = func_get_args();
		$pxid = $arg_list[0];
		$str = $arg_list[1];
	endif;
		
	$q_px = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id IN ($str)") or die("cannot query 333: ".mysql_error());
		
	if(mysql_num_rows($q_px)!=0):
		return 1;
	else:
		return ;
	endif;

}


function get_max_month($date){
        list($taon,$buwan,$araw) = explode('-',$date);
        $max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

        return $max_date;
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
$util = new util();

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);

$pdf->AddPage();


//$pdf->AddPage();
$pdf->show_fp_summary();
$alison = $pdf->Output();

?>
