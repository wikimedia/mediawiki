<?php
/** Hungarian (Magyar)
 *
 * @addtogroup Language
 *
 * @author Bdanee
 * @author Bdamokos
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
	'standard'    => 'Klasszikus',
	'nostalgia'   => 'Nosztalgia',
	'cologneblue' => 'Kölni kék',
	'monobook'    => 'MonoBook',
	'myskin'      => 'MySkin',
	'chick'       => 'Chick',
	'simple'      => 'Egyszerű'
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
	'Statistics'                => array( 'Statisztika' ),
	'Randompage'                => array( 'Lap_találomra' ),
	'Lonelypages'               => array( 'Magányos_lapok' ),
	'Uncategorizedpages'        => array( 'Kategorizálatlan_lapok' ),
	'Uncategorizedcategories'   => array( 'Kategorizálatlan_kategóriák'),
	'Uncategorizedimages'       => array( 'Kategorizálatlan_képek', 'Kategorizálatlan_fájlok' ),
	'Uncategorizedtemplates'    => array( 'Kategorizálatlan_sablonok' ),
	'Unusedcategories'          => array( 'Nem_használt_kategóriák' ),
	'Unusedimages'              => array( 'Nem_használt_képek' ),
	'Wantedpages'               => array( 'Keresett_lapok' ),
	'Wantedcategories'          => array( 'Keresett_kategóriák' ),
	'Mostlinked'                => array( 'Legtöbbet_hivatkozott_lapok' ),
	'Mostlinkedcategories'      => array( 'Legtöbbet_hivatkozott_kategóriák' ),
	'Mostlinkedtemplates'       => array( 'Legtöbbet_hivatkozott_sablonok' ),
	'Mostcategories'            => array( 'Legtöbb_kategóriába_tartozó_lapok' ),
	'Mostimages'                => array( 'Legtöbbet_használt_képek' ),
	'Mostrevisions'             => array( 'Legtöbbet_szerkesztett_lapok' ),
	'Fewestrevisions'           => array( 'Legkevesebb_javítások' ),
	'Shortpages'                => array( 'Rövid_lapok' ),
	'Longpages'                 => array( 'Hosszú_lapok' ),
	'Newpages'                  => array( 'Új_lapok' ),
	'Ancientpages'              => array( 'Régóta_nem_változott_szócikkek' ),
	'Deadendpages'              => array( 'Zsákutcalapok' ),
	'Protectedpages'            => array( 'Védett_oldalak' ),
	'Allpages'                  => array( 'Az_összes_lap_listája' ),
	'Prefixindex'               => array( 'Egy_névtérbe_tartozó_lapok_listája' ) ,
	'Ipblocklist'               => array( 'Blokkolt_IP_lista' ),
	'Specialpages'              => array( 'Speciális_lapok' ),
	'Contributions'             => array( 'Szerkesztő_közreműködései' ),
	'Emailuser'                 => array( 'E-mail_küldése', 'E-mail_küldése_ezen_szerkesztőnek' ),
	'Whatlinkshere'             => array( 'Mi_hivatkozik_erre' ),
	'Recentchangeslinked'       => array( 'Kapcsolódó_változtatások' ),
	'Movepage'                  => array( 'Lap_áthelyezés' ),
	'Blockme'                   => array( 'Blokkolj' ),
	'Booksources'               => array( 'Könyvforrások' ),
	'Categories'                => array( 'Kategóriák' ),
	'Export'                    => array( 'Exportálás' ),
	'Version'                   => array( 'Névjegy', 'Verziószám' ),
	'Allmessages'               => array( 'Rendszerüzenetek' ),
	'Log'                       => array( 'Napló' ),
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
	'Popularpages'              => array( 'Népszerű_oldalak' ),
	'Search'                    => array( 'Keresés' ),
	'Resetpass'                 => array( 'Jelszócsere' ),
	'Withoutinterwiki'          => array( 'Belső_wiki_nélkül' ),
);
$datePreferences = array(
	'ymd',
	'ISO 8601',
);

$defaultDateFormat = 'ymd';

$dateFormats = array(
	'ymd time' => 'H:i',
	'ymd date' => 'Y. F j.',
	'ymd both' => 'Y. F j., H:i',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$linkTrail = '/^([a-záéíóúöüőűÁÉÍÓÚÖÜŐŰ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Hivatkozások aláhúzása:',
'tog-highlightbroken'         => 'Megszakadt hivatkozások <a href="" class="new">így</a> (alternatíva: így<a href="" class="internal">?</a>).',
'tog-justify'                 => 'A bekezdések teljes szélességű tördelése („sorkizárás”)',
'tog-hideminor'               => 'A kisebb változtatások elrejtése a Friss változtatások lapon',
'tog-extendwatchlist'         => 'A figyelőlista kiterjesztése minden változtatásra (ne csak az utolsót mutassa)',
'tog-usenewrc'                => 'Modern változások listája (nem minden böngészőre)',
'tog-numberheadings'          => 'A címsorok automatikus számozása',
'tog-showtoolbar'             => 'A szerkesztő eszköztár megjelenítése (JavaScript)',
'tog-editondblclick'          => 'A lapok szerkesztése dupla kattintásra (JavaScript)',
'tog-editsection'             => 'Hivatkozások az egyes szakaszok szerkesztéséhez',
'tog-editsectiononrightclick' => 'Egyes szakaszok szerkesztése a szakaszcímre kattintással (JavaScript)',
'tog-showtoc'                 => 'A három fejezetnél többel rendelkező cikkeknél mutasson tartalomjegyzéket',
'tog-rememberpassword'        => 'Emlékezzen rám ezen a számítógépen',
'tog-editwidth'               => 'A szerkesztőablak teljes szélességű',
'tog-watchcreations'          => 'Az általam létrehozott lapok felvétele a figyelőlistámra',
'tog-watchdefault'            => 'Az általam szerkesztett lapok felvétele a figyelőlistámra',
'tog-watchmoves'              => 'Az általam áthelyezett lapok felvétele a figyelőlistámra',
'tog-watchdeletion'           => 'Az általam törölt lapok felvétele a figyelőlistámra',
'tog-minordefault'            => 'Az összes szerkesztés alapértelmezésként megjelölése kisebbként',
'tog-previewontop'            => 'Előnézet a szerkesztőablak előtt és nem utána',
'tog-previewonfirst'          => 'Előnézet az első szerkesztéskor',
'tog-nocache'                 => 'A lapok gyorstárazásának letiltása',
'tog-enotifwatchlistpages'    => 'Kérek értesítést e-mailben, ha az általam figyelt lap megváltozik',
'tog-enotifusertalkpages'     => 'Kérek értesítést e-mailben, ha a vitalapom megváltozik',
'tog-enotifminoredits'        => 'Kérek értesítést e-mailben a lapok kisebb módosításairól is',
'tog-enotifrevealaddr'        => 'Az e-mail címem nyilvánosságra hozása az értesítő e-mailekben',
'tog-shownumberswatching'     => 'Az oldalt figyelő felhasználók számának megjelenítése',
'tog-fancysig'                => 'Aláírás automatikus hivatkozás nélkül',
'tog-externaleditor'          => 'Külső szerkesztőprogram alapértelmezésként történő használata',
'tog-externaldiff'            => 'Külső különbségképző (diff) program használata alapértelmezésként',
'tog-showjumplinks'           => 'Helyezzen el hivatkozást („Ugrás”) a beépített eszköztárra',
'tog-uselivepreview'          => 'Élő előnézet használata (JavaScript) (Kísérleti)',
'tog-forceeditsummary'        => 'Figyelmeztessen, ha üresen hagyom a szerkesztés összegzését',
'tog-watchlisthideown'        => 'A saját szerkesztéseim elrejtése a figyelőlistáól',
'tog-watchlisthidebots'       => 'A robotok szerkesztéseinek elrejtése a figyelőlistáról',
'tog-watchlisthideminor'      => 'A kisebb változtatások elrejtése a figyelőlistáról',
'tog-nolangconversion'        => 'A változások átalakításának letiltása',
'tog-ccmeonemails'            => 'Kérek én is másolatot a másoknak küldött e-mailekről',
'tog-diffonly'                => 'Nem látható az oldal tartalma az eltérések alatt',

'underline-always'  => 'Mindig',
'underline-never'   => 'Soha',
'underline-default' => 'A böngésző alapértelmezése szerint',

'skinpreview' => '(előnézet)',

# Dates
'sunday'        => 'vasárnap',
'monday'        => 'hétfő',
'tuesday'       => 'kedd',
'wednesday'     => 'szerda',
'thursday'      => 'csütörtök',
'friday'        => 'péntek',
'saturday'      => 'szombat',
'sun'           => 'Vas',
'mon'           => 'Hét',
'tue'           => 'Kedd',
'wed'           => 'Sze',
'thu'           => 'Csü',
'fri'           => 'péntek',
'sat'           => 'Szo',
'january'       => 'január',
'february'      => 'február',
'march'         => 'március',
'april'         => 'április',
'may_long'      => 'május',
'june'          => 'június',
'july'          => 'július',
'august'        => 'augusztus',
'september'     => 'szeptember',
'october'       => 'október',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'január',
'february-gen'  => 'Február',
'march-gen'     => 'Március',
'april-gen'     => 'április',
'may-gen'       => 'május',
'june-gen'      => 'június',
'july-gen'      => 'július',
'august-gen'    => 'augusztus',
'september-gen' => 'szeptember',
'october-gen'   => 'október',
'november-gen'  => 'november',
'december-gen'  => 'december',
'jan'           => 'jan',
'feb'           => 'febr',
'mar'           => 'márc',
'apr'           => 'ápr',
'may'           => 'Máj',
'jun'           => 'Jún',
'jul'           => 'Júl',
'aug'           => 'aug',
'sep'           => 'szep',
'oct'           => 'Okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Bits of text used by many pages
'categories'            => 'Kategóriák',
'pagecategories'        => '{{PLURAL:$1|Kategória|Kategóriák}}',
'category_header'       => '„$1” kategória szócikkei',
'subcategories'         => 'Alkategóriák',
'category-media-header' => '„$1” kategóriába tartozó médiafájlok',
'category-empty'        => "''Ez a kategória jelenleg nem tartalmaz se szócikket, se médiát.''",

'mainpagetext'      => "<big>'''A MediaWiki telepítése sikerült.'''</big>",
'mainpagedocfooter' => "Ha segítségre van szükséged a wikiszoftver használatához, akkor keresd fel a [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] címet.

== Getting started ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Névjegy',
'article'        => 'Szócikk',
'newwindow'      => '(új ablakban nyílik meg)',
'cancel'         => 'Mégse',
'qbfind'         => 'Keresés',
'qbbrowse'       => 'Böngészés',
'qbedit'         => 'Szerkesztés',
'qbpageoptions'  => 'Lapbeállítások',
'qbpageinfo'     => 'Lapinformáció',
'qbmyoptions'    => 'Lapjaim',
'qbspecialpages' => 'Speciális lapok',
'moredotdotdot'  => 'Több...',
'mypage'         => 'Lapom',
'mytalk'         => 'Vitám',
'anontalk'       => 'az IP-cím vitalapja',
'navigation'     => 'Navigáció',

# Metadata in edit box
'metadata_help' => 'Metaadatok:',

'errorpagetitle'    => 'Hiba',
'returnto'          => 'Vissza a $1 cikkhez.',
'tagline'           => 'A {{SITENAME}}ből',
'help'              => 'Súgó',
'search'            => 'Keresés',
'searchbutton'      => 'Keresés',
'go'                => 'Menj',
'searcharticle'     => 'Menj',
'history'           => 'Laptörténet',
'history_short'     => 'Laptörténet',
'updatedmarker'     => 'az utolsó látogatásom óta frissítették',
'info_short'        => 'Információ',
'printableversion'  => 'Nyomtatható változat',
'permalink'         => 'Állandó hivatkozás',
'print'             => 'Nyomtatás',
'edit'              => 'Szerkesztés',
'editthispage'      => 'Lap szerkesztése',
'delete'            => 'Törlés',
'deletethispage'    => 'Lap törlése',
'undelete_short'    => '$1 szerkesztés visszavonása',
'protect'           => 'Védelem',
'protect_change'    => 'védelem módosítása',
'protectthispage'   => 'Lap védelme',
'unprotect'         => 'Védelem ki',
'unprotectthispage' => 'Lapvédelem megszüntetése',
'newpage'           => 'Új lap',
'talkpage'          => 'Lap megbeszélése',
'talkpagelinktext'  => 'Vita',
'specialpage'       => 'Speciális lap',
'personaltools'     => 'Személyes eszközök',
'postcomment'       => 'Megjegyzés beküldése',
'articlepage'       => 'Szócikk megtekintése',
'talk'              => 'Vitalap',
'views'             => 'Nézetek',
'toolbox'           => 'Eszköztár',
'userpage'          => 'Felhasználói lap megtekintése',
'projectpage'       => 'Projektlap megtekintése',
'imagepage'         => 'Képlap megtekintése',
'mediawikipage'     => 'Üzenetlap megtekintése',
'templatepage'      => 'Sablonlap megtekintése',
'viewhelppage'      => 'Súgólap megtekintése',
'categorypage'      => 'Kategórialap megtekintése',
'viewtalkpage'      => 'Beszélgetés megtekintése',
'otherlanguages'    => 'Más nyelveken',
'redirectedfrom'    => '($1 szócikkből átirányítva)',
'redirectpagesub'   => 'Átirányító lap',
'lastmodifiedat'    => 'A lap utolsó módosítása: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Ezt a lapot {{PLURAL:$1|egy|$1}} alkalommal keresték föl.',
'protectedpage'     => 'Védett lap',
'jumpto'            => 'Ugrás:',
'jumptonavigation'  => 'navigáció',
'jumptosearch'      => 'keresés',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'A {{SITENAME}}ről',
'aboutpage'         => 'Project:Rólunk',
'bugreports'        => 'Hibabejelentések',
'bugreportspage'    => 'Project:Hibabejelentések',
'copyright'         => 'A tartalom a(z) $1 alatt lelhető.',
'copyrightpage'     => '{{ns:project}}:Copyright',
'currentevents'     => 'Aktuális események',
'currentevents-url' => 'Project:Aktuális események',
'disclaimers'       => 'Felelősséget kizáró nyilatkozat',
'disclaimerpage'    => 'Project:Felelősséget kizáró nyilatkozat',
'edithelp'          => 'Szerkesztési súgó',
'edithelppage'      => 'Help:Szerkesztés',
'faq'               => 'GYIK',
'faqpage'           => 'Project:GYIK',
'helppage'          => 'Help:Tartalom',
'mainpage'          => 'Kezdőlap',
'policy-url'        => 'Project:Nyilatkozat',
'portal'            => 'Közösségi portál',
'portal-url'        => 'Project:Közösségi portál',
'privacy'           => 'Adatvédelmi nyilatkozat',
'privacypage'       => 'Project:Adatvédelmi nyilatkozat',
'sitesupport'       => 'Adományok',
'sitesupport-url'   => 'Project:Webhely támogatása',

'badaccess'        => 'Engedélyezési hiba',
'badaccess-group0' => 'Az általad kért művelet végrehajtása a számodra nem engedélyezett.',
'badaccess-group1' => 'Az általad kért művelet végrehajtása a(z) $1 csoportba tartozó felhasználók számára engedélyezett.',
'badaccess-group2' => 'Az általad kért művelet végrehajtása a(z) $1 csoportok valamelyikébe tartozó felhasználók számára engedélyezett.',
'badaccess-groups' => 'Az általad kért művelet végrehajtása a(z) $1 csoportok valamelyikébe tartozó felhasználók számára engedélyezett.',

'versionrequired'     => 'A MediaWiki $1-s verziója szükséges',
'versionrequiredtext' => 'A MediaWiki $1-s verziójára van szükség ennek a lapnak a használatához. Lásd a [[Special:Version|version page]] lapot.',

'retrievedfrom'           => 'A lap eredeti címe "$1"',
'youhavenewmessages'      => '$1 van. ($2)',
'newmessageslink'         => 'Új üzeneted',
'newmessagesdifflink'     => 'utolsó változtatás',
'youhavenewmessagesmulti' => 'Új üzeneteid érkeztek a(z) $1 laphoz',
'editsection'             => 'szerkesztés',
'editold'                 => 'szerkesztés',
'editsectionhint'         => 'Szakasz szerkesztése: $1',
'toc'                     => 'Tartalomjegyzék',
'showtoc'                 => 'megjelenítés',
'hidetoc'                 => 'elrejtés',
'thisisdeleted'           => '$1 megtekintése vagy visszaállítása?',
'viewdeleted'             => 'Megtekinted a(z) $1 lapot?',
'restorelink'             => '{{PLURAL:$1|egy|$1}} törölt szerkesztés',
'feedlinks'               => 'Hírcsatorna:',
'feed-invalid'            => 'Érvénytelen a figyelt hírcsatorna típusa.',
'site-rss-feed'           => '$1 RSS csatorna',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Cikk',
'nstab-user'      => 'User lap',
'nstab-media'     => 'Médialap',
'nstab-special'   => 'Speciális',
'nstab-project'   => 'Projektlap',
'nstab-image'     => 'Kép',
'nstab-mediawiki' => 'Üzenet',
'nstab-template'  => 'Sablon',
'nstab-help'      => 'Súgólap',
'nstab-category'  => 'Kategória',

# Main script and global functions
'nosuchaction'      => 'Nincs ilyen tevékenység',
'nosuchactiontext'  => 'Az URL által megadott tevékenységet
a wiki nem ismeri föl',
'nosuchspecialpage' => 'Nincs ilyen speciális lap',
'nospecialpagetext' => "<big>'''Érvénytelen speciális lapot kértél.'''</big>

Az érvényes speciális lapok listáját a [[Special:Specialpages|Speciális lapok]] címen találod.",

# General errors
'error'                => 'Hiba',
'databaseerror'        => 'Adatbázishiba',
'dberrortext'          => 'Szintaktikai hiba fordult elő az adatbázis lekérdezésekor.
Ez a szoftverben lévő hibát jelezheti.
Az utoljára megkísérelt adatbázis lekérdezés az alábbi volt:
<blockquote><tt>$1</tt></blockquote>
a "<tt>$2</tt>" függvényből.
A MySQL hiba "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Egy adatbázis lekérés formai hiba történt.
Az utolsó lekérési próbálkozás:
"$1"
a "$2" függvényből történt.
A MySQL által visszaadott hiba "$3: $4".',
'noconnect'            => 'Sajnáljuk! A wikinak műszaki nehézségei adódtak, és nem tud csatlakozni az adatbázis kiszolgálóhoz. <br />
$1',
'nodb'                 => 'Nem választható ki a(z) $1 adatbázist',
'cachederror'          => 'A következő a kért lap tárolt változata, ezért lehet, hogy nem tartalmazza a legújabb módosításokat.',
'laggedslavemode'      => 'Figyelem: A lap lehet, hogy nem tartalmazza a legutóbbi változtatásokat!',
'readonly'             => 'Az adatbázis zárolt',
'enterlockreason'      => 'Add meg a lezárás indoklását valamint egy becslést,
hogy mikor kerül a lezárás feloldásra',
'readonlytext'         => 'Az adatbázist jelenleg lezárták, és nem vihetők be új bejegyzések vagy módosítások, valószínűleg a napi adatbázis-karbantartás miatt, melynek befejezése után visszavált a normál állapotba. 

Az adminisztrátor, aki lezárta az adatbázist, az alábbi magyarázatot adta: $1',
'missingarticle'       => 'Az adatbázisban nem található meg a(z) „$1” nevű lap szövege.

Ennek oka általában egy olyan régi hivatkozás követése, amely egy már 
törölt lapra hivatkozik.

Ha nem erről van szó, akkor lehet, hogy programozási hibát találtál a szoftverben. 
Kérjük, hogy jelentsd ezt be egy adminisztrátornak, jegyezd fel neki az URL-t (pontos webcímet) is.',
'readonly_lag'         => 'Az adatbázis automatikusan zárolásra került, amíg a mellékkiszolgálók utolérik a főkiszolgálót.',
'internalerror'        => 'Belső hiba',
'internalerror_info'   => 'Belső hiba: $1',
'filecopyerror'        => 'A(z) "$1" fájl nem másolható "$2" névre.',
'filerenameerror'      => 'A(z) "$1" fájl nem nevezhető át "$2" névre.',
'filedeleteerror'      => 'A(z) "$1" fájl nem törölhető.',
'directorycreateerror' => 'Nem hozható létre a(z) "$1" könyvtár.',
'filenotfound'         => 'Nem található a(z) "$1" fájl.',
'fileexistserror'      => 'A(z) "$1" fájl nem írható: a fájl létezik',
'unexpected'           => 'Váratlan érték: "$1"="$2".',
'formerror'            => 'Hiba: nem küldhető el az űrkap',
'badarticleerror'      => 'Ez a tevékenység nem végezhető el ezen a lapon.',
'cannotdelete'         => 'A megadott lap vagy kép nem törölhető (valaki már törölhette).',
'badtitle'             => 'Hibás cím',
'badtitletext'         => 'A kért oldal címe hibás, üres, vagy rosszul hivatkozott belső nyelv vagy belső wiki cím volt. Olyan karaktereket tartalmazhat, melyek a címekben nem használhatók.',
'perfdisabled'         => 'Elnézést, de ez a lehetőség átmenetileg nem elérhető, mert annyira lelassítja az adatbázist, hogy senki nem tudja a wikit használni.',
'perfcached'           => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, és ezért lehetséges, hogy nem a legfrissebb változatot mutatják:",
'perfcachedts'         => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, legutóbbi frissítésük ideje $1.",
'querypage-no-updates' => 'Ennek az oldalnak a frissítései jelenleg letiltottak. Az itt lévő adatok nem kerülnek frissítésre.',
'wrong_wfQuery_params' => 'A wfQuery() függvény paraméterei pontatlanok<br />
Function: $1<br />
Query: $2',
'viewsource'           => 'Forrás megtekintése',
'viewsourcefor'        => '$1 változata',
'protectedpagetext'    => 'Ezt a lapot a szerkesztések megakadályozása érdekében zároltuk. Módosításokat a vitalapon javasolhatsz, a védelem feloldását az adminisztrátorok üzenőfalán kérheted .',
'viewsourcetext'       => 'A lap forrását megtekintheted és másolhatod:',
'protectedinterface'   => 'Ez a lap a honlap kezelőfelületéhez szolgáltat szöveget a szoftver számára, és a visszaélések elkerülése végett zárolt. A vitalapon javasolhatsz módosításokat.',
'editinginterface'     => "'''Figyelmeztetés:''' Olyan lapot szerkesztesz, mely a szoftver kezelőfelületének szövegeit tartalmazza. Ennek az oldalnak a változtatásai hatással lesznek a felhasználói kezeőfelület megjelenésére, amit a többi felhasználó is tapasztalni fog.",
'sqlhidden'            => '(SQL lekérdezés rejtett)',
'cascadeprotected'     => 'Ez a lap szerkesztés elleni védelem alatt áll, mert a következő {{PLURAL:$1|page|pages}} oldalak között található, melyek a "lépcsőzetes" lehetőség bekapcsolásával védettek:
$2',
'namespaceprotected'   => "A(z) '''$1''' névtérben a lapok szerkesztése a számodra nem engedélyezett.",
'customcssjsprotected' => 'Ennek az oldalnak a szerkesztése a számodra nem engedélyezett, mert egy másik felhasználó személyes beállításait tartalmazza.',
'ns-specialprotected'  => 'A {{ns:special}} névtérben lévő lapokat nem szerkesztheted.',

# Login and logout pages
'logouttitle'                => 'Kilépés',
'logouttext'                 => '<strong>Kiléptél.</strong><br />
A {{SITENAME}} használatát névtelenül folytathatod, vagy beléphetsz
újra ugyanazzal vagy másik felhasználónévvel. Néhány oldalon lehet, 
hogy továbbra is bejelentkezettként leszel látható, mindaddig, amíg
nem üríted ki a böngésződ gyorsítótárát.',
'welcomecreation'            => '== Köszöntünk, $1! ==

A fiókodat létrehoztuk. Ne felejtsd el módosítani a személyes {{SITENAME}} beállításaidat.',
'loginpagetitle'             => 'Belépés',
'yourname'                   => 'Felhasználóneved:',
'yourpassword'               => 'Jelszavad:',
'yourpasswordagain'          => 'Jelszavad ismét:',
'remembermypassword'         => 'Emlékezzen rám ezen a számítógépen',
'yourdomainname'             => 'A domained:',
'externaldberror'            => 'Vagy külső adatbázis hitelesítési hiba történt, vagy a külső fiókod frissítése a számodra nem engedélyezett.',
'loginproblem'               => '<b>Valami probléma van a belépéseddel.</b><br />Kérjük, próbáld ismét!',
'login'                      => 'Belépés',
'loginprompt'                => 'Engedélyezned kell a cookie-kat, hogy bejelentkezhess a {{grammar:be|{{SITENAME}}}}.',
'userlogin'                  => 'Belépés / fiók létrehozása',
'logout'                     => 'Kilépés',
'userlogout'                 => 'Kilépés',
'notloggedin'                => 'Nem lépett be',
'nologin'                    => 'Még nincs felhasználóneved? $1.',
'nologinlink'                => 'Itt regisztrálhatsz',
'createaccount'              => 'Fiók létrehozása',
'gotaccount'                 => 'Van már fiókod? $1.',
'gotaccountlink'             => 'Jelentkezz be',
'createaccountmail'          => 'e-mail alapján',
'badretype'                  => 'Az általad megadott jelszavak nem egyeznek.',
'userexists'                 => 'A megadott felhasználónév már foglalt. Kérjük, válassz másikat!',
'youremail'                  => 'E-mail címed1:',
'username'                   => 'Felhasználónév:',
'uid'                        => 'Azonosítószám:',
'yourrealname'               => 'Valódi neved*',
'yourlanguage'               => 'A kezelőfelület nyelve:',
'yourvariant'                => 'Változó',
'yournick'                   => 'Beceneved (aláírásokhoz):',
'badsig'                     => 'Rossz aláírás; ellenőrizd a HTML-kódelemeket.',
'badsiglength'               => 'Túl hosszú a beceneved; $1 karakternél kevesebbnek kell lennie.',
'prefs-help-realname'        => 'A valódi név elhagyható, de ha úgy döntesz, hogy megadod, akkor ez kerül felhasználásra a munkád szerzőjének megjelölésére.',
'loginerror'                 => 'Belépési hiba',
'prefs-help-email'           => '1 E-mail cím (nem kötelező megadni): Lehetővé teszi, hogy más szerkesztők kapcsolatba lépjenek veled a felhasználói vagy vitalapodon keresztül, anélkül, hogy névtelenséged feladnád.',
'prefs-help-email-required'  => 'E-mail cím szükséges.',
'nocookiesnew'               => 'A felhasználói fiók létrehozása befejeződött, de nem léptél be. A(z) {{SITENAME}} cookie-kat ("süti") használ a felhasználók azonosítására, és lehetséges, hogy te ezeket letiltottad. Kérjük, hogy engedélyezd a cookie-kat, majd lépj be azonosítóddal és jelszavaddal.',
'nocookieslogin'             => 'A wiki cookie-kat ("süti") használ az azonosításhoz, de te ezeket letiltottad. Engedélyezd őket, majd próbálkozz ismét.',
'noname'                     => 'Nem adtál meg érvényes felhasználónevet.',
'loginsuccesstitle'          => 'Sikeres belépés',
'loginsuccess'               => 'Beléptél a {{grammar:ba|{{SITENAME}}}} "$1"-ként.',
'nosuchuser'                 => 'Nincs "$1" nevű felhasználó. Ellenőrizd a helyesírást, vagy hozz létre új fiókot.',
'nosuchusershort'            => 'Nincs "$1" nevű felhasználó. Ellenőrizd a helyesírást.',
'nouserspecified'            => 'Meg kell adnod a felhasználónevet.',
'wrongpassword'              => 'A megadott jelszó hibás. Próbáld meg újra.',
'wrongpasswordempty'         => 'Nem adtad meg a jelszót. Próbáld meg újra.',
'passwordtooshort'           => 'Az általad megadott jelszó hibás vagy túl rövid. Legalább $1 karakterből kell állnia, és a felhasználónévtől eltérőnek kell lennie.',
'mailmypassword'             => 'Jelszó küldése e-mailben',
'passwordremindertitle'      => '{{SITENAME}} jelszó emlékeztető',
'passwordremindertext'       => 'Valaki (vélhetően te, a $1 IP-címről)
azt kérte, hogy küldjünk neked új {{SITENAME}} ($4) jelszót.
A "$2" felhasználó jelszava most "$3".
Lépj be, és változtasd meg a jelszavadat.

Ha nem kértél új jelszót, vagy közben eszedbe jutott a régi, 
és már nem akarod megváltoztatni, nyugodtan figyelmen kívül 
hagyhatod ezt az értesítést, és használhatod tovább a régi jelszavadat.',
'noemail'                    => 'Nincs a "$1" felhasználóhoz e-mail felvéve.',
'passwordsent'               => 'Az új jelszót elküldtük "$1" email címére.
Lépj be a levélben található adatokkal.',
'blocked-mailpassword'       => 'Az IP-címedet blokkoltuk a szerkesztésből, ezért
a visszaélések elkerülése miatt a jelszó-visszaállítás funkció használata nem engedélyezett.',
'eauthentsent'               => 'Egy megerősítést kérő e-mailt küldtünk a megadott címre. Mielőtt további levelek lennének küldve a megadott címre, végre kell hajtanod az e-mailben kapott utasításokat, hogy bizonyítsd, valóban tiéd a felhasználói fiók.',
'throttled-mailpassword'     => '$1 órája már elküldtük a jelszó-emlékeztetőt. 
A visszaélések elkerülése végett $1 óránként csak egy jelszó-emlékeztetőt 
küldünk.',
'mailerror'                  => 'Hiba az e-mail küldésekor: $1',
'acct_creation_throttle_hit' => 'Már létrehoztál $1 fiókot. Sajnáljuk, de többet nem hozhatsz létre.',
'emailauthenticated'         => 'Az e-mail címedet $1-kor megerősítetted.',
'emailnotauthenticated'      => 'Még <strong>nem erősítetted meg</strong> az e-mail címedet. 
A következő funkciók egyikére sem történik e-mail küldés.',
'noemailprefs'               => 'Add meg az e-mail címedet e funkciók működéséhez.',
'emailconfirmlink'           => 'E-mail címed megerősítése',
'invalidemailaddress'        => 'Az e-mail cím nem fogadhatjuk el, mert a formátuma érvénytelen.  
Kérjük, adj meg egy helyesen formázott e-mail címet vagy hagyd üresen a mezőt.',
'accountcreated'             => 'Azonosító létrehozva',
'accountcreatedtext'         => '$1 felhasználói azonosítója sikeresen létrejött.',
'loginlanguagelabel'         => 'Nyelv: $1',

# Password reset dialog
'resetpass'               => 'A fiók jelszavának módosítása',
'resetpass_announce'      => 'E-mailben megküldött ideiglenes kóddal jelentkeztél be. A bejelentkezés befejezéséhez itt kell megadnod az új jelszót:',
'resetpass_header'        => 'Jelszó módosítása',
'resetpass_submit'        => 'Add meg a jelszót és jelentkezz be',
'resetpass_success'       => 'A jelszavad módosítása sikerült! Most jelentkezel be...',
'resetpass_bad_temporary' => 'Az ideiglenes jelszó hibás. Lehet, hogy már sikeresen megváltoztattad a jelszavadat, vagy új ideiglenes jelszót kértél.',
'resetpass_forbidden'     => 'Nem változtatható meg a jelszó ebben a wikiben',
'resetpass_missing'       => 'Nincs űrlapadat.',

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
'nowiki_sample'   => 'Ide írd a nem formázott szöveget',
'nowiki_tip'      => 'A wiki formázás mellőzése',
'image_sample'    => 'Pelda.jpg',
'image_tip'       => 'Beágyazott kép',
'media_sample'    => 'Peldaegyketto.ogg',
'media_tip'       => 'Médiafájl hivatkozása',
'sig_tip'         => 'Aláírás időponttal',
'hr_tip'          => 'Vízszintes vonal (módjával használd)',

# Edit pages
'summary'                   => 'Összegzés',
'subject'                   => 'Téma/főcím',
'minoredit'                 => 'Ez egy kisebb változtatás',
'watchthis'                 => 'A lap figyelése',
'savearticle'               => 'Lap mentése',
'preview'                   => 'Előnézet',
'showpreview'               => 'Előnézet megtekintése',
'showlivepreview'           => 'Élő előnézet',
'showdiff'                  => 'Változtatások megtekintése',
'anoneditwarning'           => "'''Figyelmeztetés:''' Nem jelentkeztél be. Az IP címed látható lesz a laptörténetben.",
'missingsummary'            => "'''Emlékeztető:''' Nem adtad meg szerkesztés összegzését. Ha összegzés nélkül akarod elküldeni a szöveget, kattints újra a Mentés gombra.",
'missingcommenttext'        => 'Kérjük, hogy írj összegzést a szerkesztésedhez.',
'missingcommentheader'      => "'''Emlékeztető:''' Nem adtad meg a megjegyzés tárgyát/címét. Ha ismét a Mentés gombra kattintasz, akkor a szerkesztésed anélkül kerül mentésre.",
'summary-preview'           => 'A szerkesztési összegzés előnézete',
'subject-preview'           => 'A szakaszcím előnézete',
'blockedtitle'              => 'Blokkolt felhasználó',
'blockedtext'               => "<big>'''A felhasználónevedet vagy az IP-címedet blokkoltuk.'''</big>

A blokkolást $1 tette. Az általa felhozott indok: ''$2''.

* A blokkolás kezdete: $8
* A blokkolás lejárata: $6
* Szándékos blokkoló: $7

Kapcsolatba léphetsz $1 felhasználóval, vagy egy másik [[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]], és megbeszélheted vele a blokkolásodat.
Az 'E-mail küldése ennek a felhasználónak' funkciót nem használhatod, ha a megadott e-mail cím a
[[Special:Preferences|fiókbeállításaidban]] nem érvényes, és nem blokkolták annak a használatát.
Jelenlegi IP-címed: $3, a blokkolás azonosítószáma: #$5. Kérjük, hogy érdeklődés esetén lehetőleg mindkettőt add meg.",
'autoblockedtext'           => 'Az IP-címed automatikusan blokkolásra került, mertegy másik felhasználó használta azt, akit $1 blokkolt.
Az indok a következő:

:\'\'$2\'\'

* Blokkolás kezdete: $8
* Blokkolás lejárata: $6

Felveheted $1 felhasználóval vagy egy másik
[[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]] a kapcsolatot, és megbeszélheted vele a blokkolásodat.

Az "E-mail küldése ennek a felhasználónak" funkciót nem használhatod, ha a megadott e-mail cím a
[[Special:Preferences|fiókbeállításaidban]] nem érvényes, és nem blokkolták annak a használatát.

Blokkolásod azonosítószáma: $5. Kérjük, hogy érdeklődés esetén add meg ezt az azonosítószámot.',
'blockedoriginalsource'     => "'''$1''' forrása 
megtalálható alább:",
'blockededitsource'         => "'''$1''' lapon '''általad végrehajtott szerkesztések''' szövege:",
'whitelistedittitle'        => 'A szerkesztéshez be kell lépned',
'whitelistedittext'         => 'A szócikkek szerkesztéséhez $1.',
'whitelistreadtitle'        => 'Az olvasáshoz be kell lépned',
'whitelistreadtext'         => '[[Special:Userlogin|Be kell lépned]] ahhoz, hogy cikkeket tudj olvasni.',
'whitelistacctitle'         => 'Új fiók létrehozása a számodra nem engedélyezett',
'whitelistacctext'          => 'Ahhoz, hogy ezen a Wikin új nevet regisztrálj [[Special:Userlogin|be kell lépned]] a szükséges engedélyszinttel.',
'confirmedittitle'          => 'E-mail cím megerősítése szükséges a szerkesztéshez',
'confirmedittext'           => 'A lapok szerkesztése előtt meg kell erősítened az e-mail címedet. Kérjük, hogy a [[Special:Preferences|felhasználói beállításaidban]] add meg és ellenőrizd az e-mail címedet.',
'nosuchsectiontitle'        => 'Nincs ilyen szakasz',
'nosuchsectiontext'         => 'Olyan szakaszt prbáltál meg szerkeszteni, mely nem létezik.  Mivel nincs $1 szakasz, nincs hely a szerkesztésed mentésére.',
'loginreqtitle'             => 'Be kell jelentkezned',
'loginreqlink'              => 'belépés',
'loginreqpagetext'          => 'Előbb $1 kell nézned a többi lapot.',
'accmailtitle'              => 'A jelszót elküldtük.',
'accmailtext'               => '„$1” jelszavát elküldtük $2 címre.',
'newarticle'                => '(Új)',
'newarticletext'            => "Egy olyan lapra mutató hivatkozást követtél, mely még nem létezik.
A lap létrehozásához kezdd el írni a szövegét az alábbi keretben
(a [[{{MediaWiki:Helppage}}|súgó]] lapon lelsz további
információkat).
Ha tévedésből jöttél ide, csak nyomd meg a böngésző '''Vissza/Back'''
gombját.",
'anontalkpagetext'          => "---- ''Ez egy olyan névtelen felhasználó vitalapja, aki nem hozott még létre fiókot, vagy nem használja azt. Ezért az IP-címével azonosítjuk őt be. Az IP címeken számos felhasználó osztozhat az idők folyamán. Ha névtelen felhasználó vagy, és úgy érzed, hogy értelmetlen megjegyzéseket írnak neked, akkor [[Special:Userlogin|hozd létre a fiókodat, vagy jelentkezz be]], hogy elkerüld a más névtelen felhasználókkal való keveredést.''",
'noarticletext'             => 'Jelenleg nincs szöveg ezen a lapon, a többi lapon [[Special:Search/{{PAGENAME}}|kereshetsz erre a lapcímre]], vagy [{{fullurl:{{FULLPAGENAME}}|action=edit}} szerkesztheted ezt a lapot].',
'clearyourcache'            => "'''Megjegyzés:''' A beállítások mentése után frissítened kell a böngésződ gyorsítótárát, hogy a változások érvénybe lépjenek. '''Mozilla / Firefox / Safari:''' tartsd lenyomva a Shift gombot, és kattints a ''Frissítés'' gombra az eszköztáron, vagy használd a ''Ctrl–F5'' billentyűkombinációt (Apple Mac-en ''Cmd–Shift–R''); '''Internet Explorer:''' tartsd lenyomva a ''Ctrl'' gombot, és kattints a ''Frissítés'' gombra, vagy nyomd le a ''Ctrl–F5'' billentyűparancsot; '''Konqueror:''' egyszerűen csak kattints a ''Reload'' / ''Frissítés'' gombra (vagy ''Ctrl–R'' vagy ''F5''); '''Opera''' az ''Eszközök›Beállítások'' menüpontból megnyitható ablakban a felhasználóknak teljesen ki kell üríteniük a gyorsítótárat.",
'usercssjsyoucanpreview'    => '<strong>Tipp:</strong> Használd az "Előnézet megtekintése" gombot az új css/js teszteléséhez mentés előtt.',
'usercsspreview'            => "'''Ne felejtsd el, hogy ez csak a CSS előnézete, még nem mentetted el!'''",
'userjspreview'             => "'''Ne felejtsd el, hogy még csak teszteled a felhasználói JavaScriptedet, és azt még nem mentetted el!'''",
'userinvalidcssjstitle'     => "'''Figyelem:''' Nincs „$1” nevű felszín. Lehet, hogy nagy kezdőbetűt használtál olyan helyen, ahol nem kellene? A felületekhez tartozó .css/.js oldalak kisbetűvel kezdődnek. (Például ''{{ns:user}}:Gipsz Jakab/monobook.css'' és nem ''{{ns:user}}:Gipsz Jakab/Monobook.css''.)",
'updated'                   => '(Frissítve)',
'note'                      => '<strong>Megjegyzés:</strong>',
'previewnote'               => '<strong>Ez csak egy előnézet, a változtatásokat még nem mentetted!</strong>',
'previewconflict'           => 'Ez az előnézet a felső szerkesztőablakban levő szövegnek megfelelő képet mutatja, ahogy a mentés után kinézne.',
'session_fail_preview'      => '<strong>Sajnos nem tudtuk feldolgozni a szerkesztésedet, mert elveszett a session adat. Kérjük próbálkozz újra! Amennyiben továbbra sem sikerül, próbálj meg kijelentkezni, majd ismét bejelentkezni!</strong>',
'session_fail_preview_html' => "<strong>Elnézést! A munkamenet adatainak megsemmisülése miatt nem tudtuk feldolgozni a szerkesztésedet.</strong>

''Mivel ebben a wikiben a nyers HTML engedélyezett, az előnézet a JavaScript támadások miatti elővigyázatosságból rejtett.''

<strong>Ha ez egy jogos szerkesztési kísérlet, akkor próbáld meg újra. Ha még mindig nem működik, próbáld meg, hogy kijelentkezel, és visszajelentkezel.</strong>",
'token_suffix_mismatch'     => '<strong>A szerkesztésedet elutasítottuk, mert az ügyfeled megváltoztatta az írásjeleket 
a szerkesztési vezérjelben. A szerkesztést azért utasítottuk vissza, hogy megelőzzük a cikk szövegének sérülését. 
Ez olyankor fordul elő, ha az általad használt webalapú névtelen proxy szolgáltatás hibás.</strong>',
'editing'                   => '$1 szerkesztés alatt',
'editinguser'               => '$1 szerkesztés alatt',
'editingsection'            => '$1 szerkesztés alatt (szakasz)',
'editingcomment'            => '$1 szerkesztés alatt (üzenet)',
'editconflict'              => 'Szerkesztési ütközés: $1',
'explainconflict'           => 'Valaki megváltoztatta a lapot azóta,
mióta szerkeszteni kezdted.
A felső szövegablak tartalmazza a szöveget, ahogy az jelenleg létezik.
A módosításaid az alsó ablakban láthatóak.
Át kell vezetned a módosításaidat a felső szövegbe.
<b>Csak</b> a felső ablakban levő szöveg kerül elmentésre akkor, mikor
a "Lap mentését" választod.<br />',
'storedversion'             => 'A tárolt változat',
'nonunicodebrowser'         => '<strong>Figyelem: A böngésződ nem unicode kompatibilis. Egy programozási trükk segítségével biztonságban szerkesztheted a cikkeket: a nem ASCII karakterek a szerkesztőablakban hexadeciális kódokként jelennek meg..</strong>',
'editingold'                => '<strong>FIGYELMEZTETÉS: A lap egy elavult változatát 
szerkeszted. 
Ha mented, akkor az ezen változat után végzett összes módosítás elvész.</strong>',
'yourdiff'                  => 'Eltérések',
'copyrightwarning'          => 'A {{SITENAME}} wikijének valamennyi szócikke a $2 alatt kerül kiadásra (részletek itt: $1). Ha nem akarod, hogy az írásodat módosítsák vagy továbbterjesszék, akkor ne küldd itt be.<br />
Azt is ígéred, hogy ezt saját magad írtad, ill. nyilvános tartományról vagy hasonló szabad forrásból másoltad.
<strong>NE KÜLDJ BE SZERZŐI JOGILAG VÉDETT MUNKÁKAT, MELYEKET NEM ENGEDÉLYEZTEK!</strong>',
'copyrightwarning2'         => 'A {{SITENAME}} wikijében a többi közreműködő valamennyi szócikket szerkesztheti, megváltoztathatja vagy eltávolíthatja. Ha nem akarod, hogy az írásodat módosítsák vagy továbbterjesszék, akkor ne küldd itt be.<br />
Azt is ígéred, hogy ezt saját magad írtad, ill. nyilvános tartományról vagy 
hasonló szabad forrásból másoltad.
<strong>NE KÜLDJ BE SZERZŐI JOGILAG VÉDETT MUNKÁKAT, MELYEKET NEM ENGEDÉLYEZTEK!</strong>',
'longpagewarning'           => '<strong>FIGYELEM: Ez a lap $1 kilobájt hosszú;
néhány böngészőnek problémái vannak a 32KB körüli vagy nagyobb lapok
szerkesztésével.
Fontold meg a lap kisebb szakaszokra bontását.</strong>',
'longpageerror'             => '<strong>HIBA: Az általad beküldött szöveg $1 kilobájt hosszú, ami több az engedélyezett $2 kilobájtnál. It cannot be saved.</strong>',
'readonlywarning'           => '<strong>FIGYELMEZTETÉS: Az adatbázist karbantartás miatt zárolták,
ezért sajnos nem tudod most menteni a szerkesztéseidet. A szöveget a vágólapra másolhatod
és beillesztheted egy szövegfájlba, amit elmenthetsz későbbre.</strong>',
'protectedpagewarning'      => '<strong>FIGYELMEZTETÉS:  Ezt az oldalt zárolták, ezért csak a rendszerfelelősi jogokkal rendelkező felhasználók szerkeszthetik.</strong>',
'semiprotectedpagewarning'  => "'''Megjegyzés:''' ez a lap védett, nem vagy újonnan regisztrált felhasználók nem szerkeszthetik.",
'cascadeprotectedwarning'   => "'''Figyelmeztetés:''' Ezt az oldalt zárolták, ezért csak a rendszerfelelősi jogokkal rendelkező felhasználók szerkeszthetik, mert a következő lépcsőzetes védelmű {{PLURAL:$1|page|oldalak}} közé tartozik:",
'templatesused'             => 'Sablonok ezen a lapon:',
'templatesusedpreview'      => 'Az előnézetben használt sablonok:',
'templatesusedsection'      => 'Szakaszban használt sablonok:',
'template-protected'        => '(védett)',
'template-semiprotected'    => '(félig-védett)',
'edittools'                 => '<!-- Ez a szöveg a szerkesztés és a feltöltés űrlap alatt lesz látható. -->',
'nocreatetitle'             => 'A laplétrehozás korlátozott',
'nocreatetext'              => 'Ezen a webhelyen korlátozták az új oldalak készítését.
Visszamehetsz és szerkeszthetsz egy létező lapot, ill. [[Special:Userlogin|bejelentkezhetsz vagy létrehozhatod a fiókodat]].',
'nocreate-loggedin'         => 'Az új lapok készítése nem engedélyezett ebben a wikiben a számodra.',
'permissionserrors'         => 'Engedélyezési hibák',
'permissionserrorstext'     => 'Végrehajtása nem engedélyezett a számodra, a következő {{PLURAL:$1|ok|okok}} miatt:',
'recreate-deleted-warn'     => "'''Figyelmeztetés: Olyan oldalt készítesz újra, melyet már töröltünk.''',

Vedd fontolóra, hogy helyénvaló-e ezen lap szerkesztésének a folytatása.
Ezen lap törlésnaplóját a kényelem kedvéért alább találod:",

# "Undo" feature
'undo-success' => 'A szerkesztés visszavonható. Kérjük, ellenőrizd a változásokat alább, hogy valóban ezt szeretnéd-e tenni, majd kattints a Lap mentése gombra a visszavonás véglegesítéséhez.',
'undo-failure' => 'A szerkesztés nem vonható vissza a vele ütköző későbbi szerkesztések miatt.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|vita]]) $1 szerkesztésének visszaállítása.',

# Account creation failure
'cantcreateaccounttitle' => 'Nem hozható létre a fiók',
'cantcreateaccount-text' => "Erről az IP-címről (<b>$1</b>) átmenetileg nem lehet regisztrálni, mert [[User:$3|$3]] blokkolta az alábbi indokkal:
:''$2''",

# History pages
'viewpagelogs'        => 'A lap naplóinak megtekintése',
'nohistory'           => 'Ennek a lapnak nincs szerkesztési története.',
'revnotfound'         => 'A változat nem található',
'revnotfoundtext'     => 'A lap általad kért régi változata nem található. Kérjük, ellenőrizd az URL-t, amivel erre a lapra jutottál.',
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
'page_first'          => 'első',
'page_last'           => 'utolsó',
'histlegend'          => 'Eltérések kijelölése: jelöld ki az összehasonlítandó verziókat, majd nyomd meg az Enter billentyűt, vagy az alul lévő gombot.<br />
Jelmagyarázat: (akt) = eltérés az aktuális változattól,
(előző) = eltérés az előző változattól, A = Apró változtatás',
'deletedrev'          => '[törölve]',
'histfirst'           => 'legkorábbi',
'histlast'            => 'legutolsó',
'historysize'         => '($1 byte)',
'historyempty'        => '(üres)',

# Revision feed
'history-feed-title'          => 'Laptörténet',
'history-feed-description'    => 'Az oldal laptörténete a {{SITENAME}}',
'history-feed-item-nocomment' => '$1, $2-n', # user at time
'history-feed-empty'          => 'A kért oldal nem létezik.
Lehet, hogy törölték a wikiből, vagy átnevezhették.
Próbálkozz meg a témával kapcsolatos wikilapok [[Special:Search|keresésével]].',

# Revision deletion
'rev-deleted-comment'         => '(megjegyzés eltávolítva)',
'rev-deleted-user'            => '(felhazsnálónév eltávolítva)',
'rev-deleted-event'           => '(bejegyzés eltávolítva)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Ezt a lapjavítást eltávolították a nyilvános archívumokból.
A részletek benne lehetnek a [{{fullurl:Special:Napló/delete|page={{FULLPAGENAMEE}}}} törlési naplóban].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Ezt a lapváltozatot eltávolították a nyilvános archívumokból.
Mivel adminisztrátor vagy ezen a webhelyen, te megtekintheted;
a részletek benne lehetnek a [{{fullurl:Special:Napló/delete|page={{FULLPAGENAMEE}}}} törlési naplóban].
</div>',
'rev-delundel'                => 'megjelenítés/elrejtés',
'revisiondelete'              => 'Javítások törlése/visszaállítása',
'revdelete-nooldid-title'     => 'Nincs célváltozat',
'revdelete-nooldid-text'      => 'Nem adtad meg a célváltozatot vagy változatokat, melyeken el akarod végezni ezt a funkciót.',
'revdelete-selected'          => "{{PLURAL:$2|Kiválasztott változat|Kiválasztott változatok}} - '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Kiválasztott naplóesemény|Kiválasztott naplóesemények}} - '''$1:'''",
'revdelete-text'              => 'A törölt változatok és események még láthatók lesznek a lap előzményeiben és naplójában,
azonban a tartalmuknak csak egy része lesz a nyilvánosság számára hozzáférhetetlen.

Ezen wiki többi adminisztrátora még hozzá tud férni a rejtett tartalomhoz, és
vissza tudja ugyanezen a kezelőfelületen keresztül állítani, ha nincs megadva további korlátozás.',
'revdelete-legend'            => 'Korlátozások megadása:',
'revdelete-hide-text'         => 'Változat szövegének elrejtése',
'revdelete-hide-name'         => 'Művelet és cél elrejtése',
'revdelete-hide-comment'      => 'Megjegyzés módosításának elrejtése',
'revdelete-hide-user'         => 'A szerkesztő felhasználónevének/IP-címének elrejtése',
'revdelete-hide-restricted'   => 'Ezen korlátozások alkalmazása a rendszerfelelősökre is, és a többiekre is',
'revdelete-suppress'          => 'Adatok letiltása a rendszerfelelősöktől is, és a többiektől is',
'revdelete-hide-image'        => 'Fájltartalom elrejtése',
'revdelete-unsuppress'        => 'A visszaállított változatok korlátozásainak eltávolítása',
'revdelete-log'               => 'Naplómegjegyzés:',
'revdelete-submit'            => 'Alkalmazás a kiválasztott változatra',
'revdelete-logentry'          => 'megváltozott változat láthatóság [[$1]]',
'logdelete-logentry'          => 'megváltozott esemény láthatóság [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|revision|revíziók}} átállítva $2. módra',
'logdelete-logaction'         => '$1 {{PLURAL:$1|event|események}} to [[$3]] átállítva $2. módra',
'revdelete-success'           => 'A változat láthatóságának beállítása sikerült.',
'logdelete-success'           => 'Az esemény láthatóságának beállítása sikerült.',

# Oversight log
'oversightlog'    => 'Tévedésnapló',
'overlogpagetext' => 'Az alábbiakban látható a rendszerfelelősök elől elrejtett legutóbbi törlések listája.
 A jelenleg érvényben lévő kitiltásokat és blokkolásokat lásd az [[Special:Ipblocklist|IP blokkolási listában]].',

# History merging
'mergehistory'      => 'Laptörténetek egyesítése',
'mergehistory-box'  => 'Két oldal változatainak egyesítése:',
'mergehistory-from' => 'Forrásoldal:',
'mergehistory-into' => 'Céloldal:',
'mergehistory-list' => 'Egyesíthető laptörténet',
'mergehistory-go'   => 'Egyesíthető szerkesztések mutatása',

# Diffs
'history-title'           => 'A(z) „$1” laptörténete',
'difference'              => '(Változatok közti eltérés)',
'lineno'                  => '$1. sor:',
'compareselectedversions' => 'A kiválasztott verziók összehasonlítása',
'editundo'                => 'visszavonás',
'diff-multi'              => '({{PLURAL:$1|Egy közbeeső változat|$1 közbeeső változat}} nem látható)',

# Search results
'searchresults'         => 'A keresés eredménye',
'searchresulttext'      => 'A {{SITENAME}} keresésével kapcsolatos további információ a [[{{MediaWiki:Helppage}}|{{int:súgóban}}]].',
'searchsubtitle'        => 'Erre kerestél: „[[:$1]]”',
'searchsubtitleinvalid' => 'A "$1" kereséshez',
'noexactmatch'          => "'''Nincs \"\$1\" című lap.''' [[:\$1|Elkészítheted ezt a lapot]].",
'titlematches'          => 'Címszó egyezik',
'notitlematches'        => 'Nincs egyező címszó',
'textmatches'           => 'Szócikk szövege egyezik',
'notextmatches'         => 'Nincs egyező szócikk szöveg',
'prevn'                 => 'előző $1',
'nextn'                 => 'következő $1',
'viewprevnext'          => '($1) ($2) ($3)',
'showingresults'        => 'Lent látható <b>$1</b> találat, az eleje <b>$2</b>.',
'showingresultsnum'     => 'Lent látható <b>$3</b> találat, az eleje #<b>$2</b>.',
'nonefound'             => "'''Megjegyzés''': A sikertelen keresések
gyakori oka olyan szavak keresése (pl. \"have\" és \"from\"), amiket a
rendszer nem indexel, vagy több független keresési kifejezés megadása
(csak minden megadott szót tartalmazó találatok jelennek meg az eredményben).",
'powersearch'           => 'Keresés',
'powersearchtext'       => 'Keresés a névterekben:<br />$1<br />$2 Átirányítások listája &nbsp; Keresés:$3 $9',
'searchdisabled'        => 'Elnézésed kérjük, de a teljes szöveges keresés terhelési okok miatt átmenetileg nem használható. Ezidő alatt használhatod a lenti Google keresést, mely viszont lehetséges, hogy nem teljesen friss adatokkal dolgozik.',

# Preferences page
'preferences'              => 'Beállításaim',
'mypreferences'            => 'Beállításaim',
'prefs-edits'              => 'Szerkesztések száma:',
'prefsnologin'             => 'Nem jelentkeztél be',
'prefsnologintext'         => 'Ahhoz, hogy a beállításaidat rögzíthesd, [[Special:Userlogin|be kell lépned]].',
'prefsreset'               => 'A beállítások visszaállításra kerültek a tárolóból.',
'qbsettings'               => 'Gyorsmenü beállítások',
'qbsettings-none'          => 'Nincs',
'qbsettings-fixedleft'     => 'Fix baloldali',
'qbsettings-fixedright'    => 'Fix jobboldali',
'qbsettings-floatingleft'  => 'Lebegő baloldali',
'qbsettings-floatingright' => 'Lebegő jobboldali',
'changepassword'           => 'Jelszócsere',
'skin'                     => 'Felszín',
'math'                     => 'Képletek',
'dateformat'               => 'Dátum formátuma',
'datedefault'              => 'Nincs beállítás',
'datetime'                 => 'Dátum és idő',
'math_failure'             => 'Értelmezés sikertelen',
'math_unknown_error'       => 'ismeretlen hiba',
'math_unknown_function'    => 'ismeretlen függvény',
'math_lexing_error'        => 'lexikai hiba',
'math_syntax_error'        => 'formai hiba',
'math_image_error'         => 'A PNG konvertálás nem sikerült; ellenőrizd a latex, dvips, gs telepítését, és konvertáld',
'math_bad_tmpdir'          => 'Nem írható vagy nem hozható létre a matematikai ideiglenes könyvtár',
'math_bad_output'          => 'Nem írható vagy nem hozható létre a matematikai kimeneti könyvtár',
'math_notexvc'             => 'HIányzó texvc végrehajtható fájl; a beállítást lásd a math/README fájlban.',
'prefs-personal'           => 'Felhasználói adatok',
'prefs-rc'                 => 'Legutóbbi változtatások',
'prefs-watchlist'          => 'Figyelőlista',
'prefs-watchlist-days'     => 'A figyelőlistában mutatott napok száma:',
'prefs-watchlist-edits'    => 'A kiterjesztett figyelőlistán mutatott szerkesztések száma:',
'prefs-misc'               => 'Egyéb',
'saveprefs'                => 'Mentés',
'resetprefs'               => 'Alaphelyzet',
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
'stub-threshold'           => 'A <a href="#" class="stub">stub hivatkozás</a> formázásának küszöbértéke:',
'recentchangesdays'        => 'Napok száma, amíg a legutóbbi változtatások közt látható:',
'recentchangescount'       => 'Címszavak száma a friss változtatásokban:',
'savedprefs'               => 'Az új beállításaid érvénybe léptek.',
'timezonelegend'           => 'Időzóna',
'timezonetext'             => 'Add meg az órák számát, amennyivel a helyi idő a GMT-től eltér (Magyarországon nyáron 2, télen 1).',
'localtime'                => 'Helyi idő:',
'timezoneoffset'           => 'Eltérés1:',
'servertime'               => 'A kiszolgáló ideje:',
'guesstimezone'            => 'Töltse ki a böngésző',
'allowemail'               => 'E-mail engedélyezése más felhasználóktól',
'defaultns'                => 'Alapértelmezett keresés az alábbi névterekben:',
'default'                  => 'alapértelmezés',
'files'                    => 'Képek',

# User rights
'userrights-lookup-user'      => 'Felhasználócsoportok kezelése',
'userrights-user-editname'    => 'Írd be a felhasználónevet:',
'editusergroup'               => 'Felhasználócsoportok módosítása',
'userrights-editusergroup'    => 'Felhasználócsoportok módosítása',
'saveusergroups'              => 'Felhasználócsoportok mentése',
'userrights-groupsmember'     => 'Csoporttag:',
'userrights-groupsavailable'  => 'Létező csoportok:',
'userrights-groupshelp'       => 'Jelöld ki azokat a csoportokat, melyekből el akarod távolítani, vagy melyekhez hozzá akarod adni a felhasználót.
A kijelöletlen csportok változatlanok maradnak. CTRL + bal kattintással tudod egy csoport kijelölését megszüntetni',
'userrights-reason'           => 'A változtatás indoka:',
'userrights-available-none'   => 'A csoporttagságot nem módosíthatod.',
'userrights-available-add'    => 'Adhatsz hozzá felhasználókat a(z) $1 csoporthoz.',
'userrights-available-remove' => 'Távolíthatsz el felhazsnálókat a(z) $1 csoportból.',

# Groups
'group'               => 'Csoport:',
'group-autoconfirmed' => 'Automatikus megerősítésű felhasználók',
'group-bot'           => 'Botok',
'group-sysop'         => 'adminisztrátorok',
'group-bureaucrat'    => 'Bürokraták',
'group-all'           => '(mind)',

'group-autoconfirmed-member' => 'Automatikus megerősítésű felhasználó',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Rendszerfelelős',
'group-bureaucrat-member'    => 'Bürokrata',

'grouppage-autoconfirmed' => '{{ns:project}}:Automatikus megerősítésű felhasználók',
'grouppage-bot'           => '{{ns:project}}:Robotok',
'grouppage-sysop'         => '{{ns:project}}:Adminisztrátorok',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraták',

# User rights log
'rightslog'      => 'Felhasználói jogosultságok naplója',
'rightslogtext'  => 'A felhasználói jogok változtatásainak naplója.',
'rightslogentry' => '$1 csoporttagsága $2 típusról $3 típusra változott',
'rightsnone'     => '(nincs)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|change|változtatások}}',
'recentchanges'                     => 'Friss változtatások',
'recentchangestext'                 => 'A wiki legutóbbi változtatásainak követése ezen a lapon.',
'recentchanges-feed-description'    => 'A wiki legutóbbi változtatásainak követése ebben a hírcsatornában.',
'rcnote'                            => 'Alább az utolsó <strong>$2</strong> nap utolsó <strong>$1</strong> változtatása látható. A lap generálásának időpontja $3.',
'rcnotefrom'                        => 'Alább láthatóak a <b>$2</b> óta történt változások (<b>$1</b>-ig).',
'rclistfrom'                        => 'Az új változtatások kijelzése $1 után',
'rcshowhideminor'                   => 'kisebb módosítások $1',
'rcshowhidebots'                    => 'robotok szerkesztéseinek $1',
'rcshowhideliu'                     => 'bejelentkezett felhasználók szerkesztéseinek $1',
'rcshowhideanons'                   => 'névtelen szerkesztések $1',
'rcshowhidepatr'                    => 'ellenőrzött szerkesztések $1',
'rcshowhidemine'                    => 'saját szerkesztések $1',
'rclinks'                           => 'Az elmúlt $2 nap utolsó $1 változtatása legyen látható<br />$3',
'diff'                              => 'eltér',
'hist'                              => 'történet',
'hide'                              => 'elrejtése',
'show'                              => 'megjelenítése',
'minoreditletter'                   => 'A',
'newpageletter'                     => 'Ú',
'boteditletter'                     => 'R',
'number_of_watching_users_pageview' => '[$1 figyelő felhasználó]',
'rc_categories'                     => 'Korlátozás kategóriákra ("|" jellel elválasztva)',
'rc_categories_any'                 => 'Bármi',
'newsectionsummary'                 => 'Új szekció:',

# Recent changes linked
'recentchangeslinked'          => 'Kapcsolódó változtatások',
'recentchangeslinked-title'    => 'A(z) $1 lappal kapcsolatos változtatások',
'recentchangeslinked-noresult' => 'Nem történt változtatás a hivatkozott lapokon a megadott időtartam alatt.',
'recentchangeslinked-summary'  => "Ez a speciális lap listázza ki az utolsó változtatásokat azokon a lapokon, melyekre hivatkoznak. A figyelőlistán lévő oldalak '''félkövér''' stílusúak.",

# Upload
'upload'                      => 'Fájl feltöltése',
'uploadbtn'                   => 'Fájl feltöltése',
'reupload'                    => 'Újra feltöltés',
'reuploaddesc'                => 'Visszatérés a feltöltési űrlaphoz.',
'uploadnologin'               => 'Nem jelentkeztél be',
'uploadnologintext'           => 'Csak regisztrált felhasználók tölthetnek fel fájlokat. [[Special:Userlogin|Jelentkezz be]] vagy [{{FULLURL:Special:Belépés|type=signup}} regisztrálj]!',
'upload_directory_read_only'  => 'A feltöltési könyvtár ($1) a webkiszolgáló által nem írható.',
'uploaderror'                 => 'Feltöltési hiba',
'uploadtext'                  => "Az alábbi űrlap használatával tölthetsz föl fájlokat, a már feltöltött képek megtekintéséhez vagy kereséséhez menj a [[Special:Képlista|feltöltött fájlok listájához]], a feltöltések és a törlések is naplózásra kerülnek a [[Special:Log/upload|feltöltési naplóban]].

To include the image in a page, use a link in the form
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|alt text]]</nowiki>''' or
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' for directly linking to the file.",
'uploadlog'                   => 'feltöltési napló',
'uploadlogpage'               => 'Feltöltési_napló',
'uploadlogpagetext'           => 'Alább látható a legutóbbi feltöltések listája. Minden időpont a kiszolgáló időzónájában (UTC) van megadva.',
'filename'                    => 'Fájlnév',
'filedesc'                    => 'Összegzés',
'fileuploadsummary'           => 'Összegzés:',
'filestatus'                  => 'Szerzői jogi állapot',
'filesource'                  => 'Forrás',
'uploadedfiles'               => 'Feltöltött fájlok',
'ignorewarning'               => 'A figyelmeztetés mellőzése és a fájl mentése mindenképp.',
'ignorewarnings'              => 'A figyelmeztetések mellőzése',
'minlength1'                  => 'A fájlnévnek legalább egy betűből kell állnia.',
'illegalfilename'             => 'A(z) "$1" fájlnév a lapcímekben engedélyezett karaktereket tartalmaz. Nevezd át a fájlt, és prbáld meg ismét feltölteni.',
'badfilename'                 => 'A kép új neve "$1".',
'filetype-badmime'            => 'A(z) "$1" MIME típusú fájlok feltöltése nem engedélyezett.',
'filetype-badtype'            => "'''\".\$1\"''' nem kívánatos fájltípus
: Az engedélyezett fájltípusok listája: \$2",
'filetype-missing'            => 'A fájlnak nincs kiterjesztése (pl. ".jpg").',
'large-file'                  => 'Javasoljuk, hogy a dájl ne legyen nagyobb, mint $1; ennek a fájlnak a mérete $2.',
'largefileserver'             => 'A fájl mérete meghaladja a kiszolgálón beállított legnagyobb értéket.',
'emptyfile'                   => 'Az általad feltöltött fájl üresnek tűnik. Ez a fájlnévben lévő hibás karakter miatt lehet. Ellenőrizd, hogy valóban fel akarod-e tölteni ezt a fájlt.',
'fileexists'                  => 'Már van ilyen nevű fájl. Ellenőrizd a(z) <strong><tt>$1</tt></strong> fájlt, hogy valóban felül akarod-e írni.',
'fileexists-extension'        => 'Van hasonló nevű fájl:<br />
A feltöltendő fájl neve: <strong><tt>$1</tt></strong><br />
A létező fájl neve: <strong><tt>$2</tt></strong><br />
Kérjük, hogy válassz másik nevet.',
'fileexists-thumb'            => "<center>'''Létező kép'''</center>",
'fileexists-thumbnail-yes'    => 'A fájl egy csökkentett méretű képnek <i>(bélyegképnek)</i> tűnik. Kérjük, hogy ellenőrizd a(z) <strong><tt>$1</tt></strong> fájlt.<br />
Ha az ellenőrzött fájl ugyanakkora, mint az eredeti méretű kép, akkor a bélyegképet nem kell külön feltöltened.',
'file-thumbnail-no'           => 'A fájlnév a(z) <strong><tt>$1</tt></strong> karakterlánccal kezdődik. Úgy tűnik, hogy ez egy csökkentett méretű kép <i>(bélyegkép)</i>.
Ha megvan neked a teljes felbontású kép, akkor töltsd fel azt, egyéb esetben kérjük, hogy változtasd meg a fájlnevet.',
'fileexists-forbidden'        => 'Már van ilyen nevű fájl; menj vissza, és töltsd fel a fájlt új néven. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Már van ilyen nevű fájl a megosztott fájlraktárban; menj vissza, és töltsd fel új néven ezt a fájlt. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'A feltöltés sikerült',
'uploadwarning'               => 'Feltöltési figyelmeztetés',
'savefile'                    => 'Fájl mentése',
'uploadedimage'               => '"[[$1]]" feltöltve',
'overwroteimage'              => 'feltöltötték a(z) "[[$1]]" kép új változatát',
'uploaddisabled'              => 'Feltöltések kikapcsolva',
'uploaddisabledtext'          => 'A fájlfeltöltés ebben a wikiben letiltott.',
'uploadscripted'              => 'Ez a fájl olyan HTML- vagy parancsfájlkódot tartalmaz, melyet tévedésből egy webböngésző esetleg értelmezni próbálhatna.',
'uploadcorrupt'               => 'A fájl sérült, vagy hibás a kiterjesztése. Kérjük, hogy ellenőrizd a fájlt, és próbálkozz újra!',
'uploadvirus'                 => 'Ez a fájl vírussal fertőzött! Részletek: $1',
'sourcefilename'              => 'Forrásfájl neve',
'destfilename'                => 'Célmédiafájl neve',
'watchthisupload'             => 'Figyeld ezt a lapot',
'filewasdeleted'              => 'Már töltöttek föl ilyen nevű fájlt, de később törlésre került. Ellenőrizned kell a $1 fájlt a feltöltés folytatás előtt.',
'upload-wasdeleted'           => "'''Vigyázat: egy olyan fájlt akarsz feltölteni, ami korábban már törölve lett.'''

Mielőtt ismét feltöltenéd, nézd meg, miért lett korábban törölve, és ellenőrizd, hogy a törlés indoka nem érvényes-e még. A törlési naplóban a lapról az alábbi bejegyzések szerepelnek:",
'filename-bad-prefix'         => 'Annak a fájlnak a neve, amelyet fel akarsz tölteni <strong>„$1”</strong> karakterekkel kezdődik. Ilyeneket általában a digitális kamerák adnak a fájloknak, automatikusan, azonban ezek nem írják le annak tartalmát. Válassz egy leíró nevet!',

'upload-proto-error'      => 'Hibás protokoll',
'upload-proto-error-text' => 'A távoli feltöltéshez <code>http://</code> vagy <code>ftp://</code> kezdetű URL-ekre van szükség.',
'upload-file-error'       => 'Belső hiba',
'upload-file-error-text'  => 'Belső hiba történt az ideiglenes fájlnak a kiszolgálón történő létrehozásának megkísérlésekor.  Kérjük, hogy lépj kapcsolatba a rendszergazdával.',
'upload-misc-error'       => 'Ismeretlen feltöltési hiba',
'upload-misc-error-text'  => 'A feltöltés során ismeretlen hiba történt.  Kérjük, ellenőrizd, hogy az URL érvényes-e és hozzáférhető-e, majd próbáld újra.  Ha a probléma továbbra is fennáll, akkor lépj kapcsolatba a rendszergazdával.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nem érhető el az URL',
'upload-curl-error6-text'  => 'A megadott URL nem érhető el.  Kérjük, ellenőrizd újra, hogy az URL pontos-e, és a webhely működik-e.',
'upload-curl-error28'      => 'Feltöltési időtúllépés',
'upload-curl-error28-text' => 'A webhely túl sokára válaszolt. Kérjük, ellenőrizd, hogy a webhely elérhető-e, várj egy kicsit, aztán próbáld újra. Kevésbé forgalmas időben is megpróbálhatod.',

'license'            => 'Licenc',
'nolicense'          => 'Válassz licencet!',
'license-nopreview'  => '(Előnézet nem elérhető)',
'upload_source_url'  => ' (egy érvényes, nyilvánosan elérhető URL)',
'upload_source_file' => ' (egy fájl a számítógépeden)',

# Image list
'imagelist'                 => 'Képlista',
'imagelisttext'             => 'Alább $1 kép látható, $2 rendezve.',
'getimagelist'              => 'képlista lehívása',
'ilsubmit'                  => 'Keresés',
'showlast'                  => 'Az utolsó $1 kép $2.',
'byname'                    => 'név szerint',
'bydate'                    => 'dátum szerint',
'bysize'                    => 'méret szerint',
'imgdelete'                 => 'töröl',
'imgdesc'                   => 'leírás',
'imgfile'                   => 'fájl',
'filehist'                  => 'Fájlelőzmények',
'filehist-help'             => 'Kattints egy dátumra/időpontra a fájl akkori állapotában történő megtekintéséhez.',
'filehist-deleteall'        => 'az összes törlése',
'filehist-deleteone'        => 'ennek a törlése',
'filehist-revert'           => 'visszatérés',
'filehist-current'          => 'jelenlegi',
'filehist-datetime'         => 'Dátum/Időpont',
'filehist-user'             => 'Felhasználó',
'filehist-dimensions'       => 'Képméret',
'filehist-filesize'         => 'Fájlméret',
'filehist-comment'          => 'Megjegyzés',
'imagelinks'                => 'Képhivatkozások',
'linkstoimage'              => 'Az alábbi lapok hivatkoznak erre a képre:',
'nolinkstoimage'            => 'Erre a képre nem hivatkozik lap.',
'sharedupload'              => 'Ez a fájlt egy megosztott feltöltés, és más projektek használhatják.',
'shareduploadwiki'          => 'Lásd a [$1 file leírólapját] a további információkért.',
'shareduploadwiki-linktext' => 'fájlleírás oldala',
'noimage'                   => 'Ezen a néven nem létezik médiafájl. Ha szeretnél, $1 egyet.',
'noimage-linktext'          => 'feltölthetsz',
'uploadnewversion-linktext' => 'A fájl újabb változatának felküldése',
'imagelist_date'            => 'Dátum',
'imagelist_name'            => 'Név',
'imagelist_user'            => 'Felhasználó',
'imagelist_size'            => 'Méret',
'imagelist_description'     => 'Leírás',
'imagelist_search_for'      => 'Fájlnév keresése:',

# File reversion
'filerevert'                => '$1 visszaállítása',
'filerevert-legend'         => 'Fájl visszaállítása',
'filerevert-intro'          => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl [$4 verzióját állítod vissza, dátum: $3, $2].</span>',
'filerevert-comment'        => 'Megjegyzés:',
'filerevert-defaultcomment' => 'A $2, $1-i verzió visszaállítása',
'filerevert-submit'         => 'Visszaállítás',
'filerevert-success'        => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl visszaállítása a(z) [$4 verzióra, $3, $2] sikerült.</span>',
'filerevert-badversion'     => 'A megadott időbélyegzésű fájlnak nincs helyi változata.',

# File deletion
'filedelete'             => '$1 törlése',
'filedelete-legend'      => 'Fájl törlése',
'filedelete-intro'       => "A(z) '''[[Media:$1|$1]]''' fájlt törlöd.",
'filedelete-intro-old'   => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl, dátum: [$4 $3, $2] változatát törlöd.</span>',
'filedelete-comment'     => 'Megjegyzés:',
'filedelete-submit'      => 'Törlés',
'filedelete-success'     => "A(z) '''$1''' törlésre került.",
'filedelete-success-old' => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl, dátum: $3, $2 törlése sikerült.</span>',
'filedelete-nofile'      => "A(z) '''$1''' fájl ezen a webhelyen nem létezik.",
'filedelete-nofile-old'  => "There is no archived version of A(z) '''$1''' fájlnak nincs a megadott attribútumú archivált változata.",
'filedelete-iscurrent'   => 'A fájl legutóbbi változatát kísérled meg törölni. Kérjük, hogy előbb állíts vissza egy régebbi verziót.',

# MIME search
'mimesearch'         => 'Keresés MIME-típus alapján',
'mimesearch-summary' => 'Ez az oldal engedélyezi a fájlok MIME-típus alapján történő szűrését. Bevitel: tartalomtípus/altípus, pl. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-típus:',
'download'           => 'letöltés',

# Unwatched pages
'unwatchedpages' => 'Nem figyelt lapok',

# List redirects
'listredirects' => 'Átirányítások listája',

# Unused templates
'unusedtemplates'     => 'Nem használt sablonok',
'unusedtemplatestext' => 'Ez a lap azon sablon névtérben lévő lapokat gyűjti össze, melyek nem találhatók meg más lapokon. Ellenőrizd a hivatkozásokat, mielőtt törölnéd őket.',

# Random page
'randompage'         => 'Lap találomra',
'randompage-nopages' => 'Ebben a névtérben nincsenek lapok.',

# Random redirect
'randomredirect'         => 'Átirányítás találomra',
'randomredirect-nopages' => 'Ebben a névtérben nincsenek átirányítások.',

# Statistics
'statistics'             => 'Statisztika',
'sitestats'              => 'Tartalmi statisztika',
'userstats'              => 'Felhasználói statisztika',
'sitestatstext'          => "{{PLURAL:\$1|is '''1''' lap|van '''\$1''' lap összesen}} az adatbázisban.
Ezek közé tartoznak a \"vitalapok\", oldalak a(z) {{SITENAME}} webhelyről, minimális \"stub\"
lapok, átirányítások és mások, melyek valószínűleg nem minősülnek tartalmi oldalnak.
Ezek kivételével {{PLURAL:\$2|'''1''' lap van, ami|'''\$2''' lap van, ami}} valószínűleg szablyszerű
tartalmi {{PLURAL:\$2|oldal|oldal}}.

'''\$8''' {{PLURAL:\$8|fájlt|fájl}} töltöttek föl.

'''\$3''' {{PLURAL:\$3|page view|lapmegtekintés}}, és '''\$4''' {{PLURAL:\$4|page edit|lapszerkesztés}} volt eddig összesen
a(z) {{SITENAME}} megnyitása óta.
Ez oldalanként átlag '''\$5''' szerkesztés, és '''\$6''' nézet szerkesztésenként.

A [http://meta.wikimedia.org/wiki/Help:Job_queue feladat várólista] hossza '''\$7'''.",
'userstatstext'          => "Jelenleg <b>$1</b> regisztrált felhasználónk van; közülük <b>$2</b> ($4%) $5 (lásd: $3).
'''$2''' (vagy '''$4%''') {{PLURAL:$2|van|van}} $5 jogai.",
'statistics-mostpopular' => 'Legtöbbször megtekintett lapok',

'disambiguations'      => 'Egyértelműsítő lapok',
'disambiguationspage'  => 'Template:Egyért',
'disambiguations-text' => "A következő lapok '''félreérthetőséget megszüntető lapra''' hivatkoznak. Ezeknek a megfelelő témára kell inkább hivatkozniuk.<br />Egy lap akkor tekintendő félreérthetőséget megszüntető lapnak, ha a [[MediaWiki:disambiguationspage]] oldalról hivatkozott sablont használ",

'doubleredirects'     => 'Dupla átirányítások',
'doubleredirectstext' => '<strong>Figyelem:</strong> Ez a lista nem feltétlenül pontos. Ennek általában az oka az, hogy a #REDIRECT alatt további szöveg található.<br /> Minden sor tartalmazza az első és a második átirányítást, valamint a második átirányítás cikkének első sorát, ami általában a „valódi” célt tartalmazza, amire az elsőnek mutatnia kellene.',

'brokenredirects'        => 'Nem létező lapra mutató átirányítások',
'brokenredirectstext'    => 'Az alábbi átirányítások nem létező lapokra mutatnak.',
'brokenredirects-edit'   => '(szerkeszt)',
'brokenredirects-delete' => '(törlés)',

'withoutinterwiki'        => 'Nyelvi hivatkozások nélküli lapok',
'withoutinterwiki-header' => 'A következő lapok nem hivatkoznak más nyelvű változatokra:',

'fewestrevisions' => 'Legkevesebb változattal rendelkező szócikkek',

# Miscellaneous special pages
'nbytes'                  => '$1 bájt',
'ncategories'             => '$1 kategória',
'nlinks'                  => '$1 hivatkozás',
'nmembers'                => '$1 elem',
'nrevisions'              => '$1 változat',
'nviews'                  => '$1 megtekintés',
'specialpage-empty'       => 'Ennek a jelentésnek nincs eredménye.',
'lonelypages'             => 'Árván maradt lapok',
'lonelypagestext'         => 'A következő lapokra nem mutatnak hivatkozások ezen wiki többi lapjáról.',
'uncategorizedpages'      => 'Kategorizálatlan lapok',
'uncategorizedcategories' => 'Kategorizálatlan kategóriák',
'uncategorizedimages'     => 'Kategorizálatlan képek',
'uncategorizedtemplates'  => 'Kategorizálatlan sablonok',
'unusedcategories'        => 'Nem használt kategóriák',
'unusedimages'            => 'Nem használt képek',
'popularpages'            => 'Népszerű lapok',
'wantedcategories'        => 'Keresett kategóriák',
'wantedpages'             => 'Keresett lapok',
'mostlinked'              => 'Legtöbbször hivatkozott oldalak',
'mostlinkedcategories'    => 'Legtöbbször hivatkozott kategóriák',
'mostlinkedtemplates'     => 'Legtöbbször hivatkozott sablonok',
'mostcategories'          => 'A legtöbb kategóriába besorolt szócikkek',
'mostimages'              => 'Legtöbbször hivatkozott képek',
'mostrevisions'           => 'Legtöbb változattal rendelkező szócikkek',
'allpages'                => 'Az összes lap listája',
'prefixindex'             => 'Keresés előtag szerint',
'shortpages'              => 'Rövid lapok',
'longpages'               => 'Hosszú lapok',
'deadendpages'            => 'Zsákutca lapok',
'deadendpagestext'        => 'Az itt található lapok nem kapcsolódnak hivatkozásokkal ezen wiki más oldalaihoz.',
'protectedpages'          => 'Védett oldalak',
'protectedpagestext'      => 'A következő oldalak áthelyezés vagy szerkesztés ellen védettek',
'protectedpagesempty'     => 'Jelenleg nincsenek az ezekkel a paraméterekkel védett oldalak.',
'listusers'               => 'Felhasználók',
'specialpages'            => 'Speciális lapok',
'spheading'               => 'Speciális lapok',
'restrictedpheading'      => 'Korlátozott hozzáférésű speciális lapok',
'rclsub'                  => '(a "$1" lapról hivatkozott lapok)',
'newpages'                => 'Új lapok',
'newpages-username'       => 'Felhasználónév:',
'ancientpages'            => 'Régóta nem változott szócikkek',
'intl'                    => 'Nyelvek közötti hivatkozások',
'move'                    => 'Áthelyezés',
'movethispage'            => 'Lap áthelyezése',
'unusedimagestext'        => '<p>Vedd figyelembe, hogy más lapok - például a nemzetközi {{grammar:k|{{SITENAME}}}} - közvetlenül
hivatkozhatnak egy fájl URL-jére, ezért szerepelhet itt annak
ellenére, hogy aktívan használják.</p>',
'unusedcategoriestext'    => 'A következő kategóriákban egyetlen szócikk, illetve alkategória sem szerepel.',
'notargettitle'           => 'Nincs cél',
'notargettext'            => 'Nem adtál meg lapot vagy usert keresési célpontnak.',

# Book sources
'booksources'               => 'Könyvforrások',
'booksources-search-legend' => 'Könyvforrások keresése',
'booksources-go'            => 'Ugrás',
'booksources-text'          => 'Alább látható a másik webhelyekre mutató hivatkozások listája, ahol új és használt könyveket árulnak, és
további információkat lelhetsz ott az általad keresett könyvekről:',

'categoriespagetext' => 'A wikiben az alábbi kategóriák találhatók.',
'data'               => 'Adatok',
'userrights'         => 'Felhasználói jogok kezelése',
'groups'             => 'Felhasználócsoportok',
'alphaindexline'     => '$1 – $2',
'version'            => 'Névjegy',

# Special:Log
'specialloguserlabel'  => 'Felhasználó:',
'speciallogtitlelabel' => 'Cím:',
'log'                  => 'Rendszernapló',
'all-logs-page'        => 'Minden napló',
'log-search-legend'    => 'Keresés a naplóban',
'log-search-submit'    => 'Ugrás',
'alllogstext'          => 'Az átnevezési, feltöltési, törlési, lapvédelmi, blokkolási, bürokrata és felhasználó-átnevezési naplók közös listája. 
Szűkítheted a listát a naplótípus, a műveletet végző felhasználó vagy az érintett oldal megadásával.',
'logempty'             => 'Nincs egyező tétel a naplóban.',
'log-title-wildcard'   => 'Ezzel a szöveggel kezdődő címek keresése',

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
'allpages-bad-ns'   => 'A(z) {{SITENAME}} webhelyen nincs "$1" névtér.',

# Special:Listusers
'listusersfrom'      => 'Felhasználók listázása a következő névtől kezdve:',
'listusers-submit'   => 'Megjelenítés',
'listusers-noresult' => 'Nem található felhasználó.',

# E-mail user
'mailnologin'     => 'Nincs feladó',
'mailnologintext' => 'Ahhoz hogy másoknak emailt küldhess
[[Special:Userlogin|be kell jelentkezned]]
és meg kell adnod egy érvényes email címet a [[Special:Preferences|beállításaidban]].',
'emailuser'       => 'E-mail küldése ezen szerkesztőnek',
'emailpage'       => 'E-mail küldése',
'emailpagetext'   => 'Ha ez a felhasználó érvényes e-mail címet adott meg, 
akkor ezen űrlap kitöltésével e-mailt tudsz neki küldeni. 
Feladóként a beállításaid között megadott e-mail címed 
fog szerepelni, 
hogy a címzett válaszolni tudjon.',
'usermailererror' => 'A postázó objektum által visszaadott hiba:',
'noemailtitle'    => 'Nincs e-mail cím',
'noemailtext'     => 'Ez a felhasználó nem adta meg az e-mail címét, vagy
nem kíván másoktól leveleket kapni.',
'emailfrom'       => 'Feladó',
'emailto'         => 'Címzett',
'emailsubject'    => 'Téma',
'emailmessage'    => 'Üzenet',
'emailsend'       => 'Küldés',
'emailccme'       => 'Az üzenet másolatát küldje el nekem is e-mailben.',
'emailccsubject'  => '$1-nek küldött $2 tárgyú üzenet másolata',
'emailsent'       => 'E-mail elküldve',
'emailsenttext'   => 'Az e-mail üzenetedet elküldtük.',

# Watchlist
'watchlist'            => 'Figyelőlistám',
'mywatchlist'          => 'Figyelőlistám',
'watchlistfor'         => "('''$1''' részére)",
'nowatchlist'          => 'Nincs lap a figyelőlistádon.',
'watchlistanontext'    => 'A figyelőlistád megtekintéséhez és szerkesztéséhez $1.',
'watchnologin'         => 'Nincs belépve',
'watchnologintext'     => 'Ahhoz, hogy figyelőlistád lehessen, [[Special:Userlogin|be kell lépned]].',
'addedwatch'           => 'Figyelőlistához hozzáfűzve',
'addedwatchtext'       => "A \"[[:\$1]]\" lap hozzáadásra került a [[Special:Watchlist|figyelőlistádhoz]].
Ezután minden, a lapon vagy annak vitalapján történő változást ott fogsz
látni, és a lap '''vastagon''' fog szerepelni a [[Special:Recentchanges|friss változtatások]]
lapon, hogy könnyen észrevehető legyen.

Ha később el akarod távolítani a lapot a figyelőlistádról, akkor ezt az oldalmenü „{{MediaWiki:Unwatchthispage}}” pontjával (vagy a „{{MediaWiki:Unwatch}}” füllel) teheted meg.",
'removedwatch'         => 'Figyelőlistáról eltávolítva',
'removedwatchtext'     => 'A „$1” lapot eltávolítottam a figyelőlistáról.',
'watch'                => 'Lap figyelése',
'watchthispage'        => 'Lap figyelése',
'unwatch'              => 'Lapfigyelés vége',
'unwatchthispage'      => 'Figyelés leállítása',
'notanarticle'         => 'Nem szócikk',
'watchnochange'        => 'Egyik figyelt lap sem változott a megadott időintervallumon belül.',
'watchlist-details'    => '<strong>$1</strong> lap van a figyelőlistádon (a vitalapokon kívül).',
'wlheader-enotif'      => '* Email értesítés engedélyezve.',
'wlheader-showupdated' => "* Azok a lapok, amelyek megváltoztak, mióta utoljára megnézted őket, '''vastagon''' láthatóak.",
'watchmethod-recent'   => 'a figyelt lapokon belüli legfrissebb szerkesztések',
'watchmethod-list'     => 'a legfrissebb szerkesztésekben található figyelt lapok',
'watchlistcontains'    => 'A figyelőlistád $1 lapot tartalmaz.',
'iteminvalidname'      => "Probléma a '$1' elemmel: érvénytelen név...",
'wlnote'               => 'Az utolsó <b>$2</b> óra $1 változtatása látható az alábbiakban.',
'wlshowlast'           => 'Az elmúlt $1 órában | $2 napon | $3 történt változtatások legyenek láthatóak',
'watchlist-show-bots'  => 'Robotok szerkesztéseinek megjelenítése',
'watchlist-hide-bots'  => 'Robotok szerkesztéseinek elrejtése',
'watchlist-show-own'   => 'Saját szerkesztések megjelenítése',
'watchlist-hide-own'   => 'Saját szerkesztések elrejtése',
'watchlist-show-minor' => 'Apró módosítások megjelenítése',
'watchlist-hide-minor' => 'Apró módosítások elrejtése',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Figyelés...',
'unwatching' => 'Figyelés befejezése...',

'enotif_mailer'                => '{{SITENAME}} Értesítéspostázó',
'enotif_reset'                 => 'Az összes lap megjelölése felkeresettként',
'enotif_newpagetext'           => 'Ez egy új lap.',
'enotif_impersonal_salutation' => '{{SITENAME}} felhasználó',
'changed'                      => 'megváltoztatta',
'created'                      => 'létrehozta',
'enotif_subject'               => 'A(z) {{SITENAME}} $PAGETITLE című oldalát $CHANGEDORCREATED $PAGEEDITOR',
'enotif_lastvisited'           => 'Lásd a $1 lapot az utolsó látogatásod történt változtatásokért.',
'enotif_lastdiff'              => 'Lásd a $1 lapot ezen változtatás megtekintéséhez.',
'enotif_anon_editor'           => '$1 névtelen felhasználó',
'enotif_body'                  => 'Kedves $WATCHINGUSERNAME!
	

A(z) {{SITENAME}} $PAGETITLE című oldalát $CHANGEDORCREATED $PAGEEDITDATE-n $PAGEEDITOR, a jelenlegi verziót lásd a $PAGETITLE_URL webcímen.

$NEWPAGE

A szerkesztő összegzése: $PAGESUMMARY $PAGEMINOREDIT

A szerkesztő elérhetősége:
levél: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ha nem keresed fel ezt az oldalt, akkor nem kapsz értesítést a további változtatásokról. A figyelőlistádon lévő lapok értesítési jelzőit is alaphelyzetbe állítottad.

             Baráti üdvözlettel: {{SITENAME}} értesítő rendszere

--
A figyelőlistád beállításainak módosításához keresd fel a
{{fullurl:{{ns:special}}:Figyelőlistám/edit}} címet

Visszajelzés és további segítség:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Lap törlése',
'confirm'                     => 'Megerősítés',
'excontent'                   => "a lap tartalma: '$1'",
'excontentauthor'             => "a lap tartalma: '$1' (és csak '$2' szerkesztette)",
'exbeforeblank'               => 'az eltávolítás előtti tartalom: $1',
'exblank'                     => 'a lap üres volt',
'confirmdelete'               => 'Törlés megerősítése',
'deletesub'                   => '("$1" törlése)',
'historywarning'              => 'Figyelem: a lapnak, amit törölni készülsz, története van:',
'confirmdeletetext'           => 'Egy lap vagy kép teljes laptörténetével együtti 
végleges törlésére készülsz.  
Kérjük, erősítsd meg, hogy valóban ezt szándékozod tenni, 
átlátod a következményeit, és a [[{{MediaWiki:Policy-url}}|törlési irányelvekkel]] 
összhangban cselekedsz.',
'actioncomplete'              => 'Művelet végrehajtva',
'deletedtext'                 => 'A(z) „$1” lapot törölted.  
A legutóbbi törlések listájához lásd a $2 lapot.',
'deletedarticle'              => '"$1" törölve',
'dellogpage'                  => 'Törlési_napló',
'dellogpagetext'              => 'Itt láthatók a legutóbb törölt lapok. Minden időpont a server órája (UTC) szerint értendő.',
'deletionlog'                 => 'törlési napló',
'reverted'                    => 'Visszaállítva a korábbi változatra',
'deletecomment'               => 'A törlés oka',
'rollback'                    => 'Szerkesztések visszaállítása',
'rollback_short'              => 'Visszaállítás',
'rollbacklink'                => 'visszaállítás',
'rollbackfailed'              => 'A visszaállítás nem sikerült',
'cantrollback'                => 'Nem lehet visszaállítani: az utolsó szerkesztést végző felhasználó az egyetlen, aki a lapot szerkesztette.',
'alreadyrolled'               => '[[:$1]] utolsó, [[User:$2|$2]] ([[User talk:$2|Vita]] | [[Special:Contributions/$2|Szerkesztései]] | [[Special:blockip/$2|Blokkolás]]) általi szerkesztését nem lehet visszavonni: időközben valaki már visszavonta, vagy szerkesztette a lapot.

Az utolsó szerkesztést [[User:$3|$3]] ([[User talk:$3|vita]]) végezte.',
'editcomment'                 => 'A változtatás összegzése "<i>$1</i>" volt.', # only shown if there is an edit comment
'revertpage'                  => '[[Special:Contributions/$2|$2]] szerkesztései visszaállítva $1 utolsó változatára',
'rollback-success'            => '$1 visszaállított szerkesztései; az utolsó verzióra visszaállította: $2.',
'sessionfailure'              => 'Úgy látszik, hogy probléma van a bejelentkezési munkameneteddel;
ez a művelet a munkamenet eltérítése miatti óvatosságból megszakadt.
Kérjük, hogy nyomd meg a "vissza" gombot, és töltsd le újra az oldalt, ahonnan jöttél, majd próbáld újra.',
'protectlogpage'              => 'Lapvédelmi_napló',
'protectlogtext'              => 'Ez a lezárt/megnyitott lapok listája. A részleteket a zárt lapok irányelve tartalmazza.',
'protectedarticle'            => 'levédte a(z) [[$1]] lapot',
'modifiedarticleprotection'   => 'a védelmi szint a következőre változott: "[[$1]]"',
'unprotectedarticle'          => 'eltávolította a védelmet a(z) "[[$1]]" lapról',
'protectsub'                  => '(„$1” levédése)',
'confirmprotect'              => 'Levédés megerősítése',
'protectcomment'              => 'A védelem oka',
'protectexpiry'               => 'Időtartam',
'protect_expiry_invalid'      => 'A lejárati idő érvénytelen.',
'protect_expiry_old'          => 'A lejárati idő a múltban van.',
'unprotectsub'                => '(„$1” védelmének feloldása)',
'protect-unchain'             => 'Átnevezési jogok állítása külön',
'protect-text'                => 'Itt megtekintheted és módosíthatod a(z) [[$1]] lap védelmi szintjét. Légy szives, tartsd be a védett lapokkal kapcsolatos előírásokat.',
'protect-locked-blocked'      => 'Nem változtathatod meg a védelmi szinteket, amíg blokkolnak. Itt vannak a(z) 
<strong>$1</strong> lap jelenlegi beállításai:',
'protect-locked-dblock'       => 'A védelmi szinteket egy aktív adatbázis zárolás miatt nem változtathatod meg.
Itt vannak a(z) <strong>$1</strong> lap jelenlegi beállításai:',
'protect-locked-access'       => 'A fiókod számára nem engedélyezett a védelmi szintek megváltoztatása.
Itt vannak a(z) <strong>$1</strong> lap jelenlegi beállításai:',
'protect-cascadeon'           => 'A lap le van védve, mert tartalmazzák az alábbi lapok, amelyeken be van kapcsolva a kaszkád védelem. Ezen lap védelmi szintjének a megváltoztatása a kaszkád védelemre nincs hatással.',
'protect-default'             => '(alapértelmezett)',
'protect-fallback'            => '"$1" engedély szükséges hozzá',
'protect-level-autoconfirmed' => 'Csak regisztrált felhasználók',
'protect-level-sysop'         => 'Csak rendszerfelelősök',
'protect-summary-cascade'     => 'részben fedő',
'protect-expiring'            => 'lejár: $1 (UTC)',
'protect-cascade'             => 'Kaszkád védelem – védjen le minden lapot, amit ez a lap tartalmaz.',
'restriction-type'            => 'Engedély:',
'restriction-level'           => 'Korlátozási szint:',
'minimum-size'                => 'Legkisebb méret',
'maximum-size'                => 'Legnagyobb méret',
'pagesize'                    => '(bájt)',

# Restrictions (nouns)
'restriction-edit' => 'Szerkesztés',
'restriction-move' => 'Átmozgatás',

# Restriction levels
'restriction-level-sysop'         => 'teljesen védett',
'restriction-level-autoconfirmed' => 'félig védett',
'restriction-level-all'           => 'bármilyen szint',

# Undelete
'undelete'                     => 'Törölt lap megtekintése',
'undeletepage'                 => 'Törölt lapok megtekintése és visszaállítása',
'viewdeletedpage'              => 'Törölt lapok megtekintése',
'undeletepagetext'             => 'Az alábbi lapokat törölték, de még visszaállíthatók az archívumból 
(az archívumot időről időre üríthetik!).',
'undeleteextrahelp'            => "A teljes lap visszaállításához hagyd kijelöletlenül az összes jelölőnégyzetet, és nyomd meg a '''''Visszaállítás!''''' gombot. A részleges visszaállításhoz jelöld be a kívánt változatok melletti jelölőnégyzeteket, és nyomd meg a '''''Visszaállítás!''''' gombot. Az '''''Alaphelyzet''''' gomb megnyomásával törlöd a megjegyzés mezőt és az összes jelölőnégyzetet.",
'undeleterevisions'            => '$1 változat archiválva',
'undeletehistory'              => 'Ha visszaállítasz egy lapot, azzal visszahozod a laptörténet összes változatát.  
Ha a lap törlése óta azonos néven már létrehoztak egy újabb lapot, 
a helyreállított változatok a laptörténet elejére kerülnek be, 
a jelenlegi lapváltozat módosítása nélkül.',
'undeleterevdel'               => 'A visszavonás nem hajtható végre, ha a legfrissebb lapváltozat részben
törlését eredmémyezi. Ilyen esetekben törölnöd kell a legújabb törölt változatok kijelölését, vagy megszüntetni az elrejtésüket. Azon fájlváltozatok,
melyek megtekintése a számodra nem engedélyezett, nem kerülnek visszaállításra.',
'undeletehistorynoadmin'       => 'Ezt a szócikket törölték. A törlés okát alább az összegzésben 
láthatod, az oldalt a törlés előtt szerkesztő felhasználók részleteivel együtt. Ezeknek 
a törölt változatoknak a tényleges szövege csak az adminisztrátorok számára hozzáférhető.',
'undelete-revision'            => '$1 változatának törlése kész ($2), $3:',
'undeleterevision-missing'     => 'Érvénytelen vagy hiányzó változat. Lehet, hogy rossz hivatkozásod van, ill. a
változatot visszaállították vagy eltávolították az archívumból.',
'undelete-nodiff'              => 'Nem található korábbi változat.',
'undeletebtn'                  => 'Visszaállítás!',
'undeletereset'                => 'Alaphelyzet',
'undeletecomment'              => 'Visszaállítás oka:',
'undeletedarticle'             => '"$1" visszaállítása kész',
'undeletedrevisions'           => '$1 változat visszaállítása kész',
'undeletedrevisions-files'     => '$1 változat és $2 fájl visszaállítása kész',
'undeletedfiles'               => '$1 fájl visszaállítása kész',
'cannotundelete'               => 'Nem lehet a lapot visszaállítani; lehet, hogy azt már valaki visszaállította.',
'undeletedpage'                => "<big>'''$1 visszaállítása kész'''</big>

Lásd a [[Special:Log/delete|törlési naplót]] a legutóbbi törlések és helyreállítások listájához.",
'undelete-header'              => 'A legutoljára törölt oldalakat lásd a [[Special:Log/delete|törlési naplóban]].',
'undelete-search-box'          => 'A törölt lapok keresése',
'undelete-search-prefix'       => 'A következővel kezdődő lapok megjelenítése:',
'undelete-search-submit'       => 'Keresés',
'undelete-no-results'          => 'Nem található egyező lap a törlési archívumban.',
'undelete-filename-mismatch'   => 'Nem állítható vissza a(z) $1 időbélyegzésű fájlváltozat: fájlnév típuskeveredés',
'undelete-bad-store-key'       => 'Nem állítható vissza a(z) $1 időbélyegzésű fájlváltozat: a fájl hiányzott törlés előtt.',
'undelete-cleanup-error'       => 'Hiba történt a nem használt "$1" archív fájl törlésekor.',
'undelete-missing-filearchive' => 'Nem állítható vissza a(z) $1 azonosítószámú fájlarchívum, mert nem található az adatbázisban. Lehet, hogy már visszaállították.',
'undelete-error-short'         => 'Hiba a következő fájl visszaállításakor: $1',
'undelete-error-long'          => 'Hibák merültek föl a következő fájl visszaállításakor:

$1',

# Namespace form on various pages
'namespace'      => 'Névtér:',
'invert'         => 'Kijelölés megfordítása',
'blanknamespace' => '(Fő)',

# Contributions
'contributions' => 'Szerkesztői közreműködések',
'mycontris'     => 'Közreműködéseim',
'contribsub2'   => '$1 ($2) cikkhez',
'nocontribs'    => 'Nem található a feltételeknek megfelelő változtatás.',
'ucnote'        => 'Alább <b>$1</b> módosításai láthatóak az elmúlt <b>$2</b> napban.',
'uclinks'       => 'Az utolsó $1 változtatás megtekintése; az utolsó $2 nap megtekintése.',
'uctop'         => ' (utolsó)',
'month'         => 'Hónaptól (és korábban):',
'year'          => 'Évtől (és korábban):',

'sp-contributions-newest'      => 'Legújabb',
'sp-contributions-oldest'      => 'Legrégebbi',
'sp-contributions-newer'       => '$1 újabb',
'sp-contributions-older'       => '$1 régebbi',
'sp-contributions-newbies'     => 'Csak az új fiókok cikkeinek megjelenítése',
'sp-contributions-newbies-sub' => 'Új szerkesztők lapjai',
'sp-contributions-blocklog'    => 'Blokkolási napló',
'sp-contributions-search'      => 'Cikkek keresése',
'sp-contributions-username'    => 'IP-cím vagy felhasználónév:',
'sp-contributions-submit'      => 'Szűrés',

'sp-newimages-showfrom' => 'Új képek mutatása $1 után',

# What links here
'whatlinkshere'       => 'Mi hivatkozik erre',
'whatlinkshere-title' => 'A(z) $1 lapra hivatkozó lapok',
'whatlinkshere-page'  => 'Oldal:',
'linklistsub'         => '(Hivatkozások )',
'linkshere'           => 'Az alábbi lapok hivatkoznak erre: [[:$1]]',
'nolinkshere'         => 'Erre a lapra semmi nem hivatkozik: [[:$1]]',
'nolinkshere-ns'      => "A kiválasztott nvtartományban egy lap sem hivatkozik a(z) '''[[:$1]]''' szócikkre.",
'isredirect'          => 'átirányítás',
'istemplate'          => 'beillesztve',
'whatlinkshere-prev'  => '{{PLURAL:$1|previous|előző $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|next|következő $1}}',
'whatlinkshere-links' => '← hivatkozások',

# Block/unblock
'blockip'                     => 'Blokkolás',
'blockiptext'                 => 'Az alábbi űrlappal blokkolhatod egy adott IP-cím 
vagy felhasználónév írási hozzáférését. Ezt az 
[[{{MediaWiki:Policy-url}}|adatvédelmi szabályzat]] 
alapján csak a vandalizmus megelőzése érdekében kell 
megtenni. Írj be alább egy konkrét indokot (például 
barbár módra elbánt oldalak idézése).',
'ipaddress'                   => 'IP cím',
'ipadressorusername'          => 'IP cím vagy felhasználónév',
'ipbexpiry'                   => 'Lejárat',
'ipbreason'                   => 'Blokkolás oka',
'ipbreasonotherlist'          => 'Egyéb indok',
'ipbreason-dropdown'          => '
*Gyakori blokkolási indokok
** Hamis adatok beszúrása
** Tartalom eltávolítása a lapokból
** Külső webhelyekre mutató hivatkozások spammelése
** Értelmetlenség beszúrása az oldalakba
** Megfélemlítő viselkedés/zaklatás
** Visszaélés több fiókkal
** Elfogadhatatlan felhasználónév',
'ipbanononly'                 => 'Csak anonim felhasználók blokkolása',
'ipbcreateaccount'            => 'Új regisztráció megakadályozása',
'ipbemailban'                 => 'A felhasználó e-amil küldésének megakadályozása',
'ipbenableautoblock'          => 'A szerkesztő által használt IP-címek automatikus blokkolása',
'ipbsubmit'                   => 'Blokkolás',
'ipbother'                    => 'Más időtartam',
'ipboptions'                  => '2 óra:2 hours,1 nap:1 day,3 nap:3 days,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite',
'ipbotheroption'              => 'Más időtartam',
'ipbotherreason'              => 'Egyéb/további indok:',
'ipbhidename'                 => 'A felhasználónév/IP elrejtése a blokkolási naplóból, az aktív blokkolási listából és a felhasználólistából',
'badipaddress'                => 'Érvénytelen IP cím',
'blockipsuccesssub'           => 'Sikeres blokkolás',
'blockipsuccesstext'          => '„[[{{ns:special}}:Contributions/$1|$1]]” felhasználót blokkoltad. 
<br />Lásd a [[{{ns:special}}:Blokkolt_IP_lista|blokkolt IP címek listáját]] az érvényben lévő blokkok áttekintéséhez.',
'ipb-edit-dropdown'           => 'A blokkolási indokok szerkesztése',
'ipb-unblock-addr'            => '$1 blokkjának feloldása',
'ipb-unblock'                 => 'Felhasználónév vagy IP-cím blokkolásának feloldása',
'ipb-blocklist-addr'          => '$1 aktív blokkjainak megtekintése',
'ipb-blocklist'               => 'Létező blokkok megtekintése',
'unblockip'                   => 'Felhasználó blokkolásának feloldása',
'unblockiptext'               => 'Az alábbi űrlap használatával visszaállíthatod egy korábban már blokkolt IP-cím vagy felhasználónév írási hozzáférését.',
'ipusubmit'                   => 'Blokk feloldása',
'unblocked'                   => '[[User:$1|$1]] blokkolása feloldva',
'unblocked-id'                => '$1 blokkolása feloldásra került',
'ipblocklist'                 => 'Blokkolt IP címek listája',
'ipblocklist-legend'          => 'Blokkolt felhasználó keresése',
'ipblocklist-username'        => 'Felhasználónév vagy IP-cím:',
'ipblocklist-summary'         => 'Lásd még a [[Special:log/block|blokkolási naplót]].',
'ipblocklist-submit'          => 'Keresés',
'blocklistline'               => '$1, $2 blokkolta $3 felhasználót (lejárat: $4)',
'infiniteblock'               => 'korlátlan',
'expiringblock'               => '$1-n lejár',
'anononlyblock'               => 'csak anon.',
'noautoblockblock'            => 'az automatikus blokkolás letiltott',
'createaccountblock'          => 'új felhasználó létrehozása blokkolva',
'emailblock'                  => 'e-mail cím blokkolva',
'ipblocklist-empty'           => 'A blokkoltak listája üres.',
'ipblocklist-no-results'      => 'A kért IP-cím vagy felhazsnálónév nem blokkolt.',
'blocklink'                   => 'Blokkolás',
'unblocklink'                 => 'blokk feloldása',
'contribslink'                => 'Szerkesztései',
'autoblocker'                 => "Az általad használt IP-cím autoblokkolva van, mivel korábban a kitiltott „[[User:$1|$1]]” használta. ($1 blokkolásának indoklása: „'''$2'''”) Ha nem te vagy $1, lépj kapcsolatba valamelyik adminisztrátorral, és kérd az autoblokk feloldását. Ne felejtsd el megírni neki, hogy kinek szóló blokkba ütköztél bele!",
'blocklogpage'                => 'Blokkolási_napló',
'blocklogentry'               => '"$1" blokkolva $2 $3 időtartamra',
'blocklogtext'                => 'Ez a felhasználók blokkolásának és feloldásának naplója. 
Az automatikusan blokkolt IP-címek nem szerepelnek a listában. Lásd még [[Special:Ipblocklist|a jelenleg életben lévő blokkolások és kitiltások listáját]].',
'unblocklogentry'             => '"$1" blokkolása feloldva',
'block-log-flags-anononly'    => 'csak névtelen felhasználók',
'block-log-flags-nocreate'    => 'a fióklétrehozás letiltott',
'block-log-flags-noautoblock' => 'az automatikus blokkolás letiltott',
'block-log-flags-noemail'     => 'e-mail blokkolva',
'range_block_disabled'        => 'A rendszerfelelős tartományblokkolás létrehozási képessége letiltott.',
'ipb_expiry_invalid'          => 'Hibás lejárati dátum.',
'ipb_already_blocked'         => '"$1" már blokkolva',
'ipb_cant_unblock'            => 'Hiba: A(z) $1 blokkoéási azonosító nem található. Lehet, hogy már feloldották a blokkolását.',
'ip_range_invalid'            => 'Érvénytelen IP-tartomány.',
'blockme'                     => 'Saját magam blokkolása',
'proxyblocker'                => 'Proxyblokkoló',
'proxyblocker-disabled'       => 'Ez a funkció le van tiltva.',
'proxyblockreason'            => "Az IP címed ''open proxy'' probléma miatt le van tiltva. Vedd fel a kapcsolatot egy informatikussal vagy az internet szolgáltatóddal ezen súlyos biztonsági probléma ügyében.",
'proxyblocksuccess'           => 'Kész.',
'sorbsreason'                 => 'Az IP-címed nyitott proxyként szerepel e webhely által használt DNSBL listán.',
'sorbs_create_account_reason' => 'Az IP-címed nyitott proxyként szerepel e webhely által használt DNSBL listán. Nem hozhatsz létre fiókot.',

# Developer tools
'lockdb'              => 'Adatbázis zárolása',
'unlockdb'            => 'Adatbázis kinyitása',
'lockdbtext'          => 'Az adatbázis zárolása felfüggeszti valamennyi felhasználó 
számára a lapok szerkesztésének, a beállításaik módosításának, és olyan más 
dolgoknak a képességét, amihez az adatbázisban kell 
változtatni. Kérjük, erősítsd meg, hogy ezt kívánod tenni, és a karbantartás 
befejezése után kinyitod az adatbázist.',
'unlockdbtext'        => 'Az adatbázis kinyitása visszaállítja valamennyi felhasználó 
számára a lapok szerkesztésének, a beállításaik módosításának, és olyan más 
dolgoknak a képességét, amihez az adatbázisban kell 
változtatni. Kérjük, erősítsd meg, hogy ezt kívánod tenni.',
'lockconfirm'         => 'Igen, valóban zárolni akarom az adatbázist.',
'unlockconfirm'       => 'Igen, valóban ki akarom nyitni az adatbázist.',
'lockbtn'             => 'Adatbázis zárolása',
'unlockbtn'           => 'Adatbázis kinyitása',
'locknoconfirm'       => 'Nem jelölted ki a megerősítő jelölőnégyzetet.',
'lockdbsuccesssub'    => 'Az adatbázis zárolása sikerült',
'unlockdbsuccesssub'  => 'Az adatbázis zárolásának eltávolítása kész',
'lockdbsuccesstext'   => 'Az adatbázist zárolták.
<br />A karbantartás befejezése után ne feledd el [[Special:Unlockdb|kinyitni]].',
'unlockdbsuccesstext' => 'Az adatbázis kinyitása kész.',
'lockfilenotwritable' => 'Az adatbázist zároló fájl nem írható. Az adatbázis zárolásához vagy kinyitásához ennek a webkiszolgáló által írhatónak kell lennie.',
'databasenotlocked'   => 'Az adatbázis nincs lezárva.',

# Move page
'movepage'                => 'Lap áthelyezése',
'movepagetext'            => "Az alábbi űrlap használatával nevezhetsz át egy lapot, és 
helyezheted át előzményeit az új névre. 
A régi cím az új címre átirányító lap lesz. A régi lapcímre 
mutató hivatkozások változatlanok maradnak; a rossz 
átirányításokat ellenőrizd. Te vagy a felelős annak biztosításáért, 
hogy a hivatkozások továbbítsanak ahhoz a ponthoz, 
ahová feltehetőleg vinniük kell.

A lap '''nem''' kerül áthelyezésre, ha már van olyan című új lap, 
hacsak nem üres vagy átirányítás, és nincs szerkesztési előzménye. 
Ez azt jelenti, hogy visszanevezheted az oldalt az eredeti nevére, 
ha hibázol, létező oldalt pedig 
nem tudsz felülírni.

<b>FIGYELEM!</b>
Népszerű oldalak esetén ez drasztikus és nem várt változtatás lehet;
győződj meg róla a folytatás előtt, hogy tisztában vagy-e 
a következményekkel.",
'movepagetalktext'        => "A kapcsolódó vitalap automatikusan áthelyezésre kerül vele együtt, '''ha:'''
*Az új néven már van nem üres vitalap, vagy
*Törlöd az alábbi jelölőnégyzet kijelölését.

Ezekben az esetekben szükség esetén kézzel kell áthelyezned vagy egyesítened a lapot.",
'movearticle'             => 'Áthelyezendő lap:',
'movenologin'             => 'Nem jelentkeztél be',
'movenologintext'         => 'Regisztrált felhasználónak kell lenned, és [[Special:Userlogin|be kell jelentkezned]], ha át akatsz helyezni egy lapot.',
'movenotallowed'          => 'A lapok áthelyezése ebben a wikiben a számdra nem engedélyezett.',
'newtitle'                => 'Az új cím:',
'move-watch'              => 'A lap figyelése',
'movepagebtn'             => 'Lap áthelyezése',
'pagemovedsub'            => 'Az áthelyezés sikerült',
'movepage-moved'          => '<big>A(z) \'\'\'"$1" lap áthelyezésre került ide: "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Már van ilyen nevű lap, vagy az általad
választott név érvénytelen. Kérjük, válassz egy másik nevet.
Ha már létezik ilyen nevű lap, akkor az [[{{ns:project}}:Azonnali törlés]] lapon kérd annak törlését.',
'talkexists'              => 'A lap áthelyezése sikerült, de a hozzá tartozó vitalapot nem tudtam áthelyezni mert már létezik egy egyező nevű
lap az új helyen. Kérjük gondoskodj a két lap összefűzéséről.',
'movetalk'                => 'A kapcsolódó vitalap áthelyezése',
'talkpagemoved'           => 'Az oldal vitalapját is áthelyeztem.',
'talkpagenotmoved'        => 'Az oldal vitalapja <strong>nem került</strong> áthelyezésre.',
'1movedto2'               => '[[$1]] átnevezve [[$2]] névre',
'1movedto2_redir'         => '[[$1]] átnevezve [[$2]] névre (átirányítást felülírva)',
'movelogpage'             => 'Napló áthelyezése',
'movelogpagetext'         => 'Alább látható az áthelyezett lapok listája.',
'movereason'              => 'Indok:',
'revertmove'              => 'visszaállítás',
'delete_and_move'         => 'Törlés és áthelyezés',
'delete_and_move_text'    => '== Törlés szükséges ==

Az átnevezés céljaként megadott „[[$1]]” szócikk már létezik.  Ha az átnevezést végre akarod hajtani, ezt a lapot törölni kell.  Valóban ezt szeretnéd?',
'delete_and_move_confirm' => 'Igen, töröld a lapot',
'delete_and_move_reason'  => 'átnevezendő lap célneve felszabadítva',
'selfmove'                => 'A cikk jelenlegi címe megegyezik azzal, amire át szeretnéd mozgatni. Egy szócikket saját magára mozgatni nem lehet.',
'immobile_namespace'      => 'A forrás- vagy a célcím speciális típusú; nem helyezetsz át lapokat abba a névtérbe vagy onnan.',

# Export
'export'            => 'Lapok exportálása',
'exporttext'        => 'Egy adott lap vagy lapcsoport szövegét és laptörténetét exportálhatod XML-be. A kapott 
fájlt importálhatod egy másik MediaWiki alapú rendszerbe 
a Special:Import lapon keresztül.

Lapok exportálásához add meg a címüket a lenti szövegdobozban (minden címet külön sorba), és válaszd ki, 
hogy az összes korábbi változatra és a teljes laptörténetekre szükséged van-e, vagy csak az aktuális 
változatok és a legutolsó változtatásokra vonatkozó információk kellenek.

Az utóbbi esetben közvetlen hivatkozást is használhatsz, például a [[Special:Export/{{MediaWiki:Mainpage}}]] a [[{{MediaWiki:Mainpage}}]] nevű lapot exportálja.',
'exportcuronly'     => 'Csak a legfrissebb állapot, teljes laptörténet nélkül',
'exportnohistory'   => "----
'''Megjegyzés:''' A lapok teljes előzményeinek ezen az űrlapon keresztül történő exportálása teljesítményporlbémák miatt letiltott.",
'export-submit'     => 'Exportálás',
'export-addcattext' => 'Lapok hozzáadása kategóriából:',
'export-addcat'     => 'Hozzáadás',
'export-download'   => 'A fájlban történő mentés felkínálása',

# Namespace 8 related
'allmessages'               => 'Rendszerüzenetek',
'allmessagesname'           => 'Név',
'allmessagesdefault'        => 'Alapértelmezett szöveg',
'allmessagescurrent'        => 'Jelenlegi szöveg',
'allmessagestext'           => 'Ez a MediaWiki névtérben elérhető összes üzenet listája.',
'allmessagesnotsupportedDB' => "A '''''{{ns:special}}:Allmessages''''' lap nem használható, mert a '''\$wgUseDatabaseMessages''' ki van kapcsolva.",
'allmessagesfilter'         => 'Üzenetnevek szűrése:',
'allmessagesmodified'       => 'Csak a módosítottak mutatása',

# Thumbnails
'thumbnail-more'           => 'Nagyít',
'missingimage'             => '<b>Hiányzó kép</b><br /><i>$1</i>',
'filemissing'              => 'Hiányzó fájl',
'thumbnail_error'          => 'Hiba a bélyegkép létrehozásakor: $1',
'djvu_page_error'          => 'A DjVu lap a tartományon kívülre esik',
'djvu_no_xml'              => 'Nem olvasható ki a DjVu fájl XML-je',
'thumbnail_invalid_params' => 'Érvénytelen bélyegkép paraméterek',
'thumbnail_dest_directory' => 'Nem hozható létre a célkönyvtár',

# Special:Import
'import'                     => 'Lapok importálása',
'importinterwiki'            => 'Transwiki importálása',
'import-interwiki-text'      => 'Válaszd ki az importálandó wikit és lapcímet.
A változatok dátumai és a szerkesztők nevei megőrzésre kerülnek.
Valamennyi transwiki importálási művelet az [[Special:Log/import|importálási naplóban]] kerül naplózásra.',
'import-interwiki-history'   => 'A lap összes előzményváltozatainak másolása',
'import-interwiki-submit'    => 'Importálás',
'import-interwiki-namespace' => 'A lapok átvitele névtérbe:',
'importtext'                 => 'Kérjük, hogy a fájlt a forráswikiből a Special:Export segédeszköz használatával exportáld, mentsd a lemezedre, és töltsd ide föl.',
'importstart'                => 'Lapok importálása...',
'import-revision-count'      => '$1 {{PLURAL:$1|revision|változatok}}',
'importnopages'              => 'Nincs importálandó lap.',
'importfailed'               => 'Az importálás nem sikerült: $1',
'importunknownsource'        => 'Ismeretlen import forrástípus',
'importcantopen'             => 'Nem nyitható meg az importfájl',
'importbadinterwiki'         => 'Rossz interwiki hivatkozás',
'importnotext'               => 'Üres, vagy nincs szöveg',
'importsuccess'              => 'Az importálás sikerült!',
'importhistoryconflict'      => 'Ütköző előzményváltozat létezik (lehet, hogy már importálták ezt a lapot)',
'importnosources'            => 'Nincsenek transzwikiimport-források definiálva, a közvetlen laptörténet-felküldés pedig nem megengedett.',
'importnofile'               => 'Nem került importfájl feltöltésre.',
'importuploaderror'          => 'Az importfájl feltöltése nem sikerült; lehet, hogy a fájl nagyobb, mint a megengedett feltöltési méret.',

# Import log
'importlogpage'                    => 'Importnapló',
'importlogpagetext'                => 'Lapok szerkesztési előzményekkel történő adminisztratív imporálása más wikikből.',
'import-logentry-upload'           => '[[$1]] importálása fájlfeltöltéssel kész',
'import-logentry-upload-detail'    => '$1 változat',
'import-logentry-interwiki-detail' => '$1 változat innen: $2',

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
'tooltip-ca-edit'                 => 'Te is szerkesztheted ezt az oldalt. Mentés előtt használd az előnézet gombot.',
'tooltip-ca-addsection'           => 'Újabb fejezet nyitása a vitában.',
'tooltip-ca-viewsource'           => 'Ez egy védett lap. Ide kattintva megnézheted a forrását.',
'tooltip-ca-history'              => 'A lap korábbi változatai',
'tooltip-ca-protect'              => 'A lap levédése',
'tooltip-ca-delete'               => 'A lap törlése',
'tooltip-ca-undelete'             => 'A törölt lapváltozatok visszaállítása',
'tooltip-ca-move'                 => 'A lap áthelyezése',
'tooltip-ca-watch'                => 'A lap hozzáadása a figyelőlistádhoz',
'tooltip-ca-unwatch'              => 'A lap eltávolítása a figyelőlistádról',
'tooltip-search'                  => 'Keresés a wikiben',
'tooltip-search-go'               => 'Ugrás a megadott lapra, ha létezik',
'tooltip-search-fulltext'         => 'Oldalak keresése a megadott szöveg alapján',
'tooltip-p-logo'                  => 'Főlap',
'tooltip-n-mainpage'              => 'A főlap felkeresése',
'tooltip-n-portal'                => 'A közösségről, miben segíthetsz, mit hol találsz meg',
'tooltip-n-currentevents'         => 'Háttérinformáció az aktuális eseményekről',
'tooltip-n-recentchanges'         => 'A wikiben történt legutóbbi változtatások listája',
'tooltip-n-randompage'            => 'Egy véletlenszerűen kiválasztott lap betöltése',
'tooltip-n-help'                  => 'Ha bármi problémád van...',
'tooltip-n-sitesupport'           => 'Támogass minket!',
'tooltip-t-whatlinkshere'         => 'Az erre a lapra hivatkozó más lapok listája',
'tooltip-t-recentchangeslinked'   => 'Az erről a lapról hivatkozott lapok utolsó változtatásai',
'tooltip-feed-rss'                => 'A lap tartalma RSS hírcsatorna formájában',
'tooltip-feed-atom'               => 'A lap tartalma Atom hírcsatorna formájában',
'tooltip-t-contributions'         => 'A felhasználó közreműködéseinek listája',
'tooltip-t-emailuser'             => 'Írj levelet ennek a felhasználónak!',
'tooltip-t-upload'                => 'Képek vagy egyéb fájlok feltöltése',
'tooltip-t-specialpages'          => 'Az összes speciális lap listája',
'tooltip-t-print'                 => 'A lap nyomtatható változata',
'tooltip-t-permalink'             => 'Állandó hivatkozás a lap ezen változatához',
'tooltip-ca-nstab-main'           => 'A lap megtekintése',
'tooltip-ca-nstab-user'           => 'A felhasználói lap megtekintése',
'tooltip-ca-nstab-media'          => 'A fájlleíró lap megtekintése',
'tooltip-ca-nstab-special'        => 'Ez egy speciális lap, nem szerkesztheted.',
'tooltip-ca-nstab-project'        => 'A projektlap megtekintése',
'tooltip-ca-nstab-image'          => 'A képleíró lap megtekintése',
'tooltip-ca-nstab-mediawiki'      => 'A rendszerüzenet megtekintése',
'tooltip-ca-nstab-template'       => 'A sablon megtekintése',
'tooltip-ca-nstab-help'           => 'A súgólap megtekintése',
'tooltip-ca-nstab-category'       => 'A kategória megtekintése',
'tooltip-minoredit'               => 'A szerkesztés megjelölése apróként',
'tooltip-save'                    => 'A változtatásaid mentése',
'tooltip-preview'                 => 'Mielőtt elmentenéd a lapot, ellenőrizd, biztosan úgy néz-e ki, ahogy szeretnéd!',
'tooltip-diff'                    => 'Nézd meg, milyen változtatásokat végeztél eddig a szövegen',
'tooltip-compareselectedversions' => 'A két kiválasztott változat közötti eltérések megjelenítése',
'tooltip-watch'                   => 'A lap hozzáadása a figyelőlistádhoz',
'tooltip-recreate'                => 'A lap újra létrehozása a törlés ellenére',
'tooltip-upload'                  => 'Feltöltés indítása',

# Stylesheets
'common.css'   => '/* Közös CSS az összes felszínnek */',
'monobook.css' => '/* Az ide elhelyezett CSS hatással lesz a Monobook felület használóira */',

# Scripts
'common.js' => '/* Az ide elhelyezett JavaScript kód minden felhasználó számára lefut az oldalak betöltésekor. */',

# Metadata
'nodublincore'      => 'Ezen a kiszolgálón a Dublin Core RDF metaadatok használata letiltott.',
'nocreativecommons' => 'Ezen a kiszolgálón a Creative Commons RDF metaadatok használata letiltott.',
'notacceptable'     => 'A wiki kiszolgálója nem tudja olyan formátumban biztosítani az adatokat, amit a kliens olvasni tud.',

# Attribution
'anonymous'        => 'Névtelen {{SITENAME}}-felhasználó(k)',
'siteuser'         => '$1 wiki felhasználó',
'lastmodifiedatby' => 'Ezt a lapot utoljára $3 módosította $2, $1 időpontban.', # $1 date, $2 time, $3 user
'and'              => 'és',
'othercontribs'    => '$1 munkája alapján.',
'others'           => 'mások',
'siteusers'        => '$1 wiki felhasználó(k)',
'creditspage'      => 'A lap közreműködői',
'nocredits'        => 'Ennek a lapnak nincs közreműködői információja.',

# Spam protection
'spamprotectiontitle'    => 'Spamszűrő',
'spamprotectiontext'     => 'Az általad elmenteni kívánt lap fennakadt a spamszűrőn. Ezt valószínűleg egy külső weblapra történő hivatkozás okozta. Ha úgy érzed, tévedés történt, kérd a lap spamszűrőből való kivételét [[{{ns:project}}:Adminisztrátorok üzenőfala|az adminisztrátorok üzenőfalán]].',
'spamprotectionmatch'    => 'A spamszűrőn az alábbi szöveg fennakadt: $1',
'subcategorycount'       => 'Ebben a kategóriában $1 alkategória található.',
'categoryarticlecount'   => 'A kategória lenti listájában $1 szócikk található.',
'category-media-count'   => '{{PLURAL:$1|Egy fájl|$1 darab fájl}} található ebben a kategóriában.',
'listingcontinuesabbrev' => 'folyt.',
'spambot_username'       => 'MediaWiki spam kitakarítása',
'spam_reverting'         => 'Visszatérés a $1 lapra mutató hivatkozásokat nem tartalmazó utolsó változathoz',
'spam_blanking'          => 'Az összes változat tartalmazott a $1 lapra mutató hivatkozásokat, kiürítés',

# Info page
'infosubtitle'   => 'Információk a lapról',
'numedits'       => 'Szerkesztések száma (szócikk): $1',
'numtalkedits'   => 'Szerkesztések száma (vitalap): $1',
'numwatchers'    => 'Figyelők száma: $1',
'numauthors'     => 'Önálló szerzők száma (szócikk): $1',
'numtalkauthors' => 'Önálló szerzők száma (vitalap): $1',

# Math options
'mw_math_png'    => 'Mindig készítsen PNG-t',
'mw_math_simple' => 'HTML, ha nagyon egyszerű, egyébként PNG',
'mw_math_html'   => 'HTML, ha lehetséges, egyébként PNG',
'mw_math_source' => 'Hagyja TeX formában (szöveges böngészőknek)',
'mw_math_modern' => 'Modern böngészőknek ajánlott beállítás',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Ellenőrzöttnek jelölöd',
'markaspatrolledtext'                 => 'Ezt a cikket ellenőrzöttnek jelölöd',
'markedaspatrolled'                   => 'Ellenőrzöttnek jelölve',
'markedaspatrolledtext'               => 'A kiválasztott változatot ellenőrzöttnek jelölted.',
'rcpatroldisabled'                    => 'A Friss Változtatások Ellenőrzése kikapcsolva',
'rcpatroldisabledtext'                => 'A Friss Változtatások Ellenőrzése jelenleg nincs engedélyezve.',
'markedaspatrollederror'              => 'Nem lehet ellenőrzöttnek jelölni',
'markedaspatrollederrortext'          => 'Meg kell adnod egy ellenőrzöttként megjelölt változatot.',
'markedaspatrollederror-noautopatrol' => 'A saját változtatásaid megjelölése ellenőrzöttként nem engedélyezett.',

# Patrol log
'patrol-log-page' => 'Ellenőrzési napló',
'patrol-log-line' => 'megjelölve $1 / $2 ellenőrizve $3',
'patrol-log-auto' => '(automatikus)',

# Image deletion
'deletedrevision'                 => 'A régi $1 változat törlése kész.',
'filedeleteerror-short'           => 'Hiba a fájl törlésekor: $1',
'filedeleteerror-long'            => 'Hibák merültek föl a következő fájl törlésekor:

$1',
'filedelete-missing'              => 'A(z) "$1" fájl nem törölhető, mert nem létezik.',
'filedelete-old-unregistered'     => 'A megadott "$1" fájlváltzozat nincs az adatbázisban.',
'filedelete-current-unregistered' => 'A megadott "$1" fájl nincs az adatbázisban.',
'filedelete-archive-read-only'    => 'A(z) "$1" archív könyvtár a webkiszolgáló által nem írható.',

# Browsing diffs
'previousdiff' => '‹ Előző változtatások',
'nextdiff'     => 'Következő változtatások ›',

# Media information
'mediawarning'         => "'''Figyelmeztetés''': Ez a fájl kártékony kódot tartalmazhat, lefuttatása veszélyeztetheti a rendszeredet.<hr />",
'thumbsize'            => 'Bélyegkép mérete:',
'widthheightpage'      => '$1×$2, $3 oldal',
'file-info'            => '(fájlméret: $1, MIME típus: $2)',
'file-info-size'       => '($1 × $2 képpont, fájlméret: $3, MIME típus: $4)',
'file-nohires'         => '<small>Nagyobb felbontás nem létezik.</small>',
'svg-long-desc'        => '(SVG fájl, névlegesen $1 × $2 képpont, fájlméret: $3)',
'show-big-image'       => 'Teljes felbontás',
'show-big-image-thumb' => '<small>Jelen előnézet mérete: $1 × $2 képpont</small>',

# Special:Newimages
'newimages'    => 'Új képek galériája',
'showhidebots' => '($1 robot)',
'noimages'     => 'Nem tekinthető meg semmi.',

# Bad image list
'bad_image_list' => 'A formátum a következő:

Csak a listatételek (csillaggal * kezdődő tételek) vannak figyelembe véve. Egy sor első hivatkozásának egy rossz képre mutató hivatkozásnak kell lennie.
Ugyanazon sor további hivatkozásai kivételnek tekintettek, pl. a szócikkek, ahol a kép bennük fordulhat elő.',

# Metadata
'metadata'          => 'Metaadatok',
'metadata-help'     => 'Ez a kép járulékos adatokat tartalmaz, amelyek feltehetően a kép létrehozásához használt digitális fényképezőgép vagy lapolvasó beállításairól adnak tájékoztatást.  Ha a képet az eredetihez képest módosították, ezen adatok eltérhetnek a kép tényleges jellemzőitől.',
'metadata-expand'   => 'További képadatok',
'metadata-collapse' => 'További képadatok elrejtése',
'metadata-fields'   => 'Az ebben az üznetben kilistázott EXIF metaadat mezőket 
a képlap megjelenítés a metaadat táblázat összecsukásakor 
tartalmazni fogja. A többi alapértelmezésként rejtett marad.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Szélesség',
'exif-imagelength'                 => 'Magasság',
'exif-bitspersample'               => 'Bitek összetevőnként',
'exif-compression'                 => 'Tömörítési séma',
'exif-photometricinterpretation'   => 'Színösszetevők',
'exif-orientation'                 => 'Tájolás',
'exif-samplesperpixel'             => 'Színösszetevők száma',
'exif-planarconfiguration'         => 'Adatok csoportosítása',
'exif-ycbcrsubsampling'            => 'Y to C almintavételezésének aránya',
'exif-ycbcrpositioning'            => 'Y és C pozicionálása',
'exif-xresolution'                 => 'Vízszintes felbontás',
'exif-yresolution'                 => 'Függőleges felbontás',
'exif-resolutionunit'              => 'Az X és Y felbontás mértékegysége',
'exif-stripoffsets'                => 'Képadatok elhelyezése',
'exif-rowsperstrip'                => 'Egy csíkban levő sorok száma',
'exif-stripbytecounts'             => 'Bájt/csík',
'exif-jpeginterchangeformat'       => 'Eltolás JPEG SOI-be',
'exif-jpeginterchangeformatlength' => 'JPEG adatok bájtjai',
'exif-transferfunction'            => 'Átviteli funkció',
'exif-whitepoint'                  => 'Fehér pont színérték',
'exif-referenceblackwhite'         => 'Fekete-fehér referenciaértékek párja',
'exif-datetime'                    => 'Utolsó változtatás ideje',
'exif-imagedescription'            => 'Kép címe',
'exif-make'                        => 'Fényképezőgép gyártója',
'exif-model'                       => 'Fényképezőgép típusa',
'exif-software'                    => 'Használt szoftver',
'exif-artist'                      => 'Szerző',
'exif-copyright'                   => 'Szerzői jog tulajdonosa',
'exif-exifversion'                 => 'EXIF verzió',
'exif-flashpixversion'             => 'Támogatott Flashpix verzió',
'exif-componentsconfiguration'     => 'Az egyes összetevők jelentése',
'exif-compressedbitsperpixel'      => 'Képtömörítési mód',
'exif-pixelydimension'             => 'Érvényes képszélesség',
'exif-pixelxdimension'             => 'Érvényes képmagasság',
'exif-makernote'                   => 'Gyártó jegyzetei',
'exif-usercomment'                 => 'Felhasználók megjegyzései',
'exif-relatedsoundfile'            => 'Kapcsolódó hangfájl',
'exif-datetimeoriginal'            => 'EXIF információ létrehozásának dátuma',
'exif-datetimedigitized'           => 'Digitalizálás dátuma és időpontja',
'exif-exposuretime'                => 'Expozíciós idő',
'exif-exposuretime-format'         => '$1 mp. ($2)',
'exif-fnumber'                     => 'F szám',
'exif-exposureprogram'             => 'Expozíciós program',
'exif-spectralsensitivity'         => 'Színkép érzékenysége',
'exif-isospeedratings'             => 'ISO érzékenység minősítése',
'exif-oecf'                        => 'Optoelectronikai konverziós tényező',
'exif-shutterspeedvalue'           => 'Zársebesség',
'exif-aperturevalue'               => 'Lencsenyílás',
'exif-brightnessvalue'             => 'Fényerő',
'exif-maxaperturevalue'            => 'Legnagyobb földi lencsenyílás',
'exif-subjectdistance'             => 'Tárgy távolsága',
'exif-meteringmode'                => 'Fénymérési mód',
'exif-lightsource'                 => 'Fényforrás',
'exif-flash'                       => 'Vaku',
'exif-focallength'                 => 'Fókusztávolság',
'exif-subjectarea'                 => 'Tárgy területe',
'exif-flashenergy'                 => 'Vaku ereje',
'exif-subjectlocation'             => 'Tárgy helye',
'exif-filesource'                  => 'Fájl forrása',
'exif-scenetype'                   => 'Színhely típusa',
'exif-cfapattern'                  => 'CFA minta',
'exif-customrendered'              => 'Egyéni képfeldolgozás',
'exif-whitebalance'                => 'Fehéregyensúly',
'exif-digitalzoomratio'            => 'Digitális zoom aránya',
'exif-focallengthin35mmfilm'       => 'Fókusztávolság 35 mm-es filmen',
'exif-scenecapturetype'            => 'Színhely rögzítési típusa',
'exif-gaincontrol'                 => 'Színhely kezelése',
'exif-contrast'                    => 'Kontraszt',
'exif-saturation'                  => 'Telítettség',
'exif-sharpness'                   => 'Élesség',
'exif-devicesettingdescription'    => 'Eszközbeállítások leírása',
'exif-subjectdistancerange'        => 'Tárgy távolsági tartománya',
'exif-imageuniqueid'               => 'Egyedi képazonosító',
'exif-gpsversionid'                => 'GPS kód verziója',
'exif-gpslatituderef'              => 'Északi vagy déli szélességi fok',
'exif-gpslatitude'                 => 'Szélességi fok',
'exif-gpslongituderef'             => 'Keleti vagy nyugati hosszúsági fok',
'exif-gpslongitude'                => 'Hosszúsági fok',
'exif-gpsaltituderef'              => 'Tengerszint feletti magasság hivatkozás',
'exif-gpsaltitude'                 => 'Tengerszint feletti magasság',
'exif-gpstimestamp'                => 'GPS idő (atomóra)',
'exif-gpssatellites'               => 'Méréshez felhasznált műholdak',
'exif-gpsstatus'                   => 'Vevő állapota',
'exif-gpsmeasuremode'              => 'Mérési mód',
'exif-gpsdop'                      => 'Mérés pontossága',
'exif-gpsspeedref'                 => 'Sebesség mértékegysége',
'exif-gpsspeed'                    => 'GPS vevő sebessége',
'exif-gpstrackref'                 => 'Hivatkozás a mozgásirányra',
'exif-gpstrack'                    => 'Mozgásirány',
'exif-gpsimgdirectionref'          => 'Hivatkozás a kép irányára',
'exif-gpsimgdirection'             => 'Kép iránya',
'exif-gpsmapdatum'                 => 'Felhasznált geodéziai kérdőív adatai',
'exif-gpsdestlatituderef'          => 'Hivatkozás a cél szélességi fokára',
'exif-gpsdestlatitude'             => 'Szélességi fok célja',
'exif-gpsdestlongituderef'         => 'Hivatkozás a cél hosszúsági fokára',
'exif-gpsdestlongitude'            => 'Cél hosszúsági foka',
'exif-gpsdestbearingref'           => 'Hivatkozás a cél hordozójára',
'exif-gpsdestbearing'              => 'Cél hordozója',
'exif-gpsdestdistanceref'          => 'Hivatkozás a cél távolságára',
'exif-gpsdestdistance'             => 'Cél távolsága',
'exif-gpsprocessingmethod'         => 'GPS feldolgozási mód neve',
'exif-gpsareainformation'          => 'GPS terület neve',
'exif-gpsdatestamp'                => 'GPS dátum',
'exif-gpsdifferential'             => 'GPS különbözeti korrekció',

# EXIF attributes
'exif-compression-1' => 'Nem tömörített',

'exif-unknowndate' => 'Ismeretlen dátum',

'exif-orientation-1' => 'Normál', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Vízszintesen tükrözött', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Elforgatott 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Függőlegesen tükrözött', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Elforgatott 90° ÓE és függőlegesen tükrözött', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Elforgatott 90° ÓSZ', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Elforgatott 90° ÓSZ és függőlegesen tükrözött', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Elforgatott 90° ÓE', # 0th row: left; 0th column: bottom

'exif-exposureprogram-0' => 'Nem meghatározott',
'exif-exposureprogram-1' => 'Kézi',
'exif-exposureprogram-2' => 'Normál program',
'exif-exposureprogram-3' => 'Lencsenyílás elsőbbsége',
'exif-exposureprogram-4' => 'Zár elsőbbsége',
'exif-exposureprogram-5' => 'Létrehozó program (a mezőmélység felé eltolva)',
'exif-exposureprogram-6' => 'Működtető program (a gyors zársebesség felé eltolva)',
'exif-exposureprogram-7' => 'Arckép mód (a fókuszon kívüli hátterű közeli fényképekhez)',
'exif-exposureprogram-8' => 'Tájkép mód (a fókuszban lévő hátterű tájkép fotókhoz)',

'exif-subjectdistance-value' => '$1 méter',

'exif-meteringmode-0'   => 'Ismeretlen',
'exif-meteringmode-1'   => 'Átlagos',
'exif-meteringmode-3'   => 'Megvilágítás',
'exif-meteringmode-4'   => 'Többszörös megvilágítás',
'exif-meteringmode-5'   => 'Minta',
'exif-meteringmode-6'   => 'Részleges',
'exif-meteringmode-255' => 'Egyéb',

'exif-lightsource-0'   => 'Ismeretlen',
'exif-lightsource-1'   => 'Természetes fény',
'exif-lightsource-2'   => 'Fénycső',
'exif-lightsource-3'   => 'Tungsten (izzófény)',
'exif-lightsource-4'   => 'Vaku',
'exif-lightsource-9'   => 'Derült idő',
'exif-lightsource-10'  => 'Felhős idő',
'exif-lightsource-11'  => 'Árnyék',
'exif-lightsource-12'  => 'Természetes fény fénycső (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Napfehér fénycső (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Hideg fehér fénycső (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fehér fénycső (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Hagyományos izzó A',
'exif-lightsource-18'  => 'Hagyományos izzó B',
'exif-lightsource-19'  => 'Hagyományos izzó C',
'exif-lightsource-255' => 'Egyéb fényforrás',

'exif-focalplaneresolutionunit-2' => 'hüvelyk',

'exif-sensingmethod-1' => 'Nem meghatározott',
'exif-sensingmethod-2' => 'Egylapkás színterület-érzékelő',
'exif-sensingmethod-3' => 'Kétlapkás színterület-érzékelő',
'exif-sensingmethod-4' => 'Háromlapkás színterület-érzékelő',
'exif-sensingmethod-5' => 'Színsorrendi területérzékelő',
'exif-sensingmethod-7' => 'Háromvonalas érzékelő',
'exif-sensingmethod-8' => 'Színsorrendi vonalas érzékelő',

'exif-scenetype-1' => 'Egy közvetlenül lefotózott kép',

'exif-customrendered-0' => 'Normál feldolgozás',
'exif-customrendered-1' => 'Egyéni feldolgozás',

'exif-exposuremode-0' => 'Automatikus felvétel',
'exif-exposuremode-1' => 'Kézi felvétel',

'exif-whitebalance-0' => 'Automatikus fehéregyensúly',
'exif-whitebalance-1' => 'Kézi fehéregyensúly',

'exif-scenecapturetype-0' => 'Hagyományos',
'exif-scenecapturetype-1' => 'Tájkép',
'exif-scenecapturetype-2' => 'Arckép',
'exif-scenecapturetype-3' => 'Éjszakai színhely',

'exif-gaincontrol-0' => 'Nincs',

'exif-contrast-0' => 'Normál',
'exif-contrast-1' => 'Lágy',
'exif-contrast-2' => 'Kemény',

'exif-saturation-0' => 'Normál',
'exif-saturation-1' => 'Alacsony telítettség',
'exif-saturation-2' => 'Magas telítettség',

'exif-sharpness-0' => 'Normál',
'exif-sharpness-1' => 'Lágy',
'exif-sharpness-2' => 'Kemény',

'exif-subjectdistancerange-0' => 'Ismeretlen',
'exif-subjectdistancerange-1' => 'Makró',
'exif-subjectdistancerange-2' => 'Közeli nézet',
'exif-subjectdistancerange-3' => 'Távoli nézet',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Északi szélességi fok',
'exif-gpslatitude-s' => 'Déli szélességi fok',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Keleti hosszúsági fok',
'exif-gpslongitude-w' => 'Nyugati hosszúsági fok',

'exif-gpsmeasuremode-2' => '2-dimenziós méret',
'exif-gpsmeasuremode-3' => '3-dimenziós méret',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilométer óránként',
'exif-gpsspeed-m' => 'Márföld óránként',
'exif-gpsspeed-n' => 'Csomó',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Igazi irány',
'exif-gpsdirection-m' => 'Mágneses irány',

# External editor support
'edit-externally'      => 'A fájl szerkesztése külső alkalmazással',
'edit-externally-help' => 'Lásd a [http://meta.wikimedia.org/wiki/Help:External_editors „setup instructions”] leírást (angolul) ennek használatához.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'összes',
'imagelistall'     => 'összes',
'watchlistall2'    => 'bármikor',
'namespacesall'    => 'Mind',
'monthsall'        => 'mind',

# E-mail address confirmation
'confirmemail'            => 'E-mail cím megerősítése',
'confirmemail_noemail'    => 'Nincs érvényes e-mail cím megadva a [[Special:Preferences|beállításaidnál]].',
'confirmemail_text'       => 'Ennek a wikinek a használatához meg kell erősítened az e-mail címed, 
mielőtt használni kezded a levelezési rendszerét. Nyomd meg az alsó gombot, hogy kaphass egy e-mailt, 
melyben megtalálod a megerősítéshez szükséges kódot. Töltsd be a kódot a böngésződbe, hogy aktiválhasd az e-mail címedet. Köszönjük!',
'confirmemail_pending'    => '<div class="error">
A megerősítő kódot már megküldtük neked e-mailben; ha nemrég hoztad 
létre a fiókodat, akkor várhatnál néhány percet, amíg megérkezik,
mielőtt új kódot kérnél.
</div>',
'confirmemail_send'       => 'Megerősítő kód postázása',
'confirmemail_sent'       => 'Kaptál egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot.',
'confirmemail_oncreate'   => 'A megerősítő kódot elküldtük az e-mail címedre.
Ez a kód nem szükséges a belépéshez, de meg kell adnod, 
mielőtt a wiki e-mail alapú szolgáltatásait igénybe veheted.',
'confirmemail_sendfailed' => 'Nem tudjuk elküldeni a megerősítéshez szükséges e-mailt. Kérjük, ellenőrizd a címet. $1',
'confirmemail_invalid'    => 'Nem megfelelő kód. A kódnak lehet, hogy lejárt a felhasználhatósági ideje.',
'confirmemail_needlogin'  => 'Meg kell $1 erősíteni az e-mail címedet.',
'confirmemail_success'    => 'Az e-mail címed megerősítve. Most már beléphetsz a wikibe.',
'confirmemail_loggedin'   => 'E-mail címed megerősítve.',
'confirmemail_error'      => 'Hiba az e-mail címed megerősítése során.',
'confirmemail_subject'    => '{{SITENAME}} e-mail cím megerősítés',
'confirmemail_body'       => 'Valaki, valószínűleg te, a $1 IP címről regisztrált a "$2" azonosítóval, 
ezzel az e-maillel. 

Annak érdekében, hogy megerősítsd, ez az azonosító valóban hozzád tartozik, és aktiválni szeretnéd az 
e-mail címedet, nyisd meg az alábbi hivatkozást a böngésződben:

$3

Ha ez *nem* te vagy, ne kattints a hivatkozásra. Ennek a megerősítésre szánt kódnak 
a felhasználhatósági ideje lejár: $4.',

# Scary transclusion
'scarytranscludefailed'  => '[$1 sablonjának visszakeresése nem sikerült; sajnáljuk]',
'scarytranscludetoolong' => '[Túl hosszú az URL; sajnáljuk]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Visszakövetések ehhez a szócikkhez:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 törlése])',
'trackbacklink'     => 'Visszakövetés',
'trackbackdeleteok' => 'A visszakövetés törlése sikerült.',

# Delete conflict
'deletedwhileediting' => 'Figyelmeztetés: Ezt a lapot a szerkesztés megkezdése után törölték!',
'confirmrecreate'     => "[[User:$1|$1]] ([[User talk:$1|talk]]) törölte ezt a lapot, miután a következő indokkal elkezdted szerkeszteni:
: ''$2''
Kérjük, erősítsd meg, hogy valóban újból létre akarod-e hozni ezt a lapot.",
'recreate'            => 'Újra létrehozás',

# HTML dump
'redirectingto' => 'Átirányítás a következőre: [[:$1|$1]]...',

# action=purge
'confirm_purge' => 'Kiüríted ennek a lapnak gyorsítótárát?

$1',

# AJAX search
'searchcontaining' => "''$1''-t tartalmazó lapokra keresés.",
'searchnamed'      => "''$1'' című lapok keresése.",
'articletitles'    => "''$1'' kezdetű szócikkek",
'hideresults'      => 'Eredmények elrejtése',

# Multipage image navigation
'imgmultipageprev'   => '← előző lap',
'imgmultipagenext'   => 'következő lap →',
'imgmultigo'         => 'Ugrás!',
'imgmultigotopre'    => 'Ugrás laphoz',
'imgmultiparseerror' => 'A képfájl sérültnek vagy hibásnak tűnik, ezért a {{SITENAME}} nem tudja visszakeresni a lapok listáját.',

# Table pager
'ascending_abbrev'         => 'növ',
'descending_abbrev'        => 'csökk',
'table_pager_next'         => 'Következő lap',
'table_pager_prev'         => 'Előző lap',
'table_pager_first'        => 'Első lap',
'table_pager_last'         => 'Utolsó lap',
'table_pager_limit'        => 'Laponként $1 tétel megjelenítése',
'table_pager_limit_submit' => 'Ugrás',
'table_pager_empty'        => 'Nincs találat',

# Auto-summaries
'autosumm-blank'   => 'A lap teljes tartalmának eltávolítása',
'autosumm-replace' => 'A lap tartalmának cseréje erre: $1',
'autoredircomment' => 'Átirányítás ide:[[$1]]',
'autosumm-new'     => 'Új oldal, tartalma: „$1”',

# Live preview
'livepreview-loading' => 'Loading…',
'livepreview-ready'   => 'Betöltés… Kész!',
'livepreview-failed'  => 'Az élő előnézet nem sikerült! Próbálkozz a normál előnézettel.',
'livepreview-error'   => 'A csatlakozás nem sikerült: $1 "$2". Próbálkozz a normál előnézettel.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Lehet, hogy a(z) $1 másodpercnél újabb változtatások nem láthatók ebben a listában.',
'lag-warn-high'   => 'Az adatbázis-kiszolgáló nagy késése miatt lehet, hogy a(z) $1 másodpercnél újabb változtatások nem láthatók ebben a listában.',

# Watchlist editor
'watchlistedit-numitems'       => 'A vitalapok kivételével {{PLURAL:$1|1 cím|$1 cím}} van a figyelőlistádon.',
'watchlistedit-noitems'        => 'Nincs egy cím sem a figyelőlistádon.',
'watchlistedit-normal-title'   => 'Figyelőlista szerkesztése',
'watchlistedit-normal-legend'  => 'Címek eltávolítása a figyelőlistáról',
'watchlistedit-normal-explain' => 'Alább láthatók a figyelőlistádon szereplő címek. Ha el akarsz távolítani egy 
	címet, akkor jelöld be a jelölőnégyzetét, és kattints a Címek eltávolítása gombra. A nyers listát [[Special:Watchlist/raw|szerkesztheted]],
	vagy [[Special:Watchlist/clear|az összes címet eltávolíthatod]].',
'watchlistedit-normal-submit'  => 'Címek eltávolítása',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 cím|$1 cím}} került eltávolításra a figyelőlistádról:',
'watchlistedit-raw-title'      => 'Nyers figyelőlista szerkesztése',
'watchlistedit-raw-legend'     => 'Nyers figyelőlista szerkesztése',
'watchlistedit-raw-explain'    => 'Alább láthatók a figyelőlistádon szereplő címek, amiket a listára vétellel vagy 
 eltávolítással szerkeszthetsz; soronként egy cím. Ha befejezted, kattints a Figyelőlista frissítése 
 gombra. A [[Special:Watchlist/edit|hagyományos szerkesztőt]] is használhatod.',
'watchlistedit-raw-titles'     => 'Címek:',
'watchlistedit-raw-submit'     => 'Figyelőlista frissítése',
'watchlistedit-raw-done'       => 'A figyelőlistád frissítése kész.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 cím|$1 cím}} került hozzáadásra:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 cím|$1 cím}} került eltávolításra:',

# Watchlist editing tools
'watchlisttools-view' => 'A témával kapcsolatos változtatások megtekintése',
'watchlisttools-edit' => 'Figyelőlista megtekintése és szerkesztése',
'watchlisttools-raw'  => 'Nyers figyelőlista szerkesztése',

);
