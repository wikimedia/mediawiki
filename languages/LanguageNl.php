<?php
#
# Nederlands localisation for MediaWiki
#
# This file is encoded in UTF-8, no byte order mark.
# For compatibility with Latin-1 installations, please
# don't add literal characters above U+00ff.
#

require_once( "LanguageUtf8.php" );

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.

/* private */ $wgNamespaceNamesNl = array(
	NS_MEDIA			=> "Media",
	NS_SPECIAL			=> "Speciaal",
	NS_MAIN				=> "",
	NS_TALK				=> "Overleg",
	NS_USER				=> "Gebruiker",
	NS_USER_TALK		=> "Overleg_gebruiker",
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> "Overleg_$wgMetaNamespace",
	NS_IMAGE			=> "Afbeelding",
	NS_IMAGE_TALK		=> "Overleg_afbeelding",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK	=> "Overleg_MediaWiki",
	NS_TEMPLATE			=> "Sjabloon",
	NS_TEMPLATE_TALK	=> "Overleg_sjabloon",
	NS_HELP				=> "Help",
	NS_HELP_TALK		=> "Overleg_help",
	NS_CATEGORY			=> "Categorie",
	NS_CATEGORY_TALK	=> "Overleg_categorie"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsNl = array(
 "Uitgeschakeld", "Links vast", "Rechts vast", "Links zwevend"
);

/* private */ $wgSkinNamesNl = array(
	'standard' => "Standaard",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Keuls blauw",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);



# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesNl = array(
 "Userlogin"  => "",
 "Userlogout" => "",
 "Preferences" => "Mijn gebruikersvoorkeuren instellen",
 "Watchlist"  => "Mijn volglijst",
 "Recentchanges" => "Recent bijgewerkte pagina's",
 "Upload"  => "Afbeeldingen uploaden",
 "Imagelist"  => "Lijst ge-uploade afbeeldingen",
 "Listusers"  => "Geregistreerde gebruikers",
 "Statistics" => "Statistieken",
 "Randompage" => "Ga naar een willekeurig artikel",

 "Lonelypages" => "Niet-gelinkte artikels",
 "Unusedimages" => "Niet-gelinkte afbeeldingen",
 "Popularpages" => "Populaire artikels",
 "Wantedpages" => "Meest gewenste artikels",
 "Shortpages" => "Korte artikels",
 "Longpages"  => "Lange artikels",
 "Newpages"  => "Nieuwe artikels",
 "Ancientpages" => "Oudste artikels",
#"Intl" => "Taallinks",
 "Allpages"  => "Alle paginatitels",

 "Ipblocklist" => "Geblokkeerde IP-adressen",
 "Maintenance" => "Onderhoudspagina",
 "Specialpages"  => "",
 "Contributions" => "",
 "Emailuser"  => "",
 "Whatlinkshere" => "",
 "Recentchangeslinked" => "",
 "Movepage"  => "",
 "Booksources" => "",
# "Categories" => "Rubrieken",
 "Export" => "XML export",
 "Version" => "Version",
);

/* private */ $wgSysopSpecialPagesNl = array(
 "Blockip"  => "Blokkeer een IP-adres of gebruiker",
 "Asksql"  => "Raadpleeg de database",
 "Undelete" => "Verwijderde pagina's herstellen"
);

/* private */ $wgDeveloperSpecialPagesNl = array(
 "Lockdb"  => "Maak de database alleen-lezen",
 "Unlockdb"  => "Maak de database overschrijfbaar",
);

/* private */ $wgAllMessagesNl = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User Toggles

"tog-hover"  => "Wikilinks in zwevend tekstvak tonen",
"tog-underline" => "Links onderstrepen",
"tog-highlightbroken" => "Links naar lege pagina's laten oplichten",
"tog-justify" => "Paragrafen uitvullen",
"tog-hideminor" => "Kleine wijzigingen verbergen in recente wijzigingen",
"tog-usenewrc" => "Gebruik de uitgebreide Recente Wijzigingen-pagina (niet op alle browsers mogelijk)",
"tog-numberheadings" => "Koppen automatisch nummeren",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "Dubbelklikken levert bewerkingspagina (vereist JavaScript)",
"tog-editsection" => "Maak het bewerken van deelpagina's mogelijk",
"tog-editsectionondblclick" => "Edit deelpagina's met rechtermuisklik (vereist JavaScript)",
"tog-showtoc" => "Geef een inhoudsopgave (van pagina's met minstens 3 tussenkoppen)",
"tog-rememberpassword" => "Wachtwoord onthouden",
"tog-editwidth" => "Bewerkingsveld over volle breedte",
"tog-watchdefault" => "Artikelen die u wijzigt automatisch volgen",
"tog-minordefault" => "Maak 'kleine' veranderingen mijn standaard",
"tog-previewontop" => "Toon controlepagina boven bewerkingsveld",
"tog-nocache" => "Gebruik geen caching",

# Dates
'sunday' => 'zondag',
'monday' => 'maandag',
'tuesday' => 'dinsdag',
'wednesday' => 'woensdag',
'thursday' => 'donderdag',
'friday' => 'vrijdag',
'saturday' => 'zaterdag',
'january' => 'januari',
'february' => 'februari',
'march' => 'maart',
'april' => 'april',
'may_long' => 'mei',
'june' => 'juni',
'july' => 'juli',
'august' => 'augustus',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'december',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mrt',
'apr' => 'apr',
'may' => 'mei',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',


# Bits of text used by many pages:
# Diverse stukjes tekst
"categories" => "Rubrieken",
"category" => "rubriek",
"category_header" => "Artikelen in rubriek \"$1\"",
"subcategories" => "Onderrubrieken",
"linktrail" => "/^((?:[a-z]|ä|ö|ü|ï|ë|é|è|à)+)(.*)\$/sD",
"mainpage"  => "Hoofdpagina",
"mainpagetext" => "Installatie van de Wiki software geslaagd.",
"about"   => "Info",
"aboutsite"      => "Over {{SITENAME}}",
"aboutpage"  => "{{ns:4}}:info",
"help"   => "Help",
"helppage"  => "{{ns:4}}:Help",
"wikititlesuffix" => "{{SITENAME}} NL",
"bugreports" => "Foutenrapportage",
"sitesupport" => "Financieel bijdragen",
"bugreportspage" => "{{ns:4}}:Foutenrapportage",
"faq"   => "FAQ",
"faqpage"  => "{{ns:4}}:Veel gestelde vragen",
"edithelp"  => "Hulp bij bewerken",
"edithelppage" => "{{ns:4}}:Instructies",
"cancel"  => "Annuleren",
"qbfind"  => "Zoeken",
"qbbrowse"  => "Bladeren",
"qbedit"  => "Bewerken",
"qbpageoptions" => "Pagina-opties",
"qbpageinfo" => "Pagina-informatie",
"qbmyoptions" => "Mijn opties",
"mypage"  => "Mijn gebruikerspagina",
"mytalk"  => "Mijn overleg",
"currentevents" => "In het nieuws",
"errorpagetitle" => "Fout",
"returnto"  => "Terugkeren naar $1.",
"tagline"       => " ",
"whatlinkshere" => "Pagina's die hierheen verwijzen",
"help"   => "Help",
"search"  => "Zoeken",
"go" => "Ga naar pagina",
"history"  => "Voorgeschiedenis",
"printableversion" => "Printer-vriendelijke versie",
"editthispage" => "Pagina bewerken",
"deletethispage" => "Verwijderen",
"protectthispage" => "Beveiligen",
"unprotectthispage" => "Beveiliging opheffen",
"newpage" => "Nieuwe pagina",
"talkpage"  => "Overlegpagina",
"postcomment" => "Schrijf commentaar",
"subjectpage" => "Artikel",
"articlepage"   => "Artikel",
"userpage" => "Gebruikerspagina",
"wikipediapage" => "Artikel",
"imagepage" => "Beschrijvingspagina",
"viewtalkpage" => "Bekijk de overlegpagina",
"otherlanguages" => "Andere talen",
"redirectedfrom" => "(Doorverwezen vanaf $1)",
"lastmodified" => "De laatste wijziging op deze pagina vond plaats op $1.",
"viewcount"  => "Deze pagina werd $1 maal bekeken. ",
"gnunote" => "Alle tekst op deze pagina valt onder de  <a class=internal href='/wiki/Gnu_Vrije_Documentatie_Licentie'>GNU FDL</a>.",
"printsubtitle" => "(Uit http://nl.wikipedia.org)",
"protectedpage" => "Beveiligde pagina",
"administrators" => "{{ns:4}}:Systeembeheerders",
"sysoptitle" => "Toegang alleen voor systeembeheerders",
"sysoptext"  => "De gevraagde handeling kan enkel uitgevoerd worden door systeembeheerders. Zie $1.",
"developertitle" => "Toegang alleen voor systeemontwikkelaars",
"developertext" => "De gevraagde handeling kan enkel uitgevoerd worden door systeemontwikkelaars. Zie $1.",
"nbytes"  => "$1 bytes",
"go"   => "OK",
"ok"   => "OK",
"sitetitle"  => "<span style=\"text-transform: none\">{{SITENAME}} NL</span>", # Okay, HERE's an ugly hack. There should be a cleaner way to do this.
"sitesubtitle" => "De vrije encyclopedie",
"retrievedfrom" => "Afkomstig van {{SITENAME}} NL, de Vrije Encyclopedie. \"$1\"",
"newmessages" => "U heeft $1.",
"newmessageslink" => "een nieuw bericht",
"editsection" => "bewerken",
"toc" => "Inhoud",
"showtoc" => "Tonen",
"hidetoc" => "Niet tonen",
"thisisdeleted" => "$1 bekijken of terugbrengen?",
"restorelink" => "$1 verwijderde versies",

# Main script and global functions
# Algemene functies
"nosuchaction" => "Gevraagde handeling bestaat niet",
"nosuchactiontext" => "De door de URL gespecifieerde handeling wordt niet herkend door de {{SITENAME}} software",
"nosuchspecialpage" => "De gevraagde speciale pagina is onvindbaar",
"nospecialpagetext" => "U heeft een speciale pagina aangevraagd die niet wordt herkend door de {{SITENAME}} software",

# General errors
# Algemene foutmeldingen
"error"   => "Fout",
"databaseerror" => "Database fout",
"dberrortext" => "Bij het zoeken is een syntaxfout in de database opgetreden.
De laatste zoekpoging in de database was:
<blockquote><tt>$1</tt></blockquote>
vanuit de functie \"<tt>$2</tt>\".
MySQL gaf the foutmelding \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Bij het opvragen van de database is een syntaxfout opgetreden. De laatste opdracht was:
\"$1\"
Vanuit de functie \"$2\"
MySQL gaf de volgende foutmelding: \"$3: $4\".\n",
"noconnect"  => "De database is momenteel niet bereikbaar.",
"nodb"   => "Selectie van database $1 niet mogelijk",
"cachederror" => "Hieronder wordt een versie uit de cache getoond. Dit is mogelijk niet de meest recente versie.",
"readonly"  => "Database geblokeerd",
"enterlockreason" => "Geef een reden voor de blokkering en hoelang het waarschijnlijk gaat duren. De ingegeven reden zal aan de gebruikers getoond worden.",
"readonlytext" => "De database van {{SITENAME}} is momenteel gesloten voor nieuwe bewerkingen en wijzigingen, waarschijnlijk voor bestandsonderhoud.
De verantwoordelijke systeembeheerder gaf hiervoor volgende reden op:
<p>$1",
"missingarticle" => "De database heeft een paginatekst (\"$1\") die het zou moeten vinden niet gevonden. Dit kan betekenen dat u een oude versie hebt proberen op te halen van een pagina die inmiddels verdwenen is. Indien dit niet het geval is, dan is er waarschijnlijk een fout in de software. Meld dit a.u.b. aan een beheerder, met vermelding van de URL.",
"internalerror" => "Interne fout",
"filecopyerror" => "Bestand \"$1\" naar \"$2\" kopiëren niet mogelijk.",
"filerenameerror" => "Wijziging titel bestand \"$1\" in \"$2\" niet mogelijk.",
"filedeleteerror" => "Kon bestand \"$1\" niet verwijderen.",
"filenotfound" => "Kon bestand \"$1\" niet vinden.",
"unexpected" => "Onverwachte waarde: \"$1\"=\"$2\".",
"formerror"  => "Fout: kon formulier niet verzenden", 
"badarticleerror" => "Deze handeling kan op deze pagina niet worden uitgevoerd.",
"cannotdelete" => "Kon de pagina of afbeelding niet verwijderen.",
"badtitle"              => "Ongeldige paginatitel", 
"badtitletext"  => "De opgevraagde pagina is niet beschikbaar of leeg.",
"perfdisabled" => "Om overbelasting van het systeem te voorkomen, is deze optie momenteel niet bruikbaar.",
"perfdisabledsub" => "We kunnen u wel onderstaande kopie van $1 tonen; deze is echter mogelijk niet up-to-date.",

# Login and logout pages
# Aanmelden en afmelden
"logouttitle" => "Afmelden gebruiker",
"logouttext" => "U bent nu afgemeld.
U kunt {{SITENAME}} anoniem blijven gebruiken, of u opnieuw aanmelden onder dezelfde of onder een andere naam.\n",

"welcomecreation" => "<h2>Welkom, $1!</h2><p>Uw gebruikersprofiel is aangemaakt. U kunt nu uw persoonlijke voorkeuren instellen.",

"loginpagetitle" => "Gebruikersnaam",
"yourname"  => "Uw gebruikersnaam",
"yourpassword" => "Uw wachtwoord",
"yourpasswordagain" => "Wachtwoord opnieuw ingeven",
"newusersonly" => " (alleen nieuwe gebruikers)",
"remembermypassword" => "Mijn wachtwoord onthouden voor latere sessies.",
"loginproblem" => "<b>Er is een probleem met het aanmelden.</b><br>Probeer het opnieuw a.u.b.",
"alreadyloggedin" => "<font color=red><b>Gebruiker $1, u bent al aangemeld.</b></font><br>\n",

"login"   => "Aanmelden & Inschrijven",
"userlogin"  => "Aanmelden",
"logout"  => "Afmelden",
"userlogout" => "Afmelden",
"notloggedin" => "Niet aangemeld",
"createaccount" => "Nieuw gebruikersprofiel aanmaken",
"createaccountmail" => "per email",
"badretype"  => "De ingevoerde wachtwoorden verschillen van elkaar.",
"userexists" => "De gebruikersnaam die u heeft ingevoerd is al in gebruik. Gelieve een andere naam te kiezen.",
"youremail"  => "Uw e-mailadres",
"yournick"  => "Uw bijnaam (voor handtekeningen)",
"emailforlost" => "Het opgeven van een e-mailadres is niet verplicht.<br>Enkel als er een email-adres beschikbaar is, kunt u een nieuw wachtwoord aanvragen indien u het oude vergeten bent.<br>Een emailadres geeft andere gebruikers de mogelijkheid u een email te sturen via een formulier. U kunt die functie eventueel uitzetten via de voorkeuren.",
"loginerror" => "Inlogfout",
"noname"  => "U dient een gebruikersnaam op te geven.",
"loginsuccesstitle" => "Aanmelden gelukt.",
"loginsuccess" => "U bent nu aangemeld bij {{SITENAME}} NL als \"$1\".",
"nosuchuser" => "Er bestaat geen gebruiker met de naam \"$1\". Controleer uw spelling, of gebruik onderstaand formulier om een nieuw gebruikersprofiel aan te maken.",
"wrongpassword" => "Het ingegeven wachtwoord is niet juist. Probeer het opnieuw.",
"mailmypassword" => "Stuur mij een nieuw wachtwoord op",
"passwordremindertitle" => "Herinnering wachtwoord {{SITENAME}} NL",
"passwordremindertext" => "Iemand (waarschijnlijk uzelf) vanaf IP-adres $1 heeft verzocht u een nieuw wachtwoord voor {{SITENAME}} NL toe te zenden. Het nieuwe wachtwoord voor gebruiker \"$2\" is \"$3\". Advies: nu aanmelden en uw wachtwoord wijzigigen.",
"noemail"  => "Er is geen e-mailadres geregistreerd voor \"$1\".",
"passwordsent" => "Er is een nieuw wachtwoord verzonden naar het e-mailadres wat geregistreerd staat voor \"$1\".
Gelieve na ontvangst opnieuw aan te melden.",

# Edit pages
# Pagina bewerken
"summary"  => "Samenvatting",
"subject" => "Onderwerp",
"minoredit"  => "Dit is een kleine wijziging",
"watchthis" => "Volg deze pagina",
"savearticle" => "Pagina opslaan",
"preview"  => "Nakijken",
"showpreview" => "Toon bewerking ter controle",
"blockedtitle" => "Gebruiker is geblokkeerd",
"blockedtext" => "Uw gebruikersnaam of IP-adres is door $1 geblokkeerd. De opgegeven reden:<br>$2<p>. Elke computer die verbonden is met het internet krijgt een [[ip-adres]] toegewezen van zijn [[internetprovider]]. In veel gevallen krijgt een gebruiker regelmatig een ander ip-adres toegewezen. Het het door u gebruikte ip-adres is recent gebruikt door u of iemand anders voor bewerkingen die in overtreding zijn van de [[{{ns:4}}:Toch een paar regels|regels]] van {{SITENAME}}.<p>U kunt voor overleg contact opnemen met de [[{{ns:4}}:Systeembeheerders|systeembeheerders]] via [http://wikinl.sol3.info/wikinl_info.html#email wikinl-l-owner@wikipedia.org een formulier].",
"whitelistedittitle" => "Aanmelden verplicht",
"whitelistedittext" => "Om dit artikel te kunnen wijzigen, moet u [[Speciaal:Userlogin|ingelogd]] zijn.",
"whitelistreadtitle" => "Aanmelden verplicht",
"whitelistreadtext" => "Om dit artikel te kunnen lezen, moet u [[Speciaal:Userlogin|ingelogd]] zijn.",
"whitelistacctitle" => "Creatie account niet toegestaan",
"whitelistacctext" => "Nieuwe accounts kunnen slechts worden aangemaakt door bepaalde geregistreerde gebruikers",
"accmailtitle" => "Wachtwoord verzonden.",
"accmailtext" => "Het wachtwoord voor '$1' is verzonden naar $2.",
"newarticle" => "(Nieuw)",
"newarticletext" => "Er bestaat nog geen artikel over dit onderwerp.<br>Als u wilt, kunt u hieronder een nieuw artikel schrijven.<br>Was dit niet de bedoeling, gebruik dan de 'Terug' knop van uw browser.<p>WAARSCHUWING: Let er goed op dat uw tekst vrij van auteursrechten is, bijvoorbeeld omdat u het zelf geschreven heeft. Neem geen teksten over uit boeken, tijdschriften of andere websites tenzij u zeker weet dat deze vrij van auteursrechten zijn.",
"anontalkpagetext" => "<hr>Deze overlegpagina hoort bij een anonieme gebruiker die hetzij geen loginnaam heeft, hetzij deze niet gebruikt. We gebruiken daarom het IP-adres ter identificatie. Het kan echter zijn dat meerdere personen hetzelfde IP-adres gebruiken. Het kan daarom zijn dat u hier berichten ontvangt die niet voor u bedoeld zijn. Mocht u dat willen voorkomen, dan kunt u [[Speciaal:Userlogin|een gebruikersnaam aanvragen of u aanmelden]].",
"noarticletext" => "(Deze pagina bevat momenteel geen tekst)",
"updated"  => "(Bijgewerkt)",
"note"   => "<strong>Opmerking:</strong> ",
"previewnote" => "Let op: dit is een controlepagina; uw tekst is nog niet opgeslagen!",
"previewconflict" => "Deze versie toont hoe de tekst in het bovenste veld eruit gaat zien wanneer u zou opslaan.",
"editing"  => "Bewerkingspagina: $1",
"editingsection"  => "Bewerkingspagina: $1 (deelpagina)",
"editingcomment"  => "Bewerkingspagina: $1 (nieuwe opmerking)",
"editconflict" => "Bewerkingsconflict: $1",
"explainconflict" => "Iemand anders heeft deze pagina gewijzigd nadat u aan deze bewerking bent begonnen. Het bovenste tekstveld toont de huidige versie van de pagina. U zal uw eigen wijzigingen moeten integreren in die tekst. Alleen de tekst in het bovenste veld wordt bewaard wanneer u kiest voor \"Pagina opslaan\".\n<p>",
"yourtext"  => "Uw tekst",
"storedversion" => "Opgeslagen versie",
"editingold" => "<strong>WAARSCHUWING: U bent bezig een oude versie van deze pagina te bewerken. Wanneer u uw bewerking opslaat, gaan alle wijzigingen die na deze versie gedaan zijn verloren.\n.</strong>\n",
"yourdiff"  => "Wijzigingen",
"copyrightwarning" => "Opgelet: Alle bijdragen aan {{SITENAME}} worden geacht te zijn vrijgegeven onder de GNU Free Documentation License. Als u niet wil dat uw tekst door anderen naar believen bewerkt en verspreid kan worden, kies dan niet voor 'Pagina Opslaan'.<br> Hierbij belooft u ons tevens dat u deze tekst zelf hebt geschreven, of overgenomen uit een vrije, openbare bron.<br> <strong>GEBRUIK GEEN MATERIAAL DAT BESCHERMD WORDT DOOR AUTEURSRECHT, TENZIJ JE DAARTOE TOESTEMMING HEBT!</strong>",
"longpagewarning" => "Waarschuwing! Deze pagina is $1 kilobyte lang. Pagina's langer dan 32 kb zorgen voor problemen op sommige browsers. Het is daarom waarschijnlijk een goed idee deze pagina in meerdere pagina's te splitsen.",
"readonlywarning" => "Waarschuwing! De database is op dit moment in onderhoud; het is daarom niet mogelijk op dit moment pagina's te wijzigen. Wij adviseren u de tekst op uw eigen computer op te slaan en later opnieuw te proberen deze pagina te bewerken.",
"protectedpagewarning" => "Waarschuwing! U staat op het punt een beveiligde pagina te wijzigen. Gewone gebruikers kunnen deze pagina niet bewerken.",

# History pages
# Geschiedenis pagina's
"revhistory" => "Bewerkingsgeschiedenis",
"nohistory"  => "Deze pagina heeft nog geen bewerkingen ondergaan.",
"revnotfound" => "Wijziging niet gevonden",
"revnotfoundtext" => "De opgevraagde oude versie van deze pagina is onvindbaar. Controleer a.u.b. de URL die u gebruikte om naar deze pagina te gaan.\n",
"loadhist"  => "Bezig met het laden van de paginageschiedenis",
"currentrev" => "Huidige versie",
"revisionasof" => "Versie op $1",
"cur"   => "huidig",
"next"   => "volgende",
"last"   => "vorige",
"orig"   => "orig",
"histlegend" => "Verklaring afkortingen: (wijz) = verschil met huidige versie, (vorige) = verschil met voorgaande versie, K = kleine wijziging",

# Diffs
# Verschil
"difference" => "(Verschil tussen bewerkingen)",
"loadingrev" => "bezig paginaversie te laden",
"lineno"  => "Regel $1:",
"editcurrent" => "De huidige versie van deze pagina bewerken",

# Search results
# Zoekresultaten
"searchresults" => "Zoekresultaten",
"searchresulttext" => "Voor meer informatie over zoeken op {{SITENAME}}: zie [[Project:Zoeken|Zoeken op {{SITENAME}}]].",
"searchquery" => "Voor zoekopdracht \"$1\"",
"badquery"  => "Slecht geformuleerde zoekopdracht",
"badquerytext" => "Uw zoekopdracht kon niet worden uitgevoerd. Dit kan komen doordat u geprobeerd hebt om een 'woord' van 1 letter te zoeken, of 1 van de <a HREF=\"http://nl.wikipedia.org/wiki/{{ns:4}}:Verboden_woorden\">Verboden woorden</a>.",
"matchtotals" => "De zoekterm \"$1\" is gevonden in $2 paginatitels en in de tekst van $3 pagina's.",
"nogomatch" => "Er bestaat geen pagina met deze titel, op zoek naar pagina's waarin de tekst voorkomt.",
"titlematches" => "Overeenkomst met volgende titels",
"notitlematches" => "Geen enkele paginatitel gevonden met de opgegeven zoekterm",
"textmatches" => "Overeenkomst met artikel inhoud",
"notextmatches" => "Geen artikel gevonden met opgegeven zoekterm",
"prevn"   => "vorige $1",
"nextn"   => "volgende $1",
"viewprevnext" => "($1) ($2) ($3) bekijken.",
"showingresults" => "Hieronder <b>$1</b> resultaten vanaf nummer <b>$2</b>.",
"showingresultsnum" => "Hieronder <b>$3</b> resultaten vanaf nummer <b>$2</b>.",
"nonefound"  => "<strong>Merk op:</strong> wanneer een zoekopdracht mislukt komt dat vaak door gebruik van veel voorkomende woorden zoals \"de\" en \"het\", die niet geïndexeerd zijn, of door verschillende zoektermen tegelijk op te geven (u krijgt dan alleen in pagina's waaarin alle opgegeven termen voorkomen).

Het kan natuurlijk dat er gewoon nog geen artikel aanwezig op {{SITENAME}} NL over dit onderwerp. Mogelijk is het aanwezig in een andere taal. Zoek met de <a HREF=\"http://pliny.wikipedia.org/tools/wikisearch.php\">multi-wikipedia zoeker</a> in de andere {{SITENAME}}'s, of buiten {{SITENAME}} in een <a href=\"http://encyclopedie.zoekhulp.nl/?refer=Wikipedia.nl\">andere encyclopedie</a>. Wanneer u gevonden heeft wat u zocht, kunt u wellicht daarover een artikel schrijven op {{SITENAME}} NL, zodat de volgende die zoekt wat u zocht het wel kan vinden. 
",
"powersearch" => "Zoeken",
"powersearchtext" => "   
 Zoek in naamruimten :<br>
$1<br>
$2 Toon redirects &nbsp; Zoek: $3 $9",   
"searchdisabled" => "Wegens een overbelasting van de server zijn sommige functies die het systeem extra belasten tijdelijk niet beschikbaar.
 Hierdoor is in de interne zoekfunctie van {{SITENAME}} (vermoedelijk) niet beschikbaar voor onbepaalde duur.<p>
Via google kunt u zoeken op {{SITENAME}}. <br>
<form method=\"get\" action=\"http://www.google.com/search\">
<table bgcolor=\"#ffffff\" style=\"width: 752px; height: 76px;\"><tbody><tr><td>
<a href=\"http://www.google.com/\">
<img src=\"http://www.google.com/logos/Logo_40wht.gif\" border=\"0\" alt=\"Google\"></a>
</td>
<td>
<input type=\"text\" name=\"q\" size=\"31\" maxlength=\"255\" value=\"\">
<input type=\"submit\" name=\"btnG\" value=\"Met Google zoeken in {{SITENAME}}\">
<font size=\"-1\">
<input type=\"hidden\" name=\"domains\" value=\"http://nl.wikipedia.org\"><br><input type=\"radio\" name=\"sitesearch\" value=\"\">Het volledige internet<input type=\"radio\" name=\"sitesearch\" value=\"http://nl.wikipedia.org\" checked=\"checked\">{{SITENAME}} NL<br>
</font>
</td></tr></tbody></table>
</form>
<p> U zal niet alle artikels kunnen vinden maar het zal niet veel schelen. 
Als u zoekt via google op {{SITENAME}} zal u ook treffers vinden op {{SITENAME}} die geen artikels zijn. 
{{SITENAME}} heeft bij de meeste artikels ook een \"overlegpagina\" en diverse soorten dienstpagina's. 
Enkel de pagina's die geen prefix (zoals {{ns:4}}:, Gebruiker: of Overleg:) hebben zijn artikels.
",
"blanknamespace" => "(encyclopedie)",

# Preferences page
# Voorkeuren
"preferences" => "Voorkeuren",
"prefsnologin" => "Niet aangemeld",
"prefsnologintext" => "U dient <a href=\"" .
  "{{localurle:Special:Userlogin}}\">aangemeld</a> te zijn om voorkeuren te kunnen instellen.",
"prefslogintext" => "U bent aangemeld als \"$1\". Uw interne identificatienummer is $2.

Een beschrijving van de verschillende opties staat op [[{{ns:4}}:Voorkeuren]].",
"prefsreset" => "Standaardvoorkeuren hersteld.",
"qbsettings" => "Menubalkinstellingen", 
"changepassword" => "Wachtwoord wijzigen",
"skin" => "{{SITENAME}}-Uiterlijk",
"math" => "Wiskundige formules",
"math_failure" => "Wiskundige formule niet begrijpelijk",
"math_unknown_error" => "Onbekende fout in formule",
"math_unknown_function" => "Onbekende functie in formule",
"math_lexing_error" => "Lexicografische fout in formule",
"math_syntax_error" => "Syntax-fout in formule",
# "dateformat" => "Wijze van tonen datum",
# Not used in nl, kept here for reference if we want to change it
"saveprefs"  => "Voorkeuren opslaan",
"resetprefs" => "Standaardvoorkeuren herstellen",
"oldpassword" => "Huidig wachtwoord",
"newpassword" => "Nieuw wachtwoord",
"retypenew"  => "Voer het nieuwe wachtwoord nogmaals in",
"textboxsize" => "Afmetingen tekstveld",
"rows"   => "Regels",
"columns"  => "Kolommen",
"searchresultshead" => "Instellingen voor zoekresultaten",
"resultsperpage" => "Aantal per bladzijde te tonen zoekresultaten",
"contextlines" => "Aantal regels per gevonden pagina",
"contextchars" => "Aantal tekens van de context per regel",
"stubthreshold" => "Grootte waaronder een pagina als 'stub' wordt aangegeven",
"recentchangescount" => "Aantal titels in lijst recente wijzigingen",
"savedprefs" => "Uw voorkeuren zijn opgeslagen.",
"timezonetext" => "De tijd van de server is UTC (Coordinated Universal Time) Geef aan hoeveel uur de plaatselijke tijd in uw woonplaats verschilt met die van de server. Voor o.a. België en Nederland: +1 (+2 zomertijd); voor Suriname en voor de Nederlandse Antillen: -4; voor Zuid-Afrika: +2.",
"localtime" => "Plaatselijke tijd",
"timezoneoffset" => "Tijdsverschil",
"servertime" => "De locale tijd van de {{SITENAME}}-server:",
"guesstimezone" => "Vanuit de browser toe te voegen",
"emailflag" => "E-mail ontvangen van andere gebruikers uitschakelen",
"defaultns" => "Naamruimten om direct in te zoeken:",

# Recent changes
# Recente wijzigingen
"changes" => "wijzigingen",
"recentchanges" => "Recente wijzigingen",
"recentchangestext" => "Deze pagina toont de laatste aanpassingen aan artikelen van {{SITENAME}} NL. <br>
Ben je hier nieuw? Lees dan ook [[{{ns:4}}:Welkom voor nieuwelingen|Welkom voor nieuwelingen]] -- Wil je een pagina verwijderd hebben? Ga dan naar [[{{ns:4}}:Te verwijderen pagina's|Te verwijderen pagina's]] -- Wil je iets met andere gebruikers overleggen? Ga naar [[{{ns:4}}:Overleg gewenst|Overleg gewenst]] of meld je aan voor de discussielijst [http://mail.wikipedia.org/mailman/listinfo/wikinl-l WikiNL-l] -- Zin in een gezellige babbel? Kom naar de [[{{ns:4}}:De kroeg|De kroeg]] of doe mee op ons nieuwe [http://chat.wikipedia.be Wiki-chatkanaal].<p>
Om {{SITENAME}} te laten slagen is het erg belangrijk geen materiaal toe te voegen waarop iemand anders auteursrechten heeft, tenzij je daartoe toestemming hebt. De wettelijke gevolgen van inbreuk op de rechten van anderen zouden de hele onderneming grote schade kunnen toebrengen.",
"rcloaderr"  => "Meest recente wijzigingen laden",
"rcnote"  => "Hieronder zijn de <strong>$1</strong> laatste wijzigingen gedaan in de laatste <strong>$2</strong> dagen.",
"rcnotefrom"  => "Veranderingen sinds <b>$2</b> (met een maximum van <b>$1</b> veranderingen).",
"rclistfrom"  => "Toon de veranderingen vanaf $1",
"rclinks"  => "Bekijk de $1 laatste wijzigingen in de laatste $2 dagen - $3 kleine wijzigingen.",
"rchide"  => "in $4 vorm; $1 kleine wijzigingen; $2 wijzigingen op speciale pagina's zoals overleg- en gebruikerspagina's; $3 meervoudige wijzigingen.",
"rcliu" => "- $1 edits van geregistreerde gebruikers",
"diff"   => "wijz",
"hist"   => "hist",
"hide"   => "verberg",
"show"   => "toon",
"tableform"  => "tabel",
"listform"  => "lijst",
"nchanges"  => "$1 wijzigingen",
"minoreditletter" => "K",
"newpageletter" => "N",

# Upload
#
"upload"  => "Upload",
"uploadbtn"  => "upload bestand",
"uploadlink" => "upload afbeelding",
"reupload"  => "Opnieuw uploaden",
"reuploaddesc" => "Terug naar het uploadformulier.",
"uploadnologin" => "Niet aangemeld",
"uploadnologintext" => "U dient <a href=\"" .
  "{{localurle:Speciaal:Userlogin}}\">aangemeld te zijn</a>
om bestanden te uploaden.",
"uploadfile" => "upload bestand",
"uploaderror" => "upload fout",
"uploadtext" => "'''STOP!''' Voor u iets hier upload,
wees zeker dat het in overeenkomst is met het {{SITENAME}} NL
[[Project:Beleid_voor_gebruik_van_afbeeldingen|afbeeldingsbeleid]].

Om de reeds ge-uploade bestanden te bekijken of te zoeken ga naar de
[[Speciaal:Imagelist|lijst van ge-uploade bestanden]].
Uploads en verwijderingen worden bijgehouden in het
[[Project:Upload_logboek|upload logboek]].

Gebruik het onderstaande formulier om bestanden zoals afbeeldingen en geluidsbestanden die relevant zijn voor uw artikel te u-loaden. Bij de meeste browers zoals 'Internet Explorer' en 'Mozilla' zult u een \"Bladeren...\" of \"Browse..\" knop zien die een standaard dialoogscherm van uw bestuuringssysteem oproept. Kiest u een bestand, dan zal het ingevuld worden in het veld naast de \"Bladeren...\" knop. U dient ook het vakje aan te vinken waarmee u bevestigt dat er geen schending van auteursrechten plaatsvindt door het gebruik van dat bestand. Vul het veld \"Omschrijving\" in. Druk op de \"Upload\" knop om het uploaden te voltooien. Dit kan even duren als u een langzame internetverbinding gebruikt.

Gebruik bij voorkeur JPEG voor foto's, PNG voor tekeningen en dergelijke en OGG voor geluid. 
Geef uw bestanden een duidelijk omschrijvende naam om verwarring te voorkomen. Om het bestand in een pagina te laten verschijnen, kunt u het volgende doen;  '''<nowiki>[[afbeelding:uw_foto.jpg]]</nowiki>''' of '''<nowiki>[[afbeelding:uw_logo.png|alt text]]</nowiki>''' of '''<nowiki>[[media:uw_geluid.ogg]]</nowiki>''' voor audio.

Vergeet niet dat net als met andere pagina's op {{SITENAME}} anderen de ge-uploade bestanden kunnen verwijderen indien men denkt dat dat in het voordeel van het project is. Bij misbruik van dit systeem kan u de toegang tot {{SITENAME}} NL ontzegd worden.",
"uploadlog"  => "upload logboek",
"uploadlogpage" => "Upload_logboek",
"uploadlogpagetext" => "Hieronder de lijst met de meest recent ge-uploade bestanden. Alle tijden zijn servertijd (UTC).
<ul>
</ul>
",
"filename"  => "Bestandsnaam",
"filedesc"  => "Beschrijving",
"filestatus" => "Auteursrechtensituatie",
"filesource" => "Auteur/bron",
"affirmation" => "Ik verklaar dat de eigenaar van de rechten op dit bestand toestemt om het onder de voorwaarden van $1 te verspreiden.",
"copyrightpage" => "{{ns:4}}:Auteursrechten",
"copyrightpagename" => "{{SITENAME}} NL auteursrechten",
"uploadedfiles" => "Ge-uploade bestanden",
"noaffirmation" => "U dient te bevestigen dat deze handeling geen inbreuk maakt op auteursrechten.",
"ignorewarning" => "Negeer de waarschuwing en sla het bestand op.",
"minlength"  => "De naam van het bestand moet uit ten minste drie tekens bestaan.",
"badfilename" => "De naam van het bestand is gewijzigd in \"$1\".",
"badfiletype" => "\".$1\" is geen aanbevolen afbeeldings bestandsformaat.",
"largefile"  => "Aanbeveling: maak afbeeldingen niet groter dan 100k",
"successfulupload" => "De upload was succesvol",
"fileuploaded" => "<b>Het uploaden van bestand \"$1\" is geslaagd.</b> Gelieve deze link naar de omschrijvingspagina te volgen: ($2). Vul daar informatie in over dit bestand, bijvoorbeeld de oorsprong, wanneer en door wie het gemaakt is en wat u verder er nog over te vertellen heeft.",
"uploadwarning" => "Upload waarschuwing ",
"savefile"  => "Bestand opslaan",
"uploadedimage" => "heeft ge-upload: \"$1\"",
"uploaddisabled" => "Uploads zijn op deze server niet mogelijk.",

# Image list
# Afbeeldingslijst
"imagelist"  => "Lijst van afbeeldingen",
"imagelisttext" => "Hier volgt een lijst met $1 afbeeldingen geordend $2.",
"getimagelist" => "Lijst van afbeeldingen ophalen",
"ilshowmatch" => "Toon alle afbeeldingen waarvan de naam voldoet aan",
"ilsubmit"  => "Zoek",
"showlast"  => "Toon de laatste $1 afbeeldingen geordend $2.",
"all"   => "alle",
"byname"  => "op naam",
"bydate"  => "op datum",
"bysize"  => "op grootte",
"imgdelete"  => "verw",
"imgdesc"  => "besc",
"imglegend"  => "Verklaring: (besc) = toon/verander beschrijving van de afbeelding, (verw) = verwijdering de afbeelding.",
"imghistory" => "Geschiedenis van de afbeelding",
"revertimg"  => "rev",
"deleteimg"  => "verw",
"deleteimgcompletely"  => "verw",
"imghistlegend" => "Verklaring: (cur)= huidige afbeelding, (verw) = verwijder de oude versie, (rev) = breng oude versie terug.<br>
<i>Klik op de datum om de afbeeldingen die ge-upload zijn op die datum te zien</i>.",
"imagelinks" => "Afbeeldingsverwijzingen",
"linkstoimage" => "Deze afbeelding wordt gebruikt op de volgende pagina's:",
"nolinkstoimage" => "Geen enkele pagina gebruikt deze afbeelding.",

# Statistics
# Statistieken
"statistics" => "Statistieken",
"sitestats"  => "Statistieken betreffende {{SITENAME}} NL",
"userstats"  => "Statistieken betreffende gebruikers",
"sitestatstext" => "Er zijn <b>$1</b> pagina's in de database. Hierbij zijn inbegrepen \"Overleg\" pagina's, pagina's over {{SITENAME}}, extreem korte \"stub\" pagina's, redirects, en diverse andere pagina's die waarschijnlijk niet als artikel moeten worden geteld. Na uitsluiting daarvan, is er een geschat aantal van <b>$2</b> artikels.<p>
Er is in totaal $3 maal een pagina bekeken, en $4 maal een pagina bewerkt. Dat geeft een gemiddelde van $5 bewerkingen per pagina, en $6 paginabezoeken per wijziging.",
"userstatstext" => "Er zijn momenteel $1 geregistreerde gebruikers; hiervan zijn er $2 systeembeheerders (zie $3).",

# Maintenance Page   
#
"maintenance"           => "Onderhoudspagina",
"maintnancepagetext"    => "Op deze pagina vindt u een aantal handige zoekopdrachten om kleine alledaagse problemen in de {{SITENAME}} te verhelpen. Sommige van deze zoekopdrachten vormen een grote belasting voor de database; ga dus niet na elke paar pagina's die u hersteld heeft, de pagina opnieuw laden.",
"maintenancebacklink"   => "Terug naar de Onderhoudspagina",
"disambiguations"       => "Doorverwijspagina's",
"disambiguationspage"   => "{{ns:4}}:Doorverwijspagina",
"disambiguationstext"   => "De onderstaande artikelen verwijzen naar een [[{{ns:4}}:Doorverwijspagina|doorverwijspagina]]. Deze zouden waarschijnlijk direct naar de onderwerpspagina moeten verwijzen. <br>Als doorverwijspagina's worden die pagina's beschouwd waar vanaf $1 naar verwezen wordt.<br>Opmerking: Deze lijst toont alleen pagina's vanuit de hoofdnaamruimte, en dus niet Overlegpagina's, {{ns:4}}:pagina's en dergelijke.",
"doubleredirects"       => "Dubbele redirects",
"doubleredirectstext"   => "<b>Let op:</b> Er kunnen in deze lijst redirects staan die er niet in thuishoren. Dat komt over het algemeen doordat er na de #REDIRECT nog andere links op de pagina staan.<br>\nOp elke regel vindt u de eerste redirectpagina, de tweede redirectpagina en de eerste regel van de tweede redirectpagina. Normaal gesproken bevat deze laatste de pagina waar de eerste redirect naartoe zou moeten verwijzen.",
"brokenredirects"       => "Gebroken redirects",
"brokenredirectstext"   => "De onderstaande redirectpagina's bevatten een redirect naar een niet-bestaande pagina.",
"selflinks"             => "Pagina's die naar zichzelf verwijzen",
"selflinkstext"         => "De volgende pagina's verwijzen naar zichzelf, wat niet hoort te gebeuren.",
"mispeelings"           => "Pagina's met spelfouten",
"mispeelingstext"       => "De volgende pagina's bevatten een veel voorkomende spel- of typfout, die staat aangegeven op de lijst in $1. Daar staat meestal ook (tussen haakjes) de juiste spelling.",
"mispeelingspage"       => "Veel voorkomende spelfouten",
"missinglanguagelinks"  => "Ontbrekende taallinks",
"missinglanguagelinksbutton"    => "Vind ontbrekende taallinks voor",
"missinglanguagelinkstext"      => "De onderstaande artikelen bevatten geen taallink naar een overeenkomende pagina in de taal \"$1\".",

# Miscellaneous special pages
# Diverse speciale pagina's
"orphans"  => "Weespagina's",
"lonelypages" => "Weespagina's",
"unusedimages" => "Ongebruikte afbeeldingen",
"popularpages" => "Populaire artikels",
"nviews"  => "$1 keer bekeken",
"wantedpages" => "Gewenste pagina's",
"nlinks"  => "$1 verwijzingen",
"allpages"  => "Alle pagina's",
"randompage" => "Willekeurig artikel",
"shortpages" => "Korte artikels",
"longpages"  => "Lange artikels",
"listusers"  => "Lijst van gebruikers",
"specialpages" => "Speciale pagina's",
"spheading"  => "",
"sysopspheading" => "Alleen voor systeembeheerders",
"developerspheading" => "Alleen voor systeemontwikkelaars",
"protectpage" => "Beveilig pagina",
"recentchangeslinked" => "Volg links",
"rclsub"  => "(van pagina's waarnaar \"$1\" verwijst)",
"debug"   => "Bugreparatie",
"newpages"  => "Nieuwe artikels",
"ancientpages" => "Oudste artikels",
#"intl" => "Taallinks",  -  not active yet
"movethispage" => "Verplaats deze pagina",
"unusedimagestext" => "<p>Let op! Het zou kunnen dat er via een directe link verwezen wordt naar een afbeelding, bijvoorbeeld vanuit een anderstalige {{SITENAME}}. Het is daarom mogelijk dat een afbeelding hier vermeld staat terwijl het wel degelijk gebruikt wordt.",
"booksources" => "Boekhandels",
"booksourcetext" => "Hieronder is een lijst van externe websites die boeken verkopen en ook verdere informatie hierover kunnen verstekken. Via een ISBN-nummer in een artikel kunt u via deze pagina een werk opzoeken. <p>Deze dienst is enkel ter uwer informatie. {{SITENAME}} NL heeft <u>geen enkele</u> relatie met deze websites.",
"alphaindexline" => "$1 tot $2",

# Email this user
# E-mail deze gebruiker
"mailnologin" => "Geen verzendadres beschikbaar",
"mailnologintext" => "U dient <a href=\"" .
  "{{localurle:Speciaal:Userlogin}}\">aangemeld te zijn </a>
en een geldig e-mailadres in uw <a href=\"" .
  "{{localurle:Speciaal:Preferences}}\">voorkeuren</a>
te vermelden om deze functie te kunnen gebruiken.",
"emailuser"  => "E-mail deze gebruiker",
"emailpage"  => "E-mail gebruiker",
"emailpagetext" => "Indien deze gebruiker een geldig e-mailadres heeft opgegeven dan kunt u via dit formulier een bericht verzenden. Het e-mailadres dat u heeft opgegeven bij uw voorkeuren zal als afzender gebruikt worden.",
"noemailtitle" => "Geen e-mailadres gekend voor deze gebruiker",
"noemailtext" => "Deze gebruiker heeft geen geldig e-mailadres opgegeven of heeft deze functie uitgeschakelt.",
"emailfrom"  => "Van",
"emailto"  => "Aan",
"emailsubject" => "Onderwerp",
"emailmessage" => "Bericht",
"emailsend"  => "Verstuur bericht",
"emailsent"  => "E-mail versturen",
"emailsenttext" => "Uw bericht is verzonden.",

# Watchlist
# Volglijst
"watchlist"  => "Volglijst",
"watchlistsub" => "(van gebruiker \"$1\")",
"nowatchlist" => "Er staat niets op uw volglijst.",
"watchnologin" => "U bent niet aangemeld",
"watchnologintext" => "Om uw volglijst te veranderen dient u eerst <a href=\"" .
  "{{localurle:Speciaal:Userlogin}}\">aangemeld</a>
te zijn.",
"addedwatch" => "Toegevoegd aan volglijst",
"addedwatchtext" => "De pagina \"$1\" is aan uw <a href=\"" .
  "{{localurle:Speciaal:Watchlist}}\">volglijst</a> toegevoegd.
Toekomstige wijzigingen aan deze pagina en overlegpagina zullen hier vermeld worden. 
Ook zullen deze pagina's in het <b>vet</b> verschijnen in de <a href=\"" .
  "{{localurle:Speciaal:Recentchanges}}\">lijst van recente wijzigingen</a> zodat u ze eenvoudiger kunt opmerken.</p>

<p>Indien u een pagina wenst te verwijderen van uw volglijst klik dan op \"Van volglijst verwijderen\" in de menubalk.",
"removedwatch" => "Van volglijst verwijderen",
"removedwatchtext" => "De pagina \"$1\" is van uw volglijst verwijderd.",
"watchthispage" => "Volg deze pagina",
"unwatchthispage" => "Niet meer volgen",
"notanarticle" => "Is geen artikel",
"watchnochange" => "Geen van de pagina's op uw volglijst is in deze periode gewijzigd",
"watchdetails" => "Er staan $1 pagina's op uw volglijst (overlegpagina's niet meegeteld.
In de aangegeven periode zijn $2 pagina's gewijzigd.
$3. (<a href='$4'>Toon mijn volledige volglijst</a>.)",
"watchmethod-recent" => "Bij de recent gewijzigde pagina's gezocht naar gevolgde pagina's",
"watchmethod-list" => "Bij de gevolgde pagina's naar wijzigingen bekeken",
"removechecked" => "Verwijderen",
"watchlistcontains" => "U heeft $1 pagina's op uw volglijst",
"watcheditlist" => "Hier is een lijst van alle pagina's op uw volglijst.
Vink de vakjes aan voor de pagina's die u wilt verwijderen, en druk dan
op 'Verwijderen' onderaan deze pagina.",
"removingchecked" => "De aangegeven pagina's worden van uw volglijst verwijderd.",
"couldntremove" => "Verwijdering van '$1' onmogelijk.",
"iteminvalidname" => "Incorrecte naam '$1'",
"wlnote" => "Getoond worden de laatste $1 wijzigingen in de laatste $2 uur.",
"wlshowlast" => "Toon de laatste $1 uur $2 dagen $3",

# Delete/protect/revert
# Verwijderen/beschermen/annuleren
"deletepage" => "Pagina verwijderen",
"confirm"  => "Bevestig",
"excontent" => "De inhoud was:",
"exbeforeblank" => "Voor leegmaking was de inhoud:",
"exblank" => "Dit was een lege pagina.",
"confirmdelete" => "Bevestig verwijdering",
"deletesub"  => "(Verwijderen \"$1\")",
"historywarning" => "Waarschuwing: Deze pagina heeft een voorgeschiedenis. Overtuig uzelf ervan dat geen van de oudere versies een te behouden pagina is.",
"confirmdeletetext" => "U bent staat op het punt een pagina of afbeelding voorgoed te verwijderen. Dit verwijdert alle inhoud en geschiedenis van de database. Bevestig hieronder dat dit inderdaad uw bedoeling is, dat u de gevolgen begrijpt, en dat uw verwijdering overeenkomt met de [[{{ns:4}}:Instructies]].",
"confirmcheck" => "Ja, ik wil dit voorgoed verwijderen.",
"actioncomplete" => "Actie voltooid",
"deletedtext" => "\"$1\" is verwijderd. Zie $2 voor een overzicht van recente verwijderingen.",
"deletedarticle" => "\"$1\" is verwijderd",
"dellogpage" => "Logboek_verwijderde_pagina's", # This one needs the underscores!
"dellogpagetext" => "Hieronder ziet u een lijst van de meest recentelijk verwijderde pagina's en afbeeldingen. Alle tijden zijn servertijd, UTC-0.",
"deletionlog" => "Logboek verwijderde pagina's",
"reverted"  => "Eerdere versie hersteld",
"deletecomment" => "Reden voor verwijdering",
"imagereverted" => "De omzetting naar de oudere versie is geslaagd.",
"rollback"      => "Wijzigingen ongedaan maken",
"rollbacklink"  => "Terugdraaien",
"rollbackfailed" => "Ongedaan maken van wijzigingen mislukt.",
"cantrollback"  => "Ongedaan maken van wijzigingen onmogelijk: Dit artikel heeft slechts 1 auteur.",
"allreadyrolled" => "[[Gebruiker:$3|$3]] heeft de pagina [[$1]] bewerkt na de laatste bewerking door [[Gebruiker:$2|$2]].",
"editcomment"   => "Commentaar bij de wijziging: <i>$1</i>",
"revertpage"    => "Hersteld tot de versie na de laatste wijziging door $1.",

# Undelete
"undelete" => "Verwijderde pagina terugplaatsen",
"undeletepage" => "Verwijderde pagina's bekijken en terugplaatsen",
"undeletepagetext" => "De onderstaande pagina's zijn verwijderd, maar bevinden zich nog steeds in het archief, en kunnen teruggeplaatst worden.",
"undeletearticle" => "Verwijderde pagina terugplaatsen",
"undeleterevisions" => "$1 versies in het archief",
"undeletehistory" => "Als u een pagina terugplaatst, worden alle versies als oude versies teruggeplaatst. Als er al een nieuwe pagina met dezelfde naam is aangemaakt, zullen deze versies als oude versies worden teruggeplaatst, maar de huidige versie niet gewijzigd worden.",
"undeleterevision" => "Verwijderde versie van $1",
"undeletebtn" => "Terugplaatsen!",
"undeletedarticle" => "\"$1\" is teruggeplaatst.",
"undeletedtext" =>"Het artikel [[$1]] is teruggeplaatst. Zie [[{{ns:4}}:Logboek verwijderde pagina's]] voor een lijst van de meest recente verwijderingen en terugplaatsingen.",

# Contributions
# Bijdragen
"contributions" => "Bijdragen per gebruiker",
"mycontris" => "Mijn bijdragen",
"contribsub" => "Voor $1",
"nocontribs" => "Geen wijzigingen gevonden die aan de gestelde criteria voldoen.",
"ucnote"  => "Hieronder ziet u de laatste <b>$1</b> wijzigingen van deze gebruiker in de laatste <b>$2</b> dagen.",
"uclinks"  => "Bekijk de laatste <b>$1</b> veranderingen; bekijk de laatste <b>$2</b> dagen.",
"uctop" => " (laatste wijziging)",

# What links here
# Wat linkt hier
"whatlinkshere" => "Referenties",
"notargettitle" => "Geen doelpagina",
"notargettext" => "U hebt niet gezegd voor welke pagina u deze functie wilt bekijken.",
"linklistsub" => "(lijst van verwijzingen)",
"linkshere"  => "De volgende pagina's verwijzen hiernaartoe:",
"nolinkshere" => "Geen enkele pagina verwijst hierheen.",
"isredirect" => "redirect pagina",

# Block/unblock IP
#
"blockip"  => "Gebruiker blokkeren",
"blockiptext" => "Gebruik het onderstaande formulier om een bepaald IP-adres of een bepaalde gebruikersnaam de schrijftoegang te ontnemen. Gebruik deze optie spaarzaam! Het is bedoeld om vandalisme te voorkomen. Misbruik van deze mogelijkheid kan tot gevolg hebben dat uw systeembeheerderschap wordt weggenomen. Vul hieronder een specifieke reden in.",
"ipaddress"  => "IP-adres of gebruikersnaam",
"ipbreason"  => "Reden",
"ipbsubmit"  => "Blokkeer deze gebruiker",
"badipaddress" => "Geen bestaande gebruikersnaam of geldig IP-adres",
"noblockreason" => "U dient een reden op te geven voor het blokkeren van een gebruiker.",
"blockipsuccesssub" => "Blokkering gelukt",
"blockipsuccesstext" => "\"$1\" is geblokkeerd.<br>
Zie de [[speciaal:Ipblocklist|Lijst van geblokkeerde IP-adressen]].",
"unblockip"  => "De-blokkeer gebruiker",
"unblockiptext" => "Gebruik het onderstaande formulier om terug schrijftoegang te geven aan een geblokkeerde gebruiker of IP-adres.",
"ipusubmit"  => "De-blokkeer deze gebruiker.",
"ipusuccess" => "\"$1\" is gedeblokkeerd.",
"ipblocklist" => "Lijst van geblokkeerde gebruikers en IP-adressen.",
"blocklistline" => "Op $1 blokkeerde $2: $3",
"blocklink"  => "blokkeer",
"unblocklink" => "de-blokkeer",
"contribslink" => "bijdragen",
"autoblocker" => "U werd geblokkeerd omdat uw IP-adres overeenkomt met dat van \"$1\". Deze gebruiker werd geblokkeerd met als reden: \"$2\".",

# Developer tools
# Ontwikkelingsgereedsschap
"lockdb"  => "Blokkeer de database",
"unlockdb"  => "De-blokkeer de database",
"lockdbtext" => "Waarschuwing: De database blokkeren heeft tot gevolg dat geen enkele gebruiker meer in staat is de pagina's te bewerken, hun voorkeuren te wijzigen of iets anders te doen waarvoor er wijzigingen in de database nodig zijn.",
"unlockdbtext" => "Het de-blokkeren van de database zal de gebruikers de mogelijkheid geven om wijzigingen aan pagina's op te slaan, hun voorkeuren te wijzigen en alle andere bewerkingen waarvoor er wijzigingen in de database nodig zijn. Is dit inderdaad wat u wilt doen?.",
"lockconfirm" => "Ja, ik wil de database blokkeren.",
"unlockconfirm" => "Ja, ik wil de database de-blokkeren.",
"lockbtn"  => "Blokkeer de database",
"unlockbtn"  => "De-blokkeer de database",
"locknoconfirm" => "U heeft niet het vakje aangevinkt om uw keuze te bevestigen.",
"lockdbsuccesssub" => "Blokkering database succesvol",
"unlockdbsuccesssub" => "Blokkering van de database opgeheven",
"lockdbsuccesstext" => "De database van {{SITENAME}} NL is geblokkeerd.
Vergeet niet de database opnieuw te de-blokkeren zodra u klaar bent met uw onderhoud.",
"unlockdbsuccesstext" => "Blokkering van de database van {{SITENAME}} NL is opgeheven.",

# SQL query
# SQL raadplegen
"asksql"  => "SQL raadplegen",
"asksqltext" => "Gebruik het onderstaande formulier om een direct verzoek naar de database van {{SITENAME}} NL te zenden. Gebruik enkelvoudige aanhalingstekens ('zoals hier') voor letterlijke teksten. Een ingewikkelde aanvraag kan de sever vaak extra belasten. Gelieve deze mogelijkheid daarom spaarzaam te gebruiken. Zie ook: [[{{ns:4}}:SQL opdrachten]].",
"sqlislogged" => "Alle SQL Queries worden gelogd.",
"sqlquery"  => "Voer opdracht in",
"querybtn"  => "Verstuur opdracht",
"selectonly" => "Opdrachten anders dan \"SELECT\" zijn voorbehouden aan {{SITENAME}} ontwikkelaars.",
"querysuccessful" => "Opdracht succesvol",

# Move page
# Verplaats pagina
"movepage"  => "Verplaats pagina",
"movepagetext" => "Door middel van het onderstaande formulier kunt u de titel van een pagina hernoemen. De voorgeschiedenis van de oude pagina zal deze van de nieuwe worden. De oude titel zal automatisch een doorverwijzing worden naar de nieuwe. U kunt een dergelijke hernoeming alleen doen plaatsvinden, als er geen pagina bestaat met de nieuwe naam, of als er slechts een redirect zonder verdere geschiedenis is.",
"movepagetalktext" => "De bijbehorende overlegpagina wordt ook verplaatst, maar '''niet''' in de volgende gevallen:
* Als de pagina naar een andere naamruimte wordt verplaatst
* Als er al een niet-lege Overlegpagina bestaat onder de andere naam
* Als u de onderstaande radiobox niet aangevinkt laat",
"movearticle" => "Verplaats pagina",
"movenologin" => "Niet aangemeld",
"movenologintext" => "U dient <a href=\"" .
  "{{localurle:Speciaal:Userlogin}}\">aangemeld</a>
te zijn om een pagina te verplaatsen.",
"newtitle"  => "Naar de nieuwe titel",
"movepagebtn" => "Verplaats pagina",
"pagemovedsub" => "De verplaatsing was succesvol",
"pagemovedtext" => "Pagina \"[[$1]]\" is verplaatst naar \"[[$2]]\".",
"articleexists" => "Er is reeds een pagina met deze titel of de titel is ongeldig. <br>Gelieve een andere titel te kiezen.",
"talkexists" => "De pagina zelf is verplaatst, maar de Overlegpagina kon niet worden verplaatst, omdat de doeltitel al een niet-lege overlegpagina had. Combineer de overlegpagina's a.u.b. handmatig.",
"movedto"  => "verplaatst naar",
"movetalk"  => "Verplaats \"Overleg\" pagina ook indien aanwezig.",
"talkpagemoved" => "De bijhorende overlegpagina is ook verplaatst.",
"talkpagenotmoved" => "De bijhorende overlegpagina is <strong>niet</strong> verplaatst.",

# Math
           'mw_math_png' => "Altijd als PNG weergeven",
           'mw_math_simple' => "HTML voor eenvoudige formules, anders PNG",
           'mw_math_html' => "HTML indien mogelijk, anders PNG",
           'mw_math_source' => "Laat de TeX broncode staan (voor tekstbrowsers)",
           'mw_math_modern' => "Aanbevolen methode voor recente browsers",
	   'mw_math_mathml' => 'MathML',
);

class LanguageNl extends LanguageUtf8 {
	
	function getDefaultUserOptions () {
		$opt = Language::getDefaultUserOptions();
		$opt["date"]=2;
		return $opt;
	}
	
	function getNamespaces() {
		global $wgNamespaceNamesNl;
		return $wgNamespaceNamesNl;
	}
	
	function getNsText( $index ) {
		global $wgNamespaceNamesNl;
		return $wgNamespaceNamesNl[$index];
	}
	
	function getNsIndex( $text ) {
		global $wgNamespaceNamesNl;
		
		foreach ( $wgNamespaceNamesNl as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}
	
	# Inherit specialPage()
	
	function getQuickbarSettings() {
		global $wgQuickbarSettingsNl;
		return $wgQuickbarSettingsNl;
	}
	
	function getSkinNames() {
		global $wgSkinNamesNl;
		return $wgSkinNamesNl;
	}
	
	# Inherit userAdjust()
	
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }
		
		$d = (0 + substr( $ts, 6, 2 )) . " " .
		$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " . 
		substr( $ts, 0, 4 );
		return $d;
	}
	
	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}
	
	function getValidSpecialPages() {
		global $wgValidSpecialPagesNl;
		return $wgValidSpecialPagesNl;
	}	
	
	function getSysopSpecialPages() {
		global $wgSysopSpecialPagesNl;
		return $wgSysopSpecialPagesNl;
	}
	
	function getDeveloperSpecialPages() {
		global $wgDeveloperSpecialPagesNl;
		return $wgDeveloperSpecialPagesNl;
	}
	
	function getMessage( $key ) {
		global $wgAllMessagesNl;
		if( isset( $wgAllMessagesNl[$key] ) ) {
			return $wgAllMessagesNl[$key];
		} else {
			return Language::getMessage( $key );
		}
	}
	
	# Inherit iconv(), ucfirst(), stripForSearch(), recodeForEdit(), recodeInput()
	# since they are same as English/Latin1

}

?>
