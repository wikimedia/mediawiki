<?

// NOTE: To turn off "Current Events" in the sidebar,
// set "currentevents" => "-"
// 
// The names of the namespaces can be set here, but the numbers
// are magical, so don't change or move them!  The Namespace class
// encapsulates some of the magic-ness.
// 

/* private */ $wgNamespaceNamesSv = array(
	-2	=> "Media",
        -1      => "Special",
        0       => "",
        1       => "Diskussion",
        2       => "Anv�ndare",
        3       => "Anv�ndare_diskussion",
        4       => "Wikipedia",
        5       => "Wikipedia_diskussion",
        6       => "Bild",
        7       => "Bild_diskussion",
	8	=> "MediaWiki",
	9	=> "MediaWiki_diskussion",
);

/* inherit standard defaults */

/* private */ $wgQuickbarSettingsSv = array(
        "Ingen", "Fast v�nster", "Fast h�ger", "Flytande v�nster"
);

/* private */ $wgSkinNamesSv = array(
        "Standard", "Nostalgi", "Cologne Bl�"
);

/* private */ $wgUserTogglesSv = array(
        "hover"            => "Sv�vande text �ver wikil�nkar",
        "underline"        => "Understrukna l�nkar",
        "highlightbroken"  => "R�da l�nkar till tomma sidor",
        "justify"          => "Justera indrag",
        "hideminor"        => "G�m sm� redigeringar vid senaste �ndring",
	"usenewrc"         => "Avancerad 'Senaste �ndringar'",
        "numberheadings"   => "Automatisk numrering av �verskrifter",
        "rememberpassword" => "Kom ih�g l�senord till n�sta bes�k",
        "editwidth"        => "Redigeringsboxen har full bredd",
        "editondblclick"   => "Redigera sidor med dubbelklick (JavaScript)",
	"editsection"      => "Visa [edit]-l�nkar f�r att redigera sektioner",
	"editsectiononrightclick" => "H�gerklick p� rubriker redigerar sektioner",
	"showtoc"          => "Visa automatisk inneh�llsf�rteckning (p� sidor med mer �n 3 sektioner)",
        "watchdefault"     => "�vervaka nya och �ndrade artiklar",
        "minordefault"     => "Markera som standard alla �ndringer som mindre",
	"previewontop"     => "Visa f�rhandsgranskning f�re textf�ltet ist�llet f�r efter",
	"nocache"          => "Sl� av cachning av sidor"
);

/* private */ $wgBookstoreListSv = array(
        "AddALL"         => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
        "PriceSCAN"      => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
        "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
        "Amazon.com"     => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* Note: native names of languages are preferred where known to maximize
   ease of navigation -- people should be able to recognize their own
   languages! */

/* private */ $wgWeekdayNamesSv = array(
        "s�ndag", "m�ndag", "tisdag", "onsdag", "torsdag",
        "fredag", "l�rdag"
);

/* private */ $wgMonthNamesSv = array(
        "januari", "februari", "mars", "april", "maj", "juni",
        "juli", "augusti", "september", "oktober", "november",
        "december"
);

/* private */ $wgMonthAbbreviationsSv = array(
        "jan", "feb", "mar", "apr", "maj", "jun", "jul", "aug",
        "sep", "okt", "nov", "dec"
);

// All special pages have to be listed here: a description of ""
// will make them not show up on the "Special Pages" page, which
// is the right thing for some of them (such as the "targeted" ones).
//#

// private
$wgValidSpecialPagesSv = array(
        "Userlogin"     => "",
        "Userlogout"    => "",
        "Preferences"   => "Mina anv�ndarinst�llningar",
        "Watchlist"     => "Min �vervakningslista",
        "Recentchanges" => "Senaste �ndringar",

        "Upload"        => "Ladda upp filer",
        "Imagelist"     => "Bildlista",
        "Listusers"     => "Registrerade anv�ndare",
        "Statistics"    => "Sidstatistik",

        "Randompage"    => "Slumpm�ssig sida",
        "Lonelypages"   => "F�r�ldral�sa sidor",
        "Unusedimages"  => "F�r�ldral�sa filer",
        "Popularpages"  => "Popul�ra artiklar",
        "Wantedpages"   => "Mest �nskade artiklar",
        "Shortpages"    => "Korta artiklar",
        "Longpages"     => "L�nga artiklar",
        "Newpages"      => "De nyaste artiklarna",
        "Ancientpages"  => "Oldest pages",
        "Allpages"      => "Alla sidor efter titel",

        "Ipblocklist"   => "Blockerade IP adresser",
        "Maintenance"   => "Underh�llssida",
        "Specialpages"  => "Specialsidor",
        "Contributions" => "",
        "Emailuser"     => "E-postanv�ndare",
        "Whatlinkshere" => "",
        "Recentchangeslinked" => "",
        "Movepage"      => "",
        "Booksources"   => "Externa bokk�llor",
        "Export"        => "XML export",
);

/* private */ $wgSysopSpecialPagesSv = array(
        "Blockip"       => "Blockera en IP-adress",
        "Asksql"        => "G�r en s�kning i databasen",
        "Undelete"      => "Se och �terst�ll raderade sidor"
);

/* private */ $wgDeveloperSpecialPagesSv = array(
        "Lockdb"        => "Skrivskydda databasen",
        "Unlockdb"      => "�terst�ll skrivning till databasen",
        "Debug"         => "Debuginformation"
);

/* private */ $wgAllMessagesSv = array(

// Bits of text used by many pages:
//	
"linktrail"             => "/^([a-z���]+)(.*)\$/sD",
"mainpage"              => "Huvudsida",
"about"                 => "Om",
"aboutwikipedia"        => "Om Wikipedia",
"aboutpage"             => "Wikipedia:Om",

"help"                  => "Hj�lp",
"helppage"              => "Wikipedia:Hj�lp",
"wikititlesuffix"       => "Wikipedia",
"bugreports"            => "Felrapporter",
"bugreportspage"        => "Wikipedia:Felrapporter",
"sitesupport"           => "Donationer",
"sitesupportpage"       => "", # If not set, won't appear. Can be wiki page or URL
"faq"                   => "FAQ",
"faqpage"               => "Wikipedia:FAQ",
"edithelp"              => "Redigeringshj�lp",
"edithelppage"          => "Wikipedia:Hur_redigerar_jag_en_sida",
"cancel"                => "Avbryt",
"qbfind"                => "SnabbS�k",
"qbbrowse"              => "Genoms�k",
"qbedit"                => "Redigera",
"qbpageoptions"         => "Sidinst�llningar",
"qbpageinfo"            => "Sidinformation",
"qbmyoptions"           => "Mina inst�llningar",
"mypage"                => "Min sida",
"mytalk"                => "Min diskussion",
"currentevents"         => "-",
"errorpagetitle"        => "Fel",
"returnto"              => "Tillbaka till $1.",
"fromwikipedia"         => "Fr�n Wikipedia, den fria encyklopedin.",
"whatlinkshere"         => "Vilka sidor l�nkar hit?",
"help"                  => "Hj�lp",
"search"                => "S�k",
"history"               => "Versionshistorik",
"printableversion"      => "Skrivarv�nlig version",
"editthispage"          => "Redigera den h�r sidan",
"deletethispage"        => "Radera den h�r sidan",
"protectthispage"       => "Skydda den h�r sidan",
"unprotectthispage"     => "Ta bort skydd av denna sida",
"newpage"               => "Ny sida",
"talkpage"              => "Diskussionssida",
"postcomment"           => "Skriv ett inl�gg",
"articlepage"           => "Visa artikel",
"subjectpage"           => "�mnessida",
"userpage"              => "Visa anv�ndarsida",
"wikipediapage"         => "Visa metasida",
"imagepage"             => "Visa bildsida",
"otherlanguages"        => "Andra spr�k",
"redirectedfrom"        => "(Omdirigerad fr�n $1)",
"lastmodified"          => "Denna sida blev senast �ndrad $1.",
"viewcount"             => "Denna sida har visats $1 g�nger.",
"gnunote"               => "Denna sida �r publicerad under <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>.",
"printsubtitle"         => "(Fr�n http://sv.wikipedia.org)",
"protectedpage"         => "Skyddad sida",
"administrators"        => "Wikipedia:Administrat�rer",
"sysoptitle"            => "Sysop-beh�righet kr�vs",
"sysoptext"             => "Denna funktion kan bara utf�ras av anv�ndare med \"sysop\" status.
Se $1.",
"developertitle"        => "Utvecklarbeh�righet kr�vs",
"developertext"         => "Denna funktion kan bara utf�ras av anv�ndare med \"developer\" status.
Se $1.",
"nbyte"                 => " bytes",
"go"                    => "Utf�r",
"ok"                    => "OK",
"sitetitle"             => "Wikipedia",
"sitesubtitle"          => "Den fria encyklopedin",
"retrievedfrom"         => "H�mtat fr�n \"$1\"",
"newmessages"           => "Du har $1.",
"newmessageslink"       => "nya meddelanden",
"editsection"           => "edit",
"toc"                   => "Inneh�ll",
"showtoc"               => "visa",
"hidetoc"               => "g�m",
"thisisdeleted"         => "Visa eller �terst�ll $1?",
"restorelink"           => "$1 raderade versioner",

// Main script and global functions
//
"nosuchaction"          => "Funktionen finns inte",
"nosuchactiontext"      => "Den funktion som specificerats i URL:en kan inte
hittas av Wikipediaprogramvaran",
"nosuchspecialpage"     => "S�dan specialsida finns inte",
"nospecialpagetext"     => "Du har �nskat en specialsida som inte
hittas av Wikipediaprogramvaran.",

// General errors
//
"error"                 => "Fel",
"databaseerror"         => "Databasfel",
"dberrortext"           => "Ett syntaxfel i databasfr�gan har uppst�tt. Detta kan bero p� en felaktig s�kfr�ga (se $5) eller det kan bero p� ett fel i programvaran.
Den senste utf�rda databasfr�gan var:
<blockquote><tt>$1</tt></blockquote>
fr�n funktionen \"<tt>$2</tt>\".
MySQL returnerade felen \"$3<tt>: $4</tt>\".",
"noconnect"             => "Kunde inte ansluta till databasen p� $1",
"nodb"                  => "Kunde inte v�lja databasen $1",
"readonly"              => "Databasen �r skrivskyddad",
"enterlockreason"       => "Skriv en grund f�r skrivskyddet, inklusive 
en uppskattning p� n�r skrivskyddet skall upph�vas",
"readonlytext"          => "Wikipediadatabasen �r f�r �gonblicket skrivskyddad f�r 
nya sidor och andra modifikationer, beroende p� rutinm�ssigt 
underh�ll av databasen, varefter den �terg�r till normalstatus.
Den administrat�r som skrivskyddade den har gett f�ljande f�rklaring:
<p>$1",
"missingarticle"        => "Databasen fann inte texten p� en sida
som den skulle hitta, med namnet \"$1\".
Dette �r inte ett databas fel, utan beror p� ett fel i mjukvaran.
Skicka v�nligen en rapport om detta till en administrat�r, d�r du ocks� n�mner 
URL:en.",
"internalerror"         => "Internt fel",
"filecopyerror"         => "Kunde inte kopiera filen \"$1\" til \"$2\".",
"filerenameerror"       => "Kunde inte byta namn p� filen \"$1\" til \"$2\".",
"filedeleteerror"       => "Kunde inte radera filen \"$1\".",
"filenotfound"          => "Kunde inte hitta filen \"$1\".",
"unexpected"            => "Ov�ntat v�rde: \"$1\"=\"$2\".",
"formerror"             => "Fel: Kunde inte s�nda formul�r",
"badarticleerror"       => "Denna funktion kan inte utf�ras p� denna sida.",
"cannotdelete"          => "Kunde inte radera sidan, eller filen som specificerades.",
"badtitle"              => "Felaktig titel",
"badtitletext"          => "Den �nskade sidans titel var inte till�ten, tom eller sidan
�r felaktigt l�nkad fr�n en Wikipedia p� ett annat spr�k.",


// Login and logout pages
//
"logouttitle"           => "Logga ut Anv�ndare",
"logouttext"            => "Du �r nu utloggad.
Du kan forts�tta som anonym Wikipediaanv�ndare, eller s� kan du logga in
igen som samma eller annan anv�ndare.\n",

"welcomecreation"       => "<h2>V�lkommen, $1!</h2><p>Ditt konto har skapats. Gl�m inte att anpassa dina Wikipediainst�llningar.",

"loginpagetitle"        => "Logga in Anv�ndare",
"yourname"              => "Ditt anv�ndarnamn",
"yourpassword"          => "Ditt l�senord",
"yourpasswordagain"     => "Upprepa l�senord",
"newusersonly"          => " (bara f�r nya anv�ndare)",
"remembermypassword"    => "Kom ih�g mitt l�senord till n�sta g�ng.",
"loginproblem"          => "<b>Det var sv�rt att logga in dig .</b><br>Pr�va igen!",
"alreadyloggedin"       => "<font color=red><b>Anv�ndare $1, du �r redan inloggad !</b></font><br>\n",

"login"                 => "Logga in",
"userlogin"             => "Logga in",
"logout"                => "Logga ut",
"userlogout"            => "Logga ut",
"notloggedin" 		=> "Ej inloggad",
"createaccount"         => "Skapa ett konto",
"badretype"             => "De l�senord du uppgett �verenst�mmer inte med varandra.",
"userexists"            => "Detta anv�ndarnamn anv�nds redan. Ange ett annat anv�ndarnamn.",
"youremail"             => "Din e-postadress",
"yournick"              => "Ditt smeknamn (till signaturer)",
"emailforlost"          => "Har du gl�mt ditt l�senord, s� kan du f� ett nytt l�senord skickat till din e-post",
"loginerror"            => "Inloggningsproblem",
"noname"                => "Det anv�ndarnamn som du angett finns inte",
"loginsuccesstitle"     => "Inloggningen lyckades",
"loginsuccess"          => "Du �r nu inloggad p� wikipedia med anv�ndarnamnet \"$1\".",
"nosuchuser"            => "Det finns ingen anv�ndare med namnet \"$1\".
Kontrollera stavningen, eller anv�nd formul�ret nedan f�r att skapa ett nytt konto.",
"wrongpassword"         => "L�senordet du skrev �r felaktigt. Pr�va igen",
"mailmypassword"        => "S�nd mig ett nytt l�senord",
"passwordremindertitle" => "Nytt l�senord fr�n Wikipedia",
"passwordremindertext"  => "N�gon (f�rmodligen du, med IP-numret $1)
har bett oss s�nda dig ett nytt l�senord f�r din Wikipedia-inloggning
L�senordet f�r anv�ndare \"$2\" �r nu \"$3\".
Du ska logga in p� din anv�ndare och byta l�senord.",
"noemail"               => "Det finns ingen e-postadress registrerad f�r anv�ndare \"$1\".",
"passwordsent"          => "Ett nytt l�senord har skickats till e-posten registrerad av anv�ndaren\"$1\".
Var sn�ll och logga in igen n�r du f�tt meddelandet.",


// Edit pages
//
"summary"               => "Sammanfattning",
"minoredit"             => "Detta �r en mindre �ndring",
"watchthis"             => "Bevaka den h�r artikeln",
"savearticle"           => "Spara",
"preview"               => "F�rhandsgranska",
"showpreview"           => "Visa f�rhandgranskning",
"blockedtitle"          => "Anv�ndaren �r sp�rrad",
"blockedtext"           => "Ditt anv�ndarnamn har blivit sp�rrat av $1.
Anledning �r att:<br>''$2''<p>Ta kontakt med $1 eller en av de andra
[[Wikipedia:Administrat�rer|administrat�rerna]] f�r att diskutera varf�r du blivit sp�rrad", // "
"newarticle"            => "(Ny)",
"newarticletext"        => "Du har klickat p� en r�d l�nk, en sida som inte finns �nnu. Du kan hj�lpa till genom att sj�lv skriva vad du vet om �mnet i f�ltet nedan. Om du inte vill skriva n�got kan du bara trycka p� \"tillbaka\" i din webbl�sare.",
"anontalkpagetext"      => "---- ''Detta �r en diskussionssida f�r en anonym anv�ndare, en anv�ndare som inte har skapat sig ett konto, eller som inte har loggat in p� det. Vi m�ste d�rf�r anv�nda personens numeriska [[IP-adress]] f�r identifiera honom eller henne. En s�dan IP-adress kan ibland anv�ndas av flera olika personer. Om du �r en anonym anv�ndare och ser meddelanden p� den h�r sidan som inte tycks vara riktade till dig, var v�nlig [[Special:Userlogin|logga in]] s� du undviker f�rv�xling med andra anonyma anv�ndare i framtiden.'' ",
"noarticletext"         => "(Det finns f�r tillf�llet ingen text p� den h�r sidan.)",
"updated"               => "(Uppdaterad)",
"note"                  => "<strong>Notera:</strong> ",
"previewnote"           => "Observera att detta �r en f�rhandsvisning, och att sidan �nnu inte sparats!",
"previewconflict"       => "Denna f�rhandsvisning �r resultatet av den 
redigerbara texten ovanf�r,
s� som det kommer att se ut om du v�ljer att spara.",
"editing"               => "Redigerar $1",
"editconflict"          => "Redigeringskonflikt: $1",
"explainconflict"       => "N�gon har �ndrat denna sida efter att du b�rjade att redigera den.
Det �versta text blocket inneh�ller den nuvarande texten.
Dina �ndringer syns i det nedersta blocket.
Du m�ste infoga dina �ndringar i den existerande texten.
<b>Bara</b> texten i den �versta textboxen sparas om du trycker \"Spara sida\".\n<p>",
"yourtext"              => "Din text",
"storedversion"         => "Din sparade version",
"editingold"            => "<strong>VARNING: Du redigerar en gammal version
av denna sida. Om du sparar den, kommer alla �ndringar p� denns sida f�reg�ende revison att bli �verskrivna.</strong>\n",
"yourdiff"              => "Skillnader",
"copyrightwarning"      => "Observera att alla bidrag till Wikipedia �r
att betrakta som utgivna under GNU Free Documentation License
(se $1 f�r detaljer).
Om du inte vill ha din text redigerad och kopierad efter andras gottfinnade s� skall du inte skriva n�gon text h�r.<br>
Du lovar oss ocks� att du skrev texten sj�lv, eller kopierade fr�n public domain eller liknande fri resurs.<BR>
<strong>L�GG ALDRIG UT UPPHOVSR�TTSSKYDDAT MATERIAL H�R UTAN F�RFATTARENS TILL�TELSE!</strong>",

"longpagewarning"       => "VARNING: Den h�r artikeln �r $1 kilobytes l�ng; vissa �ldre webbl�sare
kan ha problem med att redigera sidor som �r omkring 32 kb eller st�rre.
Du kanske vill �verv�ga att dela upp artikeln i mindre delar.",
"readonlywarning"       => "VARNING: Databasen �r tillf�lligt l�st f�r underh�ll. Du kommer inte att kunna spara 
dina �ndringar just nu. F�r s�kerhets skull kanske du vill �verv�ga att kopiera �ver texten till din egen dator tills
databasen �r uppl�st igen.",
"protectedpagewarning"  => "VARNING:  Den h�r sidan har l�sts s� att bara administrat�rer kan redigera den. 
F�rs�kra dig om att du f�ljer rekommendationerna f�r <a href='/wiki/Wikipedia:Skyddade sidor'>skyddade sidor</a>",

// History pages
//
"revhistory"            => "Versionshistoria",
"nohistory"             => "Det finns ingen versionshistoria f�r denna sida.",
"revnotfound"           => "Versionen hittades inte",
"revnotfoundtext"       => "Den gamla versionen av den sida du fr�gade efter kan inte hittas. Kontrollera den URL du anv�nde f�r att n� denna sida.\n",
"loadhist"              => "L�ser sidans versioner",
"currentrev"            => "Nuvarande version",
"revisionasof"          => "Versionen fr�n $1",
"cur"                   => "nuvarande",
"next"                  => "n�sta",
"last"                  => "f�reg�ende",
"orig"                  => "original",
"histlegend"            => "F�rklaring: (nuvarande) = skillnad mot den nuvarande versionen,
 (f�reg�ende) = skillnad mot den f�reg�ende versionen, M = mindre �ndring",


// Diffs
//
"difference"            => "(Skillnad mellan versioner)",
"loadingrev"            => "l�ser version f�r att se skillnad",
"lineno"                => "Rad $1:",
"editcurrent"           => "Redigera den nuvarande versionen av denna sida",

// Search results
//
"searchresults"         => "S�kresultat",
"searchhelppage"        => "Wikipedia:S�kning",
"searchingwikipedia"    => "S�kning p� Wikipedia",
"searchresulttext"      => "F�r mer information om s�kning p� Wikipedia, se $1.",
"searchquery"           => "For query \"$1\"",
"badquery"              => "Felaktigt utformat s�kbegrepp",
"badquerytext"          => "Vi kunde inte utf�ra din s�kning.
Detta beror sannolikt p� att du f�rs�kt s�ka efter ett ord med f�rre �n tre bokst�ver, n�got som f.n. inte st�ds. Det kan ocks� vara s� att du har anget en felaktig s�kning, till exempel \"fisk och och skaldjur\". Prova att formulera om s�kningen.",
"matchtotals"           => "S�kordet f�rekommer i \"$1\" �verensst�mde med $2 artiklar titlar
och texter i $3 artiklar.",
"titlematches"          => "Artikel titlar som �verensst�mmer med s�kordet",
"notitlematches"        => "Ingen artikel titlar �verensst�mmer med s�kordet",
"textmatches"           => "Artikel texter som �verensst�mmer med s�kordet",
"notextmatches"         => "Ingen artikel texter �verensst�mmer med s�kordet",
"prevn"                 => "f�rra $1",
"nextn"                 => "n�sta $1",

"viewprevnext"          => "Om ($1) ($2) ($3).",
"showingresults"        => "Nedan visas <b>$1</b> resultat som startar med nummer <b>$2</b>.",
"nonefound"             => "<strong>Note</strong>: Misslyckade s�kningar f�rorsakas ofta av
 att man s�ker efter vanliga ord som \"har\" och \"fr�n\",
vilka inte indexeras, eller att specificera flera s�kord (bara 
sidor som inneh�ller alla s�korden hittas).",
"powersearch"           => "S�k",
"powersearchtext"       => "
S�k i namnutrymme :<br>
$1<br>
$2 List redirects &nbsp; S�k efter $3 $9",


// Preferences page
//
"preferences"           => "Inst�llningar",
"prefsnologin"          => "Du �r inte inloggad",
"prefsnologintext"      => "Du m�ste vara <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
f�r att kunna �ndra i inst�llningar.",
"prefslogintext"        => "Du �r inloggad som \"$1\".
Ditt IP-nummer �r $2.",
"prefsreset"            => "Inst�llningar har blivit �terst�llda fr�n minne.",
"qbsettings"            => "Inst�llningar f�r snabbmeny",
"changepassword"        => "Byt l�senord",
"skin"                  => "Utseende",
"saveprefs"             => "Spara inst�llningar",
"resetprefs"            => "�terst�ll inst�llningar",
"oldpassword"           => "Gammalt l�senord",
"newpassword"           => "Nytt l�senord",
"retypenew"             => "Skriv om nytt l�senord",
"textboxsize"           => "Textbox dimensioner",
"rows"                  => "Rader",
"columns"               => "Kolumner",
"searchresultshead"     => "Inst�llningar f�r s�kresultat",
"resultsperpage"        => "Resultat att visa per sida",
"contextlines"          => "Linjer att visa per sida",
"contextchars"          => "Antalet bokst�ver per linje i resultatet",
"stubthreshold"         => "Gr�nser f�r visning av stubs",
"recentchangescount"    => "Antalet artiklar i \"senaste �ndringarna\" ",
"savedprefs"            => "Dina inst�llningar har blivit sparade",
"timezonetext"          => "Skriv in antalet timmar som din lokal tid skiljer sig fr�n
serverns klocka (UTC).
Den blir automatiskt inst�lld efter svensk tid eller skulle man till exempel f�r svensk vintertid, endast ha \"1\" (och \"2\" n�r vi har sommartid).",
"localtime"             => "Lokal tid",
"timezoneoffset"        => "Utj�mna",
"emailflag"             => "Hindra andra anv�ndare fr�n att skicka e-post till dig",

// Recent changes
//
"recentchanges"         => "Senaste �ndringarna",
"recentchangestext"     => "Se de senaste redigerade sidorna i Wikipedia p� denna sida.",
"rcloaderr"             => "L�ser senaste redigerade sidor",
"rcnote"                => "Nedanf�r �r de senaste <strong>$1</strong> �ndringarna under de sista <strong>$2</strong> dagarna.",
"rcnotefrom"            => "Nedanf�r �r �ndringarna fr�n <b>$2</b> till <b>$1</b> visade.",
"rclistfrom"            => "Visa nya �ndringar fr�n och med $1",
"rclinks"               => "Visa de senaste $1 �ndringarna under de senaste $2 dagarna",
// "rclinks"             => "Visa de senaste $1 �ndringarna under de senaste $2 dagarna",
"rchide"                => "i $4 form; $1 mindre �ndringar; $2 andra namnrum; $3 mer �n en redigering.",
"diff"                  => "skillnad",
"hist"                  => "historia",
"hide"                  => "g�m",
"show"                  => "visa",

"tableform"             => "tabell",
"listform"              => "lista",
"nchanges"              => "$1 �ndringar",
"minoreditletter"       => "M",
"newpageletter"         => "N",

// Upload
//
"upload"                => "Ladda upp",
"uploadbtn"             => "Ladda upp fil",
"uploadlink"            => "Ladda upp bild",
"reupload"              => "�teruppladdning",
"reuploaddesc"          => "Tillbaka till uppladdningsformul�r.",
"uploadnologin"         => "Inte inloggad",
"uploadnologintext"     => "Du m�ste vara <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
f�r att kunna ladda upp filer.",
"uploadfile"            => "Ladda upp fil",
"uploaderror"           => "Uppladdnings fel",
"uploadtext"            => "<strong>STOPP!</strong> Innan du laddar upp h�r,
s� m�ste du ha l�st och f�lja Wikipedias <a href=\"" .
wfLocalUrlE( "Wikipedia:Policy om bruk av bilder" ) . "\">policy om hur 
bilder f�r anv�ndas</a>.
<p>F�r att visa eller s�ka tidigare uppladdade bilder g� till
<a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">lista �ver uppladdade bilder</a>.
Uppladdningar och borttagningar loggas i <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">uppladdnings logg</a>.
<p>Anv�nd formul�ret nedan f�r att ladda upp nya filer, som 
du kan illustrera dina artiklar med.
P� de flesta webbl�sare kommer du att se en \"Browse...\" knapp eller en 
\"�ppna...\" knapp, som startar ditt operativsystems dialogruta f�r att �ppna filer. N�r du valt en fil kommer namnet p� den filen att visas i textf�ltet brevid knappen. Du m�ste �ven kryssa f�r rutan, f�r att du inte g�r n�got som strider mot upphovsr�tten av filen som laddas upp.
Tryck p� \"Upload\" knappen f�r att ladda upp filen.
Detta kan dr�ja ett tag om du har en l�ngsam internetf�rbindelse.
<p>Formaten p� filerna ska helst vara JPEG f�r bilder, PNG f�r ritningar 
och andra ikonliknande bilder och OGG f�r ljud.
Var v�nlig namnge filen med ett s� beskrivande namn som m�jligt, f�r att undvika f�rvirring.
F�r att anv�nda en fil i en artikel, skriv f�ljande om det �r en bild: <b>[[bild:filnamn.jpg]]</b> eller <b>[[bild:filnamn.png|alternativ text]]</b>
eller <b>[[media:filnamn.ogg]]</b> om det �r en ljudfil.
<p>Kom ih�g att det h�r �r en wiki, vilket g�r att andra kan redigera eller ta bort dina uppladdningar om de tycker de inte passar i en artikel. Om du missbrukar systemet med uppladdningar kommer filen avl�gsnas och du bli sp�rrad fr�n att ladda upp filer i framtiden.",
"uploadlog"             => "upload log",
"uploadlogpage"         => "Upload_log",
"uploadlogpagetext"     => "Nedan f�ljer en lista med de senaste uppladdade filerna.
Alla tider visas efter serverns tid (UTC).
<ul>
</ul>
",
"filename"              => "Filnamn",
"filedesc"              => "Beskrivning",
"affirmation"           => "Jag bekr�ftar att �garen till upphovsr�tten accepterar att licensiera enligt f�ljande avtal $1.",
"copyrightpage"         => "Wikipedia:copyright",
"copyrightpagename"     => "Wikipedia copyright",
"uploadedfiles"         => "Uppladdade filer",
"noaffirmation"         => "Du m�ste bekr�fta att uppladdningen inte kr�nker n�gon copyright",
"ignorewarning"         => "Ignorera varning och spara fil.",
"minlength"             => "Namnet p� bildfilen ska vara minst tre bokst�ver",
"badfilename"           => "Bildnamn har blivit �ndrat till \"$1\".",
"badfiletype"           => "\".$1\" �r inte ett rekomenderat bildformat.",
"largefile"             => "Bilder ska helst inte vara st�rre �n 100k.",
"successfulupload"      => "Uppladdningen lyckades",
"fileuploaded"          => "Filen \"$1\" laddades upp korrekt.
F�lj denna l�nk: ($2) till beskrivningssidan och fyll i
information om filen, som till exempel var den kommer ifr�n, 
n�r den skapades och vem som gjort den och allt annat du vet om den.",
"uploadwarning"         => "Uppladdnings varning",
"savefile"              => "Spara fil",
"uploadedimage"         => "uppladdad \"$1\"",

// Image list
//
"imagelist"             => "Bildlista",
"imagelisttext"         => "Nedan �r en lista med $1 bilder sorterad $2",
"getimagelist"          => "h�mta bildlista",
"ilshowmatch"           => "Visa alla bilder med namn som matchar",
"ilsubmit"              => "S�k",
"showlast"              => "Visa de senaste $1 bilderna sorterad $2.",
"all"                   => "alla",
"byname"                => "efter namn",
"bydate"                => "efter datum",
"bysize"                => "efter storlek",
"imgdelete"             => "ta bort",
"imgdesc"               => "beskrivning",
"imglegend"             => "Legend: (beskrivning) = visa/redigera bildbeskrivning.",
"imghistory"            => "Bildhistoria",
"revertimg"             => "�terg�",
"deleteimg"             => "radera",
"imghistlegend"         => "Legend: (nuvarande) = detta �r den nuvarande bilden, 
(ta bort) = ta bort den gamla version, (�terg�) = �terg� till en gammal version.
<br><i>Klicka p� ett datum f�r att se bilden som laddades upp den dagen</i>.", //"
"imagelinks"            => "Bildl�nk",
"linkstoimage"          => "De f�ljande sidorna l�nkar till denna bild:",
"nolinkstoimage"        => "Det finns ingen sida som l�nkar till denna bild.",

// Statistics
//
"statistics"            => "Statistik",
"sitestats"             => "Statistiksida",
"userstats"             => "Anv�ndarstatistik",
"sitestatstext"         => "Det �r <b>$1</b> sidor i databasen.
Detta inkluderer diskussionssidorna, sidor om Wikipedia, mycket korta\"stub\"
sidor, omdirigeringssidor, och andra sidor som kvalificerar sig som artiklar.
Om man tar bort ovanst�ende s� �r det <b>$2</b> sidor som anses som riktiga artiklar.<p>
Det har varit totalt <b>$3</b> sidvisningar och det har varit <b>$4</b> sidor som har �ndrats
sedan uppdateringen av mjukvaran (1 december 2002).
Det vill s�ga <b>$5</b> �ndringar per sida genomsnittligt, 
och <b>$6</b> sidvisningar per �ndring.",
"userstatstext"         => "Det �r <b>$1</b> registrerade anv�ndare.
<b>$2</b> av dem �r administrat�rer (se $3).",

// Maintenance Page
//
"maintenance"           => "Underh�llssida",
"maintnancepagetext"    => "Den h�r sidan inneh�ller flera verktyg f�r att sk�ta sidan. Vissa av dessa funktioner tenderar att stressa databasen (allt tar l�ng tid), s� var sn�ll och  tryck inte p� reloadknappen varje g�ng du gjort en liten �ndring.",
"maintenancebacklink"   => "Tillbaka till underh�llssidorna",
"disambiguations"       => "Sidor med tvetydiga l�nkar",
"disambiguationspage"   => "Wikipedia:L�nkar till sidor med tvetydiga titlar",
"disambiguationstext"   => "F�ljande artiklar l�nkar till en <i>sidor med tvetydliga titlar</i>. De ska l�nka till en sidor med en korrekt titel.<br>En sida behandlar som tvetydig om den l�nkar fr�n $1. <br>L�nkar fr�n andra namngrupper �r <i>inte</i> listade h�r.",
"doubleredirects"       => "Dubbla omdirigeringar",
"doubleredirectstext"   => "<b>OBS:</b> Denna lista kan inneh�lla falska resultat. Detta betyder normalt att det finns ytterligare text under den f�rsta #REDIRECT.<br>\n Varje rad inneh�ller en l�nk till den f�rsta och andra omdirigering och den f�rsta raden av den andra omdirigeringen ger oftast den \"riktiga\" artikeln, vilket egentligen den f�rsta omdirigeringen ska peka p�.",
"brokenredirects"       => "D�liga omdirigeringar",
"brokenredirectstext"   => "F�ljande l�nkar omdirigerar till en artikel som inte existerar.",
"selflinks"             => "Sidor med l�nkar till sig sj�lva",
"selflinkstext"         => "F�ljande sidor inneh�ller l�nkar till sig sj�lv, vilket de inte ska g�ra.",
"mispeelings"           => "Sidor med felstavningar",
"mispeelingstext"       => "F�ljande sidor innerh�ller vanliga felstavningar, som visas i $1. Den korrekta stavningen kanske ska se ut s�h�r.",
"mispeelingspage"       => "Lista med vanliga stavfel",
"missinglanguagelinks"  => "Saknade spr�kl�nkar",
"missinglanguagelinksbutton"    => "S�k efter saknade spr�kl�nkar f�r",
"missinglanguagelinkstext"      => "De h�r artiklarna �r <i>inte</i> l�nkade 
till deras i $1. Redirects och undersidor visas <i>inte</i>.",

// Miscellaneous special pages
//
"orphans"               => "F�r�ldral�sa sidor",
"lonelypages"           => "F�r�ldral�sa sidor",
"unusedimages"          => "Oanv�nda bilder",
"popularpages"          => "Popul�ra sidor",
"nviews"                => "$1 visningar",
"wantedpages"           => "�nskelista",
"nlinks"                => "$1 l�nkar",
"allpages"              => "Alla sidor",
"randompage"            => "Slumpartikel",
"shortpages"            => "Korta sidor",
"longpages"             => "L�nga sidor",
"listusers"             => "Anv�ndarlista",
"specialpages"          => "Speciella sidor",
"spheading"             => "Speciella sidor",
"sysopspheading"        => "Speciella sidor f�r sysop",
"developerspheading"    => "Speciella sidor f�r utvecklare",
"protectpage"           => "Skydda sida",
"recentchangeslinked"   => "Relaterade �ndringar",
"rclsub"                => "(till sidor som �r l�nkade fr�n \"$1\")",
"debug"                 => "Debug",
"newpages"              => "Nya sidor",
"movethispage"          => "Flytta den h�r sidan",
"unusedimagestext"      => "<p>L�gg m�rket till att andra hemsidor
som till exempel de internationella wikipedias kan l�nka till bilder 
med en direkt URL, och kan d�rf�r bli listade h�r trots att de anv�nds kontinuerligt.",
"booksources"           => "Bokk�llor",
"booksourcetext"        => "Nedan f�ljer en lista �ver l�nkar till hemsidor som s�ljer
nya och begagnade b�cker, och mycket annan information om de b�cker du s�ker.
Wikipedia har <b>inget</b> aff�rssamarbete med ovanst�ende f�retag och ska inte heller tolkas som en uppmuntran.",

// Email this user
//
"mailnologin"           => "Ingen adress att skicka till",
"mailnologintext"       => "Du ska vara<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
och ha angivit en korrekt epost-adress i dina <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">anv�ndarinst�llningar</a>
f�r att kunna skicka e-post till andra anv�ndare.",
"emailuser"             => "Skicka e-post till den h�r anv�ndaren",
"emailpage"             => "Skicka e-post till annan anv�ndare",
"emailpagetext"         => "Om den h�r anv�ndaren har skrivit in en korrekt e-postadress, i sina
anv�ndarinst�llningar, kommer formul�ret nedan skicka ett meddelande.
Den epost-adress du anget i dina anv�ndarinst�llningar kommer att skrivas
i \"Fr�n\"f�ltet i detta e-post, s� mottagaren har m�jlighet att svara.",
"noemailtitle"          => "Ingen e-postadress",
"noemailtext"           => "Den h�r anv�ndaren har inte angivet en korrekt e-postadress eller
valt att inte ta emot n�got mail fr�n andra anv�ndare.",
"emailfrom"             => "Fr�n",
"emailto"               => "Till",
"emailsubject"          => "�mne",
"emailmessage"          => "Meddelande",
"emailsend"             => "Skickat",
"emailsent"             => "E-post �r nu skickat",
"emailsenttext"         => "Din e-post har skickats.",

// Watchlist
//
"watchlist"             => "Min �vervakningslista",
"watchlistsub"          => "(f�r anv�ndare \"$1\")",
"nowatchlist"           => "Du har inga sidor upptagna p� din �vervakningslista.",
"watchnologin"          => "Du �r inte inloggad",
"watchnologintext"      => "Du ska vara<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
f�r att kunna g�ra �ndringar p� din �vervakningslista.",
"addedwatch"            => "Tillagd p� �vervakningslistan",
"addedwatchtext"        => "Sidan \"$1\" har satts upp p� din <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">�vervakningslista</a>.
Framtida �ndringar av den h�r sidan och dess diskussionssida vill listas d�r, 

och sidan kommer att markeras med <b>fet stil</b> i <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">listan �ver de senaste �ndringarna
</a> f�r att l�ttare kunna hittas</p>

<p>Om du vill ta bort denna sida fr�n din �vervakningslista, s� klicka 
\"Ta bort �vervakning\" ute i sidan.",
"removedwatch"          => "Borttagen fr�n �vervakningslista",
"removedwatchtext"      => "Sidan \"$1\" har blivit borttagen fr�n din �vervakningslista",
"watchthispage"         => "�vervaka sida",
"unwatchthispage"       => "Stoppa �vervakning",
"notanarticle"          => "Inte en artikel",

// Delete/protect/revert
//
"deletepage"            => "Ta bort sida",
"confirm"               => "Bekr�fta",
"excontent"             => "f�re radering:",
"exbeforeblank"         => "f�re t�mning:",
"exblank"               => "sidan var tom",
"confirmdelete"         => "Bekr�fta borttagning",
"deletesub"             => "(Tar bort \"$1\")",
"confirmdeletetext"     => "Du h�ller p� och permanent ta bort en sida
eller bild med all dess historia fr�n databasen.
Bekr�fta att du f�rst�r vad du h�ller p� med och vilka konsekvenser
detta leder till, och att det f�ljer 
[[Wikipedia:Policy]].", 
"confirmcheck"          => "Ja, jag vill verkligen ta bort det h�r.", //"
"actioncomplete"        => "Genomf�rt",
"deletedtext"           => "\"$1\" har blivit borttagen.
Se $2 f�r lista �ver senaste borttagningar",
"deletedarticle"        => "borttagen \"\$1\"",
"dellogpage"            => "Borttagningslogg",
"dellogpagetext"        => "Nedan �r en lista �ver de senaste borttagningarna.
De tidsangivelser som anges f�ljer serverns klocka (UTC).
<ul>
</ul>
",
"deletionlog"           => "borttagningslogg",
"reverted"              => "�terg�tt till yngre version",
"deletecomment"         => "Anledningen till borttagning",
"imagereverted"         => "�terst�llandet av nyare artikelversion lyckades",

// Undelete
//
"undelete"              => "�terst�ll borttagna sidor",
"undeletepage"          => "Visa och �terst�ll borttagna sidor",
"undeletepagetext"      => "F�ljande sidor har blivit borttagna, men �r fortfarande i arkivet och kan anv�ndas vid �terst�llning.
Arkivet kan ibland rensas p� gamla versioner.",
"undeletearticle"       => "�terst�ll borttagen artikel",
"undeleterevisions"     => "$1 versioner arkiverade",
"undeletehistory"       => "Om du �terst�ller sidan kommer allt tidigare versioner att sparas i versionshanteraren.
Om en ny sida med samma namn har blivit skapad sedan borttagningen s� kommer den �terst�llda versionen att
hamna i den �ldre versionshanteraren och den senaste versionen av sidan kommer inte blir automatiskt ersatt.",
"undeleterevision"      => "Tog bort version $1",
"undeletebtn"           => "�terst�lld!",
"undeletedarticle"      => "�terst�lld \"$1\"",
"undeletedtext"         => "Artikeln [[$1]] har blivit �terst�lld
Se [[Wikipedia:Borttagningslogg]] f�r en lista �ver nyligen gjorda borttagningar och �terst�llningar",

// Contributions
//
"contributions"         => "Anv�ndarbidrag",
"mycontris"             => "Mina bidrag",
"contribsub"            => "F�r $1",
"nocontribs"            => "Inga �ndringar var funna som motsvarar dessa kriterier",
"ucnote"                => "Nedan visas dennes anv�ndares senaste <b>$1</b> �ndringar, under de senaste <b>$2</b> dagarna.",
"uclinks"               => "Visa de senaste $1 �ndringarna. Visa de senaste $2 dagarna.",
"uctop"                 => " (top)",

// What links here
//
"whatlinkshere"         => "Vilka sidor l�nkar hit",
"notargettitle"         => "Inget m�l",
"notargettext"          => "Du har inte specificerat en sida eller anv�ndare
f�r att genomf�ra den h�r funktionen.",
"linklistsub"           => "(L�nklista)",
"linkshere"             => "F�ljande sidor l�nkas hit:",
"nolinkshere"           => "Inga sidor l�nkar hit.",
"isredirect"            => "L�nka vidare sida",

// Block/unblock IP
//
"blockip"               => "Blockera IP-adress",
"blockiptext"           => "Anv�nd formul�ret nedan f�r att blockera skriv�tkomst 
fr�n en viss IP-adress
Detta ska bara genomf�ras f�r att stoppa klotter och
�verst�mma med [[Wikipedia:Politik|Wikipedia politik]].
Fyll i anledningen till blockering nedan (till exempel vilka artiklar som klottrats ner).",
"ipaddress"             => "IP-adress",
"ipbreason"             => "Anledning",
"ipbsubmit"             => "Blockera den h�r IP-adressen",
"badipaddress"          => "Du har inte skrivit IP-adressen korrekt.",
"noblockreason"         => "Du m�ste ange en anledning till varf�r du blockerar.",
"blockipsuccesssub"     => "Blockeringen lyckades",
"blockipsuccesstext"    => "IP-adressen \"$1\" har blockerats.
<br>Se [[Speciel:Ipblocklist|IP blockeringslistan]] f�r alla blockeringar.",
"unblockip"             => "Ta bort blockering av IP-adress",
"unblockiptext"         => "Anv�nd nedanst�ende formul�r f�r att �terst�lla skrivr�ttigheten f�r en tidigare blockerad IP-adress.",
"ipusubmit"             => "Ta bort blockering f�r denna adress",
"ipusuccess"            => "Blockeringen f�r IP-adressen \"$1\" har tagits bort",

"ipblocklist"           => "Lista �ver blockerade IP-adresser",
"blocklistline"         => "$1, $2 blockerade $3",
"blocklink"             => "blockera",
"unblocklink"           => "ta bort blockering",
"contribslink"          => "bidrag",

// Developer tools 
//
"lockdb"                => "L�s databas",
"unlockdb"              => "L�s upp databas",
"lockdbtext"            => "En l�sning av databasen hindrar alla anv�ndare fr�n att redigera sidor, �ndra inst�llningar och andra saker som kr�ver �ndringar i databasen.
Bekr�fta att du verkligen vill g�ra detta och att du kommer att l�sa upp databasen n�r underh�llet �r utf�rt.",
"unlockdbtext"          => "Genom att l�sa upp databasen kommer alla anv�ndare att kunna redigera sidor, �ndra inst�llningar etc. igen.
Bekr�fta att du vill g�ra detta.",
"lockconfirm"           => "Ja, jag vill verkligen l�sa databasen.",
"unlockconfirm"         => "Ja, jag vill verkligen l�sa upp databasen.",
"lockbtn"               => "L�s databasen",
"unlockbtn"             => "L�s upp databasen",
"locknoconfirm"         => "Du har inte bekr�ftat l�sningen.",
"lockdbsuccesssub"      => "Databasen har l�sts",
"unlockdbsuccesssub"    => "Databasen har l�sts upp",
"lockdbsuccesstext"     => "Wikipediadatabasen �r l�st.
<br>Kom ih�g att ta bort l�sningen n�r du �r f�rdig med ditt underh�ll.",
"unlockdbsuccesstext"   => "Wikipediadatabasen �r uppl�st.",

// SQL query
//
"asksql"                => "SQL-fr�ga",
"asksqltext"            => "Anv�nd nedanst�ende formul�r f�r att st�lla fr�gor direkt till Wikipedias databas.
Anv�nd enkla citationstecken ('s� h�r') f�r att markera str�ngar.
Detta belastar ofta servern h�rt, s� anv�nd denna funktion med omtanke.",
"sqlquery"              => "Skriv fr�ga",
"querybtn"              => "Skicka fr�ga",
"selectonly"            => "Andra fr�gor �n \"SELECT\" f�r endast utf�ras av Wikipedias utvecklare.",
"querysuccessful"       => "Fr�gan genomf�rdes korrekt",

// Move page
//
"movepage"              => "Flytta sida",
"movepagetext"          => "Formul�ret nedan byter namn p� sidan och flyttar hela dess historia till det nya namnet. Den gamla sidan blir en omdirigeringssida till den nya. L�nkar till den gamla sidan kommer inte att �ndras. Om det finns en diskussionssida kommer den inte att flyttas. <b>OBS!</b> Detta kan inneb�ra en drastisk �ndring p� en popul�r sida; var s�ker p� att du inser konsekvenserna i f�rv�g.",
"movearticle"           => "Flytta sida",
"movenologin"           => "Ej inloggad",
"movenologintext"       => "Du m�ste vara registrerad anv�ndare och ha <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">loggat in</a>
f�r att kunna flytta en sida.",
"newtitle"              => "Till ny titel",
"movepagebtn"           => "Flytta sida",
"pagemovedsub"          => "Sidan har flyttats",
"pagemovedtext"         => "Sidan \"[[$1]]\" har flyttats till \"[[$2]]\".",
"articleexists"         => "Det finns redan en sida med detta namn eller s� �r namnet du angett ogiltigt. V�lj ett annat namn.",
"talkexists"            => "Sidan  flyttades korrekt, men den tilh�rande diskussionssidan kunde inte flyttas, eftersom det redan existerar en sida med denna nya titel. Du m�ste sammanfoga dem manuellt.",
"movedto"               => "flyttat till",
"movetalk"              => "Flytta �ven diskussionssidan, om den finns.",
"talkpagemoved"         => "Sidans diskussionssida flyttades ocks�.",
"talkpagenotmoved"      => "Sidans diskussionssida flyttades <strong>inte</strong>.",

);

class LanguageSv extends Language {

        function getNamespaces() {
                global $wgNamespaceNamesSv;
                return $wgNamespaceNamesSv;
        }

        function getBookstoreList () {
                global $wgBookstoreListSv ;
                return $wgBookstoreListSv ;
        }

        function getNsText( $index ) {
                global $wgNamespaceNamesSv;
                return $wgNamespaceNamesSv[$index];
        }

        function getNsIndex( $text ) {
                global $wgNamespaceNamesSv;

                foreach ( $wgNamespaceNamesSv as $i => $n ) {
                        if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
                }
		
                // Consider Special: and Speciel: equal... which is preferred?
                if ( 0 == strcasecmp( "speciel", $text ) ) { return -1; }
                return false;
        }

        // inherit specialPage()

        function getQuickbarSettings() {
                global $wgQuickbarSettingsSv;
                return $wgQuickbarSettingsSv;
        }

        function getSkinNames() {
                global $wgSkinNamesSv;
                return $wgSkinNamesSv;
        }

        function getUserToggles() {
                global $wgUserTogglesSv;
                return $wgUserTogglesSv;
        }

        function getMonthName( $key )
        {
                global $wgMonthNamesSv;
                return $wgMonthNamesSv[$key-1];
        }

        function getMonthAbbreviation( $key )
        {
                global $wgMonthAbbreviationsSv;
                return $wgMonthAbbreviationsSv[$key-1];
        }

        function getWeekdayName( $key )
        {
                global $wgWeekdayNamesSv;
                return $wgWeekdayNamesSv[$key-1];
        }

        # Inherit userAdjust()

        function date( $ts, $adj = false )
        {
                if ( $adj ) { $ts = $this->userAdjust( $ts ); }

                $d = (0 + substr( $ts, 6, 2 )) . " " .
                  $this->getMonthName( substr( $ts, 4, 2 ) ) . " " .
                  substr( $ts, 0, 4 );
                return $d;
        }

	// "." is used as the character to separate the
	// hours from the minutes in the date output
        function time( $ts, $adj = false )
        {
                if ( $adj ) { $ts = $this->userAdjust( $ts ); }

                $t = substr( $ts, 8, 2 ) . "." . substr( $ts, 10, 2 );
                return $t;
        }

        function timeanddate( $ts, $adj = false )
        {
                return $this->date( $ts, $adj ) . " kl." . $this->time( $ts, $adj );
        }

        function getValidSpecialPages()
        {
                global $wgValidSpecialPagesSv;
                return $wgValidSpecialPagesSv;
        }

        function getSysopSpecialPages()
        {
                global $wgSysopSpecialPagesSv;
                return $wgSysopSpecialPagesSv;
        }

        function getDeveloperSpecialPages()
        {
                global $wgDeveloperSpecialPagesSv;
                return $wgDeveloperSpecialPagesSv;
        }

	function getMessage( $key )
        {
                global $wgAllMessagesSv;
                if( array_key_exists( $key, $wgAllMessagesSv ) )
                        return $wgAllMessagesSv[$key];
                else
                        return Language::getMessage($key);
        }

}

?>
