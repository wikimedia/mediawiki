<?php
/**
 * Magyar (Hungarian)
 *
 * @addtogroup Language
 */

$namespaceNames = array(
	NS_MEDIA          => 'Média',
	NS_SPECIAL        => 'Speciális',
	NS_MAIN           => '',
	NS_TALK           => 'Vita',
	NS_USER           => 'User',
	NS_USER_TALK      => 'User_vita',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_vita',
	NS_IMAGE          => 'Kép',
	NS_IMAGE_TALK     => 'Kép_vita',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_vita',
	NS_TEMPLATE       => 'Sablon',
	NS_TEMPLATE_TALK  => 'Sablon_vita',
	NS_HELP           => 'Segítség',
	NS_HELP_TALK      => 'Segítség_vita',
	NS_CATEGORY       => 'Kategória',
	NS_CATEGORY_TALK  => 'Kategória_vita'
);

$skinNames = array(
	'standard' => "Alap",
	'nostalgia' => "Nosztalgia",
	'cologneblue' => "Kölni kék"
);

$fallback8bitEncoding = "iso8859-2";
$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$specialPageAliases = array(
        'DoubleRedirects'           => array( 'Dupla_átirányítások' ),
        'BrokenRedirects'           => array( 'Nem_létező_lapra_mutató_átirányítások' ),
        'Disambiguations'           => array( 'Egyértelműsítő_lapok' ),
        'Userlogin'                 => array( 'Belépés' ),
        'Userlogout'                => array( 'Kilépés' ),
        'Preferences'               => array( 'Beállításaim' ),
        'Watchlist'                 => array( 'Figyelőlistám' ),
        'Recentchanges'             => array( 'Friss_változtatások' ),
        'Upload'                    => array( 'Feltöltés' ),
        'Imagelist'                 => array( 'Képlista' ),
        'Newimages'                 => array( 'Új_képek_galériája' ),
        'Listusers'                 => array( 'Felhasználók' ),
        'Statistics'                => array( 'Statisztikák' ),
        'Randompage'                => array( 'Lap_találomra' ),
        'Lonelypages'               => array( 'Magányos_lapok' ),
        'Uncategorizedpages'        => array( 'Kategorizálatlan_lapok' ),
        'Uncategorizedcategories'   => array( 'Kategorizálatlan_kategóriák'),
        'Uncategorizedimages'       => array( 'Kategorizálatlan_képek', 'Kategorizálatlan_fájlok' ),
        'Unusedcategories'          => array( 'Nem_használt_kategóriák' ),
        'Unusedimages'              => array( 'Nem_használt_képek' ),
        'Wantedpages'               => array( 'Keresett_lapok' ),
        'Wantedcategories'          => array( 'Keresett_kategóriák' ),
        'Mostlinked'                => array( 'Legtöbbet_hivatkozott_lapok' ),
        'Mostlinkedcategories'      => array( 'Legtöbbet_hivatkozott_kategóriák' ),
        'Mostcategories'            => array( 'Legtöbb_kategóriába_tartozó_lapok' ),
        'Mostimages'                => array( 'Legtöbbet_használt_képek' ),
        'Mostrevisions'             => array( 'Legtöbbet_szerkesztett_lapok' ),
        'Shortpages'                => array( 'Rövid_lapok' ),
        'Longpages'                 => array( 'Hosszú_lapok' ),
        'Newpages'                  => array( 'Új_lapok' ),
        'Ancientpages'              => array( 'Régóta_nem_változott_szócikkek' ),
        'Deadendpages'              => array( 'Zsákutcalapok' ),
        'Allpages'                  => array( 'Az_összes_lap_listája' ),
        'Prefixindex'               => array( 'Egy_névtérbe_tartozó_lapok_listája', 'Az_összes_lap_listája' ) ,
        'Ipblocklist'               => array( 'Blokkolt_IP-címek_listája' ),
        'Specialpages'              => array( 'Speciális_lapok' ),
        'Contributions'             => array( 'Szerkesztő_közreműködései' ),
        'Emailuser'                 => array( 'E-mail_küldése', 'E-mail_küldése_ezen_szerkesztőnek' ),
        'Whatlinkshere'             => array( 'Mi_hivatkozik_erre' ),
        'Recentchangeslinked'       => array( 'Kapcsolódó_változtatások' ),
        'Movepage'                  => array( 'Lap_átnevezése' ),
        'Blockme'                   => array( 'Blokkolj' ),
        'Booksources'               => array( 'Könyvforrások' ),
        'Categories'                => array( 'Kategóriák' ),
        'Export'                    => array( 'Lapok_exportálása' ),
        'Version'                   => array( 'Névjegy', 'Verziószám' ),
        'Allmessages'               => array( 'Rendszerüzenetek' ),
        'Log'                       => array( 'Rendszernaplók' ),
        'Blockip'                   => array( 'Blokkolás' ),
        'Undelete'                  => array( 'Törölt_lapváltozatok_visszaállítása' ),
        'Import'                    => array( 'Lapok_importálása' ),
        'Lockdb'                    => array( 'Adatbázis_lezárása' ),
        'Unlockdb'                  => array( 'Adatbázis_lezárás_feloldása' ),
        'Userrights'                => array( 'Szerkesztői_jogok' ),
        'MIMEsearch'                => array( 'Keresés_MIME-típus_alapján' ),
        'Unwatchedpages'            => array( 'Nem_figyelt_lapok' ),
        'Listredirects'             => array( 'Átirányítások_listája' ),
        'Revisiondelete'            => array( 'Változat_törlése' ),
        'Unusedtemplates'           => array( 'Nem_használt_sablonok' ),
        'Randomredirect'            => array( 'Átirányítás_találomra' ),
        'Mypage'                    => array( 'Lapom', 'Userlapom' ),
        'Mytalk'                    => array( 'Vitám', 'Vitalapom', 'Uservitalapom' ),
        'Mycontributions'           => array( 'Közreműködéseim' ),
        'Listadmins'                => array( 'Adminisztrátorok', 'Adminisztrátorok_listája', 'Sysopok' ),
);

$datePreferences = false;
$defaultDateFormat = 'ymd';
$dateFormats = array(
	'ymd time' => 'H:i',
	'ymd date' => 'Y. F j.',
	'ymd both' => 'Y. F j., H:i',
);

$linkTrail = '/^([a-záéíóúöüőűÁÉÍÓÚÖÜŐŰ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Linkek aláhúzása:',
'tog-highlightbroken'         => 'Törött linkek <a href="" class="new">így</a> (alternatíva: így<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Bekezdések teljes szélességű tördelése („sorkizárás”)',
'tog-hideminor'               => 'Apró változtatások elrejtése a Friss változtatások lapon',
'tog-extendwatchlist'         => 'A figyelőlista kiterjesztése minden változtatásra (ne csak az utolsót mutassa)',
'tog-usenewrc'                => 'Modern változások listája (nem minden böngészőre)',
'tog-numberheadings'          => 'Címsorok automatikus számozása',
'tog-showtoolbar'             => 'Szerkesztőeszköz–sor látható',
'tog-editondblclick'          => 'Lapon duplakattintásra szerkesztés (JavaScript)',
'tog-editsection'             => 'Linkek az egyes szakaszok szerkesztéséhez',
'tog-editsectiononrightclick' => 'Egyes szakaszok szerkesztése a szakaszcímre klikkeléssel (Javascript)',
'tog-showtoc'                 => 'Három fejezetnél többel rendelkező cikkeknél mutasson tartalomjegyzéket',
'tog-rememberpassword'        => 'Jelszó megjegyzése a használatok között',
'tog-editwidth'               => 'Teljes szélességű szerkesztőterület',
'tog-watchcreations'          => 'Általad létrehozott lapok felvétele a figyelőlistádra',
'tog-watchdefault'            => 'Szerkesztett cikkek felvétele a figyelőlistára',
'tog-watchmoves'              => 'Átnevezett lapok felvétele a figyelőlistára',
'tog-watchdeletion'           => 'Törölt cikkek felvétele a figyelőlistára',
'tog-minordefault'            => 'Alapból minden szerkesztést jelöljön aprónak',
'tog-previewontop'            => 'Előnézet a szerkesztőterület előtt és nem utána',
'tog-previewonfirst'          => 'Előnézet első szerkesztésnél',
'tog-nocache'                 => 'Lapok gyorstárazásának letiltása',
'tog-shownumberswatching'     => 'Az oldalt figyelők szerkesztők számának mutatása',
'tog-fancysig'                => 'Aláírás automatikus hivatkozás nélkül',
'tog-externaleditor'          => 'Külső szerkesztőprogram alapértelmezett',
'tog-externaldiff'            => 'Külső különbségképző (diff) program használata',
'tog-showjumplinks'           => 'Helyezzen el linket („Ugrás”) a beépített eszköztárra',
'tog-forceeditsummary'        => 'Figyelmeztessen, ha nem adok meg szerkesztési összefoglalót',
'tog-watchlisthideown'        => 'Saját szerkesztések elrejtése',
'tog-watchlisthidebots'       => 'Robotok szerkesztéseinek elrejtése',
'tog-watchlisthideminor'      => 'Apró változtatások elrejtése',
'tog-ccmeonemails'            => 'A másoknak küldött e-mailekről kapjak én is egy másolatot',

'underline-always'  => 'Mindig',
'underline-never'   => 'Soha',
'underline-default' => 'A böngésző alapértelmezése szerint',

'skinpreview' => '(előnézet)',

# Dates
'sunday'       => 'vasárnap',
'monday'       => 'hétfő',
'tuesday'      => 'kedd',
'wednesday'    => 'szerda',
'thursday'     => 'csütörtök',
'friday'       => 'péntek',
'saturday'     => 'szombat',
'sun'          => 'Vas',
'mon'          => 'Hét',
'tue'          => 'Kedd',
'wed'          => 'Sze',
'thu'          => 'Csü',
'fri'          => 'péntek',
'sat'          => 'Szo',
'january'      => 'január',
'february'     => 'február',
'march'        => 'március',
'april'        => 'április',
'may_long'     => 'május',
'june'         => 'június',
'july'         => 'július',
'august'       => 'augusztus',
'september'    => 'szeptember',
'october'      => 'október',
'november'     => 'november',
'december'     => 'december',
'january-gen'  => 'január',
'february-gen' => 'Február',
'april-gen'    => 'április',
'may-gen'      => 'május',
'june-gen'     => 'június',
'july-gen'     => 'július',
'august-gen'   => 'augusztus',
'october-gen'  => 'Október',
'mar'          => 'Már',
'apr'          => 'ápr',
'may'          => 'Máj',
'jun'          => 'Jún',
'jul'          => 'Júl',
'aug'          => 'aug',
'sep'          => 'szep',
'oct'          => 'Okt',

# Bits of text used by many pages
'categories'            => 'Kategóriák',
'pagecategories'        => '{{PLURAL:$1|Kategória|Kategóriák}}',
'category_header'       => '„$1” kategóriába tartozó szócikkek',
'subcategories'         => 'Alkategóriák',
'category-media-header' => '„$1” kategóriába tartozó média fájlok',

'mainpagetext' => 'Wiki szoftver sikeresen telepítve.',

'about'          => 'Névjegy',
'article'        => 'Szócikk',
'newwindow'      => '(új ablakban nyílik meg)',
'cancel'         => 'Vissza',
'qbfind'         => 'Keresés',
'qbbrowse'       => 'Böngészés',
'qbedit'         => 'Szerkeszt',
'qbpageoptions'  => 'Lapbeállítások',
'qbpageinfo'     => 'Lapinformáció',
'qbmyoptions'    => 'Beállításaim',
'qbspecialpages' => 'Speciális lapok',
'mypage'         => 'Lapom',
'mytalk'         => 'Vitám',
'anontalk'       => 'Vitalap ehhez az IP-hez',
'navigation'     => 'Navigáció',

'errorpagetitle'    => 'Hiba',
'returnto'          => 'Vissza a $1 cikkhez.',
'tagline'           => 'A {{SITENAME}}BÓL',
'help'              => 'Segítség',
'search'            => 'Keresés',
'searchbutton'      => 'Keresés',
'go'                => 'Menj',
'searcharticle'     => 'Menj',
'history'           => 'laptörténet',
'history_short'     => 'Laptörténet',
'printableversion'  => 'Nyomtatható változat',
'permalink'         => 'Link erre a változatra',
'print'             => 'Nyomtatás',
'edit'              => 'Szerkesztés',
'editthispage'      => 'Szerkeszd ezt a lapot',
'delete'            => 'Törlés',
'deletethispage'    => 'Lap törlése',
'undelete_short'    => '$1 szerkesztés helyreállítása',
'protect'           => 'Lapvédelem',
'protectthispage'   => 'Védelem a lapnak',
'unprotect'         => 'Védelem ki',
'unprotectthispage' => 'Védelem megszüntetése',
'newpage'           => 'Új lap',
'talkpage'          => 'Lap megbeszélése',
'specialpage'       => 'Speciális lap',
'personaltools'     => 'Személyes eszközök',
'postcomment'       => 'Üzenethagyás',
'articlepage'       => 'Szócikk megtekintése',
'talk'              => 'Vitalap',
'views'             => 'Nézetek',
'toolbox'           => 'Eszközök',
'userpage'          => 'Felhasználói lap',
'projectpage'       => 'Wiki lap megtekintése',
'imagepage'         => 'Képlap',
'templatepage'      => 'Sablon lapjának megtekintése',
'viewtalkpage'      => 'Beszélgetés megtekintése',
'otherlanguages'    => 'Más nyelveken',
'redirectedfrom'    => '($1 szócikkből átirányítva)',
'redirectpagesub'   => 'Átirányítás lap',
'lastmodifiedat'    => 'A lap utolsó módosítása $2, $1', # $1 date, $2 time
'viewcount'         => 'Ezt a lapot eddig {{PLURAL:$1|egy|$1}} alkalommal látogatták meg.',
'protectedpage'     => 'Védett lap',
'jumpto'            => 'Ugrás:',
'jumptonavigation'  => 'navigáció',
'jumptosearch'      => 'keresés',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'bugreports'        => 'Hibajelentés',
'bugreportspage'    => 'Project:Hibajelentések',
'copyrightpage'     => 'Project:Copyright',
'currentevents'     => 'Friss események',
'currentevents-url' => '{{ns:project}}:Friss események',
'disclaimers'       => 'Jogi nyilatkozat',
'disclaimerpage'    => '{{ns:project}}:Jogi nyilatkozat',
'edithelp'          => 'Segítség a szerkesztéshez',
'edithelppage'      => '{{ns:project}}:Hogyan_szerkessz_egy_lapot',
'faq'               => 'GyIK',
'faqpage'           => 'Project:GyIK',
'helppage'          => 'Help:Tartalom',
'mainpage'          => 'Kezdőlap',
'portal'            => 'Közösségi portál',
'portal-url'        => '{{ns:project}}:Közösségi portál',
'privacy'           => 'Adatvédelmi irányelvek',
'privacypage'       => '{{ns:project}}:Adatvédelmi irányelvek',
'sitesupport'       => 'Adományok',
'sitesupport-url'   => '{{ns:project}}:Gyűjtőkampány',

'badaccess'        => 'Engedélyezési hiba',
'badaccess-group0' => 'Ezt a tevékenységet nem végezheted el.',
'badaccess-group1' => 'Ezt a tevékenységet csak a(z) $1 csoportjába tartozó felhasználó végezheti el.',
'badaccess-group2' => 'Ezt a tevékenységet csak a(z) $1 csoportok valamelyikébe tartozó felhasználó végezheti el.',
'badaccess-groups' => 'Ezt a tevékenységet csak a(z) $1 csoportok valamelyikébe tartozó felhasználó végezheti el.',

'retrievedfrom'       => 'A lap eredeti címe "$1"',
'youhavenewmessages'  => '$1 van. ($2)',
'newmessageslink'     => 'Új üzeneted',
'newmessagesdifflink' => 'utolsó változtatás',
'editsection'         => 'szerkesztés',
'editold'             => 'szerkesztés',
'editsectionhint'     => 'Szakasz szerkesztése: $1',
'toc'                 => 'Tartalomjegyzék',
'showtoc'             => 'mutat',
'hidetoc'             => 'elrejt',
'thisisdeleted'       => '$1 megnézése vagy helyreállítása?',
'restorelink'         => '{{PLURAL:$1|egy|$1}} törölt szerkesztés',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Szócikk',
'nstab-user'      => 'User lap',
'nstab-media'     => 'Média',
'nstab-special'   => 'Speciális',
'nstab-project'   => 'Wiki lap',
'nstab-image'     => 'Kép',
'nstab-mediawiki' => 'Üzenet',
'nstab-template'  => 'Sablon',
'nstab-help'      => 'Segítség',
'nstab-category'  => 'Kategória',

# Main script and global functions
'nosuchaction'      => 'Nincs ilyen tevékenység',
'nosuchactiontext'  => 'Az URL által megadott tevékenységet a {{SITENAME}}
software nem ismeri fel',
'nosuchspecialpage' => 'Nincs ilyen speciális lap',
'nospecialpagetext' => 'Olyan speciális lapot kértél, amit a {{SITENAME}}-szoftver nem ismer fel.',

# General errors
'error'              => 'Hiba',
'databaseerror'      => 'Adatbázis hiba',
'dberrortext'        => 'Adatbázis formai hiba történt.
Az utolsó lekérési próbálkozás az alábbi volt:
<blockquote><tt>$1</tt></blockquote>
a "<tt>$2</tt>" függvényből.
A MySQL hiba "<tt>$3: $4</tt>".',
'dberrortextcl'      => 'Egy adatbázis lekérés formai hiba történt.
Az utolsó lekérési próbálkozás:
"$1"
a "$2" függvényből történt.
A MySQL hiba "$3: $4".',
'noconnect'          => 'Nem tudok az adatbázis gépre csatlakozni
<br />
$1',
'nodb'               => 'Nem tudom elérni a $1 adatbázist',
'cachederror'        => 'Ez a kért cikk egy régebben elmentett példánya, lehetséges, hogy nem tartalmazza a legújabb módosításokat.',
'laggedslavemode'    => 'Figyelem: Ez a lap nem feltétlenül tartalmazza a legfrissebb változtatásokat!',
'readonly'           => 'Adatbázis lezárva',
'enterlockreason'    => 'Add meg a lezárás indoklását valamint egy becslést,
hogy mikor kerül a lezárás feloldásra',
'readonlytext'       => "
{| style=\"background: none;\"
|-
| A {{SITENAME}} adatbázisa ideiglenesen le van zárva (valószínűleg adatbázis-karbantartás miatt). 
A lezárás időtartama alatt a lapok nem szerkeszthetők, és új szócikkek sem hozhatóak létre, az oldalak azonban továbbra is böngészhetőek.

Az adminisztrátor, aki lezárta az adatbázist, az alábbi magyarázatot adta: <div>'''\$1'''</div>

|}",
'missingarticle'     => 'Az adatbázisban nem található meg a(z) „$1” nevű lap szövege.

<p>Ennek oka általában egy olyan régi link követése, amely egy már törölt lapra hivatkozik.

<p>Ha nem erről van szó akkor lehetséges, hogy programozási hibát találtál a szoftverben. Kérlek, értesíts erről egy [[{{ns:project}}:Adminisztrátorok|adminisztrátort]], és jegyezd fel neki az URL-t (pontos webcímet) is.',
'readonly_lag'       => 'Az adatbázis automatikusan zárolásra került, amíg a mellékszerverek utolérik a főszervert.',
'internalerror'      => 'Belső hiba',
'filecopyerror'      => 'Nem tudom a "$1" file-t a "$2" névre másolni.',
'filerenameerror'    => 'Nem tudom a "$1" file-t "$2" névre átnevezni.',
'filedeleteerror'    => 'Nem tudom a "$1" file-t letörölni.',
'filenotfound'       => 'Nem találom a "$1" file-t.',
'unexpected'         => 'Váratlan érték: "$1"="$2".',
'formerror'          => 'Hiba: nem tudom a formot elküldeni',
'badarticleerror'    => 'Ez a tevékenység nem végezhető ezen a lapon.',
'cannotdelete'       => 'Nem lehet a megadott lapot vagy képet törölni (talán már valaki más törölte).',
'badtitle'           => 'Hibás cím',
'badtitletext'       => 'A kért cím helytelen, üres vagy hibásan hivatkozik
egy nyelvek közötti vagy wikik közötti címre.',
'perfdisabled'       => 'Elnézést, de ez a lehetőség átmenetileg nem elérhető, mert annyira lelassítja az adatbázist, hogy senki nem tudja a wikit használni.',
'perfcached'         => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, és ezért lehetséges, hogy nem a legfrissebb változatot mutatják:",
'perfcachedts'       => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, legutóbbi frissítésük ideje $1.",
'viewsource'         => 'Lapforrás',
'viewsourcefor'      => '$1 változata',
'protectedpagetext'  => 'Ez a lap a szerkesztések megakadályozása érdekében le lett zárva. Módosításokat a vitalapon javasolhatsz, a védelem feloldását az adminisztrátorok üzenőfalán kérheted .',
'viewsourcetext'     => 'A lap forrását megtekintheted és másolhatod:',
'protectedinterface' => 'Ez a lap a honlap felületéhez szolgáltat szöveget a szoftver számára, és a visszaélések elkerülése végett le van zárva. A vitalapon javasolhatsz módosításokat.',

# Login and logout pages
'logouttitle'                => 'Kilépés',
'logouttext'                 => 'Kiléptél.
Folytathatod a {{SITENAME}} használatát név nélkül, vagy beléphetsz
újra vagy másik felhasználóként.',
'welcomecreation'            => '== Üdvözöllek, $1! ==

A felhasználói környezeted létrehoztuk.
Ne felejtsd el átnézni a személyes {{SITENAME}} beállításaidat.',
'loginpagetitle'             => 'Belépés',
'yourname'                   => 'A felhasználói neved',
'yourpassword'               => 'Jelszavad',
'yourpasswordagain'          => 'Jelszavad ismét',
'remembermypassword'         => 'Jelszó megjegyzése a használatok között.',
'loginproblem'               => '<b>Valami probléma van a belépéseddel.</b><br />Kérlek, próbáld ismét!',
'alreadyloggedin'            => '<strong>Kedves $1, már be vagy lépve!</strong><br />',
'login'                      => 'Belépés',
'loginprompt'                => 'Engedélyezned kell a cookie-kat, hogy bejelentkezhess a {{grammar:ba|{{SITENAME}}}}.',
'userlogin'                  => 'Belépés',
'logout'                     => 'Kilépés',
'userlogout'                 => 'Kilépés',
'notloggedin'                => 'Nincs belépve',
'nologin'                    => 'Nincsen még felhasználói neved? $1.',
'nologinlink'                => 'Itt regisztrálhatsz',
'createaccount'              => 'Új felhasználó készítése',
'gotaccount'                 => 'Ha már korábban regisztráltál, $1!',
'gotaccountlink'             => 'jelentkezz be',
'createaccountmail'          => 'eMail alapján',
'badretype'                  => 'A két jelszó eltér egymástól.',
'userexists'                 => 'A megadott felhasználói név már foglalt. Kérlek, válassz másikat!',
'youremail'                  => 'Az e-mail címed1:',
'username'                   => 'Felhasználói név:',
'uid'                        => 'Azonosító:',
'yourrealname'               => 'Valódi neved*',
'yourlanguage'               => 'A felület nyelve:',
'yournick'                   => 'A beceneved (aláírásokhoz):',
'badsig'                     => 'Rossz aláírás; ellenőrizd a HTML formázást.',
'prefs-help-realname'        => '* Igazi neved (nem kötelező): ha úgy döntesz, hogy megadod ez lesz használva a munkád szerzőjének megjelölésére.',
'loginerror'                 => 'Belépési hiba',
'prefs-help-email'           => '1 E-mail cím (nem kötelező megadni): Lehetővé teszi, hogy más szerkesztők kapcsolatba lépjenek veled a felhasználói vagy vitalapodon keresztül, anélkül, hogy névtelenséged feladnád.',
'nocookiesnew'               => 'A felhasználói azonosító létrejött, de nem léptél be. A(z) {{SITENAME}} cookie-kat ("süti") használ a felhasználók azonosítására, és lehetséges, hogy te ezeket letiltottad. Kérünk, hogy engedélyezd a cookie-kat, majd lépj be azonosítóddal és jelszavaddal.',
'nocookieslogin'             => 'A wiki cookie-kat ("süti") használ az azonosításhoz, de te ezeket letiltottad. Engedélyezd őket, majd próbálkozz ismét.',
'noname'                     => 'Nem adtál meg érvényes felhasználói nevet.',
'loginsuccesstitle'          => 'Sikeres belépés',
'loginsuccess'               => 'Beléptél a {{grammar:ba|{{SITENAME}}}} "$1"-ként.',
'nosuchuser'                 => 'Nincs olyan felhasználó hogy "$1".
Ellenőrizd a gépelést, vagy készíts új nevet a fent látható űrlappal.',
'wrongpassword'              => 'A megadott jelszó helytelen.',
'wrongpasswordempty'         => 'Nem adtál meg jelszót. Próbáld újra.',
'mailmypassword'             => 'Küldd el nekem a jelszavamat emailben',
'passwordremindertitle'      => '{{SITENAME}} jelszó emlékeztető',
'passwordremindertext'       => 'Valaki (vélhetően te, a $1 IP-címről)
azt kérte, hogy küldjünk neked új {{SITENAME}} ($4) jelszót.
A "$2" felhasználó jelszava most "$3".
Lépj be, és változtasd meg a jelszavad.

Ha nem kértél új jelszót, vagy közben eszedbe jutott a régi, 
és már nem akarod megváltoztatni, nyugodtan figyelmen kívül 
hagyhatod ezt az értesítést, és használhatod tovább a régi jelszavadat.',
'noemail'                    => 'Nincs a "$1" felhasználóhoz e-mail felvéve.',
'passwordsent'               => 'Az új jelszót elküldtük "$1" email címére.
Lépj be a levélben található adatokkal.',
'eauthentsent'               => 'Egy megerősítést kérő e-mail küldtünk a megadott címre. Mielőtt további levelek lennének küldve a megadott címre, végre kell hajtanod az e-mailben kapott utasításokat, hogy bizonyítsd, valóban tiéd a felhasználói fiók.',
'mailerror'                  => 'Hiba az e-mail küldésekor: $1',
'acct_creation_throttle_hit' => 'Már létrehoztál $1 felhasználói azonosítót. Sajnáljuk, de többet nem hozhatsz létre.',
'emailauthenticated'         => 'Az e-mail címedet megerősítetted $1-kor.',
'emailnotauthenticated'      => 'Az e-mail címed még <strong>nincs megerősítve</strong>. E-mailek küldése és fogadása nem engedélyezett.',
'emailconfirmlink'           => 'Erősítsd meg az e-mail címedet',
'invalidemailaddress'        => 'Az e-mail cím nem fogadható el, mert érvénytelen a formátuma.  Kérlek, adj meg egy helyesen formázott e-mail címet vagy hagyd üresen a mezőt.',
'accountcreated'             => 'Azonosító létrehozva',
'accountcreatedtext'         => '$1 felhasználói azonosítója sikeresen létrejött.',

# Edit page toolbar
'bold_sample'     => 'Félkövér szöveg',
'bold_tip'        => 'Félkövér szöveg',
'italic_sample'   => 'Dőlt szöveg',
'italic_tip'      => 'Dőlt szöveg',
'link_sample'     => 'Belső hivatkozás',
'link_tip'        => 'Belső hivatkozás',
'extlink_sample'  => 'http://www.példa-hivatkozás.hu hivatkozás címe',
'extlink_tip'     => 'Külső hivatkozás (ne felejtsd a http:// előtagot)',
'headline_sample' => 'Alfejezet címe',
'headline_tip'    => 'Alfejezetcím',
'math_sample'     => 'TeX-képlet ide',
'math_tip'        => 'Matematikai képlet (LaTeX)',
'nowiki_sample'   => 'Ide írd a nem-formázott szöveget',
'nowiki_tip'      => 'Wiki formázás kikapcsolása',
'image_sample'    => 'Egyszerikép.jpg',
'image_tip'       => 'Kép beszúrása',
'media_sample'    => 'Peldaegyketto.ogg',
'media_tip'       => 'Média file hivatkozás',
'sig_tip'         => 'Aláírás időponttal',
'hr_tip'          => 'Vízszintes vonal (módjával használd)',

# Edit pages
'summary'                  => 'Összefoglaló',
'subject'                  => 'Téma/főcím',
'minoredit'                => 'Ez egy apró változtatás',
'watchthis'                => 'Figyeld a szócikket',
'savearticle'              => 'Lap mentése',
'preview'                  => 'Előnézet',
'showpreview'              => 'Előnézet megtekintése',
'showdiff'                 => 'Változtatások megtekintése',
'anoneditwarning'          => 'Nem vagy bejelentkezve. Az IP címed látható lesz a laptörténetben.',
'missingsummary'           => "'''Emlékeztető:''' Nem adtál meg szerkesztési összefoglalót. Ha összefoglaló nélkül akarod elküldeni a szöveget, kattints újra a mentésre.",
'missingcommenttext'       => 'Kérjük, hogy írj összefoglalót szerkesztésedhez.',
'summary-preview'          => 'A szerkesztési összefoglaló előnézete',
'subject-preview'          => 'A szakaszcím előnézete',
'blockedtitle'             => 'A felhasználó fel van függesztve',
'blockedtext'              => "Az IP címed vagy a felhasználói neved blokkolva lett a Wiki szabályainak súlyos megsértése miatt. A blokkot $1 állította be az alábbi indoklással:
:''$2''

Amíg a blokk életben van, nem tudod szerkeszteni a Wiki lapjait. Semmi másban nem vagy korlátozva – ha csak olvasni szeretnél, minden akadály nélkül megteheted. A blokk időtartamát a [[Special:Ipblocklist|blokkok listájában]] nézheted meg. Ha kérdésed vagy kifogásod van, vagy úgy gondolod, hogy a blokkolás nem felelt meg a [[{{ns:project}}:Blokkolási irányelvek|szabályoknak]], fordulj az adminisztrátorokhoz.

Egyes IP címeken több ember osztozik, vagy más-más időpontban különböző emberek kapják meg. '''Elképzelhető, hogy egy másvalakinek szánt blokkba futottál bele.''' (Ha nem érted, miért vagy blokkolva, valószínűleg ez a helyzet.) Ebben az esetben elnézésedet kérjük a kellemetlenségért. Próbáld meg bontani az internetkapcsolatodat, és újracsatlakozni. Ha ez sem segít, értesítsd az egyik adminisztrátort.

== Kapcsolatfelvétel ==
'''Ha be vagy jelentkezve, és adtál meg email-címet''', [[Special:Emailuser/$4|küldhetsz levelet]] a blokkot beállító adminisztrátornak. Az esetleges egyéb elérhetőségeit a [[User:$4|felhasználói lapján]] találod. Ezenkívül felveheted a kapcsolatot [[{{ns:project}}:Adminisztrátorok|a többi adminisztrátor]] valamelyikével (lásd a lapon a „további elérhtőségek” oszlopot), vagy írhatsz a nyilvános levelezőlistára.

'''A blokkal kapcsolatos üzenetekben írf meg az IP címedet ($3), a blokk sorszámát ($5) és – ha be vagy jelentkezve – a felhasználónevedet!'''

<small>Your username or IP has been blocked by $1. If you have objections, you can [[Special:Emailuser/$4|email $4]] or contact [[{{ns:project}}:Adminisztrátorok#Adminisztrátorok listája|other admins]].</small>",
'blockedoriginalsource'    => "'''$1''' forrása megtalálható alább:",
'blockededitsource'        => "'''$1''' lapon '''általad végrehajtott szerkesztések''' szövege:",
'whitelistedittitle'       => 'A szerkesztéshez be kell lépned',
'whitelistedittext'        => 'A szócikkek szerkesztéséhez $1.',
'whitelistreadtitle'       => 'Az olvasáshoz be kell lépned',
'whitelistreadtext'        => '[[Special:Userlogin|Be kell lépned]] ahhoz, hogy cikkeket tudj olvasni.',
'whitelistacctitle'        => 'Nem készíthetsz új bejelentkezési kódot',
'whitelistacctext'         => 'Ahhoz, hogy ezen a Wikin új nevet regisztrálj [[Special:Userlogin|be kell lépned]] a szükséges engedélyszinttel.',
'confirmedittitle'         => 'E-mail cím megerősítése szükséges a szerkesztéshez',
'accmailtitle'             => 'Jelszó elküldve.',
'accmailtext'              => '„$1” jelszavát elküldtük $2 címre.',
'newarticle'               => '(Új)',
'newarticletext'           => "Egy olyan lapra jutottál ami még nem létezik.
A lap létrehozásához kezdd el írni a szövegét lenti keretbe
(a [[{{MediaWiki:helppage}}|segítség]] lapon lelsz további
információkat).
Ha tévedésből jöttél ide, csak nyomd meg a böngésző '''Vissza/Back'''
gombját.",
'anontalkpagetext'         => "---- ''Ez egy olyan anonim felhasználó vitalapja, aki még nem készített magának nevet vagy azt nem használta. Ezért az IP-címét használjuk az azonosítására. Az IP számokon számos felhasználó osztozhat az idők folyamán. Ha anonim felhasználó vagy és úgy érzed, hogy értelmetlen megjegyzéseket írnak neked akkor [[Special:Userlogin|készíts magadnak egy nevet vagy lépj be]] hogy megakadályozd más anonim felhasználókkal való keveredést.''",
'noarticletext'            => '(Ez a lap jelenleg nem tartalmaz szöveget)',
'clearyourcache'           => "'''Megjegyzés:''' A beállítások elmentése után frissítened kell a böngésződ gyorsítótárát, hogy a változások érvénybe lépjenek. '''Mozilla''' / '''Firefox''' / '''Safari:''' tartsd lenyomva a Shift gombot és kattints a ''Reload'' / ''Frissítés'' gombra az eszköztáron, vagy használd a ''Ctrl–F5'' billentyűkombinációt (Apple Mac-en ''Cmd–Shift–R''); '''Internet Explorer:''' tartsd nyomva a ''Ctrl''-t, és kattints a ''Reload'' / ''Frissítés'' gombra, vagy nyomj ''Ctrl–F5''-öt; '''Konqueror:''' egyszerűen csak kattints a ''Reload'' / ''Frissítés'' gombra (vagy ''Ctrl–R'' vagy ''F5''); '''Opera''' felhasználóknak teljesen ki kell üríteniük a gyorsítótárat a ''Tools›Preferences'' menüben.",
'usercssjsyoucanpreview'   => '<strong>Tipp:</strong> Használd az "Előnézet megtekintése" gombot az új css/js teszteléséhez mentés előtt.',
'usercsspreview'           => "'''Ne felejtsd el, hogy ez csak a css előnézete és még nincs elmentve!'''",
'userjspreview'            => "'''Ne felejtsd el hogy még csak teszteled a felhasználói javascriptedet és az még nincs elmentve!'''",
'userinvalidcssjstitle'    => "'''Figyelem:''' Nincs „$1” nevű felület. Lehet, hogy nagy kezdőbetűt használtál olyan helyen, ahol nem kellene? A felületekhez tartozó .css/.js oldalak kisbetűvel kezdődnek. (Például ''User:Gipsz Jakab/monobook.css'' és nem ''User:Gipsz Jakab/Monobook.css''.)",
'updated'                  => '(Frissítve)',
'note'                     => '<strong>Megjegyzés:</strong>',
'previewnote'              => 'Ne felejtsd el, hogy ez csak egy előnézet, és nincs elmentve!',
'previewconflict'          => 'Ez az előnézet a felső szerkesztőablakban levő
szövegnek megfelelő képet mutatja, ahogy az elmentés után kinézne.',
'session_fail_preview'     => '<strong>Sajnos nem tudtuk feldolgozni a szerkesztésedet, mert elveszett a session adat. Kérjük próbálkozz újra! Amennyiben továbbra sem sikerül próbálj meg kijelentkezni, majd ismét bejelentkezni!</strong>',
'editing'                  => '$1 szerkesztés alatt',
'editinguser'              => '$1 szerkesztés alatt',
'editingsection'           => '$1 szerkesztés alatt (szakasz)',
'editingcomment'           => '$1 szerkesztés alatt (üzenet)',
'editconflict'             => 'Szerkesztési ütközés: $1',
'explainconflict'          => 'Valaki megváltoztatta a lapot azóta,
mióta szerkeszteni kezdted.
A felső szövegablak tartalmazza a szöveget, ahogy az jelenleg létezik.
A módosításaid az alsó ablakban láthatóak.
Át kell vezetned a módosításaidat a felső szövegbe.
<b>Csak</b> a felső ablakban levő szöveg kerül elmentésre akkor, mikor
a "Lap mentését" választod.<br />',
'storedversion'            => 'A tárolt változat',
'nonunicodebrowser'        => '<strong>Figyelem: A böngésződ nem unicode kompatibilis. Egy programozási trükk segítségével biztonságban szerkesztheted a cikkeket: a nem ASCII karakterek a szerkesztőablakban hexadeciális kódokként jelennek meg..</strong>',
'editingold'               => 'A lap egy elavult változatát szerkeszted. Ha elmented, akkor az ezen változat után végzett összes
módosítás elvész.',
'yourdiff'                 => 'Eltérések',
'longpagewarning'          => '<strong>FIGYELEM: Ez a lap $1 kilobyte hosszú;
néhány böngészőnek problémái vannak a 32KB körüli vagy nagyobb lapok
szerkesztésével.
Fontold meg a lap kisebb szakaszokra bontását.</strong>',
'readonlywarning'          => '<strong>FIGYELEM: Az adatbázis karbantartás miatt le van zárva,
ezért a módosításaidat most nem lehetséges elmenteni. Érdemes a szöveget
kimásolni és elmenteni egy szövegszerkesztőben a későbbi mentéshez.</strong>',
'protectedpagewarning'     => '<strong>FIGYELEM: Ez a lap védett, csak adminisztrátorok szerkeszthetik. Szerkesztéskor tartsd szem előtt a [[Project:Lapvédelmi_irányelvek|zárt lapok irányelveit]].</strong>',
'semiprotectedpagewarning' => "'''Megjegyzés:''' ez a lap [[{{ns:project}}:Védett lapok|védett]], nem vagy újonnan regisztrált felhasználók nem szerkeszthetik.",
'templatesused'            => 'Sablonok ezen a lapon:',
'templatesusedpreview'     => 'Az előnézetben használt sablonok:',
'templatesusedsection'     => 'Szakaszban használt sablonok:',
'template-protected'       => '(védett)',
'template-semiprotected'   => '(félig-védett)',

# "Undo" feature
'undo-success' => 'A szerkesztés visszavonható. Kérlek ellenőrizd a változásokat alább, hogy valóban ezt szeretnéd-e tenni, majd kattints a lap mentése gombra a visszavonás véglegesítéséhez.',
'undo-failure' => 'A szerkesztést nem lehet visszavonni vele ütköző későbbi szerkesztések miatt.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|vita]]) $1 szerkesztésének visszaállítása.',

# Account creation failure
'cantcreateaccounttext' => 'Új felhasználó létrehozását erről az IP címről (<b>$1</b>) blokkolták. 
Erre valószínűleg ismétlődő vandalizmus miatt került sor.',

# History pages
'revhistory'          => 'Változások története',
'viewpagelogs'        => 'A lap a rendszernaplókban',
'nohistory'           => 'Nincs szerkesztési történet ehhez a laphoz.',
'revnotfound'         => 'A változat nem található',
'revnotfoundtext'     => 'A lap általad kért régi változatát nem találom. Kérlek, ellenőrizd az URL-t, amivel erre a lapra jutottál.',
'loadhist'            => 'Laptörténet beolvasása',
'currentrev'          => 'Aktuális változat',
'revisionasof'        => '$1 változat',
'revision-info'       => '$2 $1 kori változata',
'previousrevision'    => '‹Régebbi változat',
'nextrevision'        => 'Újabb változat›',
'currentrevisionlink' => 'legfrissebb változat',
'cur'                 => 'akt',
'next'                => 'köv',
'last'                => 'előző',
'orig'                => 'eredeti',
'histlegend'          => 'Jelmagyarázat: (akt) = eltérés az aktuális változattól,
(előző) = eltérés az előző változattól, 
A = Apró változtatás',
'deletedrev'          => '[törölve]',
'histfirst'           => 'legkorábbi',
'histlast'            => 'legutolsó',

# Revision feed
'history-feed-title'       => 'Laptörténet',
'history-feed-description' => 'Az oldal laptörténete a {{SITENAME}}',

# Diffs
'difference'  => '(Változatok közti eltérés)',
'loadingrev'  => 'különbségképzéshez olvasom a változatokat',
'lineno'      => '$1. sor:',
'editcurrent' => 'A lap aktuális változatának szerkesztése',
'editundo'    => 'visszavonás',
'diff-multi'  => '({{plural:$1|Egy közbeeső változat|$1 közbeeső változat}} nincs mutatva)',

# Search results
'searchresults'         => 'A keresés eredménye',
'searchresulttext'      => "'''Megjegyzés''': Az újonnan elkészített szócikkek körülbelül 30-40 óra elteltével válnak kereshetővé. <br />
További információkkal a keresésről a [[Project:Keresés|Keresés]] szolgál.",
'searchsubtitle'        => 'Erre kerestél: „[[:$1]]”',
'searchsubtitleinvalid' => 'A "$1" kereséshez',
'badquery'              => 'Hibás formájú keresés',
'badquerytext'          => 'Nem tudjuk a kérésedet végrehajtani. Ennek oka valószínűleg az, hogy három betűnél rövidebb karaktersorozatra próbáltál keresni, ami jelenleg nem lehetséges. Lehet az is, hogy elgépelted a kifejezést, például „hal and and mérleg”. Kérlek, próbálj másik kifejezést keresni.',
'matchtotals'           => 'A "$1" keresés $2 címszót talált és
$3 szócikk szövegét.',
'noexactmatch'          => "Nincs '''$1''' nevű lap. Készíthetsz egy [[:$1|új szócikket]] ezen a néven, felveheted a [[{{ns:project}}:Kért cikkek|kért cikkek]] közé, vagy megnézheted azon szócikkek listáját, amik [[Special:Whatlinkshere/$1|erre a kifejezésre hivatkoznak]], vagy [[Special:Prefixindex/$1|vele kezdődnek]].",
'titlematches'          => 'Címszó egyezik',
'notitlematches'        => 'Nincs egyező címszó',
'textmatches'           => 'Szócikk szövege egyezik',
'notextmatches'         => 'Nincs szócikk szöveg egyezés',
'prevn'                 => 'előző $1',
'nextn'                 => 'következő $1',
'viewprevnext'          => '($1) ($2) ($3)',
'showingresults'        => 'Lent látható <b>$1</b> találat, az eleje <b>$2</b>.',
'showingresultsnum'     => 'Lent látható <b>$3</b> találat, az eleje #<b>$2</b>.',
'nonefound'             => '<strong>Megyjegyzés</strong>: a sikertelen keresések
gyakori oka olyan szavak keresése (pl. "have" és "from") amiket a
rendszer nem indexel fel, vagy több független keresési szó szerepeltetése
(csak minden megadott szót tartalmazó találatok jelennek meg a
végeredményben).',
'powersearch'           => 'Keresés',
'powersearchtext'       => '
Keresés a névterekben:<br />
$1<br />
$2 Átirányítások listája &nbsp; Keresés:$3 $9',
'searchdisabled'        => 'Elnézésed kérjük, de a teljes szöveges keresés terhelési okok miatt átmenetileg nem használható. Ezidő alatt használhatod a lenti Google keresést, mely viszont lehetséges, hogy nem teljesen friss adatokkal dolgozik.',
'blanknamespace'        => '(Alap)',

# Preferences page
'preferences'              => 'Beállításaim',
'mypreferences'            => 'beállításaim',
'prefsnologin'             => 'Nem vagy belépve',
'prefsnologintext'         => 'Ahhoz, hogy a 
beállításaidat rögzíthesd, [[Special:Userlogin|be kell lépned]].',
'prefsreset'               => 'A beállítások törlődtek a tárolóból vett értékekre.',
'qbsettings'               => 'Gyorsmenü beállítások',
'qbsettings-none'          => 'Nincs',
'qbsettings-fixedleft'     => 'Fix baloldali',
'qbsettings-fixedright'    => 'Fix jobboldali',
'qbsettings-floatingleft'  => 'Lebegő baloldali',
'qbsettings-floatingright' => 'Lebegő jobboldali',
'changepassword'           => 'Jelszó változtatása',
'skin'                     => 'Felület',
'math'                     => 'Képletek',
'dateformat'               => 'Dátum formátuma',
'datetime'                 => 'Dátum és idő',
'math_failure'             => 'Értelmezés sikertelen',
'math_unknown_error'       => 'ismeretlen hiba',
'math_unknown_function'    => 'ismeretlen függvény',
'math_syntax_error'        => 'formai hiba',
'math_image_error'         => 'Sikertelen PNG-vé alakítás (szerver oldali hiba)',
'prefs-personal'           => 'Felhasználói adatok',
'prefs-rc'                 => 'Friss változtatások',
'prefs-watchlist'          => 'Figyelőlista',
'prefs-watchlist-days'     => 'A figyelőlistában mutatott napok száma:',
'prefs-watchlist-edits'    => 'A kiterjesztett figyelőlistán mutatott szerkesztések száma:',
'prefs-misc'               => 'Egyéb',
'saveprefs'                => 'Beállítások mentése',
'resetprefs'               => 'Beállítások törlése',
'oldpassword'              => 'Régi jelszó:',
'newpassword'              => 'Új jelszó:',
'retypenew'                => 'Új jelszó ismét:',
'textboxsize'              => 'Szerkesztés',
'rows'                     => 'Sor',
'columns'                  => 'Oszlop',
'searchresultshead'        => 'Keresés',
'resultsperpage'           => 'Laponként mutatott találatok száma:',
'contextlines'             => 'Találatonként mutatott sorok száma:',
'contextchars'             => 'Soronkénti szövegkörnyezet (karakterszám):',
'recentchangescount'       => 'Címszavak száma a friss változtatásokban:',
'savedprefs'               => 'Az új beállításaid érvénybe léptek.',
'timezonelegend'           => 'Időzóna',
'timezonetext'             => 'Add meg az órák számát, amennyivel a helyi
idő a GMT-től eltér (Magyarországon nyáron 2, télen 1).',
'localtime'                => 'Helyi idő:',
'timezoneoffset'           => 'Eltérés1:',
'servertime'               => 'A szerver ideje:',
'guesstimezone'            => 'Töltse ki a böngésző',
'allowemail'               => 'E-mail engedélyezése más felhasználóktól',
'defaultns'                => 'Alapértelmezésben az alábbi névterekben keressünk:',
'default'                  => 'alapértelmezés',
'files'                    => 'Képek',

# Groups
'group'            => 'Csoport:',
'group-bot'        => 'Botok',
'group-sysop'      => 'adminisztrátorok',
'group-bureaucrat' => 'Bürokraták',

'group-sysop-member'      => 'adminisztrátor',
'group-bureaucrat-member' => 'Bürokrata',

'grouppage-bot'        => '{{ns:project}}:Botok',
'grouppage-sysop'      => '{{ns:project}}:Adminisztrátorok',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokraták',

# User rights log
'rightslog' => 'Felhasználói jogosultságok naplója',

# Recent changes
'recentchanges'                  => 'Friss változtatások',
'recentchanges-feed-description' => 'Kövesd a wiki friss változtatásait ezzel a hírcsatornával.',
'rcnote'                         => 'Lentebb az utolsó <strong>$2</strong> nap utolsó <strong>$1</strong> változtatása látható. A lap generálásának időpontja $3.',
'rcnotefrom'                     => 'Lentebb láthatóak a <b>$2</b> óta történt változások (<b>$1</b>-ig).',
'rclistfrom'                     => 'Az új változtatások kijelzése $1 után',
'rcshowhideminor'                => 'apró módosítások $1',
'rcshowhidebots'                 => 'robotok szerkesztéseinek $1',
'rcshowhideliu'                  => 'bejelentkezett felhasználók szerkesztéseinek $1',
'rcshowhideanons'                => 'névtelen szerkesztések $1',
'rcshowhidepatr'                 => 'ellenőrzött szerkesztések $1',
'rcshowhidemine'                 => 'saját szerkesztések $1',
'rclinks'                        => 'Az elmúlt $2 nap utolsó $1 változtatása legyen látható<br />$3',
'diff'                           => 'eltér',
'hist'                           => 'történet',
'hide'                           => 'elrejtése',
'show'                           => 'megjelenítése',
'minoreditletter'                => 'A',
'newpageletter'                  => 'Ú',

# Recent changes linked
'recentchangeslinked' => 'Kapcsolódó változtatások',

# Upload
'upload'                      => 'Fájl felküldése',
'uploadbtn'                   => 'Fájl felküldése',
'reupload'                    => 'Újraküldés',
'reuploaddesc'                => 'Visszatérés a felküldési űrlaphoz.',
'uploadnologin'               => 'Nem jelentkeztél be',
'uploadnologintext'           => 'Csak regisztrált felhasználók tölthetnek fel fájlokat. [[Special:Userlogin|Jelentkezz be]] vagy [{{FULLURL:Special:userlogin|type=signup}} regisztrálj]!',
'uploaderror'                 => 'Felküldési hiba',
'uploadlog'                   => 'felküldési napló',
'uploadlogpage'               => 'Felküldési_napló',
'uploadlogpagetext'           => 'Lentebb látható a legutóbbi felküldések listája. Minden időpont a szerver időzónájában (UTC) van megadva.',
'filename'                    => 'Filenév',
'filedesc'                    => 'Összefoglaló',
'fileuploadsummary'           => 'Összefoglaló:',
'filestatus'                  => 'Szerzői jogi állapot',
'filesource'                  => 'Forrás',
'uploadedfiles'               => 'Felküldött file-ok',
'ignorewarning'               => 'Biztosan így akarom feltölteni.',
'ignorewarnings'              => 'Hagyd figyelmen kívül a figyelmeztetéseket',
'minlength'                   => 'A kép nevének legalább három betűből kell állnia.',
'badfilename'                 => 'A kép új neve "$1".',
'largefileserver'             => 'A fájl mérete meghaladja a kiszolgálón beállított maximális értéket.',
'fileexists'                  => 'Ezzel a névvel már létezik egy file: $1. Ellenőrizd hogy biztosan felül akarod-e írni azt!',
'fileexists-forbidden'        => 'Egy ugyanilyen nevű fájl már létezik; kérlek menj vissza és töltsd fel a fájlt egy másik néven. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Egy ugyanilyen nevű fájl már létezik a Commonson; kérlek menj vissza és válassz egy másik nevet a fájlnak.
[[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Sikeresen felküldve',
'fileuploaded'                => 'A(z) „$1” fájl felküldése sikeres volt. Kérlek, a ($2) linken add meg a fájl adatait és leírását, mint például honnan való, mikor és ki készítette, stb.',
'uploadwarning'               => 'Felküldési figyelmeztetés',
'savefile'                    => 'File mentése',
'uploadedimage'               => '"[[$1]]" felküldve',
'uploadscripted'              => 'Ez a file olyan HTML vagy script kódot tartalmaz melyet tévedésből egy webböngésző esetleg értelmezni próbálhatna.',
'uploadcorrupt'               => 'A fájl sérült vagy hibás a kiterjesztése. Légy szíves ellenőrizd a fájlt és próbálkozz újra!',
'uploadvirus'                 => 'Ez a file vírust tartalmaz! A részletek: $1',
'sourcefilename'              => 'Forrásfájl neve',
'destfilename'                => 'Célmédiafájl neve',
'watchthisupload'             => 'Figyeld ezt a lapot',

'license'   => 'Licenc',
'nolicense' => 'Válassz licencet!',

# Image list
'imagelist'                 => 'Képlista',
'imagelisttext'             => 'Lentebb látható $1 kép, $2 rendezve.',
'getimagelist'              => 'képlista lehívása',
'ilsubmit'                  => 'Keresés',
'showlast'                  => 'Az utolsó $1 kép $2.',
'byname'                    => 'név szerint',
'bydate'                    => 'dátum szerint',
'bysize'                    => 'méret szerint',
'imgdelete'                 => 'töröl',
'imgdesc'                   => 'leírás',
'imglegend'                 => 'Jelmagyarázat: (leírás) = kép leírás megtekintés/szerkesztés.',
'imghistory'                => 'Kép története',
'revertimg'                 => 'régi',
'deleteimg'                 => 'töröl',
'deleteimgcompletely'       => 'töröl',
'imghistlegend'             => 'Jelmagyarázat: (akt) = ez az aktuális kép,
(töröl) = ezen régi változat törlése,
(régi) = visszaállás erre a régi változatra.
<br /><i>Klikkelj a dátumra hogy megnézhesd az akkor felküldött képet</i>.',
'imagelinks'                => 'Képhivatkozások',
'linkstoimage'              => 'Az alábbi lapok hivatkoznak erre a képre:',
'nolinkstoimage'            => 'Erre a képre nem hivatkozik lap.',
'shareduploadwiki'          => 'Lásd a [$1 file leírólapját] a további információkért.',
'noimage'                   => 'Ezen a néven nem létezik médiafájl. Ha szeretnél, $1 egyet.',
'noimage-linktext'          => 'feltölthetsz',
'uploadnewversion-linktext' => 'A fájl újabb változatának felküldése',

# MIME search
'mimesearch' => 'Keresés MIME-típus alapján',
'mimetype'   => 'MIME-típus:',

# Unwatched pages
'unwatchedpages' => 'Nem figyelt lapok',

# List redirects
'listredirects' => 'Átirányítások listája',

# Unused templates
'unusedtemplates'     => 'Nem használt sablonok',
'unusedtemplatestext' => 'Ez a lap azon sablon névtérben lévő lapokat gyűjti össze, melyek nem találhatók meg más lapokon. Ellenőrizd a linkeket, mielőtt törölnéd őket.',

# Random redirect
'randomredirect' => 'Átirányítás találomra',

# Statistics
'statistics'    => 'Statisztikák',
'sitestats'     => 'Tartalmi statisztikák',
'userstats'     => 'Felhasználói statisztikák',
'userstatstext' => 'Jelenleg <b>$1</b> regisztrált felhasználónk van; közülük <b>$2</b> ($4%) $5 (lásd: $3).',

'disambiguations'     => 'Egyértelműsítő lapok',
'disambiguationspage' => 'Template:Egyért',

'doubleredirects'     => 'Dupla átirányítások',
'doubleredirectstext' => '<strong>Figyelem:</strong> Ez a lista nem feltétlenül pontos. Ennek általában az oka az, hogy a #REDIRECT alatt további szöveg található.<br />
Minden sor tartalmazza az első és a második átirányítást, valamint a második átirányítás cikkének első sorát, ami általában a „valódi” célt tartalmazza, amire az elsőnek mutatnia kellene.',

'brokenredirects'        => 'Nem létező lapra mutató átirányítások',
'brokenredirectstext'    => 'Az alábbi átirányítások nem létező lapokra mutatnak.',
'brokenredirects-edit'   => '(szerkeszt)',
'brokenredirects-delete' => '(törlés)',

# Miscellaneous special pages
'nbytes'                  => '$1 bájt',
'ncategories'             => '$1 kategória',
'nlinks'                  => '$1 link',
'nmembers'                => '$1 elem',
'nrevisions'              => '$1 változat',
'nviews'                  => '$1 megtekintés',
'lonelypages'             => 'Magányos lapok',
'uncategorizedpages'      => 'Kategorizálatlan lapok',
'uncategorizedcategories' => 'Kategorizálatlan kategóriák',
'uncategorizedimages'     => 'Kategorizálatlan képek',
'unusedcategories'        => 'Nem használt kategóriák',
'unusedimages'            => 'Nem használt képek',
'popularpages'            => 'Népszerű lapok',
'wantedcategories'        => 'Keresett kategóriák',
'wantedpages'             => 'Keresett lapok',
'allpages'                => 'Az összes lap listája',
'prefixindex'             => 'Keresés előtag szerint',
'randompage'              => 'Lap találomra',
'shortpages'              => 'Rövid lapok',
'longpages'               => 'Hosszú lapok',
'deadendpages'            => 'Zsákutca lapok',
'deadendpagestext'        => 'Az itt található lapok nem kapcsolódnak hivatkozásokkal ezen wiki más oldalaihoz.',
'listusers'               => 'Felhasználók',
'specialpages'            => 'Speciális lapok',
'spheading'               => 'Speciális lapok',
'restrictedpheading'      => 'Korlátozott hozzáférésű speciális lapok',
'rclsub'                  => '(a "$1" lapról hivatkozott lapok)',
'newpages'                => 'Új lapok',
'newpages-username'       => 'Felhasználói név:',
'ancientpages'            => 'Régóta nem változott szócikkek',
'intl'                    => 'Nyelvek közötti linkek',
'movethispage'            => 'Nevezd át ezt a lapot',
'unusedimagestext'        => '<p>Vedd figyelembe azt hogy más
lapok - mint például a nemzetközi {{grammar:k|{{SITENAME}}}} - közvetlenül
hivatkozhatnak egy file URL-jére, ezért szerepelhet itt annak
ellenére hogy aktívan használják.</p>',
'unusedcategoriestext'    => 'A következő kategóriákban egyetlen cikk, illetve alkategória sem szerepel.',

# Book sources
'booksources' => 'Könyvforrások',

'categoriespagetext' => 'A wikiben az alábbi kategóriák találhatóak.',
'alphaindexline'     => '$1 – $2',
'version'            => 'Névjegy',

# Special:Log
'specialloguserlabel'  => 'Felhasználó:',
'speciallogtitlelabel' => 'Cím:',
'log'                  => 'Rendszernaplók',
'alllogstext'          => 'Az átnevezési, feltöltési, törlési, lapvédelmi, blokkolási, bürokrata és felhasználó-átnevezési naplók közös listája. Szűkítheted a listát a naplótípus, a műveletet végző felhasználó vagy az érintett oldal megadásával.',
'logempty'             => 'Nincs illeszkedő naplóbejegyzés.',

# Special:Allpages
'nextpage'          => 'Következő lap ($1)',
'prevpage'          => 'Előző oldal ($1)',
'allpagesfrom'      => 'Lapok listázása a következő címtől kezdve:',
'allarticles'       => 'Az összes lap listája',
'allinnamespace'    => 'Összes lap ($1 névtér)',
'allnotinnamespace' => 'Minden olyan lap, ami nem a(z) $1 névtérben van.',
'allpagesprev'      => 'Előző',
'allpagesnext'      => 'Következő',
'allpagessubmit'    => 'Keresés',
'allpagesprefix'    => 'Lapok listázása, amik ezzel az előtaggal kezdődnek:',
'allpagesbadtitle'  => 'A megadott lapnév nyelvközi vagy wikiközi előtagot tartalmazott, vagy érvénytelen volt. Talán olyan karakter van benne, amit nem lehet lapnevekben használni.',

# Special:Listusers
'listusersfrom' => 'Felhasználók listázása a következő névtől kezdve:',

# E-mail user
'mailnologin'     => 'Nincs feladó',
'mailnologintext' => 'Ahhoz hogy másoknak emailt küldhess
[[Special:Userlogin|be kell jelentkezned]]
és meg kell adnod egy érvényes email címet a [[Special:Preferences|beállításaidban]].',
'emailuser'       => 'E-mail küldése ezen szerkesztőnek',
'emailpage'       => 'E-mail küldése',
'emailpagetext'   => 'Ha ez a felhasználó érvényes e-mail-címet adott meg, akkor ezen űrlap kitöltésével e-mailt tudsz neki küldeni. Feladóként a beállításaid között megadott e-mail-címed fog szerepelni, hogy a címzett válaszolni tudjon.',
'defemailsubject' => 'Wiki e-mail',
'noemailtitle'    => 'Nincs e-mail cím',
'noemailtext'     => 'Ez a felhasználó nem adott meg e-mail címet, vagy
nem kíván másoktól leveleket kapni.',
'emailfrom'       => 'Feladó',
'emailto'         => 'Címzett',
'emailsubject'    => 'Téma',
'emailmessage'    => 'Üzenet',
'emailsend'       => 'Küldés',
'emailccme'       => 'Az üzenet másolatát küldje el nekem is e-mailben.',
'emailccsubject'  => '$1-nek küldött $2 tárgyú üzenet másolata',
'emailsent'       => 'E-mail elküldve',
'emailsenttext'   => 'Az e-mail üzenetedet elküldtem.',

# Watchlist
'watchlist'            => 'Figyelőlistám',
'mywatchlist'          => 'Figyelőlistám',
'watchlistfor'         => "('''$1''' részére)",
'nowatchlist'          => 'Nincs lap a figyelőlistádon.',
'watchlistanontext'    => 'A figyelőlistád megtekintéséhez és szerkesztéséhez $1.',
'watchlistcount'       => "'''$1 lap van a figyelőlistádon, beleértve a vitalapokat is.'''",
'clearwatchlist'       => 'Figyelőlista törlése',
'watchlistcleartext'   => 'Biztosan el akarod őket távolítani?',
'watchlistclearbutton' => 'Figyelőlista törlése',
'watchlistcleardone'   => 'A figyelőlistád törölve, $1 lap került eltávolításra.',
'watchnologin'         => 'Nincs belépve',
'watchnologintext'     => 'Ahhoz, hogy figyelőlistád lehessen, [[Special:Userlogin|be kell lépned]].',
'addedwatch'           => 'Figyelőlistához hozzáfűzve',
'addedwatchtext'       => "A „$1” lapot hozzáadtam a [[Special:Watchlist|figyelőlistádhoz]].
Ezután minden, a lapon vagy annak vitalapján történő változást ott fogsz
látni, és a lap '''vastagon''' fog szerepelni a [[Special:Recentchanges|friss változtatások]]
lapon, hogy könnyen észrevehető legyen.

Ha később el akarod távolítani a lapot a figyelőlistádról, akkor ezt az
oldalmenü „{{MediaWiki:unwatchthispage}}” pontjával (vagy a „{{MediaWiki:unwatch}}” füllel) teheted meg.",
'removedwatch'         => 'Figyelőlistáról eltávolítva',
'removedwatchtext'     => 'A „$1” lapot eltávolítottam a figyelőlistáról.',
'watch'                => 'Lap figyelése',
'watchthispage'        => 'Lap figyelése',
'unwatch'              => 'Lapfigyelés vége',
'unwatchthispage'      => 'Figyelés vége',
'notanarticle'         => 'Nem szócikk',
'watchnochange'        => 'Egyik figyelt lap sem változott a megadott időintervallumon belül.',
'watchdetails'         => '<strong>$1</strong> lap van a figyelőlistádon (a vitalapokon kívül). A listát [[Special:Watchlist/edit|itt szerkesztheted]] vagy [[Special:Watchlist/clear|törölhetsz róla mindent]].',
'wlheader-enotif'      => '* Email értesítés engedélyezve.',
'wlheader-showupdated' => "* Azok a lapok, amelyek megváltoztak, mióta utoljára megnézted őket, '''vastagon''' láthatóak.",
'watchmethod-recent'   => 'a figyelt lapokon belüli legfrissebb szerkesztések',
'watchmethod-list'     => 'a legfrissebb szerkesztésekben található figyelt lapok',
'removechecked'        => 'A kijelölt lapok eltávolítása a figyelésből',
'watchlistcontains'    => 'A figyelőlistád $1 lapot tartalmaz.',
'watcheditlist'        => "Íme a figyelőlistádban található lapok betűrendes listája. Ha egyes lapokat el szeretnél távolítani, jelöld ki őket, és válaszd a 'Kijelöltek eltávolítása' gombot a lap alján.",
'removingchecked'      => 'A kért lapok eltávolítása a figyelőlistáról...',
'couldntremove'        => "'$1' nem távolítható el...",
'iteminvalidname'      => "Probléma a '$1' elemmel: érvénytelen név...",
'wlnote'               => 'Lentebb az utolsó <b>$2</b> óra $1 változtatása látható.',
'wlshowlast'           => 'Az elmúlt $1 órában | $2 napon | $3 történt változtatások legyenek láthatóak',
'wlsaved'              => 'Ez a figyelőlistád egy elmentett példánya.',
'watchlist-show-bots'  => 'Botok szerkesztéseinek megjelenítése',
'watchlist-hide-bots'  => 'Botok szerkesztéseinek elrejtése',
'watchlist-show-own'   => 'Saját szerkesztések megjelenítése',
'watchlist-hide-own'   => 'Saját szerkesztések elrejtése',
'watchlist-show-minor' => 'Apró módosítások megjelenítése',
'watchlist-hide-minor' => 'Apró módosítások elrejtése',
'wldone'               => 'Kész.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Figyelés...',
'unwatching' => 'Figyelés befejezése...',

# Delete/protect/revert
'deletepage'                  => 'Lap törlése',
'excontent'                   => "a lap tartalma: '$1'",
'excontentauthor'             => "a lap tartalma: '$1' (és csak '$2' szerkesztette)",
'exbeforeblank'               => 'az eltávolítás előtti tartalom: $1',
'exblank'                     => 'a lap üres volt',
'confirmdelete'               => 'Törlés megerősítése',
'deletesub'                   => '("$1" törlése)',
'historywarning'              => 'Figyelem: a lapnak, amit törölni készülsz, története van:',
'confirmdeletetext'           => 'Egy lap vagy kép teljes laptörténetével együtti végleges törlésére készülsz.  Kérlek, erősítsd meg, hogy valóban ezt szándékozod tenni, átlátod a következményeit, és a [[{{ns:project}}:Törlési irányelvek|törlési irányelvekkel]] összhangban cselekedsz.',
'actioncomplete'              => 'Művelet végrehajtva',
'deletedtext'                 => 'A(z) „$1” lapot törölted.  A legutóbbi törlések listájához lásd a $2 lapot.',
'deletedarticle'              => '"$1" törölve',
'dellogpage'                  => 'Törlési_napló',
'dellogpagetext'              => 'Itt láthatók a legutóbb törölt lapok.
Minden időpont a server órája (UTC) szerint értendő.',
'deletionlog'                 => 'törlési napló',
'reverted'                    => 'Visszaállítva a korábbi változatra',
'deletecomment'               => 'A törlés oka',
'rollbacklink'                => 'visszaállítás',
'cantrollback'                => 'Nem lehet visszaállítani: az utolsó szerkesztést végző felhasználó az egyetlen, aki a lapot szerkesztette.',
'alreadyrolled'               => '[[:$1]] utolsó, [[User:$2|$2]] ([[User talk:$2|Vita]] | [[Special:Contributions/$2|Szerkesztései]] | [[Special:blockip/$2|Blokkolás]]) általi szerkesztését nem lehet visszavonni: időközben valaki már visszavonta, vagy szerkesztette a lapot.

Az utolsó szerkesztést [[User:$3|$3]] ([[User talk:$3|vita]]) végezte.',
'editcomment'                 => 'A változtatás összefoglalója "<i>$1</i>" volt.', # only shown if there is an edit comment
'revertpage'                  => '[[Special:Contributions/$2|$2]] szerkesztései visszaállítva $1 utolsó változatára',
'protectlogpage'              => 'Lapvédelmi_napló',
'protectlogtext'              => 'Ez a lezárt/megnyitott lapok listája. 
A részleteket a [[{{ns:project}}:Lapvédelmi irányelvek|zárt lapok irányelve]] tartalmazza.',
'protectedarticle'            => 'levédte a(z) [[$1]] lapot',
'unprotectedarticle'          => 'eltávolította a védelmet a(z) "[[$1]]" lapról',
'protectsub'                  => '(„$1” levédése)',
'confirmprotect'              => 'Levédés megerősítése',
'protectcomment'              => 'A védelem oka',
'protectexpiry'               => 'Időtartam',
'unprotectsub'                => '(„$1” védelmének feloldása)',
'protect-unchain'             => 'Átnevezési jogok állítása külön',
'protect-text'                => 'Itt megtekintheted és módosíthatod a(z) [[$1]] lap védelmi szintjét. Légy szives, tartsd be a [[{{ns:project}}:Védett lapok|védett lapokkal kapcsolatos előírásokat]].',
'protect-cascadeon'           => 'A lap le van védve, mert tartalmazzák az alábbi lapok, amelyeken be van kapcsolva a kaszkád védelem. Ezen lap védelmi szintjének a megváltoztatása a kaszkád védelemre nincs hatással.',
'protect-default'             => '(alapértelmezett)',
'protect-level-autoconfirmed' => 'Csak regisztrált felhasználók',
'protect-level-sysop'         => 'Csak adminisztrátorok',
'protect-cascade'             => 'Kaszkád védelem – védjen le minden lapot, amit ez a lap tartalmaz.',

# Restrictions (nouns)
'restriction-edit' => 'Szerkesztés',
'restriction-move' => 'Átmozgatás',

# Undelete
'undelete'           => 'Törölt lap helyreállítása',
'undeletepage'       => 'Törölt lapok megtekintése és helyreállítása',
'viewdeletedpage'    => 'Törölt lapok megtekintése',
'undeletepagetext'   => 'Az alábbi lapokat törölték, de még helyreállíthatók az archívumból (az archívumot időről időre üríthetik!).',
'undeleteextrahelp'  => "A lap teljes helyreállításához ne jelölj be egy boxot sem, csak nyomj a '''''Helyreállítás!''''' gombra. A lap részleges helyreállításához jelöld be a kívánt szerkesztések melletti boxokat, és nyomj a '''''Helyreállítás!''''' gombra. Ha megnyomod a '''''Vissza''''' gombot, az törli a boxok és az összefoglaló jelenlegi tartalmát.",
'undeleterevisions'  => '$1 változat archiválva',
'undeletehistory'    => 'Ha helyreállítasz egy lapot, azzal visszahozod laptörténet összes változatát.  Ha lap törlése óta azonos néven már létrehoztak egy újabb lapot, a helyreállított változatok a laptörténet elejére kerülnek be, a jelenlegi lapváltozat módosítása nélkül.',
'undeletebtn'        => 'Helyreállítás!',
'undeletereset'      => 'Vissza',
'undeletecomment'    => 'Visszaállítás oka:',
'undeletedarticle'   => '"$1" helyreállítva',
'undeletedrevisions' => '$1 változat helyreállítva',
'cannotundelete'     => 'Nem lehet a lapot visszaállítani; lehet, hogy azt már valaki visszaállította.',
'undeletedpage'      => "<big>'''$1 helyreállítva'''</big>

Lásd a [[Special:Log/delete|törlési naplót]] a legutóbbi törlések és helyreállítások listájához.",

# Namespace form on various pages
'namespace' => 'Névtér:',
'invert'    => 'Kijelölés megfordítása',

# Contributions
'contributions' => 'Szerkesztő közreműködései',
'mycontris'     => 'Közreműködéseim',
'contribsub2'   => '$1 ($2) cikkhez',
'nocontribs'    => 'Nem találtam a feltételnek megfelelő módosítást.',
'ucnote'        => 'Lentebb <b>$1</b> módosításai láthatóak az elmúlt <b>$2</b> napban.',
'uctop'         => ' (utolsó)',

'sp-contributions-newest'      => 'Legfrissebb',
'sp-contributions-oldest'      => 'Legkorábbi',
'sp-contributions-newer'       => '$1 frissebb',
'sp-contributions-older'       => '$1 korábbi',
'sp-contributions-newbies-sub' => 'Új szerkesztők lapjai',
'sp-contributions-blocklog'    => 'Blokkolási napló',

'sp-newimages-showfrom' => 'Új képek mutatása $1 után',

# What links here
'whatlinkshere' => 'Mi hivatkozik erre',
'notargettitle' => 'Nincs cél',
'notargettext'  => 'Nem adtál meg lapot vagy usert keresési célpontnak.',
'linklistsub'   => '(Linkek )',
'linkshere'     => 'Az alábbi lapok hivatkoznak erre: [[:$1]]',
'nolinkshere'   => 'Erre a lapra semmi nem hivatkozik: [[:$1]]',
'isredirect'    => 'átirányítás',
'istemplate'    => 'beillesztve',

# Block/unblock
'blockip'             => 'Blokkolás',
'blockiptext'         => 'Az alábbi űrlap segítségével megvonhatod egy adott felhasználótól vagy egy adott IP-cím használójától az írási jogokat. Figyelj oda, hogy az intézkedés mindig az [[{{ns:project}}:Blokkolási irányelvek|irányelvek]] szerint történjen. Add meg a blokkolás okát is (például idézd a blokkolandó személy által vandalizált lapokat).

A blokkolás lejáratát GNU standard formátumban add meg, ennek a leírását megtalálod a [http://www.gnu.org/software/tar/manual/html_node/Date-input-formats.html tar kézikönyvében]. Néhány példa: „1 hour”, „2 days”, „next Wednesday”, „1 January 2017”. A blokkolás szólhat „indefinite” (határozatlan) vagy „infinite” (végtelen) időre is.

IP-tartományok blokkolásával kapcsolatban lásd a range blocks szócikket. Blokkolás megszüntetésére a [[Special:Ipblocklist|blokkolt IP címek listája]] oldalon van mód. A blokkok visszamenőleg megtekinthetőek a [[Special:Log/block|blokkolási naplóban]] is.',
'ipaddress'           => 'IP cím',
'ipadressorusername'  => 'IP cím vagy felhasználói név',
'ipbexpiry'           => 'Lejárat',
'ipbreason'           => 'Blokkolás oka',
'ipbanononly'         => 'Csak anonim felhasználók blokkolása',
'ipbcreateaccount'    => 'Új regisztráció megakadályozása',
'ipbenableautoblock'  => 'A szerkesztő által használt IP-címek automatikus blokkolása',
'ipbsubmit'           => 'Blokkolás',
'ipbother'            => 'Más időtartam',
'ipboptions'          => '2 óra:2 hours,1 nap:1 day,3 nap:3 days,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite',
'ipbotheroption'      => 'Más időtartam',
'badipaddress'        => 'Érvénytelen IP cím',
'blockipsuccesssub'   => 'Sikeres blokkolás',
'blockipsuccesstext'  => '„[[{{ns:Special}}:Contributions/$1|$1]]” felhasználót blokkoltad. <br />Lásd a [[{{ns:Special}}:Ipblocklist|blokkolt IP címek listáját]] az érvényben lévő blokkok áttekintéséhez.',
'ipb-unblock-addr'    => '$1 blokkjának feloldása',
'ipb-blocklist-addr'  => '$1 aktív blokkjainak megtekintése',
'ipusubmit'           => 'Blokk feloldása',
'unblocked'           => '[[User:$1|$1]] blokkolása feloldva',
'ipblocklist'         => 'Blokkolt IP címek listája',
'ipblocklist-summary' => 'Lásd még a [[Special:Log/block|blokkolási naplót]].',
'blocklistline'       => '$1, $2 blokkolta $3 felhasználót (lejárat: $4)',
'anononlyblock'       => 'csak anon.',
'createaccountblock'  => 'új felhasználó létrehozása blokkolva',
'blocklink'           => 'Blokkolás',
'unblocklink'         => 'blokk feloldása',
'contribslink'        => 'Szerkesztései',
'autoblocker'         => "Az általad használt IP-cím autoblokkolva van, mivel korábban a kitiltott „[[User:$1|$1]]” használta. ($1 blokkolásának indoklása: „'''$2'''”) Ha nem te vagy $1, lépj kapcsolatba valamelyik adminisztrátorral, és kérd az autoblokk feloldását. Ne felejtsd el megírni neki, hogy kinek szóló blokkba ütköztél bele!",
'blocklogpage'        => 'Blokkolási_napló',
'blocklogentry'       => '"$1" blokkolva $2 $3 időtartamra',
'blocklogtext'        => 'Ez a felhasználókra helyezett blokkoknak és azok feloldásának listája. Az IP autoblokkok nem szerepelnek a listában. Lásd még [[Special:Ipblocklist|a jelenleg életben lévő blokkok listáját]].',
'unblocklogentry'     => '"$1" blokkolása feloldva',
'ipb_expiry_invalid'  => 'Hibás lejárati dátum.',
'ipb_already_blocked' => '"$1" már blokkolva',
'proxyblockreason'    => "Az IP címed ''open proxy'' probléma miatt le van tiltva. Vedd fel a kapcsolatot egy informatikussal vagy az internet szolgáltatóddal ezen súlyos biztonsági probléma ügyében.",
'proxyblocksuccess'   => 'Kész.',

# Developer tools
'databasenotlocked' => 'Az adatbázis nincs lezárva.',

# Move page
'newtitle'                => 'Az új névre',
'pagemovedsub'            => 'Átnevezés sikeres',
'pagemovedtext'           => "A(z) „[[$1]]” lapot átneveztem a(z) „[[$2]]” névre.

'''Kérlek, [[{{ns:Special}}:Whatlinkshere/$2|ellenőrizd]]''', hogy az átnevezés nem hozott-e létre [[{{ns:Special}}:DoubleRedirects|dupla átirányításokat]], és javítsd őket, ha szükséges.",
'articleexists'           => 'Ilyen névvel már létezik lap, vagy az általad
választott név érvénytelen.
Kérlek, válassz egy másik nevet.

Ha már létezik ilyen nevű lap, akkor kérd annak törlését a [[{{ns:project}}:Azonnali törlés]] lapon.',
'talkexists'              => 'A lap átmozgatása sikerült, de a hozzá tartozó
vitalapot nem tudtam átmozgatni mert már létezik egy egyező nevű
lap az új helyen. Kérlek gondoskodj a két lap összefűzéséről.',
'movetalk'                => 'Nevezd át a vitalapot is, ha lehetséges.',
'talkpagemoved'           => 'Az oldal vitalapját is átmozgattam.',
'talkpagenotmoved'        => 'Az oldal vitalapja <strong>nem került</strong> átmozgatásra.',
'1movedto2'               => '[[$1]] átnevezve [[$2]] névre',
'1movedto2_redir'         => '[[$1]] átnevezve [[$2]] névre (átirányítást felülírva)',
'movereason'              => 'Indoklás',
'revertmove'              => 'visszaállítás',
'delete_and_move'         => 'Törlés és átnevezés',
'delete_and_move_text'    => '== Törlés szükséges ==

Az átnevezés céljaként megadott „[[$1]]” szócikk már létezik.  Ha az átnevezést végre akarod hajtani, ezt a lapot törölni kell.  Valóban ezt szeretnéd?',
'delete_and_move_confirm' => 'Igen, töröld a lapot',
'delete_and_move_reason'  => 'átnevezendő lap célneve felszabadítva',
'selfmove'                => 'A cikk jelenlegi címe megegyezik azzal, amire át szeretnéd mozgatni. Egy szócikket saját magára mozgatni nem lehet.',

# Export
'export'        => 'Lapok exportálása',
'exporttext'    => 'Egy adott lap vagy lapcsoport szövegét és laptörténetét exportálhatod XML-be. A kapott fájlt importálhatod egy másik MediaWiki alapú rendszerbe a Special:Import lapon keresztül.

Lapok exportálásához add meg a címüket a lenti szövegdobozban (minden címet külön sorba), és válaszd ki, hogy az összes korábbi változatra és a teljes laptörténetekre szükséged van-e, vagy csak az aktuális változatok és a legutolsó változtatásokra vonatkozó információk kellenek.

Az utóbbi esetben közvetlen linket is használhatsz, például a [[Special:Export/{{MediaWiki:Mainpage}}]] a [[{{MediaWiki:Mainpage}}]] nevű lapot exportálja.',
'exportcuronly' => 'Csak a legfrissebb állapot, teljes laptörténet nélkül',

# Namespace 8 related
'allmessages'               => 'Rendszerüzenetek',
'allmessagesname'           => 'Név',
'allmessagesdefault'        => 'Alapértelmezett szöveg',
'allmessagescurrent'        => 'Jelenlegi szöveg',
'allmessagestext'           => 'Ez a MediaWiki [[{{ns:project}}:Névtér|névtérben]] elérhető összes üzenet listája.',
'allmessagesnotsupportedUI' => "A felhasználói felületedhez jelenleg megadott nyelvet (<b>$1</b>) ezen a wikin a ''Special:Allmessages'' nem támogatja.",
'allmessagesnotsupportedDB' => "A '''''Special:Allmessages''''' lap nem használható, mert a '''\$wgUseDatabaseMessages''' ki van kapcsolva.",
'allmessagesfilter'         => 'Üzenetnevek szűrése:',
'allmessagesmodified'       => 'Csak a módosítottak mutatása',

# Thumbnails
'thumbnail-more'  => 'Nagyít',
'missingimage'    => '<b>Hiányzó kép</b><br /><i>$1</i>',
'thumbnail_error' => 'Hiba az indexkép létrehozásakor: $1',

# Special:Import
'import'          => 'Lapok importálása',
'importnosources' => 'Nincsenek transzwikiimport-források definiálva, a közvetlen laptörténet-felküldés pedig nem megengedett.',

# Import log
'importlogpage' => 'Importnapló',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'A felhasználói lapod',
'tooltip-pt-anonuserpage'         => 'Az általad használt IP címhez tartozó felhasználói lap',
'tooltip-pt-mytalk'               => 'A vitalapod',
'tooltip-pt-anontalk'             => 'Az általad használt IP címről végrehajtott szerkesztések megvitatása',
'tooltip-pt-preferences'          => 'A beállításaid',
'tooltip-pt-watchlist'            => 'Az általad figyelemmel kísért oldalak utolsó változtatásai',
'tooltip-pt-mycontris'            => 'A közreműködéseid listája',
'tooltip-pt-login'                => 'Bejelentkezni javasolt, de nem kötelező.',
'tooltip-pt-anonlogin'            => 'Bejelentkezni javasolt, de nem kötelező.',
'tooltip-pt-logout'               => 'Kijelentkezés',
'tooltip-ca-talk'                 => 'Az oldal tartalmának megvitatása',
'tooltip-ca-edit'                 => 'Te is szerkesztheted ezt az oldalt. Mielőtt elmentenéd, használd az előnézetet.',
'tooltip-ca-addsection'           => 'Újabb fejezet nyitása a vitában.',
'tooltip-ca-viewsource'           => 'Ez egy védett lap. Ide kattintva megnézheted a forrását.',
'tooltip-ca-history'              => 'A lap korábbi változatai',
'tooltip-ca-protect'              => 'Lap levédése',
'tooltip-ca-delete'               => 'Lap törlése',
'tooltip-ca-undelete'             => 'Törölt lapváltozatok visszaállítása',
'tooltip-ca-move'                 => 'Lap átmozgatása',
'tooltip-ca-watch'                => 'Lap hozzáadása a figyelőlistádhoz',
'tooltip-ca-unwatch'              => 'Lap eltávolítása a figyelőlistádról',
'tooltip-search'                  => 'Keresés a wikiben',
'tooltip-p-logo'                  => 'Kezdőlap',
'tooltip-n-mainpage'              => 'Kezdőlap megtekintése',
'tooltip-n-portal'                => 'A közösségről, miben segíthetsz, mit hol találsz meg',
'tooltip-n-currentevents'         => 'Háttérinformáció az aktuális eseményekről',
'tooltip-n-recentchanges'         => 'A wikin történt legutóbbi változtatások listája',
'tooltip-n-randompage'            => 'Egy véletlenszerűen kiválasztott lap betöltése',
'tooltip-n-help'                  => 'Ha bármi problémád van...',
'tooltip-n-sitesupport'           => 'Támogass minket!',
'tooltip-t-whatlinkshere'         => 'Az erre a lapra hivatkozó más lapok listája',
'tooltip-t-recentchangeslinked'   => 'Az erről a lapról hivatkozott lapok utolsó változtatásai',
'tooltip-feed-rss'                => 'A lap tartalma RSS feed formájában',
'tooltip-feed-atom'               => 'A lap tartalma Atom feed formájában',
'tooltip-t-contributions'         => 'A felhasználó közreműködéseinek listája',
'tooltip-t-emailuser'             => 'Írj levelet ennek a felhasználónak!',
'tooltip-t-upload'                => 'Képek vagy egyéb fájlok feltöltése',
'tooltip-t-specialpages'          => 'Az összes speciális lap listája',
'tooltip-ca-nstab-main'           => 'Lap megtekintése',
'tooltip-ca-nstab-user'           => 'Felhasználói lap megtekintése',
'tooltip-ca-nstab-media'          => 'Fájlleíró lap megtekintése',
'tooltip-ca-nstab-special'        => 'Ez egy speciális lap, nem lehet szerkeszteni.',
'tooltip-ca-nstab-project'        => 'Projekt lap megtekintése',
'tooltip-ca-nstab-image'          => 'Képleíró lap megtekintése',
'tooltip-ca-nstab-mediawiki'      => 'Rendszerüzenet megtekintése',
'tooltip-ca-nstab-template'       => 'Sablon megtekintése',
'tooltip-ca-nstab-help'           => 'Segítő lap megtekintése',
'tooltip-ca-nstab-category'       => 'Kategória megtekintése',
'tooltip-minoredit'               => 'Szerkesztés megjelölése apróként',
'tooltip-save'                    => 'A változtatásaid elmentése',
'tooltip-preview'                 => 'Mielőtt elmentenéd a lapot, ellenőrizd, biztosan úgy néz-e ki, ahogy szeretnéd!',
'tooltip-diff'                    => 'Nézd meg, milyen változtatásokat végeztél eddig a szövegen',
'tooltip-compareselectedversions' => 'A két kiválasztott változat közötti eltérések megjelenítése',
'tooltip-watch'                   => 'Lap hozzáadása a figyelőlistádhoz',

# Stylesheets
'common.css'   => '/* Közös CSS az összes skinnek */',
'monobook.css' => '/*
Közös (skinfüggetlen) css: [[MediaWiki:Common.css]]*/',

# Attribution
'anonymous'        => 'Névtelen {{SITENAME}}-felhasználó(k)',
'siteuser'         => '$1 wiki felhasználó',
'lastmodifiedatby' => 'Ezt a lapot utoljára $3 módosította $2, $1 időpontban.', # $1 date, $2 time, $3 user
'and'              => 'és',
'siteusers'        => '$1 wiki felhasználó(k)',

# Spam protection
'spamprotectiontitle'    => 'Spamszűrő',
'spamprotectiontext'     => 'Az általad elmenteni kívánt lap fennakadt a spamszűrőn. Ezt valószínűleg egy külső weblapra történő hivatkozás okozta. Ha úgy érzed, tévedés történt, kérd a lap spamszűrőből való kivételét [[{{ns:project}}:Adminisztrátorok üzenőfala|az adminisztrátorok üzenőfalán]].',
'spamprotectionmatch'    => 'A spamszűrőn az alábbi szöveg fennakadt: $1',
'subcategorycount'       => 'Ebben a kategóriában $1 alkategória található.',
'categoryarticlecount'   => 'A kategória lenti listájában $1 szócikk található.',
'category-media-count'   => '{{PLURAL:$1|Egy fájl|$1 darab fájl}} található ebben a kategóriában.',
'listingcontinuesabbrev' => ' folyt.',

# Math options
'mw_math_png'    => 'Mindig készítsen PNG-t',
'mw_math_simple' => 'HTML, ha nagyon egyszerű, egyébként PNG',
'mw_math_html'   => 'HTML, ha lehetséges, egyébként PNG',
'mw_math_source' => 'Hagyja TeX formában (szöveges böngészőknek)',
'mw_math_modern' => 'Modern böngészőknek ajánlott beállítás',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'    => 'Ellenőrzöttnek jelölöd',
'markaspatrolledtext'    => 'Ezt a cikket ellenőrzöttnek jelölöd',
'markedaspatrolled'      => 'Ellenőrzöttnek jelölve',
'markedaspatrolledtext'  => 'A kiválasztott változatot ellenőrzöttnek jelölted.',
'rcpatroldisabled'       => 'A Friss Változtatások Ellenőrzése kikapcsolva',
'rcpatroldisabledtext'   => 'A Friss Változtatások Ellenőrzése jelenleg nincs engedélyezve.',
'markedaspatrollederror' => 'Nem lehet ellenőrzöttnek jelölni',

# Image deletion
'deletedrevision' => 'Törölted $1 egy régebbi változatát.',

# Browsing diffs
'previousdiff' => '‹ Előző változtatások',
'nextdiff'     => 'Következő változtatások ›',

# Media information
'imagemaxsize' => 'A képlapokon mutatott maximális képméret:',
'thumbsize'    => 'Indexkép mérete:',

'newimages' => 'Új képek galériája',

'passwordtooshort' => 'Túl rövid a jelszavad. Legalább $1 karakterből kell állnia.',

# Metadata
'metadata'          => 'Metaadatok',
'metadata-help'     => 'Ez a kép járulékos adatokat tartalmaz, amelyek feltehetően a kép létrehozásához használt digitális fényképezőgép vagy lapolvasó beállításairól adnak tájékoztatást.  Ha a képet az eredetihez képest módosították, ezen adatok eltérhetnek a kép tényleges jellemzőitől.',
'metadata-expand'   => 'További képadatok',
'metadata-collapse' => 'További képadatok elrejtése',

# EXIF tags
'exif-imagewidth'                => 'Szélesség',
'exif-imagelength'               => 'Magasság',
'exif-compression'               => 'Tömörítési séma',
'exif-photometricinterpretation' => 'Színösszetevők',
'exif-samplesperpixel'           => 'Színösszetevők száma',
'exif-planarconfiguration'       => 'Adatok csoportosítása',
'exif-stripoffsets'              => 'Csík ofszet',
'exif-rowsperstrip'              => 'Egy csíkban levő sorok száma',
'exif-stripbytecounts'           => 'Bájt/csík',
'exif-datetime'                  => 'Utolsó változtatás ideje',
'exif-make'                      => 'Fényképezőgép gyártója',
'exif-model'                     => 'Fényképezőgép típusa',
'exif-software'                  => 'Használt szoftver',
'exif-datetimeoriginal'          => 'EXIF információ létrehozásának dátuma',
'exif-exposuretime'              => 'Expozíciós idő',
'exif-focallength'               => 'Fókusztávolság',

'exif-planarconfiguration-1' => 'Egyben',

# External editor support
'edit-externally'      => 'A file szerkesztése külső alkalmazással',
'edit-externally-help' => 'Lásd a [http://meta.wikimedia.org/wiki/Help:External_editors „setup instructions”] leírást (angolul) ennek használatához.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'összes',
'imagelistall'     => 'összes',
'watchlistall1'    => 'összes',
'watchlistall2'    => 'bármikor',
'namespacesall'    => 'Összes',

# E-mail address confirmation
'confirmemail'            => 'E-mail cím megerősítése',
'confirmemail_noemail'    => 'Nincs érvényes e-mail cím megadva a [[Special:Preferences|beállításaidnál]].',
'confirmemail_text'       => 'Ennek a wikinek a használatához meg kell erősítened az e-mail címed, mielőtt használni kezded a levelezési rendszerét. Nyomd meg az alsó gombot, hogy kaphass egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot. Töltsd be a kódot a böngésződbe, hogy aktiválhasd az e-mail címedet. Köszönjük!',
'confirmemail_send'       => 'Küldd el a kódot',
'confirmemail_sent'       => 'Kaptál egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot.',
'confirmemail_oncreate'   => 'A megerősítő kódot elküldtük az e-mail címedre.
Ez a kód nem szükséges a belépéshez, de meg kell adnod mielőtt a wiki e-mail alapú szolgáltatásait igénybe veheted.',
'confirmemail_sendfailed' => 'Nem tudjuk elküldeni a megerősítéshez szükséges e-mailt. Kérünk, ellenőrizd a címet. $1',
'confirmemail_invalid'    => 'Nem megfelelő kód. A kódnak lehet, hogy lejárt a felhasználhatósági ideje.',
'confirmemail_success'    => 'Az e-mail címed megerősítve. Most már beléphetsz a wikibe.',
'confirmemail_loggedin'   => 'E-mail címed megerősítve.',
'confirmemail_error'      => 'Hiba az e-mail címed megerősítése során.',
'confirmemail_subject'    => '{{SITENAME}} e-mail cím megerősítés',
'confirmemail_body'       => 'Valaki, valószínűleg te, a $1 IP címről regisztrált a "$2" azonosítóval, ezzel az e-maillel. 

Annak érdekében, hogy megerősítsd, ez az azonosító valóban hozzád tartozik, és aktiválni szeretnéd az e-mail címedet, nyisd meg az alábbi linket a böngésződben:

$3

Ha ez *nem* te vagy, ne kattints a linkre. Ennek a megerősítésre szánt kódnak a felhasználhatósági ideje lejár: $4.',

# Inputbox extension, may be useful in other contexts as well
'searchfulltext' => 'Teljes szöveg keresése',

# HTML dump
'redirectingto' => 'Átirányítás a következőre: [[:$1|$1]]...',

'searchcontaining' => "''$1''-t tartalmazó lapokra keresés.",
'searchnamed'      => "''$1'' című lapok keresése.",
'articletitles'    => "''$1'' kezdetű szócikkek",
'hideresults'      => 'Eredmények elrejtése',

# Auto-summaries
'autosumm-blank'   => 'A lap teljes tartalmának eltávolítása',
'autosumm-replace' => 'A lap tartalmának cseréje erre: $1',
'autoredircomment' => 'Átirányítás ide:[[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Új oldal, tartalma: „$1”',

);


