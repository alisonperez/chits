<?php
//
//      SMS MESSAGE RETRIEVAL QUEUE  MODULE
//
//      Author: Herman Tolentino MD
//      February 2003
//      Copyright 2003 All rights reserved
//
//      This module runs as a cron job and performs the following:
//      1. reads the outbox and sends out the messages found
//      2. fetches messages from the cellular phone
//

// set this path to cqi script path
$cqipath = "/home/herman/public_html/cqi";

`killall -9 gnokii 1>/dev/null 2>/dev/null`;
sleep(1);
//
//      Includes
//
include "$cqipath/conn.php";
include "$cqipath/functions.php";

//
//      Define variables
//
$outfolder = "$cqipath/gsm/out";
$infolder = "$cqipath/gsm/in";
$sentfolder = "$cqipath/gsm/sent";
$failfolder = "$cqipath/gsm/fail";
$number="";
$message="";
$failemail="herman\@localhost";
$okemail="herman\@localhost";
$gnokii = "/usr/bin/gnokii";
$gnokii_getsms = "/usr/bin/gnokii";

//
//      SEND MESSAGES IN OUTBOX
//
$handle = opendir("$outfolder");
while (false !== ($file = readdir($handle))) {
    unset($message);
    unset($number);
    if ($file!="." && $file!="..") {
        $lines = file("$outfolder/$file");
        foreach($lines as $key => $value) {
            switch ($key) {
            case 0: // cellnumbers
                $phonenumbers = rtrim(str_replace("NUMBER: ", "", $lines[$key]), " \n\t");
                //print "$phonenumbers\n";
                break;
            case 1: // message
                $message = $lines[$key];
                //print "$message\n";
                break;
            }
            //  send out the message to each number
            //  find out if one or more phone numbers
            $numbers = explode(",", $phonenumbers);
            if (count($numbers)>1) {                // more than one number
                foreach ($numbers as $key => $value) {
                    $reply = `echo '$message'|$gnokii --sendsms $value`;
                    move($file, $outfolder, $sentfolder);
                }
            } else {                                           // single number
                $reply = `echo '$message'|$gnokii --sendsms $phonenumbers`;
                move($file, $outfolder, $sentfolder);
            }
        }
    }
}
closedir($handle);

sleep(1);
`killall -9 gnokii 1>/dev/null 2>/dev/null`;
$target = "$sentfolder/*";
`chown -R herman:apache $target`;

//
//      FETCH MESSAGES FROM PHONE
//
//      loop through 14 messages
//
for ($i=1; $i<15; $i++) {
    // get messages from phone and store in $reply
    $reply = `$gnokii_getsms --getsms ME $i`;
    $status = explode("\n", $reply);
    // if there is a message
    if (eregi("$i. Inbox Message", $status[0])) {
        // iterate through each line ($status array) of reply
        foreach($status as $key => $value) {
            switch ($key) {
            case 1: // date/time
                $datestring = str_replace("Date/time: ", "", $status[$key]);
                $datestring = str_replace(" +0800", "", $datestring);
                list($xx, $datestring, $timestring) = explode(" ", $status[$key]);
                $datestring = str_replace("/", ".", $datestring);
                $timestring = str_replace(":", ".", $timestring);
                print $datestring."_".$timestring."\n";
                break;
            case 2: //sender
                list($xx, $senderstring, ) = explode(" ", $status[$key]);
                $senderstring = str_replace("+", "", $senderstring);
                $infile = "$senderstring-$datestring"."_"."$timestring.txt";
                // write SMS to IN folder and change owner
                $fp = fopen("$infolder/$infile", "w");
                fwrite($fp, $reply);
                fclose($fp);
                print $target = "$infolder/*";
                `chown -R herman;herman $target`;
                // delete this SMS message from phone
                $delete = `$gnokii_getsms --deletesms ME $i $i`;
                break;
            }
        }
    } else {
        break;
    }
}
`killall -9 gnokii 1>/dev/null 2>/dev/null`;
sleep(1);
?>
