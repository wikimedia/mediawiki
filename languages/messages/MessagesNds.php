<?php
/** Low German (Plattdüütsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Purodha
 * @author Slomox
 * @author The Evil IP address
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskuschoon',
	NS_USER             => 'Bruker',
	NS_USER_TALK        => 'Bruker_Diskuschoon',
	NS_PROJECT_TALK     => '$1_Diskuschoon',
	NS_FILE             => 'Datei',
	NS_FILE_TALK        => 'Datei_Diskuschoon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskuschoon',
	NS_TEMPLATE         => 'Vörlaag',
	NS_TEMPLATE_TALK    => 'Vörlaag_Diskuschoon',
	NS_HELP             => 'Hülp',
	NS_HELP_TALK        => 'Hülp_Diskuschoon',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskuschoon',
);

$namespaceAliases = array(
	'Diskussion' => NS_TALK,
	'Benutzer' => NS_USER,
	'Benutzer_Diskussion' => NS_USER_TALK,
	'$1_Diskussion' => NS_PROJECT_TALK,
	'Bild' => NS_FILE,
	'Bild_Diskussion' => NS_FILE_TALK,
	'MediaWiki_Diskussion' => NS_MEDIAWIKI_TALK,
	'Vorlage' => NS_TEMPLATE,
	'Vorlage_Diskussion' => NS_TEMPLATE_TALK,
	'Hilfe' => NS_HELP,
	'Hilfe_Diskussion' => NS_HELP_TALK,
	'Kategorie' => NS_CATEGORY,
	'Kategorie_Diskussion' => NS_CATEGORY_TALK,
);

$magicWords = array(
	'redirect'              => array( '0', '#wiederleiden', '#WEITERLEITUNG', '#REDIRECT' ),
	'notoc'                 => array( '0', '__KEENINHOLTVERTEKEN__', '__KEIN_INHALTSVERZEICHNIS__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__WIESINHOLTVERTEKEN__', '__INHALTSVERZEICHNIS_ERZWINGEN__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__INHOLTVERTEKEN__', '__INHALTSVERZEICHNIS__', '__TOC__' ),
	'noeditsection'         => array( '0', '__KEENÄNNERNLINK__', '__ABSCHNITTE_NICHT_BEARBEITEN__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'AKTMAAND', 'JETZIGER_MONAT', 'JETZIGER_MONAT_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'AKTMAANDNAAM', 'JETZIGER_MONATSNAME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'AKTMAANDNAAMGEN', 'JETZIGER_MONATSNAME_GENITIV', 'CURRENTMONTHNAMEGEN' ),
	'currentday'            => array( '1', 'AKTDAG', 'JETZIGER_KALENDERTAG', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'AKTDAGNAAM', 'JETZIGER_WOCHENTAG', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'AKTJOHR', 'JETZIGES_JAHR', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'AKTTIED', 'JETZIGE_UHRZEIT', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'ARTIKELTALL', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ),
	'pagename'              => array( '1', 'SIETNAAM', 'SEITENNAME', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'SIETNAAME', 'SEITENNAME_URL', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NAAMRUUM', 'NAMENSRAUM', 'NAMESPACE' ),
	'img_thumbnail'         => array( '1', 'duum', 'miniatur', 'thumbnail', 'thumb' ),
	'img_right'             => array( '1', 'rechts', 'right' ),
	'img_left'              => array( '1', 'links', 'left' ),
	'img_none'              => array( '1', 'keen', 'ohne', 'none' ),
	'img_center'            => array( '1', 'merrn', 'zentriert', 'center', 'centre' ),
	'img_framed'            => array( '1', 'rahmt', 'gerahmt', 'framed', 'enframed', 'frame' ),
	'sitename'              => array( '1', 'STEEDNAAM', 'PROJEKTNAME', 'SITENAME' ),
	'ns'                    => array( '0', 'NR:', 'NS:' ),
	'localurl'              => array( '0', 'STEEDURL:', 'LOKALE_URL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'STEEDURLE:', 'LOCALURLE:' ),
	'grammar'               => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
);


$bookstoreList = array(
	'Verteken vun leverbore Böker'  => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1=0&y=0',
	'abebooks.de'                   => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de'                     => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Lehmanns Fachbuchhandlung'     => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Spezial',
	NS_MAIN           => '',
	NS_TALK           => 'Diskuschoon',
	NS_USER           => 'Bruker',
	NS_USER_TALK      => 'Bruker_Diskuschoon',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => '$1_Diskuschoon',
	NS_FILE           => 'Bild',
	NS_FILE_TALK      => 'Bild_Diskuschoon',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_Diskuschoon',
	NS_TEMPLATE       => 'Vörlaag',
	NS_TEMPLATE_TALK  => 'Vörlaag_Diskuschoon',
	NS_HELP           => 'Hülp',
	NS_HELP_TALK      => 'Hülp_Diskuschoon',
	NS_CATEGORY       => 'Kategorie',
	NS_CATEGORY_TALK  => 'Kategorie_Diskuschoon',
);

$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';
$separatorTransformTable = array(',' => '.', '.' => ',' );

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j., Y',
	'mdy both' => 'H:i, M j., Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'H:i, j. M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j.',
	'ymd both' => 'H:i, Y M j.',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dubbelte Redirects' ),
	'BrokenRedirects'           => array( 'Kaputte Redirects' ),
	'Disambiguations'           => array( 'Mehrdüdige Begrepen' ),
	'Userlogin'                 => array( 'Anmellen' ),
	'Userlogout'                => array( 'Afmellen' ),
	'CreateAccount'             => array( 'Brukerkonto anleggen' ),
	'Preferences'               => array( 'Instellungen' ),
	'Watchlist'                 => array( 'Oppasslist' ),
	'Recentchanges'             => array( 'Toletzt ännert', 'Neeste Ännern' ),
	'Upload'                    => array( 'Hoochladen' ),
	'Listfiles'                 => array( 'Dateilist' ),
	'Newimages'                 => array( 'Nee Datein' ),
	'Listusers'                 => array( 'Brukers' ),
	'Listgrouprights'           => array( 'Gruppenrechten' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Tofällige Siet' ),
	'Lonelypages'               => array( 'Weetsieden' ),
	'Uncategorizedpages'        => array( 'Sieden ahn Kategorie' ),
	'Uncategorizedcategories'   => array( 'Kategorien ahn Kategorie' ),
	'Uncategorizedimages'       => array( 'Datein ahn Kategorie' ),
	'Uncategorizedtemplates'    => array( 'Vörlagen ahn Kategorie' ),
	'Unusedcategories'          => array( 'Nich bruukte Kategorien' ),
	'Unusedimages'              => array( 'Nich bruukte Datein' ),
	'Wantedpages'               => array( 'Wünschte Sieden' ),
	'Wantedcategories'          => array( 'Wünschte Kategorien' ),
	'Mostlinked'                => array( 'Veel lenkte Sieden' ),
	'Mostlinkedcategories'      => array( 'Veel bruukte Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Veel bruukte Vörlagen' ),
	'Mostimages'                => array( 'Veel bruukte Datein' ),
	'Mostcategories'            => array( 'Sieden mit vele Kategorien' ),
	'Mostrevisions'             => array( 'Faken ännerte Sieden' ),
	'Fewestrevisions'           => array( 'Kuum ännerte Sieden' ),
	'Shortpages'                => array( 'Korte Sieden' ),
	'Longpages'                 => array( 'Lange Sieden' ),
	'Newpages'                  => array( 'Nee Sieden' ),
	'Ancientpages'              => array( 'Ole Sieden' ),
	'Deadendpages'              => array( 'Sackstraatsieden' ),
	'Protectedpages'            => array( 'Schuulte Sieden' ),
	'Protectedtitles'           => array( 'Sperrte Titels' ),
	'Allpages'                  => array( 'Alle Sieden' ),
	'Prefixindex'               => array( 'Sieden de anfangt mit' ),
	'Ipblocklist'               => array( 'List vun blockte IPs' ),
	'Specialpages'              => array( 'Spezialsieden' ),
	'Contributions'             => array( 'Bidrääg' ),
	'Emailuser'                 => array( 'E-Mail an Bruker' ),
	'Confirmemail'              => array( 'E-Mail bestätigen' ),
	'Whatlinkshere'             => array( 'Wat wiest hier hen' ),
	'Recentchangeslinked'       => array( 'Ännern an lenkte Sieden' ),
	'Movepage'                  => array( 'Schuven' ),
	'Blockme'                   => array( 'Proxy-Sparr' ),
	'Booksources'               => array( 'ISBN-Söök' ),
	'Categories'                => array( 'Kategorien' ),
	'Export'                    => array( 'Exporteren' ),
	'Allmessages'               => array( 'Systemnarichten' ),
	'Log'                       => array( 'Logbook' ),
	'Blockip'                   => array( 'Blocken' ),
	'Undelete'                  => array( 'Wedderhalen' ),
	'Import'                    => array( 'Importeren' ),
	'Lockdb'                    => array( 'Datenbank sparren' ),
	'Unlockdb'                  => array( 'Datenbank freegeven' ),
	'Userrights'                => array( 'Brukerrechten' ),
	'MIMEsearch'                => array( 'MIME-Typ-Söök' ),
	'FileDuplicateSearch'       => array( 'Dubbelte-Datein-Söök' ),
	'Unwatchedpages'            => array( 'Sieden op keen Oppasslist' ),
	'Listredirects'             => array( 'List vun Redirects' ),
	'Revisiondelete'            => array( 'Versionen wegsmieten' ),
	'Unusedtemplates'           => array( 'Nich bruukte Vörlagen' ),
	'Randomredirect'            => array( 'Tofällig Redirect' ),
	'Mypage'                    => array( 'Miene Brukersiet' ),
	'Mytalk'                    => array( 'Miene Diskuschoonssiet' ),
	'Mycontributions'           => array( 'Miene Bidrääg' ),
	'Listadmins'                => array( 'Administraters' ),
	'Listbots'                  => array( 'Bots' ),
	'Popularpages'              => array( 'Veel besöchte Sieden' ),
	'Search'                    => array( 'Söök' ),
	'Resetpass'                 => array( 'Passwoort trüchsetten' ),
	'Withoutinterwiki'          => array( 'Sieden ahn Spraaklenken' ),
	'MergeHistory'              => array( 'Versionshistorie tohoopbringen' ),
	'Filepath'                  => array( 'Dateipadd' ),
	'Blankpage'                 => array( 'Leddige Sied' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Verwies ünnerstrieken',
'tog-highlightbroken'         => 'Verwies op leddige Sieten hervörheven',
'tog-justify'                 => 'Text as Blocksatz',
'tog-hideminor'               => 'Kene lütten Ännern in letzte Ännern wiesen',
'tog-hidepatrolled'           => 'Nakeken Ännern bi „Toletzt ännert“ nich wiesen',
'tog-newpageshidepatrolled'   => 'Nakeken Ännern bi „Ne’e Sieden“ nich wiesen',
'tog-extendwatchlist'         => 'Oppasslist utwieden, dat se all, un nich blot de jüngsten, wiest',
'tog-usenewrc'                => 'Utwiedt letzte Ännern (bruukt JavaScript)',
'tog-numberheadings'          => 'Överschrieven automatsch nummereern',
'tog-showtoolbar'             => 'Editeer-Warktüüchlist wiesen',
'tog-editondblclick'          => 'Sieden mit Dubbelklick ännern (JavaScript)',
'tog-editsection'             => 'Links för dat Ännern vun en Afsatz wiesen',
'tog-editsectiononrightclick' => 'En Afsatz mit en Rechtsklick ännern (Javascript)',
'tog-showtoc'                 => "Wiesen vun'n Inholtsverteken bi Sieten mit mehr as dree Överschriften",
'tog-rememberpassword'        => 'Duersam Inloggen',
'tog-watchcreations'          => 'Nee schrevene Sieden op miene Oppasslist setten',
'tog-watchdefault'            => 'Op ne’e un ännerte Sieden oppassen',
'tog-watchmoves'              => 'Sieden, de ik schuuv, to de Oppasslist todoon',
'tog-watchdeletion'           => 'Sieden, de ik wegsmiet, to de Oppasslist todoon',
'tog-previewontop'            => 'Vörschau vör dat Editeerfinster wiesen',
'tog-previewonfirst'          => "Vörschau bi'n eersten Ännern wiesen",
'tog-nocache'                 => 'Sietencache deaktiveern',
'tog-enotifwatchlistpages'    => 'Schriev mi en Nettbreef, wenn ene Siet, op de ik oppass, ännert warrt',
'tog-enotifusertalkpages'     => 'Schriev mi en Nettbreef, wenn ik ne’e Narichten heff',
'tog-enotifminoredits'        => 'Schriev mi en Nettbreef, ok wenn dat blots en lütte Ännern weer',
'tog-enotifrevealaddr'        => 'Miene Nettbreefadress in Bestätigungsnettbreven wiesen',
'tog-shownumberswatching'     => 'Wies de Tall vun Brukers, de op disse Siet oppasst',
'tog-fancysig'                => 'Signatur as Wikitext behanneln (ahn automaatsch Lenk)',
'tog-externaleditor'          => 'Jümmer extern Editor bruken (blot för Lüüd, de sik dor mit utkennt, dor mutt noch mehr op dien Reekner instellt warrn, dat dat geiht)',
'tog-externaldiff'            => 'Extern Warktüüch to’n Wiesen vun Ünnerscheden as Standard bruken (blot för Lüüd, de sik dor mit utkennt, dor mutt noch mehr op dien Reekner instellt warrn, dat dat geiht)',
'tog-showjumplinks'           => '„Wesseln-na“-Lenken tolaten',
'tog-uselivepreview'          => 'Live-Vörschau bruken (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Segg mi bescheid, wenn ik keen Tosamenfaten geven heff, wat ik allens ännert heff',
'tog-watchlisthideown'        => 'Ännern vun mi sülvs op de Oppasslist nich wiesen',
'tog-watchlisthidebots'       => 'Ännern vun Bots op de Oppasslist nich wiesen',
'tog-watchlisthideminor'      => 'Lütte Ännern op de Oppasslist nich wiesen',
'tog-watchlisthideliu'        => 'Ännern vun anmellt Brukers nich wiesen',
'tog-watchlisthideanons'      => 'Ännern vun anonyme Brukers nich wiesen',
'tog-watchlisthidepatrolled'  => 'Nakeken Ännern op de Oppasslist nich wiesen',
'tog-nolangconversion'        => 'Variantenkonverschoon utschalten',
'tog-ccmeonemails'            => 'vun Nettbreven, de ik wegschick, mi sülvst Kopien tostüren',
'tog-diffonly'                => "Na ''{{int:showdiff}}'' nich de kumplette Sied wiesen",
'tog-showhiddencats'          => 'Wies verstekene Kategorien',
'tog-norollbackdiff'          => 'Ünnerscheed na’t Trüchsetten nich wiesen',

'underline-always'  => 'Jümmer',
'underline-never'   => 'Nienich',
'underline-default' => 'so as in’n Nettkieker instellt',

# Dates
'sunday'        => 'Sünndag',
'monday'        => 'Maandag',
'tuesday'       => 'Dingsdag',
'wednesday'     => 'Merrweek',
'thursday'      => 'Dunnersdag',
'friday'        => 'Freedag',
'saturday'      => 'Sünnavend',
'sun'           => 'Sü',
'mon'           => 'Ma',
'tue'           => 'Di',
'wed'           => 'Mi',
'thu'           => 'Du',
'fri'           => 'Fr',
'sat'           => 'Sa',
'january'       => 'Januar',
'february'      => 'Februar',
'march'         => 'März',
'april'         => 'April',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Januar',
'february-gen'  => 'Februar',
'march-gen'     => 'März',
'april-gen'     => 'April',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'August',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mär',
'apr'           => 'Apr.',
'may'           => 'Mai',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt',
'nov'           => 'Nov.',
'dec'           => 'Dez',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Sieden in de Kategorie „$1“',
'subcategories'                  => 'Ünnerkategorien',
'category-media-header'          => 'Mediendatein in de Kategorie „$1“',
'category-empty'                 => "''In disse Kategorie sünd aktuell kene Sieden.''",
'hidden-categories'              => '{{PLURAL:$1|Verstekene Kategorie|Verstekene Kategorien}}',
'hidden-category-category'       => 'Verstekene Kategorien',
'category-subcat-count'          => '{{PLURAL:$2|De Kategorie hett disse Ünnerkategorie:|De Kategorie hett disse Ünnerkategorie{{PLURAL:$1||n}}, vun $2 Ünnerkategorien alltohoop:}}',
'category-subcat-count-limited'  => 'De Kategorie hett disse {{PLURAL:$1|Ünnerkategorie|$1 Ünnerkategorien}}:',
'category-article-count'         => '{{PLURAL:$2|De Kategorie bargt disse Siet:|De Kategorie bargt disse {{PLURAL:$1|Siet|Sieden}}, vun $2 Sieden alltohoop:}}',
'category-article-count-limited' => 'De Kategorie bargt disse {{PLURAL:$1|Siet|$1 Sieden}}:',
'category-file-count'            => '{{PLURAL:$2|De Kategorie bargt disse Datei:|De Kategorie bargt disse Datei{{PLURAL:$1||n}}, vun $2 Datein alltohoop:}}',
'category-file-count-limited'    => 'De Kategorie bargt disse {{PLURAL:$1|Datei|$1 Datein}}:',
'listingcontinuesabbrev'         => 'wieder',

'mainpagetext'      => "'''De MediaWiki-Software is mit Spood installeert worrn.'''",
'mainpagedocfooter' => 'Kiek de [http://meta.wikimedia.org/wiki/MediaWiki_localisation Dokumentatschoon för dat Anpassen vun de Brukerböversiet]
un dat [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Brukerhandbook] för Hülp to de Bruuk un Konfiguratschoon.',

'about'         => 'Över',
'article'       => 'Artikel',
'newwindow'     => '(apent sik in en nieg Finster)',
'cancel'        => 'Afbreken',
'moredotdotdot' => 'Mehr...',
'mypage'        => 'Mien Siet',
'mytalk'        => 'Mien Diskuschoon',
'anontalk'      => 'Diskuschoonssiet vun disse IP',
'navigation'    => 'Navigatschoon',
'and'           => '&#32;un',

# Cologne Blue skin
'qbfind'         => 'Finnen',
'qbbrowse'       => 'Blädern',
'qbedit'         => 'Ännern',
'qbpageoptions'  => 'Disse Sied',
'qbpageinfo'     => 'Sietendaten',
'qbmyoptions'    => 'Instellen',
'qbspecialpages' => 'Spezialsieten',
'faq'            => 'Faken stellte Fragen',
'faqpage'        => 'Project:Faken stellte Fragen',

# Vector skin
'vector-action-addsection'   => 'Thema tofögen',
'vector-action-delete'       => 'Wegdoon',
'vector-action-move'         => 'Schuven',
'vector-action-protect'      => 'Schulen',
'vector-action-undelete'     => 'Wedderhalen',
'vector-action-unprotect'    => 'Freegeven',
'vector-namespace-category'  => 'Kategorie',
'vector-namespace-help'      => 'Hülpsied',
'vector-namespace-image'     => 'Datei',
'vector-namespace-main'      => 'Sied',
'vector-namespace-media'     => 'Mediensied',
'vector-namespace-mediawiki' => 'Naricht',
'vector-namespace-project'   => 'Projektsied',
'vector-namespace-special'   => 'Spezialsied',
'vector-namespace-talk'      => 'Diskuschoon',
'vector-namespace-template'  => 'Vörlaag',
'vector-namespace-user'      => 'Brukersied',
'vector-view-create'         => 'Opstellen',
'vector-view-edit'           => 'Ännern',
'vector-view-history'        => 'Historie bekieken',
'vector-view-view'           => 'Lesen',
'vector-view-viewsource'     => 'Borntext bekieken',

'errorpagetitle'    => 'Fehler',
'returnto'          => 'Trüch to $1.',
'tagline'           => 'Vun {{SITENAME}}',
'help'              => 'Hülp',
'search'            => 'Söken',
'searchbutton'      => 'Söken',
'go'                => 'Gah',
'searcharticle'     => 'Los',
'history'           => 'Historie',
'history_short'     => 'Historie',
'updatedmarker'     => 'bearbeidt, in de Tiet sietdem ik toletzt dor weer',
'info_short'        => 'Informatschoon',
'printableversion'  => 'Druckversion',
'permalink'         => 'Duurlenk',
'print'             => 'Drucken',
'edit'              => 'Ännern',
'create'            => 'Opstellen',
'editthispage'      => 'Disse Siet ännern',
'create-this-page'  => 'Siet opstellen',
'delete'            => 'Wegsmieten',
'deletethispage'    => 'Disse Siet wegsmieten',
'undelete_short'    => '{{PLURAL:$1|ene Version|$1 Versionen}} wedderhalen',
'protect'           => 'Schulen',
'protect_change'    => 'ännern',
'protectthispage'   => 'Siet schulen',
'unprotect'         => 'Freegeven',
'unprotectthispage' => 'Schuul opheben',
'newpage'           => 'Ne’e Siet',
'talkpage'          => 'Diskuschoon',
'talkpagelinktext'  => 'Diskuschoon',
'specialpage'       => 'Spezialsiet',
'personaltools'     => 'Persönliche Warktüüch',
'postcomment'       => 'Afsnidd tofögen',
'articlepage'       => 'Artikel',
'talk'              => 'Diskuschoon',
'views'             => 'Ansichten',
'toolbox'           => 'Warktüüch',
'userpage'          => 'Brukersiet ankieken',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Dateisied',
'mediawikipage'     => 'Systemnaricht ankieken',
'templatepage'      => 'Vörlaag ankieken',
'viewhelppage'      => 'Helpsiet ankieken',
'categorypage'      => 'Kategorie ankieken',
'viewtalkpage'      => 'Diskuschoon ankieken',
'otherlanguages'    => 'Annere Spraken',
'redirectedfrom'    => '(wiederwiest vun $1)',
'redirectpagesub'   => 'Redirectsiet',
'lastmodifiedat'    => 'Disse Siet is toletzt üm $2, $1 ännert worrn.',
'viewcount'         => 'Disse Siet is {{PLURAL:$1|een|$1}} Maal opropen worrn.',
'protectedpage'     => 'Schuulte Sieden',
'jumpto'            => 'Wesseln na:',
'jumptonavigation'  => 'Navigatschoon',
'jumptosearch'      => 'Söök',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Över {{SITENAME}}',
'aboutpage'            => 'Project:Över_{{SITENAME}}',
'copyright'            => 'Inholt is verfögbor ünner de $1.',
'copyrightpage'        => '{{ns:project}}:Lizenz',
'currentevents'        => 'Aktuell Schehn',
'currentevents-url'    => 'Project:Aktuell Schehn',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Bearbeidenshülp',
'edithelppage'         => 'Help:Ännern',
'helppage'             => 'Help:Hülp',
'mainpage'             => 'Hööftsiet',
'mainpage-description' => 'Hööftsiet',
'policy-url'           => 'Project:Richtlienen',
'portal'               => '{{SITENAME}}-Portal',
'portal-url'           => 'Project:{{SITENAME}}-Portal',
'privacy'              => 'Över Datenschutz',
'privacypage'          => 'Project:Datenschutz',

'badaccess'        => 'Fehler bi de Rechten',
'badaccess-group0' => 'Du hest keen Verlööf för disse Akschoon.',
'badaccess-groups' => 'Disse Akschoon is blots för Brukers ut {{PLURAL:$2|de Brukergrupp|een vun de Brukergruppen}} $1.',

'versionrequired'     => 'Version $1 vun MediaWiki nödig',
'versionrequiredtext' => 'Version $1 vun MediaWiki is nödig, disse Siet to bruken. Kiek op de Siet [[Special:Version|Version]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Vun „$1“',
'youhavenewmessages'      => 'Du hest $1 ($2).',
'newmessageslink'         => 'Ne’e Narichten',
'newmessagesdifflink'     => 'Ünnerscheed to vörher',
'youhavenewmessagesmulti' => 'Du hest ne’e Narichten op $1',
'editsection'             => 'ännern',
'editold'                 => 'ännern',
'viewsourceold'           => 'Borntext wiesen',
'editlink'                => 'ännern',
'viewsourcelink'          => 'Borntext ankieken',
'editsectionhint'         => 'Ännere Afsnitt: $1',
'toc'                     => 'Inholtsverteken',
'showtoc'                 => 'wiesen',
'hidetoc'                 => 'Nich wiesen',
'thisisdeleted'           => 'Ankieken oder weerholen vun $1?',
'viewdeleted'             => '$1 ankieken?',
'restorelink'             => '{{PLURAL:$1|ene löschte Version|$1 löschte Versionen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungülligen Abo-Typ.',
'feed-unavailable'        => 'Dat gifft kene Feeds',
'site-rss-feed'           => 'RSS-Feed för $1',
'site-atom-feed'          => 'Atom-Feed för $1',
'page-rss-feed'           => 'RSS-Feed för „$1“',
'page-atom-feed'          => 'Atom-Feed för „$1“',
'red-link-title'          => '$1 (noch nich vörhannen)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Brukersied',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezialsied',
'nstab-project'   => 'Över',
'nstab-image'     => 'Bild',
'nstab-mediawiki' => 'Naricht',
'nstab-template'  => 'Vörlaag',
'nstab-help'      => 'Hülp',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Disse Aktschoon gifft dat nich',
'nosuchactiontext'  => 'De in de URL angeven Akschoon warrt nich ünnerstütt.
Villicht hest du in de URL en Tippfehler oder büst en verkehrten Lenk nagahn.
Dat kann aver ok op en Bug in de Software henwiesen, de op {{SITENAME}} bruukt warrt.',
'nosuchspecialpage' => 'Disse Spezialsiet gifft dat nich',
'nospecialpagetext' => 'Disse Spezialsiet warrt vun de MediaWiki-Software nich ünnerstütt',

# General errors
'error'                => 'Fehler',
'databaseerror'        => 'Fehler in de Datenbank',
'dberrortext'          => 'Dor weer en Syntaxfehler in de Datenbankaffraag.
De letzte Datenbankaffraag weer:

<blockquote><tt>$1</tt></blockquote>

ut de Funktschoon <tt>$2</tt>.
MySQL mell den Fehler <tt>$3: $4</tt>.',
'dberrortextcl'        => 'Dor weer en Syntaxfehler in de Datenbankaffraag.
De letzte Datenbankaffraag weer: $1 ut de Funktschoon <tt>$2</tt>.
MySQL mell den Fehler: <tt>$3: $4</tt>.',
'laggedslavemode'      => 'Wohrschau: Disse Siet is villicht nich mehr op den ne’esten Stand.',
'readonly'             => 'Datenbank is sparrt',
'enterlockreason'      => 'Giff den Grund an, worüm de Datenbank sparrt warrn schall un taxeer, wo lang de Sparr duert',
'readonlytext'         => 'De Datenbank vun {{SITENAME}} is opstunns sparrt. Versöök dat later noch eenmal, duert meist nich lang, denn geiht dat wedder.

As Grund för de Sparr is angeven: $1',
'missing-article'      => 'De Text för „$1“ $2 is nich in de Datenbank.

Dat kann vörkamen, wenn een op en olen Lenk op den Ünnerscheed twischen twee Versionen oder to en ole Version klickt hett un de Sied al wegsmeten is.

Wenn dat nich de Fall is, denn hest du villicht en Fehler in de Software funnen. Mell dat an en [[Special:ListUsers/sysop|Administrater]] un segg em ok de URL.',
'missingarticle-rev'   => '(Versionsnr.: $1)',
'missingarticle-diff'  => '(Ünnerscheed: $1, $2)',
'readonly_lag'         => 'De Datenbank is automaatsch sperrt worrn, dat sik de opdeelten Datenbankservers mit den Hööft-Datenbankserver afglieken köönt.',
'internalerror'        => 'Internen Fehler',
'internalerror_info'   => 'Internen Fehler: $1',
'filecopyerror'        => 'De Software kunn de Datei ‚$1‘ nich na ‚$2‘ koperen.',
'filerenameerror'      => 'De Software kunn de Datei ‚$1‘ nich na ‚$2‘ ümnömen.',
'filedeleteerror'      => 'De Software kunn de Datei ‚$1‘ nich wegsmieten.',
'directorycreateerror' => 'Kunn Orner „$1“ nich anleggen.',
'filenotfound'         => 'De Software kunn de Datei ‚$1‘ nich finnen.',
'fileexistserror'      => 'Kunn de Datei „$1“ nich schrieven: de gifft dat al',
'unexpected'           => 'Unvermoodten Weert: ‚$1‘=‚$2‘.',
'formerror'            => 'Fehler: De Software kunn dat Formular nich verarbeiden',
'badarticleerror'      => 'Disse Aktschoon kann op disse Siet nich anwennt warrn.',
'cannotdelete'         => 'De Software kunn de angevene Siet nich wegsmieten. (Mööglicherwies is de al vun en annern wegsmeten worrn.)',
'badtitle'             => 'Ungülligen Titel',
'badtitletext'         => 'De Titel vun de opropene Siet weer ungüllig, leddig, oder en ungülligen Spraaklink vun en annern Wiki.',
'perfcached'           => 'Disse Daten kamen ut den Cache un sünd mööglicherwies nich aktuell:',
'perfcachedts'         => 'Disse Daten sünd ut’n Cache, tolest aktuell maakt worrn sünd se $1.',
'querypage-no-updates' => "'''Dat aktuell Maken vun disse Siet is opstunns utstellt. De Daten warrt för’t Eerste veröllert blieven.'''",
'wrong_wfQuery_params' => 'Falschen Parameter för wfQuery()<br />
Funktschoon: $1<br />
Query: $2',
'viewsource'           => 'Dokmentborn ankieken',
'viewsourcefor'        => 'för $1',
'actionthrottled'      => 'Akschoon in de Tall begrenzt',
'actionthrottledtext'  => 'Disse Akschoon kann blot en bestimmte Tall mal in en bestimmte Tiet utföhrt warrn. Du hest disse Grenz nu anreckt. Versöök dat later noch wedder.',
'protectedpagetext'    => 'Disse Siet is sparrt, dat een se nich ännern kann.',
'viewsourcetext'       => 'Kannst den Borntext vun disse Siet ankieken un koperen:',
'protectedinterface'   => 'Op disse Siet staht Narichtentexte för dat System un de Siet is dorüm sparrt.',
'editinginterface'     => "'''Wohrschau:''' Disse Siet bargt Text, de vun de MediaWiki-Software för ehr Böverflach bruukt warrt.
Wat du hier ännerst, warkt sik op dat kumplette Wiki ut.
Wenn du Text översetten wist, de betherto noch gornich översett is, denn maak dat opbest op [http://translatewiki.net/wiki/Main_Page?setlang=nds translatewiki.net], dat Översett-Projekt vun MediaWiki.",
'sqlhidden'            => '(SQL-Affraag versteken)',
'cascadeprotected'     => 'Disse Siet is sperrt un kann nich ännert warrn. Dat kummt dorvun dat se in disse {{PLURAL:$1|Siet|Sieden}} inbunnen is, de över Kaskadensperr schuult {{PLURAL:$1|is|sünd}}:
$2',
'namespaceprotected'   => "Du hest keen Rechten, Sieden in’n Naamruum '''$1''' to ännern.",
'customcssjsprotected' => 'Du hest keen Rechten, disse Siet to ännern. Dor sünd persönliche Instellungen vun en annern Bruker in.',
'ns-specialprotected'  => 'Spezialsieden köönt nich ännert warrn.',
'titleprotected'       => "Disse Siet is gegen dat nee Opstellen vun [[User:$1|$1]] schuult worrn.
As Grund is angeven: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Slechte Konfiguratschoon: unbekannten Virenscanner: ''$1''",
'virus-scanfailed'     => 'Scan hett nich klappt (Code $1)',
'virus-unknownscanner' => 'Unbekannten Virenscanner:',

# Login and logout pages
'logouttext'                 => "'''Du büst nu afmellt.'''

Du kannst {{SITENAME}} nu anonym wiederbruken oder di ünner dissen oder en annern Brukernaam wedder [[Special:UserLogin|anmellen]].
Denk dor an, dat welk Sieden ünner Ümstänn noch jümmer so wiest warrn köönt, as wenn du anmellt weerst. Dat ännert sik, wenn du den Cache vun dien Browser leddig maakst.",
'welcomecreation'            => '== Willkamen, $1! ==
Dien Brukerkonto is nu inricht.
Vergeet nich, de Sied för di persönlich [[Special:Preferences|intostellen]].',
'yourname'                   => 'Dien Brukernaam',
'yourpassword'               => 'Dien Passwoort',
'yourpasswordagain'          => 'Passwoort nochmal ingeven',
'remembermypassword'         => 'Duersam inloggen',
'yourdomainname'             => 'Diene Domään:',
'externaldberror'            => 'Dat geev en Fehler bi de externe Authentifizerungsdatenbank oder du dröffst dien extern Brukerkonto nich ännern.',
'login'                      => 'Anmellen',
'nav-login-createaccount'    => 'Nee Konto anleggen oder anmellen',
'loginprompt'                => 'Dat du di bi {{SITENAME}} anmellen kannst, musst du Cookies anstellt hebben.',
'userlogin'                  => 'Nee Konto anleggen oder anmellen',
'logout'                     => 'Afmellen',
'userlogout'                 => 'Afmellen',
'notloggedin'                => 'Nich anmellt',
'nologin'                    => "Wenn du noch keen Brukerkonto hest, denn kannst di anmellen: '''$1'''.",
'nologinlink'                => 'Brukerkonto inrichten',
'createaccount'              => 'Nieg Brukerkonto anleggen',
'gotaccount'                 => "Hest Du al en Brukerkonto? '''$1'''.",
'gotaccountlink'             => 'Anmellen',
'createaccountmail'          => 'över E-Mail',
'badretype'                  => 'De beiden Passwöör stimmt nich övereen.',
'userexists'                 => 'Disse Brukernaam is al weg. Bitte söök di en annern ut.',
'loginerror'                 => 'Fehler bi dat Anmellen',
'nocookiesnew'               => 'De Brukertogang is anleggt, aver du büst nich inloggt. {{SITENAME}} bruukt för disse Funktschoon Cookies, aktiveer de Cookies un logg di denn mit dien nieg Brukernaam un den Password in.',
'nocookieslogin'             => '{{SITENAME}} bruukt Cookies för dat Inloggen vun de Bruker. Du hest Cookies deaktiveert, aktiveer de Cookies un versöök dat noch eenmal.',
'noname'                     => 'Du muttst en Brukernaam angeven.',
'loginsuccesstitle'          => 'Anmellen hett Spood',
'loginsuccess'               => 'Du büst nu as „$1“ bi {{SITENAME}} anmellt.',
'nosuchuser'                 => 'Den Brukernaam „$1“ gifft dat nich.
Brukernaams maakt en Ünnerscheed twischen groot un lütt schrevene Bookstaven.
Kiek de Schrievwies na oder [[Special:UserLogin/signup|mell di as ne’en Bruker an]].',
'nosuchusershort'            => 'De Brukernaam „<nowiki>$1</nowiki>“ existeert nich. Prööv de Schrievwies.',
'nouserspecified'            => 'Du musst en Brukernaam angeven',
'wrongpassword'              => 'Dat Passwoort, wat du ingeven hest, is verkehrt. Kannst dat aver noch wedder versöken.',
'wrongpasswordempty'         => 'Dat Passwoort, wat du ingeven hest, is leddig, versöök dat noch wedder.',
'passwordtooshort'           => 'Dat Passwoord is to kort. Dat schall woll beter {{PLURAL:$1|een Teken|$1 Teken}} lang oder noch länger wesen.',
'mailmypassword'             => 'En nee Passwoord tostüren',
'passwordremindertitle'      => 'Nee Passwoort för {{SITENAME}}',
'passwordremindertext'       => 'Een (IP-Adress $1) hett för en nee Passwoord to’n Anmellen bi {{SITENAME}} beden ($4).
En temporär Passwoord för Bruker „$2“ is opstellt worrn un heet „$3“. Wenn du dat wullt hest, denn musst du di nu anmellen un en nee Passwoord wählen. Dien temporär Passwoord löppt in {{PLURAL:$5|een Dag|$5 Daag}} ut.

Wenn du nich sülvst för en nee Passwoord beden hest, denn bruukst di wegen disse Naricht nich to kümmern un kannst dien oold Passwoord wiederbruken.',
'noemail'                    => 'Bruker „$1“ hett kene E-Mail-Adress angeven.',
'passwordsent'               => 'En nee Passwoord is de E-Mail-Adress vun Bruker „$1“ tostüürt worrn. Mell di an, wenn du dat Passwoord kregen hest.',
'blocked-mailpassword'       => 'Dien IP-Adress is sperrt. Missbruuk to verhinnern, is dat Toschicken vun en nee Passwoord ok sperrt.',
'eauthentsent'               => 'En Bestätigungs-E-Mail is de angeven Adress tostüürt worrn.
Ehrdat E-Mails vun annere Brukers över de E-Mail-Funkschoon kamen köönt, mutt de Adress eerst noch bestätigt warrn.
In de E-Mail steiht, wat du doon musst.',
'throttled-mailpassword'     => 'Binnen de {{PLURAL:$1|letzte Stünn|letzten $1 Stünnen}} is al mal en neet Passwoort toschickt worrn. Dat disse Funkschoon nich missbruukt warrt, kann blot {{PLURAL:$1|jede Stünn|alle $1 Stünnen}} een Maal en neet Passwoort toschickt warrn.',
'mailerror'                  => 'Fehler bi dat Sennen vun de E-Mail: $1',
'acct_creation_throttle_hit' => 'Ünner disse IP-Adress hebbt Lüüd in de lesten 24 Stünnen al {{PLURAL:$1|een Brukerkonto|$1 Brukerkontos}} anleggt. Mehr is nich verlöövt in disse Tied. Dorüm köönt Brukers ünner disse IP-Adress eerstmal keen Brukerkonten mehr opstellen.',
'emailauthenticated'         => 'Diene E-Mail-Adress is bestätigt worrn: $2, $3.',
'emailnotauthenticated'      => 'Dien E-Mail-Adress is noch nich bestätigt. Disse E-Mail-Funkschonen kannst du eerst bruken, wenn de Adress bestätigt is.',
'noemailprefs'               => 'Geev en E-Mail-Adress an, dat du disse Funkschonen bruken kannst.',
'emailconfirmlink'           => 'Nettbreef-Adress bestätigen',
'invalidemailaddress'        => 'Mit de E-Mail-Adress weer nix antofangen, dor stimmt wat nich mit dat Format. Geev en k’rekkte Adress in oder laat dat Feld leddig.',
'accountcreated'             => 'Brukerkonto inricht',
'accountcreatedtext'         => 'Dat Brukerkonto $1 is nee opstellt worrn.',
'createaccount-title'        => 'Konto anleggen för {{SITENAME}}',
'createaccount-text'         => 'Een hett för di op {{SITENAME}} ($4) en Brukerkonto "$2" nee opstellt. Dat automaatsch instellte Passwoort för "$2" is "$3". Du schullst di nu man anmellen un dat Passwoort ännern.

Wenn du dat Brukerkonto gor nich hebben wullst, denn is disse Naricht egaal för di. Kannst ehr eenfach ignoreren.',
'login-throttled'            => 'Du hest to faken versöcht, di ünner dissen Brukernaam antomellen. Tööv en Stoot, ehrdat du dat noch wedder versöchst.',
'loginlanguagelabel'         => 'Spraak: $1',

# Password reset dialog
'resetpass'                 => 'Passwoord ännern',
'resetpass_announce'        => 'Du hest di mit en Kood anmellt, de di över E-Mail toschickt worrn is. Dat anmellen aftosluten, söök di nu en neet Passwoord ut:',
'resetpass_header'          => 'Passwoord trüchsetten',
'oldpassword'               => 'Oolt Passwoort:',
'newpassword'               => 'Nee Passwoort',
'retypenew'                 => 'Nee Passwoort (nochmal)',
'resetpass_submit'          => 'Passwoort instellen un inloggen',
'resetpass_success'         => 'Dien Passwoort is mit Spood ännert worrn. Warrst nu anmellt …',
'resetpass_forbidden'       => 'Passwöör köönt nich ännert warrn.',
'resetpass-no-info'         => 'Du musst anmellt wesen, dat du disse Sied direkt opropen kannst.',
'resetpass-submit-loggedin' => 'Passwoord ännern',
'resetpass-wrong-oldpass'   => 'Dat Passwoord (temporär oder aktuell) gellt nich.
Villicht hest du dien Passwoord al ännert oder noch wedder en nee temporär Passwoord anfeddert.',
'resetpass-temp-password'   => 'Temporär Passwoord:',

# Edit page toolbar
'bold_sample'     => 'Fetten Text',
'bold_tip'        => 'Fetten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursiven Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Internen Link',
'extlink_sample'  => 'http://www.example.com Link-Text',
'extlink_tip'     => 'Externen Link (http:// is wichtig)',
'headline_sample' => 'Evene 2 Överschrift',
'headline_tip'    => 'Evene 2 Överschrift',
'math_sample'     => 'Formel hier infögen',
'math_tip'        => 'Mathematsche Formel (LaTeX)',
'nowiki_sample'   => 'Unformateerten Text hier infögen',
'nowiki_tip'      => 'Unformateerten Text',
'image_sample'    => 'Bispeel.jpg',
'image_tip'       => 'Bild-Verwies',
'media_sample'    => 'Bispeel.ogg',
'media_tip'       => 'Mediendatei-Verwies',
'sig_tip'         => 'Diene Signatur mit Tietstempel',
'hr_tip'          => 'Waagrechte Lien (sporsam bruken)',

# Edit pages
'summary'                          => 'Grund för’t Ännern:',
'subject'                          => 'Bedrap:',
'minoredit'                        => 'Blots lütte Ännern',
'watchthis'                        => 'Op disse Siet oppassen',
'savearticle'                      => 'Siet spiekern',
'preview'                          => 'Vörschau',
'showpreview'                      => 'Vörschau wiesen',
'showlivepreview'                  => 'Live-Vörschau',
'showdiff'                         => 'Ünnerscheed wiesen',
'anoneditwarning'                  => "'''Wohrschau:''' Du büst nich anmellt. Diene IP-Adress warrt in de Versionshistorie vun de Siet fasthollen.",
'missingsummary'                   => "'''Wohrschau:''' Du hest keen Tosamenfaten angeven, wat du ännert hest. Wenn du nu Spiekern klickst, warrt de Siet ahn Tosamenfaten spiekert.",
'missingcommenttext'               => 'Geev ünnen en Tosamenfaten in.',
'missingcommentheader'             => "'''WOHRSCHAU:''' Du hest keen Överschrift in dat Feld „{{MediaWiki:Subject/nds}}“ ingeven. Wenn du noch wedder op „{{MediaWiki:Savearticle/nds}}“ klickst, denn warrt dien Ännern ahn Överschrift spiekert.",
'summary-preview'                  => 'Vörschau vun’t Tosamenfaten:',
'subject-preview'                  => "Vörschau vun de Reeg ''Tosamenfaten'':",
'blockedtitle'                     => 'Bruker is blockt',
'blockedtext'                      => 'Dien Brukernaam oder diene IP-Adress is vun $1 blockt worrn.
As Grund is angeven: \'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:IPBlockList|&action=search&limit=&ip=%23}}$5 Logbookindrag]</span>, de Block-ID is $5).

Du dröffst aver jümmer noch lesen. Blot dat Schrieven geiht nich. Wenn du gor nix schrieven wullst, denn hest du villicht op en roden Lenk klickt, to en Artikel den dat noch nich gifft. blot blaue Lenken gaht na vörhannene Artikels.

Wenn du glöövst, dat Sparren weer unrecht, denn mell di bi een vun de [[{{MediaWiki:Grouppage-sysop}}|Administraters]]. Geev bi Fragen jümmer ok all disse Infos mit an:

* Anfang vun’n Block: $8
* Enn vun’n Block: $6
* Block vun: $7
* IP-Adress: $3
* Block-ID: #$5
* Grund för’n Block: #$2
* Wokeen hett blockt: $1',
'autoblockedtext'                  => "Diene IP-Adress ($3) is blockt, denn en annern Bruker hett ehr vördem bruukt un is dör $1 blockt worrn.
As Grund is angeven: ''$2'' (de Block-ID is $5).

Du dröffst aver jümmer noch lesen. Blot dat Schrieven geiht nich.

Wenn du över de Sperr snacken wist, denn mell di bi $1 oder een vun de [[{{MediaWiki:Grouppage-sysop}}|Administraters]]. Geev bi Fragen jümmer ok all disse Infos mit an:

* Anfang vun’n Block: $8
* Enn vun’n Block: $6
* Wokeen is blockt worrn: $7
* Block-ID: #$5
* Grund för’n Block: $2
* Wokeen hett blockt: $1",
'blockednoreason'                  => 'keen Grund angeven',
'blockedoriginalsource'            => "De Borntext vun '''$1''' warrt hier wiest:",
'blockededitsource'                => "De Text vun '''diene Ännern''' an '''$1''':",
'whitelistedittitle'               => 'de Sied to ännern is dat nödig, anmellt to wesen',
'whitelistedittext'                => 'Du musst di $1, dat du Sieden ännern kannst.',
'confirmedittext'                  => 'Du musst dien E-Mail-Adress bestätigen, dat du wat ännern kannst. Stell dien E-Mail-Adress in de [[Special:Preferences|{{int:preferences}}]] in un bestätig ehr.',
'nosuchsectiontitle'               => 'Dissen Afsnitt gifft dat nich',
'nosuchsectiontext'                => 'Du hest versöcht den Afsnitt „$1“ to ännern, den dat nich gifft. Du kannst blot Afsneed ännern, de al dor sünd.',
'loginreqtitle'                    => 'Anmellen nödig',
'loginreqlink'                     => 'anmellen',
'loginreqpagetext'                 => 'Du musst di $1, dat du annere Sieden ankieken kannst.',
'accmailtitle'                     => 'Passwoort is toschickt worrn.',
'accmailtext'                      => "En tofällig Passwoord för [[User talk:$1|$1]] is $2 tostüürt worrn.

Dat Passwoord för dit Brukerkonto kann na dat Anmellen ünner ''[[Special:ChangePassword|Passwoord ännern]]'' ännert warrn.",
'newarticle'                       => '(Nee)',
'newarticletext'                   => 'Hier den Text vun de ne’e Siet indregen. Jümmer in ganze Sätz schrieven un kene Texten vun Annern, de en Oorheverrecht ünnerliggt, hierher kopeern.',
'anontalkpagetext'                 => "---- ''Dit is de Diskuschoonssiet vun en nich anmellt Bruker, de noch keen Brukerkonto anleggt hett oder dat jüst nich bruukt.
Wi mööt hier de numerische IP-Adress verwennen, üm den Bruker to identifizeern.
So en Adress kann vun verscheden Brukern bruukt warrn.
Wenn du en anonymen Bruker büst un meenst, dat disse Kommentaren nich an di richt sünd, denn [[Special:UserLogin/signup|legg di en Brukerkonto an]] oder [[Special:UserLogin|mell di an]], dat dat Problem nich mehr dor is.''",
'noarticletext'                    => 'Dor is opstunns keen Text op disse Sied. Du kannst [[Special:Search/{{PAGENAME}}|na dissen Utdruck in annere Sieden söken]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} in de Logböker söken],
oder [{{fullurl:{{FULLPAGENAME}}|action=edit}} disse Sied ännern]</span>.',
'userpage-userdoesnotexist'        => 'Dat Brukerkonto „$1“ gifft dat noch nich. Överlegg, wat du disse Siet würklich nee opstellen/ännern wullt.',
'clearyourcache'                   => "'''Denk doran:''' No den Spiekern muttst du dien Browser noch seggen, de niege Version to laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssyoucanpreview'             => "'''Tipp:''' Bruuk den Vörschau-Knoop, üm dien nieg CSS vör dat Spiekern to testen.",
'userjsyoucanpreview'              => "'''Tipp:''' Bruuk den Vörschau-Knoop, üm dien nieg JS vör dat Spiekern to testen.",
'usercsspreview'                   => "'''Denk doran, dat du blots en Vörschau vun dien CSS ankickst, dat is noch nich spiekert!'''",
'userjspreview'                    => "'''Denk doran, dat du blots en Vörschau vun dien JS ankiekst, dat is noch nich spiekert!'''",
'userinvalidcssjstitle'            => "'''Wohrschau:''' Dat gifft keen Skin „$1“. Denk dor an, dat .css- un .js-Sieden  för Brukers mit en lütten Bookstaven anfangen mööt, to’n Bispeel ''{{ns:user}}:Brukernaam/monobook.css'' un nich ''{{ns:user}}:Brukernaam/Monobook.css''.",
'updated'                          => '(Ännert)',
'note'                             => "'''Wohrschau:'''",
'previewnote'                      => "'''Dit is blots en Vörschau, de Siet is noch nich spiekert!'''",
'previewconflict'                  => 'Disse Vörschau wiest den Inholt vun dat Textfeld baven; so warrt de Siet utseihn, wenn du nu spiekerst.',
'session_fail_preview'             => "'''Deit uns leed! Wi kunnen dien Ännern nich spiekern. Diene Sitzungsdaten weren weg.
Versöök dat noch wedder. Wenn dat noch jümmer nich geiht, denn versöök di [[Special:UserLogout|aftomellen]] un denn wedder antomellen.'''",
'session_fail_preview_html'        => "'''Deit uns leed! Wi kunnen dien Ännern nich spiekern, de Sitzungsdaten sünd verloren gahn.'''

''In {{SITENAME}} is dat Spiekern vun rein HTML verlöövt, dorvun is de Vörschau utblennt, dat JavaScript-Angrepen nich mööglich sünd.''

'''Versöök dat noch wedder un klick noch wedder op „Siet spiekern“. Wenn dat Problem noch jümmer dor is, [[Special:UserLogout|mell di af]] un denn wedder an.'''",
'token_suffix_mismatch'            => "'''Dien Ännern sünd afwiest worrn. Dien Browser hett welk Teken in de Kuntrull-Tekenreeg kaputt maakt.
Wenn dat so spiekert warrt, kann dat angahn, dat noch mehr Teken in de Sied kaputt gaht.
Dat kann to’n Bispeel dor vun kamen, dat du en anonymen Proxy-Deenst bruukst, de wat verkehrt maakt.'''",
'editing'                          => 'Ännern vun $1',
'editingsection'                   => 'Ännern vun $1 (Afsatz)',
'editingcomment'                   => 'Ännern vun $1 (nee Afsnidd)',
'editconflict'                     => 'Konflikt bi’t Sied ännern: $1',
'explainconflict'                  => 'En annern Bruker hett disse Sied ännert, na de Tied dat du anfungen hest, de Sied to ännern.
Dat Textfeld baven wiest de aktuelle Sied.
Dat Textfeld ünnen wiest dien Ännern.
Föög dien Ännern in dat Textfeld baven in.

<b>Blots</b> de Text in dat Textfeld baven warrt spiekert, wenn du op Spiekern klickst!<br />',
'yourtext'                         => 'Dien Text',
'storedversion'                    => 'Spiekerte Version',
'nonunicodebrowser'                => "'''Wohrschau: Dien Browser kann keen Unicode, bruuk en annern Browser, wenn du en Siet ännern wist.'''",
'editingold'                       => "'''Wohrscho: Du bearbeidst en ole Version vun disse Siet.
Wenn du spiekerst, warrn alle niegeren Versionen överschrieven.'''",
'yourdiff'                         => 'Ünnerscheed',
'copyrightwarning'                 => "Bitte pass op, dat all diene Bidrääg to {{SITENAME}} so ansehn warrt, dat se ünner de $2 staht (kiek op $1 för de Details). Wenn du nich willst, dat diene Bidrääg ännert un verdeelt warrt, denn schallst du hier man nix bidragen. Du seggst ok to, dat du dat hier sülvst schreven hest, oder dat du dat ut en fre’e Born (to’n Bispeel gemeenfree oder so wat in disse Oort) kopeert hest.
'''Stell hier nix rin, wat ünner Oorheverrecht steiht, wenn de, de dat Oorheverrecht hett, di dorto keen Verlööf geven hett!'''",
'copyrightwarning2'                => "Dien Text, de du op {{SITENAME}} stellen wullst, könnt vun elkeen ännert oder wegmaakt warrn.
Wenn du dat nich wullst, dröffst du dien Text hier nich apentlich maken.<br />

Du bestätigst ok, dat du den Text sülvst schreven hest oder ut en „Public Domain“-Born oder en annere fre'e Born kopeert hest (Kiek ok $1 för Details).
'''Kopeer kene Warken, de enen Oorheverrecht ünnerliggt, ahn Verlööv vun de Copyright-Inhebbers!'''",
'longpagewarning'                  => "'''Wohrschau: Disse Sied is $1 kB groot; en poor Browsers köönt Problemen hebben, Sieden to ännern, de grötter as 32 kB sünd.
Överlegg, wat disse Sied nich villicht in lüttere Afsneed opdeelt warrn kann.'''",
'longpageerror'                    => "'''Fehler: Dien Text is $1 Kilobytes lang. Dat is länger as dat Maximum vun $2 Kilobytes. Kann den Text nich spiekern.'''",
'readonlywarning'                  => "'''Wohrscho: De Datenbank is för Pleegarbeiden sparrt worrn, so dat du de Sied en Stoot nich
spiekern kannst. Seker di den Text un versöök dat later noch wedder.'''

As Grund is angeven: $1",
'protectedpagewarning'             => "'''Wohrscho: Disse Siet is sparrt worrn, so dat blots
Bruker mit Sysop-Rechten doran arbeiden könnt.'''",
'semiprotectedpagewarning'         => "'''Henwies:''' Disse Siet is sparrt. Blots anmellt Brukers köönt de Siet ännern.",
'cascadeprotectedwarning'          => "'''Wohrschau:''' Disse Siet is so sparrt, dat blot Brukers mit Admin-Status ehr ännern köönt. Dat liggt dor an, dat se in disse {{PLURAL:$1|kaskadensparrte Siet|kaskadensparrten Sieden}} inbunnen is:",
'titleprotectedwarning'            => "'''WOHRSCHAU: Disse Sied is schuult, dat blot welk [[Special:ListGroupRights|Brukergruppen]] ehr anleggen köönt.'''",
'templatesused'                    => 'Vörlagen de in disse Siet bruukt warrt:',
'templatesusedpreview'             => 'Vörlagen de in disse Vörschau bruukt warrt:',
'templatesusedsection'             => 'Vörlagen de in dissen Afsnitt bruukt warrt:',
'template-protected'               => '(schuult)',
'template-semiprotected'           => '(half-schuult)',
'hiddencategories'                 => 'Disse Siet steiht in {{PLURAL:$1|ene verstekene Kategorie|$1 verstekene Kategorien}}:',
'edittools'                        => '<!-- Disse Text warrt ünner de Finstern för dat Ännern un Hoochladen wiest. -->',
'nocreatetitle'                    => 'Opstellen vun ne’e Sieden is inschränkt.',
'nocreatetext'                     => '{{SITENAME}} verlööft di dat Opstellen vun ne’e Sieden nich. Du kannst blot Sieden ännern, de al dor sünd, oder du musst di [[Special:UserLogin|anmellen]].',
'nocreate-loggedin'                => 'Du hest keen Verlööf, ne’e Sieden antoleggen.',
'permissionserrors'                => 'Fehlers mit de Rechten',
'permissionserrorstext'            => 'Du hest keen Verlööf, dat to doon. De {{PLURAL:$1|Grund is|Grünn sünd}}:',
'permissionserrorstext-withaction' => 'Du hest nich de Rechten $2. Dat hett {{PLURAL:$1|dissen Grund|disse Grünn}}:',
'recreate-moveddeleted-warn'       => "'''Wohrschau: Du stellst jüst en Sied wedder nee op, de vördem al mal wegsmeten worrn is.'''

Överlegg genau, wat du würklich de Sied nee opstellen wist.
Dat du bescheed weetst, worüm de Sied vörher wegsmeten worrn is, hier nu en Deel ut dat Lösch-Logbook:",
'moveddeleted-notice'              => 'De Sied is wegdaan worrn. Wat nu kummt, is en Deel ut dat Lösch-Logbook för disse Sied.',
'log-fulllog'                      => 'Vull Logbook ankieken',
'edit-hook-aborted'                => 'Ännern is ahn angeven Grund vun en Hook afbraken worrn.',
'edit-gone-missing'                => 'De Sied kunn nich aktuell maakt warrn.
Schient so, as wenn se wegdaan worrn is.',
'edit-conflict'                    => 'Konflikt bi’t Sied ännern.',
'edit-no-change'                   => 'Dien Ännern is nich afspiekert worrn, denn dor hett sik nix an’n Text ännert.',
'edit-already-exists'              => 'Kunn keen ne’e Sied opstellen, dat gifft ehr al.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Wohrschau: Disse Sied bruukt to veel opwännige Parserfunkschonen.

Nu {{PLURAL:$1|is dor een|sünd dor $1}}, wesen {{PLURAL:$2|dröff dat blot een|dröfft dat blot $2}}.',
'expensive-parserfunction-category'       => 'Sieden, de toveel opwännige Parserfunkschonen bruukt',
'post-expand-template-inclusion-warning'  => 'Wohrschau: De Grött vun inföögte Vörlagen is to groot, welk Vörlagen köönt nich inföögt warrn.',
'post-expand-template-inclusion-category' => 'Sieden, de över de Maximumgrött för inbunnene Sieden rövergaht',
'post-expand-template-argument-warning'   => 'Wohrschau: Disse Sied bruukt opminnst een Parameter in ene Vörlaag, de to groot is, wenn’t wiest warrt. Disse Parameters warrt weglaten.',
'post-expand-template-argument-category'  => 'Sieden mit utlaten Vörlaagargmenten',
'parser-template-loop-warning'            => 'Vörlagenslööf funnen: [[$1]]',
'parser-template-recursion-depth-warning' => 'över de Rekursionsdeepdengrenz för Vörlagen rövergahn ($1)',

# "Undo" feature
'undo-success' => 'De Ännern kann trüchdreiht warrn. Vergliek ünnen de Versionen, dat ok allens richtig is, un spieker de Sied denn af.',
'undo-failure' => 'Kunn de Siet nich op de vörige Version trüchdreihn. De Afsnitt is twischendör al wedder ännert worrn.',
'undo-norev'   => 'De Ännern kunn nich trüchdreiht warrn, de gifft dat nich oder is wegsmeten worrn.',
'undo-summary' => 'Ännern $1 vun [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskuschoon]]) trüchdreiht.',

# Account creation failure
'cantcreateaccounttitle' => 'Brukerkonto kann nich anleggt warrn',
'cantcreateaccount-text' => "Dat Opstellen vun Brukerkonten vun de IP-Adress '''$1''' ut is vun [[User:$3|$3]] sperrt worrn.

De Grund weer: ''$2''",

# History pages
'viewpagelogs'           => 'Logbook för disse Siet',
'nohistory'              => 'Disse Siet hett keen Vörgeschicht.',
'currentrev'             => 'Aktuelle Version',
'currentrev-asof'        => 'Aktuelle Version vun’n $1',
'revisionasof'           => 'Version vun $1',
'revision-info'          => '<div id="viewingold-warning" style="background: #ffbdbd; border: 1px solid #BB7979; font-weight: bold; padding: .5em 1em;">
Dit is en ole Version vun disse Siet, so as $2 de $1 ännert hett. De Version kann temlich stark vun de <a href="{{FULLURL:{{FULLPAGENAME}}}}" title="{{FULLPAGENAME}}">aktuelle Version</a> afwieken.
</div>',
'previousrevision'       => 'Nächstöllere Version→',
'nextrevision'           => 'Ne’ere Version →',
'currentrevisionlink'    => 'aktuelle Version',
'cur'                    => 'Aktuell',
'next'                   => 'tokamen',
'last'                   => 'vörige',
'page_first'             => 'Anfang',
'page_last'              => 'Enn',
'histlegend'             => "Ünnerscheed-Utwahl: De Boxen vun de wünschten
Versionen markeern un 'Enter' drücken oder den Knoop nerrn klicken/alt-v.<br />
Legende:
(Aktuell) = Ünnerscheed to de aktuelle Version,
(Letzte) = Ünnerscheed to de vörige Version,
L = Lütte Ännern",
'history-fieldset-title' => 'Versionsgeschicht dörkieken',
'histfirst'              => 'Öllste',
'histlast'               => 'Ne’este',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'           => '(leddig)',

# Revision feed
'history-feed-title'          => 'Versionsgeschicht',
'history-feed-description'    => 'Versionsgeschicht för disse Siet',
'history-feed-item-nocomment' => '$1 üm $2',
'history-feed-empty'          => 'De angevene Siet gifft dat nich.
Villicht is se löscht worrn oder hett en annern Naam kregen.
Versöök [[Special:Search|dat Söken]] na annere relevante Sieden.',

# Revision deletion
'rev-deleted-comment'         => '(Kommentar rutnahmen)',
'rev-deleted-user'            => '(Brukernaam rutnahmen)',
'rev-deleted-event'           => '(Logbook-Indrag rutnahmen)',
'rev-deleted-text-permission' => 'Disse Version is nu wegdaan un is nich mehr apen in’t Archiv to sehn.
Details dorto staht in dat [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Lösch-Logbook].',
'rev-deleted-text-unhide'     => 'Disse Version is nu wegdaan un is nich mehr apen in’t Archiv to sehn.
Details dorto staht in dat [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Lösch-Logbook].
As Administrator kannst du de Version [$1 över dissen Lenk] ankieken.',
'rev-deleted-text-view'       => 'Disse Version is wegsmeten worrn un is nich mehr apen in’t Archiv to sehn.
As Administrater op {{SITENAME}} kannst du ehr aver noch jümmer sehn.
Mehr över dat Wegsmieten is in dat [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Lösch-Logbook] to finnen.',
'rev-deleted-no-diff'         => 'Du kannst dissen Ünnerscheed nich ankieken. Een von de Versionen is nich mehr apen in’t Archiv to sehn.
Mehr dorto steiht in dat [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Wegsmiet-Logbook].',
'rev-deleted-unhide-diff'     => 'Een vun de Versionen vun dissen Ünnerscheed is nich mehr apen in’t Archiv to sehn.
Details staht in’t [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Wegsmiet-Logbook].
As Administrater kannst du [$1 mit dissen Lenk] den Ünnerscheed ankieken.',
'rev-delundel'                => 'wiesen/versteken',
'revisiondelete'              => 'Versionen wegsmieten/wedderhalen',
'revdelete-nooldid-title'     => 'kene Versionen dor, de passt',
'revdelete-nooldid-text'      => 'Du hest keen Version för disse Akschoon angeven, de utwählte Version gifft dat nich oder du versöchst, de ne’este Version wegtodoon.',
'revdelete-nologtype-title'   => 'Keen Logbooktyp angeven',
'revdelete-nologtype-text'    => 'Du hest keen Logtyp för disse Akschoon angeven.',
'revdelete-nologid-title'     => 'Ungüllig Logindrag',
'revdelete-nologid-text'      => 'Is keen Logtyp utwählt oder den utwählten Logtyp gifft dat nich.',
'revdelete-no-file'           => 'De angeven Datei gifft dat nich.',
'revdelete-show-file-submit'  => 'Jo',
'revdelete-selected'          => "'''{{PLURAL:$2|Wählte Version|Wählte Versionen}} vun [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Wählt Logbook-Indrag|Wählte Logbook-Indrääg}}:'''",
'revdelete-text'              => "'''Wegsmetene Versionen un Akschonen staht noch jümmer in de Versionsgeschicht un Logböker, sünd aver nich mehr apen intosehn.'''
{{SITENAME}}-Administraters köönt de Sieden noch jümmer sehn un ok wedderhalen, solang dat nich extra fastleggt is, dat ok Administraters dat nich mehr mehr dröfft.",
'revdelete-suppress-text'     => "Ünnerdrücken schull '''blot''' bi disse Fäll bruukt warrn:
* Nich passliche persönliche Information
*: ''Adressen, Telefonnummern, Sozialversekerungsnummern etc.''",
'revdelete-legend'            => 'Inschränkungen för de Sichtborkeit setten',
'revdelete-hide-text'         => 'Versiontext versteken',
'revdelete-hide-image'        => 'Dateiinholt versteken',
'revdelete-hide-name'         => 'Akschoon un Teel versteken',
'revdelete-hide-comment'      => 'Kommentar versteken',
'revdelete-hide-user'         => 'Brukernaam/IP vun’n Schriever versteken',
'revdelete-hide-restricted'   => 'Ok för Administraters versteken',
'revdelete-suppress'          => 'Grund för dat Wegsmieten ok för Administraters versteken',
'revdelete-unsuppress'        => 'Inschränkungen för wedderhaalte Versionen wegdoon',
'revdelete-log'               => 'Grund:',
'revdelete-submit'            => 'Op utwählte Version anwennen',
'revdelete-logentry'          => 'Sichtborkeit vun Version för [[$1]] ännert',
'logdelete-logentry'          => 'Sichtborkeit vun Begeevnis för [[$1]] ännert',
'revdelete-success'           => "'''Sichtborkeit vun Version mit Spood ännert.'''",
'revdelete-failure'           => "'''Sichtborkeit vun de Version kunn nich ännert warrn.'''
$1",
'logdelete-success'           => "'''Sichtborkeit in Logbook mit Spood ännert.'''",
'revdel-restore'              => 'Sichtborkeit ännern',
'pagehist'                    => 'Versionshistorie',
'deletedhist'                 => 'wegsmetene Versionen',
'revdelete-content'           => 'Inholt',
'revdelete-summary'           => 'Tosamenfaten',
'revdelete-uname'             => 'Brukernaam',
'revdelete-restricted'        => 'Inschränkungen för Administraters instellt',
'revdelete-unrestricted'      => 'Inschränkungen för Administraters rutnahmen',
'revdelete-hid'               => 'hett $1 versteken',
'revdelete-unhid'             => 'hett $1 wedder sichtbor maakt',
'revdelete-log-message'       => '$1 för $2 {{PLURAL:$2|Version|Versionen}}',
'logdelete-log-message'       => '$1 för $2 {{PLURAL:$2|Logbook-Indrag|Logbook-Indrääg}}',
'revdelete-edit-reasonlist'   => 'Grünn för’t Wegsmieten ännern',

# Suppression log
'suppressionlog'     => 'Oversight-Logbook',
'suppressionlogtext' => 'Dit is dat Logbook vun de Oversight-Akschonen mit Sieden un Sperren, de ok för Administraters nich mehr to sehn sünd.
Kiek in de [[Special:IPBlockList|IP-Sperrnlist]] för en Översicht över de opstunns aktiven Sperrn.',

# History merging
'mergehistory'                     => 'Versionshistorien tohoopföhren',
'mergehistory-header'              => 'Disse Spezialsied verlöövt dat Tohoopfögen vun Versionen vun een Sied mit en annere.
Seh to, dat de Versionsgeschicht vun’n Artikel vun de Historie her bi de Reeg blifft.',
'mergehistory-box'                 => 'Versionshistorien vun twee Sieden tohoopföhren',
'mergehistory-from'                => 'Bornsiet:',
'mergehistory-into'                => 'Teelsiet:',
'mergehistory-list'                => 'Versionen, de tohoopföhrt warrn köönt',
'mergehistory-merge'               => 'Disse Versionen vun „[[:$1]]“ köönt na „[[:$2]]“ överdragen warrn. Krüüz de Version an, de tohoop mit all de dorför överdragen warrn schall. Bedenk, dat dat Bruken vun de Navigatschoon de Utwahl trüchsett.',
'mergehistory-go'                  => 'Wies Versionen, de tohoopföhrt warrn köönt',
'mergehistory-submit'              => 'Versionen tohoopbringen',
'mergehistory-empty'               => 'Köönt kene Versionen tohoopföhrt warrn.',
'mergehistory-success'             => '{{PLURAL:$3|Ene Version|$3 Versionen}} vun „[[:$1]]“ mit Spood tohoopföhrt mit „[[:$2]]“.',
'mergehistory-fail'                => 'Tohoopföhren geiht nich, kiek na, wat de Siet un de Tietangaven ok passen doot.',
'mergehistory-no-source'           => 'Utgangssiet „$1“ gifft dat nich.',
'mergehistory-no-destination'      => 'Teelsiet „$1“ gifft dat nich.',
'mergehistory-invalid-source'      => 'Utgangssiet mutt en gülligen Siedennaam wesen.',
'mergehistory-invalid-destination' => 'Zielsiet mutt en gülligen Siedennaam wesen.',
'mergehistory-autocomment'         => '„[[:$1]]“ tohoopföhrt mit „[[:$2]]“',
'mergehistory-comment'             => '„[[:$1]]“ tohoopföhrt mit „[[:$2]]“: $3',
'mergehistory-same-destination'    => 'De Sied, vun de schaven warrt un op de schaven warrt, dröfft nich desülve wesen',
'mergehistory-reason'              => 'Grund:',

# Merge log
'mergelog'           => 'Tohoopföhr-Logbook',
'pagemerge-logentry' => '[[$1]] mit [[$2]] tohoopföhrt (Versionen bet $3)',
'revertmerge'        => 'Tohoopbringen trüchdreihn',
'mergelogpagetext'   => 'Dit is dat Logbook över de tohoopföhrten Versionshistorien.',

# Diffs
'history-title'            => 'Versionshistorie vun „$1“',
'difference'               => '(Ünnerscheed twischen de Versionen)',
'lineno'                   => 'Reeg $1:',
'compareselectedversions'  => 'Ünnerscheed twischen den utwählten Versionen wiesen',
'showhideselectedversions' => 'Utwählt Versionen wiesen/versteken',
'editundo'                 => 'rutnehmen',
'diff-multi'               => '(Twischen de beiden Versionen {{PLURAL:$1|liggt noch ene Twischenversion|doot noch $1 Twischenversionen liggen}}.)',

# Search results
'searchresults'                    => 'Söökresultaten',
'searchresults-title'              => 'Söökresultaten för „$1“',
'searchresulttext'                 => 'För mehr Informatschonen över {{SITENAME}}, kiek [[{{MediaWiki:Helppage}}|{{SITENAME}} dörsöken]].',
'searchsubtitle'                   => 'Du hest na „[[:$1]]“ söcht ([[Special:Prefixindex/$1|all Sieden, de mit „$1“ anfangt]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|all Sieden, de na „$1“ wiest]])',
'searchsubtitleinvalid'            => 'För de Söökanfraag „$1“',
'toomanymatches'                   => 'To veel Sieden funnen för de Söök, versöök en annere Affraag.',
'titlematches'                     => 'Övereenstimmen mit Överschriften',
'notitlematches'                   => 'Kene Övereenstimmen',
'textmatches'                      => 'Övereenstimmen mit Texten',
'notextmatches'                    => 'Kene Övereenstimmen',
'prevn'                            => 'vörige {{PLURAL:$1|$1}}',
'nextn'                            => 'tokamen {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Vörig Resultat|Vörige $1 Resultaten}}',
'nextn-title'                      => 'Tokamen {{PLURAL:$1|Resultat|$1 Resultaten}}',
'shown-title'                      => 'Wies $1 {{PLURAL:$1|Resultat|Resultaten}} per Sied',
'viewprevnext'                     => 'Wies ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Söökoptionen',
'searchmenu-exists'                => "* Sied '''[[$1]]'''",
'searchmenu-new'                   => "'''Stell de Sied „[[:$1]]“ in dit Wiki nee op!'''",
'searchhelp-url'                   => 'Help:Hülp',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Wies Sieden, de mit disse Bookstaven anfangt]]',
'searchprofile-articles'           => 'Inholdsieden',
'searchprofile-project'            => 'Hülp- un Projektsieden',
'searchprofile-images'             => 'Datein',
'searchprofile-everything'         => 'Allens',
'searchprofile-advanced'           => 'Verwiedert',
'searchprofile-articles-tooltip'   => 'Söken in $1',
'searchprofile-project-tooltip'    => 'Söken in $1',
'searchprofile-images-tooltip'     => 'Na Datein söken',
'searchprofile-everything-tooltip' => 'Allen Inholt dörsöken (inklusiv Diskuschoonssieden)',
'searchprofile-advanced-tooltip'   => 'Söök in angevene Naamrüüm',
'search-result-size'               => '$1 ({{PLURAL:$2|een Woort|$2 Wöör}})',
'search-result-score'              => 'Relevanz: $1 %',
'search-redirect'                  => '(Redirect $1)',
'search-section'                   => '(Afsnitt $1)',
'search-suggest'                   => 'Hest du „$1“ meent?',
'search-interwiki-caption'         => 'Süsterprojekten',
'search-interwiki-default'         => '$1 Resultaten:',
'search-interwiki-more'            => '(mehr)',
'search-mwsuggest-enabled'         => 'mit Vörslääg',
'search-mwsuggest-disabled'        => 'kene Vörslääg',
'search-relatedarticle'            => 'Verwandt',
'mwsuggest-disable'                => 'Vörslääg per Ajax utstellen',
'searcheverything-enable'          => 'In all Naamrüüm söken',
'searchrelated'                    => 'verwandt',
'searchall'                        => 'all',
'showingresults'                   => "Hier {{PLURAL:$1|is een Resultat|sünd '''$1''' Resultaten}}, anfungen mit #'''$2'''.",
'showingresultsnum'                => "Hier {{PLURAL:$3|is een Resultat|sünd '''$3''' Resultaten}}, anfungen mit #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultaat '''$1''' vun '''$3'''|Resultaten '''$1 - $2''' vun '''$3'''}} för '''$4'''",
'nonefound'                        => "'''Henwies''': Na de Standardinstellung warrt blot en poor Naamrüüm dörsöcht.
Du kannst dat Woord ''all:'' vör dien Söökwoord setten, dat all Naamrüüm (ok Diskuschoonssieden, Vörlagen usw.) dörsöcht warrt. Dat sülve geit mit de Naams vun de enkelten Naamrüüm.",
'search-nonefound'                 => 'För de Söökanfraag geev dat keen Resultaten.',
'powersearch'                      => 'Betere Söök',
'powersearch-legend'               => 'Betere Söök',
'powersearch-ns'                   => 'Söök in Naamrüüm:',
'powersearch-redir'                => 'Redirects wiesen',
'powersearch-field'                => 'Söök na:',
'powersearch-toggleall'            => 'All',
'powersearch-togglenone'           => 'Keen',
'search-external'                  => 'Externe Söök',
'searchdisabled'                   => '<p>De Vulltextsöök is wegen Överlast en Stoot deaktiveert. In disse Tied kannst du disse Google-Söök verwennen,
de aver nich jümmer den aktuellsten Stand weerspegelt.<p>',

# Quickbar
'qbsettings'               => 'Siedenliest',
'qbsettings-none'          => 'Keen',
'qbsettings-fixedleft'     => 'Links, fast',
'qbsettings-fixedright'    => 'Rechts, fast',
'qbsettings-floatingleft'  => 'Links, sweven',
'qbsettings-floatingright' => 'Rechts, sweven',

# Preferences page
'preferences'                 => 'Instellen',
'mypreferences'               => 'För mi Instellen',
'prefs-edits'                 => 'Wo faken du in dit Wiki Sieden ännert hest:',
'prefsnologin'                => 'Nich anmellt',
'prefsnologintext'            => 'Du musst <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} anmellt]</span> wesen, dat du dien Instellen ännern kannst.',
'changepassword'              => 'Passwoort ännern',
'prefs-skin'                  => 'Utsehn vun de Steed',
'skin-preview'                => 'Vörschau',
'prefs-math'                  => 'TeX',
'datedefault'                 => 'Standard',
'prefs-datetime'              => 'Datum un Tiet',
'prefs-personal'              => 'Brukerdaten',
'prefs-rc'                    => 'Letzte Ännern un Wiesen vun kotte Sieten',
'prefs-watchlist'             => 'Oppasslist',
'prefs-watchlist-days'        => 'Maximumtall Daag, de in de Oppasslist wiest warrt:',
'prefs-watchlist-days-max'    => '(Maximal 7 Daag)',
'prefs-watchlist-edits'       => 'Maximumtall Daag, de in de verwiederte Oppasslist wiest warrt:',
'prefs-watchlist-edits-max'   => '(Maximaltall: 1000)',
'prefs-misc'                  => 'Verscheden Kraam',
'prefs-resetpass'             => 'Passwoord ännern',
'prefs-email'                 => 'E-Mail-Instellungen',
'prefs-rendering'             => 'Utsehn vun de Sied',
'saveprefs'                   => 'Spiekern',
'resetprefs'                  => 'Trüchsetten',
'restoreprefs'                => 'All Standardinstellungen wedderhalen',
'prefs-editing'               => 'Grött vun’t Textfeld',
'prefs-edit-boxsize'          => 'Grött vun dat Finster för Ännern.',
'rows'                        => 'Regen',
'columns'                     => 'Spalten',
'searchresultshead'           => 'Söökresultaten',
'resultsperpage'              => 'Treffer pro Siet',
'contextlines'                => 'Lienen pro Treffer',
'contextchars'                => 'Teken je Reeg',
'stub-threshold'              => 'Grött ünner de Lenken op <a href="#" class="stub">Stubbens un lütte Sieden</a> farvlich kenntekent warrn schöölt (in Bytes):',
'recentchangesdays'           => 'Daag, de de List vun de „Ne’esten Ännern“ wiesen schall:',
'recentchangesdays-max'       => '(Maximal $1 {{PLURAL:$1|Dag|Daag}})',
'recentchangescount'          => 'Antall Ännern, de bi Letzte Ännern, in Versionsgeschichten un in Logböker wiest warrt:',
'savedprefs'                  => 'Allens spiekert.',
'timezonelegend'              => 'Tiedrebeed:',
'localtime'                   => 'Oortstied:',
'timezoneuseserverdefault'    => 'Tied op’n Server bruken',
'timezoneuseoffset'           => 'Anners (Ünnerscheed angeven)',
'timezoneoffset'              => 'Ünnerscheed¹:',
'servertime'                  => 'Tied op den Server:',
'guesstimezone'               => 'Ut den Browser övernehmen',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Amerika',
'timezoneregion-antarctica'   => 'Antarktis',
'timezoneregion-arctic'       => 'Arktis',
'timezoneregion-asia'         => 'Asien',
'timezoneregion-atlantic'     => 'Atlantisch Ozean',
'timezoneregion-australia'    => 'Australien',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Indisch Ozean',
'timezoneregion-pacific'      => 'Pazifisch Ozean',
'allowemail'                  => 'Nettbreven vun annere Brukers annehmen',
'prefs-searchoptions'         => 'Söökopschonen',
'prefs-namespaces'            => 'Naamrüüm',
'defaultns'                   => 'In disse Naamrüüm schall standardmatig söökt warrn:',
'default'                     => 'Standard',
'prefs-files'                 => 'Datein',
'prefs-custom-css'            => 'Anpasst CSS',
'prefs-custom-js'             => 'Anpasst JS',
'prefs-reset-intro'           => 'Du kannst disse Sied bruken, dien Instellungen al op de Standardinstellung trüchtosetten.
Dat kann nich wedder ungeschehn maakt warrn.',
'prefs-emailconfirm-label'    => 'E-Mail-Bestätigung:',
'prefs-textboxsize'           => 'Grött vun dat Änner-Finster',
'youremail'                   => 'Dien E-Mail (kene Plicht) *',
'username'                    => 'Brukernaam:',
'uid'                         => 'Bruker-ID:',
'prefs-memberingroups'        => 'Liddmaten vun de {{PLURAL:$1|Grupp|Gruppen}}:',
'prefs-registration'          => 'Tied vun dat Anmellen:',
'yourrealname'                => 'Dien echten Naam (kene Plicht)',
'yourlanguage'                => 'Snittstellenspraak',
'yourvariant'                 => 'Dien Spraak',
'yournick'                    => 'Dien Ökelnaam (för dat Ünnerschrieven)',
'badsig'                      => 'De Signatur is nich korrekt, kiek nochmal na de HTML-Tags.',
'badsiglength'                => 'Diene Ünnerschrift is to lang; de schall weniger as $1 {{PLURAL:$1|Teken|Tekens}} hebben.',
'yourgender'                  => 'Geslecht:',
'gender-unknown'              => 'Nich angeven',
'gender-male'                 => 'Mann',
'gender-female'               => 'Fro',
'prefs-help-gender'           => 'Mutt nich angeven warrn: Warrt bruukt, dat di de Software korrekt ansnacken kann. Disse Informatschoon is vör annere Brukers sichtbor.',
'email'                       => 'Nettbreef',
'prefs-help-realname'         => 'De echte Naam mutt nich angeven warrn. Wenn du em angiffst, warrt de Naam bruukt, dat diene Arbeit di torekent warrn kann.',
'prefs-help-email'            => 'De E-Mail-Adress mutt nich angeven warrn. Aver so köönt annere Brukers di över E-Mail schrieven, ahn dat du dien Identität priesgiffst, un du kannst di ok en nee Passwoord toschicken laten, wenn du dien oold vergeten hest.',
'prefs-help-email-required'   => 'E-Mail-Adress nödig.',
'prefs-info'                  => 'Basisinformatschoon',
'prefs-i18n'                  => 'Spraakinstellungen',
'prefs-signature'             => 'Ünnerschrift',
'prefs-dateformat'            => 'Datumsformat',
'prefs-advancedediting'       => 'Anner Instellungen',
'prefs-advancedrc'            => 'Anner Instellungen',
'prefs-advancedrendering'     => 'Anner Instellungen',
'prefs-advancedsearchoptions' => 'Anner Instellungen',
'prefs-advancedwatchlist'     => 'Anner Instellungen',
'prefs-diffs'                 => 'Ünnerscheed',

# User rights
'userrights'                  => 'Brukerrechten inrichten',
'userrights-lookup-user'      => 'Brukergruppen verwalten',
'userrights-user-editname'    => 'Brukernaam ingeven:',
'editusergroup'               => 'Brukergruppen ännern',
'editinguser'                 => "Ännern vun Brukerrechten vun '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Brukergruppen ännern',
'saveusergroups'              => 'Brukergruppen spiekern',
'userrights-groupsmember'     => 'Liddmaat vun:',
'userrights-groups-help'      => 'Du kannst de Gruppen för dissen Bruker ännern:
* En ankrüüzt Kasten bedüüdt, dat de Bruker Maat vun de Grupp is.
* En * bedüüdt, dat du dat Brukerrecht na dat Tokennen nich wedder trüchnehmen kannst (un annersrüm).',
'userrights-reason'           => 'Grund:',
'userrights-no-interwiki'     => 'Du hest nich de Rechten, Brukerrechten in annere Wikis to setten.',
'userrights-nodatabase'       => 'Datenbank $1 gifft dat nich oder is nich lokal.',
'userrights-nologin'          => 'Du musst mit en Administrater-Brukerkonto [[Special:UserLogin|anmellt]] wesen, dat du Brukerrechten ännern kannst.',
'userrights-notallowed'       => 'Du hest nich de Rechten, Brukerrechten to setten.',
'userrights-changeable-col'   => 'Gruppen, de du ännern kannst',
'userrights-unchangeable-col' => 'Gruppen, de du nich ännern kannst',

# Groups
'group'               => 'Grupp:',
'group-user'          => 'Brukers',
'group-autoconfirmed' => 'Bestätigte Brukers',
'group-bot'           => 'Bots',
'group-sysop'         => 'Admins',
'group-bureaucrat'    => 'Bürokraten',
'group-suppress'      => 'Oversights',
'group-all'           => '(all)',

'group-user-member'          => 'Bruker',
'group-autoconfirmed-member' => 'Bestätigt Bruker',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Admin',
'group-bureaucrat-member'    => 'Bürokraat',
'group-suppress-member'      => 'Oversight',

'grouppage-user'          => '{{ns:project}}:Brukers',
'grouppage-autoconfirmed' => '{{ns:project}}:Bestätigte Brukers',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administraters',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Sieden lesen',
'right-edit'                  => 'Sieden ännern',
'right-createpage'            => 'Sieden nee opstellen (annere as Diskuschoonssieden)',
'right-createtalk'            => 'Diskuschoonssieden nee opstellen',
'right-createaccount'         => 'Brukerkonten nee opstellen',
'right-minoredit'             => 'Ännern as lütt marken',
'right-move'                  => 'Sieden schuven',
'right-move-subpages'         => 'Sieden tohoop mit Ünnersieden schuven',
'right-move-rootuserpages'    => 'Hööft-Brukersieden schuven',
'right-movefile'              => 'Datein schuven',
'right-suppressredirect'      => 'Bi dat Schuven keen Redirect maken',
'right-upload'                => 'Datein hoochladen',
'right-reupload'              => 'Datein Överschrieven',
'right-reupload-own'          => 'Överschrieven vun Datein, de een sülvst hoochlaadt hett',
'right-reupload-shared'       => 'Datein lokal hoochladen, de dat al op’n gemeensam bruukten Datei-Spiekerplatz gifft',
'right-upload_by_url'         => 'Datein vun en URL-Adress hoochladen',
'right-purge'                 => 'Siedencache leddig maken ahn dat noch wedder fraagt warrt',
'right-autoconfirmed'         => 'Halfschuulte Sieden ännern',
'right-bot'                   => 'Lieks as en automaatschen Prozess behannelt warrn',
'right-nominornewtalk'        => 'Lüttje Ännern an Diskuschoonssieden wiest keen „Ne’e Narichten“',
'right-apihighlimits'         => 'Bruuk högere Limits in API-Affragen',
'right-writeapi'              => 'Ännern över de Schriev-API',
'right-delete'                => 'Sieden wegsmieten',
'right-bigdelete'             => 'Sieden mit grote Versionsgeschichten wegsmieten',
'right-deleterevision'        => 'Wegsmieten un Wedderhalen vun enkelte Versionen',
'right-deletedhistory'        => 'wegsmetene Versionen in de Versionsgeschicht ankieken (aver nich den Text)',
'right-browsearchive'         => 'Söök na wegsmetene Sieden',
'right-undelete'              => 'Sieden wedderhalen',
'right-suppressrevision'      => 'Ankieken un wedderhalen vun Versionen, de ok för Administraters versteken sünd',
'right-suppressionlog'        => 'Private Logböker ankieken',
'right-block'                 => 'Brukers dat Schrieven sperren',
'right-blockemail'            => 'Brukers dat Schrieven vun E-Mails sperren',
'right-hideuser'              => 'Brukernaam sperrn un nich mehr apen wiesen',
'right-ipblock-exempt'        => 'IP-Sperrn, Autoblocks un Rangesperrn ümgahn',
'right-proxyunbannable'       => 'Utnahm vun automaatsche Proxysperren',
'right-protect'               => 'Schuulstatus vun Sieden ännern',
'right-editprotected'         => 'Schuulte Sieden ännern (ahn Kaskadensperr)',
'right-editinterface'         => 'Systemnarichten ännern',
'right-editusercssjs'         => 'Anner Lüüd ehr CSS- un JS-Datein ännern',
'right-editusercss'           => 'Anner Lüüd ehr CSS-Datein ännern',
'right-edituserjs'            => 'Anner Lüüd ehr JS-Datein ännern',
'right-rollback'              => 'Sieden gau trüchdreihn',
'right-markbotedits'          => 'Trüchdreihte Ännern as Bot-Ännern marken',
'right-noratelimit'           => 'Tempolimit nich ünnerworpen',
'right-import'                => 'Sieden ut annere Wikis importeren',
'right-importupload'          => 'Sieden över Datei hoochladen importeren',
'right-patrol'                => 'Anner Lüüd ehr Ännern as nakeken marken',
'right-autopatrol'            => 'Egene Ännern automaatsch as nakeken marken',
'right-patrolmarks'           => 'Nakeken-Teken in de Ne’esten Ännern ankieken',
'right-unwatchedpages'        => 'List mit Sieden, de op kene Oppasslist staht, ankieken',
'right-trackback'             => 'Trackback övermiddeln',
'right-mergehistory'          => 'Versionsgeschichten tohoopföhren',
'right-userrights'            => 'Brukerrechten ännern',
'right-userrights-interwiki'  => 'Brukerrechten op annere Wikis ännern',
'right-siteadmin'             => 'Datenbank sperren un wedder apen maken',
'right-reset-passwords'       => 'Anner Lüüd ehr Passwoord trüchsetten',
'right-override-export-depth' => 'Exporteer Sieden, lenkt Sieden inslaten bet to en Deepd vun 5',

# User rights log
'rightslog'      => 'Brukerrechten-Logbook',
'rightslogtext'  => 'In dit Logbook staht Ännern an de Brukerrechten.',
'rightslogentry' => 'Grupp bi $1 vun $2 op $3 ännert.',
'rightsnone'     => '(kene)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'de Sied to lesen',
'action-edit'                 => 'de Sied to ännern',
'action-createpage'           => 'Sieden optostellen',
'action-createtalk'           => 'Diskuschoonssieden optostellen',
'action-createaccount'        => 'dit Brukerkonto optostellen',
'action-minoredit'            => 'dit Ännern as lütt to marken',
'action-move'                 => 'de Sied to schuven',
'action-move-subpages'        => 'de Sied un ehr Ünnersieden to schuven',
'action-move-rootuserpages'   => 'Hööft-Brukersieden to schuven',
'action-movefile'             => 'Disse Datei schuven',
'action-upload'               => 'disse Datei hoochtoladen',
'action-reupload'             => 'disse Datei to överschrieven',
'action-reupload-shared'      => 'över disse Datei vun’n gemeensam bruukten Bildspiekerplatz en anner Bild rövertosetten',
'action-upload_by_url'        => 'disse Datei vun en Webadress (URL) hoochtoladen',
'action-writeapi'             => 'de Schriev-API to bruken',
'action-delete'               => 'de Sied wegtodoon',
'action-deleterevision'       => 'disse Version wegtodoon',
'action-deletedhistory'       => 'disse Sied ehr wegdaan Versionen antokieken',
'action-browsearchive'        => 'na wegdaan Sieden to söken',
'action-undelete'             => 'de Sied weddertohalen',
'action-suppressrevision'     => 'disse verstekene Version antokieken un weddertohalen',
'action-suppressionlog'       => 'dit private Logbook antokieken',
'action-block'                => 'dissen Bruker to sperren',
'action-protect'              => 'den Schuulstatus vun disse Sied to ännern',
'action-import'               => 'disse Sied ut en anner Wiki to importeren',
'action-importupload'         => 'disse Sied över dat Hoochladen vun ene Datei to importeren',
'action-patrol'               => 'anner Brukers jemehr Ännern as nakeken to marken',
'action-autopatrol'           => 'egen Ännern as nakeken to marken',
'action-unwatchedpages'       => 'de List mit Sieden, de op kene Oppasslist staht, antokieken',
'action-trackback'            => 'en Trackback to överdragen',
'action-mergehistory'         => 'de Versionengeschichten vun disse Sied tohooptoföhren',
'action-userrights'           => 'all Brukerrechten to ännern',
'action-userrights-interwiki' => 'de Rechten vun Brukers op annere Wikis to ännern',
'action-siteadmin'            => 'de Datenbank to sperren oder freetogeven',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|Een Ännern|$1 Ännern}}',
'recentchanges'                     => 'Toletzt ännert',
'recentchanges-legend'              => 'Optionen för toletzt ännert',
'recentchangestext'                 => 'Op disse Sied warrt de Sieden wiest, de toletzt ännert worrn sünd.',
'recentchanges-feed-description'    => 'Behool mit dissen Feed de ne’esten Ännern op dit Wiki in’t Oog.',
'rcnote'                            => "Hier sünd de letzten '''$1''' Ännern vun {{PLURAL:$2|den letzten Dag|de letzten '''$2''' Daag}} (Stand $5, $4). ('''N''' - Ne’e Sieden; '''L''' - Lütte Ännern)",
'rcnotefrom'                        => 'Dit sünd de Ännern siet <b>$2</b> (bet to <b>$1</b> wiest).',
'rclistfrom'                        => 'Wies ne’e Ännern siet $1',
'rcshowhideminor'                   => '$1 lütte Ännern',
'rcshowhidebots'                    => '$1 Bots',
'rcshowhideliu'                     => '$1 inloggte Brukers',
'rcshowhideanons'                   => '$1 anonyme Brukers',
'rcshowhidepatr'                    => '$1 nakekene Ännern',
'rcshowhidemine'                    => '$1 miene Ännern',
'rclinks'                           => "Wies de letzten '''$1''' Ännern vun de letzten '''$2''' Daag. ('''N''' - Ne’e Sieden; '''L''' - Lütte Ännern)<br />$3",
'diff'                              => 'Ünnerscheed',
'hist'                              => 'Versionen',
'hide'                              => 'Nich wiesen',
'show'                              => 'Wiesen',
'minoreditletter'                   => 'L',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|Een Bruker|$1 Brukers}}, de oppasst]',
'rc_categories'                     => 'Blot Sieden ut de Kategorien (trennt mit „|“):',
'rc_categories_any'                 => 'All',
'newsectionsummary'                 => '/* $1 */ nee Afsnitt',
'rc-enhanced-expand'                => 'Details wiesen (bruukt JavaScript)',
'rc-enhanced-hide'                  => 'Details versteken',

# Recent changes linked
'recentchangeslinked'          => 'Ännern an lenkte Sieden',
'recentchangeslinked-feed'     => 'Ännern an lenkte Sieden',
'recentchangeslinked-toolbox'  => 'Ännern an lenkte Sieden',
'recentchangeslinked-title'    => 'Ännern an Sieden, de vun „$1“ ut lenkt sünd',
'recentchangeslinked-noresult' => 'In disse Tiet hett nüms de lenkten Sieden ännert.',
'recentchangeslinked-summary'  => "Disse List wiest de letzten Ännern an de Sieden, de vun en bestimmte Siet ut verlenkt oder in en bestimmte Kategorie in sünd. Sieden, de op diene [[Special:Watchlist|Oppasslist]] staht, sünd '''fett''' schreven.",
'recentchangeslinked-page'     => 'Siet:',
'recentchangeslinked-to'       => 'Wies Ännern op Sieden, de hierher wiest',

# Upload
'upload'                      => 'Hoochladen',
'uploadbtn'                   => 'Datei hoochladen',
'reuploaddesc'                => 'Trüch to de Hoochladen-Siet.',
'uploadnologin'               => 'Nich anmellt',
'uploadnologintext'           => 'Du musst [[Special:UserLogin|anmellt wesen]], dat du Datein hoochladen kannst.',
'upload_directory_missing'    => 'De Dateimapp för hoochladene Datein ($1) fehlt un de Webserver kunn ehr ok nich nee opstellen.',
'upload_directory_read_only'  => 'De Server kann nich in’n Orner för dat Hoochladen vun Datein ($1) schrieven.',
'uploaderror'                 => 'Fehler bi dat Hoochladen',
'uploadtext'                  => "Bruuk dit Formular, ne’e Datein hoochtoladen.
Dat du hoochladene Datein söken un ankieken kannst, gah na de [[Special:FileList|List vun hoochladene Datein]]. Dat Hoochladen un nee Hoochladen vun Datein warrt ok in dat [[Special:Log/upload|Hoochlade-Logbook]] fasthollen. Dat Wegsmieten in dat [[Special:Log/delete|Wegsmiet-Logbook]].

Üm en Datei in en Sied to bruken, schriev dat hier in de Sied rin:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:datei.jpg]]</nowiki></tt>''' för de Datei in vulle Grött
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:datei.jpg|200px|thumb|left|Beschrieven]]</nowiki></tt>''' för dat Bild in en Breed vun 200 Pixels in en lütt Kassen op de linke Sied mit ''Beschrieven'' as Text ünner dat Bild
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' för en direkten Lenk op de Datei, ahn dat se wiest warrt.",
'upload-permitted'            => 'Verlöövte Dateitypen: $1.',
'upload-preferred'            => 'Vörtagene Dateitypen: $1.',
'upload-prohibited'           => 'Verbadene Dateitypen: $1.',
'uploadlog'                   => 'Hoochlade-Logbook',
'uploadlogpage'               => 'Hoochlade-Logbook',
'uploadlogpagetext'           => 'Ünnen steiht de List vun de ne’esten hoochladenen Datein.
Kiek bi de [[Special:NewFiles|Galerie vun ne’e Datein]] för en Översicht mit Duumnagel-Biller.',
'filename'                    => 'Dateinaam',
'filedesc'                    => 'Beschrieven',
'fileuploadsummary'           => 'Tosamenfaten:',
'filereuploadsummary'         => 'Dateiännern:',
'filestatus'                  => 'Copyright-Status:',
'filesource'                  => 'Born:',
'uploadedfiles'               => 'Hoochladene Datein',
'ignorewarning'               => 'Schiet op dat Wohrschauel un Datei spiekern',
'ignorewarnings'              => 'Schiet op all Wohrschauen',
'minlength1'                  => 'Dateinaams mööt opminnst een Teken lang wesen.',
'illegalfilename'             => 'In den Dateinaam „$1“ snd Teken in, de nich de Naams vun Sieden nich verlööft sünd. Söök di en annern Naam ut un denn versöök de Datei noch wedder hoochtoladen.',
'badfilename'                 => 'De Bildnaam is na „$1“ ännert worrn.',
'filetype-badmime'            => 'Datein vun den MIME-Typ „$1“ dröfft nich hoochlaadt warrn.',
'filetype-bad-ie-mime'        => 'Disse Datei kann nich hoochladen warrn. De Internet Explorer hett ehr as „$1“ kennt, wat en nich verlöövten Dateityp is, de womööglich Schaden toföögt.',
'filetype-unwanted-type'      => "'''„.$1“''' as Dateiformat schall beter nich bruukt warrn. As Dateityp beter {{PLURAL:$3|is|sünd}}: $2.",
'filetype-banned-type'        => "'''„.$1“''' is as Dateiformat nich tolaten. {{PLURAL:$3|As Dateityp verlöövt is|Verlöövte Dateitypen sünd}}: $2.",
'filetype-missing'            => 'Disse Datei hett keen Ennen (so as „.jpg“).',
'large-file'                  => 'Datein schöölt opbest nich grötter wesen as $1. Disse Datei is $2 groot.',
'largefileserver'             => 'De Datei is grötter as de vun’n Server verlöövte Bövergrenz för de Grött.',
'emptyfile'                   => 'De hoochladene Datei is leddig. De Grund kann en Tippfehler in de Dateinaam ween. Kontrolleer, of du de Datei redig hoochladen wullst.',
'fileexists'                  => "En Datei mit dissen Naam existeert al, prööv '''<tt>[[:$1]]</tt>''', wenn du di nich seker büst of du dat ännern wullst.
[[$1|thumb]]",
'filepageexists'              => "En Sied, de dat Bild beschrifft, gifft dat al as '''<tt>[[:$1]]</tt>''', dat gifft aver keen Datei mit dissen Naam. De Text, den du hier ingiffst, warrt nich op de Sied övernahmen. Du musst de Sied na dat Hoochladen noch wedder extra ännern.",
'fileexists-extension'        => "Dat gifft al en Datei mit en ähnlichen Naam: [[$2|thumb]]
* Naam vun diene Datei: '''<tt>[[:$1]]</tt>'''
* Naam vun de Datei, de al dor is: '''<tt>[[:$2]]</tt>'''
Blot dat Ennen vun de Datei is bi dat Groot-/Lütt-Schrieven anners. Kiek na, wat de Datein villicht desülven sünd.",
'fileexists-thumbnail-yes'    => "De Datei schient en Bild to wesen, dat lütter maakt is ''(thumbnail)''. [[$1|thumb]]
Kiek di de Datei '''<tt>[[:$1]]</tt>''' an.
Wenn dat dat Bild in vulle Grött is, denn bruukst du keen extra Vörschaubild hoochladen.",
'file-thumbnail-no'           => "De Dateinaam fangt an mit '''<tt>$1</tt>'''. Dat düüdt dor op hen, dat dat en lütter maakt Bild ''(thumbnail, Duumnagel-Bild)'' is.
Kiek na, wat du dat Bild nich ok in vulle Grött hest un laad dat ünner’n Originalnaam hooch oder änner den Dateinaam.",
'fileexists-forbidden'        => 'En Datei mit dissen Naam gifft dat al un kann nich överschreven warrn.
Wenn du ehr liekers hoochladen wullt, gah trüch un laad de Datei ünner en annern Naam hooch. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dat gifft al en Datei mit dissen Naam. Gah trüch un laad de Datei ünner en annern Naam hooch. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'De Datei is desülve as disse {{PLURAL:$1|Datei|$1 Datein}}:',
'file-deleted-duplicate'      => 'Jüst disse Datei ([[$1]]) is al mal löscht worrn. Kiek man eerst, wat in dat Löschlogbook steiht, ehrdat du ehr noch wedder hoochlaadst.',
'uploadwarning'               => 'Wohrschau',
'savefile'                    => 'Datei spiekern',
'uploadedimage'               => '„$1“ hoochladen',
'overwroteimage'              => 'Ne’e Version vun „[[$1]]“ hoochlaadt',
'uploaddisabled'              => 'Dat Hoochladen is deaktiveert.',
'uploaddisabledtext'          => 'Dat Hoochladen vun Datein is utschalt.',
'php-uploaddisabledtext'      => 'Dat Hoochladen vun Datein is in PHP utstellt. Kiek de file_uploads-Instellungen na.',
'uploadscripted'              => 'In disse Datei steiht HTML- oder Skriptkood in, de vun welk Browsers verkehrt dorstellt oder utföhrt warrn kann.',
'uploadvirus'                 => 'In de Datei stickt en Virus! Mehr: $1',
'sourcefilename'              => 'Dateinaam op dien Reekner:',
'destfilename'                => 'Dateinaam, so as dat hier spiekert warrn schall:',
'upload-maxfilesize'          => 'Maximale Dateigrött: $1',
'watchthisupload'             => 'Op disse Datei oppassen',
'filewasdeleted'              => 'En Datei mit dissen Naam hett dat al mal geven un is denn wegsmeten worrn. Kiek doch toeerst in dat $1 na, ehrdat du de Datei afspiekerst.',
'upload-wasdeleted'           => "'''Wohrschau: Du läädst en Datei hooch, de al ehrder mal wegsmeten worrn is.'''

Bedenk di eerst, wat dat ok passt, dat du de Datei noch wedder hoochladen deist.
Hier dat Logbook, wo insteiht, worüm de Sied wegsmeten worrn is:",
'filename-bad-prefix'         => "De Naam vun de Datei fangt mit '''„$1“''' an. Dat is normalerwies en Naam, den de Datei automaatsch vun de Digitalkamera kriggt. De Naam beschrievt de Datei nich un seggt dor ok nix över ut. Söök di doch en Naam för de Datei ut, de ok wat över den Inholt seggt.",
'upload-success-subj'         => 'Datei hoochladen hett Spood',

'upload-proto-error'      => 'Verkehrt Protokoll',
'upload-proto-error-text' => 'De URL mutt mit <code>http://</code> oder <code>ftp://</code> anfangen.',
'upload-file-error'       => 'Internen Fehler',
'upload-file-error-text'  => 'Dat geev en internen Fehler bi dat Anleggen vun en temporäre Datei op’n Server. Segg man en [[Special:ListUsers/sysop|Administrater]] bescheed.',
'upload-misc-error'       => 'Unbekannt Fehler bi dat Hoochladen',
'upload-misc-error-text'  => 'Bi dat Hoochladen geev dat en unbekannten Fehler. Kiek na, wat dor en Fehler in de URL is, wat de Websteed ok löppt un versöök dat denn noch wedder. Wenn dat Problem denn noch jümmer dor is, denn vertell dat en [[Special:ListUsers/sysop|System-Administrater]].',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kunn de URL nich kriegen',
'upload-curl-error6-text'  => 'De URL kunn nich opropen warrn. Kiek na, wat dor ok keen Fehler in de URL is un wat de Websteed ok löppt.',
'upload-curl-error28'      => 'Tied-Ut bi dat Hoochladen',
'upload-curl-error28-text' => 'De Siet hett to lang bruukt för en Antwoort.
Kiek na, wat de Siet ok online is, tööv en Stoot un versöök dat denn noch wedder.
Kann angahn, dat dat beter geiht, wenn du dat to en Tiet versöchst, to de op de Siet nich ganz so veel los is.',

'license'            => 'Lizenz:',
'license-header'     => 'Lizenz:',
'nolicense'          => 'nix utwählt',
'license-nopreview'  => '(Vörschau nich mööglich)',
'upload_source_url'  => ' (gellen, apen togängliche URL)',
'upload_source_file' => ' (en Datei op dien Reekner)',

# Special:ListFiles
'listfiles-summary'     => 'Disse Spezialsied wiest all Datein. As Standard warrt de ne’esten Datein toeerst wiest. Wenn du op de enkelten Överschriften klickst, kannst du de Sortreeg ümdreihn oder na en anner Kriterium sorteren.',
'listfiles_search_for'  => 'Söök na Datei:',
'imgfile'               => 'Datei',
'listfiles'             => 'Billerlist',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Naam',
'listfiles_user'        => 'Bruker',
'listfiles_size'        => 'Grött (Bytes)',
'listfiles_description' => 'Beschrieven',
'listfiles_count'       => 'Versionen',

# File description page
'file-anchor-link'          => 'Bild',
'filehist'                  => 'Datei-Historie',
'filehist-help'             => 'Klick op de Tiet, dat du de Datei ankieken kannst, so as se do utseeg.',
'filehist-deleteall'        => 'all wegsmieten',
'filehist-deleteone'        => 'wegsmieten',
'filehist-revert'           => 'Trüchsetten',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Datum/Tiet',
'filehist-thumb'            => 'Duumnagelbild',
'filehist-thumbtext'        => 'Duumnagelbild för Version vun’n $1',
'filehist-nothumb'          => 'Keen Duumnagelbild',
'filehist-user'             => 'Bruker',
'filehist-dimensions'       => 'Grött',
'filehist-filesize'         => 'Dateigrött',
'filehist-comment'          => 'Kommentar',
'filehist-missing'          => 'Datei fehlt',
'imagelinks'                => 'Dateiverwiesen',
'linkstoimage'              => 'Disse {{PLURAL:$1|Sied|Sieden}} bruukt dit Bild:',
'linkstoimage-more'         => 'Mehr as {{PLURAL:$1|ene Sied|$1 Sieden}} wiest na disse Datei.
Disse List wiest blot {{PLURAL:$1|den eersten Lenk|de eersten $1 Lenken}} op disse Datei.
En [[Special:WhatLinksHere/$2|kumplette List]] gifft dat ok.',
'nolinkstoimage'            => 'Kene Siet bruukt dat Bild.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Mehr Verwiesen]] för disse Datei.',
'redirectstofile'           => 'Disse {{PLURAL:$1|Datei is|Datein sünd}} en Redirect op disse Datei:',
'duplicatesoffile'          => 'Disse {{PLURAL:$1|Datei is|Datein sünd}} jüst de {{PLURAL:$1|glieke|glieken}} as disse Datei hier ([[Special:FileDuplicateSearch/$2|mehr Infos]]):',
'sharedupload'              => 'Disse Datei is as gemeensam bruukte Datei op $1 hoochlaadt un warrt mööglicherwies ok vun annere Wikis bruukt.',
'sharedupload-desc-there'   => 'Disse Datei is as gemeensam bruukte Datei op $1 hoochlaadt un warrt mööglicherwies ok vun annere Wikis bruukt.
Kiek op de [$2 Bildsied dor] för mehr Infos.',
'sharedupload-desc-here'    => 'Disse Datei is as gemeensam bruukte Datei op $1 hoochlaadt un warrt mööglicherwies ok vun annere Wikis bruukt.
De [$2 Bildsied vun dor] warrt ünnen wiest.',
'filepage-nofile'           => 'Gifft keen Datei mit dissen Naam.',
'filepage-nofile-link'      => 'Dat gifft keen Datei mit dissen Naam, aver du kannst een [$1 hoochladen].',
'uploadnewversion-linktext' => 'Ne’e Version vun disse Datei hoochladen',
'shared-repo-from'          => 'ut $1',
'shared-repo'               => 'en tohoop bruukt Medienarchiv',

# File reversion
'filerevert'                => '„$1“ Trüchsetten',
'filerevert-legend'         => 'Datei trüchsetten',
'filerevert-intro'          => "Du settst de Datei '''[[Media:$1|$1]]''' op de [$4 Version vun $2, $3] trüch.",
'filerevert-comment'        => 'Kommentar:',
'filerevert-defaultcomment' => 'trüchsett op de Version vun’n $1, $2',
'filerevert-submit'         => 'Trüchsetten',
'filerevert-success'        => "'''[[Media:$1|$1]]''' is op de [$4 Version vun $2, $3] trüchsett worrn.",
'filerevert-badversion'     => 'Gifft keen vörige lokale Version vun de Datei to disse Tied.',

# File deletion
'filedelete'                  => '$1 wegsmieten',
'filedelete-legend'           => 'Datei wegsmieten',
'filedelete-intro'            => "Du smittst de Datei '''[[Media:$1|$1]]''' tohoop mit de ganze Versionsgeschicht weg.",
'filedelete-intro-old'        => "Du smittst vun de Datei '''„[[Media:$1|$1]]“''' de [$4 Version vun $2, $3] weg.",
'filedelete-comment'          => 'Grund:',
'filedelete-submit'           => 'Wegsmieten',
'filedelete-success'          => "'''$1''' wegsmeten.",
'filedelete-success-old'      => "De Version vun de Datei '''„[[Media:$1|$1]]“''' vun $2, $3 is wegsmeten worrn.",
'filedelete-nofile'           => "'''$1''' gifft dat nich.",
'filedelete-nofile-old'       => "Gifft keen Version vun '''„$1“''' in’t Archiv mit disse Egenschoppen.",
'filedelete-otherreason'      => 'Annern/tosätzlichen Grund:',
'filedelete-reason-otherlist' => 'Annern Grund',
'filedelete-reason-dropdown'  => '* Faken bruukte Grünn
** Verstoot gegen Oorheverrecht
** dubbelt vörhannen',
'filedelete-edit-reasonlist'  => 'Grünn för’t Wegsmieten ännern',

# MIME search
'mimesearch'         => 'MIME-Söök',
'mimesearch-summary' => 'Disse Sied verlööft dat Filtern vun Datein na’n MIME-Typ. Du musst jümmer den Medien- un den Subtyp ingeven, to’n Bispeel: <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-Typ:',
'download'           => 'Dalladen',

# Unwatched pages
'unwatchedpages' => 'Sieden, de op kene Oppasslist staht',

# List redirects
'listredirects' => 'List vun Redirects',

# Unused templates
'unusedtemplates'     => 'Nich bruukte Vörlagen',
'unusedtemplatestext' => 'Disse Sied wiest all Sieden in’n Naamruum „{{ns:template}}“, de nich op annere Sieden inbunnen warrt.
Denk dor an, natokieken, wat nich noch annere Sieden na de Vörlagen wiest, ehrdat du jem wegsmittst.',
'unusedtemplateswlh'  => 'Annere Lenken',

# Random page
'randompage'         => 'Tofällige Siet',
'randompage-nopages' => 'Gifft kene Sieden in’n Naamruum „$1“.',

# Random redirect
'randomredirect'         => 'Tofällig Redirect',
'randomredirect-nopages' => 'Gifft kene Redirects in’n Naamruum „$1“.',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Siedenstatistik',
'statistics-header-edits'      => 'Änner-Statistik',
'statistics-header-views'      => 'Siedenweddergaav-Statistik',
'statistics-header-users'      => 'Brukerstatistik',
'statistics-articles'          => 'Inholtssieden',
'statistics-pages'             => 'Sieden',
'statistics-pages-desc'        => 'All Sieden in dit Wiki, tohoop mit all Diskuschoonssieden, Redirects usw.',
'statistics-files'             => 'Hoochlaadt Datein',
'statistics-edits'             => 'Ännern, siet dat {{SITENAME}} gifft',
'statistics-edits-average'     => 'Dörsnittlich Ännern je Sied',
'statistics-views-total'       => 'Weddergeven Sieden alltohoop',
'statistics-views-peredit'     => 'Weddergeven Sieden je Ännern',
'statistics-users'             => 'Anmellt [[Special:ListUsers|Brukers]]',
'statistics-users-active'      => 'Aktive Brukers',
'statistics-users-active-desc' => 'Brukers, de {{PLURAL:$1|in de vergahn 24 Stünnen|in de vergahn $1 Daag}} wat daan hebbt',
'statistics-mostpopular'       => 'opmehrst ankekene Sieden',

'disambiguations'      => 'Mehrdüdige Begrepen',
'disambiguationspage'  => 'Template:Mehrdüdig_Begreep',
'disambiguations-text' => 'Disse Sieden wist na Sieden för mehrdüdige Begrepen. Se schöölt lever op de Sieden wiesen, de egentlich meent sünd.<br />Ene Siet warrt as Siet för en mehrdüdigen Begreep ansehn, wenn [[MediaWiki:Disambiguationspage]] na ehr wiest.<br />Lenken ut annere Naamrüüm sünd nich mit in de List.',

'doubleredirects'            => 'Dubbelte Wiederleiden',
'doubleredirectstext'        => '<b>Wohrscho:</b> Disse List kann „falsche Positive“ bargen.
Dat passeert denn, wenn en Wiederleiden blangen de Wiederleiden-Verwies noch mehr Text mit annere Verwiesen hett.
De schallen denn löscht warrn. Elk Reeg wiest de eerste un tweete Wiederleiden un de eerste Reeg Text ut de Siet,
to den vun den tweeten Wiederleiden wiest warrt, un to den de eerste Wiederleiden mehrst wiesen schall.',
'double-redirect-fixed-move' => '[[$1]] is schaven worrn un wiest nu na [[$2]]',
'double-redirect-fixer'      => 'Redirect-Utbeterer',

'brokenredirects'        => 'Kaputte Wiederleiden',
'brokenredirectstext'    => 'Disse Redirects wiest na Sieden, de dat nich gifft.',
'brokenredirects-edit'   => 'ännern',
'brokenredirects-delete' => 'wegsmieten',

'withoutinterwiki'         => 'Sieden ahn Spraaklenken',
'withoutinterwiki-summary' => 'Disse Sieden hebbt keen Lenken na annere Spraakversionen:',
'withoutinterwiki-legend'  => 'Präfix',
'withoutinterwiki-submit'  => 'Wies',

'fewestrevisions' => 'Sieden mit de wenigsten Versionen',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '$1 {{PLURAL:$1|Verwies|Verwiesen}}',
'nmembers'                => '$1 {{PLURAL:$1|Maat|Maten}}',
'nrevisions'              => '{{PLURAL:$1|1 Version|$1 Versionen}}',
'nviews'                  => '$1 {{PLURAL:$1|Affraag|Affragen}}',
'specialpage-empty'       => 'Disse Siet is leddig.',
'lonelypages'             => 'Weetsieden',
'lonelypagestext'         => 'Op disse Sieden wiest kene annern Sieden vun {{SITENAME}} un se sünd ok narms inbunnen.',
'uncategorizedpages'      => 'Sieden ahn Kategorie',
'uncategorizedcategories' => 'Kategorien ahn Kategorie',
'uncategorizedimages'     => 'Datein ahn Kategorie',
'uncategorizedtemplates'  => 'Vörlagen ahn Kategorie',
'unusedcategories'        => 'Kategorien ahn insorteerte Artikels oder Ünnerkategorien',
'unusedimages'            => 'Weetbiller',
'popularpages'            => 'Faken opropene Sieden',
'wantedcategories'        => 'Kategorien, de veel bruukt warrt, aver noch keen Text hebbt (nich anleggt sünd)',
'wantedpages'             => 'Sieden, de noch fehlt',
'wantedpages-badtitle'    => 'Ungülligen Titel in Resultaat: $1',
'wantedfiles'             => 'Wünschte Datein',
'wantedtemplates'         => 'Vörlagen, de noch fehlt',
'mostlinked'              => 'Sieden, op de vele Lenken wiest',
'mostlinkedcategories'    => 'Kategorien, op de vele Lenken wiest',
'mostlinkedtemplates'     => 'Vörlagen, op de vele Lenken wiest',
'mostcategories'          => 'Artikels mit vele Kategorien',
'mostimages'              => 'Datein, de veel bruukt warrt',
'mostrevisions'           => 'Sieden mit de mehrsten Versionen',
'prefixindex'             => 'All Sieden mit Präfix',
'shortpages'              => 'Korte Sieden',
'longpages'               => 'Lange Sieden',
'deadendpages'            => 'Sackstraatsieden',
'deadendpagestext'        => 'Disse Sieden wiest op kene annern Sieden vun {{SITENAME}}.',
'protectedpages'          => 'Schuulte Sieden',
'protectedpages-indef'    => 'Blot unbeschränkt schuulte Sieden wiesen',
'protectedpages-cascade'  => 'Blot Sieden mit Kaskadenschutz',
'protectedpagestext'      => 'Disse Sieden sünd vör dat Schuven oder Ännern schuult',
'protectedpagesempty'     => 'Opstunns sünd kene Sieden schuult',
'protectedtitles'         => 'Sparrte Sieden',
'protectedtitlestext'     => 'Disse Sieden sünd för dat nee Opstellen sperrt',
'protectedtitlesempty'    => 'Opstunns sünd mit disse Parameters kene Sieden sperrt.',
'listusers'               => 'Brukerlist',
'listusers-editsonly'     => 'Blot Brukers mit Bidrääg wiesen',
'listusers-creationsort'  => 'Na Opstelldatum sorteren',
'usereditcount'           => '$1 {{PLURAL:$1|Ännern|Ännern}}',
'usercreated'             => 'Opstellt an’n $1 üm $2',
'newpages'                => 'Ne’e Sieden',
'newpages-username'       => 'Brukernaam:',
'ancientpages'            => 'Öllste Sieden',
'move'                    => 'Schuven',
'movethispage'            => 'Siet schuven',
'unusedimagestext'        => 'Denk doran, dat annere Wikis mööglicherwies en poor vun disse Biller bruken.',
'unusedcategoriestext'    => 'Disse Kategorien sünd leddig, keen Artikel un kene Ünnerkategorie steiht dor in.',
'notargettitle'           => 'Kene Siet angeven',
'notargettext'            => 'Du hest nich angeven, op welke Siet du disse Funktschoon anwennen willst.',
'nopagetitle'             => 'Teelsiet gifft dat nich',
'nopagetext'              => 'De angevene Teelsiet gifft dat nich.',
'pager-newer-n'           => '{{PLURAL:$1|nächste|nächste $1}}',
'pager-older-n'           => '{{PLURAL:$1|vörige|vörige $1}}',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Bookhannel',
'booksources-search-legend' => 'Na Böker bi Bookhökers söken',
'booksources-go'            => 'Los',
'booksources-text'          => 'Hier staht Lenken na Websteden, woneem dat Böker to köpen gifft, de mitünner ok mehr Informatschonen to dat Book anbeden doot:',
'booksources-invalid-isbn'  => 'Süht ut, as wenn de angeven ISBN ungüllig is. Villicht hett dat en Fehler bi’t Afschrieven oder Koperen geven.',

# Special:Log
'specialloguserlabel'  => 'Bruker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logböker',
'all-logs-page'        => 'All Logböker',
'alllogstext'          => 'Kombineerte Ansicht vun all Logböker bi {{SITENAME}}.
Du kannst de List körter maken, wenn du den Logbook-Typ, den Brukernaam (grote un lütte Bookstaven maakt en Ünnerscheed) oder de Sied angiffst (ok mit Ünnerscheed vun grote un lütte Bookstaven).',
'logempty'             => 'In’e Logböker nix funnen, wat passt.',
'log-title-wildcard'   => 'Titel fangt an mit …',

# Special:AllPages
'allpages'          => 'Alle Sieden',
'alphaindexline'    => '$1 bet $2',
'nextpage'          => 'tokamen Siet ($1)',
'prevpage'          => 'Vörige Siet ($1)',
'allpagesfrom'      => 'Sieden wiesen, de mit disse Bookstaven anfangt:',
'allpagesto'        => 'Sieden wiesen bet:',
'allarticles'       => 'Alle Artikels',
'allinnamespace'    => 'Alle Sieden (Naamruum $1)',
'allnotinnamespace' => 'Alle Sieden (nich in Naamruum $1)',
'allpagesprev'      => 'vörig',
'allpagesnext'      => 'tokamen',
'allpagessubmit'    => 'Los',
'allpagesprefix'    => 'Sieden wiesen, de anfangt mit:',
'allpagesbadtitle'  => 'De ingevene Siedennaam gellt nich: Kann angahn, dor steiht en Afkörten för en annere Spraak oder en anneret Wiki an’n Anfang oder dor sünd Tekens binnen, de in Siedennaams nich bruukt warrn dröfft.',
'allpages-bad-ns'   => '{{SITENAME}} hett keen Naamruum „$1“.',

# Special:Categories
'categories'                    => 'Kategorien',
'categoriespagetext'            => 'In disse Kategorien staht Sieden oder Mediendatein.
[[Special:UnusedCategories|Nich bruukte Kategorien]] warrt hier nich wiest.
Kiek ok bi de [[Special:WantedCategories|wünschten Kategorien]].',
'categoriesfrom'                => 'Wies Kategorien anfungen mit:',
'special-categories-sort-count' => 'na Tall sorteren',
'special-categories-sort-abc'   => 'alphabeetsch sorteren',

# Special:DeletedContributions
'deletedcontributions'             => 'Wegsmetene Bidrääg vun’n Bruker',
'deletedcontributions-title'       => 'Wegsmetene Bidrääg vun’n Bruker',
'sp-deletedcontributions-contribs' => 'Bidrääg',

# Special:LinkSearch
'linksearch'       => 'Weblenken söken',
'linksearch-pat'   => 'Söökmunster:',
'linksearch-ns'    => 'Naamruum:',
'linksearch-ok'    => 'Söken',
'linksearch-text'  => 'Wildcards as to’n Bispeel <tt>*.wikipedia.org</tt> köönt bruukt warrn.<br />
Ünnerstütt Protokollen: <tt>$1</tt>',
'linksearch-line'  => '$1 hett en Lenk vun $2',
'linksearch-error' => 'Wildcards dröfft blot an’n Anfang vun de URL stahn.',

# Special:ListUsers
'listusersfrom'      => 'Wies de Brukers, de anfangt mit:',
'listusers-submit'   => 'Wiesen',
'listusers-noresult' => 'Keen Bruker funnen.',

# Special:Log/newusers
'newuserlogpage'              => 'Ne’e-Bruker-Logbook',
'newuserlogpagetext'          => 'Dit is dat Logbook för nee opstellte Brukerkonten.',
'newuserlog-byemail'          => 'Passwoord per E-Mail toschickt',
'newuserlog-create-entry'     => 'Nee Bruker',
'newuserlog-create2-entry'    => 'hett nee Brukerkonto „$1“ opstellt',
'newuserlog-autocreate-entry' => 'Brukerkonto automaatsch opstellt',

# Special:ListGroupRights
'listgrouprights'                      => 'Brukergruppen-Rechten',
'listgrouprights-summary'              => 'Dit is en List vun de Brukergruppen, de in dit Wiki defineert sünd, un de Rechten, de dor mit verbunnen sünd.
Mehr Informatschonen över enkelte Rechten staht ünner [[{{MediaWiki:Listgrouprights-helppage}}]].',
'listgrouprights-group'                => 'Grupp',
'listgrouprights-rights'               => 'Rechten',
'listgrouprights-helppage'             => 'Help:Gruppenrechten',
'listgrouprights-members'              => '(Matenlist)',
'listgrouprights-addgroup'             => 'Kann Brukers to {{PLURAL:$2|disse Grupp|disse Gruppen}} tofögen: $1',
'listgrouprights-removegroup'          => 'Kann Brukers ut {{PLURAL:$2|disse Grupp|disse Gruppen}} rutnehmen: $1',
'listgrouprights-addgroup-all'         => 'Kann all Gruppen tofögen',
'listgrouprights-removegroup-all'      => 'Kann all Gruppen wegnehmen',
'listgrouprights-addgroup-self'        => 'Kann {{PLURAL:$2|Grupp|Gruppen}} bi dat egen Brukerkonto tofögen: $1',
'listgrouprights-removegroup-self'     => 'Kann {{PLURAL:$2|Grupp|Gruppen}} bi dat egen Brukerkonto rutnehmen: $1',
'listgrouprights-addgroup-self-all'    => 'Kann all Gruppen to’t egen Brukerkonto tofögen',
'listgrouprights-removegroup-self-all' => 'Kann all Gruppen vun’t egen Brukerkonto wegdoon',

# E-mail user
'mailnologin'      => 'Du büst nich anmellt.',
'mailnologintext'  => 'Du musst [[Special:UserLogin|anmellt wesen]] un in diene [[Special:Preferences|Instellungen]] en güllige E-Mail-Adress hebben, dat du annere Brukers E-Mails tostüren kannst.',
'emailuser'        => 'E-Mail an dissen Bruker',
'emailpage'        => 'E-Mail an Bruker',
'emailpagetext'    => 'Du kannst dissen Bruker mit dit Formular en E-Mail tostüren. As Afsenner warrt de E-Mail-Adress ut dien [[Special:Preferences|Instellen]] indragen, dat de Bruker di antern kann.',
'usermailererror'  => 'Dat Mail-Objekt hett en Fehler trüchgeven:',
'defemailsubject'  => '{{SITENAME}} E-Mail',
'noemailtitle'     => 'Kene E-Mail-Adress',
'noemailtext'      => 'Disse Bruker hett kene güllige E-Mail-Adress angeven.',
'nowikiemailtitle' => 'E-Mails sünd nich verlöövt',
'nowikiemailtext'  => 'Disse Bruker will vun annere Brukers keen E-Mails tostüürt kriegen.',
'email-legend'     => 'en annern Bruker op {{SITENAME}} en E-Mail tostüren',
'emailfrom'        => 'Vun:',
'emailto'          => 'An:',
'emailsubject'     => 'Bedrap:',
'emailmessage'     => 'Naricht:',
'emailsend'        => 'Sennen',
'emailccme'        => 'Ik will en Kopie vun mien Naricht an mien egen E-Mail-Adress hebben.',
'emailccsubject'   => 'Kopie vun diene Naricht an $1: $2',
'emailsent'        => 'E-Mail afsennt',
'emailsenttext'    => 'Dien E-Mail is afsennt worrn.',
'emailuserfooter'  => 'Disse E-Mail hett $1 över de Funkschoon „{{int:emailuser}}“ vun {{SITENAME}} $2 tostüürt.',

# Watchlist
'watchlist'            => 'Mien Oppasslist',
'mywatchlist'          => 'Mien Oppasslist',
'watchlistfor'         => "(för '''$1''')",
'nowatchlist'          => 'Du hest kene Indreeg op dien Oppasslist.',
'watchlistanontext'    => '$1, dat du dien Oppasslist ankieken oder ännern kannst.',
'watchnologin'         => 'Du büst nich anmellt',
'watchnologintext'     => 'Du must [[Special:UserLogin|anmellt]] wesen, wenn du dien Oppasslist ännern willst.',
'addedwatch'           => 'To de Oppasslist toföögt',
'addedwatchtext'       => 'De Siet „<nowiki>$1</nowiki>“ is to diene [[Special:Watchlist|Oppasslist]] toföögt worrn.
Ännern, de in Tokumst an disse Siet un an de tohörige Diskuschoonssiet maakt warrt, sünd dor op oplist un de Siet is op de [[Special:RecentChanges|List vun de letzten Ännern]] fett markt. Wenn du de Siet nich mehr op diene Oppasslist hebben willst, klick op „Nich mehr oppassen“.',
'removedwatch'         => 'De Siet is nich mehr op de Oppasslist',
'removedwatchtext'     => 'De Siet „[[:$1]]“ is nich mehr op de Oppasslist.',
'watch'                => 'Oppassen',
'watchthispage'        => 'Op disse Siet oppassen',
'unwatch'              => 'nich mehr oppassen',
'unwatchthispage'      => 'Nich mehr oppassen',
'notanarticle'         => 'Keen Artikel',
'notvisiblerev'        => 'Version wegsmeten',
'watchnochange'        => 'Kene Siet op dien Oppasslist is in den wiesten Tietruum ännert worrn.',
'watchlist-details'    => '{{PLURAL:$1|Ene Siet is|$1 Sieden sünd}} op dien Oppasslist (ahn Diskuschoonssieden).',
'wlheader-enotif'      => 'Benarichtigen per E-Mail is anstellt.',
'wlheader-showupdated' => "* Sieden, de siet dien letzten Besöök ännert worrn sünd, warrt '''fett''' wiest.",
'watchmethod-recent'   => 'letzte Ännern no Oppasslist pröven',
'watchmethod-list'     => 'Oppasslist na letzte Ännern nakieken',
'watchlistcontains'    => 'Diene Oppasslist bargt {{PLURAL:$1|ene Siet|$1 Sieden}}.',
'iteminvalidname'      => "Problem mit den Indrag '$1', ungülligen Naam...",
'wlnote'               => "Ünnen {{PLURAL:$1|steiht de letzte Ännern|staht de letzten $1 Ännern}} vun de {{PLURAL:$2|letzte Stünn|letzten '''$2''' Stünnen}}.",
'wlshowlast'           => 'Wies de letzten $1 Stünnen $2 Daag $3',
'watchlist-options'    => 'Optionen för de Oppasslist',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'warrt op de Oppasslist ropsett...',
'unwatching' => 'warrt vun de Oppasslist rünnernahmen...',

'enotif_mailer'                => '{{SITENAME}} E-Mail-Bescheedgeevdeenst',
'enotif_reset'                 => 'All Sieden as besöcht marken',
'enotif_newpagetext'           => 'Dit is en ne’e Siet.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Bruker',
'changed'                      => 'ännert',
'created'                      => 'opstellt',
'enotif_subject'               => '[{{SITENAME}}] De Siet „$PAGETITLE“ is vun $PAGEEDITOR $CHANGEDORCREATED worrn',
'enotif_lastvisited'           => 'All Ännern siet dien letzten Besöök op een Blick: $1',
'enotif_lastdiff'              => 'Kiek bi $1 för dit Ännern.',
'enotif_anon_editor'           => 'Anonymen Bruker $1',
'enotif_body'                  => 'Leve/n $WATCHINGUSERNAME,

de {{SITENAME}}-Siet „$PAGETITLE“ is vun $PAGEEDITOR an’n $PAGEEDITDATE $CHANGEDORCREATED ännert worrn.

Aktuelle Version: $PAGETITLE_URL

$NEWPAGE

Kommentar vun’n Bruker: $PAGESUMMARY $PAGEMINOREDIT

Kuntakt to’n Bruker:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Du kriggst solang keen Bescheedgeev-E-Mails mehr, bet dat du de Siet wedder besöcht hest. Op diene Oppasslist kannst du ok all Bescheedgeevmarker trüchsetten.

             Dien fründlich {{SITENAME}}-Bescheedgeevsystem

--
De Instellungen vun dien Oppasslist to ännern, gah na: {{fullurl:Special:Watchlist/edit}}',

# Delete
'deletepage'             => 'Siet wegsmieten',
'confirm'                => 'Bestätigen',
'excontent'              => 'Olen Inholt: ‚$1‘',
'excontentauthor'        => 'Inholt weer: ‚$1‘ (un de eenzige Autor weer ‚[[Special:Contributions/$2|$2]]‘)',
'exbeforeblank'          => 'Inholt vör dat Leddigmaken vun de Siet: ‚$1‘',
'exblank'                => 'Siet weer leddig',
'delete-confirm'         => '„$1“ wegsmieten',
'delete-legend'          => 'Wegsmieten',
'historywarning'         => 'Wohrschau: De Siet, de du bi büst to löschen, hett en Versionshistorie:',
'confirmdeletetext'      => 'Du büst dorbi, en Siet oder en Bild un alle ölleren Versionen duersam ut de Datenbank to löschen.
Segg to, dat du över de Folgen Bescheed weetst un dat du in Övereenstimmen mit uns [[{{MediaWiki:Policy-url}}|Leidlienen]] hannelst.',
'actioncomplete'         => 'Akschoon trech',
'actionfailed'           => 'Akschoon fehlslaan',
'deletedtext'            => 'De Artikel „<nowiki>$1</nowiki>“ is nu wegsmeten. Op $2 gifft dat en Logbook vun de letzten Löschakschonen.',
'deletedarticle'         => '„$1“ wegsmeten',
'suppressedarticle'      => 'hett „[[$1]]“ versteken',
'dellogpage'             => 'Lösch-Logbook',
'dellogpagetext'         => 'Hier is en List vun de letzten Löschen.',
'deletionlog'            => 'Lösch-Logbook',
'reverted'               => 'Op en ole Version trüchsett',
'deletecomment'          => 'Grund:',
'deleteotherreason'      => 'Annere/tosätzliche Grünn:',
'deletereasonotherlist'  => 'Annern Grund',
'deletereason-dropdown'  => '* Grünn för dat Wegsmieten
** op Wunsch vun’n Schriever
** gegen dat Oorheverrecht
** Vandalismus',
'delete-edit-reasonlist' => 'Grünn för’t Wegsmieten ännern',
'delete-toobig'          => 'Disse Siet hett en temlich lange Versionsgeschicht vun mehr as {{PLURAL:$1|ene Version|$1 Versionen}}. Dat Wegsmieten kann de Datenbank vun {{SITENAME}} för längere Tied utlasten un den Bedriev vun dat Wiki stöörn.',
'delete-warning-toobig'  => 'Disse Siet hett en temlich lange Versionsgeschicht vun mehr as {{PLURAL:$1|ene Version|$1 Versionen}}. Dat Wegsmieten kann de Datenbank vun {{SITENAME}} för längere Tied utlasten un den Bedriev vun dat Wiki stöörn.',

# Rollback
'rollback'         => 'Trüchnahm vun de Ännern',
'rollback_short'   => 'Trüchnehmen',
'rollbacklink'     => 'Trüchnehmen',
'rollbackfailed'   => 'Trüchnahm hett kenen Spood',
'cantrollback'     => 'De Ännern kann nich trüchnahmen warrn; de letzte Autor is de eenzige.',
'alreadyrolled'    => 'Dat Trüchnehmen vun de Ännern an de Siet [[:$1]] vun [[User:$2|$2]] ([[User talk:$2|Diskuschoonssiet]]{{int:pipe-separator}}[[Special:Contributions/$2|Bidrääg]]) is nich mööglich, vun wegen dat dor en annere Ännern oder Trüchnahm wesen is.

De letzte Ännern is vun [[User:$3|$3]] ([[User talk:$3|Diskuschoon]]{{int:pipe-separator}}[[Special:Contributions/$3|Bidrääg]]).',
'editcomment'      => "De Ännerkommentar weer: „''$1''“.",
'revertpage'       => 'Ännern vun [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskuschoon]]) rut un de Version vun [[User:$1]] wedderhaalt',
'rollback-success' => 'Ännern vun $1 trüchsett op letzte Version vun $2.',

# Edit tokens
'sessionfailure' => 'Dor weer en Problem mit diene Brukersitzung.
Disse Akschoon is nu ut Sekerheitsgrünn afbraken, dat de Ännern nich verkehrt en annern Bruker toornt warrt.
Gah een Sied trüch un versöök dat noch wedder.',

# Protect
'protectlogpage'              => 'Sietenschuul-Logbook',
'protectlogtext'              => 'Dit is en List vun de blockten Sieten. Kiek [[Special:ProtectedPages|Schulte Sieten]] för mehr Informatschonen.',
'protectedarticle'            => 'Siet $1 schuult',
'modifiedarticleprotection'   => 'Schuul op „[[$1]]“ sett',
'unprotectedarticle'          => 'Siet $1 freegeven',
'movedarticleprotection'      => 'hett de Schuulinstellungen vun „[[$2]]“ na „[[$1]]“ verschaven',
'protect-title'               => 'Sparren vun „$1“',
'prot_1movedto2'              => '[[$1]] is nu na [[$2]] verschaven.',
'protect-legend'              => 'Sparr bestätigen',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Löppt ut:',
'protect_expiry_invalid'      => 'Utlooptiet ungüllig',
'protect_expiry_old'          => 'Utlooptiet al vörbi.',
'protect-text'                => "Hier kannst du den Schuulstatus för de Siet '''<nowiki>$1</nowiki>''' ankieken un ännern.",
'protect-locked-blocked'      => "Du kannst den Schuulstatus vun de Sied nich ännern, du büst sperrt. Hier sünd de aktuellen Schuulstatus-Instellungen för de Siet '''„$1“:'''",
'protect-locked-dblock'       => "De Datenbank is sperrt un de Schuulstatus vun de Sied kann nich ännert warrn. Dit sünd de aktuellen Schuul-Instellungen för de Sied '''„$1“:'''",
'protect-locked-access'       => "Du hest nich de nödigen Rechten, den Schuulstatus vun de Siet to ännern. Dit sünd de aktuellen Instellungen för de Siet '''„$1“:'''",
'protect-cascadeon'           => 'Disse Siet is aktuell dör ene Kaskadensparr schuult. Se is in de nakamen {{PLURAL:$1|Siet|Sieden}} inbunnen, de dör Kaskadensparr schuult {{PLURAL:$1|is|sünd}}. De Schuulstatus kann för disse Siet ännert warrn, dat hett aver keen Effekt op de Kaskadensparr:',
'protect-default'             => 'all Brukers',
'protect-fallback'            => '„$1“-Rechten nödig.',
'protect-level-autoconfirmed' => 'Ne’e un nich registreerte Brukers blocken',
'protect-level-sysop'         => 'Blots Admins',
'protect-summary-cascade'     => 'Kaskadensparr',
'protect-expiring'            => 'bet $1 (UTC)',
'protect-expiry-indefinite'   => 'ahn Enn',
'protect-cascade'             => 'Kaskadensparr – in disse Siet inbunnene Vörlagen warrt ok schuult.',
'protect-cantedit'            => 'Du kannst de Sparr vun disse Siet nich ännern, du hest dor nich de nödigen Rechten för.',
'protect-othertime'           => 'Annere Tied:',
'protect-othertime-op'        => 'annere Tied',
'protect-existing-expiry'     => 'Aktuell Enn för de Sperr: $2, $3',
'protect-otherreason'         => 'Annern Grund:',
'protect-otherreason-op'      => 'annern Grund',
'protect-dropdown'            => '*Faken bruukt Schuulgrünn
** toveel Vandalismus
** toveel Spam
** Rin’e-Tuffeln/Rut’e-Tuffeln-Geänner
** veel bruukt Vörlaag
** Sied mit temlich veel Besökers',
'protect-edit-reasonlist'     => 'Grünn för de Sperr ännern',
'protect-expiry-options'      => '1 Stünn:1 hour,1 Dag:1 day,1 Week:1 week,2 Weken:2 weeks,1 Maand:1 month,3 Maand:3 months,6 Maand:6 months,1 Johr:1 year,ahn Enn:infinite',
'restriction-type'            => 'Schuulstatus',
'restriction-level'           => 'Schuulhööchd',
'minimum-size'                => 'Minimumgrött',
'maximum-size'                => 'Maximumgrött:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Ännern',
'restriction-move'   => 'Schuven',
'restriction-create' => 'Anleggen',
'restriction-upload' => 'Hoochladen',

# Restriction levels
'restriction-level-sysop'         => 'vull schuult',
'restriction-level-autoconfirmed' => 'deelwies schuult',
'restriction-level-all'           => 'all',

# Undelete
'undelete'                     => 'Wegsmetene Siet wedderhalen',
'undeletepage'                 => 'Wegsmetene Sieden wedderhalen',
'undeletepagetitle'            => "'''Dit sünd de wegsmetenen Versionen vun [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Wegsmetene Sieden ankieken',
'undeletepagetext'             => 'Disse {{PLURAL:$1|Sied is wegsmeten worrn, aver jümmer noch spiekert un kann|$1 Sieden sünd wegsmeten worrn, aver jümmer noch spiekert un köönt}} wedderhaalt warrn.
De Spieker mit wegsmeten Sieden warrt mööglicherwies aver vun Tied to Tied leddig maakt.',
'undelete-fieldset-title'      => 'Versionen wedderhalen',
'undeleteextrahelp'            => '* De Sied kumplett mit all Versionen weddertohalen, geev en Grund an, laat all Ankrüüzfeller leddig un klick op „{{int:Undeletebtn}}“.
* Wenn du blot welk Versionen wedderhalen wullt, denn wähl jem enkelt mit de Ankrüüzfeller ut, geev en Grund an un klick denn op „{{int:Undeletebtn}}“.
* „{{int:Undeletereset}}“ maakt dat Kommentarfeld un all Ankrüüzfeller bi de Versionen leddig.',
'undeleterevisions'            => '{{PLURAL:$1|Ene Version|$1 Versionen}} archiveert',
'undeletehistory'              => 'Wenn du disse Sied wedderhaalst, warrt ok all ole Versionen wedderhaalt. Wenn siet dat Löschen en nee Sied mit lieken
Naam schreven worrn is, warrt de wedderhaalten Versionen as ole Versionen vun disse Sied wiest.',
'undeleterevdel'               => 'Dat Wedderhalen geiht nich, wenn dor de ne’este Version vun de Sied oder Datei mit wegdaan warrt.
In den Fall mutt de ne’este Version op sichtbor stellt warrn.',
'undeletehistorynoadmin'       => 'Disse Sied is wegsmeten worrn.
De Grund för dat Wegsmieten steiht hier ünner, jüst so as Details to’n letzten Bruker, de disse Sied vör dat Wegsmieten ännert hett.
Den Text vun de wegsmetene Sied köönt blot Administraters sehn.',
'undelete-revision'            => 'Wegsmetene Version vun $1 - $4, $5, vun $3:',
'undeleterevision-missing'     => 'Version is ungüllig oder fehlt. Villicht weer de Lenk verkehrt oder de Version is wedderhaalt oder ut dat Archiv rutnahmen worrn.',
'undelete-nodiff'              => 'Gifft kene öllere Version.',
'undeletebtn'                  => 'Wedderhalen!',
'undeletelink'                 => 'ankieken/wedderhalen',
'undeleteviewlink'             => 'bekieken',
'undeletereset'                => 'Afbreken',
'undeleteinvert'               => 'Utwahl ümkehrn',
'undeletecomment'              => 'Grund:',
'undeletedarticle'             => '„$1“ wedderhaalt',
'undeletedrevisions'           => '{{PLURAL:$1|ene Version|$1 Versionen}} wedderhaalt',
'undeletedrevisions-files'     => '{{PLURAL:$1|Ene Version|$1 Versionen}} un {{PLURAL:$2|ene Datei|$2 Datein}} wedderhaalt',
'undeletedfiles'               => '{{PLURAL:$1|ene Datei|$1 Datein}} wedderhaalt',
'cannotundelete'               => 'Wedderhalen güng nich; en annern hett de Siet al wedderhaalt.',
'undeletedpage'                => "'''$1''' wedderhaalt.

In dat [[Special:Log/delete|Lösch-Logbook]] steiht en Översicht över de wegsmetenen un wedderhaalten Sieden.",
'undelete-header'              => 'Kiek in dat [[Special:Log/delete|Lösch-Logbook]] för Sieden, de nuletzt wegsmeten worrn sünd.',
'undelete-search-box'          => 'Wegsmetene Sieden söken',
'undelete-search-prefix'       => 'Wies Sieden, de anfangt mit:',
'undelete-search-submit'       => 'Söken',
'undelete-no-results'          => 'Kene Sieden in’t Archiv funnen, de passt.',
'undelete-filename-mismatch'   => 'De Dateiversion vun de Tied $1 kunn nich wedderhaalt warrn: De Dateinaams passt nich tohoop.',
'undelete-bad-store-key'       => 'De Dateiversion vun de Tied $1 kunn nich wedderhaalt warrn: De Datei weer al vör dat Wegsmieten nich mehr dor.',
'undelete-cleanup-error'       => 'Fehler bi dat Wegsmieten vun de nich bruukte Archiv-Version $1.',
'undelete-missing-filearchive' => 'De Datei mit de Archiv-ID $1 kunn nich wedderhaalt warrn. Dat gifft ehr gornich in de Datenbank. Villicht hett ehr al en annern wedderhaalt.',
'undelete-error-short'         => 'Fehler bi dat Wedderhalen vun de Datei $1',
'undelete-error-long'          => 'Fehlers bi dat Wedderhalen vun de Datei:

$1',
'undelete-show-file-confirm'   => 'Wullt du worraftig en wegsmeten Version vun de Datei „<nowiki>$1</nowiki>“ vun $2, $3 ankieken?',
'undelete-show-file-submit'    => 'Jo',

# Namespace form on various pages
'namespace'      => 'Naamruum:',
'invert'         => 'Utwahl ümkehren',
'blanknamespace' => '(Hööft-)',

# Contributions
'contributions'       => 'Bidrääg vun den Bruker',
'contributions-title' => 'Brukerbidrääg vun „$1“',
'mycontris'           => 'Mien Arbeid',
'contribsub2'         => 'För $1 ($2)',
'nocontribs'          => 'Kene Ännern för disse Kriterien funnen.',
'uctop'               => ' (aktuell)',
'month'               => 'bet Maand:',
'year'                => 'Bet Johr:',

'sp-contributions-newbies'       => 'Wies blot Bidrääg vun ne’e Brukers',
'sp-contributions-newbies-sub'   => 'För ne’e Kontos',
'sp-contributions-newbies-title' => 'Brukerbidrääg vun ne’e Brukers',
'sp-contributions-blocklog'      => 'Sparr-Logbook',
'sp-contributions-deleted'       => 'Wegsmetene Bidrääg vun’n Bruker',
'sp-contributions-logs'          => 'Logböker',
'sp-contributions-talk'          => 'Diskuschoon',
'sp-contributions-userrights'    => 'Brukerrechten inrichten',
'sp-contributions-search'        => 'Na Brukerbidrääg söken',
'sp-contributions-username'      => 'IP-Adress oder Brukernaam:',
'sp-contributions-submit'        => 'Söken',

# What links here
'whatlinkshere'            => 'Wat wiest na disse Siet hen',
'whatlinkshere-title'      => 'Sieden, de na „$1“ wiest',
'whatlinkshere-page'       => 'Siet:',
'linkshere'                => "Disse Sieden wiest na '''„[[:$1]]“''':",
'nolinkshere'              => "Kene Siet wiest na '''„[[:$1]]“'''.",
'nolinkshere-ns'           => "Kene Sieden wiest na '''[[:$1]]''' in’n utwählten Naamruum.",
'isredirect'               => 'Wiederleiden',
'istemplate'               => 'inbunnen dör Vörlaag',
'isimage'                  => 'Dateilenk',
'whatlinkshere-prev'       => '{{PLURAL:$1|vörige|vörige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nächste|nächste $1}}',
'whatlinkshere-links'      => '← Lenken',
'whatlinkshere-hideredirs' => 'Redirects $1',
'whatlinkshere-hidetrans'  => 'Vörlageninbinnungen $1',
'whatlinkshere-hidelinks'  => 'Lenken $1',
'whatlinkshere-hideimages' => 'Dateilenken $1',
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                         => 'IP-Adress blocken',
'blockip-legend'                  => 'Bruker blocken',
'blockiptext'                     => 'Bruuk dat Formular, ene IP-Adress to blocken.
Dit schall blots maakt warrn, Vandalismus to vermasseln, aver jümmer in Övereenstimmen mit uns [[{{MediaWiki:Policy-url}}|Leidlienen]].
Ok den Grund för dat Blocken indregen.',
'ipaddress'                       => 'IP-Adress',
'ipadressorusername'              => 'IP-Adress oder Brukernaam:',
'ipbexpiry'                       => 'Aflooptiet',
'ipbreason'                       => 'Grund:',
'ipbreasonotherlist'              => 'Annern Grund',
'ipbreason-dropdown'              => '* Allgemene Sperrgrünn
** Tofögen vun verkehrte Infos
** Leddigmaken vun Sieden
** Schrifft Tüdelkraam in Sieden
** Bedroht annere
** Brukernaam nich akzeptabel',
'ipbanononly'                     => 'Blot anonyme Brukers blocken',
'ipbcreateaccount'                => 'Anleggen vun Brukerkonto verhinnern',
'ipbemailban'                     => 'E-Mail schrieven sperren',
'ipbenableautoblock'              => 'Sperr de aktuell vun dissen Bruker bruukte IP-Adress un automaatsch all de annern, vun de ut he Sieden ännern oder Brukers anleggen will',
'ipbsubmit'                       => 'Adress blocken',
'ipbother'                        => 'Annere Tiet:',
'ipboptions'                      => '2 Stünnen:2 hours,1 Dag:1 day,3 Daag:3 days,1 Week:1 week,2 Weken:2 weeks,1 Maand:1 month,3 Maand:3 months,6 Maand:6 months,1 Johr:1 year,ahn Enn:infinite',
'ipbotheroption'                  => 'Annere Duer',
'ipbotherreason'                  => 'Annern Grund:',
'ipbhidename'                     => 'Brukernaam narms mehr wiesen',
'ipbwatchuser'                    => 'Op Brukersiet un Brukerdiskuschoon oppassen',
'ipballowusertalk'                => 'Den sperrten Bruker verlöven de egene Diskuschoonssied to ännern',
'ipb-change-block'                => 'Mit disse Sperrparameters noch wedder nee sperren',
'badipaddress'                    => 'De IP-Adress hett en falsch Format.',
'blockipsuccesssub'               => 'Blocken hett Spood',
'blockipsuccesstext'              => 'De IP-Adress „[[Special:Contributions/$1|$1]]“ is nu blockt.<br />
Op de [[Special:IPBlockList|IP-Blocklist]] is en List vun alle Blocks to finnen.',
'ipb-edit-dropdown'               => 'Blockgrünn ännern',
'ipb-unblock-addr'                => '„$1“ freegeven',
'ipb-unblock'                     => 'IP-Adress/Bruker freegeven',
'ipb-blocklist-addr'              => 'Aktuelle Sperren för „$1“',
'ipb-blocklist'                   => 'Aktuelle Sperren wiesen',
'ipb-blocklist-contribs'          => 'Brukerbidrääg för „$1“',
'unblockip'                       => 'IP-Adress freegeven',
'unblockiptext'                   => 'Bruuk dat Formular, üm en blockte IP-Adress freetogeven.',
'ipusubmit'                       => 'Disse Sperr opheven',
'unblocked'                       => '[[User:$1|$1]] freegeven',
'unblocked-id'                    => 'Sperr $1 freegeven',
'ipblocklist'                     => 'List vun blockte IP-Adressen un Brukernaams',
'ipblocklist-legend'              => 'Blockten Bruker finnen',
'ipblocklist-username'            => 'Brukernaam oder IP-Adress:',
'ipblocklist-sh-userblocks'       => 'Brukersperren $1',
'ipblocklist-sh-tempblocks'       => 'tiedwies Sperren $1',
'ipblocklist-sh-addressblocks'    => 'enkelt IP-Sperren $1',
'ipblocklist-submit'              => 'Söken',
'blocklistline'                   => '$1, $2 hett $3 blockt ($4)',
'infiniteblock'                   => 'unbegrenzt',
'expiringblock'                   => 'löppt $1 $2 af',
'anononlyblock'                   => 'blot Anonyme',
'noautoblockblock'                => 'Autoblock afstellt',
'createaccountblock'              => 'Brukerkonten opstellen sperrt',
'emailblock'                      => 'E-Mail schrieven sperrt',
'blocklist-nousertalk'            => 'kann de egene Diskuschoonssied nich ännern',
'ipblocklist-empty'               => 'De List is leddig.',
'ipblocklist-no-results'          => 'De söchte IP-Adress/Brukernaam is nich sperrt.',
'blocklink'                       => 'blocken',
'unblocklink'                     => 'freegeven',
'change-blocklink'                => 'Sperr ännern',
'contribslink'                    => 'Bidrääg',
'autoblocker'                     => 'Automatisch Block, vun wegen dat du en IP-Adress bruukst mit „$1“. Grund: „$2“.',
'blocklogpage'                    => 'Brukerblock-Logbook',
'blocklogentry'                   => 'block [[$1]] för en Tiedruum vun: $2 $3',
'reblock-logentry'                => 'hett de Sperr för „[[$1]]“ op de Tied $2 $3 ännert',
'blocklogtext'                    => 'Dit is en Logbook över Blocks un Freegaven vun Brukern. Automatisch blockte IP-Adressen sünd nich opföhrt.
Kiek [[Special:IPBlockList|IP-Blocklist]] för en List vun den blockten Brukern.',
'unblocklogentry'                 => 'Block vun $1 ophoven',
'block-log-flags-anononly'        => 'blots anonyme Brukers',
'block-log-flags-nocreate'        => 'Brukerkonten opstellen sperrt',
'block-log-flags-noautoblock'     => 'Autoblock utschalt',
'block-log-flags-noemail'         => 'E-Mail schrieven sperrt',
'block-log-flags-nousertalk'      => 'kann de egene Diskuschoonssied nich ännern',
'block-log-flags-angry-autoblock' => 'utwiedt Autoblock aktiv',
'block-log-flags-hiddenname'      => 'Brukernaam versteken',
'range_block_disabled'            => 'De Mööglichkeit, ganze Adressrüüm to sparren, is nich aktiveert.',
'ipb_expiry_invalid'              => 'De angeven Aflooptiet is nich güllig.',
'ipb_expiry_temp'                 => 'Versteken Brukernaam-Sperren schöölt duurhaft wesen.',
'ipb_hide_invalid'                => 'Dit Konto kunn nich versteken warrn, dat hett toveel Ännern maakt.',
'ipb_already_blocked'             => '„$1“ is al blockt.',
'ipb-needreblock'                 => '== Is al sperrt ==
„$1“ is al sperrt. Wullt du de Sperrparameters ännern?',
'ipb_cant_unblock'                => 'Fehler: Block-ID $1 nich funnen. De Sperr is villicht al wedder ophoven.',
'ipb_blocked_as_range'            => 'Fehler: De IP-Adress $1 is as Deel vun de IP-Reeg $2 indirekt sperrt worrn. De Sperr trüchnehmen för $1 alleen geiht nich.',
'ip_range_invalid'                => 'Ungüllig IP-Addressrebeet.',
'blockme'                         => 'Sperr mi',
'proxyblocker'                    => 'Proxyblocker',
'proxyblocker-disabled'           => 'Disse Funkschoon is afstellt.',
'proxyblockreason'                => 'Dien IP-Adress is blockt, vun wegen dat se en apenen Proxy is.
Kontakteer dien Provider oder diene Systemtechnik un informeer se över dat möögliche Sekerheitsproblem.',
'proxyblocksuccess'               => 'Trech.',
'sorbsreason'                     => 'Diene IP-Adress steiht in de DNSBL vun {{SITENAME}} as apen PROXY.',
'sorbs_create_account_reason'     => 'Diene IP-Adress steiht in de DNSBL vun {{SITENAME}} as apen PROXY. Du kannst keen Brukerkonto nee opstellen.',
'cant-block-while-blocked'        => 'Du kannst kene annern Brukers sperren, wenn du sülvst sperrt büst.',

# Developer tools
'lockdb'              => 'Datenbank sparren',
'unlockdb'            => 'Datenbank freegeven',
'lockdbtext'          => 'Mit de Sparr vun de Datenbank warrt alle Ännern an de Brukerinstellen, Oppasslisten, Sieten un so wieder verhinnert.
Schall de Datenbank redig sparrt warrn?',
'unlockdbtext'        => 'Dat Beennen vun de Datenbank-Sparr maakt alle Ännern weer mööglich.
Schall de Datenbank-Sparr redig beennt warrn?',
'lockconfirm'         => 'Ja, ik will de Datenbank sparren.',
'unlockconfirm'       => 'Ja, ik will de Datenbank freegeven.',
'lockbtn'             => 'Datenbank sparren',
'unlockbtn'           => 'Datenbank freegeven',
'locknoconfirm'       => 'Du hest dat Bestätigungsfeld nich markeert.',
'lockdbsuccesssub'    => 'Datenbanksparr hett Spood',
'unlockdbsuccesssub'  => 'Datenbankfreegaav hett Spood',
'lockdbsuccesstext'   => 'De {{SITENAME}}-Datenbank is sparrt.
<br />Du muttst de Datenbank weer freegeven, wenn de Pleegarbeiden beennt sünd.',
'unlockdbsuccesstext' => 'De {{SITENAME}}-Datenbank is weer freegeven.',
'lockfilenotwritable' => 'De Datenbank-Sperrdatei geiht nich to schrieven. För dat Sperren oder Freegeven vun de Datenbank mutt disse Datei för den Webserver to schrieven gahn.',
'databasenotlocked'   => 'De Datenbank is nich sparrt.',

# Move page
'move-page'                    => 'Schuuv „$1“',
'move-page-legend'             => 'Siet schuven',
'movepagetext'                 => "Mit dit Formular kannst du en Siet en ne’en Naam geven, tohoop mit all Versionen.
De ole Titel wiest denn achterna na den ne’en.
Verwiesen op den olen Titel köönt automaatsch ännert warrn.
Wenn du dat automaatsche Utbetern vun de Redirects nich utwählst, denn kiek na, wat dor kene [[Special:DoubleRedirects|dubbelten]] un [[Special:BrokenRedirects|kaputten Redirects]] nablifft.
Dat is dien Opgaav, optopassen, dat de Lenken all dorhen wiest, wo se hen wiesen schöölt.

De Siet warrt '''nich''' schaven, wenn dat al en Siet mit’n ne’en Naam gifft. Utnahmen vun disse Regel sünd blot leddige Sieden un Redirects, wenn disse Sieden kene öllern Versionen hebbt.
Dat bedüüdt, dat du ene jüst verschavene Siet na’n olen Titel trüchschuven kannst, wenn du en Fehler maakt hest, un dat du kene vörhannenen Sieden överschrieven kannst.

'''WOHRSCHAU!'''
Dit kann sik temlich dull utwarken bi veel bruukte Sieden. Stell seker, dat du weetst, wie sik dat utwarkt, ehrdat du wiedermaakst.",
'movepagetalktext'             => "De tohören Diskuschoonssiet warrt, wenn een dor is, mitverschaven, ''mit disse Utnahmen:''
* Du schuffst de Siet in en annern Naamruum oder
* dat gifft al en Diskuschoonssiet mit dissen Naam, oder
* du wählst de nerrn stahn Opschoon af

In disse Fäll musst du de Siet, wenn du dat willst, vun Hand schuven.",
'movearticle'                  => 'Siet schuven',
'movenologin'                  => 'Du büst nich anmellt',
'movenologintext'              => 'Du muttst en registreert Bruker un
[[Special:UserLogin|anmellt]] ween,
üm en Siet to schuven.',
'movenotallowed'               => 'Du hest nich de Rechten, Sieden to schuven.',
'movenotallowedfile'           => 'Du hest nich de Rechten, Datein to schuven.',
'cant-move-user-page'          => 'Du hest nich de Rechten, Brukersieden to schuven.',
'cant-move-to-user-page'       => 'Du hest nich de Rechten, Sieden op en Brukersied to schuven (mit Utnahm vun Brukerünnersieden).',
'newtitle'                     => 'To ne’en Titel',
'move-watch'                   => 'Op disse Siet oppassen',
'movepagebtn'                  => 'Siet schuven',
'pagemovedsub'                 => 'Schuven hett Spood',
'movepage-moved'               => "'''De Sied „$1“ is na „$2“ schaven worrn.'''",
'movepage-moved-redirect'      => 'Redirect opstellt.',
'movepage-moved-noredirect'    => 'Dat Opstellen vun en Redirect is ünnerdrückt worrn.',
'articleexists'                => 'Ünner dissen Naam gifft dat al ene Siet.
Bitte söök en annern Naam ut.',
'cantmove-titleprotected'      => 'Du kannst de Siet nich na dissen ne’en Naam schuven. De Naam is gegen dat nee Opstellen schuult.',
'talkexists'                   => 'Dat Schuven vun de Siet sülvst hett Spood, aver dat Schuven vun de
Diskuschoonssiet nich, vun wegen dat dat dor al ene Siet mit dissen Titel gifft. De Inholt mutt vun Hand anpasst warrn.',
'movedto'                      => 'schaven na',
'movetalk'                     => 'De Diskuschoonssiet ok schuven, wenn mööglich.',
'move-subpages'                => 'All Ünnersieden (bet to $1) mit schuven',
'move-talk-subpages'           => 'All Ünnersieden vun Diskuschoonssieden (bet to $1) mit schuven',
'movepage-page-exists'         => 'De Sied „$1“ gifft dat al un kann nich automaatsch överschreven warrn.',
'movepage-page-moved'          => 'De Siet „$1“ is nu schaven na „$2“.',
'movepage-page-unmoved'        => 'De Siet „$1“ kunn nich na „$2“ schaven warrn.',
'movepage-max-pages'           => 'De Maximaltall vun $1 {{PLURAL:$1|Siet|Sieden}} is schaven. All de annern Sieden warrt nich automaatsch schaven.',
'1movedto2'                    => '[[$1]] is nu na [[$2]] verschaven.',
'1movedto2_redir'              => '[[$1]] is nu na [[$2]] verschaven un hett den olen Redirect överschreven.',
'move-redirect-suppressed'     => 'Redirect ünnerdrückt',
'movelogpage'                  => 'Schuuv-Logbook',
'movelogpagetext'              => 'Dit is ene List vun all schavene Sieden.',
'movesubpage'                  => '{{PLURAL:$1|Ünnersiede|Ünnersieden}}',
'movesubpagetext'              => 'Disse Sied hett $1 {{PLURAL:$1|Ünnersied|Ünnersieden}}, de ünnen wiest warrt.',
'movenosubpage'                => 'Disse Sied hett keen Ünnersieden.',
'movereason'                   => 'Grund:',
'revertmove'                   => 'trüchschuven',
'delete_and_move'              => 'Wegsmieten un Schuven',
'delete_and_move_text'         => '== Siet gifft dat al, wegsmieten? ==

De Siet „[[:$1]]“ gifft dat al. Wullt du ehr wegsmieten, dat disse Siet schaven warrn kann?',
'delete_and_move_confirm'      => 'Jo, de Siet wegsmieten',
'delete_and_move_reason'       => 'wegsmeten, Platz to maken för Schuven',
'selfmove'                     => 'Utgangs- un Teelnaam sünd desülve; en Siet kann nich över sik sülvst röver schaven warrn.',
'immobile-source-namespace'    => 'Sieden in’n „$1“-Naamruum köönt nich schaven warrn',
'immobile-target-namespace'    => 'Sieden köönt nich in’n „$1“-Naamruum schaven warrn',
'immobile-target-namespace-iw' => 'Sieden köönt nich op en Interwiki-Lenk schaven warrn.',
'immobile-source-page'         => 'Disse Sied kann nich schaven warrn.',
'immobile-target-page'         => 'Na disse Sied kann nich schaven warrn.',
'imagenocrossnamespace'        => 'Datein köönt nich na buten den Datei-Naamruum schaven warrn',
'imagetypemismatch'            => 'De ne’e Dateiennen passt nich to de ole',
'imageinvalidfilename'         => 'De ne’e Dateinaam is ungüllig',
'fix-double-redirects'         => 'All Redirects, de na den olen Titel wiest, op den ne’en ännern',
'move-leave-redirect'          => 'Redirect opstellen',

# Export
'export'            => 'Sieden exporteren',
'exporttext'        => 'Du kannst den Text un de Historie vun een oder mehr Sieden na XML exporteren.
Dat Resultat kann över de [[Special:Import|Import-Sied]] in en anner Wiki mit MediaWiki-Software inspeelt warrn.

Sieden to exporteren, geev de Titels in dat Textfeld ünnen in, een per Reeg un wähl ut, of du de aktuelle un all de olen Versionen oder blot de aktuelle mit de Infos över den leste Version wullt.

Wenn du blot de aktuelle hebben wist, kannst du ok en Lenk bruken, to’n Bispeel [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] för de Sied [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Blots de aktuelle Version vun de Siet exporteern',
'exportnohistory'   => "----
'''Henwies:''' Exporteren vun hele Versionsgeschichten över dit Formular geiht nich, wegen de Performance.",
'export-submit'     => 'Export',
'export-addcattext' => 'Sieden ut Kategorie tofögen:',
'export-addcat'     => 'Tofögen',
'export-addnstext'  => 'Sieden ut Naamruum tofögen:',
'export-addns'      => 'Tofögen',
'export-download'   => 'As XML-Datei spiekern',
'export-templates'  => 'mit Vörlagen',
'export-pagelinks'  => 'Sieden op de Lenken wiest, automaatsch mit exporteren, bet to en Deepd vun:',

# Namespace 8 related
'allmessages'               => 'Alle Systemnarichten',
'allmessagesname'           => 'Naam',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Text nu',
'allmessagestext'           => 'Dit is de List vun de Systemnarichten, de dat in den MediaWiki-Naamruum gifft.',
'allmessagesnotsupportedDB' => '{{ns:special}}:Allmessages is nich ünnerstütt, vun wegen dat wgUseDatabaseMessages utstellt is.',

# Thumbnails
'thumbnail-more'           => 'grötter maken',
'filemissing'              => 'Datei fehlt',
'thumbnail_error'          => 'Fehler bi dat Maken vun’t Duumnagel-Bild: $1',
'djvu_page_error'          => 'DjVu-Siet buten de verföögboren Sieden',
'djvu_no_xml'              => 'kunn de XML-Daten för de DjVu-Datei nich afropen',
'thumbnail_invalid_params' => 'Duumnagelbild-Parameter passt nich',
'thumbnail_dest_directory' => 'Kann Zielorner nich anleggen',
'thumbnail_image-type'     => 'Bildtyp nich ünnerstütt',
'thumbnail_gd-library'     => 'Unvullstännige Instellungen vun de GD-Bibliothek: Funkschoon $1 fehlt',
'thumbnail_image-missing'  => 'Süht ut as wenn de Datei fehlt: $1',

# Special:Import
'import'                     => 'Import vun Sieden',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-text'      => 'Wähl en Wiki un en Siet för dat Importeren ut.
De Versionsdaten un Brukernaams blievt dor bi vörhannen.
All Transwiki-Import-Akschonen staht later ok in dat [[Special:Log/import|Import-Logbook]].',
'import-interwiki-source'    => 'Bornwiki/sied:',
'import-interwiki-history'   => 'Importeer all Versionen vun disse Siet',
'import-interwiki-templates' => 'All Vörlagen inslaten',
'import-interwiki-submit'    => 'Rinhalen',
'import-interwiki-namespace' => 'Sied in dissen Naamruum halen:',
'import-upload-filename'     => 'Dateinaam:',
'import-comment'             => 'Kommentar:',
'importtext'                 => 'Exporteer de Siet vun dat Utgangswiki mit Special:Export un laad de Datei denn över disse Siet weer hooch.',
'importstart'                => 'Sieden warrt rinhaalt...',
'import-revision-count'      => '$1 {{PLURAL:$1|Version|Versionen}}',
'importnopages'              => 'Gifft kene Sieden to’n Rinhalen.',
'importfailed'               => 'Import hett kenen Spood: $1',
'importunknownsource'        => 'Unbekannten Typ för den Importborn',
'importcantopen'             => 'Kunn de Import-Datei nich apen maken',
'importbadinterwiki'         => 'Verkehrt Interwiki-Lenk',
'importnotext'               => 'Leddig oder keen Text',
'importsuccess'              => 'Import hett Spood!',
'importhistoryconflict'      => 'Dor sünd al öllere Versionen, de mit dissen kollideert. (Mööglicherwies is de Siet al vörher importeert worrn)',
'importnosources'            => 'För den Transwiki-Import sünd noch keen Bornen fastleggt. Dat direkte Hoochladen vun Versionen is sperrt.',
'importnofile'               => 'Kene Import-Datei hoochladen.',
'importuploaderrorsize'      => 'Hoochladen vun de Importdatei güng nich. De Datei is grötter as de verlöövte Maximumgrött för Datein.',
'importuploaderrorpartial'   => 'Hoochladen vun de Importdatei güng nich. De Datei weer blot to’n Deel hoochlaadt.',
'importuploaderrortemp'      => 'Hoochladen vun de Importdatei güng nich. En temporär Mapp fehlt.',
'import-parse-failure'       => 'Fehler bi’n XML-Import:',
'import-noarticle'           => 'Kene Siet to’n Rinhalen angeven!',
'import-nonewrevisions'      => 'Gifft kene ne’en Versionen to importeren, all Versionen sünd al vördem importeert worrn.',
'xml-error-string'           => '$1 Reeg $2, Spalt $3, (Byte $4): $5',
'import-upload'              => 'XML-Daten hoochladen',
'import-token-mismatch'      => 'Session-Daten sünd verloren gahn. Versöök dat noch wedder.',
'import-invalid-interwiki'   => 'Ut dat angevene Wiki is en Import nich mööglich.',

# Import log
'importlogpage'                    => 'Import-Logbook',
'importlogpagetext'                => 'Administrativen Import vun Sieden mit Versionsgeschicht vun annere Wikis.',
'import-logentry-upload'           => 'hett „[[$1]]“ ut Datei importeert',
'import-logentry-upload-detail'    => '{{PLURAL:$1|ene Version|$1 Versionen}}',
'import-logentry-interwiki'        => 'hett „$1“ importeert (Transwiki)',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|ene Version|$1 Versionen}} vun $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Dien Brukersied',
'tooltip-pt-anonuserpage'         => 'De Brukersiet för de IP-Adress ünner de du schriffst',
'tooltip-pt-mytalk'               => 'Dien Diskuschoonssied',
'tooltip-pt-anontalk'             => 'Diskuschoon över Ännern vun disse IP-Adress',
'tooltip-pt-preferences'          => 'Mien Instellen',
'tooltip-pt-watchlist'            => 'Mien Oppasslist',
'tooltip-pt-mycontris'            => 'List vun dien Bidrääg',
'tooltip-pt-login'                => 'Du kannst di geern anmellen, dat is aver nich nödig, dat du Sieden ännern kannst.',
'tooltip-pt-anonlogin'            => 'Du kannst di geern anmellen, dat is aver nich nödig, dat du Sieden ännern kannst.',
'tooltip-pt-logout'               => 'Afmellen',
'tooltip-ca-talk'                 => 'Diskuschoon över disse Siet',
'tooltip-ca-edit'                 => 'Du kannst disse Siet ännern. Bruuk dat vör dat Spiekern.',
'tooltip-ca-addsection'           => 'En Afsnidd tofögen',
'tooltip-ca-viewsource'           => 'Disse Siet is schuult. Du kannst den Borntext ankieken.',
'tooltip-ca-history'              => 'Historie vun disse Siet.',
'tooltip-ca-protect'              => 'Disse Siet schulen',
'tooltip-ca-delete'               => 'Disse Siet löschen',
'tooltip-ca-undelete'             => 'Weerholen vun de Siet, so as se vör dat löschen ween is',
'tooltip-ca-move'                 => 'Disse Siet schuven',
'tooltip-ca-watch'                => 'Disse Siet to de Oppasslist hentofögen',
'tooltip-ca-unwatch'              => 'Disse Siet vun de Oppasslist löschen',
'tooltip-search'                  => 'Söken in dit Wiki',
'tooltip-search-go'               => 'Gah na de Siet, de jüst dissen Naam driggt, wenn dat een gifft.',
'tooltip-search-fulltext'         => 'Söök na Sieden, op de disse Text steiht',
'tooltip-p-logo'                  => 'Hööftsiet',
'tooltip-n-mainpage'              => 'Besöök de Hööftsiet',
'tooltip-n-portal'                => 'över dat Projekt, wat du doon kannst, woans du de Saken finnen kannst',
'tooltip-n-currentevents'         => 'Achtergrünn to aktuellen Schehn finnen',
'tooltip-n-recentchanges'         => 'Wat in dit Wiki toletzt ännert worrn is.',
'tooltip-n-randompage'            => 'Tofällige Siet',
'tooltip-n-help'                  => 'Hier kriegst du Hülp.',
'tooltip-t-whatlinkshere'         => 'Wat wiest hierher',
'tooltip-t-recentchangeslinked'   => 'Verlinkte Sieden',
'tooltip-feed-rss'                => 'RSS-Feed för disse Siet',
'tooltip-feed-atom'               => 'Atom-Feed för disse Siet',
'tooltip-t-contributions'         => 'List vun de Bidreeg vun dissen Bruker',
'tooltip-t-emailuser'             => 'Dissen Bruker en E-Mail tostüren',
'tooltip-t-upload'                => 'Biller oder Mediendatein hoochladen',
'tooltip-t-specialpages'          => 'List vun alle Spezialsieden',
'tooltip-t-print'                 => 'Druckversion vun disse Siet',
'tooltip-t-permalink'             => 'Permanentlenk na disse Version vun de Siet',
'tooltip-ca-nstab-main'           => 'Siet ankieken',
'tooltip-ca-nstab-user'           => 'Brukersiet ankieken',
'tooltip-ca-nstab-media'          => 'Mediensiet ankieken',
'tooltip-ca-nstab-special'        => 'Dit is en Spezialsiet, du kannst disse Siet nich ännern.',
'tooltip-ca-nstab-project'        => 'Portalsiet ankieken',
'tooltip-ca-nstab-image'          => 'Bildsiet ankieken',
'tooltip-ca-nstab-mediawiki'      => 'Systemnarichten ankieken',
'tooltip-ca-nstab-template'       => 'Vörlaag ankieken',
'tooltip-ca-nstab-help'           => 'Hülpsiet ankieken',
'tooltip-ca-nstab-category'       => 'Kategoriesiet ankieken',
'tooltip-minoredit'               => 'Dit as en lütt Ännern markeern',
'tooltip-save'                    => 'Sekern, wat du ännert hest',
'tooltip-preview'                 => 'Vörschau för dien Ännern, bruuk dat vör dat Spiekern.',
'tooltip-diff'                    => 'Den Ünnerscheed to vörher ankieken.',
'tooltip-compareselectedversions' => 'De Ünnerscheed twüschen de twee wählten Versionen vun disse Siet ankieken.',
'tooltip-watch'                   => 'Op disse Siet oppassen.',
'tooltip-recreate'                => 'Siet wedder nee anleggen, ok wenn se wegsmeten worrn is',
'tooltip-upload'                  => 'Hoochladen',
'tooltip-rollback'                => 'Rullt all Ännern vun’n letzten Bruker an disse Sied mit een Klick trüch.',
'tooltip-undo'                    => 'Rullt dit een Ännern trüch un wiest den Text in de Vörschau, dat en Grund för’t Ännern angeven warrn kann.',

# Stylesheets
'common.css'   => '/** CSS-Kood hier binnen warrt för all Stilvörlagen (Skins) inbunnen */',
'monobook.css' => '/* disse Datei ännern üm de Monobook-Stilvörlaag för de ganze Siet antopassen */',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'notacceptable'     => 'Dat Wiki-Server kann kene Daten in enen Format levern, dat dien Klient lesen kann.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonym Bruker|Anonyme Brukers}} vun {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Bruker $1',
'lastmodifiedatby' => 'Disse Siet weer dat letzte Maal $2, $1 vun $3 ännert.',
'othercontribs'    => 'Grünnt op Arbeid vun $1.',
'others'           => 'annere',
'siteusers'        => '{{SITENAME}}-{{PLURAL:$2|Bruker|Brukers}} $1',
'creditspage'      => 'Sieten-Autoren',
'nocredits'        => 'Dor is keen Autorenlist för disse Siet verfögbor.',

# Spam protection
'spamprotectiontitle' => 'Spamschild',
'spamprotectiontext'  => 'De Sied, de du spiekern wullst, is vun’n Spamschild blockt worrn. Dat kann vun en Link na en externe Sied kamen.',
'spamprotectionmatch' => 'Dit Text hett den Spamschild utlöst: $1',
'spambot_username'    => 'MediaWiki Spam-Oprümen',
'spam_reverting'      => 'Trüchdreiht na de letzte Version ahn Lenken na $1.',
'spam_blanking'       => 'All Versionen harrn Lenken na $1, rein maakt.',

# Info page
'infosubtitle'   => 'Informatschonen för de Siet',
'numedits'       => 'Antall vun Ännern (Siet): $1',
'numtalkedits'   => 'Antall vun Ännern (Diskuschoonssiet): $1',
'numwatchers'    => 'Antall vun Oppassers: $1',
'numauthors'     => 'Antall vun verschedene Autoren (Siet): $1',
'numtalkauthors' => 'Antall vun verschedene Autoren (Diskuschoonssiet): $1',

# Skin names
'skinname-standard'    => 'Klassik',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Kölsch Blau',
'skinname-chick'       => 'Küken',

# Math options
'mw_math_png'    => 'Jümmer as PNG dorstellen',
'mw_math_simple' => 'Eenfach TeX as HTML dorstellen, sünst PNG',
'mw_math_html'   => 'Wenn mööglich as HTML dorstellen, sünst PNG',
'mw_math_source' => 'As TeX laten (för Textbrowser)',
'mw_math_modern' => 'Anratenswert för moderne Browser',
'mw_math_mathml' => 'MathML (experimentell)',

# Math errors
'math_failure'          => 'Parser-Fehler',
'math_unknown_error'    => 'Unbekannten Fehler',
'math_unknown_function' => 'Unbekannte Funktschoon',
'math_lexing_error'     => "'Lexing'-Fehler",
'math_syntax_error'     => 'Syntaxfehler',
'math_image_error'      => 'dat Konverteren na PNG harr keen Spood.',
'math_bad_tmpdir'       => 'Kann dat Temporärverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_bad_output'       => 'Kann dat Teelverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_notexvc'          => 'Dat texvc-Programm kann nich funnen warrn. Kiek ok math/README.',

# Patrolling
'markaspatrolleddiff'                 => 'As nakeken marken',
'markaspatrolledtext'                 => 'Disse Siet as nakeken marken',
'markedaspatrolled'                   => 'As nakeken marken',
'markedaspatrolledtext'               => 'Disse Version is as nakeken markt.',
'rcpatroldisabled'                    => 'Nakieken vun Letzte Ännern nich anstellt',
'rcpatroldisabledtext'                => 'Dat Nakieken vun de Letzten Ännern is in’n Momang nich anstellt.',
'markedaspatrollederror'              => 'As nakeken marken klappt nich',
'markedaspatrollederrortext'          => 'Du musst ene Version angeven, dat du de as nakeken marken kannst.',
'markedaspatrollederror-noautopatrol' => 'Du kannst de Saken, de du sülvst ännert hest, nich as nakeken marken.',

# Patrol log
'patrol-log-page'      => 'Nakiek-Logbook',
'patrol-log-header'    => 'Dit is dat Patrolleer-Logbook.',
'patrol-log-line'      => '$1 vun $2 as nakeken markt $3',
'patrol-log-auto'      => '(automaatsch)',
'patrol-log-diff'      => 'Version $1',
'log-show-hide-patrol' => 'Nakiek-Logbook $1',

# Image deletion
'deletedrevision'                 => 'Löschte ole Version $1',
'filedeleteerror-short'           => 'Fehler bi dat Wegsmieten vun de Datei: $1',
'filedeleteerror-long'            => 'Dat geev Fehlers bi dat Wegsmieten vun de Datei:

$1',
'filedelete-missing'              => 'De Datei „$1“ kann nich wegsmeten warrn, de gifft dat gornich.',
'filedelete-old-unregistered'     => 'De angevene Datei-Version „$1“ is nich in de Datenbank.',
'filedelete-current-unregistered' => 'De angevene Datei „$1“ is nich in de Datenbank.',
'filedelete-archive-read-only'    => 'De Archiv-Mapp „$1“ geiht för den Webserver nich to schrieven.',

# Browsing diffs
'previousdiff' => '← Gah to den vörigen Ünnerscheed',
'nextdiff'     => 'Gah to den tokamen Ünnerscheed →',

# Media information
'mediawarning'         => "'''Wohrschau:''' Disse Soort Datein kann bööswilligen Programmkood bargen. Dör dat Rünnerladen un Opmaken vun de Datei kann dien Reekner Schaden nehmen.",
'imagemaxsize'         => 'Biller op de Bildsied begrenzen op:',
'thumbsize'            => 'Grött vun dat Duumnagel-Bild:',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|Ene Siet|$3 Sieden}}',
'file-info'            => '(Grött: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Grött: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Gifft dat Bild nich grötter.</small>',
'svg-long-desc'        => '(SVG-Datei, Utgangsgrött: $1 × $2 Pixel, Dateigrött: $3)',
'show-big-image'       => 'Dat Bild wat grötter',
'show-big-image-thumb' => '<small>Grött vun disse Vörschau: $1 × $2 Pixels</small>',

# Special:NewFiles
'newimages'             => 'Ne’e Biller',
'imagelisttext'         => 'Hier is en List vun {{PLURAL:$1|een Bild|$1 Biller}}, sorteert $2.',
'newimages-summary'     => 'Disse Spezialsiet wiest de Datein, de toletzt hoochladen worrn sünd.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Dateinaam (oder Deel dorvun):',
'showhidebots'          => '($1 Bots)',
'noimages'              => 'Kene Biller.',
'ilsubmit'              => 'Söken',
'bydate'                => 'na Datum',
'sp-newimages-showfrom' => 'Wies ne’e Datein vun $1, $2, af an',

# Bad image list
'bad_image_list' => 'Format:

Blot na Regen, de mit en * anfangt, warrt keken. Na dat Teken * mutt toeerst en Lenk op dat Bild stahn, dat nich bruukt warrn dröff.
Wat denn noch an Lenken kummt in de Reeg, dat sünd Utnahmen, bi de dat Bild liekers noch bruukt warrn dröff.',

# Metadata
'metadata'          => 'Metadaten',
'metadata-help'     => 'Disse Datei bargt noch mehr Informatschonen, de mehrsttiets vun de Digitalkamera oder den Scanner kaamt. Dör Afännern vun de Originaldatei köönt welk Details nich mehr ganz to dat Bild passen.',
'metadata-expand'   => 'Wies mehr Details',
'metadata-collapse' => 'Wies minner Details',
'metadata-fields'   => 'De Feller vun de EXIF-Metadaten, de hier indragen sünd, warrt op Bildsieden glieks wiest. De annern Feller sünd versteken.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Breed',
'exif-imagelength'                 => 'Hööchd',
'exif-bitspersample'               => 'Bits je Farvkomponent',
'exif-compression'                 => 'Oort vun de Kompression',
'exif-photometricinterpretation'   => 'Pixeltohoopsetzung',
'exif-orientation'                 => 'Utrichtung',
'exif-samplesperpixel'             => 'Komponententall',
'exif-planarconfiguration'         => 'Datenutrichtung',
'exif-ycbcrsubsampling'            => 'Subsampling-Rate vun Y bet C',
'exif-ycbcrpositioning'            => 'Y un C Positionerung',
'exif-xresolution'                 => 'Oplösen in de Breed',
'exif-yresolution'                 => 'Oplösen in de Hööchd',
'exif-resolutionunit'              => 'Eenheit vun de Oplösen',
'exif-stripoffsets'                => 'Bilddaten-Versatz',
'exif-rowsperstrip'                => 'Tall Regen je Striepen',
'exif-stripbytecounts'             => 'Bytes je kumprimeert Striepen',
'exif-jpeginterchangeformat'       => 'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Grött vun de JPEG-Daten in Bytes',
'exif-transferfunction'            => 'Transferfunkschoon',
'exif-whitepoint'                  => 'Wittpunkt-Chromatizität',
'exif-primarychromaticities'       => 'Chromatizität vun de Grundfarven',
'exif-ycbcrcoefficients'           => 'YCbCr-Koeffizienten',
'exif-referenceblackwhite'         => 'Swart/Witt-Referenzpunkten',
'exif-datetime'                    => 'Spiekertiet',
'exif-imagedescription'            => 'Bildtitel',
'exif-make'                        => 'Kamera-Hersteller',
'exif-model'                       => 'Kameramodell',
'exif-software'                    => 'bruukte Software',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Oorheverrechten',
'exif-exifversion'                 => 'Exif-Version',
'exif-flashpixversion'             => 'ünnerstütt Flashpix-Version',
'exif-colorspace'                  => 'Farvruum',
'exif-componentsconfiguration'     => 'Bedüden vun elk Kumponent',
'exif-compressedbitsperpixel'      => 'Komprimeerte Bits je Pixel',
'exif-pixelydimension'             => 'Gellen Bildbreed',
'exif-pixelxdimension'             => 'Gellen Bildhööchd',
'exif-makernote'                   => 'Herstellernotiz',
'exif-usercomment'                 => 'Brukerkommentar',
'exif-relatedsoundfile'            => 'Tohörige Toondatei',
'exif-datetimeoriginal'            => 'Tiet vun de Opnahm',
'exif-datetimedigitized'           => 'Tiet vun dat digital Maken',
'exif-subsectime'                  => 'Spiekertiet (1/100 s)',
'exif-subsectimeoriginal'          => 'Tiet vun de Opnahm (1/100 s)',
'exif-subsectimedigitized'         => 'Tiet digital maakt (1/100 s)',
'exif-exposuretime'                => 'Belichtungstiet',
'exif-exposuretime-format'         => '$1 Sek. ($2)',
'exif-fnumber'                     => 'F-Nummer',
'exif-exposureprogram'             => 'Belichtungsprogramm',
'exif-spectralsensitivity'         => 'Spektralsensitivität',
'exif-isospeedratings'             => 'Film- oder Sensorempfindlichkeit (ISO)',
'exif-oecf'                        => 'Optoelektroonsch Ümrekenfaktor',
'exif-shutterspeedvalue'           => 'Belichttiet',
'exif-aperturevalue'               => 'Blennweert',
'exif-brightnessvalue'             => 'Helligkeit',
'exif-exposurebiasvalue'           => 'Belichtungsvörgaav',
'exif-maxaperturevalue'            => 'Gröttste Blenn',
'exif-subjectdistance'             => 'wo wied weg',
'exif-meteringmode'                => 'Meetmethood',
'exif-lightsource'                 => 'Lichtborn',
'exif-flash'                       => 'Blitz',
'exif-focallength'                 => 'Brennwied',
'exif-subjectarea'                 => 'Hauptmotivpositschoon un -grött',
'exif-flashenergy'                 => 'Blitzstärk',
'exif-spatialfrequencyresponse'    => 'Ruumfrequenz-Reakschoon',
'exif-focalplanexresolution'       => 'X-Oplösung Brennpunktflach',
'exif-focalplaneyresolution'       => 'Y-Oplösung Brennpunktflach',
'exif-focalplaneresolutionunit'    => 'Eenheit vun de Oplösung Brennpunktflach',
'exif-subjectlocation'             => 'Oort vun dat Motiv',
'exif-exposureindex'               => 'Belichtungsindex',
'exif-sensingmethod'               => 'Meetmethood',
'exif-filesource'                  => 'Dateiborn',
'exif-scenetype'                   => 'Szenentyp',
'exif-cfapattern'                  => 'CFA-Munster',
'exif-customrendered'              => 'Anpasst Bildverarbeidung',
'exif-exposuremode'                => 'Belichtungsmodus',
'exif-whitebalance'                => 'Wittutgliek',
'exif-digitalzoomratio'            => 'Digitalzoom',
'exif-focallengthin35mmfilm'       => 'Brennwied (Kleenbildäquivalent)',
'exif-scenecapturetype'            => 'Opnahmoort',
'exif-gaincontrol'                 => 'Verstärkung',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Sättigung',
'exif-sharpness'                   => 'Schärp',
'exif-devicesettingdescription'    => 'Apparatinstellung',
'exif-subjectdistancerange'        => 'Motivafstand',
'exif-imageuniqueid'               => 'Bild-ID',
'exif-gpsversionid'                => 'GPS-Tag-Version',
'exif-gpslatituderef'              => 'Bredengraad Noord oder Süüd',
'exif-gpslatitude'                 => 'Breed',
'exif-gpslongituderef'             => 'Längengraad Oost oder West',
'exif-gpslongitude'                => 'Läng',
'exif-gpsaltituderef'              => 'Betogshööchd',
'exif-gpsaltitude'                 => 'Hööch',
'exif-gpstimestamp'                => 'GPS-Tiet (Atomklock)',
'exif-gpssatellites'               => 'För dat Meten bruukte Satelliten',
'exif-gpsstatus'                   => 'Empfängerstatus',
'exif-gpsmeasuremode'              => 'Meetverfohren',
'exif-gpsdop'                      => 'Meetnauigkeit',
'exif-gpsspeedref'                 => 'Tempo-Eenheit',
'exif-gpsspeed'                    => 'Tempo vun’n GPS-Empfänger',
'exif-gpstrackref'                 => 'Referenz för Bewegungsrichtung',
'exif-gpstrack'                    => 'Bewegungsrichtung',
'exif-gpsimgdirectionref'          => 'Referenz för de Utrichtung vun dat Bild',
'exif-gpsimgdirection'             => 'Bildrichtung',
'exif-gpsmapdatum'                 => 'Geodäätsch Datum bruukt',
'exif-gpsdestlatituderef'          => 'Referenz för den Bredengraad',
'exif-gpsdestlatitude'             => 'Bredengraad',
'exif-gpsdestlongituderef'         => 'Referenz för den Längengraad',
'exif-gpsdestlongitude'            => 'Längengraad',
'exif-gpsdestbearingref'           => 'Referenz för Motivrichtung',
'exif-gpsdestbearing'              => 'Motivrichtung',
'exif-gpsdestdistanceref'          => 'Referenz för den Afstand to’t Motiv',
'exif-gpsdestdistance'             => 'wo wied af vun dat Motiv',
'exif-gpsprocessingmethod'         => 'Naam vun dat GPS-Verfohren',
'exif-gpsareainformation'          => 'Naam vun dat GPS-Rebeet',
'exif-gpsdatestamp'                => 'GPS-Datum',
'exif-gpsdifferential'             => 'GPS-Differentialkorrektur',

# EXIF attributes
'exif-compression-1' => 'Unkomprimeert',

'exif-unknowndate' => 'Unbekannt Datum',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'waagrecht kippt',
'exif-orientation-3' => '180° dreiht',
'exif-orientation-4' => 'Vertikal kippt',
'exif-orientation-5' => '90° gegen de Klock dreiht un vertikal kippt',
'exif-orientation-6' => '90° mit de Klock dreiht',
'exif-orientation-7' => '90° mit de Klock dreiht un vertikal kippt',
'exif-orientation-8' => '90° gegen de Klock dreiht',

'exif-planarconfiguration-1' => 'Groffformat',
'exif-planarconfiguration-2' => 'Planarformat',

'exif-componentsconfiguration-0' => 'gifft dat nich',

'exif-exposureprogram-0' => 'Unbekannt',
'exif-exposureprogram-1' => 'vun Hand',
'exif-exposureprogram-2' => 'Standardprogramm',
'exif-exposureprogram-3' => 'Tietautomatik',
'exif-exposureprogram-4' => 'Blennenautomatik',
'exif-exposureprogram-5' => 'Kreativprogramm mit mehr hoge Schärpendeep',
'exif-exposureprogram-6' => 'Action-Programm mit mehr korte Belichtungstiet',
'exif-exposureprogram-7' => 'Porträt-Programm (för Biller vun dicht ahn Fokus op’n Achtergrund)',
'exif-exposureprogram-8' => 'Landschopsopnahm (mit Fokus op Achtergrund)',

'exif-subjectdistance-value' => '$1 Meter',

'exif-meteringmode-0'   => 'Unbekannt',
'exif-meteringmode-1'   => 'Dörsnittlich',
'exif-meteringmode-2'   => 'Middzentreert',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Munster',
'exif-meteringmode-6'   => 'Bilddeel',
'exif-meteringmode-255' => 'Unbekannt',

'exif-lightsource-0'   => 'unbekannt',
'exif-lightsource-1'   => 'Daglicht',
'exif-lightsource-2'   => 'Fluoreszent',
'exif-lightsource-3'   => 'Glöhlamp',
'exif-lightsource-4'   => 'Blitz',
'exif-lightsource-9'   => 'Good Weder',
'exif-lightsource-10'  => 'Wulkig',
'exif-lightsource-11'  => 'Schatten',
'exif-lightsource-12'  => 'Daglicht fluoreszeren (D 5700–7100 K)',
'exif-lightsource-13'  => 'Dagwitt fluoreszeren (N 4600–5400 K)',
'exif-lightsource-14'  => 'Köhlwitt fluoreszeren (W 3900–4500 K)',
'exif-lightsource-15'  => 'Witt fluoreszeren (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Standardlicht A',
'exif-lightsource-18'  => 'Standardlicht B',
'exif-lightsource-19'  => 'Standardlicht C',
'exif-lightsource-24'  => 'ISO Studio Kunstlicht',
'exif-lightsource-255' => 'Annern Lichtborn',

# Flash modes
'exif-flash-fired-0'    => 'keen Blitz',
'exif-flash-fired-1'    => 'Blitz utlööst',
'exif-flash-return-0'   => 'Blitz mellt nix trüch',
'exif-flash-return-2'   => 'keen Reflexion vun’n Blitz faststellt',
'exif-flash-return-3'   => 'Reflexion vun’n Blitz faststellt',
'exif-flash-mode-1'     => 'twungen Blitz',
'exif-flash-mode-2'     => 'Blitz utschalt',
'exif-flash-mode-3'     => 'Auto',
'exif-flash-function-1' => 'Keen Blitzfunkschoon',
'exif-flash-redeye-1'   => 'Rode-Ogen-Filter',

'exif-focalplaneresolutionunit-2' => 'Toll',

'exif-sensingmethod-1' => 'Undefineert',
'exif-sensingmethod-2' => 'Een-Chip-Farvsensor',
'exif-sensingmethod-3' => 'Twee-Chip-Farvsensor',
'exif-sensingmethod-4' => 'Dree-Chip-Farvsensor',
'exif-sensingmethod-5' => 'Rebeedssensor (een Klöör na de annere)',
'exif-sensingmethod-7' => 'Trilinear Sensor',
'exif-sensingmethod-8' => 'Linearsensor (een Klöör na de annere)',

'exif-scenetype-1' => 'Normal',

'exif-customrendered-0' => 'Standard',
'exif-customrendered-1' => 'Anpasst',

'exif-exposuremode-0' => 'Automaatsch Belichtung',
'exif-exposuremode-1' => 'Belichtung vun Hand',
'exif-exposuremode-2' => 'Belichtungsreeg',

'exif-whitebalance-0' => 'Automaatsch Wittutgliek',
'exif-whitebalance-1' => 'Wittutgliek vun Hand',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landschop',
'exif-scenecapturetype-2' => 'Porträt',
'exif-scenecapturetype-3' => 'Nacht',

'exif-gaincontrol-0' => 'Keen',
'exif-gaincontrol-1' => 'beten heller',
'exif-gaincontrol-2' => 'düüdlich heller',
'exif-gaincontrol-3' => 'beten minner hell',
'exif-gaincontrol-4' => 'düüdlich minner hell',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Wiek',
'exif-contrast-2' => 'Hart',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Sied',
'exif-saturation-2' => 'Hooch',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Wiek',
'exif-sharpness-2' => 'Hart',

'exif-subjectdistancerange-0' => 'unbekannt',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nahopnahm',
'exif-subjectdistancerange-3' => 'Feernopnahm',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Breed Noord',
'exif-gpslatitude-s' => 'Breed Süüd',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Läng Oost',
'exif-gpslongitude-w' => 'Läng West',

'exif-gpsstatus-a' => 'Meten löppt',
'exif-gpsstatus-v' => 'Meetinteroperabilität',

'exif-gpsmeasuremode-2' => '2-dimensional meet',
'exif-gpsmeasuremode-3' => '3-dimensional meet',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometers in’e Stünn',
'exif-gpsspeed-m' => 'Mielen in’e Stünn',
'exif-gpsspeed-n' => 'Knoten',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Wohre Richtung',
'exif-gpsdirection-m' => 'Magneetsch Richtung',

# External editor support
'edit-externally'      => 'Änner disse Datei mit en extern Programm',
'edit-externally-help' => '(Lees de [http://www.mediawiki.org/wiki/Manual:External_editors Installatschoonshelp] wenn du dor mehr to weten wullt)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'all',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',

# E-mail address confirmation
'confirmemail'             => 'Nettbreefadress bestätigen',
'confirmemail_noemail'     => 'Du hest kene bestätigte Nettbreefadress in diene [[Special:Preferences|Instellen]] angeven.',
'confirmemail_text'        => '{{SITENAME}} verlangt, dat du diene Nettbreefadress bestätigst, ehrder du de Nettbreeffunkschonen bruken kannst. Klick op den Knopp wieder ünnen, dat di en Bestätigungskood tostüürt warrt.',
'confirmemail_pending'     => 'Di is al en Bestätigungs-Kood över E-Mail toschickt worrn. Wenn du dien Brukerkonto nu eerst nee opstellt hest, denn tööv doch noch en poor Minuten op de E-Mail, ehrdat du di en ne’en Kood toschicken lettst.',
'confirmemail_send'        => 'Bestätigungskood tostüren.',
'confirmemail_sent'        => 'Bestätigungsnettbreef afschickt.',
'confirmemail_oncreate'    => 'Du hest en Bestätigungs-Kood an dien E-Mail-Adress kregen. Disse Kood is för dat Anmellen nich nödig. He warrt blot bruukt, dat du de E-Mail-Funkschonen in dat Wiki bruken kannst.',
'confirmemail_sendfailed'  => '{{SITENAME}} kunn di keen Bestätigungsnettbreef tostüren. Schasst man nakieken, wat de Adress ok nich verkehrt schreven is.

Fehler bi’t Versennen: $1',
'confirmemail_invalid'     => 'Bestätigungskood weer nich korrekt. De Kood is villicht to oolt.',
'confirmemail_needlogin'   => 'Du musst $1, dat diene Nettbreefadress bestätigt warrt.',
'confirmemail_success'     => 'Diene Nettbreefadress is nu bestätigt.',
'confirmemail_loggedin'    => 'Diene Nettbreefadress is nu bestätigt.',
'confirmemail_error'       => 'Dat Spiekern vun diene Bestätigung hett nich klappt.',
'confirmemail_subject'     => '{{SITENAME}} Nettbreefadress-Bestätigung',
'confirmemail_body'        => 'Een, villicht du vun de IP-Adress $1 ut, hett dat Brukerkonto „$2“ mit disse Nettbreefadress op {{SITENAME}} anmellt.

Dat wi weet, dat dit Brukerkonto würklich di tohöört un dat wi de Nettbreeffunkschonen freeschalten köönt, roop dissen Lenk op:

$3

Wenn du dit Brukerkonto *nich* nee opstellt hest, denn klick op dissen Lenk, dat du dat Bestätigen afbrickst:

$5

Wenn du dat nich sülvst wesen büst, denn folg den Lenk nich. De Bestätigungskood warrt $4 ungüllig.',
'confirmemail_invalidated' => 'E-Mail-Adressbestätigung afbraken',
'invalidateemail'          => 'E-Mail-Adressbestätigung afbreken',

# Scary transclusion
'scarytranscludedisabled' => '[Dat Inbinnen vun Interwikis is nich aktiv]',
'scarytranscludefailed'   => '[Vörlaag halen för $1 hett nich klappt]',
'scarytranscludetoolong'  => '[URL is to lang]',

# Trackbacks
'trackbackbox'      => 'Trackbacks för disse Sied:<br />
$1',
'trackbackremove'   => '([$1 wegsmieten])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback mit Spood wegsmeten.',

# Delete conflict
'deletedwhileediting' => "'''Wohrschau''': Disse Siet is wegsmeten worrn, wieldes du ehr graad ännert hest!",
'confirmrecreate'     => "De Bruker [[User:$1|$1]] ([[User talk:$1|talk]]) hett disse Siet wegsmeten, nadem du dat Ännern anfungen hest. He hett as Grund schreven:
: ''$2''
Wist du de Siet würklich nee anleggen?",
'recreate'            => 'wedder nee anleggen',

# action=purge
'confirm_purge_button' => 'Jo',
'confirm-purge-top'    => 'Den Cache vun disse Siet leddig maken?',
'confirm-purge-bottom' => 'Maakt den Cache vun en Sied leddig un sorgt dor för dat de aktuelle Version wiest warrt.',

# Multipage image navigation
'imgmultipageprev' => '← vörige Siet',
'imgmultipagenext' => 'nächste Siet →',
'imgmultigo'       => 'Los!',
'imgmultigoto'     => 'Gah na de Siet $1',

# Table pager
'ascending_abbrev'         => 'op',
'descending_abbrev'        => 'dal',
'table_pager_next'         => 'Nächste Siet',
'table_pager_prev'         => 'Vörige Siet',
'table_pager_first'        => 'Eerste Siet',
'table_pager_last'         => 'Letzte Siet',
'table_pager_limit'        => 'Wies $1 Indrääg je Siet',
'table_pager_limit_submit' => 'Los',
'table_pager_empty'        => 'nix funnen',

# Auto-summaries
'autosumm-blank'   => 'Sied leddig maakt',
'autosumm-replace' => 'Siet leddig maakt un ‚$1‘ rinschreven',
'autoredircomment' => 'Redirect sett na [[$1]]',
'autosumm-new'     => 'Ne’e Sied anleggt: ‚$1‘',

# Live preview
'livepreview-loading' => 'Läädt…',
'livepreview-ready'   => 'Läädt… Trech!',
'livepreview-failed'  => 'Live-Vörschau klapp nich!
Versöök de normale Vörschau.',
'livepreview-error'   => 'Verbinnen klapp nich: $1 „$2“
Versöök de normale Vörschau.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ännern, de jünger as {{PLURAL:$1|ene Sekunn|$1 Sekunnen}} sünd, warrt in de List noch nich wiest.',
'lag-warn-high'   => 'De Datenbank is temlich dull utlast. Ännern, de jünger as $1 {{PLURAL:$1|Sekunn|Sekunnen}} sünd, warrt in de List noch nich wiest.',

# Watchlist editor
'watchlistedit-numitems'       => 'Du hest {{PLURAL:$1|ene Siet|$1 Sieden}} op diene Oppasslist, Diskuschoonssieden nich tellt.',
'watchlistedit-noitems'        => 'Diene Oppasslist is leddig.',
'watchlistedit-normal-title'   => 'Oppasslist ännern',
'watchlistedit-normal-legend'  => 'Sieden vun de Oppasslist rünnernehmen',
'watchlistedit-normal-explain' => 'Dit sünd all de Sieden op diene Oppasslist. Sieden ruttonehmen, krüüz de Kassens blangen de Sieden an un klick op „{{int:Watchlistedit-normal-submit}}“. Du kannst diene Oppasslist ok in [[Special:Watchlist/raw|Listenform ännern]].',
'watchlistedit-normal-submit'  => 'Sieden rutnehmen',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Ene Siet|$1 Sieden}} vun de Oppasslist rünnernahmen:',
'watchlistedit-raw-title'      => 'Oppasslist as Textlist ännern',
'watchlistedit-raw-legend'     => 'Oppasslist as Textlist ännern',
'watchlistedit-raw-explain'    => 'Dit sünd de Sieden op diene Oppasslist as List. Du kannst Sieden rutnehmen oder tofögen. Een Reeg för een Sied.
Wenn du trech büst, denn klick op Oppasslist spiekern“.
Du kannst ok de [[Special:Watchlist/edit|normale Sied to’n Ännern]] bruken.',
'watchlistedit-raw-titles'     => 'Sieden:',
'watchlistedit-raw-submit'     => 'Oppasslist spiekern',
'watchlistedit-raw-done'       => 'Diene Oppasslist is spiekert worrn.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Ene Siet|$1 Sieden}} dorto:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Ene Siet|$1 Sieden}} rünnernahmen:',

# Watchlist editing tools
'watchlisttools-view' => 'Oppasslist ankieken',
'watchlisttools-edit' => 'Oppasslist ankieken un ännern',
'watchlisttools-raw'  => 'Oppasslist as Textlist ännern',

# Core parser functions
'unknown_extension_tag' => 'Unbekannt Extension-Tag „$1“',
'duplicate-defaultsort' => 'Wohrschau: De DEFAULTSORTKEY „$2“ överschrifft den vörher bruukten Slötel „$1“.',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Installeerte Extensions',
'version-specialpages'             => 'Spezialsieden',
'version-parserhooks'              => 'Parser-Hooks',
'version-variables'                => 'Variablen',
'version-other'                    => 'Annern Kraam',
'version-mediahandlers'            => 'Medien-Handlers',
'version-hooks'                    => 'Hooks',
'version-extension-functions'      => 'Extension-Funkschonen',
'version-parser-extensiontags'     => "Parser-Extensions ''(Tags)''",
'version-parser-function-hooks'    => 'Parser-Funkschonen',
'version-skin-extension-functions' => 'Skin-Extension-Funkschonen',
'version-hook-name'                => 'Hook-Naam',
'version-hook-subscribedby'        => 'Opropen vun',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Lizenz',
'version-software'                 => 'Installeerte Software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Dateipadd',
'filepath-page'    => 'Datei:',
'filepath-submit'  => 'Padd',
'filepath-summary' => 'Disse Spezialsiet gifft den kumpletten Padd för ene Datei trüch. Biller warrt in vull Oplösen wiest, annere Datein warrt glieks mit dat Programm opropen, dat för de Soort Datein instellt is.

Geev den Dateinaam ahn den Tosatz „{{ns:file}}:“ an.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Söök na Datein, de jüst gliek sünd',
'fileduplicatesearch-summary'  => 'Söök na Datein, de na jemehr Hash-Tallen jüst gliek sünd.

Geev den Dateinaam ahn dat Präfix „{{ns:file}}:“ in.',
'fileduplicatesearch-legend'   => 'Söök na Datein, de jüst gliek sünd',
'fileduplicatesearch-filename' => 'Dateinaam:',
'fileduplicatesearch-submit'   => 'Söken',
'fileduplicatesearch-info'     => '$1 × $2 Pixel<br />Dateigrött: $3<br />MIME-Typ: $4',
'fileduplicatesearch-result-1' => 'To de Datei „$1“ gifft dat keen Datei, de jüst gliek is.',
'fileduplicatesearch-result-n' => 'To de Datei „$1“ gifft dat {{PLURAL:$2|ene Datei, de jüst gliek is|$2 Datein, de jüst gliek sünd}}.',

# Special:SpecialPages
'specialpages'                   => 'Sünnerliche Sieden',
'specialpages-note'              => '----
* Normale Spezialsieden
* <strong class="mw-specialpagerestricted">Spezialsieden för Brukers mit mehr Rechten</strong>',
'specialpages-group-maintenance' => 'Pleeglisten',
'specialpages-group-other'       => 'Annere Spezialsieden',
'specialpages-group-login'       => 'Anmellen',
'specialpages-group-changes'     => 'Letzte Ännern un Logböker',
'specialpages-group-media'       => 'Medien',
'specialpages-group-users'       => 'Brukers un Rechten',
'specialpages-group-highuse'     => 'Veel bruukte Sieden',
'specialpages-group-pages'       => 'Siedenlisten',
'specialpages-group-pagetools'   => 'Siedenwarktüüch',
'specialpages-group-wiki'        => 'Systemdaten un Warktüüch',
'specialpages-group-redirects'   => 'Redirect-Spezialsieden',
'specialpages-group-spam'        => 'Spam-Warktüüch',

# Special:BlankPage
'blankpage'              => 'Leddige Sied',
'intentionallyblankpage' => 'Disse Sied is mit Afsicht leddig.',

# External image whitelist
'external_image_whitelist' => '  #Disse Reeg nich ännern<pre>
#Ünnen köönt Delen vun reguläre Utdrück (de Deel twischen de //) angeven warrn.
#De warrt mit de URLs vun Biller ut externe Borns vergleken
#En positiv Vergliek föhrt dorto, dat dat Bild wiest warrt, ans warrt dat Bild blot as Lenk wiest
#Regen, de mit en # anfangt, warrt as Kommentar behannelt
#De List maakt keen Ünnerscheed bi grote un lütte Bookstaven

#Delen vun reguläre Utdrück na disse Reeg indragen. Disse Reeg nich ännern</pre>',

# Special:Tags
'tags'                    => 'Güllig Änneretiketten',
'tag-filter'              => '
[[Special:Tags|Tag]]-Filter:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Tags',
'tags-intro'              => 'Disse Sied wiest all Etiketten, de för Ännern bruukt warrt, un wat se bedüüdt.',
'tags-tag'                => 'Intern Tagnaam',
'tags-display-header'     => 'Weddergaav op de Ännernlisten',
'tags-description-header' => 'Beschrievung, wat dat bedüüdt',
'tags-hitcount-header'    => 'Markeert Ännern',
'tags-edit'               => 'ännern',
'tags-hitcount'           => '$1 {{PLURAL:$1|Ännern|Ännern}}',

# Database error messages
'dberr-header'      => 'Dit Wiki hett en Problem',
'dberr-problems'    => 'Deit uns leed. Disse Websteed hett opstunns en beten technische Problemen.',
'dberr-again'       => 'Tööv en poor Minuten un versöök dat denn noch wedder.',
'dberr-info'        => '(Kunn nich mit’n Datenbank-Server verbinnen: $1)',
'dberr-usegoogle'   => 'Du kannst dat solang mit Google versöken.',
'dberr-outofdate'   => 'Wees gewohr, dat de Söökindex, de se vun uns Inhold hebbt, oold wesen kann.',
'dberr-cachederror' => 'Dit is en Kopie ut’n Cache vun de opropen Sied un is villicht nich de ne’este Version.',

# HTML forms
'htmlform-invalid-input'       => 'Mit welk vun de angeven Weerten gifft dat Problemen',
'htmlform-select-badoption'    => 'De angeven Weert is nich güllig.',
'htmlform-int-invalid'         => 'De angeven Tall is keen ganze Tall.',
'htmlform-int-toolow'          => 'De angeven Weert liggt ünner dat Minimum vun $1',
'htmlform-int-toohigh'         => 'De angeven Weert liggt över dat Maximum vun $1',
'htmlform-submit'              => 'Afspiekern',
'htmlform-reset'               => 'Ännern trüchsetten',
'htmlform-selectorother-other' => 'Annere',

);
