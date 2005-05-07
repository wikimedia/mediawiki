<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once("LanguageUtf8.php");

# Tu môžete meniť názvy "namespaces" (no proste, rôznych častí encyklopédie),
# ale čísla nechajte tak, ako sú! Program to tak vyžaduje...
#
/* private */ $wgNamespaceNamesSk = array(
	-2	=> "Media",
	-1	=> "Špeciálne",
	0	=> "",
	1	=> "Komentár",
	2	=> "Redaktor",
	3	=> "Komentár_k_redaktorovi",
	4	=> "Wikipédia",
	5	=> "Komentár_k_Wikipédii",
	6	=> "Obrázok",
	7	=> "Komentár_k_obrázku",
	8	=> "MediaWiki",
	9	=> "Komentár_k_MediaWiki",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesSk = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Užívateľské nastavenia",
	"Watchlist"		=> "Sledované stránky",
	"Recentchanges" => "Ostatné úpravy",
	"Upload"		=> "Ulož obrázky",
	"Imagelist"		=> "Zoznam obrázkov",
	"Listusers"		=> "Registrovaní užívatelia",
	"Statistics"	=> "Štatistika",
	"Randompage"	=> "Náhodný článok",

	"Lonelypages"	=> "Nezaradené články",
	"Unusedimages"	=> "Nezaradené obrázky",
	"Popularpages"	=> "Obľúbené články",
	"Wantedpages"	=> "Najčítanejšie články",
	"Shortpages"	=> "Krátke články",
	"Longpages"		=> "Dlhé články",
	"Newpages"		=> "Nové články",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "Všetky články podľa nadpisu",

	"Ipblocklist"	=> "Zablokované IP adresy",
	"Maintenance" => "Údržba",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Eksterné stránky s knihami",
#	"Categories"	=> "Page categories",
	"Export"		=> "Export XML",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesSk = array(
	"Blockip"		=> "Zablokuj IP adresu",
	"Asksql"		=> "Dotaz do databázy",
	"Undelete"		=> "Zobraz a obnov vymazané stránky"
);

/* private */ $wgDeveloperSpecialPagesSk = array(
	"Lockdb"		=> "Zamkni databázu na zápis",
	"Unlockdb"		=> "Odomkni databázu na zápis",
);

/* private */ $wgAllMessagesSk = array(

# User Toggles

"tog-underline" => "Podčiarkuj linky",
"tog-highlightbroken" => "Neexistujúce linky zobrazuj červenou.",
"tog-justify"	=> "Zarovnávaj odstavce",
"tog-hideminor" => "V posledných úpravách neukazuj drobné úpravy",
"tog-usenewrc" => "Špeciálne zobrazenie posledných úprav (vyžaduje JavaScript)",
"tog-numberheadings" => "Automaticky čísluj odstavce",
"tog-showtoolbar" => "Show edit toolbar",
"tog-rememberpassword" => "Pamätaj si heslo aj nabudúce",
"tog-editwidth" => "Maximálna šírka editovacieho okna",
"tog-editondblclick" => "Edituj stránky po dvojkliku (JavaScript)",
"tog-watchdefault" => "Upozorňuj na nové a novu upravené stránky",
"tog-minordefault" => "Označ všetky zmeny ako drobné",
"tog-previewontop" => "Zobrazuj ukážku pred editovacím oknom, a nie až za ním",

# Dates
#

'sunday' => 'Nedeľa',
'monday' => 'Pondelok',
'tuesday' => 'Utorok',
'wednesday' => 'Streda',
'thursday' => "Štvrtok",
'friday' => 'Piatok',
'saturday' => 'Sobota',
'january' => 'Január',
'february' => 'Február',
'march' => 'Marec',
'april' => 'Apríl',
'may_long' => 'Máj',
'june' => 'Jún',
'july' => 'Júl',
'august' => 'August',
'september' => 'September',
'october' => 'Október',
'november' => 'November',
'december' => 'December',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mar',
'apr' => 'Apr',
'may' => 'Máj',
'jun' => 'Jún',
'jul' => 'Júl',
'aug' => 'Aug',
'sep' => 'Sep',
'oct' => 'Okt',
'nov' => 'Nov',
'dec' => 'Dec',

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Hlavná stránka",
"about"			=> "Úvod",
"aboutsite"      => "O Wikipédii",
"aboutpage"		=> "Wikipédia:Úvod",
"help"			=> "Pomoc",
"helppage"		=> "Wikipédia:Pomoc",
"wikititlesuffix" => "Wikipédia",
"bugreports"	=> "Známe_chyby",
"bugreportspage" => "Wikipédia:Známe_chyby",
"faq"			=> "FAQ",
"faqpage"		=> "Wikipédia:FAQ",
"edithelp"		=> "Informácie pre redaktorov",
"edithelppage"	=> "Wikipédia:Ako_editovať_stránku",
"cancel"		=> "Storno",
"qbfind"		=> "Nájdi",
"qbbrowse"		=> "Listuj",
"qbedit"		=> "Edituj",
"qbpageoptions" => "Možnosti stránky",
"qbpageinfo"	=> "Informácie o stránke",
"qbmyoptions"	=> "Moje nastavenia",
"mypage"		=> "Moja stránka",
"mytalk"		=> "Moje komentáre",
"currentevents" => "Aktuality",
"errorpagetitle" => "Chyba",
"returnto"		=> "Späť na $1.",
"tagline"      	=> "Z Wikipédie, slobodnej encyklopédie.",
"whatlinkshere"	=> "Sem ukazujú stránky",
"help"			=> "Pomoc",
"search"		=> "Hľadaj",
"go"		=> "Choď",
"history"		=> "Staršie verzie",
"printableversion" => "Veria na tlač",
"editthispage"	=> "Edituj stránku",
"deletethispage" => "Vymaž stránku",
"protectthispage" => "Zamkni stránku",
"unprotectthispage" => "Odomkni stránku",
"newpage" => "Nová stránka",
"talkpage"		=> "Komentuj stránku",
"articlepage"	=> "Zobraz článok",
"subjectpage"	=> "Zobraz tému", # For compatibility
"userpage" => "Zobraz užívateľovu stránku",
"wikipediapage" => "Zobraz metastránku",
"imagepage" => 	"Zobraz stránku s obrázkom",
"viewtalkpage" => "Zobraz komentáre",
"otherlanguages" => "Iné jazyky",
"redirectedfrom" => "(Presmerované z $1)",
"lastmodified"	=> "Posledné úpravy $1.",
"viewcount"		=> "Táto stránka bola zobrazená $1-krát.",
"gnunote" => "Celý text je dostupný pod podmienkami <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(Zdroj: http://www.wikipedia.org)",
"protectedpage" => "Zamknutá stránka",
"administrators" => "Wikipédia:Správcovia",
"sysoptitle"	=> "Potrebné oprávnenie: sysop",
"sysoptext"		=> "Požadovanú akciu môžu vykonať iba užívatelia s oprávnením \"sysop\".
Viď $1.",
"developertitle" => "Potrebné oprávnenie: vývojár",
"developertext"	=> "Požadovanú akciu môžu vykonať iba užívatelia s oprávnením \"developer\".
Viď $1.",
"nbytes"		=> "$1 bajtov",
"go"			=> "Choď",
"ok"			=> "OK",
"sitetitle"		=> "Wikipédia",
"sitesubtitle"	=> "The Free Encyclopedia",
"retrievedfrom" => "Zdroj: \"$1\"",
"newmessages" => "Máš $1.",
"newmessageslink" => "nových správ",


# Main script and global functions
#
"nosuchaction"	=> "Neznáma akcia",
"nosuchactiontext" => "Softvér Wikipédie nepozná akciu, ktorú vyžadujete URL",
"nosuchspecialpage" => "Neznáma špeciálna stránka",
"nospecialpagetext" => "Softvér Wikipédie nepozná takúto špeciálnu stránku.",

# General errors
#
"error"			=> "Chyba",
"databaseerror" => "Chyba v databáze",
"dberrortext"	=> "Nastala syntaktická chyba v dotaze do databázy.
Posledný pokus o dotaz bol:
<blockquote><tt>$1</tt></blockquote>
z funkcie \"<tt>$2</tt>\".
MySQL vrátil chybu \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Neviem sa pripojiť k databáze na $1",
"nodb"			=> "Neviem otvoriť databázu $1",
"readonly"		=> "Databáza je zamknutá",
"enterlockreason" => "Zadajte dôvod zamknutia vrátane odhadu, kedy očakávate odomknutie",
"readonlytext"	=> "Databáza Wikipédie je momentálne zamknutá, nové články a úpravy sú zablokované, pravdepodobne kvôli údržbe databázy. Po skončení tejto údržby bude Wikipédia opäť fungovať normálne.
Správca, ktorý nariadil uazmknutie, uvádza tento dôvod:
<p>$1",
"missingarticle" => "Databáza nenašla text stránky, ktorú by mala nájsť, menovite \"$1\".
Toto najskôr nie je chyba v databáze, ale v softvéri.
Prosím ohláste túto chybu správcovi, uveďte aj linku (URL).",
"internalerror" => "Vnútorná chyba",
"filecopyerror" => "Neviem skopírovať súbor \"$1\" na \"$2\".",
"filerenameerror" => "Neviem premenovať súbor \"$1\" na \"$2\".",
"filedeleteerror" => "Neviem vymazať súbor \"$1\".",
"filenotfound"	=> "Neviem nájsť súbor \"$1\".",
"unexpected"	=> "Nečakaná hodnota: \"$1\"=\"$2\".",
"formerror"		=> "Chyba: neviem odoslať formulár",
"badarticleerror" => "Na tejto stránke túto akciu vykonať nemožno.",
"cannotdelete"	=> "Neviem vymazať danú stránku alebo obrázok. (Možno to už vymazal niekto iný.)",
"badtitle"		=> "Zlý nadpis",
"badtitletext"	=> "Požadovaný nadpis bol neplatný, nezadaný, alebo nesprávne linkovaný.",
"perfdisabled" => "Prepáčte! Táto funkcia je počas špičky dočasne vypnutá kvôli veľkej záťaži; prosím vráťte sa medzi 02:00 a 14:00 UTC.",

# Login and logout pages
#
"logouttitle"	=> "Odhlásiť užívateľa",
"logouttext"	=> "Práve ste sa odhlásili.
Môžete naďalej používať Wikipédiu anonymne,
alebo sa môžete opäť prihlásiť pod rovnakým alebo odlišným užívateľským menom.\n",

"welcomecreation" => "<h2>Vitaj, $1!</h2><p>Vaše konto je vytvorené.
Nezabudnite si nastaviť užívateľské nastavenia.",

"loginpagetitle" => "Prihlásiť užívateľa",
"yourname"		=> "Vaše užívateľské meno",
"yourpassword"	=> "Vaše heslo",
"yourpasswordagain" => "Zopakujte heslo",
"newusersonly"	=> " (iba noví užívatelia)",
"remembermypassword" => "Pamätať si heslo aj po vypnutí počítača.",
"loginproblem"	=> "<b>Nastal problém pri prihlasovaní.</b><br />Skúste znova!",
"alreadyloggedin" => "<font color=red><b>Užívateľ $1, vy už ste prihlásený!</b></font><br />\n",

# Math
	'mw_math_png' => "Vždy vytvor PNG",
	'mw_math_simple' => "Na jednoduché použi HTML, inak PNG",
	'mw_math_html' => "Ak sa dá, použi HTML, inak PNG",
	'mw_math_source' => "Ponechaj TeX (pre textové prehliadače)",
	'mw_math_modern' => "Odporúčame pre moderné prehliadače",
	'mw_math_mathml' => 'MathML',

);

class LanguageSk extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSk;
		return $wgNamespaceNamesSk;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesSk;
		return $wgNamespaceNamesSk[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesSk;

		foreach ( $wgNamespaceNamesSk as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( 0 == strcasecmp( "Special", $text ) ) return -1;
		if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
		return false;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesSk;
		return $wgValidSpecialPagesSk;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesSk;
		return $wgSysopSpecialPagesSk;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesSk;
		return $wgDeveloperSpecialPagesSk;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesSk;
		if($wgAllMessagesSk[$key])
			return $wgAllMessagesSk[$key];
		return Language::getMessage( $key );
	}

	function fallback8bitEncoding() {
		return "iso-8859-2"; #?
	}

}
?>
