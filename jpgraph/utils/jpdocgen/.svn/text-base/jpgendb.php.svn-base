<?php
//==============================================================================
// Name:        JPGENDB.PHP
// Description:	Use parsing classes to generate a documentation skeleton
// Created: 	01/12/03 
// Author:		johanp@aditus.nu
// Version: 	$Id: jpgendb.php,v 1.14 2002/06/25 22:40:40 aditus Exp $
//
// License:	QPL 1.0
// Copyright (C) 2002 Johan Persson
//
//==============================================================================
include_once("jplintphp.php");
include_once("jpdb.php");
include_once("de_utils.php");

class Log {
    var $iDisplayLog=false;  
      
    // Used to log action
    function ToScreen($aStr,$aLineBreak=true) {
    	if( $this->iDisplayLog ) {
	        echo "<font color=#FF0000>";
	        echo $aStr;
    	    echo "</font>";
	        if( $aLineBreak ) 
		        echo "<br>\n";
    	}
    }
}

$gLogger = new Log();

class DBFuncProp extends FuncProp {
    var $iKey="";
    var $iDesc="";
    var $iClassIdx="";
    var $iMethodRef=array();
    var $iArgsDes=array();
    var $iExample="";
    var $iProjname;
    var $iPublic;

    function DBFuncProp($aProjname,$aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortDesc="",$aFileName="") {
	    parent::FuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortDesc,$aFileName);
	    $this->iProjname = $aProjname;
	    $this->iPublic = 1; // Default to public
    }

    function Internalize($aDBRow) {
    }

    function Externalize($aDB,$aClassIdx="",$aKey="") {   	
	    $this->iClassIdx = $aClassIdx;
	    $q = "REPLACE INTO tbl_method_".$this->iProjname." SET ";
	    $q .= "fld_key='".$aKey."',";
	    $q .= "fld_name='".$this->iName."',";
	    $q .= "fld_public=".$this->iPublic.",";
	    $q .= "fld_linenbr='".$this->iLineNbr."',";
	    $q .= "fld_file='".mysql_escape_string($this->iFileName)."',";
	    $q .= "fld_shortdesc='".mysql_escape_string($this->iShortComment)."',";
	    $q .= "fld_classidx='".$aClassIdx."',";
	    $q .= "fld_classname='".$this->iClassName."',";
    	$q .= "fld_desc='".mysql_escape_string($this->iDesc)."',";
    	$q .= "fld_example='".mysql_escape_string($this->iExample)."',";
	
    	for( $i=1; $i<=4; ++$i ) {
	        $mref = '';
	        if( !empty($this->iMethodRef[$i-1]) )
        		$mref = $this->iMethodRef[$i-1];
	        $q .= "fld_methref$i='".$mref."',";
	    }
    	$q .= "fld_numargs=".$this->iNumArgs;
    	for( $i=1; $i<=$this->iNumArgs; ++$i ) {
	        $q .= ",fld_arg$i='".$this->iArgs[$i-1]."'"; 
    	}
	    for( $i=1; $i<=$this->iNumArgs; ++$i ) {
	        if( empty($this->iArgsDes[$i-1]) )
		        $argdes="";
	        else
		        $argdes = $this->iArgsDes[$i-1];
	        $q .= ",fld_argdes$i='".mysql_escape_string($argdes)."'"; 
	    }
	    
	    for( $i=1; $i<=$this->iNumArgs; ++$i ) {
	        if( !isset($this->iArgsVal[$i-1]) )
		        $argval='';
	        else
		        $argval = $this->iArgsVal[$i-1];
	        $q .= ",fld_argval$i='".mysql_escape_string($argval)."'"; 
	    }
	    
	    $aDB->query($q);
	    $this->iKey = $aDB->LastIdx();
	    return $this->iKey;
    }

    function UpdateFromExisting($aOldFunc) {
	    // Sanity check
    	if( $this->iName != $aOldFunc["fld_name"] )
	        die("PANIC: UpdateArguments() Function name different.".$this->iName." != ". $aOldFunc['fld_name'] );

	    $numoldargs=$aOldFunc["fld_numargs"];
    	$numnewargs=$this->iNumArgs;

	    // Get the old descriptions and references
	    	    
    	$this->iDesc = empty($aOldFunc['fld_desc']) ? '' : $aOldFunc['fld_desc'];
	    $this->iShortComment = empty($aOldFunc["fld_shortdesc"]) ? '' : $aOldFunc['fld_shortdesc'];
    	$this->iExample = empty($aOldFunc['fld_example']) ? '' : $aOldFunc['fld_example'];
    	$this->iPublic = $aOldFunc['fld_public'];

	    for( $i=0; $i < 4 ; ++$i) {
	        $this->iMethodRef[$i] = @$aOldFunc["fld_methref".($i+1)];
	    }

    	// DB Optimization. If old args are the same as new then don't
	    // bother touching DB.
	    for($i=0; $i<$numoldargs; ++$i) {
	        $exists[$i] = false;
    	}
    	
	    for($i=1; $i<=$numoldargs; ++$i) {
	        $arg = $aOldFunc["fld_arg$i"];
	        for( $j=0; $j<$numnewargs; ++$j) {
		        if( $this->iArgs[$j] == $arg ) {
		            $exists[$i-1] = true;
		            $this->iArgsDes[$j] = $aOldFunc["fld_argdes$i"];
		        }
	        }
	    }
	    
	    if( $numoldargs == $numnewargs ) {
	        $allsame=true;
	        for( $i=0; $i<$numoldargs; ++$i) {
		        if( !$exists[$i] ) 
		            $allsame=false;
	        }
	        
	        // Check if any default value have changed
	        if( $allsame ) {
		        for( $i=1; $i<=$numoldargs && $allsame; ++$i) {
					if( isset($this->iArgsVal[$i-1]) && $this->iArgsVal[$i-1] != $aOldFunc["fld_argval$i"] ) 
						$allsame = false;
	    	    }
	        }
	        
	        if( $allsame && ($aOldFunc["fld_linenbr"]==$this->iLineNbr) ) { 
	            return false;
	        }
	    }
	
    	// Create the new set of arguments by combining the old existing
	    // one that is the same with the new one. 
	    for( $i=0; $i<$numnewargs; ++$i ) {
	        $newarg = $this->iArgs[$i];
	        $this->iArgsDes[$i] = '';
	        for( $j=0; $j<$numoldargs; ++$j ) {
		        $oldarg = $aOldFunc['fld_arg'.($j+1)];
		        if( empty($aOldFunc['fld_argdes'.($j+1)]) )
		            $oldargdes = '';
		        else
		            $oldargdes = $aOldFunc['fld_argdes'.($j+1)];
		        if( $newarg==$oldarg ) {
		            $this->iArgsDes[$i]  = $oldargdes;
		        }
	        }
	    }
	    
	    return true;
    }        
}

class DBClassProp extends ClassProp {
    var $iKey="";
    var $iDesc="";
	var $iProjname;

    function DBClassProp($aProjname,$aParent,$aName,$aLineNbr,$aFile) {
    	parent::ClassProp($aParent,$aName,$aLineNbr,$aFile);
    	$this->iProjname = $aProjname;
    }

    function Internalize($aDBRow) {
    }

    function Externalize($aDB) {	    
    	// Check if this class already exist in the DB
	    $q  = "SELECT * FROM tbl_class_".$this->iProjname." WHERE ";
    	$q .= "fld_name='".$this->iName."'";
	    $res = $aDB->query($q);
    	if( $res->NumRows() > 0 ) {
	        $GLOBALS["gLogger"]->ToScreen( "Class '".$this->iName."' at line #".$this->iLineNbr." is already in database<br>" );
	        $row = $res->Fetch();
    	    if( !is_null($row["fld_desc"]) && $row["fld_desc"]!='' )
	            $this->iDesc = $row["fld_desc"];
	        else
	        	$this->iDesc = '';
	        	
            $idx = $row['fld_key'];

			// A sanity check of DB
			if( is_null($row['fld_numfuncs']) ) {
				echo "PANIC: DB Corruption. fld_numfuncs in DB is NULL for class ".$row['fld_name'].".($row[fld_numfuncs])<br>";
				exit();
			}
			$this->ExternalizeUpdateMethods($aDB,$idx);	        
   	        $this->ExternalizeClass($aDB,$idx);	        
	    }
    	else {
	        $GLOBALS["gLogger"]->ToScreen( "Adding class ".$this->iName );
	        $idx=$this->ExternalizeClass($aDB);
        	$this->ExternalizeMethods($aDB,$idx);
        }   
	    $this->ExternalizeVars($aDB,$idx);    
        return $idx;
    }
    
    function ExternalizeClass($aDB,$aKey="") {

	    $q  = "REPLACE INTO tbl_class_".$this->iProjname." SET ";
    	$q .= "fld_key='".$aKey."',";
    	$q .= "fld_name='".$this->iName."',";
	    $q .= "fld_parentname='".$this->iParent."',";
	    $q .= "fld_file='".mysql_escape_string($this->iFileName)."',";
	    $q .= "fld_numfuncs=".$this->iFuncNbr.",";
	    $q .= "fld_desc='".mysql_escape_string($this->iDesc)."',";
    	$q .= "fld_linenbr=".$this->iLineNbr." ";
    	$aDB->query($q);
    	$this->iKey = $aDB->LastIdx();
	
	    return $this->iKey;
    }

    function ExternalizeMethods($aDB,$aClassIdx) {
	    for( $m=0; $m<$this->iFuncNbr; ++$m) {
	        $func = $this->iFuncs[$m];
	        $func->Externalize($aDB,$aClassIdx);
    	}
    }
    
    function ExternalizeUpdateMethods($aDB,$aClassIdx) {
	    // Now get all the methods that is registred for this class
	    
	    $q = "SELECT * FROM tbl_method_".$this->iProjname." WHERE fld_classidx=$aClassIdx";
	    $methres = $aDB->query($q);
	    $nbrmeth = $methres->NumRows();
	    
	    if( $nbrmeth > 0 ) {
	    	// Read all existing methods into 'oldfuncs'
		    $oldfuncs = array();
    		for($i=0; $i<$nbrmeth; ++$i) {
	    	    $oldfuncs[$i]=$methres->Fetch();
		        $exists[$i] = false;
    		}	
    		$nold = count($oldfuncs);
    		
    		// Sanity check of Database 
    		for($i=0; $i<$nold; ++$i ) {
    			for($j=$i+1; $j<$nold; ++$j ) {
    				if( $oldfuncs[$i]['fld_name'] == $oldfuncs[$j]['fld_name'] ) {
    					echo "PANIC: Corruption in database. Function ".$oldfuncs[$j]['fld_name']." is double defined for classidx $aClassIdx<p>";
    					exit();
    				}
    			}
    		}
    		
		    // Walk through all the existing methods
    		for($i=0; $i<$this->iFuncNbr ; ++$i) {
	    	    $found = false;
		        $func = $this->iFuncs[$i];

		        // This unfortunately have to be an O(n^2) mathching ...
	    	    for($j=0; ($j < $nold) && !$found ; ++$j) {
		        	if( $oldfuncs[$j]["fld_name"] == $func->iName ) {
			            $found = true;
			            $exists[$j] = true;
    			        $oldfunc = $oldfuncs[$j];
            		}
		        }

				$GLOBALS["gLogger"]->ToScreen( "Checking if method :$func->iName exists..." );
		        if( !$found ) {
    	    		$exists[$j] = true;
	    	    	$GLOBALS["gLogger"]->ToScreen( " no. Adding it to DB." );
        			$func->Externalize($aDB,$aClassIdx);
		        }
		        else {
        			$GLOBALS["gLogger"]->ToScreen( " yes." );
        			
		        	// Now update the newly parsed method with any exsiting descriptions
		        	// in the database      	
    	    		$changed = $func->UpdateFromExisting($oldfunc);   	    		
    	    		if( $changed )
			        	$func->Externalize($aDB,$aClassIdx,$oldfunc["fld_key"]);
		        }
    		} // for

	    	// Delete no longer existing methods
		    for( $i=0; $i<$nold; ++$i ) {
    		    if( !$exists[$i] ) {
	        		$GLOBALS["gLogger"]->ToScreen( "Deleting  method ".$this->iName."::".$oldfuncs[$i]["fld_name"]."()" );
			        $q = "DELETE FROM tbl_method_".$this->iProjname." WHERE fld_key=".$oldfuncs[$i]["fld_key"];
        			$res = $aDB->query($q);     			
        			if( $res->iRes==1 ) {
	        			$q = "UPDATE tbl_class_".$this->iProjname." SET fld_numfuncs=fld_numfuncs-1 WHERE fld_key=$aClassIdx";
    	    			$aDB->query($q);
        			}
        			else {
        				echo "PANIC: DB Corruption. Can't delete function ".$oldfuncs[$i]["fld_name"]."<br>$q<p>\n";
        				exit();
        			}			
		        }
    		}
	    } 
	    else {
	    	// No existing methods in the DB so just store the new ones
	    	$this->ExternalizeMethods($aDB,$aClassIdx);
	    }
    }

    function ExternalizeVars($aDB,$aClassIdx) {
	    // We just delete all exiting variables for this class and then
    	// add the new ones. This could be DB optimized to only
	    // delete variables that doesn't exist any more and just adding
    	// the new one but this is simpler!
	    $q = "DELETE FROM tbl_classvars_".$this->iProjname." WHERE fld_classidx=".$aClassIdx;
    	$res = $aDB->query($q);

        for( $i=0; $i < $this->iVarNbr; ++$i ) {
	        $q = "INSERT INTO tbl_classvars_".$this->iProjname." SET fld_key='',";
    	    $q .= "fld_name='".mysql_escape_string($this->iVars[$i][0])."',";
	        $q .= "fld_default='".mysql_escape_string($this->iVars[$i][1])."',";
	        $q .= "fld_classidx=".$aClassIdx;
    	    $aDB->query($q);
        }
    }    
}

class DBParser extends Parser {
    var $iLogNbr;
    var $iDB;
    var $iClassIdx=array();
    var $iProjName;
    var $iPrettyPrint=false;

    function DBParser($aProjname,$aFile,$aDB) {
	    $this->iDB = $aDB;
	    $this->iLogNbr = 0;
	    $this->iProjName = $aProjname;
    	parent::Parser($aFile);	    
    }

	function PrettyPrint($aFlg) {
		$this->iPrettyPrint = $aFlg;
	}    
    
	function LineIndicatorMinor($aLineNbr) {    
	}

	function LineIndicatorMajor($aLineNbr) {    
	}

	function StartIndicator($aFilename) {
	}

    // Override Factory function for classes
    function &NewClassProp($aParent,$aName,$aLineNbr,$aFileName) {
	    return new DBClassProp($this->iProjName,$aParent,$aName,$aLineNbr,$aFileName);
    }
	
    // Override Factory function for methods
    function &NewFuncProp($aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment) {
	    return new DBFuncProp($this->iProjName,$aClassName,$aName,$aLineNbr,$aArgs,$aArgsVal,$aShortComment,$this->iCurrFileName);
    }
    
    // Map function for global functions
    function MapGlobalFunc($aFunc) {
    	
    	if( $this->iPrettyPrint )
    		parent::MapGlobalFunc($aFunc);
    	
    	// Check if this function already exists
    	$q = "SELECT * FROM tbl_method_".$this->iProjName." WHERE fld_name='".$aFunc->iName."' AND fld_file='".mysql_escape_string($this->iCurrFileName)."'";
    	$res = $this->iDB->Query($q);
    	$n=$res->NumRows();
    	if( $n===1 ) {	    	
	    	$oldfunc = $res->Fetch();
	    	$aFunc->UpdateFromExisting($oldfunc);   	  
	    	$oldidx = $oldfunc['fld_key'];
	    	$aFunc->Externalize($this->iDB,0,$oldidx);
	    }
	    elseif( $n > 1 ) { 
	    	die('PANIC : Database corrupt. There are multiple versions of GLOBAL function : '.$aFunc->iName);
	    }
	    else
	    	$aFunc->Externalize($this->iDB);
    }

    // map function for classes
    function MapClass($aClass) {
	    if( $this->iPrettyPrint ) {
			$GLOBALS["gLogger"]->ToScreen( "<p>Mapping class '".$aClass->iName );	    	
	    	parent::MapClass($aClass);
	    }
	    $this->iClassIdx[$aClass->iName] = $aClass->Externalize($this->iDB);
    }
}


class DBDriver extends LintDriver {
    var $iDB;
    var $iProjname;
    var $iPrintFile = false;

    function DBDriver($aProjname,$aFile,$aDB) {
	    $this->iDB = $aDB;
	    $this->iProjname = $aProjname;
	    parent::Driver($aFile);	
    }
    	
    function NewParser($aFile) {
	    return new DBParser($this->iProjname,$aFile,$this->iDB);
    }
	
    function PostProcessing() {
   		parent::PostProcessing();
    }		
}


// Test Driver
class DBTestDriver extends LintDriver {
    var $iDB;
    var $iProjname;

    function DBDriver($aProjname,$aFile) {
		$this->iDB = DBFactory::InitDB();    	
    	$this->iProjname = $aProjname;
	    parent::Driver($aFile);	
    }
	
    function NewParser($aFile) {
	    return new DBParser($this->iProjname,$aFile,$this->iDB);
    }
	
    function PostProcessing() {
    	parent::PostProcessing();
	    $res = $this->iParser->GetUnusedClassVariables();
    	if( trim($res!="") )
	        echo "<hr><h3>SUMMARY of unused instance variables</h3>$res";		
    	$res = $this->iParser->GetWarnings();
	    if( trim($res!="") )
	        echo "<hr><h3>SUMMARY of warnings</h3>$res";
    	$this->iDB->close();
    }		
}


//==========================================================================
// Script entry point for test harness
// Read URL argument and create Driver
//==========================================================================
//if( !isset($HTTP_GET_VARS['target']) )
//die("<b>No file specified.</b> Use 'mylintphp.php?target=file_name'" );	
//$file = urldecode($HTTP_GET_VARS['target']);
//$driver = new DBTestDriver($file);
//$driver->Run();




?>





