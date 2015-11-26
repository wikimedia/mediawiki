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

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

$magicWords = array(
	'redirect'                  => array( '0', '#wiederleiden', '#WEITERLEITUNG', '#REDIRECT' ),
	'notoc'                     => array( '0', '__KEENINHOLTVERTEKEN__', '__KEIN_INHALTSVERZEICHNIS__', '__KEININHALTSVERZEICHNIS__', '__NOTOC__' ),
	'forcetoc'                  => array( '0', '__WIESINHOLTVERTEKEN__', '__INHALTSVERZEICHNIS_ERZWINGEN__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__INHOLTVERTEKEN__', '__INHALTSVERZEICHNIS__', '__TOC__' ),
	'noeditsection'             => array( '0', '__KEENÄNNERNLINK__', '__ABSCHNITTE_NICHT_BEARBEITEN__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'AKTMAAND', 'JETZIGER_MONAT', 'JETZIGER_MONAT_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'AKTMAANDNAAM', 'JETZIGER_MONATSNAME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'AKTMAANDNAAMGEN', 'JETZIGER_MONATSNAME_GENITIV', 'JETZIGER_MONATSNAME_GEN', 'CURRENTMONTHNAMEGEN' ),
	'currentday'                => array( '1', 'AKTDAG', 'JETZIGER_KALENDERTAG', 'JETZIGER_TAG', 'CURRENTDAY' ),
	'currentdayname'            => array( '1', 'AKTDAGNAAM', 'JETZIGER_WOCHENTAG', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'AKTJOHR', 'JETZIGES_JAHR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'AKTTIED', 'JETZIGE_UHRZEIT', 'CURRENTTIME' ),
	'numberofarticles'          => array( '1', 'ARTIKELTALL', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ),
	'pagename'                  => array( '1', 'SIETNAAM', 'SEITENNAME', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'SIETNAAME', 'SEITENNAME_URL', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NAAMRUUM', 'NAMENSRAUM', 'NAMESPACE' ),
	'img_thumbnail'             => array( '1', 'duum', 'miniatur', 'mini', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'rechts', 'right' ),
	'img_left'                  => array( '1', 'links', 'left' ),
	'img_none'                  => array( '1', 'keen', 'ohne', 'none' ),
	'img_center'                => array( '1', 'merrn', 'zentriert', 'center', 'centre' ),
	'img_framed'                => array( '1', 'rahmt', 'gerahmt', 'framed', 'enframed', 'frame' ),
	'sitename'                  => array( '1', 'STEEDNAAM', 'PROJEKTNAME', 'SITENAME' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'localurl'                  => array( '0', 'STEEDURL:', 'LOKALE_URL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'STEEDURLE:', 'LOKALE_URL_C:', 'LOCALURLE:' ),
	'grammar'                   => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
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
$separatorTransformTable = array( ',' => '.', '.' => ',' );

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

// Remove German aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Allmessages'               => array( 'Systemnarichten' ),
	'Allpages'                  => array( 'Alle Sieden' ),
	'Ancientpages'              => array( 'Ole Sieden' ),
	'Blankpage'                 => array( 'Leddige Sied' ),
	'Block'                     => array( 'Blocken' ),
	'Booksources'               => array( 'ISBN-Söök' ),
	'BrokenRedirects'           => array( 'Kaputte Redirects' ),
	'Categories'                => array( 'Kategorien' ),
	'ChangePassword'            => array( 'Passwoort trüchsetten' ),
	'Confirmemail'              => array( 'E-Mail bestätigen' ),
	'Contributions'             => array( 'Bidrääg' ),
	'CreateAccount'             => array( 'Brukerkonto anleggen' ),
	'Deadendpages'              => array( 'Sackstraatsieden' ),
	'DoubleRedirects'           => array( 'Dubbelte Redirects' ),
	'Emailuser'                 => array( 'E-Mail an Bruker' ),
	'Export'                    => array( 'Exporteren' ),
	'Fewestrevisions'           => array( 'Kuum ännerte Sieden' ),
	'FileDuplicateSearch'       => array( 'Dubbelte-Datein-Söök' ),
	'Filepath'                  => array( 'Dateipadd' ),
	'Import'                    => array( 'Importeren' ),
	'BlockList'                 => array( 'List vun blockte IPs' ),
	'Listadmins'                => array( 'Administraters' ),
	'Listbots'                  => array( 'Bots' ),
	'Listfiles'                 => array( 'Dateilist' ),
	'Listgrouprights'           => array( 'Gruppenrechten' ),
	'Listredirects'             => array( 'List vun Redirects' ),
	'Listusers'                 => array( 'Brukers' ),
	'Lockdb'                    => array( 'Datenbank sparren' ),
	'Log'                       => array( 'Logbook' ),
	'Lonelypages'               => array( 'Weetsieden' ),
	'Longpages'                 => array( 'Lange Sieden' ),
	'MergeHistory'              => array( 'Versionshistorie tohoopbringen' ),
	'MIMEsearch'                => array( 'MIME-Typ-Söök' ),
	'Mostcategories'            => array( 'Sieden mit vele Kategorien' ),
	'Mostimages'                => array( 'Veel bruukte Datein' ),
	'Mostlinked'                => array( 'Veel lenkte Sieden' ),
	'Mostlinkedcategories'      => array( 'Veel bruukte Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Veel bruukte Vörlagen' ),
	'Mostrevisions'             => array( 'Faken ännerte Sieden' ),
	'Movepage'                  => array( 'Schuven' ),
	'Mycontributions'           => array( 'Miene Bidrääg' ),
	'Mypage'                    => array( 'Miene Brukersiet' ),
	'Mytalk'                    => array( 'Miene Diskuschoonssiet' ),
	'Newimages'                 => array( 'Nee Datein' ),
	'Newpages'                  => array( 'Nee Sieden' ),
	'Preferences'               => array( 'Instellungen' ),
	'Prefixindex'               => array( 'Sieden de anfangt mit' ),
	'Protectedpages'            => array( 'Schuulte Sieden' ),
	'Protectedtitles'           => array( 'Sperrte Titels' ),
	'Randompage'                => array( 'Tofällige Siet' ),
	'Randomredirect'            => array( 'Tofällig Redirect' ),
	'Recentchanges'             => array( 'Toletzt ännert', 'Neeste Ännern' ),
	'Recentchangeslinked'       => array( 'Ännern an lenkte Sieden' ),
	'Revisiondelete'            => array( 'Versionen wegsmieten' ),
	'Search'                    => array( 'Söök' ),
	'Shortpages'                => array( 'Korte Sieden' ),
	'Specialpages'              => array( 'Spezialsieden' ),
	'Statistics'                => array( 'Statistik' ),
	'Uncategorizedcategories'   => array( 'Kategorien ahn Kategorie' ),
	'Uncategorizedimages'       => array( 'Datein ahn Kategorie' ),
	'Uncategorizedpages'        => array( 'Sieden ahn Kategorie' ),
	'Uncategorizedtemplates'    => array( 'Vörlagen ahn Kategorie' ),
	'Undelete'                  => array( 'Wedderhalen' ),
	'Unlockdb'                  => array( 'Datenbank freegeven' ),
	'Unusedcategories'          => array( 'Nich bruukte Kategorien' ),
	'Unusedimages'              => array( 'Nich bruukte Datein' ),
	'Unusedtemplates'           => array( 'Nich bruukte Vörlagen' ),
	'Unwatchedpages'            => array( 'Sieden op keen Oppasslist' ),
	'Upload'                    => array( 'Hoochladen' ),
	'Userlogin'                 => array( 'Anmellen' ),
	'Userlogout'                => array( 'Afmellen' ),
	'Userrights'                => array( 'Brukerrechten' ),
	'Wantedcategories'          => array( 'Wünschte Kategorien' ),
	'Wantedpages'               => array( 'Wünschte Sieden' ),
	'Watchlist'                 => array( 'Oppasslist' ),
	'Whatlinkshere'             => array( 'Wat wiest hier hen' ),
	'Withoutinterwiki'          => array( 'Sieden ahn Spraaklenken' ),
);

