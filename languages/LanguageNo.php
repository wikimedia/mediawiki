<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesNo = array(
	NS_MEDIA          => "Medium",
	NS_SPECIAL        => "Spesial",
	NS_MAIN           => "",
	NS_TALK           => "Diskusjon",
	NS_USER           => "Bruker",
	NS_USER_TALK      => "Brukerdiskusjon",
	NS_PROJECT        => "Wikipedia",
	NS_PROJECT_TALK   => "Wikipedia-diskusjon",
	NS_IMAGE          => "Bilde",
	NS_IMAGE_TALK     => "Bildediskusjon",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki-diskusjon",
	NS_TEMPLATE       => "Mal",
	NS_TEMPLATE_TALK  => "Maldiskusjon",
	NS_HELP           => "Hjelp",
	NS_HELP_TALK      => "Hjelpdiskusjon",
	NS_CATEGORY       => "Kategori",
	NS_CATEGORY_TALK  => "Kategoridiskusjon",
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsNo = array(
	"Ingen", "Fast venstre", "Fast høyre", "Flytende venstre"
);

/* private */ $wgSkinNamesNo = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgi",
	'cologneblue' => "Kölnerblå",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);


/* private */ $wgBookstoreListNo = array(
	"Antikvariat.net" => "http://www.antikvariat.net/",
	"Bibsys" => "http://www.bibsys.no/",
	"Bokkilden" => "http://www.bokkilden.no/",
	"Haugenbok" => "http://www.haugenbok.no/",
	"Mao.no" => "http://www.mao.no/"
);


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesNo = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Brukerinnstillinger",
	"Watchlist"		=> "Overvåkningsliste",
	"Recentchanges" => "Siste endringer",
	"Upload"		=> "Last opp fil",
	"Imagelist"		=> "Billedliste",
	"Listusers"		=> "Registrerte brukere",

	"Statistics"	=> "Statistikk",
	"Randompage"	=> "Tilfeldig artikkel",

	"Lonelypages"	=> "Foreldreløse artikler",
	"Unusedimages"	=> "Foreldreløse filer",
	"Popularpages"	=> "Populære artikler",
	"Wantedpages"	=> "Mest etterspurte artikler",
	"Shortpages"	=> "Korte artikler",
	"Longpages"		=> "Lange artikler",
	"Newpages"		=> "Nyeste artikler",
	"Ancientpages"	=> "Eldste artikler",
	"Allpages"		=> "Alle sider etter tittel",

	"Ipblocklist"	=> "Blokkerte IP-adresser",
	"Maintenance" => "Vedlikeholdsside",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Bokkilder",
	"Export"		=> "XML export",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesNo = array(
	"Blockip"		=> "Blokker IP-adresse",
	"Asksql"		=> "Søk i databasen",
	"Undelete"		=> "Vis og gjenopprett slettede sider"
);

/* private */ $wgDeveloperSpecialPagesNo = array(
	"Lockdb"		=> "Skrivebeskytt databasen",
	"Unlockdb"		=> "Gjenopprett tilgang til databasen",
);

/* private */ $wgAllMessagesNo = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles

"tog-hover"		=> "Vis svevetekst over wiki-lenker",
"tog-underline" => "Strek under lenker",
"tog-highlightbroken" => "Røde lenker til tomme sider",
"tog-justify"	=> "Blokkjusterte avsnitt",
"tog-hideminor" => "Skjul mindre redigeringer i siste endringer",
"tog-usenewrc" => "Forbedret siste endringer (ikke for alle nettlesere)",
"tog-numberheadings" => "Nummerer overskrifter",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "Rediger sider med dobbeltklikk (JavaScript)",
"tog-editsection"=>"Rediger avsnitt ved hjelp av [rediger]-lenke",
"tog-editsectiononrightclick"=>"Rediger avsnitt ved å høyreklikke<br> på avsnittsoverskrift (JavaScript)",
"tog-showtoc"=>"Vis innholdsfortegnelse<br>(for artikler med mer enn tre avsnitt)",
"tog-rememberpassword" => "Husk passordet til neste gang",
"tog-editwidth" => "Redigeringsboksen har full bredde",
"tog-watchdefault" => "Overvåk nye og endrede artikler",
"tog-minordefault" => "Marker i utgangspunktet alle redigeringer som mindre",
"tog-previewontop" => "Vis forhåndsvisningen foran redigeringsboksen, og ikke etter den",
"nocache" => "Ikke husk sidene ved neste besøk",

# Dates
#

'sunday' => 'søndag',
'monday' => 'mandag',
'tuesday' => 'tirsdag',
'wednesday' => 'onsdag',
'thursday' => 'torsdag',
'friday' => 'fredag',
'saturday' => 'lørdag',
'january' => 'januar',
'february' => 'februar',
'march' => 'mars',
'april' => 'april',
'may_long' => 'mai',
'june' => 'juni',
'july' => 'juli',
'august' => 'august',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'des',

# Bits of text used by many pages:
#
"categories" => "Sidekategorier",
"category" => "kategori",
"category_header" => "Artikler i kategorien \"$1\"",
"subcategories" => "Underkategorier",

"linktrail"		=> "/^((?:[a-z]|æ|ø|å)+)(.*)\$/sD",
"mainpage"		=> "Hovedside",
"mainpagetext"	=> "Wiki-programvare er nå installert.",
"about"			=> "Om",
"aboutsite"      => "Om Wikipedia",
"aboutpage"		=> "Wikipedia:Om",
"help"			=> "Hjelp",
"helppage"		=> "Wikipedia:Hjelp",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Feilmeldinger",
"bugreportspage" => "Wikipedia:Feilmeldinger",
"sitesupport"   => "Donasjoner",
"sitesupportpage" => "", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "OSS",
"faqpage"		=> "Wikipedia:OSS",
"edithelp"		=> "Hjelp til redigering",
"edithelppage"	=> "Wikipedia:Hvordan_redigere",
"cancel"		=> "Avbryt",
"qbfind"		=> "Finn",
"qbbrowse"		=> "Søk",
"qbedit"		=> "Rediger",
"qbpageoptions" => "Sideinnstillinger",
"qbpageinfo"	=> "Sideinformasjon",
"qbmyoptions"	=> "Egne innstillinger",
"mypage"		=> "Egen side",
"mytalk"		=> "Egen diskusjonsside",
"currentevents" => "Aktuelt",
"errorpagetitle" => "Feil",
"returnto"		=> "Tilbake til $1.",
"tagline"      	=> "Fra Wikipedia, den frie encyklopedi.",
"whatlinkshere"	=> "Lenker hit",
"help"			=> "Hjelp",
"search"		=> "Søk",
"go"		=> "Utfør",
"history"		=> "Historikk",
"printableversion" => "Utskriftsversjon",
"editthispage"	=> "Rediger side",
"deletethispage" => "Slett side",
"protectthispage" => "Beskytt",
"unprotectthispage" => "Fjern beskyttelse",
"newpage" => "Ny side",
"talkpage"		=> "Diskuter side",
"postcomment"   => "Tilføy en kommentar",
"articlepage"	=> "Vis artikkel",
"subjectpage"	=> "Vis emne", # For compatibility
"userpage" => "Brukerside",
"wikipediapage" => "Metaside",
"imagepage" => 	"Billedside",
"viewtalkpage" => "Vis diskusjon",
"otherlanguages" => "Andre språk",
"redirectedfrom" => "(Omdirigert fra $1)",
"lastmodified"	=> "Sist endret $1.",
"viewcount"		=> "Denne siden er vist $1 ganger.",
"gnunote" => "Artikkelen er utgitt under <a class=internal href='$wgScriptPath/GNU_FDL'>GNU fri dokumentasjonslisens</a>.",
"printsubtitle" => "(fra http://no.wikipedia.org)",
"protectedpage" => "Beskyttet side",
"administrators" => "Wikipedia:Administratorer",
"sysoptitle"	=> "Sysop-rettigheter kreves",
"sysoptext"		=> "Funksjonen kan kun utføres av brukere med \"sysop\"-status.
Se $1.",
"developertitle" => "Utviklerrettigheter kreves.",
"developertext"	=> "Funksjonen kan kun utføres av brukere med \"utvikler\"-status.
Se $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Utfør",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Den frie encyklopedi",
"retrievedfrom" => "Hentet fra \"$1\"",
"newmessages" => "Du har $1.",
"newmessageslink" => "nye meldinger",
"editsection"=>"rediger",
"toc" => "Innholdsfortegnelse",
"showtoc" => "vis",
"hidetoc" => "skjul",
"thisisdeleted" => "Se eller gjenopprett $1?",
"restorelink" => "$1 slettede endringer",

# Main script and global functions
#
"nosuchaction"	=> "Funksjonen finnes ikke",
"nosuchactiontext" => " Wikipedia-programvaren klarer ikke å
gjenkjenne funksjonen som er spesifisert i URL-en",
"nosuchspecialpage" => "En slik spesialside finnes ikke",
"nospecialpagetext" => "Du har bedt om en spesialside som Wikipedia-programvaren ikke
klarer å gjenkjenne.",

# General errors
#
"error"			=> "Feil",
"databaseerror" => "Databasefeil",
"dberrortext"	=> "Det har skjedd en syntaksfeil i databasen.
Den sist forsøkte forespørsel var:
<blockquote><tt>$1</tt></blockquote>
fra funksjonen \"<tt>$2</tt>\".
MySQL returnerte feilen \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Det har skjedd en syntaksfeil i databasen.
Den sist forsøkte forespørselen var:
\"$1\"
fra funksjonen \"$2\".
MySQL returnerte feilen \"$3: $4\".\n",
"noconnect"		=> "Kunne ikke koble til databasen på $1",
"nodb"			=> "Kunne ikke velge databasen $1",
"cachederror"	=> "Det følgende er en lagret kopi av den ønskede siden, og er ikke nødvendigvis oppdatert.",
"readonly"		=> "Databasen er skrivebeskyttet",
"enterlockreason" => "Skriv en begrunnelse for skrivebeskyttelsen, inkludert et estimat på når den vil bli opphevet",
"readonlytext"	=> "Databasen er for øyeblikket skrivebeskyttet for nye sider og andre modifikasjoner, sannsynligvis for rutinemessig vedlikehold. Administratoren som stengte den har gitt denne forklaringen:
<p>$1",
"missingarticle" => "Databasen fant ikke teksten på en side den skulle ha funnet, med navnet \"$1\".
Dette er ikke en databasefeil, men sannsynligvis en programfeil
Send en rapport om dette til en administrator, oppgi adresse til siden.",
"internalerror" => "Intern feil",
"filecopyerror" => "Kunne ikke kopiere filen \"$1\" til \"$2\".",
"filerenameerror" => "Kunne ikke omdøpe filen \"$1\" til \"$2\".",
"filedeleteerror" => "Kunne ikke slette filen \"$1\".",
"filenotfound"	=> "Kunne ikke finne filen \"$1\".",
"unexpected"	=> "Uventet verdi: \"$1\"=\"$2\".",
"formerror"		=> "Feil: Kunne ikke sende skjema",	
"badarticleerror" => "Handlingen kan ikke utføres på denne siden.",
"cannotdelete"	=> "Kunne ikke slette filen. (den kan være slettet av noen andre.)",
"badtitle"		=> "Feil tittel",
"badtitletext"	=> "Den ønskede tittel var ulovlig, tom eller er galt lenket fra en Wikipedia på et annet språk.",
"perfdisabled" => "Beklager! Denne funksjon er midlertidig avbrutt av vedlikeholdsgrunner.",
"perfdisabledsub" => "Her er en lagret kopi fra $1:",
"viewsource" => "Vis kildekode",
"protectedtext" => "Denne siden er sperret for å hindre redigering; det
kan være flere grunner til dette, se
[[Wikipedia:Beskyttet side]].

Du kan se og kopiere kildekoden til denne siden:",

# Login and logout pages
#
"logouttitle"	=> "Logg ut",
"logouttext"	=> "Du er nå logget ut.
Du kan fortsette å bruke Wikipedia anonymt, eller du kan logge inn
igjen med samme konto eller med en annen en.\n",

"welcomecreation" => "<h2>Hjertelig velkommen til Wikipedia, $1!</h2><p>Vi har opprettet din brukerkonto.
Hvis du vil, kan du personliggjøre brukerinnstillingene.",

"loginpagetitle" => "Logg inn",
"yourname"		=> "Brukernavn",
"yourpassword"	=> "Passord",
"yourpasswordagain" => "Gjenta passord",
"newusersonly"	=> " (kun nye brukere)",
"remembermypassword" => "Husk passordet til neste gang.",
"loginproblem"	=> "<b>Du ble ikke logget inn.</b><br>Prøv igjen!",
"alreadyloggedin" => "<font color=red><b>Bruker $1 er allerede logget inn!</b></font><br>\n",

"areyounew"		=> "Hvis du er ny på Wikipedia og vil ha en brukerkonto, skriv inn et brukernavn og et passord, og bekreft passordet ved å skrive det inn en gang til.
E-postadresse er frivillig; hvis du oppgir det, kan du få passordet tilsendt om du glemmer det.<br>\n",

"login"			=> "Logg inn",
"userlogin"		=> "Logg inn",
"logout"		=> "Logg ut",
"userlogout"	=> "Logg ut",
"notloggedin"	=> "Ikke innlogget",
"createaccount"	=> "Opprett ny konto",
"badretype"		=> "Passordene var ikke like.",
"userexists"	=> "Brukernavnet er allerede i bruk. Velg et nytt.",
"youremail"		=> "E-postadresse",
"yournick"		=> "Økenavn (for signaturer)",
"emailforlost"	=> "Hvis du glemmer passordet, kan du få et nytt sendt til din e-postadresse.",
"loginerror"	=> "Innloggingsfeil",
"noname"		=> "Du har ikke oppgitt et gyldig brukernavn.",
"loginsuccesstitle" => "Du er nå innlogget",
"loginsuccess"	=> "Du er nå innlogget som \"$1\".",
"nosuchuser"	=> "Det eksisterer ingen bruker \"$1\".
Sjekk stavemåten, eller opprett en ny konto.",
"wrongpassword"	=> "Du har oppgitt et ugyldig passord. Prøv igjen.",
"mailmypassword" => "Send nytt passord.",
"passwordremindertitle" => "Nytt passord til Wikipedia",
"passwordremindertext" => "Noen (antagelig deg, fra IP-adressen $1)
ba oss sende deg et nytt passord til Wikipedia..
Passord for bruker \"$2\" er nå \"$3\".
Du bør logge inn og endre passordet nå.",
"noemail"		=> "Det er ikke registrert noen e-postadresse på bruker \"$1\".",
"passwordsent"	=> "Et nytt passord har blitt sendt til e-postadressen registrert på bruker \"$1\".
Logg inn når du har mottatt det nye passordet.",

# Edit pages
#
"summary"		=> "Beskrivelse",
"subject"		=> "Overskrift",
"minoredit"		=> "Mindre endring",
"watchthis"		=> "Overvåk side",
"savearticle"	=> "Lagre siden",
"preview"		=> "Forhåndsvisning",
"showpreview"	=> "Forhåndsvisning",
"blockedtitle"	=> "Brukeren er blokkert",
"blockedtext"	=> "Ditt brukernavn eller din IP-adresse er blokkert av $1.
Følgende begrunnelse ble gitt:<br>''$2''<p>Du kan kontakte $1 eller en annen
[[Wikipedia:Administratorer|administrator]] for å diskutere utestengelsen.",
"newarticle"	=> "(Ny)",
"newarticletext" =>
"Artikkelen inneholder ingen tekst.
Du kan begynne på en artikkel ved å skrive i boksen under
(se [[Wikipedia:Hjelp|hjelpsiden]] for mere informasjon).
Hvis du ikke vil redigere siden, klikk på '''tilbake''' i nettleseren.",
"anontalkpagetext" => "---- ''Dette er en diskusjonsside for en anonym bruker som ikke har opprettet en konto eller ikke bruker den. Vi er derfor nødt til å bruke den numeriske IP-adressen til å identifisere ham eller henne. En IP-adresse kan være delt mellom flere brukere. Hvis du er en anonym bruker og synes at du har fått irrelevante kommentarer på en slik side, [[Spesial:Innlogging|logg på]] så vi unngår fremtidige forvekslinger med andre anonyme brukere.'' ",
"noarticletext" => "(Det er for øyeblikket ingen tekst på denne siden.)",
"updated"		=> "(Oppdatert)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Husk at dette kun er en forhåndsvisning og at teksten ikke er lagret!",
"previewconflict" => "Slik vil teksten i redigeringsvinduet se ut hvis du lagrer den",
"editing"		=> "Redigerer $1",
"editingsection"	=> "Redigerer $1 (seksjon)",
"editingcomment"	=> "Redigerer $1 (kommentar)",
"editconflict"	=> "Redigeringskonflikt: $1",
"explainconflict" => "Noen andre har endret teksten siden du begynte å redigere.
Den øverste boksen inneholder den nåværende tekst.
Dine endringer vises i den nederste boksen.
Du er nødt til å flette dine endringer sammen med den nåværende teksten.
<b>Kun</b> teksten i den øverste tekstboksen vil bli lagret når du
trykker \"Lagre siden\".\n<p>",
"yourtext"		=> "Din tekst",
"storedversion" => "Den lagrede versjonen",
"editingold"	=> "<strong>ADVARSEL: Du redigerer en gammel versjon
av denne siden.
Hvis du lagrer den, vil alle endringer foretatt siden denne revisjonen bli 
overskrevet.</strong>\n",
"yourdiff"		=> "Forskjeller",
"copyrightwarning" => "Legg merke til at alle bidrag til Wikipedia er
å betrakte som utgitt under GNU fri dokumentasjonslisens
(se $1 for detaljer).
Hvis du ikke vil ha teksten redigert uten nåde og kopiert etter
forgodtbefinnende, kan du ikke legge den her.<br>
Du lover oss også at du skrev teksten selv eller kopierte fra en
ressurs som ikke er beskyttet av opphavsrett.

<strong>LEGG ALDRI MATERIALE HER SOM ER BESKYTTET AV ANDRES OPPHAVSRETT UTEN 
DERES TILLATELSE!</strong>",
"longpagewarning" => "ADVARSEL: Denne siden er $1 kilobyte lang; noen
nettlesere kan ha problemer med å redigere sider som nærmer seg eller 
er lengre enn 32kb. Overvei om ikke siden kan deles opp i mindre deler.",
"readonlywarning" => "ADVARSEL: Databasen er låst på grunn av vedlikehold,
så du kan ikke lagre dine endringer akkurat nå. Det kan være en god idé å 
kopiere teksten din til en tekstfil, så du kan lagre den til senere.",
"protectedpagewarning" => "ADVARSEL: Denne siden er låst, så kun administratorer
kan redigere den. Sørg for at du følger 
<a href='$wgScriptPath/$wgMetaNamespace:Retningslinjer_for_beskyttede_sider'>retningslinjer for 
beskyttede sider</a>.",

# History pages
#
"revhistory"	=> "Historikk",
"nohistory"		=> "Det er ingen historikk for denne siden.",
"revnotfound"	=> "Versjonen er ikke funnet",
"revnotfoundtext" => "Den gamle versjonen av den siden du spurte etter, finnes ikke. Kontroller den URL-en du brukte for å få adgang til denne siden.\n",
"loadhist"		=> "Laster historikk",
"currentrev"	=> "Nåværende versjon",
"revisionasof"	=> "Versjonen fra $1",
"cur"			=> "nå",
"next"			=> "neste",
"last"			=> "forrige",
"orig"			=> "original",
"histlegend"	=> "Forklaring: (nå) = forskjell fra den nåværende 
versjonen, (forrige) = forskjell fra den forrige versjonen, M = mindre endring",

# Diffs
#
"difference"	=> "(Forskjeller mellom versjoner)",
"loadingrev"	=> "laster versjon for å se forskjeller",
"lineno"		=> "Linje $1:",
"editcurrent"	=> "Rediger den nåværende versjonen av denne siden",

# Search results
#
"searchresults" => "Søkeresultater",
"searchresulttext" => "For mer informasjon om søking i Wikipedia, se 
$1.",
"searchquery"	=> "For forespørsel \"$1\"",
"badquery"		=> "Galt utformet forespørsel",
"badquerytext"	=> "Vi kunne ikke utføre forespørselen.
Det er sannsynligvis fordi du har forsøkt å søke etter et ord med
færre enn tre bokstaver, hvilket ikke støttes ennå.
Det kan også være du har skrevet feil.
Prøv igjen.",
"matchtotals"	=> "Forespørselen \"$1\" ga treff på $2 artikkeltitler
og på teksten i $3 artikler.",
"nogomatch" => "Ingen sider med akkurat denne tittelen eksisterer, prøver 
fulltekstsøking i stedet. ",
"titlematches"	=> "Artikkeltitler med treff på forespørselen",
"notitlematches" => "Ingen artikkeltitler hadde treff på forespørselen",
"textmatches"	=> "Artikkeltekster med treff på forespørselen",
"notextmatches"	=> "Ingen artikkeltekster hadde treff på forespørselen",
"prevn"			=> "forrige $1",
"nextn"			=> "neste $1",
"viewprevnext"	=> "Vis ($1) ($2) ($3).",
"showingresults" => "Nedenfor vises <b>$1</b> resultater som starter med 
nummer <b>$2</b>.",
"showingresultsnum" => "Nedenfor vises <b>$3</b> resultater som starter med nummer <b>$2</b>.",
"nonefound"		=> "<strong>NB</strong>: søk uten resultat skyldes 
at man søker etter alminnelige ord som \"har\" og \"fra\",
som ikke er indeksert, eller ved å spesifisere mer enn et søkeord (da kun 
sider som inneholder alle søkeordene vil bli funnet).",
"powersearch" => "Søk",
"powersearchtext" => "
Søk i navnerom :<br>
$1<br>
$2 List opp omdirigeringer   Søk etter $3 $9",
"searchdisabled" => "<p>Søkefunksjonen er midlertidig avbrutt på grunn av
for stort press på tjeneren; vi håper vi kan sette den på igjen når vi har
oppgradert programvaren. I mellomtiden kan du søke via Google:</p>
                                                                                                                                                        
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
"preferences"	=> "Innstillinger",
"prefsnologin" => "Ikke logget inn",
"prefsnologintext"	=> "Du må være <a href=\"" .
  "{{localurle:Spesial:Userlogin}}\">logget inn</a>
for å endre brukerinnstillingene.",
"prefslogintext" => "Du logget inn som \"$1\".
Ditt interne ID-nummer er $2.

Se [[Wikipedia:Hvordan stille inn brukerinnstillinger]] for en forklaring på de forskjellige brukerinnstillingene.",
"prefsreset"	=> "Brukerinnstillingene er tilbakestilt.",
"qbsettings"	=> "Brukerinnstillinger for hurtigmeny",
"changepassword" => "Skift passord",
"skin"			=> "Utseende",
"math"			=> "Vis matematiske formler",
"dateformat"	=> "Datoformat",
"math_failure"		=> "Feil i matematikken",
"math_unknown_error"	=> "ukjent feil",
"math_unknown_function"	=> "ukjent funksjon ",
"math_lexing_error"	=> "lexerfeil",
"math_syntax_error"	=> "syntaksfeil",
"saveprefs"		=> "Lagre brukerinnstillinger",
"resetprefs"	=> "Tilbakestill brukerinnstillinger",
"oldpassword"	=> "Gammelt passord",
"newpassword"	=> "Nytt passord",
"retypenew"		=> "Gjenta nytt passord",
"textboxsize"	=> "Tekstboks-størrelse",
"rows"			=> "Rekker",
"columns"		=> "Kolonner",
"searchresultshead" => "Brukerinnstillinger for søkeresultater",
"resultsperpage" => "Resultater per side",
"contextlines"	=> "Linjer per resultat",
"contextchars"	=> "Tegn per linje i resultatet",
"stubthreshold" => "Grense for visning av småartikler",
"recentchangescount" => "Antall titler på siden \"siste endringer\"",
"savedprefs"	=> "Brukerinnstillingene er lagret.",
"timezonetext"	=> "Tast inn antall timer lokal tid er forskjellig
fra tjenerens tid.",
"localtime"	=> "Lokaltid",
"timezoneoffset" => "Forskjell",
"servertime"	=> "Tjenerens tid er nå",
"guesstimezone" => "Hent tidssone fra nettleseren",
"emailflag"	=> "Ikke ta imot e-post fra andre brukere",
"defaultns"		=> "Søk som standard i disse navnerom:",

# Recent changes
#
"changes" => "endringer",
"recentchanges" => "Siste endringer",
# This is the default text, and can be overriden by editing [[Wikipedia::Recentchanges]]
"recentchangestext" => "Se de sist endrede sider i Wikipedia på denne siden.",
"rcloaderr"		=> "Laster sist endrede sider",
"rcnote"		=> "Nedenfor er de siste <strong>$1</strong> endringer i de 
siste <strong>$2</strong> dagene.",
"rcnotefrom"	=> "Nedenfor er endringene fra <b>$2</b> inntil <b>$1</b> vist.",
"rclistfrom"	=> "Vis nye endringer med start fra $1",
"rclinks"		=> "Vis seneste $1 endringer i de siste $2 dager; $3 mindre endringer.",
"rchide"		=> "i $4 form; $1 mindre endringer; $2 andre navnerom; $3 mer enn én redigering.",
"rcliu"			=> "; $1 redigeringer fra brukere som er logget inn",
"diff"			=> "forskjell",
"hist"			=> "historikk",
"hide"			=> "skjul",
"show"			=> "vis",
"tableform"		=> "tabell",
"listform"		=> "liste",
"nchanges"		=> "$1 endringer",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Last opp fil",
"uploadbtn"		=> "Last opp fil",
"uploadlink"	=> "Last opp fil",
"reupload"		=> "Last opp fil igjen",
"reuploaddesc"	=> "Tilbake til skjemaet for å laste opp filer.",
"uploadnologin" => "Ikke logget inn",
"uploadnologintext"	=> "Du må være <a href=\"" .
  "{{localurle:Spesial:Userlogin}}\">logget inn</a>
for å kunne laste opp filer.",
"uploadfile"	=> "Last opp filer",
"uploaderror"	=> "Feil under opplasting av fil",
"uploadtext"	=> "'''STOPP!''' Før du laster opp filer her,
vær sikker på du har lest og følger Wikipedias
[[Project:Retningslinjer for billedbruk|retningslinjer for billedbruk]].

For å se eller søke i bilder som tidligere er lastet opp,
gå til [[Spesial:Imagelist|listen over bilder]].
Opplasting og slettinger er registrert i
[[Project:Upload_log|loggen over opplastede filer]].

Bruk skjemaet nedenunder til å laste opp nye bilder som kan brukes
til å illustrere dine artikler.
På de fleste nettlesere vil du se en \"Browse...\"-knapp eller en 
\"Bla igjennom...\"-knapp, som vil
bringe deg til operativsystemets standarddialog for å åpne filer.
Når du velger en fil, vil navnet på filen dukke opp i tekstfeltet
ved siden av knappen.
Du må også verifisere at du ikke bryter noens opphavsrett.
Det gjør du ved å krysse av i boksen.
Trykk på \"Last opp\"-knappen for å laste opp filen.
Dette kan godt ta litt tid hvis du har en langsom internettforbindelse.

De foretrukne formatene er JPEG til fotografiske bilder, PNG
til tegninger og andre små bilder, og OGG til lyd.
Sørg for å gi filen et beskrivende navn for å unngå 
forvirring om innholdet.
For å bruke bildet i en artikkel, bruk en lenke av dette slaget:

'''<nowiki>[[bilde:fil.jpg]]</nowiki>''' eller
'''<nowiki>[[bilde:fil.png|alternativ tekst]]</nowiki>''' eller
'''<nowiki>[[medium:fil.ogg]]</nowiki>''' for lyd.

Legg merke til at akkurat som med Wikipedia-sider, kan andre gjerne 
redigere eller
slette de filene du har lastet opp, hvis de mener det hjelper encyklopedien, og
du kan bli blokkert fra å laste opp hvis du misbruker systemet.",
"uploadlog"		=> "opplastingslogg",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Her er en liste med de filene som er lastet 
opp sist. Alle de viste tidene er tjenerens tid (UTC).
<ul>
</ul>
",
"filename"		=> "Filnavn",
"filedesc"		=> "Beskrivelse",
"affirmation"	=> "Jeg bekrefter at opphavsrettsinnehaveren til denne filen
samtykker i at filen utgis under betingelsene for $1.",
"copyrightpage" => "Wikipedia:Opphavsrett",
"copyrightpagename" => "Wikipedia opphavsrett",
"uploadedfiles"	=> "Filer som er lastet opp",
"noaffirmation" => "Du må bekrefte at du ikke bryter noens opphavsrett
ved å laste opp denne filen.",
"ignorewarning"	=> "Ignorer advarselen og lagre filen likevel.",
"minlength"		=> "Navnet på filen må bestå av minst tre bokstaver.",
"badfilename"	=> "Navnet på filen er blitt endret til \"$1\".",
"badfiletype"	=> "\".$1\" er ikke et av de anbefalte filformatene.",
"largefile"		=> "Det anbefales at filer ikke er større enn 100kb.",
"successfulupload" => "Opplastingen er gjennomført",
"fileuploaded"	=> "Filen \"$1\" er lastet opp med.
Følg denne lenken: ($2) til siden med beskrivelse og fyll ut
informasjon omkring filen, slik som hvor den kom fra, når den er laget
og av hvem, og andre ting du vet om filen.",
"uploadwarning" => "Opplastingsadvarsel",
"savefile"		=> "Lagre fil",
"uploadedimage" => "Lastet opp \"$1\"",
"uploaddisabled" => "Beklager, muligheten for opplasting er deaktivert på denne tjeneren.",

# Image list
#
"imagelist"		=> "Billedliste",
"imagelisttext"	=> "Her er en liste med $1 bilder sortert $2.",
"getimagelist"	=> "henter billedliste",
"ilshowmatch"	=> "Vis alle treff på bilder ved navn",
"ilsubmit"		=> "Søk",
"showlast"		=> "Vis de siste $1 bilder sortert $2.",
"all"			=> "alle",
"byname"		=> "etter navn",
"bydate"		=> "etter dato",
"bysize"		=> "etter størrelse",
"imgdelete"		=> "slett",
"imgdesc"		=> "beskrivelse",
"imglegend"		=> "Forklaring: (beskrivelse) = vis/rediger bildebeskrivelse.",
"imghistory"	=> "Billedhistorikk",
"revertimg"		=> "gjenopprett",
"deleteimg"		=> "slett",
"deleteimgcompletely"		=> "slett",
"imghistlegend" => "Forklaring: (nå) = dette er det nåværende bilde, 
(slett) = slett denne gamle versjonen, (gjenopprett) = gjenopprett en gammel versjon.
<br><i>Klikk på en dato for å se bildet som ble lastet opp da</i>.",
"imagelinks"	=> "Billedlenker",
"linkstoimage"	=> "De følgende sider har lenker til dette bildet:",
"nolinkstoimage" => "Det er ingen sider som har lenker til dette bildet.",

# Statistics
#
"statistics"	=> "Statistikk",
"sitestats"		=> "Wikipedia-statistikk",
"userstats"		=> "Brukerstatistikk",
"sitestatstext" => "Der er i alt <b>$1</b> sider i databasen.
Dette inkluderer diskusjonssider, sider om Wikipedia, 
omdirigeringssider, og andre som sikkert ikke kvalifiserer til å være artikler.
Hvis man ekskluderer disse, er det <b>$2</b> sider som sannsynligvis er 
ordinære artikler.<p>
Der har i alt vært <b>$3</b> viste sider, og <b>$4</b> redigeringer av sider
siden programvaren ble oppdatert (25. september 2002).
Det vil si at det har vært <b>$5</b> gjennomsnittlige redigeringer per side, 
og <b>$6</b> visninger per redigering.",
"userstatstext" => "Der er  <b>$1</b> registrerte brukere.
<b>$2</b> av disse er administratorer (se $3).",

# Maintenance Page
#
"maintenance"		=> "Vedlikeholdsside",
"maintnancepagetext"	=> "På denne siden er det forskjellige
verktøyer for å vedlikeholde Wikipedia. Noen av disse funksjonene er 
harde for databasen (de tar lang tid), så la være å oppdatere siden 
hver gang du har rettet en enkelt ting",
"maintenancebacklink"	=> "Tilbake til vedlikeholdssiden",
"disambiguations"	=> "Artikler med flertydige titler",
"disambiguationspage"	=> "Wikipedia:Lenker til artikler med flertydige titler",
"disambiguationstext"	=> "De følgende artikler har lenker til 
<i>artikler med flertydige titler</i>. De burde ha lenke til en ikke-flertydig 
tittel i stedet.<br>En artikkel blir behandlet som flertydig hvis den har
lenker fra $1.<br>Lenker fra andre navnerom er <i>ikke</i> listet her.",
"doubleredirects"	=> "Dobbelte omdirigeringer",
"doubleredirectstext"	=> "<b>NB:</b> Denne listen kan inneholde gale 
resultater. Det er som regel fordi siden inneholder ekstra tekst under den
første #REDIRECT.<br>\nHver linje inneholder lenker til den første og den 
anden omdirigeringen, og den første linjen fra den andre omdirigeringsteksten. 
Det gir som regel den \"riktige\" målartikkelen, som den første omdirigeringen 
skulle ha pekt på.",
"brokenredirects"	=> "Dårlige omdirigeringer",
"brokenredirectstext"	=> "De følgende omdirigeringer peker på en side som 
ikke eksisterer.",
"selflinks"		=> "Sider som henviser til seg selv",
"selflinkstext"		=> "De følgende sider inneholder henvisninger til seg selv, 
men det burde de ikke.",
"mispeelings"           => "Sider med stavefeil",
"mispeelingstext"               => "De følgende sider inneholder en av de 
alminnelige stavefeilene som er listet på $1. Den korrekte stavemåte kan 
angis i paranteser etter den feilaktige stavemåten (som dette).",
"mispeelingspage"       => "Liste over alminnelige stavefeil",
"missinglanguagelinks"  => "Manglende språklenker",
"missinglanguagelinksbutton"    => "Finn manglende språklenker for",
"missinglanguagelinkstext"      => "Disse artiklene har <i>ikke</i> noen 
lenker til den samme artikkel i $1. Omdirigeringer og underartikler er 
<i>ikke</i> vist.",


# Miscellaneous special pages
#
"orphans"		=> "Foreldreløse sider",
"lonelypages"	=> "Foreldreløse sider",
"unusedimages"	=> "Ubrukte bilder",
"popularpages"	=> "Populære sider",
"nviews"		=> "$1 visninger",
"wantedpages"	=> "Etterspurte sider",
"nlinks"		=> "$1 lenker",
"allpages"		=> "Alle sider",
"randompage"	=> "Tilfeldig side",
"shortpages"	=> "Korte sider",
"longpages"		=> "Lange sider",
"listusers"		=> "Brukerliste",
"specialpages"	=> "Spesialsider",
"spheading"		=> "Spesialsider for alle brukere",
"sysopspheading" => "Kun for sysop-bruk",
"developerspheading" => "Kun for utvikler-bruk",
"protectpage"	=> "Beskytt side",
"recentchangeslinked" => "Relaterte endringer",
"rclsub"		=> "(til sider med lenke fra \"$1\")",
"debug"			=> "Fiks feil",
"newpages"		=> "Nye sider",
"ancientpages"		=> "Eldste sider",
"intl"		=> "Språklenker",
"movethispage"	=> "Flytt side",
"unusedimagestext" => "<p>Legg merke til at andre internettsider
slik som de andre internasjonale Wikipediaene kanskje har lenker til et bilde med
en direkte URL, og kan være listet opp her, selv om det er
i aktiv bruk.",
"booksources"	=> "Bokkilder",
"booksourcetext" => "Her er en liste over lenker til steder som
låner ut og/eller selger nye og brukte bøker, og som kanskje også har 
ytterligere informasjon om bøker du leter etter.
Wikipedia er ikke assosiert med noen av disse stedene,
og denne listen skal ikke sees på som en anbefaling av disse.",
"alphaindexline" => "$1 til $2",

# Email this user
#
"mailnologin"	=> "Ingen avsenderadresse",
"mailnologintext" => "Du må være <a href=\"" .
  "{{localurle:Spesial:Userlogin}}\">logget inn</a>
og ha en gyldig e-postadresse satt i <a href=\"" .
  "{{localurle:Spesial:Preferences}}\">brukerinnstillingene</a>
for å sende e-post til andre brukere.",
"emailuser"		=> "E-post til denne brukeren",
"emailpage"		=> "E-post til bruker",
"emailpagetext"	=> "Hvis denne brukeren har oppgitt en gyldig e-postadresse i
sine brukerinnstillinger, vil dette skjemaet sende en enkelt 
beskjed.
Den e-postadressen du har satt i brukerinnstillingene dine, vil dukke opp
i \"Fra\"-feltet på denne e-posten, så mottageren er i stand til å svare.",
"noemailtitle"	=> "Ingen e-postadresse",
"noemailtext"	=> "Denne brukeren har ikke oppgitt en gyldig e-postadresse,
eller har valgt å ikke motta e-post fra andre brukere.",
"emailfrom"		=> "Fra",
"emailto"		=> "Til",
"emailsubject"	=> "Emne",
"emailmessage"	=> "Beskjed",
"emailsend"		=> "Send",
"emailsent"		=> "E-posten sendt",
"emailsenttext" => "E-postbeskjeden er sendt.",

# Watchlist
#
"watchlist"		=> "Overvåkningsliste",
"watchlistsub"	=> "(for bruker \"$1\")",
"nowatchlist"	=> "Du har ingenting i overvåkningslisten.",
"watchnologin"	=> "Ikke logget inn",
"watchnologintext"	=> "Du må være <a href=\"" .
  "{{localurle:Spesial:Userlogin}}\">logget inn</a>
for å kunne endre overvåkningslisten.",
"addedwatch"	=> "Tilføyd til overvåkningslisten",
"addedwatchtext" => "Siden \"$1\" er tilføyd <a href=\"" .
  "{{localurle:Spesial:Watchlist}}\">overvåkningslisten</a>.
Fremtidige endringer til denne siden og den tilhørende diskusjonssiden vil 
bli listet opp her, og siden vil fremstå <b>fremhevet</b> i <a href=\"" .
  "{{localurle:Spesial:Recentchanges}}\">listen med de siste 
endringene</a> for å gjøre det lettere å finne den.</p>

<p>Hvis du senere vil fjerne siden fra overvåkningslisten, klikk
\"Fjern overvåkning\" ute i siden.",
"removedwatch"	=> "Fjernet fra overvåkningslisten",
"removedwatchtext" => "Siden \"$1\" er fjernet fra 
overvåkningslisten.",
"watchthispage"	=> "Overvåk side",
"unwatchthispage" => "Fjern overvåkning",
"notanarticle"	=> "Ikke en artikkel",
"watchnochange" => "Ingen av sidene i overvåkningslisten er endret i den valgte perioden.",
"watchdetails" => "($1 sider i overvåkningslisten, fratrukket alle diskusjonssidene;
$2 totalt antall sider endret i den valgte perioden;
$3...
<a href='$4'>vis og rediger den komplette listen</a>.)",
"watchmethod-recent" => "sjekker siste endringer for sider i overvåkningslisten",
"watchmethod-list" => "sjekker siste endringer for sider i overvåkningslisten",
"removechecked" => "Fjern valgte sider fra overvåkningslisten",
"watchlistcontains" => "Overvåkningslisten inneholder $1 sider.",
"watcheditlist" => "Her er en alfabetisk liste over sidene i overvåkningslisten.
Velg de sidene du vil fjerne fra overvåkningslisten 
og klikk på 'fjern valgte sider fra overvåkningslisten'-knappen
i bunnen av skjermen.",
"removingchecked" => "Fjerner de valgte sidene fra overvåkningslisten ...",
"couldntremove" => "Kunne ikke fjerne '$1'...",
"iteminvalidname" => "Problem med '$1', ugyldig navn...",
"wlnote" => "Nedenfor er de siste $1 endringer i de siste <b>$2</b> timer.",


# Delete/protect/revert
#
"deletepage"	=> "Slett side",
"confirm"		=> "Bekreft",
"excontent" => "innholdet var:",
"exbeforeblank" => "innholdet før siden ble tømt var:",
"exblank" => "siden var tom",
"confirmdelete" => "Bekreft sletting",
"deletesub"		=> "(Sletter \"$1\")",
"historywarning" => "Advarsel: Siden du holder på å slette har en historikk: ",
"confirmdeletetext" => "Du holder på å permanent slette en side
eller et bilde sammen med hele den tilhørende historikken fra databasen.
Bekreft at du virkelig vil gjøre dette, at du forstår
konsekvensene, og at du gjør dette i overensstemmelse med
[[Wikipedia:Retningslinjer]].",
"confirmcheck"	=> "Ja, jeg vil virkelig slette.",
"actioncomplete" => "Gjennomført",
"deletedtext"	=> "\"$1\" er slettet.
Se $2 for en oversikt over de nyeste slettinger.",
"deletedarticle" => "slettet \"$1\"",
"dellogpage"	=> "Slettingslogg",
"dellogpagetext" => "Her er en liste over de nyeste slettinger.
Alle tider er serverens tid (UTC).
<ul>
</ul>
",
"deletionlog"	=> "slettingslogg",
"reverted"		=> "Gjenopprettet en tidligere versjon",
"deletecomment"	=> "Begrunnelse for sletting",
"imagereverted" => "Gjenopprettelse av en tidligere versjon gjennomført.",
"rollback"		=> "Fjern redigeringer",
"rollbacklink"	=> "fjern redigering",
"rollbackfailed" => "Kunne ikke fjerne redigeringen",
"cantrollback"	=> "Kan ikke fjerne redigering; 
den siste brukeren er den eneste forfatteren.",
"alreadyrolled"	=> "Kan ikke fjerne den siste redigeringen av [[$1]]
foretatt av [[Bruker:$2|$2]] ([[Brukerdiskusjon:$2|diskusjon]]); 
en annen har allerede redigert siden eller fjernet redigeringen. 

Den siste redigeringen er foretatt av [[Bruker:$3|$3]] ([[Brukerdiskusjon:$3|diskusjon]]). ",
#   only shown if there is an edit comment
"editcomment" => "Kommentaren til redigeringen var: \"<i>$1</i>\".", 
"revertpage"	=> "Gjenopprettet siden til tidligere versjon redigert av $1",
"protectlogpage" => "Beskyttelseslogg",
"protectlogtext" => "Her er en liste over sider som er blitt beskyttet eller har fått fjernet beskyttelsen.
Se [[Wikipedia:Beskyttet side]] for mer informasjon.",
"protectedarticle" => "beskyttet $1",
"unprotectedarticle" => "fjernet beskyttelse for $1",

# Undelete
"undelete" => "Gjenopprett en slettet side",
"undeletepage" => "Se og gjenopprett slettede sider",
"undeletepagetext" => "De følgende sider er slettet, men de finnes 
stadig i arkivet og kan gjenopprettes. Arkivet blir periodevis slettet.",
"undeletearticle" => "Gjenopprett slettet artikkel",
"undeleterevisions" => "$1 revisjoner arkivert",
"undeletehistory" => "Hvis du gjenoppretter siden, vil alle de historiske 
revisjoner også bli gjenopprettet. Hvis en ny side med det samme navnet 
er opprettet siden denne ble slettet, vil de gjenopprettede revisjonene 
dukke opp i den tidligere historikken, og den nyeste revisjonen vil forbli 
på siden.",
"undeleterevision" => "Slettet versjon fra $1",
"undeletebtn" => "Gjenopprett!",
"undeletedarticle" => "gjenopprettet \"$1\"",
"undeletedtext"   => "Artikkelen [[$1]] er gjenopprettet.
Se [[Wikipedia:Slettingslogg]] for en oversikt over nylige 
slettinger og gjenopprettelser.",

# Contributions
#
"contributions"	=> "Brukerbidrag",
"mycontris" => "Egne bidrag",
"contribsub"	=> "For $1",
"nocontribs"	=> "Ingen endringer er funnet som passer disse kriteriene.",

"ucnote"	=> "Her er denne brukerens siste <b>$1</b> endringer i de 
siste <b>$2</b> dagene.",
"uclinks"	=> "Vis de siste $1 endringene; vis de siste $2 dagene.",
"uctop"		=> " (topp)" ,

# What links here
#
"whatlinkshere"	=> "Lenker hit",
"notargettitle" => "Intet mål",
"notargettext"	=> "Du har ikke spesifisert en målside eller bruker
å utføre denne funksjonen på.",
"linklistsub"	=> "(Liste over lenker)",
"linkshere"	=> "De følgende sider har lenker hit:",
"nolinkshere"	=> "Ingen sider har lenker hit.",
"isredirect"	=> "omdirigeringsside",

# Block/unblock IP
#
"blockip"		=> "Blokker IP-adresse",
"blockiptext"	=> "Bruk skjemaet nedenunder for å blokkere skriveadgangen
fra en spesifikk IP-adresse.
Dette må kun gjøres for at forhindre vandalisme, og i
overensstemmelse med [[Wikipedia:Retningslinjer|retningslinjene]].
Fyll ut en spesiell begrunnelse nedenunder (for eksempel med et sitat fra
sider som har vært utsatt for vandalisme).",
"ipaddress"		=> "IP-adresse",
"ipbreason"		=> "Begrunnelse",
"ipbsubmit"		=> "Blokker denne adressen",
"badipaddress"	=> "IP-adressen er galt utformet.",
"noblockreason" => "Du må angi en begrunnelse for denne blokkeringen.",
"blockipsuccesssub" => "Blokkering utført",
"blockipsuccesstext" => "IP-adressen \"$1\" er blokkert.
<br>Se [[Spesial:Ipblocklist|IP-blokkeringslisten]] for alle blokkeringer.",
"unblockip"		=> "Opphev blokkeringen av IP-adresse",
"unblockiptext"	=> "Bruk skjemaet nedenunder for å gjenopprette skriveadgangen
for en tidligere blokkert IP-adresse.",
"ipusubmit"		=> "Opphev blokkeringen av denne adresse",
"ipusuccess"	=> "IP-adressen \"$1\" har fått opphevet blokkeringen",
"ipblocklist"	=> "Liste over blokkerte IP-adresser",
"blocklistline"	=> "$1, $2 blokkerte $3",
"blocklink"		=> "blokker",
"unblocklink"	=> "opphev blokkering",
"contribslink"	=> "bidrag",
"autoblocker"	=> "Automatisk blokkert fordi du deler IP-adresse med \"$1\". Begrunnelse \"$2\".",

# Developer tools
#
"lockdb"		=> "Lås database",
"unlockdb"		=> "Lås opp database",
"lockdbtext"	=> "Å låse databasen vil avbryte alle brukere fra å kunne
redigere sider, endre deres innstillinger, redigere deres 
overvåkningsliste, og andre ting som krever endringer i databasen.
Bekreft at du har til hensikt å gjøre dette, og at du vil
låse opp databasen når vedlikeholdet er utført.",
"unlockdbtext"	=> "Å låse opp databasen vil si at alle brukere igjen 
kan redigere sider, endre sine innstillinger, redigere sin 
overvåkningsliste, og andre ting som krever endringer i databasen.
Bekreft at du har til hensikt å gjøre dette.",
"lockconfirm"	=> "Ja, jeg vil virkelig låse databasen.",
"unlockconfirm"	=> "Ja, jeg vil virkelig låse opp databasen.",
"lockbtn"		=> "Lås databasen",
"unlockbtn"		=> "Lås opp databasen",
"locknoconfirm" => "Du har ikke bekreftet handlingen.",
"lockdbsuccesssub" => "Databasen er nå låst",
"unlockdbsuccesssub" => "Databasen er nå låst opp",
"lockdbsuccesstext" => "Wikipedia-databasen er låst.
<br>Husk å fjerne låsen når du er ferdig med vedlikeholdet.",
"unlockdbsuccesstext" => "Wikipedia-databasen er låst opp.",

# SQL query
#
"asksql"		=> "SQL-forespørsel",
"asksqltext"	=> "Bruk skjemaet nedenunder for direkte forespørsler 
i Wikipedia-databasen.
Bruk enkle anførselstegn ('som dette') for å skille strenger.
Dette kan ofte belaste serveren kraftig, så bruk denne funksjonen
med omtanke.",
"sqlislogged"	=> "Vær oppmerksom på at alle SQL-forespørsler lagres i en loggfil.",
"sqlquery"		=> "Tast inn forespørsel",
"querybtn"		=> "Send forespørsel",
"selectonly"	=> "Forespørsler andre enn \"SELECT\" er forbeholdt 
Wikipedia-utviklere.",
"querysuccessful" => "Forespørsel gjennomført",

# Move page
#
"movepage"		=> "Flytt side",
"movepagetext"	=> "Når du bruker skjemaet nedenunder, vil du få omdøpt en 
side og flyttet hele historikken til det nye navnet.
Den gamle tittelen vil bli en omdirigeringsside til den nye tittelen.
Lenker til den gamle tittelen vil ikke bli endret. Sørg for å 
[[Spesial:Maintenance|sjekke]] for dobbelte eller dårlige omdirigeringer. 
Du er ansvarlig for at alle lenker stadig peker dit det er 
meningen de skal peke.

Legg merke til at siden '''ikke''' kan flyttes hvis det allerede finnes en side 
med den nye tittelen, med mindre den siden er tom eller er en omdirigering 
uten noen historikk. Det betyr at du kan flytte en side tilbake dit
den kom fra hvis du gjør en feil.

<b>ADVARSEL!</b>
Dette kan være en drastisk og uventet endring for en populær side;
vær sikker på at du forstår konsekvensene av dette før du
fortsetter.",
"movepagetalktext" => "Den tilhørende diskusjonssiden, hvis det finnes en, 
vil automatisk bli flyttet med siden '''med mindre:'''
*Du flytter siden til et annet navnerom,
*En ikke-tom diskusjonsside allerede eksisterer under det nye navnet, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfellene er du nødt til å flytte eller flette sammen siden manuelt.",
"movearticle"	=> "Flytt side",
"movenologin"	=> "Ikke logget inn",
"movenologintext" => "Du må være registrert bruker og være <a href=\"" .
  "{{localurle:Spesial:Userlogin}}\">logget på</a>
for å flytte en side.",
"newtitle"		=> "Til ny tittel",
"movepagebtn"	=> "Flytt side",
"pagemovedsub"	=> "Flytting gjennomført",
"pagemovedtext" => "Siden \"[[$1]]\" er flyttet til \"[[$2]]\".",
"articleexists" => "En side med det navnet eksisterer allerede, eller det
navnet du har valgt, er ikke gyldig. Velg et annet navn.",
"talkexists"	=> "Siden ble flyttet korrekt, men den tilhørende 
diskusjonssiden kunne ikke flyttes, fordi det allerede eksisterer en 
med den nye tittelen. Du er nødt til å flette dem sammen manuelt.",
"movedto"		=> "flyttet til",
"movetalk"		=> "Flytt også diskusjonssiden, hvis den eksisterer.",
"talkpagemoved" => "Den tilhørende diskusjonssiden ble også flyttet.",
"talkpagenotmoved" => "Den tilhørende diskusjonssiden ble 
<strong>ikke</strong> flyttet.",
# Math
	'mw_math_png' => "Vis alltid som PNG",
	'mw_math_simple' => "HTML hvis veldig enkel, ellers PNG",
	'mw_math_html' => "HTML hvis mulig, ellers PNG",
	'mw_math_source' => "Behold som TeX (for tekst-nettlesere)",
	'mw_math_modern' => "Anbefalt for moderne nettlesere",
	'mw_math_mathml' => 'MathML',

);

require_once( "LanguageUtf8.php" );

class LanguageNo extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListNo ;
		return $wgBookstoreListNo ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesNo;
		return $wgNamespaceNamesNo;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesNo;
		return $wgNamespaceNamesNo[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesNo;

		foreach ( $wgNamespaceNamesNo as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNo;
		return $wgQuickbarSettingsNo;
	}

	function getSkinNames() {
		global $wgSkinNamesNo;
		return $wgSkinNamesNo;
	}

	function getDateFormats() {
		global $wgDateFormatsNo;
		return $wgDateFormatsNo;
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

	# Inherit rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesNo;
		return $wgValidSpecialPagesNo;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesNo;
		return $wgSysopSpecialPagesNo;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesNo;
		return $wgDeveloperSpecialPagesNo;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesNo;
		if( isset( $wgAllMessagesNo[$key] ) ) {
			return $wgAllMessagesNo[$key];
		} else {
			return Language::getMessage( $key );
		}
	}

	# Inherit ucfirst()
	
	# Inherit checkTitleEncoding()
	
	# Inherit stripForSearch()
	
	# Inherit setAltEncoding()
	
	# Inherit recodeForEdit()
	
	# Inherit recodeInput()
	
	# Inherit replaceDates()
	
	# Inherit isRTL()

}

?>
