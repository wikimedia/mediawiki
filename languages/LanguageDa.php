<?

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesDa = array(
	-2	=> "Media",
	-1	=> "Speciel",
	0	=> "",
	1	=> "Diskussion",
	2	=> "Bruger",
	3	=> "Bruger_diskussion",
	4	=> "Wikipedia",
	5	=> "Wikipedia_diskussion",
	6	=> "Billede",
	7	=> "Billede_diskussion",
	8	=> "MediaWiki",
	9	=> "MediaWiki_diskussion"
);

/* private */ $wgQuickbarSettingsDa = array(
	"Ingen", "Fast venstre", "Fast h�jre", "Flydende venstre"
);

/* private */ $wgSkinNamesDa = array(
	"Standard", "Nostalgi", "Cologne-bl�", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesDa = array(
	"Vis altid som PNG",
	"HTML hvis meget simpel ellers PNG",
	"HTML hvis muligt ellers PNG",
	"Lad v�re som TeX (for tekstbrowsere)",
    "Anbefalet til moderne browsere"
);

/* private */ $wgDateFormatsDa = array(
	"Ingen foretrukken",
	"januar 15, 2001",
	"15. januar 2001",
	"2001 januar 15",
	"2001-01-15"
);

/* private */ $wgUserTogglesDa = array(
	"hover"		=> "Vis sv�vende tekst over wikihenvisninger",
	"underline" => "Understreg henvisninger",
	"highlightbroken" => "Brug r�de henvisninger til tomme sider",
	"justify"	=> "Just�r afsnit",
	"hideminor" => "Skjul mindre �ndringer i seneste �ndringer listen",
	"usenewrc" => "Udvidet seneste �ndringer liste<br>(ikke for alle browsere)",
	"numberheadings" => "Automatisk nummerering af overskrifter",
	"showtoolbar" => "Vis v�rkt�jslinje til redigering",
	"editondblclick" => "Redig�r sider med dobbeltklik (JavaScript)",
	"editsection"=>"Redig�r afsnit ved hj�lp af [redig�r]-henvisning",
	"editsectiononrightclick"=>"Redig�r afsnit ved at h�jreklikke<br> p� afsnittets titel (JavaScript)",
 	"showtoc"=>"Vis indholdsfortegnelse<br>(for artikler med mere end tre afsnit)",
	"rememberpassword" => "Husk adgangskode til n�ste bes�g",
	"editwidth" => "Redigeringsboksen har fuld bredde",
	"watchdefault" => "Overv�g nye og �ndrede artikler",
	"minordefault" => "Mark�r som standard alle �ndringer som mindre",
	"previewontop" => "Vis forh�ndsvisning f�r redigeringsboksen",
	"nocache" => "Husk ikke siderne til n�ste bes�g"
);

/* private */ $wgBookstoreListDa = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesDa = array(
	"s�ndag", "mandag", "tirsdag", "onsdag", "torsdag",
	"fredag", "l�rdag"
);

/* private */ $wgMonthNamesDa = array(
	"januar", "februar", "marts", "april", "maj", "juni",
	"juli", "august", "september", "oktober", "november",
	"december"
);

/* private */ $wgMonthAbbreviationsDa = array(
	"jan", "feb", "mar", "apr", "maj", "jun", "jul", "aug",
	"sep", "okt", "nov", "dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesDa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Mine brugerindstillinger",
	"Watchlist"	=> "Min overv�gningsliste",
	"Recentchanges" => "Seneste �ndringer",
	"Upload"	=> "L�g filer op",
	"Imagelist"	=> "Billedliste",
	"Listusers"	=> "Registrerede brugere",
	"Statistics"	=> "Statistik om siden",
	"Randompage"	=> "Tilf�ldig artikel",

	"Lonelypages"	=> "For�ldrel�se artikler",
	"Unusedimages"	=> "For�ldrel�se filer",
#	"Popularpages"	=> "Popul�re artikler",
	"Wantedpages"	=> "Mest �nskede artikler",
	"Shortpages"	=> "Korte artikler",
	"Longpages"		=> "Lange artikler",
	"Newpages"		=> "Nyeste artikler",
	"Ancientpages"	=> "�ldste artikler",
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
#	"Categories"	=> "Sidekategorier",
	"Export"	=> "Eksport�r sider i XML format",
	"Version"	=> "Vis MediaWiki version",
);

/* private */ $wgSysopSpecialPagesDa = array(
	"Blockip"		=> "Bloker en IP-adresse",
	"Asksql"		=> "Lav en foresp�rgsel i databasen",
	"Undelete"		=> "Se og gendan slettede sider",
	"Makesysop"		=> "Lav en bruger til administrator"
);

/* private */ $wgDeveloperSpecialPagesDa = array(
	"Lockdb"		=> "Skrivebeskyt databasen",
	"Unlockdb"		=> "Gendan skriveadgangen til databasen",
);

/* private */ $wgAllMessagesDa = array(

# Bits of text used by many pages:
#
"categories" => "Sidekategorier",
"category" => "kategori",
"category_header" => "Artikler i kategorien \"$1\"",
"subcategories" => "Underkategorier",

"linktrail"		=> "/^([a-z|�|�|�]+)(.*)\$/sD",
"mainpage"		=> "Forside",
"mainpagetext"	=> "Wiki-software er nu installeret.",
"about"			=> "Om",
"aboutwikipedia" => "Om Wikipedia",
"aboutpage"		=> "Wikipedia:Om",
"help"			=> "Hj�lp",
"helppage"		=> "Wikipedia:Hj�lp",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Fejlrapporter",
"bugreportspage" => "Wikipedia:Fejlrapporter",
"sitesupport"   => "Donation",
"sitesupportpage" => "Wikipedia:Donation", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "OSS",
"faqpage"		=> "Wikipedia:OSS",
"edithelp"		=> "Hj�lp til redigering",
"edithelppage"	=> "Wikipedia:Hvordan_redigerer_jeg_en_side",
"cancel"		=> "Afbryd",
"qbfind"		=> "Find",
"qbbrowse"		=> "Gennemse",
"qbedit"		=> "Redig�r",
"qbpageoptions" => "Indstillinger for side",
"qbpageinfo"	=> "Information om side",
"qbmyoptions"	=> "Mine indstillinger",
"mypage"		=> "Min side",
"mytalk"		=> "Min diskussion",
"currentevents" => "Aktuelle begivenheder",
"errorpagetitle" => "Fejl",
"returnto"		=> "Tilbage til $1.",
"fromwikipedia"	=> "Fra Wikipedia, den frie encyklop�di",
"whatlinkshere"	=> "Sider med en henvisning hertil",
"help"			=> "Hj�lp",
"search"		=> "S�g",
"go"		=> "Udf�r",
"history"		=> "Historie",
"printableversion" => "Printervenlig version",
"editthispage"	=> "Redig�r side",
"deletethispage" => "Slet side",
"protectthispage" => "Beskyt side",
"unprotectthispage" => "Fjern beskyttelse af side",
"newpage" => "Ny side",
"talkpage"		=> "Diskussionssiden",
"postcomment"   => "Tilf�j en kommentar",
"articlepage"	=> "Se artiklen",
"subjectpage"	=> "Se emnesiden",
"userpage" => "Se brugersiden",
"wikipediapage" => "Se metasiden",
"imagepage" => 	"Se billedsiden",
"viewtalkpage" => "Se diskussion",
"otherlanguages" => "Andre sprog",
"redirectedfrom" => "(Omdirigeret fra $1)",
"lastmodified"	=> "Sidst �ndret den $1.",
"viewcount"		=> "Siden er vist i alt $1 gange.",
"gnunote" => "Denne side er udgivet under <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>.",
"printsubtitle" => "(Fra http://da.wikipedia.org)",
"protectedpage" => "Beskyttet side",
"administrators" => "Wikipedia:Administratorer",
"sysoptitle"	=> "Sysop-adgang p�kr�vet",
"sysoptext"		=> "Den funktion du har bedt om kan kun
udf�res af brugere med \"sysop\"-status. Se $1.",
"developertitle" => "Developer-adgang p�kr�vet",
"developertext"	=> "Den funktion du har bedt om, kan kun
udf�res af brugere med \"developer\"-status. Se $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Udf�r",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Den frie encyklop�di",
"retrievedfrom" => "Hentet fra \"$1\"",
"newmessages" => "Du har $1.",
"newmessageslink" => "nye beskeder",
"editsection"=>"redig�r",
"toc" => "Indholdsfortegnelse",
"showtoc" => "vis",
"hidetoc" => "skjul",
"thisisdeleted" => "Se eller gendan $1?",
"restorelink" => "$1 slettede �ndringer",

# Main script and global functions
#
"nosuchaction"	=> "Funktionen findes ikke",
"nosuchactiontext" => "Funktion specificeret i URL'en kan ikke
genkendes af Wikipediasoftwaren",
"nosuchspecialpage" => "En s�dan specialside findes ikke",
"nospecialpagetext" => "Du har bedt om en specialside, der ikke
kan genkendes af Wikipediasoftwaren.",

# General errors
#
"error"			=> "Fejl",
"databaseerror" => "Databasefejl",
"dberrortext"	=> "Der er sket en syntaksfejl i en databaseforesp�rgsel.
Dette kan v�re p� grund af en illegal foresp�rgsel (se $5),
eller det kan betyde en fejl i softwaren.
Den sidst fors�gte databaseforesp�rgsel var:
<blockquote><tt>$1</tt></blockquote>
fra funktionen \"<tt>$2</tt>\".
MySQL returnerede fejlen \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Der er sket en syntaksfejl i en databaseforesp�rgsel.
Den sidst fors�gte databaseforesp�rgsel var:
\"$1\"
fra funktionen \"$2\".
MySQL returnerede fejlen \"$3: $4\".\n",
"noconnect"		=> "Kunne ikke forbinde til databasen p� $1",
"nodb"			=> "Kunne ikke v�lge databasen $1",
"cachederror"	=> "Det f�lgende er en gemt kopi af den �nskede side, og er m�ske ikke helt opdateret.",
"readonly"		=> "Databasen er skrivebeskyttet",
"enterlockreason" => "Skriv en begrundelse for skrivebeskyttelsen, inklusive 
et estimat p� hvorn�r skrivebeskyttelsen vil blive oph�vet igen",
"readonlytext"	=> "Wikipediadatabasen er for �jeblikket skrivebeskyttet for 
nye sider og andre modifikationer, sandsynligvis p� grund af rutinem�ssig databasevedligeholdelse, hvorefter den vil returnere til normaldrift.
Den administrator der skrivebeskyttede den har denne forklaring:
<p>$1",
"missingarticle" => "Databasen fandt ikke teksten p� en side,
som den skulle have fundet, med navnet \"$1\".

<p>Dette er ikke en databasefejl, men sandsynligvis en fejl i softwaren.

<p>Send venligst en rapport om dette til en administrator, 
hvor du ogs� n�vner URL'en.",
"internalerror" => "Intern fejl",
"filecopyerror" => "Kunne ikke kopiere filen \"$1\" til \"$2\".",
"filerenameerror" => "Kunne ikke omd�be filen \"$1\" til \"$2\".",
"filedeleteerror" => "Kunne ikke slette filen \"$1\".",
"filenotfound"	=> "Kunne ikke finde filen \"$1\".",
"unexpected"	=> "Uventet v�rdi: \"$1\"=\"$2\".",
"formerror"		=> "Fejl: kunne ikke afsende form",
"badarticleerror" => "Denne funktion kan ikke udf�res p� denne side.",
"cannotdelete"	=> "Kunne ikke slette siden eller filen der blev 
specificeret.",
"badtitle"		=> "Forkert titel",
"badtitletext"	=> "Den �nskede sides titel var ikke tilladt, tom eller siden
er forkert henvist fra en Wikipedia p� et andet sprog.",
"perfdisabled" => "Desv�rre! Denne funktion er midlertidigt afbrudt, 
fordi den belaster databasen meget h�rdt og i en s�dan grad, at siden 
bliver meget langsom. Funktionen bliver forh�bentlig omskrevet i den 
n�rmeste fremtid (m�ske af dig, det er jo open source!!).",
"perfdisabledsub" => "Her er en gemt kopi fra $1:",

# Login and logout pages
#
"logouttitle"	=> "Bruger-log-af",
"logouttext"	=> "Du er nu logget af.
Du kan forts�tte med at bruge Wikipedia anonymt, eller du kan logge p�
igen som den samme eller en anden bruger.\n",

"welcomecreation" => "<h2>Velkommen, $1!</h2><p>Din konto er blevet 
oprettet. Glem ikke at personligg�re dine Wikipedia-indstillinger.",

"loginpagetitle" => "Bruger log p�",
"yourname"		=> "Dit brugernavn",
"yourpassword"	=> "Din adgangskode",
"yourpasswordagain" => "Gentag adgangskode",
"newusersonly"	=> " (kun nye brugere)",
"remembermypassword" => "Husk min adgangskode til n�ste gang.",
"loginproblem"	=> "<b>Der har v�ret et problem med at f� dig logget  
p�.</b><br>Pr�v igen!",
"alreadyloggedin" => "<font color=red><b>Bruger $1, du er allerede logget 
p�!</b></font><br>\n",

"login"			=> "Log p�",
"userlogin"		=> "Log p�",
"logout"		=> "Log af",
"userlogout"	=> "Log af",
"notloggedin"	=> "Ikke logget p�",
"createaccount"	=> "Opret en ny konto",
"badretype"		=> "De indtastede adgangskoder er ikke ens.",
"userexists"	=> "Det brugernavn du har valgt er allerede i brug. V�lg 
venligst et andet brugernavn.",
"youremail"		=> "Din e-mailadresse *",
"yournick"		=> "Dit kaldenavn (til signaturer)",
"emailforlost"	=> "* Det er valgfrit om du vil oplyse din e-mailadresse. 
Men det g�r andre brugere i stand til at sende dig en e-mail, uden at 
du beh�ver offentligg�re din e-mailadresse. Samtidig g�r det muligt, at du kan f� en 
ny adgangskode sendt til din e-mailadresse.",
"loginerror"	=> "Fejl med at logge p�",
"noname"		=> "Du har ikke angivet et gyldigt brugernavn.",
"loginsuccesstitle" => "Logget p� med succes",
"loginsuccess"	=> "Du er nu logget p� Wikipedia som \"$1\".",
"nosuchuser"	=> "Der er ingen bruger med navnet \"$1\".
Kontroll�r stavem�den igen, eller brug formularen herunder til at oprette en ny brugerkonto.",
"wrongpassword"	=> "Den indtastede adgangskode var forkert. Pr�v igen.",
"mailmypassword" => "Send mig en ny adgangskode til min e-mailadresse",
"passwordremindertitle" => "Ny adgangskode fra Wikipedia",
"passwordremindertext" => "Nogen (sandsynligvis dig, fra IP-adressen $1)
har bedt om at vi sender dig en ny adgangskode til at logge p� Wikipedia.
Den nye adgangskode for bruger \"$2\" er nu \"$3\".
Du b�r logge p� nu og �ndre din adgangskode.",
"noemail"		=> "Der er ikke oplyst nogen e-mailadresse for bruger \"$1\".",
"passwordsent"	=> "En ny adgangskode er sendt til e-mailadressen,
som er registreret for \"$1\".
Du b�r logge p� og �ndre din adgangskode straks efter, du har modtaget den.",

# Edit pages
#
"summary"		=> "Beskrivelse",
"subject"		=> "Emne/overskrift",
"minoredit"		=> "Dette er en mindre �ndring.",
"watchthis"		=> "Overv�g denne artikel",
"savearticle"	=> "Gem side",
"preview"		=> "Forh�ndsvisning",
"showpreview"	=> "Forh�ndsvisning",
"blockedtitle"	=> "Brugeren er blokeret",
"blockedtext"	=> "Dit brugernavn eller din IP-adresse er blevet blokeret af 
$1. Begrundelsen er denne:<br>$2<p>Du kan kontakte $1
eller en af de andre [[Wikipedia:Administratorer|administratorer]] for at diskutere blokeringen.

Din IP-adresse er $3.
S�rg venligst for at inkludere dette nummer i alle henvendelser til en administrator.
",
"newarticle"	=> "(Ny)",
"newarticletext" => "Der er p� nuv�rende tidspunkt ingen tekst p� denne side.<br>
Du kan begynde en artikel ved at skrive i boksen herunder. 
(se [[Wikipedia:Hj�lp|hj�lpen]] for yderligere information).<br>
Hvis det ikke var din mening, s� tryk p� '''Tilbage''' eller '''Back''' knappen.",
"anontalkpagetext" => "---- ''Dette er en diskussionsside for en anonym bruger der 
ikke har oprettet en konto endnu eller ikke bruger den. Vi er derfor n�dt til at 
bruge den nummeriske [[IP-adresse]] til at identificere ham eller hende.
En IP-adresse kan v�re delt mellem flere brugere. Hvis du er en anonym bruger 
og syntes, at du har f�et irrelevante kommentarer p� s�dan en side, s� v�r 
venlig, at oprette en brugerkonto og [[Speciel:Userlogin|logge p�]], s� vi undg�r fremtidige 
forvekslinger med andre anonyme brugere.'' ",
"noarticletext" => "(Der er p� nuv�rende tidspunkt ingen tekst p� denne 
side)",
"updated"		=> "(Opdateret)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Husk at dette er kun en forh�ndsvisning, siden er ikke 
gemt endnu!",
"previewconflict" => "Denne forh�ndsvisning er resultatet af den 
redig�rbare tekst ovenfor,
s�dan vil det komme til at se ud hvis du v�lger at gemme teksten.",
"editing"		=> "Redigerer $1",
"sectionedit"	=> " (afsnit)",
"commentedit"	=> " (kommentar)",
"editconflict"	=> "Redigeringskonflikt: $1",
"explainconflict" => "Nogen har �ndret denne side, efter du
startede p� at redigere den.
Den �verste tekstboks indeholder den nuv�rende tekst.
Dine �ndringer er vist i den nederste tekstboks.
Du er n�dt til at sammenflette dine �ndringer med den eksisterende tekst.
<b>Kun</b> teksten i den �verste tekstboks vil blive gemt n�r du
trykker \"Gem side\".\n<p>",
"yourtext"		=> "Din tekst",
"storedversion" => "Den gemte version",
"editingold"	=> "<strong>ADVARSEL: Du redigerer en gammel version
af denne side.
Hvis du gemmer den, vil alle �ndringer foretaget siden denne revision blive 
overskrevet.</strong>\n",
"yourdiff"		=> "Forskelle",
"copyrightwarning" => "L�g m�rke til at alle bidrag til Wikipedia er
at betragte som udgivet under GNU Free Documentation License
(se $1 for detaljer).
Hvis du ikke vil have din tekst redigeret uden n�de og kopieret efter
forgodtbefindene, s� skal du ikke l�gge det her.<br>
Du lover os ogs�, at du skrev teksten selv, kopierede fra en
public domain eller lignende fri ressource.
<strong>L�G ALDRIG MATERIALE HER SOM ER BESKYTTET AF ANDRES OPHAVSRET UDEN 
DERES TILLADELSE!</strong>",
"longpagewarning" => "ADVARSEL: Denne side er $1 kilobytes lang; nogle
browsere kan have problemer med at redigerer sider der n�rmer sig eller 
er l�ngere end 32kb. Overvej om ikke siden kan deles op i mindre dele.",
"readonlywarning" => "ADVARSEL: Databasen er l�st p� grund af vedligeholdelse,
s� du kan ikke gemme dine �ndringer lige nu. Det kan godt v�re en god ide at 
kopiere din tekst til en tekstfil, s� du kan gemme den til senere.",
"protectedpagewarning" => "ADVARSEL: Denne side er l�st, s� kun administratorer
kan redigere den. S�rg for at du f�lger 
<a href='/wiki/Wikipedia:Politik_for_beskyttede_sider'>politiken for 
beskyttede sider</a>.",

# History pages
#
"revhistory"	=> "Versionshistorik",
"nohistory"		=> "Der er ingen versionshistorik for denne side.",
"revnotfound"	=> "Versionen er ikke fundet",
"revnotfoundtext" => "Den gamle version af den side du spurgte efter kan 
ikke findes. Kontroll�r den URL du brugte til at f� adgang til denne side.\n",
"loadhist"		=> "Indl�ser sidens historik",
"currentrev"	=> "Nuv�rende version",
"revisionasof"	=> "Versionen fra $1",
"cur"			=> "nuv�rende",
"next"			=> "n�ste",
"last"			=> "forrige",
"orig"			=> "originale",
"histlegend"	=> "Forklaring: (nuv�rende) = forskel til den nuv�rende 
version, (forrige) = forskel til den forrige version, M = mindre �ndring",

# Diffs
#
"difference"	=> "(Forskelle mellem versioner)",
"loadingrev"	=> "indl�ser version for at se forskelle",
"lineno"		=> "Linje $1:",
"editcurrent"	=> "Redig�r den nuv�rende version af denne side",

# Search results
#
"searchresults" => "S�geresultater",
"searchhelppage" => "Wikipedia:S�gning",
"searchingwikipedia" => "S�gning p� Wikipedia",
"searchresulttext" => "For mere information om s�gning p� Wikipedia, se $1.",
"searchquery"	=> "For foresp�rgsel \"$1\"",
"badquery"		=> "Forkert udformet foresp�rgsel",
"badquerytext"	=> "Vi kunne ikke udf�re din foresp�rgsel.
Det er sandsynligvis fordi du har fors�gt at s�ge efter et ord med
f�rre end tre bogstaver, hvilket ikke underst�ttes endnu.
Det kan ogs� v�re du har skrevet forkert, for
eksempel \"fisk og og skaldyr\".
Pr�v en anden foresp�rgsel.",
"matchtotals"	=> "Foresp�rgslen \"$1\" matchede $2 artikeltitler
og teksten i $3 artikler.",
"nogomatch" => "Ingen sider med pr�cis denne titel eksisterer, pr�ver 
fuldteksts�gning i stedet for. ",
"titlematches"	=> "Artikeltitler der matchede foresp�rgslen",
"notitlematches" => "Ingen artikeltitler matchede foresp�rgslen",
"textmatches"	=> "Artikeltekster der matchede foresp�rgslen",
"notextmatches"	=> "Ingen artikeltekster matchede foresp�rgslen",
"prevn"			=> "forrige $1",
"nextn"			=> "n�ste $1",
"viewprevnext"	=> "Vis ($1) ($2) ($3).",
"showingresults" => "Nedenfor vises <b>$1</b> resultater startende med 
nummer <b>$2</b>.",
"showingresultsnum" => "Herunder vises <b>$3</b> resultater startende med nummer <b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: s�gning uden resultat skyldes, 
at man s�ger efter almindelige ord som \"har\" og \"fra\",
der ikke er indekseret, eller ved at specificere mere end et s�geord (da kun 
sider der indeholder alle s�geordene vil blive fundet).",
"powersearch" => "S�g",
"powersearchtext" => "
S�g i navnerum :<br>
$1<br>
$2 List omdirigeringer &nbsp; S�g efter $3 $9",
"searchdisabled" => "<p>S�gefunktionen er midlertidigt afbrudt p� grund af
for stort pres p� serveren; vi h�ber vi kan s�tte den p� igen n�r vi har
opgraderet softwaren. I mellemtiden kan du s�ge via google:</p>
                                                                                                                                                        
<!-- SiteSearch Google -->
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
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio
name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",
"blanknamespace" => "(Hoved)",

# Preferences page
#
"preferences"	=> "Indstillinger",
"prefsnologin" => "Ikke logget p�",
"prefsnologintext"	=> "Du skal v�re <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget p�</a>
for at �ndre brugerindstillinger.",
"prefslogintext" => "Du logget p� som \"$1\".
Dit interne ID-nummer er $2.

Se [[Wikipedia:Hvordan s�tter jeg mine indstillinger]] for en forklaring p� de forskellige indstillinger.",
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
"textboxsize"	=> "Tekstboks-st�rrelse",
"rows"			=> "R�kker",
"columns"		=> "Kolonner",
"searchresultshead" => "Indstillinger for s�geresultater",
"resultsperpage" => "Resultater pr. side",
"contextlines"	=> "Linjer pr. resultat",
"contextchars"	=> "Tegn pr. linje i resultatet",
"stubthreshold" => "Gr�nse for visning af stubs",
"recentchangescount" => "Antallet af titler p� siden \"seneste �ndringer\"",
"savedprefs"	=> "Dine indstillinger er blevet gemt.",
"timezonetext"	=> "Indtast antal timer din lokale tid er forskellig
fra serverens tid (UTC). Der bliver automatisk tilpasset til dansk tid, 
ellers skulle man for eksempel for dansk vintertid, indtaste \"1\" 
(og \"2\" n�r vi er p� sommertid).",
"localtime"	=> "Lokaltid",
"timezoneoffset" => "Forskel",
"servertime"	=> "Serverens tid er nu",
"guesstimezone" => "Hent tidszone fra browseren",
"emailflag"	=> "Frav�lg muligheden for at f� e-mail fra andre brugere",
"defaultns"		=> "S�g som standard i disse navnerum:",

# Recent changes
#
"changes" => "�ndringer",
"recentchanges" => "Seneste �ndringer",
# This is the default text, and can be overriden by editing [[Wikipedia::Recentchanges]]
"recentchangestext" => "Se de senest �ndrede sider i Wikipedia p� denne side.",
"rcloaderr"		=> "Indl�ser seneste �ndrede sider",
"rcnote"		=> "Nedenfor er de seneste <strong>$1</strong> �ndringer i de 
sidste <strong>$2</strong> dage.",
"rcnotefrom"	=> "Nedenfor er �ndringerne fra <b>$2</b> indtil <b>$1</b> vist.",
"rclistfrom"	=> "Vis nye �ndringer startende fra $1",
"rclinks"		=> "Vis seneste $1 �ndringer i de sidste $2 dage; $3 mindre �ndringer.",
"rchide"		=> "i $4 form; $1 mindre �ndringer; $2 andre navnerum; $3 mere end en redigering.",
"rcliu"			=> "; $1 redigeringer fra brugere der er logget p�",
"diff"			=> "forskel",
"hist"			=> "historik",
"hide"			=> "skjul",
"show"			=> "vis",
"tableform"		=> "tabel",
"listform"		=> "liste",
"nchanges"		=> "$1 �ndringer",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "L�g en fil op",
"uploadbtn"		=> "L�g en fil op",
"uploadlink"	=> "L�g en fil op",
"reupload"		=> "L�g en fil op igen",
"reuploaddesc"	=> "Tilbage til formularen til at l�gge filer op.",
"uploadnologin" => "Ikke logget p�",
"uploadnologintext"	=> "Du skal v�re <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget p�</a>
for at kunne l�gge filer op.",
"uploadfile"	=> "L�g filen op",
"uploaderror"	=> "Fejl under opl�gning af fil",
"uploadtext"	=> "<strong>STOP!</strong> F�r du l�gger filer op her,
s� v�r sikker p� du har l�st og f�lger Wikipedias <a href=\"" .
wfLocalUrlE( "Wikipedia:Politik om brug af billeder" ) . "\">politik om brug 
af billeder</a>.
<p>For at se eller s�ge i billeder, som tidligere er lagt op,
g� til <a href=\"" . wfLocalUrlE( "Speciel:Imagelist" ) .
"\">listen over billeder</a>.
Opl�gning og sletninger er registreret i <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">log over oplagte filer</a>.
<p>Brug formularen herunder til at l�gge nye billeder op, der kan bruges
som illustration i dine artikler.
P� de fleste browsere vil du se en \"Browse...\" knap eller en 
\"Gennemse...\" knap, som vil
bringe dig til dit styresystemets standard-dialog til �bning af filer.
N�r du v�lger en fil, vil navnet p� filen dukke op i tekstfeltet
ved siden af knappen.
Du skal ogs� verificere, at du ikke er ved at bryde nogens ophavsret.
Det g�r du ved at s�tte et m�rke i checkboksen.
Tryk p� \"L�g op\"-knappen for at l�gge filen op.
Dette kan godt tage lidt tid hvis du har en langsom internetforbindelse.
<p>De foretrukne formater er JPEG til fotografiske billeder, PNG
til tegninger og andre sm� billeder, og OGG til lyd.
S�rg for at navngive din fil med et beskrivende navn for at undg� 
forvirring om indholdet.
For at bruge billedet i en artikel, s� brug et link af denne slags
<b>[[billede:fil.jpg]]</b> eller <b>[[billede:fil.png|alternativ tekst]]</b>
eller <b>[[media:fil.ogg]]</b> for lyd.
<p>L�g m�rke til at pr�cis som med Wikipedia-sider, s� kan og m� andre gerne 
redigere eller
slette de filer, du har lagt op, hvis de mener det hj�lper encyklop�dien, og
du kan blive blokeret fra at l�gge op hvis du misbruger systemet.",
"uploadlog"		=> "opl�gningslog",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Herunder er der en liste med de filer, som er lagt 
op senest. Alle de viste tider er serverens tid (UTC).
<ul>
</ul>
",
"filename"		=> "Filnavn",
"filedesc"		=> "Beskrivelse",
"affirmation"	=> "Jeg bekr�fter, at ophavsretshaveren til denne fil
er enig i, at filen udgives under betingelserne for $1.",
"copyrightpage" => "Wikipedia:Ophavsret",
"copyrightpagename" => "Wikipedia ophavsret",
"uploadedfiles"	=> "Filer som er lagt op",
"noaffirmation" => "Du skal bekr�fte, at du ikke bryder nogens ophavsret
ved at l�gge denne fil op.",
"ignorewarning"	=> "Ignor�r advarslen og gem filen alligevel.",
"minlength"		=> "Navnet p� filen skal v�re p� mindst tre bogstaver.",
"badfilename"	=> "Navnet p� filen er blevet �ndret til \"$1\".",
"badfiletype"	=> "\".$1\" er ikke et af de anbefalede filformater.",
"largefile"		=> "Det anbefales, at filer ikke fylder mere end 100kb.",
"successfulupload" => "Opl�gning er gennemf�rt med success",
"fileuploaded"	=> "Filen \"$1\" er lagt op med success.
F�lg dette link: ($2) til siden med beskrivelse og udfyld
information omkring filen, s�som hvor den kom fra, hvorn�r den er lavet
og af hvem, og andre ting du ved om filen.",
"uploadwarning" => "Opl�gningsadvarsel",
"savefile"		=> "Gem fil",
"uploadedimage" => "Lagde \"$1\" op",
"uploaddisabled" => "Desv�rre er funktionen til at l�gge billeder op afbrudt p� denne server.",

# Image list
#
"imagelist"		=> "Billedliste",
"imagelisttext"	=> "Herunder er en liste med $1 billeder sorteret $2.",
"getimagelist"	=> "henter billedliste",
"ilshowmatch"	=> "Vis alle billeder med navne der matcher",
"ilsubmit"		=> "S�g",
"showlast"		=> "Vis de sidste $1 billeder sorteret $2.",
"all"			=> "alle",
"byname"		=> "efter navn",
"bydate"		=> "efter dato",
"bysize"		=> "efter st�rrelse",
"imgdelete"		=> "slet",
"imgdesc"		=> "beskrivelse",
"imglegend"		=> "Forklaring: (beskrivelse) = vis/redig�r billedebeskrivelse.",
"imghistory"	=> "Billedhistorik",
"revertimg"		=> "gendan",
"deleteimg"		=> "slet",
"imghistlegend" => "Forklaring: (nuv�rende) = dette er det nuv�rende billede, 
(slet) = slet denne gamle version, (gendan) = gendan en gammel version.
<br><i>Klik p� en dato for at se billedet, som er lagt op den dag</i>.",
"imagelinks"	=> "Billedehenvisninger",
"linkstoimage"	=> "De f�lgende sider henviser til dette billede:",
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
Hvis man ekskludere disse, s� er der <b>$2</b> sider som sandsynligvis er 
rigtige artikler.<p>
Der har ialt v�ret <b>$3</b> viste sider, og <b>$4</b> redigeringer af sider
siden softwaren blev opdateret (25. september 2002).
Det vil sige, der har v�ret <b>$5</b> gennemsnitlige redigeringer pr. side, 
og <b>$6</b> visninger pr. redigering.",
"userstatstext" => "Der er  <b>$1</b> registrerede brugere.
<b>$2</b> af disse er administratorer (se $3).",

# Maintenance Page
#
"maintenance"		=> "Vedligeholdelsesside",
"maintnancepagetext"	=> "P� denne side er der forskellige smarte 
v�rkt�jer til at vedligeholde Wikipedia. Nogle af disse funktioner er ret 
h�rde for databasen (de tager lang tid), s� lad v�re med at opdatere siden 
hver gang du har rettet en enkelt ting ;-)",
"maintenancebacklink"	=> "Tilbage til vedligeholdelsessiden",
"disambiguations"	=> "Artikler med flertydige titler",
"disambiguationspage"	=> "Wikipedia:Henvisninger til artikler med flertydige titler",
"disambiguationstext"	=> "De f�lgende artikler henviser til 
<i>artikler med flertydige titler</i>. De skulle henvise til en ikke-flertydig 
titel i stedet for.<br>En artikel bliver behandlet som flertydig, hvis den er
henvist fra $1.<br>Henvisninger fra andre navnerum er <i>ikke</i> listet her.",
"doubleredirects"	=> "Dobbelte omdirigeringer",
"doubleredirectstext"	=> "<b>Bem�rk:</b> Denne liste kan indeholde forkerte 
resultater. Det er som regel, fordi siden indeholder ekstra tekst under den
f�rste #REDIRECT.<br>\nHver linje indeholder henvisninger til den f�rste og den 
anden omdirigering, og den f�rste linje fra den anden omdirigeringstekst, 
det giver som regel den \"rigtige\" m�lartikel, som den f�rste omdirigering 
skulle have peget p�.",
"brokenredirects"	=> "D�rlige omdirigeringer",
"brokenredirectstext"	=> "De f�lgende omdirigeringer peger p� en side der 
ikke eksisterer.",
"selflinks"		=> "Sider der henviser til sig selv",
"selflinkstext"		=> "De f�lgende sider indeholder henvisninger til sig selv, 
men det burde de ikke.",
"mispeelings"           => "Sider med stavefejl",
"mispeelingstext"               => "De f�lgende sider indeholder en af de 
almindelig stavefejl, som er listet p� $1. Den korrekte stavem�de kan 
angives i paranteser efter den fejlagtige stavem�de (s�dan her).",
"mispeelingspage"       => "Liste af almindelige stavefejl",
"missinglanguagelinks"  => "Manglende sproghenvisninger",
"missinglanguagelinksbutton"    => "Find manglende sproghenvisninger for",
"missinglanguagelinkstext"      => "Disse artikler har <i>ikke</i> nogen 
henvisning til den samme artikel i $1. Omdirigeringer og underartikler er 
<i>ikke</i> vist.",


# Miscellaneous special pages
#
"orphans"		=> "For�ldrel�se artikler",
"lonelypages"	=> "For�ldrel�se artikler",
"unusedimages"	=> "Ubrugte billeder",
"popularpages"	=> "Popul�re artikler",
"nviews"		=> "$1 visninger",
"wantedpages"	=> "�nskede artikler",
"nlinks"		=> "$1 henvisninger",
"allpages"		=> "Alle artikler",
"randompage"	=> "Tilf�ldig artikel",
"shortpages"	=> "Korte artikler",
"longpages"		=> "Lange artikler",
"listusers"		=> "Brugerliste",
"specialpages"	=> "Specielle sider",
"spheading"		=> "Specielle sider for alle brugere",
"sysopspheading" => "Specielle sider til sysop-brug",
"developerspheading" => "Specielle sider til developer-brug",
"protectpage"	=> "Beskyt side",
"recentchangeslinked" => "Relaterede �ndringer",
"rclsub"		=> "(til sider henvist fra \"$1\")",
"debug"			=> "Aflus",
"newpages"		=> "Nyeste artikler",
"ancientpages"		=> "�ldste artikler",
"intl"		=> "Sproghenvisninger",
"movethispage"	=> "Flyt side",
"unusedimagestext" => "<p>L�g m�rke til, at andre websider
s�som de andre internationale Wikipediaer m�ske henviser til et billede med
en direkte URL, s� det kan stadig v�re listet her, selvom det er
i aktivt brug.",
"booksources"	=> "Bogkilder",
"booksourcetext" => "Herunder er en liste af henvisninger til steder der
udl�ner og/eller s�lger nye og brugte b�ger, og som m�ske ogs� har 
yderligere information om b�ger du leder efter.
Wikipedia er ikke associeret med nogen af disse steder,
og denne liste skal ikke ses som en anbefaling af disse.",
"alphaindexline" => "$1 til $2",

# Email this user
#
"mailnologin"	=> "Ingen afsenderadresse",
"mailnologintext" => "Du skal v�re <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget p�</a>
og have en gyldig e-mailadresse sat i dine <a href=\"" .
  wfLocalUrl( "Speciel:Preferences" ) . "\">indstillinger</a>
for at sende e-mail til andre brugere.",
"emailuser"		=> "E-mail til denne bruger",
"emailpage"		=> "E-mail bruger",
"emailpagetext"	=> "Hvis denne bruger har sat en gyldig e-mailadresse i
sine brugerindstillinger, s� vil formularen herunder sende en enkelt 
besked.
Den e-mailadresse, du har sat i dine brugerindstillinger, vil dukke op
i \"Fra\" feltet p� denne mail, s� modtageren er i stand til at svare.",
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
"watchlist"		=> "Overv�gningsliste",
"watchlistsub"	=> "(for bruger \"$1\")",
"nowatchlist"	=> "Du har ingenting i din overv�gningsliste.",
"watchnologin"	=> "Ikke logget p�",
"watchnologintext"	=> "Du skal v�re <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget p�</a>
for at kunne �ndre din overv�gningsliste.",
"addedwatch"	=> "Tilf�jet til din overv�gningsliste",
"addedwatchtext" => "Siden \"$1\" er blevet tilf�jet til din <a href=\"" .
  wfLocalUrl( "Speciel:Watchlist" ) . "\">overv�gningsliste</a>.
Fremtidige �ndringer til denne side og den tilh�rende diskussionsside vil 
blive listet her, og siden vil fremst� <b>fremh�vet</b> i <a href=\"" .
  wfLocalUrl( "Speciel:Recentchanges" ) . "\">listen med de seneste 
�ndringer</a> for at g�re det lettere at finde den.</p>

<p>Hvis du senere vil fjerne siden fra din overv�gningsliste, s� klik 
\"Fjern overv�gning\" ude i siden.",
"removedwatch"	=> "Fjernet fra overv�gningsliste",
"removedwatchtext" => "Siden \"$1\" er blevet fjernet fra din 
overv�gningsliste.",
"watchthispage"	=> "Overv�g side",
"unwatchthispage" => "Fjern overv�gning",
"notanarticle"	=> "Ikke en artikel",
"watchnochange" => "Ingen af siderne i din overv�gningsliste er �ndret i den valgte periode.",
"watchdetails" => "($1 sider i din overv�gningsliste, fratrukket alle diskussionssiderne;
$2 totalt antal sider �ndret i den valgte periode;
$3...
<a href='$4'>vis og redig�r den komplette liste</a>.)",
"watchmethod-recent" => "tjekker seneste �ndringer for sider i din overv�gningsliste",
"watchmethod-list" => "tjekker seneste �ndringer for sider i din overv�gningsliste",
"removechecked" => "Fjern valgte sider fra min overv�gningsliste",
"watchlistcontains" => "Din overv�gningsliste indeholder $1 sider.",
"watcheditlist" => "Her er en alfabetisk liste over siderne i din overv�gningsliste.
V�lg de sider du vil fjerne fra din overv�gningsliste 
og klik p� 'fjern valgte sider fra min overv�gningsliste' knappen
i bunden af sk�rmen.",
"removingchecked" => "Fjerner de valgte sider fra din overv�gningsliste...",
"couldntremove" => "Kunne ikke fjerne '$1'...",
"iteminvalidname" => "Problem med '$1', ugyldigt navn...",
"wlnote" => "Nedenfor er de seneste $1 �ndringer i de sidste <b>$2</b> timer.",


# Delete/protect/revert
#
"deletepage"	=> "Slet side",
"confirm"		=> "Bekr�ft",
"excontent" => "indholdet var:",
"exbeforeblank" => "indholdet f�r siden blev t�mt var:",
"exblank" => "siden var tom",
"confirmdelete" => "Bekr�ft sletning",
"deletesub"		=> "(Sletter \"$1\")",
"historywarning" => "Advarsel: Siden du er ved at slette har en historie: ",
"confirmdeletetext" => "Du er ved permanent at slette en side
eller et billede sammen med hele den tilh�rende historie fra databasen.
Bekr�ft venligst at du virkelig vil g�re dette, at du forst�r
konsekvenserne, og at du g�r dette i overensstemmelse med
[[Wikipedia:Politik]].",
"confirmcheck"	=> "Ja, jeg vil virkelig slette den her.",
"actioncomplete" => "Gennemf�rt",
"deletedtext"	=> "\"$1\" er slettet.
Se $2 for en fortegnelse over de nyeste sletninger.",
"deletedarticle" => "slettet \"$1\"",
"dellogpage"	=> "Sletningslog",
"dellogpagetext" => "Herunder er en liste over de nyeste sletninger.
Alle tider er serverens tid (UTC).
<ul>
</ul>
",
"deletionlog"	=> "sletningslog",
"reverted"		=> "Gendannet en tidligere version",
"deletecomment"	=> "Begrundelse for sletning",
"imagereverted" => "Gendannelse af en tidligere version gennemf�rt med 
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
"protectlogtext" => "Herunder er en liste over sider der er blevet beskyttet/har f�et fjernet beskyttelsen.
Se [[Wikipedia:Beskyttet side]] for mere information.",
"protectedarticle" => "beskyttet [[$1]]",
"unprotectedarticle" => "fjernet beskyttelse [[$1]]",

# Undelete
"undelete" => "Gendan en slettet side",
"undeletepage" => "Se og gendan slettede sider",
"undeletepagetext" => "De f�lgende sider er slettede, men de findes 
stadig i arkivet og kan gendannes. Arkivet blivet periodevis slettet.",
"undeletearticle" => "Gendan slettet artikel",
"undeleterevisions" => "$1 revisioner arkiveret",
"undeletehistory" => "Hvis du gendanner siden, vil alle de historiske 
revisioner ogs� blive gendannet. Hvis en ny side med det samme navn 
er oprettet siden denne blev slettet, s� vil de gendannede revisioner 
dukke op i den tidligere historie, og den nyeste revision vil forblive 
p� siden.",
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
"nocontribs"	=> "Ingen �ndringer er fundet som matcher disse kriterier.",
"ucnote"	=> "Herunder er denne brugers sidste <b>$1</b> �ndringer i de 
sidste <b>$2</b> dage.",
"uclinks"	=> "Vis de sidste $1 �ndringer; vis de sidste $2 dage.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Hvad henviser hertil",
"notargettitle" => "Intet m�l",
"notargettext"	=> "Du har ikke specificeret en m�lside eller bruger
at udf�re denne funktion p�.",
"linklistsub"	=> "(Liste over henvisninger)",
"linkshere"	=> "De f�lgende sider henviser her til:",
"nolinkshere"	=> "Ingen sider henviser her til.",
"isredirect"	=> "omdirigeringsside",

# Block/unblock IP
#
"blockip"		=> "Bloker bruger",
"blockiptext"	=> "Brug formularen herunder til at blokere for skriveadgangen
fra en specifik IP-adresse eller et brugernavn.
Dette m� kun g�res for at forhindre vandalisme, og i
overensstemmelse med [[Wikipedia:Politik|Wikipedia politik]].
Udfyld en speciel begrundelse herunder (for eksempel med et citat fra
sider der har v�ret udsat for vandalisme).",
"ipaddress"		=> "IP-Adresse/brugernavn",
"ipbreason"		=> "Begrundelse",
"ipbsubmit"		=> "Bloker denne bruger",
"badipaddress"	=> "IP-adressen/brugernavnet er udformet forkert eller eksistere ikke.",
"noblockreason" => "Du skal angive en begrundelse for denne blokering.",
"blockipsuccesssub" => "Blokering udf�rt med success",
"blockipsuccesstext" => "\"$1\" er blevet blokeret.
<br>Se [[Speciel:Ipblocklist|IP blokeringslisten]] for alle blokeringer.",
"unblockip"		=> "Oph�v blokering af bruger",
"unblockiptext"	=> "Brug formularen herunder for at gendanne skriveadgangen
for en tidligere blokeret IP-adresse eller bruger.",
"ipusubmit"		=> "Oph�v blokeringen af denne adresse",
"ipusuccess"	=> "\"$1\" har f�et oph�vet blokeringen",
"ipblocklist"	=> "Liste af blokerede IP-adresser og brugernavne",
"blocklistline"	=> "$1, $2 blokerede $3",
"blocklink"		=> "bloker",
"unblocklink"	=> "oph�v blokering",
"contribslink"	=> "bidrag",
"autoblocker"	=> "Automatisk blokeret fordi du deler IP-adresse med \"$1\". Begrundelse \"$2\".",

# Developer tools
#
"lockdb"		=> "L�s database",
"unlockdb"		=> "L�s database op",
"lockdbtext"	=> "At l�se databasen vil afbryde alle brugere fra at kunne
redigere sider, �ndre deres indstillinger, redigere deres 
overv�gningsliste, og andre ting der kr�ver �ndringer i databasen.
Bekr�ft venligst at du har til hensigt at g�re dette, og at du vil
l�se databasen op, n�r din vedligeholdelse er overst�et.",
"unlockdbtext"	=> "At l�se databasen op vil g�re, at alle brugere igen 
kan redigere sider, �ndre deres indstillinger, redigere deres 
overv�gningsliste, og andre ting der kr�ver �ndringer i databasen.
Bekr�ft venligst at du har til hensigt at g�re dette.",
"lockconfirm"	=> "Ja, jeg vil virkelig l�se databasen.",
"unlockconfirm"	=> "Ja, jeg vil virkelig l�se databasen op.",
"lockbtn"		=> "L�s databasen",
"unlockbtn"		=> "L�s databasen op",
"locknoconfirm" => "Du har ikke bekr�ftet handlingen.",
"lockdbsuccesssub" => "Databasen er nu l�st",
"unlockdbsuccesssub" => "Databasen er nu l�st op",
"lockdbsuccesstext" => "Wikipediadatabasen er l�st.
<br>Husk at fjerne l�sen n�r du er f�rdig med din vedligeholdelse.",
"unlockdbsuccesstext" => "Wikipediadatabasen er l�st op.",

# SQL query
#
"asksql"		=> "SQL-foresp�rgsel",
"asksqltext"	=> "Brug formularen herunder til at lave direkte foresp�rgsler 
i Wikipediadatabasen.
Brug enkelte anf�rselstegn ('s�dan her') for at adskille strenge.
Dette kan ofte belaste serveren kraftigt, s� brug venligst denne funktion
med omtanke.",
"sqlislogged"	=> "V�r opm�rksom p� at alle SQL-foresp�rgsler gemmes i en logfil.",
"sqlquery"		=> "Indtast foresp�rgsel",
"querybtn"		=> "Afsend foresp�rgsel",
"selectonly"	=> "Foresp�rgsler andre end \"SELECT\" er forbeholdt 
Wikipediaudviklere.",
"querysuccessful" => "Foresp�rgsel gennemf�rt med success",

# Move page
#
"movepage"		=> "Flyt side",
"movepagetext"	=> "N�r du bruger formularen herunder vil du f� omd�bt en 
side og flyttet hele sidens historie til det nye navn.
Den gamle titel vil blive en omdirigeringsside til den nye titel.
Henvisninger til den gamle titel vil ikke blive �ndret. S�rg for at 
[[Speciel:Maintenance|tjekke]] for dobbelte eller d�rlige omdirigeringer. 
Du er ansvarlig for, at alle henvisninger stadig peger derhen, hvor det er 
meningen de skal pege.

Bem�rk at siden '''ikke''' kan flyttes hvis der allerede er en side 
med den nye titel, medmindre den side er tom eller er en omdirigering 
uden nogen historie. Det betyder at du kan flytte en side tilbage hvor 
den kom fra, hvis du kommer til at lave en fejl.

<b>ADVARSEL!</b>
Dette kan v�re en drastisk og uventet �ndring for en popul�r side;
v�r sikker p�, at du forst�r konsekvenserne af dette f�r du
forts�tter.",
"movepagetalktext" => "Den tilh�rende diskussionsside, hvis der er en, 
vil automatisk blive flyttet med siden '''medmindre:'''
*Du flytter siden til et andet navnerum,
*En ikke-tom diskussionsside allerede eksisterer under det nye navn, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilf�lde er du n�dt til at flytte eller sammenflette siden manuelt.",
"movearticle"	=> "Flyt side",
"movenologin"	=> "Ikke logget p�",
"movenologintext" => "Du skal v�re registreret bruger og v�re <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget p�</a>
for at flytte en side.",
"newtitle"		=> "Til ny titel",
"movepagebtn"	=> "Flyt side",
"pagemovedsub"	=> "Flytning gennemf�rt",
"pagemovedtext" => "Siden \"[[$1]]\" er flyttet til \"[[$2]]\".",
"articleexists" => "En side med det navn eksisterer allerede, eller det
navn du har valgt er ikke gyldigt. V�lg et andet navn.",
"talkexists"	=> "Siden blev flyttet korrekt, men den tilh�rende 
diskussionsside kunne ikke flyttes, fordi der allerede eksisterer en 
med den nye titel. Du er n�dt til at flette dem sammen manuelt.",
"movedto"		=> "flyttet til",
"movetalk"		=> "Flyt ogs� \"diskussion\"ssiden, hvis den eksisterer.",
"talkpagemoved" => "Den tilh�rende diskussionsside blev ogs� flyttet.",
"talkpagenotmoved" => "Den tilh�rende diskussionsside blev 
<strong>ikke</strong> flyttet.",

);

class LanguageDa extends Language {

        function getDefaultUserOptions () {
                $opt = Language::getDefaultUserOptions();
                return $opt;
                }

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

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsDa;
		return $wgQuickbarSettingsDa;
	}

	function getSkinNames() {
		global $wgSkinNamesDa;
		return $wgSkinNamesDa;
	}

	function getMathNames() {
		global $wgMathNamesDa;
		return $wgMathNamesDa;
	}

	function getDateFormats() {
		global $wgDateFormatsDa;
		return $wgDateFormatsDa;
	}

	function getUserToggles() {
		global $wgUserTogglesDa;
		return $wgUserTogglesDa;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesDa;
		return $wgMonthNamesDa[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		global $wgMonthNamesDa;
		return $wgMonthNamesDa[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsDa;
		return $wgMonthAbbreviationsDa[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesDa;
		return $wgWeekdayNamesDa[$key-1];
	}

	# Inherit userAdjust()

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
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
		return $this->date( $ts, $adj ) . " kl. " . $this->time( $ts, $adj );
	}

	# Inherit rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesDa;
		return $wgValidSpecialPagesDa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesDa;
		return $wgSysopSpecialPagesDa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesDa;
		return $wgDeveloperSpecialPagesDa;
	}

	function getMessage( $key )
	{
            global $wgAllMessagesDa, $wgAllMessagesEn;
            $m = $wgAllMessagesDa[$key];

            if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
            else return $m;
	}

	# Inherit iconv()

	# Inherit ucfirst()

	# Inherit lcfirst()
	
	# Inherit checkTitleEncoding()
	
	# Inherit stripForSearch()
	
	# Inherit setAltEncoding()
	
	# Inherit recodeForEdit()
	
	# Inherit recodeInput()

	# Inherit isRTL()
	
	# Inherit getMagicWords()

}

?>

