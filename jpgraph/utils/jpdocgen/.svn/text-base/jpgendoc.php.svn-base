<?php
//==============================================================================
// Name: 	JPGENDOC.PHP
// Description:	Use parsing classes to generate a documentation skeleton
// Created: 	01/12/03 
// Author:	johanp@aditus.nu
// Version: 	$Id: jpgendoc.php,v 1.1 2002/04/20 19:31:32 aditus Exp $
//
// License:	QPL 1.0
// Copyright (C) 2001,2002 Johan Persson
//==============================================================================
include("jplintphp.php");

// Utility function to highlight a snippet of PHP code
function HighlightCodeSnippet($t,$bg=true) {
    $t = "<?php $t?>";
    ob_start();
    highlight_string($t);
    $t = ob_get_contents();	
    ob_end_clean();
    $t=str_replace('&lt;?php&nbsp;','',$t);
    $t=str_replace('?&gt;','',$t);
    if( $bg ) {
	$t = "<div style=\"background-color:#E6E6E6;font-family:courier new;font-size:85%;font-weight:bold;\"><b>$t</b></div>";
    }
    else {
	$t = "<span style=\"font-family:courier;font-size:85%;font-weight:bold;\">$t</span>";
    }
    return $t;
}


// Formatting class for Classes
class DocClassProp extends ClassProp {
    var $aB,$aC;
	
    function DocClassProp($aParent,$aName,$aLineNbr,$aFile) {	
	parent::ClassProp($aParent,$aName,$aLineNbr,$aFile);
    }
		
    function FormatVar($aVar) {
	//return $aVar;
	return HighlightCodeSnippet($aVar,false)."; ";
    }
	
    function FormatClass($aClass,$aParent) {
	$res = "<div style=\"background-color:yellow;font-family:courier new;\">";
	$res .= "CLASS <b>".$aClass."</b>";
	if( $aParent != "" )
	    $res .= " EXTENDS <b><i>$aParent</i></b>";
	$res .= "</div>\n";
	return $res;
    }	

    function PrettyPrintVars() {
	$res =  "<table border=0>\n";
	for($i=0; $i<count($this->iVars); ++$i) {
	    $res .= "<tr><td valign=top>";
	    // highlight_string is buggy so we add ';' to be able to parse a 
	    // single variable.
	    $t = $this->iVars[$i];
	    $t = "<?php $t?>";
	    ob_start();
	    highlight_string($t);
	    $t = ob_get_contents();	
	    ob_end_clean();
	    $t=str_replace('&lt;?php&nbsp;','',$t);
	    $t=str_replace('?&gt;','',$t);
	    $res .= "<span style=\"font-family:times;font-size:85%;font-weight:bold;\">$t</span>\n";
	    //$res .= "</td><td valign=top>&nbsp;</td><td>".$this->[$i+1];
	    $res .= "</td></tr>\n";
	}
	$res .= "</table>\n";
	return $res;
    }
}

// Formatting class for Methods
class DocFuncProp extends FuncProp {
    function DocFuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment) {
	parent::FuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment);
    }

    function ToString() {
	$res = $this->PrettyPrintFunc();
	$res .= $this->PrettyPrintArgs();
	$res .= "<b>Returns:</b>\n";
	$res .= "<br><b>Description:</b><br>\n".str_replace('//','',$this->iShortComment);
	$res .= "<br><b>Also see:</b>\n";
	$res .= "<br><b>Example:</b><br>&nbsp;\n";
	return  $res;
    }
	
    function PrettyPrintFunc() {
	$t="function ".$this->GetName()."(";
	for($i=0; $i<count($this->iArgs); ++$i) {
	    if( $i != 0 ) $t .= ",";
	    $t .= $this->iArgs[$i];
	}
	$t .= ")";
	return HighlightCodeSnippet($t);
    }
	
    function PrettyPrintArgs()  {
	if( count($this->iArgs) == 0 ) 
	    return "<br>\n";
	$res =  "<table border=0>\n";
	for($i=0; $i<count($this->iArgs); ++$i) {
	    $res .= "<tr><td valign=top>";
	    // highlight_string is buggy so we add ';' to be able to parse a 
	    // single variable.
	    $t = $this->iArgs[$i];
	    $t = "<?php $t?>";
	    ob_start();
	    highlight_string($t);
	    $t = ob_get_contents();	
	    ob_end_clean();
	    $t=str_replace('&lt;?php&nbsp;','',$t);
	    $t=str_replace('?&gt;','',$t);
	    $res .= "<span style=\"font-family:times;font-size:85%;font-weight:bold;\">$t</span>\n";
	    $res .= "</td><td valign=top>&nbsp;</td><td>XXXX</td></tr>\n";
	}
	$res .= "</table>\n";
	return $res;
    }
}

// Parser
class DocParser extends Parser {
    function DocParser($aFile) {
	parent::Parser($aFile);
    }
    // Factory function for classes
    function NewClassProp($aParent,$aName,$aLineNbr,$aFileName) {
	return new DocClassProp($aParent,$aName,$aLineNbr,$aFileName);
    }
    // Factory function for methods
    function NewFuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment) {
	return new DocFuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment);
    }
    // Map function for methods
    function MapFunc(&$aFunc) {
	parent::MapFunc($aFunc);
    }
    // map function for classes
    function MapClass(&$aClass) {
	parent::MapClass($aClass);
    }
}


// Driver
class DocDriver extends Driver {
    function DocDriver($aFile) {
	parent::Driver($aFile);
    }
	
    function NewParser($aFile) {
	return new DocParser($aFile);
    }
	
    function PostProcessing() {
	parent::PostProcessing();
	$res = $this->iParser->GetUnusedClassVariables();
	if( trim($res!="") )
	    echo "<hr><h3>SUMMARY of unused instance variables</h3>$res";		
	$res = $this->iParser->GetWarnings();
	if( trim($res!="") )
	    echo "<hr><h3>SUMMARY of warnings</h3>$res";
    }		
}


//==========================================================================
// Script entry point
// Read URL argument and create Driver
//==========================================================================
if( !isset($HTTP_GET_VARS['target']) )
die("<b>No file specified.</b> Use 'mylintphp.php?target=file_name'" );	
$file = urldecode($HTTP_GET_VARS['target']);
$driver = new DocDriver($file);
$driver->Run();




?>