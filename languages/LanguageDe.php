<?php
#
# German localisation for MediaWiki
#
# This file is encoded in UTF-8, no byte order mark.
# For compatibility with Latin-1 installations, please
# don't add literal characters above U+00ff.
#
require_once( "LanguageUtf8.php" );

# See Language.php for notes.

if($wgMetaNamespace === FALSE)
        $wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesDe = array(
	NS_MEDIA            => "Media",
	NS_SPECIAL          => "Spezial",
	NS_MAIN             => "",
	NS_TALK             => "Diskussion",
	NS_USER             => "Benutzer",
	NS_USER_TALK        => "Benutzer_Diskussion",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . "_Diskussion",
	NS_IMAGE            => "Bild",
	NS_IMAGE_TALK       => "Bild_Diskussion",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "MediaWiki_Diskussion",
	NS_TEMPLATE         => "Vorlage",
	NS_TEMPLATE_TALK    => "Vorlage_Diskussion",
	NS_HELP             => "Hilfe",
	NS_HELP_TALK        => "Hilfe_Diskussion",
	NS_CATEGORY         => "Kategorie",
	NS_CATEGORY_TALK    => "Kategorie_Diskussion"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsDe = array(
	"Keine", "Links, fest", "Rechts, fest", "Links, schwebend"
);

/* private */ $wgSkinNamesDe = array(
	'standard'      => "Klassik",
	'nostalgia'     => "Nostalgie",
	'cologneblue'   => "Kölnisch Blau",
	'smarty'        => "Paddington",
	'montparnasse'  => "Montparnasse",
	'davinci'       => "DaVinci",
	'mono'          => "Mono",
	'monobook'      => "MonoBook",
	'myskin'        => "MySkin",
	'chick'         => "Küken"
);


/* private */ $wgBookstoreListDe = array(
	"Verzeichnis lieferbarer B&uuml;cher" => "http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1&x=0&y=0",
	"abebooks.de" => "http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1",
	"Amazon.de" => "http://www.amazon.de/exec/obidos/ISBN=$1",
	"Lehmanns Fachbuchhandlung" => "http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1",
);


/* private */ $wgValidSpecialPagesDe = array(
  "Userlogin"           => "",
  "Userlogout"          => "",
  "Preferences"         => "Meine Benutzereinstellungen",
  "Watchlist"           => "Meine Beobachtungsliste",
  "Recentchanges"       => "Letzte Änderungen",
  "Upload"              => "Dateien hochladen",
  "Imagelist"           => "Hochgeladene Dateien",
  "Listusers"           => "Registrierte Benutzer",
  "Statistics"          => "Seitenstatistik",
  "Randompage"          => "Zufälliger Artikel",

  "Lonelypages"         => "Verwaiste Artikel",
  "Unusedimages"        => "Verwaiste Dateien",
  "Popularpages"        => "Beliebte Artikel",
  "Wantedpages"         => "Gewünschte Artikel",
  "Shortpages"          => "Kurze Artikel",
  "Longpages"           => "Lange Artikel",
  "Newpages"            => "Neue Artikel",
  "Ancientpages"        => "Älteste Artikel",
/*  "Intl"                => "Interlanguage Links", */
  "Allpages"            => "Alle Artikel (alphabetisch)",

  "Ipblocklist"         => "Blockierte IP-Adressen",
  "Maintenance"         => "Wartungsseite",
  "Specialpages"        => "",
  "Contributions"       => "",
  "Movepage"            => "",
  "Emailuser"           => "",
  "Whatlinkshere"       => "",
  "Recentchangeslinked" => "",
  "Booksources"         => "Externe Buchhandlungen",
  "Categories"          => "Seiten-Kategorien",
  "Export"              => "XML-Seitenexport",
  "Version"				=> "Version",
);

/* private */ $wgSysopSpecialPagesDe = array(
	"Blockip"		=> "Blockiere eine IP-Adresse",
	"Asksql"		=> "Datenbank-Abfrage",
	"Undelete"              => "Gelöschte Seiten wiederherstellen"
);

/* private */ $wgDeveloperSpecialPagesDe = array(
	"Lockdb"		=> "Datenbank sperren",
	"Unlockdb"		=> "Datenbank freigeben",
);

/* private */ $wgAllMessagesDe = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User toggles
"tog-hover"	            => "Hinweis über interne Verweise",
"tog-underline"               => "Verweise unterstreichen",
"tog-highlightbroken"         => "Verweise auf leere Themen hervorheben",
"tog-justify"                 => "Text als Blocksatz",
"tog-hideminor"               => "Keine kleinen Änderungen in Letzte Änderungen anzeigen",
"tog-usenewrc"                => "Erweiterte letzte Änderungen (nicht für alle Browser geeignet)",
"tog-numberheadings"          => "Überschriften automatisch numerieren",
"tog-showtoolbar" 	    => "Editier-Werkzeugleiste anzeigen",
"tog-editondblclick"          => "Seiten mit Doppelklick bearbeiten (JavaScript)",
"tog-editsection"             => "Links zum Bearbeiten einzelner Absätze anzeigen",
"tog-editsectiononrightclick" => "Einzelne Absätze per Rechtsklick bearbeiten (Javascript)",
"tog-showtoc"                 => "Anzeigen eines Inhaltsverzeichnisses bei Artikeln mit mehr als 3 Überschriften",
"tog-rememberpassword"        => "Dauerhaftes Einloggen",
"tog-editwidth"               => "Text-Eingabefeld mit voller Breite",
"tog-watchdefault"            => "Neue und geänderte Seiten beobachten",
"tog-minordefault"            => "Alle Änderungen als geringfügig markieren",
"tog-previewontop"            => "Vorschau vor dem Editierfenster anzeigen",
"tog-nocache"                 => "Seitencache deaktivieren",
# Dates
'sunday' => "Sonntag",
'monday' => "Montag",
'tuesday' => "Dienstag",
'wednesday' => "Mittwoch",
'thursday' => "Donnerstag",
'friday' => "Freitag",
'saturday' => "Samstag",
'january' => "Januar",
'february' => "Februar",
'march' => "März",
'april' => "April",
'may_long' => "Mai",
'june' => "Juni",
'july' => "Juli",
'august' => "August",
'september' => "September",
'october' => "Oktober",
'november' => "November",
'december' => "Dezember",
'jan' => "Jan",
'feb' => "Feb",
'mar' => "Mär",
'apr' => "Apr",
'may' => "Mai",
'jun' => "Jun",
'jul' => "Jul",
'aug' => "Aug",
'sep' => "Sep",
'oct' => "Okt",
'nov' => "Nov",
'dec' => "Dez",


# Bits of text used by many pages:
#
"categories" => "Seitenkategorien",
"category" => "Kategorie",
"category_header" => "Artikel in der Kategorie \"$1\"",
"subcategories" => "Unterkategorien",
"linktrail"		=> "/^([ä|ö|ü|ß|a-z]+)(.*)\$/sD",
"mainpage"		=> "Hauptseite",
"mainpagetext"          => "Die Wiki Software wurde erfolgreich installiert.",
"mainpagedocfooter" => "Siehe die [http://meta.wikipedia.org/wiki/MediaWiki_i18n Dokumentation zur Anpassung der Benutzeroberfläche]
und das [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide Benutzerhandbuch] für Hilfe zur Benutzung und Konfiguration.",
'portal'		=> "{{SITENAME}}-Portal",
"portal-url"		=> "{{ns:4}}:Portal",
"about"			=> "Über",
"aboutsite"      => "Über {{SITENAME}}",
"aboutpage"		=> "{$wgMetaNamespace}:Über_{{SITENAME}}",
"article" => "Artikel",
"help"			=> "Hilfe",
"helppage"		=> "{$wgMetaNamespace}:Hilfe",
"wikititlesuffix"       => "{{SITENAME}}",
"bugreports"	=> "Kontakt",
"bugreportspage" => "{$wgMetaNamespace}:Kontakt",
"sitesupport"   => "Spenden",
"faq"			=> "FAQ",
"faqpage"		=> "{{SITENAME}}:Häufig_gestellte_Fragen",
"edithelp"		=> "Bearbeitungshilfe",
"edithelppage"	=> "{{SITENAME}}:Editierhilfe",
"cancel"		=> "Abbruch",
"qbfind"		=> "Finden",
"qbbrowse"		=> "Blättern",
"qbedit"		=> "Ändern",
"qbpageoptions" => "Seitenoptionen",
"qbpageinfo"	=> "Seitendaten",
"qbmyoptions"	=> "Einstellungen",
"qbspecialpages"	=> "Spezialseiten",
"moredotdotdot"	=> "Mehr...",
"mypage"		=> "Meine Seite",
"mytalk"		=> "Meine Diskussion",
"anontalk"		=> "Diskussionsseite dieser IP",
"navigation" => "Navigation",
"currentevents" => "Aktuelle Ereignisse",
"disclaimers" => "Lizenzbestimmungen",
"disclaimerpage"		=> "{{ns:4}}:Lizenzbestimmungen",
"errorpagetitle" => "Fehler",
"returnto"		=> "Zurück zu $1.",
"tagline"      	=> "aus {{SITENAME}}, der freien Wissensdatenbank",
"whatlinkshere"	=> "Was zeigt hierhin",
"help"			=> "Hilfe",
"search"		=> "Suche",
"history"		=> "Versionen",
"history_short" => "Versionen",
"printableversion" => "Druckversion",
"editthispage"	=> "Seite bearbeiten",
"delete" => "löschen",
"deletethispage" => "Diese Seite löschen",
"undelete_short" => "Wiederherstellen",
"protect" => "Schützen",
"protectthispage" => "Artikel schützen",
"unprotect" => "Freigeben",
"unprotectthispage" => "Schutz aufheben",
"newpage" => "Neue Seite",
"talkpage"		=> "Diskussion",
"specialpage" => "Spezialseite",
"personaltools" => "'Persönliche Werkzeuge",
"postcomment" => "Kommentar hinzufügen",
"addsection"   => "+",
"articlepage"	=> "Artikel",
"toolbox" => "Werkzeuge",
"wikipediapage" => "Meta-Text",
"userpage" => "Benutzerseite",
"imagepage" => "Bildseite",
"viewtalkpage" => "Diskussion",
"otherlanguages" => "Andere Sprachen",
"redirectedfrom" => "(Weitergeleitet von $1)",
"lastmodified"	=> "Diese Seite wurde zuletzt geändert um $1.",
"viewcount"		=> "Diese Seite wurde bisher $1 mal abgerufen.",
"copyright"	=> "Inhalt ist verfügbar unter der $1.",
"poweredby"	=> "{{SITENAME}} benutzt [http://www.mediawiki.org/ MediaWiki], eine Open Source Wiki-Engine.",
"printsubtitle" => "(Von {{SERVER}})",
"gnunote" => "Diese Seite ist unter der <a class=internal href='$wgScriptPath/GNU_FDL'>GNU FDL</a> verfügbar.",
"protectedpage" => "Geschützte Seite",
"administrators" => "{$wgMetaNamespace}:Administratoren",
"sysoptitle"	=> "Sysop-Zugang notwendig",
"sysoptext"		=> "Dieser Vorgang kann aus Sicherheitsgründen nur von Benutzern mit \"Sysop\"-Status durchgeführt werden. Siehe auch $1.",
"developertitle" => "Entwickler-Zugang notwendig",
"developertext"	=> "Dieser Vorgang kann aus Sicherheitsgründen nur von Benutzern mit \"Entwickler\"-Status durchgeführt werden. Siehe auch $1.",
"bureaucrattitle"	=> "Bürokraten-Rechte notwendig",
"bureaucrattext"	=> "Dieser Vorgang kann nur von Benutzern mit \"Bürokrat\"-Status durchgeführt werden.",
"nbytes"		=> "$1 Byte",
"go"			=> "Los",
"ok"			=> "OK",
"sitetitle"		=> "{{SITENAME}}",
"sitesubtitle"	=> "Die freie Enzyklopädie",
"pagetitle"		=> "$1 - {{SITENAME}}",
"sitesubtitle"	=> "Die freie Wissensdatenbank",
"retrievedfrom" => "Von \"$1\"",
"newmessages" => "Sie haben $1.",
"newmessageslink" => "neue Nachrichten",
"editsection" => "bearbeiten",
"toc" => "Inhaltsverzeichnis",
"showtoc" => "Anzeigen",
"hidetoc" => "Verbergen",
"thisisdeleted" => "Ansehen oder wiederherstellen von $1?",
"restorelink" => "$1 gelöschte Bearbeitungsvorgänge",
"feedlinks" => "Feed:",

# Kurzworte für jeden Namespace, u.a. von MonoBook verwendet
'nstab-main' => 'Artikel',
'nstab-user' => 'Benutzerseite',
'nstab-media' => 'Media',
'nstab-special' => 'Spezial',
'nstab-image' => 'Bild',
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
"image_tip"=>"Bild-Verweis",
"media_sample"=>"Beispiel.mp3",
"media_tip"=>"Mediendatei-Verweis",
"sig_tip"=>"Ihre Signatur mit Zeitstempel",
"hr_tip"=>"Horizontale Linie (sparsam verwenden)",

# Main script and global functions
#
"nosuchaction"	=> "Diese Aktion gibt es nicht",
"nosuchactiontext" => "Diese Aktion wird von der MediaWiki-Software nicht unterstützt",
"nosuchspecialpage" => "Diese Spezialseite gibt es nicht",
"nospecialpagetext" => "Diese Spezialseite wird von der MediaWiki-Software nicht unterstützt",

# General errors
#
"error" => "Fehler",
"databaseerror" => "Fehler in der Datenbank",
"dberrortext"	=> "Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete:
<blockquote><tt>$1</tt></blockquote>
aus der Funktion \"<tt>$2</tt>\".
MySQL meldete den Fehler \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: \"$1\" aus der Funktion \"<tt>$2</tt>\".
MySQL meldete den Fehler: \"<tt>$3: $4</tt>\".\n",
"noconnect"		=> "Konnte keine Verbindung zur Datenbank auf $1 herstellen",
"nodb"			=> "Konnte Datenbank $1 nicht auswählen",
"cachederror" => "Das folgende ist eine Kopie aus dem Cache und möglicherweise nicht aktuell.",
"readonly"		=> "Datenbank ist gesperrt",
"enterlockreason" => "Bitte geben Sie einen Grund ein, warum die Datenbank
gesperrt werden soll und eine Abschätzung über die Dauer der Sperrung",
"readonlytext"	=> "Die {{SITENAME}}-Datenbank ist vorübergehend gesperrt, z.B. für Wartungsarbeiten. Bitte versuchen Sie es später noch einmal.\n",
"missingarticle" => "Der Text für den Artikel \"$1\" wurde nicht in der Datenbank gefunden. Das ist wahrscheinlich ein Fehler in der Software. Bitte melden Sie dies einem Administrator, und geben sie den Artikelnamen an.",
"internalerror" => "Interner Fehler",
"filecopyerror" => "Konnte Datei \"$1\" nicht nach \"$2\" kopieren.",
"filerenameerror" => "Konnte Datei \"$1\" nicht nach \"$2\" umbenennen.",
"filedeleteerror" => "Konnte Datei \"$1\" nicht löschen.",
"filenotfound"	=> "Konnte Datei \"$1\" nicht finden.",
"unexpected"	=> "Unerwarteter Wert: \"$1\"=\"$2\".",
"formerror"		=> "Fehler: Konnte Formular nicht verarbeiten",
"badarticleerror" => "Diese Aktion kann auf diesen Artikel nicht angewendet werden.",
"cannotdelete"	=> "Kann spezifizierte Seite oder Artikel nicht löschen. (Wurde möglicherweise schon von jemand anderem gelöscht.)",
"badtitle"		=> "Ungültiger Titel",
"badtitletext"	=> "Der Titel der angeforderten Seite war ungültig, leer, oder ein ungültiger Sprachlink von einem anderen Wiki.",
"perfdisabled" => "Diese Funktion wurde wegen Überlastung des Servers vorübergehend deaktiviert. Versuchen Sie es bitte zwischen 02:00 und 14:00 UTC noch einmal<br>(Aktuelle Serverzeit : ".date("H:i:s")." UTC).",
"perfdisabledsub" => "Hier ist eine gespeicherte Kopie von $1:",
"perfcached" => "Die folgenden Daten stammen aus dem Cache und sind möglicherweise nicht aktuell:",
"wrong_wfQuery_params" => "Falsche Parameter für wfQuery()<br />
Funktion: $1<br />
Query: $2
",
"viewsource" => "Quelltext betrachten",
"protectedtext" => "Diese Seite ist für das Bearbeiten gesperrt. Dafür kann es diverse Gründe geben; siehe [[{{ns:4}}:Geschützte Seiten]].

Sie können den Quelltext dieser Seite betrachten und kopieren:",
'seriousxhtmlerrors' => 'Tidy entdeckte schwere Fehler im XHTML-Markup.',


# Login and logout pages
#
"logouttitle"	=> "Benutzer-Abmeldung",
"logouttext"	=> "Sie sind nun abgemeldet.
Sie können {{SITENAME}} jetzt anonym weiterbenutzen, oder sich unter dem selben oder einem anderen Benutzernamen wieder anmelden.\n",

"welcomecreation" => "<h2>Willkommen, $1!</h2><p>Ihr Benutzerkonto wurde eingerichtet.
Vergessen Sie nicht, Ihre Einstellungen anzupassen.",

"loginpagetitle" => "Benutzer-Anmeldung",
"yourname"		=> "Ihr Benutzername",
"yourpassword"	=> "Ihr Passwort",
"yourpasswordagain" => "Passwort wiederholen",
"newusersonly"	=> " (nur für neue Mitglieder)",
"remembermypassword" => "Dauerhaftes einloggen",
"loginproblem"	=> "<b>Es gab ein Problem mit Ihrer Anmeldung.</b><br>Bitte versuchen Sie es nochmal!",
"alreadyloggedin" => "<font color=red><b>Benutzer $1, Sie sind bereits angemeldet!</b></font><br>\n",

"login"			=> "Anmelden",
"loginprompt"           => "Um sich bei {{SITENAME}} anmelden zu können, müssen Cookies aktiviert sein.",
"userlogin"		=> "Anmelden",
"logout"		=> "Abmelden",
"userlogout"	=> "Abmelden",
"notloggedin" => "Nicht angemeldet",
"createaccount"	=> "Neues Benutzerkonto anlegen",
"createaccountmail" => "über eMail",
"badretype"		=> "Die beiden Passwörter stimmen nicht überein.",
"userexists"	=> "Dieser Benutzername ist schon vergeben. Bitte wählen Sie einen anderen.",
"youremail"		=> "Ihre E-Mail",
"yournick"		=> "Ihr \"Spitzname\" (zum \"Unterschreiben\")",
"yourrealname"		=> "Ihr echter Name (keine Pflicht)",
"emailforlost"	=> "Falls Sie Ihr Passwort vergessen haben, kann Ihnen ein neues an Ihre E-Mail-Adresse gesendet werden.",
"loginerror"	=> "Fehler bei der Anmeldung",
"noname"		=> "Sie müssen einen Benutzernamen angeben.",
"loginsuccesstitle" => "Anmeldung erfolgreich",
"loginsuccess"	=> "Sie sind jetzt als \"$1\" bei {{SITENAME}} angemeldet.",
"nosuchuser"	=> "Der Benutzername \"$1\" existiert nicht.
Überprüfen Sie die Schreibweise, oder melden Sie sich als neuer Benutzer an.",
"wrongpassword"	=> "Das Passwort ist falsch. Bitte versuchen Sie es erneut.",
"mailmypassword" => "Ein neues Passwort schicken",
"passwordremindertitle" => "{{SITENAME}} Passwort",
"passwordremindertext" => "Jemand (IP-Adresse $1)
hat um ein neues Passwort für die Anmeldung bei {{SITENAME}} gebeten.
Das Passwort für Benutzer \"$2\" lautet nun \"$3\".
Sie sollten sich jetzt anmelden und Ihr Passwort ändern.",
"noemail"		=> "Benutzer \"$1\" hat keine E-Mail-Adresse angegeben.",
"passwordsent"	=> "Ein neues Passwort wurde an die E-Mail-Adresse von Benutzer \"$1\" gesendet.
Bitte melden Sie sich an, sobald Sie es erhalten.",
"loginend"		=> "&nbsp;",
"mailerror" => "Fehler beim Senden von Mail: $1",

# Edit pages
#
"summary"	=> "Zusammenfassung",
#"subject"       => "Betreff/Schlagzeile",
"subject"       => "Betreff",
"minoredit"	=> "Nur Kleinigkeiten wurden verändert.",
"watchthis"     => "Diesen Artikel beobachten",
"savearticle"	=> "Artikel speichern",
"preview"	=> "Vorschau",
"showpreview"	=> "Vorschau zeigen",
"blockedtitle"	=> "Benutzer ist blockiert",
"blockedtext"	=> "Ihr Benutzername oder Ihre IP-Adresse wurde von $1 blockiert.
Als Grund wurde angegeben:<br>$2<p>Bitte kontaktieren Sie den Administrator, um über die Blockierung zu sprechen.",
"whitelistedittitle" => "Zum Bearbeiten ist es erforderlich angemeldet zu sein",
"whitelistedittext" => "Sie müssen sich [[Spezial:Userlogin|hier anmelden]] um Artikel bearbeiten zu können.",
"whitelistreadtitle" => "Zum Lesen ist es erforderlich angemeldet zu sein",
"whitelistreadtext" => "Sie müssen sich [[Spezial:Userlogin|hier anmelden]] um Artikel lesen zu können.",
"whitelistacctitle" => "Sie sind nicht berechtigt einen Account zu erzeugen",
"whitelistacctext" => "Um in diesem Wiki Accounts anlegen zu dürfen müssen Sie sich [[Spezial:Userlogin|hier anmelden]] und die nötigen Berechtigungen haben.",
"loginreqtitle"	=> "Anmeldung erforderlich",
"loginreqtext"	=> "Sie müssen sich [[Spezial:Userlogin|anmelden]], um andere Seiten betrachten zu können.",
"accmailtitle" => "Passwort wurde verschickt.",
"accmailtext" => "Das Passwort von $1 wurde an $2 geschickt.",
"newarticle"	=> "(Neu)",
"newarticletext" => "Hier den Text des neuen Artikels eintragen.\nBitte nur in ganzen Sätzen schreiben und keine urheberrechtsgeschützten Texte anderer kopieren.",
"anontalkpagetext" => "---- ''Dies ist die Diskussions-Seite eines nicht angemeldeten Benutzers. Wir müssen hier die numerische [[IP-Adresse]] zur Identifizierung verwenden. Eine solche Adresse kann nacheinander von mehreren Benutzern verwendet werden. Wenn Sie ein anonymer Benutzer sind und denken, dass irrelevante Kommentare an Sie gerichtet wurden, [[Spezial:Userlogin|melden Sie sich bitte
 an]], um zukünftige Verwirrung zu vermeiden. ''",
"noarticletext" => "(Dieser Artikel enthält momentan keinen Text)",
'usercssjs' => "'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' Klick auf ''Neu laden''(oder ''Strg-R''), '''IE / Opera:''' ''Strg-F5'', '''Safari:''' ''Cmd-r'', '''Konqueror''' ''Strg-R''.",
'usercsspreview' => "== Vorschau ihres Benutzer-CSS. ==
'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' Klick auf ''Neu laden''(oder ''Strg-R''), '''IE / Opera:''' ''Strg-F5'', '''Safari:''' ''Cmd-r'', '''Konqueror''' ''Strg-R''.",
'userjspreview' => "== Vorschau Ihres Benutzer-Javascript. ==
'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' Klick auf ''Neu laden''(oder ''Strg-R''), '''IE / Opera:''' ''Strg-F5'', '''Safari:''' ''Cmd-r'', '''Konqueror''' ''Strg-R''.",
'usercssjsyoucanpreview' => "<strong>Tipp:</strong> Benutzen Sie den Vorschau-Button, um Ihr neues css/js vor dem Speichern zu testen.",
"updated"		=> "(Geändert)",
"note"			=> "<strong>Hinweis:</strong> ",
"previewnote"	=> "Dies ist nur eine Vorschau, der Artikel wurde noch nicht gespeichert!",
"previewconflict" => "Diese Vorschau gibt den Inhalt des oberen Textfeldes wieder; so wird der Artikel aussehen, wenn Sie jetzt speichern.",
"editing"		=> "Bearbeiten von $1",
"sectionedit" => " (Absatz)",
"commentedit" => " (Kommentar)",
"editconflict"	=> "Bearbeitungs-Konflikt: $1",
"explainconflict" => "Jemand anders hat diesen Artikel geändert, nachdem Sie angefangen haben, ihn zu bearbeiten.
Das obere Textfeld enthält den aktuellen Artikel.
Das untere Textfeld enthält Ihre Änderungen.
Bitte fügen Sie Ihre Änderungen in das obere Textfeld ein.
<b>Nur</b> der Inhalt des oberen Textfeldes wird gespeichert, wenn Sie auf \"Speichern\" klicken!\n<p>",
"yourtext"		=> "Ihr Text",
"storedversion" => "Gespeicherte Version",
"editingold"	=> "<strong>ACHTUNG: Sie bearbeiten eine alte Version dieses Artikels.
Wenn Sie speichern, werden alle neueren Versionen überschrieben.</strong>\n",
"yourdiff"		=> "Unterschiede",
"copyrightwarning" => "
<b>Bitte <font size='+1'>kopieren Sie keine Webseiten</font>, die nicht Ihre eigenen sind, benutzen Sie <font size='+1'>keine urheberrechtlich geschützten Werke</font> ohne Erlaubnis des Copyright-Inhabers!</b>
<p>Sie geben uns hiermit ihre Zusage, dass Sie den Text <strong>selbst verfasst</strong> haben, dass der Text Allgemeingut (<strong>public domain</strong>) ist, oder dass der <strong>Copyright-Inhaber</strong> seine <strong>Zustimmung</strong> gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weisen Sie bitte auf der 'Diskussion:'-Seite darauf hin.
<p><i>Bitte beachten Sie, dass alle {{SITENAME}}-Beiträge automatisch unter der \"GNU Freie Dokumentationslizenz\" stehen. Falls Sie nicht möchten, dass Ihre Arbeit hier von anderen verändert und verbreitet wird, dann drücken Sie nicht auf \"Speichern\".</i>",
"longpagewarning" => "WARNUNG: Diese Seite ist $1KB groß; einige Browser könnten Probleme haben, Seiten zu bearbeiten, die größer als 32KB sind.
Überlegen Sie bitte, ob eine Aufteilung der Seite in kleinere Abschnitte möglich ist.",
"readonlywarning" => "WARNUNG: Die Datenbank wurde während dem Ändern der
Seite für Wartungsarbeiten gesperrt, so dass Sie die Seite im Moment nicht
speichern können. Sichern Sie sich den Text und versuchen Sie die Änderungen
später einzuspielen.",
"protectedpagewarning" => "WARNUNG: Diese Seite wurde gesperrt, so dass sie nur
Benutzer mit Sysop-Rechten bearbeitet werden kann. Beachten Sie bitte die
<a href='$wgScriptPath/{$wgMetaNamespace}:Geschützte Seiten'>Regeln für geschützte Seiten</a>.",

# History pages
#
"revhistory"	=> "Frühere Versionen",
"nohistory"		=> "Es gibt keine früheren Versionen von diesem Artikel.",
"revnotfound"	=> "Keine früheren Versionen gefunden",
"revnotfoundtext" => "Die Version dieses Artikels, nach der Sie suchen, konnte nicht gefunden werden. Bitte überprüfen Sie die URL dieser Seite.\n",
"loadhist"		=> "Lade Liste mit früheren Versionen",
"currentrev"	=> "Aktuelle Version",
"revisionasof"	=> "Version vom $1",
'revisionasofwithlink'	=> 'Version vom $1; $2<br />$3 | $4',
'nextrevision'		=> '&larr;N�chstj�ngere Version',
'previousrevision'	=> 'N�chst�ltere Version&rarr;',
"cur"			=> "Aktuell",
"next"			=> "Nächste",
"last"			=> "Letzte",
"orig"			=> "Original",
"histlegend"	=> "Diff Auswahl: Die Boxen der gewünschten
Versionen markieren und 'Enter' drücken oder den Button unten klicken/alt-v.<br/>
Legende:
(Aktuell) = Unterschied zur aktuellen Version,
(Letzte) = Unterschied zur vorherigen Version,
M = Kleine Änderung",

# Diffs
#
"difference"	=> "(Unterschied zwischen Versionen)",
"loadingrev"	=> "lage Versionen zur Unterscheidung",
"lineno"		=> "Zeile $1:",
"editcurrent"	=> "Die aktuelle Version dieses Artikels bearbeiten",
'selectnewerversionfordiff' => 'Eine neuere Version zum Vergleich auswählen',
'selectolderversionfordiff' => 'Eine ältere Version zum Vergleich auswählen',
'compareselectedversions' => 'Gewählte Versionen vergleichen',

# Search results
#
"searchresults" => "Suchergebnisse",
"searchresulttext" => "Für mehr Information über {{SITENAME}}, siehe [[Project:Suche|{{SITENAME}} durchsuchen]].",
"searchquery"	=> "Für die Suchanfrage \"$1\"",
"badquery"		=> "Falsche Suchanfrage",
"badquerytext"	=> "Wir konnten Ihre Suchanfrage nicht verarbeiten.
Vermutlich haben Sie versucht, ein Wort zu suchen, das kürzer als zwei Buchstaben ist.
Dies funktioniert im Moment noch nicht.
Möglicherweise haben Sie auch die Anfrage falsch formuliert, z.B.
\"Lohn und und Steuern\".
Bitte versuchen Sie eine anders formulierte Anfrage.",
"matchtotals"	=> "Die Anfrage \"$1\" stimmt mit $2 Artikelüberschriften
und dem Text von $3 Artikeln überein.",
"nogomatch" => "Es existiert kein Artikel mit diesem Namen. Bitte versuchen
Sie die Volltextsuche oder legen Sie den Artikel <a href=\"$1\">neu</a> an. ",
"titlematches"	=> "Übereinstimmungen mit Überschriften",
"notitlematches" => "Keine Übereinstimmungen",
"textmatches"	=> "Übereinstimmungen mit Texten",
"notextmatches"	=> "Keine Übereinstimmungen",
"prevn"			=> "vorherige $1",
"nextn"			=> "nächste $1",
"viewprevnext"	=> "Zeige ($1) ($2) ($3).",
"showingresults" => "Hier sind <b>$1</b> Ergebnisse, beginnend mit #<b>$2</b>.",
"showingresultsnum" => "Hier sind <b>$3</b> Ergebnisse, beginnend mit #<b>$2</b>.",
"nonefound"		=> "<strong>Hinweis</strong>:
Erfolglose Suchanfragen werden häufig verursacht durch den Versuch, nach 'gewöhnlichen' Worten zu suchen; diese sind nicht indiziert.",
"powersearch" => "Suche",
"powersearchtext" => "
Suche in Namensräumen :<br>
$1<br>
$2 Zeige auch REDIRECTs &nbsp; Suche nach $3 $9",
"searchdisabled" => "<p>Entschuldigung! Die Volltextsuche wurde wegen Überlastung temporär deaktiviert. Derweil können Sie die folgende Google Suche verwenden, die allerdings nicht den aktuellen Stand wiederspiegelt.<p>

",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio name
=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer
}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(Haupt-)",

# Preferences page
#
"preferences"	=> "Einstellungen",
"prefsnologin" => "Nicht angemeldet",
"prefsnologintext"	=> "Sie müssen <a href=\"" .
  "{{localurle:Spezial:Userlogin}}\">angemeldet</a>
sein, um Ihre Einstellungen zu ändern.",
"prefslogintext" => "Sie sind angemeldet als \"$1\".
Ihre interne ID-Nummer ist $2.",
"prefsreset"	=> "Einstellungen wuden auf Standard zurückgesetzt.",
"qbsettings"	=> "Seitenleiste",
"changepassword" => "Passwort ändern",
"skin"			=> "Skin",
"math"			=> "TeX",
"dateformat" => "Datumsformat",
"math_failure"		=> "Parser-Fehler",
"math_unknown_error"	=> "Unbekannter Fehler",
"math_unknown_function"	=> "Unbekannte Funktion ",
"math_lexing_error"	=> "'Lexing'-Fehler",
"math_syntax_error"	=> "Syntaxfehler",
"saveprefs"		=> "Einstellungen speichern",
"resetprefs"	=> "Einstellungen zurücksetzen",
"oldpassword"	=> "Altes Passwort",
"newpassword"	=> "Neues Passwort",
"retypenew"		=> "Neues Passwort (nochmal)",
"textboxsize"	=> "Textfeld-Grösse",
"rows"			=> "Zeilen",
"columns"		=> "Spalten",
"searchresultshead" => "Suchergebnisse",
"resultsperpage" => "Treffer pro Seite",
"contextlines"	=> "Zeilen pro Treffer",
"contextchars"	=> "Zeichen pro Zeile",
"stubthreshold" => "Kurze Artikel markieren bis",
"recentchangescount" => "Anzahl \"Letzte Änderungen\"",
"savedprefs"	=> "Ihre Einstellungen wurden gespeichert.",
"timezonelegend" => "Zeitzone",
"timezonetext"	=> "Geben Sie die Anzahl der Stunden ein, die zwischen Ihrer Zeitzone und UTC liegen.",
"localtime"	=> "Ortszeit",
"timezoneoffset" => "Unterschied",
"servertime" => "Aktuelle Zeit auf dem Server",
"guesstimezone" => "Einfügen aus dem Browser",
"emailflag"		=> "Keine E-Mail von anderen Benutzern erhalten",
"defaultns"		=> "In diesen Namensräumen soll standardmäßig gesucht werden:",

# Recent changes
#
"changes" => "Änderungen",
"recentchanges" => "Letzte Änderungen",
"recentchangestext" => "
Diese Seite wird beim Laden automatisch aktualisiert. Angezeigt werden Seiten, die zuletzt bearbeitet wurden, sowie die Zeit und der Name des Autors.<br>
Falls Sie neu bei {{SITENAME}} sind, lesen Sie bitte die [[{$wgMetaNamespace}:Willkommen|Willkommensseite]] und [[{$wgMetaNamespace}:Erste Schritte|Erste Schritte]].<br>
Wenn Sie möchten, dass {{SITENAME}} zu einem Erfolg wird, dann fügen Sie bitte keine Texte hinzu, die dem [[{$wgMetaNamespace}:Urheberrechte beachten|Urheberrecht]] anderer unterliegen. Dies könnte dem Projekt sonst schweren Schaden zufügen.",
"rcloaderr"		=> "Lade Letzte Änderungen",
"rcnote"		=> "Hier sind die letzten <b>$1</b> Änderungen der letzten <b>$2</b> Tage. (<b>N</b> - Neuer Artikel; <b>M</b> - kleine Änderung)",
"rcnotefrom"	=> "Dies sind die Änderungen seit <b>$2</b> (bis zu <b>$1</b> gezeigt).",
"rclistfrom"	=> "Zeige neue Änderungen seit $1",
"rclinks"		=> "Zeige die letzten $1 Änderungen; zeige die letzten $2 Tage.",
"diff"			=> "Unterschied",
"hist"			=> "Versionen",
"hide"			=> "Ausblenden",
"show"			=> "Einblenden",
"tableform"		=> "Tabelle",
"listform"		=> "Liste",
"nchanges"		=> "$1 Änderungen",
"minoreditletter" => "M",
"newpageletter" => "N",


# Upload
#
"upload"		=> "Hochladen",
"uploadbtn"		=> "Dateien hochladen",
"uploadlink"		=> "Bilder hochladen",
"reupload"		=> "Erneut hochladen",
"reuploaddesc"	=> "Zurück zur Hochladen-Seite.",
"uploadnologin" => "Nicht angemeldet",
"uploadnologintext"	=> "Sie müssen <a href=\"" .
  "{{localurle:Spezial:Userlogin}}\">angemeldet sein</a>
um Dateien hochladen zu können.",
"uploadfile"	=> "Datei hochladen",
"uploaderror"	=> "Fehler beim Hochladen",
"uploadtext"	=> "
Um hochgeladene Bilder zu suchen und anzusehen,
gehen Sie zu der [[Spezial:Imagelist|Liste hochgeladener Bilder]].

Benutzen Sie das Formular, um neue Bilder hochzuladen und
sie in Artikeln zu verwenden.
In den meisten Browsern werden Sie ein \"Durchsuchen\"-Feld sehen,
das einen Standard-Dateidialog öffnet.
Suchen Sie sich eine Datei aus. Die Datei wird dann im Textfeld angezeigt.
Bestätigen Sie dann die Copyright-Vereinbarung.
Schließlich drücken Sie den \"Hochladen\"-Knopf.
Dies kann eine Weile dauern, besonders bei einer langsamen Internet-Verbindung.

Für Photos wird das JPEG-Format, für Zeichnungen und Symbole das PNG-Format bevorzugt.
Um ein Bild in einem Artikel zu verwenden, schreiben Sie an Stelle des Bildes
'''<nowiki>[[bild:datei.jpg]]</nowiki>''' oder
'''<nowiki>[[bild:datei.jpg|Beschreibung]]</nowiki>'''.

Bitte beachten Sie, dass, genau wie bei den Artikeln, andere Benutzer Ihre Dateien löschen oder verändern können.",
"uploadlog"		=> "Datei-Logbuch",
"uploadlogpage" => "Datei-Logbuch",
"uploadlogpagetext" => "Hier ist die Liste der letzten hochgeladenen Dateien.
Alle Zeiten sind UTC.
<ul>
</ul>
",
"uploadlogtext" => "Hochgeladene und gelöschte Dateien werden im $1 verzeichnet.",
"filename"		=> "Dateiname",
"filedesc"		=> "Beschreibung",
"filestatus" => "Copyright-Status",
"filesource" => "Quelle",
"affirmation"	=> "Hiermit bestätige ich, dass ich das Copyright dieser Datei habe, und diese hiermit unter $1 veröffentliche, bzw. dass die Datei 'Public Domain' ist.",
"copyrightpage" => "{$wgMetaNamespace}:Copyright",
"copyrightpagename" => "{{SITENAME}} copyright",
"uploadedfiles"	=> "Hochgeladene Dateien",
"noaffirmation" => "Sie müssen bestätigen, dass das Hochladen der Datei keine Copyright-Verletzung darstellt.",
"ignorewarning"	=> "Warnung ignorieren und Datei trotzdem speichern.",
"minlength"		=> "Bilddateien müssen mindestens drei Buchstaben haben.",
"badfilename"	=> "Der Bildname wurde in \"$1\" geändert.",
"badfiletype"	=> "\".$1\" ist kein empfohlenes Dateiformat.",
"largefile"		=> "Bitte keine Bilder über 100 KByte hochladen.",
'emptyfile'		=> "Die hochgeladene Datei ist leer. Der Grund kann ein Tippfehler im Dateinamen sein. Bitte kontrollieren Sie, ob Sie die Datei wirklich hochladen wollen.",
"successfulupload" => "Erfolgreich hochgeladen",
"fileuploaded"	=> "Die Datei \"$1\" wurde erfolgreich hochgeladen. Bitte
verwenden Sie diesen ($2) Link zur Beschreibungsseite und füllen Sie die
Informationen über die Datei aus, insbesondere seine Herkunft, von wem und wann es
gemacht wurde und besondere Angaben zum Copyright, falls notwendig.",
"uploadwarning" => "Warnung",
"savefile"		=> "Datei speichern",
"uploadedimage" => "\"$1\" hochgeladen",

# Image list
#
"imagelist"		=> "Bilderliste",
"imagelisttext"	=> "Hier ist eine Liste von $1 Bildern, sortiert $2.",
"getimagelist"	=> "Lade Bilderliste",
"ilshowmatch"	=> "Zeige alle Bilder mit Namen",
"ilsubmit"		=> "Suche",
"showlast"		=> "Zeige die letzten $1 Bilder, sortiert nach $2.",
"all"			=> "alle",
"byname"		=> "nach Name",
"bydate"		=> "nach Datum",
"bysize"		=> "nach Grösse",
"imgdelete"		=> "Löschen",
"imgdesc"		=> "Beschreibung",
"imglegend"		=> "Legende: (Beschreibung) = Zeige/Bearbeite Bildbeschreibung.",
"imghistory"	=> "Bild-Versionen",
"revertimg"		=> "Zurücksetzen",
"deleteimg"		=> "Löschen",
"deleteimgcompletely"		=> "Löschen",
"imghistlegend" => "Legende: (cur) = Dies ist das aktuelle Bild, (Löschen) = lösche
diese alte Version, (Zurücksetzen) = verwende wieder diese alte Version.",
"imagelinks"	=> "Bildverweise",
"linkstoimage"	=> "Die folgenden Artikel benutzen dieses Bild:",
"nolinkstoimage" => "Kein Artikel benutzt dieses Bild.",

# Statistics
#
"statistics"	=> "Statistik",
"sitestats"		=> "Seitenstatistik",
"userstats"		=> "Benutzerstatistik",
"sitestatstext" => "Es gibt insgesamt <b>$1</b> Seiten in der Datenbank.
Das schliesst \"Diskussion\"-Seiten, Seiten über {{SITENAME}}, extrem kurze Artikel, Weiterleitungen und andere Seiten ein, die nicht als Artikel gelten können.
Diese ausgenommen, gibt es <b>$2</b> Seiten, die als Artikel gelten können.<p>
Es wurden insgesamt <b>$3</b>&times; Seiten aufgerufen, und <b>$4</b>&times; Seiten bearbeitet.
Daraus ergeben sich <b>$5</b> Bearbeitungen pro Seite, und <b>$6</b> Betrachtungen pro Bearbeitung.",
"userstatstext" => "Es gibt <b>$1</b> registrierte Benutzer.
Davon haben <b>$2</b> Administrator-Rechte (siehe $3).",

# Maintenance Page
#
"maintenance"		=> "Wartungsseite",
"maintnancepagetext"	=> "Diese Seite enthält mehrere praktische Funktionen zur täglichen Wartung von {{SITENAME}}. Einige dieser Funktionen können die Datenbank stark beanspruchen, also bitte nicht nach jeder Änderung neu laden ;-)",
"maintenancebacklink"	=> "Zurück zur Wartungsseite",
"disambiguations"	=> "Begriffsklärungsseiten",
"disambiguationspage"	=> "{$wgMetaNamespace}:Begriffsklärung",
"disambiguationstext"	=> "Die folgenden Artikel verweisen auf eine <i>Seite zur Begriffsklärung</i>. Sie sollten statt dessen auf die eigentlich gemeinte Seite verweisen.<br>Eine Seite wird als Begriffsklärungsseite behandelt, wenn $1 auf sie verweist.<br>Verweise aus Namensräumen werden hier <i>nicht</i> aufgelistet.",
"doubleredirects"	=> "Doppelte Redirects",
"doubleredirectstext"	=> "<b>Achtung:</b> Diese Liste kann \"falsche Positive\" enthalten. Das ist dann der Fall, wenn ein Redirect außer dem Redirect-Verweis noch weiteren Text mit anderen Verweisen enthält. Letztere sollten dann entfernt werden.",
"brokenredirects"	=> "Kaputte Redirects",
"brokenredirectstext"	=> "Die folgenden Redirects leiten zu einem nicht existierenden Artikel weiter",
"selflinks"		=> "Seiten, die auf sich selbst verweisen",
"selflinkstext"		=> "Die folgenden Artikel verweisen auf sich selbst, was sie nicht sollten.",
"mispeelings"           => "Seiten mit falsch geschriebenen Worten",
"mispeelingstext"       => "Die folgenden Seiten enthalten falsch geschriebene Worte, wie sie auf $1 definiert sind. In Klammern angegebene Worte geben die korrekte Schreibweise wieder.<p><strong>Zitate, Buchtitel u.ä. bitte im Originalzustand belassen, also ggf. in alter Rechtschreibung und mit Rechtschreibfehlern!</strong>",
"mispeelingspage"       => "Liste von Tippfehlern",
"missinglanguagelinks"  => "Fehlende Sprachverweise",
"missinglanguagelinksbutton"    => "Zeige fehlende Sprachverweise nach",
"missinglanguagelinkstext"      => "Diese Artikel haben <i>keinen</i> Verweis zu ihrem Gegenstück in $1. Redirects und Unterseiten werden <i>nicht</i> angezeigt.",


# Miscellaneous special pages
#
"orphans"		=> "Verwaiste Seiten",
"lonelypages"	=> "Verwaiste Seiten",
"unusedimages"	=> "Verwaiste Bilder",
"popularpages"	=> "Beliebte Seiten",
"nviews"		=> "$1 Abfragen",
"wantedpages"	=> "Gewünschte Seiten",
"nlinks"		=> "$1 Verweise",
"allpages"		=> "Alle Artikel",
"randompage"	=> "Zufälliger Artikel",
"shortpages"	=> "Kurze Artikel",
"longpages"		=> "Lange Artikel",
"listusers"		=> "Benutzerverzeichnis",
"specialpages"	=> "Spezialseiten",
"spheading"		=> "Spezialseiten",
"sysopspheading" => "Spezialseiten für Sysops",
"developerspheading" => "Spezialseiten für Entwickler",
"protectpage"	=> "Artikel schützen",
"recentchangeslinked" => "Verlinkte Seiten",
"rclsub"		=> "(auf Artikel von \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Neue Artikel",
"ancientpages" => "Älteste Artikel",
"movethispage"	=> "Artikel verschieben",
"unusedimagestext" => "<p>Bitte beachten Sie, dass andere Wikis möglicherweise einige dieser Bilder benutzen.",
"booksources"	=> "Buchhandlungen",
"booksourcetext" => "Dies ist eine Liste mit Links zu Internetseiten, die neue und gebrauchte Bücher verkaufen. Dort kann es auch weitere Informationen über die Bücher geben, die Sie interessieren. {{SITENAME}} ist mit keinem dieser Anbieter geschäftlich verbunden.",
"alphaindexline" => "$1 bis $2",

# Email this user
#
"mailnologin"	=> "Sie sind nicht angemeldet.",
"mailnologintext" => "Sie müssen <a href=\"" .
  "{{localurle:Spezial:Userlogin}}\">angemeldet sein</a>
und eine gültige E-Mail-Adresse haben, um anderen Benutzern E-Mail zu schicken.",
"emailuser"		=> "E-Mail an diesen Benutzer",
"emailpage"		=> "E-Mail an Benutzer",
"emailpagetext"	=> "Wenn dieser Benutzer eine gültige E-Mail-Adresse angegeben hat, können Sie ihm mit dem untenstehenden Formular eine E-Mail senden. Als Absender wird die E-Mail-Adresse aus Ihren Einstellungen eingetragen, damit der Benutzer Ihnen antworten kann.",
"usermailererror" => "Das Mail-Objekt gab einen Fehler zurück: ",
"defemailsubject"  => "{{SITENAME}} e-mail",
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
"watchlistsub"	=> "(für Benutzer \"$1\")",
"nowatchlist"	=> "Sie haben keine Einträge auf Ihrer Beobachtungsliste.",
"watchnologin"	=> "Sie sind nicht angemeldet",
"watchnologintext"	=> "Sie müssen <a href=\"" .
  "{{localurle:Spezial:Userlogin}}\">angemeldet</a>
sein, um Ihre Beobachtungsliste zu bearbeiten.",
"addedwatch"	=> "Zur Beobachtungsliste hinzugefügt",
"addedwatchtext" => "Der Artikel \"$1\" wurde zu Ihrer <a href=\"" .
"{{localurle:Spezial:Watchlist}}\">Beobachtungsliste</a> hinzugefügt.
Spätere Änderungen an diesem Artikel und der zugehörigen Diskussions-Seite
werden dort gelistet und der Artikel wird in der <a href=\""
"{{localurle:Spezial:Recentchanges}}\">Liste der letzten Änderungen</a>
<b>fett</b> angezeigt. <p>Wenn Sie den Artikel wieder von ihrer
Beobachtungsliste entfernen wollen, klicken Sie auf \"Nicht mehr beobachten\"
am Ende des Artikels.",
"removedwatch"	=> "Von der Beobachtungsliste entfernt",
"removedwatchtext" => "Der Artikel \"$1\" wurde von Ihrer Beobachtungsliste entfernt.",
"watchthispage"	=> "Seite beobachten",
"unwatchthispage" => "Nicht mehr beobachten",
"notanarticle"	=> "Kein Artikel",
"watchnochange" => "Keine Ihrer beobachteten Artikel wurde während des angezeigten Zeitraums bearbeitet.",
"watchdetails" => "($1 Artikel werden beobachtet (ohne Diskussionsseiten);
$2 Artikel im eingestellten Zeitraum bearbeitet;
$3... <a href='$4'>komplette Liste zeigen und bearbeiten</a>.)",
"watchmethod-recent" => "überprüfen der letzten Bearbeitungen für die Beobachtungsliste",
"watchmethod-list" => "überprüfen der Beobachtungsliste nach letzten Bearbeitungen",
"removechecked" => "Markierte Einträge löschen",
"watchlistcontains" => "Ihre Beobachtungsliste enthält $1 Seiten.",
"watcheditlist" => "Hier ist eine alphabetische Liste der von Ihnen beobachteten Seiten. Markieren Sie die Seiten die Sie von der Beobachtungsliste löschen wollen und betätigen Sie den 'markierte Einträge löschen' Knopf am Ende der Seite.",
"removingchecked" => "Wunschgemäß werden die Einträge aus der Beobachtungsliste entfernt...",
"couldntremove" => "Der Eintrag '$1' kann nicht gelöscht werden...",
"iteminvalidname" => "Ploblem mit dem Eintrag '$1', ungültiger Name...",
"wlnote" => "Es folgen die letzten $1 Änderungen der letzten <b>$2</b> Stunden.",
"wlshowlast" => "Zeige die letzen $1 Stunden $2 Tage $3",
"wlsaved"			=> "Dies ist eine gespeicherte Version Ihrer Beobachtungsliste.",

# Delete/protect/revert
#
"deletepage"	=> "Seite löschen",
"confirm"		=> "Bestätigen",
"excontent" => "Alter Inhalt:",
"exbeforeblank" => "Inhalt vor dem Leeren der Seite:",
"exblank" => "Seite war leer",
"confirmdelete" => "Löschung bestätigen",
"deletesub"		=> "(Lösche \"$1\")",
"historywarning" => "WARNUNG: Die Seite die Sie zu löschen gedenken hat
eine Versionsgeschichte: ",
"confirmdeletetext" => "Sie sind dabei, einen Artikel oder ein Bild und alle älteren Versionen permanent aus der Datenbank zu löschen.
Bitte bestätigen Sie Ihre Absicht, dies zu tun, dass Sie sich der Konsequenzen bewusst sind, und dass Sie in Übereinstimmung mit unseren [[{$wgMetaNamespace}:Leitlinien|Leitlinien]] handeln.",
"confirmcheck"	=> "Ja, ich möchte den Löschvorgang fortsetzen.",
"actioncomplete" => "Aktion beendet",
"deletedtext"	=> "\"$1\" wurde gelöscht.
Im $2 finden Sie eine Liste der letzten Löschungen.",
"deletedarticle" => "\"$1\" gelöscht",
"dellogpage"	=> "Lösch-Logbuch",
"dellogpagetext" => "Hier ist eine Liste der letzten Löschungen (UTC).
<ul>
</ul>
",
"deletionlog"	=> "Lösch-Logbuch",
"reverted"		=> "Auf eine alte Version zurückgesetzt",
"deletecomment"	=> "Grund der Löschung",
"imagereverted" => "Auf eine alte Version zurückgesetzt.",
"rollback" => "Zurücknahme der Änderungen",
'rollback_short' => 'Rollback',
"rollbacklink" => "Rollback",
"rollbackfailed" => "Zurücknahme gescheitert",
"cantrollback" => "Die Änderung kann nicht zurückgenommen werden; der
letzte Autor ist der einzige.",
"alreadyrolled" => "Die Zurücknahme des Artikels [[$1]] von [[Benutzer:$2|$2]]
([[Benutzer Diskussion:$2|Diskussion]]) ist nicht möglich, da eine andere
Änderung oder Rücknahme erfolgt ist.

Die letzte Änderung ist von [[Benutzer:$3|$3]]
([[Benutzer Diskussion:$3|Diskussion]])",
#   only shown if there is an edit comment
"editcomment" => "Der Änderungskommentar war: \"<i>$1</i>\".",
"revertpage" => "Wiederhergestellt zur letzten Änderung von $1",

# Undelete
"undelete" => "Gelöschte Seite wiederherstellen",
"undeletepage" => "Gelöschte Seiten wiederherstellen",
"undeletepagetext" => "Die folgenden Seiten wurden gelöscht, sind aber immer noch
gespeichert und können wiederhergestellt werden.",
"undeletearticle" => "Gelöschten Artikel wiederherstellen",
"undeleterevisions" => "$1 Versionen archiviert",
"undeletehistory" => "Wenn Sie diese Seite wiederherstellen, werden auch alle alten
Versionen wiederhergestellt. Wenn seit der Löschung ein neuer Artikel gleichen
Namens erstellt wurde, werden die wiederhergestellten Versionen als alte Versionen
dieses Artikels erscheinen.",
"undeleterevision" => "Gelöschte Version vom $1",
"undeletebtn" => "Wiederherstellen!",
"undeletedarticle" => "\"$1\" wiederhergestellt",
"undeletedtext"   => "Der Artikel [[$1]] wurde erfolgreich wiederhergestellt.",

# Contributions
#
"contributions"	=> "Benutzerbeiträge",
"mycontris" => "Meine Beiträge",
"contribsub"	=> "Für $1",
"nocontribs"	=> "Es wurden keine Änderungen für diese Kriterien gefunden.",
"ucnote"		=> "Dies sind die letzten <b>$1</b> Beiträge des Benutzers in den letzten <b>$2</b> Tagen.",
"uclinks"		=> "Zeige die letzten $1 Beiträge; zeige die letzten $2 Tage.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Was zeigt hierhin",
"notargettitle" => "Kein Artikel angegeben",
"notargettext"	=> "Sie haben nicht angegeben, auf welche Seite Sie diese Funktion anwenden wollen.",
"linklistsub"	=> "(Liste der Verweise)",
"linkshere"		=> "Die folgenden Artikel verweisen hierhin:",
"nolinkshere"	=> "Kein Artikel verweist hierhin.",
"isredirect"	=> "Weiterleitungs-Seite",

# Block/unblock IP
#
"blockip"		=> "IP-Adresse blockieren",
"blockiptext"	=> "Benutzen Sie das Formular, um eine IP-Adresse zu blockieren.
Dies sollte nur erfolgen, um Vandalismus zu verhindern, in Übereinstimmung mit unseren [[{$wgMetaNamespace}:Leitlinien|Leitlinien]].
Bitte tragen Sie den Grund für die Blockade ein.",
"ipaddress"		=> "IP-Adresse",
"ipbreason"		=> "Grund",
"ipbsubmit"		=> "Adresse blockieren",
"badipaddress"	=> "Die IP-Adresse hat ein falsches Format.",
"noblockreason" => "Sie müssen einen Grund für die Blockade angeben.",
"blockipsuccesssub" => "Blockade erfolgreich",
"blockipsuccesstext" => "Die IP-Adresse \"$1\" wurde blockiert.
<br>Auf [[Spezial:Ipblocklist|IP block list]] ist eine Liste der Blockaden.",
"unblockip"		=> "IP-Adresse freigeben",
"unblockiptext"	=> "Benutzen Sie das Formular, um eine blockierte IP-Adresse freizugeben.",
"ipusubmit"		=> "Diese Adresse freigeben",
"ipusuccess"	=> "IP-Adresse \"$1\" wurde freigegeben",
"ipblocklist"	=> "Liste blockierter IP-Adressen",
"blocklistline"	=> "$1, $2 blockierte $3",
"blocklink"		=> "blockieren",
"unblocklink"	=> "freigeben",
"contribslink"	=> "Beiträge",
"autoblocker" => "Automatische Blockierung, da Sie eine IP-Adresse benutzen mit \"$1\". Grund: \"$2\".",

# Developer tools
#
"lockdb"		=> "Datenbank sperren",
"unlockdb"		=> "Datenbank freigeben",
"lockdbtext"	=> "Mit dem Sperren der Datenbank werden alle Änderungen an Benutzereinstellungen, watchlisten, Artikeln usw. verhindert. Bitte bestätigen Sie Ihre Absicht, die Datenbank zu sperren.",
"unlockdbtext"	=> "Das Aufheben der Datenbank-Sperre wird alle Änderungen wieder zulassen. Bitte bestätigen Sie Ihre Absicht, die Sperrung aufzuheben.",
"lockconfirm"	=> "Ja, ich möchte die Datenbank sperren.",
"unlockconfirm"	=> "Ja, ich möchte die Datenbank freigeben.",
"lockbtn"		=> "Datenbank sperren",
"unlockbtn"		=> "Datenbank freigeben",
"locknoconfirm" => "Sie haben das Bestätigungsfeld nicht markiert.",
"lockdbsuccesssub" => "Datenbank wurde erfolgreich gesperrt",
"unlockdbsuccesssub" => "Datenbank wurde erfolgreich freigegeben",
"lockdbsuccesstext" => "Die {{SITENAME}}-Datenbank wurde gesperrt.
<br>Bitte geben Sie die Datenbank wieder frei, sobald die Wartung abgeschlossen ist.",
"unlockdbsuccesstext" => "Die {{SITENAME}}-Datenbank wurde freigegeben.",

# SQL query
#
"asksql"		=> "SQL-Abfrage",
"asksqltext"	=> "Benutzen Sie das Formular für eine direkte
Datenbank-Abfrage. Benutze einzelne Hochkommata ('so'), um Text zu begrenzen.
Bitte diese Funktion vorsichtig benutzen! Das abschließende ';' wird
automatisch ergänzt.",
"sqlislogged" => "Bitte beachten Sie das alle SQL-Abfrage mitprotokolliert
werden.",
"sqlquery"		=> "Abfrage eingeben",
"querybtn"		=> "Abfrage starten",
"selectonly"	=> "Andere Abfragen als \"SELECT\" können nur von Entwicklern benutzt werden.",
"querysuccessful" => "Abfrage erfolgreich",

# Move page
#
"movepage"		=> "Artikel verschieben",
"movepagetext"	=> "Mit diesem Formular können Sie einen Artikel umbenennen, mitsamt allen Versionen. Der alte Titel wird zum neuen weiterleiten. Verweise auf den alten Titel werden nicht geändert, und die Diskussionsseite wird auch nicht mitverschoben.",
"movepagetalktext" => "Die dazugehörige Diskussionsseite wird, sofern vorhanden, mitverschoben, '''es sei denn:'''
*Sie verschieben die Seite in einen anderen Namensraum, oder
*Es existiert bereits eine Diskussionsseite mit diesem Namen, oder
*Sie wählen die untenstehende Option ab

In diesen Fällen müssen Sie die Seite, falls gewünscht, von Hand verschieben.",
"movearticle"	=> "Artikel verschieben",
"movenologin"   => "Sie sind nicht angemeldet",
"movenologintext" => "Sie müssen ein registrierter Benutzer und
<a href=\"{{localurle:Special:Userlogin}}\">angemeldet</a> sein,
um eine Seite zu verschieben.",
"newtitle"		=> "Zu neuem Titel",
"movepagebtn"	=> "Artikel verschieben",
"pagemovedsub"	=> "Verschiebung erfolgreich",
"pagemovedtext" => "Artikel \"[[$1]]\" wurde nach \"[[$2]]\" verschoben.",
"articleexists" => "Unter diesem Namen existiert bereits ein Artikel.
Bitte wählen Sie einen anderen Namen.",
"talkexists"    => "Die Seite selbst wurde erfolgreich verschoben, aber die
Diskussions-Seite nicht, da schon eine mit dem neuen Titel existiert. Bitte gleichen Sie die Inhalte von Hand ab.",
"movedto"		=> "verschoben nach",
"movetalk"		=> "Die \"Diskussions\"-Seite mitverschieben, wenn möglich.",
"talkpagemoved" => "Die \"Diskussions\"-Seite wurde ebenfalls verschoben.",
"talkpagenotmoved" => "Die \"Diskussions\"-Seite wurde <strong>nicht</strong> verschoben.",

"export"        => "Seiten exportieren",
"exporttext"    => "Sie können den Text und die Bearbeitungshistorie einer bestimmten oder einer Auswahl von Seiten nach XML exportieren. Das Ergebnis kann in ein anderes Wiki mit WikiMedia Software eingespielt werden, bearbeitet oder archiviert werden.",
"exportcuronly" => "Nur die aktuelle Version der Seite exportieren",
"missingimage"          => "<b>Fehlendes Bild</b><br><i>$1</i>\n",

#Tooltips:
'tooltip-atom'	=> 'Atom-Feed von dieser Seite',
'tooltip-addsection' => 'Einen Kommentar zu dieser Seite hinzufügen. [alt-+]',
'tooltip-article' => 'Artikel betrachten [alt-a]',
'tooltip-talk' => 'Diesen Artikel diskutieren [alt-t]',
'tooltip-edit' => 'Sie können diesen Artikel bearbeiten. Benutzen Sie die Vorschau, bevor Sie die Seite speichern. [alt-e]',
'tooltip-viewsource' => 'Diese Seite ist geschützt. Sie können ihren Quelltext betrachten. [alt-e]',
'tooltip-history' => 'Ältere Versionen dieser Seite. [alt-h]',
'tooltip-protect' => 'Diese Seite schützen [alt--]',
'tooltip-delete' => 'Diese Seite löschen [alt-d]',
'tooltip-undelete' => "$1 Versionen diese Artikels wieder herstellen. [alt-d]",
'tooltip-move' => 'Diese Seite verschieben. [alt-m]',
'tooltip-nomove' => 'Sie können diese Seite nicht verschieben',
'tooltip-watch' => 'Diese Seite beobachten. [alt-w]',
'tooltip-unwatch' => 'Diese Seite nicht mehr beobachten. [alt-w]',
'tooltip-watchlist' => 'Die Liste der Artikel, die Sie auf Änderungen beobachten. [alt-l]',
'tooltip-userpage' => 'Meine Benutzerseite  [alt-.]',
'tooltip-anonuserpage' => 'Die Benutzerseite Ihrer IP-Adresse [alt-.]',
'tooltip-mytalk' => 'Meine Benutzerdiskussion  [alt-n]',
'tooltip-anontalk' => 'Diskussionen zu Bearbeitungen, die von dieser IP-Adresse gemacht wurden. [alt-n]',
'tooltip-preferences' => 'Meine Einstellungen',
'tooltip-mycontris' => 'Liste meiner Beiträge [alt-y]',
'tooltip-login' => 'Sie können sich gerne anmelden, es ist aber nicht notwendig, um Artikel zu bearbeiten. [alt-o]',
'tooltip-logout' => 'The start button [alt-o]',
'tooltip-search' => 'Suchen [alt-f]',
'tooltip-mainpage' => 'Zur Hauptseite [alt-z]',
'tooltip-portal' => 'Über das Projekt, was Sie tun können, wo Sie Dinge finden können',
'tooltip-randompage' => 'Zufälliger Artikel [alt-x]',
'tooltip-currentevents' => 'Hintergründe zu aktuellen Ereignissen finden',
'tooltip-sitesupport' => 'Unterstützen Sie {{SITENAME}}',
'tooltip-help' => 'Hier bekommen Sie Hilfe.',
'tooltip-recentchanges' => 'Die letzten Änderungen in diesem Wiki. [alt-r]',
'tooltip-recentchangeslinked' => 'Die letzten Änderungen an Seiten, die von dieser Seite verlinkt wurden. [alt-c]',
'tooltip-whatlinkshere' => 'Liste aller Seiten, die auf diese verweisen [alt-b]',
'tooltip-specialpages' => 'Liste aller Spezialseiten [alt-q]',
'tooltip-upload' => 'Bilder oder andere Medien hochladen [alt-u]',
'tooltip-specialpage' => 'Dies ist eine Spezialseite, die nicht bearbeitet werden kann.',
'tooltip-minoredit' => 'Diese Änderung als klein markieren. [alt-i]',
'tooltip-save' => 'Änderungen speichern [alt-s]',
'tooltip-preview' => 'Vorschau der Änderungen an dieser Seite. Benutzen Sie dies vor dem Speichern! [alt-p]',
'tooltip-contributions' => 'Liste der beiträge dieses Benutzers.',
'tooltip-emailuser' => 'Senden Sie eine Mail an diesen Benutzer',
'tooltip-rss' => 'RSS-Feed von dieser Seite.',
'tooltip-compareselectedversions' => 'Unterschiede zwischen zwei ausgewählten Versionen dieser Seite vergleichen. [alt-v]',

#Tastatur-Shortcuts
'accesskey-article' => 'a',
'accesskey-addsection' => '+',
'accesskey-talk' => 't',
'accesskey-edit' => 'e',
'accesskey-viewsource' => 'e',
'accesskey-history' => 'h',
'accesskey-protect' => '-',
'accesskey-delete' => 'd',
'accesskey-undelete' => 'd',
'accesskey-move' => 'm',
'accesskey-watch' => 'w',
'accesskey-unwatch' => 'w',
'accesskey-watchlist' => 'l',
'accesskey-userpage' => '.',
'accesskey-anonuserpage' => '.',
'accesskey-mytalk' => 'n',
'accesskey-anontalk' => 'n',
'accesskey-preferences' => '',
'accesskey-mycontris' => 'y',
'accesskey-login' => 'o',
'accesskey-logout' => 'o',
'accesskey-search' => 'f',
'accesskey-mainpage' => 'z',
'accesskey-portal' => '',
'accesskey-randompage' => 'x',
'accesskey-currentevents' => '',
'accesskey-sitesupport' => '',
'accesskey-help' => '',
'accesskey-recentchanges' => 'r',
'accesskey-recentchangeslinked' => 'c',
'accesskey-whatlinkshere' => 'b',
'accesskey-specialpages' => 'q',
'accesskey-specialpage' => '',
'accesskey-upload' => 'u',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-contributions' => '',
'accesskey-emailuser' => '',
'accesskey-compareselectedversions' => 'v',

"makesysoptitle"        => "Mache einen Benutzer zum Administrator",
"makesysoptext"         => "Diese Maske wird von Bürokraten benutzt, um normale Benutzer zu Administratoren zu machen.",
"makesysopname"         => "Name des Benutzers:",
"makesysopsubmit"       => "Mache diesen Benutzer zu einem Administrator",
"makesysopok"           => "<b>Benutzer \"$1\" ist nun ein Administrator.</b>",
"makesysopfail"         => "<b>Benutzer \"$1\" konnte nicht zu einem Administrator gemacht werden. (Haben Sie den Namen richtig geschrieben?)</b>",
"makesysop"         => "Mache einen Benutzer zum Administrator",
"bureaucratlogentry"	=> "Rechte für Benutzer \"$1\" auf \"$2\" gesetzt",
"rights"		=> "Rechte:",
"set_user_rights"	=> "Benutzerrechte setzen",
"user_rights_set"	=> "<b>Benutzerrechte für \"$1\" aktualisiert</b>",
"set_rights_fail"	=> "<b>Benutzerrechte für \"$1\" konnten nicht gesetzt werden. (Haben Sie den Namen korrekt eingegeben?)</b>",
"1movedto2"		=> "$1 wurde nach $2 verschoben",
"allmessages"		=> "Alle MediaWiki-Meldungen",
"allmessagestext"	=> "Dies ist eine Liste aller möglichen Meldungen im MediaWiki-Namensraum.",
"thumbnail-more"	=> "vergrößern",
"and"			=> "und",
"rchide"		=> "in $4 form; $1 kleine Änderungen; $2 sekundäre Namensräume; $3 mehrfache Änderungen.",
"showhideminor"		=> "kleine Änderungen $1",
"rcliu"			=> "$1 Änderungen durch eingeloggte Benutzer",
"uploaddisabled"	=> "Entschuldigung, das Hochladen ist deaktiviert.",
"deadendpages"		=> "Sackgassenartikel",
"intl"			=> "InterWikiLinks",
"version"		=> "Version",
"protectlogpage"	=> "Seitenschutz-Logbuch",
"protectlogtext"	=> "Dies ist eine Liste der blockierten Seiten. Siehe [[Wikipedia:Geschützte Seiten]] für mehr Informationen.",
"protectedarticle" => "Artikel [[$1]] geschützt",
"unprotectedarticle" => "Artikel [[$1]] freigegeben",
"protectsub" =>"(Sperren von \"$1\")",
"confirmprotecttext" => "Soll diese Seite wirklich geschützt werden?",
"ipbexpiry"		=> "Ablaufzeit",
"blocklogpage"		=> "Benutzerblockaden-Logbuch",
"blocklogentry"		=> "blockiert [[Benutzer:$1]] - ([[Spezial:Contributions/$1|Beiträge]]) für einen Zeitraum von: $2",
"blocklogtext"		=> "Dies ist ein Log über Sperrungen und Entsperrungen von Benutzern. Automatisch geblockte IP-Adressen werden nicht erfasst. Siehe [[Special:Ipblocklist|IP block list]] für eine Liste der gesperrten Benutzern.",
"unblocklogentry"	=> "Blockade von [[Benutzer:$1]] aufgehoben",
"range_block_disabled"	=> "Die Möglichkeit, ganze Adressräume zu sperren, ist nicht aktiviert.",
"ipb_expiry_invalid"	=> "Die angegebeben Ablaufzeit ist ungültig.",
"ip_range_invalid"	=> "Ungültiger IP-Addressbereich.",
"confirmprotect" 	=> "Sperrung bestätigen",
"protectcomment" 	=> "Grund der Sperrung",
"unprotectsub"		=> "(Aufhebung der Sperrung von \"$1\")",
"confirmunprotecttext"	=> "Wollen Sie wirklich die Sperrung dieser Seite aufheben?",
"confirmunprotect"	=> "Aufhebung der Sperrung bestätigen",
"unprotectcomment"	=> "Grund für das Aufheben der Sperrung",
"protectreason"		=> "(Bitte Grund der Sperrung angeben)",
"proxyblocker"  	=> "Proxyblocker",
"proxyblockreason"      => "Ihre IP-Adresse wurde gesperrt, da sie ein offener Proxy ist. Bitte kontaktieren Sie Ihren Provider oder Ihre Systemtechnik und informieren Sie sie über dieses mögliche Sicherheitsproblem.",
"proxyblocksuccess"     => "Fertig.\n",
"math_image_error"	=> "die PNG-Konvertierung schlug fehl.",
"math_bad_tmpdir"	=> "Kann das Temporärverzeichnis für mathematische Formeln nicht anlegen oder beschreiben.",
"math_bad_output"	=> "Kann das Zielverzeichnis für mathematische Formeln nicht anlegen oder beschreiben.",
"math_notexvc"		=> "Das texvc-Programm kann nicht gefunden werden. Bitte beachten Sie math/README.",
'prefs-personal' => 'Benutzerdaten',
'prefs-rc' => 'Letzte Änderungen und Anzeige kurzer Artikel',
'prefs-misc' => 'Verschiedene Einstellungen',
"import"        	=> "Seiten importieren",
"importtext"    	=> "Bitte exportieren Sie die Seite vom Quellwiki mittels Spezial:Export und laden Sie die Datei dann über diese Seite wieder hoch.",
"importfailed"  	=> "Import fehlgeschlagen: $1",
"importnotext"  	=> "Leer oder kein Text",
"importsuccess" 	=> "Import erfolgreich!",
"importhistoryconflict" => "Es existieren bereits ältere Versionen, die mit diesen kollidieren. (Möglicherweise wurde die Seite bereits vorher importiert)",
"isbn"			=> "ISBN",
"rfcurl"		=> "http://www.faqs.org/rfcs/rfc$1.html",
"siteuser" => "{{SITENAME}}-Benutzer $1",
"siteusers" => "{{SITENAME}}-Benutzer $1",
'watch' => 'Beobachten',
'unwatch' => 'nicht mehr beobachten',
'move' => "verschieben",
'edit' => 'bearbeiten',
'talk' => 'Diskussion',
"infobox" => "Klicken Sie einen Button, um einen Beispieltext zu erhalten.",
"infobox_alert" => "Bitte geben Sie den Text ein, den Sie formatiert haben möchten.\\nEr wird dann zum Kopieren in der Infobox angezeigt.\\nBeispiel:\\n$1\\nwird zu\\n$2",
"nocookiesnew" => "Der Benutzerzugang wurde erstellt, aber Sie sind nicht eingeloggt. {{SITENAME}} benötigt für diese Funktion Cookies, bitte aktivieren Sie diese und loggen sich dann mit Ihrem neuen Benutzernamen und dem Passwort ein.",
"nocookieslogin" => "{{SITENAME}} benutzt Cookies zum Einloggen der Benutzer. Sie haben Cookies deaktiviert, bitte aktivieren Sie diese und versuchen es erneut.",
"subcategorycount" => "Diese Kategorie hat $1 Unterkategorien.",
"categoryarticlecount" => "Dieser Kategorie gehören $1 Artikel an.",
# math
	'mw_math_png' => "Immer als PNG darstellen",
	'mw_math_simple' => "Einfaches TeX als HTML darstellen, sonst PNG",
	'mw_math_html' => "Wenn möglich als HTML darstellen, sonst PNG",
	'mw_math_source' =>"Als TeX belassen (für Textbrowser)",
	'mw_math_modern' => "Empfehlenswert für moderne Browser",
	'mw_math_mathml' => 'MathML (experimentell)',


);
class LanguageDe extends LanguageUtf8 {

	function getDefaultUserOptions () {
		$opt = Language::getDefaultUserOptions();
		return $opt;
	}

	function getBookstoreList () {
		global $wgBookstoreListDe ;
		return $wgBookstoreListDe ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesDe;
		return $wgNamespaceNamesDe;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesDe;
		return $wgNamespaceNamesDe[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesDe;

		foreach ( $wgNamespaceNamesDe as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsDe;
		return $wgQuickbarSettingsDe;
	}

	function getSkinNames() {
		global $wgSkinNamesDe;
		return $wgSkinNamesDe;
	}

	# Inherit userAdjust()

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " .
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
		return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesDe;
		return $wgValidSpecialPagesDe;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesDe;
		return $wgSysopSpecialPagesDe;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesDe;
		return $wgDeveloperSpecialPagesDe;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesDe;
		if( isset( $wgAllMessagesDe[$key] ) ) {
			return $wgAllMessagesDe[$key];
		} else {
			return Language::getMessage( $key );
		}
	}


}

?>
