<?php
/** Nederlands (informeel)‎ (Nederlands (informeel)‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author MarkvA
 * @author Siebrand
 * @author Tedjuh10
 */

$fallback = 'nl';

$messages = array(
'view-pool-error' => 'Sorry, de servers zijn op het moment overbelast.
Te veel gebruikers proberen deze pagina te bekijken.
Wacht alstublieft even voordat je opnieuw toegang probeert te krijgen tot deze pagina.

$1',

'badaccess-group0' => 'Je hebt geen rechten om de gevraagde handeling uit te voeren.',

'youhavenewmessages'      => 'Je hebt $1 ($2).',
'youhavenewmessagesmulti' => 'Je hebt nieuwe berichten op $1',

# Main script and global functions
'nosuchactiontext'  => 'De opdracht in de URL is ongeldig.
Mogelijk heb je een typefout gemaakt in de URL of een onjuiste verwijzing gevolgd.
Het kan ook wijzen op een fout in de software van {{SITENAME}}.',
'nospecialpagetext' => '<strong>Je hebt een onbestaande speciale pagina opgevraagd.</strong>

Een lijst met bestaande speciale pagina’s staat op [[Special:SpecialPages|speciale pagina’s]].',

# General errors
'missing-article'     => 'In de database is geen inhoud aangetroffen voor de pagina "$1" die er wel zou moeten zijn ($2).

Dit kan voorkomen als je een verouderde verwijzing naar het verschil tussen twee versies van een pagina volgt of een versie opvraagt die is verwijderd.

Als dit niet het geval is, heb je wellicht een fout in de software gevonden.
Maak hiervan melding bij een [[Special:ListUsers/sysop|systeembeheerder]] van {{SITENAME}} en vermeld daarbij de URL van deze pagina.',
'actionthrottledtext' => 'Als maatregel tegen spam is het aantal keren per tijdseenheid dat je deze handeling kunt verrichten beperkt.
De limiet is overschreden.
Probeer het over een aantal minuten opnieuw.',
'viewsourcetext'      => 'Je kunt de brontekst van deze pagina bekijken en kopiëren:',
'editinginterface'    => "'''Waarschuwing:''' Je bewerkt een pagina die gebruikt wordt door de software.
Bewerkingen op deze pagina beïnvloeden de gebruikersinterface van iedereen.
Overweeg voor vertalingen om [//translatewiki.net/wiki/Main_Page?setlang=nl translatewiki.net] te gebruiken, het vertalingsproject voor MediaWiki.",
'namespaceprotected'  => "Je hebt geen rechten om pagina's in de naamruimte '''$1''' te bewerken.",

# Login and logout pages
'logouttext'                 => "'''Je bent nu afgemeld.'''

Je kunt {{SITENAME}} nu anoniem gebruiken of weer [[Special:UserLogin|aanmelden]] als dezelfde of een andere gebruiker.
Mogelijk worden nog een aantal pagina's weergegeven alsof je aangemeld bent totdat je de cache van uw browser leegt.",
'welcomecreation'            => '== Welkom, $1! ==
Je gebruiker is geregistreerd.
Vergeet niet je [[Special:Preferences|voorkeuren voor {{SITENAME}}]] aan te passen.',
'yourpasswordagain'          => 'Geef je wachtwoord opnieuw in:',
'yourdomainname'             => 'Je domein:',
'externaldberror'            => 'Er is een fout opgetreden bij het aanmelden bij de database of je hebt geen toestemming uw externe gebruiker bij te werken.',
'loginprompt'                => 'Je moet cookies ingeschakeld hebben om je te kunnen aanmelden bij {{SITENAME}}.',
'gotaccount'                 => "Heb je al een gebruikersnaam? '''$1'''.",
'nocookiesnew'               => 'De gebruiker is geregistreerd, maar niet aangemeld.
{{SITENAME}} gebruikt cookies voor het aanmelden van gebruikers.
Schakel die in en meld daarna aan met je nieuwe gebruikersnaam en wachtwoord.',
'nocookieslogin'             => '{{SITENAME}} gebruikt cookies voor het aanmelden van gebruikers.
Cookies zijn uitgeschakeld in je browser.
Schakel deze optie aan en probeer het opnieuw.',
'noname'                     => 'Je hebt geen geldige gebruikersnaam opgegeven.',
'loginsuccess'               => "'''Je bent nu aangemeld bij {{SITENAME}} als \"\$1\".'''",
'nouserspecified'            => 'Je dient een gebruikersnaam op te geven.',
'password-name-match'        => 'Je wachtwoord en uw gebruikersnaam mogen niet overeenkomen.',
'passwordremindertext'       => 'Iemand, waarschijnlijk jijzelf, heeft vanaf IP-adres $1 een verzoek
gedaan tot het toezenden van een nieuw wachtwoord voor {{SITENAME}}
($4). Er is een tijdelijk wachtwoord aangemaakt voor gebruiker "$2":
"$3". Als dat je bedoeling was, meld je dan nu aan en kies een nieuw
wachtwoord.
Je tijdelijke wachtwoord vervalt over {{PLURAL:$5|$5 dag|$5 dagen}}.

Als iemand anders dan jij dit verzoek heeft gedaan of als je zich inmiddels het
wachtwoord herinnert en het niet langer wilt wijzigen, negeer dit bericht
dan en blijf je bestaande wachtwoord gebruiken.',
'noemailcreate'              => 'Je moet een geldig e-mailadres opgeven',
'passwordsent'               => 'Het wachtwoord is verzonden naar het e-mailadres voor "$1".
Meld je aan nadat je het hebt ontvangen.',
'blocked-mailpassword'       => 'Je IP-adres is geblokkeerd voor het maken van wijzigingen.
Om misbruik te voorkomen is het niet mogelijk om een nieuw wachtwoord aan te vragen.',
'eauthentsent'               => 'Er is een bevestigingse-mail naar het opgegeven e-mailadres gezonden.
Volg de aanwijzingen in de e-mail om aan te geven dat het jouw e-mailadres is.
Tot die tijd kunnen er geen e-mails naar het e-mailadres gezonden worden.',
'acct_creation_throttle_hit' => 'Bezoekers van deze wiki met hetzelfde IP-adres als jij hebben de afgelopen dag {{PLURAL:$1|al 1 gebruiker|al $1 gebruikers}} geregistreerd, wat het maximale aantal in deze periode is.
Daarom kun je als vanaf jouw IP-adres op dit moment geen nieuwe gebruiker registreren.',
'emailauthenticated'         => 'Je e-mailadres is bevestigd op $2 om $3.',
'emailnotauthenticated'      => 'Je e-mailadres is <strong>niet bevestigd</strong>.
Je ontvangt geen e-mail voor de onderstaande functies.',
'noemailprefs'               => 'Geef een e-mailadres op in je voorkeuren om deze functies te gebruiken.',
'emailconfirmlink'           => 'Bevestig je e-mailadres',
'createaccount-text'         => 'Iemand heeft een gebruiker op {{SITENAME}} ($4) aangemaakt met de naam "$2" en jouw e-mailadres.
Het wachtwoord voor "$2" is "$3".
Meld je aan en wijzig je wachtwoord.

Negeer dit bericht als deze gebruiker zonder jouw medeweten is aangemaakt.',
'login-throttled'            => 'Je hebt recentelijk te vaak geprobeerd aan te melden met een onjuist wachtwoord.
Wacht even voordat je het opnieuw probeert.',
'suspicious-userlogout'      => 'Je verzoek om af te melden is genegeerd, omdat het lijkt alsof het verzoek is verzonden door een browser of cacheproxy die stuk is.',

# Change password dialog
'resetpass_announce'      => 'Je bent aangemeld met een tijdelijke code die je per e-mail is toegezonden.
Voer een nieuw wachtwoord in om het aanmelden te voltooien:',
'resetpass_success'       => 'Je wachtwoord is gewijzigd.
Bezig met aanmelden…',
'resetpass-no-info'       => 'Je dient aangemeld zijn voordat je deze pagina kunt gebruiken.',
'resetpass-wrong-oldpass' => 'Het huidige of tijdelijke wachtwoord is ongeldig.
Mogelijk heb je je wachtwoord al gewijzigd of een nieuw tijdelijk wachtwoord aangevraagd.',

# Edit page toolbar
'sig_tip' => 'Je handtekening met datum en tijd',

# Edit pages
'anoneditwarning'                  => "'''Waarschuwing:''' je bent niet aangemeld.
Je IP-adres wordt opgeslagen als je wijzigingen op deze pagina maakt.",
'anonpreviewwarning'               => "''Je bent niet aangemeld.''
''Door je bewerking op te slaan wordt je IP-adres opgeslagen in de paginageschiedenis.''",
'missingsummary'                   => "'''Herinnering:''' je hebt geen samenvatting opgegeven voor je bewerking.
Als je nogmaals op ''Pagina opslaan'' klikt wordt de bewerking zonder samenvatting opgeslagen.",
'missingcommenttext'               => 'Plaats je opmerking hieronder.',
'missingcommentheader'             => "'''Let op:''' Je hebt geen onderwerp/kop voor deze opmerking opgegeven.
Als je opnieuw op \"{{int:savearticle}}\" klikt, wordt je wijziging zonder een onderwerp/kop opgeslagen.",
'blockedtext'                      => '\'\'\'Je gebruiker of IP-adres is geblokkeerd.\'\'\'

De blokkade is uitgevoerd door $1.
De opgegeven reden is \'\'$2\'\'.

* Aanvang blokkade: $8
* Einde blokkade: $6
* Bedoeld te blokkeren: $7

Je kunt contact opnemen met $1 of een andere [[{{MediaWiki:Grouppage-sysop}}|beheerder]] om de blokkade te bespreken.
Je kunt geen gebruik maken van de functie "Deze gebruiker e-mailen", tenzij je een geldig e-mailadres hebt opgegeven in uw [[Special:Preferences|voorkeuren]] en het gebruik van deze functie niet geblokkeerd is.
Je huidige IP-adres is $3 en het blokkadenummer is #$5.
Vermeld alle bovenstaande gegevens als je ergens op deze blokkade reageert.',
'autoblockedtext'                  => 'Je IP-adres is automatisch geblokkeerd, omdat het is gebruikt door een andere gebruiker, die is geblokkeerd door $1.
De opgegeven reden is:

:\'\'$2\'\'

* Aanvang blokkade: $8
* Einde blokkade: $6
* Bedoeld te blokkeren: $7

Je kunt deze blokkade bespreken met $1 of een andere [[{{MediaWiki:Grouppage-sysop}}|beheerder]].

Je kunt geen gebruik maken van de functie "Deze gebruiker e-mailen", tenzij je een geldig e-mailadres hebt opgegeven in je [[Special:Preferences|voorkeuren]] en het gebruik van deze functie niet is geblokkeerd.

Je huidige IP-adres is $3 en het blokkadenummer is #$5.
Vermeld alle bovenstaande gegevens als je ergens op deze blokkade reageert.',
'whitelistedittext'                => "Je moet $1 om pagina's te bewerken.",
'confirmedittext'                  => 'Je moet je e-mailadres bevestigen voor je kunt bewerken.
Voer je e-mailadres in en bevestig het via [[Special:Preferences|je voorkeuren]].',
'nosuchsectiontext'                => 'Je probeerde een subkopje te bewerken dat niet bestaat.
Wellicht is het verplaatst of verwijderd terwijl je de pagina aan het bekijken was.',
'loginreqpagetext'                 => "Je moet je $1 om andere pagina's te kunnen bekijken.",
'newarticletext'                   => "Deze pagina bestaat niet.
Typ in het onderstaande veld om de pagina aan te maken (meer informatie staat op de [[{{MediaWiki:Helppage}}|hulppagina]]).
Gebruik de knop '''vorige''' in je browser als je hier per ongeluk terecht bent gekomen.",
'anontalkpagetext'                 => "----''Deze overlegpagina hoort bij een anonieme gebruiker die hetzij geen gebruikersnaam heeft, hetzij deze niet gebruikt.
Daarom wordt het IP-adres ter identificatie gebruikt.
Het is mogelijk dat meerdere personen hetzelfde IP-adres gebruiken.
Mogelijk ontvang je hier berichten die niet voor je bedoeld zijn.
Als je dat wilt voorkomen, [[Special:UserLogin/signup|registreer je]] of [[Special:UserLogin|meld je aan]] om verwarring met andere anonieme gebruikers te voorkomen.''",
'noarticletext'                    => 'Deze pagina bevat geen tekst.
Je kunt [[Special:Search/{{PAGENAME}}|naar deze term zoeken]] in andere pagina\'s, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logboeken doorzoeken] of [{{fullurl:{{FULLPAGENAME}}|action=edit}} deze pagina bewerken]</span>.',
'noarticletext-nopermission'       => 'Deze pagina bevat geen tekst.
Je kunt [[Special:Search/{{PAGENAME}}|naar deze term zoeken]] in andere pagina\'s of
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logboeken doorzoeken]</span>.',
'userpage-userdoesnotexist'        => 'Je bewerkt een gebruikerspagina van een gebruiker die niet bestaat (gebruiker "<nowiki>$1</nowiki>").
Controleer of je deze pagina wel wilt aanmaken/bewerken.',
'clearyourcache'                   => "'''Let op!''' Nadat je de wijzigingen hebt opgeslagen is het wellicht nodig je browsercache te legen.
* '''Firefox / Safari:''' houd ''Shift'' ingedrukt terwijl je op ''Vernieuwen'' klikt of druk op ''Ctrl-F5'' of ''Ctrl-R'' (''⌘-Shift-R'' op een Mac)
* '''Google Chrome:''' druk op ''Ctrl-Shift-R'' (''⌘-Shift-R'' op een Mac)
* '''Internet Explorer:''' houd ''Ctrl'' ingedrukt terwijl je op ''Vernieuwen'' klikt of druk op ''Ctrl-F5''
* '''Opera:''' leeg je cache in ''Extra → Voorkeuren''",
'usercssyoucanpreview'             => "'''Tip:''' Gebruik de knop \"{{int:showpreview}}\" om je nieuwe CSS te testen alvorens op te slaan.",
'userjsyoucanpreview'              => "'''Tip:''' Gebruik de knop \"{{int:showpreview}}\" om je nieuwe JavaScript te testen alvorens op te slaan.",
'usercsspreview'                   => "'''Dit is alleen een voorvertoning van je persoonlijke CSS.
Deze is nog niet opgeslagen!'''",
'userjspreview'                    => "'''Let op: je test nu je persoonlijke JavaScript.'''
'''De pagina is niet opgeslagen!'''",
'userinvalidcssjstitle'            => "'''Waarschuwing:''' er is geen skin \"\$1\".
Let op: je eigen .css- en .js-pagina's beginnen met een kleine letter, bijvoorbeeld {{ns:user}}:Naam/vector.css in plaats van {{ns:user}}:Naam/Vector.css.",
'previewnote'                      => "'''Let op: dit is een controlepagina; je tekst is niet opgeslagen!'''",
'previewconflict'                  => 'Deze voorvertoning geeft aan hoe de tekst in het bovenste veld eruit ziet als je deze opslaat.',
'session_fail_preview'             => "'''Je bewerking is niet verwerkt, omdat de sessiegegevens verloren zijn gegaan.
Probeer het opnieuw.
Als het dan nog niet lukt, [[Special:UserLogout|meld jezelf dan af]] en weer aan.'''",
'session_fail_preview_html'        => "'''Je bewerking is niet verwerkt, omdat sessiegegevens verloren zijn gegaan.'''

''Omdat in {{SITENAME}} ruwe HTML is ingeschakeld, is een voorvertoning niet mogelijk als bescherming tegen aanvallen met JavaScript.''

'''Als dit een legitieme bewerking is, probeer het dan opnieuw.
Als het dan nog niet lukt, [[Special:UserLogout|meld jezelf dan af]] en weer aan.'''",
'token_suffix_mismatch'            => "'''Je bewerking is geweigerd, omdat je browser de leestekens in het bewerkingstoken onjuist heeft behandeld.
De bewerking is geweigerd om verminking van de paginatekst te voorkomen.
Dit gebeurt soms als er een webgebaseerde proxydienst wordt gebruikt die fouten bevat.'''",
'explainconflict'                  => "Een andere gebruiker heeft deze pagina bewerkt sinds je met je bewerking bent begonnen.
In het bovenste deel van het venster staat de tekst van de huidige pagina.
Je bewerking staat in het onderste gedeelte.
Je dient je bewerkingen in te voegen in de bestaande tekst.
'''Alleen''' de tekst in het bovenste gedeelte wordt opgeslagen als je op \"{{int:savearticle}}\" klikt.",
'yourtext'                         => 'Je tekst',
'nonunicodebrowser'                => "'''WAARSCHUWING: Je browser kan niet goed overweg met unicode.
Hiermee wordt door de MediaWiki-software rekening gehouden zodat je toch zonder problemen pagina's kunt bewerken: niet-ASCII karakters worden in het bewerkingsveld weergegeven als hexadecimale codes.'''",
'editingold'                       => "'''WAARSCHUWING!
Je bewerkt een oude versie van deze pagina.
Als je je bewerking opslaat, gaan alle wijzigingen die na deze versie gemaakt zijn verloren.'''",
'copyrightwarning'                 => "Opgelet: alle bijdragen aan {{SITENAME}} worden geacht te zijn vrijgegeven onder de $2 (zie $1 voor details).
Als je niet wilt dat je tekst door anderen naar believen bewerkt en verspreid kan worden, kies dan niet voor ‘Pagina opslaan’.<br />
Hierbij beloof je ons tevens dat je deze tekst zelf hebt geschreven of overgenomen uit een vrije, openbare bron.<br />
'''GEBRUIK GEEN MATERIAAL DAT BESCHERMD WORDT DOOR AUTEURSRECHT, TENZIJ JE DAAR TOESTEMMING VOOR HEBT!'''",
'copyrightwarning2'                => "Al je bijdragen aan {{SITENAME}} kunnen bewerkt, gewijzigd of verwijderd worden door andere gebruikers.
Als je niet wilt dat je teksten rigoureus aangepast worden door anderen, plaats ze hier dan niet.<br />
Je belooft ook dat je de oorspronkelijke auteur bent van dit materiaal of dat je het hebt gekopieerd uit een bron in het publieke domein of een soortgelijke vrije bron (zie $1 voor details).
'''GEBRUIK GEEN MATERIAAL DAT BESCHERMD WORDT DOOR AUTEURSRECHT, TENZIJ JE DAARVOOR TOESTEMMING HEBT!'''",
'longpageerror'                    => "'''FOUT: de tekst die je hebt toegevoegd is $1 kilobyte groot, wat groter is dan het maximum van $2 kilobyte.
Opslaan is niet mogelijk.'''",
'readonlywarning'                  => "'''WAARSCHUWING: De database is geblokkeerd voor bewerkingen, waarschijnlijk voor regulier databaseonderhoud, dus je kunt deze nu niet opslaan.
Het is misschien verstandig om je tekst tijdelijk in een tekstbestand op te slaan om dit te bewaren voor wanneer de blokkering van de database opgeheven is.'''

Een beheerder heeft de database geblokkeerd om de volgende reden: $1",
'nocreatetext'                     => "{{SITENAME}} heeft de mogelijkheid om nieuwe pagina's te maken beperkt.
Je kunt reeds bestaande pagina's wijzigen of je kunt [[Special:UserLogin|jezelf aanmelden of registreren]].",
'nocreate-loggedin'                => "Je hebt geen rechten om nieuwe pagina's te maken.",
'permissionserrorstext'            => 'Je hebt geen rechten om dit te doen om de volgende {{PLURAL:$1|reden|redenen}}:',
'permissionserrorstext-withaction' => 'Je hebt geen rechten om $2 om de volgende {{PLURAL:$1|reden|redenen}}:',
'recreate-moveddeleted-warn'       => "'''Waarschuwing: je bent bezig met het aanmaken van een pagina die in het verleden verwijderd is.'''

Overweeg of het terecht is dat je verder werkt aan deze pagina.
Voor je gemak staan hieronder het verwijderingslogboek en het hernoemingslogboek voor deze pagina:",
'edit-no-change'                   => 'Je bewerking is genegeerd, omdat er geen wijziging aan de tekst is gemaakt.',

# Revision deletion
'rev-deleted-text-unhide'     => "Deze versie van de pagina is '''verwijderd'''.
Achtergronden zijn mogelijk te vinden in het [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} verwijderingslogboek].
Als beheerder kun je  [$1 deze versie bekijken] als je wil.",
'rev-suppressed-text-unhide'  => "Deze paginaversie is '''onderdrukt'''.
Achtergronden zijn mogelijk te vinden in het [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logboek onderdrukte versies].
Als beheerder kun je [$1 de verschillen bekijken] als je wil.",
'rev-deleted-text-view'       => "Deze bewerking is '''verwijderd'''.
Als beheerder kun je deze zien;
er kunnen details aanwezig zijn in het [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} verwijderingslogboek].",
'rev-suppressed-text-view'    => "Deze paginaversie is '''onderdrukt'''.
Als beheerder kun je deze bekijken;
achtergronden zijn mogelijk te vinden in het [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logboek onderdrukte versies].",
'rev-deleted-no-diff'         => "Je kunt de verschillen niet bekijken, omdat een van de versies is '''verwijderd'''.
Achtergronden zijn mogelijk te vinden in het [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} verwijderingslogboek].",
'rev-suppressed-no-diff'      => "Je kunt de verschillen niet bekijken, omdat een van de versies is '''verwijderd'''.",
'rev-deleted-unhide-diff'     => "Een van de bewerkingen voor de verschillen die je hebt opgevraagd is '''verwijderd'''.
Achtergronden zijn mogelijk te vinden in het [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} verwijderingslogboek].
Als beheerder kun je [$1 de verschillen bekijken] als je wil.",
'rev-suppressed-unhide-diff'  => "Een van de versies in deze verschillen is '''onderdrukt'''.
Achtergronden zijn mogelijk te vinden in het [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} verbergingslogboek].
Als beheerder kunt je [$1 deze versie bekijken] als je wil.",
'rev-deleted-diff-view'       => "Een van de versies voor de verschillen die je hebt opgevraagd, is '''verwijderd'''.
Jij kunt deze verschillen bekijken. Mogelijk zijn details zichtbaar in het [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} verwijderlogboek].",
'rev-suppressed-diff-view'    => "Een van de bewerkingen voor de verschillen die je hebt opgevraagd, is '''onderdrukt'''.
Als beheerder kun je deze verschillen bekijken. Mogelijk zijn details zichtbaar in het [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} verbergingslogboek].",
'revdelete-nooldid-text'      => 'Je hebt geen doelversie(s) voor deze handeling opgegeven, de aangegeven versie bestaat niet of je probeert de laatste versie te verbergen.',
'revdelete-nologtype-text'    => 'Je hebt geen logboektype opgegeven om deze handeling op uit te voeren.',
'revdelete-nologid-text'      => 'Je hebt ofwel geen doellogboekregel opgegeven of de aangegeven logboekregel bestaat niet.',
'revdelete-show-file-confirm' => 'Weet je zeker dat u de verwijderde versie van het bestand "<nowiki>$1</nowiki>" van $2 om $3 wilt bekijken?',
'revdelete-confirm'           => 'Bevestig dat je dit wilde doen, dat je de consequenties begrijpt en dat je dit doet in overeenstemming met het geldende [[{{MediaWiki:Policy-url}}|beleid]].',
'revdelete-show-no-access'    => 'Er is een fout opgetreden bij het weergeven van het object van $1 om $2 uur: dit object is gemarkeerd als "beschermd".
Je hebt geen toegang tot dit object.',
'revdelete-modify-no-access'  => 'Er is een fout opgetreden bij het wijzigen van het object van $1 om $2 uur: dit object is gemarkeerd als "beschermd".
Je hebt geen toegang tot dit object.',
'revdelete-only-restricted'   => 'Er is een fout opgetreden bij het verbergen van het item van $1, $2: je kunt geen items onderdrukken uit het zicht van beheerders zonder ook een van de andere zichtbaarheidsopties te selecteren.',

# History merging
'mergehistory-header' => 'Via deze pagina kun je versies van de geschiedenis van een bronpagina naar een nieuwere pagina samenvoegen.
Zorg dat deze wijziging de geschiedenisdoorlopendheid van de pagina behoudt.',

# Search results
'searchsubtitle'        => 'Je zocht naar \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|pagina\'s die beginnen met "$1"]] {{int:pipe-separator}}[[Special:WhatLinksHere/$1|pagina\'s die verwijzen naar "$1"]])',
'searchsubtitleinvalid' => "Je hebt gezocht naar '''$1'''",
'search-suggest'        => 'Bedoelde je: $1',
'nonefound'             => "'''Opmerking''': standaard worden niet alle naamruimten doorzocht.
Als je in uw zoekopdracht als voorvoegsel \"''all:''\" gebruikt worden alle pagina's doorzocht (inclusief overlegpagina's, sjablonen, enzovoort).
Je kunt ook een naamruimte als voorvoegsel gebruiken.",
'searchdisabled'        => 'Zoeken in {{SITENAME}} is niet mogelijk.
Je kunt gebruik maken van Google.
De gegevens over {{SITENAME}} zijn mogelijk niet bijgewerkt.',

# Preferences page
'prefsnologintext'           => 'Je moet <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} aangemeld]</span> zijn om je voorkeuren te kunnen instellen.',
'prefs-help-watchlist-token' => 'Door hier een geheime sleutel in te vullen wordt een RSS-feed voor je volglijst aangemaakt.
Iedereen die deze sleutel kent kan je volglijst lezen, dus kies een veilige sleutel.
Hier volgt een willekeurig gegenereerde waarde die je kunt gebruiken: $1',
'savedprefs'                 => 'Je voorkeuren zijn opgeslagen.',
'prefs-reset-intro'          => 'Gebruik deze functie om je voorkeuren te herstellen naar de standaardinstellingen.
Deze handeling kan niet ongedaan gemaakt worden.',
'youremail'                  => 'Je e-mailadres:',
'prefs-help-signature'       => 'Reacties op de overlegpagina\'s worden meestal ondertekend met "<nowiki>~~~~</nowiki>".
De tildes worden omgezet in je ondertekening en een datum en tijd van de bewerking.',
'badsiglength'               => 'Uw ondertekening is te lang.
Deze moet minder dan $1 {{PLURAL:$1|karakters|karakters}} bevatten.',
'prefs-help-realname'        => 'Echte naam is optioneel, als je deze opgeeft kan deze naam gebruikt worden om je erkenning te geven voor uw werk.',
'prefs-help-email'           => 'E-mailadres is optioneel, maar maakt het mogelijk om je jouw wachtwoord te e-mailen als je het bent vergeten.
Je kunt ook anderen in staat stellen per e-mail contact met je op te nemen via een verwijzing op je gebruikers- en overlegpagina zonder dat u uw identiteit prijsgeeft.',

# User rights
'userrights-groups-help'      => 'Je kunt de groepen wijzigen waar deze gebruiker lid van is.
* Een aangekruist vakje betekent dat de gebruiker lid is van de groep.
* Een niet aangekruist vakje betekent dat de gebruiker geen lid is van de groep.
* Een "*" betekent dat je een gebruiker niet uit een groep kunt verwijderen nadat je die hebt toegevoegd of vice versa.',
'userrights-no-interwiki'     => "Je hebt geen rechten om gebruikersrechten op andere wiki's te wijzigen.",
'userrights-nologin'          => 'Je moet jezelf [[Special:UserLogin|aanmelden]] met een gebruiker met de juiste rechten om gebruikersrechten toe te wijzen.',
'userrights-notallowed'       => 'Je hebt geen rechten om gebruikersrechten toe te wijzen.',
'userrights-changeable-col'   => 'Groepen die je kunt beheren',
'userrights-unchangeable-col' => 'Groepen die je niet kunt beheren',

# Recent changes linked
'recentchangeslinked-summary' => "Deze speciale pagina geeft de laatste bewerkingen weer op pagina's waarheen verwezen wordt vanaf een aangegeven pagina of vanuit pagina's in een aangegeven pagina een categorie.
Pagina's die op [[Special:Watchlist|je volglijst]] staan worden '''vet''' weergegeven.",

# Upload
'uploadnologintext'           => 'Je moet [[Special:UserLogin|aangemeld]] zijn om bestanden te uploaden.',
'empty-file'                  => 'Het bestand dat je probeerde te uploaden had geen inhoud.',
'file-too-large'              => 'Het bestand dat je probeerde te uploaden was te groot.',
'hookaborted'                 => 'De wijziging die je probeerde te maken is afgebroken door een uitbreidingshook.',
'emptyfile'                   => 'Het bestand dat je hebt geüpload lijkt leeg te zijn.
Dit zou kunnen komen door een typefout in de bestandsnaam.
Ga na of je dit bestand werkelijk bedoelde te uploaden.',
'fileexists'                  => 'Er bestaat al een bestand met deze naam.
Controleer <strong>[[:$1]]</strong> als je niet zeker weet of je het huidige bestand wilt overschrijven.
[[$1|thumb]]',
'filepageexists'              => 'De beschrijvingspagina voor dit bestand bestaat al op <strong>[[:$1]]</strong>, maar er bestaat geen bestand met deze naam.
De samenvatting die je hebt opgegeven zal niet op de beschrijvingspagina verschijnen.
Bewerk de pagina handmatig om je beschrijving daar weer te geven. [[$1|miniatuur]]',
'file-thumbnail-no'           => "De bestandsnaam begint met <strong>$1</strong>.
Het lijkt een verkleinde afbeelding te zijn ''(miniatuurafbeelding)''.
Als je deze afbeelding in volledige resolutie hebt, upload die afbeelding dan.
Wijzig anders de bestandsnaam.",
'fileexists-forbidden'        => 'Er bestaat al een bestand met deze naam, en dat kan niet overschreven worden.
Upload je bestand onder een andere naam.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Er bestaat al een bestand met deze naam bij de gedeelte bestanden.
Als je het bestand alsnog wilt uploaden, ga dan terug en kies een andere naam.
[[File:$1|thumb|center|$1]]',
'file-deleted-duplicate'      => 'Een bestand dat identiek is aan dit bestand ([[:$1]]) is voorheen verwijderd.
Raadpleeg het verwijderingslogboek voordat je verder gaat.',
'uploadfromurl-queued'        => 'Je upload is in de wachtrij geplaatst.',
'filewasdeleted'              => 'Er is eerder een bestand met deze naam verwijderd.
Raadpleeg het $1 voordat je het opnieuw toevoegt.',
'filename-bad-prefix'         => "De naam van het bestand dat je aan het uploaden bent begint met '''\"\$1\"''', wat een niet-beschrijvende naam is die meestal automatisch door een digitale camera wordt gegeven.
Kies een duidelijke naam voor je bestand.",
'upload-success-msg'          => 'Je upload van [$2] is geslaagd en is beschikbaar: [[:{{ns:file}}:$1]]',
'upload-failure-msg'          => 'Er was een probleem met je upload van [$2]:

$1',
'upload-warning-msg'          => 'Er was een probleem met je upload van [$2].
Ga terug naar het [[Special:Upload/stash/$1|uploadformulier]] om dit probleem te verhelpen.',

# img_auth script messages
'img-auth-nopathinfo' => 'PATH_INFO mist.
Je server is niet ingesteld om deze informatie door te geven.
Misschien gebruikt deze CGI, en dan wordt img_auth niet ondersteund.
Zie https://www.mediawiki.org/wiki/Manual:Image_Authorization voor meer informatie.',
'img-auth-nologinnWL' => 'Je bent niet aangemeld en "$1" staat niet op de witte lijst.',
'img-auth-isdir'      => 'Je probeert de map "$1" te benaderen.
Alleen toegang tot bestanden is toegestaan.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error28-text' => 'Het duurde te lang voordat de website antwoordde.
Controleer of de website beschikbaar is, wacht even en probeer het dan opnieuw.
Je kunt het misschien proberen als het minder druk is.',

'upload_source_file' => '(een bestand op je computer)',

# File description page
'filepage-nofile-link' => 'Er bestaat geen bestand met deze naam, maar je kunt het [$1 uploaden].',

# File reversion
'filerevert-intro' => "Je bent '''[[Media:$1|$1]]''' aan het terugdraaien tot de [$4 versie op $2, $3].",

# File deletion
'filedelete-intro'     => "Je staat op het punt om het bestand '''[[Media:$1|$1]]''' te verwijderen, inclusief alle eerdere versies.",
'filedelete-intro-old' => "Je bent de versie van '''[[Media:$1|$1]]''' van [$4 $3, $2] aan het verwijderen.",

# Miscellaneous special pages
'notargettext' => 'Je hebt niet opgegeven voor welke pagina of gebruiker u deze handeling wilt uitvoeren.',
'nopagetext'   => 'De pagina die je wilt hernoemen bestaat niet.',

# Book sources
'booksources-text' => 'Hieronder staat een lijst met koppelingen naar andere websites die nieuwe of gebruikte boeken verkopen, en die wellicht meer informatie over het boek dat je zoekt hebben:',

# Special:Log
'alllogstext' => 'Dit is het gecombineerde logboek van {{SITENAME}}.
Je kunt ook kiezen voor specifieke logboeken en filteren op gebruiker (hoofdlettergevoelig) en paginanaam (hoofdlettergevoelig).',

# E-mail user
'mailnologintext'      => 'Je moet [[Special:UserLogin|aangemeld]] zijn en een geldig e-mailadres in je [[Special:Preferences|voorkeuren]] vermelden om andere gebruikers te kunnen e-mailen.',
'emailpagetext'        => 'Via dit formulier kun je een e-mail aan deze gebruiker verzenden.
Het e-mailadres dat je hebt opgegeven bij [[Special:Preferences|je voorkeuren]] wordt als afzender gebruikt.
De ontvanger kan dus direct naar je reageren.',
'usermaildisabledtext' => 'Je kunt geen e-mail verzenden naar andere gebruikers op deze wiki',
'emailccsubject'       => 'Kopie van je bericht aan $1: $2',
'emailsenttext'        => 'Je e-mail is verzonden.',

# Watchlist
'nowatchlist'          => 'Je volglijst is leeg.',
'watchlistanontext'    => 'Om je volglijst te bekijken of te bewerken moet je je $1.',
'watchnologintext'     => 'Je dient [[Special:UserLogin|aangemeld]] te zijn om je volglijst te bewerken.',
'addedwatchtext'       => "De pagina \"[[:\$1]]\" is toegevoegd aan je [[Special:Watchlist|volglijst]].
Toekomstige bewerkingen van deze pagina en de bijbehorende overlegpagina worden op [[Special:Watchlist|je volglijst]] vermeld en worden '''vet''' weergegeven in de [[Special:RecentChanges|lijst van recente wijzigingen]].",
'removedwatchtext'     => 'De pagina "[[:$1]]" is van [[Special:Watchlist|je volglijst]] verwijderd.',
'watchnochange'        => "Geen van de pagina's op je volglijst is in deze periode bewerkt.",
'watchlist-details'    => "Er {{PLURAL:$1|staat één pagina|staan $1 pagina's}} op je volglijst, exclusief overlegpagina's.",
'wlheader-showupdated' => "* Pagina's die zijn bewerkt sinds je laatste bezoek worden '''vet''' weergegeven",
'watchlistcontains'    => "Er {{PLURAL:$1|staat 1 pagina|staan $1 pagina's}} op je volglijst.",

'enotif_lastvisited' => 'Zie $1 voor alle wijzigingen sinds je laatste bezoek.',
'enotif_body'        => 'Beste $WATCHINGUSERNAME,

De pagina $PAGETITLE op {{SITENAME}} is $CHANGEDORCREATED op $PAGEEDITDATE door $PAGEEDITOR, zie $PAGETITLE_URL voor de huidige versie.

$NEWPAGE

Samenvatting van de wijziging: $PAGESUMMARY $PAGEMINOREDIT

Contactgegevens van de auteur:
E-mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Tenzij je deze pagina bezoekt, komen er geen verdere berichten. Op je volglijst kun je voor alle gevolgde pagina\'s de waarschuwingsinstellingen opschonen.

             Groet van je {{SITENAME}} waarschuwingssysteem.

--
Je kunt je volglijstinstellingen wijzigen op:
{{canonicalurl:Special:Watchlist/edit}}

Je kunt de pagina van uw volglijst verwijderen via de volgende verwijzing:
$UNWATCHURL

Feedback en andere assistentie:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'historywarning'    => "'''Waarschuwing:''' de pagina die je wilt verwijderen heeft ongeveer $1 {{PLURAL:$1|versie|versies}}:",
'confirmdeletetext' => 'Je staat op het punt een pagina te verwijderen, inclusief de geschiedenis.
Bevestig hieronder dat dit inderdaad je bedoeling is, dat je de gevolgen begrijpt en dat de verwijdering overeenstemt met het [[{{MediaWiki:Policy-url}}|beleid]].',

# Edit tokens
'sessionfailure' => 'Er lijkt een probleem te zijn met je aanmeldsessie.
Je handeling is gestopt uit voorzorg tegen een beveiligingsrisico (dat bestaat uit mogelijke "hijacking" van deze sessie).
Ga een pagina terug, laad die pagina opnieuw en probeer het nog eens.',

# Protect
'protect-text'           => "Hier kun je het beveiligingsniveau voor de pagina '''$1''' bekijken en wijzigen.",
'protect-locked-blocked' => "Je kunt het beveiligingsniveau niet wijzigen terwijl je geblokkeerd bent.
Hier zijn de huidige instellingen voor de pagina '''$1''':",
'protect-locked-access'  => "Je hebt geen rechten om het beveiligingsniveau te wijzigen.
Dit zijn de huidige instellingen voor de pagina '''$1''':",
'protect-cantedit'       => 'Je kunt het beveiligingsniveau van deze pagina niet wijzigen, omdat je geen rechten hebt om het te bewerken.',

# Undelete
'undeletehistory'            => 'Als je een pagina terugplaatst, worden alle versies hersteld.
Als er al een nieuwe pagina met dezelfde naam is aangemaakt sinds de pagina is verwijderd, worden de eerder verwijderde versies teruggeplaatst en blijft de huidige versie intact.',
'undeleterevision-missing'   => 'Ongeldige of missende versie.
Mogelijk heb je een verkeerde verwijzing of is de versie hersteld of verwijderd uit het archief.',
'undelete-show-file-confirm' => 'Weet je zeker dat je een verwijderde versie van het bestand "<nowiki>$1</nowiki>" van $2 om $3 wil bekijken?',

# Block/unblock
'ipb-needreblock'             => '$1 is al geblokkeerd.
Wil je de instellingen wijzigen?',
'proxyblockreason'            => 'Dit is een automatische preventieve blokkade, omdat je gebruik maakt van een open proxyserver.
Neem contact op met je Internet-provider of je helpdesk en stel die op de hoogte van dit ernstige beveiligingsprobleem.',
'sorbsreason'                 => 'Je IP-adres staat bekend als open proxyserver in de DNS-blacklist die {{SITENAME}} gebruikt.',
'sorbs_create_account_reason' => 'Je IP-adres staat bekend als open proxyserver in de DNS-blacklist die {{SITENAME}} gebruikt.
Je kunt geen gebruiker registreren.',
'cant-block-while-blocked'    => 'Je kunt andere gebruikers niet blokkeren terwijl je zelf geblokkeerd bent.',
'cant-see-hidden-user'        => "De gebruiker die je probeert te blokken is al geblokkeerd en verborgen.
Omdat je het recht 'hideuser' niet hebt, kun je de blokkade van de gebruiker niet bekijken of bewerken.",
'ipbblocked'                  => 'Je kunt geen andere gebruikers (de)blokkeren, omdat je zelf geblokkeerd bent',
'ipbnounblockself'            => 'Je mag jezelf niet deblokkeren',

# Developer tools
'lockdbtext'        => "Waarschuwing: de database blokkeren heeft tot gevolg dat geen enkele gebruiker meer in staat is pagina's te bewerken, voorkeuren te wijzigen of iets anders te doen waarvoor wijzigingen in de database nodig zijn.

Bevestig dat je deze handeling wilt uitvoeren en dat je de database vrijgeeft nadat het onderhoud is uitgevoerd.",
'unlockdbtext'      => "Na het vrijgeven van de database kunnen gebruikers weer pagina's bewerken, hun voorkeuren wijzigen of iets anders te doen waarvoor er wijzigingen in de database nodig zijn.

Bevestig dat je deze handeling wil uitvoeren.",
'locknoconfirm'     => 'Je hebt je keuze niet bevestigd via het vinkvakje.',
'lockdbsuccesstext' => 'De database is afgesloten.<br />
Vergeet niet de [[Special:UnlockDB|database vrij te geven]] zodra je klaar bent met je onderhoud.',

# Move page
'movepagetext'            => "Door middel van het onderstaande formulier kun je een pagina hernoemen.
De geschiedenis gaat mee naar de nieuwe pagina.
* De oude naam wordt automatisch een doorverwijzing naar de nieuwe pagina.
* Verwijzingen naar de oude pagina worden niet aangepast.
* De pagina's die doorverwijzen naar de oorspronkelijke paginanaam worden automatisch bijgewerkt.
Als je dit niet wenst, controleer dan of er geen [[Special:DoubleRedirects|dubbele]] of [[Special:BrokenRedirects|onjuiste doorverwijzingen]] zijn ontstaan.

Een pagina kan '''alleen''' hernoemd worden als de nieuwe paginanaam niet bestaat of een doorverwijspagina zonder verdere geschiedenis is.

'''WAARSCHUWING!'''
Voor veel bekeken pagina's kan het hernoemen drastische en onvoorziene gevolgen hebben.
Zorg ervoor dat je die gevolgen overziet voordat je deze handeling uitvoert.",
'movepagetalktext'        => "De bijbehorende overlegpagina krijgt automatisch een andere naam, '''tenzij''':
* De overlegpagina onder de nieuwe naam al bestaat;
* Je het onderstaande vinkje deselecteert.",
'moveuserpage-warning'    => "'''Waarschuwing:''' Je gaat een gebruikerspagina hernoemen.
Houd er rekening mee dat alleen de pagina wordt hernoemd, ''niet'' de gebruiker.",
'movenologintext'         => 'Je moet [[Special:UserLogin|aangemeld]] zijn om een pagina te hernoemen.',
'movenotallowed'          => "Je hebt geen rechten om pagina's te hernoemen.",
'movenotallowedfile'      => 'Je hebt geen rechten om bestanden te hernoemen.',
'cant-move-user-page'     => "Je hebt geen rechten om gebruikerspagina's te hernoemen (met uitzondering van subpagina's).",
'cant-move-to-user-page'  => 'Je hebt geen rechten om een pagina naar een gebruikerspagina te hernoemen. Hernoemen naar een subpagina is wel mogelijk.',
'cantmove-titleprotected' => 'Je kunt geen pagina naar deze paginaam hernoemen, omdat deze paginanaam beveiligd is tegen het aanmaken ervan.',
'delete_and_move_text'    => '==Verwijdering nodig==
Onder de naam "[[:$1]]" bestaat al een pagina.
Wil je deze verwijderen om plaats te maken voor de te hernoemen pagina?',

# Export
'exporttext' => 'Je kunt de tekst en geschiedenis van een pagina of pagina\'s exporteren naar XML.
Dit exportbestand is daarna te importeren in een andere MediaWiki via de [[Special:Import|importpagina]].

Geef in het onderstaande veld de namen van de te exporteren pagina\'s op, één pagina per regel, en geef aan of je alle versies met de bewerkingssamenvatting of alleen de huidige versies met de bewerkingssamenvatting wilt exporteren.

In het laatste geval kun je ook een verwijzing gebruiken, bijvoorbeeld [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] voor de pagina "[[{{MediaWiki:Mainpage}}]]".',

# Namespace 8 related
'allmessagestext' => 'Hieronder staan de systeemberichten uit de MediaWiki-naamruimte.
Ga naar [//www.mediawiki.org/wiki/Localisation MediaWiki-lokalisatie] en [//translatewiki.net translatewiki.net] als je wilt bijdragen aan de algemene vertaling voor MediaWiki.',

# Special:Import
'importtext' => 'Gebruik de [[Special:Export|exportfunctie]] in de wiki waar de informatie vandaan komt, sla de uitvoer op je eigen systeem op, en voeg die daarna hier toe.',

# Tooltip help for the actions
'tooltip-pt-userpage'      => 'Jouw gebruikerspagina',
'tooltip-pt-anonuserpage'  => 'Gebruikerspagina voor je IP-adres',
'tooltip-pt-mytalk'        => 'Jouw overlegpagina',
'tooltip-pt-watchlist'     => "Overzicht van pagina's die je volgt",
'tooltip-pt-mycontris'     => 'Overzicht van je bijdragen',
'tooltip-pt-login'         => 'Je wordt van harte uitgenodigd om je aan te melden als gebruiker, maar dit is niet verplicht',
'tooltip-pt-anonlogin'     => 'Je wordt van harte uitgenodigd om je aan te melden als gebruiker, maar dit is niet verplicht',
'tooltip-ca-edit'          => 'Je kunt deze pagina bewerken.
Gebruik de voorbeeldweergaveknop alvorens te bewaren.',
'tooltip-ca-viewsource'    => 'Deze pagina is beveiligd.
Je kunt wel de broncode bekijken.',
'tooltip-ca-nstab-special' => 'Dit is een speciale pagina, je kunt de pagina zelf niet bewerken',
'tooltip-save'             => 'Je wijzigingen opslaan',
'tooltip-watch'            => 'Deze pagina aan je volglijst toevoegen',

# Metadata
'notacceptable' => 'De wikiserver kan de gegevens niet leveren in een vorm die je browser kan lezen.',

# Spam protection
'spamprotectiontext' => 'De pagina die je wilde opslaan is geblokkeerd door het spamfilter.
Meestal wordt dit door een externe verwijzing op een zwarte lijst veroorzaakt.',

# Patrolling
'markedaspatrollederror-noautopatrol' => 'Je kunt je eigen wijzigingen niet als gecontroleerd markeren.',

# Media information
'mediawarning' => "'''Waarschuwing''': dit bestandstype bevat mogelijk programmacode die je systeem schade kan berokkenen.",

# E-mail address confirmation
'confirmemail_noemail'      => 'Je hebt geen geldig e-mailadres ingegeven in je [[Special:Preferences|gebruikersvoorkeuren]].',
'confirmemail_text'         => '{{SITENAME}} eist bevestiging van je e-mailadres voordat je de e-mailmogelijkheden kunt gebruiken.
Klik op de onderstaande knop om een bevestigingsbericht te ontvangen.
Dit bericht bevat een verwijzing met een code.
Open die verwijzing om je e-mailadres te bevestigen.',
'confirmemail_pending'      => 'Er is al een bevestigingsbericht aan je verzonden.
Als je recentelijk je gebruiker hebt aangemaakt, wacht dan een paar minuten totdat die aankomt voordat je opnieuw een e-mail laat sturen.',
'confirmemail_oncreate'     => 'Er is een bevestigingscode naar je e-mailadres verzonden.
Deze code is niet nodig om je aan te melden, maar je dient deze wel te bevestigen voordat je de e-mailmogelijkheden van deze wiki kunt gebruiken.',
'confirmemail_sendfailed'   => '{{SITENAME}} kon je bevestigingscode niet verzenden.
Controleer je e-mailadres op ongeldige tekens.

Het e-mailprogramma meldde: $1',
'confirmemail_needlogin'    => 'Je moet $1 om je e-mailadres te bevestigen.',
'confirmemail_success'      => 'Je e-mailadres is bevestigd.
Je kunt jezelf nu [[Special:UserLogin|aanmelden]] en {{SITENAME}} gebruiken.',
'confirmemail_loggedin'     => 'Je e-mailadres is nu bevestigd.',
'confirmemail_error'        => 'Er is iets verkeerd gegaan tijdens het opslaan van je bevestiging.',
'confirmemail_body'         => 'Iemand, waarschijnlijk jijzelf, met het IP-adres $1,
heeft zich met dit e-mailadres geregistreerd als gebruiker "$2" op {{SITENAME}}.

Open de volgende verwijzing in je webbrowser om te bevestigen dat je deze gebruiker bent en om de e-mailmogelijkheden op {{SITENAME}} te activeren:

$3

Als je jezelf *niet* hebt aangemeld, volg dan de volgende verwijzing om de bevestiging van je e-mailadres te annuleren:

$5

De bevestigingscode vervalt op $4.',
'confirmemail_body_changed' => 'Iemand, waarschijnlijk jijzelf, met het IP-adres $1,
heeft het het e-mailadres geregistreerd voor gebruiker "$2" op {{SITENAME}} gewijzigd naar dit e-mailadres.

Open de volgende verwijzing in je webbrowser om te bevestigen dat je deze gebruiker bent en om de e-mailmogelijkheden op {{SITENAME}} opnieuw te activeren:

$3

Als je jezelf *niet* hebt aangemeld, volg dan de volgende verwijzing om de bevestiging van je e-mailadres te annuleren:

$5

De bevestigingscode vervalt op $4.',

# Delete conflict
'deletedwhileediting' => "'''Let op''': deze pagina is verwijderd terwijl je bezig was met je bewerking!",
'confirmrecreate'     => "Nadat je begonnen bent met je wijziging heeft [[User:$1|$1]] ([[User talk:$1|overleg]]) deze pagina verwijderd met opgave van de volgende reden:
: ''$2''
Bevestig dat je de pagina opnieuw wilt aanmaken.",

# Watchlist editor
'watchlistedit-numitems'       => 'Je volglijst bevat {{PLURAL:$1|1 pagina|$1 pagina’s}}, zonder overlegpagina’s.',
'watchlistedit-noitems'        => 'Je volglijst bevat geen pagina’s.',
'watchlistedit-normal-explain' => 'Hieronder worden de pagina’s op je volglijst weergegeven.
Klik op het vierkantje ernaast en daarna op "{{int:Watchlistedit-normal-submit}}" om een pagina te verwijderen.
Je kunt ook de [[Special:EditWatchlist/raw|ruwe lijst bewerken]].',
'watchlistedit-normal-done'    => 'Er {{PLURAL:$1|is 1 pagina|zijn $1 pagina’s}} verwijderd van je volglijst:',
'watchlistedit-raw-explain'    => 'Hieronder staan pagina’s op je volglijst.
Je kunt de lijst bewerken door pagina’s te verwijderen en toe te voegen.
Eén pagina per regel.
Als je klaar bent, klik dan op "{{int:Watchlistedit-raw-submit}}".
Je kunt ook [[Special:EditWatchlist|het standaard bewerkingsscherm gebruiken]].',
'watchlistedit-raw-done'       => 'Jr volglijst is bijgewerkt.',

# Special:Version
'version-license-info' => 'MediaWiki is vrije software; je kunt MediaWiki verspreiden en/of aanpassen onder de voorwaarden van de GNU General Public License zoals gepubliceerd door de Free Software Foundation; ofwel versie 2 van de Licentie, of - zo je wilt - enige latere versie.

MediaWiki wordt verspreid in de hoop dat het nuttig is, maar ZONDER ENIGE GARANTIE; zonder zelfs de implicitiete garantie van VERKOOPBAARHEID of GESCHIKHEID VOOR ENIG DOEL IN HET BIJZONDER. Zie de GNU General Public License voor meer informatie.

Samen met dit programma hoor je een [{{SERVER}}{{SCRIPTPATH}}/COPYING kopie van de GNU General Public License] te hebben ontvangen; zo niet, schrijf dan naar de Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA of [//www.gnu.org/licenses/old-licenses/gpl-2.0.html lees de licentie online].',

# Database error messages
'dberr-usegoogle' => 'Wellicht kun je in de tussentijd zoeken via Google.',

# HTML forms
'htmlform-float-invalid' => 'De waarde die je hebt opgegeven is geen getal.',

);
