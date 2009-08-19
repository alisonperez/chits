<?php
//=======================================================================
// File:		jpd_editproject.php
// Description:	Form for edit a specified project
// Created:		2002-05-03
// Author:		Johan Persson (johanp@aditus.nu)
// Ver: 		$Id: jpd_editproject.php,v 1.8 2002/06/25 21:09:44 aditus Exp $
//
// License: QPL 1.0
// Copyright (C) 2002 Johan Persson
//=======================================================================

include "jpdb.php";
include "de_utils.php";


class EditProjects extends DBTableEdit {
	var $iLabelShow = ' Show ';
	var $iLabelShowFiles = ' Project files... ';
	function EditProjects($aDBUtils) {
		parent::DBTableEdit('projects',$aDBUtils,'jpd_editproject.php');
		$doctype = array(" &nbsp; HTML:Separate files &nbsp; ",0," &nbsp; HTML:Single file &nbsp; ",1);
		$formSpec = array(
			array('name',1,1,1,'Project name:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,38,40),
			//array('lang',1,2,1,'Language:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,10,20),
			array('timestamp',1,2,1,'Last edit:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TIMESTAMP),			
			array('desc',2,1,2,'Description:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTAREA,50,5),				
			array('docdir',3,1,1,'Output directory:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,38,80),
			array('doctype',3,2,1,'Output format:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,$doctype),
			array('showprivate',4,1,2,'Include private methods & classes',LBLPOS_RIGHT,FLDPOS_LEFT,FLDTYPE_CHECKBOX),
			array('',5,2,1,'',LBLPOS_LEFT,FLDPOS_RIGHT,FLDTYPE_DELETECLEARSAVE),					
			array('show_files',5,1,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_SUBMIT,$this->iLabelShowFiles),			
			array('_x_allprojects',7,1,2,'Existing projects:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,MODIFY_HOOK,3),					
			array('show_project',8,1,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_SUBMIT,$this->iLabelShow),
			array('',6,1,2,'',LBLPOS_LEFT,FLDPOS_RIGHT,FLDTYPE_HRULE),			
		);			
		$this->Set('tbl_projects',$formSpec);
		$this->iFormLayout->SetTitle('Edit projects');
		$this->iFormLayout->SetButtonLabels(' Save ',' Delete ',' Clear ');
	}

	
	function PreDeleteHook($aKey) {
		$this->iDBUtils->DropProjTables($aKey);
		return true;
	}

	function PostDeleteHook($aKey) {
		return true;
	}

	
	function Validate($aValues,&$aErrFld) {
		$r = true;
		
		$pos = strpos($aValues['name'], ' ');
		if( trim($aValues['name']) == '' || is_integer($pos) ) {
			$aErrFld['name'] = 'Project name: Name must not contain spaces.';
			$r = false;
		}
		
		if( strlen(trim($aValues['name'])) < 4 ) {
			$aErrFld['name'] = 'Project name: Name must be at least be 4 characters.';
			$r = false;
		}
		

		if( substr(trim($aValues['docdir']),-1) !='/' ) {
			$aErrFld['docdir'] = 'Output directory: Must end with a "/" charcter.';
			$r = false;
		}
		
		if( is_dir($aValues['docdir'])==false ) {
			$aErrFld['docdir'] = 'Output directory does not exist or is not a directory.';
			$r = false;
		}
		
		if( trim($aValues['desc']) == '' ) {
			$aErrFld['desc'] = 'Description: You must enter a description.';
			$r = false;
		}		
		
		if( !$r ) return false;
		
		if( $aValues['key'] == '' ) { // Must be a new post
			$this->iDBUtils->CreateNewTablesForProject($aValues['name']);
		}		
			
		return true;
	}

	function ModifyArrayHook($aName,&$aArr) {
		// We only have one hook so er don't bother to check that
		// it really is _x_allfiles
		$this->iDBUtils->GetProjects($aArr,90);
	}
	
	function ModifyValuesHook(&$aValues) {
		// Get's called just before the new post is read
		if( !empty($aValues['show_project']) && 
		     $aValues['show_project'] == $this->iLabelShow  &&	
		     !empty($aValues['_x_allprojects']) ) {
			$aValues['key'] = $aValues['_x_allprojects'];
		}
	}

}


class EditProjectFiles extends DBTableEdit {
	var $iLabelShow = ' Show ';
	var $iLabelBackToProjects = 'Back To Project';
	var $iProjIdx;
	function EditProjectFiles($aDBUtils,$aProjIdx) {
		parent::DBTableEdit('projfiles',$aDBUtils,'jpd_editproject.php');
		$this->iProjIdx = $aProjIdx;
		// Note: The prefix '_x_' indicates a field NOT in the DB
		$formSpec = array(
			array('projectname',1,1,2,'Project name:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_NONDBTEXT),
			array('name',2,1,1,'Filename:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTINPUT,30,255),
			array('desc',3,1,2,'Description:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_TEXTAREA,50,5),			
			array('dbupdtime',2,2,1,'DB Updated:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_STATICTEXT),	
			array('timestamp',4,1,1,'Last edit:',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_TIMESTAMP),
			array('',5,2,1,'',LBLPOS_LEFT,FLDPOS_RIGHT,FLDTYPE_DELETECLEARSAVE),
			array('show_projects',5,1,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_SUBMIT,$this->iLabelBackToProjects),	
			array('_x_allfiles',7,1,2,'Existing files in project:',LBLPOS_TOP,FLDPOS_LEFT,FLDTYPE_DROPDOWNCODE,MODIFY_HOOK,3),								
			array('show_file',8,1,1,'',LBLPOS_LEFT,FLDPOS_LEFT,FLDTYPE_SUBMIT,$this->iLabelShow),			
			array('',6,1,2,'',LBLPOS_LEFT,FLDPOS_RIGHT,FLDTYPE_HRULE),		
		);
			
		$hidden = array('projidx');	
		$this->Set('tbl_projfiles',$formSpec,$hidden);
		$this->iFormLayout->SetTitle('Project files');
		$this->iFormLayout->SetButtonLabels(' Save ',' Delete ',' Clear ');
	}
	
	function Validate($aValues,&$aErrFld) {
		
		$pos = strpos($aValues['name'], ' ');
		if( trim($aValues['name']) == '' || is_integer($pos) ) {
			$aErrFld['name'] = 'Name must not contain spaces.';
			return false;
		}
		else
			return true;		
	}

	function PreDeleteHook($aKey) {
		return true;
	}


	function ModifyArrayHook($aName,&$aArr) {
		// We only have one hook so er don't bother to check that
		// it really is _x_allfiles
		$this->iDBUtils->GetProjectFiles($aArr,$this->iProjIdx,75);
	}
	
	function ModifyValuesHook(&$aValues) {
		// Get's called just before the new post is read
		if( !empty($aValues['show_file']) && 
		     $aValues['show_file'] == $this->iLabelShow  &&	
		     !empty($aValues['_x_allfiles']) ) {
			$aValues['key'] = $aValues['_x_allfiles'];
		}
		
		// After a clear (or delete) all values are cleared
		// so we need to restore the project index
		$aValues['projidx'] = $this->iProjIdx ;
	}
}


class ProjectDriver extends DocEditDriver {
	function Run() {
		global $HTTP_POST_VARS;
		global $HTTP_GET_VARS;
		
		// We should go back to the project file form if we
		// a) either came from that form with a save/delete
		// b) we are going to that form from the project form
		if( empty($HTTP_POST_VARS['show_projects']) && ((!empty($HTTP_POST_VARS['show_files']) && !empty($HTTP_POST_VARS['key']) && 
		       $HTTP_POST_VARS['key'] > 0 &&  $HTTP_POST_VARS['_x_formname']=='projects') 
		    || @$HTTP_POST_VARS['_x_formname']=='projfiles') ) {
		    if( @$HTTP_POST_VARS['_x_formname']=='projfiles' )
		    	$projkey = $HTTP_POST_VARS['projidx'];
		    else {
		    	if( !empty($HTTP_POST_VARS['_x_allprojects']) )
		    		$projkey = 	$HTTP_POST_VARS['_x_allprojects'];
		    	else
		    		$projkey = $HTTP_POST_VARS['key'];
		    	$HTTP_POST_VARS = array();
		    }
			HTMLGenerator::DocHeader('Edit DDDA Project Files','Modify or Add Files To Project');
			HTMLGenerator::DocPreamble();
			GenJavascript::Stroke();
		    	
			$e = new EditProjectFiles($this->iDBUtils,$projkey);
			$pname = $this->iDBUtils->GetProjNameForKey($projkey);				
			$e->Run($HTTP_POST_VARS,array('projectname' => '<b>'.$pname.'</b>'));
			HTMLGenerator::CloseWinButton();
		}
		else {
			HTMLGenerator::DocHeader('Edit DDDA Project','Modify or create new DDDA projects');
			HTMLGenerator::DocPreamble();
			GenJavascript::Stroke();
			
			// For the case when we return from prtojfiles we set the current project key
			// so we get back to the same project
			if( @$HTTP_POST_VARS['_x_formname']=='projfiles' ) {	
				$key = @$HTTP_POST_VARS['projidx'];			
				$HTTP_POST_VARS = array();				
				$HTTP_POST_VARS['key'] = $key;
				$HTTP_POST_VARS['_x_allprojects'] = $key;			
			}
			
			// Special case if we open the window to create a new project (start with empty form)
			if( empty($HTTP_GET_VARS['new']) && strlen(trim($this->iProjname)) > 0 && count($HTTP_POST_VARS)==0 ) {
				$r = $this->iDBUtils->GetProject($this->iProjname);
				$HTTP_POST_VARS['key'] = $r['fld_key'];
			}
									
			$e = new EditProjects($this->iDBUtils);
			$e->Run($HTTP_POST_VARS);
			
			HTMLGenerator::CloseWinButton();			
		}
	}
}

$driver = new ProjectDriver();
if( !empty($HTTP_GET_VARS['projkey']) )
	$key = $HTTP_POST_VARS['projkey'] = $HTTP_GET_VARS['projkey'];	
$driver->Run();
$driver->Close();

?>