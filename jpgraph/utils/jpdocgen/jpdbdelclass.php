<?php
//==============================================================================
// Name:        JPDBDELCLASS.PHP
// Description:	Deletes a class from the documentation DB
// Created: 	2002-04-14 
// Author:	    johanp@aditus.nu
// Version: 	$Id: jpdbdelclass.php,v 1.3 2002/06/05 22:39:14 aditus Exp $
//
// License:	    QPL 1.0
// Copyright (C) 2002 Johan Persson
//
//==============================================================================

include ("jpdb.php");
include ("de_utils.php");

DEFINE("DELDB_OVERRIDE_REFCHK",1);
DEFINE("DELDB_OVERRIDE_EXTCHK",2);

class RemoveDBClass {
    var $iDB;
    function RemoveDBCLass($aDB) {
        $this->iDB = $aDB;
    }
    
    function Run($aProjname,$aClass,$aFlags=0) {
        
        // Get some info about this class   
        $q = 'SELECT * FROM tbl_class_'.$aProjname.' WHERE fld_name='."'$aClass'";
        $res = $this->iDB->Query($q);
        if( $res->NumRows() == 0 ) {
            Utils::Error("Can't find class '$aClass' in DB.");
            return false;
        }
                    
        $class = $res->Fetch();
        
        // As a safety check we don't allow deleting classes who's
        // methods are referenced by the method references in other classes.
        
        $q = "SELECT DISTINCT CONCAT(c.fld_name,'::<b>',b.fld_name,'</b>()') as fld_name 
             FROM tbl_method_".$aProjname." as a,tbl_method_".$aProjname." as b,tbl_class_".$aProjname." as c 
             WHERE a.fld_classidx=".$class['fld_key']." AND
                   c.fld_key = b.fld_classidx AND
                   (b.fld_methref1=a.fld_key OR
                    b.fld_methref2=a.fld_key OR
                    b.fld_methref3=a.fld_key OR
                    b.fld_methref4=a.fld_key OR
                    b.fld_methref5=a.fld_key) 
              ";
        $res = $this->iDB->Query($q);
        $n = $res->NumRows(); 
        if( $n > 0 && !($aFlags & DELDB_OVERRIDE_REFCHK)) {
            $s = '';
            while( $n > 0 ) {
                $row = $res->Fetch();
                $s .= $row['fld_name'];
                if( $n > 1 ) $s .= ",<br> ";
                --$n;
            }
            Utils::Error("Can't delete class '$aClass' since it's methods are referenced by:<br>$s<p>");    
            return false;
        }           
        
        // As another safety check we don't allow deleting classes which
        // are superclasses to some other classes in the DB
        
        $q = 'SELECT fld_name FROM tbl_class_'.$aProjname.' WHERE fld_parentname='."'$aClass'";
        $res = $this->iDB->Query($q);
        $n = $res->NumRows(); 
        if( $n > 0 && !($aFlags & DELDB_OVERRIDE_EXTCHK) ) {
            $s = '';
            while( $n > 0 ) {
                $row = $res->Fetch();
                $s .= "CLASS ".$row['fld_name'];
                if( $n > 1 ) $s .= ",<br> ";
                --$n;
            }
            Utils::Error("Can't delete class '$aClass' since it is inherited by:<br>$s<p>");    
            return false;
        }           
        
        // Checks are done. Point of no return. Now really delete class
        
        // Start by deleting all methods in this class
        $q = "DELETE FROM tbl_method_".$aProjname." WHERE fld_classidx=".$class['fld_key'];
        $this->iDB->Query($q);

        // .. and all instance variables
        $q = "DELETE FROM tbl_classvars_".$aProjname." WHERE fld_classidx=".$class['fld_key'];
        $this->iDB->Query($q);
        
        // ... and the class
        $q = "DELETE FROM tbl_class_".$aProjname." WHERE fld_name='".$aClass."'";
        $this->iDB->Query($q);
        
        return true;        
    }
}

// Driver
class Driver {
    var $iDB;
	var $iProjname,$iProjidx;
	
    function Driver() {
    	global $HTTP_COOKIE_VARS;
    	$this->iDB = DBFactory::InitDB();
    	
		$this->iProjname = strtok(@$HTTP_COOKIE_VARS['ddda_project'],':');		
		if( $this->iProjname != '' ) {
			$this->iProjidx = strtok(':');
		} 
		else
			die('No project specified.'); 	 	    	
    }
    
    function Run($aClass,$aFlags=0) {
        $dbremove = new RemoveDBClass($this->iDB);
        if( $dbremove->Run($this->iProjname,$aClass,$aFlags) )
            echo "Class <b>$aClass</b> has been removed from JpGraph Database.";
        else
            Utils::Error("Can't remove class <b>$aClass</b> from JpGRaph Database.");
    }	
}


//==========================================================================
// Script entry point
// Read URL argument and create Driver
//==========================================================================
if( !isset($HTTP_GET_VARS['class']) )
    Utils::Error("Must specify class to remove from db. Use syntax class=<class-name>");
else
    $class = urldecode($HTTP_GET_VARS['class']);
    
$driver = new Driver();
$driver->Run($class);
?>