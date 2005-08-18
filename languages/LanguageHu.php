<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Hungarian localisation for MediaWiki
#

require_once("LanguageUtf8.php");

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

# suffixed project name (Wikipédia -> Wikipédiá) -- ról, ba, k
$wgSitenameROL = $wgSitename . "ról";
$wgSitenameBA = $wgSitename . "ba";
$wgSitenameK = $wgSitename . "k";
if( 0 == strcasecmp( "Wikipédia", $wgSitename ) ) {
	$wgSitenameROL = "Wikipédiáról";
	$wgSitenameBA  = "Wikipédiába";
	$wgSitenameK   = "Wikipédiák";

} elseif( 0 == strcasecmp( "Wikidézet", $wgSitename ) ) {
	$wgSitenameROL = "Wikidézetről";
	$wgSitenameBA  = "Wikidézetbe";
	$wgSitenameK   = "Wikidézetek";

} elseif( 0 == strcasecmp( "Wikiszótár", $wgSitename ) ) {
	$wgSitenameROL = "Wikiszótárról";
	$wgSitenameBA  = "Wikiszótárba";
	$wgSitenameK   = "Wikiszótárak";

} elseif( 0 == strcasecmp( "Wikikönyvek", $wgSitename ) ) {
	$wgSitenameROL = "Wikikönyvekről";
	$wgSitenameBA  = "Wikikönyvekbe";
	$wgSitenameK   = "Wikikönyvek";
}

/* private */ $wgNamespaceNamesHu = array(
	NS_MEDIA			=> "Média",
	NS_SPECIAL			=> "Speciális",
	NS_MAIN				=> "",
	NS_TALK				=> "Vita",
	NS_USER				=> "User",
	NS_USER_TALK		=> "User_vita",
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace . "_vita",
	NS_IMAGE			=> "Kép",
	NS_IMAGE_TALK		=> "Kép_vita",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK 	=> "MediaWiki_vita",
	NS_TEMPLATE			=> "Sablon",
	NS_TEMPLATE_TALK 	=> "Sablon_vita",
	NS_HELP				=> "Segítség",
	NS_HELP_TALK		=> "Segítség_vita",
	NS_CATEGORY			=> "Kategória",
	NS_CATEGORY_TALK	=> "Kategória_vita"
) + $wgNamespaceNamesEn;

/* Inherit default options; make specific changes via
   custom getDefaultUserOptions() if needed. */

/* private */ $wgQuickbarSettingsHu = array(
	"Nincs", "Fix baloldali", "Fix jobboldali", "Lebegő baloldali"
);

/* private */ $wgSkinNamesHu = array(
	'standard' => "Alap",
	'nostalgia' => "Nosztalgia",
	'cologneblue' => "Kölni kék"
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsHu = array(
#	"Mindegy",
);

/* private */ $wgAllMessagesHu = array(

# User Toggles
#

"tog-underline" => "Linkek aláhúzása",
"tog-highlightbroken" => "Törött linkek <a href=\"\" class=\"new\">így</a> (alternatíva: így<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Bekezdések teljes szélességű tördelése",
"tog-hideminor" => "Apró változtatások elrejtése a recent changes-ben",
"tog-usenewrc" => "Modern változások listája (nem minden böngészőre)",
"tog-numberheadings" => "Címsorok automatikus számozása",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editsection"=>"Linkek az egyes szakaszok szerkesztéséhez",
"tog-editsectiononrightclick"=>"Egyes szakaszok szerkesztése a szakaszcímre klikkeléssel (Javascript)",
"tog-showtoc"=>"Három fejezetnél többel rendelkező cikkeknél mutasson tartalomjegyzéket",
"tog-rememberpassword" => "Jelszó megjegyzése a használatok között",
"tog-editwidth" => "Teljes szélességű szerkesztőterület",
"tog-editondblclick" => "Lapon duplakattintásra szerkesztés (JavaScript)",
"tog-watchdefault" => "Figyelje az új és a megváltoztatott cikkeket",
"tog-minordefault" => "Alapból minden szerkesztést jelöljön aprónak",
"tog-previewontop" => "Előnézet a szerkesztőterület előtt és nem utána",
"tog-nocache" => "Lapok gyorstárazásának letiltása",


# Dates
#

'sunday' => "vasárnap",
'monday' => "hétfő",
'tuesday' => "kedd",
'wednesday' => "szerda",
'thursday' => "csütörtök",
'friday' => "péntek",
'saturday' => "szombat",
'january' => "január",
'february' => "február",
'march' => "március",
'april' => "április",
'may_long' => "május",
'june' => "június",
'july' => "július",
'august' => "augusztus",
'september' => "szeptember",
'october' => "október",
'november' => "november",
'december' => "december",
'jan' => "Jan",
'feb' => "Feb",
'mar' => "Már",
'apr' => "Ápr",
'may' => "Máj",
'jun' => "Jún",
'jul' => "Júl",
'aug' => "Aug",
'sep' => "Sep",
'oct' => "Okt",
'nov' => "Nov",
'dec' => "Dec",

# Bits of text used by many pages:
#
"categories" 	=> "Lapkategóriák",
"category" 		=> "kategória",
"category_header" => "Cikkek a(z) \"$1\" kategóriában",
"subcategories" => "Alkategóriák",
"linktrail"		=> "/^((?:[a-z]|á|é|í|ú|ó|ö|ü|ő|ű|Á|É|Í|Ó|Ú|Ö|Ü|Ő|Ű)+)(.*)\$/sD",
"mainpage"		=> "Kezdőlap",
"mainpagetext"	=> "Wiki szoftver sikeresen telepítve.",
"about"			=> "Névjegy",
"aboutsite"      => "A {{SITENAME}}ROL",
"aboutpage"		=> "{{��ns:project}}}:Névjegy",
"help"			=> "Segítség",
"helppage"		=> "{{��ns:project}}}:Segítség",
"bugreports"	=> "Hibajelentés",
"bugreportspage" => "{{��ns:project}}}:Hibajelentések",
"faq"			=> "GyIK",
"faqpage"		=> "{{��ns:project}}}:GyIK",
"edithelp"		=> "Segítség a szerkesztéshez",
"edithelppage"	=> "{{��ns:project}}}:Hogyan_szerkessz_egy_lapot",
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
"tagline"      	=> "A Wikipediából, a szabad enciklopédiából.",
"whatlinkshere"	=> "Lapok, melyek ide mutatnak",
"help"			=> "Segítség",
"search"		=> "Keresés",
"go"			=> "Menj!",
"history"		=> "Laptörténet",
"printableversion" => "Nyomtatható változat",
"editthispage"	=> "Szerkeszd ezt a lapot",
"deletethispage" => "Lap törlése",
"protectthispage" => "Védelem a lapnak",
"unprotectthispage" => "Védelem megszüntetése",
"newpage" 		=> "Új lap",
"talkpage"		=> "Lap megbeszélése",
"postcomment"	=> "Üzenethagyás",
"articlepage"	=> "Szócikk megtekintése",
"subjectpage"	=> "Témalap megtekintése", # For compatibility
"userpage" 		=> "Felhasználói lap",
"wikipediapage" => "Metalap",
"imagepage" 	=> "Képlap",
"viewtalkpage"	=> "Beszélgetés megtekintése",
"otherlanguages" => "Egyéb nyelvek",
"redirectedfrom" => "(Átirányítva $1 cikkből)",
"lastmodified"	=> "A lap utolsó módosítása $1.",
"viewcount"		=> "Ezt a lapot eddig $1 alkalommal látogatták.",
"printsubtitle" => "(From {{SERVER}})",
"protectedpage" => "Védett lap",
"administrators" => "{{ns:project}}:Adminisztrátorok",
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
"sitetitle"		=> "{{SITENAME}}",
"sitesubtitle"	=> "A szabad enciklopédia",
"retrievedfrom" => "Retrieved from \"$1\"",
"newmessages" 	=> "$1 van.",
"newmessageslink" => "Új üzeneted",
"editsection"	=> "szerkesztés",
"toc" 			=> "Tartalomjegyzék",
"showtoc" 		=> "mutat",
"hidetoc" 		=> "elrejt",
"thisisdeleted" => "$1 megnézése vagy helyreállítása?",
"restorelink" 	=> "$1 törölt szerkesztés",

# Main script and global functions
#
"nosuchaction"	=> "Nincs ilyen tevékenység",
"nosuchactiontext" => "Az URL által megadott tevékenységet a {{SITENAME}}
software nem ismeri fel",
"nosuchspecialpage" => "Nincs ilyen speciális lap",
"nospecialpagetext" => "Olyan speciális lapot kértél amit a {{SITENAME}}
software nem ismer fel.",

# General errors
#
"error"	=> "Hiba",
"databaseerror" => "Adatbázis hiba",
"dberrortext"	=> "Adatbázis formai hiba történt.
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
"cachederror"	=> "Ez a kért cikk egy régebben elmentett példánya, lehetséges, hogy nem tartalmazza a legújabb módosításokat.",
"readonly"		=> "Adatbázis lezárva",
"enterlockreason" => "Add meg a lezárás indoklását valamint egy becslést,
hogy mikor kerül a lezárás feloldásra",
"readonlytext"	=> "A {{SITENAME}} adatbázis jelenleg le van zárva az új
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
"perfdisabled" 	=> "Bocsánat! Ez a lehetőség időszakosan nem elérhető
mert annyira lelassítja az adatbázist, hogy senki nem tudja a
wikit használni.",
"perfdisabledsub" => "Íme $1 egy elmentett másolata:",

# Login and logout pages
#
"logouttitle"	=> "Kilépés",
"logouttext"	=> "Kiléptél.
Folytathatod a {{SITENAME}} használatát név nélkül, vagy beléphetsz
újra vagy másik felhasználóként.\n",

"welcomecreation" => "<h2>Üdvözöllek, $1!</h2><p>A felhasználói környezeted
létrehoztam.
Ne felejtsd el átnézni a személyes {{SITENAME}} beállításaidat.",

"loginpagetitle" => "Belépés",
"yourname"		=> "A felhasználói neved",
"yourpassword"	=> "Jelszavad",
"yourpasswordagain" => "Jelszavad ismét",
"newusersonly"	=> " (csak új felhasználóknak)",
"remembermypassword" => "Jelszó megjegyzése a használatok között.",
"loginproblem"	=> "<b>Valami probléma van a belépéseddel.</b><br />Kérlek próbáld ismét!",
"alreadyloggedin" => "<strong>Kedves $1, már be vagy lépve!</strong><br />\n",

"login"			=> "Belépés",
"userlogin"		=> "Belépés",
"logout"		=> "Kilépés",
"userlogout"	=> "Kilépés",
"notloggedin"	=> "Nincs belépve",
"createaccount"	=> "Új felhasználó készítése",
"createaccountmail"	=> "eMail alapján", /* FIXME??? */
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
"loginsuccess"	=> "Beléptél a {{SITENAME}}BA \"$1\"-ként.",
"nosuchuser"	=> "Nincs olyan felhasználó hogy \"$1\".
Ellenőrizd a gépelést, vagy készíts új nevet a fent látható űrlappal.",
"wrongpassword"	=> "A megadott jelszó helytelen.",
"mailmypassword" => "Küldd el nekem a jelszavamat emailben",
"passwordremindertitle" => "{{SITENAME}} jelszó emlékeztető",
"passwordremindertext" => "Valaki (vélhetően te, a $1 IP számról)
azt kérte, hogy küldjük el a jelszavadat.
A jelszavad a \"$2\" felhasználóhoz most \"$3\".
Lépj be, és változtasd meg a jelszavad.",
"noemail"	=> "Nincs a \"$1\" felhasználóhoz email felvéve.",
"passwordsent"	=> "Az új jelszót elküldtük \"$1\" email címére.
Lépj be a levélben található adatokkal.",

# Edit pages
#
"summary"		=> "Összefoglaló",
"subject"		=> "Téma/főcím",
"minoredit"		=> "Ez egy apró változtatás",
"watchthis"		=> "Figyeld a szócikket",
"savearticle"	=> "Lap mentése",
"preview"		=> "Előnézet",
"showpreview"	=> "Előnézet megtekintése",
"blockedtitle"	=> "A felhasználó le van tiltva",
"blockedtext"	=> "A felhasználói neved vagy IP számod $1 letiltotta.
Az indoklás:<br />''$2''
<p>Felveheted a kapcsolatot $1 adminnal vagy bármely más
[[{{��ns:project}}}:adminisztrátorok|adminisztrátorral]] hogy megvitasd a letiltást.",
"whitelistedittitle" => "A szerkesztéshez be kell lépned",
"whitelistedittext" => "A szócikkek szerkesztéséhez [[Special:Userlogin|be kell lépned]].",
"whitelistreadtitle" => "Az olvasáshoz be kell lépned",
"whitelistreadtext" => "[[Special:Userlogin|Be kell lépned]] ahhoz, hogy cikkeket tudj olvasni.",
"whitelistacctitle" => "Nem készíthetsz új bejelentkezési kódot",
"whitelistacctext" => "Ahhoz, hogy ezen a Wikin új nevet regisztrálj [[Special:Userlogin|be kell lépned]] a szükséges engedélyszinttel.",
"accmailtitle"	=> "Jelszó elküldve.",
"accmailtext"	=> "'$1' jelszavát elküldtük $2 címre.",
"newarticle"	=> "(Új)",
"newarticletext" =>
"Egy olyan lapra jutottál ami még nem létezik.
A lap létrehozásához kezdd el írni a szövegét lenti keretbe
(a [[{{��ns:project}}}:Segítség|segítség]] lapon lelsz további
információkat).
Ha tévedésből jöttél ide, csak nyomd meg a böngésző '''Vissza/Back'''
gombját.",
"anontalkpagetext" => "
---- ''Ez egy olyan anonim felhasználó vitalapja, aki még nem készített magának nevet vagy azt nem használta. Ezért az [[IP szám]]át használjuk az azonosítására. Az IP számokon számos felhasználó osztozhat az idők folyamán. Ha anonim felhasználó vagy és úgy érzed, hogy értelmetlen megjegyzéseket írnak neked akkor [[Speciális:Belépés|készíts magadnak egy nevet vagy lépj be]] hogy megakadályozd más anonim felhasználókkal való keveredést.'' ",
"noarticletext" => "(Ez a lap jelenleg nem tartalmaz szöveget)",
"updated"		=> "(Frissítve)",
"note"			=> "<strong>Megjegyzés:</strong> ",
"previewnote"	=> "Ne felejtsd el, hogy ez csak egy előnézet, és nincs elmentve!",
"previewconflict" => "Ez az előnézet a felső szerkesztőablakban levő
szövegnek megfelelő képet mutatja, ahogy az elmentés után kinézne.",
"editing"		=> "$1 szerkesztés alatt",
"editingsection"	=> "$1 szerkesztés alatt (szakasz)",
"editingcomment"	=> "$1 szerkesztés alatt (üzenet)",
"editconflict"	=> "Szerkesztési ütközés: $1",
"explainconflict" => "Valaki megváltoztatta a lapot azóta,
mióta szerkeszteni kezdted.
A felső szövegablak tartalmazza a szöveget, ahogy az jelenleg létezik.
A módosításaid az alsó ablakban láthatóak.
Át kell vezetned a módosításaidat a felső szövegbe.
<b>Csak</b> a felső ablakban levő szöveg kerül elmentésre akkor, mikor
a \"Lap mentését\" választod.<br />",
"yourtext"		=> "A te szöveged",
"storedversion" => "A tárolt változat",
"editingold"	=> "<strong>VIGYÁZAT! A lap egy elavult
változatát szerkeszted.
Ha elmented, akkor az ezen változat után végzett összes
módosítás elvész.</strong>",
"yourdiff"		=> "Eltérések",
"copyrightwarning" => "Kérlek vedd figyelembe hogy minden
{{SITENAME}}BA küldött anyag a GNU Szabad Dokumentum Licenc alatti
publikálásnak számít (lásd $1 a részletekért).
Ha nem akarod, hogy az írásod könyörtelenül módosíthassák vagy
tetszés szerint terjesszék, akkor ne küldd be ide.<br />
A beküldéssel együtt azt is garantálod hogy mindezt saját
magad írtad, vagy másoltad be egy szabadon elérhető vagy
közkincs (public domain) forrásból.
<strong>ENGEDÉLY NÉLKÜL NE KÜLDJ BE JOGVÉDETT ANYAGOKAT!</strong>",
"longpagewarning" => "<strong>FIGYELEM: Ez a lap $1 kilobyte hosszú;
néhány böngészőnek problémái vannak a 32KB körüli vagy nagyobb lapok
szerkesztésével.
Fontold meg a lap kisebb szakaszokra bontását.</strong>",
"readonlywarning" => "<strong>FIGYELEM: Az adatbázis karbantartás miatt le van zárva,
ezért a módosításaidat most nem lehetséges elmenteni. Érdemes a szöveget
kimásolni és elmenteni egy szövegszerkesztőben a későbbi mentéshez.</strong>",
"protectedpagewarning" => "<strong>FIGYELEM: A lap lezárásra került és ilyenkor
csak a Sysop jogú adminisztrátorok tudják szerkeszteni. Ellenőrizd, hogy
betartod a [[Project:Zárt_lapok_irányelve|zárt lapok irányelvét]].</strong>",

# History pages
#
"revhistory"	=> "Változások története",
"nohistory"		=> "Nincs szerkesztési történet ehhez a laphoz.",
"revnotfound"	=> "A változat nem található",
"revnotfoundtext" => "A lap általad kért régi változatát nem találom.
Kérlek ellenőrizd az URL-t amivel erre a lapra jutottál.\n",
"loadhist"		=> "Laptörténet beolvasása",
"currentrev"	=> "Aktuális változat",
"revisionasof"	=> "$1 változat",
"cur"			=> "akt",
"next"			=> "köv",
"last"			=> "előző",
"orig"			=> "eredeti",
"histlegend"	=> "Jelmagyarázat: (akt) = eltérés az aktuális változattól,
(előző) = eltérés az előző változattól,
Legend: (cur) = difference with current version,
A = Apró változtatás",

# Diffs
#
"difference"	=> "(Változatok közti eltérés)",
"loadingrev"	=> "különbségképzéshez olvasom a változatokat",
"lineno"		=> "Sor $1:",
"editcurrent"	=> "A lap aktuális változatának szerkesztése",

# Search results
#
"searchresults" => "A keresés eredménye",
"searchresulttext" => "További információkkal a keresésről [[Project:Keresés|Keresés a {{SITENAME}}]] szolgál.",
"searchquery"	=> "A \"$1\" kereséshez",
"badquery"		=> "Hibás formájú keresés",
"badquerytext"	=> "Nem tudjuk a kérésedet végrehajtani.
Ennek oka valószínűleg az, hogy három betűnél rövidebb
karaktersorozatra próbáltál keresni, ami jelenleg nem lehetséges.
Lehet az is hogy elgépelted a kifejezést, például \"hal and and mérleg\".
Kérlek próbálj másik kifejezést keresni.",
"matchtotals"	=> "A \"$1\" keresés $2 címszót talált és
$3 szócikk szövegét.",
"nogomatch"		=> "Nincs pontosan ezzel megegyező címszó,
próbálom a keresést a cikkek szövegében.",
"titlematches"	=> "Címszó egyezik",
"notitlematches" => "Nincs egyező címszó",
"textmatches"	=> "Szócikk szövege egyezik",
"notextmatches"	=> "Nincs szócikk szöveg egyezés",
"prevn"			=> "előző $1",
"nextn"			=> "következő $1",
"viewprevnext"	=> "Nézd ($1) ($2) ($3).",
"showingresults" => "Lent látható <b>$1</b> találat, az eleje #<b>$2</b>.",
"showingresultsnum" => "Lent látható <b>$3</b> találat, az eleje #<b>$2</b>.",
"nonefound"		=> "<strong>Megyjegyzés</strong>: a sikertelen keresések
gyakori oka olyan szavak keresése (pl. \"have\" és \"from\") amiket a
rendszer nem indexel fel, vagy több független keresési szó szerepeltetése
(csak minden megadott szót tartalmazó találatok jelennek meg a
végeredményben).",
"powersearch" => "Keresés",
"powersearchtext" => "
Keresés a névterekben:<br />
$1<br />
$2 Átirányítások listája &nbsp; Keresés:$3 $9",
"searchdisabled" => "<p>Elnézésed kérjük, de a teljes szöveges keresés terhelési okok miatt átmenetileg nem használható. Ezidő alatt használhatod a lenti Google keresést, mely viszont lehetséges, hogy nem teljesen friss adatokkal dolgozik.</p>",
"blanknamespace" => "(Alap)",

# Preferences page
#
"preferences"	=> "Beállítások",
"prefsnologin"	=> "Nem vagy belépve",
"prefsnologintext"	=> "Ahhoz hogy a
beállításaidat rögzíthesd [[Speciális:Belépés|be kell lépned]].",
"prefslogintext" => "Be vagy lépve \"$1\" néven.
A belső azonosítód $2.",
"prefsreset"	=> "A beállítások törlődtek a tárolóból vett értékekre.",
"qbsettings"	=> "Gyorsmenü beállítások",
"changepassword" => "Jelszó változtatása",
# látvány? bőr?!
"skin"			=> "Skin",
"math"			=> "Képletek megjelenítése",
"dateformat"	=> "Dátum formátuma",
"math_failure"	=> "Értelmezés sikertelen",
"math_unknown_error"	=> "ismertlen hiba",
"math_unknown_function"	=> "ismeretlen függvény ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "formai hiba",
"saveprefs"		=> "Beállítások mentése",
"resetprefs"	=> "Beállítások törlése",
"oldpassword"	=> "Régi jelszó",
"newpassword"	=> "Új jelszó",
"retypenew"		=> "Új jelszó mégegyszer",
"textboxsize"	=> "Szövegdoboz méretei",
"rows"			=> "Sor",
"columns"		=> "Oszlop",
"searchresultshead" => "Keresési eredmények beállításai",
"resultsperpage" => "Laponként mutatott találatok száma",
"contextlines"	=> "Találatonként mutatott sorok száma",
# FIXME, what is that?
"contextchars"	=> "Characters of context per line",
"stubthreshold" => "Csonkok kijelzésének küszöbértéke",
"recentchangescount" => "Címszavak száma a friss változtatásokban",
"savedprefs"	=> "A beállításaidat letároltam.",
"timezonetext"	=> "Add meg az órák számát, amennyivel a helyi
idő a GMT-től eltér (Magyarországon nyáron 2, télen 1).",
"localtime"		=> "Helyi idő",
"timezoneoffset" => "Eltérés",
"servertime"	=> "A server ideje most",
"guesstimezone" => "Töltse ki a böngésző",
"emailflag"		=> "Email küldés letiltása más userektől",
"defaultns"		=> "Alapértelmezésben az alábbi névterekben keressünk:",

# Recent changes  'legutóbbi változtatások', 'friss v.'
#

"changes" 		=> "változtatás",
"recentchanges" => "Friss változtatások",
"recentchangestext" => "Ezen a lapon követheted a {{SITENAME}} történt legutóbbi
változtatásokat. [[{{��ns:project}}}:Üdvözlünk_látogató|Üdvözlünk, látogató]]!
Légy szíves ismerkedj meg az alábbi lapokkal: [[{{��ns:project}}}:GyIK|{{SITENAME}} GyIK]],
[[{{��ns:project}}}:Irányelvek]] (különösen az [[{{��ns:project}}}:Elnevezési szokások|elnevezési szokásokat]],
a [[{{��ns:project}}}:Semleges nézőpont|semleges nézőpontot]]), és a
[[{{��ns:project}}}:Legelterjedtebb baklövések|legelterjedtebb baklövéseket]].
Ha azt szeretnéd hogy a Wikipedia sikeres legyen akkor nagyon fontos, hogy
soha ne add hozzá mások [[{{��ns:project}}}:Copyright|jogvédett és nem felhasználható]]
anyagait.
A jogi problémák komolyan árthatnak a projektnek ezért kérünk arra, hogy ne tegyél
ilyet.",
"rcloaderr"		=> "Friss változtatások betöltése",
"rcnote"		=> "Lentebb az utolsó <strong>$2</strong> nap <strong>$1</strong> változtatása látható.",
"rcnotefrom"	=> "Lentebb láthatóak a <b>$2</b> óta történt változások (<b>$1</b>-ig).",
"rclistfrom"	=> "Az új változtatások kijelzése $1 után",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
# azért kell a 'db' mert ha nincs egy sem, akkor üres $3, és hülyén néz ki.
"rclinks"		=> "Az utolsó $1 változtatás látszik az elmúlt $2 napon; $3 db apró módosítással",
"rchide"		=> "$4 formában; $1 apró módosítás; $2 másodlagos névtér; $3 többszörös módosítás.",
"rcliu"			=> "; $1 módosítás ismert userektől",
"diff"			=> "eltér",
"hist"			=> "történet",
"hide"			=> "rejt",
"show"			=> "mutat",
"tableform"		=> "tábla",
"listform"		=> "lista",
"nchanges"		=> "$1 módosítás",
"minoreditletter" => "A",
"newpageletter" => "Ú",

# Upload
#
"upload"		=> "File felküldése",
"uploadbtn"		=> "File felküldés",
"uploadlink"	=> "Kép felküldése",
"reupload"		=> "Újraküldés",
"reuploaddesc"	=> "Visszatérés a felküldési űrlaphoz.",
"uploadnologin" => "Nincs belépve",
"uploadnologintext"	=> "Ahhoz hogy file-okat tudj
felküldeni [[Speciális:Belépés|logged in]]
to upload files.",
"uploaderror"	=> "Felküldési hiba",
"uploadtext"	=> "'''ÁLLJ!''' Mielőtt bármit felküldesz ide
győződj meg róla hogy elolvastad és követed a
[[Project:Képhasználati_irányelvek|képhasználati irányelveket]].

A régebben felküldött képek megnézéséhez vagy kereséséhez
nézd meg a [[Speciális:Képlista|felküldött képek listáját]].
A felküldések és törlések naplója a
[[Project:Upload_log|felküldési naplóban]] található.

Az alábbi űrlapot használd a cikkeidet illusztráló új kép felküldéséhez.
A legtöbb büngészőben látsz egy \"Böngészés...\" (Browse) gombot
aminek segítségével a rendszered file-jai között keresgélhetsz.

A file-t kiválasztva az bekerül a gomb melletti mezőbe.
Ezután be kell jelölnöd a kis pipát amivel igazolod hogy a felküldéssel
nem sértesz meg semmilyen szerzői jogot.
A \"Felküldés\" gombbal fejezheted be a küldést.
Ez lassú internet kapcsolat esetén eltarthat egy kis ideig.

A javasolt formátumok JPG a fotókhoz, PNG a rajzokhoz és
ikon jellegű képekhez és OGG a hanganyagokhoz.
Kérünk arra, hogy a file-jaidnak jellemző, beszélő nevet adj hogy
elkerüld a félreértéseket. A képet a cikkbe a
'''<nowiki>[[kép:file.jpg]]</nowiki>''' vagy
'''<nowiki>[[kép:file.png|leírás]]</nowiki>'''
formában használhatod és '''<nowiki>[[media:file.ogg]]</nowiki>''' formában
a hanganyagokat.

Kérünk hogy vedd figyelembe azt, hogy mint minden {{SITENAME}}
lap esetében bárki szerkesztheti vagy törölheti a felküldésedet
ha úgy ítéli meg, hogy az hasznos a lexikonnak, vagy letiltásra
kerülhetsz a felküldési lehetőségről ha visszaélsz a rendszerrel.",
"uploadlog"		=> "felküldési napló",
"uploadlogpage" => "Felküldési_napló",
"uploadlogpagetext" => "Lentebb látható a legutóbbi felküldések listája.
Minden időpont a server idejében (UTC) van megadva.
<ul>
</ul>
",
"filename"		=> "Filenév",
"filedesc"		=> "Összefoglaló",
"filestatus" 	=> "Szerzői jogi állapot",
"filesource" 	=> "Forrás",
"copyrightpage" => "{{��ns:project}}}:Copyright",
"copyrightpagename" => "{{SITENAME}} copyright",
"uploadedfiles"	=> "Felküldött file-ok",
"ignorewarning"	=> "Mentés a figyelmeztetés figyelmen kívül hagyásával.",
"minlength"		=> "A kép nevének legalább három betűből kell állnia.",
"badfilename"	=> "A kép új neve \"$1\".",
"badfiletype"	=> "\".$1\" nem javasolt képformátumnak.",
"largefile"		=> "Javasolt hogy a képek mérete ne haladja meg a 100 kilobyte-ot.",
"successfulupload" => "Sikeresen felküldve",
"fileuploaded"	=> "A \"$1\" file felküldése sikeres volt.
kérlek a ($2) linken add meg a file leírását és az információkat a
file-ról, mint például hogy honnan való, mikor és ki készítette, vagy bármi
más információ amit meg tudsz adni.",
"uploadwarning" => "Felküldési figyelmeztetés",
"savefile"		=> "File mentése",
"uploadedimage" => "\"[[$1]]\" felküldve",

# Image list
#
"imagelist"		=> "Képlista",
"imagelisttext"	=> "Lentebb látható $1 $2 rendezett kép.",
"getimagelist"	=> "képlista lehívása",
"ilsubmit"		=> "Keresés",
"showlast"		=> "Az utolsó $1 kép $2.",
"byname"		=> "név szerint",
"bydate"		=> "dátum szerint",
"bysize"		=> "méret szerint",
"imgdelete"		=> "töröl",
"imgdesc"		=> "leírás",
"imglegend"		=> "Jelmagyarázat: (leírás) = kép leírás megtekintés/szerkesztés.",
"imghistory"	=> "Kép története",
"revertimg"		=> "régi",
"deleteimg"		=> "töröl",
"deleteimgcompletely"		=> "töröl",
"imghistlegend" => "Jelmagyarázat: (akt) = ez az aktuális kép,
(töröl) = ezen régi változat törlése,
(régi) = visszaállás erre a régi változatra.
<br /><i>Klikkelj a dátumra hogy megnézhesd az akkor felküldött képet</i>.",
"imagelinks"	=> "Kép hivatkozások",
"linkstoimage"	=> "Az alábbi lapok hivatkoznak erre a képre:",
"nolinkstoimage" => "Erre a képre nem hivatkozik lap.",

# Statistics
#
"statistics"	=> "Statisztika",
"sitestats"		=> "Server statisztika",
"userstats"		=> "User statisztika",
"sitestatstext" => "Az adatbázisban összesen <b>$1</b> lap található.
Ebben benne vannak a \"vita\" lapok, a {{SITENAME}}ROL szóló lapok, a
minimális \"csonk\" lapok, átirányítások és hasonlók amik vélhetően nem
számítanak igazi szócikkeknek.
Ezeket nem számítva <b>$2</b> lapunk van ami valószínűleg igazi szócikknek
számít.<p>
A magyar {{SITENAME}} indítása óta (2003 júl 8) <b>$3</b> alkalommal néztek meg
lapokat a rendszeren, és <b>$4</b> alkalommal szerkesztett valaki lapot.
Ezek alapján átlagosan egy lapot <b>$5</b> alkalommal szerkesztettek, és
szerkesztésenként <b>$6</b> alkalommal nézték meg.",
"userstatstext" => "Jelenleg <b>$1</b> regisztrált felhasználónk van.
Ebből <b>$2</b> darab adminisztrátor (lásd $3).",

# Maintenance Page
#
"maintenance"	=> "Karbantartás",
"maintnancepagetext" => "Ezen a lapon a mindennapi karbantartáshoz hasznos dologkat lelsz. Mivel ezek az adatbázist a szokásosnál jobban terhelik kérlek ne nyomj minden kijavított cikk után reloadot ;-)",
"maintenancebacklink" => "Vissza a karbantartás lapra",
"disambiguations" => "Egyértelműsítő lapok",
"disambiguationspage" => "{{ns:project}}:Egyértelműsítő lapok",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as dismbiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br />\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"	=> "Pages with Self Links",
"selflinkstext"	=> "The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"	=> "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"	=> "{{ns:project}}:Gyakori elírások listája",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"		=> "Árva lapok",
"lonelypages"	=> "Árva lapok",
"unusedimages"	=> "Nem használt képek",
"popularpages"	=> "Népszerű lapok",
"nviews"		=> "$1 megnézés",
# FIXME
"wantedpages"	=> "Wanted pages",
"nlinks"		=> "$1 link",
"allpages"		=> "Minden lap",
"randompage"	=> "Lap találomra",
"shortpages"	=> "Rövid lapok",
"longpages"		=> "Hosszú lapok",
"listusers"		=> "Felhasználók",
"specialpages"	=> "Speciális lapok",
"spheading"		=> "Speciális lapok",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Kapcsolódó változtatások",
# FIXME: possible context?
"rclsub"		=> "(a \"$1\" lapról hivatkozott lapok)",
"debug"			=> "Debug",
"newpages"		=> "Új lapok",
"ancientpages"	=> "Ősi szócikkek",
"intl"			=> "Nyelvek közötti linkek",
"movethispage"	=> "Mozgasd ezt a lapot",
"unusedimagestext" => "<p>Vedd figyelembe azt hogy más
lapok - mint például a nemzetközi {{SITENAME}}K - közvetlenül
hivatkozhatnak egy file URL-jére, ezért szerepelhet itt annak
ellenére hogy aktívan használják.",
"booksources"	=> "Könyvforrások",
"booksourcetext" => "Lentebb néhány hivatkozás található olyan lapokra,
ahol új vagy használt könyveket árusítanak, vagy további információkkal
szolgálhatnak az általad vizsgált könyvről.
A {{SITENAME}} semmilyen módon nem áll kapcsolatba ezen cégekkel és
ezt a listát semmiképpen ne tekintsd bármiféle ajánlásnak.",
# FIXME: huh?
"alphaindexline" => "$1 -> $2",

# Email this user
#
"mailnologin"	=> "Nincs feladó",
"mailnologintext" => "Ahhoz hogy másoknak emailt küldhess
[[Speciális:Belépés|be kell jelentkezned]]
és meg kell adnod egy érvényes email címet a [[Speciális:Beállítások|beállításaidban]].",
"emailuser"		=> "E-mail küldése ezen felhasználónak",
"emailpage"		=> "E-mail küldése",
"emailpagetext"	=> "Ha ez a felhasználó megadott egy érvényes email
címet, akokr ezen űrlap segítségével tudsz neki emailt küldeni.
Az általad a beállításaid között megadott email címed fog feladóként
szerepelni, hogy a címzett válaszolni tudjon.",
"noemailtitle"	=> "Nincs email cím",
"noemailtext"	=> "Ez a felhasználó nem adott meg email címet, vagy
nem kíván másoktól leveleket kapni.",
"emailfrom"		=> "Feladó",
"emailto"		=> "Cím",
"emailsubject"	=> "Téma",
"emailmessage"	=> "Üzenet",
"emailsend"		=> "Küldés",
"emailsent"		=> "E-mail elküldve",
"emailsenttext" => "Az email üzenetedet elküldtem.",

# Watchlist
#
"watchlist"		=> "Figyelőlistám",
"watchlistsub"	=> "(\"$1\" user)",
"nowatchlist"	=> "Nincs lap a figyelőlistádon.",
"watchnologin"	=> "Nincs belépve",
"watchnologintext"	=> "Ahhoz hogy figyelőlistád lehessen [[Speciál:Belépés|be kell lépned]].",
"addedwatch"	=> "Figyelőlistához hozzáfűzve",
"addedwatchtext" => "A \"$1\" lapot hozzáadtam a <a href=\"" .
  "{{localurle:Speciális:Figyelőlista}}\">figyelőlistádhoz</a>.
Ezután minden a lapon vagy annak vitalapján történő változást ott fogsz
látni, és a lap <b>vastagon</b> fog szerepelni a <a href=\"" .
  "{{localurle:Speciális:Friss_változtatások}}\">friss változtatások</a>
lapon, hogy könnyen észrevehető legyen.</p>

<p>Ha később el akarod távolítani a lapot a figyelőlistádról, akkor ezt az
oldalmenü \"Figyelés vége\" pontjával teheted meg.",
"removedwatch"	=> "Figyelőlistáról eltávolítva",
"removedwatchtext" => "A \"$1\" lapot eltávolítottam a figyelőlistáról.",
"watchthispage"	=> "Lap figyelése",
"unwatchthispage" => "Figyelés vége",
"notanarticle"	=> "Nem szócikk",
"watchdetails" => "($1 lap figyelése a vitalapokon kívül,
$2 lap változott az adott határokon belül,
$3...
[$4 teljes lista áttekintés és szerkesztés].)",
"watchmethod-recent" => "a figyelt lapokon belüli legfrissebb szerkesztések",
"watchmethod-list" => "a legfrissebb szerkesztésekben található figyelt lapok",
"removechecked" => "A kijelölt lapok eltávolítása a figyelésből",
"watchlistcontains" => "A figyelőlistád $1 lapot tartalmaz.",
"watcheditlist" => "Íme a figyelőlistádban található lapok
betűrendes listája. Jelöld ki azokat a lapokat, amiket el
szeretnél távolítani, és válaszd a 'Kijelöltek eltávolítása'
gombot a lap alján.",
"removingchecked" => "A kért lapok eltávolítása a figyelőlistáról...",
"couldntremove" => "'$1' nem távolítható el...",
"iteminvalidname" => "Probléma a '$1' elemmel: érvénytelen név...",
"wlnote" => "Lentebb az utolsó <b>$2</b> óra $1 változtatása látható.",
"wlshowlast" => "Módosítások az utolsó $1 órában $2 napon $3",

# Delete/protect/revert
#
"deletepage"	=> "Delete page",
"confirm"		=> "Confirm",
"excontent" 	=> "content was: '$1'",
"exbeforeblank" => "content before blanking was: '$1'",
"exblank"		=> "page was empty",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"historywarning" => "Warning: The page you are about to delete has a history: ",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Wikipedia:Policy]].",
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
"contributions"	=> "User közreműködései",
"mycontris" => "Közreműködéseim",
"contribsub"	=> "$1 cikkhez",
"nocontribs"	=> "Nem találtam a feltételnek megfelelő módosítást.",
"ucnote"		=> "Lentebb <b>$1</b> módosításai láthatóak az elmúlt <b>$2</b> napban.",
"uclinks"		=> "View the last $1 changes; view the last $2 days.",
"uctop"			=> " (top)",

# What links here
#
"whatlinkshere"	=> "Mi hivatkozik erre",
"notargettitle" => "Nincs cél",
"notargettext"	=> "Nem adtál meg lapot vagy usert keresési célpontnak.",
"linklistsub"	=> "(Linkek )",
"linkshere"		=> "Az alábbi lapok hivatkoznak erre:",
"nolinkshere"	=> "Erre a lapra senki nem hivatkozik.",
"isredirect"	=> "átirányítás",

# Block/unblock IP
#
#FIXME:
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
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "The IP address \"$1\" has been blocked.
<br />See [[Speciális:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock IP address",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "IP address \"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses",
"blocklistline"	=> "$1, $2 blocked $3 ($4)",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",
"autoblocker"	=> "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
"blocklogpage"	=> "Block_log",
"blocklogentry"	=> 'blocked "$1"',
"blocklogtext"	=> "This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
"unblocklogentry"	=> 'unblocked "$1"',

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
<br />Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The Wikipedia database has been unlocked.",

# Move page
#
"movepage"		=> "Lap mozgatása",
"movepagetext"	=> "A lentebb található űrlap segítségével legetséges
egy lapot átnevezni, és átmozgatni a teljes történetével együtt egy
új névre.
A régi név átirányítássá válik az új címszóra.
A régi címszóra hivatkozások nem változnak meg;
[[Speciális:Karbantartás|győződj meg arról]] hogy nem hagysz
magad után a régi címszóra hivatkozó linkeket. A te feladatod
biztosítani hogy a linkek oda mutassanak, ahova kell nekik.

Vedd figyelembe azt hogy az átnevezés '''nem''' történik meg
akkorr, ha már létezik olyan nevű lap, kivéve ha az üres,
átirányítás vagy nincs szerkesztési története. Ez azt jelenti
hogy vissza tudsz nevezni egy tévedésből átnevezett lapot, de
nem tudsz egy már létező aktív lapot felülírni.

<b>FIGYELEM!</b>
Egy népszerű lap esetén ez egy drasztikus és váratlan változás;
mielőtt átnevezel valamit győződj meg arról hogy tudatában vagy a következményeknek.",
"movepagetalktext" => "A laphoz tartozó vitalap automatikusan átneveződik '''kivéve ha:'''
* A lapot névterek között mozgatod át,
* Már létezik egy nem üres vitalap az új helyen,
* Nem jelölöd be a lenti pipát.

Ezen esetekben a vita lapot külön, kézzel kell átnevezned a kívánságaid
szerint.",
"movearticle"	=> "Lap mozgatás",
"movenologin"	=> "Nincs belépve",
"movenologintext" => "Ahhoz hogy mozgass egy lapot [[Speciális:Belépés|be kell lépned]].",
"newtitle"		=> "Az új névre",
"movepagebtn"	=> "Lap mozgatása",
"pagemovedsub"	=> "Átmozgatás sikeres",
"pagemovedtext" => "A \"[[$1]]\" lapot átmozgattam a \"[[$2]]\" névre.",
"articleexists" => "Ilyen névvel már létezik lap, vagy az általad
választott név érvénytelen.
Kérlek válassz egy másik nevet.",
"talkexists"	=> "A lap átmozgatása sikerült, de a hozzá tartozó
vitalapot nem tudtam átmozgatni mert már létezik egy egyező nevű
lap az új helyen. Kérlek gondoskodj a két lap összefűzéséről.",
"movedto"		=> "átmozgatva",
"movetalk"		=> "Mozgasd a \"vita\" lapokat is ha lehetséges.",
"talkpagemoved" => "A laphoz tartozó vita lap is átmozgatásra került.",
"talkpagenotmoved" => "A laphoz tartozó vita lap <strong>nem került</strong> átmozgatásra.",

"export"		=> "Lapok exportálása",
"exporttext"	=> "Egy adott lap vagy lapcsoport szövegét és változtatásait
tudod egyfajta XML-be exportálni; ezt használhatod egy másik MediaWiki alapú
rendszerbe való importálásra, átalakításra vagy a saját szórakoztatásodra.",
"exportcuronly"	=> "Csak a legfrissebb állapot, teljes laptörténet nélkül",

# Namespace 8 related

"allmessages"	=> "All_messages",
"allmessagestext"	=> "Ez a MediaWiki: névtérben elérhető összes üzenet listája",

# Math

'mw_math_png' => "Mindig készítsen PNG-t",
'mw_math_simple' => "HTML ha nagyon egyszerű, egyébként PNG",
'mw_math_html' => "HTML ha lehetséges, egyébként PNG",
'mw_math_source' => "Hagyja TeX formában (szöveges böngészőknek)",
'mw_math_modern' => "Modern böngészőknek ajánlott beállítás",
'mw_math_mathml' => 'MathML',
);

class LanguageHu extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesHu;
		return $wgNamespaceNamesHu;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesHu;

		foreach ( $wgNamespaceNamesHu as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with old names:
		if( 0 == strcasecmp( "Special", $text ) ) { return -1; }
		if( 0 == strcasecmp( "Wikipedia", $text ) ) { return 4; }
		if( 0 == strcasecmp( "Wikipedia_talk", $text ) ) { return 5; }
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

	function getDateFormats() {
		global $wgDateFormatsHu;
		return $wgDateFormatsHu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesHu;
		if(array_key_exists($key, $wgAllMessagesHu))
			return $wgAllMessagesHu[$key];
		else
			return parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "iso8859-2";
	}

	# localised date and time
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . ". " .
		$this->getMonthName( substr( $ts, 4, 2 ) ) . " ".
			(0 + substr( $ts, 6, 2 )) . ".";
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . ", " . $this->time( $ts, $adj );
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, '.,', ',.' ) : $number;
	}

}

?>
