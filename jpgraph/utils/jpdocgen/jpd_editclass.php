<?php
//=======================================================================
// File:		JPD_EDITCLASS.PHP
// Description:	Edit class information
// Created: 	2001-04-15
// Author:		Johan Persson (johanp@aditus.nu)
// Ver:			$Id: jpd_editclass.php,v 1.3 2002/07/03 23:32:12 aditus Exp $
//
// License:	This code is released under QPL 1.0 
// Copyright (C) 2001,2002 Johan Persson 
//=======================================================================

include "jpdb.php";
include "de_utils.php";

class EditClassTable extends DBTableEdit {
	function EditClassTable($aDBUtils,$aTitle) {
		parent::DBTableEdit('editclass',$aDBUtils);

		$aDBUtils->GetClassList($cl);	
		$yn = array("  Private  ",0,"  Public  ",1);
		$formSpec = array(
			array('file',1,1,2,'File:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_STATICTEXT),			
			array('linenbr',1,3,1,'Line:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_STATICTEXT),
			array('desc',2,1,3,'Description:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTAREA,60,20),							
			array('ref1',3,1,1,'See also:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWN,$cl),
			array('ref2',3,2,1,'&nbsp;',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWN,$cl),
			array('ref3',4,1,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_DROPDOWN,$cl),						
			array('ref4',4,2,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_DROPDOWN,$cl),
			array('public',5,1,1,'Scope:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$yn),
			array('timestamp',5,2,1,'Last edit:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TIMESTAMP),						
			array('_x_save',5,3,1,'',LBLPOS_LEFT,FLDPOS_RIGHT,FLDTYPE_SAVE),			
		);
			
		$hidden = array('name','parentname','numfuncs');	
		$this->Set('tbl_class'.$aDBUtils->iProject,$formSpec,$hidden);
		$this->iFormLayout->SetTitle($aTitle);		
	}
	
	function Verify($aValues,&$aErrFld) {
		return true;
	}
}

class ClassEdDriver extends DocEditDriver {
	function ClassEdDriver() {
		$this->DocEditDriver();
	}
	function Run($aKey='') {
		global $HTTP_POST_VARS;

		HTMLGenerator::DocHeader('Edit DDDA Class','Modify or create existing class');
		HTMLGenerator::DocPreamble();
		
		$HTTP_POST_VARS['key'] = $aKey;
		$r = $this->iDBUtils->GetClassKey($aKey);

		if( !empty($r['fld_parentname']) )
			$ext = ' <font color=lightgrey>extends '.$r['fld_parentname'].'</font>';
		else
			$ext = '';
			
		$e = new EditClassTable($this->iDBUtils,$r['fld_name'].$ext);
		$e->Run($HTTP_POST_VARS);
	}
}

$d = new ClassEdDriver();
$key = @$HTTP_GET_VARS['key']+0;
$d->Run($key);
$d->Close();

?>
