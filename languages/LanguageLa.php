<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesLa = array(
	-2	=> "Media",
	-1	=> "Specialis",
	0	=> "",
	1	=> "Disputatio",
	2	=> "Usor",
	3	=> "Disputatio_Usoris",
	4	=> "Wikipedia",
	5	=> "Disputatio_Wikipedia",
	6	=> "Imago",
	7	=> "Disputatio_Imaginis",
	8	=> "MediaWiki",
	9	=> "Disputatio_MediaWiki",
	10  => "Template",
	11  => "Template_talk"


) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsLa = array(
	"Nullus", "Constituere a sinistra", "Constituere a dextra", "Innens a sinistra"
);

/* private */ $wgSkinNamesLa = array(
	'standard' => "Norma",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Caerulus Colonia",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgMathNamesLa = array(
	"Semper vertere PNG",
	"HTML si admodum simplex, alioqui PNG",
	"HTML si fieri potest, alioqui PNG",
	"Stet ut TeX (pro navigatri texti)",
	"Commendatum pro navigatri recentes"
);

/* private */ $wgUserTogglesLa = array(
	"hover"		=> "Monstrare capsam impensam super wikinexos",
	"underline" => "Subscribere nexi",
	"highlightbroken" => "Formare nexos fractos <a href=\"\" class=\"new\">sici</a> (alioqui: sic<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Saepire capites",
	"hideminor" => "Celare mutationes recentes minores",
	"usenewrc" => "Mutationes recentes amplificatae (non efficit in tota navigatra)",
	"numberheadings" => "Numerare indices necessario",
	"rememberpassword" => "Recordari tesserae inter conventa (uti cookies)",
	"editwidth" => "Capsa recensitorum totam latitudinem habet",
	"editondblclick" => "Premere bis ut paginam recensere (uti JavaScript)",
	"watchdefault" => "Custodire res novas et mutatas",
	"minordefault" => "Notare totas mutations ut minor",
	"previewontop" => "Monstrare praevisus ante capsam recensiti, non post ipsam"
	
);

/* private */ $wgBookstoreListLa = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesLa = array(
	"dies Solis", "dies Lunae", "dies Martis", "dies Mercuri", "dies Iovis",
	"dies Veneris", "dies Saturni"
);

/* private */ $wgMonthNamesLa = array(
	"Ianuarii", "Februarii", "Martii", "Aprilis", "Maii", "Iunii",
	"Iulii", "Augusti", "Septembri", "Octobri", "Novembri",
	"Decembri"
);

/* private */ $wgMonthAbbreviationsLa = array(
	"ian", "feb", "mar", "apr", "mai", "iun", "iul", "aug",
	"sep", "oct", "nov", "dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesLa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Praeferentiae usoris",
	"Watchlist"		=> "Paginae custoditae",
	"Recentchanges" => "Mutationes recentes",
	"Upload"		=> "Onerare fascicula",
	"Imagelist"		=> "Index imaginum",
	"Listusers"		=> "Usores perscripti",
	"Statistics"	=> "Statisticas",
	"Randompage"	=> "Pagina fortuita",

	"Lonelypages"	=> "Paginae orbatae",
	"Unusedimages"	=> "Imagines orbatae",
	"Popularpages"	=> "Res populares",
	"Wantedpages"	=> "Res desideratissimae",
	"Shortpages"	=> "Res breves",
	"Longpages"		=> "Res longae",
	"Newpages"		=> "Res novae",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "Totae paginae (ex indice)",

	"Ipblocklist"	=> "Loci IP obstructi",
	"Maintenance" => "Pagina alimentori",
	"Specialpages"  => "", # "Paginae specialiae",
	"Contributions" => "", # "Conlationes",
	"Emailuser"		=> "", # "Mittere litteras electronicas ad usorum(?)",
	"Whatlinkshere" => "", # "Nexi ad hanc paginam",
	"Recentchangeslinked" => "", # "Mutationes conlata (?)",
	"Movepage"		=> "", # "Motare hanc paginam",
	"Booksources"	=> "Fontes externi (libri)",
#	"Categories"	=> "Page categories",
	"Export"		=> "Exportare in XML",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesLa = array(
	"Blockip"		=> "Obstruere locum IP",
	"Asksql"		=> "Quaerere basem dati",
	"Undelete"		=> "Videre et restituere paginas deletas"
);

/* private */ $wgDeveloperSpecialPagesLa = array(
	"Lockdb"		=> "Suspendere mutationes",
	"Unlockdb"		=> "Permittere mutationes",
	"Debug"			=> "Nuntii de refectis"
);


require_once( "LanguageUtf8.php" );

class LanguageLa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesLa;
		return $wgNamespaceNamesLa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesLa;
		return $wgNamespaceNamesLa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesLa;

		foreach ( $wgNamespaceNamesLa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsLa;
		return $wgQuickbarSettingsLa;
	}

	function getSkinNames() {
		global $wgSkinNamesLa;
		return $wgSkinNamesLa;
	}

	function getMathNames() {
		global $wgMathNamesLa;
		return $wgMathNamesLa;
	}

	function getUserToggles() {
		global $wgUserTogglesLa;
		return $wgUserTogglesLa;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesLa;
		return $wgMonthNamesLa[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsLa;
		return $wgMonthAbbreviationsLa[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesLa;
		return $wgWeekdayNamesLa[$key-1];
	}

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . (0 + substr( $ts, 6, 2 )) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesLa;
		return $wgValidSpecialPagesLa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesLa;
		return $wgSysopSpecialPagesLa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesLa;
		return $wgDeveloperSpecialPagesLa;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesLa, $wgAllMessagesEn;
		$m = $wgAllMessagesLa[$key];

		if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
		else return $m;
	}

}


?>
