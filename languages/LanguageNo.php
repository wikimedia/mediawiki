<?

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesNo = array(
	-2	=> "Medium",
	-1	=> "Spesial",
	0	=> "",
	1	=> "Diskusjon",
	2	=> "Bruker",
	3	=> "Brukerdiskusjon",
	4	=> "Wikipedia",
	5	=> "Wikipedia-diskusjon",
	6	=> "Bilde",
	7	=> "Bildediskusjon"
);

/* private */ $wgDefaultUserOptionsNo = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0,
	"previewontop" => 1, "editsection" => 1,
	"editsectiononrightclick" => 0, "showtoc" => 1,
	"date" => 0
);

/* private */ $wgQuickbarSettingsNo = array(
	"Ingen", "Fast venstre", "Fast h�yre", "Flytende venstre"
);

/* private */ $wgSkinNamesNo = array(
	"Standard", "Nostalgi", "K�lnerbl�"
);

/* private */ $wgMathNamesNo = array(
	"Vis alltid som PNG",
	"HTML hvis veldig enkel, ellers PNG",
	"HTML hvis mulig, ellers PNG",
	"Behold som TeX (for tekst-nettlesere)",
	"Anbefalt for moderne nettlesere"
);

/* private */ $wgUserTogglesNo = array(
	"hover"		=> "Vis svevetekst over wiki-lenker",
	"underline" => "Strek under lenker",
	"highlightbroken" => "R�de lenker til tomme sider",
	"justify"	=> "Blokkjusterte avsnitt",
	"hideminor" => "Skjul mindre redigeringer i siste endringer",
	"usenewrc" => "Forbedret siste endringer (ikke for alle nettlesere)",
	"numberheadings" => "Nummerer overskrifter",
	"editondblclick" => "Rediger sider med dobbeltklikk (JavaScript)",
	"editsection"=>"Rediger avsnitt ved hjelp av [rediger]-lenke",
	"editsectiononrightclick"=>"Rediger avsnitt ved � h�yreklikke<br> p� avsnittsoverskrift (JavaScript)",
 	"showtoc"=>"Vis innholdsfortegnelse<br>(for artikler med mer enn tre avsnitt)",
	"rememberpassword" => "Husk passordet til neste gang",
	"editwidth" => "Redigeringsboksen har full bredde",
	"watchdefault" => "Overv�k nye og endrede artikler",
	"minordefault" => "Marker i utgangspunktet alle redigeringer som mindre",
	"previewontop" => "Vis forh�ndsvisningen foran redigeringsboksen, og ikke etter den"
	"nocache" => "Ikke husk sidene ved neste bes�k"

);

/* private */ $wgBookstoreListNo = array(
	"Antikvariat.net" => "http://www.antikvariat.net/",
	"Bibsys" => "http://www.bibsys.no/",
	"Bokkilden" => "http://www.bokkilden.no/",
	"Haugenbok" => "http://www.haugenbok.no/",
	"Mao.no" => "http://www.mao.no/"
);

/* private */ $wgWeekdayNamesNo = array(
	"s�ndag", "mandag", "tirsdag", "onsdag", "torsdag",
	"fredag", "l�rdag"
);

/* private */ $wgMonthNamesNo = array(
	"januar", "februar", "mars", "april", "mai", "juni",
	"juli", "august", "september", "oktober", "november",
	"desember"
);

/* private */ $wgMonthAbbreviationsNo = array(
	"jan", "feb", "mar", "apr", "mai", "jun", "jul", "aug",
	"sep", "okt", "nov", "des"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesNo = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Brukerinnstillinger",
	"Watchlist"		=> "Overv�kningsliste",
	"Recentchanges" => "Siste endringer",
	"Upload"		=> "Last opp fil",
	"Imagelist"		=> "Billedliste",
	"Listusers"		=> "Registrerte brukere",

	"Statistics"	=> "Statistikk",
	"Randompage"	=> "Tilfeldig artikkel",

	"Lonelypages"	=> "Foreldrel�se artikler",
	"Unusedimages"	=> "Foreldrel�se filer",
	"Popularpages"	=> "Popul�re artikler",
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
	"Booksources"	=> "Bokkilder"
);

/* private */ $wgSysopSpecialPagesNo = array(
	"Blockip"		=> "Blokker IP-adresse",
	"Asksql"		=> "S�k i databasen",
	"Undelete"		=> "Vis og gjenopprett slettede sider"
);

/* private */ $wgDeveloperSpecialPagesNo = array(
	"Lockdb"		=> "Skrivebeskytt databasen",
	"Unlockdb"		=> "Gjenopprett tilgang til databasen",
	"Debug"			=> "Feils�kingsinformasjon"
);

/* private */ $wgAllMessagesNo = array(

# Bits of text used by many pages:
#
"categories" => "Sidekategorier",
"category" => "kategori",
"category_header" => "Artikler i kategorien \"$1\"",
"subcategories" => "Underkategorier",

"linktrail"		=> "/^([a-z|�|�|�]+)(.*)\$/sD",
"mainpage"		=> "Hovedside",
"mainpagetext"	=> "Wiki-programvare er n� installert.",
"about"			=> "Om",
"aboutwikipedia" => "Om Wikipedia",
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
"qbbrowse"		=> "S�k",
"qbedit"		=> "Rediger",
"qbpageoptions" => "Sideinnstillinger",
"qbpageinfo"	=> "Sideinformasjon",
"qbmyoptions"	=> "Egne innstillinger",
"mypage"		=> "Egen side",
"mytalk"		=> "Egen diskusjonsside",
"currentevents" => "Aktuelt",
"errorpagetitle" => "Feil",
"returnto"		=> "Tilbake til $1.",
"fromwikipedia"	=> "Fra Wikipedia, den frie encyklopedi.",
"whatlinkshere"	=> "Lenker hit",
"help"			=> "Hjelp",
"search"		=> "S�k",
"go"		=> "Utf�r",
"history"		=> "Historikk",
"printableversion" => "Utskriftsversjon",
"editthispage"	=> "Rediger side",
"deletethispage" => "Slett side",
"protectthispage" => "Beskytt",
"unprotectthispage" => "Fjern beskyttelse",
"newpage" => "Ny side",
"talkpage"		=> "Diskuter side",
"postcomment"   => "Tilf�y en kommentar",
"articlepage"	=> "Vis artikkel",
"subjectpage"	=> "Vis emne", # For compatibility
"userpage" => "Brukerside",
"wikipediapage" => "Metaside",
"imagepage" => 	"Billedside",
"viewtalkpage" => "Vis diskusjon",
"otherlanguages" => "Andre spr�k",
"redirectedfrom" => "(Omdirigert fra $1)",
"lastmodified"	=> "Sist endret $1.",
"viewcount"		=> "Denne siden er vist $1 ganger.",
"gnunote" => "Artikkelen er utgitt under <a class=internal href='/wiki/GNU_FDL'>GNU fri dokumentasjonslisens</a>.",
"printsubtitle" => "(fra http://no.wikipedia.org)",
"protectedpage" => "Beskyttet side",
"administrators" => "Wikipedia:Administratorer",
"sysoptitle"	=> "Sysop-rettigheter kreves",
"sysoptext"		=> "Funksjonen kan kun utf�res av brukere med \"sysop\"-status.
Se $1.",
"developertitle" => "Utviklerrettigheter kreves.",
"developertext"	=> "Funksjonen kan kun utf�res av brukere med \"utvikler\"-status.
Se $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Utf�r",
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
"nosuchactiontext" => " Wikipedia-programvaren klarer ikke �
gjenkjenne funksjonen som er spesifisert i URL-en",
"nosuchspecialpage" => "En slik spesialside finnes ikke",
"nospecialpagetext" => "Du har bedt om en spesialside som Wikipedia-programvaren ikke
klarer � gjenkjenne.",

# General errors
#
"error"			=> "Feil",
"databaseerror" => "Databasefeil",
"dberrortext"	=> "Det har skjedd en syntaksfeil i databasen.
Dette kan skyldes en illegal foresp�rsel (se $5),
eller det kan bety feil i programvaren.
Den sist fors�kte foresp�rsel var:
<blockquote><tt>$1</tt></blockquote>
fra funksjonen \"<tt>$2</tt>\".
MySQL returnerte feilen \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Det har skjedd en syntaksfeil i databasen.
Den sist fors�kte foresp�rselen var:
\"$1\"
fra funksjonen \"$2\".
MySQL returnerte feilen \"$3: $4\".\n",
"noconnect"		=> "Kunne ikke koble til databasen p� $1",
"nodb"			=> "Kunne ikke velge databasen $1",
"cachederror"	=> "Det f�lgende er en lagret kopi av den �nskede siden, og er ikke n�dvendigvis oppdatert.",
"readonly"		=> "Databasen er skrivebeskyttet",
"enterlockreason" => "Skriv en begrunnelse for skrivebeskyttelsen, inkludert et estimat p� n�r den vil bli opphevet",
"readonlytext"	=> "Databasen er for �yeblikket skrivebeskyttet for nye sider og andre modifikasjoner, sannsynligvis for rutinemessig vedlikehold. Administratoren som stengte den har gitt denne forklaringen:
<p>$1",
"missingarticle" => "Databasen fant ikke teksten p� en side den skulle ha funnet, med navnet \"$1\".
Dette er ikke en databasefeil, men sannsynligvis en programfeil
Send en rapport om dette til en administrator, oppgi adresse til siden.",
"internalerror" => "Intern feil",
"filecopyerror" => "Kunne ikke kopiere filen \"$1\" til \"$2\".",
"filerenameerror" => "Kunne ikke omd�pe filen \"$1\" til \"$2\".",
"filedeleteerror" => "Kunne ikke slette filen \"$1\".",
"filenotfound"	=> "Kunne ikke finne filen \"$1\".",
"unexpected"	=> "Uventet verdi: \"$1\"=\"$2\".",
"formerror"		=> "Feil: Kunne ikke sende skjema",	
"badarticleerror" => "Handlingen kan ikke utf�res p� denne siden.",
"cannotdelete"	=> "Kunne ikke slette filen. (den kan v�re slettet av noen andre.)",
"badtitle"		=> "Feil tittel",
"badtitletext"	=> "Den �nskede tittel var ulovlig, tom eller er galt lenket fra en Wikipedia p� et annet spr�k.",
"perfdisabled" => "Beklager! Denne funksjon er midlertidig avbrutt av vedlikeholdsgrunner.",
"perfdisabledsub" => "Her er en lagret kopi fra $1:",
"viewsource" => "Vis kildekode",
"protectedtext" => "Denne siden er sperret for � hindre redigering; det
kan v�re flere grunner til dette, se
[[Wikipedia:Beskyttet side]].

Du kan se og kopiere kildekoden til denne siden:",

# Login and logout pages
#
"logouttitle"	=> "Logg ut",
"logouttext"	=> "Du er n� logget ut.
Du kan fortsette � bruke Wikipedia anonymt, eller du kan logge inn
igjen med samme konto eller med en annen en.\n",

"welcomecreation" => "<h2>Hjertelig velkommen til Wikipedia, $1!</h2><p>Vi har opprettet din brukerkonto.
Hvis du vil, kan du personliggj�re brukerinnstillingene.",

"loginpagetitle" => "Logg inn",
"yourname"		=> "Brukernavn",
"yourpassword"	=> "Passord",
"yourpasswordagain" => "Gjenta passord",
"newusersonly"	=> " (kun nye brukere)",
"remembermypassword" => "Husk passordet til neste gang.",
"loginproblem"	=> "<b>Du ble ikke logget inn.</b><br>Pr�v igjen!",
"alreadyloggedin" => "<font color=red><b>Bruker $1 er allerede logget inn!</b></font><br>\n",

"areyounew"		=> "Hvis du er ny p� Wikipedia og vil ha en brukerkonto, skriv inn et brukernavn og et passord, og bekreft passordet ved � skrive det inn en gang til.
E-postadresse er frivillig; hvis du oppgir det, kan du f� passordet tilsendt om du glemmer det.<br>\n",

"login"			=> "Logg inn",
"userlogin"		=> "Logg inn",
"logout"		=> "Logg ut",
"userlogout"	=> "Logg ut",
"notloggedin"	=> "Ikke innlogget",
"createaccount"	=> "Opprett ny konto",
"badretype"		=> "Passordene var ikke like.",
"userexists"	=> "Brukernavnet er allerede i bruk. Velg et nytt.",
"youremail"		=> "E-postadresse",
"yournick"		=> "�kenavn (for signaturer)",
"emailforlost"	=> "Hvis du glemmer passordet, kan du f� et nytt sendt til din e-postadresse.",
"loginerror"	=> "Innloggingsfeil",
"noname"		=> "Du har ikke oppgitt et gyldig brukernavn.",
"loginsuccesstitle" => "Du er n� innlogget",
"loginsuccess"	=> "Du er n� innlogget som \"$1\".",
"nosuchuser"	=> "Det eksisterer ingen bruker \"$1\".
Sjekk stavem�ten, eller opprett en ny konto.",
"wrongpassword"	=> "Du har oppgitt et ugyldig passord. Pr�v igjen.",
"mailmypassword" => "Send nytt passord.",
"passwordremindertitle" => "Nytt passord til Wikipedia",
"passwordremindertext" => "Noen (antagelig deg, fra IP-adressen $1)
ba oss sende deg et nytt passord til Wikipedia..
Passord for bruker \"$2\" er n� \"$3\".
Du b�r logge inn og endre passordet n�.",
"noemail"		=> "Det er ikke registrert noen e-postadresse p� bruker \"$1\".",
"passwordsent"	=> "Et nytt passord har blitt sendt til e-postadressen registrert p� bruker \"$1\".
Logg inn n�r du har mottatt det nye passordet.",

# Edit pages
#
"summary"		=> "Beskrivelse",
"subject"		=> "Overskrift",
"minoredit"		=> "Mindre endring",
"watchthis"		=> "Overv�k side",
"savearticle"	=> "Lagre siden",
"preview"		=> "Forh�ndsvisning",
"showpreview"	=> "Forh�ndsvisning",
"blockedtitle"	=> "Brukeren er blokkert",
"blockedtext"	=> "Ditt brukernavn eller din IP-adresse er blokkert av $1.
F�lgende begrunnelse ble gitt:<br>''$2''<p>Du kan kontakte $1 eller en annen
[[Wikipedia:Administratorer|administrator]] for � diskutere utestengelsen.",
"newarticle"	=> "(Ny)",
"newarticletext" =>
"Artikkelen inneholder ingen tekst.
Du kan begynne p� en artikkel ved � skrive i boksen under
(se [[Wikipedia:Hjelp|hjelpsiden]] for mere informasjon).
Hvis du ikke vil redigere siden, klikk p� '''tilbake''' i nettleseren.",
"anontalkpagetext" => "---- ''Dette er en diskusjonsside for en anonym bruker som ikke har opprettet en konto eller ikke bruker den. Vi er derfor n�dt til � bruke den numeriske IP-adressen til � identifisere ham eller henne. En IP-adresse kan v�re delt mellom flere brukere. Hvis du er en anonym bruker og synes at du har f�tt irrelevante kommentarer p� en slik side, [[Spesial:Innlogging|logg p�]] s� vi unng�r fremtidige forvekslinger med andre anonyme brukere.'' ",
"noarticletext" => "(Det er for �yeblikket ingen tekst p� denne siden.)",
"updated"		=> "(Oppdatert)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Husk at dette kun er en forh�ndsvisning og at teksten ikke er lagret!",
"previewconflict" => "Slik vil teksten i redigeringsvinduet se ut hvis du lagrer den",
"editing"		=> "Redigerer $1",
"sectionedit"	=> " (seksjon)",
"commentedit"	=> " (kommentar)",
"editconflict"	=> "Redigeringskonflikt: $1",
"explainconflict" => "Noen andre har endret teksten siden du begynte � redigere.
Den �verste boksen inneholder den n�v�rende tekst.
Dine endringer vises i den nederste boksen.
Du er n�dt til � flette dine endringer sammen med den n�v�rende teksten.
<b>Kun</b> teksten i den �verste tekstboksen vil bli lagret n�r du
trykker \"Lagre siden\".\n<p>",
"yourtext"		=> "Din tekst",
"storedversion" => "Den lagrede versjonen",
"editingold"	=> "<strong>ADVARSEL: Du redigerer en gammel versjon
av denne siden.
Hvis du lagrer den, vil alle endringer foretatt siden denne revisjonen bli 
overskrevet.</strong>\n",
"yourdiff"		=> "Forskjeller",
"copyrightwarning" => "Legg merke til at alle bidrag til Wikipedia er
� betrakte som utgitt under GNU fri dokumentasjonslisens
(se $1 for detaljer).
Hvis du ikke vil ha teksten redigert uten n�de og kopiert etter
forgodtbefinnende, kan du ikke legge den her.<br>
Du lover oss ogs� at du skrev teksten selv eller kopierte fra en
ressurs som ikke er beskyttet av opphavsrett.

<strong>LEGG ALDRI MATERIALE HER SOM ER BESKYTTET AV ANDRES OPPHAVSRETT UTEN 
DERES TILLATELSE!</strong>",
"longpagewarning" => "ADVARSEL: Denne siden er $1 kilobyte lang; noen
nettlesere kan ha problemer med � redigere sider som n�rmer seg eller 
er lengre enn 32kb. Overvei om ikke siden kan deles opp i mindre deler.",
"readonlywarning" => "ADVARSEL: Databasen er l�st p� grunn av vedlikehold,
s� du kan ikke lagre dine endringer akkurat n�. Det kan v�re en god id� � 
kopiere teksten din til en tekstfil, s� du kan lagre den til senere.",
"protectedpagewarning" => "ADVARSEL: Denne siden er l�st, s� kun administratorer
kan redigere den. S�rg for at du f�lger 
<a href='/wiki/Wikipedia:Retningslinjer_for_beskyttede_sider'>retningslinjer for 
beskyttede sider</a>.",

# History pages
#
"revhistory"	=> "Historikk",
"nohistory"		=> "Det er ingen historikk for denne siden.",
"revnotfound"	=> "Versjonen er ikke funnet",
"revnotfoundtext" => "Den gamle versjonen av den siden du spurte etter, finnes ikke. Kontroller den URL-en du brukte for � f� adgang til denne siden.\n",
"loadhist"		=> "Laster historikk",
"currentrev"	=> "N�v�rende versjon",
"revisionasof"	=> "Versjonen fra $1",
"cur"			=> "n�",
"next"			=> "neste",
"last"			=> "forrige",
"orig"			=> "original",
"histlegend"	=> "Forklaring: (n�) = forskjell fra den n�v�rende 
versjonen, (forrige) = forskjell fra den forrige versjonen, M = mindre endring",

# Diffs
#
"difference"	=> "(Forskjeller mellom versjoner)",
"loadingrev"	=> "laster versjon for � se forskjeller",
"lineno"		=> "Linje $1:",
"editcurrent"	=> "Rediger den n�v�rende versjonen av denne siden",

# Search results
#
"searchresults" => "S�keresultater",
"searchhelppage" => "Wikipedia:S�king",
"searchingwikipedia" => "S�king i Wikipedia",
"searchresulttext" => "For mer informasjon om s�king i Wikipedia, se 
$1.",
"searchquery"	=> "For foresp�rsel \"$1\"",
"badquery"		=> "Galt utformet foresp�rsel",
"badquerytext"	=> "Vi kunne ikke utf�re foresp�rselen.
Det er sannsynligvis fordi du har fors�kt � s�ke etter et ord med
f�rre enn tre bokstaver, hvilket ikke st�ttes enn�.
Det kan ogs� v�re du har skrevet feil.
Pr�v igjen.",
"matchtotals"	=> "Foresp�rselen \"$1\" ga treff p� $2 artikkeltitler
og p� teksten i $3 artikler.",
"nogomatch" => "Ingen sider med akkurat denne tittelen eksisterer, pr�ver 
fullteksts�king i stedet. ",
"titlematches"	=> "Artikkeltitler med treff p� foresp�rselen",
"notitlematches" => "Ingen artikkeltitler hadde treff p� foresp�rselen",
"textmatches"	=> "Artikkeltekster med treff p� foresp�rselen",
"notextmatches"	=> "Ingen artikkeltekster hadde treff p� foresp�rselen",
"prevn"			=> "forrige $1",
"nextn"			=> "neste $1",
"viewprevnext"	=> "Vis ($1) ($2) ($3).",
"showingresults" => "Nedenfor vises <b>$1</b> resultater som starter med 
nummer <b>$2</b>.",
"showingresultsnum" => "Nedenfor vises <b>$3</b> resultater som starter med nummer <b>$2</b>.",
"nonefound"		=> "<strong>NB</strong>: s�k uten resultat skyldes 
at man s�ker etter alminnelige ord som \"har\" og \"fra\",
som ikke er indeksert, eller ved � spesifisere mer enn et s�keord (da kun 
sider som inneholder alle s�keordene vil bli funnet).",
"powersearch" => "S�k",
"powersearchtext" => "
S�k i navnerom :<br>
$1<br>
$2 List opp omdirigeringer   S�k etter $3 $9",
"searchdisabled" => "<p>S�kefunksjonen er midlertidig avbrutt p� grunn av
for stort press p� tjeneren; vi h�per vi kan sette den p� igjen n�r vi har
oppgradert programvaren. I mellomtiden kan du s�ke via Google:</p>
                                                                                                                                                        
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
"preferences"	=> "Innstillinger",
"prefsnologin" => "Ikke logget inn",
"prefsnologintext"	=> "Du m� v�re <a href=\"" .
  wfLocalUrl( "Spesial:Userlogin" ) . "\">logget inn</a>
for � endre brukerinnstillingene.",
"prefslogintext" => "Du logget inn som \"$1\".
Ditt interne ID-nummer er $2.

Se [[Wikipedia:Hvordan stille inn brukerinnstillinger]] for en forklaring p� de forskjellige brukerinnstillingene.",
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
"textboxsize"	=> "Tekstboks-st�rrelse",
"rows"			=> "Rekker",
"columns"		=> "Kolonner",
"searchresultshead" => "Brukerinnstillinger for s�keresultater",
"resultsperpage" => "Resultater per side",
"contextlines"	=> "Linjer per resultat",
"contextchars"	=> "Tegn per linje i resultatet",
"stubthreshold" => "Grense for visning av sm�artikler",
"recentchangescount" => "Antall titler p� siden \"siste endringer\"",
"savedprefs"	=> "Brukerinnstillingene er lagret.",
"timezonetext"	=> "Tast inn antall timer lokal tid er forskjellig
fra tjenerens tid.",
"localtime"	=> "Lokaltid",
"timezoneoffset" => "Forskjell",
"servertime"	=> "Tjenerens tid er n�",
"guesstimezone" => "Hent tidssone fra nettleseren",
"emailflag"	=> "Ikke ta imot e-post fra andre brukere",
"defaultns"		=> "S�k som standard i disse navnerom:",

# Recent changes
#
"changes" => "endringer",
"recentchanges" => "Siste endringer",
# This is the default text, and can be overriden by editing [[Wikipedia::Recentchanges]]
"recentchangestext" => "Se de sist endrede sider i Wikipedia p� denne siden.",
"rcloaderr"		=> "Laster sist endrede sider",
"rcnote"		=> "Nedenfor er de siste <strong>$1</strong> endringer i de 
siste <strong>$2</strong> dagene.",
"rcnotefrom"	=> "Nedenfor er endringene fra <b>$2</b> inntil <b>$1</b> vist.",
"rclistfrom"	=> "Vis nye endringer med start fra $1",
"rclinks"		=> "Vis seneste $1 endringer i de siste $2 dager; $3 mindre endringer.",
"rchide"		=> "i $4 form; $1 mindre endringer; $2 andre navnerom; $3 mer enn �n redigering.",
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
"reuploaddesc"	=> "Tilbake til skjemaet for � laste opp filer.",
"uploadnologin" => "Ikke logget inn",
"uploadnologintext"	=> "Du m� v�re <a href=\"" .
  wfLocalUrl( "Spesial:Userlogin" ) . "\">logget inn</a>
for � kunne laste opp filer.",
"uploadfile"	=> "Last opp filer",
"uploaderror"	=> "Feil under opplasting av fil",
"uploadtext"	=> "<strong>STOPP!</strong> F�r du laster opp filer her,
v�r sikker p� du har lest og f�lger Wikipedias <a href=\"" .
wfLocalUrlE( "Wikipedia:Retningslinjer for billedbruk" ) . "\">retningslinjer for billedbruk</a>.
<p>For � se eller s�ke i bilder som tidligere er lastet opp,
g� til <a href=\"" . wfLocalUrlE( "Spesial:Imagelist" ) .
"\">listen over bilder</a>.
Opplasting og slettinger er registrert i <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">loggen over opplastede filer</a>.
<p>Bruk skjemaet nedenunder til � laste opp nye bilder som kan brukes
til � illustrere dine artikler.
P� de fleste nettlesere vil du se en \"Browse...\"-knapp eller en 
\"Bla igjennom...\"-knapp, som vil
bringe deg til operativsystemets standarddialog for � �pne filer.
N�r du velger en fil, vil navnet p� filen dukke opp i tekstfeltet
ved siden av knappen.
Du m� ogs� verifisere at du ikke bryter noens opphavsrett.
Det gj�r du ved � krysse av i boksen.
Trykk p� \"Last opp\"-knappen for � laste opp filen.
Dette kan godt ta litt tid hvis du har en langsom internettforbindelse.
<p>De foretrukne formatene er JPEG til fotografiske bilder, PNG
til tegninger og andre sm� bilder, og OGG til lyd.
S�rg for � gi filen et beskrivende navn for � unng� 
forvirring om innholdet.
For � bruke bildet i en artikkel, bruk en lenke av dette slaget:
<b>[[bilde:fil.jpg]]</b> eller <b>[[bilde:fil.png|alternativ tekst]]</b>
eller <b>[[medium:fil.ogg]]</b> for lyd.
<p>Legg merke til at akkurat som med Wikipedia-sider, kan andre gjerne 
redigere eller
slette de filene du har lastet opp, hvis de mener det hjelper encyklopedien, og
du kan bli blokkert fra � laste opp hvis du misbruker systemet.",
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
"noaffirmation" => "Du m� bekrefte at du ikke bryter noens opphavsrett
ved � laste opp denne filen.",
"ignorewarning"	=> "Ignorer advarselen og lagre filen likevel.",
"minlength"		=> "Navnet p� filen m� best� av minst tre bokstaver.",
"badfilename"	=> "Navnet p� filen er blitt endret til \"$1\".",
"badfiletype"	=> "\".$1\" er ikke et av de anbefalte filformatene.",
"largefile"		=> "Det anbefales at filer ikke er st�rre enn 100kb.",
"successfulupload" => "Opplastingen er gjennomf�rt",
"fileuploaded"	=> "Filen \"$1\" er lastet opp med.
F�lg denne lenken: ($2) til siden med beskrivelse og fyll ut
informasjon omkring filen, slik som hvor den kom fra, n�r den er laget
og av hvem, og andre ting du vet om filen.",
"uploadwarning" => "Opplastingsadvarsel",
"savefile"		=> "Lagre fil",
"uploadedimage" => "Lastet opp \"$1\"",
"uploaddisabled" => "Beklager, muligheten for opplasting er deaktivert p� denne tjeneren.",

# Image list
#
"imagelist"		=> "Billedliste",
"imagelisttext"	=> "Her er en liste med $1 bilder sortert $2.",
"getimagelist"	=> "henter billedliste",
"ilshowmatch"	=> "Vis alle treff p� bilder ved navn",
"ilsubmit"		=> "S�k",
"showlast"		=> "Vis de siste $1 bilder sortert $2.",
"all"			=> "alle",
"byname"		=> "etter navn",
"bydate"		=> "etter dato",
"bysize"		=> "etter st�rrelse",
"imgdelete"		=> "slett",
"imgdesc"		=> "beskrivelse",
"imglegend"		=> "Forklaring: (beskrivelse) = vis/rediger bildebeskrivelse.",
"imghistory"	=> "Billedhistorikk",
"revertimg"		=> "gjenopprett",
"deleteimg"		=> "slett",
"imghistlegend" => "Forklaring: (n�) = dette er det n�v�rende bilde, 
(slett) = slett denne gamle versjonen, (gjenopprett) = gjenopprett en gammel versjon.
<br><i>Klikk p� en dato for � se bildet som ble lastet opp da</i>.",
"imagelinks"	=> "Billedlenker",
"linkstoimage"	=> "De f�lgende sider har lenker til dette bildet:",
"nolinkstoimage" => "Det er ingen sider som har lenker til dette bildet.",

# Statistics
#
"statistics"	=> "Statistikk",
"sitestats"		=> "Wikipedia-statistikk",
"userstats"		=> "Brukerstatistikk",
"sitestatstext" => "Der er i alt <b>$1</b> sider i databasen.
Dette inkluderer diskusjonssider, sider om Wikipedia, 
omdirigeringssider, og andre som sikkert ikke kvalifiserer til � v�re artikler.
Hvis man ekskluderer disse, er det <b>$2</b> sider som sannsynligvis er 
ordin�re artikler.<p>
Der har i alt v�rt <b>$3</b> viste sider, og <b>$4</b> redigeringer av sider
siden programvaren ble oppdatert (25. september 2002).
Det vil si at det har v�rt <b>$5</b> gjennomsnittlige redigeringer per side, 
og <b>$6</b> visninger per redigering.",
"userstatstext" => "Der er  <b>$1</b> registrerte brukere.
<b>$2</b> av disse er administratorer (se $3).",

# Maintenance Page
#
"maintenance"		=> "Vedlikeholdsside",
"maintnancepagetext"	=> "P� denne siden er det forskjellige
verkt�yer for � vedlikeholde Wikipedia. Noen av disse funksjonene er 
harde for databasen (de tar lang tid), s� la v�re � oppdatere siden 
hver gang du har rettet en enkelt ting",
"maintenancebacklink"	=> "Tilbake til vedlikeholdssiden",
"disambiguations"	=> "Artikler med flertydige titler",
"disambiguationspage"	=> "Wikipedia:Lenker til artikler med flertydige titler",
"disambiguationstext"	=> "De f�lgende artikler har lenker til 
<i>artikler med flertydige titler</i>. De burde ha lenke til en ikke-flertydig 
tittel i stedet.<br>En artikkel blir behandlet som flertydig hvis den har
lenker fra $1.<br>Lenker fra andre navnerom er <i>ikke</i> listet her.",
"doubleredirects"	=> "Dobbelte omdirigeringer",
"doubleredirectstext"	=> "<b>NB:</b> Denne listen kan inneholde gale 
resultater. Det er som regel fordi siden inneholder ekstra tekst under den
f�rste #REDIRECT.<br>\nHver linje inneholder lenker til den f�rste og den 
anden omdirigeringen, og den f�rste linjen fra den andre omdirigeringsteksten. 
Det gir som regel den \"riktige\" m�lartikkelen, som den f�rste omdirigeringen 
skulle ha pekt p�.",
"brokenredirects"	=> "D�rlige omdirigeringer",
"brokenredirectstext"	=> "De f�lgende omdirigeringer peker p� en side som 
ikke eksisterer.",
"selflinks"		=> "Sider som henviser til seg selv",
"selflinkstext"		=> "De f�lgende sider inneholder henvisninger til seg selv, 
men det burde de ikke.",
"mispeelings"           => "Sider med stavefeil",
"mispeelingstext"               => "De f�lgende sider inneholder en av de 
alminnelige stavefeilene som er listet p� $1. Den korrekte stavem�te kan 
angis i paranteser etter den feilaktige stavem�ten (som dette).",
"mispeelingspage"       => "Liste over alminnelige stavefeil",
"missinglanguagelinks"  => "Manglende spr�klenker",
"missinglanguagelinksbutton"    => "Finn manglende spr�klenker for",
"missinglanguagelinkstext"      => "Disse artiklene har <i>ikke</i> noen 
lenker til den samme artikkel i $1. Omdirigeringer og underartikler er 
<i>ikke</i> vist.",


# Miscellaneous special pages
#
"orphans"		=> "Foreldrel�se sider",
"lonelypages"	=> "Foreldrel�se sider",
"unusedimages"	=> "Ubrukte bilder",
"popularpages"	=> "Popul�re sider",
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
"intl"		=> "Spr�klenker",
"movethispage"	=> "Flytt side",
"unusedimagestext" => "<p>Legg merke til at andre internettsider
slik som de andre internasjonale Wikipediaene kanskje har lenker til et bilde med
en direkte URL, og kan v�re listet opp her, selv om det er
i aktiv bruk.",
"booksources"	=> "Bokkilder",
"booksourcetext" => "Her er en liste over lenker til steder som
l�ner ut og/eller selger nye og brukte b�ker, og som kanskje ogs� har 
ytterligere informasjon om b�ker du leter etter.
Wikipedia er ikke assosiert med noen av disse stedene,
og denne listen skal ikke sees p� som en anbefaling av disse.",
"alphaindexline" => "$1 til $2",

# Email this user
#
"mailnologin"	=> "Ingen avsenderadresse",
"mailnologintext" => "Du m� v�re <a href=\"" .
  wfLocalUrl( "Spesial:Userlogin" ) . "\">logget inn</a>
og ha en gyldig e-postadresse satt i <a href=\"" .
  wfLocalUrl( "Spesial:Preferences" ) . "\">brukerinnstillingene</a>
for � sende e-post til andre brukere.",
"emailuser"		=> "E-post til denne brukeren",
"emailpage"		=> "E-post til bruker",
"emailpagetext"	=> "Hvis denne brukeren har oppgitt en gyldig e-postadresse i
sine brukerinnstillinger, vil dette skjemaet sende en enkelt 
beskjed.
Den e-postadressen du har satt i brukerinnstillingene dine, vil dukke opp
i \"Fra\"-feltet p� denne e-posten, s� mottageren er i stand til � svare.",
"noemailtitle"	=> "Ingen e-postadresse",
"noemailtext"	=> "Denne brukeren har ikke oppgitt en gyldig e-postadresse,
eller har valgt � ikke motta e-post fra andre brukere.",
"emailfrom"		=> "Fra",
"emailto"		=> "Til",
"emailsubject"	=> "Emne",
"emailmessage"	=> "Beskjed",
"emailsend"		=> "Send",
"emailsent"		=> "E-posten sendt",
"emailsenttext" => "E-postbeskjeden er sendt.",

# Watchlist
#
"watchlist"		=> "Overv�kningsliste",
"watchlistsub"	=> "(for bruker \"$1\")",
"nowatchlist"	=> "Du har ingenting i overv�kningslisten.",
"watchnologin"	=> "Ikke logget inn",
"watchnologintext"	=> "Du m� v�re <a href=\"" .
  wfLocalUrl( "Spesial:Userlogin" ) . "\">logget inn</a>
for � kunne endre overv�kningslisten.",
"addedwatch"	=> "Tilf�yd til overv�kningslisten",
"addedwatchtext" => "Siden \"$1\" er tilf�yd <a href=\"" .
  wfLocalUrl( "Spesial:Watchlist" ) . "\">overv�kningslisten</a>.
Fremtidige endringer til denne siden og den tilh�rende diskusjonssiden vil 
bli listet opp her, og siden vil fremst� <b>fremhevet</b> i <a href=\"" .
  wfLocalUrl( "Spesial:Recentchanges" ) . "\">listen med de siste 
endringene</a> for � gj�re det lettere � finne den.</p>

<p>Hvis du senere vil fjerne siden fra overv�kningslisten, klikk
\"Fjern overv�kning\" ute i siden.",
"removedwatch"	=> "Fjernet fra overv�kningslisten",
"removedwatchtext" => "Siden \"$1\" er fjernet fra 
overv�kningslisten.",
"watchthispage"	=> "Overv�k side",
"unwatchthispage" => "Fjern overv�kning",
"notanarticle"	=> "Ikke en artikkel",
"watchnochange" => "Ingen av sidene i overv�kningslisten er endret i den valgte perioden.",
"watchdetails" => "($1 sider i overv�kningslisten, fratrukket alle diskusjonssidene;
$2 totalt antall sider endret i den valgte perioden;
$3...
<a href='$4'>vis og rediger den komplette listen</a>.)",
"watchmethod-recent" => "sjekker siste endringer for sider i overv�kningslisten",
"watchmethod-list" => "sjekker siste endringer for sider i overv�kningslisten",
"removechecked" => "Fjern valgte sider fra overv�kningslisten",
"watchlistcontains" => "Overv�kningslisten inneholder $1 sider.",
"watcheditlist" => "Her er en alfabetisk liste over sidene i overv�kningslisten.
Velg de sidene du vil fjerne fra overv�kningslisten 
og klikk p� 'fjern valgte sider fra overv�kningslisten'-knappen
i bunnen av skjermen.",
"removingchecked" => "Fjerner de valgte sidene fra overv�kningslisten ...",
"couldntremove" => "Kunne ikke fjerne '$1'...",
"iteminvalidname" => "Problem med '$1', ugyldig navn...",
"wlnote" => "Nedenfor er de siste $1 endringer i de siste <b>$2</b> timer.",


# Delete/protect/revert
#
"deletepage"	=> "Slett side",
"confirm"		=> "Bekreft",
"excontent" => "innholdet var:",
"exbeforeblank" => "innholdet f�r siden ble t�mt var:",
"exblank" => "siden var tom",
"confirmdelete" => "Bekreft sletting",
"deletesub"		=> "(Sletter \"$1\")",
"historywarning" => "Advarsel: Siden du holder p� � slette har en historikk: ",
"confirmdeletetext" => "Du holder p� � permanent slette en side
eller et bilde sammen med hele den tilh�rende historikken fra databasen.
Bekreft at du virkelig vil gj�re dette, at du forst�r
konsekvensene, og at du gj�r dette i overensstemmelse med
[[Wikipedia:Retningslinjer]].",
"confirmcheck"	=> "Ja, jeg vil virkelig slette.",
"actioncomplete" => "Gjennomf�rt",
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
"imagereverted" => "Gjenopprettelse av en tidligere versjon gjennomf�rt.",
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
"protectlogtext" => "Her er en liste over sider som er blitt beskyttet eller har f�tt fjernet beskyttelsen.
Se [[Wikipedia:Beskyttet side]] for mer informasjon.",
"protectedarticle" => "beskyttet [[$1]]",
"unprotectedarticle" => "fjernet beskyttelse for [[$1]]",

# Undelete
"undelete" => "Gjenopprett en slettet side",
"undeletepage" => "Se og gjenopprett slettede sider",
"undeletepagetext" => "De f�lgende sider er slettet, men de finnes 
stadig i arkivet og kan gjenopprettes. Arkivet blir periodevis slettet.",
"undeletearticle" => "Gjenopprett slettet artikkel",
"undeleterevisions" => "$1 revisjoner arkivert",
"undeletehistory" => "Hvis du gjenoppretter siden, vil alle de historiske 
revisjoner ogs� bli gjenopprettet. Hvis en ny side med det samme navnet 
er opprettet siden denne ble slettet, vil de gjenopprettede revisjonene 
dukke opp i den tidligere historikken, og den nyeste revisjonen vil forbli 
p� siden.",
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
"notargettitle" => "Intet m�l",
"notargettext"	=> "Du har ikke spesifisert en m�lside eller bruker
� utf�re denne funksjonen p�.",
"linklistsub"	=> "(Liste over lenker)",
"linkshere"	=> "De f�lgende sider har lenker hit:",
"nolinkshere"	=> "Ingen sider har lenker hit.",
"isredirect"	=> "omdirigeringsside",

# Block/unblock IP
#
"blockip"		=> "Blokker IP-adresse",
"blockiptext"	=> "Bruk skjemaet nedenunder for � blokkere skriveadgangen
fra en spesifikk IP-adresse.
Dette m� kun gj�res for at forhindre vandalisme, og i
overensstemmelse med [[Wikipedia:Retningslinjer|retningslinjene]].
Fyll ut en spesiell begrunnelse nedenunder (for eksempel med et sitat fra
sider som har v�rt utsatt for vandalisme).",
"ipaddress"		=> "IP-adresse",
"ipbreason"		=> "Begrunnelse",
"ipbsubmit"		=> "Blokker denne adressen",
"badipaddress"	=> "IP-adressen er galt utformet.",
"noblockreason" => "Du m� angi en begrunnelse for denne blokkeringen.",
"blockipsuccesssub" => "Blokkering utf�rt",
"blockipsuccesstext" => "IP-adressen \"$1\" er blokkert.
<br>Se [[Spesial:Ipblocklist|IP-blokkeringslisten]] for alle blokkeringer.",
"unblockip"		=> "Opphev blokkeringen av IP-adresse",
"unblockiptext"	=> "Bruk skjemaet nedenunder for � gjenopprette skriveadgangen
for en tidligere blokkert IP-adresse.",
"ipusubmit"		=> "Opphev blokkeringen av denne adresse",
"ipusuccess"	=> "IP-adressen \"$1\" har f�tt opphevet blokkeringen",
"ipblocklist"	=> "Liste over blokkerte IP-adresser",
"blocklistline"	=> "$1, $2 blokkerte $3",
"blocklink"		=> "blokker",
"unblocklink"	=> "opphev blokkering",
"contribslink"	=> "bidrag",
"autoblocker"	=> "Automatisk blokkert fordi du deler IP-adresse med \"$1\". Begrunnelse \"$2\".",

# Developer tools
#
"lockdb"		=> "L�s database",
"unlockdb"		=> "L�s opp database",
"lockdbtext"	=> "� l�se databasen vil avbryte alle brukere fra � kunne
redigere sider, endre deres innstillinger, redigere deres 
overv�kningsliste, og andre ting som krever endringer i databasen.
Bekreft at du har til hensikt � gj�re dette, og at du vil
l�se opp databasen n�r vedlikeholdet er utf�rt.",
"unlockdbtext"	=> "� l�se opp databasen vil si at alle brukere igjen 
kan redigere sider, endre sine innstillinger, redigere sin 
overv�kningsliste, og andre ting som krever endringer i databasen.
Bekreft at du har til hensikt � gj�re dette.",
"lockconfirm"	=> "Ja, jeg vil virkelig l�se databasen.",
"unlockconfirm"	=> "Ja, jeg vil virkelig l�se opp databasen.",
"lockbtn"		=> "L�s databasen",
"unlockbtn"		=> "L�s opp databasen",
"locknoconfirm" => "Du har ikke bekreftet handlingen.",
"lockdbsuccesssub" => "Databasen er n� l�st",
"unlockdbsuccesssub" => "Databasen er n� l�st opp",
"lockdbsuccesstext" => "Wikipedia-databasen er l�st.
<br>Husk � fjerne l�sen n�r du er ferdig med vedlikeholdet.",
"unlockdbsuccesstext" => "Wikipedia-databasen er l�st opp.",

# SQL query
#
"asksql"		=> "SQL-foresp�rsel",
"asksqltext"	=> "Bruk skjemaet nedenunder for direkte foresp�rsler 
i Wikipedia-databasen.
Bruk enkle anf�rselstegn ('som dette') for � skille strenger.
Dette kan ofte belaste serveren kraftig, s� bruk denne funksjonen
med omtanke.",
"sqlislogged"	=> "V�r oppmerksom p� at alle SQL-foresp�rsler lagres i en loggfil.",
"sqlquery"		=> "Tast inn foresp�rsel",
"querybtn"		=> "Send foresp�rsel",
"selectonly"	=> "Foresp�rsler andre enn \"SELECT\" er forbeholdt 
Wikipedia-utviklere.",
"querysuccessful" => "Foresp�rsel gjennomf�rt",

# Move page
#
"movepage"		=> "Flytt side",
"movepagetext"	=> "N�r du bruker skjemaet nedenunder, vil du f� omd�pt en 
side og flyttet hele historikken til det nye navnet.
Den gamle tittelen vil bli en omdirigeringsside til den nye tittelen.
Lenker til den gamle tittelen vil ikke bli endret. S�rg for � 
[[Spesial:Maintenance|sjekke]] for dobbelte eller d�rlige omdirigeringer. 
Du er ansvarlig for at alle lenker stadig peker dit det er 
meningen de skal peke.

Legg merke til at siden '''ikke''' kan flyttes hvis det allerede finnes en side 
med den nye tittelen, med mindre den siden er tom eller er en omdirigering 
uten noen historikk. Det betyr at du kan flytte en side tilbake dit
den kom fra hvis du gj�r en feil.

<b>ADVARSEL!</b>
Dette kan v�re en drastisk og uventet endring for en popul�r side;
v�r sikker p� at du forst�r konsekvensene av dette f�r du
fortsetter.",
"movepagetalktext" => "Den tilh�rende diskusjonssiden, hvis det finnes en, 
vil automatisk bli flyttet med siden '''med mindre:'''
*Du flytter siden til et annet navnerom,
*En ikke-tom diskusjonsside allerede eksisterer under det nye navnet, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfellene er du n�dt til � flytte eller flette sammen siden manuelt.",
"movearticle"	=> "Flytt side",
"movenologin"	=> "Ikke logget inn",
"movenologintext" => "Du m� v�re registrert bruker og v�re <a href=\"" .
  wfLocalUrl( "Spesial:Userlogin" ) . "\">logget p�</a>
for � flytte en side.",
"newtitle"		=> "Til ny tittel",
"movepagebtn"	=> "Flytt side",
"pagemovedsub"	=> "Flytting gjennomf�rt",
"pagemovedtext" => "Siden \"[[$1]]\" er flyttet til \"[[$2]]\".",
"articleexists" => "En side med det navnet eksisterer allerede, eller det
navnet du har valgt, er ikke gyldig. Velg et annet navn.",
"talkexists"	=> "Siden ble flyttet korrekt, men den tilh�rende 
diskusjonssiden kunne ikke flyttes, fordi det allerede eksisterer en 
med den nye tittelen. Du er n�dt til � flette dem sammen manuelt.",
"movedto"		=> "flyttet til",
"movetalk"		=> "Flytt ogs� diskusjonssiden, hvis den eksisterer.",
"talkpagemoved" => "Den tilh�rende diskusjonssiden ble ogs� flyttet.",
"talkpagenotmoved" => "Den tilh�rende diskusjonssiden ble 
<strong>ikke</strong> flyttet.",

);

class LanguageNo extends Language {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsNo ;
		return $wgDefaultUserOptionsNo ;
		}

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

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNo;
		return $wgQuickbarSettingsNo;
	}

	function getSkinNames() {
		global $wgSkinNamesNo;
		return $wgSkinNamesNo;
	}

	function getMathNames() {
		global $wgMathNamesNo;
		return $wgMathNamesNo;
	}

	function getDateFormats() {
		global $wgDateFormatsNo;
		return $wgDateFormatsNo;
	}

	function getUserToggles() {
		global $wgUserTogglesNo;
		return $wgUserTogglesNo;
	}

	function getLanguageNames() {
		global $wgLanguageNamesNo;
		return $wgLanguageNamesNo;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesNo;
		if ( ! array_key_exists( $code, $wgLanguageNamesNo ) ) {
			return "";
		}
		return $wgLanguageNamesNo[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesNo;
		return $wgMonthNamesNo[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		global $wgMonthNamesNo;
		return $wgMonthNamesNo[$key-1];
	}

	function getMonthRegex()
	{
		global $wgMonthNamesNo;
		return implode( "|", $wgMonthNamesNo );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsNo;
		return $wgMonthAbbreviationsNo[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesNo;
		return $wgWeekdayNamesNo[$key-1];
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
            global $wgAllMessagesNo, $wgAllMessagesEn;
            $m = $wgAllMessagesNo[$key];

            if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
            else return $m;
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
