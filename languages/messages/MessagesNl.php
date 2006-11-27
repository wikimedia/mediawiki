<?php
/** Dutch (Nederlands)
 *
 * @package MediaWiki
 * @subpackage Language
 */

$quickbarSettings = array(
	'Uitgeschakeld', 'Links vast', 'Rechts vast', 'Links zwevend'
);

$skinNames = array(
	'standard' => 'Standaard',
	'nostalgia' => 'Nostalgie',
	'cologneblue' => 'Keuls blauw',
);

$bookstoreList = array(
	'Koninklijke Bibliotheek' => 'http://opc4.kb.nl/DB=1/SET=5/TTL=1/CMD?ACT=SRCH&IKT=1007&SRT=RLV&TRM=$1'
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciaal',
	NS_MAIN           => '',
	NS_TALK           => 'Overleg',
	NS_USER           => 'Gebruiker',
	NS_USER_TALK      => 'Overleg_gebruiker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Overleg_$1',
	NS_IMAGE          => 'Afbeelding',
	NS_IMAGE_TALK     => 'Overleg_afbeelding',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Overleg_MediaWiki',
	NS_TEMPLATE       => 'Sjabloon',
	NS_TEMPLATE_TALK  => 'Overleg_sjabloon',
	NS_HELP           => 'Help',
	NS_HELP_TALK      => 'Overleg_help',
	NS_CATEGORY       => 'Categorie',
	NS_CATEGORY_TALK  => 'Overleg_categorie'
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'M j, Y H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j H:i',
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zäöüïëéèà]+)(.*)$/sDu';

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

$messages = array(
/*
The sidebar for MonoBook is generated from this message, lines that do not
begin with * or ** are discarded, furthermore lines that do begin with ** and
do not contain | are also discarded, but don't depend on this behaviour for
future releases. Also note that since each list value is wrapped in a unique
XHTML id it should only appear once and include characters that are legal
XHTML id names.

Note to translators: Do not include this message in the language files you
submit for inclusion in MediaWiki, it should always be inherited from the
parent class in order maintain consistency across languages.
*/
# User preference toggles
'tog-underline'         => 'Links onderstrepen:',
'tog-highlightbroken'   => 'Links naar lege pagina\'s <a href="" class="new">zo weergeven</a> (alternatief: zo weergeven<a href="" class="internal">?</a>).',
'tog-justify'           => 'Paragrafen uitvullen',
'tog-hideminor'         => 'Kleine wijzigingen verbergen in recente wijzigingen',
'tog-extendwatchlist'   => 'Toon alle wijzigingen in mijn volglijst',
'tog-usenewrc'          => 'Gebruik de uitgebreide Recente Wijzigingen-pagina (vereist JavaScript)',
'tog-numberheadings'    => 'Koppen automatisch nummeren',
'tog-showtoolbar'       => 'Toon werkbalk boven bewerkingsveld (vereist JavaScript)',
'tog-editondblclick'    => 'Dubbelklikken voor bewerken (vereist JavaScript)',
'tog-editsection'       => 'Maak het bewerken van deelpagina\'s mogelijk',
'tog-editsectiononrightclick'=> 'Maak bewerken van deelpagina\'s mogelijk met een rechtermuisklik op een tussenkop (vereist JavaScript)',
'tog-showtoc'           => 'Toon inhoudsopgave (voor pagina\'s met minstens 3 tussenkoppen)',
'tog-rememberpassword'  => 'Wachtwoord onthouden',
'tog-editwidth'         => 'Bewerkingsveld over volle breedte',
'tog-watchcreations'    => 'Pagina\'s die ik aanmaak automatisch volgen',
'tog-watchdefault'      => 'Pagina\'s die ik bewerk automatisch volgen',
'tog-minordefault'      => 'Al mijn bewerkingen als \'klein\' markeren',
'tog-previewontop'      => 'Toon voorvertoning boven bewerkingsveld',
'tog-previewonfirst'    => 'Toon voorvertoning bij eerste bewerking',
'tog-nocache'           => 'Gebruik geen caching',
'tog-enotifwatchlistpages'=> 'Verzend een e-mail bij bewerkingen van pagina\'s op mijn volglijst',
'tog-enotifusertalkpages'=> 'Verzend een e-mail als mijn overlegpagina wijzigt',
'tog-enotifminoredits'  => 'Verzend ook een e-mail bij kleine bewerkingen op pagina\'s op mijn volglijst',
'tog-enotifrevealaddr'  => 'Toon mijn e-mailadres in e-mailberichten',
'tog-shownumberswatching'=> 'Toon aantal gebruikers dat deze pagina volgt',
'tog-fancysig'          => 'Ondertekenen zonder link naar gebruikerspagina',
'tog-externaleditor'    => 'Gebruik standaard een externe tekstbewerker',
'tog-externaldiff'      => 'Gebruik standaard een extern vergelijkingsprogramma',
'tog-showjumplinks'     => 'Maak "ga naar"-toegankelijkheidslinks mogelijk',
'tog-uselivepreview'    => 'Gebruik "live voorvertoning" (vereist JavaScript - experimenteel)',
'tog-autopatrol'        => 'Markeer eigen bewerkingen als gecontroleerd',
'tog-forceeditsummary'  => 'Geef een melding bij een lege samenvatting',
'tog-watchlisthideown'  => 'Verberg eigen bewerkingen op mijn volglijst',
'tog-watchlisthidebots' => 'Verberg botbewerkingen op mijn volglijst',

'underline-always'      => 'Altijd',
'underline-never'       => 'Nooit',
'underline-default'     => 'Webbrowser-standaard',

'skinpreview'           => '(Voorvertoning)',

# dates
'sunday'                => 'zondag',
'monday'                => 'maandag',
'tuesday'               => 'dinsdag',
'wednesday'             => 'woensdag',
'thursday'              => 'donderdag',
'friday'                => 'vrijdag',
'saturday'              => 'zaterdag',
'sun'                   => 'zon',
'mon'                   => 'maa',
'tue'                   => 'din',
'wed'                   => 'woe',
'thu'                   => 'don',
'fri'                   => 'vri',
'sat'                   => 'zat',
'january'               => 'januari',
'february'              => 'februari',
'march'                 => 'maart',
'april'                 => 'april',
'may_long'              => 'mei',
'june'                  => 'juni',
'july'                  => 'juli',
'august'                => 'augustus',
'september'             => 'september',
'october'               => 'oktober',
'november'              => 'november',
'december'              => 'december',
'january-gen'           => 'januari',
'february-gen'          => 'februari',
'march-gen'             => 'maart',
'april-gen'             => 'april',
'may-gen'               => 'mei',
'june-gen'              => 'juni',
'july-gen'              => 'juli',
'august-gen'            => 'augustus',
'september-gen'         => 'september',
'october-gen'           => 'oktober',
'november-gen'          => 'november',
'december-gen'          => 'december',
'jan'                   => 'jan',
'feb'                   => 'feb',
'mar'                   => 'mrt',
'apr'                   => 'apr',
'may'                   => 'mei',
'jun'                   => 'jun',
'jul'                   => 'jul',
'aug'                   => 'aug',
'sep'                   => 'sep',
'oct'                   => 'okt',
'nov'                   => 'nov',
'dec'                   => 'dec',
# Bits of text used by many pages:
#
'categories'            => 'Categorieën',
'pagecategories'        => '{{PLURAL:$1|Categorie|Categorieën}}',
'category_header'       => 'Pagina\'s in categorie "$1"',
'subcategories'         => 'Ondercategorieën',

'mainpage'              => 'Hoofdpagina',
'mainpagetext'          => '<big>\'\'\'De installatie van MediaWiki is geslaagd.\'\'\'</big>',
'mainpagedocfooter'     => 'Raadpleeg de [http://meta.wikimedia.org/wiki/Help:Contents handleiding] voor informatie over het gebruik van de wikisoftware.

== Meer hulp ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Lijst met instellingen]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki mailinglijst voor nieuwe versies]',

'portal'                => 'Gebruikersportaal',
'portal-url'            => 'Project:Gebruikersportaal',
'about'                 => 'Info',
'aboutsite'             => 'Over {{SITENAME}}',
'aboutpage'             => 'Project:Info',
'article'               => 'Pagina',
'help'                  => 'Hulp en contact',
'helppage'              => 'Help:Inhoud',
'bugreports'            => 'Foutrapporten',
'bugreportspage'        => 'Project:Foutrapportage',
'sitesupport'           => 'Financieel bijdragen',
'sitesupport-url'       => 'Project:Financieel bijdragen',
'faq'                   => 'FAQ (veelgestelde vragen)',
'faqpage'               => 'Project:Veelgestelde vragen',
'edithelp'              => 'Hulp bij bewerken',
'newwindow'             => '(nieuw venster)',
'edithelppage'          => 'Help:Bewerken',
'cancel'                => 'Annuleren',
'qbfind'                => 'Zoeken',
'qbbrowse'              => 'Bladeren',
'qbedit'                => 'Bewerken',
'qbpageoptions'         => 'Pagina-opties',
'qbpageinfo'            => 'Pagina-informatie',
'qbmyoptions'           => 'Mijn opties',
'qbspecialpages'        => 'Speciale pagina\'s',
'moredotdotdot'         => 'Meer...',
'mypage'                => 'Mijn gebruikerspagina',
'mytalk'                => 'Mijn overleg',
'anontalk'              => 'Overlegpagina voor dit IP-adres',
'navigation'            => 'Navigatie',

# Metadata in edit box
'metadata_help'         => 'Metadata (zie [[Project:Metadata]] voor toelichting):',

'currentevents'         => 'In het nieuws',
'currentevents-url'     => 'In het nieuws',

'disclaimers'           => 'Voorbehoud',
'disclaimerpage'        => 'Project:Algemeen voorbehoud',
'privacy'               => 'Privacybeleid',
'privacypage'           => 'Project:Privacybeleid',
'errorpagetitle'        => 'Fout',
'returnto'              => 'Terug naar $1.',
'tagline'               => 'Van {{SITENAME}}',
'search'                => 'Zoeken',
'searchbutton'          => 'Zoeken',
'go'                    => 'OK',
'searcharticle'                    => 'OK',
'history'               => 'Geschiedenis',
'history_short'         => 'Geschiedenis',
'updatedmarker'         => 'bewerkt sinds mijn laatste bezoek',
'info_short'            => 'Informatie',
'printableversion'      => 'Printervriendelijke versie',
'permalink'             => 'Permalink',
'print'                 => 'Afdrukken',
'edit'                  => 'Bewerk',
'editthispage'          => 'Bewerk deze pagina',
'delete'                => 'Verwijder',
'deletethispage'        => 'Verwijder deze pagina',
'undelete_short'        => '$1 {{PLURAL:$1|bewerking|bewerkingen}} terugplaatsen',
'protect'               => 'Beveilig',
'protectthispage'       => 'Beveiligen',
'unprotect'             => 'Beveiliging opheffen',
'unprotectthispage'     => 'Beveiliging opheffen',
'newpage'               => 'Nieuwe pagina',
'talkpage'              => 'Overlegpagina',
'specialpage'           => 'Speciale pagina',
'personaltools'         => 'Persoonlijke instellingen',
'postcomment'           => 'Voeg opmerking toe',
'articlepage'           => 'Toon pagina',
'talk'                  => 'Overleg',
'views'                 => 'Aspecten/acties',
'toolbox'               => 'Hulpmiddelen',
'userpage'              => 'Toon gebruikerspagina',
'projectpage'           => 'Toon projectpagina',
'imagepage'             => 'Toon afbeeldingspagina',
'mediawikipage'         => 'Toon berichtpagina',
'templatepage'          => 'Toon sjabloonpagina',
'viewhelppage'          => 'Toon helppagina',
'categorypage'          => 'Toon categoriepagina',
'viewtalkpage'          => 'Toon overlegpagina',
'otherlanguages'        => 'Andere talen',
'redirectedfrom'        => '(Doorverwezen vanaf $1)',
'autoredircomment'      => 'Verwijst door naar [[$1]]',
'redirectpagesub'       => 'Doorverwijspagina',
'lastmodifiedat'        => 'Deze pagina is het laatst bewerkt op $2, $1.',
'viewcount'             => 'Deze pagina is $1 maal bekeken.',
'copyright'             => 'Inhoud is beschikbaar onder de $1.',
'protectedpage'         => 'Beveiligde pagina',
'jumpto'                => 'Ga naar:',
'jumptonavigation'      => 'navigatie',
'jumptosearch'          => 'zoek',

'badaccess'             => 'Geen toestemming',
'badaccess-group0' => 'U heeft geen rechten om de gevraagde handeling uit te voeren.',
'badaccess-group1' => 'De gevraagde handeling is voorbehouden aan gebruikers in de groep $1.',
'badaccess-group2' => 'De gevraagde handeling is voorbehouden aan gebruikers in een van de groepen $1.',
'badaccess-groups' => 'De gevraagde handeling is voorbehouden aan gebruikers in een van de groepen $1.',

'versionrequired'       => 'Versie $1 van MediaWiki is vereist',
'versionrequiredtext'   => 'Versie $1 van MediaWiki is vereist om deze pagina te gebruiken. Zie [[Special:Version]]',

'retrievedfrom'         => 'Teruggeplaatst van "$1"',
'youhavenewmessages'    => 'U heeft $1 ($2).',
'newmessageslink'       => 'nieuwe berichten',
'newmessagesdifflink'   => 'toon de bewerking',
'editsection'           => 'bewerk',
'editold'               => 'bewerk',
'editsectionhint'       => 'Bewerk deelpagina: $1',
'toc'                   => 'Inhoud',
'showtoc'               => 'tonen',
'hidetoc'               => 'verbergen',
'thisisdeleted'         => '$1 tonen of terugplaatsen?',
'viewdeleted'           => '$1 tonen?',
'restorelink'           => '$1 verwijderde {{PLURAL:$1|versie|versies}}',
'feedlinks'             => 'Feed:',
'feed-invalid'          => 'Feedtype wordt niet ondersteund.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'            => 'Pagina',
'nstab-user'            => 'Gebruiker',
'nstab-media'           => 'Mediapagina',
'nstab-special'         => 'Speciaal',
'nstab-project'         => 'Projectpagina',
'nstab-image'           => 'Bestand',
'nstab-mediawiki'       => 'Bericht',
'nstab-template'        => 'Sjabloon',
'nstab-help'            => 'Help',
'nstab-category'        => 'Categorie',

# Main script and global functions
#
'nosuchaction'          => 'Opgegeven handeling bestaat niet',
'nosuchactiontext'      => 'De opdracht in de URL is niet herkend door de wiki',
'nosuchspecialpage'     => 'Deze speciale pagina bestaat niet',
'nospecialpagetext'     => 'U heeft een niet bestaande speciale pagina opgevraagd. Een lijst met speciale pagina\'s staat op [[Special:Specialpages]].',

# General errors
#
'error'                 => 'Fout',
'databaseerror'         => 'Databasefout',
'dberrortext'           => 'Er is een syntaxisfout in de databasequery opgetreden.
Mogelijk zit er een fout in de software.
De laatste query naar de database was:
<blockquote><tt>$1</tt></blockquote>
vanuit de functie "<tt>$2</tt>".
MySQL gaf the foutmelding "<tt>$3: $4</tt>".',
'dberrortextcl'         => 'Er is een syntaxisfout in de databasequery opgetreden.
De laatste query naar de database was:
"$1"
vanuit de functie "$2".
MySQL gaf de volgende foutmelding: "$3: $4"',
'noconnect'             => 'Sorry! De wiki ondervindt technische moeilijkheden en kan de database niet bereiken. <br />
$1',
'nodb'                  => 'Kon database $1 niet selecteren',
'cachederror'           => 'De getoonde pagina is een kopie uit de cache en deze kan verouderd zijn.',
'laggedslavemode'       => 'Waarschuwing: De pagina kan verouderd zijn.',
'readonly'              => 'Database geblokkeerd',
'enterlockreason'       => 'Geef een reden op voor de blokkade en geef op wanneer die waarschijnlijk wordt opgeheven.',
'readonlytext'          => 'De database is op het moment geblokkeerd voor bewerkingen, waarschijnlijk vanwege regulier databaseonderhoud. Na afronding wordt de functionaliteit hersteld.

De beheerder heeft de volgende reden opgegeven: $1',
'missingarticle'        => 'In de database is geen tekst aangetroffen voor een pagina met de naam "$1".

Dit wordt meestal veroorzaakt door het volgen van een verouderde link of een link uit de geschiedenis naar een pagina die is verwijderd.

Als dit niet het geval is, dan heeft u een fout in de software gevonden.
Rapporteer dit alstublieft aan een beheerder met vermelding van de URL.',
'readonly_lag'          => 'De database is automatisch vergrendeld terwijl de slave databaseservers synchroniseren met de master.',
'internalerror'         => 'Interne fout',
'filecopyerror'         => 'Bestand "$1" kon niet gekopieerd worden naar "$2".',
'filerenameerror'       => '"$1" kon niet hernoemd worden naar "$2".',
'filedeleteerror'       => 'Bestand "$1" kon niet verwijderd worden.',
'filenotfound'          => 'Bestand "$1" is niet gevonden.',
'unexpected'            => 'Onverwachte waarde: "$1"="$2".',
'formerror'             => 'Fout: formulier kon niet verzonden worden',
'badarticleerror'       => 'Deze handeling kan niet op deze pagina worden uitgevoerd.',
'cannotdelete'          => 'De pagina of het bestand kon niet verwijderd worden. Mogelijk is deze al door iemand anders verwijderd.',
'badtitle'              => 'Ongeldige paginanaam',
'badtitletext'          => 'De opgevraagde pagina was ongeldig, leeg, of een verkeerd gelinkte intertaal- of interwikinaam. Wellicht bevat de paginanaam niet toegestane karakters.',
'perfdisabled'          => 'Sorry! Deze functionaliteit is tijdelijk uitgeschakeld omdat deze de database zo langzaam maakt dat niemand de wiki kan gebruiken.',
'perfdisabledsub'       => 'Hieronder staat een opgeslagen kopie van $1:',
'perfcached'            => 'De getoonde gegevens komen uit een cache en zijn mogelijk niet actueel.',
'perfcachedts'          => 'De getoonde gegevens komen uit een cache en zijn voor het laatst bijgewerkt op $1.',
'wrong_wfQuery_params'  => 'Incorrecte parameters voor wfQuery()<br />
Functie: $1<br />
Query: $2',
'viewsource'            => 'Toon brontekst',
'viewsourcefor'         => 'voor $1',
'protectedtext'         => 'Deze pagina is beveiligd en niet te bewerken.

U kunt de broncode bekijken en kopiëren:',
'protectedinterface'    => 'Deze pagina bevat tekst voor berichten van de software en is beveiligd om misbruik te voorkomen.',
'editinginterface'      => '\'\'\'Waarschuwing:\'\'\' U bewerkt een pagina die gebruikt wordt door de software. Bewerkingen op deze pagina beïnvloeden de gebruikers interface van iedereen.',
'sqlhidden'             => '(SQL query verborgen)',

# Login and logout pages
#
'logouttitle'           => 'Gebruiker afmelden',
'logouttext'            => '<strong>U bent nu afgemeld.</strong><br />
U kunt {{SITENAME}} nu anoniem gebruiken of weer aanmelden als dezelfde of een andere gebruiker. Mogelijk blijven een aantal pagina\'s getoond worden alsof u nog bent aangemeld totdat u de cache van uw browser leegt.',
'welcomecreation'       => '== Welkom, $1! ==

Uw account is aangemaakt. Vergeet niet uw voorkeuren voor {{SITENAME}} aan te passen.',
'loginpagetitle'        => 'Gebruikersnaam',
'yourname'              => 'Gebruikersnaam',
'yourpassword'          => 'Wachtwoord',
'yourpasswordagain'     => 'Wachtwoord opnieuw ingeven',
'remembermypassword'    => 'Aanmeldgegevens onthouden',
'yourdomainname'        => 'Uw domein',
'externaldberror'       => 'Er is een fout opgetreden bij het aanmelden bij de database of u heeft geen toestemming uw externe account bij te werken.',
'loginproblem'          => '<b>Er was een probleem bij het aanmelden.</b><br />Probeer het a.u.b. opnieuw.',
'alreadyloggedin'       => '<strong>Gebruiker $1, u bent al aangemeld.</strong><br />',

'login'                 => 'Aanmelden',
'loginprompt'           => 'U moet cookies accepteren om aan te kunnen melden bij {{SITENAME}}.',
'userlogin'             => 'Aanmelden / Inschrijven',
'logout'                => 'Afmelden',
'userlogout'            => 'Afmelden',
'notloggedin'           => 'Niet aangemeld',
'nologin'               => 'Nog geen gebruikersnaam? $1.',
'nologinlink'           => 'Maak een gebruiker aan',
'createaccount'         => 'Gebruiker aanmaken',
'gotaccount'            => 'Heeft u al een gebruikersnaam? $1.',
'gotaccountlink'        => 'Aanmelden',
'createaccountmail'     => 'per e-mail',
'badretype'             => 'De ingevoerde wachtwoorden verschillen van elkaar.',
'userexists'            => 'De gekozen gebruikersnaam is al in gebruik. Kies a.u.b. een andere naam.',
'youremail'             => 'Uw e-mailadres (optioneel)*:',
'username'              => 'Gebruikersnaam:',
'uid'                   => 'Gebruikersnummer:',
'yourrealname'          => 'Uw echte naam *:',
'yourlanguage'          => 'Taal:',
'yourvariant'           => 'Taalvariant',
'yournick'              => 'Tekst voor ondertekening:',
'badsig'                => 'Ongeldige ondertekening; controleer de HTML-tags.',
'email'                 => 'E-mail',
'prefs-help-email-enotif'=> 'Dit e-mailadres wordt ook gebruikt om mededelingen naar u toe te sturen, als u die functies heeft ingesteld.',
'prefs-help-realname'   => '* Echte naam (optioneel): als u deze opgeeft kan deze naam gebruikt worden om u erkenning te geven voor uw werk.',
'loginerror'            => 'Aanmeldfout',
'prefs-help-email'      => '* E-mail (optioneel): Stelt anderen in staat contact met u op te nemen via uw gebruikers- of overlegpagina zonder dat u uw identiteit prijsgeeft.',
'nocookiesnew'          => 'De gebruiker is aangemaakt maar nog niet aangemeld. {{SITENAME}} gebruikt cookies voor het aanmelden van gebruikers. Schakel die a.u.b. in en meld daarna aan met uw nieuwe gebruikersnaam en wachtwoord.',
'nocookieslogin'        => '{{SITENAME}} gebruikt cookies voor het aanmelden van gebruikers. U accepteert geen cookies. Schakel deze optie a.u.b. aan en probeer het opnieuw.',
'noname'                => 'U heeft geen geldige gebruikersnaam opgegeven.',
'loginsuccesstitle'     => 'Aanmelden geslaagd',
'loginsuccess'          => '\'\'\'U bent nu aangemeld bij {{SITENAME}} als "$1".\'\'\'',
'nosuchuser'            => 'De gebruiker "$1" bestaat niet. Controleer de schrijfwijze of maak een nieuwe gebruiker aan.',
'nosuchusershort'       => 'De gebruiker "$1" bestaat niet. Controleer de schrijfwijze.',
'nouserspecified'       => 'U dient een gebruikersnaam op te geven.',
'wrongpassword'         => 'Wachtwoord onjuist. Probeer het opnieuw.',
'wrongpasswordempty'    => 'Het opgegeven wachtwoord was leeg. Probeer het opnieuw.',
'mailmypassword'        => 'E-mail wachtwoord',
'passwordremindertitle' => 'Wachtwoordherinnering van {{SITENAME}}',
'passwordremindertext'  => 'Iemand, waarschijnlijk u, heeft vanaf  IP-adres $1 een verzoek gedaan tot het
toezenden van het wachtwoord voor {{SITENAME}} ($4).
Het wachtwoord voor gebruiker "$2" is "$3".
Meld u nu aan en wijzig dan uw wachtwoord.

Als iemand anders dan u dit verzoek heeft gedaan of als u zich inmiddels het wachtwoord herinnert en het niet langer wilt wijzigen, negeer dit bericht dan en blijf uw bestaande wachtwoord gebruiken.',
'noemail'               => 'Er is geen e-mailadres bekend voor gebruiker "$1".',
'passwordsent'          => 'Het wachtwoord is verzonden naar het e-mailadres voor "$1".
Meld u a.u.b. aan nadat u het heeft ontvangen.',
'eauthentsent'          => 'Er is een bevestigingsmail naar het opgegeven e-mailadres gezonden. Volg de instructies in de e-mail om aan te geven dat het uw e-mailadres is. Tot die tijd wordt er geen e-mail aan het adres gezonden.',
'mailerror'             => 'Fout bij het verzenden van e-mail: $1',
'acct_creation_throttle_hit'=> 'Sorry, er zijn al $1 accounts aangemaakt vanaf dit IP-adres. U kunt geen nieuwe gebruikers meer aanmaken.',
'emailauthenticated'    => 'Uw e-mailadres is bevestigd op $1.',
'emailnotauthenticated' => 'Uw e-mailadres is nog niet bevestigd. U ontvangt geen e-mail voor de onderstaande functies.',
'noemailprefs'          => 'Geef een e-mailadres op om deze functies te gebruiken.',
'emailconfirmlink'      => 'Bevestig uw e-mailadres',
'invalidemailaddress'   => 'Het e-mailadres is niet geaccepteerd omdat het een ongeldige opmaak heeft. Geef a.u.b. een geldig e-mailadres op of laat het veld leeg.',
'accountcreated'        => 'Gebruiker aangemaakt',
'accountcreatedtext'    => 'De gebruiker $1 is aangemaakt.',

# Edit page toolbar
'bold_sample'           => 'Vetgedrukte tekst',
'bold_tip'              => 'Vet',
'italic_sample'         => 'Cursieve tekst',
'italic_tip'            => 'Cursief',
'link_sample'           => 'Onderwerp',
'link_tip'              => 'Interne link',
'extlink_sample'        => 'http://www.example.com linktekst',
'extlink_tip'           => 'Externe link (vergeet http:// niet)',
'headline_sample'       => 'Deelonderwerp',
'headline_tip'          => 'Tussenkopje (hoogste niveau)',
'math_sample'           => 'Voer de formule in',
'math_tip'              => 'Wiskundige formule (LaTeX)',
'nowiki_sample'         => 'Voer hier de niet op te maken tekst in',
'nowiki_tip'            => 'Negeer wiki-opmaak',
'image_sample'          => 'Voorbeeld.png',
'image_tip'             => 'Afbeelding',
'media_sample'          => 'Voorbeeld.ogg',
'media_tip'             => 'Link naar bestand',
'sig_tip'               => 'Uw handtekening met datum en tijd',
'hr_tip'                => 'Horizontale lijn (gebruik spaarzaam)',

# Edit pages
#
'summary'               => 'Samenvatting',
'subject'               => 'Onderwerp/kop',
'minoredit'             => 'Dit is een kleine bewerking',
'watchthis'             => 'Volg deze pagina',
'savearticle'           => 'Pagina opslaan',
'preview'               => 'Nakijken',
'showpreview'           => 'Toon bewerking ter controle',
'showlivepreview'       => 'Toon bewerking ter controle',
'showdiff'              => 'Toon wijzigingen',
'anoneditwarning'       => '\'\'\'Waarschuwing:\'\'\' U bent niet aangemeld. Uw IP-adres wordt opgeslagen als u wijzigingen op deze pagina maakt.',
'missingsummary'        => '\'\'\'Herinnering:\'\'\' U heeft geen samenvatting opgegeven voor uw bewerking. Als u nogmaals op \'\'Pagina opslaan\'\' klikt wordt de bewerking zonder samenvatting opgeslagen.',
'missingcommenttext'    => 'Plaats uw opmerking hieronder, a.u.b.',
'blockedtitle'          => 'Gebruiker is geblokkeerd',
'blockedtext'           => "<big>'''Uw gebruikersnaam of IP-adres is geblokkeerd.'''</big>
De blokkade is ingesteld door $1. De opgegeven reden is ''$2''.
U kunt contact opnemen met $1 of een andere [[Project:Administrators|beheerder]] om de blokkade te bespreken.
U kunt geen gebruik maken van de functie 'E-mail gebruiker' tenzij u een een geldig e-mailadres heeft opgegeven in uw [[Special:Preferences|voorkeuren]].
Uw huidige IP-adres is $3. Vermeld dit adres in eventuele correspondentie.",
'blockedoriginalsource' => 'Hieronder staat de brontekst van \'\'\'$1\'\'\':',
'blockededitsource'     => 'Hieronder staat de tekst van \'\'\'uw bewerkingen\'\'\' aan \'\'\'$1\'\'\':',
'whitelistedittitle'    => 'Voor bewerken is aanmelden verplicht',
'whitelistedittext'     => 'U moet $1 om pagina\'s te bewerken.',
'whitelistreadtitle'    => 'Voor leestoegang is aanmelden verplicht',
'whitelistreadtext'     => '[[Special:Userlogin|Meld u aan]] voor leestoegang tot pagina\'s.',
'whitelistacctitle'     => 'Het aanmaken van nieuwe gebruikers is niet toegestaan',
'whitelistacctext'      => 'U dient [[Special:Userlogin|aangemeld]] te zijn en de juiste rechten te hebben om gebruikers aan te maken in deze Wiki.',
'confirmedittitle'      => 'E-mailbevestiging is verplicht voordat u kunt bewerken',
'confirmedittext'       => 'U moet uw e-mailadres bevestigen voor u kunt bewerken. Voer uw emailadres in en bevestig het via [[Special:Preferences|uw voorkeuren]].',
'loginreqtitle'         => 'Aanmelden verplicht',
'loginreqlink'          => 'aanmelden',
'loginreqpagetext'      => '$1 is verplicht om andere pagina\'s te kunnen zien.',
'accmailtitle'          => 'Wachtwoord verzonden.',
'accmailtext'           => 'Het wachtwoord voor "$1" is verzonden naar $2.',
'newarticle'            => '(Nieuw)',
'newarticletext'        => 'Deze pagina bestaat nog niet. Typ in het onderstaande veld om de pagina aan te maken (meer informatie staat op de [[Help:Inhoud|hulppagina]]).
Gebruik te knop \'\'\'vorige\'\'\' in uw browser als u hier per ongeluk terecht bent gekomen.',
'anontalkpagetext'      => '----\'\'Deze overlegpagina hoort bij een anonieme gebruiker die hetzij geen loginnaam heeft, hetzij deze niet gebruikt. We gebruiken daarom het IP-adres ter identificatie. Het is mogelijk dat meerdere personen hetzelfde IP-adres gebruiken. Mogelijk ontvangt u hier berichten die niet voor u bedoeld zijn. Als u dat wilt voorkomen, [[Special:Userlogin|maak dan een gebruikersnaam aan of meld u aan]].\'\'',
'noarticletext'         => 'Deze pagina bevat geen tekst. U kunt [[Special:Search/{{PAGENAME}}|naar deze term zoeken]] in andere pagina\'s of [{{fullurl:{{FULLPAGENAME}}|action=edit}} deze pagina bewerken].',
'clearyourcache'        => '\'\'\'Let op!\'\'\' Leeg uw cache nadat u de wijzigingen heeft opgeslagen.

{| border="1" cellpadding="3" class=toccolours style="border: 1px #AAAAAA solid; border-collapse: collapse;"
| Mozilla/Safari/Konqueror || CTRL-SHIFT-R
|-
| IE || CTRL-F5
|-
| Opera || F5
|-
| Safari || CMD-R
|-
| Konqueror || F5
|}',
'usercssjsyoucanpreview'=> "<strong>Tip:</strong> Gebruik de knop 'Toon bewerking ter controle' om uw nieuwe css/js te testen alvorens op te slaan.",
'usercsspreview'        => "'''Dit is alleen een voorvertoning van uw persoonlijke css, deze is nog niet opgeslagen!'''",
'userjspreview'         => "'''Let op: u test nu uw persoonlijke  JavaScript. Het is nog niet opgeslagen!'''",
'userinvalidcssjstitle' => "'''Waarschuwing:''' er is geen skin \"$1\". Let op: uw eigen .css- en .js-pagina's beginnen met een kleine letter, bijvoorbeeld User:Naam/monobook.css in plaats van User:Naam/Monobook.css.",
'updated'               => '(Bijgewerkt)',
'note'                  => '<strong>Opmerking:</strong>',
'previewnote'           => '<strong>Let op: dit is een controlepagina; uw tekst is nog niet opgeslagen!</strong>',
'session_fail_preview'  => '<strong>Sorry! Uw bewerking is niet verwerkt omdat sessiegegevens verloren zijn gegaan.
Probeer het opnieuw. Als het dan nog niet lukt, meldt u dan af en weer aan.</strong>',
'previewconflict'       => 'Deze voorvertoning geeft aan hoe de tekst in het bovenste veld eruit ziet als u deze opslaat.',
'session_fail_preview_html'=> '<strong>Sorry! Uw bewerking is niet verwerkt omdat sessiegegevens verloren zijn gegaan.</strong>

\'\'Omdat in deze wiki ruwe HTML is ingeschakeld, is een voorvertoning niet mogelijk als bescherming tegen aanvallen met JavaScript.\'\'

<strong>Als dit een legitieme bewerking is, probeer het dan opnieuw. Als het dan nog niet lukt, meldt u dan af en weer aan.</strong>',
'importing'             => 'Bezig met importeren van $1',
'editing'               => 'Bezig met bewerken van $1',
'editinguser'               => 'Bezig met bewerken van $1',
'editingsection'        => 'Bezig met bewerken van $1 (deelpagina)',
'editingcomment'        => 'Bezig met bewerken van $1 (opmerking)',
'editconflict'          => 'Bewerkingsconflict: $1',
'explainconflict'       => 'Een andere gebruiker heeft deze pagina bewerkt sinds u met uw bewerking bent begonnen.
In het bovenste deel van het venster staat de tekst van de huidige pagina.
Uw bewerking staat nog in het onderste gedeelte.
U dient uw bewerkingen in te voegen in de bestaande tekst. 
<b>Alleen</b> de tekst in het bovenste gedeelte wordt opgeslagen als u op "Pagina opslaan" klikt.<br />',
'yourtext'              => 'Uw tekst',
'storedversion'         => 'Opgeslagen versie',
'nonunicodebrowser'     => '<strong>WAARSCHUWING: Uw browser kan niet goed overweg met unicode. Hiermee wordt door de Mediawiki rekening gehouden zodat u toch zonder problemen pagina\'s kan bewerken: niet-ASCII karakters worden in het bewerkingsveld weergegeven als hexadecimale codes.</strong>',
'editingold'            => '<strong><span style="color:#ff0000">Waarschuwing!</span> U bewerkt een oude versie van deze pagina. Als u uw bewerking opslaat, gaan alle wijzigingen die na deze versie gemaakt zijn verloren.</strong>',
'yourdiff'              => 'Wijzigingen',
'copyrightwarning'      => 'Opgelet: Alle bijdragen aan {{SITENAME}} worden geacht te zijn vrijgegeven onder de $2 (zie $1 voor details). Als u niet wilt dat uw tekst door anderen naar believen bewerkt en verspreid kan worden, kies dan niet voor \'Pagina Opslaan\'.<br />
Hierbij belooft u ons tevens dat u deze tekst zelf heeft geschreven, of overgenomen uit een vrije, openbare bron.<br />
<strong>GEBRUIK GEEN MATERIAAL DAT BESCHERMD WORDT DOOR AUTEURSRECHT, TENZIJ U DAARTOE TOESTEMMING HEEFT!</strong>',
'copyrightwarning2'     => 'Al uw bijdragen aan {{SITENAME}} kunnen bewerkt, gewijzigd of verwijderd worden door andere gebruikers. Als u niet wilt dat uw teksten rigoureus aangepast worden door anderen, plaats ze hier dan niet.<br />
U belooft ook u dat u de oorspronkelijke auteur bent van dit materiaal, of dat u het heeft gekopieerd uit een bron in het publieke domein, of een soortgelijke vrije bron (zie $1 voor details).
<strong>GEBRUIK GEEN MATERIAAL DAT BESCHERMD WORDT DOOR AUTEURSRECHT, TENZIJ U DAARTOE TOESTEMMING HEEFT!</strong>',
'longpagewarning'       => '<strong>Deze pagina is $1 kilobyte groot; sommige browsers hebben problemen met het bewerken van pagina\'s die groter zijn dan 32kb. Wellicht kan deze pagina gesplitst worden in kleinere delen.</strong>',
'longpageerror'         => '<strong>ERROR: De tekst die u heeft toegevoegd heeft is $1 kilobyte
groot, wat groter is dan het maximum van $2 kilobyte. Opslaan is niet mogelijk.</strong>',
'readonlywarning'       => '<strong>WAARSCHUWING: De database accepteert geen bewerkingen, dus u kunt deze nu niet opslaan. Het is misschien verstandig uw tekst lokaal op te slaan in een bestand met kopiëren en plakken zodat u die hier later weer kunt invoegen.</strong>',
'protectedpagewarning'  => '<strong>WAARSCHUWING! Deze beveiligde pagina kan alleen door gebruikers met beheerdersrechten bewerkt worden.</strong>',
'semiprotectedpagewarning'=> '\'\'\'Let op:\'\'\' Deze pagina is beveiligd en kan alleen door geregistreerde gebruikers bewerkt worden.',
'templatesused'         => 'Op deze pagina gebruikte sjablonen:',
'edittools'             => '<!-- Deze tekst wordt weergegeven onder bewerkings- en uploadformulieren. -->',
'nocreatetitle'         => 'Het aanmaken van pagina\'s is beperkt',
'nocreatetext'          => 'Deze website heeft de mogelijkheid om nieuwe pagina\'s te maken beperkt.
U kunt reeds bestaande pagina\'s wijzigen, of u kunt [[Special:Userlogin|zich aanmelden of een gebruiker aanmaken]].',
'cantcreateaccounttitle' => 'Kan gebruiker niet aanmaken',
'cantcreateaccounttext' => 'Het aanmaken van gebruikers vanaf dit IP-adres (<b>$1</b>) is geblokkeerd. Dit komt mogelijk door aanhoudend vandalisme vanuit uw onderwijsinstelling of Internet service provider.',

# History pages
#
'revhistory'            => 'Bewerkingsgeschiedenis',
'viewpagelogs'          => 'Toon logboek voor deze pagina',
'nohistory'             => 'Deze pagina is nog niet bewerkt.',
'revnotfound'           => 'Bewerking niet gevonden',
'revnotfoundtext'       => 'De opgevraagde oude versie van deze pagina is onvindbaar. Controleer a.u.b. de URL die u gebruikte om naar deze pagina te gaan.',
'loadhist'              => 'Bezig met het laden van de paginageschiedenis',
'currentrev'            => 'Huidige versie',
'revisionasof'          => 'Versie op $1',
'revision-info' => 'Versie per $1; $2',
'previousrevision'      => '←Oudere versie',
'nextrevision'          => 'Nieuwere versie→',
'currentrevisionlink'   => 'Huidige versie',
'cur'                   => 'huidig',
'next'                  => 'volgende',
'last'                  => 'vorige',
'histlegend'            => 'Selectie voor diff: selecteer de te vergelijken versies en toets ENTER of de knop onderaan.<br />
Verklaring afkortingen: (huidig) = verschil met huidige versie, (vorige) = verschil met voorgaande versie, k = kleine wijziging',
'deletedrev'            => '[verwijderd]',
'histfirst'             => 'Oudste',
'histlast'              => 'Nieuwste',
'rev-deleted-comment'   => '(opmerking verwijderd)',
'rev-deleted-user'      => '(gebruiker verwijderd)',
'rev-deleted-text-permission'=> '<div class="mw-warning plainlinks">
De geschiedenis van deze pagina is verwijderd uit de publieke archieven.
Er kunnen details aanwezig zijn in het [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} verwijderlogboek].
</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">
De geschiedenis van deze pagina is verwijderd uit de publieke archieven.
Als beheerder van deze site kunt u deze zien;
er kunnen details aanwezig zijn in het [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} verwijderlogboek].
</div>',
'rev-delundel'          => 'toon/verberg',

'history-feed-title'    => 'Bewerkingsoverzicht',
'history-feed-description'=> 'Bewerkingsoverzicht voor deze pagina op de wiki',
'history-feed-item-nocomment'=> '$1 op $2',
'history-feed-empty'    => 'De gevraagde pagina bestaat niet.
Wellicht is die verwijderd of hernoemd.
[[Special:Search|Doorzoek de wiki]] voor relevante pagina\'s.',

# Revision deletion
#
'revisiondelete'        => 'Verwijder/Herstel bewerkingen',
'revdelete-nooldid-title'=> 'Geen doelversie',
'revdelete-nooldid-text'=> 'U heeft geen doelversie(s) voor deze handeling opgegeven.',
'revdelete-selected'    => 'Geselecteerde bewerking van [[:$1]]:',
'revdelete-text'        => 'Verwijderde bewerkingen zijn nog steeds zichtbaar in de geschiedenis, maar de inhoud is niet langer publiek toegankelijk.

Andere beheerders van deze wiki kunnen nog steeds de verborgen inhoud benaderen en de verwijdering ongedaan maken met behulp van dit scherm, tenzij er additionele restricties gelden die zijn ingesteld door de systeembeheerder.',
'revdelete-legend'      => 'Stel versiebeperkingen in:',
'revdelete-hide-text'   => 'Verberg de bewerkte tekst',
'revdelete-hide-comment'=> 'Verberg de bewerkingssamenvatting',
'revdelete-hide-user'   => 'Verberg gebruikersnaam/IP van de gebruiker',
'revdelete-hide-restricted'=> 'Pas deze beperkingen toe op zowel beheerders als anderen',
'revdelete-log'         => 'Opmerking in logboek:',
'revdelete-submit'      => 'Pas toe op de geselecteerde bewerking',
'revdelete-logentry'    => 'zichtbaarheid van bewerkingen is gewijzigd voor [[$1]]',

# Diffs
#
'difference'            => '(Verschil tussen bewerkingen)',
'loadingrev'            => 'bezig versie voor diff te laden',
'lineno'                => 'Regel $1:',
'editcurrent'           => 'Bewerk de huidige versie van deze pagina',
'selectnewerversionfordiff'=> 'Selecteer een nieuwere versie voor de vergelijking',
'selectolderversionfordiff'=> 'Selecteer een oudere versie voor de vergelijking',
'compareselectedversions'=> 'Vergelijk geselecteerde versies',

# Search results
#
'searchresults'         => 'Zoekresultaten',
'searchresulttext'      => 'Voor meer informatie over zoeken op {{SITENAME}}, zie [[Project:Zoeken|Zoeken op {{SITENAME}}]].',
'searchsubtitle'        => "U zocht naar '''[[:$1]]'''",
'searchsubtitleinvalid' => 'Voor zoekopdracht "$1"',
'badquery'              => 'Verkeerd geformuleerde zoekopdracht',
'badquerytext'          => 'Uw vraag kan niet verwerkt worden.
Dit komt waarschijnlijk doordat u heeft gezocht op woorden met minder dan drie letters, wat niet mogelijk is.
Mogelijk heeft u een verkeerde zoekopdracht gebruikt, zoals bijvoorbeeld "fish and and scales".
Probeer het nog een keer.',
'matchtotals'           => 'De zoekterm "$1" is gevonden in $2 onderwerpen en in de tekst van $3 pagina\'s.',
'noexactmatch'          => '\'\'\'Er bestaat geen pagina met onderwerp  $1.\'\'\' U kunt deze [[:$1|aanmaken]].',
'titlematches'          => 'Overeenkomst met onderwerp',
'notitlematches'        => 'Geen resultaten gevonden',
'textmatches'           => 'Overeenkomst met inhoud',
'notextmatches'         => 'Geen pagina\'s gevonden',
'prevn'                 => 'vorige $1',
'nextn'                 => 'volgende $1',
'viewprevnext'          => '($1) ($2) ($3) tonen.',
'showingresults'        => 'Hieronder staan <b>$1</b> resultaten vanaf resultaat <b>$2</b>.',
'showingresultsnum'     => 'Hieronder staan <b>$3</b> resultaten vanaf resultaat <b>$2</b>.',
'nonefound'             => '\'\'\'Opmerking\'\'\': mislukte zoekopdrachten worden vaak veroorzaakt door zoeken naar veelvoorkomende woorden als "van" en "het", die niet in de indexen worden opgenomen, of door meer dan één zoekterm op te geven. Alleen pagina\'s die alle zoektermen bevatten worden opgenomen in de resultaten.',
'powersearch'           => 'Zoeken',
'powersearchtext'       => 'Zoek in naamruimten:<br />$1<br />$2 Toon redirects<br />Zoek naar $3 $9',
'searchdisabled'        => 'Zoeken in {{SITENAME}} is niet mogelijk. U kunt gebruik maken van Google. De gegevens over {{SITENAME}} zijn mogelijk niet bijgewerkt.',

'blanknamespace'        => '(Hoofdnaamruimte)',

# Preferences page
#
'preferences'           => 'Voorkeuren',
'mypreferences'         => 'Mijn voorkeuren',
'prefsnologin'          => 'Niet aangemeld',
'prefsnologintext'      => 'U dient [[Special:Userlogin|aangemeld]] te zijn om uw voorkeuren te kunnen instellen.',
'prefsreset'            => 'Standaardvoorkeuren hersteld.',
'qbsettings'            => 'Menubalk',
'changepassword'        => 'Wachtwoord wijzigen',
'skin'                  => 'Vormgeving',
'math'                  => 'Formules',
'dateformat'            => 'Datumopmaak',
'datedefault'           => 'Geen voorkeur',
'datetime'              => 'Datum en tijd',
'math_failure'          => 'Parsen mislukt',
'math_unknown_error'    => 'onbekende fout',
'math_unknown_function' => 'onbekende functie',
'math_lexing_error'     => 'lexicografische fout',
'math_syntax_error'     => 'syntactische fout',
'math_image_error'      => 'PNG-conversie is mislukt. Ga na of latex, dvips en gs correct geïnstalleerd zijn en converteer nogmaals',
'math_bad_tmpdir'       => 'De map voor tijdelijke bestanden voor wiskundige formules bestaat niet of kan niet gemaakt worden',
'math_bad_output'       => 'De map voor bestanden met wiskundige formules bestaat niet of kan niet gemaakt worden.',
'math_notexvc'          => 'Kan het programma texvc niet vinden; stel alles in volgens de beschrijving in math/README.',
'prefs-personal'        => 'Gebruikersprofiel',
'prefs-rc'              => 'Recente wijzigingen',
'prefs-watchlist'       => 'Volglijst',
'prefs-watchlist-days'  => 'Aantal dagen in de volglijst:',
'prefs-watchlist-edits' => 'Aantal bewerkingen in de uitgebreide volglijst:',
'prefs-misc'            => 'Diversen',
'saveprefs'             => 'Opslaan',
'resetprefs'            => 'Standaardvoorkeuren herstellen',
'oldpassword'           => 'Huidige wachtwoord:',
'newpassword'           => 'Nieuwe wachtwoord:',
'retypenew'             => 'Herhaling nieuwe wachtwoord:',
'textboxsize'           => 'Bewerken',
'rows'                  => 'Regels:',
'columns'               => 'Kolommen:',
'searchresultshead'     => 'Zoeken',
'resultsperpage'        => 'Resultaten per pagina:',
'contextlines'          => 'Regels per resultaat:',
'contextchars'          => 'Context per regel:',
'stubthreshold'         => 'Drempelwaarde voor markering als \'beginnetje\':',
'recentchangescount'    => 'Aantal pagina\'s in Recente wijzigingen:',
'savedprefs'            => 'Uw voorkeuren zijn opgeslagen.',
'timezonelegend'        => 'Tijdzone',
'timezonetext'          => 'Het aantal uren dat uw lokale tijd afwijkt van de servertijd (UTC).',
'localtime'             => 'Plaatselijke tijd',
'timezoneoffset'        => 'Tijdsverschil¹',
'servertime'            => 'Servertijd',
'guesstimezone'         => 'Vanuit de browser toevoegen',
'allowemail'            => 'E-mail van andere gebruikers toestaan',
'defaultns'             => 'Zoek standaard in deze naamruimten:',
'default'               => 'standaard',
'files'                 => 'Bestanden',

# User rights
'userrights-lookup-user'=> 'Beheer gebruikersgroepen',
'userrights-user-editname'=> 'Voer een gebruikersnaam in:',
'editusergroup'         => 'Bewerk gebruikersgroepen',

'userrights-editusergroup'=> 'Bewerk gebruikersgroepen',
'saveusergroups'        => 'Gebruikersgroepen opslaan',
'userrights-groupsmember'=> 'Lid van:',
'userrights-groupsavailable'=> 'Beschikbare groepen:',
'userrights-groupshelp' => 'Selecteer de groepen waaruit u de gebruiker wilt verwijderen of aan toe wilt voegen.
Niet geselecteerde groepen worden niet gewijzigd. Deselecteer een groep met "Ctrl + linkermuisknop".',

# Groups
'group'                 => 'Groep:',
'group-sysop'           => 'Beheerders',
'group-bureaucrat'      => 'Bureaucraten',
'group-all'             => '(alles)',

'group-sysop-member'    => 'Beheerder',
'group-bureaucrat-member'=> 'Bureaucraat',

'grouppage-bot'         => 'Project:Bots',
'grouppage-sysop'       => 'Project:Beheerders',
'grouppage-bureaucrat'  => 'Project:Bureaucraten',

# Recent changes
#
'changes'               => 'wijzigingen',
'recentchanges'         => 'Recente wijzigingen',
'recentchangestext'     => 'Toon de meest recente wijzigingen op de wiki op deze pagina.',
'rcnote'                => 'Hieronder staan de <strong>$1</strong> laatste bewerkingen in de laatste <strong>$2</strong> dagen, per $3.',
'rcnotefrom'            => 'Wijzigingen sinds <b>$2</b> (met een maximum van <b>$1</b> wijzigingen).',
'rclistfrom'            => 'Toon de wijzigingen vanaf $1',
'rcshowhideminor'       => '$1 kleine wijzigingen',
'rcshowhideliu'         => '$1 aangemelde gebruikers',
'rcshowhideanons'       => '$1 anonieme gebruikers',
'rcshowhidepatr'        => '$1 gecontroleerde bewerkingen',
'rcshowhidemine'        => '$1 mijn bewerkingen',
'rclinks'               => 'Toon de $1 laatste wijzigingen in de laatste $2 dagen<br />$3',
'diff'                  => 'wijz',
'hist'                  => 'gesch',
'hide'                  => 'Verberg',
'show'                  => 'Toon',
'minoreditletter'       => 'k',
'number_of_watching_users_pageview'=> '[$1 keer op een volglijst]',
'rc_categories'         => 'Toon alleen categorieën (scheid met een "|")',
'rc_categories_any'     => 'Elke',

# Upload
#
'upload'                => 'Upload bestand',
'uploadbtn'             => 'Upload bestand',
'reupload'              => 'Opnieuw uploaden',
'reuploaddesc'          => 'Terug naar het uploadformulier.',
'uploadnologin'         => 'Niet aangemeld',
'uploadnologintext'     => 'U dient [[Special:Userlogin|aangemeld]] te zijn
om bestanden te uploaden.',
'upload_directory_read_only'=> 'De webserver kan niet schrijven in de uploadmap ($1).',
'uploaderror'           => 'Uploadfout',
'uploadtext'            => 'Gebruik het onderstaande formulier om bestanden te uploaden. Om eerder geüploade bestanden te bekijken of te zoeken kunt u naar de [[Special:Imagelist|lijst van geüploade bestanden]] gaan. Uploads en verwijderingen worden bijgehouden in het [[Special:Log/upload|uploadlogboek]].

Om de afbeelding of het bestand in te voegen in een pagina kunt u een van de volgende codes gebruiken, al naar gelang het bestandsformaat dat van toepassing is:

* \'\'\'<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Bestand.jpg]]</nowiki>\'\'\'
* \'\'\'<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Bestand.png|alternatieve tekst]]</nowiki>\'\'\'
* \'\'\'<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:Bestand.ogg]]</nowiki>\'\'\'

De laatste link is bedoeld voor mediabestanden.',
'uploadlog'             => 'uploadlogboek',
'uploadlogpage'         => 'Uploadlogboek',
'uploadlogpagetext'     => 'Hieronder staan de nieuwste bestanden.',
'filename'              => 'Bestandsnaam',
'filedesc'              => 'Samenvatting',
'fileuploadsummary'     => 'Samenvatting:',
'filestatus'            => 'Auteursrechtensituatie',
'filesource'            => 'Bron',
'copyrightpage'         => 'Project:Auteursrechten',
'copyrightpagename'     => '{{SITENAME}} auteursrechten',
'uploadedfiles'         => 'Geüploade bestanden',
'ignorewarning'         => 'Negeer deze waarschuwing en sla het bestand toch op.',
'ignorewarnings'        => 'Negeer alle waarschuwingen',
'minlength'             => 'Bestandsnamen hebben een minimale lengte van drie letters.',
'illegalfilename'       => 'De bestandsnaam "$1" bevat ongeldige karakters. Geef het bestand een andere naam, en probeer het dan opnieuw te uploaden.',
'badfilename'           => 'De naam van het bestand is gewijzigd in "$1".',
'badfiletype'           => '".$1" is geen aanbevolen bestandsformaat.',
'largefile'             => 'Aanbeveling: maak bestanden niet groter dan $1 bytes, dit bestand is $2 bytes',
'largefileserver'       => 'Het bestand is groter dan de instelling van de server toestaat.',
'emptyfile'             => 'Het bestand dat u heeft geüpload lijkt leeg te zijn. Dit zou kunnen komen door een typefout in de bestandsnaam. Ga a.u.b. na of u dit bestand werkelijk bedoelde te uploaden.',
'fileexists'            => 'Er bestaat al een bestand met deze naam. Controleer $1 als u niet zeker weet of u het huidige bestand wilt overschrijven.',
'fileexists-forbidden'  => 'Er bestaat al een bestand met deze naam. Upload uw bestand onder een andere naam.
[[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden'=> 'Er bestaat al een bestand met deze naam bij de gedeelte bestanden. Upload het bestand onder een andere naam. [[Image:$1|thumb|center|$1]]',
'successfulupload'      => 'De upload is geslaagd',
'fileuploaded'          => 'De upload van bestand $1 is geslaagd.
Volg alstublieft deze link: $2 naar de beschrijvingspagina en voeg informatie toe over het bestand, zoals waar het vandaan komt, wanneer en door wie het is gemaakt en wat u er nog meer over weet.

Een afbeelding kunt u als volgt toevoegen: <tt><nowiki>[[Image:$1|thumb|Beschrijving]]</nowiki></tt>',
'uploadwarning'         => 'Uploadwaarschuwing',
'savefile'              => 'Bestand opslaan',
'uploadedimage'         => 'heeft "[[$1]]" geüpload',
'uploaddisabled'        => 'Uploaden is uitgeschakeld',
'uploaddisabledtext'    => 'Het uploaden van bestanden is uitgeschakeld op deze wiki.',
'uploadscripted'        => 'Dit bestand bevat HTML- of scriptcode die foutief door uw browser kan worden weergegeven.',
'uploadcorrupt'         => 'Het bestand is corrupt of heeft een onjuiste extensie. Controleer het bestand en upload het opnieuw.',
'uploadvirus'           => 'Het bestand bevat een virus! Details: $1',
'sourcefilename'        => 'Oorspronkelijke bestandsnaam',
'destfilename'          => 'Opslaan als',
'watchthisupload'       => 'Volg deze pagina',
'filewasdeleted'        => 'Er is eerder een bestand met deze naam verwijderd. Raadpleeg het $1 voordat u het opnieuw toevoegt.',

'license'               => 'Licentie',
'nolicense'             => 'Maak een keuze',
'upload_source_url'     => ' (een geldige, publiek toegankelijke URL)',
'upload_source_file'    => ' (een bestand op uw computer)',

# Image list
#
'imagelist'             => 'Bestandslijst',
'imagelisttext'         => 'Hier volgt een lijst met \'\'\'$1\'\'\' {{plural:$1|bestand|bestanden}} gesorteerd $2.',
'imagelistforuser'      => 'Hieronder staan alleen bestanden die door $1 zijn toegevoegd.',
'getimagelist'          => 'bezig met ophalen bestandslijst',
'ilsubmit'              => 'Zoek',
'showlast'              => 'Toon de laatste $1 afbeeldingen gesorteerd $2.',
'byname'                => 'op naam',
'bydate'                => 'op datum',
'bysize'                => 'op grootte',
'imgdelete'             => 'verw',
'imgdesc'               => 'beschrijving',
'imgfile'               => 'bestand',
'imglegend'             => 'Verklaring: (desc) = toon/bewerk bestandsbeschrijving.',
'imghistory'            => 'Bestandsgeschiedenis',
'revertimg'             => 'herstel',
'deleteimg'             => 'verw',
'deleteimgcompletely'   => 'Verwijder alle versies van dit bestand',
'imghistlegend'         => 'Verklaring: (huidig) = huidige afbeelding, (verw) = verwijder de oude versie, (herstel) = gebruik eerdere versie.<br />
<i>Klik op het tijdstip om de versie van het bestand te zien die op dat tijdstip is geüpload.</i>.',
'imagelinks'            => 'Bestandsverwijzingen',
'linkstoimage'          => 'Dit bestand wordt op de volgende pagina\'s gebruikt:',
'nolinkstoimage'        => 'Geen enkele pagina gebruikt dit bestand.',
'sharedupload'          => 'Dit bestand is een gedeelde upload en kan ook door andere projecten gebruikt worden.',
'shareduploadwiki'      => 'Zie $1 voor verdere informatie.',
'shareduploadwiki-linktext'=> 'bestandsbeschrijving',
'noimage'               => 'Er bestaat nog geen bestand met deze naam. U kunt het $1.',
'noimage-linktext'      => 'uploaden',
'uploadnewversion-linktext'=> 'Upload een nieuwe versie van dit bestand',
'imagelist_date'        => 'Datum',
'imagelist_name'        => 'Naam',
'imagelist_user'        => 'Gebruiker',
'imagelist_size'        => 'Grootte (bytes)',
'imagelist_description' => 'Beschrijving',
'imagelist_search_for'  => 'Zoek naar bestand:',

# Mime search
#
'mimesearch'            => 'Zoeken op MIME-type',
'mimetype'              => 'MIME-type:',

# Unwatchedpages
#
'unwatchedpages'        => 'Pagina\'s die niet op een volglijst staan',

# List redirects
'listredirects'         => 'Lijst van doorverwijzingen',

# Unused templates
'unusedtemplates'       => 'Ongebruikte sjablonen',
'unusedtemplatestext'   => 'Deze pagina geeft alle pagina\'s weer in de naamruimte sjabloon die op geen enkele pagina gebruikt worden. Vergeet niet de "Links naar deze pagina" te controleren alvorens dit sjabloon te verwijderen.',
'unusedtemplateswlh'    => 'andere links',

# Random redirect
'randomredirect'        => 'Willekeurige doorverwijzing',

# Statistics
#
'statistics'            => 'Statistieken',
'sitestats'             => 'Statistieken betreffende {{SITENAME}} NL',
'userstats'             => 'Gebruikerstatistieken',
'sitestatstext'         => 'In de database staan \'\'\'$1\'\'\' pagina\'s, inclusief overlegpagina\'s, pagina\'s over {{SITENAME}}, beginnetjes, doorverwijzingen en andere pagina\'s die waarschijnlijk geen content zijn.
Er zijn waarschijnlijk \'\'\'$2\'\'\' pagina\'s met echte content.

Er zijn \'\'\'$8\'\'\' bestanden toegevoegd.

Er zijn \'\'\'$3\'\'\' pagina\'s getoond en \'\'\'$4\'\'\' bewerkingen gemaakt sinds de wiki is opgezet.
Dat komt uit op gemiddeld \'\'\'$5\'\'\' bewerkingen per pagina en \'\'\'$6\'\'\' getoonde pagina\'s per bewerking.

De lengte van de [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] is \'\'\'$7\'\'\'.',
'userstatstext'         => "Er zijn $1 geregistreerde gebruikers; hiervan zijn er '''$2''' (of '''$4%''') $5.",
'statistics-mostpopular'=> 'Meest bekeken pagina\'s',

'disambiguations'       => 'Doorverwijspagina\'s',
'disambiguationspage'   => 'Template:Disambig',
'disambiguationstext'   => 'Hieronder staan pagina\'s die verwijzen naar een <i>disambiguation page</i>. Deze horen waarschijnlijk direct naar het juiste onderwerp te verwijzen. <br />Als doorverwijspagina\'s worden de pagina\'s beschouwd waar $1 in voorkomt.<br />Verwijzingen vanuit andere naamruimtes worden hier niet getoond.',

'doubleredirects'       => 'Dubbele doorverwijzingen',
'doubleredirectstext'   => 'Op elke regel staat de eerste doorverwijspagina, de tweede doorverwijspagina en de eerste regel van de tweede doorverwijzing. Meestal is laatste pagina het eigenlijke doel.',

'brokenredirects'       => 'Onjuiste doorverwijzingen',
'brokenredirectstext'   => 'Hieronder staan doorverwijspagina\'s die een doorverwijzing bevatten naar een niet-bestaande pagina.',

# Miscellaneous special pages
#
'ncategories'           => '$1 {{PLURAL:$1|categorie|categorieën}}',
'nlinks'                => '{{FORMATNUM|$1}} {{PLURAL:$1|verwijzing|verwijzingen}}',
'nmembers'              => '$1 {{PLURAL:$1|item|items}}',
'nrevisions'            => '$1 {{PLURAL:$1|versie|versies}}',
'nviews'                => '$1 keer bekeken',

'lonelypages'           => 'Weespagina\'s',
'lonelypagestext'       => 'Naar de onderstaande pagina\'s wordt vanuit deze wiki niet verwezen.',
'uncategorizedpages'    => 'Niet-gecategoriseerde pagina\'s',
'uncategorizedcategories'=> 'Niet-gecategoriseerde categorieën',
'uncategorizedimages'   => 'Niet-gecatoriseerde afbeeldingen',
'unusedcategories'      => 'Ongebruikte categorieën',
'unusedimages'          => 'Ongebruikte bestanden',
'popularpages'          => 'Populaire pagina\'s',
'wantedcategories'      => 'Niet-bestaande categorieën met de meeste verwijzingen',
'wantedpages'           => 'Niet-bestaande pagina\'s met de meeste verwijzingen',
'mostlinked'            => 'Pagina\'s waar het meest naar verwezen wordt',
'mostlinkedcategories'  => 'Categorieën waar het meest naar verwezen wordt',
'mostcategories'        => 'Pagina\'s met de meeste categorieën',
'mostimages'            => 'Meest gebruikte bestanden',
'mostrevisions'         => 'Pagina\'s met de meeste bewerkingen',
'allpages'              => 'Alle pagina\'s',
'prefixindex'           => 'Prefix-index',
'randompage'            => 'Willekeurige pagina',
'shortpages'            => 'Korte pagina\'s',
'longpages'             => 'Lange pagina\'s',
'deadendpages'          => 'Pagina\'s zonder links',
'deadendpagestext'      => 'De onderstaande pagina\'s verwijzen niet naar andere pagina\'s in deze wiki.',
'listusers'             => 'Gebruikerslijst',
'specialpages'          => 'Speciale pagina\'s',
'spheading'             => 'Speciale pagina\'s voor alle gebruikers',
'restrictedpheading'    => 'Speciale pagina\'s met beperkte toegang',
'recentchangeslinked'   => 'Volg links',
'rclsub'                => '(van pagina\'s waarnaar "$1" verwijst)',
'newpages'              => 'Nieuwe pagina\'s',
'newpages-username'     => 'Gebruikersnaam:',
'ancientpages'          => 'Oudste pagina\'s',
'intl'                  => 'Taallinks',
'move'                  => 'Hernoem',
'movethispage'          => 'Hernoem pagina',
'unusedimagestext'      => '<p>Let op! Het is mogelijk dat er via een directe link verwezen wordt naar een bestand. Een bestand kan hier dus ten onrechte opgenomen zijn.',
'unusedcategoriestext'  => 'Hieronder staan categorieën die zijn aangemaakt, maar door geen enkele pagina of andere categorie gebruikt worden.',

'booksources'           => 'Boekinformatie',
'categoriespagetext'    => 'Deze wiki kent de volgende categorieën.',
'data'                  => 'Gegevens',
'userrights'            => 'Gebruikersrechtenbeheer',
'groups'                => 'Gebruikersgroepen',

'booksourcetext'        => 'Hieronder staan verwijzingen naar andere sites waar nieuwe en gebruikte boeken verkocht worden. Mogelijk is er ook verdere informatie te vinden over boeken waar u naar op zoek bent.',
'alphaindexline'        => '$1 tot $2',
'version'               => 'Softwareversie',
'log'                   => 'Logboeken',
'alllogstext'           => 'Dit is het gecombineerde logboek. U kunt ook kiezen voor specifieke logboeken en filteren op gebruiker en paginanaam.',
'logempty'              => 'Er zijn geen regels in het logboek die voldoen aan deze criteria.',

# Special:Allpages
'nextpage'              => 'Volgende pagina ($1)',
'allpagesfrom'          => 'Toon pagina\'s vanaf:',
'allarticles'           => 'Alle pagina\'s',
'allinnamespace'        => 'Alle pagina\'s (naamruimte $1)',
'allnotinnamespace'     => 'Alle pagina\'s (niet in naamruimte $1)',
'allpagesprev'          => 'Vorige',
'allpagesnext'          => 'Volgende',
'allpagessubmit'        => 'OK',
'allpagesprefix'        => 'Toon pagina\'s die beginnen met:',
'allpagesbadtitle'      => 'De opgegeven paginanaam is ongeldig of had een intertaal of interwiki voorvoegsel. Mogelijk bevatte de naam karakters die niet gebruikt mogen worden in paginanamen.',

# Special:Listusers
'listusersfrom'         => 'Toon gebruikers vanaf:',

# E this user
#
'mailnologin'           => 'Geen verzendadres beschikbaar',
'mailnologintext'       => 'U dient [[Special:Userlogin|aangemeld]] te zijn en een geldig e-mailadres in uw [[Special:Preferences|voorkeuren]] te vermelden om andere gebruikers te mailen.',
'emailuser'             => 'E-mail deze gebruiker',
'emailpage'             => 'E-mail gebruiker',
'emailpagetext'         => 'Als deze gebruiker een geldig e-mailadres heeft opgegeven dan kunt u via dit formulier een bericht verzenden. Het e-mailadres dat u heeft opgegeven bij uw voorkeuren wordt als afzender gebruikt.',
'usermailererror'       => 'Foutmelding bij het verzenden:',
'defemailsubject'       => '{{SITENAME}} e-mail',
'noemailtitle'          => 'Van deze gebruiker is geen e-mailadres bekend',
'noemailtext'           => 'Deze gebruiker heeft geen e-mailadres opgegeven of wil geen e-mail ontvangen van andere gebruikers.',
'emailfrom'             => 'Van',
'emailto'               => 'Aan',
'emailsubject'          => 'Onderwerp',
'emailmessage'          => 'Bericht',
'emailsend'             => 'Verstuur',
'emailsent'             => 'E-mail verzonden',
'emailsenttext'         => 'Uw e-mail is verzonden.',

# Watchlist
'watchlist'             => 'Volglijst',
'watchlistfor'          => "(voor '''$1''')",
'nowatchlist'           => 'Uw volglijst is leeg.',
'watchlistanontext'     => '$1 is verplicht om uw volglijst in te zien of te wijzigen.',
'watchlistcount'        => "'''Uw volglijst bevat {{PLURAL:$1|één pagina|$1 pagina's}}, inclusief overlegpagina's.'''",
'clearwatchlist'        => 'Wis volglijst',
'watchlistcleartext'    => 'Weet u zeker dat u ze wilt verwijderen?',
'watchlistclearbutton'  => 'Wis volglijst',
'watchlistcleardone'    => "Uw volglijst is gewist. Er {{PLURAL:$1|is één pagina|zijn $1 pagina's}} verwijderd).",
'watchnologin'          => 'U bent niet aangemeld',
'watchnologintext'      => 'U dient [[Special:Userlogin|aangemeld]] te zijn om uw volglijst te bewerken.',
'addedwatch'            => 'Toegevoegd aan volglijst',
'addedwatchtext'        => 'De pagina "[[:$1]]" is toegevoegd aan uw [[Special:Watchlist|volglijst]].
Toekomstige bewerkingen van deze pagina en de bijbehorende overlegpagina worden op [[Special:Watchlist|uw volglijst]] vermeld en worden \'\'\'vet\'\'\' weergegeven in de [[Special:Recentchanges|lijst van recente wijzigingen]].

Indien u een pagina niet langer wilt volgen, ga dan naar de pagina en klik op "Niet volgen" in de menubalk.',
'removedwatch'          => 'Verwijderd van volglijst',
'removedwatchtext'      => 'De pagina "[[:$1]]" is van uw volglijst verwijderd.',
'watch'                 => 'Volg',
'watchthispage'         => 'Volg deze pagina',
'unwatch'               => 'Niet volgen',
'unwatchthispage'       => 'Niet meer volgen',
'notanarticle'          => 'Is geen pagina',
'watchnochange'         => 'Geen van de pagina\'s op uw volglijst is in deze periode bewerkt.',
'watchdetails'          => '* Er {{PLURAL:$1|staat één pagina|staan $1 pagina\'s}} op uw volglijst, exclusief overlegpagina\'s
* [[Special:Watchlist/edit|Toon en bewerk de volledige volglijst]]
* [[Special:Watchlist/clear|Verwijder alle pagina\'s van de volglijst]]',
'wlheader-enotif'       => '* U wordt per e-mail gewaarschuwd',
'wlheader-showupdated'  => '* Pagina\'s die zijn bewerkt sinds uw laatste bezoek worden \'\'\'vet\'\'\' weergegeven',
'watchmethod-recent'    => 'controleer recente wijzigingen op pagina\'s op volglijst',
'watchmethod-list'      => 'controleer pagina\'s op volglijst op wijzigingen',
'removechecked'         => 'Verwijderen',
'watchlistcontains'     => 'Er staan $1 pagina\'s op uw volglijst.',
'watcheditlist'         => 'Hieronder staan alle pagina\'s op uw volglijst, alfabetisch gesorteerd. Vink de vakjes aan voor de pagina\'s die u wilt verwijderen en druk dan op \'Verwijderen\' onderaan deze pagina. Door een pagina te verwijderen, verwijdert u ook het volgen van de bijbehorende overlegpagina en vice versa.',
'removingchecked'       => 'De aangegeven pagina\'s worden van uw volglijst verwijderd...',
'couldntremove'         => 'Het was niet mogelijk object \'$1\' te verwijderen...',
'iteminvalidname'       => 'Probleem met object \'$1\', ongeldige naam...',
'wlnote'                => 'Hieronder staan de laatste $1 wijzigingen in de laatste $2 uur.',
'wlshowlast'            => 'Toon de laatste $1 uur $2 dagen $3',
'wlsaved'               => 'Dit is een opgeslagen versie van uw volglijst.',
'wlhideshowown'         => '$1 mijn bewerkingen.',
'wlhideshowbots'        => '$1 botbewerkingen.',
'wldone'                => 'Uitgevoerd.',

'enotif_mailer'         => '{{SITENAME}} waarschuwingssysteem',
'enotif_reset'          => 'Markeer alle pagina\'s als bezocht',
'enotif_newpagetext'    => 'Dit is een nieuwe pagina.',
'changed'               => 'gewijzigd',
'created'               => 'aangemaakt',
'enotif_subject'        => 'Pagina $PAGETITLE op {{SITENAME}} is $CHANGEDORCREATED door $PAGEEDITOR',
'enotif_lastvisited'    => 'Zie $1 voor alle wijzigingen sinds uw laatste bezoek.',
'enotif_body'           => 'Beste $WATCHINGUSERNAME,

De pagina $PAGETITLE op {{SITENAME}} is $CHANGEDORCREATED op $PAGEEDITDATE door $PAGEEDITOR, zie $PAGETITLE_URL voor de huidige versie.

$NEWPAGE

Samenvatting van de wijziging: $PAGESUMMARY $PAGEMINOREDIT

Contactgevevens van de auteur:
E-mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Tenzij u deze pagina bezoekt komen er geen verdere berichten. Op uw volglijst kunt u voor alle gevolgde pagina\'s de waarschuwingsinstellingen opschonen.

             Groet van uw {{SITENAME}} waarschuwingssysteem.

--
U kunt uw volglijstinstellingen wijzigen op:
{{fullurl:Special:Watchlist/edit}}

Feedback en andere assistentie:
{{fullurl:Help:Contents}}',

# Delete/protect/revert
#
'deletepage'            => 'Verwijder pagina',
'confirm'               => 'Bevestig',
'excontent'             => 'De inhoud was: \'$1\'',
'excontentauthor'       => 'inhoud was: \'$1\' (\'$2\' was de enige auteur)',
'exbeforeblank'         => 'De inhoud was: \'$1\'',
'exblank'               => 'pagina was leeg',
'confirmdelete'         => 'Bevestig verwijdering',
'deletesub'             => '("$1" aan het verwijderen)',
'historywarning'        => 'Waarschuwing: de pagina die u wilt verwijderen heeft meerdere versies:',
'confirmdeletetext'     => 'U staat op het punt een pagina of bestand voorgoed te verwijderen, inclusief de geschiedenis. Bevestig hieronder dat dit inderdaad uw bedoeling is, dat u de gevolgen begrijpt en dat uw verwijdering overeenkomt met het beleid op deze wiki.',
'actioncomplete'        => 'Handeling voltooid.',
'deletedtext'           => '"$1" is verwijderd. Zie $2 voor een overzicht van recente verwijderingen.',
'deletedarticle'        => 'verwijderde "[[$1]]"',
'dellogpage'            => 'Logboek verwijderde pagina\'s',
'dellogpagetext'        => 'Hieronder staan recent verwijderde pagina\'s en bestanden.',
'deletionlog'           => 'logboek verwijderde pagina\'s',
'reverted'              => 'Eerdere versie hersteld',
'deletecomment'         => 'Reden voor verwijderen',
'imagereverted'         => 'Herstel naar de eerdere versie is geslaagd.',
'rollback'              => 'Wijzigingen ongedaan maken',
'rollback_short'        => 'Terugdraaien',
'rollbacklink'          => 'terugdraaien',
'rollbackfailed'        => 'Ongedaan maken van wijzigingen mislukt.',
'cantrollback'          => 'Ongedaan maken van wijzigingen onmogelijk: deze pagina heeft slechts 1 auteur.',
'alreadyrolled'         => 'Het is niet mogelijk om de bewerking van de pagina [[:$1]] door [[User:$2|$2]] ([[User talk:$2|overleg]]) ongedaan te maken. Iemand anders heeft deze pagina al bewerkt of hersteld naar een eerdere versie. 

De meest recente bewerking is gemaakt door [[User:$3|$3]] ([[User talk:$3|overleg]]).',
'editcomment'           => 'Bewerkingssamenvatting: "<i>$1</i>".',
'revertpage'            => 'Wijzigingen door [[Special:Contributions/$2|$2]] hersteld tot de laatste versie door [[User:$1|$1]].',
'sessionfailure'        => 'Er lijkt een probleem te zijn met uw aanmeldsessie. Uw handeling is gestopt uit voorzorg tegen een beveiligingsrisico (dat bestaat uit mogelijke "hijacking" van deze sessie). Ga een pagina terug, laad die pagina opnieuw en probeer het nog eens.',
'protectlogpage'        => 'Logboek beveiligde pagina\'s',
'protectlogtext'        => 'Hieronder staan pagina\'s die recentelijk beveiligd zijn, of waarvan de beveiliging is opgeheven.',
'protectedarticle'      => '"[[$1]]" beveiligd',
'unprotectedarticle'    => 'beveiliging "[[$1]]" opgeheven',
'protectsub'            => '(Beveilig "$1")',
'confirmprotecttext'    => 'Wilt u deze pagina inderdaad beveiligen?',
'confirmprotect'        => 'Bevestig beveiliging',
'protectmoveonly'       => 'Alleen beveiligen tegen hernoemen',
'protectcomment'        => 'Reden voor beveiligen',
'unprotectsub'          => '(Beveiliging "$1" opgeheven)',
'confirmunprotecttext'  => 'Wilt u inderdaad de beveiliging van deze pagina opheffen?',
'confirmunprotect'      => 'Bevestig opheffen beveiliging',
'unprotectcomment'      => 'Reden voor opheffen beveiliging',
'protect-unchain'       => 'Maak hernoemen mogelijk',
'protect-text'          => 'Hier kunt u het beveiligingsniveau voor de pagina <strong>$1</strong> bekijken en wijzigen.',
'protect-viewtext'      => 'U heeft geen rechten om de beveiliging te wijzigen. Dit zijn de huidige beveiligingsinstellingen voor <strong>$1</strong>:',
'protect-default'       => '(standaard)',
'protect-level-autoconfirmed'=> 'Blokkeer niet-geregistreerde gebruikers',
'protect-level-sysop'   => 'Alleen beheerders',

# restrictions (nouns)
'restriction-edit'      => 'Bewerk',
'restriction-move'      => 'Hernoem',

# Undelete
'undelete'              => 'Toon verwijderde pagina\'s',
'undeletepage'          => 'Verwijderde pagina\'s tonen en terugplaatsen',
'viewdeletedpage'       => 'Toon verwijderde pagina\'s',
'undeletepagetext'      => 'Hieronder staan pagina\'s die zijn verwijderd en vanuit het archief teruggeplaatst kunnen worden.',
'undeleteextrahelp'     => 'Om de hele pagina inclusief alle eerdere versies terug te plaatsen: laat alle hokjes onafgevinkt en klik op \'\'\'\'\'Terugplaatsen\'\'\'\'\'. Om slechts bepaalde versies terug te zetten: vink de terug te plaatsen versies aan en klik op \'\'\'\'\'Terugplaatsen\'\'\'\'\'. Als u op \'\'\'\'\'Reset\'\'\'\'\' klikt wordt het toelichtingsveld leeggemaakt en worden alle versies gedeselecteerd.',
'undeletearticle'       => 'Verwijderde pagina terugplaatsen',
'undeleterevisions'     => '$1 versies gearchiveerd',
'undeletehistory'       => 'Als u een pagina terugplaatst, worden alle versies hersteld. Als er al een nieuwe pagina met dezelfde naam is aangemaakt, worden deze versies teruggeplaatst en blijft de huidige versie in tact.',
'undeletehistorynoadmin'=> 'Deze pagina is verwijderd. De reden hiervoor staat hieronder, samen met de details van de gebruikers die deze pagina hebben bewerkt vóór de verwijdering. De verwijderde inhoud van de pagina is alleen zichtbaar voor beheerders.',
'undeleterevision'      => 'Verwijderde versie van $1',
'undeletebtn'           => 'Terugplaatsen',
'undeletecomment'       => 'Toelichting:',
'undeletedarticle'      => '"[[$1]]" is teruggeplaatst',
'undeletedrevisions'    => '$1 versies teruggeplaatst',
'undeletedrevisions-files'=> '$1 versies en $2 bestand(en) teruggeplaatst',
'undeletedfiles'        => '$1 bestand(en) teruggeplaatst',
'cannotundelete'        => 'Verwijderen mislukt. Misschien heeft een andere gebruiker de pagina al verwijderd.',
'undeletedpage'         => '<big>\'\'\'$1 is teruggeplaatst\'\'\'</big>

In het [[Special:Log/delete|verwijderlogboek]] staan recente verwijderingen en herstelhandelingen.',

# Namespace form on various pages
'namespace'             => 'Naamruimte:',
'invert'                => 'Omgekeerde selectie',

# Contributions
#
'contributions'         => 'Bijdragen gebruiker',
'mycontris'             => 'Mijn bijdragen',
'contribsub'            => 'Voor $1',
'nocontribs'            => 'Geen wijzigingen gevonden die aan de gestelde criteria voldoen.',
'ucnote'                => 'Hieronder staan de laatste <b>$1</b> wijzigingen van deze gebruiker in de laatste <b>$2</b> dagen.',
'uclinks'               => 'Toon de laatste $1 wijzigingen; toon de laatste $2 dagen.',
'uctop'                 => ' (laatste wijziging)',
'newbies'               => 'nieuwelingen',
'sp-newimages-showfrom' => 'Toon nieuwe afbeeldingen vanaf $1',
'sp-contributions-newest'=> 'Nieuwste',
'sp-contributions-oldest'=> 'Oudste',
'sp-contributions-newer'=> '$1 nieuwere',
'sp-contributions-older'=> '$1 oudere',
'sp-contributions-newbies-sub'=> 'Voor nieuwelingen',

# What links here
#
'whatlinkshere'         => 'Verwijzingen naar deze pagina',
'notargettitle'         => 'Geen doelpagina',
'notargettext'          => 'U heeft niet opgegeven voor welke pagina of gebruiker u deze handeling wilt uitvoeren.',
'linklistsub'           => '(Lijst van verwijzingen)',
'linkshere'             => "De volgende pagina's verwijzen naar '''[[:$1]]''':",
'nolinkshere'           => "Geen enkele pagina verwijst naar '''[[:$1]]'''.",
'isredirect'            => 'redirectpagina',
'istemplate'            => 'ingevoegd als sjabloon',

# Block/unblock IP
#
'blockip'               => 'Gebruiker blokkeren',
'blockiptext'           => 'Gebruik het onderstaande formulier om schrijftoegang voor een gebruiker of IP-adres in te trekken. Doe dit alleen als bescherming tegen vandalisme en in overeenstemming met het [[Project:Policy|beleid]].
Geef hieronder een reden op (bijvoorbeeld welke pagina\'s gevandaliseerd zijn).',
'ipaddress'             => 'IP-adres',
'ipadressorusername'    => 'IP-adres of gebruikersnaam',
'ipbexpiry'             => 'Duur (maak een keuze)',
'ipbreason'             => 'Reden',
'ipbanononly'           => 'Blokkeer alleen anonieme gebruikers',
'ipbcreateaccount'      => 'Voorkomen aanmaken gebruikers',
'ipbsubmit'             => 'Blokkeer deze gebruiker',
'ipbother'              => 'Andere duur',
'ipboptions'            => '15 minuten:15 min,1 uur:1 hour,2 uur:2 hours,6 uur:6 hours,12 uur:12 hours,1 dag:1 day,3 dagen:3 days,1 week:1 week,2 weken:2 weeks,1 maand:1 month,3 maanden:3 months,6 maanden:6 months,1 jaar:1 year,onbeperkt:infinite',
'ipbotheroption'        => 'ander verloop',
'badipaddress'          => 'Geen geldig IP-adres',
'blockipsuccesssub'     => 'Blokkering geslaagd',
'blockipsuccesstext'    => '[[Special:Contributions/$1|$1]] is geblokkeerd.<br />
Zie de [[Special:Ipblocklist|Lijst van geblokkeerde IP-adressen]].',
'unblockip'             => 'Deblokkeer gebruiker',
'unblockiptext'         => 'Gebruik het onderstaande formulier om opnieuw schrijftoegang te geven aan een geblokkeerde gebruiker of IP-adres.',
'ipusubmit'             => 'Blokkade van dit adres opheffen.',
'unblocked'             => 'Blokkade van [[User:$1|$1]] is opgeheven',
'ipblocklist'           => 'Lijst van geblokkeerde gebruikers en IP-adressen.',
'blocklistline'         => 'Op $1 blokkeerde $2: $3 ($4)',
'infiniteblock'         => 'onbeperkt',
'expiringblock'         => 'verloopt op $1',
'anononlyblock'         => 'alleen anoniemen',
'createaccountblock'    => 'gebruikers aanmaken geblokkeerd',
'ipblocklistempty'      => 'Het blokkeerlogboek is leeg.',
'blocklink'             => 'blokkeer',
'unblocklink'           => 'deblokkeer',
'contribslink'          => 'bijdragen',
'autoblocker'           => 'Automatisch geblokkeerd omdat het IP-adres overeenkomt met dat van [[User:$1|$1]], die geblokkeerd is om de volgende reden: "\'\'\'$2\'\'\'"',
'blocklogpage'          => 'Blokkeerlogboek',
'blocklogentry'         => '"[[$1]]" is geblokkeerd voor de duur van $2.',
'blocklogtext'          => 'Hier ziet u een lijst van de recente blokkeringen en deblokkeringen. Automatische blokkeringen en deblokkeringen komen niet in het logboek. Zie de [[Special:Ipblocklist|Ipblocklist]] voor op dit moment geblokkeerde adressen.',
'unblocklogentry'       => 'blokkade van $1 opgeheven',
'range_block_disabled'  => 'De mogelijkheid voor beheerders om een groep IP-addressen te blokkeren is uitgeschakeld.',
'ipb_expiry_invalid'    => 'Ongeldige duur.',
'ipb_already_blocked'   => '"$1" is al geblokkeerd',
'ip_range_invalid'      => 'Ongeldige IP-reeks.',
'proxyblocker'          => 'Proxyblocker',
'ipb_cant_unblock'      => 'Fout: BlokkadeID $1 niet aangetroffen. Wellicht is de blokkade al opgeheven.',
'proxyblockreason'      => 'Dit is een automatische preventieve blokkade omdat u gebruik maakt van een open proxyserver. Neem a.u.b. contact op met uw Internet provider of uw helpdesk en stel die op de hoogte van dit ernstige beveiligingsprobleem.',
'proxyblocksuccess'     => 'Geslaagd.',
'sorbs'                 => 'SORBS DNS-blacklist',
'sorbsreason'           => 'Uw IP-adres is opgenomen in de [http://www.sorbs.net SORBS DNS-blacklist] als open proxyserver.',
'sorbs_create_account_reason'=> 'Uw IP-adres is opgenomen in de [http://www.sorbs.net SORBS DNS-blacklist] als open proxyserver. U kunt geen account aanmaken.',

# Developer tools
#
'lockdb'                => 'Blokkeer de database',
'unlockdb'              => 'Blokkering van de database opheffen',
'lockdbtext'            => 'Waarschuwing: De database blokkeren heeft tot gevolg dat geen enkele gebruiker meer in staat is pagina\'s te bewerken, voorkeuren te wijzigen of iets anders te doen waarvoor wijzigingen in de database nodig zijn.

Bevestig dat u deze handeling wilt uitvoeren en dat u de database vrijgeeft nadat het onderhoud is uitgevoerd.',
'unlockdbtext'          => 'Na het vrijgeven van de database kunnen gebruikers weer pagina\'s bewerken, hun voorkeuren wijzigen of iets anders te doen waarvoor er wijzigingen in de database nodig zijn.

Bevestig dat u deze handeling wilt uitvoeren.',
'lockconfirm'           => 'Ja, ik wil de database blokkeren.',
'unlockconfirm'         => 'Ja, ik wil de database vrijgeven.',
'lockbtn'               => 'Blokkeer de database',
'unlockbtn'             => 'Geef de database vrij',
'locknoconfirm'         => 'U heeft uw keuze niet bevestigd via het vinkvakje.',
'lockdbsuccesssub'      => 'Blokkeren database geslaagd',
'unlockdbsuccesssub'    => 'Database vrijgegeven.',
'lockdbsuccesstext'     => 'De database is geblokkeerd.
<br />Vergeet niet de database vrij te geven zodra u klaar bent met uw onderhoud.',
'unlockdbsuccesstext'   => 'De database is vrijgegeven.',
'lockfilenotwritable'   => 'Geen schrijfrechten op het databaselockbestand. Om de database te blokkeren of de blokkade op te heffen, dient er geschreven te kunnen worden door de webserver.',
'databasenotlocked'     => 'De database is niet geblokkeerd.',

# Make sysop
'makesysoptitle'        => 'Maak een gebruiker beheerder',
'makesysoptext'         => 'Dit formulier wordt door bureaucraten gebruikt om een gebruiker beheerder te maken. Geef de naam van een gebruiker in het veld in en klik op de knop om de gebruiker beheerder te maken.',
'makesysopname'         => 'Gebruikersnaam:',
'makesysopsubmit'       => 'Wijzig de gebruikersrechten',
'makesysopok'           => '<b>Gebruiker "$1" is nu beheerder</b>',
'makesysopfail'         => '<b>Gebruiker "$1" kon geen beheerder gemaakt worden. Heeft u de juiste naam opgegeven?</b>',
'setbureaucratflag'     => 'Maak deze gebruiker ook bureaucraat',
'rightslog'             => 'Gebruikersrechtenlogboek',
'rightslogtext'         => 'Hieronder staan de wijzigingen in gebruikersrechten.',
'rightslogentry'        => 'wijzigde de gebruikersrechten voor $1 van $2 naar $3',
'rights'                => 'Rechten:',
'set_user_rights'       => 'Gebruikersrechten aanpassen',
'user_rights_set'       => '<b>Rechten van gebruiker "$1" bijgewerkt</b>',
'set_rights_fail'       => '<b>Gebruikersrechten voor "$1" konden niet worden aangepast. Heeft u de naam juist ingevoerd?</b>',
'makesysop'             => 'Maak een gebruiker beheerder',
'already_sysop'         => 'Deze gebruiker is al beheerder',
'already_bureaucrat'    => 'Deze gebruiker is al bureaucraat',
'rightsnone'            => '(geen)',

# Move page
#
'movepage'              => 'Hernoem pagina',
'movepagetext'          => 'Door middel van het onderstaande formulier kunt u een pagina hernoemen. De geschiedenis gaat mee naar de nieuwe pagina. De oude naam wordt automatisch een doorverwijzing naar de nieuwe pagina. Verwijzingen naar de oude pagina worden niet aangepast. Controleer na het hernoemen of er geen dubbele of onjuiste doorverwijzingen zijn onstaan. U bent verantwoordelijk voor de continuiteït van de verwijzingen.

Een wijziging van de paginanaam kan \'\'\'alleen\'\'\' worden uitgevoerd als de nieuwe paginanaam:
*nog niet bestaat, of
*slechts een doorverwijspagina zonder verdere geschiedenis is.

<b>WAARSCHUWING!</b>
Voor populaire pagina\'s kan het hernoemen drastische en onvoorziene gevolgen hebben. Zorg ervoor dat u de consequenties overziet voordat u deze handeling uitvoert.',
'movepagetalktext'      => 'De bijbehorende overlegpagina krijgt automatisch een andere naam, \'\'\'tenzij\'\'\':
* De overlegpagina onder de nieuwe naam al bestaat;
* U het onderstaande vinkje deselecteert.',
'movearticle'           => 'Hernoem pagina',
'movenologin'           => 'Niet aangemeld',
'movenologintext'       => 'U dient [[Special:Userlogin|aangemeld]] te zijn om een pagina te hernoemen.',
'newtitle'              => 'Naar de nieuwe paginanaam',
'movepagebtn'           => 'Hernoem pagina',
'pagemovedsub'          => 'Hernoemen pagina geslaagd',
'pagemovedtext'         => 'Pagina "[[$1]]" is hernoemd naar "[[$2]]".',
'articleexists'         => 'De pagina bestaat al of de paginanaam is ongeldig.
Kies a.u.b. een andere paginanaam.',
'talkexists'            => '\'\'\'De pagina is hernoemd, maar de overlegpagina kon niet hernoemd worden omdat er al een pagina met de nieuwe naam bestaat. Combineer de overlegpagina\'s a.u.b. handmatig.\'\'\'',
'movedto'               => 'hernoemd naar',
'movetalk'              => 'Hernoem de bijbehorende overlegpagina',
'talkpagemoved'         => 'De bijbehorende overlegpagina is ook hernoemd.',
'talkpagenotmoved'      => 'De bijhorende overlegpagina is <strong>niet</strong> hernoemd.',
'1movedto2'             => '[[$1]] hernoemd naar [[$2]]',
'1movedto2_redir'       => '[[$1]] hernoemd over de doorverwijzing [[$2]]',
'movelogpage'           => 'Logboek hernoemde pagina\'s',
'movelogpagetext'       => 'Hieronder staan hernoemde pagina\'s.',
'movereason'            => 'Reden',
'revertmove'            => 'terugdraaien',
'delete_and_move'       => 'Verwijderen en hernoemen',
'delete_and_move_text'  => '==Verwijdering nodig== 
Onder de naam "[[$1]]" bestaat al een pagina. Wilt u het verwijderen om plaats te maken voor de te hernoemen pagina?',
'delete_and_move_confirm'=> 'Ja, verwijder de pagina',
'delete_and_move_reason'=> 'Verwijderd in verband met hernoeming',
'selfmove'              => 'U kunt een pagina niet hernoemen naar dezelfde paginanaam.',
'immobile_namespace'    => 'De bron- of doelpaginanaam is van een speciaal type. Een pagina kan niet hernoemd worden naar of van die naamruimte.',

# Export
'export'                => 'Exporteren',
'exporttext'            => 'U kunt de tekst en geschiedenis van een pagina of pagina\'s exporteren naar XML. Dit exportbestand is daarna te importeren in een andere MediaWiki via de pagina Special:Import.

Geef in het onderstaande veld de namen van de te exporteren pagina\'s op, één pagina per regel, en geef aan of u alle versies met de bewerkingssamenvatting of alleen de huidige versies met de bewerkingssamenvatting wilt exporteren.

In het laatste geval kunt u ook een link gebruiken, bijvoorbeeld [[Special:Export/{{Mediawiki:Mainpage}}]] voor de pagina {{Mediawiki:Mainpage}}.',
'exportcuronly'         => 'Alleen de laatste versie, niet de volledige geschiedenis',
'exportnohistory'       => '---- 
\'\'\'Let op:\'\'\' het exporteren van de gehele geschiedenis is uitgeschakeld wegens prestatieredenen.',
'export-submit'         => 'Exporteer',

# Namespace 8 related
'allmessages'           => 'Systeemteksten',
'allmessagesname'       => 'Naam',
'allmessagesdefault'    => 'Standaardinhoud',
'allmessagescurrent'    => 'Huidige inhoud',
'allmessagestext'       => 'Hieronder staan de systeemberichten  uit de MediaWiki-naamruimte:',
'allmessagesnotsupportedUI'=> 'De taal die u heeft geselecteerd voor berichten (<b>$1</b>) wordt niet ondersteund door Special:Allmessages op deze wiki.',
'allmessagesnotsupportedDB'=> 'Er is geen ondersteuning voor Special:AllMessages omdat \'\'\'$wgUseDatabaseMessages\'\'\' is uitgeschakeld.',
'allmessagesfilter'     => 'Bericht naamfilter:',
'allmessagesmodified'   => 'Toon alleen gewijzigde systeemteksten',

# Thumbnails
'thumbnail-more'        => 'Groter',
'missingimage'          => '<b>Afbeelding ontbreekt</b><br /><i>$1</i>',
'filemissing'           => 'Bestand is zoek',
'thumbnail_error'       => 'Fout bij het aanmaken van thumbnail: $1',

# Special:Import
'import'                => 'Pagina\'s importeren',
'importinterwiki'       => 'Transwiki-import',
'import-interwiki-text' => 'Selecteer een wiki en paginanaam om te importeren.
Versie- en auteursgegevens blijven hierbij in tact.
Alle transwiki-importhandelingen worden opgeslagen in het [[Special:Log/import|importlogboek]].',
'import-interwiki-history'=> 'Kopieer de volledige geschiedenis van deze pagina',
'import-interwiki-submit'=> 'Importeer',
'import-interwiki-namespace'=> 'Plaats pagina\'s in de volgende naamruimte:',
'importtext'            => 'Gebruik de functie Special:Export in de wiki waar de informatie vandaan komt, sla de uitvoer op uw eigen systeem op, en voeg die daarna hier toe.',
'importstart'           => 'Pagina\'s aan het importeren...',
'import-revision-count' => '$1 {{PLURAL:$1|versie|versies}}',
'importnopages'         => 'Geen pagina\'s te importeren.',
'importfailed'          => 'Import is mislukt: $1',
'importunknownsource'   => 'Onbekend importbrontype',
'importcantopen'        => 'Kon het importbestand niet openen',
'importbadinterwiki'    => 'Verkeerde interwikilink',
'importnotext'          => 'Leeg of geen tekst',
'importsuccess'         => 'Import geslaagd.',
'importhistoryconflict' => 'Er zijn conflicten in de geschiedenis van de pagina (is misschien eerder geïmporteerd)',
'importnosources'       => 'Er zijn geen transwiki-importbronnen gedefinieerd en directe geschiedenis-uploads zijn uitgeschakeld.',
'importnofile'          => 'Er is geen importbestand geüpload.',
'importuploaderror'     => 'Upload van het importbestand in mislukt; mogelijk is het bestand groter is dan de limiet.',

# import log
'importlogpage'         => 'Importlogboek',
'importlogpagetext'     => 'Administratieve import van pagina\'s met geschiedenis van andere wiki\'s.',
'import-logentry-upload'=> '[[$1]] geïmporteerd via een bestandsupload',
'import-logentry-upload-detail'=> '$1 versie(s)',
'import-logentry-interwiki'=> 'transwiki voor $1 geslaagd',
'import-logentry-interwiki-detail'=> '$1 versie(s) van $2',

# Keyboard access keys for power users

# tooltip help for some actions, most are in Monobook.js
'tooltip-search'        => 'Doorzoek {{SITENAME}} [alt-f]',
'tooltip-minoredit'     => 'Markeer dit als een kleine wijziging [alt-i]',
'tooltip-save'          => 'Sla uw wijzigingen op [alt-s]',
'tooltip-preview'       => 'Maak een voorvertoning. Gebruik dit! [alt-p]',
'tooltip-diff'          => 'Toon de gemaakte wijzigingen. [alt-v]',
'tooltip-compareselectedversions'=> 'Toon de verschillen tussen de geselecteerde versies. [alt-v]',
'tooltip-watch'         => 'Voeg deze pagina toe aan uw volglijst [alt-w]',

# stylesheets
'Monobook.css'          => '/* Een CSS die hier wordt geplaatst heeft invloed op alle gebruikers van de skin Monobook */',

# Metadata
'nodublincore'          => 'Dublin Core RDF metadata is uitgeschakeld op deze server.',
'nocreativecommons'     => 'Creative Commons RDF metadata is uitgeschakeld op deze server.',
'notacceptable'         => 'De wikiserver kan de gegevens niet leveren in een vorm die uw client kan lezen.',

# Attribution
'anonymous'             => 'Anonieme gebruiker(s) van {{SITENAME}}',
'siteuser'              => '{{SITENAME}} gebruiker $1',
'lastmodifiedatby'        => 'Deze pagina is het laatst bewerkt op $2, $1 door $3.',
'and'                   => 'en',
'othercontribs'         => 'Gebaseerd op werk van $1.',
'others'                => 'anderen',
'siteusers'             => '{{SITENAME}} gebruiker(s) $1',
'creditspage'           => 'Auteurspagina',
'nocredits'             => 'Er is geen auteursinformatie beschikbaar voor deze pagina.',

# Spam protection
'spamprotectiontitle'   => 'Spamfilter',
'spamprotectiontext'    => 'De pagina die u wilde opslaan is geblokkeerd door het spamfilter. Meestal wordt dit door een externe link veroorzaakt.',
'spamprotectionmatch'   => 'De volgende tekst veroorzaakte het alarm van de spamfilter: $1',
'subcategorycount'      => 'Er {{PLURAL:$1|is één ondercategorie|zijn $1 ondercategorieën}} binnen deze categorie.',
'categoryarticlecount'  => 'Er {{PLURAL:$1|staat één onderwerp|staan $1 onderwerpen}} in deze categorie.',
'listingcontinuesabbrev'=> ' meer',
'spambot_username'      => 'MediaWiki opschoning spam',
'spam_reverting'        => 'Bezig met terugdraaien naar de laatste versie die geen verwijzing heeft naar $1',
'spam_blanking'         => 'Alle wijzigingen met een link naar $1 worden verwijderd',

# Info page
'infosubtitle'          => 'Informatie voor pagina',
'numedits'              => 'Aantal bewerkingen (pagina): $1',
'numtalkedits'          => 'Aantal bewerkingen (overlegpagina): $1',
'numwatchers'           => 'Aantal volgers: $1',
'numauthors'            => 'Aantal auteurs (pagina): $1',
'numtalkauthors'        => 'Aantal verschilende auteurs (overlegpagina): $1',

# Math options
'mw_math_png'           => 'Altijd als PNG weergeven',
'mw_math_simple'        => 'HTML voor eenvoudige formules, anders PNG',
'mw_math_html'          => 'HTML indien mogelijk, anders PNG',
'mw_math_source'        => 'Toon de TeX broncode (voor tekstbrowsers)',
'mw_math_modern'        => 'Aanbevolen methode voor recente browsers',
'mw_math_mathml'        => 'MathML als mogelijk (experimenteel)',

# Patrolling
'markaspatrolleddiff'   => 'Markeer als gecontroleerd',
'markaspatrolledtext'   => 'Markeer deze pagina als gecontroleerd',
'markedaspatrolled'     => 'Gemarkeerd als gecontroleerd',
'markedaspatrolledtext' => 'De gekozen versie is gemarkeerd als gecontroleerd.',
'rcpatroldisabled'      => 'De controlemogelijkheid op recente wijzigingen is uitgeschakeld.',
'rcpatroldisabledtext'  => 'De mogelijkheid om recente wijzigingen als gecontroleerd aan te merken is op dit ogenblik uitgeschakeld.',
'markedaspatrollederror'=> 'Kan niet als gecontroleerd worden aangemerkt',
'markedaspatrollederrortext'=> 'Selecteer een versie om als gecontroleerd aan te merken.',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js'           => '/* tooltips en sneltoetsen */
 var ta = new Object();
 ta[\'pt-userpage\'] = new Array(\'.\',\'Mijn gebruikerspagina\');
 ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Gebruikerspagina voor uw IP-adres\');
 ta[\'pt-mytalk\'] = new Array(\'n\',\'Mijn overlegpagina\');
 ta[\'pt-anontalk\'] = new Array(\'n\',\'Overlegpagina van de anonieme gebruiker van dit IP-adres\');
 ta[\'pt-preferences\'] = new Array(\'\',\'Mijn voorkeuren\');
 ta[\'pt-watchlist\'] = new Array(\'l\',\'Pagina\'s die op mijn volglijst staan\');
 ta[\'pt-mycontris\'] = new Array(\'y\',\'Mijn bijdragen\');
 ta[\'pt-login\'] = new Array(\'o\',\'U wordt van harte uitgenodigd om u aan te melden als gebruiker, maar dit is niet verplicht\');
 ta[\'pt-anonlogin\'] = new Array(\'o\',\'U wordt van harte uitgenodigd om u aan te melden als gebruiker, maar dit is niet verplicht\');
 ta[\'pt-logout\'] = new Array(\'o\',\'Afmelden\');
 ta[\'ca-talk\'] = new Array(\'t\',\'Toon de overlegtekst bij deze pagina\');
 ta[\'ca-edit\'] = new Array(\'e\',\'U kunt deze pagina bewerken. Gebruik a.u.b. de voorbeeldweergaveknop alvorens te bewaren\');
 ta[\'ca-addsection\'] = new Array(\'+\',\'Voeg uw opmerking toe aan de overlegpagina\');
 ta[\'ca-viewsource\'] = new Array(\'e\',\'Deze pagina is beveiligd tegen wijzigen. U kunt de pagina wel inzien\');
 ta[\'ca-history\'] = new Array(\'h\',\'Eerdere versies van deze pagina\');
 ta[\'ca-protect\'] = new Array(\'=\',\'Beveilig deze pagina tegen wijzigen\');
 ta[\'ca-delete\'] = new Array(\'d\',\'Verwijder deze pagina\');
 ta[\'ca-undelete\'] = new Array(\'d\',\'Plaats verwijderde versies van deze pagina terug\');
 ta[\'ca-move\'] = new Array(\'m\',\'Hernoem deze pagina\');
 ta[\'ca-watch\'] = new Array(\'w\',\'Voeg deze pagina toe aan mijn volglijst\');
 ta[\'ca-unwatch\'] = new Array(\'w\',\'Verwijder deze pagina van mijn volglijst\');
 ta[\'search\'] = new Array(\'f\',\'Doorzoek deze wiki\');
 ta[\'p-logo\'] = new Array(\'\',\'Hoofdpaginalogo\');
 ta[\'n-mainpage\'] = new Array(\'z\',\'Ga naar de Hoofdpagina\');
 ta[\'n-portal\'] = new Array(\'\',\'Informatie over het project: wie, wat, hoe en waarom\');
 ta[\'n-currentevents\'] = new Array(\'\',\'Achtergrondinformatie over actuele zaken\');
 ta[\'n-recentchanges\'] = new Array(\'r\',\'Toon recente wijzigingen\');
 ta[\'n-randompage\'] = new Array(\'x\',\'Toon een willekeurige pagina\');
 ta[\'n-help\'] = new Array(\'\',\'Hulpinformatie over deze wiki\');
 ta[\'n-sitesupport\'] = new Array(\'\',\'Ondersteun ons financieel\');
 ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Toon verwijzingen naar deze pagina\');
 ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Toon wijzigingen van pagina\'s waar deze pagina naar verwijst\');
 ta[\'feed-rss\'] = new Array(\'\',\'RSS-feed voor deze pagina\');
 ta[\'feed-atom\'] = new Array(\'\',\'Atom-feed voor deze pagina\');
 ta[\'t-contributions\'] = new Array(\'\',\'Bijdragen van deze gebruiker\');
 ta[\'t-emailuser\'] = new Array(\'\',\'Verzend een e-mail naar deze gebruiker\');
 ta[\'t-upload\'] = new Array(\'u\',\'Upload bestanden\');
 ta[\'t-specialpages\'] = new Array(\'q\',\'Toon alle speciale pagina\'s\');
 ta[\'ca-nstab-main\'] = new Array(\'c\',\'Toon de paginatekst\');
 ta[\'ca-nstab-user\'] = new Array(\'c\',\'Toon de gebruikerspagina\');
 ta[\'ca-nstab-media\'] = new Array(\'c\',\'Toon de mediatekst\');
 ta[\'ca-nstab-special\'] = new Array(\'\',\'Deze speciale pagina kunt u niet wijzigen\');
 ta[\'ca-nstab-project\'] = new Array(\'a\',\'Toon de projectpagina\');
 ta[\'ca-nstab-image\'] = new Array(\'c\',\'Toon de afbeeldingspagina\');
 ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Toon de systeemtekstpagina\');
 ta[\'ca-nstab-template\'] = new Array(\'c\',\'Toon de sjabloonpagina\');
 ta[\'ca-nstab-help\'] = new Array(\'c\',\'Toon de helppagina\');
 ta[\'ca-nstab-category\'] = new Array(\'c\',\'Toon de rubriekpagina\');',

# image deletion
'deletedrevision'       => 'Oude versie $1 verwijderd.',

# browsing diffs
'previousdiff'          => '← Vorige wijziging',
'nextdiff'              => 'Volgende wijziging →',

'imagemaxsize'          => 'Maximale grootte beelden op beschrijvingspagina:',
'thumbsize'             => 'Grootte thumbnail:',
'showbigimage'          => 'Download afbeelding in origineel formaat ($1x$2 pixels, $3 kB)',

'newimages'             => 'Nieuwe afbeeldingen',
'noimages'              => 'Niets te zien.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
# variants for Serbian language

# labels for User: and Title: on Special:Log pages
'specialloguserlabel'   => 'Gebruiker:',
'speciallogtitlelabel'  => 'Paginanaam:',

'passwordtooshort'      => 'Uw wachtwoord is te kort. Het moet uit minstens $1 tekens bestaan.',

# Media Warning
'mediawarning'          => '\'\'\'Waarschuwing\'\'\': dit bestand bevat mogelijk programmacode die uw systeem schade kan berokkenen.<hr />',
'fileinfo'              => '$1KB, MIME-type: <code>$2</code>',

# Metadata
'metadata-help'         => 'Dit bestand bevat aanvullende informatie, die door een fotocamera, scanner of fotobewerkingsprogramma toegevoegd kan zijn. Als het bestand is aangepast, dan komen details mogelijk niet overeen met de gewijzigde afbeelding.',
'metadata-expand'       => 'Toon uitgebreide gegevens',
'metadata-collapse'     => 'Verberg uitgebreide gegevens',

# Exif tags
'exif-imagewidth'       => 'Breedte',
'exif-imagelength'      => 'Hoogte',
'exif-bitspersample'    => 'Bits per component',
'exif-compression'      => 'Compressieschema',
'exif-photometricinterpretation'=> 'Pixelcompositie',
'exif-orientation'      => 'Oriëntatie',
'exif-samplesperpixel'  => 'Aantal componenten',
'exif-planarconfiguration'=> 'Gegevensstructuur',
'exif-ycbcrsubsampling' => 'Subsampleverhouding van Y tot C',
'exif-ycbcrpositioning' => 'Y- en C-positionering',
'exif-xresolution'      => 'Horizontale resolutie',
'exif-yresolution'      => 'Verticale resolutie',
'exif-resolutionunit'   => 'Eenheid X en Y resolutie',
'exif-stripoffsets'     => 'Locatie afbeeldingsgegevens',
'exif-rowsperstrip'     => 'Rijen per strip',
'exif-stripbytecounts'  => 'Bytes per gecomprimeerde strip',
'exif-jpeginterchangeformat'=> 'Afstand tot JPEG SOI',
'exif-jpeginterchangeformatlength'=> 'Bytes JPEG-gegevens',
'exif-transferfunction' => 'Transferfunctie',
'exif-whitepoint'       => 'Witpuntchromaticiteit',
'exif-primarychromaticities'=> 'Chromaticities of primaries',
'exif-ycbcrcoefficients'=> 'Transformatiematrixcoëfficiënten voor de kleurruimte',
'exif-referenceblackwhite'=> 'Paar zwart en wit referentiewaarden',
'exif-datetime'         => 'Tijdstip laatste bestandswijziging',
'exif-imagedescription' => 'Omschrijving afbeelding',
'exif-make'             => 'Merk camera',
'exif-model'            => 'Cameramodel',
'exif-software'         => 'Gebruikte software',
'exif-artist'           => 'Auteur',
'exif-copyright'        => 'Copyrighthouder',
'exif-exifversion'      => 'Exif-versie',
'exif-flashpixversion'  => 'Ondersteunde Flashpix-versie',
'exif-colorspace'       => 'Kleurruimte',
'exif-componentsconfiguration'=> 'Betekenis van elke component',
'exif-compressedbitsperpixel'=> 'Beeldcompressiemethode',
'exif-pixelydimension'  => 'Bruikbare afbeeldingsbreedte',
'exif-pixelxdimension'  => 'Bruikbare afbeeldingshoogte',
'exif-makernote'        => 'Opmerkingen maker',
'exif-usercomment'      => 'Opmerkingen',
'exif-relatedsoundfile' => 'Bijbehorend audiobestand',
'exif-datetimeoriginal' => 'Tijdstip gegevensaanmaak',
'exif-datetimedigitized'=> 'Tijdstip digitalisering',
'exif-subsectime'       => 'Datum tijd subseconden',
'exif-exposuretime'     => 'Belichtingstijd',
'exif-fnumber'          => 'F-getal',
'exif-fnumber-format'   => 'f/$1',
'exif-exposureprogram'  => 'Belichtingsprogramma',
'exif-spectralsensitivity'=> 'Spectrale gevoeligheid',
'exif-isospeedratings'  => 'ISO/ASA-waarde',
'exif-oecf'             => 'Opto-elektronische conversiefactor',
'exif-shutterspeedvalue'=> 'Sluitersnelheid',
'exif-aperturevalue'    => 'Diafragma',
'exif-brightnessvalue'  => 'Helderheid',
'exif-exposurebiasvalue'=> 'Belichtingscompensatie',
'exif-maxaperturevalue' => 'Maximale diafragma-opening',
'exif-subjectdistance'  => 'Objectafstand',
'exif-meteringmode'     => 'Methode lichtmeting',
'exif-lightsource'      => 'Lichtbron',
'exif-flash'            => 'Flitser',
'exif-focallength'      => 'Brandpuntsafstand',
'exif-subjectarea'      => 'Objectruimte',
'exif-flashenergy'      => 'Flitssterkte',
'exif-focalplanexresolution'=> 'Brandpuntsvlak-X-resolutie',
'exif-focalplaneyresolution'=> 'Brandpuntsvlak-Y-resolutie',
'exif-focalplaneresolutionunit'=> 'Eenheid CCD-resolutie',
'exif-subjectlocation'  => 'Objectlocatie',
'exif-exposureindex'    => 'Belichtingsindex',
'exif-sensingmethod'    => 'Opvangmethode',
'exif-filesource'       => 'Bestandsbron',
'exif-scenetype'        => 'Soort scene',
'exif-cfapattern'       => 'CFA-patroon',
'exif-customrendered'   => 'Aangepaste beeldverwerking',
'exif-exposuremode'     => 'Belichtingsinstelling',
'exif-whitebalance'     => 'Witbalans',
'exif-digitalzoomratio' => 'Digitale zoomfactor',
'exif-focallengthin35mmfilm'=> 'Brandpuntsafstand (35mm-equivalent)',
'exif-scenecapturetype' => 'Soort opname',
'exif-gaincontrol'      => 'Piekbeheersing',
'exif-saturation'       => 'Verzadiging',
'exif-sharpness'        => 'Scherpte',
'exif-devicesettingdescription'=> 'Omschrijving apparaatinstellingen',
'exif-subjectdistancerange'=> 'Bereik objectafstand',
'exif-imageuniqueid'    => 'Uniek ID afbeelding',
'exif-gpsversionid'     => 'GPS versienummer',
'exif-gpslatituderef'   => 'Noorder- of zuiderbreedte',
'exif-gpslatitude'      => 'Breedtegraad',
'exif-gpslongituderef'  => 'Ooster- of westerlengte',
'exif-gpslongitude'     => 'Lengtegraad',
'exif-gpsaltituderef'   => 'Hoogtereferentie',
'exif-gpsaltitude'      => 'Hoogte',
'exif-gpstimestamp'     => 'GPS-tijd (atoomklok)',
'exif-gpssatellites'    => 'Gebruikte satellieten voor meting',
'exif-gpsstatus'        => 'Ontvangerstatus',
'exif-gpsmeasuremode'   => 'Meetmodus',
'exif-gpsdop'           => 'Meetprecisie',
'exif-gpsspeedref'      => 'Snelheid eenheid',
'exif-gpsspeed'         => 'Snelheid van GPS-ontvanger',
'exif-gpstrackref'      => 'Referentie voor bewegingsrichting',
'exif-gpstrack'         => 'Bewegingsrichting',
'exif-gpsimgdirectionref'=> 'Referentie voor afbeeldingsrichting',
'exif-gpsimgdirection'  => 'Afbeeldingsrichting',
'exif-gpsmapdatum'      => 'Gebruikte geodetische onderzoeksgegevens',
'exif-gpsdestlatituderef'=> 'Referentie voor breedtegraad bestemming',
'exif-gpsdestlatitude'  => 'Breedtegraad bestemming',
'exif-gpsdestlongituderef'=> 'Referentie voor lengtegraad bestemming',
'exif-gpsdestlongitude' => 'Lengtegraad bestemming',
'exif-gpsdestbearingref'=> 'Referentie voor richting naar bestemming',
'exif-gpsdestbearing'   => 'Richting naar bestemming',
'exif-gpsdestdistanceref'=> 'Referentie voor afstand tot bestemming',
'exif-gpsdestdistance'  => 'Afstand tot bestemming',
'exif-gpsprocessingmethod'=> 'GPS-verwerkingsmethode',
'exif-gpsareainformation'=> 'Naam GPS-gebied',
'exif-gpsdatestamp'     => 'GPS-datum',
'exif-gpsdifferential'  => 'Differentiele GPS-correctie',

# Make & model, can be wikified in order to link to the camera and model name

# Exif attributes

'exif-compression-1'    => 'Ongecomprimeerd',

'exif-orientation-1'    => 'Normaal',
'exif-orientation-2'    => 'Horizontaal gespiegeld',
'exif-orientation-3'    => '180° gedraaid',
'exif-orientation-4'    => 'Verticaal gespiegeld',
'exif-orientation-5'    => 'Gespiegeld om as linksboven-rechtsonder',
'exif-orientation-6'    => '90° rechtsom gedraaid',
'exif-orientation-7'    => 'Gespiegeld om as linksonder-rechtsboven',
'exif-orientation-8'    => '90° linksom gedraaid',

'exif-colorspace-ffff.h'=> 'Niet gecalibreerd',
'exif-componentsconfiguration-0'=> 'bestaat niet',

'exif-exposureprogram-0'=> 'Niet gedefiniëerd',
'exif-exposureprogram-1'=> 'Handmatig',
'exif-exposureprogram-2'=> 'Normaal programma',
'exif-exposureprogram-3'=> 'Diafragmaprioriteit',
'exif-exposureprogram-4'=> 'Sluiterprioriteit',
'exif-exposureprogram-5'=> 'Creatief (voorkeur voor hoge scherpte/diepte)',
'exif-exposureprogram-6'=> 'Actie (voorkeur voor hoge sluitersnelheid)',
'exif-exposureprogram-7'=> 'Portret (detailopname met onscherpe achtergrond)',
'exif-exposureprogram-8'=> 'Landschap (scherpe achtergrond)',

'exif-subjectdistance-value'=> '$1 meter',

'exif-meteringmode-0'   => 'Onbekend',
'exif-meteringmode-1'   => 'Gemiddeld',
'exif-meteringmode-2'   => 'Centrumgewogen',
'exif-meteringmode-4'   => 'Multi-spot',
'exif-meteringmode-5'   => 'Multi-segment (patroon)',
'exif-meteringmode-6'   => 'Deelmeting',
'exif-meteringmode-255' => 'Anders',

'exif-lightsource-0'    => 'Onbekend',
'exif-lightsource-1'    => 'Daglicht',
'exif-lightsource-2'    => 'TL-licht',
'exif-lightsource-3'    => 'Tungsten (lamplicht)',
'exif-lightsource-4'    => 'Flits',
'exif-lightsource-9'    => 'Mooi weer',
'exif-lightsource-10'   => 'Bewolkt',
'exif-lightsource-11'   => 'Schaduw',
'exif-lightsource-12'   => 'Daglicht fluorescerend (D 5700 – 7100K)',
'exif-lightsource-13'   => 'Dagwit fluorescerend (N 4600 - 5400K)',
'exif-lightsource-14'   => 'Koel wit fluorescerend (W 3900 - 4500K)',
'exif-lightsource-15'   => 'Wit fluorescerend (WW 3200 - 3700K)',
'exif-lightsource-17'   => 'Standaard licht A',
'exif-lightsource-18'   => 'Standaard licht B',
'exif-lightsource-19'   => 'Standaard licht C',
'exif-lightsource-255'  => 'Andere lichtbron',

'exif-focalplaneresolutionunit-2'=> 'inch',

'exif-sensingmethod-1'  => 'Niet gedefiniëerd',
'exif-sensingmethod-2'  => 'Eén-chip-kleursensor',
'exif-sensingmethod-3'  => 'Twee-chip-kleursensor',
'exif-sensingmethod-4'  => 'Drie-chip-kleursensor',

'exif-scenetype-1'      => 'Een direct gefotografeerde afbeelding',

'exif-customrendered-0' => 'Normale verwerking',
'exif-customrendered-1' => 'Aangepaste verwerking',

'exif-exposuremode-0'   => 'Automatische belichting',
'exif-exposuremode-1'   => 'Handmatige belichting',
'exif-exposuremode-2'   => 'Auto-Bracket',

'exif-whitebalance-0'   => 'Automatische witbalans',
'exif-whitebalance-1'   => 'Handmatige witbalans',

'exif-scenecapturetype-0'=> 'Standaard',
'exif-scenecapturetype-1'=> 'Landschap',
'exif-scenecapturetype-2'=> 'Portret',
'exif-scenecapturetype-3'=> 'Nachtscène',

'exif-gaincontrol-0'    => 'Geen',
'exif-gaincontrol-1'    => 'Lage pieken omhoog',
'exif-gaincontrol-2'    => 'Hoge pieken omhoog',
'exif-gaincontrol-3'    => 'Lage pieken omlaag',
'exif-gaincontrol-4'    => 'Hoge pieken omlaag',

'exif-contrast-0'       => 'Normaal',
'exif-contrast-1'       => 'Zacht',

'exif-saturation-0'     => 'Normaal',
'exif-saturation-1'     => 'Laag',
'exif-saturation-2'     => 'Hoog',

'exif-sharpness-0'      => 'Normaal',
'exif-sharpness-1'      => 'Zacht',

'exif-subjectdistancerange-0'=> 'Onbekend',
'exif-subjectdistancerange-2'=> 'Dichtbij',
'exif-subjectdistancerange-3'=> 'Ver weg',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n'    => 'Noorderbreedte',
'exif-gpslatitude-s'    => 'Zuiderbreedte',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e'   => 'Oosterlengte',
'exif-gpslongitude-w'   => 'Westerlengte',

'exif-gpsstatus-a'      => 'Bezig met meten',
'exif-gpsstatus-v'      => 'Meetinteroperabiliteit',

'exif-gpsmeasuremode-2' => '2-dimensionale meting',
'exif-gpsmeasuremode-3' => '3-dimensionale meting',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k'       => 'Kilometer per uur',
'exif-gpsspeed-m'       => 'Mijl per uur',
'exif-gpsspeed-n'       => 'Knopen',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t'   => 'Eigenlijke richting',
'exif-gpsdirection-m'   => 'Magnetische richting',

# external editor support
'edit-externally'       => 'Bewerk dit bestand in een extern programma',
'edit-externally-help'  => 'In de [http://meta.wikimedia.org/wiki/Help:External_editors handleiding voor instellingen] staat meer informatie.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall'      => 'alles',
'imagelistall'          => 'alle',
'watchlistall1'         => 'allemaal',
'watchlistall2'         => 'alles',
'namespacesall'         => 'alle',

# E-mail address confirmation
'confirmemail'          => 'Bevestig e-mailadres',
'confirmemail_noemail'  => 'U heeft geen geldig e-mailadres ingegeven in uw [[Special:Preferences|gebruikersvoorkeuren]].',
'confirmemail_text'     => 'Deze wiki vereist de bevestiging van uw e-mailadres voordat u de e-mailmogelijkheden kunt gebruiken. Klik op de onderstaande knop om een bevestigingsbericht te ontvangen. Dit bericht bevat een link met een code. Open die link om uw e-mailadres te bevestigen.',
'confirmemail_send'     => 'Verzend een bevestigingscode',
'confirmemail_sent'     => 'Bevestigingscode verzonden.',
'confirmemail_sendfailed'=> 'Het was niet mogelijk een bevestigingscode te verzenden. Controleer het adres op ongeldige tekens.',
'confirmemail_invalid'  => 'Ongeldige bevestigingscode. Mogelijk is de code verlopen.',
'confirmemail_needlogin'=> 'U dient $1 om uw e-mailadres te bevestigen.',
'confirmemail_success'  => 'Uw e-mailadres is bevestigd. U kunt zich nu aanmelden en {{SITENAME}} gebruiken.',
'confirmemail_loggedin' => 'Uw e-mailadres is nu bevestigd.',
'confirmemail_error'    => 'Er is iets verkeerd gegaan tijdens het opslaan van uw bevestiging.',
'confirmemail_subject'  => 'Bevestiging e-mailadres voor {{SITENAME}}',
'confirmemail_body'     => 'Iemand, waarschijnlijk u, met het IP-adres $1, heeft zich met dit e-mailadres geregistreerd als gebruiker "$2" op {{SITENAME}}.

Open de volgende link om te bevestigen dat u deze gebruiker bent en om de e-mailmogelijkheden op {{SITENAME}} te activeren:

$3

Als u zichzelf *niet* heeft aangemeld, open deze link dan niet. De bevestigingscode verloopt  op $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'              => 'Zoek op exacte overeenkomst',
'searchfulltext'        => 'Volledige tekst doorzoeken',
'createarticle'         => 'Maak nieuwe pagina',

# Scary transclusion
'scarytranscludedisabled'=> '[Interwikitransclusie is uitgeschakeld]',
'scarytranscludefailed' => '[Sjabloon $1 kon niet opgehaald worden; sorry]',
'scarytranscludetoolong'=> '[URL is te lang; sorry]',

# Trackbacks
'trackbackbox'          => '<div id=\'mw_trackbacks\'>
Trackbacks voor deze pagina:<br />
$1
</div>',
'trackbackremove'       => ' ([$1 Verwijderen])',
'trackbackdeleteok'     => 'De trackback is verwijderd.',

# delete conflict
'deletedwhileediting'   => 'Let op: deze pagina is verwijderd terwijl u bezig was met uw bewerking!',
'confirmrecreate'       => 'Gebruiker [[User:$1|$1]] ([[User talk:$1|overleg]]) heeft deze pagina verwijderd nadat u begonnen bent met uw wijziging met opgaaf van de volgende reden:
: \'\'$2\'\'
Bevestig alstublieft dat u de pagina opnieuw wilt aanmaken.',
'recreate'              => 'Opnieuw aanmaken',
'tooltip-recreate'      => 'Maak deze pagina opnieuw aan ondanks eerdere verwijdering',


# HTML dump
'redirectingto'         => 'Aan het doorverwijzen naar [[$1]]...',

# action=purge
'confirm_purge'         => 'Wis de cache van deze pagina?

$1',
'youhavenewmessagesmulti'=> 'U heeft nieuwe berichten op $1',
'searchcontaining'      => "Zoek naar pagina's die ''$1'' bevatten.",
'searchnamed'           => "Zoek naar pagina's met de naam ''$1''.",
'articletitles'         => "Pagina's die met ''$1'' beginnen",
'hideresults'           => 'Verberg resultaten',

# DISPLAYTITLE
'displaytitle'          => '(Link naar deze pagina als [[$1]])',
# Separator for categories in page lists
# Please don't localise this
'loginlanguagelabel'    => 'Taal: $1',

# Multipage image navigation
'imgmultipageprev'      => '&larr; vorige pagina',
'imgmultipagenext'      => 'volgende pagina &rarr;',
'imgmultigo'            => 'OK',
'imgmultigotopre'       => 'Ga naar pagina',

# Table pager
'ascending_abbrev'      => 'opl.',
'descending_abbrev'     => 'afl.',
'table_pager_next'      => 'Volgende pagina',
'table_pager_prev'      => 'Vorige pagina',
'table_pager_first'     => 'Eerste pagina',
'table_pager_last'      => 'Laatste pagina',
'table_pager_limit'     => 'Toon $1 resultaten per pagina',
'table_pager_limit_submit'=> 'OK',
'table_pager_empty'     => 'Geen resultaten',
);
?>
