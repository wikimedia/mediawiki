<?

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesFo = array(
	NS_MEDIA            => "Miðil",
	NS_SPECIAL          => "Serstakur",
	NS_MAIN             => "",
	NS_TALK             => "Kjak",
	NS_USER             => "Brúkari",
	NS_USER_TALK        => "Brúkari_kjak",
	NS_WIKIPEDIA        => "Wikipedia",
	NS_WIKIPEDIA_TALK   => "Wikipedia_kjak",
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
);

/* private */ $wgQuickbarSettingsFo = array(
	"Eingin", "Fast vinstru", "Fast høgru", "Flótandi vinstru"
);

/* private */ $wgSkinNamesFo = array(
	"Standardur", "Nostalgiskur", "Cologne-bláur", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesFo = array(
	"Vís altíð sum PNG",
	"HTML um sera einfalt annars PNG",
	"HTML um møguligt annars PNG",
	"Lat verða sum TeX (til tekstkagara)",
	"Tilmælt nýtíðarkagara"
);

/* private */ $wgDateFormatsFo = array(
	"Ongi forrættindi",
	"januar 15, 2001",
	"15. januar 2001",
	"2001 januar 15",
	"2001-01-15"
);

/* private */ $wgUserTogglesFo = array(
	"hover"		      => "Vís sveimandi tekst yvur wikiávísingar",
	"underline"	   => "Undurstrika ávísingar",
	"highlightbroken" => "Brúka reyða ávísing til tómar síður",
	"justify"	   => "Stilla greinpart",
	"hideminor"	   => "Goym minni broytingar í seinast broytt listanum",		  # Skjul mindre ændringer i seneste ændringer listen
	"usenewrc"	   => "víðka seinastu broytingar lista<br>(ikki til alla kagarar)",
	"numberheadings"   => "Sjálvtalmerking av yvirskrift",
	"showtoolbar"	   => "Vís amboðslinju í rætting",
	"editondblclick"   => "Rætta síðu við at tvíklikkja (JavaScript)",
	"editsection"	   =>"Rætta greinpart við hjálp av [rætta]-ávísing",
	"editsectiononrightclick"=>"Rætta greinpart við at høgraklikkja<br> á yvirskrift av greinparti (JavaScript)",
	"showtoc"=>"Vís innihaldsyvurlit<br>(Til greinir við meira enn trimun greinpartum)",
	"rememberpassword" => "Minst til loyniorð næstu ferð",
	"editwidth" => "Rættingarkassin hevur fulla breid",
	"watchdefault" => "Vaka yvur nýggjum og broyttum greinum",
	"minordefault" => "Merk sum standard allar broytingar sum smærri",
	"previewontop" => "Vís forhondsvísning áðren rættingarkassan",
	"nocache" => "Minst ikki til síðurnar til næstu ferð"
);

/* private */ $wgBookstoreListFo = array(
	"Bokasolan.fo" => "http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1",
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesFo = array(
	"sunnudagur", "mánadagur", "týsdagur", "mikudagur", "hósdagur",
	"fríggjadagur", "leygardagur"
);

/* private */ $wgMonthNamesFo = array(
	"januar", "februar", "mars", "apríl", "mai", "juni",
	"juli", "august", "september", "oktober", "november",
	"desember"
);

/* private */ $wgMonthAbbreviationsFo = array(
	"jan", "feb", "mar", "apr", "mai", "jun", "jul", "aug",
	"sep", "okt", "nov", "des"
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsFo = array(
#   ID				       CASE  SYNONYMS
	MAG_REDIRECT		 => array( 0,	 '#redirect'		  ),
	MAG_NOTOC		 => array( 0,	 '__NOTOC__'		  ),
	MAG_FORCETOC		 => array( 0,	 '__FORCETOC__'		  ),
	MAG_TOC			 => array( 0,	 '__TOC__'		  ),
	MAG_NOEDITSECTION	 => array( 0,	 '__NOEDITSECTION__'	  ),
	MAG_START		 => array( 0,	 '__START__'		  ),
	MAG_CURRENTMONTH	 => array( 1,	 'CURRENTMONTH'		  ),
	MAG_CURRENTMONTHNAME	 => array( 1,	 'CURRENTMONTHNAME'	  ),
	MAG_CURRENTDAY		 => array( 1,	 'CURRENTDAY'		  ),
	MAG_CURRENTDAYNAME	 => array( 1,	 'CURRENTDAYNAME'	  ),
	MAG_CURRENTYEAR		 => array( 1,	 'CURRENTYEAR'		  ),
	MAG_CURRENTTIME		 => array( 1,	 'CURRENTTIME'		  ),
	MAG_NUMBEROFARTICLES	 => array( 1,	 'NUMBEROFARTICLES'	  ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,	 'CURRENTMONTHNAMEGEN'	  ),
	MAG_PAGENAME		 => array( 1,	 'PAGENAME'		  ),
	MAG_PAGENAMEE		 => array( 1,	 'PAGENAMEE'		  ),
	MAG_NAMESPACE		 => array( 1,	 'NAMESPACE'		  ),
	MAG_MSG			 => array( 0,	 'MSG:'			  ),
	MAG_SUBST		 => array( 0,	 'SUBST:'		  ),
	MAG_MSGNW		 => array( 0,	 'MSGNW:'		  ),
	MAG_END			 => array( 0,	 '__END__'		  ),
	MAG_IMG_THUMBNAIL	 => array( 1,	 'thumbnail', 'thumb'	  ),
	MAG_IMG_RIGHT		 => array( 1,	 'right'		  ),
	MAG_IMG_LEFT		 => array( 1,	 'left'			  ),
	MAG_IMG_NONE		 => array( 1,	 'none'			  ),
	MAG_IMG_WIDTH		 => array( 1,	 '$1px'			  ),
	MAG_IMG_CENTER		 => array( 1,	 'center', 'centre'	  ),
	MAG_IMG_FRAMED		 => array( 1,	 'framed', 'enframed', 'frame' ),
	MAG_INT			 => array( 0,	 'INT:'			  ),
	MAG_SITENAME		 => array( 1,	 'SITENAME'		  ),
	MAG_NS			 => array( 0,	 'NS:'			  ),
	MAG_LOCALURL		 => array( 0,	 'LOCALURL:'		  ),
	MAG_LOCALURLE		 => array( 0,	 'LOCALURLE:'		  ),
	MAG_SERVER		 => array( 0,	 'SERVER'		  ),
	MAG_GRAMMAR		 => array( 0,	 'GRAMMAR:'		  )
);

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

class LanguageFo extends Language {

	function getDefaultUserOptions () {
		$opt = Language::getDefaultUserOptions();
		return $opt;
		}

	function getBookstoreList () {
		global $wgBookstoreListFo ;
		return $wgBookstoreListFo ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFo;
		return $wgNamespaceNamesFo;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesFo;
		return $wgNamespaceNamesFo[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFo;

		foreach ( $wgNamespaceNamesFo as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFo;
		return $wgQuickbarSettingsFo;
	}

	function getSkinNames() {
		global $wgSkinNamesFo;
		return $wgSkinNamesFo;
	}

	function getMathNames() {
		global $wgMathNamesFo;
		return $wgMathNamesFo;
	}

	function getDateFormats() {
		global $wgDateFormatsFo;
		return $wgDateFormatsFo;
	}

	function getUserToggles() {
		global $wgUserTogglesFo;
		return $wgUserTogglesFo;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesFo;
		return $wgMonthNamesFo[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		global $wgMonthNamesFo;
		return $wgMonthNamesFo[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsFo;
		return $wgMonthAbbreviationsFo[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesFo;
		return $wgWeekdayNamesFo[$key-1];
	}

	# Inherit userAdjust()

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->date( $ts, $adj ) . " kl. " . $this->time( $ts, $adj );
	}

	# Inherit rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFo;
		return $wgValidSpecialPagesFo;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFo;
		return $wgSysopSpecialPagesFo;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFo;
		return $wgDeveloperSpecialPagesFo;
	}

	function getMessage( $key )
	{
	    global $wgAllMessagesFo, $wgAllMessagesEn;
	    $m = $wgAllMessagesFo[$key];

	    if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
	    else return $m;
	}

	# Inherit iconv()

	# Inherit ucfirst()

	# Inherit lcfirst()
	
	# Inherit checkTitleEncoding()
	
	# Inherit stripForSearch()
	
	# Inherit setAltEncoding()
	
	# Inherit recodeForEdit()
	
	# Inherit recodeInput()

	# Inherit isRTL()
	
	# Inherit getMagicWords()

}

?>
