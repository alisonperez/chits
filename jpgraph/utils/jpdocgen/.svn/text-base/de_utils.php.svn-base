<?php
//=======================================================================
// File:		DE_UTILS.PHP
// Description:	Doc Edit Utilities
// Created:		2002-04-15
// Author:		Johan Persson (johanp@aditus.nu)
// Ver: 		$Id: de_utils.php,v 1.23 2002/06/27 09:54:44 aditus Exp $
//
// License: QPL 1.0
// Copyright (C) 2002 Johan Persson
//=======================================================================

$yesno_array=array(" ","","&nbsp  Ja  &nbsp","1","&nbsp  Nej  &nbsp","2");

// --------------------------------------------------------------
// MySQL setup. You need to change these defines to suit your
// own setup
// --------------------------------------------------------------

DEFINE( 'DBSERVER',	'localhost'	);
DEFINE( 'DBUSER',	'root'       	);
DEFINE( 'DBPWD',	''		);
DEFINE( 'DBNAME',	'jpgraph_doc'	);

//===================================================
// CLASS JpgTimer
// Description: General timing utility class to handle
// timne measurement of generating graphs. Multiple
// timers can be started by pushing new on a stack.
//===================================================
class JpgTimer {
    var $start;
    var $idx;	
//---------------
// CONSTRUCTOR
    function JpgTimer() {
	$this->idx=0;
    }

//---------------
// PUBLIC METHODS	

    // Push a new timer start on stack
    function Push() {
	list($ms,$s)=explode(" ",microtime());	
	$this->start[$this->idx++]=floor($ms*1000) + 1000*$s;	
    }

    // Pop the latest timer start and return the diff with the
    // current time
    function Pop() {
	assert($this->idx>0);
	list($ms,$s)=explode(" ",microtime());	
	$etime=floor($ms*1000) + (1000*$s);
	$this->idx--;
	return $etime-$this->start[$this->idx];
    }
} // Class


class HTMLGenerator {
	
function DocHeader($title, $desc='' , $keywords='', $stylesheet='de_normal.css') {


		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<HTML><HEAD>
		<META name=\"description\" content=\"$desc\">
		<META name=\"keywords\" content=\"$keywords\">	
		<META HTTP-EQUIV=\"Expires\" CONTENT=\"Fri Jun 12 1981 08:20:00 GMT\">
		<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">
		<META HTTP-EQUIV=\"Cache-control\" CONTENT=\"no-cache\">
		<LINK REL=STYLESHEET TYPE=\"text/css\" HREF=\"". $stylesheet ."\">
		<title>". $title ."</title></head>";		
	}

	function DocPreamble() {
		echo "\n<body>\n";
	}
	
	function DocPostamble() {
		echo "\n</body></html>\n";
	}

	function CloseWinButton($aAlign='right') {
		echo "<span align=$aAlign><form><input type=button value='Close Windows' onclick='window.close();'></form></span>";		
	}

	//-------------------------------------------------------------
	// SelectCode() - Where value is different from displayed label
	//-------------------------------------------------------------
	function SelectCode($name,$option,$selected="",$size=0,$js="",$cssclass="") {
		$txt="<select name=$name";
		if( $size > 0 )
			$txt .= " size=$size ";
		if( $cssclass != "" )
			$txt .= " class=$cssclass>\n ";
		else
			$txt .= " $js>";		
		for($i=0; $i<count($option); $i += 2) {
			if( $selected==$option[($i+1)] )
				$txt=$txt."<option selected value=".$option[($i+1)].">$option[$i]</option>\n";		
			else
				$txt=$txt."<option value=\"".$option[($i+1)]."\">$option[$i]</option>\n";
		}
		return $txt."</select>\n";
	}
	
	//-------------------------------------------------------------
	// Select() - Where value is same as displayed value
	//-------------------------------------------------------------
	function Select($name,$option,$selected="",$size=0,$js="",$cssclass="") {
		$txt="<select name=$name";
		if( $size > 0 )
			$txt .= " size=$size ";
		if( $cssclass != "" )
			$txt .= " class=$cssclass>\n ";
		else
			$txt .= " $js>";
		for($i=0; $i<count($option); $i++) {
			if( $selected==$option[$i] )
				$txt=$txt."<option selected value=\"$option[$i]\">$option[$i]</option>\n";		
			else
				$txt=$txt."<option value=\"".$option[$i]."\">$option[$i]</option>\n";
		}
		return $txt."</select>\n";
	}

	//-------------------------------------------------------------
	// TransCodetable()
	//-------------------------------------------------------------
	function TransCodetable($code,$table) {
		for($i=0; $i<count($table); $i += 2) {
			if( $code==$table[($i+1)] ) return $table[$i];
		}
		return "[UNKNOWN CODE $code]";
	}
	
	function CheckBox($name,$value,$ismarked=false) {
		$c = $ismarked ? ' CHECKED ' : '';
		return "<input type=checkbox name=$name value=$value $c>";
	}
	
	function Radio($name,$options,$selected='',$maxperrow=0) {
		$n = count($options);
		$t = '';
		$rc = 0;
		$t .= '<table width="100%" cellspacing=2 ><tr>';
		for($i=0; $i < $n; ++$i ) {
			$s = '';
			if( $selected == $options[$i] ) 
				$s = ' CHECKED ';
			$t .= "<td> <input type=radio name=$name $s value=\"$options[$i]\">$options[$i] </td>\n";
			++$rc;
			if( $maxperrow>0 && $rc>=$maxperrow ) {
				$t .= "</tr>\n<tr>\n";
			 	$rc = 0;
			}
		}		
		return $t.'</tr></table>';
	}

	function RadioCode($name,$options,$selected='',$maxperrow=0) {
		$n = count($options);
		$t = '';
		$rc=0;
		$t .= '<table width="100%" cellspacing=5 ><tr>';
		for($i=0; $i < $n; $i+=2 ) {
			$s = '';
			if( $selected == $options[$i+1] ) 
				$s = ' CHECKED ';
			$t .= "<td> <input type=radio name=$name $s value=\"".$options[$i+1]."\">$options[$i] </td>\n";
			++$rc;
			if( $maxperrow>0 && $rc>=$maxperrow ) {
				$t .= "</tr>\n<tr>\n";
			 	$rc = 0;
			}
			
		}		
		return $t.'</tr></table>';
	}	

	function Reset($name,$val,$enabled=true) {
		$t = $enabled ? '' : 'DISABLED';
		return "<INPUT TYPE=reset name=$name $t value=\"$val\">";
	}

	function InputText($name,$val,$len,$maxlen=100) {
		return '<INPUT TYPE=TEXT NAME='.$name.' SIZE='.$len.' MAXLENGTH='.$maxlen.'>';
	}

	function Button($name,$val='',$onclick='',$enabled=true ) {
		if( $val == '' )
			$val = ' Ok ';
		$t = $enabled ? '' : 'DISABLED';
		$o = $onclick != '' ? "onclick=\"$onclick\" " : ''; 
		return "<INPUT TYPE=button name=$name $t value=\"$val\" $o>";
	}
	
	function Submit($name,$val='',$onclick='',$enabled=true) {
		if( $val == '' )
			$val = ' Ok ';
		$t = $enabled ? '' : 'DISABLED';
		$o = $onclick != '' ? "onclick=\"$onclick\" " : ''; 
		return "<INPUT class=submitbutton TYPE=submit name=$name $t value=\"$val\" $o>";
	}
	
	function FileInput($name,$val,$enabled=true) {
		$t = $enabled ? '' : 'DISABLED';
		return "<INPUT TYPE=file name=$name $t value=\"$val\">";
	}
	
}
 
 
class DBUtils {
	var $iDBServer;
	var $iProject='';
	
	
	var $sql_create_table_project =
		"CREATE TABLE tbl_projects (
		 fld_key int(11) NOT NULL auto_increment,
		 fld_name varchar(80) NOT NULL default '',
		 fld_docdir varchar(255) NOT NULL default '',
		 fld_doctype tinyint(4) NOT NULL default '0',
		 fld_showprivate tinyint(4) NOT NULL default '0',
		 fld_lang varchar(10) NOT NULL default '',
		 fld_desc text NOT NULL,
		 fld_timestamp timestamp(14) NOT NULL,
		 PRIMARY KEY  (fld_key)
		 ) TYPE=MyISAM;" ;

	var $sql_create_table_projfiles =
		"CREATE TABLE tbl_projfiles (
		 fld_key int(11) NOT NULL auto_increment,
		 fld_name varchar(255) NOT NULL default '',
		 fld_desc text,
		 fld_projidx int(11) NOT NULL default '0',
		 fld_dbupdtime datetime default NULL,
		 fld_timestamp timestamp(14) NOT NULL,
		 PRIMARY KEY  (fld_key)
		) TYPE=MyISAM;" ;
		
	var $sql_create_table_class = 
		" CREATE TABLE tbl_class_%s (
  		  fld_key int(11) NOT NULL auto_increment,
  		  fld_name varchar(40) NOT NULL default '',
  		  fld_public tinyint(4) NOT NULL default '1',
  		  fld_parentname varchar(40) default NULL,
  		  fld_file varchar(255) NOT NULL default '',
  		  fld_linenbr int(11) NOT NULL default '0',
  		  fld_numfuncs int(11) NOT NULL default '0',
  		  fld_desc text,
		  fld_ref1 varchar(40) default NULL,
		  fld_ref2 varchar(40) default NULL,
		  fld_ref3 varchar(40) default NULL,
		  fld_ref4 varchar(40) default NULL,
		  fld_timestamp timestamp(14) NOT NULL,
		  PRIMARY KEY  (fld_key)
		  ) TYPE=MyISAM;"	;

	var $sql_create_table_method = 
		" CREATE TABLE tbl_method_%s (
		  fld_key int(11) NOT NULL auto_increment,
		  fld_name varchar(40) NOT NULL default '',
		  fld_public tinyint(4) NOT NULL default '1',
		  fld_linenbr int(11) NOT NULL default '0',
		  fld_file varchar(255) NOT NULL default '',
		  fld_classidx int(11) default NULL,
		  fld_classname varchar(50) NOT NULL default '',
		  fld_shortdesc tinytext,
		  fld_desc text,
		  fld_return varchar(200) NOT NULL default '',
		  fld_example text,
		  fld_methref1 int(11) default NULL,
		  fld_methref2 int(11) default NULL,
		  fld_methref3 int(11) default NULL,
		  fld_methref4 int(11) default NULL,
		  fld_methref5 int(11) default NULL,
		  fld_numargs tinyint(4) default NULL,
		  fld_arg1 varchar(40) default NULL,
		  fld_arg2 varchar(40) default NULL,
		  fld_arg3 varchar(40) default NULL,
		  fld_arg4 varchar(40) default NULL,
		  fld_arg5 varchar(40) default NULL,
		  fld_arg6 varchar(40) default NULL,
		  fld_arg7 varchar(40) default NULL,
		  fld_arg8 varchar(40) default NULL,
		  fld_arg9 varchar(40) default NULL,
		  fld_arg10 varchar(40) default NULL,
		  fld_argdes1 varchar(80) default NULL,
		  fld_argdes2 varchar(80) default NULL,
		  fld_argdes3 varchar(80) default NULL,
		  fld_argdes4 varchar(80) default NULL,
		  fld_argdes5 varchar(80) default NULL,
		  fld_argdes6 varchar(80) default NULL,
		  fld_argdes7 varchar(80) default NULL,
		  fld_argdes8 varchar(80) default NULL,
		  fld_argdes9 varchar(80) default NULL,
		  fld_argdes10 varchar(80) default NULL,
		  fld_argval1 varchar(30) NOT NULL default '',
		  fld_argval2 varchar(30) NOT NULL default '',
		  fld_argval3 varchar(30) NOT NULL default '',
		  fld_argval4 varchar(30) NOT NULL default '',
		  fld_argval5 varchar(30) NOT NULL default '',
		  fld_argval6 varchar(30) NOT NULL default '',
		  fld_argval7 varchar(30) NOT NULL default '',
		  fld_argval8 varchar(30) NOT NULL default '',
		  fld_argval9 varchar(30) NOT NULL default '',
		  fld_argval10 varchar(30) NOT NULL default '',		  
		  fld_timestamp timestamp(14) NOT NULL,
		  PRIMARY KEY  (fld_key)
		) TYPE=MyISAM;";
		
	var $sql_create_table_classvars = 
		" CREATE TABLE tbl_classvars_%s (
		  fld_key int(11) NOT NULL auto_increment,
		  fld_name varchar(30) NOT NULL default '',
		  fld_public tinyint(4) NOT NULL default '1',
		  fld_default varchar(30) default NULL,
		  fld_desc tinytext,
		  fld_classidx int(11) NOT NULL default '0',
		  fld_timestamp timestamp(14) NOT NULL,
		  PRIMARY KEY  (fld_key)
		) TYPE=MyISAM;";
	

	function DBUtils($aDBServer) {
		$this->iDBServer = $aDBServer;
	}
	
	function SetProject($aProj) {
		if( trim($aProj) != '' ) {
			$this->iProject = '_'.$aProj;
		}
		else
			$this->iProject = '';
	}
	
	function ExistTableProjects() {
		$res = $this->iDBServer->Query("SELECT * FROM tbl_projects",true);
		if( $res == false ) return false;
		else return true;
	}
	
	function SetupNewDB() {
		$this->iDBServer->Query($this->sql_create_table_project);
		$this->iDBServer->Query($this->sql_create_table_projfiles);
	}
	
	function CreateNewTablesForProject($aProjName) {
		$this->iDBServer->Query(sprintf($this->sql_create_table_class,$aProjName));
		$this->iDBServer->Query(sprintf($this->sql_create_table_classvars,$aProjName));
		$this->iDBServer->Query(sprintf($this->sql_create_table_method,$aProjName));
	}
		
	function GetList(&$aList,$aFlds, $aTable, $aWhere='') {
		$q = "SELECT ".$aFlds[0];
		$nf = count($aFlds);
		$i = 1;
		while( $i < $nf ) {
			$q .= ",".$aFlds[$i];
			++$i;
		}
		$q .= " FROM $aTable ";
		if( $aWhere != '' )
			$q .= ' WHERE '.$aWhere;
		$q .= " ORDER BY fld_name "; 
		$res = $this->iDBServer->Query($q);
		$n = $res->NumRows();
		$i = 0;
		while( $i < $n ) {
			$row = $res->Fetch();
			for( $j=0; $j< $nf; ++$j )
				$aList[] = $row[$j];//$row[$aFlds[$j]];
			++$i;
 		}
	}
	
	function GetClassListNameKeyPublic(&$aList) {
		$aList = array(" ",-1,0);
		$this->GetList($aList,array('fld_name','fld_key','fld_public'),'tbl_class'.$this->iProject);
	}
	
	function GetClassList(&$aList) {
		$aList = array(" ");
		$this->GetList($aList,array('fld_name'),'tbl_class'.$this->iProject);
	}

	function GetClassKey($aKey) {
		$q = "SELECT * FROM tbl_class".$this->iProject." WHERE fld_key='".$aKey."'";
		$res = $this->iDBServer->Query($q);
		return  $res->Fetch();
	}
	
	function DropProjTables($aKey) {
		$projname=$this->GetProjNameForKey($aKey);
		$q = 'DROP TABLE tbl_class_'.$projname.', tbl_method_'.$projname.', tbl_classvars_'.$projname;
		$this->iDBServer->Query($q);
	}
	
	function GetMethodList(&$aList) {
		$aList = array(" ",-1);
		$this->GetList($aList,
			array("CONCAT(fld_classname,'::',fld_name) as fld_name", 'fld_key'),'tbl_method'.$this->iProject);
	}
	
	function GetMethodKey($aKey) {
		$q = "SELECT * FROM tbl_method".$this->iProject." WHERE fld_key=".$aKey;
		$res = $this->iDBServer->Query($q);
		return $res->Fetch();
	}
	
	function GetNumMethods($aKey) {
		$q = "SELECT COUNT(*) FROM tbl_method".$this->iProject." WHERE fld_classidx=$aKey";
		$res = $this->iDBServer->Query($q);
		$r = $res->Fetch();		
		return $r[0];
	}

	function GetMethodsForClassKeyR($aKey) {
		$q = "SELECT * FROM tbl_method".$this->iProject." WHERE fld_classidx=$aKey ORDER BY fld_name";
		$res = $this->iDBServer->Query($q);
		return $res;
	}

	
	function GetMethodListForClassKey(&$aList,$aClassIdx) {
		$this->GetList($aList,
			array("CONCAT(fld_classname,'::',fld_name) as fld_name", 'fld_key') ,
			'tbl_method'.$this->iProject,"fld_classidx=$aClassIdx");
	}	
	
	function GetProject($aName='') {
		if( $aName == '' ) 
			$aName = substr($this->iProject,1);
		$q = 'SELECT * FROM tbl_projects WHERE fld_name='."'$aName'";
		$res = $this->iDBServer->Query($q);
		$r = $res->Fetch();
		return $r;
	}	
	
	function GetProjects(&$aList,$aFill=0) {
		$this->GetList($aList,array('fld_name', 'fld_key'),'tbl_projects');
		$this->PadDisplayName($aList,$aFill);
	}		

	function GetProjectFiles(&$aList,$aProjIdx,$aFill=0) {
		$this->GetList($aList,
			array('fld_name', 'fld_key'),'tbl_projfiles',' fld_projidx='.$aProjIdx);
		$this->PadDisplayName($aList,$aFill);			
	}		
	
	function GetProjDir($aName) {
		$q = "SELECT fld_docdir FROM tbl_projects WHERE fld_name='".$aName."'";
		$res = $this->iDBServer->Query($q);
		$r = $res->Fetch();
		return $r['fld_docdir'];		
	}	

	function GetShowPrivate($aName) {
		$q = "SELECT fld_showprivate FROM tbl_projects WHERE fld_name='".$aName."'";
		$res = $this->iDBServer->Query($q);
		$r = $res->Fetch();
		return $r['fld_showprivate'];		
	}		

	function GetProjNameForKey($aKey) {
		$q = 'SELECT fld_name FROM tbl_projects WHERE fld_key='.$aKey;
		$res = $this->iDBServer->Query($q);
		$r = $res->Fetch();
		return $r['fld_name'];
	}	
		
	function PadDisplayName(&$aList,$aFill) {
		if( $aFill > 0 ) {
			$n = count($aList);			
			for($i=0; $i < $n; $i += 2 ) {
				$len = strlen($aList[$i]);
				if( $len < $aFill ) {
					$ps = '';
					while($len++ < $aFill) 
						$ps .= '&nbsp;';  
					$aList[$i] .= $ps;
				}	
			}
		}
	}	
}


// Must have a global comparison method for usort()
function _cmp($a,$b) {
	if ($a[1] == $b[1]) {
		if( $a[2] == $b[2] ) return 0;
		return ($a[2] > $b[2]) ? 1 : -1;		
	}
   	return ($a[1] > $b[1]) ? 1 : -1;		
}

DEFINE('FLDTYPE_TEXTINPUT',1);
DEFINE('FLDTYPE_TEXTAREA',2);
DEFINE('FLDTYPE_DROPDOWN',3);
DEFINE('FLDTYPE_DROPDOWNCODE',4);
DEFINE('FLDTYPE_STATICTEXT',5);
DEFINE('FLDTYPE_STATICTEXTCODE',6);
DEFINE('FLDTYPE_NONDBTEXT',7);
DEFINE('FLDTYPE_TIMESTAMP',8);
DEFINE('FLDTYPE_RADIO',9);
DEFINE('FLDTYPE_RADIOCODE',10);
DEFINE('FLDTYPE_CHECKBOX',11);
DEFINE('FLDTYPE_FILEINPUT',12);
DEFINE('FLDTYPE_PWD',13);

DEFINE('FLDTYPE_SUBMIT',20);
DEFINE('FLDTYPE_SAVE',21);
DEFINE('FLDTYPE_CLEARSAVE',22);
DEFINE('FLDTYPE_DELETESAVE',23);
DEFINE('FLDTYPE_DELETECLEARSAVE',24);
DEFINE('FLDTYPE_BUTTON',25);

DEFINE('FLDTYPE_HRULE',40);


DEFINE('MODIFY_HOOK',30);

DEFINE('LBLPOS_TOP',1);
DEFINE('LBLPOS_LEFT',2);
DEFINE('LBLPOS_RIGHT',3);

DEFINE('FLDPOS_LEFT',1);
DEFINE('FLDPOS_RIGHT',2);

class FormLayout {

// Parameters
//fldname,row,col,span,label,labelpos,fldpos,fldtype,targ1,targ2

	// sort on row & col
	var $iForm=array(),$iRows,$iHidden=array();
	var $iFormClass, $iCellClass;
	var $iAction;
	var $iSaveButtonLabel = '    Save    ';
	var $iClearButtonLabel = '    New    ';
	var $iDeleteButtonLabel = '    Delete    ';
	var $iShowDelete = false, $iShowClear = false;
	var $iBorder = 1, $iCellSpacing = 0, $iCellPadding = 5;
	var $iHTMLGen;
	var $iErrLabelClass = "errfldlabel";
	var $iNormLabelClass = "normfldlabel";
	var $iShowSave	= true;
	var $iReallyDeleteTxt = 'Really delete?';
	var $iFormName, $iFormClass='stdinputform';
	var $iAlign='center',$iTitle;
	
	function FormLayout($aFormName,$aAction,$aTitle='') {
		$this->iAction = $aAction;
		$this->iHTMLGen = new HTMLGenerator(); 
		$this->iFormName = $aFormName;
		$this->iTitle = $aTitle;
	}
	
	function SetTitle($aTitle) {
		$this->iTitle = $aTitle;
	}
	
	function SetCSSClass($aFormClass="",$aCellClass="") {
		$this->iFormClass = $aFormClass;
		$this->iCellClass = $aCellClass;	
	}
	
	function SetButtonLabels($aSave,$aDelete='',$aNew='') {
		$this->iSaveButtonLabel = $aSave;
		if( $aNew!='' ) $this->iClearButtonLabel = $aNew;
		if( $aDelete!='' ) $this->iDeleteButtonLabel = $aDelete;
	}
	
	function ShowDelete($aFlg = true ) {
		$this->iShowDelete = $aFlg;
	}
	
	function SetBorder($aBorder) {
		$this->iBorder = $aBorder;
	}
		
	function ShowClear($aFlg = true ) {
		$this->iShowClear = $aFlg;
	}
	
	function SetFormClass($aClass) {
		$this->iFormClass = $aClass;
	}
	
	function Stroke($aForm, $aHidden, $aValues, $aNonDBTexts=array(), $aErrFld=array() ) {
		$this->iForm = $aForm;		
		$this->iHidden = $aHidden;		
		$this->iRows = count($aForm);	

		// Find out number of table columns
		$max = 0;
		for($i=0; $i < $this->iRows; $i++) {
			$tmp = $this->iForm[$i][2] + $this->iForm[$i][3] - 1;
			if( $tmp > $max ) $max = $tmp;
		}
		$ncols = $max;
		
		if( empty($aValues['key']) )
			$aValues['key'] = '';		
			
				
		$t = '<form name="'.$this->iFormName.'" action="'.$this->iAction."\" method=post>\n";
		$t .= "<input type=hidden name=key value='".$aValues['key']."'>\n";
		$t .= "<input type=hidden name=_x_formname value='".$this->iFormName."'>\n";
		$nh = count($this->iHidden);
		foreach( $this->iHidden as $h )
			$t .= "<input type=hidden name=$h value='".$aValues[$h]."'>\n";
			
		$t .= "<div align=$this->iAlign><table cellspacing=$this->iCellSpacing cellpadding=$this->iCellPadding class=$this->iFormClass>\n";
		
		if( $this->iTitle != '' ) 
			$t .= "<tr><td class=stdinputtitle colspan=$ncols>$this->iTitle</td></tr>";
		
		usort($this->iForm,'_cmp');
		$newrow = true;
		$crow = 0;
		for($i=0; $i < $this->iRows; $i++) {

			if( $crow == 0 ) {
				while( $crow < $this->iForm[$i][1] - 1 ) {
					$t.= "\n<tr><td class=datainput colspan=$ncols> &nbsp; &nbsp; </td></tr>\n";
					++$crow;
				}
				++$crow;
				$t .= "<tr>\n";						
			}
			elseif( $crow < ($this->iForm[$i][1] - 1) ) {
				$t .= "</tr>\n";	
				while( $crow < $this->iForm[$i][1] - 1 ) {
					$t .= "\n</tr><td class=datainput colspan=$ncols> &nbsp; &nbsp; </td></tr>\n";
					++$crow;
				}
				$t .= "<tr>\n";	
				++$crow;
			}
			elseif( $crow == $this->iForm[$i][1]-1 ) {
				$t .= "\n</tr>\n<tr>\n";
				++$crow;
			}
				
			if( $i > 0 ) {				
				if( ($this->iForm[$i-1][1]==$this->iForm[$i][1]) && 
					($this->iForm[$i-1][2]+$this->iForm[$i-1][3] > $this->iForm[$i][2]) )
					die("<b>Form layout error</b><br>In row $crow Columns <b>".$this->iForm[$i-1][0]."</b> and <b>".$this->iForm[$i][0]."</b> overlaps. Please fix!");
				
				if( $this->iForm[$i-1][1] == $crow ) {
					$d = $this->iForm[$i][2] - ($this->iForm[$i-1][2] + $this->iForm[$i-1][3]);
					for( $j=0; $j<$d; ++$j )
						$t .= '<td class=datainput> &nbsp; &nbsp; </td>';
				}
				else {
					for( $j=1; $j<$this->iForm[$i][2]; ++$j )
						$t .= '<td class=datainput> &nbsp; &nbsp; </td>';
				}
			}				
				
			$lblclass = @$aErrFld[$this->iForm[$i][0]] ? $this->iErrLabelClass : $this->iNormLabelClass;	
			$cellalign = $this->iForm[$i][6] == FLDPOS_LEFT ? '' : ' align=right ';
			$tst = $this->iForm[$i][7] == FLDTYPE_SUBMIT || $this->iForm[$i][7] == FLDTYPE_SAVE ;
			$cellvalign = $tst ? 'valign=bottom' : '';
			
			if( $this->iForm[$i][5] == LBLPOS_TOP || $this->iForm[$i][5] == LBLPOS_LEFT ) {
				$t .= "<td class=datainput $cellalign $cellvalign colspan=".
					$this->iForm[$i][3].'><span class="'.$lblclass.'">'.$this->iForm[$i][4].'</span>';
			
				if( $this->iForm[$i][5] == LBLPOS_TOP ) 
					$t .= "<br>";
				else
					$t .= ' ';
			}
			else {
				$t .= "<td class=datainput $cellalign $cellvalign colspan=".$this->iForm[$i][3].'>';
			}
			
		
			$val = @$aValues[$this->iForm[$i][0]];
			switch( $this->iForm[$i][7] ) {
				case FLDTYPE_HRULE :
					$t .= "<hr>";
					break;
				case FLDTYPE_FILEINPUT :
					//echo "val=$val ";
					$t .= '<input type=file name='.$this->iForm[$i][0].' value="'.$val.'" ';
					$t .= 'size='.$this->iForm[$i][8].' maxlength='.$this->iForm[$i][9].'>';				
					break;
				case FLDTYPE_TEXTINPUT :
					//<input type=text name=modell value=\"$flds[fld_modell]\" size=15 maxlength=20>
					$t .= '<input type=text name='.$this->iForm[$i][0].' value="'.$val.'" ';
					$t .= 'size='.$this->iForm[$i][8].' maxlength='.$this->iForm[$i][9];
					if( !empty($this->iForm[$i][10]) )
						$t .= ' readonly style="font-family:courier new;font-weight:bold;">';
					else
						$t .= '>'; 				
					break;				
					
				case FLDTYPE_DROPDOWNCODE :
					if( !empty($this->iForm[$i][9]) )
						$size = $this->iForm[$i][9];
					else
						$size = 0;
					$t .= $this->iHTMLGen->SelectCode($this->iForm[$i][0],$this->iForm[$i][8],$val,$size);
					break;
				case FLDTYPE_DROPDOWN :
					if( !empty($this->iForm[$i][9]) )
						$size = $this->iForm[$i][9];
					else
						$size = 0;				
					$t .= $this->iHTMLGen->Select($this->iForm[$i][0],$this->iForm[$i][8],$val,$size);
					break;					
				case FLDTYPE_TEXTAREA :
					$t .= "<textarea cols=".$this->iForm[$i][8]." rows=".$this->iForm[$i][9]." name=\"".$this->iForm[$i][0]."\">".$val."</textarea>";
					break;
				case FLDTYPE_STATICTEXTCODE :
					$val = $this->iHTMLGen->TransCodetable($val,$this->iForm[$i][8]);
				case FLDTYPE_STATICTEXT :
					$tmp = $val;
					if( $tmp == '' ) $tmp =' &nbsp; &nbsp; ';
					$t .= $tmp;
					$t .= "<input type=hidden name=".$this->iForm[$i][0]." value='".$val."'>";
					break;
				case FLDTYPE_NONDBTEXT :
					if( !empty($this->iForm[$i][8]) )
						$tmp = $this->iForm[$i][8];
					else
						$tmp = @$aNonDBTexts[$this->iForm[$i][0]];
					if( $tmp == '' ) $tmp =' &nbsp; &nbsp; ';
					$t .= $tmp;
					break;
					
				case FLDTYPE_TIMESTAMP :		
					if( $val == '' ) 
						$t .= '&nbsp; &nbsp; ';
					else
						$t .= $val = substr($val,0,4)."-".substr($val,4,2)."-".substr($val,6,2)." ".substr($val,8,2).":".substr($val,10,2);
					break;
					
				case FLDTYPE_CHECKBOX :
					// name,value,checked
					if( !empty($this->iForm[$i][8]) )
						$v = $this->iForm[$i][8];
					else
						$v = 1;
					$t .= $this->iHTMLGen->CheckBox($this->iForm[$i][0],$v,$val);
					break;

				case FLDTYPE_RADIO :
					if( !empty($this->iForm[$i][9]) )
						$cols = $this->iForm[$i][9];
					else
						$cols = 0;
					$t .= $this->iHTMLGen->Radio($this->iForm[$i][0],$this->iForm[$i][8],$val,$cols);
					break;
					
				case FLDTYPE_RADIOCODE :
					if( empty($this->iForm[$i][9]) )
						$this->iForm[$i][9] = 0;
					$t .= $this->iHTMLGen->RadioCode($this->iForm[$i][0],$this->iForm[$i][8],$val,$this->iForm[$i][9]);
					break;

				case FLDTYPE_DELETECLEARSAVE :
					if( $aValues['key'] != '' )
						$t .= $this->iHTMLGen->Submit('button_delete',$this->iDeleteButtonLabel,"return confirm('".$this->iReallyDeleteTxt."')");
					$this->iShowDelete = false;
					// No break here, fall through to next case !

				case FLDTYPE_CLEARSAVE :
					$t .= ' '.$this->iHTMLGen->Submit('button_clear',$this->iClearButtonLabel);
					$this->iShowClear = false;
					// No break here, fall through to next case !
					
				case FLDTYPE_SAVE :
					if( empty($this->iForm[$i][8]) )
						$val = $this->iSaveButtonLabel;			
					else
						$val = $this->iForm[$i][8];
					$t .= ' '.$this->iHTMLGen->Submit('button_save',$this->iSaveButtonLabel);
					$this->iShowSave = false;
					break;
					
				case FLDTYPE_SUBMIT :
					if( empty($this->iForm[$i][8]) )
						$val = '&nbsp: Ok &nbsp;';					
					else
						$val = $this->iForm[$i][8];
					$t .= $this->iHTMLGen->Submit($this->iForm[$i][0],$val);
					$this->iShowSave = false;					
 					break;					

				case FLDTYPE_BUTTON :
					if( empty($this->iForm[$i][8]) )
						$val = '&nbsp: Ok &nbsp;';					
					else
						$val = $this->iForm[$i][8];
					$t .= $this->iHTMLGen->Submit($this->iForm[$i][0],$val,$this->iForm[$i][9]);
 					break;									
					
				default:
					die("Unknown field type.");
					break;
			}
			if( $this->iForm[$i][5] == LBLPOS_RIGHT ) {
				$t .= '<span class="'.$lblclass.'">'.$this->iForm[$i][4].'</span>';
			}
			
			$t .= "</td>\n";
			
			// Is the next one at same row then find out how many empty
			// columns is in between
			if( $i < $this->iRows-1 ) {
				if( $this->iForm[$i+1][1] != $crow ) {
					for( $j=$this->iForm[$i][2]+$this->iForm[$i][3] - 1; $j<$ncols; ++$j )
						$t .= '<td>&nbsp;</td>';	
				}
			}
			else {
				// fill out the last row
				for( $j=$this->iForm[$i][2]+$this->iForm[$i][3] - 1; $j<$ncols; ++$j )
						$t .= '<td>&nbsp;</td>';				
			}
		}
		$t .= "\n</tr>\n";
		
		// Setup automatic submit buttons
		if( $this->iShowClear || $this->iShowDelete || $this->iShowSave ) {
			$t .= "<tr>";
			$t .= "<td class=datainputbutton align=right colspan=$ncols>";
			$t .= $this->iShowClear ? "<input type=submit name=button_clear value=\"$this->iClearButtonLabel\">" : '&nbsp;';  
			$t .= $this->iShowDelete ? "\n<input type=submit name=button_delete onclick=\"return confirm('Really delete?')\" value=\"$this->iDeleteButtonLabel\" >" : '&nbsp;';		
			$t .= $this->iShowSave ? "\n<input class=submit type=submit name=button_save value=\"$this->iSaveButtonLabel\">\n" : '&nbsp;';
			$t .= "</td></tr>";		
		}
		$t .= "\n</table></div></form>";		
		return $t;
	}		
}

class DBTableEdit {
	var $iDBUtils;
	var $iDB;
	var $iTableName = "";
	var $iFormSpec=array() , $iHidden=array(), $iAction;
	var $iFormLayout;
	
	function DBTableEdit($aFormName,$aDBUtils,$aAction="") {
		$this->iDBUtils = $aDBUtils;
		$this->iDB = $aDBUtils->iDBServer;
		$this->iAction = $aAction;
		$this->iFormLayout = new FormLayout($aFormName,$this->iAction);		
	}	
	
	function ReportError($aErrFld) {
		$nbr=1;
		$t = "<b>The following fields have illegal values : </b><br>";
		while ( list ($field, $errmsg) = each ($aErrFld) ) {
			$t .= "<b><font color=red>$nbr. $errmsg</font></b><br>\n";
			++$nbr;
		}
		echo $t;
	}
	

	function Validate($aValues,&$aErrFld) {
		// Must be implemented by subclasses
		return true;
	}
		
	function DeleteRow($aKey) {
		$q="DELETE FROM $this->iTableName WHERE fld_key=$aKey";
		$this->iDB->Query($q);
	}
	
	function GetRow($aKey) {
		$q = "SELECT * FROM $this->iTableName WHERE fld_key=$aKey";
		$res = $this->iDB->Query($q);
		if( $res->NumRows() > 0 )
			return $res->Fetch();
		else
			return false;
	}
	
	function Set($aTableName,$aFormSpec,$aHidden=array()) {
		$this->iTableName = $aTableName;
		$this->iFormSpec = $aFormSpec;
		$this->iHidden = $aHidden;
	}
	
	function ModifyValuesHook(&$aValues) {
		// Virtual function to be used by subclass as a hook into
		// the disply engined
	}
	
	function ModifyArrayHook($aName,&$aPar1) {
		// Virtual function to be used by subclass as a hook into
		// the disply engined	
	}
	
	function NewPostHook($aValues) {
		// Gets called right after a new post has been inserted
	}
	
	function PreDeleteHook($aKey) {
		return false;
	}
	
	function PostDeleteHook($aKey) {
		return true;
	}
	
	function Run($aValues,$aNonDBTexts=array()) {
				
		$aErrFld = array();
							
		if( isset($aValues['button_delete']) && !empty($aValues['key']) ) {
			$t = $this->PreDeleteHook($aValues['key']);
			if( $t ) {
				$this->DeleteRow($aValues['key']);
				$this->PostDeleteHook($aValues['key']);
				$aValues = array();				
			}
		}
		elseif( isset($aValues['button_clear']) ) {
			$aValues = array();
			unset($aValues['key']);
		}
		elseif( isset($aValues['button_save']) ) {
			if( $this->Validate($aValues,$aErrFld) ) {
				$q = "REPLACE $this->iTableName SET ";
				while (list ($key, $val) = each ($aValues)) {
					if( !strstr($key,'button') && !strstr($key,'_x_') )
    					$q .= "fld_$key='".$val."', ";
				}
				$q .= "fld_timestamp=now()";
				$this->iDB->Query($q);
				if( empty($aValues['key']) ) {
					$aValues['key'] = mysql_insert_id(); 				
					$this->NewPostHook($aValues);
				}
			}
			else {
				$this->ReportError($aErrFld);
			}
		}
		
		$n = count($this->iFormSpec);
		for( $i=0; $i < $n; ++$i ) {
			if( ($this->iFormSpec[$i][7] == FLDTYPE_DROPDOWN ||
			    $this->iFormSpec[$i][7] == FLDTYPE_DROPDOWNCODE) &&
			    !is_array($this->iFormSpec[$i][8])) {
			    	
			    if( $this->iFormSpec[$i][8] == MODIFY_HOOK ){
			    	$this->iFormSpec[$i][8] = array();
			    	$this->ModifyArrayHook($this->iFormSpec[$i][0],$this->iFormSpec[$i][8]);			    	
			    }
			}
		}
		
		$this->ModifyValuesHook($aValues);
		
		if( !empty($aValues['key']) ) {
			$aRow = $this->GetRow($aValues['key']);
			while (list ($key, $val) = each ($aRow)) {				
				if( !is_integer($key) ) {
					$aValues[substr($key,4)] = htmlentities($val);
				}
			}			
		}
		echo $this->iFormLayout->Stroke($this->iFormSpec, $this->iHidden, $aValues,$aNonDBTexts,$aErrFld);		
	}
}
 

class DocStat {
	var $iDB,$iDBUtils;

	function DocStat($aDBUtils) {
		$this->iDBUtils = $aDBUtils;
		$this->iDB = $aDBUtils->iDBServer;
	}
	
	// Find out summary statistics for a project
	function ProjStat($aProjName) {
		$cl=array();
		// Get all classes in the project
		$this->iDBUtils->GetClassListNameKeyPublic($cl);
		$nc = (count($cl)-3)/3;
		if( $nc == 1 ) 
			return array(0,0,0); 	// No classes
		$sum = 0;
		$totnm = 0;
		for( $i=1; $i <= $nc; ++$i ) {
			list($cname,$nm,$ma,$tp,$ap) = $this->ClassStat($cl[3*$i+1]);
			if( $tp==0 ) 
				die( "PANIC: Total points for Class $cname is 0 actual points=$ap, nm=".(count($ma))."<br>\n" );
			$sum += (1.0 * ($ap/$tp));			
			$totnm += $nm;
		}
		$avg = $sum / ($nc);
		return array($avg,$nc,$totnm);		 
	}
	
	function ClassStat($aKey) {
		// Find out how many methods in this class
		$nm = $this->iDBUtils->GetNumMethods($aKey);

		// Calculate points for each class
		// Method description is weighted 5 to 1
		$res = $this->iDBUtils->GetMethodsForClassKeyR($aKey);
		$n = $res->NumRows();
		$points = 0;
		$tot = 0;
		$ma = array();
		for( $i=0; $i < $n; ++$i ) {
			$meth = $res->Fetch();
			$na = $meth['fld_numargs'];
			$argdescnt=0;
			for( $j=1; $j <= $na; ++$j ) {
				if( !empty($meth['fld_argdes'.$j]) && trim($meth['fld_argdes'.$j]) != '' ) {
					++$argdescnt;
				}
			}
			$sdes = (!empty($meth['fld_shortdesc']) && trim($meth['fld_shortdesc'])!='' ? 3 : 0);			
			$des = (!empty($meth['fld_desc']) && trim($meth['fld_desc'])!='' ? 5 : 0);			
			
			// For private methods we don't require the example to be filled in
			$ex = $meth['fld_public']==0 || (!empty($meth['fld_example']) && trim($meth['fld_example'])!='') ? 2 : 0;	
			$mp = round(($argdescnt+$des+$sdes+$ex)/($na+10)*100);
			$ma[] = array($meth['fld_public'],$meth['fld_name'],$meth['fld_key'],$mp);			
			
			$tot += ($na + 10);
			$points += ($argdescnt + $des + $sdes + $ex);		
		}
		
		// Don't do this for the pseudoclass for global functions which has index=0
		if( $aKey > 0 ) {
			// Calculate score for class
			// Class description is weighted 5 to 1
			$r = $this->iDBUtils->GetClassKey($aKey);
			$classpoints = !empty($r['fld_desc']) && trim($r['fld_desc'])!='' ? 5 : 0;
			$tot += 5;
			$points += $classpoints;
			// number of methds, Total possioble points, Actual points		
			return array($r['fld_name'],$nm,$ma,$tot,$points);
		}
		else
			return array('Global functions',$nm,$ma,$tot,$points);
	}
}



class GenJavascript {
	
	function Stroke() {

		$js = "<script language='JavaScript'>			
				function setprojcookie() {
					opt=document.mainform.s1_projidx.options[document.mainform.s1_projidx.selectedIndex];
					//alert('txt: '+opt.text);
					projidx=opt.value;
					if( projidx <= 0 ) {
						projname='';
						projidx = '';
						document.cookie = 'ddda_project=';
					}
					else {
						projname=opt.text;
						projcookie = 'ddda_project='+projname+':'+projidx;
						document.cookie= projcookie;
					}
					//confirm('Really quit');
					//document.mainform.submit();
				}
				
				function openPopup(url,width,height,name) {
					window.open(url,name,'width='+width+', height='+height+',left=50,top=50,resizable=yes,scrollbars=yes');
				}
				
				</script>\n";
				
		echo $js;
	}
}		


class Utils {

    function HighlightCodeSnippet($t,$bg=true) {
        $t = "<?php $t?>";
        ob_start();
        highlight_string($t);
        $t = ob_get_contents();	
        ob_end_clean();
        $t=str_replace('&lt;?php&nbsp;','',$t);
        $t=str_replace('?&gt;','',$t);
		$t=str_replace('<code>','',$t);	
		$t=str_replace('</code>','',$t);	
		
        if( $bg ) {
	        //$t = "<div style=\"background-color:#E1E1E1;font-family:courier new;font-size:90%;font-weight:bold;\"><b>$t</b></div>";
	        $t = "<div style=\"border-top:solid black 2pt;background-color:lightblue;font-family:courier new;font-size:90%;font-weight:bold;\"><b>$t</b></div>";
        }
        else {
	        $t = "<span style=\"font-family:courier;font-size:85%;\">$t</span>";
        }
        return $t;
    }
    
    function Error($aMsg) {
        die( "<font color=red><b>Error:</b></font> $aMsg" );
    }
    
    function Warning($aMsg) {
        echo "<font color=red><b>Warning:</b></font> $aMsg";
    }
    
    function DBTimeStampFormat($a) {
    	return substr($a,0,4).'-'.substr($a,4,2).'-'.substr($a,6,2).'  '.substr($a,8,2).':'.substr($a,10,2);
    }
}



class DocEditDriver {
	var $iDB;
	var $iDBUtils;
	var $iProjname='',$iProjidx=-1;
	
	function DocEditDriver() {
		global $HTTP_COOKIE_VARS;
		
    	$this->iDB = DBFactory::InitDB();    	
    	$this->iDBUtils = new DBUtils($this->iDB);  
    	
		$this->iProjname = strtok(@$HTTP_COOKIE_VARS['ddda_project'],':');
		
		//echo "cookie=$this->iProjname <p>";
				
		if( $this->iProjname != '' ) {
			$this->iProjidx = strtok(':');
			$this->iDBUtils->SetProject($this->iProjname);
		}  	 	
    }
    
	function Run() {
		// Must be implemented by subclass
	}
	
	function Close() {
		HTMLGenerator::DocPostamble();
	}
}

class DBFactory {
	
	// DBFactory::InitDB()
	function InitDB() {
		$db = new DBServer(DBUSER,DBPWD,DBSERVER);
    	if( !$db->SetDB(DBNAME,true) ) {
    		$db->Create(DBNAME);
    		echo "Created new database : ".DBNAME."<p>";
    		$db->SetDB(DBNAME);
    	}
    	
    	return $db;
	}
}


?>