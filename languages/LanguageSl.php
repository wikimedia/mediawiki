<?php

#
# Revision/
# Inačica 1.00.00 XJamRastafire 2003-07-08 |NOT COMPLETE
#         1.00.10 XJamRastafire 2003-11-03 |NOT COMPLETE
# ______________________________________________________
#         1.00.20 XJamRastafire 2003-11-05 |    COMPLETE
#         1.00.30 romanm        2003-11-07 |    minor changes
#         1.00.31 romanm        2003-11-11 |    merged incorrectly broken lines
#         1.00.32 romanm        2003-11-19 |    merged incorrectly broken lines
#         1.00.40 romanm        2003-11-21 |    fixed Google search
#

require_once( "LanguageUtf8.php" );

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesSl = array(
	-2  => "Media",
	-1	=> "Posebno",
	0	=> "",
	1	=> "Pogovor",
	2	=> "Uporabnik",
	3	=> "Uporabniški_pogovor",
	4	=> "Wikipedija",
	5	=> "Pogovor_k_Wikipediji",
	6	=> "Slika",
	7	=> "Pogovor_k_sliki",
	8	=> "MediaWiki",
	9	=> "MediaWiki_talk",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSl = array(
	"Brez", "Levo nepomično", "Desno nepomično", "Levo leteče"
);

/* private */ $wgSkinNamesSl = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Cologne Blue",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);



/* private */ $wgBookstoreListSl = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);




# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesSl = array(
	"Userlogin"	=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Ponastavi moje uporabniške nastavitve",
	"Watchlist"	=> "Moj opazovalni seznam",
	"Recentchanges" => "Nedavno posodobljene strani",
	"Upload"	=> "Naloži slikovne datoteke",
	"Imagelist"	=> "Seznam slik",
	"Listusers"	=> "Vpisani uporabniki",
	"Statistics"	=> "Statistika strani",
	"Randompage"	=> "Naključni članek",
	"Lonelypages"	=> "Osamljeni članki",
	"Unusedimages"	=> "Osamljene slike",
	"Popularpages"	=> "Priljubljeni članki",
	"Wantedpages"	=> "Najbolj iskani članki",
	"Shortpages"	=> "Kratki članki",
	"Longpages"	=> "Dolgi članki",
	"Newpages"	=> "Nanovo ustvarjeni članki",
	"Ancientpages"	=> "Oldest pages",
	"Allpages"	=> "Vse strani po naslovu",
	"Ipblocklist"	=> "Zaprti IP naslovi",
	"Maintenance"   => "Vzdrževalna stran",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"	=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"	=> "",
	"Booksources"	=> "Zunanji knjižni viri",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesSl = array(
	"Blockip"		=> "Zapri IP naslov",
	"Asksql"		=> "Preglej podatkovno bazo",
	"Undelete"		=> "Poglej in obnovi zbrisane strani"
);

/* private */ $wgDeveloperSpecialPagesSl = array(
	"Lockdb"		=> "Postavi podatkovno bazo samo za branje",
	"Unlockdb"		=> "Obnovi zapisovalni dostop podatkovne baze",
);

/* private */ $wgAllMessagesSl = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User Toggles

"tog-hover"		   => "Prikaži lebdečo škatlo nad wikijevimi povezavami",
"tog-underline"        => "Podčrtane povezave",
"tog-highlightbroken"  => "Oblikuj prekinjene povezave <a href=\"\" class=\"new\">kot</a> (druga možnost: kot<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	   => "Poravnaj odstavke",
"tog-hideminor"        => "Skrij manjše popravke v trenutnih spremembah",
"tog-usenewrc"         => "Izbolšane trenutne spremembe (ni za vse brskljalnike)",
"tog-numberheadings"   => "Samodejno oštevilči poglavja",
"tog-showtoolbar" => "Show edit toolbar",
"tog-rememberpassword" => "Zapomni si geslo v vseh urejevanjih",
"tog-editwidth"        => "Urejevalna škatla ima celo širino",
"tog-editondblclick"   => "Urejuj strani z dvojnih klikom (JavaScript)",
"tog-watchdefault"     => "Opazuj nove in spremenjene članke",
"tog-minordefault"     => "Po privzetem označi vsa urejanja kot manjša",
"tog-previewontop"     => "Prikaži predogled pred urejevalno škatlo in ne za njo",

# Dates
'sunday' => 'nedelja',
'monday' => 'ponedeljek',
'tuesday' => 'torek',
'wednesday' => 'sreda',
'thursday' => 'četrtek',
'friday' => 'petek',
'saturday' => 'sobota',
'january' => 'januar',
'february' => 'februar',
'march' => 'marec',
'april' => 'april',
'may_long' => 'maj',
'june' => 'junij',
'july' => 'julij',
'august' => 'avgust',
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
'aug' => 'avg',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Glavna stran",
"about"			=> "O",
"aboutsite"             => "O Wikipediji",
"aboutpage"		=> "Wikipedija:O_Wikipediji",
"help"			=> "Pomoč",
"helppage"		=> "Wikipedija:Pomoč",
"wikititlesuffix"       => "Wikipedija",
"bugreports"	        => "Poročila o hroščih",
"bugreportspage"        => "Wikipedija:Poročila_o_hroščih",
"faq"			=> "Najpogostejša vprašanja",
"faqpage"		=> "Wikipedija:Najpogostejša_vprašanja",
"edithelp"		=> "Pomoč pri urejanju",
"edithelppage"	        => "Wikipedija:Urejevanje_slovenskih_strani",
"cancel"		=> "Prekini",
"qbfind"		=> "Najdi",
"qbbrowse"		=> "Pobrskaj",
"qbedit"		=> "Uredi",
"qbpageoptions"         => "Možnosti strani",
"qbpageinfo"	        => "Podatki strani",
"qbmyoptions"	        => "Moje možnosti",
"mypage"		=> "Moja stran",
"mytalk"		=> "Moj pogovor",
"currentevents"         => "Trenutni dogodki",
"errorpagetitle"        => "Napaka",
"returnto"		=> "Vrni se k $1.",
"tagline"      	        => "Iz Wikipedije, proste enciklopedije.",
"whatlinkshere"	        => "Strani, ki so vezane sem",
"help"			=> "Pomoč",
"search"		=> "Išči",
"go"		        => "Pojdi",
"history"		=> "Stare inačice",
"printableversion"      => "Inačica za tiskanje",
"editthispage"	        => "Uredi to stran",
"deletethispage"        => "Zbriši to stran",
"protectthispage"       => "Zavaruj to stran",
"unprotectthispage"     => "Ta stran naj bo nezavarovana",
"newpage"               => "Nova stran",
"talkpage"		=> "Pogovori se o tej strani",
"articlepage"	        => "Poglej članek",
"subjectpage"	        => "Poglej temo", # For compatibility
"userpage"              => "Poglej uporabnikovo stran",
"wikipediapage"         => "Poglej meta stran",
"imagepage"             => "Poglej stran slike",
"viewtalkpage"          => "Poglej pogovor",
"otherlanguages"        => "Drugi jeziki",
"redirectedfrom"        => "(Preusmerjeno iz $1)",
"lastmodified"	        => "Zadnja sprememba $1.",
"viewcount"		=> "To stran so pogledali $1 krat.",
"gnunote" => "Vse besedilo je na razpolago pod pogoji <a class=internal href='/wiki/GNU_FDL'>GNU licence proste dokumentacije</a>.",
"printsubtitle"         => "(Iz http://sl.wikipedia.org)",
"protectedpage"         => "Zaščitena stran",
"administrators"        => "Wikipedija:Administratorji",
"sysoptitle"	        => "Zahtevan dostop sistemskega operaterja",
"sysoptext"		=> "To dejanje, ki ste ga zahtevali, lahko izvedejo le uporabniki s statusom \"sysop\". Glej še $1.",
"developertitle" => "Zahtevan dostop razvijalca",
"developertext"	=> "To dejanje, ki ste ga zahtevali, lahko izvedejo le uporabniki s statusom \"razvijalec\". Glej še $1.",
"nbytes"		=> "$1 bitov",
"go"			=> "Pojdi",
"ok"			=> "V redu",
"sitetitle"		=> "Wikipedija",
"sitesubtitle"	        => "Prosta enciklopedija",
"retrievedfrom"         => "Vzpostavljeno iz \"$1\"",
"newmessages"           => "Imaš $1.",
"newmessageslink"       => "novih sporočil",
"editsection"=>"spremeni",
"toc" => "Vsebina",
"showtoc" => "prikaži",
"hidetoc" => "skrij",
"thisisdeleted" => "Poglej ali vrni $1?",
"restorelink" => "$1 pobrisanih popravkov",

# Main script and global functions
#
"nosuchaction"	        => "Ni takšnega dejanja",
"nosuchactiontext"      => "Dejanje, ki ga je označil URL programje Wikipedije ne prepozna",
"nosuchspecialpage"     => "Ne obstaja takšna posebna stran",
"nospecialpagetext"     => "Zahtevali ste posebno stran, ki jo programje Wikipedije ne prepozna.",

# General errors
#
"error"			=> "Napaka",
"databaseerror"         => "Napaka podatkovne baze",
"dberrortext"	        => "Nastopila je skladenjska povpraševanja podatkovne baze.
Zadnje poskušano povpraševanje podatkovne baze je bilo:
<blockquote><tt>$1</tt></blockquote>
iz telesa funkcije \"<tt>$2</tt>\".
MySQL je vrnil napako \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Ne morem se povezati s PB na $1",
"nodb"			=> "ne morem izbrati podatkovne baze $1",
"readonly"		=> "Podatkovna baza je zaklenjena",
"enterlockreason" => "Vnesi razlog za zastoj, skupaj z oceno, kdaj bo zastoj odpravljen",
"readonlytext"	=> "Podatkovna baza Wikipedije je trenutno zaklenjena za nove vnose in nove spremembe, verjetno zaradi njenega tekočega vzdrževanja. Kmalu po tem bo spet v običajnem stanju.
Administrator, ki jo je zaklenil je ponudil naslednjo razlago:
<p>$1",
"missingarticle" => "Podatkovna baza ni našla besedila strani \"$1\", ki bi ga morala. To ni napaka podatkovne baze, ampak najverjetneje hrošč v programju. Prosim sporočite to administratorju in navedite zapisek URL-ja.",
"internalerror"         => "Notranja napaka",
"filecopyerror"         => "Ne morem prepisati datoteke \"$1\" v \"$2\".",
"filerenameerror"       => "Ne morem preimenovati datoteke \"$1\" v \"$2\".",
"filedeleteerror"       => "Ne morem zbrisati datoteke \"$1\".",
"filenotfound"	        => "Ne najdem datoteke \"$1\".",
"unexpected"	        => "Nepričakovana vrednost: \"$1\"=\"$2\".",
"formerror"		=> "Napaka: ne morem predložiti oblike",
"badarticleerror"       => "Tega dejanja ne morem izvesti na tej strani.",
"cannotdelete"	        => "Ne morem zbrisati navedene strani ali slike. (Lahko da jo je zbrisal že nekdo drug.)",
"badtitle"		=> "Slab naslov",
"badtitletext"	        => "Naveden naslov strani je neveljaven, prazen ali nepravilno povezan naslov med drugimi jeziki ali med drugimi wikijevimi projekti.",
"perfdisabled"          => "To je posneta kopija $1:",

# Login and logout pages
#
"logouttitle"	        => "Izpis uporabnika",
"logouttext"	        => "Sedaj niste vpisani.
Lahko nadaljujete z uporabo Wikipedije nepodpisani, ali pa se lahko ponovno vpišete kot isti ali drug uporabnik.\n",

"welcomecreation"       => "<h2>Pozdravljeni, $1!</h2><p>Ustvarili smo vaš račun. Ne pozabite si ponastaviti vaših nastavitev Wikipedije.",

"loginpagetitle"        => "Vpis uporabnika",
"yourname"		=> "Vaše uporabniško ime",
"yourpassword"	        => "Vaše geslo",
"yourpasswordagain"     => "Ponovno vpišite geslo",
"newusersonly"	        => " (Samo novi uporabniki)",
"remembermypassword"    => "Zapomni si moje geslo vseskozi.",
"loginproblem"	        => "<b>Nastala je tažava z vašim vpisom.</b><br>Poskusite znova!",
"alreadyloggedin"       => "<font color=red><b>Uporabnik $1, ste že vpisani!</b></font><br>\n",

"login"			=> "Vpis",
"userlogin"		=> "Vpis",
"logout"		=> "Izpis",
"userlogout"	        => "Izpis",
"createaccount"	        => "Izdelajte nov račun",
"badretype"		=> "Gesli, ki ste ju vnesli, se ne ujemata.",
"userexists"	        => "Uporabniško ime, ki ste ga vnesli, je že v uporabi. Prosimo izberite drugačno ime.",
"youremail"		=> "Vaša elektronska pošta",
"yournick"		=> "Vaš vzdevek (za podpise)",
"emailforlost"	        => "Če ste pozabili geslo, imate lahko novo, poslano na naslov vaše elektronske pošte.",
"loginerror"	        => "Napaka vpisa",
"noname"		=> "Niste navedli veljavnega uporabniškega imena.",
"loginsuccesstitle"     => "Vpis uspešen",
"loginsuccess"	        => "Sedaj ste vpisani v Wikipediji kot \"$1\".",
"nosuchuser"	        => "Uporabnik z imenom \"$1\" ne obstaja. Preverite vaše črkovanje, ali uporabite spodnji obrazec za izdelavo novega uporabniškega računa.",
"wrongpassword"	        => "Geslo, ki ste ga vnesli je nepravilno. Prosimo poskusite znova.",
"mailmypassword"        => "Pošljite mi novo geslo",
"passwordremindertitle" => "Opomnik gesla iz Wikipedije",
"passwordremindertext"  => "Nekdo (verjetno vi, z IP naslova $1)
je zahteval, da vam pošljemo novo vpisno geslo Wikipedije.
Geslo uporabnika \"$2\" je sedaj \"$3\".
Sedaj se lahko vpišete in spremenite vaše geslo.",
"noemail"		=> "Elektronska pošta za uporabnika \"$1\" ni zapisana.",
"passwordsent"	        => "Novo geslo smo poslali na naslov elektronske pošte, vpisano za \"$1\". Prosimo vpišite se ponovno, ko ga boste prejeli.",

# Edit pages
#
"summary"		=> "Povzetek",
"minoredit"		=> "To je manjše urejevanje",
"watchthis"		=> "Opazuj ta članek",
"savearticle"	        => "Shrani stran",
"preview"		=> "Predpregled",
"showpreview"	        => "Pokaži predpregled",
"blockedtitle"	        => "Uporabnik je zaprt",
"blockedtext"	        => "Vaše uporabniško ime ali IP naslov je zaprl $1.
Vzrok za to je naslednji:<br>''$2''<p>Lahko pokličete $1 ali katerega drugega
[[Wikipedija:Administratorji|administratorja]] za pogovor o zaprtju.",
"newarticle"	        => "(Nov)",
"newarticletext" =>
"Sledili ste povezavi, ki še ne obstaja.
Za izdelavo strani, začnite vnašati besedilo v spodnjo škatlo
(poglejte [[Wikipedija:Pomoč|stran o pomoči]] za več sporočil).
Če ste tukaj po pomoti, samo kliknite gumb za '''nazaj''' vašega brskljalnika.",
"anontalkpagetext"      => "---- ''To je pogovorna stran za nepodpisanega uporabnika, ki še ni ustvaril računa ali, ki ga ne uporablja. Zaradi tega moramo uporabiti števčen [[IP address]] za njegovo/njeno ugotavljanje istovetnosti. Takšen IP naslov si lahko deli več uporabnikov. Če ste nepodpisan uporabnik in če menite, da so nepomembne pripombe namenjene vam, prosimo [[Special:Userlogin|ustvarite račun ali pa se vpišite]], da preprečite naslednje zmede z drugimi nepodpisanimi uporabniki.'' ",
"noarticletext" => "(Trenutno ni besedila na tej strani)",
"updated"		=> "(Posodobljeno)",
"note"			=> "<strong>Opomba:</strong> ",
"previewnote"	        => "Zapomnite si, da je to le predpregled in stran še ni bila shranjena!",
"previewconflict"       => "Ta predpregled kaže besedilo v zgornjem predelu urejevanja besedila kakor se bo pojavilo, če se ga odločite shraniti.",
"editing"		=> "Urejevanje $1",
"editconflict"	        => "Navzkrižje urejevanja: $1",
"explainconflict"       => "Nekdo je spremenil to stran od takrat, ko ste jo vi začeli urejevati.
Zgodnji predel besedila vsebuje besedilo strani, ki trenutno obstaja.
Vaše spremembe so prikazane v spodnjem predelu besedila.
Morali boste spojiti vaše spremembe v obstoječe besedilo.
<b>Samo</b> besedilo z zgornjem predelu besedila bo shranjeno, ko boste pritisnili \"Shrani stran\".\n<p>",
"yourtext"		=> "Vaše besedilo",
"storedversion"         => "Shranjena inačica",
"editingold"	        => "<strong>OPOZORILO: Urejujete zastarelo inačico te strani.
Če jo boste shranili, bodo vse spremembe, narejene od tedaj, izgubljene.</strong>\n",
"yourdiff"		=> "Razlike",
"copyrightwarning"      => "Prosimo upoštevajte, da se vsi doprinosi k Wikipediji smatrajo kot objave pod GNU licenco proste dokumentacije (glej $1 za podrobnosti).
Če ne želite, da se vaša pisanja neusmiljeno urejujejo ali ponovno razdeljujejo prostovoljno, potem jih ne predlagajte tukaj.<br>
Poleg tega nam obljubljate, da ste to napisali samo ali pa prepisali iz javno dostopnega ali podobnega prostega vira.
<strong>NE PREDLAGAJTE AVTORSKO ZAŠČITENEGA DELA BREZ DOVOLJENJA!</strong>",
"longpagewarning" => "OPOZORILO: Ta stran je dolga $1 kilobitov; nekateri brskalniki s težavo urejujejo strani, ki so daljše kot 32 kB. Prosimo, da upoštevate delitev strani na manjše dele.",
"readonlywarning" => "OPOZORILO: Baza je trenutno zaprta zaradi vzdrževanja
in trenutno ne morete shranjevati sprememb. Skopirajte besedilo v urejevalnik in ga posnemite kasneje.",
"protectedpagewarning" => "OPOZORILO: Ta stran je zaključena in jo lahko spreminjajo samo uporabniki, ki imajo vzdrževalne pravice. Prosimo poglejte <a href='/wiki/Wikipedija:Smernice_zaščitenih_strani'>smernice zaščitenih strani</a>.",

# History pages
#
"revhistory"	        => "Zgodovina različic",
"nohistory"		=> "Ni urejevalne zgodovine za to stran.",
"revnotfound"	        => "Ne najdem različice",
"revnotfoundtext"       => "Ne morem najti stare različice strani, po kateri ste povpraševali.
Prosimo preverite URL, ki ste ga uporabili za dostop do te strani.\n",
"loadhist"		=> "Nalagam zgodovino strani",
"currentrev"	        => "Trenutna različica",
"revisionasof"	        => "Različica od $1",
"cur"			=> "tren",
"next"			=> "nasl",
"last"			=> "zadn",
"orig"			=> "izvi",
"histlegend"	        => "Napotek: (tren) = razlika od trenutne različice,
(zadn) = razlika od prejšnje različice, M = manjše urejevanje",

# Diffs
#
"difference"	        => "(Razlika med različicami)",
"loadingrev"	        => "nalagam različico za razliko",
"lineno"		=> "Vrstica $1:",
"editcurrent"	        => "Uredi trenutno različico te strani",

# Search results
#
"searchresults"         => "Izidi iskanja",
"searchresulttext"      => "Za več sporočil o iskanju v Wikipediji glej [[Wikipedija:Iskanje|Iščem v Wikipediji]].",
"searchquery"	        => "Za povpraševanje \"$1\"",
"badquery"		=> "Slabo oblikovano iskalno povpraševanje",
"badquerytext"	        => "Ne moremo obdelati vašega povpraševanja.
To je verjetno zaradi tega, ker ste hoteli iskati besedo, dolgo manj kot tri črke, kar še ni podprto.
Lahko ste tudi narobe vnesli izraz, na primer \"Ribi in in Tehntnica\".
Prosimo poskusite z drugim povpraševanjem.",
"matchtotals"	        => "Povpraševanje \"$1\" se ujema z $2 naslovi člankov in z besedilom $3 člankov.",
"nogomatch"             => "Ne obstaja stran z natančno tem naslovom, poskušam z iskanjem celotnega besedila. ",
"titlematches"	        => "Ujemanje z naslovom članka",
"notitlematches"        => "Noben naslov članka se ne ujema",
"textmatches"	        => "Ujemanje z besedilom članka",
"notextmatches"	        => "Nobeno besedilo članka se ne ujema",
"prevn"			=> "prejšnji $1",
"nextn"			=> "naslednji $1",
"viewprevnext"	        => "Pogled ($1) ($2) ($3).",
"showingresults"        => "Spodaj prikažem <b>$1</b> izidov, začenši z #<b>$2</b>.",
"nonefound"		=> "<strong>Opomba</strong>: neuspešna iskanja velikokrat povzročijo iskanja vsakdanjih besed kot sta \"imeti\" in \"iz\", katera niso vnešena v seznam, ali navajanja več iskalnih izrazov (v izidu se bodo pojavile samo strani, ki vsebujejo iskalne izraze).",
"powersearch"           => "Iskanje",
"powersearchtext" => "
Iskanje v imenskem prostoru :<br>
$1<br>
$2 Seznam se preusmerja   Iskanje za $3 $9",
"searchdisabled" => "<p>Oprostite! Iskanje po celotni bazi je zaradi hitrejšega delovanja Wikipedije trenutno onomogočena. Lahko pa se poslužite z Googlovim iskalnikom.</p>

",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/intl/sl/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=hidden name=hl value=sl>
<INPUT TYPE=hidden name=ie value=UTF-8>
<INPUT TYPE=hidden name=oe value=UTF-8>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Iskanje z Googlom\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer}\" checked> {$wgServer} <br>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(Osnovno)",

# Preferences page
#
"preferences"	        => "Nastavitve",
"prefsnologin"          => "Niste vpisani",
"prefsnologintext"	=> "Morate biti <a href=\"" .
  "{{localurle:Special:Userlogin}}\">vpisani</a>
za ponastavljanje uporabniških nastavitev.",
"prefslogintext"        => "Vpisani ste kot \"$1\".
Notranja ID števka je $2.",
"prefsreset"	        => "Nastavitve so bile ponastavljene iz shrambe.",
"qbsettings"	        => "Postavitve hitre vrstice",
"changepassword"        => "Sprememba gesla",
"skin"			=> "Koža",
"math"			=> "Prikazujem matematično besedilo",
"math_failure"		=> "Nisem uspel razčleniti",
"math_unknown_error"	=> "neznana napaka",
"math_unknown_function"	=> "neznana funkcija ",
"math_lexing_error"	=> "slovarska napaka",
"math_syntax_error"	=> "skladenjska napaka",
"saveprefs"		=> "Shrani nastavitve",
"resetprefs"	        => "Ponastavi nastavitve",
"oldpassword"	        => "Staro geslo",
"newpassword"	        => "Novo geslo",
"retypenew"		=> "Ponovno vnesite geslo",
"textboxsize"	        => "Razsežnosti urejevalne škatle",
"rows"			=> "Vrstice",
"columns"		=> "Stolpci",
"searchresultshead"     => "Postavitve izida iskanja",
"resultsperpage"        => "Zadetkov za prikaz na stran",
"contextlines"	        => "Vrstic za prikaz na zadetek",
"contextchars"	        => "Znakov vsebine na vrstico",
"stubthreshold"         => "Prag za škrbinski prikaz",
"recentchangescount"    => "Število naslovov v trenutnih spremembah",
"savedprefs"	        => "Vaše nastavitve so bile shranjene.",
"timezonetext"	        => "Vnesite za koliko ur se vaš krajevni čas razlikuje od strežnikovega časa (UTC).",
"localtime"	        => "Krajevni čas",
"timezoneoffset"        => "Izravnava",
"emailflag"		=> "Ne prikažem elektronske pošte drugim uporabnikom",
"defaultns"  => "Ponavadi išči na naslednjih imenskih področjih:",

# Recent changes
#
"changes"               => "spremembe",
"recentchanges"         => "Trenutne spremembe",
"recentchangestext"     => "Sledi najpoznejšim spremembam v Wikipediji na tej strani.
[[Wikipedija:Dobrodošli,_novinci|Dobrodošli, novinci]]!
Prosimo poglejte na naslednje strani: [[Wikipedija:Najpogostejša vprašanja|Najpogostejša vprašanja]],
[[Wikipedija:Primernosti in smernice|Smernice]]
(še posebej [[Wikipedija:Dogovori o poimenovanjih|Dogovori o poimenovanjih]],
[[Wikipedija:Nepristransko stališče videnja|Nepristransko stališče videnja (NSV)]]),
in [[Wikipedija:Najpogostejše stranpoti Wikipedije|Najpogostejše stranpoti Wikipedije]].
Če bi radi, da Wikipedija uspe, je zelo pomembno, da ne dodajate
snovi, ki je pridržana z drugimi [[wikipedia:Avtorske pravice|avtorskimi pravicami]].
Zakonita obveza lahko v resnici škodi opravilu, zatorej ne počnite tega.
Poglejte tudi [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].",
"rcloaderr"		=> "Nalagam trenutne spremembe",
"rcnote"		=> "Spodaj so zadnje <strong>$1</strong> spremembe v zadnjih <strong>$2</strong> dnevih.",
"rcnotefrom"	=> "Spodaj so spremembe od <b>$2</b> (prikazane do <b>$1</b>).",
"rclistfrom"	=> "Prikaži nove spremembe od $1",
# "rclinks"		=> "Prikaži zadnjih $1 sprememb v zadnjih $2 urah / zadnjih $3 dnevih",
"rclinks"		=> "Prikaži zadnjih $1 sprememb v zadnjih $2 dnevih.",
"rchide"		=> "v $4 obliki; $1 manjša urejanja; $2 druga poimenovanja; $3 večkratna urejevanja.",
"diff"			=> "razl",
"hist"			=> "zgod",
"hide"			=> "skrij",
"show"			=> "prikaži",
"tableform"		=> "tabela",
"listform"		=> "seznam",
"nchanges"		=> "$1 sprememb",
"minoreditletter"       => "M",
"newpageletter"         => "N",

# Upload
#
"upload"		=> "Naložite datoteko",
"uploadbtn"		=> "Naložite datoteko",
"uploadlink"	        => "Naložite slike",
"reupload"		=> "Ponovno naložite",
"reuploaddesc"	        => "Vrnite se v obrazec za nalaganje.",
"uploadnologin"         => "Niste vpisani",
"uploadnologintext"	=> "Za nalaganje datotek morate biti <a href=\"" .
  "{{localurle:Special:Userlogin}}\">vpisani</a>
.",
"uploadfile"	        => "Naložite datoteko",
"uploaderror"	        => "Naložite napako",
"uploadtext"	=> "'''USTAVITE SE!''' Preden tukaj naložite,
se prepričajte, da ste prebrali in sledili Wikipedijini
[[Project:Primernost_uporabe_slik|primernosti uporabe slik]].

Da pregledate ali poiščete prejšnje naložene slike,
pojdite na [[Special:Imagelist|seznam naloženih slik]].
Nalaganje in brisanje je vpisano v
[[Project:Dnevnik_nalaganja|dnevniku nalaganja]].

Uporabite spodnji obrazec za nalaganje novih slik za
ponazarjanje vaših člankov.
Na večini brskljalnikov boste videli \"Preišči...\" gumb, ki vas
bo spravil na standardno pogovorno okno odprtja datoteke vašega operacijskega sistema.
Z izbiro datoteke se bo vpisalo ime v besedilno
polje poleg gumba.
Morate tudi potrditi škatlico s čimer izjavljate,
da z nalaganjem datoteke ne kršite nobenih avtorskih pravic.
Pritisnite gumb \"Naloži\" za dokončanje nalaganja.
To bo malo malce potrajalo, če imate slabšo mrežno povezavo.

Prednostni formati so JPEG za fotografske slike, PNG
za risbe in druge ikonske slike in OGG za zvoke.
Imenujete vaše datoteke opisno, da se izognete zmešnjavi.
Da v članek vključite sliko, uporabite povezavo oblike
'''<nowiki>[[image:datoteka.jpg]]</nowiki>''' ali
'''<nowiki>[[image:datoteka.png|alt besedilo]]</nowiki>''' ali
'''<nowiki>[[media:datoteka.ogg]]</nowiki>''' za zvoke.

Vedite, da lahko znotraj Wikipedijinih strani drugi urejajo ali
pobrišejo vaše naložene slike, če menijo, da to služi enciklopediji
in lahko vam zaprejo dostop, če izrabljate sistem.",
"uploadlog"		=> "dnevnik nalaganja",
"uploadlogpage"         => "Dnevnik_nalaganja",
"uploadlogpagetext" => "Spodaj je seznam najpoznejših naloženih datotek.
Vsi prikazani časi so strežnikov čas (UTC).
<ul>
</ul>
",
"filename"		=> "Imedatoteke",
"filedesc"		=> "Povzetek",
"affirmation"	        => "Potrjujem da se nosilec avtorske pravice te datoteke
strinja z licenco pod pogoji $1.",
"copyrightpage"         => "Wikipedija:Avtorske pravice",
"copyrightpagename"     => "Avtorske pravice Wikipedije",
"uploadedfiles"	        => "Naložene datoteke",
"noaffirmation"         => "Morate potrditi, da vaše nalaganje ne krši
nobenih avtorskih pravic.",
"ignorewarning"	        => "Zanemari opozorilo in vseeno shrani.",
"minlength"		=> "Imena slik morajo vsebovati vsaj tri črke.",
"badfilename"	        => "Slika se je spremenila v \"$1\".",
"badfiletype"	        => "\".$1\" ni priporočen format datotek slik.",
"largefile"		=> "Priporočeno je, da slike po elikosti ne presegajo 100 k.",
"successfulupload"      => "Nalaganje uspešno",
"fileuploaded"	        => "Datoteka \"$1\" je bila uspešno naložena.
Prosimo sledite tej povezavi: ($2) za stran opisa in izpolnite
podatke o datoteki, na primer od kod prihaja, kdaj je bila
izdelana in kdo jo je izdelal ali karkoli bi še vedeli o njej.",
"uploadwarning"         => "Opozorilo nalaganja",
"savefile"		=> "Shrani datoteko",
"uploadedimage"         => "naloženo \"$1\"",

# Image list
#
"imagelist"		=> "Seznam slik",
"imagelisttext"	        => "Spodaj je seznam $1 slik zložen $2.",
"getimagelist"	        => "prinašam seznam slik",
"ilshowmatch"	        => "Prikaži vse slike s podobnimi imeni",
"ilsubmit"		=> "Išči",
"showlast"		=> "Prikaži zadnjih $1 slik zloženih $2.",
"all"			=> "vse",
"byname"		=> "po imenu",
"bydate"		=> "po datumu",
"bysize"		=> "po velikosti",
"imgdelete"		=> "briš",
"imgdesc"		=> "opis",
"imglegend"		=> "Napotek: (opis) = prikaži/uredi opis slike.",
"imghistory"	        => "Zgodovina slike",
"revertimg"		=> "vrn",
"deleteimg"		=> "bri",
"deleteimgcompletely"		=> "bri",
"imghistlegend"         => "Napotek: (tre) = trenutna slika, (bri) = briši
zadnjo inačico, (vrn) = vrni sliko na to zadnjo inačico.
<br><i>Klikni na datum, da vidiš katera slika je bila tedaj naložena</i>.",
"imagelinks"	=> "Povezave slike",
"linkstoimage"	=> "Naslednje strani so vezane s to sliko:",
"nolinkstoimage" => "Nobena stran ni vezana s to sliko.",

# Statistics
#
"statistics"	=> "Statistika",
"sitestats"		=> "Statistika sedeža Wikipedije",
"userstats"		=> "Statistika uporabnika",
"sitestatstext" => "V podatkovni bazi je skupno <b>$1</b> strani.
Vključujejo tudi \"pogovorne\" strani, strani o Wikipediji, najmanjše \"škrbinske\" strani, preusmeritve in še druge, ki niso članki.
Če izključimo te zadnje, obstaja <b>$2</b> strani; ki so po vsej
verjetnosti pravi članki.<p>
Do sedaj je bilo <b>$3</b> pregledov strani in <b>$4</b> urejanj strani od
nadgraditve programske opreme (20. julij 2002).
To da skupaj povprečno <b>$5</b> urejevanj na stran in <b>$6</b> pogledov
na eno urejevanje.",
"userstatstext" => "<b>$1</b> je vpisanih uporabnikov. Od tega jih je <b>$2</b> administratorjev (glej $3).",

# Vzdrževalna stran
#
"maintenance"		=> "Vzdrževalna stran",
"maintnancepagetext"	=> "Ta stran vsebuje več pripravnih pripomočkov za vsakdanje vzdrževanje. Nekatere teh funkcij obremenjujejo podatkovno bazo, zato prosim ne poženite novega nalaganja pri vsakem popravljanju ;-)",
"maintenancebacklink"	=> "Nazaj na vzdrževalno stran",
"disambiguations"	=> "Razjasnjevalne strani",
"disambiguationspage"	=> "Wikipedija:Povezave_na_razjasnjevalne_strani",
"disambiguationstext"	=> "Naslednji članki so povezani na <i>razjasnjevalno stran</i>. Morajo biti povezani na pripadajočo vsebino.<br>Stran je razjasnjevalna, če je povezana iz $1.<br>Povezave iz drugih imenskih področij tukaj <i>niso</i> prikazane.",
"doubleredirects"	=> "Dvojne preusmeritve",
"doubleredirectstext"	=> "<b>Pozor:</b> ta seznam lahko vsebuje nepravilne člene. To ponavadi pomeni, da obstaja dodatno besedilo s povezavami pod prvim ukazom #REDIRECT.<br>\nVsaka vrsta vsebuje povezave k prvi in drugi preusmeritvi, kot tudi prvo vrstico drugega preusmerjenega besedila, kar ponavadi da \"resnični\" ciljni članek, na katerega mora kazati prva preusmeritev.",
"brokenredirects"	=> "Polomljene preusmeritve",
"brokenredirectstext"	=> "Naslednje preusmeritve kažejo na neobstoječe članke.",
"selflinks"		=> "strani z lastnimi povezavami",
"selflinkstext"		=> "Naslednje strani vsebujejo povezave nase, kar ne bi smele.",
"mispeelings"           => "Strani z napačnimi črkovanji",
"mispeelingstext"       => "Naslednje strani vsebujejo običajna napačna črkovanja, ki so prikazana na $1. Pravilno črkovanje bo mogoče zgledalo (kot).",
"mispeelingspage"       => "Seznam običajni napačnih črkovanja",
"missinglanguagelinks"  => "Manjkajoče jezikovne povezave",
"missinglanguagelinksbutton"    => "Najti manjkajoče jezikovne povezave za",
"missinglanguagelinkstext"      => "Ti članki <i>niso</i> povezani z njihovimi nasprotnimi članki v $1. Preusmeritve in podstrani <i>niso</i> prikazane.",


# Miscellaneous special pages
#
"orphans"	=> "Siromačne strani",
"lonelypages"	=> "Siromačne strani",
"unusedimages"	=> "Neuporabljene strani",
"popularpages"	=> "Priljubljene strani",
"nviews"	=> "$1 krat pregledano",
"wantedpages"	=> "Želene strani",
"nlinks"	=> "$1 povezav",
"allpages"	=> "Vse strani",
"randompage"	=> "Naključna stran",
"shortpages"	=> "Kratke strani",
"longpages"	=> "Dolge strani",
"listusers"	=> "Seznam uporabnikov",
"specialpages"	=> "Posebne strani",
"spheading"	=> "Posebne strani za vse uporabnike",
"sysopspheading" => "Posebne strani za vzdrževalce",
"developerspheading" => "Posebne strani za razvijalce",
"protectpage"	=> "Zaščiti stran",
"recentchangeslinked" => "Povezane strani",
"rclsub"	=> "(na strani povezano od \"$1\")",
"debug"		=> "Razhroščuj",
"newpages"	=> "Nove strani",
"movethispage"	=> "Premakni to stran",
"unusedimagestext" => "<p>Prosimo upoštevajte, da so lahko druge spletne strani, kot so mednarodne Wikipedije povezane s sliko z neposrednim URL-jem in so tukaj navedene, navkljub aktivni uporabi.",
"booksources"	=> "Knjižni viri",
"booksourcetext" => "Spodaj je seznam k drugim stranem, ki prodajajo nove ali rabljene knjige in kjer so lahko dodatne informacije o knjigah, ki jih iščete.
Wikipedija ne služi z nobenim od teh poslov in ta spisek ni pokazatelj njihovih uspehov.",

# Email this user
#
"mailnologin"	=> "Ni naslova odpošiljatelja",
"mailnologintext" => "Morate biti <a href=\"" .
  "{{localurle:Special:Userlogin}}\">prijavljeni</a>
in imeti veljaven naslov e-pošte v vaših <a href=\"" .
  "{{localurle:Special:Preferences}}\">nastavitvah</a>,
da lahko pošljete pošto drugim uporabnikom.",
"emailuser"	=> "Pošlji e-pošto temu uporabniku",
"emailpage"	=> "Pošlji e-pošto uporabniku",
"emailpagetext"	=> "Če je ta uporabnik vnesel veljaven naslov e-pošte v svojih ali njenih nastavitvah, bo spodnji vprašalnik poslal sporočilo.
Naslov e-pošte, ki ste jo vnesli v vaših uporabniških nastavitvah, bo v
 \"From\" naslovu pošte in bo lahko prejemnik odgovoril nanjo.",
"noemailtitle"	=> "Ni naslova e-pošte",
"noemailtext"	=> "Ta uporabnik ni navedel veljavnega naslova e-pošte, ali pa se je odločil, da ne bo prejemal pošte drugih.",
"emailfrom"	=> "Od",
"emailto"	=> "Za",
"emailsubject"	=> "Tema",
"emailmessage"	=> "Sporočilo",
"emailsend"	=> "Pošlji",
"emailsent"	=> "E-pošta poslana",
"emailsenttext" => "Vaše sporočilo je poslano z e-pošto.",

# Watchlist
#
"watchlist"	=> "Moj spisek nadzorov",
"watchlistsub"	=> "(za uporabnika \"$1\")",
"nowatchlist"	=> "Na vašem spisku nadzorov ni nobenega članka.",
"watchnologin"	=> "Niste prijavljeni",
"watchnologintext" => "Morate biti <a href=\"" .
  "{{localurle:Special:Userlogin}}\">prijavljeni</a>
za spremembo seznama nadzorov.",
"addedwatch"	=> "Dodano k seznamu nadzorov",
"addedwatchtext" => "Stran \"$1\" je dodana na vaš <a href=\"" .
  "{{localurle:Special:Watchlist}}\">seznam nadzorov</a>.
Morebitne spremembe te strani in njena pripadajoča pogovorna stran bosta navedeni tam in stran bo prikazana <b>krepko</b> v <a href=\"" .
  "{{localurle:Special:Recentchanges}}\">seznamu trenutnih sprememb</a>, da jo boste tudi lažje izbrali.</p>
<p>Če želite kasneje odstraniti stran iz seznama nadzorov, pritisnite \"Prekini nadzor\" v stranski vrstici.",
"removedwatch"	=> "Odstranjena iz seznama nadzorov",
"removedwatchtext" => "Stran \"$1\" je odstranjena iz vašega seznama nadzorov.",
"watchthispage"	=> "Nadzoruj to stran",
"unwatchthispage" => "Prekini nadzor",
"notanarticle"	=> "Ni članek",

# Delete/protect/revert
#
"deletepage"	=> "Zbriši stran",
"confirm"	=> "Potrdi",
"excontent"     => "vsebina je bila:",
"exbeforeblank" => "vsebina pred brisanjem je bila:",
"exblank"       => "stran je bila prazna",
"confirmdelete" => "Potrdi brisanje",
"deletesub"	=> "(Brišem \"$1\")",
"historywarning" => "OPOZORILO: stran, ki jo želite brisati ima zgodovino: ",
"confirmdeletetext" => "Za stalno boste zbrisali stran ali sliko skupaj z zgodovino iz podatkovne baze.
Prosimo potrdite vaš namen, da razumete posledice in da to počnete v skladu s [[Wikipedija:Pravila|pravili]].",
"confirmcheck"	 => "Da, resnično želim to zbrisati.",
"actioncomplete" => "Poseg zaključen",
"deletedtext"	 => "\"$1\" je zbrisana.
Glej $2 za zabeležbe nadavnih brisanj.",
"deletedarticle" => "zbrisan \"$1\"",
"dellogpage"	 => "Dnevnik_brisanja",
"dellogpagetext" => "Spodaj je seznam nedavnih brisanj.
Vsi časi so časi strežnika (UTC).
<ul>
</ul>
",
"deletionlog"	=> "dnevnik brisanja",
"reverted"	=> "Sprememba v prejšnjo različico",
"deletecomment"	=> "Razlog za brisanje",
"imagereverted" => "Sprememba v prejšnjo različico je bila uspešna.",
"rollback"	=> "Vrni spremembe",
"rollbacklink"	=> "vrni",
"cantrollback"	=> "Ne morem vrniti ureditve; zadnji avtor je hkrati edini.",
"alreadyrolled" => "Ne morem vrniti zadnje spremembe [[$1]]
od uporabnika [[Uporabnik:$2|$2]] ([[Pogovor z uporabnikom:$2|Pogovor]]); nekdo drug je že spremenil ali vrnil članek.

Zadnja sprememba od uporabnika [[Uporabnik:$3|$3]] ([[Pogovor z uporabnikom:$3|Pogovor]]). ",
#   only shown if there is an edit comment
"editcomment"  => "Tolmač spremembe je: \"<i>$1</i>\".",
"revertpage"   => "Vrnjeno na zadnje urejevanje od $1",

# Undelete
"undelete" => "Obnovi zbrisano stran",
"undeletepage" => "Poglej in obnovi zbrisane strani",
"undeletepagetext" => "Naslednje strani so bile zbrisane, vendar so še vedno v arhivu in jih lahko obnovite. Arhiv se mora občasno počistiti.",
"undeletearticle" => "Obnovi zbrisan članek",
"undeleterevisions" => "$1 različic arhiviranih",
"undeletehistory" => "Če obnovite stran, se bodo obnovile vse različice v zgodovini.
Če je kdo od brisanja naredil novo stran z istim imenom, se bodo obnovljene različice pojavile v prejšnji zgodovini in trenutna različica žive strani se ne bo samodejno zamenjala.",
"undeleterevision" => "Zbrisana različica od $1",
"undeletebtn" => "Obnovi!",
"undeletedarticle" => "obnovljen \"$1\"",
"undeletedtext"   => "Članek [[$1]] se je uspešno obnovil.
Glej [[Wikipedija:Dnevnik_brisanja]] za zabeležbe nedavnih brisanj in obnovitev.",

# Contributions
#
"contributions"	=> "Prispevki uporabnika",
"mycontris"     => "Moji prispevki",
"contribsub"	=> "Za $1",
"nocontribs"	=> "Ne najdem nobene spremembe, ki ustreza tem sodilom.",
"ucnote"	=> "Spodaj je zadnjih <b>$1</b> sprememb tega uporabnika v zadnjih <b>$2</b> dnevih.",
"uclinks"	=> "Poglej zadnjih $1 sprememb; poglej zadnje $2 dni.",
"uctop"		=> " (vrh)" ,

# What links here
#
"whatlinkshere"	=> "Kaj je povezano sem",
"notargettitle" => "Ni tarče",
"notargettext"	=> "Niste navedli ciljne strani ali uporabnika za izvršitev te funkcije.",
"linklistsub"	=> "(Seznam povezav)",
"linkshere"	=> "Naslednje strani so povezane sem:",
"nolinkshere"	=> "Nobena stran ni povezana sem.",
"isredirect"	=> "preusmeritvena stran",

# Block/unblock IP
#
"blockip"	=> "Prekini IP naslov",
"blockiptext"	=> "Uporabi spodnjo obliko za prekinitev dostopa zapisa iz določenega IP naslova.
To naredimo samo zaradi zaščite pred nepotrebnim uničevanjem in v skladu s
[[Wikipedija:Pravila|pravili Wikipedije]].
Vnesi razloge spodaj (na primer z navedbo določenih strani, ki so jih po nepotrebnem uničili).",
"ipaddress"	=> "IP naslov",
"ipbreason"	=> "Razlog",
"ipbsubmit"	=> "Prekini ta naslov",
"badipaddress"	=> "IP naslov je slabo oblikovan.",
"noblockreason" => "Morate navesti razlog prekinitve.",
"blockipsuccesssub" => "Prekinitev je uspela",
"blockipsuccesstext" => "IP naslov \"$1\" je prekinjen.
<br>Glej [[Posebno:Ipseznamprekinitev|seznam prekinitev IP]] za pregled prekinitev.",
"unblockip"	=> "Poveži IP naslov",
"unblockiptext"	=> "Uporabi spodnjo obliko za obnovitev dostopa zapisa prejšnjega prekinjenega IP naslova.",
"ipusubmit"	=> "Poveži ta naslov",
"ipusuccess"	=> "IP naslov \"$1\" je povezan",
"ipblocklist"	=> "Seznam prekinjenih IP naslovov",
"blocklistline"	=> "$1, $2 je prekinil $3",
"blocklink"	=> "prekini",
"unblocklink"	=> "poveži",
"contribslink"	=> "prispevki",
"autoblocker" => "Samodejno se prekinili, ker si delite IP naslov z \"$1\". Razlog \"$2\".",


# Developer tools
#
"lockdb"	=> "Zakleni podatkovno bazo",
"unlockdb"	=> "Odkleni podatkovno bazo",
"lockdbtext"	=> "Zaklenitev podatkovne baze bo odložila možnost urejevanja vsem uporabnikom, spremembe njihovih nastavitev, urejevanja njihovih seznamov nadzorov in drugih stvari, ki zahtevajo spremembe v podatkovni bazi.
Prosimo potrdite vaš resnični namen in da boste odklenili podatkovno bazo, ko boste zaključili z vzdrževanjem podatkovne baze.",
"unlockdbtext"	=> "Odklenitev podatkovne baze bo obnovila zmožnost urejevanja vsem uporabnikom, spremembe njihovih nastavitev, urejevanja njihovih seznamov nadzorov in drugih stvari, ki zahtevajo spremembe v podatkovni bazi.
Prosimo potrdite vaš resnični namen.",
"lockconfirm"	=> "Da, resnično želim zakleniti podatkovno bazo.",
"unlockconfirm"	=> "Da, resnično želim odkleniti podatkovno bazo.",
"lockbtn"	=> "Zakleni podatkovno bazo",
"unlockbtn"	=> "Odkleni podatkovno bazo",
"locknoconfirm" => "Niste potrdili svoje namere.",
"lockdbsuccesssub" => "Zaklenitev podatkovne baze je uspela",
"unlockdbsuccesssub" => "Podatkovna baza je odklenjena",
"lockdbsuccesstext" => "Podatkovna baza Wikipedije je bila zaklenjena.
<br>Ne pozabite odkleniti, ko boste končali z vzdrževanjem.",
"unlockdbsuccesstext" => "Podatkovna baza Wikipedije je bila odklenjena.",

# SQL query
#
"asksql"	=> "SQL vprašanje",
"asksqltext"	=> "Uporabi spodnjo obliko za neposedno vprašanje podatkovni bazi Wikipedije.
Uporabite enojne narekovaje ('tako') za razmejitev črkovnih nizov.
To precej obremeni strežnik zato, prosimo, previdno uporabljajte to funkcijo.",
"sqlquery"	=> "Vnesite vprašanje",
"querybtn"	=> "Pošljite vprašanje",
"selectonly"	=> "Vsa vprašanja razen \"SELECT\" so omejeni za razvijalce Wikipedije.",
"querysuccessful" => "Vprašanje uspešno",

# Move page
#
"movepage"	=> "Prestavi stran",
"movepagetext"	=> "Uporaba spodnje oblike bo preimenovala stran, prestavila vso njeno zgodovino na novo ime.
Stara stran bo preusmeritvena stran na nov naslov.
Povezave na stari naslov strani se ne bodo spremenile; zagotovo [[Posebno:Vzdrževanje|preverite]] dvojne ali pretrgane preusmeritve.
Odgovorni ste, da povezave še naprej kažejo na pravilna mesta.

Upoštevajte, da stran '''ne''' bo prestavljena, če stran z istim imenom že obstaja, razen če je prazna ali preusmeritvena in je brez zgodovine urejevanj. To pomeni, da lahko preimenujete stran nazaj na prejšnjo, če ste se zmotili in ne morete prepisati obstoječe strani.

<b>OPOZORILO!</b>
To je lahko velika in nepričakovana sprememba za priljubljeno stran;
prosimo bodite prepričani, da razumete posledice tega, preden nadaljujete.",
"movepagetalktext" => "Pripadajoča pogovorna stran bo tudi samodejno prestavljena '''razen:'''
*Če prestavljate stran preko imenskih področij,
*Če že obstaja neprazna pogovorna stran pod istim imenom, ali
*Odkljukajte spodnji okvirček.

V teh primerih boste morali prestaviti ali povezati stran ročno, če to želite.",
"movearticle"	=> "Prestavite stran",
"movenologin"	=> "Niste vpisani",
"movenologintext" => "Za prestavitev strani morate biti zabeležen uporabnik in <a href=\"" .
  "{{localurle:Special:Userlogin}}\">prijavljeni</a>.",
"newtitle"	=> "Na nov naslov",
"movepagebtn"	=> "Prestavite stran",
"pagemovedsub"	=> "Prstavitev uspela",
"pagemovedtext" => "Stran \"[[$1]]\" prestavljena na \"[[$2]]\".",
"articleexists" => "Stran s tem imenom že obstaja ali pa izbrano ime ni pravilno. Prosimo izberite drugo ime.",
"talkexists"	=> "Stran sama je prestavljena uspešno, pogovorna stran pa ne, ker že obstaja na novem naslovu. Prosimo povežite ju ročno.",
"movedto"	=> "prestavljeno na",
"movetalk"	=> "Prestavite tudi \"pogovorno\" stran, če je mogoče.",
"talkpagemoved" => "Pripadajoča pogovorna stran je tudi prestavljena.",
"talkpagenotmoved" => "Pripadajoča pogovorna stran <strong>ni</strong> prestavljena.",
# Math
	'mw_math_png' => "Vedno prikaži PNG",
	'mw_math_simple' => "Če je dovolj preprosto, uporabi HTML, drugače pa PNG",
	'mw_math_html' => "Uporabi HTML, če je možno, drugače pa PNG",
	'mw_math_source' => "Pusti v TeX-ovi obliki (za tekstovne brskljalnike)",
	'mw_math_modern' => "Priporočeno za sodobne brskljalnike",
	'mw_math_mathml' => 'MathML',

);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageSl extends LanguageUtf8 {

  function getDefaultUserOptions () {
   $opt = Language::getDefaultUserOptions();
   return $opt;
  }

 function getNamespaces() {
  global $wgNamespaceNamesSl;
  return $wgNamespaceNamesSl;
 }

 function getNsText( $index ) {
  global $wgNamespaceNamesSl;
  return $wgNamespaceNamesSl[$index];
 }

 function getNsIndex( $text ) {
  global $wgNamespaceNamesSl;

  foreach ( $wgNamespaceNamesSl as $i => $n ) {
   if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
  }
  if( 0 == strcasecmp( "Special", $text ) ) { return -1; }
  if( 0 == strcasecmp( "User", $text ) ) { return 2; }
  if( 0 == strcasecmp( "Wikipedia", $text ) ) { return 4; }
  return false;
 }

 function getQuickbarSettings() {
  global $wgQuickbarSettingsSl;
  return $wgQuickbarSettingsSl;
 }

 function getSkinNames() {
  global $wgSkinNamesSl;
  return $wgSkinNamesSl;
 }

 function getDateFormats() {
  global $wgDateFormatsSl;
  return $wgDateFormatsSl;
 }

 function getValidSpecialPages()
 {
  global $wgValidSpecialPagesSl;
  return $wgValidSpecialPagesSl;
 }

 function getSysopSpecialPages()
 {
  global $wgSysopSpecialPagesSl;
  return $wgSysopSpecialPagesSl;
 }

 function getDeveloperSpecialPages()
 {
  global $wgDeveloperSpecialPagesSl;
  return $wgDeveloperSpecialPagesSl;
 }

 function getMessage( $key )
 {
		global $wgAllMessagesSl;
		if(array_key_exists($key, $wgAllMessagesSl))
			return $wgAllMessagesSl[$key];
		else
			return Language::getMessage($key);
 }

 function fallback8bitEncoding() {
		return "iso-8859-2";
 }
}

?>
