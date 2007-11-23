<?php
/**
 * German (Deutsch)
 *
 * @addtogroup Language
 *
 * @author Jimmy Collins <jimmy.collins@web.de>
 * @author Raimond Spekking (Raymond) <raimond.spekking@gmail.com> since January 2007
 */


$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_MAIN             => '',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Benutzer',
	NS_USER_TALK        => 'Benutzer_Diskussion',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_Diskussion',
	NS_IMAGE            => 'Bild',
	NS_IMAGE_TALK       => 'Bild_Diskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussion',
	NS_TEMPLATE         => 'Vorlage',
	NS_TEMPLATE_TALK    => 'Vorlage_Diskussion',
	NS_HELP             => 'Hilfe',
	NS_HELP_TALK        => 'Hilfe_Diskussion',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussion'
);

$skinNames = array(
	'standard'      => 'Klassik',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Kölnisch Blau',
	'monobook'      => 'MonoBook',
	'myskin'        => 'MySkin',
	'chick'         => 'Küken',
	'simple'        => 'Einfach'
);


$bookstoreList = array(
	'abebooks.de' => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'amazon.de' => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'buch.de' => 'http://www.buch.de/de.buch.shop/shop/1/home/schnellsuche/buch/?fqbi=$1',
	'Karlsruher Virtueller Katalog (KVK)' => 'http://www.ubka.uni-karlsruhe.de/kvk.html?SB=$1',
	'Lehmanns Fachbuchhandlung' => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1'
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';

/**
 * Alternate names of special pages. All names are case-insensitive. The first
 * listed alias will be used as the default. Aliases from the fallback 
 * localisation (usually English) will be included by default. 
 * 
 * This array may be altered at runtime using the LanguageGetSpecialPageAliases 
 * hook.
 */
$specialPageAliases = array(
        'DoubleRedirects'           => array( 'Doppelte_Weiterleitungen' ),
        'BrokenRedirects'           => array( 'Kaputte_Weiterleitungen' ),
        'Disambiguations'           => array( 'Begriffsklärungsverweise' ),
        'Userlogin'                 => array( 'Anmelden' ),
        'Userlogout'                => array( 'Abmelden' ),
        'Preferences'               => array( 'Einstellungen' ),
        'Watchlist'                 => array( 'Beobachtungsliste' ),
        'Recentchanges'             => array( 'Letzte_Änderungen' ),
        'Upload'                    => array( 'Hochladen' ),
        'Imagelist'                 => array( 'Dateien', 'Dateiliste' ),
        'Newimages'                 => array( 'Neue_Dateien' ),
        'Listusers'                 => array( 'Benutzer' ),
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
        'Mostlinked'                => array( 'Meistverlinkte_Seiten' ),
        'Mostlinkedcategories'      => array( 'Meistbenutzte_Kategorien' ),
        'Mostlinkedtemplates'       => array( 'Meistbenutzte_Vorlagen' ),
        'Mostcategories'            => array( 'Meistkategorisierte_Seiten' ),
        'Mostimages'                => array( 'Meistbenutzte_Dateien' ),
        'Mostrevisions'             => array( 'Meistbearbeitete_Seiten' ),
        'Fewestrevisions'           => array( 'Wenigstbearbeitete_Seiten' ),
        'Shortpages'                => array( 'Kürzeste_Seiten' ),
        'Longpages'                 => array( 'Längste_Seiten' ),
        'Newpages'                  => array( 'Neue_Seiten' ),
        'Ancientpages'              => array( 'Älteste_Seiten' ),
        'Deadendpages'              => array( 'Sackgassenseiten' ),
        'Protectedpages'            => array( 'Geschützte_Seiten' ),
        'Allpages'                  => array( 'Alle_Seiten' ),
        'Prefixindex'               => array( 'Präfixindex' ) ,
        'Ipblocklist'               => array( 'Gesperrte_IPs' ),
        'Specialpages'              => array( 'Spezialseiten' ),
        'Contributions'             => array( 'Beiträge' ),
        'Emailuser'                 => array( 'E-Mail' ),
        'Whatlinkshere'             => array( 'Linkliste', 'Verweisliste' ),
        'Recentchangeslinked'       => array( 'Änderungen_an_verlinkten_Seiten' ),
        'Movepage'                  => array( 'Verschieben' ),
        'Blockme'                   => array( 'Proxy-Sperre' ),
        'Booksources'               => array( 'ISBN-Suche' ),
        'Categories'                => array( 'Kategorien' ),
        'Export'                    => array( 'Exportieren' ),
        'Version'                   => array( 'Version' ),
        'Allmessages'               => array( 'MediaWiki-Systemnachrichten' ),
        'Log'                       => array( 'Logbuch' ),
        'Blockip'                   => array( 'Sperren' ),
        'Undelete'                  => array( 'Wiederherstellen' ),
        'Import'                    => array( 'Importieren' ),
        'Lockdb'                    => array( 'Datenbank_sperren' ),
        'Unlockdb'                  => array( 'Datenbank_entsperren' ),
        'Userrights'                => array( 'Benutzerrechte' ),
        'MIMEsearch'                => array( 'MIME-Typ-Suche' ),
        'Unwatchedpages'            => array( 'Ignorierte_Seiten', 'Unbeobachtete_Seiten' ),
        'Listredirects'             => array( 'Weiterleitungen' ),
        'Revisiondelete'            => array( 'Versionslöschung' ),
        'Unusedtemplates'           => array( 'Unbenutzte_Vorlagen' ),
        'Randomredirect'            => array( 'Zufällige_Weiterleitung' ),
        'Mypage'                    => array( 'Meine_Benutzerseite' ),
        'Mytalk'                    => array( 'Meine_Diskussionsseite' ),
        'Mycontributions'           => array( 'Meine_Beiträge' ),
        'Listadmins'                => array( 'Administratoren' ),
        'Search'                    => array( 'Suche' ),
        'Withoutinterwiki'          => array( 'Fehlende_Interwikis' ),
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

$messages = array(
# User preference toggles
'tog-underline'               => 'Links unterstreichen:',
'tog-highlightbroken'         => 'Links auf leere Seiten hervorheben',
'tog-justify'                 => 'Text als Blocksatz',
'tog-hideminor'               => 'Kleine Änderungen ausblenden',
'tog-extendwatchlist'         => 'Erweiterte Beobachtungsliste',
'tog-usenewrc'                => 'Erweiterte Darstellung (benötigt JavaScript)',
'tog-numberheadings'          => 'Überschriften automatisch nummerieren',
'tog-showtoolbar'             => 'Bearbeiten-Werkzeugleiste anzeigen',
'tog-editondblclick'          => 'Seiten mit Doppelklick bearbeiten (JavaScript)',
'tog-editsection'             => 'Links zum Bearbeiten einzelner Absätze anzeigen',
'tog-editsectiononrightclick' => 'Einzelne Absätze per Rechtsklick bearbeiten (JavaScript)',
'tog-showtoc'                 => 'Anzeigen eines Inhaltsverzeichnisses bei Seiten mit mehr als drei Überschriften',
'tog-rememberpassword'        => 'Benutzer soll auf diesem Computer dauerhaft angemeldet bleiben',
'tog-editwidth'               => 'Text-Eingabefeld mit voller Breite',
'tog-watchcreations'          => 'Selbst erstellte Seiten automatisch beobachten',
'tog-watchdefault'            => 'Selbst geänderte und neu erstellte Seiten automatisch beobachten',
'tog-watchmoves'              => 'Selbst verschobene Seiten automatisch beobachten',
'tog-watchdeletion'           => 'Selbst gelöschte Seiten automatisch beobachten',
'tog-minordefault'            => 'Alle eigenen Änderungen als geringfügig markieren',
'tog-previewontop'            => 'Vorschau oberhalb des Bearbeitungsfensters anzeigen',
'tog-previewonfirst'          => 'Beim ersten Bearbeiten immer die Vorschau anzeigen',
'tog-nocache'                 => 'Seitencache deaktivieren',
'tog-enotifwatchlistpages'    => 'Bei Änderungen an beobachteten Seiten E-Mails senden.',
'tog-enotifusertalkpages'     => 'Bei Änderungen an meiner Benutzer-Diskussionsseite E-Mails senden.',
'tog-enotifminoredits'        => 'Auch bei kleinen Änderungen an beobachteten Seiten E-Mails senden.',
'tog-enotifrevealaddr'        => 'Deine E-Mail-Adresse wird in Benachrichtigungsmails gezeigt.',
'tog-shownumberswatching'     => 'Anzahl der beobachtenden Benutzer anzeigen',
'tog-fancysig'                => 'Signatur ohne Verlinkung zur Benutzerseite',
'tog-externaleditor'          => 'Externen Editor als Standard benutzen',
'tog-externaldiff'            => 'Externes Diff-Programm als Standard benutzen',
'tog-showjumplinks'           => '„Wechseln-zu“-Links ermöglichen',
'tog-uselivepreview'          => 'Live-Vorschau nutzen (JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Warnen, wenn beim Speichern die Zusammenfassung fehlt',
'tog-watchlisthideown'        => 'Eigene Bearbeitungen in der Beobachtungsliste ausblenden',
'tog-watchlisthidebots'       => 'Bearbeitungen durch Bots in der Beobachtungsliste ausblenden',
'tog-watchlisthideminor'      => 'Kleine Bearbeitungen in der Beobachtungsliste ausblenden',
'tog-nolangconversion'        => 'Konvertierung von Sprachvarianten deaktivieren',
'tog-ccmeonemails'            => 'Schicke mir Kopien der E-Mails, die ich anderen Benutzern sende.',
'tog-diffonly'                => 'Zeige beim Versionsvergleich nur die Unterschiede, nicht die vollständige Seite',

'underline-always'  => 'immer',
'underline-never'   => 'nie',
'underline-default' => 'von Browsereinstellung abhängig',

'skinpreview' => '(Vorschau)',

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

# Bits of text used by many pages
'categories'            => 'Kategorien',
'pagecategories'        => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'       => 'Seiten in der Kategorie „$1“',
'subcategories'         => 'Unterkategorien',
'category-media-header' => 'Medien in der Kategorie „$1“',
'category-empty'        => "''Diese Kategorie enthält zur Zeit keine Seiten oder Medien.''",

'mainpagetext'      => 'MediaWiki wurde erfolgreich installiert.',
'mainpagedocfooter' => 'Hilfe zur Benutzung und Konfiguration der Wiki Software findest du im [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].

== Starthilfen ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste der Konfigurationsvariablen]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'about'          => 'Über',
'article'        => 'Seite',
'newwindow'      => '(wird in einem neuen Fenster geöffnet)',
'cancel'         => 'Abbrechen',
'qbfind'         => 'Finden',
'qbbrowse'       => 'Blättern',
'qbedit'         => 'Ändern',
'qbpageoptions'  => 'Seitenoptionen',
'qbpageinfo'     => 'Seitendaten',
'qbmyoptions'    => 'Meine Seiten',
'qbspecialpages' => 'Spezialseiten',
'moredotdotdot'  => 'Mehr …',
'mypage'         => 'Eigene Seite',
'mytalk'         => 'Eigene Diskussion',
'anontalk'       => 'Diskussionsseite dieser IP',
'navigation'     => 'Navigation',

# Metadata in edit box
'metadata_help' => 'Metadaten:',

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
'updatedmarker'     => '(geändert)',
'info_short'        => 'Information',
'printableversion'  => 'Druckversion',
'permalink'         => 'Permanentlink',
'print'             => 'Drucken',
'edit'              => 'bearbeiten',
'editthispage'      => 'Seite bearbeiten',
'delete'            => 'löschen',
'deletethispage'    => 'Diese Seite löschen',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiederherstellen',
'protect'           => 'schützen',
'protect_change'    => 'Schutz ändern',
'protectthispage'   => 'Seite schützen',
'unprotect'         => 'freigeben',
'unprotectthispage' => 'Schutz aufheben',
'newpage'           => 'Neue Seite',
'talkpage'          => 'Diskussion',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Spezialseite',
'personaltools'     => 'Persönliche Werkzeuge',
'postcomment'       => 'Kommentar hinzufügen',
'articlepage'       => 'Seite',
'talk'              => 'Diskussion',
'views'             => 'Ansichten',
'toolbox'           => 'Werkzeuge',
'userpage'          => 'Benutzerseite',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Bildseite',
'mediawikipage'     => 'Inhaltsseite anzeigen',
'templatepage'      => 'Vorlagenseite anzeigen',
'viewhelppage'      => 'Hilfeseite anzeigen',
'categorypage'      => 'Kategorieseite anzeigen',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Andere Sprachen',
'redirectedfrom'    => '(Weitergeleitet von $1)',
'redirectpagesub'   => 'Weiterleitung',
'lastmodifiedat'    => 'Diese Seite wurde zuletzt am $1 um $2 Uhr geändert.', # $1 date, $2 time
'viewcount'         => 'Diese Seite wurde bisher {{PLURAL:$1|einmal|$1-mal}} abgerufen.',
'protectedpage'     => 'Geschützte Seite',
'jumpto'            => 'Wechseln zu:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Suche',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Über {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Über_{{SITENAME}}',
'bugreports'        => 'Kontakt',
'bugreportspage'    => '{{ns:project}}:Kontakt',
'copyright'         => 'Inhalt ist verfügbar unter der $1.',
'copyrightpagename' => '{{SITENAME}} Urheberrecht',
'copyrightpage'     => '{{ns:project}}:Urheberrecht',
'currentevents'     => 'Aktuelle Ereignisse',
'currentevents-url' => 'Aktuelle Ereignisse',
'disclaimers'       => 'Impressum',
'disclaimerpage'    => '{{ns:project}}:Impressum',
'edithelp'          => 'Bearbeitungshilfe',
'edithelppage'      => '{{ns:project}}:Bearbeitungshilfe',
'faq'               => 'FAQ',
'faqpage'           => '{{ns:project}}:FAQ',
'helppage'          => '{{ns:project}}:Hilfe',
'mainpage'          => 'Hauptseite',
'policy-url'        => 'Project:Leitlinien',
'portal'            => '{{SITENAME}}-Portal',
'portal-url'        => '{{ns:project}}:Portal',
'privacy'           => 'Datenschutz',
'privacypage'       => '{{ns:project}}:Datenschutz',
'sitesupport'       => 'Spenden',
'sitesupport-url'   => '{{ns:project}}:Spenden',

'badaccess'        => 'Keine ausreichenden Rechte',
'badaccess-group0' => 'Du hast nicht die erforderliche Berechtigung für diese Aktion.',
'badaccess-group1' => 'Diese Aktion ist beschränkt auf Benutzer, die der Gruppe „$1“ angehören.',
'badaccess-group2' => 'Diese Aktion ist beschränkt auf Benutzer, die einer der Gruppen „$1“ angehören.',
'badaccess-groups' => 'Diese Aktion ist beschränkt auf Benutzer, die einer der Gruppen „$1“ angehören.',

'versionrequired'     => 'Version $1 von MediaWiki ist erforderlich',
'versionrequiredtext' => 'Version $1 von MediaWiki ist erforderlich, um diese Seite zu nutzen. Siehe die [[{{ns:special}}:Version|Versionsseite]]',

'ok'                      => 'Suchen',
'pagetitle'               => '$1 - {{SITENAME}}',
'retrievedfrom'           => 'Von „$1“',
'youhavenewmessages'      => 'Du hast $2 auf deiner $1.',
'newmessageslink'         => 'Diskussionsseite',
'newmessagesdifflink'     => 'neue Nachrichten',
'youhavenewmessagesmulti' => 'Du hast neue Nachrichten: $1',
'editsection'             => 'bearbeiten',
'editold'                 => 'bearbeiten',
'editsectionhint'         => 'Abschnitt bearbeiten: $1',
'toc'                     => 'Inhaltsverzeichnis',
'showtoc'                 => 'Anzeigen',
'hidetoc'                 => 'Verbergen',
'thisisdeleted'           => '$1 ansehen oder wiederherstellen?',
'viewdeleted'             => '$1 anzeigen?',
'restorelink'             => '$1 {{PLURAL:$1|gelöschte Version|gelöschte Versionen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungültiger Abonnement-Typ.',
'site-rss-feed'           => 'RSS-Feed für $1',
'site-atom-feed'          => 'Atom-Feed für $1',
'page-rss-feed'           => 'RSS-Feed für „$1“',
'page-atom-feed'          => 'Atom-Feed für „$1“',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Seite',
'nstab-user'      => 'Benutzerseite',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezialseite',
'nstab-project'   => 'Portalseite',
'nstab-image'     => 'Datei',
'nstab-mediawiki' => 'MediaWiki-Systemtext',
'nstab-template'  => 'Vorlage',
'nstab-help'      => 'Hilfeseite',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Diese Aktion gibt es nicht',
'nosuchactiontext'  => 'Die in der URL angegebene Aktion wird von MediaWiki nicht unterstützt.',
'nosuchspecialpage' => 'Spezialseite nicht vorhanden',
'nospecialpagetext' => "<big>'''Die aufgerufene Spezialseite ist nicht vorhanden.'''</big>

Alle verfügbaren Spezialseiten sind in der [[{{ns:special}}:Specialpages|Liste der Spezialseiten]] zu finden.",

# General errors
'error'                => 'Fehler',
'databaseerror'        => 'Fehler in der Datenbank',
'dberrortext'          => 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: <blockquote><tt>$1</tt></blockquote> aus der Funktion „<tt>$2</tt>“.
MySQL meldete den Fehler „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: „$1“ aus der Funktion „<tt>$2</tt>“.
MySQL meldete den Fehler: „<tt>$3: $4</tt>“.',
'noconnect'            => 'Konnte keine Verbindung zur Datenbank auf $1 herstellen',
'nodb'                 => 'Konnte Datenbank $1 nicht auswählen',
'cachederror'          => 'Das Folgende ist eine Kopie aus dem Cache und möglicherweise nicht aktuell.',
'laggedslavemode'      => 'Achtung: Die angezeigte Seite enthält unter Umständen nicht die jüngsten Bearbeitungen.',
'readonly'             => 'Datenbank ist gesperrt',
'enterlockreason'      => 'Bitte gib einen Grund ein, warum die Datenbank gesperrt werden soll und eine Abschätzung über die Dauer der Sperrung',
'readonlytext'         => 'Die Datenbank ist vorübergehend für Neueinträge und Änderungen gesperrt. Bitte versuchen Sie es später noch einmal.

Grund der Sperrung: $1',
'missingarticle'       => 'Der Text für „$1“ wurde nicht in der Datenbank gefunden.

Die Seite ist möglicherweise gelöscht oder verschoben worden.

Falls dies nicht der Fall ist, hast du eventuell einen Fehler in der Software gefunden. Bitte melde  dies einem [[{{MediaWiki:grouppage-sysop}}|Administrator]] unter Nennung der URL.',
'readonly_lag'         => 'Die Datenbank wurde automatisch für Schreibzugriffe gesperrt, damit sich die verteilten Datenbankserver (slaves) mit dem Hauptdatenbankserver (master) abgleichen können.',
'internalerror'        => 'Interner Fehler',
'internalerror_info'   => 'Interner Fehler: $1',
'filecopyerror'        => 'Die Datei „$1“ konnte nicht nach „$2“ kopiert werden.',
'filerenameerror'      => 'Die Datei „$1“ konnte nicht nach „$2“ umbenannt werden.',
'filedeleteerror'      => 'Die Datei „$1“ konnte nicht gelöscht werden.',
'directorycreateerror' => 'Das Verzeichnis „$1“ konnte nicht angelegt werden.',
'filenotfound'         => 'Die Datei „$1“ wurde nicht gefunden.',
'fileexistserror'      => 'In die Datei „$1“ konnte nicht geschrieben werden, da die Datei bereits vorhanden ist.',
'unexpected'           => 'Unerwarteter Wert: „$1“=„$2“.',
'formerror'            => 'Fehler: Die Eingaben konnten nicht verarbeitet werden.',
'badarticleerror'      => 'Diese Aktion kann auf diese Seite nicht angewendet werden.',
'cannotdelete'         => 'Die gewählte Seite kann nicht gelöscht werden. Möglicherweise wurde sie bereits gelöscht.',
'badtitle'             => 'Ungültiger Titel',
'badtitletext'         => 'Der Titel der angeforderten Seite ist ungültig, leer oder ein ungültiger Sprachlink von einem anderen Wiki.',
'perfdisabled'         => "'''Entschuldigung!''' Diese Funktion wurde wegen Überlastung des Servers vorübergehend deaktiviert.",
'perfcached'           => 'Die folgenden Daten stammen aus dem Cache und sind möglicherweise nicht aktuell:',
'perfcachedts'         => 'Diese Daten stammen aus dem Cache, letztes Update: $1',
'querypage-no-updates' => "'''Die Aktualisierungsfunktion für diese Seite ist zur Zeit deaktiviert. Die Daten werden bis auf weiteres nicht erneuert.'''",
'wrong_wfQuery_params' => 'Falsche Parameter für wfQuery()<br />
Funktion: $1<br />
Abfrage: $2',
'viewsource'           => 'Quelltext betrachten',
'viewsourcefor'        => 'für $1',
'actionthrottled'      => 'Aktionsanzahl limitiert',
'actionthrottledtext'  => 'Die Ausführung dieser Aktion zu oft in einem kurzen Zeitabstand ist limitiert. Du hast dieses soeben Limit erreicht. Bitte versuche es in einigen Minuten erneut.',
'protectedpagetext'    => 'Diese Seite ist für das Bearbeiten gesperrt.',
'viewsourcetext'       => 'Quelltext dieser Seite:',
'protectedinterface'   => 'Diese Seite enthält Text für das Sprach-Interface der Software und ist gesperrt, um Missbrauch zu verhindern.',
'editinginterface'     => "'''Warnung:''' Diese Seite enthält von der MediaWiki-Software benutzten Text. Änderungen wirken sich auf die Benutzeroberfläche aus.",
'sqlhidden'            => '(SQL-Abfrage versteckt)',
'cascadeprotected'     => 'Diese Seite ist zur Bearbeitung gesperrt. Sie ist in die {{PLURAL:$1|folgende Seite|folgenden Seiten}} eingebunden, die mittels der Kaskadensperroption geschützt {{PLURAL:$1|ist|sind}}:
$2',
'namespaceprotected'   => "Du hast keine Berechtigung, die Seite in dem '''$1'''-Namensraum zu bearbeiten.",
'customcssjsprotected' => 'Du bist nicht berechtigt diese Seite zu bearbeiten, da sie zu den persönlichen Einstellungen eines anderen Benutzers gehört.',
'ns-specialprotected'  => 'Seiten im {{ns:special}}-Namensraum können nicht bearbeitet werden.',

# Login and logout pages
'logouttitle'                => 'Benutzer-Abmeldung',
'logouttext'                 => 'Du bist nun abgemeldet.
Du kannst {{SITENAME}} jetzt anonym weiterbenutzen, oder dich erneut unter dem selben oder einem anderen Benutzernamen wieder anmelden.',
'welcomecreation'            => '== Willkommen, $1! ==

Dein Benutzerkonto wurde eingerichtet. Vergiss nicht, deine Einstellungen anzupassen.',
'loginpagetitle'             => 'Benutzer-Anmeldung',
'yourname'                   => 'Benutzername:',
'yourpassword'               => 'Passwort:',
'yourpasswordagain'          => 'Passwort wiederholen:',
'remembermypassword'         => 'Benutzer auf diesem Computer dauerhaft anmelden',
'yourdomainname'             => 'Deine Domain:',
'externaldberror'            => 'Entweder es liegt ein Fehler bei der externen Authentifizierung vor, oder du darfst dein externes Benutzerkonto nicht aktualisieren.',
'loginproblem'               => "'''Es gab ein Problem mit deiner Anmeldung.'''<br />Bitte versuche es nochmal!",
'login'                      => 'Anmelden',
'loginprompt'                => 'Um sich bei {{SITENAME}} anmelden zu können, müssen Cookies aktiviert sein.',
'userlogin'                  => 'Anmelden',
'logout'                     => 'Abmelden',
'userlogout'                 => 'Abmelden',
'notloggedin'                => 'Nicht angemeldet',
'nologin'                    => 'Du hast kein Benutzerkonto? $1.',
'nologinlink'                => 'Neues Benutzerkonto anlegen',
'createaccount'              => 'Benutzerkonto anlegen',
'gotaccount'                 => 'Du hast bereits ein Benutzerkonto? $1.',
'gotaccountlink'             => 'Anmelden',
'createaccountmail'          => 'über E-Mail',
'badretype'                  => 'Die beiden Passwörter stimmen nicht überein.',
'userexists'                 => 'Dieser Benutzername ist schon vergeben. Bitte wähle einen anderen.',
'youremail'                  => 'E-Mail-Adresse:',
'username'                   => 'Benutzername:',
'uid'                        => 'Benutzer-ID:',
'yourrealname'               => 'Echter Name:',
'yourlanguage'               => 'Sprache der Benutzeroberfläche:',
'yourvariant'                => 'Variante',
'yournick'                   => 'Unterschrift:',
'badsig'                     => 'Die Syntax der Unterschrift ist ungültig; bitte HTML überprüfen.',
'badsiglength'               => 'Die Unterschrift darf maximal $1 Zeichen lang sein.',
'email'                      => 'E-Mail',
'prefs-help-realname'        => 'Optional. Dein echter Name wird deinen Beiträgen zugeordnet.',
'loginerror'                 => 'Fehler bei der Anmeldung',
'prefs-help-email'           => 'Optional. Ermöglicht anderen Benutzern, über E-Mail Kontakt mit dir aufzunehmen, ohne dass du deine Identität offenlegen musst, sowie das Zustellen eines Ersatzpasswortes.',
'prefs-help-email-required'  => 'Es wird eine gültige E-Mail-Adresse benötigt.',
'nocookiesnew'               => 'Der Benutzerzugang wurde erstellt, aber du bist nicht eingeloggt. {{SITENAME}} benötigt für diese Funktion Cookies, bitte aktiviere diese und logge dich dann mit deinem neuen Benutzernamen und dem Passwort ein.',
'nocookieslogin'             => '{{SITENAME}} benutzt Cookies zum Einloggen der Benutzer. Du hast Cookies deaktiviert, bitte aktiviere diese und versuchen es erneut.',
'noname'                     => 'Du musst einen gültigen Benutzernamen angeben.',
'loginsuccesstitle'          => 'Anmeldung erfolgreich',
'loginsuccess'               => 'Du bist jetzt als „$1“ bei {{SITENAME}} angemeldet.',
'nosuchuser'                 => 'Der Benutzername „$1“ existiert nicht. Überprüfe die Schreibweise oder melde dich als neuer Benutzer an.',
'nosuchusershort'            => 'Der Benutzername „$1“ existiert nicht. Bitte überprüfe die Schreibweise.',
'nouserspecified'            => 'Bitte gib einen Benutzernamen an.',
'wrongpassword'              => 'Das Passwort ist falsch (oder fehlt). Bitte versuche es erneut.',
'wrongpasswordempty'         => 'Das eingegebene Passwort war leer. Bitte versuche es erneut.',
'passwordtooshort'           => 'Fehler bei der Passwort-Wahl: Es muss mindestens $1 Zeichen lang sein und darf nicht mit dem Benutzernamen identisch sein.',
'mailmypassword'             => 'Neues Passwort zusenden',
'passwordremindertitle'      => 'Neues Passwort für ein {{SITENAME}}-Benutzerkonto',
'passwordremindertext'       => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst, hat ein neues Passwort für die Anmeldung bei {{SITENAME}} ($4) angefordert.

Das automatisch generierte Passwort für Benutzer $2 lautet nun: $3

Du solltest dich jetzt anmelden und das Passwort ändern: {{fullurl:{{ns:special}}}}:Userlogin

Bitte ignoriese diese E-Mail, falls du diese nicht selbst angefordert haben. Das alte Passwort bleibt weiterhin gültig.',
'noemail'                    => 'Benutzer „$1“ hat keine E-Mail-Adresse angegeben.',
'passwordsent'               => 'Ein neues, temporäres Passwort wurde an die E-Mail-Adresse von Benutzer „$1“ gesendet.
Bitte melde dich damit an, sobald du es erhalten hast. Das alte Passwort bleibt weiterhin gültig.',
'blocked-mailpassword'       => 'Die von dir verwendete IP-Adresse ist für das Ändern von Seiten gesperrt. Um einen Missbrauch zu verhindern, wurde die Möglichkeit zur Anforderung eines neuen Passwortes ebenfalls gesperrt.',
'eauthentsent'               => 'Eine Bestätigungs-E-Mail wurde an die angegebene Adresse verschickt. 

Bevor eine E-Mail von anderen Benutzern über die E-Mail-Funktion empfangen werden kann, muss die Adresse und ihre tatsächliche Zugehörigkeit zu diesem Benutzerkonto erst bestätigt werden. Bitte befolge die Hinweise in der Bestätigungs-E-Mail.',
'throttled-mailpassword'     => 'Es wurde innerhalb der letzten $1 Stunden bereits ein neues Passwort angefordert. Um einen Missbrauch der Funktion zu verhindern, kann nur alle $1 Stunden ein neues Passwort angefordert werden.',
'mailerror'                  => 'Fehler beim Senden der E-Mail: $1',
'acct_creation_throttle_hit' => 'Du hast schon $1 Benutzerkonten angelegt und kannst jetzt keine weiteren mehr anlegen.',
'emailauthenticated'         => 'Deine E-Mail-Adresse wurde bestätigt: $1.',
'emailnotauthenticated'      => 'Deine E-Mail-Adresse ist noch nicht bestätigt. Die folgenden E-Mail-Funktionen stehen erst nach erfolgreicher Bestätigung zur Verfügung.',
'noemailprefs'               => 'Gib eine E-Mail-Adresse an, damit die nachfolgenden Funktionen zur Verfügung stehen.',
'emailconfirmlink'           => 'E-Mail-Adresse bestätigen (authentifizieren).',
'invalidemailaddress'        => 'Die E-Mail-Adresse wurde nicht akzeptiert, da sie ein ungültiges Format aufzuweisen scheint. Bitte gib eine Adresse in einem gültigen Format ein oder leere das Feld.',
'accountcreated'             => 'Benutzerkonto erstellt',
'accountcreatedtext'         => 'Das Benutzerkonto $1 wurde eingerichtet.',
'createaccount-title'        => 'Erstellung eines Benutzerkontos für {{SITENAME}}',
'createaccount-text'         => 'Jemand ($1) hat ein Benutzerkonto "$2" auf {{SITENAME}}.
($4) erstellt. Das Passwort for "$2" ist "$3". Du solltest dich nun anmelden und dein Passwort ändern.

Du kannst diese Nachricht ignorieren, falls das Benutzerkonto durch einen Fehler angelegt wurde.',
'loginlanguagelabel'         => 'Sprache: $1',

# Password reset dialog
'resetpass'               => 'Passwort für Benutzerkonto zurücksetzen',
'resetpass_announce'      => 'Anmeldung mit dem per E-Mail zugesandten Code. Um die Anmeldung abzuschließen, musst du jetzt ein neues Passwort wählen.',
'resetpass_header'        => 'Passwort zurücksetzen',
'resetpass_submit'        => 'Passwort übermitteln und anmelden',
'resetpass_success'       => 'Dein Passwort wurde erfolgreich geändert. Es folgt die Anmeldung …',
'resetpass_bad_temporary' => 'Ungültiges vorläufiges Passwort. Du hast bereits dein Passwort erfolgreich geändert oder ein neues, vorläufiges Passwort angefordert.',
'resetpass_forbidden'     => 'Das Passwort kann in {{SITENAME}} nicht geändert werden.',
'resetpass_missing'       => 'Leeres Formular.',

# Edit page toolbar
'bold_sample'     => 'Fetter Text',
'bold_tip'        => 'Fetter Text',
'italic_sample'   => 'Kursiver Text',
'italic_tip'      => 'Kursiver Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Interner Link',
'extlink_sample'  => 'http://www.beispiel.de Link-Text',
'extlink_tip'     => 'Externer Link (http:// beachten)',
'headline_sample' => 'Ebene 2 Überschrift',
'headline_tip'    => 'Ebene 2 Überschrift',
'math_sample'     => 'Formel hier einfügen',
'math_tip'        => 'Mathematische Formel (LaTeX)',
'nowiki_sample'   => 'Unformatierten Text hier einfügen',
'nowiki_tip'      => 'Unformatierter Text',
'image_sample'    => 'Beispiel.jpg',
'image_tip'       => 'Bildlink',
'media_sample'    => 'Beispiel.ogg',
'media_tip'       => 'Mediendatei-Link',
'sig_tip'         => 'Deine Signatur mit Zeitstempel',
'hr_tip'          => 'Horizontale Linie (sparsam verwenden)',

# Edit pages
'summary'                   => 'Zusammenfassung',
'subject'                   => 'Betreff',
'minoredit'                 => 'Nur Kleinigkeiten wurden verändert',
'watchthis'                 => 'Diese Seite beobachten',
'savearticle'               => 'Seite speichern',
'preview'                   => 'Vorschau',
'showpreview'               => 'Vorschau zeigen',
'showlivepreview'           => 'Live-Vorschau',
'showdiff'                  => 'Änderungen zeigen',
'anoneditwarning'           => "Du bearbeitest diese Seite unangemeldet. Wenn du speicherst, wird deine aktuelle IP-Adresse in der Versionsgeschichte aufgezeichnet und ist damit unwiderruflich '''öffentlich''' einsehbar.",
'missingsummary'            => "'''Hinweis:''' Du hast keine Zusammenfassung angegeben. Wenn du erneut auf „Seite speichern“ klickst, wird deine Änderung ohne Zusammenfassung übernommen.",
'missingcommenttext'        => 'Bitte gib eine Zusammenfassung ein.',
'missingcommentheader'      => "'''ACHTUNG:''' Du hast keine Überschrift im Feld „Betreff:“ eingegeben. Wenn du erneut auf „Seite speichern“ klickst, wird deine Bearbeitung ohne Überschrift gespeichert.",
'summary-preview'           => 'Vorschau der Zusammenfassungszeile',
'subject-preview'           => 'Vorschau des Betreffs',
'blockedtitle'              => 'Benutzer ist gesperrt',
'blockedtext'               => 'Dein Benutzername oder deine IP-Adresse wurde von $1 gesperrt. Als Grund wurde angegeben:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Logbucheintrag]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>Ein Lesezugriff ist weiterhin möglich,</b> 
nur die Bearbeitung und Erstellung von Seiten in {{SITENAME}} wurde gesperrt.
Sollte diese Nachricht angezeigt werden, obwohl nur lesend zugriffen wurde, bist du einem (roten) Link auf eine noch nicht existente Seite gefolgt.</p>

Du kannst $1 oder einen der anderen [[{{MediaWiki:grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Bitte gib folgende Daten in jeder Anfrage an:\'\'\'
*Sperrender Administrator: $1
*Sperrgrund: $2
*Beginn der Sperre: $8
*Sperr-Ende: $6
*IP-Adresse: $3
*Sperre betrifft: $7
*Sperr-ID: #$5
</div>',
'autoblockedtext'           => 'Deine IP-Adresse wurde automatisch gesperrt, da sie von einem anderen Benutzer genutzt wurde, der durch $1 gesperrt wurde.
Als Grund wurde angegeben:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Logbucheintrag]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>Ein Lesezugriff ist weiterhin möglich,</b> 
nur die Bearbeitung und Erstellung von Seiten in {{SITENAME}} wurde gesperrt.
Sollte diese Nachricht angezeigt werden, obwohl nur lesend zugriffen wurde, bist du einem (roten) Link auf eine noch nicht existente Seite gefolgt.</p>

Du kannst $1 oder einen der anderen [[{{MediaWiki:grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Bitte gib folgende Daten in jeder Anfrage an:\'\'\'
*Sperrender Administrator: $1
*Sperrgrund: $2
*Beginn der Sperre: $8
*Sperr-Ende: $6
*IP-Adresse: $3
*Sperr-ID: #$5
</div>',
'blockednoreason'           => 'keine Begründung angegeben',
'blockedoriginalsource'     => "Der Quelltext von '''$1''' wird hier angezeigt:",
'blockededitsource'         => "Der Quelltext '''deiner Änderungen''' an '''$1''':",
'whitelistedittitle'        => 'Zum Bearbeiten ist es erforderlich, angemeldet zu sein',
'whitelistedittext'         => 'Du musst dich $1, um Seiten bearbeiten zu können.',
'whitelistreadtitle'        => 'Zum Lesen ist es erforderlich, angemeldet zu sein',
'whitelistreadtext'         => 'Du musst dich [[Special:Userlogin|hier anmelden]], um Seiten lesen zu können.',
'whitelistacctitle'         => 'Du bist nicht berechtigt, ein Benutzerkonto anzulegen.',
'whitelistacctext'          => 'Um in {{SITENAME}} Benutzer anlegen zu dürfen, musst du dich [[Special:Userlogin|hier anmelden]] und die nötigen Berechtigungen haben.',
'confirmedittitle'          => 'Zum Bearbeiten ist die E-Mail-Bestätigung erforderlich.',
'confirmedittext'           => 'Du musst deine E-Mail-Adresse erst bestätigen, bevor du bearbeiten kannst. Bitte ergänze und bestätige Deine E-Mail in den [[Special:Preferences|Einstellungen]].',
'nosuchsectiontitle'        => 'Abschnitt nicht vorhanden',
'nosuchsectiontext'         => 'Du versuchst den nicht vorhandenen Abschnitt $1 zu bearbeiten. Es können jedoch nur bereits vorhandene Abschnitte bearbeitet werden.',
'loginreqtitle'             => 'Anmeldung erforderlich',
'loginreqlink'              => 'anmelden',
'loginreqpagetext'          => 'Du musst dich $1, um Seiten lesen zu können.',
'accmailtitle'              => 'Passwort wurde verschickt',
'accmailtext'               => 'Das Passwort für den [[{{ns:user}}:$1]] wurde an $2 geschickt.',
'newarticle'                => '(Neu)',
'newarticletext'            => 'Hier den Text der neuen Seite eintragen. Bitte nur in ganzen Sätzen schreiben und keine urheberrechtsgeschützten Texte anderer kopieren.',
'anontalkpagetext'          => "---- ''Diese Seite dient dazu, einem nicht angemeldeten Benutzer Nachrichten zu hinterlassen. Wenn du mit den Kommentaren auf dieser Seite nichts anfangen kannst, richten sie sich vermutlich an einen früheren Inhaber deiner IP-Adresse und du kannst sie ignorieren.''",
'noarticletext'             => '(Diese Seite enthält momentan noch keinen Text)',
'clearyourcache'            => "'''Hinweis:''' Leere nach dem Speichern den Browser-Cache, um die Änderungen zu sehen: '''Mozilla/Firefox:''' ''Shift-Strg-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''⌘-R'', '''Konqueror:''' ''Strg-R''.",
'usercssjsyoucanpreview'    => '<strong>Tipp:</strong> Benutze den Vorschau-Button, um dein neues css/js vor dem Speichern zu testen.',
'usercsspreview'            => "== Vorschau Ihres Benutzer-CSS ==
'''Beachte:''' Nach dem Speichern musst du deinen Browser anweisen, die neue Version zu laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'             => "== Vorschau Ihres Benutzer-JavaScript ==
'''Beachte:''' Nach dem Speichern musst du deinen Browser anweisen, die neue Version zu laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'     => "'''Warnung:''' Es existiert kein Skin „$1“. Bitte bedenke, dass benutzerspezifische .css- und .js-Seiten mit einem Kleinbuchstaben anfangen müssen, also z.B. ''Benutzer:Mustermann/monobook.css'' an Stelle von ''Benutzer:Mustermann/Monobook.css''.",
'updated'                   => '(Geändert)',
'note'                      => '<strong>Hinweis:</strong>',
'previewnote'               => 'Dies ist nur eine Vorschau, die Seite wurde noch nicht gespeichert!',
'previewconflict'           => 'Diese Vorschau gibt den Inhalt des oberen Textfeldes wieder; so wird die Seite aussehen, wenn du jetzt speicherst.',
'session_fail_preview'      => '<strong>Deine Bearbeitung konnte nicht gespeichert werden, da deine Sitzungsdaten verloren gegangen sind.
Bitte versuche es erneut, indem du unter der folgenden Textvorschau nochmals auf „Seite speichern“ klickst. 
Sollte das Problem bestehen bleiben, melde dich ab und danach wieder an.</strong>',
'session_fail_preview_html' => "<strong>Deine Bearbeitung konnte nicht gespeichert werden, da deine Sitzungsdaten verloren gegangen sind.</strong>

''Da in diesem Wiki reines HTML aktiviert ist, wurde die Vorschau ausgeblendet um JavaScript Attacken vorzubeugen.''

<strong>Bitte versuche es erneut, indem du unter der folgenden Textvorschau nochmals auf „Seite speichern“ klickst. Sollte das Problem bestehen bleiben, melde dich ab und danach wieder an.</strong>",
'token_suffix_mismatch'     => '<strong>Deine Bearbeitung wurde zurückgewiesen, da dein Browser Zeichen im Bearbeiten-Token verstümmelt hat.
Eine Speicherung kann den Seiteninhalt zerstören. Dies geschieht bisweilen durch die Benutzung eines anonymen Proxy-Dienstes, der fehlerhaft arbeitet.</strong>',
'editing'                   => 'Bearbeiten von $1',
'editinguser'               => 'Bearbeiten von Benutzer <b>$1</b>',
'editingsection'            => 'Bearbeiten von $1 (Absatz)',
'editingcomment'            => 'Bearbeiten von $1 (Kommentar)',
'editconflict'              => 'Bearbeitungskonflikt: $1',
'explainconflict'           => 'Jemand anders hat diese Seite geändert, nachdem du angefangen hast diese zu bearbeiten.
Das obere Textfeld enthält den aktuellen Stand.
Das untere Textfeld enthält deine Änderungen.
Bitte füge deine Änderungen in das obere Textfeld ein.
<b>Nur</b> der Inhalt des oberen Textfeldes wird gespeichert, wenn du auf „Seite speichern“ klickst!<br />',
'yourtext'                  => 'Dein Text',
'storedversion'             => 'Gespeicherte Version',
'nonunicodebrowser'         => '<strong>Achtung:</strong> Dein Browser kann Unicode-Zeichen nicht richtig verarbeiten. Bitte verwende einen anderen Browser um Seiten zu bearbeiten.',
'editingold'                => '<strong>ACHTUNG: Du bearbeitest eine alte Version dieser Seite. Wenn du speicherst, werden alle neueren Versionen überschrieben.</strong>',
'yourdiff'                  => 'Unterschiede',
'copyrightwarning'          => '<strong>Bitte <big>kopiere keine Webseiten</big>, die nicht deine eigenen sind, benutzen <big>keine urheberrechtlich geschützten Werke</big> ohne Erlaubnis des Copyright-Inhabers!</strong><br />
Du gibst uns hiermit deine Zusage, dass du den Text <strong>selbst verfasst</strong> haben, dass der Text Allgemeingut (<strong>public domain</strong>) ist, oder dass der <strong>Copyright-Inhaber</strong> seine <strong>Zustimmung</strong> gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weise bitte auf der Diskussionsseite darauf hin.
<i>Bitte beachte, dass alle {{SITENAME}}-Beiträge automatisch unter der „$2“ stehen (siehe $1 für Details). Falls du nicht möchtest, dass deine Arbeit hier von anderen verändert und verbreitet wird, dann drücke nicht auf „Seite speichern“.</i>',
'copyrightwarning2'         => 'Bitte beachte, dass alle Beiträge zu {{SITENAME}} von anderen Mitwirkenden bearbeitet, geändert oder gelöscht werden können.
Reiche hier keine Texte ein, falls du nicht willst, dass diese ohne Einschränkung geändert werden können.

Du bestätigst hiermit auch, dass du diese Texte selbst geschrieben hast oder diese von einer gemeinfreien Quelle kopiert habst
(siehe $1 für weitere Details). <strong>ÜBERTRAGE OHNE GENEHMIGUNG KEINE URHEBERRECHTLICH GESCHÜTZTEN INHALTE!</strong>',
'longpagewarning'           => '<strong>WARNUNG: Diese Seite ist $1 kB groß; einige Browser könnten Probleme haben, Seiten zu bearbeiten, die größer als 32 KB sind.
Überlege bitte, ob eine Aufteilung der Seite in kleinere Abschnitte möglich ist.</strong>',
'longpageerror'             => '<strong>FEHLER: Der Text, den du zu speichern versuchst, ist $1 KB groß. Das ist größer als das erlaubte Maximum von $2 KB – Speicherung nicht möglich.</strong>',
'readonlywarning'           => '<strong>WARNUNG: Die Datenbank wurde während der Seitenbearbeitung für Wartungsarbeiten gesperrt, so dass du die Seite im Moment nicht
speichern kannst. Sichere den Text und versuche die Änderungen später einzuspielen.</strong>',
'protectedpagewarning'      => "'''ACHTUNG: Diese Seite wurde gesperrt, so dass sie nur durch Benutzer mit Administratorrechten bearbeitet werden kann.'''",
'semiprotectedpagewarning'  => "'''Halbsperrung:''' Die Seite wurde so gesperrt, dass nur registrierte Benutzer diese ändern können.",
'cascadeprotectedwarning'   => "'''ACHTUNG: Diese Seite wurde gesperrt, so dass sie nur durch Benutzer mit Administratorrechten bearbeitet werden kann. Sie ist in die {{PLURAL:$1|folgende Seite|folgenden Seiten}} eingebunden, die mittels der Kaskadensperroption geschützt {{PLURAL:$1|ist|sind}}:'''",
'templatesused'             => 'Folgende Vorlagen werden von dieser Seite verwendet:',
'templatesusedpreview'      => 'Folgende Vorlagen werden von dieser Seitenvorschau verwendet:',
'templatesusedsection'      => 'Folgende Vorlagen werden von diesem Abschnitt verwendet:',
'template-protected'        => '(schreibgeschützt)',
'template-semiprotected'    => '(schreibgeschützt für unangemeldete und neue Benutzer)',
'edittools'                 => '<!-- Dieser Text wird unter dem „Bearbeiten“-Formular sowie dem "Hochladen"-Formular angezeigt. -->',
'nocreatetitle'             => 'Die Erstellung neuer Seiten ist eingeschränkt.',
'nocreatetext'              => 'Der Server hat das Erstellen neuer Seiten eingeschränkt. Du kannst bestehende Seiten ändern oder dich [[Special:Userlogin|anmelden]].',
'nocreate-loggedin'         => 'Du hast keine Berechtigung, neue Seiten in diesem Wiki anzulegen.',
'permissionserrors'         => 'Berechtigungs-Fehler',
'permissionserrorstext'     => 'Du bist nicht berechtigt, die Aktion auszuführen. {{PLURAL:$1|Grund|Gründe}}:',
'recreate-deleted-warn'     => "'''Achtung: Du erstellst eine Seite, die bereits früher gelöscht wurde.'''
 
Bitte prüfe sorgfältig, ob die erneute Seitenerstellung den Richtlinien entspricht.
Zu Deiner Information folgt das Lösch-Logbuch mit der Begründung für die vorhergehende Löschung:",

# "Undo" feature
'undo-success' => 'Die Änderung konnte erfolgreich rückgängig gemacht werden. Bitte die Bearbeitung in der Vergleichsansicht kontrollieren und dann auf „Seite speichern“ klicken, um sie zu speichern.',
'undo-failure' => '<span class="error">Die Änderung konnte nicht rückgängig gemacht werden, da der betroffene Abschnitt zwischenzeitlich verändert wurde.</span>',
'undo-summary' => 'Änderung $1 von [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|Diskussion]]) wurde rückgängig gemacht.',

# Account creation failure
'cantcreateaccounttitle' => 'Benutzerkonto kann nicht erstellt werden',
'cantcreateaccount-text' => "Die Erstellung eines Benutzerkontos von der IP-Adresse <b>$1</b> aus wurde von [[User:$3|$3]] gesperrt.

Grund der Sperre: ''$2''",

# History pages
'revhistory'          => 'Frühere Versionen',
'viewpagelogs'        => 'Logbücher für diese Seite anzeigen',
'nohistory'           => 'Es gibt keine früheren Versionen dieser Seite.',
'revnotfound'         => 'Diese Version wurde nicht gefunden.',
'revnotfoundtext'     => 'Die Version dieser Seite, nach der du suchst, konnte nicht gefunden werden. Bitte überprüfe die URL dieser Seite.',
'loadhist'            => 'Lade Liste mit früheren Versionen',
'currentrev'          => 'Aktuelle Version',
'revisionasof'        => 'Version vom $1',
'revision-info'       => 'Version vom $1 von $2',
'previousrevision'    => '← Nächstältere Version',
'nextrevision'        => 'Nächstjüngere Version →',
'currentrevisionlink' => 'Aktuelle Version',
'cur'                 => 'Aktuell',
'next'                => 'Nächste',
'last'                => 'Vorherige',
'orig'                => 'Original',
'page_first'          => 'Anfang',
'page_last'           => 'Ende',
'histlegend'          => 'Zur Anzeige der Änderungen einfach die zu vergleichenden Versionen auswählen und die Schaltfläche „{{int:compareselectedversions}}“ klicken.<br />
* (Aktuell) = Unterschied zur aktuellen Version, (Vorherige) = Unterschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dieser Zeit, Benutzername/IP-Adresse des Bearbeiters, K = Kleine Änderung',
'deletedrev'          => '[gelöscht]',
'histfirst'           => 'Älteste',
'histlast'            => 'Neueste',
'historysize'         => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'        => '(leer)',

# Revision feed
'history-feed-title'          => 'Versionsgeschichte',
'history-feed-description'    => 'Versionsgeschichte für diese Seite in {{SITENAME}}',
'history-feed-item-nocomment' => '$1 um $2', # user at time
'history-feed-empty'          => 'Die angeforderte Seite existiert nicht. Vielleicht wurde sie gelöscht oder verschoben. [[Special:Search|Durchsuche]] {{SITENAME}} für passende neue Seiten.',

# Revision deletion
'rev-deleted-comment'         => '(Bearbeitungskommentar entfernt)',
'rev-deleted-user'            => '(Benutzername entfernt)',
'rev-deleted-event'           => '(Aktion entfernt)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Diese Version wurde gelöscht und ist nicht mehr öffentlich einsehbar.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Diese Version wurde gelöscht und ist nicht mehr öffentlich einsehbar.
Als Administrator kannst du sie weiterhin einsehen.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-delundel'                => 'zeige/verstecke',
'revisiondelete'              => 'Versionen löschen/wiederherstellen',
'revdelete-nooldid-title'     => 'Keine Version angegeben',
'revdelete-nooldid-text'      => 'Du hast keine Version angegeben, auf die diese Aktion ausgeführt werden soll.',
'revdelete-selected'          => "{{PLURAL:$2|Ausgewählte Version|Ausgewählte Versionen}} von '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Ausgewählter Logbucheintrag|Ausgewählte Logbucheinträge}} für '''$1:'''",
'revdelete-text'              => 'Der Inhalt oder andere Bestandteile gelöschter Versionen sind nicht mehr öffentlich einsehbar, erscheinen jedoch weiterhin als Einträge in der Versionsgeschichte. 

Administratoren können den entfernten Inhalt oder andere entfernte Bestandteile weiterhin einsehen und wiederherstellen, es sei denn, es wurde festgelegt, dass die Zugangsbeschränkungen auch für Administratoren gelten.',
'revdelete-legend'            => 'Einschränkungen für die Versionen festlegen:',
'revdelete-hide-text'         => 'Text der Version verstecken',
'revdelete-hide-name'         => 'Logbuch-Aktion verstecken',
'revdelete-hide-comment'      => 'Bearbeitungskommentar verstecken',
'revdelete-hide-user'         => 'Benutzernamen/die IP des Bearbeiters verstecken',
'revdelete-hide-restricted'   => 'Diese Einschränkungen gelten auch für Administratoren',
'revdelete-suppress'          => 'Grund der Löschung auch für Administratoren versteckt',
'revdelete-hide-image'        => 'Bildinhalt verstecken',
'revdelete-unsuppress'        => 'Einschränkungen für wiederhergestellte Versionen aufheben',
'revdelete-log'               => 'Kommentar/Begründung (erscheint im Logbuch):',
'revdelete-submit'            => 'Auf ausgewählte Version anwenden',
'revdelete-logentry'          => 'Versionsansicht geändert für [[$1]]',
'logdelete-logentry'          => 'änderte die Sichtbarkeit für [[$1]]',
'revdelete-logaction'         => '$1 {{plural:$1|Version|Versionen}} auf Modus $2 gesetzt',
'logdelete-logaction'         => '$1 {{plural:$1|Eintrag|Einträge}} für [[$3]] auf Modus $2 gesetzt',
'revdelete-success'           => 'Versionsansicht erfolgreich geändert.',
'logdelete-success'           => 'Logbuch-Aktion erfolgreich gesetzt.',

# Oversight log
'oversightlog'    => 'Oversight-Logbuch',
'overlogpagetext' => 'Das ist das Logbuch der Löschungen und Sperren, die vor Administratoren versteckt sind.',

# Diffs
'history-title'             => 'Versionsgeschichte von „$1“',
'difference'                => '(Unterschied zwischen Versionen)',
'loadingrev'                => 'Lade Versionen zur Unterscheidung',
'lineno'                    => 'Zeile $1:',
'editcurrent'               => 'Die aktuelle Version dieser Seite bearbeiten',
'selectnewerversionfordiff' => 'Eine neuere Version zum Vergleich auswählen',
'selectolderversionfordiff' => 'Eine ältere Version zum Vergleich auswählen',
'compareselectedversions'   => 'Gewählte Versionen vergleichen',
'editundo'                  => 'rückgängig',
'diff-multi'                => "<span style='font-size: smaller'>(Der Versionsvergleich bezieht {{plural:$1|eine dazwischen liegende Version|$1 dazwischen liegende Versionen}} mit ein.)</span>",

# Search results
'searchresults'         => 'Suchergebnisse',
'searchresulttext'      => 'Für mehr Informationen zur Suche siehe die [[{{MediaWiki:helppage}}|Hilfeseite]].',
'searchsubtitle'        => 'Für deine Suchanfrage „[[:$1|$1]]“.',
'searchsubtitleinvalid' => 'Für deine Suchanfrage „$1“.',
'noexactmatch'          => "'''Es existiert keine Seite mit dem Titel „$1“.'''

Versuche es über die Volltextsuche.
Alternativ kannst du auch den [[Special:Allpages|alphabetischen Index]] nach ähnlichen Begriffen durchsuchen.

Wenn du dich mit dem Thema auskennen, kannst du selbst die Seite „[[$1]]“ verfassen.",
'titlematches'          => 'Übereinstimmungen mit Seitentiteln',
'notitlematches'        => 'Keine Übereinstimmungen mit Seitentiteln',
'textmatches'           => 'Übereinstimmungen mit Inhalten',
'notextmatches'         => 'Keine Übereinstimmungen mit Inhalten',
'prevn'                 => 'vorherige $1',
'nextn'                 => 'nächste $1',
'viewprevnext'          => 'Zeige ($1) ($2) ($3)',
'showingresults'        => "Hier {{PLURAL:$1|ist '''1''' Ergebnis|sind '''$1''' Ergebnisse}}, beginnend mit Nummer '''$2'''.",
'showingresultsnum'     => "Hier {{PLURAL:$3|ist '''1''' Ergebnis|sind '''$1''' Ergebnisse}}, beginnend mit Nummer '''$2'''.",
'nonefound'             => '<strong>Hinweis</strong>: Erfolglose Suchanfragen werden häufig dadurch verursacht, dass mehr als ein Suchbegriff angegeben wurde. Nur Seiten die alle Suchbegriffe enthalten werden hier angezeigt. Versuche in diesem Fall die Anzahl der Suchbegriffe zu verringern.',
'powersearch'           => 'Suche',
'powersearchtext'       => 'Suche in Namensräumen:<br />$1<br />$2 Weiterleitungen anzeigen<br />Suche nach: $3 $9',
'searchdisabled'        => 'Die {{SITENAME}} Suche wurde deaktiviert. Du kannst unterdessen über Google suchen. Bitte bedenken, dass der Suchindex für {{SITENAME}} veraltet sein kann.',

# Preferences page
'preferences'              => 'Einstellungen',
'preferences-summary'      => 'Auf dieser Spezialseite kannst du deine Zugangsdaten ändern und bestimmte Teile der Oberfläche individuell anpassen ',
'mypreferences'            => 'Einstellungen',
'prefs-edits'              => 'Anzahl Bearbeitungen:',
'prefsnologin'             => 'Nicht angemeldet',
'prefsnologintext'         => 'Du musst [[Special:Userlogin|angemeldet]] sein, um deine Einstellungen ändern zu können.',
'prefsreset'               => 'Die Eingaben wurden verworfen, es erfolgte keine Speicherung.',
'qbsettings'               => 'Seitenleiste',
'qbsettings-none'          => 'Keine',
'qbsettings-fixedleft'     => 'Links, fest',
'qbsettings-fixedright'    => 'Rechts, fest',
'qbsettings-floatingleft'  => 'Links, schwebend',
'qbsettings-floatingright' => 'Rechts, schwebend',
'changepassword'           => 'Passwort ändern',
'skin'                     => 'Skin',
'math'                     => 'TeX',
'dateformat'               => 'Datumsformat',
'datedefault'              => 'Standard',
'datetime'                 => 'Datum und Zeit',
'math_failure'             => 'Parser-Fehler',
'math_unknown_error'       => 'Unbekannter Fehler',
'math_unknown_function'    => 'Unbekannte Funktion',
'math_lexing_error'        => "'Lexing'-Fehler",
'math_syntax_error'        => 'Syntaxfehler',
'math_image_error'         => 'die PNG-Konvertierung schlug fehl',
'math_bad_tmpdir'          => 'Das temporäre Verzeichnis für mathematische Formeln kann nicht angelegt oder beschrieben werden.',
'math_bad_output'          => 'Das Zielverzeichnis für mathematische Formeln kann nicht angelegt oder beschrieben werden.',
'math_notexvc'             => 'Das texvc-Programm wurde nicht gefunden. Bitte math/README beachten.',
'prefs-personal'           => 'Benutzerdaten',
'prefs-rc'                 => 'Anzeige von „Letzte Änderungen“',
'prefs-watchlist'          => 'Beobachtungsliste',
'prefs-watchlist-days'     => 'Maximale Anzahl der Tage, die die Beobachtungsliste standardmäßig umfassen soll:',
'prefs-watchlist-edits'    => 'Maximale Anzahl der Einträge in der erweiterten Beobachtungsliste:',
'prefs-misc'               => 'Verschiedenes',
'saveprefs'                => 'Einstellungen speichern',
'resetprefs'               => 'Eingaben verwerfen',
'oldpassword'              => 'Altes Passwort:',
'newpassword'              => 'Neues Passwort:',
'retypenew'                => 'Neues Passwort (nochmal):',
'textboxsize'              => 'Bearbeiten',
'rows'                     => 'Zeilen',
'columns'                  => 'Spalten',
'searchresultshead'        => 'Suche',
'resultsperpage'           => 'Treffer pro Seite:',
'contextlines'             => 'Zeilen pro Treffer:',
'contextchars'             => 'Zeichen pro Zeile:',
'stub-threshold'           => 'Linkformatierung <a href="#" class="stub">kleiner Seiten</a> (in Byte):',
'recentchangesdays'        => 'Anzahl der Tage, die die Liste der „Letzten Änderungen“ standardmäßig umfassen soll:',
'recentchangescount'       => 'Anzahl der Einträge in „Letzte Änderungen“ und „Neue Seiten“:',
'savedprefs'               => 'Deine Einstellungen wurden gespeichert.',
'timezonelegend'           => 'Zeitzone',
'timezonetext'             => 'Gib die Anzahl der Stunden ein, die zwischen Deiner Zeitzone und UTC liegen.',
'localtime'                => 'Ortszeit:',
'timezoneoffset'           => 'Unterschied¹:',
'servertime'               => 'Aktuelle Zeit auf dem Server:',
'guesstimezone'            => 'Vom Browser übernehmen',
'allowemail'               => 'E-Mail-Empfang von anderen Benutzern ermöglichen.',
'defaultns'                => 'In diesen Namensräumen soll standardmäßig gesucht werden:',
'default'                  => 'Voreinstellung',
'files'                    => 'Dateien',

# User rights
'userrights-lookup-user'      => 'Verwalte Gruppenzugehörigkeit',
'userrights-user-editname'    => 'Benutzername:',
'editusergroup'               => 'Benutzerrechte bearbeiten',
'userrights-editusergroup'    => 'Bearbeite Gruppenzugehörigkeit des Benutzers',
'saveusergroups'              => 'Gruppenzugehörigkeit speichern',
'userrights-groupsmember'     => 'Mitglied von:',
'userrights-groupsavailable'  => 'Verfügbare Gruppen:',
'userrights-groupshelp'       => "Wähle die Gruppen, aus denen der Benutzer entfernt oder zu denen er hinzugefügt werden soll.
Nicht selektierte Gruppen werden nicht geändert. Eine Selektion kann mit '''Strg + Linksklick''' (bzw. Ctrl + Linksklick) entfernt werden.",
'userrights-reason'           => 'Grund:',
'userrights-available-none'   => 'Du darst keine Benutzerrechte verändern.',
'userrights-available-add'    => 'Du darst Benutzer den Grupppen $1 hinzufügen.',
'userrights-available-remove' => 'Du darst Benutzer aus den Grupppen $1 entfernen.',

# Groups
'group'               => 'Gruppe:',
'group-autoconfirmed' => 'Bestätigte Benutzer',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratoren',
'group-bureaucrat'    => 'Bürokraten',
'group-all'           => '(alle)',

'group-autoconfirmed-member' => 'Bestätigter Benutzer',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bürokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Bestätigte Benutzer',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratoren',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',

# User rights log
'rightslog'      => 'Rechte-Logbuch',
'rightslogtext'  => 'Dies ist das Logbuch der Änderungen der Benutzerrechte.',
'rightslogentry' => 'änderte die Gruppenzugehörigkeit für „[[$1]]“ von „$2“ auf „$3“.',
'rightsnone'     => '(-)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Änderung|Änderungen}}',
'recentchanges'                     => 'Letzte Änderungen',
'recentchangestext'                 => "Auf dieser Seite kannst du die letzten Änderungen auf '''{{SITENAME}}''' nachverfolgen.",
'recentchanges-feed-description'    => 'Verfolge mit diesem Feed die letzten Änderungen in {{SITENAME}}.',
'rcnote'                            => "Angezeigt {{PLURAL:$1|wird '''1''' Änderung|werden die letzten '''$1''' Änderungen}} {{PLURAL:$2|des letzten Tages|der letzten '''$2''' Tage}}. Stand: $3. (<b><tt>Neu</tt></b>&nbsp;– neuer Eintrag; <b><tt>K</tt></b>&nbsp;– kleine Änderung; <b><tt>B</tt></b>&nbsp;– Änderung durch einen Bot; ''(± Zahl)''&nbsp;– Größenänderung in Byte)",
'rcnotefrom'                        => 'Angezeigt werden die Änderungen seit <b>$2</b> (max. <b>$1</b> Einträge).',
'rclistfrom'                        => 'Nur Änderungen seit $1 zeigen.',
'rcshowhideminor'                   => 'Kleine Änderungen $1',
'rcshowhidebots'                    => 'Bots $1',
'rcshowhideliu'                     => 'Angemeldete Benutzer $1',
'rcshowhideanons'                   => 'Anonyme Benutzer $1',
'rcshowhidepatr'                    => 'Überprüfte Änderungen $1',
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

# Recent changes linked
'recentchangeslinked'          => 'Änderungen an verlinkten Seiten',
'recentchangeslinked-title'    => 'Änderungen an Seiten, die von „$1“ verlinkt sind',
'recentchangeslinked-noresult' => 'Im ausgewählten Zeitraum wurden an den verlinkten Seiten keine Änderungen vorgenommen.',
'recentchangeslinked-summary'  => "Diese Spezialseite listet die letzten Änderungen der verlinkten Seiten auf. Seiten auf deiner Beobachtungsliste sind '''fett''' geschrieben.",

# Upload
'upload'                      => 'Hochladen',
'uploadbtn'                   => 'Datei hochladen',
'reupload'                    => 'Abbrechen',
'reuploaddesc'                => 'Zurück zur Hochladen-Seite.',
'uploadnologin'               => 'Nicht angemeldet',
'uploadnologintext'           => 'Du musst [[Special:Userlogin|angemeldet sein]], um Dateien hochladen zu können.',
'upload_directory_read_only'  => 'Der Webserver hat keine Schreibrechte für das Upload-Verzeichnis ($1).',
'uploaderror'                 => 'Fehler beim Hochladen',
'uploadtext'                  => "Gehe zu der [[Special:Imagelist|Liste hochgeladener Dateien]], um vorhandene Dateien zu suchen und anzuzeigen.

Benutze dieses Formular, um neue Dateien hochzuladen. Klicke auf '''„Durchsuchen...“''', um einen Dateiauswahl-Dialog zu öffnen.
Nach der Auswahl einer Datei wird der Dateiname im Textfeld '''„Quelldatei“''' angezeigt.
Bestätige dann die Lizenz-Vereinbarung und klicke anschließend auf '''„Datei hochladen“'''.
Dies kann eine Weile dauern, besonders bei einer langsamen Internet-Verbindung.

Um ein '''Bild''' in einer Seite zu verwenden, schreibe an Stelle des Bildes zum Beispiel:
* '''<tt><nowiki>[[</nowiki>{{ns:image}}:Datei.jpg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:image}}:Datei.jpg|Link-Text<nowiki>]]</nowiki></tt>'''

Um '''Mediendateien''' einzubinden, verwende zum Beispiel:
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Datei.ogg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Datei.ogg|Link-Text<nowiki>]]</nowiki></tt>'''

Bitte beachte, dass, genau wie bei normalen Seiteninhalten, andere Benutzer deine Dateien löschen oder verändern können.",
'uploadlog'                   => 'Datei-Logbuch',
'uploadlogpage'               => 'Datei-Logbuch',
'uploadlogpagetext'           => 'Dies ist das Logbuch der hochgeladenen Dateien, siehe auch [[{{ns:special}}:Newimages]].',
'filename'                    => 'Dateiname',
'filedesc'                    => 'Beschreibung',
'fileuploadsummary'           => 'Beschreibung/Quelle:',
'filestatus'                  => 'Copyright-Status',
'filesource'                  => 'Quelle',
'uploadedfiles'               => 'Hochgeladene Dateien',
'ignorewarning'               => 'Warnung ignorieren und Datei speichern.',
'ignorewarnings'              => 'Warnungen ignorieren',
'minlength1'                  => 'Dateinamen müssen mindestens einen Buchstaben lang sein.',
'illegalfilename'             => 'Der Dateiname „$1“ enthält mindestens ein nicht erlaubtes Zeichen. Bitte benenne die Datei um und versuche sie erneut hochzuladen.',
'badfilename'                 => 'Der Dateiname wurde in „$1“ geändert.',
'filetype-badmime'            => 'Dateien mit dem MIME-Typ „$1“ dürfen nicht hochgeladen werden.',
'filetype-badtype'            => "'''„.$1“''' ist ein unerwünschtes Dateiformat.
: Erlaubt sind: $2",
'filetype-missing'            => 'Die hochzuladende Datei hat keine Erweiterung (z. B. „.jpg“).',
'large-file'                  => 'Die Dateigröße sollte nach Möglichkeit $1 nicht überschreiten. Diese Datei ist $2 groß.',
'largefileserver'             => 'Die Datei ist größer als die vom Server eingestellte Maximalgröße.',
'emptyfile'                   => 'Die hochgeladene Datei ist leer. Der Grund kann ein Tippfehler im Dateinamen sein. Bitte kontrolliere, ob du die Datei wirklich hochladen willst.',
'fileexists'                  => 'Eine Datei mit diesem Namen existiert bereits. Wenn du auf „Datei speichern“ klickst, wird die Datei überschrieben. Bitte prüfe <strong><tt>$1</tt></strong>, wenn du dir nicht sicher bist.',
'fileexists-extension'        => 'Eine Datei mit ähnlichem Namen existiert bereits:<br />
Name der hochzuladenden Datei: <strong><tt>$1</tt></strong><br />
Name der vorhandenen Datei: <strong><tt>$2</tt></strong><br />
Nur die Dateiendung unterscheidet sich in Groß-/Kleinschreibung. Bitte prüfe, ob die Dateien inhaltlich identisch sind.',
'fileexists-thumb'            => "<center>'''Vorhandenes Bild'''</center>",
'fileexists-thumbnail-yes'    => 'Bei der Datei scheint es sich um ein Bild verringerter Größe <i>(thumbnail)</i> zu handeln. Bitte prüfe die Datei <strong><tt>$1</tt></strong>.<br />
Wenn es sich um das Bild in Originalgröße handelt, so braucht kein separates Vorschaubild hochgeladen zu werden.',
'file-thumbnail-no'           => 'Der Dateiname beginnt mit <strong><tt>$1</tt></strong>. Dies deutet auf ein Bild verringerter Größe <i>(thumbnail)</i> hin.
Bitte prüfe, ob du das Bild in voller Auflösung vorliegen hast und lade dieses unter dem Originalnamen hoch.',
'fileexists-forbidden'        => 'Mit diesem Namen existiert bereits eine Datei. Bitte gehe zurück und lade diese Datei unter einem anderen Namen hoch. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Mit diesem Namen existiert bereits eine Datei. Bitte gehe zurück und lade diese Datei unter einem anderen Namen hoch. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Erfolgreich hochgeladen',
'uploadwarning'               => 'Warnung',
'savefile'                    => 'Datei speichern',
'uploadedimage'               => 'hat „[[$1]]“ hochgeladen',
'overwroteimage'              => 'hat eine neue Version von „[[$1]]“ hochgeladen',
'uploaddisabled'              => 'Entschuldigung, das Hochladen ist deaktiviert.',
'uploaddisabledtext'          => 'Das Hochladen von Dateien ist in {{SITENAME}} deaktiviert.',
'uploadscripted'              => 'Diese Datei enthält HTML- oder Scriptcode, der irrtümlich von einem Webbrowser ausgeführt werden könnte.',
'uploadcorrupt'               => 'Die Datei ist beschädigt oder hat eine falsche Datei-Erweiterung. Bitte überprüfe die Datei und wiederhole den Hochlade-Vorgang.',
'uploadvirus'                 => 'Diese Datei enthält einen Virus! Details: $1',
'sourcefilename'              => 'Quelldatei',
'destfilename'                => 'Zielname',
'watchthisupload'             => 'Diese Seite beobachten',
'filewasdeleted'              => 'Eine Datei mit diesem Namen wurde schon einmal hochgeladen und zwischenzeitlich wieder gelöscht. Bitte prüfe zuerst den Eintrag im $1, bevor du die Datei wirklich speicherst.',
'upload-wasdeleted'           => "'''Achtung: Du lädst eine Datei hoch, die bereits früher gelöscht wurde.'''
 
Bitte prüfe sorgfältig, ob das erneute Hochladen den Richtlinien entspricht.
Zu Deiner Information folgt das Lösch-Logbuch mit der Begründung für die vorhergehende Löschung:",
'filename-bad-prefix'         => 'Der Dateiname beginnt mit <strong>„$1“</strong>. Dies ist im allgemeinen der von einer Digitalkamera vorgegebener Dateiname und daher nicht sehr aussagekräftig.
Bitte gebe der Datei einen Namen, der den Inhalt besser beschreibt.',

'upload-proto-error'      => 'Falsches Protokoll',
'upload-proto-error-text' => 'Die URL muss mit <code>http://</code> oder <code>ftp://</code> beginnen.',
'upload-file-error'       => 'Interner Fehler',
'upload-file-error-text'  => 'Bei der Erstellung einer temporären Datei auf dem Server ist ein interner Fehler aufgetreten. Bitte informiere einen System-Administrator.',
'upload-misc-error'       => 'Unbekannter Fehler beim Hochladen',
'upload-misc-error-text'  => 'Beim Hochladen ist ein unbekannter Fehler aufgetreten. Prüfe die URL auf Fehler, den Online-Status der Seite und versuche es erneut. Wenn das Problem weiterbesteht, informiere einen System-Administrator.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL ist nicht erreichbar',
'upload-curl-error6-text'  => 'Die angegebene URL ist nicht erreichbar. Prüfe sowohl die URL auf Fehler als auch den Online-Status der Seite.',
'upload-curl-error28'      => 'Zeitüberschreitung beim Hochladen',
'upload-curl-error28-text' => 'Die Seite braucht zu lange für eine Antwort. Prüfe, ob die Seite online ist, warte einen kurzen Moment und versuche es dann erneut. Es kann sinnvoll sein, einen erneuten Versuch zu einer anderen Zeit zu probieren..',

'license'            => 'Lizenz',
'nolicense'          => 'keine Vorauswahl',
'license-nopreview'  => '(es ist keine Vorschau verfügbar)',
'upload_source_url'  => ' (gültige, öffentlich zugängliche URL)',
'upload_source_file' => ' (eine Datei auf Ihrem Computer)',

# Image list
'imagelist'                 => 'Dateiliste',
'imagelist-summary'         => 'Diese Spezialseite listet alle hochgeladenen Dateien auf. Standardmäßig werden die zuletzt hochgeladenen Dateien zuerst angezeigt. Durch einen Klick auf die Spaltenüberschriften kann die Sortierung umgedreht werden oder es kann nach einer anderen Spalte sortiert werden.',
'imagelisttext'             => "Hier ist eine Liste von '''$1''' {{PLURAL:$1|Datei|Dateien}}, sortiert $2.",
'getimagelist'              => 'Lade Dateiliste',
'ilsubmit'                  => 'Suchen',
'showlast'                  => 'Zeige die letzten $1 Dateien, sortiert nach $2.',
'byname'                    => 'nach Name',
'bydate'                    => 'nach Datum',
'bysize'                    => 'nach Größe',
'imgdelete'                 => 'Löschen',
'imgdesc'                   => 'Beschreibung',
'imgfile'                   => 'Datei',
'filehist'                  => 'Dateiversionen',
'filehist-help'             => 'Klicke auf einen Zeitpunkt, um diese Version zu laden.',
'filehist-deleteall'        => 'Alle Versionen löschen',
'filehist-deleteone'        => 'Diese Version löschen',
'filehist-revert'           => 'zurücksetzen',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Version vom',
'filehist-user'             => 'Benutzer',
'filehist-dimensions'       => 'Maße',
'filehist-filesize'         => 'Dateigröße',
'filehist-comment'          => 'Kommentar',
'imagelinks'                => 'Verwendung',
'linkstoimage'              => 'Die folgenden Seiten benutzen diese Datei:',
'nolinkstoimage'            => 'Keine Seite benutzt diese Datei.',
'sharedupload'              => 'Diese Datei ist ein gemeinsam genutzter Upload und kann von anderen Projekten verwendet werden.',
'shareduploadwiki'          => 'Für weitere Informationen siehe $1.',
'shareduploadwiki-linktext' => 'Datei-Beschreibungsseite',
'noimage'                   => 'Eine Datei mit diesem Namen existiert nicht, du kannst sie jedoch $1.',
'noimage-linktext'          => 'hochladen',
'uploadnewversion-linktext' => 'Eine neue Version dieser Datei hochladen',
'imagelist_date'            => 'Datum',
'imagelist_name'            => 'Name',
'imagelist_user'            => 'Benutzer',
'imagelist_size'            => 'Größe',
'imagelist_description'     => 'Beschreibung',
'imagelist_search_for'      => 'Suche nach Datei:',

# File reversion
'filerevert'                => 'Zurücksetzen von „$1“',
'filerevert-legend'         => 'Datei zurücksetzen',
'filerevert-intro'          => '<span class="plainlinks">Du setzt die Datei \'\'\'[[Media:$1|$1]]\'\'\' auf die [$4 Version vom $2, $3 Uhr] zurück.</span>',
'filerevert-comment'        => 'Grund:',
'filerevert-defaultcomment' => 'zurückgesetzt auf die Version vom $1, $2 Uhr',
'filerevert-submit'         => 'Zurücksetzen',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' wurde auf die [$4 Version vom $2, $3 Uhr] zurückgesetzt.</span>',
'filerevert-badversion'     => 'Es gibt keine Version der Datei zu dem angegebenen Zeitpunkt.',

# File deletion
'filedelete'             => 'Lösche „$1“',
'filedelete-legend'      => 'Lösche Datei',
'filedelete-intro'       => "Du löschst die Datei '''„[[Media:$1|$1]]“'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Du löschst von der Datei \'\'\'„[[Media:$1|$1]]“\'\'\' die [$4 Version vom $2, $3 Uhr].</span>',
'filedelete-comment'     => 'Grund:',
'filedelete-submit'      => 'Löschen',
'filedelete-success'     => "'''„$1“''' wurde gelöscht.",
'filedelete-success-old' => '<span class="plainlinks">Von der Datei \'\'\'„[[Media:$1|$1]]“\'\'\' wurde die Version $2, $3 Uhr gelöscht.</span>',
'filedelete-nofile'      => "'''„$1“''' ist auf dieser Website nicht vorhanden.",
'filedelete-nofile-old'  => "Es gibt von '''„$1“''' keine Version vom $2, $3 Uhr.",
'filedelete-iscurrent'   => 'Du versuchst, die aktuelle Version dieser Datei zu löschen. Bitte setze vorher auf eine ältere Version zurück.',

# MIME search
'mimesearch'         => 'Suche nach MIME-Typ',
'mimesearch-summary' => 'Auf dieser Spezialseite können die Dateien nach dem MIME-Typ gefiltert werden. Die Eingabe muss immer den Medien- und Subtyp beinhalten: <tt>image/jpeg</tt> (siehe Bildbeschreibungsseite).',
'mimetype'           => 'MIME-Typ:',
'download'           => 'Herunterladen',

# Unwatched pages
'unwatchedpages'         => 'Nicht beobachtete Seiten',
'unwatchedpages-summary' => 'Diese Spezialseite zeigt alle Seiten, die von keinem Benutzer auf einer Beobachtungsliste stehen.',

# List redirects
'listredirects'         => 'Weiterleitungsliste',
'listredirects-summary' => 'Diese Spezialseite listet Weiterleitungen auf.',

# Unused templates
'unusedtemplates'         => 'Nicht benutzte Vorlagen',
'unusedtemplates-summary' => 'Diese Seite listet alle Vorlagen auf, die nicht in anderen Seiten eingebunden sind. Überprüfe andere Links zu den Vorlagen, bevor du diese löscht.',
'unusedtemplatestext'     => '',
'unusedtemplateswlh'      => 'Andere Links',

# Random redirect
'randomredirect'         => 'Zufällige Weiterleitung',
'randomredirect-nopages' => 'In diesem Namensraum sind keine Weiterleitungen vorhanden.',

# Statistics
'statistics'             => 'Statistik',
'sitestats'              => 'Seitenstatistik',
'userstats'              => 'Benutzerstatistik',
'sitestatstext'          => "Es gibt insgesamt '''$1''' {{PLURAL:$1|Seite|Seiten}} in der Datenbank.
Das schließt Diskussionsseiten, Seiten über {{SITENAME}}, kleine Seiten, Weiterleitungen und andere Seiten ein,
die eventuell nicht als Seiten gewertet werden können.

Diese ausgenommen gibt es '''$2''' {{PLURAL:$2|Seite|Seiten}}, die als Seite gewertet werden {{PLURAL:$2|kann|können}}.

Insgesamt {{PLURAL:$8|wurde '''1''' Datei|wurden '''$8''' Dateien}} hochgeladen.

Insgesamt gab es '''$3''' {{PLURAL:$3|Seitenabruf|Seitenabrufe}} und '''$4''' {{PLURAL:$4|Seitenbearbeitung|Seitenbearbeitungen}} seit {{SITENAME}} eingerichtet wurde.
Daraus ergeben sich '''$5''' Bearbeitungen pro Seite und '''$6''' Seitenabrufe pro Bearbeitung.

Länge der [http://meta.wikimedia.org/wiki/Help:Job_queue „Job queue“]: '''$7'''",
'userstatstext'          => "Es gibt '''$1''' {{PLURAL:$1|registrierten|registrierte}} [[Special:Listusers|Benutzer]].
Davon {{PLURAL:$2|hat|haben}} '''$2''' Benutzer (=$4 %) $5-Rechte.",
'statistics-mostpopular' => 'Meist besuchte Seiten',

'disambiguations'      => 'Begriffsklärungsseiten',
'disambiguationspage'  => 'Template:Begriffsklärung',
'disambiguations-text' => 'Die folgenden Seiten verlinken auf eine Seite zur Begriffsklärung. Sie sollten statt dessen auf die eigentlich gemeinte Seite verlinken.<br />Eine Seite wird als Begriffsklärungsseite behandelt, wenn [[MediaWiki:disambiguationspage]] auf sie verlinkt.<br />Links aus Namensräumen werden hier nicht aufgelistet.',

'doubleredirects'         => 'Doppelte Weiterleitungen',
'doubleredirects-summary' => 'Diese Liste enthält Weiterleitungen, die auf eine weitere Weiterleitungen verlinken.
Jede Zeile enthält Links zu der ersten und zweiten Weiterleitungs sowie das Ziel der zweiten Weiterleitung, welches für gewöhnlich die gewünschte Zielseite ist,
auf die bereits die erste Weiterleitung zeigen sollte.',
'doubleredirectstext'     => '',

'brokenredirects'         => 'Kaputte Weiterleitungen',
'brokenredirects-summary' => 'Diese Spezialseite listet Weiterleitungen auf nicht existierende Seiten auf.',
'brokenredirectstext'     => '',
'brokenredirects-edit'    => '(bearbeiten)',
'brokenredirects-delete'  => '(löschen)',

'withoutinterwiki'        => 'Seiten ohne Links zu anderen Sprachen',
'withoutinterwiki-header' => 'Die folgenden Seiten verlinken nicht auf andere Sprachversionen:',

'fewestrevisions'         => 'Seiten mit den wenigsten Versionen',
'fewestrevisions-summary' => 'Diese Spezialseite zeigt eine Liste von Seiten mit den wenigsten Bearbeitungen.',

# Miscellaneous special pages
'nbytes'                          => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'                     => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                          => '{{PLURAL:$1|1 Link|$1 Links}}',
'nmembers'                        => '{{PLURAL:$1|1 Eintrag|$1 Einträge}}',
'nrevisions'                      => '{{PLURAL:$1|1 Bearbeitung|$1 Bearbeitungen}}',
'nviews'                          => '{{PLURAL:$1|1 Abfrage|$1 Abfragen}}',
'specialpage-empty'               => 'Die Seite enthält aktuell keine Einträge.',
'lonelypages'                     => 'Verwaiste Seiten',
'lonelypages-summary'             => 'Diese Spezialseite zeigt Seiten, auf die von keiner anderen Seite verlinkt wird. Diese verwaisten Seiten sind deshalb nicht erwünscht, oder eventuell fragwürdig, weil sie über die normale Navigation durch {{SITENAME}} nie aufgerufen werden können. ',
'lonelypagestext'                 => '',
'uncategorizedpages'              => 'Nicht kategorisierte Seiten',
'uncategorizedpages-summary'      => 'Diese Spezialseite zeigt alle Seiten, die noch keiner Kategorie zugewiesen wurden.',
'uncategorizedcategories'         => 'Nicht kategorisierte Kategorien',
'uncategorizedcategories-summary' => 'Diese Spezialseite zeigt alle Kategorien, die selbst noch keiner Kategorie zugewiesen wurden.',
'uncategorizedimages'             => 'Nicht kategorisierte Dateien',
'uncategorizedimages-summary'     => 'Diese Spezialseite zeigt alle Dateien, die in keine Kategorie eingeordnet wurden.',
'uncategorizedtemplates'          => 'Nicht kategorisierte Vorlagen',
'uncategorizedtemplates-summary'  => 'Diese Spezialseite zeigt alle Vorlagen, die in keine Kategorie eingeordnet wurden.',
'unusedcategories'                => 'Verwaiste Kategorien',
'unusedimages'                    => 'Unbenutzte Dateien',
'popularpages'                    => 'Beliebte Seiten',
'wantedcategories'                => 'Benutzte, aber nicht angelegte Kategorien',
'wantedcategories-summary'        => 'Diese Spezialseite listet Kategorien auf, die zwar in Seiten verwendet werden, welche aber nicht als Kategorie angelegt worden sind.',
'wantedpages'                     => 'Gewünschte Seiten',
'wantedpages-summary'             => 'Diese Spezialseite listet alle Seiten auf, die noch nicht existieren, auf die aber von bestehenden Seiten bereits verlinkt wird.',
'mostlinked'                      => 'Häufig verlinkte Seiten',
'mostlinked-summary'              => 'Diese Spezialseite zeigt, unabhängig vom Namensraum, alle besonders häufig verlinkten Seiten an.',
'mostlinkedcategories'            => 'Meistbenutzte Kategorien',
'mostlinkedcategories-summary'    => 'Diese Spezialseite zeigt eine Liste der meistbenutzten Kategorien.',
'mostlinkedtemplates'             => 'Meistbenutzte Vorlagen',
'mostlinkedtemplates-summary'     => 'Diese Spezialseite zeigt eine Liste der meistbenutzten Vorlagen.',
'mostcategories'                  => 'Meistkategorisierte Seiten',
'mostcategories-summary'          => 'Diese Spezialseite zeigt besonders häufig kategorisierte Seiten an.',
'mostimages'                      => 'Meistbenutzte Dateien',
'mostimages-summary'              => 'Diese Spezialseite zeigt eine Liste der meistbenutzten Dateien.',
'mostrevisions'                   => 'Seiten mit den meisten Versionen',
'mostrevisions-summary'           => 'Diese Spezialseite zeigt eine Liste von Seiten mit den meisten Bearbeitungen.',
'allpages'                        => 'Alle Seiten',
'allpages-summary'                => "Diese Spezialseite listet den Seitenbestand von {{SITENAME}} von A bis Z auf. Sortiert wird alphabetisch, erst Zahlen, dann Großbuchstaben, Kleinbuchstaben und schließlich Sonderzeichen. ''A&nbsp;10'' findet sich vor ''AZ'', der ''Aal'' ist jedoch noch dahinter eingeordnet.",
'prefixindex'                     => 'Alle Seiten (mit Präfix)',
'prefixindex-summary'             => 'Diese Spezialseite zeigt alle Seiten, die mit der eingegebenen Zeichenfolge („Präfix“) beginnen. Die Ausgabe kann auf einen Namensraum eingeschränkt werden.',
'randompage'                      => 'Zufällige Seite',
'randompage-nopages'              => 'In diesem Namensraum sind keine Seiten vorhanden.',
'shortpages'                      => 'Kurze Seiten',
'shortpages-summary'              => 'Diese Liste zeigt die kürzesten Seiten im Hauptnamensraum an. Gezählt werden die Zeichen des Textes wie er im Bearbeitungsfenster dargestellt wird, also in Wiki-Syntax und ohne die Inhalte eingebundener Vorlagen. Grundlage der Zählung ist der UTF-8-kodierte Text, nach dem beispielsweise deutsche Umlaute als zwei Zeichen gelten.',
'longpages'                       => 'Lange Seiten',
'longpages-summary'               => 'Diese Liste zeigt die längsten Seiten im Hauptnamensraum an. Gezählt werden die Zeichen des Textes wie er im Bearbeitungsfenster dargestellt wird, also in Wiki-Syntax und ohne die Inhalte eingebundener Vorlagen. Grundlage der Zählung ist der UTF-8-kodierte Text, nach dem beispielsweise deutsche Umlaute als zwei Zeichen gelten.',
'deadendpages'                    => 'Sackgassenseiten',
'deadendpages-summary'            => 'Diese Spezialseite zeigt eine Liste von Seiten, die keine Links auf andere Seiten oder nur Links auf noch nicht vorhandene Seiten enthalten.',
'deadendpagestext'                => '',
'protectedpages'                  => 'Geschützte Seiten',
'protectedpages-summary'          => 'Diese Spezialseite zeigt alle vor dem Verschieben oder Bearbeiten geschützten Seiten.',
'protectedpagestext'              => '',
'protectedpagesempty'             => 'Aktuell sind keine Seiten mit diesen Parametern geschützt.',
'listusers'                       => 'Benutzerverzeichnis',
'listusers-summary'               => "Diese Spezialseite listet alle registrierten Benutzer auf; die Gesamtzahl kann [[Special:Statistics|hier]] eingesehen werden. Über das Auswahlfeld ''Gruppe'' lässt sich die Abfrage auf bestimmte Benutzergruppen einschränken.",
'specialpages'                    => 'Spezialseiten',
'specialpages-summary'            => 'Diese Seite bietet einen Überblick aller Spezialseiten. Diese werden automatisch generiert und können nicht bearbeitet werden.',
'spheading'                       => 'Spezialseiten für alle Benutzer',
'restrictedpheading'              => 'Spezialseiten für Administratoren',
'rclsub'                          => '(auf Seiten von „$1“)',
'newpages'                        => 'Neue Seiten',
'newpages-summary'                => 'Diese Spezialseite listet alle neu erstellten Seiten der letzten 30 Tage auf. Die Ausgabe kann auf einen Namensraum und/oder Benutzernamen eingeschränkt werden.',
'newpages-username'               => 'Benutzername:',
'ancientpages'                    => 'Seit längerem unbearbeitete Seiten',
'ancientpages-summary'            => 'Diese Spezialseite zeigt eine Liste von Seiten, die am längsten nicht mehr geändert worden sind.',
'intl'                            => 'Interwiki-Links',
'move'                            => 'verschieben',
'movethispage'                    => 'Seite verschieben',
'unusedimagestext'                => '<p>Bitte beachte, dass andere Webseiten dieses Bild mit einer direkten URL verlinken können. Diese wird nicht als Verwendung erkannt, so dass das Bild hier aufgeführt wird.</p>',
'unusedcategoriestext'            => 'Diese Spezialseite zeigt alle Kategorien, die selber keiner Kategorie zugewiesen wurden.',
'notargettitle'                   => 'Keine Seite angegeben',
'notargettext'                    => 'Du hast nicht angegeben, auf welche Seite diese Funktion angewendet werden soll.',

# Book sources
'booksources'               => 'ISBN-Suche',
'booksources-summary'       => 'Auf dieser Spezialseite kannst du eine ISBN eingeben und erhältst dann eine Liste mit Onlinekatalogen und Bezugsmöglichkeiten zur gesuchten ISBN. Bindestriche oder Leerzeichen zwischen den Ziffern spielen für die Suche keine Rolle.',
'booksources-search-legend' => 'Suche nach Bezugsquellen für Bücher',
'booksources-go'            => 'Suchen',
'booksources-text'          => 'Dies ist eine Liste mit Links zu Internetseiten, die neue und gebrauchte Bücher verkaufen. Dort kann es auch weitere Informationen über die Bücher geben. {{SITENAME}} ist mit keinem dieser Anbieter geschäftlich verbunden.',

'categoriespagetext' => 'Die folgenden Kategorien existieren in {{SITENAME}}:',
'data'               => 'Daten',
'userrights'         => 'Benutzerrechteverwaltung',
'groups'             => 'Benutzergruppen',
'alphaindexline'     => '$1 bis $2',
'version'            => 'Version',

# Special:Log
'specialloguserlabel'  => 'Benutzer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbücher',
'all-logs-page'        => 'Alle Logbücher',
'log-search-legend'    => 'Logbücher durchsuchen',
'log-search-submit'    => 'Suchen',
'alllogstext'          => 'Dies ist die kombinierte Anzeige aller in {{SITENAME}} geführten Logbücher. Die Ausgabe kann durch die Auswahl des Logbuchtyps, des Benutzers oder des Seitentitels eingeschränkt werden.',
'logempty'             => 'Keine passenden Einträge.',
'log-title-wildcard'   => 'Titel beginnt mit …',

# Special:Allpages
'nextpage'          => 'Nächste Seite ($1)',
'prevpage'          => 'Vorherige Seite ($1)',
'allpagesfrom'      => 'Seiten anzeigen ab:',
'allarticles'       => 'Alle Seiten',
'allinnamespace'    => 'Alle Seiten (Namensraum: $1)',
'allnotinnamespace' => 'Alle Seiten (nicht im $1 Namensraum)',
'allpagesprev'      => 'Vorherige',
'allpagesnext'      => 'Nächste',
'allpagessubmit'    => 'Anwenden',
'allpagesprefix'    => 'Seiten anzeigen mit Präfix:',
'allpagesbadtitle'  => 'Der eingegebene Seitenname ist ungültig: Er hat entweder ein vorangestelltes Sprach-, ein Interwiki-Kürzel oder enthält ein oder mehrere Zeichen, welche in Seitennamen nicht verwendet werden dürfen.',
'allpages-bad-ns'   => 'Der Namensraum „$1“ ist in {{SITENAME}} nicht vorhanden.',

# Special:Listusers
'listusersfrom'      => 'Zeige Benutzer ab:',
'listusers-submit'   => 'Zeige',
'listusers-noresult' => 'Keinen Benutzer gefunden.',

# E-mail user
'mailnologin'     => 'Fehler beim E-Mail-Versand',
'mailnologintext' => 'Du musst [[{{ns:special}}:Userlogin|angemeldet sein]] und eine [[{{ns:special}}:Confirmemail|bestätigte]] E-Mail-Adresse haben, um anderen Benutzern E-Mails schicken zu können.',
'emailuser'       => 'E-Mail an diesen Benutzer',
'emailpage'       => 'E-Mail an Benutzer',
'emailpagetext'   => 'Wenn dieser Benutzer eine gültige E-Mail-Adresse angegeben hat, kannst du ihm mit dem untenstehenden Formular eine E-Mail senden. Als Absender wird die E-Mail-Adresse aus deinen Einstellungen eingetragen, damit der Benutzer dir antworten kann.',
'usermailererror' => 'Das E-Mail-Objekt gab einen Fehler zurück:',
'defemailsubject' => '{{SITENAME}}-E-Mail',
'noemailtitle'    => 'Keine E-Mail-Adresse',
'noemailtext'     => 'Dieser Benutzer hat keine gültige E-Mail-Adresse angegeben oder möchte keine E-Mail von anderen Benutzern empfangen.',
'emailfrom'       => 'Von',
'emailto'         => 'An',
'emailsubject'    => 'Betreff',
'emailmessage'    => 'Nachricht',
'emailsend'       => 'Senden',
'emailccme'       => 'Sende eine Kopie der E-Mail an mich',
'emailccsubject'  => 'Kopie deiner Nachricht an $1: $2',
'emailsent'       => 'E-Mail verschickt',
'emailsenttext'   => 'Deine E-Mail wurde verschickt.',

# Watchlist
'watchlist'            => 'Beobachtungsliste',
'mywatchlist'          => 'Beobachtungsliste',
'watchlistfor'         => "(für '''$1''')",
'nowatchlist'          => 'Du hast keine Einträge auf deiner Beobachtungsliste.',
'watchlistanontext'    => 'Du musst dich $1, um deine Beobachtungsliste zu sehen oder Einträge auf ihr zu bearbeiten.',
'watchnologin'         => 'Du bist nicht angemeldet',
'watchnologintext'     => 'Du musst [[Special:Userlogin|angemeldet]] sein, um deine Beobachtungsliste zu bearbeiten.',
'addedwatch'           => 'Zur Beobachtungsliste hinzugefügt',
'addedwatchtext'       => 'Die Seite „$1“ wurde zu deiner [[Special:Watchlist|Beobachtungsliste]] hinzugefügt.

Spätere Änderungen an dieser Seite und der dazugehörigen Diskussionsseite werden dort gelistet und
in der Übersicht der [[Special:Recentchanges|letzten Änderungen]] in Fettschrift dargestellt. 

Wenn du die Seite wieder von deiner Beobachtungsliste entfernen möchtest, klicke auf der jeweiligen Seite auf „nicht mehr beobachten“.',
'removedwatch'         => 'Von der Beobachtungsliste entfernt',
'removedwatchtext'     => 'Die Seite „$1“ wurde von deiner Beobachtungsliste entfernt.',
'watch'                => 'beobachten',
'watchthispage'        => 'Seite beobachten',
'unwatch'              => 'nicht mehr beobachten',
'unwatchthispage'      => 'Nicht mehr beobachten',
'notanarticle'         => 'Keine Seite',
'watchnochange'        => 'Keine der von dir beobachteten Seiten wurde während des angezeigten Zeitraums bearbeitet.',
'watchlist-details'    => 'Du beobachtest {{PLURAL:$1|1 Seite|$1 Seiten}}.',
'wlheader-enotif'      => '* Der E-Mail-Benachrichtigungsdienst ist aktiviert.',
'wlheader-showupdated' => "* Seiten mit noch nicht gesehenen Änderungen werden '''fett''' dargestellt.",
'watchmethod-recent'   => 'Überprüfen der letzten Bearbeitungen für die Beobachtungsliste',
'watchmethod-list'     => 'Überprüfen der Beobachtungsliste nach letzten Bearbeitungen',
'watchlistcontains'    => 'Deine Beobachtungsliste enthält $1 {{PLURAL:$1|Seite|Seiten}}.',
'iteminvalidname'      => 'Problem mit dem Eintrag „$1“, ungültiger Name.',
'wlnote'               => "Es {{PLURAL:$1|folgt die letzte Änderung|folgen die letzten '''$1''' Änderungen}} der letzten {{PLURAL:$2|Stunde|'''$2''' Stunden}}.",
'wlshowlast'           => 'Zeige die Änderungen der letzten $1 Stunden, $2 Tage oder $3 (in den letzten 30 Tagen).',
'watchlist-show-bots'  => 'Bot-Änderungen einblenden',
'watchlist-hide-bots'  => 'Bot-Änderungen ausblenden',
'watchlist-show-own'   => 'eigene Änderungen einblenden',
'watchlist-hide-own'   => 'eigene Änderungen ausblenden',
'watchlist-show-minor' => 'kleine Änderungen einblenden',
'watchlist-hide-minor' => 'kleine Änderungen ausblenden',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Beobachten …',
'unwatching' => 'Nicht beobachten …',

'enotif_mailer'                => '{{SITENAME}} E-Mail-Benachrichtigungsdienst',
'enotif_reset'                 => 'Alle Seiten als besucht markieren',
'enotif_newpagetext'           => 'Das ist eine neue Seite.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Benutzer',
'changed'                      => 'geändert',
'created'                      => 'erzeugt',
'enotif_subject'               => '[{{SITENAME}}] Die Seite "$PAGETITLE" wurde von $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Alle Änderungen auf einen Blick: $1',
'enotif_lastdiff'              => 'Siehe $1 für diese Änderung.',
'enotif_anon_editor'           => 'Anonymer Benutzer $1',
'enotif_body'                  => 'Liebe/r $WATCHINGUSERNAME,

die {{SITENAME}} Seite "$PAGETITLE" wurde von $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED.

Aktuelle Version: $PAGETITLE_URL

$NEWPAGE

Zusammenfassung des Bearbeiters: $PAGESUMMARY $PAGEMINOREDIT

Kontakt zum Bearbeiter:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Es werden solange keine weiteren Benachrichtigungsmails gesendet, bis du die Seite wieder besucht hast. Auf deiner Beobachtungsliste kannst du alle Benachrichtigungsmarker zusammen zurücksetzen.

             Dein freundliches {{SITENAME}} Benachrichtigungssystem

-- 
Um die Einstellungen deiner Beobachtungsliste anzupassen, besuche: {{fullurl:Special:Watchlist/edit}}',

# Delete/protect/revert
'deletepage'                  => 'Seite löschen',
'confirm'                     => 'Bestätigen',
'excontent'                   => "Alter Inhalt: '$1'",
'excontentauthor'             => "Inhalt war: '$1' (einziger Bearbeiter: [[{{ns:user}}:$2|$2]] - [[{{ns:user_talk}}:$2|Diskussion]])",
'exbeforeblank'               => "Inhalt vor dem Leeren der Seite: '$1'",
'exblank'                     => 'Seite war leer',
'confirmdelete'               => 'Löschen bestätigen',
'deletesub'                   => '(Lösche „$1“)',
'historywarning'              => 'Achtung, die Seite, die du löschen möchtest, hat eine Versionsgeschichte:',
'confirmdeletetext'           => 'Du bist dabei, eine Seite oder eine Datei und alle zugehörigen älteren Versionen zu löschen. Bitte bestätige dazu, dass du dir der Konsequenzen bewusst bist, und dass Du in Übereinstimmung mit den [[{{MediaWiki:policy-url}}|Richtlinien]] handelst.',
'actioncomplete'              => 'Aktion beendet',
'deletedtext'                 => '„$1“ wurde gelöscht. Im $2 findest du eine Liste der letzten Löschungen.',
'deletedarticle'              => 'hat „[[$1]]“ gelöscht',
'dellogpage'                  => 'Lösch-Logbuch',
'dellogpagetext'              => 'Dies ist das Logbuch der gelöschten Seiten und Dateien.',
'deletionlog'                 => 'Lösch-Logbuch',
'reverted'                    => 'Auf eine alte Version zurückgesetzt',
'deletecomment'               => 'Grund der Löschung',
'rollback'                    => 'Zurücksetzen der Änderungen',
'rollback_short'              => 'Zurücksetzen',
'rollbacklink'                => 'Zurücksetzen',
'rollbackfailed'              => 'Zurücksetzen gescheitert',
'cantrollback'                => 'Die Änderung kann nicht zurückgesetzt werden, da es keine früheren Autoren gibt.',
'alreadyrolled'               => "Das Zurücksetzen der Änderungen von [[{{ns:user}}:$2|$2]] <span style='font-size: smaller'>([[{{ns:user_talk}}:$2|Diskussion]], 
[[{{ns:special}}:Contributions/$2|Beiträge]])</span> an Seite [[:$1]] war nicht erfolgreich, da in der Zwischenzeit bereits ein anderer Benutzer 
Änderungen an dieser Seite vorgenommen hat.<br />Die letzte Änderung stammt von [[{{ns:user}}:$3|$3]] <span style='font-size: smaller'>([[{{ns:user_talk}}:$3|Diskussion]])</span>.",
'editcomment'                 => 'Der Änderungskommentar lautet: „<i>$1</i>“.', # only shown if there is an edit comment
'revertpage'                  => 'Änderungen von [[{{ns:user}}:$2|$2]] ([[{{ns:special}}:Contributions/$2|Beiträge]]) rückgängig gemacht und letzte Version von $1 wiederhergestellt',
'rollback-success'            => 'Die Änderungen von $1 wurden rückgängig gemacht und die letzte Version von $2 wurde wiederhergestellt.',
'sessionfailure'              => 'Es gab ein Problem mit deiner Benutzersitzung.
Diese Aktion wurde aus Sicherheitsgründen abgebrochen, um eine falsche Zuordnung deiner Änderungen zu einem anderen Benutzer zu verhindern.
Bitte gehe zurück und versuche den Vorgang erneut auszuführen.',
'protectlogpage'              => 'Seitenschutz-Logbuch',
'protectlogtext'              => 'Dies ist das Seitenschutz-Logbuch. Siehe die [[{{ns:special}}:Protectedpages|Liste der geschützten Seiten]] für alle aktuell geschützten Seiten.',
'protectedarticle'            => 'schützte „[[$1]]“',
'modifiedarticleprotection'   => 'änderte den Schutz von „[[$1]]“',
'unprotectedarticle'          => 'hob den Schutz von „[[$1]]“ auf',
'protectsub'                  => '(Schutz ändern von „$1“)',
'confirmprotect'              => 'Seitenschutzstatus ändern',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Sperrdauer:',
'protect_expiry_invalid'      => 'Die eingegebene Dauer ist ungültig.',
'protect_expiry_old'          => 'Die Sperrzeit liegt in der Vergangenheit.',
'unprotectsub'                => '(Aufhebung der Sperrung von „$1“)',
'protect-unchain'             => 'Verschiebeschutz ändern',
'protect-text'                => "Hier kannst du den Schutzstatus für die Seite '''$1''' einsehen und ändern.",
'protect-locked-blocked'      => 'Du kannst den Seitenschutz nicht ändern, da dein Benutzerkonto gesperrt ist. Hier sind die aktuellen Seitenschutz-Einstellungen für die Seite <strong>„$1“:</strong>',
'protect-locked-dblock'       => 'Die Datenbank ist gesperrt, der Seitenschutz kann daher nicht geändert werden. Hier sind die aktuellen Seitenschutz-Einstellungen für die Seite <strong>„$1“:</strong>',
'protect-locked-access'       => 'Dein Benutzerkonto verfügt nicht über die notwendigen Rechte zur Änderung des Seitenschutzes. Hier sind die aktuellen Seitenschutzeinstellungen für die Seite <strong>„$1“:</strong>',
'protect-cascadeon'           => 'Diese Seite ist gegenwärtig Teil einer Kaskadensperre. Sie ist in die {{PLURAL:$1|folgende Seite|folgenden Seiten}} eingebunden, welche durch die Kaskadensperroption geschützt {{PLURAL:$1|ist|sind}}. Der Seitenschutzstatus kann für diese Seite geändert werden, dies hat jedoch keinen Einfluss auf die Kaskadensperre:',
'protect-default'             => 'Alle (Standard)',
'protect-fallback'            => 'Es wird die „$1“-Berechtigung benötigt.',
'protect-level-autoconfirmed' => 'Sperrung für nicht registrierte Benutzer',
'protect-level-sysop'         => 'Nur Administratoren',
'protect-summary-cascade'     => 'kaskadierend',
'protect-expiring'            => 'bis $1 (UTC)',
'protect-cascade'             => 'Kaskadierende Sperre – alle in diese Seite eingebundenen Vorlagen werden ebenfalls gesperrt.',
'restriction-type'            => 'Schutzstatus',
'restriction-level'           => 'Schutzhöhe',
'minimum-size'                => 'Mindestgröße:',
'maximum-size'                => 'Maximalgröße:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit' => 'bearbeiten',
'restriction-move' => 'verschieben',

# Restriction levels
'restriction-level-sysop'         => 'geschützt (nur Administratoren)',
'restriction-level-autoconfirmed' => 'geschützt (nur angemeldete, nicht-neue Benutzer)',
'restriction-level-all'           => 'alle',

# Undelete
'undelete'                     => 'Gelöschte Seite wiederherstellen',
'undeletepage'                 => 'Gelöschte Seite wiederherstellen',
'viewdeletedpage'              => 'Gelöschte Seiten anzeigen',
'undeletepagetext'             => 'Die folgenden Seiten wurden gelöscht und können von Administratoren wiederhergestellt werden:',
'undeleteextrahelp'            => '* Um die Seite komplett mit allen Versionen wiederherzustellen, gib bitte eine Begründung an und klicke auf „Wiederherstellen“.
* Möchtest du nur bestimmte Versionen wiederherstellen, so wähle diese bitte einzeln anhand der Markierungen aus, gib eine Begründung an und klicke dann auf „Wiederherstellen“.
* „Abbrechen“ leert das Kommentarfeld und entfernt alle Markierungen bei den Versionen.',
'undeleterevisions'            => '{{PLURAL:$1|1 Version|$1 Versionen}} archiviert',
'undeletehistory'              => 'Wenn du diese Seite wiederherstellst, werden auch alle alten
Versionen wiederhergestellt. Wenn seit der Löschung eine neue Seite gleichen
Namens erstellt wurde, werden die wiederhergestellten Versionen chronologisch in die Versionsgeschichte eingeordnet.
Sichtbarkeits-Einschränkungen an Dateiversionen gehen bei einer Wiederherstellung verloren.',
'undeleterevdel'               => 'Die Wiederherstellung wird nicht durchgeführt, wenn die aktuellste Version versteckt ist oder versteckte Teile enthält.
In diesem Fall darf die aktuellste Version nicht markiert werden oder ihr Status muss auf den einer normalen Version geändert werden.
Versionen von Dateien, auf die du keinen Zugriff hast, werden nicht wiederhergestellt.',
'undeletehistorynoadmin'       => 'Diese Seite wurde gelöscht. Der Grund für die Löschung ist in der Zusammenfassung angegeben,
genauso wie Details zum letzten Benutzer, der diese Seite vor der Löschung bearbeitet hat.
Der aktuelle Text der gelöschten Seite ist nur Administratoren zugänglich.',
'undelete-revision'            => 'Gelöschte Version von $1 - $2, $3:',
'undeleterevision-missing'     => 'Ungültige oder fehlende Version. Entweder ist der Link falsch oder die Version wurde aus dem Archiv wiederhergestellt oder entfernt.',
'undelete-nodiff'              => 'Keine vorhergehende Version vorhanden.',
'undeletebtn'                  => 'Wiederherstellen',
'undeletereset'                => 'Abbrechen',
'undeletecomment'              => 'Begründung:',
'undeletedarticle'             => 'hat „[[$1]]“ wiederhergestellt',
'undeletedrevisions'           => '{{PLURAL:$1|1 Version wurde|$1 Versionen wurden}} wiederhergestellt',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 Version|$1 Versionen}} und {{PLURAL:$2|1 Datei|$2 Dateien}} wurden wiederhergestellt',
'undeletedfiles'               => '{{PLURAL:$1|1 Datei wurde|$1 Dateien wurden}} wiederhergestellt',
'cannotundelete'               => 'Wiederherstellung fehlgeschlagen; jemand anderes hat die Seite bereits wiederhergestellt.',
'undeletedpage'                => "'''$1''' wurde wiederhergestellt.

Im [[Special:Log/delete|Lösch-Logbuch]] findest du eine Übersicht der gelöschten und wiederhergestellten Seiten.",
'undelete-header'              => 'Siehe das [[{{ns:special}}:Log/delete|Lösch-Logbuch]] für kürzlich gelöschte Seiten.',
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

# Namespace form on various pages
'namespace'      => 'Namensraum:',
'invert'         => 'Auswahl umkehren',
'blanknamespace' => '(Seiten)',

# Contributions
'contributions' => 'Benutzerbeiträge',
'mycontris'     => 'Eigene Beiträge',
'contribsub2'   => 'Für $1 ($2)',
'nocontribs'    => 'Es wurden keine Benutzerbeiträge mit diesen Kriterien gefunden.',
'ucnote'        => 'Dies sind die letzten <b>$1</b> Beiträge des Benutzers in den letzten <b>$2</b> Tagen.',
'uclinks'       => 'Zeige die letzten $1 Beiträge; zeige die letzten $2 Tage.',
'uctop'         => ' (aktuell)',
'month'         => 'und Monat:',
'year'          => 'bis Jahr:',

'sp-contributions-newest'      => 'Jüngste',
'sp-contributions-oldest'      => 'Älteste',
'sp-contributions-newer'       => 'Jüngere $1',
'sp-contributions-older'       => 'Ältere $1',
'sp-contributions-newbies'     => 'Zeige nur Beiträge neuer Benutzer',
'sp-contributions-newbies-sub' => 'Für Neulinge',
'sp-contributions-blocklog'    => 'Sperrlogbuch',
'sp-contributions-search'      => 'Suche nach Benutzerbeiträgen',
'sp-contributions-username'    => 'IP-Adresse oder Benutzername:',
'sp-contributions-submit'      => 'Suchen',

'sp-newimages-showfrom' => 'Zeige neue Dateien ab $1',

# What links here
'whatlinkshere'         => 'Links auf diese Seite',
'whatlinkshere-title'   => 'Seiten, die auf „$1“ verlinken',
'whatlinkshere-summary' => 'Diese Spezialseite listet alle internen Links auf eine bestimmte Seite auf. Die möglichen Zusätze „(Vorlageneinbindung)“ und „(Weiterleitungsseite)“ zeigen jeweils an, dass die Seite nicht durch einen normalen Wikilink eingebunden ist. ',
'whatlinkshere-page'    => 'Seite:',
'linklistsub'           => '(Linkliste)',
'linkshere'             => "Die folgenden Seiten verlinken auf '''„[[:$1]]“''':",
'nolinkshere'           => "Keine Seite verlinkt auf '''„[[:$1]]“'''.",
'nolinkshere-ns'        => "Keine Seite verlinkt auf '''„[[:$1]]“''' im gewählten Namensraum.",
'isredirect'            => 'Weiterleitungsseite',
'istemplate'            => 'Vorlageneinbindung',
'whatlinkshere-prev'    => '{{PLURAL:$1|vorheriger|vorherige $1}}',
'whatlinkshere-next'    => '{{PLURAL:$1|nächster|nächste $1}}',
'whatlinkshere-links'   => '← Links',

# Block/unblock
'blockip'                     => 'IP-Adresse/Benutzer sperren',
'blockiptext'                 => 'Mit diesem Formular sperrst du eine IP-Adresse oder einen Benutzernamen, so dass von dort keine Änderungen mehr vorgenommen werden können.
Dies sollte nur erfolgen, um Vandalismus zu verhindern und in Übereinstimmung mit den [[{{MediaWiki:policy-url}}|Richtlinien]].
Bitte gib den Grund für die Sperre an.',
'ipaddress'                   => 'IP-Adresse oder Benutzername:',
'ipadressorusername'          => 'IP-Adresse oder Benutzername:',
'ipbexpiry'                   => 'Sperrdauer:',
'ipbreason'                   => 'Begründung:',
'ipbreasonotherlist'          => 'Andere Begründung',
'ipbreason-dropdown'          => '
* Allgemeine Sperrgründe
** Löschen von Seiten
** Einstellen unsinniger Seiten
** Fortgesetzte Verstöße gegen die Richtlinien für Weblinks
** Verstoß gegen den Grundsatz „Keine persönlichen Angriffe“
* Benutzerspezifische Sperrgründe
** Ungeeigneter Benutzername
** Neuanmeldung eines unbeschränkt gesperrten Benutzers
* IP-spezifische Sperrgründe
** Proxy, wegen Vandalismus einzelner Benutzer längerfristig gesperrt',
'ipbanononly'                 => 'Nur anonyme Benutzer sperren',
'ipbcreateaccount'            => 'Erstellung von Benutzerkonten verhindern',
'ipbemailban'                 => 'E-Mail-Versand sperren',
'ipbenableautoblock'          => 'Sperre die aktuell von diesem Benutzer genutzte IP-Adresse sowie automatisch alle folgenden, von denen aus er Bearbeitungen oder das Anlegen von Benutzeraccounts versucht',
'ipbsubmit'                   => 'IP-Adresse/Benutzer sperren',
'ipbother'                    => 'Andere Dauer (englisch):',
'ipboptions'                  => '1 Stunde:1 hour,2 Stunden:2 hours,6 Stunden:6 hours,1 Tag:1 day,3 Tage:3 days,1 Woche:1 week,2 Wochen:2 weeks,1 Monat:1 month,3 Monate:3 months,1 Jahr:1 year,Unbeschränkt:infinite',
'ipbotheroption'              => 'Andere Dauer',
'ipbotherreason'              => 'Andere/ergänzende Begründung:',
'ipbhidename'                 => 'Benutzername/IP-Adresse im Sperr-Logbuch, der Liste aktiver Sperren und dem Benutzerverzeichnis verstecken.',
'badipaddress'                => 'Die IP-Adresse hat ein falsches Format.',
'blockipsuccesssub'           => 'Sperre erfolgreich',
'blockipsuccesstext'          => 'Der Benutzer/die IP-Adresse [[{{ns:special}}:Contributions/$1|$1]] wurde gesperrt und die Aktion im [[{{ns:special}}:Log/block|Benutzersperr-Logbuch]] protokolliert

Zur Aufhebung der Sperre siehe die [[{{ns:special}}:Ipblocklist|Liste aller aktiven Sperren]].',
'ipb-edit-dropdown'           => 'Sperrgründe bearbeiten',
'ipb-unblock-addr'            => '„$1“ freigeben',
'ipb-unblock'                 => 'IP-Adresse/Benutzer freigeben',
'ipb-blocklist-addr'          => 'Aktuelle Sperre für „$1“ anzeigen',
'ipb-blocklist'               => 'Alle aktuellen Sperren anzeigen',
'unblockip'                   => 'IP-Adresse freigeben',
'unblockiptext'               => 'Mit diesem Formular kannst du eine IP-Adresse oder einen Benutzer freigeben.',
'ipusubmit'                   => 'Freigeben',
'unblocked'                   => '[[User:$1|$1]] wurde freigegeben',
'unblocked-id'                => 'Sperr-ID $1 wurde freigegeben',
'ipblocklist'                 => 'Liste gesperrter Benutzer/IP-Adressen',
'ipblocklist-legend'          => 'Suche nach einem gesperrten Benutzer',
'ipblocklist-username'        => 'Benutzername oder IP-Adresse:',
'ipblocklist-summary'         => "Diese Spezialseite führt – ergänzend zum [[Special:Log/block|Benutzersperr-Logbuch]], das alle manuell vorgenommenen (Ent-)Sperrungen protokolliert – die '''aktuell''' gesperrten Benutzer und IP-Adressen auf, einschließlich automatisch gesperrter IP-Adressen in anonymisierter Form.",
'ipblocklist-submit'          => 'Suchen',
'blocklistline'               => '$1, $2 sperrte $3 (bis $4)',
'infiniteblock'               => 'unbegrenzt',
'expiringblock'               => '$1',
'anononlyblock'               => 'nur Anonyme',
'noautoblockblock'            => 'Autoblock deaktiviert',
'createaccountblock'          => 'Erstellung von Benutzerkonten gesperrt',
'emailblock'                  => 'E-Mail-Versand gesperrt',
'ipblocklist-empty'           => 'Die Liste enthält keine Einträge.',
'ipblocklist-no-results'      => 'Die gesuchte IP-Adresse/der Benutzername ist nicht gesperrt.',
'blocklink'                   => 'sperren',
'unblocklink'                 => 'freigeben',
'contribslink'                => 'Beiträge',
'autoblocker'                 => 'Automatische Sperre, da du eine gemeinsame IP-Adresse mit [[Benutzer:$1]] benutzt. Grund: „$2“.',
'blocklogpage'                => 'Benutzersperr-Logbuch',
'blocklogentry'               => 'sperrte „[[$1]]“ für einen Zeitraum von: $2 $3',
'blocklogtext'                => 'Dies ist das Logbuch über Sperrungen und Entsperrungen von Benutzern und IP-Adressen. Automatisch gesperrte IP-Adressen werden nicht erfasst. Siehe die [[{{ns:special}}:Ipblocklist|{{int:ipblocklist}}]] für alle aktiven Sperren.',
'unblocklogentry'             => 'hat die Sperre von „[[$1]]“ aufgehoben',
'block-log-flags-anononly'    => 'nur Anonyme',
'block-log-flags-nocreate'    => 'Erstellung von Benutzerkonten gesperrt',
'block-log-flags-noautoblock' => 'Autoblock deaktiviert',
'block-log-flags-noemail'     => 'E-Mail-Versand gesperrt',
'range_block_disabled'        => 'Die Möglichkeit, ganze Adressräume zu sperren, ist nicht aktiviert.',
'ipb_expiry_invalid'          => 'Die eingegebene Dauer ist ungültig.',
'ipb_already_blocked'         => '„$1“ wurde bereits gesperrt',
'ipb_cant_unblock'            => 'Fehler: Sperr-ID $1 nicht gefunden. Die Sperre wurde bereits aufgehoben.',
'ipb_blocked_as_range'        => 'Fehler: Die IP-Adresse $1 wurde als Teil der Bereichssperre $2 indirekt gesperrt. Eine Entsperrung von $1 alleine ist nicht möglich.',
'ip_range_invalid'            => 'Ungültiger IP-Addressbereich.',
'blockme'                     => 'Sperre mich',
'proxyblocker'                => 'Proxy blocker',
'proxyblocker-disabled'       => 'Diese Funktion ist deaktiviert.',
'proxyblockreason'            => 'Deine IP-Adresse wurde gesperrt, da sie ein offener Proxy ist. Bitte kontaktiere deinen Internet-Provider oder deine Systemadministratoren und informiere sie über dieses mögliche Sicherheitsproblem.',
'proxyblocksuccess'           => 'Fertig.',
'sorbsreason'                 => 'Die IP-Adresse ist in der DNSBL von {{SITENAME}} als offener PROXY gelistet.',
'sorbs_create_account_reason' => 'Die IP-Adresse ist in der DNSBL von {{SITENAME}} als offener PROXY gelistet. Das Anlegen neuer Benutzer ist nicht möglich.',

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
'lockdbsuccesstext'   => 'Die {{SITENAME}}-Datenbank wurde gesperrt.<br />Bitte gib die Datenbank [[Special:Unlockdb|wieder frei]], sobald die Wartung abgeschlossen ist.',
'unlockdbsuccesstext' => 'Die {{SITENAME}}-Datenbank wurde freigegeben.',
'lockfilenotwritable' => 'Die Datenbank-Sperrdatei ist nicht beschreibbar. Zum Sperren oder Freigeben der Datenbank muss diese für den Webserver beschreibbar sein.',
'databasenotlocked'   => 'Die Datenbank ist nicht gesperrt.',

# Move page
'movepage'                => 'Seite verschieben',
'movepagetext'            => 'Mit diesem Formular kannst du eine Seite umbenennen (mitsamt allen Versionen). Der alte Titel wird zum neuen weiterleiten. Links auf den alten Titel werden nicht geändert.',
'movepagetalktext'        => "Die dazugehörige Diskussionsseite wird, sofern vorhanden, mitverschoben, '''es sei denn:'''
*Es existiert bereits eine Diskussionsseite mit diesem Namen, oder
*du wählst die untenstehende Option ab.

In diesen Fällen musst du, falls gewünscht, den Inhalt der Seite von Hand verschieben oder zusammenführen.

Bitte den '''neuen''' Titel unter '''Ziel''' eintragen, darunter die Umbenennung bitte '''begründen.'''",
'movearticle'             => 'Seite verschieben:',
'movenologin'             => 'Du bist nicht angemeldet',
'movenologintext'         => 'Du musst ein registrierter Benutzer und [[Special:Userlogin|angemeldet]] sein, um eine Seite zu verschieben.',
'movenotallowed'          => 'Du hast in diesem Wiki keine Berechtigung, Seiten zu verschieben.',
'newtitle'                => 'Ziel:',
'move-watch'              => 'Diese Seite beobachten',
'movepagebtn'             => 'Seite verschieben',
'pagemovedsub'            => 'Verschiebung erfolgreich',
'movepage-moved'          => "<big>'''Die Seite „$1“ wurde nach „$2“ verschoben.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Unter diesem Namen existiert bereits eine Seite. Bitte wähle einen anderen Namen.',
'talkexists'              => 'Die Seite selbst wurde erfolgreich verschoben, aber die zugehörige Diskussions-Seite nicht, da bereits eine mit dem neuen Titel existiert. Bitte gleiche die Inhalte von Hand ab.',
'movedto'                 => 'verschoben nach',
'movetalk'                => 'Die Diskussionsseite mitverschieben, wenn möglich.',
'talkpagemoved'           => 'Die Diskussionsseite wurde ebenfalls verschoben.',
'talkpagenotmoved'        => 'Die Diskussionsseite wurde <strong>nicht</strong> verschoben.',
'1movedto2'               => 'hat „[[$1]]“ nach „[[$2]]“ verschoben',
'1movedto2_redir'         => 'hat „[[$1]]“ nach „[[$2]]“ verschoben und dabei eine Weiterleitung überschrieben',
'movelogpage'             => 'Verschiebungs-Logbuch',
'movelogpagetext'         => 'Dies ist eine Liste aller verschobenen Seiten.',
'movereason'              => 'Begründung:',
'revertmove'              => 'zurück verschieben',
'delete_and_move'         => 'Löschen und Verschieben',
'delete_and_move_text'    => '==Zielseite vorhanden, löschen?==

Die Seite „[[$1]]“ existiert bereits. Möchtest du diese löschen, um die Seite verschieben zu können?',
'delete_and_move_confirm' => 'Zielseite für die Verschiebung löschen',
'delete_and_move_reason'  => 'gelöscht, um Platz für Verschiebung zu machen',
'selfmove'                => 'Ursprungs- und Zielname sind gleich; eine Seite kann nicht zu sich selbst verschoben werden.',
'immobile_namespace'      => 'Der Quell- oder Zielnamensraum ist geschützt; Verschiebungen in diesen Namensraum hinein oder aus diesem heraus sind nicht möglich.',

# Export
'export'            => 'Seiten exportieren',
'exporttext'        => 'Mit dieser Spezialseite kannst du den Text (und die Bearbeitungs-/Versionsgeschichte) einzelner Seiten in eine XML-Datei exportieren.
Die Datei kann in ein anderes Wiki mit MediaWiki-Software eingespielt, bearbeitet oder archiviert werden.

Trage den oder die entsprechenden Seitentitel in das folgende Textfeld ein (pro Zeile jeweils nur für eine Seite).

Alternativ ist der Export auch mit der Syntax <tt><nowiki>[[</nowiki>{{ns:special}}<nowiki>:Export/Seitentitel]]</nowiki></tt> möglich, zum Beispiel [[{{ns:special}}:Export/{{Mediawiki:mainpage}}]] für die [[{{Mediawiki:mainpage}}]].',
'exportcuronly'     => 'Nur die aktuelle Version der Seite exportieren',
'exportnohistory'   => "----
'''Hinweis:''' Der Export kompletter Versionsgeschichten ist aus Performancegründen bis auf weiteres nicht möglich.",
'export-submit'     => 'Seiten exportieren',
'export-addcattext' => 'Seiten aus Kategorie hinzufügen:',
'export-addcat'     => 'Hinzufügen',
'export-download'   => 'Als XML-Datei speichern',

# Namespace 8 related
'allmessages'               => 'MediaWiki-Systemtexte',
'allmessagesname'           => 'Name',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Aktueller Text',
'allmessagestext'           => 'Dies ist eine Liste der MediaWiki-Systemtexte.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' ist momentan nicht möglich, weil die Datenbank offline ist.",
'allmessagesfilter'         => 'Nachrichtennamensfilter:',
'allmessagesmodified'       => 'Nur geänderte zeigen',

# Thumbnails
'thumbnail-more'           => 'vergrößern',
'missingimage'             => '<b>Fehlendes Bild</b><br /><i>$1</i>',
'filemissing'              => 'Datei fehlt',
'thumbnail_error'          => 'Fehler beim Erstellen des Vorschaubildes: $1',
'djvu_page_error'          => 'DjVu-Seite ausserhalb des Seitenbereichs',
'djvu_no_xml'              => 'XML-Daten können für die DjVu-Datei nicht abgerufen werden',
'thumbnail_invalid_params' => 'Ungültige Thumbnail-Parameter',
'thumbnail_dest_directory' => 'Zielverzeichnis kann nicht erstellt werden.',

# Special:Import
'import'                     => 'Seiten importieren',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-text'      => 'Wähle ein Wiki und eine Seite zum Importieren aus.
Die Versionsdaten und Benutzernamen bleiben dabei erhalten.
Alle Transwiki-Import-Aktionen werden im [[Special:Log/import|Import-Logbuch]] protokolliert.',
'import-interwiki-history'   => 'Importiere alle Versionen dieser Seite',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Importiere die Seite in den Namensraum:',
'importtext'                 => 'Auf dieser Spezialseite können über [[{{ns:special}}:Export]] exportierte Seiten in dieses Wiki importiert werden.',
'importstart'                => 'Importiere Seiten …',
'import-revision-count'      => '– {{PLURAL:$1|1 Version|$1 Versionen}}',
'importnopages'              => 'Keine Seiten zum Importieren vorhanden.',
'importfailed'               => 'Import fehlgeschlagen: $1',
'importunknownsource'        => 'Unbekannte Importquelle',
'importcantopen'             => 'Importdatei konnte nicht geöffnet werden',
'importbadinterwiki'         => 'Falscher Interwiki-Link',
'importnotext'               => 'Leer oder kein Text',
'importsuccess'              => 'Import erfolgreich!',
'importhistoryconflict'      => 'Es existieren bereits ältere Versionen, welche mit diesen kollidieren. Möglicherweise wurde die Seite bereits vorher importiert.',
'importnosources'            => 'Für den Transwiki-Import sind keine Quellen definiert. Das direkte Hochladen von Versionen ist gesperrt.',
'importnofile'               => 'Es ist keine Importdatei ausgewählt worden!',
'importuploaderror'          => 'Das Hochladen der Importdatei ist fehlgeschlagen. Vielleicht ist die Datei größer als erlaubt.',

# Import log
'importlogpage'                    => 'Import-Logbuch',
'importlogpagetext'                => 'Administrativer Import von Seiten mit Versionsgeschichte von anderen Wikis.',
'import-logentry-upload'           => 'hat „[[$1]]“ von einer Datei importiert',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Version|Versionen}}',
'import-logentry-interwiki'        => 'hat „[[$1]]“ importiert (Transwiki)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Version|Versionen}} von $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Eigene Benutzerseite',
'tooltip-pt-anonuserpage'         => 'Benutzerseite der IP-Adresse von der aus du Änderungen durchführst',
'tooltip-pt-mytalk'               => 'Eigene Diskussionsseite',
'tooltip-pt-anontalk'             => 'Diskussion über Änderungen von dieser IP-Adresse',
'tooltip-pt-preferences'          => 'Eigene Einstellungen',
'tooltip-pt-watchlist'            => 'Liste der beobachteten Seiten',
'tooltip-pt-mycontris'            => 'Liste eigener Beiträge',
'tooltip-pt-login'                => 'Sich einzuloggen wird zwar gerne gesehen, ist aber keine Pflicht.',
'tooltip-pt-anonlogin'            => 'Sich einzuloggen wird zwar gerne gesehen, ist aber keine Pflicht.',
'tooltip-pt-logout'               => 'Abmelden',
'tooltip-ca-talk'                 => 'Diskussion zum Seiteninhalt',
'tooltip-ca-edit'                 => 'Seite bearbeiten. Bitte vor dem Speichern die Vorschaufunktion benutzen.',
'tooltip-ca-addsection'           => 'Einen Kommentar zu dieser Diskussion hinzufügen.',
'tooltip-ca-viewsource'           => 'Diese Seite ist geschützt. Der Quelltext kann angesehen werden.',
'tooltip-ca-history'              => 'Frühere Versionen dieser Seite',
'tooltip-ca-protect'              => 'Diese Seite schützen',
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
'tooltip-n-portal'                => 'Über das Portal, was du tun kannst, wo was zu finden ist',
'tooltip-n-currentevents'         => 'Hintergrundinformationen zu aktuellen Ereignissen',
'tooltip-n-recentchanges'         => 'Liste der letzten Änderungen in {{SITENAME}}.',
'tooltip-n-randompage'            => 'Zufällige Seite',
'tooltip-n-help'                  => 'Hilfeseite anzeigen',
'tooltip-n-sitesupport'           => 'Unterstützen Sie uns',
'tooltip-t-whatlinkshere'         => 'Liste aller Seiten, die hierher zeigen',
'tooltip-t-recentchangeslinked'   => 'Letzte Änderungen an Seiten, die von hier verlinkt sind',
'tooltip-feed-rss'                => 'RSS-Feed für diese Seite',
'tooltip-feed-atom'               => 'Atom-Feed für diese Seite',
'tooltip-t-contributions'         => 'Liste der Beiträge von diesem Benutzer ansehen',
'tooltip-t-emailuser'             => 'Eine E-Mail an diesen Benutzer senden',
'tooltip-t-upload'                => 'Dateien hochladen',
'tooltip-t-specialpages'          => 'Liste aller Spezialseiten',
'tooltip-t-print'                 => 'Druckansicht dieser Seite',
'tooltip-t-permalink'             => 'Dauerhafter Link zu dieser Seitenversion',
'tooltip-ca-nstab-main'           => 'Seiteninhalt anzeigen',
'tooltip-ca-nstab-user'           => 'Benutzerseite anzeigen',
'tooltip-ca-nstab-media'          => 'Mediendateienseite anzeigen',
'tooltip-ca-nstab-special'        => 'Dies ist eine Spezialseite. Sie kann nicht verändert werden.',
'tooltip-ca-nstab-project'        => 'Portalseite anzeigen',
'tooltip-ca-nstab-image'          => 'Bilderseite anzeigen',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki-Systemtext anzeigen',
'tooltip-ca-nstab-template'       => 'Vorlage anzeigen',
'tooltip-ca-nstab-help'           => 'Hilfeseite anzeigen',
'tooltip-ca-nstab-category'       => 'Kategorieseite anzeigen',
'tooltip-minoredit'               => 'Diese Änderung als klein markieren.',
'tooltip-save'                    => 'Änderungen speichern',
'tooltip-preview'                 => 'Vorschau der Änderungen an dieser Seite. Bitte vor dem Speichern benutzen!',
'tooltip-diff'                    => 'Zeigt Änderungen am Text tabellarisch an',
'tooltip-compareselectedversions' => 'Unterschiede zwischen zwei ausgewählten Versionen dieser Seite vergleichen.',
'tooltip-watch'                   => 'Füge diese Seite deiner Beobachtungsliste hinzu',
'tooltip-recreate'                => 'Seite neu erstellen, obwohl sie gelöscht wurde.',
'tooltip-upload'                  => 'Hochladen starten',

# Stylesheets
'common.css'   => '/** CSS an dieser Stelle wirkt sich auf alle Skins aus */',
'monobook.css' => '/** Kleinschreibung nicht erzwingen */
.portlet h5,
.portlet h6,
#p-personal ul,
#p-cactions li a {
	text-transform: none;
}',

# Scripts
'common.js'   => '/* Jedes JavaScript hier wird für alle Benutzer für jede Seite geladen. */',
'monobook.js' => '/* Veraltet; benutzer stattdessen [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadaten sind für diesen Server deaktiviert.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadaten sind für diesen Server deaktiviert.',
'notacceptable'     => 'Der Wiki-Server kann die Daten nicht für dein Ausgabegerät aufbereiten.',

# Attribution
'anonymous'        => 'Anonyme(r) Benutzer auf {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Benutzer $1',
'lastmodifiedatby' => 'Diese Seite wurde zuletzt am $1 um $2 Uhr von $3 geändert.', # $1 date, $2 time, $3 user
'and'              => 'und',
'othercontribs'    => 'Basiert auf der Arbeit von $1',
'others'           => 'andere',
'siteusers'        => '{{SITENAME}}-Benutzer $1',
'creditspage'      => 'Seiteninformationen',
'nocredits'        => 'Für diese Seite sind keine Informationen vorhanden.',

# Spam protection
'spamprotectiontitle'    => 'Spamschutzfilter',
'spamprotectiontext'     => 'Die Seite, die du speichern willst, wurde von dem Spamschutzfilter blockiert. Das liegt wahrscheinlich an einem Link zu einer externen Seite.',
'spamprotectionmatch'    => "'''Der folgende Text wurde vom Spamfilter gefunden: ''$1'''''",
'subcategorycount'       => '{{PLURAL:$1|Es wird $1 Unterkategorie|Es werden $1 Unterkategorien}} angezeigt.',
'categoryarticlecount'   => '<small>Es {{PLURAL:$1|wird $1 Seite|werden $1 Seiten}} aus dieser Kategorie angezeigt.</small>',
'category-media-count'   => '<small>Es {{PLURAL:$1|wird $1 Datei|werden $1 Dateien}} aus dieser Kategorie angezeigt.</small>',
'listingcontinuesabbrev' => '(Fortsetzung)',
'spambot_username'       => 'MediaWiki Spam-Säuberung',
'spam_reverting'         => 'Letzte Version ohne Links zu $1 wiederhergestellt.',
'spam_blanking'          => 'Alle Versionen enthielten Links zu $1, bereinigt.',

# Info page
'infosubtitle'   => 'Seiteninformation',
'numedits'       => 'Anzahl der Seitenänderungen: $1',
'numtalkedits'   => 'Anzahl der Diskussionsänderungen: $1',
'numwatchers'    => 'Anzahl der Beobachter: $1',
'numauthors'     => 'Anzahl der Autoren: $1',
'numtalkauthors' => 'Anzahl der Diskussionsteilnehmer: $1',

# Math options
'mw_math_png'    => 'Immer als PNG darstellen',
'mw_math_simple' => 'Einfaches TeX als HTML darstellen, sonst PNG',
'mw_math_html'   => 'Wenn möglich als HTML darstellen, sonst PNG',
'mw_math_source' => 'Als TeX belassen (für Textbrowser)',
'mw_math_modern' => 'Empfehlenswert für moderne Browser',
'mw_math_mathml' => 'MathML (experimentell)',

# Patrolling
'markaspatrolleddiff'                 => 'Als kontrolliert markieren',
'markaspatrolledtext'                 => 'Diese neue Seite als kontrolliert markieren',
'markedaspatrolled'                   => 'Als kontrolliert markiert',
'markedaspatrolledtext'               => 'Die ausgewählte Seitenänderung wurde als kontrolliert markiert.',
'rcpatroldisabled'                    => 'Kontrolle der letzten Änderungen gesperrt',
'rcpatroldisabledtext'                => 'Die Kontrolle der letzten Änderungen ist zur Zeit gesperrt.',
'markedaspatrollederror'              => 'Markierung als „kontrolliert“ nicht möglich.',
'markedaspatrollederrortext'          => 'Du musst eine Seitenänderung auswählen.',
'markedaspatrollederror-noautopatrol' => 'Es ist nicht erlaubt, eigene Bearbeitungen als kontrolliert zu markieren.',

# Patrol log
'patrol-log-page' => 'Kontroll-Logbuch',
'patrol-log-line' => 'hat $1 von $2 als kontrolliert markiert $3',
'patrol-log-auto' => '(automatisch)',
'patrol-log-diff' => 'Version $1',

# Image deletion
'deletedrevision'                 => 'alte Version: $1',
'filedeleteerror-short'           => 'Fehler beim Datei-Löschen: $1',
'filedeleteerror-long'            => 'Beim Datei-Löschen wurden Fehler festgestellt:

$1',
'filedelete-missing'              => 'Die Datei „$1“ kann nicht gelöscht werden, da sie nicht vorhanden ist.',
'filedelete-old-unregistered'     => 'Die angegebene Datei-Version „$1“ ist nicht in der Datenbank vorhanden.',
'filedelete-current-unregistered' => 'Die angegebene Datei „$1“ ist nicht in der Datenbank vorhanden.',
'filedelete-archive-read-only'    => 'Das Archiv-Verzeichnis „$1“ ist für den Webserver nicht beschreibbar.',

# Browsing diffs
'previousdiff' => '← Zum vorherigen Versionsunterschied',
'nextdiff'     => 'Zum nächsten Versionsunterschied →',

# Media information
'mediawarning'         => "'''Warnung:''' Diese Art von Datei kann böswilligen Programmcode enthalten. Durch das Herunterladen und Öffnen der Datei kann dein Computer beschädigt werden.<hr />",
'imagemaxsize'         => 'Maximale Bildgröße auf Bildbeschreibungsseiten:',
'thumbsize'            => 'Standardgröße der Vorschaubilder (thumbnails):',
'widthheightpage'      => '$1×$2, $3 Seiten',
'file-info'            => '(Dateigröße: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Dateigröße: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Keine höhere Auflösung vorhanden.</small>',
'svg-long-desc'        => '(SVG-Datei, Basisgröße: $1 × $2 Pixel, Dateigröße: $3)',
'show-big-image'       => 'Version in höherer Auflösung',
'show-big-image-thumb' => '<small>Größe der Voransicht: $1 × $2 Pixel</small>',

# Special:Newimages
'newimages'         => 'Neue Dateien',
'newimages-summary' => 'Diese Spezialseite zeigt die zuletzt hochgeladenen Bilder und Dateien an.',
'showhidebots'      => '(Bots $1)',
'noimages'          => 'Keine Dateien gefunden.',

# Bad image list
'bad_image_list' => 'Format:

Nur Zeilen, die mit einem * anfangen, werden ausgewertet. Als erstes nach dem * muss ein Link auf ein unerwünschtes Bild stehen.
Darauf folgende Seitenlinks in derselben Zeile definieren Ausnahmen, in deren Kontext das Bild trotzdem erscheinen darf.',

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
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Breite',
'exif-imagelength'                 => 'Länge',
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
'exif-gpsmapdatum'                 => 'Geodätisches Datum benutzt',
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

# EXIF attributes
'exif-compression-1' => 'Unkomprimiert',

'exif-unknowndate' => 'Unbekanntes Datum',

'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Horizontal gedreht', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Um 180° gedreht', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikal gedreht', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Entgegen dem Uhrzeigersinn um 90° gedreht und vertikal gewendet', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Um 90° in Uhrzeigersinn gedreht', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Um 90° in Uhrzeigersinn gedreht und vertikal gewendet', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Um 90° entgegen dem Uhrzeigersinn gedreht', # 0th row: left; 0th column: bottom

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

'exif-focalplaneresolutionunit-2' => 'Zoll',

'exif-sensingmethod-1' => 'Undefiniert',
'exif-sensingmethod-2' => 'Ein-Chip-Farbsensor',
'exif-sensingmethod-3' => 'Zwei-Chip-Farbsensor',
'exif-sensingmethod-4' => 'Drei-Chip-Farbsensor',
'exif-sensingmethod-7' => 'Trilinearer Sensor',

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

'exif-gpsmeasuremode-2' => '2-dimensionale Messung',
'exif-gpsmeasuremode-3' => '3-dimensionale Messung',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'Knoten',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tatsächliche Richtung',
'exif-gpsdirection-m' => 'Magnetische Richtung',

# External editor support
'edit-externally'      => 'Diese Datei mit einem externen Programm bearbeiten',
'edit-externally-help' => '<span class="plainlinks">Siehe die [http://meta.wikimedia.org/wiki/Help:External_editors Installationsanweisungen] für weitere Informationen</span>',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',

# E-mail address confirmation
'confirmemail'            => 'E-Mail-Adresse bestätigen (Authentifizierung)',
'confirmemail_noemail'    => 'Du hast keine gültige E-Mail-Adresse in deinen [[Special:Preferences|persönlichen Einstellungen]] eingetragen.',
'confirmemail_text'       => '{{SITENAME}} erfordert, dass du deine E-Mail-Adresse bestätigst (authentifizieren), bevor du die erweiterten E-Mail-Funktionen benutzen kannst. Durch einen Klick auf die Schaltfläche unten wird eine E-Mail an dich verschickt. Diese E-Mail enthält einen Link mit einem Bestätigungs-Code. Durch Klicken auf diesen Link wird bestätigt, dass deine E-Mail-Adresse gültig ist.',
'confirmemail_pending'    => '<div class="error">Es wurde dir bereits ein Bestätigungs-Code per E-Mail zugeschickt. Wenn du dein Benutzerkonto erst vor kurzem erstellt hast, warte bitte noch ein paar Minuten auf die E-Mail, bevor du einen neuen Code anforderst.</div>',
'confirmemail_send'       => 'Bestätigungscode zuschicken',
'confirmemail_sent'       => 'Bestätigungs-E-Mail wurde verschickt.',
'confirmemail_oncreate'   => 'Ein Bestätigungs-Code wurde an deine E-Mail-Adresse gesandt. Dieser Code wird für die Anmeldung nicht benötigt, jedoch wird er zur Aktivierung der E-Mail-Funktionen innerhalb des Wikis gebraucht.',
'confirmemail_sendfailed' => 'Die Bestätigungs-E-Mail konnte nicht versendet werden. Bitte prüfe die E-Mail-Adresse auf ungültige Zeichen.

Rückmeldung des Mailservers: $1',
'confirmemail_invalid'    => 'Ungültiger Bestätigungscode. Eventuell ist der Code bereits wieder ungültig geworden.',
'confirmemail_needlogin'  => 'Du musst dich $1, um deine E-Mail-Adresse zu bestätigen.',
'confirmemail_success'    => 'Deine E-Mail-Adresse wurde erfolgreich bestätigt. Du kannst dich jetzt einloggen.',
'confirmemail_loggedin'   => 'Deine E-Mail-Adresse wurde erfolgreich bestätigt.',
'confirmemail_error'      => 'Es gab einen Fehler bei der Bestätigung deiner E-Mail-Adresse.',
'confirmemail_subject'    => '[{{SITENAME}}] - Bestätigung der E-Mail-Adresse',
'confirmemail_body'       => 'Hallo,

jemand mit der IP-Adresse $1, wahrscheinlich du selbst, hat eine Bestätigung dieser E-Mail-Adresse für das Benutzerkonto "$2" in {{SITENAME}} angefordert. 

Um die E-Mail-Funktion für {{SITENAME}} (wieder) zu aktivieren und um zu bestätigen, dass dieses Benutzerkonto wirklich zu deiner E-Mail-Adresse und damit zu dir gehört, öffne bitte die folgende Web-Adresse:

$3

Sollte die vorstehende Adresse in deinem E-Mail-Programm über mehrere Zeilen gehen, musst Du sie eventuell per Hand in die Adresszeile Deines Web-Browsers einfügen. 

Dieser Bestätigungscode ist gültig bis $4.

Wenn diese E-Mail-Adresse nicht zu dem genannten Benutzerkonto gehört, folge dem Link bitte nicht.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-Einbindung ist deaktiviert]',
'scarytranscludefailed'   => '[Vorlageneinbindung für $1 ist gescheitert]',
'scarytranscludetoolong'  => '[URL ist zu lang; Entschuldigung]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks für diese Seite:<br />
$1
</div>',
'trackbackremove'   => '([$1 löschen])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback wurde erfolgreich gelöscht.',

# Delete conflict
'deletedwhileediting' => '<span class="error">Achtung: Diese Seite wurde gelöscht, nachdem du angefangen hast, sie zu bearbeiten! 
Siehe im [{{fullurl:Special:Log|type=delete&page=}}{{FULLPAGENAMEE}} Lösch-Logbuch] nach, 
warum die Seite gelöscht wurde. Wenn du die Seite speicherst, wird sie neu angelegt.</span>',
'confirmrecreate'     => "Benutzer [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|Diskussion]]) hat diese Seite gelöscht, nachdem du angefangen hast, sie zu bearbeiten. Die Begründung lautete:
''$2''
Bitte bestätige, dass du diese Seite wirklich neu erstellen möchten.",
'recreate'            => 'Erneut anlegen',

# HTML dump
'redirectingto' => 'Weitergeleitet nach [[$1]]',

# action=purge
'confirm_purge'        => 'Diese Seite aus dem Server-Cache löschen? $1',
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => "Suche nach Seiten, in denen ''$1'' vorkommt.",
'searchnamed'      => "Suche nach Seiten, deren Name ''$1'' enthält.",
'articletitles'    => "Seiten, die mit ''$1'' beginnen",
'hideresults'      => 'Verbergen',

# Multipage image navigation
'imgmultipageprev'   => '← vorige Seite',
'imgmultipagenext'   => 'nächste Seite →',
'imgmultigo'         => 'OK',
'imgmultigotopre'    => 'Gehe zu Seite',
'imgmultiparseerror' => 'Die Datei scheint defekt zu sein, so dass {{SITENAME}} keine Seitenliste erstellen kann.',

# Table pager
'ascending_abbrev'         => 'auf',
'descending_abbrev'        => 'ab',
'table_pager_next'         => 'Nächste Seite',
'table_pager_prev'         => 'Vorherige Seite',
'table_pager_first'        => 'Erste Seite',
'table_pager_last'         => 'Letzte Seite',
'table_pager_limit'        => 'Zeige $1 Einträge pro Seite',
'table_pager_limit_submit' => 'Los',
'table_pager_empty'        => 'Keine Ergebnisse',

# Auto-summaries
'autosumm-blank'   => 'Die Seite wurde geleert.',
'autosumm-replace' => "Der Seiteninhalt wurde durch einen anderen Text ersetzt: '$1'",
'autoredircomment' => 'Weiterleitung nach [[$1]] erstellt',
'autosumm-new'     => 'Die Seite wurde neu angelegt: $1',

# Live preview
'livepreview-loading' => 'Laden …',
'livepreview-ready'   => 'Laden … Fertig!',
'livepreview-failed'  => 'Live-Vorschau nicht möglich! Bitte die normale Vorschau benutzen.',
'livepreview-error'   => 'Verbindung nicht möglich: $1 "$2". Bitte die normale Vorschau benutzen.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Bearbeitungen der letzten $1 Sekunden werden in dieser Liste noch nicht angezeigt.',
'lag-warn-high'   => 'Auf Grund hoher Datenbankauslastung werden die Bearbeitungen der letzten $1 Sekunden in dieser Liste noch nicht angezeigt.',

# Watchlist editor
'watchlistedit-numitems'       => 'Deine Beobachtungsliste enthält {{PLURAL:$1|1 Eintrag |$1 Einträge}}, Diskussionsseiten werden nicht gezählt.',
'watchlistedit-noitems'        => 'Deine Beobachtungsliste ist leer.',
'watchlistedit-normal-title'   => 'Beobachtungsliste bearbeiten',
'watchlistedit-normal-legend'  => 'Einträge von der Beobachtungsliste entfernen',
'watchlistedit-normal-explain' => 'Dies sind die Einträge deiner Beobachtungsliste. Um Einträge zu entfernen, markiere die Kästchen neben den Einträgen
	und klicke auf „Einträge entfernen“. Du kannst deine Beobachtungsliste auch im [[Special:Watchlist/raw|Listenformat bearbeiten]]
	oder sie [[Special:Watchlist/clear|komplett löschen]].',
'watchlistedit-normal-submit'  => 'Einträge entfernen',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} von deiner Beobachtungsliste entfernt:',
'watchlistedit-raw-title'      => 'Beobachtungsliste im Listenformat bearbeiten',
'watchlistedit-raw-legend'     => 'Beobachtungsliste im Listenformat bearbeiten',
'watchlistedit-raw-explain'    => 'Dies sind die Einträge deiner Beobachtungsliste im Listenformat. Die Einträge können zeilenweise gelöscht oder hinzugefügt werden.
	Pro Zeile ist ein Eintrag erlaubt. Wenn du fertig bist, klicke auf „Beobachtungsliste speichern“.
	Du kannst auch die [[Special:Watchlist/edit|Standard-Bearbeitungsseite]] benutzen.',
'watchlistedit-raw-titles'     => 'Einträge:',
'watchlistedit-raw-submit'     => 'Beobachtungsliste speichern',
'watchlistedit-raw-done'       => 'Deine Beobachtungsliste wurde gespeichert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} hinzugefügt:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} entfernt:',

# Watchlist editing tools
'watchlisttools-view'  => 'Beobachtungsliste: Änderungen',
'watchlisttools-edit'  => 'normal bearbeiten',
'watchlisttools-raw'   => 'Listenformat bearbeiten (Import/Export)',

);
