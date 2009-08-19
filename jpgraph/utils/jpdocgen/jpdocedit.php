<?php
//=======================================================================
// File:		JPDOCEDIT.PHP
// Description:	Main entry for editing information in the doc DB
// Created:		2002-04-15
// Author:		Johan Persson (johanp@aditus.nu)
// Ver: 		$Id: jpdocedit.php,v 1.19 2002/07/03 23:32:12 aditus Exp $
//
// License: QPL 1.0
// Copyright (C) 2002 Johan Persson
//=======================================================================

include_once "jpdb.php";
include "de_utils.php";
include 'jpgendb.php';

class IndexPage {
	var $iDBUtils;
	
	function IndexPage($aDBUtils) {
		$this->iDBUtils = $aDBUtils;		
	}

	function Run($aProjidx,$aExpandMethods=false,$aCols=3,$aProjName='') {
		$cl = array();
		$this->iDBUtils->GetClassListNameKeyPublic($cl);
		$nc = (count($cl)-3)/3;
		$ds = new DocStat($this->iDBUtils);
		$total = 0;
		$ct = array();
		$npercol = round($nc / $aCols);
		$limit = 0;
		$start = 1;
		for( $c=0; $c < $aCols; ++$c ) {			
			$limit = $c==($aCols-1) ? $nc : $limit+$npercol;
			$t = '';
			$marker1 = '<span style="font-family:arial;font-size:110%;font-weight:bold;color:darkblue;">[</span>';
			$marker2 = '<span style="font-family:arial;font-size:110%;font-weight:bold;color:darkblue;">]</span>';
			
			for( $i = $start; $i <= $limit; ++$i ) {
				list($cname,$nm,$ma,$tp,$ap) = $ds->ClassStat($cl[3*$i+1]);
				$classpublic = $cl[3*$i+2];
				$mk1=''; $mk2='';
				if( $classpublic == 0 ) {
					$mk1 = $marker1;
					$mk2 = $marker2;
				}
				
				$p = round(100*$ap/$tp);
				$total += $p;
				$t .= "<tr><td style=\"font-family:arial;font-size:80%;\"><b>$i. <font color=red><b>[".sprintf("%3d",$p)."%]</b> </font>$mk1<a href=\"javascript:openPopup('jpd_editclass.php?key=".$cl[3*$i+1]."',550,590);\">$cname</a>$mk2</b></td></tr>\n";
				if( $aExpandMethods ) {
					for( $j=0; $j < $nm; ++$j ) {
						$mk1=''; $mk2='';
						if( $ma[$j][0]==0  ) {
							$mk1 = $marker1;
							$mk2 = $marker2;
						}
						// Adjust the title a little bit to make the columns nicer
						$l = $j+1 < 10 ? ($j+1).'&nbsp;' : ''.$j+1;	
						$t .= "<tr><td style=\"font-family:arial;font-size:80%;\">&nbsp;&nbsp; $i.".$l.' <font face=courier color=darkred>['.sprintf("%3d",$ma[$j][3])."%]</font> $mk1<a href=\"javascript:openPopup('jpd_editmethod.php?key=".$ma[$j][2]."',560,670);\">".$ma[$j][1]."$mk2</a></td></tr>";
					}
				}
			}
			$start += $npercol;
			$ct[] = "<table width=\"100%\" border=0 cellpadding=4>\n$t</table>" ;			
		}
		
		// Get all global functions
		list($cname,$nm,$ma,$tp,$ap) = $ds->ClassStat(0);
		$gfuncs=array();
		$res=$this->iDBUtils->GetMethodListForClassKey($gfuncs,0);
		$n = count($gfuncs)/2;
		$gf='';
		if( $n > 0 ) {
			$gf='<p> <table border=0><tr><td style="font-family:arial;font-weight:bold;font-size:90%;">Global functions</td></tr>';
			for( $i=0; $i<$n; ++$i ) {
				$mk1=''; $mk2='';
				if( $ma[$i][0]==0  ) {
					$mk1 = $marker1;
					$mk2 = $marker2;
				}
								
				$gf .= "<tr><td style=\"font-family:arial;font-size:80%;\">&nbsp;&nbsp; ".($i+1).". <a href=\"javascript:openPopup('jpd_editmethod.php?key=".$ma[$i][2]."',560,670);\"><font face=courier color=darkred>[".sprintf("%3d",$ma[$i][3]).'%] </font>'.$mk1.$ma[$i][1]."$mk2</a></td></tr>";
			}
			$gf .= '</table>';
		}
		

		if( $nc > 0 )
			$avg = round($total/$nc);
		else
			$avg = 0;
		$w = round(100/$aCols);
		

		$t = "<form name='mainform' method=post action=''>";
		$t .= "<table border=0 cellspacing=0 width=100%><tr><td  style='border-bottom:solid black 1pt;' valign=top>";				
		$projdoc = $this->iDBUtils->GetProjDir($aProjName).'index.html';
		
		$t .= "\n<input name=\"button_gendoc\" type=button value=' Create doc ' onclick=\"openPopup('jpgenhtmldoc.php',400,500,'Update docs');\"> ";					
		$t .= "\n<input name=\"button_opendoc\" type=button value=\" Open doc \" onclick=\"openPopup('$projdoc',800,500,'Documentation:$aProjName');\">";
		$t .= "</td><td  style='border-bottom:solid black 1pt;'>\n<input name=\"button_regen\" type=button value=\" Update DB \" onclick=\"openPopup('jpgendbdriver.php?force='+mainform.chkbox_timestamp.checked,500,350,'Update DB');\"> ";
		$t .= "\n<input name=\"chkbox_timestamp\" type=checkbox value=1> Force &nbsp;";
							
		$t .= "\n</td><td  style='border-bottom:solid black 1pt;' valign=top align=right>";		
		$t .= "<input type=button value=' Close ' onclick='window.close();'>";				
		$t .= "</td></tr></table></form>";
		$t .= "\n<table width=100% cellpadding=5 cellspacing=0 class=projectindex>\n" ;
		$t .= "<tr><td colspan=$aCols class=projindexheader>$aProjName</td></tr>";
	 	$t .= "<tr><td colspan=$aCols style=\"background:lightgrey;font-family:arial;color:#400080;\">";
	 	$t .= "<b>Documentation status: <span style=\"color:#B01400;\">$avg %</span></b> ($nc classes)</td></tr>\n";
	 	$tt = '<tr>';
	 	$tt .= "<td width=".$w."% valign=top >$ct[0]</td>";

	 	for( $i=1; $i < $aCols; ++$i ) {
	 		$tt .= "<td width=".$w."% valign=top style='border-left: black solid 0.5pt;' >$ct[$i]";
	 		if( $i < $aCols-1 )
	 			$tt .= "</td>";
	 		else 
	 			$tt .= "$gf</td>";
	 	}	 	
	 	return $t.$tt.'</table>';
	}	
}


class DocEditMainEntry {
	var $iDBUtils;
	
	function DocEditMainEntry($aDBUtils) {
		$this->iDBUtils = $aDBUtils;
	}
	
	function StrokeMeny($aProjname,$aProjidx) {
		
		if( !$this->iDBUtils->ExistTableProjects() ) {
		
			echo( "First time for this database. Initializing... " );
			$this->iDBUtils->SetupNewDB();
			echo( "done.");
		}
				
		$pl = array(' -- Select project -- ',-1);
		$this->iDBUtils->GetProjects($pl);
		$aHTML = new HTMLGenerator();
				
		$t = "<form name='mainform' method=POST action=''>";
		$t .= "<div align=center><table width=100% border=0 cellspacing=0 cellpadding=5 class='mainmeny'>";
		$t .= '<tr><td colspan=2 class=mainmeny><span class=menytitle>Project meny</span></td></tr>';
		$t .= "<tr><td>".$aHTML->SelectCode('s1_projidx',$pl,$aProjidx,1,'onchange=setprojcookie();');
		$t .= "</td><td align=right><input name=\"button_showidx\" type=button value=\"Show\" onclick=\"openPopup('jpdocedit.php?button_showidx=1&showmethod=1',700,450,'DDDA Project:');\"> ";
		$t .= "<input type=button value=\"Edit project\" onclick=\"openPopup('jpd_editproject.php',520,540,'Edit current project');\"></form>";
		$t .= "</td></tr>";				
		$t .= "<tr><td><form name='mainform' method=POST action=''><input type=button value=\"New project\" onclick=\"openPopup('jpd_editproject.php?new=1',470,540,'New project');\">";
		$t .= "</td><td align=right><input type=button value=\"DB Check\" onclick=\"openPopup('ddda_chkdb.php?idx='+mainform.s1_projidx.value,450,450,'DB Check');\">";
		$t .= "</form></td></tr>";
		$t .= "</table></div>";
		
		echo $t;
	}	
}


class DocEdDriver extends DocEditDriver {
	
	function PreAmble($aTitle,$aDesc) {
		HTMLGenerator::DocHeader($aTitle,$aDesc);
		HTMLGenerator::DocPreamble();
		GenJavascript::Stroke();		
	}
	
	function Run() {
		global $HTTP_POST_VARS;
		global $HTTP_GET_VARS;
				
		$button_toggle = @$HTTP_POST_VARS['button_toggle'];		
		$button_showidx = @$HTTP_GET_VARS['button_showidx'];
		$button_regen  = @$HTTP_POST_VARS['button_regen'];
		$regen_projidx = @$HTTP_POST_VARS['regen_projidx'];		
		$showmethod    = @$HTTP_GET_VARS['showmethod'] ;
				
		if((!empty($button_toggle) || !empty($button_showidx)) && !empty($this->iProjidx) && $this->iProjidx > 0) {
			$this->Preamble('DDDA Project: '.$this->iProjname,'DDDA Project: '.$this->iProjname);
			$ip = new IndexPage($this->iDBUtils);
			$txt = $ip->Run($this->iProjidx,$showmethod,3,$this->iProjname);
			echo $txt;
		}
		else {				
			$this->Preamble(' DDDA Main Meny ',' DDDA Main Meny '.$this->iProjname);			
			$m = new DocEditMainEntry($this->iDBUtils);
			$m->StrokeMeny($this->iProjname,$this->iProjidx);
		}
	}
}

$driver = new DocEdDriver();
$driver->Run();
$driver->Close();

?>