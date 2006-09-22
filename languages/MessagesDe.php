<?php

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

$quickbarSettings = array(
	'Keine', 'Links, fest', 'Rechts, fest', 'Links, schwebend'
);

$skinNames = array(
	'standard'      => 'Klassik',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Kölnisch Blau',
	'smarty'        => 'Paddington',
	'montparnasse'  => 'Montparnasse',
	'davinci'       => 'DaVinci',
	'mono'          => 'Mono',
	'monobook'      => 'MonoBook',
	'myskin'        => 'MySkin',
	'chick'         => 'Küken'
);


$bookstoreList = array(
	'abebooks.de' => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'amazon.de' => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'buch.de' => 'http://www.buch.de/de.buch.shop/shop/1/home/schnellsuche/buch/?fqbi=$1',
	'buchhandel.de' => 'http://www.buchhandel.de/vlb/vlb.cgi?type=voll&isbn=$1',
	'Karlsruher Virtueller Katalog (KVK)' => 'http://www.ubka.uni-karlsruhe.de/kvk.html?SB=$1',
	'Lehmanns Fachbuchhandlung' => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1'
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([äöüßa-z]+)(.*)$/sDu';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j. Y',
	'mdy both' => 'H:i, M j. Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'H:i, j. M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
);

$messages = array(

# stylesheets
'Common.css'		=> '/** CSS an dieser Stelle wirkt sich auf alle Skins aus */',
'Monobook.css'		=> '/** Kleinschreibung nicht erzwingen */
.portlet h5,
.portlet h6,
#p-personal ul,
#p-cactions li a {
	text-transform: none;
}',

# User preference toggles
"tog-underline"               => "Verweise unterstreichen:",
'tog-highlightbroken'		=> 'Verweise auf leere Seiten hervorheben',
"tog-justify"                 => "Text als Blocksatz",
'tog-hideminor'			=> 'Kleine Änderungen ausblenden',
'tog-extendwatchlist'		=> 'Erweiterte Beobachtungsliste',
'tog-usenewrc'			=> 'Erweiterte Darstellung (JavaScript)',
'tog-numberheadings'		=> 'Überschriften automatisch nummerieren',
'tog-showtoolbar'		=> 'Bearbeiten-Werkzeugleiste anzeigen',
"tog-editondblclick"          => "Seiten mit Doppelklick bearbeiten (JavaScript)",
"tog-editsection"             => "Links zum Bearbeiten einzelner Absätze anzeigen",
"tog-editsectiononrightclick" => "Einzelne Absätze per Rechtsklick bearbeiten (JavaScript)",
"tog-showtoc"                 => "Anzeigen eines Inhaltsverzeichnisses bei Seiten mit mehr als 3 Überschriften",
'tog-rememberpassword'		=> 'Dauerhaftes Anmelden',
"tog-editwidth"               => "Text-Eingabefeld mit voller Breite",
'tog-watchcreations'		=> 'Selbst erstellte Seiten automatisch zu meiner Beobachtungsliste hinzufügen',
"tog-watchdefault"            => "Neue und geänderte Seiten beobachten",
'tog-minordefault'		=> 'Alle eigenen Änderungen als geringfügig markieren',
'tog-previewontop'		=> 'Vorschau oberhalb des Bearbeitungsfensters anzeigen',
'tog-previewonfirst'		=> 'Beim ersten Bearbeiten immer die Vorschau anzeigen',
"tog-nocache"                 => "Seitencache deaktivieren",
'tog-enotifwatchlistpages'	=> 'Bei Änderungen an beobachtetene Seiten E-Mails senden.',
'tog-enotifusertalkpages'	=> 'Bei Änderungen an meiner Benutzer-Diskussionsseite E-Mails senden.',
'tog-enotifminoredits'		=> 'Auch bei kleinen Änderungen an beobachteten Seiten E-Mails senden.',
'tog-enotifrevealaddr' 		=> 'Ihre E-Mail-Adresse wird in Benachrichtigungsmails gezeigt',
'tog-shownumberswatching'	=> 'Anzahl der beobachtenden Benutzer anzeigen',
'tog-fancysig'			=> 'Signatur ohne Verlinkung zur Benutzerseite',
'tog-externaleditor'		=> 'Externen Editor als Standard benutzen',
'tog-externaldiff'		=> 'Externes Diff-Programm als Standard benutzen',
'tog-showjumplinks'		=> '„Wechseln-zu“-Links ermöglichen',
'tog-uselivepreview'		=> 'Live-Vorschau nutzen (JavaScript) (experimentell)',
'tog-autopatrol'		=> 'Alle eigenen Bearbeitungen als „kontrolliert“ markieren',
'tog-forceeditsummary'		=> 'Warne mich, wenn ich beim Ändern eine Zusammenfassung vergesse',
'tog-watchlisthideown'		=> 'Eigene Änderungen auf der Beobachtungsliste ausblenden',
'tog-watchlisthidebots'		=> 'Bot-Änderungen auf der Beobachtungsliste ausblenden',

'underline-always' => 'Immer',
'underline-never' => 'Niemals',
'underline-default'		=> 'Browsereinstellungen verwenden',

'skinpreview' => '(Vorschau)',


# Dates
'sunday'	=> 'Sonntag',
'monday'	=> 'Montag',
'tuesday'	=> 'Dienstag',
'wednesday'	=> 'Mittwoch',
'thursday'	=> 'Donnerstag',
'friday'	=> 'Freitag',
'saturday'	=> 'Samstag',
'sun'		=> 'So',
'mon'		=> 'Mo',
'tue'		=> 'Di',
'wed'		=> 'Mi',
'thu'		=> 'Do',
'fri'		=> 'Fr',
'sat'		=> 'Sa',
'january'	=> 'Januar',
'february'	=> 'Februar',
'march'		=> 'März',
'april'		=> 'April',
'may_long'	=> 'Mai',
'june'		=> 'Juni',
'july'		=> 'Juli',
'august'	=> 'August',
'september'	=> 'September',
'october'	=> 'Oktober',
'november'	=> 'November',
'december'	=> 'Dezember',
'january-gen'	=> 'Januars',
'february-gen'	=> 'Februars',
'march-gen'	=> 'Märzes',
'april-gen'	=> 'Aprils',
'may-gen'	=> 'Mais',
'june-gen'	=> 'Junis',
'july-gen'	=> 'Julis',
'august-gen'	=> 'Augusts',
'september-gen'	=> 'Septembers',
'october-gen'	=> 'Oktobers',
'november-gen'	=> 'Novembers',
'december-gen'	=> 'Dezembers',
'jan'		=> 'Jan',
'feb'		=> 'Feb',
'mar'		=> 'Mär',
'apr'		=> 'Apr',
'may'		=> 'Mai',
'jun'		=> 'Jun',
'jul'		=> 'Jul',
'aug'		=> 'Aug',
'sep'		=> 'Sep',
'oct'		=> 'Okt',
'nov'		=> 'Nov',
'dec'		=> 'Dez',


# Bits of text used by many pages:
#
'categories'		=> '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'	=> 'Seiten in der Kategorie „$1“',
"subcategories" => "Unterkategorien",
"mainpage"		=> "Hauptseite",
'mainpagetext'		=> 'MediaWiki wurde erfolgreich installiert.',
'mainpagedocfooter'	=> 'Hilfe zur Benutzung und Konfiguration der Wiki Software finden Sie im [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].',
'portal'		=> "{{SITENAME}}-Portal",
'portal-url'		=> '{{ns:project}}:Portal',
"about"			=> "Über",
"aboutsite"      => "Über {{SITENAME}}",
"aboutpage"		=> "{{ns:project}}:Über_{{SITENAME}}",
"article" => "Seite",
"help"			=> "Hilfe",
'helppage'		=> '{{ns:project}}:Hilfe',
"bugreports"	=> "Kontakt",
'bugreportspage'	=> '{{ns:project}}:Kontakt',
"sitesupport"   => "Spenden",
'sitesupport-url'	=> '{{ns:project}}:Spenden',
"faq"			=> "FAQ",
'faqpage'		=> '{{ns:project}}:FAQ',
"edithelp"		=> "Bearbeitungshilfe",
'newwindow'		=> '(öffnet ein neues Fenster)',
'edithelppage'		=> '{{ns:project}}:Bearbeitungshilfe',
'cancel'		=> 'Abbrechen',
"qbfind"		=> "Finden",
"qbbrowse"		=> "Blättern",
"qbedit"		=> "Ändern",
"qbpageoptions" => "Seitenoptionen",
"qbpageinfo"	=> "Seitendaten",
"qbmyoptions"	=> "Einstellungen",
"qbspecialpages"	=> "Spezialseiten",
"moredotdotdot"	=> "Mehr...",
'mypage'		=> 'Eigene Seite',
'mytalk'		=> 'Eigene Diskussion',
"anontalk"		=> "Diskussionsseite dieser IP",
"navigation" => "Navigation",
"currentevents" => "Aktuelle Ereignisse",
'currentevents-url'	=> 'Aktuelle Ereignisse',
'disclaimers'		=> 'Impressum',
'disclaimerpage'	=> '{{ns:project}}:Impressum',
'privacy' => 'Datenschutz',
'privacypage'		=> '{{ns:project}}:Datenschutz',
"errorpagetitle" => "Fehler",
"returnto"		=> "Zurück zu $1.",
'tagline'		=> 'Aus {{SITENAME}}',
"help"			=> "Hilfe",
"search"		=> "Suche",
"searchbutton"	=> "Suche",
"history"		=> "Versionen",
'info_short'		=> 'Information',
'history_short'		=> 'Versionen/Autoren',
"printableversion" => "Druckversion",
'print' => 'Drucken',
"editthispage"	=> "Seite bearbeiten",
"delete" => "löschen",
"deletethispage" => "Diese Seite löschen",
'undelete_short'	=> '{{PLURAL:$1|eine Änderung|$1 Änderungen}} wiederherstellen',
"protect" => "schützen",
'protectthispage'	=> 'Seite schützen',
'unprotect'		=> 'freigeben',
"unprotectthispage" => "Schutz aufheben",
"newpage" => "Neue Seite",
"talkpage"		=> "Diskussion",
"specialpage" => "Spezialseite",
"personaltools" => "Persönliche Werkzeuge",
"postcomment" => "Kommentar hinzufügen",
"articlepage"	=> "Seite",
"toolbox" => "Werkzeuge",
"projectpage" => "Meta-Text",
"userpage" => "Benutzerseite",
"imagepage" => "Bildseite",
'mediawikipage'		=> 'Inhaltsseite anzeigen',
'templatepage'		=> 'Vorlagenseite anzeigen',
'viewhelppage'		=> 'Hilfeseite anzeigen',
'categorypage'		=> 'Kategorieseite anzeigen',
"viewtalkpage" => "Diskussion",
"otherlanguages" => "Andere Sprachen",
"redirectedfrom" => "(Weitergeleitet von $1)",
'autoredircomment'	=> 'Weiterleitung nach [[$1]]',
'redirectpagesub' => 'Weiterleitung',
'lastmodifiedat'		=> 'Diese Seite wurde zuletzt geändert am $1 um $2 Uhr.',
"viewcount"		=> "Diese Seite wurde bisher $1 mal abgerufen.",
"copyright"	=> "Inhalt ist verfügbar unter der $1.",
"protectedpage" => "Geschützte Seite",
'jumpto'		=> 'Wechseln zu:',
'jumptonavigation'	=> 'Navigation',
'jumptosearch'		=> 'Suche',
'badaccess'		=> 'Keine ausreichenden Rechte.',
'badaccess-group0'	=> 'Sie haben nicht die erforderliche Berechtigung für diese Aktion.',
'badaccess-group1'	=> 'Diese Aktion ist beschränkt auf Benutzer, die der Gruppe $1 angehören.',
'badaccess-group2'	=> 'Diese Aktion ist beschränkt auf Benutzer, die einer der Gruppen $1 angehören.',
'badaccess-groups'	=> 'Diese Aktion ist beschränkt auf Benutzer, die einer der Gruppen $1 angehören.',
'versionrequired'	=> 'Version $1 von MediaWiki ist erforderlich',
'versionrequiredtext'	=> 'Version $1 von MediaWiki ist erforderlich um diese Seite zu nutzen. Siehe [[{{ns:special}}:Version]]',
'nbytes'		=> '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'		=> '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nrevisions'		=> '{{PLURAL:$1|eine Bearbeitung|$1 Bearbeitungen}}',
"go"			=> "Seite",
"ok"			=> "Suche",
'pagetitle'		=> '$1 - {{SITENAME}}',
'retrievedfrom'		=> 'Von „$1“',
'youhavenewmessages' => 'Sie haben $1 ($2).',
"newmessageslink" => "neue Nachrichten",
'newmessagesdifflink'	=> 'Unterschied zur vorletzten Version',
"editsection" => "bearbeiten",
"editold" => "bearbeiten",
'editsectionhint' => 'Abschnitt bearbeiten: $1',
"toc" => "Inhaltsverzeichnis",
"showtoc" => "Anzeigen",
"hidetoc" => "Verbergen",
"thisisdeleted" => "Ansehen oder wiederherstellen von $1?",
'viewdeleted' => '$1 anzeigen?',
'restorelink'		=> '{{PLURAL:$1|einer gelöschten Version|$1 gelöschten Versionen}}',
"feedlinks" => "Feed:",
'feed-invalid'		=> 'Ungültiger Abonnement-Typ.',
'permalink'     => 'Permanentlink',
"listingcontinuesabbrev" => "(Forts.)",

# Kurzworte für jeden Namespace, u.a. von MonoBook verwendet
'nstab-main' => 'Seite',
'nstab-user' => 'Benutzerseite',
'nstab-media' => 'Media',
'nstab-special' => 'Spezial',
'nstab-project' => 'Projektseite',
'nstab-image'		=> 'Datei',
'nstab-mediawiki' => 'Nachricht',
'nstab-template' => 'Vorlage',
'nstab-help' => 'Hilfe',
'nstab-category' => 'Kategorie',

# Editier-Werkzeugleiste
"bold_sample"=>"Fetter Text",
"bold_tip"=>"Fetter Text",
"italic_sample"=>"Kursiver Text",
"italic_tip"=>"Kursiver Text",
"link_sample"=>"Link-Text",
"link_tip"=>"Interner Link",
"extlink_sample"=>"http://www.beispiel.de Link-Text",
"extlink_tip"=>"Externer Link (http:// beachten)",
"headline_sample"=>"Ebene 2 Überschrift",
"headline_tip"=>"Ebene 2 Überschrift",
"math_sample"=>"Formel hier einfügen",
"math_tip"=>"Mathematische Formel (LaTeX)",
"nowiki_sample"=>"Unformatierten Text hier einfügen",
"nowiki_tip"=>"Unformatierter Text",
"image_sample"=>"Beispiel.jpg",
'image_tip'		=> 'Bildverweis',
'media_sample'		=> 'Beispiel.ogg',
"media_tip"=>"Mediendatei-Verweis",
"sig_tip"=>"Ihre Signatur mit Zeitstempel",
"hr_tip"=>"Horizontale Linie (sparsam verwenden)",

# Main script and global functions
#
"nosuchaction"	=> "Diese Aktion gibt es nicht",
'nosuchactiontext'	=> 'Die in der URL angegebene Aktion wird von der MediaWiki-Software nicht unterstützt.',
"nosuchspecialpage" => "Diese Spezialseite gibt es nicht",
'nospecialpagetext'	=> 'Sie haben eine nicht vorhandene Spezialseite aufgerufen. Eine Liste aller verfügabren Spezialseiten finden Sie unter [[{{ns:special}}:Specialpages]].',

# General errors
#
"error" => "Fehler",
"databaseerror" => "Fehler in der Datenbank",
'dberrortext'		=> 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: <blockquote><tt>$1</tt></blockquote> aus der Funktion „<tt>$2</tt>“.
MySQL meldete den Fehler „<tt>$3: $4</tt>“.',
'dberrortextcl'		=> 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: „$1“ aus der Funktion „<tt>$2</tt>“.
MySQL meldete den Fehler: „<tt>$3: $4</tt>“.',
"noconnect"		=> "Konnte keine Verbindung zur Datenbank auf $1 herstellen",
"nodb"			=> "Konnte Datenbank $1 nicht auswählen",
'cachederror'		=> 'Das Folgende ist eine Kopie aus dem Cache und möglicherweise nicht aktuell.',
'laggedslavemode'	=> 'Warnung: Die angezeigte Seite kann unter Umständen die jüngsten Änderungen noch nicht beinhalten.',
"readonly"		=> "Datenbank ist gesperrt",
'enterlockreason'	=> 'Bitte geben Sie einen Grund ein, warum die Datenbank gesperrt werden soll und eine Abschätzung über die Dauer der Sperrung',
'readonlytext'		=> 'Die Datenbank ist vorübergehend für Neueinträge und Änderungen gesperrt. Bitte versuchen Sie es später noch einmal.

Grund der Sperrung: $1',
'missingarticle'	=> 'Der Text für „$1“ wurde nicht in der Datenbank gefunden.

Die Seite ist möglicherweise gelöscht oder verschoben worden.

Falls dies nicht der Fall ist, haben Sie eventuell einen Fehler in der Software gefunden. Bitte melden Sie dies einem [[{{ns:project}}:Administrator]] unter Nennung der URL.',
'readonly_lag'		=> 'Die Datenbank wurde kurzzeitig automatisch gesperrt, damit sich die Datenbanken abgleichen können.',
"internalerror" => "Interner Fehler",
'filecopyerror'		=> 'Konnte Datei „$1“ nicht nach „$2“ kopieren.',
'filerenameerror'	=> 'Konnte Datei „$1“ nicht nach „$2“ umbenennen.',
'filedeleteerror'	=> 'Konnte Datei „$1“ nicht löschen.',
'filenotfound'		=> 'Konnte Datei „$1“ nicht finden.',
'unexpected'		=> 'Unerwarteter Wert: „$1“=„$2“.',
'formerror'		=> 'Fehler: Die Eingaben konnten nicht verarbeitet werden.',
'badarticleerror'	=> 'Diese Aktion kann auf diese Seite nicht angewendet werden.',
'cannotdelete'		=> 'Die gewählte Seite kann nicht gelöscht werden. Möglicherweise wurde sie bereits gelöscht.',
"badtitle"		=> "Ungültiger Titel",
'badtitletext'		=> 'Der Titel der angeforderten Seite ist ungültig, leer, oder ein ungültiger Sprachlink von einem anderen Wiki.',
'perfdisabled'	=> '\'\'\'Entschuldigung!\'\'\' Diese Funktion wurde wegen Überlastung des Servers vorübergehend deaktiviert.',
"perfdisabledsub" => "Hier ist eine gespeicherte Kopie von $1:",
"perfcached" => "Die folgenden Daten stammen aus dem Cache und sind möglicherweise nicht aktuell:",
'perfcachedts'		=> 'Diese Daten stammen aus dem Cache, letztes Update: $1',
'wrong_wfQuery_params'	=> 'Falsche Parameter für wfQuery()<br />
Funktion: $1<br />
Abfrage: $2',
"viewsource" => "Quelltext betrachten",
'viewsourcefor' => 'für $1',
'protectedtext'		=> 'Diese Seite ist für das Bearbeiten gesperrt. Sie können jedoch den Quelltext dieser Seite betrachten und kopieren:',
'protectedinterface'	=> 'Diese Seite enthält Text für die Benutzeroberfläche der MediaWiki-Software und ist gesperrt, um Missbrauch zu verhindern.',
'editinginterface'	=> '\'\'\'Warnung:\'\'\' Diese Seite enthält von der MediaWiki-Software benutzten Text. Änderungen wirken sich auf die Benutzeroberfläche aus.',
'sqlhidden'		=> '(SQL-Abfrage versteckt)',


# Login and logout pages
#
"logouttitle"	=> "Benutzer-Abmeldung",
"logouttext"	=> "Sie sind nun abgemeldet.
Sie können {{SITENAME}} jetzt anonym weiterbenutzen, oder sich unter dem selben oder einem anderen Benutzernamen wieder anmelden.",
'welcomecreation'	=> '== Willkommen, $1! ==

Ihr Benutzerkonto wurde eingerichtet. Vergessen Sie nicht, Ihre Einstellungen anzupassen.',

"loginpagetitle" => "Benutzer-Anmeldung",
"yourname"		=> "Benutzername",
"yourpassword"	=> "Passwort",
"yourpasswordagain" => "Passwort wiederholen",
'remembermypassword'	=> 'dauerhaft anmelden',
'yourdomainname' => 'Ihre Domain',
'externaldberror'	=> 'Entweder es liegt ein Fehler bei der externen Authentifizierung vor, oder Sie dürfen Ihr externes Benutzerkonto nicht aktualisieren.',
'loginproblem' => '\'\'\'Es gab ein Problem mit Ihrer Anmeldung.\'\'\'<br />Bitte versuchen Sie es nochmal!',
'alreadyloggedin' => '\'\'\'Benutzer $1, Sie sind bereits angemeldet!\'\'\'<br />',

"login"			=> "Anmelden",
"loginprompt"           => "Um sich bei {{SITENAME}} anmelden zu können, müssen Cookies aktiviert sein.",
"userlogin"		=> "Anmelden",
"logout"		=> "Abmelden",
"userlogout"	=> "Abmelden",
"notloggedin" => "Nicht angemeldet",
'nologin'		=> 'Sie haben kein Benutzerkonto? $1.',
'nologinlink'		=> 'Neues Benutzerkonto anlegen',
'createaccount'		=> 'Neues Benutzerkonto anlegen',
'accountcreated'	=> 'Benutzerkonto erstellt',
'accountcreatedtext'	=> 'Das Benutzerkonto für $1 wurde erstellt.',
'gotaccountlink'	=> 'Anmelden',
'gotaccount'		=> 'Sie haben bereits ein Benutzerkonto? $1.',
'createaccountmail'	=> 'über E-Mail',
"badretype"		=> "Die beiden Passwörter stimmen nicht überein.",
"userexists"	=> "Dieser Benutzername ist schon vergeben. Bitte wählen Sie einen anderen.",
'youremail'		=> 'E-Mail-Adresse**:',
'yournick'		=> 'Signatur:',
'yourrealname'		=> 'Echter Name*:',
'yourlanguage'		=> 'Sprache der Benutzeroberfläche:',
'username'		=> 'Benutzername:',
'uid'			=> 'Benutzer ID:',
'yourvariant'		=> 'Variante',
'badsig'		=> 'Die Syntax der Signatur ist ungültig; bitte HTML überprüfen.',
'email'			=> 'E-Mail',
'prefs-help-email' 	=> '** <strong>E-Mail-Adresse</strong> (optional): Erlaubt anderen Benutzern Sie über Ihre Benutzerseite zu kontaktieren,
ohne dass Sie Ihre E-Mail-Adresse veröffentlichen müssen.
Für den Fall, dass Sie Ihr Passwort vergessen haben, kann Ihnen ein temporäres Einmal-Passwort zugesendet werden.',
'prefs-help-email-enotif' => 'An diese Adresse werden auch die Benachrichtigungsmails geschickt, sofern Sie das eingeschaltet haben.',
'prefs-help-realname'	=> '* <strong>Echter Name</strong> (optional): Für anerkennende Nennungen Ihres Namens im Zusammenhang mit Ihren Beiträgen.',
"loginerror"	=> "Fehler bei der Anmeldung",
"noname"		=> "Sie müssen einen Benutzernamen angeben.",
"loginsuccesstitle" => "Anmeldung erfolgreich",
'loginsuccess'		=> 'Sie sind jetzt als „$1“ bei {{SITENAME}} angemeldet.',
'nosuchuser'		=> 'Der Benutzername „$1“ existiert nicht. Überprüfen Sie die Schreibweise oder legen Sie ein neues Benutzerkonto an.',
'nosuchusershort'	=> 'Der Benutzername „$1“ existiert nicht. Bitte überprüfen Sie die Schreibweise.',
'nouserspecified'	=> 'Bitte geben Sie einen Benutzernamen an.',
"wrongpassword"	=> "Das Passwort ist falsch (oder fehlt). Bitte versuchen Sie es erneut.",
'mailmypassword'	=> 'Neues Passwort zusenden',
'passwordremindertitle' => '[{{SITENAME}}] Neues Passwort',
'passwordremindertext'	=> 'Jemand mit der IP-Adresse $1, wahrscheinlich Sie selbst, hat ein neues Passwort für die Anmeldung bei {{SITENAME}} ($4) angefordert.

Das automatisch generierte Passwort für Benutzer $2 lautet nun: $3

Sie sollten sich jetzt anmelden und das Passwort ändern: {{fullurl:{{ns:special}}}}:Userlogin

Bitte ignoriesen Sie diese E-Mail, falls Sie diese nicht selbst angefordert haben. Das alte Passwort bleibt weiterhin gültig.',
'noemail'		=> 'Benutzer „$1“ hat keine E-Mail-Adresse angegeben.',
'passwordsent'		=> 'Ein temporäres Passwort wurde an die E-Mail-Adresse von Benutzer „$1“ gesendet.
Bitte melden Sie sich damit an, sobald Sie es erhalten.
Das alte Passwort bleibt weiterhin gültig.',
'eauthentsent'		=>  'Eine Bestätigungsmail wurde an die angegebene E-Mail-Adresse verschickt.

Bevor eine E-Mail von anderen Benutzern über die {{SITENAME}}-Mailfunktion empfangen werden kann, muss die Adresse und ihre tatsächliche Zugehörigkeit zu diesem Benutzerkonto erst bestätigt werden. Bitte befolgen Sie die Hinweise in der Bestätigungsmail.',
'mailerror'		=> 'Fehler beim Senden der E-Mail: $1',
'acct_creation_throttle_hit' => 'Sie haben schon $1 Benutzerkonten und können jetzt keine weiteren mehr anlegen.',
'emailconfirmlink' 	=> 'E-Mail-Adresse bestätigen (authentifizieren).',
'emailauthenticated' 	=> 'Ihre E-Mail-Adresse wurde am $1 authentifiziert.',
'emailnotauthenticated'	=> 'Ihre E-Mail-Adresse ist <strong>noch nicht authentifiziert</strong>. Es wird Ihnen keine E-Mail für eine der folgenden Funktionen zugesendet.',
'invalidemailaddress'	=> 'Die E-Mail-Adresse wurde nicht akzeptiert, da sie ein ungültiges Format aufzuweisen scheint. Bitte geben Sie eine Adresse in einem gültigen Format ein, oder leeren Sie das Feld.',
'noemailprefs'		=> 'Geben Sie eine E-Mail-Adresse an, damit die nachfolgenden Funktionen zur Verfügung stehen.',
'wrongpasswordempty'	=> 'Das eingegebene Passwort war leer. Bitte versuchen Sie es erneut.',

# Edit pages
#
"summary"	=> "Zusammenfassung",
"subject"       => "Betreff",
'minoredit'		=> 'Nur Kleinigkeiten wurden verändert',
'watchthis'		=> 'Diese Seite beobachten',
'savearticle'		=> 'Seite speichern',
"preview"	=> "Vorschau",
"showpreview"	=> "Vorschau zeigen",
'showlivepreview'	=> 'Live-Vorschau',
'showdiff'		=> 'Änderungen zeigen',
'anoneditwarning'	=> "Sie bearbeiten diese Seite ohne angemeldet zu sein. Statt eines Benutzernamens wird die IP-Adresse in der Versionsgeschichte aufgezeichnet.",
'missingsummary'	=> '\'\'\'Hinweis:\'\'\' Sie haben keine Zusammenfassung angegeben. Wenn Sie erneut „Speichern“ klicken, wird Ihre Änderung ohne Zusammenfassung übernommen.',
'missingcommenttext'	=> 'Bitte geben Sie eine Zusammenfassung ein.',
"blockedtitle"	=> "Benutzer ist blockiert",

'blockedtext'		=> 'Ihr Benutzername oder Ihre IP-Adresse wurde von $1 blockiert.

Folgender Grund wurde angegeben: $2

Sie können $1 oder die [[{{ns:project}}:Administratoren]] kontaktieren, um über die Blockierung zu diskutieren.

Bitte geben Sie Ihre IP-Adresse ($3) in allen Ihren Anfragen mit an.',
'blockedoriginalsource'	=> 'Der Quelltext von \'\'\'$1\'\'\' wird hier angezeigt:',
'blockededitsource'	=> 'Der Quelltext von \'\'\'Ihren Änderungen\'\'\' an \'\'\'$1\'\'\':',
"whitelistedittitle" => "Zum Bearbeiten ist es erforderlich, angemeldet zu sein",
'whitelistedittext'	=> 'Sie müssen sich $1, um Seiten bearbeiten zu können.',
"whitelistreadtitle" => "Zum Lesen ist es erforderlich, angemeldet zu sein",
'whitelistreadtext'	=> 'Sie müssen sich [[{{ns:special}}:Userlogin|hier anmelden]], um Seiten lesen zu können.',
'whitelistacctitle'	=> 'Sie sind nicht berechtigt, einen Benutzer zu erzeugen.',
'whitelistacctext'	=> 'Um in diesem Wiki Benutzer anlegen zu dürfen, müssen Sie sich [[{{ns:special}}:Userlogin|hier anmelden]] und die nötigen Berechtigungen haben.',
'confirmedittitle'	=> 'Zum Bearbeiten ist die E-Mail-Bestätigung erforderlich.',
'confirmedittext'	=> 'Sie müssen Ihre E-Mail-Adresse erst bestätigen, bevor Sie bearbeiten können. Bitte ergänzen und bestätigen Sie Ihre E-Mail-Adresse in den [[{{ns:special}}:Preferences|Einstellungen]].',
'loginreqtitle'		=> 'Anmeldung erforderlich',
'loginreqlink' => 'anmelden',
'loginreqpagetext'	=> 'Sie müssen sich $1, um Seiten lesen zu können.',
"accmailtitle" => "Passwort wurde verschickt.",
"accmailtext" => "Das Passwort von $1 wurde an $2 geschickt.",
"newarticle"	=> "(Neu)",
'newarticletext'	=> 'Hier den Text der neuen Seite eintragen. Bitte nur in ganzen Sätzen schreiben und keine urheberrechtsgeschützten Texte anderer kopieren.',
'anontalkpagetext'	=> '---- \'\'Dies ist die Diskussionsseite eines nicht angemeldeten Benutzers. Wir müssen hier die numerische IP-Adresse zur Identifizierung verwenden. Eine solche Adresse kann nacheinander von mehreren Benutzern verwendet werden. Wenn Sie ein anonymer Benutzer sind und denken, dass irrelevante Kommentare an Sie gerichtet wurden, [[{{ns:special}}:Userlogin|melden Sie sich bitte an]], um zukünftige Verwirrung zu vermeiden. \'\'',
'noarticletext'		=> '(Diese Seite enthält momentan noch keinen Text)',
'usercsspreview'	=> '== Vorschau Ihres Benutzer-CSS ==
\'\'\'Beachten Sie:\'\'\' Nach dem Speichern müssen Sie Ihren Browser anweisen, die neue Version zu laden: \'\'\'Mozilla/Firefox:\'\'\' \'\'Strg-Shift-R\'\', \'\'\'Internet Explorer:\'\'\' \'\'Strg-F5\'\', \'\'\'Opera:\'\'\' \'\'F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-Shift-R\'\', \'\'\'Konqueror:\'\'\' \'\'F5\'\'.',
'userjspreview'		=> '== Vorschau Ihres Benutzer-JavaScript ==
\'\'\'Beachten Sie:\'\'\' Nach dem Speichern müssen Sie Ihren Browser anweisen, die neue Version zu laden: \'\'\'Mozilla/Firefox:\'\'\' \'\'Strg-Shift-R\'\', \'\'\'Internet Explorer:\'\'\' \'\'Strg-F5\'\', \'\'\'Opera:\'\'\' \'\'F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-Shift-R\'\', \'\'\'Konqueror:\'\'\' \'\'F5\'\'.',
'userinvalidcssjstitle'	=> '\'\'\'Warnung:\'\'\' Es existiert kein Skin „$1“. Bitte bedenken Sie, dass benutzerspezifische .css- and .js-Seiten mit einem Kleinbuchstaben anfangen müssen, also z.B. Benutzer:Foo/monobook.css an Stelle von Benutzer:Foo/Monobook.css.',
'clearyourcache'	=> '\'\'\'Beachten Sie:\'\'\' Nach dem Speichern müssen Sie Ihren Browser anweisen, die neue Version zu laden:<br />
\'\'\'Mozilla/Firefox:\'\'\' \'\'Strg-Shift-R\'\', \'\'\'Internet Explorer:\'\'\' \'\'Strg-F5\'\', \'\'\'Opera:\'\'\' \'\'F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-Shift-R\'\', \'\'\'Konqueror:\'\'\' \'\'F5\'\'.',
'usercssjsyoucanpreview' => "<strong>Tipp:</strong> Benutzen Sie den Vorschau-Button, um Ihr neues css/js vor dem Speichern zu testen.",
"updated"		=> "(Geändert)",
"note"			=> "<strong>Hinweis:</strong>",
'previewnote'		=> 'Dies ist nur eine Vorschau, die Seite wurde noch nicht gespeichert!',
'session_fail_preview' => '<strong>Ihre Bearbeitung konnte nicht gespeichert werden, da Ihre Sitzungsdaten verloren gegangen sind.
Bitte versuchen Sie es erneut. Sollte das Problem bestehen bleiben, loggen Sie sich kurz aus und wieder ein.</strong>',
'previewconflict'	=> 'Diese Vorschau gibt den Inhalt des oberen Textfeldes wieder; so wird die Seite aussehen, wenn Sie jetzt speichern.',
'session_fail_preview_html'	=> '<strong>Ihre Bearbeitung konnte nicht gespeichert werden, da Ihre Sitzungsdaten verloren gegangen sind.</strong>

\'\'Da in diesem Wiki reines HTML aktiviert ist, wurde die Vorschau ausgeblendet um JavaScript Attacken vorzubeugen.\'\'

<strong>Bitte versuchen Sie es erneut. Sollte das Problem bestehen bleiben, melden Sie sich kurz ab und wieder an.</strong>',
'importing'		=> 'importiere $1',
"editing"		=> "Bearbeiten von $1",
"editingsection"	=> "Bearbeiten von $1 (Absatz)",
"editingcomment"	=> "Bearbeiten von $1 (Kommentar)",
'editconflict'		=> 'Bearbeitungskonflikt: $1',
'explainconflict'	=> 'Jemand anders hat diese Seite geändert, nachdem Sie angefangen haben diese zu bearbeiten.
Das obere Textfeld enthält den aktuellen Stand.
Das untere Textfeld enthält Ihre Änderungen.
Bitte fügen Sie Ihre Änderungen in das obere Textfeld ein.
<b>Nur</b> der Inhalt des oberen Textfeldes wird gespeichert, wenn Sie auf „Speichern“ klicken!<br />',
"yourtext"		=> "Ihr Text",
"storedversion" => "Gespeicherte Version",
'nonunicodebrowser'	=> '<strong>Achtung:</strong> Ihr Browser kann Unicode-Zeichen nicht richtig verarbeiten. Bitte verwenden Sie einen anderen Browser um Seiten zu bearbeiten.',
"editingold"	=> "<strong>ACHTUNG: Sie bearbeiten eine alte Version dieser Seite.
Wenn Sie speichern, werden alle neueren Versionen überschrieben.</strong>",
"yourdiff"		=> "Unterschiede",
'copyrightwarning'	=> '<strong>Bitte <big>kopieren Sie keine Webseiten</big>, die nicht Ihre eigenen sind, benutzen Sie <big>keine urheberrechtlich geschützten Werke</big> ohne Erlaubnis des Copyright-Inhabers!</strong><br />
Sie geben uns hiermit Ihre Zusage, dass Sie den Text <strong>selbst verfasst</strong> haben, dass der Text Allgemeingut (<strong>public domain</strong>) ist, oder dass der <strong>Copyright-Inhaber</strong> seine <strong>Zustimmung</strong> gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weisen Sie bitte auf der Diskussionsseite darauf hin.
<i>Bitte beachten Sie, dass alle {{SITENAME}}-Beiträge automatisch unter der „$2“ stehen (siehe $1 für Details). Falls Sie nicht möchten, dass Ihre Arbeit hier von anderen verändert und verbreitet wird, dann drücken Sie nicht auf „Speichern“.</i>',
'copyrightwarning2' => 'Bitte beachten Sie, dass alle Beiträge zu {{SITENAME}} von anderen Mitwirkenden bearbeitet, geändert oder gelöscht werden können.
Reichen Sie hier keine Texte ein, falls Sie nicht wollen dass diese ohne Einschränkung geändert werden können.

Sie bestätigen hiermit auch, dass Sie diese Texte selbst geschrieben haben oder diese von einer gemeinfreien Quelle kopiert haben
(siehe $1 für weitere Details). <strong>ÜBERTRAGEN SIE OHNE GENEHMIGUNG KEINE URHEBERRECHTLICH GESCHÜTZEN INHALTE!</strong>',
"longpagewarning" => "<strong>WARNUNG: Diese Seite ist $1 kB groß; einige Browser könnten Probleme haben, Seiten zu bearbeiten, die größer als 32 kB sind.
Überlegen Sie bitte, ob eine Aufteilung der Seite in kleinere Abschnitte möglich ist.</strong>",
'longpageerror'		=> '<strong>FEHLER: Der Text, den Sie zu speichern versuchen, ist $1 kB groß. Das ist größer als das erlaubte Maximum von $2 kB. Speicherung nicht möglich.</strong>',
"readonlywarning" => "<strong>WARNUNG: Die Datenbank wurde während dem Ändern der
Seite für Wartungsarbeiten gesperrt, so dass Sie die Seite im Moment nicht
speichern können. Sichern Sie sich den Text und versuchen Sie die Änderungen
später einzuspielen.</strong>",
'protectedpagewarning'	=> '\'\'\'ACHTUNG: Diese Seite wurde gesperrt, so dass sie nur durch Benutzer mit Admninistratorrechten bearbeitet werden kann.\'\'\'',
'semiprotectedpagewarning'	=> '\'\'\'Halbsperrung:\'\'\' Die Seite wurde so gesperrt, dass nur registrierte Benutzer diese ändern können.',
'templatesused'		=> 'Folgende Vorlagen werden von dieser Seite verwendet:',
'edittools'		=> '<!-- Dieser Text wird unter dem „Bearbeiten“-Formular sowie dem "Hochladen"-Formular angezeigt. -->',
'nocreatetitle'		=> 'Die Erstellung neuer Seiten ist eingeschränkt.',
'nocreatetext'		=> 'Der Server hat das Erstellen neuer Seiten eingeschränkt.

Sie können bestehende Seiten ändern oder sich [[{{ns:special}}:Userlogin|anmelden]].',
'cantcreateaccounttitle'	=> 'Benutzerkonto kann nicht erstellt werden',
'cantcreateaccounttext'	=> 'Die Erstellung eines Benutzerkontos von dieser IP-Adresse (<b>$1</b>) wurde gesperrt.
Dies geschah vermutlich auf Grund von wiederholtem Vandalismus von Ihrer Bildungseinrichtung oder anderen Benutzern Ihres Internet-Service-Provider.',

# History pages
#
"revhistory"	=> "Frühere Versionen",
'viewpagelogs'		=> 'Logbücher für diese Seite anzeigen',
'nohistory'		=> 'Es gibt keine früheren Versionen dieser Seite.',
'revnotfound'		=> 'Diese Version wurde nicht gefunden.',
"revnotfoundtext" => "Die Version dieser Seite, nach der Sie suchen, konnte nicht gefunden werden. Bitte überprüfen Sie die URL dieser Seite.",
"loadhist"		=> "Lade Liste mit früheren Versionen",
"currentrev"	=> "Aktuelle Version",
"revisionasof"	=> "Version vom $1",
'old-revision-navigation'	=> 'Version vom $1; $5<br />($6) $3 | $2 | $4 ($7)',
'nextrevision'		=> 'Nächstjüngere Version →',
'previousrevision'	=> '← Nächstältere Version',
'currentrevisionlink'	=> 'Aktuelle Version',
"cur"			=> "Aktuell",
"next"			=> "Nächste",
"last"			=> "Vorherige",
'deletedrev'		=> '[gelöscht]',
'histfirst'		=> 'Älteste',
'histlast'		=> 'Neueste',
'rev-deleted-comment'	=> '(Kommentar entfernt)',
'rev-deleted-user'	=> '(Benutzername entfernt)',
'rev-deleted-text-permission'	=> '<div class="mw-warning plainlinks"> Diese Version wurde gelöscht und ist nicht mehr öffentlich einsehbar.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:Spezial:Log/delete|page={{PAGENAMEE}}}} Lösch-Logbuch].</div>',
'rev-deleted-text-view'	=> '<div class="mw-warning plainlinks">Diese Version wurde gelöscht und ist nicht mehr öffentlich einsehbar.
Als Administrator können Sie sie weiterhin einsehen.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:Spezial:Log/delete|page={{PAGENAMEE}}}} Lösch-Logbuch].</div>',
"orig"			=> "Original",
'histlegend'		=> 'Zur Anzeige der Änderungen einfach die zu vergleichenden Versionen auswählen und die Schaltfläche „{{int:compareselectedversions}}“ klicken („alt-v“).<br />
* (Aktuell) = Unterschied zur aktuellen Version, (Vorherige) = Unterschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dieser Zeit, Benutzername/IP-Adresse des Bearbeiters, K = Kleine Änderung',

'revdelete-legend'		=> 'Einschränkungen für die Versionen festlegen:',
'revdelete-hide-text'		=> 'Verstecke den Text der Version',
'revdelete-hide-comment'	=> 'Bearbeitungskommentar verstecken',
'revdelete-hide-user'		=> 'Verstecke den Benutzernamen/die IP des Bearbeiters.',
'revdelete-hide-restricted'	=> 'Diese Einschränkungen gelten auch für Administratoren (nicht nur für „normale“ Benutzer).',
'revdelete-log'			=> 'Kommentar/Begründung (erscheint im Logbuch):',
'revdelete-submit'		=> 'Auf ausgewählte Version anwenden',
'revdelete-logentry'		=> 'Versionszugang geändert für [[$1]]',
'rev-delundel'			=> 'zeige/verstecke',

'history-feed-title'		=> 'Versionsgeschichte',
'history-feed-description'	=> 'Versionsgeschichte für diese Seite im Wiki',
'history-feed-item-nocomment'	=> '$1 um $2', # user at time
'history-feed-empty'		=> 'Die angeforderte Seite exisitiert nicht.
Vielleicht wurde sie aus dem Wiki gelöscht oder verschoben.
[[{{ns:special}}:Search|Durchsuchen]] Sie das Wiki für passende neue Seiten.',
'revisiondelete'		=> 'Versionen löschen/wiederherstellen',
'revdelete-nooldid-title'	=> 'Keine Version angegeben',
'revdelete-nooldid-text'	=> 'Sie haben keine Version angegeben, auf die diese Aktion ausgeführt werden soll.',
'revdelete-selected'		=> 'Ausgewählte Version von [[:$1]]:',
'revdelete-text'		=> 'Der Inhalt oder andere Bestandteile gelöschter Versionen sind nicht mehr öffentlich einsehbar, erscheinen jedoch weiterhin als Einträge in der Versionsgeschichte. 

Administroren können den entfernten Inhalt oder andere entfernte Bestandteile weiterhin einsehen und wiederherstellen, es sei denn, es wurde festgelegt, dass die Zugangsbeschränkungen auch für Administratoren gelten.',

# Diffs
#
"difference"	=> "(Unterschied zwischen Versionen)",
'loadingrev'		=> 'Lade Versionen zur Unterscheidung',
"lineno"		=> "Zeile $1:",
"editcurrent"	=> "Die aktuelle Version dieser Seite bearbeiten",
'selectnewerversionfordiff' => 'Eine neuere Version zum Vergleich auswählen',
'selectolderversionfordiff' => 'Eine ältere Version zum Vergleich auswählen',
'compareselectedversions' => 'Gewählte Versionen vergleichen',

# Search results
#
"searchresults" => "Suchergebnisse",
'searchresulttext'	=> 'Für mehr Informationen zur Suche siehe „[[{{ns:project}}:Suche|{{SITENAME}} durchsuchen]]“.',
'searchsubtitle'	=> 'Für Ihre Suchanfrage „[[:$1]]“.',
'searchsubtitleinvalid'	=> 'Für Ihre Suchanfrage „$1“.',
"badquery"		=> "Falsche Suchanfrage",
'badquerytext'		=> 'Wir konnten Ihre Suchanfrage nicht verarbeiten.
Vermutlich haben Sie versucht, ein Wort zu suchen, das kürzer als vier Buchstaben ist.
Dies funktioniert im Moment noch nicht.
Möglicherweise haben Sie auch die Anfrage falsch formuliert, z.B.
„Lohn und und Steuern“.
Bitte versuchen Sie eine anders formulierte Suchanfrage.',
'matchtotals'		=> 'Die Suchanfrage „$1“ stimmt mit $2 Seitentiteln und dem Inhalt von $3 Seiten überein.',
'noexactmatch'		=> '\'\'\'Es existiert keine Seite mit dem Namen „$1“.\'\'\'

Versuchen Sie es über die Volltextsuche.
Alternativ können Sie auch den [[{{ns:special}}:Allpages|alphabetischen Index]] nach ähnlichen Begriffen durchsuchen.

Wenn Sie sich mit dem Thema auskennen, können Sie selbst die Seite „[[$1]]“ verfassen.',
'titlematches'		=> 'Übereinstimmungen mit Seitentiteln',
'notitlematches'	=> 'Keine Übereinstimmungen mit Seitentiteln',
'textmatches'		=> 'Übereinstimmungen mit Inhalten',
'notextmatches'		=> 'Keine Übereinstimmungen mit Inhalten',
"prevn"			=> "vorherige $1",
"nextn"			=> "nächste $1",
"viewprevnext"	=> "Zeige ($1) ($2) ($3).",
'showingresults'	=> 'Hier sind <b>$1</b> Ergebnisse, beginnend mit Nummer <b>$2</b>.',
'showingresultsnum'	=> 'Hier sind <b>$3</b> Ergebnisse, beginnend mit Nummer <b>$2</b>.',
'nonefound'		=> '<strong>Hinweis</strong>: Erfolglose Suchanfragen werden häufig verursacht durch den Versuch, nach <i>gewöhnlichen</i> Worten zu suchen; diese sind nicht indiziert.',
"powersearch" => "Suche",
'powersearchtext'	=> 'Suche in Namensräumen:<br />$1<br />$2 Weiterleitungen anzeigen<br />Suche nach: $3 $9',
'searchdisabled'	=> 'Die {{SITENAME}} Suche wurde deaktiviert. Sie können unterdessen über Google suchen. Bitte bedenken Sie, dass der Suchindex für {{SITENAME}} veraltet sein kann.',
'blanknamespace'	=> '(Seiten)',

# Preferences page
#
'preferences'		=> 'Einstellungen',
'mypreferences'		=> 'Einstellungen',
"prefsnologin" => "Nicht angemeldet",
'prefsnologintext'	=> 'Sie müssen [[{{ns:special}}:Userlogin|angemeldet]] sein, um Ihre Einstellungen ändern zu können.',
"prefsreset"	=> "Einstellungen wurden auf Standard zurückgesetzt.",
"qbsettings"	=> "Seitenleiste",
"changepassword" => "Passwort ändern",
"skin"			=> "Skin",
"math"			=> "TeX",
"dateformat" => "Datumsformat",
'datedefault'		=> 'Standard',
'datetime'		=> 'Datum und Zeit',
"math_failure"		=> "Parser-Fehler",
"math_unknown_error"	=> "Unbekannter Fehler",
"math_unknown_function"	=> "Unbekannte Funktion",
"math_lexing_error"	=> "'Lexing'-Fehler",
"math_syntax_error"	=> "Syntaxfehler",
"saveprefs"		=> "Einstellungen speichern",
"resetprefs"	=> "Einstellungen verwerfen",
"oldpassword"	=> "Altes Passwort:",
"newpassword"	=> "Neues Passwort:",
"retypenew"		=> "Neues Passwort (nochmal):",
'textboxsize'		=> 'Bearbeiten',
"rows"			=> "Zeilen",
"columns"		=> "Spalten",
'searchresultshead'	=> 'Suche',
'resultsperpage'	=> 'Treffer pro Seite:',
'contextlines'		=> 'Zeilen pro Treffer:',
'contextchars'		=> 'Zeichen pro Zeile:',
'stubthreshold'		=> 'Kleine Seiten markieren bis (Zeichen):',
"recentchangescount" => 'Anzahl der Einträge in „Letzte Änderungen“:',
"savedprefs"	=> "Ihre Einstellungen wurden gespeichert.",
"timezonelegend" => "Zeitzone",
"timezonetext"	=> "Geben Sie die Anzahl der Stunden ein, die zwischen Ihrer Zeitzone und UTC liegen.",
'localtime'		=> 'Ortszeit:',
'timezoneoffset'	=> 'Unterschied¹:',
'servertime'		=> 'Aktuelle Zeit auf dem Server:',
'guesstimezone'		=> 'Aus Browser übernehmen',
'allowemail'		=> 'E-Mail-Empfang von anderen Benutzern ermöglichen.',
"defaultns"		=> "In diesen Namensräumen soll standardmäßig gesucht werden:",
'default'		=> 'Voreinstellung',
'files'			=> 'Dateien',
'imagemaxsize'		=> 'Maximale Bildgröße auf Bildbeschreibungsseiten:',
'thumbsize'		=> 'Größe der Vorschaubilder (Thumbnails):',
'showbigimage'		=> 'Version mit hoher Auflösung herunterladen ($1 x $2 Pixel, $3 kB)',

# Recent changes
#
"changes" => "Änderungen",
"recentchanges" => "Letzte Änderungen",
'recentchangestext'	=> 'Auf dieser Seite können Sie die letzten Änderungen auf \'\'\'{{SITENAME}}\'\'\' nachverfolgen.',
'rcnote'		=> 'Angezeigt werden die letzten <b>$1</b> Änderungen der letzten <b>$2</b> Tage (Stand: $3).<br />(<b>N</b> - neuer Beitrag; <b>K</b> - kleine Änderung; <b>B</b> - Bot-Änderung)',
'rcnotefrom'		=> 'Angezeigt werden die Änderungen seit <b>$2</b> (max. <b>$1</b> Einträge).',
'rclistfrom'		=> 'Nur Änderungen seit $1 zeigen.',
'rclinks'		=> 'Zeige die letzten $1 Änderungen der letzten $2 Tage.<br />$3',
"diff"			=> "Unterschied",
"hist"			=> "Versionen",
"hide"			=> "ausblenden",
"show"			=> "einblenden",
'minoreditletter'	=> 'K',
'newpageletter'		=> 'N',
'boteditletter'		=> 'B',
'sectionlink'		=> '→',
'number_of_watching_users_pageview'	=> '[$1 beobachtende/r Benutzer]',
'rc_categories'		=> 'Nur Kategorien (getrennt mit „|“):',
'rc_categories_any'	=> 'Alle',


# Upload
#
"upload"		=> "Hochladen",
"uploadbtn"		=> "Datei hochladen",
'reupload'		=> 'Abbrechen',
"reuploaddesc"	=> "Zurück zur Hochladen-Seite.",
"uploadnologin" => "Nicht angemeldet",
'uploadnologintext'	=> 'Sie müssen [[{{ns:special}}:Userlogin|angemeldet sein]], um Dateien hochladen zu können.',
'upload_directory_read_only'	=> 'Der Webserver hat keine Schreibrechte für das Upload-Verzeichnis ($1).',
"uploaderror"	=> "Fehler beim Hochladen",
'uploadtext'		=> 'Gehen Sie zu der [[{{ns:special}}:Imagelist|Liste hochgeladener Dateien]], um vorhandene Dateien zu suchen und anzuzeigen.

Benutzen Sie dieses Formular, um neue Dateien hochzuladen. Klicken Sie auf \'\'\'„Durchsuchen...“\'\'\', um einen Dateiauswahl-Dialog zu öffnen.
Nach der Auswahl einer Datei wird der Dateiname im Textfeld \'\'\'„Quelldatei“\'\'\' angezeigt.
Bestätigen Sie dann die Copyright-Vereinbarung und klicken anschliessend auf \'\'\'„Datei hochladen“\'\'\'.
Dies kann eine Weile dauern, besonders bei einer langsamen Internet-Verbindung.

Um ein \'\'\'Bild\'\'\' in einer Seite zu verwenden, schreiben Sie an Stelle des Bildes zum Beispiel:
* \'\'\'<tt><nowiki>[[{{ns:image}}:Datei.jpg]]</nowiki></tt>\'\'\'
* \'\'\'<tt><nowiki>[[{{ns:image}}:Datei.jpg|Link-Text]]</nowiki></tt>\'\'\'

Um \'\'\'Mediendateien\'\'\' einzubinden, verwenden Sie zum Beispiel:
* \'\'\'<tt><nowiki>[[{{ns:media}}:Datei.ogg]]</nowiki></tt>\'\'\'
* \'\'\'<tt><nowiki>[[{{ns:media}}:Datei.ogg|Link-Text]]</nowiki></tt>\'\'\'

Bitte beachten Sie, dass, genau wie bei normalen Seiteninhalten, andere Benutzer Ihre Dateien löschen oder verändern können.',
"uploadlog"		=> "Datei-Logbuch",
"uploadlogpage" => "Datei-Logbuch",
"uploadlogpagetext" => "Hier ist die Liste der letzten hochgeladenen Dateien.
Alle Zeiten sind UTC.
<ul>
</ul>",
"filename"		=> "Dateiname",
"filedesc"		=> "Beschreibung",
'fileuploadsummary'	=> 'Beschreibung/Quelle:',
"filestatus" => "Copyright-Status",
"filesource" => "Quelle",
'copyrightpage'		=> '{{ns:project}}:Urheberrecht',
'copyrightpagename'	=> '{{SITENAME}} Urheberrecht',
"uploadedfiles"	=> "Hochgeladene Dateien",
'ignorewarning'		=> 'Warnung ignorieren und Datei speichern.',
'ignorewarnings'	=> 'Warnungen ignorieren',
'minlength'		=> 'Dateiname müssen mindestens drei Buchstaben lang sein.',
'illegalfilename'	=> 'Der Dateiname „$1“ enthält mindestens ein nicht erlaubtes Zeichen. Bitte benennen Sie die Datei um und versuchen Sie diese erneut hochzuladen.',
'badfilename'		=> 'Der Dateiname wurde in „$1“ geändert.',
'badfiletype'		=> '„.$1“ ist kein empfohlenes Dateiformat.',
'largefile'		=> 'Es wird nicht empfohlen Dateien hochzuladen, die größer als $1 Bytes sind. Diese Datei ist $2 Bytes groß.',
'largefileserver'	=> 'Die Datei ist größer als die vom Server eingestellte Maximalgröße.',
'emptyfile'		=> 'Die hochgeladene Datei ist leer. Der Grund kann ein Tippfehler im Dateinamen sein. Bitte kontrollieren Sie, ob Sie die Datei wirklich hochladen wollen.',
'fileexists'		=> 'Eine Datei mit diesem Namen existiert bereits. Wenn Sie auf „Datei speichern“ klicken, wird die Datei überschrieben. Bitte prüfen Sie $1, wenn Sie sich nicht sicher sind.',
'fileexists-forbidden'	=> 'Mit diesem Namen existiert bereits eine Datei. Bitte gehen Sie zurück und laden Ihre Datei unter einem anderen Namen hoch. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden'	=> 'Mit diesem Namen existiert bereits eine Datei. Bitte gehen Sie zurück und laden Sie diese Datei unter einem anderen Namen hoch. [[{{ns:image}:$1|thumb|center|$1]]',
'uploadedimage'		=> '„[[$1]]“ hochgeladen',
'uploadscripted' => 'Diese Datei enthält HTML- oder Scriptcode der irrtümlich von einem Webbrowser ausgeführt werden könnte.',
'uploadvirus' => 'Diese Datei enthält einen Virus! Details: $1',
'uploadcorrupt' => 'Die Datei ist beschädigt oder hat einen falschen Namen. Bitte überprüfen Sie die Datei und laden Sie sie erneut hoch.',
"successfulupload" => "Erfolgreich hochgeladen",
'fileuploaded'		=> 'Die Datei „$1“ wurde erfolgreich hochgeladen. Bitte folgen Sie dem Link $2 zur Beschreibungsseite und geben Sie weitere Informationen zur Datei an.

Falls es sich um ein Bild gehandelt hat, so können Sie mit <tt><nowiki>[[{{ns:image}}:$1|thumb|Description]]</nowiki></tt> ein Vorschaubild auf der Seite erzeugen lassen.',
"uploadwarning" => "Warnung",
"savefile"		=> "Datei speichern",
'uploadedimage'		=> '„[[$1]]“ hochgeladen',
'uploaddisabledtext'	=> 'Das Hochladen von Dateien ist in diesem Wiki deaktiviert.',
'sourcefilename'	=> 'Quelldatei',
'destfilename'		=> 'Dateiname ändern in',
'watchthisupload'	=> 'Diese Seite beobachten',
'filewasdeleted'	=> 'Eine Datei mit diesem Namen wurde schon einmal hochgeladen und zwischenzeitlich wieder gelöscht. Bitte prüfen Sie zuerst den Eintrag im $1, bevor Sie die Datei wirklich speichern.',

'license'		=> 'Lizenz',
'nolicense'		=> 'keine Vorauswahl',
'upload_source_url'	=> ' (gültige, öffentlich zugängliche URL)',
'upload_source_file'	=> ' (eine Datei auf Ihrem Computer)',

# Image list
#
'imagelist'		=> 'Dateiliste',
'imagelisttext'		=> 'Hier ist eine Liste von \'\'\'$1\'\'\' {{PLURAL:$1|Datei|Dateien}}, sortiert $2.',
'imagelistforuser'	=> 'Diese Seite zeigt nur Dateien, die von $1 hochgeladen wurden.',
'getimagelist'		=> 'Lade Dateiliste',
"ilsubmit"		=> "Suche",
'showlast'		=> 'Zeige die letzten $1 Dateien, sortiert nach $2.',
"byname"		=> "nach Name",
"bydate"		=> "nach Datum",
'bysize'		=> 'nach Größe',
"imgdelete"		=> "Löschen",
"imgdesc"		=> "Beschreibung",
'imgfile'		=> 'Datei',
'imglegend'		=> 'Legende: (Beschreibung) = Zeige/Bearbeite Dateibeschreibung.',
'imghistory'		=> 'Dateiversionen',
"revertimg"		=> "Zurücksetzen",
"deleteimg"		=> "Löschen",
'deleteimgcompletely'	=> 'Alle Versionen dieser Datei löschen',
'imghistlegend'		=> 'Legende: (Aktuell) = Dies ist die aktuelle Datei, (Löschen) = lösche diese alte Version, (Zurücksetzen) = diese alte Version wiederherstellen.',
'imagelinks'		=> 'Dateiverweise',
'linkstoimage'		=> 'Die folgenden Seiten benutzen diese Datei:',
'nolinkstoimage'	=> 'Keine Seite benutzt diese Datei.',
'sharedupload'			=> 'Diese Datei ist ein gemeinsam genutzter Upload und kann von anderen Projekten verwendet werden.',
'shareduploadwiki'		=> 'Für weitere Informationen siehe $1.',
'shareduploadwiki-linktext'	=> 'Dateibeschreibungsseite',
'noimage'			=> 'Eine Datei mit diesem Namen existiert nicht, Sie können sie jedoch $1.',
'noimage-linktext'		=> 'hochladen',
'uploadnewversion-linktext'	=> 'Eine neue Version dieser Datei hochladen',
'imagelist_date'	=> 'Datum',
'imagelist_name'	=> 'Name',
'imagelist_user'	=> 'Benutzer',
'imagelist_size'	=> 'Größe (Byte)',
'imagelist_description'	=> 'Beschreibung',
'imagelist_search_for'	=> 'Suche nach Datei:',


# List redirects
'listredirects' => 'Weiterleitungsliste',

# Unused templates
'unusedtemplates'	=> 'Nicht benutzte Vorlagen',
'unusedtemplatestext'	=> 'Diese Seite listet alle Vorlagen auf, die nicht in anderen Seiten eingebunden sind. Überprüfen Sie andere Links zu den Vorlagen, bevor Sie diese löschen.',
'unusedtemplateswlh' => 'Andere Verweise',

# Random redirect
'randomredirect' => 'Zufällige Weiterleitung',

# Statistics
#
"statistics"	=> "Statistik",
"sitestats"		=> "Seitenstatistik",
"userstats"		=> "Benutzerstatistik",
'sitestatstext'		=> 'Es gibt insgesamt \'\'\'$1\'\'\' Seiten in der Datenbank.
Das schliesst Diskussionsseiten, Seiten über {{SITENAME}}, kleine Seiten, Weiterleitungen und andere Seiten ein,
die eventuell nicht als Seiten gewertet werden können.

Diese ausgenommen gibt es \'\'\'$2\'\'\' Seiten, die als Seite gewertet werden können.

Insgesamt wurden \'\'\'$8\'\'\' Dateien hochgeladen.

Insgesamt gab es \'\'\'$3\'\'\' Seitenabrufe und \'\'\'$4\'\'\' Seitenbearbeitungen seit dieses Wiki eingerichtet wurde.
Daraus ergeben sich \'\'\'$5\'\'\' Bearbeitungen pro Seite und \'\'\'$6\'\'\' Seitenabrufe pro Bearbeitung.

Länge der „Job queue“: \'\'\'$7\'\'\'',
'userstatstext'		=> 'Es gibt \'\'\'$1\'\'\' registrierte [[{{ns:special}}:Listusers|Benutzer]].
Davon sind \'\'\'$2\'\'\' (=$4%) $5.',
'statistics-mostpopular'	=> 'Meist besuchte Seiten',

# Maintenance Page
#
"disambiguations"	=> "Begriffsklärungsseiten",
'disambiguationspage'	=> '{{ns:project}}:Begriffsklärung',
"disambiguationstext"	=> "Die folgenden Seiten verweisen auf eine <i>Seite zur Begriffsklärung</i>. Sie sollten statt dessen auf die eigentlich gemeinte Seite verweisen.<br />Eine Seite wird als Begriffsklärungsseite behandelt, wenn $1 auf sie verweist.<br />Verweise aus Namensräumen werden hier <i>nicht</i> aufgelistet.",
'doubleredirects'	=> 'Doppelte Weiterleitungen',
'doubleredirectstext'	=> '<b>Achtung:</b> Diese Liste kann „falsche Positive“ enthalten. Das ist dann der Fall, wenn eine Weiterleitung außer dem Weiterleitungs-Verweis noch weiteren Text mit anderen Verweisen enthält. Letztere sollten dann entfernt werden.',
'brokenredirects'	=> 'Kaputte Weiterleitungen',
'brokenredirectstext'	=> 'Die folgenden Weiterleitungen verweisen auf nicht existierende Seiten:',

# Miscellaneous special pages
#
"lonelypages"	=> "Verwaiste Seiten",
'unusedimages'		=> 'Verwaiste Dateien',
"popularpages"	=> "Beliebte Seiten",
'nviews'		=> '{{PLURAL:$1|eine Abfrage|$1 Abfragen}}',
'wantedcategories'	=> 'Gewünschte Kategorieseiten',
'wantedpages'		=> 'Gewünschte Seiten',
'mostlinkedcategories'	=> 'Meist benutzte Kategorien',
'mostcategories'	=> 'Meist kategorisierte Seiten',
'mostimages'		=> 'Meist benutzte Dateien',
'mostrevisions'		=> 'Seiten mit den meisten Versionen',
'nlinks'		=> '{{PLURAL:$1|ein Verweis|$1 Verweise}}',
'nmembers'		=> '{{PLURAL:$1|ein Eintrag|$1 Einträge}}',
'randompage'		=> 'Zufällige Seite',
"shortpages"	=> "Kurze Seiten",
"longpages"		=> "Lange Seiten",
"listusers"		=> "Benutzerverzeichnis",
"specialpages"	=> "Spezialseiten",
"spheading"		=> "Spezialseiten",
'restrictedpheading'	=> 'Spezialseiten für Administratoren',
'recentchangeslinked'	=> 'Änderungen an verlinkten Seiten',
'rclsub'		=> '(auf Seiten von „$1“)',
"newpages"		=> "Neue Seiten",
'newpages-username'	=> 'Benutzername:',
'ancientpages'		=> 'Lange unbearbeitete Seiten',
"movethispage"	=> "Seite verschieben",
'unusedimagestext'	=> '<p>Bitte beachten Sie, dass andere Wikis möglicherweise einige dieser Dateien verwenden.',
'unusedcategoriestext'	=> 'Die folgenden Kategorieseiten bestehen, obwohl sie momentan nicht in Verwendung sind.',
'booksources'		=> 'ISBN-Suche',
"booksourcetext" => "Dies ist eine Liste mit Links zu Internetseiten, die neue und gebrauchte Bücher verkaufen. Dort kann es auch weitere Informationen über die Bücher geben, die Sie interessieren. {{SITENAME}} ist mit keinem dieser Anbieter geschäftlich verbunden.",
"alphaindexline" => "$1 bis $2",
'newimages'	=> 'Neue Dateien',
'showhidebots'	=> '(Bots $1)',
'mimesearch'	=> 'Suche nach MIME-Typ',
'mimetype'	=> 'MIME-Typ:',
'download'	=> 'Herunterladen',
'mostlinked'	=> 'Häufig verlinkte Seiten',
'uncategorizedpages'	=> 'Nicht kategorisierte Seiten',
'uncategorizedcategories'	=> 'Nicht kategorisierte Kategorien',
'uncategorizedimages'	=> 'Nicht kategorisierte Dateien',
'unusedcategories' => 'Verwaiste Kategorien',
'unwatchedpages'	=> 'Nicht beobachtete Seiten',
'categoriespagetext'	=> 'Die folgenden Kategorien existieren in diesem Wiki.',
'data'			=> 'Daten',
'groups'		=> 'Benutzergruppen',
'noimages'	=> 'Keine Dateien gefunden.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn'	=> 'cn',
'variantname-zh-tw'	=> 'tw',
'variantname-zh-hk'	=> 'hk',
'variantname-zh-sg'	=> 'sg',
'variantname-zh'	=> 'zh',
# variants for Serbian language
'variantname-sr-ec'	=> 'sr-ec',
'variantname-sr-el'	=> 'sr-el',
'variantname-sr-jc'	=> 'sr-jc',
'variantname-sr-jl'	=> 'sr-jl',
'variantname-sr'	=> 'sr',
# variants for Kazakh language
'variantname-kk-tr'	=> 'kk-tr',
'variantname-kk-kz'	=> 'kk-kz',
'variantname-kk-cn'	=> 'kk-cn',
'variantname-kk'	=> 'kk',

# Special:Allpages
'allpages'		=> 'Alle Seiten',
'prefixindex'		=> 'Alle Seiten (mit Präfix)',
'nextpage'	=> "Nächste Seite ($1)",
'allpagesfrom'	=> 'Seiten anzeigen ab:',
'allpagesprefix'	=> 'Seiten anzeigen mit Präfix:',
'allarticles'	=> 'Alle Seiten',
'allinnamespace'	=> "Alle Seiten im Namensraum: $1",
'allnotinnamespace'	=> 'Alle Seiten (nicht im $1 Namensraum)',
'allpagesprev'	=> 'Vorherige',
'allpagesnext'	=> 'Nächste',
'allpagessubmit'	=> 'Anwenden',
'allpagesbadtitle'	=> 'Der eingegebene Seitenname ist ungültig: Er hat entweder ein vorangestelltes Sprach-, ein Interwiki-Kürzel oder enthält ein oder mehrere Zeichen, welche in Seitennamen nicht verwendet werden dürfen.',

# Special:Listusers
'listusersfrom'		=> 'Zeige Benutzer ab:',

# Email this user
#
"mailnologin"	=> "Sie sind nicht angemeldet.",
'mailnologintext'	=> 'Sie müssen [[{{ns:special}}:Userlogin|angemeldet sein]] und eine gültige E-Mail-Adresse haben, um anderen Benutzern E-Mails schicken zu können.',
"emailuser"		=> "E-Mail an diesen Benutzer",
"emailpage"		=> "E-Mail an Benutzer",
"emailpagetext"	=> "Wenn dieser Benutzer eine gültige E-Mail-Adresse angegeben hat, können Sie ihm mit dem untenstehenden Formular eine E-Mail senden. Als Absender wird die E-Mail-Adresse aus Ihren Einstellungen eingetragen, damit der Benutzer Ihnen antworten kann.",
"usermailererror" => "Das E-Mail-Objekt gab einen Fehler zurück:",
"defemailsubject"  => "{{SITENAME}} E-Mail",
"noemailtitle"	=> "Keine E-Mail-Adresse",
"noemailtext"	=> "Dieser Benutzer hat keine gültige E-Mail-Adresse angegeben, oder möchte keine E-Mail von anderen Benutzern empfangen.",
"emailfrom"		=> "Von",
"emailto"		=> "An",
"emailsubject"	=> "Betreff",
"emailmessage"	=> "Nachricht",
"emailsend"		=> "Senden",
"emailsent"		=> "E-Mail verschickt",
"emailsenttext" => "Ihre E-Mail wurde verschickt.",


# Beobachtungsliste
#
"watchlist"		=> "Beobachtungsliste",
'watchlistfor'		=> '(für \'\'\'$1\'\'\')',
"nowatchlist"	=> "Sie haben keine Einträge auf Ihrer Beobachtungsliste.",
'watchlistanontext'	=> 'Sie müssen sich $1, damit Sie Einträge in Ihrer Beobachtungsliste ansehen oder bearbeiten können.',	// $1 -> 'loginreqlink'
'watchlistcount'	=> "'''Sie haben $1 Einträge auf Ihrer Beobachtungsliste einschließlich Diskussionsseiten.'''",
'clearwatchlist'	=> 'Beobachtungsliste löschen',
'watchlistcleartext'	=> 'Sind Sie sicher, dass Sie diese vollständig löschen wollen?',
'watchlistclearbutton'	=> 'Beobachtungsliste löschen',
'watchlistcleardone'	=> 'Ihre Beobachtungsliste wurde gelöscht. $1 Einträge wurden entfernt.',
"watchnologin"	=> "Sie sind nicht angemeldet",
'watchnologintext'	=> 'Sie müssen [[{{ns:special}}:Userlogin|angemeldet]]
sein, um Ihre Beobachtungsliste zu bearbeiten.',
"addedwatch"	=> "Zur Beobachtungsliste hinzugefügt",
'addedwatchtext'	=> 'Die Seite „$1“ wurde zu Ihrer [[{{ns:special}}:Watchlist|Beobachtungsliste]] hinzugefügt.
Spätere Änderungen an dieser Seite und der zugehörigen Diskussions werden dort gelistet und die Seite wird
in der Liste der [[{{ns:special}}:Recentchanges|letzten Änderungen]] \'\'\'fett\'\'\' angezeigt. 

Wenn Sie die Seite wieder von Ihrer Beobachtungsliste entfernen wollen, klicken Sie auf „nicht mehr beobachten“.',
"removedwatch"	=> "Von der Beobachtungsliste entfernt",
'removedwatchtext'	=> 'Die Seite „$1“ wurde von Ihrer Beobachtungsliste entfernt.',
"watchthispage"	=> "Seite beobachten",
"unwatchthispage" => "Nicht mehr beobachten",
"notanarticle"	=> "Keine Seite",
"watchnochange" => "Keine Ihrer beobachteten Seiten wurde während des angezeigten Zeitraums bearbeitet.",
"watchdetails" => "* Sie beobachten zur Zeit insgesamt $1 Seiten (Diskussionsseiten wurden hier nicht mitgezählt).
* [[{{ns:special}}:Watchlist/edit|Gesamte Beobachtungsliste]] anzeigen und bearbeiten.",
'wlheader-enotif' 		=> "* E-Mail-Benachrichtigungsdienst ist eingeschaltet.",
'wlheader-showupdated'   => "* Seiten mit noch nicht gesehenen Änderungen werden '''fett''' dargestellt.",
"watchmethod-recent" => "Überprüfen der letzten Bearbeitungen für die Beobachtungsliste",
"watchmethod-list" => "Überprüfen der Beobachtungsliste nach letzten Bearbeitungen",
"removechecked" => "Markierte Einträge löschen",
"watchlistcontains" => "Ihre Beobachtungsliste enthält $1 Seiten.",
"watcheditlist" => "Alphabetische Liste der von Ihnen beobachteten Seiten.<br />
Hier können Sie Seiten markieren, um Sie dann von der Beobachtungsliste zu löschen.",
"removingchecked" => "Wunschgemäß werden die Einträge aus der Beobachtungsliste entfernt...",
"couldntremove" => "Der Eintrag '$1' kann nicht gelöscht werden...",
'iteminvalidname'	=> 'Problem mit dem Eintrag „$1“, ungültiger Name.',
'wlnote' => 'Es folgen die letzten $1 Änderungen der letzten <b>$2</b> Stunden.',
'wlshowlast' => 'Zeige die letzen: $1 Stunden - $2 Tage - $3',
'wlsaved'	 => 'Dies ist eine gespeicherte Version Ihrer Beobachtungsliste.',
'wlhideshowown'		=> '$1 meine Änderungen',
'wlhideshowbots'	=> '$1 von Bot-Änderungen.',
'wldone'		=> 'Erfolgreich ausgeführt.',

'updatedmarker'			=> '(geändert)',
'enotif_mailer' 		=> '{{SITENAME}} E-Mail-Benachrichtigungsdienst',
'enotif_reset'			=> 'Alle Seiten als besucht markieren',
'enotif_newpagetext'		=> 'Das ist eine neue Seite.',
'changed' 			=> 'geändert',
'created' 			=> 'erzeugt',
'enotif_subject'		=> '[{{SITENAME}}] Die Seite "$PAGETITLE" wurde von $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'		=> 'Alle Änderungen auf einen Blick: $1',
'enotif_body'			=> 'Liebe/r $WATCHINGUSERNAME,

die {{SITENAME}} Seite "$PAGETITLE" wurde von $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED.

Aktuelle Version: $PAGETITLE_URL

$NEWPAGE

Zusammenfassung des Bearbeiters: $PAGESUMMARY $PAGEMINOREDIT

Kontakt zum Bearbeiter:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Es werden solange keine weiteren Benachrichtigungsmails gesendet, bis Sie die Seite wieder besucht haben. Auf Ihrer Beobachtungsseite können Sie alle Benachrichtigungsmarker zusammen zurücksetzen.

             Ihr freundliches {{SITENAME}} Benachrichtigungssystem

-- 
Um die Einstellungen Ihrer Beobachtungsliste anzupassen besuchen Sie: {{fullurl:Special:Watchlist/edit}}',


# Delete/protect/revert
#
"deletepage"	=> "Seite löschen",
"confirm"		=> "Bestätigen",
"excontent" => "Alter Inhalt: '$1'",
'excontentauthor' => "Alter Inhalt: '$1' (einziger Bearbeiter war '$2')",
"exbeforeblank" => "Inhalt vor dem Leeren der Seite: '$1'",
"exblank" => "Seite war leer",
'confirmdelete'		=> 'Löschen bestätigen',
'deletesub'		=> '(Lösche „$1“)',
'historywarning'	=> 'WARNUNG: Die Seite die Sie löschen wollen hat eine Versionsgeschichte:',
'confirmdeletetext'	=> 'Sie sind dabei, eine Seite oder eine Datei und alle zugehörigen älteren Versionen
permanent aus der Datenbank zu löschen. Bitte bestätigen Sie dazu, dass Sie sich der Konsequenzen bewusst sind
und dass Sie in Übereinstimmung mit den [[{{ns:project}}:Löschregeln|Löschregeln]] handeln.

\'\'\'Achtung:\'\'\' Im Unterschied zu Textseiten können hochgeladene Dateien nicht wiederhergestellt werden.',
"actioncomplete" => "Aktion beendet",
'deletedtext'		=> '„$1“ wurde gelöscht. Im $2 finden Sie eine Liste der letzten Löschungen.',
'deletedarticle'	=> '„$1“ gelöscht',
"dellogpage"	=> "Lösch-Logbuch",
"dellogpagetext" => "Hier ist eine Liste der letzten Löschungen (UTC).
<ul>
</ul>",
"deletionlog"	=> "Lösch-Logbuch",
"reverted"		=> "Auf eine alte Version zurückgesetzt",
"deletecomment"	=> "Grund der Löschung",
"imagereverted" => "Auf eine alte Version zurückgesetzt.",
"rollback"		=> 'Zurücksetzen der Änderungen',
'rollback_short'	=> 'Zurücksetzen',
'rollbacklink'		=> 'Zurücksetzen',
'rollbackfailed'	=> 'Zurücksetzen gescheitert',
'cantrollback'		=> 'Die Änderung kann nicht zurückgesetzt werden, da es keine früheren Autoren gibt.',
'alreadyrolled'	=> 'Die Zurücknahme der Seite [[$1]] von [[{{ns:user}}:$2|$2]]
([[{{ns:user_talk}}:$2|Diskussion]]) ist nicht möglich, da eine andere Änderung oder Rücknahme erfolgt ist.

Die letzte Änderung ist von [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Diskussion]])',
#   only shown if there is an edit comment
'editcomment'		=> 'Der Änderungskommentar war: „<i>$1</i>“.',
'revertpage'		=> 'Änderungen von [[{{ns:user}}:$2]] rückgängig gemacht und letzte Version von [[{{ns:user}}:$1]] wiederhergestellt',
'sessionfailure'	=> 'Es gab ein Problem mit Ihrer Benutzersitzung.
Diese Aktion wurde aus Sicherheitsgründen abgebrochen, um eine falsche Zuordnung Ihrer Änderungen zu einem anderen Benutzer zu verhindern.
Bitte gehen Sie zurück und versuchen den Vorgang erneut auszuführen.',

# Undelete
"undelete" => "Gelöschte Seite wiederherstellen",
"undeletepage" => "Gelöschte Seiten wiederherstellen",
'viewdeletedpage'	=> 'Gelöschte Seiten anzeigen',
"undeletepagetext" => "Die folgenden Seiten wurden gelöscht, sind aber immer noch
gespeichert und können wiederhergestellt werden.",
'undeleteextrahelp' => '* Um die Seite komplett mit allen Versionen wiederherzustellen, geben Sie bitte eine Begründung an und klicken auf „Wiederherstellen“.
* Möchten Sie nur bestimmte Versionen wiederherstellen, so wählen Sie diese bitte einzeln an Hand der Markierungen aus,
geben eine Begründung an und klicken dann auf „Wiederherstellen“.
* „Zurücksetzen“ leert das Kommentarfeld und entfernt alle Markierungen bei den Versionen.',
"undeletearticle" => "Gelöschte Seiten wiederherstellen",
"undeleterevisions" => "$1 Versionen archiviert",
"undeletehistory" => "Wenn Sie diese Seite wiederherstellen, werden auch alle alten
Versionen wiederhergestellt. Wenn seit der Löschung eine neue Seite gleichen
namens erstellt wurde, werden die wiederhergestellten Versionen als alte Versionen
dieser Seite erscheinen.",
'undeletehistorynoadmin'	=> 'Diese Seite wurde gelöscht. Der Grund für die Löschung ist in der Zusammenfassung angegeben,
genauso wie Details zum letzten Benutzer der diese Seite vor der Löschung bearbeitet hat.
Der aktuelle Text der gelöschten Seite ist nur Administratoren zugänglich.',
"undeleterevision" => "Gelöschte Version vom $1",
"undeletebtn" => "Wiederherstellen",
'undeletereset'		=> 'Zurücksetzen',
'undeletecomment' => 'Begründung:',
'undeletedarticle'	=> '„$1“ wiederhergestellt',
'undeletedrevisions' => "$1 Versionen wiederhergestellt.",
'undeletedrevisions-files'	=> '$1 Änderungen und $2 Dateien wiederhergestellt',
'undeletedfiles'	=> '$1 Dateien wiederhergestellt',
'cannotundelete'	=> 'Wiederherstellung fehlgeschlagen; jemand anderes hat die Seite bereits wiederhergestellt.',
'undeletedpage'		=> '<big>\'\'\'$1 wurde wiederhergestellt\'\'\'</big>

Im [[{{ns:special}}:Log/delete|Lösch-Logbuch]] finden Sie eine Übersicht von kürzlich gelöschten und wiederhergestellten Seiten.',

# Namespace form on various pages
'namespace'	=> 'Namensraum:',
'invert' => 'Auswahl umkehren',

# Contributions
#
"contributions"	=> "Benutzerbeiträge",
'mycontris'	=> 'Eigene Beiträge',
"contribsub"	=> "Für $1",
"nocontribs"	=> "Es wurden keine Änderungen für diese Kriterien gefunden.",
"ucnote"		=> "Dies sind die letzten <b>$1</b> Beiträge des Benutzers in den letzten <b>$2</b> Tagen.",
"uclinks"		=> "Zeige die letzten $1 Beiträge; zeige die letzten $2 Tage.",
'uctop'		=> ' (aktuell)',
'newbies'		=> 'Neulinge',

'sp-newimages-showfrom'		=> 'Neue Dateien seit $1 anzeigen',

'sp-contributions-newest'	=> 'Jüngste',
'sp-contributions-oldest'	=> 'Älteste',
'sp-contributions-newer' 	=> 'Jüngere $1',
'sp-contributions-older'	=> 'Ältere $1',
'sp-contributions-newbies-sub'	=> 'Für Neulinge',

# What links here
#
'whatlinkshere'	=> 'Links auf diese Seite',
"notargettitle" => "Keine Seite angegeben",
'notargettext'		=> 'Sie haben nicht angegeben, auf welche Seite diese Funktion angewendet werden soll.',
"linklistsub"	=> "(Liste der Verweise)",
'linkshere'	=> 'Die folgenden Seiten verweisen auf \'\'\'[[:$1]]\'\'\':',
'nolinkshere'	=> 'Keine Seite verweist auf \'\'\'[[:$1]]\'\'\'.',
'isredirect'		=> 'Weiterleitungsseite',
'istemplate'	=> 'Vorlageneinbindung',

# Block/unblock IP
#
'blockip'		=> 'Benutzer blockieren',
'blockiptext'		=> 'Benutzen Sie das Formular, um einen Benutzer oder eine IP-Adresse zu blockieren.
Dies sollte nur erfolgen, um Vandalismus zu verhindern und in Übereinstimmung mit unseren [[{{ns:project}}:Leitlinien|Leitlinien]] geschehen.
Bitte geben Sie den Grund für die Blockade an.',
"ipaddress"		=> "IP-Adresse",
'ipadressorusername'	=> 'IP-Adresse oder Benutzername',
'ipbreason'		=> 'Begründung',
'ipbanononly'		=> 'Nur anonyme Benutzer sperren',
'ipbcreateaccount'	=> 'Erstellung von Benutzerkonten verhindern',
'ipbsubmit'		=> 'Benutzer blockieren',
'ipbother'		=> 'Andere Dauer',
'ipboptions'		=> '1 Stunde:1 hour,2 Stunden:2 hours,6 Stunden:6 hours,1 Tag:1 day,3 Tage:3 days,1 Woche:1 week,2 Wochen:2 weeks,1 Monat:1 month,3 Monate:3 months,1 Jahr:1 year,Unbeschränkt:indefinite',
'ipbotheroption'	=> 'Andere Dauer',
"badipaddress"	=> "Die IP-Adresse hat ein falsches Format.",
"blockipsuccesssub" => "Blockade erfolgreich",
'blockipsuccesstext'	=> 'Der Benutzer/die IP-Adresse [[{{ns:special}}:Contributions/$1|$1]] wurde blockiert.

Beachten Sie die [[{{ns:special}}:Ipblocklist|{{int:ipblocklist}}]] für alle aktiven Blockaden.',
"unblockip"		=> "IP-Adresse freigeben",
"unblockiptext"	=> "Benutzen Sie das Formular, um eine blockierte IP-Adresse freizugeben.",
"ipusubmit"		=> "Diese Adresse freigeben",
'unblocked'		=> '[[{{ns:user}}:$1|$1]] wurde freigegeben',
"ipblocklist"	=> "Liste blockierter Benutzer/IP-Adressen",
'ipblocklistempty'	=> 'Die Liste der Benutzersperrungen hat keine Einträge.',
"blocklistline"	=> "$1, $2 blockierte $3 ($4)",
'infiniteblock'		=> 'unbegrenzt',
'expiringblock'		=> 'erlischt $1',
'anononlyblock'		=> 'nur Anonyme',
'createaccountblock'	=> 'Erstellung von Benutzerkonten gesperrt',
"blocklink"		=> "blockieren",
"unblocklink"	=> "freigeben",
"contribslink"	=> "Beiträge",
'autoblocker'		=> 'Automatische Blockierung, da Sie eine IP-Adresse benutzen mit „$1“. Grund: „$2“.',

# Developer tools
#
"lockdb"		=> "Datenbank sperren",
"unlockdb"		=> "Datenbank freigeben",
"lockdbtext"	=> "Mit dem Sperren der Datenbank werden alle Änderungen an Benutzereinstellungen, Beobachtungslisten, Seiten usw. verhindert. Bitte bestätigen Sie Ihre Absicht, die Datenbank zu sperren.",
"unlockdbtext"	=> "Das Aufheben der Datenbank-Sperre wird alle Änderungen wieder zulassen. Bitte bestätigen Sie Ihre Absicht, die Sperrung aufzuheben.",
"lockconfirm"	=> "Ja, ich möchte die Datenbank sperren.",
"unlockconfirm"	=> "Ja, ich möchte die Datenbank freigeben.",
"lockbtn"		=> "Datenbank sperren",
"unlockbtn"		=> "Datenbank freigeben",
"locknoconfirm" => "Sie haben das Bestätigungsfeld nicht markiert.",
"lockdbsuccesssub" => "Datenbank wurde erfolgreich gesperrt",
"unlockdbsuccesssub" => "Datenbank wurde erfolgreich freigegeben",
'lockdbsuccesstext'	=> 'Die {{SITENAME}}-Datenbank wurde gesperrt.
<br />Bitte geben Sie die Datenbank [[Special:Unlockdb|wieder frei]], sobald die Wartung abgeschlossen ist.',
"unlockdbsuccesstext" => "Die {{SITENAME}}-Datenbank wurde freigegeben.",
'lockfilenotwritable' => 'Die Datenbank-Sperrdatei ist nicht beschreibbar. Zum Sperren oder Freigeben der Datenbank muss diese für den Webserver beschreibbar sein.',
'databasenotlocked'	=> 'Die Datenbank ist nicht gesperrt.',

# User levels special page
#

# switching pan
'userrights'			=> 'Benutzerrechteverwaltung',
'userrights-lookup-user'	=> 'Gruppenzugehörigkeiten verwalten',
'userrights-user-editname' => 'Benutzername:',
'editusergroup' => 'Bearbeite Benutzerrechte',

# user groups editing
#
'userrights-editusergroup' => 'Bearbeite Gruppenzugehörigkeiten des Benutzers',
'saveusergroups' => 'Gruppenzugehörigkeiten speichern',
'userrights-groupsmember' => 'Mitglied von:',
'userrights-groupsavailable' => 'Verfügbare Gruppen:',
'userrights-groupshelp' => 'Wählen Sie die Gruppen, aus denen der Benutzer entfernt oder zu denen er hinzugefügt werden soll.
Nicht selektierte Gruppen werden nicht geändert. Eine Selektion kann mit \'\'\'Strg + Linksklick\'\'\' (bzw. Ctrl + Linksklick) entfernt werden.',

# Groups
'group'				=> 'Gruppe:',
'group-bot'			=> 'Bots',
'group-sysop'			=> 'Administratoren',
'group-bureaucrat'		=> 'Bürokraten',
'group-all'			=> '(alle)',

'group-bot-member'		=> 'Bot',
'group-sysop-member'		=> 'Administrator',
'group-bureaucrat-member'	=> 'Bürokrat',

'grouppage-bot'			=> '{{ns:project}}:Bots',
'grouppage-sysop'		=> '{{ns:project}}:Administratoren',
'grouppage-bureaucrat'		=> '{{ns:project}}:Bürokraten',

# Move page
#
'movepage'		=> 'Seite verschieben',
'movepagetext'		=> 'Mit diesem Formular können Sie eine Seite umbenennen (mitsamt allen Versionen). Der alte Titel wird zum neuen weiterleiten. Verweise auf den alten Titel werden nicht geändert, und die Diskussionsseite wird ebenfalls nicht mitverschoben.',
'movepagetalktext'	=> 'Die dazugehörige Diskussionsseite wird mitverschoben, \'\'\'es sei denn:\'\'\'
*Es existiert bereits eine Diskussionsseite mit diesem Namen, oder
*Sie wählen die untenstehende Option ab.

In diesen Fällen müssen Sie, falls gewünscht, den Inhalt der Seite von Hand verschieben oder zusammenführen.',
"movearticle"	=> "Seite verschieben",
"movenologin"   => "Sie sind nicht angemeldet",
"movenologintext" => "Sie müssen ein registrierter Benutzer und
[[{{ns:special}}:Userlogin|angemeldet]] sein,
um eine Seite zu verschieben.",
"newtitle"		=> "Zu neuem Titel",
'movepagebtn'		=> 'Seite verschieben',
"pagemovedsub"	=> "Verschiebung erfolgreich",
'pagemovedtext'		=> 'Seite „[[$1]]“ wurde nach „[[$2]]“ verschoben.',
'movereason'	=> 'Begründung',
'revertmove'	=> 'zurück verschieben',
'delete_and_move' => 'Löschen und Verschieben',
'delete_and_move_text'	=>
'==Löschen erforderlich==

Der Zielseite "[[$1]]" besteht bereits. Möchten Sie diesen löschen, um die Seite verschieben zu können?',
'delete_and_move_confirm' => 'Ja, Seite löschen.',
'delete_and_move_reason' => 'Gelöscht um Verschiebung zu ermöglichen',
'selfmove' => 'Ursprungs- und Zielname sind gleich; eine Seite kann nicht zu sich selbst verschoben werden.',
'immobile_namespace'	=> 'Der Quell- oder Zielnamensraum ist geschützt; Verschiebungen in diesen Namensraum hinein oder aus diesem heraus sind nicht möglich.',
"articleexists" => "Unter diesem Namen existiert bereits eine Seite.
Bitte wählen Sie einen anderen Namen.",
'talkexists'		=> 'Die Seite selbst wurde erfolgreich verschoben, aber die zugehörige Diskussions-Seite nicht, da bereits eine mit dem neuen Titel existiert. Bitte gleichen Sie die Inhalte von Hand ab.',
"movedto"		=> "verschoben nach",
'movetalk'		=> 'Die Diskussionsseite mitverschieben, wenn möglich.',
'talkpagemoved'		=> 'Die Diskussionsseite wurde ebenfalls verschoben.',
'talkpagenotmoved'	=> 'Die Diskussionsseite wurde <strong>nicht</strong> verschoben.',

"export"        => "Seiten exportieren",
'exporttext'		=> 'Mit dieser Spezialseite können  Sie den Text (und die Bearbeitungs-/Versionsgeschichte) einzelner Seiten nach XML exportieren.
Das Ergebnis kann in ein anderes Wiki mit MediaWiki-Software eingespielt, bearbeitet oder archiviert werden.

Tragen Sie einfach den oder die entsprechenden Seitentitel in das folgende Textfeld ein (pro Zeile jeweils nur für eine Seite).

Alternativ ist der Export auch mit der Syntax <tt><nowiki>[[Spezial:Export/Seitentitel]]</nowiki></tt> möglich, zum Beispiel [[{{ns:special}}:Export/{{Mediawiki:mainpage}}]] für die [[{{Mediawiki:mainpage}}]].',
"exportcuronly" => "Nur die aktuelle Version der Seite exportieren",
'exportnohistory' => '----
\'\'\'Hinweis:\'\'\' Der Export kompletter Versionsgeschichten ist aus Performancegründen bis auf Weiteres nicht möglich.',
'export-submit'		=> 'Seiten exportieren',
"missingimage"          => "<b>Fehlendes Bild</b><br /><i>$1</i>",
'filemissing' => 'Datei fehlt',
'thumbnail_error' => 'Fehler beim Erstellen des Vorschaubildes: $1',

#Tooltips:
'tooltip-watch' => 'Diese Seite beobachten. [alt-w]',
'tooltip-search' => 'Suchen [alt-f]',
'tooltip-minoredit' => 'Diese Änderung als klein markieren. [alt-i]',
'tooltip-save' => 'Änderungen speichern [alt-s]',
'tooltip-preview' => 'Vorschau der Änderungen an dieser Seite. Benutzen Sie dies vor dem Speichern! [alt-p]',
'tooltip-diff' => 'Zeigt Ihre Änderungen am Text tabellarisch an [alt-d]',
'tooltip-compareselectedversions' => 'Unterschiede zwischen zwei ausgewählten Versionen dieser Seite vergleichen. [alt-v]',

# Metadata
'nodublincore' => 'Dublin Core RDF-Metadaten sind für diesen Server deaktiviert.',
'nocreativecommons' => 'Creative Commons RDF-Metadaten sind für diesen Server deaktiviert.',
'notacceptable' => 'Der Wiki-Server kann die Daten nicht für Ihr Ausgabegerät aufbereiten.',

# Attribution
'anonymous' => 'Anonyme(r) Benutzer auf {{SITENAME}}',
'lastmodifiedatby'	=> 'Diese Seite wurde zuletzt geändert am $1 um $2 Uhr von $3.',
'othercontribs' => 'Basiert auf der Arbeit von $1',
'others' => 'andere',
'creditspage' => 'Seiteninformationen',
'nocredits' => 'Für diese Seite sind keine Informationen vorhanden.',

# Info page
'infosubtitle'		=> 'Seiteninformation',
'numedits'		=> 'Anzahl der Seitenänderungen: $1',
'numtalkedits'		=> 'Anzahl der Diskussionsänderungen: $1',
'numwatchers'		=> 'Anzahl der Beobachter: $1',
'numauthors'		=> 'Anzahl der Autoren: $1',
'numtalkauthors'	=> 'Anzahl der Diskussionsteilnehmer: $1',

#Tastatur-Shortcuts
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'd',
'accesskey-compareselectedversions' => 'v',
'accesskey-watch' => 'w',

"makesysoptitle"        => "Mache einen Benutzer zum Administrator",
"makesysoptext"         => "Diese Maske wird von Bürokraten benutzt, um normale Benutzer zu Administratoren zu machen.",
"makesysopname"         => "Name des Benutzers:",
"makesysopsubmit"       => "Mache diesen Benutzer zu einem Administrator",
'makesysopok'		=> '<b>Benutzer „$1“ ist nun ein Administrator.</b>',
'makesysopfail'		=> '<b>Benutzer „$1“ konnte nicht zu einem Administrator gemacht werden. (Haben Sie den Namen richtig geschrieben?)</b>',
"makesysop"         => "Mache einen Benutzer zum Administrator",
'setbureaucratflag'	=> 'Mache Benutzer zum Bürokraten',
'rightslog' => 'Rechte-Logbuch',
'rightslogtext' => 'Dies ist das Logbuch der Änderungen der Benutzerrechte.',
'rightslogentry'	=> 'Gruppenzugehörigkeit für „[[$1]]“ von „$2“ auf „$3“ geändert.',
"rights"		=> "Rechte:",
"set_user_rights"	=> "Benutzerrechte setzen",
'user_rights_set'	=> '<b>Benutzerrechte für „$1“ aktualisiert</b>',
'set_rights_fail'	=> '<b>Benutzerrechte für „$1“ konnten nicht gesetzt werden. (Haben Sie den Namen korrekt eingegeben?)</b>',
'already_sysop'		=> 'Dieser Benutzer ist bereits Administrator.',
'already_bureaucrat'	=> 'Dieser Benutzer ist bereits Bürokrat.',
'rightsnone'		=> '(nichts)',

'1movedto2'		=> 'hat [[$1]] nach [[$2]] verschoben',
'1movedto2_redir'	=> 'hat [[$1]] nach [[$2]] verschoben und dabei eine Weiterleitung überschrieben.',
'movelogpage'		=> 'Verschiebungs-Logbuch',
'movelogpagetext'	=> 'Dies ist eine Liste aller verschobenen Seiten.',
'allmessages'		=> 'MediaWiki-Texte',
'allmessagesname'	=> 'Name',
"allmessagestext"	=> "Dies ist eine Liste aller möglichen Texte im MediaWiki-Namensraum.",
'allmessagesnotsupportedUI' => 'Die aktuelle Sprache Ihrer Benutzeroberfläche <b>$1</b> wird auf dieser Seite nicht von {{ns:special}}:Allmessages unterstützt.',
'allmessagesnotsupportedDB' => '\'\'\'{{ns:special}}:Allmessages\'\'\' ist momentan nicht möglich, weil die Datenbank offline ist.',
'allmessagesdefault'	=> 'Standardtext',
'allmessagescurrent'	=> 'Aktueller Text',
'allmessagesfilter'	=> 'Filter für Meldungsnamen:',
'allmessagesmodified'	=> 'Nur geänderte zeigen',
"thumbnail-more"	=> "vergrößern",
"and"			=> "und",
'rcshowhideminor' => 'Kleine Änderungen $1',
'rcshowhidebots'	=> 'Bots $1',
'rcshowhideliu' => 'Angemeldete Benutzer $1',
'rcshowhideanons' => 'Anonyme Benutzer $1',
'rcshowhidepatr' => 'Überprüfte Änderungen $1',
'rcshowhidemine' => 'Eigene Beiträge $1',
"uploaddisabled"	=> "Entschuldigung, das Hochladen ist deaktiviert.",
'deadendpages'		=> 'Sackgassenseiten',
'deadendpagestext'	=> 'Die folgenden Seiten verweisen nicht auf andere Seiten in diesem Wiki.',
'intl'			=> 'Interwiki Links',
"version"		=> "Version",
"log"			=> "Logbücher",
'alllogstext'		=> 'Kombinierte Anzeige der Datei-, Lösch-, Seitenschutz-, Benutzerblockaden- und Rechte-Logbücher.<br />Sie können die Anzeige durch die Auswahl des Logbuchtyps, des Benutzers oder des Seitentitels einschränken.',
'logempty'		=> 'Keine passenden Einträge.',
"protectlogpage"	=> "Seitenschutz-Logbuch",
'protectlogtext'	=> 'Dies ist eine Liste der blockierten Seiten.',
'protectedarticle'	=> '„[[$1]]“ geschützt',
'unprotectedarticle'	=> '„[[$1]]“ freigegeben',
'protectsub'		=> '(Sperren von „$1“)',
"confirmprotecttext" => "Soll diese Seite wirklich geschützt werden?",
'protect-text'		=> 'Hier können Sie den Schutzstatus für die Seite \'\'\'$1\'\'\' einsehen und ändern.',
'protect-level-autoconfirmed'	=> 'nicht registrierte Benuter blocken',
'protect-level-sysop'	=> 'nur Administratoren',
'restriction-edit'	=> 'bearbeiten',
'restriction-move'	=> 'verschieben',
'protect-unchain'	=> 'Verschiebeschutz ändern',
'ipbexpiry'		=> 'Sperrdauer',
"blocklogpage"		=> "Benutzerblockaden-Logbuch",
'blocklogentry'		=> 'blockiert [[{{ns:user}}:$1]] - ([[{{ns:special}}:Contributions/$1|Beiträge]]) für einen Zeitraum von: $2',
'blocklogtext'		=> 'Dies ist ein Log über Sperrungen und Entsperrungen von Benutzern. Automatisch geblockte IP-Adressen werden nicht erfasst. Siehe [[{{ns:special}}:Ipblocklist|IP block list]] für eine Liste der gesperrten Benutzern.',
'unblocklogentry'	=> 'Blockade von [[{{ns:user}}:$1]] aufgehoben',
"range_block_disabled"	=> "Die Möglichkeit, ganze Adressräume zu sperren, ist nicht aktiviert.",
"ipb_expiry_invalid"	=> "Die angegebeben Ablaufzeit ist ungültig.",
'ipb_already_blocked'	=> '„$1“ ist bereits gesperrt',
"ip_range_invalid"	=> "Ungültiger IP-Addressbereich.",
"confirmprotect" 	=> "Sperrung bestätigen",
'protectmoveonly'	=> 'Nur vor dem Verschieben schützen',
'protectcomment' 	=> 'Grund der Sperrung:',
'unprotectsub'		=> '(Aufhebung der Sperrung von „$1“)',
"confirmunprotecttext"	=> "Wollen Sie wirklich die Sperrung dieser Seite aufheben?",
"confirmunprotect"	=> "Aufhebung der Sperrung bestätigen",
"unprotectcomment"	=> "Grund für das Aufheben der Sperrung",
'protect-viewtext'	=> 'Sie sind nicht berechtigt, den Seitenschutzstatus zu ändern. Hier ist der aktuelle Schutzstatus der Seite: [[$1]]',
'protect-default'	=> '(Standard)',
"proxyblocker"  	=> "Proxyblocker",
'ipb_cant_unblock'	=> 'Fehler: Block ID $1 nicht gefunden. Die Sperre wurde vermutlich bereits aufgehoben.',
"proxyblockreason"      => "Ihre IP-Adresse wurde gesperrt, da sie ein offener Proxy ist. Bitte kontaktieren Sie Ihren Provider oder Ihre Systemtechnik und informieren Sie sie über dieses mögliche Sicherheitsproblem.",
"proxyblocksuccess"     => "Fertig.",
'sorbs'			=> 'SORBS DNSbl',
'sorbsreason'		=> 'Ihre IP-Adresse ist bei [http://www.sorbs.net SORBS] DNSbl als offener PROXY gelistet.',
'sorbs_create_account_reason'	=> 'Ihre IP-Adresse ist bei [http://www.sorbs.net SORBS] DNSbl als offener PROXY gelistet. Sie können keinen Benutzer anlegen.',
"math_image_error"	=> "die PNG-Konvertierung schlug fehl.",
"math_bad_tmpdir"	=> "Kann das Temporärverzeichnis für mathematische Formeln nicht anlegen oder beschreiben.",
"math_bad_output"	=> "Kann das Zielverzeichnis für mathematische Formeln nicht anlegen oder beschreiben.",
"math_notexvc"		=> "Das texvc-Programm kann nicht gefunden werden. Bitte beachten Sie math/README.",
'prefs-personal' => 'Benutzerdaten',
'prefs-rc'		=> 'Anzeige von „Letzte Änderungen“',
'prefs-watchlist'	=> 'Beobachtungsliste',
'prefs-watchlist-days'	=> 'Anzahl der Tage, die auf der Beobachtungsliste angezeigt werden sollen:',
'prefs-watchlist-edits'	=> 'Anzahl der Einträge in der erweiterten Beobachtungsliste:',
'prefs-misc'		=> 'Verschiedenes',

# Special:Import
'import'		=> 'Seiten importieren',
'importinterwiki'	=> 'Transwiki Import',
'import-interwiki-text'	=> 'Wählen Sie ein Wiki und eine Seite zum Importieren aus.
Das Datum der jeweiligen Versionen und die Autoren bleiben erhalten.
Alle Transwiki Import-Aktionen werden im [[{{ns:special}}:Log/import|Import-Logbuch]] protokolliert.',
'import-interwiki-history'	=> 'Alle Versionen dieser Seite kopieren',
'import-interwiki-submit'	=> 'Import',
'import-interwiki-namespace'	=> 'Importiere Seiten in den Namensraum:',
'importtext'		=> 'Auf dieser Spezialseite können über [[{{ns:special}}:Export]] exportierte Seiten in dieses Wiki importiert werden.',
'importstart'		=> 'Importiere Seiten...',
'import-revision-count'	=> '$1 {{PLURAL:$1|Version|Versionen}}',
'importnopages'		=> 'Keine Seiten zum Importieren vorhanden.',
'importfailed'		=> 'Import fehlgeschlagen: $1',
'importunknownsource'	=> 'Unbekannte Importquelle',
'importcantopen'	=> 'Importdatei konnte nicht geöffnet werden',
'importbadinterwiki'	=> 'Falscher Interwiki Link',
'importnotext'		=> 'Leer oder kein Text',
'importsuccess'		=> 'Import erfolgreich!',
'importhistoryconflict'	=> 'Es existieren bereits ältere Versionen, welche mit diesen kollidieren. Möglicherweise wurde die Seite bereits vorher importiert.',
'importnosources'	=> 'Für den Transwiki Import sind keine Quellen definiert. Das direkte Hochladen von Versionen ist blockiert.',
'importnofile'		=> 'Es ist keine Importdatei ausgewählt worden!',
'importuploaderror'	=> 'Das Hochladen der Importdatei ist fehlgeschlagen. Vielleicht ist die Datei größer als erlaubt.',

# import log
'importlogpage'		=> 'Import-Logbuch',
'importlogpagetext'	=> 'Administrativer Import von Seiten mit Versionsgeschichte von anderen Wikis.',
'import-logentry-upload'	=> '$1 wurde importiert',
'import-logentry-upload-detail'	=> '{{PLURAL:$1|eine Version|$1 Versionen}}',
'import-logentry-interwiki'	=> '$1 wurde importiert (Transwiki)',
'import-logentry-interwiki-detail'	=> '{{PLURAL:$1|eine Version|$1 Versionen}} von $2',

"isbn"			=> "ISBN",
"siteuser" => "{{SITENAME}}-Benutzer $1",
"siteusers" => "{{SITENAME}}-Benutzer $1",
'watch' => 'beobachten',
'unwatch' => 'nicht mehr beobachten',
'move'			=> 'verschieben',
'edit'			=> 'bearbeiten',
'talk' => 'Diskussion',
'views'			=> 'Ansichten',
"nocookiesnew" => "Der Benutzerzugang wurde erstellt, aber Sie sind nicht eingeloggt. {{SITENAME}} benötigt für diese Funktion Cookies, bitte aktivieren Sie diese und loggen sich dann mit Ihrem neuen Benutzernamen und dem Passwort ein.",
"nocookieslogin" => "{{SITENAME}} benutzt Cookies zum Einloggen der Benutzer. Sie haben Cookies deaktiviert, bitte aktivieren Sie diese und versuchen es erneut.",

'spamprotectiontitle' => 'Spamschutzfilter',
'spamprotectiontext' => 'Die Seite die Sie speichern wollten wurde vom Spamschutzfilter blockiert. Das liegt wahrscheinlich an einem Link zu einer externen Seite.',
'spamprotectionmatch' => 'Der folgende Text hat den Spamfilter ausgelöst: $1',
'subcategorycount' => 'Diese Kategorie hat {{PLURAL:$1|eine Unterkategorie|$1 Unterkategorien}}.',
'categoryarticlecount' => 'Es gibt {{PLURAL:$1|einen|$1}} Seiten in dieser Kategorie.',
'spambot_username'		=> 'MediaWiki Spam-Säuberung',
'spam_reverting' => 'Letzte Version ohne Links zu $1 wiederhergestellt.',
'spam_blanking' => 'Alle Versionen enthielten Links zu $1, bereinigt.',

# math
'mw_math_png' => "Immer als PNG darstellen",
'mw_math_simple' => "Einfaches TeX als HTML darstellen, sonst PNG",
'mw_math_html' => "Wenn möglich als HTML darstellen, sonst PNG",
'mw_math_source' =>"Als TeX belassen (für Textbrowser)",
'mw_math_modern' => "Empfehlenswert für moderne Browser",
'mw_math_mathml' => 'MathML (experimentell)',

# Patrolling
'markaspatrolleddiff'		=> 'Als geprüft markieren',
'markaspatrolledtext'		=> 'Diese Seitenänderung als geprüft markieren',
'markedaspatrolled'		=> 'Als geprüft markiert',
'markedaspatrolledtext'		=> 'Die ausgewählte Seitenänderung wurde als geprüft markiert.',
'rcpatroldisabled'		=> 'Prüfung der letzten Änderungen gesperrt',
'rcpatroldisabledtext'		=> 'Die Prüfung der letzten Änderungen ist zur Zeit gesperrt.',
'markedaspatrollederror'	=> 'Markierung als „geprüft“ nicht möglich.',
'markedaspatrollederrortext'	=> 'Sie müssen eine Seitenänderung auswählen.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Benutzer:',
'speciallogtitlelabel' => 'Titel:',
'passwordtooshort' => 'Ihr Passwort ist zu kurz. Es muss mindestens $1 Zeichen lang sein.',

# Media Warning
'mediawarning'		=> '\'\'\'Warnung:\'\'\' Diese Art von Datei kann böswilligen Programmcode enthalten. Durch das Herunterladen oder Öffnen der Datei kann Ihr Computer beschädigt werden.<hr />',

'fileinfo'		=> '$1 kB, MIME Typ: <code>$2</code>',

# external editor support
'edit-externally' => 'Diese Datei mit einem externen Programm bearbeiten',
'edit-externally-help' => 'Siehe die [http://meta.wikimedia.org/wiki/Help:External_editors Installationsanweisungen] für weitere Informationen',

# Metadata
'metadata' => 'Metadaten',
'metadata-help' => 'Diese Datei enthält weitere Informationen, die in der Regel von der Digitalkamera oder dem verwendeten Scanner stammen. Durch nachträgliche Bearbeitung der Originaldatei können einige Details verändert worden sein.',
'metadata_help'		=> 'Metadaten:',
'metadata-expand' => 'Erweiterte Details einblenden',
'metadata-collapse'	=> 'Erweiterte Details ausblenden',
'metadata-fields'	=> 'Die folgenden EXIF-Metadaten in dieser MediaWiki-Nachricht werden auf Bildbeschreibungsseiten angezeigt. Weitere EXIF-Metadaten werden standardmäßig ausgeblendet.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# Exif tags
'exif-aperturevalue'		=> 'Blendenwert',
'exif-artist'			=> 'Fotograf',
'exif-bitspersample'		=> 'Bits pro Farbkomponente',
'exif-brightnessvalue'		=> 'Helligkeitswert',
'exif-cfapattern'		=> 'CFA-Muster',
'exif-colorspace'		=> 'Farbraum',
'exif-colorspace-1'		=> 'sRGB',
'exif-colorspace-ffff.h'	=> 'FFFF.H',
'exif-componentsconfiguration'	=> 'Bedeutung einzelner Komponenten',
'exif-componentsconfiguration-0'	=> 'Existiert nicht',
'exif-componentsconfiguration-1'	=> 'Y',
'exif-componentsconfiguration-2'	=> 'Cb',
'exif-componentsconfiguration-3'	=> 'Cr',
'exif-componentsconfiguration-4'	=> 'R',
'exif-componentsconfiguration-5'	=> 'G',
'exif-componentsconfiguration-6'	=> 'B',
'exif-compressedbitsperpixel'	=> 'Komprimierte Bits pro Pixel',
'exif-compression'		=> 'Art der Kompression',
'exif-compression-1'		=> 'Unkomprimiert',
'exif-compression-6'		=> 'JPEG',
'exif-contrast'			=> 'Kontrast',
'exif-contrast-0'		=> 'Normal',
'exif-contrast-1'		=> 'Schwach',
'exif-contrast-2'		=> 'Stark',
'exif-copyright'		=> 'Urheberrechte',
'exif-customrendered'		=> 'Benutzerdefinierte Bildverarbeitung',
'exif-customrendered-0'		=> 'Standard',
'exif-customrendered-1'		=> 'Benutzerdefiniert',
'exif-datetime'			=> 'Speicherzeitpunkt',
'exif-datetimedigitized'	=> 'Digitalisierungszeitpunkt',
'exif-datetimeoriginal'		=> 'Erfassungszeitpunkt',
'exif-devicesettingdescription'	=> 'Geräteeinstellung',
'exif-digitalzoomratio'		=> 'Digitalzoom',
'exif-exifversion'		=> 'Exif-Version',
'exif-exposurebiasvalue'	=> 'Belichtungsvorgabe',
'exif-exposureindex'		=> 'Belichtungsindex',
'exif-exposuremode'		=> 'Belichtungsmodus',
'exif-exposuremode-0'		=> 'Automatische Belichtung',
'exif-exposuremode-1'		=> 'Manuelle Belichtung',
'exif-exposuremode-2'		=> 'Belichtungsreihe',
'exif-exposureprogram'		=> 'Belichtungsprogramm',
'exif-exposureprogram-0'	=> 'Unbekannt',
'exif-exposureprogram-1'	=> 'Manuell',
'exif-exposureprogram-2'	=> 'Standardprogramm',
'exif-exposureprogram-3'	=> 'Zeitautomatik',
'exif-exposureprogram-4'	=> 'Blendenautomatik',
'exif-exposureprogram-5'	=> 'Kreativprogramm mit Bevorzugung hoher Schärfentiefe',
'exif-exposureprogram-6'	=> 'Action-Programm mit Bevorzugung einer kurzen Belichtungszeit',
'exif-exposureprogram-7'	=> 'Portrait-Programm',
'exif-exposureprogram-8'	=> 'Landschaftsaufnahmen',
'exif-exposuretime'		=> 'Belichtungsdauer',
'exif-exposuretime-format'	=> '$1 Sekunden ($2)',
'exif-filesource'		=> 'Quelle der Datei',
'exif-filesource-3'		=> 'DSC',
'exif-flash'			=> 'Blitz',
'exif-flashenergy'		=> 'Blitzstärke',
'exif-flashpixversion'		=> 'unterstützte Flashpix-Version',
'exif-fnumber'			=> 'Blende',
'exif-fnumber-format'		=> 'f/$1',
'exif-focallength'		=> 'Brennweite',
'exif-focallength-format'	=> '$1 mm',
'exif-focallengthin35mmfilm'	=> 'Brennweite (Kleinbildäquivalent)',
'exif-focalplaneresolutionunit'	=> 'Einheit der Sensorauflösung',
'exif-focalplaneresolutionunit-2'	=> 'Zoll',
'exif-focalplanexresolution'	=> 'Sensorauflösung horizontal',
'exif-focalplaneyresolution'	=> 'Sensorauflösung vertikal',
'exif-gaincontrol'		=> 'Verstärkung',
'exif-gaincontrol-0'		=> 'Keine',
'exif-gaincontrol-1'		=> 'Gering',
'exif-gaincontrol-2'		=> 'High gain up',
'exif-gaincontrol-3'		=> 'Low gain down',
'exif-gaincontrol-4'		=> 'High gain down',
'exif-gpsaltitude'		=> 'Höhe',
'exif-gpsaltituderef'		=> 'Bezugshöhe',
'exif-gpsareainformation'	=> 'Name des GPS-Gebietes',
'exif-gpsdatestamp'		=> 'GPS-Datum',
'exif-gpsdestbearing'		=> 'Motivrichtung',
'exif-gpsdestbearingref'	=> 'Referenz für Motivrichtung',
'exif-gpsdestdistance'		=> 'Motiventfernung',
'exif-gpsdestdistanceref'	=> 'Referenz für die Motiventfernung',
'exif-gpsdestlatitude'		=> 'Breite',
'exif-gpsdestlatituderef'	=> 'Referenz für die Breite',
'exif-gpsdestlongitude'		=> 'Länge',
'exif-gpsdestlongituderef'	=> 'Referenz für die Länge',
'exif-gpsdifferential'		=> 'GPS-Differentialkorrektur',
'exif-gpsdirection-m'		=> 'Magnetische Richtung',
'exif-gpsdirection-t'		=> 'Tatsächliche Richtung',
'exif-gpsdop'			=> 'Maßpräzision',
'exif-gpsimgdirection'		=> 'Bildrichtung',
'exif-gpsimgdirectionref'	=> 'Referenz für die Ausrichtung des Bildes',
'exif-gpslatitude'		=> 'Geografische Breite',
'exif-gpslatitude-n'		=> 'nördl. Breite',
'exif-gpslatitude-s'		=> 'südl. Breite',
'exif-gpslatituderef'		=> 'nördl. oder südl. Breite',
'exif-gpslongitude'		=> 'Geografische Länge',
'exif-gpslongitude-e'		=> 'östl. Länge',
'exif-gpslongitude-w'		=> 'westl. Länge',
'exif-gpslongituderef'		=> 'östl. oder westl. Länge',
'exif-gpsmapdatum'		=> 'Geodätisches Datum benutzt',
'exif-gpsmeasuremode'		=> 'Messverfahren',
'exif-gpsmeasuremode-2'		=> '2-dimensionale Messung',
'exif-gpsmeasuremode-3'		=> '3-dimensionale Messung',
'exif-gpsprocessingmethod'	=> 'Name des GPS-Verfahrens',
'exif-gpssatellites'		=> 'Für die Messung benutzte Satelliten',
'exif-gpsspeed'			=> 'Geschwindigkeit des GPS-Empfängers',
'exif-gpsspeed-k'		=> 'km/h',
'exif-gpsspeed-m'		=> 'mph',
'exif-gpsspeed-n'		=> 'Knoten',
'exif-gpsspeedref'		=> 'Geschwindigkeitseinheit',
'exif-gpsstatus'		=> 'Empfängerstatus',
'exif-gpsstatus-a'		=> 'Messung läuft',
#'exif-gpsstatus-v'		=> 'Measurement interoperability',
'exif-gpstimestamp'		=> 'GPS-Zeit',
'exif-gpstrack'			=> 'Bewegungsrichtung',
'exif-gpstrackref'		=> 'Referenz für Bewegungsrichtung',
'exif-gpsversionid'		=> 'GPS-Tag-Version',
'exif-imagedescription'		=> 'Bildtitel',
'exif-imagelength'		=> 'Länge',
'exif-imageuniqueid'		=> 'Bild-ID',
'exif-imagewidth'		=> 'Breite',
'exif-isospeedratings'		=> 'Film- oder Sensorempfindlichkeit (ISO)',
'exif-jpeginterchangeformat'	=> 'Offset zu JPEG SOI',
'exif-jpeginterchangeformatlength'	=> 'Größe der JPEG-Daten in Bytes',
'exif-lightsource'		=> 'Lichtquelle',
'exif-lightsource-0'		=> 'Unbekannt',
'exif-lightsource-1'		=> 'Tageslicht',
'exif-lightsource-10'		=> 'Bewölkt',
'exif-lightsource-11'		=> 'Schatten',
'exif-lightsource-12'		=> 'Tageslicht fluoreszierend (D 5700–7100 K)',
'exif-lightsource-13'		=> 'Tagesweiß fluoreszierend (N 4600–5400 K)',
'exif-lightsource-14'		=> 'Kaltweiß fluoreszierend (W 3900–4500 K)',
'exif-lightsource-15'		=> 'Weiß fluoreszierend (WW 3200–3700 K)',
'exif-lightsource-17'		=> 'Standardlicht A',
'exif-lightsource-18'		=> 'Standardlicht B',
'exif-lightsource-19'		=> 'Standardlicht C',
'exif-lightsource-2'		=> 'Fluoreszierend',
'exif-lightsource-20'		=> 'D55',
'exif-lightsource-21'		=> 'D65',
'exif-lightsource-22'		=> 'D75',
'exif-lightsource-23'		=> 'D50',
'exif-lightsource-24'		=> 'ISO Studio Kunstlicht',
'exif-lightsource-255'		=> 'Andere Lichtquelle',
'exif-lightsource-3'		=> 'Glühlampe',
'exif-lightsource-4'		=> 'Blitz',
'exif-lightsource-9'		=> 'Schönes Wetter',
'exif-make'			=> 'Hersteller',
'exif-makernote'		=> 'Herstellernotiz',
'exif-maxaperturevalue'		=> 'Größte Blende',
'exif-meteringmode'		=> 'Messverfahren',
'exif-meteringmode-0'		=> 'Unbekannt',
'exif-meteringmode-1'		=> 'Durchschnittlich',
'exif-meteringmode-2'		=> 'Mittenzentriert',
'exif-meteringmode-255'		=> 'Unbekannt',
'exif-meteringmode-3'		=> 'Spotmessung',
'exif-meteringmode-4'		=> 'Mehrfachspotmessung',
'exif-meteringmode-5'		=> 'Muster',
'exif-meteringmode-6'		=> 'Bildteil',
'exif-model'			=> 'Modell',
'exif-oecf'			=> 'Optoelektronischer Umrechnungsfaktor',
'exif-orientation'		=> 'Kameraausrichtung',
'exif-orientation-1'		=> 'Normal',
'exif-orientation-2'		=> 'Horizontal gedreht',
'exif-orientation-3'		=> 'Um 180° gedreht',
'exif-orientation-4'		=> 'Vertikal gedreht',
'exif-orientation-5'		=> 'Entgegen dem Uhrzeigersinn um 90° gedreht und vertikal gewendet',
'exif-orientation-6'		=> 'Um 90° in Uhrzeigersinn gedreht',
'exif-orientation-7'		=> 'Um 90° in Uhrzeigersinn gedreht und vertikal gewendet',
'exif-orientation-8'		=> 'Um 90° entgegen dem Uhrzeigersinn gedreht',
'exif-photometricinterpretation-2'	=> 'RGB',
'exif-photometricinterpretation-6'	=> 'YCbCr',
'exif-photometricinterpretation'	=> 'Pixelzusammensetzung',
'exif-pixelxdimension'		=> 'Gültige Bildhöhe',
'exif-pixelydimension'		=> 'Gültige Bildbreite',
'exif-planarconfiguration'	=> 'Datenausrichtung',
'exif-planarconfiguration-1'	=> 'Grobformat',
'exif-planarconfiguration-2'	=> 'Planarformat',
#'exif-primarychromaticities'	=> 'Chromaticities of primarities',
'exif-referenceblackwhite'	=> 'Schwarz/Weiß-Referenzpunkte',
'exif-relatedsoundfile'		=> 'Zugehörige Tondatei',
'exif-resolutionunit'		=> 'Masseinheit der Auflösung',
'exif-rowsperstrip'		=> 'Anzahl Zeilen pro Streifen',
'exif-samplesperpixel'		=> 'Anzahl Komponenten',
'exif-saturation'		=> 'Sättigung',
'exif-saturation-0'		=> 'Normal',
'exif-saturation-1'		=> 'Gering',
'exif-saturation-2'		=> 'Hoch',
'exif-scenecapturetype'		=> 'Aufnahmeart',
'exif-scenecapturetype-0'	=> 'Standard',
'exif-scenecapturetype-1'	=> 'Landschaft',
'exif-scenecapturetype-2'	=> 'Portrait',
'exif-scenecapturetype-3'	=> 'Nachtszene',
'exif-scenetype'		=> 'Szenentyp',
'exif-scenetype-1'		=> 'Normal',
'exif-sensingmethod'		=> 'Messmethode',
'exif-sensingmethod-1'		=> 'Undefiniert',
'exif-sensingmethod-2'		=> 'Ein-Chip-Farbsensor',
'exif-sensingmethod-3'		=> 'Zwei-Chip-Farbsensor',
'exif-sensingmethod-4'		=> 'Drei-Chip-Farbsensor',
#'exif-sensingmethod-5'		=> 'Color sequential area sensor',
'exif-sensingmethod-7'		=> 'Trilinearer Sensor',
#'exif-sensingmethod-8'		=> 'Color sequential linear sensor',
'exif-sharpness'		=> 'Schärfe',
'exif-sharpness-0'		=> 'Normal',
'exif-sharpness-1'		=> 'Gering',
'exif-sharpness-2'		=> 'Stark',
'exif-shutterspeedvalue'	=> 'Belichtungszeitwert',
'exif-software'			=> 'Software',
#'exif-spatialfrequencyresponse'	=> 'Spatial frequency response',
'exif-spectralsensitivity'	=> 'Spectral Sensitivity',
#'exif-stripbytecounts'		=> 'Bytes per compressed strip',
'exif-stripoffsets'		=> 'Bilddaten-Versatz',
'exif-subjectarea'		=> 'Bereich',
'exif-subjectdistance'		=> 'Entfernung',
'exif-subjectdistance-value'	=> '$1 Meter',
'exif-subjectdistancerange'	=> 'Motiventfernung',
'exif-subjectdistancerange-0'	=> 'Unbekannt',
'exif-subjectdistancerange-1'	=> 'Makro',
'exif-subjectdistancerange-2'	=> 'Nah',
'exif-subjectdistancerange-3'	=> 'Entfernt',
'exif-subjectlocation'		=> 'Motivstandort',
'exif-subsectime'		=> 'Speicherzeitpunkt (1/100 s)',
'exif-subsectimedigitized'	=> 'Digitalisierungszeitpunkt (1/100 s)',
'exif-subsectimeoriginal'	=> 'Erfassungszeitpunkt (1/100 s)',
'exif-transferfunction'		=> 'Übertragungsfunktion',
'exif-usercomment'		=> 'Benutzerkommentare',
'exif-whitebalance'		=> 'Weißabgleich',
'exif-whitebalance-0'		=> 'Automatisch',
'exif-whitebalance-1'		=> 'Manuell',
'exif-whitepoint'		=> 'Manuell mit Messung',
'exif-xresolution'		=> 'Horizontale Auflösung',
'exif-xyresolution-c'		=> '$1 dpc',
'exif-xyresolution-i'		=> '$1 dpi',
'exif-ycbcrcoefficients'	=> 'YCbCr-Koeffizienten',
'exif-ycbcrpositioning'		=> 'Y und C Positionierung',
'exif-ycbcrsubsampling'		=> 'Subsampling Rate von Y bis C',
'exif-yresolution'		=> 'Vertikale Auflösung',

# 'all' in various places, this might be different for inflected languages
'recentchangesall'	=> 'alle',
'imagelistall'		=> 'alle',
'watchlistall1'		=> 'alle',
'watchlistall2'		=> 'alle',
'namespacesall'		=> 'alle',

# E-mail address confirmation
'confirmemail'		=> 'Bestätigung der E-Mail-Adresse (Authentifizierung)',
'confirmemail_noemail'	=> 'Sie haben keine gültige E-Mail-Adresse in Ihrem [[Special:Preferences|Benutzerprofil]] angegeben.',
'confirmemail_text'	=> 'Dieses Wiki erfordert, dass Sie Ihre E-Mail-Adresse bestätigen (authentifizieren), bevor Sie die erweiterten E-Mail-Funktionen benutzen können. Durch einen Klick auf die Schaltfläche unten wird eine E-Mail an Sie gesendet. Diese E-Mail enthält einen Link mit einem Bestätigungs-Code. Durch Klicken auf diesen Link wird bestätigt, dass Ihre E-Mail-Adresse gültig ist.',
'confirmemail_send' => 'Anforderung einer E-Mail zur Adressenbestätigung',
'confirmemail_sent' => 'Es wurde Ihnen eine E-Mail zur Adressenbestätigung gesendet.',
'confirmemail_sendfailed' => 'Eine Bestätigung konnte auf Grund einer Fehlkonfiguration des Servers oder ungültigen Zeichen in der E-Mail-Adresse nicht verschickt werden.',
'confirmemail_invalid' => 'Ungültiger Bestätigungs-Code. Die Gültigkeitsdauer des Codes ist eventuell abgelaufen.',
'confirmemail_needlogin' => 'Sie müssen sich $1 um Ihre E-Mail-Adresse zu bestätigen.',
'confirmemail_success'	=> 'Ihre E-Mail-Adresse wurde erfolgreich bestätigt. Sie können sich jetzt einloggen.',
'confirmemail_loggedin'	=> 'Ihre E-Mail-Adresse wurde erfolgreich bestätigt.',
'confirmemail_error' => 'Es gab einen Fehler bei der Bestätigung Ihrer E-Mail-Adresse.',

'confirmemail_subject'	=> '[{{SITENAME}}] Bestätigung Ihrer E-Mail-Adresse',
'confirmemail_body'	=> 'Hallo,

jemand mit der IP-Adresse $1, wahrscheinlich Sie selbst, hat eine Bestätigung dieser E-Mail-Adresse für das Benutzerkonto "$2" für {{SITENAME}} angefordert.

Um die E-Mail-Funktion für {{SITENAME}} (wieder) zu aktivieren und um zu bestätigen, dass dieses Benutzerkonto wirklich zu Ihrer E-Mail-Adresse und damit zu Ihnen gehört, öffnen Sie bitte folgenden Link in Ihrem Browser: $3

Der Bestätigungscode ist bis zu folgendem Zeitpunkt gültig: $4

Wenn diese E-Mail-Adresse *nicht* zu dem genannten Benutzerkonto gehört, folgen Sie diesem Link bitte *nicht*.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Inputbox extension, may be useful in other contexts as well
'tryexact'		=> 'Versuche exakte Suche:',
'searchfulltext' => 'Gesamten Text durchsuchen',
'createarticle' => 'Seite anlegen',

# Scary transclusion
'scarytranscludedisabled'	=> '[Interwiki Einbindung ist deaktiviert]',
'scarytranscludefailed' => '[Vorlageneinbindung für $1 ist gescheitert]',
'scarytranscludetoolong' => '[URL ist zu lang; Entschuldigung]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Trackbacks für diese Seite:<br />
$1
</div>',
'trackbackremove' => '([$1 löschen])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'Trackback wurde erfolgreich gelöscht.',

# delete conflict
'deletedwhileediting' => 'Warnung. Diese Seite wurde gelöscht, nach dem Sie angefangen haben diese zu bearbeiten!',
'confirmrecreate'	=> 'Benutzer [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|Diskussion]]) hat diese Seite gelöscht, nachdem Sie angefangen haben ihn zu bearbeiten. Die Begründung lautete:
\'\'$2\'\'
Bitte bestätigen Sie, dass Sie diese Seite wirklich neu erstellen möchten.',
'recreate' => 'Wiederherstellen',
'tooltip-recreate' => 'Seite neu erstellen, obwohl sie gelöscht wurde.',

'unit-pixel' => 'px',

'searchcontaining' => "Suche nach Seiten, in denen ''$1'' vorkommt.",
'searchnamed' => "Suche nach Seiten, deren Name ''$1'' enthält.",
'articletitles' => "Seiten, die mit ''$1'' beginnen",
'hideresults' => 'Verbergen',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Eigene Benutzerseite\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Benutzerseite der IP-Adresse von der aus Sie Änderungen durchführen\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Eigene Diskussionsseite\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Diskussion über Änderungen von dieser IP-Adresse\');
ta[\'pt-preferences\'] = new Array(\'\',\'Eigene Einstellungen\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Liste der beobachteten Seiten\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Liste eigener Beiträge\');
ta[\'pt-login\'] = new Array(\'o\',\'Sich einzuloggen wird zwar gerne gesehen, ist aber keine Pflicht.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Sich einzuloggen wird zwar gerne gesehen, ist aber keine Pflicht.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Abmelden\');
ta[\'ca-talk\'] = new Array(\'t\',\'Diskussion zum Seiteninhalt\');
ta[\'ca-edit\'] = new Array(\'e\',\'Seite bearbeiten. Bitte benutzen Sie vor dem Speichern die Vorschaufunktion.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Einen Kommentar zu dieser Diskussion hinzufügen.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Diese Seite ist geschützt. Sie können sich den Quelltext ansehen.\');
ta[\'ca-history\'] = new Array(\'h\',\'Frühere Versionen dieser Seite\');
ta[\'ca-protect\'] = new Array(\'=\',\'Diese Seite schützen\');
ta[\'ca-delete\'] = new Array(\'d\',\'Diese Seite löschen\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Einträge wiederherstellen, bevor diese Seite gelöscht wurde\');
ta[\'ca-move\'] = new Array(\'m\',\'Diese Seite verschieben\');
ta[\'ca-watch\'] = new Array(\'w\',\'Diese Seite zu Ihrer Beobachtungsliste hinzufügen\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Diese Seite von Ihrer Beobachtungsliste entfernen\');
ta[\'search\'] = new Array(\'f\',\'Dieses Wiki durchsuchen\');
ta[\'p-logo\'] = new Array(\'\',\'Hauptseite\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Hauptseite anzeigen\');
ta[\'n-portal\'] = new Array(\'\',\'Über das Portal, was Sie tun können, wo was zu finden ist\');
ta[\'n-currentevents\'] = new Array(\'\',\'Hintergrundinformationen zu aktuellen Ereignissen\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Liste der letzten Änderungen in diesem Wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Zufällige Seite\');
ta[\'n-help\'] = new Array(\'\',\'Hilfeseite anzeigen\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Unterstützen Sie uns\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Liste aller Seiten, die hierher zeigen\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Letzte Änderungen an Seiten, die von hier verlinkt sind\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS-Feed für diese Seite\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom-Feed für diese Seite\');
ta[\'t-contributions\'] = new Array(\'\',\'Liste der Beiträge von diesem Benutzer ansehen\');
ta[\'t-emailuser\'] = new Array(\'\',\'Eine E-Mail an diesen Benutzer senden\');
ta[\'t-upload\'] = new Array(\'u\',\'Dateien hochladen\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Liste aller Spezialseiten\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Seiteninhalt anzeigen\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Benutzerseite anzeigen\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Mediendateienseite anzeigen\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Dies ist eine Spezialseite. Sie können diese nicht ändern.\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Portalseite anzeigen\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Bilderseite anzeigen\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Systemmeldungen anzeigen\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Vorlage anzeigen\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Hilfeseite anzeigen\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Kategorieseite anzeigen\');',

# image deletion
'deletedrevision' => 'Alte Version $1 gelöscht.',

# browsing diffs
'previousdiff' => '← Zum vorherigen Versionsunterschied',
'nextdiff' => 'Zum nächsten Versionsunterschied →',

# HTML dump
'redirectingto' => 'Weitergeleitet nach [[$1]]',

# action=purge
'confirm_purge' => "Den Cache dieser Seite leeren? $1",
'confirm_purge_button' => 'OK',

'newtalkseperator'	=> ',_',
'youhavenewmessagesmulti' => "Sie haben neue Nachrichten: $1",

# DISPLAYTITLE
'displaytitle' => '(Link zu dieser Seite als [[$1]])',

'loginlanguagelabel'	=> 'Sprache: $1',


# Multipage image navigation
'imgmultipageprev' => '← vorige Seite',
'imgmultipagenext' => 'nächste Seite →',
'imgmultigo' => 'OK',
'imgmultigotopre' => 'Gehe zu Seite',

# Table pager
'ascending_abbrev'	=> 'auf',
'descending_abbrev'	=> 'ab',
'table_pager_next'	=> 'Nächste Seite',
'table_pager_prev'	=> 'Vorherige Seite',
'table_pager_first'	=> 'Erste Seite',
'table_pager_last'	=> 'Letzte Seite',
'table_pager_limit'	=> 'Zeige $1 Einträge pro Seite',
'table_pager_limit_submit'	=> 'Los',
'table_pager_empty'	=> 'Keine Ergebnisse',

);


?>
