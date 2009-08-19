<?php
//==============================================================================
// Name:        JPCLASSREF.PHP
// Description:	Basic framework to extract information about class hierarchi
//              and structure from DB. To do specific output this framework
//              expects a "formatter" plugin which handles the actual 
//              layout and formatting of class, functions and variables.
//              See jpgenhtmldoc.php for example on how to write a simple
//              HTML formatter.
// Created: 	2002-04-12 
// Author:	    johanp@aditus.nu
// Version: 	$Id: jpclassref.php,v 1.14 2002/06/25 22:40:38 aditus Exp $
//
// License:	QPL 1.0
// Copyright (C) 2001,2002 Johan Persson
//
//==============================================================================
include_once("jpdb.php");
include_once("de_utils.php");

DEFINE("MAX_METHODREF",5);
DEFINE("MAX_METHODARGS",10);


DEFINE('GLOBFUNCCLASS','GLOBALFUNCS');

class DBCache {
    var $iDB;
    var $iDBUtils;
    var $iClasses=array(), 
         $iClassesByName=array(), 
         $iClassMethods=array(), 
         $iClassMethodsByName=array(),
         $iAllMethods=array();
    var $iMethods;
    var $iProjName;
    var $iShowPrivate;
    
    function DBCache($aDB,$aProjName) {
    	$this->iProjName = '_'.$aProjName;
        $this->iDB = $aDB;
        $this->iDBUtils = new DBUtils($aDB);
        $this->iShowPrivate = $this->iDBUtils->GetShowPrivate($aProjName);
    }
    
    function RefreshMethods() {
        $q = 'SELECT * FROM tbl_method'.$this->iProjName.' ORDER BY fld_name';
        $res = $this->iDB->query($q);
        $n = $res->NumRows();
        $this->iMethods = array();
        $this->iClassMethods = array();
        $this->iClassMethodsByName = array();
        for( $i=0; $i < $n; ++$i ) {
            $row = $res->Fetch();
            if( empty($row['fld_classidx']) ||  $row['fld_classidx']===0 )
                $classname = GLOBFUNCCLASS;
            else
                $classname = $this->iClasses[$row['fld_classidx']][0];
            $this->iMethods[$row["fld_key"]] = array($classname,$row['fld_name'],$row['fld_public']);        
            $this->iClassMethods[$classname][] = array('fld_name' => $row['fld_name'],
            										   'fld_public' => $row['fld_public'],
            										   'fld_file' => $row['fld_file'],
            										   'fld_linenbr' => $row['fld_linenbr']);
            $this->iClassMethodsByName[$classname][$row['fld_name']] = array($row['fld_name'],$row['fld_public']);            
        }
    }
    
    function RefreshClasses() {
        $q = 'SELECT * FROM tbl_class'.$this->iProjName.' ORDER BY fld_name';
        $res = $this->iDB->query($q);
        $n = $res->NumRows();
        $this->iMethods = array();
        for( $i=0; $i < $n; ++$i ) {
            $row = $res->Fetch();
            $this->iClasses[$row["fld_key"]] = array($row["fld_name"],$row["fld_parentname"],$row["fld_public"]);        
            $this->iClassesByName[$row["fld_name"]] = array($row["fld_key"],$row["fld_parentname"],$row["fld_public"]);        
        }
    }
    
}    

DEFINE("FMT_CLASSVARS",1);

class ClassFormatter {
    var $iDBCache;
    var $iFlags;
        
    function ClassFormatter($aDBCache,$aFlags="") {
        $this->iDBCache = $aDBCache;
        $this->iFlags = $aFlags;
    }
    
    // Empty stubs ("virtual functions")
    // A subclass needs to override this methods to actual achieve the
    // desired formatting. The framework will call these formatting
    // functions at the appropriate time.
    
    function FmtClassHierarchySetup($aHier,$aNbr) {}
    function FmtClassHierarchyExit($aHier,$aNbr) {}
    function FmtClassHierarchyHeaders($aHier,$aNbr) {}    
    function FmtClassHierarchyColumnSetup($aClassName,$aColNbr) {}
    function FmtClassHierarchyColumnExit($aClassName,$aColNbr) {}    
    function FmtClassHierarchyRow($aClassName,$aMethodName,$aOverridden,$aPublic) {}    
    function FmtClassSetup($aClassInfo) {}    
    function FmtClassOverview($aClassInfo) {}
    function FmtClassVars($aVars) {}
    function FmtClassRefs($aClassInfo) {}
    function FmtFuncReturn($aFunc) {}
    function FmtFuncPrototype($aClassName,$aFunc,$aShowFile=false) {}    
    function FmtFuncArgs($aFunc)  {}    
    function FmtFuncDesc($aFunc) {}
    function FmtFuncRef($aRef) {}           
    function FmtFuncExample($aFunc) {}    
    function FmtIndexSetup() {}
    function FmtIndexClass($aClassName) {}
   	function FmtIndexMethod($aClassName,$aMethodName) {}
    function FmtIndexExit() {}
    
    // Called before/after global funcs
    function GlobalFuncEntry($aNumFuncs) {}
    function GlobalFuncExit() {}
    
    // Called before/after any new class. 
    function ClassExit() {}
    function ClassEntry($aClassName) {}
    
    // Called before and after all formatting is done
    function Start($aDB,$aProjName) {}
    function End() {}
     

    // -------  END OF STUBS  -----------
    
    function ClassHierarchy($aHier) {
        $n = count($aHier);
        $this->FmtClassHierarchySetup($aHier,$n);
        $this->FmtClassHierarchyHeaders($aHier,$n);
        
        for( $i=0; $i<$n; ++$i ) {
            $this->FmtClassHierarchyColumnSetup($aHier[$i],$i);
            
            if( empty($this->iDBCache->iClassMethods[$aHier[$i]]) ) {
                $this->FmtClassHierarchyRow($aHier[$i],"",false,false);
            }
            else {
                $methods = $this->iDBCache->iClassMethods[$aHier[$i]];
                $m = count($methods);
                for( $j=0; $j<$m; ++$j ) {
                    $overridden = false;
                    if( $i > 0 ) {
                        if( !empty($supermethods[$methods[$j]['fld_name']]) )
                            $overridden = true;
                    }                    
                    $this->FmtClassHierarchyRow($aHier[$i],$methods[$j]['fld_name'],$overridden,$methods[$j]['fld_public']);                    
                }
            }
            if( !empty($this->iDBCache->iClassMethodsByName[$aHier[$i]]) )
                $supermethods = $this->iDBCache->iClassMethodsByName[$aHier[$i]];
            else
                $supermethods = array();
            $this->FmtClassHierarchyColumnExit($aHier[$i],$i);                
        }
        $this->FmtClassHierarchyExit($aHier,$n);
    }
            
        
    function DoClass($aClass,$aHier) {
        $this->FmtClassSetup($aClass);
        $this->ClassHierarchy($aHier);        
        $this->FmtClassOverview($aClass);
        $this->FmtClassRefs($aClass);
        $this->FmtClassOverviewExit($aClass); 
    }
    
    function DoVars($aVars) {
        if( !$this->iFlags & FMT_CLASSVARS )
            return;
        $this->FmtClassVars($aVars);
    }
    
    function ResolvMethRef($aRef) {
        if( empty( $this->iDBCache->iMethods[$aRef] ) )
            Utils::Error("Unknown method reference=$aRef");
        else return $this->iDBCache->iMethods[$aRef];
    }
    
    function DoFuncs($aFuncs,$aClassName,$aShowFile=false) {
        $n = count($aFuncs);
        for( $i=0; $i < $n; ++$i ) {
        	 
        	if( $aClassName == GLOBFUNCCLASS ) {        	
        		echo '<br>'.($i+1).' : <font color=blue>'.$aFuncs[$i]['fld_name']."()</font>...\n";
        	}
        	        	        	        	
        	if( $aFuncs[$i]['fld_public'] == 0 && !$this->iDBCache->iShowPrivate )
        		continue;
            $this->FmtFuncPrototype($aClassName,$aFuncs[$i],$aShowFile);
            $this->FmtFuncArgs($aFuncs[$i]);
            $this->FmtFuncDesc($aFuncs[$i]);
            $this->FmtFuncReturn($aFuncs[$i]);            
            
            $j = 1;
            $ref=array();
            while( $j <= MAX_METHODREF  ) {
            	if( !empty($aFuncs[$i]["fld_methref$j"]) && $aFuncs[$i]["fld_methref$j"] > 0)
                	$ref[]=$aFuncs[$i]["fld_methref$j"];
                ++$j;
            }
            $m = count($ref); 
            if( $m > 0 ) {
                $refarr=array();
                for( $j=0; $j < $m; ++$j ) {
                    if( empty( $this->iDBCache->iMethods[$ref[$j]] ) )
                        Utils::Error("Unknown method reference key=$ref[$j] in method : $aClassName::".$aFuncs[$i]['fld_name']);
                    else $refarr[] = $this->iDBCache->iMethods[$ref[$j]];
                }        
                $this->FmtFuncRef($refarr);                          
            }            
            $this->FmtFuncExample($aFuncs[$i]);
        }
    }
}


class ClassRef {
    var $iIdx, $iRow, $iVars, $iFuncs, $iDBCache, $iHierarchy;
    function ClassRef($aRow,$aHierarchy,$aVars,$aFuncs,$aIdx,$aDBCache) {
        $this->iIdx = $aIdx;
        $this->iRow = $aRow;
        $this->iVars = $aVars;
        $this->iFuncs = $aFuncs;                
        $this->iDBCache = $aDBCache;
        $this->iHierarchy = $aHierarchy;
    }
        
    function Stroke(&$aFormatter) {
    	$aFormatter->ClassEntry($this->iRow["fld_name"]);
        $aFormatter->DoClass($this->iRow,$this->iHierarchy); 
        $aFormatter->DoVars($this->iVars); 
        $aFormatter->DoFuncs($this->iFuncs,$this->iRow["fld_name"]);                                      
        $aFormatter->ClassExit();
    }    
}

class ClassReader {
    var $iDB, $iDBCache, $iFlags;
    var $iFormatter;
    var $iProjname;
    var $iNumIndexCols = 3;
    
    function ClassReader($aFormatter,$aDBCache,$aFlags="") {
        $this->iDB = $aDBCache->iDB;
        $this->iDBCache = $aDBCache;
        $this->iFlags = $aFlags;
        $this->iFormatter = $aFormatter;
        $this->iProjName = $aDBCache->iProjName;
    }
    
    function SetNumIndexCols($aNum) {
    	$this->iNumIndexCols = $aNum;
    }
    
    function GetHierarchy($aClassName) {    
        $h = array($aClassName);
        $parent = $this->iDBCache->iClassesByName[$aClassName][1];
        while( $parent != "" ) {
            $h[] = $parent;       
            if( empty($this->iDBCache->iClassesByName[$parent][1]) ) {
                break;
            }
            else                 
                $parent = $this->iDBCache->iClassesByName[$parent][1];
        }
        return $h;
    }
    
    function GenClassIndex() {
    	$this->iFormatter->FmtIndexSetup(count($this->iDBCache->iClasses),count($this->iDBCache->iMethods),$this->iNumIndexCols);
		foreach( $this->iDBCache->iClasses as $c ) {
        	if( $c[2] == 0 && !$this->iDBCache->iShowPrivate )
	    		continue;
			
			$this->iFormatter->FmtIndexClass($c[0]);
			if( !empty($this->iDBCache->iClassMethods[$c[0]]) && count($this->iDBCache->iClassMethods[$c[0]]) > 0 ) {
				foreach( $this->iDBCache->iClassMethods[$c[0]] as $m ) {
		        	if( $m['fld_public'] == 0 && !$this->iDBCache->iShowPrivate )
    		    		continue;
					$this->iFormatter->FmtIndexMethod($c[0], $m['fld_name']);
				}
			}			
		}
		$this->iFormatter->FmtIndexExit();    	
    }
     
    
    function Run($aClass) {
    	
        $q = 'SELECT * FROM tbl_class'.$this->iProjName.' ORDER BY fld_name ';        
        if( $aClass != "" )
            $q .= " WHERE fld_name='".$aClass."'";
        $classres = $this->iDB->query($q);
        $n = $classres->NumRows();

        
        $this->GenClassIndex();
        
        for( $i=0; $i < $n; ++$i ) {
            $row = $classres->Fetch();

		     if( $row['fld_public'] == 0 && !$this->iDBCache->iShowPrivate )
    		    continue;

            $hier = $this->GetHierarchy($row["fld_name"]);
            
            $q = 'SELECT * FROM tbl_classvars'.$this->iProjName.' WHERE fld_classidx='.$row['fld_key'].' ORDER BY fld_name';
            $varres = $this->iDB->query($q);
            $nn = $varres->NumRows();
            $vars = array();
            for( $j=0; $j < $nn; ++$j ) {
                $vars[] = $varres->Fetch();
            }

            $q = 'SELECT * FROM tbl_method'.$this->iProjName.' WHERE fld_classidx='.$row['fld_key'].' ORDER BY fld_name';
            $funcres = $this->iDB->query($q);
            $nn = $funcres->NumRows();
            $funcs = array();
            for( $j=0; $j < $nn; ++$j ) {
                $funcs[] = $funcres->Fetch();
            }
            
            $c = new ClassRef($row,$hier,$vars,$funcs,$i,$this->iDBCache);  
            $c->Stroke($this->iFormatter);
        }
    }
}

// Read all global functions and format them
class GlobalFuncReader {
	var $iDB,$iDBCache,$iFlags;
    var $iFormatter;
    var $iProjname;
    
	function GlobalFuncReader($aFormatter,$aDBCache,$aFlags="") {
        $this->iDBCache = $aDBCache;
        $this->iDB = $aDBCache->iDB;
        $this->iFlags = $aFlags;
        $this->iFormatter = $aFormatter;
        $this->iProjName = $aDBCache->iProjName;
	}

	function Run() {
        $q = 'SELECT * FROM tbl_method'.$this->iProjName.' WHERE fld_classidx=0 ORDER BY fld_name';
        $res = $this->iDB->query($q);
		$n = $res->NumRows();
		$funcs=array();
		for($i=0; $i<$n; ++$i ) {
			$funcs[] = $res->Fetch();
		}
	    $this->iFormatter->GlobalFuncEntry($n);	    	    
	    $this->iFormatter->DoFuncs($funcs,GLOBFUNCCLASS,true);	    
    	$this->iFormatter->GlobalFuncExit();
	}
}


// Driver
class ClassRefDriver {
    var $iDB,$iDBCache;
    var $iProjName, $iProjIdx;
    var $iNumIndexCols=3;

    function NewClassFormatter($aDBCache,$aFlags) {
        Utils::Error("ERROR: NewClassFormatter must be overridden to provide the actual formatter");
    }

	function SetNumIndexCols($aNum) {
		$this->iNumIndexCols = $aNum;
	}

    function ClassRefDriver() {
    	global $HTTP_COOKIE_VARS;

    	$this->iDB = DBFactory::InitDB();
    	
		$this->iProjname = strtok(@$HTTP_COOKIE_VARS['ddda_project'],':');
		if( $this->iProjname != '' ) {
			$this->iProjidx = strtok(':');
			$this->iDBCache = new DBCache($this->iDB,$this->iProjname);    	
		}
		else   	
			die('No, no project specified.'); 	
    }
    
    function Run($aClass,$aFlags="") {
        $this->iDBCache->RefreshClasses();
        
        $this->iDBCache->RefreshMethods();
        $fmt = $this->NewClassFormatter($this->iDBCache,$aFlags);        
        $fmt->Start($this->iDB,$this->iProjname);        
        
        // Format all the classes and their methods
        $cr = new ClassReader($fmt,$this->iDBCache,$aFlags);
        $cr->SetNumIndexCols($this->iNumIndexCols);
        $cr->Run($aClass);
        
        // Format all global functions
        $gf = new GlobalFuncReader($fmt,$this->iDBCache,$aFlags);
        $gf->Run();

		$fmt->End();       
		
    }	
}

?>
