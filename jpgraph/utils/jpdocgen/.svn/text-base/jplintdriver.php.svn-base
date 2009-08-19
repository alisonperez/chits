<?php
//==============================================================================
// Name: 	JPLINTDRIVER.PHP
// Description:	Driver for PHP "lint"
// Created: 	01/12/03 
// Author:	johanp@aditus.nu
// Version: 	$Id: jplintdriver.php,v 1.1 2002/04/20 19:31:32 aditus Exp $
//
// License:	QPL 1.0
// Copyright (C) 2001,2002 Johan Persson
//==============================================================================

include("jplintphp.php");

//==========================================================================
// Script entry point
// Read URL argument and create Driver
//==========================================================================
if( !isset($HTTP_GET_VARS['target']) )
	die("<b>No file specified.</b> Use 'mylintphp.php?target=file_name'" );	
$file = urldecode($HTTP_GET_VARS['target']);
$driver = new Driver($file);
$driver->Run();


?>