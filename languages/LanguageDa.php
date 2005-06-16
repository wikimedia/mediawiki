<?php
/** Danish (Dansk)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesDa = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciel',
	NS_MAIN				=> '',
	NS_TALK				=> 'Diskussion',
	NS_USER				=> 'Bruger',
	NS_USER_TALK		=> 'Bruger_diskussion',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace.'_diskussion',
	NS_IMAGE			=> 'Billede',
	NS_IMAGE_TALK		=> 'Billede_diskussion',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki_diskussion',
	NS_TEMPLATE  		=> 'Skabelon',
	NS_TEMPLATE_TALK	=> 'Skabelon_diskussion',
	NS_HELP				=> 'Hjælp',
	NS_HELP_TALK		=> 'Hjælp_diskussion',
	NS_CATEGORY			=> 'Kategori',
	NS_CATEGORY_TALK	=> 'Kategori_diskussion'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsDa = array(
	"Ingen", "Fast venstre", "Fast højre", "Flydende venstre"
);

/* private */ $wgSkinNamesDa = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgi",
	'cologneblue' => "Cologne-blå",
	'smarty' => "Paddington",
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsDa = array();


/* private */ $wgBookstoreListDa = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
) + $wgBookstoreListEn;


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesDa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Mine brugerindstillinger",
	"Watchlist"	=> "Min overvågningsliste",
	"Recentchanges" => "Seneste ændringer",
	"Upload"	=> "Læg filer op",
	"Imagelist"	=> "Billedliste",
	"Listusers"	=> "Registrerede brugere",
	"Statistics"	=> "Statistik om siden",
	"Randompage"	=> "Tilfældig artikel",

	"Lonelypages"	=> "Forældreløse artikler",
	"Unusedimages"	=> "Forældreløse filer",
	"Popularpages"	=> "Populære artikler",
	"Wantedpages"	=> "Mest ønskede artikler",
	"Shortpages"	=> "Korte artikler",
	"Longpages"		=> "Lange artikler",
	"Newpages"		=> "Nyeste artikler",
	"Ancientpages"	=> "Ældste artikler",
	"Deadendpages"	=> "Blindgydesider",
#	"Intl"			=> "Sproghenvisninger",
	"Allpages"		=> "Alle sider efter titel",

	"Ipblocklist"	=> "Blokerede IP-adresser",
	"Maintenance"	=> "Vedligeholdelsesside",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Eksterne bogkilder",
	"Categories"	=> "Kategorier",
	"Export"	=> "Eksportér sider i XML format",
	"Version"	=> "Vis MediaWiki version",
);

/* private */ $wgSysopSpecialPagesDa = array(
	"Blockip"		=> "Bloker en IP-adresse",
	"Asksql"		=> "Lav en forespørgsel i databasen",
	"Undelete"		=> "Se og gendan slettede sider",
	"Makesysop"		=> "Lav en bruger til administrator"
);

/* private */ $wgDeveloperSpecialPagesDa = array(
	"Lockdb"		=> "Skrivebeskyt databasen",
	"Unlockdb"		=> "Gendan skriveadgangen til databasen",
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => ""

# NOTE: To turn off "Disclaimers" in the title links,
# set "disclaimers" => ""

# NOTE: To turn off "Community portal" in the title links,
# set "portal" => ""


/* private */ $wgAllMessagesDa = array(
# User Toggles
"tog-underline" => "Understreg henvisninger",
"tog-highlightbroken" => "Brug røde henvisninger til tomme sider",
"tog-justify"	=> "Justér afsnit",
"tog-hideminor" => "Skjul mindre ændringer i seneste ændringer listen",
"tog-usenewrc" => "Udvidet seneste ændringer liste<br />(ikke for alle browsere)",
"tog-numberheadings" => "Automatisk nummerering af overskrifter",
"tog-showtoolbar" => "Vis værktøjslinje til redigering",
"tog-editondblclick" => "Redigér sider med dobbeltklik (JavaScript)",
"tog-editsection"=>"Redigér afsnit ved hjælp af [redigér]-henvisning",
"tog-editsectiononrightclick"=>"Redigér afsnit ved at højreklikke<br /> på afsnittets titel (JavaScript)",
"tog-showtoc"=>"Vis indholdsfortegnelse<br />(for artikler med mere end tre afsnit)",
"tog-rememberpassword" => "Husk adgangskode til næste besøg",
"tog-editwidth" => "Redigeringsboksen har fuld bredde",
"tog-watchdefault" => "Overvåg nye og ændrede artikler",
"tog-minordefault" => "Markér som standard alle ændringer som mindre",
"tog-previewontop" => "Vis forhåndsvisning før redigeringsboksen",
"tog-nocache" => "Husk ikke siderne til næste besøg",

# Dates

'sunday' => 'søndag',
'monday' => 'mandag',
'tuesday' => 'tirsdag',
'wednesday' => 'onsdag',
'thursday' => 'torsdag',
'friday' => 'fredag',
'saturday' => 'lørdag',
'january' => 'januar',
'february' => 'februar',
'march' => 'marts',
'april' => 'april',
'may_long' => 'maj',
'june' => 'juni',
'july' => 'juli',
'august' => 'august',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'december',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'maj',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',


# Bits of text used by many pages:
#
"categories" => "Sidekategorier",
"category" => "kategori",
"category_header" => "Artikler i kategorien \"$1\"",
"subcategories" => "Underkategorier",

"linktrail"		=> "/^((?:[a-z]|æ|ø|å)+)(.*)\$/sD",
"mainpage"		=> "Forside",
"mainpagetext"	=> "Wiki-software er nu installeret.",
"about"			=> "Om",
"aboutsite"      => "Om Wikipedia",
"aboutpage"		=> "Wikipedia:Om",
"help"			=> "Hjælp",
"helppage"		=> "Wikipedia:Hjælp",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Fejlrapporter",
"bugreportspage" => "Wikipedia:Fejlrapporter",
"sitesupport"   => "Donation",
"sitesupportpage" => "Wikipedia:Donation", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "OSS",
"faqpage"		=> "Wikipedia:OSS",
"edithelp"		=> "Hjælp til redigering",
"edithelppage"	=> "Wikipedia:Hvordan_redigerer_jeg_en_side",
"cancel"		=> "Afbryd",
"qbfind"		=> "Find",
"qbbrowse"		=> "Gennemse",
"qbedit"		=> "Redigér",
"qbpageoptions" => "Indstillinger for side",
"qbpageinfo"	=> "Information om side",
"qbmyoptions"	=> "Mine indstillinger",
"mypage"		=> "Min side",
"mytalk"		=> "Min diskussion",
"currentevents" => "Aktuelle begivenheder",
"errorpagetitle" => "Fejl",
"returnto"		=> "Tilbage til $1.",
"tagline"      	=> "Fra Wikipedia, den frie encyklopædi",
"whatlinkshere"	=> "Sider med en henvisning hertil",
"help"			=> "Hjælp",
"search"		=> "Søg",
"go"		=> "Udfør",
"history"		=> "Historie",
"printableversion" => "Printervenlig version",
"editthispage"	=> "Redigér side",
"deletethispage" => "Slet side",
"protectthispage" => "Beskyt side",
"unprotectthispage" => "Fjern beskyttelse af side",
"newpage" => "Ny side",
"talkpage"		=> "Diskussionssiden",
"postcomment"   => "Tilføj en kommentar",
"articlepage"	=> "Se artiklen",
"subjectpage"	=> "Se emnesiden",
"userpage" => "Se brugersiden",
"wikipediapage" => "Se metasiden",
"imagepage" => 	"Se billedsiden",
"viewtalkpage" => "Se diskussion",
"otherlanguages" => "Andre sprog",
"redirectedfrom" => "(Omdirigeret fra $1)",
"lastmodified"	=> "Sidst ændret den $1.",
"viewcount"		=> "Siden er vist i alt $1 gange.",
"gnunote" => "Denne side er udgivet under <a class=internal href='$wgScriptPath/GNU_FDL'>GNU FDL</a>.",
"printsubtitle" => "(Fra http://da.wikipedia.org)",
"protectedpage" => "Beskyttet side",
"administrators" => "Wikipedia:Administratorer",
"sysoptitle"	=> "Sysop-adgang påkrævet",
"sysoptext"		=> "Den funktion du har bedt om kan kun
udføres af brugere med \"sysop\"-status. Se $1.",
"developertitle" => "Developer-adgang påkrævet",
"developertext"	=> "Den funktion du har bedt om, kan kun
udføres af brugere med \"developer\"-status. Se $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Udfør",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Den frie encyklopædi",
"retrievedfrom" => "Hentet fra \"$1\"",
"newmessages" => "Du har $1.",
"newmessageslink" => "nye beskeder",
"editsection"=>"redigér",
"toc" => "Indholdsfortegnelse",
"showtoc" => "vis",
"hidetoc" => "skjul",
"thisisdeleted" => "Se eller gendan $1?",
"restorelink" => "$1 slettede ændringer",

# Main script and global functions
#
"nosuchaction"	=> "Funktionen findes ikke",
"nosuchactiontext" => "Funktion specificeret i URL'en kan ikke
genkendes af Wikipediasoftwaren",
"nosuchspecialpage" => "En sådan specialside findes ikke",
"nospecialpagetext" => "Du har bedt om en specialside, der ikke
kan genkendes af Wikipediasoftwaren.",

# General errors
#
"error"			=> "Fejl",
"databaseerror" => "Databasefejl",
"dberrortext"	=> "Der er sket en syntaksfejl i en databaseforespørgsel.
Den sidst forsøgte databaseforespørgsel var:
<blockquote><tt>$1</tt></blockquote>
fra funktionen \"<tt>$2</tt>\".
MySQL returnerede fejlen \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Der er sket en syntaksfejl i en databaseforespørgsel.
Den sidst forsøgte databaseforespørgsel var:
\"$1\"
fra funktionen \"$2\".
MySQL returnerede fejlen \"$3: $4\".\n",
"noconnect"		=> "Kunne ikke forbinde til databasen på $1",
"nodb"			=> "Kunne ikke vælge databasen $1",
"cachederror"	=> "Det følgende er en gemt kopi af den ønskede side, og er måske ikke helt opdateret.",
"readonly"		=> "Databasen er skrivebeskyttet",
"enterlockreason" => "Skriv en begrundelse for skrivebeskyttelsen, inklusive 
et estimat på hvornår skrivebeskyttelsen vil blive ophævet igen",
"readonlytext"	=> "Wikipediadatabasen er for øjeblikket skrivebeskyttet for 
nye sider og andre modifikationer, sandsynligvis på grund af rutinemæssig databasevedligeholdelse, hvorefter den vil returnere til normaldrift.
Den administrator der skrivebeskyttede den har denne forklaring:
<p>$1",
"missingarticle" => "Databasen fandt ikke teksten på en side,
som den skulle have fundet, med navnet \"$1\".

<p>Dette er ikke en databasefejl, men sandsynligvis en fejl i softwaren.

<p>Send venligst en rapport om dette til en administrator, 
hvor du også nævner URL'en.",
"internalerror" => "Intern fejl",
"filecopyerror" => "Kunne ikke kopiere filen \"$1\" til \"$2\".",
"filerenameerror" => "Kunne ikke omdøbe filen \"$1\" til \"$2\".",
"filedeleteerror" => "Kunne ikke slette filen \"$1\".",
"filenotfound"	=> "Kunne ikke finde filen \"$1\".",
"unexpected"	=> "Uventet værdi: \"$1\"=\"$2\".",
"formerror"		=> "Fejl: kunne ikke afsende form",
"badarticleerror" => "Denne funktion kan ikke udføres på denne side.",
"cannotdelete"	=> "Kunne ikke slette siden eller filen der blev 
specificeret.",
"badtitle"		=> "Forkert titel",
"badtitletext"	=> "Den ønskede sides titel var ikke tilladt, tom eller siden
er forkert henvist fra en Wikipedia på et andet sprog.",
"perfdisabled" => "Desværre! Denne funktion er midlertidigt afbrudt, 
fordi den belaster databasen meget hårdt og i en sådan grad, at siden 
bliver meget langsom. Funktionen bliver forhåbentlig omskrevet i den 
nærmeste fremtid (måske af dig, det er jo open source!!).",
"perfdisabledsub" => "Her er en gemt kopi fra $1:",

# Login and logout pages
#
"logouttitle"	=> "Bruger-log-af",
"logouttext"	=> "Du er nu logget af.
Du kan fortsætte med at bruge Wikipedia anonymt, eller du kan logge på
igen som den samme eller en anden bruger.\n",

"welcomecreation" => "<h2>Velkommen, $1!</h2><p>Din konto er blevet 
oprettet. Glem ikke at personliggøre dine Wikipedia-indstillinger.",

"loginpagetitle" => "Bruger log på",
"yourname"		=> "Dit brugernavn",
"yourpassword"	=> "Din adgangskode",
"yourpasswordagain" => "Gentag adgangskode",
"newusersonly"	=> " (kun nye brugere)",
"remembermypassword" => "Husk min adgangskode til næste gang.",
"loginproblem"	=> "<b>Der har været et problem med at få dig logget  
på.</b><br />Prøv igen!",
"alreadyloggedin" => "<font color=red><b>Bruger $1, du er allerede logget 
på!</b></font><br />\n",

"login"			=> "Log på",
"userlogin"		=> "Log på",
"logout"		=> "Log af",
"userlogout"	=> "Log af",
"notloggedin"	=> "Ikke logget på",
"createaccount"	=> "Opret en ny konto",
"badretype"		=> "De indtastede adgangskoder er ikke ens.",
"userexists"	=> "Det brugernavn du har valgt er allerede i brug. Vælg 
venligst et andet brugernavn.",
"youremail"		=> "Din e-mailadresse *",
"yournick"		=> "Dit kaldenavn (til signaturer)",
"emailforlost"	=> "* Det er valgfrit om du vil oplyse din e-mailadresse. 
Men det gør andre brugere i stand til at sende dig en e-mail, uden at 
du behøver offentliggøre din e-mailadresse. Samtidig gør det muligt, at du kan få en 
ny adgangskode sendt til din e-mailadresse.",
"loginerror"	=> "Fejl med at logge på",
"noname"		=> "Du har ikke angivet et gyldigt brugernavn.",
"loginsuccesstitle" => "Logget på med succes",
"loginsuccess"	=> "Du er nu logget på Wikipedia som \"$1\".",
"nosuchuser"	=> "Der er ingen bruger med navnet \"$1\".
Kontrollér stavemåden igen, eller brug formularen herunder til at oprette en ny brugerkonto.",
"wrongpassword"	=> "Den indtastede adgangskode var forkert. Prøv igen.",
"mailmypassword" => "Send mig en ny adgangskode til min e-mailadresse",
"passwordremindertitle" => "Ny adgangskode fra Wikipedia",
"passwordremindertext" => "Nogen (sandsynligvis dig, fra IP-adressen $1)
har bedt om at vi sender dig en ny adgangskode til at logge på Wikipedia.
Den nye adgangskode for bruger \"$2\" er nu \"$3\".
Du bør logge på nu og ændre din adgangskode.",
"noemail"		=> "Der er ikke oplyst nogen e-mailadresse for bruger \"$1\".",
"passwordsent"	=> "En ny adgangskode er sendt til e-mailadressen,
som er registreret for \"$1\".
Du bør logge på og ændre din adgangskode straks efter, du har modtaget den.",

# Edit pages
#
"summary"		=> "Beskrivelse",
"subject"		=> "Emne/overskrift",
"minoredit"		=> "Dette er en mindre ændring.",
"watchthis"		=> "Overvåg denne artikel",
"savearticle"	=> "Gem side",
"preview"		=> "Forhåndsvisning",
"showpreview"	=> "Forhåndsvisning",
"blockedtitle"	=> "Brugeren er blokeret",
"blockedtext"	=> "Dit brugernavn eller din IP-adresse er blevet blokeret af 
$1. Begrundelsen er denne:<br />$2<p>Du kan kontakte $1
eller en af de andre [[Wikipedia:Administratorer|administratorer]] for at diskutere blokeringen.

Din IP-adresse er $3.
Sørg venligst for at inkludere dette nummer i alle henvendelser til en administrator.
",
"newarticle"	=> "(Ny)",
"newarticletext" => "Der er på nuværende tidspunkt ingen tekst på denne side.<br />
Du kan begynde en artikel ved at skrive i boksen herunder. 
(se [[Wikipedia:Hjælp|hjælpen]] for yderligere information).<br />
Hvis det ikke var din mening, så tryk på '''Tilbage''' eller '''Back''' knappen.",
"anontalkpagetext" => "---- ''Dette er en diskussionsside for en anonym bruger der 
ikke har oprettet en konto endnu eller ikke bruger den. Vi er derfor nødt til at 
bruge den nummeriske [[IP-adresse]] til at identificere ham eller hende.
En IP-adresse kan være delt mellem flere brugere. Hvis du er en anonym bruger 
og syntes, at du har fået irrelevante kommentarer på sådan en side, så vær 
venlig, at oprette en brugerkonto og [[Speciel:Userlogin|logge på]], så vi undgår fremtidige 
forvekslinger med andre anonyme brugere.'' ",
"noarticletext" => "(Der er på nuværende tidspunkt ingen tekst på denne 
side)",
"updated"		=> "(Opdateret)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Husk at dette er kun en forhåndsvisning, siden er ikke 
gemt endnu!",
"previewconflict" => "Denne forhåndsvisning er resultatet af den 
redigérbare tekst ovenfor,
sådan vil det komme til at se ud hvis du vælger at gemme teksten.",
"editing"		=> "Redigerer $1",
"editingsection"	=> "Redigerer $1 (afsnit)",
"editingcomment"	=> "Redigerer $1 (kommentar)",
"editconflict"	=> "Redigeringskonflikt: $1",
"explainconflict" => "Nogen har ændret denne side, efter du
startede på at redigere den.
Den øverste tekstboks indeholder den nuværende tekst.
Dine ændringer er vist i den nederste tekstboks.
Du er nødt til at sammenflette dine ændringer med den eksisterende tekst.
<b>Kun</b> teksten i den øverste tekstboks vil blive gemt når du
trykker \"Gem side\".<br />",
"yourtext"		=> "Din tekst",
"storedversion" => "Den gemte version",
"editingold"	=> "<strong>ADVARSEL: Du redigerer en gammel version
af denne side.
Hvis du gemmer den, vil alle ændringer foretaget siden denne revision blive 
overskrevet.</strong>",
"yourdiff"		=> "Forskelle",
"copyrightwarning" => "Læg mærke til at alle bidrag til Wikipedia er
at betragte som udgivet under GNU Free Documentation License
(se $1 for detaljer).
Hvis du ikke vil have din tekst redigeret uden nåde og kopieret efter
forgodtbefindene, så skal du ikke lægge det her.<br />
Du lover os også, at du skrev teksten selv, kopierede fra en
public domain eller lignende fri ressource.
<strong>LÆG ALDRIG MATERIALE HER SOM ER BESKYTTET AF ANDRES OPHAVSRET UDEN 
DERES TILLADELSE!</strong>",
"longpagewarning" => "<strong>ADVARSEL: Denne side er $1 kilobytes lang; nogle
browsere kan have problemer med at redigerer sider der nærmer sig eller 
er længere end 32kb. Overvej om ikke siden kan deles op i mindre dele.</strong>",
"readonlywarning" => "<strong>ADVARSEL: Databasen er låst på grund af vedligeholdelse,
så du kan ikke gemme dine ændringer lige nu. Det kan godt være en god ide at 
kopiere din tekst til en tekstfil, så du kan gemme den til senere.</strong>",
"protectedpagewarning" => "<strong>ADVARSEL: Denne side er låst, så kun administratorer kan redigere den. Sørg for at du følger [[Project:Politik_for_beskyttede_sider|politiken for beskyttede sider]].</strong>",

# History pages
#
"revhistory"	=> "Versionshistorik",
"nohistory"		=> "Der er ingen versionshistorik for denne side.",
"revnotfound"	=> "Versionen er ikke fundet",
"revnotfoundtext" => "Den gamle version af den side du spurgte efter kan 
ikke findes. Kontrollér den URL du brugte til at få adgang til denne side.\n",
"loadhist"		=> "Indlæser sidens historik",
"currentrev"	=> "Nuværende version",
"revisionasof"	=> "Versionen fra $1",
"cur"			=> "nuværende",
"next"			=> "næste",
"last"			=> "forrige",
"orig"			=> "originale",
"histlegend"	=> "Forklaring: (nuværende) = forskel til den nuværende 
version, (forrige) = forskel til den forrige version, M = mindre ændring",

# Diffs
#
"difference"	=> "(Forskelle mellem versioner)",
"loadingrev"	=> "indlæser version for at se forskelle",
"lineno"		=> "Linje $1:",
"editcurrent"	=> "Redigér den nuværende version af denne side",

# Search results
#
"searchresults" => "Søgeresultater",
"searchresulttext" => "For mere information om søgning på {{SITENAME}}, se [[Project:Søgning|Søgning på {{SITENAME}}]].",
"searchquery"	=> "For forespørgsel \"$1\"",
"badquery"		=> "Forkert udformet forespørgsel",
"badquerytext"	=> "Vi kunne ikke udføre din forespørgsel.
Det er sandsynligvis fordi du har forsøgt at søge efter et ord med
færre end tre bogstaver, hvilket ikke understøttes endnu.
Det kan også være du har skrevet forkert, for
eksempel \"fisk og og skaldyr\".
Prøv en anden forespørgsel.",
"matchtotals"	=> "Forespørgslen \"$1\" matchede $2 artikeltitler
og teksten i $3 artikler.",
"nogomatch" => "Ingen sider med præcis denne titel eksisterer, prøver 
fuldtekstsøgning i stedet for. ",
"titlematches"	=> "Artikeltitler der matchede forespørgslen",
"notitlematches" => "Ingen artikeltitler matchede forespørgslen",
"textmatches"	=> "Artikeltekster der matchede forespørgslen",
"notextmatches"	=> "Ingen artikeltekster matchede forespørgslen",
"prevn"			=> "forrige $1",
"nextn"			=> "næste $1",
"viewprevnext"	=> "Vis ($1) ($2) ($3).",
"showingresults" => "Nedenfor vises <b>$1</b> resultater startende med 
nummer <b>$2</b>.",
"showingresultsnum" => "Herunder vises <b>$3</b> resultater startende med nummer <b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: søgning uden resultat skyldes, 
at man søger efter almindelige ord som \"har\" og \"fra\",
der ikke er indekseret, eller ved at specificere mere end et søgeord (da kun 
sider der indeholder alle søgeordene vil blive fundet).",
"powersearch" => "Søg",
"powersearchtext" => "
Søg i navnerum :<br />
$1<br />
$2 List omdirigeringer &nbsp; Søg efter $3 $9",
"searchdisabled" => "<p>Søgefunktionen er midlertidigt afbrudt på grund af
for stort pres på serveren; vi håber vi kan sætte den på igen når vi har
opgraderet softwaren. I mellemtiden kan du søge via google:</p>",
"blanknamespace" => "(Hoved)",

# Preferences page
#
"preferences"	=> "Indstillinger",
"prefsnologin" => "Ikke logget på",
"prefsnologintext"	=> "Du skal være [[Speciel:Userlogin|logget på]]
for at ændre brugerindstillinger.",
"prefslogintext" => "Du logget på som \"$1\".
Dit interne ID-nummer er $2.

Se [[Wikipedia:Hvordan sætter jeg mine indstillinger]] for en forklaring på de forskellige indstillinger.",
"prefsreset"	=> "Indstillingerne er blevet gendannet fra lageret.",
"qbsettings"	=> "Indstillinger for hurtigmenu",
"changepassword" => "Skift adgangskode",
"skin"			=> "Udseende",
"math"			=> "Vis matematiske formler",
"dateformat"	=> "Datoformat",
"math_failure"		=> "Fejl i matematikken",
"math_unknown_error"	=> "ukendt fejl",
"math_unknown_function"	=> "ukendt funktion ",
"math_lexing_error"	=> "lexerfejl",
"math_syntax_error"	=> "syntaxfejl",
"saveprefs"		=> "Gem indstillinger",
"resetprefs"	=> "Gendan indstillinger",
"oldpassword"	=> "Gammel adgangskode",
"newpassword"	=> "Ny adgangskode",
"retypenew"		=> "Gentag ny adgangskode",
"textboxsize"	=> "Tekstboks-størrelse",
"rows"			=> "Rækker",
"columns"		=> "Kolonner",
"searchresultshead" => "Indstillinger for søgeresultater",
"resultsperpage" => "Resultater pr. side",
"contextlines"	=> "Linjer pr. resultat",
"contextchars"	=> "Tegn pr. linje i resultatet",
"stubthreshold" => "Grænse for visning af stubs",
"recentchangescount" => "Antallet af titler på siden \"seneste ændringer\"",
"savedprefs"	=> "Dine indstillinger er blevet gemt.",
"timezonetext"	=> "Indtast antal timer din lokale tid er forskellig
fra serverens tid (UTC). Der bliver automatisk tilpasset til dansk tid, 
ellers skulle man for eksempel for dansk vintertid, indtaste \"1\" 
(og \"2\" når vi er på sommertid).",
"localtime"	=> "Lokaltid",
"timezoneoffset" => "Forskel",
"servertime"	=> "Serverens tid er nu",
"guesstimezone" => "Hent tidszone fra browseren",
"emailflag"	=> "Fravælg muligheden for at få e-mail fra andre brugere",
"defaultns"		=> "Søg som standard i disse navnerum:",

# Recent changes
#
"changes" => "ændringer",
"recentchanges" => "Seneste ændringer",
# This is the default text, and can be overriden by editing [[Wikipedia::Recentchanges]]
"recentchangestext" => "Se de senest ændrede sider i Wikipedia på denne side.",
"rcloaderr"		=> "Indlæser seneste ændrede sider",
"rcnote"		=> "Nedenfor er de seneste <strong>$1</strong> ændringer i de 
sidste <strong>$2</strong> dage.",
"rcnotefrom"	=> "Nedenfor er ændringerne fra <b>$2</b> indtil <b>$1</b> vist.",
"rclistfrom"	=> "Vis nye ændringer startende fra $1",
"rclinks"		=> "Vis seneste $1 ændringer i de sidste $2 dage; $3 mindre ændringer.",
"rchide"		=> "i $4 form; $1 mindre ændringer; $2 andre navnerum; $3 mere end en redigering.",
"rcliu"			=> "; $1 redigeringer fra brugere der er logget på",
"diff"			=> "forskel",
"hist"			=> "historik",
"hide"			=> "skjul",
"show"			=> "vis",
"tableform"		=> "tabel",
"listform"		=> "liste",
"nchanges"		=> "$1 ændringer",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Læg en fil op",
"uploadbtn"		=> "Læg en fil op",
"uploadlink"	=> "Læg en fil op",
"reupload"		=> "Læg en fil op igen",
"reuploaddesc"	=> "Tilbage til formularen til at lægge filer op.",
"uploadnologin" => "Ikke logget på",
"uploadnologintext"	=> "Du skal være [[Speciel:Userlogin|logget på]]
for at kunne lægge filer op.",
"uploaderror"	=> "Fejl under oplægning af fil",
"uploadtext"	=> "'''STOP!''' Før du lægger filer op her,
så vær sikker på du har læst og følger Wikipedias
[[Project:Politik om brug af billeder|politik om brug af billeder]].

For at se eller søge i billeder, som tidligere er lagt op,
gå til [[Speciel:Imagelist|listen over billeder]].
Oplægning og sletninger er registreret i
[[Project:Upload_log|log over oplagte filer]].

Brug formularen herunder til at lægge nye billeder op, der kan bruges
som illustration i dine artikler.
På de fleste browsere vil du se en \"Browse...\" knap eller en 
\"Gennemse...\" knap, som vil
bringe dig til dit styresystemets standard-dialog til åbning af filer.
Når du vælger en fil, vil navnet på filen dukke op i tekstfeltet
ved siden af knappen.
Du skal også verificere, at du ikke er ved at bryde nogens ophavsret.
Det gør du ved at sætte et mærke i checkboksen.
Tryk på \"Læg op\"-knappen for at lægge filen op.
Dette kan godt tage lidt tid hvis du har en langsom internetforbindelse.

De foretrukne formater er JPEG til fotografiske billeder, PNG
til tegninger og andre små billeder, og OGG til lyd.
Sørg for at navngive din fil med et beskrivende navn for at undgå 
forvirring om indholdet.
For at bruge billedet i en artikel, så brug et link af denne slags
'''<nowiki>[[billede:fil.jpg]]</nowiki>''' eller
'''<nowiki>[[billede:fil.png|alternativ tekst]]</nowiki>''' eller
'''<nowiki>[[media:fil.ogg]]</nowiki>''' for lyd.

Læg mærke til at præcis som med Wikipedia-sider, så kan og må andre gerne 
redigere eller
slette de filer, du har lagt op, hvis de mener det hjælper encyklopædien, og
du kan blive blokeret fra at lægge op hvis du misbruger systemet.",
"uploadlog"		=> "oplægningslog",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Herunder er der en liste med de filer, som er lagt 
op senest. Alle de viste tider er serverens tid (UTC).
",
"filename"		=> "Filnavn",
"filedesc"		=> "Beskrivelse",
"affirmation"	=> "Jeg bekræfter, at ophavsretshaveren til denne fil
er enig i, at filen udgives under betingelserne for $1.",
"copyrightpage" => "Wikipedia:Ophavsret",
"copyrightpagename" => "Wikipedia ophavsret",
"uploadedfiles"	=> "Filer som er lagt op",
"noaffirmation" => "Du skal bekræfte, at du ikke bryder nogens ophavsret
ved at lægge denne fil op.",
"ignorewarning"	=> "Ignorér advarslen og gem filen alligevel.",
"minlength"		=> "Navnet på filen skal være på mindst tre bogstaver.",
"badfilename"	=> "Navnet på filen er blevet ændret til \"$1\".",
"badfiletype"	=> "\".$1\" er ikke et af de anbefalede filformater.",
"largefile"		=> "Det anbefales, at filer ikke fylder mere end 100kb.",
"successfulupload" => "Oplægning er gennemført med success",
"fileuploaded"	=> "Filen \"$1\" er lagt op med success.
Følg dette link: ($2) til siden med beskrivelse og udfyld
information omkring filen, såsom hvor den kom fra, hvornår den er lavet
og af hvem, og andre ting du ved om filen.",
"uploadwarning" => "Oplægningsadvarsel",
"savefile"		=> "Gem fil",
"uploadedimage" => "Lagde \"[[$1]]\" op",
"uploaddisabled" => "Desværre er funktionen til at lægge billeder op afbrudt på denne server.",
'uploadscripted' => 'Denne fil indeholder HTML eller script-kode, der i visse tilfælde can fejlfortolkes af en browser.',
'uploadcorrupt' => 'Denne fil er beskadiget eller forsynet med en forkert endelse. Kontroller venligst filen og prøv at lægge den op igen.',
'uploadvirus' => 'Denne fil indeholder en virus! Virusnavn: $1',

# Image list
#
"imagelist"		=> "Billedliste",
"imagelisttext"	=> "Herunder er en liste med $1 billeder sorteret $2.",
"getimagelist"	=> "henter billedliste",
"ilsubmit"		=> "Søg",
"showlast"		=> "Vis de sidste $1 billeder sorteret $2.",
"byname"		=> "efter navn",
"bydate"		=> "efter dato",
"bysize"		=> "efter størrelse",
"imgdelete"		=> "slet",
"imgdesc"		=> "beskrivelse",
"imglegend"		=> "Forklaring: (beskrivelse) = vis/redigér billedebeskrivelse.",
"imghistory"	=> "Billedhistorik",
"revertimg"		=> "gendan",
"deleteimg"		=> "slet",
"deleteimgcompletely"		=> "slet",
"imghistlegend" => "Forklaring: (nuværende) = dette er det nuværende billede, 
(slet) = slet denne gamle version, (gendan) = gendan en gammel version.
<br /><i>Klik på en dato for at se billedet, som er lagt op den dag</i>.",
"imagelinks"	=> "Billedehenvisninger",
"linkstoimage"	=> "De følgende sider henviser til dette billede:",
"nolinkstoimage" => "Der er ingen sider der henviser til dette billede.",

# Statistics
#
"statistics"	=> "Statistik",
"sitestats"		=> "Side-statistik",
"userstats"		=> "Bruger-statistik",
"sitestatstext" => "Der er i alt <b>$1</b> sider i databasen.
Dette er inklusiv \"diskussion\"-sider, sider om Wikipedia, 
omdirigeringssider, og andre der sikkert ikke kan 
kvalificeres som artikler.
Hvis man ekskludere disse, så er der <b>$2</b> sider som sandsynligvis er 
rigtige artikler.<p>
Der har ialt været <b>$3</b> viste sider, og <b>$4</b> redigeringer af sider
siden softwaren blev opdateret (25. september 2002).
Det vil sige, der har været <b>$5</b> gennemsnitlige redigeringer pr. side, 
og <b>$6</b> visninger pr. redigering.",
"userstatstext" => "Der er  <b>$1</b> registrerede brugere.
<b>$2</b> af disse er administratorer (se $3).",

# Maintenance Page
#
"maintenance"		=> "Vedligeholdelsesside",
"maintnancepagetext"	=> "På denne side er der forskellige smarte 
værktøjer til at vedligeholde Wikipedia. Nogle af disse funktioner er ret 
hårde for databasen (de tager lang tid), så lad være med at opdatere siden 
hver gang du har rettet en enkelt ting ;-)",
"maintenancebacklink"	=> "Tilbage til vedligeholdelsessiden",
"disambiguations"	=> "Artikler med flertydige titler",
"disambiguationspage"	=> "Wikipedia:Henvisninger til artikler med flertydige titler",
"disambiguationstext"	=> "De følgende artikler henviser til 
<i>artikler med flertydige titler</i>. De skulle henvise til en ikke-flertydig 
titel i stedet for.<br />En artikel bliver behandlet som flertydig, hvis den er
henvist fra $1.<br />Henvisninger fra andre navnerum er <i>ikke</i> listet her.",
"doubleredirects"	=> "Dobbelte omdirigeringer",
"doubleredirectstext"	=> "<b>Bemærk:</b> Denne liste kan indeholde forkerte 
resultater. Det er som regel, fordi siden indeholder ekstra tekst under den
første #REDIRECT.<br />\nHver linje indeholder henvisninger til den første og den 
anden omdirigering, og den første linje fra den anden omdirigeringstekst, 
det giver som regel den \"rigtige\" målartikel, som den første omdirigering 
skulle have peget på.",
"brokenredirects"	=> "Dårlige omdirigeringer",
"brokenredirectstext"	=> "De følgende omdirigeringer peger på en side der 
ikke eksisterer.",
"selflinks"		=> "Sider der henviser til sig selv",
"selflinkstext"		=> "De følgende sider indeholder henvisninger til sig selv, 
men det burde de ikke.",
"mispeelings"           => "Sider med stavefejl",
"mispeelingstext"               => "De følgende sider indeholder en af de 
almindelig stavefejl, som er listet på $1. Den korrekte stavemåde kan 
angives i paranteser efter den fejlagtige stavemåde (sådan her).",
"mispeelingspage"       => "Liste af almindelige stavefejl",
"missinglanguagelinks"  => "Manglende sproghenvisninger",
"missinglanguagelinksbutton"    => "Find manglende sproghenvisninger for",
"missinglanguagelinkstext"      => "Disse artikler har <i>ikke</i> nogen 
henvisning til den samme artikel i $1. Omdirigeringer og underartikler er 
<i>ikke</i> vist.",


# Miscellaneous special pages
#
"orphans"		=> "Forældreløse artikler",
"lonelypages"	=> "Forældreløse artikler",
"unusedimages"	=> "Ubrugte billeder",
"popularpages"	=> "Populære artikler",
"nviews"		=> "$1 visninger",
"wantedpages"	=> "Ønskede artikler",
"nlinks"		=> "$1 henvisninger",
"allpages"		=> "Alle artikler",
"randompage"	=> "Tilfældig artikel",
"shortpages"	=> "Korte artikler",
"longpages"		=> "Lange artikler",
"listusers"		=> "Brugerliste",
"specialpages"	=> "Specielle sider",
"spheading"		=> "Specielle sider for alle brugere",
"sysopspheading" => "Specielle sider til sysop-brug",
"developerspheading" => "Specielle sider til developer-brug",
"protectpage"	=> "Beskyt side",
"recentchangeslinked" => "Relaterede ændringer",
"rclsub"		=> "(til sider henvist fra \"$1\")",
"debug"			=> "Aflus",
"newpages"		=> "Nyeste artikler",
"ancientpages"		=> "Ældste artikler",
"intl"		=> "Sproghenvisninger",
"movethispage"	=> "Flyt side",
"unusedimagestext" => "<p>Læg mærke til, at andre websider
såsom de andre internationale Wikipediaer måske henviser til et billede med
en direkte URL, så det kan stadig være listet her, selvom det er
i aktivt brug.</p>",
"booksources"	=> "Bogkilder",
"booksourcetext" => "Herunder er en liste af henvisninger til steder der
udlåner og/eller sælger nye og brugte bøger, og som måske også har 
yderligere information om bøger du leder efter.
Wikipedia er ikke associeret med nogen af disse steder,
og denne liste skal ikke ses som en anbefaling af disse.",
"alphaindexline" => "$1 til $2",

# Email this user
#
"mailnologin"	=> "Ingen afsenderadresse",
"mailnologintext" => "Du skal være [[Speciel:Userlogin|logget på]]
og have en gyldig e-mailadresse sat i dine [[Speciel:Preferences|indstillinger]]
for at sende e-mail til andre brugere.",
"emailuser"		=> "E-mail til denne bruger",
"emailpage"		=> "E-mail bruger",
"emailpagetext"	=> "Hvis denne bruger har sat en gyldig e-mailadresse i
sine brugerindstillinger, så vil formularen herunder sende en enkelt 
besked.
Den e-mailadresse, du har sat i dine brugerindstillinger, vil dukke op
i \"Fra\" feltet på denne mail, så modtageren er i stand til at svare.",
"noemailtitle"	=> "Ingen e-mailadresse",
"noemailtext"	=> "Denne bruger har ikke angivet en gyldig e-mailadresse,
eller har valgt ikke at modtage e-mail fra andre brugere.",
"emailfrom"		=> "Fra",
"emailto"		=> "Til",
"emailsubject"	=> "Emne",
"emailmessage"	=> "Besked",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sendt",
"emailsenttext" => "Din e-mailbesked er blevet sendt.",

# Watchlist
#
"watchlist"		=> "Overvågningsliste",
"watchlistsub"	=> "(for bruger \"$1\")",
"nowatchlist"	=> "Du har ingenting i din overvågningsliste.",
"watchnologin"	=> "Ikke logget på",
"watchnologintext"	=> "Du skal være [[Speciel:Userlogin|logget på]]
for at kunne ændre din overvågningsliste.",
"addedwatch"	=> "Tilføjet til din overvågningsliste",
"addedwatchtext" => "Siden \"$1\" er blevet tilføjet til din <a href=\"" .
  "{{localurle:Speciel:Watchlist}}\">overvågningsliste</a>.
Fremtidige ændringer til denne side og den tilhørende diskussionsside vil 
blive listet her, og siden vil fremstå <b>fremhævet</b> i <a href=\"" .
  "{{localurle:Speciel:Recentchanges}}\">listen med de seneste 
ændringer</a> for at gøre det lettere at finde den.</p>

<p>Hvis du senere vil fjerne siden fra din overvågningsliste, så klik 
\"Fjern overvågning\" ude i siden.",
"removedwatch"	=> "Fjernet fra overvågningsliste",
"removedwatchtext" => "Siden \"$1\" er blevet fjernet fra din 
overvågningsliste.",
"watchthispage"	=> "Overvåg side",
"unwatchthispage" => "Fjern overvågning",
"notanarticle"	=> "Ikke en artikel",
"watchnochange" => "Ingen af siderne i din overvågningsliste er ændret i den valgte periode.",
"watchdetails" => "($1 sider i din overvågningsliste, fratrukket alle diskussionssiderne;
$2 totalt antal sider ændret i den valgte periode;
$3...
<a href='$4'>vis og redigér den komplette liste</a>.)",
"watchmethod-recent" => "tjekker seneste ændringer for sider i din overvågningsliste",
"watchmethod-list" => "tjekker seneste ændringer for sider i din overvågningsliste",
"removechecked" => "Fjern valgte sider fra min overvågningsliste",
"watchlistcontains" => "Din overvågningsliste indeholder $1 sider.",
"watcheditlist" => "Her er en alfabetisk liste over siderne i din overvågningsliste.
Vælg de sider du vil fjerne fra din overvågningsliste 
og klik på 'fjern valgte sider fra min overvågningsliste' knappen
i bunden af skærmen.",
"removingchecked" => "Fjerner de valgte sider fra din overvågningsliste...",
"couldntremove" => "Kunne ikke fjerne '$1'...",
"iteminvalidname" => "Problem med '$1', ugyldigt navn...",
"wlnote" => "Nedenfor er de seneste $1 ændringer i de sidste <b>$2</b> timer.",


# Delete/protect/revert
#
"deletepage"	=> "Slet side",
"confirm"		=> "Bekræft",
"excontent" => "indholdet var: '$1'",
"excontentauthor" => "'$2' -- indholdet var: '$1'",
"exbeforeblank" => "indholdet før siden blev tømt var: '$1'",
"exblank" => "siden var tom",
"confirmdelete" => "Bekræft sletning",
"deletesub"		=> "(Sletter \"$1\")",
"historywarning" => "Advarsel: Siden du er ved at slette har en historie: ",
"confirmdeletetext" => "Du er ved permanent at slette en side
eller et billede sammen med hele den tilhørende historie fra databasen.
Bekræft venligst at du virkelig vil gøre dette, at du forstår
konsekvenserne, og at du gør dette i overensstemmelse med
[[Wikipedia:Politik]].",
"actioncomplete" => "Gennemført",
"deletedtext"	=> "\"$1\" er slettet.
Se $2 for en fortegnelse over de nyeste sletninger.",
"deletedarticle" => "slettet \"$1\"",
"dellogpage"	=> "Sletningslog",
"dellogpagetext" => "Herunder er en liste over de nyeste sletninger.
Alle tider er serverens tid (UTC).
",
"deletionlog"	=> "sletningslog",
"reverted"		=> "Gendannet en tidligere version",
"deletecomment"	=> "Begrundelse for sletning",
"imagereverted" => "Gendannelse af en tidligere version gennemført med 
success.",
"rollback"		=> "Fjern redigeringer",
"rollbacklink"	=> "fjern redigering",
"rollbackfailed" => "Kunne ikke fjerne redigeringen",
"cantrollback"	=> "Kan ikke fjerne redigering; 
den sidste bruger er den eneste forfatter.",
"alreadyrolled"	=> "Kan ikke fjerne den seneste redigering af [[$1]]
foretaget af [[Bruger:$2|$2]] ([[Bruger diskussion:$2|diskussion]]); 
en anden har allerede redigeret siden eller fjernet redigeringen. 

Den seneste redigering er foretaget af [[Bruger:$3|$3]] ([[Bruger diskussion:$3|diskussion]]). ",
#   only shown if there is an edit comment
"editcomment" => "Kommentaren til redigeringen var: \"<i>$1</i>\".", 
"revertpage"	=> "Gendannet siden til tidligere version redigeret af $1",
"protectlogpage" => "Liste_over_beskyttede_sider",
"protectlogtext" => "Herunder er en liste over sider der er blevet beskyttet/har fået fjernet beskyttelsen.
Se [[Wikipedia:Beskyttet side]] for mere information.",
"protectedarticle" => "beskyttet [[$1]]",
"unprotectedarticle" => "fjernet beskyttelse [[$1]]",

# Undelete
"undelete" => "Gendan en slettet side",
"undeletepage" => "Se og gendan slettede sider",
"undeletepagetext" => "De følgende sider er slettede, men de findes 
stadig i arkivet og kan gendannes. Arkivet blivet periodevis slettet.",
"undeletearticle" => "Gendan slettet artikel",
"undeleterevisions" => "$1 revisioner arkiveret",
"undeletehistory" => "Hvis du gendanner siden, vil alle de historiske 
revisioner også blive gendannet. Hvis en ny side med det samme navn 
er oprettet siden denne blev slettet, så vil de gendannede revisioner 
dukke op i den tidligere historie, og den nyeste revision vil forblive 
på siden.",
"undeleterevision" => "Slettet version fra $1",
"undeletebtn" => "Gendan!",
"undeletedarticle" => "gendannet \"$1\"",
"undeletedtext"   => "Artiklen [[$1]] er blevet gendannet med succes.
Se [[Wikipedia:Sletningslog]] for en fortegnelse over nylige 
sletninger og gendannelser.",

# Contributions
#
"contributions"	=> "Brugerbidrag",
"mycontris" => "Mine bidrag",
"contribsub"	=> "For $1",
"nocontribs"	=> "Ingen ændringer er fundet som matcher disse kriterier.",
"ucnote"	=> "Herunder er denne brugers sidste <b>$1</b> ændringer i de 
sidste <b>$2</b> dage.",
"uclinks"	=> "Vis de sidste $1 ændringer; vis de sidste $2 dage.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Hvad henviser hertil",
"notargettitle" => "Intet mål",
"notargettext"	=> "Du har ikke specificeret en målside eller bruger
at udføre denne funktion på.",
"linklistsub"	=> "(Liste over henvisninger)",
"linkshere"	=> "De følgende sider henviser her til:",
"nolinkshere"	=> "Ingen sider henviser her til.",
"isredirect"	=> "omdirigeringsside",

# Block/unblock IP
#
"blockip"		=> "Bloker bruger",
"blockiptext"	=> "Brug formularen herunder til at blokere for skriveadgangen
fra en specifik IP-adresse eller et brugernavn.
Dette må kun gøres for at forhindre vandalisme, og i
overensstemmelse med [[Wikipedia:Politik|Wikipedia politik]].
Udfyld en speciel begrundelse herunder (for eksempel med et citat fra
sider der har været udsat for vandalisme).",
"ipaddress"		=> "IP-Adresse/brugernavn",
"ipbreason"		=> "Begrundelse",
"ipbsubmit"		=> "Bloker denne bruger",
"badipaddress"	=> "IP-adressen/brugernavnet er udformet forkert eller eksistere ikke.",
"noblockreason" => "Du skal angive en begrundelse for denne blokering.",
"blockipsuccesssub" => "Blokering udført med success",
"blockipsuccesstext" => "\"$1\" er blevet blokeret.
<br />Se [[Speciel:Ipblocklist|IP blokeringslisten]] for alle blokeringer.",
"unblockip"		=> "Ophæv blokering af bruger",
"unblockiptext"	=> "Brug formularen herunder for at gendanne skriveadgangen
for en tidligere blokeret IP-adresse eller bruger.",
"ipusubmit"		=> "Ophæv blokeringen af denne adresse",
"ipusuccess"	=> "\"$1\" har fået ophævet blokeringen",
"ipblocklist"	=> "Liste af blokerede IP-adresser og brugernavne",
"blocklistline"	=> "$1, $2 blokerede $3",
"blocklink"		=> "bloker",
"unblocklink"	=> "ophæv blokering",
"contribslink"	=> "bidrag",
"autoblocker"	=> "Automatisk blokeret fordi du deler IP-adresse med \"$1\". Begrundelse \"$2\".",

# Developer tools
#
"lockdb"		=> "Lås database",
"unlockdb"		=> "Lås database op",
"lockdbtext"	=> "At låse databasen vil afbryde alle brugere fra at kunne
redigere sider, ændre deres indstillinger, redigere deres 
overvågningsliste, og andre ting der kræver ændringer i databasen.
Bekræft venligst at du har til hensigt at gøre dette, og at du vil
låse databasen op, når din vedligeholdelse er overstået.",
"unlockdbtext"	=> "At låse databasen op vil gøre, at alle brugere igen 
kan redigere sider, ændre deres indstillinger, redigere deres 
overvågningsliste, og andre ting der kræver ændringer i databasen.
Bekræft venligst at du har til hensigt at gøre dette.",
"lockconfirm"	=> "Ja, jeg vil virkelig låse databasen.",
"unlockconfirm"	=> "Ja, jeg vil virkelig låse databasen op.",
"lockbtn"		=> "Lås databasen",
"unlockbtn"		=> "Lås databasen op",
"locknoconfirm" => "Du har ikke bekræftet handlingen.",
"lockdbsuccesssub" => "Databasen er nu låst",
"unlockdbsuccesssub" => "Databasen er nu låst op",
"lockdbsuccesstext" => "Wikipediadatabasen er låst.
<br />Husk at fjerne låsen når du er færdig med din vedligeholdelse.",
"unlockdbsuccesstext" => "Wikipediadatabasen er låst op.",

# SQL query
#
"asksql"		=> "SQL-forespørgsel",
"asksqltext"	=> "Brug formularen herunder til at lave direkte forespørgsler 
i Wikipediadatabasen.
Brug enkelte anførselstegn ('sådan her') for at adskille strenge.
Dette kan ofte belaste serveren kraftigt, så brug venligst denne funktion
med omtanke.",
"sqlislogged"	=> "Vær opmærksom på at alle SQL-forespørgsler gemmes i en logfil.",
"sqlquery"		=> "Indtast forespørgsel",
"querybtn"		=> "Afsend forespørgsel",
"selectonly"	=> "Forespørgsler andre end \"SELECT\" er forbeholdt 
Wikipediaudviklere.",
"querysuccessful" => "Forespørgsel gennemført med success",

# Move page
#
"movepage"		=> "Flyt side",
"movepagetext"	=> "Når du bruger formularen herunder vil du få omdøbt en 
side og flyttet hele sidens historie til det nye navn.
Den gamle titel vil blive en omdirigeringsside til den nye titel.
Henvisninger til den gamle titel vil ikke blive ændret. Sørg for at 
tjekke for dobbelte eller dårlige omdirigeringer. 
Du er ansvarlig for, at alle henvisninger stadig peger derhen, hvor det er 
meningen de skal pege.

Bemærk at siden '''ikke''' kan flyttes hvis der allerede er en side 
med den nye titel, medmindre den side er tom eller er en omdirigering 
uden nogen historie. Det betyder at du kan flytte en side tilbage hvor 
den kom fra, hvis du kommer til at lave en fejl.

<b>ADVARSEL!</b>
Dette kan være en drastisk og uventet ændring for en populær side;
vær sikker på, at du forstår konsekvenserne af dette før du
fortsætter.",
"movepagetalktext" => "Den tilhørende diskussionsside, hvis der er en, 
vil automatisk blive flyttet med siden '''medmindre:'''
*Du flytter siden til et andet navnerum,
*En ikke-tom diskussionsside allerede eksisterer under det nye navn, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfælde er du nødt til at flytte eller sammenflette siden manuelt.",
"movearticle"	=> "Flyt side",
"movenologin"	=> "Ikke logget på",
"movenologintext" => "Du skal være registreret bruger og være [[Speciel:Userlogin|logget på]]
for at flytte en side.",
"newtitle"		=> "Til ny titel",
"movepagebtn"	=> "Flyt side",
"pagemovedsub"	=> "Flytning gennemført",
"pagemovedtext" => "Siden \"[[$1]]\" er flyttet til \"[[$2]]\".",
"articleexists" => "En side med det navn eksisterer allerede, eller det
navn du har valgt er ikke gyldigt. Vælg et andet navn.",
"talkexists"	=> "Siden blev flyttet korrekt, men den tilhørende 
diskussionsside kunne ikke flyttes, fordi der allerede eksisterer en 
med den nye titel. Du er nødt til at flette dem sammen manuelt.",
"movedto"		=> "flyttet til",
"movetalk"		=> "Flyt også \"diskussion\"ssiden, hvis den eksisterer.",
"talkpagemoved" => "Den tilhørende diskussionsside blev også flyttet.",
"talkpagenotmoved" => "Den tilhørende diskussionsside blev 
<strong>ikke</strong> flyttet.",
# Math
	'mw_math_png' => "Vis altid som PNG",
	'mw_math_simple' => "HTML hvis meget simpel ellers PNG",
	'mw_math_html' => "HTML hvis muligt ellers PNG",
	'mw_math_source' => "Lad være som TeX (for tekstbrowsere)",
        'mw_math_modern' => "Anbefalet til moderne browsere",
	'mw_math_mathml' => "MathML hvis muligt",

# Media Warning
'mediawarning' => "'''Advarsel''', denne filtype kan muligvis indeholde skadelig kode, du kan beskadige dit system hvis du udf�rer den.",
);

class LanguageDa extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListDa ;
		return $wgBookstoreListDa ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesDa;
		return $wgNamespaceNamesDa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesDa;
		return $wgNamespaceNamesDa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesDa;

		foreach ( $wgNamespaceNamesDa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsDa;
		return $wgQuickbarSettingsDa;
	}

	function getSkinNames() {
		global $wgSkinNamesDa;
		return $wgSkinNamesDa;
	}

	function getDateFormats() {
		global $wgDateFormatsDa;
		return $wgDateFormatsDa;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " kl. " . $this->time( $ts, $adj );
	}

	function getValidSpecialPages() {
		global $wgValidSpecialPagesDa;
		return $wgValidSpecialPagesDa;
	}

	function getSysopSpecialPages() {
		global $wgSysopSpecialPagesDa;
		return $wgSysopSpecialPagesDa;
	}

	function getDeveloperSpecialPages() {
		global $wgDeveloperSpecialPagesDa;
		return $wgDeveloperSpecialPagesDa;
	}

	function getMessage( $key ) {
		global $wgAllMessagesDa;
		if( isset( $wgAllMessagesDa[$key] ) ) {
			return $wgAllMessagesDa[$key];
		} else {
			return parent:getMessage( $key );
		}
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, '.,', ',.' ) : $number;
	}

}

?>
