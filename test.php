
<?php 
$sc[czas] = 180; 
$sc[host] = "s6.xpx.pl"; 
$sc[port] = 8450; 
$sc[listen] = "http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8450&format=m3u"; 

$sc[template1] = "[radio]"; 
$sc[template2] = "[aim]"; 

if($fp = fsockopen($sc[host], $sc[port])) 
{ 
	fputs($fp,"GET /index.html HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n"); 
	fgets($fp);fgets($fp);fgets($fp); 
	while(!feof($fp)) $in.=strip_tags(fgets($fp)); 
	fclose($fp); 


	// [status] 
	$m[0]="Server is currently"; 
	$m[1]=""; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=@strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=explode( " " , trim( substr( $in , $mp[0] + $ml[0] , 5 ) ) ); 
	
	if( $tmp[1][0] == "up" ) 
	$dat[status] = "On"; 
	elseif( $tmp[1][0] == "down" ) 
	$dat[status] = "Off"; 
	else 
	$dat[status] = "err"; 
	
	if($dat[status]=="On") 
	{ 
	// [radio] 
	$m[0]="Stream Title:"; 
	$m[1]="Content Type:"; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=trim( substr( $in , $mp[0] + $ml[0] , $mp[1]-$mp[0]-$ml[1] ) ); 
	
	if( $tmp[1] ) $dat[radio] = $tmp[1]; 
	else $dat[radio] = "err"; 
	
	// [aim] 
	$m[0]="Stream AIM:"; 
	$m[1]="Stream IRC:"; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=trim( substr( $in , $mp[0] + $ml[0] , $mp[1]-$mp[0]-$ml[1] ) ); 
	
	if( $tmp[1] ) $dat[aim] = $tmp[1]; 
	else $dat[aim] = "err"; 
	
	// [genre] 
	$m[0]="Stream Genre:"; 
	$m[1]="Stream URL:"; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=trim( substr( $in , $mp[0] + $ml[0] , $mp[1]-$mp[0]-$ml[1]-2 ) ); 
	
	if( $tmp[1] ) $dat[genre] = $tmp[1]; 
	else $dat[genre] = "err"; 
	
	// [ile] 
	$m[0]="kbps with"; 
	$m[1]="listeners"; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=explode( " " , trim( substr( $in , $mp[0] + $ml[0] , $mp[1]-$mp[0]-$ml[1]-2 ) ) ); 
	
	if( $tmp[1] ) $dat[ile] = $tmp[1][0]; 
	else $dat[ile] = "err"; 
	
	// [max] 
	$m[0]="kbps with"; 
	$m[1]="listeners"; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=explode( " " , trim( substr( $in , $mp[0] + $ml[0] , $mp[1]-$mp[0]-$ml[1]-2 ) ) ); 
	
	if( $tmp[1] ) $dat[max] = $tmp[1][2]; 
		else $dat[max] = "err"; 

	// [peak] 
	$m[0]="Listener Peak:"; 
	$m[1]="Average"; 
	$mp[0]=strpos($in,$m[0]); 
	$mp[1]=strpos($in,$m[1]); 
	$ml[0]=strlen($m[0]); 
	$ml[1]=strlen($m[1]); 
	
	$tmp[1]=trim( substr( $in , $mp[0] + $ml[0] , $mp[1]-$mp[0]-$ml[1]-7) ); 

	if( $tmp[1] ) $dat[peak] = $tmp[1]; 
	else $dat[peak] = "err"; 
} 
} 
else 
{ 
	$dat=array(); 
	$dat[status]="err"; 
} 

$co = array('[status]','[radio]','[aim]','[genre]','[ile]','[max]','[peak]'); 
$naco = array($dat[status],$dat[radio],$dat[aim],$dat[genre],$dat[ile],$dat[max],$dat[peak]); 
$sc[genre] = str_replace($co,$naco,$dat[genre]); 
$sc[template1] = str_replace($co,$naco,$sc[template1]); 
$sc[template2] = str_replace($co,$naco,$sc[template2]); 

$fp1 = @fsockopen($sc[host], $sc[port], &$errno, &$errstr, 10); 

if (!$fp1) { 
	$text = "Aktualnie nie nadajemy"; 
} else { 

	fputs($fp1, "GET /7 HTTP/1.1\nUser-Agent:Mozilla\n\n"); 

	for($i = 0; $i < 1; $i++) { 
		if (feof($fp1)) break; 
		$fp_data1 = fread($fp1, 31337); 
		usleep(500000); 
	} 

	$fp_data1 = ereg_replace("^.*<body>", "", $fp_data1); 
	$fp_data1 = ereg_replace("</body>.*", "", $fp_data1); 
	
	list($current1, $status1, $peak1, $max1, $reported1, $bit1, $song1) = explode(",", $fp_data1, 7); 

	if ($status1 == "1") 
		$text = "$song1"; 
	else 
		$text = "Aktualnie nie nadajemy"; 
} 

// TU WSTAW ADRESY DO ZDJEC DJ'OW - WEDLUG WZORU 

if ($dat[aim] == "numergg") $fotka = "http://radiostyl.panelradiowy.pl/embed.php?script=avatar&size=120"; else $fotka = "assets/img/img.png"; 

?>         

<table cellspacing="0" cellpadding="0" height="216" width="140" border="0" background ="tlo.gif"> 
<tr><td width ="100%" height ="19" colspan ="2"> 
<center><font size ="2" face ="Verdana" color ="black"> 
Radio 
<br></font></center> 
</td></tr> 
<tr><td width ="80%" height ="15" valign ="top" colspan ="2"> 
<center><font size ="1" face ="Arial"> 
<A href ="http://www.omega.ovh.org" target ="_blank"><font color ="brown">www.omega.ovh.org</font></A> 
<br></font></center> 
</td></tr> 
<tr><td width ="100%" height ="15" colspan ="2"> 
</td></tr> 
<tr><td width ="100%" height ="17" valign ="top" colspan ="2"> 
<center><font size ="2" face ="Courier" color ="black"> Prezenter
<MARQUEE width="80%" SCROLLAMOUNT="4" behavior ="scroll" onMouseOver='this.stop()' onMouseOut='this.start()'> 
<?php echo $sc[genre]; ?> 
</MARQUEE> 
</font></center> 
</td></tr> 
<tr><td width ="100%" height ="15" colspan ="2"> 
</td></tr> 
<tr><td width ="100%" height ="17" valign ="top" colspan ="2"> 
<center><font size ="2" face ="Courier" color ="black"> 
<MARQUEE width="80%" SCROLLAMOUNT="4" behavior ="scroll" onMouseOver='this.stop()' onMouseOut='this.start()'> 
<?php echo $sc[template1]; ?> 
</MARQUEE> 
</font></center> 
</td></tr> 
<tr><td width ="100%" height ="15" colspan ="2"> 
</td></tr> 
<tr><td width ="100%" height ="17" valign ="top" colspan ="2"> 
<center><font size ="2" face ="Courier" color ="black"> 
<MARQUEE width="80%" SCROLLAMOUNT="4" behavior ="scroll" onMouseOver='this.stop()' onMouseOut='this.start()'> 
<?php echo $text; ?> 
</MARQUEE> 
</font></center> 
</td></tr> 
<tr><td width ="100%" height ="15" colspan ="2"> 
</td></tr> 
<tr><td width ="100%" height ="17" valign ="top" colspan ="2"> 
<center><font size ="2" face ="Courier" color ="black"> 
<MARQUEE width="80%" SCROLLAMOUNT="4" behavior ="alternate" onMouseOver='this.stop()' onMouseOut='this.start()'> 
<?php echo $sc[template2]; ?> 
</MARQUEE> 
</font></center> 
</td></tr> 
<tr><td width ="100%" height ="6" colspan ="2"> 
</td></tr> 
<tr><td width ="65" height ="80" valign ="top"> 
<?php print "<a href='$sc[listen]'>Słuchaj nas</a>"; ?> 
<td width ="75" height ="80" valign ="top"> 
<?php print "<img src ='$fotka' width ='62' height ='70'>"; ?> 
<br clear ="all"> 
</td></tr> 
</table>