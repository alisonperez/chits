<?php
//==============================================================================
// Name:	JPLINTPHP.PHP
// Description:	Simple static analysis of a PHP file.
// Created:	01/12/03 
// Author:	johanp@aditus.nu
// Version: 	$Id: jplintphp.php,v 1.10 2002/07/02 22:39:40 aditus Exp $
//
// License:	QPL 1.0
//
// Copyright (C) 2001,2002 Johan Persson
//
// Long Description:
// Parses a correct PHP file for classes and methods and does some rudimentary
// checks and warns for:
// 1) ... unused instance variables exists
// 2) ... possible forgotten $this-> qualifier for access to instance variables
// 
// Please note that the PHP file MUST be syntactically correct since
// the parsing is very simple and can't cope with recovery after syntax errors. 
//==============================================================================


//-------------------------------------------------------------------
// Some testcode to get the ereg expressions correct. Why does this
// always has to be a bloody pain... :-)
//------------------------------------------------------------------------
//$aLine = 'var $txt1 = array(), $txt2 = "" , $txt3 = "kalle" ;';
//$pattern='/^var\s+(\$\w+)?\s*=?(array\(\)|\d+\.\d+|\d+|"\w*")?(,\s*(\$\w+)?\s*=?(array\(\)|\d+\.\d+|\d+|"\w*")?)*/';
//$vdec = '(\$\w+)?\s*=?\s*(array\(\)|\d+\.\d+|\d+|"\w*")?';
//$vlist = "\s*$vdec\s*,?";
//$pattern = "/^var $vlist$vlist$vlist$vlist$vlist;/";
//echo "pattern=$pattern<p>";

/*
if( true ) {
    $aLine   = "function LogAction(&\$aAA, \$aStr=array(0,0,0),\$aLineBreak=true,\$lastarg='x')";
    $argdef  = '\s*(\&?\$\w+)*=?(""|'."''".'|'."'\w+'".'|\d+|\d+\.\d+|\w+|"\w+"|array\(\d*,?\d*,?\d*,?\))?\s*,?';
    //$argdef  = '\s*(\&?\$\w+)*=?(""|\d+|\d+\.\d+|\w+|"\w+"|array\(\d*,?\d*,?\d*,?\))?\s*,?';
    $pattern = '/^function\s+(\w+)\s*\(\s*'.$argdef.$argdef.$argdef.$argdef.$argdef.$argdef.$argdef.$argdef.'\s*\)/i';
    
    $flg=preg_match($pattern,trim($aLine),$matches);
    if( $flg )
    {
	
	$numArgs = ceil((count($matches)-2)/2);
	$fname = $matches[1];
	$args=array();
	$argsval=array();
	for($i=0; $i<$numArgs; ++$i) {
	    $args[$i]=$matches[2+$i*2];
	    if( isset($matches[3+$i*2]) )
		$argsval[$i]=$matches[3+$i*2];
	}
	
	echo "Number of args: ".ceil((count($matches)-1)/2)."<br>";
	for($i=0;$i<count($matches); ++$i)
	    echo "<pre>$i:$matches[$i]</pre>";
    }
    else
	echo "No match found.<br>";
    exit();
}
*/

// Base class for PHP class properties (Class, methods)
class Prop {
    var $iName;
    function Prop($aName) {
	$this->iName = $aName;
    }
    function GetName() {
	return $this->iName;
    }
} 

// Stores properties for a class definition, name, methods, file etc
class ClassProp extends Prop {
    var $iParent;
    var $iFileName;
    var $iLineNbr;
    var $iFuncs,$iFuncNbr=0;
    var $iVars=array(),$iVarNbr=0,$iUsed=array();
	
    function ClassProp($aParent,$aName,$aLineNbr,$aFile) {
	$this->iName = $aName;
	$this->iParent = $aParent;
	$this->iLineNbr = $aLineNbr;
	$this->iFileName = $aFile;                
	$this->iFuncs=array();
	$this->iVars=array();
	$this->iUsed=array();
	$this->iFuncNbr = 0;
    }

    function AddVar($aVar,$aVal="") {
	$this->iVars[$this->iVarNbr] = array($aVar,$aVal);
	$this->iUsed[$this->iVarNbr] = false;
	$this->iVarNbr++;
    }
	
    function IaAllVarsUsed() {
	for($i=0; count($this->iVars); ++$i) 
	    if( !$this->iUsed[$i] )
		return false;
	return true;
    }

    function AddFunc($aFunc) {
    
    // Sanity check. Make sure that a function with this name isn't
    // alrey defined in this class
    $found = false;
    for($i=0; $i<$this->iFuncNbr && !$found; ++$i) {
    	$found = ($aFunc->iName == $this->iFuncs[$i]->iName);
    }
    if( $found ) {
    	echo "<br><font color=red><b>Semantic error in PHP file:</font></b> Function <b>$aFunc->iName</b> is multiple defined in class <b>$this->iName</b>. Skipping.<br>\n";
		return;
    }
    
    	
	$this->iFuncs[$this->iFuncNbr]=$aFunc;
	$this->iFuncNbr++;
    }

    function GetFileName() {
	return $this->iFileName;
    }
	
    function FormatVar($aVar) {
	return "<i>".$aVar."</i>";
    }

    function FormatClass($aClass,$aParent) {
	$res = "<hr>CLASS <b>".$aClass."</b>";
	if( $aParent != "" )
	    $res .= " INHERITS <b>".$aParent."</b>";		
	return $res;
    }
	
    // Some Java style ToString() methods
    function ToString() {
	$res = $this->FormatClass($this->iName,$this->iParent);
	$res .= "(Defined in: ".$this->iFileName.":".$this->iLineNbr.")<br>" ;				
	$res .= "<br><b>VARS</b>";
	for( $i=0; $i<count($this->iVars); ++$i) {
	    $res .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->FormatVar($this->iVars[$i][0]);
	    if($this->iVars[$i][1] != "")
		$res .= " = ".$this->FormatVar($this->iVars[$i][1]);
	    if( !$this->iUsed[$i] )
		$res .= "<font color=\"red\"> ** NOT USED **</font>";
	}
	$res .= "<p><b>METHODS</b><br>";
	for( $i=0; $i<count($this->iFuncs); ++$i) {
	    $res .= $this->iFuncs[$i]->ToString();
	}
	return $res;
    }
	
}

// Stores properties for a class method
class FuncProp extends Prop {
    var $iNumArgs;
    var $iArgs=array(),$iArgsVal=array(), $iArgsDes=array();
    var $iClassName;
    var $iLineNbr;
    var $iFileName;
    var $iShortComment;
	
    function FuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment="",$aFileName="") {
	$this->iName = $aName;
	$this->iClassName = $aClassName;
	$this->iNumArgs = count($aArgs);
	$this->iArgs = $aArgs;
	$this->iArgsVal = $aArgsVal;
	$this->iLineNbr = $aLineNbr;
	$this->iShortComment = $aShortComment;
	$this->iFileName = $aFileName;
    }

// Some Java style ToString() methods
    function ToString() {
	$res = $this->iClassName."::<b>".$this->iName."</b>";
				 
	if( $this->iNumArgs > 0 ) {
	    $res .= "(";
	    for($i=0; $i<$this->iNumArgs; ++$i) {
		if($i!=0) $res .= ", ";
		$res .= "<i>".$this->iArgs[$i];
		if( isset($this->iArgsVal[$i]) && $this->iArgsVal[$i]!="" )
		    $res .= " = ".$this->iArgsVal[$i];
		$res .= "</i>";
	    }
	    $res .= ")";
	}
	else
	    $res .= "()";
	return $res."<br>";
    }	
}

// The actual parser class. very simple. Read all the line
// for a given file and try to figure out if there is a function or
// class definiton on that line.
class Parser {
    var $iClasses=null,$iClassCnt;
    var $iCurrClass=null;
    var $iGlobalFuncs=null;
    var $iBraceCnt=0;
    var $iInComment=0,$iHyphenMarks=0,$iQuoteMarks=0,$iInString=0;
    var $iCurrFileName;
    var $iFp=null, $iCurrFile="";
    var $iPrevLine,$iNextLine;
    var $iWarnings=null;
    var $iCommentBreak=true,$iLastComment="";

    function Parser($aFile) {
	$this->iClasses=array();
	$this->iWarnings = array();
	$this->iClassCnt=0;
	$this->iGlobalFuncs = array();
	$this->iCurrFileName=$aFile;
	$fp = @fopen($aFile,"r");
	if( !$fp ) {
	    die("Parser: Can't open file $aFile");
	}
	$this->iFp = $fp;
	$this->iCurrFile=$aFile;
    }

    function MapClass($aClass) {
	echo $aClass->ToString().'<p>';
    }
	
	function MapGlobalFunc($aFunc) {
		echo $aFunc->ToString().'<p>';
	}
	
	
    function DoMapClasses() {
	for($i=0; $i<count($this->iClasses); ++$i) {
	    $this->MapClass($this->iClasses[$i]);
	}
    }

    function DoMapGlobalFuncs() {
    	$n = count($this->iGlobalFuncs);
    	for( $i=0; $i<$n; ++$i ) {
      		$this->MapGlobalFunc($this->iGlobalFuncs[$i]);
      	}
    }
	
	function StartIndicator($aFilename) {
		echo "<h2>File: $aFilename </h2>\n";
		flush();
	}
	
    function Start() {
	// Read line by line to find each class and all methods
	// defined within that class
	$lnbr=1;
	$this->iPrevLine = "";
	$this->iNextLine = fgets($this->iFp,256);		
	$this->StartIndicator($this->iCurrFileName);
	while( !feof($this->iFp) ) {
	    $buffer = $this->iNextLine;
	    $this->iNextLine = fgets($this->iFp,256);
	    $this->ParseLine($buffer,$lnbr);
	    $this->iPrevLine = $buffer;
	    ++$lnbr;
	}
	$buffer = $this->iNextLine;
	$this->iNextLine="";
	$this->ParseLine($buffer,$lnbr);
    }

    function End() {
	fclose($this->iFp);
    }
	
    function GetWarnings() {
	$res="";
	for($i=0; $i<count($this->iWarnings); ++$i)
	    $res .= $this->iWarnings[$i]."<br>";
	return $res;
    }
	
    function GetUnusedClassVariables() {
	$res = "";
	for($i=0; $i<count($this->iClasses); ++$i) {
	    $var = "";
	    for($j=0; $j<count($this->iClasses[$i]->iVars); ++$j) {
		if( !$this->iClasses[$i]->iUsed[$j] ) {				
		    if( $var != "" ) $var .= ", ";
		    $var .= "<i>".$this->iClasses[$i]->iVars[$j][0]."</i>";
		}
	    }
	    if( $var != "" )
		$res .= "<b>Warning:</b>Unused variables in Class ".$this->iClasses[$i]->GetName()." (".$var.")<br>";
	}
	return $res;
    }

    function CheckUsedVars($aLine,$aLineNbr) {
	$n = count($this->iCurrClass->iVars);
	if( $n==0 )	return;
	$ret=false;
	for( $i=0; $i<$n; ++$i) {
	    $pattern = "/this->".substr($this->iCurrClass->iVars[$i][0],1)."/";
	    $isVarUsed=preg_match($pattern,trim($aLine));

	    $pattern = "/[^>\w]".trim(substr($this->iCurrClass->iVars[$i][0],1))."[^\w]/";
	    $isVarUsedWithoutThis=preg_match($pattern,trim($aLine));

	    if( $isVarUsed ) {
		$ret=true;
		$this->iCurrClass->iUsed[$i]=true;
	    }
	    elseif( $isVarUsedWithoutThis ) {		    
		$this->iWarnings[] = "<b>Warning:</b> Possible use of <b>".$this->iCurrClass->iVars[$i][0]."</b> (Class ".$this->iCurrClass->GetName().") in ".
		     $this->iCurrFileName.":".$aLineNbr." without a '\$this' qualifier.";
	    }
	}
	return $ret;
    }
	
    function ParseClassVars($aLine) {
	// Instance variables in $matches[$i], $i=1,2,3,...
        $vdec = '(\$\w+)?\s*=?\s*(array\(\)|\d+\.\d+|\d+|".*"'."|'.*'".')?';
        $vlist = "\s*$vdec\s*,?";
        $pattern = "/^var $vlist$vlist$vlist$vlist$vlist;/";


	$isVar=preg_match($pattern,trim($aLine),$matches);
	if( !$isVar ) return false;
	$n = ceil((count($matches)-1)/2);
	for($i=0; $i<ceil((count($matches)-1)/2); ++$i) {
	    if( !isset($matches[2+$i*2]) )
		$matches[2+$i*2]="";
	    if( trim($matches[1+$i*2]) == "" ) {
		echo "****DEBUG #$i: line=$aLine<br>m1=".$matches[$i*2]."m2=".$matches[1+$i*2]."m3=".$matches[2+$i*2]."<p>";
            }
	    else
		$this->iCurrClass->AddVar($matches[1+$i*2],$matches[2+$i*2]);
	}
	return true;
    }
	
    // Factory function for classes
    function &NewClassProp($aParent,$aName,$aLineNbr,$aFileName) {
	return new ClassProp($aParent,$aName,$aLineNbr,$aFileName);
    }
	
    // Factory function for methods
    function &NewFuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment) {
	return new FuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment,$this->iCurrFileName);
    }
	
	function LineIndicatorMinor($aLineNbr) {
		echo "..$aLineNbr..";
		flush();
	}

	function LineIndicatorMajor($aLineNbr) {
		echo "<br>\n";
	}

	// Maintain brace count ignoring braces within strings and comments.
	function BraceCount($aLine) {
		$n = strlen($aLine);
		$done = false;
		$prevc='';
		for( $i=0; $i<$n && !$done; ++$i ) {
			$cc = substr($aLine,$i,2);
			$c = substr($cc,0,1);
			if( $prevc != '\\' && $c=='"' && !$this->iHyphenMarks )
				$this->iQuoteMarks = $this->iQuoteMarks ? 0 : 1;
				
			if( $prevc != '\\' && $c=="'" && !$this->iQuoteMarks )
				$this->iHyphenMarks = $this->iHyphenMarks ? 0 : 1;
			
			$this->iInString = $this->iHyphenMarks || $this->iQuoteMarks ? 1 : 0;
				
			if( $cc == '//' && !$this->iInString ) 
				$done=true;
			else {
				if( $cc == '/*' && !$this->iInString ) $this->iInComment = true;
				elseif( $cc == '*/' && !$this->iInString )  $this->iInComment = false;
				elseif( $c == '{' && !$this->iInComment && !$this->iInString ) ++$this->iBraceCnt;
				elseif( $c == '}' && !$this->iInComment && !$this->iInString ) --$this->iBraceCnt;
			}
			$prevc = $c;
		}
		//echo " $this->iBraceCnt ($this->iInComment, $this->iInString) : ".htmlentities($aLine)."<br>\n";
	}	
	
    function ParseLine($aLine,$aLineNbr) {

	    	
    if( $aLineNbr % 50 == 0 )	{
    	$this->LineIndicatorMinor($aLineNbr);
    	if( $aLineNbr % 500 == 0 ) 
    		$this->LineIndicatorMajor($aLineNbr);
    }
    
    
    $aLine = trim($aLine);
    if( $aLine=='' ) return;
    
	$pattern = '/^\s*\/\//';
	if( !$this->iInString && preg_match($pattern,$aLine) ) {
	    if( $this->iCommentBreak ) {
		$this->iLastComment = trim($aLine);
		$this->iCommentBreak = false;
	    }
	    else
		$this->iLastComment .= $aLine;
	    return;		
	}
	else
	    $this->iCommentBreak = true;
			
	if( $this->iBraceCnt < 0 )
	    die("Syntax error in PHP file. Unmatched braces on line $aLineNbr");

	if( $this->iBraceCnt > 0 ) {
	    if( $this->ParseClassVars($aLine) ) {
 		return;
	    }
	    $this->CheckUsedVars($aLine,$aLineNbr);
	}
			
	// Is this a class definition of the form
	// class classname {extends parentclass} \{
	$pattern="/^(class)\s+(\w+)\s*(extends\s+(\w+\s*))?/i";
	//$isClass=preg_match($pattern,trim($aLine),$matches);
	if( !$this->iInString && preg_match($pattern,$aLine,$matches) ) {
	    $name = $matches[2];
	    if( isset($matches[4]) )	// Inheritance?
		$parent = $matches[4];
	    else
		$parent = "";
	    $this->iClasses[$this->iClassCnt] = $this->NewClassProp($parent,$name,$aLineNbr,$this->iCurrFileName);
	    $this->iCurrClass = &$this->iClasses[$this->iClassCnt];
	    $this->iClassCnt++;
	}
	else {	
	    // Look for a function definition with arguments which may have default
	    // values. The pattern below works for up to 10 arguments
	    // $matches[1]=function name
	    // $matches[2+($i)*2]=argument $i name [i=0,1,2,...]
	    // $matches[3+($i)*2]=argument $i value
	    // Number of arguments=ceil((count($matches)-2)/2)
	    // Note: We must use ceil() since if the last argument has no initialization
	    // the last two entries wont exist and we will get a floating point
	    // number back.

	    //$argdef  = '\s*(\&?\$\w+)*=?(""|'."''".'|'."'.+'".'|\d+|\d+\.\d+|\w+|".+"|array\(\d*,?\d*,?\d*,?\))?\s*,?';
	    $argdef  = '\s*(\&?\$\w+)*=?('."'.*'".'|\d+|\d+\.\d+|\w+|".*"|array\(\d*,?\d*,?\d*,?\))?\s*,?';
	    $pattern = '/^function\s+(\w+)\s*\(\s*'.$argdef.$argdef.$argdef.$argdef.$argdef.$argdef.$argdef.$argdef.'\s*\)/i';

	    //$isFunction=preg_match($pattern,trim($aLine),$matches);
	    if( !$this->iInString && preg_match($pattern,$aLine,$matches) ) {
			$numArgs = ceil((count($matches)-2)/2);
			$fname = $matches[1];
			$args=array();
			$argsval=array();
			for($i=0; $i<$numArgs; ++$i) {
			    $args[$i]=$matches[2+$i*2];
		    	if( isset($matches[3+$i*2]) )
				$argsval[$i]=$matches[3+$i*2];
			}
			if( isset($this->iCurrClass) && $this->iCurrClass!=null && $this->iBraceCnt==1 )
		    	$this->iCurrClass->AddFunc($this->NewFuncProp($this->iCurrClass->GetName(),$fname,$aLineNbr,$args,$argsval,$this->iLastComment));
			elseif( $this->iBraceCnt==0 ) {
				// Add a global function
				//$aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment,$aFileName=""
				$this->iGlobalFuncs[] = $this->NewFuncProp('',$fname,$aLineNbr,$args,$argsval,$this->iPrevLine);
			}
			else
			    die("Syntax error in PHP file. Function definition within function. (".$this->iBraceCnt.")");

	        // Clear comment once we used it
    	    $this->iLastComment = "" ; 
	    }
	}
	$this->BraceCount($aLine);
    }	
}


// Class Driver
// Parse a file and get all the functions and classed defined in that
// file. The methods and classes are stored in the properties
// iClasses and iFuncs and are each instances of ClassProp and FuncProp respectively
// To use this class just inherit the class and implement
// your own overloaded version of PostProcessing() (currently it just prints out the
// found methods)
class LintDriver {
    var $iParser,$aFileName;
	
    function Driver($aFile) {
	$this->iParser = $this->NewParser($aFile);
    }
	
    function NewParser($aFile) {
	return new Parser($aFile);
    }
		
    function Run() {
	$this->iParser->Start();
	$this->iParser->End();
	$this->PostProcessing();
    }
	
    function PostProcessing() {
	$this->iParser->DoMapClasses();
	$this->iParser->DoMapGlobalFuncs();		
    }
}

// EOF
?>