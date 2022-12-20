<?php
/** Low German (Plattdüütsch)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'AKTDAG', 'JETZIGER_KALENDERTAG', 'JETZIGER_TAG', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'AKTDAGNAAM', 'JETZIGER_WOCHENTAG', 'CURRENTDAYNAME' ],
	'currentmonth'              => [ '1', 'AKTMAAND', 'JETZIGER_MONAT', 'JETZIGER_MONAT_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'AKTMAANDNAAM', 'JETZIGER_MONATSNAME', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'AKTMAANDNAAMGEN', 'JETZIGER_MONATSNAME_GENITIV', 'JETZIGER_MONATSNAME_GEN', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'AKTTIED', 'JETZIGE_UHRZEIT', 'CURRENTTIME' ],
	'currentyear'               => [ '1', 'AKTJOHR', 'JETZIGES_JAHR', 'CURRENTYEAR' ],
	'forcetoc'                  => [ '0', '__WIESINHOLTVERTEKEN__', '__INHALTSVERZEICHNIS_ERZWINGEN__', '__FORCETOC__' ],
	'grammar'                   => [ '0', 'GRAMMATIK:', 'GRAMMAR:' ],
	'img_center'                => [ '1', 'zentriert', 'merrn', 'center', 'centre' ],
	'img_framed'                => [ '1', 'gerahmt', 'rahmt', 'frame', 'framed', 'enframed' ],
	'img_left'                  => [ '1', 'links', 'left' ],
	'img_none'                  => [ '1', 'keen', 'ohne', 'none' ],
	'img_right'                 => [ '1', 'rechts', 'right' ],
	'img_thumbnail'             => [ '1', 'duum', 'miniatur', 'mini', 'thumb', 'thumbnail' ],
	'localurl'                  => [ '0', 'STEEDURL:', 'LOKALE_URL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'STEEDURLE:', 'LOKALE_URL_C:', 'LOCALURLE:' ],
	'namespace'                 => [ '1', 'NAAMRUUM', 'NAMENSRAUM', 'NAMESPACE' ],
	'noeditsection'             => [ '0', '__KEENÄNNERNLINK__', '__ABSCHNITTE_NICHT_BEARBEITEN__', '__NOEDITSECTION__' ],
	'notoc'                     => [ '0', '__KEENINHOLTVERTEKEN__', '__KEIN_INHALTSVERZEICHNIS__', '__KEININHALTSVERZEICHNIS__', '__NOTOC__' ],
	'ns'                        => [ '0', 'NR:', 'NS:' ],
	'numberofarticles'          => [ '1', 'ARTIKELTALL', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ],
	'pagename'                  => [ '1', 'SIETNAAM', 'SEITENNAME', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIETNAAME', 'SEITENNAME_URL', 'PAGENAMEE' ],
	'redirect'                  => [ '0', '#wiederleiden', '#WEITERLEITUNG', '#REDIRECT' ],
	'sitename'                  => [ '1', 'STEEDNAAM', 'PROJEKTNAME', 'SITENAME' ],
	'toc'                       => [ '0', '__INHOLTVERTEKEN__', '__INHALTSVERZEICHNIS__', '__TOC__' ],
];

$bookstoreList = [
	'Verteken vun leverbore Böker'  => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1=0&y=0',
	'abebooks.de'                   => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de'                     => 'https://www.amazon.de/exec/obidos/ISBN=$1',
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Systemnarichten' ],
	'Allpages'                  => [ 'Alle_Sieden' ],
	'Ancientpages'              => [ 'Ole_Sieden' ],
	'Blankpage'                 => [ 'Leddige_Sied' ],
	'Block'                     => [ 'Blocken' ],
	'BlockList'                 => [ 'List_vun_blockte_IPs' ],
	'Booksources'               => [ 'ISBN-Söök' ],
	'BrokenRedirects'           => [ 'Kaputte_Redirects' ],
	'Categories'                => [ 'Kategorien' ],
	'ChangePassword'            => [ 'Passwoort_trüchsetten' ],
	'Confirmemail'              => [ 'E-Mail_bestätigen' ],
	'Contributions'             => [ 'Bidrääg' ],
	'CreateAccount'             => [ 'Brukerkonto_anleggen' ],
	'Deadendpages'              => [ 'Sackstraatsieden' ],
	'DoubleRedirects'           => [ 'Dubbelte_Redirects' ],
	'Emailuser'                 => [ 'E-Mail_an_Bruker' ],
	'Export'                    => [ 'Exporteren' ],
	'Fewestrevisions'           => [ 'Kuum_ännerte_Sieden' ],
	'FileDuplicateSearch'       => [ 'Dubbelte-Datein-Söök' ],
	'Filepath'                  => [ 'Dateipadd' ],
	'Import'                    => [ 'Importeren' ],
	'Listadmins'                => [ 'Administraters' ],
	'Listbots'                  => [ 'Bots' ],
	'Listfiles'                 => [ 'Dateilist' ],
	'Listgrouprights'           => [ 'Gruppenrechten' ],
	'Listredirects'             => [ 'List_vun_Redirects' ],
	'Listusers'                 => [ 'Brukers' ],
	'Lockdb'                    => [ 'Datenbank_sparren' ],
	'Log'                       => [ 'Logbook' ],
	'Lonelypages'               => [ 'Weetsieden' ],
	'Longpages'                 => [ 'Lange_Sieden' ],
	'MergeHistory'              => [ 'Versionshistorie_tohoopbringen' ],
	'MIMEsearch'                => [ 'MIME-Typ-Söök' ],
	'Mostcategories'            => [ 'Sieden_mit_vele_Kategorien' ],
	'Mostimages'                => [ 'Veel_bruukte_Datein' ],
	'Mostlinked'                => [ 'Veel_lenkte_Sieden' ],
	'Mostlinkedcategories'      => [ 'Veel_bruukte_Kategorien' ],
	'Mostlinkedtemplates'       => [ 'Veel_bruukte_Vörlagen' ],
	'Mostrevisions'             => [ 'Faken_ännerte_Sieden' ],
	'Movepage'                  => [ 'Schuven' ],
	'Mycontributions'           => [ 'Miene_Bidrääg' ],
	'Mypage'                    => [ 'Miene_Brukersiet' ],
	'Mytalk'                    => [ 'Miene_Diskuschoonssiet' ],
	'Newimages'                 => [ 'Nee_Datein' ],
	'Newpages'                  => [ 'Nee_Sieden' ],
	'Preferences'               => [ 'Instellungen' ],
	'Prefixindex'               => [ 'Sieden_de_anfangt_mit' ],
	'Protectedpages'            => [ 'Schuulte_Sieden' ],
	'Protectedtitles'           => [ 'Sperrte_Titels' ],
	'Randompage'                => [ 'Tofällige_Siet' ],
	'Randomredirect'            => [ 'Tofällig_Redirect' ],
	'Recentchanges'             => [ 'Toletzt_ännert', 'Neeste_Ännern' ],
	'Recentchangeslinked'       => [ 'Ännern_an_lenkte_Sieden' ],
	'Revisiondelete'            => [ 'Versionen_wegsmieten' ],
	'Search'                    => [ 'Söök' ],
	'Shortpages'                => [ 'Korte_Sieden' ],
	'Specialpages'              => [ 'Spezialsieden' ],
	'Statistics'                => [ 'Statistik' ],
	'Uncategorizedcategories'   => [ 'Kategorien_ahn_Kategorie' ],
	'Uncategorizedimages'       => [ 'Datein_ahn_Kategorie' ],
	'Uncategorizedpages'        => [ 'Sieden_ahn_Kategorie' ],
	'Uncategorizedtemplates'    => [ 'Vörlagen_ahn_Kategorie' ],
	'Undelete'                  => [ 'Wedderhalen' ],
	'Unlockdb'                  => [ 'Datenbank_freegeven' ],
	'Unusedcategories'          => [ 'Nich_bruukte_Kategorien' ],
	'Unusedimages'              => [ 'Nich_bruukte_Datein' ],
	'Unusedtemplates'           => [ 'Nich_bruukte_Vörlagen' ],
	'Unwatchedpages'            => [ 'Sieden_op_keen_Oppasslist' ],
	'Upload'                    => [ 'Hoochladen' ],
	'Userlogin'                 => [ 'Anmellen' ],
	'Userlogout'                => [ 'Afmellen' ],
	'Userrights'                => [ 'Brukerrechten' ],
	'Wantedcategories'          => [ 'Wünschte_Kategorien' ],
	'Wantedpages'               => [ 'Wünschte_Sieden' ],
	'Watchlist'                 => [ 'Oppasslist' ],
	'Whatlinkshere'             => [ 'Wat_wiest_hier_hen' ],
	'Withoutinterwiki'          => [ 'Sieden_ahn_Spraaklenken' ],
];
