<?

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesDa = array(
	-1	=> "Speciel",
	0	=> "",
	1	=> "Diskussion",
	2	=> "Bruger",
	3	=> "Bruger_diskussion",
	4	=> "Wikipedia",
	5	=> "Wikipedia_diskussion",
	6	=> "Billede",
	7	=> "Billede_diskussion"
);

/* private */ $wgDefaultUserOptionsDa = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0
);

/* private */ $wgQuickbarSettingsDa = array(
	"Ingen", "Fast venstre", "Fast højre", "Flydende venstre"
);

/* private */ $wgSkinNamesDa = array(
	"Standard", "Nostalgi", "Cologne Blå"
);

/* private */ $wgMathNamesDa = array(
	"Vis altid som PNG",
	"HTML hvis meget simpel ellers PNG",
	"HTML hvis muligt ellers PNG",
	"Lad være som TeX (for tekst browsere)",
    "Anbefalet til moderne browsere"
);

/* private */ $wgUserTogglesDa = array(
	"hover"		=> "Vis svævende tekst over wiki links",
	"underline" => "Understreg links",
	"highlightbroken" => "Røde links til tomme sider",
	"justify"	=> "Justér paragraffer",
	"hideminor" => "Gem små redigeringer i sidste ændringer",
	"usenewrc" => "Udvidet seneste ændringer (ikke for alle browsere)",
	"numberheadings" => "Automatisk nummerering af overskrifter",
	"rememberpassword" => "Husk password til næste besøg",
	"editwidth" => "Redigeringsboksen har fuld bredde",
	"editondblclick" => "Rediger sider med dobbeltklik (JavaScript)",
	"watchdefault" => "Overvåg nye og ændrede artikler",
	"minordefault" => "Marker som standard alle ændringer som mindre",
	"previewontop" => "Vis forhåndsvisning før redigeringsboksen"
	
);

/* private */ $wgBookstoreListDa = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* Note: native names of languages are preferred where known to maximize
   ease of navigation -- people should be able to recognize their own
   languages! */
/* private */ $wgLanguageNamesDa = array(
    "aa"    => "Afar",
    "ab"    => "Abkhazian",
	"af"	=> "Afrikaans",
	"am"	=> "Amharisk",
	"ar" => "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236; (Araby)",
	"as"	=> "Assamesisk",
	"ay"	=> "Aymará",
	"az"	=> "Aserbajdsjansk",
	"ba"	=> "Bajkirsk",
	"be" => "&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1080;",
	"bh"	=> "Bihara",
	"bi"	=> "Bislama",
	"bn"	=> "Bengalsk",
	"bo"	=> "Tibetansk",
	"br" => "Brezhoneg",
	"bs" => "Bosnisk",
	"ca" => "Catal&#224;",
	"ch" => "Chamoru",
	"co"	=> "Korsikansk",
	"cs" => "&#268;esk&#225;",
	"cy" => "Cymraeg",
	"da" => "Dansk", # Note two different subdomains. 
    "dk" => "Dansk", # 'da' is correct for the language.
	"de" => "Deutsch",
	"dz"	=> "Bhutansk",
	"el" => "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940; (Ellenika)",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Espa&#241;ol",
	"et" => "Eesti",
	"eu" => "Euskara",
	"fa" => "&#8238;&#1601;&#1585;&#1587;&#1609;&#8236;(Farsi)",
	"fi" => "Suomi",
	"fj"	=> "Fijian",
	"fo"	=> "Færøsk",
	"fr" => "Fran&#231;ais",
	"fy"	=> "Frisisk",
	"ga"	=> "Irsk",
	"gl"	=> "Galicisk",
	"gn"	=> "Guarani",
	"gu" => "&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752; (Gujarati)",
	"ha"	=> "Hausa",
	"he" => "&#1506;&#1489;&#1512;&#1497;&#1514; (Ivrit)",
	"hi" => "&#2361;&#2367;&#2344;&#2381;&#2342;&#2368; (Hindi)",
	"hr" => "Hrvatski",
	"hu" => "Magyar",
	"hy"	=> "Armensk",
	"ia" => "Interlingua",
	"id" => "Indonesia",
	"ik"	=> "Inupiaq",
	"is" => "&#205;slenska",
	"it" => "Italiano",
	"iu"	=> "Inuktitut",
	"ja" => "&#26085;&#26412;&#35486; (Nihongo)",
	"jv"	=> "Javanesisk",
	"ka" => "&#4325;&#4304;&#4320;&#4311;&#4309;&#4308;&#4314;&#4312; (Kartuli)",
	"kk"	=> "Kasakhisk",
	"kl"	=> "Grønlandsk",
	"km"	=> "Cambodjansk",
	"kn"	=> "Kannaresisk",
	"ko" => "&#54620;&#44397;&#50612; (Hangukeo)",
	"ks"	=> "Kashmiri",
	"kw" => "Kernewek",
	"ky"	=> "Kirgisisk",
	"la" => "Latina",
	"ln"	=> "Lingala",
	"lo"	=> "Laotisk",
	"lt" => "Lietuvi&#371;",
	"lv"	=> "Lettisk",
	"mg" => "Malagasy",
	"mi"	=> "Maori",
	"mk"	=> "Makedonisk",
	"ml"	=> "Maltesisk",
	"mn"	=> "Mongolsk",
	"mo"	=> "Moldovisk",
	"mr"	=> "Marathi",
	"ms" => "Bahasa Melayu",
	"my"	=> "Burmesisk",
	"na"	=> "Nauru",
	"ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368; (Nepali)",
	"nl" => "Nederlands",
	"no" => "Norsk",
	"oc"	=> "Occitansk",
	"om"	=> "Oromo",
	"or"	=> "Orija",
	"pa"	=> "Panjabi",
	"pl" => "Polski",
	"ps"	=> "Pashto",
	"pt" => "Portugu&#234;s",
	"qu"	=> "Kechua",
	"rm"	=> "Rhætoromansk",
	"rn"	=> "Rundi",
	"ro" => "Rom&#226;n&#259;",
	"ru" => "&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; (Russkij)",
	"rw"	=> "Kinyarwanda",
	"sa" => "&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340; (Samskrta)",
	"sd"	=> "Sindhi",
	"sg"	=> "Sango",
	"sh"	=> "Kroatisk",
	"si"	=> "Singalesisk",
	"simple" => "Simple English",
	"sk"	=> "Slovakisk",
	"sl"	=> "Slovensko",
	"sm"	=> "Samoansk",
	"sn"	=> "Shona",
	"so" => "Soomaali",
	"sq" => "Shqiptare",
	"sr" => "Srpski",
	"ss"	=> "Swati",
	"st"	=> "Sotho",
	"su"	=> "Sudanesisk",
	"sv" => "Svenska",
	"sw" => "Kiswahili",
	"ta"	=> "Tamilsk",
	"te"	=> "Telugu",
	"tg"	=> "Tajik",
	"th"	=> "Thai",
	"ti"	=> "Tigrinja",
	"tk"	=> "Turkmensk",
	"tl"	=> "Tagalog",
	"tn"	=> "Tswana",
	"to"	=> "Tonga",
	"tr" => "T&#252;rk&#231;e",
	"ts"	=> "Tsonga",
	"tt"	=> "Tatarisk",
	"tw"	=> "Twi",
	"ug"	=> "Uigurisk",
	"uk" => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072; (Ukrayins`ka)",
	"ur"	=> "Urdu",
	"uz"	=> "Uzbekisk",
	"vi"	=> "Vietnamesisk",
	"vo" => "Volap&#252;k",
	"wo"	=> "Wolof",
	"xh" => "isiXhosa",
	"yi"	=> "Jiddisch",
	"yo"	=> "Yoruba",
	"za"	=> "Zhuang",
	"zh" => "&#20013;&#25991; (Zhongwen)",
	"zu"	=> "Zulu"
);

/* private */ $wgWeekdayNamesDa = array(
	"Søndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag",
	"Fredag", "Lørdag"
);

/* private */ $wgMonthNamesDa = array(
	"januar", "februar", "marts", "april", "maj", "juni",
	"juli", "august", "september", "oktober", "november",
	"december"
);

/* private */ $wgMonthAbbreviationsDa = array(
	"Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug",
	"Sep", "Okt", "Nov", "Dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesDa = array(
	"Userlogin"	=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Mine brugerindstillinger",
	"Watchlist"	=> "Min overvågningsliste",
	"Recentchanges" => "Seneste ændringer",
	"Upload"	=> "Upload filer",
	"Imagelist"	=> "Billedliste",
	"Listusers"	=> "Registrerede brugere",
	"Statistics"	=> "Statistik om siden",
	"Randompage"	=> "Tilfældig artikel",

	"Lonelypages"	=> "Forældreløse artikler",
	"Unusedimages"	=> "Forældreløse filer",
	"Popularpages"	=> "Populære artikler",
	"Wantedpages"	=> "Mest ønskede artikler",
	"Shortpages"	=> "Korte artikler",
	"Longpages"	=> "Lange artikler",
	"Newpages"	=> "De nyeste artikler",
	"Intl"		=> "Sproglinks",
	"Allpages"	=> "Alle sider efter titel",

	"Ipblocklist"	=> "Blokerede IP adresser",
	"Maintenance"	=> "Vedligeholdelsesside",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Eksterne bogkilder"
);

/* private */ $wgSysopSpecialPagesDa = array(
	"Blockip"		=> "Bloker en IP adresse",
	"Asksql"		=> "Lav en query i databasen",
	"Undelete"		=> "Se og gendan slettede sider"
);

/* private */ $wgDeveloperSpecialPagesDa = array(
	"Lockdb"		=> "Skrivebeskyt databasen",
	"Unlockdb"		=> "Gendan skriveadgangen til databasen",
	"Debug"			=> "Debug information"
);

/* private */ $wgAllMessagesDa = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z|æ|ø|å]+)(.*)\$/sD",
"mainpage"		=> "Forside",
"about"			=> "Om",
"aboutwikipedia" => "Om Wikipedia",
"aboutpage"		=> "Wikipedia:Om",
"help"			=> "Hjælp",
"helppage"		=> "Wikipedia:Hjælp",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Fejlrapporter",
"bugreportspage" => "Wikipedia:Fejlrapporter",
"faq"			=> "OSS",
"faqpage"		=> "Wikipedia:OSS",
"edithelp"		=> "Hjælp til redigering",
"edithelppage"	=> "Wikipedia:Hvordan_redigerer_jeg_en_side",
"cancel"		=> "Afbryd",
"qbfind"		=> "Find",
"qbbrowse"		=> "Gennemse",
"qbedit"		=> "Rediger",
"qbpageoptions" => "Indstillinger for side",
"qbpageinfo"	=> "Information om side",
"qbmyoptions"	=> "Mine indstillinger",
"mypage"		=> "Min side",
"mytalk"		=> "Min diskussion",
"currentevents" => "Aktuelle begivenheder",
"errorpagetitle" => "Fejl",
"returnto"		=> "Tilbage til $1.",
"fromwikipedia"	=> "Fra Wikipedia, den frie encyklopædi.",
"whatlinkshere"	=> "Sider med et link hertil",
"help"			=> "Hjælp",
"search"		=> "Søg",
"go"		=> "Udfør",
"history"		=> "Historie",
"printableversion" => "Printervenlig version",
"editthispage"	=> "Rediger side",
"deletethispage" => "Slet side",
"protectthispage" => "Beskyt side",
"unprotectthispage" => "Fjern beskyttelse af side",
"newpage" => "Ny side",
"talkpage"		=> "Diskussionssiden",
"articlepage"	=> "Se artiklen",
"subjectpage"	=> "Se emnesiden",
"userpage" => "Se brugersiden",
"wikipediapage" => "Se metasiden",
"imagepage" => 	"Se billedsiden",
"viewtalkpage" => "Se diskussion",
"otherlanguages" => "Andre sprog",
"redirectedfrom" => "(Omdirigeret fra $1)",
"lastmodified"	=> "Sidst ændret den $1.",
"viewcount"		=> "Siden er vist ialt $1 gange.",
"gnunote" => "Denne side er udgivet under <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>.",
"printsubtitle" => "(Fra http://da.wikipedia.org)",
"protectedpage" => "Beskyttet side",
"administrators" => "Wikipedia:Administratorer",
"sysoptitle"	=> "Sysop adgang påkrævet",
"sysoptext"		=> "Den funktion du har bedt om kan kun
udføres af brugere med \"sysop\" status. Se $1.",
"developertitle" => "Developer adgang påkrævet",
"developertext"	=> "Den funktion du har bedt om kan kun
udføres af brugere med \"developer\" status. Se $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Udfør",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Den frie encyklopædi",
"retrievedfrom" => "Hentet fra \"$1\"",
"newmessages" => "Du har $1.",
"newmessageslink" => "nye beskeder",

# Main script and global functions
#
"nosuchaction"	=> "Funktionen findes ikke",
"nosuchactiontext" => "Den funktion der er specificeret i URL'en kan ikke
genkendes af Wikipedia softwaren",
"nosuchspecialpage" => "Sådan en speciel side findes ikke",
"nospecialpagetext" => "Du har bedt om en speciel side der ikke
kan genkendes af Wikipedia softwaren.",

# General errors
#
"error"			=> "Fejl",
"databaseerror" => "Database fejl",
"dberrortext"	=> "Der er sket en database query syntax fejl.
Dette kan være på grund af en illegal søge query (se $5),
eller det kan betyde en fejl i softwaren.
Den sidst forsøgte database query var:
<blockquote><tt>$1</tt></blockquote>
fra funktionen \"<tt>$2</tt>\".
MySQL returnerede fejlen \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Kunne ikke forbinde til databasen på $1",
"nodb"			=> "Kunne ikke vælge databasen $1",
"readonly"		=> "Databasen er skrivebeskyttet",
"enterlockreason" => "Skriv en begrundelse for skrivebeskyttelsen, inklusive 
et estimat på hvornår skrivebeskyttelsen vil blive ophævet igen",
"readonlytext"	=> "Wikipedia databasen er for øjeblikket skrivebeskyttet for 
nye sider og andre modifikationer, sandsynligvis for rutinemæssig database 
vedligeholdelse, hvorefter den vil returnere til normal.
Den administrator der skrivebeskyttede den har denne forklaring:
<p>$1",
"missingarticle" => "Databasen fandt ikke teksten på en side
som den skulle have fundet, med navnet \"$1\".
Dette er ikke en database fejl, men sandsynligvis en fejl i softwaren.
Send venligst en rapport om dette til en administrator, 
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
"badtitletext"	=> "Den ønskede sides titel var ulovlig, tom eller siden
er forkert linket fra en Wikipedia på et andet sprog.",
"perfdisabled" => "Desværre! Denne funktion er midlertidigt afbrudt, 
fordi den belaster databasen meget hårdt, i en sådan grad at siden 
bliver meget langsom. Funktionen bliver forhåbentlig omskrevet i den 
nærmeste fremtid (måske af dig, vi er jo open source!!).",

# Login and logout pages
#
"logouttitle"	=> "Bruger log af",
"logouttext"	=> "Du er nu logget af.
Du kan fortsætte med at bruge Wikipedia anonymt, eller du kan logge på
igen som den samme eller en anden bruger.\n",

"welcomecreation" => "<h2>Velkommen, $1!</h2><p>Din konto er blevet 
oprettet. Glem ikke at personliggøre dine Wikipedia indstillinger.",

"loginpagetitle" => "Bruger log på",
"yourname"		=> "Dit brugernavn",
"yourpassword"	=> "Dit password",
"yourpasswordagain" => "Gentag password",
"newusersonly"	=> " (kun nye brugere)",
"remembermypassword" => "Husk mit password til næste gang.",
"loginproblem"	=> "<b>Der har været et problem med at logge dig 
på.</b><br>Prøv igen!",
"alreadyloggedin" => "<font color=red><b>Bruger $1, du er allerede logget 
på!</b></font><br>\n",
"areyounew"		=> "Hvis du er ny på Wikipedia og gerne vil have en 
bruger konto, så indtast et brugernavn, derefter indtaster du et 
password og gentager samme password. Din e-mail adresse er valgfri; 
hvis du mister dit password kan du bede om
at få det sendt til den adresse du har oplyst.<br>\n",

"login"			=> "Log på",
"userlogin"		=> "Log på",
"logout"		=> "Log af",
"userlogout"	=> "Log af",
"createaccount"	=> "Opret en ny konto",
"badretype"		=> "De passwords du indtastede er ikke ens.",
"userexists"	=> "Det brugernavn du har valgt er allerede i brug. Vælg 
venligst et andet brugernavn.",
"youremail"		=> "Din e-mail *",
"yournick"		=> "Dit øgenavn (til signaturer)",
"emailforlost"	=> "* Det er valgfrit om du vil oplyse din e-mail adresse. 
Men det gør andre brugere i stand til at sende dig en e-mail, uden at 
du behøves offentliggøre din e-mail adresse, og det gør at du kan få et 
nyt password sendt til din e-mail adresse.",
"loginerror"	=> "Fejl med at logge på",
"noname"		=> "Du har ikke specificeret et gyldigt brugernavn.",
"loginsuccesstitle" => "Logget på med success",
"loginsuccess"	=> "Du er nu logget på Wikipedia som \"$1\".",
"nosuchuser"	=> "Der er ingen bruger med navnet \"$1\".
Tjek stavemåden igen, eller brug formen herunder til at lave en ny bruger 
konto.",
"wrongpassword"	=> "Det password du indtastede var forkert. Prøv igen.",
"mailmypassword" => "Send mig et nyt password med e-mail",
"passwordremindertitle" => "Nyt password fra Wikipedia",
"passwordremindertext" => "Nogen (sandsynligvis dig, fra IP adressen $1)
har bedt om at vi sender dig et nyt password til at logge på Wikipedia.
Det nye password for bruger \"$2\" er nu \"$3\".
Du bør logge på nu og ændre dit password.",
"noemail"		=> "Der er ikke oplyst nogen e-mail adresse for bruger \"$1\".",
"passwordsent"	=> "Et nyt password er sendt til e-mail adressen
der er registreret for \"$1\".
Du bør logge på og ændre dit password straks efter du har modtaget det.",

# Edit pages
#
"summary"		=> "Beskrivelse",
"minoredit"		=> "Dette er en mindre ændring.",
"watchthis"		=> "Overvåg denne artikel",
"savearticle"	=> "Gem side",
"preview"		=> "Forhåndsvisning",
"showpreview"	=> "Forhåndsvisning",
"blockedtitle"	=> "Brugeren er blokeret",
"blockedtext"	=> "Dit brugernavn eller din IP adresse er blevet blokeret af 
$1. Begrundelsen der er blevet givet er denne:<br>$2<p>Du kan kontakte 
administratoren for at diskutere blokeringen.",
"newarticle"	=> "(Ny)",
"newarticletext" => "Der er på nuværende tidspunkt ingen tekst på denne side.<br>
Du kan begynde en artikel ved at skrive i boksen herunder. 
(se [[Wikipedia:Hjælp|hjælpen]] for yderligere information).<br>
Hvis det ikke var din mening, så tryk på '''Tilbage''' eller '''Back''' knappen.",
"anontalkpagetext" => "---- ''Dette er en diskussionsside for anonyme brugere der 
ikke har oprettet en konto endnu eller ikke bruger den. Vi er derfor nødt til at 
bruge den nummeriske [[IP adresse]] til at identificere ham eller hende.
En IP adresse kan være delt mellem flere brugere. Hvis du er en anonym bruger 
og syntes at du har fået irrelevante kommentarer på sådan en side, så vær 
venlig og [[Speciel:Userlogin|logge på]] så vi undgår fremtidige 
forvekslinger med andre anonyme brugere.'' ",
"noarticletext" => "(Der er på nuværende tidspunkt ingen tekst på denne 
side)",
"updated"		=> "(Opdateret)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Husk at dette er kun en forhåndsvisning, og siden er ikke 
gemt endnu!",
"previewconflict" => "Denne forhåndsvisning er resultatet af den 
redigerbare tekst ovenfor,
sådan vil det komme til at se ud hvis du vælger at gemme teksten.",
"editing"		=> "Redigerer $1",
"editconflict"	=> "Redigeringskonflikt: $1",
"explainconflict" => "Nogen har ændret denne side efter du
startede på at redigerer den.
Den øverste tekst boks indeholder den nuværende tekst.
Dine ændringer er vist i den nederste tekst boks.
Du er nødt til at sammenflette dine ændringer med den eksisterende tekst.
<b>Kun</b> teksten i den øverste tekst boks vil blive gemt når du
trykker \"Gem side\".\n<p>",
"yourtext"		=> "Din tekst",
"storedversion" => "Den gemte version",
"editingold"	=> "<strong>ADVARSEL: Du redigerer en gammel version
af denne side
Hvis du gemmer den, vil alle ændringer lavet siden denne revision være 
overskrevet.</strong>\n",
"yourdiff"		=> "Forskelle",
"copyrightwarning" => "Læg mærke til at alle bidrag til Wikipedia er
at betragte som udgivet under GNU Free Documentation License
(se $1 for detaljer).
Hvis du ikke vil have din tekst redigeret uden nåde og kopieret efter
forgodtbefindene, så skal du ikke lægge det her.<br>
Du lover os også at du skrev teksten selv, eller kopierede fra en
public domain eller lignende fri resurce.
<strong>LÆG ALDRIG MATERIALE HER SOM ER BESKYTTET AF ANDRES OPHAVSRET UDEN 
DERES TILLADELSE!</strong>",
"longpagewarning" => "ADVARSEL: Denne side er $1 kilobytes lang; nogle
browsere kan have problemer med at redigerer sider der nærmer sig eller 
er længere end 32kb. Overvej om ikke siden kan splittes op i mindre dele.",

# History pages
#
"revhistory"	=> "Versionshistorie",
"nohistory"		=> "Der er ingen versionshistorie for denne side.",
"revnotfound"	=> "Versionen er ikke fundet",
"revnotfoundtext" => "Den gamle version af den side du spurgte efter kan 
ikke findes. Tjek den URL du brugte til at få adgang til denne side.\n",
"loadhist"		=> "Læser sidens historie",
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
"loadingrev"	=> "læser version til at se forskelle",
"lineno"		=> "Linje $1:",
"editcurrent"	=> "Rediger den nuværende version af denne side",

# Search results
#
"searchresults" => "Søge resultater",
"searchhelppage" => "Wikipedia:Søgning",
"searchingwikipedia" => "Søgning på Wikipedia",
"searchresulttext" => "For mere information om søgning på Wikipedia, se 
$1.",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "Forkert udformet søge forespørgsel",
"badquerytext"	=> "Vi kunne ikke udføre din forespørgsel.
Det er sandsynligvis fordi du har forsøgt at søge efter et ord med
færre end tre bogstaver, hvilket ikke understøttes endnu.
Det kan også være du har skrevet forkert, for
eksempel \"fisk og og skaldyr\".
Prøv en anden forespørgsel.",
"matchtotals"	=> "Forespørgslen \"$1\" matchede $2 artikel titler
og teksten i $3 artikler.",
"nogomatch" => "Ingen sider med præcis denne titel eksisterer, prøver 
fuldtekstsøgning istedet for. ",
"titlematches"	=> "Artikel titler der matchede forespørgslen",
"notitlematches" => "Ingen artikel titler matchede forespørgslen",
"textmatches"	=> "Artikel tekster der matchede forespørgslen",
"notextmatches"	=> "Ingen artikel tekster matchede forespørgslen",
"prevn"			=> "forrige $1",
"nextn"			=> "næste $1",
"viewprevnext"	=> "Vis ($1) ($2) ($3).",
"showingresults" => "Nedenfor vises <b>$1</b> resultater startende med 
nummer <b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: søgning uden success er tit
forårsaget af at man søger efter almindelige ord som \"har\" og \"fra\",
som ikke er indekseret, eller ved at specificere mere end et søgeord (kun 
sider der indeholder alle søgeordene vil blive fundet).",
"powersearch" => "Søg",
"powersearchtext" => "
Søg i navnerum :<br>
$1<br>
$2 List omdirigeringer &nbsp; Søg efter $3 $9",


# Preferences page
#
"preferences"	=> "Indstillinger",
"prefsnologin" => "Ikke logget på",
"prefsnologintext"	=> "Du skal være <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget på</a>
for at ændre bruger indstillinger.",
"prefslogintext" => "Du logget på som \"$1\".
Dit interne ID nummer er $2.",
"prefsreset"	=> "Indstillingerne er blevet gendannet fra lageret.",
"qbsettings"	=> "Indstillinger for hurtigmenu",
"changepassword" => "Skift password",
"skin"			=> "Udseende",
"math"			=> "Vise matematik",
"math_failure"		=> "Fejl i matematikken",
"math_unknown_error"	=> "ukendt fejl",
"math_unknown_function"	=> "ukendt funktion ",
"math_lexing_error"	=> "lexer fejl",
"math_syntax_error"	=> "syntax fejl",
"saveprefs"		=> "Gem indstillinger",
"resetprefs"	=> "Gendan indstillinger",
"oldpassword"	=> "Gammelt password",
"newpassword"	=> "Nyt password",
"retypenew"		=> "Gentag nyt password",
"textboxsize"	=> "Tekstboks dimensioner",
"rows"			=> "Rækker",
"columns"		=> "Kolonner",
"searchresultshead" => "Søge resultat indstillinger",
"resultsperpage" => "Resultater pr. side",
"contextlines"	=> "Linjer pr. resultat",
"contextchars"	=> "Karakterer pr. linje i resultatet",
"stubthreshold" => "Grænse for visning af stubs",
"recentchangescount" => "Antallet af titler på \"seneste ændringer\" 
siden",
"savedprefs"	=> "Dine indstillinger er blevet gemt.",
"timezonetext"	=> "Indtast antal timer din lokale tid er forskellig
fra server (UTC) tiden. Der bliver automatisk tilpasset til dansk tid, 
ellers skulle man for eksempel for Dansk vintertid, indtaste \"1\" 
(og \"2\" når vi er på sommertid).",
"localtime"	=> "Lokal tid",
"timezoneoffset" => "Forskel",
"emailflag"	=> "Fravælg muligheden for at få e-mail fra andre brugere",

# Recent changes
#
"changes" => "ændringer",
"recentchanges" => "Seneste ændringer",
"recentchangestext" => "Se de senest ændrede sider i Wikipedia på denne 
side.

[[Wikipedia:Velkommen nybegynder|Velkommen nybegynder]]!
Kig venligst på disse sider: [[Wikipedia:Hjælp|Hjælp]], 
[[Wikipedia:OSS|Ofte Stillede Spørgsmål]] og 
[[Wikipedia:Mest almindelige begynderfejl på Wikipedia|mest almindelige begynderfejl på Wikipedia]].

Det er meget vigtigt for Wikipedias success, at du ikke lægger materiale på 
Wikipedia som andre har ophavsret til. De retslige konsekvenser kan blive 
meget kostbare og besværlige
for projektet, så lad venligst være med det.

Se også seneste ændringer for andre sprog: 
[http://meta.wikipedia.org/wiki/Special:Recentchanges meta], 
[http://de.wikipedia.org/wiki/Spezial:Recentchanges de], 
[http://www.wikipedia.org/wiki/Special:Recentchanges en], 
[http://eo.wikipedia.org/wiki/Speciala:Recentchanges eo], 
[http://es.wikipedia.org/wiki/Especial:Recentchanges es], 
[http://et.wikipedia.com/wiki.cgi?Recent_Changes et], 
[http://fr.wikipedia.org/wiki/Special:Recentchanges fr], 
[http://it.wikipedia.com/wiki.cgi?RecentChanges it], 
[http://la.wikipedia.com/wiki.cgi?Recent_Changes la], 
[http://nl.wikipedia.org/wiki/Speciaal:Recentchanges nl], 
[http://no.wikipedia.com/wiki.cgi?Recent_Changes no], 
[http://pl.wikipedia.org/wiki/Specjalna:Recentchanges pl], 
[http://pt.wikipedia.com/wiki.cgi?RecentChanges pt], 
[http://ru.wikipedia.org/wiki/Special:Recentchanges ru] og 
[http://sv.wikipedia.org/wiki/Special:Recentchanges sv].",
"rcloaderr"		=> "Læser seneste ændrede sider",
"rcnote"		=> "Nedenfor er de seneste <strong>$1</strong> ændringer i de 
sidste <strong>$2</strong> dage.",
"rcnotefrom"	=> "Nedenfor er ændringerne fra <b>$2</b> indtil <b>$1</b> vist.",
"rclistfrom"	=> "Vis nye ændringer startende fra $1",
# "rclinks"		=> "Vis seneste $1 ændringer i de sidste $2 timer / sidste $3 dage",
"rclinks"		=> "Vis seneste $1 ændringer i de sidste $2 dage.",
"rchide"		=> "i $4 form; $1 mindre ændringer; $2 andre navnerum; $3 mere end en redigering.",
"diff"			=> "forskel",
"hist"			=> "historie",
"hide"			=> "gem",
"show"			=> "vis",
"tableform"		=> "tabel",
"listform"		=> "liste",
"nchanges"		=> "$1 ændringer",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Upload",
"uploadbtn"		=> "Upload fil",
"uploadlink"	=> "Upload fil",
"reupload"		=> "Gen-upload",
"reuploaddesc"	=> "Tilbage til upload formen.",
"uploadnologin" => "Ikke logget på",
"uploadnologintext"	=> "Du skal være <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget på</a>
for at kunne uploade filer.",
"uploadfile"	=> "Upload fil",
"uploaderror"	=> "Upload fejl",
"uploadtext"	=> "<strong>STOP!</strong> Før du uploader her,
så vær sikker på du har læst og følger Wikipedias <a href=\"" .
wfLocalUrlE( "Wikipedia:Politik om brug af billeder" ) . "\">politik om brug 
af billeder</a>.
<p>For at se eller søge i tidligere uploadede billeder,
gå til <a href=\"" . wfLocalUrlE( "Speciel:Imagelist" ) .
"\">listen af uploadede billeder</a>.
Uploads og sletninger er logget i <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">upload log</a>.
<p>Brug formularen herunder til at uploade nye billeder til at bruge
som illustration i dine artikler.
På de fleste browsere vil du se en \"Browse...\" knap eller en 
\"Gennemse...\" knap, som vil
bringe dig til operativsystemets standard fil åben dialog.
Når du vælger en fil vil navnet på filen dukke op i tekst feltet
ved siden af knappen.
Du skal også verificerer at du ikke er ved at bryde nogens ophavsret.
Det gør du ved at sætte et mærke i check boksen.
Tryk på \"Upload\" knappen for at afslutte din upload.
Dette kan godt tage lidt tid hvis du har en langsom internet forbindelse.
<p>De foretrukne formater er JPEG til fotografiske billeder, PNG
til tegninger og andre små billeder, og OGG til lyd.
Sørg for at navngive din fil med et beskrivende navn, for at undgå 
forvirring om indholdet.
For at bruge billedet i en artikel, så brug et link af denne slags
<b>[[billede:fil.jpg]]</b> eller <b>[[billede:fil.png|alternativ tekst]]</b>
eller <b>[[media:fil.ogg]]</b> for lyd.
<p>Læg mærke til at præcis som med Wikipedia sider, så kan og må andre gerne 
redigerer eller
slette dine uploadede filer hvis de mener det hjælper encyklopædien, og
du kan blive blokeret fra at uploade hvis du misbruger systemet.",
"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Herunder er der en liste af de seneste 
uploadede filer. Alle de viste tider er server (UTC) tider.
<ul>
</ul>
",
"filename"		=> "Filnavn",
"filedesc"		=> "Beskrivelse",
"affirmation"	=> "Jeg bekræfter at ophavsretshaveren til denne fil
er enig i at filen udgives under betingelserne for $1.",
"copyrightpage" => "Wikipedia:Ophavsret",
"copyrightpagename" => "Wikipedia ophavsret",
"uploadedfiles"	=> "Uploadede filer",
"noaffirmation" => "Du skal bekræfte at du ikke bryder nogens ophavsret
ved at uploade denne fil.",
"ignorewarning"	=> "Ignorer advarslen og gem filen alligevel.",
"minlength"		=> "Navnet på filen skal være på mindst tre bogstaver.",
"badfilename"	=> "Navnet på filen er blevet ændret til \"$1\".",
"badfiletype"	=> "\".$1\" er ikke et af de anbefalede fil formater.",
"largefile"		=> "Det anbefales at filer ikke fylder mere end 100kb.",
"successfulupload" => "Upload gennemført med success",
"fileuploaded"	=> "Filen \"$1\" er uploadeded med success.
Følg dette link: ($2) til siden med beskrivelse og udfyld
information omkring filen, såsom hvor den kom fra, hvornår den er lavet
og af hvem, og andre ting du ved om filen.",
"uploadwarning" => "Upload advarsel",
"savefile"		=> "Gem fil",
"uploadedimage" => "uploadede \"$1\"",

# Image list
#
"imagelist"		=> "Billedliste",
"imagelisttext"	=> "Herunder er en liste med $1 billeder sorteret $2.",
"getimagelist"	=> "henter billedliste",
"ilshowmatch"	=> "Vis alle billeder med navne der matcher",
"ilsubmit"		=> "Søg",
"showlast"		=> "Vis de sidste $1 billeder sorteret $2.",
"all"			=> "alle",
"byname"		=> "efter navn",
"bydate"		=> "efter dato",
"bysize"		=> "efter størrelse",
"imgdelete"		=> "slet",
"imgdesc"		=> "beskrivelse",
"imglegend"		=> "Legend: (beskrivelse) = vis/rediger billede beskrivelse.",
"imghistory"	=> "Billedhistorie",
"revertimg"		=> "gendan",
"deleteimg"		=> "slet",
"imghistlegend" => "Legend: (nuværende) = dette er det nuværende billede, 
(slet) = slet denne gamle version, (gendan) = gendan en gammel version.
<br><i>Klik på en dato for at se billedet som er uploaded den dag</i>.",
"imagelinks"	=> "Billede links",
"linkstoimage"	=> "De følgende sider linker til dette billede:",
"nolinkstoimage" => "Der er ingen sider der linker til dette billede.",

# Statistics
#
"statistics"	=> "Statistik",
"sitestats"		=> "Side statistik",
"userstats"		=> "Bruger statistik",
"sitestatstext" => "Der er ialt <b>$1</b> sider i databasen.
Dette inkluderer \"diskussion\" sider, sider om Wikipedia, minimale \"stub\"
sider, omdirigeringssider, og andre der sikkert ikke kan kvalificeres som 
artikler.
Hvis man ekskludere disse, så er der <b>$2</b> sider som sandsynligvis er 
rigtige artikler.<p>
Der har ialt været <b>$3</b> viste sider, og <b>$4</b> redigeringer af sider
siden softwaren blev opdateret (25. september 2002).
Det vil sige der har været <b>$5</b> gennemsnitlige redigeringer per side, 
og <b>$6</b> visninger per redigering.",
"userstatstext" => "Der er  <b>$1</b> registrerede brugere.
<b>$2</b> af disse er administratorer (se $3).",

# Maintenance Page
#
"maintenance"		=> "Vedligeholdelsesside",
"maintnancepagetext"	=> "På denne side er der forskellige smarte 
værktøjer til at vedligeholde Wikipedia. Nogle af disse funktioner er ret 
hårde for databasen (de tager lang tid), så lad være med at refreshe siden 
hver gang du har rettet en enkelt ting ;-)",
"maintenancebacklink"	=> "Tilbage til vedligeholdelsessiden",
"disambiguations"	=> "Sider med tvetydige titler",
"disambiguationspage"	=> "Wikipedia:Links_til_sider_med_tvetydige_titler",
"disambiguationstext"	=> "De følgende artikler linker til 
<i>sider med tvetydige titler</i>. De skulle linke til en ikke-tvetydig 
titel i stedet for.<br>En side bliver behandlet som tvetydig hvis den er
linket fra $1.<br>Links fra andre navnerum er <i>ikke</i> listet her.",
"doubleredirects"	=> "Dobbelte omdirigeringer",
"doubleredirectstext"	=> "<b>Bemærk:</b> Denne liste kan indeholde forkerte 
resultater. Det er som regel fordi siden indeholder ekstra tekst under den
første #REDIRECT.<br>\nHver linje indeholder links til den første og den 
anden omdirigering, og den første linje fra den anden omdirigeringstekst, 
det giver som regel den \"rigtige\" mål artikel, som den første omdirigering 
skulle have peget på.",
"brokenredirects"	=> "Dårlige omdirigeringer",
"brokenredirectstext"	=> "De følgende omdirigeringer peger på en side der 
ikke eksisterer.",
"selflinks"		=> "Sider der linker til sig selv",
"selflinkstext"		=> "De følgende sider indeholder links til sig selv, 
men det burde de ikke.",
"mispeelings"           => "Sider med stavefejl",
"mispeelingstext"               => "De følgende sider indeholder en af de 
almindelig stavefejl, som er listet på $1. Den korrekte stavemåde kan 
angives i paranteser efter den fejlagtige stavemåde (sådan her).",
"mispeelingspage"       => "Liste af almindelige stavefejl",
"missinglanguagelinks"  => "Manglende sprog links",
"missinglanguagelinksbutton"    => "Find manglende sprog links for",
"missinglanguagelinkstext"      => "Disse artikler har <i>ikke</i> noget 
link til den samme artikel i $1. Omdirigeringer og underartikler er 
<i>ikke</i> vist.",


# Miscellaneous special pages
#
"orphans"		=> "Forældreløse sider",
"lonelypages"	=> "Forældreløse sider",
"unusedimages"	=> "Ubrugte billeder",
"popularpages"	=> "Populære sider",
"nviews"		=> "$1 visninger",
"wantedpages"	=> "Ønskede sider",
"nlinks"		=> "$1 links",
"allpages"		=> "Alle sider",
"randompage"	=> "Tilfældig artikel",
"shortpages"	=> "Korte sider",
"longpages"		=> "Lange sider",
"listusers"		=> "Brugerliste",
"specialpages"	=> "Specielle sider",
"spheading"		=> "Specielle sider",
"sysopspheading" => "Specielle sider til sysop brug",
"developerspheading" => "Specielle sider til developer brug",
"protectpage"	=> "Beskyt side",
"recentchangeslinked" => "Relaterede ændringer",
"rclsub"		=> "(til sider linket fra \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Nye sider",
"intl"		=> "Sproglinks",
"movethispage"	=> "Flyt side",
"unusedimagestext" => "<p>Læg mærke til at andre web sider
såsom de andre internationale Wikipediaer måske linker til et billede med
en direkte URL, så det kan stadig være listet her selvom det er
i aktivt brug.",
"booksources"	=> "Bogkilder",
"booksourcetext" => "Herunder er en liste af links til steder der
udlåner og/eller sælger nye og brugte bøger, og som måske også har 
yderligere information om bøger du leder efter.
Wikipedia er ikke associeret med nogen af disse steder,
og denne liste skal ikke ses som en anbefaling af disse.",
"alphaindexline" => "$1 til $2",

# Email this user
#
"mailnologin"	=> "Ingen sende adresse",
"mailnologintext" => "Du skal være <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget på</a>
og have en gyldig e-mail adresse sat i dine <a href=\"" .
  wfLocalUrl( "Speciel:Preferences" ) . "\">indstillinger</a>
for at sende e-mail til andre brugere.",
"emailuser"		=> "E-mail til denne bruger",
"emailpage"		=> "E-mail bruger",
"emailpagetext"	=> "Hvis denne bruger har sat en gyldig e-mail adresse i
sine bruger indstillinger, så vil formularen herunder sende en enkelt 
besked.
Den e-mail adresse du har sat i dine bruger indstillinger vil dukke op
i \"Fra\" feltet på denne mail, så modtageren er i stand til at svare.",
"noemailtitle"	=> "Ingen e-mail adresse",
"noemailtext"	=> "Denne bruger har ikke sat en gyldig e-mail adresse,
eller har valgt ikke at modtage e-mail fra andre brugere.",
"emailfrom"		=> "Fra",
"emailto"		=> "Til",
"emailsubject"	=> "Emne",
"emailmessage"	=> "Besked",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sendt",
"emailsenttext" => "Din e-mail besked er blevet sendt.",

# Watchlist
#
"watchlist"		=> "Overvågningsliste",
"watchlistsub"	=> "(for bruger \"$1\")",
"nowatchlist"	=> "Du har ingenting i din overvågningsliste.",
"watchnologin"	=> "Ikke logget på",
"watchnologintext"	=> "Du skal være <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget på</a>
for at kunne ændre din overvågningsliste.",
"addedwatch"	=> "Tilføjet til din overvågningsliste",
"addedwatchtext" => "Siden \"$1\" er blevet tilføjet til din <a href=\"" .
  wfLocalUrl( "Speciel:Watchlist" ) . "\">overvågningsliste</a>.
Fremtidige ændringer til denne side og den tilhørende diskussion side vil 
blive listet her, og siden vil fremstå <b>fremhævet</b> i <a href=\"" .
  wfLocalUrl( "Speciel:Recentchanges" ) . "\">listen med de seneste 
ændringer</a> for at gøre det lettere at finde den.</p>

<p>Hvis du senere vil fjerne siden fra din overvågningsliste, så klik 
\"Fjern overvågning\" ude i siden.",
"removedwatch"	=> "Fjernet fra overvågningsliste",
"removedwatchtext" => "Siden \"$1\" er blevet fjernet fra din 
overvågningsliste.",
"watchthispage"	=> "Overvåg side",
"unwatchthispage" => "Fjern overvågning",
"notanarticle"	=> "Ikke en artikel",

# Delete/protect/revert
#
"deletepage"	=> "Slet side",
"confirm"		=> "Bekræft",
"confirmdelete" => "Bekræft sletning",
"deletesub"		=> "(Sletter \"$1\")",
"confirmdeletetext" => "Du er ved permanent at slette en side
eller et billede sammen med hele den tilhørende historie fra databasen.
Bekræft venligst at du virkelig vil gøre dette, at du forstår
konsekvenserne, og at du gør dette i overensstemmelse med
[[Wikipedia:Politik]].",
"confirmcheck"	=> "Ja, jeg vil virkelig slette den her.",
"actioncomplete" => "Gennemført",
"deletedtext"	=> "\"$1\" er slettet.
Se $2 for en fortegnelse over de nyeste sletninger.",
"deletedarticle" => "slettet \"$1\"",
"dellogpage"	=> "Sletningslog",
"dellogpagetext" => "Herunder er en liste over de nyeste sletninger.
Alle tider er server (UTC) tider.
<ul>
</ul>
",
"deletionlog"	=> "sletningslog",
"reverted"		=> "Gendannet en tidligere version",
"deletecomment"	=> "Begrundelse for sletning",
"imagereverted" => "Gendannelse af en tidligere version gennemført med 
success.",
"rollback"		=> "Fjern redigeringer",
"rollbacklink"	=> "fjern redigering",
"cantrollback"	=> "Kan ikke fjerne redigering; den sidste bruger er den eneste forfatter.",
"revertpage"	=> "Fjernet den seneste redigering lavet af $1",

# Undelete
"undelete" => "Gendan en slettet side",
"undeletepage" => "Se og gendan slettede sider",
"undeletepagetext" => "De følgende sider er slettede, men de findes 
stadig i arkivet og kan gendannes. Arkivet blivet periodevis slettet.",
"undeletearticle" => "Gendan slettet artikel",
"undeleterevisions" => "$1 revisioner arkiveret",
"undeletehistory" => "Hvis du gendanner siden, vil alle de historiske 
revisioner også blive gendannet. Hvis en ny side med det samme navn 
er oprettet siden den blev slettet, så vil de gendannede revisioner 
dukke op i den tidligere historie, og den nyeste revision vil forblive 
på siden.",
"undeleterevision" => "Slettet version fra $1",
"undeletebtn" => "Gendan!",
"undeletedarticle" => "gendannet \"$1\"",
"undeletedtext"   => "Artiklen [[$1]] er blevet gendannet med success.
Se [[Wikipedia:Sletningslog]] for en fortegnelse over nylige 
sletninger og gendannelser.",

# Contributions
#
"contributions"	=> "Bruger bidrag",
"mycontris" => "Mine bidrag",
"contribsub"	=> "For $1",
"nocontribs"	=> "Ingen ændringer er fundet som matcher disse kriterier.",
"ucnote"	=> "Herunder er denne brugers sidste <b>$1</b> ændringer i de 
sidste <b>$2</b> dage.",
"uclinks"	=> "Vis de sidste $1 ændringer; vis de sidste $2 dage.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Hvad linker hertil",
"notargettitle" => "Intet mål",
"notargettext"	=> "Du har ikke specificeret en mål side eller bruger
at udføre denne funktion på.",
"linklistsub"	=> "(Liste af links)",
"linkshere"	=> "De følgende sider linker hertil:",
"nolinkshere"	=> "Ingen sider linker hertil.",
"isredirect"	=> "omdirigeringsside",

# Block/unblock IP
#
"blockip"		=> "Bloker IP adresse",
"blockiptext"	=> "Brug formularen herunder til at blokere for skriveadgangen
fra en specifik IP adresse.
Dette må kun gøres for at forhindre vandalisme, og i
overensstemmelse med [[Wikipedia:Politik|Wikipedia politik]].
Udfyld en speciel begrundelse herunder (for eksempel med et citat fra
sider der har været udsat for vandalisme).",
"ipaddress"		=> "IP Adresse",
"ipbreason"		=> "Begrundelse",
"ipbsubmit"		=> "Bloker denne adresse",
"badipaddress"	=> "IP adressen er udformet forkert.",
"noblockreason" => "Du skal angive en begrundelse for denne blokering.",
"blockipsuccesssub" => "Blokering udført med success",
"blockipsuccesstext" => "IP adressen \"$1\" er blevet blokeret.
<br>Se [[Speciel:Ipblocklist|IP blokeringslisten]] for alle blokeringer.",
"unblockip"		=> "Ophæv blokeringen af IP adresse",
"unblockiptext"	=> "Brug formularen herunder for at gendanne skriveadgangen
for en tidligere blokeret IP adresse.",
"ipusubmit"		=> "Ophæv blokeringen af denne adresse",
"ipusuccess"	=> "IP adressen \"$1\" har fået ophævet blokeringen",
"ipblocklist"	=> "Liste af blokerede IP adresser",
"blocklistline"	=> "$1, $2 blokerede $3",
"blocklink"		=> "bloker",
"unblocklink"	=> "ophæv blokering",
"contribslink"	=> "bidrag",

# Developer tools
#
"lockdb"		=> "Lås database",
"unlockdb"		=> "Lås database op",
"lockdbtext"	=> "At låse databasen vil afbryde alle brugere fra at kunne
redigerer sider, ændre deres indstillinger, redigerer deres 
overvågningsliste, og
andre ting der kræver ændringer i databasen.
Bekræft venligst at du har til hensigt at gøre dette, og at du vil
låse databasen op når din vedligeholdelse er overstået.",
"unlockdbtext"	=> "At låse databasen op vil vil gøre at alle brugere igen 
kan
redigerer sider, ændre deres indstillinger, redigerer deres 
overvågningsliste, og
andre ting der kræver ændringer i databasen.
Bekræft venligst at du har til hensigt at gøre dette.",
"lockconfirm"	=> "Ja, jeg vil virkelig låse databasen.",
"unlockconfirm"	=> "Ja, jeg vil virkelig låse databasen op.",
"lockbtn"		=> "Lås databasen",
"unlockbtn"		=> "Lås databasen op",
"locknoconfirm" => "Du har ikke bekræftet handlingen.",
"lockdbsuccesssub" => "Databasen er nu låst",
"unlockdbsuccesssub" => "Databasen er nu låst op",
"lockdbsuccesstext" => "Wikipedia databasen er låst.
<br>Husk at fjerne låsen når du er færdig med din vedligeholdelse.",
"unlockdbsuccesstext" => "Wikipedia databasen er låst op.",

# SQL query
#
"asksql"		=> "SQL forespørgsel",
"asksqltext"	=> "Brug formularen herunder til at lave direkte forespørgsler 
i Wikipedia databasen.
Brug enkelte anførselstegn ('sådan her') for at adskille strenge.
Dette kan ofte belaste serveren kraftigt, så brug venligst denne funktion
med omtanke.",
"sqlquery"		=> "Indtast forespørgsel",
"querybtn"		=> "Afsend forespørgsel",
"selectonly"	=> "Forespørgsler andre end \"SELECT\" er forbeholdt 
Wikipedia udviklere.",
"querysuccessful" => "Forespørgsel gennemført med success",

# Move page
#
"movepage"		=> "Flyt side",
"movepagetext"	=> "Når du bruger formularen herunder vil du få omdøbt en 
side, flyttet hele sidens historie til det nye navn.
Den gamle titel vil blive en omdirigeringsside til den nye titel.
Links til den gamle titel vil ikke blive ændret. Sørg for at 
[[Speciel:Maintenance|tjekke]] for dobbelte eller dårlige omdirigeringer. 
Du er ansvarlig for, at alle links stadig peger på der hvor det er 
meningen de skal pege.

Bemærk at siden '''ikke''' kan flyttes hvis der allerede er en side 
med den nye titel, medmindre den side er tom eller er en omdirigering 
uden nogen historie. Det betyder at du kan flytte en side tilbage hvor 
den kom fra, hvis du kommer til at lave en fejl.

<b>ADVARSEL!</b>
Dette kan være en drastisk og uventet ændring for en populær side;
vær sikker på at du forstår konsekvenserne af dette før du
fortsætter.",
"movepagetalktext" => "Den tilhørende diskussionsside, hvis der er en, 
vil automatisk blive flyttet med siden '''medmindre:'''
*Du flytter siden til et andet navnerum,
*En ikke-tom diskussionsside allerede eksisterer under det nye navn, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfælde er du nødt til at flytte eller sammenflette siden manuelt.",
"movearticle"	=> "Flyt side",
"movenologin"	=> "Ikke logget på",
"movenologintext" => "Du skal være registreret bruger og være <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">logget på</a>
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
"movetalk"		=> "Flyt også \"diskussion\" siden, hvis den eksistere.",
"talkpagemoved" => "Den tilhørende diskussion side blev også flyttet.",
"talkpagenotmoved" => "Den tilhørende diskussion side blev 
<strong>ikke</strong> flyttet.",

);

class LanguageDa extends Language {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsDa ;
		return $wgDefaultUserOptionsDa ;
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

	function getUserToggles() {
		global $wgUserTogglesDa;
		return $wgUserTogglesDa;
	}

	function getLanguageNames() {
		global $wgLanguageNamesDa;
		return $wgLanguageNamesDa;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesDa;
		if ( ! array_key_exists( $code, $wgLanguageNamesDa ) ) {
			return "";
		}
		return $wgLanguageNamesDa[$code];
	}

	function getMonthName( $key )
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
		return $this->date( $ts, $adj ) . " kl." . $this->time( $ts, $adj );
	}

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
}

?>
