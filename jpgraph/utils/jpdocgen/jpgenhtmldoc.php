<?php
//==============================================================================
// Name:        JPGENHTMLDOC.PHP
// Description:	Implements a HTML plugin for the reference framework as 
//              specified in jpclassref.php
// Created: 	2002-04-14 
// Author:	    johanp@aditus.nu
// Version: 	$Id: jpgenhtmldoc.php,v 1.15 2002/06/25 22:40:41 aditus Exp $
//
// License:	    QPL 1.0
// Copyright (C) 2002 Johan Persson
//
//==============================================================================

include "jpclassref.php" ;

DEFINE('GLOBALFUNCS_FILENAME','global_funcs.html');

class FileWriter {
	var $iFP=0;
	var $iFileName;
	
	function Open($aFileName) {
		if( ($this->iFP = @fopen($aFileName,'w')) == false )
			die("<b>File open error:</b> Can't open file '$aFileName'. Check that file and directory exists.");
		$this->iFileName = $aFileName;
	}
	
	function Close() {
		if( $this->iFP )
			fclose($this->iFP);
	}
	
	function W($aStr) {
		if( fwrite($this->iFP,$aStr) == -1 )
			die("Can't write to file : ".$this->iFileName);
		fflush($this->iFP);
	}
	
}


// Basic HTML Class formatter
class ClassHTMLFormatter extends ClassFormatter {
	var $iNumClasses,$iColumnClassCnt,$iGlobalClassCnt,$iNumMethods,$iColumnMethCnt,$iClassMethodCnt;
	var $iCol;
	var $iWriter;
	var $iDirectory = '';
	var $iListCnt=0;
	var $iProjName;
	var $iShowPrivate;
	var $iDocType = 0;	// 0=Framed document, each class one file. 1=Everything in one file
	var $iCSS = 
    	'<style type="text/css">
		<!--
		A:link        {font-family: helvetica, arial, geneva, sans-serif; font-size: x-small; text-decoration: none; color: #0000ff}
		A:visited     {font-family: helvetica, arial, geneva, sans-serif; font-size: x-small; text-decoration: none; color: #0000ff}
		A:hover       {font-family: helvetica, arial, geneva, sans-serif; font-size: x-small; text-decoration: underline; color: #FF0000}
		th            {font-family: helvetica, arial; color : blue; font-size:85%; background : lightgrey; border-right:black solid 1pt; border-bottom:black solid 1pt;}
		//-->
		</style>';	
	
	var $iIndexFramePage =
		'<!doctype html public "-//W3C//DTD HTML 4.0 Frameset//EN">
		<HTML><HEAD>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="de_normal.css">
		<title>Project documentation</title>
		</head>
		<frameset cols="270,*" ">
		<frame src=class_toc.html name=toc>;
		<frame  src=projinfo.html name=classdetail>
		</frameset>
		</html>';
		
	var $iIndexPage = 
		'<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
		<HTML><HEAD>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="de_normal.css">
		<title>Project documentation</title>
		</head>';
	
	function ClassHTMLFormatter($aDBCache,$aFlags="") {
		parent::ClassFormatter($aDBCache,$aFlags="");
		$this->iWriter = new FileWriter();
	}
	
	function Start($aDBServer,$aProjName) {
		$dbutils = new DBUtils($aDBServer);
		$dbutils->SetProject($aProjName);
		$this->iDirectory = $dbutils->GetProjDir($aProjName); 
		$this->iProjName = $aProjName;
		$proj = $dbutils->GetProject();
		$this->iShowPrivate = $dbutils->GetShowPrivate($aProjName);
		$this->iDocType = $proj['fld_doctype'];
		$ds = new DocStat($dbutils);
		list($avg,$nc,$nm) = $ds->ProjStat($aProjName);

		$t  = '<table width=100% border=1 style="background-color:lightblue;"><tr><td align=center><span style="font-size:20pt;font-family:arial;font-weight:bold;">'.$aProjName."</span></td></tr></table>\n";
		$t .= '<div align=center><h3 style="color:darkred;font-family:arial;">Documentation status: '.round($avg*100).'%</h3>';
		$t .= '<span style="font-family:arial;">Total number of Classes: '.$nc.', Methods: '.$nm."</span><p>\n"; 
		if( $this->iShowPrivate )
			$t .= '<i>This version <b>includes</b> private methods & classes</i><p>';
		else
			$t .= '<i>This version does <b>not</b> include private methods & classes</i><p>';
		$t .= '<p><i>Generated at '.strftime('%d %b %Y  at  %H:%M')."</i><br>\n";
		$t .="</div><hr>";
		$t .= "<p>".$proj['fld_desc'];
		
		if( $this->iDocType == 0 ) {
			$dt = 'HTML: Multiple files.';
			$this->iWriter->Open($this->iDirectory.'projinfo.html');					
			$this->iWriter->W( $t );
			$this->iWriter->Close();
		
			$this->iWriter->Open($this->iDirectory.'index.html');					
			$this->iWriter->W( $this->iIndexFramePage );
			$this->iWriter->Close();
		}
		else {
			$dt = 'HTML: Single file.';
			$this->iWriter->Open($this->iDirectory.'index.html');								
			$this->iWriter->W( $this->iIndexPage );
			$this->iWriter->W( $this->iCSS );
			$this->iWriter->W( $t );			
		}
		
		HTMLGenerator::CloseWinButton('left');
		echo "<hr>";
		echo "<font face=arial><b>Generating reference for project : <font color=blue>$aProjName</font></b></font><br>";
		echo "Output directory: <b><font color=blue>".$this->iDirectory.'</font></b><br>';
		echo "Output format: <b><font color=blue>$dt</font></b> <p>\n";
		echo "<hr>";
	}
	
	function End() {
		echo "<h3>Successfully generated all documentation.</h3>";
		echo "<hr>";
		HTMLGenerator::CloseWinButton('left');
	}
	
	function ClassEntry($aClassName) {
		++$this->iListCnt;
		echo "$this->iListCnt. Writing class <b><font color=blue>$aClassName</font></b>...";flush();
		if( $this->iDocType == 0 ) { // Split output
			$this->iWriter->Open($this->iDirectory.$aClassName.'.html');			
			$this->iWriter->W( $this->iCSS );
		}
	}
	
	function ClassExit() {
		echo " done.<br>\n";flush();
		$this->iWriter->W( '<p> <hr> <p>' );
		if( $this->iDocType == 0 ) { // Split output
			$this->iWriter->Close();	
		}
	}
	
	function GlobalFuncEntry($aNumFuncs) {
		echo "<p>Writing <b><font color=blue> $aNumFuncs global functions</font></b>...\n";flush();
		if( $this->iDocType == 0 ) { // Split output
			$this->iWriter->Open($this->iDirectory.GLOBALFUNCS_FILENAME);			
			$this->iWriter->W( $this->iCSS );
		}
		$this->iWriter->W('<div style="background-color:yellow;font-family:arial;font-weight:bold;font-size:150%;">Global functions</div>');
	}
	
    function GlobalFuncExit() {
		echo "<br> done.<br>\n";
		$this->iWriter->W( '<p> &nbsp; <hr> &nbsp; <p>' );
		if( $this->iDocType == 0 ) { // Split output
			$this->iWriter->Close();	
		}
    }

    function FmtClassHierarchySetup($aHier,$aNbr) {
        $this->iWriter->W( "<table border=1>" );
    }

    function FmtClassHierarchyExit($aHier,$aNbr) {
        $this->iWriter->W( "</tr></table>" );
    }

    function FmtClassHierarchyHeaders($aHier,$aNbr) {
        $this->iWriter->W( "<tr>" );
        for( $i=0; $i<$aNbr; ++$i ) {
            $this->iWriter->W( '<td>&nbsp;<a href="'.$aHier[$i].'.html" style="font-family:arial;font-weight:bold;color:darkblue;">'.$aHier[$i]."</a>&nbsp;</td>" );          
        }
        $this->iWriter->W( "</tr><tr>" );
    }
    
    function FmtClassHierarchyColumnSetup($aClassName,$aColNbr) {
        $this->iWriter->W( "<td valign=top>" );
    }

    function FmtClassHierarchyColumnExit($aClassName,$aColNbr) {
        $this->iWriter->W( "</td>" );
    }
    
    function FmtClassHierarchyRow($aClassName,$aMethodName,$aOverridden,$aPublic) {
        if( $aMethodName == "" )
            $this->iWriter->W( "&nbsp;" );
        else {
        	// Markup private methods with a '*'
        	if( !$this->iShowPrivate && !$aPublic )
        		return;
        		
	        if( $aPublic ) 
    	      	$mark='';
        	else
           		$mark='*';
            if($aOverridden) {
               $this->iWriter->W( "<a href=\"$aClassName.html#".
                                  strtoupper("_".$aClassName."_".$aMethodName).
                                  "\" style=\"color:darkgrey;\">$mark".$aMethodName."()&nbsp;</a><br>\n" );
            }
            else { 
            	$this->iWriter->W( "&nbsp;".
                    "<a href=\"$aClassName.html#".strtoupper("_".$aClassName."_".$aMethodName)."\">$mark".
                    $aMethodName."()</a>&nbsp;<br>\n" );
           }
        }        
    }
    
    function FmtClassSetup($aClassInfo) {
        
	    $res = "<hr><a name=\"".strtoupper("_C_".$aClassInfo["fld_name"])."\"><div style=\"background-color:yellow;font-family:courier new;\"></a>";
	    $res .= "CLASS <b>".$aClassInfo["fld_name"]."</b>";
	    if( $aClassInfo["fld_parentname"] != "" )
	        $res .= " EXTENDS <a href=\"".$aClassInfo["fld_parentname"].".html#".strtoupper("_C_".$aClassInfo["fld_parentname"])."\" style=\"font-face:arial;font-weight:bold;\">".$aClassInfo["fld_parentname"]."</a>";
	    $res .= "</div>\n";
	    $res .= "<i>(Defined in: ".$aClassInfo['fld_file']." : $aClassInfo[fld_linenbr])</i>";
        $this->iWriter->W( $res );
    }            
    
    function FmtClassOverview($aClassInfo) {
        $res = "&nbsp;<p><div style=\"font-weight:bold;font-family:arial;font-size:100%;\">Class usage and Overview</div>".$aClassInfo["fld_desc"]." <p> &nbsp;\n";                 
        $this->iWriter->W( $res );
    }
    
    function FmtClassOverviewExit($aClassInfo) {
    	 $this->iWriter->W( '<hr><span style="font-family:arial;font-size:120%;font-weight:bold;">Class Methods</span><hr>' );
    }
    
    function FmtClassRefs($aClassInfo) {
		$MAXREFS = 4;
		$refs = array();
        for( $i=1; $i <= $MAXREFS; ++$i ) {
        	$cname = @$aClassInfo['fld_ref'.$i];
        	if( !empty($cname) && trim($cname) != '' ) {
        		$refs[] = $cname;
        	}
        }        		
        $n = count($refs);
        if( $n==0 ) 
        	return;
        $this->iWriter->W("<div style=\"font-weight:bold;font-family:arial;font-size:85%;\">See also related classes:</div>" ) ;        
        for( $i=0; $i < $n; ++$i ) {        
        	$this->iWriter->W( '<a href="'.$refs[$i].'.html">'.$refs[$i].'</a>' );
 			if( $i < $n-2 ) 
   	  			$this->iWriter->W( ", " );        	        
   	  		elseif( $i==$n-2 )
   	    		$this->iWriter->W( " and " );        	        
   	   	}
   	   	$this->iWriter->W( ' <p> &nbsp;' );
    }
    
    function FmtClassVars($aVars) {
    	$res =  "<table border=0>\n";
	    for($i=0; $i<count($aVars); ++$i) {
	        $res .= "<tr><td valign=top>";
    	    // highlight_string is buggy so we add ';' to be able to parse a 
	        // single variable.
	        $t = $aVars[$i]["fld_name"];
    	    $t = "<?php $t?>";
	        ob_start();
	        highlight_string($t);
    	    $t = ob_get_contents();	
	        ob_end_clean();
	        $t=str_replace('&lt;?php&nbsp;','',$t);
    	    $t=str_replace('?&gt;','',$t);
	        $res .= "<span style=\"font-family:times;font-size:85%;font-weight:bold;\">$t</span>\n";	    
    	    $res .= "</td></tr>\n";
	    }
    	$res .= "</table>\n";
	    $this->iWriter->W( $res );
    }

    function FmtFuncPrototype($aClassName,$aFunc,$aShowFile=false) {        
    	$file = '';
    	if( $aShowFile ) {
    		$file = "<i>($aFunc[fld_file]:$aFunc[fld_linenbr])</i><br>\n";
    	}
    	$t = "function ".$aFunc["fld_name"]."(";
	    for($i=0; $i<$aFunc["fld_numargs"]; ++$i) {
	        if( $i != 0 ) $t .= ",";
    	        $t .= $aFunc["fld_arg".($i+1)];
	    }
	    $t .= ")";
	    $t  = Utils::HighlightCodeSnippet($t);
	    $t  = "<p>&nbsp; <p> &nbsp; <span style='font-size:110%;'><a name=\"".strtoupper("_".$aClassName."_".$aFunc["fld_name"])."\">".$t."</a></span>\n";
	    $t .= "$file\n<span style='font-family:arial;font-size:90%;'><i>".$aFunc["fld_shortdesc"]."</i></span><p>\n";
	    $this->iWriter->W( "<p>\n".$t );
    }
    
    function FmtFuncReturn($aFunc)  {
    	$r = $aFunc["fld_return"];
    	if( trim($r) != '' ) {
			$res = "<br>\n<div style=\"font-weight:bold;font-family:arial;font-size:85%;\">Returns</div>$r<br>\n";
			$this->iWriter->W( $res );            	
		}
    }
    
    // Utility method to highlight a single line
    function _Highlight($aCode) {
        $res = "<?php $aCode?>";
   	    ob_start();
        highlight_string($res);
        $res = ob_get_contents();	
   	    ob_end_clean();
        $res=str_replace('&lt;?php&nbsp;','',$res);
        $res=str_replace('?&gt;','',$res);    
        return $res;
    }
    
    function FmtFuncArgs($aFunc)  {
    	if( $aFunc["fld_numargs"] == 0 ) {
    	    $this->iWriter->W( "<br>\n" );
    	    return;
    	}
    	    	    
	    $res =  "\n<table cellspacing=0 style='border:black solid 1pt;' width=100%>\n";
	    $res .= '<tr><th width=25%>Argument</th><th width=15%>Default</th><th width=60%>Description</th></tr>';
    	for($i=0; $i<$aFunc["fld_numargs"]; ++$i) {
	        $res .= "\n<tr><td style='border-right:black solid 1pt;font-family:courier;font-size:90%;font-weight:bold;'>";
	        // highlight_string is buggy so we add ';' to be able to parse a 
    	    // single variable.
	        $t = $aFunc["fld_arg".($i+1)];
	        $t = $this->_Highlight($t);
    	    $res .= "$t\n";
	        $res .= "</td><td style='border-right:black solid 1pt;font-family:courier;font-size:90%;font-weight:bold;'>";
        	$val = $aFunc["fld_argval".($i+1)];	        
        	if( !isset($val) || strlen(trim($val)) == 0 )
        		$val = '&nbsp;';
        	else
        		$val = $this->_Highlight($val);
        	$res .= $val.'</td><td>'; 
	        $des = $aFunc["fld_argdes".($i+1)];
	        if( empty($des) || strlen(trim($des)) == 0 )
	        	$des = "No description available";
        	$res .= "$des</td>";
        	$res .= "</tr>\n";
	    }
	    $res .= "</table>\n";
	    $this->iWriter->W( $res );
    }
    
    function FmtFuncDesc($aFunc) {
        $res = "\n<div style=\"font-weight:bold;font-family:arial;font-size:85%;\">Description</div>";
        
        if( strlen(trim($aFunc['fld_desc'])) == 0 )
        	$res .= "No description available.";
        else
        	$res .= $aFunc['fld_desc']."&nbsp;<br>\n";	
        	
		$this->iWriter->W( $res );        
    }

    function FmtFuncRef($aRef) {
        $this->iWriter->W(" &nbsp; <div style=\"font-weight:bold;font-family:arial;font-size:85%;\">See also</div>" ) ;
        $m = count($aRef);
        for( $i=0; $i < $m; ++$i ) {
            list($cname,$mname) = $aRef[$i];
            $this->iWriter->W( "<a href=\"$cname.html#".strtoupper("_".$cname."_".$mname)."\">".$cname."::".$mname."</a>" );
            if( $i < $m-2 ) 
                $this->iWriter->W( ", " );
            elseif( $i==$m-2 )
            	$this->iWriter->W( " and " );
        }                  
    }       
    
    function FmtFuncExample($aFunc) {
        if( $aFunc["fld_example"] != "" ) {                
            $this->iWriter->W( "\n<div style=\"font-weight:bold;font-family:arial;font-size:85%;\"><p>Example</div>" ); 
            $this->iWriter->W( Utils::HighlightCodeSnippet($aFunc["fld_example"],false)."<br>\n" );        
        }    
    }  
    
    function FmtIndexSetup($aNumClasses,$aNumMethods, $aNumColumns=3) {

		// Note: In this formatter we ignore the outside specification of
		// columns because we always use 1 column for frames formatting
		// and 3 columns for a single file formatting.

    	if( $this->iDocType == 0 ) {
    		$aNumColumns=1;    		
    		$class_toc_name = 'class_toc.html';
    		$this->iWriter->Open( $this->iDirectory.$class_toc_name );
    	}
    	else {
    		$class_toc_name = 'index.html';
    		$aNumColumns=3;    		
    	}
    		
    	$this->iNumIndexColumns = $aNumColumns;    		
    	//echo "<h4>Creating index (".$this->iDirectory.$class_toc_name.") with $aNumColumns columns...</h4>";    	
	    $this->iWriter->W( $this->iCSS );
    	$this->iWriter->W( "<a href=projinfo.html target=classdetail style='font-size:120%;'>$this->iProjName</a><p>\n" );
    	$this->iWriter->W( "<a href=".GLOBALFUNCS_FILENAME." target=classdetail style='font-size:100%;'>Global functions</a><p>\n" );
    	$this->iNumClasses = $aNumClasses;
	    $this->iNumMethods = $aNumMethods;
    	$this->iCol = 1;
    	$this->iColumnClassCnt = 0;
    	$this->iGlobalClassCnt = 0;
    	$this->iColumnMethCnt = 0;
    	$this->iWriter->W( "<table width=100%><tr><td valign=top width=".round(100/$aNumColumns,0)."%>" );
    }

    function FmtIndexExit() {
		$this->iWriter->W( "</td></tr></table><p>" );   
		if( $this->iDocType == 0 ) {		
			$this->iWriter->Close();
		}
    }
    
    function FmtIndexClass($aClassName) {
    	++$this->iColumnClassCnt;
    	++$this->iGlobalClassCnt;
    	$this->iClassMethodCnt=0;
    	if( ($this->iColumnClassCnt > floor($this->iNumClasses/$this->iNumIndexColumns)) ) { 
    		if( $this->iCol < $this->iNumIndexColumns ) {
    			$this->iWriter->W( "</td><td valign=top width=".round(100/$this->iNumIndexColumns,0)."%>" );
    			$this->iColumnClassCnt = 0;
    			$this->iColumnMethCnt = 0;
    		}
		}
		$target = ' target=classdetail';
		$this->iWriter->W( "<b><font face='arial'>$this->iGlobalClassCnt.</font> <a href=\"$aClassName.html#".strtoupper("_C_$aClassName")."\" $target>$aClassName</a></b><br>\n" );    	
   	}
   	
   	function FmtIndexMethod($aClassName,$aMethodName) {
   		++$this->iColumnMethCnt;
   		++$this->iClassMethodCnt;
   		$target = ' target=classdetail';
   		$this->iWriter->W( "&nbsp;&nbsp;&nbsp;<span style='font-family:arial;font-size:x-small;'> $this->iGlobalClassCnt.$this->iClassMethodCnt</span> <a href=\"$aClassName.html#".strtoupper("_".$aClassName."_".$aMethodName)."\" $target>$aMethodName</a><br>\n" );
    }
}


class HTMLDriver extends ClassRefDriver {
    // Factory function
    function NewClassFormatter($aDBCache,$aFlags) {
        return new ClassHTMLFormatter($aDBCache,$aFlags);
    }
}


//==========================================================================
// Script entry point
// Read URL argument and create Driver
//==========================================================================
if( !isset($HTTP_GET_VARS['class']) )
    $class = "";
else
    $class = urldecode($HTTP_GET_VARS['class']);
    
$driver = new HTMLDriver();
$driver->Run($class,FMT_CLASSVARS);
exit();

?>