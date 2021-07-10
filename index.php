<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0");
$favicon = '<link rel="icon" type="image/png" href="/img/favicon.png"/>';
if (isset($_REQUEST['country']) && strlen($_REQUEST['country']) == 2) {
 $country = $_REQUEST['country'];
 if (isset($_REQUEST['lang']) && strlen($_REQUEST['lang']) == 2) {
 $lang = $_REQUEST['lang'];
 }
} elseif (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
 $country = $_SERVER['HTTP_CF_IPCOUNTRY'];
 if (isset($_GET['lang']) && strlen($_GET['lang']) == 2) {
 $lang = $_GET['lang'];
 }
}
if (isset($country)) {
 if (is_file("locales/" . strtolower($country) . "/passphrase" )) {
 $ucountry = strtoupper($country);
 $lcountry = strtolower($country);
 $locdir = "locales/" . $lcountry;
 $cflag = '<img class="country" src="img/flag/' . $ucountry . '.png" alt="' . $ucountry . ' version" title="' . $ucountry . ' version">';
 $favicon = '<link rel="shortcut icon" href="/img/ico/' . $ucountry . '.ico"/>';
  if (isset($lang)) {
   if (is_file("locales/" . strtolower($country) . "/" . strtolower($lang) . "/passphrase" )) {
   $ulang = strtoupper($lang);
   $llang = strtolower($lang);
   $locdir = "locales/" . $lcountry . "/" . $llang;
   $lflag = '<img class="country" src="img/flag/' . $ulang . '.png" alt="' . $ulang . ' language" title="' . $ulang . ' language">';
   $favicon = '<link rel="shortcut icon" href="/img/ico/' . $ulang . '.ico"/>';
   }
  }
 }
}
if (isset($locdir)) {
 $qr = $locdir . "/qr.png";
 $qrdark = $locdir . "/qr-dark.png";
 $passphrase = $locdir . "/passphrase";
} else {
 $qr = "qr.png";
 $qrdark = "qr-dark.png";
 $passphrase = "passphrase";
}
if (isset($_REQUEST["api"])) { readfile($passphrase); die; }
?>
<html><title>TheGuestWifi</title>
<style>
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 400;
  src: local(''), url('font/montserrat.woff2') format('woff2'), url('font/montserrat.woff') format('woff');
}
 body {position:relative; top:5%; font-family: 'Montserrat', sans-serif; color:#1B1B54; background-color:#F0FFFF; font-size:50pt; text-align:center}
 img.qr {width: auto; height: 50%}
 img.country {vertical-align: center}
 a:link {color:#4863A0}
 a:visited {color:#728FCE}
 /* Text and background color for dark mode */
 @media (prefers-color-scheme: dark) {
  body {position:relative; top:5%; font-family: 'Montserrat', sans-serif; color:#F0FFFF; background-color: #1B1B54; font-size:50pt; text-align:center}
  img {opacity: .75; transition: opacity .5s ease-in-out}
  img:hover {opacity: 1}
  a:link {color:#728FCE}
  a:visited {color:#4863A0}
}
.country {all: initial}
</style>
<?php if(isset($favicon)) { printf($favicon."\n"); }?>
<body>
TheGuestWiFi
<br>
<picture>
 <source srcset="<?= $qrdark; ?>" media="(prefers-color-scheme: dark)">
 <img class="qr" src="<?= $qr; ?>">
</picture>
<br>
Password: <?php readfile($passphrase); ?>
<br>
<p style="font-size:14pt">
<br><br>
Scripting users: Use https://<?= $_SERVER['HTTP_HOST']; ?>?api to retrieve the daily password.
<br><br>
Developers: TheGuestWiFi is a project hosted on <a style="text-decoration:none" href="https://github.com/drmessano/theguestwifi">GitHub</a>
<br><br>
<?php if(isset($cflag)) { printf ($cflag); if(isset($lflag)) { printf ($lflag); }}?>
Provided courtesy of <a style="text-decoration:none" href="https://www.2l2o.com">2l2o</a>
</p>
<?php if(file_exists('serverid')) { printf('<p hidden/>##### ServerID: '.preg_replace("/\r|\n/","",file_get_contents('serverid')).' #####'."\n"); }?>
</body></html>
