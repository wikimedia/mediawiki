<?php
/** Faroese (Føroyskt)
  *
  * @package MediaWiki
  * @subpackage Language
  */
require_once( 'LanguageUtf8.php');

/* private */ $wgNamespaceNamesFo = array(
	NS_MEDIA            => "Miðil",
	NS_SPECIAL          => "Serstakur",
	NS_MAIN             => "",
	NS_TALK             => "Kjak",
	NS_USER             => "Brúkari",
	NS_USER_TALK        => "Brúkari_kjak",
	NS_PROJECT          => "Wikipedia",
	NS_PROJECT_TALK     => "Wikipedia_kjak",
	NS_IMAGE            => "Mynd",
	NS_IMAGE_TALK       => "Mynd_kjak",
	NS_MEDIAWIKI        => "MidiaWiki",
	NS_MEDIAWIKI_TALK   => "MidiaWiki_kjak",
	NS_TEMPLATE         => "Fyrimynd",
	NS_TEMPLATE_TALK    => "Fyrimynd_kjak",
	NS_HELP             => "Hjálp",
	NS_HELP_TALK        => "Hjálp_kjak",
	NS_CATEGORY         => "Bólkur",
	NS_CATEGORY_TALK    => "Bólkur_kjak"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFo = array(
	"Eingin", "Fast vinstru", "Fast høgru", "Flótandi vinstru"
);

/* private */ $wgSkinNamesFo = array(
	"Standardur", "Nostalgiskur", "Cologne-bláur", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesFo = array(
	MW_MATH_PNG => 'mw_math_png',
	MW_MATH_SIMPLE => 'mw_math_simple',
	MW_MATH_HTML => 'mw_math_html',
	MW_MATH_SOURCE => 'mw_math_source',
	MW_MATH_MODERN => 'mw_math_modern',
	MW_MATH_MATHML => 'mw_math_mathml'
);

/* private */ $wgDateFormatsFo = array(
#	"Ongi forrættindi",
);

/* private */ $wgBookstoreListFo = array(
	"Bokasolan.fo" => "http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1",
) + $wgBookstoreListEn;

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesFo = array(
	"Userlogin" => "",
	"Userlogout" => "",
	"Preferences" => "Minar brúkarainnstillingar",
	"Watchlist" => "Mín vaka yvur listi",
	"Recentchanges" => "Seinsastu broytingar",
	"Upload" => "Leg fílur upp",
	"Imagelist" => "Myndalisti",
	"Listusers" => "Skrásettir brúkarir",
	"Statistics" => "Hagtøl um síðuna",
	"Randompage" => "Tilvildarlig grein",

	"Lonelypages" => "Foreldreleysar greinir",
	"Unusedimages" => "Foreldreleysir fílur",
	"Popularpages" => "Umtóktar greinir",
	"Wantedpages" => "Mest ynsktu greinir",
	"Shortpages" => "Stytstu greinir",
	"Longpages" => "Langar greinir",
	"Newpages" => "Nýggjastu greinir",
	"Ancientpages" => "Elstu greinir",
	"Deadendpages" => "Blindsíður",
	"Intl" => "Málávísing",
	"Allpages" => "Allar síður eftur yvurskrift",

	"Ipblocklist" => "Bannaðar IP-adressur",
	"Maintenance" => "Viðlíkahaldssíðir",
	"Specialpages" => "",
	"Contributions" => "",
	"Emailuser" => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage" => "",
	"Booksources" => "Útvortis bókakelda",
	"Categories" => "síðubólkur",
	"Export" => "Útflyt síðu í XML sniði",
	"Version" => "Vís MediaWiki útgávu",
);

/* private */ $wgSysopSpecialPagesFo = array(
	"Blockip"	      => "Bannað eina IP-adressu",
	"Asksql"	      => "Ger ein fyrispurning í dátagrunnin",
	"Undelete"	      => "Síggj og endurstovna strikaðar síður",
	"Makesysop"	      => "Ber ein brúkara til umboðsstjóra"
);

/* private */ $wgDeveloperSpecialPagesFo = array(
	"Lockdb"	      => "Skriviverj dátagrunnin", # Skrivivardur
	"Unlockdb"	      => "Endurstovna skriviatgongd til dátagrunnin",
);

/* private */ $wgAllMessagesFo = array(

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

'linktrail' => '/^([áðíóúýæøa-z]+)(.*)$/sDu',
);
class LanguageFo extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListFo ;
		return $wgBookstoreListFo ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFo;
		return $wgNamespaceNamesFo;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFo;
		return $wgQuickbarSettingsFo;
	}

	function getSkinNames() {
		global $wgSkinNamesFo;
		return $wgSkinNamesFo;
	}

	function getDateFormats() {
		global $wgDateFormatsFo;
		return $wgDateFormatsFo;
	}

	
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " kl. " . $this->time( $ts, $adj );
	}

	function getValidSpecialPages() {
		global $wgValidSpecialPagesFo;
		return $wgValidSpecialPagesFo;
	}

	function getSysopSpecialPages() {
		global $wgSysopSpecialPagesFo;
		return $wgSysopSpecialPagesFo;
	}

	function getDeveloperSpecialPages() {
		global $wgDeveloperSpecialPagesFo;
		return $wgDeveloperSpecialPagesFo;
	}

	function getMessage( $key ) {
	    global $wgAllMessagesFo, $wgAllMessagesEn;
	    $m = $wgAllMessagesFo[$key];

	    if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
	    else return $m;
	}

}

?>
