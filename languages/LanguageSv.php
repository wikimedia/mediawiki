<?php

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
        2       => "Användare",
        3       => "Användare_diskussion",
        4       => $wgMetaNamespace,
        5       => $wgMetaNamespace."_diskussion",
        6       => "Bild",
        7       => "Bild_diskussion",
	8	=> "MediaWiki",
	9	=> "MediaWiki_diskussion",
);

/* inherit standard defaults */

/* private */ $wgQuickbarSettingsSv = array(
        "Ingen", "Fast vänster", "Fast höger", "Flytande vänster"
);

/* private */ $wgSkinNamesSv = array(
        "Standard", "Nostalgi", "Cologne Blå"
);

/* private */ $wgUserTogglesSv = array(
        "hover"            => "Svävande text över wikilänkar",
        "underline"        => "Understrukna länkar",
        "highlightbroken"  => "Röda länkar till tomma sidor",
        "justify"          => "Justera indrag",
        "hideminor"        => "Göm små redigeringar vid senaste ändring",
	"usenewrc"         => "Avancerad 'Senaste ändringar'",
        "numberheadings"   => "Automatisk numrering av överskrifter",
	"showtoolbar" => "Show edit toolbar",
        "rememberpassword" => "Kom ihåg lösenord till nästa besök",
        "editwidth"        => "Redigeringsboxen har full bredd",
        "editondblclick"   => "Redigera sidor med dubbelklick (JavaScript)",
	"editsection"      => "Visa [edit]-länkar för att redigera sektioner",
	"editsectiononrightclick" => "Högerklick på rubriker redigerar sektioner",
	"showtoc"          => "Visa automatisk innehållsförteckning (på sidor med mer än 3 sektioner)",
        "watchdefault"     => "Övervaka nya och ändrade artiklar",
        "minordefault"     => "Markera som standard alla ändringer som mindre",
	"previewontop"     => "Visa förhandsgranskning före textfältet istället för efter",
	"nocache"          => "Slå av cachning av sidor"
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
        "söndag", "måndag", "tisdag", "onsdag", "torsdag",
        "fredag", "lördag"
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
        "Preferences"   => "Mina användarinställningar",
        "Watchlist"     => "Min övervakningslista",
        "Recentchanges" => "Senaste ändringar",

        "Upload"        => "Ladda upp filer",
        "Imagelist"     => "Bildlista",
        "Listusers"     => "Registrerade användare",
        "Statistics"    => "Sidstatistik",

        "Randompage"    => "Slumpmässig sida",
        "Lonelypages"   => "Föräldralösa sidor",
        "Unusedimages"  => "Föräldralösa filer",
        "Popularpages"  => "Populära artiklar",
        "Wantedpages"   => "Mest önskade artiklar",
        "Shortpages"    => "Korta artiklar",
        "Longpages"     => "Långa artiklar",
        "Newpages"      => "De nyaste artiklarna",
        "Ancientpages"  => "Oldest pages",
        "Allpages"      => "Alla sidor efter titel",

        "Ipblocklist"   => "Blockerade IP adresser",
        "Maintenance"   => "Underhållssida",
        "Specialpages"  => "Specialsidor",
        "Contributions" => "",
        "Emailuser"     => "E-postanvändare",
        "Whatlinkshere" => "",
        "Recentchangeslinked" => "",
        "Movepage"      => "",
        "Booksources"   => "Externa bokkällor",
        "Export"        => "XML export",
		"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesSv = array(
	"Makesysop" => "Turn a user into a sysop",
        "Blockip"       => "Blockera en IP-adress",
        "Asksql"        => "Gör en sökning i databasen",
        "Undelete"      => "Se och återställ raderade sidor"
);

/* private */ $wgDeveloperSpecialPagesSv = array(
        "Lockdb"        => "Skrivskydda databasen",
        "Unlockdb"      => "Återställ skrivning till databasen",
);

/* private */ $wgAllMessagesSv = array(

// Bits of text used by many pages:
//	
"linktrail"             => "/^([a-zäöå]+)(.*)\$/sD",
"mainpage"              => "Huvudsida",
"about"                 => "Om",
"aboutwikipedia"        => "Om Wikipedia",
"aboutpage"             => "{$wgMetaNamespace}:Om",

"help"                  => "Hjälp",
"helppage"              => "{$wgMetaNamespace}:Hjälp",
"wikititlesuffix"       => "Wikipedia",
"bugreports"            => "Felrapporter",
"bugreportspage"        => "{$wgMetaNamespace}:Felrapporter",
"sitesupport"           => "Donationer",
"sitesupportpage"       => "", # If not set, won't appear. Can be wiki page or URL
"faq"                   => "FAQ",
"faqpage"               => "{$wgMetaNamespace}:FAQ",
"edithelp"              => "Redigeringshjälp",
"edithelppage"          => "{$wgMetaNamespace}:Hur_redigerar_jag_en_sida",
"cancel"                => "Avbryt",
"qbfind"                => "SnabbSök",
"qbbrowse"              => "Genomsök",
"qbedit"                => "Redigera",
"qbpageoptions"         => "Sidinställningar",
"qbpageinfo"            => "Sidinformation",
"qbmyoptions"           => "Mina inställningar",
"mypage"                => "Min sida",
"mytalk"                => "Min diskussion",
"currentevents"         => "-",
"errorpagetitle"        => "Fel",
"returnto"              => "Tillbaka till $1.",
"fromwikipedia"         => "Från Wikipedia, den fria encyklopedin.",
"whatlinkshere"         => "Vilka sidor länkar hit?",
"help"                  => "Hjälp",
"search"                => "Sök",
"history"               => "Versionshistorik",
"printableversion"      => "Skrivarvänlig version",
"editthispage"          => "Redigera den här sidan",
"deletethispage"        => "Radera den här sidan",
"protectthispage"       => "Skydda den här sidan",
"unprotectthispage"     => "Ta bort skydd av denna sida",
"newpage"               => "Ny sida",
"talkpage"              => "Diskussionssida",
"postcomment"           => "Skriv ett inlägg",
"articlepage"           => "Visa artikel",
"subjectpage"           => "Ämnessida",
"userpage"              => "Visa användarsida",
"wikipediapage"         => "Visa metasida",
"imagepage"             => "Visa bildsida",
"otherlanguages"        => "Andra språk",
"redirectedfrom"        => "(Omdirigerad från $1)",
"lastmodified"          => "Denna sida blev senast ändrad $1.",
"viewcount"             => "Denna sida har visats $1 gånger.",
"gnunote"               => "Denna sida är publicerad under <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>.",
"printsubtitle"         => "(Från http://sv.wikipedia.org)",
"protectedpage"         => "Skyddad sida",
"administrators"        => "{$wgMetaNamespace}:Administratörer",
"sysoptitle"            => "Sysop-behörighet krävs",
"sysoptext"             => "Denna funktion kan bara utföras av användare med \"sysop\" status.
Se $1.",
"developertitle"        => "Utvecklarbehörighet krävs",
"developertext"         => "Denna funktion kan bara utföras av användare med \"developer\" status.
Se $1.",
"nbyte"                 => " bytes",
"go"                    => "Utför",
"ok"                    => "OK",
"sitetitle"             => "Wikipedia",
"sitesubtitle"          => "Den fria encyklopedin",
"retrievedfrom"         => "Hämtat från \"$1\"",
"newmessages"           => "Du har $1.",
"newmessageslink"       => "nya meddelanden",
"editsection"           => "edit",
"toc"                   => "Innehåll",
"showtoc"               => "visa",
"hidetoc"               => "göm",
"thisisdeleted"         => "Visa eller återställ $1?",
"restorelink"           => "$1 raderade versioner",

// Main script and global functions
//
"nosuchaction"          => "Funktionen finns inte",
"nosuchactiontext"      => "Den funktion som specificerats i URL:en kan inte
hittas av Wikipediaprogramvaran",
"nosuchspecialpage"     => "Sådan specialsida finns inte",
"nospecialpagetext"     => "Du har önskat en specialsida som inte
hittas av Wikipediaprogramvaran.",

// General errors
//
"error"                 => "Fel",
"databaseerror"         => "Databasfel",
"dberrortext"           => "Ett syntaxfel i databasfrågan har uppstått. Detta kan bero på en felaktig sökfråga (se $5) eller det kan bero på ett fel i programvaran.
Den senste utförda databasfrågan var:
<blockquote><tt>$1</tt></blockquote>
från funktionen \"<tt>$2</tt>\".
MySQL returnerade felen \"$3<tt>: $4</tt>\".",
"noconnect"             => "Kunde inte ansluta till databasen på $1",
"nodb"                  => "Kunde inte välja databasen $1",
"readonly"              => "Databasen är skrivskyddad",
"enterlockreason"       => "Skriv en grund för skrivskyddet, inklusive 
en uppskattning på när skrivskyddet skall upphävas",
"readonlytext"          => "Wikipediadatabasen är för ögonblicket skrivskyddad för 
nya sidor och andra modifikationer, beroende på rutinmässigt 
underhåll av databasen, varefter den återgår till normalstatus.
Den administratör som skrivskyddade den har gett följande förklaring:
<p>$1",
"missingarticle"        => "Databasen fann inte texten på en sida
som den skulle hitta, med namnet \"$1\".
Dette är inte ett databas fel, utan beror på ett fel i mjukvaran.
Skicka vänligen en rapport om detta till en administratör, där du också nämner 
URL:en.",
"internalerror"         => "Internt fel",
"filecopyerror"         => "Kunde inte kopiera filen \"$1\" til \"$2\".",
"filerenameerror"       => "Kunde inte byta namn på filen \"$1\" til \"$2\".",
"filedeleteerror"       => "Kunde inte radera filen \"$1\".",
"filenotfound"          => "Kunde inte hitta filen \"$1\".",
"unexpected"            => "Oväntat värde: \"$1\"=\"$2\".",
"formerror"             => "Fel: Kunde inte sända formulär",
"badarticleerror"       => "Denna funktion kan inte utföras på denna sida.",
"cannotdelete"          => "Kunde inte radera sidan, eller filen som specificerades.",
"badtitle"              => "Felaktig titel",
"badtitletext"          => "Den önskade sidans titel var inte tillåten, tom eller sidan
är felaktigt länkad från en Wikipedia på ett annat språk.",


// Login and logout pages
//
"logouttitle"           => "Logga ut Användare",
"logouttext"            => "Du är nu utloggad.
Du kan fortsätta som anonym Wikipediaanvändare, eller så kan du logga in
igen som samma eller annan användare.\n",

"welcomecreation"       => "<h2>Välkommen, $1!</h2><p>Ditt konto har skapats. Glöm inte att anpassa dina Wikipediainställningar.",

"loginpagetitle"        => "Logga in Användare",
"yourname"              => "Ditt användarnamn",
"yourpassword"          => "Ditt lösenord",
"yourpasswordagain"     => "Upprepa lösenord",
"newusersonly"          => " (bara för nya användare)",
"remembermypassword"    => "Kom ihåg mitt lösenord till nästa gång.",
"loginproblem"          => "<b>Det var svårt att logga in dig .</b><br>Pröva igen!",
"alreadyloggedin"       => "<font color=red><b>Användare $1, du är redan inloggad !</b></font><br>\n",

"login"                 => "Logga in",
"userlogin"             => "Logga in",
"logout"                => "Logga ut",
"userlogout"            => "Logga ut",
"notloggedin" 		=> "Ej inloggad",
"createaccount"         => "Skapa ett konto",
"badretype"             => "De lösenord du uppgett överenstämmer inte med varandra.",
"userexists"            => "Detta användarnamn används redan. Ange ett annat användarnamn.",
"youremail"             => "Din e-postadress",
"yournick"              => "Ditt smeknamn (till signaturer)",
"emailforlost"          => "Har du glömt ditt lösenord, så kan du få ett nytt lösenord skickat till din e-post",
"loginerror"            => "Inloggningsproblem",
"noname"                => "Det användarnamn som du angett finns inte",
"loginsuccesstitle"     => "Inloggningen lyckades",
"loginsuccess"          => "Du är nu inloggad på wikipedia med användarnamnet \"$1\".",
"nosuchuser"            => "Det finns ingen användare med namnet \"$1\".
Kontrollera stavningen, eller använd formuläret nedan för att skapa ett nytt konto.",
"wrongpassword"         => "Lösenordet du skrev är felaktigt. Pröva igen",
"mailmypassword"        => "Sänd mig ett nytt lösenord",
"passwordremindertitle" => "Nytt lösenord från Wikipedia",
"passwordremindertext"  => "Någon (förmodligen du, med IP-numret $1)
har bett oss sända dig ett nytt lösenord för din Wikipedia-inloggning
Lösenordet för användare \"$2\" är nu \"$3\".
Du ska logga in på din användare och byta lösenord.",
"noemail"               => "Det finns ingen e-postadress registrerad för användare \"$1\".",
"passwordsent"          => "Ett nytt lösenord har skickats till e-posten registrerad av användaren\"$1\".
Var snäll och logga in igen när du fått meddelandet.",


// Edit pages
//
"summary"               => "Sammanfattning",
"minoredit"             => "Detta är en mindre ändring",
"watchthis"             => "Bevaka den här artikeln",
"savearticle"           => "Spara",
"preview"               => "Förhandsgranska",
"showpreview"           => "Visa förhandgranskning",
"blockedtitle"          => "Användaren är spärrad",
"blockedtext"           => "Ditt användarnamn har blivit spärrat av $1.
Anledning är att:<br>''$2''<p>Ta kontakt med $1 eller en av de andra
[[{$wgMetaNamespace}:Administratörer|administratörerna]] för att diskutera varför du blivit spärrad", // "
"newarticle"            => "(Ny)",
"newarticletext"        => "Du har klickat på en röd länk, en sida som inte finns ännu. Du kan hjälpa till genom att själv skriva vad du vet om ämnet i fältet nedan. Om du inte vill skriva något kan du bara trycka på \"tillbaka\" i din webbläsare.",
"anontalkpagetext"      => "---- ''Detta är en diskussionssida för en anonym användare, en användare som inte har skapat sig ett konto, eller som inte har loggat in på det. Vi måste därför använda personens numeriska [[IP-adress]] för identifiera honom eller henne. En sådan IP-adress kan ibland användas av flera olika personer. Om du är en anonym användare och ser meddelanden på den här sidan som inte tycks vara riktade till dig, var vänlig [[Special:Userlogin|logga in]] så du undviker förväxling med andra anonyma användare i framtiden.'' ",
"noarticletext"         => "(Det finns för tillfället ingen text på den här sidan.)",
"updated"               => "(Uppdaterad)",
"note"                  => "<strong>Notera:</strong> ",
"previewnote"           => "Observera att detta är en förhandsvisning, och att sidan ännu inte sparats!",
"previewconflict"       => "Denna förhandsvisning är resultatet av den 
redigerbara texten ovanför,
så som det kommer att se ut om du väljer att spara.",
"editing"               => "Redigerar $1",
"editconflict"          => "Redigeringskonflikt: $1",
"explainconflict"       => "Någon har ändrat denna sida efter att du började att redigera den.
Det översta text blocket innehåller den nuvarande texten.
Dina ändringer syns i det nedersta blocket.
Du måste infoga dina ändringar i den existerande texten.
<b>Bara</b> texten i den översta textboxen sparas om du trycker \"Spara sida\".\n<p>",
"yourtext"              => "Din text",
"storedversion"         => "Din sparade version",
"editingold"            => "<strong>VARNING: Du redigerar en gammal version
av denna sida. Om du sparar den, kommer alla ändringar på denns sida föregående revison att bli överskrivna.</strong>\n",
"yourdiff"              => "Skillnader",
"copyrightwarning"      => "Observera att alla bidrag till Wikipedia är
att betrakta som utgivna under GNU Free Documentation License
(se $1 för detaljer).
Om du inte vill ha din text redigerad och kopierad efter andras gottfinnade så skall du inte skriva någon text här.<br>
Du lovar oss också att du skrev texten själv, eller kopierade från public domain eller liknande fri resurs.<BR>
<strong>LÄGG ALDRIG UT UPPHOVSRÄTTSSKYDDAT MATERIAL HÄR UTAN FÖRFATTARENS TILLÅTELSE!</strong>",

"longpagewarning"       => "VARNING: Den här artikeln är $1 kilobytes lång; vissa äldre webbläsare
kan ha problem med att redigera sidor som är omkring 32 kb eller större.
Du kanske vill överväga att dela upp artikeln i mindre delar.",
"readonlywarning"       => "VARNING: Databasen är tillfälligt låst för underhåll. Du kommer inte att kunna spara 
dina ändringar just nu. För säkerhets skull kanske du vill överväga att kopiera över texten till din egen dator tills
databasen är upplåst igen.",
"protectedpagewarning"  => "VARNING:  Den här sidan har låsts så att bara administratörer kan redigera den. 
Försäkra dig om att du följer rekommendationerna för <a href='/wiki/{$wgMetaNamespace}:Skyddade sidor'>skyddade sidor</a>",

// History pages
//
"revhistory"            => "Versionshistoria",
"nohistory"             => "Det finns ingen versionshistoria för denna sida.",
"revnotfound"           => "Versionen hittades inte",
"revnotfoundtext"       => "Den gamla versionen av den sida du frågade efter kan inte hittas. Kontrollera den URL du använde för att nå denna sida.\n",
"loadhist"              => "Läser sidans versioner",
"currentrev"            => "Nuvarande version",
"revisionasof"          => "Versionen från $1",
"cur"                   => "nuvarande",
"next"                  => "nästa",
"last"                  => "föregående",
"orig"                  => "original",
"histlegend"            => "Förklaring: (nuvarande) = skillnad mot den nuvarande versionen,
 (föregående) = skillnad mot den föregående versionen, M = mindre ändring",


// Diffs
//
"difference"            => "(Skillnad mellan versioner)",
"loadingrev"            => "läser version för att se skillnad",
"lineno"                => "Rad $1:",
"editcurrent"           => "Redigera den nuvarande versionen av denna sida",

// Search results
//
"searchresults"         => "Sökresultat",
"searchhelppage"        => "{$wgMetaNamespace}:Sökning",
"searchingwikipedia"    => "Sökning på Wikipedia",
"searchresulttext"      => "För mer information om sökning på Wikipedia, se $1.",
"searchquery"           => "For query \"$1\"",
"badquery"              => "Felaktigt utformat sökbegrepp",
"badquerytext"          => "Vi kunde inte utföra din sökning.
Detta beror sannolikt på att du försökt söka efter ett ord med färre än tre bokstäver, något som f.n. inte stöds. Det kan också vara så att du har anget en felaktig sökning, till exempel \"fisk och och skaldjur\". Prova att formulera om sökningen.",
"matchtotals"           => "Sökordet förekommer i \"$1\" överensstämde med $2 artiklar titlar
och texter i $3 artiklar.",
"titlematches"          => "Artikel titlar som överensstämmer med sökordet",
"notitlematches"        => "Ingen artikel titlar överensstämmer med sökordet",
"textmatches"           => "Artikel texter som överensstämmer med sökordet",
"notextmatches"         => "Ingen artikel texter överensstämmer med sökordet",
"prevn"                 => "förra $1",
"nextn"                 => "nästa $1",

"viewprevnext"          => "Om ($1) ($2) ($3).",
"showingresults"        => "Nedan visas <b>$1</b> resultat som startar med nummer <b>$2</b>.",
"nonefound"             => "<strong>Note</strong>: Misslyckade sökningar förorsakas ofta av
 att man söker efter vanliga ord som \"har\" och \"från\",
vilka inte indexeras, eller att specificera flera sökord (bara 
sidor som innehåller alla sökorden hittas).",
"powersearch"           => "Sök",
"powersearchtext"       => "
Sök i namnutrymme :<br>
$1<br>
$2 List redirects &nbsp; Sök efter $3 $9",


// Preferences page
//
"preferences"           => "Inställningar",
"prefsnologin"          => "Du är inte inloggad",
"prefsnologintext"      => "Du måste vara <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
för att kunna ändra i inställningar.",
"prefslogintext"        => "Du är inloggad som \"$1\".
Ditt IP-nummer är $2.",
"prefsreset"            => "Inställningar har blivit återställda från minne.",
"qbsettings"            => "Inställningar för snabbmeny",
"changepassword"        => "Byt lösenord",
"skin"                  => "Utseende",
"saveprefs"             => "Spara inställningar",
"resetprefs"            => "Återställ inställningar",
"oldpassword"           => "Gammalt lösenord",
"newpassword"           => "Nytt lösenord",
"retypenew"             => "Skriv om nytt lösenord",
"textboxsize"           => "Textbox dimensioner",
"rows"                  => "Rader",
"columns"               => "Kolumner",
"searchresultshead"     => "Inställningar för sökresultat",
"resultsperpage"        => "Resultat att visa per sida",
"contextlines"          => "Linjer att visa per sida",
"contextchars"          => "Antalet bokstäver per linje i resultatet",
"stubthreshold"         => "Gränser för visning av stubs",
"recentchangescount"    => "Antalet artiklar i \"senaste ändringarna\" ",
"savedprefs"            => "Dina inställningar har blivit sparade",
"timezonetext"          => "Skriv in antalet timmar som din lokal tid skiljer sig från
serverns klocka (UTC).
Den blir automatiskt inställd efter svensk tid eller skulle man till exempel för svensk vintertid, endast ha \"1\" (och \"2\" när vi har sommartid).",
"localtime"             => "Lokal tid",
"timezoneoffset"        => "Utjämna",
"emailflag"             => "Hindra andra användare från att skicka e-post till dig",

// Recent changes
//
"recentchanges"         => "Senaste ändringarna",
"recentchangestext"     => "Se de senaste redigerade sidorna i Wikipedia på denna sida.",
"rcloaderr"             => "Läser senaste redigerade sidor",
"rcnote"                => "Nedanför är de senaste <strong>$1</strong> ändringarna under de sista <strong>$2</strong> dagarna.",
"rcnotefrom"            => "Nedanför är ändringarna från <b>$2</b> till <b>$1</b> visade.",
"rclistfrom"            => "Visa nya ändringar från och med $1",
"rclinks"               => "Visa de senaste $1 ändringarna under de senaste $2 dagarna",
// "rclinks"             => "Visa de senaste $1 ändringarna under de senaste $2 dagarna",
"rchide"                => "i $4 form; $1 mindre ändringar; $2 andra namnrum; $3 mer än en redigering.",
"diff"                  => "skillnad",
"hist"                  => "historia",
"hide"                  => "göm",
"show"                  => "visa",

"tableform"             => "tabell",
"listform"              => "lista",
"nchanges"              => "$1 ändringar",
"minoreditletter"       => "M",
"newpageletter"         => "N",

// Upload
//
"upload"                => "Ladda upp",
"uploadbtn"             => "Ladda upp fil",
"uploadlink"            => "Ladda upp bild",
"reupload"              => "återuppladdning",
"reuploaddesc"          => "Tillbaka till uppladdningsformulär.",
"uploadnologin"         => "Inte inloggad",
"uploadnologintext"     => "Du måste vara <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
för att kunna ladda upp filer.",
"uploadfile"            => "Ladda upp fil",
"uploaderror"           => "Uppladdnings fel",
"uploadtext"            => "<strong>STOPP!</strong> Innan du laddar upp här,
så måste du ha läst och följa Wikipedias <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Policy om bruk av bilder" ) . "\">policy om hur 
bilder får användas</a>.
<p>För att visa eller söka tidigare uppladdade bilder gå till
<a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">lista över uppladdade bilder</a>.
Uppladdningar och borttagningar loggas i <a href=\"" .
wfLocalUrlE( "{$wgMetaNamespace}:Upload_log" ) . "\">uppladdnings logg</a>.
<p>Använd formuläret nedan för att ladda upp nya filer, som 
du kan illustrera dina artiklar med.
På de flesta webbläsare kommer du att se en \"Browse...\" knapp eller en 
\"Öppna...\" knapp, som startar ditt operativsystems dialogruta för att öppna filer. När du valt en fil kommer namnet på den filen att visas i textfältet brevid knappen. Du måste även kryssa för rutan, för att du inte gör något som strider mot upphovsrätten av filen som laddas upp.
Tryck på \"Upload\" knappen för att ladda upp filen.
Detta kan dröja ett tag om du har en långsam internetförbindelse.
<p>Formaten på filerna ska helst vara JPEG för bilder, PNG för ritningar 
och andra ikonliknande bilder och OGG för ljud.
Var vänlig namnge filen med ett så beskrivande namn som möjligt, för att undvika förvirring.
För att använda en fil i en artikel, skriv följande om det är en bild: <b>[[bild:filnamn.jpg]]</b> eller <b>[[bild:filnamn.png|alternativ text]]</b>
eller <b>[[media:filnamn.ogg]]</b> om det är en ljudfil.
<p>Kom ihåg att det här är en wiki, vilket gör att andra kan redigera eller ta bort dina uppladdningar om de tycker de inte passar i en artikel. Om du missbrukar systemet med uppladdningar kommer filen avlägsnas och du bli spärrad från att ladda upp filer i framtiden.",
"uploadlog"             => "upload log",
"uploadlogpage"         => "Upload_log",
"uploadlogpagetext"     => "Nedan följer en lista med de senaste uppladdade filerna.
Alla tider visas efter serverns tid (UTC).
<ul>
</ul>
",
"filename"              => "Filnamn",
"filedesc"              => "Beskrivning",
"affirmation"           => "Jag bekräftar att ägaren till upphovsrätten accepterar att licensiera enligt följande avtal $1.",
"copyrightpage"         => "{$wgMetaNamespace}:copyright",
"copyrightpagename"     => "Wikipedia copyright",
"uploadedfiles"         => "Uppladdade filer",
"noaffirmation"         => "Du måste bekräfta att uppladdningen inte kränker någon copyright",
"ignorewarning"         => "Ignorera varning och spara fil.",
"minlength"             => "Namnet på bildfilen ska vara minst tre bokstäver",
"badfilename"           => "Bildnamn har blivit ändrat till \"$1\".",
"badfiletype"           => "\".$1\" är inte ett rekomenderat bildformat.",
"largefile"             => "Bilder ska helst inte vara större än 100k.",
"successfulupload"      => "Uppladdningen lyckades",
"fileuploaded"          => "Filen \"$1\" laddades upp korrekt.
Följ denna länk: ($2) till beskrivningssidan och fyll i
information om filen, som till exempel var den kommer ifrån, 
när den skapades och vem som gjort den och allt annat du vet om den.",
"uploadwarning"         => "Uppladdnings varning",
"savefile"              => "Spara fil",
"uploadedimage"         => "uppladdad \"$1\"",

// Image list
//
"imagelist"             => "Bildlista",
"imagelisttext"         => "Nedan är en lista med $1 bilder sorterad $2",
"getimagelist"          => "hämta bildlista",
"ilshowmatch"           => "Visa alla bilder med namn som matchar",
"ilsubmit"              => "Sök",
"showlast"              => "Visa de senaste $1 bilderna sorterad $2.",
"all"                   => "alla",
"byname"                => "efter namn",
"bydate"                => "efter datum",
"bysize"                => "efter storlek",
"imgdelete"             => "ta bort",
"imgdesc"               => "beskrivning",
"imglegend"             => "Legend: (beskrivning) = visa/redigera bildbeskrivning.",
"imghistory"            => "Bildhistoria",
"revertimg"             => "återgå",
"deleteimg"             => "radera",
"imghistlegend"         => "Legend: (nuvarande) = detta är den nuvarande bilden, 
(ta bort) = ta bort den gamla version, (återgå) = återgå till en gammal version.
<br><i>Klicka på ett datum för att se bilden som laddades upp den dagen</i>.", //"
"imagelinks"            => "Bildlänk",
"linkstoimage"          => "De följande sidorna länkar till denna bild:",
"nolinkstoimage"        => "Det finns ingen sida som länkar till denna bild.",

// Statistics
//
"statistics"            => "Statistik",
"sitestats"             => "Statistiksida",
"userstats"             => "Användarstatistik",
"sitestatstext"         => "Det är <b>$1</b> sidor i databasen.
Detta inkluderer diskussionssidorna, sidor om Wikipedia, mycket korta\"stub\"
sidor, omdirigeringssidor, och andra sidor som kvalificerar sig som artiklar.
Om man tar bort ovanstående så är det <b>$2</b> sidor som anses som riktiga artiklar.<p>
Det har varit totalt <b>$3</b> sidvisningar och det har varit <b>$4</b> sidor som har ändrats
sedan uppdateringen av mjukvaran (1 december 2002).
Det vill säga <b>$5</b> ändringar per sida genomsnittligt, 
och <b>$6</b> sidvisningar per ändring.",
"userstatstext"         => "Det är <b>$1</b> registrerade användare.
<b>$2</b> av dem är administratörer (se $3).",

// Maintenance Page
//
"maintenance"           => "Underhållssida",
"maintnancepagetext"    => "Den här sidan innehåller flera verktyg för att sköta sidan. Vissa av dessa funktioner tenderar att stressa databasen (allt tar lång tid), så var snäll och  tryck inte på reloadknappen varje gång du gjort en liten ändring.",
"maintenancebacklink"   => "Tillbaka till underhållssidorna",
"disambiguations"       => "Sidor med tvetydiga länkar",
"disambiguationspage"   => "{$wgMetaNamespace}:Länkar till sidor med tvetydiga titlar",
"disambiguationstext"   => "Följande artiklar länkar till en <i>sidor med tvetydliga titlar</i>. De ska länka till en sidor med en korrekt titel.<br>En sida behandlar som tvetydig om den länkar från $1. <br>Länkar från andra namngrupper är <i>inte</i> listade här.",
"doubleredirects"       => "Dubbla omdirigeringar",
"doubleredirectstext"   => "<b>OBS:</b> Denna lista kan innehålla falska resultat. Detta betyder normalt att det finns ytterligare text under den första #REDIRECT.<br>\n Varje rad innehåller en länk till den första och andra omdirigering och den första raden av den andra omdirigeringen ger oftast den \"riktiga\" artikeln, vilket egentligen den första omdirigeringen ska peka på.",
"brokenredirects"       => "Dåliga omdirigeringar",
"brokenredirectstext"   => "Följande länkar omdirigerar till en artikel som inte existerar.",
"selflinks"             => "Sidor med länkar till sig själva",
"selflinkstext"         => "Följande sidor innehåller länkar till sig själv, vilket de inte ska göra.",
"mispeelings"           => "Sidor med felstavningar",
"mispeelingstext"       => "Följande sidor innerhåller vanliga felstavningar, som visas i $1. Den korrekta stavningen kanske ska se ut såhär.",
"mispeelingspage"       => "Lista med vanliga stavfel",
"missinglanguagelinks"  => "Saknade språklänkar",
"missinglanguagelinksbutton"    => "Sök efter saknade språklänkar för",
"missinglanguagelinkstext"      => "De här artiklarna är <i>inte</i> länkade 
till deras i $1. Redirects och undersidor visas <i>inte</i>.",

// Miscellaneous special pages
//
"orphans"               => "Föräldralösa sidor",
"lonelypages"           => "Föräldralösa sidor",
"unusedimages"          => "Oanvända bilder",
"popularpages"          => "Populära sidor",
"nviews"                => "$1 visningar",
"wantedpages"           => "Önskelista",
"nlinks"                => "$1 länkar",
"allpages"              => "Alla sidor",
"randompage"            => "Slumpartikel",
"shortpages"            => "Korta sidor",
"longpages"             => "Långa sidor",
"listusers"             => "Användarlista",
"specialpages"          => "Speciella sidor",
"spheading"             => "Speciella sidor",
"sysopspheading"        => "Speciella sidor för sysop",
"developerspheading"    => "Speciella sidor för utvecklare",
"protectpage"           => "Skydda sida",
"recentchangeslinked"   => "Relaterade ändringar",
"rclsub"                => "(till sidor som är länkade från \"$1\")",
"debug"                 => "Debug",
"newpages"              => "Nya sidor",
"movethispage"          => "Flytta den här sidan",
"unusedimagestext"      => "<p>Lägg märket till att andra hemsidor
som till exempel de internationella wikipedias kan länka till bilder 
med en direkt URL, och kan därför bli listade här trots att de används kontinuerligt.",
"booksources"           => "Bokkällor",
"booksourcetext"        => "Nedan följer en lista över länkar till hemsidor som säljer
nya och begagnade böcker, och mycket annan information om de böcker du söker.
Wikipedia har <b>inget</b> affärssamarbete med ovanstående företag och ska inte heller tolkas som en uppmuntran.",

// Email this user
//
"mailnologin"           => "Ingen adress att skicka till",
"mailnologintext"       => "Du ska vara<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
och ha angivit en korrekt epost-adress i dina <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">användarinställningar</a>
för att kunna skicka e-post till andra användare.",
"emailuser"             => "Skicka e-post till den här användaren",
"emailpage"             => "Skicka e-post till annan användare",
"emailpagetext"         => "Om den här användaren har skrivit in en korrekt e-postadress, i sina
användarinställningar, kommer formuläret nedan skicka ett meddelande.
Den epost-adress du anget i dina användarinställningar kommer att skrivas
i \"Från\"fältet i detta e-post, så mottagaren har möjlighet att svara.",
"noemailtitle"          => "Ingen e-postadress",
"noemailtext"           => "Den här användaren har inte angivet en korrekt e-postadress eller
valt att inte ta emot något mail från andra användare.",
"emailfrom"             => "Från",
"emailto"               => "Till",
"emailsubject"          => "Ämne",
"emailmessage"          => "Meddelande",
"emailsend"             => "Skickat",
"emailsent"             => "E-post är nu skickat",
"emailsenttext"         => "Din e-post har skickats.",

// Watchlist
//
"watchlist"             => "Min övervakningslista",
"watchlistsub"          => "(för användare \"$1\")",
"nowatchlist"           => "Du har inga sidor upptagna på din övervakningslista.",
"watchnologin"          => "Du är inte inloggad",
"watchnologintext"      => "Du ska vara<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">inloggad</a>
för att kunna göra ändringar på din övervakningslista.",
"addedwatch"            => "Tillagd på övervakningslistan",
"addedwatchtext"        => "Sidan \"$1\" har satts upp på din <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">övervakningslista</a>.
Framtida ändringar av den här sidan och dess diskussionssida vill listas där, 

och sidan kommer att markeras med <b>fet stil</b> i <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">listan över de senaste ändringarna
</a> för att lättare kunna hittas</p>

<p>Om du vill ta bort denna sida från din övervakningslista, så klicka 
\"Ta bort övervakning\" ute i sidan.",
"removedwatch"          => "Borttagen från övervakningslista",
"removedwatchtext"      => "Sidan \"$1\" har blivit borttagen från din övervakningslista",
"watchthispage"         => "Övervaka sida",
"unwatchthispage"       => "Stoppa övervakning",
"notanarticle"          => "Inte en artikel",

// Delete/protect/revert
//
"deletepage"            => "Ta bort sida",
"confirm"               => "Bekräfta",
"excontent"             => "före radering:",
"exbeforeblank"         => "före tömning:",
"exblank"               => "sidan var tom",
"confirmdelete"         => "Bekräfta borttagning",
"deletesub"             => "(Tar bort \"$1\")",
"confirmdeletetext"     => "Du håller på och permanent ta bort en sida
eller bild med all dess historia från databasen.
Bekräfta att du förstår vad du håller på med och vilka konsekvenser
detta leder till, och att det följer 
[[{$wgMetaNamespace}:Policy]].", 
"confirmcheck"          => "Ja, jag vill verkligen ta bort det här.", //"
"actioncomplete"        => "Genomfört",
"deletedtext"           => "\"$1\" har blivit borttagen.
Se $2 för lista över senaste borttagningar",
"deletedarticle"        => "borttagen \"\$1\"",
"dellogpage"            => "Borttagningslogg",
"dellogpagetext"        => "Nedan är en lista över de senaste borttagningarna.
De tidsangivelser som anges följer serverns klocka (UTC).
<ul>
</ul>
",
"deletionlog"           => "borttagningslogg",
"reverted"              => "Återgått till yngre version",
"deletecomment"         => "Anledningen till borttagning",
"imagereverted"         => "Återställandet av nyare artikelversion lyckades",

// Undelete
//
"undelete"              => "Återställ borttagna sidor",
"undeletepage"          => "Visa och återställ borttagna sidor",
"undeletepagetext"      => "Följande sidor har blivit borttagna, men är fortfarande i arkivet och kan användas vid återställning.
Arkivet kan ibland rensas på gamla versioner.",
"undeletearticle"       => "Återställ borttagen artikel",
"undeleterevisions"     => "$1 versioner arkiverade",
"undeletehistory"       => "Om du återställer sidan kommer allt tidigare versioner att sparas i versionshanteraren.
Om en ny sida med samma namn har blivit skapad sedan borttagningen så kommer den återställda versionen att
hamna i den äldre versionshanteraren och den senaste versionen av sidan kommer inte blir automatiskt ersatt.",
"undeleterevision"      => "Tog bort version $1",
"undeletebtn"           => "Återställd!",
"undeletedarticle"      => "återställd \"$1\"",
"undeletedtext"         => "Artikeln [[$1]] har blivit återställd
Se [[{$wgMetaNamespace}:Borttagningslogg]] för en lista över nyligen gjorda borttagningar och återställningar",

// Contributions
//
"contributions"         => "Användarbidrag",
"mycontris"             => "Mina bidrag",
"contribsub"            => "För $1",
"nocontribs"            => "Inga ändringar var funna som motsvarar dessa kriterier",
"ucnote"                => "Nedan visas dennes användares senaste <b>$1</b> ändringar, under de senaste <b>$2</b> dagarna.",
"uclinks"               => "Visa de senaste $1 ändringarna. Visa de senaste $2 dagarna.",
"uctop"                 => " (top)",

// What links here
//
"whatlinkshere"         => "Vilka sidor länkar hit",
"notargettitle"         => "Inget mål",
"notargettext"          => "Du har inte specificerat en sida eller användare
för att genomföra den här funktionen.",
"linklistsub"           => "(Länklista)",
"linkshere"             => "Följande sidor länkas hit:",
"nolinkshere"           => "Inga sidor länkar hit.",
"isredirect"            => "Länka vidare sida",

// Block/unblock IP
//
"blockip"               => "Blockera IP-adress",
"blockiptext"           => "Använd formuläret nedan för att blockera skrivåtkomst 
från en viss IP-adress
Detta ska bara genomföras för att stoppa klotter och
överstämma med [[{$wgMetaNamespace}:Politik|Wikipedia politik]].
Fyll i anledningen till blockering nedan (till exempel vilka artiklar som klottrats ner).",
"ipaddress"             => "IP-adress",
"ipbreason"             => "Anledning",
"ipbsubmit"             => "Blockera den här IP-adressen",
"badipaddress"          => "Du har inte skrivit IP-adressen korrekt.",
"noblockreason"         => "Du måste ange en anledning till varför du blockerar.",
"blockipsuccesssub"     => "Blockeringen lyckades",
"blockipsuccesstext"    => "IP-adressen \"$1\" har blockerats.
<br>Se [[Speciel:Ipblocklist|IP blockeringslistan]] för alla blockeringar.",
"unblockip"             => "Ta bort blockering av IP-adress",
"unblockiptext"         => "Använd nedanstående formulär för att återställa skrivrättigheten för en tidigare blockerad IP-adress.",
"ipusubmit"             => "Ta bort blockering för denna adress",
"ipusuccess"            => "Blockeringen för IP-adressen \"$1\" har tagits bort",

"ipblocklist"           => "Lista över blockerade IP-adresser",
"blocklistline"         => "$1, $2 blockerade $3",
"blocklink"             => "blockera",
"unblocklink"           => "ta bort blockering",
"contribslink"          => "bidrag",

// Developer tools 
//
"lockdb"                => "Lås databas",
"unlockdb"              => "Lås upp databas",
"lockdbtext"            => "En låsning av databasen hindrar alla användare från att redigera sidor, ändra inställningar och andra saker som kräver ändringar i databasen.
Bekräfta att du verkligen vill göra detta och att du kommer att låsa upp databasen när underhållet är utfört.",
"unlockdbtext"          => "Genom att låsa upp databasen kommer alla användare att kunna redigera sidor, ändra inställningar etc. igen.
Bekräfta att du vill göra detta.",
"lockconfirm"           => "Ja, jag vill verkligen låsa databasen.",
"unlockconfirm"         => "Ja, jag vill verkligen låsa upp databasen.",
"lockbtn"               => "Lås databasen",
"unlockbtn"             => "Lås upp databasen",
"locknoconfirm"         => "Du har inte bekräftat låsningen.",
"lockdbsuccesssub"      => "Databasen har låsts",
"unlockdbsuccesssub"    => "Databasen har låsts upp",
"lockdbsuccesstext"     => "Wikipediadatabasen är låst.
<br>Kom ihåg att ta bort låsningen när du är färdig med ditt underhåll.",
"unlockdbsuccesstext"   => "Wikipediadatabasen är upplåst.",

// SQL query
//
"asksql"                => "SQL-fråga",
"asksqltext"            => "Använd nedanstående formulär för att ställa frågor direkt till Wikipedias databas.
Använd enkla citationstecken ('så här') för att markera strängar.
Detta belastar ofta servern hårt, så använd denna funktion med omtanke.",
"sqlquery"              => "Skriv fråga",
"querybtn"              => "Skicka fråga",
"selectonly"            => "Andra frågor än \"SELECT\" får endast utföras av Wikipedias utvecklare.",
"querysuccessful"       => "Frågan genomfördes korrekt",

// Move page
//
"movepage"              => "Flytta sida",
"movepagetext"          => "Formuläret nedan byter namn på sidan och flyttar hela dess historia till det nya namnet. Den gamla sidan blir en omdirigeringssida till den nya. Länkar till den gamla sidan kommer inte att ändras. Om det finns en diskussionssida kommer den inte att flyttas. <b>OBS!</b> Detta kan innebära en drastisk ändring på en populär sida; var säker på att du inser konsekvenserna i förväg.",
"movearticle"           => "Flytta sida",
"movenologin"           => "Ej inloggad",
"movenologintext"       => "Du måste vara registrerad användare och ha <a href=\"" .
  wfLocalUrl( "Speciel:Userlogin" ) . "\">loggat in</a>
för att kunna flytta en sida.",
"newtitle"              => "Till ny titel",
"movepagebtn"           => "Flytta sida",
"pagemovedsub"          => "Sidan har flyttats",
"pagemovedtext"         => "Sidan \"[[$1]]\" har flyttats till \"[[$2]]\".",
"articleexists"         => "Det finns redan en sida med detta namn eller så är namnet du angett ogiltigt. Välj ett annat namn.",
"talkexists"            => "Sidan  flyttades korrekt, men den tilhörande diskussionssidan kunde inte flyttas, eftersom det redan existerar en sida med denna nya titel. Du måste sammanfoga dem manuellt.",
"movedto"               => "flyttat till",
"movetalk"              => "Flytta även diskussionssidan, om den finns.",
"talkpagemoved"         => "Sidans diskussionssida flyttades också.",
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
