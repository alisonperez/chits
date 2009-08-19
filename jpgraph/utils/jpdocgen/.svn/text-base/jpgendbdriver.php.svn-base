<?php
//==============================================================================
// Name:        JPGENDBDRIVER.PHP
// Description:	Driver for scanning project files to update the DB
// Created: 	2002-06-06 22:50 
// Author:		johanp@aditus.nu
// Version: 	$Id: jpgendbdriver.php,v 1.8 2002/06/28 13:14:03 aditus Exp $
//
// License:	QPL 1.0
// Copyright (C) 2002 Johan Persson
//
//==============================================================================

include 'de_utils.php';
include 'jpgendb.php';

class ScanProjFiles {
	var $iProjname;
	var $iDBUtils;
	var $iDB;
	
	function ScanProjFiles($aDBUtils) {
		$this->iDBUtils = $aDBUtils;
		$this->iDB = $aDBUtils->iDBServer;
	}
	
	function Run($aProjname,$aForceUpdate=false) {
		$this->iProjname = $aProjname;
		
		echo "<h3>Scanning files for project '$aProjname'</h3>";
		
		// Find full filename of all project files in the project
		$proj = $this->iDBUtils->GetProject($aProjname);
		$projidx = $proj['fld_key'];
		
		$q = "SELECT * FROM tbl_projfiles WHERE fld_projidx=$projidx";
		$res = $this->iDB->Query($q);
		$n = $res->NumRows();
		$ptimer = new JpgTimer();
		while( $n-- > 0 ) {
			$r = $res->Fetch();
			$fname = $r['fld_name'];
			$modtime=filemtime($fname);
			$dbtime = strtotime($r['fld_dbupdtime']);
			if( $aForceUpdate || $modtime > $dbtime ) {
				echo "Parsing file $fname...\n";flush();
				$dbdriver = new DBDriver($aProjname,$fname,$this->iDB);
				$ptimer->Push();
				$dbdriver->Run();
				$t = round($ptimer->Pop()/1000,2);
				$q = "UPDATE tbl_projfiles SET fld_dbupdtime=now() WHERE fld_key=".$r['fld_key'];
				$this->iDB->Query($q);
				echo "[$t s]<br>\n";
			}
			else {
				echo "DB is up to date with file: '$fname'<br>\n";
			}
		}
		echo "<p><h3>Done.</h3>";
		HTMLGenerator::CloseWinButton();
	}
}

class DbGenDriver extends DocEditDriver {
	function Run($aForceUpdate=false) {
		if( !empty($this->iProjidx) && $this->iProjidx > 0 ) {
			$scan = new ScanProjFiles($this->iDBUtils);
			$projname = $this->iDBUtils->GetProjNameForKey($this->iProjidx);//$regen_projidx);
			$scan->Run($projname,$aForceUpdate);
		}
	}
}

$force    = @$HTTP_GET_VARS['force'] ;

if( isset($force) && $force=='true' )
	$force=1;
else
	$force=0;

$driver = new DbGenDriver();
$driver->Run($force);
$driver->Close();
?>