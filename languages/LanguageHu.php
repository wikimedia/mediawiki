<?

include_once("Utf8Case.php");

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesHu = array(
	-1	=> "Speciális",
	0	=> "",
	1	=> "Vita",
	2	=> "User",
	3	=> "User_vita",
	4	=> "Wikipedia",
	5	=> "Wikipedia_vita",
	6	=> "Kép",
	7	=> "Kép_vita"
);

/* private */ $wgDefaultUserOptionsHu = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0,
	"previewontop" => 1, "editsection"=>1, "showtoc"=>1
);

/* private */ $wgQuickbarSettingsHu = array(
	"Nincs", "Fix baloldali", "Fix jobboldali", "Lebegő baloldali"
);

/* private */ $wgSkinNamesHu = array(
	"Alap", "Nosztalgia", "Kölni kék"
);

/* private */ $wgMathNamesHu = array(
	"Mindig készítsen PNG-t",
	"HTML ha nagyon egyszerű, egyébként PNG",
	"HTML ha lehetséges, egyébként PNG",
	"Hagyja TeX formában (szöveges böngészőknek)",
	"Modern böngészőknek ajánlott beállítás"
);

/* private */ $wgDateFormatsHu = array(
	"Mindegy",
	"Július 8, 2003",
	"8 Július, 2003",
	"2003 Július 8"
);

/* private */ $wgUserTogglesHu = array(
	"hover"		=> "Mutassa a címdobozt a linkek fölött",
	"underline" => "Linkek aláhúzása",
	"highlightbroken" => "Törött linkek <a href=\"\" class=\"new\">így</a> (alternatíva: így<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Bekezdések teljes szélességű tördelése",
	"hideminor" => "Apró változtatások elrejtése a recent changes-ben",
	"usenewrc" => "Modern változások listája (nem minden böngészőre)",
	"numberheadings" => "Címsorok automatikus számozása",
	"editsection"=>"Linkek az egyes szakaszok szerkesztéséhez",
	"showtoc"=>"Három fejezetnél többel rendelkező cikkeknél mutasson tartalomjegyzéket",
	"rememberpassword" => "Jelszó megjegyzése a használatok között",
	"editwidth" => "Teljes szélességű szerkesztőterület",
	"editondblclick" => "Lapon duplakattintásra szerkesztés (JavaScript)",
	"watchdefault" => "Figyelje az új és a megváltoztatott cikkeket",
	"minordefault" => "Alapból minden szerkesztést jelöljön aprónak",
	"previewontop" => "Előnézet a szerkesztőterület előtt és nem utána",
	"nocache" => "Lapok gyorstárazásának letiltása"
);

/* private */ $wgBookstoreListHu = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* Language names should be the native names. Inherit common array from Language.php */

/* private */ $wgWeekdayNamesHu = array(
	"vasárnap", "hétfő", "kedd", "szerda", "csütörtök",
	"péntek", "szombat"
);

/* private */ $wgMonthNamesHu = array(
	"január", "február", "március", "április", "május", "június",
	"július", "augusztus", "szeptember", "október", "november",
	"december"
);

/* private */ $wgMonthAbbreviationsHu = array(
	"Jan", "Feb", "Már", "Ápr", "Máj", "Jún", "Júl", "Aug",
	"Sep", "Okt", "Nov", "Dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesHu = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Beállításaim",
	"Watchlist"		=> "Figyelőlistám",
	"Recentchanges" => "Frissen változtatott oldalak",
	"Upload"		=> "Képek feltöltése",
	"Imagelist"		=> "Képek listája",
	"Listusers"		=> "Regisztrált felhasználók",
	"Statistics"	=> "Weblap statisztika",
	"Randompage"	=> "Egy lap találomra",

	"Lonelypages"	=> "Árva szócikkek",
	"Unusedimages"	=> "Árva képek",
	"Popularpages"	=> "Népszerű szócikkek",
	"Wantedpages"	=> "Hiányszócikkek",
	"Shortpages"	=> "Rövid szócikkek",
	"Longpages"		=> "Hosszú szócikkek",
	"Newpages"		=> "Újonnan készült szócikkek",
	"Ancientpages"	=> "Ősi szócikkek",
	"Intl"		=> "Nyelvek közötti linkek",
	"Allpages"		=> "Az összes lap cím szerint",

	"Ipblocklist"	=> "Blokkolt IP címek",
	"Maintenance" => "Karbantartási lap",
	"Specialpages"  => "Speciális lapok",
	"Contributions" => "Hozzájárulások",
	"Emailuser"		=> "Email írása",
	"Whatlinkshere" => "Mi mutat ide",
	"Recentchangeslinked" => "Kapcsolódó változások",
	"Movepage"		=> "Lap mozgatása",
	"Booksources"	=> "Külső könyvinformációk"
);

/* private */ $wgSysopSpecialPagesHu = array(
	"Blockip"		=> "Block an IP address",
	"Asksql"		=> "Query the database",
	"Undelete"		=> "Restore deleted pages"
);

/* private */ $wgDeveloperSpecialPagesHu = array(
	"Lockdb"		=> "Make database read-only",
	"Unlockdb"		=> "Restore DB write access",
	"Debug"			=> "Debugging information"
);

/* private */ $wgAllMessagesHu = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^((?:[a-z]|á|é|í|ú|ó|ö|ü|ő|ű|Á|É|Í|Ó|Ú|Ö|Ü|Ő|Ű)+)(.*)\$/sD",
"mainpage"		=> "Kezdőlap",
"mainpagetext"	=> "Wiki szoftver sikeresen telepítve.",
"about"			=> "Névjegy",
"aboutwikipedia" => "A Wikipédiáról",
"aboutpage"		=> "Wikipedia:Névjegy",
"help"			=> "Segítség",
"helppage"		=> "Wikipedia:Segítség",
"wikititlesuffix" => "Wikipédia",
"bugreports"	=> "Hibajelentés",
"bugreportspage" => "Wikipedia:Hibajelentések",
"faq"			=> "GyIK",
"faqpage"		=> "Wikipedia:GyIK",
"edithelp"		=> "Segítség a szerkesztéshez",
"edithelppage"	=> "Wikipedia:Hogyan_szerkessz_egy_lapot",
"cancel"		=> "Vissza",
"qbfind"		=> "Keresés",
"qbbrowse"		=> "Böngészés",
"qbedit"		=> "Szerkeszt",
"qbpageoptions" => "Lapbeállítások",
"qbpageinfo"	=> "Lapinformáció",
"qbmyoptions"	=> "Beállításaim",
"mypage"		=> "Lapom",
"mytalk"		=> "Vitám",
"currentevents" => "Friss események",
"errorpagetitle" => "Hiba",
"returnto"		=> "Vissza a $1 cikkhez.",
"fromwikipedia"	=> "A Wikipediából, a szabad enciklopédiából.",
"whatlinkshere"	=> "Lapok, melyek ide mutatnak",
"help"			=> "Segítség",
"search"		=> "Keresés",
"go"		=> "Menj!",
"history"		=> "Laptörténet",
"printableversion" => "Nyomtatható változat",
"editthispage"	=> "Szerkeszd ezt a lapot",
"deletethispage" => "Lap törlése",
"protectthispage" => "Védelem a lapnak",
"unprotectthispage" => "Védelem megszüntetése",
"newpage" => "Új lap",
"talkpage"		=> "Lap megbeszélése",
"articlepage"	=> "Szócikk megtekintése",
"subjectpage"	=> "Témalap megtekintése", # For compatibility
"userpage" => "Felhasználói lap",
"wikipediapage" => "Metalap",
"imagepage" => 	"Képlap",
"viewtalkpage" => "Beszélgetés megtekintése",
"otherlanguages" => "Egyéb nyelvek",
"redirectedfrom" => "(Átirányítva $1 cikkből)",
"lastmodified"	=> "A lap utolsó módosítása $1.",
"viewcount"		=> "Ezt a lapot eddig $1 alkalommal látogatták.",
"gnunote" => "Minden szöveg a <a class=internal href='/wiki/GNU_FDL'>GNU Szabad Dokumentációk Liszensze</a> feltételei mellett érhető el.",
"printsubtitle" => "(From http://www.wikipedia.org)",
"protectedpage" => "Védett lap",
"administrators" => "Wikipedia:Adminisztrátorok",
"sysoptitle"	=> "Sysop hozzáférés szükséges",
"sysoptext"		=> "Az általad kért tevékenységet csak \"sysopok\" végezhetik el.
Lásd $1.",
"developertitle" => "Developer access required",
"developertext"	=> "The action you have requested can only be
performed by users with \"developer\" status.
See $1.",
"nbytes"		=> "$1 byte",
"go"			=> "Menj",
"ok"			=> "OK",
"sitetitle"		=> "Wikipédia",
"sitesubtitle"	=> "A szabad enciklopédia",
"retrievedfrom" => "Retrieved from \"$1\"",
"newmessages" => "$1 van.",
"newmessageslink" => "Új üzeneted",
"editsection"=>"szerkesztés",
"toc" => "Tartalomjegyzék",

# Main script and global functions
#
"nosuchaction"	=> "Nincs ilyen tevékenység",
"nosuchactiontext" => "Az URL által megadott tevékenységet a Wikipédia
software nem ismeri fel",
"nosuchspecialpage" => "Nincs ilyen speciális lap",
"nospecialpagetext" => "Olyan speciális lapot kértél amit a Wikipédia
software nem ismer fel.",

# General errors
#
"error"			=> "Hiba",
"databaseerror" => "Adatbázis hiba",
"dberrortext"	=> "Adatbázis formai hiba történt.
Ennek lehet oka egy illegális keresési parancs (lásd $5),
vagy okozhatta egy programozási hiba.
Az utolsó lekérési próbálkozás az alábbi volt:
<blockquote><tt>$1</tt></blockquote>
a \"<tt>$2</tt>\" függvényből.
A MySQL hiba \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Egy adatbázis lekérés formai hiba történt.
Az utolsó lekérési próbálkozás:
\"$1\"
a \"$2\" függvényből történt.
A MySQL hiba \"$3: $4\".\n",
"noconnect"		=> "Nem tudok a $1 adatbázis gépre csatlakozni",
"nodb"			=> "Nem tudom elérni a $1 adatbázist",
"readonly"		=> "Adatbázis lezárva",
"enterlockreason" => "Add meg a lezárás indoklását valamint egy becslést,
hogy mikor kerül a lezárás feloldásra",
"readonlytext"	=> "A Wikipédia adatbázis jelenleg le van zárva az új
szócikkek és módosítások elől, valószínűleg adatbázis karbantartás miatt, 
aminek a végén minden visszaáll a régi kerékvágásba.
Az adminisztrátor aki a lezárást elvégezte az alábbi magyarázatot adta:
<p>$1",
"missingarticle" => "Az adatbázis nem találta meg egy létező lap szövegét,
aminek a neve \"$1\".

<p>Ennek oka általában egy olyan régi link kiválasztása, ami egy
már törölt lap történetére hivatkozik.

<p>Ha nem erről van szó akkor lehetséges, hogy programozási hibát
találtál a software-ben. Kérlek értesíts erről egy adminisztrátort,
és jegyezd fel neki az URL-t (pontos webcímet) is.",
"internalerror" => "Belső hiba",
"filecopyerror" => "Nem tudom a \"$1\" file-t a \"$2\" névre másolni.",
"filerenameerror" => "Nem tudom a \"$1\" file-t \"$2\" névre átnevezni.",
"filedeleteerror" => "Nem tudom a \"$1\" file-t letörölni.",
"filenotfound"	=> "Nem találom a \"$1\" file-t.",
"unexpected"	=> "Váratlan érték: \"$1\"=\"$2\".",
"formerror"		=> "Hiba: nem tudom a formot elküldeni",	
"badarticleerror" => "Ez a tevékenység nem végezhető ezen a lapon.",
"cannotdelete"	=> "Nem tudom a megadott lapot vagy képet törölni. (Talán már valaki más törölte.)",
"badtitle"		=> "Hibás cím",
"badtitletext"	=> "A kért cím helytelen, üres vagy hibásan hivatkozik
egy nyelvek közötti vagy wikik közötti címre.",
"perfdisabled" => "Bocsánat! Ez a lehetőség időszakosan nem elérhető
mert annyira lelassítja az adatbázist, hogy senki nem tudja a 
wikit használni.",
"perfdisabledsub" => "Íme $1 egy elmentett másolata:",

# Login and logout pages
#
"logouttitle"	=> "Kilépés",
"logouttext"	=> "Kiléptél.
Folytathatod a Wikipédia használatát név nélkül, vagy beléphetsz
újra vagy másik felhasználóként.\n",

"welcomecreation" => "<h2>Üdvözöllek, $1!</h2><p>A felhasználói környezeted
létrehoztam.
Ne felejtsd el átnézni a személyes Wikipédia beállításaidat.",

"loginpagetitle" => "Belépés",
"yourname"		=> "A felhasználói neved",
"yourpassword"	=> "Jelszavad",
"yourpasswordagain" => "Jelszavad ismét",
"newusersonly"	=> " (csak új felhasználóknak)",
"remembermypassword" => "Jelszó megjegyzése a használatok között.",
"loginproblem"	=> "<b>Valami probléma van a belépéseddel.</b><br>Kérlek próbáld ismét!",
"alreadyloggedin" => "<font color=red><b>Kedves $1, már be vagy lépve!</b></font><br>\n",

"areyounew"		=> "Ha új látogató vagy és szeretnél magadnak felhasználói nevet,
akkor adj meg egyet, és adj meg egy jelszót (kétszer).
Az email cím megadása nem kötelező; ha elveszíted a jelszavad, akkor
újat a megadott email címre tudsz kérni.<br>\n",

"login"			=> "Belépés",
"userlogin"		=> "Belépés",
"logout"		=> "Kilépés",
"userlogout"	=> "Kilépés",
"notloggedin"	=> "Nincs belépve",
"createaccount"	=> "Új felhasználó készítése",
"badretype"		=> "A két jelszó eltér egymástól.",
"userexists"	=> "A név amit megadtál már létezik. Kérlek, adj meg más nevet.",
"youremail"		=> "Az emailed*",
"yournick"		=> "A beceneved (aláírásokhoz)",
"emailforlost"	=> "* Az email cím megadása nem kötelező, viszont lehetővé
teszi másoknak, hogy kapcsolatba lépjenek veled a weblapon keresztül
anélkül, hogy a címedet megtudnák. Segítségedre lehet akkor is, ha
elfelejted a jelszavadat.",
"loginerror"	=> "Belépési hiba.",
"noname"		=> "Nem adtál meg érvényes felhasználói nevet.",
"loginsuccesstitle" => "Sikeres belépés",
"loginsuccess"	=> "Beléptél a Wikipédiába \"$1\"-ként.",
"nosuchuser"	=> "Nincs olyan felhasználó hogy \"$1\".
Ellenőrizd a gépelést, vagy készíts új nevet a fent látható űrlappal.",
"wrongpassword"	=> "A megadott jelszó helytelen.",
"mailmypassword" => "Küldd el nekem a jelszavamat emailben",
"passwordremindertitle" => "Wikipédia jelszó emlékeztető",
"passwordremindertext" => "Valaki (vélhetően te, a $1 IP számról)
azt kérte, hogy küldjük el a jelszavadat.
A jelszavad a \"$2\" felhasználóhoz most \"$3\".
Lépj be, és változtasd meg a jelszavad.",
"noemail"		=> "Nincs a \"$1\" felhasználóhoz email felvéve.",
"passwordsent"	=> "Az új jelszót elküldtük \"$1\" email címére.
Lépj be a levélben található adatokkal.",

# Edit pages
#
"summary"		=> "Összefoglaló",
"minoredit"		=> "Ez egy apró változtatás",
"watchthis"		=> "Figyeld a szócikket",
"savearticle"	=> "Lap mentése",
"preview"		=> "Előnézet",
"showpreview"	=> "Előnézet megtekintése",
"blockedtitle"	=> "A felhasználó le van tiltva",
"blockedtext"	=> "A felhasználói neved vagy IP számod $1 letiltotta.
Az indoklás:<br>''$2''
<p>Felveheted a kapcsolatot $1 adminnal vagy bármely más
[[Wikipedia:adminisztrátorok|adminisztrátorral]] hogy megvitasd a letiltást.",
"newarticle"	=> "(Új)",
"newarticletext" =>
"Egy olyan lapra jutottál ami még nem létezik.
A lap létrehozásához kezdd el írni a szövegét lenti keretbe
(a [[Wikipedia:Segítség|segítség]] lapon lelsz további 
információkat).
Ha tévedésből jöttél ide, csak nyomd meg a böngésző '''Vissza/Back'''
gombját.",
"anontalkpagetext" => "
---- ''Ez egy olyan anonim felhasználó vitalapja, aki még nem készített magának nevet vagy azt nem használta. Ezért az [[IP szám]]át használjuk az azonosítására. Az IP számokon számos felhasználó osztozhat az idők folyamán. Ha anonim felhasználó vagy és úgy érzed, hogy értelmetlen megjegyzéseket írnak neked akkor [[Special:Userlogin|készíts magadnak egy nevet vagy lépj be]] hogy megakadályozd más anonim felhasználókkal való keveredést.'' ",
"noarticletext" => "(Ez a lap jelenleg nem tartalmaz szöveget)",
"updated"		=> "(Frissítve)",
"note"			=> "<strong>Megjegyzés:</strong> ",
"previewnote"	=> "Ne felejtsd el, hogy ez csak egy előnézet, és nincs elmentve!",
"previewconflict" => "Ez az előnézet a felső szerkesztőablakban levő
szövegnek megfelelő képet mutatja, ahogy az elmentés után kinézne.",
"editing"		=> "$1 szerkesztés alatt",
"sectionedit"	=> " (szakasz)",
"editconflict"	=> "Szerkesztési ütközés: $1",
"explainconflict" => "Valaki megváltoztatta a lapot azóta,
mióta szerkeszteni kezdted.
A felső szövegablak tartalmazza a szöveget, ahogy az jelenleg létezik.
A módosításaid az alsó ablakban láthatóak.
Át kell vezetned a módosításaidat a felső szövegbe.
<b>Csak</b> a felső ablakban levő szöveg kerül elmentésre akkor, mikor
a \"Lap mentését\" választod.\n<p>",
"yourtext"		=> "A te szöveged",
"storedversion" => "A tárolt változat",
"editingold"	=> "<strong>VIGYÁZAT! A lap egy elavult 
változatát szerkeszted.
Ha elmented, akkor az ezen változat után végzett összes 
módosítás elvész.</strong>\n",
"yourdiff"		=> "Eltérések",
"copyrightwarning" => "Kérlek vedd figyelembe hogy minden
Wikipédiába küldött anyag a GNU Szabad Dokumentum Licenc alatti
publikálásnak számít (lásd $1 a részletekért).
Ha nem akarod, hogy az írásod könyörtelenül módosíthassák vagy
tetszés szerint terjesszék, akkor ne küldd be ide.<br>
A beküldéssel együtt azt is garantálod hogy mindezt saját
magad írtad, vagy másoltad be egy szabadon elérhető vagy 
közkincs (public domain) forrásból.
<strong>ENGEDÉLY NÉLKÜL NE KÜLDJ BE JOGVÉDETT ANYAGOKAT!</strong>",
"longpagewarning" => "FIGYELEM: Ez a lap $1 kilobyte hosszú;
néhány böngészőnek problémái vannak a 32KB körüli vagy nagyobb lapok
szerkesztésével.
Fontold meg a lap kisebb szakaszokra bontását.",
"readonlywarning" => "FIGYELEM: Az adatbázis karbantartás miatt le van zárva,
ezért a módosításaidat most nem lehetséges elmenteni. Érdemes a szöveget
kimásolni és elmenteni egy szövegszerkesztőben a későbbi mentéshez.",
"protectedpagewarning" => "FIGYELEM: A lap lezárásra került és ilyenkor
csak a Sysop jogú adminisztrátorok tudják szerkeszteni. Ellenőrizd, hogy
betartod a <a href='/wiki/Wikipedia:Zart_lapok_iranyelve'>zárt lapok 
irányelvét</a>.",

# History pages
#
"revhistory"	=> "Revision history",
"nohistory"		=> "There is no edit history for this page.",
"revnotfound"	=> "Revision not found",
"revnotfoundtext" => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
"loadhist"		=> "Loading page history",
"currentrev"	=> "Current revision",
"revisionasof"	=> "Revision as of $1",
"cur"			=> "cur",
"next"			=> "next",
"last"			=> "last",
"orig"			=> "orig",
"histlegend"	=> "Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit",

# Diffs
#
"difference"	=> "(Difference between revisions)",
"loadingrev"	=> "loading revision for diff",
"lineno"		=> "Line $1:",
"editcurrent"	=> "Edit the current version of this page",

# Search results
#
"searchresults" => "Search results",
"searchhelppage" => "Wikipedia:Searching",
"searchingwikipedia" => "Searching Wikipedia",
"searchresulttext" => "For more information about searching Wikipedia, see $1.",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "Badly formed search query",
"badquerytext"	=> "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.",
"matchtotals"	=> "The query \"$1\" matched $2 article titles
and the text of $3 articles.",
"nogomatch" => "No page with this exact title exists, trying full text search.",
"titlematches"	=> "Article title matches",
"notitlematches" => "No article title matches",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "No article text matches",
"prevn"			=> "previous $1",
"nextn"			=> "next $1",
"viewprevnext"	=> "View ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "Search",
"powersearchtext" => "
Search in namespaces :<br>
$1<br>
$2 List redirects &nbsp; Search for $3 $9",


# Preferences page
#
"preferences"	=> "Preferences",
"prefsnologin" => "Not logged in",
"prefsnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to set user preferences.",
"prefslogintext" => "You are logged in as \"$1\".
Your internal ID number is $2.",
"prefsreset"	=> "Preferences have been reset from storage.",
"qbsettings"	=> "Quickbar settings", 
"changepassword" => "Change password",
"skin"			=> "Skin",
"math"			=> "Rendering math",
"dateformat"	=> "Date format",
"math_failure"		=> "Failed to parse",
"math_unknown_error"	=> "unknown error",
"math_unknown_function"	=> "unknown function ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "syntax error",
"saveprefs"		=> "Save preferences",
"resetprefs"	=> "Reset preferences",
"oldpassword"	=> "Old password",
"newpassword"	=> "New password",
"retypenew"		=> "Retype new password",
"textboxsize"	=> "Textbox dimensions",
"rows"			=> "Rows",
"columns"		=> "Columns",
"searchresultshead" => "Search result settings",
"resultsperpage" => "Hits to show per page",
"contextlines"	=> "Lines to show per hit",
"contextchars"	=> "Characters of context per line",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Number of titles in recent changes",
"savedprefs"	=> "Your preferences have been saved.",
"timezonetext"	=> "Enter number of hours your local time differs
from server time (UTC).",
"localtime"	=> "Local time display",
"timezoneoffset" => "Offset",
"servertime"	=> "Server time is now",
"guesstimezone" => "Fill in from browser",
"emailflag"		=> "Disable e-mail from other users",
"defaultns"		=> "Search in these namespaces by default:",

# Recent changes
#
"changes" => "changes",
"recentchanges" => "Recent changes",
"recentchangestext" => "Track the most recent changes to Wikipedia on this page.
[[Wikipedia:Welcome,_newcomers|Welcome, newcomers]]!
Please have a look at these pages: [[wikipedia:FAQ|Wikipedia FAQ]],
[[Wikipedia:Policies and guidelines|Wikipedia policy]]
(especially [[wikipedia:Naming conventions|naming conventions]],
[[wikipedia:Neutral point of view|neutral point of view]]),
and [[wikipedia:Most common Wikipedia faux pas|most common Wikipedia faux pas]].

If you want to see Wikipedia succeed, it's very important that you don't add
material restricted by others' [[wikipedia:Copyrights|copyrights]].
The legal liability could really hurt the project, so please don't do it.
See also the [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].",
"rcloaderr"		=> "Loading recent changes",
"rcnote"		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
"rclistfrom"	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
"rclinks"		=> "Show last $1 changes in last $2 days; $3 minor edits",
"rchide"		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			=> "; $1 edits from logged in users",
"diff"			=> "diff",
"hist"			=> "hist",
"hide"			=> "hide",
"show"			=> "show",
"tableform"		=> "table",
"listform"		=> "list",
"nchanges"		=> "$1 changes",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Upload file",
"uploadbtn"		=> "Upload file",
"uploadlink"	=> "Upload images",
"reupload"		=> "Re-upload",
"reuploaddesc"	=> "Return to the upload form.",
"uploadnologin" => "Not logged in",
"uploadnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to upload files.",
"uploadfile"	=> "Upload file",
"uploaderror"	=> "Upload error",
"uploadtext"	=> "<strong>STOP!</strong> Before you upload here,
make sure to read and follow Wikipedia's <a href=\"" .
wfLocalUrlE( "Wikipedia:Image_use_policy" ) . "\">image use policy</a>.
<p>To view or search previously uploaded images,
go to the <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">list of uploaded images</a>.
Uploads and deletions are logged on the <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">upload log</a>.
<p>Use the form below to upload new image files for use in
illustrating your articles.
On most browsers, you will see a \"Browse...\" button, which will
bring up your operating system's standard file open dialog.

Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.
This may take some time if you have a slow internet connection.
<p>The preferred formats are JPEG for photographic images, PNG
for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in an article, use a link in the form
<b>[[image:file.jpg]]</b> or <b>[[image:file.png|alt text]]</b>
or <b>[[media:file.ogg]]</b> for sounds.
<p>Please note that as with Wikipedia pages, others may edit or
delete your uploads if they think it serves the encyclopedia, and
you may be blocked from uploading if you abuse the system.",
"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",
"filename"		=> "Filename",
"filedesc"		=> "Summary",
"affirmation"	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "Wikipedia:Copyrights",
"copyrightpagename" => "Wikipedia copyright",
"uploadedfiles"	=> "Uploaded files",
"noaffirmation" => "You must affirm that your upload does not violate
any copyrights.",
"ignorewarning"	=> "Ignore warning and save file anyway.",
"minlength"		=> "Image names must be at least three letters.",
"badfilename"	=> "Image name has been changed to \"$1\".",
"badfiletype"	=> "\".$1\" is not a recommended image file format.",
"largefile"		=> "It is recommended that images not exceed 100k in size.",
"successfulupload" => "Successful upload",
"fileuploaded"	=> "File \"$1\" uploaded successfully.
Please follow this link: ($2) to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
"uploadwarning" => "Upload warning",
"savefile"		=> "Save file",
"uploadedimage" => "uploaded \"$1\"",

# Image list
#
"imagelist"		=> "Image list",
"imagelisttext"	=> "Below is a list of $1 images sorted $2.",
"getimagelist"	=> "fetching image list",
"ilshowmatch"	=> "Show all images with names matching",
"ilsubmit"		=> "Search",
"showlast"		=> "Show last $1 images sorted $2.",
"all"			=> "all",
"byname"		=> "by name",
"bydate"		=> "by date",
"bysize"		=> "by size",
"imgdelete"		=> "del",
"imgdesc"		=> "desc",
"imglegend"		=> "Legend: (desc) = show/edit image description.",
"imghistory"	=> "Image history",
"revertimg"		=> "rev",
"deleteimg"		=> "del",
"imghistlegend" => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Image links",
"linkstoimage"	=> "The following pages link to this image:",
"nolinkstoimage" => "There are no pages that link to this image.",

# Statistics
#
"statistics"	=> "Statistics",
"sitestats"		=> "Site statistics",
"userstats"		=> "User statistics",
"sitestatstext" => "There are <b>$1</b> total pages in the database.
This includes \"talk\" pages, pages about Wikipedia, minimal \"stub\"
pages, redirects, and others that probably don't qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.",
"userstatstext" => "There are <b>$1</b> registered users.
<b>$2</b> of these are administrators (see $3).",

# Maintenance Page
#
"maintenance"		=> "Maintenance page",
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "Back to Maintenance Page",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"		=> "Pages with Self Links",
"selflinkstext"		=> "The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"		=> "Orphaned pages",
"lonelypages"	=> "Orphaned pages",
"unusedimages"	=> "Unused images",
"popularpages"	=> "Popular pages",
"nviews"		=> "$1 views",
"wantedpages"	=> "Wanted pages",
"nlinks"		=> "$1 links",
"allpages"		=> "All pages",
"randompage"	=> "Random page",
"shortpages"	=> "Short pages",
"longpages"		=> "Long pages",
"listusers"		=> "User list",
"specialpages"	=> "Special pages",
"spheading"		=> "Special pages",
"sysopspheading" => "Special pages for sysop use",
"developerspheading" => "Special pages for developer use",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Related changes",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "New pages",
"ancientpages"		=> "Oldest articles",
"intl"		=> "Interlanguage links",
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
"emailmessage"	=> "Message",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sent",
"emailsenttext" => "Your e-mail message has been sent.",

# Watchlist
#
"watchlist"		=> "My watchlist",
"watchlistsub"	=> "(for user \"$1\")",
"nowatchlist"	=> "You have no items on your watchlist.",
"watchnologin"	=> "Not logged in",
"watchnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist.",
"addedwatch"	=> "Added to watchlist",
"addedwatchtext" => "The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
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
"deletepage"	=> "Delete page",
"confirm"		=> "Confirm",
"excontent" => "content was:",
"exbeforeblank" => "content before blanking was:",
"exblank" => "page was empty",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"historywarning" => "Warning: The page you are about to delete has a history: ",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Wikipedia:Policy]].",
"confirmcheck"	=> "Yes, I really want to delete this.",
"actioncomplete" => "Action complete",
"deletedtext"	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
"deletedarticle" => "deleted \"$1\"",
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
"rollbackfailed" => "Rollback failed",
"cantrollback"	=> "Cannot revert edit; last contributor is only author of this article.",
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the article already. 

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
"editcomment" => "The edit comment was: \"<i>$1</i>\".", 
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
"undeletebtn" => "Restore!",
"undeletedarticle" => "restored \"$1\"",
"undeletedtext"   => "The article [[$1]] has been successfully restored.
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
"whatlinkshere"	=> "What links here",
"notargettitle" => "No target",
"notargettext"	=> "You have not specified a target page or user
to perform this function on.",
"linklistsub"	=> "(List of links)",
"linkshere"		=> "The following pages link to here:",
"nolinkshere"	=> "No pages link to here.",
"isredirect"	=> "redirect page",

# Block/unblock IP
#
"blockip"		=> "Block IP address",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address.
This should be done only only to prevent vandalism, and in
accordance with [[Wikipedia:Policy|Wikipedia policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this address",
"badipaddress"	=> "The IP address is badly formed.",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "The IP address \"$1\" has been blocked.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock IP address",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "IP address \"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses",
"blocklistline"	=> "$1, $2 blocked $3",
"blocklink"		=> "block",
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
"lockbtn"		=> "Lock database",
"unlockbtn"		=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",

"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The Wikipedia database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The Wikipedia database has been unlocked.",

# SQL query
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
Wikipedia database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlislogged"	=> "Please note that all queries are logged.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Queries other than \"SELECT\" are restricted to
Wikipedia developers.",
"querysuccessful" => "Query successful",

# Move page
#
"movepage"		=> "Move page",
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

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",
"movepagetalktext" => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",
"movearticle"	=> "Move page",
"movenologin"	=> "Not logged in",
"movenologintext" => "You must be a registered user and <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to move a page.",
"newtitle"		=> "To new title",
"movepagebtn"	=> "Move page",
"pagemovedsub"	=> "Move succeeded",
"pagemovedtext" => "Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.",
"talkexists"	=> "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.",
"movedto"		=> "moved to",
"movetalk"		=> "Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "The corresponding talk page was also moved.",
"talkpagenotmoved" => "The corresponding talk page was <strong>not</strong> moved.",

);

class LanguageHu extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsHu ;
		return $wgDefaultUserOptionsHu ;
		}

	function getBookstoreList () {
		global $wgBookstoreListHu ;
		return $wgBookstoreListHu ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesHu;
		return $wgNamespaceNamesHu;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesHu;
		return $wgNamespaceNamesHu[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesHu;

		foreach ( $wgNamespaceNamesHu as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsHu;
		return $wgQuickbarSettingsHu;
	}

	function getSkinNames() {
		global $wgSkinNamesHu;
		return $wgSkinNamesHu;
	}

	function getMathNames() {
		global $wgMathNamesHu;
		return $wgMathNamesHu;
	}
	
	function getDateFormats() {
		global $wgDateFormatsHu;
		return $wgDateFormatsHu;
	}

	function getUserToggles() {
		global $wgUserTogglesHu;
		return $wgUserTogglesHu;
	}

	/* inherit common getLanguageNames() */

	function getMonthName( $key )
	{
		global $wgMonthNamesHu;
		return $wgMonthNamesHu[$key-1];
	}
	
	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsHu;
		return $wgMonthAbbreviationsHu[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesHu;
		return $wgWeekdayNamesHu[$key-1];
	}
 
	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesHu;
		return $wgValidSpecialPagesHu;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesHu;
		return $wgSysopSpecialPagesHu;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesHu;
		return $wgDeveloperSpecialPagesHu;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesHu;
		if(array_key_exists($key, $wgAllMessagesHu))
			return $wgAllMessagesHu[$key];
		else
			return Language::getMessage($key);
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;
		
		# Check for non-UTF-8 URLs; assume they are 8859-2
	        $ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( $ishigh and !$isutf )
			return iconv( "iso8859-2", "utf-8", $s );
		
		return $s;
	}

	
}

?>
