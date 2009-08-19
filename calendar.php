<?
Class calendar extends module {

// Calendar originally
// copyright  ©  2000-2001 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther

//using MySQL
//unzip and put somewhere
//This chunk of code uses a calendar type navigation to bring results 
//from a db or other. This example snags a database result using a date 
//field and an example of snagging directories by name/date (courtesy 
//of CNN). Codes a bit sloppy - not really tightened up - ok for a few 
//hours chomping. Paul Routledge qube@proqc.com
//do your stuff here
//$where="this_dir_and_filename"; // better to use this for testing cnn for the base a href //
//$where=$PHP_SELF; // real use for your site //

    function calendar() {
        $this->day
    }

switch ($p) {
case "84897":
    $where = "index.php?link=33940&p=84897";    
    break;
case "37564":
    $where = "index.php?link=33940&p=37564";
    break;
case "29740":
    $where = "index.php?link=33940&p=29740";
    break;
default:
    $where = $_SERVER["HTTP_REFERRER"];
}
?>
<div style="z-order:0">
<table width="192" bgcolor="black" cellpadding="0" cellspacing="1">
<tr bgcolor="white">
<td width="178">
<?
//set the calendar initial vars
if ($nextm==">") {
    if ($month==12) {
        $month=1;
        $year++;
    } else {
        $month++;
    }
}
if ($previousm=="<") {
    if ($month==1) {
        $month=12;
        $year--;
    } else {
        $month--;
    }
}
if ($day<="9"&ereg("(^[1-9]{1})",$day)) {
    $day="0".$day;
}
if ($month<="9"&ereg("(^[1-9]{1})",$month)) {
    $month="0".$month;
}
if (!$year) {
    $year=date("Y");
    //$year=date("Y",$timenow);
}
if (!$month) {
    $month=date("m");
    //$month=date("m",$timenow);
}
if (!$day) {
    $day=date("d");
    //$day=date("d",$timenow);
}

$thisday = "$year-$month-$day";
$day_name = array("M","T","W","Th","F","S","<font color=red>S</font>");
$month_abbr = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");// set an initial bogey for loop beginning 1 not 0

print "<form action='$where' method='post'>";
print "<table width='177' height='100' cellpadding='2' cellspacing='1' bgcolor='white'>";
// begin row 1
print "<tr bgcolor='#ff9900'>";
print "<td align='center' colspan='8' nowrap>";
print "<input  type='submit' class='btext' style='background=#6699ff' name='previousm' value='<'>&nbsp;";
print "<select name='month' class='btext'>";
// use a bogey here a=1 last=13 then we keep the correct month number in the array
for ($a=1; $a<13; $a++) {
  	$mo=date("n",mktime(0,0,0,$a,1,$year));
  	$name_of_month=$month_abbr[$mo];
  	if ($mo==$month) {
        print "<option  value=$a selected>$name_of_month\n";
		} else {
        print "<option value=$a>$name_of_month\n";
    }
}
print "</select> ";
print "<input type='hidden' name='s' value='1'>";
print "<input  type='submit' style='background=#6699ff' value='go' class='btext'>&nbsp;";	
print "<select name='year' class='btext'>";
$y=date("Y");
if ($year<$y-2) {
    print "<option value='$year' selected >$year\n";
}
for ($i=$y-2;$i<=$y+5;$i++) {
    if ($i==$year) {
        print "<option value='$i' selected>$i";
    } else {
        print "<option value='$i'>$i";
    }
}	
if ($year>$y+5) {
    print "<option value=$year selected>$year";
}
print "</select>&nbsp;";
if ($day) {
    print "<input type='hidden' name='day' value='$day'>";
}
print  "<input type='submit' style='background=#6699ff' name='nextm' value='>' class='btext'>";
print  "</td>";
print  "</tr>";
// end of row 1
// begin row 2
//week day names
print "<tr align='center' bgcolor='#cccccc' class='btext'>";
print "<td width='25' align='center'><b>Wk</b></td>";
for ($i=0; $i<7; $i++) {
    print "<td width='25'><b>$day_name[$i]</b></td>";
}
print  "</tr>";

// begin row 2
print "<tr>";
//first week if in previous month
if (date("w",mktime(0,0,0,$month,1,$year))==0) {
    $da=-6;
} else if (date("w",mktime(0,0,0,$month,1,$year))<>1) {
    $da= - date("w",mktime(0,0,0,$month,1,$year))+1;
} else {
    $da = 1;
}

//week number 	//iron out the 00/53 week number
if (strftime('%W',mktime(0,0,0,$month,($da+2),$year))==00) {
    print (strftime("<td align='center' bgcolor='#666666'><i><a href=\"$where&s=2&year=%Y&month=%m&w=%W&day=%d\" class='btext'><span class='wtext'><b>%W</b></span<></a></i></td>\n", mktime(0,0,0,$month,$da+1,$year)));
} else {
    print (strftime("<td align='center' bgcolor='#666666'><i><a href=\"$where&s=2&year=%Y&month=%m&w=%W&day=%d\" class='btext'><span class='wtext'><b>%W</b></span></a></i></td>\n", mktime(0,0,0,$month,$da+1,$year)));
}
// show overlap days of previous month
if (date("w",mktime(0,0,0,$month,1,$year))==0) {
    $start=7;}else{$start=date("w",mktime(0,0,0,$month,1,$year));
}
for ($a=($start-2);$a>=0;$a--) {
    $d=date("t",mktime(0,0,0,$month,0,$year))-$a;
    print "<td bgcolor='white' align='center' class='gtext'>$d</td>\n";
}

//days of the month
for ($d=1;$d<=date("t",mktime(0,0,0,($month+1),0,$year));$d++) { 
    //day link - today with different color bg
    if ($month==date("m", $timenow) & $year==date("Y", $timenow) & $d==date("d", $timenow)) {
        $bg = "bgcolor='#ffff33'";
    } else {
        $bg = "bgcolor='#FFFFFF'";
    }
    print "<td $bg class='btext' align='center'><a href=\"$where&year=$year&month=$month&day=$d&s=0\" class='btext'>$d</a></td>\n";
    
    if(date("w",mktime(0,0,0,$month,$d,$year))==0&date("t",mktime(0,0,0,($month+1),0,$year))>$d) {
        print "</tr><tr class='btext'>\n";
    	  $da = $d + 1;
    	  //iron out the 00/53 week number
        if (strftime('%W',mktime(0,0,0,$month,($d+2),$year))==00) {
            print "<td align='center' bgcolor='#666666'><i><a href=\"$where&s=2&year=$year&month=$month&w=53&day=$da\" class='btext'><span class='wtext'><b>53</b></span></a></i></td>\n";
        } else {
            print (strftime("<td align=center bgcolor=#666666><i><a href=\"$where&s=2&year=%Y&month=%m&w=%W&day=%d\" class='btext'><span class='wtext'><b>%W</b></span></a></i></td>\n",mktime(0,0,0,$month,($d+1),$year)));
        }
    }
}

// days of next month
if (date("w",mktime(0,0,0,$month+1,1,$year))<>1) {
    $d=1;
    while (date("w",mktime(0,0,0,($month+1),$d,$year))<>1) {
        print "<td class='btext' bgcolor='#cccccc' align=center>$d</td>\n";
    	  $d++;
    }
}


print  "</tr></table>";
print "</form>";

print "</td>";
print "</tr>";
/*
//put the sql here
if($s==2){$sql_select = "select stuff from table where datefield like '$year%' and week(open_date,1)='$w' order by datefield desc";}
if($s==0){$sql_select = "select stuff from table where datefield='$thisday' order by datefield desc";}
if($s==1){$sql_select = "select stuff from tablewhere  datefield like '$year-$month%' order by datefielde desc";}

//make the call
$result=mysql_query($sql_select);

if($s==2){print (mysql_num_rows($result));print " Results in Week $w, $year";}
if($s==0){print (mysql_num_rows($result));print " Results on ".strftime("%A, %B $day",mktime(0,0,0,$month,$day,$year))." $year";}
if($s==1){print (mysql_num_rows($result));print " Results in ".strftime("%B",mktime(0,0,0,$month,$day,$year)).", $year";}

print "</td></tr>";
print "<tr><td colspan=1 class=g valign=top>";
if(!mysql_num_rows($result)==0){print "<hr noshade><b>Database Results</b>";}

//put the db return results here

print "<pre>";

while($row=mysql_fetch_array($result)) 
	{
  	$date=$row["date"];
	$stuff=$row["stuff"];print "$date | $stuff \n";
	}

?>
<hr noshade></PRE></td><td class=g valign=top>
<?

//snag some directories by date from CNN using calendar navigation  - cnn's a bit slow =)
$cnn="http://www.cnn.com/";
$ni=array("US","WORLD/europe","WORLD/africa","WORLD/americas","WORLD/meast");
$nl=array("US","European","African","Americas","Middle-East");
$nwls=count($ni);
for($id=0;$id<$nwls;++$id)
	{
	print "<BASE HREF=\"$cnn$year/$ni[$id]/$month/$day/\">";
	$filo=@fopen("$cnn$year/$ni[$id]/$month/$day","r");
	// remove some stuff
    print (
	@eregi_replace("Index of ","<HR NOSHADE>CNN $nl[$id] News ", 
	@preg_replace("/<\/?(TITLE).*>/","",
	@eregi_replace("<A HREF=\"","<A TARGET=_news HREF=\"",
	@eregi_replace("H1","H3",
	@fread($filo,10000))))));
	}
mysql_close();
*/
?>
</table>
</div>
}
