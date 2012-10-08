<?php
/** Swiss High German (Schweizer Hochdeutsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Filzstift
 * @author Geitost
 * @author Ianusius
 * @author Kghbln
 * @author MichaelFrey
 * @author SVG
 * @author The Evil IP address
 */

$fallback = 'de';
$separatorTransformTable = array( ',' => "'", '.' => ',' );

$messages = array(
# User preference toggles
'tog-hideminor' => 'Kleine Änderungen in den «Letzten Änderungen» ausblenden',
'tog-hidepatrolled' => 'Kontrollierte Änderungen in den «Letzten Änderungen» ausblenden',
'tog-newpageshidepatrolled' => 'Kontrollierte Seiten bei den «Neuen Seiten» ausblenden',
'tog-usenewrc' => 'Erweiterte Darstellung der «Letzten Änderungen» (benötigt JavaScript)',
'tog-minordefault' => 'Eigene Änderungen standardmässig als minim markieren',
'tog-externaleditor' => 'Externen Editor standardmässig nutzen (nur für Experten, erfordert spezielle Einstellungen auf dem eigenen Computer. [//www.mediawiki.org/wiki/Manual:External_editors Weitere Informationen hierzu.])',
'tog-externaldiff' => 'Externes Programm standardmässig für Versionsunterschiede nutzen (nur für Experten, erfordert spezielle Einstellungen auf dem eigenen Computer. [//www.mediawiki.org/wiki/Manual:External_editors Weitere Informationen hierzu.])',
'tog-showjumplinks' => '«Wechseln zu»-Links aktivieren',

# Categories related messages
'category_header' => 'Seiten in der Kategorie «$1»',
'category-media-header' => 'Medien in der Kategorie «$1»',

'badaccess-groups' => 'Diese Aktion ist auf Benutzer beschränkt, die {{PLURAL:$2|der Gruppe|einer der Gruppen}} «$1» angehören.',

'retrievedfrom' => 'Von «$1»',
'site-atom-feed' => 'Atom-Feed für «$1»',
'page-rss-feed' => 'RSS-Feed für «$1»',
'page-atom-feed' => 'Atom-Feed für «$1»',

# General errors
'dberrortext' => 'Es ist ein Datenbankfehler aufgetreten.
Der Grund kann ein Programmierfehler sein.
Die letzte Datenbankabfrage lautete:
<blockquote><tt>$1</tt></blockquote>
aus der Funktion «<tt>$2</tt>».
Die Datenbank meldete den Fehler «<tt>$3: $4</tt>».',
'dberrortextcl' => 'Es gab einen Syntaxfehler in der Datenbankabfrage.
Die letzte Datenbankabfrage lautete: «$1» aus der Funktion «<tt>$2</tt>».
Die Datenbank meldete den Fehler: «<tt>$3: $4</tt>».',
'missing-article' => 'Der Text von «$1» $2 wurde nicht in der Datenbank gefunden.

Die Seite ist möglicherweise gelöscht oder verschoben worden.

Falls dies nicht  zutrifft, hast du eventuell einen Fehler in der Software gefunden. Bitte melde dies einem [[Special:ListUsers/sysop|Administrator]] unter Nennung der URL.',
'fileappenderrorread' => '«$1» konnte während des Hinzufügens nicht gelesen werden.',
'fileappenderror' => 'Konnte «$1» nicht an «$2» anhängen.',
'filecopyerror' => 'Die Datei «$1» konnte nicht nach «$2» kopiert werden.',
'filerenameerror' => 'Die Datei «$1» konnte nicht nach «$2» umbenannt werden.',
'filedeleteerror' => 'Die Datei «$1» konnte nicht gelöscht werden.',
'directorycreateerror' => 'Das Verzeichnis «$1» konnte nicht angelegt werden.',
'filenotfound' => 'Die Datei «$1» wurde nicht gefunden.',
'fileexistserror' => 'In die Datei «$1» konnte nicht geschrieben werden, da die Datei bereits vorhanden ist.',
'unexpected' => 'Unerwarteter Wert: «$1» = «$2»',
'cannotdelete' => 'Die Seite oder Datei «$1» kann nicht gelöscht werden.
Möglicherweise wurde sie bereits von jemand anderem gelöscht.',
'cannotdelete-title' => 'Seite «$1» kann nicht gelöscht werden',
'actionthrottledtext' => 'Im Rahmen einer Anti-Spam-Massnahme oder aufgrund eines Missbrauchsfilters kann diese Aktion in einem kurzen Zeitabstand nur begrenzt oft ausgeführt werden. Diese Grenze hast du überschritten.
Bitte versuche es in ein paar Minuten erneut.',
'editinginterface' => "'''Warnung:''' Diese Seite enthält von der MediaWiki-Software genutzten Text.
Änderungen auf dieser Seite wirken sich auf die Benutzeroberfläche aus.
Ziehe bitte im Fall von Übersetzungen in Betracht, diese bei [//translatewiki.net/wiki/Main_Page?setlang=de-ch translatewiki.net], der Lokalisierungsplattform für MediaWiki, durchzuführen.",
'titleprotected' => "Eine Seite mit diesem Namen kann nicht angelegt werden.
Die Sperre wurde durch [[User:$1|$1]] mit der Begründung ''«$2»'' eingerichtet.",
'filereadonlyerror' => 'Die Datei «$1» kann nicht verändert werden, da auf das Dateirepositorium «$2» nur Lesezugriff möglich ist.

Der Administrator, der den Schreibzugriff sperrte, gab folgenden Grund an: «$3».',

# Login and logout pages
'loginsuccess' => 'Du bist jetzt als «$1» bei {{SITENAME}} angemeldet.',
'nosuchuser' => 'Der Benutzername «$1» existiert nicht.
Überprüfe die Schreibweise (Gross-/Kleinschreibung beachten) oder [[Special:UserLogin/signup|melde dich als neuer Benutzer an]].',
'nosuchusershort' => 'Der Benutzername «$1» ist nicht vorhanden. Bitte überprüfe die Schreibweise.',
'passwordremindertext' => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst, hat ein neues Passwort für die Anmeldung bei {{SITENAME}} ($4) angefordert.

Das automatisch generierte Passwort für Benutzer „$2“ lautet nun: $3

Falls du dies wirklich gewünscht hast, solltest du dich jetzt anmelden und das Passwort ändern.
Das neue Passwort ist {{PLURAL:$5|1 Tag|$5 Tage}} gültig.

Bitte ignoriere dieses E-Mail, falls du sie nicht selbst angefordert hast. Das alte Passwort bleibt weiterhin gültig.',
'noemail' => '{{GENDER:$1|Benutzer|Benutzerin|Benutzer}} «$1» hat keine E-Mail-Adresse angegeben.',
'passwordsent' => 'Ein neues, temporäres Passwort wurde an die E-Mail-Adresse von Benutzer «$1» gesandt.
Bitte melde dich damit an, sobald du es erhalten hast. Das alte Passwort bleibt weiterhin gültig.',
'eauthentsent' => 'Ein Bestätigungs-E-Mail wurde an die angegebene Adresse verschickt.

Bevor ein E-Mail von anderen Benutzern über die E-Mail-Funktion empfangen werden kann, muss die Adresse und ihre tatsächliche Zugehörigkeit zu diesem Benutzerkonto erst bestätigt werden. Bitte befolge die Hinweise im Bestätigungs-E-Mail.',
'mailerror' => 'Fehler beim Senden des E-Mails: $1',
'createaccount-text' => 'Es wurde für dich ein Benutzerkonto «$2» auf {{SITENAME}} ($4) erstellt. Das automatisch generierte Passwort für «$2» ist «$3». Du solltest dich nun anmelden und das Passwort ändern.

Falls das Benutzerkonto irrtümlich angelegt wurde, kannst du diese Nachricht ignorieren.',

# E-mail sending
'user-mail-no-addy' => 'Versuchte ein E-Mail ohne Angabe einer E-Mail-Adresse zu versenden',

# Change password dialog
'resetpass_announce' => 'Anmeldung mit dem per E-Mail zugesandten Code. Um die Anmeldung abzuschliessen, musst du jetzt ein neues Passwort wählen.',

# Edit pages
'missingsummary' => "'''Hinweis:''' Du hast keine Zusammenfassung angegeben. Wenn du erneut auf «{{int:savearticle}}» klickst, wird deine Änderung ohne Zusammenfassung übernommen.",
'missingcommentheader' => "'''Achtung:''' Du hast kein Betreff/Überschrift eingegeben. Wenn du erneut auf «{{int:savearticle}}» klickst, wird deine Bearbeitung ohne Überschrift gespeichert.",
'blockedtext' => "'''Dein Benutzername oder deine IP-Adresse wurde gesperrt.'''

Die Sperrung wurde von $1 durchgeführt.
Als Grund wurde ''$2'' angegeben.

* Beginn der Sperre: $8
* Ende der Sperre: $6
* Sperre betrifft: $7

Du kannst $1 oder einen der anderen [[{{MediaWiki:Grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.
Du kannst die «E-Mail an diesen Benutzer»-Funktion nicht nutzen, solange keine gültige E-Mail-Adresse in deinen [[Special:Preferences|Benutzerkonto-Einstellungen]] eingetragen ist oder diese Funktion für dich gesperrt wurde.
Deine aktuelle IP-Adresse ist $3, und die Sperr-ID ist $5.
Bitte füge alle Informationen jeder Anfrage hinzu, die du stellst.",
'autoblockedtext' => "Deine IP-Adresse wurde automatisch gesperrt, da sie von einem anderen Benutzer genutzt wurde, der von $1 gesperrt wurde.
Als Grund wurde angegeben:

:''$2''

* Beginn der Sperre: $8
* Ende der Sperre: $6
* Sperre betrifft: $7

Du kannst $1 oder einen der anderen [[{{MediaWiki:Grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.

Du kannst die «E-Mail an diesen Benutzer»-Funktion nicht nutzen, solange keine gültige E-Mail-Adresse in deinen [[Special:Preferences|Benutzerkonto-Einstellungen]] eingetragen ist oder diese Funktion für dich gesperrt wurde.

Deine aktuelle IP-Adresse ist $3, und die Sperr-ID ist $5.
Bitte füge alle Informationen jeder Anfrage hinzu, die du stellst.",
'confirmedittext' => 'Du musst deine E-Mail-Adresse erst bestätigen, bevor du Bearbeitungen durchführen kannst. Bitte ergänze und bestätige dein E-Mail in den [[Special:Preferences|Einstellungen]].',
'accmailtext' => 'Ein zufällig generiertes Passwort für [[User talk:$1|$1]] wurde an $2 versandt.

Das Passwort für dieses neue Benutzerkonto kann auf der Spezialseite «[[Special:ChangePassword|Passwort ändern]]» geändert werden.',
'userpage-userdoesnotexist' => 'Das Benutzerkonto «<nowiki>$1</nowiki>» ist nicht vorhanden. Bitte prüfe, ob du diese Seite wirklich erstellen/bearbeiten willst.',
'userpage-userdoesnotexist-view' => 'Das Benutzerkonto «$1» ist nicht vorhanden.',
'clearyourcache' => "'''Hinweis:''' Leere nach dem Speichern den Browser-Cache, um die Änderungen sehen zu können.
* '''Firefox/Safari:''' ''Umschalttaste'' drücken und gleichzeitig ''Aktualisieren'' anklicken oder entweder ''Ctrl+F5'' oder ''Ctrl+R'' (''⌘+R'' auf dem Mac) drücken
* '''Google Chrome:''' ''Umschalttaste+Ctrl+R'' (''⌘+Umschalttaste+R'' auf dem Mac) drücken
* '''Internet Explorer:''' ''Ctrl+F5'' drücken oder ''Ctrl'' drücken und gleichzeitig ''Aktualisieren'' anklicken
* '''Opera:''' ''Extras → Internetspuren löschen … → Individuelle Auswahl → Den kompletten Cache löschen''",
'usercssyoucanpreview' => "'''Tipp:''' Benutze den «{{int:showpreview}}»-Button, um dein neues CSS vor dem Speichern zu testen.",
'userjsyoucanpreview' => "'''Tipp:''' Benutze den «{{int:showpreview}}»-Button, um dein neues JavaScript vor dem Speichern zu testen.",
'userinvalidcssjstitle' => "'''Achtung:''' Die Benutzeroberfläche «$1» existiert nicht. Bedenke, dass benutzerspezifische .css- und .js-Seiten mit einem Kleinbuchstaben anfangen müssen, also beispielsweise ''{{ns:user}}:Mustermann/vector.css'' an Stelle von ''{{ns:user}}:Mustermann/Vector.css''.",
'editing' => 'Bearbeiten von «$1»',
'editingsection' => 'Bearbeiten von «$1» (Abschnitt)',
'editingcomment' => 'Bearbeiten von «$1» (Neuer Abschnitt)',
'explainconflict' => "Jemand anders hat diese Seite geändert, nachdem du angefangen hast sie zu bearbeiten.
Das obere Textfeld enthält den aktuellen Bearbeitungsstand der Seite.
Das untere Textfeld enthält deine Änderungen.
Bitte füge deine Änderungen in das obere Textfeld ein.
'''Nur''' der Inhalt des oberen Textfeldes wird gespeichert, wenn du auf «{{int:savearticle}}» klickst.",
'copyrightwarning' => "'''Bitte kopiere keine Webseiten, die nicht deine eigenen sind, benutze keine urheberrechtlich geschützten Werke ohne Erlaubnis des Urhebers!'''<br />
Du gibst uns hiermit deine Zusage, dass du den Text '''selbst verfasst''' hast, dass der Text Allgemeingut '''(public domain)''' ist, oder dass der '''Urheber''' seine '''Zustimmung''' gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weise bitte auf der Diskussionsseite darauf hin.
<i>Bitte beachte, dass alle {{SITENAME}}-Beiträge automatisch unter der «$2» stehen (siehe $1 für Einzelheiten). Falls du nicht möchtest, dass deine Arbeit hier von anderen verändert und verbreitet wird, dann drücke nicht auf «Seite speichern».</i>",
'longpageerror' => "'''Fehler: Der Text, den du zu speichern versuchst, ist {{PLURAL:$1|ein Kilobyte|$1 Kilobytes}} gross. Dies ist grösser als das erlaubte Maximum von {{PLURAL:$2|ein Kilobyte|$2 Kilobytes}}.'''
Er kann nicht gespeichert werden.",

# Parser/template warnings
'post-expand-template-inclusion-warning' => 'Warnung: Die Grösse eingebundener Vorlagen ist zu gross, einige Vorlagen können nicht eingebunden werden.',
'post-expand-template-inclusion-category' => 'Seiten, in denen die maximale Grösse eingebundener Vorlagen überschritten ist',
'post-expand-template-argument-warning' => "'''Warnung:''' Diese Seite enthält mindestens einen Parameter in einer Vorlage, der expandiert zu gross ist. Diese Parameter werden ignoriert.",

# History pages
'histlegend' => 'Zur Anzeige der Änderungen einfach die zu vergleichenden Versionen auswählen und die Schaltfläche «{{int:compareselectedversions}}» klicken.<br />
* ({{int:cur}}) = Unterschied zur aktuellen Version, ({{int:last}}) = Unterschied zur vorherigen Version
* Uhrzeit/Datum = Version zu dieser Zeit, Benutzername/IP-Adresse des Bearbeiters, {{int:minoreditletter}} = Kleine Änderung',

# Revision deletion
'revdelete-show-file-confirm' => 'Bist du sicher, dass du die gelöschte Version der Datei «<nowiki>$1</nowiki>» vom $2 um $3 Uhr ansehen willst?',
'revdelete-show-no-access' => 'Fehler beim Anzeigen des Eintrags vom $1, $2 Uhr: Dieser Eintrag wurde als «eingeschränkt» markiert.
Du hast darauf keinen Zugriff.',
'revdelete-modify-no-access' => 'Fehler beim Bearbeiten des Eintrags vom $1, $2 Uhr: Dieser Eintrag wurde als «eingeschränkt» markiert.
Du hast darauf keinen Zugriff.',

# History merging
'mergehistory-merge' => 'Die folgenden Versionen von «[[:$1]]» können nach «[[:$2]]» übertragen werden. Markiere die Version, bis zu der (einschliesslich) die Versionen übertragen werden sollen. Bitte beachte, dass die Nutzung der Navigationslinks die Auswahl zurücksetzt.',
'mergehistory-success' => '{{PLURAL:$3|1 Version|$3 Versionen}} von «[[:$1]]» erfolgreich nach «[[:$2]]» vereinigt.',
'mergehistory-no-source' => 'Ursprungsseite «$1» ist nicht vorhanden.',
'mergehistory-no-destination' => 'Zielseite «$1» ist nicht vorhanden.',
'mergehistory-autocomment' => '«[[:$1]]» vereinigt nach «[[:$2]]»',
'mergehistory-comment' => '«[[:$1]]» vereinigt nach «[[:$2]]»: $3',

# Diffs
'history-title' => 'Versionsgeschichte von «$1»',

# Search results
'searchresults-title' => 'Suchergebnisse für «$1»',
'searchsubtitle' => 'Deine Suchanfrage: «[[:$1]]» ([[Special:Prefixindex/$1|alle mit «$1» beginnenden Seiten]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Seiten, die nach «$1» verlinken]])',
'searchsubtitleinvalid' => 'Deine Suchanfrage: «$1».',
'toomanymatches' => 'Die Anzahl der Suchergebnisse ist zu gross, bitte versuche eine andere Abfrage.',
'searchmenu-exists' => "'''Es gibt eine Seite, die den Namen «[[:$1]]» hat.'''",
'searchmenu-new' => "'''Erstelle die Seite «[[:$1]]» in diesem Wiki.'''",
'search-redirect' => '(Weiterleitung von «$1»)',
'search-suggest' => 'Meintest du «$1»?',
'nonefound' => "'''Hinweis:''' Es werden standardmässig nur einige Namensräume durchsucht. Setze ''all:'' vor deinen Suchbegriff, um alle Seiten (inkl. Diskussionsseiten, Vorlagen usw.) zu durchsuchen oder gezielt den Namen des zu durchsuchenden Namensraumes.",

# Preferences page
'prefs-watchlist-days' => 'Anzahl der Tage, die die Beobachtungsliste standardmässig umfassen soll:',
'prefs-edit-boxsize' => 'Grösse des Bearbeitungsfensters:',
'recentchangesdays' => 'Anzahl der Tage, die die Liste der «Letzten Änderungen» standardmässig umfassen soll:',
'recentchangescount' => 'Anzahl der standardmässig angezeigten Bearbeitungen:',
'defaultns' => 'In diesen Namensräumen soll standardmässig gesucht werden:',
'prefs-textboxsize' => 'Grösse des Bearbeitungsfensters',
'prefs-help-signature' => 'Beiträge auf Diskussionsseiten sollten mit «<nowiki>~~~~</nowiki>» signiert werden, was dann in die Signatur mit Zeitstempel umgewandelt wird.',

# Rights
'right-createpage' => 'Seiten erstellen (ausser Diskussionsseiten)',
'right-nominornewtalk' => 'Kleine Bearbeitungen an Diskussionsseiten führen zu keiner «Neue Nachrichten»-Anzeige',
'right-bigdelete' => 'Seiten mit grosser Versionsgeschichte löschen',
'right-override-export-depth' => 'Exportiere Seiten einschliesslich verlinkter Seiten bis zu einer Tiefe von 5',
'right-passwordreset' => 'Passwort eines Benutzers zurücksetzen und das dazu verschickte E-Mail einsehen',

# User rights log
'rightslogentry' => 'änderte die Benutzerrechte für «$1» von «$2» auf «$3»',
'rightslogentry-autopromote' => 'wurde automatisch von «$2» nach «$3» zugeordnet',

# Recent changes
'rc_categories' => 'Nur Seiten aus den Kategorien (getrennt mit «|»):',
'rc-old-title' => 'ursprünglich erstellt als «$1»',

# Recent changes linked
'recentchangeslinked-title' => 'Änderungen an Seiten, die von «$1» verlinkt sind',

# Upload
'uploadtext' => "Benutze dieses Formular, um neue Dateien hochzuladen.

Gehe zu der [[Special:FileList|Liste hochgeladener Dateien]], um vorhandene Dateien zu suchen und anzuzeigen. Siehe auch das [[Special:Log/upload|Datei-]] und [[Special:Log/delete|Lösch-Logbuch]].

Um ein '''Bild''' in einer Seite zu verwenden, nutze einen Link in der folgenden Form:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datei.jpg]]</nowiki></code>''' – für ein Vollbild
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datei.png|200px|thumb|left|Alternativer Text]]</nowiki></code>''' – für ein 200px breites Bild innerhalb einer Box, mit «Alternativer Text» als Bildbeschreibung
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datei.ogg]]</nowiki></code>''' – für einen direkten Link auf die Datei, ohne Darstellung der Datei",
'illegalfilename' => 'Der Dateiname «$1» enthält mindestens ein nicht erlaubtes Zeichen. Bitte benenne die Datei um und versuche, sie erneut hochzuladen.',
'filename-toolong' => 'Dateinamen dürfen nicht grösser als 240 Byte sein.',
'badfilename' => 'Der Dateiname wurde in «$1» geändert.',
'filetype-mime-mismatch' => 'Dateierweiterung «.$1» stimmt nicht mit dem MIME-Typ ($2) überein.',
'filetype-badmime' => 'Dateien mit dem MIME-Typ «$1» dürfen nicht hochgeladen werden.',
'filetype-bad-ie-mime' => 'Diese Datei kann nicht hochgeladen werden, da der Internet Explorer sie als «$1» erkennt, welcher ein nicht erlaubter potentiell gefährlicher Dateityp ist.',
'filetype-unwanted-type' => "'''«.$1»''' ist ein unerwünschtes Dateiformat. Erlaubt {{PLURAL:$3|ist das Dateiformat|sind die Dateiformate}}: $2.",
'filetype-banned-type' => "'''«.$1»''' {{PLURAL:$4|ist ein nicht erlaubter Dateityp|sind nicht erlaubte Dateitypen}}.
{{PLURAL:$3|Erlaubter Dateityp ist|Erlaubte Dateitypen sind}} $2.",
'filetype-missing' => 'Die hochzuladende Datei hat keine Erweiterung (z. B. «.jpg»).',
'file-too-large' => 'Die hochgeladene Datei war zu gross.',
'large-file' => 'Die Dateigrösse sollte nach Möglichkeit $1 nicht überschreiten. Diese Datei ist $2 gross.',
'largefileserver' => 'Die Datei ist grösser als die vom Server eingestellte Maximalgrösse.',
'fileexists-extension' => 'Eine Datei mit ähnlichem Namen existiert bereits: [[$2|thumb]]
* Name der hochzuladenden Datei: <strong>[[:$1]]</strong>
* Name der vorhandenen Datei: <strong>[[:$2]]</strong>
Nur die Dateiendung unterscheidet sich in Gross-/Kleinschreibung. Bitte prüfe, ob die Dateien inhaltlich identisch sind.',
'fileexists-thumbnail-yes' => "Bei der Datei scheint es sich um ein Bild verringerter Grösse ''(thumbnail)'' zu handeln. [[$1|thumb]]
Bitte prüfe die Datei <strong>[[:$1]]</strong>.
Wenn es sich um das Bild in Originalgrösse handelt, so braucht kein separates Vorschaubild hochgeladen zu werden.",
'file-thumbnail-no' => "Der Dateiname beginnt mit <strong>$1</strong>. Dies deutet auf ein Bild verringerter Grösse ''(thumbnail)'' hin.
Bitte prüfe, ob du das Bild in voller Auflösung vorliegen hast und lade dieses unter dem Originalnamen hoch.",
'uploadedimage' => 'lud «[[$1]]» hoch',
'overwroteimage' => 'lud eine neue Version von «[[$1]]» hoch',
'upload-maxfilesize' => 'Maximale Dateigrösse: $1',
'filename-bad-prefix' => "Der Dateiname beginnt mit '''«$1»'''. Dies ist im allgemeinen der von einer Digitalkamera vorgegebene Dateiname und daher nicht sehr aussagekräftig.
Bitte gib der Datei einen Namen, der den Inhalt besser beschreibt.",

'upload-unknown-size' => 'Unbekannte Grösse',

# File backend
'backend-fail-maxsize' => 'Die Datei $1 konnte nicht erstellt werden, da sie grösser als {{PLURAL:$2|ein Byte|$2 Byte}} ist.',
'backend-fail-readonly' => 'Das Speicher-Backend «$1» befindet sich derzeit im Lesemodus. Der angegebene Grund lautet: «$2»',
'backend-fail-synced' => 'Die Datei «$1» befindet sich, innerhalb des internen Speicher-Backends, in einem inkonsistenten Zustand.',
'backend-fail-connect' => 'Es konnte keine Verbindung zum Speicher-Backend «$1» hergestellt werden.',
'backend-fail-internal' => 'Im Speicher-Backend «$1» ist ein unbekannter Fehler aufgetreten.',
'backend-fail-contenttype' => 'Der Inhaltstyp, der im Pfad «$1» zu speichernden Datei, konnte nicht bestimmt werden.',
'backend-fail-usable' => 'Die Datei «$1» konnte entweder aufgrund eines nicht vorhandenen Verzeichnisses oder wegen unzureichender Berechtigungen weder abgerufen noch gespeichert werden.',

# File journal errors
'filejournal-fail-dbconnect' => 'Es konnte keine Verbindung zur Journaldatenbank des Speicher-Backends «$1» hergestellt werden.',
'filejournal-fail-dbquery' => 'Die Journaldatenbank des Speicher-Backends «$1» konnte nicht aktualisiert werden.',

# Lock manager
'lockmanager-notlocked' => '«$1» konnte nicht entsperrt werden, da keine Sperrung besteht.',
'lockmanager-fail-closelock' => 'Die Sperrdatei für «$1» konnte nicht geschlossen werden.',
'lockmanager-fail-deletelock' => 'Die Sperrdatei für «$1» konnte nicht gelöscht werden.',
'lockmanager-fail-acquirelock' => 'Die Sperre für «$1» konnte nicht abgerufen werden.',
'lockmanager-fail-openlock' => 'Die Sperrdatei für «$1» konnte nicht geöffnet werden.',
'lockmanager-fail-releaselock' => 'Die Sperre für «$1» konnte nicht freigegeben werden.',

# ZipDirectoryReader
'zip-bad' => 'Die Datei ist beschädigt oder eine aus anderweitigen Gründen nicht lesbare ZIP-Datei.
Sie kann daher keiner ordnungsgemässen Sicherheitsüberprüfung unterzogen werden.',
'zip-unsupported' => 'Diese ZIP-Datei verfügt über Komprimierungseigenschaften, die nicht von MediaWiki unterstützt werden.
Sie kann daher keiner ordnungsgemässen Sicherheitsüberprüfung unterzogen werden.',

# img_auth script messages
'img-auth-badtitle' => 'Aus «$1» kann kein gültiger Titel erstellt werden.',
'img-auth-nologinnWL' => 'Du bist nicht angemeldet und «$1» ist nicht in der weissen Liste.',
'img-auth-nofile' => 'Datei «$1» existiert nicht.',
'img-auth-isdir' => 'Du versuchst, auf ein Verzeichnis «$1» zuzugreifen.
Nur Dateizugriff ist erlaubt.',
'img-auth-streaming' => 'Lade «$1».',
'img-auth-noread' => 'Benutzer hat keine Berechtigung, «$1» zu lesen.',

# HTTP errors
'http-invalid-scheme' => 'URLs mit dem Schema «$1» werden nicht unterstützt',

# Special:ListFiles
'listfiles-summary' => 'Diese Spezialseite listet alle hochgeladenen Dateien auf. Standardmässig werden die zuletzt hochgeladenen Dateien zuerst angezeigt. Durch einen Klick auf die Spaltenüberschriften kann die Sortierung umgedreht werden oder es kann nach einer anderen Spalte sortiert werden.',
'listfiles_size' => 'Grösse',

# File description page
'filehist-dimensions' => 'Masse',
'filehist-filesize' => 'Dateigrösse',

# File reversion
'filerevert' => 'Zurücksetzen von «$1»',

# File deletion
'filedelete' => 'Lösche «$1»',
'filedelete-intro' => "Du löschst die Datei '''«[[Media:$1|$1]]»''' inklusive ihrer Versionsgeschichte.",
'filedelete-intro-old' => "Du löschst von der Datei '''«[[Media:$1|$1]]»''' die [$4 Version vom $2, $3 Uhr].",
'filedelete-success' => "'''«$1»''' wurde gelöscht.",
'filedelete-success-old' => "Von der Datei '''«[[Media:$1|$1]]»''' wurde die Version vom $2, $3 Uhr gelöscht.",
'filedelete-nofile' => "'''«$1»''' ist nicht vorhanden.",
'filedelete-nofile-old' => "Es gibt von '''«$1»''' keine archivierte Version mit den angegebenen Attributen.",

# Random page
'randompage-nopages' => 'Es sind keine Seiten {{PLURAL:$2|im folgenden Namensraum|in den folgenden Namensräumen}} enthalten: «$1»',

# Random redirect
'randomredirect-nopages' => 'Im Namensraum «$1» sind keine Weiterleitungen vorhanden.',

# Special:Log
'alllogstext' => 'Dies ist die kombinierte Anzeige aller in {{SITENAME}} geführten Logbücher.
Die Ausgabe kann durch die Auswahl des Logbuchtyps, des Benutzers oder des Seitentitels eingeschränkt werden (Gross-/Kleinschreibung muss beachtet werden).',

# Special:AllPages
'allpages-bad-ns' => 'Der Namensraum «$1» ist in {{SITENAME}} nicht vorhanden.',

# Special:LinkSearch
'linksearch-text' => 'Diese Spezialseite ermöglicht die Suche nach Seiten, in denen bestimmte Weblinks enthalten sind. Dabei können Platzhalter wie beispielsweise <code>*.beispiel.ch</code> benutzt werden. Es muss mindestens eine Top-Level-Domain, z. B. «*.org». angegeben werden. <br />Unterstützte Protokolle: <code>$1</code> (Diese bitte nicht bei der Suchanfrage angeben.)',

# E-mail user
'emailpagetext' => 'Du kannst dem Benutzer mit dem unten stehenden Formular ein E-Mail senden.
Als Absender wird die E-Mail-Adresse aus deinen [[Special:Preferences|Einstellungen]] eingetragen, damit der Benutzer dir antworten kann.',
'defemailsubject' => '{{SITENAME}} - E-Mail von Benutzer «$1»',
'emailnotarget' => 'Nicht vorhandener oder ungültiger Benutzername für den Empfang eines E-Mails.',
'emailccme' => 'Sende eine Kopie des E-Mails an mich',
'emailsenttext' => 'Dein E-Mail wurde verschickt.',
'emailuserfooter' => 'Dieses E-Mail wurde von {{SITENAME}}-Benutzer «$1» an «$2» gesendet.',

# Watchlist
'addedwatchtext' => 'Die Seite «[[:$1]]» wurde zu deiner [[Special:Watchlist|Beobachtungsliste]] hinzugefügt.
Spätere Änderungen an dieser Seite und der zugehörigen Diskussionsseite werden dort gelistet und die Seite wird in der [[Special:RecentChanges|Liste der letzten Änderungen]] in Fettschrift angezeigt.',
'removedwatchtext' => 'Die Seite «[[:$1]]» wurde von deiner [[Special:Watchlist|Beobachtungsliste]] entfernt.',
'iteminvalidname' => 'Problem mit dem Eintrag «$1», ungültiger Name.',

# Displayed when you click the "watch" button and it is in the process of watching
'watcherrortext' => 'Beim Ändern der Beobachtungslisteneinstellungen für «$1» ist ein Fehler aufgetreten.',

# Delete
'excontent' => 'Inhalt war: «$1»',
'excontentauthor' => 'Inhalt war: «$1» (einziger Bearbeiter: [[Special:Contributions/$2|$2]])',
'exbeforeblank' => 'Inhalt vor dem Leeren der Seite: «$1»',
'delete-confirm' => 'Löschen von «$1»',
'deletedtext' => '«$1» wurde gelöscht. Im $2 findest du eine Liste der letzten Löschungen.',

# Rollback
'editcomment' => "Die Änderungszusammenfassung lautet: ''«$1»''.",

# Protect
'protectedarticle' => 'schützte «[[$1]]»',
'modifiedarticleprotection' => 'änderte den Schutz von «[[$1]]»',
'unprotectedarticle' => 'hob den Schutz von «[[$1]]» auf',
'movedarticleprotection' => 'übertrug den Seitenschutz von «[[$2]]» auf «[[$1]]»',
'protect-title' => 'Schutz ändern von «$1»',
'protect-title-notallowed' => 'Schutz ansehen von «$1»',
'protect-text' => 'Hier kannst du den Schutzstatus der Seite «$1» einsehen und ändern.',
'protect-locked-blocked' => "Du kannst den Seitenschutz nicht ändern, da dein Benutzerkonto gesperrt ist. Hier sind die aktuellen Seitenschutz-Einstellungen der Seite '''«$1»:'''",
'protect-locked-dblock' => "Die Datenbank ist gesperrt, der Seitenschutz kann daher nicht geändert werden. Hier sind die aktuellen Seitenschutz-Einstellungen der Seite '''«$1»:'''",
'protect-locked-access' => "Dein Benutzerkonto verfügt nicht über die notwendigen Rechte zur Änderung des Seitenschutzes. Hier sind die aktuellen Seitenschutzeinstellungen der Seite '''«$1»:'''",
'protect-fallback' => 'Es wird die «$1»-Berechtigung benötigt.',
'minimum-size' => 'Mindestgrösse',
'maximum-size' => 'Maximalgrösse:',

# Undelete
'undeleteextrahelp' => '* Um die Seite mitsamt aller Versionen wiederherzustellen, wähle keine Version aus, gib eine Begründung an und klicke dann auf «{{int:undeletebtn}}».
* Um lediglich bestimmte Versionen der Seite wiederherzustellen, wähle die betreffenden Versionen einzeln aus, gib eine Begründung an und klicke dann auf «{{int:undeletebtn}}».',
'undeletedpage' => "'''«$1»''' wurde wiederhergestellt.

Im [[Special:Log/delete|Lösch-Logbuch]] findest du eine Übersicht der gelöschten und wiederhergestellten Seiten.",
'undelete-cleanup-error' => 'Fehler beim Löschen der unbenutzten Archiv-Version «$1».',
'undelete-show-file-confirm' => 'Bist du sicher, dass du eine gelöschte Version der Datei «<nowiki>$1</nowiki>» vom $2, $3 Uhr sehen willst?',

# Contributions
'contributions-title' => 'Benutzerbeiträge von «$1»',

# What links here
'whatlinkshere-title' => 'Seiten, die auf «$1» verlinken',
'linkshere' => "Die folgenden Seiten verlinken auf '''«[[:$1]]»''':",
'nolinkshere' => "Keine Seite verlinkt auf '''«[[:$1]]»'''.",
'nolinkshere-ns' => "Keine Seite verlinkt auf '''«[[:$1]]»''' im gewählten Namensraum.",

# Block/unblock
'ipb-confirmhideuser' => 'Du bist gerade dabei, einen Benutzer im Modus «Benutzer verstecken» zu sperren. Dies führt dazu, dass der Benutzername in allen Listen und Logbüchern unterdrückt wird. Möchtest du das wirklich tun?',
'ipb-blocklist-contribs' => 'Benutzerbeiträge von «$1»',
'autoblocker' => 'Automatische Sperre, da du eine gemeinsame IP-Adresse mit [[User:$1|$1]] benutzt. Grund der Benutzersperre: «$2».',
'blocklogentry' => 'sperrte «[[$1]]» für den Zeitraum: $2 $3',
'reblock-logentry' => 'änderte die Sperre von «[[$1]]» für den Zeitraum: $2 $3',
'unblocklogentry' => 'hob die Sperre von «$1» auf',
'ipb_already_blocked' => '«$1» wurde bereits gesperrt.',
'ipb-needreblock' => '«$1» ist bereits gesperrt. Möchtest du die Sperrparameter ändern?',
'ip_range_toolarge' => 'Adressbereiche, die größer als /$1 sind, sind nicht erlaubt.',
'cant-see-hidden-user' => 'Der Benutzer, den du versuchst zu sperren, wurde bereits gesperrt und verborgen. Da du das «hideuser»-Recht nicht hast, kannst du die Benutzersperre nicht sehen und nicht bearbeiten.',

# Move page
'move-page' => 'Verschieben von «$1»',
'movepage-moved' => "'''Die Seite «$1» wurde nach «$2» verschoben.'''",
'movepage-page-exists' => 'Die Seite «$1» ist bereits vorhanden und kann nicht automatisch überschrieben werden.',
'movepage-page-moved' => 'Die Seite «$1» wurde nach «$2» verschoben.',
'movepage-page-unmoved' => 'Die Seite «$1» konnte nicht nach «$2» verschoben werden.',
'delete_and_move_text' => '== Löschung erforderlich ==

Die Seite «[[:$1]]» existiert bereits. Möchtest du diese löschen, um die Seite verschieben zu können?',
'delete_and_move_reason' => 'gelöscht, um Platz für die Verschiebung von «[[$1]]» zu machen',
'immobile-source-namespace' => 'Seiten des «$1»-Namensraums können nicht verschoben werden',
'immobile-target-namespace' => 'Seiten können nicht in den «$1»-Namensraum verschoben werden',

# Thumbnails
'thumbnail-more' => 'vergrössern',
'djvu_page_error' => 'DjVu-Seite ausserhalb des Seitenbereichs',

# Special:Import
'import-interwiki-templates' => 'Alle Vorlagen einschliessen',
'importuploaderrorsize' => 'Das Hochladen der Importdatei ist fehlgeschlagen. Die Datei ist grösser als die maximal erlaubte Dateigrösse.',
'import-error-edit' => 'Die Seite «$1» wurde nicht importiert, da du nicht berechtigt bist, sie zu bearbeiten.',
'import-error-create' => 'Die Seite «$1» wurde nicht importiert, da du nicht berechtigt bist, sie zu erstellen.',
'import-error-interwiki' => 'Die Seite «$1» wurde nicht importiert, da deren Name für externe Links (Interwiki) reserviert ist.',
'import-error-special' => 'Die Seite «$1» wurde nicht importiert, da sie zu einem besonderen Namensraum gehört, in dem keine Seiten möglich sind.',
'import-error-invalid' => 'Seite «$1» wurde nicht importiert, da deren Name ungültig ist.',

# Import log
'import-logentry-upload' => 'importierte «[[$1]]» von einer Datei',
'import-logentry-interwiki' => 'importierte «$1» (Transwiki)',

# JavaScriptTest
'javascripttest-pagetext-unknownframework' => 'Unbekanntes Framework «$1».',

# Tooltip help for the actions
'tooltip-t-emailuser' => 'Ein E-Mail an diesen Benutzer senden',

# Info page
'pageinfo-title' => 'Informationen zu «$1»',

# Patrolling
'markedaspatrollederror' => 'Markierung als «kontrolliert» nicht möglich.',

# Image deletion
'filedelete-missing' => 'Die Datei «$1» kann nicht gelöscht werden, da sie nicht vorhanden ist.',
'filedelete-old-unregistered' => 'Die angegebene Datei-Version «$1» ist nicht in der Datenbank vorhanden.',
'filedelete-current-unregistered' => 'Die angegebene Datei «$1» ist nicht in der Datenbank vorhanden.',
'filedelete-archive-read-only' => 'Das Archiv-Verzeichnis «$1» ist für den Webserver nicht beschreibbar.',

# Media information
'imagemaxsize' => "Maximale Bildgrösse:<br />''(für Dateibeschreibungsseiten)''",
'thumbsize' => 'Standardgrösse der Vorschaubilder:',
'file-info' => 'Dateigrösse: $1, MIME-Typ: $2',
'file-info-size' => '$1 × $2 Pixel, Dateigrösse: $3, MIME-Typ: $4',
'file-info-size-pages' => '$1 × $2 Pixel, Dateigrösse: $3, MIME-Typ: $4, $5 {{PLURAL:$5|Seite| Seiten}}',
'svg-long-desc' => 'SVG-Datei, Basisgrösse: $1 × $2 Pixel, Dateigrösse: $3',
'show-big-image-preview' => 'Grösse dieser Vorschau: $1.',

# Metadata
'metadata-fields' => 'Die folgenden Felder der EXIF-Metadaten, die in diesem MediaWiki-Systemtext angegeben sind, werden auf Bildbeschreibungsseiten mit eingeklappter Metadatentabelle angezeigt.
Weitere werden standardmässig nicht angezeigt.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-jpeginterchangeformatlength' => 'Grösse der JPEG-Daten in Bytes',
'exif-referenceblackwhite' => 'Schwarz/Weiss-Referenzpunkte',
'exif-maxaperturevalue' => 'Grösste Blende',
'exif-whitebalance' => 'Weissabgleich',
'exif-gpsdop' => 'Masspräzision',

'exif-lightsource-13' => 'Tagesweiss fluoreszierend (N 4600–5400 K)',
'exif-lightsource-14' => 'Kaltweiss fluoreszierend (W 3900–4500 K)',
'exif-lightsource-15' => 'Weiss fluoreszierend (WW 3200–3700 K)',

'exif-isospeedratings-overflow' => 'Grösser als 65535',

# E-mail address confirmation
'confirmemail_text' => '{{SITENAME}} erfordert, dass du deine E-Mail-Adresse bestätigst (authentifizierst), bevor du die erweiterten E-Mail-Funktionen benutzen kannst. Klicke bitte auf die unten stehende, mit «Bestätigungscode zuschicken» beschriftete Schaltfläche, damit ein automatisch erstelltes E-Mail an die angegebene Adresse geschickt wird. Dieses E-Mail enthält eine Web-Adresse mit einem Bestätigungscode. Indem du diese Webseite in deinem Webbrowser öffnest, bestätigst du, dass die angegebene E-Mail-Adresse korrekt und gültig ist.',
'confirmemail_pending' => 'Es wurde dir bereits ein Bestätigungscode per E-Mail zugeschickt.
Wenn du dein Benutzerkonto erst vor kurzem erstellt hast, warte bitte noch ein paar Minuten auf das E-Mail, bevor du einen neuen Code anforderst.',
'confirmemail_body' => 'Hallo,

jemand mit der IP-Adresse $1, wahrscheinlich du selbst, hat das Benutzerkonto «$2» in {{SITENAME}} registriert.

Um die E-Mail-Funktion von {{SITENAME}} (wieder) zu aktivieren und um zu bestätigen,
dass dieses Benutzerkonto wirklich zu deiner E-Mail-Adresse und damit zu dir gehört, öffne bitte die folgende Web-Adresse:

$3

Sollte die vorstehende Adresse in deinem E-Mail-Programm über mehrere Zeilen gehen, musst du sie allenfalls per Hand in die Adresszeile deines Web-Browsers einfügen.

Wenn du das genannte Benutzerkonto *nicht* registriert hast, folge diesem Link, um den Bestätigungsprozess abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $6, $7 Uhr.',
'confirmemail_body_changed' => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst,
hat die E-Mail-Adresse des Benutzerkontos «$2» zu dieser Adresse auf {{SITENAME}} geändert.

Um zu bestätigen, dass dieses Benutzerkonto wirklich dir gehört
und um die E-Mail-Features auf {{SITENAME}} zu reaktivieren, öffne diesen Link in deinem Browser:

$3

Falls das Konto *nicht* dir gehört, folge diesem Link,
um die E-Mail-Adress-Bestätigung abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $4.',
'confirmemail_body_set' => 'Jemand mit der IP-Adresse $1, wahrscheinlich du selbst,
hat die E-Mail-Adresse des Benutzerkontos «$2» auf {{SITENAME}} zu dieser E-Mail-Adresse abgeändert.

Um zu bestätigen, dass dieses Benutzerkonto wirklich zu dir gehört
und um die E-Mail-Funktionen auf {{SITENAME}} wieder zu aktivieren, öffne bitte den folgenden Link in deinem Browser:

$3

Falls das Konto *nicht* zu dir gehört, bitte den nachfolgenden Link öffnen,
um die Bestätigung der E-Mail-Adresse abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $4.',

# Auto-summaries
'autosumm-replace' => 'Der Seiteninhalt wurde durch einen anderen Text ersetzt: «$1»',
'autosumm-new' => 'Die Seite wurde neu angelegt: «$1»',

# Live preview
'livepreview-error' => 'Verbindung nicht möglich: $1 «$1». Bitte die normale Vorschau benutzen.',

# Watchlist editor
'watchlistedit-normal-explain' => 'Dies sind die Einträge deiner Beobachtungsliste. Um Einträge zu entfernen, markiere die Kästchen neben den Einträgen und klicke am Ende der Seite auf «{{int:Watchlistedit-normal-submit}}». Du kannst deine Beobachtungsliste auch im [[Special:EditWatchlist/raw|Listenformat bearbeiten]].',
'watchlistedit-raw-explain' => 'Dies sind die Einträge deiner Beobachtungsliste im Listenformat. Die Einträge können zeilenweise gelöscht oder hinzugefügt werden.
Pro Zeile ist ein Eintrag erlaubt. Sobald du fertig bist, klicke auf «{{int:Watchlistedit-raw-submit}}».
Du kannst auch die [[Special:EditWatchlist|Standardseite]] zum Bearbeiten benutzen.',

# Core parser functions
'unknown_extension_tag' => 'Unbekanntes Parsertag «$1»',
'duplicate-defaultsort' => 'Achtung: Der Sortierungsschlüssel «$2» überschreibt den vorher verwendeten Schlüssel «$1».',

# Special:Version
'version-license-info' => "MediaWiki ist freie Software, d. h. sie kann, gemäss den Bedingungen der von der Free Software Foundation veröffentlichten ''GNU General Public License'', weiterverteilt und/oder modifiziert werden. Dabei kann die Version 2, oder nach eigenem Ermessen, jede neuere Version der Lizenz verwendet werden.

MediaWiki wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings OHNE JEGLICHE GARANTIE und sogar ohne die implizierte Garantie einer MARKTGÄNGIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK. Hierzu sind weitere Hinweise in der ''GNU General Public License'' enthalten.

Eine [{{SERVER}}{{SCRIPTPATH}}/COPYING Kopie der ''GNU General Public License''] sollte zusammen mit diesem Programm verteilt worden sein. Sofern dies nicht der Fall war, kann eine Kopie bei der Free Software Foundation Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, schriftlich angefordert oder auf deren Website [//www.gnu.org/licenses/old-licenses/gpl-2.0.html online gelesen] werden.",

# Special:FileDuplicateSearch
'fileduplicatesearch-info' => '$1 × $2 Pixel<br />Dateigrösse: $3<br />MIME-Typ: $4',
'fileduplicatesearch-result-1' => 'Die Datei «$1» hat keine identischen Duplikate.',
'fileduplicatesearch-result-n' => 'Die Datei «$1» hat {{PLURAL:$2|ein identisches Duplikat|$2 identische Duplikate}}.',
'fileduplicatesearch-noresults' => 'Es wurde keine Datei namens «$1» gefunden.',

# External image whitelist
'external_image_whitelist' => ' #Diese Zeile nicht verändern.<pre>
#Untenstehend können Fragmente regulärer Ausdrücke (der Teil zwischen den //) eingegeben werden.
#Diese werden mit den URLs von Bildern aus externen Quellen verglichen.
#Ein positiver Vergleich führt zur Anzeige des Bildes, andernfalls wird das Bild nur als Link angezeigt.
#Zeilen, die mit einem # beginnen, werden als Kommentar behandelt.
#Es wird nicht zwischen Gross- und Kleinschreibung unterschieden.

#Fragmente regulärer Ausdrücke nach dieser Zeile eintragen. Diese Zeile nicht verändern.</pre>',

# Feedback
'feedback-bugornote' => 'Sofern Du detailliert ein technisches Problem beschreiben möchtest, melde bitte [$1 einen Fehler].
Anderenfalls kannst du auch das untenstehende einfache Formular nutzen. Dein Kommentar wird, zusammen mit deinem Benutzernamen und der Version des von Dir verwendeten Webbrowsers sowie Betriebssystems, auf der Seite «[$3 $2]» hinzugefügt.',
'feedback-thanks' => 'Vielen Dank. Deine Rückmeldung wurde auf der Seite «[$2 $1]» gespeichert.',

# API errors
'api-error-file-too-large' => 'Die hochgeladene Datei war zu gross.',
'api-error-hookaborted' => 'Die von dir vorgesehene Anpassung kann nicht durchgeführt werden (Unterbruch durch eine Programmschnittstelle).',
'api-error-unknown-code' => 'Unbekannter Fehler: «$1»',
'api-error-unknown-warning' => 'Unbekannte Warnung: «$1»',
'api-error-unknownerror' => 'Unbekannter Fehler: «$1»',

);
