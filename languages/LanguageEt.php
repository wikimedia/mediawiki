<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesEt = array(
	-1	=> "Eri",
	0	=> "",
	1	=> "Arutelu",
	2	=> "Kasutaja",
	3	=> "Kasutaja_arutelu",
	4	=> "Vikipeedia",
	5	=> "Vikipeedia_arutelu",
	6	=> "Pilt",
	7	=> "Pildi_arutelu",
	8	=> "MediaWiki",
	9	=> "MediaWiki_arutelu",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsEt = array(
	"Ei_ole", "Püsivalt_vasakul", "Püsivalt paremal", "Ujuvalt vasakul"
);

/* private */ $wgSkinNamesEt = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Kölni sinine",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);



/* private */ $wgBookstoreListEt = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesEt = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Minu eelistuste seadmine",
	"Watchlist"		=> "Minu jälgimisloend",
	"Recentchanges" => "Viimati muudetud artiklid",
	"Upload"		=> "Pildifailide üleslaadimine",
	"Imagelist"		=> "Piltide loend",
	"Listusers"		=> "Kasutajad",
	"Statistics"	=> "Saidi statistika",
	"Randompage"	=> "Juhuslik artikkel",

	"Lonelypages"	=> "Üksildased artiklid",
	"Unusedimages"	=> "Kasutamata pildid",
	"Popularpages"	=> "Kõige loetavamad artiklid",
	"Wantedpages"	=> "Kõige oodatumad artiklid",
	"Shortpages"	=> "Lühikesed artiklid",
	"Longpages"		=> "Pikad artiklid",
	"Newpages"		=> "Uued artiklid",
#	"Intl"		=> "Keeltevahelised lingid",
	"Allpages"		=> "Kõik artiklid pealkirja järgi",

	"Ipblocklist"	=> "Blokeeritud IP-aadressid",
	"Maintenance" => "Hoolduslehekülg",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Raamatud",
	"Export"		=> "XML export",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesEt = array(
	"Blockip"		=> "Blokeeri IP-aadress",
	"Asksql"		=> "Otsi andmebaasist",
	"Undelete"		=> "Taasta kustutatud leheküljed"
);

/* private */ $wgDeveloperSpecialPagesEt = array(
	"Lockdb"		=> "Võta andmebaas kirjutuskaitse alla",
	"Unlockdb"		=> "Taasta andmebaasi kirjutuspääs",
);

/* private */ $wgAllMessagesEt = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles

"tog-hover"		=> "Näita tekstimulli siselinkide peale",
"tog-underline" => "Lingid alla kriipsutada",
"tog-highlightbroken" => "Vorminda lingirikked<a href=\"\" class=\"new\">nii</a> (alternatiiv: nii<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Lõikude rööpjoondus",
"tog-hideminor" => "Peida pisiparandused viimastes muudatustes",
"tog-usenewrc" => "Laiendatud viimased muudatused (mitte kõikide brauserite puhul)",
"tog-numberheadings" => "Pealkirjade automaatnummerdus",
"tog-showtoolbar" => "Show edit toolbar",
"tog-rememberpassword" => "Parooli meeldejätmine tulevasteks seanssideks",
"tog-editwidth" => "Redaktoriboksil on täislaius",
"tog-editondblclick" => "Artiklite redigeerimine topeltklõpsu peale (JavaScript)",
"tog-watchdefault" => "Jälgi uusi ja muudetud artikleid",
"tog-minordefault" => "Märgi kõik parandused vaikimisi pisiparandusteks",
"tog-previewontop" => "Näita eelvaadet redaktoriboksi ees, mitte järel",
# Dates

'sunday' => 'pühapäev',
'monday' => 'esmaspäev',
'tuesday' => 'teisipäev',
'wednesday' => 'kolmapäev',
'thursday' => 'neljapäev',
'friday' => 'reede',
'saturday' => 'laupäev',
'january' => 'jaanuar',
'february' => 'veebruar',
'march' => 'märts',
'april' => 'aprill',
'may_long' => 'mai',
'june' => 'juuni',
'july' => 'juuli',
'august' => 'august',
'september' => 'september',
'october' => 'oktoober',
'november' => 'november',
'december' => 'detsember',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mar',
'apr' => 'Apr',
'may' => 'May',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Aug',
'sep' => 'Sep',
'oct' => 'Oct',
'nov' => 'Nov',
'dec' => 'Dec',


# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Esileht",
"mainpagetext"	=> "Wiki tarkvara installeeritud.",
"about"			=> "Tiitelandmed",
"aboutsite"      => "Vikipeedia tiitelandmed",
"aboutpage"		=> "Vikipeedia:Tiitelandmed",
"help"			=> "Spikker",
"helppage"		=> "Vikipeedia:Spikker",
"wikititlesuffix" => "Vikipeedia",
"bugreports"	=> "Teated programmivigadest",
"bugreportspage" => "Vikipeedia:Teated_programmivigadest",
"faq"			=> "KKK",
"faqpage"		=> "Vikipeedia:KKK",
"edithelp"		=> "Redigeerimisspikker",
"edithelppage"	=> "Vikipeedia:Kuidas_artiklit_redigeerida",
"cancel"		=> "Tühista",
"qbfind"		=> "Otsi",
"qbbrowse"		=> "Sirvi",
"qbedit"		=> "Redigeeri",
"qbpageoptions" => "Lehekülje suvandid",
"qbpageinfo"	=> "Lehekülje andmed",
"qbmyoptions"	=> "Minu suvandid",
"mypage"		=> "Minu lehekülg",
"mytalk"		=> "Minu arutelu",
"currentevents" => "Jooksvad sündmused",
"errorpagetitle" => "Viga",
"returnto"		=> "Naase $1 juurde",
"tagline"      	=> "Vabast entsüklopeediast Vikipeedia.",
"whatlinkshere"	=> "Siia viitavad artiklid",
"help"			=> "Spikker",
"search"		=> "Otsi",
"go"		=> "Mine",
"history"		=> "Artikli ajalugu",
"printableversion" => "Prindivariant",
"editthispage"	=> "Redigeeri seda artiklit",
"deletethispage" => "Kustuta see artikkel",
"protectthispage" => "Kaitse seda artiklit",
"unprotectthispage" => "Ära kaitse seda artiklit",
"newpage" => "Uus artikkel",
"talkpage"		=> "Selle artikli arutelu",
"articlepage"	=> "Artiklilehekülg",
"subjectpage"	=> "Teema", # For compatibility
"userpage" => "Kasutajalehekülg",
"wikipediapage" => "Metalehekülg",
"imagepage" => 	"Pildilehekülg",
"viewtalkpage" => "Arutelulehekülg",
"otherlanguages" => "Teised keeled",
"redirectedfrom" => "(Ümber suunatud artiklist $1)",
"lastmodified"	=> "Viimati muudetud $1.",
"viewcount"		=> "Seda lehekülge on külastatud $1 korda.",
"gnunote" => "Kogu tekst on kasutatav litsentsi <a class=internal href='$wgScriptPath/GNU_FDL'>GNU Free Documentation License</a> tingimustel.",
"printsubtitle" => "(Pärineb veebisaidilt http://et.wikipedia.org)",
"protectedpage" => "Kaitstud artikkel",
"administrators" => "Vikipeedia:Administraatorid",
"sysoptitle"	=> "Nõutav süsteemi operaatori staatus",
"sysoptext"		=> "Seda toimingut saavad sooritada ainult süsteemi operaatori staatusega kasutajad. Vaata $1.",
"developertitle" => "Nõutav väljatöötaja staatus",
"developertext"	=> "Seda toimingut saavad sooritada ainult väljatöötaja staatusega kasutajad.
Vaata $1.",
"nbytes"		=> "$1 baiti",
"go"			=> "Mine",
"ok"			=> "OK",
"sitetitle"		=> "Vikipeedia",
"sitesubtitle"	=> "Vaba entsüklopeedia",
"retrievedfrom" => "Välja otsitud andmebaasist \"$1\"",
"newmessages" => "Teil on $1.",
"newmessageslink" => "uut sõnumit",

# Main script and global functions
#
"nosuchaction"	=> "Sellist toimingut pole",
"nosuchactiontext" => "Vikipeedia tarkvara ei tunne sellele aadressile vastavat toimingut",
"nosuchspecialpage" => "Sellist erilehekülge pole",
"nospecialpagetext" => "Vikipeedia tarkvara ei tunne sellist erilehekülge.",

# General errors
#
"error"			=> "Viga",
"databaseerror" => "Andmebaasi viga",
"dberrortext"	=> "Andmebaasipäringus oli süntaksiviga.
Viimane andmebaasipäring oli:
<blockquote><tt>$1</tt></blockquote>
funktsioonist \"<tt>$2</tt>\".
MySQL andis vea \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Andmebaasipäringus oli süntaksiviga.
Viimane andmebaasipäring oli:
\"$1\"
from within function \"$2\".
MySQL andis vea \"$3: $4\".\n",
"noconnect"		=> "Ei saanud ühendada to DB on $1",
"nodb"			=> "Ei saanud valida andmebaasi $1",
"readonly"		=> "Andmebaas kaitse alla",
"enterlockreason" => "Sisesta lukustamise põhjus ning juurdepääsu taastamise ligikaudne aeg",
"readonlytext"	=> "Vikipeedia andmebaas on praegu kirjutuskaitse all, tõenäoliselt andmebaasi rutiinseks hoolduseks, mille lõppedes normaalne olukord taastub.
Administraator, kes selle kaitse alla võttis, andis järgmise selgituse:
<p>$1",
"missingarticle" => "Andmebaas ei leidnud lehekülje \"$1\" teksti, kuigi see oleks pidanud olema leitav. 

<p>Tavaliselt on selle põhjuseks vananenud erunevuste- või ajaloolink leheküljele, mis on kustutatud. 

<p>Kui ei ole tegemist sellise juhtumiga, siis võib olla tegemist tarkvaraveaga. Palun teatage sellest administraatorile, märkides ära aadressi.",
"internalerror" => "Sisemine viga",
"filecopyerror" => "Ei saanud  faili \"$1\" kopeerida mimega \"$2\".",
"filerenameerror" => "Ei saanud faili \"$1\" ümber nimetada failiks \"$2\".",
"filedeleteerror" => "Faili nimega \"$1\" ei ole võimalik kustutada.",
"filenotfound"	=> "Faili nimega \"$1\" ei leitud.",
"unexpected"	=> "Ootamatu väärtus: \"$1\"=\"$2\".",
"formerror"		=> "Viga: vormi ei saanud salvestada",	
"badarticleerror" => "Seda toimingut ei saa sellel leheküljel sooritada.",
"cannotdelete"	=> "Seda lehekülge või pilti ei ole võimalik kustutada. (Võib-olla keegi teine juba kustutas selle.)",
"badtitle"		=> "Vigane pealkiri",
"badtitletext"	=> "Küsitud pealkiri oli vigane, tühi või
valesti viidatud keeltevaheline või wikidevaheline pealkiri.",
"perfdisabled" => "Vabandage! See funktsioon ajutiselt ei tööta, sest ta aeglustab andmebaasi kasutamist võimatuseni. Sellepärast täiustatakse vastavat programmi lähitulevikus. Võib-olla teete seda Teie!",

# Login and logout pages
#
"logouttitle"	=> "Väljalogimine",
"logouttext"	=> "Te olete välja loginud.
Võite kasutada Vikipeediat anonüümselt või uuesti sisse logida sama või teise kasutajana.\n",

"welcomecreation" => "<h2>Tere tulemast, $1!</h2><p>Teie konto on loodud. Ärge unustage seada oma eelistusi.",

"loginpagetitle" => "Sisselogimine",
"yourname"		=> "Teie kasutajanimi",
"yourpassword"	=> "Teie parool",
"yourpasswordagain" => "Sisestage parool uuesti",
"newusersonly"	=> " (ainult uued kasutajad)",
"remembermypassword" => "Parooli meeldejätmine tulevasteks seanssideks.",
"loginproblem"	=> "<b>Sisselogimine ei õnnestunud.</b><br>Proovige uuesti!",
"alreadyloggedin" => "<font color=red><b>Kasutaja $1, Te olete juba sisse loginud!</b></font><br>\n",

"areyounew"		=> "Kui olete Vikipeedias uustulnuk ja tahate saada kasutajakontot, siis 
sisestage kasutajanimi, seejärel parool (kaks korda).
E-posti aadress ei ole kohustuslik; kui Te kaotate oma parooli, võite lasta selle saata Teie poolt antud aadressil.<br>\n",

"login"			=> "Logi sisse",
"userlogin"		=> "Logi sisse",
"logout"		=> "Logi välja",
"userlogout"	=> "Logi välja",
"createaccount"	=> "Loo uus konto",
"badretype"		=> "Sisestatud paroolid ei lange kokku.",
"userexists"	=> "Sisestatud kasutajanimi on juba kasutusel. Valige uus nimi.",
"youremail"		=> "Teie e-posti aadress*",
"yournick"		=> "Teie hüüdnimi (allakirjutamiseks)",
"emailforlost"	=> "* Meiliaadressi sisestamine ei ole kohustuslik. Ent see aitab inimestel Teiega veebisaidi kaudu ühendust võtta, ilma et Te peaksite neile oma meiliaadressi avaldama, ning samuti on sellest kasu, kui unustate parooli.",
"loginerror"	=> "Kasutajanime viga",
"noname"		=> "Kasutajanimi ei ole lubatav.",
"loginsuccesstitle" => "Sisselogimine õnnestus",
"loginsuccess"	=> "Te olete sisse loginud. Teie kasutajanimi on \"$1\".",
"nosuchuser"	=> "Ei ole kasutajat nimega \"$1\".
Kontrollige kirjapilti või kasutage alljärgnevat vormi uue kasutajakonto loomiseks.",
"wrongpassword"	=> "Vale parool. Proovige uuesti.",
"mailmypassword" => "Saada mulle meili teel uus parool",
"passwordremindertitle" => "Parooli meeldetuletamine Vikipeediast",
"passwordremindertext" => "Keegi (tõenäoliselt Teie, IP-aadressilt $1)
küsis meilt Teile uut parooli Vikipeediasse sisselogimiseks.
Kasutaja \"$2\" parool on nüüd \"$3\".
Logige nüüd sisse ja muutke oma parool ära",
"noemail"		=> "Kasutaja \"$1\" meiliaadressi ei ole registreritud.",
"passwordsent"	=> "Uus parool on saadetud kasutaja \"$1\" registreeritud meiliaadressil.
Pärast parooli saamist palun logige sisse.",

# Edit pages
#
"summary"		=> "Resümee",
"minoredit"		=> "See on pisiparandus",
"watchthis"		=> "Jälgi seda artiklit",
"savearticle"	=> "Salvesta",
"preview"		=> "Vaata",
"showpreview"	=> "Näita eelvaadet",
"blockedtitle"	=> "Kasutaja on blokeeritud",
"blockedtext"	=> "Teie kasutajanime või IP-aadressi blokeeris $1.
Tema põhjendus on järgmine:<br>''$2''<p>Küsimuse arutamiseks võite pöörduda $1 või mõne teise
[[Vikipeedia:administraatorid|administraatori]] poole.",
"newarticle"	=> "(Uus)",
"newarticletext" =>
"Seda lehekülge veel ei ole.
Lehekülje loomiseks hakake kirjutama all olevasse boksi 
(lisainfo saamiseks vaadake [[Vikipeedia:Spikker|spikrit]]).
Kui sattusite siia kogemata, klõpsake lihtsalt brauseri ''back''-nupule.",
"anontalkpagetext" => "---- ''See on arutelulehekülg anonüümse kasutaja kohta, kes ei ole loonud kontot või ei kasuta seda. Sellepärast tuleb meil kasutaja identifitseerimiseks kasutada tema [[IP-aadress]]i. See IP-aadress võib olla mitmele kasutajale ühine. Kui olete anonüümne kasutaja ning leiate, et kommentaarid sellel leheküljel ei ole mõeldud Teile, siis palun [[Vikipeedia:Kasutaja sisselogimine|looge konto või logige sisse]], et edaspidi arusaamatusi vältida.'' ",
"noarticletext" => "(See lehekülg on praegu tühi)",
"updated"		=> "(Värskendatud)",
"note"			=> "<strong>Meeldetuletus:</strong> ",
"previewnote"	=> "Ärge unustage, et see versioon ei ole veel salvestatud!",
"previewconflict" => "See eelvaade näitab, kuidas ülemises redaktoriboksis olev tekst näeb välja pärast salvestamist.",
"editing"		=> "Redigeerimisel on $1",
"editconflict"	=> "Redigeerimiskonflikt: $1",
"explainconflict" => "Keegi teine on muutnud seda lehekülge pärast seda, kui Te hakkasite seda redigeerima.
Ülemine redaktoriboks sisaldab teksti viimast versiooni.
Teie muudatused on alumises boksis.
Teil tuleb need viimasesse versiooni üle viia.
Kui Te klõpsate nupule
 \"Salvesta\", siis salvestub <b>ainult</b> ülemises redaktoriboksis olev tekst.\n<p>",
"yourtext"		=> "Teie tekst",
"storedversion" => "Salvestatud redaktsioon",
"editingold"	=> "<strong>ETTEVAATUST! Te redigeerite praegu selle lehekülje vana redaktsiooni.
Kui Te selle salvestate, siis kõik Teie tehtud muudatused lähevad kaduma.</strong>\n",
"yourdiff"		=> "Erinevused",
"copyrightwarning" => "Pidage silmas, et kõik Vikipeediale tehtud kaastööd loetakse avaldatuks vastavalt litsentsile GNU Free Documentation License
(üksikasjad on leheküljel $1).
Kui Te ei soovi, et Teie poolt kirjutatut halastamatult redigeeritakse ja omal äranägemisel kasutatakse, siis ärge seda siia salvestage.<br>
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast. 
<strong>ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!</strong>",
"longpagewarning" => "HOIATUS: Selle lehekülje pikkus ületab $1 kilobaiti. Mõne brauseri puhul valmistab raskusi 32 kilobaidile läheneva pikkusega lehekülgede redigeerimine. Palun kaaluge selle lehekülje sisu jaotamist lühemate lehekülgede vahel.",
"readonlywarning" => "HOIATUS: Andmebaas on lukustatud hooldustöödeks, nii et praegu ei saa parandusi salvestada. Võite teksti alal hoida tekstifailina ning salvestada hiljem.",
"protectedpagewarning" => "HOIATUS:  See lehekülg on lukustatud, nii et seda saavad redigeerida ainult süsteemi operaatori õigustega kasutajad. Järgige juhtnööre leheküljel 
<a href='$wgScriptPath/$wgMetaNamespace:Juhtnöörid_kaitstud_lehekülje_kohta'>
</a>.",

# History pages
#
"revhistory"	=> "Redigeerimislugu",
"nohistory"		=> "Sellel leheküljel ei ole eelmisi redaktsioone.",
"revnotfound"	=> "Redaktsiooni ei leitud",
"revnotfoundtext" => "Teie poolt päritud vana redaktsiooni ei leitud. Palun kontrollige aadressi, millel Te seda lehekülge leida püüdsite.\n",
"loadhist"		=> "Lehekülje ajaloo laadimine",
"currentrev"	=> "Viimane redaktsioon",
"revisionasof"	=> "Redaktsioon $1",
"cur"			=> "viim",
"next"			=> "järg",
"last"			=> "eel",
"orig"			=> "orig",
"histlegend"	=> "Legend: (viim) = erinevused võrreldes viimase redaktsiooniga,
(eel) = erinevused võrreldes eelmise redaktsiooniga, P = pisimuudatus",

# Diffs
#
"difference"	=> "(Erinevused redaktsioonide vahel)",
"loadingrev"	=> "Redaktsiooni laadimine erinevustelehekülje jaoks",
"lineno"		=> "Rida $1:",
"editcurrent"	=> "Redigeeri selle lehekülje viimast redaktsiooni",

# Search results
#
"searchresults" => "Otsingu tulemid",
"searchresulttext" => "Lisainfot Vikipeediast otsimise kohta vaata [[Vikipeedia:Otsing|Otsimine Vikipeediast]].",
"searchquery"	=> "Päring \"$1\"",
"badquery"		=> "Vigane päring",
"badquerytext"	=> "Teie päringut ei saanud menetleda.
Tõenäoliselt püüdsite otsida alla kolme tähelist sõna. Selline otsing ei ole praegu veel võimalik. Võib ka olla, et päringus on trükiviga.
Palun prooviga päring ümber sõnastada.",
"matchtotals"	=> "Otsitud sõna \"$1\" leidub $2 artikli pealkirjas
ning $3 artikli tekstis.",
"nogomatch" => "Täpselt sellise pealkirjaga lehekülge ei ole, proovime täistekstotsingut.",
"titlematches"	=> "Tabamused artiklipealkirjades",
"notitlematches" => "Artiklipealkirjades tabamusi ei ole",
"textmatches"	=> "Tabamused artiklitekstides",
"notextmatches"	=> "Artiklitekstides tabamusi ei ole",
"prevn"			=> "eelmised $1",
"nextn"			=> "järgmised $1",
"viewprevnext"	=> "Näita ($1) ($2) ($3).",
"showingresults" => "Allpool näitame <b>$1</b> tulemit alates tulemist #<b>$2</b>.",
"nonefound"		=> "<strong>Märkus</strong>: otsingute ebaõnnestumine tuleneb sageli sellest, et otsitakse väga sageli esinevaid sõnu, mida otsimisel ei võeta arvesse,  
või kasutatakse mitut otsingusõna (tulemina ilmuvad ainult leheküljed, mis sisaldavad kõiki otsingusõnu).",
"powersearch" => "Otsi",
"powersearchtext" => "
Otsing nimeruumidest :<br>
$1<br>
$2 Loetle ümbersuunamisi &nbsp; Otsi $3 $9",


# Preferences page
#
"preferences"	=> "Teie eelistused",
"prefsnologin" => "Te ei ole sisse loginud",
"prefsnologintext"	=> "Et oma eelistusi seada, <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">tuleb Teil</a>
sisse logida.",
"prefslogintext" => "Te olete sisse loginud kasutajanimega \"$1\".
Teie sisemine ID-number on $2.",
"prefsreset"	=> "Teie eelistused on arvutimälu järgi taastatud.",
"qbsettings"	=> "Kiirriba sätted", 
"changepassword" => "Muuda parool",
"skin"			=> "Nahk",
"math"			=> "Valemite näitamine",
"math_failure"		=> "Arusaamatu süntaks",
"math_unknown_error"	=> "Tundmatu viga",
"math_unknown_function"	=> "Tundmatu funktsioon ",
"math_lexing_error"	=> "Väljalugemisviga",
"math_syntax_error"	=> "Süntaksiviga",
"saveprefs"		=> "Salvesta eelistused",
"resetprefs"	=> "Lähtesta eelistused",
"oldpassword"	=> "Vana parool",
"newpassword"	=> "Uus parool",
"retypenew"		=> "Sisestage uus parool uuesti",
"textboxsize"	=> "Redaktoriboksi suurus",
"rows"			=> "Ridade arv",
"columns"		=> "Veergude arv",
"searchresultshead" => "Otsingutulemite sätted",
"resultsperpage" => "Tulemeid leheküljel",
"contextlines"	=> "Ridu tulemis",
"contextchars"	=> "Konteksti pikkus real",
"stubthreshold" => "Nupu näitamise lävi",
"recentchangescount" => "Pealkirjade arv viimastes muudatustes",
"savedprefs"	=> "Teie eelistused on salvestatud.",
"timezonetext"	=> "Kohaliku aja ja serveri aja (maailmaaja) vahe tundides.",
"localtime"	=> "Kohalik aeg",
"timezoneoffset" => "Ajavahe",
"emailflag"		=> "Blokeeri e-kirjad teistelt kasutajatelt",

# Recent changes
#
"changes" => "muudatused",
"recentchanges" => "Viimased muudatused",
"recentchangestext" => "Jälgige sellel leheküljel Vikipeedia viimaseid muudatusi.
[[Vikipeedia:Tere tulemast|Tere tulemast]]!
Palun vaadake järgmisi lehekülgi: [[Vikipeedia:KKK|Vikipeedia KKK]],
[[Vikipeedia:Ideoloogia ja juhtnöörid|Vikipeedia ideoloogia]]
(eriti [[Vikipeedia:Nomenklatuur|Nomenklatuur]],
[[Vikipeedia:Neutraalne vaatekoht|Neutraalne vaatekoht]]),
and [[Vikipeedia:Levinumad eksisammud Vikipeedias|levinumad eksisammud Vikipeedias]].

Et Vikipeedia projekt õnnestuks, on väga tähtis, et Te ei paigutaks siia kasutuspiirangutega materjali' [[Vikipeedia:Autoriõigused|autoriõigused]].
Palun ärge tehke seda, et vältida Vikipeedia kohtusse kaebamist.
Vaata ka [http://meta.wikipedia.org/wiki/Special:Recentchanges hiljutist ingliskeelset arutelu].",
"rcloaderr"		=> "Viimaste muudatuste laadimine",

"rcnote"		=> "Allpool on viimased <strong>$1</strong> muudatust viimase <strong>$2</strong> päeva jooksul.",
"rcnotefrom"	=> "Allpool on muudatused alates <b>$2</b> (näidatakse kuni <b>$1</b> muudatust).",
"rclistfrom"	=> "Näita muudatusi alates $1",
# "rclinks"		=> "Näita viimast $1 muudatust viimase $2 tunni / viimase $3 päeva jooksul",
"rclinks"		=> "Näita viimast $1 muudatust viimase $2 päeva jooksul.",
"rchide"		=> "in $4 form; $1 pisiparandust; $2 secondary namespaces; $3 multiple edits.",
"diff"			=> "diff",
"hist"			=> "hist",
"hide"			=> "hide",
"show"			=> "show",
"tableform"		=> "table",
"listform"		=> "list",
"nchanges"		=> "$1 changes",
"minoreditletter" => "P",
"newpageletter" => "U",

# Upload
#
"upload"		=> "Faili üleslaadimine",
"uploadbtn"		=> "Upload file",
"uploadlink"	=> "Piltide üleslaadimine",
"reupload"		=> "Re-upload",
"reuploaddesc"	=> "Return to the upload form.",
"uploadnologin" => "sisse logimata",
"uploadnologintext"	=> "Kui Te soovite faile üles laadida, peate <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">sisse logima</a>.",
"uploadfile"	=> "Upload file",
"uploaderror"	=> "Upload error",
"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",
"filename"		=> "Faili nimi",
"filedesc"		=> "Lühikirjeldus",
"affirmation"	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "Vikipeedia:Copyrights",
"copyrightpagename" => "Vikipeedia copyright",
"uploadedfiles"	=> "Uploaded files",
"noaffirmation" => "You must affirm that your upload does not violate
any copyrights.",
"ignorewarning"	=> "Ignore warning and save file anyway.",
"minlength"		=> "Pildi nimi peab sisaldama vähemalt kolme tähte.",
"badfilename"	=> "Pildi nimi on muudetud. Uus nimi on \"$1\".",
"badfiletype"	=> "\".$1\" is not a recommended image file format.",
"largefile"		=> "It is recommended that images not exceed 100k in size.",
"successfulupload" => "Successful upload",
"fileuploaded"	=> "File \"$1\" uploaded successfully.
Please follow this link: ($2) to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
"uploadwarning" => "Upload warning",
"savefile"		=> "Salvesta fail",
"uploadedimage" => "uploaded \"$1\"",

# Image list
#
"imagelist"		=> "Piltide loend",
"imagelisttext"	=> "Below is a list of $1 images sorted $2.",
"getimagelist"	=> "fetching image list",
"ilshowmatch"	=> "Show all images with names matching",
"ilsubmit"		=> "Otsi",
"showlast"		=> "Näita viimast $1 pilti $2 sorteeritud.",
"all"			=> "kõik",
"byname"		=> "nime järgi",
"bydate"		=> "kuupäeva järgi",
"bysize"		=> "suuruse järgi",
"imgdelete"		=> "del",
"imgdesc"		=> "kirj",
"imglegend"		=> "Legend: (kirj) = näita/redigeeri pildi kirjeldust.",
"imghistory"	=> "Pildi ajalugu",
"revertimg"		=> "taas",
"deleteimg"		=> "del",
"deleteimgcompletely"		=> "del",
"imghistlegend" => "Legend: (viim) = see on pildi viimane versioon, (del) = kustuta
see vana versioon, (taas) = taasta see vana versioon.
<br><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Image links",
"linkstoimage"	=> "Sellele pildile viitavad järgmised leheküljed:",
"nolinkstoimage" => "Selle pildile ei viita ükski lehekülg.",

# Statistics
#
"statistics"	=> "Statistika",
"sitestats"		=> "Saidi statistika",
"userstats"		=> "Kasutaja statistika",
"sitestatstext" => "Andmebaas sisaldab kokku <b>$1</b> lehekülge.
This includes \"talk\" pages, pages about Wikipedia, minimal \"stub\"
pages, redirects, and others that probably don't qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.",
"userstatstext" => "Kokku on <b>$1</b> registreeritud kasutajat.
Nende hulgas on <b>$2</b> administraatorit (vt $3).",

# Maintenance Page
#
"maintenance"		=> "Hoolduslehekülg",
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "Tagasi hooldusleheküljele",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"		=> "Iseendale viitavad leheküljed",
"selflinkstext"		=> "Järgmised leheküljed sisaldavad viita iseendale, mis ei ole soovitatav.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"		=> "Üksildased artiklid",
"lonelypages"	=> "Üksildased artiklid",
"unusedimages"	=> "Kasutamata pildid",
"popularpages"	=> "Popular pages",
"nviews"		=> "$1 külastust",
"wantedpages"	=> "Kõige oodatumad artiklid",
"nlinks"		=> "$1 linki",
"allpages"		=> "Kõik artiklid",
"randompage"	=> "Juhuslik artikkel",
"shortpages"	=> "Lühikesed artiklid",
"longpages"		=> "Pikad artiklid",
"listusers"		=> "Kasutajad",
"specialpages"	=> "Erileheküljed",
"spheading"		=> "Erileheküljed",
"sysopspheading" => "Special pages for sysop use",
"developerspheading" => "Special pages for developer use",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Related changes",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "Silu",
"newpages"		=> "Uued leheküljed",
"intl"		=> "Keeltevahelised lingid",
"movethispage"	=> "Move this page",
"unusedimagestext" => "<p>Please note that other web sites
such as the international Wikipedias may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",
"booksources"	=> "Book sources",
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
Wikipedia is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
"alphaindexline" => "$1 to $2",

# Email this user
#
"mailnologin"	=> "No send address",
"mailnologintext" => "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferences</a>
to send e-mail to other users.",
"emailuser"		=> "E-mail this user",
"emailpage"		=> "E-mail user",
"emailpagetext"	=> "If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.",
"noemailtitle"	=> "No e-mail address",
"noemailtext"	=> "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.",
"emailfrom"		=> "From",
"emailto"		=> "To",
"emailsubject"	=> "Subject",
"emailmessage"	=> "Sõnum",
"emailsend"		=> "Saada",
"emailsent"		=> "E-post saadetud",
"emailsenttext" => "Teie sõnum on saadetud.",

# Watchlist
#
"watchlist"		=> "Minu jälgimisloend",
"watchlistsub"	=> "(for user \"$1\")",
"nowatchlist"	=> "Teie jälgimisloend on tühi.",
"watchnologin"	=> "Ei ole sisse loginud",
"watchnologintext"	=> "Jälgimisloendi muutmiseks peate <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">sisse logima</a>.",
"addedwatch"	=> "Lisatud jälgimisloendile",
"addedwatchtext" => "Lehekülg \"$1\" on lisatud Teie <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">jälgimisloendile</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p>

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
"removedwatch"	=> "Removed from watchlist",
"removedwatchtext" => "The page \"$1\" has been removed from your watchlist.",
"watchthispage"	=> "Watch this page",
"unwatchthispage" => "Stop watching",
"notanarticle"	=> "Not an article",

# Delete/protect/revert
#
"deletepage"	=> "Kustuta lehekülg",
"confirm"		=> "Kinnita",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Wikipedia:Policy]].",
"confirmcheck"	=> "Yes, I really want to delete this.",
"actioncomplete" => "Toiming sooritatud",
"deletedtext"	=> "\"$1\" on kustutatud.
See $2 for a record of recent deletions.",
"deletedarticle" => "\"$1\" kustutatud",
"dellogpage"	=> "Deletion_log",
"dellogpagetext" => "Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",
"deletionlog"	=> "deletion log",
"reverted"		=> "Reverted to earlier revision",
"deletecomment"	=> "Reason for deletion",
"imagereverted" => "Revert to earlier version was successful.",
"rollback"		=> "Roll back edits",
"rollbacklink"	=> "rollback",
"cantrollback"	=> "Can't revert edit; last contributor is only author of this article.",
"revertpage"	=> "Reverted to last edit by $1",

# Undelete
"undelete" => "Restore deleted page",
"undeletepage" => "View and restore deleted pages",
"undeletepagetext" => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Restore deleted article",
"undeleterevisions" => "$1 revisions archived",
"undeletehistory" => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.",
"undeleterevision" => "Deleted revision as of $1",
"undeletebtn" => "Taasta!",
"undeletedarticle" => "\"$1\" taastatud",
"undeletedtext"   => "Artikkel [[$1]] on taastatud.
See [[Wikipedia:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
"contributions"	=> "User contributions",
"mycontris" => "My contributions",
"contribsub"	=> "For $1",
"nocontribs"	=> "No changes were found matching these criteria.",
"ucnote"		=> "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"		=> "View the last $1 changes; view the last $2 days.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Viidad siia",
"notargettitle" => "No target",
"notargettext"	=> "You have not specified a target page or user
to perform this function on.",
"linklistsub"	=> "(List of links)",
"linkshere"		=> "Siia viitavad järgmised leheküljed:",
"nolinkshere"	=> "Siia ei viita ükski lehekülg.",
"isredirect"	=> "redirect page",

# Block/unblock IP
#
"blockip"		=> "Blokeeri IP-aadress",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address.
This should be done only only to prevent vandalism, and in
accordance with [[Wikipedia:Policy|Wikipedia policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP-aadress",
"ipbreason"		=> "Põhjus",
"ipbsubmit"		=> "Blokeeri see aadress",
"badipaddress"	=> "The IP address is badly formed.",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "IP-aadress \"$1\" on blokeeritud.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock IP address",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "IP address \"$1\" unblocked",
"ipblocklist"	=> "Blokeeritud IP-aadresside loend",
"blocklistline"	=> "$1, $2 blocked $3",
"blocklink"		=> "blokeeri",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",

# Developer tools
#
"lockdb"		=> "Lock database",
"unlockdb"		=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"		=> "Võta andmebaas kirjutuskaitse alla",
"unlockbtn"		=> "Taasta andmebaasi kirjutuspääs",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Andmebaas kirjutuskaitse all",
"unlockdbsuccesssub" => "Kirjutuspääs taastatud",
"lockdbsuccesstext" => "Vikipeedia andmebaas on nüüd kirjutuskaitse all.
<br>Kui Teie hooldustöö on läbi, ärge unustage taastada kirjutuspääs!",
"unlockdbsuccesstext" => "Vikipeedia andmebaasi kirjutuspääs on taastatud.",

# SQL query
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
Wikipedia database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Queries other than \"SELECT\" are restricted to
Wikipedia developers.",
"querysuccessful" => "Query successful",

# Move page
#
"movepage"		=> "Teisalda artikkel",
"movepagetext"	=> "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
[[Special:Maintenance|check]] for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will '''not''' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>ETTEVAATUST!</b>
Siin võib olla tegemist ootamatu olulise muudatusega väga loetavas artiklis;
enne muudatuse tegemist mõelge palun järele selle võimalike tagajärgede üle.",
"movepagetalktext" => "Koos artiklileheküljega teisaldatakse automaatselt ka arutelulehekülg, '''välja arvatud juhul, kui:'''
*You are moving the page across namespaces,
*uue nime all on juba olemas mittetühi arutelulehekülg või
*You uncheck the box below.

Nimetatud juhtudel teisaldage arutelulehekülg soovi korral ise või ühendage see ise uue nime all olemasoleva aruteluleheküljega.",
"movearticle"	=> "Teisalda artiklilehekülg",
"movenologin"	=> "Te ei ole sisse loginud",
"movenologintext" => "Te peate registreeruma kasutajaks ja <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">sisse logima</a>
to move a page.",
"newtitle"		=> "Uue pealkirja alla",
"movepagebtn"	=> "Teisalda artikkel",
"pagemovedsub"	=> "Artikkel on teisaldatud",
"pagemovedtext" => "Artikkel \"[[$1]]\" on teisaldatud pealkirja \"[[$2]]\" alla.",
"articleexists" => "Selle nimega artikkel on juba olemas või valitud nimi ei ole lubatav. Palun valige uus nimi.",
"talkexists"	=> "Artikkel on teisaldatud, kuid arutelulehekülge ei saanud teisaldada, sest uue nime all on arutelulehekülg juba olemas. Palun ühendage aruteluleheküljed ise.",
"movedto"		=> "Teisaldatud pealkirja alla:",
"movetalk"		=> "Teisalda ka \"arutelu\", kui saab.",
"talkpagemoved" => "Ka vastav arutelulehekülg on teisaldatud.",
"talkpagenotmoved" => "Vastav arutelulehekülg jäi teisaldamata.",
# Math
	'mw_math_png' => "Alati PNG",
	'mw_math_simple' => "Kui väga lihtne, siis HTML, muidu PNG",
	'mw_math_html' => "Võimaluse korral HTML, muidu PNG",
	'mw_math_source' => "Säilitada TeX (tekstibrauserite puhul)",
	'mw_math_modern' => "Soovitatav moodsate brauserite puhul",
	'mw_math_mathml' => 'MathML',

);

require_once( "LanguageUtf8.php" );

class LanguageEt extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListEt ;
		return $wgBookstoreListEt ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEt;
		return $wgNamespaceNamesEt;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesEt;
		return $wgNamespaceNamesEt[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesEt;

		foreach ( $wgNamespaceNamesEt as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEt;
		return $wgQuickbarSettingsEt;
	}

	function getSkinNames() {
		global $wgSkinNamesEt;
		return $wgSkinNamesEt;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesEt;
		return $wgValidSpecialPagesEt;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesEt;
		return $wgSysopSpecialPagesEt;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesEt;
		return $wgDeveloperSpecialPagesEt;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesEt;
		if( isset( $wgAllMessagesEt[$key] ) ) {
			return $wgAllMessagesEt[$key];
		} else {
			return Language::getMessage( $key );
		}
	}
}

?>
