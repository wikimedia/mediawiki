<?php
/** German (Deutsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author *Surak*
 * @author Als-Holder
 * @author ChrisiPK
 * @author Church of emacs
 * @author DaSch
 * @author Duesentrieb
 * @author Giftpflanze
 * @author Imre
 * @author Jan Luca
 * @author Jens Liebenau
 * @author Jimmy Collins <jimmy.collins@web.de>
 * @author Kghbln
 * @author Khaledelmansoury
 * @author Krinkle
 * @author Kwin
 * @author LWChris
 * @author Li-sung
 * @author Lyzzy
 * @author MF-Warburg
 * @author Melancholie
 * @author Meno25
 * @author Merlissimo
 * @author Metalhead64
 * @author MichaelFrey
 * @author MtaÄ
 * @author Omnipaedista
 * @author Pill
 * @author Purodha
 * @author Raimond Spekking (Raymond) <raimond.spekking@gmail.com> since January 2007
 * @author Red Baron
 * @author Remember the dot
 * @author Revolus
 * @author SVG
 * @author Spacebirdy
 * @author Srhat
 * @author Tbleher
 * @author The Evil IP address
 * @author Tim Bartel (avatar) <wikipedistik@computerkultur.org> small changes
 * @author Tischbeinahe
 * @author UV
 * @author Umherirrender
 * @author W (aka Wuzur)
 * @author Wikifan
 * @author Ziko
 * @author לערי ריינהארט
 * @author ✓
 */

$capitalizeAllNouns = true;

$namespaceNames = array(
	NS_MEDIA            => 'Medium',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Benutzer',
	NS_USER_TALK        => 'Benutzer_Diskussion',
	NS_PROJECT_TALK     => '$1_Diskussion',
	NS_FILE             => 'Datei',
	NS_FILE_TALK        => 'Datei_Diskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussion',
	NS_TEMPLATE         => 'Vorlage',
	NS_TEMPLATE_TALK    => 'Vorlage_Diskussion',
	NS_HELP             => 'Hilfe',
	NS_HELP_TALK        => 'Hilfe_Diskussion',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussion',
);

$namespaceAliases = array(
	'Bild' => NS_FILE,
	'Bild_Diskussion' => NS_FILE_TALK,
);
$namespaceGenderAliases = array(
	NS_USER => array( 'male' => 'Benutzer', 'female' => 'Benutzerin' ),
	NS_USER_TALK => array( 'male' => 'Benutzer_Diskussion', 'female' => 'Benutzerin_Diskussion' ),
);

$bookstoreList = array(
	'abebooks.de' => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'amazon.de' => 'http://www.amazon.de/gp/search/field-isbn=$1',
	'buch.de' => 'http://www.buch.de/shop/home/suche/?sswg=BUCH&sq=$1',
	'Karlsruher Virtueller Katalog (KVK)' => 'http://www.ubka.uni-karlsruhe.de/kvk.html?SB=$1',
	'Lehmanns Fachbuchhandlung' => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1'
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Doppelte_Weiterleitungen' ),
	'BrokenRedirects'           => array( 'Kaputte_Weiterleitungen' ),
	'Disambiguations'           => array( 'Begriffsklärungsverweise' ),
	'Userlogin'                 => array( 'Anmelden' ),
	'Userlogout'                => array( 'Abmelden' ),
	'CreateAccount'             => array( 'Benutzerkonto_anlegen' ),
	'Preferences'               => array( 'Einstellungen' ),
	'Watchlist'                 => array( 'Beobachtungsliste' ),
	'Recentchanges'             => array( 'Letzte_Änderungen' ),
	'Upload'                    => array( 'Hochladen' ),
	'Listfiles'                 => array( 'Dateien', 'Dateiliste' ),
	'Newimages'                 => array( 'Neue_Dateien' ),
	'Listusers'                 => array( 'Benutzer', 'Benutzerliste' ),
	'Listgrouprights'           => array( 'Gruppenrechte' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Zufällige_Seite' ),
	'Lonelypages'               => array( 'Verwaiste_Seiten' ),
	'Uncategorizedpages'        => array( 'Nicht_kategorisierte_Seiten' ),
	'Uncategorizedcategories'   => array( 'Nicht_kategorisierte_Kategorien' ),
	'Uncategorizedimages'       => array( 'Nicht_kategorisierte_Dateien' ),
	'Uncategorizedtemplates'    => array( 'Nicht_kategorisierte_Vorlagen' ),
	'Unusedcategories'          => array( 'Unbenutzte_Kategorien' ),
	'Unusedimages'              => array( 'Unbenutzte_Dateien' ),
	'Wantedpages'               => array( 'Gewünschte_Seiten' ),
	'Wantedcategories'          => array( 'Gewünschte_Kategorien' ),
	'Wantedfiles'               => array( 'Fehlende_Dateien' ),
	'Wantedtemplates'           => array( 'Fehlende_Vorlagen' ),
	'Mostlinked'                => array( 'Meistverlinkte_Seiten' ),
	'Mostlinkedcategories'      => array( 'Meistbenutzte_Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Meistbenutzte_Vorlagen' ),
	'Mostimages'                => array( 'Meistbenutzte_Dateien' ),
	'Mostcategories'            => array( 'Meistkategorisierte_Seiten' ),
	'Mostrevisions'             => array( 'Meistbearbeitete_Seiten' ),
	'Fewestrevisions'           => array( 'Wenigstbearbeitete_Seiten' ),
	'Shortpages'                => array( 'Kürzeste_Seiten' ),
	'Longpages'                 => array( 'Längste_Seiten' ),
	'Newpages'                  => array( 'Neue_Seiten' ),
	'Ancientpages'              => array( 'Älteste_Seiten' ),
	'Deadendpages'              => array( 'Sackgassenseiten' ),
	'Protectedpages'            => array( 'Geschützte_Seiten' ),
	'Protectedtitles'           => array( 'Geschützte_Titel', 'Gesperrte_Titel' ),
	'Allpages'                  => array( 'Alle_Seiten' ),
	'Prefixindex'               => array( 'Präfixindex' ),
	'Ipblocklist'               => array( 'Liste_der_Sperren', 'Gesperrte_IP-Adressen', 'Gesperrte_IPs' ),
	'Unblock'                   => array( 'Freigeben' ),
	'Specialpages'              => array( 'Spezialseiten' ),
	'Contributions'             => array( 'Beiträge' ),
	'Emailuser'                 => array( 'E-Mail' ),
	'Confirmemail'              => array( 'E-Mail_bestaetigen', 'E-Mail_bestätigen' ),
	'Whatlinkshere'             => array( 'Linkliste', 'Verweisliste' ),
	'Recentchangeslinked'       => array( 'Änderungen_an_verlinkten_Seiten' ),
	'Movepage'                  => array( 'Verschieben' ),
	'Blockme'                   => array( 'Proxy-Sperre' ),
	'Booksources'               => array( 'ISBN-Suche' ),
	'Categories'                => array( 'Kategorien' ),
	'Export'                    => array( 'Exportieren' ),
	'Allmessages'               => array( 'MediaWiki-Systemnachrichten' ),
	'Log'                       => array( 'Logbuch' ),
	'Blockip'                   => array( 'Sperren' ),
	'Undelete'                  => array( 'Wiederherstellen' ),
	'Import'                    => array( 'Importieren' ),
	'Lockdb'                    => array( 'Datenbank_sperren' ),
	'Unlockdb'                  => array( 'Datenbank_entsperren' ),
	'Userrights'                => array( 'Benutzerrechte' ),
	'MIMEsearch'                => array( 'MIME-Typ-Suche' ),
	'FileDuplicateSearch'       => array( 'Datei-Duplikat-Suche' ),
	'Unwatchedpages'            => array( 'Ignorierte_Seiten', 'Unbeobachtete_Seiten' ),
	'Listredirects'             => array( 'Weiterleitungen' ),
	'Revisiondelete'            => array( 'Versionslöschung' ),
	'Unusedtemplates'           => array( 'Unbenutzte_Vorlagen' ),
	'Randomredirect'            => array( 'Zufällige_Weiterleitung' ),
	'Mypage'                    => array( 'Meine_Benutzerseite' ),
	'Mytalk'                    => array( 'Meine_Diskussionsseite' ),
	'Mycontributions'           => array( 'Meine_Beiträge' ),
	'Myuploads'                 => array( 'Meine_hochgeladenen_Dateien' ),
	'PermanentLink'             => array( 'Permanenter_Link' ),
	'Listadmins'                => array( 'Administratoren' ),
	'Listbots'                  => array( 'Bots' ),
	'Popularpages'              => array( 'Beliebteste_Seiten' ),
	'Search'                    => array( 'Suche' ),
	'Resetpass'                 => array( 'Passwort_ändern', 'Passwort_zurücksetzen' ),
	'Withoutinterwiki'          => array( 'Fehlende_Interwikis' ),
	'MergeHistory'              => array( 'Versionsgeschichten_vereinen' ),
	'Filepath'                  => array( 'Dateipfad' ),
	'Invalidateemail'           => array( 'E-Mail_nicht_bestaetigen', 'E-Mail_nicht_bestätigen' ),
	'Blankpage'                 => array( 'Leerseite', 'Leere_Seite' ),
	'LinkSearch'                => array( 'Weblink-Suche' ),
	'DeletedContributions'      => array( 'Gelöschte_Beiträge' ),
	'Tags'                      => array( 'Markierungen' ),
	'Activeusers'               => array( 'Aktive_Benutzer' ),
	'RevisionMove'              => array( 'Version_verschieben' ),
	'ComparePages'              => array( 'Seiten_vergleichen' ),
	'Badtitle'                  => array( 'Ungültiger_Titel' ),
	'DisableAccount'            => array( 'Benutzerkonto_deaktivieren' ),
);

$datePreferences = array(
	'default',
	'dmyt',
	'dmyts',
	'dmy',
	'ymd',
	'ISO 8601'
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. M Y, H:i',

	'dmyts time' => 'H:i:s',
	'dmyts date' => 'j. F Y',
	'dmyts both' => 'j. M Y, H:i:s',

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'H:i, j. M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns'
);

$magicWords = array(
	'redirect'              => array( '0', '#WEITERLEITUNG', '#REDIRECT' ),
	'notoc'                 => array( '0', '__KEIN_INHALTSVERZEICHNIS__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__KEINE_GALERIE__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__INHALTSVERZEICHNIS_ERZWINGEN__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__INHALTSVERZEICHNIS__', '__TOC__' ),
	'noeditsection'         => array( '0', '__ABSCHNITTE_NICHT_BEARBEITEN__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__KEINKOPF__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'JETZIGER_MONAT', 'JETZIGER_MONAT_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'JETZIGER_MONAT_1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'JETZIGER_MONATSNAME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'JETZIGER_MONATSNAME_GENITIV', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'JETZIGER_MONATSNAME_KURZ', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'JETZIGER_KALENDERTAG', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'JETZIGER_KALENDERTAG_2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'JETZIGER_WOCHENTAG', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'JETZIGES_JAHR', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'JETZIGE_UHRZEIT', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'JETZIGE_STUNDE', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'LOKALER_MONAT', 'LOKALER_MONAT_2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'LOKALER_MONAT_1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'LOKALER_MONATSNAME', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'LOKALER_MONATSNAME_GENITIV', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'LOKALER_MONATSNAME_KURZ', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'LOKALER_KALENDERTAG', 'LOCALDAY' ),
	'localday2'             => array( '1', 'LOKALER_KALENDERTAG_2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'LOKALER_WOCHENTAG', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'LOKALES_JAHR', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'LOKALE_UHRZEIT', 'LOCALTIME' ),
	'localhour'             => array( '1', 'LOKALE_STUNDE', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'SEITENANZAHL', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'DATEIANZAHL', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'BENUTZERANZAHL', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'AKTIVE_BENUTZER', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'BEARBEITUNGSANZAHL', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'BETRACHTUNGEN', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'SEITENNAME', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'SEITENNAME_URL', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NAMENSRAUM', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NAMENSRAUM_URL', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'DISKUSSIONSNAMENSRAUM', 'DISK_NR', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'DISKUSSIONSNAMENSRAUM_URL', 'DISK_NR_URL', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'HAUPTNAMENSRAUM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'HAUPTNAMENSRAUM_URL', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'VOLLER_SEITENNAME', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'VOLLER_SEITENNAME_URL', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'UNTERSEITE', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'UNTERSEITE_URL', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'OBERSEITE', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'OBERSEITE_URL', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'DISKUSSIONSSEITE', 'DISK', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'DISKUSSIONSSEITE_URL', 'DISK_URL', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'HAUPTSEITE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'HAUPTSEITE_URL', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'subst'                 => array( '0', 'ERS:', 'SUBST:' ),
	'img_thumbnail'         => array( '1', 'miniatur', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'miniatur=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'rechts', 'right' ),
	'img_left'              => array( '1', 'links', 'left' ),
	'img_none'              => array( '1', 'ohne', 'none' ),
	'img_center'            => array( '1', 'zentriert', 'center', 'centre' ),
	'img_framed'            => array( '1', 'gerahmt', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'rahmenlos', 'frameless' ),
	'img_page'              => array( '1', 'seite=$1', 'seite $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'hochkant', 'hochkant=$1', 'hochkant $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'rand', 'border' ),
	'img_sub'               => array( '1', 'tiefgestellt', 'sub' ),
	'img_super'             => array( '1', 'hochgestellt', 'super', 'sup' ),
	'img_link'              => array( '1', 'verweis=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'alternativtext=$1', 'alt=$1' ),
	'int'                   => array( '0', 'NACHRICHT:', 'INT:' ),
	'sitename'              => array( '1', 'PROJEKTNAME', 'SITENAME' ),
	'ns'                    => array( '0', 'NR:', 'NS:' ),
	'nse'                   => array( '0', 'NR_URL:', 'NSE:' ),
	'localurl'              => array( '0', 'LOKALE_URL:', 'LOCALURL:' ),
	'scriptpath'            => array( '0', 'SKRIPTPFAD', 'SCRIPTPATH' ),
	'stylepath'             => array( '0', 'STYLEPFAD', 'STYLEPATH' ),
	'grammar'               => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'GESCHLECHT:', 'GENDER:' ),
	'currentweek'           => array( '1', 'JETZIGE_KALENDERWOCHE', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'JETZIGER_WOCHENTAG_ZAHL', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'LOKALE_KALENDERWOCHE', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'LOKALER_WOCHENTAG_ZAHL', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'REVISIONSID', 'REVISIONID' ),
	'revisionday'           => array( '1', 'REVISIONSTAG', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'REVISIONSTAG2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'REVISIONSMONAT', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'REVISIONSJAHR', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'REVISIONSZEITSTEMPEL', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'REVISIONSBENUTZER', 'REVISIONUSER' ),
	'fullurl'               => array( '0', 'VOLLSTÄNDIGE_URL:', 'FULLURL:' ),
	'lcfirst'               => array( '0', 'INITIAL_KLEIN:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'INITIAL_GROSS:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'KLEIN:', 'LC:' ),
	'uc'                    => array( '0', 'GROSS:', 'UC:' ),
	'raw'                   => array( '0', 'ROH:', 'RAW:' ),
	'displaytitle'          => array( '1', 'SEITENTITEL', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__NEUER_ABSCHNITTSLINK__', '__PLUS_LINK__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__KEIN_NEUER_ABSCHNITTSLINK__', '__KEIN_PLUS_LINK__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'JETZIGE_VERSION', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'URLENKODIERT:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'SPRUNGMARKEENKODIERT:', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'JETZIGER_ZEITSTEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'LOKALER_ZEITSTEMPEL', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'TEXTAUSRICHTUNG', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#SPRACHE:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'INHALTSSPRACHE', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'SEITEN_IM_NAMENSRAUM:', 'SEITEN_NR:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'ADMINANZAHL', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ZAHLENFORMAT', 'FORMATNUM' ),
	'padleft'               => array( '0', 'FÜLLENLINKS', 'PADLEFT' ),
	'padright'              => array( '0', 'FÜLLENRECHTS', 'PADRIGHT' ),
	'special'               => array( '0', 'spezial', 'special' ),
	'defaultsort'           => array( '1', 'SORTIERUNG:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'DATEIPFAD:', 'FILEPATH:' ),
	'hiddencat'             => array( '1', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'SEITEN_IN_KATEGORIE', 'SEITEN_KAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'SEITENGRÖSSE', 'PAGESIZE' ),
	'index'                 => array( '1', '__INDIZIEREN__', '__INDEX__' ),
	'noindex'               => array( '1', '__NICHT_INDIZIEREN__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'BENUTZER_IN_GRUPPE', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__PERMANENTE_WEITERLEITUNG__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'SCHUTZSTATUS', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'DATUMSFORMAT', 'formatdate', 'dateformat' ),
);

$imageFiles = array(
	'button-bold'     => 'de/button_bold.png',
	'button-italic'   => 'de/button_italic.png',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Links unterstreichen:',
'tog-highlightbroken'         => 'Links auf nicht vorhandene Seiten hervorheben <a href="" class="new">Beispiel</a> (Alternative: wie dieser<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Text als Blocksatz',
'tog-hideminor'               => 'Kleine Änderungen in den „Letzten Änderungen“ ausblenden',
'tog-hidepatrolled'           => 'Kontrollierte Änderungen in den „Letzten Änderungen“ ausblenden',
'tog-newpageshidepatrolled'   => 'Kontrollierte Seiten in den „Neuen Seiten“ ausblenden',
'tog-extendwatchlist'         => 'Erweiterte Beobachtungsliste zur Anzeige aller Änderungen',
'tog-usenewrc'                => 'Erweiterte Darstellung der „Letzten Änderungen“ (benötigt JavaScript)',
'tog-numberheadings'          => 'Überschriften automatisch nummerieren',
'tog-showtoolbar'             => 'Bearbeiten-Werkzeugleiste anzeigen (benötigt JavaScript)',
'tog-editondblclick'          => 'Seiten mit Doppelklick bearbeiten (benötigt JavaScript)',
'tog-editsection'             => 'Links zum Bearbeiten einzelner Abschnitte anzeigen',
'tog-editsectiononrightclick' => 'Einzelne Abschnitte per Rechtsklick bearbeiten (benötigt JavaScript)',
'tog-showtoc'                 => 'Anzeigen eines Inhaltsverzeichnisses bei Seiten mit mehr als drei Überschriften',
'tog-rememberpassword'        => 'Mit diesem Browser dauerhaft angemeldet bleiben (maximal $1 {{PLURAL:$1|Tag|Tage}})',
'tog-watchcreations'          => 'Selbst erstellte Seiten automatisch beobachten',
'tog-watchdefault'            => 'Selbst geänderte Seiten automatisch beobachten',
'tog-watchmoves'              => 'Selbst verschobene Seiten automatisch beobachten',
'tog-watchdeletion'           => 'Selbst gelöschte Seiten automatisch beobachten',
'tog-minordefault'            => 'Eigene Änderungen standardmäßig als geringfügig markieren',
'tog-previewontop'            => 'Vorschau oberhalb des Bearbeitungsfensters anzeigen',
'tog-previewonfirst'          => 'Beim ersten Bearbeiten immer die Vorschau anzeigen',
'tog-nocache'                 => 'Seitencache des Browsers deaktivieren',
'tog-enotifwatchlistpages'    => 'Bei Änderungen an beobachteten Seiten E-Mails senden',
'tog-enotifusertalkpages'     => 'Bei Änderungen an meiner Benutzer-Diskussionsseite E-Mails senden',
'tog-enotifminoredits'        => 'Auch bei kleinen Änderungen an beobachteten Seiten E-Mails senden',
'tog-enotifrevealaddr'        => 'Meine E-Mail-Adresse in Benachrichtigungs-E-Mails anzeigen',
'tog-shownumberswatching'     => 'Anzahl der beobachtenden Benutzer anzeigen',
'tog-oldsig'                  => 'Vorschau der aktuellen Signatur:',
'tog-fancysig'                => 'Signatur als Wikitext behandeln (ohne automatische Verlinkung)',
'tog-externaleditor'          => 'Externen Editor als Standard benutzen (nur für Experten, erfordert spezielle Einstellungen auf dem eigenen Computer. [http://www.mediawiki.org/wiki/Manual:External_editors Weitere Informationen hierzu.])',
'tog-externaldiff'            => 'Externes Programm für Versionsunterschiede als Standard benutzen (nur für Experten, erfordert spezielle Einstellungen auf dem eigenen Computer. [http://www.mediawiki.org/wiki/Manual:External_editors Weitere Informationen hierzu.])',
'tog-showjumplinks'           => '„Wechseln zu“-Links aktivieren',
'tog-uselivepreview'          => 'Live-Vorschau nutzen (benötigt JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Warnen, wenn beim Speichern die Zusammenfassung fehlt',
'tog-watchlisthideown'        => 'Eigene Bearbeitungen in der Beobachtungsliste ausblenden',
'tog-watchlisthidebots'       => 'Bearbeitungen durch Bots in der Beobachtungsliste ausblenden',
'tog-watchlisthideminor'      => 'Kleine Bearbeitungen in der Beobachtungsliste ausblenden',
'tog-watchlisthideliu'        => 'Bearbeitungen angemeldeter Benutzer in der Beobachtungsliste ausblenden',
'tog-watchlisthideanons'      => 'Bearbeitungen anonymer Benutzer (IP-Adressen) in der Beobachtungsliste ausblenden',
'tog-watchlisthidepatrolled'  => 'Kontrollierte Änderungen in der Beobachtungsliste ausblenden',
'tog-nolangconversion'        => 'Konvertierung von Sprachvarianten deaktivieren',
'tog-ccmeonemails'            => 'Schicke mir Kopien der E-Mails, die ich anderen Benutzern sende',
'tog-diffonly'                => 'Zeige beim Versionsvergleich nur die Unterschiede, nicht die vollständige Seite',
'tog-showhiddencats'          => 'Zeige versteckte Kategorien',
'tog-noconvertlink'           => 'Konvertierung des Titels deaktivieren',
'tog-norollbackdiff'          => 'Unterschied nach dem Zurücksetzen unterdrücken',

'underline-always'  => 'immer',
'underline-never'   => 'nie',
'underline-default' => 'abhängig von Browsereinstellung',

# Font style option in Special:Preferences
'editfont-style'     => 'Schriftart für den Text im Bearbeitungsfenster:',
'editfont-default'   => 'Browserstandard',
'editfont-monospace' => 'Schrift mit fester Zeichenbreite',
'editfont-sansserif' => 'Serifenlose Groteskschrift',
'editfont-serif'     => 'Schrift mit Serifen',

# Dates
'sunday'        => 'Sonntag',
'monday'        => 'Montag',
'tuesday'       => 'Dienstag',
'wednesday'     => 'Mittwoch',
'thursday'      => 'Donnerstag',
'friday'        => 'Freitag',
'saturday'      => 'Samstag',
'sun'           => 'So',
'mon'           => 'Mo',
'tue'           => 'Di',
'wed'           => 'Mi',
'thu'           => 'Do',
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
'january-gen'   => 'Januars',
'february-gen'  => 'Februars',
'march-gen'     => 'Märzes',
'april-gen'     => 'Aprils',
'may-gen'       => 'Mais',
'june-gen'      => 'Junis',
'july-gen'      => 'Julis',
'august-gen'    => 'Augusts',
'september-gen' => 'Septembers',
'october-gen'   => 'Oktobers',
'november-gen'  => 'Novembers',
'december-gen'  => 'Dezembers',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mär.',
'apr'           => 'Apr.',
'may'           => 'Mai',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Nov.',
'dec'           => 'Dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Seiten in der Kategorie „$1“',
'subcategories'                  => 'Unterkategorien',
'category-media-header'          => 'Medien in der Kategorie „$1“',
'category-empty'                 => "''Diese Kategorie enthält zurzeit keine Seiten oder Medien.''",
'hidden-categories'              => '{{PLURAL:$1|Versteckte Kategorie|Versteckte Kategorien}}',
'hidden-category-category'       => 'Versteckte Kategorien',
'category-subcat-count'          => '{{PLURAL:$2|Diese Kategorie enthält folgende Unterkategorie:|{{PLURAL:$1|Folgende Unterkategorie ist eine von insgesamt $2 Unterkategorien in dieser Kategorie:|Es werden $1 von insgesamt $2 Unterkategorien in dieser Kategorie angezeigt:}}}}',
'category-subcat-count-limited'  => 'Diese Kategorie enthält folgende {{PLURAL:$1|Unterkategorie|$1 Unterkategorien}}:',
'category-article-count'         => '{{PLURAL:$2|Diese Kategorie enthält folgende Seite:|{{PLURAL:$1|Folgende Seite ist eine von insgesamt $2 Seiten in dieser Kategorie:|Es werden $1 von insgesamt $2 Seiten in dieser Kategorie angezeigt:}}}}',
'category-article-count-limited' => 'Folgende {{PLURAL:$1|Seite ist|$1 Seiten sind}} in dieser Kategorie enthalten:',
'category-file-count'            => '{{PLURAL:$2|Diese Kategorie enthält folgende Datei:|{{PLURAL:$1|Folgende Datei ist eine von insgesamt $2 Dateien in dieser Kategorie:|Es werden $1 von insgesamt $2 Dateien in dieser Kategorie angezeigt:}}}}',
'category-file-count-limited'    => 'Folgende {{PLURAL:$1|Datei ist|$1 Dateien sind}} in dieser Kategorie enthalten:',
'listingcontinuesabbrev'         => '(Fortsetzung)',
'index-category'                 => 'Indizierte Seiten',
'noindex-category'               => 'Nichtindizierte Seiten',

'mainpagetext'      => "'''MediaWiki wurde erfolgreich installiert.'''",
'mainpagedocfooter' => 'Hilfe zur Benutzung und Konfiguration der Wiki-Software findest du im [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].

== Starthilfen ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste der Konfigurationsvariablen]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'about'         => 'Über',
'article'       => 'Seite',
'newwindow'     => '(wird in einem neuen Fenster geöffnet)',
'cancel'        => 'Abbrechen',
'moredotdotdot' => 'Mehr …',
'mypage'        => 'Eigene Seite',
'mytalk'        => 'Eigene Diskussion',
'anontalk'      => 'Diskussionsseite dieser IP',
'navigation'    => 'Navigation',
'and'           => '&#32;und',

# Cologne Blue skin
'qbfind'         => 'Finden',
'qbbrowse'       => 'Blättern',
'qbedit'         => 'Bearbeiten',
'qbpageoptions'  => 'Seitenoptionen',
'qbpageinfo'     => 'Seitendaten',
'qbmyoptions'    => 'Meine Seiten',
'qbspecialpages' => 'Spezialseiten',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Abschnitt hinzufügen',
'vector-action-delete'           => 'Löschen',
'vector-action-move'             => 'Verschieben',
'vector-action-protect'          => 'Schützen',
'vector-action-undelete'         => 'Wiederherstellen',
'vector-action-unprotect'        => 'Freigeben',
'vector-simplesearch-preference' => 'Erweiterte Suchvorschläge aktivieren (nur Vector)',
'vector-view-create'             => 'Erstellen',
'vector-view-edit'               => 'Bearbeiten',
'vector-view-history'            => 'Versionsgeschichte',
'vector-view-view'               => 'Lesen',
'vector-view-viewsource'         => 'Quelltext anzeigen',
'actions'                        => 'Aktionen',
'namespaces'                     => 'Namensräume',
'variants'                       => 'Varianten',

'errorpagetitle'    => 'Fehler',
'returnto'          => 'Zurück zur Seite $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Hilfe',
'search'            => 'Suche',
'searchbutton'      => 'Suchen',
'go'                => 'Ausführen',
'searcharticle'     => 'Seite',
'history'           => 'Versionen',
'history_short'     => 'Versionen/Autoren',
'updatedmarker'     => 'geändert seit meinem letzten Besuch',
'info_short'        => 'Information',
'printableversion'  => 'Druckversion',
'permalink'         => 'Permanenter Link',
'print'             => 'Drucken',
'view'              => 'Lesen',
'edit'              => 'Bearbeiten',
'create'            => 'Erstellen',
'editthispage'      => 'Seite bearbeiten',
'create-this-page'  => 'Seite erstellen',
'delete'            => 'Löschen',
'deletethispage'    => 'Diese Seite löschen',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiederherstellen',
'viewdeleted_short' => '{{PLURAL:$1|Eine gelöschte Version|$1 gelöschte Versionen}} ansehen',
'protect'           => 'Schützen',
'protect_change'    => 'ändern',
'protectthispage'   => 'Seite schützen',
'unprotect'         => 'Freigeben',
'unprotectthispage' => 'Schutz aufheben',
'newpage'           => 'Neue Seite',
'talkpage'          => 'Diese Seite diskutieren',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Spezialseite',
'personaltools'     => 'Meine Werkzeuge',
'postcomment'       => 'Neuer Abschnitt',
'articlepage'       => 'Inhaltsseite anzeigen',
'talk'              => 'Diskussion',
'views'             => 'Ansichten',
'toolbox'           => 'Werkzeuge',
'userpage'          => 'Benutzerseite anzeigen',
'projectpage'       => 'Projektseite anzeigen',
'imagepage'         => 'Dateiseite anzeigen',
'mediawikipage'     => 'Meldungsseite anzeigen',
'templatepage'      => 'Vorlagenseite anzeigen',
'viewhelppage'      => 'Hilfeseite anzeigen',
'categorypage'      => 'Kategorieseite anzeigen',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'In anderen Sprachen',
'redirectedfrom'    => '(Weitergeleitet von $1)',
'redirectpagesub'   => 'Weiterleitung',
'lastmodifiedat'    => 'Diese Seite wurde zuletzt am $1 um $2 Uhr geändert.',
'viewcount'         => 'Diese Seite wurde bisher {{PLURAL:$1|einmal|$1-mal}} abgerufen.',
'protectedpage'     => 'Geschützte Seite',
'jumpto'            => 'Wechseln zu:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Suche',
'view-pool-error'   => 'Entschuldigung, die Server sind im Moment überlastet.
Zu viele Benutzer versuchen, diese Seite zu besuchen.
Bitte warte einige Minuten, bevor du es noch einmal versuchst.

$1',
'pool-timeout'      => 'Zeitablauf während des Wartens auf die Sperrung',
'pool-queuefull'    => 'Poolwarteschlange ist voll',
'pool-errorunknown' => 'Unbekannter Fehler',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Über {{SITENAME}}',
'aboutpage'            => 'Project:Über',
'copyright'            => 'Inhalt ist verfügbar unter der $1.',
'copyrightpage'        => '{{ns:project}}:Urheberrechte',
'currentevents'        => 'Aktuelle Ereignisse',
'currentevents-url'    => 'Project:Aktuelle Ereignisse',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Bearbeitungshilfe',
'edithelppage'         => 'Help:Bearbeiten',
'helppage'             => 'Help:Inhaltsverzeichnis',
'mainpage'             => 'Hauptseite',
'mainpage-description' => 'Hauptseite',
'policy-url'           => 'Project:Richtlinien',
'portal'               => 'Gemeinschafts-Portal',
'portal-url'           => 'Project:Gemeinschafts-Portal',
'privacy'              => 'Datenschutz',
'privacypage'          => 'Project:Datenschutz',

'badaccess'        => 'Keine ausreichenden Rechte',
'badaccess-group0' => 'Du hast nicht die erforderliche Berechtigung für diese Aktion.',
'badaccess-groups' => 'Diese Aktion ist beschränkt auf Benutzer, die {{PLURAL:$2|der Gruppe|einer der Gruppen}} „$1“ angehören.',

'versionrequired'     => 'Version $1 von MediaWiki ist erforderlich.',
'versionrequiredtext' => 'Version $1 von MediaWiki ist erforderlich, um diese Seite zu nutzen.
Siehe die [[Special:Version|Versionsseite]]',

'ok'                      => 'OK',
'pagetitle'               => '$1 – {{SITENAME}}',
'retrievedfrom'           => 'Von „$1“',
'youhavenewmessages'      => 'Du hast $1 auf deiner Diskussionsseite ($2).',
'newmessageslink'         => 'neue Nachrichten',
'newmessagesdifflink'     => 'Letzte Änderung',
'youhavenewmessagesmulti' => 'Du hast neue Nachrichten: $1',
'editsection'             => 'Bearbeiten',
'editold'                 => 'Bearbeiten',
'viewsourceold'           => 'Quelltext anzeigen',
'editlink'                => 'bearbeiten',
'viewsourcelink'          => 'Quelltext anzeigen',
'editsectionhint'         => 'Abschnitt bearbeiten: $1',
'toc'                     => 'Inhaltsverzeichnis',
'showtoc'                 => 'Anzeigen',
'hidetoc'                 => 'Verbergen',
'collapsible-collapse'    => 'Einklappen',
'collapsible-expand'      => 'Ausklappen',
'thisisdeleted'           => '$1 ansehen oder wiederherstellen?',
'viewdeleted'             => '$1 anzeigen?',
'restorelink'             => '$1 {{PLURAL:$1|gelöschte Version|gelöschte Versionen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungültiger Feed-Abonnement-Typ.',
'feed-unavailable'        => 'Es stehen keine Feeds zur Verfügung.',
'site-rss-feed'           => 'RSS-Feed für „$1“',
'site-atom-feed'          => 'Atom-Feed für „$1“',
'page-rss-feed'           => 'RSS-Feed für „$1“',
'page-atom-feed'          => 'Atom-Feed für „$1“',
'red-link-title'          => '$1 (Seite nicht vorhanden)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Seite',
'nstab-user'      => 'Benutzerseite',
'nstab-media'     => 'Medienseite',
'nstab-special'   => 'Spezialseite',
'nstab-project'   => 'Projektseite',
'nstab-image'     => 'Datei',
'nstab-mediawiki' => 'MediaWiki-Systemnachricht',
'nstab-template'  => 'Vorlage',
'nstab-help'      => 'Hilfeseite',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Diese Aktion gibt es nicht',
'nosuchactiontext'  => 'Die in der URL angegebene Aktion wird von MediaWiki nicht unterstützt.
Es kann ein Schreibfehler in der URL vorliegen oder es wurde ein fehlerhafter Link angeklickt.
Es kann sich auch um einen Programmierfehler in der Software, die auf {{SITENAME}} benutzt wird, handeln.',
'nosuchspecialpage' => 'Spezialseite nicht vorhanden',
'nospecialpagetext' => '<strong>Die aufgerufene Spezialseite ist nicht vorhanden.</strong>

Alle verfügbaren Spezialseiten sind in der [[Special:SpecialPages|Liste der Spezialseiten]] zu finden.',

# General errors
'error'                => 'Fehler',
'databaseerror'        => 'Datenbankfehler',
'dberrortext'          => 'Es ist ein Datenbankfehler aufgetreten.
Der Grund kann ein Programmierfehler sein.
Die letzte Datenbankabfrage lautete:
<blockquote><tt>$1</tt></blockquote>
aus der Funktion „<tt>$2</tt>“.
Die Datenbank meldete den Fehler „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: „$1“ aus der Funktion „<tt>$2</tt>“.
Die Datenbank meldete den Fehler: „<tt>$3: $4</tt>“.',
'laggedslavemode'      => "'''Achtung:''' Die angezeigte Seite könnte unter Umständen nicht die letzten Bearbeitungen enthalten.",
'readonly'             => 'Datenbank gesperrt',
'enterlockreason'      => 'Bitte gib einen Grund ein, warum die Datenbank gesperrt werden soll und eine Abschätzung über die Dauer der Sperrung',
'readonlytext'         => 'Die Datenbank ist vorübergehend für Neueinträge und Änderungen gesperrt. Bitte versuche es später noch einmal.

Grund der Sperrung: $1',
'missing-article'      => 'Der Text von „$1“ $2 wurde nicht in der Datenbank gefunden.

Die Seite ist möglicherweise gelöscht oder verschoben worden.

Falls dies nicht  zutrifft, hast du eventuell einen Fehler in der Software gefunden. Bitte melde dies einem [[Special:ListUsers/sysop|Administrator]] unter Nennung der URL.',
'missingarticle-rev'   => '(Versionsnummer: $1)',
'missingarticle-diff'  => '(Unterschied zwischen Versionen: $1, $2)',
'readonly_lag'         => 'Die Datenbank wurde automatisch für Schreibzugriffe gesperrt, damit sich die verteilten Datenbankserver (slaves) mit dem Hauptdatenbankserver (master) abgleichen können.',
'internalerror'        => 'Interner Fehler',
'internalerror_info'   => 'Interner Fehler: $1',
'fileappenderrorread'  => '„$1“ konnte während des Hinzufügens nicht gelesen werden.',
'fileappenderror'      => 'Konnte „$1“ nicht an „$2“ anhängen.',
'filecopyerror'        => 'Die Datei „$1“ konnte nicht nach „$2“ kopiert werden.',
'filerenameerror'      => 'Die Datei „$1“ konnte nicht nach „$2“ umbenannt werden.',
'filedeleteerror'      => 'Die Datei „$1“ konnte nicht gelöscht werden.',
'directorycreateerror' => 'Das Verzeichnis „$1“ konnte nicht angelegt werden.',
'filenotfound'         => 'Die Datei „$1“ wurde nicht gefunden.',
'fileexistserror'      => 'In die Datei „$1“ konnte nicht geschrieben werden, da die Datei bereits vorhanden ist.',
'unexpected'           => 'Unerwarteter Wert: „$1“ = „$2“',
'formerror'            => 'Fehler: Die Eingaben konnten nicht verarbeitet werden.',
'badarticleerror'      => 'Diese Aktion kann auf diese Seite nicht angewendet werden.',
'cannotdelete'         => 'Die Seite oder Datei „$1“ kann nicht gelöscht werden.
Möglicherweise wurde sie bereits von jemand anders gelöscht.',
'badtitle'             => 'Ungültiger Titel',
'badtitletext'         => 'Der Titel der angeforderten Seite ist ungültig, leer oder ein ungültiger Sprachlink von einem anderen Wiki.',
'perfcached'           => 'Die folgenden Daten stammen aus dem Cache und sind möglicherweise nicht aktuell:',
'perfcachedts'         => 'Diese Daten stammen aus dem Cache, letzte Aktualisierung: $2, $3 Uhr',
'querypage-no-updates' => "'''Die Aktualisierungsfunktion dieser Seite ist zurzeit deaktiviert. Die Daten werden bis auf Weiteres nicht erneuert.'''",
'wrong_wfQuery_params' => 'Falsche Parameter für wfQuery()<br />
Funktion: $1<br />
Abfrage: $2',
'viewsource'           => 'Quelltext anzeigen',
'viewsourcefor'        => 'von $1',
'actionthrottled'      => 'Aktionsanzahl limitiert',
'actionthrottledtext'  => 'Im Rahmen einer Anti-Spam-Maßnahme kann diese Aktion in einem kurzen Zeitabstand nur begrenzt oft ausgeführt werden. Diese Grenze hast du überschritten.
Bitte versuche es in ein paar Minuten erneut.',
'protectedpagetext'    => 'Diese Seite wurde geschützt, um Bearbeitungen zu verhindern.',
'viewsourcetext'       => 'Du kannst den Quelltext dieser Seite betrachten und kopieren:',
'protectedinterface'   => 'Diese Seite enthält Text für die Benutzeroberfläche der Software und ist geschützt, um Missbrauch vorzubeugen.',
'editinginterface'     => "'''Warnung:''' Diese Seite enthält von der MediaWiki-Software genutzten Text.
Änderungen auf dieser Seite wirken sich auf die Benutzeroberfläche aus.
Ziehe bitte im Fall von Übersetzungen in Betracht, diese bei [http://translatewiki.net/wiki/Main_Page?setlang=de translatewiki.net], der Lokalisierungsplattform für MediaWiki, durchzuführen.",
'sqlhidden'            => '(SQL-Abfrage versteckt)',
'cascadeprotected'     => 'Diese Seite ist zur Bearbeitung gesperrt. Sie ist in die {{PLURAL:$1|folgende Seite|folgenden Seiten}} eingebunden, die mittels der Kaskadensperroption geschützt {{PLURAL:$1|ist|sind}}:
$2',
'namespaceprotected'   => "Du hast keine Berechtigung, Seiten im '''$1'''-Namensraum zu bearbeiten.",
'customcssjsprotected' => 'Du bist nicht berechtigt, diese Seite zu bearbeiten, da sie zu den persönlichen Einstellungen eines anderen Benutzers gehört.',
'ns-specialprotected'  => 'Spezialseiten können nicht bearbeitet werden.',
'titleprotected'       => "Eine Seite mit diesem Namen kann nicht angelegt werden.
Die Sperre wurde durch [[User:$1|$1]] mit der Begründung ''„$2“'' eingerichtet.",

# Virus scanner
'virus-badscanner'     => "Fehlerhafte Konfiguration: unbekannter Virenscanner: ''$1''",
'virus-scanfailed'     => 'Scan fehlgeschlagen (code $1)',
'virus-unknownscanner' => 'Unbekannter Virenscanner:',

# Login and logout pages
'logouttext'                 => "'''Du bist nun abgemeldet.'''

Du kannst {{SITENAME}} jetzt anonym weiternutzen, oder dich erneut unter demselben oder einem anderen Benutzernamen [[Special:UserLogin|anmelden]].
Beachte, dass einige Seiten noch anzeigen können, dass du angemeldet bist, solange du nicht deinen Browsercache geleert hast.",
'welcomecreation'            => '== Willkommen, $1! ==

Dein Benutzerkonto wurde eingerichtet.
Vergiss nicht, deine [[Special:Preferences|{{SITENAME}}-Einstellungen]] anzupassen.',
'yourname'                   => 'Benutzername:',
'yourpassword'               => 'Passwort:',
'yourpasswordagain'          => 'Passwort wiederholen:',
'remembermypassword'         => 'Mit diesem Browser dauerhaft angemeldet bleiben (maximal $1 {{PLURAL:$1|Tag|Tage}})',
'securelogin-stick-https'    => 'Nach dem Anmelden mit HTTPS verbunden bleiben',
'yourdomainname'             => 'Deine Domain:',
'externaldberror'            => 'Entweder es liegt ein Fehler bei der externen Authentifizierung vor oder du darfst dein externes Benutzerkonto nicht aktualisieren.',
'login'                      => 'Anmelden',
'nav-login-createaccount'    => 'Anmelden / Benutzerkonto erstellen',
'loginprompt'                => 'Zur Anmeldung müssen Cookies aktiviert sein.',
'userlogin'                  => 'Anmelden / Benutzerkonto erstellen',
'userloginnocreate'          => 'Anmelden',
'logout'                     => 'Abmelden',
'userlogout'                 => 'Abmelden',
'notloggedin'                => 'Nicht angemeldet',
'nologin'                    => "Du hast kein Benutzerkonto? '''$1'''.",
'nologinlink'                => 'Neues Benutzerkonto anlegen',
'createaccount'              => 'Benutzerkonto anlegen',
'gotaccount'                 => "Du hast bereits ein Benutzerkonto? '''$1'''.",
'gotaccountlink'             => 'Anmelden',
'createaccountmail'          => 'per E-Mail',
'createaccountreason'        => 'Grund:',
'badretype'                  => 'Die beiden Passwörter stimmen nicht überein.',
'userexists'                 => 'Dieser Benutzername ist schon vergeben. Bitte wähle einen anderen.',
'loginerror'                 => 'Fehler bei der Anmeldung',
'createaccounterror'         => 'Benutzerkonto konnte nicht erstellt werden: $1',
'nocookiesnew'               => 'Der Benutzerzugang wurde erstellt, aber du bist nicht angemeldet. {{SITENAME}} benötigt für diese Funktion Cookies, bitte aktiviere diese und melde dich dann mit deinem neuen Benutzernamen und dem zugehörigen Passwort an.',
'nocookieslogin'             => '{{SITENAME}} benutzt Cookies zur Anmeldung der Benutzer. Du hast Cookies deaktiviert, bitte aktiviere diese und versuche es erneut.',
'nocookiesfornew'            => 'Das Benutzerkonto wurde nicht erstellt, da die Datenherkunft nicht ermittelt werden konnte.
Es muss sichergestellt sein, dass Cookies aktiviert sind. Danach diese Seite erneut laden und es wieder versuchen.',
'noname'                     => 'Du musst einen gültigen Benutzernamen angeben.',
'loginsuccesstitle'          => 'Anmeldung erfolgreich',
'loginsuccess'               => 'Du bist jetzt als „$1“ bei {{SITENAME}} angemeldet.',
'nosuchuser'                 => 'Der Benutzername „$1“ existiert nicht.
Überprüfe die Schreibweise (Groß-/Kleinschreibung beachten) oder [[Special:UserLogin/signup|melde dich als neuer Benutzer an]].',
'nosuchusershort'            => 'Der Benutzername „<nowiki>$1</nowiki>“ existiert nicht. Bitte überprüfe die Schreibweise.',
'nouserspecified'            => 'Bitte gib einen Benutzernamen an.',
'login-userblocked'          => '{{GENDER:$1|Dieser Benutzer|Dieser Benutzerin|Dieser Benutzer}} ist gesperrt. Anmeldung nicht erlaubt.',
'wrongpassword'              => 'Das Passwort ist falsch. Bitte versuche es erneut.',
'wrongpasswordempty'         => 'Es wurde kein Passwort eingegeben. Bitte versuche es erneut.',
'passwordtooshort'           => 'Passwörter müssen mindestens {{PLURAL:$1|1 Zeichen|$1 Zeichen}} lang sein.',
'password-name-match'        => 'Dein Passwort muss sich von deinem Benutzernamen unterscheiden.',
'password-login-forbidden'   => 'Die Verwendung dieses Benutzernamens und Passwortes ist nicht erlaubt.',
'mailmypassword'             => 'Neues Passwort zusenden',
'passwordremindertitle'      => 'Neues Passwort für ein {{SITENAME}}-Benutzerkonto',
'passwordremindertext'       => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst, hat ein neues Passwort für die Anmeldung bei {{SITENAME}} ($4) angefordert.

Das automatisch generierte Passwort für Benutzer „$2“ lautet nun: $3

Falls du dies wirklich gewünscht hast, solltest du dich jetzt anmelden und das Passwort ändern.
Das neue Passwort ist {{PLURAL:$5|1 Tag|$5 Tage}} gültig.

Bitte ignoriere diese E-Mail, falls du sie nicht selbst angefordert hast. Das alte Passwort bleibt weiterhin gültig.',
'noemail'                    => '{{GENDER:$1|Benutzer|Benutzerin|Benutzer}} „$1“ hat keine E-Mail-Adresse angegeben.',
'noemailcreate'              => 'Du musst eine gültige E-Mail-Adresse angeben',
'passwordsent'               => 'Ein neues, temporäres Passwort wurde an die E-Mail-Adresse von Benutzer „$1“ gesandt.
Bitte melde dich damit an, sobald du es erhalten hast. Das alte Passwort bleibt weiterhin gültig.',
'blocked-mailpassword'       => 'Die von dir verwendete IP-Adresse ist für das Ändern von Seiten gesperrt. Um einen Missbrauch zu verhindern, wurde die Möglichkeit zur Anforderung eines neuen Passwortes ebenfalls gesperrt.',
'eauthentsent'               => 'Eine Bestätigungs-E-Mail wurde an die angegebene Adresse verschickt.

Bevor eine E-Mail von anderen Benutzern über die E-Mail-Funktion empfangen werden kann, muss die Adresse und ihre tatsächliche Zugehörigkeit zu diesem Benutzerkonto erst bestätigt werden. Bitte befolge die Hinweise in der Bestätigungs-E-Mail.',
'throttled-mailpassword'     => 'Es wurde innerhalb der letzten {{PLURAL:$1|Stunde|$1 Stunden}} bereits ein neues Passwort angefordert. Um einen Missbrauch der Funktion zu verhindern, kann nur {{PLURAL:$1|einmal pro Stunde|alle $1 Stunden}} ein neues Passwort angefordert werden.',
'mailerror'                  => 'Fehler beim Senden der E-Mail: $1',
'acct_creation_throttle_hit' => 'Besucher dieses Wikis, die deine IP-Adresse verwenden, haben innerhalb des letzten Tages {{PLURAL:$1|1 Benutzerkonto|$1 Benutzerkonten}} erstellt, was die maximal erlaubte Anzahl in dieser Zeitperiode ist.

Besucher, die diese IP-Adresse verwenden, können momentan keine Benutzerkonten mehr erstellen.',
'emailauthenticated'         => 'Deine E-Mail-Adresse wurde am $2 um $3 Uhr bestätigt.',
'emailnotauthenticated'      => 'Deine E-Mail-Adresse ist noch nicht bestätigt. Die folgenden E-Mail-Funktionen stehen erst nach erfolgreicher Bestätigung zur Verfügung.',
'noemailprefs'               => 'Gib eine E-Mail-Adresse in den Einstellungen an, damit die nachfolgenden Funktionen zur Verfügung stehen.',
'emailconfirmlink'           => 'E-Mail-Adresse bestätigen (authentifizieren).',
'invalidemailaddress'        => 'Die E-Mail-Adresse wird nicht akzeptiert, weil sie ein ungültiges Format (eventuell ungültige Zeichen) zu haben scheint. Bitte gib eine korrekte Adresse ein oder leere das Feld.',
'accountcreated'             => 'Benutzerkonto erstellt',
'accountcreatedtext'         => 'Das Benutzerkonto $1 wurde eingerichtet.',
'createaccount-title'        => 'Erstellung eines Benutzerkontos für {{SITENAME}}',
'createaccount-text'         => 'Es wurde für dich ein Benutzerkonto „$2“ auf {{SITENAME}} ($4) erstellt. Das automatisch generierte Passwort für „$2“ ist „$3“. Du solltest dich nun anmelden und das Passwort ändern.

Falls das Benutzerkonto irrtümlich angelegt wurde, kannst du diese Nachricht ignorieren.',
'usernamehasherror'          => 'Benutzernamen dürfen kein Rautenzeichen enthalten',
'login-throttled'            => 'Du hast zu oft versucht, dich anzumelden.
Bitte warte, bevor du es erneut probierst.',
'loginlanguagelabel'         => 'Sprache: $1',
'suspicious-userlogout'      => 'Deine Abmeldeanfrage wurde verweigert, da sie vermutlich von einem defekten Browser oder einem Cache-Proxy gesendet wurde.',

# E-mail sending
'php-mail-error-unknown' => 'Unbekannter Fehler mit der Funktion mail() von PHP',

# JavaScript password checks
'password-strength'            => 'Geschätzte Passwortstärke: $1',
'password-strength-bad'        => 'SCHLECHT',
'password-strength-mediocre'   => 'mittelmäßig',
'password-strength-acceptable' => 'akzeptabel',
'password-strength-good'       => 'gut',
'password-retype'              => 'Passwort wiederholen',
'password-retype-mismatch'     => 'Passwörter stimmen nicht überein',

# Password reset dialog
'resetpass'                 => 'Passwort ändern',
'resetpass_announce'        => 'Anmeldung mit dem per E-Mail zugesandten Code. Um die Anmeldung abzuschließen, musst du jetzt ein neues Passwort wählen.',
'resetpass_text'            => '<!-- Ergänze den Text hier -->',
'resetpass_header'          => 'Passwort ändern',
'oldpassword'               => 'Altes Passwort:',
'newpassword'               => 'Neues Passwort:',
'retypenew'                 => 'Neues Passwort (nochmal):',
'resetpass_submit'          => 'Passwort übermitteln und anmelden',
'resetpass_success'         => 'Dein Passwort wurde erfolgreich geändert. Es folgt die Anmeldung …',
'resetpass_forbidden'       => 'Das Passwort kann nicht geändert werden.',
'resetpass-no-info'         => 'Du musst dich anmelden, um auf diese Seite direkt zuzugreifen.',
'resetpass-submit-loggedin' => 'Passwort ändern',
'resetpass-submit-cancel'   => 'Abbrechen',
'resetpass-wrong-oldpass'   => 'Ungültiges temporäres oder aktuelles Passwort.
Möglicherweise hast du dein Passwort bereits erfolgreich geändert oder ein neues temporäres Passwort beantragt.',
'resetpass-temp-password'   => 'Temporäres Passwort:',

# Edit page toolbar
'bold_sample'     => 'Fetter Text',
'bold_tip'        => 'Fetter Text',
'italic_sample'   => 'Kursiver Text',
'italic_tip'      => 'Kursiver Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Interner Link',
'extlink_sample'  => 'http://www.example.com Link-Text',
'extlink_tip'     => 'Externer Link (http:// beachten)',
'headline_sample' => 'Ebene-2-Überschrift',
'headline_tip'    => 'Ebene-2-Überschrift',
'math_sample'     => 'Formel hier einfügen',
'math_tip'        => 'Mathematische Formel (LaTeX)',
'nowiki_sample'   => 'Unformatierten Text hier einfügen',
'nowiki_tip'      => 'Unformatierter Text',
'image_sample'    => 'Beispiel.jpg',
'image_tip'       => 'Dateilink',
'media_sample'    => 'Beispiel.ogg',
'media_tip'       => 'Mediendatei-Link',
'sig_tip'         => 'Deine Signatur mit Zeitstempel',
'hr_tip'          => 'Horizontale Linie (sparsam verwenden)',

# Edit pages
'summary'                          => 'Zusammenfassung:',
'subject'                          => 'Betreff:',
'minoredit'                        => 'Nur Kleinigkeiten wurden verändert',
'watchthis'                        => 'Diese Seite beobachten',
'savearticle'                      => 'Seite speichern',
'preview'                          => 'Vorschau',
'showpreview'                      => 'Vorschau zeigen',
'showlivepreview'                  => 'Live-Vorschau',
'showdiff'                         => 'Änderungen zeigen',
'anoneditwarning'                  => "Du bearbeitest diese Seite unangemeldet. Wenn du speicherst, wird deine aktuelle IP-Adresse in der Versionsgeschichte aufgezeichnet und ist damit unwiderruflich '''öffentlich''' einsehbar.",
'anonpreviewwarning'               => "''Du bist nicht angemeldet. Beim Speichern wird deine IP-Adresse in der Versionsgeschichte aufgezeichnet.''",
'missingsummary'                   => "'''Hinweis:''' Du hast keine Zusammenfassung angegeben. Wenn du erneut auf „{{int:savearticle}}“ klickst, wird deine Änderung ohne Zusammenfassung übernommen.",
'missingcommenttext'               => 'Dein Abschnitt enthält keinen Text.',
'missingcommentheader'             => "'''Achtung:''' Du hast kein Betreff/Überschrift eingegeben. Wenn du erneut auf „{{int:savearticle}}“ klickst, wird deine Bearbeitung ohne Überschrift gespeichert.",
'summary-preview'                  => 'Vorschau der Zusammenfassungszeile:',
'subject-preview'                  => 'Vorschau des Betreffs:',
'blockedtitle'                     => 'Benutzer ist gesperrt',
'blockedtext'                      => "'''Dein Benutzername oder deine IP-Adresse wurde gesperrt.'''

Die Sperrung wurde von $1 durchgeführt.
Als Grund wurde ''$2'' angegeben.

* Beginn der Sperre: $8
* Ende der Sperre: $6
* Sperre betrifft: $7

Du kannst $1 oder einen der anderen [[{{MediaWiki:Grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.
Du kannst die „E-Mail an diesen Benutzer“-Funktion nicht nutzen, solange keine gültige E-Mail-Adresse in deinen [[Special:Preferences|Benutzerkonto-Einstellungen]] eingetragen ist, oder diese Funktion für dich gesperrt wurde.
Deine aktuelle IP-Adresse ist $3, und die Sperr-ID ist $5.
Bitte füge alle Informationen jeder Anfrage hinzu, die du stellt.",
'autoblockedtext'                  => "Deine IP-Adresse wurde automatisch gesperrt, da sie von einem anderen Benutzer genutzt wurde, der von $1 gesperrt wurde.
Als Grund wurde angegeben:

:''$2''

* Beginn der Sperre: $8
* Ende der Sperre: $6
* Sperre betrifft: $7

Du kannst $1 oder einen der anderen [[{{MediaWiki:Grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.

Du kannst die „E-Mail an diesen Benutzer“-Funktion nicht nutzen, solange keine gültige E-Mail-Adresse in deinen [[Special:Preferences|Benutzerkonto-Einstellungen]] eingetragen ist, oder diese Funktion für dich gesperrt wurde.

Deine aktuelle IP-Adresse ist $3, und die Sperr-ID ist $5.
Bitte füge alle Informationen jeder Anfrage hinzu, die du stellt.",
'blockednoreason'                  => 'keine Begründung angegeben',
'blockedoriginalsource'            => "Der Quelltext von '''$1''' wird hier angezeigt:",
'blockededitsource'                => "Der Quelltext '''deiner Änderungen''' an '''$1''':",
'whitelistedittitle'               => 'Zum Bearbeiten ist es erforderlich, angemeldet zu sein',
'whitelistedittext'                => 'Du musst dich $1, um Seiten bearbeiten zu können.',
'confirmedittext'                  => 'Du musst deine E-Mail-Adresse erst bestätigen, bevor du Bearbeitungen durchführen kannst. Bitte ergänze und bestätige deine E-Mail in den [[Special:Preferences|Einstellungen]].',
'nosuchsectiontitle'               => 'Abschnitt nicht gefunden',
'nosuchsectiontext'                => 'Du hast versucht, einen Abschnitt zu bearbeiten, der nicht existiert.
Vermutlich wurde er verschoben oder gelöscht, nachdem du die Seite aufgerufen hast.',
'loginreqtitle'                    => 'Anmeldung erforderlich',
'loginreqlink'                     => 'anmelden',
'loginreqpagetext'                 => 'Du musst dich $1, um Seiten lesen zu können.',
'accmailtitle'                     => 'Passwort wurde verschickt',
'accmailtext'                      => 'Ein zufällig generiertes Passwort für [[User talk:$1|$1]] wurde an $2 versandt.

Das Passwort für dieses neue Benutzerkonto kann auf der Spezialseite „[[Special:ChangePassword|Passwort ändern]]“ geändert werden.',
'newarticle'                       => '(Neu)',
'newarticletext'                   => "Du bist einem Link zu einer Seite gefolgt, die nicht vorhanden ist.
Um die Seite anzulegen, trage deinen Text in die untenstehende Box ein (siehe die [[{{MediaWiki:Helppage}}|Hilfeseite]] für mehr Informationen).
Bist du fälschlicherweise hier, klicke die '''Zurück'''-Schaltfläche deines Browsers.",
'anontalkpagetext'                 => "----''Diese Seite dient dazu, einem nicht angemeldeten Benutzer Nachrichten zu hinterlassen. Es wird seine IP-Adresse zur Identifizierung verwendet. IP-Adressen können von mehreren Benutzern gemeinsam verwendet werden. Wenn du mit den Kommentaren auf dieser Seite nichts anfangen kannst, richten sie sich vermutlich an einen früheren Inhaber deiner IP-Adresse und du kannst sie ignorieren. Du kannst dir auch ein [[Special:UserLogin/signup|Benutzerkonto erstellen]] oder dich [[Special:UserLogin|anmelden]], um künftig Verwechslungen mit anderen anonymen Benutzern zu vermeiden.''",
'noarticletext'                    => 'Diese Seite enthält momentan noch keinen Text.
Du kannst diesen Titel auf den anderen Seiten [[Special:Search/{{PAGENAME}}|suchen]],
<span class="plainlinks">in den zugehörigen [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbüchern suchen] oder diese Seite [{{fullurl:{{FULLPAGENAME}}|action=edit}} bearbeiten]</span>.',
'noarticletext-nopermission'       => 'Diese Seite enthält momentan noch keinen Text.
Du kannst diesen Titel auf den anderen Seiten [[Special:Search/{{PAGENAME}}|suchen]]
oder in den zugehörigen <span class="plainlinks">[{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbüchern suchen].</span>',
'userpage-userdoesnotexist'        => 'Das Benutzerkonto „$1“ ist nicht vorhanden. Bitte prüfe, ob du diese Seite wirklich erstellen/bearbeiten willst.',
'userpage-userdoesnotexist-view'   => 'Das Benutzerkonto „$1“ existiert nicht.',
'blocked-notice-logextract'        => '{{GENDER:$1|Dieser Benutzer|Diese Benutzerin|Dieser Benutzer}} ist zurzeit gesperrt.
Zur Information folgt ein aktueller Auszug aus dem Benutzersperr-Logbuch:',
'clearyourcache'                   => "'''Hinweis - Leere nach dem Speichern den Browser-Cache, um die Änderungen sehen zu können:''' '''Mozilla/Firefox/Safari:''' ''Shift'' gedrückt halten und auf ''Aktualisieren'' klicken oder alternativ entweder ''Strg-F5'' oder ''Strg-R'' (''Befehlstaste-R'' auf dem Macintosh) drücken; '''Konqueror: '''Auf ''Aktualisieren'' klicken oder ''F5'' drücken; '''Opera:''' Cache unter ''Extras → Einstellungen'' leeren; '''Internet Explorer:''' ''Strg-F5'' drücken oder ''Strg'' gedrückt halten und dabei ''Aktualisieren'' anklicken.",
'usercssyoucanpreview'             => "'''Tipp:''' Benutze den „{{int:showpreview}}“-Button, um dein neues CSS vor dem Speichern zu testen.",
'userjsyoucanpreview'              => "'''Tipp:''' Benutze den „{{int:showpreview}}“-Button, um dein neues JavaScript vor dem Speichern zu testen.",
'usercsspreview'                   => "'''Beachte, dass du nur eine Vorschau deines Benutzer-CSS betrachtest.'''
'''Es wurde noch nicht gespeichert!'''",
'userjspreview'                    => "'''Beachte, dass du nur eine Vorschau deines Benutzer-JavaScript betrachtest.'''
'''Es wurde noch nicht gespeichert!'''",
'sitecsspreview'                   => "'''Beachte, dass du nur eine Vorschau dieses CSS betrachtest.'''
'''Es wurde noch nicht gespeichert!'''",
'sitejspreview'                    => "'''Beachte, dass du nur eine Vorschau dieses JavaScript betrachtest.'''
'''Es wurde noch nicht gespeichert!'''",
'userinvalidcssjstitle'            => "'''Achtung:''' Die Benutzeroberfläche „$1“ existiert nicht. Bedenke, dass benutzerspezifische .css- und .js-Seiten mit einem Kleinbuchstaben anfangen müssen, also beispielsweise ''{{ns:user}}:Mustermann/vector.css'' an Stelle von ''{{ns:user}}:Mustermann/Vector.css''.",
'updated'                          => '(Geändert)',
'note'                             => "'''Hinweis:'''",
'previewnote'                      => "'''Dies ist nur eine Vorschau, die Seite wurde noch nicht gespeichert!'''",
'previewconflict'                  => 'Diese Vorschau gibt den Inhalt des oberen Textfeldes wieder. So wird die Seite aussehen, wenn du jetzt speicherst.',
'session_fail_preview'             => "'''Deine Bearbeitung konnte nicht gespeichert werden, da Sitzungsdaten verloren gegangen sind.
Bitte versuche es erneut, indem du unter der folgenden Textvorschau nochmals auf „Seite speichern“ klickst.
Sollte das Problem bestehen bleiben, [[Special:UserLogout|melde dich ab]] und danach wieder an.'''",
'session_fail_preview_html'        => "'''Deine Bearbeitung konnte nicht gespeichert werden, da Sitzungsdaten verloren gegangen sind.'''

''Da in {{SITENAME}} das Speichern von reinem HTML aktiviert ist, wurde die Vorschau ausgeblendet, um JavaScript-Attacken vorzubeugen.''

'''Bitte versuche es erneut, indem du unter der folgenden Textvorschau nochmals auf „Seite speichern“ klickst.
Sollte das Problem bestehen bleiben, [[Special:UserLogout|melde dich ab]] und danach wieder an.'''",
'token_suffix_mismatch'            => "'''Deine Bearbeitung wurde zurückgewiesen, da dein Browser Zeichen im Bearbeiten-Token verstümmelt hat.
Eine Speicherung kann den Seiteninhalt zerstören. Dies geschieht bisweilen durch die Benutzung eines anonymen Proxy-Dienstes, der fehlerhaft arbeitet.'''",
'edit_form_incomplete'             => "'''Der Inhalt des Bearbeitungsformulars hat den Server nicht vollständig erreicht. Bitte prüfe deine Bearbeitungen auf Vollständigkeit und versuche es erneut.'''",
'editing'                          => 'Bearbeiten von „$1“',
'editingsection'                   => 'Bearbeiten von „$1“ (Abschnitt)',
'editingcomment'                   => 'Bearbeiten von „$1“ (Neuer Abschnitt)',
'editconflict'                     => 'Bearbeitungskonflikt: $1',
'explainconflict'                  => "Jemand anders hat diese Seite geändert, nachdem du angefangen hast diese zu bearbeiten.
Das obere Textfeld enthält den aktuellen Stand.
Das untere Textfeld enthält deine Änderungen.
Bitte füge deine Änderungen in das obere Textfeld ein.
'''Nur''' der Inhalt des oberen Textfeldes wird gespeichert, wenn du auf „{{int:savearticle}}“ klickst!",
'yourtext'                         => 'Dein Text',
'storedversion'                    => 'Gespeicherte Version',
'nonunicodebrowser'                => "'''Achtung:''' Dein Browser kann Unicode-Zeichen nicht richtig verarbeiten. Bitte verwende einen anderen Browser um Seiten zu bearbeiten.",
'editingold'                       => "'''Achtung: Du bearbeitest eine alte Version dieser Seite. Wenn du speicherst, werden alle neueren Versionen überschrieben.'''",
'yourdiff'                         => 'Unterschiede',
'copyrightwarning'                 => "'''Bitte kopiere keine Webseiten, die nicht deine eigenen sind, benutze keine urheberrechtlich geschützten Werke ohne Erlaubnis des Urhebers!'''<br />
Du gibst uns hiermit deine Zusage, dass du den Text '''selbst verfasst''' hast, dass der Text Allgemeingut '''(public domain)''' ist, oder dass der '''Urheber''' seine '''Zustimmung''' gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weise bitte auf der Diskussionsseite darauf hin.
<i>Bitte beachte, dass alle {{SITENAME}}-Beiträge automatisch unter der „$2“ stehen (siehe $1 für Details). Falls du nicht möchtest, dass deine Arbeit hier von anderen verändert und verbreitet wird, dann drücke nicht auf „Seite speichern“.</i>",
'copyrightwarning2'                => "Bitte beachte, dass alle Beiträge zu {{SITENAME}} von anderen Mitwirkenden bearbeitet, geändert oder gelöscht werden können.
Reiche hier keine Texte ein, falls du nicht willst, dass diese ohne Einschränkung geändert werden können.

Du bestätigst hiermit auch, dass du diese Texte selbst geschrieben hast oder diese von einer gemeinfreien Quelle kopiert hast
(siehe $1 für weitere Details). '''ÜBERTRAGE OHNE GENEHMIGUNG KEINE URHEBERRECHTLICH GESCHÜTZTEN INHALTE!'''",
'longpageerror'                    => "'''Fehler: Der Text, den du zu speichern versuchst, ist $1 KB groß. Dies ist größer als das erlaubte Maximum von $2 KB.'''
Er kann nicht gespeichert werden.",
'readonlywarning'                  => "'''Achtung: Die Datenbank wurde für Wartungsarbeiten gesperrt, so dass deine Änderungen derzeit nicht gespeichert werden können.
Sichere den Text bitte lokal auf deinem Computer und versuche zu einem späteren Zeitpunkt, die Änderungen zu übertragen.'''

Grund für die Sperre: $1",
'protectedpagewarning'             => "'''Achtung: Diese Seite wurde geschützt. Nur Benutzer mit Administratorrechten können die Seite bearbeiten.'''
Zur Information folgt der aktuelle Logbucheintrag:",
'semiprotectedpagewarning'         => "'''Halbsperrung:''' Die Seite wurde so geschützt, dass nur registrierte Benutzer diese ändern können.
Zur Information folgt der aktuelle Logbucheintrag:",
'cascadeprotectedwarning'          => "'''Achtung:''' Diese Seite wurde so geschützt, dass sie nur durch Benutzer mit Administratorrechten bearbeitet werden kann. Sie ist in die {{PLURAL:$1|folgende Seite|folgenden Seiten}} eingebunden, die mittels der Kaskadensperroption geschützt {{PLURAL:$1|ist|sind}}:",
'titleprotectedwarning'            => "'''Achtung: Die Seitenerstellung wurde so geschützt, dass nur Benutzer mit [[Special:ListGroupRights|speziellen Rechten]] diese Seite erstellen können.'''
Zur Information folgt der aktuelle Logbucheintrag:",
'templatesused'                    => '{{PLURAL:$1|Die folgende Vorlage wird|Folgende Vorlagen werden}} von dieser Seite verwendet:',
'templatesusedpreview'             => '{{PLURAL:$1|Die folgende Vorlage wird|Folgende Vorlagen werden}} von dieser Seitenvorschau verwendet:',
'templatesusedsection'             => '{{PLURAL:$1|Die folgende Vorlage wird|Folgende Vorlagen werden}} von diesem Abschnitt verwendet:',
'template-protected'               => '(schreibgeschützt)',
'template-semiprotected'           => '(schreibgeschützt für unangemeldete und neue Benutzer)',
'hiddencategories'                 => 'Diese Seite ist Mitglied von {{PLURAL:$1|1 versteckter Kategorie|$1 versteckten Kategorien}}:',
'edittools'                        => '<!-- Dieser Text wird unter dem „Bearbeiten“-Formular sowie dem „Hochladen“-Formular angezeigt. -->',
'nocreatetitle'                    => 'Die Erstellung neuer Seiten ist eingeschränkt.',
'nocreatetext'                     => 'Auf {{SITENAME}} wurde das Erstellen neuer Seiten eingeschränkt. Du kannst bestehende Seiten ändern oder dich [[Special:UserLogin|anmelden]].',
'nocreate-loggedin'                => 'Du hast keine Berechtigung, neue Seiten zu erstellen.',
'sectioneditnotsupported-title'    => 'Die Bearbeitung von Abschnitten wird nicht unterstützt',
'sectioneditnotsupported-text'     => 'Die Bearbeitung von Abschnitten wird auf dieser Bearbeitungsseite nicht unterstützt.',
'permissionserrors'                => 'Berechtigungsfehler',
'permissionserrorstext'            => 'Du bist nicht berechtigt, die Aktion auszuführen. {{PLURAL:$1|Grund|Gründe}}:',
'permissionserrorstext-withaction' => 'Du bist nicht berechtigt, $2.
{{PLURAL:$1|Grund|Gründe}}:',
'recreate-moveddeleted-warn'       => "'''Achtung: Du erstellst eine Seite, die bereits früher gelöscht wurde.'''

Bitte prüfe sorgfältig, ob die erneute Seitenerstellung den Richtlinien entspricht.
Zu deiner Information folgt das Lösch- und Verschiebungs-Logbuch mit der Begründung für die vorhergehende Löschung:",
'moveddeleted-notice'              => 'Diese Seite wurde gelöscht. Es folgt ein Auszug aus dem Lösch- und Verschiebungs-Logbuch dieser Seite.',
'log-fulllog'                      => 'Alle Logbucheinträge ansehen',
'edit-hook-aborted'                => 'Die Bearbeitung wurde ohne Erklärung durch eine Schnittstelle abgebrochen.',
'edit-gone-missing'                => 'Die Seite konnte nicht aktualisiert werden.
Sie wurde anscheinend gelöscht.',
'edit-conflict'                    => 'Bearbeitungskonflikt.',
'edit-no-change'                   => 'Deine Bearbeitung wurde ignoriert, da keine Änderung an dem Text vorgenommen wurde.',
'edit-already-exists'              => 'Die neue Seite konnte nicht erstellt werden, da sie bereits vorhanden ist.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Achtung: Diese Seite enthält zu viele Aufrufe aufwendiger Parserfunktionen.

Es {{PLURAL:$2|darf nicht mehr als 1 Aufruf|dürfen nicht mehr als $1 Aufrufe}} sein.',
'expensive-parserfunction-category'       => 'Seiten, die aufwändige Parserfunktionen zu oft aufrufen',
'post-expand-template-inclusion-warning'  => 'Warnung: Die Größe eingebundener Vorlagen ist zu groß, einige Vorlagen können nicht eingebunden werden.',
'post-expand-template-inclusion-category' => 'Seiten, in denen die maximale Größe eingebundener Vorlagen überschritten ist',
'post-expand-template-argument-warning'   => "'''Warnung:''' Diese Seite enthält mindestens ein Argument in einer Vorlage, das expandiert zu groß ist. Diese Argumente werden ignoriert.",
'post-expand-template-argument-category'  => 'Seiten, die ignorierte Vorlagenargumente enthalten',
'parser-template-loop-warning'            => 'Vorlagenschleife entdeckt: [[$1]]',
'parser-template-recursion-depth-warning' => 'Vorlagenrekursionstiefengrenze überschritten ($1)',
'language-converter-depth-warning'        => 'Sprachkonvertertiefenlimit überschritten ($1)',

# "Undo" feature
'undo-success' => 'Die Bearbeitung kann rückgängig gemacht werden.
Bitte prüfe den Vergleich unten um sicherzustellen, dass du dies tun möchtest, und speichere dann unten deine Änderungen, um die Bearbeitung rückgängig zu machen.',
'undo-failure' => 'Die Änderung konnte nicht rückgängig gemacht werden, da der betroffene Abschnitt zwischenzeitlich verändert wurde.',
'undo-norev'   => 'Die Bearbeitung konnte nicht rückgängig gemacht werden, da sie nicht vorhanden ist oder gelöscht wurde.',
'undo-summary' => 'Änderung $1 von [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussion]]) rückgängig gemacht.',

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerkonto kann nicht erstellt werden',
'cantcreateaccount-text' => "Die Erstellung eines Benutzerkontos von der IP-Adresse '''($1)''' aus wurde durch [[User:$3|$3]] gesperrt.

Grund der Sperre: ''$2''",

# History pages
'viewpagelogs'           => 'Logbücher dieser Seite anzeigen',
'nohistory'              => 'Es gibt keine Versionsgeschichte für diese Seite.',
'currentrev'             => 'Aktuelle Version',
'currentrev-asof'        => 'Aktuelle Version vom $2, $3 Uhr',
'revisionasof'           => 'Version vom $2, $3 Uhr',
'revision-info'          => 'Version vom $4, $5 Uhr von $2',
'previousrevision'       => '← Nächstältere Version',
'nextrevision'           => 'Nächstjüngere Version →',
'currentrevisionlink'    => 'Aktuelle Version',
'cur'                    => 'Aktuell',
'next'                   => 'Nächste',
'last'                   => 'Vorherige',
'page_first'             => 'Anfang',
'page_last'              => 'Ende',
'histlegend'             => 'Zur Anzeige der Änderungen einfach die zu vergleichenden Versionen auswählen und die Schaltfläche „{{int:compareselectedversions}}“ klicken.<br />
* ({{int:cur}}) = Unterschied zur aktuellen Version, ({{int:last}}) = Unterschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dieser Zeit, Benutzername/IP-Adresse des Bearbeiters, {{int:minoreditletter}} = Kleine Änderung',
'history-fieldset-title' => 'Suche in der Versionsgeschichte',
'history-show-deleted'   => 'nur gelöschte Versionen',
'histfirst'              => 'Älteste',
'histlast'               => 'Neueste',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'           => '(leer)',

# Revision feed
'history-feed-title'          => 'Versionsgeschichte',
'history-feed-description'    => 'Versionsgeschichte dieser Seite in {{SITENAME}}',
'history-feed-item-nocomment' => '$1 am $3 um $4 Uhr',
'history-feed-empty'          => 'Die angeforderte Seite existiert nicht. Vielleicht wurde sie gelöscht oder verschoben. [[Special:Search|Durchsuche]] {{SITENAME}} nach passenden neuen Seiten.',

# Revision deletion
'rev-deleted-comment'         => '(Bearbeitungskommentar entfernt)',
'rev-deleted-user'            => '(Benutzername entfernt)',
'rev-deleted-event'           => '(Logbuchaktion entfernt)',
'rev-deleted-user-contribs'   => '[Benutzername oder IP-Adresse entfernt – Bearbeitung aus Beiträgen versteckt]',
'rev-deleted-text-permission' => "Diese Version wurde '''gelöscht'''.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-deleted-text-unhide'     => "Diese Version wurde '''gelöscht'''.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].
Als Administrator kannst du [$1 diese Version einsehen].",
'rev-suppressed-text-unhide'  => "Diese Version wurde '''unterdrückt'''.
Details stehen im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unterdrückungs-Logbuch].
Als Administrator kannst du [$1 diese Version einsehen].",
'rev-deleted-text-view'       => "Diese Version wurde '''gelöscht'''.
Als Administrator kannst du sie weiterhin einsehen.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-suppressed-text-view'    => "Diese Version wurde '''unterdrückt'''.
Administratoren können sie einsehen; Details stehen im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unterdrückungs-Logbuch].",
'rev-deleted-no-diff'         => "Du kannst diesen Unterschied nicht betrachten, da eine der Versionen '''gelöscht''' wurde.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-suppressed-no-diff'      => "Du kannst diesen Versionsunterschied nicht betrachten, da eine der Versionen '''gelöscht''' wurde.",
'rev-deleted-unhide-diff'     => "Eine der Versionen dieses Unterschieds wurde '''gelöscht'''.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].
Als Administrator kannst du [$1 diese Version einsehen].",
'rev-suppressed-unhide-diff'  => "Eine der Versionen dieses Unterschieds wurde '''unterdrückt'''.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Unterdrückungs-Logbuch].
Als Administrator kannst du [$1 diese Version einsehen].",
'rev-deleted-diff-view'       => "Eine Version dieses Versionsunterschiedes wurde '''gelöscht'''.
Als Administrator kannst du diesen Versionsunterschied sehen. Details finden sich im [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-suppressed-diff-view'    => "Eine der Versionen dieses Versionsunterschiedes wurde '''unterdrückt'''.
Als Administrator kannst du diesen Versionsunterschied sehen. Details finden sich im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unterdrückungs-Logbuch].",
'rev-delundel'                => 'zeige/verstecke',
'rev-showdeleted'             => 'zeige',
'revisiondelete'              => 'Versionen löschen/wiederherstellen',
'revdelete-nooldid-title'     => 'Keine Version angegeben',
'revdelete-nooldid-text'      => 'Du hast entweder keine Version angegeben, auf die diese Aktion ausgeführt werden soll, die gewählte Version ist nicht vorhanden oder du versuchst, die aktuelle Version zu entfernen.',
'revdelete-nologtype-title'   => 'Kein Logtyp angegeben',
'revdelete-nologtype-text'    => 'Es wurde kein Logtyp für diese Aktion angegeben.',
'revdelete-nologid-title'     => 'Ungültiger Logeintrag',
'revdelete-nologid-text'      => 'Es wurde kein Logtyp ausgewählt oder der gewählte Logtyp existiert nicht.',
'revdelete-no-file'           => 'Die angegebene Datei existiert nicht.',
'revdelete-show-file-confirm' => 'Bist du sicher, dass du die gelöschte Version der Datei „<nowiki>$1</nowiki>“ vom $2 um $3 Uhr ansehen willst?',
'revdelete-show-file-submit'  => 'Ja',
'revdelete-selected'          => "'''{{PLURAL:$2|Gewählte Version|Gewählte Versionen}} von [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Gewählter Logbucheintrag|Gewählte Logbucheinträge}}:'''",
'revdelete-text'              => "'''Gelöschte Versionen und Aktionen verbleiben in der Versionsgeschichte und den Logbüchern, jedoch sind Teile davon für die Öffentlichkeit unzugänglich.'''
Andere Administratoren auf {{SITENAME}} haben Zugriff auf den versteckten Inhalt und können ihn mit der gleichen Seite wiederherstellen, sofern nicht zusätzliche Einschränkungen bestehen.",
'revdelete-confirm'           => 'Bitte bestätige, dass du beabsichtigst, dies zu tun, die Konsequenzen verstehst und es in Übereinstimmung mit den [[{{MediaWiki:Policy-url}}|Richtlinien]] tust.',
'revdelete-suppress-text'     => "Unterdrückungen sollten '''nur''' in den folgenden Fällen vorgenommen werden:
* Unangebrachte persönliche Informationen
*: ''Adressen, Telefonnummern, Sozialversicherungsnummern etc.''",
'revdelete-legend'            => 'Setzen der Sichtbarkeits-Einschränkungen',
'revdelete-hide-text'         => 'Text der Version verstecken',
'revdelete-hide-image'        => 'Dateiinhalt verstecken',
'revdelete-hide-name'         => 'Logbuch-Aktion verstecken',
'revdelete-hide-comment'      => 'Bearbeitungszusammenfassung verstecken',
'revdelete-hide-user'         => 'Benutzername/IP-Adresse des Bearbeiters verstecken',
'revdelete-hide-restricted'   => 'Daten auch vor Administratoren und anderen unterdrücken',
'revdelete-radio-same'        => '(nicht ändern)',
'revdelete-radio-set'         => 'Ja',
'revdelete-radio-unset'       => 'Nein',
'revdelete-suppress'          => 'Grund der Löschung auch vor Administratoren verstecken',
'revdelete-unsuppress'        => 'Einschränkungen für wiederhergestellte Versionen aufheben',
'revdelete-log'               => 'Grund:',
'revdelete-submit'            => 'Auf gewählte {{PLURAL:$1|Version|Versionen}} anwenden',
'revdelete-logentry'          => 'hat die Versionsansicht von „[[$1]]“ geändert',
'logdelete-logentry'          => 'hat die Sichtbarkeit von „[[$1]]“ geändert',
'revdelete-success'           => "'''Die Versionsansicht wurde aktualisiert.'''",
'revdelete-failure'           => "'''Die Versionssichtbarkeit konnte nicht aktualisiert werden:'''
$1",
'logdelete-success'           => "'''Logbuchansicht erfolgreich geändert.'''",
'logdelete-failure'           => "'''Logbuchsichtbarkeit kann nicht geändert werden:'''
$1",
'revdel-restore'              => 'Sichtbarkeit ändern',
'revdel-restore-deleted'      => 'gelöschte Versionen',
'revdel-restore-visible'      => 'sichtbare Versionen',
'pagehist'                    => 'Versionsgeschichte',
'deletedhist'                 => 'Gelöschte Versionen',
'revdelete-content'           => 'Seiteninhalt',
'revdelete-summary'           => 'Zusammenfassungskommentar',
'revdelete-uname'             => 'Benutzername',
'revdelete-restricted'        => 'Einschränkungen gelten auch für Administratoren',
'revdelete-unrestricted'      => 'Einschränkungen für Administratoren aufgehoben',
'revdelete-hid'               => 'versteckte $1',
'revdelete-unhid'             => 'machte $1 wieder öffentlich',
'revdelete-log-message'       => '$1 für $2 {{PLURAL:$2|Version|Versionen}}',
'logdelete-log-message'       => '$1 für $2 {{PLURAL:$2|Logbucheintrag|Logbucheinträge}}',
'revdelete-hide-current'      => 'Fehler beim Verstecken des Eintrags vom $1, $2 Uhr: Dies ist die aktuelle Version.
Sie kann nicht versteckt werden.',
'revdelete-show-no-access'    => 'Fehler beim Anzeigen des Eintrags vom $1, $2 Uhr: Dieser Eintrag wurde als „eingeschränkt“ markiert.
Du hast darauf keinen Zugriff.',
'revdelete-modify-no-access'  => 'Fehler beim Bearbeiten des Eintrags vom $1, $2 Uhr: Dieser Eintrag wurde als „eingeschränkt“ markiert.
Du hast darauf keinen Zugriff.',
'revdelete-modify-missing'    => 'Fehler beim Bearbeiten von ID $1: Es fehlt in der Datenbank!',
'revdelete-no-change'         => "'''Warnung:''' Der Eintrag vom $1, $2 Uhr besitzt bereits die gewünschten Sichtbarkeitseinstellungen.",
'revdelete-concurrent-change' => 'Fehler beim Bearbeiten des Eintrags vom $1, $2 Uhr: Es scheint, als ob der Status von jemandem geändert wurde, bevor du vorhattest, ihn zu bearbeiten.
Bitte prüfe die Logbücher.',
'revdelete-only-restricted'   => 'Fehler beim Verstecken des Eintrags vom $1, $2 Uhr: Du kannst keinen Eintrag vor Administratoren verstecken, ohne eine der anderen Ansichtsoptionen gewählt zu haben.',
'revdelete-reason-dropdown'   => '*Allgemeine Löschgründe
** Urheberrechtsverletzung
** Unangebrachte persönliche Informationen',
'revdelete-otherreason'       => 'Anderer/ergänzender Grund:',
'revdelete-reasonotherlist'   => 'Anderer Grund',
'revdelete-edit-reasonlist'   => 'Löschgründe bearbeiten',
'revdelete-offender'          => 'Autor der Version:',

# Suppression log
'suppressionlog'     => 'Oversight-Logbuch',
'suppressionlogtext' => 'Dies ist das Logbuch der Oversight-Aktionen (Änderungen der Sichtbarkeit von Versionen, Bearbeitungskommentaren, Benutzernamen und Benutzersperren).
Siehe die [[Special:IPBlockList|Liste der gesperrten IP-Adressen und Benutzernamen]] für aktuelle Sperren.',

# Revision move
'moverevlogentry'              => 'verschob {{PLURAL:$3|eine Version|$3 Versionen}} von $1 nach $2',
'revisionmove'                 => 'Versionen verschieben von „$1“',
'revmove-explain'              => 'Die folgenden Versionen werden von $1 zur angegebenen Zielseite verschoben. Falls die Zielseite nicht existiert, wird sie erstellt. Anderenfalls werden diese Versionen in der Versionsgeschichte zusammengeführt.',
'revmove-legend'               => 'Zielseite und Zusammenfassung festlegen',
'revmove-submit'               => 'Versionen zur ausgewählten Seite verschieben',
'revisionmoveselectedversions' => 'Ausgewählte Versionen verschieben',
'revmove-reasonfield'          => 'Grund:',
'revmove-titlefield'           => 'Zielseite:',
'revmove-badparam-title'       => 'Falsche Parameter',
'revmove-badparam'             => 'Deine Anfrage enthält unerlaubte oder mangelhafte Parameter. Bitte klicke auf „zurück“ und versuche es erneut.',
'revmove-norevisions-title'    => 'Ungültige Zielversion',
'revmove-norevisions'          => 'Du hast keine Zielversion angegeben, um diese Aktion durchzuführen oder die angegebene Version existiert nicht.',
'revmove-nullmove-title'       => 'Ungültiger Titel',
'revmove-nullmove'             => 'Quell- und Zielseite sind identisch. Bitte klicke auf „zurück“ und gib einen anderen Seitennamen als „$1“ ein.',
'revmove-success-existing'     => '{{PLURAL:$1|Eine Version von [[$2]] wurde|$1 Versionen von [[$2]] wurden}} zur existierenden Seite [[$3]] verschoben.',
'revmove-success-created'      => '{{PLURAL:$1|Eine Version von [[$2]] wurde|$1 Versionen von [[$2]] wurden}} zur neu angelegten Seite [[$3]] verschoben.',

# History merging
'mergehistory'                     => 'Versionsgeschichten vereinen',
'mergehistory-header'              => 'Mit dieser Spezialseite kannst du die Versionsgeschichte einer Ursprungsseite mit der Versionsgeschichte einer Zielseite vereinen.
Stelle sicher, dass die Versionsgeschichte einer Seite historisch korrekt ist.',
'mergehistory-box'                 => 'Versionsgeschichten zweier Seiten vereinen',
'mergehistory-from'                => 'Ursprungsseite:',
'mergehistory-into'                => 'Zielseite:',
'mergehistory-list'                => 'Versionen, die vereinigt werden können',
'mergehistory-merge'               => 'Die folgenden Versionen von „[[:$1]]“ können nach „[[:$2]]“ übertragen werden. Markiere die Version, bis zu der (einschließlich) die Versionen übertragen werden sollen. Bitte beachte, dass die Nutzung der Navigationslinks die Auswahl zurücksetzt.',
'mergehistory-go'                  => 'Zeige Versionen, die vereinigt werden können',
'mergehistory-submit'              => 'Vereinige Versionen',
'mergehistory-empty'               => 'Es können keine Versionen vereinigt werden.',
'mergehistory-success'             => '{{PLURAL:$3|1 Version|$3 Versionen}} von „[[:$1]]“ erfolgreich nach „[[:$2]]“ vereinigt.',
'mergehistory-fail'                => 'Versionsvereinigung nicht möglich, bitte prüfe die Seite und die Zeitangaben.',
'mergehistory-no-source'           => 'Ursprungsseite „$1“ ist nicht vorhanden.',
'mergehistory-no-destination'      => 'Zielseite „$1“ ist nicht vorhanden.',
'mergehistory-invalid-source'      => 'Ursprungsseite muss ein gültiger Seitenname sein.',
'mergehistory-invalid-destination' => 'Zielseite muss ein gültiger Seitenname sein.',
'mergehistory-autocomment'         => '„[[:$1]]“ vereinigt nach „[[:$2]]“',
'mergehistory-comment'             => '„[[:$1]]“ vereinigt nach „[[:$2]]“: $3',
'mergehistory-same-destination'    => 'Ausgangs- und Zielseite dürfen nicht identisch sein',
'mergehistory-reason'              => 'Grund:',

# Merge log
'mergelog'           => 'Vereinigungs-Logbuch',
'pagemerge-logentry' => 'vereinigte [[$1]] in [[$2]] (Versionen bis $3)',
'revertmerge'        => 'Vereinigung rückgängig machen',
'mergelogpagetext'   => 'Dies ist das Logbuch der vereinigten Versionsgeschichten.',

# Diffs
'history-title'            => 'Versionsgeschichte von „$1“',
'difference'               => '(Unterschied zwischen Versionen)',
'difference-multipage'     => '(Unterschied zwischen Seiten)',
'lineno'                   => 'Zeile $1:',
'compareselectedversions'  => 'Gewählte Versionen vergleichen',
'showhideselectedversions' => 'Gewählte Versionen zeigen/verstecken',
'editundo'                 => 'rückgängig',
'diff-multi'               => '({{PLURAL:$1|Eine dazwischenliegende Version|$1 dazwischenliegende Versionen}} von {{PLURAL:$2|einem Benutzer|$2 Benutzern}} {{PLURAL:$1|wird|werden}} nicht angezeigt)',
'diff-multi-manyusers'     => '({{PLURAL:$1|$1 dazwischenliegende Versionen}} von mehr als {{PLURAL:$2|$2 Benutzern}}, die nicht angezeigt werden)',

# Search results
'searchresults'                    => 'Suchergebnisse',
'searchresults-title'              => 'Suchergebnisse für „$1“',
'searchresulttext'                 => 'Für mehr Informationen zur Suche siehe die [[{{MediaWiki:Helppage}}|Hilfeseite]].',
'searchsubtitle'                   => 'Deine Suchanfrage: „[[:$1]]“ ([[Special:Prefixindex/$1|alle mit „$1“ beginnenden Seiten]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Seiten, die nach „$1“ verlinken]])',
'searchsubtitleinvalid'            => 'Deine Suchanfrage: „$1“.',
'toomanymatches'                   => 'Die Anzahl der Suchergebnisse ist zu groß, bitte versuche eine andere Abfrage.',
'titlematches'                     => 'Übereinstimmungen mit Seitentiteln',
'notitlematches'                   => 'Keine Übereinstimmungen mit Seitentiteln',
'textmatches'                      => 'Übereinstimmungen mit Inhalten',
'notextmatches'                    => 'Keine Übereinstimmungen mit Inhalten',
'prevn'                            => '{{PLURAL:$1|vorheriger|vorherige $1}}',
'nextn'                            => '{{PLURAL:$1|nächster|nächste $1}}',
'prevn-title'                      => '{{PLURAL:$1|Vorheriges Ergebnis|Vorherige $1 Ergebnisse}}',
'nextn-title'                      => '{{PLURAL:$1|Folgendes Ergebnis|Folgende $1 Ergebnisse}}',
'shown-title'                      => 'Zeige $1 {{PLURAL:$1|Ergebnis|Ergebnisse}} pro Seite',
'viewprevnext'                     => 'Zeige ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Suchoptionen',
'searchmenu-exists'                => "'''Es gibt eine Seite, die den Namen „[[:$1]]“ hat.'''",
'searchmenu-new'                   => "'''Erstelle die Seite „[[:$1|$1]]“ in diesem Wiki.'''",
'searchmenu-new-nocreate'          => '„$1“ ist ein ungültiger Seitenname oder kann von dir nicht erstellt werden.',
'searchhelp-url'                   => 'Help:Hilfe',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Zeige alle Seiten, die mit dem Suchbegriff anfangen]]',
'searchprofile-articles'           => 'Inhaltsseiten',
'searchprofile-project'            => 'Hilfe und Projektseiten',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Alles',
'searchprofile-advanced'           => 'Erweitert',
'searchprofile-articles-tooltip'   => 'Suchen in $1',
'searchprofile-project-tooltip'    => 'Suchen in $1',
'searchprofile-images-tooltip'     => 'Nach Bildern suchen',
'searchprofile-everything-tooltip' => 'Gesamten Inhalt durchsuchen (inklusive Diskussionsseiten)',
'searchprofile-advanced-tooltip'   => 'Suche in weiteren Namensräumen',
'search-result-size'               => '$1 ({{PLURAL:$2|1 Wort|$2 Wörter}})',
'search-result-category-size'      => '{{PLURAL:$1|1 Seite|$1 Seiten}} ({{PLURAL:$2|1 Unterkategorie|$2 Unterkategorien}}, {{PLURAL:$3|1 Datei|$3 Dateien}})',
'search-result-score'              => 'Relevanz: $1 %',
'search-redirect'                  => '(Weiterleitung von „$1“)',
'search-section'                   => '(Abschnitt $1)',
'search-suggest'                   => 'Meintest du „$1“?',
'search-interwiki-caption'         => 'Schwesterprojekte',
'search-interwiki-default'         => '$1 Ergebnisse:',
'search-interwiki-more'            => '(weitere)',
'search-mwsuggest-enabled'         => 'mit Vorschlägen',
'search-mwsuggest-disabled'        => 'keine Vorschläge',
'search-relatedarticle'            => 'Verwandte',
'mwsuggest-disable'                => 'Vorschläge per Ajax deaktivieren',
'searcheverything-enable'          => 'In allen Namensräumen suchen',
'searchrelated'                    => 'verwandt',
'searchall'                        => 'alle',
'showingresults'                   => "Hier {{PLURAL:$1|ist '''1''' Ergebnis|sind '''$1''' Ergebnisse}}, beginnend mit Nummer '''$2.'''",
'showingresultsnum'                => "Hier {{PLURAL:$3|ist '''1''' Ergebnis|sind '''$3''' Ergebnisse}}, beginnend mit Nummer '''$2.'''",
'showingresultsheader'             => "{{PLURAL:$5|Ergebnis '''$1''' von '''$3'''|Ergebnisse '''$1–$2''' von '''$3'''}} für '''$4'''",
'nonefound'                        => "'''Hinweis:''' Es werden standardmäßig nur einige Namensräume durchsucht. Setze ''all:'' vor deinen Suchbegriff, um alle Seiten (inkl. Diskussionsseiten, Vorlagen usw.) zu durchsuchen oder gezielt den Namen des zu durchsuchenden Namensraumes.",
'search-nonefound'                 => 'Für deine Suchanfrage wurden keine Ergebnisse gefunden.',
'powersearch'                      => 'Erweiterte Suche',
'powersearch-legend'               => 'Erweiterte Suche',
'powersearch-ns'                   => 'Suche in Namensräumen:',
'powersearch-redir'                => 'Weiterleitungen anzeigen',
'powersearch-field'                => 'Suche nach:',
'powersearch-togglelabel'          => 'Wähle aus:',
'powersearch-toggleall'            => 'Alle',
'powersearch-togglenone'           => 'Keine',
'search-external'                  => 'Externe Suche',
'searchdisabled'                   => 'Die {{SITENAME}}-Suche ist deaktiviert. Du kannst unterdessen mit Google suchen. Bitte bedenke, dass der Suchindex von {{SITENAME}} veraltet sein kann.',

# Quickbar
'qbsettings'               => 'Seitenleiste',
'qbsettings-none'          => 'Keine',
'qbsettings-fixedleft'     => 'Links, fest',
'qbsettings-fixedright'    => 'Rechts, fest',
'qbsettings-floatingleft'  => 'Links, schwebend',
'qbsettings-floatingright' => 'Rechts, schwebend',

# Preferences page
'preferences'                   => 'Einstellungen',
'mypreferences'                 => 'Einstellungen',
'prefs-edits'                   => 'Anzahl der Bearbeitungen:',
'prefsnologin'                  => 'Nicht angemeldet',
'prefsnologintext'              => 'Du musst <span class="plainlinks">[{{fullurl:{{#special:UserLogin}}|returnto=$1}} angemeldet]</span> sein, um deine Einstellungen ändern zu können.',
'changepassword'                => 'Passwort ändern',
'prefs-skin'                    => 'Benutzeroberfläche',
'skin-preview'                  => 'Vorschau',
'prefs-math'                    => 'TeX',
'datedefault'                   => 'Standard',
'prefs-datetime'                => 'Datum und Zeit',
'prefs-personal'                => 'Benutzerdaten',
'prefs-rc'                      => 'Letzte Änderungen',
'prefs-watchlist'               => 'Beobachtungsliste',
'prefs-watchlist-days'          => 'Anzahl der Tage, die die Beobachtungsliste standardmäßig umfassen soll:',
'prefs-watchlist-days-max'      => 'Maximal 7 Tage',
'prefs-watchlist-edits'         => 'Maximale Zahl der Einträge:',
'prefs-watchlist-edits-max'     => 'Maximale Anzahl: 1000',
'prefs-watchlist-token'         => 'Beobachtungslisten-Token:',
'prefs-misc'                    => 'Verschiedenes',
'prefs-resetpass'               => 'Passwort ändern',
'prefs-email'                   => 'E-Mail-Optionen',
'prefs-rendering'               => 'Aussehen',
'saveprefs'                     => 'Einstellungen speichern',
'resetprefs'                    => 'Eingaben verwerfen',
'restoreprefs'                  => 'Alle Standardeinstellungen wiederherstellen',
'prefs-editing'                 => 'Bearbeiten',
'prefs-edit-boxsize'            => 'Größe des Bearbeitungsfensters:',
'rows'                          => 'Zeilen:',
'columns'                       => 'Spalten:',
'searchresultshead'             => 'Suche',
'resultsperpage'                => 'Treffer pro Seite:',
'contextlines'                  => 'Zeilen pro Treffer:',
'contextchars'                  => 'Zeichen pro Zeile:',
'stub-threshold'                => 'Linkformatierung <a href="#" class="stub">kleiner Seiten</a> (in Byte):',
'stub-threshold-disabled'       => 'Deaktiviert',
'recentchangesdays'             => 'Anzahl der Tage, die die Liste der „Letzten Änderungen“ standardmäßig umfassen soll:',
'recentchangesdays-max'         => 'Maximal $1 {{PLURAL:$1|Tag|Tage}}',
'recentchangescount'            => 'Anzahl der standardmäßig angezeigten Bearbeitungen:',
'prefs-help-recentchangescount' => 'Dies umfasst die Liste der letzten Änderungen, die Versionsgeschichte und die Logbücher.',
'prefs-help-watchlist-token'    => 'Das Ausfüllen dieses Feldes mit einem geheimen Schlüssel generiert einen RSS-Feed für deine Beobachtungsliste.
Jeder, der diesen Schlüssel kennt, kann deine Beobachtungsliste einsehen. Wähle also einen sicheren Wert.
Hier ein zufällig generierter Wert, den du verwenden kannst: $1',
'savedprefs'                    => 'Deine Einstellungen wurden gespeichert.',
'timezonelegend'                => 'Zeitzone:',
'localtime'                     => 'Ortszeit:',
'timezoneuseserverdefault'      => 'Standardzeit des Servers',
'timezoneuseoffset'             => 'Andere (Unterschied angeben)',
'timezoneoffset'                => 'Unterschied¹:',
'servertime'                    => 'Aktuelle Zeit auf dem Server:',
'guesstimezone'                 => 'Vom Browser übernehmen',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktis',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Asien',
'timezoneregion-atlantic'       => 'Atlantischer Ozean',
'timezoneregion-australia'      => 'Australien',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indischer Ozean',
'timezoneregion-pacific'        => 'Pazifischer Ozean',
'allowemail'                    => 'E-Mail-Empfang von anderen Benutzern ermöglichen',
'prefs-searchoptions'           => 'Suchoptionen',
'prefs-namespaces'              => 'Namensräume',
'defaultns'                     => 'Anderenfalls in diesen Namensräumen suchen:',
'default'                       => 'Voreinstellung',
'prefs-files'                   => 'Dateien',
'prefs-custom-css'              => 'Benutzerdefinierte CSS',
'prefs-custom-js'               => 'Benutzerdefiniertes JavaScript',
'prefs-common-css-js'           => 'Gemeinsames CSS/JavaScript aller Benutzeroberflächen:',
'prefs-reset-intro'             => 'Du kannst diese Seite verwenden, um die Einstellungen auf die Standards zurückzusetzen.
Dies kann nicht mehr rückgängig gemacht werden.',
'prefs-emailconfirm-label'      => 'E-Mail-Bestätigung:',
'prefs-textboxsize'             => 'Größe des Bearbeitungsfensters',
'youremail'                     => 'E-Mail-Adresse:',
'username'                      => 'Benutzername:',
'uid'                           => 'Benutzer-ID:',
'prefs-memberingroups'          => 'Mitglied der {{PLURAL:$1|Benutzergruppe|Benutzergruppen}}:',
'prefs-memberingroups-type'     => '$2',
'prefs-registration'            => 'Anmeldezeitpunkt:',
'prefs-registration-date-time'  => '$2, $3 Uhr',
'yourrealname'                  => 'Bürgerlicher Name:',
'yourlanguage'                  => 'Sprache der Benutzeroberfläche:',
'yourvariant'                   => 'Variante:',
'yournick'                      => 'Signatur:',
'prefs-help-signature'          => 'Beiträge auf Diskussionsseiten sollten mit „<nowiki>~~~~</nowiki>“ signiert werden, was dann in die Signatur mit Zeitstempel umgewandelt wird.',
'badsig'                        => 'Die Syntax der Signatur ist ungültig; bitte HTML überprüfen.',
'badsiglength'                  => 'Die Signatur darf maximal $1 {{PLURAL:$1|Zeichen|Zeichen}} lang sein.',
'yourgender'                    => 'Geschlecht:',
'gender-unknown'                => 'Nicht angegeben',
'gender-male'                   => 'Männlich',
'gender-female'                 => 'Weiblich',
'prefs-help-gender'             => 'Optional: Wird von der Software für die geschlechtsspezifische Anrede genutzt. Diese Information ist öffentlich.',
'email'                         => 'E-Mail',
'prefs-help-realname'           => 'Optional. Damit kann dein bürgerlicher Name deinen Beiträgen zugeordnet werden.',
'prefs-help-email'              => 'Die Angabe einer E-Mail ist optional, ermöglicht aber die Zusendung eines Ersatzpasswortes, wenn du dein Passwort vergessen hast.',
'prefs-help-email-others'       => 'Mit anderen Benutzern kannst du auch über die Benutzerdiskussionsseiten Kontakt aufnehmen, ohne dass du deine Identität offenlegen musst.',
'prefs-help-email-required'     => 'Es wird eine gültige E-Mail-Adresse benötigt.',
'prefs-info'                    => 'Basisinformationen',
'prefs-i18n'                    => 'Internationalisierung',
'prefs-signature'               => 'Signatur',
'prefs-dateformat'              => 'Datumsformat',
'prefs-timeoffset'              => 'Zeitunterschied',
'prefs-advancedediting'         => 'Erweiterte Optionen',
'prefs-advancedrc'              => 'Erweiterte Optionen',
'prefs-advancedrendering'       => 'Erweiterte Optionen',
'prefs-advancedsearchoptions'   => 'Erweiterte Optionen',
'prefs-advancedwatchlist'       => 'Erweiterte Optionen',
'prefs-displayrc'               => 'Anzeigeoptionen',
'prefs-displaysearchoptions'    => 'Anzeigeoptionen',
'prefs-displaywatchlist'        => 'Anzeigeoptionen',
'prefs-diffs'                   => 'Versionsvergleich',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Gültige E-Mail-Adresse',
'email-address-validity-invalid' => 'Eine gültige E-Mail-Adresse ist erforderlich.',

# User rights
'userrights'                   => 'Benutzerrechteverwaltung',
'userrights-lookup-user'       => 'Gruppenzugehörigkeit verwalten',
'userrights-user-editname'     => 'Benutzername:',
'editusergroup'                => 'Benutzerrechte bearbeiten',
'editinguser'                  => "Ändere Benutzerrechte von '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Benutzer-Gruppenzugehörigkeit bearbeiten',
'saveusergroups'               => 'Gruppenzugehörigkeit ändern',
'userrights-groupsmember'      => 'Mitglied von:',
'userrights-groupsmember-auto' => 'Automatisch Mitglied von:',
'userrights-groups-help'       => 'Du kannst die Gruppenzugehörigkeit dieses Benutzer ändern:
* Ein markiertes Kästchen bedeutet, dass der Benutzer Mitglied dieser Gruppe ist
* Ein * bedeutet, dass du das Benutzerrecht nach Erteilung nicht wieder zurücknehmen kannst (oder umgekehrt).',
'userrights-reason'            => 'Grund:',
'userrights-no-interwiki'      => 'Du hast keine Berechtigung, Benutzerrechte in anderen Wikis zu ändern.',
'userrights-nodatabase'        => 'Die Datenbank $1 ist nicht vorhanden oder nicht lokal.',
'userrights-nologin'           => 'Du musst dich mit einem Administrator-Benutzerkonto [[Special:UserLogin|anmelden]], um Benutzerrechte zu ändern.',
'userrights-notallowed'        => 'Du besitzt nicht die erforderlichen Berechtigungen, um Benutzerrechte zu vergeben.',
'userrights-changeable-col'    => 'Gruppenzugehörigkeit, die du ändern kannst',
'userrights-unchangeable-col'  => 'Gruppenzugehörigkeit, die du nicht ändern kannst',

# Groups
'group'               => 'Gruppe:',
'group-user'          => 'Benutzer',
'group-autoconfirmed' => 'Automatisch bestätigte Benutzer',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratoren',
'group-bureaucrat'    => 'Bürokraten',
'group-suppress'      => 'Oversighter',
'group-all'           => '(alle)',

'group-user-member'          => 'Benutzer',
'group-autoconfirmed-member' => 'Automatisch bestätigter Benutzer',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bürokrat',
'group-suppress-member'      => 'Oversighter',

'grouppage-user'          => '{{ns:project}}:Benutzer',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatisch bestätigte Benutzer',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratoren',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',
'grouppage-suppress'      => '{{ns:project}}:Oversighter',

# Rights
'right-read'                  => 'Seiten lesen',
'right-edit'                  => 'Seiten bearbeiten',
'right-createpage'            => 'Seiten erstellen (außer Diskussionsseiten)',
'right-createtalk'            => 'Diskussionsseiten erstellen',
'right-createaccount'         => 'Benutzerkonto erstellen',
'right-minoredit'             => 'Bearbeitungen als klein markieren',
'right-move'                  => 'Seiten verschieben',
'right-move-subpages'         => 'Seiten inklusive Unterseiten verschieben',
'right-move-rootuserpages'    => 'Haupt-Benutzerseiten verschieben',
'right-movefile'              => 'Dateien verschieben',
'right-suppressredirect'      => 'Beim Verschieben die Erstellung einer Weiterleitung unterdrücken',
'right-upload'                => 'Dateien hochladen',
'right-reupload'              => 'Überschreiben einer vorhandenen Datei',
'right-reupload-own'          => 'Überschreiben einer zuvor selbst hochgeladenen Datei',
'right-reupload-shared'       => 'Lokales Überschreiben einer in einem gemeinsam genutzten Repositorium vorhandenen Datei',
'right-upload_by_url'         => 'Dateien von einer URL-Adresse hochladen',
'right-purge'                 => 'Seitencache leeren ohne Rückfrage',
'right-autoconfirmed'         => 'Halbgeschützte Seiten bearbeiten',
'right-bot'                   => 'Behandlung als automatischer Prozess',
'right-nominornewtalk'        => 'Kleine Bearbeitungen an Diskussionsseiten führen zu keiner „Neue Nachrichten“-Anzeige',
'right-apihighlimits'         => 'Höhere Limits in API-Abfragen',
'right-writeapi'              => 'Benutzung der writeAPI',
'right-delete'                => 'Seiten löschen',
'right-bigdelete'             => 'Seiten mit großer Versionsgeschichte löschen',
'right-deleterevision'        => 'Einzelne Versionen einer Seite löschen und wiederherstellen',
'right-deletedhistory'        => 'Gelöschte Versionen in der Versionsgeschichte ansehen, ohne zugehörigen Text',
'right-deletedtext'           => 'Gelöschte Texte und Versionsunterschiede zwischen gelöschten Versionen ansehen',
'right-browsearchive'         => 'Nach gelöschten Seiten suchen',
'right-undelete'              => 'Seiten wiederherstellen',
'right-suppressrevision'      => 'Versionen ansehen und wiederherstellen, die auch vor Administratoren verborgen sind',
'right-suppressionlog'        => 'Private Logbücher ansehen',
'right-block'                 => 'Benutzer sperren (Schreibrecht)',
'right-blockemail'            => 'Benutzer am Versenden von E-Mails hindern',
'right-hideuser'              => 'Benutzernamen sperren und verbergen',
'right-ipblock-exempt'        => 'Ausnahme von IP-Sperren, Autoblocks und Rangesperren',
'right-proxyunbannable'       => 'Ausnahme von automatischen Proxysperren',
'right-unblockself'           => 'Sich selbst entsperren',
'right-protect'               => 'Seitenschutzstatus ändern',
'right-editprotected'         => 'Geschützte Seiten bearbeiten (ohne Kaskadenschutz)',
'right-editinterface'         => 'Benutzeroberfläche bearbeiten',
'right-editusercssjs'         => 'Fremde CSS- und JavaScript-Dateien bearbeiten',
'right-editusercss'           => 'Fremde CSS-Dateien bearbeiten',
'right-edituserjs'            => 'Fremde JavaScript-Dateien bearbeiten',
'right-rollback'              => 'Schnelles Zurücksetzen',
'right-markbotedits'          => 'Schnell zurückgesetzte Bearbeitungen als Bot-Bearbeitung markieren',
'right-noratelimit'           => 'Keine Beschränkung durch Limits',
'right-import'                => 'Seiten aus anderen Wikis importieren',
'right-importupload'          => 'Seiten über Hochladen von Dateien importieren',
'right-patrol'                => 'Fremde Bearbeitungen als kontrolliert markieren',
'right-autopatrol'            => 'Eigene Bearbeitungen automatisch als kontrolliert markieren',
'right-patrolmarks'           => 'Kontrollmarkierungen in den letzten Änderungen sehen',
'right-unwatchedpages'        => 'Liste der unbeobachteten Seiten ansehen',
'right-trackback'             => 'Trackback übermitteln',
'right-mergehistory'          => 'Versionsgeschichten von Seiten vereinen',
'right-userrights'            => 'Benutzerrechte bearbeiten',
'right-userrights-interwiki'  => 'Benutzerrechte in anderen Wikis bearbeiten',
'right-siteadmin'             => 'Datenbank sperren und entsperren',
'right-reset-passwords'       => 'Passwort eines anderen Benutzers zurücksetzen',
'right-override-export-depth' => 'Exportiere Seiten einschließlich verlinkter Seiten bis zu einer Tiefe von 5',
'right-sendemail'             => 'E-Mails an andere Benutzer senden',
'right-revisionmove'          => 'Versionen verschieben',
'right-disableaccount'        => 'Benutzerkonto deaktivieren',

# User rights log
'rightslog'      => 'Rechte-Logbuch',
'rightslogtext'  => 'Dies ist das Logbuch der Änderungen der Benutzerrechte.',
'rightslogentry' => 'änderte die Benutzerrechte für „$1“ von „$2“ auf „$3“',
'rightsnone'     => '(–)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'die Seite zu lesen',
'action-edit'                 => 'die Seite zu bearbeiten',
'action-createpage'           => 'Seiten zu erstellen',
'action-createtalk'           => 'Diskussionsseiten zu erstellen',
'action-createaccount'        => 'ein Benutzerkonto zu erstellen',
'action-minoredit'            => 'diese Bearbeitung als klein zu markieren',
'action-move'                 => 'die Seite zu verschieben',
'action-move-subpages'        => 'diese Seite und zugehörige Unterseiten zu verschieben',
'action-move-rootuserpages'   => 'Haupt-Benutzerseiten zu verschieben',
'action-movefile'             => 'Diese Datei verschieben',
'action-upload'               => 'Dateien hochzuladen',
'action-reupload'             => 'die vorhandene Datei zu überschreiben',
'action-reupload-shared'      => 'diese Datei aus dem gemeinsam genutzten Repositorium zu überschreiben',
'action-upload_by_url'        => 'Dateien von einer Webadresse (URL) hochzuladen',
'action-writeapi'             => 'die API mit Schreibzugriffen zu verwenden',
'action-delete'               => 'Seiten zu löschen',
'action-deleterevision'       => 'Versionen zu löschen',
'action-deletedhistory'       => 'Liste der gelöschten Versionen zu sehen',
'action-browsearchive'        => 'nach gelöschten Seiten zu suchen',
'action-undelete'             => 'die Seite wiederherzustellen',
'action-suppressrevision'     => 'die versteckte Version einzusehen und wiederherzustellen',
'action-suppressionlog'       => 'das private Logbuch einzusehen',
'action-block'                => 'den Benutzer zu sperren',
'action-protect'              => 'den Schutzstatus von Seiten zu ändern',
'action-import'               => 'Seiten aus einem anderen Wiki zu importieren',
'action-importupload'         => 'Seiten über das Hochladen einer Datei zu importieren',
'action-patrol'               => 'die Bearbeitungen andere Benutzer zu kontrollieren',
'action-autopatrol'           => 'eigene Bearbeitungen als kontrolliert zu markieren',
'action-unwatchedpages'       => 'die Liste der unbeobachteten Seiten einzusehen',
'action-trackback'            => 'einen Trackback zu übertragen',
'action-mergehistory'         => 'die Versionengeschichten von Seiten zu vereinen',
'action-userrights'           => 'Benutzerrechte zu ändern',
'action-userrights-interwiki' => 'die Rechte von Benutzern in anderen Wikis zu ändern',
'action-siteadmin'            => 'die Datenbank zu sperren oder freizugeben',
'action-revisionmove'         => 'Versionen zu verschieben',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Änderung|Änderungen}}',
'recentchanges'                     => 'Letzte Änderungen',
'recentchanges-legend'              => 'Anzeigeoptionen',
'recentchangestext'                 => "Auf dieser Seite kannst du die letzten Änderungen auf '''{{SITENAME}}''' nachverfolgen.",
'recentchanges-feed-description'    => 'Verfolge mit diesem Feed die letzten Änderungen in {{SITENAME}}.',
'recentchanges-label-newpage'       => 'Neue Seite',
'recentchanges-label-minor'         => 'Kleine Änderung',
'recentchanges-label-bot'           => 'Änderung durch einen Bot',
'recentchanges-label-unpatrolled'   => 'Nicht-kontrollierte Änderung',
'rcnote'                            => "{{PLURAL:$1|'''1''' Änderung|'''$1''' Änderungen}} in den {{PLURAL:$2|letzten 24 Stunden|letzten '''$2''' Tagen}}.
Stand: $4, $5 Uhr.",
'rcnotefrom'                        => "Angezeigt werden die Änderungen seit '''$2''' (max. '''$1''' Einträge).",
'rclistfrom'                        => 'Nur Änderungen seit $1 zeigen.',
'rcshowhideminor'                   => 'Kleine Änderungen $1',
'rcshowhidebots'                    => 'Bots $1',
'rcshowhideliu'                     => 'Angemeldete Benutzer $1',
'rcshowhideanons'                   => 'Unangemeldete Benutzer $1',
'rcshowhidepatr'                    => 'Kontrollierte Änderungen $1',
'rcshowhidemine'                    => 'Eigene Beiträge $1',
'rclinks'                           => 'Zeige die letzten $1 Änderungen der letzten $2 Tage.<br />$3',
'diff'                              => 'Unterschied',
'hist'                              => 'Versionen',
'hide'                              => 'ausblenden',
'show'                              => 'einblenden',
'minoreditletter'                   => 'K',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|beobachtender|beobachtende}} Benutzer]',
'rc_categories'                     => 'Nur Seiten aus den Kategorien (getrennt mit „|“):',
'rc_categories_any'                 => 'Alle',
'rc-change-size'                    => '$1 {{PLURAL:$1|Byte|Bytes}}',
'newsectionsummary'                 => 'Neuer Abschnitt /* $1 */',
'rc-enhanced-expand'                => 'Details anzeigen (benötigt JavaScript)',
'rc-enhanced-hide'                  => 'Details verstecken',

# Recent changes linked
'recentchangeslinked'          => 'Änderungen an verlinkten Seiten',
'recentchangeslinked-feed'     => 'Änderungen an verlinkten Seiten',
'recentchangeslinked-toolbox'  => 'Änderungen an verlinkten Seiten',
'recentchangeslinked-title'    => 'Änderungen an Seiten, die von „$1“ verlinkt sind',
'recentchangeslinked-noresult' => 'Im ausgewählten Zeitraum wurden an den verlinkten Seiten keine Änderungen vorgenommen.',
'recentchangeslinked-summary'  => "Diese Spezialseite listet die letzten Änderungen an den verlinkten Seiten auf (bzw. bei Kategorien an den Mitgliedern dieser Kategorie). Seiten auf deiner [[Special:Watchlist|Beobachtungsliste]] sind '''fett''' dargestellt.",
'recentchangeslinked-page'     => 'Seite:',
'recentchangeslinked-to'       => 'Zeige Änderungen auf Seiten, die hierher verlinken',

# Upload
'upload'                      => 'Datei hochladen',
'uploadbtn'                   => 'Datei hochladen',
'reuploaddesc'                => 'Abbrechen und zurück zur Hochladen-Seite',
'upload-tryagain'             => 'Geänderte Dateibeschreibung abschicken',
'uploadnologin'               => 'Nicht angemeldet',
'uploadnologintext'           => 'Du musst [[Special:UserLogin|angemeldet sein]], um Dateien hochladen zu können.',
'upload_directory_missing'    => 'Das Upload-Verzeichnis ($1) fehlt und konnte durch den Webserver auch nicht erstellt werden.',
'upload_directory_read_only'  => 'Der Webserver hat keine Schreibrechte für das Upload-Verzeichnis ($1).',
'uploaderror'                 => 'Fehler beim Hochladen',
'upload-recreate-warning'     => "'''Achtung: Eine Datei dieses Namens wurde bereits gelöscht oder verschoben.'''

Es folgt ein Auszug aus dem Lösch- und Verschiebungs-Logbuch dieser Datei.",
'uploadtext'                  => "Benutze dieses Formular, um neue Dateien hochzuladen.

Gehe zu der [[Special:FileList|Liste hochgeladener Dateien]], um vorhandene Dateien zu suchen und anzuzeigen. Siehe auch das [[Special:Log/upload|Datei-]] und [[Special:Log/delete|Lösch-Logbuch]].

Um ein '''Bild''' in einer Seite zu verwenden, nutze einen Link in der folgenden Form:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datei.jpg]]</nowiki></tt>''' – für ein Vollbild
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datei.png|200px|thumb|left|Alternativer Text]]</nowiki></tt>''' – für ein 200px breites Bild innerhalb einer Box, mit „Alternativer Text“ als Bildbeschreibung
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datei.ogg]]</nowiki></tt>''' – für einen direkten Link auf die Datei, ohne Darstellung der Datei",
'upload-permitted'            => 'Erlaubte Dateitypen: $1.',
'upload-preferred'            => 'Bevorzugte Dateitypen: $1.',
'upload-prohibited'           => 'Nicht erlaubte Dateitypen: $1.',
'uploadlog'                   => 'Datei-Logbuch',
'uploadlogpage'               => 'Datei-Logbuch',
'uploadlogpagetext'           => 'Dies ist das Logbuch der hochgeladenen Dateien, siehe auch die [[Special:NewFiles|Galerie neuer Dateien]] für einen visuellen Überblick.',
'filename'                    => 'Dateiname',
'filedesc'                    => 'Beschreibung',
'fileuploadsummary'           => 'Beschreibung/Quelle:',
'filereuploadsummary'         => 'Dateiänderungen:',
'filestatus'                  => 'Copyright-Status:',
'filesource'                  => 'Quelle:',
'uploadedfiles'               => 'Hochgeladene Dateien',
'ignorewarning'               => 'Warnung ignorieren und Datei speichern',
'ignorewarnings'              => 'Warnungen ignorieren',
'minlength1'                  => 'Dateinamen müssen mindestens einen Buchstaben lang sein.',
'illegalfilename'             => 'Der Dateiname „$1“ enthält mindestens ein nicht erlaubtes Zeichen. Bitte benenne die Datei um und versuche sie erneut hochzuladen.',
'badfilename'                 => 'Der Dateiname wurde in „$1“ geändert.',
'filetype-mime-mismatch'      => 'Dateierweiterung „.$1“ stimmt nicht mit dem MIME-Typ ($2) überein.',
'filetype-badmime'            => 'Dateien mit dem MIME-Typ „$1“ dürfen nicht hochgeladen werden.',
'filetype-bad-ie-mime'        => 'Diese Datei kann nicht hochgeladen werden, da der Internet Explorer sie als „$1“ erkennt, welcher ein nicht erlaubter potentiell gefährlicher Dateityp ist.',
'filetype-unwanted-type'      => "'''„.$1“''' ist ein unerwünschtes Dateiformat. Erlaubt {{PLURAL:$3|ist das Dateiformat|sind die Dateiformate}}: $2.",
'filetype-banned-type'        => "'''„.$1“''' {{PLURAL:$4|ist ein nicht erlaubter Dateityp|sind nicht erlaubte Dateitypen}}.
{{PLURAL:$3|Erlaubter Dateityp ist|Erlaubte Dateitypen sind}} $2.",
'filetype-missing'            => 'Die hochzuladende Datei hat keine Erweiterung (z. B. „.jpg“).',
'empty-file'                  => 'Die übertragene Datei ist leer',
'file-too-large'              => 'Die übertragene Datei ist zu groß',
'filename-tooshort'           => 'Der Dateiname ist zu kurz',
'filetype-banned'             => 'Dieser Dateityp ist gesperrt',
'verification-error'          => 'Diese Datei hat die Dateiprüfung nicht bestanden.',
'hookaborted'                 => 'Der Versuch, die Änderung durchzuführen, ist aufgrund eines Extension-Hooks fehlgeschlagen',
'illegal-filename'            => 'Der Dateiname ist nicht erlaubt',
'overwrite'                   => 'Das Überschreiben einer existierenden Datei ist nicht erlaubt',
'unknown-error'               => 'Ein unbekannter Fehler ist aufgetreten',
'tmp-create-error'            => 'Temporäre Datei konnte nicht erstellt werden',
'tmp-write-error'             => 'Fehler beim Schreiben der temporären Datei',
'large-file'                  => 'Die Dateigröße sollte nach Möglichkeit $1 nicht überschreiten. Diese Datei ist $2 groß.',
'largefileserver'             => 'Die Datei ist größer als die vom Server eingestellte Maximalgröße.',
'emptyfile'                   => 'Die hochgeladene Datei ist leer. Der Grund kann ein Tippfehler im Dateinamen sein. Bitte kontrolliere, ob du die Datei wirklich hochladen willst.',
'fileexists'                  => "Eine Datei mit diesem Namen existiert bereits.
Bitte prüfe '''<tt>[[:$1]]</tt>''', wenn du dir nicht sicher bist, ob du sie ändern willst.
[[$1|thumb]]",
'filepageexists'              => "Eine Beschreibungsseite wurde bereits als '''<tt>[[:$1]]</tt>''' erstellt, es ist aber keine Datei mit diesem Namen vorhanden.
Die eingegebene Beschreibung wird nicht auf die Beschreibungsseite übernommen.
Die Beschreibungsseite musst du nach dem Hochladen der Datei noch manuell bearbeiten.
[[$1|thumb]]",
'fileexists-extension'        => "Eine Datei mit ähnlichem Namen existiert bereits: [[$2|thumb]]
* Name der hochzuladenden Datei: '''<tt>[[:$1]]</tt>'''
* Name der vorhandenen Datei: '''<tt>[[:$2]]</tt>'''
Bitte wähle einen anderen Namen.",
'fileexists-thumbnail-yes'    => "Bei der Datei scheint es sich um ein Bild verringerter Größe ''(thumbnail)'' zu handeln. [[$1|thumb]]
Bitte prüfe die Datei '''<tt>[[:$1]]</tt>'''.
Wenn es sich um das Bild in Originalgröße handelt, so braucht kein separates Vorschaubild hochgeladen zu werden.",
'file-thumbnail-no'           => "Der Dateiname beginnt mit '''<tt>$1</tt>'''. Dies deutet auf ein Bild verringerter Größe ''(thumbnail)'' hin.
Bitte prüfe, ob du das Bild in voller Auflösung vorliegen hast und lade dieses unter dem Originalnamen hoch.",
'fileexists-forbidden'        => 'Unter diesem Namen existiert bereits eine Datei und sie kann nicht überschrieben werden. Bitte gehe zurück und lade die Datei unter einem anderen Namen hoch. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Unter diesem Namen existiert bereits eine Datei im zentralen Medienarchiv.
Wenn du diese Datei trotzdem hochladen möchtest, gehe bitte zurück und ändere den Namen.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Diese Datei ist ein Duplikat der folgenden {{PLURAL:$1|Datei|$1 Dateien}}:',
'file-deleted-duplicate'      => 'Eine mit dieser identische Datei ([[:$1]]) wurde früher gelöscht. Sieh das Lösch-Logbuch ein, bevor du sie hochlädst.',
'uploadwarning'               => 'Warnung',
'uploadwarning-text'          => 'Bitte ändere unten die Dateibeschreibung und versuche es erneut.',
'savefile'                    => 'Datei speichern',
'uploadedimage'               => 'hat „[[$1]]“ hochgeladen',
'overwroteimage'              => 'hat eine neue Version von „[[$1]]“ hochgeladen',
'uploaddisabled'              => 'Hochladen deaktiviert',
'copyuploaddisabled'          => 'Das Hochladen von URLs ist deaktiviert',
'uploadfromurl-queued'        => 'Dein Upload befindet sich nun in der Warteschlange.',
'uploaddisabledtext'          => 'Das Hochladen von Dateien ist deaktiviert.',
'php-uploaddisabledtext'      => 'Das Hochladen von Dateien wurde in PHP deaktiviert.
Bitte überprüfe die <code>file_uploads</code>-Einstellung.',
'uploadscripted'              => 'Diese Datei enthält HTML- oder Scriptcode, der irrtümlich von einem Webbrowser ausgeführt werden könnte.',
'uploadvirus'                 => 'Diese Datei enthält einen Virus! Details: $1',
'uploadjava'                  => 'Dies ist eine ZIP-Datei, die ein CLASS-Datei von Java enthält.
Das Hochladen von Java-Dateien ist nicht gestattet, da sie die Umgehung von Sicherheitseinschränkungen ermöglichen könnten.',
'upload-source'               => 'Quelldatei',
'sourcefilename'              => 'Quelldatei:',
'sourceurl'                   => 'Quell-URL:',
'destfilename'                => 'Zielname:',
'upload-maxfilesize'          => 'Maximale Dateigröße: $1',
'upload-description'          => 'Dateibeschreibung',
'upload-options'              => 'Hochlade-Optionen',
'watchthisupload'             => 'Diese Datei beobachten',
'filewasdeleted'              => 'Eine Datei mit diesem Namen wurde schon einmal hochgeladen und zwischenzeitlich wieder gelöscht. Bitte prüfe zuerst den Eintrag im $1, bevor du die Datei wirklich speicherst.',
'upload-wasdeleted'           => "'''Achtung: Du lädst eine Datei hoch, die bereits früher gelöscht wurde.'''

Bitte prüfe sorgfältig, ob das erneute Hochladen den Richtlinien entspricht.
Zu deiner Information folgt das Lösch-Logbuch mit der Begründung für die vorhergehende Löschung:",
'filename-bad-prefix'         => "Der Dateiname beginnt mit '''„$1“'''. Dies ist im allgemeinen der von einer Digitalkamera vorgegebene Dateiname und daher nicht sehr aussagekräftig.
Bitte gib der Datei einen Namen, der den Inhalt besser beschreibt.",
'filename-prefix-blacklist'   => ' #<!-- Diese Zeile darf nicht verändert werden! --> <pre>
#  Syntax:
#   * Alles von einem #-Zeichen bis zum Ende der Zeile ist ein Kommentar.
#   * Jede nicht-leere Zeile ist der Anfang eines typischen Dateinamens, der automatisch von einer Digitalkamera erzeugt wird.
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # einige Mobiltelefone
IMG # allgemein
JD # Jenoptik
MGP # Pentax
PICT # verschiedene
 #</pre> <!-- Diese Zeile darf nicht verändert werden!-->',
'upload-success-subj'         => 'Erfolgreich hochgeladen',
'upload-success-msg'          => 'Das Hochladen von [$2] war erfolgreich. Sie ist hier verfügbar: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Hochladefehler',
'upload-failure-msg'          => 'Es gab ein Problem mit deiner hochgeladenen Datei von [$2]:

$1',
'upload-warning-subj'         => 'Hochladewarnung',
'upload-warning-msg'          => 'Es gab ein Problem beim Hochladen von [$2]. Bitte zum [[Special:Upload/stash/$1|Hochladeformular]] zurückkehren, um dieses Problem zu beheben.',

'upload-proto-error'        => 'Falsches Protokoll',
'upload-proto-error-text'   => 'Die URL muss mit <code>http://</code> oder <code>ftp://</code> beginnen.',
'upload-file-error'         => 'Interner Fehler',
'upload-file-error-text'    => 'Bei der Erstellung einer temporären Datei auf dem Server ist ein interner Fehler aufgetreten.
Bitte informiere einen [[Special:ListUsers/sysop|System-Administrator]].',
'upload-misc-error'         => 'Unbekannter Fehler beim Hochladen',
'upload-misc-error-text'    => 'Beim Hochladen ist ein unbekannter Fehler aufgetreten.
Prüfe die URL auf Fehler, den Online-Status der Seite und versuche es erneut.
Wenn das Problem weiter besteht, informiere einen [[Special:ListUsers/sysop|System-Administrator]].',
'upload-too-many-redirects' => 'Die URL beinhaltete zu viele Weiterleitungen',
'upload-unknown-size'       => 'Unbekannte Größe',
'upload-http-error'         => 'Ein HTTP-Fehler ist aufgetreten: $1',

# ZipDirectoryReader
'zip-file-open-error' => 'Es ist ein Fehler beim Öffnen der Datei zur ZIP-Überprüfung aufgetreten.',
'zip-wrong-format'    => 'Die angegebene Datei ist keine ZIP-Datei.',
'zip-bad'             => 'Die Datei ist beschädigt oder eine aus anderweitigen Gründen nicht lesbare ZIP-Datei.
Sie kann daher keiner ordnungsgemäßen Sicherheitsüberprüfung unterzogen werden.',
'zip-unsupported'     => 'Diese ZIP-Datei verfügt über Komprimierungseigenschaften, die nicht von MediaWiki unterstützt werden.
Sie kann daher keiner ordnungsgemäßen Sicherheitsüberprüfung unterzogen werden.',

# Special:UploadStash
'uploadstash'          => 'Vorabspeicherung beim Hochladen',
'uploadstash-summary'  => 'Diese Seite ermöglicht den Zugriff auf Dateien, die hochgeladen wurden, bzw. gerade hochgeladen werden, aber noch nicht auf dem Wiki publiziert wurden. Diese Dateien sind, der hochladende Benutzer ausgenommen, noch nicht öffentlich einsehbar.',
'uploadstash-clear'    => 'Die vorab gespeicherten Dateien entfernen',
'uploadstash-nofiles'  => 'Es sind keine vorab gespeicherten Dateien vorhanden.',
'uploadstash-badtoken' => 'Das Entfernen der vorab gespeicherten Dateien war erfolglos, vielleicht weil die Sitzungsdaten abgelaufen sind. Bitte erneut versuchen.',
'uploadstash-errclear' => 'Das Entfernen der vorab gespeicherten Dateien war erfolglos.',
'uploadstash-refresh'  => 'Liste der Dateien aktualisieren',

# img_auth script messages
'img-auth-accessdenied' => 'Zugriff verweigert',
'img-auth-nopathinfo'   => 'PATH_INFO fehlt.
Dein Server ist nicht dafür eingerichtet, diese Information weiterzugeben.
Es könnte CGI-basiert sein und unterstützt img_auth nicht.
Siehe http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Der gewünschte Pfad ist nicht im konfigurierten Uploadverzeichnis.',
'img-auth-badtitle'     => 'Aus „$1“ kann kein gültiger Titel erstellt werden.',
'img-auth-nologinnWL'   => 'Du bist nicht angemeldet und „$1“ ist nicht in der weißen Liste.',
'img-auth-nofile'       => 'Datei „$1“ existiert nicht.',
'img-auth-isdir'        => 'Du versuchst, auf ein Verzeichnis „$1“ zuzugreifen.
Nur Dateizugriff ist erlaubt.',
'img-auth-streaming'    => 'Lade „$1“.',
'img-auth-public'       => 'img_auth.php gibt Dateien von einem privaten Wiki aus.
Dieses Wiki wurde als ein öffentliches Wiki konfiguriert.
Aus Sicherheitsgründen ist img_auth.php deaktiviert.',
'img-auth-noread'       => 'Benutzer hat keine Berechtigung, „$1“ zu lesen.',

# HTTP errors
'http-invalid-url'      => 'Ungültige URL: $1',
'http-invalid-scheme'   => 'URLs mit dem Schema „$1“ werden nicht unterstützt',
'http-request-error'    => 'Fehler beim Senden der Anfrage.',
'http-read-error'       => 'HTTP-Lesefehler.',
'http-timed-out'        => 'Zeitüberschreitung bei der HTTP-Anfrage.',
'http-curl-error'       => 'Fehler beim Abruf der URL: $1',
'http-host-unreachable' => 'URL konnte nicht erreicht werden.',
'http-bad-status'       => 'Während der HTTP-Anfrage ist ein Fehler aufgetreten: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL ist nicht erreichbar',
'upload-curl-error6-text'  => 'Die angegebene URL ist nicht erreichbar. Prüfe sowohl die URL auf Fehler als auch den Online-Status der Seite.',
'upload-curl-error28'      => 'Zeitüberschreitung beim Hochladen',
'upload-curl-error28-text' => 'Die Seite braucht zu lange für eine Antwort. Prüfe, ob die Seite online ist, warte einen kurzen Moment und versuche es dann erneut. Es kann sinnvoll sein, einen erneuten Versuch zu einem anderen Zeitpunkt zu probieren.',

'license'            => 'Lizenz:',
'license-header'     => 'Lizenz',
'nolicense'          => 'keine Vorauswahl',
'license-nopreview'  => '(es ist keine Vorschau verfügbar)',
'upload_source_url'  => ' (gültige, öffentlich zugängliche URL)',
'upload_source_file' => ' (eine Datei auf deinem Computer)',

# Special:ListFiles
'listfiles-summary'     => 'Diese Spezialseite listet alle hochgeladenen Dateien auf. Standardmäßig werden die zuletzt hochgeladenen Dateien zuerst angezeigt. Durch einen Klick auf die Spaltenüberschriften kann die Sortierung umgedreht werden oder es kann nach einer anderen Spalte sortiert werden.',
'listfiles_search_for'  => 'Suche nach Datei:',
'imgfile'               => 'Datei',
'listfiles'             => 'Dateiliste',
'listfiles_thumb'       => 'Vorschaubild',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Name',
'listfiles_user'        => 'Benutzer',
'listfiles_size'        => 'Größe',
'listfiles_description' => 'Beschreibung',
'listfiles_count'       => 'Versionen',

# File description page
'file-anchor-link'          => 'Datei',
'filehist'                  => 'Dateiversionen',
'filehist-help'             => 'Klicke auf einen Zeitpunkt, um diese Version zu laden.',
'filehist-deleteall'        => 'Alle Versionen löschen',
'filehist-deleteone'        => 'Diese Version löschen',
'filehist-revert'           => 'zurücksetzen',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Version vom',
'filehist-thumb'            => 'Vorschaubild',
'filehist-thumbtext'        => 'Vorschaubild der Version vom $2, $3 Uhr',
'filehist-nothumb'          => 'Kein Vorschaubild vorhanden',
'filehist-user'             => 'Benutzer',
'filehist-dimensions'       => 'Maße',
'filehist-filesize'         => 'Dateigröße',
'filehist-comment'          => 'Kommentar',
'filehist-missing'          => 'Datei fehlt',
'imagelinks'                => 'Dateiverwendungen',
'linkstoimage'              => 'Die {{PLURAL:$1|folgende Seite verwendet|folgenden $1 Seiten verwenden}} diese Datei:',
'linkstoimage-more'         => 'Mehr als {{PLURAL:$1|eine Seite verlinkt|$1 Seiten verlinken}} auf diese Datei.
Die folgende Liste zeigt nur {{PLURAL:$1|den ersten Link|die ersten $1 Links}} auf diese Datei.
Eine [[Special:WhatLinksHere/$2|vollständige Liste]] ist verfügbar.',
'nolinkstoimage'            => 'Keine Seite benutzt diese Datei.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Weitere Links]] auf diese Datei.',
'redirectstofile'           => 'Die {{PLURAL:$1|folgende Datei leitet|folgenden $1 Dateien leiten}} auf diese Datei weiter:',
'duplicatesoffile'          => 'Die {{PLURAL:$1|folgende Datei ist ein Duplikat|folgenden $1 Dateien sind Duplikate}} dieser Datei ([[Special:FileDuplicateSearch/$2|weitere Details]]):',
'sharedupload'              => 'Diese Datei stammt aus $1 und darf von anderen Projekten verwendet werden.',
'sharedupload-desc-there'   => 'Diese Datei stammt aus $1 und darf von anderen Projekten verwendet werden. Siehe auf der [$2 Dateibeschreibungsseite] nach weiteren Informationen.',
'sharedupload-desc-here'    => 'Diese Datei stammt aus $1 und darf von anderen Projekten verwendet werden. Die Beschreibung der [$2 Dateibeschreibungsseite] wird unten angezeigt.',
'filepage-nofile'           => 'Es existiert keine Datei mit diesem Namen.',
'filepage-nofile-link'      => 'Es existiert keine Datei mit diesem Namen, aber du kannst [$1 diese Datei hochladen].',
'uploadnewversion-linktext' => 'Eine neue Version dieser Datei hochladen',
'shared-repo-from'          => 'aus $1',
'shared-repo'               => 'einem gemeinsam genutzten Medienarchiv',
'filepage.css'              => '/* Das folgende CSS wird auf Dateibeschreibungsseiten, auch auf fremden Client-Wikis, geladen. */',

# File reversion
'filerevert'                => 'Zurücksetzen von „$1“',
'filerevert-legend'         => 'Datei zurücksetzen',
'filerevert-intro'          => "Du setzt die Datei '''[[Media:$1|$1]]''' auf die [$4 Version vom $2, $3 Uhr] zurück.",
'filerevert-comment'        => 'Grund:',
'filerevert-defaultcomment' => 'Zurückgesetzt auf die Version vom $1, $2 Uhr',
'filerevert-submit'         => 'Zurücksetzen',
'filerevert-success'        => "'''[[Media:$1|$1]]''' wurde auf die [$4 Version vom $2, $3 Uhr] zurückgesetzt.",
'filerevert-badversion'     => 'Es gibt keine Version der Datei zu dem angegebenen Zeitpunkt.',

# File deletion
'filedelete'                  => 'Lösche „$1“',
'filedelete-legend'           => 'Lösche Datei',
'filedelete-intro'            => "Du löschst die Datei '''„[[Media:$1|$1]]“''' inklusive ihrer Versionsgeschichte.",
'filedelete-intro-old'        => "Du löschst von der Datei '''„[[Media:$1|$1]]“''' die [$4 Version vom $2, $3 Uhr].",
'filedelete-comment'          => 'Grund:',
'filedelete-submit'           => 'Löschen',
'filedelete-success'          => "'''„$1“''' wurde gelöscht.",
'filedelete-success-old'      => "Von der Datei '''„[[Media:$1|$1]]“''' wurde die Version vom $2, $3 Uhr gelöscht.",
'filedelete-nofile'           => "'''„$1“''' ist nicht vorhanden.",
'filedelete-nofile-old'       => "Es gibt von '''„$1“''' keine archivierte Version mit den angegebenen Attributen.",
'filedelete-otherreason'      => 'Anderer/ergänzender Grund:',
'filedelete-reason-otherlist' => 'Anderer Grund',
'filedelete-reason-dropdown'  => '
* Allgemeine Löschgründe
** Urheberrechtsverletzung
** Duplikat',
'filedelete-edit-reasonlist'  => 'Löschgründe bearbeiten',
'filedelete-maintenance'      => 'Das Löschen und Wiederherstellen von Dateien ist aufgrund von Wartungsarbeiten vorübergehend deaktiviert.',

# MIME search
'mimesearch'         => 'Suche nach MIME-Typ',
'mimesearch-summary' => 'Auf dieser Spezialseite können die Dateien nach dem MIME-Typ gefiltert werden. Die Eingabe muss immer den Medien- und Subtyp beinhalten: <tt>image/jpeg</tt> (siehe Dateibeschreibungsseite).',
'mimetype'           => 'MIME-Typ:',
'download'           => 'Herunterladen',

# Unwatched pages
'unwatchedpages' => 'Nicht beobachtete Seiten',

# List redirects
'listredirects' => 'Weiterleitungsliste',

# Unused templates
'unusedtemplates'     => 'Unbenutzte Vorlagen',
'unusedtemplatestext' => 'Diese Seite listet alle Seiten im {{ns:template}}-Namensraum auf, die nicht in anderen Seiten eingebunden sind.
Überprüfe andere Links zu den Vorlagen, bevor du diese löscht.',
'unusedtemplateswlh'  => 'Andere Links',

# Random page
'randompage'         => 'Zufällige Seite',
'randompage-nopages' => 'Es sind keine Seiten {{PLURAL:$2|im folgenden Namensraum|in den folgenden Namensräumen}} enthalten: „$1“',

# Random redirect
'randomredirect'         => 'Zufällige Weiterleitung',
'randomredirect-nopages' => 'Im Namensraum „$1“ sind keine Weiterleitungen vorhanden.',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Seitenstatistik',
'statistics-header-edits'      => 'Bearbeitungsstatistik',
'statistics-header-views'      => 'Seitenaufrufstatistik',
'statistics-header-users'      => 'Benutzerstatistik',
'statistics-header-hooks'      => 'Andere Statistiken',
'statistics-articles'          => 'Inhaltsseiten',
'statistics-pages'             => 'Seiten',
'statistics-pages-desc'        => 'Alle Seiten in diesem Wiki, inklusive Diskussionsseiten, Weiterleitungen usw.',
'statistics-files'             => 'Hochgeladene Dateien',
'statistics-edits'             => 'Seitenbearbeitungen',
'statistics-edits-average'     => 'Bearbeitungen pro Seite im Durchschnitt',
'statistics-views-total'       => 'Seitenaufrufe gesamt',
'statistics-views-total-desc'  => 'Aufrufe nicht vorhandener Seiten und von Spezialseiten werden nicht berücksichtigt',
'statistics-views-peredit'     => 'Seitenaufrufe pro Bearbeitung',
'statistics-users'             => 'Registrierte [[Special:ListUsers|Benutzer]]',
'statistics-users-active'      => 'Aktive Benutzer',
'statistics-users-active-desc' => 'Benutzer mit Bearbeitungen {{PLURAL:$1|in den letzten 24 Stunden|in den vergangenen $1 Tagen}}',
'statistics-mostpopular'       => 'Meistbesuchte Seiten',

'disambiguations'      => 'Begriffsklärungsseiten',
'disambiguationspage'  => 'Template:Begriffsklärung',
'disambiguations-text' => 'Die folgenden Seiten verlinken auf eine Seite zur Begriffsklärung. Sie sollten statt dessen auf die eigentlich gemeinte Seite verlinken.

Eine Seite gilt als Begriffsklärungsseite, wenn sie eine der in [[MediaWiki:Disambiguationspage]] aufgeführte(n) Vorlage(n) einbindet.<br />
Links aus Namensräumen werden hier nicht aufgelistet.',

'doubleredirects'                   => 'Doppelte Weiterleitungen',
'doubleredirectstext'               => 'Diese Liste enthält Weiterleitungen, die auf weitere Weiterleitungen verlinken.
Jede Zeile enthält Links zu der ersten und zweiten Weiterleitung sowie das Ziel der zweiten Weiterleitung, welches für gewöhnlich die gewünschte Zielseite ist, auf die bereits die erste Weiterleitung zeigen sollte.
<del>Durchgestrichene</del> Einträge wurden bereits erledigt.',
'double-redirect-fixed-move'        => '[[$1]] wurde verschoben und leitet nun nach [[$2]] weiter.',
'double-redirect-fixed-maintenance' => 'Bereinigung der doppelten Weiterleitung von [[$1]] nach [[$2]].',
'double-redirect-fixer'             => 'RedirectBot',

'brokenredirects'        => 'Kaputte Weiterleitungen',
'brokenredirectstext'    => 'Diese Spezialseite listet Weiterleitungen auf nicht existierende Seiten auf.',
'brokenredirects-edit'   => 'bearbeiten',
'brokenredirects-delete' => 'löschen',

'withoutinterwiki'         => 'Seiten ohne Links zu anderen Sprachen',
'withoutinterwiki-summary' => 'Die folgenden Seiten verlinken nicht auf andere Sprachversionen.',
'withoutinterwiki-legend'  => 'Präfix',
'withoutinterwiki-submit'  => 'Zeige',

'fewestrevisions' => 'Seiten mit den wenigsten Versionen',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '{{PLURAL:$1|1 Link|$1 Links}}',
'nmembers'                => '{{PLURAL:$1|1 Eintrag|$1 Einträge}}',
'nrevisions'              => '{{PLURAL:$1|1 Bearbeitung|$1 Bearbeitungen}}',
'nviews'                  => '{{PLURAL:$1|1 Abfrage|$1 Abfragen}}',
'nimagelinks'             => 'Verwendet auf $1 {{PLURAL:$1|Seite|Seiten}}',
'ntransclusions'          => 'verwendet auf $1 {{PLURAL:$1|Seite|Seiten}}',
'specialpage-empty'       => 'Die Seite enthält aktuell keine Einträge.',
'lonelypages'             => 'Verwaiste Seiten',
'lonelypagestext'         => 'Die folgenden Seiten werden nicht eingebunden oder es wird nicht auf sie in {{SITENAME}} verwiesen.',
'uncategorizedpages'      => 'Nicht kategorisierte Seiten',
'uncategorizedcategories' => 'Nicht kategorisierte Kategorien',
'uncategorizedimages'     => 'Nicht kategorisierte Dateien',
'uncategorizedtemplates'  => 'Nicht kategorisierte Vorlagen',
'unusedcategories'        => 'Unbenutzte Kategorien',
'unusedimages'            => 'Unbenutzte Dateien',
'popularpages'            => 'Beliebteste Seiten',
'wantedcategories'        => 'Benutzte, aber nicht angelegte Kategorien',
'wantedpages'             => 'Gewünschte Seiten',
'wantedpages-badtitle'    => 'Ungültiger Titel im Ergebnis: $1',
'wantedfiles'             => 'Fehlende Dateien',
'wantedtemplates'         => 'Fehlende Vorlagen',
'mostlinked'              => 'Häufig verlinkte Seiten',
'mostlinkedcategories'    => 'Meistbenutzte Kategorien',
'mostlinkedtemplates'     => 'Meistbenutzte Vorlagen',
'mostcategories'          => 'Meistkategorisierte Seiten',
'mostimages'              => 'Meistbenutzte Dateien',
'mostrevisions'           => 'Seiten mit den meisten Versionen',
'prefixindex'             => 'Alle Seiten (mit Präfix)',
'shortpages'              => 'Kurze Seiten',
'longpages'               => 'Lange Seiten',
'deadendpages'            => 'Sackgassenseiten',
'deadendpagestext'        => 'Die folgenden Seiten verweisen nicht auf andere Seiten von {{SITENAME}}.',
'protectedpages'          => 'Geschützte Seiten',
'protectedpages-indef'    => 'Nur unbeschränkt geschützte Seiten zeigen',
'protectedpages-cascade'  => 'Nur Seiten mit Kaskadenschutz',
'protectedpagestext'      => 'Diese Spezialseite zeigt alle vor dem Verschieben oder Bearbeiten geschützten Seiten.',
'protectedpagesempty'     => 'Aktuell sind keine Seiten mit diesen Parametern geschützt.',
'protectedtitles'         => 'Geschützte Titel',
'protectedtitlestext'     => 'Die folgenden Titel wurden zur Neuerstellung gesperrt.',
'protectedtitlesempty'    => 'Zurzeit sind mit den angegebenen Parametern keine Seiten zur Neuerstellung gesperrt.',
'listusers'               => 'Benutzerverzeichnis',
'listusers-editsonly'     => 'Zeige nur Benutzer mit Beiträgen',
'listusers-creationsort'  => 'Nach Erstelldatum sortieren',
'usereditcount'           => '$1 {{PLURAL:$1|Bearbeitung|Bearbeitungen}}',
'usercreated'             => 'Erstellt am $1 um $2 Uhr',
'newpages'                => 'Neue Seiten',
'newpages-username'       => 'Benutzername:',
'ancientpages'            => 'Seit längerem unbearbeitete Seiten',
'move'                    => 'Verschieben',
'movethispage'            => 'Seite verschieben',
'unusedimagestext'        => 'Bitte beachte, dass andere Webseiten eine Datei mit einer direkten URL verlinken können. Sie könnte daher hier aufgelistet sein, obwohl sie in aktiver Verwendung ist.',
'unusedcategoriestext'    => 'Diese Spezialseite zeigt alle Kategorien, die leer sind, also selbst keine Kategorien oder Seiten enthalten.',
'notargettitle'           => 'Keine Seite angegeben',
'notargettext'            => 'Du hast nicht angegeben, auf welche Seite diese Funktion angewendet werden soll.',
'nopagetitle'             => 'Quellseite nicht vorhanden',
'nopagetext'              => 'Die zu verschiebende Seite ist nicht vorhanden.',
'pager-newer-n'           => '{{PLURAL:$1|nächster|nächste $1}}',
'pager-older-n'           => '{{PLURAL:$1|vorheriger|vorherige $1}}',
'suppress'                => 'Oversight',
'querypage-disabled'      => 'Diese Spezialseite wurde aus Gründen der Leistungserhaltung deaktiviert.',

# Book sources
'booksources'               => 'ISBN-Suche',
'booksources-search-legend' => 'Suche nach Bezugsquellen für Bücher',
'booksources-go'            => 'Suchen',
'booksources-text'          => 'Dies ist eine Liste mit Links zu Internetseiten, die neue und gebrauchte Bücher verkaufen. Dort kann es auch weitere Informationen über die Bücher geben. {{SITENAME}} ist mit keinem dieser Anbieter geschäftlich verbunden.',
'booksources-invalid-isbn'  => 'Vermutlich ist die ISBN ungültig.
Bitte prüfe, ob sie korrekt von der Quelle übertragen wurde.',

# Special:Log
'specialloguserlabel'  => 'Benutzer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbücher',
'all-logs-page'        => 'Alle öffentlichen Logbücher',
'alllogstext'          => 'Dies ist die kombinierte Anzeige aller in {{SITENAME}} geführten Logbücher.
Die Ausgabe kann durch die Auswahl des Logbuchtyps, des Benutzers oder des Seitentitels eingeschränkt werden (Groß-/Kleinschreibung muss beachtet werden).',
'logempty'             => 'Keine passenden Einträge.',
'log-title-wildcard'   => 'Titel beginnt mit …',

# Special:AllPages
'allpages'          => 'Alle Seiten',
'alphaindexline'    => '$1 bis $2',
'nextpage'          => 'Nächste Seite ($1)',
'prevpage'          => 'Vorherige Seite ($1)',
'allpagesfrom'      => 'Seiten anzeigen ab:',
'allpagesto'        => 'Seiten anzeigen bis:',
'allarticles'       => 'Alle Seiten',
'allinnamespace'    => 'Alle Seiten (Namensraum: $1)',
'allnotinnamespace' => 'Alle Seiten (nicht im $1 Namensraum)',
'allpagesprev'      => 'Vorherige',
'allpagesnext'      => 'Nächste',
'allpagessubmit'    => 'Anwenden',
'allpagesprefix'    => 'Seiten anzeigen mit Präfix:',
'allpagesbadtitle'  => 'Der eingegebene Seitenname ist ungültig: Er hat entweder ein vorangestelltes Sprach-, ein Interwiki-Kürzel oder enthält ein oder mehrere Zeichen, welche in Seitennamen nicht verwendet werden dürfen.',
'allpages-bad-ns'   => 'Der Namensraum „$1“ ist in {{SITENAME}} nicht vorhanden.',

# Special:Categories
'categories'                    => 'Kategorien',
'categoriespagetext'            => 'Folgende {{PLURAL:$1|Kategorie enthält|Kategorien enthalten}} Seiten oder Dateien.
[[Special:UnusedCategories|Unbenutzte Kategorien]] werden hier nicht aufgeführt.
Siehe auch die Liste der [[Special:WantedCategories|gewünschten Kategorien]].',
'categoriesfrom'                => 'Zeige Kategorien ab:',
'special-categories-sort-count' => 'Sortierung nach Anzahl',
'special-categories-sort-abc'   => 'Sortierung nach Alphabet',

# Special:DeletedContributions
'deletedcontributions'             => 'Gelöschte Beiträge',
'deletedcontributions-title'       => 'Gelöschte Beiträge',
'sp-deletedcontributions-contribs' => 'Benutzerbeiträge',

# Special:LinkSearch
'linksearch'       => 'Weblink-Suche',
'linksearch-pat'   => 'Suchmuster:',
'linksearch-ns'    => 'Namensraum:',
'linksearch-ok'    => 'Suchen',
'linksearch-text'  => 'Diese Spezialseite ermöglicht die Suche nach Seiten, in denen bestimmte Weblinks enthalten sind. Dabei können Wildcards wie beispielsweise <tt>*.example.com</tt> benutzt werden.<br />Unterstützte Protokolle: <tt>$1</tt>',
'linksearch-line'  => '$1 ist verlinkt von $2',
'linksearch-error' => 'Wildcards können nur am Anfang der URL verwendet werden.',

# Special:ListUsers
'listusersfrom'      => 'Zeige Benutzer ab:',
'listusers-submit'   => 'Zeige',
'listusers-noresult' => 'Keinen Benutzer gefunden.',
'listusers-blocked'  => '(gesperrt)',

# Special:ActiveUsers
'activeusers'            => 'Liste aktiver Benutzer',
'activeusers-intro'      => 'Dies ist eine Liste von Benutzern, die innerhalb {{PLURAL:$1|des letzten Tages|der letzten $1 Tage}} Aktivitäten aufwiesen.',
'activeusers-count'      => '$1 {{PLURAL:$1|Bearbeitung|Bearbeitungen}} in den {{PLURAL:$3|letzten 24 Stunden|vergangenen $3 Tagen}}',
'activeusers-from'       => 'Zeige Benutzer ab:',
'activeusers-hidebots'   => 'Bots ausblenden',
'activeusers-hidesysops' => 'Administratoren ausblenden',
'activeusers-noresult'   => 'Keine Benutzer gefunden.',

# Special:Log/newusers
'newuserlogpage'              => 'Neuanmeldungs-Logbuch',
'newuserlogpagetext'          => 'Dies ist ein Logbuch der neu erstellten Benutzerkonten.',
'newuserlog-byemail'          => 'das Passwort wurde per E-Mail versandt',
'newuserlog-create-entry'     => 'Benutzer wurde neu registriert',
'newuserlog-create2-entry'    => 'erstellte neues Benutzerkonto „$1“',
'newuserlog-autocreate-entry' => 'Benutzerkonto wurde automatisch erstellt',

# Special:ListGroupRights
'listgrouprights'                      => 'Benutzergruppen-Rechte',
'listgrouprights-summary'              => 'Dies ist eine Liste der in diesem Wiki definierten Benutzergruppen und der damit verbundenen Rechte.
Zusätzliche Informationen über einzelne Rechte können [[{{MediaWiki:Listgrouprights-helppage}}|hier]] gefunden werden.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Gewährtes Recht</span>
* <span class="listgrouprights-revoked">Entzogenes Recht</span>',
'listgrouprights-group'                => 'Gruppe',
'listgrouprights-rights'               => 'Rechte',
'listgrouprights-helppage'             => 'Help:Gruppenrechte',
'listgrouprights-members'              => '(Mitgliederliste)',
'listgrouprights-addgroup'             => 'Benutzer zu {{PLURAL:$2|dieser Gruppe|diesen Gruppen}} hinzufügen: $1',
'listgrouprights-removegroup'          => 'Benutzer aus {{PLURAL:$2|dieser Gruppe|diesen Gruppen}} entfernen: $1',
'listgrouprights-addgroup-all'         => 'Benutzer zu allen Gruppen hinzufügen',
'listgrouprights-removegroup-all'      => 'Benutzer aus allen Gruppen entfernen',
'listgrouprights-addgroup-self'        => 'Eigenes Benutzerkonto zu {{PLURAL:$2|dieser Gruppe|diesen Gruppen}} hinzufügen: $1',
'listgrouprights-removegroup-self'     => 'Eigenes Benutzerkonto aus {{PLURAL:$2|dieser Gruppe|diesen Gruppen}} entfernen: $1',
'listgrouprights-addgroup-self-all'    => 'Kann alle Gruppen zum eigenen Konto hinzufügen',
'listgrouprights-removegroup-self-all' => 'Kann alle Gruppen vom eigenen Konto entfernen',

# E-mail user
'mailnologin'          => 'Fehler beim E-Mail-Versand',
'mailnologintext'      => 'Du musst [[Special:UserLogin|angemeldet sein]] und eine bestätigte E-Mail-Adresse in deinen [[Special:Preferences|Einstellungen]] eingetragen haben, um anderen Benutzern E-Mails schicken zu können.',
'emailuser'            => 'E-Mail an diesen Benutzer',
'emailpage'            => 'E-Mail an Benutzer',
'emailpagetext'        => 'Du kannst dem Benutzer mit dem unten stehenden Formular eine E-Mail senden.
Als Absender wird die E-Mail-Adresse aus deinen [[Special:Preferences|Einstellungen]] eingetragen, damit der Benutzer dir antworten kann.',
'usermailererror'      => 'Das E-Mail-Objekt gab einen Fehler zurück:',
'defemailsubject'      => '{{SITENAME}}-E-Mail',
'usermaildisabled'     => 'E-Mail-Empfang deaktiviert',
'usermaildisabledtext' => 'Du kannst in diesem Wiki keine E-Mails an andere Benutzer senden',
'noemailtitle'         => 'Keine E-Mail-Adresse',
'noemailtext'          => 'Dieser Benutzer hat keine gültige E-Mail-Adresse angegeben.',
'nowikiemailtitle'     => 'E-Mail-Versand nicht möglich',
'nowikiemailtext'      => 'Dieser Benutzer möchte keine E-Mails von anderen Benutzern erhalten.',
'email-legend'         => 'E-Mail an einen anderen {{SITENAME}}-Benutzer senden',
'emailfrom'            => 'Von:',
'emailto'              => 'An:',
'emailsubject'         => 'Betreff:',
'emailmessage'         => 'Nachricht:',
'emailsend'            => 'Senden',
'emailccme'            => 'Sende eine Kopie der E-Mail an mich',
'emailccsubject'       => 'Kopie deiner Nachricht an $1: $2',
'emailsent'            => 'E-Mail verschickt',
'emailsenttext'        => 'Deine E-Mail wurde verschickt.',
'emailuserfooter'      => 'Diese E-Mail wurde von {{SITENAME}}-Benutzer „$1“ an „$2“ gesendet.',

# User Messenger
'usermessage-summary'  => 'Systemnachricht gespeichert.',
'usermessage-editor'   => 'System-Messenger',
'usermessage-template' => 'MediaWiki:Benutzernachricht',

# Watchlist
'watchlist'            => 'Beobachtungsliste',
'mywatchlist'          => 'Beobachtungsliste',
'watchlistfor2'        => 'Von $1 $2',
'nowatchlist'          => 'Du hast keine Einträge auf deiner Beobachtungsliste.',
'watchlistanontext'    => 'Du musst dich $1, um deine Beobachtungsliste zu sehen oder Einträge auf ihr zu bearbeiten.',
'watchnologin'         => 'Du bist nicht angemeldet',
'watchnologintext'     => 'Du musst [[Special:UserLogin|angemeldet]] sein, um deine Beobachtungsliste zu bearbeiten.',
'addedwatch'           => 'Zur Beobachtungsliste hinzugefügt',
'addedwatchtext'       => 'Die Seite „[[:$1]]“ wurde zu deiner [[Special:Watchlist|Beobachtungsliste]] hinzugefügt.

Spätere Änderungen an dieser Seite und der dazugehörigen Diskussionsseite werden dort gelistet und
in der Übersicht der [[Special:RecentChanges|letzten Änderungen]] in Fettschrift dargestellt.',
'removedwatch'         => 'Von der Beobachtungsliste entfernt',
'removedwatchtext'     => 'Die Seite „[[:$1]]“ wurde von deiner [[Special:Watchlist|Beobachtungsliste]] entfernt.',
'watch'                => 'Beobachten',
'watchthispage'        => 'Seite beobachten',
'unwatch'              => 'nicht mehr beobachten',
'unwatchthispage'      => 'Nicht mehr beobachten',
'notanarticle'         => 'Keine Seite',
'notvisiblerev'        => 'Version wurde gelöscht',
'watchnochange'        => 'Keine der von dir beobachteten Seiten wurde während des angezeigten Zeitraums bearbeitet.',
'watchlist-details'    => 'Du beobachtest {{PLURAL:$1|1 Seite|$1 Seiten}}.',
'wlheader-enotif'      => '* Der E-Mail-Benachrichtigungsdienst ist aktiviert.',
'wlheader-showupdated' => "* Seiten mit noch nicht gesehenen Änderungen werden '''fett''' dargestellt.",
'watchmethod-recent'   => 'Überprüfen der letzten Bearbeitungen für die Beobachtungsliste',
'watchmethod-list'     => 'Überprüfen der Beobachtungsliste nach letzten Bearbeitungen',
'watchlistcontains'    => 'Deine Beobachtungsliste enthält $1 {{PLURAL:$1|Seite|Seiten}}.',
'iteminvalidname'      => 'Problem mit dem Eintrag „$1“, ungültiger Name.',
'wlnote'               => "Es {{PLURAL:$1|folgt die letzte Änderung|folgen die letzten '''$1''' Änderungen}} der letzten {{PLURAL:$2|Stunde|'''$2''' Stunden}}.",
'wlshowlast'           => 'Zeige die Änderungen der letzten $1 Stunden, $2 Tage oder $3.',
'watchlist-options'    => 'Anzeigeoptionen',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Beobachten …',
'unwatching' => 'Nicht beobachten …',

'enotif_mailer'                => '{{SITENAME}}-E-Mail-Benachrichtigungsdienst',
'enotif_reset'                 => 'Alle Seiten als besucht markieren',
'enotif_newpagetext'           => 'Das ist eine neue Seite.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Benutzer',
'changed'                      => 'geändert',
'created'                      => 'erstellt',
'enotif_subject'               => '[{{SITENAME}}] Die Seite „$PAGETITLE“ wurde von $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Alle Änderungen auf einen Blick: $1',
'enotif_lastdiff'              => 'Siehe $1 nach dieser Änderung.',
'enotif_anon_editor'           => 'Anonymer Benutzer $1',
'enotif_body'                  => 'Hallo $WATCHINGUSERNAME,

die {{SITENAME}}-Seite „$PAGETITLE“ wurde von $PAGEEDITOR am $PAGEEDITDATE um $PAGEEDITTIME Uhr $CHANGEDORCREATED.

Aktuelle Version: $PAGETITLE_URL

$NEWPAGE

Zusammenfassung des Bearbeiters: $PAGESUMMARY $PAGEMINOREDIT

Kontakt zum Bearbeiter:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Es werden dir solange keine weiteren Benachrichtigungs-E-Mails gesendet, bis du die Seite wieder besucht hast. Auf deiner Beobachtungsliste kannst du alle Benachrichtigungsmarkierungen zusammen zurücksetzen.

             Dein freundliches {{SITENAME}}-Benachrichtigungssystem

--
Um die Einstellungen deiner Beobachtungsliste anzupassen, besuche {{fullurl:{{#special:Watchlist}}/edit}}

Um die Seite von deiner Beobachtungsliste herunterzunehmen, besuche $UNWATCHURL

Rückmeldungen und weitere Hilfe: {{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Seite löschen',
'confirm'                => 'Bestätigen',
'excontent'              => 'Inhalt war: „$1“',
'excontentauthor'        => 'Inhalt war: „$1“ (einziger Bearbeiter: [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'Inhalt vor dem Leeren der Seite: „$1“',
'exblank'                => 'Seite war leer',
'delete-confirm'         => 'Löschen von „$1“',
'delete-legend'          => 'Löschen',
'historywarning'         => "'''Achtung:''' Die Seite, die du löschen möchtest, hat eine Versionsgeschichte mit ungefähr $1 {{PLURAL:$1|Version|Versionen}}:",
'confirmdeletetext'      => 'Du bist dabei, eine Seite mit allen zugehörigen älteren Versionen zu löschen. Bitte bestätige dazu, dass du dir der Konsequenzen bewusst bist, und dass du in Übereinstimmung mit den [[{{MediaWiki:Policy-url}}|Richtlinien]] handelst.',
'actioncomplete'         => 'Aktion beendet',
'actionfailed'           => 'Aktion fehlgeschlagen',
'deletedtext'            => '„<nowiki>$1</nowiki>“ wurde gelöscht. Im $2 findest du eine Liste der letzten Löschungen.',
'deletedarticle'         => 'hat „[[$1]]“ gelöscht',
'suppressedarticle'      => 'veränderte die Sichtbarkeit von „[[$1]]“',
'dellogpage'             => 'Lösch-Logbuch',
'dellogpagetext'         => 'Dies ist das Logbuch der gelöschten Seiten und Dateien.',
'deletionlog'            => 'Lösch-Logbuch',
'reverted'               => 'Auf eine alte Version zurückgesetzt',
'deletecomment'          => 'Grund:',
'deleteotherreason'      => 'Anderer/ergänzender Grund:',
'deletereasonotherlist'  => 'Anderer Grund',
'deletereason-dropdown'  => '* Allgemeine Löschgründe
** Wunsch des Autors
** Urheberrechtsverletzung
** Vandalismus',
'delete-edit-reasonlist' => 'Löschgründe bearbeiten',
'delete-toobig'          => 'Diese Seite hat mit mehr als $1 {{PLURAL:$1|Version|Versionen}} eine sehr lange Versionsgeschichte. Das Löschen solcher Seiten wurde eingeschränkt, um eine versehentliche Überlastung der Server zu verhindern.',
'delete-warning-toobig'  => 'Diese Seite hat mit mehr als $1 {{PLURAL:$1|Version|Versionen}} eine sehr lange Versionsgeschichte. Das Löschen kann zu Störungen im Datenbankbetrieb führen.',

# Rollback
'rollback'          => 'Zurücksetzen der Änderungen',
'rollback_short'    => 'Zurücksetzen',
'rollbacklink'      => 'Zurücksetzen',
'rollbackfailed'    => 'Zurücksetzen gescheitert',
'cantrollback'      => 'Die Änderung kann nicht zurückgesetzt werden, da es keine früheren Autoren gibt.',
'alreadyrolled'     => 'Das Zurücksetzen der Änderungen von [[User:$2|$2]] ([[User talk:$2|Diskussion]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) an [[:$1]] ist gescheitert, da in der Zwischenzeit ein anderer Benutzer die Seite geändert hat.

Die letzte Änderung stammt von [[User:$3|$3]] ([[User talk:$3|Diskussion]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Die Änderungszusammenfassung lautet: ''„$1“''.",
'revertpage'        => 'Änderungen von [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussion]]) rückgängig gemacht und letzte Version von [[User:$1|$1]] wiederhergestellt',
'revertpage-nouser' => 'Änderungen von (Benutzername entfernt) rückgängig gemacht und letzte Version von [[User:$1|$1]] wiederhergestellt',
'rollback-success'  => 'Die Änderungen von $1 wurden rückgängig gemacht und die letzte Version von $2 wurde wiederhergestellt.',

# Edit tokens
'sessionfailure-title' => 'Sitzungsfehler',
'sessionfailure'       => 'Es gab ein Problem mit der Übertragung deiner Benutzerdaten.
Diese Aktion wurde daher sicherheitshalber abgebrochen, um eine falsche Zuordnung deiner Änderungen zu einem anderen Benutzer zu verhindern.
Bitte gehe zurück und versuche den Vorgang erneut auszuführen.',

# Protect
'protectlogpage'              => 'Seitenschutz-Logbuch',
'protectlogtext'              => 'Dies ist das Seitenschutz-Logbuch.
Siehe die [[Special:ProtectedPages|Liste der geschützten Seiten]] für alle aktuell geschützten Seiten.',
'protectedarticle'            => 'schützte „[[$1]]“',
'modifiedarticleprotection'   => 'änderte den Schutz von „[[$1]]“',
'unprotectedarticle'          => 'hob den Schutz von „[[$1]]“ auf',
'movedarticleprotection'      => 'übertrug den Seitenschutz von „[[$2]]“ auf „[[$1]]“',
'protect-title'               => 'Schutz ändern von „$1“',
'prot_1movedto2'              => 'hat „[[$1]]“ nach „[[$2]]“ verschoben',
'protect-legend'              => 'Seitenschutzstatus ändern',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Sperrdauer:',
'protect_expiry_invalid'      => 'Die eingegebene Dauer ist ungültig.',
'protect_expiry_old'          => 'Die Sperrzeit liegt in der Vergangenheit.',
'protect-unchain-permissions' => 'Separate Sperroptionen aktivieren',
'protect-text'                => 'Hier kannst du den Schutzstatus der Seite „$1“ einsehen und ändern.',
'protect-locked-blocked'      => "Du kannst den Seitenschutz nicht ändern, da dein Benutzerkonto gesperrt ist. Hier sind die aktuellen Seitenschutz-Einstellungen der Seite '''„$1“:'''",
'protect-locked-dblock'       => "Die Datenbank ist gesperrt, der Seitenschutz kann daher nicht geändert werden. Hier sind die aktuellen Seitenschutz-Einstellungen der Seite '''„$1“:'''",
'protect-locked-access'       => "Dein Benutzerkonto verfügt nicht über die notwendigen Rechte zur Änderung des Seitenschutzes. Hier sind die aktuellen Seitenschutzeinstellungen der Seite '''„$1“:'''",
'protect-cascadeon'           => 'Diese Seite ist gegenwärtig Teil einer Kaskadensperre. Sie ist in die {{PLURAL:$1|folgende Seite|folgenden Seiten}} eingebunden, welche durch die Kaskadensperroption geschützt {{PLURAL:$1|ist|sind}}. Der Seitenschutzstatus dieser Seite kann geändert werden, dies hat jedoch keinen Einfluss auf die Kaskadensperre:',
'protect-default'             => 'Alle Benutzer',
'protect-fallback'            => 'Es wird die „$1“-Berechtigung benötigt.',
'protect-level-autoconfirmed' => 'Sperrung für neue und nicht registrierte Benutzer',
'protect-level-sysop'         => 'Nur Administratoren',
'protect-summary-cascade'     => 'kaskadierend',
'protect-expiring'            => 'bis $2, $3 Uhr (UTC)',
'protect-expiry-indefinite'   => 'unbeschränkt',
'protect-cascade'             => 'Kaskadierende Sperre – alle in diese Seite eingebundenen Vorlagen werden ebenfalls gesperrt.',
'protect-cantedit'            => 'Du kannst die Sperre dieser Seite nicht ändern, da du keine Berechtigung zum Bearbeiten der Seite hast.',
'protect-othertime'           => 'Andere Sperrdauer:',
'protect-othertime-op'        => 'andere Sperrdauer',
'protect-existing-expiry'     => 'Aktuelles Seitenschutzende: $2, $3 Uhr',
'protect-otherreason'         => 'Anderer/ergänzender Grund:',
'protect-otherreason-op'      => 'Anderer Grund',
'protect-dropdown'            => '*Allgemeine Schutzgründe
** Weblink-Spam
** Editwar
** Häufig eingebundene Vorlage
** Seite mit hoher Besucherzahl',
'protect-edit-reasonlist'     => 'Schutzgründe bearbeiten',
'protect-expiry-options'      => '1 Stunde:1 hour,1 Tag:1 day,1 Woche:1 week,2 Wochen:2 weeks,1 Monat:1 month,3 Monate:3 months,6 Monate:6 months,1 Jahr:1 year,Unbeschränkt:infinite',
'restriction-type'            => 'Schutzstatus:',
'restriction-level'           => 'Schutzhöhe:',
'minimum-size'                => 'Mindestgröße',
'maximum-size'                => 'Maximalgröße:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Bearbeiten',
'restriction-move'   => 'Verschieben',
'restriction-create' => 'Erstellen',
'restriction-upload' => 'Hochladen',

# Restriction levels
'restriction-level-sysop'         => 'geschützt (nur Administratoren)',
'restriction-level-autoconfirmed' => 'geschützt (nur angemeldete, nicht-neue Benutzer)',
'restriction-level-all'           => 'alle',

# Undelete
'undelete'                     => 'Gelöschte Seiten anzeigen',
'undeletepage'                 => 'Gelöschte Seiten anzeigen und wiederherstellen',
'undeletepagetitle'            => "'''Die folgende Ausgabe zeigt die gelöschten Versionen von [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Gelöschte Seiten anzeigen',
'undeletepagetext'             => 'Die {{PLURAL:$1|folgende Seite wurde gelöscht und kann|folgenden $1 Seiten wurden gelöscht und können}} von Administratoren wiederhergestellt werden:',
'undelete-fieldset-title'      => 'Wiederherstellen',
'undeleteextrahelp'            => '* Um die Seite komplett mit allen Versionen wiederherzustellen, wähle keine Version aus, gib eine Begründung an und klicke auf „{{int:undeletebtn}}“.
* Möchtest du nur bestimmte Versionen wiederherstellen, so wähle diese bitte einzeln anhand der Markierungen aus, gib eine Begründung an und klicke dann auf „{{int:undeletebtn}}“.
* „{{int:undeletereset}}“ leert das Kommentarfeld und entfernt alle Markierungen bei den Versionen.',
'undeleterevisions'            => '{{PLURAL:$1|1 Version|$1 Versionen}} archiviert',
'undeletehistory'              => 'Wenn du diese Seite wiederherstellst, werden auch alle alten Versionen wiederhergestellt.
Wenn seit der Löschung eine neue Seite gleichen Namens erstellt wurde, werden die wiederhergestellten Versionen chronologisch in die Versionsgeschichte eingeordnet.',
'undeleterevdel'               => 'Die Wiederherstellung wird nicht durchgeführt, wenn die aktuellste Version versteckt ist oder versteckte Teile enthält.
In diesem Fall darf die aktuellste Version nicht markiert werden oder ihr Status muss auf den einer normalen Version geändert werden.',
'undeletehistorynoadmin'       => 'Diese Seite wurde gelöscht. Der Löschgrund ist in der Zusammenfassung angegeben,
genauso wie Details zum letzten Benutzer, der diese Seite vor der Löschung bearbeitet hat.
Der aktuelle Text der gelöschten Seite ist nur Administratoren zugänglich.',
'undelete-revision'            => 'Gelöschte Version von $1 (vom $4 um $5 Uhr), $3:',
'undeleterevision-missing'     => 'Ungültige oder fehlende Version. Entweder ist der Link falsch oder die Version wurde aus dem Archiv wiederhergestellt oder entfernt.',
'undelete-nodiff'              => 'Keine vorhergehende Version vorhanden.',
'undeletebtn'                  => 'Wiederherstellen',
'undeletelink'                 => 'ansehen/wiederherstellen',
'undeleteviewlink'             => 'ansehen',
'undeletereset'                => 'Abbrechen',
'undeleteinvert'               => 'Auswahl umkehren',
'undeletecomment'              => 'Grund:',
'undeletedarticle'             => 'hat „[[$1]]“ wiederhergestellt',
'undeletedrevisions'           => '{{PLURAL:$1|1 Version wurde|$1 Versionen wurden}} wiederhergestellt',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 Version|$1 Versionen}} und {{PLURAL:$2|1 Datei|$2 Dateien}} wurden wiederhergestellt',
'undeletedfiles'               => '{{PLURAL:$1|1 Datei wurde|$1 Dateien wurden}} wiederhergestellt',
'cannotundelete'               => 'Wiederherstellung fehlgeschlagen; jemand anderes hat die Seite bereits wiederhergestellt.',
'undeletedpage'                => "'''„$1“''' wurde wiederhergestellt.

Im [[Special:Log/delete|Lösch-Logbuch]] findest du eine Übersicht der gelöschten und wiederhergestellten Seiten.",
'undelete-header'              => 'Siehe das [[Special:Log/delete|Lösch-Logbuch]] für kürzlich gelöschte Seiten.',
'undelete-search-box'          => 'Suche nach gelöschten Seiten',
'undelete-search-prefix'       => 'Suchbegriff (Wortanfang ohne Wildcards):',
'undelete-search-submit'       => 'Suchen',
'undelete-no-results'          => 'Es wurde im Archiv keine zum Suchbegriff passende Seite gefunden.',
'undelete-filename-mismatch'   => 'Die Dateiversion mit dem Zeitstempel $1 konnte nicht wiederhergestellt werden: Die Dateinamen passen nicht zueinander.',
'undelete-bad-store-key'       => 'Die Dateiversion mit dem Zeitstempel $1 konnte nicht wiederhergestellt werden: Die Datei war bereits vor dem Löschen nicht mehr vorhanden.',
'undelete-cleanup-error'       => 'Fehler beim Löschen der unbenutzten Archiv-Version $1.',
'undelete-missing-filearchive' => 'Die Datei mit der Archiv-ID $1 kann nicht wiederhergestellt werden, da sie nicht in der Datenbank vorhanden ist. Möglicherweise wurde sie bereits wiederhergestellt.',
'undelete-error-short'         => 'Fehler beim Wiederherstellen der Datei $1',
'undelete-error-long'          => 'Es wurden Fehler beim Wiederherstellen einer Datei festgestellt:

$1',
'undelete-show-file-confirm'   => 'Bist du sicher, dass du eine gelöschte Version der Datei „<nowiki>$1</nowiki>“ vom $2, $3 Uhr sehen willst?',
'undelete-show-file-submit'    => 'Ja',

# Namespace form on various pages
'namespace'      => 'Namensraum:',
'invert'         => 'Auswahl umkehren',
'blanknamespace' => '(Seiten)',

# Contributions
'contributions'       => 'Benutzerbeiträge',
'contributions-title' => 'Benutzerbeiträge von „$1“',
'mycontris'           => 'Eigene Beiträge',
'contribsub2'         => 'Von $1 ($2)',
'nocontribs'          => 'Es wurden keine Benutzerbeiträge mit diesen Kriterien gefunden.',
'uctop'               => '(aktuell)',
'month'               => 'und Monat:',
'year'                => 'bis Jahr:',

'sp-contributions-newbies'             => 'Zeige nur Beiträge neuer Benutzer',
'sp-contributions-newbies-sub'         => 'Für Neulinge',
'sp-contributions-newbies-title'       => 'Benutzerbeiträge von neuen Benutzern',
'sp-contributions-blocklog'            => 'Sperr-Logbuch',
'sp-contributions-deleted'             => 'Gelöschte Beiträge',
'sp-contributions-uploads'             => 'Hochgeladene Dateien',
'sp-contributions-logs'                => 'Logbücher',
'sp-contributions-talk'                => 'Diskussion',
'sp-contributions-userrights'          => 'Benutzerrechteverwaltung',
'sp-contributions-blocked-notice'      => '{{GENDER:$1|Dieser Benutzer|Diese Benutzerin|Dieser Benutzer}} ist derzeit gesperrt. Es folgt der aktuelle Eintrag aus dem Benutzersperr-Logbuch:',
'sp-contributions-blocked-notice-anon' => 'Diese IP-Adresse ist zur Zeit gesperrt.
Zur Information folgt der aktuelle Auszug aus dem Sperr-Logbuch:',
'sp-contributions-search'              => 'Suche nach Benutzerbeiträgen',
'sp-contributions-username'            => 'IP-Adresse oder Benutzername:',
'sp-contributions-toponly'             => 'Nur aktuelle Versionen zeigen',
'sp-contributions-submit'              => 'Suchen',

# What links here
'whatlinkshere'            => 'Links auf diese Seite',
'whatlinkshere-title'      => 'Seiten, die auf „$1“ verlinken',
'whatlinkshere-page'       => 'Seite:',
'linkshere'                => "Die folgenden Seiten verlinken auf '''„[[:$1]]“''':",
'nolinkshere'              => "Keine Seite verlinkt auf '''„[[:$1]]“'''.",
'nolinkshere-ns'           => "Keine Seite verlinkt auf '''„[[:$1]]“''' im gewählten Namensraum.",
'isredirect'               => 'Weiterleitungsseite',
'istemplate'               => 'Vorlageneinbindung',
'isimage'                  => 'Dateilink',
'whatlinkshere-prev'       => '{{PLURAL:$1|vorheriger|vorherige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nächster|nächste $1}}',
'whatlinkshere-links'      => '← Links',
'whatlinkshere-hideredirs' => 'Weiterleitungen $1',
'whatlinkshere-hidetrans'  => 'Vorlageneinbindungen $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-hideimages' => 'Dateilinks $1',
'whatlinkshere-filters'    => 'Filter',

# Block/unblock
'blockip'                         => 'IP-Adresse/Benutzer sperren',
'blockip-title'                   => 'Benutzer sperren',
'blockip-legend'                  => 'IP-Adresse/Benutzer sperren',
'blockiptext'                     => 'Mit diesem Formular sperrst du eine IP-Adresse oder einen Benutzernamen, so dass von dort keine Änderungen mehr vorgenommen werden können.
Dies sollte nur erfolgen, um Vandalismus zu verhindern und in Übereinstimmung mit den [[{{MediaWiki:Policy-url}}|Richtlinien]].
Bitte gib den Grund für die Sperre an.',
'ipaddress'                       => 'IP-Adresse:',
'ipadressorusername'              => 'IP-Adresse oder Benutzername:',
'ipbexpiry'                       => 'Sperrdauer:',
'ipbreason'                       => 'Grund:',
'ipbreasonotherlist'              => 'Anderer Grund',
'ipbreason-dropdown'              => '* Allgemeine Sperrgründe
** Einfügen falscher Informationen
** Leeren von Seiten
** Massenweises Einfügen externer Links
** Einstellen unsinniger Inhalte in Seiten
** Ungebührliches Verhalten
** Missbrauch mit mehreren Benutzerkonten
** Ungeeigneter Benutzername',
'ipbanononly'                     => 'Nur unangemeldete Benutzer sperren',
'ipbcreateaccount'                => 'Erstellung von Benutzerkonten verhindern',
'ipbemailban'                     => 'E-Mail-Versand sperren',
'ipbenableautoblock'              => 'Sperre die aktuell von diesem Benutzer genutzte IP-Adresse sowie automatisch alle folgenden, von denen aus er Bearbeitungen oder das Anlegen von Benutzerkonten versucht',
'ipbsubmit'                       => 'IP-Adresse/Benutzer sperren',
'ipbother'                        => 'Andere Dauer (englisch):',
'ipboptions'                      => '2 Stunden:2 hours,1 Tag:1 day,3 Tage:3 days,1 Woche:1 week,2 Wochen:2 weeks,1 Monat:1 month,3 Monate:3 months,6 Monate:6 months,1 Jahr:1 year,Unbeschränkt:infinite',
'ipbotheroption'                  => 'Andere Dauer',
'ipbotherreason'                  => 'Anderer/ergänzender Grund:',
'ipbhidename'                     => 'Benutzername in Bearbeitungen und Listen verstecken',
'ipbwatchuser'                    => 'Benutzer(diskussions)seite beobachten',
'ipballowusertalk'                => 'Benutzer darf eigene Diskussionsseiten während seiner Sperre bearbeiten',
'ipb-change-block'                => 'Sperre mit diesen Sperrparametern erneuern',
'badipaddress'                    => 'Die IP-Adresse hat ein falsches Format.',
'blockipsuccesssub'               => 'Sperre erfolgreich',
'blockipsuccesstext'              => 'Der Benutzer/die IP-Adresse [[Special:Contributions/$1|$1]] wurde gesperrt.<br />
Zur Aufhebung der Sperre siehe die [[Special:IPBlockList|Liste aller aktiven Sperren]].',
'ipb-edit-dropdown'               => 'Sperrgründe bearbeiten',
'ipb-unblock-addr'                => '„$1“ freigeben',
'ipb-unblock'                     => 'IP-Adresse/Benutzer freigeben',
'ipb-blocklist'                   => 'Alle aktuellen Sperren anzeigen',
'ipb-blocklist-contribs'          => 'Benutzerbeiträge von „$1“',
'unblockip'                       => 'IP-Adresse freigeben',
'unblockiptext'                   => 'Mit diesem Formular kannst du eine IP-Adresse oder einen Benutzer freigeben.',
'ipusubmit'                       => 'Freigeben',
'unblocked'                       => '[[User:$1|$1]] wurde freigegeben',
'unblocked-id'                    => 'Sperr-ID $1 wurde freigegeben',
'ipblocklist'                     => 'Gesperrte IP-Adressen und Benutzernamen',
'ipblocklist-legend'              => 'Suche nach einem gesperrten Benutzer',
'ipblocklist-username'            => 'Benutzername oder IP-Adresse:',
'ipblocklist-sh-userblocks'       => 'Benutzersperren $1',
'ipblocklist-sh-tempblocks'       => 'Befristete Sperren $1',
'ipblocklist-sh-addressblocks'    => 'IP-Sperren $1',
'ipblocklist-submit'              => 'Suchen',
'ipblocklist-localblock'          => 'Lokale Sperre',
'ipblocklist-otherblocks'         => 'Andere {{PLURAL:$1|Sperre|Sperren}}',
'blocklistline'                   => '$1, $2 sperrte $3 ($4)',
'infiniteblock'                   => 'unbegrenzt',
'expiringblock'                   => 'endet am $1 um $2 Uhr',
'anononlyblock'                   => 'nur Anonyme',
'noautoblockblock'                => 'Autoblock deaktiviert',
'createaccountblock'              => 'Erstellung von Benutzerkonten gesperrt',
'emailblock'                      => 'E-Mail-Versand gesperrt',
'blocklist-nousertalk'            => 'darf eigene Diskussionsseite nicht bearbeiten',
'ipblocklist-empty'               => 'Die Liste enthält keine Einträge.',
'ipblocklist-no-results'          => 'Die gesuchte IP-Adresse/der Benutzername ist nicht gesperrt.',
'blocklink'                       => 'Sperren',
'unblocklink'                     => 'Freigeben',
'change-blocklink'                => 'Sperre ändern',
'contribslink'                    => 'Beiträge',
'autoblocker'                     => 'Automatische Sperre, da du eine gemeinsame IP-Adresse mit [[User:$1|$1]] benutzt. Grund der Benutzersperre: „$2“.',
'blocklogpage'                    => 'Benutzersperr-Logbuch',
'blocklog-showlog'                => '{{GENDER:$1|Dieser Benutzer|Diese Benutzerin|Dieser Benutzer}} wurde schon früher gesperrt. Es folgt der Eintrag aus dem Benutzersperr-Logbuch:',
'blocklog-showsuppresslog'        => '{{GENDER:$1|Dieser Benutzer|Diese Benutzerin|Dieser Benutzer}} wurde schon früher gesperrt und versteckt.
Es folgt der Eintrag aus dem Unterdrückungs-Logbuch:',
'blocklogentry'                   => 'sperrte „[[$1]]“ für den Zeitraum: $2 $3',
'reblock-logentry'                => 'änderte die Sperre von „[[$1]]“ für den Zeitraum: $2 $3',
'blocklogtext'                    => 'Dies ist das Logbuch über Sperrungen und Entsperrungen von Benutzern und IP-Adressen.
Automatisch gesperrte IP-Adressen werden nicht erfasst.
Siehe die [[Special:IPBlockList|Liste der gesperrten IP-Adressen und Benutzernamen]] für alle aktiven Sperren.',
'unblocklogentry'                 => 'hob die Sperre von „$1“ auf',
'block-log-flags-anononly'        => 'nur Anonyme',
'block-log-flags-nocreate'        => 'Erstellung von Benutzerkonten gesperrt',
'block-log-flags-noautoblock'     => 'Autoblock deaktiviert',
'block-log-flags-noemail'         => 'E-Mail-Versand gesperrt',
'block-log-flags-nousertalk'      => 'darf eigene Diskussionsseite nicht bearbeiten',
'block-log-flags-angry-autoblock' => 'erweiterter Autoblock aktiviert',
'block-log-flags-hiddenname'      => 'Benutzername versteckt',
'range_block_disabled'            => 'Die Möglichkeit, ganze Adressräume zu sperren, ist nicht aktiviert.',
'ipb_expiry_invalid'              => 'Die eingegebene Dauer ist ungültig.',
'ipb_expiry_temp'                 => 'Benutzernamens-Sperren mit der Verstecken-Option müssen permanent sein.',
'ipb_hide_invalid'                => 'Dieses Konto kann nicht unterdrückt werden, da es zu viele Bearbeitungen aufweist.',
'ipb_already_blocked'             => '„$1“ wurde bereits gesperrt.',
'ipb-needreblock'                 => '== Sperre vorhanden ==
„$1“ ist bereits gesperrt. Möchtest du die Sperrparameter ändern?',
'ipb-otherblocks-header'          => 'Andere {{PLURAL:$1|Sperre|Sperren}}',
'ipb_cant_unblock'                => 'Fehler: Sperr-ID $1 nicht gefunden. Die Sperre wurde bereits aufgehoben.',
'ipb_blocked_as_range'            => 'Fehler: Die IP-Adresse $1 wurde als Teil der Bereichssperre $2 indirekt gesperrt. Eine Entsperrung von $1 alleine ist nicht möglich.',
'ip_range_invalid'                => 'Ungültiger IP-Adressbereich.',
'ip_range_toolarge'               => 'Adressbereiche, die größer als /$1 sind, sind nicht erlaubt.',
'blockme'                         => 'Sperre mich',
'proxyblocker'                    => 'Proxy blocker',
'proxyblocker-disabled'           => 'Diese Funktion ist deaktiviert.',
'proxyblockreason'                => 'Deine IP-Adresse wurde gesperrt, da sie ein offener Proxy ist. Bitte kontaktiere deinen Internet-Provider oder deine Systemadministratoren und informiere sie über dieses mögliche Sicherheitsproblem.',
'proxyblocksuccess'               => 'Fertig.',
'sorbsreason'                     => 'Die IP-Adresse ist in der DNSBL von {{SITENAME}} als offener PROXY gelistet.',
'sorbs_create_account_reason'     => 'Die IP-Adresse ist in der DNSBL von {{SITENAME}} als offener PROXY gelistet. Das Anlegen neuer Benutzer ist nicht möglich.',
'cant-block-while-blocked'        => 'Du kannst keine anderen Benutzer sperren, während du selbst gesperrt bist.',
'cant-see-hidden-user'            => 'Der Benutzer, den du versuchst zu sperren, wurde bereits gesperrt und verborgen. Da du das „hideuser“-Recht nicht hast, kannst du die Benutzersperre nicht sehen und nicht bearbeiten.',
'ipbblocked'                      => 'Du kannst keine anderen Benutzer sperren oder entsperren, da du selbst gesperrt bist',
'ipbnounblockself'                => 'Du hast nicht die Berechtigung, dich selbst zu entsperren',

# Developer tools
'lockdb'              => 'Datenbank sperren',
'unlockdb'            => 'Datenbank freigeben',
'lockdbtext'          => 'Mit dem Sperren der Datenbank werden alle Änderungen an Benutzereinstellungen, Beobachtungslisten, Seiten usw. verhindert. Bitte die Sperrung bestätigen.',
'unlockdbtext'        => 'Das Aufheben der Datenbank-Sperre wird alle Änderungen wieder zulassen. Bitte die Aufhebung bestätigen.',
'lockconfirm'         => 'Ja, ich möchte die Datenbank sperren.',
'unlockconfirm'       => 'Ja, ich möchte die Datenbank freigeben.',
'lockbtn'             => 'Datenbank sperren',
'unlockbtn'           => 'Datenbank freigeben',
'locknoconfirm'       => 'Du hast das Bestätigungsfeld nicht markiert.',
'lockdbsuccesssub'    => 'Datenbank wurde erfolgreich gesperrt',
'unlockdbsuccesssub'  => 'Datenbank wurde erfolgreich freigegeben',
'lockdbsuccesstext'   => 'Die {{SITENAME}}-Datenbank wurde gesperrt.<br />Bitte gib die Datenbank [[Special:UnlockDB|wieder frei]], sobald die Wartung abgeschlossen ist.',
'unlockdbsuccesstext' => 'Die {{SITENAME}}-Datenbank wurde freigegeben.',
'lockfilenotwritable' => 'Die Datenbank-Sperrdatei ist nicht beschreibbar. Zum Sperren oder Freigeben der Datenbank muss diese für den Webserver beschreibbar sein.',
'databasenotlocked'   => 'Die Datenbank ist nicht gesperrt.',

# Move page
'move-page'                    => 'Verschieben von „$1“',
'move-page-legend'             => 'Seite verschieben',
'movepagetext'                 => "Mit untenstehendem Formular kannst du eine Seite umbenennen, indem du sie mitsamt allen Versionen auf einen neuen Titel verschiebst.
Der alte Titel wird danach zum neuen weiterleiten.
Du kannst Weiterleitungen, die auf den Originaltitel verlinken, automatisch korrigieren lassen.
Stelle sicher, dass du im Anschluss alle [[Special:DoubleRedirects|doppelten]] oder [[Special:BrokenRedirects|kaputten Weiterleitungen]] überprüfst.
Du bist dafür verantwortlich, dass Links weiterhin auf das korrekte Ziel verweisen.

Die Seite wird '''nicht''' verschoben, sofern es bereits eine Seite mit dem vorgesehenen Titel gibt, es sei denn, diese ist leer oder eine Weiterleitung ohne Versionsgeschichte.
Dies bedeutet, dass du die Umbenennung rückgängig machen kannst, sofern du einen Fehler gemacht hast. Du kannst hingegen keine Seite überschreiben.

'''Warnung!'''
Die Verschiebung kann weitreichende und unerwartete Folgen für häufig besuchte Seiten haben.
Du solltest daher die Konsequenzen verstanden haben, bevor du jetzt fortfährst.",
'movepagetext-noredirectfixer' => "Mit untenstehendem Formular kannst du eine Seite umbenennen, indem du sie mitsamt allen Versionen auf einen neuen Titel verschiebst.
Der alte Titel wird danach zum neuen weiterleiten.
Stelle sicher, dass du im Anschluss alle [[Special:DoubleRedirects|doppelten]] oder [[Special:BrokenRedirects|kaputten Weiterleitungen]] überprüfst.
Du bist dafür verantwortlich, dass Links weiterhin auf das korrekte Ziel verweisen.

Die Seite wird '''nicht''' verschoben, sofern es bereits eine Seite mit dem vorgesehenen Titel gibt, es sei denn, diese ist leer oder eine Weiterleitung ohne Versionsgeschichte.
Dies bedeutet, dass du die Umbenennung rückgängig machen kannst, sofern du einen Fehler gemacht hast. Du kannst hingegen keine Seite überschreiben.

'''Warnung!'''
Die Verschiebung kann weitreichende und unerwartete Folgen für häufig besuchte Seiten haben.
Du solltest daher die Konsequenzen verstanden haben, bevor du jetzt fortfährst.",
'movepagetalktext'             => "Die dazugehörige Diskussionsseite wird, sofern vorhanden, mitverschoben, '''es sei denn:'''
*Es existiert bereits eine Diskussionsseite mit diesem Namen, oder
*du wählst die unten stehende Option ab.

In diesen Fällen musst du, falls gewünscht, den Inhalt der Seite von Hand verschieben oder zusammenführen.

Bitte den '''neuen''' Titel unter '''Ziel''' eintragen, darunter die Umbenennung bitte '''begründen.'''",
'movearticle'                  => 'Seite verschieben:',
'moveuserpage-warning'         => "'''Warnung:''' Du bist dabei, eine Benutzerseite zu verschieben. Bitte bedenke, dass dadurch nur die Benutzerseite verschoben, '''nicht''' aber der Benutzer umbenannt wird.",
'movenologin'                  => 'Du bist nicht angemeldet',
'movenologintext'              => 'Du musst ein registrierter Benutzer und [[Special:UserLogin|angemeldet]] sein, um eine Seite zu verschieben.',
'movenotallowed'               => 'Du hast keine Berechtigung, Seiten zu verschieben.',
'movenotallowedfile'           => 'Du hast keine Berechtigung, Dateien zu verschieben.',
'cant-move-user-page'          => 'Du hast keine Berechtigung, Benutzerhauptseiten zu verschieben.',
'cant-move-to-user-page'       => 'Du hast nicht die Berechtigung, Seiten auf eine Benutzerseite zu verschieben (mit Ausnahme von Benutzerunterseiten).',
'newtitle'                     => 'Ziel:',
'move-watch'                   => 'Quell- und Zielseite beobachten',
'movepagebtn'                  => 'Seite verschieben',
'pagemovedsub'                 => 'Verschiebung erfolgreich',
'movepage-moved'               => "'''Die Seite „$1“ wurde nach „$2“ verschoben.'''",
'movepage-moved-redirect'      => 'Es wurde eine Weiterleitung erstellt.',
'movepage-moved-noredirect'    => 'Die Erstellung einer Weiterleitung wurde unterdrückt.',
'articleexists'                => 'Unter diesem Namen existiert bereits eine Seite. Bitte wähle einen anderen Namen.',
'cantmove-titleprotected'      => 'Die Verschiebung kann nicht durchgeführt werden, da der Zieltitel zur Erstellung gesperrt ist.',
'talkexists'                   => 'Die Seite selbst wurde erfolgreich verschoben, aber die zugehörige Diskussionsseite nicht, da bereits eine mit dem neuen Titel existiert. Bitte gleiche die Inhalte von Hand ab.',
'movedto'                      => 'verschoben nach',
'movetalk'                     => 'Die Diskussionsseite mitverschieben, wenn möglich',
'move-subpages'                => 'Unterseiten verschieben (bis zu $1)',
'move-talk-subpages'           => 'Unterseiten der Diskussionsseite verschieben (bis zu $1)',
'movepage-page-exists'         => 'Die Seite „$1“ ist bereits vorhanden und kann nicht automatisch überschrieben werden.',
'movepage-page-moved'          => 'Die Seite „$1“ wurde nach „$2“ verschoben.',
'movepage-page-unmoved'        => 'Die Seite „$1“ konnte nicht nach „$2“ verschoben werden.',
'movepage-max-pages'           => 'Die Maximalanzahl von $1 {{PLURAL:$1|Seite|Seiten}} wurde verschoben, Alle weiteren Seiten können nicht automatisch verschoben werden.',
'1movedto2'                    => 'hat „[[$1]]“ nach „[[$2]]“ verschoben',
'1movedto2_redir'              => 'hat „[[$1]]“ nach „[[$2]]“ verschoben und dabei eine Weiterleitung überschrieben',
'move-redirect-suppressed'     => 'Weiterleitung unterdrückt',
'movelogpage'                  => 'Verschiebungs-Logbuch',
'movelogpagetext'              => 'Dies ist eine Liste aller verschobenen Seiten.',
'movesubpage'                  => '{{PLURAL:$1|Unterseite|Unterseiten}}',
'movesubpagetext'              => 'Diese Seite hat $1 {{PLURAL:$1|Unterseite|Unterseiten}}.',
'movenosubpage'                => 'Diese Seite hat keine Unterseiten.',
'movereason'                   => 'Grund:',
'revertmove'                   => 'zurück verschieben',
'delete_and_move'              => 'Löschen und Verschieben',
'delete_and_move_text'         => '== Löschung erforderlich ==

Die Seite „[[:$1]]“ existiert bereits. Möchtest du diese löschen, um die Seite verschieben zu können?',
'delete_and_move_confirm'      => 'Ja, Seite löschen',
'delete_and_move_reason'       => 'gelöscht, um Platz für Verschiebung zu machen',
'selfmove'                     => 'Ursprungs- und Zielname sind gleich; eine Seite kann nicht auf sich selbst verschoben werden.',
'immobile-source-namespace'    => 'Seiten des „$1“-Namensraums können nicht verschoben werden',
'immobile-target-namespace'    => 'Seiten können nicht in den „$1“-Namensraum verschoben werden',
'immobile-target-namespace-iw' => 'Interwiki-Link ist kein gültiges Ziel für Seitenverschiebungen.',
'immobile-source-page'         => 'Diese Seite ist nicht verschiebbar.',
'immobile-target-page'         => 'Es kann nicht auf diese Zielseite verschoben werden.',
'imagenocrossnamespace'        => 'Dateien können nicht aus dem {{ns:file}}-Namensraum heraus verschoben werden',
'nonfile-cannot-move-to-file'  => 'Nichtdateien können nicht in den {{ns:file}}-Namensraum hinein verschoben werden',
'imagetypemismatch'            => 'Die neue Dateierweiterung ist nicht mit der alten identisch',
'imageinvalidfilename'         => 'Der Ziel-Dateiname ist ungültig',
'fix-double-redirects'         => 'Nach dem Verschieben alle Weiterleitungen auf die Ursprungsseite bereinigen',
'move-leave-redirect'          => 'Weiterleitung erstellen',
'protectedpagemovewarning'     => "'''Warnung:''' Diese Seite wurde so geschützt, dass sie nur von Benutzern mit Administratorenrechten verschoben werden kann.
Zur Information folgt der aktuelle Logbucheintrag:",
'semiprotectedpagemovewarning' => "'''Hinweis:''' Diese Seite wurde so geschützt, dass sie nur von angemeldeten Benutzern verschoben werden kann.
Zur Information folgt der aktuelle Logbucheintrag:",
'move-over-sharedrepo'         => '==Datei existiert==
[[:$1]] existiert in einem gemeinsam genutzten Repositorium. Das Verschieben einer Datei zu diesem Titel überschreibt die gemeinsam genutzte Datei.',
'file-exists-sharedrepo'       => 'Der gewählte Dateiname wird bereits in einem gemeinsam genutzten Repositorium verwendet.
Bitte wähle einen anderen Namen.',

# Export
'export'            => 'Seiten exportieren',
'exporttext'        => 'Mit dieser Spezialseite kannst du den Text inklusive der Versionsgeschichte einzelner Seiten in eine XML-Datei exportieren.
Die Datei kann in ein anderes MediaWiki-Wiki über die [[Special:Import|Importfunktion]] eingespielt werden.

Trage den oder die entsprechenden Seitentitel in das folgende Textfeld ein (pro Zeile jeweils nur eine Seite).

Alternativ ist der Export auch mit der Syntax [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] möglich, beispielsweise für die [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Nur die aktuelle Version der Seite exportieren',
'exportnohistory'   => "----
'''Hinweis:''' Der Export kompletter Versionsgeschichten ist aus Performancegründen bis auf weiteres nicht möglich.",
'export-submit'     => 'Seiten exportieren',
'export-addcattext' => 'Seiten aus Kategorie hinzufügen:',
'export-addcat'     => 'Hinzufügen',
'export-addnstext'  => 'Seiten aus Namensraum hinzufügen:',
'export-addns'      => 'Hinzufügen',
'export-download'   => 'Als XML-Datei speichern',
'export-templates'  => 'Inklusive Vorlagen',
'export-pagelinks'  => 'Verlinkte Seiten automatisch mit exportieren, bis zur Rekursionstiefe von:',

# Namespace 8 related
'allmessages'                   => 'MediaWiki-Systemtexte',
'allmessagesname'               => 'Name',
'allmessagesdefault'            => 'Standardtext',
'allmessagescurrent'            => 'Aktueller Text',
'allmessagestext'               => 'Dies ist eine Liste der MediaWiki-Systemtexte.
Besuche die Seiten [http://www.mediawiki.org/wiki/Localisation MediaWiki-Lokalisierung] und [http://translatewiki.net translatewiki.net], wenn du dich an der Lokalisierung von MediaWiki beteiligen möchtest.',
'allmessagesnotsupportedDB'     => 'Diese Spezialseite steht nicht zur Verfügung, da sie über den Parameter <tt>$wgUseDatabaseMessages</tt> deaktiviert wurde.',
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Filter für angepassten Zustand:',
'allmessages-filter-unmodified' => 'Unverändert',
'allmessages-filter-all'        => 'Alle',
'allmessages-filter-modified'   => 'Verändert',
'allmessages-prefix'            => 'Präfixfilter:',
'allmessages-language'          => 'Sprache:',
'allmessages-filter-submit'     => 'Los',

# Thumbnails
'thumbnail-more'           => 'vergrößern',
'filemissing'              => 'Datei fehlt',
'thumbnail_error'          => 'Fehler beim Erstellen des Vorschaubildes: $1',
'djvu_page_error'          => 'DjVu-Seite außerhalb des Seitenbereichs',
'djvu_no_xml'              => 'XML-Daten können für die DjVu-Datei nicht abgerufen werden',
'thumbnail_invalid_params' => 'Ungültige Thumbnail-Parameter',
'thumbnail_dest_directory' => 'Zielverzeichnis kann nicht erstellt werden.',
'thumbnail_image-type'     => 'Bildtyp nicht unterstützt',
'thumbnail_gd-library'     => 'Unvollständige Konfiguration der GD-Bibliothek: Fehlende Funktion $1',
'thumbnail_image-missing'  => 'Datei scheint fehlend zu sein: $1',

# Special:Import
'import'                     => 'Seiten importieren',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-text'      => 'Wähle ein Wiki und eine Seite zum Importieren aus.
Die Versionsdaten und Benutzernamen bleiben dabei erhalten.
Alle Transwiki-Import-Aktionen werden im [[Special:Log/import|Import-Logbuch]] protokolliert.',
'import-interwiki-source'    => 'Quell-Wiki/-Seite:',
'import-interwiki-history'   => 'Alle Versionen dieser Seite importieren',
'import-interwiki-templates' => 'Alle Vorlagen einschließen',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Zielnamensraum:',
'import-upload-filename'     => 'Dateiname:',
'import-comment'             => 'Grund:',
'importtext'                 => 'Auf dieser Spezialseite können über die [[Special:Export|Exportfunktion]] im Quellwiki exportierte Seiten in dieses Wiki importiert werden.',
'importstart'                => 'Importiere Seite …',
'import-revision-count'      => '– {{PLURAL:$1|1 Version|$1 Versionen}}',
'importnopages'              => 'Keine Seite zum Importieren vorhanden.',
'imported-log-entries'       => '$1 {{PLURAL:$1|lLogbucheintrag|Logbucheinträge}} importiert.',
'importfailed'               => 'Import fehlgeschlagen: $1',
'importunknownsource'        => 'Unbekannte Importquelle',
'importcantopen'             => 'Importdatei konnte nicht geöffnet werden',
'importbadinterwiki'         => 'Falscher Interwiki-Link',
'importnotext'               => 'Leer oder kein Text',
'importsuccess'              => 'Import abgeschlossen.',
'importhistoryconflict'      => 'Es existieren bereits ältere Versionen, welche mit diesen kollidieren. Möglicherweise wurde die Seite bereits vorher importiert.',
'importnosources'            => 'Für den Transwiki-Import sind keine Quellen definiert. Das direkte Hochladen von Versionen ist gesperrt.',
'importnofile'               => 'Es ist keine Importdatei ausgewählt worden.',
'importuploaderrorsize'      => 'Das Hochladen der Importdatei ist fehlgeschlagen. Die Datei ist größer als die maximal erlaubte Dateigröße.',
'importuploaderrorpartial'   => 'Das Hochladen der Importdatei ist fehlgeschlagen. Die Datei wurde nur teilweise hochgeladen.',
'importuploaderrortemp'      => 'Das Hochladen der Importdatei ist fehlgeschlagen. Ein temporäres Verzeichnis fehlt.',
'import-parse-failure'       => 'Fehler beim XML-Import:',
'import-noarticle'           => 'Es wurde keine zu importierende Seite angegeben!',
'import-nonewrevisions'      => 'Es sind keine neuen Versionen zum Import vorhanden, alle Versionen wurden bereits früher importiert.',
'xml-error-string'           => '$1 Zeile $2, Spalte $3, (Byte $4): $5',
'import-upload'              => 'XML-Daten importieren',
'import-token-mismatch'      => 'Verlust der Sessiondaten. Bitte versuche es erneut.',
'import-invalid-interwiki'   => 'Aus dem angegebenen Wiki ist kein Import möglich.',

# Import log
'importlogpage'                    => 'Import-Logbuch',
'importlogpagetext'                => 'Administrativer Import von Seiten mit Versionsgeschichte von anderen Wikis.',
'import-logentry-upload'           => 'hat „[[$1]]“ von einer Datei importiert',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Version|Versionen}}',
'import-logentry-interwiki'        => 'hat „$1“ importiert (Transwiki)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Version|Versionen}} von $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Deine Benutzerseite',
'tooltip-pt-anonuserpage'         => 'Benutzerseite der IP-Adresse von der aus du Änderungen durchführst',
'tooltip-pt-mytalk'               => 'Deine Diskussionsseite',
'tooltip-pt-anontalk'             => 'Diskussion über Änderungen von dieser IP-Adresse',
'tooltip-pt-preferences'          => 'Eigene Einstellungen',
'tooltip-pt-watchlist'            => 'Liste der beobachteten Seiten',
'tooltip-pt-mycontris'            => 'Liste deiner Beiträge',
'tooltip-pt-login'                => 'Sich anzumelden wird zwar gerne gesehen, ist aber keine Pflicht.',
'tooltip-pt-anonlogin'            => 'Sich anzumelden wird zwar gerne gesehen, ist aber keine Pflicht.',
'tooltip-pt-logout'               => 'Abmelden',
'tooltip-ca-talk'                 => 'Diskussion zum Seiteninhalt',
'tooltip-ca-edit'                 => 'Seite bearbeiten. Bitte vor dem Speichern die Vorschaufunktion benutzen.',
'tooltip-ca-addsection'           => 'Neuen Abschnitt beginnen',
'tooltip-ca-viewsource'           => 'Diese Seite ist geschützt. Der Quelltext kann angesehen werden.',
'tooltip-ca-history'              => 'Frühere Versionen dieser Seite',
'tooltip-ca-protect'              => 'Diese Seite schützen',
'tooltip-ca-unprotect'            => 'Diese Seite freigeben',
'tooltip-ca-delete'               => 'Diese Seite löschen',
'tooltip-ca-undelete'             => 'Einträge wiederherstellen, bevor diese Seite gelöscht wurde',
'tooltip-ca-move'                 => 'Diese Seite verschieben',
'tooltip-ca-watch'                => 'Diese Seite zur persönlichen Beobachtungsliste hinzufügen',
'tooltip-ca-unwatch'              => 'Diese Seite von der persönlichen Beobachtungsliste entfernen',
'tooltip-search'                  => '{{SITENAME}} durchsuchen',
'tooltip-search-go'               => 'Gehe direkt zu der Seite, die exakt dem eingegebenen Namen entspricht.',
'tooltip-search-fulltext'         => 'Suche nach Seiten, die diesen Text enthalten',
'tooltip-p-logo'                  => 'Hauptseite',
'tooltip-n-mainpage'              => 'Hauptseite anzeigen',
'tooltip-n-mainpage-description'  => 'Hauptseite besuchen',
'tooltip-n-portal'                => 'Über das Projekt, was du tun kannst, wo was zu finden ist',
'tooltip-n-currentevents'         => 'Hintergrundinformationen zu aktuellen Ereignissen',
'tooltip-n-recentchanges'         => 'Liste der letzten Änderungen in {{SITENAME}}',
'tooltip-n-randompage'            => 'Zufällige Seite',
'tooltip-n-help'                  => 'Hilfeseite anzeigen',
'tooltip-t-whatlinkshere'         => 'Liste aller Seiten, die hierher verlinken',
'tooltip-t-recentchangeslinked'   => 'Letzte Änderungen an Seiten, die von hier verlinkt sind',
'tooltip-feed-rss'                => 'RSS-Feed dieser Seite',
'tooltip-feed-atom'               => 'Atom-Feed dieser Seite',
'tooltip-t-contributions'         => 'Liste der Beiträge von diesem Benutzer ansehen',
'tooltip-t-emailuser'             => 'Eine E-Mail an diesen Benutzer senden',
'tooltip-t-upload'                => 'Dateien hochladen',
'tooltip-t-specialpages'          => 'Liste aller Spezialseiten',
'tooltip-t-print'                 => 'Druckansicht dieser Seite',
'tooltip-t-permalink'             => 'Dauerhafter Link zu dieser Seitenversion',
'tooltip-ca-nstab-main'           => 'Seiteninhalt anzeigen',
'tooltip-ca-nstab-user'           => 'Benutzerseite anzeigen',
'tooltip-ca-nstab-media'          => 'Mediendateienseite anzeigen',
'tooltip-ca-nstab-special'        => 'Dies ist eine Spezialseite. Sie kann nicht bearbeitet werden.',
'tooltip-ca-nstab-project'        => 'Portalseite anzeigen',
'tooltip-ca-nstab-image'          => 'Dateiseite anzeigen',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki-Systemtext anzeigen',
'tooltip-ca-nstab-template'       => 'Vorlage anzeigen',
'tooltip-ca-nstab-help'           => 'Hilfeseite anzeigen',
'tooltip-ca-nstab-category'       => 'Kategorieseite anzeigen',
'tooltip-minoredit'               => 'Diese Änderung als klein markieren.',
'tooltip-save'                    => 'Änderungen speichern',
'tooltip-preview'                 => 'Vorschau der Änderungen an dieser Seite. Bitte vor dem Speichern benutzen!',
'tooltip-diff'                    => 'Änderungen am Text zeigen',
'tooltip-compareselectedversions' => 'Unterschied zwischen zwei ausgewählten Versionen dieser Seite anzeigen',
'tooltip-watch'                   => 'Füge diese Seite deiner Beobachtungsliste hinzu',
'tooltip-recreate'                => 'Seite neu erstellen, obwohl sie gelöscht wurde',
'tooltip-upload'                  => 'Hochladen starten',
'tooltip-rollback'                => 'Macht alle letzten Änderungen der Seite, die vom gleichen Benutzer vorgenommen worden sind, durch nur einen Klick rückgängig.',
'tooltip-undo'                    => 'Macht lediglich diese eine Änderung rückgängig und zeigt das Resultat in der Vorschau an, damit in der Zusammenfassungszeile eine Begründung angegeben werden kann.',
'tooltip-preferences-save'        => 'Einstellungen speichern',
'tooltip-summary'                 => 'Gib eine kurze Zusammenfassung ein',

# Stylesheets
'common.css'      => '/* Das folgende CSS wird für alle Benutzeroberflächen geladen. */',
'standard.css'    => '/* Das folgende CSS wird für Benutzer der Klassik-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'nostalgia.css'   => '/* Das folgende CSS wird für Benutzer der Nostalgie-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'cologneblue.css' => '/* Das folgende CSS wird für Benutzer der Kölnisch-Blau-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'monobook.css'    => '/* Das folgende CSS wird für Benutzer der Monobook-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'myskin.css'      => '/* Das folgende CSS wird für Benutzer der MySkin-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'chick.css'       => '/* Das folgende CSS wird für Benutzer der Küken-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'simple.css'      => '/* Das folgende CSS wird für Benutzer der Einfach-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'modern.css'      => '/* Das folgende CSS wird für Benutzer der Modern-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */

/* Kleinschreibung in Navigationsbereichen verhindern */
.portlet h5,
.portlet h6,
#p-personal ul,
#p-cactions li a,
#preftoc a {
    text-transform: none;
}',
'vector.css'      => '/* Das folgende CSS wird für Benutzer der Vector-Benutzeroberfläche geladen. Für allgemeingültige Benutzeroberflächen-Anpassungen bitte [[MediaWiki:Common.css]] bearbeiten. */',
'print.css'       => '/* Das folgende CSS wird in der Druckausgabe geladen. */',
'handheld.css'    => '/* Das folgende CSS wird für Handheld-Geräte, basierend auf der in $wgHandheldStyle konfigurierten Benutzeroberfläche, geladen. */',

# Scripts
'common.js'      => '/* Das folgende JavaScript wird für alle Benutzer geladen. */',
'standard.js'    => '/* Das folgende JavaScript wird für Benutzer der Klassik-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'nostalgia.js'   => '/* Das folgende JavaScript wird für Benutzer der Nostalgie-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'cologneblue.js' => '/* Das folgende JavaScript wird für Benutzer der Kölnisch-Blau-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'monobook.js'    => '/* Das folgende JavaScript wird für Benutzer der Monobook-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'myskin.js'      => '/* Das folgende JavaScript wird für Benutzer der MySkin-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'chick.js'       => '/* Das folgende JavaScript wird für Benutzer der Küken-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'simple.js'      => '/* Das folgende JavaScript wird für Benutzer der Einfach-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'modern.js'      => '/* Das folgende JavaScript wird für Benutzer der Modern-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',
'vector.js'      => '/* Das folgende JavaScript wird für Benutzer der Vector-Benutzeroberfläche geladen. Allgemeingültiges JavaScript bitte in [[MediaWiki:Common.js]] eintragen. */',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadaten sind auf diesem Server deaktiviert.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadaten sind auf diesem Server deaktiviert.',
'notacceptable'     => 'Der Wiki-Server kann die Daten nicht für dein Ausgabegerät aufbereiten.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Unangemeldeter Benutzer|Unangemeldete Benutzer}} auf {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-{{GENDER:$2|Benutzer|Benutzerin|Benutzer}} $1',
'anonuser'         => 'Anonymer {{SITENAME}}-Benutzer $1',
'lastmodifiedatby' => 'Diese Seite wurde zuletzt am $1 um $2 Uhr von $3 geändert.',
'othercontribs'    => 'Basierend auf der Arbeit von $1.',
'others'           => 'anderen',
'siteusers'        => '{{SITENAME}}-{{PLURAL:$2|Benutzer|Benutzer}} $1',
'anonusers'        => '{{PLURAL:$2|unangemeldeter|unangemeldete}} {{SITENAME}}-{{PLURAL:$2|Benutzer|Benutzer}} $1',
'creditspage'      => 'Seiteninformationen',
'nocredits'        => 'Für diese Seite sind keine Informationen vorhanden.',

# Spam protection
'spamprotectiontitle' => 'Spamschutzfilter',
'spamprotectiontext'  => 'Der Text, die du speichern willst, wurde vom Spamschutzfilter blockiert.
Das liegt wahrscheinlich an einem Link auf eine externe Seite.',
'spamprotectionmatch' => "'''Der folgende Text wurde vom Spamfilter gefunden: ''$1'''''",
'spambot_username'    => 'MediaWiki-Spam-Säuberung',
'spam_reverting'      => 'Letzte Version ohne Links zu $1 wiederhergestellt.',
'spam_blanking'       => 'Alle Versionen enthielten Links zu $1, bereinigt.',

# Info page
'infosubtitle'   => 'Seiteninformation',
'numedits'       => 'Anzahl der Seitenänderungen: $1',
'numtalkedits'   => 'Anzahl der Diskussionsänderungen: $1',
'numwatchers'    => 'Anzahl der Beobachter: $1',
'numauthors'     => 'Anzahl der Autoren: $1',
'numtalkauthors' => 'Anzahl der Diskussionsteilnehmer: $1',

# Skin names
'skinname-standard'    => 'Klassik',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Kölnisch Blau',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Küken',
'skinname-simple'      => 'Einfach',
'skinname-modern'      => 'Modern',
'skinname-vector'      => 'Vector',

# Math options
'mw_math_png'    => 'Immer als PNG darstellen',
'mw_math_simple' => 'Einfaches TeX als HTML darstellen, ansonsten als PNG',
'mw_math_html'   => 'Sofern möglich als HTML darstellen, ansonsten als PNG',
'mw_math_source' => 'Als TeX belassen (für Textbrowser)',
'mw_math_modern' => 'Für moderne Browser empfohlene Darstellungsmethode',
'mw_math_mathml' => 'Sofern möglich als MathML darstellen (experimentell)',

# Math errors
'math_failure'          => 'Parser-Fehler',
'math_unknown_error'    => 'Unbekannter Fehler',
'math_unknown_function' => 'Unbekannte Funktion ',
'math_lexing_error'     => '„Lexing“-Fehler',
'math_syntax_error'     => 'Syntaxfehler',
'math_image_error'      => 'PNG-Konvertierung fehlgeschlagen; korrekte Installation von LaTeX und dvipng überprüfen (oder dvips + gs + convert)',
'math_bad_tmpdir'       => 'Das temporäre Verzeichnis für mathematische Formeln kann nicht angelegt oder beschrieben werden.',
'math_bad_output'       => 'Das Zielverzeichnis für mathematische Formeln kann nicht angelegt oder beschrieben werden.',
'math_notexvc'          => 'Das texvc-Programm wurde nicht gefunden. Bitte math/README beachten.',

# Patrolling
'markaspatrolleddiff'                 => 'Als kontrolliert markieren',
'markaspatrolledtext'                 => 'Diese Seite als kontrolliert markieren',
'markedaspatrolled'                   => 'Als kontrolliert markiert',
'markedaspatrolledtext'               => 'Die ausgewählte Version von [[:$1]] wurde als kontrolliert markiert.',
'rcpatroldisabled'                    => 'Kontrolle der letzten Änderungen gesperrt',
'rcpatroldisabledtext'                => 'Die Kontrolle der letzten Änderungen ist zurzeit gesperrt.',
'markedaspatrollederror'              => 'Markierung als „kontrolliert“ nicht möglich.',
'markedaspatrollederrortext'          => 'Du musst eine Seitenänderung auswählen.',
'markedaspatrollederror-noautopatrol' => 'Es ist nicht erlaubt, eigene Bearbeitungen als kontrolliert zu markieren.',

# Patrol log
'patrol-log-page'      => 'Kontroll-Logbuch',
'patrol-log-header'    => 'Dies ist das Kontroll-Logbuch.',
'patrol-log-line'      => 'hat $1 von „$2“ als kontrolliert markiert $3',
'patrol-log-auto'      => '(automatisch)',
'patrol-log-diff'      => 'Version $1',
'log-show-hide-patrol' => 'Kontroll-Logbuch $1',

# Image deletion
'deletedrevision'                 => 'alte Version $1 gelöscht',
'filedeleteerror-short'           => 'Fehler bei Datei-Löschung: $1',
'filedeleteerror-long'            => 'Bei der Datei-Löschung wurden Fehler festgestellt:

$1',
'filedelete-missing'              => 'Die Datei „$1“ kann nicht gelöscht werden, da sie nicht vorhanden ist.',
'filedelete-old-unregistered'     => 'Die angegebene Datei-Version „$1“ ist nicht in der Datenbank vorhanden.',
'filedelete-current-unregistered' => 'Die angegebene Datei „$1“ ist nicht in der Datenbank vorhanden.',
'filedelete-archive-read-only'    => 'Das Archiv-Verzeichnis „$1“ ist für den Webserver nicht beschreibbar.',

# Browsing diffs
'previousdiff' => '← Zum vorherigen Versionsunterschied',
'nextdiff'     => 'Zum nächsten Versionsunterschied →',

# Media information
'mediawarning'         => "'''Warnung:''' Dieser Dateityp kann böswilligen Programmcode enthalten.
Durch das Herunterladen und Öffnen der Datei kann dein Computer beschädigt werden.",
'imagemaxsize'         => "Maximale Bildgröße:<br />''(für Dateibeschreibungsseiten)''",
'thumbsize'            => 'Standardgröße der Vorschaubilder (Thumbnails):',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|1 Seite|$3 Seiten}}',
'file-info'            => 'Dateigröße: $1, MIME-Typ: $2',
'file-info-size'       => '$1 × $2 Pixel, Dateigröße: $3, MIME-Typ: $4',
'file-nohires'         => '<small>Keine höhere Auflösung vorhanden.</small>',
'svg-long-desc'        => 'SVG-Datei, Basisgröße: $1 × $2 Pixel, Dateigröße: $3',
'show-big-image'       => 'Version in höherer Auflösung',
'show-big-image-thumb' => '<small>Größe der Voransicht: $1 × $2 Pixel</small>',
'file-info-gif-looped' => 'Endlosschleife',
'file-info-gif-frames' => '$1 {{PLURAL:$1|Bild|Bilder}}',
'file-info-png-looped' => 'Endlosschleife',
'file-info-png-repeat' => '$1-{{PLURAL:$1|mal|mal}} abgespielt',
'file-info-png-frames' => '$1 {{PLURAL:$1|Bild|Bilder}}',

# Special:NewFiles
'newimages'             => 'Neue Dateien',
'imagelisttext'         => "Hier ist eine Liste von '''$1''' {{PLURAL:$1|Datei|Dateien}}, sortiert $2.",
'newimages-summary'     => 'Diese Spezialseite zeigt die zuletzt hochgeladenen Dateien an.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Dateiname (oder ein Teil davon):',
'showhidebots'          => '(Bots $1)',
'noimages'              => 'Keine Dateien gefunden.',
'ilsubmit'              => 'Suchen',
'bydate'                => 'nach Datum',
'sp-newimages-showfrom' => 'Zeige neue Dateien ab $1, $2 Uhr',

# Bad image list
'bad_image_list' => 'Format:

Nur Zeilen, die mit einem * anfangen, werden ausgewertet. Als erstes nach dem * muss ein Link auf eine unerwünschte Datei stehen.
Darauf folgende Seitenlinks in derselben Zeile definieren Ausnahmen, in deren Kontext die Datei trotzdem erscheinen darf.',

# Metadata
'metadata'          => 'Metadaten',
'metadata-help'     => 'Diese Datei enthält weitere Informationen, die in der Regel von der Digitalkamera oder dem verwendeten Scanner stammen. Durch nachträgliche Bearbeitung der Originaldatei können einige Details verändert worden sein.',
'metadata-expand'   => 'Erweiterte Details einblenden',
'metadata-collapse' => 'Erweiterte Details ausblenden',
'metadata-fields'   => 'Die folgenden Felder der EXIF-Metadaten in diesem MediaWiki-Systemtext werden auf Bildbeschreibungsseiten angezeigt; weitere standardmäßig „eingeklappte“ Details können angezeigt werden.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Breite',
'exif-imagelength'                 => 'Höhe',
'exif-bitspersample'               => 'Bits pro Farbkomponente',
'exif-compression'                 => 'Art der Kompression',
'exif-photometricinterpretation'   => 'Pixelzusammensetzung',
'exif-orientation'                 => 'Kameraausrichtung',
'exif-samplesperpixel'             => 'Anzahl Komponenten',
'exif-planarconfiguration'         => 'Datenausrichtung',
'exif-ycbcrsubsampling'            => 'Subsampling Rate von Y bis C',
'exif-ycbcrpositioning'            => 'Y und C Positionierung',
'exif-xresolution'                 => 'Horizontale Auflösung',
'exif-yresolution'                 => 'Vertikale Auflösung',
'exif-resolutionunit'              => 'Maßeinheit der Auflösung',
'exif-stripoffsets'                => 'Bilddaten-Versatz',
'exif-rowsperstrip'                => 'Anzahl Zeilen pro Streifen',
'exif-stripbytecounts'             => 'Bytes pro komprimiertem Streifen',
'exif-jpeginterchangeformat'       => 'Offset zu JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Größe der JPEG-Daten in Bytes',
'exif-transferfunction'            => 'Übertragungsfunktion',
'exif-whitepoint'                  => 'Manuell mit Messung',
'exif-primarychromaticities'       => 'Primäre Farbart',
'exif-ycbcrcoefficients'           => 'YCbCr-Koeffizienten',
'exif-referenceblackwhite'         => 'Schwarz/Weiß-Referenzpunkte',
'exif-datetime'                    => 'Speicherzeitpunkt',
'exif-imagedescription'            => 'Bildtitel',
'exif-make'                        => 'Hersteller',
'exif-model'                       => 'Modell',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Fotograf',
'exif-copyright'                   => 'Urheberrechte',
'exif-exifversion'                 => 'Exif-Version',
'exif-flashpixversion'             => 'unterstützte Flashpix-Version',
'exif-colorspace'                  => 'Farbraum',
'exif-componentsconfiguration'     => 'Bedeutung einzelner Komponenten',
'exif-compressedbitsperpixel'      => 'Komprimierte Bits pro Pixel',
'exif-pixelydimension'             => 'Gültige Bildbreite',
'exif-pixelxdimension'             => 'Gültige Bildhöhe',
'exif-makernote'                   => 'Herstellernotiz',
'exif-usercomment'                 => 'Benutzerkommentare',
'exif-relatedsoundfile'            => 'Zugehörige Tondatei',
'exif-datetimeoriginal'            => 'Erfassungszeitpunkt',
'exif-datetimedigitized'           => 'Digitalisierungszeitpunkt',
'exif-subsectime'                  => 'Speicherzeitpunkt (1/100 s)',
'exif-subsectimeoriginal'          => 'Erfassungszeitpunkt (1/100 s)',
'exif-subsectimedigitized'         => 'Digitalisierungszeitpunkt (1/100 s)',
'exif-exposuretime'                => 'Belichtungsdauer',
'exif-exposuretime-format'         => '$1 Sekunden ($2)',
'exif-fnumber'                     => 'Blende',
'exif-exposureprogram'             => 'Belichtungsprogramm',
'exif-spectralsensitivity'         => 'Spectral Sensitivity',
'exif-isospeedratings'             => 'Film- oder Sensorempfindlichkeit (ISO)',
'exif-oecf'                        => 'Optoelektronischer Umrechnungsfaktor',
'exif-shutterspeedvalue'           => 'Belichtungszeitwert',
'exif-aperturevalue'               => 'Blendenwert',
'exif-brightnessvalue'             => 'Helligkeitswert',
'exif-exposurebiasvalue'           => 'Belichtungsvorgabe',
'exif-maxaperturevalue'            => 'Größte Blende',
'exif-subjectdistance'             => 'Entfernung',
'exif-meteringmode'                => 'Messverfahren',
'exif-lightsource'                 => 'Lichtquelle',
'exif-flash'                       => 'Blitz',
'exif-focallength'                 => 'Brennweite',
'exif-subjectarea'                 => 'Bereich',
'exif-flashenergy'                 => 'Blitzstärke',
'exif-spatialfrequencyresponse'    => 'Spatial-Frequenz',
'exif-focalplanexresolution'       => 'Sensorauflösung horizontal',
'exif-focalplaneyresolution'       => 'Sensorauflösung vertikal',
'exif-focalplaneresolutionunit'    => 'Einheit der Sensorauflösung',
'exif-subjectlocation'             => 'Motivstandort',
'exif-exposureindex'               => 'Belichtungsindex',
'exif-sensingmethod'               => 'Messmethode',
'exif-filesource'                  => 'Quelle der Datei',
'exif-scenetype'                   => 'Szenentyp',
'exif-cfapattern'                  => 'CFA-Muster',
'exif-customrendered'              => 'Benutzerdefinierte Bildverarbeitung',
'exif-exposuremode'                => 'Belichtungsmodus',
'exif-whitebalance'                => 'Weißabgleich',
'exif-digitalzoomratio'            => 'Digitalzoom',
'exif-focallengthin35mmfilm'       => 'Brennweite (Kleinbildäquivalent)',
'exif-scenecapturetype'            => 'Aufnahmeart',
'exif-gaincontrol'                 => 'Verstärkung',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Sättigung',
'exif-sharpness'                   => 'Schärfe',
'exif-devicesettingdescription'    => 'Geräteeinstellung',
'exif-subjectdistancerange'        => 'Motiventfernung',
'exif-imageuniqueid'               => 'Bild-ID',
'exif-gpsversionid'                => 'GPS-Tag-Version',
'exif-gpslatituderef'              => 'nördl. oder südl. Breite',
'exif-gpslatitude'                 => 'Geografische Breite',
'exif-gpslongituderef'             => 'östl. oder westl. Länge',
'exif-gpslongitude'                => 'Geografische Länge',
'exif-gpsaltituderef'              => 'Bezugshöhe',
'exif-gpsaltitude'                 => 'Höhe',
'exif-gpstimestamp'                => 'GPS-Zeit',
'exif-gpssatellites'               => 'Für die Messung benutzte Satelliten',
'exif-gpsstatus'                   => 'Empfängerstatus',
'exif-gpsmeasuremode'              => 'Messverfahren',
'exif-gpsdop'                      => 'Maßpräzision',
'exif-gpsspeedref'                 => 'Geschwindigkeitseinheit',
'exif-gpsspeed'                    => 'Geschwindigkeit des GPS-Empfängers',
'exif-gpstrackref'                 => 'Referenz für Bewegungsrichtung',
'exif-gpstrack'                    => 'Bewegungsrichtung',
'exif-gpsimgdirectionref'          => 'Referenz für die Ausrichtung des Bildes',
'exif-gpsimgdirection'             => 'Bildrichtung',
'exif-gpsmapdatum'                 => 'Geodätisches Referenzsystem',
'exif-gpsdestlatituderef'          => 'Referenz für die Breite',
'exif-gpsdestlatitude'             => 'Breite',
'exif-gpsdestlongituderef'         => 'Referenz für die Länge',
'exif-gpsdestlongitude'            => 'Länge',
'exif-gpsdestbearingref'           => 'Referenz für Motivrichtung',
'exif-gpsdestbearing'              => 'Motivrichtung',
'exif-gpsdestdistanceref'          => 'Referenz für die Motiventfernung',
'exif-gpsdestdistance'             => 'Motiventfernung',
'exif-gpsprocessingmethod'         => 'Name des GPS-Verfahrens',
'exif-gpsareainformation'          => 'Name des GPS-Gebietes',
'exif-gpsdatestamp'                => 'GPS-Datum',
'exif-gpsdifferential'             => 'GPS-Differentialkorrektur',
'exif-objectname'                  => 'Kurztitel',

# EXIF attributes
'exif-compression-1' => 'Unkomprimiert',

'exif-unknowndate' => 'Unbekanntes Datum',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Horizontal gespiegelt',
'exif-orientation-3' => 'Um 180° gedreht',
'exif-orientation-4' => 'Vertikal gespiegelt',
'exif-orientation-5' => 'Entgegen dem Uhrzeigersinn um 90° gedreht und vertikal gespiegelt',
'exif-orientation-6' => 'Um 90° im Uhrzeigersinn gedreht',
'exif-orientation-7' => 'Um 90° im Uhrzeigersinn gedreht und vertikal gespiegelt',
'exif-orientation-8' => 'Um 90° entgegen dem Uhrzeigersinn gedreht',

'exif-planarconfiguration-1' => 'Grobformat',
'exif-planarconfiguration-2' => 'Planarformat',

'exif-componentsconfiguration-0' => 'Existiert nicht',

'exif-exposureprogram-0' => 'Unbekannt',
'exif-exposureprogram-1' => 'Manuell',
'exif-exposureprogram-2' => 'Standardprogramm',
'exif-exposureprogram-3' => 'Zeitautomatik',
'exif-exposureprogram-4' => 'Blendenautomatik',
'exif-exposureprogram-5' => 'Kreativprogramm mit Bevorzugung hoher Schärfentiefe',
'exif-exposureprogram-6' => 'Action-Programm mit Bevorzugung einer kurzen Belichtungszeit',
'exif-exposureprogram-7' => 'Portrait-Programm',
'exif-exposureprogram-8' => 'Landschaftsaufnahmen',

'exif-subjectdistance-value' => '$1 Meter',

'exif-meteringmode-0'   => 'Unbekannt',
'exif-meteringmode-1'   => 'Durchschnittlich',
'exif-meteringmode-2'   => 'Mittenzentriert',
'exif-meteringmode-3'   => 'Spotmessung',
'exif-meteringmode-4'   => 'Mehrfachspotmessung',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Bildteil',
'exif-meteringmode-255' => 'Unbekannt',

'exif-lightsource-0'   => 'Unbekannt',
'exif-lightsource-1'   => 'Tageslicht',
'exif-lightsource-2'   => 'Fluoreszierend',
'exif-lightsource-3'   => 'Glühlampe',
'exif-lightsource-4'   => 'Blitz',
'exif-lightsource-9'   => 'Schönes Wetter',
'exif-lightsource-10'  => 'Bewölkt',
'exif-lightsource-11'  => 'Schatten',
'exif-lightsource-12'  => 'Tageslicht fluoreszierend (D 5700–7100 K)',
'exif-lightsource-13'  => 'Tagesweiß fluoreszierend (N 4600–5400 K)',
'exif-lightsource-14'  => 'Kaltweiß fluoreszierend (W 3900–4500 K)',
'exif-lightsource-15'  => 'Weiß fluoreszierend (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Standardlicht A',
'exif-lightsource-18'  => 'Standardlicht B',
'exif-lightsource-19'  => 'Standardlicht C',
'exif-lightsource-24'  => 'ISO Studio Kunstlicht',
'exif-lightsource-255' => 'Andere Lichtquelle',

# Flash modes
'exif-flash-fired-0'    => 'kein Blitz',
'exif-flash-fired-1'    => 'Blitz ausgelöst',
'exif-flash-return-0'   => 'Blitz sendet keine Daten',
'exif-flash-return-2'   => 'keine Reflexion des Blitz festgestellt',
'exif-flash-return-3'   => 'Reflexion des Blitz festgestellt',
'exif-flash-mode-1'     => 'erzwungenes Blitzen',
'exif-flash-mode-2'     => 'Blitz abgeschaltet',
'exif-flash-mode-3'     => 'Automatik',
'exif-flash-function-1' => 'Keine Blitzfunktion',
'exif-flash-redeye-1'   => 'Rotaugen Reduktion',

'exif-focalplaneresolutionunit-2' => 'Zoll',

'exif-sensingmethod-1' => 'Undefiniert',
'exif-sensingmethod-2' => 'Ein-Chip-Farbsensor',
'exif-sensingmethod-3' => 'Zwei-Chip-Farbsensor',
'exif-sensingmethod-4' => 'Drei-Chip-Farbsensor',
'exif-sensingmethod-5' => 'Farbraum sequentiell Sensor',
'exif-sensingmethod-7' => 'Trilinearer Sensor',
'exif-sensingmethod-8' => 'Farbraum linear sequentieller Sensor',

'exif-scenetype-1' => 'Normal',

'exif-customrendered-0' => 'Standard',
'exif-customrendered-1' => 'Benutzerdefiniert',

'exif-exposuremode-0' => 'Automatische Belichtung',
'exif-exposuremode-1' => 'Manuelle Belichtung',
'exif-exposuremode-2' => 'Belichtungsreihe',

'exif-whitebalance-0' => 'Automatisch',
'exif-whitebalance-1' => 'Manuell',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landschaft',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Nachtszene',

'exif-gaincontrol-0' => 'Keine',
'exif-gaincontrol-1' => 'Gering',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Schwach',
'exif-contrast-2' => 'Stark',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Gering',
'exif-saturation-2' => 'Hoch',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Gering',
'exif-sharpness-2' => 'Stark',

'exif-subjectdistancerange-0' => 'Unbekannt',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nah',
'exif-subjectdistancerange-3' => 'Entfernt',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'nördl. Breite',
'exif-gpslatitude-s' => 'südl. Breite',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'östl. Länge',
'exif-gpslongitude-w' => 'westl. Länge',

'exif-gpsstatus-a' => 'Messung läuft',
'exif-gpsstatus-v' => 'Interoperabilität von Messungen',

'exif-gpsmeasuremode-2' => '2-dimensionale Messung',
'exif-gpsmeasuremode-3' => '3-dimensionale Messung',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'Knoten',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tatsächliche Richtung',
'exif-gpsdirection-m' => 'Magnetische Richtung',

# External editor support
'edit-externally'      => 'Diese Datei mit einem externen Programm bearbeiten',
'edit-externally-help' => '(Siehe die [http://www.mediawiki.org/wiki/Manual:External_editors Installationsanweisungen] für weitere Informationen)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',
'limitall'         => 'alle',

# E-mail address confirmation
'confirmemail'              => 'E-Mail-Adresse bestätigen (Authentifizierung)',
'confirmemail_noemail'      => 'Du hast keine gültige E-Mail-Adresse in deinen [[Special:Preferences|persönlichen Einstellungen]] eingetragen.',
'confirmemail_text'         => '{{SITENAME}} erfordert, dass du deine E-Mail-Adresse bestätigst (authentifizieren), bevor du die erweiterten E-Mail-Funktionen benutzen kannst. Klicke bitte auf die unten stehende, mit „Bestätigungscode zuschicken“ beschriftete Schaltfläche, damit eine automatisch erstellte E-Mail an die angegebene Adresse geschickt wird. Diese E-Mail enthält eine Web-Adresse mit einem Bestätigungscode. Indem du diese Webseite in deinem Webbrowser öffnest, bestätigst du, dass die angegebene E-Mail-Adresse korrekt und gültig ist.',
'confirmemail_pending'      => 'Es wurde dir bereits ein Bestätigungscode per E-Mail zugeschickt.
Wenn du dein Benutzerkonto erst vor kurzem erstellt hast, warte bitte noch ein paar Minuten auf die E-Mail, bevor du einen neuen Code anforderst.',
'confirmemail_send'         => 'Bestätigungscode zuschicken',
'confirmemail_sent'         => 'Bestätigungs-E-Mail wurde verschickt.',
'confirmemail_oncreate'     => 'Ein Bestätigungs-Code wurde an deine E-Mail-Adresse gesandt. Dieser Code wird für die Anmeldung nicht benötigt, jedoch wird er zur Aktivierung der E-Mail-Funktionen innerhalb des Wikis gebraucht.',
'confirmemail_sendfailed'   => '{{SITENAME}} konnte die Bestätigungs-E-Mail nicht an dich versenden.
Bitte prüfe die E-Mail-Adresse auf ungültige Zeichen.

Rückmeldung des Mailservers: $1',
'confirmemail_invalid'      => 'Ungültiger Bestätigungscode. Möglicherweise ist der Bestätigungszeitraum verstrichen. Versuche bitte, die Bestätigung zu wiederholen.',
'confirmemail_needlogin'    => 'Du musst dich $1, um deine E-Mail-Adresse zu bestätigen.',
'confirmemail_success'      => 'Deine E-Mail-Adresse wurde erfolgreich bestätigt. Du kannst dich jetzt [[Special:UserLogin|anmelden]].',
'confirmemail_loggedin'     => 'Deine E-Mail-Adresse wurde erfolgreich bestätigt.',
'confirmemail_error'        => 'Es gab einen Fehler bei der Bestätigung deiner E-Mail-Adresse.',
'confirmemail_subject'      => '[{{SITENAME}}] Bestätigung der E-Mail-Adresse',
'confirmemail_body'         => 'Hallo,

jemand mit der IP-Adresse $1, wahrscheinlich du selbst, hat das Benutzerkonto „$2“ in {{SITENAME}} registriert.

Um die E-Mail-Funktion von {{SITENAME}} (wieder) zu aktivieren und um zu bestätigen,
dass dieses Benutzerkonto wirklich zu deiner E-Mail-Adresse und damit zu dir gehört, öffne bitte die folgende Web-Adresse:

$3

Sollte die vorstehende Adresse in deinem E-Mail-Programm über mehrere Zeilen gehen, musst du sie eventuell per Hand in die Adresszeile deines Web-Browsers einfügen.

Wenn du das genannte Benutzerkonto *nicht* registriert hast, folge diesem Link, um den Bestätigungsprozess abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $6, $7 Uhr.',
'confirmemail_body_changed' => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst,
hat die E-Mail-Adresse des Benutzerkontos „$2“ zu dieser Adresse auf {{SITENAME}} geändert.

Um zu bestätigen, dass dieses Benutzerkonto wirklich dir gehört
und um die E-Mail-Features auf {{SITENAME}} zu reaktivieren, öffne diesen Link in deinem Browser:

$3

Falls das Konto *nicht* dir gehört, folge diesem Link,
um die E-Mail-Adress-Bestätigung abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $4.',
'confirmemail_body_set'     => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst,
hat die E-Mail-Adresse des Benutzerkontos „$2“ auf {{SITENAME}} zu dieser E-Mail-Adresse abgeändert.

Um zu bestätigen, dass dieses Benutzerkonto wirklich zu dir gehört
und um die E-Mail-Funktionen auf {{SITENAME}} wieder zu aktivieren, öffne bitte den folgenden Link in deinem Browser:

$3

Falls das Konto *nicht* zu dir gehört, bitte den nachfolgenden Link öffnen,
um die Bestätigung der E-Mail-Adresse abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $4.',
'confirmemail_invalidated'  => 'E-Mail-Adressbestätigung abbrechen',
'invalidateemail'           => 'E-Mail-Adressbestätigung abbrechen',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-Einbindung ist deaktiviert]',
'scarytranscludefailed'   => '[Vorlageneinbindung für $1 ist gescheitert]',
'scarytranscludetoolong'  => '[URL ist zu lang]',

# Trackbacks
'trackbackbox'      => 'Trackbacks für diese Seite:<br />
$1',
'trackbackremove'   => '([$1 löschen])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback wurde erfolgreich gelöscht.',

# Delete conflict
'deletedwhileediting' => 'Achtung: Diese Seite wurde gelöscht, nachdem du angefangen hast sie zu bearbeiten!
Im [{{fullurl:{{#special:Log}}|type=delete&page={{FULLPAGENAMEE}}}} Lösch-Logbuch] findest du den Grund für die Löschung. Wenn du die Seite speicherst, wird sie neu angelegt.',
'confirmrecreate'     => "Benutzer [[User:$1|$1]] ([[User talk:$1|Diskussion]]) hat diese Seite gelöscht, nachdem du angefangen hast, sie zu bearbeiten. Die Begründung lautete:
:''$2''
Bitte bestätige, dass du diese Seite wirklich neu erstellen möchten.",
'recreate'            => 'Erneut anlegen',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Diese Seite aus dem Server-Cache löschen?',
'confirm-purge-bottom' => 'Leert den Cache einer Seite und erzwingt die Anzeige der aktuellen Version.',

# Separators for various lists, etc.
'ellipsis' => '…',
'percent'  => '$1&nbsp;%',

# Multipage image navigation
'imgmultipageprev' => '← vorherige Seite',
'imgmultipagenext' => 'nächste Seite →',
'imgmultigo'       => 'OK',
'imgmultigoto'     => 'Gehe zu Seite $1',

# Table pager
'ascending_abbrev'         => 'auf',
'descending_abbrev'        => 'ab',
'table_pager_next'         => 'Nächste Seite',
'table_pager_prev'         => 'Vorherige Seite',
'table_pager_first'        => 'Erste Seite',
'table_pager_last'         => 'Letzte Seite',
'table_pager_limit'        => 'Zeige $1 Einträge pro Seite',
'table_pager_limit_label'  => 'Positionen pro Seite:',
'table_pager_limit_submit' => 'Los',
'table_pager_empty'        => 'Keine Ergebnisse',

# Auto-summaries
'autosumm-blank'   => 'Die Seite wurde geleert.',
'autosumm-replace' => 'Der Seiteninhalt wurde durch einen anderen Text ersetzt: „$1“',
'autoredircomment' => 'Weiterleitung auf [[$1]] erstellt',
'autosumm-new'     => 'Die Seite wurde neu angelegt: „$1“',

# Size units
'size-bytes'     => '$1 Bytes',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Laden …',
'livepreview-ready'   => 'Laden … Fertig!',
'livepreview-failed'  => 'Live-Vorschau nicht möglich! Bitte die normale Vorschau benutzen.',
'livepreview-error'   => 'Verbindung nicht möglich: $1 „$2“. Bitte die normale Vorschau benutzen.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Bearbeitungen der letzten {{PLURAL:$1|Sekunde|$1 Sekunden}} werden in dieser Liste noch nicht angezeigt.',
'lag-warn-high'   => 'Auf Grund hoher Datenbankauslastung werden die Bearbeitungen der letzten {{PLURAL:$1|Sekunde|$1 Sekunden}} in dieser Liste noch nicht angezeigt.',

# Watchlist editor
'watchlistedit-numitems'       => 'Deine Beobachtungsliste enthält {{PLURAL:$1|1 Eintrag |$1 Einträge}}, Diskussionsseiten werden nicht gezählt.',
'watchlistedit-noitems'        => 'Deine Beobachtungsliste ist leer.',
'watchlistedit-normal-title'   => 'Beobachtungsliste bearbeiten',
'watchlistedit-normal-legend'  => 'Einträge von der Beobachtungsliste entfernen',
'watchlistedit-normal-explain' => 'Dies sind die Einträge deiner Beobachtungsliste. Um Einträge zu entfernen, markiere die Kästchen neben den Einträgen und klicke am Ende der Seite auf „{{int:Watchlistedit-normal-submit}}“. Du kannst deine Beobachtungsliste auch im [[Special:Watchlist/raw|Listenformat bearbeiten]].',
'watchlistedit-normal-submit'  => 'Einträge entfernen',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} von deiner Beobachtungsliste entfernt:',
'watchlistedit-raw-title'      => 'Beobachtungsliste im Listenformat bearbeiten',
'watchlistedit-raw-legend'     => 'Beobachtungsliste im Listenformat bearbeiten',
'watchlistedit-raw-explain'    => 'Dies sind die Einträge deiner Beobachtungsliste im Listenformat. Die Einträge können zeilenweise gelöscht oder hinzugefügt werden.
Pro Zeile ist ein Eintrag erlaubt. Wenn du fertig bist, klicke auf „{{int:Watchlistedit-raw-submit}}“.
Du kannst auch die [[Special:Watchlist/edit|Standard-Bearbeitungsseite]] benutzen.',
'watchlistedit-raw-titles'     => 'Einträge:',
'watchlistedit-raw-submit'     => 'Beobachtungsliste aktualisieren',
'watchlistedit-raw-done'       => 'Deine Beobachtungsliste wurde gespeichert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} hinzugefügt:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} entfernt:',

# Watchlist editing tools
'watchlisttools-view' => 'Beobachtungsliste: Änderungen',
'watchlisttools-edit' => 'normal bearbeiten',
'watchlisttools-raw'  => 'Listenformat bearbeiten (Import/Export)',

# Core parser functions
'unknown_extension_tag' => 'Unbekannter Extension-Tag „$1“',
'duplicate-defaultsort' => 'Achtung: Der Sortierungsschlüssel „$2“ überschreibt den vorher verwendeten Schlüssel „$1“.',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Installierte Erweiterungen',
'version-specialpages'             => 'Spezialseiten',
'version-parserhooks'              => 'Parser-Hooks',
'version-variables'                => 'Variablen',
'version-antispam'                 => 'Spamschutz',
'version-skins'                    => 'Benutzeroberflächen',
'version-other'                    => 'Anderes',
'version-mediahandlers'            => 'Medien-Handler',
'version-hooks'                    => "Schnittstellen ''(Hooks)''",
'version-extension-functions'      => 'Funktionsaufrufe',
'version-parser-extensiontags'     => "Parser-Erweiterungen ''(tags)''",
'version-parser-function-hooks'    => 'Parser-Funktionen',
'version-skin-extension-functions' => 'Benutzeroberflächen-Erweiterungs-Funktionen',
'version-hook-name'                => 'Schnittstellenname',
'version-hook-subscribedby'        => 'Aufruf von',
'version-version'                  => '(Version $1)',
'version-svn-revision'             => '(Version $2)',
'version-license'                  => 'Lizenz',
'version-poweredby-credits'        => "Diese Website nutzt '''[http://www.mediawiki.org/wiki/MediaWiki/de MediaWiki]''', Copyright © 2001–$1 $2.",
'version-poweredby-others'         => 'andere',
'version-license-info'             => "MediaWiki ist freie Software, d. h. sie kann, gemäß den Bedingungen der von der Free Software Foundation veröffentlichten ''GNU General Public License'', weiterverteilt und/ oder modifiziert werden. Dabei kann die Version 2, oder nach eigenem Ermessen, jede neuere Version der Lizenz verwendet werden.

MediaWiki wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings OHNE JEGLICHE GARANTIE und sogar ohne die implizierte Garantie einer MARKTGÄNGIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK. Hierzu sind weitere Hinweise in der ''GNU General Public License'' enthalten.

Eine [{{SERVER}}{{SCRIPTPATH}}/COPYING Kopie der ''GNU General Public License''] sollte zusammen mit diesem Programm verteilt worden sein. Sofern dies nicht der Fall war, kann eine Kopie bei der Free Software Foundation Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, schriftlich angefordert oder auf deren Website [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html online gelesen] werden.",
'version-software'                 => 'Installierte Software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Dateipfad',
'filepath-page'    => 'Datei:',
'filepath-submit'  => 'Los',
'filepath-summary' => 'Mit dieser Spezialseite lässt sich der komplette Pfad der aktuellen Version einer Datei ohne Umweg abfragen. Die angefragte Datei wird direkt dargestellt bzw. mit der verknüpften Anwendung gestartet.

Die Eingabe muss ohne den Zusatz „{{ns:file}}:“ erfolgen.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Datei-Duplikat-Suche',
'fileduplicatesearch-summary'   => 'Suche nach Dateiduplikaten auf Basis ihres Hashwertes.',
'fileduplicatesearch-legend'    => 'Suche nach Duplikaten',
'fileduplicatesearch-filename'  => 'Dateiname:',
'fileduplicatesearch-submit'    => 'Suchen',
'fileduplicatesearch-info'      => '$1 × $2 Pixel<br />Dateigröße: $3<br />MIME-Typ: $4',
'fileduplicatesearch-result-1'  => 'Die Datei „$1“ hat keine identischen Duplikate.',
'fileduplicatesearch-result-n'  => 'Die Datei „$1“ hat {{PLURAL:$2|1 identisches Duplikat|$2 identische Duplikate}}.',
'fileduplicatesearch-noresults' => 'Es wurde keine Datei namens „$1“ gefunden.',

# Special:SpecialPages
'specialpages'                   => 'Spezialseiten',
'specialpages-note'              => '----
* Spezialseiten für jedermann
* <strong class="mw-specialpagerestricted">Spezialseiten für Benutzer mit erweiterten Rechten</strong>',
'specialpages-group-maintenance' => 'Wartungslisten',
'specialpages-group-other'       => 'Andere Spezialseiten',
'specialpages-group-login'       => 'Anmelden',
'specialpages-group-changes'     => 'Letzte Änderungen und Logbücher',
'specialpages-group-media'       => 'Medien',
'specialpages-group-users'       => 'Benutzer und Rechte',
'specialpages-group-highuse'     => 'Häufig benutzte Seiten',
'specialpages-group-pages'       => 'Listen von Seiten',
'specialpages-group-pagetools'   => 'Seitenwerkzeuge',
'specialpages-group-wiki'        => 'Systemdaten und Werkzeuge',
'specialpages-group-redirects'   => 'Weiterleitende Spezialseiten',
'specialpages-group-spam'        => 'Spam-Werkzeuge',

# Special:BlankPage
'blankpage'              => 'Leere Seite',
'intentionallyblankpage' => 'Diese Seite ist absichtlich ohne Inhalt. Sie wird für Benchmarks verwendet.',

# External image whitelist
'external_image_whitelist' => ' #Diese Zeile nicht verändern<pre>
#Untenstehend können Fragmente regulärer Ausdrücke (der Teil zwischen den //) eingegeben werden.
#Diese werden mit den URLs von Bildern aus externen Quellen verglichen
#Ein positiver Vergleich führt zur Anzeige des Bildes, andernfalls wird das Bild nur als Link angezeigt
#Zeilen, die mit einem # beginnen, werden als Kommentar behandelt
#Es wird nicht zwischen Groß- und Kleinschreibung unterschieden

#Fragmente regulärer Ausdrücke nach dieser Zeile eintragen. Diese Zeile nicht verändern</pre>',

# Special:Tags
'tags'                    => 'Gültige Änderungsmarkierungen',
'tag-filter'              => '[[Special:Tags|Markierungs]]-Filter:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Markierungen',
'tags-intro'              => 'Diese Seite zeigt alle Markierungen, die für Bearbeitungen verwendet werden, sowie deren Bedeutung.',
'tags-tag'                => 'Markierungsname',
'tags-display-header'     => 'Benennung auf den Änderungslisten',
'tags-description-header' => 'Vollständige Beschreibung',
'tags-hitcount-header'    => 'Markierte Änderungen',
'tags-edit'               => 'bearbeiten',
'tags-hitcount'           => '$1 {{PLURAL:$1|Änderung|Änderungen}}',

# Special:ComparePages
'comparepages'     => 'Seiten vergleichen',
'compare-selector' => 'Seitenversionen vergleichen',
'compare-page1'    => 'Seite 1',
'compare-page2'    => 'Seite 2',
'compare-rev1'     => 'Version 1',
'compare-rev2'     => 'Version 2',
'compare-submit'   => 'Vergleichen',

# Database error messages
'dberr-header'      => 'Dieses Wiki hat ein Problem',
'dberr-problems'    => 'Entschuldigung. Diese Seite hat momentan technische Schwierigkeiten.',
'dberr-again'       => 'Warte einige Minuten und versuche dann neu zu laden.',
'dberr-info'        => '(Kann keine Verbindung zum Datenbank-Server herstellen: $1)',
'dberr-usegoogle'   => 'Du könntest in der Zwischenzeit mit Google suchen.',
'dberr-outofdate'   => 'Beachte, dass der Suchindex unserer Inhalte bei Google veraltet sein kann.',
'dberr-cachederror' => 'Folgendes ist eine Kopie des Caches der angeforderten Seite und kann veraltet sein.',

# HTML forms
'htmlform-invalid-input'       => 'Mit einigen Eingaben gibt es Probleme',
'htmlform-select-badoption'    => 'Der angegebene Wert ist keine gültige Option.',
'htmlform-int-invalid'         => 'Der angegebene Wert ist keine Ganzzahl.',
'htmlform-float-invalid'       => 'Der angegebene Wert ist keine Zahl.',
'htmlform-int-toolow'          => 'Der angegebene Wert ist unter dem Minimum von $1',
'htmlform-int-toohigh'         => 'Der angegebene Wert ist über dem Maximum von $1',
'htmlform-required'            => 'Dieser Wert wird benötigt',
'htmlform-submit'              => 'Speichern',
'htmlform-reset'               => 'Änderungen rückgängig machen',
'htmlform-selectorother-other' => 'Andere',

# SQLite database support
'sqlite-has-fts' => 'Version $1 mit Unterstützung für die Volltextsuche',
'sqlite-no-fts'  => 'Version $1 ohne Unterstützung für die Volltextsuche',

# Special:DisableAccount
'disableaccount'             => 'Ein Benutzerkonto deaktivieren',
'disableaccount-user'        => 'Benutzername:',
'disableaccount-reason'      => 'Grund:',
'disableaccount-confirm'     => "Deaktivieren dieses Benutzerkontos.
Der Benutzer wird nicht mehr in der Lage sein sich anzumelden, das Passwort zurückzusetzen oder E-Mail-Benachrichtigungen zu empfangen.
Sofern der Benutzer momentan angemeldet ist, wird er umgehend abgemeldet.
''Es ist zu beachten, das die Deaktivierung eines Benutzerkontos nicht mehr ohne das Eingreifen eines Systemadministrators rückgängig gemacht werden kann.''",
'disableaccount-mustconfirm' => 'Die Deaktivierung dieses Benutzerkontos muss nun bestätigt werden.',
'disableaccount-nosuchuser'  => 'Das Benutzerkonto „$1“ ist nicht vorhanden.',
'disableaccount-success'     => 'Das Benutzerkonto „$1“ wurde dauerhaft deaktiviert.',
'disableaccount-logentry'    => 'deaktivierte das Benutzerkonto [[$1]] dauerhaft',

);
