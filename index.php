<?php
function explodes($kod, $przecinek1, $przecinek2) {
	$ciecie1 = explode($przecinek1, $kod); 
	$ciecie2 = explode($przecinek2, $ciecie1[1]);
	return $ciecie2[0];
}
function statystyki($ip, $port) {
	$error = 0;
	$blad = 'B³±d w po³±czeniu';
	//cURL
	$ch = curl_init($ip.':'.$port);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; pl; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$code = curl_exec($ch);
	curl_close($ch);
	if (empty($code)) {
		$error++;
	} else {
		$code2 = htmlspecialchars(stripslashes(strip_tags($code)));
		if (explodes($code2, 'Server Status:', 'Stream Status:') == 'Server is currently down.') {
			$error++;
		} else {
			$statystyki['audycja'] = trim(explodes($code2, 'Stream Title: ', 'Content Type:'));
			$statystyki['prezenter'] = trim(explodes($code2, 'Stream Genre: ', 'Stream URL:'));
		}
	}
	if (!empty($error)) {
		$statystyki = array('sluchaczy' => $blad, 'audycja' => $blad, 'prezenter' => $blad, 'utwor' => $blad);
	}
	$statystyki['audycja'] = str_replace('(', '&#40;', $statystyki['audycja']);
	$statystyki['audycja'] = str_replace(')', '&#41;', $statystyki['audycja']);
	$statystyki['audycja'] = str_replace('ó', '?', $statystyki['audycja']);
	return '\''.$statystyki['prezenter'].'\', \''.$statystyki['audycja'].'\'';
}
?>
<!DOCTYPE HTML>
<!-- 
Template Name: comming soon page RadioStyl.net
Version: 1.0
Author: Blue-NET Mateusz Serwinowski
Website: http://Blue-NET.pl
Contact: support@blue-net.pl
-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<title>RadioSTYL.net :: comming soon</title>
    <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="assets/plugins/bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
    <link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="assets/css/countdown.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="stylesheet" type="text/css" href="assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.2" media="screen" />
</head>
<body>

<div id="container">

	<div id="logo">
    	<a href="#" class="logo"><img src="assets/img/logo.png" alt=""></a>
    	<img src="assets/img/slagon.png" alt="">
    </div>
    
    <div id="clock" class="container">
    	<div class="row"><div class="col-md-12"><div class="loading triangles">
        <!-- [START] Timer -->
        <div class="from-top" id="countdown_container">
            <div id="countdown_timer"></div>
            <div id="countdown_clock">
                <canvas id="circular_countdown_days" width="160" height="160"></canvas>
                <canvas id="circular_countdown_hours" width="160" height="160"></canvas>
                <canvas id="circular_countdown_minutes" width="160" height="160"></canvas>
                <canvas id="circular_countdown_seconds" width="160" height="160"></canvas>
            </div>
        </div>
        <!-- [END] Timer -->
        </div></div></div>
    </div>
    
    
    <div id="ramowka">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-4 text-center" id="panel1">

<?php 
$sc[czas] = 180; 
$sc[host] = "s6.xpx.pl"; 
$sc[port] = 8458; 
$sc[listen] = "http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8458&format=m3u"; 

$sc[genre] = "[genre]"; 
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
$sc[genre] = str_replace($co,$naco,$sc[genre]); 
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
                	<div class="kanal text-left">
                    	<div class="typ"><img src="assets/img/club.png" alt=""></div>
                        <div class="play"><a href="<?php print $sc[listen]; ?>"></a></div>
                        <div class="prezenter">
                            <div class="row">
                                <div class="col-md-5"><img src="<?php echo $fotka; ?>" class="img-thumbnail" alt=""></div>
                                <div class="col-md-7 text">
                                	<p><b>Prezenter:</b> <span id="prezenter"><?php echo $sc[genre]; ?> </span></p>
                                	<p><b>Audycja:</b> <span id="audycja"><marquee><?php echo $text; ?></marquee></span></p>
                                </div>
                            </div>
                            <p class="text-center"><a href="http://radiostyl.panelradiowy.pl/embed.php?script=ramowka" class="fancybox fancybox.iframe">RAM&Oacute;WKA</a></p>
                        </div>
                    </div>
                    <a href="http://radiostyl.panelradiowy.pl/embed.php?script=pozdrowienia" class="fancybox1 fancybox.iframe"><img src="assets/img/pozdrowiennia.png" alt=""></a>
                </div>
            	<div class="col-md-4 text-center" id="panel2">

<?php 
$sc1[czas] = 180; 
$sc1[host] = "s6.xpx.pl"; 
$sc1[port] = 8450; 
$sc1[listen] = "http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8450&format=m3u"; 

$sc1[genre] = "[genre]"; 
$sc1[template1] = "[radio]"; 
$sc1[template2] = "[aim]"; 

if($fp1 = fsockopen($sc1[host], $sc1[port])) 
{ 
	fputs($fp1,"GET /index.html HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n"); 
	fgets($fp1);fgets($fp1);fgets($fp1); 
	while(!feof($fp1)) $in1.=strip_tags(fgets($fp1)); 
	fclose($fp1); 


	// [status] 
	$m1[0]="Server is currently"; 
	$m1[1]=""; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=@strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=explode( " " , trim( substr( $in1 , $mp1[0] + $ml1[0] , 5 ) ) ); 
	
	if( $tmp1[1][0] == "up" ) 
	$dat1[status] = "On"; 
	elseif( $tmp1[1][0] == "down" ) 
	$dat1[status] = "Off"; 
	else 
	$dat1[status] = "err"; 
	
	if($dat1[status]=="On") 
	{ 
	// [radio] 
	$m1[0]="Stream Title:"; 
	$m1[1]="Content Type:"; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=trim( substr( $in1 , $mp1[0] + $ml1[0] , $mp1[1]-$mp1[0]-$ml1[1] ) ); 
	
	if( $tmp1[1] ) $dat1[radio] = $tmp1[1]; 
	else $dat1[radio] = "err"; 
	
	// [aim] 
	$m1[0]="Stream AIM:"; 
	$m1[1]="Stream IRC:"; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=trim( substr( $in1 , $mp1[0] + $ml1[0] , $mp1[1]-$mp1[0]-$ml1[1] ) ); 
	
	if( $tmp1[1] ) $dat1[aim] = $tmp1[1]; 
	else $dat1[aim] = "err"; 
	
	// [genre] 
	$m1[0]="Stream Genre:"; 
	$m1[1]="Stream URL:"; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=trim( substr( $in1 , $mp1[0] + $ml1[0] , $mp1[1]-$mp1[0]-$ml1[1]-2 ) ); 
	
	if( $tmp1[1] ) $dat1[genre] = $tmp1[1]; 
	else $dat1[genre] = "err"; 
	
	// [ile] 
	$m1[0]="kbps with"; 
	$m1[1]="listeners"; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=explode( " " , trim( substr( $in1 , $mp1[0] + $ml1[0] , $mp1[1]-$mp1[0]-$ml1[1]-2 ) ) ); 
	
	if( $tmp1[1] ) $dat1[ile] = $tmp1[1][0]; 
	else $dat1[ile] = "err"; 
	
	// [max] 
	$m1[0]="kbps with"; 
	$m1[1]="listeners"; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=explode( " " , trim( substr( $in1 , $mp1[0] + $ml1[0] , $mp1[1]-$mp1[0]-$ml1[1]-2 ) ) ); 
	
	if( $tmp1[1] ) $dat1[max] = $tmp1[1][2]; 
		else $dat1[max] = "err"; 

	// [peak] 
	$m1[0]="Listener Peak:"; 
	$m1[1]="Average"; 
	$mp1[0]=strpos($in1,$m1[0]); 
	$mp1[1]=strpos($in1,$m1[1]); 
	$ml1[0]=strlen($m1[0]); 
	$ml1[1]=strlen($m1[1]); 
	
	$tmp1[1]=trim( substr( $in1 , $mp1[0] + $ml1[0] , $mp1[1]-$mp1[0]-$ml1[1]-7) ); 

	if( $tmp1[1] ) $dat1[peak] = $tmp1[1]; 
	else $dat1[peak] = "err"; 
} 
} 
else 
{ 
	$dat1=array(); 
	$dat1[status]="err"; 
} 

$co1 = array('[status]','[radio]','[aim]','[genre]','[ile]','[max]','[peak]'); 
$naco1 = array($dat1[status],$dat1[radio],$dat1[aim],$dat1[genre],$dat1[ile],$dat1[max],$dat1[peak]); 
$sc1[genre] = str_replace($co1,$naco1,$sc1[genre]); 
$sc1[template1] = str_replace($co1,$naco1,$sc1[template1]); 
$sc1[template2] = str_replace($co1,$naco1,$sc1[template2]); 

$fp11 = @fsockopen($sc1[host], $sc1[port], &$errno1, &$errstr1, 10); 

if (!$fp11) { 
	$text1 = "Aktualnie nie nadajemy"; 
} else { 

	fputs($fp11, "GET /7 HTTP/1.1\nUser-Agent:Mozilla\n\n"); 

	for($i = 0; $i < 1; $i++) { 
		if (feof($fp11)) break; 
		$fp_data11 = fread($fp11, 31337); 
		usleep(500000); 
	} 

	$fp_data11 = ereg_replace("^.*<body>", "", $fp_data11); 
	$fp_data11 = ereg_replace("</body>.*", "", $fp_data11); 
	
	list($current11, $status11, $peak11, $max11, $reported11, $bit11, $song11) = explode(",", $fp_data11, 7); 

	if ($status11 == "1") 
		$text1 = "$song1"; 
	else 
		$text1 = "Aktualnie nie nadajemy"; 
} 

// TU WSTAW ADRESY DO ZDJEC DJ'OW - WEDLUG WZORU 

if ($dat1[aim] == "numergg") $fotka1 = "http://radiostyl.panelradiowy.pl/embed.php?script=avatar&size=120"; else $fotka1 = "assets/img/img.png"; 

?>             
                	<div class="kanal text-left">
                    	<div class="typ krak"><img src="assets/img/krak.png" alt=""></div>
                        <div class="play"><a href="<?php print $sc1[listen]; ?>"></a></div>
                        <div class="prezenter">
                            <div class="row">
                                <div class="col-md-5"><img src="<?php echo $fotka1; ?>" class="img-thumbnail" alt=""></div>
                                <div class="col-md-7 text">
                                	<p><b>Prezenter:</b> <span id="prezenter"><?php echo $sc1[genre]; ?> </span></p>
                                	<p><b>Audycja:</b> <span id="audycja"><marquee><?php echo $text1; ?></marquee></span></p>
                                </div>
                            </div>
                            <p class="text-center"><a href="http://radiostyl.panelradiowy.pl/embed.php?script=ramowka" class="fancybox fancybox.iframe">RAM&Oacute;WKA</a></p>
                        </div>
                    </div>
                    <a href="http://radiostyl.panelradiowy.pl/embed.php?script=pozdrowienia" class="fancybox1 fancybox.iframe"><img src="assets/img/pozdrowiennia.png" alt=""></a>
                </div>
            	<div class="col-md-4 text-center" id="panel3">

<?php 
$sc[czas] = 180; 
$sc[host] = "s6.xpx.pl"; 
$sc[port] = 8458; 
$sc[listen] = "http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8458&format=m3u"; 

$sc[genre] = "[genre]"; 
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
$sc[genre] = str_replace($co,$naco,$sc[genre]); 
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
                	<div class="kanal text-left">
                    	<div class="typ disco"><img src="assets/img/disco.png" alt=""></div>
                        <div class="play"><a href="<?php print $sc[listen]; ?>"></a></div>
                        <div class="prezenter">
                            <div class="row">
                                <div class="col-md-5"><img src="<?php echo $fotka; ?>" class="img-thumbnail" alt=""></div>
                                <div class="col-md-7 text">
                                	<p><b>Prezenter:</b> <span id="prezenter"><?php echo $sc[genre]; ?> </span></p>
                                	<p><b>Audycja:</b> <span id="audycja"><marquee><?php echo $text; ?></marquee></span></p>
                                </div>
                            </div>
                            <p class="text-center"><a href="http://radiostyl.panelradiowy.pl/embed.php?script=ramowka" class="fancybox fancybox.iframe">RAM&Oacute;WKA</a></p>
                        </div>
                    </div>
                    <a href="http://radiostyl.panelradiowy.pl/embed.php?script=pozdrowienia" class="fancybox1 fancybox.iframe"><img src="assets/img/pozdrowiennia.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>

</div>
    
<footer>
    <a href="http://facebook.com/radiostyl"><img src="assets/img/facebook.png" alt=""></a>
</footer>


	<div id="wrapp"></div>    	
<!-- [START] jQuery include -->
<script type="text/javascript" src="assets/plugins/jQuery.js"></script>
<!-- [END] jQuery include -->

<!-- [START] script variables -->
<script type="text/javascript">
/* <![CDATA[ */
var trian = {
    "primary_color":"#d7d7d7",
    "secondary_color":"#fb5a09",
    "countdown_date": new Date(2014,6,1,00,00,00) // date in yyyy,m,d,hh,mm,ss format
};
/* ]]> */
</script>
<!-- [END] script variables -->

<!-- [START] scripts -->
<script type="text/javascript" src="assets/scripts/convertHex.js"></script>
<script type="text/javascript" src="assets/plugins/countdown/countdown.js"></script>
<script type="text/javascript" src="assets/plugins/countdown/countdown_plugins.js"></script>
<script type="text/javascript" src="assets/scripts/countdown_settings.js"></script>
<script type="text/javascript" src="assets/scripts/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery.browser.js"></script>
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="assets/plugins/fancybox/source/jquery.fancybox.js"></script>
<script type="text/javascript" src="assets/scripts/plugins.js"></script>
<!-- [END] scripts -->
<script type="text/javascript">
function change(panel, kanal, prezenter, audycja, play) {
	//$(panel + ' .kanal').text(kanal);
	$(panel + ' #prezenter').text(prezenter);
	$(panel + ' #audycja marquee').text(audycja);
	$(panel + ' .play a').attr('href', play);
}

$(function() {
/*
	change("#panel1",'Kana³ Club', <?php echo statystyki('s6.xpx.pl', 8458); ?>, 'http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8458&format=m3u');
	change("#panel2",'Kana³ Club', <?php echo statystyki('s6.xpx.pl', 8450); ?>, 'http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8450&format=m3u');
	change("#panel3",'Kana³ Club', <?php echo statystyki('s6.xpx.pl', 8458); ?>, 'http://radiostyl.panelradiowy.pl/listen.php?ip=s6.xpx.pl&port=8458&format=m3u');
	<a href="">Playlista</a>
	$("#panel2 #audycja marquee").text("Radiostyl To Twój Styl, To Twoja Muzyka");

*/
	$('.fancybox').fancybox({
		maxWidth	: 455,
		maxHeight	: 600,
		autoSize	: false
	});
	$('.fancybox1').fancybox({
		maxWidth	: 355,
		maxHeight	: 300,
		autoSize	: false
	});
});
</script>
</body>
</html>