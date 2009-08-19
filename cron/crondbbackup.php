#!/usr/bin/php
<?php
//  DAILY MAINTENANCE MODULE
//
//  Author: Herman Tolentino MD
//  August 2003
//  Copyright 2003 All rights reserved
//
//  This module runs as a cron job and performs the following:
//  1. 12 MN Back ups database and sends to administrator email
//  2. Deletes contents of jpcache directory
//  3. Send report by email
//

//
//  EDIT THIS SECTION FOR DATABASE
//
$cqipath = "/home/herman/public_html/cqi";
include "$cqipath/conn.php";
include "$cqipath/functions.php";
$sqltoday = date("Y-m-d");
$asoftoday = date("h:mA, M j, Y D");

//  important path settings
//  make sure you set this correctly
$mysqlbinpath = "/usr/bin";

//
//  DO NOT EDIT BEYOND THIS POINT!!!
//  ================================
//

$adminemail = "hermant@cm.upm.edu.ph";

//
//  DATABASE BACKUP MODULE
//


// filenames
$sqlfilename = date("Ymd")."-cqi.sql";
$gzipfilename = date("Ymd")."-cqi.sql.gz";

// backtick dump of database
$info = `$mysqlbinpath/mysqldump -u root -pkambing anescqi > /tmp/$sqlfilename`;

// open raw dump and get contents into variable
$fp = fopen ("/tmp/$sqlfilename", "r");
$contents = fread ($fp, filesize ("/tmp/$sqlfilename"));
fclose ($fp);

// create temporary gzip file
$tempfilename = tempnam("/tmp", "backup").'.gz';

// open file for writing with maximum compression
$zp = gzopen($tempfilename, "w9");
// write string to file
gzwrite($zp, $contents);
// close file
gzclose($zp);

// transfer to logs
copy("$tempfilename", "$cqipath/logs/$gzipfilename");
$filesize = filesize("$cqipath/logs/$gzipfilename");
unlink("/tmp/$sqlfilename");
unlink($tempfilename);

// mail the file
$file_url = "$cqipath/logs/$gzipfilename";
$fp = fopen($file_url,"r");
$str = fread($fp, filesize($file_url));
$str = chunk_split(base64_encode($str));
$headers = "From: admin@anes1.upm.edu.ph\n";
$headers .= "Reply-To: $adminemail\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\n";
$headers .= "X-Sender: admin@anes1.upm.edu.ph\n";
$headers .= "X-Mailer: PHP4\n";
$headers .= "X-Priority: 3\n";
$headers .= "Return-Path: $adminemail\n";
$headers .= "This is a multi-part message in MIME format.\n";
$message = "--MIME_BOUNDRY\n";
$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
$message .= "Content-Transfer-Encoding: quoted-printable\n";
$message .= "\n";
// your text goes
$message .= "CQISERVER Database Backup\n";
$message .= "Backup Date: ".date("Y-m-d H:i:s")."\n";
$message .= "\n";
$message .= "--MIME_BOUNDRY\n";
$message .= "Content-Type: application/x-gzip; name=\"$gzipfilename\"\n";
$message .= "Content-disposition: attachment\n";
$message .= "Content-Transfer-Encoding: base64\n";
$message .= "\n";
$message .= "$str\n";
$message .= "\n";
//message ends
$message .= "--MIME_BOUNDRY--\n";
// send the message :-)
$subject = "CQI Database Backup";

mail($adminemail, $subject, $message, $headers);
?>
