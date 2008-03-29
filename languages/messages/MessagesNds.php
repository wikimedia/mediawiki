<?php
/** Low German (Plattdüütsch)
 *
 * @addtogroup Language
 *
 * @author Slomox
 * @author Nike
 * @author SPQRobin
 * @author לערי ריינהארט
 * @author Siebrand
 */

$fallback = 'de';

$magicWords = array(
	'redirect'            => array( '0', '#redirect', '#wiederleiden' ),
	'notoc'               => array( '0', '__NOTOC__', '__KEENINHOLTVERTEKEN__' ),
	'forcetoc'            => array( '0', '__FORCETOC__', '__WIESINHOLTVERTEKEN__' ),
	'toc'                 => array( '0', '__TOC__', '__INHOLTVERTEKEN__' ),
	'noeditsection'       => array( '0', '__NOEDITSECTION__', '__KEENÄNNERNLINK__' ),
	'currentmonth'        => array( '1', 'CURRENTMONTH', 'AKTMAAND' ),
	'currentmonthname'    => array( '1', 'CURRENTMONTHNAME', 'AKTMAANDNAAM' ),
	'currentmonthnamegen' => array( '1', 'CURRENTMONTHNAMEGEN', 'AKTMAANDNAAMGEN' ),
	'currentday'          => array( '1', 'CURRENTDAY', 'AKTDAG' ),
	'currentdayname'      => array( '1', 'CURRENTDAYNAME', 'AKTDAGNAAM' ),
	'currentyear'         => array( '1', 'CURRENTYEAR', 'AKTJOHR' ),
	'currenttime'         => array( '1', 'CURRENTTIME', 'AKTTIED' ),
	'numberofarticles'    => array( '1', 'NUMBEROFARTICLES', 'ARTIKELTALL' ),
	'pagename'            => array( '1', 'PAGENAME', 'SIETNAAM' ),
	'pagenamee'           => array( '1', 'PAGENAMEE', 'SIETNAAME' ),
	'namespace'           => array( '1', 'NAMESPACE', 'NAAMRUUM' ),
	'img_thumbnail'       => array( '1', 'thumbnail', 'thumb', 'duum' ),
	'img_right'           => array( '1', 'right', 'rechts' ),
	'img_left'            => array( '1', 'left', 'links' ),
	'img_none'            => array( '1', 'none', 'keen' ),
	'img_width'           => array( '1', '$1px', '$1px' ),
	'img_center'          => array( '1', 'center', 'centre', 'merrn' ),
	'img_framed'          => array( '1', 'framed', 'enframed', 'frame', 'rahmt' ),
	'sitename'            => array( '1', 'SITENAME', 'STEEDNAAM' ),
	'ns'                  => array( '0', 'NS:', 'NR:' ),
	'localurl'            => array( '0', 'LOCALURL:', 'STEEDURL:' ),
	'localurle'           => array( '0', 'LOCALURLE:', 'STEEDURLE:' ),
	'server'              => array( '0', 'SERVER', 'SERVER' ),
	'grammar'             => array( '0', 'GRAMMAR:', 'GRAMMATIK:' ),
);

$skinNames = array(
	'standard'    => 'Klassik',
	'nostalgia'   => 'Nostalgie',
	'cologneblue' => 'Kölsch Blau',
	'chick'       => 'Küken',
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
	NS_IMAGE          => 'Bild',
	NS_IMAGE_TALK     => 'Bild_Diskuschoon',
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
	'DoubleRedirects'           => array( 'Dubbelte_Redirects' ),
	'BrokenRedirects'           => array( 'Kaputte_Redirects' ),
	'Disambiguations'           => array( 'Mehrdüdige_Begrepen' ),
	'Userlogin'                 => array( 'Anmellen' ),
	'Userlogout'                => array( 'Afmellen' ),
	'Preferences'               => array( 'Instellungen' ),
	'Watchlist'                 => array( 'Oppasslist' ),
	'Recentchanges'             => array( 'Neeste_Ännern' ),
	'Upload'                    => array( 'Hoochladen' ),
	'Imagelist'                 => array( 'Dateilist' ),
	'Newimages'                 => array( 'Nee_Datein' ),
	'Listusers'                 => array( 'Brukers' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Tofällige_Siet' ),
	'Lonelypages'               => array( 'Weetsieden' ),
	'Uncategorizedpages'        => array( 'Sieden_ahn_Kategorie' ),
	'Uncategorizedcategories'   => array( 'Kategorien_ahn_Kategorie' ),
	'Uncategorizedimages'       => array( 'Datein_ahn_Kategorie' ),
	'Uncategorizedtemplates'    => array( 'Vörlagen_ahn_Kategorie' ),
	'Unusedcategories'          => array( 'Nich_bruukte_Kategorien' ),
	'Unusedimages'              => array( 'Nich_bruukte_Datein' ),
	'Wantedpages'               => array( 'Wünschte_Sieden' ),
	'Wantedcategories'          => array( 'Wünschte_Kategorien' ),
	'Mostlinked'                => array( 'Veel_lenkte_Sieden' ),
	'Mostlinkedcategories'      => array( 'Veel_bruukte_Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Veel_bruukte_Vörlagen' ),
	'Mostcategories'            => array( 'Sieden_mit_vele_Kategorien' ),
	'Mostimages'                => array( 'Veel_bruukte_Datein' ),
	'Mostrevisions'             => array( 'Faken_ännerte_Sieden' ),
	'Fewestrevisions'           => array( 'Kuum_ännerte_Sieden' ),
	'Shortpages'                => array( 'Korte_Sieden' ),
	'Longpages'                 => array( 'Lange_Sieden' ),
	'Newpages'                  => array( 'Nee_Sieden' ),
	'Ancientpages'              => array( 'Ole_Sieden' ),
	'Deadendpages'              => array( 'Sackstraatsieden' ),
	'Protectedpages'            => array( 'Schuulte_Sieden' ),
	'Allpages'                  => array( 'Alle_Sieden' ),
	'Prefixindex'               => array( 'Sieden_de_anfangt_mit' ),
	'Ipblocklist'               => array( 'List_vun_blockte_IPs' ),
	'Specialpages'              => array( 'Spezialsieden' ),
	'Contributions'             => array( 'Bidrääg' ),
	'Emailuser'                 => array( 'E-Mail_an_Bruker' ),
	'Confirmemail'              => array( 'E-Mail_bestätigen' ),
	'Whatlinkshere'             => array( 'Wat_wiest_hier_hen' ),
	'Recentchangeslinked'       => array( 'Ännern_an_lenkte_Sieden' ),
	'Movepage'                  => array( 'Schuven' ),
	'Blockme'                   => array( 'Proxy-Sparr' ),
	'Booksources'               => array( 'ISBN-Söök' ),
	'Categories'                => array( 'Kategorien' ),
	'Export'                    => array( 'Exporteren' ),
	'Version'                   => array( 'Version' ),
	'Allmessages'               => array( 'Systemnarichten' ),
	'Log'                       => array( 'Logbook' ),
	'Blockip'                   => array( 'Blocken' ),
	'Undelete'                  => array( 'Wedderhalen' ),
	'Import'                    => array( 'Importeren' ),
	'Lockdb'                    => array( 'Datenbank_sparren' ),
	'Unlockdb'                  => array( 'Datenbank_freegeven' ),
	'Userrights'                => array( 'Brukerrechten' ),
	'MIMEsearch'                => array( 'MIME-Typ-Söök' ),
	'Unwatchedpages'            => array( 'Sieden_op_keen_Oppasslist' ),
	'Listredirects'             => array( 'List_vun_Redirects' ),
	'Revisiondelete'            => array( 'Versionen_wegsmieten' ),
	'Unusedtemplates'           => array( 'Nich_bruukte_Vörlagen' ),
	'Randomredirect'            => array( 'Tofällig_Redirect' ),
	'Mypage'                    => array( 'Miene_Brukersiet' ),
	'Mytalk'                    => array( 'Miene_Diskuschoonssiet' ),
	'Mycontributions'           => array( 'Miene_Bidrääg' ),
	'Listadmins'                => array( 'Administraters' ),
	'Listbots'                  => array( 'Bots' ),
	'Popularpages'              => array( 'Veel_besöchte_Sieden' ),
	'Search'                    => array( 'Söök' ),
	'Resetpass'                 => array( 'Passwoort_trüchsetten' ),
	'Withoutinterwiki'          => array( 'Sieden_ahn_Spraaklenken' ),
	'MergeHistory'              => array( 'Versionshistorie_tohoopbringen' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Verwies ünnerstrieken',
'tog-highlightbroken'         => 'Verwies op leddige Sieten hervörheven',
'tog-justify'                 => 'Text as Blocksatz',
'tog-hideminor'               => 'Kene lütten Ännern in letzte Ännern wiesen',
'tog-extendwatchlist'         => 'Utwiedt Oppasslist',
'tog-usenewrc'                => 'Erwiederte letzte Ännern (nich för alle Browser bruukbor)',
'tog-numberheadings'          => 'Överschrieven automatsch nummereern',
'tog-showtoolbar'             => 'Editeer-Warktüüchlist wiesen',
'tog-editondblclick'          => 'Sieten mit Dubbelklick bearbeiden (JavaScript)',
'tog-editsection'             => 'Links för dat Bearbeiden vun en Afsatz wiesen',
'tog-editsectiononrightclick' => 'En Afsatz mit en Rechtsklick bearbeiden (Javascript)',
'tog-showtoc'                 => "Wiesen vun'n Inholtsverteken bi Sieten mit mehr as dree Överschriften",
'tog-rememberpassword'        => 'Duersam Inloggen',
'tog-editwidth'               => 'Text-Ingaavfeld mit vulle Breed',
'tog-watchcreations'          => 'Nee schrevene Sieden op miene Oppasslist setten',
'tog-watchdefault'            => 'Op ne’e un ännerte Sieden oppassen',
'tog-watchmoves'              => 'Sieden, de ik schuuv, to de Oppasslist todoon',
'tog-watchdeletion'           => 'Sieden, de ik wegsmiet, to de Oppasslist todoon',
'tog-minordefault'            => 'Alle Ännern as lütt markeern',
'tog-previewontop'            => 'Vörschau vör dat Editeerfinster wiesen',
'tog-previewonfirst'          => "Vörschau bi'n eersten Ännern wiesen",
'tog-nocache'                 => 'Sietencache deaktiveern',
'tog-enotifwatchlistpages'    => 'Schriev mi en Nettbreef, wenn ene Siet, op de ik oppass, ännert warrt',
'tog-enotifusertalkpages'     => 'Schriev mi en Nettbreef, wenn ik ne’e Narichten heff',
'tog-enotifminoredits'        => 'Schriev mi en Nettbreef, ok wenn dat blots en lütte Ännern weer',
'tog-enotifrevealaddr'        => 'Miene Nettbreefadress in Bestätigungsnettbreven wiesen',
'tog-shownumberswatching'     => 'Wies de Tall vun Brukers, de op disse Siet oppasst',
'tog-fancysig'                => 'eenfache Signatur (ahn Lenk)',
'tog-externaleditor'          => 'Jümmer extern Editor bruken',
'tog-externaldiff'            => 'Extern Warktüüch to’n Wiesen vun Ünnerscheden as Standard bruken',
'tog-showjumplinks'           => '„Wesseln-na“-Lenken tolaten',
'tog-uselivepreview'          => 'Live-Vörschau bruken (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Segg mi bescheid, wenn ik keen Tosamenfaten geven heff, wat ik allens ännert heff',
'tog-watchlisthideown'        => 'Ännern vun mi sülvs op de Oppasslist nich wiesen',
'tog-watchlisthidebots'       => 'Ännern vun Bots op de Oppasslist nich wiesen',
'tog-watchlisthideminor'      => 'Lütte Ännern op de Oppasslist nich wiesen',
'tog-nolangconversion'        => 'Variantenkonverschoon utschalten',
'tog-ccmeonemails'            => 'vun Nettbreven, de ik wegschick, an mi sülvst Kopien schicken',
'tog-diffonly'                => "Don't show page content below diffs
Na ''Ünnerscheed wiesen'' nich de kumplette Siet wiesen",
'tog-showhiddencats'          => 'Wies verstekene Kategorien',

'underline-always'  => 'Jümmer',
'underline-never'   => 'Nienich',
'underline-default' => 'so as in’n Nettkieker instellt',

'skinpreview' => '(Vörschau)',

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
'april-gen'     => 'Aprils',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Augusts',
'september-gen' => 'Septembers',
'october-gen'   => 'Oktober',
'november-gen'  => 'Novembers',
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
'categories'                    => '{{PLURAL:$1|Kategorie|Kategorien}}',
'categoriespagetext'            => 'In disse Kategorien staht Sieden oder Mediendatein.',
'special-categories-sort-count' => 'na Tall sorteren',
'special-categories-sort-abc'   => 'alphabeetsch sorteren',
'pagecategories'                => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'               => 'Sieden in de Kategorie „$1“',
'subcategories'                 => 'Ünnerkategorien',
'category-media-header'         => 'Mediendatein in de Kategorie „$1“',
'category-empty'                => "''In disse Kategorie sünd aktuell kene Sieden.''",
'hidden-categories'             => '{{PLURAL:$1|Verstekene Kategorie|Verstekene Kategorien}}',
'hidden-category-category'      => 'Verstekene Kategorien', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'        => 'wieder',

'mainpagetext'      => 'De Wiki-Software is mit Spood installeert worrn.',
'mainpagedocfooter' => 'Kiek de [http://meta.wikimedia.org/wiki/MediaWiki_localisation Dokumentatschoon för dat Anpassen vun de Brukerböversiet]
un dat [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Brukerhandbook] för Hülp to de Bruuk un Konfiguratschoon.',

'about'          => 'Över',
'article'        => 'Artikel',
'newwindow'      => '(apent sik in en nieg Finster)',
'cancel'         => 'Afbreken',
'qbfind'         => 'Finnen',
'qbbrowse'       => 'Blädern',
'qbedit'         => 'Ännern',
'qbpageoptions'  => 'Siedenopschonen',
'qbpageinfo'     => 'Sietendaten',
'qbmyoptions'    => 'Instellen',
'qbspecialpages' => 'Spezialsieten',
'moredotdotdot'  => 'Mehr...',
'mypage'         => 'Mien Siet',
'mytalk'         => 'Mien Diskuschoon',
'anontalk'       => 'Diskuschoonssiet vun disse IP',
'navigation'     => 'Navigatschoon',
'and'            => 'un',

# Metadata in edit box
'metadata_help' => 'Metadaten:',

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
'protect_change'    => 'Schuul-Status ännern',
'protectthispage'   => 'Siet schulen',
'unprotect'         => 'Freegeven',
'unprotectthispage' => 'Schuul opheben',
'newpage'           => 'Ne’e Siet',
'talkpage'          => 'Diskuschoon',
'talkpagelinktext'  => 'Diskuschoon',
'specialpage'       => 'Spezialsiet',
'personaltools'     => 'Persönliche Warktüüch',
'postcomment'       => 'Kommentar hentofögen',
'articlepage'       => 'Artikel',
'talk'              => 'Diskuschoon',
'views'             => 'Ansichten',
'toolbox'           => 'Warktüüch',
'userpage'          => 'Brukersiet ankieken',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Bildsiet',
'mediawikipage'     => 'Systemnaricht ankieken',
'templatepage'      => 'Vörlaag ankieken',
'viewhelppage'      => 'Helpsiet ankieken',
'categorypage'      => 'Kategorie ankieken',
'viewtalkpage'      => 'Diskuschoon ankieken',
'otherlanguages'    => 'Annere Spraken',
'redirectedfrom'    => '(Wiederleiden vun $1)',
'redirectpagesub'   => 'Redirectsiet',
'lastmodifiedat'    => 'Disse Siet is toletzt üm $2, $1 ännert worrn.', # $1 date, $2 time
'viewcount'         => 'Disse Siet is {{PLURAL:$1|een|$1}} Maal opropen worrn.',
'protectedpage'     => 'Schuulte Sieden',
'jumpto'            => 'Wesseln na:',
'jumptonavigation'  => 'Navigatschoon',
'jumptosearch'      => 'Söök',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Över {{SITENAME}}',
'aboutpage'         => 'Project:Över_{{SITENAME}}',
'bugreports'        => 'Kontakt',
'bugreportspage'    => 'Project:Kontakt',
'copyright'         => 'Inholt is verfögbor ünner de $1.',
'copyrightpagename' => '{{SITENAME}} Copyright',
'copyrightpage'     => '{{ns:project}}:Lizenz',
'currentevents'     => 'Aktuell Schehn',
'currentevents-url' => 'Project:Aktuell Schehn',
'disclaimers'       => 'Impressum',
'disclaimerpage'    => 'Project:Impressum',
'edithelp'          => 'Bearbeidenshülp',
'edithelppage'      => 'Help:Ännern',
'faq'               => 'Faken stellte Fragen',
'faqpage'           => 'Project:Faken stellte Fragen',
'helppage'          => 'Help:Hülp',
'mainpage'          => 'Hööftsiet',
'policy-url'        => 'Project:Richtlienen',
'portal'            => '{{SITENAME}}-Portal',
'portal-url'        => 'Project:{{SITENAME}}-Portal',
'privacy'           => 'Över Datenschutz',
'privacypage'       => 'Project:Datenschutz',
'sitesupport'       => 'Spennen',
'sitesupport-url'   => 'Project:Spennen',

'badaccess'        => 'Fehler bi de Rechten',
'badaccess-group0' => 'Du hest keen Verlööf för disse Akschoon.',
'badaccess-group1' => 'Disse Akschoon is blots för Brukers ut de Brukergrupp $1.',
'badaccess-group2' => 'Disse Akschoon is blots för Brukers ut een vun de Brukergruppen $1.',
'badaccess-groups' => 'Disse Akschoon is blots för Brukers ut een vun de Brukergruppen $1.',

'versionrequired'     => 'Version $1 vun MediaWiki nödig',
'versionrequiredtext' => 'Version $1 vun MediaWiki is nödig, disse Siet to bruken. Kiek op de Siet [[Special:Version|Version]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Vun „$1“',
'youhavenewmessages'      => 'Du hest $1 ($2).',
'newmessageslink'         => 'Ne’e Narichten',
'newmessagesdifflink'     => 'Ünnerscheed to vörher',
'youhavenewmessagesmulti' => 'Du hest ne’e Narichten op $1',
'editsection'             => 'bearbeiden',
'editold'                 => 'bearbeiden',
'editsectionhint'         => 'Ännere Afsnitt: $1',
'toc'                     => 'Inholtsverteken',
'showtoc'                 => 'wiesen',
'hidetoc'                 => 'Nich wiesen',
'thisisdeleted'           => 'Ankieken oder weerholen vun $1?',
'viewdeleted'             => '$1 ankieken?',
'restorelink'             => '{{PLURAL:$1|ene löschte Version|$1 löschte Versionen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungülligen Abo-Typ.',
'feed-unavailable'        => 'För {{SITENAME}} gifft dat kene Feeds.',
'site-rss-feed'           => 'RSS-Feed för $1',
'site-atom-feed'          => 'Atom-Feed för $1',
'page-rss-feed'           => 'RSS-Feed för „$1“',
'page-atom-feed'          => 'Atom-Feed för „$1“',
'red-link-title'          => '$1 (noch nich vörhannen)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Siet vun den Bruker',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezial',
'nstab-project'   => 'Över',
'nstab-image'     => 'Bild',
'nstab-mediawiki' => 'Naricht',
'nstab-template'  => 'Vörlaag',
'nstab-help'      => 'Hülp',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Disse Aktschoon gifft dat nich',
'nosuchactiontext'  => 'Disse Aktschoon warrt vun de MediaWiki-Software nich ünnerstütt',
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
'noconnect'            => 'De Software kunn keen Verbinnen to de Datenbank op $1 opnehmen',
'nodb'                 => 'De Software kunn de Datenbank $1 nich utwählen',
'cachederror'          => "Disse Siet is en Kopie ut'n Cache un is mööglicherwies nich aktuell.",
'laggedslavemode'      => 'Wohrschau: Disse Siet is villicht nich mehr op den ne’esten Stand.',
'readonly'             => 'Datenbank is sparrt',
'enterlockreason'      => 'Giff den Grund an, worüm de Datenbank sparrt warrn schall un taxeer, wo lang de Sparr duert',
'readonlytext'         => 'De Datenbank vun {{SITENAME}} is opstunns sparrt. Versöök dat later noch eenmal, duert meist nich lang, denn geiht dat wedder.

As Grund för de Sparr is angeven: $1',
'missingarticle'       => "De Text för de Siet '$1' kunn nich in de Datenbank funnen warrn. Dat is wohrschienlich en Fehler in de Software. Bitte mell dat an enen Administrater un giff ok den Sietennaam an.",
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
'perfdisabled'         => 'Disse Funktschoon is wegen Överlast vun de Servers för enige Tied deaktiveert. Versöök dat doch twüschen 02:00 un 14:00 UTC noch eenmal<br />(Aktuelle Servertied: 21:06:12 UTC).',
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
'sqlhidden'            => '(SQL-Affraag versteken)',
'namespaceprotected'   => "Du hest keen Rechten, Sieden in’n Naamruum '''$1''' to ännern.",
'customcssjsprotected' => 'Du hest keen Rechten, disse Siet to ännern. Dor sünd persönliche Instellungen vun en annern Bruker in.',
'ns-specialprotected'  => 'Sieden in’n Naamruum {{ns:special}} köönt nich ännert warrn.',
'titleprotected'       => "Disse Siet is gegen dat nee Opstellen vun [[{{ns:user}}:$1]] schuult worrn.
As Grund is angeven: ''$2''.",

# Login and logout pages
'logouttitle'                => 'Bruker-Afmellen',
'logouttext'                 => 'Du büst nu afmellt. Du kannst {{SITENAME}} nu anonym wiederbruken oder di ünner en annern Brukernaam wedder anmellen.',
'welcomecreation'            => '<h2>Willkomen, $1!</h2><p>Dien Brukerkonto is nu inricht.
Vergeet nich, dien [[Special:Preferences|Instellen]] antopassen.',
'loginpagetitle'             => 'Bruker-Anmellen',
'yourname'                   => 'Dien Brukernaam',
'yourpassword'               => 'Dien Passwoort',
'yourpasswordagain'          => 'Passwoort nochmal ingeven',
'remembermypassword'         => 'Duersam inloggen',
'yourdomainname'             => 'Diene Domään:',
'loginproblem'               => '<b>Dor weer en Problem mit dien Anmellen.</b><br />Versöök dat noch eenmal!',
'login'                      => 'Anmellen',
'loginprompt'                => 'Üm di bi {{SITENAME}} antomellen, musst du Cookies anstellt hebben.',
'userlogin'                  => 'Nee Konto anleggen oder anmellen',
'logout'                     => 'Afmellen',
'userlogout'                 => 'Afmellen',
'notloggedin'                => 'Nich anmellt',
'nologin'                    => 'Wenn du noch keen Brukerkonto hest, denn kannst di anmellen: $1.',
'nologinlink'                => 'Brukerkonto inrichten',
'createaccount'              => 'Nieg Brukerkonto anleggen',
'gotaccount'                 => 'Hebbt Se al en Konto? $1.',
'gotaccountlink'             => 'Anmellen',
'createaccountmail'          => 'över E-Mail',
'badretype'                  => 'De beiden Passwöör stimmt nich övereen.',
'userexists'                 => 'Disse Brukernaam is al weg. Bitte söök di en annern ut.',
'youremail'                  => 'Dien E-Mail (kene Plicht) *',
'username'                   => 'Brukernaam:',
'uid'                        => 'Bruker-ID:',
'yourrealname'               => 'Dien echten Naam (kene Plicht)',
'yourlanguage'               => 'Snittstellenspraak',
'yourvariant'                => 'Dien Spraak',
'yournick'                   => 'Dien Ökelnaam (för dat Ünnerschrieven)',
'badsig'                     => 'De Signatur is nich korrekt, kiek nochmal na de HTML-Tags.',
'badsiglength'               => 'Signatur is to lang; de mutt weniger as $1 Teken lang wesen.',
'email'                      => 'Nettbreef',
'prefs-help-realname'        => 'De echte Naam mutt nich angeven warrn. Wenn du em angiffst, warrt de Naam bruukt, dat diene Arbeit di torekent warrn kann.',
'loginerror'                 => 'Fehler bi dat Anmellen',
'prefs-help-email-required'  => 'E-Mail-Adress nödig.',
'nocookiesnew'               => 'De Brukertogang is anleggt, aver du büst nich inloggt. {{SITENAME}} bruukt för disse Funktschoon Cookies, aktiveer de Cookies un logg di denn mit dien nieg Brukernaam un den Password in.',
'nocookieslogin'             => '{{SITENAME}} bruukt Cookies för dat Inloggen vun de Bruker. Du hest Cookies deaktiveert, aktiveer de Cookies un versöök dat noch eenmal.',
'noname'                     => 'Du muttst en Brukernaam angeven.',
'loginsuccesstitle'          => 'Anmellen hett Spood',
'loginsuccess'               => 'Du büst nu as „$1“ bi {{SITENAME}} anmellt.',
'nosuchuser'                 => 'Den Brukernaam „$1“ gifft dat nich.
Kiek de Schrievwies na oder mell di as ne’en Bruker an.',
'nosuchusershort'            => 'De Brukernaam „<nowiki>$1</nowiki>“ existeert nich. Prööv de Schrievwies.',
'nouserspecified'            => 'Du musst en Brukernaam angeven',
'wrongpassword'              => 'Dat Passwoort, wat du ingeven hest, is verkehrt. Kannst dat aver noch wedder versöken.',
'wrongpasswordempty'         => 'Dat Passwoort, wat du ingeven hest, is leddig, versöök dat noch wedder.',
'passwordtooshort'           => 'Dat Passwoort is to kort. Dat schall woll beter $1 Teken lang oder noch länger wesen.',
'mailmypassword'             => 'En nieg Password sennen',
'passwordremindertitle'      => 'Nee Passwoort för {{SITENAME}}',
'passwordremindertext'       => 'Een (IP-Adress $1) hett för en nee Passwoort to’n Anmellen bi {{SITENAME}} beden ($4).
Dat Passwoort för Bruker „$2“ is nu „$3“. Bitte mell di nu an un änner dien Passwoort.

Wenn du nich sülvst för en nee Passwoort beden hest, denn bruukst di wegen disse Naricht nich to kümmern un kannst dien oolt Passwoort wiederbruken.',
'noemail'                    => 'Bruker „$1“ hett kene E-Mail-Adress angeven.',
'passwordsent'               => 'En nee Passwoort is an de E-Mail-Adress vun Bruker „$1“ schickt worrn. Mell di an, wenn du dat Passwoort kregen hest.',
'eauthentsent'               => 'Ene Bestätigungs-E-Mail is an de angevene Adress schickt worrn.
Ehrdat E-Mails vun annere Brukers över de E-Mail-Funkschoon kamen köönt, mutt de Adress eerst noch bestätigt warrn.
In de E-Mail steiht, wie dat geiht.',
'throttled-mailpassword'     => 'Binnen de letzten $1 Stünnen is al mal en neet Passwoort toschickt worrn. Dat disse Funkschoon nich missbruukt warrt, kann blot alle $1 Stünnen en neet Passwoort toschickt warrn.',
'mailerror'                  => 'Fehler bi dat Sennen vun de E-Mail: $1',
'acct_creation_throttle_hit' => 'Du hest al $1 Brukerkontos anleggt. Du kannst nich noch mehr anleggen.',
'emailauthenticated'         => 'Diene E-Mail-Adress is bestätigt worrn: $1.',
'noemailprefs'               => 'Geev en E-Mail-Adress an, dat du disse Funkschonen bruken kannst.',
'emailconfirmlink'           => 'Nettbreef-Adress bestätigen',
'invalidemailaddress'        => 'Mit de E-Mail-Adress weer nix antofangen, dor stimmt wat nich mit dat Format. Geev en k’rekkte Adress in oder laat dat Feld leddig.',
'accountcreated'             => 'Brukerkonto inricht',
'accountcreatedtext'         => 'Dat Brukerkonto $1 is nee opstellt worrn.',
'createaccount-title'        => 'Konto anleggen för {{SITENAME}}',
'createaccount-text'         => 'Een hett för di op {{SITENAME}} ($4) en Brukerkonto "$2" nee opstellt. Dat automaatsch instellte Passwoort för "$2" is "$3". Du schullst di nu man anmellen un dat Passwoort ännern.

Wenn du dat Brukerkonto gor nich hebben wullst, denn is disse Naricht egaal för di. Kannst ehr eenfach ignoreren.',
'loginlanguagelabel'         => 'Spraak: $1',

# Password reset dialog
'resetpass'               => 'Passwoort vun dat Brukerkonto trüchsetten',
'resetpass_header'        => 'Passwoort trüchsetten',
'resetpass_submit'        => 'Passwoort instellen un inloggen',
'resetpass_success'       => 'Dien Passwoort is mit Spood ännert worrn. Warrst nu anmellt …',
'resetpass_bad_temporary' => 'Passwoort op Tiet gellt nich mehr. Du hest al mit Spood dien Passwoort ännert oder en neet Passwoort op Tiet kregen.',
'resetpass_forbidden'     => 'Dat Passwoort bi {{SITENAME}} kann nich ännert warrn.',
'resetpass_missing'       => 'Formular leddig',

# Edit page toolbar
'bold_sample'     => 'Fetten Text',
'bold_tip'        => 'Fetten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursiven Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Internen Link',
'extlink_sample'  => 'http://www.bispeel.com Link-Text',
'extlink_tip'     => 'Externen Link (http:// is wichtig)',
'headline_sample' => 'Evene 2 Överschrift',
'headline_tip'    => 'Evene 2 Överschrift',
'math_sample'     => 'Formel hier infögen',
'math_tip'        => 'Mathematsche Formel (LaTeX)',
'nowiki_sample'   => 'Unformateerten Text hier infögen',
'nowiki_tip'      => 'Unformateerten Text',
'image_sample'    => 'Bispeel.jpg',
'image_tip'       => 'Bild-Verwies',
'media_sample'    => 'Bispeel.mp3',
'media_tip'       => 'Mediendatei-Verwies',
'sig_tip'         => 'Diene Signatur mit Tietstempel',
'hr_tip'          => 'Waagrechte Lien (sporsam bruken)',

# Edit pages
'summary'                   => 'Tosamenfaten',
'subject'                   => 'Bedrap',
'minoredit'                 => 'Blots lütte Ännern',
'watchthis'                 => 'Op disse Siet oppassen',
'savearticle'               => 'Siet spiekern',
'preview'                   => 'Vörschau',
'showpreview'               => 'Vörschau wiesen',
'showlivepreview'           => 'Live-Vörschau',
'showdiff'                  => 'Ünnerscheed wiesen',
'anoneditwarning'           => "'''Wohrschau:''' Du büst nich anmellt. Diene IP-Adress warrt in de Versionshistorie vun de Siet fasthollen.",
'missingsummary'            => "'''Wohrschau:''' Du hest keen Tosamenfaten angeven, wat du ännert hest. Wenn du nu Spiekern klickst, warrt de Siet ahn Tosamenfaten spiekert.",
'missingcommenttext'        => 'Geev ünnen en Tosamenfaten in.',
'missingcommentheader'      => "'''WOHRSCHAU:''' Du hest keen Överschrift in dat Feld „{{MediaWiki:Subject/nds}}“ ingeven. Wenn du noch wedder op „{{MediaWiki:Savearticle/nds}}“ klickst, denn warrt dien Ännern ahn Överschrift spiekert.",
'summary-preview'           => 'Vörschau vun’t Tosamenfaten',
'subject-preview'           => "Vörschau vun de Reeg ''Tosamenfaten''",
'blockedtitle'              => 'Bruker is blockt',
'blockedtext'               => 'Dien Brukernaam oder diene IP-Adress is vun $1 blockt worrn.
As Grund is angeven: \'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Logbookindrag]</span>, de Block-ID is $5).

Du dröffst aver jümmer noch lesen. Blot dat Schrieven geiht nich. Wenn du gor nix schrieven wullst, denn hest du villicht op en roden Lenk klickt, to en Artikel den dat noch nich gifft. blot blaue Lenken gaht na vörhannene Artikels.

Wenn du glöövst, dat Sparren weer unrecht, denn mell di bi een vun de [[{{MediaWiki:Grouppage-sysop}}|Administraters]]. Geev bi Fragen jümmer ok dien IP-Adress ($3) oder de ID vun dien Block (#$5) mit an.

* Anfang vun’n Block: $8
* Enn vun’n Block: $6
* Block vun: $7
* IP-Adress: $3
* Block-ID: #$5
* Wokeen hett blockt: $1',
'blockednoreason'           => 'keen Grund angeven',
'blockedoriginalsource'     => "De Borntext vun '''$1''' warrt hier wiest:",
'blockededitsource'         => "De Text vun '''diene Ännern''' an '''$1''':",
'whitelistedittitle'        => 'de Siet to ännern is dat nödig anmellt to wesen',
'whitelistedittext'         => 'Du musst di $1, dat du Sieden ännern kannst.',
'whitelistreadtitle'        => 'üm to Lesen is dat neudig anmellt to ween',
'whitelistreadtext'         => 'Du musst di [[{{ns:special}}:Userlogin|hier anmellen]], dat du Sieden lesen kannst.',
'whitelistacctitle'         => 'Du hest nich de Rechten en Konto antoleggen',
'whitelistacctext'          => 'Dat du op {{SITENAME}} Brukerkontos anleggen kannst, musst du di [[Special:Userlogin|hier anmellen]] un de nödigen Rechten hebben.',
'confirmedittitle'          => 'E-Mail-Adress mutt bestätigt wesen, dat du wat ännern kannst',
'nosuchsectiontitle'        => 'Dissen Afsnitt gifft dat nich',
'loginreqtitle'             => 'Anmellen nödig',
'loginreqlink'              => 'anmellen',
'loginreqpagetext'          => 'Du musst di $1, dat du annere Sieden ankieken kannst.',
'accmailtitle'              => 'Passwoort is toschickt worrn.',
'accmailtext'               => 'Dat Passwoort vun $1 is an $2 schickt worrn.',
'newarticle'                => '(Nee)',
'newarticletext'            => 'Hier den Text vun de ne’e Siet indregen. Jümmer in ganze Sätz schrieven un kene Texten vun Annern, de en Oorheverrecht ünnerliggt, hierher kopeern.',
'anontalkpagetext'          => "---- ''Dit is de Diskuschoonssiet vun en nich anmellt Bruker. Wi mööt hier de numerische [[IP-Adress]]
verwennen, üm den Bruker to identifizeern. So en Adress kann vun verscheden Brukern bruukt warrn. Wenn du en anonymen Bruker büst un meenst,
dat disse Kommentaren nich an di richt sünd, denn [[Special:Userlogin|mell di doch an]], dormit dat Problem nich mehr besteiht.''",
'noarticletext'             => 'Dor is keen Text op disse Siet. [[{{ns:special}}:Search/{{PAGENAME}}|Na dissen Utdruck in annere Sieden söken]].',
'userpage-userdoesnotexist' => 'Dat Brukerkonto „$1“ gifft dat noch nich. Överlegg, wat du disse Siet würklich nee opstellen/ännern wullt.',
'clearyourcache'            => "'''Denk doran:''' No den Spiekern muttst du dien Browser noch seggen, de niege Version to laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview'    => '<strong>Tipp:</strong> Bruuk den Vörschau-Knoop, üm dien nieg CSS/JS vör dat Spiekern to testen.',
'usercsspreview'            => "'''Denk doran, dat du blots en Vörschau vun dien CSS ankiekst, dat is noch nich spiekert!'''",
'userjspreview'             => "'''Denk doran, dat du blots en Vörschau vun dien JS ankiekst, dat is noch nich spiekert!'''",
'updated'                   => '(Ännert)',
'note'                      => '<strong>Wohrschau:</strong>',
'previewnote'               => 'Dit is blots en Vörschau, de Siet is noch nich spiekert!',
'previewconflict'           => 'Disse Vörschau wiest den Inholt vun dat Textfeld baven; so warrt de Siet utseihn, wenn du nu spiekerst.',
'session_fail_preview'      => '<strong>Deit uns leed! Wi kunnen dien Ännern nich spiekern. Diene Sitzungsdaten weren weg.
Versöök dat noch wedder. Wenn dat noch jümmer nich geiht, denn versöch di af- un denn wedder antomellen.</strong>',
'editing'                   => 'Ännern vun $1',
'editingsection'            => 'Ännern vun $1 (Afsatz)',
'editingcomment'            => 'Ännern vun $1 (Kommentar)',
'editconflict'              => 'Konflikt bi dat Bearbeiden: $1',
'explainconflict'           => 'En anner Bruker hett disse Siet ännert, no de Tied dat du anfungen hest, de Siet to bearbeiden.
Dat Textfeld baven wiest de aktuelle Siet.
Dat Textfeld nerrn wiest diene Ännern.
Föög diene Ännern in dat Textfeld baven in.

<b>Blots</b> de Text in dat Textfeld baven warrt spiekert, wenn du op Spiekern klickst!<br />',
'yourtext'                  => 'Dien Text',
'storedversion'             => 'Spiekerte Version',
'nonunicodebrowser'         => "'''Wohrschau: Dien Browser kann keen Unicode, bruuk en annern Browser, wenn du en Siet ännern wist.'''",
'editingold'                => '<strong>Wohrscho: Du bearbeidst en ole Version vun disse Siet.
Wenn du spiekerst, warrn alle niegeren Versionen överschrieven.</strong>',
'yourdiff'                  => 'Ünnerscheed',
'copyrightwarning'          => 'Bitte pass op, dat all diene Bidrääg to de Wikipedia so ansehn warrt, dat se ünner de $2 staht (kiek op $1 för de Details). Wenn du nich willst, dat diene Bidrääg ännert un verdeelt warrt, denn schallst du hier man nix bidragen. Du seggst ok to, dat du dat hier sülvst schreven hest, oder dat du dat ut en fre’e Born (to’n Bispeel Gemeenfree oder so wat in disse Oort) kopeert hest.
<strong>Stell hier nix rin, wat ünner Oorheverrecht steiht, wenn de, de dat Oorheverrecht hett, di dorto keen Verlööf geven hett!</strong>',
'copyrightwarning2'         => "Dien Text, de du op {{SITENAME}} stellen wullst, könnt vun elkeen ännert oder wegmaakt warrn.
Wenn du dat nich wullst, dröffst du dien Text hier nich apentlich maken.<br />

Du bestätigst ok, dat du den Text sülvst schreven hest oder ut en „Public Domain“-Born oder en annere fre'e Born kopeert hest (Kiek ok $1 för Details).
<strong>Kopeer kene Warken, de enen Oorheverrecht ünnerliggt, ahn Verlööv vun de Copyright-Inhebbers!</strong>",
'longpagewarning'           => '<strong>Wohrscho: Disse Siet is $1 KB groot; en poor Browser köönt Probleme hebben, Sieten to bearbeiden, de grötter as 32 KB sünd.
Bedenk of disse Siet vilicht in lüttere Afsnitten opdeelt warrn kann.</strong>',
'longpageerror'             => "'''Fehler: Dien Text is $1 Kilobytes lang. Dat is länger as dat Maximum vun $2 Kilobytes. Kann den Text nich spiekern.'''",
'readonlywarning'           => '<strong>Wohrscho: De Datenbank is wiel dat Ännern vun de
Siet för Pleegarbeiden sparrt worrn, so dat du de Siet en Stoot nich
spiekern kannst. Seker di den Text un versöök later weer de Ännern to spiekern.</strong>',
'protectedpagewarning'      => '<strong>Wohrscho: Disse Siet is sparrt worrn, so dat blots
Bruker mit Sysop-Rechten doran arbeiden könnt.</strong>',
'semiprotectedpagewarning'  => "'''Henwies:''' Disse Siet is sparrt. Blots anmellt Brukers köönt de Siet ännern.",
'cascadeprotectedwarning'   => "'''Wohrschau:''' Disse Siet is so sparrt, dat blot Brukers mit Admin-Status ehr ännern köönt. Dat liggt dor an, dat se in disse {{PLURAL:$1|kaskadensparrte Siet|kaskadensparrten Sieden}} inbunnen is:",
'titleprotectedwarning'     => '<strong>WOHRSCHAU: Disse Siet is schuult, dat blot welk Brukergruppen ehr anleggen köönt.</strong>',
'templatesused'             => 'Vörlagen de in disse Siet bruukt warrt:',
'templatesusedpreview'      => 'Vörlagen de in disse Vörschau bruukt warrt:',
'templatesusedsection'      => 'Vörlagen de in dissen Afsnitt bruukt warrt:',
'template-protected'        => '(schuult)',
'template-semiprotected'    => '(half-schuult)',
'hiddencategories'          => 'Disse Siet steiht in {{PLURAL:$1|ene verstekene Kategorie|$1 verstekene Kategorien}}:',
'edittools'                 => '<!-- Disse Text warrt ünner de Finstern för dat Ännern un Hoochladen wiest. -->',
'nocreatetitle'             => 'Opstellen vun ne’e Sieden is inschränkt.',
'nocreatetext'              => '{{SITENAME}} verlööft di dat Opstellen vun ne’e Sieden nich. Du kannst blot Sieden ännern, de al dor sünd, oder du musst di [[Special:Userlogin|anmellen]].',
'nocreate-loggedin'         => 'Du hest keen Verlööf, op {{SITENAME}} ne’e Sieden antoleggen.',
'permissionserrors'         => 'Fehlers mit de Rechten',
'permissionserrorstext'     => 'Du hest keen Verlööf, dat to doon. De {{PLURAL:$1|Grund is|Grünn sünd}}:',
'recreate-deleted-warn'     => "'''Wohrschau: Du stellst jüst en Siet wedder nee op, de vördem al mal wegsmeten worrn is.'''

Överlegg genau, wat du würklich de Siet nee opstellen wist.
Dat du bescheed weetst, worüm de Siet vörher wegsmeten worrn is, hier nu de Uttog ut dat Lösch-Logbook:",

# "Undo" feature
'undo-summary' => 'Ännern $1 vun [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:User_talk}}:$2|Diskuschoon]]) trüchdreiht.',

# Account creation failure
'cantcreateaccounttitle' => 'Brukerkonto kann nich anleggt warrn',
'cantcreateaccount-text' => "Dat Opstellen vun Brukerkonten vun de IP-Adress '''$1''' ut is vun [[User:$3|$3]] sperrt worrn.

De Grund weer: ''$2''",

# History pages
'viewpagelogs'        => 'Logbook för disse Siet',
'nohistory'           => 'Disse Siet hett keen Vörgeschicht.',
'revnotfound'         => 'Kene fröheren Versionen funnen',
'revnotfoundtext'     => 'De Version vun disse Siet, no de du söökst, kunn nich funnen warrn. Prööv de URL vun disse Siet.',
'currentrev'          => 'Aktuelle Version',
'revisionasof'        => 'Version vun’n $1',
'revision-info'       => '<div id="viewingold-warning" style="background: #ffbdbd; border: 1px solid #BB7979; font-weight: bold; padding: .5em 1em;">
Dit is en ole Version vun disse Siet, so as <span id="mw-revision-name">$2</span> de <span id="mw-revision-date">$1</span> ännert hett. De Version kann temlich stark vun de <a href="{{FULLURL:{{FULLPAGENAME}}}}" title="{{FULLPAGENAME}}">aktuelle Version</a> afwieken.
</div>',
'previousrevision'    => 'Nächstöllere Version→',
'nextrevision'        => 'Ne’ere Version →',
'currentrevisionlink' => 'aktuelle Version',
'cur'                 => 'Aktuell',
'next'                => 'Tokamen',
'last'                => 'Letzte',
'page_first'          => 'Anfang',
'page_last'           => 'Enn',
'histlegend'          => "Ünnerscheed-Utwahl: De Boxen vun de wünschten
Versionen markeern un 'Enter' drücken oder den Knoop nerrn klicken/alt-v.<br />
Legende:
(Aktuell) = Ünnerscheed to de aktuelle Version,
(Letzte) = Ünnerscheed to de vörige Version,
L = Lütte Ännern",
'deletedrev'          => '[wegsmeten]',
'histfirst'           => 'Öllste',
'histlast'            => 'Ne’este',
'historysize'         => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'        => '(leddig)',

# Revision feed
'history-feed-title'          => 'Versionsgeschicht',
'history-feed-description'    => 'Versionsgeschicht för disse Siet',
'history-feed-item-nocomment' => '$1 üm $2', # user at time
'history-feed-empty'          => 'De angevene Siet gifft dat nich.
Villicht is se löscht worrn oder hett en annern Naam kregen.
Versöök [[Special:Search|dat Söken]] na annere relevante Sieden.',

# Revision deletion
'rev-deleted-comment'     => '(Kommentar rutnahmen)',
'rev-deleted-user'        => '(Brukernaam rutnahmen)',
'rev-deleted-event'       => '(Indrag rutnahmen)',
'rev-delundel'            => 'wiesen/versteken',
'revisiondelete'          => 'Versionen wegsmieten/wedderhalen',
'revdelete-nooldid-title' => 'kene Versionen dor, de passt',
'revdelete-selected'      => "{{PLURAL:$2|wählte Version|wählte Versionen}} vun '''$1:'''",
'logdelete-selected'      => "{{PLURAL:$2|wählt Logbook-Indrag|wählte Logbook-Indrääg}} för '''$1:'''",
'revdelete-legend'        => 'Inschränkungen setten:',
'revdelete-hide-text'     => 'Versiontext versteken',
'revdelete-hide-name'     => 'Akschoon un Teel versteken',
'revdelete-hide-comment'  => 'Kommentar versteken',
'revdelete-hide-user'     => 'Brukernaam/IP vun’n Schriever versteken',
'revdelete-hide-image'    => 'Dateiinholt versteken',
'revdelete-log'           => 'Kommentar:',
'revdelete-logentry'      => 'Sichtborkeit vun Version för [[$1]] ännert',
'logdelete-logentry'      => 'Sichtborkeit vun Begeevnis för [[$1]] ännert',
'pagehist'                => 'Versionshistorie',
'deletedhist'             => 'wegsmetene Versionen',

# Oversight log
'oversightlog' => 'Opsicht-Logbook',

# History merging
'mergehistory'                     => 'Versionshistorien tohoopföhren',
'mergehistory-box'                 => 'Versionshistorien vun twee Sieden tohoopföhren',
'mergehistory-from'                => 'Bornsiet:',
'mergehistory-into'                => 'Teelsiet:',
'mergehistory-list'                => 'Versionen, de tohoopföhrt warrn köönt',
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

# Merge log
'mergelog'           => 'Tohoopföhr-Logbook',
'pagemerge-logentry' => '[[$1]] mit [[$2]] tohoopföhrt (Versionen bet $3)',
'revertmerge'        => 'Tohoopbringen trüchdreihn',
'mergelogpagetext'   => 'Dit is dat Logbook över de tohoopföhrten Versionshistorien.',

# Diffs
'history-title'           => 'Versionshistorie vun „$1“',
'difference'              => '(Ünnerscheed twischen de Versionen)',
'lineno'                  => 'Reeg $1:',
'compareselectedversions' => 'Ünnerscheed twischen den utwählten Versionen wiesen',
'editundo'                => 'rutnehmen',
'diff-multi'              => '(Twischen de beiden Versionen {{plural:$1|liggt noch ene Twischenversion|doot noch $1 Twischenversionen liggen}}.)',

# Search results
'searchresults'         => 'Söökresultaten',
'searchresulttext'      => 'För mehr Informatschonen över {{SITENAME}}, kiek [[{{MediaWiki:Helppage}}|{{SITENAME}} dörsöken]].',
'searchsubtitle'        => 'För de Söökanfraag „[[:$1]]“',
'searchsubtitleinvalid' => 'För de Söökanfraag „$1“',
'noexactmatch'          => 'Gifft kene Siet mit dissen Naam. Bruuk de Vulltextsöök oder legg de Siet [[:$1|nee]] an.',
'noexactmatch-nocreate' => "'''Gifft kene Siet mit’n Titel „$1“.'''",
'toomanymatches'        => 'To veel Sieden funnen för de Söök, versöök en annere Affraag.',
'titlematches'          => 'Övereenstimmen mit Överschriften',
'notitlematches'        => 'Kene Övereenstimmen',
'textmatches'           => 'Övereenstimmen mit Texten',
'notextmatches'         => 'Kene Övereenstimmen',
'prevn'                 => 'vörige $1',
'nextn'                 => 'tokamen $1',
'viewprevnext'          => 'Wies ($1) ($2) ($3).',
'search-result-size'    => '$1 ({{PLURAL:$2|een Woort|$2 Wöör}})',
'search-result-score'   => 'Relevanz: $1 %',
'search-redirect'       => '(Redirect $1)',
'search-section'        => '(Afsnitt $1)',
'searchall'             => 'all',
'showingresults'        => "Hier {{PLURAL:$1|is een Resultat|sünd '''$1''' Resultaten}}, anfungen mit #'''$2'''.",
'showingresultsnum'     => "Hier {{PLURAL:$3|is een Resultat|sünd '''$3''' Resultaten}}, anfungen mit #'''$2'''.",
'showingresultstotal'   => "Dit sünd de Fundstellen '''$1–$2''' vun '''$3'''",
'nonefound'             => '<strong>Henwies</strong>:
Söökanfragen ahn Spood hebbt faken de Oorsaak, dat no kotte oder gemeene Wöör söökt warrt, de nich indizeert sünd.',
'powersearch'           => 'Betere Söök',
'powersearch-legend'    => 'Betere Söök',
'powersearchtext'       => '
Söök in Naamrüüm:<br />


$1<br />
$2 Wies ok Wiederleiden   Söök no $3 $9',
'searchdisabled'        => '<p>De Vulltextsöök is wegen Överlast en Stoot deaktiveert. In disse Tied kannst du disse Google-Söök verwennen,
de aver nich jümmer den aktuellsten Stand weerspegelt.<p>',

# Preferences page
'preferences'              => 'Instellen',
'mypreferences'            => 'För mi Instellen',
'prefs-edits'              => 'Wo faken du in dit Wiki Sieden ännert hest:',
'prefsnologin'             => 'Nich anmellt',
'prefsnologintext'         => 'Du musst [[{{ns:special}}:Userlogin|anmellt]] wesen, üm dien Instellen to ännern.',
'prefsreset'               => 'Instellen sünd op Standard trüchsett.',
'qbsettings'               => 'Siedenliest',
'qbsettings-none'          => 'Keen',
'qbsettings-fixedleft'     => 'Links, fast',
'qbsettings-fixedright'    => 'Rechts, fast',
'qbsettings-floatingleft'  => 'Links, sweven',
'qbsettings-floatingright' => 'Rechts, sweven',
'changepassword'           => 'Passwoort ännern',
'skin'                     => 'Utsehn vun de Steed',
'math'                     => 'TeX',
'dateformat'               => 'Datumsformat',
'datedefault'              => 'Standard',
'datetime'                 => 'Datum un Tiet',
'math_failure'             => 'Parser-Fehler',
'math_unknown_error'       => 'Unbekannten Fehler',
'math_unknown_function'    => 'Unbekannte Funktschoon',
'math_lexing_error'        => "'Lexing'-Fehler",
'math_syntax_error'        => 'Syntaxfehler',
'math_image_error'         => 'dat Konverteren na PNG harr keen Spood.',
'math_bad_tmpdir'          => 'Kann dat Temporärverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_bad_output'          => 'Kann dat Teelverteken för mathematsche Formeln nich anleggen oder beschrieven.',
'math_notexvc'             => 'Dat texvc-Programm kann nich funnen warrn. Kiek ok math/README.',
'prefs-personal'           => 'Brukerdaten',
'prefs-rc'                 => 'Letzte Ännern un Wiesen vun kotte Sieten',
'prefs-watchlist'          => 'Oppasslist',
'prefs-watchlist-days'     => 'Maximumtall Daag, de in de Oppasslist wiest warrt:',
'prefs-watchlist-edits'    => 'Maximumtall Daag, de in de verwiederte Oppasslist wiest warrt:',
'prefs-misc'               => 'Verscheden Instellen',
'saveprefs'                => 'Instellen spiekern',
'resetprefs'               => 'Instellen trüchsetten',
'oldpassword'              => 'Oolt Passwoort:',
'newpassword'              => 'Nee Passwoort',
'retypenew'                => 'Nee Passwoort (nochmal)',
'textboxsize'              => 'Textfeld-Grött',
'rows'                     => 'Regen',
'columns'                  => 'Spalten',
'searchresultshead'        => 'Söökresultaten',
'resultsperpage'           => 'Treffer pro Siet',
'contextlines'             => 'Lienen pro Treffer',
'contextchars'             => 'Teken je Reeg',
'stub-threshold'           => 'Grött ünner de Lenken op <a href="#" class="stub">Stubbens un lütte Sieden</a> farvlich kenntekent warrn schöölt (in Bytes):',
'recentchangesdays'        => 'Daag, de de List vun de „Ne’esten Ännern“ wiesen schall:',
'recentchangescount'       => 'Antall „Letzte Ännern“',
'savedprefs'               => 'Dien Instellen sünd spiekert.',
'timezonelegend'           => 'Tietrebeet',
'timezonetext'             => 'Giff de Antall vun de Stünnen an, de twüschen dien Tiedrebeet un UTC liggen.',
'localtime'                => 'Oortstied',
'timezoneoffset'           => 'Ünnerscheed',
'servertime'               => 'Tiet op den Server',
'guesstimezone'            => 'Ut den Browser övernehmen',
'allowemail'               => 'Nettbreven vun annere Brukers annehmen',
'defaultns'                => 'In disse Naamrüüm schall standardmatig söökt warrn:',
'default'                  => 'Standard',
'files'                    => 'Datein',

# User rights
'userrights'                       => 'Brukerrechten inrichten', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => 'Brukergruppen verwalten',
'userrights-user-editname'         => 'Brukernaam ingeven:',
'editusergroup'                    => 'Brukergruppen bearbeiden',
'editinguser'                      => "Ännern vun Brukerrechten vun '''[[{{ns:User}}:$1|$1]]''' ([[{{ns:User talk}}:$1|{{int:talkpagelinktext}}]] | [[{{ns:Special}}:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'         => 'Brukergruppen ännern',
'saveusergroups'                   => 'Brukergruppen spiekern',
'userrights-groupsmember'          => 'Liddmaat vun:',
'userrights-groupsremovable'       => 'Gruppen, de wegnahmen warrn köönt:',
'userrights-groupsavailable'       => 'vörhannene Gruppen:',
'userrights-groupshelp'            => "Wähl de Gruppen ut, ut de de Bruker rutnahmen oder toföögt warrn schall. Nich utwählte Gruppen warrt ok nich ännert. En Utwahl kann mit '''Strg + Linksklick''' (oder Ctrl + Linksklick) wegnahmen warrn.",
'userrights-reason'                => 'Grund:',
'userrights-available-none'        => 'Du dröffst kene Brukerrechten ännern.',
'userrights-available-add'         => 'Du kannst Brukers to disse Grupp{{PLURAL:$2||en}} tofögen: $1.',
'userrights-available-remove'      => 'Du kannst Brukers ut disse Grupp{{PLURAL:$2||en}} rutnehmen: $1.',
'userrights-available-add-self'    => 'Du kannst di sülvst to {{PLURAL:$2|disse Grupp|disse Gruppen}} tofögen: $1.',
'userrights-available-remove-self' => 'Du kannst di sülvst ut {{PLURAL:$2|disse Grupp|disse Gruppen}} rutnehmen: $1.',
'userrights-no-interwiki'          => 'Du hest nich de Rechten, Brukerrechten in annere Wikis to setten.',
'userrights-nodatabase'            => 'Datenbank $1 gifft dat nich oder is nich lokal.',
'userrights-nologin'               => 'Du musst mit en Administrater-Brukerkonto [[Special:Userlogin|anmellt]] wesen, dat du Brukerrechten ännern kannst.',
'userrights-notallowed'            => 'Du hest nich de Rechten, Brukerrechten to setten.',

# Groups
'group'               => 'Grupp:',
'group-autoconfirmed' => 'Bestätigte Brukers',
'group-bot'           => 'Bots',
'group-sysop'         => 'Admins',
'group-bureaucrat'    => 'Bürokraten',
'group-all'           => '(all)',

'group-autoconfirmed-member' => 'Bestätigt Bruker',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Admin',
'group-bureaucrat-member'    => 'Bürokraat',

'grouppage-autoconfirmed' => '{{ns:project}}:Bestätigte Brukers',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administraters',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',

# User rights log
'rightslog'      => 'Brukerrechten-Logbook',
'rightslogtext'  => 'In dit Logbook staht Ännern an de Brukerrechten.',
'rightslogentry' => 'Grupp bi $1 vun $2 op $3 ännert.',
'rightsnone'     => '(kene)',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|Een Ännern|$1 Ännern}}',
'recentchanges'                     => 'Niegest Ännern',
'recentchangestext'                 => '
Disse Siet warrt wiel dat Laden automatsch aktualiseert. Wiest warrn Sieten, de toletzt bearbeid worrn sünd, dorto de Tied un de Naam vun de Autor.',
'recentchanges-feed-description'    => 'Behool mit dissen Feed de ne’esten Ännern op dit Wiki in’t Oog.',
'rcnote'                            => "Hier sünd de letzten '''$1''' Ännern vun de letzten {{PLURAL:$2|Dag|'''$2''' Daag}} (Stand $3). ('''N''' - Ne’e Sieden; '''L''' - Lütte Ännern)",
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

# Recent changes linked
'recentchangeslinked'          => 'Ännern an lenkte Sieden',
'recentchangeslinked-title'    => 'Ännern an Sieden, de vun „$1“ ut lenkt sünd',
'recentchangeslinked-noresult' => 'In disse Tiet hett nüms de lenkten Sieden ännert.',
'recentchangeslinked-summary'  => "Disse Spezialsiet wiest de letzten Ännern an de lenkten Sieden. Sieden, de op diene Oppasslist staht, sünd '''fett''' schreven.",

# Upload
'upload'                     => 'Hoochladen',
'uploadbtn'                  => 'Datei hoochladen',
'reupload'                   => 'Nee hoochladen',
'reuploaddesc'               => 'Trüch to de Hoochladen-Siet.',
'uploadnologin'              => 'Nich anmellt',
'uploadnologintext'          => 'Du musst [[Special:Userlogin|anmellt wesen]], dat du Datein hoochladen kannst.',
'upload_directory_read_only' => 'De Server kann nich in’n Orner för dat Hoochladen vun Datein ($1) schrieven.',
'uploaderror'                => 'Fehler bi dat Hoochladen',
'uploadtext'                 => "
Üm hoochladene Biller to söken un antokieken,
geih to de [[Special:Imagelist|List vun hoochladene Biller]].

Bruuk dat Formular, üm niege Biller hoochtoladen un disse in Sieten to bruken.
In de mehrsten Browser warrt en „Durchsuchen“-Feld wiest, dat en Standard-Dateidialog apent.
Wähl de Datei ut, de du hoochladen wullst. De Dateinaam warrt denn in dat Textfeld wiest.
Bestätig dann den Copyright-Henwies.
Toletzt muttst du den „Hoochladen“-Knopp klicken.
Dat kann en Stoot duern, sünnerlich bi en langsamen Internet-Verbinnen.

För Fotos is dat JPEG-Format, för Grafiken un Symbolen dat PNG-Format best.
Üm en Bild in en Siet to bruken, schriev an Stell vun dat Bild
'''[[Image:datei.jpg]]''' oder
'''[[Image:datei.jpg|Beschrieven]]'''.

Denk doran, dat, lieks as bi de annern Sieten, annere Bruker dien Datein löschen oder ännern könen.',
'uploadlog'                => 'Datei-Logbook',
'uploadlogpage'     => 'Datei-Logbook',
'uploadlogpagetext' => 'Hier is de List vun de letzten hoochladenen Datein.
Alle Tieden sünd UTC.",
'upload-permitted'           => 'Verlöövte Dateitypen: $1.',
'upload-preferred'           => 'Vörtagene Dateitypen: $1.',
'upload-prohibited'          => 'Verbadene Dateitypen: $1.',
'uploadlog'                  => 'Hoochlade-Logbook',
'uploadlogpage'              => 'Hoochlade-Logbook',
'uploadlogpagetext'          => 'Ünnen steiht de List vun de ne’esten hoochladenen Datein.',
'filename'                   => 'Dateinaam',
'filedesc'                   => 'Beschrieven',
'fileuploadsummary'          => 'Tosamenfaten:',
'filestatus'                 => 'Copyright-Status:',
'filesource'                 => 'Born:',
'uploadedfiles'              => 'Hoochladene Datein',
'ignorewarning'              => 'Schiet op dat Wohrschauel un Datei spiekern',
'ignorewarnings'             => 'Schiet op all Wohrschauen',
'minlength1'                 => 'Dateinaams mööt opminnst een Teken lang wesen.',
'illegalfilename'            => 'In den Dateinaam „$1“ snd Teken in, de nich de Naams vun Sieden nich verlööft sünd. Söök di en annern Naam ut un denn versöök de Datei noch wedder hoochtoladen.',
'badfilename'                => 'De Bildnaam is na „$1“ ännert worrn.',
'filetype-badmime'           => 'Datein vun den MIME-Typ „$1“ dröfft nich hoochlaadt warrn.',
'filetype-unwanted-type'     => "'''„.$1“''' as Dateiformat schall beter nich bruukt warrn. Verlöövt sünd: $2.",
'filetype-banned-type'       => "'''„.$1“''' as Dateiformat is nich tolaten. Verlöövt sünd: $2.",
'filetype-missing'           => 'Disse Datei hett keen Ennen (so as „.jpg“).',
'large-file'                 => 'Datein schöölt opbest nich grötter wesen as $1. Disse Datei is $2 groot.',
'largefileserver'            => 'De Datei is grötter as de vun’n Server verlöövte Bövergrenz för de Grött.',
'emptyfile'                  => 'De hoochladene Datei is leddig. De Grund kann en Tippfehler in de Dateinaam ween. Kontrolleer, of du de Datei redig hoochladen wullst.',
'fileexists'                 => 'En Datei mit dissen Naam existeert al, prööv $1, wenn du di nich seker büst of du dat ännern wullst.',
'fileexists-thumb'           => "<center>'''Vörhannene Datei'''</center>",
'fileexists-forbidden'       => 'En Datei mit dissen Naam gifft dat al; gah trüch un laad de Datei ünner en annern Naam hooch. [[Image:$1|thumb|center|$1]]',
'successfulupload'           => 'Datei hoochladen hett Spood',
'uploadwarning'              => 'Wohrschau',
'savefile'                   => 'Datei spiekern',
'uploadedimage'              => '„$1“ hoochladen',
'overwroteimage'             => 'Ne’e Version vun „[[$1]]“ hoochlaadt',
'uploaddisabled'             => 'Dat Hoochladen is deaktiveert.',
'uploaddisabledtext'         => 'Op {{SITENAME}} is dat Hoochladen vun Datein utschalt.',
'uploadscripted'             => 'In disse Datei steiht HTML- oder Skriptkood in, de vun welk Browsers verkehrt dorstellt oder utföhrt warrn kann.',
'uploadcorrupt'              => 'De Datei is korrupt oder hett en falsch Ennen. Datei pröven un nieg hoochladen.',
'uploadvirus'                => 'In de Datei stickt en Virus! Mehr: $1',
'sourcefilename'             => 'Dateinaam op dien Reekner:',
'destfilename'               => 'Dateinaam, so as dat hier spiekert warrn schall:',
'watchthisupload'            => 'Op disse Siet oppassen',

'upload-proto-error'     => 'Verkehrt Protokoll',
'upload-file-error'      => 'Internen Fehler',
'upload-file-error-text' => 'An internal error occurred when attempting to create a temporary file on the server.  Please contact a system administrator.
Dat geev en internen Fehler bi dat Anleggen vun en temporäre Datei op’n Server. Segg man en System-Administrater bescheed.',
'upload-misc-error'      => 'Unbekannt Fehler bi dat Hoochladen',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'Kunn de URL nich kriegen',
'upload-curl-error28' => 'Tied-Ut bi dat Hoochladen',

'license'            => 'Lizenz:',
'nolicense'          => 'nix utwählt',
'license-nopreview'  => '(Vörschau nich mööglich)',
'upload_source_url'  => ' (gellen, apen togängliche URL)',
'upload_source_file' => ' (en Datei op dien Reekner)',

# Special:Imagelist
'imagelist_search_for'  => 'Söök na Datei:',
'imgdesc'               => 'Beschrieven',
'imgfile'               => 'Datei',
'imagelist'             => 'Billerlist',
'imagelist_date'        => 'Datum',
'imagelist_name'        => 'Naam',
'imagelist_user'        => 'Bruker',
'imagelist_size'        => 'Grött (Bytes)',
'imagelist_description' => 'Beschrieven',

# Image description page
'filehist'                  => 'Datei-Historie',
'filehist-help'             => 'Klick op de Tiet, dat du de Datei ankieken kannst, so as se do utseeg.',
'filehist-deleteall'        => 'all wegsmieten',
'filehist-deleteone'        => 'dit wegsmieten',
'filehist-revert'           => 'Trüchsetten',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Datum/Tiet',
'filehist-user'             => 'Bruker',
'filehist-dimensions'       => 'Grött',
'filehist-filesize'         => 'Dateigrött',
'filehist-comment'          => 'Kommentar',
'imagelinks'                => 'Bildverwiesen',
'linkstoimage'              => 'Disse Sieden bruukt dit Bild:',
'nolinkstoimage'            => 'Kene Siet bruukt dat Bild.',
'sharedupload'              => 'Disse Datei is as gemeensam bruukte Datei hoochlaadt un warrt mööglicherwies ok vun annere Wikis bruukt.',
'shareduploadwiki'          => 'Kiek bi $1 för mehr Informatschoon.',
'shareduploadwiki-desc'     => 'Wat nu kummt is de Text vun de $1.',
'shareduploadwiki-linktext' => 'Siet mit de Datei-Beschrievung',
'noimage'                   => 'Ene Datei mit dissen Naam gifft dat nich, du kannst ehr $1.',
'noimage-linktext'          => 'Hoochladen',
'uploadnewversion-linktext' => 'Ne’e Version vun disse Datei hoochladen',
'imagepage-searchdupe'      => 'Söök na dubbelte Datein',

# File reversion
'filerevert'                => '„$1“ Trüchsetten',
'filerevert-legend'         => 'Datei trüchsetten',
'filerevert-comment'        => 'Kommentar:',
'filerevert-defaultcomment' => 'trüchsett op de Version vun’n $1, $2',
'filerevert-submit'         => 'Trüchsetten',

# File deletion
'filedelete'                  => '$1 wegsmieten',
'filedelete-legend'           => 'Datei wegsmieten',
'filedelete-intro'            => "Du smittst '''[[Media:$1|$1]]''' weg.",
'filedelete-comment'          => 'Kommentar:',
'filedelete-submit'           => 'Wegsmieten',
'filedelete-success'          => "'''$1''' wegsmeten.",
'filedelete-nofile'           => "'''$1''' gifft dat nich op {{SITENAME}}.",
'filedelete-otherreason'      => 'Annern/tosätzlichen Grund:',
'filedelete-reason-otherlist' => 'Annern Grund',
'filedelete-reason-dropdown'  => '* Faken bruukte Grünn
** Verstoot gegen Oorheverrecht
** dubbelt vörhannen',
'filedelete-edit-reasonlist'  => 'Grünn för’t Wegsmieten bearbeiden',

# MIME search
'mimesearch' => 'MIME-Söök',
'mimetype'   => 'MIME-Typ:',
'download'   => 'Dalladen',

# Unwatched pages
'unwatchedpages' => 'Sieden, de op kene Oppasslist staht',

# List redirects
'listredirects' => 'List vun Redirects',

# Unused templates
'unusedtemplates'    => 'Nich bruukte Vörlagen',
'unusedtemplateswlh' => 'Annere Lenken',

# Random page
'randompage'         => 'Tofällige Siet',
'randompage-nopages' => 'Gifft kene Sieden in dissen Naamruum.',

# Random redirect
'randomredirect'         => 'Tofällig Redirect',
'randomredirect-nopages' => 'Gifft kene Redirects in dissen Naamruum.',

# Statistics
'statistics'             => 'Statistik',
'sitestats'              => 'Sietenstatistik',
'userstats'              => 'Brukerstatistik',
'sitestatstext'          => "Dat gifft allens tosamen {{PLURAL:$1|ene Siet|'''$1''' Sieden}} in de Datenbank.
Dat slött Diskuschoonsieden, Sieden över {{SITENAME}}, bannig korte Sieden, Wiederleiden un annere Sieden in, de nich as richtige Sieden gellen köönt.
Disse utnahmen, gifft dat {{PLURAL:$2|ene Siet, de as Artikel gellen kann|'''$2''' Sieden, de as Artikels gellen köönt}}.

'''$8''' hoochladene {{PLURAL:$8|Datei|Datein}} gifft dat.

De Lüüd hebbt {{PLURAL:$3|ene Siet|'''$3'''× Sieden}} opropen, un {{PLURAL:$4|ene Siet ännert|'''$4'''× Sieden ännert}}.
Dat heet, jede Siet is '''$5''' Maal ännert un '''$6''' maal ankeken worrn.

De List, mit de Opgaven, de de Software noch maken mutt, hett {{PLURAL:$7|een Indrag|'''$7''' Indrääg}}.",
'userstatstext'          => "Dat gifft {{PLURAL:$1|'''een''' anmellt Bruker|'''$1''' anmellt Brukers}}.
Dorvun {{PLURAL:$2|hett '''een'''|hebbt '''$2'''}} {{PLURAL:$1||($4 %)}} $5-Rechten (kiek $3).",
'statistics-mostpopular' => 'opmehrst ankekene Sieden',

'disambiguations'     => 'Mehrdüdige Begrepen',
'disambiguationspage' => 'Template:Mehrdüdig_Begreep',

'doubleredirects'     => 'Dubbelte Wiederleiden',
'doubleredirectstext' => '<b>Wohrscho:</b> Disse List kann „falsche Positive“ bargen.
Dat passeert denn, wenn en Wiederleiden blangen de Wiederleiden-Verwies noch mehr Text mit annere Verwiesen hett.
De schallen denn löscht warrn. Elk Reeg wiest de eerste un tweete Wiederleiden un de eerste Reeg Text ut de Siet,
to den vun den tweeten Wiederleiden wiest warrt, un to den de eerste Wiederleiden mehrst wiesen schall.',

'brokenredirects'        => 'Kaputte Wiederleiden',
'brokenredirectstext'    => 'Disse Wiederleiden wiest na en Siet, de dat nich gifft.',
'brokenredirects-edit'   => '(ännern)',
'brokenredirects-delete' => '(wegsmieten)',

'withoutinterwiki'        => 'Sieden ahn Spraaklenken',
'withoutinterwiki-header' => 'Disse Sieden hebbt keen Lenken na annere Spraakversionen:',
'withoutinterwiki-submit' => 'Wies',

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
'lonelypagestext'         => 'Op disse Sieden wiest kene annern Sieden vun {{SITENAME}}.',
'uncategorizedpages'      => 'Sieden ahn Kategorie',
'uncategorizedcategories' => 'Kategorien ahn Kategorie',
'uncategorizedimages'     => 'Datein ahn Kategorie',
'uncategorizedtemplates'  => 'Vörlagen ahn Kategorie',
'unusedcategories'        => 'Kategorien ahn insorteerte Artikels oder Ünnerkategorien',
'unusedimages'            => 'Weetbiller',
'popularpages'            => 'Faken opropene Sieden',
'wantedcategories'        => 'Kategorien, de veel bruukt warrt, aver noch keen Text hebbt (nich anleggt sünd)',
'wantedpages'             => 'Wünschte Sieden',
'mostlinked'              => 'Sieden, op de vele Lenken wiest',
'mostlinkedcategories'    => 'Kategorien, op de vele Lenken wiest',
'mostlinkedtemplates'     => 'Vörlagen, op de vele Lenken wiest',
'mostcategories'          => 'Artikels mit vele Kategorien',
'mostimages'              => 'Datein, de veel bruukt warrt',
'mostrevisions'           => 'Sieden mit de mehrsten Versionen',
'prefixindex'             => 'All Sieden (mit Präfix)',
'shortpages'              => 'Korte Sieden',
'longpages'               => 'Lange Sieden',
'deadendpages'            => 'Sackstraatsieden',
'deadendpagestext'        => 'Disse Sieden wiest op kene annern Sieden vun {{SITENAME}}.',
'protectedpages'          => 'Schuulte Sieden',
'protectedpagestext'      => 'Disse Sieden sünd vör dat Schuven oder Ännern schuult',
'protectedpagesempty'     => 'Opstunns sünd kene Sieden schuult',
'protectedtitles'         => 'Sparrte Sieden',
'listusers'               => 'Brukerlist',
'specialpages'            => 'Sünnerliche Sieden',
'spheading'               => 'Spezialsieden för all Brukers',
'restrictedpheading'      => 'Spezialsieden mit inschränkt Togang',
'newpages'                => 'Ne’e Sieden',
'newpages-username'       => 'Brukernaam:',
'ancientpages'            => 'Öllste Sieden',
'move'                    => 'Schuven',
'movethispage'            => 'Siet schuven',
'unusedimagestext'        => 'Denk doran, dat annere Wikis mööglicherwies en poor vun disse Biller bruken.',
'unusedcategoriestext'    => 'Disse Kategorien sünd leddig, keen Artikel un kene Ünnerkategorie steiht dor in.',
'notargettitle'           => 'Kene Siet angeven',
'notargettext'            => 'Du hest nich angeven, op welke Siet du disse Funktschoon anwennen willst.',
'pager-newer-n'           => '{{PLURAL:$1|nächste|nächste $1}}',
'pager-older-n'           => '{{PLURAL:$1|vörige|vörige $1}}',

# Book sources
'booksources'               => 'Bookhannel',
'booksources-search-legend' => 'Na Böker bi Bookhökers söken',
'booksources-go'            => 'Los',
'booksources-text'          => 'Hier staht Lenken na Websteden, woneem dat Böker to köpen gifft, de mitünner ok mehr Informatschonen to dat Book anbeden doot:',

# Special:Log
'specialloguserlabel'  => 'Bruker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logböker',
'all-logs-page'        => 'All Logböker',
'log-search-legend'    => 'Na Logbook-Indrääg söken',
'log-search-submit'    => 'Los',
'alllogstext'          => 'Kombineerte Ansicht vun all Logböker bi {{SITENAME}}.
Du kannst de List körter maken, wenn du den Logbook-Typ, den Brukernaam oder de Siet angiffst.',
'logempty'             => 'In’e Logböker nix funnen, wat passt.',
'log-title-wildcard'   => 'Titel fangt an mit …',

# Special:Allpages
'allpages'          => 'Alle Sieden',
'alphaindexline'    => '$1 bet $2',
'nextpage'          => 'tokamen Siet ($1)',
'prevpage'          => 'Vörige Siet ($1)',
'allpagesfrom'      => 'Sieden wiesen, de mit disse Bookstaven anfangt:',
'allarticles'       => 'Alle Artikels',
'allinnamespace'    => 'Alle Sieden (Naamruum $1)',
'allnotinnamespace' => 'Alle Sieden (nich in Naamruum $1)',
'allpagesprev'      => 'vörig',
'allpagesnext'      => 'tokamen',
'allpagessubmit'    => 'Los',
'allpagesprefix'    => 'Sieden wiesen, de anfangt mit:',
'allpages-bad-ns'   => '{{SITENAME}} hett keen Naamruum „$1“.',

# Special:Listusers
'listusersfrom'      => 'Wies de Brukers, de anfangt mit:',
'listusers-submit'   => 'Wiesen',
'listusers-noresult' => 'Keen Bruker funnen.',

# E-mail user
'mailnologin'     => 'Du büst nich anmellt.',
'mailnologintext' => 'Du musst [[Spezial:Userlogin|anmellt wesen]] un en güllige E-Mail-Adress hebben, dat du en annern Bruker en E-Mail sennen kannst.',
'emailuser'       => 'E-Mail an dissen Bruker',
'emailpage'       => 'E-Mail an Bruker',
'emailpagetext'   => 'Wenn disse Bruker en güllige E-Mail-Adress angeven hett, kannst du em mit den nerrn stahn Formular en E-Mail sennen. As Afsenner warrt de E-Mail-Adress ut dien Instellen indregen, dormit de Bruker di antern kann.',
'usermailererror' => 'Dat Mail-Objekt hett en Fehler trüchgeven:',
'defemailsubject' => '{{SITENAME}} E-Mail',
'noemailtitle'    => 'Kene E-Mail-Adress',
'noemailtext'     => 'Disse Bruker hett kene güllige E-Mail-Adress angeven, oder will kene E-Mail vun annere Bruker sennt kriegen.',
'emailfrom'       => 'Vun',
'emailto'         => 'An',
'emailsubject'    => 'Bedrap',
'emailmessage'    => 'Naricht',
'emailsend'       => 'Sennen',
'emailccme'       => 'Ik will en Kopie vun mien Naricht an mien egen E-Mail-Adress hebben.',
'emailccsubject'  => 'Kopie vun diene Naricht an $1: $2',
'emailsent'       => 'E-Mail afsennt',
'emailsenttext'   => 'Dien E-Mail is afsennt worrn.',

# Watchlist
'watchlist'            => 'Mien Oppasslist',
'mywatchlist'          => 'Mien Oppasslist',
'watchlistfor'         => "(för '''$1''')",
'nowatchlist'          => 'Du hest kene Indreeg op dien Oppasslist.',
'watchlistanontext'    => '$1, dat du dien Oppasslist ankieken oder ännern kannst.',
'watchnologin'         => 'Du büst nich anmellt',
'watchnologintext'     => 'Du must [[Spezial:Userlogin|anmellt]] wesen, wenn du dien Oppasslist ännern willst.',
'addedwatch'           => 'To de Oppasslist toföögt',
'addedwatchtext'       => 'De Siet „<nowiki>$1</nowiki>“ is to diene [[Special:Watchlist|Oppasslist]] toföögt worrn.
Ännern, de in Tokumst an disse Siet un an de tohörige Diskuschoonssiet maakt warrt, sünd dor op oplist un de Siet is op de [[Special:Recentchanges|List vun de letzten Ännern]] fett markt. Wenn du de Siet nich mehr op diene Oppasslist hebben willst, klick op „Nich mehr oppassen“.',
'removedwatch'         => 'De Siet is nich mehr op de Oppasslist',
'removedwatchtext'     => 'De Siet „<nowiki>$1</nowiki>“ is nich mehr op de Oppasslist.',
'watch'                => 'Oppassen',
'watchthispage'        => 'Op disse Siet oppassen',
'unwatch'              => 'nich mehr oppassen',
'unwatchthispage'      => 'Nich mehr oppassen',
'notanarticle'         => 'Keen Artikel',
'notvisiblerev'        => 'Version wegsmeten',
'watchnochange'        => 'Kene Siet op dien Oppasslist is in den wiesten Tietruum ännert worrn.',
'watchlist-details'    => '{{PLURAL:$1|Ene Siet is|$1 Sieden sünd}} op de Oppasslist (ahn Diskuschoonssieden).',
'wlheader-enotif'      => 'Benarichtigen per E-Mail is anstellt.',
'wlheader-showupdated' => "* Sieden, de ännert worrn sünd siet dien letzten Besöök, warrt '''fett''' dorstellt.",
'watchmethod-recent'   => 'letzte Ännern no Oppasslist pröven',
'watchmethod-list'     => 'Oppasslist na letzte Ännern nakieken',
'watchlistcontains'    => 'Diene Oppasslist bargt {{PLURAL:$1|ene Siet|$1 Sieden}}.',
'iteminvalidname'      => "Problem mit den Indrag '$1', ungülligen Naam...",
'wlnote'               => "Ünnen {{PLURAL:$1|steiht de letzte Ännern|staht de letzten $1 Ännern}} vun de {{PLURAL:$2|letzte Stünn|letzten '''$2''' Stünnen}}.",
'wlshowlast'           => 'Wies de letzten $1 Stünnen $2 Daag $3',
'watchlist-show-bots'  => 'Ännern vun Bots wiesen',
'watchlist-hide-bots'  => 'Ännern vun Bots versteken',
'watchlist-show-own'   => 'Miene Ännern wiesen',
'watchlist-hide-own'   => 'Miene Ännern versteken',
'watchlist-show-minor' => 'Lütte Ännern wiesen',
'watchlist-hide-minor' => 'Lütte Ännern versteken',

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

# Delete/protect/revert
'deletepage'                  => 'Siet wegsmieten',
'confirm'                     => 'Bestätigen',
'excontent'                   => 'Olen Inholt: ‚$1‘',
'excontentauthor'             => 'Inholt weer: ‚$1‘ (un de eenzige Autor weer ‚[[Special:Contributions/$2|$2]]‘)',
'exbeforeblank'               => 'Inholt vör dat Leddigmaken vun de Siet: ‚$1‘',
'exblank'                     => 'Siet weer leddig',
'delete-confirm'              => '„$1“ wegsmieten',
'delete-legend'               => 'Wegsmieten',
'historywarning'              => 'Wohrschau: De Siet, de du bi büst to löschen, hett en Versionshistorie:',
'confirmdeletetext'           => 'Du büst dorbi, en Siet oder en Bild un alle ölleren Versionen duersam ut de Datenbank to löschen.
Segg to, dat du över de Folgen Bescheed weetst un dat du in Övereenstimmen mit uns [[{{MediaWiki:Policy-url}}|Leidlienen]] hannelst.',
'actioncomplete'              => 'Akschoon trech',
'deletedtext'                 => 'De Artikel „<nowiki>$1</nowiki>“ is nu wegsmeten. Op $2 gifft dat en Logbook vun de letzten Löschakschonen.',
'deletedarticle'              => '„$1“ wegsmeten',
'dellogpage'                  => 'Lösch-Logbook',
'dellogpagetext'              => 'Hier is en List vun de letzten Löschen (UTC).',
'deletionlog'                 => 'Lösch-Logbook',
'reverted'                    => 'Op en ole Version trüchsett',
'deletecomment'               => 'Grund för dat Wegsmieten:',
'deleteotherreason'           => 'Annere/tosätzliche Grünn:',
'deletereasonotherlist'       => 'Annern Grund',
'deletereason-dropdown'       => '* Grünn för dat Wegsmieten
** op Wunsch vun’n Schriever
** gegen dat Oorheverrecht
** Vandalismus',
'delete-edit-reasonlist'      => 'Grünn för’t Wegsmieten ännern',
'rollback'                    => 'Trüchnahm vun de Ännern',
'rollback_short'              => 'Trüchnehmen',
'rollbacklink'                => 'Trüchnehmen',
'rollbackfailed'              => 'Trüchnahm hett kenen Spood',
'cantrollback'                => 'De Ännern kann nich trüchnahmen warrn; de letzte Autor is de eenzige.',
'alreadyrolled'               => 'Dat Trüchnehmen vun de Ännern an de Siet [[:$1]] vun [[User:$2|$2]]
([[User_talk:$2|Diskuschoonssiet]]) is nich mööglich, vun wegen dat dor en annere Ännern oder Trüchnahm ween is.

De letzte Ännern is vun [[User:$3|$3]]
([[User talk:$3|Diskuschoon]])',
'editcomment'                 => "De Ännerkommentar weer: ''$1''.", # only shown if there is an edit comment
'revertpage'                  => 'Ännern vun [[Special:Contributions/$2|$2]] rut un de Version vun [[User:$1]] wedderhaalt', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Ännern vun $1 trüchsett op letzte Version vun $2.',
'protectlogpage'              => 'Sietenschuul-Logbook',
'protectlogtext'              => 'Dit is en List vun de blockten Sieten. Kiek [[Special:Protectedpages|Schulte Sieten]] för mehr Informatschonen.',
'protectedarticle'            => 'Siet $1 schuult',
'modifiedarticleprotection'   => 'Schuul op „[[$1]]“ sett',
'unprotectedarticle'          => 'Siet $1 freegeven',
'protect-title'               => 'Sparren vun „$1“',
'protect-legend'              => 'Sparr bestätigen',
'protectcomment'              => 'Grund för de Sparr',
'protectexpiry'               => 'Löppt ut:',
'protect_expiry_invalid'      => 'Utlooptiet ungüllig',
'protect_expiry_old'          => 'Utlooptiet al vörbi.',
'protect-unchain'             => 'Schuul vör dat Schuven ännern',
'protect-text'                => "Hier kannst du den Schuulstatus för de Siet '''<nowiki>$1</nowiki>''' ankieken un ännern.",
'protect-locked-access'       => 'Du hest nich de nödigen Rechten, den Schuulstatus vun de Siet to ännern. Dit sünd de aktuellen Instellungen för de Siet <strong>„$1“:</strong>',
'protect-cascadeon'           => 'Disse Siet is aktuell dör ene Kaskadensparr schuult. Se is in de nakamen {{PLURAL:$1|Siet|Sieden}} inbunnen, de dör Kaskadensparr schuult {{PLURAL:$1|is|sünd}}. De Schuulstatus kann för disse Siet ännert warrn, dat hett aver keen Effekt op de Kaskadensparr:',
'protect-default'             => '(normal)',
'protect-fallback'            => '„$1“-Rechten nödig.',
'protect-level-autoconfirmed' => 'nich registreerte Brukers blocken',
'protect-level-sysop'         => 'Blots Admins',
'protect-summary-cascade'     => 'Kaskadensparr',
'protect-expiring'            => 'bet $1 (UTC)',
'protect-cascade'             => 'Kaskadensparr – in disse Siet inbunnene Vörlagen warrt ok schuult.',
'protect-cantedit'            => 'Du kannst de Sparr vun disse Siet nich ännern, du hest dor nich de nödigen Rechten för.',
'restriction-type'            => 'Schuulstatus',
'restriction-level'           => 'Schuulhööchd',
'minimum-size'                => 'Minimumgrött:',
'maximum-size'                => 'Maximumgrött:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Ännern',
'restriction-move'   => 'Schuven',
'restriction-create' => 'Anleggen',

# Restriction levels
'restriction-level-sysop'         => 'vull schuult',
'restriction-level-autoconfirmed' => 'deelwies schuult',
'restriction-level-all'           => 'all',

# Undelete
'undelete'                 => 'Wegsmetene Siet wedderhalen',
'undeletepage'             => 'Wegsmetene Sieden wedderhalen',
'viewdeletedpage'          => 'Wegsmetene Sieden ankieken',
'undeletepagetext'         => 'Disse Sieden sünd wegsmeten worrn, aver jümmer noch spiekert un köönt wedderhaalt warrn.',
'undeleterevisions'        => '{{PLURAL:$1|Ene Version|$1 Versionen}} archiveert',
'undeletehistory'          => 'Wenn du disse Siet weerholst, warrt ok alle olen Versionen weerholt. Wenn siet dat Löschen en nieg Siet mit lieken
Naam schreven worrn is, warrt de weerholten Versionen as ole Versionen vun disse Siet wiest.',
'undelete-nodiff'          => 'Gifft kene öllere Version.',
'undeletebtn'              => 'Wedderhalen!',
'undeletelink'             => 'wedderhalen',
'undeletereset'            => 'Afbreken',
'undeletecomment'          => 'Grund:',
'undeletedarticle'         => '„$1“ wedderhaalt',
'undeletedrevisions'       => '{{Plural:$1|ene Version|$1 Versionen}} wedderhaalt',
'undeletedrevisions-files' => '{{Plural:$1|Ene Version|$1 Versionen}} un {{Plural:$2|ene Datei|$2 Datein}} wedderhaalt',
'undeletedfiles'           => '{{Plural:$1|ene Datei|$1 Datein}} wedderhaalt',
'undelete-search-box'      => 'Wegsmetene Sieden söken',
'undelete-search-prefix'   => 'Wies Sieden, de anfangt mit:',
'undelete-search-submit'   => 'Söken',
'undelete-no-results'      => 'Kene Sieden in’t Archiv funnen, de passt.',
'undelete-error-short'     => 'Fehler bi dat Wedderhalen vun de Datei $1',
'undelete-error-long'      => 'Fehlers bi dat Wedderhalen vun de Datei:

$1',

# Namespace form on various pages
'namespace'      => 'Naamruum:',
'invert'         => 'Utwahl ümkehren',
'blanknamespace' => '(Hööft-)',

# Contributions
'contributions' => 'Bidrääg vun den Bruker',
'mycontris'     => 'Mien Arbeid',
'contribsub2'   => 'För $1 ($2)',
'nocontribs'    => 'Kene Ännern för disse Kriterien funnen.',
'uctop'         => ' (aktuell)',
'month'         => 'bet Maand:',
'year'          => 'Bet Johr:',

'sp-contributions-newbies'     => 'Wies blot Bidrääg vun ne’e Brukers',
'sp-contributions-newbies-sub' => 'För ne’e Kontos',
'sp-contributions-blocklog'    => 'Sparr-Logbook',
'sp-contributions-search'      => 'Na Brukerbidrääg söken',
'sp-contributions-username'    => 'IP-Adress oder Brukernaam:',
'sp-contributions-submit'      => 'Söken',

# What links here
'whatlinkshere'       => 'Wat wiest na disse Siet hen',
'whatlinkshere-title' => 'Sieden, de na „$1“ wiest',
'whatlinkshere-page'  => 'Siet:',
'linklistsub'         => '(List vun de Verwiesen)',
'linkshere'           => "Disse Sieden wiest na '''„[[:$1]]“''':",
'nolinkshere'         => "Kene Siet wiest na '''„[[:$1]]“'''.",
'nolinkshere-ns'      => "Kene Sieden wiest na '''[[:$1]]''' in’n utwählten Naamruum.",
'isredirect'          => 'Wiederleiden',
'istemplate'          => 'inbunnen dör Vörlaag',
'whatlinkshere-prev'  => '{{PLURAL:$1|vörige|vörige $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|nächste|nächste $1}}',
'whatlinkshere-links' => '← Lenken',

# Block/unblock
'blockip'                     => 'IP-Adress blocken',
'blockip-legend'              => 'Bruker blocken',
'blockiptext'                 => 'Bruuk dat Formular, ene IP-Adress to blocken.
Dit schall blots maakt warrn, Vandalismus to vermasseln, aver jümmer in Övereenstimmen mit uns [[{{MediaWiki:Policy-url}}|Leidlienen]].
Ok den Grund för dat Blocken indregen.',
'ipaddress'                   => 'IP-Adress',
'ipadressorusername'          => 'IP-Adress oder Brukernaam:',
'ipbexpiry'                   => 'Aflooptiet',
'ipbreason'                   => 'Grund',
'ipbreasonotherlist'          => 'Annern Grund',
'ipbreason-dropdown'          => '* Allgemene Sperrgrünn
** Tofögen vun verkehrte Infos
** Leddigmaken vun Sieden
** Schrifft Tüdelkraam in Sieden
** Bedroht annere
** Brukernaam nich akzeptabel',
'ipbanononly'                 => 'Blot anonyme Brukers blocken',
'ipbcreateaccount'            => 'Anleggen vun Brukerkonto verhinnern',
'ipbemailban'                 => 'E-Mail schrieven sperren',
'ipbsubmit'                   => 'Adress blocken',
'ipbother'                    => 'Annere Tiet:',
'ipboptions'                  => '1 Stünn:1 hour,2 Stünnen:2 hours,6 Stünnen:6 hours,1 Dag:1 day,3 Daag:3 days,1 Week:1 week,2 Weken:2 weeks,1 Maand:1 month,3 Maand:3 months,1 Johr:1 year,ahn Enn:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'Annere Duer',
'ipbotherreason'              => 'Annern Grund:',
'ipbhidename'                 => 'Brukernaam/IP-Adress in dat Sperr-Logbook, de List vun de aktiven Sperren un de Brukerlist versteken.',
'badipaddress'                => 'De IP-Adress hett en falsch Format.',
'blockipsuccesssub'           => 'Blocken hett Spood',
'blockipsuccesstext'          => 'De IP-Adress „$1“ is nu blockt.

<br />Op de [[Special:Ipblocklist|IP-Blocklist]] is en List vun alle Blocks to finnen.',
'ipb-edit-dropdown'           => 'Blockgrünn bearbeiden',
'ipb-unblock-addr'            => '„$1“ freegeven',
'ipb-unblock'                 => 'IP-Adress/Bruker freegeven',
'ipb-blocklist-addr'          => 'Aktuelle Sperren för „$1“ wiesen',
'ipb-blocklist'               => 'Aktuelle Sperren wiesen',
'unblockip'                   => 'IP-Adress freegeven',
'unblockiptext'               => 'Bruuk dat Formular, üm en blockte IP-Adress freetogeven.',
'ipusubmit'                   => 'Disse Adress freegeven',
'unblocked'                   => '[[{{ns:User}}:$1|$1]] freegeven',
'unblocked-id'                => 'Sperr $1 freegeven',
'ipblocklist'                 => 'List vun blockte IP-Adressen',
'ipblocklist-legend'          => 'Blockten Bruker finnen',
'ipblocklist-username'        => 'Brukernaam oder IP-Adress:',
'ipblocklist-submit'          => 'Söken',
'blocklistline'               => '$1, $2 hett $3 blockt ($4)',
'infiniteblock'               => 'unbegrenzt',
'expiringblock'               => 'löppt $1 af',
'anononlyblock'               => 'blot Anonyme',
'noautoblockblock'            => 'Autoblock afstellt',
'createaccountblock'          => 'Brukerkonten opstellen sperrt',
'emailblock'                  => 'E-Mail schrieven sperrt',
'ipblocklist-empty'           => 'De List is leddig.',
'ipblocklist-no-results'      => 'De söchte IP-Adress/Brukernaam is nich sperrt.',
'blocklink'                   => 'blocken',
'unblocklink'                 => 'freegeven',
'contribslink'                => 'Bidrääg',
'autoblocker'                 => 'Automatisch Block, vun wegen dat du en IP-Adress bruukst mit „$1“. Grund: „$2“.',
'blocklogpage'                => 'Brukerblock-Logbook',
'blocklogentry'               => 'block [[User:$1|$1]] - ([[Special:Contributions/$1|Bidreeg]]) för en Tiedruum vun: $2 $3',
'blocklogtext'                => 'Dit is en Logbook över Blocks un Freegaven vun Brukern. Automatisch blockte IP-Adressen sünd nich opföhrt.
Kiek [[Special:Ipblocklist|IP-Blocklist]] för en List vun den blockten Brukern.',
'unblocklogentry'             => 'Block vun [[User:$1]] ophoven',
'block-log-flags-anononly'    => 'blots anonyme Brukers',
'block-log-flags-nocreate'    => 'Brukerkonten opstellen sperrt',
'block-log-flags-noautoblock' => 'Autoblock utschalt',
'block-log-flags-noemail'     => 'E-Mail schrieven sperrt',
'range_block_disabled'        => 'De Mööglichkeit, ganze Adressrüüm to sparren, is nich aktiveert.',
'ipb_expiry_invalid'          => 'De angeven Aflooptiet is nich güllig.',
'ipb_already_blocked'         => '„$1“ is al blockt',
'ip_range_invalid'            => 'Ungüllig IP-Addressrebeet.',
'blockme'                     => 'Sperr mi',
'proxyblocker'                => 'Proxyblocker',
'proxyblocker-disabled'       => 'Disse Funkschoon is afstellt.',
'proxyblockreason'            => 'Dien IP-Adress is blockt, vun wegen dat se en apenen Proxy is.
Kontakteer dien Provider oder diene Systemtechnik un informeer se över dat möögliche Sekerheitsproblem.',
'proxyblocksuccess'           => 'Trech.',

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
'databasenotlocked'   => 'De Datenbank is nich sparrt.',

# Move page
'move-page'               => 'Schuuv „$1“',
'move-page-legend'        => 'Siet schuven',
'movepagetext'            => 'Mit dit Formular kannst du en Siet ümnömen, tosamen mit all Versionen.
De ole Titel warrt to den ne’en wiederleidt.
Verwiesen op den olen Titel warrt nich ännert un de Diskuschoonssiet warrt ok nich mitschaven.',
'movepagetalktext'        => "De tohören Diskuschoonssiet warrt, wenn een dor is, mitverschaven, ''mit disse Utnahmen:''
* Du schuffst de Siet in en annern Naamruum oder
* dat gifft al en Diskuschoonssiet mit dissen Naam, oder
* du wählst de nerrn stahn Opschoon af

In disse Fäll musst du de Siet, wenn du dat willst, vun Hand schuven.",
'movearticle'             => 'Siet schuven',
'movenologin'             => 'Du büst nich anmellt',
'movenologintext'         => 'Du muttst en registreert Bruker un
[[Special:Userlogin|anmellt]] ween,
üm en Siet to schuven.',
'movenotallowed'          => 'Du hest nich de Rechten, op {{SITENAME}} Sieden to schuven.',
'newtitle'                => 'To ne’en Titel',
'move-watch'              => 'Op disse Siet oppassen',
'movepagebtn'             => 'Siet schuven',
'pagemovedsub'            => 'Schuven hett Spood',
'movepage-moved'          => "<big>'''„$1“ is nu na „$2“ schaven.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Ünner dissen Naam gifft dat al ene Siet.
Bitte söök en annern Naam ut.',
'talkexists'              => 'Dat Schuven vun de Siet sülvst hett Spood, aver dat Schuven vun de
Diskuschoonssiet nich, vun wegen dat dat dor al ene Siet mit dissen Titel gifft. De Inholt mutt vun Hand anpasst warrn.',
'movedto'                 => 'schaven na',
'movetalk'                => 'De Diskuschoonssiet ok schuven, wenn mööglich.',
'talkpagemoved'           => 'De Diskuschoonssiet is ok schaven worrn.',
'talkpagenotmoved'        => 'De Diskuschoonssiet is <strong>nich</strong> schaven worrn.',
'1movedto2'               => '[[$1]] is nu na [[$2]] verschaven.',
'1movedto2_redir'         => '[[$1]] is nu na [[$2]] verschaven un hett den olen Redirect överschreven.',
'movelogpage'             => 'Schuuv-Logbook',
'movelogpagetext'         => 'Dit is ene List vun all schavene Sieden.',
'movereason'              => 'Grund',
'revertmove'              => 'trüchschuven',
'delete_and_move'         => 'Wegsmieten un Schuven',
'delete_and_move_confirm' => 'Jo, de Siet wegsmieten',
'delete_and_move_reason'  => 'wegsmeten, Platz to maken för Schuven',

# Export
'export'            => 'Sieden exporteren',
'exporttext'        => 'Du kannst de Text un de Bearbeidenshistorie vun een oder mehr Sieten no XML exporteern. Dat Resultat kann in en annern Wiki mit Mediawiki-Software inspeelt warrn, bearbeid oder archiveert warrn.',
'exportcuronly'     => 'Blots de aktuelle Version vun de Siet exporteern',
'export-submit'     => 'Export',
'export-addcattext' => 'Sieden ut Kategorie tofögen:',
'export-addcat'     => 'Tofögen',
'export-download'   => 'As XML-Datei spiekern',
'export-templates'  => 'mit Vörlagen',

# Namespace 8 related
'allmessages'               => 'Alle Systemnarichten',
'allmessagesname'           => 'Naam',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Text nu',
'allmessagestext'           => 'Dit is de List vun all de Systemnarichten, de dat in den Mediawiki-Naamruum gifft.',
'allmessagesnotsupportedDB' => '{{ns:special}}:Allmessages is nich ünnerstütt, vun wegen dat wgUseDatabaseMessages utstellt is.',
'allmessagesfilter'         => 'Narichtennaamfilter:',
'allmessagesmodified'       => 'Blot ännerte wiesen',

# Thumbnails
'thumbnail-more'           => 'grötter maken',
'filemissing'              => 'Datei fehlt',
'thumbnail_error'          => 'Fehler bi dat Maken vun’t Duumnagel-Bild: $1',
'djvu_page_error'          => 'DjVu-Siet buten de verföögboren Sieden',
'thumbnail_invalid_params' => 'Duumnagelbild-Parameter passt nich',
'thumbnail_dest_directory' => 'Kann Zielorner nich anleggen',

# Special:Import
'import'                     => 'Import vun Sieden',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-submit'    => 'Rinhalen',
'import-interwiki-namespace' => 'Siet in Naamruum halen:',
'importtext'                 => 'Exporteer de Siet vun dat Utgangswiki mit Special:Export un laad de Datei denn över disse Siet weer hooch.',
'importstart'                => 'Sieden warrt rinhaalt...',
'import-revision-count'      => '$1 {{PLURAL:$1|Version|Versionen}}',
'importnopages'              => 'Gifft kene Sieden to’n Rinhalen.',
'importfailed'               => 'Import hett kenen Spood: $1',
'importcantopen'             => 'Kunn de Import-Datei nich apen maken',
'importbadinterwiki'         => 'Verkehrt Interwiki-Lenk',
'importnotext'               => 'Leddig oder keen Text',
'importsuccess'              => 'Import hett Spood!',
'importhistoryconflict'      => 'Dor sünd al öllere Versionen, de mit dissen kollideert. (Mööglicherwies is de Siet al vörher importeert worrn)',
'importnofile'               => 'Kene Import-Datei hoochladen.',
'import-noarticle'           => 'Kene Siet to’n Rinhalen angeven!',
'xml-error-string'           => '$1 Reeg $2, Spalt $3, (Byte $4): $5',

# Import log
'importlogpage'                    => 'Import-Logbook',
'import-logentry-upload-detail'    => '{{PLURAL:$1|ene Version|$1 Versionen}}',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|ene Version|$1 Versionen}} vun $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mien Brukersiet',
'tooltip-pt-anonuserpage'         => 'De Brukersiet för de IP-Adress ünner de du schriffst',
'tooltip-pt-mytalk'               => 'Mien Diskuschoonssiet',
'tooltip-pt-anontalk'             => 'Diskuschoon över Ännern vun disse IP-Adress',
'tooltip-pt-preferences'          => 'Mien Instellen',
'tooltip-pt-watchlist'            => 'Mien Oppasslist',
'tooltip-pt-mycontris'            => 'List vun mien Bidreeg',
'tooltip-pt-login'                => 'Du kannst di geern anmellen, dat is aver nich neudig, üm Sieten to bearbeiden.',
'tooltip-pt-anonlogin'            => 'Du kannst di geern anmellen, dat is aver nich neudig, üm Sieten to bearbeiden.',
'tooltip-pt-logout'               => 'Afmellen',
'tooltip-ca-talk'                 => 'Diskuschoon över disse Siet',
'tooltip-ca-edit'                 => 'Du kannst disse Siet ännern. Bruuk dat vör dat Spiekern.',
'tooltip-ca-addsection'           => 'En Kommentar to disse Diskuschoonssiet hentofögen.',
'tooltip-ca-viewsource'           => 'Disse Siet is schuult. Du kannst den Borntext ankieken.',
'tooltip-ca-history'              => 'Historie vun disse Siet.',
'tooltip-ca-protect'              => 'Disse Siet schulen',
'tooltip-ca-delete'               => 'Disse Siet löschen',
'tooltip-ca-undelete'             => 'Weerholen vun de Siet, so as se vör dat löschen ween is',
'tooltip-ca-move'                 => 'Disse Siet schuven',
'tooltip-ca-watch'                => 'Disse Siet to de Oppasslist hentofögen',
'tooltip-ca-unwatch'              => 'Disse Siet vun de Oppasslist löschen',
'tooltip-search'                  => 'Söken in dit Wiki',
'tooltip-p-logo'                  => 'Hööftsiet',
'tooltip-n-mainpage'              => 'Besöök de Hööftsiet',
'tooltip-n-portal'                => 'över dat Projekt, wat du doon kannst, woans du de Saken finnen kannst',
'tooltip-n-currentevents'         => 'Achtergrünn to aktuellen Schehn finnen',
'tooltip-n-recentchanges'         => 'List vun de letzten Ännern in dissen Wiki.',
'tooltip-n-randompage'            => 'Tofällige Siet',
'tooltip-n-help'                  => 'Hier kriegst du Hülp.',
'tooltip-n-sitesupport'           => 'Gaven',
'tooltip-t-whatlinkshere'         => 'Wat wiest hierher',
'tooltip-t-recentchangeslinked'   => 'Verlinkte Sieden',
'tooltip-feed-rss'                => 'RSS-Feed för disse Siet',
'tooltip-feed-atom'               => 'Atom-Feed för disse Siet',
'tooltip-t-contributions'         => 'List vun de Bidreeg vun dissen Bruker',
'tooltip-t-emailuser'             => 'En E-Mail an dissen Bruker sennen',
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

# Stylesheets
'common.css'   => '/** CSS-Kood hier binnen warrt för all Stilvörlagen (Skins) inbunnen */',
'monobook.css' => '/* disse Datei ännern üm de Monobook-Stilvörlaag för de ganze Siet antopassen */',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadaten sünd för dissen Server nich aktiveert.',
'notacceptable'     => 'Dat Wiki-Server kann kene Daten in enen Format levern, dat dien Klient lesen kann.',

# Attribution
'anonymous'        => 'Anonyme Bruker vun {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Bruker $1',
'lastmodifiedatby' => 'Disse Siet weer dat letzte Maal $2, $1 vun $3 ännert.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Grünnt op Arbeid vun $1.',
'others'           => 'annere',
'siteusers'        => '{{SITENAME}}-Bruker $1',
'creditspage'      => 'Sieten-Autoren',
'nocredits'        => 'Dor is keen Autorenlist för disse Siet verfögbor.',

# Spam protection
'spamprotectiontitle' => 'Spamschild',
'spamprotectiontext'  => 'De Siet, de du spiekern wullst, weer vun de Spamschild blockt. Dat kann vun en Link to en externe Siet kamen.',
'spamprotectionmatch' => 'Dit Text hett den Spamschild utlöst: $1',
'spambot_username'    => 'MediaWiki Spam-Oprümen',

# Info page
'infosubtitle'   => 'Informatschonen för de Siet',
'numedits'       => 'Antall vun Ännern (Siet): $1',
'numtalkedits'   => 'Antall vun Ännern (Diskuschoonssiet): $1',
'numwatchers'    => 'Antall vun Oppassers: $1',
'numauthors'     => 'Antall vun verschedene Autoren (Siet): $1',
'numtalkauthors' => 'Antall vun verschedene Autoren (Diskuschoonssiet): $1',

# Math options
'mw_math_png'    => 'Jümmer as PNG dorstellen',
'mw_math_simple' => 'Eenfach TeX as HTML dorstellen, sünst PNG',
'mw_math_html'   => 'Wenn mööglich as HTML dorstellen, sünst PNG',
'mw_math_source' => 'As TeX laten (för Textbrowser)',
'mw_math_modern' => 'Anratenswert för moderne Browser',
'mw_math_mathml' => 'MathML (experimentell)',

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
'patrol-log-page' => 'Nakiek-Logbook',
'patrol-log-line' => '$1 vun $2 as nakeken markt $3',
'patrol-log-auto' => '(automaatsch)',

# Image deletion
'deletedrevision'       => 'Löschte ole Version $1',
'filedeleteerror-short' => 'Fehler bi dat Wegsmieten vun de Datei: $1',
'filedeleteerror-long'  => 'Dat geev Fehlers bi dat Wegsmieten vun de Datei:

$1',

# Browsing diffs
'previousdiff' => '← Gah to den vörigen Ünnerscheed',
'nextdiff'     => 'Gah to den tokamen Ünnerscheed →',

# Media information
'imagemaxsize'         => 'Biller op de Bildbeschrievensiet begrenzen op:',
'thumbsize'            => 'Grött vun dat Duumnagel-Bild:',
'widthheightpage'      => '$1×$2, $3 Sieden',
'file-info'            => '(Grött: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Grött: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Gifft dat Bild nich grötter.</small>',
'svg-long-desc'        => '(SVG-Datei, Utgangsgrött: $1 × $2 Pixel, Dateigrött: $3)',
'show-big-image'       => 'Dat Bild wat grötter',
'show-big-image-thumb' => '<small>Grött vun disse Vörschau: $1 × $2 Pixels</small>',

# Special:Newimages
'newimages'             => 'Ne’e Biller',
'imagelisttext'         => 'Hier is en List vun {{PLURAL:$1|een Bild|$1 Biller}}, sorteert $2.',
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
* focallength', # Do not translate list items

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
'exif-pixelydimension'             => 'Gellen Bildbreed',
'exif-pixelxdimension'             => 'Gellen Bildhööchd',
'exif-makernote'                   => 'Herstellernotiz',
'exif-usercomment'                 => 'Brukerkommentar',
'exif-relatedsoundfile'            => 'Tohörige Toondatei',
'exif-subsectime'                  => 'Spiekertiet (1/100 s)',
'exif-subsectimedigitized'         => 'Tiet digital maakt (1/100 s)',
'exif-exposuretime'                => 'Belichtungstiet',
'exif-exposuretime-format'         => '$1 Sek. ($2)',
'exif-fnumber'                     => 'F-Nummer',
'exif-exposureprogram'             => 'Belichtungsprogramm',
'exif-shutterspeedvalue'           => 'Belichttiet',
'exif-aperturevalue'               => 'Blennweert',
'exif-brightnessvalue'             => 'Helligkeit',
'exif-subjectdistance'             => 'wo wied weg',
'exif-meteringmode'                => 'Meetmethood',
'exif-lightsource'                 => 'Lichtborn',
'exif-flash'                       => 'Blitz',
'exif-focallength'                 => 'Brennwied',
'exif-flashenergy'                 => 'Blitzstärk',
'exif-sensingmethod'               => 'Meetmethood',
'exif-filesource'                  => 'Dateiborn',
'exif-scenetype'                   => 'Szenentyp',
'exif-whitebalance'                => 'Wittutgliek',
'exif-digitalzoomratio'            => 'Digitalzoom',
'exif-gaincontrol'                 => 'Verstärkung',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Sättigung',
'exif-sharpness'                   => 'Schärp',
'exif-imageuniqueid'               => 'Bild-ID',
'exif-gpslatituderef'              => 'Bredengraad Noord oder Süüd',
'exif-gpslatitude'                 => 'Breed',
'exif-gpslongituderef'             => 'Längengraad Oost oder West',
'exif-gpslongitude'                => 'Läng',
'exif-gpsaltituderef'              => 'Betogshööchd',
'exif-gpsaltitude'                 => 'Hööch',
'exif-gpsmeasuremode'              => 'Meetverfohren',
'exif-gpsdop'                      => 'Meetnauigkeit',
'exif-gpsspeedref'                 => 'Tempo-Eenheit',
'exif-gpsspeed'                    => 'Tempo vun’n GPS-Empfänger',
'exif-gpsdestlatitude'             => 'Bredengraad',
'exif-gpsdestlongitude'            => 'Längengraad',
'exif-gpsdestbearing'              => 'Motivrichtung',
'exif-gpsdestdistance'             => 'wo wied af vun dat Motiv',
'exif-gpsdatestamp'                => 'GPS-Datum',

# EXIF attributes
'exif-compression-1' => 'Unkomprimeert',

'exif-unknowndate' => 'Unbekannt Datum',

'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'waagrecht kippt', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° dreiht', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikal kippt', # 0th row: bottom; 0th column: left
'exif-orientation-5' => '90° gegen de Klock dreiht un vertikal kippt', # 0th row: left; 0th column: top
'exif-orientation-6' => '90° mit de Klock dreiht', # 0th row: right; 0th column: top
'exif-orientation-7' => '90° mit de Klock dreiht un vertikal kippt', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '90° gegen de Klock dreiht', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Groffformat',
'exif-planarconfiguration-2' => 'Planarformat',

'exif-componentsconfiguration-0' => 'gifft dat nich',

'exif-exposureprogram-0' => 'Unbekannt',
'exif-exposureprogram-1' => 'vun Hand',
'exif-exposureprogram-2' => 'Standardprogramm',
'exif-exposureprogram-4' => 'Blennenautomatik',

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

'exif-focalplaneresolutionunit-2' => 'Toll',

'exif-sensingmethod-1' => 'Undefineert',
'exif-sensingmethod-2' => 'Een-Chip-Farvsensor',
'exif-sensingmethod-3' => 'Twee-Chip-Farvsensor',
'exif-sensingmethod-4' => 'Dree-Chip-Farvsensor',
'exif-sensingmethod-7' => 'Trilinear Sensor',

'exif-customrendered-0' => 'Standard',
'exif-customrendered-1' => 'Anpasst',

'exif-whitebalance-0' => 'Automaatsch Wittutgliek',
'exif-whitebalance-1' => 'Wittutgliek vun Hand',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landschop',
'exif-scenecapturetype-2' => 'Porträt',
'exif-scenecapturetype-3' => 'Nacht',

'exif-gaincontrol-0' => 'Keen',

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

'exif-gpsmeasuremode-2' => '2-dimensional meet',
'exif-gpsmeasuremode-3' => '3-dimensional meet',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometers in’e Stünn',
'exif-gpsspeed-m' => 'Mielen in’e Stünn',
'exif-gpsspeed-n' => 'Knoten',

# External editor support
'edit-externally'      => 'Änner disse Datei mit en extern Programm',
'edit-externally-help' => '<span class="plainlinks">Lees de [http://meta.wikimedia.org/wiki/Help:External_editors Installatschoonshelp] wenn du dor mehr to weten wist.</span>',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'all',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',

# E-mail address confirmation
'confirmemail'             => 'Nettbreefadress bestätigen',
'confirmemail_noemail'     => 'Du hest kene bestätigte Nettbreefadress in diene [[Special:Preferences|Instellen]] angeven.',
'confirmemail_text'        => '{{SITENAME}} verlangt, dat du diene Nettbreefadress bestätigst, ehrder du de Nettbreeffunkschonen bruken kannst. Klick op den Knopp wieder ünnen, dat die en Bestätigungskood schickt warrt.',
'confirmemail_send'        => 'Bestätigungskood schicken.',
'confirmemail_sent'        => 'Bestätigungsnettbreef afschickt.',
'confirmemail_sendfailed'  => 'Bestätigungsnettbreef kunn nich sennt warrn. Schasst man nakieken, wat de Adress ok nich verkehrt schreven is.

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
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks för dissen Artikel:<br />
$1
</div>',
'trackbackremove'   => '([$1 wegsmieten])',
'trackbackdeleteok' => 'Trackback mit Spood wegsmeten.',

# Delete conflict
'deletedwhileediting' => 'Wohrschau: Disse Siet is wegsmeten worrn, as du se graad ännert hest!',
'confirmrecreate'     => "De Bruker [[User:$1|$1]] ([[User talk:$1|talk]]) hett disse Siet wegsmeten, nadem du dat Ännern anfungen hest. He hett as Grund schreven:
: ''$2''
Wist du de Siet würklich nee anleggen?",
'recreate'            => 'wedder nee anleggen',

# HTML dump
'redirectingto' => 'Redirect sett na [[$1]]...',

# action=purge
'confirm_purge'        => 'Den Cache vun disse Siet leddig maken?

$1',
'confirm_purge_button' => 'Jo',

# AJAX search
'searchcontaining' => "Na Artikels söken, in de ''$1'' binnen is.",
'searchnamed'      => "Na Artikels söken, de ''$1'' heten doot.",
'articletitles'    => 'Artikels, de mit „$1“ anfangt',
'hideresults'      => 'Resultaten verstecken',
'useajaxsearch'    => 'Bruuk de AJAX-Söök',

# Multipage image navigation
'imgmultipageprev' => '← vörige Siet',
'imgmultipagenext' => 'nächste Siet →',
'imgmultigo'       => 'Los!',
'imgmultigotopre'  => 'Gah na de Siet',

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
'autosumm-blank'   => '[[{{ns:help}}:Autokommentar|AK]]: Siet leddig maakt',
'autosumm-replace' => '[[{{ns:help}}:Autokommentar|AK]]: Siet leddig maakt un ‚$1‘ rinschreven',
'autoredircomment' => '[[{{ns:help}}:Autokommentar|AK]]: Redirect sett na [[$1]]',
'autosumm-new'     => '[[{{ns:help}}:Autokommentar|AK]]: Ne’e Siet: ‚$1‘',

# Live preview
'livepreview-loading' => 'Läädt…',
'livepreview-ready'   => 'Läädt… Trech!',
'livepreview-failed'  => 'Live-Vörschau klapp nich!
Versöök de normale Vörschau.',
'livepreview-error'   => 'Verbinnen klapp nich: $1 „$2“
Versöök de normale Vörschau.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ännern, de jünger as $1 Sekunnen sünd, warrt in de List noch nich wiest.',
'lag-warn-high'   => 'De Datenbank is temlich dull utlast. Ännern, de jünger as $1 Sekunnen sünd, warrt in de List noch nich wiest.',

# Watchlist editor
'watchlistedit-numitems'      => 'Du hest {{PLURAL:$1|ene Siet|$1 Sieden}} op diene Oppasslist, Diskuschoonssieden nich tellt.',
'watchlistedit-noitems'       => 'Diene Oppasslist is leddig.',
'watchlistedit-normal-title'  => 'Oppasslist ännern',
'watchlistedit-normal-legend' => 'Sieden vun de Oppasslist rünnernehmen',
'watchlistedit-normal-submit' => 'Sieden rutnehmen',
'watchlistedit-normal-done'   => '{{PLURAL:$1|Ene Siet|$1 Sieden}} vun de Oppasslist rünnernahmen:',
'watchlistedit-raw-title'     => 'Oppasslist as Textlist ännern',
'watchlistedit-raw-legend'    => 'Oppasslist as Textlist ännern',
'watchlistedit-raw-titles'    => 'Sieden:',
'watchlistedit-raw-submit'    => 'Oppasslist spiekern',
'watchlistedit-raw-done'      => 'Diene Oppasslist is spiekert worrn.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|Ene Siet|$1 Sieden}} dorto:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|Ene Siet|$1 Sieden}} rünnernahmen:',

# Watchlist editing tools
'watchlisttools-view' => 'Oppasslist ankieken',
'watchlisttools-edit' => 'Oppasslist ankieken un ännern',
'watchlisttools-raw'  => 'Oppasslist as Textlist ännern',

# Special:Version
'version'                   => 'Version', # Not used as normal message but as header for the special page itself
'version-specialpages'      => 'Spezialsieden',
'version-other'             => 'Annern Kraam',
'version-hook-subscribedby' => 'Opropen vun',
'version-version'           => 'Version',
'version-license'           => 'Lizenz',
'version-software'          => 'Installeerte Software',
'version-software-product'  => 'Produkt',
'version-software-version'  => 'Version',

# Special:Filepath
'filepath'         => 'Dateipadd',
'filepath-page'    => 'Datei:',
'filepath-submit'  => 'Padd',
'filepath-summary' => 'Disse Spezialsiet gifft den kumpletten Padd för ene Datei trüch. Biller warrt in vull Oplösen wiest, annere Datein warrt glieks mit dat Programm opropen, dat för de Soort Datein instellt is.

Geev den Dateinaam ahn den Tosatz „{{ns:image}}:“ an.',

);
