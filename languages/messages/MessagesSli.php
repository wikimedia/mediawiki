<?php
/** Lower Silesian (Schläsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Als-Holder
 * @author Jens Liebenau
 * @author Jonny84
 * @author Piotron
 * @author Purodha
 * @author Schläsinger
 * @author Teutonius
 * @author Timpul
 * @author Äberlausitzer
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-underline'               => 'Verknipfonga unterstreeicha:',
'tog-highlightbroken'         => 'Verknipfonga uff lääre Seeita harvurheeba <a href="" class="new">asu</a> (oder asu:<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Obschniete eim Block setza',
'tog-hideminor'               => 'Klänne Änneronga ausblenda',
'tog-hidepatrolled'           => 'Gepriefte Änneronga ausblenda',
'tog-newpageshidepatrolled'   => 'Kontrollierte Seeta ei dar Liste "neue Seeta" verberga',
'tog-extendwatchlist'         => 'Erweiterte Beobachtungsliste',
'tog-usenewrc'                => 'Erweiterte Darstellung (beneetigt JavaScript)',
'tog-numberheadings'          => 'Ieberschrifta automatisch nummeriern',
'tog-showtoolbar'             => 'Beoarbeeta Werkzichleiste aozäan (beneeticht JavaScript)',
'tog-editondblclick'          => 'Seyta mit Doppelklick beoarbeeta (JavaScript)',
'tog-editsection'             => 'Verknipfunga zim Beoarbeeta voo eenzelna Obschieta oazeega',
'tog-editsectiononrightclick' => 'Eenzelne Obschniete dorch Rechtsklick beoarbeeta (JavaScript)',
'tog-showtoc'                 => 'Inhaltsverzeichnis aozäan bei meh wie drei Ieberschrifta',
'tog-rememberpassword'        => 'Notzer sull uff de Lengde oagemeldt blein (login uff diesem Rechner speichern)',
'tog-editwidth'               => 'Text-Eingabefeld miet vuller Breete',
'tog-watchcreations'          => 'Salber derstallte Seyta automatisch beobachta',
'tog-watchdefault'            => 'Salber geänderte on neu erstellte Seyta automatisch beobachta (zu menner Beobachtungsliste hinzufügen)',
'tog-watchmoves'              => 'Vo merr salber verschobene Seyta autoatisch beobachta',
'tog-watchdeletion'           => 'Salber geläschte Seyta automatisch beobachta',
'tog-minordefault'            => 'Eegene Änderunga standardmäßig als geringfiegich markiern',
'tog-previewontop'            => 'Vurschau uberhoalb voo dam Beoarbeetungsfanster oazäan',
'tog-previewonfirst'          => 'Beim irscha Beoarbeeta emmer de Vurschau oazeega',
'tog-nocache'                 => 'Zwischaspeicharn derr Seyte (eim cache) deaktiviern',
'tog-enotifwatchlistpages'    => 'Bei Änderunga voo beobachteta Seyta mer an E-mail schicka',
'tog-enotifusertalkpages'     => 'Bei Änderunga oa menner Benutzer-Dischkurseyte E-mail oa mich schicka',
'tog-enotifminoredits'        => 'Au bei klenn Änderunga E-mail oa mich schicka',
'tog-enotifrevealaddr'        => 'Denne E-mail Oaschrift wart ei Benoachrichtigungs-E-mails oagezäat',
'tog-shownumberswatching'     => 'Oazoahl derr beobachtenden Nutzer oazäan',
'tog-oldsig'                  => 'Vorschau der aktuella Signatur:',
'tog-fancysig'                => 'Underschrift ohne automatische Verknipfung zur Nutzerseete',
'tog-externaleditor'          => "Extern'n Editor als Standard benutza (nur fier Experta, is missa spezielle Einstellungen uff dam eegenen Rechner vurgenumma warn)",
'tog-externaldiff'            => 'Externes Diff-Programm als Standard benutza (nur fier Experta, is missa spezielle Einstellungen uff dam eegenen Rechner vurgenumma warn)',
'tog-showjumplinks'           => '„Wechseln zu“-Verknipfunga aktiviern',
'tog-uselivepreview'          => 'Direkte Vurschau notza (beneetigt JavaScript) (vrsuchsweise)',
'tog-forceeditsummary'        => 'Warnen, wenn bem Speichern de Zsoammafassung fahln tutt',
'tog-watchlisthideown'        => 'Eigene Bearbeitungen ausblenden',
'tog-watchlisthidebots'       => 'Bearbeitungen durch Bots ausblenden',
'tog-watchlisthideminor'      => 'Kleine Bearbeitungen ausblenden',
'tog-watchlisthideliu'        => 'Bearbeitungen angemeldeter Benutzer ausblenden',
'tog-watchlisthideanons'      => 'Bearbeitungen anonymer Benutzer (IP-Adressen) ausblenden',
'tog-watchlisthidepatrolled'  => 'Kontrollierte Änderungen ei der Beobachtungsliste ausblenda',
'tog-nolangconversion'        => 'Konvertierung von Sprachvarianten deaktivieren',
'tog-ccmeonemails'            => 'Schicke mir Kopien der E-Mails, die ich anderen Benutzern sende',
'tog-diffonly'                => 'Zeige beim Versionsvergleich nur die Unterschiede, nicht die vollständige Seite',
'tog-showhiddencats'          => 'Vestackte Kategorien oazäan',
'tog-norollbackdiff'          => 'Underschied noachm Zericksatza underdricka',

'underline-always'  => 'immer',
'underline-never'   => 'nie',
'underline-default' => 'obhängig voo dan Eistellunga der Suchmaschine',

# Font style option in Special:Preferences
'editfont-style'     => 'Schriftfamilie fier dann Text eim Beorbeetungsfanster:',
'editfont-default'   => 'obhängig voo dan Eistellunga der Suchmaschine',
'editfont-monospace' => 'Schrift miet faster Zeechabreite',
'editfont-sansserif' => 'Serifenlose Groteskschrift',
'editfont-serif'     => 'Schrift miet Serifen',

# Dates
'sunday'        => 'Sunntich',
'monday'        => 'Montich',
'tuesday'       => 'Dienstich',
'wednesday'     => 'Mietwuch',
'thursday'      => 'Dunnstich',
'friday'        => 'Freitich',
'saturday'      => 'Sinnomd',
'sun'           => 'Su',
'mon'           => 'Mu',
'tue'           => 'Di',
'wed'           => 'Mi',
'thu'           => 'Du',
'fri'           => 'Fr',
'sat'           => 'Si',
'january'       => 'Januar',
'february'      => 'Februar',
'march'         => 'März',
'april'         => 'Oapril',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Januar',
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
'pagecategories'                 => '{{PLURAL:$1|Kategory|Kategorien}}',
'category_header'                => 'Seyta ei derr Kategorie "$1"',
'subcategories'                  => 'Underkategorien',
'category-media-header'          => 'Media ei der Kategorie "$1"',
'category-empty'                 => "''De delle Kategorie enthält zer Zeet keene Seyta oder Media.''",
'hidden-categories'              => '{{PLURAL:$1|Verstackte Kategorie|Verstackte Kategorien}}',
'hidden-category-category'       => 'Verstackte Kategorie',
'category-subcat-count'          => '{{PLURAL:$2|Diese Kategorie enthält folgende Underkategorie:|{{PLURAL:$1|Folgende Underkategorie ies eene voo insgesomt $2 Underkategoria ei dieser Kategorie:|Is waan $1 voo insgesomt $2 Underkategoria ei dieser Kategorie oagezäat:}}}}',
'category-subcat-count-limited'  => 'Diese Kategorie enthält folgende {{PLURAL:$1|Underkategorie|$1 Underkategoria}}:',
'category-article-count'         => '{{PLURAL:$2|Diese Kategorie enthält folgende Seyte:|{{PLURAL:$1|Folgende Seyte ies eene voo insgesomt $2 Seyta ei dieser Kategorie:|Is werden $1 voo insgesomt $2 Seyta ei dieser Kategorie oagezäat:}}}}',
'category-article-count-limited' => 'Folgende {{PLURAL:$1|Seyte ies|$1 Seyta sein}} ei dieser Kategorie enthalta:',
'category-file-count'            => '{{PLURAL:$2|Diese Kategorie enthält folgende Seyte:|{{PLURAL:$1|Folgende Seyte ies eene voo insgesomt $2 Seyta ei dieser Kategorie:|Is werden $1 voo insgesomt $2 Seyta ei dieser Kategorie oagezäat:}}}}',
'category-file-count-limited'    => 'Folgende {{PLURAL:$1|Datei ies|$1 Dateien sein}} ei dieser Kategorie enthalta:',
'listingcontinuesabbrev'         => '(Furtsetzung)',
'index-category'                 => 'Indizierte Seyta',
'noindex-category'               => 'Neindizierte Seyta',

'mainpagetext'      => "<big>'''MediaWiki wourde erfolgreich installiert.'''</big>",
'mainpagedocfooter' => 'Hilfe zur Benutzung und Konfiguration der Wiki-Software fendest du eim [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbichl].

== Stoarthilfa ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste der Konfigurationsvariablen]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'about'         => 'Ieber',
'article'       => 'Seyte',
'newwindow'     => '(wird ei annem neua Fanster geöffnet)',
'cancel'        => 'Abbrecha',
'moredotdotdot' => 'Mehr ...',
'mypage'        => 'Eigene Seyte',
'mytalk'        => 'Mei Dischkur',
'anontalk'      => 'Diskussionsseite dieser IP',
'navigation'    => 'Navigation',
'and'           => ',&#32;und',

# Cologne Blue skin
'qbfind'         => 'Finda',
'qbbrowse'       => 'Blättern',
'qbedit'         => 'Ändern',
'qbpageoptions'  => 'Seytaoptiona',
'qbpageinfo'     => 'Seytadata',
'qbmyoptions'    => 'Menne Seyta',
'qbspecialpages' => 'Spezialseyta',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Obschnitt hinzufiega',
'vector-action-delete'       => 'Läscha',
'vector-action-move'         => 'Verschieba',
'vector-action-protect'      => 'Schützen',
'vector-action-undelete'     => 'Wiederherstella',
'vector-action-unprotect'    => 'Freigahn',
'vector-namespace-category'  => 'Kategorie',
'vector-namespace-help'      => 'Hilfeseyte',
'vector-namespace-image'     => 'Datei',
'vector-namespace-main'      => 'Seyte',
'vector-namespace-media'     => 'Mediaseyte',
'vector-namespace-mediawiki' => 'MediaWiki-Systemtext',
'vector-namespace-project'   => 'Projektseyte',
'vector-namespace-special'   => 'Spezialseyte',
'vector-namespace-talk'      => 'Dischkur',
'vector-namespace-template'  => 'Vierlooche',
'vector-namespace-user'      => 'Notzerseyte',
'vector-view-create'         => 'Erstella',
'vector-view-edit'           => 'Bearbeita',
'vector-view-history'        => 'Versionsgeschichte',
'vector-view-view'           => 'Lessa',
'vector-view-viewsource'     => 'Quelltext siehn',
'actions'                    => 'Aksjonna',
'namespaces'                 => 'Noamensraum:',
'variants'                   => 'Varianta',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Fehler',
'returnto'          => 'Zurück zur Seite $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Hilfe',
'search'            => 'Suche',
'searchbutton'      => 'Suchen',
'go'                => 'Ausführen',
'searcharticle'     => 'Seyte',
'history'           => 'Versionen',
'history_short'     => 'Geschichte',
'updatedmarker'     => '(geändert)',
'info_short'        => 'Information',
'printableversion'  => 'Druckversion',
'permalink'         => 'Permanentlink',
'print'             => 'Drucken',
'edit'              => 'Beoarbeita',
'create'            => 'Erstella',
'editthispage'      => 'Seyte beoarbeeta',
'create-this-page'  => 'Seyte erstella',
'delete'            => 'Löschen',
'deletethispage'    => 'Diese Seyte löschen',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiederherstellen',
'protect'           => 'Schützen',
'protect_change'    => 'ändern',
'protectthispage'   => 'Seite schützen',
'unprotect'         => 'Freigahn',
'unprotectthispage' => 'Schutz aufheben',
'newpage'           => 'Neue Seyte',
'talkpage'          => 'Diskussion',
'talkpagelinktext'  => 'Dischkur',
'specialpage'       => 'Spezialseyte',
'personaltools'     => 'Persönliche Werkzeuge',
'postcomment'       => 'Kommentar hinzufiega',
'articlepage'       => 'Seyte',
'talk'              => 'Dischkur',
'views'             => 'Oansichta',
'toolbox'           => 'Werkzeuge',
'userpage'          => 'Benutzerseite',
'projectpage'       => 'Projektseyte',
'imagepage'         => 'Dateiseite',
'mediawikipage'     => 'Diskussionsseite oanzeiga',
'templatepage'      => 'Vorlagaseyte oanzeiga',
'viewhelppage'      => 'Hilfeseyte oanzeiga',
'categorypage'      => 'Kategorieseyte oanzeiga',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Oandere Sproacha',
'redirectedfrom'    => '(Weitergeleitet vo $1)',
'redirectpagesub'   => 'Weiterleitung',
'lastmodifiedat'    => 'Diese Seyte wurde zuletzt oam $1 um $2 geändert.',
'viewcount'         => 'Diese Seyte wurde bisher {{PLURAL:$1|eimuol|$1 times}} oabgerufa.',
'protectedpage'     => 'Geschützte Seyte',
'jumpto'            => 'Wechseln zu:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Suche',
'view-pool-error'   => 'Entschuldigung, de Server sein eim Moment ieberlastet.
Zu viele Benutzer versicha, diese Seyte zu besicha.
Bitte warte eenige Minuta, bevor du is noo eemoll versichst.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ieber {{SITENAME}}',
'aboutpage'            => 'Project:Ieber',
'copyright'            => 'Inhalt ies verfügbar unter der $1.',
'copyrightpage'        => '{{ns:project}}:Urheberrecht',
'currentevents'        => 'Aktuelle Ereignisse',
'currentevents-url'    => 'Project:Aktuelle Ereignisse',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Bearbeitungshilfe',
'edithelppage'         => 'Help:Bearbeitungshilfe',
'helppage'             => 'Help:Hilfe',
'mainpage'             => 'Heetseyte',
'mainpage-description' => 'Heetseyte',
'policy-url'           => 'Project:Leitlinien',
'portal'               => '{{SITENAME}}-Portal',
'portal-url'           => 'Project:Benutzerportal',
'privacy'              => 'Datenschutz',
'privacypage'          => 'Project:Datenschutz',

'badaccess'        => 'Keine ausreichenden Rechte',
'badaccess-group0' => 'Du hast nicht die erforderliche Berechtigung für diese Aktion.',
'badaccess-groups' => 'Diese Aktion ies beschränkt uff Benutzer, de {{PLURAL:$2|der Gruppe|anner der Gruppen}} „$1“ angehören.',

'versionrequired'     => 'Version $1 vo MediaWiki ies erforderlich',
'versionrequiredtext' => 'Version $1 vo MediaWiki ies erforderlich, um diese Seite zu nutzen. Siehe de [[Special:Version|Versionsseite]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'voo "$1"',
'youhavenewmessages'      => 'Du hust $1 ($2).',
'newmessageslink'         => 'Neue Noachrichta',
'newmessagesdifflink'     => 'neue Noachrichta',
'youhavenewmessagesmulti' => 'Du hast neue Nachrichta: $1',
'editsection'             => 'Beorbeita',
'editold'                 => 'Beoarbeeta',
'viewsourceold'           => 'Quelltext oanzeiga',
'editlink'                => 'beoarbeeta',
'viewsourcelink'          => 'Quelltext oanschaua',
'editsectionhint'         => 'Oabschnitt beorbeita: $1',
'toc'                     => 'Inhoaltsverzeichnis',
'showtoc'                 => 'Oanzeega',
'hidetoc'                 => 'Verberga',
'thisisdeleted'           => '$1 oanseha oder wiederherstella?',
'viewdeleted'             => '$1 oanzeega?',
'restorelink'             => '$1 {{PLURAL:$1|gelöschte Version|gelöschte Versiona}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungültiger Abonnement-Typ.',
'feed-unavailable'        => 'Es steha keene Feeds zur Verfiegung.',
'site-rss-feed'           => 'RSS-Feed fier $1',
'site-atom-feed'          => 'Atom-Feed fier $1',
'page-rss-feed'           => 'RSS-Feed fier „$1“',
'page-atom-feed'          => 'Atom-Feed fier „$1“',
'red-link-title'          => '$1 (Seyte ies nich doo)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Seyte',
'nstab-user'      => 'Benutzerseyte',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezialseyte',
'nstab-project'   => 'Portalseyte',
'nstab-image'     => 'Datei',
'nstab-mediawiki' => 'MediaWiki-Systemtext',
'nstab-template'  => 'Vierlooche',
'nstab-help'      => 'Hilfeseyte',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Diese Aktion gibt es nicht',
'nosuchactiontext'  => 'De ei der URL angegebene Aktion wird vo MediaWiki nicht unterstützt.',
'nosuchspecialpage' => 'Spezialseyte nee vorhoanda',
'nospecialpagetext' => '<strong>De uffgerufene Spezialseyte ies nee vorhanden.</strong>

Oalle verfügbara Spezialseyta sein ei der [[Special:SpecialPages|Liste der Spezialseyta]] zu finda.',

# General errors
'error'                => 'Fehler',
'databaseerror'        => 'Fehler ei der Datenbank',
'dberrortext'          => 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: <blockquote><tt>$1</tt></blockquote> aus der Funktion „<tt>$2</tt>“.
MySQL meldete den Fehler „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: „$1“ aus der Funktion „<tt>$2</tt>“.
MySQL meldete den Fehler: „<tt>$3: $4</tt>“.',
'laggedslavemode'      => 'Achtung: Die angezeigte Seite enthält unter Umständen nicht die jüngsten Bearbeitungen.',
'readonly'             => 'Datenbanksperre',
'enterlockreason'      => 'Bitte gib einen Grund ein, warum die Datenbank gesperrt werden soll und eine Abschätzung über die Dauer der Sperrung',
'readonlytext'         => 'Die Datenbank ist vorübergehend für Neueinträge und Änderungen gesperrt. Bitte versuche es später noch einmal.

Grund der Sperrung: $1',
'missing-article'      => 'Der Text für „$1“ $2 wurde nicht in der Datenbank gefunden.

Die Seite ist möglicherweise gelöscht oder verschoben worden.

Falls dies nicht der Fall ist, hast du eventuell einen Fehler in der Software gefunden. Bitte melde dies einem [[Special:ListUsers/sysop|Administrator]] unter Nennung der URL.',
'missingarticle-rev'   => '(Versionsnummer: $1)',
'missingarticle-diff'  => '(Unterschied zwischen Versionen: $1, $2)',
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
'cannotdelete'         => 'Die gewählte Seite kann nicht gelöscht werden. Möglicherweise wurde sie bereits entfernt.',
'badtitle'             => 'Ungültiger Tittel',
'badtitletext'         => 'Dar Tittel dar oagefurderta Seite ies ungieltig, laar oder a ungieltiger Sproachlink voo eenem andern Wiki.',
'perfcached'           => 'De folgenden Daten stomma oaus damm Cache und sein meegliecherweise nee aktuell:',
'perfcachedts'         => 'Diese Daten stomma oaus damm Cache, letztes Update: $1',
'querypage-no-updates' => "'''De Aktualisierungsfunktion fier diese Seyte ies zurzeit deaktiviert. De Daten waan bis uff Weiteres nee erneuert.'''",
'wrong_wfQuery_params' => 'Foalsche Parameter fier wfQuery()<br />
Funksjonn: $1<br />
Abfroage: $2',
'viewsource'           => 'Quelltext oasahn',
'viewsourcefor'        => 'fier $1',
'actionthrottled'      => 'Aksjonszoahl limmetiert',
'actionthrottledtext'  => 'Du host diese Aksjonn zu uffte innerholb annes korza Zeitraums ausgeführt. Bitte woarte a poar Minuta und probiere is doann erneut.',
'protectedpagetext'    => 'Diese Seyte ies fier doas Beorbeeta gesperrt.',
'viewsourcetext'       => 'Quelltext voo dar della Seyte:',
'protectedinterface'   => 'Diese Seyte enthält Text fier doas Sproach-Interface der Software und ies gesperrt, im Missbrauch zu verhindern.',
'editinginterface'     => "'''Warnung:''' Diese Seyte enthält vu dar MediaWiki-Software benutzta Text.
Änderunga wirka siech uff de Benutzeroberfläche aus.
Fier Iebersetzunga ziehe bitte ei Betracht, diese eim [http://translatewiki.net/wiki/Main_Page?setlang=de Translatewiki], damm MediaWiki-Lokalisierungsprojekt, durchzufiehra.",
'sqlhidden'            => '(SQL-Abfroage versteckt)',
'cascadeprotected'     => 'Diese Seyte ies zur Beoarbeetung gesperrt. Se ies ei de {{PLURAL:$1|folgende Seyte|folgenda Seyta}} eengebunda, de mittels dar Kaskadensperroption geschietzt {{PLURAL:$1|ies|sein}}:
$2',
'namespaceprotected'   => "Du host kenne Berechtigung, de Seyte eim '''$1'''-Noamensraum zu beorbeeta.",
'customcssjsprotected' => 'Du biest ne berechtigt, diese Seyte zu beoarbeeta, do se zu dann persenlicha Eenstellunga annes andern Benutzers gehiert.',
'ns-specialprotected'  => 'Spezialseyta kinna nicht beoarbeet warn.',
'titleprotected'       => "Enne Seyte miet dam della Noama koan nicht oageläat warn.
De Sperre wurde durch [[User:$1|$1]] miet der Begrindung ''„$2“'' eigerichtet.",

# Virus scanner
'virus-badscanner'     => "Fahlerhofte Konfiguration: unbekoannter Virenskänner: ''$1''",
'virus-scanfailed'     => 'Skän fahlgeschloan (Kode $1)',
'virus-unknownscanner' => 'Unbekoannter Virenskänner:',

# Login and logout pages
'logouttext'                 => "'''Du bist nun oabgemeldet.'''

Du koannst {{SITENAME}} jetzt anonym weiter benutza, oder dich noch amool under dam sella oder annem oandera Benutzernoama [[Special:UserLogin|oanmelda]].",
'welcomecreation'            => '== Willkumma, $1! ==

Dei Benutzerkonto wurde eigerichtet. 
Vergiss nä, denne [[Special:Preferences|{{SITENAME}}-Eistellunga]] oazupoassa.',
'yourname'                   => 'Benutzernoame:',
'yourpassword'               => 'Passwort:',
'yourpasswordagain'          => 'Passwort wiederhola:',
'remembermypassword'         => 'uff diesem Computer dauerhaft oanmelda',
'yourdomainname'             => 'Denne Domain:',
'externaldberror'            => 'Entweder is leit a Fahler bei der externa Authentifizierung vur, oder du darfst dei externes Benutzerkonto ne aktualisiera.',
'login'                      => 'Oanmelda',
'nav-login-createaccount'    => 'Oamelda / a Konto oalega',
'loginprompt'                => 'Zer Oameldung missa Cookies aktiviert sei.',
'userlogin'                  => 'Oanmelda',
'logout'                     => 'Oabmelda',
'userlogout'                 => 'Oabmelda',
'notloggedin'                => 'Nä oangemeldet',
'nologin'                    => "Du hast keen Benutzerkonto? '''$1'''.",
'nologinlink'                => 'Neues Benutzerkonto oanleega',
'createaccount'              => 'Benutzerkonto oanlega',
'gotaccount'                 => "Du hast bereits a Benutzerkonto? '''$1'''.",
'gotaccountlink'             => 'Oanmelda',
'createaccountmail'          => 'ieber E-Mail',
'badretype'                  => 'De beida Passwörter stimma nä ieberein.',
'userexists'                 => 'Dar delle Benutzernoame ies schunt vergahn. Bitte wähle enn andern.',
'loginerror'                 => 'Fahler bei dar Oameldung',
'createaccounterror'         => 'Nutzerkonto konnte ne erstellt waan: $1',
'nocookiesnew'               => 'Der Nutzerzugang wurde erstellt, oaber du biest ne oagemeldet. {{SITENAME}} beneetigt fier diese Funksjonn Cookies, bitte aktiviere diese und melde diech dann miet demm neua Nutzernoama und damm zugehieriga Poaßwurt oa.',
'nocookieslogin'             => '{{SITENAME}} benutzt Cookies zer Oameldung der Benutzer. Du host Cookies deaktiviert, bitte aktiviere diese und versiche is erneut.',
'noname'                     => 'Du muußt enn giltiga Nutzernoama oangahn.',
'loginsuccesstitle'          => 'Oameldung erfolgreich',
'loginsuccess'               => 'Du biest jitz ols „$1“ bei {{SITENAME}} oagemeldet.',
'nosuchuser'                 => 'Dar Nutzernoame „$1“ existiert ne.
Ieberpriefe de Schreibweise (Gruß-/Kleenschreibung beachta) oder [[Special:UserLogin/signup|melde diech ols neuer Benutzer oa]].',
'nosuchusershort'            => 'Dar Nutzernoame „<nowiki>$1</nowiki>“ existiert ne. Bitte ieberpriefe de Schreibweise.',
'nouserspecified'            => 'Bitte gieb enn Benutzernoamen oa.',
'wrongpassword'              => 'Doas Passwurt ies foalsch (oder fehlt). Bitte versuche is erneut.',
'wrongpasswordempty'         => 'Is wurde kei Poaßwurt eigegahn. Bitte versuchs nuch amool.',
'passwordtooshort'           => 'Poaßwurt ungildich oder zu korz: Is muß zim wingsta {{PLURAL:$1|1 Zeecha|$1 Zeecha}} lang sein und derf ne miet dam Benutzernoama iebereistimma.',
'password-name-match'        => 'Dei Poaßwurt muuß siech vu demm Nutzernoama underscheida.',
'mailmypassword'             => 'Neues Passwurt zusenda',
'passwordremindertitle'      => 'Neues Passwurt fier a {{SITENAME}}-Benutzerkonto',
'passwordremindertext'       => 'Jemand miet dar IP-Atresse $1, woahrscheinlich du selbst, hoot  neues Poaßwurt fier de Oameldung bei {{SITENAME}} ($4) oagefurdert.

Doas automatisch generierte Poaßwurt fier Nutzer „$2“ lautet nun: $3

Foalls du dies wirklich gewienscht host, sulltest du diech jitz oamelden und doas Poaßwurt ändern.
Doas neue Poaßwurt ies {{PLURAL:$5|1 Tag|$5 Tage}} giltig.

Bitte ignoriere diese E-Mail, foalls du se ne selbst oagefurdert host. Doas aale Poaßwurt bleit wetterhien giltig.',
'noemail'                    => 'Nutzer „$1“ hoot keene E-Mail-Atresse oagegahn.',
'noemailcreate'              => 'Du mußt anne giltige E-Mail-Atresse oagahn',
'passwordsent'               => 'A neues, temporäres Poaßwurt wurde oa de E-Mail-Atresse vu Nutzer „$1“ gesandt.
Bitte melde diech damit oa, subald du is erhalten host. Doas aale Poaßwurt bleit wetterhien giltig.',
'blocked-mailpassword'       => "De vu dir verwendete IP-Atresse ies fier doas Ändern vu Seyta gesperrt. Im an'n Missbrauch zu verhindern, wurde de Meeglichkeet zer Oaforderung annes neua Poaßwurtes ebenfoalls gesperrt.",
'eauthentsent'               => 'Anne Bestätigungs-E-Mail wurde oa de oagegebene Atresse verschickt.

Bevor anne E-Mail vu andern Nutzern ieber de E-Mail-Funksjonn empfanga waan koan, muuß de Atresse und ihre tatsächliche Zugehierigkeet zu diesem Nutzerkonto erscht bestätigt waan. Bitte befolge de Hinweise ei der Bestätigungs-E-Mail.',
'throttled-mailpassword'     => "Is wurde innerhoalb der letzta {{PLURAL:$1|Stunde|$1 Stunda}} bereits a neues Poaßwurt oagefurdert. Im an'n Missbrauch der Funksjonn zu verhindern, koan ock {{PLURAL:$1|eemool pro Stunde|olle $1 Stunda}} a neues Poaßwurt ongefurdert werden.",
'mailerror'                  => 'Fahler beim Senda der E-Mail: $1',
'acct_creation_throttle_hit' => 'Besicher dieses Wikis, de denne IP-Atresse verwenden, hoan innerhoalb des letzta Tages {{PLURAL:$1|1 Nutzerkonto|$1 Nutzerkonta}} erstellt, woas de maximal erlaubte Oazoahl ei dieser Zeitperiode ies.

Besicher, de diese IP-Atresse verwenden, kinna momentan keene Nutzerkonta meh erstella.',
'emailauthenticated'         => 'Denne E-Mail-Adresse wourde oam $2 im $3 Seeger bestätigt.',
'emailnotauthenticated'      => 'Denne E-Mail-Atresse ies noo ne bestätigt. De folgenda E-Mail-Funksjonna stiehn erscht noach erfolgreicher Bestätigung zer Verfügung.',
'noemailprefs'               => 'Gib anne E-Mail-Atresse ei dann Eenstallunga oa, damit de noachfolgenda Funksjonna zer Verfiegung stiehn.',
'emailconfirmlink'           => 'E-Mail-Adresse bestätiga (authentifizieren).',
'invalidemailaddress'        => 'De E-Mail-Atresse werd ne akzeptiert, weil se a ungiltiges Furmat (eventuell ungiltige Zeecha) zu hoan scheint. Bitte gib anne korrekte Atresse a oder laare doas Feld.',
'accountcreated'             => 'Nutzerkonto erstellt',
'accountcreatedtext'         => 'Is Nutzerkonto fier $1 ies oangeläat wurrn.',
'createaccount-title'        => 'Erstellung annes Nutzerkontos fier {{SITENAME}}',
'createaccount-text'         => 'Is wurde fier diech a Nutzerkonto „$2“ uff {{SITENAME}} ($4) erstellt. Doas automatisch generierte Poaßwurt fier „$2“ ies „$3“. Du sulltest diech nun oamelda und doas Poaßwurt ändern.

Foalls doas Nutzerkonto irrtümlich oagelagt wurde, koast du diese Noachricht ignoriera.',
'usernamehasherror'          => 'Nutzernoama dirfa kee Rautenzeecha enthaala',
'login-throttled'            => 'Du host zu uffte versicht, diech oazumelda.
Bitte warte, bevor du is erneut probierst.',
'loginlanguagelabel'         => 'Sproache: $1',

# Password reset dialog
'resetpass'                 => 'Passwurt ändern',
'resetpass_announce'        => "Anmeldung mi'm per E-Mail zugesandten Code. Im de Anmeldung abzuschließa, mußt du jitz a neues Poaßwurt wähla.",
'resetpass_header'          => 'Passwurt ändern',
'oldpassword'               => 'Aales Passwurt:',
'newpassword'               => 'Neues Passwurt:',
'retypenew'                 => 'Neues Passwurt (nuchmoal):',
'resetpass_submit'          => 'Poaßwurt iebermitteln und oamelda',
'resetpass_success'         => 'Dei Poaßwurt wurde erfolgreich geändert. Is fulgt de Oameldung …',
'resetpass_forbidden'       => 'Doas Poaßwurt koan ne geändert waan.',
'resetpass-no-info'         => 'Du mußt diech oamelda, im uff diese Seyte direkt zuzugreifa.',
'resetpass-submit-loggedin' => 'Poaßwurt ändern',
'resetpass-wrong-oldpass'   => 'Ungiltiges temporäres oder aktuelles Poaßwurt.
Meeglicherweise host du dei Poaßwurt bereits erfolgreich geändert oder a neues temporäres Poaßwurt beantragt.',
'resetpass-temp-password'   => 'Temporäres Poaßwurt:',

# Edit page toolbar
'bold_sample'     => 'Fetter Text',
'bold_tip'        => 'Fetter Text',
'italic_sample'   => 'kursiver Text',
'italic_tip'      => 'kursiver Text',
'link_sample'     => 'Verknipfungstext',
'link_tip'        => 'Interne Verknipfung',
'extlink_sample'  => 'http://www.example.com Verknipfungstittel',
'extlink_tip'     => 'Externe Verknipfung (http:// beoachta)',
'headline_sample' => 'Ieberschreft (Ebene 2)',
'headline_tip'    => 'Ebene 2 Ieberschreft',
'math_sample'     => 'Formel hier eifiega',
'math_tip'        => 'mathematische Formel (LaTeX)',
'nowiki_sample'   => 'unformatierta Text hier eifiega',
'nowiki_tip'      => 'unformatierter Text (nowiki)',
'image_sample'    => 'Beispiel.jpg',
'image_tip'       => 'Verknipfung miet Datei',
'media_sample'    => 'Beispiel.ogg',
'media_tip'       => 'Verknipfung miet Mediadatei',
'sig_tip'         => 'Denne Underschrift miet Zeetstempel',
'hr_tip'          => 'Hurizuntale Linie (sporsam verwenda)',

# Edit pages
'summary'                          => 'Zusammafoassung:',
'subject'                          => 'Betreff:',
'minoredit'                        => 'Ocke Kleenigkeeta wurda verändert',
'watchthis'                        => 'Diese Seyte beoboachta',
'savearticle'                      => 'Seyte oabspeichern',
'preview'                          => 'Vorschau',
'showpreview'                      => 'Vorschau zeega',
'showlivepreview'                  => 'Live-Vorschau',
'showdiff'                         => 'Änderunga zeega',
'anoneditwarning'                  => "Du beorbeetest diese Seyte unoagemeldet. Wenn du speicherst, wird denne aktuelle IP-Atresse ei dar Versionsgeschichte uffgezeechnet on ies damit unwiderruflich '''eeffentlich''' einsehbar.",
'missingsummary'                   => "'''Hinweis:''' Du host kenne Zusommafassung oagegahn. Wenn du erneut uff „Seyte speichern“ klickst, werd denne Änderung ohne Zusommafassung iebernumma.",
'missingcommenttext'               => 'Bitte gib anne Zusommafassung a.',
'missingcommentheader'             => "'''OCHTICHE:''' Du host kenne Ieberschrift eim Feld „Betreff:“ eingegeben. Wenn du erneut uff „Seyte speichern“ klickst, werd denne Beoarbeetung ohne Ieberschrift gespeichert.",
'summary-preview'                  => 'Vurschau dar Zusommafassungszeile:',
'subject-preview'                  => 'Vurschau des Betreffs:',
'blockedtitle'                     => 'Benutzer ies gesperrt',
'blockedtext'                      => "big>'''Dei Nutzernoame oder denne IP-Atresse wurde gesperrt.'''</big>

De Sperrung wurde vu $1 durchgefiehrt.
Ols Grund wurde ''$2'' oagegahn.

* Beginn dar Sperre: $8
* Ende dar Sperre: $6
* Sperre betrifft: $7

Du koast $1 oder an'n dar andern [[{{MediaWiki:Grouppage-sysop}}|Administratorn]] kontaktiern, im ieber de Sperre zu diskuriren.
Du koast de „E-Mail oa diesa Nutzer“-Funksjonn ne nutzen, solange kenne gültige E-Mail-Atresse ei denna [[Special:Preferences|Nutzerkonto-Einstellungen]] eengetraga ies, oder diese Funksjonn fier diech gesperrt wurde.
Denne aktuelle IP-Atresse ies $3, und de Sperr-ID ies $5.
Bitte fiege olle Informationa jeder Oafroage hinzu, de du stallt.",
'autoblockedtext'                  => "Denne IP-Atresse wurde automatisch gesperrt, do se vu a'm andern Nutzer genutzt wurde, dar vu $1 gesperrt wurde.
Ols Grund wurde oagegahn:

:''$2''

* Beginn dar Sperre: $8
* Ende dar Sperre: $6
* Sperre betrifft: $7

Du koast $1 oder an'n dar andern [[{{MediaWiki:Grouppage-sysop}}|Administratorn]] kontaktiern, im ieber de Sperre zu diskuriren.

Du koast de „E-Mail oa diesa Nutzer“-Funksjonn ne nutza, sulange keene giltige E-Mail-Atresse ei dennen [[Special:Preferences|Nutzerkonto-Einstellungen]] eengetraga ies, oder diese Funksjonn fier diech gesperrt wurde.

Denne aktuelle IP-Atresse ies $3, und de Sperr-ID ies $5.
Bitte fiege olle Informationa jeder Oafroage hinzu, de du stallt.",
'blockednoreason'                  => 'keene Begründung oagegahn',
'blockedoriginalsource'            => "Dar Quelltext vu '''$1''' werd hier oagezeigt:",
'blockededitsource'                => "Dar Quelltext '''denner Änderunga''' oa '''$1''':",
'whitelistedittitle'               => 'Zum Beoarbeeta ies is erforderlich, oagemeldet zu sei',
'whitelistedittext'                => 'Du mußt diech $1, im Seyta beoarbeeta zu kinna.',
'confirmedittext'                  => 'Du mußt denne E-Mail-Atresse erscht bestätiga, bevor du Beoarbeetunga durchfiehrn koast. Bitte ergänze und bestätige Denne E-Mail ei dann [[Special:Preferences|Eenstallunga]].',
'nosuchsectiontitle'               => 'Obschnitt ne vurhanda',
'nosuchsectiontext'                => 'Du versichst dann ne vorhandena Obschnitt $1 zu beoarbeeta. Is kinna jedoch ock bereits vorhandene Obschnitte beoarbeetet waan.',
'loginreqtitle'                    => 'Oameldung erforderlich',
'loginreqlink'                     => 'Oanmelda',
'loginreqpagetext'                 => 'Du mußt diech $1, im Seyta lasa zu kinna.',
'accmailtitle'                     => 'Passwurt wourde verschickt',
'newarticle'                       => '(Neu)',
'newarticletext'                   => 'Hier dan Text dar neua Seyte eentraga. Bite oack ei ganza Sätza schreiba on keene urheberrechtsgeschietzta Texte anderer kopiera.',
'anontalkpagetext'                 => "----''Diese Seyte dient dazu, a'm ne oagemeldeta Benutzer Noachrichta zu hinterlassa. Is werd senne IP-Atresse zur Identifizierung verwendet. IP-Atressen kinna vu mehrera Nutzern gemeensam verwendet waan. Wenn du miet dann Kommentarn uff dieser Seyte nischt oafanga koast, richta se siech vermutlich oa an'n friehera Inhaber denner IP-Atresse und du koast se ignorieren. Du koast dir au a [[Special:UserLogin/signup|Nutzerkonto erstalla]] oder dich [[Special:UserLogin|oamelda]], im kinftig Verwechslunga miet andern anonyma Nutzern zu vermeida.''",
'noarticletext'                    => 'Diese Seyte enthält momentan noo kenn Text.
Du koast diesen Tittel uffa andern Seyta [[Special:Search/{{PAGENAME}}|sucha]],
<span class="plainlinks"> ei dan zugeheeriga [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbichern sucha] oder diese Seite [{{fullurl:{{FULLPAGENAME}}|action=edit}} beorbeeta]</span>.',
'noarticletext-nopermission'       => 'Diese Seyte enthält momentan noo kenn Text.
Du koast diesen Tittel uff dann andern Seyta [[Special:Search/{{PAGENAME}}|sicha]]
oder ei dann zugehieriga <span class="plainlinks">[{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbichern sicha].</span>',
'userpage-userdoesnotexist'        => 'Doas Nutzerkonto „$1“ ies ne vurhanda. Bitte priefe, ob du diese Seyte wirklich erstalla/beoarbeeta wielst.',
'userpage-userdoesnotexist-view'   => 'Nutzerkonto „$1“ existiert ne.',
'clearyourcache'                   => "'''Hinweis - Laare noach damm Speichern dann Browser-Cache, im de Änderungen sahn zu kinna:''' '''Mozilla/Firefox/Safari:''' ''Shift'' gedrückt halten und uff ''Aktualisiera'' klicka oder alternativ entweder ''Strg-F5'' oder ''Strg-R'' (''Befehlstaste-R'' bei Macintosh) dricka; '''Konqueror: '''Uff ''Aktualisiera'' klicka oder ''F5'' dricka; '''Opera:''' Cache under ''Extras → Eenstellunga'' laara; '''Internet Explorer:''' ''Strg-F5'' dricka oder ''Strg'' gedrickt halta und dabei ''Aktualisiera'' oaklicka.",
'usercssyoucanpreview'             => "'''Tipp:''' Benutze dann Vurschau-Button, im dei neues CSS vur damm Speichern zu testa.",
'userjsyoucanpreview'              => "'''Tipp:''' Benutze dann Vurschau-Button, im dei neues JS vur damm Speichern zu testa.",
'usercsspreview'                   => "== Vurschau Dennes Nutzer-CSS ==
'''Beachte:''' Noach damm Speichern mußt du dennen Browser oaweisa, de neue Version zu loada: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'                    => "== Vurschau Dennes Nutzer-JavaScript ==
'''Beachte:''' Noach damm Speichern mußt du dennen Browser oaweisa, de neue Version zu loada: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'            => "'''OCHTICHE:''' Skin „$1“ existiert ne. Bedenke, doß nutzerspezifische .css- und .js-Seyta miet a'm Kleenbuchstaba oafanga missa, olso beispielsweise ''{{ns:user}}:Mustermann/monobook.css'' oa Stalle vu ''{{ns:user}}:Mustermoan/Monobook.css''.",
'updated'                          => '(Geändert)',
'note'                             => "'''Hinweis:'''",
'previewnote'                      => "'''Dies ies oack eene Vorschau, de Seyte wurde noo nee gespeichert!'''",
'previewconflict'                  => 'Diese Vurschau gitt dann Inhalt des obern Textfeldes wieder. Su werd de Seyte aussahn, wenn du jitz speicherst.',
'session_fail_preview'             => "'''Denne Beoarbeetung konnte ne gespeichert waan, do Sitzungsdaten verlorn geganga sein.
Bitte versiche is erneut, indem du under dar fulgenda Textvurschau noo amols uff „Seyte speichern“ klickst.
Sullte doas Problem bestiehn bleiba, [[Special:UserLogout|melde diech ob]] und danone wieder oa.'''",
'token_suffix_mismatch'            => "'''Denne Beoarbeetung wurde zerrickegewiesa, do dei Browser Zeecha eim Beoarbeeta-Token verstümmelt hoot.
Anne Speicherung koan dann Seytainhalt zerstiera. Dies geschieht bisweilen durch de Benutzung annes anonyma Proxy-Dienstes, dar fahlerhaft oarbeetet.'''",
'editing'                          => 'Beoarbeeta vo $1',
'editingsection'                   => 'Beoarbeeta vo $1 (Oabsatz)',
'editingcomment'                   => 'Beoarbeeta vo $1 (Kommentar)',
'editconflict'                     => 'Beoarbeetungskonflikt: $1',
'yourtext'                         => 'Deen Text',
'storedversion'                    => 'Gespeicherte Version',
'yourdiff'                         => 'Underschiede',
'copyrightwarning'                 => "'''Bite <big>kopiere kenne Webseita</big>, de nee denne eegena sein, benutze <big>kenne urheberrechtlich geschietzta Werke</big> ohne Erlaubnis des Urhebers!'''<br />
Du gest ons hiermit denne Zusoage, dass du dan Text '''selbst verfasst''' host, dass dar Text Allgemeengutt '''(public domain)''' ies, oder dass dar '''Urheber''' senne '''Zustimmung''' gegeben hoot. Foalls dieser Text bereits woanders vereeffentlicht wurde, weise bite uff dar Diskussionsseite darauf hin.
<i>Bite beachte, dass olle {{SITENAME}}-Beiträge automatisch under dar „$2“ stieha (siehe $1 für Details). Foalls du nee meechtest, dass deine Arbeit hier voo andern verändert on verbreitet wird, doann dricke nee uff „Seite speichern“.</i>",
'protectedpagewarning'             => "'''OCHTICHE: Diese Seyte wurde gesperrt. Ock Nutzer miet Administratorrechta kinna de Seyte beoarbeeta.'''",
'templatesused'                    => 'Folgende Vorlaga waan voo dieser Seyte verwendet:',
'templatesusedpreview'             => 'Fulgende Vurlaga waan voo dieser Seitavorschau verwendet:',
'templatesusedsection'             => '{{PLURAL:$1|De fulgende Vurloage werd|Fulgende Vurloaga waan}} vu diesem Obschnitt verwendet:',
'template-protected'               => '(schreibgeschietzt)',
'template-semiprotected'           => '(schreibgeschietzt fier unoagemeldete on neue Benutzer)',
'hiddencategories'                 => 'Diese Seite ies Mitglied vun {{PLURAL:$1|1 versteckter Kategorie|$1 versteckta Kategoria}}:',
'nocreatetitle'                    => 'De Erstellung neuer Seyta ies eengeschränkt.',
'nocreatetext'                     => 'Uff {{SITENAME}} wurde doas Erstalla neuer Seyta eengeschränkt. Du koast bestiehende Seyten ändern oder diech [[Special:UserLogin|oamelda]].',
'nocreate-loggedin'                => 'Du host kenne Berechtigung, neue Seyta zu erstalla.',
'permissionserrors'                => 'Berechtigungsfehler',
'permissionserrorstext'            => 'Du best ne berechtigt, de Aksjonn auszufiehra. {{PLURAL:$1|Grund|Grinde}}:',
'permissionserrorstext-withaction' => 'Du best nee berechtigt, $2.
{{PLURAL:$1|Grund|Griende}}:',
'moveddeleted-notice'              => 'Diese Seyte wurde geläscht. Is fulgt a Auszug aus damm Läsch- und Verschiebungs-Logbuch fier diese Seyte.',
'log-fulllog'                      => 'Olle Logbucheinträge oasahn',
'edit-hook-aborted'                => 'De Beoarbeetung wurde ohne Erklärung durch anne Schnittstalle obgebrocha.',
'edit-gone-missing'                => 'De Seyte konnt ne aktualisiert waan.
Se wurde anscheinend geläscht.',
'edit-conflict'                    => 'Beoarbeetungskonflikt.',
'edit-no-change'                   => 'Denne Beoarbeetung wurde ignoriert, do kenne Änderung oa damm Text vurgenumma wurde.',

# Parser/template warnings
'expensive-parserfunction-category'      => 'Seyta, de uffwändige Parserfunksjonna zu uffte uffrufa',
'post-expand-template-argument-category' => 'Seyta, de ignorierte Vurlagaargumente enthalta',
'parser-template-loop-warning'           => 'Vurloagaschleife entdeckt: [[$1]]',

# Account creation failure
'cantcreateaccounttitle' => 'Nutzerkonto koan ne erstallt waan',

# History pages
'viewpagelogs'           => 'Logbicher fier diese Seite oazeega',
'nohistory'              => 'Is gitt kenne Versionsgeschichte fier diese Seyte.',
'currentrev'             => 'Aktuelle Version',
'currentrev-asof'        => 'Aktuelle Version vum $1',
'revisionasof'           => 'Version vum $1',
'revision-info'          => 'Version vum $4, $5 Seeger vu $2',
'previousrevision'       => '← aale Version',
'nextrevision'           => 'Neue Version →',
'currentrevisionlink'    => 'Aktuelle Version',
'cur'                    => 'Aktuell',
'next'                   => 'Nächste',
'last'                   => 'Vorherige',
'page_first'             => 'Oanfoang',
'page_last'              => 'Ende',
'histlegend'             => 'Zerr Oazeege dar Änneronga eefach de zu vergleichenda Versiona auswähla on de Schaltfläche „{{int:compareselectedversions}}“ klicka.<br />
* (Aktuell) = Underschied zerr aktuella Version, (Vurherige) = Underschied zerr vurheriga Version
* Seegerzeit/Datum = Version zu dieser Zeit, Benutzername/IP-Atresse des Beorbeeters, K = Kleene Ännerong',
'history-fieldset-title' => 'Suche ei dar Versionsgeschichtla',
'history-show-deleted'   => 'ock geläschte Versiona',
'histfirst'              => 'aalteste',
'histlast'               => 'Neueste',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'           => '(laar)',

# Revision feed
'history-feed-title'          => 'Versionsgeschichtla',
'history-feed-item-nocomment' => '$1 oam $3 im $4 Seeger',

# Revision deletion
'rev-deleted-comment'        => '(Beorbeetungskommentar entfernt)',
'rev-deleted-user'           => '(Benutzernoame entfernt)',
'rev-deleted-event'          => '(Logbuchaksjonn entfernt)',
'rev-suppressed-text-view'   => "Diese Version wurde '''underdrickt'''.
Administratorn kinna se eensahn; Details stiehn eim [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Underdrickungs-Logbuch].",
'rev-delundel'               => 'zeege/verstecke',
'rev-showdeleted'            => 'zeige',
'revisiondelete'             => 'Versiona löscha/wiederherstella',
'revdelete-nooldid-title'    => 'Keene Version oangegeba',
'revdelete-nologtype-title'  => 'Kee Logtyp oagegahn',
'revdelete-nologid-title'    => 'Ungiltiger Logeentrag',
'revdelete-no-file'          => 'De oagegeahne Datei existiert ne.',
'revdelete-show-file-submit' => 'Ju',
'revdelete-legend'           => 'Setza der Sichtbarkeits-Einschränkunga',
'revdelete-hide-text'        => 'Text dar Version verstecka',
'revdelete-hide-name'        => 'Logbichl-Aksjonn verstecka',
'revdelete-hide-comment'     => 'Beorbeetungskommentar verstecka',
'revdelete-hide-user'        => 'Benutzernoame/de IP des Beorbeeters verstecka',
'revdelete-hide-restricted'  => 'Daten au vur Administratorn und andern underdricka',
'revdelete-suppress'         => 'Grund der Läschung au vor Administratora verstecka',
'revdelete-hide-image'       => 'Bildinhalt verstecka',
'revdelete-unsuppress'       => 'Einschränkungen fier wiederhergestallte Versionn uffhebn',
'revdelete-log'              => 'Grund dar Läschung:',
'revdelete-submit'           => 'Uff ausgewählte {{PLURAL:$1|Version|Versiona}} oawenda',
'revdelete-logentry'         => 'hoot de Versionsansicht fier „[[$1]]“ geändert',
'logdelete-logentry'         => 'hoot de Sichtbarkeit fier „[[$1]]“ geändert',
'revdelete-success'          => "'''De Versionsoasicht wurde aktualisiert.'''",
'revdelete-failure'          => "'''De Versionssichtbarkeit konnte ne aktualisiert waan:'''
$1",
'logdelete-failure'          => "'''Logbuchsichtbarkeit koan ne geändert waan:'''
$1",
'revdel-restore'             => 'Sichtbarkeit ändern',
'pagehist'                   => 'Versionsgeschichte',
'deletedhist'                => 'Geläschte Versiona',
'revdelete-content'          => 'Seytainhoalt',
'revdelete-summary'          => 'Zusommafoassungskommentar',
'revdelete-uname'            => 'Benutzernoame',
'revdelete-restricted'       => 'Einschränkungen gelten au fier Administratorn',
'revdelete-unrestricted'     => 'Einschränkungen fier Administratorn uffgehobn',
'revdelete-hid'              => 'versteckte $1',
'revdelete-unhid'            => 'machte $1 wieder effentlich',
'revdelete-log-message'      => '$1 fier $2 {{PLURAL:$2|Version|Versiona}}',
'logdelete-log-message'      => '$1 fier $2 {{PLURAL:$2|Logbucheintrag|Logbucheinträge}}',
'revdelete-modify-missing'   => 'Fahler beim Beoarbeeta vu ID $1: Is fahlt ei dar Datenbank!',
'revdelete-no-change'        => "'''Warnung:''' Dar Eentrag vum $1, $2 Seeger besitzt bereits de gewüischta Sichtbarkeitseenstallunga.",
'revdelete-otherreason'      => 'Andere/zusätzliche Begriendung:',
'revdelete-edit-reasonlist'  => 'Läschgrinde beoarbeeta',
'revdelete-offender'         => 'Autor dar Version:',

# Suppression log
'suppressionlog' => 'Oversight-Logbichl',

# History merging
'mergehistory'                => 'Versionsgeschichta vereina',
'mergehistory-box'            => 'Versionsgeschichta zweier Seyta vereinen',
'mergehistory-from'           => 'Ursprungsseyte:',
'mergehistory-into'           => 'Zielseyte:',
'mergehistory-list'           => 'Versionen, de vereinigt waan kinna',
'mergehistory-go'             => 'Zeige Versiona, de vereinigt waan kinna',
'mergehistory-submit'         => 'Vereinige Versionen',
'mergehistory-empty'          => 'Is kinna kenne Versiona vereinigt waan.',
'mergehistory-success'        => '{{PLURAL:$3|1 Version|$3 Versiona}} vu „[[:$1]]“ erfolgreich noach „[[:$2]]“ vereinigt.',
'mergehistory-fail'           => 'Versionsvereinigung ne meeglich, bitte prife de Seyte und de Zeitoagaba.',
'mergehistory-no-source'      => 'Ursprungsseyte „$1“ ies ne vurhanda.',
'mergehistory-no-destination' => 'Zielseyte „$1“ ies ne vurhanda.',
'mergehistory-invalid-source' => 'Ursprungsseyte muuß a giltiger Seytanoame sei.',
'mergehistory-autocomment'    => '„[[:$1]]“ vereinigt noach „[[:$2]]“',
'mergehistory-comment'        => '„[[:$1]]“ vereinigt noach „[[:$2]]“: $3',
'mergehistory-reason'         => 'Begriendung:',

# Merge log
'mergelog'    => 'Vereinigungs-Logbuch',
'revertmerge' => 'Vereinigung rieckgängig macha',

# Diffs
'history-title'           => 'Versionsgeschichte vun „$1“',
'difference'              => '(Underschied zwischa Versiona)',
'lineno'                  => 'Zeile $1:',
'compareselectedversions' => 'Gewählte Versiona vergleichen',
'editundo'                => 'rieckgängig',

# Search results
'searchresults'                    => 'Suchergebnisse',
'searchresults-title'              => 'Suchergebnisse fier "$1"',
'searchresulttext'                 => 'Fier meh Informationa zer Suche siehe de [[{{MediaWiki:Helppage}}|Helfeseite]].',
'searchsubtitle'                   => 'Denne Suchanfroage: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|olle mit „$1“ beginnenda Seita]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Seiten, de noach „$1“ verlinka]])',
'searchsubtitleinvalid'            => 'Denne Suchanfroage: „$1“.',
'titlematches'                     => 'Iebereinstimmunga miet Seytatitteln',
'notitlematches'                   => 'Kenne Iebereinstimmunga miet Seytatitteln',
'textmatches'                      => 'Iebereinstimmunga miet Inhalta',
'notextmatches'                    => 'Kenne Iebereinstimmunga miet Inhalta',
'prevn'                            => 'vurherige {{PLURAL:$1|$1}}',
'nextn'                            => 'nächste {{PLURAL:$1|$1}}',
'nextn-title'                      => '{{PLURAL:$1|Folgendes Ergebnis|Folgende $1 Ergebnisse}}',
'shown-title'                      => 'Zeige $1 {{PLURAL:$1|Ergebnis|Ergebnisse}} pro Seyte',
'viewprevnext'                     => 'Zeige ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Suchoptiona',
'searchmenu-new'                   => "'''Erstalle de Seyte „[[:$1|$1]]“ ei diesem Wiki.'''",
'searchprofile-articles'           => 'Inhaltsseyta',
'searchprofile-project'            => 'Helfe on Projektseyta',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Olles',
'searchprofile-advanced'           => 'Erweitert',
'searchprofile-articles-tooltip'   => 'Sucha ei $1',
'searchprofile-project-tooltip'    => 'Sucha ei $1',
'searchprofile-images-tooltip'     => 'Noach Bildern sucha',
'searchprofile-everything-tooltip' => 'Gesamta Inhalt durchsicha (inklusive Dischkursseyta)',
'searchprofile-advanced-tooltip'   => 'Siche ei wettern Noamensräuma',
'search-result-size'               => '$1 ({{PLURAL:$2|1 Wurt|$2 Wärter}})',
'search-result-score'              => 'Relevanz: $1 %',
'search-redirect'                  => '(Weiterleitung vun „$1“)',
'search-section'                   => '(Obschnitt $1)',
'search-suggest'                   => 'Meitest du „$1“?',
'search-interwiki-caption'         => 'Schwasterprujätte',
'search-interwiki-default'         => '$1 Ergebnisse:',
'search-interwiki-more'            => '(weitere)',
'search-mwsuggest-enabled'         => 'miet Vurschläga',
'search-mwsuggest-disabled'        => 'kääne Vurschläge',
'search-relatedarticle'            => 'Verwandte',
'mwsuggest-disable'                => 'Vurschläge per Ajax deaktiviern',
'searchrelated'                    => 'verwandt',
'searchall'                        => 'olle',
'showingresultsheader'             => "{{PLURAL:$5|Ergebnis '''$1''' vu '''$3'''|Ergebnisse '''$1–$2''' vu '''$3'''}} fier '''$4'''",
'nonefound'                        => "'''Hinweis:''' Is waan standardmäßig ocke eenige Noamensräume dorchsucht. Setze ''all:'' vur denn Suchbegriff, im olle Seita (inkl. Diskussionsseiten, Vorlagen usw.) zu dorchsucha oder gezielt dan Noama des zu dorchsuchenden Noamensraumes.",
'powersearch'                      => 'Erweiterte Suche',
'powersearch-legend'               => 'Erweiterte Suche',
'powersearch-ns'                   => 'Suche ei Noamasräuma:',
'powersearch-redir'                => 'Weiterleitunga oanzeega:',
'powersearch-field'                => 'Suche noach:',
'powersearch-togglelabel'          => 'Wähle aus:',
'powersearch-toggleall'            => 'Olle',
'powersearch-togglenone'           => 'Kenne',
'search-external'                  => 'Externe Suche',

# Quickbar
'qbsettings'               => 'Seytaleiste',
'qbsettings-none'          => 'Keene',
'qbsettings-fixedleft'     => 'Links, fest',
'qbsettings-fixedright'    => 'Rechts, fest',
'qbsettings-floatingleft'  => 'Links, schwebend',
'qbsettings-floatingright' => 'Rechts, schwebend',

# Preferences page
'preferences'               => 'Eenstellunga',
'mypreferences'             => 'Meene Eistellunga',
'prefs-edits'               => 'Oazoahl dar Beoarbeetunga:',
'prefsnologin'              => 'Ne oagemeldet',
'changepassword'            => 'Poaßwurt ändern',
'prefs-skin'                => 'Skin',
'skin-preview'              => 'Vorschau',
'prefs-math'                => 'TeX',
'prefs-datetime'            => 'Datum und Zeit',
'prefs-personal'            => 'Nutzerdaten',
'prefs-rc'                  => 'Letzte Änderunga',
'prefs-watchlist'           => 'Beobachtungsliste',
'prefs-watchlist-days'      => 'Oazoahl dar Tage, de de Beobachtungsliste standardmäßig umfassa sull:',
'prefs-watchlist-days-max'  => 'Maximal 7 Tage',
'prefs-watchlist-edits'     => 'Maximale Zoahl dar Eenträge:',
'prefs-watchlist-edits-max' => 'Maximale Oazoahl: 1000',
'prefs-watchlist-token'     => 'Beobachtungslisten-Token:',
'prefs-misc'                => 'Verschiedenes',
'prefs-resetpass'           => 'Poaßwurt ändern',
'prefs-email'               => 'E-Mail-Optionen',
'prefs-rendering'           => 'Aussahn',
'saveprefs'                 => 'Eenstallunga speichern',
'resetprefs'                => 'Eingaben verwerfen',
'restoreprefs'              => 'Olle Standardeinstallunga wiederherstalla',
'prefs-editing'             => 'Beorbeeta',
'prefs-edit-boxsize'        => 'Griße des Beoarbeetungsfansters:',
'rows'                      => 'Zeila:',
'columns'                   => 'Spalta:',
'searchresultshead'         => 'Suche',
'resultsperpage'            => 'Treffer pro Seyte:',
'contextlines'              => 'Zeila pro Treffer:',
'contextchars'              => 'Zeecha pro Zeile:',
'stub-threshold'            => 'Linkformatierung <a href="#" class="stub">klenner Seyta</a> (ei Byte):',
'recentchangesdays'         => 'Oazoahl dar Tage, de de Liste dar „Letzta Änderunga“ standardmäßig imfassa sull:',
'recentchangesdays-max'     => 'Maximal $1 {{PLURAL:$1|Tag|Tage}}',
'savedprefs'                => 'Denne Einstallunga waan gespeichert.',
'timezonelegend'            => 'Zeitzone:',
'localtime'                 => 'Urtszeit:',
'timezoneuseserverdefault'  => 'Standardzeit des Servers',
'timezoneoffset'            => 'Underschied¹:',
'servertime'                => "Aktuelle Zeit uff'm Server:",
'guesstimezone'             => 'Vum Browser iebernahma',
'timezoneregion-africa'     => 'Offreka',
'timezoneregion-america'    => 'Amerika',
'timezoneregion-arctic'     => 'Arktis',
'timezoneregion-asia'       => 'Asien',
'timezoneregion-atlantic'   => 'Atlantischer Ozean',
'allowemail'                => 'E-Mail-Empfang vu andern Benutzern ermeeglichn',
'prefs-searchoptions'       => 'Sichoptiona',
'prefs-namespaces'          => 'Noamasräume',
'default'                   => 'Voreinstellung',
'prefs-files'               => 'Dateien',
'prefs-custom-css'          => 'Benutzerdefinierte CSS',
'prefs-custom-js'           => 'Nutzerdefiniertes JS',
'prefs-emailconfirm-label'  => 'E-Mail-Bestätigung:',
'prefs-textboxsize'         => 'Griße des Beoarbeetungsfansters',
'youremail'                 => 'E-Mail-Adresse:',
'username'                  => 'Benutzernoame:',
'uid'                       => 'Benutzer-ID:',
'prefs-memberingroups'      => 'Mitglied dar {{PLURAL:$1|Nutzergruppe|Nutzergruppen}}:',
'yourrealname'              => 'Echter Noame:',
'yourlanguage'              => 'Sproache der Benutzeroberfläche:',
'yourvariant'               => 'Variante',
'yournick'                  => 'Unterschrift:',
'badsiglength'              => 'Ihre Underschrift derf ne länger sein als wie $1 {{PLURAL:$1|character|Zeecha}}.',
'yourgender'                => 'Geschlecht:',
'gender-unknown'            => 'ne oagagahn',
'gender-male'               => 'männlich',
'gender-female'             => 'weiblich',
'email'                     => 'E-mail',
'prefs-info'                => 'Basisinformationen',
'prefs-signature'           => 'Underschrift',
'prefs-dateformat'          => 'Datumsfurmat',
'prefs-timeoffset'          => 'Zeitunderschied',
'prefs-display'             => 'Oazeigeoptiona',

# User rights
'editusergroup'           => 'Benutzerrechte beoarbeeta',
'userrights-groupsmember' => 'Mitglied vun:',

# Groups
'group'               => 'Gruppe:',
'group-user'          => 'Benutzer',
'group-autoconfirmed' => 'Automatisch bestätigte Benutzer',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratora',
'group-suppress'      => 'Oversighter',
'group-all'           => '(olle)',

'group-user-member'       => 'Notzer',
'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administrator',
'group-bureaucrat-member' => 'Birokrat',
'group-suppress-member'   => 'Oversighter',

'grouppage-user'     => '{{ns:project}}:Benutzer',
'grouppage-sysop'    => '{{ns:project}}:Administratora',
'grouppage-suppress' => '{{ns:project}}:Oversighter',

# Rights
'right-read'                 => 'Seyta lasa',
'right-edit'                 => 'Seyta beorbeeta',
'right-minoredit'            => 'Beoarbeetunga ols kleen markiern',
'right-move'                 => 'Seyta verschieba',
'right-move-subpages'        => 'Seyta inklusive Underseyta verschieba',
'right-movefile'             => 'Dateien verschieba',
'right-upload'               => 'Dateien huchloada',
'right-upload_by_url'        => "Dateien vu a'r URL-Atresse huchloada",
'right-writeapi'             => 'Nutzung dar writeAPI',
'right-delete'               => 'Seyta läscha',
'right-browsearchive'        => 'Noach geläschta Seyta sicha',
'right-undelete'             => 'Seyta wiederherstalla',
'right-suppressionlog'       => 'Private Logbicher oasahn',
'right-block'                => 'Nutzer sperra (Schreibrecht)',
'right-ipblock-exempt'       => 'Ausnahm vu IP-Sperra, Autoblocks und Rangesperra',
'right-editinterface'        => 'Nutzeroberfläche beoarbeeta',
'right-editusercssjs'        => 'Fremde CSS- und JS-Dateien beoarbeeta',
'right-editusercss'          => 'Fremde CSS-Datei beoarbeeta',
'right-edituserjs'           => 'Fremde JS-Datei beoarbeeta',
'right-trackback'            => 'Trackback iebermitteln',
'right-userrights'           => 'Nutzerrechte beoarbeeta',
'right-userrights-interwiki' => 'Nutzerrechte ei andern Wikis beoarbeeta',
'right-reset-passwords'      => 'Poaßwurt annes andern Nutzers zerrickesetza',
'right-sendemail'            => 'E-Mails oa andere Nutzer senda',

# User rights log
'rightslog'      => 'Rechte-Logbuch',
'rightslogtext'  => 'Dies ies doas Logbuch dar Änderunga dar Nutzerrechte.',
'rightslogentry' => 'änderte de Nutzerrechte fier „$1“ vu „$2“ uff „$3“',
'rightsnone'     => '(–)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'de Seyte zu lasa',
'action-edit'               => 'de Seite zu beoarbeeta',
'action-createpage'         => 'Seyta zu erschtella',
'action-createtalk'         => 'Dischkurseyta zu erstalla',
'action-createaccount'      => 'a Nutzerkonto zu erstalla',
'action-minoredit'          => 'diese Beoarbeetung ols kleen zu markiern',
'action-move'               => 'de Seyte zu verschieba',
'action-move-subpages'      => 'diese Seyte und zugehierige Underseyta zu verschieba',
'action-move-rootuserpages' => 'Haupt-Nutzerseyta zu verschieba',
'action-movefile'           => 'Diese Datei verschieba',
'action-upload'             => 'Dateien huchzuloada',
'action-reupload'           => 'de vorhandene Datei zu ieberschreiba',
'action-writeapi'           => 'de API miet Schreibzugriffa zu verwenda',
'action-delete'             => 'Seyta zu läscha',
'action-deleterevision'     => 'Versionen zu läscha',
'action-deletedhistory'     => 'Liste dar geläschta Versiona zu sahn',
'action-undelete'           => 'de Seyte wiederherzustella',
'action-suppressionlog'     => 'doas private Logbuch eenzusahn',
'action-trackback'          => "an'n Trackback zu iebertraga",
'action-userrights'         => 'Nutzerrechte zu ändern',

# Recent changes
'nchanges'                         => '$1 {{PLURAL:$1|Ännerong|Änneronga}}',
'recentchanges'                    => 'Foarchte Verändarunga',
'recentchanges-legend'             => 'Oazeigeoptiona',
'recentchanges-feed-description'   => 'Verfolge miet diesem Feed de letzta Änneronga ei {{SITENAME}}.',
'recentchanges-label-legend'       => 'Legende: $1.',
'recentchanges-legend-newpage'     => '$1 - neue Seyte',
'recentchanges-label-newpage'      => 'Neue Seyte',
'recentchanges-legend-minor'       => '$1 - klenne Änderung',
'recentchanges-label-minor'        => 'Klenne Änderung',
'recentchanges-legend-bot'         => "$1 - Änderung durch an'n Bot",
'recentchanges-label-bot'          => "Änderung durch an'n Bot",
'recentchanges-legend-unpatrolled' => '$1 - ne-kontrollierte Änderung',
'rcnote'                           => "Oagezeegt {{PLURAL:$1|wird '''1''' Ännerong|waan de letzta '''$1''' Änneronga}} {{PLURAL:$2|des letzta Taages|dar letzta '''$2''' Taage}}. Stand: $4, $5. (<b><tt>N</tt></b>&nbsp;– neuer Eentrag; <b><tt>K</tt></b>&nbsp;– kleene Ännerong; <b><tt>B</tt></b>&nbsp;– Ännerong dorch eena Bot; ''(± Zoahl)''&nbsp;– Greeßaännerong ei Byte)",
'rcnotefrom'                       => "Oagezeigt waan de Änderunga seit '''$2''' (max. '''$1''' Einträge).",
'rclistfrom'                       => 'Oack Änneronga seit $1 zeiga.',
'rcshowhideminor'                  => 'Klenne Änderunga $1',
'rcshowhidebots'                   => 'Bots $1',
'rcshowhideliu'                    => 'Oagemeldete Benutzer $1',
'rcshowhideanons'                  => 'Anonyme Benutzer $1',
'rcshowhidepatr'                   => 'Kontrollierte Änderunga $1',
'rcshowhidemine'                   => 'Eegene Beiträge $1',
'rclinks'                          => 'Zeige de letzta $1 Änneronga dar letzta $2 Taage.<br />$3',
'diff'                             => 'Unt.',
'hist'                             => 'Versiona',
'hide'                             => 'ausblenda',
'show'                             => 'eenblenda',
'minoreditletter'                  => 'K',
'newpageletter'                    => 'N',
'boteditletter'                    => 'B',
'rc_categories_any'                => 'Olle',
'newsectionsummary'                => 'Neuer Obschnitt /* $1 */',
'rc-enhanced-expand'               => 'Details oazeega (beneetigt JavaScript)',
'rc-enhanced-hide'                 => 'Details verstecka',

# Recent changes linked
'recentchangeslinked'         => 'Änderunga oa verlinkta Seyta',
'recentchangeslinked-feed'    => 'Änderunga oa verlinkta Seyta',
'recentchangeslinked-toolbox' => 'Änderunga oa verlinkta Seyta',
'recentchangeslinked-title'   => 'Änneronga oa Seyta, de voo „$1“ verlinkt sein',
'recentchangeslinked-summary' => "Diese Spezialseyte listet de letzta Änderunga oa dan verlinkta Seyta uff (bzw. bei Kategoria oa dan Mitgliedern dieser Kategorie). Seyta uff denner [[Special:Watchlist|Beobachtungsliste]] sein '''fett''' dargestellt.",
'recentchangeslinked-page'    => 'Seyte:',
'recentchangeslinked-to'      => 'Zeige Änneronga uff Seita, de hierher verlinka',

# Upload
'upload'                => 'Huchloada',
'uploadbtn'             => 'Datei huchloada',
'reuploaddesc'          => 'Oabbrecha un zurieck zur Huchloada-Seete',
'upload-tryagain'       => 'Geänderte Dateibeschreibung obschicka',
'uploadnologin'         => 'Nä oangemeldet',
'uploadnologintext'     => 'Du musst [[Special:UserLogin|oangemeldet sein]], um Dateia huchloada zu könna.',
'uploaderror'           => 'Fehler beim Huchloada',
'upload-permitted'      => 'Erlaubte Dateitypen: $1.',
'upload-preferred'      => 'Bevorzugte Dateitypen: $1.',
'upload-prohibited'     => 'Ne erlaubte Dateitypen: $1.',
'uploadlog'             => 'Datei-Logbuch',
'uploadlogpage'         => 'Datei-Logbuch',
'filename'              => 'Dateinoame',
'filedesc'              => 'Beschreibung',
'fileuploadsummary'     => 'Beschreibung/Quelle:',
'filereuploadsummary'   => 'Dateiänderunga:',
'filestatus'            => 'Copyright-Status:',
'filesource'            => 'Quelle:',
'uploadedfiles'         => 'Huchgeloadene Dateien',
'ignorewarnings'        => 'Warnunga ignoriera',
'minlength1'            => "Dateinoama missa mindestens an'n Buchstaba lang sei.",
'badfilename'           => 'Der Dateinoame wourde ei „$1“ geändert.',
'filetype-badmime'      => "Dateien mi'm MIME-Typ „$1“ dirfa ne huchgeloada waan.",
'filetype-missing'      => 'Due hochzuloadende Datei hoot kenne Erweiterung (z. B. „.jpg“).',
'large-file'            => 'De Dateigriße sullte noach Meeglichkeet $1 ne ieberschreita. Diese Datei ies $2 gruß.',
'largefileserver'       => 'De Datei ies grißer ols de vum Server eengestallte Maximalgriße.',
'emptyfile'             => 'De huchgeloadene Datei ies laar. Dar Grund koan a Tippfahler eim Dateinoama sei. Bitte kontrolliere, ob du de Datei wirklich huchloada wielst.',
'file-thumbnail-no'     => "Dar Dateinoame beginnt miet '''<tt>$1</tt>'''. Dies deutet uff a Bild verringerter Griße ''(thumbnail)'' hin.
Bitte priefe, ob du doas Bild ei voller Ufflesung vorliegen host und loade dieses under damm Originalnoama huch.",
'file-exists-duplicate' => 'Diese Datei ies a Duplikat dar fulgenda {{PLURAL:$1|Datei|$1 Dateien}}:',
'successfulupload'      => 'Erfolgreich huchgeloada',
'uploadwarning'         => 'Warnung',
'savefile'              => 'Datei speichern',
'uploadedimage'         => 'hoot „[[$1]]“ huchgeloada',
'uploaddisabled'        => 'Huchlada deaktiviert',
'uploaddisabledtext'    => 'Doas Huchloada vu Dateien ies deaktiviert.',
'uploadvirus'           => "Diese Datei enthält an'n Virus! Details: $1",
'upload-source'         => 'Quelldatei',
'sourcefilename'        => 'Quelldatei:',
'sourceurl'             => 'Quell-URL:',
'destfilename'          => 'Zielnoame:',
'upload-maxfilesize'    => 'Maximale Dateigriße: $1',
'upload-description'    => 'Dateibeschreibung',
'upload-options'        => 'Huchloade-Optionen',
'watchthisupload'       => 'Diese Seite beobachta',

'upload-proto-error'        => 'Foalsches Protokoll',
'upload-file-error'         => 'Interner Fehler',
'upload-misc-error'         => 'Unbekennter Fahler beim Huchloada',
'upload-misc-error-text'    => "Beim Huchloada ies a unbekennter Fahler uffgetreta.
Priefe de URL uff Fahler, dann Online-Status dar Seyte und versuche is erneut.
Wenn doas Problem wetter bestieht, informiere an'n [[Special:ListUsers/sysop|System-Administrator]].",
'upload-too-many-redirects' => 'De URL beinhaltete zu viele Wetterleitunga',
'upload-unknown-size'       => 'Unbekennte Grieße',
'upload-http-error'         => 'A HTTP-Fahler ies uffgetreta: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Zugriff verweigert',
'img-auth-nofile'       => 'Datei „$1“ existiert ne.',
'img-auth-streaming'    => 'Loade „$1“.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'      => 'URL ies nee erreichbar',
'upload-curl-error6-text' => 'De oagegahne URL ies ne erreichbar. Priefe suwohl de URL uff Fahler ols au dann Online-Status dar Seyte.',
'upload-curl-error28'     => 'Zeitieberschreitung beim Huchloada',

'license'            => 'Lizenz:',
'license-header'     => 'Lizenz:',
'nolicense'          => 'kenne Vurauswoahl',
'license-nopreview'  => '(is ies kenne Vurschau verfiegbar)',
'upload_source_url'  => '  (giltige, effentlich zugängliche URL)',
'upload_source_file' => '  (anne Datei uff dennem Computer)',

# Special:ListFiles
'listfiles_search_for'  => 'Siche nooch Datei:',
'imgfile'               => 'Datei',
'listfiles'             => 'Dateiliste',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Noame',
'listfiles_user'        => 'Benutzer',
'listfiles_size'        => 'Greeße',
'listfiles_description' => 'Beschreibung',
'listfiles_count'       => 'Versionen',

# File description page
'file-anchor-link'          => 'Datei',
'filehist'                  => 'Dateiversiona',
'filehist-help'             => 'Klicke uff eenen Zeitpunkt, im diese Version zu loada.',
'filehist-deleteall'        => 'Olle Versiona läscha',
'filehist-deleteone'        => 'Diese Version läscha',
'filehist-revert'           => 'zerricke scherga',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Version vum',
'filehist-thumb'            => 'Vurschaubild',
'filehist-thumbtext'        => 'Vurschaubild fier Version vum $1',
'filehist-nothumb'          => 'Kee Vurschaubild vurhanda',
'filehist-user'             => 'Nutzer',
'filehist-dimensions'       => 'Moaße',
'filehist-filesize'         => 'Dateigrieße',
'filehist-comment'          => 'Kommentar',
'filehist-missing'          => 'Datei fahlt',
'imagelinks'                => 'Woas fiehrt bies zum hier',
'linkstoimage'              => 'De {{PLURAL:$1|folgende Seyte verwendet|folgenden $1 Seyta verwenda}} diese Datei:',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Weitere Links]] fier diese Datei.',
'sharedupload'              => 'Diese Datei stommt ous $1 on dorf voo andern Projekta verwendet waan.',
'filepage-nofile-link'      => 'Is existiert kenne Datei mit diesem Noama, oaber du koast [$1 diese Datei huchloada].',
'uploadnewversion-linktext' => 'Eene neue Version dieser Datei huchloada',
'shared-repo-from'          => 'oaus $1',

# File reversion
'filerevert'         => 'Zerrickesetza vun „$1“',
'filerevert-legend'  => 'Datei zerrickesetza',
'filerevert-intro'   => "Du setzt de Datei '''[[Media:$1|$1]]''' uff de [$4 Version vum $2, $3 Seeger] zerricke.",
'filerevert-comment' => 'Grund:',
'filerevert-submit'  => 'Zerrickesetza',

# File deletion
'filedelete'                  => 'Läsche „$1“',
'filedelete-legend'           => 'Läsche Datei',
'filedelete-comment'          => 'Grund:',
'filedelete-submit'           => 'Läscha',
'filedelete-success'          => "'''„$1“''' wourde geläscht.",
'filedelete-nofile'           => "'''„$1“''' ies ne vurhanda.",
'filedelete-otherreason'      => 'Anderer/ergänzender Grund:',
'filedelete-reason-otherlist' => 'Oanderer Grund',
'filedelete-reason-dropdown'  => '* Allgemeene Läschgrinde
** Urheberrechtsverletzung
** Duplikat',
'filedelete-edit-reasonlist'  => 'Läschgrinde beoarbeeta',

# MIME search
'mimesearch' => 'Suche noach MIME-Typ',
'mimetype'   => 'MIME-Typ:',
'download'   => 'Herunderloada',

# Unwatched pages
'unwatchedpages' => 'Ne beobachtete Seyta',

# List redirects
'listredirects' => 'Wetterleitungsliste',

# Unused templates
'unusedtemplates'    => 'Unbenutzte Vurloaga',
'unusedtemplateswlh' => 'Ondere Links',

# Random page
'randompage' => 'Zufoallige Seyte',

# Statistics
'statistics'               => 'Statistik',
'statistics-header-pages'  => 'Seytastatistik',
'statistics-header-edits'  => 'Beoarbeetungsstatistik',
'statistics-header-views'  => 'Seytauffrufstatistik',
'statistics-header-users'  => 'Benutzerstatistik',
'statistics-header-hooks'  => 'Andere Statistika',
'statistics-articles'      => 'Inhaltsseyta',
'statistics-pages'         => 'Seyta',
'statistics-files'         => 'Huchgeladene Dateien',
'statistics-views-total'   => 'Seytaaufrufe gesamt',
'statistics-views-peredit' => 'Seyta uffruffe pro Beoarbeetung',
'statistics-users-active'  => 'Aktive Benutzer',
'statistics-mostpopular'   => 'Meistbesichte Seyta',

'doubleredirects'            => 'Doppelte Weiterleitunga',
'double-redirect-fixed-move' => 'doppelte Wetterleitung uffgelest: [[$1]] → [[$2]]',
'double-redirect-fixer'      => 'RedirectBot',

'brokenredirects'        => 'Kaputte Wetterleitunga',
'brokenredirects-edit'   => 'Beorbeeta',
'brokenredirects-delete' => 'läscha',

'withoutinterwiki'        => 'Seyta ohne Links zu andern Sproacha',
'withoutinterwiki-legend' => 'Präfix',
'withoutinterwiki-submit' => 'Zeige',

# Miscellaneous special pages
'nbytes'                 => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'            => '$1 {{PLURAL:$1|Kategorie|Kategoria}}',
'nlinks'                 => '{{PLURAL:$1|1 Link|$1 Links}}',
'nmembers'               => '{{PLURAL:$1|1 Eentrag|$1 Eenträge}}',
'nrevisions'             => '{{PLURAL:$1|1 Beoarbeetung|$1 Beoarbeetunga}}',
'lonelypages'            => 'Verwaiste Seyta',
'unusedcategories'       => 'Unbenutzte Kategorien',
'unusedimages'           => 'Unbenutzte Dateien',
'popularpages'           => 'Beliebteste Seyta',
'wantedcategories'       => 'Nutzte, oaber ne oagelegte Kategorien',
'wantedpages'            => 'Gewinschte Seyta',
'wantedfiles'            => 'Fahlende Dateien',
'wantedtemplates'        => 'Fahlende Vurloaga',
'mostlinked'             => 'Häufig verlinkte Seyta',
'mostcategories'         => 'Meistkategorisierte Seyta',
'prefixindex'            => 'Olle Seita (mit Präfix)',
'shortpages'             => 'Korze Seyta',
'longpages'              => 'Lange Seyta',
'deadendpages'           => 'Sackgassenseyta',
'protectedpages'         => 'Geschitzte Seyta',
'protectedpages-indef'   => 'Ock unbeschränkt geschitzte Seyta zeiga',
'protectedpages-cascade' => 'Ock Seyta miet Kaskadenschutz',
'protectedtitles'        => 'Gesperrte Tittel',
'listusers'              => 'Nutzerverzeichnis',
'listusers-editsonly'    => 'Zeige ock Nutzer miet Beiträga',
'listusers-creationsort' => 'Noach Erstelldatum sortiera',
'usereditcount'          => '$1 {{PLURAL:$1|Beoarbeetung|Beoarbeetunga}}',
'usercreated'            => 'Erstallt oam $1 im $2 Seeger',
'newpages'               => 'Neue Seyta',
'newpages-username'      => 'Benutzernoame:',
'ancientpages'           => 'Seit längerem unbeoarbeetete Seyta',
'move'                   => 'Verschieba',
'movethispage'           => 'Seyte verschieba',
'notargettitle'          => 'Kenne Seyte oagegahn',
'nopagetitle'            => 'Zielseyte ne vurhanda',
'pager-newer-n'          => '{{PLURAL:$1|nächster|nächste $1}}',
'pager-older-n'          => '{{PLURAL:$1|vurheriger|vurherige $1}}',
'suppress'               => 'Oversight',

# Book sources
'booksources'               => 'ISBN-Suche',
'booksources-search-legend' => 'Suche noach Bezugsquella fier Bicher',
'booksources-go'            => 'Sucha',

# Special:Log
'specialloguserlabel'  => 'Benutzer:',
'speciallogtitlelabel' => 'Tittel:',
'log'                  => 'Logbicher',
'all-logs-page'        => 'Olle Logbicher',
'alllogstext'          => 'Dies ies de kombinierte Oazeige oller ei {{SITENAME}} gefiehrta Logbicher.
De Ausgabe koan durch de Auswoahl des Logbuchtyps, des Nutzers oder des Seytatittels eingeschränkt waan (Gruß-/Kleenschreibung muuß beachtet waan).',
'logempty'             => 'Kenne poaßenda Einträge.',
'log-title-wildcard'   => 'Tittel begennt miet …',

# Special:AllPages
'allpages'       => 'Olle Seyta',
'alphaindexline' => '$1 bis $2',
'nextpage'       => 'Nächste Seyte',
'prevpage'       => 'Vurherige Seyte ($1)',
'allpagesfrom'   => 'Seyta oazeega oab:',
'allpagesto'     => 'Seita oazeega bis:',
'allarticles'    => 'Olle Seyta',
'allinnamespace' => 'Olle Seyta (Noamasraum: $1)',
'allpagesprev'   => 'Vurherige',
'allpagesnext'   => 'Nächste',
'allpagessubmit' => 'Oawenda',
'allpagesprefix' => 'Seyta oazeiga mit Präfix:',

# Special:Categories
'categories'                    => 'Kategoria',
'categoriesfrom'                => 'Zeige Kategorien ob:',
'special-categories-sort-count' => 'Sortierung noach Oazoahl',
'special-categories-sort-abc'   => 'Sortierung noach Alphabet',

# Special:DeletedContributions
'deletedcontributions'             => 'Geläschte Beiträge',
'deletedcontributions-title'       => 'Geläschte Beiträge',
'sp-deletedcontributions-contribs' => 'Benutzerbeiträge',

# Special:LinkSearch
'linksearch'      => 'Weblink-Suche',
'linksearch-pat'  => 'Suchmuster:',
'linksearch-ns'   => 'Noamensraum:',
'linksearch-ok'   => 'Sucha',
'linksearch-line' => '$1 ies verlinkt vun $2',

# Special:ListUsers
'listusersfrom'      => 'Zeige Benutzer ob:',
'listusers-submit'   => 'Zeige',
'listusers-noresult' => 'Kenn Benutzer gefunda.',
'listusers-blocked'  => '(gesperrt)',

# Special:ActiveUsers
'activeusers'          => 'Liste aktiver Benutzer',
'activeusers-noresult' => 'Kenne Benutzer gefunda.',

# Special:Log/newusers
'newuserlogpage'              => 'Neuoameldungs-Logbuch',
'newuserlog-byemail'          => 'doas Passwurt wourde per E-Mail versandt',
'newuserlog-create-entry'     => 'Nutzer wourde neu registriert',
'newuserlog-create2-entry'    => 'erstallte neues Nutzerkonto „$1“',
'newuserlog-autocreate-entry' => 'Nutzerkonto wurde automatisch erstallt',

# Special:ListGroupRights
'listgrouprights'                 => 'Nutzergruppen-Rechte',
'listgrouprights-group'           => 'Gruppe',
'listgrouprights-rights'          => 'Rechte',
'listgrouprights-helppage'        => 'Help:Grupparechte',
'listgrouprights-members'         => '(Mitgliederliste)',
'listgrouprights-addgroup-all'    => 'Nutzer zu olla Gruppa hinzufiega',
'listgrouprights-removegroup-all' => 'Nutzer aus olla Gruppa entferna',

# E-mail user
'mailnologin'      => 'Fahler beim E-Mail-Versand',
'emailuser'        => 'E-Mail oa diesa Benutzer',
'emailpage'        => 'E-Mail oa Benutzer',
'usermailererror'  => "Doas E-Mail-Objekt gab an'n Fahler zerricke:",
'defemailsubject'  => '{{SITENAME}}-E-Mail',
'noemailtitle'     => 'Kenne E-Mail-Atresse',
'nowikiemailtitle' => 'E-Mail-Versand nee meegliech',
'email-legend'     => "E-Mail oa an'n andern {{SITENAME}}-Nutzer senda",
'emailfrom'        => 'Vun:',
'emailto'          => 'Oa:',
'emailsubject'     => 'Betreff:',
'emailmessage'     => 'Noachricht:',
'emailsend'        => 'Senda',
'emailccsubject'   => 'Kopie denner Noachricht oa $1: $2',
'emailsent'        => 'E-Mail verschickt',
'emailsenttext'    => 'Denne E-Mail wurde verschickt.',

# Watchlist
'watchlist'          => 'Beobachtungsliste',
'mywatchlist'        => 'Beobachtungsliste',
'watchlistfor'       => "(fier '''$1''')",
'watchlistanontext'  => 'Du mußt diech $1, im denne Beobachtungsliste zu sahn oder Einträge uff ihr zu beoarbeeta.',
'watchnologin'       => 'Du best ne oagemeldet',
'watchnologintext'   => 'Du mußt [[Special:UserLogin|oagemeldet]] sei, im denne Beobachtungsliste zu beoarbeeta.',
'addedwatch'         => 'Zerr Beobachtungsliste hinzugefiegt',
'addedwatchtext'     => 'De Seyte „<nowiki>$1</nowiki>“ wurde zu denner [[Special:Watchlist|Beobachtungsliste]] hinzugefiegt.

Spätere Änneronga oa dieser Seyte on dar dazugeheeriga Dischkursseite waan durt gelistet on
ei dar Iebersicht dar [[Special:RecentChanges|letzta Änneronga]] ei Fettschrift dargestellt.

Wenn du de Seyte wieder voo denner Beobachtungsliste entferna mechtest, klicke uff dar jeweiligen Seyte uff „nee meh beobachta“.',
'removedwatch'       => 'Vun dar Beobachtungsliste entfernt',
'removedwatchtext'   => 'De Seyte „[[:$1]]“ wurde vun denner [[Special:Watchlist|Beobachtungsliste]] entfernt.',
'watch'              => 'Beobachta',
'watchthispage'      => 'Seyte beobachta',
'unwatch'            => 'nä mehr beobachta',
'unwatchthispage'    => 'Nä mehr beobachta',
'notanarticle'       => 'Keene Seyte',
'notvisiblerev'      => 'Version wurde gelöscht',
'watchlist-details'  => 'Du beobachtest {{PLURAL:$1|1 Seyte|$1 Seyta}}.',
'wlheader-enotif'    => '* Dar E-Mail-Benoachrichtigungsdienst ies aktiviert.',
'watchmethod-recent' => 'Ieberprifa dar letzta Beoarbeetunga fier de Beobachtungsliste',
'watchmethod-list'   => 'Ieberpriefa dar Beobachtungsliste noach letzta Beoarbeetunga',
'iteminvalidname'    => "Problem mi'm Eintrag „$1“, ungiltiger Noame.",
'wlshowlast'         => 'Zeige de Änneronga dar letzta $1 Stonda, $2 Taage oder $3.',
'watchlist-options'  => 'Oazeegeoptiona',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Beobachta …',
'unwatching' => 'Nee beobachta …',

'enotif_mailer'                => '{{SITENAME}}-E-Mail-Benoachrichtigungsdienst',
'enotif_reset'                 => 'Olle Seyta ols besucht markiern',
'enotif_newpagetext'           => 'Doas ies anne neue Seyte.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Benutzer',
'changed'                      => 'geändert',
'created'                      => 'erzeugt',
'enotif_subject'               => '[{{SITENAME}}] De Seyte "$PAGETITLE" wurde vu $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastdiff'              => 'Siehe $1 noach dieser Änderung.',
'enotif_anon_editor'           => 'Anonymer Nutzer $1',

# Delete
'deletepage'            => 'Seyte läscha',
'confirm'               => 'Bestätiga',
'excontent'             => 'Aaler Inhalt: „$1“',
'exblank'               => 'Seyte woar laar',
'delete-confirm'        => 'Läscha vun „$1“',
'delete-legend'         => 'Läscha',
'confirmdeletetext'     => 'Du best dabei, eene Seyte miet olla zugeheeriga ältera Versiona zu läscha. Bite bestätige dazu, dass du dir dar Konsequenza bewusst best, on dass du ei Iebereinstimmung miet dan [[{{MediaWiki:Policy-url}}|Richtlinia]] handelst.',
'actioncomplete'        => 'Aksjonn beendet',
'actionfailed'          => 'Aksjonn fahlgeschlaga',
'deletedtext'           => '„<nowiki>$1</nowiki>“ wurde geläscht. Eim $2 findest du eene Liste dar letzta Läschunga.',
'deletedarticle'        => 'hoot „[[$1]]“ geläscht',
'dellogpage'            => 'Läsch-Logbuch',
'deletionlog'           => 'Läsch-Logbuch',
'reverted'              => 'Uff anne aale Version zerrickegesetzt',
'deletecomment'         => 'Grund dar Läschung:',
'deleteotherreason'     => 'Anderer/ergänzender Grund:',
'deletereasonotherlist' => 'Anderer Grund',
'delete-toobig'         => 'Diese Seyte hoot miet meh ols $1 {{PLURAL:$1|Version|Versionen}} anne siehr lange Versionsgeschichte. Doas Läscha sulcher Seyta wurde eingeschränkt, im anne versehentliche Ieberlastung dar Server zu verhindern.',

# Rollback
'rollback'       => 'Zerrickesetza dar Änderunga',
'rollback_short' => 'Zerrickesetza',
'rollbacklink'   => 'Zerrickesetza',
'rollbackfailed' => 'Zerrickesetza gescheitert',
'editcomment'    => "De Änderungszusommafassung lautet: ''„$1“''.",

# Protect
'protectlogpage'              => 'Seytaschutz-Logbuch',
'protectedarticle'            => 'schietzte „[[$1]]“',
'modifiedarticleprotection'   => 'änderte dan Schutz voo „[[$1]]“',
'unprotectedarticle'          => 'hob dann Schutz vu „[[$1]]“ uff',
'movedarticleprotection'      => 'iebertrug dann Seytaschutz vu „[[$2]]“ uff „[[$1]]“',
'protect-title'               => 'Schutz ändern vu „$1“',
'prot_1movedto2'              => 'hoot „[[$1]]“ nooch „[[$2]]“ verschoba',
'protect-legend'              => 'Seytaschutzstatus ändern',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Sperrdauer:',
'protect_expiry_invalid'      => 'De eengegebene Lengde ies ungieltig.',
'protect_expiry_old'          => 'De Sperrzeit liegt ei dar Vergangenheet.',
'protect-text'                => 'Hier koast du dan Schutzstatus fier de Seite „$1“ asahn on ändern.',
'protect-locked-access'       => "Dei Benutzerkonto verfiegt nee ieber de nuutwendiga Rechte zerr Ännerong des Seitaschutzes. Hier sein de aktuella Seitaschutzeinstellunga fier de Seite '''„$1“:'''",
'protect-cascadeon'           => 'Diese Seyte ies gegenwärtig Teel eener Kaskadensperre. Se ies ei de {{PLURAL:$1|folgende Seyte|folgenda Seyta}} eingebunda, welche durch die Kaskadensperroption geschietzt {{PLURAL:$1|ies|sein}}. Dar Seytaschutzstatus koan fier diese Seyte geändert waan, dies hoot jedoch kenn Einfluss uff de Kaskadensperre:',
'protect-default'             => 'Olle Benutzer',
'protect-fallback'            => 'Is wird de „$1“-Berechtigung beneetigt.',
'protect-level-autoconfirmed' => 'Sperrung fier neue on nee registrierte Benutzer',
'protect-level-sysop'         => 'Oack Administratora',
'protect-summary-cascade'     => 'kaskadierend',
'protect-expiring'            => 'bis $2, $3 Seeger (UTC)',
'protect-expiry-indefinite'   => 'unbeschränkt',
'protect-cascade'             => 'Kaskadierende Sperre – olle ei diese Seyte eingebundena Vorlaga waan ebenfoalls gesperrt.',
'protect-cantedit'            => 'Du koast de Sperre dieser Seite nee ändern, doo du keene Berechtigung zim Beoarbeeta dar Seite host.',
'protect-othertime'           => 'Andere Sperrdauer:',
'protect-othertime-op'        => 'andere Sperrdauer',
'protect-existing-expiry'     => 'Aktuelles Seytaschutzende: $2, $3 Seeger',
'protect-otherreason'         => 'Anderer/ergänzender Grund:',
'protect-edit-reasonlist'     => 'Schutzgrinde beoarbeeta',
'protect-expiry-options'      => '1 Stunde:1 hour,1 Tag:1 day,1 Wuche:1 week,2 Wucha:2 weeks,1 Monat:1 month,3 Monate:3 months,6 Monate:6 months,1 Joahr:1 year,Unbeschränkt:infinite',
'restriction-type'            => 'Schutzstatus',
'restriction-level'           => 'Schutzhiehe',
'minimum-size'                => 'Mindestgreeße',
'maximum-size'                => 'Maximalgreeße:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Beorbeeta',
'restriction-move'   => 'Verschieba',
'restriction-create' => 'Erschtella',
'restriction-upload' => 'Huchloada',

# Restriction levels
'restriction-level-sysop'         => 'geschitzt (ock Administratorn)',
'restriction-level-autoconfirmed' => 'geschitzt (ock oagemeldete, ne-neue Nutzer)',
'restriction-level-all'           => 'olle',

# Undelete
'undelete'                  => 'Geläschte Seyta oazeiga',
'undeletepage'              => 'Geläschte Seyta oazeiga und wiederherstalla',
'viewdeletedpage'           => 'Geläschte Seyta oazeiga',
'undelete-fieldset-title'   => 'Wiederherstalla',
'undelete-revision'         => 'Geläschte Version vu $1 (vum $4 im $5 Seeger), $3:',
'undeletebtn'               => 'Wiederherstalla',
'undeletelink'              => 'oasahn/wiederherstella',
'undeleteviewlink'          => 'oasahn',
'undeletereset'             => 'Abbrechen',
'undeleteinvert'            => 'Auswoahl umkehra',
'undeletecomment'           => 'Begrindung:',
'undeletedarticle'          => 'hoot „[[$1]]“ wiederhergestellt',
'undeletedrevisions-files'  => '{{PLURAL:$1|1 Version|$1 Versionen}} und {{PLURAL:$2|1 Datei|$2 Dateien}} wurden wiederhergestallt',
'undelete-search-box'       => 'Siche noach geläschta Seyta',
'undelete-search-submit'    => 'Sucha',
'undelete-cleanup-error'    => 'Fahler beim Läscha dar unbenutzta Archiv-Version $1.',
'undelete-error-short'      => 'Fahler beim Wiederherstalla dar Datei $1',
'undelete-show-file-submit' => 'Ju',

# Namespace form on various pages
'namespace'      => 'Noamensraum:',
'invert'         => 'Oauswoahl imkehra',
'blanknamespace' => '(Seyta)',

# Contributions
'contributions'       => 'Benutzerbeiträge',
'contributions-title' => 'Benutzerbeiträge voo „$1“',
'mycontris'           => 'Vu mer verändart',
'contribsub2'         => 'Fier $1 ($2)',
'uctop'               => '(aktuell)',
'month'               => 'on Moonat:',
'year'                => 'bis Joahr:',

'sp-contributions-newbies'     => 'Zeige oack Beiträge neuer Benutzer',
'sp-contributions-newbies-sub' => 'Fier Neulinge',
'sp-contributions-blocklog'    => 'Sperr-Logbuch',
'sp-contributions-deleted'     => 'Geläschte Beiträge',
'sp-contributions-logs'        => 'Logbicher',
'sp-contributions-talk'        => 'Dischkur',
'sp-contributions-userrights'  => 'Nutzerrechteverwaltung',
'sp-contributions-search'      => 'Suche noach Benutzerbeiträga',
'sp-contributions-username'    => 'IP-Atresse oder Benutzernoame:',
'sp-contributions-submit'      => 'Sucha',

# What links here
'whatlinkshere'            => 'Links uff de Seyte',
'whatlinkshere-title'      => 'Seyta, de uff „$1“ verlinka',
'whatlinkshere-page'       => 'Seyte:',
'linkshere'                => "De folgenden Seyta verlinka uff '''„[[:$1]]“''':",
'nolinkshere'              => "Kenne Seyte verlinkt uff '''„[[:$1]]“'''.",
'isredirect'               => 'Weiterleitungsseyte',
'istemplate'               => 'Vorlageneinbindung',
'isimage'                  => 'Dateilink',
'whatlinkshere-prev'       => '{{PLURAL:$1|vurheriger|vurherige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nächster|nächste $1}}',
'whatlinkshere-links'      => '← Links',
'whatlinkshere-hideredirs' => 'Weiterleitunga $1',
'whatlinkshere-hidetrans'  => 'Vurlageneinbindunga $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-hideimages' => 'Dateilinks $1',
'whatlinkshere-filters'    => 'Filter',

# Block/unblock
'blockip'                      => 'IP-Atresse/Benutzer sperra',
'blockip-legend'               => 'IP-Atresse/Benutzer sperra',
'ipaddress'                    => 'IP-Atresse oder Benutzernoame:',
'ipadressorusername'           => 'IP-Atresse oder Benutzernoame:',
'ipbexpiry'                    => 'Sperrdauer:',
'ipbreason'                    => 'Begriendung:',
'ipbreasonotherlist'           => 'Andere Begrindung',
'ipbreason-dropdown'           => '* Allgemeene Sperrgrinde
** Eenfiega foalscher Informationa
** Laara vu Seyta
** Fiegt massenweise externe Links a
** Einstalla unsinniger Sacha ei Seyta
** bedrohliches Verhaala/Belästigung
** Missbrauch durch mehrere Nutzerkonten
** Ungeeigneter Nutzernoame',
'ipbanononly'                  => 'Ock anonyme Nutzer sperra',
'ipbcreateaccount'             => 'Erstellung vu Nutzerkonten verhindern',
'ipbemailban'                  => 'E-Mail-Versand sperra',
'ipbenableautoblock'           => 'Sperre de aktuell vu diesem Benutzer genutzte IP-Atresse suwie automatisch olle folgenda, vu denen aus ar Beoarbeetunga oder doas Oalega vu Benutzeraccounts versucht',
'ipbsubmit'                    => 'IP-Atresse/Benutzer sperra',
'ipbother'                     => 'Ondere Dauer (englsch):',
'ipboptions'                   => '2 Stonda:2 hours,1 Taag:1 day,3 Taage:3 days,1 Wuche:1 week,2 Wucha:2 weeks,1 Moonat:1 month,3 Moonate:3 months,6 Moonate:6 months,1 Joahr:1 year,Unbeschränkt:infinite',
'ipbotheroption'               => 'Oandere Dauer',
'ipbotherreason'               => 'Andere/ergänzende Begrindung:',
'ipbhidename'                  => 'Nutzernoame ei Beoarbeetunga und Listen verstecka',
'ipbwatchuser'                 => 'Nutzer(dischkur)seyte beobachta',
'ipballowusertalk'             => 'Nutzer darf eegene Dischkurseyta während senner Sperre beoarbeeta',
'badipaddress'                 => 'De IP-Atresse hoot a foalsches Furmat.',
'blockipsuccesssub'            => 'Sperre erfolgreich',
'ipb-edit-dropdown'            => 'Sperrgrinde beoarbeeta',
'ipb-unblock-addr'             => '„$1“ freigahn',
'ipb-blocklist-addr'           => 'Aktuelle Sperre fier „$1“ onzeiga',
'ipb-blocklist-contribs'       => 'Nutzerbeiträge fier „$1“',
'unblockip'                    => 'IP-Atresse freigahn',
'ipusubmit'                    => 'Freigahn',
'unblocked'                    => '[[User:$1|$1]] wurde freigegahn',
'unblocked-id'                 => 'Sperr-ID $1 wurde freigegahn',
'ipblocklist'                  => 'Gesperrte IP-Atressa on Benutzernoama',
'ipblocklist-legend'           => "Siche noach a'm gesperrta Nutzer",
'ipblocklist-username'         => 'Nutzernoame oder IP-Atresse:',
'ipblocklist-sh-userblocks'    => 'Benutzersperra $1',
'ipblocklist-sh-tempblocks'    => 'Befristete Sperra $',
'ipblocklist-sh-addressblocks' => 'IP-Sperra $1',
'ipblocklist-submit'           => 'Sucha',
'ipblocklist-localblock'       => 'Lokale Sperre',
'ipblocklist-otherblocks'      => 'Andere {{PLURAL:$1|Sperre|Sperra}}',
'blocklistline'                => '$1, $2 sperrte $3 (bis $4)',
'infiniteblock'                => 'unbegrenzt',
'expiringblock'                => 'endet oam $1 im $2 Seeger',
'anononlyblock'                => 'ocke Anonyme',
'noautoblockblock'             => 'Autoblock deaktiviert',
'createaccountblock'           => 'Erstellung vu Nutzerkonten gesperrt',
'emailblock'                   => 'E-Mail-Versand gesperrt',
'blocklist-nousertalk'         => 'darf eegene Dischkurseyte ne beoarbeeta',
'ipblocklist-empty'            => 'De Liste enthält kenne Eenträge.',
'ipblocklist-no-results'       => 'De gesuchte IP-Atresse/dar Nutzernoame ies ne gesperrt.',
'blocklink'                    => 'Sperra',
'unblocklink'                  => 'freigahn',
'change-blocklink'             => 'Sperre ändern',
'contribslink'                 => 'Beiträge',
'autoblocker'                  => 'Automatische Sperre, do du anne gemeinsame IP-Atresse miet [[User:$1|$1]] benutzt. Grund dar Nutzersperre: „$2“.',
'blocklogpage'                 => 'Benutzersperr-Logbuch',
'blocklogentry'                => 'sperrte „[[$1]]“ fier dan Zeitraum: $2 $3',
'unblocklogentry'              => 'hoot de Sperre voo „$1“ uffgehoba',
'block-log-flags-anononly'     => 'ock Anonyme',
'block-log-flags-nocreate'     => 'Erstellung voo Benutzerkonta gesperrt',
'block-log-flags-noautoblock'  => 'Autoblock deaktiviert',
'block-log-flags-noemail'      => 'E-Mail-Versand gesperrt',
'block-log-flags-nousertalk'   => 'darf eegene Dischkurseyte ne beoarbeeta',
'block-log-flags-hiddenname'   => 'Nutzernoame versteckt',
'ipb_already_blocked'          => '„$1“ wurde bereits gesperrt.',
'ipb-needreblock'              => '== Sperre vurhanda ==
„$1“ ies bereits gesperrt. Mechtest du de Sperrparameter ändern?',
'ipb-otherblocks-header'       => 'Andere {{PLURAL:$1|Sperre|Sperra}}',
'ip_range_invalid'             => 'Ungiltiger IP-Atressbereich.',
'blockme'                      => 'Sperre miech',
'proxyblocker'                 => 'Proxy blocker',
'proxyblocker-disabled'        => 'Diese Funksjonn ies deaktiviert.',
'proxyblocksuccess'            => 'Fattich',
'cant-block-while-blocked'     => 'Du koast kenne andern Nutzer sperra, während du selbst gesperrt best',

# Developer tools
'lockdb'              => 'Datenbank sperra',
'unlockdb'            => 'Datenbank freigahn',
'lockconfirm'         => 'Ju, iech mechte de Datenbank sperra.',
'unlockconfirm'       => 'Ju, iech mechte de Datenbank freigahn.',
'lockbtn'             => 'Datenbank sperra',
'unlockbtn'           => 'Datenbank freigahn',
'locknoconfirm'       => 'Du host doas Bestätigungsfeld ne markiert.',
'lockdbsuccesssub'    => 'Datenbank wurde erfolgreich gesperrt',
'unlockdbsuccesssub'  => 'Datenbank wourde erfolgreich freigegahn',
'unlockdbsuccesstext' => 'De {{SITENAME}}-Datenbank wourde freigegahn.',
'lockfilenotwritable' => 'De Datenbank-Sperrdatei ies ne beschreibbar. Zum Sperra oder Freigahn dar Datenbank muuß diese fier dann Webserver beschreibbar sei.',
'databasenotlocked'   => 'De Datenbank ies ne gesperrt.',

# Move page
'move-page'                    => 'Verschieba $1',
'move-page-legend'             => 'Seyte verschieba',
'movepagetext'                 => "Miet diesem Formular koast du eene Seyte umbenenna (mitsamt olla Versiona).
Dar aale Tittel wird zim neua weiterleita.
Du koast Weiterleitunga, de uffa Originaltittel verlinka, automatisch korrigiera lassa.
Falls du dies nee tust, priefe uff [[Special:DoubleRedirects|doppelte]] oder [[Special:BrokenRedirects|kaputte Weiterleitunga]].
Du best derfier verantwurtlich, dass Links weiterhin uff doas korrekte Ziel zeiga.

De Seyte wird '''nee''' verschoba, wenn is bereits eene Seyte miet demselba Noama gitt, sufern diese nee laar oder eene Weiterleitung ohne Versionsgeschichte ies. Dies bedeutet, dass du de Seyte zerricke verschieba koast, wenn du eena Fahler gemoacht host. Du koast hingegen kenne Seyte ieberschreiba.

'''Warnung'''
De Verschiebung koan weitreichende on unerwartete Folga fier beliebte Seyta hoan.
Du sulltest daher de Konsequenzen verstanda hoan, bevur du furtfährst.",
'movepagetalktext'             => "De dazugeheerige Dischkursseyte wird, sufern vorhanda, mitverschoba, '''is sei denn:'''
*Is existiert bereits eene Dischkursseyte miet diesem Noama, oder
*du wählst de onda stiehende Option ob.

Ei diesa Fäll muußt du, foalls gewinscht, dan Inhalt dar Seyte voo Hond verschieben oder zusommafiehra.

Bite dan '''neua''' Tittel under '''Ziel''' eintraga, darunder de Umbenennung bite '''begrienda.'''",
'movearticle'                  => 'Seyte verschieba:',
'movenologin'                  => 'Nä oangemeldet',
'movenotallowed'               => 'Du host kenne Berechtigung, Seyta zu verschieba.',
'cant-move-user-page'          => 'Du host kenne Berechtigung, Nutzerhauptseyta zu verschieba.',
'cant-move-to-user-page'       => 'Du host ne de Berechtigung, Seyta uff anne Nutzerseyte zu verschieba (miet Ausnahme vu Nutzerunderseyta).',
'newtitle'                     => 'Ziel:',
'move-watch'                   => 'Diese Seyte beobachta',
'movepagebtn'                  => 'Seite verschieba',
'pagemovedsub'                 => 'Verschiebung erfolgreich',
'movepage-moved'               => "<big>'''De Seyte „$1“ wurde noach „$2“ verschoba.'''</big>",
'articleexists'                => 'Under diesem Noama existiert bereits eene Seyte. Bite wähle eena andern Noama.',
'talkexists'                   => 'De Seyte selbst wurde erfolgreich verschoba, oaber de zugeheerige Dischkursseite nee, doo bereits eene miet dam neua Tittel existiert. Bite gleiche de Inhalte voo Hond ob.',
'movedto'                      => 'verschoba noach',
'movetalk'                     => 'De Dischkurseyte mitverschieba, wenn meegliech',
'move-subpages'                => 'Underseyta verschieba (bis zu $1)',
'movepage-page-exists'         => 'De Seyte „$1“ ies bereits vurhanda und koan ne automatisch ieberschrieba waan.',
'movepage-max-pages'           => 'De Maximalanzoahl vu $1 {{PLURAL:$1|Seyte|Seyta}} wurde verschoba, Olle wettera Seyta kinna ne automatisch verschoba waan.',
'1movedto2'                    => 'hoot „[[$1]]“ noach „[[$2]]“ verschoba',
'1movedto2_redir'              => 'hoot „[[$1]]“ noach „[[$2]]“ verschoba on dabei eene Weiterleitung ieberschrieba',
'move-redirect-suppressed'     => 'Weiterleitung underdrickt',
'movelogpage'                  => 'Verschiebungs-Logbuch',
'movesubpage'                  => '{{PLURAL:$1|Underseyte|Underseyta}}',
'movenosubpage'                => 'Diese Seyte hoot kenne Underseyta.',
'movereason'                   => 'Begriendung:',
'revertmove'                   => 'zerricke scherga',
'delete_and_move'              => 'Läscha und Verschieba',
'delete_and_move_confirm'      => 'Zielseyte fier de Verschiebung läscha',
'delete_and_move_reason'       => 'geläscht, im Ploatz fier Verschiebung zu macha',
'immobile-target-namespace-iw' => 'Interwiki-Link ies kee giltiges Ziel fier Seytaverschiebunga.',
'immobile-source-page'         => 'Diese Seyte ies ne verschiebbar.',
'immobile-target-page'         => 'Is koan ne uff diese Zielseyte verschoba waan.',
'imagenocrossnamespace'        => 'Dateien kinna ne aus damm {{ns:file}}-Noamasraum heraus verschoba waan',
'imagetypemismatch'            => 'De neue Dateierweiterung ies ne miet dar aaln identisch',
'imageinvalidfilename'         => 'Dar Ziel-Dateinoame ies ungiltig',
'fix-double-redirects'         => 'Noach damm Verschieba doppelte Weiterleitunga ufflesa',
'move-leave-redirect'          => 'Weiterleitung erstalla',

# Export
'export'            => 'Seyta exportiera',
'exporttext'        => 'Miet dieser Spezialseyte koast du dann Text inklusive der Versionsgeschichte einzelner Seyta ei anne XML-Datei exportieren.
De Datei koan ei a anderes MediaWiki-Wiki ieber de [[Special:Import|Importfunksjonn]] eingespielt waan.

Trage dann oder de entsprechenden Seytatittel ei doas fulgende Textfeld a (pro Zeile jeweils ock fier anne Seyte).

Alternativ ies dar Export au miet dar Syntax [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] meeglich, beispielsweise fier de [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Ock de aktuelle Version dar Seyte exportieren',
'exportnohistory'   => "'''Hinweis:''' Dar Export kompletter Versionsgeschichta ies aus Performancegrinda bis uff wetteres ne meeglich.",
'export-submit'     => 'Seyta exportiera',
'export-addcattext' => 'Seyta aus Kategorie hinzufiega:',
'export-addcat'     => 'Hinzufiega',
'export-addns'      => 'Hinzufügen',
'export-download'   => 'Ols XML-Datei speichern',
'export-templates'  => 'Inklusive Vorlagen',
'export-pagelinks'  => 'Verlinkte Seyta automatisch miet exportiera, bis zer Rekursionstiefe vu:',

# Namespace 8 related
'allmessages'                   => 'MediaWiki-Systemtexte',
'allmessagesname'               => 'Noame',
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter-unmodified' => 'Unverändert',
'allmessages-filter-all'        => 'Olle',
'allmessages-filter-modified'   => 'Verändert',
'allmessages-prefix'            => 'Präfixfilter:',
'allmessages-language'          => 'Sproache:',
'allmessages-filter-submit'     => 'Lus',

# Thumbnails
'thumbnail-more'       => 'vergrießern',
'filemissing'          => 'Datei fahlt',
'thumbnail_image-type' => 'Bildtyp ne understützt',

# Special:Import
'import'                     => 'Seyta importiern',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-source'    => 'Quell-Wiki/-Seyte:',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Zielnoamasraum:',
'import-upload-filename'     => 'Dateinoame:',
'import-comment'             => 'Grund:',
'importtext'                 => 'Uff dieser Spezialseyte kinna ieber de [[Special:Export|Exportfunktion]] eim Quellwiki exportierte Seyta ei dieses Wiki importiert waan.',
'importstart'                => 'Importiere Seyte …',
'import-revision-count'      => '– {{PLURAL:$1|1 Version|$1 Versionen}}',
'importnopages'              => 'Kenne Seyte zum Importiern vurhanda.',
'importfailed'               => 'Import fehlgeschlaga: $1',
'importunknownsource'        => 'Unbekennte Importquelle',
'importcantopen'             => 'Importdatei konnte ne geeffnet waan',
'importbadinterwiki'         => 'Foalscher Interwiki-Link',
'importnotext'               => 'Laar oder kee Text',
'importsuccess'              => 'Import obgeschlossa.',
'importhistoryconflict'      => 'Is existieren bereits ältere Versionen, welche miet diesen kollidieren. Meeglicherweise wurde de Seyte bereits vorher importiert.',
'importnofile'               => 'Is ies kenne Importdatei ausgewählt worden.',
'importuploaderrorpartial'   => 'Doas Huchloada dar Importdatei ies fehlgeschlagen. De Datei wurde ock teelweise huchgeloada.',
'import-parse-failure'       => 'Fahler beim XML-Import:',
'import-noarticle'           => 'Is wurde kenne zu importierende Seyte oagegahn!',
'import-nonewrevisions'      => 'Is sein kenne neua Versiona zum Import vorhanden, olle Versiona wurden bereits frieher importiert.',
'xml-error-string'           => '$1 Zeile $2, Spalte $3, (Byte $4): $5',
'import-upload'              => 'XML-Daten importiera',
'import-token-mismatch'      => 'Verlust dar Sessiondaten. Bitte versuche is erneut.',
'import-invalid-interwiki'   => "Aus damm oagegahn'n Wiki ies kee Import meeglich.",

# Import log
'importlogpage'                    => 'Import-Logbuch',
'importlogpagetext'                => 'Administrativer Import vu Seyta miet Versionsgeschichte vu andern Wikis.',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Version|Versionen}}',
'import-logentry-interwiki'        => 'hoot „$1“ importiert (Transwiki)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Version|Versiona}} vun $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Denne Nutzerseyte',
'tooltip-pt-anonuserpage'         => 'Nutzerseite dar IP-Atresse vu dar aus du Änderunga durchfiehrst',
'tooltip-pt-mytalk'               => 'Denne Dischkursseyte',
'tooltip-pt-anontalk'             => 'Dischkur ieber Änderunga vu dieser IP-Atresse',
'tooltip-pt-preferences'          => 'Eegene Eenstellunga',
'tooltip-pt-watchlist'            => 'Liste dar beobachteta Seyta',
'tooltip-pt-mycontris'            => 'Liste denner Beiträge',
'tooltip-pt-login'                => 'Siech oazumelda werd zwoar gerne gesahn, ies oaber kenne Pflicht.',
'tooltip-pt-logout'               => 'Oabmelda',
'tooltip-ca-talk'                 => 'Dischkur iebern Inhalt dar Seyte',
'tooltip-ca-edit'                 => 'Seyte beorbeeta. Bite vur dam Speichern de Vorschaufunktion benutza.',
'tooltip-ca-addsection'           => 'Neua Abschnitt beginna',
'tooltip-ca-viewsource'           => 'Diese Seyte ies geschietzt. Dar Quelltext koan oagesahn waan.',
'tooltip-ca-history'              => 'Friehere Versionen vo dieser Seete',
'tooltip-ca-protect'              => 'Diese Seyte schietza',
'tooltip-ca-unprotect'            => 'Diese Seyte freigahn',
'tooltip-ca-delete'               => 'Diese Seyte läscha',
'tooltip-ca-move'                 => 'Diese Seyte verschieba',
'tooltip-ca-watch'                => 'Diese Seyte zer perseenlicha Beobachtungsliste hinzufiega',
'tooltip-ca-unwatch'              => 'Diese Seyte voo derr perseenlicha Beobachtungsliste entferna',
'tooltip-search'                  => '{{SITENAME}} dorchsucha',
'tooltip-search-go'               => 'Gieh direkt zu dar Seyte, de exakt dam eingegebena Noama entspricht.',
'tooltip-search-fulltext'         => 'Suche noach Seyta, de diesen Text enthalta',
'tooltip-p-logo'                  => 'Heetseyte',
'tooltip-n-mainpage'              => 'Heetseyte oanzeiga',
'tooltip-n-mainpage-description'  => 'Heetseyte besicha',
'tooltip-n-portal'                => 'Iebers Portal: woas du tun koast, wo woas zu finda ies',
'tooltip-n-currentevents'         => 'Hintergrundinformationen zu aktuellen Ereignissen',
'tooltip-n-recentchanges'         => 'Liste der letzten Änderungen in {{SITENAME}}',
'tooltip-n-randompage'            => 'Zufällige Seyte',
'tooltip-n-help'                  => 'Hilfeseite anzeigen',
'tooltip-t-whatlinkshere'         => 'Liste oller Seyta, de hierher zeiga',
'tooltip-t-recentchangeslinked'   => 'Letzte Änderungen an Seiten, die von hier verlinkt sind',
'tooltip-feed-rss'                => 'RSS-Feed fier diese Seyte',
'tooltip-feed-atom'               => 'Atom-Feed fier diese Seyte',
'tooltip-t-contributions'         => 'Liste dar Beiträge voo diesem Benutzer oasahn',
'tooltip-t-emailuser'             => 'Eene E-Mail oa diesa Benutzer senda',
'tooltip-t-upload'                => 'Dateia huchloaden',
'tooltip-t-specialpages'          => 'Liste oller Spezialseyta',
'tooltip-t-print'                 => 'Druckansicht dieser Seyte',
'tooltip-t-permalink'             => 'Dauerhofter Link zu dieser Seytaversion',
'tooltip-ca-nstab-main'           => 'Seytainhalt oazeega',
'tooltip-ca-nstab-user'           => 'Benutzerseyte oazeega',
'tooltip-ca-nstab-media'          => 'Mediendateienseyte oazeiga',
'tooltip-ca-nstab-special'        => 'Dies ies eene Spezialseyte. Se koan nee beorbeetet waan.',
'tooltip-ca-nstab-project'        => 'Portalseyte oazeega',
'tooltip-ca-nstab-image'          => 'Dateiseyte oazeega',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki-Systemtext oazeiga',
'tooltip-ca-nstab-template'       => 'Vorlage oazeega',
'tooltip-ca-nstab-help'           => 'Helfeseyte oazeiga',
'tooltip-ca-nstab-category'       => 'Kategorieseyte oazeega',
'tooltip-minoredit'               => 'Diese Änderung ols kleen markiera.',
'tooltip-save'                    => 'Änderunga speichern',
'tooltip-preview'                 => 'Vorschau dar Änderunga oa dieser Seyte. Bite vur dam Speichern benutza!',
'tooltip-diff'                    => 'Zeigt Änderunga oam Text tabellarisch oa',
'tooltip-compareselectedversions' => 'Underschied zwischa zwee ausgewählta Versiona dieser Seyte oazeega.',
'tooltip-watch'                   => 'Fiege diese Seyte denner Beobachtungsliste hinzu',
'tooltip-upload'                  => 'Huchloada starta',
'tooltip-rollback'                => 'Moacht olle letzta Änderunga dar Seite, de vum gleichen Benutzer vurgenumma waan sein, dorch ocke eenen Klick rieckgängig.',
'tooltip-undo'                    => 'Moacht lediglich diese eene Änderung rieckgängig on zeigt doas Resultat ei dar Vorschau oa, damit ei dar Zusommafassungszeile eene Begründung angegeba waan koan.',

# Attribution
'siteuser'      => '{{SITENAME}}-Benutzer $1',
'anonuser'      => 'Anonymer {{SITENAME}}-Benutzer $1',
'othercontribs' => 'Basierend uff dar Orbeet vu $1.',
'others'        => 'oandera',
'creditspage'   => 'Seytainformationa',
'nocredits'     => 'Fier diese Seyte sein kenne Informationa vorhanda.',

# Spam protection
'spamprotectiontitle' => 'Spamschutzfilter',

# Info page
'infosubtitle' => 'Seytainformation',
'numedits'     => 'Oazoahl dar Seyta änderunga: $1',

# Math options
'mw_math_png'    => 'Emmer ols PNG darstalla',
'mw_math_source' => 'Ols TeX belassen (fier Textbrowser)',

# Math errors
'math_failure'          => 'Parser-Fahler',
'math_unknown_error'    => 'Unbekennter Fahler',
'math_unknown_function' => 'Unbekennte Funksjonn',
'math_lexing_error'     => '„Lexing“-Fahler',
'math_syntax_error'     => 'Syntaxfahler',
'math_image_error'      => 'de PNG-Konvertierung schlug fehl',
'math_bad_tmpdir'       => 'Doas temporäre Verzeichnis fier mathematische Formeln koan ne oagelagt oder beschrieba waan.',

# Patrolling
'markaspatrolleddiff'        => 'Ols kontrolliert markiern',
'markaspatrolledtext'        => 'Diese Seyte ols kontrolliert markiernn',
'markedaspatrolled'          => 'Ols kontrolliert markiert',
'markedaspatrolledtext'      => 'De ausgewählte Seyta änderung wurde ols kontrolliert markiert.',
'markedaspatrollederror'     => 'Markierung ols „kontrolliert“ ne meeglich.',
'markedaspatrollederrortext' => 'Du mußt anne Seyta änderung auswähla.',

# Patrol log
'patrol-log-page'      => 'Kontroll-Logbichl',
'patrol-log-header'    => 'Dies ies doas Kontroll-Logbuch.',
'patrol-log-auto'      => '(automatisch)',
'patrol-log-diff'      => 'Version $1',
'log-show-hide-patrol' => 'Kontroll-Logbichl $1',

# Image deletion
'deletedrevision'                 => 'aale Version: $1',
'filedeleteerror-short'           => 'Fahler bei Datei-Läschung: $1',
'filedelete-current-unregistered' => 'De oagegahne Datei „$1“ ies ne ei dar Datenbank vurhanda.',

# Browsing diffs
'previousdiff' => '← Zim vurheriga Versionsunderschied',
'nextdiff'     => 'Zim nächsta Versionsunderschied →',

# Media information
'thumbsize'            => 'Standardgriße dar Vurschaubilder (Thumbnails):',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|1 Seyte|$3 Seyta}}',
'file-info'            => '(Dateigreeße: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Dateigreeße: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Kenne hiehere Uffleesung vurhanda.</small>',
'svg-long-desc'        => '(SVG-Datei, Basisgreeße: $1 × $2 Pixel, Dateigreeße: $3)',
'show-big-image'       => 'Version ei hieherer Uffleesung',
'show-big-image-thumb' => 'small>Greeße dar Vuroasicht: $1 × $2 Pixel</small>',
'file-info-gif-looped' => 'Endlosschleife',
'file-info-gif-frames' => '$1 {{PLURAL:$1|Bild|Bilder}}',

# Special:NewFiles
'newimages'        => 'Neue Dateien',
'newimages-legend' => 'Filter',
'newimages-label'  => 'Dateinoame (oder a Teel davon):',
'showhidebots'     => '(Bots $1)',
'noimages'         => 'Kenne Dateien gefunda.',
'ilsubmit'         => 'Sucha',
'bydate'           => 'noach Datum',

# Bad image list
'bad_image_list' => 'Furmat:

Ock Zeila, de mit eenem * oafanga, waan oausgewertet. Ols erschtes noach dam * muuß a Link uff eene unerwünschte Datei stieha.
Darauf folgende Seitalinks ei derselba Zeile definiera Ausnahma, ei deren Kontext de Datei trotzdem erscheina doarf.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Diese Datei enthält weitere Informationa, de ei dar Regel voo dar Digitalkamera oder dam verwendeta Scanner stoamma. Dorch noachträgliche Bearbeitung der Originaldatei kinna eenige Details verändert worden sein.',
'metadata-expand'   => 'Erweiterte Details eenblenda',
'metadata-collapse' => 'Erweiterte Details oausblenda',
'metadata-fields'   => 'De folgenden Felder derr EXIF-Metadata ei diesem MediaWiki-Systemtext waan uff Bildbeschreibungsseita oagezäat; weitere standardmäßig „eingeklappte“ Details kinna oagezäat waan.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Breite',
'exif-imagelength'                 => 'Länge',
'exif-bitspersample'               => 'Bits pro Forbkomponente',
'exif-compression'                 => 'Oart dar Kompression',
'exif-photometricinterpretation'   => 'Pixelzusommasetzung',
'exif-orientation'                 => 'Kameraausrichtung',
'exif-planarconfiguration'         => 'Datenausrichtung',
'exif-ycbcrpositioning'            => 'Y und C Positionierung',
'exif-xresolution'                 => 'Horizontale Ufflesung',
'exif-yresolution'                 => 'Vertikale Ufflesung',
'exif-resolutionunit'              => 'Maßeinheet dar Ufflesung',
'exif-stripoffsets'                => 'Bilddaten-Versatz',
'exif-rowsperstrip'                => 'Oazoahl Zeila pro Streifa',
'exif-stripbytecounts'             => 'Bytes pro komprimiertem Streifa',
'exif-jpeginterchangeformat'       => 'Offset zu JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Griße dar JPEG-Daten ei Bytes',
'exif-transferfunction'            => 'Iebertragungsfunksjonn',
'exif-whitepoint'                  => 'Manuell miet Messung',
'exif-datetime'                    => 'Speicherzeitpunkt',
'exif-imagedescription'            => 'Bildtittel',
'exif-make'                        => 'Hersteller',
'exif-model'                       => 'Modell',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Fotogroaf',
'exif-copyright'                   => 'Urheberrechte',
'exif-exifversion'                 => 'Exif-Version',
'exif-flashpixversion'             => 'understitzte Flashpix-Version',
'exif-colorspace'                  => 'Forbraum',
'exif-compressedbitsperpixel'      => 'Komprimierte Bits pro Pixel',
'exif-pixelxdimension'             => 'Giltige Bildhiehe',
'exif-makernote'                   => 'Herstallernotiz',
'exif-datetimeoriginal'            => 'Erfassungszeitpunkt',
'exif-exposuretime'                => 'Belichtungsdauer',
'exif-exposuretime-format'         => '$1 Sekunda ($2)',
'exif-fnumber'                     => 'Blende',
'exif-spectralsensitivity'         => 'Spectral Sensitivity',
'exif-isospeedratings'             => 'Film- oder Sensorempfindlichkeit (ISO)',
'exif-shutterspeedvalue'           => 'Belichtungszeitwert',
'exif-aperturevalue'               => 'Blendenwert',
'exif-brightnessvalue'             => 'Helligkeitswert',
'exif-exposurebiasvalue'           => 'Belichtungsvorgabe',
'exif-maxaperturevalue'            => 'Grießte Blende',
'exif-subjectdistance'             => 'Entfernung',
'exif-meteringmode'                => 'Messverfoahrn',
'exif-lightsource'                 => 'Lichtquelle',
'exif-flash'                       => 'Blitz',
'exif-focallength'                 => 'Brennweite',
'exif-subjectarea'                 => 'Bereich',
'exif-flashenergy'                 => 'Blitzstärke',
'exif-focalplanexresolution'       => 'Sensorufflesung hurizuntal',
'exif-subjectlocation'             => 'Motivstandurt',
'exif-exposureindex'               => 'Belichtungsindex',
'exif-sensingmethod'               => 'Messmethode',
'exif-filesource'                  => 'Quelle dar Datei',
'exif-scenetype'                   => 'Szenatyp',
'exif-cfapattern'                  => 'CFA-Muster',
'exif-exposuremode'                => 'Belichtungsmodus',
'exif-whitebalance'                => 'Weeßabgleich',
'exif-digitalzoomratio'            => 'Digitalzoom',
'exif-focallengthin35mmfilm'       => 'Brennweite (Kleenbildäquivalent)',
'exif-scenecapturetype'            => 'Uffnahmeoart',
'exif-gaincontrol'                 => 'Verstärkung',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Sättigung',
'exif-sharpness'                   => 'Schärfe',
'exif-devicesettingdescription'    => 'Geräteeinstallung',
'exif-imageuniqueid'               => 'Bild-ID',
'exif-gpsversionid'                => 'GPS-Tag-Version',
'exif-gpslatituderef'              => 'nördl. oder südl. Breite',
'exif-gpslatitude'                 => 'Geografische Breite',
'exif-gpslongituderef'             => 'östl. oder westl. Länge',
'exif-gpslongitude'                => 'Geografische Länge',
'exif-gpsaltituderef'              => 'Bezugshiehe',
'exif-gpsaltitude'                 => 'Hiehe',
'exif-gpstimestamp'                => 'GPS-Zeit',
'exif-gpssatellites'               => 'Fier de Messung benutzte Satelliten',
'exif-gpsstatus'                   => 'Empfängerstatus',
'exif-gpsmeasuremode'              => 'Messverfoahrn',
'exif-gpsdop'                      => 'Maßpräzision',
'exif-gpsspeedref'                 => 'Geschwindigkeitseinheet',
'exif-gpsspeed'                    => 'Geschwindigkeit des GPS-Empfängers',
'exif-gpstrack'                    => 'Bewegungsrichtung',
'exif-gpsimgdirection'             => 'Bildrichtung',
'exif-gpsdestlatitude'             => 'Breite',
'exif-gpsdestlongitude'            => 'Länge',
'exif-gpsdestbearingref'           => 'Referenz fier Motivrichtung',
'exif-gpsdestbearing'              => 'Motivrichtung',
'exif-gpsdestdistanceref'          => 'Referenz fier de Motiventfernung',
'exif-gpsdestdistance'             => 'Motiventfernung',
'exif-gpsprocessingmethod'         => 'Noame des GPS-Verfahras',
'exif-gpsareainformation'          => 'Noame des GPS-Gebietes',
'exif-gpsdatestamp'                => 'GPS-Datum',
'exif-gpsdifferential'             => 'GPS-Differentialkorrektur',

# EXIF attributes
'exif-compression-1' => 'Unkomprimiert',

'exif-unknowndate' => 'Unbekenntes Datum',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Hurizuntal gespiegelt',
'exif-orientation-3' => 'Im 180° gedreht',
'exif-orientation-4' => 'Vertikal gespiegelt',
'exif-orientation-5' => 'Entgegen damm Seegerzeigersinn im 90° gedreht und vertikal gespiegelt',
'exif-orientation-6' => 'Im 90° eim Seegerzeigersinn gedreht',
'exif-orientation-7' => 'Im 90° eim Seegerzeigersinn gedreht und vertikal gespiegelt',
'exif-orientation-8' => 'Im 90° entgegen damm Seegerzeigersinn gedreht',

'exif-planarconfiguration-1' => 'Grobfurmat',
'exif-planarconfiguration-2' => 'Planarfurmat',

'exif-componentsconfiguration-0' => 'Existiert ne',

'exif-exposureprogram-0' => 'Unbekennt',
'exif-exposureprogram-1' => 'Manuell',
'exif-exposureprogram-2' => 'Standardprogramm',
'exif-exposureprogram-3' => 'Zeitautomatik',
'exif-exposureprogram-4' => 'Blendenautomatik',
'exif-exposureprogram-5' => 'Kreativprogramm miet Bevorzugung huher Schärfentiefe',
'exif-exposureprogram-6' => "Aksjonn-Programm miet Bevorzugung a'r kurza Belichtungszeit",
'exif-exposureprogram-7' => 'Portrait-Programm',
'exif-exposureprogram-8' => 'Landschoftsuffnahma',

'exif-subjectdistance-value' => '$1 Meter',

'exif-meteringmode-0'   => 'Unbekennt',
'exif-meteringmode-1'   => 'Durchschnittlich',
'exif-meteringmode-2'   => 'Mittenzentriert',
'exif-meteringmode-3'   => 'Spotmessung',
'exif-meteringmode-4'   => 'Mehfachspotmessung',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Bildteel',
'exif-meteringmode-255' => 'Unbekennt',

'exif-lightsource-0'   => 'Unbekennt',
'exif-lightsource-1'   => 'Taageslicht',
'exif-lightsource-2'   => 'Fluoreszierend',
'exif-lightsource-3'   => 'Glihlompe',
'exif-lightsource-4'   => 'Blitz',
'exif-lightsource-9'   => 'Schie Waater',
'exif-lightsource-10'  => 'Bewelkt',
'exif-lightsource-11'  => 'Schoatta',
'exif-lightsource-12'  => 'Tageslicht fluoreszierend (D 5700–7100 K)',
'exif-lightsource-13'  => 'Tagesweeß fluoreszierend (N 4600–5400 K)',
'exif-lightsource-14'  => 'Kaltweeß fluoreszierend (W 3900–4500 K)',
'exif-lightsource-15'  => 'Weeß fluoreszierend (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Standardlicht A',
'exif-lightsource-18'  => 'Standardlicht B',
'exif-lightsource-19'  => 'Standardlicht C',
'exif-lightsource-24'  => 'ISO Studio Kunstlicht',
'exif-lightsource-255' => 'Andere Lichtquelle',

# Flash modes
'exif-flash-fired-0'    => 'kee Blitz',
'exif-flash-fired-1'    => 'Blitz ausgelest',
'exif-flash-return-0'   => 'Blitz sendet kenne Daten',
'exif-flash-return-2'   => 'kenne Reflexion des Blitz festgestallt',
'exif-flash-return-3'   => 'Reflexion des Blitz festgestallt',
'exif-flash-mode-1'     => 'erzwungenes Blitza',
'exif-flash-mode-2'     => 'Blitz obgeschaltet',
'exif-flash-mode-3'     => 'Automatik',
'exif-flash-function-1' => 'Kenne Blitzfunksjonn',
'exif-flash-redeye-1'   => 'Rotaugen Reduktion',

'exif-focalplaneresolutionunit-2' => 'Zoll',

'exif-sensingmethod-1' => 'Undefiniert',
'exif-sensingmethod-2' => 'Ein-Chip-Forbsensor',
'exif-sensingmethod-3' => 'Zwee-Chip-Forbsensor',
'exif-sensingmethod-4' => 'Drei-Chip-Forbsensor',
'exif-sensingmethod-5' => 'Forbraum sequentiell Sensor',
'exif-sensingmethod-7' => 'Trilinearer Sensor',
'exif-sensingmethod-8' => 'Forbraum linear sequentieller Sensor',

'exif-scenetype-1' => 'Normal',

'exif-customrendered-0' => 'Standard',
'exif-customrendered-1' => 'Nutzerdefiniert',

'exif-exposuremode-0' => 'Automatische Belichtung',
'exif-exposuremode-1' => 'Manuelle Belichtung',
'exif-exposuremode-2' => 'Belichtungsreihe',

'exif-whitebalance-0' => 'Automatisch',
'exif-whitebalance-1' => 'Manuell',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landschoft',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Noachtszene',

'exif-gaincontrol-0' => 'Keene',
'exif-gaincontrol-1' => 'Gering',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Schwoch',
'exif-contrast-2' => 'Stork',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Gering',
'exif-saturation-2' => 'Huch',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Gering',
'exif-sharpness-2' => 'Stork',

'exif-subjectdistancerange-0' => 'Unbekennt',
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
'exif-gpsstatus-v' => 'Interoperabilität vu Messunga',

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
'edit-externally'      => 'Diese Datei miet eenem externen Programm beorbeeta',
'edit-externally-help' => '(Siehe de [http://www.mediawiki.org/wiki/Manual:External_editors Installationsanweisunga] fier weitere Informationa)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'olle',
'imagelistall'     => 'olle',
'watchlistall2'    => 'olle',
'namespacesall'    => 'olle',
'monthsall'        => 'olle',
'limitall'         => 'olle',

# E-mail address confirmation
'confirmemail'             => 'E-Mail-Atresse bestätiga (Authentifizierung)',
'confirmemail_noemail'     => 'Du host kenne giltige E-Mail-Atresse ei denn [[Special:Preferences|persenlicha Eenstallunga]] eengetraga.',
'confirmemail_text'        => "{{SITENAME}} erfordert, doß du denne E-Mail-Atresse bestätigst (authentifizieren), bevor du de erweiterten E-Mail-Funksjonna benutza koast. Klicke bitte uff de unda stehende, miet „Bestätigungscode zuschicka“ beschriftete Schaltfläche, damit anne automatisch erstellte E-Mail oa de oagegahne Atresse geschickt werd. Diese E-Mail enthält anne Web-Adresse miet a'm Bestätigungscode. Indem du diese Webseyte ei demm Webbrowser effnest, bestätigst du, doß de oagegahne E-Mail-Atresse korrekt und giltig ies.",
'confirmemail_pending'     => "Is wurde dir bereits a Bestätigungscode per E-Mail zugeschickt.
Wenn du dei Nutzerkonto erscht vur kurzem erstallt host, warte bitte noo a poar Minuta uff de E-Mail, bevor du an'n neua Code anforderst.",
'confirmemail_send'        => 'Bestätigungscode zuschicka',
'confirmemail_sent'        => 'Bestätigungs-E-Mail wurde verschickt.',
'confirmemail_oncreate'    => 'A Bestätigungs-Code wurde oa denne E-Mail-Atresse gesandt. Dieser Code werd fier de Oameldung ne benetigt, jedoch werd ar zer Aktivierung dar E-Mail-Funksjonna innerhalb des Wikis gebraucht.',
'confirmemail_invalid'     => 'Ungiltiger Bestätigungscode. Meeglicherweise ies dar Bestätigungszeitraum verstrichen. Versuche bitte, de Bestätigung zu wiederhulla.',
'confirmemail_needlogin'   => 'Du musst diech $1, im denne E-Mail-Atresse zu bestätiga.',
'confirmemail_success'     => 'Denne E-Mail-Atresse wurde erfolgreich bestätigt. Du koast diech jitz [[Special:UserLogin|oamelda]].',
'confirmemail_loggedin'    => 'Denne E-Mail-Atresse wurde erfolgreich bestätigt.',
'confirmemail_error'       => "Is gab an'n Fahler bei dar Bestätigung denner E-Mail-Atresse.",
'confirmemail_subject'     => '[{{SITENAME}}] - Bestätigung dar E-Mail-Atresse',
'confirmemail_body'        => 'Hallo,

jemand miet dar IP-Atresse $1, woahrscheinlich du selbst, hoot doas Nutzerkonto „$2“ ei {{SITENAME}} registriert.

Im de E-Mail-Funksjonn fier {{SITENAME}} (wieder) zu aktiviern und im zu bestätiga,
doß dieses Nutzerkonto wirklich zu denner E-Mail-Atresse und damit zu dir gehiert, effne bitte de folgende Web-Atresse:

$3

Sullte de vorstehende Atresse ei demm E-Mail-Programm ieber mehrere Zeila giehn, musst du se eventuell per Hand ei de Atresszeile dennes Web-Browsers einfiega.

Wenn du doas genannte Nutzerkonto *ne* registriert host, folge diesem Link, im dann Bestätigungsprozess obzubrecha:

$5

Dieser Bestätigungscode ies giltig bis $6, $7 Seeger.',
'confirmemail_invalidated' => 'E-Mail-Atressbestätigung obbrecha',
'invalidateemail'          => 'E-Mail-Atressbestätigung obbrecha',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-Einbindung ies deaktiviert]',
'scarytranscludefailed'   => '[Vorloageneinbindung fier $1 ies gescheitert]',
'scarytranscludetoolong'  => '[URL ies zu lang]',

# Trackbacks
'trackbackremove'   => '([$1 läscha])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback wurde erfolgreich geläscht.',

# Delete conflict
'deletedwhileediting' => 'Ochtiche: Diese Seyte wurde geläscht, nachdem du oagefanga host se zu beoarbeeta!
Eim [{{fullurl:{{#special:Log}}|type=delete&page={{FULLPAGENAMEE}}}} Läsch-Logbuch] fendest du dann Grund fier de Läschung. Wenn du de Seyte speicherst, werd se neu oagelegt.',
'confirmrecreate'     => "Nutzer [[User:$1|$1]] ([[User talk:$1|Dischkur]]) hoot diese Seyte geläscht, nachdem du oagefanga host, se zu beoarbeeta. De Begrindung lautete:
:''$2''
Bitte bestätige, doß du diese Seyte wirklich neu erstalla mechta.",
'recreate'            => 'Erneut oalähn',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Diese Seyte aus damm Server-Cache läscha?',

# Multipage image navigation
'imgmultipageprev' => '← vurherige Seyte',
'imgmultipagenext' => 'nächste Seyte →',
'imgmultigo'       => 'OK',
'imgmultigoto'     => 'Gieh zo Seyte $1',

# Table pager
'ascending_abbrev'         => 'uff',
'descending_abbrev'        => 'oab',
'table_pager_next'         => 'Nächste Seyte',
'table_pager_prev'         => 'Vorherige Seyte',
'table_pager_first'        => 'Erschte Seyte',
'table_pager_last'         => 'Letzte Seyte',
'table_pager_limit'        => 'Zeige $1 Einträge pro Seyte',
'table_pager_limit_submit' => 'Luß',
'table_pager_empty'        => 'Kenne Ergebnisse',

# Auto-summaries
'autosumm-blank'   => 'De Seyte wurde gelaart.',
'autosumm-replace' => "Dar Seytainhalt wurde durch an'n andern Text ersetzt: „$1“",
'autoredircomment' => 'Weiterleitung noach [[$1]] erstallt',
'autosumm-new'     => 'De Seyte wurde neu oagelagt: „$1“',

# Live preview
'livepreview-loading' => 'Loada…',
'livepreview-ready'   => 'Loadn … Fattig!',
'livepreview-failed'  => 'Live-Vurschau ne meeglich! Bitte de normale Vurschau benutza.',
'livepreview-error'   => 'Verbindung ne meeglich: $1 „$2“. Bitte de normale Vurschau benutza.',

# Friendlier slave lag warnings
'lag-warn-high' => 'Uff Grund huher Datenbankauslastung waan de Beoarbeetunga dar letzta {{PLURAL:$1|Sekunde|$1 Sekunden}} ei dieser Liste noo ne oagezeigt.',

# Watchlist editor
'watchlistedit-numitems'       => 'Denne Beobachtungsliste enthält {{PLURAL:$1|1 Eintrag |$1 Einträge}}, Dischkurseyta waan ne gezählt.',
'watchlistedit-noitems'        => 'Denne Beobachtungsliste ies laar.',
'watchlistedit-normal-title'   => 'Beobachtungsliste beoarbeeta',
'watchlistedit-normal-legend'  => 'Einträge vu dar Beobachtungsliste entferna',
'watchlistedit-normal-explain' => 'Dies sein de Einträge denner Beobachtungsliste. Im Einträge zu entferna, markiere de Kästchen neben dann Einträga und klicke oam Ende dar Seyte uff „Einträge entferna“. Du koast denne Beobachtungsliste au eim [[Special:Watchlist/raw|Listenfurmat beoarbeeta]].',
'watchlistedit-normal-submit'  => 'Einträge entferna',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} vu denner Beobachtungsliste entfernt:',
'watchlistedit-raw-title'      => 'Beobachtungsliste eim Listenfurmat beoarbeeta',
'watchlistedit-raw-legend'     => 'Beobachtungsliste eim Listenfurmat beoarbeeta',
'watchlistedit-raw-explain'    => 'Dies sein de Einträge denner Beobachtungsliste eim Listenfurmat. De Einträge kinna zeilaweise geläscht oder hinzugefiegt waan.
Pro Zeile ies a Eintrag erlaubt. Wenn du fattig best, klicke uff „Beobachtungsliste speichern“.
Du koast au de [[Special:Watchlist/edit|Standard-Beoarbeetungsseyte]] benutza.',
'watchlistedit-raw-titles'     => 'Einträge:',
'watchlistedit-raw-submit'     => 'Beobachtungsliste speichern',
'watchlistedit-raw-done'       => 'Denne Beobachtungsliste wurde gespeichert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} hinzugefiegt:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} entfernt:',

# Watchlist editing tools
'watchlisttools-view' => 'Beobachtungsliste: Änneronga',
'watchlisttools-edit' => 'normal beoarbeeta',
'watchlisttools-raw'  => 'Listafurmat beoarbeeta (Import/Export)',

# Core parser functions
'unknown_extension_tag' => 'Unbekennter Extension-Tag „$1“',
'duplicate-defaultsort' => 'Ochtiche: Dar Sortierungsschlissel „$2“ ieberschreibt dann vorher verwendeta Schlissel „$1“.',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Installierte Erweiterungen',
'version-specialpages'             => 'Spezialseyta',
'version-parserhooks'              => 'Parser-Hooks',
'version-variables'                => 'Variablen',
'version-other'                    => 'Oanderes',
'version-mediahandlers'            => 'Medien-Handler',
'version-hooks'                    => "Schnittstalla ''(Hooks)''",
'version-extension-functions'      => 'Funksjonnsuffruffe',
'version-parser-extensiontags'     => "Parser-Erweiterunga ''(tags)''",
'version-parser-function-hooks'    => 'Parser-Funksjonna',
'version-skin-extension-functions' => 'Skin-Erweiterungs-Funksjonna',
'version-hook-name'                => 'Schnittstallanoame',
'version-hook-subscribedby'        => 'Uffruff vu',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Lizenz',
'version-software'                 => 'Installierte Software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Dateipfad',
'filepath-page'    => 'Datei:',
'filepath-submit'  => 'Pfad sucha',
'filepath-summary' => 'Miet dieser Spezialseyte lässt siech dar komplette Pfad dar aktuella Version einer Datei ohne Umweg obfroaga. De oagefroagte Datei werd direkt dargestallt bzw. miet der verkniepfta Oawendung gestartet.

De Eengabe muuß ohne dann Zusatz „{{ns:file}}:“ erfolga.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Datei-Duplikat-Suche',
'fileduplicatesearch-summary'  => 'Siche noach Datei-Duplikaten uff Basis ihres Hash-Wertes.

De Eengabe muuß ohne dann Zusatz „{{ns:file}}:“ erfolga.',
'fileduplicatesearch-legend'   => 'Suche noach Duplikata',
'fileduplicatesearch-filename' => 'Dateinoame:',
'fileduplicatesearch-submit'   => 'Sucha',
'fileduplicatesearch-info'     => '$1 × $2 Pixel<br />Dateigreeße: $3<br />MIME-Typ: $4',
'fileduplicatesearch-result-1' => 'De Datei „$1“ hoot keene identischa Duplikate.',
'fileduplicatesearch-result-n' => 'De Datei „$1“ hoot {{PLURAL:$2|1 identisches Duplikat|$2 identische Duplikate}}.',

# Special:SpecialPages
'specialpages'                   => 'Spezialseyta',
'specialpages-note'              => '----
* Spezialseyta fier Jedermoan
* <strong class="mw-specialpagerestricted">Spezialseyta fier Nutzer miet erweiterta Rechta</strong>',
'specialpages-group-maintenance' => 'Wartungslisten',
'specialpages-group-other'       => 'Andere Spezialseyta',
'specialpages-group-login'       => 'Oamelda',
'specialpages-group-changes'     => 'Letzte Änderunga und Logbicher',
'specialpages-group-media'       => 'Media',
'specialpages-group-users'       => 'Benutzer on Rechte',
'specialpages-group-highuse'     => 'Häufig benutzte Seyta',
'specialpages-group-pages'       => 'Lista vun Seyta',
'specialpages-group-pagetools'   => 'Seytawerkzeuge',
'specialpages-group-wiki'        => 'Systemdaten und Werkzeuge',
'specialpages-group-redirects'   => 'Weiterleitende Spezialseyta',
'specialpages-group-spam'        => 'Spam-Werkzeuge',

# Special:BlankPage
'blankpage'              => 'Laare Seyte',
'intentionallyblankpage' => 'Diese Seyte ies obsichtlich ohne Inhalt. Se werd fier Benchmarks verwendet.',

# Special:Tags
'tags'              => 'Giltige Änderungsmarkierungn',
'tag-filter-submit' => 'Filter',
'tags-title'        => 'Markierunga',
'tags-intro'        => 'Diese Seyte zeigt olle Markierunga, de fier Beoarbeetunga verwendet waan, suwie deren Bedeutung.',
'tags-tag'          => 'Markierungsnoame',
'tags-edit'         => 'beoarbeeta',
'tags-hitcount'     => '$1 {{PLURAL:$1|Änderung|Änderunga}}',

# Database error messages
'dberr-header' => 'Dieses Wiki hoot a Problem',

# HTML forms
'htmlform-submit'              => 'Ieberträän',
'htmlform-reset'               => 'Änderunga rickgängig macha',
'htmlform-selectorother-other' => 'Ondere',

# Add categories per AJAX
'ajax-add-category'            => 'Kategorie hinzufiega',
'ajax-add-category-submit'     => 'Hinzufügen',
'ajax-confirm-title'           => 'Aksjonn bestätiga',
'ajax-confirm-save'            => 'Speichern',
'ajax-add-category-summary'    => 'Kategorie „$1“ hinzufiega',
'ajax-remove-category-summary' => 'Kategorie „$1“ entferna',
'ajax-confirm-actionsummary'   => 'Auszufiehrende Aksjonn:',
'ajax-error-title'             => 'Fahler',
'ajax-error-dismiss'           => 'OK',
'ajax-remove-category-error'   => 'Is woar ne meeglich, de Kategorie zu entferna.
Dies passiert normalerweise, wenn de Kategorie ieber anne Vurloage eingebunda ies.',

);
