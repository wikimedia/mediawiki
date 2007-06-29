<?php
/** Faroese (Føroyskt)
  *
  * @addtogroup Language
  */

$skinNames = array(
	'standard'    => 'Standardur', 
	'nostalgia'   => 'Nostalgiskur', 
	'cologneblue' => 'Cologne-bláur'
);

$bookstoreList = array(
	'Bokasolan.fo' => 'http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1',
	'inherit' => true,
);

$namespaceNames = array(
	NS_MEDIA            => 'Miðil',
	NS_SPECIAL          => 'Serstakur',
	NS_MAIN             => '',
	NS_TALK             => 'Kjak',
	NS_USER             => 'Brúkari',
	NS_USER_TALK        => 'Brúkari_kjak',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_kjak',
	NS_IMAGE            => 'Mynd',
	NS_IMAGE_TALK       => 'Mynd_kjak',
	NS_MEDIAWIKI        => 'MidiaWiki',
	NS_MEDIAWIKI_TALK   => 'MidiaWiki_kjak',
	NS_TEMPLATE         => 'Fyrimynd',
	NS_TEMPLATE_TALK    => 'Fyrimynd_kjak',
	NS_HELP             => 'Hjálp',
	NS_HELP_TALK        => 'Hjálp_kjak',
	NS_CATEGORY         => 'Bólkur',
	NS_CATEGORY_TALK    => 'Bólkur_kjak'
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',
);

$linkTrail = '/^([áðíóúýæøa-z]+)(.*)$/sDu';

$messages = array(

# User toggles
"tog-underline"	   => "Undurstrika ávísingar",
"tog-highlightbroken" => "Brúka reyða ávísing til tómar síður",
"tog-justify"	   => "Stilla greinpart",
"tog-hideminor"	   => "Goym minni broytingar í seinast broytt listanum",		  # Skjul mindre ændringer i seneste ændringer listen
"tog-usenewrc"	   => "víðka seinastu broytingar lista<br />(ikki til alla kagarar)",
"tog-numberheadings"   => "Sjálvtalmerking av yvirskrift",
"tog-showtoolbar"	   => "Vís amboðslinju í rætting",
"tog-editondblclick"   => "Rætta síðu við at tvíklikkja (JavaScript)",
"tog-editsection"	   =>"Rætta greinpart við hjálp av [rætta]-ávísing",
"tog-editsectiononrightclick"=>"Rætta greinpart við at høgraklikkja<br /> á yvirskrift av greinparti (JavaScript)",
"tog-showtoc"=>"Vís innihaldsyvurlit<br />(Til greinir við meira enn trimun greinpartum)",
"tog-rememberpassword" => "Minst til loyniorð næstu ferð",
"tog-editwidth" => "Rættingarkassin hevur fulla breid",
"tog-watchdefault" => "Vaka yvur nýggjum og broyttum greinum",
"tog-minordefault" => "Merk sum standard allar broytingar sum smærri",
"tog-previewontop" => "Vís forhondsvísning áðren rættingarkassan",
"tog-nocache" => "Minst ikki til síðurnar til næstu ferð",

# Dates
'sunday' => 'sunnudagur',
'monday' => 'mánadagur',
'tuesday' => 'týsdagur',
'wednesday' => 'mikudagur',
'thursday' => 'hósdagur',
'friday' => 'fríggjadagur',
'saturday' => 'leygardagur',
'january' => 'januar',
'february' => 'februar',
'march' => 'mars',
'april' => 'apríl',
'may_long' => 'mai',
'june' => 'juni',
'july' => 'juli',
'august' => 'august',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'des',

# Math options
'mw_math_png' => "Vís altíð sum PNG",
'mw_math_simple' => "HTML um sera einfalt annars PNG",
'mw_math_html' => "HTML um møguligt annars PNG",
'mw_math_source' => "Lat verða sum TeX (til tekstkagara)",
'mw_math_modern' => "Tilmælt nýtíðarkagara",
'mw_math_mathml' => 'MathML if possible (experimental)',

# Preferences page
'qbsettings-none'	=> 'Eingin',
'qbsettings-fixedleft'	=> 'Fast vinstru',
'qbsettings-fixedright'	=> 'Fast høgru',
'qbsettings-floatingleft'	=> 'Flótandi vinstru',

);


