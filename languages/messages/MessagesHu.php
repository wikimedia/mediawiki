<?php
/** Hungarian (Magyar)
 *
 * @addtogroup Language
 *
 * @author Dani
 * @author Bdamokos
 * @author Tgr
 * @author Siebrand
 * @author SPQRobin
 * @author Dorgan
 * @author Bennó
 */

$namespaceNames = array(
	NS_MEDIA          => 'Média',
	NS_SPECIAL        => 'Speciális',
	NS_MAIN           => '',
	NS_TALK           => 'Vita',
	NS_USER           => 'User',
	NS_USER_TALK      => 'User_vita',
	# NS_PROJECT set by \$wgMetaNamespace
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
	NS_CATEGORY_TALK  => 'Kategória_vita',
);

$skinNames = array(
	'standard'    => 'Klasszikus',
	'nostalgia'   => 'Nosztalgia',
	'cologneblue' => 'Kölni kék',
	'monobook'    => 'MonoBook',
	'myskin'      => 'MySkin',
	'chick'       => 'Chick',
	'simple'      => 'Egyszerű',
);

$fallback8bitEncoding = "iso8859-2";
$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dupla_átirányítások' ),
	'BrokenRedirects'           => array( 'Nem_létező_lapra_mutató_átirányítások' ),
	'Disambiguations'           => array( 'Egyértelműsítő_lapok' ),
	'Userlogin'                 => array( 'Belépés' ),
	'Userlogout'                => array( 'Kilépés' ),
	'CreateAccount'             => array( 'Felhasználói_fiók_létrehozása' ),
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
	'Uncategorizedcategories'   => array( 'Kategorizálatlan_kategóriák' ),
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
	'Fewestrevisions'           => array( 'Legtöbbet_szerkesztett_javítások' ),
	'Shortpages'                => array( 'Rövid_lapok' ),
	'Longpages'                 => array( 'Hosszú_lapok' ),
	'Newpages'                  => array( 'Új_lapok' ),
	'Ancientpages'              => array( 'Régóta_nem_változott_szócikkek' ),
	'Deadendpages'              => array( 'Zsákutcalapok' ),
	'Protectedpages'            => array( 'Védett_lapok' ),
	'Protectedtitles'           => array( 'Védett_címek' ),
	'Allpages'                  => array( 'Az_összes_lap_listája' ),
	'Prefixindex'               => array( 'Egy_névtérbe_tartozó_lapok_listája' ),
	'Ipblocklist'               => array( 'Blokkolt_IP-címek_listája' ),
	'Specialpages'              => array( 'Speciális_lapok' ),
	'Contributions'             => array( 'Szerkesztő_közreműködései' ),
	'Emailuser'                 => array( 'E-mail_küldése', 'E-mail_küldése_ezen_szerkesztőnek' ),
	'Confirmemail'              => array( 'Emailcím_megerősítése' ),
	'Whatlinkshere'             => array( 'Mi_hivatkozik_erre' ),
	'Recentchangeslinked'       => array( 'Kapcsolódó_változtatások' ),
	'Movepage'                  => array( 'Lap_átnevezése' ),
	'Blockme'                   => array( 'Blokkolj' ),
	'Booksources'               => array( 'Könyvforrások' ),
	'Categories'                => array( 'Kategóriák' ),
	'Export'                    => array( 'Lapok_exportálása' ),
	'Version'                   => array( 'Névjegy', 'Verziószám', 'Verzió' ),
	'Allmessages'               => array( 'Rendszerüzenetek' ),
	'Log'                       => array( 'Rendszernaplók', 'Naplók', 'Napló' ),
	'Blockip'                   => array( 'Blokkolás' ),
	'Undelete'                  => array( 'Törölt_lapváltozatok_visszaállítása' ),
	'Import'                    => array( 'Lapok_importálása' ),
	'Lockdb'                    => array( 'Adatbázis_lezárása' ),
	'Unlockdb'                  => array( 'Adatbázis_lezárás_feloldása' ),
	'Userrights'                => array( 'Szerkesztői_jogok' ),
	'MIMEsearch'                => array( 'Keresés_MIME-típus_alapján' ),
	'FileDuplicateSearch'       => array( 'Duplikátumok_keresése' ),
	'Unwatchedpages'            => array( 'Nem_figyelt_lapok' ),
	'Listredirects'             => array( 'Átirányítások_listája' ),
	'Revisiondelete'            => array( 'Változat_törlése' ),
	'Unusedtemplates'           => array( 'Nem_használt_sablonok' ),
	'Randomredirect'            => array( 'Átirányítás_találomra' ),
	'Mypage'                    => array( 'Lapom', 'Userlapom' ),
	'Mytalk'                    => array( 'Vitám', 'Vitalapom', 'Uservitalapom' ),
	'Mycontributions'           => array( 'Közreműködéseim' ),
	'Listadmins'                => array( 'Adminisztrátorok', 'Adminisztrátorok_listája', 'Sysopok' ),
	'Listbots'                  => array( 'Botok', 'Botok_listája' ),
	'Popularpages'              => array( 'Népszerű_oldalak' ),
	'Search'                    => array( 'Keresés' ),
	'Resetpass'                 => array( 'Jelszócsere' ),
	'Withoutinterwiki'          => array( 'Interwikilinkek_nélküli_lapok' ),
	'MergeHistory'              => array( 'Laptörténetek_egyesítése' ),
	'Filepath'                  => array( 'Fájl_elérési_útja' ),
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

$magicWords = array(
	'redirect'            => array( '0', '#REDIRECT', '#ÁTIRÁNYÍTÁS' ),
	'notoc'               => array( '0', '__NOTOC__', '__NINCSTARTALOMJEGYZÉK__', '__NINCSTJ__' ),
	'nogallery'           => array( '0', '__NOGALLERY__', '__NINCSGALÉRIA__' ),
	'forcetoc'            => array( '0', '__FORCETOC__', '__LEGYENTARTALOMJEGYZÉK__' ),
	'toc'                 => array( '0', '__TOC__', '__TARTALOMJEGYZÉK__', '__TJ__' ),
	'noeditsection'       => array( '0', '__NOEDITSECTION__', '__NINCSSZERKESZTÉS__' ),
	'currentmonth'        => array( '1', 'CURRENTMONTH', 'JELENLEGIHÓNAP' ),
	'currentmonthname'    => array( '1', 'CURRENTMONTHNAME', 'JELENLEGIHÓNAPNEVE' ),
	'currentmonthabbrev'  => array( '1', 'CURRENTMONTHABBREV', 'JELENLEGIHÓNAPRÖVID' ),
	'currentday'          => array( '1', 'CURRENTDAY', 'MAINAP' ),
	'currentday2'         => array( '1', 'CURRENTDAY2', 'MAINAP2' ),
	'currentdayname'      => array( '1', 'CURRENTDAYNAME', 'MAINAPNEVE' ),
	'currentyear'         => array( '1', 'CURRENTYEAR', 'JELENLEGIÉV' ),
	'currenttime'         => array( '1', 'CURRENTTIME', 'JELENLEGIIDŐ' ),
	'currenthour'         => array( '1', 'CURRENTHOUR', 'JELENLEGIÓRA' ),
	'localmonth'          => array( '1', 'LOCALMONTH', 'HELYIHÓNAP' ),
	'localmonthname'      => array( '1', 'LOCALMONTHNAME', 'HELYIHÓNAPNÉV' ),
	'localmonthabbrev'    => array( '1', 'LOCALMONTHABBREV', 'HELYIHÓNAPRÖVIDÍTÉS' ),
	'localday'            => array( '1', 'LOCALDAY', 'HELYINAP' ),
	'localday2'           => array( '1', 'LOCALDAY2', 'HELYINAP2' ),
	'localdayname'        => array( '1', 'LOCALDAYNAME', 'HELYINAPNEVE' ),
	'localyear'           => array( '1', 'LOCALYEAR', 'HELYIÉV' ),
	'localtime'           => array( '1', 'LOCALTIME', 'HELYIIDŐ' ),
	'localhour'           => array( '1', 'LOCALHOUR', 'HELYIÓRA' ),
	'numberofpages'       => array( '1', 'NUMBEROFPAGES', 'OLDALAKSZÁMA', 'LAPOKSZÁMA' ),
	'numberofarticles'    => array( '1', 'NUMBEROFARTICLES', 'SZÓCIKKEKSZÁMA' ),
	'numberoffiles'       => array( '1', 'NUMBEROFFILES', 'FÁJLOKSZÁMA' ),
	'numberofusers'       => array( '1', 'NUMBEROFUSERS', 'SZERKESZTŐKSZÁMA' ),
	'numberofedits'       => array( '1', 'NUMBEROFEDITS', 'SZERKESZTÉSEKSZÁMA' ),
	'pagename'            => array( '1', 'PAGENAME', 'OLDALNEVE' ),
	'pagenamee'           => array( '1', 'PAGENAMEE', 'OLDALNEVEE' ),
	'namespace'           => array( '1', 'NAMESPACE', 'NÉVTERE' ),
	'namespacee'          => array( '1', 'NAMESPACEE', 'NÉVTEREE' ),
	'talkspace'           => array( '1', 'TALKSPACE', 'VITATERE' ),
	'talkspacee'          => array( '1', 'TALKSPACEE', 'VITATEREE' ),
	'subjectspace'        => array( '1', 'SUBJECTSPACE', 'ARTICLESPACE', 'SZÓCIKKNÉVTERE' ),
	'subjectspacee'       => array( '1', 'SUBJECTSPACEE', 'ARTICLESPACEE', 'SZÓCIKKNÉVTEREE' ),
	'fullpagename'        => array( '1', 'FULLPAGENAME', 'LAPTELJESNEVE' ),
	'fullpagenamee'       => array( '1', 'FULLPAGENAMEE', 'LAPTELJESNEVEE' ),
	'subpagename'         => array( '1', 'SUBPAGENAME', 'ALLAPNEVE' ),
	'subpagenamee'        => array( '1', 'SUBPAGENAMEE', 'ALLAPNEVEE' ),
	'basepagename'        => array( '1', 'BASEPAGENAME', 'ALAPLAPNEVE' ),
	'basepagenamee'       => array( '1', 'BASEPAGENAMEE', 'ALAPLAPNEVEE' ),
	'talkpagename'        => array( '1', 'TALKPAGENAME', 'VITALAPNEVE' ),
	'talkpagenamee'       => array( '1', 'TALKPAGENAMEE', 'VITALAPNEVEE' ),
	'subjectpagename'     => array( '1', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'SZÓCIKKNEVE' ),
	'subjectpagenamee'    => array( '1', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'SZÓCIKKNEVEE' ),
	'msg'                 => array( '0', 'MSG:', 'ÜZENET:', 'ÜZ:' ),
	'subst'               => array( '0', 'SUBST:', 'BEILLESZT:', 'BEMÁSOL:' ),
	'img_thumbnail'       => array( '1', 'thumbnail', 'thumb', 'bélyegkép', 'bélyeg' ),
	'img_manualthumb'     => array( '1', 'thumbnail=$1', 'thumb=$1', 'bélyegkép=$1', 'bélyeg=$1' ),
	'img_right'           => array( '1', 'right', 'jobb', 'jobbra' ),
	'img_left'            => array( '1', 'left', 'bal', 'balra' ),
	'img_none'            => array( '1', 'none', 'semmi' ),
	'img_width'           => array( '1', '$1px' ),
	'img_center'          => array( '1', 'center', 'centre', 'közép', 'középre' ),
	'img_framed'          => array( '1', 'framed', 'enframed', 'frame', 'keretezett' ),
	'img_frameless'       => array( '1', 'frameless', 'keretnélküli' ),
	'img_page'            => array( '1', 'page=$1', 'page $1', 'oldal=$1', 'oldal $1' ),
	'img_upright'         => array( '1', 'upright', 'upright=$1', 'upright $1', 'fennjobbra', 'fennjobbra=$1', 'fennjobbra $1' ),
	'img_border'          => array( '1', 'border', 'keret' ),
	'img_baseline'        => array( '1', 'baseline', 'alapvonal' ),
	'img_sub'             => array( '1', 'sub', 'ai', 'alsóindex' ),
	'img_super'           => array( '1', 'super', 'sup', 'fi', 'felsőindex' ),
	'img_top'             => array( '1', 'top', 'fenn', 'fent' ),
	'img_text_top'        => array( '1', 'text-top', 'szöveg-fenn', 'szöveg-fent' ),
	'img_middle'          => array( '1', 'middle', 'középen', 'középre' ),
	'img_bottom'          => array( '1', 'bottom', 'lenn', 'lent' ),
	'img_text_bottom'     => array( '1', 'text-bottom', 'szöveg-lenn', 'szöveg-lent' ),
	'sitename'            => array( '1', 'SITENAME', 'OLDALNEVE', 'WIKINEVE' ),
	'ns'                  => array( '0', 'NS:', 'NÉVTÉR:' ),
	'localurl'            => array( '0', 'LOCALURL:', 'HELYIURL:' ),
	'localurle'           => array( '0', 'LOCALURLE:', 'HELYIURLE:' ),
	'server'              => array( '0', 'SERVER', 'SZERVER', 'KISZOLGÁLÓ' ),
	'servername'          => array( '0', 'SERVERNAME', 'SZERVERNEVE', 'KISZOLGÁLÓNEVE' ),
	'grammar'             => array( '0', 'GRAMMAR:', 'NYELVTAN:' ),
	'currentweek'         => array( '1', 'CURRENTWEEK', 'JELENLEGIHÉT' ),
	'currentdow'          => array( '1', 'CURRENTDOW', 'JELENLEGIHÉTNAPJA' ),
	'localweek'           => array( '1', 'LOCALWEEK', 'HELYIHÉT' ),
	'localdow'            => array( '1', 'LOCALDOW', 'HELYIHÉTNAPJA' ),
	'revisionid'          => array( '1', 'REVISIONID', 'VÁLTOZATID' ),
	'revisionday'         => array( '1', 'REVISIONDAY', 'VÁLTOZATNAP' ),
	'revisionday2'        => array( '1', 'REVISIONDAY2', 'VÁLTOZATNAP2' ),
	'revisionmonth'       => array( '1', 'REVISIONMONTH', 'VÁLTOZATHÓNAP' ),
	'revisionyear'        => array( '1', 'REVISIONYEAR', 'VÁLTOZATÉV' ),
	'revisiontimestamp'   => array( '1', 'REVISIONTIMESTAMP', 'VÁLTOZATIDŐBÉLYEG', 'VÁLTOZATIDŐ' ),
	'plural'              => array( '0', 'PLURAL:', 'TÖBBESSZÁM:' ),
	'fullurl'             => array( '0', 'FULLURL:', 'TELJESURL:' ),
	'fullurle'            => array( '0', 'FULLURLE:', 'TELJESURLE:' ),
	'lcfirst'             => array( '0', 'LCFIRST:', 'KISKEZDŐ:', 'KISKEZDŐBETŰ:' ),
	'ucfirst'             => array( '0', 'UCFIRST:', 'NAGYKEZDŐ:', 'NAGYKEZDŐBETŰ:' ),
	'lc'                  => array( '0', 'LC:', 'KISBETŰ:', 'KISBETŰK:', 'KB:' ),
	'uc'                  => array( '0', 'UC:', 'NAGYBETŰ:', 'NAGYBETŰK', 'NB:' ),
	'displaytitle'        => array( '1', 'DISPLAYTITLE', 'MEGJELENÍTENDŐCÍM', 'CÍM' ),
	'newsectionlink'      => array( '1', '__NEWSECTIONLINK__', '__ÚJSZAKASZLINK__' ),
	'currentversion'      => array( '1', 'CURRENTVERSION', 'JELENLEGIVÁLTOZAT' ),
	'urlencode'           => array( '0', 'URLENCODE:', 'URLKÓDOLVA:' ),
	'anchorencode'        => array( '0', 'ANCHORENCODE', 'HORGONYKÓDOLVA' ),
	'currenttimestamp'    => array( '1', 'CURRENTTIMESTAMP', 'JELENLEGIIDŐBÉLYEG' ),
	'localtimestamp'      => array( '1', 'LOCALTIMESTAMP', 'HELYIIDŐBÉLYEG' ),
	'directionmark'       => array( '1', 'DIRECTIONMARK', 'DIRMARK', 'IRÁNYJELZŐ' ),
	'language'            => array( '0', '#LANGUAGE:', '#NYELV:' ),
	'contentlanguage'     => array( '1', 'CONTENTLANGUAGE', 'CONTENTLANG', 'TARTALOMNYELVE', 'TARTNYELVE' ),
	'pagesinnamespace'    => array( '1', 'PAGESINNAMESPACE:', 'PAGESINNS:', 'OLDALAKNÉVTÉRBEN:', 'OLDALAKNBEN:' ),
	'numberofadmins'      => array( '1', 'NUMBEROFADMINS', 'ADMINOKSZÁMA' ),
	'formatnum'           => array( '0', 'FORMATNUM', 'FORMÁZOTTSZÁM', 'SZÁMFORMÁZÁS', 'SZÁMFORM' ),
	'special'             => array( '0', 'special', 'speciális' ),
	'defaultsort'         => array( '1', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:', 'RENDEZÉS:', 'KULCS:' ),
	'filepath'            => array( '0', 'FILEPATH:', 'ELÉRÉSIÚT:' ),
);

$linkTrail = '/^([a-záéíóúöüőűÁÉÍÓÚÖÜŐŰ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Hivatkozások aláhúzása:',
'tog-highlightbroken'         => 'Nem létező lapok <a href="" class="new">így</a> (alternatíva: így<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Bekezdések teljes szélességű tördelése („sorkizárás”)',
'tog-hideminor'               => 'Apró változtatások elrejtése a Friss változtatások lapon',
'tog-extendwatchlist'         => 'A figyelőlista kiterjesztése minden változtatásra (ne csak az utolsót mutassa)',
'tog-usenewrc'                => 'Fejlettebb friss változások listája (JavaScript)',
'tog-numberheadings'          => 'Fejezetcímek automatikus számozása',
'tog-showtoolbar'             => 'Szerkesztőeszközsor látható (JavaScript)',
'tog-editondblclick'          => 'A lapok szerkesztése dupla kattintásra (JavaScript)',
'tog-editsection'             => '[szerkesztés] linkek az egyes szakaszok szerkesztéséhez',
'tog-editsectiononrightclick' => 'Szakaszok szerkesztése a szakaszcímre való kattintással (JavaScript)',
'tog-showtoc'                 => 'Tartalomjegyzék megjelenítése a három fejezetnél többel rendelkező cikkeknél',
'tog-rememberpassword'        => 'Emlékezzen rám ezen a számítógépen',
'tog-editwidth'               => 'Teljes szélességű szerkesztőablak',
'tog-watchcreations'          => 'Általad létrehozott lapok felvétele a figyelőlistádra',
'tog-watchdefault'            => 'Szerkesztett cikkek felvétele a figyelőlistára',
'tog-watchmoves'              => 'Átnevezett lapok felvétele a figyelőlistára',
'tog-watchdeletion'           => 'Törölt cikkek felvétele a figyelőlistára',
'tog-minordefault'            => 'Alapértelmezésben minden szerkesztésemet jelölje aprónak',
'tog-previewontop'            => 'Előnézet megjelenítése a szerkesztőablak előtt',
'tog-previewonfirst'          => 'Előnézet első szerkesztésnél',
'tog-nocache'                 => 'A lapok gyorstárazásának letiltása',
'tog-enotifwatchlistpages'    => 'Értesítés e-mailben, ha egy általam figyelt lap megváltozik',
'tog-enotifusertalkpages'     => 'Értesítés e-mailben, ha megváltozik a vitalapom',
'tog-enotifminoredits'        => 'Értesítés e-mailben a lapok apró változtatásairól',
'tog-enotifrevealaddr'        => 'Jelenítse meg az e-mail címemet a figyelmeztető e-mailekben',
'tog-shownumberswatching'     => 'Az oldalt figyelő szerkesztők számának mutatása',
'tog-fancysig'                => 'Aláírás automatikus hivatkozás nélkül',
'tog-externaleditor'          => 'Külső szerkesztőprogram használata',
'tog-externaldiff'            => 'Külső különbségképző (diff) program használata',
'tog-showjumplinks'           => 'Helyezzen el hivatkozást („Ugrás”) a beépített eszköztárra',
'tog-uselivepreview'          => 'Élő előnézet használata (JavaScript) (Teszt)',
'tog-forceeditsummary'        => 'Figyelmeztessen, ha nem adok meg szerkesztési összefoglalót',
'tog-watchlisthideown'        => 'Saját szerkesztések elrejtése',
'tog-watchlisthidebots'       => 'Robotok szerkesztéseinek elrejtése',
'tog-watchlisthideminor'      => 'Apró változtatások elrejtése',
'tog-nolangconversion'        => 'A változók átalakításának letiltása',
'tog-ccmeonemails'            => 'A másoknak küldött e-mailekről kapjak én is egy másolatot',
'tog-diffonly'                => 'Ne mutassa a lap tartalmát lapváltozatok közötti eltérések megtekintésekor',
'tog-showhiddencats'          => 'Rejtett kategóriák megjelenítése',

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
'sun'           => 'vas',
'mon'           => 'hét',
'tue'           => 'kedd',
'wed'           => 'sze',
'thu'           => 'csü',
'fri'           => 'péntek',
'sat'           => 'szo',
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
'february-gen'  => 'február',
'march-gen'     => 'március',
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
'mar'           => 'már',
'apr'           => 'ápr',
'may'           => 'máj',
'jun'           => 'jún',
'jul'           => 'júl',
'aug'           => 'aug',
'sep'           => 'szep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'categories'                     => 'Kategóriák',
'categoriespagetext'             => 'A következő kategóriák tartalmaznak lapokat vagy fájlokat.',
'special-categories-sort-count'  => 'rendezés elemszám szerint',
'special-categories-sort-abc'    => 'rendezés ABC szerint',
'pagecategories'                 => '{{PLURAL:$1|Kategória|Kategóriák}}',
'category_header'                => '„$1” kategória szócikkei',
'subcategories'                  => 'Alkategóriák',
'category-media-header'          => 'A(z) „$1” kategóriába tartozó médiafájlok',
'category-empty'                 => "''Ebben a kategóriában pillanatnyilag egyetlen lap, médiafájl vagy alkategória sem szerepel.''",
'hidden-categories'              => '{{PLURAL:$1|Rejtett kategória|Rejtett kategóriák}}',
'hidden-category-category'       => 'Rejtett kategóriák', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Csak a következő alkategória található ebben a kategóriában.|Az összesen $2 alkategóriából a következő $1 található ebben a kategóriában.}}',
'category-subcat-count-limited'  => 'Ebben a kategóriában $1 alkategória található.',
'category-article-count'         => '{{PLURAL:$2|Csak a következő lap található ebben a kategóriában.|Az összesen $2 lapból a következő $1 található ebben a kategóriában.}}',
'category-article-count-limited' => 'A kategória lenti listájában {{PLURAL:$1|egy|$1}} lap található.',
'category-file-count'            => '{{PLURAL:$2|Csak a következő fájl található ebben a kategóriában.|Az összesen $2 fájlból a következő $1 található ebben a kategóriában.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Egy|$1}} fájl található ebben a kategóriában.',
'listingcontinuesabbrev'         => 'folyt.',

'mainpagetext'      => "<big>'''A MediaWiki telepítése sikerült.'''</big>",
'mainpagedocfooter' => "Ha segítségre van szükséged a wikiszoftver használatához, akkor keresd fel a [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] címet.

== Alapok ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Beállítások listája]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki GyIK]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki kiadások levelezőlistája]",

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
'moredotdotdot'  => 'Tovább...',
'mypage'         => 'Lapom',
'mytalk'         => 'Vitám',
'anontalk'       => 'az IP-cím vitalapja',
'navigation'     => 'Navigáció',
'and'            => 'és',

# Metadata in edit box
'metadata_help' => 'Metaadatok:',

'errorpagetitle'    => 'Hiba',
'returnto'          => 'Vissza a(z) $1 cikkhez.',
'tagline'           => 'A {{SITENAME}} wikiből',
'help'              => 'Segítség',
'search'            => 'Keresés',
'searchbutton'      => 'Keresés',
'go'                => 'Menj',
'searcharticle'     => 'Menj',
'history'           => 'laptörténet',
'history_short'     => 'Laptörténet',
'updatedmarker'     => 'az utolsó látogatásom óta frissítették',
'info_short'        => 'Információ',
'printableversion'  => 'Nyomtatható változat',
'permalink'         => 'Link erre a változatra',
'print'             => 'Nyomtatás',
'edit'              => 'Szerkesztés',
'create'            => 'Létrehozás',
'editthispage'      => 'Lap szerkesztése',
'create-this-page'  => 'Oldal létrehozása',
'delete'            => 'Törlés',
'deletethispage'    => 'Lap törlése',
'undelete_short'    => '$1 szerkesztés helyreállítása',
'protect'           => 'Lapvédelem',
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
'toolbox'           => 'Eszközök',
'userpage'          => 'Felhasználói lap megtekintése',
'projectpage'       => 'Projektlap megtekintése',
'imagepage'         => 'Képlap megtekintése',
'mediawikipage'     => 'Üzenetlap megtekintése',
'templatepage'      => 'Sablon lapjának megtekintése',
'viewhelppage'      => 'Súgólap megtekintése',
'categorypage'      => 'Kategórialap megtekintése',
'viewtalkpage'      => 'Beszélgetés megtekintése',
'otherlanguages'    => 'Más nyelveken',
'redirectedfrom'    => '($1 szócikkből átirányítva)',
'redirectpagesub'   => 'Átirányítás lap',
'lastmodifiedat'    => 'A lap utolsó módosítása $2, $1.', # $1 date, $2 time
'viewcount'         => 'Ezt a lapot {{PLURAL:$1|egy|$1}} alkalommal keresték föl.',
'protectedpage'     => 'Védett lap',
'jumpto'            => 'Ugrás:',
'jumptonavigation'  => 'navigáció',
'jumptosearch'      => 'keresés',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'A {{SITENAME}} wikiről',
'aboutpage'         => 'Project:Rólunk',
'bugreports'        => 'Hibabejelentések',
'bugreportspage'    => 'Project:Hibabejelentések',
'copyright'         => 'A tartalom a(z) $1 feltételei szerint használható fel.',
'copyrightpagename' => '{{SITENAME}} szerzői jogok',
'copyrightpage'     => '{{ns:project}}:Szerzői jogok',
'currentevents'     => 'Aktuális események',
'currentevents-url' => 'Project:Friss események',
'disclaimers'       => 'Jogi nyilatkozat',
'disclaimerpage'    => 'Project:Jogi nyilatkozat',
'edithelp'          => 'Szerkesztési súgó',
'edithelppage'      => 'Help:Hogyan szerkessz lapokat?',
'faq'               => 'GyIK',
'faqpage'           => 'Project:GyIK',
'helppage'          => 'Help:Tartalom',
'mainpage'          => 'Kezdőlap',
'policy-url'        => 'Project:Nyilatkozat',
'portal'            => 'Közösségi portál',
'portal-url'        => 'Project:Közösségi portál',
'privacy'           => 'Adatvédelmi irányelvek',
'privacypage'       => 'Project:Adatvédelmi irányelvek',
'sitesupport'       => 'Adományok',
'sitesupport-url'   => 'Project:Webhely támogatása',

'badaccess'        => 'Engedélyezési hiba',
'badaccess-group0' => 'Ezt a tevékenységet nem végezheted el.',
'badaccess-group1' => 'Ezt a tevékenységet csak a(z) $1 csoportjába tartozó felhasználó végezheti el.',
'badaccess-group2' => 'Ezt a tevékenységet csak a(z) $1 csoportok valamelyikébe tartozó felhasználó végezheti el.',
'badaccess-groups' => 'Ezt a tevékenységet csak a(z) $1 csoportok valamelyikébe tartozó felhasználó végezheti el.',

'versionrequired'     => 'A MediaWiki $1-s verziója szükséges',
'versionrequiredtext' => 'A lap használatához a MediaWiki $1-s verziójára van szükség. Lásd a [[Special:Version|verzió]] lapot.',

'ok'                      => 'OK',
'retrievedfrom'           => 'A lap eredeti címe: „$1”',
'youhavenewmessages'      => 'Új üzenet vár $1! (Az üzenetet $2.)',
'newmessageslink'         => 'a vitalapodon',
'newmessagesdifflink'     => 'külön is megtekintheted',
'youhavenewmessagesmulti' => 'Új üzenetet vár a(z) $1 lapon',
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
'feed-unavailable'        => 'Nincs elérhető hírcsatorna a(z) {{SITENAME}} wikin',
'site-rss-feed'           => '$1 RSS csatorna',
'site-atom-feed'          => '$1 Atom hírcsatorna',
'page-rss-feed'           => '„$1” RSS hírcsatorna',
'page-atom-feed'          => '„$1” Atom hírcsatorna',
'red-link-title'          => '$1 (nincs még megírva)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Szócikk',
'nstab-user'      => 'Felhasználói lap',
'nstab-media'     => 'Média',
'nstab-special'   => 'Speciális lap',
'nstab-project'   => 'Projektlap',
'nstab-image'     => 'Kép',
'nstab-mediawiki' => 'Üzenet',
'nstab-template'  => 'Sablon',
'nstab-help'      => 'Segítség',
'nstab-category'  => 'Kategória',

# Main script and global functions
'nosuchaction'      => 'Nincs ilyen művelet',
'nosuchactiontext'  => 'Az URL-ben megadott műveletet
a wiki nem ismeri fel',
'nosuchspecialpage' => 'Nem létezik ilyen speciális lap',
'nospecialpagetext' => "<big>'''Érvénytelen speciális lapot akartál megtekinteni.'''</big>

Az érvényes speciális lapok listáját a [[Special:Specialpages|Speciális lapok]] címen találod.",

# General errors
'error'                => 'Hiba',
'databaseerror'        => 'Adatbázishiba',
'dberrortext'          => 'Szintaktikai hiba fordult elő az adatbázis lekérdezésekor.
Ez a szoftverben lévő hibát jelezheti.
Az utoljára megkísérelt adatbázis lekérdezés az alábbi volt:
<blockquote><tt>$1</tt></blockquote>
a(z) „<tt>$2</tt>” függvényből.
A MySQL ezzel a hibával tért vissza: „<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Szintaktikai hiba fordult elő az adatbázis lekérdezésekor.
Az utoljára megkísérelt adatbázis lekérdezés
„$1” volt
a „<tt>$2</tt>” függvényből.
A MySQL ezzel a hibával tért vissza: „<tt>$3: $4</tt>”.',
'noconnect'            => 'Technikai problémák miatt nem sikerült az adatbázisgépre csatlakozni. <br />
$1',
'nodb'                 => 'Nem sikerült kiválasztani a(z) $1 adatbázist',
'cachederror'          => 'Ez a kért lap gyorsítótáras változata, ezért lehet, hogy nem tartalmazza a legújabb módosításokat.',
'laggedslavemode'      => 'Figyelem: Ez a lap nem feltétlenül tartalmazza a legfrissebb változtatásokat!',
'readonly'             => 'Az adatbázis le van zárva',
'enterlockreason'      => 'Add meg a lezárás okát, valamint egy becslést, hogy mikor kerül a lezárás feloldásra',
'readonlytext'         => 'A wiki adatbázisa ideiglenesen le van zárva (valószínűleg adatbázis-karbantartás miatt). A lezárás időtartama alatt a lapok nem szerkeszthetők, és új szócikkek sem hozhatóak létre, az oldalak azonban továbbra is böngészhetőek.

Az adminisztrátor, aki lezárta az adatbázist, az alábbi magyarázatot adta: $1',
'missingarticle'       => 'Az adatbázisban nem található meg a(z) „$1” nevű lap szövege.

Ennek oka általában egy olyan régi link követése, amely egy már törölt lapra hivatkozik.

Ha nem erről van szó akkor lehetséges, hogy programozási hibát találtál a szoftverben. Kérlek, értesíts erről egy adminisztrátort, és jegyezd fel neki az URL-t (pontos webcímet) is.',
'readonly_lag'         => 'Az adatbázis automatikusan zárolásra került, amíg a mellékkiszolgálók utolérik a főkiszolgálót.',
'internalerror'        => 'Belső hiba',
'internalerror_info'   => 'Belső hiba: $1',
'filecopyerror'        => 'Nem tudtam átmásolni a(z) „$1” fájlt „$2” névre.',
'filerenameerror'      => 'Nem tudtam átnevezni a(z) „$1” fájlt „$2” névre.',
'filedeleteerror'      => 'Nem tudtam törölni a(z) „$1” fájlt.',
'directorycreateerror' => 'Nem tudtam létrehozni a(z) „$1” könyvtárat.',
'filenotfound'         => 'A(z) „$1” fájl nem található.',
'fileexistserror'      => 'Nem tudtam írni a(z) „$1” fájlba: a fájl már létezik',
'unexpected'           => 'Váratlan érték: „$1”=„$2”.',
'formerror'            => 'Hiba: nem tudom elküldeni az űrlapot',
'badarticleerror'      => 'Ez a tevékenység nem végezhető el ezen a lapon.',
'cannotdelete'         => 'A megadott lapot vagy fájlt nem lehet törölni. (Talán már valaki más törölte.)',
'badtitle'             => 'Hibás cím',
'badtitletext'         => 'A kért oldal címe érvénytelen, üres, vagy rosszul hivatkozott nyelvközi vagy interwiki cím volt. Olyan karaktereket is tartalmazhatott, melyek a címekben nem használhatóak.',
'perfdisabled'         => 'Elnézést, de ez a lehetőség átmenetileg nem elérhető, mert annyira lelassítja az adatbázist, hogy senki nem tudja a wikit használni.',
'perfcached'           => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, és ezért lehetséges, hogy nem a legfrissebb változatot mutatják:",
'perfcachedts'         => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, legutóbbi frissítésük ideje $1.",
'querypage-no-updates' => 'Az oldal frissítése jelenleg le van tiltva. Az itt szereplő adatok nem frissülnek azonnal.',
'wrong_wfQuery_params' => 'A wfQuery() függvény paraméterei hibásak<br />
Függvény: $1<br />
Lekérdezés: $2',
'viewsource'           => 'Lapforrás',
'viewsourcefor'        => '$1 változata',
'actionthrottled'      => 'Művelet megszakítva',
'actionthrottledtext'  => 'A spamek elleni védekezés miatt nem végezheted el a műveletet túl sokszor egy adott időn belül, és te átlépted a megengedett határt. Próbálkozz újra néhány perc múlva.',
'protectedpagetext'    => 'Ezt a lapot a szerkesztések megakadályozása érdekében zároltuk. Módosításokat a vitalapon javasolhatsz, a védelem feloldását az adminisztrátorok üzenőfalán kérheted .',
'viewsourcetext'       => 'Megtekintheted és másolhatod a lap forrását:',
'protectedinterface'   => 'Ez a lap a honlap felületéhez szolgáltat szöveget a szoftver számára, és a visszaélések elkerülése végett le van zárva. A vitalapon javasolhatsz módosításokat.',
'editinginterface'     => "'''Vigyázat:''' egy olyan lapot szerkesztesz, ami a MediaWiki szoftver felhasználói felületéthez tarzozik. A lap megváltoztatása hatással lesz más felhasználók számára is. Fordításra inkább használd a MediaWiki fordítására indított kezdeményezést, a [http://translatewiki.net/wiki/Main_Page?setlang=hu Betawikit].",
'sqlhidden'            => '(rejtett SQL lekérdezés)',
'cascadeprotected'     => 'Ez a lap szerkesztés elleni védelemmel lett ellátva, mert a következő {{PLURAL:$1|lapon|lapokon}} be van kapcsolva a „kaszkádolt” védelem:
$2',
'namespaceprotected'   => "Nincs jogosultságod a(z) '''$1''' névtérbeli lapok szerkesztésére.",
'customcssjsprotected' => 'Nincs jogosultságod a lap szerkesztéséhez, mert egy másik felhasználó személyes beállításait tartalmazza.',
'ns-specialprotected'  => 'A {{ns:special}} névtérben található lapok nem szerkeszthetőek.',
'titleprotected'       => 'A cikk elkészítését [[User:$1|$1]] blokkolta, oka: <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'Kijelentkezés',
'logouttext'                 => '<strong>Kijelentkeztél.</strong>

Folytathatod névtelenül  a(z) {{SITENAME}} használatát, vagy ismét bejelentkezhetsz ugyanezzel, vagy egy másik felhasználói névvel. Néhány oldalon lehet, hogy továbbra is azt látod, hogy bejelentkezve vagy, mindaddig, amíg nem üríted ki a böngésződ gyorsítótárát.',
'welcomecreation'            => '== Köszöntünk, $1! ==

A felhasználói környezetedet létrehoztuk. Ne felejtsd el átnézni a személyes beállításaidat.',
'loginpagetitle'             => 'Bejelentkezés',
'yourname'                   => 'Felhasználói neved:',
'yourpassword'               => 'Jelszavad:',
'yourpasswordagain'          => 'Jelszavad ismét:',
'remembermypassword'         => 'Ne léptessen ki a böngésző bezárásakor.',
'yourdomainname'             => 'A domainneved:',
'externaldberror'            => 'Hiba történt a külső adatbázis hitelesítése közben, vagy nem vagy jogosult a külső fiókod frissítésére.',
'loginproblem'               => '<b>Hiba történt a bejelentkezésed során.</b><br />Kérlek, próbálkozz újra!',
'login'                      => 'Bejelentkezés',
'loginprompt'                => 'Engedélyezned kell a cookie-kat, hogy bejelentkezhess a {{grammar:be|{{SITENAME}}}}.',
'userlogin'                  => 'Bejelentkezés / fiók létrehozása',
'logout'                     => 'Kijelentkezés',
'userlogout'                 => 'Kijelentkezés',
'notloggedin'                => 'Nem vagy bejelentkezve',
'nologin'                    => 'Nem rendelkezel még felhasználói fiókkal? $1.',
'nologinlink'                => 'Itt regisztrálhatsz',
'createaccount'              => 'Regisztráció',
'gotaccount'                 => 'Ha már korábban regisztráltál, $1!',
'gotaccountlink'             => 'jelentkezz be',
'createaccountmail'          => 'e-mailben',
'badretype'                  => 'Az általad megadott jelszavak nem egyeznek.',
'userexists'                 => 'A megadott felhasználói név már foglalt. Kérlek, válassz másikat!',
'youremail'                  => 'Az e-mail címed:',
'username'                   => 'Felhasználói név:',
'uid'                        => 'Azonosító:',
'yourrealname'               => 'Valódi neved:',
'yourlanguage'               => 'A felület nyelve:',
'yourvariant'                => 'Változó',
'yournick'                   => 'Az aláírásokban használni kívánt névformád:',
'badsig'                     => 'Érvénytelen aláírás; ellenőrizd a HTML-formázást.',
'badsiglength'               => 'A megadott név túl hosszú; $1 karakternél rövidebbnek kell lennie.',
'email'                      => 'E-mail',
'prefs-help-realname'        => 'A valódi nevet nem kötelező megadni, de ha úgy döntesz, hogy megadod, így leszel feltüntetve a munkád szerzőjeként.',
'loginerror'                 => 'Belépési hiba',
'prefs-help-email'           => 'Az e-mail címet nem kötelező, de lehetővé teszi, hogy más szerkesztők kapcsolatba lépjenek veled a felhasználói vagy vitalapodon keresztül, anélkül, hogy névtelenséged feladnád.',
'prefs-help-email-required'  => 'Meg kell adnod az e-mail címedet.',
'nocookiesnew'               => 'A felhasználói fiókod létrejött, de nem vagy bejelentkezve. A wiki cookie-kat („süti”) használ a felhasználók azonosítására. Nálad ezek le vannak tiltva. Kérlek, engedélyezd őket, majd lépj be új azonosítóddal és jelszavaddal.',
'nocookieslogin'             => 'A wiki cookie-kat („süti”) használ az azonosításhoz. Nálad ezek le vannak tiltva. Engedélyezd őket, majd próbáld meg újra.',
'noname'                     => 'Nem érvényes felhasználónevet adtál meg.',
'loginsuccesstitle'          => 'Sikeres bejelentkezés',
'loginsuccess'               => 'Most már be vagy jelentkezve a(z) {{grammar:ba|{{SITENAME}}}} „$1” néven.',
'nosuchuser'                 => 'Nem létezik „$1” nevű felhasználó. Ellenőrizd, hogy helyesen írtad-e be, vagy hozz létre egy új fiókot.',
'nosuchusershort'            => 'Nem létezik „<nowiki>$1</nowiki>” nevű felhasználó. Ellenőrizd, hogy helyesen írtad-e be, vagy hozz létre egy új fiókot.',
'nouserspecified'            => 'Meg kell adnod a felhasználói nevet.',
'wrongpassword'              => 'A megadott jelszó érvénytelen. Próbáld meg újra.',
'wrongpasswordempty'         => 'Nem adtál meg jelszót. Próbáld meg újra.',
'passwordtooshort'           => 'Az általad megadott jelszó érvénytelen vagy túl rövid. Legalább $1 karakterből kell állnia, és nem egyezhet meg a felhasználói neveddel.',
'mailmypassword'             => 'Jelszó elküldése e-mailben',
'passwordremindertitle'      => '{{SITENAME}} jelszóemlékeztető',
'passwordremindertext'       => 'Valaki (vélhetően te, a(z) $1 IP-címről)
azt kérte, hogy küldjünk neked új {{SITENAME}}-jelszót ($4).
A "$2" felhasználó jelszava jelenleg "$3".
Lépj be, és változtasd meg.

Ha nem kértél új jelszót, vagy közben eszedbe jutott a régi, és már nem akarod megváltoztatni, nyugodtan hagyd figyelmen kívül ezt az értesítést, és használd továbbra is a régi jelszavadat.',
'noemail'                    => '„$1” e-mail címe nincs megadva.',
'passwordsent'               => 'Az új jelszót elküldtük „$1” e-mail címére.
Lépj be a levélben található adatokkal.',
'blocked-mailpassword'       => 'Az IP-címedet blokkoltuk, azaz eltiltottuk a szerkesztéstől, ezért a visszaélések elkerülése érdekében a jelszóvisszaállítás funkciót nem használhatod.',
'eauthentsent'               => 'Egy ellenőrző e-mailt küldtünk a megadott címre. Mielőtt más leveleket kaphatnál, igazolnod kell az e-mailben írt utasításoknak megfelelően, hogy valóban a tiéd a megadott cím.',
'throttled-mailpassword'     => 'Már elküldtünk egy jelszóemlékeztetőt az utóbbi $1 órában. A visszaélések elkerülése végett $1 óránként csak egy jelszó-emlékeztetőt küldünk.',
'mailerror'                  => 'Hiba történt az e-mail küldése közben: $1',
'acct_creation_throttle_hit' => 'Már létrehoztál $1 felhasználói azonosítót. Sajnáljuk, de többet nem hozhatsz létre.',
'emailauthenticated'         => '$1-kor megerősítetted az e-mail címedet.',
'emailnotauthenticated'      => 'Az e-mail címed még <strong>nincs megerősítve</strong>. E-mailek küldése és fogadása nem engedélyezett.',
'noemailprefs'               => 'Az alábbi funkciók használatához meg kell adnod az e-mail címedet.',
'emailconfirmlink'           => 'E-mail cím megerősítése',
'invalidemailaddress'        => 'A megadott e-mail cím érvénytelen formátumú. Kérlek, adj meg egy helyesen formázott e-mail címet vagy hagyd üresen azt a mezőt.',
'accountcreated'             => 'Felhasználói fiók létrehozva',
'accountcreatedtext'         => '$1 felhasználói fiókja sikeresen létrejött.',
'createaccount-title'        => 'Új {{SITENAME}}-fiók létrehozása',
'createaccount-text'         => '$1 létrehozott számodra egy „$2” nevű {{SITENAME}}-azonosítót ($4). A hozzátartozó jelszó "$3", melyet minél előbb változtass meg.

Ha nem kértél új azonosítót, és tévedésből kaptad ezt a levelet, nyugodtan hagyd figyelmen kívül.',
'loginlanguagelabel'         => 'Nyelv: $1',

# Password reset dialog
'resetpass'               => 'A fiók jelszavának módosítása',
'resetpass_announce'      => 'Az e-mailben elküldött ideiglenes kóddal jelentkeztél be. A bejelentkezés befejezéséhez meg kell megadnod egy új jelszót:',
'resetpass_text'          => '<!-- Ide írd a szöveget -->',
'resetpass_header'        => 'Jelszó módosítása',
'resetpass_submit'        => 'Add meg a jelszót és jelentkezz be',
'resetpass_success'       => 'A jelszavad megváltoztatása sikeresen befejeződött! Bejelentkezés...',
'resetpass_bad_temporary' => 'Az ideiglenes jelszó hibás. Lehet, hogy már sikeresen megváltoztattad a jelszavadat, vagy új ideiglenes jelszót kértél.',
'resetpass_forbidden'     => 'A jelszavak nem változtathatóak meg ebben a wikiben',
'resetpass_missing'       => 'Az űrlap adatai hiányoznak.',

# Edit page toolbar
'bold_sample'     => 'Félkövér szöveg',
'bold_tip'        => 'Félkövér szöveg',
'italic_sample'   => 'Dőlt szöveg',
'italic_tip'      => 'Dőlt szöveg',
'link_sample'     => 'Belső hivatkozás',
'link_tip'        => 'Belső hivatkozás',
'extlink_sample'  => 'http://www.példa.hu hivatkozás címe',
'extlink_tip'     => 'Külső hivatkozás (ne felejtsd el a http:// előtagot)',
'headline_sample' => 'Alfejezet címe',
'headline_tip'    => 'Alfejezetcím',
'math_sample'     => 'Ide írd a képletet',
'math_tip'        => 'Matematikai képlet (LaTeX)',
'nowiki_sample'   => 'Ide írd a nem-formázott szöveget',
'nowiki_tip'      => 'Wiki formázás kikapcsolása',
'image_sample'    => 'Pelda.jpg',
'image_tip'       => 'Kép beszúrása',
'media_sample'    => 'Peldaegyketto.ogg',
'media_tip'       => 'Fájlhivatkozás',
'sig_tip'         => 'Aláírás időponttal',
'hr_tip'          => 'Vízszintes vonal (ritkán használd)',

# Edit pages
'summary'                   => 'Összefoglaló',
'subject'                   => 'Téma/főcím',
'minoredit'                 => 'Apró változtatás',
'watchthis'                 => 'A lap figyelése',
'savearticle'               => 'Lap mentése',
'preview'                   => 'Előnézet',
'showpreview'               => 'Előnézet megtekintése',
'showlivepreview'           => 'Élő előnézet',
'showdiff'                  => 'Változtatások megtekintése',
'anoneditwarning'           => "'''Figyelem:''' Nem vagy bejelentkezve, ha szerkesztesz, az IP-címed látható lesz a laptörténetben.",
'missingsummary'            => "'''Emlékeztető:''' Nem adtál meg szerkesztési összefoglalót. Ha összefoglaló nélkül akarod elküldeni a szöveget, kattints újra a mentésre.",
'missingcommenttext'        => 'Kérjük, hogy írj összefoglalót szerkesztésedhez.',
'missingcommentheader'      => "'''Emlékeztető:''' Nem adtad meg a megjegyzés tárgyát/címét. Ha ismét a Mentés gombra kattintasz, akkor a szerkesztésed anélkül kerül mentésre.",
'summary-preview'           => 'A szerkesztési összefoglaló előnézete',
'subject-preview'           => 'A szakaszcím előnézete',
'blockedtitle'              => 'A felhasználó fel van függesztve',
'blockedtext'               => "<big>'''A felhasználónevedet vagy az IP-címedet blokkoltuk.'''</big>

A blokkolást $1 tette. Az általa felhozott indok: ''$2''.

* A blokkolás kezdete: $8
* A blokkolás lejárata: $6
* Blokkolt felhasználó: $7

Kapcsolatba léphetsz $1 felhasználóval, vagy egy másik [[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]], és megbeszélheted vele a blokkolást.
Az 'E-mail küldése ennek a felhasználónak' funkciót nem használhatod, ha a megadott e-mail cím a
[[Special:Preferences|fiókbeállításaidban]] nem érvényes, és nem blokkolták annak a használatát.
Jelenlegi IP-címed: $3, a blokkolás azonosítószáma: #$5. Kérjük, hogy érdeklődés esetén lehetőleg mindkettőt add meg.",
'autoblockedtext'           => "Az IP-címről automatikusan blokkolva lett, mert korábban egy olyan felhasználó használta, akit $1 blokkolt, az alábbi indoklással:

:''$2''

*A blokk kezdete: '''$8'''
*A blokk lejárata: '''$6'''

Kapcsolatba léphetsz $1 felhasználóval, vagy egy másik [[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]], és megbeszélheted vele a blokkolást.

Az 'E-mail küldése ennek a felhasználónak' funkciót nem használhatod, ha a megadott e-mail cím a
[[Special:Preferences|fiókbeállításaidban]] nem érvényes, és nem blokkolták annak a használatát.

A blokkolás azonosítószáma: $5. Kérjük, hogy érdeklődés esetén ezt add meg.",
'blockednoreason'           => 'nem lett ok megadva',
'blockedoriginalsource'     => "'''$1''' forrása alább látható:",
'blockededitsource'         => "'''$1''' lapon '''általad végrehajtott szerkesztések''' szövege:",
'whitelistedittitle'        => 'A szerkesztéshez be kell jelentkezned',
'whitelistedittext'         => 'A szócikkek szerkesztéséhez $1.',
'whitelistreadtitle'        => 'Az olvasáshoz be kell lépned',
'whitelistreadtext'         => 'A lapok olvasásához [[Special:Userlogin|be kell lépned]].',
'whitelistacctitle'         => 'Nem készíthetsz új felhasználói fiókot',
'whitelistacctext'          => 'Felhasználói fiókok létrehozásához [[Special:Userlogin|be kell jelentkezned]] a szükséges jogosultságokkal.',
'confirmedittitle'          => 'Szerkesztéshez az e-mail cím megerősítése szükséges',
'confirmedittext'           => 'A lapok szerkesztése előtt meg kell erősítened az e-mail címedet. Kérjük, hogy a [[Special:Preferences|felhasználói beállításaidban]] írd be, majd erősítsd meg az e-mail címedet.',
'nosuchsectiontitle'        => 'Nincs ilyen szakasz',
'nosuchsectiontext'         => 'Egy olyan szakaszt próbáltál meg szerkeszteni, amely nem létezik.  Mivel nincs $1. szakasz, ezért nem lehet elmenteni.',
'loginreqtitle'             => 'Bejelentkezés szükséges',
'loginreqlink'              => 'Be kell jelentkezned',
'loginreqpagetext'          => '$1 más oldalak megtekintéséhez.',
'accmailtitle'              => 'A jelszót elküldtük.',
'accmailtext'               => '„$1” jelszavát elküldtük a(z) $2 címre.',
'newarticle'                => '(Új)',
'newarticletext'            => "Egy olyan lapra mutató hivatkozást követtél, mely még nem létezik.
Ha létre akarod hozni, csak gépeld be a szövegét a lenti szövegdobozba. Ha kész vagy, az „Előnézet megtekintése” gombbal ellenőrizheted, hogy úgy fog-e kinézni, ahogy szeretnéd, és a „Lap mentése” gombbal tudod elmenteni.

A [[{{MediaWiki:Helppage}}|súgó]] lapon további információkat találsz, melyek segíthetnek eligazodni.

Ha tévedésből jöttél ide, csak nyomd meg a böngésző '''Vissza/Back'''
gombját.",
'anontalkpagetext'          => "----''Ez egy olyan anonim szerkesztő vitalapja, aki még nem regisztrált, vagy csak nem jelentkezett be. Ezért az IP-címét (<tt>{{PAGENAME}}</tt>) használjuk az azonosítására. Ugyanazon az IP-címen egy sor szerkesztő osztozhat az idők folyamán. Ha úgy látod, hogy az üzenetek, amiket ide kapsz, nem neked szólnak, regisztrálj vagy ha már regisztráltál, lépj be, hogy ne keverjenek össze másokkal.''",
'noarticletext'             => 'Ez a lap jelenleg nem tartalmaz szöveget. [[Special:Search/{{PAGENAME}}|Rákereshetsz erre a címszóra]], vagy [{{fullurl:{{FULLPAGENAME}}|action=edit}} szerkesztheted a lapot].',
'userpage-userdoesnotexist' => 'Nincs „$1” nevű regisztrált felhasználó. Nézd meg, hogy valóban ezt a lapot szeretnéd létrehozni vagy szerkeszteni.',
'clearyourcache'            => "'''Megjegyzés:''' A beállítások elmentése után frissítened kell a böngésződ gyorsítótárát, hogy a változások érvénybe lépjenek. '''Mozilla''' / '''Firefox''' / '''Safari:''' tartsd lenyomva a Shift gombot és kattints a ''Reload'' / ''Frissítés'' gombra az eszköztáron, vagy használd a ''Ctrl–F5'' billentyűkombinációt (Apple Mac-en ''Cmd–Shift–R''); '''Internet Explorer:''' tartsd nyomva a ''Ctrl''-t, és kattints a ''Reload'' / ''Frissítés'' gombra, vagy nyomj ''Ctrl–F5''-öt; '''Konqueror:''' egyszerűen csak kattints a ''Reload'' / ''Frissítés'' gombra (vagy ''Ctrl–R'' vagy ''F5''); '''Opera''' felhasználóknak teljesen ki kell üríteniük a gyorsítótárat a ''Tools→Preferences'' menüben.",
'usercssjsyoucanpreview'    => '<strong>Tipp:</strong> Használd az „Előnézet megtekintése” gombot az új CSS/JS teszteléséhez mentés előtt.',
'usercsspreview'            => "'''Ne felejtsd el, hogy ez csak a CSS előnézete és még nincs elmentve!'''",
'userjspreview'             => "'''Ne felejtsd el, hogy még csak teszteled a felhasználói JavaScriptedet, és még nincs elmentve!'''",
'userinvalidcssjstitle'     => "'''Figyelem:''' Nincs „$1” nevű felület. A felületekhez tartozó .css/.js oldalak kisbetűvel kezdődnek, például ''{{ns:user}}:Gipsz Jakab/monobook.css'' és nem ''{{ns:user}}:Gipsz Jakab/Monobook.css''.",
'updated'                   => '(Frissítve)',
'note'                      => '<strong>Megjegyzés:</strong>',
'previewnote'               => '<strong>Ne feledd, hogy ez csak előnézet, a munkád még nincs elmentve!</strong>',
'previewconflict'           => 'Ez az előnézet a felső szerkesztőablakban levő szöveg mentés utáni megfelelőjét mutatja.',
'session_fail_preview'      => '<strong>Sajnos nem tudtuk feldolgozni a szerkesztésedet, mert elveszett a session-adat. Kérjük próbálkozz újra! Amennyiben továbbra sem sikerül, próbálj meg kijelentkezni, majd ismét bejelentkezni!</strong>',
'session_fail_preview_html' => "<strong>Elnézést! A munkamenet adatainak megsemmisülése miatt nem tudtuk feldolgozni a szerkesztésedet.</strong>

''Mivel ebben a wikiben a nyers HTML engedélyezett, az előnézet a JavaScript támadások miatti elővigyázatosságból rejtett.''

<strong>Ha ez egy jogos szerkesztési kísérlet, akkor próbáld meg újra. Ha még mindig nem működik, próbáld meg, hogy kijelentkezel, és visszajelentkezel.</strong>",
'token_suffix_mismatch'     => '<strong>A szerkesztésedet elutasítottuk, mert az ügyfeled megváltoztatta az írásjeleket
a szerkesztési vezérjelben. A szerkesztést azért utasítottuk vissza, hogy megelőzzük a cikk szövegének sérülését.
Ez olyankor fordul elő, ha az általad használt webalapú névtelen proxy szolgáltatás hibás.</strong>',
'editing'                   => '$1 szerkesztése',
'editingsection'            => '$1 szerkesztése (szakasz)',
'editingcomment'            => '$1 szerkesztése (üzenet)',
'editconflict'              => 'Szerkesztési ütközés: $1',
'explainconflict'           => 'Valaki megváltoztatta a lapot azóta, hogy szerkeszteni kezdted.
A felső szövegablak tartalmazza az oldal jelenlegi állapotát.
A te módosításaid az alsó ablakban láthatóak.
Át kell vezetned a módosításaidat a felső szövegbe.
<b>Csak</b> a felső ablakban levő szöveg lesz elmentve, amikor a „Lap mentése” gombra kattintasz.',
'yourtext'                  => 'A te változatod',
'storedversion'             => 'A tárolt változat',
'nonunicodebrowser'         => '<strong>Figyelem: A böngésződ nem Unicode kompatibilis. Egy programozási trükk segítségével biztonságban szerkesztheted a cikkeket: a nem ASCII karakterek a szerkesztőablakban hexadeciális kódokként jelennek meg.</strong>',
'editingold'                => '<strong>FIGYELMEZTETÉS: A lap egy elavult változatát szerkeszted.
Ha elmented, akkor az ezen változat után végzett összes módosítás elvész.</strong>',
'yourdiff'                  => 'Eltérések',
'copyrightwarning'          => 'A szöveg elküldésével tanúsítod, hogy nem sért szerzői jogokat, és engedélyezed a(z) $2 szerinti felhasználását (lásd $1). Ha nem akarod, hogy az írásodat módosítsák vagy továbbterjesszék, akkor ne küldd be.<br />
<strong>Ne küldj be engedély nélkül szerzői jogilag védett munkákat!</strong>',
'copyrightwarning2'         => 'A {{SITENAME}} tartalmát, így az általad beküldött szövegeket is más résztvevők átírhatják vagy törölhetik. Ha nem akarod, hogy a művedet átírják, ne küldd be ide.<br />
A beküldéssel egyben azt is tanúsítod, hogy a beküldött szöveget magad írtad, vagy közkincsből vagy más szabadon felhasználható forrásból másoltad (a részletekért lásd: $1).
<strong>NE KÜLDJ BE JOGVÉDETT MŰVET ENGEDÉLY NÉLKÜL!</strong>',
'longpagewarning'           => '<strong>FIGYELEM: Ez a lap $1 kilobájt hosszú; egyes
böngészőknek problémát okoz a 32 kB-os vagy nagyobb lapok szerkesztése.
Fontold meg a lap kisebb szakaszokra bontását.</strong>',
'longpageerror'             => '<strong>HIBA: Az általad beküldött szöveg $1 kilobájt hosszú, ami több az engedélyezett $2 kilobájtnál. It cannot be saved.</strong>',
'readonlywarning'           => '<strong>FIGYELMEZTETÉS: A wiki adatbázisát karbantartás miatt zárolták,
ezért sajnos nem tudod majd elmenteni a szerkesztéseidet. A lap szöveget kimásolhatod
egy szövegfájlba, amit elmenthetsz későbbre.</strong>',
'protectedpagewarning'      => '<strong>FIGYELEM: Ez a lap védett, csak adminisztrátorok szerkeszthetik.</strong>',
'semiprotectedpagewarning'  => "'''Megjegyzés:''' ez a lap védett, nem vagy újonnan regisztrált felhasználók nem szerkeszthetik.",
'cascadeprotectedwarning'   => "'''Figyelem:''' ez a lap le van zárva, csak adminisztrátorok szerkeszthetik, mert a következő kaszkádvédelemmel ellátott {{PLURAL:$1|lapon|lapokon}} szerepel beillesztve:",
'titleprotectedwarning'     => '<strong>FIGYELEM:  Ez a lap úgy van levédve, hogy csak néhány felhasználó hozhatja létre.</strong>',
'templatesused'             => 'Sablonok ezen a lapon:',
'templatesusedpreview'      => 'Az előnézetben használt sablonok:',
'templatesusedsection'      => 'Szakaszban használt sablonok:',
'template-protected'        => '(védett)',
'template-semiprotected'    => '(félig-védett)',
'hiddencategories'          => 'Ez a lap {{PLURAL:$1|egy|$1}} rejtett kategóriába tartozik:',
'edittools'                 => '<!-- Ez a szöveg a szerkesztés és a feltöltés űrlap alatt lesz látható. -->',
'nocreatetitle'             => 'A laplétrehozás korlátozott',
'nocreatetext'              => 'Ezen a webhelyen korlátozták az új oldalak készítését.
Visszamehetsz és szerkeszthetsz egy létező lapot, ill. [[Special:Userlogin|bejelentkezhetsz vagy létrehozhatod a fiókodat]].',
'nocreate-loggedin'         => 'Az új lapok készítése nem engedélyezett ebben a wikiben a számodra.',
'permissionserrors'         => 'Engedélyezési hibák',
'permissionserrorstext'     => 'A művelet elvégzése nem engedélyezett a számodra, a következő {{PLURAL:$1|ok|okok}} miatt:',
'recreate-deleted-warn'     => "'''Vigyázat: egy olyan lapot akarsz létrehozni, amelyet korábban már töröltünk.'''

Mielőtt létrehoznád, nézd meg, miért került korábban törlésre és ellenőrizd,
hogy a törlés indoka nem érvényes-e még. A törlési naplóban a lapról az alábbi bejegyzések szerepelnek:",

# "Undo" feature
'undo-success' => 'A szerkesztés visszavonható. Kérlek ellenőrizd alább a változásokat, hogy valóban ezt szeretnéd-e tenni, majd kattints a lap mentése gombra a visszavonás véglegesítéséhez.',
'undo-failure' => 'A szerkesztést nem lehet automatikusan visszavonni vele ütköző későbbi szerkesztések miatt.',
'undo-summary' => 'Visszavontam [[Special:Contributions/$2|$2]] ([[User talk:$2|vita]]) szerkesztését (oldid: $1)',

# Account creation failure
'cantcreateaccounttitle' => 'Felhasználói fiók létrehozása sikertelen',
'cantcreateaccount-text' => "Erről az IP-címről (<b>$1</b>) átmenetileg nem lehet regisztrálni, mert [[User:$3|$3]] blokkolta az alábbi indokkal:
:''$2''

Egy IP-címet gyakran több különböző ember is használ; lehetséges, hogy egy másnak szánt blokkba futottál bele. Ebben az esetben (vagy ha kifogásod van a blokk ellen) vedd fel velünk a kapcsolatot! '''A blokkal kapcsolatos üzenetekben írd meg az IP-címedet ($1), a blokkoló admin nevét, és a blokk indokát!''' Ha szeretnéd, hogy létrehozzunk neked egy új felhasználót, adj meg egy e-mail címet, és mondd meg, milyen felhasználónevet szeretnél. Érdemes magadról is írnod pár szót, így elkerülheted, hogy összetévesszünk azzal, akinek a blokkot szántuk.",

# History pages
'viewpagelogs'        => 'A lap a rendszernaplókban',
'nohistory'           => 'Ennek a lapnak nincs szerkesztési története.',
'revnotfound'         => 'A változat nem található',
'revnotfoundtext'     => 'A lap általad kért régi változatát nem találom. Kérlek, ellenőrizd az URL-t, amivel erre a lapra jutottál.',
'currentrev'          => 'Aktuális változat',
'revisionasof'        => '$1 változat',
'revision-info'       => 'A lap korábbi változatát látod, amilyen $2 $1-kor történt szerkesztése után volt.',
'previousrevision'    => '←Régebbi változat',
'nextrevision'        => 'Újabb változat→',
'currentrevisionlink' => 'legfrissebb változat',
'cur'                 => 'akt',
'next'                => 'következő',
'last'                => 'előző',
'page_first'          => 'első',
'page_last'           => 'utolsó',
'histlegend'          => 'Eltérések kijelölése: jelöld ki az összehasonlítandó változatokat, majd nyomd meg az Enter billentyűt, vagy az alul lévő gombot.<br />
Jelmagyarázat: (akt) = eltérés az aktuális változattól, (előző) = eltérés az előző változattól, A = Apró változtatás',
'deletedrev'          => '[törölve]',
'histfirst'           => 'legkorábbi',
'histlast'            => 'legutolsó',
'historysize'         => '($1 bájt)',
'historyempty'        => '(üres)',

# Revision feed
'history-feed-title'          => 'Laptörténet',
'history-feed-description'    => 'Az oldal laptörténete a wikiben',
'history-feed-item-nocomment' => '$1, $2-n', # user at time
'history-feed-empty'          => 'A kért oldal nem létezik.
Lehet, hogy törölték a wikiből, vagy átnevezték.
Próbálkozhatsz a témával kapcsolatos lapok [[Special:Search|keresésével]].',

# Revision deletion
'rev-deleted-comment'         => '(megjegyzés eltávolítva)',
'rev-deleted-user'            => '(felhasználónév eltávolítva)',
'rev-deleted-event'           => '(bejegyzés eltávolítva)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Ezt a változatot eltávolítottuk a nyilvános archívumokból.
További információkat a [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Ezt a változatot eltávolították a nyilvános archívumokból.
Mivel adminisztrátor vagy ezen a webhelyen, te megtekintheted; további részleteket a [{{fullurl:Special:Napló/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.</div>',
'rev-delundel'                => 'megjelenítés/elrejtés',
'revisiondelete'              => 'Változatok törlése/helyreállítása',
'revdelete-nooldid-title'     => 'Nem adtad meg a célváltozatot',
'revdelete-nooldid-text'      => 'Nem adtad meg a célváltozatot vagy változatokat, melyeken el akarod végezni a műveletet.',
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
'revdelete-hide-restricted'   => 'Alkalmazás az adminisztrátorokra és a többi felhasználóra is',
'revdelete-suppress'          => 'Adatok elrejtése az adminisztrátorok és a többi felhasználó elöl is',
'revdelete-hide-image'        => 'A fájl tartalomának elrejtése',
'revdelete-unsuppress'        => 'Korlátozások eltávolítása a visszaállított változatokról',
'revdelete-log'               => 'Megjegyzés:',
'revdelete-submit'            => 'Alkalmazás a kiválasztott változatra',
'revdelete-logentry'          => '[[$1]] változatának láthatóságának módosítása',
'logdelete-logentry'          => '[[$1]] eseményének láthatóságának módosítása',
'revdelete-logaction'         => '$1 változat átállítva $2 módra',
'logdelete-logaction'         => '[[$3]] $1 eseménye átállítva $2 módra',
'revdelete-success'           => 'A változat láthatóságának beállítása sikeresen elvégezve.',
'logdelete-success'           => 'Az esemény láthatóságának beállítása sikeresen elvégezve.',
'pagehist'                    => 'Laptörténet',
'deletedhist'                 => 'Törölt változatok',

# Oversight log
'oversightlog'    => 'Adatvédelmi biztos-napló',
'overlogpagetext' => 'Az alábbiakban látható az adminisztrátorok elől legutóbb elrejtett törlések és blokkok listája. A jelenleg érvényben lévő kitiltásokat és blokkolásokat lásd az [[Special:Ipblocklist|Blokkolt IP-címek listáján]].',

# History merging
'mergehistory'                     => 'Laptörténetek egyesítése',
'mergehistory-header'              => 'Ez az oldal lehetővé teszi egy oldal laptörténetének egyesítését egy másikéval.
Győződj meg róla, hogy a laptörténet folytonossága megmarad.',
'mergehistory-box'                 => 'Két oldal változatainak egyesítése:',
'mergehistory-from'                => 'Forrásoldal:',
'mergehistory-into'                => 'Céloldal:',
'mergehistory-list'                => 'Egyesíthető laptörténet',
'mergehistory-merge'               => '[[:$1]] és [[:$2]] következő változatai vonhatóak össze. A gombok segítségével választhatod ki, ha csak egy adott idő előttieket szeretnél feldolgozni.',
'mergehistory-go'                  => 'Egyesíthető szerkesztések mutatása',
'mergehistory-submit'              => 'Változatok egyesítése',
'mergehistory-empty'               => 'Nincs egyesíthető változás.',
'mergehistory-success'             => '[[:$1]] $3 változata sikeresen egyesítve lett a(z) [[:$2]] lappal.',
'mergehistory-fail'                => 'Nem sikerült a laptörténetek egyesítése, kérlek ellenőrízd újra az oldalt és a megadott időparamétereket.',
'mergehistory-no-source'           => 'Nem létezik forráslap $1 néven.',
'mergehistory-no-destination'      => 'Nem létezik céllap $1 néven.',
'mergehistory-invalid-source'      => 'A forráslapnak érvényes címet kell megadni.',
'mergehistory-invalid-destination' => 'A céllapnak érvényes címet kell megadni.',
'mergehistory-autocomment'         => 'Egyesítette a(z) [[:$1]] lapot a(z) [[:$2]] lappal',
'mergehistory-comment'             => 'Egyesítette a(z) [[:$1]] lapot a(z) [[:$2]] lappal: $3',

# Merge log
'mergelog'           => 'Egyesítési napló',
'pagemerge-logentry' => '[[$1]] és [[$2]] egyesítve ($3 változatig)',
'revertmerge'        => 'Szétválasztás',
'mergelogpagetext'   => 'A lapok egyesítéséről szóló napló. Szűkítheted a listát a műveletet végző felhasználó vagy az érintett oldal megadásával.',

# Diffs
'history-title'           => 'A(z) „$1” laptörténete',
'difference'              => '(Változatok közti eltérés)',
'lineno'                  => '$1. sor:',
'compareselectedversions' => 'Kiválasztott változatok összehasonlítása',
'editundo'                => 'visszavonás',
'diff-multi'              => '({{plural:$1|Egy közbeeső változat|$1 közbeeső változat}} nincs mutatva)',

# Search results
'searchresults'         => 'A keresés eredménye',
'searchresulttext'      => 'A {{SITENAME}} keresésével kapcsolatos további információ a [[{{MediaWiki:Helppage}}|{{int:súgóban}}]].',
'searchsubtitle'        => 'Erre kerestél: „[[:$1]]”',
'searchsubtitleinvalid' => 'A "$1" kereséshez',
'noexactmatch'          => "Nincs '''$1''' nevű lap. Készíthetsz egy [[:$1|új oldalt]] ezen a néven.",
'noexactmatch-nocreate' => "'''Nem található „$1” nevű lap.'''",
'toomanymatches'        => 'Túl sok találat van, próbálkozz egy másik lekérdezéssel',
'titlematches'          => 'Címszó egyezik',
'notitlematches'        => 'Nincs egyező címszó',
'textmatches'           => 'Szócikk szövege egyezik',
'notextmatches'         => 'Nincs egyező szócikk szöveg',
'prevn'                 => 'előző $1',
'nextn'                 => 'következő $1',
'viewprevnext'          => '($1) ($2) ($3)',
'search-result-size'    => '$1 ({{PLURAL:$2|egy szó|$2 szó}})',
'search-result-score'   => 'Relevancia: $1%',
'search-redirect'       => '(átirányítás ide: $1)',
'search-section'        => '($1. fejezet)',
'search-suggest'        => 'Keresési javaslat: $1',
'searchall'             => 'mind',
'showingresults'        => 'Lent látható <b>$1</b> találat, az eleje <b>$2</b>.',
'showingresultsnum'     => 'Lent látható <b>$3</b> találat, az eleje #<b>$2</b>.',
'showingresultstotal'   => "Találatok: '''$1 - $2''' (összesen '''$3''')",
'nonefound'             => "'''Megjegyzés''': A sikertelen keresések
gyakori oka olyan szavak keresése (pl. \"have\" és \"from\"), amiket a
rendszer nem indexel, vagy több független keresési kifejezés megadása
(csak minden megadott szót tartalmazó találatok jelennek meg az eredményben).",
'powersearch'           => 'Részletes keresés',
'powersearch-legend'    => 'Részletes keresés',
'powersearchtext'       => 'Keresés a névterekben:<br />$1<br />$2 Átirányítások listája &nbsp; Keresés:$3 $9',
'searchdisabled'        => 'Elnézésed kérjük, de a teljes szöveges keresés terhelési okok miatt átmenetileg nem használható. Ezidő alatt használhatod a lenti Google keresést, mely viszont lehetséges, hogy nem teljesen friss adatokkal dolgozik.',

# Preferences page
'preferences'              => 'Beállításaim',
'mypreferences'            => 'beállításaim',
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
'skin'                     => 'Felület',
'math'                     => 'Képletek',
'dateformat'               => 'Dátum formátuma',
'datedefault'              => 'Nincs beállítás',
'datetime'                 => 'Dátum és idő',
'math_failure'             => 'Értelmezés sikertelen',
'math_unknown_error'       => 'ismeretlen hiba',
'math_unknown_function'    => 'ismeretlen függvény',
'math_lexing_error'        => 'lexikai hiba',
'math_syntax_error'        => 'formai hiba',
'math_image_error'         => 'Sikertelen PNG-vé alakítás; ellenőrizd a latex, dvips, gs telepítését',
'math_bad_tmpdir'          => 'Nem írható vagy nem hozható létre a matematikai ideiglenes könyvtár',
'math_bad_output'          => 'Nem írható vagy nem hozható létre a matematikai kimeneti könyvtár',
'math_notexvc'             => 'HIányzó texvc végrehajtható fájl; a beállítást lásd a math/README fájlban.',
'prefs-personal'           => 'Felhasználói adatok',
'prefs-rc'                 => 'Friss változtatások',
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
'stub-threshold'           => 'A <a href="#" class="stub">csonkokra</a> mutató linkek jelzésének határa:',
'recentchangesdays'        => 'Napok száma a friss változtatásokban:',
'recentchangescount'       => 'Címszavak száma a friss változtatásokban:',
'savedprefs'               => 'Az új beállításaid érvénybe léptek.',
'timezonelegend'           => 'Időzóna',
'timezonetext'             => 'Add meg az órák számát, amennyivel a helyi idő a GMT-től eltér (Magyarországon nyáron 2, télen 1).',
'localtime'                => 'Helyi idő:',
'timezoneoffset'           => 'Eltérés:',
'servertime'               => 'A kiszolgáló ideje:',
'guesstimezone'            => 'Töltse ki a böngésző',
'allowemail'               => 'E-mail engedélyezése más felhasználóktól',
'defaultns'                => 'Alapértelmezett keresés az alábbi névterekben:',
'default'                  => 'alapértelmezés',
'files'                    => 'Képek',

# User rights
'userrights'                       => 'Felhasználói jogok kezelése', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => 'Felhasználócsoportok kezelése',
'userrights-user-editname'         => 'Írd be a felhasználónevet:',
'editusergroup'                    => 'Felhasználócsoportok módosítása',
'editinguser'                      => "'''[[User:$1|$1]]''' jogainak megváltoztatása ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'         => 'Felhasználócsoportok módosítása',
'saveusergroups'                   => 'Felhasználócsoportok mentése',
'userrights-groupsmember'          => 'Csoporttag:',
'userrights-groupsremovable'       => 'Eltávolítható csoportok:',
'userrights-groupsavailable'       => 'Létező csoportok:',
'userrights-groupshelp'            => 'Jelöld ki azokat a csoportokat, melyekből el akarod távolítani, vagy melyekhez hozzá akarod adni a felhasználót.
A kijelöletlen csportok változatlanok maradnak. CTRL + bal kattintással tudod egy csoport kijelölését megszüntetni',
'userrights-reason'                => 'A változtatás indoka:',
'userrights-available-none'        => 'A csoporttagságot nem módosíthatod.',
'userrights-available-add'         => 'Adhatsz hozzá felhasználókat a(z) $1 csoporthoz.',
'userrights-available-remove'      => 'Távolíthatsz el felhazsnálókat a(z) $1 csoportból.',
'userrights-available-add-self'    => 'A következő {{PLURAL:$2|csoporthoz|csoportokhoz}} adhatod hozzá magadat: $1.',
'userrights-available-remove-self' => 'A következő {{PLURAL:$2|csoportból|csoportokból}} távolíthatod el magad: $1.',
'userrights-no-interwiki'          => 'Nincs jogod a felhasználók jogainak szerkesztésére más wikiken.',
'userrights-nodatabase'            => '$1 adatbázis nem létezik vagy nem helyi.',
'userrights-nologin'               => '[[Special:Userlogin|Be kell jelentkezned]] egy adminisztrátori fiókkal, hogy felhasználói jogokat adhass.',
'userrights-notallowed'            => 'A fiókoddal nincs jogod felhasználói jogokat osztani.',

# Groups
'group'               => 'Csoport:',
'group-autoconfirmed' => 'automatikusan megerősített felhasználók',
'group-bot'           => 'botok',
'group-sysop'         => 'adminisztrátorok',
'group-bureaucrat'    => 'bürokraták',
'group-all'           => '(mind)',

'group-autoconfirmed-member' => 'automatikusan megerősített felhasználó',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'adminisztrátor',
'group-bureaucrat-member'    => 'bürokrata',

'grouppage-autoconfirmed' => '{{ns:project}}:Automatikusan megerősített felhasználók',
'grouppage-bot'           => '{{ns:project}}:Botok',
'grouppage-sysop'         => '{{ns:project}}:Adminisztrátorok',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraták',

# User rights log
'rightslog'      => 'Felhasználói jogosultságok naplója',
'rightslogtext'  => 'Ez a rendszernapló a felhasználó jogosultságok változásait mutatja.',
'rightslogentry' => 'megváltoztatta $1 szerkesztő felhasználó jogait (régi: $2; új: $3)',
'rightsnone'     => '(semmi)',

# Recent changes
'nchanges'                          => '$1 változtatás',
'recentchanges'                     => 'Friss változtatások',
'recentchangestext'                 => 'A wiki legutóbbi változtatásainak követése ezen a lapon.',
'recentchanges-feed-description'    => 'Kövesd a wiki friss változtatásait ezzel a hírcsatornával.',
'rcnote'                            => 'Alább az utolsó <strong>$2</strong> nap utolsó <strong>$1</strong> változtatása látható. A lap generálásának időpontja $3.',
'rcnotefrom'                        => 'Alább láthatóak a <b>$2</b> óta történt változások (<b>$1</b>-ig).',
'rclistfrom'                        => 'Az új változtatások kijelzése $1 után',
'rcshowhideminor'                   => 'apró módosítások $1',
'rcshowhidebots'                    => 'botok szerkesztéseinek $1',
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
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[Jelenleg $1 felhasználó nézi]',
'rc_categories'                     => 'Korlátozás kategóriákra ("|" jellel elválasztva)',
'rc_categories_any'                 => 'Bármi',
'newsectionsummary'                 => '/* $1 */ (új szakasz)',

# Recent changes linked
'recentchangeslinked'          => 'Kapcsolódó változtatások',
'recentchangeslinked-title'    => 'A(z) $1 lappal kapcsolatos változtatások',
'recentchangeslinked-noresult' => 'Nem történt változtatás a hivatkozott lapokon a megadott időtartam alatt.',
'recentchangeslinked-summary'  => "Ezek azoknak a lapoknak a legutóbbi változtatásai, amik be vannak linkelve erről az oldalról. Amik fenn vannak a figyelőlistádon, azok '''félkövérrel''' szerepelnek.",

# Upload
'upload'                      => 'Fájl feltöltése',
'uploadbtn'                   => 'Fájl felküldése',
'reupload'                    => 'Újra feltöltés',
'reuploaddesc'                => 'Visszatérés a feltöltési űrlaphoz.',
'uploadnologin'               => 'Nem jelentkeztél be',
'uploadnologintext'           => 'Csak regisztrált felhasználók tölthetnek fel fájlokat. [[Special:Userlogin|Jelentkezz be]] vagy regisztrálj!',
'upload_directory_read_only'  => 'A feltöltési könyvtár ($1) a webkiszolgáló által nem írható.',
'uploaderror'                 => 'Feltöltési hiba',
'uploadtext'                  => "Az alábbi űrlap használatával tölthetsz fel fájlokat.
A korábban feltöltött képek megtekintéséhez vagy a köztük való kereséshez menj a [[Special:Imagelist|feltöltött fájlok listájához]], a feltöltések és a törlések a [[Special:Log/upload|feltöltési naplóban]] is le vannak jegyezve.

Képet a következő módon illeszhetsz be egy oldalra: '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Kép.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Kép.png|alternatív szöveg]]</nowiki>''' vagy a közvetlen hivatkozáshoz használd a
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fájl.ogg]]</nowiki>''' formát.",
'upload-permitted'            => 'Megengedett fájltípusok: $1.',
'upload-preferred'            => 'Támogatott fájltípusok: $1.',
'upload-prohibited'           => 'Tiltott fájltípusok: $1.',
'uploadlog'                   => 'feltöltési napló',
'uploadlogpage'               => 'Feltöltési_napló',
'uploadlogpagetext'           => 'Lentebb látható a legutóbbi felküldések listája.',
'filename'                    => 'Fájlnév',
'filedesc'                    => 'Összegzés',
'fileuploadsummary'           => 'Összefoglaló:',
'filestatus'                  => 'Szerzői jogi állapot:',
'filesource'                  => 'Forrás:',
'uploadedfiles'               => 'Feltöltött fájlok',
'ignorewarning'               => 'Biztosan így akarom feltölteni',
'ignorewarnings'              => 'Hagyd figyelmen kívül a figyelmeztetéseket',
'minlength1'                  => 'A fájlnévnek legalább egy betűből kell állnia.',
'illegalfilename'             => 'A „$1” lap neve olyan karaktereket tartalmaz, melyek nincsenek megengedve lapcímben. Kérlek, változtasd meg a nevet, és próbálkozz a mentéssel újra.',
'badfilename'                 => 'A kép új neve „$1”.',
'filetype-badmime'            => '„$1” MIME-típusú fájlokat nem lehet feltölteni.',
'filetype-unwanted-type'      => "A(z) '''„.$1”''' nem javasolt fájltípus. Az ajánlott típusok: $2.",
'filetype-banned-type'        => "A(z) '''„.$1”''' nem megengedett fájltípus.  A megengedett típusok: $2.",
'filetype-missing'            => 'A fájlnak nincs kiterjesztése (pl. „.jpg”).',
'large-file'                  => 'Javasoljuk, hogy a dájl ne legyen nagyobb, mint $1; ennek a fájlnak a mérete $2.',
'largefileserver'             => 'A fájl mérete meghaladja a kiszolgálón beállított maximális értéket.',
'emptyfile'                   => 'Az általad feltöltött fájl üresnek tűnik. Ez a fájlnévben lévő hibás karakter miatt lehet. Ellenőrizd, hogy valóban fel akarod-e tölteni ezt a fájlt.',
'fileexists'                  => '<strong><tt>$1</tt></strong> névvel már létezik egy állomány. Ellenőrizd, hogy biztosan felül akarod-e írni!',
'filepageexists'              => 'Ehhez a fájlnévhez már létezik leírás a <strong><tt>$1</tt></strong> lapon, de jelenleg nincs feltöltve ilyen nevű fájl. A leírás, amit ebbe az űrlapba írsz, nem fogja felülírni a már létezőt, és sehol nem fog megjelenni. Ha meg akarod változtatni a leírást, meg kell nyitnod szerkesztésre a lapját.',
'fileexists-extension'        => 'Van hasonló nevű fájl:<br />
A feltöltendő fájl neve: <strong><tt>$1</tt></strong><br />
A létező fájl neve: <strong><tt>$2</tt></strong><br />
Kérjük, hogy válassz másik nevet.',
'fileexists-thumb'            => "<center>Ilyen nevű fájl már van</center>'''",
'fileexists-thumbnail-yes'    => 'A fájl egy csökkentett méretű képnek <i>(bélyegképnek)</i> tűnik. Kérjük, hogy ellenőrizd a(z) <strong><tt>$1</tt></strong> fájlt.<br />
Ha az ellenőrzött fájl ugyanakkora, mint az eredeti méretű kép, akkor a bélyegképet nem kell külön feltöltened.',
'file-thumbnail-no'           => 'A fájlnév a(z) <strong><tt>$1</tt></strong> karakterlánccal kezdődik. Úgy tűnik, hogy ez egy csökkentett méretű kép <i>(bélyegkép)</i>.
Ha megvan neked a teljes felbontású kép, akkor töltsd fel azt, egyéb esetben kérjük, hogy változtasd meg a fájlnevet.',
'fileexists-forbidden'        => 'Egy ugyanilyen nevű fájl már létezik; kérlek menj vissza és töltsd fel a fájlt egy másik néven. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Egy ugyanilyen nevű fájl már létezik a közös fájlmegosztóban; kérlek menj vissza és válassz egy másik nevet a fájlnak. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'A feltöltés sikerült',
'uploadwarning'               => 'Feltöltési figyelmeztetés',
'savefile'                    => 'Fájl mentése',
'uploadedimage'               => '„[[$1]]” felküldve',
'overwroteimage'              => 'feltöltötte a(z) „[[$1]]” kép új változatát',
'uploaddisabled'              => 'Feltöltések kikapcsolva',
'uploaddisabledtext'          => 'A fájlfeltöltés ebben a wikiben letiltott.',
'uploadscripted'              => 'Ez a fájl olyan HTML- vagy parancsfájlkódot tartalmaz, melyet tévedésből egy webböngésző esetleg értelmezni próbálhatna.',
'uploadcorrupt'               => 'A fájl sérült vagy hibás a kiterjesztése. Légy szíves ellenőrizd a fájlt és próbálkozz újra!',
'uploadvirus'                 => 'Ez a fájl vírust tartalmaz! A részletek: $1',
'sourcefilename'              => 'Forrásfájl neve:',
'destfilename'                => 'Célmédiafájl neve:',
'upload-maxfilesize'          => 'Maximális fájlméret: $1',
'watchthisupload'             => 'Figyeld ezt a lapot',
'filewasdeleted'              => 'Korábban valaki már feltöltött ilyen néven egy fájlt, amelyet később töröltünk. Ellenőrizd a $1 bejegyzését, nehogy újra feltöltsd ugyanezt a fájlt.',
'upload-wasdeleted'           => "'''Vigyázat: egy olyan fájlt akarsz feltölteni, ami korábban már törölve lett.'''

Mielőtt ismét feltöltenéd, nézd meg, miért lett korábban törölve, és ellenőrizd, hogy a törlés indoka nem érvényes-e még. A törlési naplóban a lapról az alábbi bejegyzések szerepelnek:",
'filename-bad-prefix'         => 'Annak a fájlnak a neve, amelyet fel akarsz tölteni <strong>„$1”</strong> karakterekkel kezdődik. Ilyeneket általában a digitális kamerák adnak a fájloknak, automatikusan, azonban ezek nem írják le annak tartalmát. Válassz egy leíró nevet!',
'filename-prefix-blacklist'   => ' #<!-- ezt a sort hagyd így --> <pre>
#A szintaktika a következő:
#   * Minden a „#” karaktertől a sor végéig megjegyzésnek számít
#   * Minden nemüres sor egy, a digitális fényképezőképek által fájlok neveként használt előtag
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # néhány mobiltelefon
IMG # általános
JD # Jenoptik
MGP # Pentax
PICT # ált.
 #</pre> <!-- ezt a sort hagyd így -->',

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

'license'            => 'Licenc:',
'nolicense'          => 'Válassz licencet!',
'license-nopreview'  => '(Előnézet nem elérhető)',
'upload_source_url'  => ' (egy érvényes, nyilvánosan elérhető URL)',
'upload_source_file' => ' (egy fájl a számítógépeden)',

# Special:Imagelist
'imagelist-summary'     => 'Ezen a speciális lapon látható az összes feltöltött fájl.
A legutóbb feltöltött fájlok vannak a lista elején.
Az oszlopok címeire kattintva változtathatod meg a rendezést.',
'imagelist_search_for'  => 'Keresés kép nevére:',
'imgdesc'               => 'leírás',
'imgfile'               => 'fájl',
'imagelist'             => 'Képlista',
'imagelist_date'        => 'Dátum',
'imagelist_name'        => 'Név',
'imagelist_user'        => 'felöltő',
'imagelist_size'        => 'Méret',
'imagelist_description' => 'Leírás',

# Image description page
'filehist'                  => 'Fájltörténet',
'filehist-help'             => 'Kattints egy időpontra, hogy a fájl akkori állapotát láthasd.',
'filehist-deleteall'        => 'összes törlése',
'filehist-deleteone'        => 'ennek a törlése',
'filehist-revert'           => 'visszaállít',
'filehist-current'          => 'jelenlegi',
'filehist-datetime'         => 'Dátum/idő',
'filehist-user'             => 'Feltöltő',
'filehist-dimensions'       => 'Felbontás',
'filehist-filesize'         => 'Fájlméret',
'filehist-comment'          => 'Megjegyzés',
'imagelinks'                => 'Képhivatkozások',
'linkstoimage'              => 'Az alábbi lapok hivatkoznak erre a képre:',
'nolinkstoimage'            => 'Erre a képre nem hivatkozik lap.',
'sharedupload'              => 'Ez a fájlt egy megosztott feltöltés, és más projektek használhatják.',
'shareduploadwiki'          => 'Lásd a [$1 fájl leírólapját] a további információkért.',
'shareduploadwiki-desc'     => 'A $1 található leírás alább látható.',
'shareduploadwiki-linktext' => 'fájl leírólapját',
'noimage'                   => 'Ezen a néven nem létezik médiafájl. Ha szeretnél, $1 egyet.',
'noimage-linktext'          => 'feltölthetsz',
'uploadnewversion-linktext' => 'A fájl újabb változatának felküldése',
'imagepage-searchdupe'      => 'Duplikátumok keresése',

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
'filedelete'                  => '$1 törlése',
'filedelete-legend'           => 'Fájl törlése',
'filedelete-intro'            => "Törölni készülsz a következő médiafájlt: '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl, dátum: [$4 $3, $2] változatát törlöd.</span>',
'filedelete-comment'          => 'Indoklás:',
'filedelete-submit'           => 'Törlés',
'filedelete-success'          => "A(z) '''$1''' médiafájlt törölted.",
'filedelete-success-old'      => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl, dátum: $3, $2 törlése sikerült.</span>',
'filedelete-nofile'           => "'''$1''' nevű fájl nem létezik ezen a wikin.",
'filedelete-nofile-old'       => "There is no archived version of A(z) '''$1''' fájlnak nincs a megadott attribútumú archivált változata.",
'filedelete-iscurrent'        => 'A fájl legutóbbi változatát kísérled meg törölni. Kérjük, hogy előbb állíts vissza egy régebbi verziót.',
'filedelete-otherreason'      => 'Más/további ok:',
'filedelete-reason-otherlist' => 'Más ok',
'filedelete-reason-dropdown'  => '*Általános törlési okok
** Szerzői jog megsértése
** Duplikátum',
'filedelete-edit-reasonlist'  => 'Törlési okok szerkesztése',

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
'unusedtemplateswlh'  => 'más hivatkozások',

# Random page
'randompage'         => 'Lap találomra',
'randompage-nopages' => 'Ebben a névtérben nincsenek lapok.',

# Random redirect
'randomredirect'         => 'Átirányítás találomra',
'randomredirect-nopages' => 'Ebben a névtérben nincsenek átirányítások.',

# Statistics
'statistics'             => 'Statisztikák',
'sitestats'              => 'Tartalmi statisztikák',
'userstats'              => 'Felhasználói statisztikák',
'sitestatstext'          => "A wikiben jelenleg '''$2''' szócikk található.
Ebben nincsenek benne a vitalapok, az átirányítások,
a közösségi lapok, a csonkok és más olyan lapok, amik nem számítanak igazi szócikkeknek.
Ezeket is beleszámítva '''$1''' lapunk van.

Összesen '''$8''' fájlt töltöttek fel.

Összesen '''$3''' alkalommal tekintették meg az oldalakat, és '''$4''' szerkesztés történt a {{SITENAME}} indulása óta, ami oldalanként '''$5''' szerkesztésnek és '''$6''' megtekintésnek számít.

A [http://meta.wikimedia.org/wiki/Help:Job_queue szerver számára sorban álló feladatok] száma '''$7'''.",
'userstatstext'          => 'Jelenleg <b>$1</b> regisztrált felhasználónk van; közülük <b>$2</b> ($4%) $5.',
'statistics-mostpopular' => 'Legtöbbször megtekintett lapok',

'disambiguations'      => 'Egyértelműsítő lapok',
'disambiguationspage'  => 'Template:Egyért',
'disambiguations-text' => "A következő oldalak '''egyértelműsítő lapra''' mutató hivatkozást tartalmaznak. A megfelelő szócikkre kellene mutatniuk inkább. <br /> Egy oldal egyértelműsítő lapnak számít, ha tartalmazza a [[MediaWiki:disambiguationspage]] oldalról belinkelt sablonok valamelyikét.",

'doubleredirects'     => 'Dupla átirányítások',
'doubleredirectstext' => '<strong>Figyelem:</strong> Ez a lista nem feltétlenül pontos. Ennek általában az oka az, hogy a #REDIRECT alatt további szöveg található.<br /> Minden sor tartalmazza az első és a második átirányítást, valamint a második átirányítás cikkének első sorát, ami általában a „valódi” célt tartalmazza, amire az elsőnek mutatnia kellene.',

'brokenredirects'        => 'Nem létező lapra mutató átirányítások',
'brokenredirectstext'    => 'Az alábbi átirányítások nem létező lapokra mutatnak.',
'brokenredirects-edit'   => '(szerkesztés)',
'brokenredirects-delete' => '(törlés)',

'withoutinterwiki'        => 'Interwikilink nélküli lapok',
'withoutinterwiki-header' => 'A következő lapok nem hivatkoznak más nyelvű változatokra:',
'withoutinterwiki-submit' => 'Megjelenítés',

'fewestrevisions' => 'Legrövidebb laptörténetű lapok',

# Miscellaneous special pages
'nbytes'                  => '$1 bájt',
'ncategories'             => '$1 kategória',
'nlinks'                  => '$1 hivatkozás',
'nmembers'                => '$1 elem',
'nrevisions'              => '$1 változat',
'nviews'                  => '$1 megtekintés',
'specialpage-empty'       => 'Ez az oldal üres.',
'lonelypages'             => 'Magányos lapok',
'lonelypagestext'         => 'A következő lapokra nem mutat belső link.',
'uncategorizedpages'      => 'Kategorizálatlan lapok',
'uncategorizedcategories' => 'Kategorizálatlan kategóriák',
'uncategorizedimages'     => 'Kategorizálatlan képek',
'uncategorizedtemplates'  => 'Kategorizálatlan sablonok',
'unusedcategories'        => 'Nem használt kategóriák',
'unusedimages'            => 'Nem használt képek',
'popularpages'            => 'Népszerű lapok',
'wantedcategories'        => 'Keresett kategóriák',
'wantedpages'             => 'Keresett lapok',
'mostlinked'              => 'Legtöbbet hivatkozott lapok',
'mostlinkedcategories'    => 'Legtöbbet hivatkozott kategóriák',
'mostlinkedtemplates'     => 'Legtöbbet szerkesztett lapok',
'mostcategories'          => 'Legtöbb kategóriába tartozó lapok',
'mostimages'              => 'Legtöbbet hivatkozott fájlok',
'mostrevisions'           => 'Legtöbb változattal rendelkező szócikkek',
'prefixindex'             => 'Keresés előtag szerint',
'shortpages'              => 'Rövid lapok',
'longpages'               => 'Hosszú lapok',
'deadendpages'            => 'Zsákutcalapok',
'deadendpagestext'        => 'Az itt található lapok nem kapcsolódnak hivatkozásokkal ezen wiki más oldalaihoz.',
'protectedpages'          => 'Védett lapok',
'protectedpagestext'      => 'A következő lapok átnevezés vagy szerkesztés ellen védettek',
'protectedpagesempty'     => 'Jelenleg nincsenek ilyen paraméterekkel védett lapok.',
'protectedtitles'         => 'Létrehozás ellen védett lapok',
'protectedtitlestext'     => 'A következő lapok védve vannak a létrehozás ellen',
'protectedtitlesempty'    => 'Jelenleg nincsenek ilyen típusú védett lapok.',
'listusers'               => 'Felhasználók',
'specialpages'            => 'Speciális lapok',
'spheading'               => 'Speciális lapok',
'restrictedpheading'      => 'Korlátozott hozzáférésű speciális lapok',
'newpages'                => 'Új lapok',
'newpages-username'       => 'Felhasználói név:',
'ancientpages'            => 'Régóta nem változott szócikkek',
'move'                    => 'Átnevezés',
'movethispage'            => 'Nevezd át ezt a lapot',
'unusedimagestext'        => '<p>Vedd figyelembe, hogy más lapok - például a nemzetközi {{grammar:k|{{SITENAME}}}} - közvetlenül
hivatkozhatnak egy fájl URL-jére, ezért szerepelhet itt annak
ellenére, hogy aktívan használják.</p>',
'unusedcategoriestext'    => 'A következő kategóriákban egyetlen szócikk, illetve alkategória sem szerepel.',
'notargettitle'           => 'Nincs cél',
'notargettext'            => 'Nem adtál meg lapot vagy usert keresési célpontnak.',
'pager-newer-n'           => '{{PLURAL:$1|1 újabb|$1 újabb}}',
'pager-older-n'           => '{{PLURAL:$1|1 régebbi|$1 régebbi}}',

# Book sources
'booksources'               => 'Könyvforrások',
'booksources-search-legend' => 'Könyvforrások keresése',
'booksources-go'            => 'Keresés',
'booksources-text'          => 'Alább látható a másik webhelyekre mutató hivatkozások listája, ahol új és használt könyveket árulnak, és
további információkat lelhetsz ott az általad keresett könyvekről:',

# Special:Log
'specialloguserlabel'  => 'Felhasználó:',
'speciallogtitlelabel' => 'Cím:',
'log'                  => 'Rendszernaplók',
'all-logs-page'        => 'Rendszernaplók',
'log-search-legend'    => 'Naplók keresése',
'log-search-submit'    => 'Menj',
'alllogstext'          => 'Az átnevezési, feltöltési, törlési, lapvédelmi, blokkolási, bürokrata és felhasználó-átnevezési naplók közös listája.
Szűkítheted a listát a naplótípus, a műveletet végző felhasználó vagy az érintett oldal megadásával.',
'logempty'             => 'Nincs illeszkedő naplóbejegyzés.',
'log-title-wildcard'   => 'Így kezdődő címek keresése',

# Special:Allpages
'allpages'          => 'Az összes lap listája',
'alphaindexline'    => '$1 – $2',
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
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'Nincs e-mail cím',
'noemailtext'     => 'Ez a felhasználó nem adott meg e-mail címet, vagy
nem kíván másoktól leveleket kapni.',
'emailfrom'       => 'Feladó',
'emailto'         => 'Címzett',
'emailsubject'    => 'Téma',
'emailmessage'    => 'Üzenet',
'emailsend'       => 'Küldés',
'emailccme'       => 'Az üzenet másolatát küldje el nekem is e-mailben.',
'emailccsubject'  => '$1 szerkesztőnek küldött $2 tárgyú üzenet másolata',
'emailsent'       => 'E-mail elküldve',
'emailsenttext'   => 'Az e-mail üzenetedet elküldtem.',

# Watchlist
'watchlist'            => 'Figyelőlistám',
'mywatchlist'          => 'figyelőlistám',
'watchlistfor'         => "('''$1''' részére)",
'nowatchlist'          => 'Nincs lap a figyelőlistádon.',
'watchlistanontext'    => 'A figyelőlistád megtekintéséhez és szerkesztéséhez $1.',
'watchnologin'         => 'Nem vagy bejelentkezve',
'watchnologintext'     => 'Ahhoz, hogy figyelőlistád lehessen, [[Special:Userlogin|be kell lépned]].',
'addedwatch'           => 'Figyelőlistához hozzáfűzve',
'addedwatchtext'       => "A(z) „[[:$1]]” lapot hozzáadtam a [[Special:Watchlist|figyelőlistádhoz]].
Ezután minden, a lapon vagy annak vitalapján történő változást ott fogsz látni, és a lap '''vastagon''' fog szerepelni a [[Special:Recentchanges|friss változtatások]] lapon, hogy könnyen észrevehető legyen.",
'removedwatch'         => 'Figyelőlistáról eltávolítva',
'removedwatchtext'     => 'A „<nowiki>$1</nowiki>” lapot eltávolítottam a figyelőlistáról.',
'watch'                => 'Lap figyelése',
'watchthispage'        => 'Lap figyelése',
'unwatch'              => 'Lapfigyelés vége',
'unwatchthispage'      => 'Figyelés leállítása',
'notanarticle'         => 'Nem szócikk',
'notvisiblerev'        => 'A változat törölve lett',
'watchnochange'        => 'Egyik figyelt lap sem változott a megadott időintervallumon belül.',
'watchlist-details'    => '<strong>$1</strong> lap van a figyelőlistádon (a vitalapokon kívül).',
'wlheader-enotif'      => '* Email értesítés engedélyezve.',
'wlheader-showupdated' => "* Azok a lapok, amelyek megváltoztak, mióta utoljára megnézted őket, '''vastagon''' láthatóak.",
'watchmethod-recent'   => 'a figyelt lapokon belüli legfrissebb szerkesztések',
'watchmethod-list'     => 'a legfrissebb szerkesztésekben található figyelt lapok',
'watchlistcontains'    => 'A figyelőlistádon $1 lap szerepel.',
'iteminvalidname'      => "Probléma a '$1' elemmel: érvénytelen név...",
'wlnote'               => 'Az utolsó <b>$2</b> óra $1 változtatása látható az alábbiakban.',
'wlshowlast'           => 'Az elmúlt $1 órában | $2 napon | $3 történt változtatások legyenek láthatóak',
'watchlist-show-bots'  => 'Botok szerkesztéseinek megjelenítése',
'watchlist-hide-bots'  => 'Botok szerkesztéseinek elrejtése',
'watchlist-show-own'   => 'Saját szerkesztések megjelenítése',
'watchlist-hide-own'   => 'Saját szerkesztések elrejtése',
'watchlist-show-minor' => 'Apró módosítások megjelenítése',
'watchlist-hide-minor' => 'Apró módosítások elrejtése',

# Displayed when you click the "watch" button and it is in the process of watching
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
'excontent'                   => 'a lap tartalma: „$1”',
'excontentauthor'             => 'a lap tartalma: „$1” (és csak „$2” szerkesztette)',
'exbeforeblank'               => 'az eltávolítás előtti tartalom: $1',
'exblank'                     => 'a lap üres volt',
'delete-confirm'              => '$1 törlése',
'delete-legend'               => 'Törlés',
'historywarning'              => 'Figyelem: a lapnak, amit törölni készülsz, története van:',
'confirmdeletetext'           => 'Egy lap vagy kép teljes laptörténetével együtti
végleges törlésére készülsz.
Kérjük, erősítsd meg, hogy valóban ezt szándékozod tenni,
átlátod a következményeit, és a [[{{MediaWiki:Policy-url}}|törlési irányelvekkel]]
összhangban cselekedsz.',
'actioncomplete'              => 'Művelet végrehajtva',
'deletedtext'                 => 'A(z) „<nowiki>$1</nowiki>” lapot törölted.
A legutóbbi törlések listájához lásd a $2 lapot.',
'deletedarticle'              => '„$1” törölve',
'dellogpage'                  => 'Törlési_napló',
'dellogpagetext'              => 'Itt láthatók a legutóbb törölt lapok.',
'deletionlog'                 => 'törlési napló',
'reverted'                    => 'Visszaállítva a korábbi változatra',
'deletecomment'               => 'A törlés oka',
'deleteotherreason'           => 'Az FV-ben megjelenő indoklás:',
'deletereasonotherlist'       => 'Egyéb indok',
'deletereason-dropdown'       => '*Gyakori törlési okok
** Szerző kérésére
** Jogsértő
** Vandalizmus',
'delete-edit-reasonlist'      => 'Törlési okok szerkesztése',
'delete-toobig'               => 'Ennek a lapnak nagy laptörténete van, $1 változattal. Az ilyen lapok törlése korlátozott a wiki rendjének megőrzése végett.',
'delete-warning-toobig'       => 'Ennek a lapnak nagy laptörténete van, $1 változattal. Törlése fennakadásokat okozhat a wiki adatbázisműveleteiben; óvatosan járj el.',
'rollback'                    => 'Szerkesztések visszaállítása',
'rollback_short'              => 'Visszaállítás',
'rollbacklink'                => 'visszaállítás',
'rollbackfailed'              => 'A visszaállítás nem sikerült',
'cantrollback'                => 'Nem lehet visszaállítani: az utolsó szerkesztést végző felhasználó az egyetlen, aki a lapot szerkesztette.',
'alreadyrolled'               => '[[:$1]] utolsó, [[User:$2|$2]] ([[User talk:$2|Vita]] | [[Special:Contributions/$2|Szerkesztései]] | [[Special:blockip/$2|Blokkolás]]) általi szerkesztését nem lehet visszavonni: időközben valaki már visszavonta, vagy szerkesztette a lapot.

Az utolsó szerkesztést [[User:$3|$3]] ([[User talk:$3|vita]]) végezte.',
'editcomment'                 => 'A változtatás összefoglalója "<i>$1</i>" volt.', # only shown if there is an edit comment
'revertpage'                  => 'Visszaállítottam a lap korábbi változatát: [[Special:Contributions/$2|$2]] szerkesztéséről [[User:$1|$1]] szerkesztésére', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => '$1 szerkesztéseit visszaállítottam $2 utolsó változatára.',
'sessionfailure'              => 'Úgy látszik, hogy probléma van a bejelentkezési munkameneteddel;
ez a művelet a munkamenet eltérítése miatti óvatosságból megszakadt.
Kérjük, hogy nyomd meg a "vissza" gombot, és töltsd le újra az oldalt, ahonnan jöttél, majd próbáld újra.',
'protectlogpage'              => 'Lapvédelmi_napló',
'protectlogtext'              => 'Ez a lapok lezárásának és megnyitásának listája. A [[Special:Protectedpages|védett lapok listáján]] megtekintheted a jelenleg is érvényben lévő védelmeket.',
'protectedarticle'            => 'levédte a(z) [[$1]] lapot',
'modifiedarticleprotection'   => 'a védelmi szint a következőre változott: "[[$1]]"',
'unprotectedarticle'          => 'eltávolította a védelmet a(z) „[[$1]]” lapról',
'protect-title'               => '„$1” levédése',
'protect-legend'              => 'Levédés megerősítése',
'protectcomment'              => 'A védelem oka',
'protectexpiry'               => 'Időtartam',
'protect_expiry_invalid'      => 'A lejárati idő érvénytelen.',
'protect_expiry_old'          => 'A lejárati idő a múltban van.',
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
'protect-level-sysop'         => 'Csak adminisztrátorok',
'protect-summary-cascade'     => 'részben fedő',
'protect-expiring'            => 'lejár: $1 (UTC)',
'protect-cascade'             => 'Kaszkád védelem – védjen le minden lapot, amit ez a lap tartalmaz.',
'protect-cantedit'            => 'Nem változtathatod meg a lap védelmi szintjét, mert nincs jogod a szerkesztéséhez.',
'restriction-type'            => 'Engedély:',
'restriction-level'           => 'Korlátozási szint:',
'minimum-size'                => 'Legkisebb méret',
'maximum-size'                => 'Legnagyobb méret',
'pagesize'                    => '(bájt)',

# Restrictions (nouns)
'restriction-edit'   => 'Szerkesztés',
'restriction-move'   => 'Átmozgatás',
'restriction-create' => 'Létrehozás',

# Restriction levels
'restriction-level-sysop'         => 'teljesen védett',
'restriction-level-autoconfirmed' => 'félig védett',
'restriction-level-all'           => 'bármilyen szint',

# Undelete
'undelete'                     => 'Törölt lap helyreállítása',
'undeletepage'                 => 'Törölt lapok megtekintése és helyreállítása',
'undeletepagetitle'            => "'''A(z) [[:$1]] lap törölt változatai alább láthatók.'''",
'viewdeletedpage'              => 'Törölt lapok megtekintése',
'undeletepagetext'             => 'Az alábbi lapokat törölték, de még helyreállíthatók az archívumból
(az archívumot időről időre üríthetik!).',
'undeleteextrahelp'            => "A lap teljes helyreállításához ne jelölj be egy jelölőnégyzetet sem, csak nyomj a '''''Helyreállítás''''' gombra. A lap részleges helyreállításához jelöld be a kívánt szerkesztések melletti jelölőnégyzeteket, és nyomj a '''''Helyreállítás''''' gombra. Ha megnyomod a '''''Vissza''''' gombot, az törli a jelölőnégyzetek és az összefoglaló jelenlegi tartalmát.",
'undeleterevisions'            => '$1 változat archiválva',
'undeletehistory'              => 'Ha helyreállítasz egy lapot, azzal visszahozod laptörténet összes változatát.
Ha lap törlése óta azonos néven már létrehoztak egy újabb lapot, a helyreállított
változatok a laptörténet elejére kerülnek be, a jelenlegi lapváltozat módosítása nélkül.',
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
'undeletebtn'                  => 'Helyreállítás',
'undeletelink'                 => 'helyreállít',
'undeletereset'                => 'Vissza',
'undeletecomment'              => 'Helyreállítás oka:',
'undeletedarticle'             => '„[[$1]]” helyreállítva',
'undeletedrevisions'           => '$1 változat helyreállítva',
'undeletedrevisions-files'     => '$1 változat és $2 fájl visszaállítása kész',
'undeletedfiles'               => '$1 fájl visszaállítása kész',
'cannotundelete'               => 'Nem lehet a lapot visszaállítani; lehet, hogy azt már valaki visszaállította.',
'undeletedpage'                => "<big>'''$1 helyreállítva'''</big>

Lásd a [[Special:Log/delete|törlési naplót]] a legutóbbi törlések és helyreállítások listájához.",
'undelete-header'              => 'A legutoljára törölt lapokat lásd a [[Special:Log/delete|törlési naplóban]].',
'undelete-search-box'          => 'Törölt lapok keresése',
'undelete-search-prefix'       => 'A megadott szavakkal kezdődő oldalak megjelenítése:',
'undelete-search-submit'       => 'Keresés',
'undelete-no-results'          => 'Nem található a keresési feltételeknek megfelelő oldal a törlési naplóban.',
'undelete-filename-mismatch'   => 'Nem állítható helyre a(z) $1 időbélyeggel ellátott változat: a fájlnév nem egyezik meg',
'undelete-bad-store-key'       => 'Nem állítható helyre a(z) $1 időbélyeggel ellátott változat: a fájl már hiányzott törlés előtt.',
'undelete-cleanup-error'       => 'Hiba történt a nem használt „$1” archivált fájl törlésekor.',
'undelete-missing-filearchive' => 'Nem állítható helyre a(z) $1 azonosítószámú fájlarchívum, mert nincs az adatbázisban. Lehet, hogy már korábban helyreállították.',
'undelete-error-short'         => 'Hiba történt a fájl helyreállítása során: $1',
'undelete-error-long'          => 'Hiba történt a fájl helyreállítása során:

$1',

# Namespace form on various pages
'namespace'      => 'Névtér:',
'invert'         => 'Kijelölés megfordítása',
'blanknamespace' => '(Fő)',

# Contributions
'contributions' => 'Szerkesztő közreműködései',
'mycontris'     => 'Közreműködéseim',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'Nem található a feltételeknek megfelelő változtatás.',
'ucnote'        => 'Alább <b>$1</b> módosításai láthatóak az elmúlt <b>$2</b> napban.',
'uclinks'       => 'Az utolsó $1 változtatás megtekintése; az utolsó $2 nap megtekintése.',
'uctop'         => ' (utolsó)',
'month'         => 'E hónap végéig:',
'year'          => 'Eddig az évig:',

'sp-contributions-newbies'     => 'Csak a nemrég regisztrált szerkesztők közreműködéseinek mutatása',
'sp-contributions-newbies-sub' => 'Új szerkesztők lapjai',
'sp-contributions-blocklog'    => 'Blokkolási napló',
'sp-contributions-search'      => 'Közreműködések szűrése',
'sp-contributions-username'    => 'IP-cím vagy felhasználónév:',
'sp-contributions-submit'      => 'Szűrés',

# What links here
'whatlinkshere'       => 'Mi hivatkozik erre',
'whatlinkshere-title' => 'A(z) $1 lapra hivatkozó lapok',
'whatlinkshere-page'  => 'Oldal:',
'linklistsub'         => '(Hivatkozások )',
'linkshere'           => 'Az alábbi lapok hivatkoznak erre: [[:$1]]',
'nolinkshere'         => '[[:$1]]: erre a lapra semmi nem hivatkozik.',
'nolinkshere-ns'      => "A kiválasztott nvtartományban egy lap sem hivatkozik a(z) '''[[:$1]]''' szócikkre.",
'isredirect'          => 'átirányítás',
'istemplate'          => 'beillesztve',
'whatlinkshere-prev'  => '{{PLURAL:$1|előző|előző $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|következő|következő $1}}',
'whatlinkshere-links' => '← erre mutató hivatkozások',

# Block/unblock
'blockip'                     => 'Blokkolás',
'blockip-legend'              => 'Felhasználó blokkolása',
'blockiptext'                 => 'Az alábbi űrlap segítségével megvonhatod egy szerkesztő vagy IP-cím szerkesztési jogait. Ügyelj rá, hogy az intézkedésed mindig legyen tekintettel a vonatkozó irányelvekre. Add meg a blokkolás okát is (például idézd a blokkolandó személy által vandalizált lapokat), és linkeld be a vonatkozó irányelveket, hogy a blokk elszenvedője tudjon tájékozódni.',
'ipaddress'                   => 'IP-cím',
'ipadressorusername'          => 'IP-cím vagy felhasználói név',
'ipbexpiry'                   => 'Lejárat',
'ipbreason'                   => 'Indok:',
'ipbreasonotherlist'          => 'Más ok',
'ipbreason-dropdown'          => '*Gyakori blokkolási okok
** Téves információ beírása
** Lapok tartalmának eltávolítása
** Spammelgetés, reklámlinkek tömködése a lapokba
** Értelmetlen megjegyzések, halandzsa beillesztése a cikkekbe
** Megfélemlítő viselkedés, zaklatás
** Több szerkesztői fiókkal való visszaélés
** Elfogadhatatlan azonosító',
'ipbanononly'                 => 'Csak anonim felhasználók blokkolása',
'ipbcreateaccount'            => 'Új regisztráció megakadályozása',
'ipbemailban'                 => 'E-mailt se tudjon küldeni',
'ipbenableautoblock'          => 'A szerkesztő által használt IP-címek automatikus blokkolása',
'ipbsubmit'                   => 'Blokkolás',
'ipbother'                    => 'Más időtartam',
'ipboptions'                  => '2 óra:2 hours,1 nap:1 day,3 nap:3 days,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'Más időtartam',
'ipbotherreason'              => 'Más/további ok:',
'ipbhidename'                 => 'A felhasználónév/IP elrejtése a blokkolási naplóból, az aktív blokkolási listából és a felhasználólistából',
'badipaddress'                => 'Érvénytelen IP-cím',
'blockipsuccesssub'           => 'Sikeres blokkolás',
'blockipsuccesstext'          => '„[[Special:Contributions/$1|$1]]” felhasználót blokkoltad.
<br />Lásd a [[Special:Ipblocklist|blokkolt IP-címek listáját]] az érvényben lévő blokkok áttekintéséhez.',
'ipb-edit-dropdown'           => 'Blokkolási okok szerkesztése',
'ipb-unblock-addr'            => '$1 blokkjának feloldása',
'ipb-unblock'                 => 'Felhasználónév vagy IP-cím blokkolásának feloldása',
'ipb-blocklist-addr'          => '$1 aktív blokkjainak megtekintése',
'ipb-blocklist'               => 'Létező blokkok megtekintése',
'unblockip'                   => 'Blokk feloldása',
'unblockiptext'               => 'Itt tudod visszaadni egy blokkolt felhasználónévnek vagy IP-nek a szerkesztési jogosultságot.',
'ipusubmit'                   => 'Blokk feloldása',
'unblocked'                   => '[[User:$1|$1]] blokkolása feloldva',
'unblocked-id'                => '$1 blokkolása feloldásra került',
'ipblocklist'                 => 'Blokkolt IP-címek listája',
'ipblocklist-legend'          => 'Blokkolt felhasználó keresése',
'ipblocklist-username'        => 'Felhasználónév vagy IP-cím:',
'ipblocklist-summary'         => 'Lásd még a [[Special:log/block|blokkolási naplót]].',
'ipblocklist-submit'          => 'Keresés',
'blocklistline'               => '$1, $2 blokkolta $3 felhasználót (lejárat: $4)',
'infiniteblock'               => 'végtelen',
'expiringblock'               => 'lejárat: $1',
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
'blocklogentry'               => '„$1” blokkolva $2 $3 időtartamra',
'blocklogtext'                => 'Ez a felhasználókra helyezett blokkoknak és azok feloldásának listája. Az IP-autoblokkok nem szerepelnek a listában. Lásd még [[Special:Ipblocklist|a jelenleg életben lévő blokkok listáját]].',
'unblocklogentry'             => '"$1" blokkolása feloldva',
'block-log-flags-anononly'    => 'csak névtelen felhasználók',
'block-log-flags-nocreate'    => 'a fióklétrehozás letiltott',
'block-log-flags-noautoblock' => 'az automatikus blokkolás letiltott',
'block-log-flags-noemail'     => 'e-mail blokkolva',
'range_block_disabled'        => 'A rendszerfelelős tartományblokkolás létrehozási képessége letiltott.',
'ipb_expiry_invalid'          => 'Hibás lejárati dátum.',
'ipb_already_blocked'         => '"$1" már blokkolva',
'ipb_cant_unblock'            => 'Hiba: A(z) $1 blokkoéási azonosító nem található. Lehet, hogy már feloldották a blokkolását.',
'ipb_blocked_as_range'        => 'Hiba: a(z) $1 IP-cím nem blokkolható közvetlenül, és nem lehet feloldani. A(z) $2 tartomány részeként van blokkolva, amely feloldható.',
'ip_range_invalid'            => 'Érvénytelen IP-tartomány.',
'blockme'                     => 'Saját magam blokkolása',
'proxyblocker'                => 'Proxyblokkoló',
'proxyblocker-disabled'       => 'Ez a funkció le van tiltva.',
'proxyblockreason'            => "Az IP-címeden ''nyílt proxy'' üzemel. Amennyiben nem használsz proxyt, vedd fel a kapcsolatot egy informatikussal vagy az internetszolgáltatóddal ezen súlyos biztonsági probléma ügyében.",
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
'move-page'               => '$1 átnevezése',
'move-page-legend'        => 'Lap átnevezése',
'movepagetext'            => "Az alábbi űrlap használatával nevezhetsz át egy lapot, és helyezheted át előzményeit az új névre.
A régi cím az új címre átirányító lap lesz. A régi lapcímre mutató hivatkozások változatlanok maradnak;
a rossz átirányításokat ellenőrizd.
Te vagy a felelős annak biztosításáért, hogy a hivatkozások továbbítsanak ahhoz a ponthoz, ahová feltehetőleg vinniük kell.

A lap '''nem''' kerül áthelyezésre, ha már van olyan című új lap, hacsak nem üres vagy átirányítás, és nincs szerkesztési előzménye.
Ez azt jelenti, hogy visszanevezheted az oldalt az eredeti nevére, ha hibázol, létező oldalt pedig nem tudsz felülírni.

<b>FIGYELEM!</b>
Népszerű oldalak esetén ez drasztikus és nem várt változtatás lehet;
győződj meg róla a folytatás előtt, hogy tisztában vagy-e a következményekkel.",
'movepagetalktext'        => "A laphoz tartozó vitalap automatikusan átneveződik, '''kivéve, ha:'''
*már létezik egy nem üres vitalap az új helyen,
*nem jelölöd be a lenti pipát.

Ezen esetekben a vitalapot külön, kézzel kell átnevezned a kívánságaid szerint.",
'movearticle'             => 'Lap átnevezése',
'movenologin'             => 'Nem jelentkeztél be',
'movenologintext'         => 'Ahhoz, hogy átnevezhess egy lapot, [[Special:Userlogin|be kell lépned]].',
'movenotallowed'          => 'A lapok áthelyezése ebben a wikiben a számdra nem engedélyezett.',
'newtitle'                => 'Az új cím:',
'move-watch'              => 'Figyeld a lapot',
'movepagebtn'             => 'Lap átnevezése',
'pagemovedsub'            => 'Átnevezés sikeres',
'movepage-moved'          => "<big>'''A(z) „$1” lapot sikeresen átmozgattad a(z) „$2” névre.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Ilyen névvel már létezik lap, vagy az általad választott név érvénytelen.
Kérlek, válassz egy másik nevet.',
'cantmove-titleprotected' => 'Nem nevezheted át a lapot, mert az új cím le van védve a létrehozás ellen.',
'talkexists'              => 'A lap áthelyezése sikerült, de a hozzá tartozó vitalapot nem tudtam áthelyezni mert már létezik egy egyező nevű
lap az új helyen. Kérjük gondoskodj a két lap összefűzéséről.',
'movedto'                 => 'átnevezve',
'movetalk'                => 'Nevezd át a vitalapot is, ha lehetséges',
'talkpagemoved'           => 'Az oldal vitalapját is átmozgattam.',
'talkpagenotmoved'        => 'Az oldal vitalapja <strong>nem került</strong> átmozgatásra.',
'1movedto2'               => '[[$1]] lapot átneveztem [[$2]] névre',
'1movedto2_redir'         => '[[$1]] lapot átneveztem [[$2]] névre (az átirányítást felülírva)',
'movelogpage'             => 'Átnevezési napló',
'movelogpagetext'         => 'Az alábbiakban az átnevezett lapok listája látható.',
'movereason'              => 'Indoklás',
'revertmove'              => 'visszaállítás',
'delete_and_move'         => 'Törlés és átnevezés',
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
'export-templates'  => 'Sablonok hozzáadása',

# Namespace 8 related
'allmessages'               => 'Rendszerüzenetek',
'allmessagesname'           => 'Név',
'allmessagesdefault'        => 'Alapértelmezett szöveg',
'allmessagescurrent'        => 'Jelenlegi szöveg',
'allmessagestext'           => 'Ez a MediaWiki-névtérben elérhető összes üzenet listája.',
'allmessagesnotsupportedDB' => "A '''''{{ns:special}}:Allmessages''''' lap nem használható, mert a '''\$wgUseDatabaseMessages''' ki van kapcsolva.",
'allmessagesfilter'         => 'Üzenetnevek szűrése:',
'allmessagesmodified'       => 'Csak a módosítottak mutatása',

# Thumbnails
'thumbnail-more'           => 'Nagyít',
'filemissing'              => 'A fájl nincs meg',
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
'importsuccess'              => 'Az importálás befejeződött!',
'importhistoryconflict'      => 'Ütköző előzményváltozat létezik (lehet, hogy már importálták ezt a lapot)',
'importnosources'            => 'Nincsenek transzwikiimport-források definiálva, a közvetlen laptörténet-felküldés pedig nem megengedett.',
'importnofile'               => 'Nem került importfájl feltöltésre.',
'importuploaderrorsize'      => 'Az importálandó fájl feltöltése nem sikerült, mert nagyobb, mint a megengedett feltöltési méret.',
'importuploaderrorpartial'   => 'Az importálandó fájl feltöltése nem sikerült. A fájl csak részben lett feltöltve.',
'importuploaderrortemp'      => 'Az importálandó fájl feltöltése nem sikerült. Nem létezik ideiglenes mappa.',
'import-parse-failure'       => 'XML elemzési hiba importáláskor',
'import-noarticle'           => 'Nincs importálandó lap!',
'import-nonewrevisions'      => 'A korábban importált összes változat.',
'xml-error-string'           => '$1 a(z) $2. sorban, $3. oszlopban ($4. bájt): $5',

# Import log
'importlogpage'                    => 'Importnapló',
'importlogpagetext'                => 'Lapok szerkesztési előzményekkel történő adminisztratív imporálása más wikikből.',
'import-logentry-upload'           => '[[$1]] importálása fájlfeltöltéssel kész',
'import-logentry-upload-detail'    => '$1 változat',
'import-logentry-interwiki'        => '$1 más wikiből áthozva',
'import-logentry-interwiki-detail' => '$1 változat innen: $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'A felhasználói lapod',
'tooltip-pt-anonuserpage'         => 'Az általad használt IP-címhez tartozó felhasználói lap',
'tooltip-pt-mytalk'               => 'A vitalapod',
'tooltip-pt-anontalk'             => 'Az általad használt IP-címről végrehajtott szerkesztések megvitatása',
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
'tooltip-search'                  => 'Keresés a wikin',
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
'tooltip-save'                    => 'A változtatásaid elmentése',
'tooltip-preview'                 => 'Mielőtt elmentenéd a lapot, ellenőrizd, biztosan úgy néz-e ki, ahogy szeretnéd!',
'tooltip-diff'                    => 'Nézd meg, milyen változtatásokat végeztél eddig a szövegen',
'tooltip-compareselectedversions' => 'A két kiválasztott változat közötti eltérések megjelenítése',
'tooltip-watch'                   => 'Lap hozzáadása a figyelőlistádhoz',
'tooltip-recreate'                => 'A lap újra létrehozása a törlés ellenére',
'tooltip-upload'                  => 'Feltöltés indítása',

# Stylesheets
'common.css'   => '/* Közös CSS az összes felszínnek */',
'monobook.css' => '/* Az ide elhelyezett CSS hatással lesz a Monobook felület használóira */',

# Scripts
'common.js'   => '/* Az ide elhelyezett JavaScript kód minden felhasználó számára lefut az oldalak betöltésekor. */',
'monobook.js' => '/* Elavult; használd helyette a [[MediaWiki:common.js]]-t */',

# Metadata
'nodublincore'      => 'Ezen a kiszolgálón a Dublin Core RDF metaadatok használata letiltott.',
'nocreativecommons' => 'Ezen a kiszolgálón a Creative Commons RDF metaadatok használata letiltott.',
'notacceptable'     => 'A wiki kiszolgálója nem tudja olyan formátumban biztosítani az adatokat, amit a kliens olvasni tud.',

# Attribution
'anonymous'        => 'Névtelen {{SITENAME}}-felhasználó(k)',
'siteuser'         => '$1 {{SITENAME}}-felhasználó',
'lastmodifiedatby' => 'Ezt a lapot utoljára $3 módosította $2, $1 időpontban.', # $1 date, $2 time, $3 user
'othercontribs'    => '$1 munkája alapján.',
'others'           => 'mások',
'siteusers'        => '$1 {{SITENAME}}-felhasználó(k)',
'creditspage'      => 'A lap közreműködői',
'nocredits'        => 'Ennek a lapnak nincs közreműködői információja.',

# Spam protection
'spamprotectiontitle' => 'Spamszűrő',
'spamprotectiontext'  => 'Az általad elmenteni kívánt lap egyik külső hivatkozása fennakadt a spamszűrőn. A hivatkozást lehet, hogy te helyezted el a cikkben, de lehet, hogy már korábban is ott volt. Csak úgy tudod a lapot elmenteni, ha eltávolítod belőle a problémás külső hivatkozást. Ehhez és a szerkesztéshez a böngésző „vissza” gombjával tudsz visszatérni.',
'spamprotectionmatch' => 'A spamszűrőn az alábbi szöveg fennakadt: $1',
'spambot_username'    => 'MediaWiki spam kitakarítása',
'spam_reverting'      => 'Visszatérés a $1 lapra mutató hivatkozásokat nem tartalmazó utolsó változathoz',
'spam_blanking'       => 'Az összes változat tartalmazott a $1 lapra mutató hivatkozásokat, kiürítés',

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
'markaspatrolledtext'                 => 'Ellenőriztem',
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
'deletedrevision'                 => 'Régebbi változat törölve: $1',
'filedeleteerror-short'           => 'Hiba a fájl törlésekor: $1',
'filedeleteerror-long'            => 'Hibák merültek föl a következő fájl törlésekor:

$1',
'filedelete-missing'              => 'A(z) "$1" fájl nem törölhető, mert nem létezik.',
'filedelete-old-unregistered'     => 'A megadott "$1" fájlváltzozat nincs az adatbázisban.',
'filedelete-current-unregistered' => 'A megadott "$1" fájl nincs az adatbázisban.',
'filedelete-archive-read-only'    => 'A(z) "$1" archív könyvtár a webkiszolgáló által nem írható.',

# Browsing diffs
'previousdiff' => '← Előző változtatások',
'nextdiff'     => 'Következő változtatások →',

# Media information
'mediawarning'         => "'''Figyelmeztetés''': Ez a fájl kártékony kódot tartalmazhat, aminek futtatása kárt tehet a számítógépes rendszeredben.<hr />",
'imagemaxsize'         => 'A kép leírólapján mutatott legnagyobb képméret:',
'thumbsize'            => 'Bélyegkép mérete:',
'widthheightpage'      => '$1×$2, $3 oldal',
'file-info'            => '(fájlméret: $1, MIME típus: $2)',
'file-info-size'       => '($1 × $2 képpont, fájlméret: $3, MIME típus: $4)',
'file-nohires'         => '<small>Nem érhető el nagyobb felbontású változat.</small>',
'svg-long-desc'        => '(SVG fájl, névlegesen $1 × $2 képpont, fájlméret: $3)',
'show-big-image'       => 'A kép nagyfelbontású változata',
'show-big-image-thumb' => '<small>Az előnézet mérete: $1 × $2 képpont</small>',

# Special:Newimages
'newimages'             => 'Új képek galériája',
'imagelisttext'         => 'Lentebb $1 kép látható, $2 rendezve.',
'newimages-summary'     => 'Ezen a speciális lapon láthatóak a legutóbb feltöltött fájlok',
'showhidebots'          => '($1 robot)',
'noimages'              => 'Nem tekinthető meg semmi.',
'ilsubmit'              => 'Keresés',
'bydate'                => 'dátum szerint',
'sp-newimages-showfrom' => 'Új képek mutatása $1 után',

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
* focallength', # Do not translate list items

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
'exif-primarychromaticities'       => 'Színinger',
'exif-ycbcrcoefficients'           => 'Színtér transzformációs mátrixának együtthatói',
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
'exif-colorspace'                  => 'Színtér',
'exif-componentsconfiguration'     => 'Az egyes összetevők jelentése',
'exif-compressedbitsperpixel'      => 'Képtömörítési mód',
'exif-pixelydimension'             => 'Érvényes képszélesség',
'exif-pixelxdimension'             => 'Érvényes képmagasság',
'exif-makernote'                   => 'Gyártó jegyzetei',
'exif-usercomment'                 => 'Felhasználók megjegyzései',
'exif-relatedsoundfile'            => 'Kapcsolódó hangfájl',
'exif-datetimeoriginal'            => 'EXIF információ létrehozásának dátuma',
'exif-datetimedigitized'           => 'Digitalizálás dátuma és időpontja',
'exif-subsectime'                  => 'DateTime almásodpercek',
'exif-subsectimeoriginal'          => 'DateTimeOriginal almásodpercek',
'exif-subsectimedigitized'         => 'DateTimeDigitized almásodpercek',
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
'exif-exposurebiasvalue'           => 'Expozíciós dőltség',
'exif-maxaperturevalue'            => 'Legnagyobb földi lencsenyílás',
'exif-subjectdistance'             => 'Tárgy távolsága',
'exif-meteringmode'                => 'Fénymérési mód',
'exif-lightsource'                 => 'Fényforrás',
'exif-flash'                       => 'Vaku',
'exif-focallength'                 => 'Fókusztávolság',
'exif-subjectarea'                 => 'Tárgy területe',
'exif-flashenergy'                 => 'Vaku ereje',
'exif-spatialfrequencyresponse'    => 'Térbeli frekvenciareakció',
'exif-focalplanexresolution'       => 'Mátrixdetektor X felbontása',
'exif-focalplaneyresolution'       => 'Mátrixdetektor Y felbontása',
'exif-focalplaneresolutionunit'    => 'Mátrixdetektor felbontásának mértékegysége',
'exif-subjectlocation'             => 'Tárgy helye',
'exif-exposureindex'               => 'Expozíciós index',
'exif-sensingmethod'               => 'Érzékelési mód',
'exif-filesource'                  => 'Fájl forrása',
'exif-scenetype'                   => 'Színhely típusa',
'exif-cfapattern'                  => 'CFA minta',
'exif-customrendered'              => 'Egyéni képfeldolgozás',
'exif-exposuremode'                => 'Expozíciós mód',
'exif-whitebalance'                => 'Fehéregyensúly',
'exif-digitalzoomratio'            => 'Digitális zoom aránya',
'exif-focallengthin35mmfilm'       => 'Fókusztávolság 35 mm-es filmen',
'exif-scenecapturetype'            => 'Színhely rögzítési típusa',
'exif-gaincontrol'                 => 'Érzékelés vezérlése',
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

'exif-planarconfiguration-1' => 'sűrű formátum',
'exif-planarconfiguration-2' => 'sík formátum',

'exif-componentsconfiguration-0' => 'nem létezik',

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
'exif-meteringmode-2'   => 'CenterWeightedAverage',
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
'exif-lightsource-24'  => 'ISO stúdió wolfram',
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
'exif-exposuremode-2' => 'automatikus zárás',

'exif-whitebalance-0' => 'Automatikus fehéregyensúly',
'exif-whitebalance-1' => 'Kézi fehéregyensúly',

'exif-scenecapturetype-0' => 'Hagyományos',
'exif-scenecapturetype-1' => 'Tájkép',
'exif-scenecapturetype-2' => 'Arckép',
'exif-scenecapturetype-3' => 'Éjszakai színhely',

'exif-gaincontrol-0' => 'Nincs',
'exif-gaincontrol-1' => 'Alacsony frekvenciák kiemelése',
'exif-gaincontrol-2' => 'Magas frekvenciák kiemelése',
'exif-gaincontrol-3' => 'Alacsony frekvenciák elnyomása',
'exif-gaincontrol-4' => 'Magas frekvenciák elnyomása',

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

'exif-gpsstatus-a' => 'Mérés folyamatban',
'exif-gpsstatus-v' => 'Mérés közbeni működőképesség',

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
'namespacesall'    => 'Összes',
'monthsall'        => 'mind',

# E-mail address confirmation
'confirmemail'             => 'E-mail cím megerősítése',
'confirmemail_noemail'     => 'Nincs érvényes e-mail cím megadva a [[Special:Preferences|beállításaidnál]].',
'confirmemail_text'        => 'Meg kell erősítened az e-mail címed, mielőtt használhatnád a(z) {{SITENAME}} levelezési rendszerét. Nyomd meg az alsó gombot, hogy kaphass egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot. Töltsd be a kódot a böngésződbe, hogy aktiválhasd az e-mail címedet.',
'confirmemail_pending'     => '<div class="error">A megerősítő kódot már elküldtük neked e-mailben, kérjük, várj türelemmel, amíg a szükséges adatok megérkeznek az e-mailcímedre, és csak akkor kérj új kódot, ha valami technikai malőr folytán értelmes időn belül nem kapod meg a levelet.</div>',
'confirmemail_send'        => 'Küldd el a kódot',
'confirmemail_sent'        => 'Kaptál egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot.',
'confirmemail_oncreate'    => 'A megerősítő kódot elküldtük az e-mail címedre.
Ez a kód nem szükséges a belépéshez, de meg kell adnod,
mielőtt a wiki e-mail alapú szolgáltatásait igénybe veheted.',
'confirmemail_sendfailed'  => 'Nem tudjuk elküldeni a megerősítéshez szükséges e-mailt. Kérjük, ellenőrizd a címet. $1',
'confirmemail_invalid'     => 'Nem megfelelő kód. A kódnak lehet, hogy lejárt a felhasználhatósági ideje.',
'confirmemail_needlogin'   => 'Meg kell $1 erősíteni az e-mail címedet.',
'confirmemail_success'     => 'Az e-mail címed megerősítve. Most már beléphetsz a wikibe.',
'confirmemail_loggedin'    => 'E-mail címed megerősítve.',
'confirmemail_error'       => 'Hiba az e-mail címed megerősítése során.',
'confirmemail_subject'     => '{{SITENAME}} e-mail cím megerősítés',
'confirmemail_body'        => 'Valaki, valószínűleg te, ezzel az e-mail címmel regisztrált
"$2" néven a {{SITENAME}} wikibe, a(z) $1 IP-címről.

Annak érdekében, hogy megerősítsd, ez az azonosító valóban hozzád tartozik,
és hogy aktiváld az e-mail címedet, nyisd meg az alábbi linket a böngésződben:

$3

Ha ez *nem* te vagy, kattints erre a linkre az
e-mail cím megerősíthetőségének visszavonásához:

$5

A megerősítésre szánt kód felhasználhatósági idejének lejárata: $4.',
'confirmemail_invalidated' => 'E-mail-cím megerősíthetősége visszavonva',
'invalidateemail'          => 'E-mail-cím megerősíthetőségének visszavonása',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki beillesztése le van tiltva]',
'scarytranscludefailed'   => '[$1 sablonjának visszakeresése nem sikerült; sajnáljuk]',
'scarytranscludetoolong'  => '[Túl hosszú az URL; sajnáljuk]',

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
'confirmrecreate'     => "Miután elkezdted szerkeszteni, [[User:$1|$1]] ([[User vita:$1|vita]]) törölte ezt a lapot a következő indokkal:
: ''$2''
Kérlek erősítsd meg, hogy tényleg újra akarod-e írni a lapot.",
'recreate'            => 'Újraírás',

# HTML dump
'redirectingto' => 'Átirányítás a következőre: [[:$1|$1]]...',

# action=purge
'confirm_purge'        => 'Törlöd az oldal gyorsítótárban (cache) található változatát?

$1',
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => "''$1''-t tartalmazó lapokra keresés.",
'searchnamed'      => "''$1'' című lapok keresése.",
'articletitles'    => "''$1'' kezdetű szócikkek",
'hideresults'      => 'Eredmények elrejtése',
'useajaxsearch'    => 'AJAX-alapú kereső használata',

# Multipage image navigation
'imgmultipageprev' => '← előző oldal',
'imgmultipagenext' => 'következő oldal →',
'imgmultigo'       => 'Menj',
'imgmultigotopre'  => 'Oldalra',

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
'autoredircomment' => 'Átirányítás ide: [[$1]]',
'autosumm-new'     => 'Új oldal, tartalma: „$1”',

# Live preview
'livepreview-loading' => 'Loading…',
'livepreview-ready'   => 'Betöltés… Kész!',
'livepreview-failed'  => 'Az élő előnézet nem sikerült! Próbálkozz a normál előnézettel.',
'livepreview-error'   => 'A csatlakozás nem sikerült: $1 "$2". Próbálkozz a normál előnézettel.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'A(z) $1 másodpercnél frissebb szerkesztések nem biztos, hogy megjelennek ezen a listán.',
'lag-warn-high'   => 'Az adatbázisszerver túlterheltsége miatt a(z) $1 másodpercnél frissebb változtatások nem biztos, hogy megjelennek ezen a listán.',

# Watchlist editor
'watchlistedit-numitems'       => 'A figyelőlistádon $1 cikk szerepel (a vitalapok nélkül).',
'watchlistedit-noitems'        => 'A figyelőlistád üres.',
'watchlistedit-normal-title'   => 'A figyelőlista szerkesztése',
'watchlistedit-normal-legend'  => 'Lapok eltávolítása a figyelőlistáról',
'watchlistedit-normal-explain' => 'A figyelőlistádra felvett lapok az alábbi listában találhatók.
Ha el szeretnél távolítani egy lapot, jelöld be a címe melletti jelölőnégyzetet,
és kattints rá a lap alján található „A kijelöltek eltávolítása” feliratú gombra. Lehetőség van a [[Special:Watchlist/raw|nyers figyelőlista]] szerkesztésére is.',
'watchlistedit-normal-submit'  => 'A kijelöltek eltávolítása',
'watchlistedit-normal-done'    => '{{PLURAL:$1|A következő|A következő $1}} cikket eltávolítottam a figyelőlistádról:',
'watchlistedit-raw-title'      => 'A nyers figyelőlista szerkesztése',
'watchlistedit-raw-legend'     => 'A nyers figyelőlista szerkesztése',
'watchlistedit-raw-explain'    => 'A figyelőlistádra felvett lapok az alábbi listában találhatók. A lista szerkeszthető;
minden egyes sor egy figyelt lap címe. Ha kész vagy, kattints a lista alatt található
„Mentés” feliratú gombra. Használhatod a [[Special:Watchlist/edit|hagyományos listaszerkesztőt]] is.',
'watchlistedit-raw-titles'     => 'A figyelőlistádon található cikkek:',
'watchlistedit-raw-submit'     => 'Mentés',
'watchlistedit-raw-done'       => 'A figyelőlistád változtatásait elmentettem.',
'watchlistedit-raw-added'      => 'A {{PLURAL:$1|következő|következő $1}} cikket hozzáadtam a figyelőlistádhoz:',
'watchlistedit-raw-removed'    => 'A {{PLURAL:$1|következő|következő $1}} cikket eltávolítottam a figyelőlistádról:',

# Watchlist editing tools
'watchlisttools-view' => 'Kapcsolódó változtatások',
'watchlisttools-edit' => 'A figyelőlista megtekintése és szerkesztése',
'watchlisttools-raw'  => 'A nyers figyelőlista szerkesztése',

# Core parser functions
'unknown_extension_tag' => 'Ismeretlen tag kiterjesztés: $1',

# Special:Version
'version'                          => 'Névjegy', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Telepített kiterjesztések',
'version-specialpages'             => 'Speciális lapok',
'version-parserhooks'              => 'Értelmező hookok',
'version-variables'                => 'Változók',
'version-other'                    => 'Más',
'version-mediahandlers'            => 'Médiafájl-kezelők',
'version-hooks'                    => 'Hookok',
'version-extension-functions'      => 'A kiterjesztések függvényei',
'version-parser-extensiontags'     => 'Az értelmező kiterjesztéseinek tagjei',
'version-parser-function-hooks'    => 'Az értelmező függvényeinek hookjai',
'version-skin-extension-functions' => 'Felület kiterjeszések függvényei',
'version-hook-name'                => 'Hook neve',
'version-hook-subscribedby'        => 'Használja',
'version-version'                  => 'verzió:',
'version-license'                  => 'Licenc',
'version-software'                 => 'Telepített szoftverek',
'version-software-product'         => 'Termék',
'version-software-version'         => 'Verzió',

# Special:Filepath
'filepath'         => 'Fájlelérés',
'filepath-page'    => 'Fájl:',
'filepath-submit'  => 'Elérés',
'filepath-summary' => 'Ezen lap segítségével lekérheted egy adott fájl pontos útvonalát. A képek teljes méretben jelennek meg, más fájltípusok közvetlenül a hozzájuk rendelt programmal indulnak el.

Add meg a fájlnevet a „{{ns:image}}:” prefixum nélkül.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Duplikátumok keresése',
'fileduplicatesearch-summary'  => 'Fájlok duplikátumainak keresése hash értékük alapján.

Add meg a fájl nevét „{{ns:image}}:” előtag nélkül.',
'fileduplicatesearch-legend'   => 'Duplikátum keresése',
'fileduplicatesearch-filename' => 'Fájlnév:',
'fileduplicatesearch-submit'   => 'Keresés',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Fájlméret: $3<br />MIME-típus: $4',
'fileduplicatesearch-result-1' => 'A(z) „$1“ nevű fájlnak nincs duplikátuma.',
'fileduplicatesearch-result-n' => 'A(z) „$1“ nevű fájlnak nincs duplikátuma.',

);
