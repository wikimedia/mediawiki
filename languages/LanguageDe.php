<?php
/** German (Deutsch)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** */
require_once( 'LanguageUtf8.php' );

# See Language.php for notes.

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesDe = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_MAIN             => '',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Benutzer',
	NS_USER_TALK        => 'Benutzer_Diskussion',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_Diskussion',
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
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsDe = array(
	'Keine', 'Links, fest', 'Rechts, fest', 'Links, schwebend'
);

/* private */ $wgSkinNamesDe = array(
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


/* private */ $wgBookstoreListDe = array(
	'Verzeichnis lieferbarer B&uuml;cher' => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1&x=0&y=0',
	'abebooks.de' => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de' => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Lehmanns Fachbuchhandlung' => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
);

/* private */ $wgAllMessagesDe = array(
'Monobook.css' =>
'/** Do not force \'lowercase\' */
.portlet h5,
.portlet h6,
#p-personal ul,
#p-cactions li a {
	text-transform: none;
}',
# User toggles
"tog-underline"               => "Verweise unterstreichen",
"tog-highlightbroken"         => "Verweise auf leere Themen hervorheben",
"tog-justify"                 => "Text als Blocksatz",
"tog-hideminor"               => "Keine kleinen Änderungen in Letzte Änderungen anzeigen",
"tog-usenewrc"                => "Erweiterte letzte Änderungen (nicht für alle Browser geeignet)",
"tog-numberheadings"          => "Überschriften automatisch nummerieren",
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
'tog-previewonfirst' 		=> 'Vorschau beim ersten Editieren anzeigen',
"tog-nocache"                 => "Seitencache deaktivieren",
'tog-enotifwatchlistpages' 	=> 'Benachrichtigungsmails für Änderungen an Wiki-Seiten',
'tog-enotifusertalkpages' 	=> 'Benachrichtigungsmails für Änderungen an Ihren Benutzerseiten',
'tog-enotifminoredits' 		=> 'Benachrichtigungsmails auch für kleine Seitenänderungen',
'tog-enotifrevealaddr' 		=> 'Ihre E-Mail-Adresse wird in Benachrichtigungsmails gezeigt',
'tog-shownumberswatching' 	=> 'Zeige die Anzahl seitenbeobachtender Benutzer (in Letzte Änderungen, Beobachtungsliste und Artikelseiten)',
'tog-fancysig'			=> 'Einfache Unterschrift (Spitzname ohne Link)',
'tog-externaleditor' => 'Benutze standardmäßig externen Editor',
'tog-externaldiff' => 'Benutze standardmäßig externen Diff',

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
'category_header' => 'Artikel in der Kategorie "$1"',
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
"aboutpage"		=> "{{ns:project}}:Über_{{SITENAME}}",
"article" => "Artikel",
"help"			=> "Hilfe",
"helppage"		=> "Project:Hilfe",
"bugreports"	=> "Kontakt",
"bugreportspage" => "Project:Kontakt",
"sitesupport"   => "Spenden",
"faq"			=> "FAQ",
"faqpage"		=> "{{ns:project}}:Häufig_gestellte_Fragen",
"edithelp"		=> "Bearbeitungshilfe",
"edithelppage"	=> "{{ns:project}}:Editierhilfe",
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
"undelete_short1" => "Wiederherstellen",
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
"protectedpage" => "Geschützte Seite",
"administrators" => "Project:Administratoren",
"sysoptitle"	=> "Sysop-Zugang notwendig",
'sysoptext'		=> 'Dieser Vorgang kann aus Sicherheitsgründen nur von Benutzern mit"Sysop"-Status durchgeführt werden. Siehe auch $1.',
"developertitle" => "Entwickler-Zugang notwendig",
'developertext'	=> 'Dieser Vorgang kann aus Sicherheitsgründen nur von Benutzern mit"Entwickler"-Status durchgeführt werden. Siehe auch $1.',
"nbytes"		=> "$1 Byte",
"go"			=> "Los",
"ok"			=> "OK",
"sitetitle"		=> "{{SITENAME}}",
"sitesubtitle"	=> "Die freie Enzyklopädie",
"pagetitle"		=> "$1 - {{SITENAME}}",
"sitesubtitle"	=> "Die freie Wissensdatenbank",
'retrievedfrom' => 'Von "$1"',
"newmessages" => "Sie haben $1.",
"newmessageslink" => "neue Nachrichten",
"editsection" => "bearbeiten",
"toc" => "Inhaltsverzeichnis",
"showtoc" => "Anzeigen",
"hidetoc" => "Verbergen",
"thisisdeleted" => "Ansehen oder wiederherstellen von $1?",
"restorelink" => "$1 gelöschte Bearbeitungsvorgänge",
"feedlinks" => "Feed:",

# Namespace form on various pages
'namespace' => 'Namensraum:',
'invert' => 'Kehre Selektion um',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'Alles',
'namespacesall' => 'Alle',

# Kurzworte für jeden Namespace, u.a. von MonoBook verwendet
'nstab-main' => 'Artikel',
'nstab-user' => 'Benutzerseite',
'nstab-media' => 'Media',
'nstab-special' => 'Spezial',
'nstab-image' => 'Bild',
'nstab-help' => 'Hilfe',
'nstab-category' => 'Kategorie',
'nstab-wp' => 'Projektseite',

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
"perfdisabled" => "Diese Funktion wurde wegen Überlastung des Servers vorübergehend deaktiviert. Versuchen Sie es bitte zwischen 02:00 und 14:00 UTC noch einmal<br />(Aktuelle Serverzeit : ".date("H:i:s")." UTC).",
"perfdisabledsub" => "Hier ist eine gespeicherte Kopie von $1:",
"perfcached" => "Die folgenden Daten stammen aus dem Cache und sind möglicherweise nicht aktuell:",
"wrong_wfQuery_params" => "Falsche Parameter für wfQuery()<br />
Funktion: $1<br />
Query: $2
",
"viewsource" => "Quelltext betrachten",
"protectedtext" => "Diese Seite ist für das Bearbeiten gesperrt. Dafür kann es diverse Gründe geben; siehe [[{{ns:4}}:Geschützte Seiten]].

Sie können den Quelltext dieser Seite betrachten und kopieren:",


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
"remembermypassword" => "Dauerhaftes Einloggen",
"loginproblem"	=> "<b>Es gab ein Problem mit Ihrer Anmeldung.</b><br />Bitte versuchen Sie es nochmal!",
"alreadyloggedin" => "<strong>Benutzer $1, Sie sind bereits angemeldet!</strong><br />\n",

"login"			=> "Anmelden",
"loginprompt"           => "Um sich bei {{SITENAME}} anmelden zu können, müssen Cookies aktiviert sein.",
"userlogin"		=> "Anmelden",
'disabled_on_this_wiki'	=> '<div class="error"><b>Diese Funktion ist bei diesem Wiki dauerhaft abeschaltet.
Durch die automatische Anmeldung beim Benutzerkonto sind eigene Benutzer-Anmeldung oder -Abmeldung nicht erforderlich.</b></div>',
"logout"		=> "Abmelden",
"userlogout"	=> "Abmelden",
"notloggedin" => "Nicht angemeldet",
"createaccount"	=> "Neues Benutzerkonto anlegen",
"createaccountmail" => "über eMail",
"badretype"		=> "Die beiden Passwörter stimmen nicht überein.",
"userexists"	=> "Dieser Benutzername ist schon vergeben. Bitte wählen Sie einen anderen.",
"youremail"		=> "Ihre E-Mail-Adresse**",
"yournick"		=> "Ihr \"Spitzname\" (zum \"Unterschreiben\")",
"yourrealname"		=> "Ihr echter Name (keine Pflichtangabe)*",
"emailforlost"	=> "Falls Sie Ihr Passwort vergessen haben, kann Ihnen ein neues an Ihre E-Mail-Adresse gesendet werden.",
'prefs-help-email' 	=> '** <strong>E-Mail-Adresse</strong> (optional): Erlaubt andern, Sie über Ihre Benutzerseiten zu kontaktieren, ohne dass Sie Ihre E-Mail-Adresse offenbaren müssen.
Für den Fall, dass Sie Ihr Passwort vergessen haben, kann Ihnen so ein temporäres Einmal-Passwort gesendet werden.',
'prefs-help-email-enotif' => 'An diese Adresse werden auch die Benachrichtigungsmails geschickt, sofern Sie das eingeschaltet haben.',
'prefs-help-realname' 	=> '* <strong>Echter Name</strong> (optional): für anerkennende Nennungen Ihres Namens im Zusammenhang mit Ihren Beiträgen.',
"loginerror"	=> "Fehler bei der Anmeldung",
"noname"		=> "Sie müssen einen Benutzernamen angeben.",
"loginsuccesstitle" => "Anmeldung erfolgreich",
"loginsuccess"	=> "Sie sind jetzt als \"$1\" bei {{SITENAME}} angemeldet.",
"nosuchuser"	=> "Der Benutzername \"$1\" existiert nicht.
Überprüfen Sie die Schreibweise, oder melden Sie sich als neuer Benutzer an.",
"wrongpassword"	=> "Das Passwort ist falsch (oder fehlt). Bitte versuchen Sie es erneut.",
"mailmypassword" => "Ein neues (temporäres) Passwort schicken",
'mailmypasswordauthent'	=> 'Ein neues (temporäres) Passwort schicken, auch für Authentifizierung der E-Mailadresse',
'passwordremindermailsubject' => "E-Mail-Adressenauthentifizierung und temporäres Passwort für {{SITENAME}}",
'passwordremindermailbody' 	=> "Jemand, vielleicht Sie, hat von IP-Adresse $1
um ein temporäres Einmal-Passwort für die Anmeldung bei {{SITENAME}} gebeten.

Das Passwort für Benutzer \"$2\" lautet nun \"$3\".

Bitte melden Sie sich nun mit diesem temporären Passwort an, das gleichzeitig der Authentifizierung
Ihrer E-Mail-Adresse dient. Für weitere Anmeldungen verwenden Sie bitte wieder Ihr altes Passwort.
Alternativ können Sie auch ein neues eintragen, zum Beispiel, wenn Sie das alte vergessen haben.,

{{SERVER}}{{localurl:Special:Userlogin|wpName=$2&wpPassword=$3&returnto=Special:Preferences}}",
"noemail"		=> "Benutzer \"$1\" hat keine E-Mail-Adresse angegeben.",
"passwordsent"	=> "Ein temporäres Passwort wurde an die E-Mail-Adresse von Benutzer \"$1\" gesendet.
Bitte melden Sie sich damit an, sobald Sie es erhalten.",
'passwordsentforemailauthentication'
	=> "Ein temporäres Passwort wurde an die gerade neu eingetragene E-Mail-Adresse des Benutzers \"$1\" gesendet.
Bitte melden Sie sich damit an, um die Adressauthentifizierung durchzuführen.",
"loginend"		=> "&nbsp;",
"mailerror" => "Fehler beim Senden von Mail: $1",
'acct_creation_throttle_hit' => 'Sie haben schon $1 Benutzerkonten und können jetzt keine weiteren mehr anlegen.',
'emailconfirmlink' 	=> 'E-Mail-Adresse bestätigen (authentifizieren).',
'emailauthenticated' 	=> 'Ihre E-Mail-Adresse wurde am $1 authentifiziert.',
'emailnotauthenticated'	=> 'Ihre E-Mail-Adresse ist <strong>noch nicht authentifiziert</strong> und die erweiterten E-Mailfunktionen sind bis zur Authentifizierung ohne Funktion <strong>(aus)</strong>.<br />
Für die Authentifizierung melden Sie sich bitte mit dem per E-Mail geschickten temporären Passwort an, oder fordern Sie auf der Anmeldeseite ein neues an.',
'invalidemailaddress'	=> 'Die E-Mail-Adresse wurde nicht akzeptiert, da sie ein ungültiges Format aufzuweisen scheint. Bitte geben Sie eine Adresse in einem gültigen Format ein, oder leeren Sie das Feld.',
'disableduntilconfirmation'	=> '<strong>(aus)</strong>',
'noemailprefs'	=> '<strong>Sie haben keine E-Mail-Adresse angegeben</strong>, die folgenden
Funktionen sind zur Zeit deshalb nicht möglich.',

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
"showdiff"	=> "&Auml;nderungen zeigen",
"blockedtitle"	=> "Benutzer ist blockiert",
"blockedtext"	=> "Ihr Benutzername oder Ihre IP-Adresse wurde von $1 blockiert.
Als Grund wurde angegeben:<br />$2<p>Bitte kontaktieren Sie den Administrator, um über die Blockierung zu sprechen.",
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
'usercsspreview' => "== Vorschau ihres Benutzer-CSS. ==
'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview' => "== Vorschau Ihres Benutzer-Javascript. ==
'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'clearyourcache' => "'''Beachten Sie:''' Nach dem Speichern müssen Sie ihrem Browser sagen, die neue Version zu laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''IE:''' ''Strg-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview' => "<strong>Tipp:</strong> Benutzen Sie den Vorschau-Button, um Ihr neues css/js vor dem Speichern zu testen.",
"updated"		=> "(Geändert)",
"note"			=> "<strong>Hinweis:</strong> ",
"previewnote"	=> "Dies ist nur eine Vorschau, der Artikel wurde noch nicht gespeichert!",
"previewconflict" => "Diese Vorschau gibt den Inhalt des oberen Textfeldes wieder; so wird der Artikel aussehen, wenn Sie jetzt speichern.",
"editing"		=> "Bearbeiten von $1",
"editingsection"	=> "Bearbeiten von $1 (Absatz)",
"editingcomment"	=> "Bearbeiten von $1 (Kommentar)",
"editconflict"	=> "Bearbeitungs-Konflikt: $1",
"explainconflict" => "Jemand anders hat diesen Artikel geändert, nachdem Sie angefangen haben, ihn zu bearbeiten.
Das obere Textfeld enthält den aktuellen Artikel.
Das untere Textfeld enthält Ihre Änderungen.
Bitte fügen Sie Ihre Änderungen in das obere Textfeld ein.
<b>Nur</b> der Inhalt des oberen Textfeldes wird gespeichert, wenn Sie auf \"Speichern\" klicken!<br />",
"yourtext"		=> "Ihr Text",
"storedversion" => "Gespeicherte Version",
"editingold"	=> "<strong>ACHTUNG: Sie bearbeiten eine alte Version dieses Artikels.
Wenn Sie speichern, werden alle neueren Versionen überschrieben.</strong>",
"yourdiff"		=> "Unterschiede",
"copyrightwarning" => "
<b>Bitte <big>kopieren Sie keine Webseiten</big>, die nicht Ihre eigenen sind, benutzen Sie <big>keine urheberrechtlich geschützten Werke</big> ohne Erlaubnis des Copyright-Inhabers!</b>
<p>Sie geben uns hiermit ihre Zusage, dass Sie den Text <strong>selbst verfasst</strong> haben, dass der Text Allgemeingut (<strong>public domain</strong>) ist, oder dass der <strong>Copyright-Inhaber</strong> seine <strong>Zustimmung</strong> gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weisen Sie bitte auf der 'Diskussion:'-Seite darauf hin.
<p><i>Bitte beachten Sie, dass alle {{SITENAME}}-Beiträge automatisch unter der \"GNU Freie Dokumentationslizenz\" stehen. Falls Sie nicht möchten, dass Ihre Arbeit hier von anderen verändert und verbreitet wird, dann drücken Sie nicht auf \"Speichern\".</i>",
"longpagewarning" => "<strong>WARNUNG: Diese Seite ist $1KB groß; einige Browser könnten Probleme haben, Seiten zu bearbeiten, die größer als 32KB sind.
Überlegen Sie bitte, ob eine Aufteilung der Seite in kleinere Abschnitte möglich ist.</strong>",
"readonlywarning" => "<strong>WARNUNG: Die Datenbank wurde während dem Ändern der
Seite für Wartungsarbeiten gesperrt, so dass Sie die Seite im Moment nicht
speichern können. Sichern Sie sich den Text und versuchen Sie die Änderungen
später einzuspielen.</strong>",
"protectedpagewarning" => "<strong>WARNUNG: Diese Seite wurde gesperrt, so dass sie nur
Benutzer mit Sysop-Rechten bearbeitet werden kann. Beachten Sie bitte die
[[Project:Geschützte Seiten|Regeln für geschützte Seiten]].</strong>",

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
'nextrevision'		=> '←Nächstjüngere Version',
'previousrevision'	=> 'Nächstältere Version→',
"cur"			=> "Aktuell",
"next"			=> "Nächste",
"last"			=> "Vorherige",
"orig"			=> "Original",
"histlegend"	=> "Diff Auswahl: Die Boxen der gewünschten
Versionen markieren und 'Enter' drücken oder den Button unten klicken/alt-v.<br />
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
"nogomatch" => "Es existiert kein Artikel [[$1|$1]]. Bitte versuchen
Sie die Volltextsuche oder [[$1|legen Sie den Artikel neu an]]. ",
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
Suche in Namensräumen :<br />
$1<br />
$2 Zeige auch REDIRECTs &nbsp; Suche nach $3 $9",
"searchdisabled" => "<p>Entschuldigung! Die Volltextsuche wurde wegen Überlastung temporär deaktiviert. Derweil können Sie die folgende Google Suche verwenden, die allerdings nicht den aktuellen Stand wiederspiegelt.<p>",
"blanknamespace" => "(Haupt-)",

# Preferences page
#
"preferences"	=> "Einstellungen",
"prefsnologin" => "Nicht angemeldet",
"prefsnologintext"	=> "Sie müssen [[Special:Userlogin|angemeldet]] sein, um Ihre Einstellungen zu ändern.",
"prefslogintext" => "Sie sind angemeldet als \"$1\".
Ihre interne ID-Nummer ist $2.",
"prefsreset"	=> "Einstellungen wurden auf Standard zurückgesetzt.",
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
"textboxsize"	=> "Textfeld-Größe",
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
'enotifnewpages'	=> 'Sende mir eine Benachrichtigungsmail, wenn jemand eine neue Seite anlegt.',
"defaultns"		=> "In diesen Namensräumen soll standardmäßig gesucht werden:",
'skinpreview' => '(Vorschau)',
'yourlanguage'	=> 'Sprache',
'imagemaxsize' => 'Verkleinere Bilder auf Bildbeschreibungsseiten auf: ',
'thumbsize'	=> 'Thumbnail-	Größe: ',
'files'			=> 'Bilder',

# Recent changes
#
"changes" => "Änderungen",
"recentchanges" => "Letzte Änderungen",
"recentchangestext" => "
Diese Seite wird beim Laden automatisch aktualisiert. Angezeigt werden Seiten, die zuletzt bearbeitet wurden, sowie die Zeit und der Name des Autors.<br />
Falls Sie neu bei {{SITENAME}} sind, lesen Sie bitte die [[Project:Willkommen|Willkommensseite]] und [[Project:Erste Schritte|Erste Schritte]].<br />
Wenn Sie möchten, dass {{SITENAME}} zu einem Erfolg wird, dann fügen Sie bitte keine Texte hinzu, die dem [[Project:Urheberrechte beachten|Urheberrecht]] anderer unterliegen. Dies könnte dem Projekt sonst schweren Schaden zufügen.",
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
'number_of_watching_users_pageview' 	=> '[$1 Benutzer beobachten diese Seite]',


# Upload
#
"upload"		=> "Hochladen",
"uploadbtn"		=> "Dateien hochladen",
"uploadlink"		=> "Bilder hochladen",
"reupload"		=> "Erneut hochladen",
"reuploaddesc"	=> "Zurück zur Hochladen-Seite.",
"uploadnologin" => "Nicht angemeldet",
"uploadnologintext"	=> "Sie müssen [[Spezial:Userlogin|angemeldet sein]],
 um Dateien hochladen zu können.",
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
"filename"		=> "Dateiname",
"filedesc"		=> "Beschreibung",
"filestatus" => "Copyright-Status",
"filesource" => "Quelle",
'sourcefilename' => 'Quelldatei',
'destfilename' => 'Dateiname ändern',
"copyrightpage" => "Project:Copyright",
"copyrightpagename" => "{{SITENAME}} copyright",
"uploadedfiles"	=> "Hochgeladene Dateien",
"ignorewarning"	=> "Warnung ignorieren und Datei trotzdem speichern.",
"minlength"		=> "Bilddateien müssen mindestens drei Buchstaben haben.",
"badfilename"	=> "Der Bildname wurde in \"$1\" geändert.",
"badfiletype"	=> "\".$1\" ist kein empfohlenes Dateiformat.",
"largefile"		=> "Bitte keine Bilder über 100 KByte hochladen.",
'emptyfile'		=> "Die hochgeladene Datei ist leer. Der Grund kann ein Tippfehler im Dateinamen sein. Bitte kontrollieren Sie, ob Sie die Datei wirklich hochladen wollen.",
'fileexists'		=> 'Eine Datei mit diesem Namen existiert schon. Bitte überprüfen Sie $1, falls Sie sich nicht sicher sind, ob Sie diese Datei überschreiben wollen.',
"uploadedimage" => "\"[[$1]]\" hochgeladen",
'uploadscripted' => 'Diese Datei enthält HTML- oder Scriptcode der irrtümlich von einem Webbrowser ausgeführt werden könnte.',
'uploadvirus' => 'Diese Datei enthält einen Virus! Details: $1',
'uploadcorrupt' => 'Die Datei ist beschädigt oder hat einen falschen Namen. Bitte überprüfen Sie die Datei und laden Sie sie erneut hoch.',
"successfulupload" => "Erfolgreich hochgeladen",
"fileuploaded"	=> "Die Datei \"$1\" wurde erfolgreich hochgeladen.
Bitte verwenden Sie diesen ($2) Link zur Beschreibungsseite und füllen Sie die Informationen über die Datei
 aus, insbesondere seine Herkunft, von wem und wann es
 gemacht wurde und besondere Angaben zum Copyright, falls notwendig.
 Falls es sich um ein Bild handelte, so können Sie mit
 <tt><nowiki>[[Bild:$1|thumb|Beschreibung]]</nowiki></tt> ein Vorschaubild
 auf der Seite erzeugen lassen.",
"uploadwarning" => "Warnung",
"savefile"		=> "Datei speichern",
"uploadedimage" => "\"[[$1]]\" hochgeladen",

# Image list
#
"imagelist"		=> "Bilderliste",
"imagelisttext"	=> "Hier ist eine Liste von $1 Bildern, sortiert $2.",
"getimagelist"	=> "Lade Bilderliste",
"ilsubmit"		=> "Suche",
"showlast"		=> "Zeige die letzten $1 Bilder, sortiert nach $2.",
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
"disambiguationspage"	=> "Project:Begriffsklärung",
"disambiguationstext"	=> "Die folgenden Artikel verweisen auf eine <i>Seite zur Begriffsklärung</i>. Sie sollten statt dessen auf die eigentlich gemeinte Seite verweisen.<br />Eine Seite wird als Begriffsklärungsseite behandelt, wenn $1 auf sie verweist.<br />Verweise aus Namensräumen werden hier <i>nicht</i> aufgelistet.",
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
"randompage"	=> "Zufälliger Artikel",
"shortpages"	=> "Kurze Artikel",
"longpages"		=> "Lange Artikel",
"listusers"		=> "Benutzerverzeichnis",
"specialpages"	=> "Spezialseiten",
"spheading"		=> "Spezialseiten",
'restrictedpheading'	=> 'Spezialseiten für Administratoren',
"protectpage"	=> "Artikel schützen",
"recentchangeslinked" => "Verlinkte Seiten",
"rclsub"		=> "(auf Artikel von \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Neue Artikel",
'newimages'	=> 'Neue Dateien',
"ancientpages" => "Älteste Artikel",
"movethispage"	=> "Artikel verschieben",
"unusedimagestext" => "<p>Bitte beachten Sie, dass andere Wikis möglicherweise einige dieser Bilder benutzen.",
"booksources"	=> "Buchhandlungen",
"booksourcetext" => "Dies ist eine Liste mit Links zu Internetseiten, die neue und gebrauchte Bücher verkaufen. Dort kann es auch weitere Informationen über die Bücher geben, die Sie interessieren. {{SITENAME}} ist mit keinem dieser Anbieter geschäftlich verbunden.",
"alphaindexline" => "$1 bis $2",
'mostlinked'	=> 'Meistverlinke Seiten',
'uncategorizedpages'	=> 'Nicht kategorisierte Artikel',
'uncategorizedcategories'	=> 'Nicht kategorisierte Kategorien',
'unusedcategories' => 'Verwaiste Kategorien',

# Special:Allpages
'allpages'	=> 'Alle Artikel',
'nextpage'	=> "Nächste Seite ($1)",
'allpagesfrom'	=> 'Seiten anzeigen ab:',
'allarticles'	=> 'Alle Artikel',
'allnonarticles'	=> 'Alle Nicht-Artikel',
'allinnamespace'	=> "Alle Seiten im Namensraum: $1",
'allnotinnamespace'	=> "Alle Seiten (ohne Namensraum: $1)",
'allpagesprev'	=> 'Vorherige',
'allpagesnext'	=> 'Nächste',
'allpagessubmit'	=> 'Zeige',

#Special:Logs
'log'		=> 'Logbücher',
'alllogstext'	=> 'Kombinierte Anzeige der Datei-, Lösch-, Seitenschutz-, Verschiebungs-, Benutzerblockaden- und Bürokraten-Logbücher.
Sie können die Anzeige einschränken, indem Sie ein Logbuch auswählen und/oder einen Benutzernamen, bzw. eine Seite angeben..',
'specialloguserlabel' => 'Benutzer: ',
'speciallogtitlelabel' => 'Titel: ',
"1movedto2"		=> "$1 wurde nach $2 verschoben",
"1movedto2_redir" => "$1 wurde nach $2 verschoben und überschrieb einen Redirect",
'movelogpage' => 'Verschiebungslogbuch',
'movelogpagetext' => 'Liste verschobener Seiten',
'rightslogtext'		=> 'Dies ist ein Logbuch für Änderungen an Benutzerrechten.',

# Email this user
#
"mailnologin"	=> "Sie sind nicht angemeldet.",
"mailnologintext" => "Sie müssen [[Spezial:Userlogin|angemeldet sein]]
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
"watchnologintext"	=> "Sie müssen [[Special:Userlogin|angemeldet]]
sein, um Ihre Beobachtungsliste zu bearbeiten.",
"addedwatch"	=> "Zur Beobachtungsliste hinzugefügt",
"addedwatchtext" => "Der Artikel \"$1\" wurde zu Ihrer [[Special:Watchlist|Beobachtungsliste]] hinzugefügt.
Spätere Änderungen an diesem Artikel und der zugehörigen Diskussions-Seite
werden dort gelistet und der Artikel wird in der [[Special:Recentchanges|Liste der letzten Änderungen]] <b>fett</b> angezeigt. <p>Wenn Sie den Artikel wieder von ihrer
Beobachtungsliste entfernen wollen, klicken Sie auf \"Nicht mehr beobachten\"
am Ende des Artikels.",
"removedwatch"	=> "Von der Beobachtungsliste entfernt",
"removedwatchtext" => "Der Artikel \"$1\" wurde von Ihrer Beobachtungsliste entfernt.",
"watchthispage"	=> "Seite beobachten",
"unwatchthispage" => "Nicht mehr beobachten",
"notanarticle"	=> "Kein Artikel",
"watchnochange" => "Keine Ihrer beobachteten Artikel wurde während des angezeigten Zeitraums bearbeitet.",
"watchdetails" => "* Sie beobachten zur Zeit insgesamt $1 Artikel (Diskussionsseiten wurden hier nicht mitgezählt).
* [[Special:Watchlist/edit|Gesamte Beobachtungsliste]] anzeigen und bearbeiten.
",
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
"iteminvalidname" => "Problem mit dem Eintrag '$1', ungültiger Name...",
'wlnote' => 'Es folgen die letzten $1 Änderungen der letzten <b>$2</b> Stunden.',
'wlshowlast' => 'Zeige die letzen $1 Stunden $2 Tage $3',
'wlsaved'	 => 'Dies ist eine gespeicherte Version Ihrer Beobachtungsliste.',
'wlhideshowown'  => '$1 von mir bearbeitete Artikel.',
'wlshow'         => 'Zeige ',
'wlhide'         => 'Verstecke ',

'updatedmarker'			=> '<span class=\'updatedmarker\'>&nbsp;(geändert)&nbsp;</span>',
'enotif_mailer' 		=> '{{SITENAME}} E-Mail-Benachrichtigungsdienst',
'enotif_reset'			=> 'Alle Benachrichtigungsmarker zurücksetzen (alle Seiten als "gesehen" markieren)',
'enotif_newpagetext'		=> 'Dies ist eine neue Seite.',
'changed' 			=> 'geändert',
'created' 			=> 'neu angelegt',
'enotif_subject' 		=> 'Die {{SITENAME}} Seite $PAGETITLE wurde von $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited' => '$1 zeigt alle Änderungen auf einen Blick.',
'enotif_to' 	=> '$WATCHINGUSERNAME_QP <$WATCHINGUSEREMAILADDR>',
'enotif_body' => 'Liebe/r $WATCHINGUSERNAME,

die {{SITENAME}} Seite $PAGETITLE wurde von $PAGEEDITOR am $PAGEEDITDATE $CHANGEDORCREATED,
die aktuelle Version ist: $PAGETITLE_URL

$NEWPAGE

Zusammenfassung des Editors: $PAGESUMMARY $PAGEMINOREDIT
Kontakt zum Editor:
Mail $PAGEEDITOR_EMAIL
Wiki $PAGEEDITOR_WIKI

Es werden solange keine weiteren Benachrichtigungsmails gesendet, bis Sie die Seite wieder besuchen. Auf Ihrer Beobachtungsseite können Sie alle Benachrichtigungsmarker zusammen zurücksetzen.

             Ihr freundliches {{SITENAME}} Benachrichtigungssystem

---
Ihre Beobachtungsliste {{SERVER}}{{localurl:Special:Watchlist/edit}}
Hilfe zur Benutzung gibt {{SERVER}}{{localurl:WikiHelpdesk}}',

# Delete/protect/revert
#
"deletepage"	=> "Seite löschen",
"confirm"		=> "Bestätigen",
"excontent" => "Alter Inhalt: '$1'",
"excontentauthor" => "Alter Inhalt: '$1' (einziger Autor war '$2')",
"exbeforeblank" => "Inhalt vor dem Leeren der Seite: '$1'",
"exblank" => "Seite war leer",
"confirmdelete" => "Löschung bestätigen",
"deletesub"		=> "(Lösche \"$1\")",
"historywarning" => "WARNUNG: Die Seite die Sie zu löschen gedenken hat
eine Versionsgeschichte: ",
"confirmdeletetext" => "Sie sind dabei, einen Artikel oder ein Bild und alle älteren Versionen permanent aus der Datenbank zu löschen.
Bitte bestätigen Sie Ihre Absicht, dies zu tun, dass Sie sich der Konsequenzen bewusst sind, und dass Sie in Übereinstimmung mit unseren [[Project:Leitlinien|Leitlinien]] handeln.",
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
"uctop"		=> " (aktuell)" ,

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
Dies sollte nur erfolgen, um Vandalismus zu verhindern, in Übereinstimmung mit unseren [[Project:Leitlinien|Leitlinien]].
Bitte tragen Sie den Grund für die Blockade ein.",
"ipaddress"		=> "IP-Adresse",
'ipadressorusername' => 'IP-Adresse oder Benutzername',
"ipbreason"		=> "Grund",
"ipbsubmit"		=> "Adresse blockieren",
'ipbother'		=> 'Andere Zeit',
'ipboptions'		=> '2 Stunden:2 hours,1 Tag:1 day,3 Tage:3 days,1 Woche:1 week,2 Wochen:2 weeks,1 Monat:1 month,3 Monate:3 months,6 Monate:6 months,1 Jahr:1 year,Unendlich:infinite',
'ipbotheroption'	=> 'Andere',
"badipaddress"	=> "Die IP-Adresse hat ein falsches Format.",
"blockipsuccesssub" => "Blockade erfolgreich",
"blockipsuccesstext" => "Die IP-Adresse \"$1\" wurde blockiert.
<br />Auf [[Spezial:Ipblocklist|IP block list]] ist eine Liste der Blockaden.",
"unblockip"		=> "IP-Adresse freigeben",
"unblockiptext"	=> "Benutzen Sie das Formular, um eine blockierte IP-Adresse freizugeben.",
"ipusubmit"		=> "Diese Adresse freigeben",
"ipusuccess"	=> "IP-Adresse \"$1\" wurde freigegeben",
"ipblocklist"	=> "Liste blockierter IP-Adressen",
"blocklistline"	=> "$1, $2 blockierte $3 ($4)",
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
<br />Bitte geben Sie die Datenbank wieder frei, sobald die Wartung abgeschlossen ist.",
"unlockdbsuccesstext" => "Die {{SITENAME}}-Datenbank wurde freigegeben.",


# User levels special page
#
'userrights' => 'Benutzerrechtsverwaltung',

'userrights-lookup-user' => 'Verwalte Gruppenzugehörigkeit',
'userrights-user-editname' => 'Benutzername: ',
'editusergroup' => 'Bearbeite Benutzerrechte',

# user groups editing
#
'userrights-editusergroup' => 'Bearbeite Gruppenzugehörigkeit des Benutzers',
'saveusergroups' => 'Speichere Gruppenzugehörigkeit',
'userrights-groupsmember' => 'Mitglied von: ',
'userrights-groupsavailable' => 'Verfügbare Gruppen: ',
'userrights-groupshelp' => 'Wähle die Gruppen, aus denen der Benutzer entfernt oder zu denen er hinzugefügt werden soll.
Nicht selektierte Gruppen werden nicht geändert. Eine Selektion kann mit Strg + Linksklick (bzw. Ctrl + Linksklick) entfernt werden.',

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
[[Special:Userlogin|angemeldet]] sein,
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
"missingimage"          => "<b>Fehlendes Bild</b><br /><i>$1</i>\n",

#Tooltips:
'tooltip-watch' => 'Diese Seite beobachten. [alt-w]',
'tooltip-search' => 'Suchen [alt-f]',
'tooltip-minoredit' => 'Diese Änderung als klein markieren. [alt-i]',
'tooltip-save' => 'Änderungen speichern [alt-s]',
'tooltip-preview' => 'Vorschau der Änderungen an dieser Seite. Benutzen Sie dies vor dem Speichern! [alt-p]',
'tooltip-diff' => 'Zeigt Ihre Änderungen am Text tabellarisch an [alt-d]',
'tooltip-compareselectedversions' => 'Unterschiede zwischen zwei ausgewählten Versionen dieser Seite vergleichen. [alt-v]',

#Tastatur-Shortcuts
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'd',
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
"allmessages"		=> "Alle MediaWiki-Meldungen",
"allmessagestext"	=> "Dies ist eine Liste aller möglichen Meldungen im MediaWiki-Namensraum.",
"thumbnail-more"	=> "vergrößern",
"and"			=> "und",
"rchide"		=> "in $4 form; $1 kleine Änderungen; $2 sekundäre Namensräume; $3 mehrfache Änderungen.",
"showhideminor"		=> "kleine Änderungen $1 | $2 bots | $3 logged in users | $4 patrolled edits ",
"rcliu"			=> "$1 Änderungen durch eingeloggte Benutzer",
"uploaddisabled"	=> "Entschuldigung, das Hochladen ist deaktiviert.",
"deadendpages"		=> "Sackgassenartikel",
"intl"			=> "InterWikiLinks",
"version"		=> "Version",
"protectlogpage"	=> "Seitenschutz-Logbuch",
"protectlogtext"	=> "Dies ist eine Liste der blockierten Seiten. Siehe [[{{SITENAME}}:Geschützte Seiten]] für mehr Informationen.",
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
'categoryarticlecount1' => "Dieser Kategorie gehört $1 Artikel an.",
'categoriespagetext' => 'In diesem Wiki gibt es die folgenden Kategorien:',
'unusedcategoriestext' => 'Die folgenden Kategorieseiten existieren, obwohl sie nicht verwendet werden.',
'groups-editgroup-name'     => 'Gruppenname: ',
# math
	'mw_math_png' => "Immer als PNG darstellen",
	'mw_math_simple' => "Einfaches TeX als HTML darstellen, sonst PNG",
	'mw_math_html' => "Wenn möglich als HTML darstellen, sonst PNG",
	'mw_math_source' =>"Als TeX belassen (für Textbrowser)",
	'mw_math_modern' => "Empfehlenswert für moderne Browser",
	'mw_math_mathml' => 'MathML (experimentell)',
	
'passwordtooshort' => 'Ihr Passwort ist zu kurz. Es muss mindestens $1 Zeichen lang sein.',

# Media Warning
'mediawarning' => '
===Warnung!===
Diese Art von Datei kann böswilligen Programmcode enthalten.
Durch das Herunterladen oder Öffnen der Datei kann der Computer beschädigt werden.
Bereits das Anklicken des Links kann dazu führen dass der Browser die Datei öffnet
und unbekannter Programmcode zur Ausführung kommt.

Die Betreiber dieses Wikis können keine Verantwortung für den Inhalte
dieser Datei übernehmen. Sollte diese Datei tatsächlich böswilligen Programmcode enthalten,
sollte umgehend ein Administrator informiert werden!

',

'fileinfo' => '$1KB, [http://de.wikipedia.org/wiki/Multipurpose_Internet_Mail_Extensions $2]',

# external editor support
'edit-externally' => 'Diese Datei mit einem externen Programm bearbeiten',
'edit-externally-help' => 'Siehe [http://meta.wikimedia.org/wiki/Hilfe:Externe_Editoren Installations-Anweisungen] für weitere Informationen',

# Metadata
'exif-make'	=> 'Hersteller',     # Image input equipment manufacturer
'exif-model'	=> 'Modell',         # Image input equipment model
'exif-software' => 'Software',       # Software used
'exif-artist'   => 'Fotograf',       # Person who created the image
'exif-copyright'=> 'Copyright',      # Copyright holder

# Tags relating to image structure
'exif-imagewidth'  => 'Breite',      # Image width
'exif-imagelength' => 'Länge',      # Image height
'exif-orientation' => 'Orientierung',# Orientation of image
'exif-xresolution' => 'Horizontale Auflösung',            # Image resolution in width direction
'exif-yresolution' => 'Vertikale Auflösung',              # Image resolution in height direction
'exif-resolutionunit' => 'Masseinheit der Auflösung',     # Unit of X and Y resolution

# Tags relating to image data characteristics
'exif-ycbcrcoefficients' => 'YCbCr-Koeffizienten',              # Color space transformation matrix coefficients
'exif-referenceblackwhite' => 'Schwarz/Weiß-Referenzpunkte',  # Pair of black and white reference values

# Tags relating to Image Data Characteristics
'exif-colorspace' => 'Farbraum',                                # Color space information

# Tags relating to picture-taking conditions
'exif-exposuretime' => 'Belichtungsdauer',                 # Exposure time
'exif-fnumber' => 'F-Wert',                      # F Number
'exif-exposureprogram' => 'Belichtungsprogramm',              # Exposure Program
'exif-spectralsensitivity' => 'Spectral Sensitivity',          # Spectral sensitivity
'exif-isospeedratings' => 'Filmempfindlichkeit (ISO)',              # ISO speed rating
'exif-shutterspeedvalue' => 'Shutter Speed Value',            # Shutter speed
'exif-aperturevalue' => 'Blendenwert',                # Aperture
'exif-brightnessvalue' => 'Brightness Value',              # Brightness
'exif-exposurebiasvalue' => 'Belichtungsvorgabe',            # Exposure bias
'exif-maxaperturevalue' => 'Größte Blende',             # Maximum land aperture
'exif-subjectdistance' => 'Entfernung',              # Subject distance
'exif-meteringmode' => 'Messverfahren',                 # Metering mode
'exif-lightsource' => 'Lichtquelle',                  # Light source
'exif-flash' => 'Blitz',                        # Flash
'exif-focallength' => 'Brennweite',                  # Lens focal length
'exif-flashenergy' => 'Blitzstärke',                  # Flash energy
'exif-exposuremode' => 'Belichtungsmodus',                 # Exposure mode
'exif-whitebalance' => 'Weißabgleich',                 # White Balance
'exif-focallengthin35mmfilm' => 'Brennweite (Kleinbildäquivalent)',        # Focal length in 35 mm film
'exif-contrast' => 'Kontrast',                     # Contrast
'exif-saturation'=> 'Sättigung',                   # Saturation
'exif-sharpness' => 'Schärfe',                    # Sharpness

# E-mail address confirmation
'confirmemail' => 'E-Mail-Adressenbestätigung (Authentifizierung)',
'confirmemail_text' => "Diese Wiki erfordert, dass Sie Ihre E-Mailadresse bestätigen (authentifizieren),
bevor Sie die erweiterten Mailfunktionen benutzen können. Ein Klick auf die Schaltfläche unten sendet eine E-Mail zu Ihnen.
Diese Mail enthält einen Link mit einem Kode; durch Klicken auf diesen Link bestätigen Sie, dass Ihre Adresse gültig ist.",
'confirmemail_send' => 'Anforderung einer E-Mail zur Adressenbestätigung',
'confirmemail_sent' => 'Es wurde Ihnen eine Mail zur Adressenbestätigung gesendet.',
'confirmemail_sendfailed' => 'Could not send confirmation mail due to misconfigured server or invalid characters in e-mail address.',
'confirmemail_invalid' => 'Ungültiger Bestätigungskode. Die Gültigkeitsdauer des Kodes ist eventuell abgelaufen.',
'confirmemail_success' => 'Ihre E-Mailadresse wurde bestätigt. Sie können sich jetzt einloggen.',
'confirmemail_loggedin' => 'Ihre E-Mailadresse ist nun bestätigt.',
'confirmemail_error' => 'Es gab einen Fehler bei der Bestätigung Ihrer E-Mailadresse.',

'confirmemail_subject' => '{{SITENAME}} E-Mail-Adressenbestätigung (Authentifizierung)',
'confirmemail_body' 	=> "Jemand, vielleicht Sie, hat von IP-Adresse $1
ein Benutzerkonto \"$2\" mit dieser E-Mailadresse bei {{SITENAME}} angemeldet.

Zur Bestätigung, dass dieses Konto wirklich Ihnen gehört, und um die erweiterten
E-Mailfunktionen für Sie bei {{SITENAME}} einzuschalten, öffnen Sie bitte den folgenden Link
in Ihrem Browser:

$3

Wenn Sie *nicht* $2 sind, folgen Sie dem Link bitte nicht.

Der Bestätigungskode läuft am $4 ab.
"

);

/** @package MediaWiki */
class LanguageDe extends LanguageUtf8 {

	function getBookstoreList() {
		global $wgBookstoreListDe ;
		return $wgBookstoreListDe ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesDe;
		return $wgNamespaceNamesDe;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsDe;
		return $wgQuickbarSettingsDe;
	}

	function getSkinNames() {
		global $wgSkinNamesDe;
		return $wgSkinNamesDe;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function getMessage( $key ) {
		global $wgAllMessagesDe;
		if( isset( $wgAllMessagesDe[$key] ) ) {
			return $wgAllMessagesDe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number ) {
		return strtr($number, '.,', ',.' );
	}

}

?>
