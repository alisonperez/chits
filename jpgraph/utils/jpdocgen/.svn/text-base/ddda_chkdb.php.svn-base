<?php
//=======================================================================
// File:	DDDA_CHKDB.PHP
// Description:	Check database integrity for project
// Created:	2002-07-01
// Author:	Johan Persson (johanp@aditus.nu)
// Ver: 	$Id: ddda_chkdb.php,v 1.4 2002/07/03 23:31:01 aditus Exp $
//
// License: QPL 1.0
// Copyright (C) 2002 Johan Persson
//=======================================================================

include "jpdb.php";
include "de_utils.php";

//--------------------------------------------------
// Consistency check of database for project.
class CheckProjectDB {
  var $iDB;
  var $iMethods=array();
  var $iMethodsbyclass=array();
  var $iMethodsbyidx=array();
  var $iErrCnt=0;
  var $iClasses=array();
  var $iClassesbyname=array();
  var $iClassesbyidx=array();
  var $iProject='';
  var $nc=0, $nm=0;

  function CheckProjectDB($aDB) {
    $this->iDB = $aDB;
  }

  function ErrorReport($aStr) {
    ++$this->iErrCnt;
    echo '<b><font color=red>Error '.$this->iErrCnt.':</font></b> '.$aStr."<br>\n\r";
  }

  function ReadMethods() {
    // Read all methods
    $q = 'SELECT fld_key, fld_name, fld_classname, fld_classidx, fld_methref1, fld_methref2, fld_methref3, fld_methref4, fld_methref5  FROM tbl_method_'.$this->iProject.' ORDER BY fld_classname, fld_name ';
    $res=$this->iDB->Query($q);
    $nm = $res->NumRows();
    if( $nm==0 ) {
      $this->ErrorReport('No methods in method table for project: '.$project);
      die();
    }
    for( $i=0; $i<$nm; ++$i) {
    	$row = $res->Fetch();
      $this->iMethods[$i]=$row;
      $this->iMethodsbyclass[$row['fld_classname']][] = $row;
      $idx = $row['fld_key'];
      if( isset($this->iMethodsbyidx[$idx]) ) {
      	$this->ErrorReport('Duplicate key : '.$idx.' in method table.');
      }
      else $this->iMethodsbyidx[$idx] = $row['fld_name'];
    }
    
    return $nm;
  }

  function ReadClasses() {
    // Read all Classes
    $q = 'SELECT fld_key,fld_name,fld_parentname,fld_numfuncs FROM tbl_class_'.$this->iProject.' ORDER BY fld_key, fld_name ';
    $res=$this->iDB->Query($q);
    $nc = $res->NumRows();
    if( $nc==0 ) {
      $this->ErrorReport('No $this->iClasses for project: '.$project);
      die();
    }
    for( $i=0; $i<$nc; ++$i) {
      $this->iClasses[$i]=$res->Fetch();

      $name = $this->iClasses[$i]['fld_name'];
      if( isset($this->iClassesbyname[$name]) ) {
	$this->ErrorReport('Duplicate class name $name: '.$name);
	die();
      }
      $this->iClassesbyname[$name] = $this->iClasses[$i]['fld_key'];
  
      $idx = $this->iClasses[$i]['fld_key'];
      if( isset($this->iClassesbyidx[$idx]) ) {
	$this->ErrorReport('Duplicate class idx: '.$idx);
	die();
      }
      $this->iClassesbyidx[$idx] = $this->iClasses[$i]['fld_name'];
    }
    
    return $nc;
  }

  function ChkMethods() {
    // Check methods for inconsistency
    for( $i=0; $i<$this->nm; ++$i) {

      // Check for duplicates
      if( $i<$this->nm-1 && $this->iMethods[$i][1] == $this->iMethods[$i+1][1] && $this->iMethods[$i][2] == $this->iMethods[$i+1][2] ) {
	$this->ErrorReport('Duplicate entry for method <b>'.$this->iMethods[$i][1].
			   '</b> in class <b>'.$this->iMethods[$i+1][2].'</b> with keys '.$this->iMethods[$i][0].' and '.$this->iMethods[$i+1][0]);
      }

	// Check that the existing references point to valid methods
	for( $j=1; $j<=5; ++$j ) {
		$ref = @$this->iMethods[$i]['fld_methref'.$j];
		if( !empty($ref) ) {
			if( $ref > -1 && empty($this->iMethodsbyidx[$ref]) ) {
				$this->ErrorReport('Method <b>'.$this->iMethods[$i]['fld_classname'].'::'.$this->iMethods[$i]['fld_name'].'</b> has invalid reference fld_methref'.$j.'='.$ref);
			}
		}
	}
	

      // Check that class idx exists
      $idx = $this->iMethods[$i][3];
      if( $idx != 0 && empty($this->iClassesbyidx[$idx]) ) {
	$this->ErrorReport('Classidx <b>'.$this->iMethods[$i][3].'</b> in method <b>'.$this->iMethods[$i][1].'</b> does not exist in class table.');
      }

      // Check that class name exists
      if( $idx != 0 && empty($this->iClassesbyname[$this->iMethods[$i][2]]) ) {
	$this->ErrorReport('Classname <b>'.$this->iMethods[$i][2].'</b> in method <b>'.$this->iMethods[$i][1].'</b> does not exist in class table.');
      }

    }
  }

  function ChkClasses() {
    // Check classes for inconsistency
    for( $i=0; $i<$this->nc; ++$i) {

      // Check that parent class exist if specified
      $parent = $this->iClasses[$i]['fld_parentname'];
      if( !empty($parent) ) {
	if( empty($this->iClassesbyname[$parent]) ) {
	  $this->ErrorReport('Parent class for <b>'.$this->iClasses[$i]['fld_name'].'</b> named <i><b>'.$parent.'</b></i> does not exist.');
	}
      }

      // Check that this class have some methods
      if( empty($this->iMethodsbyclass[$this->iClasses[$i]['fld_name']]) ) {
	$this->ErrorReport('Class <b>'.$this->iClasses[$i]['fld_name'].'</b> does not have any methods ');
      }
      else {
	// Check that number of methods matchup
	$tmp = count($this->iMethodsbyclass[$this->iClasses[$i]['fld_name']]);
	if( $tmp != $this->iClasses[$i]['fld_numfuncs'] ) {
	  $this->ErrorReport('Method count for Class <b>'.$this->iClasses[$i]['fld_name'].'</b> does not match. Count=<b>'.$this->iClasses[$i]['fld_numfuncs'].'</b> vs actual <b>'.$tmp.'</b>' ); 
	}
      }
    }
  }    


  function Run($aProject) {
    $this->iProject = $aProject;
    $timer = new JpgTimer();
    
    echo "<h3>DB Consistency check for project: <font color=blue>$aProject</font></h3><hr>";
    $timer->Push();
    
    //echo HTMLGenerator::CloseWinButton();
    
    echo "<i>This will verify the integrity of the keys in the database as well as foreign key references. ";
    echo "It will also perform various consistency check within the methods and classes.</i>";

	echo '<hr>';
    
    echo "<h4>Reading project information ... </h4>\n";flush();
    $this->nm = $this->ReadMethods();
    $this->nc = $this->ReadClasses();
    
    echo "Classes: $this->nc, &nbsp Methods: $this->nm <br>";
 
    echo "<h4>Checking methods ... </h4>\n";flush();
    $this->ChkMethods();
    
    echo "<h4>Checking classes ... </h4>\n";flush();
    $this->ChkClasses();    
    
    $t = round($timer->Pop()/1000,2);
    $t = "$t".'s';
    
    if( $this->iErrCnt > 0 ) 
    	$color='red'; 
    else 
    	$color='blue';
    echo " &nbsp; <p> <font color=$color><b>Found ".$this->iErrCnt." errors.</b></font>";
    
    echo '<hr> ';
    echo "\n<table width=100%><tr><td>Time: $t </td><td align=right> <form><input type=button value='Close Windows' onclick='window.close();'></form></td></tr></table>";
  }


}

class DBCheckDriver {
  var $iProjidx;
  var $iDB,$iDBUtils;

  function DBCheckDriver($aProjidx) {
    $this->iDB = DBFactory::InitDB();
    $this->iDBUtils = new DBUtils($this->iDB);
    $this->iProjidx = $aProjidx;
  }

  function GetListOfProjects() {
  	$list=array();
  	$this->iDBUtils->GetProjects($list);
  	$n = count($list)/2;
  	$t = '<table border=0>';
  	for( $i=0; $i<$n; ++$i ) {
  		$t .= '<tr><td> &nbsp; <a href="ddda_chkdb.php?idx='.$list[$i*2+1].'">'.$list[$i*2].'</a> &nbsp; </td></tr>';
  	}
  	$t .= '</table>';
  	return $t;
  }

  function Run() {
    if( isset($this->iProjidx) && $this->iProjidx > 0 ) {
      $dbchecker = new CheckProjectDB($this->iDB);
      $projname = $this->iDBUtils->GetProjNameForKey($this->iProjidx);
      if( empty($projname) ) {
		die("<font color=red><b>Error: Invalid project index [$this->iProjidx].<b></font>");
      }
	  HTMLGenerator::DocHeader('DB Consistency check: '.$projname);
      $dbchecker->Run($projname);
    } 
    else {
      echo "<h2>DB Consistency check</h2><hr>";
      echo "Select project to check. <p>";
      echo $this->GetListOfProjects();	
      //echo "<b><font color=red>Error: No project index specified. </font></b><br>Usage: ddda_chkdb.php?idx=<i>nn</i>";
    }
  }
}
 
$idx    = @$HTTP_GET_VARS['idx'] ;
if( !isset($idx) )
     $idx=0;

$driver = new DbCheckDriver($idx);
$driver->Run();

?>