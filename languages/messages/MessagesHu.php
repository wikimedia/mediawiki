<?php
/** Hungarian (Magyar)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alquen
 * @author Balasyum
 * @author Bdamokos
 * @author Bennó
 * @author BáthoryPéter
 * @author CERminator
 * @author Cerasus
 * @author Dani
 * @author Dorgan
 * @author Enbéká
 * @author Glanthor Reviol
 * @author Gondnok
 * @author Hunyadym
 * @author KossuthRad
 * @author Misibacsi
 * @author Samat
 * @author Terik
 * @author Tgr
 */

$namespaceNames = array(
	NS_MEDIA            => 'Média',
	NS_SPECIAL          => 'Speciális',
	NS_TALK             => 'Vita',
	NS_USER             => 'Szerkesztő',
	NS_USER_TALK        => 'Szerkesztővita',
	NS_PROJECT_TALK     => '$1-vita',
	NS_FILE             => 'Fájl',
	NS_FILE_TALK        => 'Fájlvita',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-vita',
	NS_TEMPLATE         => 'Sablon',
	NS_TEMPLATE_TALK    => 'Sablonvita',
	NS_HELP             => 'Segítség',
	NS_HELP_TALK        => 'Segítségvita',
	NS_CATEGORY         => 'Kategória',
	NS_CATEGORY_TALK    => 'Kategóriavita',
);

$namespaceAliases = array(
	'Kép' => NS_FILE,
	'Képvita' => NS_FILE_TALK,
	'User_vita'      => NS_USER_TALK,
	'$1_vita'        => NS_PROJECT_TALK,
	'Kép_vita'       => NS_FILE_TALK,
	'MediaWiki_vita' => NS_MEDIAWIKI_TALK,
	'Sablon_vita'    => NS_TEMPLATE_TALK,
	'Segítség_vita'  => NS_HELP_TALK,
	'Kategória_vita' => NS_CATEGORY_TALK,
);

$fallback8bitEncoding = "iso8859-2";
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Kettős_átirányítások', 'Dupla_átirányítások' ),
	'BrokenRedirects'           => array( 'Nem_létező_lapra_mutató_átirányítások', 'Hibás_átirányítások' ),
	'Disambiguations'           => array( 'Egyértelműsítő_lapok' ),
	'Userlogin'                 => array( 'Belépés' ),
	'Userlogout'                => array( 'Kilépés' ),
	'CreateAccount'             => array( 'Szerkesztői_fiók_létrehozása', 'Felhasználói_fiók_létrehozása' ),
	'Preferences'               => array( 'Beállításaim' ),
	'Watchlist'                 => array( 'Figyelőlistám' ),
	'Recentchanges'             => array( 'Friss_változtatások' ),
	'Upload'                    => array( 'Feltöltés' ),
	'Listfiles'                 => array( 'Fájlok_listája', 'Képek_listája', 'Fájllista', 'Képlista' ),
	'Newimages'                 => array( 'Új_fájlok', 'Új_képek', 'Új_képek_galériája' ),
	'Listusers'                 => array( 'Szerkesztők_listája', 'Szerkesztők', 'Felhasználók' ),
	'Listgrouprights'           => array( 'Szerkesztői_csoportok_jogai' ),
	'Statistics'                => array( 'Statisztika', 'Statisztikák' ),
	'Randompage'                => array( 'Lap_találomra' ),
	'Lonelypages'               => array( 'Árva_lapok', 'Magányos_lapok' ),
	'Uncategorizedpages'        => array( 'Kategorizálatlan_lapok' ),
	'Uncategorizedcategories'   => array( 'Kategorizálatlan_kategóriák' ),
	'Uncategorizedimages'       => array( 'Kategorizálatlan_fájlok', 'Kategorizálatlan_képek' ),
	'Uncategorizedtemplates'    => array( 'Kategorizálatlan_sablonok' ),
	'Unusedcategories'          => array( 'Nem_használt_kategóriák' ),
	'Unusedimages'              => array( 'Nem_használt_képek' ),
	'Wantedpages'               => array( 'Keresett_lapok' ),
	'Wantedcategories'          => array( 'Keresett_kategóriák' ),
	'Wantedfiles'               => array( 'Keresett_fájlok' ),
	'Wantedtemplates'           => array( 'Keresett_sablonok' ),
	'Mostlinked'                => array( 'Legtöbbet_hivatkozott_lapok' ),
	'Mostlinkedcategories'      => array( 'Legtöbbet_hivatkozott_kategóriák' ),
	'Mostlinkedtemplates'       => array( 'Legtöbbet_hivatkozott_sablonok' ),
	'Mostimages'                => array( 'Legtöbbet_használt_fájlok', 'Legtöbbet_használt_képek' ),
	'Mostcategories'            => array( 'Legtöbb_kategóriába_tartozó_lapok' ),
	'Mostrevisions'             => array( 'Legtöbbet_szerkesztett_lapok' ),
	'Fewestrevisions'           => array( 'Legkevesebbet_szerkesztett_lapok' ),
	'Shortpages'                => array( 'Rövid_lapok' ),
	'Longpages'                 => array( 'Hosszú_lapok' ),
	'Newpages'                  => array( 'Új_lapok' ),
	'Ancientpages'              => array( 'Régóta_nem_változott_szócikkek' ),
	'Deadendpages'              => array( 'Zsákutcalapok' ),
	'Protectedpages'            => array( 'Védett_lapok' ),
	'Protectedtitles'           => array( 'Védett_címek' ),
	'Allpages'                  => array( 'Az_összes_lap_listája' ),
	'Prefixindex'               => array( 'Keresés_előtag_szerint' ),
	'Ipblocklist'               => array( 'Blokkolt_IP-címek_listája' ),
	'Unblock'                   => array( 'Blokkolás_feloldása' ),
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
	'Userrights'                => array( 'Szerkesztők_jogai', 'Szerkesztői_jogok', 'Szerkesztőjogok', 'Szerkesztő_jogai' ),
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
	'Withoutinterwiki'          => array( 'Nyelvközi_hivatkozás_nélküli_lapok', 'Wikiközi_hivatkozás_nélküli_lapok', 'Interwikilinkek_nélküli_lapok' ),
	'MergeHistory'              => array( 'Laptörténetek_egyesítése', 'Laptörténet-egyesítés' ),
	'Filepath'                  => array( 'Fájl_elérési_útja', 'Fájl_elérési_út' ),
	'Invalidateemail'           => array( 'E-mail_cím_érvénytelenítése' ),
	'Blankpage'                 => array( 'Üres_lap' ),
	'LinkSearch'                => array( 'Hivatkozás_keresés' ),
	'DeletedContributions'      => array( 'Törölt_szerkesztések' ),
	'Tags'                      => array( 'Címkék' ),
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
	'redirect'              => array( '0', '#ÁTIRÁNYÍTÁS', '#REDIRECT' ),
	'notoc'                 => array( '0', '__NINCSTARTALOMJEGYZÉK__', '__NINCSTJ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__NINCSGALÉRIA__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__LEGYENTARTALOMJEGYZÉK__', '__LEGYENTJ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__TARTALOMJEGYZÉK__', '__TJ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__NINCSSZERKESZTÉS__', '__NINCSSZERK__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'HÓNAP', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'HÓNAP1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'HÓNAPNEVE', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'HÓNAPRÖVID', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'MAINAP', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'MAINAP2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'MAINAPNEVE', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ÉV', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'IDŐ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ÓRA', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'HELYIHÓNAP', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'HELYIHÓNAP1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'HELYIHÓNAPNÉV', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'HELYIHÓNAPRÖVIDÍTÉS', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'HELYINAP', 'LOCALDAY' ),
	'localday2'             => array( '1', 'HELYINAP2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'HELYINAPNEVE', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'HELYIÉV', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'HELYIIDŐ', 'LOCALTIME' ),
	'localhour'             => array( '1', 'HELYIÓRA', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'OLDALAKSZÁMA', 'LAPOKSZÁMA', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'SZÓCIKKEKSZÁMA', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'FÁJLOKSZÁMA', 'KÉPEKSZÁMA', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'SZERKESZTŐKSZÁMA', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'AKTÍVSZERKESZTŐKSZÁMA', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'SZERKESZTÉSEKSZÁMA', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'MEGTEKINTÉSEKSZÁMA', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'OLDALNEVE', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'OLDALNEVEE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NÉVTERE', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NÉVTEREE', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'VITATERE', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'VITATEREE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'SZÓCIKKNÉVTERE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'SZÓCIKKNÉVTEREE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'LAPTELJESNEVE', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'LAPTELJESNEVEE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ALLAPNEVE', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ALLAPNEVEE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'ALAPLAPNEVE', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'ALAPLAPNEVEE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'VITALAPNEVE', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'VITALAPNEVEE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'SZÓCIKKNEVE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'SZÓCIKKNEVEE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'ÜZENET:', 'ÜZ:', 'MSG:' ),
	'subst'                 => array( '0', 'BEILLESZT:', 'BEMÁSOL:', 'SUBST:' ),
	'img_thumbnail'         => array( '1', 'bélyegkép', 'bélyeg', 'miniatűr', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'bélyegkép=$1', 'bélyeg=$1', 'miniatűr=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'jobb', 'jobbra', 'right' ),
	'img_left'              => array( '1', 'bal', 'balra', 'left' ),
	'img_none'              => array( '1', 'semmi', 'none' ),
	'img_center'            => array( '1', 'közép', 'középre', 'center', 'centre' ),
	'img_framed'            => array( '1', 'keretezett', 'keretes', 'keretben', 'kerettel', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'keretnélküli', 'frameless' ),
	'img_page'              => array( '1', 'oldal=$1', 'oldal $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'fennjobbra', 'fennjobbra=$1', 'fennjobbra $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'keret', 'border' ),
	'img_baseline'          => array( '1', 'alapvonal', 'baseline' ),
	'img_sub'               => array( '1', 'ai', 'alsóindex', 'sub' ),
	'img_super'             => array( '1', 'fi', 'felsőindex', 'super', 'sup' ),
	'img_top'               => array( '1', 'fenn', 'fent', 'top' ),
	'img_text_top'          => array( '1', 'szöveg-fenn', 'szöveg-fent', 'text-top' ),
	'img_middle'            => array( '1', 'vközépen', 'vközépre', 'middle' ),
	'img_bottom'            => array( '1', 'lenn', 'lent', 'bottom' ),
	'img_text_bottom'       => array( '1', 'szöveg-lenn', 'szöveg-lent', 'text-bottom' ),
	'sitename'              => array( '1', 'WIKINEVE', 'SITENAME' ),
	'ns'                    => array( '0', 'NÉVTÉR:', 'NS:' ),
	'localurl'              => array( '0', 'HELYIURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'HELYIURLE:', 'LOCALURLE:' ),
	'server'                => array( '0', 'SZERVER', 'KISZOLGÁLÓ', 'SERVER' ),
	'servername'            => array( '0', 'SZERVERNEVE', 'KISZOLGÁLÓNEVE', 'SERVERNAME' ),
	'grammar'               => array( '0', 'NYELVTAN:', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'HÉT', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'HÉTNAPJA', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'HELYIHÉT', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'HELYIHÉTNAPJA', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'VÁLTOZATAZON', 'VÁLTOZATAZONOSÍTÓ', 'REVISIONID' ),
	'revisionday'           => array( '1', 'VÁLTOZATNAPJA', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'VÁLTOZATNAPJA2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'VÁLTOZATHÓNAPJA', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'VÁLTOZATÉVE', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'VÁLTOZATIDŐBÉLYEG', 'VÁLTOZATIDEJE', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'VÁLTOZATSZERKESZTŐJE', 'REVISIONUSER' ),
	'plural'                => array( '0', 'TÖBBESSZÁM:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'TELJESURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'TELJESURLE:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'KISKEZDŐ:', 'KISKEZDŐBETŰ:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'NAGYKEZDŐ:', 'NAGYKEZDŐBETŰ:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'KISBETŰ:', 'KISBETŰK:', 'KB:', 'KISBETŰS:', 'LC:' ),
	'uc'                    => array( '0', 'NAGYBETŰ:', 'NAGYBETŰK', 'NB:', 'NAGYBETŰS:', 'UC:' ),
	'displaytitle'          => array( '1', 'MEGJELENÍTENDŐCÍM', 'CÍM', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__ÚJSZAKASZHIV__', '__ÚJSZAKASZLINK__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__NINCSÚJSZAKASZHIV__', '__NINCSÚJSZAKASZLINK__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'JELENLEGIVÁLTOZAT', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'URLKÓDOLVA:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'HORGONYKÓDOLVA', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'IDŐBÉLYEG', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'HELYIIDŐBÉLYEG', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'IRÁNYJELZŐ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#NYELV:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'TARTALOMNYELVE', 'TARTNYELVE', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'OLDALAKNÉVTÉRBEN:', 'OLDALAKNBEN:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'ADMINOKSZÁMA', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'FORMÁZOTTSZÁM', 'SZÁMFORMÁZÁS', 'SZÁMFORM', 'FORMATNUM' ),
	'special'               => array( '0', 'speciális', 'special' ),
	'defaultsort'           => array( '1', 'RENDEZÉS:', 'KULCS:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'ELÉRÉSIÚT:', 'FILEPATH:' ),
	'hiddencat'             => array( '1', '__REJTETTKAT__', '__REJTETTKATEGÓRIA__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'LAPOKAKATEGÓRIÁBAN', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'LAPMÉRET', 'PAGESIZE' ),
	'noindex'               => array( '1', '__NINCSINDEX__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'CSOPORTTAGOK', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__ÁLLANDÓÁTIRÁNYÍTÁS__', '__STATIKUSÁTIRÁNYÍTÁS__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'VÉDELMISZINT', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'dátumformázás', 'formatdate', 'dateformat' ),
);

$linkTrail = '/^([a-záéíóúöüőűÁÉÍÓÚÖÜŐŰ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Hivatkozások aláhúzása:',
'tog-highlightbroken'         => 'A nem létező lapokat <a href="" class="new">így</a> jelölje. (Alternatíva: így<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Sorkizárt fejezetek',
'tog-hideminor'               => 'Apró változtatások elrejtése a friss változtatások lapon',
'tog-hidepatrolled'           => 'Az ellenőrzött szerkesztések elrejtése a friss változtatások lapon',
'tog-newpageshidepatrolled'   => 'Ellenőrzött lapok elrejtése az új lapok listájáról',
'tog-extendwatchlist'         => 'A figyelőlistán az összes változtatás látszódjon, ne csak az utolsó',
'tog-usenewrc'                => 'Fejlettebb friss változások listája (JavaScript-alapú)',
'tog-numberheadings'          => 'Fejezetcímek automatikus számozása',
'tog-showtoolbar'             => 'Szerkesztőeszközsor megjelenítése (JavaScript-alapú)',
'tog-editondblclick'          => 'A lapok szerkesztése dupla kattintásra (JavaScript-alapú)',
'tog-editsection'             => '[szerkesztés] linkek az egyes szakaszok szerkesztéséhez',
'tog-editsectiononrightclick' => 'Szakaszok szerkesztése a szakaszcímre való jobb kattintással (JavaScript-alapú)',
'tog-showtoc'                 => 'Tartalomjegyzék megjelenítése a három fejezetnél többel rendelkező cikkeknél',
'tog-rememberpassword'        => 'Emlékezzen rám ezzel a böngészővel (legfeljebb $1 napig)',
'tog-watchcreations'          => 'Az általam létrehozott lapok felvétele a figyelőlistára',
'tog-watchdefault'            => 'Az általam szerkesztett lapok felvétele a figyelőlistára',
'tog-watchmoves'              => 'Az általam átnevezett lapok felvétele a figyelőlistára',
'tog-watchdeletion'           => 'Az általam törölt lapok felvétele a figyelőlistára',
'tog-minordefault'            => 'Alapértelmezésben minden szerkesztésemet jelölje aprónak',
'tog-previewontop'            => 'Előnézet megjelenítése a szerkesztőablak előtt',
'tog-previewonfirst'          => 'Előnézet első szerkesztésnél',
'tog-nocache'                 => 'A lapok gyorstárazásának letiltása a böngészőben',
'tog-enotifwatchlistpages'    => 'Értesítés küldése e-mailben, ha egy általam figyelt lap megváltozik',
'tog-enotifusertalkpages'     => 'Értesítés e-mailben, ha megváltozik a vitalapom',
'tog-enotifminoredits'        => 'Értesítés e-mailben a lapok apró változtatásairól',
'tog-enotifrevealaddr'        => 'Jelenítse meg az e-mail címemet a figyelmeztető e-mailekben',
'tog-shownumberswatching'     => 'Az oldalt figyelő szerkesztők számának mutatása',
'tog-oldsig'                  => 'A jelenlegi aláírás előnézete:',
'tog-fancysig'                => 'Az aláírás wikiszöveg (nem lesz automatikusan hivatkozásba rakva)',
'tog-externaleditor'          => 'Külső szerkesztőprogram használata (Csak haladók számára, speciális beállításokra van szükség a számítógépen. [http://www.mediawiki.org/wiki/Manual:External_editors További információ angolul.])',
'tog-externaldiff'            => 'Külső diff program használata (Csak haladók számára, speciális beállításokra van szükség a számítógépen. [http://www.mediawiki.org/wiki/Manual:External_editors További információ angolul.])',
'tog-showjumplinks'           => 'Helyezzen el hivatkozást („Ugrás”) a beépített eszköztárra',
'tog-uselivepreview'          => 'Élő előnézet használata (JavaScript-alapú, kísérleti)',
'tog-forceeditsummary'        => 'Figyelmeztessen, ha nem adok meg szerkesztési összefoglalót',
'tog-watchlisthideown'        => 'Saját szerkesztések elrejtése',
'tog-watchlisthidebots'       => 'Robotok szerkesztéseinek elrejtése',
'tog-watchlisthideminor'      => 'Apró változtatások elrejtése',
'tog-watchlisthideliu'        => 'Bejelentkezett szerkesztők módosításainak elrejtése a figyelőlistáról',
'tog-watchlisthideanons'      => 'Névtelen szerkesztések elrejtése a figyelőlistáról',
'tog-watchlisthidepatrolled'  => 'Az ellenőrzött szerkesztések elrejtése a figyelőlistán',
'tog-nolangconversion'        => 'A változók átalakításának letiltása',
'tog-ccmeonemails'            => 'A másoknak küldött e-mailjeimről kapjak én is másolatot',
'tog-diffonly'                => 'Ne mutassa a lap tartalmát lapváltozatok közötti eltérések megtekintésekor',
'tog-showhiddencats'          => 'Rejtett kategóriák megjelenítése',
'tog-norollbackdiff'          => 'Ne jelenjenek meg az eltérések a visszaállítás után',

'underline-always'  => 'Mindig',
'underline-never'   => 'Soha',
'underline-default' => 'A böngésző alapértelmezése szerint',

# Font style option in Special:Preferences
'editfont-style'     => 'A szerkesztőterület betűtípusa:',
'editfont-default'   => 'a böngésző alapértelmezett betűtípusa',
'editfont-monospace' => 'fix szélességű betűtípus',
'editfont-sansserif' => 'talpatlan (sans-serif) betűtípus',
'editfont-serif'     => 'talpas (serif) betűtípus',

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
'sep'           => 'szept',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategória|Kategóriák}}',
'category_header'                => 'A(z) „$1” kategóriába tartozó lapok',
'subcategories'                  => 'Alkategóriák',
'category-media-header'          => 'A(z) „$1” kategóriába tartozó médiafájlok',
'category-empty'                 => "''Ebben a kategóriában pillanatnyilag egyetlen lap, médiafájl vagy alkategória sem szerepel.''",
'hidden-categories'              => '{{PLURAL:$1|Rejtett kategória|Rejtett kategóriák}}',
'hidden-category-category'       => 'Rejtett kategóriák',
'category-subcat-count'          => "''{{PLURAL:$2|Ennek a kategóriának csak egyetlen alkategóriája van.|Ez a kategória az alábbi {{PLURAL:$1|alkategóriával|$1 alkategóriával}} rendelkezik (összesen $2 alkategóriája van).}}''",
'category-subcat-count-limited'  => 'Ebben a kategóriában {{PLURAL:$1|egy|$1}} alkategória található.',
'category-article-count'         => '{{PLURAL:$2|Csak a következő lap található ebben a kategóriában.|Az összesen $2 lapból a következő $1-t listázza ez a kategóriaoldal, a többi a további oldalakon található.}}',
'category-article-count-limited' => 'Ebben a kategóriában a következő {{PLURAL:$1|lap|$1 lap}} található.',
'category-file-count'            => '{{PLURAL:$2|Csak a következő fájl található ebben a kategóriában.|Az összesen $2 fájlból a következő $1-t listázza ez a kategórialap, a többi a további oldalakon található.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Egy|$1}} fájl található ebben a kategóriában.',
'listingcontinuesabbrev'         => 'folyt.',
'index-category'                 => 'Indexelt lapok',
'noindex-category'               => 'Nem indexelt lapok',

'mainpagetext'      => "'''A MediaWiki telepítése sikeresen befejeződött.'''",
'mainpagedocfooter' => "Ha segítségre van szükséged a wikiszoftver használatához, akkor keresd fel a [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] oldalt.

== Alapok (angol nyelven) ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Beállítások listája]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki GyIK]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki-kiadások levelezőlistája]",

'about'         => 'Névjegy',
'article'       => 'Szócikk',
'newwindow'     => '(új ablakban nyílik meg)',
'cancel'        => 'Mégse',
'moredotdotdot' => 'Tovább…',
'mypage'        => 'Lapom',
'mytalk'        => 'Vitalapom',
'anontalk'      => 'az IP-címhez tartozó vitalap',
'navigation'    => 'Navigáció',
'and'           => '&#32;és',

# Cologne Blue skin
'qbfind'         => 'Keresés',
'qbbrowse'       => 'Böngészés',
'qbedit'         => 'Szerkesztés',
'qbpageoptions'  => 'Lapbeállítások',
'qbpageinfo'     => 'Lapinformáció',
'qbmyoptions'    => 'Lapjaim',
'qbspecialpages' => 'Speciális lapok',
'faq'            => 'GyIK',
'faqpage'        => 'Project:GyIK',

# Vector skin
'vector-action-addsection'       => 'Új szakasz nyitása',
'vector-action-delete'           => 'Törlés',
'vector-action-move'             => 'Átnevezés',
'vector-action-protect'          => 'Lapvédelem',
'vector-action-undelete'         => 'Visszaállítás',
'vector-action-unprotect'        => 'Védelem feloldása',
'vector-simplesearch-preference' => 'Továbbfejlesztett keresési javaslatok engedélyezése (csak Vector felületen)',
'vector-view-create'             => 'Létrehozás',
'vector-view-edit'               => 'Szerkesztés',
'vector-view-history'            => 'Laptörténet',
'vector-view-view'               => 'Olvasás',
'vector-view-viewsource'         => 'A lap forrása',
'actions'                        => 'Műveletek',
'namespaces'                     => 'Névterek',
'variants'                       => 'Változók',

'errorpagetitle'    => 'Hiba',
'returnto'          => 'Vissza a(z) $1 laphoz.',
'tagline'           => 'A {{SITENAME}} wikiből',
'help'              => 'Segítség',
'search'            => 'Keresés',
'searchbutton'      => 'Keresés',
'go'                => 'Menj',
'searcharticle'     => 'Menj',
'history'           => 'Laptörténet',
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
'undelete_short'    => '{{PLURAL:$1|Egy|$1}} szerkesztés helyreállítása',
'viewdeleted_short' => '{{PLURAL:$1|Egy|$1}} törölt szerkesztés megtekintése',
'protect'           => 'Lapvédelem',
'protect_change'    => 'módosítás',
'protectthispage'   => 'Lapvédelem',
'unprotect'         => 'Védelem ki',
'unprotectthispage' => 'Lapvédelem megszüntetése',
'newpage'           => 'Új lap',
'talkpage'          => 'Megbeszélés a lappal kapcsolatban',
'talkpagelinktext'  => 'vitalap',
'specialpage'       => 'Speciális lap',
'personaltools'     => 'Személyes eszközök',
'postcomment'       => 'Új szakasz',
'articlepage'       => 'Szócikk megtekintése',
'talk'              => 'Vitalap',
'views'             => 'Nézetek',
'toolbox'           => 'Eszközök',
'userpage'          => 'Felhasználó lapjának megtekintése',
'projectpage'       => 'Projektlap megtekintése',
'imagepage'         => 'A fájl leírólapjának megtekintése',
'mediawikipage'     => 'Üzenetlap megtekintése',
'templatepage'      => 'Sablon lapjának megtekintése',
'viewhelppage'      => 'Súgólap megtekintése',
'categorypage'      => 'Kategórialap megtekintése',
'viewtalkpage'      => 'Beszélgetés megtekintése',
'otherlanguages'    => 'Más nyelveken',
'redirectedfrom'    => '($1 szócikkből átirányítva)',
'redirectpagesub'   => 'Átirányító lap',
'lastmodifiedat'    => 'A lap utolsó módosítása: $1, $2',
'viewcount'         => 'Ezt a lapot {{PLURAL:$1|egy|$1}} alkalommal keresték fel.',
'protectedpage'     => 'Védett lap',
'jumpto'            => 'Ugrás:',
'jumptonavigation'  => 'navigáció',
'jumptosearch'      => 'keresés',
'view-pool-error'   => 'Sajnáljuk, de a szerverek jelenleg túl vannak terhelve.
Túl sok felhasználó próbálta megtekinteni ezt az oldalt.
Kérlek, várj egy kicsit, mielőtt újrapróbálkoznál a lap megtekintésével.

$1',
'pool-timeout'      => 'Letelt a várakozási idő a zároláshoz',
'pool-queuefull'    => 'A pool sor megtelt',
'pool-errorunknown' => 'Ismeretlen hiba',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A {{SITENAME}} wikiről',
'aboutpage'            => 'Project:Rólunk',
'copyright'            => 'A tartalom a(z) $1 feltételei szerint használható fel.',
'copyrightpage'        => '{{ns:project}}:Szerzői jogok',
'currentevents'        => 'Aktuális események',
'currentevents-url'    => 'Project:Friss események',
'disclaimers'          => 'Jogi nyilatkozat',
'disclaimerpage'       => 'Project:Jogi nyilatkozat',
'edithelp'             => 'Szerkesztési súgó',
'edithelppage'         => 'Help:Szerkesztés',
'helppage'             => 'Help:Tartalom',
'mainpage'             => 'Kezdőlap',
'mainpage-description' => 'Kezdőlap',
'policy-url'           => 'Project:Nyilatkozat',
'portal'               => 'Közösségi portál',
'portal-url'           => 'Project:Közösségi portál',
'privacy'              => 'Adatvédelmi irányelvek',
'privacypage'          => 'Project:Adatvédelmi irányelvek',

'badaccess'        => 'Engedélyezési hiba',
'badaccess-group0' => 'Ezt a tevékenységet nem végezheted el.',
'badaccess-groups' => 'Ezt a tevékenységet csak a(z) $1 {{PLURAL:$2|csoportba|csoportok valamelyikébe}} tartozó felhasználó végezheti el.',

'versionrequired'     => 'A MediaWiki $1-s verziója szükséges',
'versionrequiredtext' => 'A lap használatához a MediaWiki $1-s verziójára van szükség. Lásd a [[Special:Version|verzió]] lapot.',

'ok'                      => 'OK',
'retrievedfrom'           => 'A lap eredeti címe: „$1”',
'youhavenewmessages'      => 'Új üzenet vár $1! (Az üzenetet $2.)',
'newmessageslink'         => 'a vitalapodon',
'newmessagesdifflink'     => 'külön is megtekintheted',
'youhavenewmessagesmulti' => 'Új üzenetet vár a(z) $1 wikin',
'editsection'             => 'szerkesztés',
'editold'                 => 'szerkesztés',
'viewsourceold'           => 'lapforrás',
'editlink'                => 'szerkesztés',
'viewsourcelink'          => 'forráskód megtekintése',
'editsectionhint'         => 'Szakasz szerkesztése: $1',
'toc'                     => 'Tartalomjegyzék',
'showtoc'                 => 'megjelenítés',
'hidetoc'                 => 'elrejtés',
'collapsible-collapse'    => 'kinyit',
'collapsible-expand'      => 'becsuk',
'thisisdeleted'           => '$1 megtekintése vagy helyreállítása?',
'viewdeleted'             => '$1 megtekintése',
'restorelink'             => '{{PLURAL:$1|Egy|$1}} törölt szerkesztés',
'feedlinks'               => 'Hírcsatorna:',
'feed-invalid'            => 'Érvénytelen a figyelt hírcsatorna típusa.',
'feed-unavailable'        => 'Ezen wikin nincs elérhető hírcsatorna',
'site-rss-feed'           => '$1 RSS csatorna',
'site-atom-feed'          => '$1 Atom hírcsatorna',
'page-rss-feed'           => '„$1” RSS hírcsatorna',
'page-atom-feed'          => '„$1” Atom hírcsatorna',
'red-link-title'          => '$1 (a lap nem létezik)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Szócikk',
'nstab-user'      => 'Szerkesztői lap',
'nstab-media'     => 'Média',
'nstab-special'   => 'Speciális lap',
'nstab-project'   => 'Projektlap',
'nstab-image'     => 'Fájl',
'nstab-mediawiki' => 'Üzenet',
'nstab-template'  => 'Sablon',
'nstab-help'      => 'Segítség',
'nstab-category'  => 'Kategória',

# Main script and global functions
'nosuchaction'      => 'Nincs ilyen művelet',
'nosuchactiontext'  => 'Az URL-ben megadott műveletet érvénytelen.
Valószínűleg elgépelted, hibás hivatkozásra kattintottál, vagy a
a(z) {{SITENAME}} által használt szoftver hibája is lehet.',
'nosuchspecialpage' => 'Nem létezik ilyen speciális lap',
'nospecialpagetext' => '<strong>Érvénytelen speciális lapot akartál megtekinteni.</strong>

Az érvényes speciális lapok listáját a [[Special:SpecialPages|Speciális lapok]] oldalon találod.',

# General errors
'error'                => 'Hiba',
'databaseerror'        => 'Adatbázishiba',
'dberrortext'          => 'Szintaktikai hiba található az adatbázis-lekérdezésben.
Ez szoftverhiba miatt történhetett.
Az utolsó adatbázis-lekérdezés a(z) „<tt>$2</tt>” függvényből történt, és a következő volt:
<blockquote><tt>$1</tt></blockquote>
Az adatbázis ezzel a hibával tért vissza: „<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Szintaktikai hiba található az adatbázis-lekérdezésben.
Az utolsó adatbázis-lekérdezés a(z) „$2” függvényből történt, és a következő volt:
„$1”
Az adatbázis ezzel a hibával tért vissza: „$3: $4”.',
'laggedslavemode'      => 'Figyelem: Ez a lap nem feltétlenül tartalmazza a legfrissebb változtatásokat!',
'readonly'             => 'Az adatbázis le van zárva',
'enterlockreason'      => 'Add meg a lezárás okát, valamint egy becslést, hogy mikor kerül a lezárás feloldásra',
'readonlytext'         => 'A wiki adatbázisa ideiglenesen le van zárva (valószínűleg adatbázis-karbantartás miatt). A lezárás időtartama alatt a lapok nem szerkeszthetők, és új szócikkek sem hozhatóak létre, az oldalak azonban továbbra is böngészhetőek.

Az adminisztrátor, aki lezárta az adatbázist, az alábbi magyarázatot adta: $1',
'missing-article'      => 'Az adatbázisban nem található meg a(z) „$1” című lap szövege $2.

Ennek az oka általában az, hogy egy olyan lapra vonatkozó linket követtél, amit már töröltek.

Ha ez nem így van, lehet, hogy hibát találtál a szoftverben.
Jelezd ezt egy [[Special:ListUsers/sysop|adminiszttrátornak]] az URL megadásával.',
'missingarticle-rev'   => '(változat azonosítója: $1)',
'missingarticle-diff'  => '(eltérés: $1, $2)',
'readonly_lag'         => 'Az adatbázis automatikusan zárolásra került, amíg a mellékkiszolgálók utolérik a főkiszolgálót.',
'internalerror'        => 'Belső hiba',
'internalerror_info'   => 'Belső hiba: $1',
'fileappenderrorread'  => 'A(z) „$1” nem olvasható hozzáírás közben.',
'fileappenderror'      => 'Nem sikerült hozzáfűzni a(z) „$1” fájlt a(z) „$2” fájlhoz.',
'filecopyerror'        => 'Nem tudtam átmásolni a(z) „$1” fájlt „$2” névre.',
'filerenameerror'      => 'Nem tudtam átnevezni a(z) „$1” fájlt „$2” névre.',
'filedeleteerror'      => 'Nem tudtam törölni a(z) „$1” fájlt.',
'directorycreateerror' => 'Nem tudtam létrehozni a(z) „$1” könyvtárat.',
'filenotfound'         => 'A(z) „$1” fájl nem található.',
'fileexistserror'      => 'Nem tudtam írni a(z) „$1” fájlba: a fájl már létezik',
'unexpected'           => 'Váratlan érték: „$1”=„$2”.',
'formerror'            => 'Hiba: nem tudom elküldeni az űrlapot',
'badarticleerror'      => 'Ez a tevékenység nem végezhető el ezen a lapon.',
'cannotdelete'         => 'A(z) $1 lapot vagy fájlt nem lehet törölni.
Talán már valaki más törölte.',
'badtitle'             => 'Hibás cím',
'badtitletext'         => 'A kért oldal címe érvénytelen, üres, vagy rosszul hivatkozott nyelvközi vagy wikiközi cím volt. Olyan karaktereket is tartalmazhatott, melyek a címekben nem használhatóak.',
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
'protectedpagetext'    => 'Ez egy védett lap, nem szerkeszthető.',
'viewsourcetext'       => 'Megtekintheted és másolhatod a lap forrását:',
'protectedinterface'   => 'Ez a lap a szoftver felületéhez szolgáltat szöveget, és a visszaélések elkerülése miatt le van zárva.',
'editinginterface'     => "'''Vigyázat:''' egy olyan lapot szerkesztesz, ami a MediaWiki szoftver felületéthez tarzozik. A lap megváltoztatása hatással lesz más szerkesztők számára is. Fordításra inkább használd a MediaWiki fordítására indított kezdeményezést, a [http://translatewiki.net/wiki/Main_Page?setlang=hu translatewiki.net-et].",
'sqlhidden'            => '(rejtett SQL lekérdezés)',
'cascadeprotected'     => 'Ez a lap szerkesztés elleni védelemmel lett ellátva, mert a következő {{PLURAL:$1|lapon|lapokon}} be van kapcsolva a „kaszkádolt” védelem:
$2',
'namespaceprotected'   => "Nincs jogosultságod a(z) '''$1''' névtérben található lapok szerkesztésére.",
'customcssjsprotected' => 'Nincs jogosultságod a lap szerkesztéséhez, mert egy másik szerkesztő személyes beállításait tartalmazza.',
'ns-specialprotected'  => 'A speciális lapok nem szerkeszthetőek.',
'titleprotected'       => "Ilyen címmel nem lehet szócikket készíteni, [[User:$1|$1]] letiltotta.
A blokkolás oka: „''$2''”.",

# Virus scanner
'virus-badscanner'     => "Hibás beállítás: ismeretlen víruskereső: ''$1''",
'virus-scanfailed'     => 'az ellenőrzés nem sikerült (hibakód: $1)',
'virus-unknownscanner' => 'ismeretlen antivírus:',

# Login and logout pages
'logouttext'                 => "'''Sikeresen kijelentkeztél.'''

Folytathatod névtelenül  a(z) {{SITENAME}} használatát, vagy [[Special:UserLogin|ismét bejelentkezhetsz]] ugyanezzel, vagy egy másik névvel.
Lehetséges, hogy néhány oldalon továbbra is azt látod, be vagy jelentkezve, mindaddig, amíg nem üríted a böngésződ gyorsítótárát.",
'welcomecreation'            => '== Köszöntünk, $1! ==
A felhasználói fiókodat létrehoztuk.
Ne felejtsd el átnézni a [[Special:Preferences|személyes beállításaidat]].',
'yourname'                   => 'Szerkesztőneved:',
'yourpassword'               => 'Jelszavad:',
'yourpasswordagain'          => 'Jelszavad ismét:',
'remembermypassword'         => 'Emlékezzen rám ezen a számítógépen (legfeljebb $1 napig)',
'securelogin-stick-https'    => 'Kapcsolódás HTTPS-en keresztül bejelentkezés után is',
'yourdomainname'             => 'A domainneved:',
'externaldberror'            => 'Hiba történt a külső adatbázis hitelesítése közben, vagy nem vagy jogosult a külső fiókod frissítésére.',
'login'                      => 'Bejelentkezés',
'nav-login-createaccount'    => 'Bejelentkezés / fiók létrehozása',
'loginprompt'                => "Engedélyezned kell a sütiket (''cookie''), hogy bejelentkezhess a(z) {{SITENAME}} wikibe.",
'userlogin'                  => 'Bejelentkezés / fiók létrehozása',
'userloginnocreate'          => 'Bejelentkezés',
'logout'                     => 'Kijelentkezés',
'userlogout'                 => 'Kijelentkezés',
'notloggedin'                => 'Nem vagy bejelentkezve',
'nologin'                    => "Nem rendelkezel még felhasználói fiókkal? '''$1'''.",
'nologinlink'                => 'Itt regisztrálhatsz',
'createaccount'              => 'Regisztráció',
'gotaccount'                 => "Ha már korábban regisztráltál, '''$1'''!",
'gotaccountlink'             => 'Bejelentkezés',
'createaccountmail'          => 'e-mailben',
'createaccountreason'        => 'Indoklás:',
'badretype'                  => 'A megadott jelszavak nem egyeznek.',
'userexists'                 => 'A megadott szerkesztőnév már foglalt.
Kérlek, válassz másikat!',
'loginerror'                 => 'Hiba történt a bejelentkezés során',
'createaccounterror'         => 'Nem sikerült létrehozni a felhasználói fiókot: $1',
'nocookiesnew'               => 'A felhasználói fiókod létrejött, de nem vagy bejelentkezve. A wiki sütiket („cookie”) használ a szerkesztők azonosítására. Nálad ezek le vannak tiltva. Kérlek, engedélyezd őket, majd lépj be az új azonosítóddal és jelszavaddal.',
'nocookieslogin'             => 'A wiki sütiket („cookie”) használ a szerkesztők azonosításhoz.
Nálad ezek le vannak tiltva.
Engedélyezd őket, majd próbáld meg újra.',
'noname'                     => 'Érvénytelen szerkesztőnevet adtál meg.',
'loginsuccesstitle'          => 'Sikeres bejelentkezés',
'loginsuccess'               => "'''Most már be vagy jelentkezve a(z) {{SITENAME}} wikibe „$1” néven.'''",
'nosuchuser'                 => 'Nem létezik „$1” nevű szerkesztő.
A szerkesztőnevek kis- és nagybetű-érzékenyek.
Ellenőrizd, hogy helyesen írtad-e be, vagy [[Special:UserLogin/signup|hozz létre egy új fiókot]].',
'nosuchusershort'            => 'Nem létezik „<nowiki>$1</nowiki>” nevű szerkesztő.
Ellenőrizd, hogy helyesen írtad-e be.',
'nouserspecified'            => 'Meg kell adnod a felhasználói nevet.',
'login-userblocked'          => 'Ez a szerkesztő blokkolva van, a bejelentkezés nem engedélyezett.',
'wrongpassword'              => 'A megadott jelszó érvénytelen. Próbáld meg újra.',
'wrongpasswordempty'         => 'Nem adtál meg jelszót. Próbáld meg újra.',
'passwordtooshort'           => 'A jelszónak legalább {{PLURAL:$1|egy|$1}} karakterből kell állnia.',
'password-name-match'        => 'A jelszavadnak különböznie kell a szerkesztőnevedtől.',
'mailmypassword'             => 'Új jelszó küldése e-mailben',
'passwordremindertitle'      => 'Ideiglenes jelszó a(z) {{SITENAME}} wikire',
'passwordremindertext'       => 'Valaki (vélhetően te, a(z) $1 IP-címről) új jelszót kért a(z)
{{SITENAME}} wikis ($4) felhasználói fiókjához.
"$2" számára most egy ideiglenes jelszót készítettünk: "$3".
Ha te kértél új jelszót, lépj be, és változtasd meg.
Az ideiglenes jelszó {{PLURAL:$5|egy nap|$5 nap}} múlva érvényét veszti.

Ha nem te küldted a kérést, vagy közben eszedbe jutott a régi,
és már nem akarod megváltoztatni, nyugodtan hagyd figyelmen kívül
ezt az üzenetet, és használd továbbra is a régi jelszavadat.',
'noemail'                    => '„$1” e-mail címe nincs megadva.',
'noemailcreate'              => 'Meg kell adnod egy valós e-mail címet',
'passwordsent'               => 'Az új jelszót elküldtük „$1” e-mail címére.
Lépj be a levélben található adatokkal.',
'blocked-mailpassword'       => 'Az IP-címedet blokkoltuk, azaz eltiltottuk a szerkesztéstől, ezért a visszaélések elkerülése érdekében a jelszóvisszaállítás funkciót nem használhatod.',
'eauthentsent'               => 'Egy ellenőrző e-mailt küldtünk a megadott címre. Mielőtt más leveleket kaphatnál, igazolnod kell az e-mailben írt utasításoknak megfelelően, hogy valóban a tiéd a megadott cím.',
'throttled-mailpassword'     => 'Már elküldtünk egy jelszóemlékeztetőt az utóbbi {{PLURAL:$1|egy|$1}} órában.
A visszaélések elkerülése végett {{PLURAL:$1|egy|$1}} óránként csak egy jelszó-emlékeztetőt küldünk.',
'mailerror'                  => 'Hiba történt az e-mail küldése közben: $1',
'acct_creation_throttle_hit' => 'A wiki látogatói ezt az IP-címet használva {{PLURAL:$1|egy|$1}} fiókot hoztak létre az elmúlt egy nap alatt . Ez a megengedett maximum ezen időtartam alatt, így az erről a címről látogatók jelenleg nem hozhatnak létre újabb fiókokat.',
'emailauthenticated'         => 'Az e-mail címedet $2 $3-kor erősítetted meg.',
'emailnotauthenticated'      => 'Az e-mail címed még <strong>nincs megerősítve</strong>. E-mailek küldése és fogadása nem engedélyezett.',
'noemailprefs'               => 'Az alábbi funkciók használatához meg kell adnod az e-mail címedet.',
'emailconfirmlink'           => 'E-mail cím megerősítése',
'invalidemailaddress'        => 'A megadott e-mail cím érvénytelen formátumú. Kérlek, adj meg egy érvényes e-mail címet vagy hagyd üresen azt a mezőt.',
'accountcreated'             => 'Felhasználói fiók létrehozva',
'accountcreatedtext'         => '$1 felhasználói fiókja sikeresen létrejött.',
'createaccount-title'        => 'Új {{SITENAME}}-azonosító létrehozása',
'createaccount-text'         => 'Valaki létrehozott számodra egy "$2" nevű {{SITENAME}}-azonosítót ($4).
A hozzátartozó jelszó "$3", melyet a bejelentkezés után minél előbb változtass meg.

Ha nem kértél új azonosítót, és tévedésből kaptad ezt a levelet, nyugodtan hagyd figyelmen kívül.',
'usernamehasherror'          => 'A felhasználónév nem tartalmazhat hash karaktereket',
'login-throttled'            => 'Túl sok hibás bejelentkezés.
Várj egy kicsit, mielőtt újra próbálkozol.',
'loginlanguagelabel'         => 'Nyelv: $1',
'suspicious-userlogout'      => 'A kijelentkezési kérésed vissza lett utasítva, mert úgy tűnik, hogy egy hibás böngésző vagy gyorsítótárazó proxy küldte.',

# E-mail sending
'php-mail-error-unknown' => 'Ismeretlen hiba a PHP mail() függvényében',

# JavaScript password checks
'password-strength'            => 'Becsült jelszóerősség: $1',
'password-strength-bad'        => 'GYENGE',
'password-strength-mediocre'   => 'közepes',
'password-strength-acceptable' => 'elfogadható',
'password-strength-good'       => 'jó',
'password-retype'              => 'Jelszavad még egyszer:',
'password-retype-mismatch'     => 'A jelszavak nem egyeznek meg',

# Password reset dialog
'resetpass'                 => 'Jelszó módosítása',
'resetpass_announce'        => 'Az e-mailben elküldött ideiglenes kóddal jelentkeztél be. A bejelentkezés befejezéséhez meg kell megadnod egy új jelszót:',
'resetpass_text'            => '<!-- Ide írd a szöveget -->',
'resetpass_header'          => 'A fiókhoz tartozó jelszó megváltoztatása',
'oldpassword'               => 'Régi jelszó:',
'newpassword'               => 'Új jelszó:',
'retypenew'                 => 'Új jelszó ismét:',
'resetpass_submit'          => 'Add meg a jelszót és jelentkezz be',
'resetpass_success'         => 'A jelszavad megváltoztatása sikeresen befejeződött! Bejelentkezés...',
'resetpass_forbidden'       => 'A jelszavak nem változtathatóak meg',
'resetpass-no-info'         => 'Be kell jelentkezned hogy közvetlenül elérd ezt a lapot.',
'resetpass-submit-loggedin' => 'Jelszó megváltoztatása',
'resetpass-submit-cancel'   => 'Mégse',
'resetpass-wrong-oldpass'   => 'Nem megfelelő ideiglenes vagy jelenlegi jelszó.
Lehet, hogy már sikeresen megváltoztattad a jelszavad, vagy pedig időközben új ideiglenes jelszót kértél.',
'resetpass-temp-password'   => 'Ideiglenes jelszó:',

# Edit page toolbar
'bold_sample'     => 'Félkövér szöveg',
'bold_tip'        => 'Félkövér szöveg',
'italic_sample'   => 'Dőlt szöveg',
'italic_tip'      => 'Dőlt szöveg',
'link_sample'     => 'Belső hivatkozás',
'link_tip'        => 'Belső hivatkozás',
'extlink_sample'  => 'http://www.example.com hivatkozás címe',
'extlink_tip'     => 'Külső hivatkozás (ne felejtsd el a http:// előtagot)',
'headline_sample' => 'Alfejezet címe',
'headline_tip'    => 'Alfejezetcím',
'math_sample'     => 'Ide írd a képletet',
'math_tip'        => 'Matematikai képlet (LaTeX)',
'nowiki_sample'   => 'Ide írd a formázatlan szöveget',
'nowiki_tip'      => 'Wiki formázás kikapcsolása',
'image_sample'    => 'Pelda.jpg',
'image_tip'       => 'Fájl (pl. kép) beszúrása',
'media_sample'    => 'Peldaegyketto.ogg',
'media_tip'       => 'Fájlhivatkozás',
'sig_tip'         => 'Aláírás időponttal',
'hr_tip'          => 'Vízszintes vonal (ritkán használd)',

# Edit pages
'summary'                          => 'Összefoglaló:',
'subject'                          => 'Téma/főcím:',
'minoredit'                        => 'Apró változtatás',
'watchthis'                        => 'A lap figyelése',
'savearticle'                      => 'Lap mentése',
'preview'                          => 'Előnézet',
'showpreview'                      => 'Előnézet megtekintése',
'showlivepreview'                  => 'Élő előnézet',
'showdiff'                         => 'Változtatások megtekintése',
'anoneditwarning'                  => "'''Figyelem:''' Nem vagy bejelentkezve, ha szerkesztesz, az IP-címed látható lesz a laptörténetben.",
'anonpreviewwarning'               => "''Nem vagy bejelentkezve. A mentéskor az IP-címed rögzítve lesz a laptörténetben.''",
'missingsummary'                   => "'''Emlékeztető:''' Nem adtál meg szerkesztési összefoglalót. Ha összefoglaló nélkül akarod elküldeni a szöveget, kattints újra a mentésre.",
'missingcommenttext'               => 'Kérjük, hogy írj összefoglalót szerkesztésedhez.',
'missingcommentheader'             => "'''Emlékeztető:''' Nem adtad meg a megjegyzés tárgyát vagy címét.
Ha ismét a „{{int:savearticle}}” gombra kattintasz, akkor a szerkesztésed nélküle kerül mentésre.",
'summary-preview'                  => 'A szerkesztési összefoglaló előnézete:',
'subject-preview'                  => 'A téma/főcím előnézete:',
'blockedtitle'                     => 'A szerkesztő blokkolva van',
'blockedtext'                      => "'''A szerkesztőnevedet vagy az IP-címedet blokkoltuk.'''

A blokkolást $1 végezte el.
Az általa felhozott indok: ''$2''.

* A blokk kezdete: $8
* A blokk lejárata: $6
* Blokkolt szerkesztő: $7

Kapcsolatba léphetsz $1 szerkesztőnkkel, vagy egy másik [[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]], és megbeszélheted vele a blokkolást.
Az 'E-mail küldése ennek a szerkesztőnek' funkciót csak akkor használhatod, ha érvényes e-mail címet adtál meg
[[Special:Preferences|fiókbeállításaidban]], és nem blokkolták a használatát.
Jelenlegi IP-címed: $3, a blokkolás azonosítószáma: #$5.
Kérjük, hogy érdeklődés esetén mindkettőt add meg.",
'autoblockedtext'                  => "Az IP-címed automatikusan blokkolva lett, mert korábban egy olyan szerkesztő használta, akit $1 blokkolt, az alábbi indoklással:

:''$2''

*A blokk kezdete: '''$8'''
*A blokk lejárata: '''$6'''
*Blokkolt szerkesztő: '''$7'''

Kapcsolatba léphetsz $1 szerkesztőnkkel, vagy egy másik [[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]], és megbeszélheted vele a blokkolást.

Az 'E-mail küldése ennek a szerkesztőnek' funkciót csak akkor használhatod, ha érvényes e-mail címet adtál meg
[[Special:Preferences|fiókbeállításaidban]], és nem blokkolták a használatát.

Jelenlegi IP-címed: $3, a blokkolás azonosítószáma: #$5.
Kérjük, hogy érdeklődés esetén mindkettőt add meg.",
'blockednoreason'                  => 'nem adott meg okot',
'blockedoriginalsource'            => "A(z) '''$1''' lap forráskódja:",
'blockededitsource'                => "A(z) '''$1''' lapon '''végrehajtott szerkesztésed''' szövege:",
'whitelistedittitle'               => 'A lap szerkesztéséhez be kell jelentkezned',
'whitelistedittext'                => 'Lapok szerkesztéséhez $1.',
'confirmedittext'                  => 'Lapok szerkesztése előtt meg kell erősítened az e-mail címedet. Kérjük, hogy a [[Special:Preferences|szerkesztői beállításaidban]] add meg, majd erősítsd meg az e-mail címedet.',
'nosuchsectiontitle'               => 'A szakasz nem található',
'nosuchsectiontext'                => 'Egy olyan szakaszt próbáltál meg szerkeszteni, ami nem létezik.
Lehet, hogy áthelyezték vagy törölték miközben nézted a lapot.',
'loginreqtitle'                    => 'Bejelentkezés szükséges',
'loginreqlink'                     => 'be kell jelentkezned',
'loginreqpagetext'                 => '$1 más oldalak megtekintéséhez.',
'accmailtitle'                     => 'Elküldtük a jelszót.',
'accmailtext'                      => "A(z) [[User talk:$1|$1]] fiókhoz egy véletlenszerűen generált jelszót küldünk a(z) $2 címre.

Az új fiók jelszava a ''[[Special:ChangePassword|jelszó megváltoztatása]]'' lapon módosítható a bejelentkezés után.",
'newarticle'                       => '(Új)',
'newarticletext'                   => "Egy olyan lapra mutató hivatkozást követtél, ami még nem létezik.
A lap létrehozásához csak gépeld be a szövegét a lenti szövegdobozba. Ha kész vagy, az „Előnézet megtekintése” gombbal ellenőrizheted, hogy úgy fog-e kinézni, ahogy szeretnéd, és a „Lap mentése” gombbal tudod elmenteni. (További információkat a [[{{MediaWiki:Helppage}}|súgólapon]] találsz).
Ha tévedésből jutottál ide, kattints a böngésződ '''vissza''' vagy '''back''' gombjára.",
'anontalkpagetext'                 => "----''Ez egy olyan anonim szerkesztő vitalapja, aki még nem regisztrált, vagy csak nem jelentkezett be.
Ezért az IP-címét használjuk az azonosítására.
Ugyanazon az IP-címen számos szerkesztő osztozhat az idők folyamán.
Ha úgy látod, hogy az üzenetek, amiket ide kapsz, nem neked szólnak, [[Special:UserLogin/signup|regisztrálj]] vagy ha már regisztráltál, [[Special:UserLogin|jelentkezz be]], hogy ne keverjenek össze másokkal.''",
'noarticletext'                    => 'Ez a lap jelenleg nem tartalmaz szöveget.
[[Special:Search/{{PAGENAME}}|Rákereshetsz erre a címszóra]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} megtekintheted a kapcsolódó naplókat],
vagy [{{fullurl:{{FULLPAGENAME}}|action=edit}} szerkesztheted a lapot].</span>',
'noarticletext-nopermission'       => 'Ez a lap jelenleg nem tartalmaz szöveget.
[[Special:Search/{{PAGENAME}}|Rákereshetsz a lap címére]] más lapok tartalmában, vagy <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} megtekintheted a kapcsolódó naplófájlokat]</span>.',
'userpage-userdoesnotexist'        => 'Nincs „$1” nevű regisztrált felhasználónk.
Nézd meg, hogy valóban ezt a lapot szeretnéd-e létrehozni vagy szerkeszteni.',
'userpage-userdoesnotexist-view'   => 'Nincs regisztrálva „$1” szerkesztői azonosító.',
'blocked-notice-logextract'        => 'A felhasználó jelenleg blokkolva van.
A blokkolási napló legutóbbi ide vonatkozó bejegyzése a következő:',
'clearyourcache'                   => "'''Megjegyzés: mentés után frissítened kell a böngésződ gyorsítótárát, hogy lásd a változásokat.'''
'''Mozilla''' / '''Firefox''' / '''Safari:''' tartsd lenyomva a Shift gombot és kattints a ''Frissítés'' gombra az eszköztáron, vagy használd a ''Ctrl–F5'' billentyűkombinációt (Apple Mac-en ''Cmd–Shift–R'');
'''Konqueror:''' egyszerűen csak kattints a ''Frissítés'' gombra vagy nyomj ''F5''-öt;
'''Opera:''' ürítsd ki a gyorsítótárat a ''Beállítások / Haladó / Előzmények→Törlés most'' gombbal, majd frissítsd az oldalt;
'''Internet Explorer:''' tartsd nyomva a ''Ctrl''-t, és kattints a ''Frissítés'' gombra, vagy nyomj ''Ctrl–F5''-öt.",
'usercssyoucanpreview'             => "'''Tipp:''' mentés előtt használd az „{{int:showpreview}}” gombot az új CSS-ed teszteléséhez.",
'userjsyoucanpreview'              => "'''Tipp:''' mentés előtt használd az „{{int:showpreview}}” gombot az új JavaScipted teszteléséhez.",
'usercsspreview'                   => "'''Ne felejtsd el, hogy ez csak a felhasználói CSS-ed előnézete és még nincs elmentve!'''",
'userjspreview'                    => "'''Ne felejtsd el, hogy még csak teszteled a felhasználói JavaScriptedet, és még nincs elmentve!'''",
'sitecsspreview'                   => "'''Ne feledd, hogy csak a CSS előnézetét látod.'''
'''Még nincs elmentve!'''",
'sitejspreview'                    => "'''Ne feledd, hogy a JavaScript-kódnak csak az előnézetét látod.'''
'''Még nincs elmentve!'''",
'userinvalidcssjstitle'            => "'''Figyelem:''' Nincs „$1” nevű felület. A felületekhez tartozó .css/.js oldalak kisbetűvel kezdődnek, például ''{{ns:user}}:Gipsz Jakab/vector.css'' és nem ''{{ns:user}}:Gipsz Jakab/Vector.css''.",
'updated'                          => '(frissítve)',
'note'                             => "'''Megjegyzés:'''",
'previewnote'                      => "'''Ne feledd, hogy ez csak előnézet, a változtatásaid még nincsenek elmentve!'''",
'previewconflict'                  => 'Ez az előnézet a felső szerkesztődobozban levő szöveg mentés utáni megfelelőjét mutatja.',
'session_fail_preview'             => "'''Az elveszett munkamenetadatok miatt sajnos nem tudtuk feldolgozni a szerkesztésedet.
Kérjük próbálkozz újra!
Amennyiben továbbra sem sikerül, próbálj meg [[Special:UserLogout|kijelentkezni]], majd ismét bejelentkezni!'''",
'session_fail_preview_html'        => "'''Az elveszett munkamenetadatok miatt sajnos nem tudtuk feldolgozni a szerkesztésedet.'''

''Mivel a wikiben engedélyezett a nyers HTML-kód használata, az előnézet el van rejtve a JavaScript-alapú támadások megakadályozása céljából.''

'''Ha ez egy normális szerkesztési kísérlet, akkor próbálkozz újra. Amennyiben továbbra sem sikerül, próbálj meg [[Special:UserLogout|kijelentkezni]], majd ismét bejelentkezni!'''",
'token_suffix_mismatch'            => "'''A szerkesztésedet elutasítottuk, mert a kliensprogramod megváltoztatta a központozó karaktereket
a szerkesztési tokenben. A szerkesztés azért lett visszautasítva, hogy megelőzzük a lap szövegének sérülését.
Ez a probléma akkor fordulhat elő, ha hibás, web-alapú proxyszolgáltatást használsz.'''",
'editing'                          => '$1 szerkesztése',
'editingsection'                   => '$1 szerkesztése (szakasz)',
'editingcomment'                   => '$1 szerkesztése (új szakasz)',
'editconflict'                     => 'Szerkesztési ütközés: $1',
'explainconflict'                  => "Valaki megváltoztatta a lapot, mióta elkezdted szerkeszteni.
A felső szövegdobozban láthatod az oldal jelenlegi tartalmát.
A te módosításaid az alsó dobozban találhatóak.
Át kell másolnod a módosításaidat a felsőbe.
'''Csak''' a felső dobozban levő szöveg lesz elmentve, amikor a „{{int:savearticle}}” gombra kattintasz.",
'yourtext'                         => 'A te változatod',
'storedversion'                    => 'A tárolt változat',
'nonunicodebrowser'                => "'''Figyelem: A böngésződ nem Unicode kompatibilis. Egy kerülő megoldásként biztonságban szerkesztheted a cikkeket: a nem ASCII karakterek a szerkesztőablakban hexadeciális kódokként jelennek meg.'''",
'editingold'                       => "'''FIGYELMEZTETÉS: A lap egy elavult változatát szerkeszted.
Ha elmented, akkor az ezen változat után végzett összes módosítás elvész.'''",
'yourdiff'                         => 'Eltérések',
'copyrightwarning'                 => "Vedd figyelembe, hogy a {{SITENAME}} wikin végzett összes módosítás a(z) $2 alatt jelenik meg (lásd a(z) $1 lapot a részletekért). Ha nem akarod, hogy az írásodat módosítsák vagy továbbterjesszék, akkor ne küldd be.<br />
Azt is megígéred, hogy ezt magadtól írtad, vagy egy közkincsből vagy más szabad forrásból másoltad.
'''NE KÜLDJ BE JOGVÉDETT MUNKÁT ENGEDÉLY NÉLKÜL!'''",
'copyrightwarning2'                => "Vedd figyelembe, hogy a {{SITENAME}} wikin végzett összes módosítást szerkeszthetik, módosíthatják vagy eltávolíthatják más szerkesztők.
Ha nem akarod, hogy az írásodat módosítsák, akkor ne küldd be.<br />
Azt is megígéred, hogy ezt magadtól írtad, vagy egy közkincsből vagy más szabad forrásból másoltad (lásd a(z) $1 lapot a részletekért).
'''NE KÜLDJ BE JOGVÉDETT MUNKÁT ENGEDÉLY NÉLKÜL!'''",
'longpageerror'                    => "'''HIBA: Az általad beküldött szöveg $1 kilobájt hosszú, ami több az engedélyezett $2 kilobájtnál.
A szerkesztést nem lehet elmenteni.'''",
'readonlywarning'                  => "'''FIGYELMEZTETÉS: A wiki adatbázisát karbantartás miatt zárolták, ezért most nem fogod tudni elmenteni a szerkesztéseidet.
A lap szöveget kimásolhatod egy szövegfájlba, amit elmenthetsz későbbre.'''

Az adatbázist lezáró adminisztrátor az alábbi magyarázatot adta: $1",
'protectedpagewarning'             => "'''Figyelem: Ez a lap le van védve, így csak adminisztrátori jogosultságokkal rendelkező szerkesztők módosíthatják.'''
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'semiprotectedpagewarning'         => "'''Megjegyzés:''' ez a lap védett, így regisztrálatlan, vagy újonnan regisztrált szerkesztők nem módosíthatják.",
'cascadeprotectedwarning'          => "'''Figyelem:''' ez a lap le van zárva, csak adminisztrátorok szerkeszthetik, mert a következő kaszkádvédelemmel ellátott {{PLURAL:$1|lapon|lapokon}} szerepel beillesztve:",
'titleprotectedwarning'            => "'''Figyelem: Ez a lap le van védve, így csak a [[Special:ListGroupRights|megfelelő jogosultságokkal]] rendelkező szerkesztők hozhatják létre.'''
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'templatesused'                    => 'A lapon használt {{PLURAL:$1|sablon|sablonok}}:',
'templatesusedpreview'             => 'Az előnézet megjelenítésekor használt {{PLURAL:$1|sablon|sablonok}}:',
'templatesusedsection'             => 'Az ebben a szakaszban használt {{PLURAL:$1|sablon|sablonok}}:',
'template-protected'               => '(védett)',
'template-semiprotected'           => '(félig védett)',
'hiddencategories'                 => 'Ez a lap {{PLURAL:$1|egy|$1}} rejtett kategóriába tartozik:',
'edittools'                        => '<!-- Ez a szöveg a szerkesztés és a feltöltés űrlap alatt lesz látható. -->',
'nocreatetitle'                    => 'Az oldallétrehozás korlátozva van',
'nocreatetext'                     => 'A(z) {{SITENAME}} wikin korlátozták az új oldalak létrehozásának lehetőségét.
Visszamehetsz és szerkeszthetsz egy létező lapot, valamint [[Special:UserLogin|bejelentkezhetsz vagy készíthetsz egy felhasználói fiókot]].',
'nocreate-loggedin'                => 'Nincs jogosultságod új lapokat létrehozni.',
'sectioneditnotsupported-title'    => 'A szakaszszerkesztés nem támogatott',
'sectioneditnotsupported-text'     => 'Ezen a lapon nem támogatott a szakaszok szerkesztése',
'permissionserrors'                => 'Engedélyezési hiba',
'permissionserrorstext'            => 'A művelet elvégzése nem engedélyezett a számodra, a következő {{PLURAL:$1|ok|okok}} miatt:',
'permissionserrorstext-withaction' => 'Nincs jogosultságod a következő művelet elvégzéséhez: $2, a következő {{PLURAL:$1|ok|okok}} miatt:',
'recreate-moveddeleted-warn'       => "'''Vigyázat: egy olyan lapot akarsz létrehozni, amit korábban már töröltek.'''

Mielőtt létrehoznád, nézd meg, miért került törölték és ellenőrizd,
hogy a törlés indoka nem érvényes-e még. A törlési és átnevezési naplókban a lapról az alábbi bejegyzések szerepelnek:",
'moveddeleted-notice'              => 'Az oldal korábban törölve lett.
A lap törlési és átnevezési naplója alább olvasható.',
'log-fulllog'                      => 'Teljes napló megtekintése',
'edit-hook-aborted'                => 'A szerkesztés meg lett szakítva egy hook által.
Nem lett magyarázat csatolva.',
'edit-gone-missing'                => 'Nem lehet frissíteni a lapot.
Úgy tűnik, hogy törölve lett.',
'edit-conflict'                    => 'Szerkesztési ütközés.',
'edit-no-change'                   => 'A szerkesztésed figyelmen kívül lett hagyva, mivel nem változtattál a lap szövegén.',
'edit-already-exists'              => 'Az új lap nem készíthető el.
Már létezik.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Figyelem: ezen a lapon túl sok erőforrásigényes elemzőfüggvény-hívás található.

Kevesebb, mint {{PLURAL:$2|egy|$2}} kellene, jelenleg {{PLURAL:$1|egy|$1}} van.',
'expensive-parserfunction-category'       => 'Túl sok költséges elemzőfüggvény-hívást tartalmazó lapok',
'post-expand-template-inclusion-warning'  => 'Figyelem: a beillesztett sablonok mérete túl nagy.
Néhány sablon nem fog megjelenni.',
'post-expand-template-inclusion-category' => 'Lapok, melyeken a beillesztett sablon mérete meghaladja a megengedett méretet',
'post-expand-template-argument-warning'   => 'Figyelem: Ez a lap legalább egy olyan sablonparamétert tartalmaz, amely kibontva túl nagy, így el lett(ek) hagyva.',
'post-expand-template-argument-category'  => 'Elhagyott sablonparaméterekkel rendelkező lapok',
'parser-template-loop-warning'            => 'Végtelen ciklus a következő sablonban: [[$1]]',
'parser-template-recursion-depth-warning' => 'A sablon rekurzív beillesztésének mélysége átlépte a határérékét ($1)',
'language-converter-depth-warning'        => 'A nyelvátalakító rekurzióinak száma túllépve ($1)',

# "Undo" feature
'undo-success' => 'A szerkesztés visszavonható. Kérlek ellenőrizd alább a változásokat, hogy valóban ezt szeretnéd-e tenni, majd kattints a lap mentése gombra a visszavonás véglegesítéséhez.',
'undo-failure' => 'A szerkesztést nem lehet automatikusan visszavonni vele ütköző későbbi szerkesztések miatt.',
'undo-norev'   => 'A szerkesztés nem állítható vissza, mert nem létezik vagy törölve lett.',
'undo-summary' => 'Visszavontam [[Special:Contributions/$2|$2]] ([[User talk:$2|vita]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]]) szerkesztését (oldid: $1)',

# Account creation failure
'cantcreateaccounttitle' => 'Felhasználói fiók létrehozása sikertelen',
'cantcreateaccount-text' => "Erről az IP-címről ('''$1''') nem lehet regisztrálni, mert [[User:$3|$3]] blokkolta az alábbi indokkal:

:''$2''",

# History pages
'viewpagelogs'           => 'A lap a rendszernaplókban',
'nohistory'              => 'A lap nem rendelkezik laptörténettel.',
'currentrev'             => 'Aktuális változat',
'currentrev-asof'        => 'A lap jelenlegi, $1-kori változata',
'revisionasof'           => 'A lap $1-kori változata',
'revision-info'          => 'A lap korábbi változatát látod, amilyen $2 $1-kor történt szerkesztése után volt.',
'previousrevision'       => '←Régebbi változat',
'nextrevision'           => 'Újabb változat→',
'currentrevisionlink'    => 'Aktuális változat',
'cur'                    => 'akt',
'next'                   => 'következő',
'last'                   => 'előző',
'page_first'             => 'első',
'page_last'              => 'utolsó',
'histlegend'             => 'Eltérések kijelölése: jelöld ki az összehasonlítandó változatokat, majd nyomd meg az Enter billentyűt, vagy az alul lévő gombot.<br />
Jelmagyarázat: (akt) = eltérés az aktuális változattól, (előző) = eltérés az előző változattól, a = apró szerkesztés',
'history-fieldset-title' => 'Keresés a laptörténetben',
'history-show-deleted'   => 'Csak a törölt változatok',
'histfirst'              => 'legelső',
'histlast'               => 'legutolsó',
'historysize'            => '({{PLURAL:$1|egy|$1}} bájt)',
'historyempty'           => '(üres)',

# Revision feed
'history-feed-title'          => 'Laptörténet',
'history-feed-description'    => 'Az oldal laptörténete a wikiben',
'history-feed-item-nocomment' => '$1, $2-n',
'history-feed-empty'          => 'A kért oldal nem létezik.
Lehet, hogy törölték a wikiből, vagy átnevezték.
Próbálkozhatsz a témával kapcsolatos lapok [[Special:Search|keresésével]].',

# Revision deletion
'rev-deleted-comment'         => '(megjegyzés eltávolítva)',
'rev-deleted-user'            => '(szerkesztőnév eltávolítva)',
'rev-deleted-event'           => '(bejegyzés eltávolítva)',
'rev-deleted-user-contribs'   => '[felhasználónév vagy IP-cím eltávolítva – szerkesztés elrejtve a közreműködések közül]',
'rev-deleted-text-permission' => "A lap ezen változatát '''törölték'''.
További információkat a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-deleted-text-unhide'     => "A lap ezen változatát '''törölték'''.
További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.
Mivel adminisztrátor vagy, még mindig [$1 megtekintheted a tartalmát], ha szeretnéd.",
'rev-suppressed-text-unhide'  => "A lap ezen változatát '''elrejtették'''.
További részleteket az [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} elrejtési naplóban] találhatsz.
Mivel adminisztrátor vagy, még mindig [$1 megtekintheted a tartalmát], ha szeretnéd.",
'rev-deleted-text-view'       => "A lap ezen változatát '''törölték'''.
Adminisztrátorként megnézheted; további részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-suppressed-text-view'    => "A lap ezen változatát '''elrejtették'''.
Mivel adminisztrátor vagy, még mindig megtekintheted.
További részleteket az [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} elrejtési naplóban] találhatsz.",
'rev-deleted-no-diff'         => "Nem nézheted meg a két változat közötti eltérést, mert a változatok egyikét '''törölték'''.
További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-suppressed-no-diff'      => "Nem nézheted meg ezt a változtatást, mert az egyik változatot '''törölték'''.",
'rev-deleted-unhide-diff'     => "A változatok közötti eltéréshez kiválasztott változatok egyike '''törölve''' lett.
További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.
Mivel adminisztrátor vagy, még mindig [$1 megtekintheted a változatok közötti eltérést], ha szeretnéd.",
'rev-suppressed-unhide-diff'  => "A változatok közötti eltéréshez kiválasztott változatok egyike '''el lett rejtve'''.
Részleteket az [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} elrejtési naplójában találhatsz].
Mivel adminisztrátor vagy, még mindig [$1 megtekintheted a változatok közötti eltérést], ha szeretnéd.",
'rev-deleted-diff-view'       => "A változatok közötti eltéréshez kiválasztott változatok egyike '''törölve''' lett.
Mivel adminisztrátor vagy, még mindig megtekintheted a változatok közötti eltérést; további részleteket pedig a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-suppressed-diff-view'    => "A változatok közötti eltéréshez kiválasztott változatok egyike '''el lett rejtve'''.
Mivel adminisztrátor vagy, még mindig megtekintheted a változatok közötti eltérést; további részleteket pedig az [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} elrejtési naplójában találhatsz].",
'rev-delundel'                => 'megjelenítés/elrejtés',
'rev-showdeleted'             => 'megjelenítés',
'revisiondelete'              => 'Változatok törlése vagy helyreállítása',
'revdelete-nooldid-title'     => 'Érvénytelen célváltozat',
'revdelete-nooldid-text'      => 'Nem adtad meg a célváltozato(ka)t, a megadott változat nem létezik,
vagy a legutolsó változatot próbáltad meg elrejteni.',
'revdelete-nologtype-title'   => 'Nem adtad meg a napló típusát',
'revdelete-nologtype-text'    => 'Nem adtad meg, hogy melyik naplón szeretnéd elvégezni a műveletet.',
'revdelete-nologid-title'     => 'Érvénytelen naplóbejegyzés',
'revdelete-nologid-text'      => 'Nem adtad meg azt a naplóbejegyzést, amin el szeretnéd végezni a műveletet, vagy olyat adtál meg, ami nem létezik.',
'revdelete-no-file'           => 'A megadott fájl nem létezik.',
'revdelete-show-file-confirm' => 'Biztosan meg szeretnéd nézni a(z) „<nowiki>$1</nowiki>” $2, $3-i törölt változatát?',
'revdelete-show-file-submit'  => 'Igen',
'revdelete-selected'          => "'''A(z) [[:$1]] lap {{PLURAL:$2|kiválasztott változata|kiválasztott változatai}}:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Kiválasztott naplóesemény|Kiválasztott naplóesemények}}:'''",
'revdelete-text'              => "'''A törölt változatok és események továbbra is megjelennek a laptörténetben és a naplókban,
azonban a tartalmuk nem lesz mindenki számára hozzáférhető.'''
A(z) {{SITENAME}} adminisztrátorai továbbra is meg tudják tekinteni az elrejtett tartalmat, és helyre tudják állítani ugyanezen a felületen keresztül, amennyiben nincs további korlátozás beállítva.",
'revdelete-confirm'           => 'Kérlek erősítsd meg, hogy valóban ezt szeretnéd tenni; megértetted a következményeket, és amit teszel, az összhangban van [[{{MediaWiki:Policy-url}}|az irányelvekkel]].',
'revdelete-suppress-text'     => "Az elrejtés '''csak''' a következő esetekben használható:
* Illetlen személyes információk
*: ''otthoni címek és telefonszámok, társadalombiztosítási számok stb.''",
'revdelete-legend'            => 'Korlátozások megadása:',
'revdelete-hide-text'         => 'Változat szövegének elrejtése',
'revdelete-hide-image'        => 'A fájl tartalomának elrejtése',
'revdelete-hide-name'         => 'Művelet és cél elrejtése',
'revdelete-hide-comment'      => 'Összefoglaló elrejtése',
'revdelete-hide-user'         => 'A szerkesztő felhasználónevének/IP-címének elrejtése',
'revdelete-hide-restricted'   => 'Adatok elrejtése az adminisztrátorok és mindenki más elől',
'revdelete-radio-same'        => '(nincs változtatás)',
'revdelete-radio-set'         => 'Igen',
'revdelete-radio-unset'       => 'Nem',
'revdelete-suppress'          => 'Adatok elrejtése az adminisztrátorok és a többi felhasználó elől is',
'revdelete-unsuppress'        => 'Korlátozások eltávolítása a visszaállított változatokról',
'revdelete-log'               => 'Ok:',
'revdelete-submit'            => 'Alkalmazás a kiválasztott {{PLURAL:$1|változatra|változatokra}}',
'revdelete-logentry'          => 'módosította a(z) [[$1]] lap egy vagy több változatának láthatóságát',
'logdelete-logentry'          => '[[$1]] eseményének láthatóságának módosítása',
'revdelete-success'           => "'''A változat láthatósága sikeresen frissítve.'''",
'revdelete-failure'           => "'''Nem sikerült frissíteni a változat láthatóságát:'''
$1",
'logdelete-success'           => "'''Az esemény láthatóságának beállítása sikeresen elvégezve.'''",
'logdelete-failure'           => "'''Nem sikerült módosítani a naplóbejegyzés láthatóságát:'''
$1",
'revdel-restore'              => 'Láthatóság megváltoztatása',
'revdel-restore-deleted'      => 'törölt lapváltozatok',
'revdel-restore-visible'      => 'látható lapváltozatok',
'pagehist'                    => 'Laptörténet',
'deletedhist'                 => 'Törölt változatok',
'revdelete-content'           => 'a tartalmát',
'revdelete-summary'           => 'a szerkesztési összefoglalóját',
'revdelete-uname'             => 'a szerkesztőjének nevét',
'revdelete-restricted'        => 'elrejtett az adminisztrátorok elől',
'revdelete-unrestricted'      => 'felfedett az adminisztrátoroknak',
'revdelete-hid'               => 'elrejtette $1',
'revdelete-unhid'             => 'felfedte $1',
'revdelete-log-message'       => '$1 {{PLURAL:$1|egy|$2}} változatnak',
'logdelete-log-message'       => '$1 {{PLURAL:$2|egy|$2}} eseményt',
'revdelete-hide-current'      => 'Nem sikerült elrejteni a $1 $2-kori elemet: ez a jelenlegi változat, amit nem lehet elrejteni.',
'revdelete-show-no-access'    => 'Nem lehet megjeleníteni a $2 $1-kori elemet, mert „korlátozottnak” van jelölve.',
'revdelete-modify-no-access'  => 'Nem lehet módosítani a $2 $1-kori elemet, mert „korlátozottnak” van jelölve.',
'revdelete-modify-missing'    => 'Nem sikerült módosítani a(z) $1 azonosítójú elemet, mert hiányzik az adatbázisból.',
'revdelete-no-change'         => "'''Figyelem:''' a(z) $1 $2-kori elem már rendelkezik a kért láthatósági beállításokkal.",
'revdelete-concurrent-change' => 'Hiba történt a(z) $1 $2-kori elem módosítása közben: úgy tűnik, valaki megváltoztatta az állapotát, miközben módosítani próbáltad.
Ellenőrizd a naplókat.',
'revdelete-only-restricted'   => 'Hiba a(z) $1 $2 időbélyegű elem elrejtésekor: nem rejthetsz el az adminisztrátorok elől elemeket anélkül, hogy ne választanál ki egy másik elrejtési beállítást.',
'revdelete-reason-dropdown'   => '*Általános törlési okok
** Jogsértő tartalom
** Kényes személyes információk',
'revdelete-otherreason'       => 'Más/további ok:',
'revdelete-reasonotherlist'   => 'Más ok',
'revdelete-edit-reasonlist'   => 'Törlési okok szerkesztése',
'revdelete-offender'          => 'Változat szerzője:',

# Suppression log
'suppressionlog'     => 'Adatvédelmibiztos-napló',
'suppressionlogtext' => 'Lenn látható az adminisztrátorok elől legutóbb elrejtett törlések és blokkok listája. Lásd a [[Special:IPBlockList|blokkolt IP-címek listája]] lapot a jelenleg érvényben lévő kitiltásokhoz és blokkokhoz.',

# Revision move
'moverevlogentry'              => 'áthelyezett {{PLURAL:$3|egy|$3}} lapváltozatot a(z) $1 lapról $2 lapra',
'revisionmove'                 => 'Lapváltozatok áthelyezése a(z) „$1” lapról',
'revmove-explain'              => 'A következő lapváltozatok át lesznek helyezve a(z) $1 lapról a megadott céllapra. Ha a cél nem létezik, akkor létre lesz hozva. Egyébként ezek a lapváltozatok össze lesznek vonva annak a lapnak a laptörténetével.',
'revmove-legend'               => 'Céllap és összefoglaló megadása',
'revmove-submit'               => 'Lapváltozatok áthelyezése egy kiválasztott lapra',
'revisionmoveselectedversions' => 'Kiválasztott lapváltozatok áthelyezése',
'revmove-reasonfield'          => 'Ok:',
'revmove-titlefield'           => 'Céllap:',
'revmove-badparam-title'       => 'Hibás paraméterek',
'revmove-badparam'             => 'A kérésed érvénytelen vagy nem elegendő paramétert tartalmaz. Kattints a „Vissza” gombra, majd próbáld újra.',
'revmove-norevisions-title'    => 'Érvénytelen célváltozat',
'revmove-norevisions'          => 'Nem adtad meg az(oka)t a lapváltozato(ka)t, mely(ek)en végre akarod hajtani ezt a műveletet, vagy a kiválasztott lapváltozat nem létezik.',
'revmove-nullmove-title'       => 'Hibás cím',
'revmove-nullmove'             => 'A forrás és a céllap megegyezik. Kattints a „Vissza” gombra, majd adj meg a jelenlegi, „$1” címtől különbözőt.',
'revmove-success-existing'     => '{{PLURAL:$1|Egy|$1}} lapváltozat át lett helyezve a(z) [[$2]] lapról a már létező [[$3]] lapra.',
'revmove-success-created'      => '{{PLURAL:$1|Egy|$1}} lapváltozat át lett helyezve a(z) [[$2]] lapról az újonnan létrehozott [[$3]] lapra.',

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
'mergehistory-success'             => '[[:$1]] {{PLURAL:$3|egy|$3}} változata sikeresen egyesítve lett a(z) [[:$2]] lappal.',
'mergehistory-fail'                => 'Nem sikerült a laptörténetek egyesítése. Kérlek, ellenőrizd újra az oldalt és a megadott időparamétereket.',
'mergehistory-no-source'           => 'Nem létezik forráslap $1 néven.',
'mergehistory-no-destination'      => 'Nem létezik céllap $1 néven.',
'mergehistory-invalid-source'      => 'A forráslapnak érvényes címet kell megadni.',
'mergehistory-invalid-destination' => 'A céllapnak érvényes címet kell megadni.',
'mergehistory-autocomment'         => 'Egyesítette a(z) [[:$1]] lapot a(z) [[:$2]] lappal',
'mergehistory-comment'             => 'Egyesítette a(z) [[:$1]] lapot a(z) [[:$2]] lappal: $3',
'mergehistory-same-destination'    => 'A forrás- és a céllap nem egyezhet meg',
'mergehistory-reason'              => 'Ok:',

# Merge log
'mergelog'           => 'Egyesítési napló',
'pagemerge-logentry' => '[[$1]] és [[$2]] egyesítve ($3 változatig)',
'revertmerge'        => 'Szétválasztás',
'mergelogpagetext'   => 'A lapok egyesítéséről szóló napló. Szűkítheted a listát a műveletet végző szerkesztő, vagy az érintett oldal megadásával.',

# Diffs
'history-title'            => 'A(z) „$1” laptörténete',
'difference'               => '(Változatok közti eltérés)',
'difference-multipage'     => '(Lapok közti eltérés)',
'lineno'                   => '$1. sor:',
'compareselectedversions'  => 'Kiválasztott változatok összehasonlítása',
'showhideselectedversions' => 'Kiválasztott változatok láthatóságának beállítása',
'editundo'                 => 'visszavonás',
'diff-multi'               => '({{PLURAL:$1|Egy közbeeső változat|$1 közbeeső változat}} nincs mutatva, amit $2 szerkesztő módosított)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Egy közbeeső változat|$1 közbeeső változat}} nincs mutatva, amit $2 szerkesztő módosított)',

# Search results
'searchresults'                    => 'A keresés eredménye',
'searchresults-title'              => 'Keresési eredmények: „$1”',
'searchresulttext'                 => 'A keresésről a [[{{MediaWiki:Helppage}}|{{int:help}}]] lapon találhatsz további információkat.',
'searchsubtitle'                   => 'A keresett kifejezés: „[[:$1]]” ([[Special:Prefixindex/$1|minden, „$1” előtaggal kezdődő lap]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|a(z) „$1” lapra hivatkozó lapok]])',
'searchsubtitleinvalid'            => "A keresett kulcsszó: „'''$1'''”",
'toomanymatches'                   => 'Túl sok találat van, próbálkozz egy másik lekérdezéssel',
'titlematches'                     => 'Címbeli egyezések',
'notitlematches'                   => 'Nincs megegyező cím',
'textmatches'                      => 'Szövegbeli egyezések',
'notextmatches'                    => 'Nincsenek szövegbeli egyezések',
'prevn'                            => 'előző {{PLURAL:$1|egy|$1}}',
'nextn'                            => 'következő {{PLURAL:$1|egy|$1}}',
'prevn-title'                      => 'Előző {{PLURAL:$1|egy|$1}} találat',
'nextn-title'                      => 'Következő {{PLURAL:$1|egy|$1}} találat',
'shown-title'                      => '{{PLURAL:$1|Egy|$1}} találat laponként',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Keresési beállítások',
'searchmenu-exists'                => "'''A wikin már van „[[:$1]]” nevű lap'''",
'searchmenu-new'                   => "'''Hozd létre a(z) „[[:$1]]” nevű lapot ezen a wikin!'''",
'searchhelp-url'                   => 'Help:Tartalom',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Ilyen előtagú lapok listázása]]',
'searchprofile-articles'           => 'Tartalmi oldalak',
'searchprofile-project'            => 'Segítség- és projektlapok',
'searchprofile-images'             => 'Médiafájlok',
'searchprofile-everything'         => 'Minden lap',
'searchprofile-advanced'           => 'Részletes',
'searchprofile-articles-tooltip'   => 'A következőkben keres: $1',
'searchprofile-project-tooltip'    => 'A következőkben keres: $1',
'searchprofile-images-tooltip'     => 'Fájlok keresése',
'searchprofile-everything-tooltip' => 'Minden névtérben keres (a vitalapokat is beleértve)',
'searchprofile-advanced-tooltip'   => 'Keresés adott névterekben',
'search-result-size'               => '$1 ({{PLURAL:$2|egy|$2}} szó)',
'search-result-category-size'      => '$1 oldal, $2 alkategória, $3 fájl',
'search-result-score'              => 'Relevancia: $1%',
'search-redirect'                  => '(átirányítva innen: $1)',
'search-section'                   => '($1 szakasz)',
'search-suggest'                   => 'Keresési javaslat: $1',
'search-interwiki-caption'         => 'Társlapok',
'search-interwiki-default'         => '$1 találat',
'search-interwiki-more'            => '(több)',
'search-mwsuggest-enabled'         => 'javaslatokkal',
'search-mwsuggest-disabled'        => 'javaslatok nélkül',
'search-relatedarticle'            => 'Kapcsolódó',
'mwsuggest-disable'                => 'AJAX-alapú keresési javaslatok letiltása',
'searcheverything-enable'          => 'Keresés az összes névtérben',
'searchrelated'                    => 'kapcsolódó',
'searchall'                        => 'mind',
'showingresults'                   => "Lent '''{{PLURAL:$1|egy|$1}}''' találat látható, az eleje '''$2'''.",
'showingresultsnum'                => "Lent '''{{PLURAL:$3|egy|$3}}''' találat látható, az eleje '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|'''$1'''|'''$1 - $2'''}}. találat a(z) '''$4''' kifejezésre (összesen: '''$3''')",
'nonefound'                        => "'''Megjegyzés''': Alapértelmezésben a keresés nem terjed ki minden névtérre. Ha az összes névtérben keresni akarsz, írd az ''all:'' karaktersorozatot a keresett kifejezés elé.",
'search-nonefound'                 => 'Nincs egyezés a megadott szöveggel.',
'powersearch'                      => 'Részletes keresés',
'powersearch-legend'               => 'Részletes keresés',
'powersearch-ns'                   => 'Névterek:',
'powersearch-redir'                => 'Átirányítások megjelenítése',
'powersearch-field'                => 'Keresett szöveg:',
'powersearch-togglelabel'          => 'Megjelölés:',
'powersearch-toggleall'            => 'Mind',
'powersearch-togglenone'           => 'Egyik sem',
'search-external'                  => 'Külső kereső',
'searchdisabled'                   => 'Elnézésed kérjük, de a teljes szöveges keresés terhelési okok miatt átmenetileg nem használható. Ezidő alatt használhatod a lenti Google keresést, mely viszont lehetséges, hogy nem teljesen friss adatokkal dolgozik.',

# Quickbar
'qbsettings'               => 'Gyorsmenü',
'qbsettings-none'          => 'Nincs',
'qbsettings-fixedleft'     => 'Fix baloldali',
'qbsettings-fixedright'    => 'Fix jobboldali',
'qbsettings-floatingleft'  => 'Lebegő baloldali',
'qbsettings-floatingright' => 'Lebegő jobboldali',

# Preferences page
'preferences'                   => 'Beállítások',
'mypreferences'                 => 'Beállításaim',
'prefs-edits'                   => 'Szerkesztéseid száma:',
'prefsnologin'                  => 'Nem jelentkeztél be',
'prefsnologintext'              => 'Saját beállításaid elmentéséhez <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} be kell jelentkezned.] </span>',
'changepassword'                => 'Jelszócsere',
'prefs-skin'                    => 'Felület',
'skin-preview'                  => 'előnézet',
'prefs-math'                    => 'Képletek',
'datedefault'                   => 'Nincs beállítás',
'prefs-datetime'                => 'Dátum és idő',
'prefs-personal'                => 'Felhasználói adatok',
'prefs-rc'                      => 'Friss változtatások',
'prefs-watchlist'               => 'Figyelőlista',
'prefs-watchlist-days'          => 'A figyelőlistában mutatott napok száma:',
'prefs-watchlist-days-max'      => '(legfeljebb 7 nap)',
'prefs-watchlist-edits'         => 'A kiterjesztett figyelőlistán mutatott szerkesztések száma:',
'prefs-watchlist-edits-max'     => '(legfeljebb 1000)',
'prefs-watchlist-token'         => 'A figyelőlista kulcsa:',
'prefs-misc'                    => 'Egyéb',
'prefs-resetpass'               => 'Jelszó megváltoztatása',
'prefs-email'                   => 'Levelezés',
'prefs-rendering'               => 'Lapok megjelenítése',
'saveprefs'                     => 'Mentés',
'resetprefs'                    => 'Alaphelyzet',
'restoreprefs'                  => 'A beállítások alaphelyzetbe állítása',
'prefs-editing'                 => 'Szerkesztés',
'prefs-edit-boxsize'            => 'A szerkesztőablak mérete.',
'rows'                          => 'Sor',
'columns'                       => 'Oszlop',
'searchresultshead'             => 'Keresés',
'resultsperpage'                => 'Laponként mutatott találatok száma:',
'contextlines'                  => 'Találatonként mutatott sorok száma:',
'contextchars'                  => 'Soronkénti szövegkörnyezet (karakterszám):',
'stub-threshold'                => 'A hivatkozások <a href="#" class="stub">csonkként</a> történő formázásának határa (bájtban):',
'stub-threshold-disabled'       => 'Kikapcsolva',
'recentchangesdays'             => 'A friss változtatásokban mutatott napok száma:',
'recentchangesdays-max'         => '(maximum {{PLURAL:$1|egy|$1}} nap)',
'recentchangescount'            => 'Az alapértelmezettként mutatott szerkesztések száma:',
'prefs-help-recentchangescount' => 'Ez vonatkozik a friss változtatásokra, laptörténetekre és naplókra is.',
'prefs-help-watchlist-token'    => 'Ha ebbe a mezőbe beírsz egy titkos kulcsot, RSS feed fog készülni a figyelőlistádról.
Bárki, aki tudja a fenti mezőbe beírt kulcsot, látni fogja a figyelőlistádat, így válassz egy titkos értéket.
Itt van egy véletlenszerűen generált érték, amit használhatsz: $1',
'savedprefs'                    => 'Az új beállításaid érvénybe léptek.',
'timezonelegend'                => 'Időzóna:',
'localtime'                     => 'Helyi idő:',
'timezoneuseserverdefault'      => 'A kiszolgáló alapértelmezett értékének használata',
'timezoneuseoffset'             => 'Egyéb (eltérés megadása)',
'timezoneoffset'                => 'Eltérés¹:',
'servertime'                    => 'A kiszolgáló ideje:',
'guesstimezone'                 => 'Töltse ki a böngésző',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktisz',
'timezoneregion-arctic'         => 'Északi-sark',
'timezoneregion-asia'           => 'Ázsia',
'timezoneregion-atlantic'       => 'Atlanti-óceán',
'timezoneregion-australia'      => 'Ausztrália',
'timezoneregion-europe'         => 'Európa',
'timezoneregion-indian'         => 'Indiai-óceán',
'timezoneregion-pacific'        => 'Csendes-óceán',
'allowemail'                    => 'E-mail engedélyezése más szerkesztőktől',
'prefs-searchoptions'           => 'A keresés beállításai',
'prefs-namespaces'              => 'Névterek',
'defaultns'                     => 'Egyébként a következő névterekben keressen:',
'default'                       => 'alapértelmezés',
'prefs-files'                   => 'Fájlok',
'prefs-custom-css'              => 'saját CSS',
'prefs-custom-js'               => 'saját JS',
'prefs-common-css-js'           => 'Közös CSS/JS az összes felület számára:',
'prefs-reset-intro'             => 'Ezen a lapon állíthatod vissza a beállításaidat az oldal alapértelmezett értékeire.
A műveletet nem lehet visszavonni.',
'prefs-emailconfirm-label'      => 'E-mail cím megerősítése:',
'prefs-textboxsize'             => 'A szerkesztőablak mérete',
'youremail'                     => 'Az e-mail címed:',
'username'                      => 'Szerkesztőnév:',
'uid'                           => 'Azonosító:',
'prefs-memberingroups'          => '{{PLURAL:$1|Csoporttagság|Csoporttagságok}}:',
'prefs-registration'            => 'Regisztráció ideje:',
'yourrealname'                  => 'Valódi neved:',
'yourlanguage'                  => 'A felület nyelve:',
'yourvariant'                   => 'Változó',
'yournick'                      => 'Aláírás:',
'prefs-help-signature'          => 'A vitalapra írt hozzászólásaidat négy hullámvonallal (<nowiki>~~~~</nowiki>) írd alá. A lap mentésekor ez lecserélődik az aláírásodra és egy időbélyegre.',
'badsig'                        => 'Érvénytelen aláírás; ellenőrizd a HTML-formázást.',
'badsiglength'                  => 'Az aláírásod túl hosszú.
{{PLURAL:$1|Egy|$1}} karakternél rövidebbnek kell lennie.',
'yourgender'                    => 'Nem:',
'gender-unknown'                => 'Nincs megadva',
'gender-male'                   => 'Férfi',
'gender-female'                 => 'Nő',
'prefs-help-gender'             => 'Nem kötelező: a szoftver használja a nemalapú üzenetek megjelenítéséhez. Az információ mindenki számára látható.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'A valódi nevet nem kötelező megadni, de ha úgy döntesz, hogy megadod, azzal leszel feltüntetve a munkád szerzőjeként.',
'prefs-help-email'              => 'Az e-mail cím megadása nem kötelező, de így kérhetsz új jelszót, ha elfelejtenéd a meglévőt.
Ezen kívül más szerkesztők is kapcsolatba lépjenek veled a szerkesztői vagy vitalapodon keresztül, anélkül, hogy névtelenséged feladnád.',
'prefs-help-email-required'     => 'Meg kell adnod az e-mail címedet.',
'prefs-info'                    => 'Alapinformációk',
'prefs-i18n'                    => 'Nyelvi beállítások',
'prefs-signature'               => 'Aláírás',
'prefs-dateformat'              => 'Dátumformátum',
'prefs-timeoffset'              => 'Időeltérés',
'prefs-advancedediting'         => 'Haladó beállítások',
'prefs-advancedrc'              => 'Haladó beállítások',
'prefs-advancedrendering'       => 'Haladó beállítások',
'prefs-advancedsearchoptions'   => 'Haladó beállítások',
'prefs-advancedwatchlist'       => 'Haladó beállítások',
'prefs-displayrc'               => 'Megjelenítési beállítások',
'prefs-displaysearchoptions'    => 'Megjelenítési beállítások',
'prefs-displaywatchlist'        => 'Megjelenítési beállítások',
'prefs-diffs'                   => 'Eltérések (diffek)',

# User rights
'userrights'                   => 'Szerkesztői jogok beállítása',
'userrights-lookup-user'       => 'Szerkesztőcsoportok beállítása',
'userrights-user-editname'     => 'Add meg a szerkesztő nevét:',
'editusergroup'                => 'Szerkesztőcsoportok módosítása',
'editinguser'                  => "'''[[User:$1|$1]]''' jogainak megváltoztatása ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Szerkesztőcsoportok módosítása',
'saveusergroups'               => 'Szerkesztőcsoportok mentése',
'userrights-groupsmember'      => 'Csoporttag:',
'userrights-groupsmember-auto' => 'Alapértelmezetten tagja:',
'userrights-groups-help'       => 'Beállíthatod, hogy a szerkesztő mely csoportokba tartozik.
* A bepipált doboz azt jelenti, hogy a szerkesztő benne van a csoportban, az üres azt, hogy nem.
* A * az olyan csoportokat jelöli, amelyeket ha egyszer hozzáadtál, nem távolíthatod el, vagy nem adhatod hozzá.',
'userrights-reason'            => 'Ok:',
'userrights-no-interwiki'      => 'Nincs jogod a szerkesztők jogainak módosításához más wikiken.',
'userrights-nodatabase'        => '$1 adatbázis nem létezik vagy nem helyi.',
'userrights-nologin'           => '[[Special:UserLogin|Be kell jelentkezned]] egy adminisztrátori fiókkal, hogy szerkesztői jogokat adhass.',
'userrights-notallowed'        => 'A fiókoddal nincs jogod felhasználói jogokat osztani.',
'userrights-changeable-col'    => 'Megváltoztatható csoportok',
'userrights-unchangeable-col'  => 'Nem megváltoztatható csoportok',

# Groups
'group'               => 'Csoport:',
'group-user'          => 'szerkesztők',
'group-autoconfirmed' => 'automatikusan megerősített felhasználók',
'group-bot'           => 'botok',
'group-sysop'         => 'adminisztrátorok',
'group-bureaucrat'    => 'bürokraták',
'group-suppress'      => 'adatvédelmi biztosok',
'group-all'           => '(mind)',

'group-user-member'          => 'szerkesztő',
'group-autoconfirmed-member' => 'automatikusan megerősített felhasználó',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'adminisztrátor',
'group-bureaucrat-member'    => 'bürokrata',
'group-suppress-member'      => 'adatvédelmi biztos',

'grouppage-user'          => '{{ns:project}}:Felhasználók',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatikusan megerősített felhasználók',
'grouppage-bot'           => '{{ns:project}}:Botok',
'grouppage-sysop'         => '{{ns:project}}:Adminisztrátorok',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraták',
'grouppage-suppress'      => '{{ns:project}}:Adatvédelmi biztosok',

# Rights
'right-read'                  => 'lapok olvasása',
'right-edit'                  => 'lapok szerkesztése',
'right-createpage'            => 'lapok készítése (nem vitalapok)',
'right-createtalk'            => 'vitalapok készítése',
'right-createaccount'         => 'új felhasználói fiók készítése',
'right-minoredit'             => 'szerkesztések apróként jelölésének lehetősége',
'right-move'                  => 'lapok átnevezése',
'right-move-subpages'         => 'lapok átnevezése az allapjukkal együtt',
'right-move-rootuserpages'    => 'szerkesztői lapok mozgatása',
'right-movefile'              => 'fájlok átnevezése',
'right-suppressredirect'      => 'nem készít átirányítást a régi néven lapok átnevezésekor',
'right-upload'                => 'fájlok feltöltése',
'right-reupload'              => 'létező fájlok felülírása',
'right-reupload-own'          => 'a saját maga által feltöltött fájlok felülírása',
'right-reupload-shared'       => 'felülírhatja a közös megosztóhelyen lévő fájlokat helyben',
'right-upload_by_url'         => 'fájl feltöltése URL-cím alapján',
'right-purge'                 => 'oldal gyorsítótárának ürítése megerősítés nélkül',
'right-autoconfirmed'         => 'félig védett lapok szerkesztése',
'right-bot'                   => 'automatikus folyamatként való kezelés',
'right-nominornewtalk'        => 'felhasználói lapok nem apró szerkesztésével megjelenik az új üzenet szöveg',
'right-apihighlimits'         => 'nagyobb mennyiségű lekérdezés az API-n keresztül',
'right-writeapi'              => 'a szerkesztő-API használata',
'right-delete'                => 'lapok törlése',
'right-bigdelete'             => 'nagy történettel rendelkező fájlok törlése',
'right-deleterevision'        => 'lapok adott változatainak törlése és helyreállítása',
'right-deletedhistory'        => 'törölt lapváltozatok megtekintése, a szövegük nélkül',
'right-deletedtext'           => 'törölt változatok szövegének és a változatok közötti eltérés megtekintése',
'right-browsearchive'         => 'keresés a törölt lapok között',
'right-undelete'              => 'lap helyreállítása',
'right-suppressrevision'      => 'az adminisztrátorok elől elrejtett változatok megtekintése és helyreállítása',
'right-suppressionlog'        => 'privát naplók megtekintése',
'right-block'                 => 'szerkesztők blokkolása',
'right-blockemail'            => 'szerkesztő e-mail küldési lehetőségének blokkolása',
'right-hideuser'              => 'felhasználói név blokkolása és elrejtése a külvilág elől',
'right-ipblock-exempt'        => 'IP-, auto- és tartományblokkok megkerülése',
'right-proxyunbannable'       => 'proxyk automatikus blokkjainak megkerülése',
'right-unblockself'           => 'saját felhasználói fiók blokkjának feloldása',
'right-protect'               => 'védelmi szintek megváltoztatása és védett lapok szerkesztése',
'right-editprotected'         => 'kaszkád védelem nélküli védett lapok szerkesztése',
'right-editinterface'         => 'felhasználói felület szerkesztése',
'right-editusercssjs'         => 'más felhasználók CSS és JS fájljainak szerkesztése',
'right-editusercss'           => 'más felhasználók CSS fájljainak szerkesztése',
'right-edituserjs'            => 'más felhasználók JS fájljainak szerkesztése',
'right-rollback'              => 'a lap utolsó szerkesztésének gyors visszaállítása',
'right-markbotedits'          => 'visszaállított szerkesztések botként való jelölése',
'right-noratelimit'           => 'sebességkorlát figyelmen kívül hagyása',
'right-import'                => 'lapok importálása más wikikből',
'right-importupload'          => 'lapok importálása fájl feltöltésével',
'right-patrol'                => 'szerkesztések ellenőrzöttként való jelölése',
'right-autopatrol'            => 'szerkesztések automatikusan ellenőrzöttként való jelölése',
'right-patrolmarks'           => 'járőrök jelzéseinek megtekintése a friss változásokban',
'right-unwatchedpages'        => 'nem figyelt lapok listájának megtekintése',
'right-trackback'             => 'trackback küldése',
'right-mergehistory'          => 'laptörténetek egyesítése',
'right-userrights'            => 'az összes szerkesztő jogainak módosítása',
'right-userrights-interwiki'  => 'más wikik szerkesztői jogainak módosítása',
'right-siteadmin'             => 'adatbázis lezárása, felnyitása',
'right-reset-passwords'       => 'Más felhasználók jelszavának visszaállítása',
'right-override-export-depth' => 'Lapok exportálása a hivatkozott lapokkal együtt, legfeljebb 5-ös mélységig',
'right-sendemail'             => 'e-mail küldése más felhasználóknak',
'right-revisionmove'          => 'lapváltozatok áthelyezése',
'right-disableaccount'        => 'fiókok letiltása',

# User rights log
'rightslog'      => 'Szerkesztői jogosultságok naplója',
'rightslogtext'  => 'Ez a rendszernapló a felhasználó jogosultságok változásait mutatja.',
'rightslogentry' => 'megváltoztatta $1 szerkesztő felhasználó jogait (régi: $2; új: $3)',
'rightsnone'     => '(semmi)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lap olvasása',
'action-edit'                 => 'lap szerkesztése',
'action-createpage'           => 'új lap készítése',
'action-createtalk'           => 'vitalap készítése',
'action-createaccount'        => 'felhasználói fiók elkészítése',
'action-minoredit'            => 'szerkesztés aprónak jelölése',
'action-move'                 => 'lap átnevezése',
'action-move-subpages'        => 'lap és allapjainak átnevezése',
'action-move-rootuserpages'   => 'szerkesztői lapok átnevezése',
'action-movefile'             => 'fájlok átnevezése',
'action-upload'               => 'fájl feltöltése',
'action-reupload'             => 'már létező fájl felülírása',
'action-reupload-shared'      => 'közös megosztón található fájl felülírása',
'action-upload_by_url'        => 'fájl feltöltése URL-címről',
'action-writeapi'             => 'író API használata',
'action-delete'               => 'lap törlése',
'action-deleterevision'       => 'változat törlése',
'action-deletedhistory'       => 'lap törölt laptörténetének megtekintése',
'action-browsearchive'        => 'keresés a törölt lapok között',
'action-undelete'             => 'lap helyreállítása',
'action-suppressrevision'     => 'rejtett változat megtekintése és helyreállítása',
'action-suppressionlog'       => 'privát napló megtekintése',
'action-block'                => 'szerkesztő blokkolása',
'action-protect'              => 'lap védelmi szintjének megváltoztatása',
'action-import'               => 'lap importálása más wikiből',
'action-importupload'         => 'lap importálása fájl feltöltésével',
'action-patrol'               => 'mások szerkesztéseinek ellenőrzöttként való megjelölése',
'action-autopatrol'           => 'saját szerkesztések ellenőrzöttként való megjelölése',
'action-unwatchedpages'       => 'nem figyelt lapok listájának megtekintése',
'action-trackback'            => 'trackback küldése',
'action-mergehistory'         => 'lap laptörténetének egyesítése',
'action-userrights'           => 'összes szerkesztő jogainak módosítása',
'action-userrights-interwiki' => 'más wikik szerkesztői jogainak módosítása',
'action-siteadmin'            => 'adatbázis lezárása vagy felnyitása',
'action-revisionmove'         => 'lapváltozatok áthelyezése',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|egy|$1}} változtatás',
'recentchanges'                     => 'Friss változtatások',
'recentchanges-legend'              => 'A friss változások beállításai',
'recentchangestext'                 => 'Ezen a lapon a wikiben történt legutóbbi változásokat lehet nyomonkövetni.',
'recentchanges-feed-description'    => 'Kövesd a wiki friss változtatásait ezzel a hírcsatornával.',
'recentchanges-label-newpage'       => 'Ezzel a szerkesztéssel egy új lap jött létre',
'recentchanges-label-minor'         => 'Ez egy apró szerkesztés',
'recentchanges-label-bot'           => 'Ezt a szerkesztést egy bot hajtotta végre',
'recentchanges-label-unpatrolled'   => 'Ezt a szerkesztést még nem ellenőrizték',
'rcnote'                            => "Alább az utolsó '''{{PLURAL:$2|egy|$2}}''' nap utolsó '''{{PLURAL:$1|egy|$1}}''' változtatása látható. A lap generálásának időpontja $4, $5.",
'rcnotefrom'                        => 'Alább a <b>$2</b> óta történt változások láthatóak (<b>$1</b> db).',
'rclistfrom'                        => '$1 után történt változások megtekintése',
'rcshowhideminor'                   => 'apró szerkesztések $1',
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
'minoreditletter'                   => 'a',
'newpageletter'                     => 'Ú',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[Jelenleg {{PLURAL:$1|egy|$1}} felhasználó figyeli]',
'rc_categories'                     => 'Szűkítés kategóriákra („|” jellel válaszd el őket)',
'rc_categories_any'                 => 'Bármelyik',
'newsectionsummary'                 => '/* $1 */ (új szakasz)',
'rc-enhanced-expand'                => 'Részletek megjelenítése (JavaScript szükséges)',
'rc-enhanced-hide'                  => 'Részletek elrejtése',

# Recent changes linked
'recentchangeslinked'          => 'Kapcsolódó változtatások',
'recentchangeslinked-feed'     => 'Kapcsolódó változtatások',
'recentchangeslinked-toolbox'  => 'Kapcsolódó változtatások',
'recentchangeslinked-title'    => 'A(z) $1 laphoz kapcsolódó változtatások',
'recentchangeslinked-noresult' => 'A megadott időtartam alatt nem történt változás a kapcsolódó lapokon.',
'recentchangeslinked-summary'  => "Alább azon lapoknak a legutóbbi változtatásai láthatóak, amelyekre hivatkozik egy megadott lap (vagy tagjai a megadott kategóriának).
A [[Special:Watchlist|figyelőlistádon]] szereplő lapok '''félkövérrel''' vannak jelölve.",
'recentchangeslinked-page'     => 'Lap neve:',
'recentchangeslinked-to'       => 'Inkább az erre linkelő lapok változtatásait mutasd',

# Upload
'upload'                      => 'Fájl feltöltése',
'uploadbtn'                   => 'Fájl feltöltése',
'reuploaddesc'                => 'Visszatérés a feltöltési űrlaphoz.',
'upload-tryagain'             => 'Módosított fájl-leírás elküldése',
'uploadnologin'               => 'Nem vagy bejelentkezve',
'uploadnologintext'           => 'Csak regisztrált felhasználók tölthetnek fel fájlokat. [[Special:UserLogin|Jelentkezz be]] vagy regisztrálj!',
'upload_directory_missing'    => 'A feltöltési könyvtár ($1) nem létezik vagy nem tudja létrehozni a kiszolgáló.',
'upload_directory_read_only'  => 'A kiszolgálónak nincs írási jogosultsága a feltöltési könyvtárban ($1).',
'uploaderror'                 => 'Feltöltési hiba',
'upload-recreate-warning'     => "'''Figyelmeztetés: az ilyen nevű fájlt törölték vagy átnevezték.'''

Az oldalhoz tartozó törlési és átnevezési naplóbejegyzések:",
'uploadtext'                  => "Az alábbi űrlap használatával tölthetsz fel fájlokat.
A korábban feltöltött képek megtekintéséhez vagy a köztük való kereséshez menj a [[Special:FileList|feltöltött fájlok listájához]], a feltöltések, újrafeltöltések a [[Special:Log/upload|feltöltési naplóban]], a törlések a [[Special:Log/delete|törlési naplóban]] vannak jegyezve.

Képet a következő módon illeszthetsz be egy oldalra: '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Kép.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Kép.png|alternatív szöveg]]</nowiki>''' vagy a közvetlen hivatkozáshoz használd a
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fájl.ogg]]</nowiki>''' formát.",
'upload-permitted'            => 'Engedélyezett fájltípusok: $1.',
'upload-preferred'            => 'Támogatott fájltípusok: $1.',
'upload-prohibited'           => 'Tiltott fájltípusok: $1.',
'uploadlog'                   => 'feltöltési napló',
'uploadlogpage'               => 'Feltöltési_napló',
'uploadlogpagetext'           => 'Lentebb látható a legutóbbi felküldések listája.
Lásd még az [[Special:NewFiles|új fáljlok galériáját]]',
'filename'                    => 'Fájlnév',
'filedesc'                    => 'Összefoglaló',
'fileuploadsummary'           => 'Összefoglaló:',
'filereuploadsummary'         => 'Változtatások:',
'filestatus'                  => 'Szerzői jogi állapot:',
'filesource'                  => 'Forrás:',
'uploadedfiles'               => 'Feltöltött fájlok',
'ignorewarning'               => 'Biztosan így akarom feltölteni',
'ignorewarnings'              => 'Hagyd figyelmen kívül a figyelmeztetéseket',
'minlength1'                  => 'A fájlnévnek legalább egy betűből kell állnia.',
'illegalfilename'             => 'A „$1” lap neve olyan karaktereket tartalmaz, melyek nincsenek megengedve lapcímben. Kérlek, változtasd meg a nevet, és próbálkozz a mentéssel újra.',
'badfilename'                 => 'A fájl új neve „$1”.',
'filetype-mime-mismatch'      => 'A fájl kiterjesztése nem egyezik a MIME-típusával.',
'filetype-badmime'            => '„$1” MIME-típusú fájlokat nem lehet feltölteni.',
'filetype-bad-ie-mime'        => 'A fájlt nem lehet feltölteni, mert az Internet Explorer „$1” típusúnak tekintené, ami tiltott és potenciálisan veszélyes fájltípus.',
'filetype-unwanted-type'      => "A(z) '''„.$1”''' nem javasolt fájltípus.
Az ajánlott {{PLURAL:$3|típus|típusok}}: $2.",
'filetype-banned-type'        => "A(z) '''„.$1”''' nem megengedett fájltípus.
Az engedélyezett {{PLURAL:$3|típus|típusok}}: $2.",
'filetype-missing'            => 'A fájlnak nincs kiterjesztése (pl. „.jpg”).',
'empty-file'                  => 'Az elküldött fájl üres volt.',
'file-too-large'              => 'Az elküldött fájl túl nagy volt.',
'filename-tooshort'           => 'A fájlnév túl rövid.',
'filetype-banned'             => 'Az ilyen típusú fájlok tiltva vannak.',
'verification-error'          => 'Ez a fájl nem felelt meg az ellenőrzésen (hibás, rossz kiterjesztés, stb.).',
'hookaborted'                 => 'A módosítást, amit próbáltál elvégezni megszakította egy kiterjesztés-hook.',
'illegal-filename'            => 'A fájlnév nem engedélyezett.',
'overwrite'                   => 'Nem engedélyezett felülírni egy létező fájlt.',
'unknown-error'               => 'Ismeretlen hiba történt.',
'tmp-create-error'            => 'Nem sikerült létrehozni az ideiglenes fájlt.',
'tmp-write-error'             => 'Hiba az ideiglenes fájl írásakor.',
'large-file'                  => 'Javasoljuk, hogy ne tölts fel olyan fájlokat, melyek nagyobbak, mint $1;
ez a fájl $2.',
'largefileserver'             => 'A fájl mérete meghaladja a kiszolgálón beállított maximális értéket.',
'emptyfile'                   => 'Az általad feltöltött fájl üresnek tűnik.
Ez valószínűleg azért van, mert hibásan adtad meg a feltöltendő fájl nevét.
Ellenőrizd, hogy valóban fel akarod-e tölteni ezt a fájlt.',
'fileexists'                  => "'''<tt>[[:$1]]</tt>''' névvel már létezik egy állomány.
Ellenőrizd, hogy biztosan felül akarod-e írni! [[$1|thumb]]",
'filepageexists'              => "Ehhez a fájlnévhez már létezik leírás a '''<tt>[[:$1]]</tt>''' lapon, de jelenleg nincs feltöltve ilyen nevű fájl.
A leírás, amit ebbe az űrlapba írsz, nem fogja felülírni a már létezőt.
Ha meg szeretnéd változtatni a leírást, meg kell nyitnod szerkesztésre a lapjot.
[[$1|thumb]]",
'fileexists-extension'        => "Már van egy hasonló nevű feltöltött fájl: [[$2|thumb]]
* A feltöltendő fájl neve: '''<tt>[[:$1]]</tt>'''
* A már létező fájl neve: '''<tt>[[:$2]]</tt>'''
Kérjük, hogy válassz másik nevet.",
'fileexists-thumbnail-yes'    => "A fájl egy kisméretű képnek ''(bélyegképnek)'' tűnik. [[$1|thumb]]
Kérjük, hogy ellenőrizd a(z) '''<tt>[[:$1]]</tt>''' fájlt.
Ha az ellenőrzött fájl ugyanakkora, mint az eredeti méretű kép, akkor nincs szükség bélyegkép feltöltésére.",
'file-thumbnail-no'           => "A fájlnév a(z) '''<tt>$1</tt>''' karakterlánccal kezdődik.
Úgy tűnik, hogy ez egy kisméretű kép ''(bélyegkép)''.
Ha rendelkezel a teljesméretű képpel, akkor töltsd fel azt, egyébként kérjük, hogy változtasd meg a fájlnevet.",
'fileexists-forbidden'        => 'Már létezik egy ugyanilyen nevű fájl, és nem lehet felülírni.
Ha még mindig fel szeretnéd tölteni a fájlt, menj vissza, és adj meg egy új nevet. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Egy ugyanilyen nevű fájl már létezik a közös fájlmegosztóban; kérlek menj vissza és válassz egy másik nevet a fájlnak, ha még mindig fel akarod tölteni! [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ez a következő {{PLURAL:$1|fájl|fájlok}} duplikátuma:',
'file-deleted-duplicate'      => 'Egy ehhez hasonló fájlt ([[:$1]]) korábban már töröltek. Ellenőrizd a fájl törlési naplóját, mielőtt újra feltöltenéd.',
'uploadwarning'               => 'Feltöltési figyelmeztetés',
'uploadwarning-text'          => 'Kérlek módosítsd a fájl leírását alább, majd próbáld újra.',
'savefile'                    => 'Fájl mentése',
'uploadedimage'               => '„[[$1]]” felküldve',
'overwroteimage'              => 'feltöltötte a(z) „[[$1]]” fájl új változatát',
'uploaddisabled'              => 'Feltöltések kikapcsolva',
'copyuploaddisabled'          => 'A feltöltés URL alapján le van tiltva.',
'uploadfromurl-queued'        => 'A feltöltésed a várakozási sorba került.',
'uploaddisabledtext'          => 'A fájlfeltöltés nem engedélyezett.',
'php-uploaddisabledtext'      => 'A PHP-s fájlfeltöltés le van tiltva. Ellenőrizd a file_uploads beállítást.',
'uploadscripted'              => 'Ez a fájl olyan HTML- vagy parancsfájlkódot tartalmaz, melyet tévedésből egy webböngésző esetleg értelmezni próbálhatna.',
'uploadvirus'                 => 'Ez a fájl vírust tartalmaz! A részletek: $1',
'upload-source'               => 'Forrásfájl',
'sourcefilename'              => 'Forrásfájl neve:',
'sourceurl'                   => 'A forrás URL-címe:',
'destfilename'                => 'Célfájlnév:',
'upload-maxfilesize'          => 'Maximális fájlméret: $1',
'upload-description'          => 'A fájl leírása',
'upload-options'              => 'Feltöltési beállítások',
'watchthisupload'             => 'Fájl figyelése',
'filewasdeleted'              => 'Korábban valaki már feltöltött ilyen néven egy fájlt, amelyet később töröltünk. Ellenőrizd a $1 bejegyzését, nehogy újra feltöltsd ugyanezt a fájlt.',
'upload-wasdeleted'           => "'''Vigyázat: egy olyan fájlt akarsz feltölteni, ami korábban már törölve lett.'''

Mielőtt ismét feltöltenéd, nézd meg, miért lett korábban törölve, és ellenőrizd, hogy a törlés indoka nem érvényes-e még. A törlési naplóban a lapról az alábbi bejegyzések szerepelnek:",
'filename-bad-prefix'         => "Annak a fájlnak a neve, amelyet fel akarsz tölteni '''„$1”''' karakterekkel kezdődik. Ilyeneket általában a digitális kamerák adnak a fájloknak, automatikusan, azonban ezek nem írják le annak tartalmát. Válassz egy leíró nevet!",
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
'upload-success-subj'         => 'A feltöltés sikerült',
'upload-success-msg'          => 'A feltöltés (innen $2) sikeres volt. A feltöltésed itt érhető el: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Feltöltési hiba',
'upload-failure-msg'          => 'Probléma történt a feltöltéseddel (innen: $2):

$1',
'upload-warning-subj'         => 'Feltöltési figyelmeztetés',
'upload-warning-msg'          => 'Hiba történt a feltöltéseddel innen: [$2]. Visszatérhetsz a [[Special:Upload/stash/$1|feltöltéshez]], hogy orvosold a hibát.',

'upload-proto-error'        => 'Hibás protokoll',
'upload-proto-error-text'   => 'A távoli feltöltéshez <code>http://</code> vagy <code>ftp://</code> kezdetű URL-ekre van szükség.',
'upload-file-error'         => 'Belső hiba',
'upload-file-error-text'    => 'Belső hiba történt egy ideiglenes fájl szerveren történő létrehozásakor.
Kérjük, hogy lépj kapcsolatba egy  [[Special:ListUsers/sysop|adminisztrátorral]].',
'upload-misc-error'         => 'Ismeretlen feltöltési hiba',
'upload-misc-error-text'    => 'A feltöltés során ismeretlen hiba történt.  Kérjük, ellenőrizd, hogy az URL érvényes-e és hozzáférhető-e, majd próbáld újra.  Ha a probléma továbbra is fennáll, akkor lépj kapcsolatba a rendszergazdával.',
'upload-too-many-redirects' => 'Az URL túl sokszor volt átirányítva',
'upload-unknown-size'       => 'Ismeretlen méretű',
'upload-http-error'         => 'HTTP-hiba történt: $1',

# Special:UploadStash
'uploadstash-summary'  => 'Ezen a lapon lehet hozzáférni azokhoz a fájlokhoz, melyek fel lettek töltve (vagy épp feltöltés alatt vannak), de még nem lettek közzétéve a wikin. Az ilyen fájlok csak a feltöltőik számára láthatóak.',
'uploadstash-errclear' => 'A fájlok törlése nem sikerült.',
'uploadstash-refresh'  => 'Fájlok listájának frissítése',

# img_auth script messages
'img-auth-accessdenied' => 'Hozzáférés megtagadva',
'img-auth-nopathinfo'   => 'Hiányzó PATH_INFO.
A szerver nincs beállítva, hogy továbbítsa ezt az információt.
Lehet, hogy CGI-alapú, és nem támogatja az img_auth-ot.
Lásd a http://www.mediawiki.org/wiki/Manual:Image_Authorization lapot.',
'img-auth-notindir'     => 'A kért elérési út nincs a beállított feltöltési könyvtárban.',
'img-auth-badtitle'     => 'Nem sikerült érvényes címet készíteni a(z) „$1” szövegből.',
'img-auth-nologinnWL'   => 'Nem vagy bejelentkezve, és a(z) „$1” nincs az engedélyezési listán.',
'img-auth-nofile'       => 'A fájl („$1”) nem létezik.',
'img-auth-isdir'        => 'Megpróbáltál hozzáférni a(z) „$1” könyvtárhoz, azonban csak a fájlokhoz lehet.',
'img-auth-streaming'    => '„$1” továbbítása.',
'img-auth-public'       => 'Az img_auth.php funkciója az, hogy fájlokat közvetítsen egy privát wikiből.
Ez a wiki publikus, így a biztonság miatt az img_auth.php ki van kapcsolva.',
'img-auth-noread'       => 'A szerkesztő nem jogosult a(z) „$1” olvasására.',

# HTTP errors
'http-invalid-url'      => 'Érvénytelen URL-cím: $1',
'http-invalid-scheme'   => 'A(z) „$1” sémájú URL-ek nem támogatottak.',
'http-request-error'    => 'A HTTP-kérés nem sikerült egy ismeretlen hiba miatt.',
'http-read-error'       => 'HTTP-olvasási hiba.',
'http-timed-out'        => 'A HTTP-kérés túllépte a határidőt.',
'http-curl-error'       => 'Hiba történt az URL lekérésekor: $1',
'http-host-unreachable' => 'Nem sikerült elérni az URL-t.',
'http-bad-status'       => 'Probléma történt a HTTP-kérés közben: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nem érhető el az URL',
'upload-curl-error6-text'  => 'A megadott URL nem érhető el.  Kérjük, ellenőrizd újra, hogy az URL pontos-e, és a webhely működik-e.',
'upload-curl-error28'      => 'Feltöltési időtúllépés',
'upload-curl-error28-text' => 'A webhely túl sokára válaszolt. Kérjük, ellenőrizd, hogy a webhely elérhető-e, várj egy kicsit, aztán próbáld újra. Kevésbé forgalmas időben is megpróbálhatod.',

'license'            => 'Licenc:',
'license-header'     => 'Licenc',
'nolicense'          => 'Válassz licencet!',
'license-nopreview'  => '(Előnézet nem elérhető)',
'upload_source_url'  => ' (egy érvényes, nyilvánosan elérhető URL)',
'upload_source_file' => ' (egy fájl a számítógépeden)',

# Special:ListFiles
'listfiles-summary'     => 'Ezen a speciális lapon látható az összes feltöltött fájl.
A legutóbb feltöltött fájlok vannak a lista elején.
Az oszlopok címeire kattintva változtathatod meg a rendezést.',
'listfiles_search_for'  => 'Keresés fájl nevére:',
'imgfile'               => 'fájl',
'listfiles'             => 'Fájllista',
'listfiles_thumb'       => 'Bélyegkép',
'listfiles_date'        => 'Dátum',
'listfiles_name'        => 'Név',
'listfiles_user'        => 'feltöltő',
'listfiles_size'        => 'Méret',
'listfiles_description' => 'Leírás',
'listfiles_count'       => 'Változatok',

# File description page
'file-anchor-link'          => 'Fájl',
'filehist'                  => 'Fájltörténet',
'filehist-help'             => 'Kattints egy időpontra, hogy a fájl akkori állapotát láthasd.',
'filehist-deleteall'        => 'összes törlése',
'filehist-deleteone'        => 'törlés',
'filehist-revert'           => 'visszaállít',
'filehist-current'          => 'aktuális',
'filehist-datetime'         => 'Dátum/idő',
'filehist-thumb'            => 'Bélyegkép',
'filehist-thumbtext'        => 'Bélyegkép a $1-kori változatról',
'filehist-nothumb'          => 'Nincs bélyegkép',
'filehist-user'             => 'Feltöltő',
'filehist-dimensions'       => 'Felbontás',
'filehist-filesize'         => 'Fájlméret',
'filehist-comment'          => 'Megjegyzés',
'filehist-missing'          => 'A fájl hiányzik',
'imagelinks'                => 'Fájlhivatkozások',
'linkstoimage'              => 'Az alábbi {{PLURAL:$1|lap hivatkozik|lapok hivatkoznak}} erre a fájlra:',
'linkstoimage-more'         => 'Több, mint {{PLURAL:$1|egy|$1}} oldal hivatkozik erre a fájlra.
A következő lista csak az {{PLURAL:$1|első linket|első $1 linket}} tartalmazza.
A teljes lista [[Special:WhatLinksHere/$2|ezen a lapon]] található meg.',
'nolinkstoimage'            => 'Erre a fájlra nem hivatkozik lap.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|További hivatkozások]] megtekintése',
'redirectstofile'           => 'A következő {{PLURAL:$1|fájl|$1 fájl}} van átirányítva erre a névre:',
'duplicatesoffile'          => 'A következő {{PLURAL:$1|fájl|$1 fájl}} ennek a fájlnak a duplikátuma ([[Special:FileDuplicateSearch/$2|további részletek]]):',
'sharedupload'              => 'Ez a fájl a(z) $1 megosztott tárhelyről származik, és más projektek is használhatják.',
'sharedupload-desc-there'   => 'Ez a fájl a $1 megosztott tárhelyről származik, és más projektek is használhatják.
Az [$2 ottani leírólapján] további információkat találhatsz róla.',
'sharedupload-desc-here'    => 'Ez a fájl a $1 megosztott tárhelyről származik, és más projektek is használhatják.
A [$2 fájl ottani leírólapjának] másolata alább látható.',
'filepage-nofile'           => 'Nem létezik ilyen nevű fájl.',
'filepage-nofile-link'      => 'Nem létezik ilyen nevű fájl. [$1 Ide kattintva] feltölthetsz egyet.',
'uploadnewversion-linktext' => 'Új változat feltöltése',
'shared-repo-from'          => 'a(z) $1 megosztott tárhelyről',
'shared-repo'               => 'megosztott tárhely',

# File reversion
'filerevert'                => '$1 visszaállítása',
'filerevert-legend'         => 'Fájl visszaállítása',
'filerevert-intro'          => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl [$4 verzióját állítod vissza, dátum: $3, $2].</span>',
'filerevert-comment'        => 'Ok:',
'filerevert-defaultcomment' => 'A $2, $1-i verzió visszaállítása',
'filerevert-submit'         => 'Visszaállítás',
'filerevert-success'        => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl visszaállítása a(z) [$4 verzióra, $3, $2] sikerült.</span>',
'filerevert-badversion'     => 'A megadott időbélyegzésű fájlnak nincs helyi változata.',

# File deletion
'filedelete'                  => '$1 törlése',
'filedelete-legend'           => 'Fájl törlése',
'filedelete-intro'            => "Törölni készülsz a(z) '''[[Media:$1|$1]]''' médiafájlt, a teljes fájltörténetével együtt.",
'filedelete-intro-old'        => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl, dátum: [$4 $3, $2] változatát törlöd.</span>',
'filedelete-comment'          => 'Ok:',
'filedelete-submit'           => 'Törlés',
'filedelete-success'          => "A(z) '''$1''' médiafájlt törölted.",
'filedelete-success-old'      => "A(z) '''[[Media:$1|$1]]''' $3, $2-kori változata sikeresen törölve lett.",
'filedelete-nofile'           => "'''$1''' nem létezik.",
'filedelete-nofile-old'       => "A(z) '''$1''' fájlnak nincs a megadott tulajdonságokkal rendelkező archivált változata.",
'filedelete-otherreason'      => 'Más/további ok:',
'filedelete-reason-otherlist' => 'Más ok',
'filedelete-reason-dropdown'  => '*Általános törlési okok
** Szerzői jog megsértése
** Duplikátum',
'filedelete-edit-reasonlist'  => 'Törlési okok szerkesztése',
'filedelete-maintenance'      => 'A fájlok törlése és helyreállítása ideiglenesen le van tiltva karbantartás miatt.',

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
'unusedtemplatestext' => 'Ez a lap azon {{ns:template}} névtérbe tartozó lapokat gyűjti össze, melyek nincsenek használva egyetlen lapon sem.
Ellenőrizd a meglévő hivatkozásokat, mielőtt törölnéd őket.',
'unusedtemplateswlh'  => 'más hivatkozások',

# Random page
'randompage'         => 'Lap találomra',
'randompage-nopages' => 'A következő {{PLURAL:$2|névtérben|névterekben}} nincsenek lapok: $1.',

# Random redirect
'randomredirect'         => 'Átirányítás találomra',
'randomredirect-nopages' => 'A(z) „$1” névtérben nincsenek átirányítások.',

# Statistics
'statistics'                   => 'Statisztika',
'statistics-header-pages'      => 'Lapstatisztikák',
'statistics-header-edits'      => 'Szerkesztési statisztika',
'statistics-header-views'      => 'Látogatási statisztika',
'statistics-header-users'      => 'Szerkesztői statisztika',
'statistics-header-hooks'      => 'További statisztikák',
'statistics-articles'          => 'Tartalommal rendelkező lapok',
'statistics-pages'             => 'Lapok száma',
'statistics-pages-desc'        => 'A wikiben található összes lap, beleértve a vitalapokat és az átirányításokat is',
'statistics-files'             => 'Feltöltött fájlok',
'statistics-edits'             => 'Szerkesztések száma a(z) {{SITENAME}} indulása óta',
'statistics-edits-average'     => 'Szerkesztések átlagos száma laponként',
'statistics-views-total'       => 'Összes megtekintés',
'statistics-views-total-desc'  => 'A nem létező és speciális lapok megtekintési adatai nincsenek beleszámolva.',
'statistics-views-peredit'     => 'Megtekintések szerkesztésenként',
'statistics-users'             => 'Regisztrált [[Speciális:Szerkesztők listája|szerkesztők]]',
'statistics-users-active'      => 'Aktív szerkesztők',
'statistics-users-active-desc' => 'Szerkesztők, akik csináltak valamit az elmúlt {{PLURAL:$1|egy|$1}} napban',
'statistics-mostpopular'       => 'Legtöbbször megtekintett lapok',

'disambiguations'      => 'Egyértelműsítő lapok',
'disambiguationspage'  => 'Template:Egyért',
'disambiguations-text' => "A következő oldalak '''egyértelműsítő lapra''' mutató hivatkozást tartalmaznak.
A megfelelő szócikkre kellene mutatniuk inkább.<br />
Egy oldal egyértelműsítő lapnak számít, ha tartalmazza a [[MediaWiki:Disambiguationspage]] oldalról belinkelt sablonok valamelyikét.",

'doubleredirects'            => 'Dupla átirányítások',
'doubleredirectstext'        => 'Ez a lap azokat a lapokat listázza, melyek átirányító lapokra irányítanak át.
Minden sor tartalmaz egy hivatkozást az első, valamint a második átirányításra, valamint a második átirányítás céljára, ami általában a valódi céllap, erre kellene az első átirányításnak mutatnia.
Az <del>áthúzott</del> sorok a lista elkészülése óta javítva lettek.',
'double-redirect-fixed-move' => '[[$1]] átnevezve, a továbbiakban átirányításként működik a(z) [[$2]] lapra',
'double-redirect-fixer'      => 'Átirányításjavító',

'brokenredirects'        => 'Nem létező lapra mutató átirányítások',
'brokenredirectstext'    => 'A következő átirányítások nem létező lapokra hivatkoznak:',
'brokenredirects-edit'   => 'szerkesztés',
'brokenredirects-delete' => 'törlés',

'withoutinterwiki'         => 'Nyelvközi hivatkozás nélküli lapok',
'withoutinterwiki-summary' => 'A következő lapok nem hivatkoznak más nyelvű változatokra:',
'withoutinterwiki-legend'  => 'Előtag',
'withoutinterwiki-submit'  => 'Megjelenítés',

'fewestrevisions' => 'Legrövidebb laptörténetű lapok',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|egy|$1}} bájt',
'ncategories'             => '{{PLURAL:$1|egy|$1}} kategória',
'nlinks'                  => '{{PLURAL:$1|egy|$1}} hivatkozás',
'nmembers'                => '{{PLURAL:$1|egy|$1}} elem',
'nrevisions'              => '{{PLURAL:$1|egy|$1}} változat',
'nviews'                  => '{{PLURAL:$1|egy|$1}} megtekintés',
'nimagelinks'             => '{{PLURAL:$1|Egy|$1}} lapon van használva',
'ntransclusions'          => '{{PLURAL:$1|egy|$1}} lapon van használva',
'specialpage-empty'       => 'Ez az oldal üres.',
'lonelypages'             => 'Árva lapok',
'lonelypagestext'         => 'A következő lapok nincsenek linkelve vagy beillesztve más lapokra a(z) {{SITENAME}} wikin.',
'uncategorizedpages'      => 'Kategorizálatlan lapok',
'uncategorizedcategories' => 'Kategorizálatlan kategóriák',
'uncategorizedimages'     => 'Kategorizálatlan fájlok',
'uncategorizedtemplates'  => 'Kategorizálatlan sablonok',
'unusedcategories'        => 'Nem használt kategóriák',
'unusedimages'            => 'Nem használt fájlok',
'popularpages'            => 'Népszerű lapok',
'wantedcategories'        => 'Keresett kategóriák',
'wantedpages'             => 'Keresett lapok',
'wantedpages-badtitle'    => 'Érvénytelen cím található az eredményhalmazban: $1',
'wantedfiles'             => 'Keresett fájlok',
'wantedtemplates'         => 'Keresett sablonok',
'mostlinked'              => 'Legtöbbet hivatkozott lapok',
'mostlinkedcategories'    => 'Legtöbbet hivatkozott kategóriák',
'mostlinkedtemplates'     => 'Legtöbbet hivatkozott sablonok',
'mostcategories'          => 'Legtöbb kategóriába tartozó lapok',
'mostimages'              => 'Legtöbbet hivatkozott fájlok',
'mostrevisions'           => 'Legtöbbet szerkesztett lapok',
'prefixindex'             => 'Keresés előtag szerint',
'shortpages'              => 'Rövid lapok',
'longpages'               => 'Hosszú lapok',
'deadendpages'            => 'Zsákutcalapok',
'deadendpagestext'        => 'Az itt található lapok nem kapcsolódnak hivatkozásokkal ezen wiki más oldalaihoz.',
'protectedpages'          => 'Védett lapok',
'protectedpages-indef'    => 'Csak a meghatározatlan idejű védelmek',
'protectedpages-cascade'  => 'Csak a kaszkádvédelmek',
'protectedpagestext'      => 'A következő lapok átnevezés vagy szerkesztés ellen védettek',
'protectedpagesempty'     => 'Jelenleg nincsenek ilyen paraméterekkel védett lapok.',
'protectedtitles'         => 'Létrehozás ellen védett lapok',
'protectedtitlestext'     => 'A következő lapok védve vannak a létrehozás ellen',
'protectedtitlesempty'    => 'Jelenleg nincsenek ilyen típusú védett lapok.',
'listusers'               => 'Szerkesztők',
'listusers-editsonly'     => 'Csak a szerkesztéssel rendelkező szerkesztők mutatása',
'listusers-creationsort'  => 'Rendezés létrehozási dátum szerint',
'usereditcount'           => '{{PLURAL:$1|egy|$1}} szerkesztés',
'usercreated'             => 'Létrehozva $1, $2-kor',
'newpages'                => 'Új lapok',
'newpages-username'       => 'Felhasználói név:',
'ancientpages'            => 'Régóta nem változott szócikkek',
'move'                    => 'Átnevezés',
'movethispage'            => 'Nevezd át ezt a lapot',
'unusedimagestext'        => 'Az alábbi fájlokat nem használjuk egyetlen oldalon sem.
Vedd figyelembe, hogy más weboldalak közvetlenül hivatkozhatnak egy fájl URL-jére, ezért szerepelhet itt annak ellenére, hogy aktívan használják.',
'unusedcategoriestext'    => 'A következő kategóriákban egyetlen szócikk, illetve alkategória sem szerepel.',
'notargettitle'           => 'Nincs cél',
'notargettext'            => 'Nem adtad meg annak a lapnak vagy szerkesztőnek a nevét, amin a műveletet végre akartad hajtani.',
'nopagetitle'             => 'A megadott céllap nem létezik',
'nopagetext'              => 'A megadott céllap nem létezik.',
'pager-newer-n'           => '{{PLURAL:$1|1 újabb|$1 újabb}}',
'pager-older-n'           => '{{PLURAL:$1|1 régebbi|$1 régebbi}}',
'suppress'                => 'adatvédelmi biztos',

# Book sources
'booksources'               => 'Könyvforrások',
'booksources-search-legend' => 'Könyvforrások keresése',
'booksources-go'            => 'Keresés',
'booksources-text'          => 'Alább látható a másik webhelyekre mutató hivatkozások listája, ahol új és használt könyveket árulnak, és
további információkat lelhetsz ott az általad keresett könyvekről:',
'booksources-invalid-isbn'  => 'A megadott ISBN hibásnak tűnik; ellenőrizd, hogy jól másoltad-e át az eredeti forrásból.',

# Special:Log
'specialloguserlabel'  => 'Felhasználó:',
'speciallogtitlelabel' => 'Cím:',
'log'                  => 'Rendszernaplók',
'all-logs-page'        => 'Minden nyilvános napló',
'alllogstext'          => 'A(z) {{SITENAME}} naplóinak összesített listája.
A napló típusának, a szerkesztő nevének (kis- és nagybetűérzékeny), vagy az érintett lap kiválasztásával (ez is kis- és nagybetűérzékeny) szűkítheted a találatok listáját.',
'logempty'             => 'Nincs illeszkedő naplóbejegyzés.',
'log-title-wildcard'   => 'Így kezdődő címek keresése',

# Special:AllPages
'allpages'          => 'Az összes lap listája',
'alphaindexline'    => '$1 – $2',
'nextpage'          => 'Következő lap ($1)',
'prevpage'          => 'Előző oldal ($1)',
'allpagesfrom'      => 'Lapok listázása a következő címtől kezdve:',
'allpagesto'        => 'Lapok listázása a következő címig:',
'allarticles'       => 'Az összes lap listája',
'allinnamespace'    => 'Összes lap ($1 névtér)',
'allnotinnamespace' => 'Minden olyan lap, ami nem a(z) $1 névtérben van.',
'allpagesprev'      => 'Előző',
'allpagesnext'      => 'Következő',
'allpagessubmit'    => 'Keresés',
'allpagesprefix'    => 'Lapok listázása, amik ezzel az előtaggal kezdődnek:',
'allpagesbadtitle'  => 'A megadott lapnév nyelvközi vagy wikiközi előtagot tartalmazott, vagy érvénytelen volt. Talán olyan karakter van benne, amit nem lehet lapnevekben használni.',
'allpages-bad-ns'   => 'A(z) {{SITENAME}} webhelyen nincs "$1" névtér.',

# Special:Categories
'categories'                    => 'Kategóriák',
'categoriespagetext'            => 'A következő {{PLURAL:$1|kategória tartalmaz|kategóriák tartalmaznak}} lapokat vagy fájlokat.
A [[Special:UnusedCategories|nem használt kategóriák]] nem jelennek meg.
Lásd még a [[Special:WantedCategories|keresett kategóriák]] listáját.',
'categoriesfrom'                => 'Kategóriák listázása a következő névtől kezdve:',
'special-categories-sort-count' => 'rendezés elemszám szerint',
'special-categories-sort-abc'   => 'rendezés ABC szerint',

# Special:DeletedContributions
'deletedcontributions'             => 'Törölt szerkesztések',
'deletedcontributions-title'       => 'Törölt szerkesztések',
'sp-deletedcontributions-contribs' => 'közreműködései',

# Special:LinkSearch
'linksearch'       => 'Külső hivatkozások',
'linksearch-pat'   => 'Keresett minta:',
'linksearch-ns'    => 'Névtér:',
'linksearch-ok'    => 'keresés',
'linksearch-text'  => 'Helyettesítő karaktereket is lehet használni, például "*.wikipedia.org".<br />
Támogatott protokollok: <tt>$1</tt>',
'linksearch-line'  => '$1 hivatkozva innen: $2',
'linksearch-error' => 'Helyettesítő karakterek csak a cím elején szerepelhetnek.',

# Special:ListUsers
'listusersfrom'      => 'Szerkesztők listázása a következő névtől kezdve:',
'listusers-submit'   => 'Megjelenítés',
'listusers-noresult' => 'Nem található szerkesztő.',
'listusers-blocked'  => '(blokkolva)',

# Special:ActiveUsers
'activeusers'            => 'Aktív szerkesztők listája',
'activeusers-intro'      => 'Ez a lap azon felhasználók listáját tartalmazza, akik csináltak valamilyen tevékenységet az elmúlt {{PLURAL:$1|egy|$1}} napban.',
'activeusers-count'      => '{{PLURAL:$1|egy|$1}} szerkesztés az utolsó {{PLURAL:$3|egy|$3}} napban',
'activeusers-from'       => 'Szerkesztők listázása a következő névtől kezdve:',
'activeusers-hidebots'   => 'Botok elrejtése',
'activeusers-hidesysops' => 'Adminisztrátorok elrejtése',
'activeusers-noresult'   => 'Nem található ilyen szerkesztő.',

# Special:Log/newusers
'newuserlogpage'              => 'Új szerkesztők naplója',
'newuserlogpagetext'          => 'Ez a napló az újonnan regisztrált szerkesztők listáját tartalmazza.',
'newuserlog-byemail'          => 'a jelszót kiküldtük a megadott e-mail címre',
'newuserlog-create-entry'     => 'új szerkesztőként regisztrált',
'newuserlog-create2-entry'    => 'új felhasználói fiókot hozott létre $1 néven',
'newuserlog-autocreate-entry' => 'Felhasználói fiók automatikusan létrehozva',

# Special:ListGroupRights
'listgrouprights'                      => 'Szerkesztői csoportok jogai',
'listgrouprights-summary'              => 'Lenn láthatóak a wikiben létező szerkesztői csoportok, valamint az azokhoz tartozó jogok.
Az egyes csoportokról további információt [[{{MediaWiki:Listgrouprights-helppage}}|itt]] találhatsz.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Kapott jog</span>
* <span class="listgrouprights-revoked">Elvett jog</span>',
'listgrouprights-group'                => 'Csoport',
'listgrouprights-rights'               => 'Jogok',
'listgrouprights-helppage'             => 'Help:Szerkesztői csoportok jogai',
'listgrouprights-members'              => '(tagok listája)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|ehhez a csoporthoz|ezekhez a csoportokhoz}} adhat szerkesztőket: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|ebből a csoportból|ezekből a csoportokból}} távolíthat el szerkesztőket: $1',
'listgrouprights-addgroup-all'         => 'bármelyik csoporthoz adhat szerkesztőket',
'listgrouprights-removegroup-all'      => 'bármelyik csoportból távolíthat el szerkesztőket',
'listgrouprights-addgroup-self'        => 'hozzáadhatja a következő {{PLURAL:$2|csoporthoz|csoportokhoz}} a saját fiókját: $1',
'listgrouprights-removegroup-self'     => 'eltávolíthatja a következő {{PLURAL:$2|csoportból|csoportokból}} a saját fiókját: $1',
'listgrouprights-addgroup-self-all'    => 'az összes csoportot hozzáadhatja a saját fiókjához',
'listgrouprights-removegroup-self-all' => 'az összes csoporból eltávolíthatja a saját fiókját',

# E-mail user
'mailnologin'          => 'Nincs feladó',
'mailnologintext'      => 'Ahhoz hogy másoknak e-mailt küldhess, [[Special:UserLogin|be kell jelentkezned]] és meg kell adnod egy érvényes e-mail címet a [[Special:Preferences|beállításaidban]].',
'emailuser'            => 'E-mail küldése ezen szerkesztőnek',
'emailpage'            => 'E-mail küldése',
'emailpagetext'        => 'A szerkesztő e-mail-címére ezen űrlap kitöltésével üzenetet tudsz küldeni.
Feladóként a [[Special:Preferences|beállításaid]]nál megadott e-mail-címed fog szerepelni, így a címzett közvetlenül neked tud majd válaszolni.',
'usermailererror'      => 'A levélküldő objektum hibával tért vissza:',
'defemailsubject'      => '{{SITENAME}} e-mail',
'usermaildisabled'     => 'Email fogadás letiltva',
'usermaildisabledtext' => 'Nem küldhetsz emailt más felhasználóknak ezen a wikin',
'noemailtitle'         => 'Nincs e-mail cím',
'noemailtext'          => 'Ez a szerkesztő nem adott meg érvényes e-mail címet.',
'nowikiemailtitle'     => 'Nem küldhető e-mail üzenet',
'nowikiemailtext'      => 'Ez a szerkesztő nem kíván másoktól e-mail üzeneteket fogadni.',
'email-legend'         => 'E-mail küldése egy másik {{SITENAME}}-szerkesztőnek',
'emailfrom'            => 'Feladó:',
'emailto'              => 'Címzett:',
'emailsubject'         => 'Téma:',
'emailmessage'         => 'Üzenet:',
'emailsend'            => 'Küldés',
'emailccme'            => 'Az üzenet másolatát küldje el nekem is e-mailben.',
'emailccsubject'       => '$1 szerkesztőnek küldött $2 tárgyú üzenet másolata',
'emailsent'            => 'E-mail elküldve',
'emailsenttext'        => 'Az e-mail üzenetedet elküldtem.',
'emailuserfooter'      => 'Ezt az e-mailt $1 küldte $2 számára, az „E-mail küldése ezen szerkesztőnek” funkció használatával a(z) {{SITENAME}} wikin.',

# User Messenger
'usermessage-summary' => 'Rendszerüzenet megadása.',
'usermessage-editor'  => 'Rendszerüzenetek',

# Watchlist
'watchlist'            => 'Figyelőlistám',
'mywatchlist'          => 'Figyelőlistám',
'watchlistfor2'        => '$1 felhasználó $2 eszközei',
'nowatchlist'          => 'Nincs lap a figyelőlistádon.',
'watchlistanontext'    => 'A figyelőlistád megtekintéséhez és szerkesztéséhez $1.',
'watchnologin'         => 'Nem vagy bejelentkezve',
'watchnologintext'     => 'Ahhoz, hogy figyelőlistád lehessen, [[Special:UserLogin|be kell lépned]].',
'addedwatch'           => 'Figyelőlistához hozzáfűzve',
'addedwatchtext'       => "A(z) „[[:$1]]” lapot hozzáadtam a [[Special:Watchlist|figyelőlistádhoz]].
Ezután minden, a lapon vagy annak vitalapján történő változást ott fogsz látni, és a lap '''vastagon''' fog szerepelni a [[Special:RecentChanges|friss változtatások]] lapon, hogy könnyen észrevehető legyen.",
'removedwatch'         => 'Figyelőlistáról eltávolítva',
'removedwatchtext'     => 'A(z) „[[:$1]]” lapot eltávolítottam a [[Special:Watchlist|figyelőlistáról]].',
'watch'                => 'Lap figyelése',
'watchthispage'        => 'Lap figyelése',
'unwatch'              => 'Lapfigyelés vége',
'unwatchthispage'      => 'Figyelés leállítása',
'notanarticle'         => 'Nem szócikk',
'notvisiblerev'        => 'A változat törölve lett',
'watchnochange'        => 'Egyik figyelt lap sem változott a megadott időintervallumon belül.',
'watchlist-details'    => 'A vitalapokon kívül {{PLURAL:$1|egy|$1}} lap van a figyelőlistádon.',
'wlheader-enotif'      => '* Az e-mailen keresztül történő értesítés engedélyezve.',
'wlheader-showupdated' => "* Azok a lapok, amelyek megváltoztak, mióta utoljára megnézted őket, '''vastagon''' láthatóak.",
'watchmethod-recent'   => 'a figyelt lapokon belüli legfrissebb szerkesztések',
'watchmethod-list'     => 'a legfrissebb szerkesztésekben található figyelt lapok',
'watchlistcontains'    => 'A figyelőlistádon {{PLURAL:$1|egy|$1}} lap szerepel.',
'iteminvalidname'      => "Probléma a '$1' elemmel: érvénytelen név...",
'wlnote'               => "Az utolsó '''{{PLURAL:$2|egy|$2}}''' óra '''{{PLURAL:$1|egy|$1}}''' változtatása látható az alábbiakban.",
'wlshowlast'           => 'Az elmúlt $1 órában | $2 napon | $3 történt változtatások legyenek láthatóak',
'watchlist-options'    => 'A figyelőlista beállításai',

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
'enotif_lastvisited'           => 'Lásd a $1 lapot az utolsó látogatásod óta történt változtatásokért.',
'enotif_lastdiff'              => 'Lásd a $1 lapot ezen változtatás megtekintéséhez.',
'enotif_anon_editor'           => '$1 névtelen felhasználó',
'enotif_body'                  => 'Kedves $WATCHINGUSERNAME!


$PAGEEDITOR $PAGEEDITDATE-kor $CHANGEDORCREATED a(z) $PAGETITLE című lapot a(z) {{SITENAME}} wikin; a jelenlegi verziót a $PAGETITLE_URL webcímen találod.

$NEWPAGE

A szerkesztési összefoglaló a következő volt: $PAGESUMMARY $PAGEMINOREDIT

A szerkesztő elérhetősége:
e-mail küldése: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Amíg nem keresed fel az oldalt, addig nem érkeznek újabb értesítések az oldal változásaival kapcsolatban. A figyelőlistádon is beállíthatod, hogy újból kapj értesítéseket, az összes lap után.

             Baráti üdvözlettel: a(z) {{SITENAME}} értesítő rendszere

--
A figyelőlistád módosításához keresd fel a
{{fullurl:{{#special:Watchlist}}/edit}} címet

A lap figyelőlistádról való törléséhez keresd fel a
$UNWATCHURL címet

Visszajelzés és további segítség:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Lap törlése',
'confirm'                => 'Megerősítés',
'excontent'              => 'a lap tartalma: „$1”',
'excontentauthor'        => 'a lap tartalma: „$1” (és csak „[[Special:Contributions/$2|$2]]” szerkesztette)',
'exbeforeblank'          => 'az eltávolítás előtti tartalom: „$1”',
'exblank'                => 'a lap üres volt',
'delete-confirm'         => '$1 törlése',
'delete-legend'          => 'Törlés',
'historywarning'         => "'''Figyelem:''' a lapnak, amit törölni készülsz, körülbelül $1 változattal rendelkező laptörténete van:",
'confirmdeletetext'      => 'Egy lapot vagy fájlt készülsz törölni a teljes laptörténetével együtt.
Kérjük, erősítsd meg, hogy valóban ezt szeretnéd tenni, átlátod a következményeit, és hogy a műveletet a [[{{MediaWiki:Policy-url}}|törlési irányelvekkel]] összhangban végzed.',
'actioncomplete'         => 'Művelet végrehajtva',
'actionfailed'           => 'A művelet nem sikerült',
'deletedtext'            => 'A(z) „<nowiki>$1</nowiki>” lapot törölted.
A legutóbbi törlések listájához lásd a $2 lapot.',
'deletedarticle'         => '„[[$1]]” törölve',
'suppressedarticle'      => 'elrejtette a(z) „[[$1]]” szócikket',
'dellogpage'             => 'Törlési_napló',
'dellogpagetext'         => 'Itt láthatók a legutóbb törölt lapok.',
'deletionlog'            => 'törlési napló',
'reverted'               => 'Visszaállítva a korábbi változatra',
'deletecomment'          => 'Ok:',
'deleteotherreason'      => 'További indoklás:',
'deletereasonotherlist'  => 'Egyéb indok',
'deletereason-dropdown'  => '*Gyakori törlési okok
** Szerző kérésére
** Jogsértő
** Vandalizmus',
'delete-edit-reasonlist' => 'Törlési okok szerkesztése',
'delete-toobig'          => 'Ennek a lapnak a laptörténete több mint {{PLURAL:$1|egy|$1}} változatot őriz. A szervert kímélendő az ilyen lapok törlése nem engedélyezett.',
'delete-warning-toobig'  => 'Ennek a lapnak a laptörténete több mint {{PLURAL:$1|egy|$1}} változatot őriz. Törlése fennakadásokat okozhat a wiki adatbázis-műveleteiben; óvatosan járj el.',

# Rollback
'rollback'          => 'Szerkesztések visszaállítása',
'rollback_short'    => 'Visszaállítás',
'rollbacklink'      => 'visszaállítás',
'rollbackfailed'    => 'A visszaállítás nem sikerült',
'cantrollback'      => 'Nem lehet visszaállítani: az utolsó szerkesztést végző felhasználó az egyetlen, aki a lapot szerkesztette.',
'alreadyrolled'     => '[[:$1]] utolsó, [[User:$2|$2]] ([[User talk:$2|vita]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) általi szerkesztését nem lehet visszavonni:
időközben valaki már visszavonta, vagy szerkesztette a lapot.

Az utolsó szerkesztést [[User:$3|$3]] ([[User talk:$3|vita]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) végezte.',
'editcomment'       => "A szerkesztési összefoglaló „''$1''” volt.",
'revertpage'        => 'Visszaállítottam a lap korábbi változatát: [[Special:Contributions/$2|$2]]  ([[User talk:$2|vita]]) szerkesztéséről [[User:$1|$1]] szerkesztésére',
'revertpage-nouser' => 'Visszaállítottam a lap korábbi változatát (szerkesztőnév eltávolítva) szerkesztéséről [[User:$1|$1]] szerkesztésére',
'rollback-success'  => '$1 szerkesztéseit visszaállítottam $2 utolsó változatára.',

# Edit tokens
'sessionfailure-title' => 'Munkamenethiba',
'sessionfailure'       => 'Úgy látszik, hogy probléma van a bejelentkezési munkameneteddel;
ez a művelet a munkamenet eltérítése miatti óvatosságból megszakadt.
Kérjük, hogy nyomd meg a "vissza" gombot, és töltsd le újra az oldalt, ahonnan jöttél, majd próbáld újra.',

# Protect
'protectlogpage'              => 'Lapvédelmi_napló',
'protectlogtext'              => 'Ez a lapok lezárásának és megnyitásának listája. A [[Special:ProtectedPages|védett lapok listáján]] megtekintheted a jelenleg is érvényben lévő védelmeket.',
'protectedarticle'            => 'levédte a(z) [[$1]] lapot',
'modifiedarticleprotection'   => 'megváltoztatta a(z) „[[$1]]” lap védelmi szintjét',
'unprotectedarticle'          => 'eltávolította a védelmet a(z) „[[$1]]” lapról',
'movedarticleprotection'      => 'áthelyezte „[[$2]]” védelmi beállításait „[[$1]]” cím alá',
'protect-title'               => '„$1” levédése',
'prot_1movedto2'              => '[[$1]] lapot átneveztem [[$2]] névre',
'protect-legend'              => 'Levédés megerősítése',
'protectcomment'              => 'Ok:',
'protectexpiry'               => 'Időtartam',
'protect_expiry_invalid'      => 'A lejárati idő érvénytelen.',
'protect_expiry_old'          => 'A lejárati idő a múltban van.',
'protect-unchain-permissions' => 'További védelmi lehetőségek feloldása',
'protect-text'                => "Itt megtekintheted és módosíthatod a(z) '''<nowiki>$1</nowiki>''' lap védelmi szintjét.",
'protect-locked-blocked'      => "Nem változtathatod meg a védelmi szinteket, amíg blokkolnak. Itt vannak a(z)
'''$1''' lap jelenlegi beállításai:",
'protect-locked-dblock'       => "A védelmi szinteket egy aktív adatbázis zárolás miatt nem változtathatod meg.
Itt vannak a(z) '''$1''' lap jelenlegi beállításai:",
'protect-locked-access'       => "A fiókod számára nem engedélyezett a védelmi szintek megváltoztatása.
Itt vannak a(z) '''$1''' lap jelenlegi beállításai:",
'protect-cascadeon'           => 'A lap le van védve, mert {{PLURAL:$1|tartalmazza az alábbi lap, amelyen|tartalmazzák az alábbi lapok, amelyeken}}
be van kapcsolva a kaszkád védelem.
Megváltoztathatod ezen lap védelmi szintjét, de az nem lesz hatással a kaszkád védelemre.',
'protect-default'             => 'Minden szerkesztő számára engedélyezett',
'protect-fallback'            => '"$1" engedély szükséges hozzá',
'protect-level-autoconfirmed' => 'Nem és frissen regisztrált szerkesztők blokkolása',
'protect-level-sysop'         => 'Csak adminisztrátorok',
'protect-summary-cascade'     => 'kaszkád védelem',
'protect-expiring'            => 'lejár: $1 (UTC)',
'protect-expiry-indefinite'   => 'határozatlan',
'protect-cascade'             => 'Kaszkád védelem – védjen le minden lapot, amit ez a lap tartalmaz.',
'protect-cantedit'            => 'Nem változtathatod meg a lap védelmi szintjét, mert nincs jogod a szerkesztéséhez.',
'protect-othertime'           => 'Más időtartam:',
'protect-othertime-op'        => 'más időtartam',
'protect-existing-expiry'     => 'Jelenleg érvényben lévő lejárati idő: $2, $3',
'protect-otherreason'         => 'További okok:',
'protect-otherreason-op'      => 'Más/további ok:',
'protect-dropdown'            => '*Általános védelmi okok
** Gyakori vandalizmus
** Gyakori spamelés
** Nagyforgalmú lap',
'protect-edit-reasonlist'     => 'Lapvédelem oka',
'protect-expiry-options'      => '1 óra:1 hour,1 nap:1 day,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite',
'restriction-type'            => 'Engedély:',
'restriction-level'           => 'Korlátozási szint:',
'minimum-size'                => 'Legkisebb méret',
'maximum-size'                => 'Legnagyobb méret',
'pagesize'                    => '(bájt)',

# Restrictions (nouns)
'restriction-edit'   => 'Szerkesztés',
'restriction-move'   => 'Átnevezés',
'restriction-create' => 'Létrehozás',
'restriction-upload' => 'Feltöltés',

# Restriction levels
'restriction-level-sysop'         => 'teljesen védett',
'restriction-level-autoconfirmed' => 'félig védett',
'restriction-level-all'           => 'bármilyen szint',

# Undelete
'undelete'                     => 'Törölt lap helyreállítása',
'undeletepage'                 => 'Törölt lapok megtekintése és helyreállítása',
'undeletepagetitle'            => "'''A(z) [[:$1]] lap törölt változatai alább láthatók.'''",
'viewdeletedpage'              => 'Törölt lapok megtekintése',
'undeletepagetext'             => 'Az alábbi {{PLURAL:$1|lapot törölték, de még helyreállítható|$1 lapot törölték, de még helyreállíthatók}} az archívumból.
Az archívumot időről időre üríthetik!',
'undelete-fieldset-title'      => 'Változatok helyreállítása',
'undeleteextrahelp'            => "A lap teljes helyreállításához ne jelölj be egy jelölőnégyzetet sem, csak kattints a '''''Helyreállítás''''' gombra.
A lap részleges helyreállításához jelöld be a kívánt változatok melletti jelölőnégyzeteket, és kattints a '''''Helyreállítás''''' gombra.
Ha megnyomod a '''''Vissza''''' gombot, az törli a jelölőnégyzetek és az összefoglaló jelenlegi tartalmát.",
'undeleterevisions'            => '{{PLURAL:$1|egy|$1}} változat archiválva',
'undeletehistory'              => 'Ha helyreállítasz egy lapot, azzal visszahozod laptörténet összes változatát.
Ha lap törlése óta azonos néven már létrehoztak egy újabb lapot, a helyreállított
változatok a laptörténet végére kerülnek be, a jelenlegi lapváltozat módosítása nélkül.',
'undeleterevdel'               => 'A visszavonás nem hajtható végre, ha a legfrissebb lapváltozat részben
törlését eredmémyezi. Ilyen esetekben törölnöd kell a legújabb törölt változatok kijelölését, vagy megszüntetni az elrejtésüket. Azon fájlváltozatok,
melyek megtekintése a számodra nem engedélyezett, nem kerülnek visszaállításra.',
'undeletehistorynoadmin'       => 'Ezt a szócikket törölték. A törlés okát alább az összegzésben
láthatod, az oldalt a törlés előtt szerkesztő felhasználók részleteivel együtt. Ezeknek
a törölt változatoknak a tényleges szövege csak az adminisztrátorok számára hozzáférhető.',
'undelete-revision'            => '$1 $4, $5-kori törölt változata (szerző: $3).',
'undeleterevision-missing'     => 'Érvénytelen vagy hiányzó változat. Lehet, hogy rossz hivatkozásod van, ill. a
változatot visszaállították vagy eltávolították az archívumból.',
'undelete-nodiff'              => 'Nem található korábbi változat.',
'undeletebtn'                  => 'Helyreállítás',
'undeletelink'                 => 'megtekintés/helyreállítás',
'undeleteviewlink'             => 'megtekintés',
'undeletereset'                => 'Vissza',
'undeleteinvert'               => 'Kijelölés megfordítása',
'undeletecomment'              => 'Ok:',
'undeletedarticle'             => '„[[$1]]” helyreállítva',
'undeletedrevisions'           => '{{PLURAL:$1|egy|$1}} változat helyreállítva',
'undeletedrevisions-files'     => '{{PLURAL:$1|egy|$1}} változat és {{PLURAL:$2|egy|$2}} fájl visszaállítva',
'undeletedfiles'               => '{{PLURAL:$1|egy|$1}} fájl visszaállítva',
'cannotundelete'               => 'Nem lehet a lapot visszaállítani; lehet, hogy azt már valaki visszaállította.',
'undeletedpage'                => "'''$1 helyreállítva'''

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
'undelete-show-file-confirm'   => 'Biztosan meg akarod nézni a(z) "<nowiki>$1</nowiki>" fájl $2, $3-kori törölt változatát?',
'undelete-show-file-submit'    => 'Igen',

# Namespace form on various pages
'namespace'      => 'Névtér:',
'invert'         => 'Kijelölés megfordítása',
'blanknamespace' => '(Fő)',

# Contributions
'contributions'       => 'Szerkesztő közreműködései',
'contributions-title' => '$1 közreműködései',
'mycontris'           => 'Közreműködéseim',
'contribsub2'         => '$1 ($2)',
'nocontribs'          => 'Nem található a feltételeknek megfelelő változtatás.',
'uctop'               => ' (utolsó)',
'month'               => 'E hónap végéig:',
'year'                => 'Eddig az évig:',

'sp-contributions-newbies'             => 'Csak a nemrég regisztrált szerkesztők közreműködéseinek mutatása',
'sp-contributions-newbies-sub'         => 'Új szerkesztők lapjai',
'sp-contributions-newbies-title'       => 'Új szerkesztők közreműködései',
'sp-contributions-blocklog'            => 'Blokkolási napló',
'sp-contributions-deleted'             => 'törölt szerkesztések',
'sp-contributions-uploads'             => 'feltöltések',
'sp-contributions-logs'                => 'naplók',
'sp-contributions-talk'                => 'vitalap',
'sp-contributions-userrights'          => 'szerkesztői jogok beállítása',
'sp-contributions-blocked-notice'      => 'Ez a szerkesztő blokkolva van. A blokknapló legutóbbi ide vonatkozó bejegyzése a következő:',
'sp-contributions-blocked-notice-anon' => 'Ez az IP-cím blokkolva van.
A blokknapló legutóbbi ide vonatkozó bejegyzése a következő:',
'sp-contributions-search'              => 'Közreműködések szűrése',
'sp-contributions-username'            => 'IP-cím vagy felhasználónév:',
'sp-contributions-toponly'             => 'Csak a jelenleg utolsónak számító változtatásokat mutassa',
'sp-contributions-submit'              => 'Keresés',

# What links here
'whatlinkshere'            => 'Mi hivatkozik erre',
'whatlinkshere-title'      => 'A(z) „$1” lapra hivatkozó lapok',
'whatlinkshere-page'       => 'Lap:',
'linkshere'                => 'Az alábbi lapok hivatkoznak erre: [[:$1]]',
'nolinkshere'              => '[[:$1]]: erre a lapra semmi nem hivatkozik.',
'nolinkshere-ns'           => "A kiválasztott névtérben egyetlen oldal sem hivatkozik a(z) '''[[:$1]]''' lapra.",
'isredirect'               => 'átirányítás',
'istemplate'               => 'beillesztve',
'isimage'                  => 'képhivatkozás',
'whatlinkshere-prev'       => '{{PLURAL:$1|előző|előző $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|következő|következő $1}}',
'whatlinkshere-links'      => '← erre mutató hivatkozások',
'whatlinkshere-hideredirs' => 'átirányítások $1',
'whatlinkshere-hidetrans'  => 'beillesztések $1',
'whatlinkshere-hidelinks'  => 'linkek $1',
'whatlinkshere-hideimages' => 'képhivatkozás $1',
'whatlinkshere-filters'    => 'Elemek szűrése',

# Block/unblock
'blockip'                         => 'Blokkolás',
'blockip-title'                   => 'Felhasználó blokkolása',
'blockip-legend'                  => 'Felhasználó blokkolása',
'blockiptext'                     => 'Az alábbi űrlap segítségével megvonhatod egy szerkesztő vagy IP-cím szerkesztési jogait.
Ügyelj rá, hogy az intézkedésed mindig legyen tekintettel a vonatkozó [[{{MediaWiki:Policy-url}}|irányelvekre]].
Add meg a blokkolás okát is (például idézd a blokkolandó személy által vandalizált lapokat).',
'ipaddress'                       => 'IP-cím',
'ipadressorusername'              => 'IP-cím vagy felhasználói név',
'ipbexpiry'                       => 'Lejárat:',
'ipbreason'                       => 'Ok:',
'ipbreasonotherlist'              => 'Más ok',
'ipbreason-dropdown'              => '*Gyakori blokkolási okok
** Téves információ beírása
** Lapok tartalmának eltávolítása
** Spammelgetés, reklámlinkek tömködése a lapokba
** Értelmetlen megjegyzések, halandzsa beillesztése a cikkekbe
** Megfélemlítő viselkedés, zaklatás
** Több szerkesztői fiókkal való visszaélés
** Elfogadhatatlan azonosító',
'ipbanononly'                     => 'Csak anonim felhasználók blokkolása',
'ipbcreateaccount'                => 'Új regisztráció megakadályozása',
'ipbemailban'                     => 'E-mailt se tudjon küldeni',
'ipbenableautoblock'              => 'A szerkesztő által használt IP-címek automatikus blokkolása',
'ipbsubmit'                       => 'Blokkolás',
'ipbother'                        => 'Más időtartam:',
'ipboptions'                      => '2 óra:2 hours,1 nap:1 day,3 nap:3 days,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite',
'ipbotheroption'                  => 'Más időtartam',
'ipbotherreason'                  => 'Más/további ok:',
'ipbhidename'                     => 'A felhasználónév ne jelenjen meg a szerkesztéseknél és a listákban',
'ipbwatchuser'                    => 'A felhasználó lapjának és vitalapjának figyelése',
'ipballowusertalk'                => 'A szerkesztő módosíthatja saját vitalapját a blokkolás ideje alatt',
'ipb-change-block'                => 'Blokk beállításainak megváltoztatása',
'badipaddress'                    => 'Érvénytelen IP-cím',
'blockipsuccesssub'               => 'Sikeres blokkolás',
'blockipsuccesstext'              => '„[[Special:Contributions/$1|$1]]” felhasználót blokkoltad.
<br />Lásd a [[Special:IPBlockList|blokkolt IP-címek listáját]] az érvényben lévő blokkok áttekintéséhez.',
'ipb-edit-dropdown'               => 'Blokkolási okok szerkesztése',
'ipb-unblock-addr'                => '$1 blokkjának feloldása',
'ipb-unblock'                     => 'Felhasználónév vagy IP-cím blokkolásának feloldása',
'ipb-blocklist'                   => 'Létező blokkok megtekintése',
'ipb-blocklist-contribs'          => '$1 közreműködései',
'unblockip'                       => 'Blokk feloldása',
'unblockiptext'                   => 'Itt tudod visszaadni egy blokkolt felhasználónévnek vagy IP-nek a szerkesztési jogosultságot.',
'ipusubmit'                       => 'Blokk eltávolítása',
'unblocked'                       => '[[User:$1|$1]] blokkolása feloldva',
'unblocked-id'                    => '$1 blokkolása feloldásra került',
'ipblocklist'                     => 'Blokkolt IP-címek és felhasználónevek listája',
'ipblocklist-legend'              => 'Blokkolt felhasználó keresése',
'ipblocklist-username'            => 'Felhasználónév vagy IP-cím:',
'ipblocklist-sh-userblocks'       => 'felhasználói fiókok blokkjainak $1',
'ipblocklist-sh-tempblocks'       => 'ideiglenes blokkok $1',
'ipblocklist-sh-addressblocks'    => 'egy IP-címre vonatkozó blokkok $1',
'ipblocklist-submit'              => 'Keresés',
'ipblocklist-localblock'          => 'Helyi blokk',
'ipblocklist-otherblocks'         => 'További {{PLURAL:$1|blokk|blokkok}}',
'blocklistline'                   => '$1, $2 blokkolta $3 felhasználót ($4)',
'infiniteblock'                   => 'végtelen',
'expiringblock'                   => 'lejárat: $1 $2',
'anononlyblock'                   => 'csak anon.',
'noautoblockblock'                => 'az automatikus blokkolás letiltott',
'createaccountblock'              => 'új felhasználó létrehozása blokkolva',
'emailblock'                      => 'e-mail cím blokkolva',
'blocklist-nousertalk'            => 'nem szerkeszthetik a vitalapjukat',
'ipblocklist-empty'               => 'A blokkoltak listája üres.',
'ipblocklist-no-results'          => 'A kért IP-cím vagy felhasználónév nem blokkolt.',
'blocklink'                       => 'blokkolás',
'unblocklink'                     => 'blokk feloldása',
'change-blocklink'                => 'blokkolás módosítása',
'contribslink'                    => 'szerkesztései',
'autoblocker'                     => "Az általad használt IP-cím autoblokkolva van, mivel korábban a kitiltott „[[User:$1|$1]]” használta. ($1 blokkolásának indoklása: „'''$2'''”) Ha nem te vagy $1, lépj kapcsolatba valamelyik adminisztrátorral, és kérd az autoblokk feloldását. Ne felejtsd el megírni neki, hogy kinek szóló blokkba ütköztél bele!",
'blocklogpage'                    => 'Blokkolási napló',
'blocklog-showlog'                => 'Ez a felhasználó már blokkolva volt korábban. A blokkolási napló ide vonatkozó része alább látható:',
'blocklog-showsuppresslog'        => 'Ez a felhasználó korábban blokkot kapott, és a naplóbejegyzés el lett rejtve. Az elrejtési napló alább látható tájékoztatásként:',
'blocklogentry'                   => '„[[$1]]” blokkolva $2 $3 időtartamra',
'reblock-logentry'                => 'megváltoztatta [[$1]] blokkjának beállításait, a blokk lejárta: $2 $3',
'blocklogtext'                    => 'Ez a felhasználókra helyezett blokkoknak és azok feloldásának listája. Az IP-autoblokkok nem szerepelnek a listában. Lásd még [[Special:IPBlockList|a jelenleg életben lévő blokkok listáját]].',
'unblocklogentry'                 => '„$1” blokkolása feloldva',
'block-log-flags-anononly'        => 'csak anonok',
'block-log-flags-nocreate'        => 'nem hozhat létre új fiókot',
'block-log-flags-noautoblock'     => 'autoblokk kikapcsolva',
'block-log-flags-noemail'         => 'e-mail blokkolva',
'block-log-flags-nousertalk'      => 'saját vitalapját sem szerkesztheti',
'block-log-flags-angry-autoblock' => 'bővített automatikus blokk bekapcsolva',
'block-log-flags-hiddenname'      => 'rejtett felhasználónév',
'range_block_disabled'            => 'A rendszerfelelős tartományblokkolás létrehozási képessége letiltott.',
'ipb_expiry_invalid'              => 'Hibás lejárati dátum.',
'ipb_expiry_temp'                 => 'A láthatatlan felhasználóinév-blokkok lehetnek állandóak.',
'ipb_hide_invalid'                => 'A felhasználói fiókot nem lehet elrejteni; lehet, hogy túl sok szerkesztése van.',
'ipb_already_blocked'             => '"$1" már blokkolva',
'ipb-needreblock'                 => '== Már blokkolva ==
$1 már blokkolva van. Meg szeretnéd változtatni a beállításokat?',
'ipb-otherblocks-header'          => 'További {{PLURAL:$1|blokk|blokkok}}',
'ipb_cant_unblock'                => 'Hiba: A(z) $1 blokkolási azonosító nem található. Lehet, hogy már feloldották a blokkolását.',
'ipb_blocked_as_range'            => 'Hiba: a(z) $1 IP-cím nem blokkolható közvetlenül, és nem lehet feloldani. A(z) $2 tartomány részeként van blokkolva, amely feloldható.',
'ip_range_invalid'                => 'Érvénytelen IP-tartomány.',
'ip_range_toolarge'               => 'Nem engedélyezettek azok a tartományblokkok, melyek nagyobbak mint /$1.',
'blockme'                         => 'Saját magam blokkolása',
'proxyblocker'                    => 'Proxyblokkoló',
'proxyblocker-disabled'           => 'Ez a funkció le van tiltva.',
'proxyblockreason'                => "Az IP-címeden ''nyílt proxy'' üzemel. Amennyiben nem használsz proxyt, vedd fel a kapcsolatot egy informatikussal vagy az internetszolgáltatóddal ezen súlyos biztonsági probléma ügyében.",
'proxyblocksuccess'               => 'Kész.',
'sorbsreason'                     => 'Az IP-címed nyitott proxyként szerepel e webhely által használt DNSBL listán.',
'sorbs_create_account_reason'     => 'Az IP-címed nyitott proxyként szerepel e webhely által használt DNSBL listán. Nem hozhatsz létre fiókot.',
'cant-block-while-blocked'        => 'Nem blokkolhatsz más szerkesztőket, miközben te magad blokkolva vagy.',
'cant-see-hidden-user'            => 'A felhasználó, akit blokkolni próbáltál már blokkolva és rejtve van. Mivel nincs felhasználó elrejtése jogosultságod, nem láthatod és nem szerkesztheted a felhasználó blokkját.',
'ipbblocked'                      => 'Nem blokkolhatsz és nem oldhatod fel más felhasználók blokkjait, mert te magad is blokkolva vagy',
'ipbnounblockself'                => 'Nincs jogosultságod feloldani a saját felhasználói fiókod blokkját',

# Developer tools
'lockdb'              => 'Adatbázis zárolása',
'unlockdb'            => 'Adatbázis kinyitása',
'lockdbtext'          => 'Az adatbázis zárolása felfüggeszti valamennyi szerkesztő
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
<br />A karbantartás befejezése után ne feledd el [[Special:UnlockDB|kinyitni]].',
'unlockdbsuccesstext' => 'Az adatbázis kinyitása kész.',
'lockfilenotwritable' => 'Az adatbázist zároló fájl nem írható. Az adatbázis zárolásához vagy kinyitásához ennek a webkiszolgáló által írhatónak kell lennie.',
'databasenotlocked'   => 'Az adatbázis nincs lezárva.',

# Move page
'move-page'                    => '$1 átnevezése',
'move-page-legend'             => 'Lap átnevezése',
'movepagetext'                 => "Az alábbi űrlap használatával nevezhetsz át egy lapot, és helyezheted át teljes laptörténetét az új nevére.
A régi cím az új címre való átirányítás lesz.
Frissítheted a régi címre mutató átirányításokat, hogy azok automatikusan a megfelelő címre mutassanak;
ha nem teszed, ellenőrizd a [[Special:DoubleRedirects|dupla]] vagy [[Special:BrokenRedirects|hibás átirányításokat]].
Neked kell biztosítanod, hogy a linkek továbbra is oda mutassanak, ahová mutatniuk kell.

A lap '''nem''' nevezhető át, ha már van egy ugyanilyen című lap, hacsak nem üres vagy átirányítás, és nincs laptörténete.
Ez azt jelenti, hogy vissza tudsz nevezni egy tévedésből átnevezett lapot, és nem tudsz egy már létező lapot véletlenül felülírni.

'''FIGYELEM!'''
Népszerű oldalak esetén ez drasztikus és nem várt változtatás lehet;
győződj meg a folytatás előtt arról, hogy tisztában vagy-e a következményekkel.",
'movepagetalktext'             => "A laphoz tartozó vitalap automatikusan átneveződik, '''kivéve, ha:'''
*már létezik egy nem üres vitalap az új helyen,
*nem jelölöd be a lenti pipát.

Ezen esetekben a vitalapot külön, kézzel kell átnevezned a kívánságaid szerint.",
'movearticle'                  => 'Lap átnevezése',
'moveuserpage-warning'         => "'''Figyelem:''' Egy felhasználólapot készülsz átmozgatni. Csak a lap lesz átmozgatva, a szerkesztő ''nem'' lesz átnevezve.",
'movenologin'                  => 'Nem jelentkeztél be',
'movenologintext'              => 'Ahhoz, hogy átnevezhess egy lapot, [[Special:UserLogin|be kell lépned]].',
'movenotallowed'               => 'Nincs jogod a lapok átnevezéséhez.',
'movenotallowedfile'           => 'Nincs megfelelő jogosultságod a fájlok átnevezéséhez.',
'cant-move-user-page'          => 'Nem nevezhetsz át szerkesztői lapokat (az allapokon kívül).',
'cant-move-to-user-page'       => 'Nincs jogosultságod átnevezni egy lapot szerkesztői lapnak (kivéve annak allapjának).',
'newtitle'                     => 'Az új cím:',
'move-watch'                   => 'Figyeld a lapot',
'movepagebtn'                  => 'Lap átnevezése',
'pagemovedsub'                 => 'Átnevezés sikeres',
'movepage-moved'               => "'''„$1” átnevezve „$2” névre'''",
'movepage-moved-redirect'      => 'Átirányítás létrehozva.',
'movepage-moved-noredirect'    => 'A régi címről nem készült átirányítás.',
'articleexists'                => 'Ilyen névvel már létezik lap, vagy az általad választott név érvénytelen.
Kérlek, válassz egy másik nevet.',
'cantmove-titleprotected'      => 'Nem nevezheted át a lapot, mert az új cím le van védve a létrehozás ellen.',
'talkexists'                   => 'A lap átnevezése sikerült, de a hozzá tartozó vitalapot nem tudtam átnevezni, mert már létezik egy egyező nevű lap az új helyen. Kérjük, gondoskodj a két lap összefűzéséről.',
'movedto'                      => 'átnevezve',
'movetalk'                     => 'Nevezd át a vitalapot is, ha lehetséges',
'move-subpages'                => 'Allapok átnevezése (maximum $1)',
'move-talk-subpages'           => 'A vitalap allapjainak átnevezése (maximum $1)',
'movepage-page-exists'         => 'A(z) „$1” nevű lap már létezik, és nem írható felül automatikusan.',
'movepage-page-moved'          => 'A(z) „$1” nevű lap át lett nevezve „$2” névre.',
'movepage-page-unmoved'        => 'A(z) „$1” nevű lap nem nevezhető át „$2” névre.',
'movepage-max-pages'           => '{{PLURAL:$1|Egy|$1}} lapnál több nem nevezhető át automatikusan, így a további lapok a helyükön maradnak.',
'1movedto2'                    => '[[$1]] lapot átneveztem [[$2]] névre',
'1movedto2_redir'              => '[[$1]] lapot átneveztem [[$2]] névre (az átirányítást felülírva)',
'move-redirect-suppressed'     => 'átirányítás nélkül',
'movelogpage'                  => 'Átnevezési napló',
'movelogpagetext'              => 'Az alábbiakban az átnevezett lapok listája látható.',
'movesubpage'                  => '{{PLURAL:$1|Allap|Allapok}}',
'movesubpagetext'              => 'Ennek a lapnak {{PLURAL:$1|egy|$1}} allapja van.',
'movenosubpage'                => 'Ez a lap nem rendelkezik allapokkal.',
'movereason'                   => 'Indoklás:',
'revertmove'                   => 'visszaállítás',
'delete_and_move'              => 'Törlés és átnevezés',
'delete_and_move_text'         => '== Törlés szükséges ==

Az átnevezés céljaként megadott „[[:$1]]” szócikk már létezik.  Ha az átnevezést végre akarod hajtani, ezt a lapot törölni kell.  Valóban ezt szeretnéd?',
'delete_and_move_confirm'      => 'Igen, töröld a lapot',
'delete_and_move_reason'       => 'átnevezendő lap célneve felszabadítva',
'selfmove'                     => 'A cikk jelenlegi címe megegyezik azzal, amire át szeretnéd mozgatni. Egy szócikket saját magára mozgatni nem lehet.',
'immobile-source-namespace'    => 'A(z) „$1” névtér lapjai nem nevezhetőek át',
'immobile-target-namespace'    => 'A(z) „$1” névtérbe nem mozgathatsz át lapokat',
'immobile-target-namespace-iw' => 'Wikiközi hivatkozás nem lehet a lap új neve.',
'immobile-source-page'         => 'Ez a lap nem nevezhető át.',
'immobile-target-page'         => 'A lap nem helyezhető át a megadott címre.',
'imagenocrossnamespace'        => 'A fájlok nem helyezhetőek át más névtérbe',
'nonfile-cannot-move-to-file'  => 'Nem fájlok nem nevezhetők át fájlnévtérbe',
'imagetypemismatch'            => 'Az új kiterjesztés nem egyezik meg a fájl típusával',
'imageinvalidfilename'         => 'A célnév érvénytelen',
'fix-double-redirects'         => 'Az eredeti címre mutató hivatkozások frissítése',
'move-leave-redirect'          => 'Átirányítás készítése a régi címről az új címre',
'protectedpagemovewarning'     => "'''Figyelem:''' Ez a lap le van védve, így csak adminisztrátori jogosultságokkal rendelkező szerkesztők nevezhetik át.
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'semiprotectedpagemovewarning' => "'''Figyelem:''' Ez a lap le van védve, így csak regisztrált felhasználók nevezhetik át.
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'move-over-sharedrepo'         => '== Létező fájlnév ==

A(z) [[:$1]] néven már létezik fájl egy megosztott tárhelyen. Ha ilyen néven töltöd fel, el fogja takarni a közös tárhelyen levőt.',
'file-exists-sharedrepo'       => 'A választott fájlnév már használatban van egy közös tárhelyen.
Kérlek válassz másik nevet.',

# Export
'export'            => 'Lapok exportálása',
'exporttext'        => 'Egy adott lap vagy lapcsoport szövegét és laptörténetét exportálhatod XML-be. A kapott
fájlt importálhatod egy másik MediaWiki alapú rendszerbe
a Special:Import lapon keresztül.

Lapok exportálásához add meg a címüket a lenti szövegdobozban (minden címet külön sorba), és válaszd ki,
hogy az összes korábbi változatra és a teljes laptörténetekre szükséged van-e, vagy csak az aktuális
változatok és a legutolsó változtatásokra vonatkozó információk kellenek.

Az utóbbi esetben közvetlen hivatkozást is használhatsz, például a [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] a "[[{{MediaWiki:Mainpage}}]]" nevű lapot exportálja.',
'exportcuronly'     => 'Csak a legfrissebb állapot, teljes laptörténet nélkül',
'exportnohistory'   => "----
'''Megjegyzés:''' A lapok teljes előzményeinek ezen az űrlapon keresztül történő exportálása teljesítményporlbémák miatt letiltott.",
'export-submit'     => 'Exportálás',
'export-addcattext' => 'Lapok hozzáadása kategóriából:',
'export-addcat'     => 'Hozzáadás',
'export-addnstext'  => 'Lapok hozzáadása ebből a névtérből:',
'export-addns'      => 'Hozzáadás',
'export-download'   => 'A fájlban történő mentés felkínálása',
'export-templates'  => 'Sablonok hozzáadása',
'export-pagelinks'  => 'Hivatkozott lapok hozzáadása, eddig a szintig:',

# Namespace 8 related
'allmessages'                   => 'Rendszerüzenetek',
'allmessagesname'               => 'Név',
'allmessagesdefault'            => 'Alapértelmezett szöveg',
'allmessagescurrent'            => 'Jelenlegi szöveg',
'allmessagestext'               => 'Ezen a lapon a MediaWiki-névtérben elérhető rendszerüzenetek listája látható.
Ha részt szeretnél venni a MediaWiki fordításában, látogass el a [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation], valamint a [http://translatewiki.net translatewiki.net] oldalra.',
'allmessagesnotsupportedDB'     => "A '''''{{ns:special}}:Allmessages''''' lap nem használható, mert a '''\$wgUseDatabaseMessages''' ki van kapcsolva.",
'allmessages-filter-legend'     => 'Elemek szűrése',
'allmessages-filter'            => 'Módosítás állapota:',
'allmessages-filter-unmodified' => 'nem módosított',
'allmessages-filter-all'        => 'összes',
'allmessages-filter-modified'   => 'módosított',
'allmessages-prefix'            => 'Előtag szerint:',
'allmessages-language'          => 'Nyelv:',
'allmessages-filter-submit'     => 'Szűrés',

# Thumbnails
'thumbnail-more'           => 'A kép nagyítása',
'filemissing'              => 'A fájl nincs meg',
'thumbnail_error'          => 'Hiba a bélyegkép létrehozásakor: $1',
'djvu_page_error'          => 'A DjVu lap a tartományon kívülre esik',
'djvu_no_xml'              => 'Nem olvasható ki a DjVu fájl XML-je',
'thumbnail_invalid_params' => 'Érvénytelen bélyegkép paraméterek',
'thumbnail_dest_directory' => 'Nem hozható létre a célkönyvtár',
'thumbnail_image-type'     => 'A képformátum nem támogatott',
'thumbnail_gd-library'     => 'A GD-könyvtár nincs megfelelően beállítva: a(z) $1 függvény hiányzik',
'thumbnail_image-missing'  => 'Úgy tűnik, hogy a fájl hiányzik: $1',

# Special:Import
'import'                     => 'Lapok importálása',
'importinterwiki'            => 'Transwiki importálása',
'import-interwiki-text'      => 'Válaszd ki az importálandó wikit és lapcímet.
A változatok dátumai és a szerkesztők nevei megőrzésre kerülnek.
Valamennyi transwiki importálási művelet az [[Special:Log/import|importálási naplóban]] kerül naplózásra.',
'import-interwiki-source'    => 'Forrás wiki/lap:',
'import-interwiki-history'   => 'A lap összes előzményváltozatainak másolása',
'import-interwiki-templates' => 'Az összes sablon hozzáadása',
'import-interwiki-submit'    => 'Importálás',
'import-interwiki-namespace' => 'Célnévtér:',
'import-upload-filename'     => 'Fájlnév:',
'import-comment'             => 'Megjegyzés:',
'importtext'                 => 'Kérjük, hogy a fájlt a forráswikiből a Special:Export segédeszköz használatával exportáld, mentsd a lemezedre, és töltsd ide föl.',
'importstart'                => 'Lapok importálása...',
'import-revision-count'      => '$1 {{PLURAL:$1|revision|változatok}}',
'importnopages'              => 'Nincs importálandó lap.',
'imported-log-entries'       => 'Importálva $1 logbejegyzés.',
'importfailed'               => 'Az importálás nem sikerült: $1',
'importunknownsource'        => 'Ismeretlen import forrástípus',
'importcantopen'             => 'Nem nyitható meg az importfájl',
'importbadinterwiki'         => 'Rossz wikiközi hivatkozás',
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
'import-upload'              => 'XML-adatok feltöltése',
'import-token-mismatch'      => 'Elveszett a session adat, próbálkozz újra.',
'import-invalid-interwiki'   => 'A kijelölt wikiből nem lehet importálni.',

# Import log
'importlogpage'                    => 'Importnapló',
'importlogpagetext'                => 'Lapok szerkesztési előzményekkel történő adminisztratív imporálása más wikikből.',
'import-logentry-upload'           => '[[$1]] importálása fájlfeltöltéssel kész',
'import-logentry-upload-detail'    => '{{PLURAL:$1|egy|$1}} változat',
'import-logentry-interwiki'        => '$1 más wikiből áthozva',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|egy|$1}} változat innen: $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'A szerkesztőlapod',
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
'tooltip-ca-addsection'           => 'Új szakasz nyitása',
'tooltip-ca-viewsource'           => 'Ez egy védett lap. Ide kattintva megnézheted a forrását.',
'tooltip-ca-history'              => 'A lap korábbi változatai',
'tooltip-ca-protect'              => 'A lap levédése',
'tooltip-ca-unprotect'            => 'Lapvédelem feloldása',
'tooltip-ca-delete'               => 'A lap törlése',
'tooltip-ca-undelete'             => 'A törölt lapváltozatok visszaállítása',
'tooltip-ca-move'                 => 'A lap áthelyezése',
'tooltip-ca-watch'                => 'A lap hozzáadása a figyelőlistádhoz',
'tooltip-ca-unwatch'              => 'A lap eltávolítása a figyelőlistádról',
'tooltip-search'                  => 'Keresés a wikin',
'tooltip-search-go'               => 'Ugrás a megadott lapra, ha létezik',
'tooltip-search-fulltext'         => 'Oldalak keresése a megadott szöveg alapján',
'tooltip-p-logo'                  => 'Kezdőlap',
'tooltip-n-mainpage'              => 'A kezdőlap felkeresése',
'tooltip-n-mainpage-description'  => 'A kezdőlap megtekintése',
'tooltip-n-portal'                => 'A közösségről, miben segíthetsz, mit hol találsz meg',
'tooltip-n-currentevents'         => 'Háttérinformáció az aktuális eseményekről',
'tooltip-n-recentchanges'         => 'A wikiben történt legutóbbi változtatások listája',
'tooltip-n-randompage'            => 'Egy véletlenszerűen kiválasztott lap betöltése',
'tooltip-n-help'                  => 'Ha bármi problémád van...',
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
'tooltip-rollback'                => '„Visszaállítás”: egy kattintással visszavonja az utolsó felhasználó egy vagy több szerkesztését.',
'tooltip-undo'                    => '„Visszavonás”: visszavonja ezt a szerkesztést, valamint megnyitja a szerkesztőt előnézet módban. A szerkesztési összefoglalóban meg lehet adni a visszavonás okát.',
'tooltip-preferences-save'        => 'Beállítások mentése',
'tooltip-summary'                 => 'Adj meg egy rövid összefoglalót',

# Stylesheets
'common.css'   => '/* Közös CSS az összes felületnek */',
'monobook.css' => '/* Az ide elhelyezett CSS hatással lesz a Monobook felület használóira */',
'vector.css'   => '/******************************************************************************************\\
*                   Ezek a stílusok csak a Vector felületre vonatkoznak                    *
*    A nem kifejezetten Vector-specifikus stílusokat a [[MediaWiki:Common.css]]-be írd!    *
\\******************************************************************************************/',

# Scripts
'common.js'   => '/* Az ide elhelyezett JavaScript kód minden felhasználó számára lefut az oldalak betöltésekor. */',
'monobook.js' => '/* A Monobook felületet használó szerkesztők számára betöltendő JavaScriptek */',
'vector.js'   => '/******************************************************************************************\\
*                   Ezek a szkriptek csak a Vector skin alatt futnak le.                   *
*    A nem kifejezetten Vector-specifikus szkripteket a [[MediaWiki:Common.js]]-be írd!    *
\\******************************************************************************************/',

# Metadata
'nodublincore'      => 'Ezen a kiszolgálón a Dublin Core RDF metaadatok használata letiltott.',
'nocreativecommons' => 'Ezen a kiszolgálón a Creative Commons RDF metaadatok használata letiltott.',
'notacceptable'     => 'A wiki kiszolgálója nem tudja olyan formátumban biztosítani az adatokat, amit a kliens olvasni tud.',

# Attribution
'anonymous'        => 'Névtelen {{SITENAME}}-{{PLURAL:$1|szerkesztő|szerkesztők}}',
'siteuser'         => '$1 {{SITENAME}}-felhasználó',
'anonuser'         => '$1 névtelen {{SITENAME}}-felhasználó',
'lastmodifiedatby' => 'Ezt a lapot utoljára $3 módosította $2, $1 időpontban.',
'othercontribs'    => '$1 munkája alapján.',
'others'           => 'mások',
'siteusers'        => '$1 {{SITENAME}}-{{PLURAL:$2|szerkesztő|szerkesztők}}',
'anonusers'        => '$1 névtelen {{PLURAL:$2|felhasználó|felhasználók}}',
'creditspage'      => 'A lap közreműködői',
'nocredits'        => 'Ennek a lapnak nincs közreműködői információja.',

# Spam protection
'spamprotectiontitle' => 'Spamszűrő',
'spamprotectiontext'  => 'Az általad elmenteni kívánt lap egyik külső hivatkozása fennakadt a spamszűrőn.
Ez valószínűleg egy olyan link miatt van, ami egy feketelistán lévő oldalra hivatkozik.',
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

# Skin names
'skinname-standard'    => 'Klasszikus',
'skinname-nostalgia'   => 'Nosztalgia',
'skinname-cologneblue' => 'Kölni kék',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Egyszerű',
'skinname-modern'      => 'Modern',

# Math options
'mw_math_png'    => 'Mindig készítsen PNG-t',
'mw_math_simple' => 'HTML, ha nagyon egyszerű, egyébként PNG',
'mw_math_html'   => 'HTML, ha lehetséges, egyébként PNG',
'mw_math_source' => 'Hagyja TeX formában (szöveges böngészőknek)',
'mw_math_modern' => 'Modern böngészőknek ajánlott beállítás',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Értelmezés sikertelen',
'math_unknown_error'    => 'ismeretlen hiba',
'math_unknown_function' => 'ismeretlen függvény',
'math_lexing_error'     => 'lexikai hiba',
'math_syntax_error'     => 'formai hiba',
'math_image_error'      => 'PNG-vé alakítás sikertelen; ellenőrizd, hogy a latex és dvipng (vagy dvips + gs + convert) helyesen van-e telepítve',
'math_bad_tmpdir'       => 'Nem írható vagy nem hozható létre a matematikai ideiglenes könyvtár',
'math_bad_output'       => 'Nem lehet létrehozni vagy írni a matematikai függvények kimeneti könyvtárába',
'math_notexvc'          => 'HIányzó texvc végrehajtható fájl; a beállítást lásd a math/README fájlban.',

# Patrolling
'markaspatrolleddiff'                 => 'Ellenőrzöttnek jelölöd',
'markaspatrolledtext'                 => 'Ellenőriztem',
'markedaspatrolled'                   => 'Ellenőrzöttnek jelölve',
'markedaspatrolledtext'               => 'A(z) [[:$1]] lap kiválasztott változatát ellenőrzöttnek jelölted.',
'rcpatroldisabled'                    => 'A friss változtatások járőrözése kikapcsolva',
'rcpatroldisabledtext'                => 'A friss változtatások ellenőrzése jelenleg nincs engedélyezve.',
'markedaspatrollederror'              => 'Nem lehet ellenőrzöttnek jelölni',
'markedaspatrollederrortext'          => 'Meg kell adnod egy ellenőrzöttként megjelölt változatot.',
'markedaspatrollederror-noautopatrol' => 'A saját változtatásaid megjelölése ellenőrzöttként nem engedélyezett.',

# Patrol log
'patrol-log-page'      => 'Ellenőrzési napló',
'patrol-log-header'    => 'Ez az ellenőrzött változatok naplója.',
'patrol-log-line'      => 'ellenőrzöttnek jelölte a(z) $2 $1 $3',
'patrol-log-auto'      => '(automatikus)',
'patrol-log-diff'      => '$1 azonosítójú változatát',
'log-show-hide-patrol' => 'járőrnapló $1',

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
'previousdiff' => '← Régebbi szerkesztés',
'nextdiff'     => 'Újabb szerkesztés →',

# Media information
'mediawarning'         => "'''Figyelmeztetés''': Ez a fájltípus kártékony kódot tartalmazhat.
A futtatása során kárt tehet a számítógépedben.",
'imagemaxsize'         => "A képek mérete, legfeljebb:<br />''(a leírólapokon)''",
'thumbsize'            => 'Bélyegkép mérete:',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|egy|$3}} oldal',
'file-info'            => 'fájlméret: $1, MIME-típus: $2',
'file-info-size'       => '$1 × $2 képpont, fájlméret: $3, MIME-típus: $4',
'file-nohires'         => '<small>Nem érhető el nagyobb felbontású változat.</small>',
'svg-long-desc'        => 'SVG fájl, névlegesen $1 × $2 képpont, fájlméret: $3',
'show-big-image'       => 'A kép nagyfelbontású változata',
'show-big-image-thumb' => '<small>Az előnézet mérete: $1 × $2 képpont</small>',
'file-info-gif-looped' => 'ismétlődik',
'file-info-gif-frames' => '{{PLURAL:$1|egy|$1}} képkocka',
'file-info-png-looped' => 'ismétlődik',
'file-info-png-repeat' => 'lejátszva {{PLURAL:$1|egy|$1}} alkalommal',
'file-info-png-frames' => '{{PLURAL:$1|egy|$1}} képkocka',

# Special:NewFiles
'newimages'             => 'Új fájlok galériája',
'imagelisttext'         => "Lentebb '''{{PLURAL:$1|egy|$1}}''' kép látható, $2 rendezve.",
'newimages-summary'     => 'Ezen a speciális lapon láthatóak a legutóbb feltöltött fájlok.',
'newimages-legend'      => 'Fájlnév',
'newimages-label'       => 'Fájlnév (vagy annak részlete):',
'showhidebots'          => '(botok szerkesztéseinek $1)',
'noimages'              => 'Nem tekinthető meg semmi.',
'ilsubmit'              => 'Keresés',
'bydate'                => 'dátum szerint',
'sp-newimages-showfrom' => 'Új fájlok mutatása $1 $2 után',

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
* isospeedratings
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

'exif-orientation-1' => 'Normál',
'exif-orientation-2' => 'Vízszintesen tükrözött',
'exif-orientation-3' => 'Elforgatott 180°',
'exif-orientation-4' => 'Függőlegesen tükrözött',
'exif-orientation-5' => 'Elforgatott 90° ÓE és függőlegesen tükrözött',
'exif-orientation-6' => 'Elforgatott 90° ÓSZ',
'exif-orientation-7' => 'Elforgatott 90° ÓSZ és függőlegesen tükrözött',
'exif-orientation-8' => 'Elforgatott 90° ÓE',

'exif-planarconfiguration-1' => 'Egyben',
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

# Flash modes
'exif-flash-fired-0'    => 'A vaku nem sült el',
'exif-flash-fired-1'    => 'A vaku elsült',
'exif-flash-return-0'   => 'Nincs strobe return detection funkció.',
'exif-flash-return-2'   => 'strobe return light nincs érzékelve',
'exif-flash-return-3'   => 'strobe return light érzékelve',
'exif-flash-mode-1'     => 'Kötelező vaku',
'exif-flash-mode-2'     => 'Kötelező vakuelnyomás',
'exif-flash-mode-3'     => 'automatikus mód',
'exif-flash-function-1' => 'Nincs vakufunkció',
'exif-flash-redeye-1'   => 'Vörös szem eltávolító mód',

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

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilométer óránként',
'exif-gpsspeed-m' => 'Márföld óránként',
'exif-gpsspeed-n' => 'Csomó',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Igazi irány',
'exif-gpsdirection-m' => 'Mágneses irány',

# External editor support
'edit-externally'      => 'A fájl szerkesztése külső alkalmazással',
'edit-externally-help' => '(Lásd a [http://www.mediawiki.org/wiki/Manual:External_editors használati utasítást] (angolul) a beállításához.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'összes',
'imagelistall'     => 'összes',
'watchlistall2'    => 'bármikor',
'namespacesall'    => 'Összes',
'monthsall'        => 'mind',
'limitall'         => 'mind',

# E-mail address confirmation
'confirmemail'              => 'E-mail cím megerősítése',
'confirmemail_noemail'      => 'Nincs érvényes e-mail cím megadva a [[Special:Preferences|beállításaidnál]].',
'confirmemail_text'         => 'Meg kell erősítened az e-mail címed, mielőtt használhatnád a(z) {{SITENAME}} levelezési rendszerét. Nyomd meg az alsó gombot, hogy kaphass egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot. Töltsd be a kódot a böngésződbe, hogy aktiválhasd az e-mail címedet.',
'confirmemail_pending'      => 'A megerősítő kódot már elküldtük neked e-mailben, kérjük, várj türelemmel, amíg a szükséges adatok megérkeznek az e-mailcímedre, és csak akkor kérj új kódot, ha valami technikai malőr folytán értelmes időn belül nem kapod meg a levelet.',
'confirmemail_send'         => 'Küldd el a kódot',
'confirmemail_sent'         => 'Kaptál egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot.',
'confirmemail_oncreate'     => 'A megerősítő kódot elküldtük az e-mail címedre.
Ez a kód nem szükséges a belépéshez, de meg kell adnod,
mielőtt a wiki e-mail alapú szolgáltatásait igénybe veheted.',
'confirmemail_sendfailed'   => 'Nem sikerült elküldeni a megerősítő e-mailt.
Ellenőrizd, hogy nem írtál-e érvénytelen karaktert a címbe.

A levelező üzenete: $1',
'confirmemail_invalid'      => 'Nem megfelelő kód. A kódnak lehet, hogy lejárt a felhasználhatósági ideje.',
'confirmemail_needlogin'    => 'Meg kell $1 erősíteni az e-mail címedet.',
'confirmemail_success'      => 'Az e-mail címed megerősítve. Most már beléphetsz a wikibe.',
'confirmemail_loggedin'     => 'E-mail címed megerősítve.',
'confirmemail_error'        => 'Hiba az e-mail címed megerősítése során.',
'confirmemail_subject'      => '{{SITENAME}} e-mail cím megerősítés',
'confirmemail_body'         => 'Valaki, valószínűleg te, ezzel az e-mail címmel regisztrált
"$2" néven a(z) {{SITENAME}} wikin, a(z) $1 IP-címről.

Annak érdekében, hogy megerősítsd, ez az azonosító valóban hozzád tartozik,
és hogy aktiváld az e-mail címedet, nyisd meg az alábbi linket a böngésződben:

$3

Ha ez *nem* te vagy, kattints erre a linkre az
e-mail cím megerősíthetőségének visszavonásához:

$5

A megerősítésre szánt kód felhasználhatósági idejének lejárata: $4.',
'confirmemail_body_changed' => 'Valaki (vélhetően te, a(z) $1 IP-címről) megváltoztatta a(z) „$2” felhasználói fiók email címét a {{SITENAME}} wikin erre a címre.

Annak érdekében, hogy megerősítsd, ez az azonosító valóban hozzád tartozik,
és hogy újra aktiváld az e-mail címedet, nyisd meg az alábbi linket a böngésződben:

$3

Ha ez *nem* te vagy, kattints erre a linkre az
e-mail cím megerősíthetőségének visszavonásához:

$5

A megerősítésre szánt kód felhasználhatósági idejének lejárata: $4.',
'confirmemail_invalidated'  => 'E-mail-cím megerősíthetősége visszavonva',
'invalidateemail'           => 'E-mail-cím megerősíthetőségének visszavonása',

# Scary transclusion
'scarytranscludedisabled' => '[Wikiközi beillesztés le van tiltva]',
'scarytranscludefailed'   => '[$1 sablon letöltése sikertelen]',
'scarytranscludetoolong'  => '[Az URL túl hosszú]',

# Trackbacks
'trackbackbox'      => 'Visszakövetések ehhez a szócikkhez:<br />
$1',
'trackbackremove'   => '([$1 törlése])',
'trackbacklink'     => 'Visszakövetés',
'trackbackdeleteok' => 'A visszakövetés törlése sikerült.',

# Delete conflict
'deletedwhileediting' => "'''Figyelmeztetés:''' A lapot a szerkesztés megkezdése után törölték!",
'confirmrecreate'     => "Miután elkezdted szerkeszteni, [[User:$1|$1]] ([[User talk:$1|vita]]) törölte ezt a lapot a következő indokkal:
: ''$2''
Kérlek erősítsd meg, hogy tényleg újra akarod-e írni a lapot.",
'recreate'            => 'Újraírás',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Törlöd az oldal gyorsítótárban (cache) található változatát?',
'confirm-purge-bottom' => 'A lap ürítésével törlődik annak gyorsítótárazott változata, és a legújabb tartalom fog megjelenni.',

# Separators for various lists, etc.
'ellipsis' => '…',

# Multipage image navigation
'imgmultipageprev' => '← előző oldal',
'imgmultipagenext' => 'következő oldal →',
'imgmultigo'       => 'Menj',
'imgmultigoto'     => 'Ugrás a(z) $1. oldalra',

# Table pager
'ascending_abbrev'         => 'növ',
'descending_abbrev'        => 'csökk',
'table_pager_next'         => 'Következő oldal',
'table_pager_prev'         => 'Előző oldal',
'table_pager_first'        => 'Első oldal',
'table_pager_last'         => 'Utolsó oldal',
'table_pager_limit'        => 'Laponként $1 tétel megjelenítése',
'table_pager_limit_label'  => 'Elemek száma oldalanként:',
'table_pager_limit_submit' => 'Ugrás',
'table_pager_empty'        => 'Nincs találat',

# Auto-summaries
'autosumm-blank'   => 'Eltávolította a lap teljes tartalmát',
'autosumm-replace' => 'A lap tartalmának cseréje erre: $1',
'autoredircomment' => 'Átirányítás ide: [[$1]]',
'autosumm-new'     => 'Új oldal, tartalma: „$1”',

# Live preview
'livepreview-loading' => 'Betöltés…',
'livepreview-ready'   => 'Betöltés… Kész!',
'livepreview-failed'  => 'Az élő előnézet nem sikerült! Próbálkozz a normál előnézettel.',
'livepreview-error'   => 'A csatlakozás nem sikerült: $1 "$2". Próbálkozz a normál előnézettel.',

# Friendlier slave lag warnings
'lag-warn-normal' => '{{PLURAL:$1|Az egy|A(z) $1}} másodpercnél frissebb szerkesztések nem biztos, hogy megjelennek ezen a listán.',
'lag-warn-high'   => 'Az adatbázisszerver túlterheltsége miatt {{PLURAL:$1|az egy|a(z) $1}} másodpercnél frissebb változtatások nem biztos, hogy megjelennek ezen a listán.',

# Watchlist editor
'watchlistedit-numitems'       => 'A figyelőlistádon {{PLURAL:$1|egy|$1}} cím szerepel (a vitalapok nélkül).',
'watchlistedit-noitems'        => 'A figyelőlistád üres.',
'watchlistedit-normal-title'   => 'A figyelőlista szerkesztése',
'watchlistedit-normal-legend'  => 'Lapok eltávolítása a figyelőlistáról',
'watchlistedit-normal-explain' => 'A figyelőlistádra felvett lapok címei alább láthatóak.
Ha el szeretnél távolítani egy címet, pipáld ki a mellette található jelölőnégyzetet, majd kattints „{{int:Watchlistedit-normal-submit}}” gombra.
Lehetőséged van a [[Special:Watchlist/raw|figyelőlista nyers változatának]] szerkesztésére is.',
'watchlistedit-normal-submit'  => 'A kijelöltek eltávolítása',
'watchlistedit-normal-done'    => '{{PLURAL:$1|A következő|A következő $1}} cikket eltávolítottam a figyelőlistádról:',
'watchlistedit-raw-title'      => 'A nyers figyelőlista szerkesztése',
'watchlistedit-raw-legend'     => 'A nyers figyelőlista szerkesztése',
'watchlistedit-raw-explain'    => 'A figyelőlistádra felvett lapok az alábbi listában találhatók. A lista szerkeszthető;
minden egyes sor egy figyelt lap címe. Ha kész vagy, kattints a lista alatt található
„{{int:Watchlistedit-raw-submit}}” feliratú gombra. Használhatod a [[Special:Watchlist/edit|hagyományos listaszerkesztőt]] is.',
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
'duplicate-defaultsort' => 'Figyelem: a(z) „$2” rendezőkulcs felülírja a korábbit („$1”).',

# Special:Version
'version'                          => 'Névjegy',
'version-extensions'               => 'Telepített kiterjesztések',
'version-specialpages'             => 'Speciális lapok',
'version-parserhooks'              => 'Értelmező hookok',
'version-variables'                => 'Változók',
'version-skins'                    => 'Felületek',
'version-other'                    => 'Egyéb',
'version-mediahandlers'            => 'Médiafájl-kezelők',
'version-hooks'                    => 'Hookok',
'version-extension-functions'      => 'A kiterjesztések függvényei',
'version-parser-extensiontags'     => 'Az értelmező kiterjesztéseinek tagjei',
'version-parser-function-hooks'    => 'Az értelmező függvényeinek hookjai',
'version-skin-extension-functions' => 'Felület kiterjeszések függvényei',
'version-hook-name'                => 'Hook neve',
'version-hook-subscribedby'        => 'Használja',
'version-version'                  => '(verzió: $1)',
'version-license'                  => 'Licenc',
'version-poweredby-credits'        => "Ez a wiki '''[http://www.mediawiki.org/ MediaWiki]''' szoftverrel működik, copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'mások',
'version-license-info'             => 'A MediaWiki szabad szoftver, terjeszthető és / vagy módosítható a GNU General Public License alatt, amit a Free Software Foundation közzétett; vagy a 2-es verziójú licenc, vagy (az Ön választása alapján) bármely későbbi verzió szerint. 

A MediaWikit abban a reményben terjesztjük, hogy hasznos lesz, de GARANCIA NÉLKÜL, anélkül, hogy PIACKÉPES vagy HASZNÁLHATÓ LENNE EGY ADOTT CÉLRA. Lásd a GNU General Public License-t a további részletekért. 

Önnek kapnia kellett [{{SERVER}}{{SCRIPTPATH}}/COPYING egy példányt a GNU General Public License-ből] ezzel a programmal együtt, ha nem, írjon a Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA címre vagy [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html olvassa el online].',
'version-software'                 => 'Telepített szoftverek',
'version-software-product'         => 'Termék',
'version-software-version'         => 'Verzió',

# Special:FilePath
'filepath'         => 'Fájlelérés',
'filepath-page'    => 'Fájl:',
'filepath-submit'  => 'Elérési út',
'filepath-summary' => 'Ezen lap segítségével lekérheted egy adott fájl pontos útvonalát. A képek teljes méretben jelennek meg, más fájltípusok közvetlenül a hozzájuk rendelt programmal indulnak el.

Add meg a fájlnevet a „{{ns:file}}:” prefixum nélkül.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Duplikátumok keresése',
'fileduplicatesearch-summary'  => 'Fájlok duplikátumainak keresése hash értékük alapján.',
'fileduplicatesearch-legend'   => 'Duplikátum keresése',
'fileduplicatesearch-filename' => 'Fájlnév:',
'fileduplicatesearch-submit'   => 'Keresés',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Fájlméret: $3<br />MIME-típus: $4',
'fileduplicatesearch-result-1' => 'A(z) „$1“ nevű fájlnak nincs duplikátuma.',
'fileduplicatesearch-result-n' => 'A(z) „$1” nevű fájlnak {{PLURAL:$2|egy|$2}} duplikátuma van.',

# Special:SpecialPages
'specialpages'                   => 'Speciális lapok',
'specialpages-note'              => '----
* Mindenki számára elérhető speciális lapok.
* <strong class="mw-specialpagerestricted">Korlátozott hozzáférésű speciális lapok.</strong>',
'specialpages-group-maintenance' => 'Állapotjelentések',
'specialpages-group-other'       => 'További speciális lapok',
'specialpages-group-login'       => 'Bejelentkezés / fiók létrehozása',
'specialpages-group-changes'     => 'Friss változások, naplók',
'specialpages-group-media'       => 'Médiafájlok, feltöltések',
'specialpages-group-users'       => 'Szerkesztők és jogaik',
'specialpages-group-highuse'     => 'Gyakran használt lapok',
'specialpages-group-pages'       => 'Listák',
'specialpages-group-pagetools'   => 'Eszközök',
'specialpages-group-wiki'        => 'A wiki adatai és eszközei',
'specialpages-group-redirects'   => 'Átirányító speciális lapok',
'specialpages-group-spam'        => 'Spam eszközök',

# Special:BlankPage
'blankpage'              => 'Üres lap',
'intentionallyblankpage' => 'Ez a lap szándékosan maradt üresen',

# External image whitelist
'external_image_whitelist' => ' #Ezt a sort hagyd pontosan így, ahogy van<pre>
#Ide reguláris kifejezéseket írhatsz (azon részüket, amik a // közé mennek)
#Ezek egyeztetve lesznek a külső képek URL-jeivel
#Egyezés esetén képként fognak megjelenni, egyébként csak link fog rájuk mutatni
#A #-tel kezdődő sorok megjegyzésnek számítanak
#A kis- és nagybetűk nincsenek megkülönböztetve

#A reguláris kifejezéseket ezen sor alá írd. Ezt a sort hagyd így, ahogy van.</pre>',

# Special:Tags
'tags'                    => 'Érvényes módosítási címkék',
'tag-filter'              => '[[Special:Tags|Címke]]szűrő:',
'tag-filter-submit'       => 'Szűrő',
'tags-title'              => 'Címkék',
'tags-intro'              => 'Ez a lap azokat a címkéket és jelentéseiket tartalmazza, amikkel a szoftver megjelölhet egy szerkesztést.',
'tags-tag'                => 'Címke neve',
'tags-display-header'     => 'Megjelenése a listákon',
'tags-description-header' => 'Teljes leírás',
'tags-hitcount-header'    => 'Címkézett változtatások',
'tags-edit'               => 'szerkesztés',
'tags-hitcount'           => '{{PLURAL:$1|Egy|$1}} változtatás',

# Special:ComparePages
'comparepages'     => 'Lapok összehasonlítása',
'compare-selector' => 'Lapváltozatok összehasonlítása',
'compare-page1'    => '1. lap',
'compare-page2'    => '2. lap',
'compare-rev1'     => '1. változat',
'compare-rev2'     => '2. változat',
'compare-submit'   => 'Összehasonlítás',

# Database error messages
'dberr-header'      => 'A wikivel problémák vannak',
'dberr-problems'    => 'Sajnáljuk, de az oldallal technikai problémák vannak.',
'dberr-again'       => 'Várj néhány percet, majd frissítsd az oldalt.',
'dberr-info'        => '(Nem sikerült kapcsolatot létesíteni az adatbázisszerverrel: $1)',
'dberr-usegoogle'   => 'A probléma elmúlásáig próbálhatsz keresni a Google-lel.',
'dberr-outofdate'   => 'Fontos tudnivaló, hogy az oldal tartalmáról készített indexeik elavultak lehetnek.',
'dberr-cachederror' => 'Lenn a kért oldal gyorsítótárazott változata látható, és lehet, hogy nem teljesen friss.',

# HTML forms
'htmlform-invalid-input'       => 'Probléma van az általad megadott értékkel',
'htmlform-select-badoption'    => 'A megadott érték nem érvényes.',
'htmlform-int-invalid'         => 'A megadott érték nem szám.',
'htmlform-float-invalid'       => 'A megadott érték nem szám.',
'htmlform-int-toolow'          => 'A megadott érték a minimum, $1 alatt van',
'htmlform-int-toohigh'         => 'A megadott érték a maximum, $1 felett van',
'htmlform-required'            => 'Az érték megadása kötelező',
'htmlform-submit'              => 'Elküldés',
'htmlform-reset'               => 'Változtatások visszavonása',
'htmlform-selectorother-other' => 'egyéb',

# SQLite database support
'sqlite-has-fts' => '$1 teljes szöveges keresés támogatással',
'sqlite-no-fts'  => '$1 teljes szöveges keresés támogatása nélkül',

# Special:DisableAccount
'disableaccount'             => 'Felhasználói fiók letiltása',
'disableaccount-user'        => 'Felhasználónév:',
'disableaccount-reason'      => 'Ok:',
'disableaccount-mustconfirm' => 'Meg kell erősítened, hogy biztosan le szeretnéd tiltani ezt a fiókot.',
'disableaccount-nosuchuser'  => 'Nem létezik „$1” nevű felhasználói fiók.',
'disableaccount-success'     => '„$1” felhasználói fiókja véglegesen le lett tiltva.',
'disableaccount-logentry'    => 'véglegesen letiltotta [[$1]] felhasználói fiókját',

);
