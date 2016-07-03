<?php
/** Low German (Plattdüütsch)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Geitost
 * @author Joachim Mos
 * @author Kaganer
 * @author Purodha
 * @author Slomox
 * @author The Evil IP address
 * @author Urhixidur
 * @author Zylbath
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskuschoon',
	NS_USER             => 'Bruker',
	NS_USER_TALK        => 'Bruker_Diskuschoon',
	NS_PROJECT_TALK     => '$1_Diskuschoon',
	NS_FILE             => 'Bild',
	NS_FILE_TALK        => 'Bild_Diskuschoon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskuschoon',
	NS_TEMPLATE         => 'Vörlaag',
	NS_TEMPLATE_TALK    => 'Vörlaag_Diskuschoon',
	NS_HELP             => 'Hülp',
	NS_HELP_TALK        => 'Hülp_Diskuschoon',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskuschoon',
];

$namespaceAliases = [
	'Diskussion' => NS_TALK,
	'Benutzer' => NS_USER,
	'Benutzer_Diskussion' => NS_USER_TALK,
	'$1_Diskussion' => NS_PROJECT_TALK,
	'Datei' => NS_FILE,
	'Bild_Diskussion' => NS_FILE_TALK,
	'Datei_Diskuschoon' => NS_FILE_TALK,
	'MediaWiki_Diskussion' => NS_MEDIAWIKI_TALK,
	'Vorlage' => NS_TEMPLATE,
	'Vorlage_Diskussion' => NS_TEMPLATE_TALK,
	'Hilfe' => NS_HELP,
	'Hilfe_Diskussion' => NS_HELP_TALK,
	'Kategorie' => NS_CATEGORY,
	'Kategorie_Diskussion' => NS_CATEGORY_TALK,
];

$magicWords = [
	'redirect'                  => [ '0', '#wiederleiden', '#WEITERLEITUNG', '#REDIRECT' ],
	'notoc'                     => [ '0', '__KEENINHOLTVERTEKEN__', '__KEIN_INHALTSVERZEICHNIS__', '__KEININHALTSVERZEICHNIS__', '__NOTOC__' ],
	'forcetoc'                  => [ '0', '__WIESINHOLTVERTEKEN__', '__INHALTSVERZEICHNIS_ERZWINGEN__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__INHOLTVERTEKEN__', '__INHALTSVERZEICHNIS__', '__TOC__' ],
	'noeditsection'             => [ '0', '__KEENÄNNERNLINK__', '__ABSCHNITTE_NICHT_BEARBEITEN__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'AKTMAAND', 'JETZIGER_MONAT', 'JETZIGER_MONAT_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'AKTMAANDNAAM', 'JETZIGER_MONATSNAME', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'AKTMAANDNAAMGEN', 'JETZIGER_MONATSNAME_GENITIV', 'JETZIGER_MONATSNAME_GEN', 'CURRENTMONTHNAMEGEN' ],
	'currentday'                => [ '1', 'AKTDAG', 'JETZIGER_KALENDERTAG', 'JETZIGER_TAG', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'AKTDAGNAAM', 'JETZIGER_WOCHENTAG', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'AKTJOHR', 'JETZIGES_JAHR', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'AKTTIED', 'JETZIGE_UHRZEIT', 'CURRENTTIME' ],
	'numberofarticles'          => [ '1', 'ARTIKELTALL', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ],
	'pagename'                  => [ '1', 'SIETNAAM', 'SEITENNAME', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIETNAAME', 'SEITENNAME_URL', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'NAAMRUUM', 'NAMENSRAUM', 'NAMESPACE' ],
	'img_thumbnail'             => [ '1', 'duum', 'miniatur', 'mini', 'thumb', 'thumbnail' ],
	'img_right'                 => [ '1', 'rechts', 'right' ],
	'img_left'                  => [ '1', 'links', 'left' ],
	'img_none'                  => [ '1', 'keen', 'ohne', 'none' ],
	'img_center'                => [ '1', 'zentriert', 'merrn', 'center', 'centre' ],
	'img_framed'                => [ '1', 'gerahmt', 'rahmt', 'frame', 'framed', 'enframed' ],
	'sitename'                  => [ '1', 'STEEDNAAM', 'PROJEKTNAME', 'SITENAME' ],
	'ns'                        => [ '0', 'NR:', 'NS:' ],
	'localurl'                  => [ '0', 'STEEDURL:', 'LOKALE_URL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'STEEDURLE:', 'LOKALE_URL_C:', 'LOCALURLE:' ],
	'grammar'                   => [ '0', 'GRAMMATIK:', 'GRAMMAR:' ],
];

$bookstoreList = [
	'Verteken vun leverbore Böker'  => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1=0&y=0',
	'abebooks.de'                   => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de'                     => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Lehmanns Fachbuchhandlung'     => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
];

$namespaceNames = [
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Spezial',
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
];

$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';
$separatorTransformTable = [ ',' => '.', '.' => ',' ];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'M j., Y',
	'mdy both' => 'H:i, M j., Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'H:i, j. M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j.',
	'ymd both' => 'H:i, Y M j.',
];

// Remove German aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Allmessages'               => [ 'Systemnarichten' ],
	'Allpages'                  => [ 'Alle Sieden' ],
	'Ancientpages'              => [ 'Ole Sieden' ],
	'Blankpage'                 => [ 'Leddige Sied' ],
	'Block'                     => [ 'Blocken' ],
	'Booksources'               => [ 'ISBN-Söök' ],
	'BrokenRedirects'           => [ 'Kaputte Redirects' ],
	'Categories'                => [ 'Kategorien' ],
	'ChangePassword'            => [ 'Passwoort trüchsetten' ],
	'Confirmemail'              => [ 'E-Mail bestätigen' ],
	'Contributions'             => [ 'Bidrääg' ],
	'CreateAccount'             => [ 'Brukerkonto anleggen' ],
	'Deadendpages'              => [ 'Sackstraatsieden' ],
	'DoubleRedirects'           => [ 'Dubbelte Redirects' ],
	'Emailuser'                 => [ 'E-Mail an Bruker' ],
	'Export'                    => [ 'Exporteren' ],
	'Fewestrevisions'           => [ 'Kuum ännerte Sieden' ],
	'FileDuplicateSearch'       => [ 'Dubbelte-Datein-Söök' ],
	'Filepath'                  => [ 'Dateipadd' ],
	'Import'                    => [ 'Importeren' ],
	'BlockList'                 => [ 'List vun blockte IPs' ],
	'Listadmins'                => [ 'Administraters' ],
	'Listbots'                  => [ 'Bots' ],
	'Listfiles'                 => [ 'Dateilist' ],
	'Listgrouprights'           => [ 'Gruppenrechten' ],
	'Listredirects'             => [ 'List vun Redirects' ],
	'Listusers'                 => [ 'Brukers' ],
	'Lockdb'                    => [ 'Datenbank sparren' ],
	'Log'                       => [ 'Logbook' ],
	'Lonelypages'               => [ 'Weetsieden' ],
	'Longpages'                 => [ 'Lange Sieden' ],
	'MergeHistory'              => [ 'Versionshistorie tohoopbringen' ],
	'MIMEsearch'                => [ 'MIME-Typ-Söök' ],
	'Mostcategories'            => [ 'Sieden mit vele Kategorien' ],
	'Mostimages'                => [ 'Veel bruukte Datein' ],
	'Mostlinked'                => [ 'Veel lenkte Sieden' ],
	'Mostlinkedcategories'      => [ 'Veel bruukte Kategorien' ],
	'Mostlinkedtemplates'       => [ 'Veel bruukte Vörlagen' ],
	'Mostrevisions'             => [ 'Faken ännerte Sieden' ],
	'Movepage'                  => [ 'Schuven' ],
	'Mycontributions'           => [ 'Miene Bidrääg' ],
	'Mypage'                    => [ 'Miene Brukersiet' ],
	'Mytalk'                    => [ 'Miene Diskuschoonssiet' ],
	'Newimages'                 => [ 'Nee Datein' ],
	'Newpages'                  => [ 'Nee Sieden' ],
	'Preferences'               => [ 'Instellungen' ],
	'Prefixindex'               => [ 'Sieden de anfangt mit' ],
	'Protectedpages'            => [ 'Schuulte Sieden' ],
	'Protectedtitles'           => [ 'Sperrte Titels' ],
	'Randompage'                => [ 'Tofällige Siet' ],
	'Randomredirect'            => [ 'Tofällig Redirect' ],
	'Recentchanges'             => [ 'Toletzt ännert', 'Neeste Ännern' ],
	'Recentchangeslinked'       => [ 'Ännern an lenkte Sieden' ],
	'Revisiondelete'            => [ 'Versionen wegsmieten' ],
	'Search'                    => [ 'Söök' ],
	'Shortpages'                => [ 'Korte Sieden' ],
	'Specialpages'              => [ 'Spezialsieden' ],
	'Statistics'                => [ 'Statistik' ],
	'Uncategorizedcategories'   => [ 'Kategorien ahn Kategorie' ],
	'Uncategorizedimages'       => [ 'Datein ahn Kategorie' ],
	'Uncategorizedpages'        => [ 'Sieden ahn Kategorie' ],
	'Uncategorizedtemplates'    => [ 'Vörlagen ahn Kategorie' ],
	'Undelete'                  => [ 'Wedderhalen' ],
	'Unlockdb'                  => [ 'Datenbank freegeven' ],
	'Unusedcategories'          => [ 'Nich bruukte Kategorien' ],
	'Unusedimages'              => [ 'Nich bruukte Datein' ],
	'Unusedtemplates'           => [ 'Nich bruukte Vörlagen' ],
	'Unwatchedpages'            => [ 'Sieden op keen Oppasslist' ],
	'Upload'                    => [ 'Hoochladen' ],
	'Userlogin'                 => [ 'Anmellen' ],
	'Userlogout'                => [ 'Afmellen' ],
	'Userrights'                => [ 'Brukerrechten' ],
	'Wantedcategories'          => [ 'Wünschte Kategorien' ],
	'Wantedpages'               => [ 'Wünschte Sieden' ],
	'Watchlist'                 => [ 'Oppasslist' ],
	'Whatlinkshere'             => [ 'Wat wiest hier hen' ],
	'Withoutinterwiki'          => [ 'Sieden ahn Spraaklenken' ],
];

