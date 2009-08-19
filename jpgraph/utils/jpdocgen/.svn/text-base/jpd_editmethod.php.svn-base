<?php
//=======================================================================
// File:		JPD_EDITMETHOD.PHP
// Description:	Edit method information
// Created: 	2001-04-15
// Author:		Johan Persson (johanp@aditus.nu)
// Ver:			$Id: jpd_editmethod.php,v 1.4 2002/06/22 23:57:07 aditus Exp $
//
// License:	This code is released under QPL 1.0 
// Copyright (C) 2001,2002 Johan Persson 
//========================================================================

include "jpdb.php";
include "de_utils.php";

class EditMethodTable extends DBTableEdit {
	function EditMethodTable($aDBUtils,$aNumArgs=0,$aTitle='Edit method') {
		parent::DBTableEdit('editmethod',$aDBUtils);

		$aDBUtils->GetMethodList($ml);	
		$yn = array("  Private  ",0,"  Public  ",1);
		// (fldname,row,col,span,label,labelpos,fldpos,fldtype,targ1,targ2)		
		$formSpec = array(
			array('shortdesc',1,1,3,'Summary:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,80,200),
			array('return',$aNumArgs+2,1,3,'Returns:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,80,200),			
			array('desc',$aNumArgs+3,1,3,'Description:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTAREA,60,6),			
			array('methref1',$aNumArgs+4,1,3,'See also:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$ml),
			array('methref2',$aNumArgs+5,1,3,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$ml),
			array('methref3',$aNumArgs+6,1,3,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$ml),			
			array('methref4',$aNumArgs+7,1,3,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$ml),
			array('example',$aNumArgs+8,1,3,'Example:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTAREA,60,4),
			array('public',$aNumArgs+9,1,1,'Scope:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$yn),			
			array('timestamp',$aNumArgs+9,2,1,'Last edit:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TIMESTAMP),
			array('_x_save',$aNumArgs+9,3,1,'',LBLPOS_LEFT,FLDPOS_RIGHT,FLDTYPE_SAVE),									
		);
		if( $aNumArgs > 0 ) {		
			$r = 2;
			$formSpec[] = array('arg1',  $r,1,1,'Argument:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,20,80,true);
			$formSpec[] = array('argdes1', $r,2,2,'Description:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,50,80);
			++$r;		
			for( $i=2; $i <= $aNumArgs; ++$i ) { 
				$formSpec[] = array('arg'.$i,  $r,1,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,20,80,true);
				$formSpec[] = array('argdes'.$i, $r,2,2,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,50,80);
				++$r;
			}		
		}
		$hidden = array('file','linenbr','classidx','numargs','name','classname');	
		$this->Set('tbl_method'.$aDBUtils->iProject,$formSpec,$hidden);
		$this->iFormLayout->SetTitle($aTitle);				
	}
	
	function Verify($aValues,&$aErrFld) {
		return true;
	}
}

class MethEdDriver extends DocEditDriver {
	function MethEdDriver() {
		$this->DocEditDriver();
	}
	function Run($aKey='') {
		global $HTTP_POST_VARS;
		
		HTMLGenerator::DocHeader('Edit DDDA Class','Modify or create existing class');
		HTMLGenerator::DocPreamble();		
		
		$HTTP_POST_VARS['key'] = $aKey;
		$r = $this->iDBUtils->GetMethodKey($aKey);		
		$title = '<b><font face=arial>'.$r['fld_classname'].'::'.$r['fld_name'].'()</b></font>';
		$e = new EditMethodTable($this->iDBUtils,$r['fld_numargs'],$title);
		$e->Run($HTTP_POST_VARS,array('name'=>'<b><font face=arial>'.$r['fld_classname'].'::'.$r['fld_name'].'()</b></font>'));
	}
}

$d = new MethEdDriver();
$key = @$HTTP_GET_VARS['key']+0;
$d->Run($key);
$d->Close();

?>
