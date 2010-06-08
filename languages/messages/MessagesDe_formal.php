<?php
/** German (formal address) (Deutsch (Sie-Form))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author ChrisiPK
 * @author Imre
 * @author Jimmy Collins <jimmy.collins@web.de>
 * @author Kghbln
 * @author Leithian
 * @author MichaelFrey
 * @author Michawiki
 * @author Pill
 * @author Raimond Spekking (Raymond) <raimond.spekking@gmail.com> since January 2007
 * @author The Evil IP address
 * @author Tim Bartel (avatar) <wikipedia@computerkultur.org> formal addressing
 * @author Tischbeinahe
 * @author Umherirrender
 * @author Urhixidur
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-enotifrevealaddr' => 'Ihre E-Mail-Adresse in Benachrichtigungs-E-Mails anzeigen',

'mainpagedocfooter' => 'Hilfe zur Benutzung und Konfiguration der Wiki-Software finden Sie im [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].

== Starthilfen ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste der Konfigurationsvariablen]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'view-pool-error' => 'Entschuldigung, die Server sind im Moment überlastet.
Zu viele Benutzer versuchen diese Seite zu besuchen.
Bitte warten Sie einige Minuten, bevor Sie es noch einmal versuchen.

$1',

'badaccess-group0' => 'Sie haben nicht die erforderliche Berechtigung für diese Aktion.',

'youhavenewmessages'      => 'Sie haben $1 auf Ihrer Diskussionsseite ($2).',
'youhavenewmessagesmulti' => 'Sie haben neue Nachrichten: $1',

# General errors
'enterlockreason'      => 'Bitte geben Sie einen Grund ein, warum die Datenbank gesperrt werden soll und eine Abschätzung über die Dauer der Sperrung',
'readonlytext'         => 'Die Datenbank ist vorübergehend für Neueinträge und Änderungen gesperrt. Bitte versuchen Sie es später noch einmal.

Grund der Sperrung: $1',
'missing-article'      => 'Der Text für „$1“ $2 wurde nicht in der Datenbank gefunden.

Die Seite ist möglicherweise gelöscht oder verschoben worden.

Falls dies nicht der Fall ist, haben Sie eventuell einen Fehler in der Software gefunden. Bitte melden Sie dies einem [[Special:ListUsers/sysop|Administrator]] unter Nennung der URL.',
'actionthrottledtext'  => 'Im Rahmen einer Anti-Spam-Maßnahme kann diese Aktion in einem kurzen Zeitabstand nur begrenzt oft ausgeführt werden. Diese Grenze haben Sie überschritten.
Bitte versuchen Sie es in ein paar Minuten erneut.',
'viewsourcetext'       => 'Sie können den Quelltext dieser Seite betrachten und kopieren:',
'editinginterface'     => "'''Warnung:''' Diese Seite enthält von der MediaWiki-Software benutzten Text.
Änderungen wirken sich auf die Benutzeroberfläche aus.
Für Übersetzungen ziehen Sie bitte in Betracht, diese im [http://translatewiki.net/wiki/Main_Page?setlang=de-formal Translatewiki], dem MediaWiki-Lokalisierungsprojekt, durchzuführen.",
'namespaceprotected'   => "Sie haben keine Berechtigung, Seiten im '''$1'''-Namensraum zu bearbeiten.",
'customcssjsprotected' => 'Sie sind nicht berechtigt, diese Seite zu bearbeiten, da sie zu den persönlichen Einstellungen eines anderen Benutzers gehört.',

# Login and logout pages
'logouttext'                 => "'''Sie sind nun abgemeldet.'''

Sie können {{SITENAME}} jetzt anonym weiter benutzen, oder sich erneut unter dem selben oder einem anderen Benutzernamen [[Special:UserLogin|anmelden]].
Beachten Sie, das einige Seiten noch anzeigen können, das Sie angemeldet sind, solange Sie nicht Ihren Browsercache geleert haben.",
'welcomecreation'            => '== Willkommen, $1! ==

Ihr Benutzerkonto wurde eingerichtet.
Vergessen Sie nicht, Ihre [[Special:Preferences|{{SITENAME}}-Einstellungen]] anzupassen.',
'yourdomainname'             => 'Ihre Domain:',
'externaldberror'            => 'Entweder es liegt ein Fehler bei der externen Authentifizierung vor oder Sie dürfen Ihr externes Benutzerkonto nicht aktualisieren.',
'nologin'                    => "Sie haben kein Benutzerkonto? '''$1'''.",
'gotaccount'                 => "Haben Sie bereits ein Benutzerkonto? '''$1'''.",
'userexists'                 => 'Dieser Benutzername ist schon vergeben. Bitte wählen Sie einen anderen.',
'nocookiesnew'               => 'Der Benutzerzugang wurde erstellt, aber Sie sind nicht angemeldet.
{{SITENAME}} benötigt für diese Funktion Cookies, bitte aktivieren Sie diese und melden sich dann mit Ihrem neuen Benutzernamen und dem zugehörigen Passwort an.',
'nocookieslogin'             => '{{SITENAME}} benutzt Cookies zur Anmeldung der Benutzer.
Sie haben Cookies deaktiviert, bitte aktivieren Sie diese und versuchen Sie es erneut.',
'noname'                     => 'Sie müssen einen gültigen Benutzernamen angeben.',
'loginsuccess'               => 'Sie sind jetzt als „$1“ bei {{SITENAME}} angemeldet.',
'nosuchuser'                 => 'Der Benutzername „$1“ existiert nicht.
Überprüfen Sie die Schreibweise (Groß-/Kleinschreibung beachten) oder [[Special:UserLogin/signup|melden Sie sich als neuer Benutzer an]].',
'nosuchusershort'            => 'Der Benutzername „<nowiki>$1</nowiki>“ existiert nicht. Bitte überprüfen Sie die Schreibweise.',
'nouserspecified'            => 'Bitte geben Sie einen Benutzernamen an.',
'wrongpassword'              => 'Das Passwort ist falsch. Bitte versuchen Sie es erneut.',
'wrongpasswordempty'         => 'Es wurde kein Passwort eingegeben. Bitte versuchen Sie es erneut.',
'password-name-match'        => 'Ihr Passwort muss sich von Ihrem Benutzernamen unterscheiden.',
'passwordremindertext'       => 'Jemand mit der IP-Adresse $1, wahrscheinlich Sie selbst, hat ein neues Passwort für die Anmeldung bei {{SITENAME}} ($4) angefordert.

Das automatisch generierte Passwort für Benutzer „$2“ lautet nun: $3

Falls Sie dies wirklich gewünscht haben, sollten Sie sich jetzt anmelden und das Passwort ändern.
Das neue Passwort ist {{PLURAL:$5|1 Tag|$5 Tage}} gültig.

Bitte ignorieren Sie diese E-Mail, falls Sie sie nicht selbst angefordert haben. Das alte Passwort bleibt weiterhin gültig.',
'noemailcreate'              => 'Sie müssen eine gültige E-Mail-Adresse angeben',
'passwordsent'               => 'Ein neues, temporäres Passwort wurde an die E-Mail-Adresse von Benutzer „$1“ gesandt.
Bitte melden Sie sich damit an, sobald sie es erhalten haben. Das alte Passwort bleibt weiterhin gültig.',
'blocked-mailpassword'       => 'Die von Ihnen verwendete IP-Adresse ist für das Ändern von Seiten gesperrt. Um einen Missbrauch zu verhindern, wurde die Möglichkeit zur Anforderung eines neuen Passwortes ebenfalls gesperrt.',
'eauthentsent'               => 'Eine Bestätigungs-E-Mail wurde an die angegebene Adresse verschickt.

Bevor eine E-Mail von anderen Benutzern über die E-Mail-Funktion empfangen werden kann, muss die Adresse und ihre tatsächliche Zugehörigkeit zu diesem Benutzerkonto erst bestätigt werden. Bitte befolgen Sie die Hinweise in der Bestätigungs-E-Mail.',
'acct_creation_throttle_hit' => 'Besucher dieses Wikis, die Ihre IP-Adresse verwenden, haben innerhalb des letzten Tages {{PLURAL:$1|1 Benutzerkonto|$1 Benutzerkonten}} erstellt, was die maximal erlaubte Anzahl in dieser Zeitperiode ist.

Besucher, die diese IP-Adresse verwenden, können momentan keine Benutzerkonten mehr erstellen.',
'emailauthenticated'         => 'Ihre E-Mail-Adresse wurde am $2 um $3 Uhr bestätigt.',
'emailnotauthenticated'      => 'Ihre E-Mail-Adresse ist noch nicht bestätigt. Die folgenden E-Mail-Funktionen stehen erst nach erfolgreicher Bestätigung zur Verfügung.',
'noemailprefs'               => 'Geben Sie eine E-Mail-Adresse in den Einstellungen an, damit die nachfolgenden Funktionen zur Verfügung stehen.',
'invalidemailaddress'        => 'Die E-Mail-Adresse wird nicht akzeptiert, weil sie ein ungültiges Format (eventuell ungültige Zeichen) zu haben scheint. Bitte geben Sie eine korrekte Adresse ein oder leeren Sie das Feld.',
'createaccount-text'         => 'Es wurde für Sie ein Benutzerkonto „$2“ auf {{SITENAME}} ($4) erstellt. Das automatisch generierte Passwort für „$2“ ist „$3“. Sie sollten sich nun anmelden und das Passwort ändern.

Falls das Benutzerkonto irrtümlich angelegt wurde, können Sie diese Nachricht ignorieren.',
'login-throttled'            => 'Sie haben zu oft versucht, sich anzumelden.
Bitte warten Sie, bevor Sie es erneut probierst.',

# Password reset dialog
'resetpass_announce'      => 'Anmeldung mit dem per E-Mail zugesandten Code. Um die Anmeldung abzuschließen, müssen Sie jetzt ein neues Passwort wählen.',
'resetpass_text'          => '<!-- Ergänzen Sie den Text hier -->',
'resetpass_success'       => 'Ihr Passwort wurde erfolgreich geändert. Es folgt die Anmeldung …',
'resetpass-no-info'       => 'Sie müssen sich anmelden, um auf diese Seite direkt zuzugreifen.',
'resetpass-wrong-oldpass' => 'Ungültiges temporäres oder aktuelles Passwort.
Möglicherweise haben Sie Ihr Passwort bereits erfolgreich geändert oder ein neues temporäres Passwort beantragt.',

# Edit page toolbar
'sig_tip' => 'Ihre Signatur mit Zeitstempel',

# Edit pages
'anoneditwarning'                  => "Sie bearbeiten diese Seite unangemeldet. Wenn Sie speichern, wird Ihre aktuelle IP-Adresse in der Versionsgeschichte aufgezeichnet und ist damit unwiderruflich '''öffentlich''' einsehbar.",
'missingsummary'                   => "'''Hinweis:''' Sie haben keine Zusammenfassung angegeben. Wenn Sie erneut auf „Seite speichern“ klicken, wird Ihre Änderung ohne Zusammenfassung übernommen.",
'missingcommenttext'               => 'Ihr Abschnitt enthält keinen Text.',
'missingcommentheader'             => "'''ACHTUNG:''' Sie haben keine Überschrift im Feld „Betreff:“ eingegeben. Wenn Sie erneut auf „Seite speichern“ klicken, wird Ihre Bearbeitung ohne Überschrift gespeichert.",
'blockedtext'                      => "'''Ihr Benutzername oder Ihre IP-Adresse wurde gesperrt.'''

Die Sperrung wurde von $1 durchgeführt.
Als Grund wurde ''$2'' angegeben.

* Beginn der Sperre: $8
* Ende der Sperre: $6
* Sperre betrifft: $7

Sie könnten $1 oder einen der anderen [[{{MediaWiki:Grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.
Sie können die „E-Mail an diesen Benutzer“-Funktion nicht nutzen, solange keine gültige E-Mail-Adresse in Ihren [[Special:Preferences|Benutzerkonto-Einstellungen]] eingetragen ist, oder diese Funktion für Sie gesperrt wurde.
Ihre aktuelle IP-Adresse ist $3, und die Sperr-ID ist $5.
Bitte fügen Sie alle Informationen jeder Anfrage hinzu, die Sie stellen.",
'autoblockedtext'                  => "Ihre IP-Adresse wurde automatisch gesperrt, da sie von einem anderen Benutzer genutzt wurde, der von $1 gesperrt wurde.
Als Grund wurde angegeben:

:''$2''

* Beginn der Sperre: $8
* Ende der Sperre: $6
* Sperre betrifft: $7

Sie können $1 oder einen der anderen [[{{MediaWiki:Grouppage-sysop}}|Administratoren]] kontaktieren, um über die Sperre zu diskutieren.

Sie können die „E-Mail an diesen Benutzer“-Funktion nicht nutzen, solange keine gültige E-Mail-Adresse in Ihren [[Special:Preferences|Benutzerkonto-Einstellungen]] eingetragen ist, oder diese Funktion für Sie gesperrt wurde.

Ihre aktuelle IP-Adresse ist $3, und die Sperr-ID ist $5.
Bitte fügen Sie alle Informationen jeder Anfrage hinzu, die Sie stellen.",
'blockededitsource'                => "Der Quelltext '''Ihrer Änderungen''' an '''$1''':",
'whitelistedittext'                => 'Sie müssen sich $1, um Seiten bearbeiten zu können.',
'confirmedittext'                  => 'Sie müssen Ihre E-Mail-Adresse erst bestätigen, bevor Sie Bearbeitungen vornehmen können. Bitte ergänzen und bestätigen Sie Ihre E-Mail in den [[Special:Preferences|Einstellungen]].',
'nosuchsectiontext'                => 'Sie haben versucht, einen Abschnitt zu bearbeiten, der nicht existiert.
Vermutlich wurde er verschoben oder gelöscht, nachdem Sie die Seite aufgerufen haben.',
'loginreqpagetext'                 => 'Sie müssen sich $1, um Seiten lesen zu können.',
'newarticletext'                   => "Sie sind einem Link zu einer Seite gefolgt, die nicht vorhanden ist.
Um die Seite anzulegen, tragen Sie Ihren Text in die untenstehende Box ein (siehe die [[{{MediaWiki:Helppage}}|Hilfeseite]] für mehr Informationen).
Sind Sie fälschlicherweise hier, klicken Sie die '''Zurück'''-Schaltfläche Ihres Browsers.",
'anontalkpagetext'                 => "----''Diese Seite dient dazu, einem nicht angemeldeten Benutzer Nachrichten zu hinterlassen. Es wird seine IP-Adresse zur Identifizierung verwendet. IP-Adressen können von mehreren Benutzern gemeinsam verwendet werden. Wenn Sie mit den Kommentaren auf dieser Seite nichts anfangen können, richten sie sich vermutlich an einen früheren Inhaber Ihrer IP-Adresse und Sie können sie ignorieren. Sie können sich auch ein [[Special:UserLogin/signup|Benutzerkonto erstellen]] oder sich [[Special:UserLogin|anmelden]], um künftig Verwechslungen mit anderen anonymen Benutzern zu vermeiden.''",
'noarticletext'                    => 'Diese Seite enthält momentan noch keinen Text.
Sie können diesen Titel auf den anderen Seiten [[Special:Search/{{PAGENAME}}|suchen]],
<span class="plainlinks">in den zugehörigen [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbüchern suchen] oder diese Seite [{{fullurl:{{FULLPAGENAME}}|action=edit}} bearbeiten]</span>.',
'noarticletext-nopermission'       => 'Diese Seite enthält momentan noch keinen Text.
Sie können diesen Titel auf den anderen Seiten [[Special:Search/{{PAGENAME}}|suchen]]
oder in den zugehörigen <span class="plainlinks">[{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbüchern suchen]</span>.',
'userpage-userdoesnotexist'        => 'Das Benutzerkonto „$1“ ist nicht vorhanden. Bitte prüfen Sie, ob Sie diese Seite wirklich erstellen/bearbeiten möchten.',
'clearyourcache'                   => "'''Hinweis - Leeren Sie nach dem Speichern den Browser-Cache, um die Änderungen sehen zu können:''' '''Mozilla/Firefox/Safari:''' ''Shift'' gedrückt halten und auf ''Aktualisieren'' klicken oder alternativ entweder ''Strg-F5'' oder ''Strg-R'' (''Befehlstaste-R'' bei Macintosh) drücken; '''Konqueror: '''Auf ''Aktualisieren'' klicken oder ''F5'' drücken; '''Opera:''' Cache unter ''Extras → Einstellungen'' leeren; '''Internet Explorer:''' ''Strg-F5'' drücken oder ''Strg'' gedrückt halten und dabei auf ''Aktualisieren'' klicken.",
'usercssyoucanpreview'             => "'''Tipp:''' Benutzen Sie den „{{int:showpreview}}“-Button, um Ihr neues CSS vor dem Speichern zu testen.",
'userjsyoucanpreview'              => "'''Tipp:''' Benutzen Sie den „{{int:showpreview}}“-Button, um Ihr neues JavaScript vor dem Speichern zu testen.",
'usercsspreview'                   => "== Vorschau Ihres Benutzer-CSS ==
'''Hinweis:''' Nach dem Speichern müssen Sie Ihren Browser anweisen, die neue Version zu laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'                    => "== Vorschau Ihres Benutzer-JavaScript ==
'''Hinweis:''' Nach dem Speichern müssen Sie Ihren Browser anweisen, die neue Version zu laden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'            => "'''Warnung:''' Es existiert kein Skin „$1“. Bitte bedenken Sie, dass benutzerspezifische .css- und .js-Seiten mit einem Kleinbuchstaben anfangen müssen, also z.B. ''{{ns:user}}:Mustermann/monobook.css'' an Stelle von ''{{ns:user}}:Mustermann/Monobook.css''.",
'previewconflict'                  => 'Diese Vorschau gibt den Inhalt des oberen Textfeldes wieder. So wird die Seite aussehen, wenn Sie jetzt speichern.',
'session_fail_preview'             => "'''Ihre Bearbeitung konnte nicht gespeichert werden, da Sitzungsdaten verloren gegangen sind.
Bitte versuchen Sie es erneut, indem Sie unter der folgenden Textvorschau nochmals auf „Seite speichern“ klicken.
Sollte das Problem bestehen bleiben, [[Special:UserLogout|melden Sie sich ab]] und danach wieder an.'''",
'session_fail_preview_html'        => "'''Ihre Bearbeitung konnte nicht gespeichert werden, da Sitzungsdaten verloren gegangen sind.'''

''Da in {{SITENAME}} das Speichern von reinem HTML aktiviert ist, wurde die Vorschau ausgeblendet, um JavaScript-Attacken vorzubeugen.''

'''Bitte versuchen Sie es erneut, indem Sie unter der folgenden Textvorschau nochmals auf „Seite speichern“ klicken.
Sollte das Problem bestehen bleiben, [[Special:UserLogout|melden Sie sich ab]] und danach wieder an.'''",
'token_suffix_mismatch'            => "'''Ihre Bearbeitung wurde zurückgewiesen, da Ihr Browser Zeichen im Bearbeiten-Token verstümmelt hat.
Eine Speicherung kann den Seiteninhalt zerstören. Dies geschieht bisweilen durch die Benutzung eines anonymen Proxy-Dienstes, der fehlerhaft arbeitet.'''",
'explainconflict'                  => "Jemand anders hat diese Seite geändert, nachdem Sie angefangen haben diese zu bearbeiten.
Das obere Textfeld enthält den aktuellen Stand.
Das untere Textfeld enthält Ihre Änderungen.
Bitte fügen Sie Ihre Änderungen in das obere Textfeld ein.
'''Nur''' der Inhalt des oberen Textfeldes wird gespeichert, wenn Sie auf „Seite speichern“ klicken!",
'yourtext'                         => 'Ihr Text',
'nonunicodebrowser'                => "'''Achtung:''' Ihr Browser kann Unicode-Zeichen nicht richtig verarbeiten. Bitte verwenden Sie einen anderen Browser um Seiten zu bearbeiten.",
'editingold'                       => "'''ACHTUNG: Sie bearbeiten eine alte Version dieser Seite. Wenn Sie speichern, werden alle neueren Versionen überschrieben.'''",
'copyrightwarning'                 => "'''Bitte <big>kopieren Sie keine Webseiten</big>, die nicht Ihre eigenen sind, benutzen Sie <big>keine urheberrechtlich geschützten Werke</big> ohne Erlaubnis des Copyright-Inhabers!'''<br />
Sie geben uns hiermit Ihre Zusage, dass Sie den Text '''selbst verfasst''' haben, dass der Text Allgemeingut ('''public domain''') ist, oder dass der '''Copyright-Inhaber''' seine '''Zustimmung''' gegeben hat. Falls dieser Text bereits woanders veröffentlicht wurde, weisen Sie bitte auf der Diskussionsseite darauf hin.
<i>Bitte beachten Sie, dass alle {{SITENAME}}-Beiträge automatisch unter der „$2“ stehen (siehe $1 für Details). Falls Sie nicht möchten, dass Ihre Arbeit hier von anderen verändert und verbreitet wird, dann drücken Sie nicht auf „Seite speichern“.</i>",
'copyrightwarning2'                => "Bitte beachten Sie, dass alle Beiträge zu {{SITENAME}} von anderen Mitwirkenden bearbeitet, geändert oder gelöscht werden können.
Reichen Sie keine Texte ein, falls Sie nicht wollen, dass diese ohne Einschränkung geändert werden können.

Sie bestätigen hiermit auch, dass Sie diese Texte selbst geschrieben haben oder diese von einer gemeinfreien Quelle kopiert haben
(siehe $1 für weitere Details). '''ÜBERTRAGEN SIE OHNE GENEHMIGUNG KEINE URHEBERRECHTLICH GESCHÜTZTEN INHALTE!'''",
'longpagewarning'                  => "'''WARNUNG: Diese Seite ist $1 kB groß; einige Browser könnten Probleme haben, Seiten zu bearbeiten, die größer als 32 kB sind.
Überlegen Sie bitte, ob eine Aufteilung der Seite in kleinere Abschnitte möglich ist.'''",
'longpageerror'                    => "'''FEHLER: Den Text den Sie versucht haben zu speichern ist $1 KB groß. Das ist größer als das erlaubte Maximum von $2 KB – Speicherung nicht möglich.'''",
'readonlywarning'                  => "'''ACHTUNG: Die Datenbank wurde für Wartungsarbeiten gesperrt, so dass Ihre Änderungen derzeit nicht gespeichert werden können.
Sichern Sie den Text bitte lokal auf Ihrem Computer und versuchen Sie zu einem späteren Zeitpunkt, die Änderungen zu übertragen.'''

Grund für die Sperre: $1",
'nocreatetext'                     => 'Auf {{SITENAME}} wurde das Erstellen neuer Seiten eingeschränkt. Sie können bestehende Seiten ändern oder sich [[Special:UserLogin|anmelden]].',
'nocreate-loggedin'                => 'Sie haben keine Berechtigung, neue Seiten zu erstellen.',
'permissionserrorstext'            => 'Sie sind nicht berechtigt, die Aktion auszuführen. {{PLURAL:$1|Grund|Gründe}}:',
'permissionserrorstext-withaction' => 'Sie sind nicht berechtigt, $2.
{{PLURAL:$1|Grund|Gründe}}:',
'recreate-moveddeleted-warn'       => "'''Achtung: Sie erstellen eine Seite, die bereits früher gelöscht wurde.'''

Bitte prüfen Sie sorgfältig, ob die erneute Seitenerstellung den Richtlinien entspricht.
Zu Ihrer Information folgt das Lösch- und Verschiebungs-Logbuch mit der Begründung für die vorhergehende Löschung:",
'edit-no-change'                   => 'Ihre Bearbeitung wurde ignoriert, da keine Änderung an dem Text vorgenomme wurde.',

# "Undo" feature
'undo-success' => 'Die Bearbeitung kann rückgängig gemacht werden.
Bitte prüfen Sie den Vergleich unten um sicherzustellen, dass Sie dies tun möchten, und speicheren Sie dann unten Ihre Änderungen, um die Bearbeitung rückgängig zu machen.',

# Revision deletion
'rev-deleted-text-unhide'     => "Diese Version wurde '''gelöscht'''.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].
Einem Administrator können Sie [$1 diesen Link zur Version] nennen.",
'rev-suppressed-text-unhide'  => "Diese Version wurde '''unterdrückt'''.
Details stehen im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unterdrückungs-Logbuch].
Sie als Administrator können Sie [$1 diese Version einsehen], wenn Sie es wünschen.",
'rev-deleted-text-view'       => "Diese Version wurde '''gelöscht'''.
Als Administrator können Sie sie weiterhin einsehen.
Nähere Angaben zum Löschvorgang sowie eine Begründung finden sich im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-deleted-no-diff'         => "Sie können diesen Unterschied nicht betrachten, da eine der Versionen '''gelöscht''' wurde.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-deleted-unhide-diff'     => "Eine der Versionen dieses Unterschieds wurde '''gelöscht'''.
Details stehen im [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].
Einem Administrator können Sie [$1 diesen Link zum Versionsunterschied] nennen.",
'rev-deleted-diff-view'       => "Eine Version dieses Versionsunterschiedes wurde '''gelöscht'''.
Als Administrator können Sie diesen Versionsunterschied sehen. Details finden sich im [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Lösch-Logbuch].",
'rev-suppressed-diff-view'    => "Eine der Versionen dieses Versionsunterschiedes wurde '''unterdrückt'''.
Als Administrator können Sie diesen Versionsunterschied sehen. Details finden sich im [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unterdrückungs-Logbuch].",
'revdelete-nooldid-text'      => 'Sie haben entweder keine Version angegeben, auf die diese Aktion ausgeführt werden soll, die gewählte Version ist nicht vorhanden oder Sie versuchen, die aktuelle Version zu entfernen.',
'revdelete-show-file-confirm' => 'Sind Sie sicher, dass Sie die gelöschte Version der Datei „<nowiki>$1</nowiki>“ vom $2 um $3 Uhr ansehen wollen?',
'revdelete-confirm'           => 'Bitte bestätigen Sie, dass Sie beabsichtigen, dies zu tun, die Konsequenzen verstehen und es in Übereinstimmung mit den [[{{MediaWiki:Policy-url}}|Richtlinien]] tuen.',
'revdelete-show-no-access'    => 'Fehler beim Anzeigen des Eintrags vom $1, $2 Uhr: Dieser Eintrag wurde als „eingeschränkt“ markiert.
Sie haben darauf keinen Zugriff.',
'revdelete-modify-no-access'  => 'Fehler beim Bearbeiten des Eintrags vom $1, $2 Uhr: Dieser Eintrag wurde als „eingeschränkt“ markiert.
Sie haben darauf keinen Zugriff.',
'revdelete-concurrent-change' => 'Fehler beim Bearbeiten des Eintrags vom $1, $2 Uhr: Es scheint, als ob der Status von jemandem geändert wurde, bevor Sie vorhatten, ihn zu bearbeiten.
Bitte prüfen Sie die Logbücher.',
'revdelete-only-restricted'   => 'Fehler beim Verstecken des Eintrags vom $1, $2 Uhr: Sie können keinen Eintrag vor Administratoren verstecken, ohne eine der anderen Ansichtsoptionen gewählt zu haben.',

# History merging
'mergehistory-header' => 'Mit dieser Spezialseite können Sie die Versionsgeschichte einer Ursprungsseite mit der Versionsgeschichte einer Zielseite vereinen.
Stellen Sie sicher, dass die Versionsgeschichte einer Seite historisch korrekt ist.',
'mergehistory-merge'  => 'Die folgenden Versionen von „[[:$1]]“ können nach „[[:$2]]“ übertragen werden. Markieren Sie die Version, bis zu der (einschließlich) die Versionen übertragen werden sollen. Bitte beachten Sie, dass die Nutzung der Navigationslinks die Auswahl zurücksetzt.',
'mergehistory-fail'   => 'Versionsvereinigung nicht möglich, bitte prüfen Sie die Seite und die Zeitangaben.',

# Search results
'searchsubtitle'        => 'Ihre Suchanfrage: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|alle mit „$1“ beginnenden Seiten]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Seiten, die nach „$1“ verlinken]])',
'searchsubtitleinvalid' => 'Ihre Suchanfrage: „$1“.',
'toomanymatches'        => 'Es Anzahl der Suchergnisse ist zu groß, bitte versuchen Sie eine andere Abfrage.',
'search-suggest'        => 'Meinten Sie „$1“?',
'nonefound'             => "'''Hinweis:''' Es werden standardmäßig nur einige Namensräume durchsucht. Setzen Sie ''all:'' vor Ihren Suchbegriff, um alle Seiten (inkl. Diskussionsseiten, Vorlagen usw.) zu durchsuchen oder gezielt den Namen des zu durchsuchenden Namensraumes.",
'search-nonefound'      => 'Für Ihre Suchanfrage wurden keine Ergebnisse gefunden.',
'searchdisabled'        => 'Die {{SITENAME}} Suche wurde deaktiviert. Sie können unterdessen mit Google suchen. Bitte bedenken Sie, dass der Suchindex für {{SITENAME}} veraltet sein kann.',

# Preferences page
'prefsnologintext'           => 'Sie müssen <span class="plainlinks">[{{fullurl:{{#special:UserLogin}}|returnto=$1}} angemeldet]</span> sein, um Ihre Einstellungen ändern zu können.',
'prefs-help-watchlist-token' => 'Das Ausfüllen dieses Feldes mit einem geheimen Schlüssel generiert einen RSS-Feed für Ihre Beobachtungsliste.
Jeder, der diesen Schlüssel kennt, kann Ihre Beobachtungsliste einsehen. Wählen Sie also einen sicheren Wert.
Hier ein zufällig generierter Wert, den Sie verwenden können: $1',
'savedprefs'                 => 'Ihre Einstellungen wurden gespeichert.',
'prefs-reset-intro'          => 'Sie können diese Seite verwenden, um die Einstellungen auf die Standards zurückzusetzen.
Dies kann nicht mehr rückgängig gemacht werden.',
'prefs-help-realname'        => 'Optional. Damit kann Ihr bürgerlicher Name Ihren Beiträgen zugeordnet werden.',
'prefs-help-email'           => 'Die Angabe einer E-Mail ist optional, ermöglicht aber die Zusendung eines Ersatzpasswortes, wenn Sie Ihr Passwort vergessen haben.
Mit anderen Benutzer können Sie auch über die Benutzerdiskussionsseiten Kontakt aufnehmen, ohne dass Sie Ihre Identität offenlegen müssen.',

# User rights
'userrights-groups-help'      => 'Sie können die Gruppenzugehörigkeit für diesen Benutzer ändern.
* Ein markiertes Kästchen bedeutet, dass der Benutzer Mitglied dieser Gruppe ist.
* Ein * bedeutet, dass Sie das Benutzerrecht nach Erteilung nicht wieder zurücknehmen können (oder umgekehrt).',
'userrights-no-interwiki'     => 'Sie haben keine Berechtigung, Benutzerrechte in anderen Wikis zu ändern.',
'userrights-nologin'          => 'Sie müssen sich mit einem Administrator-Benutzerkonto [[Special:UserLogin|anmelden]], um Benutzerrechte zu ändern.',
'userrights-notallowed'       => 'Sie besitzen nicht die erforderlichen Berechtigungen, um Benutzerrechte zu vergeben.',
'userrights-changeable-col'   => 'Gruppenzugehörigkeit, die Sie ändern dürfen',
'userrights-unchangeable-col' => 'Gruppenzugehörigkeit, die Sie nicht ändern dürfen',

# Recent changes
'recentchangestext'              => "Auf dieser Seite können Sie die letzten Änderungen auf '''{{SITENAME}}''' nachverfolgen.",
'recentchanges-feed-description' => 'Verfolgen Sie mit diesem Feed die letzten Änderungen in {{SITENAME}}.',

# Recent changes linked
'recentchangeslinked-summary' => "Diese Spezialseite listet die letzten Änderungen an den verlinkten Seiten auf (bzw. bei Kategorien an den Mitgliedern dieser Kategorie).
Seiten auf Ihrer [[Special:Watchlist|Beobachtungsliste]] sind '''fett''' dargestellt.",

# Upload
'uploadnologintext'           => 'Sie müssen [[Special:UserLogin|angemeldet sein]], um Dateien hochladen zu können.',
'uploadtext'                  => "Benutzen Sie dieses Formular, um neue Dateien hochzuladen.

Gehen Sie zu der [[Special:FileList|Liste hochgeladener Dateien]], um vorhandene Dateien zu suchen und anzuzeigen. Siehe auch das [[Special:Log/upload|Datei-]] und [[Special:Log/delete|Lösch-Logbuch]].

Um ein '''Bild''' in einer Seite zu verwenden, nutzen Sie einen Link in der folgenden Form:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datei.jpg]]</nowiki></tt>''' – für ein Vollbild
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datei.png|200px|thumb|left|Alternativer Text]]</nowiki></tt>''' – für ein 200px breites Bild innerhalb einer Box, mit „Alternativer Text“ als Bildbeschreibung
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datei.ogg]]</nowiki></tt>''' – für einen direkten Link auf die Datei, ohne Darstellung der Datei",
'illegalfilename'             => 'Der Dateiname „$1“ enthält mindestens ein nicht erlaubtes Zeichen. Bitte benennen Sie die Datei um und versuchen Sie sie erneut hochzuladen.',
'emptyfile'                   => 'Die hochgeladene Datei ist leer. Der Grund kann ein Tippfehler im Dateinamen sein. Bitte kontrollieren Sie, ob Sie die Datei wirklich hochladen wollen.',
'fileexists'                  => "Eine Datei mit diesem Namen existiert bereits.
Wenn Sie auf „Datei speichern“ klicken, wird die Datei überschrieben.
Bitte prüfen Sie '''<tt>[[:$1]]</tt>''', wenn Sie sich nicht sicher sind.
[[$1|thumb]]",
'filepageexists'              => "Eine Beschreibungsseite wurde bereits als '''<tt>[[:$1]]</tt>''' erstellt, es ist aber keine Datei mit diesem Namen vorhanden.
Die eingegebene Beschreibung wird nicht auf die Beschreibungsseite übernommen.
Die Beschreibungsseite müssen Sie nach dem Hochladen der Datei noch manuell bearbeiten.
[[$1|thumb]]",
'fileexists-extension'        => "Eine Datei mit ähnlichem Namen existiert bereits: [[$2|thumb]]
* Name der hochzuladenden Datei: '''<tt>[[:$1]]</tt>'''
* Name der vorhandenen Datei: '''<tt>[[:$2]]</tt>'''
Bitte wählen Sie einen anderen Namen.",
'fileexists-thumbnail-yes'    => "Bei der Datei scheint es sich um ein Bild verringerter Größe ''(thumbnail)'' zu handeln. [[$1|thumb]]
Bitte prüfen Sie die Datei '''<tt>[[:$1]]</tt>'''.
Wenn es sich um das Bild in Originalgröße handelt, so braucht kein separates Vorschaubild hochgeladen zu werden.",
'file-thumbnail-no'           => "Der Dateiname beginnt mit '''<tt>$1</tt>'''. Dies deutet auf ein Bild verringerter Größe ''(thumbnail)'' hin.
Bitte prüfen Sie, ob Sie das Bild in voller Auflösung vorliegen haben und laden dieses unter dem Originalnamen hoch.",
'fileexists-forbidden'        => 'Unter diesem Namen existiert bereits eine Datei und sie kann nicht überschrieben werden. Bitte gehen Sie zurück und laden Sie die Datei unter einem anderen Namen hoch. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Unter diesem Namen existiert bereits eine Datei im zentralen Medienarchiv.
Wenn Sie diese Datei trotzdem hochladen möchten, gehen Sie bitte zurück und ändern den Namen.
[[File:$1|thumb|center|$1]]',
'file-deleted-duplicate'      => 'Eine identische Datei dieser Datei ([[$1]]) wurde früher gelöscht. Überprüfen Sie das Lösch-Logbuch, bevor Sie sie hochladen.',
'uploadwarning-text'          => 'Bitte ändern Sie unten die Dateibeschreibung und versuchen Sie es erneut.',
'php-uploaddisabledtext'      => 'Das Hochladen von Dateien wurde in PHP deaktiviert.
Bitte überprüfen Sie die <code>file_uploads</code>-Einstellung.',
'filewasdeleted'              => 'Eine Datei mit diesem Namen wurde schon einmal hochgeladen und zwischenzeitlich wieder gelöscht. Bitte prüfen Sie zuerst den Eintrag im $1, bevor Sie die Datei wirklich speichern.',
'upload-wasdeleted'           => "'''Achtung: Sie laden eine Datei hoch, die bereits früher gelöscht wurde.'''

Bitte prüfen Sie sorgfältig, ob das erneute Hochladen den Richtlinien entspricht.
Zu Ihrer Information folgt das Lösch-Logbuch mit der Begründung für die vorhergehende Löschung:",
'filename-bad-prefix'         => "Der Dateiname beginnt mit '''„$1“'''. Dies ist im allgemeinen der von einer Digitalkamera vorgegebene Dateiname und daher nicht sehr aussagekräftig.
Bitte geben Sie der Datei einen Namen, der den Inhalt besser beschreibt.",

'upload-file-error-text' => 'Bei der Erstellung einer temporären Datei auf dem Server ist ein interner Fehler aufgetreten.
Bitte informieren Sie einen [[Special:ListUsers/sysop|System-Administrator]].',
'upload-misc-error-text' => 'Beim Hochladen ist ein unbekannter Fehler aufgetreten.
Prüfen Sie die URL auf Fehler, den Online-Status der Seite und versuchem Sie erneut.
Wenn das Problem weiter besteht, informieren Sie einen [[Special:ListUsers/sysop|System-Administrator]].',

# img_auth script messages
'img-auth-nopathinfo' => 'PATH_INFO fehlt.
Ihr Server ist nicht dafür eingerichtet, diese Information weiterzugeben.
Es könnte CGI-basiert sein und unterstützt img_auth nicht.
Siehe http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-nologinnWL' => 'Sie sind nicht angemeldet und „$1“ ist nicht in der weißen Liste.',
'img-auth-isdir'      => 'Sie versuchen, auf ein Verzeichnis „$1“ zuzugreifen.
Nur Dateizugriff ist erlaubt.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6-text'  => 'Die angegebene URL ist nicht erreichbar. Prüfen Sie sowohl die URL auf Fehler als auch den Online-Status der Seite.',
'upload-curl-error28-text' => 'Die Seite braucht zu lange für eine Antwort. Prüfen Sie, ob die Seite online ist, warten Sie einen kurzen Moment und versuchen es dann erneut. Es kann sinnvoll sein, einen erneuten Versuch zu einem anderen Zeitpunkt zu probieren.',

'upload_source_file' => ' (eine Datei auf Ihrem Computer)',

# File description page
'filehist-help'        => 'Klicken Sie auf einen Zeitpunkt, um diese Version zu laden.',
'filepage-nofile-link' => 'Es existiert keine Datei mit diesem Namen, aber Sie können [$1 diese Datei hochladen].',

# File reversion
'filerevert-intro' => "Sie setzen die Datei '''[[Media:$1|$1]]''' auf die [$4 Version vom $2, $3 Uhr] zurück.",

# File deletion
'filedelete-intro'     => "Sie löschen die Datei '''„[[Media:$1|$1]]“''' inklusive ihrer Versionsgeschichte.",
'filedelete-intro-old' => "Sie löschen von der Datei '''„[[Media:$1|$1]]“''' die [$4 Version vom $2, $3 Uhr].",

# Unused templates
'unusedtemplatestext' => 'Diese Seite listet alle Seiten im {{ns:template}}-Namensraum auf, die nicht in anderen Seiten eingebunden sind.
Überprüfen Sie andere Links zu den Vorlagen, bevor Sie diese löschen.',

# Miscellaneous special pages
'unusedimagestext' => 'Bitte beachten Sie, dass andere Webseiten eine Datei mit einer direkten URL verlinken können. Sie könnte daher hier aufgelistet sein, obwohl sie in aktiver Verwendung ist.',
'notargettext'     => 'Sie haben nicht angegeben, auf welche Seite diese Funktion angewendet werden soll.',

# Book sources
'booksources-invalid-isbn' => 'Vermutlich ist die ISBN ungültig.
Bitte prüfen Sie, ob korrekt von der Quelle übertragen wurde.',

# E-mail user
'mailnologintext' => 'Sie müssen [[Special:UserLogin|angemeldet sein]] und eine bestätigte E-Mail-Adresse in Ihren [[Special:Preferences|Einstellungen]] eingetragen haben, um anderen Benutzern E-Mails schicken zu können.',
'emailpagetext'   => 'Sie könnent dem Benutzer mit dem unten stehenden Formular eine E-Mail senden.
Als Absender wird die E-Mail-Adresse aus ihren [[Special:Preferences|Einstellungen]] eingetragen, damit der Benutzer Ihnen antworten kann.',
'emailccsubject'  => 'Kopie Ihrer Nachricht an $1: $2',
'emailsenttext'   => 'Ihre E-Mail wurde verschickt.',

# Watchlist
'nowatchlist'       => 'Sie haben keine Einträge auf Ihrer Beobachtungsliste.',
'watchlistanontext' => 'Sie müssen sich $1, um Ihre Beobachtungsliste zu sehen oder Einträge auf ihr zu bearbeiten.',
'watchnologin'      => 'Sie sind nicht angemeldet',
'watchnologintext'  => 'Sie müssen [[Special:UserLogin|angemeldet]] sein, um Ihre Beobachtungsliste zu bearbeiten.',
'addedwatchtext'    => 'Die Seite „[[:$1]]“ wurde zu Ihrer [[Special:Watchlist|Beobachtungsliste]] hinzugefügt.

Spätere Änderungen an dieser Seite und der dazugehörigen Diskussionsseite werden dort gelistet und
in der Übersicht der [[Special:RecentChanges|letzten Änderungen]] in Fettschrift dargestellt.

Wenn Sie die Seite wieder von Ihrer Beobachtungsliste entfernen möchten, klicken Sie auf der jeweiligen Seite auf „{{int:Unwatch}}“.',
'removedwatchtext'  => 'Die Seite „[[:$1]]“ wurde von Ihrer [[Special:Watchlist|Beobachtungsliste]] entfernt.',
'watchnochange'     => 'Keine der von Ihnen beobachteten Seiten wurde während des angezeigten Zeitraums bearbeitet.',
'watchlist-details' => 'Sie beobachten {{PLURAL:$1|1 Seite|$1 Seiten}}.',
'watchlistcontains' => 'Ihre Beobachtungsliste enthält $1 {{PLURAL:$1|Seite|Seiten}}.',

'enotif_body' => 'Werter $WATCHINGUSERNAME,

die {{SITENAME}}-Seite „$PAGETITLE“ wurde von $PAGEEDITOR am $PAGEEDITDATE um $PAGEEDITTIME Uhr $CHANGEDORCREATED.

Aktuelle Version: $PAGETITLE_URL

$NEWPAGE

Zusammenfassung des Bearbeiters: $PAGESUMMARY $PAGEMINOREDIT

Kontakt zum Bearbeiter:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Es werden solange keine weiteren Benachrichtigungs-E-Mails gesendet, bis Sie die Seite wieder besucht haben. Auf Ihrer Beobachtungsliste können Sie alle Benachrichtigungsmarker zusammen zurücksetzen.

             Das freundliche {{SITENAME}}-Benachrichtigungssystem

--
Um die Einstellungen Ihrer Beobachtungsliste anzupassen, besuchen Sie: {{fullurl:{{#special:Watchlist}}/edit}}

Um die Seite von Ihrer Beobachtungsliste zu entfernen, besuchen Sie $UNWATCHURL

Rückmeldungen und weitere Hilfe: {{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'historywarning'    => "'''Achtung:''' Die Seite, die Sie löschen möchten, hat eine Versionsgeschichte mit etwa $1 {{PLURAL:$1|Version|Versionen}}:",
'confirmdeletetext' => 'Sie sind dabei, eine Seite mit allen zugehörigen älteren Versionen zu löschen. Bitte bestätigen Sie, dass Sie sich der Konsequenzen bewusst sind, und dass Sie in Übereinstimmung mit den [[{{MediaWiki:Policy-url}}|Richtlinien]] handeln.',
'deletedtext'       => '„<nowiki>$1</nowiki>“ wurde gelöscht. Im $2 finden Sie eine Liste der letzten Löschungen.',

# Rollback
'sessionfailure' => 'Es gab ein Problem mit der Übertragung Ihrer Benutzerdaten.
Diese Aktion wurde daher sicherheitshalber abgebrochen, um eine falsche Zuordnung Ihrer Änderungen zu einem anderen Benutzer zu verhindern.
Bitte gehen Sie zurück und versuchen Sie den Vorgang erneut auszuführen.',

# Protect
'protect-text'           => 'Hier können Sie den Schutzstatus für die Seite „$1“ einsehen und ändern.',
'protect-locked-blocked' => "Sie können den Seitenschutz nicht ändern, da Ihr Benutzerkonto gesperrt ist. Hier sind die aktuellen Seitenschutz-Einstellungen für die Seite '''„$1“:'''",
'protect-locked-access'  => "Ihr Benutzerkonto verfügt nicht über die notwendigen Rechte zur Änderung des Seitenschutzes. Hier sind die aktuellen Seitenschutzeinstellungen für die Seite '''„$1“:'''",
'protect-cantedit'       => 'Sie können die Sperre dieser Seite nicht ändern, da Sie keine Berechtigung zum Bearbeiten der Seite haben.',

# Undelete
'undeleteextrahelp'          => '* Um die Seite komplett mit allen Versionen wiederherzustellen, wählen Sie keine Version aus, geben Sie eine Begründung an und klicken Sie auf „Wiederherstellen“.
* Möchten Sie nur bestimmte Versionen wiederherstellen, so wählen Sie diese bitte einzeln anhand der Markierungen aus, geben eine Begründung an und klicken dann auf „Wiederherstellen“.
* „Abbrechen“ leert das Kommentarfeld und entfernt alle Markierungen bei den Versionen.',
'undeletehistory'            => 'Wenn Sie diese Seite wiederherstellen, werden auch alle alten Versionen wiederhergestellt.
Wenn seit der Löschung eine neue Seite gleichen Namens erstellt wurde, werden die wiederhergestellten Versionen chronologisch in die Versionsgeschichte eingeordnet.',
'undeleterevdel'             => 'Die Wiederherstellung wird nicht durchgeführt, wenn die aktuellste Version versteckt ist oder versteckte Teile enthält.
In diesem Fall darf die aktuellste Version nicht markiert werden oder ihr Status muss auf den einer normalen Version geändert werden.
Versionen von Dateien, auf die Sie keinen Zugriff haben, werden nicht wiederhergestellt.',
'undeletedpage'              => "'''$1''' wurde wiederhergestellt.

Im [[Special:Log/delete|Lösch-Logbuch]] finden Sie eine Übersicht der gelöschten und wiederhergestellten Seiten.",
'undelete-show-file-confirm' => 'Sind Sie sicher, dass Sie eine gelöschte Version der Datei „<nowiki>$1</nowiki>“ vom $2, $3 Uhr sehen wollen?',

# Block/unblock
'blockiptext'              => 'Mit diesem Formular sperren Sie eine IP-Adresse oder einen Benutzernamen, so dass von dort keine Änderungen mehr vorgenommen werden können.
Dies sollte nur erfolgen, um Vandalismus zu verhindern und in Übereinstimmung mit den [[{{MediaWiki:Policy-url}}|Richtlinien]].
Bitte geben Sie den Grund für die Sperre an.',
'unblockiptext'            => 'Mit diesem Formular können Sie eine IP-Adresse oder einen Benutzer freigeben.',
'autoblocker'              => 'Automatische Sperre, da Sie eine gemeinsame IP-Adresse mit [[User:$1|$1]] benutzen. Grund der Benutzersperre: „$2“.',
'ipb-needreblock'          => '== Sperre vorhanden ==
„$1“ ist bereits gesperrt. Möchten Sie die Sperrparameter ändern?',
'proxyblockreason'         => 'Ihre IP-Adresse wurde gesperrt, da sie ein offener Proxy ist. Bitte kontaktieren Sie Ihren Internet-Provider oder Ihre Systemadministratoren und informieren Sie sie über dieses mögliche Sicherheitsproblem.',
'cant-block-while-blocked' => 'Sie können keine anderen Benutzer sperren, während Sie selbst gesperrt sind.',
'cant-see-hidden-user'     => 'Der Benutzer, den Sie versuchen zu sperren, wurde bereits gesperrt und verborgen. Da Sie das „hideuser“-Recht nicht haben, können Sie die Benutzersperre nicht sehen und nicht bearbeiten.',

# Developer tools
'locknoconfirm'     => 'Sie haben das Bestätigungsfeld nicht markiert.',
'lockdbsuccesstext' => 'Die {{SITENAME}}-Datenbank wurde gesperrt.<br />Bitte geben Sie die Datenbank [[Special:UnlockDB|wieder frei]], sobald die Wartung abgeschlossen ist.',

# Move page
'movepagetext'           => "Mit diesem Formular können Sie eine Seite umbenennen (mitsamt allen Versionen).
Der alte Titel wird zum neuen weiterleiten.
Sie können Weiterleitungen, die auf den Originaltitel verlinken, automatisch korrigieren lassen.
Falls Sie dies nicht tun, prüfen Sie auf [[Special:DoubleRedirects|doppelte]] oder [[Special:BrokenRedirects|kaputte Weiterleitungen]].
Sie sind dafür verantwortlich, dass Links weiterhin auf das korrekte Ziel zeigen.

Die Seite wird '''nicht''' verschoben, wenn es bereits eine Seite mit demselben Namen gibt, sofern diese nicht leer oder eine Weiterleitung ohne Versionsgeschichte ist. Dies bedeutet, dass Sie die Seite zurück verschieben können, wenn Sie einen Fehler gemacht haben. Sie können hingegen keine Seite überschreiben.

'''Warnung'''
Die Verschiebung kann weitreichende und unerwartete Folgen für beliebte Seiten haben.
Sie sollten daher die Konsequenzen verstanden haben, bevor Sie fortfahren.",
'movepagetalktext'       => "Die dazugehörige Diskussionsseite wird, sofern vorhanden, mitverschoben, '''es sei denn:'''
*Es existiert bereits eine Diskussionsseite mit diesem Namen, oder
*Sie wählen die unten stehende Option ab.

In diesen Fällen müssen Sie, falls gewünscht, den Inhalt der Seite von Hand verschieben oder zusammenführen.

Bitte den '''neuen''' Titel unter '''Ziel''' eintragen, darunter die Umbenennung bitte '''begründen.'''",
'moveuserpage-warning'   => "'''Warnung:''' Sie sind dabei, eine Benutzerseite zu verschieben. Bitte bedenken Sie, dass dadurch nur die Benutzerseite verschoben, '''nicht''' aber der Benutzer umbenannt wird.",
'movenologin'            => 'Sie sind nicht angemeldet',
'movenologintext'        => 'Sie müssen ein registrierter Benutzer und [[Special:UserLogin|angemeldet]] sein, um eine Seite zu verschieben.',
'movenotallowed'         => 'Sie haben keine Berechtigung, Seiten zu verschieben.',
'movenotallowedfile'     => 'Sie haben keine Berechtigung, Dateien zu verschieben.',
'cant-move-user-page'    => 'Sie haben keine Berechtigung, Benutzerhauptseiten zu verschieben.',
'cant-move-to-user-page' => 'Sie haben nicht die Berechtigung, Seiten auf eine Benutzerseite zu verschieben (mit Ausnahme von Benutzerunterseiten).',
'articleexists'          => 'Unter diesem Namen existiert bereits eine Seite. Bitte wählen Sie einen anderen Namen.',
'talkexists'             => 'Die Seite selbst wurde erfolgreich verschoben, aber die zugehörige Diskussionsseite nicht, da bereits eine mit dem neuen Titel existiert. Bitte gleichen Sie die Inhalte von Hand ab.',
'delete_and_move_text'   => '== Löschung erforderlich ==

Die Seite „[[:$1]]“ existiert bereits. Möchten Sie diese löschen, um die Seite verschieben zu können?',
'file-exists-sharedrepo' => 'Der gewählte Dateiname wird bereits in einem gemeinsam genutzten Repositorium verwendet.
Bitte wählen Sie einen anderen Namen.',

# Export
'exporttext' => 'Mit dieser Spezialseite können Sie den Text inklusive der Versionsgeschichte einzelner Seiten in eine XML-Datei exportieren.
Die Datei kann in ein anderes MediaWiki-Wiki über die [[Special:Import|Importfunktion]] eingespielt werden.

Tragen Sie den oder die entsprechenden Seitentitel in das folgende Textfeld ein (pro Zeile jeweils nur für eine Seite).

Alternativ ist der Export auch mit der Syntax [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] möglich, beispielsweise für die [[{{MediaWiki:Mainpage}}]].',

# Namespace 8 related
'allmessagestext' => 'Dies ist eine Liste der MediaWiki-Systemtexte.
Besuchen Sie die Seiten [http://www.mediawiki.org/wiki/Localisation MediaWiki-Lokalisierung] und [http://translatewiki.net translatewiki.net], wenn Sie sich an der Lokalisierung von MediaWiki beteiligen möchten.',

# Special:Import
'import-interwiki-text' => 'Wählen Sie ein Wiki und eine Seite zum Importieren aus.
Die Versionsdaten und Benutzernamen bleiben dabei erhalten.
Alle Transwiki-Import-Aktionen werden im [[Special:Log/import|Import-Logbuch]] protokolliert.',
'import-token-mismatch' => 'Verlust der Sessiondaten. Bitte versuchen Sie es erneut.',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Ihre Benutzerseite',
'tooltip-pt-anonuserpage'        => 'Benutzerseite der IP-Adresse, von der aus Sie Änderungen durchführen',
'tooltip-pt-mytalk'              => 'Ihre Diskussionsseite',
'tooltip-pt-mycontris'           => 'Liste Ihrer Beiträge',
'tooltip-n-mainpage'             => 'Besuchen Sie die Hauptseite',
'tooltip-n-mainpage-description' => 'Besuchen Sie die Hauptseite',
'tooltip-n-portal'               => 'Über das Portal, was Sie tun können, wo was zu finden ist',
'tooltip-watch'                  => 'Fügt diese Seite Ihrer Beobachtungsliste hinzu',

# Metadata
'notacceptable' => 'Der Wiki-Server kann die Daten nicht für Ihr Ausgabegerät aufbereiten.',

# Spam protection
'spamprotectiontext' => 'Die Seite, die Sie speichern wollen, wurde vom Spamschutzfilter blockiert. Das liegt wahrscheinlich an einem Link auf eine externe Seite.',

# Patrolling
'markedaspatrollederrortext' => 'Sie müssen eine Seitenänderung auswählen.',

# Media information
'mediawarning' => "'''Warnung:''' Dieser Dateityp kann böswilligen Programmcode enthalten.
Durch das Herunterladen und Öffnen der Datei kann Ihr Computer beschädigt werden.<hr />",

# E-mail address confirmation
'confirmemail_noemail'    => 'Sie haben keine gültige E-Mail-Adresse in Ihren [[Special:Preferences|persönlichen Einstellungen]] eingetragen.',
'confirmemail_text'       => '{{SITENAME}} erfordert, dass Sie Ihre E-Mail-Adresse bestätigen (authentifizieren), bevor Sie die erweiterten E-Mail-Funktionen benutzen können. Klicken Sie bitte auf die unten stehende, mit „Bestätigungscode zuschicken“ beschriftete Schaltfläche, damit eine automatisch erstellte E-Mail an die angegebene Adresse geschickt wird. Diese E-Mail enthält eine Web-Adresse mit einem Bestätigungscode. Indem Sie diese Webseite in Ihrem Webbrowser öffnen, bestätigen Sie, dass die angegebene E-Mail-Adresse korrekt und gültig ist.',
'confirmemail_pending'    => 'Es wurde Ihnen bereits ein Bestätigungscode per E-Mail zugeschickt.
Wenn Sie Ihr Benutzerkonto erst vor kurzem erstellt haben, warten Sie bitte noch ein paar Minuten auf die E-Mail, bevor Sie einen neuen Code anfordern.',
'confirmemail_oncreate'   => 'Ein Bestätigungs-Code wurde an Ihre E-Mail-Adresse gesandt. Dieser Code wird für die Anmeldung nicht benötigt, jedoch wird er zur Aktivierung der E-Mail-Funktionen innerhalb des Wikis gebraucht.',
'confirmemail_sendfailed' => '{{SITENAME}} konnte die Bestätigungs-E-Mail nicht an Sie versenden.
Bitte prüfen Sie die E-Mail-Adresse auf ungültige Zeichen.

Rückmeldung des Mailservers: $1',
'confirmemail_invalid'    => 'Ungültiger Bestätigungscode. Möglicherweise ist der Bestätigungszeitraum verstrichen. Versuchen Sie bitte, die Bestätigung zu wiederholen.',
'confirmemail_needlogin'  => 'Sie müssen sich $1, um Ihre E-Mail-Adresse zu bestätigen.',
'confirmemail_success'    => 'Ihre E-Mail-Adresse wurde erfolgreich bestätigt. Sie können sich jetzt [[Special:UserLogin|anmelden]].',
'confirmemail_loggedin'   => 'Ihre E-Mail-Adresse wurde erfolgreich bestätigt.',
'confirmemail_error'      => 'Es gab einen Fehler bei der Bestätigung Ihrer E-Mail-Adresse.',
'confirmemail_body'       => 'Hallo,

jemand mit der IP-Adresse $1, wahrscheinlich Sie selbst, hat das Benutzerkonto „$2“ in {{SITENAME}} registriert.

Um die E-Mail-Funktion für {{SITENAME}} (wieder) zu aktivieren und um zu bestätigen, 
dass dieses Benutzerkonto wirklich zu Ihrer E-Mail-Adresse und damit zu Ihnen gehört, öffnen Sie bitte die folgende Web-Adresse:

$3

Sollte die vorstehende Adresse in Ihrem E-Mail-Programm über mehrere Zeilen gehen, müssen Sie diese eventuell per Hand in die Adresszeile Ihres Web-Browsers einfügen. 

Wenn Sie das genannte Benutzerkonto *nicht* registriert haben, folgen Sie diesem Link, um den Bestätigungsprozess abzubrechen:

$5

Dieser Bestätigungscode ist gültig bis $6, $7 Uhr.',

# Delete conflict
'deletedwhileediting' => 'Achtung: Diese Seite wurde gelöscht, nachdem Sie angefangen haben sie zu bearbeiten!
Im [{{fullurl:{{#special:Log}}|type=delete&page={{FULLPAGENAMEE}}}} Lösch-Logbuch] finden Sie den Grund für die Löschung.
Wenn Sie die Seite speichern, wird sie neu angelegt.',
'confirmrecreate'     => "Benutzer [[User:$1|$1]] ([[User talk:$1|Diskussion]]) hat diese Seite gelöscht, nachdem Sie angefangen haben, sie zu bearbeiten. Die Begründung lautete:
: ''$2''
Bitte bestätigen Sie, dass Sie diese Seite wirklich neu erstellen möchten.",

# Watchlist editor
'watchlistedit-numitems'       => 'Ihre Beobachtungsliste enthält {{PLURAL:$1|1 Eintrag |$1 Einträge}}, Diskussionsseiten werden nicht gezählt.',
'watchlistedit-noitems'        => 'Ihre Beobachtungsliste ist leer.',
'watchlistedit-normal-explain' => 'Dies sind die Einträge Ihrer Beobachtungsliste. Um Einträge zu entfernen, markieren Sie die Kästchen neben den Einträgen und klicken Sie am Ende der Seite auf „{{int:Watchlistedit-normal-submit}}“. Sie können Ihre Beobachtungsliste auch im [[Special:Watchlist/raw|Listenformat bearbeiten]].',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Eintrag wurde|$1 Einträge wurden}} von Ihrer Beobachtungsliste entfernt:',
'watchlistedit-raw-explain'    => 'Dies sind die Einträge Ihrer Beobachtungsliste im Listenformat. Die Einträge können zeilenweise gelöscht oder hinzugefügt werden.
Pro Zeile ist ein Eintrag erlaubt. Wenn Sie fertig sind, klicken Sie auf „{{int:Watchlistedit-raw-submit}}“.
Sie können auch die [[Special:Watchlist/edit|Standard-Bearbeitungsseite]] benutzen.',
'watchlistedit-raw-done'       => 'Ihre Beobachtungsliste wurde gespeichert.',

# Database error messages
'dberr-again'     => 'Warten Sie einige Minuten und versuchen Sie dann neu zuladen.',
'dberr-usegoogle' => 'Sie könnten in der Zwischenzeit mit Google suchen.',
'dberr-outofdate' => 'Beachten Sie, dass der Suchindex unserer Inhalte bei Google veraltet sein kann.',

# Add categories per AJAX
'ajax-confirm-prompt' => 'Sie können unten eine Zusammenfassung eingeben.
Klicken Sie „Speichern“ um die Bearbeitung zu speichern.',

);
